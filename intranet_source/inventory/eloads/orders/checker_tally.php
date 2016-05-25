<?
// Code to generate PDF file of Vessel Discharge Report for a specific mark
  include("pow_session.php");
  include("../eloads_globals.php");
  $conn = ora_logon(RF, PASS);
  $cursor1 = ora_open($conn);
  $cursor2 = ora_open($conn);


if($eport_customer_id != 0){
  $stmt = "select to_char(A.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_OF_ACTIVITY, QTY_CHANGE, QTY_LEFT, SERVICE_CODE, PALLET_ID, ACTIVITY_ID, ARRIVAL_NUM, C.CUSTOMER_NAME from cargo_activity A, customer_profile C where A.customer_id = C.customer_id and order_num like '%$order_num%' and A.customer_id = '$eport_customer_id' order by order_num, date_of_activity";
}
else{
  $stmt = "select to_char(A.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_OF_ACTIVITY, QTY_CHANGE, QTY_LEFT, SERVICE_CODE, PALLET_ID, ACTIVITY_ID, ARRIVAL_NUM, C.CUSTOMER_NAME from cargo_activity A, customer_profile C where A.customer_id = C.customer_id and order_num like '%$order_num%' order by order_num, date_of_activity";
}

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);
ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CARGO_TRACKING and 
	  CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

if($rows1 == 0){
  // no records have a similar order number
?>

<html>
<head>
<title>Eport - Activity Reports</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">
                  <br />Port of Wilmington Activity Report<br /></font>
	          <hr>
	          <br />
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
                  <font size = "2" face="Verdana">
                  No orders having an order number like <b><?= $order_num ?></b> were shipped out of the Port of Wilmington! 
                  <br /><br />Please <a href="./">go back</a> and re-enter the order number you would like to see.
                  </font>
                  <br /><br /><br />
		  <hr>
		  <font size = "2" face="Verdana">Port of Wilmington, <?= $today ?>, Visited by <?= $user ?></font>
	       </td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

</body>
</html>

<?
   exit;
}

$customer_name = $row1['CUSTOMER_NAME'];
$date_of_activity = date('m/d/Y', strtotime($row1['DATE_OF_ACTIVITY']));
$employee = $row1['ACTIVITY_ID'];
$arrival_num = $row1['ARRIVAL_NUM'];
$service_code = $row1['SERVICE_CODE'];

// Set the checker name
$per_stmt = "select LOGIN_ID from per_owner.personnel where employee_id = '$employee'";
$ora_success = ora_parse($cursor2, $per_stmt);
$ora_success = ora_exec($cursor2);
$rows3 = ora_numrows($cursor2);
if (!$ora_success){
  // close Oracle connection
  ora_close($cursor2);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From PER_OWNER.PERSONNEL. Please Try Again Later.");
  exit;
}
if($rows3 >= 0){
  ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $checker = $row3['LOGIN_ID'];
}
else{
  $checker = "UNKNOWN";
}

// Get the Vessel name
$per_stmt = "select VESSEL_NAME from vessel_profile where arrival_num = '$arrival_num'";
$ora_success = ora_parse($cursor2, $per_stmt);
$ora_success = ora_exec($cursor2);
$rows3 = ora_numrows($cursor2);
if (!$ora_success){
  // close Oracle connection
  ora_close($cursor2);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From VESSEL_PROFILE. Please Try Again Later.");
  exit;
}
if($rows3 >= 0){
  ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $vessel_name = $row3['VESSEL_NAME'];
}
else{
  $vessel_name = "UNKNOWN";
}
if($vessel_name == "")
  $vessel_name = "UNKNOWN";

// Get the Shipment name
$per_stmt = "select SERVICE_NAME from service_category where service_code = '$service_code'";
$ora_success = ora_parse($cursor2, $per_stmt);
$ora_success = ora_exec($cursor2);
$rows3 = ora_numrows($cursor2);
if (!$ora_success){
  // close Oracle connection
  ora_close($cursor2);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From SERVICE_CATEGORY. Please Try Again Later.");
  exit;
}
if($rows3 >= 0){
  ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $shipment = $row3['SERVICE_NAME'];
}
else{
  $shipment = "UNKNOWN";
}


// initiate pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','portriat');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

// write out the intro. for the 1st page
$pdf->ezText("<b>Port of Wilmington Tally</b>", 16, $center);
$pdf->ezText("\n", 10, $center);
$pdf->ezSetDy(-25);

$general_info = array();
$lot_info = array();
$damage_info = array();

// general information
array_push($general_info, array('first'=>"Customer:  $customer_name",
				'second'=>"Order Number:  $order_num",
				'third'=>"Date:  $date_of_activity"));
array_push($general_info, array('first'=>"Checker:  $checker",
				'second'=>"",
				'third'=>"Shipment: $shipment"));


$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>''), 
	      '', array('cols'=>array('first'=>array('justification'=>'left'), 
				      'second'=>array('justification'=>'left'),
				      'third'=>array('justification'=>'left')),
			'showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>12, 'width'=>540));


$pdf->ezSetDy(-25);

$start_time = date('H:i:s', strtotime($row1['DATE_OF_ACTIVITY']));
$pallets = 0;
$ctns = 0;

// process one fetched record at a time
do {
  $pallets++;
  $end_time = date('H:i:s', strtotime($row1['DATE_OF_ACTIVITY']));
  // Get some information about the pallet
  $pallet_id = $row1['PALLET_ID'];
  $qty = $row1['QTY_CHANGE'];
  $ctns += $qty;

  if($eport_customer_id != 0){
    $stmt2 = "select MARK, CARGO_DESCRIPTION, C.COMMODITY_NAME, V.VESSEL_NAME from cargo_tracking T, commodity_profile C, vessel_profile V where T.arrival_num = V.arrival_num and T.commodity_code = C.commodity_code and pallet_id = '$pallet_id' and receiver_id = '$eport_customer_id'";
  }
  else{
    $stmt2 = "select MARK, CARGO_DESCRIPTION, C.COMMODITY_NAME from cargo_tracking T, commodity_profile C where T.commodity_code = C.commodity_code and pallet_id = '$pallet_id'";
  }
  $ora_success = ora_parse($cursor2, $stmt2);
  $ora_success = ora_exec($cursor2);

  if (!$ora_success){
    // close Oracle connection
    ora_close($cursor2);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From CARGO_TACKING. Please Try Again Later.");
    exit;
  }
  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  // add the line item
  array_push($lot_info, array('mark'=>$row2['MARK'], 'desc'=>$row2['CARGO_DESCRIPTION'], 'comm'=>$row2['COMMODITY_NAME'], 
			      'pallet'=>$row1['PALLET_ID'], 'qty'=>$row1['QTY_CHANGE'], 'qty_left'=>$row2['VESSEL_NAME'],
			      'comments'=>$row1['ACTIVITY_DESCRIPTION'],
			      'time'=>$row1['DATE_OF_ACTIVITY']));

} while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)); 

array_push($lot_info, array('mark'=>"Total:", 'desc'=>'', 'comm'=>'', 
			    'pallet'=>$pallets, 'qty'=>$ctns, 'qty_left'=>'',
			    'comments'=>'',
			    'time'=>''));


$pdf->ezText("Start Time: $start_time - End Time: $end_time");
$pdf->ezSetDy(-10);

// write out vessel discharge information
$pdf->ezText("<b>Order Information:</b>", 12, $left);
$pdf->ezSetDy(-10);
$pdf->ezTable($lot_info, array('mark'=>'Lot/Mark', 'desc'=>'Desc', 'comm'=>'Comm', 
			      'pallet'=>'Pallet ID', 'qty'=>'Quantity',
			      'qty_left'=>'Vessel', 'comments'=>'Comments',
			      'time'=>'Time Scanned'), 
	      '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>540, 'showLines'=>2, 
			'cols'=>array('mark'=>array('justification'=>'center'),
				      'desc'=>array('justification'=>'center'),
				      'comm'=>array('justification'=>'center'),
				      'pallet'=>array('justification'=>'center'),
				      'qty'=>array('justification'=>'center'),
				      'qty_left'=>array('justification'=>'center'),
				      'comments'=>array('justification'=>'center'),
				      'time'=>array('justification'=>'center'))));

// write the footer
$today = date('m/j/y'); 
$format = "Port of Wilmington, Printed: " . $today . "    Pallet Total: $pallets";
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
$pdf->addText(50,34,7,$format);
$pdf->line(30,40,575,40);
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all,'all');


// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");

?>
