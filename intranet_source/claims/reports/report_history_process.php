<?
// last modified Adam Walter, Feb 2012.
// partially because postgres is being phased out, and partly because
// I can;t believe my predecessor built the SQL statement this way...

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
/*
// make postgreSQL connection
$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$pg_conn){
  printf("Could not open connection to database server");
  exit;
}
*/
$conn = ora_logon("SAG_OWNER@BNI", "SAG");
if (!$conn) {
   printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
   printf("Please report to TS!");
   exit;
}
$cursor = ora_open($conn);
$ex_postgres_cursor = ora_open($conn);


$today = date("m/d/Y");
$timestamp = date("F j, Y, g:i A");

// get form values
$customer = $HTTP_POST_VARS["customer"];
list($customer_id, $report_customer_name) = split(",", $customer);
$billing_type = $HTTP_POST_VARS["billing_type"];
$order_by = $HTTP_POST_VARS["order_by"];
$start_date = $HTTP_POST_VARS["start_date"];
$end_date = $HTTP_POST_VARS["end_date"];
$history = $HTTP_POST_VARS["history"];
$system = $HTTP_POST_VARS["system"];
$claim_type = $HTTP_POST_VARS["claim_type"];
$letter_type = $HTTP_POST_VARS["letter_type"];

// check if the dates are in acceptable format
// if it is null, keep it untouched; if it is later than today, set it to be today
if ($start_date != "") {
  $return = strtotime($start_date);
  if ($return == -1) {			// invalid date format
    die ("The start date you entered, $start_date, is not in an acceptable format.\n
	    You may use the format as in the following example, 12/31/2003.");
  } else {
    $start_date = date('m/d/Y', $return);	// start date cannot be later than today
    if (compareDate($start_date, $today) > 0 ) {
      $start_date = $today;
    }
  }
}

if ($end_date != "") {
  $return = strtotime($end_date);
  if ($return == -1) {			// invalid date format
    die ("The end date you entered, $end_date, is not in an acceptable format.\n
          You may use the format as in the following example, 12/31/2003.");
  } else {
    $end_date = date('m/d/Y', $return);	// end date cannot be later than today
    if (compareDate($end_date, $today) > 0 ) {
      $end_date = $today;
    }
  }
}

// switch the dates if start date is later than end date
if ($start_date != "" && $end_date != "") {
  if (compareDate($start_date, $end_date) > 0) {
    $temp = $start_date;
    $start_date = $end_date;
    $end_date = $temp;
  }
}

// Get prebill update information from ccds.billing_change
$stmt = "select * from CLAIM_LOG_ORACLE where 1 = 1";

if ($customer_id != "") {
  $stmt = $stmt . " and customer_id = $customer_id";
}

if($system == "RF-OPPY"){
  $stmt .= " and customer_id = '1512'";
}

if ($billing_type != "") {
//  if (strpos($stmt, "where") === false) {
//    $stmt = $stmt . " where system = '$billing_type'";
//  } else {
    $stmt = $stmt . " and system = '$billing_type'";
//  } 
}

if ($start_date != "") {
//  if (strpos($stmt, "where") === false) {
//    $stmt = $stmt . " where claim_date >= '$start_date'";
//  } else {
    $stmt = $stmt . " and claim_date >= TO_DATE('$start_date', 'MM/DD/YYYY')";
 // } 
}

if ($end_date != "") {
//  if (strpos($stmt, "where") === false) {
//    $stmt = $stmt . " where claim_date <= '$end_date'";
//  } else {
    $stmt = $stmt . " and claim_date <= TO_DATE('$end_date', 'MM/DD/YYYY')";
//  } 
}

if($system != ""){
//  if (strpos($stmt, "where") === false) {
//    $stmt .= " where system = '$system'";
//  } else {
    $stmt .= "and system = '$system'";
//  }
}

if ($system == "") {
  $systemtxt = "Not Specified";
}
else if($system == "RF"){
  $systemtxt = "Fruit (RF)";
}
else if($system == "RF-OPPY"){
  $systemtxt = "Fruit (RF Oppenhimer)";
}
else if($system == "CCDS"){
  $systemtxt = "Meat (CCDS)";
}
else if($system == "BNI"){
  $systemtxt = "Juice (BNI)";
}

if($history != ""){
  // This report is only for completed entries
//  if (strpos($stmt, "where") === false) {
//    $stmt = $stmt . " where completed = 't'";
//  } else {
    $stmt = $stmt . " and completed = 't'";
//  } 
}
else{
//  if (strpos($stmt, "where") === false) {
//    $stmt = $stmt . " where completed = 'f'";
//  } else {
    $stmt = $stmt . " and completed = 'f'";
// } 
}

if($claim_type != ""){
//  if (strpos($stmt, "where") === false) {
//    $stmt .= " where claim_type = '$claim_type'";
//  } else {
    $stmt .= " and claim_type = '$claim_type'";
//  }
}

if($letter_type != ""){
//  if (strpos($stmt, "where") === false) {
//    $stmt .= " where letter_type = '$letter_type'";
//  } else {
    $stmt .= " and letter_type = '$letter_type'";
//  }
}


$stmt = $stmt . " order by " . $order_by;
/*
$result = pg_query($pg_conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($pg_conn));
$pg_rows = pg_num_rows($result);
*/
$ora_success = ora_parse($ex_postgres_cursor, $stmt);
$ora_success = ora_exec($ex_postgres_cursor);


$total_amount = 0;
$total_claimed = 0;
$qty_denied_total = 0;
$denied_total = 0;
$shipper_total = 0;
$claims = 0;
$current_claim = -1;

$change_info = array();
$general_info = array();

// has changes in postgreSQL table billing_change that match with the search criteria.
//if ($pg_rows > 0) {
while(ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
  // fetch one row at a time
//  for ($i=0; $i<$pg_rows; $i++) {
//    $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
    if($current_claim != $row['CLAIM_ID']){
      $claims++;
      $current_claim = $row['CLAIM_ID'];
    }
    $amount = $row['AMOUNT'] + $row['SHIPPER_AMOUNT'] + $row['DENIED_QUANTITY'];
    $total_amount += $amount;
    $total_claimed += $row['QUANTITY'];
    $shipper_total += $row['SHIPPER_QTY'];
    $port_qty_total += $row['QUANTITY_CLAIMED'];
    $qty_denied_total += $row['DENIED_QTY'];
    $denied_total += $row['DENIED_QUANTITY'];
    $shipper_amt_total += $row['SHIPPER_AMOUNT'];
    $port = $row['AMOUNT'];
    $total_paid += $port;

    list($date_part, $the_rest) = split(" ", $row['CLAIM_DATE'], 2);
    $change_date = date('m/d/y', strtotime($date_part));

    // Also get the customer name
    $stmt = "select customer_name from customer_profile where customer_id = '" . $row['CUSTOMER_ID'] . "'";
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
    $claim_id = $row['CUSTOMER_INVOICE_NUM'];
    $sys_claim_id = $row['CLAIM_ID'];

    $system = $row['SYSTEM'];
    if($system == "CCDS"){
      $product_name = $row['PRODUCT_NAME'] . " " . $row['CCD_CUT'];
      $key = $row['CCD_LOT_ID'];
      $mark = $row['CCD_MARK'];
    }
    else if($system == "BNI"){
      $product_name = $row['PRODUCT_NAME'];
      $mark = $row['BNI_MARK'];
      $key = $row['BNI_BL'];
    }
    else if($system == "RF"){
      $product_name = $row['PRODUCT_NAME'];
      $key = $row['RF_PALLET_ID'];
      $mark = $row['CCD_MARK'];
    }

    $notes = $row['NOTES'];
    // Max 20
    $notes = substr($notes, 0, 20);
    $product_name = substr($product_name, 0, 10);

    $vessel_name = $row['VESSEL_NAME'];
    if($row['VESSEL_TYPE'] == "C"){
      $vessel_name .= " C&S";
    }
    elseif($row['VESSEL_TYPE'] == "K"){
      $vessel_name .= " KY";
    }

    array_push($change_info, array('date'=>$change_date, 'claim_id'=>$claim_id, 
     'cust'=>$customer_name, 'claimed'=>$row['QUANTITY'], 
     'weight'=>$row['WEIGHT'], 'cost'=>"$" . number_format($row['COST'], 4, '.', ','), 'amount'=>"$" . $amount,
     'denied'=>"$" . $row['DENIED_QUANTITY'], 'denied_qty'=>$row['DENIED_QTY'],
     'shipper_qty'=>$row['SHIPPER_QTY'], 'product_name'=>$product_name,
     'mark'=>$mark, 'key'=>$key, 'vessel'=>$vessel_name,
     'shipper_amt'=>"$" . $row['SHIPPER_AMOUNT'], 'port'=>"$" . $port,
     'port_qty'=>$row['QUANTITY_CLAIMED'],
     'unit'=>$row['UNIT'], 'denied'=>"$" . $row['DENIED_QUANTITY'],
'notes'=>$notes, 'url'=>"http://$hostname/claims/reports/claim_tracker.php?claim_id=$sys_claim_id"));
}

// close database connections
//pg_close($pg_conn);

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

// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");
?>
