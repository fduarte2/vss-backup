<?
include("pow_session.php");
$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

// include the date comparing function
include("compareDate.php");

// To be used to eliminate trailing zeros
$trans = array(".00"=>"");

$conn = ora_logon("SAG_OWNER@BNI", "SAG");
if (!$conn) {
   printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
   printf("Please report to TS!");
   exit;
}
$cursor = ora_open($conn);


$today = date("m/d/Y");
$timestamp = date("F j, Y, g:i A");

// get form values
$cust_id = $HTTP_GET_VARS["customer"];
$season = $HTTP_GET_VARS["season"];
$LR = $HTTP_GET_VARS["LR"];
$cargo_type = $HTTP_GET_VARS["cargo_type"];

$stmt = "select customer_name from customer_profile where customer_id = '$cust_id'";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);
ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$customer_name = $row1['CUSTOMER_NAME'];
list($junk, $temp_customer_name) = split("-", $customer_name);
if($temp_customer_name != ""){
   $customer_name = $temp_customer_name;
}

if($LR == "ship"){
//	$extra_sql = "AND ((CLAIM_CARGO_TYPE = 'CHILEAN' AND h.ispercentage = 'Y') OR CLAIM_CARGO_TYPE != 'CHILEAN') AND UPPER(VESSEL) != 'TRUCK'";
	$extra_sql = "and (	(vessel is not null
						and vessel != 'TRUCK'
						and CLAIM_CARGO_TYPE != 'CLEMENTINES')
					or
						((vessel is null or vessel != 'TRUCK')
						and CLAIM_CARGO_TYPE = 'CLEMENTINES')
					)";
} else {
	$extra_sql = "AND UPPER(VESSEL) = 'TRUCK'";
}

//			AND h.ispercentage = 'Y'
$sql = "select invoice_num, exporter, bol, decode(vessel, 'TRUCK', 'TRUCK', voyage) the_voy, pallet_id, prod, 
			(port_qty + denied_qty + ship_line_qty) theqty, claim_price, claim_amt amt
		from claim_header_rf h, claim_body_rf b
		where h.claim_id = b.claim_id 
			and h.claim_cargo_type='$cargo_type' 
			and h.ispercentage='Y' 
			and customer_id = $cust_id 
			and b.status IN ('O', 'C') 
			and h.season = '$season'
			".$extra_sql."
        order by exporter, invoice_num";
/*
$sql = "select invoice_num, exporter, bol, voyage, pallet_id, prod, sum(claim_amt) as amt
	from claim_header_rf h, claim_body_rf b
	where h.claim_id = b.claim_id and customer_id = $cust_id and b.status IN ('O', 'C') and h.season = '$season'  AND h.ispercentage = 'Y'
        group by  invoice_num, exporter, bol, voyage, pallet_id, prod
        order by exporter, invoice_num";

*/
/*
$stmt = "select customer_invoice_num, exporter, bni_bl, voyage, rf_pallet_id, product_name, sum(quantity) as quantity,
 	 sum(cost) as cost from claim_log
	 where customer_id = $cust_id and system = '$system' and completed = 'f' and season = '$season'
	 group by customer_invoice_num, exporter, bni_bl, voyage, rf_pallet_id, product_name 
	 order by exporter, customer_invoice_num";
*/
ora_parse($cursor, $sql);
ora_exec($cursor);

/*
$result = pg_query($pg_conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($pg_conn));
$pg_rows = pg_num_rows($result);

    
$stmt = "select customer_name from customer_profile where customer_id = '$cust_id'";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);
ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$customer_name = $row1['CUSTOMER_NAME'];
list($junk, $temp_customer_name) = split("-", $customer_name);
if($temp_customer_name != ""){
   $customer_name = $temp_customer_name;
}
*/
$total_amount = 0;
$total_claimed = 0;
$qty_denied_total = 0;
$denied_total = 0;
$shipper_total = 0;
$claims = 0;
$current_claim = -1;
$total = 0;

$change_info = array();
$general_info = array();

// has changes in postgreSQL table billing_change that match with the search criteria.

  // fetch one row at a time
while (ora_fetch($cursor)) {
    $invoice =ora_getcolumn($cursor, 0);
    $exporter = ora_getcolumn($cursor, 1);
    $bl = ora_getcolumn($cursor, 2);
    $voy = ora_getcolumn($cursor, 3);
    $pId = ora_getcolumn($cursor, 4);
    $prod = ora_getcolumn($cursor, 5);
    $qty = ora_getcolumn($cursor, 6); // EDIT ADAM WALTER:  NOT CHANGING VARIABLES, BUT ARITHMETIC EDITED
	$cost = ora_getcolumn($cursor, 7);
	$totalprice = ora_getcolumn($cursor, 8);

    $tot_amt = $totalprice;

    $total += $tot_amt;

    if($pre_exporter <>"" && trim($pre_exporter) <> trim($exporter)){
    	array_push($change_info, array(	'invoice'=>'',
        	                       	'exporter'=>'',
                	          	'bl'=>'',
                        	        'voy'=>'',
                                	'pId'=>'',
                                   	'qty'=>'',
                                   	'cost'=>'<b>Sub total</b>',
                                   	'tot'=>'$'.number_format($sub_amt,2,'.',',')));
      	$sub_amt = 0;
    }
    $sub_amt +=$tot_amt;
    $pre_exporter = $exporter;


    array_push($change_info, array('invoice'=>$invoice,
				   'exporter'=>$exporter,
				   'bl'=>$bl,
				   'voy'=>$voy,
				   'pId'=>$pId,
				   'prod'=>$prod,
				   'qty'=>$qty,
				   'cost'=>'$'.number_format($cost,2,'.',','),
				   'tot'=>'$'.number_format($tot_amt,2,'.',',')));
}
        array_push($change_info, array( 'invoice'=>'',
                                        'exporter'=>'',
                                        'bl'=>'',
                                        'voy'=>'',
                                        'pId'=>'',
                                        'qty'=>'',
                                        'cost'=>'<b>Sub total</b>',
                                        'tot'=>'$'.number_format($sub_amt,2,'.',',')));

          array_push($change_info, array( 'invoice'=>'<b>Total</b>',
                                        'exporter'=>'',
                                        'bl'=>'',
                                        'voy'=>'',
                                        'pId'=>'',
                                        'qty'=>'',
                                        'cost'=>'',
                                        'tot'=>'<b>$'.number_format($total, 2,'.',',').'</b>'));


// close database connections
ora_close($cursor);
ora_logoff($conn);
 
   $arrHeading = array( 'invoice'=>'<b>Invoice Number</b>',
			'exporter'=>'<b>Exporter</b>',
			'bl'=>'<b>B/L</b>',
			'voy'=>'<b>Vsl. Voy. Number</b>', 
			'pId'=>'<b>Pallet Number</b>',
			'prod'=>'<b>Species</b>',
			'qty'=>'<b>Damage Short</b>',
			'cost'=>'<b>Market Price</b>',
			'tot'=>'<b>Total</b>');
   $arrCol = array('invoice'=>array('width'=>70, 'justification'=>'center'),
                   'exporter'=>array('width'=>100, 'justification'=>'center'),
                   'bl'=>array('width'=>100, 'justification'=>'center'),
                   'voy'=>array('width'=>60, 'justification'=>'center'),
                   'pId'=>array('width'=>70, 'justification'=>'center'),
                   'prod'=>array('width'=>70, 'justification'=>'center'),
                   'qty'=>array('width'=>60,'justification'=>'center'),
                   'cost'=>array('width'=>60,'justification'=>'center'),
                   'tot'=>array('width'=>60,'justification'=>'center'));
 
   $heading = array();
   array_push($heading, $arrHeading);

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','landscape');

   $pdf->ezSetMargins(20,60,65,65);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezStartPageNumbers(720, 32, 9, '','',1);
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

   $pdf->ezSetDy(-5);
   $pdf->ezText("<b>PSW Claim Report (".$LR.")</b>", 10, $left);
   $pdf->ezSetDy(-5);
   $pdf->ezText("<b>Receiver: $customer_name</b>", 10, $left);
   $pdf->ezSetDy(-5);
   $pdf->ezText("<b>Location: Port of Wilmington, DE</b>", 10, $left);
   $pdf->ezSetDy(-5);
   $pdf->ezText("<b>Season: $season    Claim Type:  ".$cargo_type."</b>", 10, $left);


   $pdf->ezSetDy(-20);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');

   $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9, 'width'=>510,'cols'=>$arrCol));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($change_info, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2,'fontSize'=>8,'width'=>510,'cols'=>$arrCol));

   $tot_submission = '$'.number_format($total, 2,'.',',');
   $tot_port = '$'.number_format(0.68 * $total, 2,'.',',');

   $pdf->ezSetDy(-15);
   $pdf->ezText("<b>$season Claim Submission           $tot_submission</b>", 10, $left);
   if($LR != "truck"){
	   $pdf->ezSetDy(-5);
	   $pdf->ezText("<b>Port's 68% responsibility		                 $tot_port</b>", 10, $left);
   }

/*
    if($current_claim != $row['claim_id']){
      $claims++;
      $current_claim = $row['claim_id'];
    }
    $amount = $row['amount'] + $row['shipper_amount'] + $row['denied_quantity'];
    $total_amount += $amount;

    $total_claimed += $row['quantity'];
    $shipper_total += $row['shipper_qty'];
    $port_qty_total += $row['quantity_claimed'];
    $qty_denied_total += $row['denied_qty'];
    $denied_total += $row['denied_quantity'];
    $shipper_amt_total += $row['shipper_amount'];
    $port = $row['amount'];
    $total_paid += $port;

    list($date_part, $the_rest) = split(" ", $row['claim_date'], 2);
    $change_date = date('m/d/y', strtotime($date_part));

    // Also get the customer name
    $stmt = "select customer_name from customer_profile where customer_id = '" . $row['customer_id'] . "'";
    $ora_success = ora_parse($cursor, $stmt);
    $ora_success = ora_exec($cursor);
    ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $customer_name = $row1['CUSTOMER_NAME'];
    list($junk, $temp_customer_name) = split("-", $customer_name); 
    if($temp_customer_name != "")
      $customer_name = $temp_customer_name;
    // Max of 10 Characters
    $customer_name = substr($customer_name, 0, 10);

    // Build a Claim ID
    $claim_id = $row['customer_invoice_num'];
    $sys_claim_id = $row['claim_id'];

    $system = $row['system'];
    if($system == "CCDS"){
      $product_name = $row['product_name'] . " " . $row['ccd_cut'];
      $key = $row['ccd_lot_id'];
      $mark = $row['ccd_mark'];
    }
    else if($system == "BNI"){
      $product_name = $row['product_name'];
      $mark = $row['bni_mark'];
      $key = $row['bni_bl'];
    }
    else if($system == "RF"){
      $product_name = $row['product_name'];
      $key = $row['rf_pallet_id'];
      $mark = $row['ccd_mark'];
    }

    $notes = $row['notes'];
    // Max 20
    $notes = substr($notes, 0, 20);
    $product_name = substr($product_name, 0, 10);

    $vessel_name = $row['vessel_name'];
    if($row['vessel_type'] == "C"){
      $vessel_name .= " C&S";
    }
    elseif($row['vessel_type'] == "K"){
      $vessel_name .= " KY";
    }

    array_push($change_info, array('date'=>$change_date, 'claim_id'=>$claim_id, 
     'cust'=>$customer_name, 'claimed'=>$row['quantity'], 
     'weight'=>$row['weight'], 'cost'=>"$" . number_format($row['cost'], 4, '.', ','), 'amount'=>"$" . $amount,
     'denied'=>"$" . $row['denied_quantity'], 'denied_qty'=>$row['denied_qty'],
     'shipper_qty'=>$row['shipper_qty'], 'product_name'=>$product_name,
     'mark'=>$mark, 'key'=>$key, 'vessel'=>$vessel_name,
     'shipper_amt'=>"$" . $row['shipper_amount'], 'port'=>"$" . $port,
     'port_qty'=>$row['quantity_claimed'],
     'unit'=>$row['unit'], 'denied'=>"$" . $row['denied_quantity'],
'notes'=>$notes, 'url'=>"http://$hostname/claims/reports/claim_tracker.php?claim_id=$sys_claim_id"));
  }
}

// close database connections
pg_close($pg_conn);

// Code to generate PDF file of the report

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','landscape');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');




if($history != ""){
  // write out the intro. for the 1st page
  $pdf->ezText("Claim History Report", 16, $center);
}
else{
  $pdf->ezText("Open Claims Report", 16, $center);
}
//$pdf->ezText("\n", 10, $center);

if ($report_customer_name == "") {
  $report_customer_name = "Not Specified";
}
 

if ($start_date == "" && $end_date == "") {
  $change_date = "Not Specified";
} elseif ($start_date != "" && $end_date != "") {
  $change_date = $start_date . " - " . $end_date;
} elseif ($start_date != "") {
  $change_date = "Since $start_date";
} else {
  $change_date = "Up to $end_date";
}
 
array_push($general_info, array('first'=>"Customer:  $report_customer_name", 
				'second'=>'', 
				'third'=>"System:  $systemtxt",
				'fourth'=>'',
				'fifth'=>"Date of Claim: $change_date"));

$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 'fifth'=>''), 
	      '', array('cols'=>array('first'=>array('justification'=>'left'), 
				      'third'=>array('justification'=>'center'),
				      'fifth'=>array('justification'=>'right')),
			'shaded'=>0, 'showLines'=>0, 'fontSize'=>11, 'width'=>760));


$pdf->ezSetDy(-15);
    // Push a totals line
    array_push($change_info, array('date'=>$claims, 'claim_id'=>'Total:', 
				   'cust'=>'', 'claimed'=>$total_claimed, 
                                   'port'=>"$" . $total_paid,
                                   'port_qty'=>$port_qty_total,
				   'cost'=>'', 'amount'=>"$" . $total_amount, 
                                   'shipper_qty'=>$shipper_total,
                                   'shipper_amt'=>"$" . $shipper_amt_total,
                                   'denied_qty'=>$qty_denied_total,
				   'denied'=>"$" . $denied_total));

   $pdf->ezTable($change_info, array('cust'=>'Customer', 'claim_id'=>'Invoice #',
     'date'=>'Date', 'vessel'=>'Vessel', 'key'=>'ID', 'mark'=>'Mark',
     'product_name'=>'Comm', 'claimed'=>'Qty', 'weight'=>'Weight',
     'cost'=>'Cost Per', 'amount'=>'Total Amount', 'port_qty'=>'Port Qty', 'port'=>'Port Amt', 'denied_qty'=>'Denied Qty', 'denied'=>'Denied', 
     'shipper_qty'=>'Ship Line', 'shipper_amt'=>'Ship Line Amt'),
     '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>775, 'showLines'=>2, 
     'fontSize'=>8, 'cols'=>array(
     'cost'=>array('justification'=>'right'), 
     'cust'=>array('justification'=>'left'),
     'claim_id'=>array('link'=>'url'))));

// footer
$text = "Port of Wilmington, printed by $user, $timestamp";
$pdf->line(28,40,754,40);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(28,950,754,950);
$pdf->addText(40,34,6, $text);
*/
// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");
?>
