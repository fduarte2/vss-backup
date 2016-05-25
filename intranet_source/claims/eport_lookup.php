<?
  include("pow_session.php");

   $ccd_lot_id = $HTTP_COOKIE_VARS["ccd_lot_id"];
   if ($ccd_lot_id == "") {
     header("Location: add_ccds.php");
     exit;
   }
   
include("connect.php");

$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$pg_conn){
  printf("Could not open connection to database server");
  exit;
}

$stmt = "select lr_num, mark from ccd_received where ccd_lot_id = '$ccd_lot_id'";
$result = pg_query($pg_conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($pg_conn));
$row = pg_fetch_array($result, 0, PGSQL_ASSOC);
$mark = $row['mark'];
$lr_num = $row['lr_num'];

$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create two cursors
$cursor1 = ora_open($ora_conn);
if (!$cursor1) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor1));
  exit;
}               
$cursor2 = ora_open($ora_conn);
if (!$cursor2) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor2));
  exit;
}               

// Code to generate PDF file of Vessel Discharge Report for a specific mark

$stmt = "select lot_id, mark, po_num, C.customer_short_name customer, 
                to_char(date_received, 'MM/DD/YY HH24:MI:SS') date_received,
                qty_expected, qty_received, (qty_received - qty_expected)
  	        qty_not_received, pallet_count_expected, pallet_count_received, 
	        (pallet_count_received - pallet_count_expected) pallet_count_not_received
         from CCD_CARGO_TRACKING T, CCD_CUSTOMER_PROFILE C 
         where arrival_num = '$lr_num' and mark like '%$mark%' and T.receiver_id = C.customer_id 
         order by lot_id, mark, po_num";

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);

ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows1 = ora_numrows($cursor1);

if (!$ora_success) {
  // close Oracle connection
  ora_close($cursor1);
  ora_logoff($ora_conn);
  printf("Oracle Error Occurred While Retrieving Data From CCD_CARGO_TRACKING and 
	  CCD_CUSTOMER_PROFILE. Please Try Again Later.");
  exit;
}

if ($rows1 == 0) {
  // no records have a similar mark as the inputed mark
?>

<html>
<head>
<title>Eport - Vessel Discharge Reports</title>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "100%" valign = "top">
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td align="center">
                  <font size="5" face="Verdana" color="#0066CC">
                  <br />Port of Wilmington Vessel Discharge Report<br /></font>
	          <hr>
	          <br />
                  <font size = "3" face="Verdana" color="#0066CC"><?= $lr_num ?> - <?= $vessel_name ?></font>
	          <br /><br />
                  <font size = "2" face="Verdana">As Of <?= $today ?> EST</font>
	          <br /><br />
	       </td>
	    </tr>
	 </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	    <tr>
	       <td align="center">
                  <font size = "2" face="Verdana">
                  No cargos having a Mark like <b><?= $mark ?></b> were shipped into the Port of Wilmington with 
                  this vessel! 
                  <br /><br />Please <a href="./">go back</a> and re-enter the mark you would like to see.
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

// initiate pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','landscape');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

// write out the intro. for the 1st page
$pdf->ezText("Vessel Discharge Report By Mark", 16, $center);
$pdf->ezText("\n", 10, $center);
$pdf->ezText("<b>COLD CHAIN DISTRIBUTION SERVICES</b>", 12, $left);
$pdf->ezText("1 Hausel Road                                                                                                                                                              Phone (302) 472-5631", 12, $left);
$pdf->ezText("Wilmington, Delaware 19801-5852                                                                                                                                  FAX (302) 472-5635", 12, $left);
$pdf->ezSetDy(-25);

$general_info = array();
$lot_info = array();
$damage_info = array();

// general information
array_push($general_info, array('first'=>"Vessel:  $lr_num - $vessel_name",
				'second'=>"Mark:  $mark",
				'third'=>"Date:  $today EST"));


$pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>''), 
	      '', array('cols'=>array('first'=>array('justification'=>'left'), 
				      'second'=>array('justification'=>'center'),
				      'third'=>array('justification'=>'right')),
			'showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>12, 'width'=>670));


$pdf->ezSetDy(-25);

// process one fetched record at a time
do {
  // add the line item
  array_push($lot_info, array('lot'=>$row1['LOT_ID'], 'mark'=>$row1['MARK'], 'po'=>$row1['PO_NUM'], 
			      'customer'=>$row1['CUSTOMER'], 'cases_expected'=>$row1['QTY_EXPECTED'],
			      'cases_received'=>$row1['QTY_RECEIVED'], 'cases_short'=>$row1['QTY_NOT_RECEIVED'],
			      'pallets_expected'=>$row1['PALLET_COUNT_EXPECTED'],
			      'pallets_received'=>$row1['PALLET_COUNT_RECEIVED'], 
			      'pallets_short'=>$row1['PALLET_COUNT_NOT_RECEIVED'],
			      'time'=>$row1['DATE_RECEIVED']));

  // check to see if we need to add Damage line
  $stmt = "select d.pallet_id, d.qty_damaged, p.description
           from CCD_CARGO_DAMAGED d, CCD_DAMAGE_PROFILE p 
           where d.arrival_num = '$lr_num' and d.damage_type_id = p.damage_type_id 
           and d.lot_id = '" . $row1['LOT_ID'] . "' and d.mark = '" . $row1['MARK'] . "'";
  
  $ora_success = ora_parse($cursor2, $stmt);
  $ora_success = ora_exec($cursor2);

  ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rows2 = ora_numrows($cursor2);
  
  if (!$ora_success) {
    // close Oracle connection
    ora_close($cursor2);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From CCD_CARGO_DAMAGED and 
	                       CCD_CUSTOMER_PROFILE. Please Try Again Later.");
    exit;
  }
  
  if ($rows2 > 0) {
    // add damage line
    array_push($damage_info, array('lot'=>$row1['LOT_ID'], 'mark'=>$row1['MARK'], 'pallet_id'=>$row2['PALLET_ID'], 
				   'qty_damaged'=>$row2['QTY_DAMAGED'], 'desc'=>$row2['DESCRIPTION']));
  }
} while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)); 


// write out vessel discharge information
$pdf->ezText("Vessel Discharge Information:", 12, $left);
$pdf->ezSetDy(-10);
$pdf->ezTable($lot_info, array('lot'=>'Lot ID', 'mark'=>'Mark', 'po'=>'PO #', 
			      'customer'=>'Customer', 'cases_expected'=>'Cases Expected',
			      'cases_received'=>'Cases Rcvd', 'cases_short'=>'Cases Short',
			      'pallets_expected'=>'Pallets Expected',
			      'pallets_received'=>'Pallets Rcvd', 
			      'pallets_short'=>'Pallets Short',
			      'time'=>'Time Scanned'), 
	      '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2, 
			'cols'=>array('lot'=>array('justification'=>'center'),
				      'mark'=>array('justification'=>'center'),
				      'po'=>array('justification'=>'center'),
				      'customer'=>array('justification'=>'center'),
				      'cases_expected'=>array('justification'=>'center'),
				      'cases_received'=>array('justification'=>'center'),
				      'cases_short'=>array('justification'=>'center'),
				      'pallets_expected'=>array('justification'=>'center'),
				      'pallets_received'=>array('justification'=>'center'),
				      'pallets_short'=>array('justification'=>'center'),
				      'time'=>array('justification'=>'center'))));
$pdf->ezSetDy(-30);

// write out damage information
if (count($damage_info) > 0) {
  $pdf->ezText("Damage Information:", 12, $left);
  $pdf->ezSetDy(-10);
  $pdf->ezTable($damage_info, array('lot'=>'Lot ID', 'mark'=>'Mark', 'pallet_id'=>'Pallet ID', 
				    'qty_damaged'=>'Cases Damaged', 'desc'=>'Damage Reason'),
		'', array('cols'=>array('lot'=>array('justification'=>'center'), 
					'mark'=>array('justification'=>'center'), 
					'pallet_id'=>array('justification'=>'center'), 
					'qty_damaged'=>array('justification'=>'center'), 
					'desc'=>array('justification'=>'center')),
			  'showHeadings'=>1, 'shaded'=>0, 'width'=>725, 'showLines'=>2));


} else {
  $pdf->ezText("Damage Information:", 12, $left);
  $pdf->ezSetDy(-10);
  $pdf->ezText("No damage to report for the listed mark(s).", 12, $left);
}

$pdf->ezSetDy(-15);

// write the footer
$today = date('m/j/y');
$format = "Port of Wilmington, " . $today;
$pdf->line(30,40,750,40);
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(30,822,750,822);
$pdf->addText(50,34,7,$format);
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all,'all');

// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");

?>
