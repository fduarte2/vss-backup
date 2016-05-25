<?
// File: run_invoice_process.php
//
// Lynn F. Wang  (05-DEC-03)
// This program generates a PDF file of the invoices of the selected billing type.

// check user authorization
include("pow_session.php");
$user = $userdata['username'];

// make sure the script has enough time to run
set_time_limit(0);

// connection variables
include("connect.php");

// Get form data
$billing_type = $HTTP_POST_VARS["selected_type"];

if ( $billing_type == "Advance Billing" )
{
	$lr_num=$HTTP_POST_VARS["AB_LR_NUM"];
}
elseif ($billing_type == "Dockage/Lines")
{
	$lr_num=$HTTP_POST_VARS["DL_LR_NUM"];	
}
elseif ($billing_type == "Miscellaneous")
{
	$lr_num=$HTTP_POST_VARS["MISC_LR_NUM"];
}
elseif ($billing_type == "Wharfage")
{
	$lr_num=$HTTP_POST_VARS["WF_LR_NUM"];
}
else 
{
	$lr_num="NA";
}
// include some useful functions
include("billing_functions.php");
$today = date('m/d/Y');

// Make Oracle Database connection
$bni_conn = ora_logon("SAG_OWNER@$bni", "SAG");
//$bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
if (!$bni_conn) {
  printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($bni_conn));
  printf("Please report to TS!\n");
  exit;
}

$rf_conn = ora_logon("PAPINET@$rf", "OWNER");
if (!$rf_conn) {
  printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
  printf("Please report to TS!\n");
  exit;
}

// turn auto-commit off
$stmt = "ora_commitoff";
$ora_success = ora_commitoff($bni_conn);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_commitoff($rf_conn);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

// open up two cursors over this connection
$bni_cursor1 = ora_open($bni_conn);
$bni_cursor2 = ora_open($bni_conn);
$bni_cursor3 = ora_open($bni_conn);
$rf_cursor = ora_open($rf_conn);
$short_term_cursor = ora_open($bni_conn);

// lock billing table so the invoice numbers won't get messed up
$stmt = "lock table billing in exclusive mode nowait";
$ora_success = ora_parse($bni_cursor2, $stmt);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_exec($bni_cursor2);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

// retrieve all invoice records for the given billing type
//$stmt = getQueryByType($billing_type);
$stmt = getQueryByType($billing_type, $lr_num);
//echo "sql: ".$stmt."<br>type: ".$billing_type."<br>";
$ora_success = ora_parse($bni_cursor1, $stmt);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_exec($bni_cursor1);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

ora_fetch_into($bni_cursor1, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows = ora_numrows($bni_cursor1);

if ($rows <= 0) {
  header("Location: run_invoice.php?type=$billing_type");
  exit;
}

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','portrait');
$pdf->openHere('XYZ', 0, 800, 1.25);
$pdf -> ezSetMargins(20,20,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

// start to keep track of page numbers
$bill_page = $pdf->ezStartPageNumbers(575, 695, 10, '', '', 1);

$sub_total = 0;       // total of a section of a invoice, like one labor ticket or billing type
$total = 0;           // invoice total
$grand_total = 0;     // grand total of all invoice
$first_entry = true;  // the very first entry fetched from DB

$curr_cust_id = trim($row['CUSTOMER_ID']);
$curr_subcust_id = trim($row['SUB_CUST_NUM']);
$curr_lr_num = trim($row['LR_NUM']);
$curr_dole_comm_newinv = trim($row['COMMODITY_CODE']);
$curr_bill_type = trim($row['BILLING_TYPE']);
$curr_service_date = date("m/d/y", strtotime($row['SERVICE_DATE']));
$curr_service_start = date("m/d/y", strtotime($row['SERVICE_START']));
$curr_service_stop = date("m/d/y", strtotime($row['SERVICE_STOP']));
$curr_labor_ticket = trim($row['LABOR_TICKET_NUM']);

// get current date information
if (($curr_bill_type == 'STORAGE') ||($curr_bill_type == 'H_STORAGE') || 
    ($curr_bill_type == 'DOCKAGE') || ($curr_bill_type == 'LINES')) {
  $curr_date = $curr_service_start . " - " . $curr_service_stop;
} else {
  $curr_date = $curr_service_date;
}

// get the beginning invoice # for the processed month
$invoice_num = getInvoiceNumber($bni_conn, $bni_cursor2);
$start_inv_num = $invoice_num;

// start to build the header
$header = $pdf->openObject();
$pdf->ezSetY(740);

$order_list_for_printing = array();

// process one entry at a time
do {
  $cust_id = trim($row['CUSTOMER_ID']);
  $subcust_id = trim($row['SUB_CUST_NUM']);
  $dole_comm_newinv = trim($row['COMMODITY_CODE']);
  $lr_num = trim($row['LR_NUM']);
  $bill_type = trim($row['BILLING_TYPE']);
  $service_date = date("m/d/y", strtotime($row['SERVICE_DATE']));
  $service_start = date("m/d/y", strtotime($row['SERVICE_START']));
  $service_stop = date("m/d/y", strtotime($row['SERVICE_STOP']));
  $labor_ticket = trim($row['LABOR_TICKET_NUM']);

  // Get the customer that have specific format
  $sql = "select * from billing_format where customer_id = $cust_id and billing_type = 'Advance Billing'";
  ora_parse($bni_cursor3, $sql);
  ora_exec($bni_cursor3);

  if(ora_fetch($bni_cursor3))
    $format_custid = $row['CUSTOMER_ID'];
  else
    $format_custid = 0;


  // to determin whether a new invoice need to be generated
  // 1. LABOR need to generate new invoice for each (customer, vessel, service_date)
  // 2. Advance Vessel Billing combine Advance Truck Loading, Backhaul, and Terminal Service together
  // 3. Dockate/Lines combine Dockage and Lines together
  // 4. other types need new invoice for each (customer, vessel), except dole paper misc bills, which are just customer.
  if ($billing_type == 'Labor') {
//    if (($cust_id == $curr_cust_id) && ($lr_num == $curr_lr_num) && ($curr_service_date == $service_date)) {
    if (($cust_id == $curr_cust_id) && ($lr_num == $curr_lr_num)) {
      $new_invoice = false;
    } else {
      $new_invoice = true;
    }
  } 
  elseif ($billing_type == 'Advance Billing') {
    if ($cust_id == $format_custid) //separate invoice for customer 1601 by each service type
    {
      if(($cust_id == $curr_cust_id) && ($lr_num == $curr_lr_num) && ($bill_type == $curr_bill_type)) {
        $new_invoice = false;
       } else {
        $new_invoice = true;
      }
    }
    else
    {
      if(($cust_id == $curr_cust_id) && ($lr_num == $curr_lr_num) && ($curr_service_date == $service_date)) {
      $new_invoice = false;
    } else {
      $new_invoice = true;
    }
    }
  }
  else {
     if((($cust_id == $curr_cust_id) && ($lr_num == $curr_lr_num)) || ($cust_id == $curr_cust_id) && ($curr_dole_comm_newinv == $dole_comm_newinv) && 
		($billing_type == "Miscellaneous") && ($row['COMMODITY_CODE'] == "1272" || $row['COMMODITY_CODE'] == "1299") && ($row['SERVICE_CODE'] == "6221" || $row['SERVICE_CODE'] == "6211")) {
//   if((($cust_id == $curr_cust_id) && ($lr_num == $curr_lr_num)) || ($cust_id == $curr_cust_id) && ($billing_type == "Miscellaneous") && ($row['COMMODITY_CODE'] == "1272" || $row['COMMODITY_CODE'] == "1299") && ($row['SERVICE_CODE'] == "6221")) {
      $new_invoice = false;
    } else {
      $new_invoice = true;
    }
  }

  // determine service date and the widths of the columns
  // 1. Storage and Dockage bills need both start date and end date so more space for the DATE column
  // 2. Lines bills are a little different: It takes the service start date intead of service date
  // 3. First entry of a new invoice, new labor ticket, or new billing type alwasy shows the date.
  //    Then only if date changes, will it shows
  if (($bill_type == 'STORAGE') || ($bill_type == 'H_STORAGE') || 
      ($bill_type == 'DOCKAGE') || ($bill_type == 'LINES')){
    $date = $service_start . " - " . $service_stop;
    $date_width = 110;
    $desc_width = 368;
  } else {
    $date = $service_date;
    $date_width = 60;
    $desc_width = 418;
  }

  // to determin whether a new section of an invoice need to be generated; there will be a subtotal after
  // each section of an invoice
  // 1. LABOR has a new section for each labor ticket #
  // 2. Advance Vessel Billing has one section for each billing type, including Advance Truck Loading, 
  //    Backhaul, and Terminal Service together; do not repeat section header when billing type is the same
  // 3. Dockate/Lines has one section for each billing type, i.e., Dockage and Lines
  // 4. other types don't have subsections.
  $new_section = false;
  $show_section_header = false;  // used only by advance vessel billing
  if ($billing_type == 'Labor') {
	if (($labor_ticket != $curr_labor_ticket) || ($date != $curr_date)) {
      $new_section = true;
      $curr_labor_ticket = $labor_ticket;
      $curr_date = $service_date;
    }
  } elseif($billing_type == 'Advance Billing') {
    if (($date != $curr_date) || ($bill_type != $curr_bill_type)) {
      $new_section = true;

      if ($bill_type != $curr_bill_type) {
	$show_section_header = true;
      }

      $curr_date = $date;
      $curr_bill_type = $bill_type;
    }
  } elseif ($billing_type == 'Dockage/Lines') {
    if ($bill_type != $curr_bill_type) {
      $new_section = true;
      $curr_date = $date;
      $curr_bill_type = $bill_type;
    }
  } elseif(($billing_type == 'Miscellaneous') && ($row['COMMODITY_CODE'] == "1272" || $row['COMMODITY_CODE'] == "1299")) {
    if ($subcust_id != $curr_subcust_id) {
      $new_section = true;
      $curr_date = $date;
	}
  }

  // get billing information.
  // we need to decide on the value of $new_invoice, $new_section before we call makeDescription
  $amount = "$" . number_format($row['SERVICE_AMOUNT'], 2, '.', ',');
  $desc = makeDescription($bni_cursor2, $row, $bill_type);
  $charge_title = getChargeTitle($billing_type);
  // HARD CODING ALERT:  Dole dock ticket paper Misc charges get their own special title.  Replace whatever the above generated with...
  if(($billing_type == "Miscellaneous") && ($row['COMMODITY_CODE'] == "1272" || $row['COMMODITY_CODE'] == "1299") && ($row['SERVICE_CODE'] == "6221" || $row['SERVICE_CODE'] == "6211")){
	  $charge_title = "TRUCK UNLOADING CHARGE";
  }
  if(($billing_type == "FumInsp") && $row['CUSTOMER_ID'] == "1803"){
	  $charge_title = "FUMIGATION";
  }
  
  // backup the date, in case we need to show the date
  $date1 = $date;  
  if (!$first_entry && !$new_invoice && !$new_section) {
    if ($date == $curr_date) {
      $date = "";
    } else {
      $curr_date = $date;
    }
  } else {
    // Labor bills have the date on the same line as the title
    if ($billing_type == 'Labor') {
      $amount = "\n\n" . $amount;
    } elseif ($billing_type == 'Dockage/Lines' || 
	      ($billing_type == 'Advance Billing' && ($first_entry || $new_invoice || $show_section_header))) {
      // these bills have a title and empty line before the 1st entry shows up
      $amount = "\n\n" . $amount;
      $date = "\n\n" . $date;
    }
  }

  // write subtotol and start a new page if it is a new bill
  if (!$new_invoice) {
    // continue on the same pre-invoice
    $total += $row['SERVICE_AMOUNT'];

    if ($billing_type =="S-Wharfage")
        $qty_total += $row['SERVICE_QTY'];

    // write sub-total line for the finished section for Labor, Advance Billing and Dockage/Lines
    if (!$new_section) {
      $sub_total += $row['SERVICE_AMOUNT'];
    } else {
      $record = array();
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>'__________'));
	  if(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
		  array_push($record, array('first'=>'', 'second'=>"<b>Subtotal for Mill:      ".GetCustomerName($curr_subcust_id, $bni_conn)."</b>", 'third'=>"<b>".number_format($sub_total, 2, '.', ',')."</b>"));
	  } else {
		array_push($record, array('first'=>'', 'second'=>'Subtotal:', 
				'third'=>'$' . number_format($sub_total, 2, '.', ',')));
	  }
	  if(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
		  array_push($record, array('first'=>'________', 'second'=>"_________________________________________________________________________", 'third'=>'____________'));
	  }
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
/*	  if(($billing_type == 'Miscellaneous') && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
		  array_push($record, array('first'=>'', 'second'=>"Subtotal for Mill:      ".GetCustomerName($curr_subcust_id, $ora_conn), 'third'=>''));
	  }
      array_push($record, array('first'=>'', 'second'=>'Subtotal:', 
				'third'=>'$' . number_format($sub_total, 2, '.', ',')));
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
   */   
      // the wording "Subtotal" is to be right-aligned
      $is_total = true;
      writeTheRecord();
      $is_total = false;

      // update subtotal
      $sub_total = $row['SERVICE_AMOUNT'];
	  $curr_subcust_id = $subcust_id;
    }
  } else {
    // need to start a new invoice for the fetched row
    $invoice_num++;

    // write sub-total line for the last section for Labor, Advance Billing and Dockage/Lines
//    if ($billing_type == 'Labor' || $billing_type == 'Advance Billing' || $billing_type == 'Dockage/Lines') {
    if ($billing_type == 'Labor' || $billing_type == 'Advance Billing' || $billing_type == 'Dockage/Lines' || 
			(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299"))) {
      $record = array();
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>'__________'));
	  if(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
		  array_push($record, array('first'=>'', 'second'=>"<b>Subtotal for Mill:      ".GetCustomerName($curr_subcust_id, $bni_conn)."</b>", 'third'=>"<b>".number_format($sub_total, 2, '.', ',')."</b>"));
	  } else {
		array_push($record, array('first'=>'', 'second'=>'Subtotal:', 
				'third'=>'$' . number_format($sub_total, 2, '.', ',')));
	  }
	  if(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
		  array_push($record, array('first'=>'________', 'second'=>"_________________________________________________________________________", 'third'=>'____________'));
	  }
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
/*	  if(($billing_type == 'Miscellaneous') && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
		  array_push($record, array('first'=>'', 'second'=>"Subtotal for Mill:      ".GetCustomerName($curr_subcust_id, $ora_conn), 'third'=>''));
	  }
      array_push($record, array('first'=>'', 'second'=>'Subtotal:', 
				'third'=>'$' . number_format($sub_total, 2, '.', ',')));
      array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
   */   
      // the wording "Subtotal" is to be right-aligned
      $is_total = true;
      writeTheRecord();
      $is_total = false;
    }

    // write the total line
    $record = array();
    array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
    array_push($record, array('first'=>'', 'second'=>'Invoice Total:', 
			      'third'=>'$' . number_format($total, 2, '.', ',')));

    // the wording "Total" is to be right-aligned
    $is_total = true;
    writeTheRecord();
    $is_total = false;

	// pallet list if applicable
	if(sizeof($order_list_for_printing) > 0 && !$first_entry){
		DisplayPalletList($order_list_for_printing, $general_info, $curr_cust_id, $ora_conn);
		$order_list_for_printing = array();
	}

    // prepairation for a new invoice
    $pdf->ezStopPageNumbers(1, 1, $bill_page);
    $pdf->stopObject($header);
    $pdf->ezNewPage();
    $header = $pdf->openObject();
    $pdf->ezSetY(740);
    $bill_page = $pdf->ezStartPageNumbers(575, 695, 10, '', '', 1);


    // update running totals
    $grand_total += $total;
    $sub_total = $row['SERVICE_AMOUNT'];
    $total = $row['SERVICE_AMOUNT'];

    // update current invoice information
    $curr_cust_id = $cust_id;
    $curr_subcust_id = $subcust_id;
	$curr_dole_comm_newinv = $dole_comm_newinv;
    $curr_lr_num = $lr_num;
    $curr_bill_type = $bill_type;
    $curr_service_date = $service_date;
    $curr_labor_ticket = $labor_ticket;
    $curr_date = $date1;
  }


  // write the header
  if ($first_entry || $new_invoice) {
    // no longer the 1st entry
    $first_entry = false;
    // get customer info
    $vessel_name = getVesselName($bni_cursor2, $curr_lr_num);
    $customer_info = getCustomerInfo($bni_conn, $bni_cursor2, $curr_cust_id);

    $general_info = array();
    array_push($general_info, array('first'=>'', 'second'=>'', 'third'=>'Invoice No:', 'fourth'=>$invoice_num));
    array_push($general_info, array('first'=>'', 'second'=>'', 'third'=>'Invoice Date:', 'fourth'=>$today));

    // add more space
    for ($i=0; $i<2; $i++) {
      array_push($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''));
    }
  
    array_push($general_info, array('first'=>'', 'second'=>$customer_info . "\n\n\n" . $vessel_name, 
				    'third'=>'', 'fourth'=>''));

    // start to write the header
    $pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>120), 
					  'second'=>array('justification'=>'left', 'width'=>288),
					  'third'=>array('justification'=>'right', 'width'=>88), 
					  'fourth'=>array('justification'=>'right', 'width'=>62)),
			  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));

    $pdf->ezSetY(555);
    $pdf->ezText("<b>*** $charge_title ***</b>", 10, $center);
    $pdf->closeObject();
    $pdf->addObject($header, 'all');
    $pdf->ezSetY(525);
  }
  
  // write the billing record
  $record = array();
  array_push($record, array('first'=>$date, 'second'=>$desc, 'third'=>$amount));
  writeTheRecord();

  // DECEMBER 2013, ADAM WALTER - this line is added so that we can keep track of orders for later pallet printing.
  // ONLY FOR:  chilean miscbill-commodities: returns or transfers
  if(trim($row['RF_ORDER_NUM']) != ""){
	  if(!in_array($row['RF_ORDER_NUM'], $order_list_for_printing) && $billing_type == "Miscellaneous" && strpos($row['COMMODITY_CODE'], '510') !== false &&
					(strpos(strtoupper($row['SERVICE_DESCRIPTION']), "TRANSFER") !== false || strpos(strtoupper($row['SERVICE_DESCRIPTION']), "RETURNS TRUCKLOADING") !== false)) {
		  array_push($order_list_for_printing, $row['RF_ORDER_NUM']);
//		  echo "Order:  ".$row['RF_ORDER_NUM']."    billingnum:  ".$row['BILLING_NUM']."<br>";
	  }
  }

  // update the billing record
  if ($bill_type == 'H_HANDLING' || $bill_type == 'H_DUNNAGE' || $bill_type == 'H_ADMIN') {
    // for each holmen arrival #, there are 3 prebills for handling, one for dunnage and one for logistics
    // we don't have billing numbers for them with our query
    $stmt = "update billing 
             set invoice_num = $invoice_num, service_status = 'INVOICED', invoice_date = to_date('$today', 'MM/DD/YYYY')
             where billing_type = '$bill_type' and service_status = 'PREINVOICE' and lr_num = $lr_num and 
             arrival_num = " . $row['ARRIVAL_NUM'];

  } else {
    $stmt = "update billing 
             set invoice_num = $invoice_num, service_status = 'INVOICED', invoice_date = to_date('$today', 'MM/DD/YYYY')
             where billing_type = '$bill_type' and service_status = 'PREINVOICE' and billing_num = " . $row['BILLING_NUM'];
  }

  $ora_success = ora_parse($bni_cursor2, $stmt);
  two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);
  
  $ora_success = ora_exec($bni_cursor2);
  two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

  // update freight charge status, for Holmen Freight only
  if ($bill_type == 'FREIGHT') {
    $stmt = "update order_detail set freight_charge_status = 'INVOICED' where order_num = '" . $row['RF_ORDER_NUM'] . "'";
    $ora_success = ora_parse($rf_cursor, $stmt);
    two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

    $ora_success = ora_exec($rf_cursor);
    two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);
  }

} while (ora_fetch_into($bni_cursor1, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

// log to bni.invoicedate
$end_inv_num = $invoice_num;
$billing_type_cap = strtoupper($billing_type);

// first delete the prebill entry
$stmt = "delete from invoicedate where type = '$billing_type' and bill_type = 'B'";
$ora_success = ora_parse($bni_cursor2, $stmt);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_exec($bni_cursor2);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

// then add the invoice entry
$stmt = "select max(id) from invoicedate";
$ora_success = ora_parse($bni_cursor2, $stmt);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_exec($bni_cursor2);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_fetch($bni_cursor2);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);
$id = ora_getcolumn($bni_cursor2, 0) + 1;
$run_date = date('Y-m-d H:i:s');

$stmt = "insert into invoicedate (id, run_date, type, bill_type, start_inv_no, end_inv_no)
         values ($id, to_date('$run_date', 'YYYY-MM-DD hh24:mi:ss'), '$billing_type', 'I', 
         '$start_inv_num', '$end_inv_num')";
$ora_success = ora_parse($bni_cursor2, $stmt);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

$ora_success = ora_exec($bni_cursor2);
two_database_check($bni_conn, $rf_conn, $ora_success, $stmt);

// write sub-total line of the last section of the last invoice for Labor, Advance Billing and Dockage/Lines
//if ($billing_type == 'Advance Billing' || $billing_type == 'Dockage/Lines' || $billing_type == 'Labor') {
// JAN 2014:  and dole paper misc truckunloading
if ($billing_type == 'Advance Billing' || $billing_type == 'Dockage/Lines' || $billing_type == 'Labor' || 
			(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299"))) {
  $record = array();
  array_push($record, array('first'=>'', 'second'=>'', 'third'=>'__________'));
  if(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
	  array_push($record, array('first'=>'', 'second'=>"<b>Subtotal for Mill:      ".GetCustomerName($curr_subcust_id, $bni_conn)."</b>", 'third'=>"<b>".number_format($sub_total, 2, '.', ',')."</b>"));
  } else {
	array_push($record, array('first'=>'', 'second'=>'Subtotal:', 
			'third'=>'$' . number_format($sub_total, 2, '.', ',')));
  }
  if(($billing_type == 'Miscellaneous') && ($curr_subcust_id != "") && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
	  array_push($record, array('first'=>'________', 'second'=>"_________________________________________________________________________", 'third'=>'____________'));
  }
/*  if(($billing_type == 'Miscellaneous') && ($curr_dole_comm_newinv == "1272" || $curr_dole_comm_newinv == "1299")){
	  array_push($record, array('first'=>'', 'second'=>"Subtotal for Mill:      ".GetCustomerName($curr_subcust_id, $ora_conn), 'third'=>''));
  }
  array_push($record, array('first'=>'', 'second'=>'Subtotal:', 
			    'third'=>'$' . number_format($sub_total, 2, '.', ',')));
  array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
  */
  // the wording "Subtotal" is to be right-aligned
  $is_total = true;
  writeTheRecord();
  $is_total = false;
}

// write the total line of the last invoice
$record = array();
array_push($record, array('first'=>'', 'second'=>'', 'third'=>''));
if ($billing_type == "S-Wharfage"){
    $service_unit = $row['SERVICE_UNIT'];

    $total_qty = number_format($qty_total, 2,'.',',')." ".$service_unit;
    $service_rate = "$" .number_format($row['SERVICE_RATE'], 2, '.',',')."/".$service_unit;

    $qtyLen = strlen($total_qty);
    if ($qtyLen <15)
        $qtySpace =  str_repeat(" ",15-$qtyLen);

   array_push($record, array('first'=>'', 'second'=>str_repeat(" ",24). 'Invoice Total:  '.$total_qty.$qtySpace.$service_rate,
                             'third'=>'$' . number_format($total, 2, '.', ',')));
}else{
   array_push($record, array('first'=>'', 'second'=>'Invoice Total:',
                             'third'=>'$' . number_format($total, 2, '.', ',')));

}

// commit updates and close the connection to the database
ora_close($bni_cursor1);
ora_close($bni_cursor2);
ora_close($bni_cursor3);
ora_commit($bni_conn);
ora_logoff($bni_conn);

ora_close($rf_cursor);
ora_commit($rf_conn);
ora_logoff($rf_conn);


//array_push($record, array('first'=>'', 'second'=>'Invoice Total:', 'third'=>'$' . number_format($total, 2, '.', ',')));

$is_total = true;
writeTheRecord();

if(sizeof($order_list_for_printing) > 0){
	DisplayPalletList($order_list_for_printing, $general_info, $curr_cust_id, $ora_conn);
	$order_list_for_printing = array();
}

// write the grand total line
$grand_total += $total;
$pdf->ezStopPageNumbers(1, 1, $bill_page);
$pdf->stopObject($header);
$pdf->ezNewPage();
$pdf->ezSetY(525);

$pdf->ezText("GRAND TOTAL OF $billing_type_cap INVOICES FOR INVOICE DATE $today :", 
	     12, array('justification'=>'center')); 
$pdf->ezSetDy(-20);
$pdf->ezText("<u>$" . number_format($grand_total, 2, '.', ',') . '</u>', 14, array('justification'=>'center')); 

// save the invoices to a PDF file and open it instead of directly writing to the browser
redirect_invoices($pdf, $start_inv_num, $end_inv_num, $billing_type);


// -------------------------- FUNCTION DEFINITIONS -----------------------------------

function DisplayPalletList($order_list_for_printing, $general_info, $curr_cust_id, $ora_conn){
	global $pdf, $record, $is_total, $date_width, $desc_width, $date1, $bill_type, $new_section;
	  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
	//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	  if(!$conn){
		$body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
		mail($mailTO, $mailsubject, $body, $mailheaders);
		exit;
	  }
	$short_term_cursor = ora_open($conn);

	$i = 0;
	while($i < sizeof($order_list_for_printing)){
		$newpagecounter = 0; // just in case there is a boatload of transferred pallets, don't want them falling off the bottom of the page.

		$sql = "SELECT CT.PALLET_ID, CP.COMMODITY_NAME, CA.QTY_CHANGE, CT.ARRIVAL_NUM, CA.ORDER_NUM
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE CP
				WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CA.ORDER_NUM = '".$order_list_for_printing[$i]."'
					AND CA.CUSTOMER_ID = '".$curr_cust_id."'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
					AND SERVICE_CODE IN (7, 11)
				ORDER BY CT.PALLET_ID";
	  ora_parse($short_term_cursor, $sql);
	  ora_exec($short_term_cursor);
//	  echo $sql."<br>";
	  if(!ora_fetch_into($short_term_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		  return;
	  } else {
			$display_array = array();
			array_push($display_array, array('plt'=>'<b>PALLET ID</b>', 'desc'=>'<b>DESCRIPTION</b>', 'QTY'=>'<b>QTY</b>', 'ves'=>'<b>ARRIVAL_NUM</b>', 'order'=>'<b>ORDER #</b>'));
		  do {
/*
			if($newpagecounter == 0){
				$pdf->ezNewPage();
				// page header
				$pdf->ezSetY(740);
				$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
				  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>250), 
							  'second'=>array('justification'=>'left', 'width'=>158),
							  'third'=>array('justification'=>'right', 'width'=>88), 
							  'fourth'=>array('justification'=>'right', 'width'=>62)),
					  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));
				$pdf->ezSetY(525);

			}
*/
			  array_push($display_array, array('plt'=>$short_term_data_row['PALLET_ID'], 'desc'=>$short_term_data_row['COMMODITY_NAME'], 
												'QTY'=>$short_term_data_row['QTY_CHANGE'], 'ves'=>$short_term_data_row['ARRIVAL_NUM'], 'order'=>$short_term_data_row['ORDER_NUM']));
//			  print_r($display_array);
//			  echo "<br>";
			  $newpagecounter++;

			  if($newpagecounter == 30){
				  $pdf->ezNewPage();
				  // page header
				  $pdf->ezSetY(740);
				  $pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
				  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>120), 
							  'second'=>array('justification'=>'left', 'width'=>288),
							  'third'=>array('justification'=>'right', 'width'=>88), 
							  'fourth'=>array('justification'=>'right', 'width'=>62)),
					  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));
				  $pdf->ezSetY(525);

				  $pdf->ezTable($display_array, '', '', array('shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));
				  $newpagecounter = 0;

				  $display_array = array();
				  array_push($display_array, array('plt'=>'<b>PALLET ID</b>', 'desc'=>'<b>DESCRIPTION</b>', 'QTY'=>'<b>QTY</b>', 'ves'=>'<b>VESSEL</b>', 'order'=>'<b>ORDER #</b>'));

			  }
		  } while(ora_fetch_into($short_term_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		  if($newpagecounter != 0){
			$pdf->ezNewPage();
			// page header
			$pdf->ezSetY(740);
			$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
			  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>120), 
						  'second'=>array('justification'=>'left', 'width'=>288),
						  'third'=>array('justification'=>'right', 'width'=>88), 
						  'fourth'=>array('justification'=>'right', 'width'=>62)),
				  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));
			$pdf->ezSetY(525);

		    $pdf->ezTable($display_array, '', '', array('shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));
		  }

	  }
	  $i++;
	}
}



// write a line or some lines, the function makes sure that the write won't overflow a page
function writeTheRecord() {
  global $pdf, $record, $is_total, $date_width, $desc_width, $date1, $bill_type, $new_section;

  // write the billing record
  $before_page_num = $pdf->ezPageCount;
  $pdf->transaction('start');

  // the wording "Total:" is to be right aligned
  if ($is_total) {
    $rate_align = "right";
  } else {
    $rate_align = "left";
  }
  if (strtoupper($bill_type) == "S-WHARFAGE"){
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Courier.afm');
	$rate_align = "left";
  }
  $pdf->ezTable($record, array('first'=>'', 'second'=>'', 'third'=>''),
		'', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>$date_width), 
					'second'=>array('justification'=>$rate_align, 'width'=>$desc_width),
					'third'=>array('justification'=>'right', 'width'=>80)),
			  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));

  $after_page_num = $pdf->ezPageCount;

  if ($before_page_num != $after_page_num) {
    $pdf->transaction('rewind');
    $pdf->ezNewPage();
    $pdf->ezSetY(525);

    // try write the record again
    // if it is not a total line, should have date anyway
    if (count($record) == 1) {
      // section header already has its date
      if (!$new_section) {
	$record[0]['first'] = $date1;
      }
    }
    if (strtoupper($bill_type) == "S-WHARFAGE")
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Courier.afm');

    $pdf->ezTable($record, array('first'=>'', 'second'=>'', 'third'=>''),
		  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>$date_width), 
					  'second'=>array('justification'=>$rate_align, 'width'=>$desc_width),
					  'third'=>array('justification'=>'right', 'width'=>80)),
			    'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));
  }

  $pdf->transaction('commit');
  $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

}


// return the desired query for the selected billing type
function getQueryByType($billing_type, $vsl_num) {
  global $billing;

  switch ($billing_type) {
  case "Advance Billing":
//    $stmt = "select * from billing 
//             where billing_type in ('ADTRKLOAD', 'BACKHAUL', 'TERMSER') and service_status = 'PREINVOICE'
//             order by customer_id, lr_num, billing_type, service_date, billing_num";
    if (strtoupper($vsl_num) == "")
    {
		$stmt = "select * from billing 
				 where billing_type in ('ADTRKLOAD', 'BACKHAUL', 'TERMSER') and service_status = 'PREINVOICE'
				 order by customer_id, lr_num, billing_type, service_date, billing_num";
    }
    else
    {
		$stmt = "select * from billing 
				 where billing_type in ('ADTRKLOAD', 'BACKHAUL', 'TERMSER') and service_status = 'PREINVOICE'
				 and lr_num = $vsl_num
				 order by customer_id, lr_num, billing_type, service_date, billing_num";
	}
    break;
    
  case "BNI Storage":
    $stmt = "select b.*, m.cargo_bol, m.cargo_mark from billing b, cargo_manifest m 
             where b.lot_num = m.container_num and billing_type = 'STORAGE' and service_status = 'PREINVOICE'
             order by b.customer_id, b.lr_num, b.service_start, b.service_stop, m.cargo_bol, m.cargo_mark";
    break;
    
  case "Dockage/Lines":
//    $stmt = "select b.*, to_char(service_start, 'HH:MI AM') start_time, to_char(service_stop, 'HH:MI AM') stop_time,
//                v.vessel_nrt, v.vessel_length from billing b, vessel_profile v
//             where b.lr_num = v.lr_num and b.billing_type in ('DOCKAGE', 'LINES') 
//                and b.service_status = 'PREINVOICE'
//             order by b.customer_id, b.lr_num, b.billing_type, b.service_start, b.service_stop, b.billing_num";
    if (strtoupper($vsl_num) == "")
    {
		$stmt = "select b.*, to_char(service_start, 'HH:MI AM') start_time, to_char(service_stop, 'HH:MI AM') stop_time,
						v.vessel_nrt, v.vessel_length from billing b, vessel_profile v
						where b.lr_num = v.lr_num 
						and b.billing_type in ('DOCKAGE', 'LINES')
						and b.service_status = 'PREINVOICE'
				 order by b.customer_id, b.lr_num, b.billing_type, b.service_start, b.service_stop, b.billing_num";
    }
    else
    {
		$stmt = "select b.*, to_char(service_start, 'HH:MI AM') start_time, to_char(service_stop, 'HH:MI AM') stop_time,
						v.vessel_nrt, v.vessel_length from billing b, vessel_profile v
						where b.lr_num = v.lr_num 
						and b.lr_num = $vsl_num
						and b.billing_type in ('DOCKAGE', 'LINES')
						and b.service_status = 'PREINVOICE'
				 order by b.customer_id, b.lr_num, b.billing_type, b.service_start, b.service_stop, b.billing_num";
    }
    break;
    
  case "Equipment":
    $stmt = "select b.*, to_char(service_start, 'HH:MI AM') start_time, to_char(service_stop, 'HH:MI AM') stop_time
             from billing b
             where billing_type = 'EQUIPMENT' and b.service_status = 'PREINVOICE'
             order by b.customer_id, b.lr_num, b.service_date, b.service_code, b.commodity_code";
    break;
    
  case "Holmen Freight":
    $stmt = "select * from billing where billing_type = 'FREIGHT' and service_status = 'PREINVOICE'
             order by memo_num, rf_order_num, lot_num";
    break;
    
  case "Holmen Storage":
    $stmt = "select * from billing where billing_type = 'H_STORAGE' and service_status = 'PREINVOICE'
             order by lr_num, arrival_num, lot_num, service_start, service_stop";
    break;
    
  case "Holmen Vessel":
    $stmt = "select lr_num, arrival_num, billing_type, customer_id, service_date, service_unit, service_description, 
                sum(service_rate) service_rate, sum(service_amount) service_amount
             from billing where billing_type in ('H_HANDLING', 'H_DUNNAGE', 'H_ADMIN') and service_status = 'PREINVOICE' 
             group by lr_num, arrival_num, billing_type, customer_id, service_date, service_unit, service_description
             order by lr_num, arrival_num, billing_type, customer_id, service_date, service_unit, service_description";
    break;

  case "Labor":
    $stmt = "select b.*, to_char(service_start, 'HH:MI AM') start_time, to_char(service_stop, 'HH:MI AM') stop_time
             from billing b where billing_type in ('LABOR') and service_status = 'PREINVOICE'
             order by customer_id, lr_num, service_date, labor_ticket_num, service_code, service_start";
    break;
    
  case "Miscellaneous":
/*    $stmt = "select * from billing 
             where billing_type = 'MISC' and service_status = 'PREINVOICE'
             order by customer_id, lr_num, service_date, service_code, billing_num"; */
//    $stmt = "select bil.*, decode(commodity_code, 1272, 'DOLESORT', 1299, 'DOLESORT', lr_num) ves_sort, decode(commodity_code, 1272, '1272', 1299, '1299', 'OTHER') comm_sort,
//				decode(commodity_code, 1272, service_description, 1299, service_description, 'OTHER') dole_reference_sort
//			 from billing bil
//             where billing_type = 'MISC' and service_status = 'PREINVOICE'
//             order by comm_sort, sub_cust_num, customer_id, ves_sort, service_date, dole_reference_sort, service_code, billing_num";
    $stmt = "select bil.*, decode(commodity_code, 1272, 'DOLESORT', 1299, 'DOLESORT', lr_num) ves_sort, decode(commodity_code, 1272, '1272', 1299, '1299', 'OTHER') comm_sort, 
				decode(commodity_code, 1272, service_description, 1299, service_description, 'OTHER') dole_reference_sort
			from billing bil where billing_type = 'MISC' and service_status = 'PREINVOICE' ";
	if(strtoupper($vsl_num) != ""){
		$stmt .= "and lr_num = $vsl_num ";
	}    
	$stmt .= "order by comm_sort, sub_cust_num, customer_id, ves_sort, service_date, dole_reference_sort, service_code, billing_num";
    break;
    
  case "Truck Loading":
    $stmt = "select b.*, m.cargo_bol, m.cargo_mark, m.qty1_unit, m.qty2_unit, d.delivery_num
             from billing b, cargo_manifest m, cargo_delivery d 
             where b.lot_num = m.container_num and b.lot_num = d.lot_num and b.activity_num_wo = d.activity_num 
                and billing_type = 'TRKLOADING' and service_status = 'PREINVOICE' and d.delivery_num > 0
             order by b.customer_id, b.lr_num, b.service_date, d.delivery_num, m.cargo_bol, m.cargo_mark";
    break;
   
  case "Truck Unloading":
    $stmt = "select b.*, m.cargo_bol, m.cargo_mark, m.qty_expected, m.qty1_unit, m.qty2_expected, m.qty2_unit, 
                m.cargo_weight, m.cargo_weight_unit, r.unit
             from billing b, cargo_manifest m, dock_rcpt_handling_rate r 
             where b.lot_num = m.container_num and b.service_code = r.service_code and 
                b.commodity_code = r.commodity_code and billing_type = 'TRKUNLDNG' and 
                service_status = 'PREINVOICE'
             order by b.customer_id, b.lr_num, b.service_date, m.cargo_bol, m.cargo_mark";
    break;
    
  case "Wharfage":
//    $stmt = "select * from billing
//             where billing_type = 'WHARFAGE' and service_status = 'PREINVOICE'
//             order by customer_id, lr_num, service_date, service_code, commodity_code";
    if (strtoupper($vsl_num) == "")
    {
		$stmt = "select * from billing
				 where billing_type = 'WHARFAGE' and service_status = 'PREINVOICE'
				 order by customer_id, lr_num, service_date, service_code, commodity_code";
    }
    else
   	{
		$stmt = "select * from billing
				 where billing_type = 'WHARFAGE' and service_status = 'PREINVOICE'
				 and lr_num = $vsl_num
				 order by customer_id, lr_num, service_date, service_code, commodity_code";
 
	}
    break;

  case "Star Vessel":
    $stmt = "select * from billing
             where billing_type = 'Star Ves' and service_status = 'PREINVOICE'
             order by customer_id, lr_num, service_date, service_code, commodity_code";
    break;

  case "S-Wharfage":
    $stmt = "select * from billing
             where billing_type = 'S-WHARFAGE' and service_status = 'PREINVOICE'
             order by customer_id, lr_num, service_date, service_code, commodity_code, billing_num";
    break;
   
  case "FumInsp":
		$stmt = "SELECT *
				FROM BILLING BIL, FUMINSP_DESCRIPTIONS FD, FUMINSP_TYPES FT
				WHERE BIL.BILLING_TYPE = 'FUMINSP'
					AND BIL.SERVICE_STATUS = 'PREINVOICE'
					AND BIL.SERVICE_NUM = FD.DESC_ID
					AND BIL.EQUIPMENT_TYPE = FT.DESC_ID
				ORDER BY CUSTOMER_ID, LR_NUM, SERVICE_DATE, SERVICE_CODE, COMMODITY_CODE, BILLING_NUM";
	break;

  default:
    break;
  }

  return $stmt;
}


// make service description out of a billing record for given billing types
function makeDescription ($cursor, $row, $bill_type) {
  global $first_entry, $new_invoice, $new_section, $labor_ticket, $lr_num, $show_section_header, $short_term_cursor;

  // To be used to eliminate trailing zeros
  $trans = array(".00"=>"");

  switch ($bill_type) {

  case "ADTRKLOAD":
  case "BACKHAUL":
  case "TERMSER":
    $commodity = getCommodityNameNoCode($cursor, $row['COMMODITY_CODE']);
    $rate_unit = strtoupper(trim($row['SERVICE_UNIT']));
    if ($rate_unit != 'EACH' && $rate_unit != 'EA') {
      $weight_unit = $rate_unit;
      $rate_unit = "/ $rate_unit";
    } else {
      $weight_unit = "LB";
    }

    if ($bill_type == "ADTRKLOAD") {
      $charge = "ADVANCE TRUCK LOADING";
    } elseif ($bill_type == "BACKHAUL") {
      // Per HD # 2388
	$charge = "HANDLING AND TERMINAL SERVICE CHARGES";
    } else {    
      $charge = "TERMINAL SERVICE";
    }

    $pallets = strtr(number_format($row['LOT_NUM'], 2, '.', ','), $trans);
    $cartons = strtr(number_format($row['THRESHOLD_QTY'], 2, '.', ','), $trans);
    $weight = strtr(number_format($row['SERVICE_QTY'], 2, '.', ','), $trans);

    $desc = "Billing #: " . $row['BILLING_NUM'] . "; ".$row['SERVICE_DESCRIPTION']." Product: " . $commodity . ";  Pallets: " . $pallets . 
       ";  Cartons: " . $cartons . ";  Weight: " . $weight . " " . $weight_unit . " @ $" . number_format($row['SERVICE_RATE'], 2) . 
       " " . $rate_unit;

    if ($first_entry || $new_invoice || ($new_section && $show_section_header)) {
      $desc = $charge . ":\n\n" . $desc;
    }

    break;
    
  case "STORAGE":
    if (strpos($row['SERVICE_DESCRIPTION'], "STORAGE WITH SURCHARGE") === 0) {
      $with_surcharge = true;
    } else {
      $with_surcharge = false;
    }

    if ($with_surcharge) {
      $desc = $row['SERVICE_DESCRIPTION'] . "\nBOL: " . $row['CARGO_BOL'] . ";  Mark: " . $row['CARGO_MARK'] . 
	 ";  " . $row['SERVICE_QTY'] . " " . $row['SERVICE_UNIT'];
    } else {
      // get number of days that is not free
      $num_days = getStorageDays($cursor, $row['LOT_NUM'], $row['SERVICE_START']);
      $rate_info = getStorageRateInfo($cursor, $lr_num, $row['SERVICE_CODE'], $row['COMMODITY_CODE'], 
				      $row['SERVICE_RATE'], $row['SERVICE_UNIT'], $num_days, $row['BILLING_NUM'], $row['CUSTOMER_ID']);

      // get the unit of the storage rate
      list($temp1, $rate_unit, $temp2) = split('/', $rate_info, 3);
      $rate_unit = trim($rate_unit);
      $qty_unit = trim($row['SERVICE_UNIT']);
      
      // by default, they get these values
      $service_qty = $row['SERVICE_QTY'];
      $service_unit = $qty_unit;
      
      // get the qty in rate unit if qty unit is not the same
      if ($qty_unit != $rate_unit) {
	$factor = getConversionFactor($cursor, $qty_unit, $rate_unit);
	if ($factor != -1) {
	  // a conversion factor is available
	  $service_qty *= $factor;
	  $service_unit = $rate_unit;
	}
      }
      
      // reformat qty
      $service_qty = strtr(number_format($service_qty, 2, '.', ','), $trans);
      
      $desc = "BOL: " . $row['CARGO_BOL'] . ";  Mark: " . $row['CARGO_MARK'] . ";  " . $service_qty . " " . 
	 $service_unit . " @ " . $rate_info . " " . $row['MEMO_NUM'];
    }

    break;
    
  case "DOCKAGE":
    $rate_info = getVesselRateInfo($cursor, $row['SERVICE_CODE'], $row['COMMODITY_CODE'], 
				    $row['SERVICE_RATE'], $row['BILLING_NUM']);
    $service_name = getServiceNameNoCode($cursor, $row['SERVICE_CODE']);

    $nrt = strtr(number_format($row['VESSEL_NRT'], 2, '.', ','), $trans);
    $loa = strtr(number_format($row['VESSEL_LENGTH'], 2, '.', ','), $trans);

    $desc = $service_name . " - " . $row['SERVICE_QTY2'] . " Day(s);  NRT: " . $nrt . ";  LOA: " . $loa . 
       " FT;  Rate: " . $rate_info;

    if ($first_entry || $new_invoice || $new_section) {
      $desc = "DOCKAGE:\n\n" . $desc;
    }

	if($row['SERVICE_UNIT'] == "FEE"){ 
		// New Security Charges (MArch 2009) propmt new logic.
		// do the above code anyway though, for it's variables.
		$sql = "SELECT DECODE(CHARGE_TYPE, 'FLAT RATE', CHARGE_TYPE, CHARGE_RATE) THE_CHARGE FROM SECURITY_CHARGE_TYPE SCT, SECURITY_VESSEL_CHARGE SVC WHERE SCT.SELECTION_NO = SVC.SELECTION_NO AND SCT.TEXT_DESC = '".$row['SERVICE_DESCRIPTION']."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$desc = $row['SERVICE_DESCRIPTION'].":  ".$short_term_row['THE_CHARGE'];
	}

	break;
    
  case "LINES":
    $desc = $row['SERVICE_DESCRIPTION'] . ";  " . $row['START_TIME'] . " - " . $row['STOP_TIME'];

    if ($first_entry || $new_invoice || $new_section) {
      $desc = "LINES HANDLING:\n\n" . $desc;
    }

    break;
    
  case "EQUIPMENT":
    //get service description
    list($name,$temp) = split(":",$row['SERVICE_DESCRIPTION'],2);
    $desc_service = trim($name);

    $service_name = getServiceNameNoCode($cursor, $row['SERVICE_CODE']);
    $desc = $desc_service .":".$service_name . ":  " . $row['START_TIME'] . " - " . $row['STOP_TIME'] . "  @ $" . 
       $row['SERVICE_RATE'] . " / HR";
    break;
    
  case "H_HANDLING":
  case "H_DUNNAGE": 
  case "H_ADMIN":
    if ($bill_type != "H_HANDLING") {
      $desc = trim($row['SERVICE_DESCRIPTION']);
    } else {
      $desc = trim($row['SERVICE_DESCRIPTION']) . " @ $" . $row['SERVICE_RATE'] . " / " . $row['SERVICE_UNIT'];
    }

    break;
    
  case "LABOR":
    list($service, $employee, $temp) = split(":", $row['SERVICE_DESCRIPTION'], 3);
    $service = trim($service);
    $employee = trim($employee);

    if ($employee == "") {
      $employee = "OPERATOR";
    }

    if ($row['SERVICE_QTY'] != 1) {
      $employee .= "S";
    }

    if ($row['START_TIME'] == "12:00 AM" && $row['STOP_TIME'] == "12:00 AM") {
      if($row['SERVICE_RATE'] != 0 && $row['SERVICE_QTY'] != 0){
        $hours = round($row['SERVICE_AMOUNT'] / $row['SERVICE_RATE'] / $row['SERVICE_QTY'], 1);
      }
      else{
        $hours = "UNKNOWN";
      }
      $desc = $row['SERVICE_QTY'] . " " . $employee . ":  " . $hours . " HRS @ $" . $row['SERVICE_RATE'] . " / HR";
    } else {
      $desc = $row['SERVICE_QTY'] . " " . $employee . ":  " . $row['START_TIME'] . " - " . $row['STOP_TIME'] . 
	 " @ $" . $row['SERVICE_RATE'] . " / HR";
    }

    if ($first_entry || $new_invoice || $new_section) {
      if ($labor_ticket != "") {
	$desc = $service . "  (LT #: " . $labor_ticket . ")\n\n" . $desc;
      } else {
	$desc = $service . ":\n\n" . $desc;
      }
    }

    break;
    
  case "MISC":
  case "FREIGHT":
  case "H_STORAGE":
    $desc = trim($row['SERVICE_DESCRIPTION']);
    break;
    
  case "TRKLOADING":
    // format service qty & rate
    $service_qty = number_format($row['SERVICE_QTY'], 2, '.', ',');
    $rate_info = "$" . $row['SERVICE_RATE'] . " / " . $row['SERVICE_UNIT'];

    // get qty1
    $stmt = "select * from cargo_activity where lot_num = '" . $row['LOT_NUM'] . "' and activity_num = " .
       $row['ACTIVITY_NUM_WO'];
    ora_parse($cursor, $stmt);
    ora_exec($cursor);
    ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $qty1 = $row1['QTY_CHANGE'];

    // get qty2 if unit2 is defined
    $qty2_unit = trim($row['QTY2_UNIT']);
    if ($qty2_unit != "") {
      $stmt = "select * from cargo_activity_ext where lot_num = '" . $row['LOT_NUM'] . "' and activity_num = " .
	 $row['ACTIVITY_NUM_WO'];
      ora_parse($cursor, $stmt);
      ora_exec($cursor);
      ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $qty2 = $row1['QTY2'];

      $desc = "W/O #: " . $row['DELIVERY_NUM'] . ";  BOL: " . $row['CARGO_BOL'] . ";  Mark: " . $row['CARGO_MARK'] . 
	 ";  " . $qty1 . " " . $row['QTY1_UNIT'] . " = " . $qty2 . " " . $row['QTY2_UNIT'] . " = " . $service_qty . 
	 " " . $row['SERVICE_UNIT'] . " @ " . $rate_info;
    } else {
      $desc = "W/O #: " . $row['DELIVERY_NUM'] . ";  BOL: " . $row['CARGO_BOL'] . ";  Mark: " . $row['CARGO_MARK'] . 
	 ";  " . $qty1 . " " . $row['QTY1_UNIT'] . " = " . $service_qty . " " . $row['SERVICE_UNIT'] . " @ " . 
	 $rate_info;
    }

    break;

  case "TRKUNLDNG":
    // get the unit of the rate
    $rate_unit = trim($row['UNIT']);
    $qty_unit = trim($row['SERVICE_UNIT']);

    // by default, they get these value
    $service_qty = $row['SERVICE_QTY'];
    $service_unit = $qty_unit;

    // get the qty in rate unit if qty unit is not the same
    if ($qty_unit != $rate_unit) {
      $factor = getConversionFactor($cursor, $qty_unit, $rate_unit);
      if ($factor != -1) {
	// a conversion factor is available
	$service_qty *= $factor;
	$service_unit = $rate_unit;
      }
    }

    // reformat qty
    $service_qty = strtr(number_format($service_qty, 2, '.', ','), $trans);

    // get qty2 if unit2 is defined
    $qty2_unit = trim($row['QTY2_UNIT']);
    if ($qty2_unit != "") {
      $desc = "BOL: " . $row['CARGO_BOL'] . ";  Mark: " . $row['CARGO_MARK'] . ";  " . $row['QTY_EXPECTED'] . 
	 " " . $row['QTY1_UNIT'] . ";  " . $row['QTY2_EXPECTED'] . " " . $row['QTY2_UNIT'] . ";  " .  
	 $service_qty . " " . $service_unit . " @ $" . $row['SERVICE_RATE'] . 
	 " / " . $rate_unit;
    } else {
      $desc = "BOL: " . $row['CARGO_BOL'] . ";  Mark: " . $row['CARGO_MARK'] . ";  " . $row['QTY_EXPECTED'] . 
	 " " . $row['QTY1_UNIT'] . ";  " . $service_qty . " " . $service_unit . " @ $" . 
	 $row['SERVICE_RATE'] . " / " . $rate_unit;
    }

    break;
    
  case "WHARFAGE":
    $service_name = getServiceNameNoCode($cursor, $row['SERVICE_CODE']);
    $commodity = getCommodityName($cursor, $row['COMMODITY_CODE']);
    $rate_unit = getVesselRateUnit1($cursor, $row['SERVICE_CODE'], $row['COMMODITY_CODE']);

    // get qty and unit description
    // default weight unit is LB
    $service_qty = strtr(number_format($row['SERVICE_QTY'], 2, '.', ','), $trans);

    if (($rate_unit == 'TON') || ($rate_unit == 'NT')) {
      $tons = strtr(number_format($row['SERVICE_QTY'] / 2000, 2, '.', ','), $trans);
      $qty_unit = $tons . " " . $rate_unit;
    } elseif ($rate_unit == 'MT') {
      $mts = strtr(number_format($row['SERVICE_QTY'] * 0.0004536, 2, '.', ','), $trans);
      $qty_unit = $mts . " " . $rate_unit;
    } elseif ($rate_unit == 'EA') {
      $commodity_unit = getCommodityUnit($cursor, $row['COMMODITY_CODE']);
      $qty_unit = $service_qty . " " . $commodity_unit;
    } else {
      $qty_unit = $service_qty . " " . $rate_unit;
    }

    $rate_info = getVesselRateInfo($cursor, $row['SERVICE_CODE'], $row['COMMODITY_CODE'], 
				   $row['SERVICE_RATE'], $row['BILLING_NUM']);

    $desc = $service_name . ":  " . $commodity . ";  " . $qty_unit . " @ " . $rate_info;

	if($row['SERVICE_DESCRIPTION'] == "SECURITY"){ 
		// New Security Charges (MArch 2009) propmt new logic.
		// do the above code anyway though, for it's variables.
		$sql = "SELECT RATE_CHARGE, UNIT FROM SECURITY_CHARGE_RATES WHERE COMMODITY_CODE = '".$row['COMMODITY_CODE']."' AND SERVICE_CODE = '".$row['SERVICE_CODE']."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$desc = "SECURITY CHARGE:  ".$commodity.";  ".$service_qty." ".$short_term_row['UNIT']." @ $".$short_term_row['RATE_CHARGE']."/".$short_term_row['UNIT'];
	}

	break;

  case "Star Ves":
    $desc = $row['SERVICE_DESCRIPTION'];
    break;

  case "S-WHARFAGE":
 
    $service_unit = $row['SERVICE_UNIT'];

    $service_qty = number_format($row['SERVICE_QTY'],2,'.',',')." ".$service_unit;
    $service_rate = "$" .number_format($row['SERVICE_RATE'], 2, '.',',')."/".$service_unit;

    $descLen = strlen($row['SERVICE_DESCRIPTION']);
    if ($desclen <40)
        $descSpace = str_repeat(" ",40-$descLen);

    $qtyLen = strlen($service_qty);
    if ($qtyLen <15)
        $qtySpace =  str_repeat(" ",15-$qtyLen);

    $desc = $row['SERVICE_DESCRIPTION'].$descSpace .$service_qty .$qtySpace .$service_rate ;

    break;

  case "FUMINSP":
	  if($row['SERVICE_DESCRIPTION'] == ""){
		$container_text = "";
	  }else {
		$container_text = "CONTAINER # ".$row['SERVICE_DESCRIPTION'];
	  }
	  $desc = $row['DESC_TEXT']." ".$container_text." ".$row['TYPE_TEXT']." ".$row['SERVICE_QTY']." ".
			$row['SERVICE_UNIT']." @ "."$" .number_format($row['SERVICE_RATE'], 2)."/".$row['LABOR_TYPE']." ".$row['COMMODITY_NAME'];
  break;

    
  default:
    break;
  }
  
  $desc = strtoupper($desc);
  return $desc;
}


// Return the title of the bills for a given billing type
function getChargeTitle($billing_type) {

  switch ($billing_type) {

  case "Advance Billing":
    $charge_title = "ADVANCE VESSEL CHARGE";
    break;
    
  case "BNI Storage":
    $charge_title = "STORAGE CHARGE";
    break;
    
  case "Dockage/Lines":
    $charge_title = "DOCKAGE & LINES HANDLING CHARGE";
    break;
    
  case "Equipment":
    $charge_title = "EQUIPMENT CHARGE";
    break;
    
  case "Holmen Freight":
    $charge_title = "FREIGHT CHARGE";
    break;
    
  case "Holmen Storage":
    $charge_title = "STORAGE CHARGE";
    break;
    
  case "Holmen Vessel":
    $charge_title = "VESSEL BILLING";
    break;
    
  case "Labor":
    $charge_title = "LABOR CHARGE";
    break;
    
  case "Miscellaneous":
    $charge_title = "MISCELLANEOUS CHARGE";
    break;
    
  case "Truck Loading":
    $charge_title = "TRUCK LOADING CHARGE";
    break;
   
  case "Truck Unloading":
    $charge_title = "TRUCK UNLOADING CHARGE";
    break;
    
  case "Wharfage":
    $charge_title = "WHARFAGE CHARGE";
    break;

  case "Star Vessel":
    $charge_title = "VESSEL BILLING";
    break;
    
  case "S-Wharfage":
    $charge_title = "WHARFAGE CHARGE";
    break;

  case "FumInsp":
    $charge_title = "FUMIGATION / INSPECTION";
    break;
   
  default:
    $charge_title = "";
    break;
  }

  return $charge_title;
}




function GetCustomerName($customer, $ora_conn){
	$short_term_cursor = ora_open($ora_conn);

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$customer."'";
	ora_parse($short_term_cursor, $sql);
	ora_exec($short_term_cursor);
	ora_fetch_into($short_term_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	return $short_term_data_row['CUSTOMER_NAME'];
}



?>
