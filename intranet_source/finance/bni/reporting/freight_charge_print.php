<?
// Lynn F. Wang  (15-JUL-04)
// Prints billing information for Holmen Truck Shipping Orders

// validate user
include("pow_session.php");
$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// make sure we don't overwrite form variables
// $status, $start_date and $end_date

// make database connections
$conn = ora_logon("PAPINET@RF", "OWNER");
$cursor1 = ora_open($conn);
$cursor2 = ora_open($conn);

$order_detail = "order_detail";

// validate start date
if ($start_date != "") {
   $timestamp = strtotime($start_date);

   if ($timestamp == -1) {			// invalid date format
      die ("The start date you entered, $start_date, is not in an acceptable format.\n
	    Please use the format of MM/DD/YYYY, and try it again");
   } else {
      $start_date = date('d-M-y', $timestamp);	// loading date could be earlier than today
   }

   $date_range = date('F d, Y', $timestamp);
}

// validate end date
if ($end_date != "") {
   $timestamp = strtotime($end_date);

   if ($timestamp == -1) {			// invalid date format
      die ("The end date you entered, $end_date, is not in an acceptable format.\n
	    Please use the format as in the following example, 12/31/2003, and try it again");
   } else {
      $end_date = date('d-M-y', $timestamp);	// loading date could be earlier than today
   }

   $date_range .= " - " . date('F d, Y', $timestamp);
}

// make up the query
$stmt = "select * from $order_detail where order_type = 'DELIVERY' and order_status = 'EXECUTED' 
         and transport_mode = 'Road' and carrier_name <> 'CUSTOMER PICKUP' and ship_date >= '$start_date' 
         and ship_date <= '$end_date'";

switch ($status) {
 case 'pending':
   $stmt .= " and (freight_reconciled is null or freight_reconciled <> 'Y')";
   break;
 
 case 'reconciled':
   $stmt .= " and freight_reconciled = 'Y'";
   break;

 default:
   break;
}

$stmt .= " order by ship_date, trailer_num, destination, carrier_name, calloff_num, order_num";

// execute the query
$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

$data = array();
$fixed_total = 0;
$surcharge_total = 0;
$charged_total = 0;
$control_total = 0;
$paid_total = 0;
$control_bal_total = 0;
$actual_bal_total = 0;

while (ora_fetch_into($cursor1, $order, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
  // get carrier rating
  $stmt = "select * from freight_rate_matrix where carrier = '" . $order['CARRIER_NAME'] . 
    "' and destination = '" . $order['DESTINATION'] . "'";
  $ora_success = ora_parse($cursor2, $stmt);
  $ora_success = ora_exec($cursor2);
  ora_fetch_into($cursor2, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  $rows = ora_numrows($cursor2);

  if ($rows > 0) {
    $rating = $row['CARRIER_RATING'];
  } else {
    $rating = 3;
  }

  // process one order at a time
  $order_num = $order['ORDER_NUM'];
  $ship_date = date('m/d/y', strtotime($order['SHIP_DATE']));
  $freight_status = ($order['FREIGHT_RECONCILED'] == "Y" ? "Yes" : "No");

  $control_amt = $order['FREIGHT_EXPECTED'];
  $paid_amt = $order['FREIGHT_PAID'];

  // reset variables
  $fixed = 0;
  $surcharge = 0;
  $charged_amt = 0;

  if ($order['FIXED_CHARGE'] != "") {
    $fixed = $order['FIXED_CHARGE'];
    $charged_amt = $order['FIXED_CHARGE'];
  }

  if ($order['SURCHARGE'] != "") {
    $surcharge = $order['SURCHARGE'];
    $charged_amt += $order['SURCHARGE'];
  }

  $control_balance = $paid_amt - $control_amt;

  if ($charged_amt > 0 || $paid_amt > 0) {
    $actual_balance = $charged_amt - $paid_amt;
  } else {
    $actual_balance = 0;
  }

  // update totals
  $fixed_total += $fixed;
  $surcharge_total += $surcharge;
  $charged_total += $charged_amt;
  $control_total += $control_amt;
  $paid_total += $paid_amt;
  $control_bal_total += $control_balance;
  $actual_bal_total += $actual_balance;
  
  // format numbers for printout
  $fixed = ($fixed > 0 ? number_format($fixed, 2, '.', ',') : "");
  $surcharge = ($surcharge > 0 ? number_format($surcharge, 2, '.', ',') : "");
  $charged_amt = ($charged_amt > 0 ? number_format($charged_amt, 2, '.', ',') : "");
  $paid_amt = ($paid_amt > 0 ? number_format($paid_amt, 2, '.', ',') : "");
  $control_amt = ($control_amt > 0 ? number_format($control_amt, 2, '.', ',') : "");
  $control_balance = ($control_balance != 0 ? number_format($control_balance, 2, '.', ',') : "");
  $actual_balance = ($actual_balance != 0 ? number_format($actual_balance, 2, '.', ',') : "");

  // write each row
  array_push($data, array('calloff'=>$order['CALLOFF_NUM'], 'order_num'=>$order_num, 'ship_date'=>$ship_date, 
			  'trailer'=>$order['TRAILER_NUM'], 'dest'=>$order['DESTINATION'], 
			  'carrier'=>$order['CARRIER_NAME'] . " - " . $rating, 'status'=>$freight_status, 'tons'=>$order['TONS'], 
			  'packages'=>$order['PACKAGES'], 'fixed'=>$fixed, 'surcharge'=>$surcharge, 
			  'charged'=>$charged_amt, 'control'=>$control_amt, 'control_bal'=>$control_balance, 
			  'paid'=>$paid_amt, 'actual_bal'=>'<b>' . $actual_balance . '</b>', 
			  'url'=>"http://$hostname/inventory/holmen/reporting/print_bol.php?order_num=$order_num"));
}

// format numbers for printout
$fixed_total_pre = $fixed_total;
$surcharge_total_pre = $surcharge_total;
$fixed_total = ($fixed_total > 0 ? number_format($fixed_total, 2, '.', ',') : "");
$surcharge_total = ($surcharge_total > 0 ? number_format($surcharge_total, 2, '.', ',') : "");
$charged_total = ($charged_total > 0 ? number_format($charged_total, 2, '.', ',') : "");
$control_total = ($control_total != 0 ? number_format($control_total, 2, '.', ',') : "");
$control_bal_total = ($control_bal_total != 0 ? number_format($control_bal_total, 2, '.', ',') : "");
$paid_total = ($paid_total > 0 ? number_format($paid_total, 2, '.', ',') : "");
$actual_bal_total = ($actual_bal_total != 0 ? number_format($actual_bal_total, 2, '.', ',') : "");

$tons = number_format($tons, 2, '.', ',');

// write totals
array_push($data, array('calloff'=>'Totals:', 'order_num'=>'', 'ship_date'=>'', 'trailer'=>'', 'dest'=>'', 'carrier'=>'', 
			'status'=>'', 'tons'=>'', 'packages'=>'', 'fixed'=>$fixed_total, 'surcharge'=>$surcharge_total, 
			'charged'=>$charged_total, 'control'=>$control_total, 'control_bal'=>$control_bal_total,
			'paid'=>$paid_total, 'actual_bal'=>'<b>' . $actual_bal_total . '</b>', 'url'=>''));

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','landscape');
$pdf->openHere('XYZ', 0, 800, 1.25);
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

// report header
$pdf->ezSetDy(-5);
$pdf->ezText("<b>Port Of Wilmington  -  Freight Charge Report</b>", 16, $center);
$pdf->ezSetDy(-25);

// write general information
$general_info = array();
array_push($general_info, array('first'=>'<b>Customer: Holmen Paper AB</b>', 
				'second'=>"<b>Shipping Date: $date_range</b>", 
				'third'=>"<b>As Of " . date("m/d/Y H:i:s") . "</b>"));

$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>''), '',
	      array('cols'=>array('first'=>array('justification'=>'left'), 
				  'second'=>array('justification'=>'center'), 
				  'third'=>array('justification'=>'right')),
		    'showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>780));
$pdf->ezSetDy(-15);

// write the data table
$pdf->ezTable($data, array('calloff'=>'Transport / Call Off', 'order_num'=>'Deliverynote / BOL #', 
			   'ship_date'=>'Date', 'trailer'=>'Trip Id / Trailer Id', 
			   'dest'=>'Destination', 'status'=>'Recon', 'tons'=>'Tons', 'packages'=>'Pkgs', 
			   'control'=>'Expected Value', 'paid'=>'Holmen Received', 'control_bal'=>'Received Variance', 
			   'carrier'=>'Carrier', 'fixed'=>'Trucking Fixed', 'surcharge'=>'Trucking Surcharge', 
			   'charged'=>'Total Paid', 'actual_bal'=>'Paid less Received'),
	      '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>790, 'fontSize'=>8, 'xPos'=>'center', 
			'cols'=>array('calloff'=>array('width'=>45),
				      'order_num'=>array('width'=>60, 'link'=>'url', 'justification'=>'center'),
				      'ship_date'=>array('width'=>48, 'justification'=>'center'),
				      'trailer'=>array('width'=>45),
				      'dest'=>array('width'=>70),
				      'carrier'=>array('width'=>65),
				      'status'=>array('width'=>35, 'justification'=>'center'),
				      'tons'=>array('width'=>40, 'justification'=>'right'),
				      'packages'=>array('width'=>35, 'justification'=>'right'),
				      'fixed'=>array('width'=>45, 'justification'=>'right'),
				      'surcharge'=>array('width'=>52, 'justification'=>'right'),
				      'charged'=>array('width'=>40, 'justification'=>'right'),
				      'control'=>array('width'=>50, 'justification'=>'right'),
				      'control_bal'=>array('width'=>50, 'justification'=>'right'),
				      'paid'=>array('width'=>48, 'justification'=>'right'),
				      'actual_bal'=>array('width'=>48, 'justification'=>'right'))));

// print average surcharge rate
if ($fixed_total > 0) {
  $surcharge_percent = round($surcharge_total_pre / $fixed_total_pre * 100, 2);
}

$pdf->ezSetDy(-15);
$pdf->ezText("<b>Actual Surcharge Rate:  $surcharge_percent%</b>", 12, $left);

// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");

?>
