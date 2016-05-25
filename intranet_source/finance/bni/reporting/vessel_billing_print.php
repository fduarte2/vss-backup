<?
// Lynn F. Wang  (10-JUN-05)
// Prints detailed information for Holmen Ves

// header and leftnav
include("pow_session.php");
$user = $userdata['username'];

// form data
$vessel = $HTTP_POST_VARS["vessel"];
list($lr_num, $vessel_name, $ship_sail_date) = split(",", $vessel, 3);

$ship_sail_date = date("m/d/Y", strtotime($ship_sail_date));

// initiate the pdf writer
include 'class.ezpdf.php';
$pdf = new Cezpdf('letter','portrait');
$pdf -> ezSetMargins(20,30,30,30);
$pdf->openHere('XYZ', 0, 800, 1);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

// title
$pdf->ezSetDy(-5);
$pdf->ezText("<b>Holmen Vessel Billing Report</b>", 14, $center);
$pdf->ezSetDy(-15);

// write vessel info
$data = array();
array_push($data, array('first'=>"<b>Vessel:</b>", 'second'=>"<b>$lr_num - $vessel_name</b>", 
			'third'=>"<b>Vessel Sailed on:</b>", 'fourth'=>"<b>$ship_sail_date</b>"));

$pdf->ezTable($data, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''),
	      '', array('cols'=>array('first'=>array('justification'=>'right', 'width'=>70),
				      'second'=>array('justification'=>'left', 'widht'=>220),
				      'third'=>array('justification'=>'right', 'widht'=>210),
				      'fourth'=>array('justification'=>'left', 'width'=>80)), 
			'showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>580));
$pdf->ezSetDy(-10);

include("defines.php");
include("connect.php");

// retrieve information from the database
$conn = ora_logon("PAPINET@RF", "OWNER");
$cursor = ora_open($conn);

$stmt = "select pow_arrival_num, arrival_num, mill_order_num, barcode, date_received, orig_gross_weight from cargo_tracking where date_received is not null and edi_goodsreceipt = 'Y' and (vessel_bill is null or vessel_bill <> 'Y') and pow_arrival_num = '$lr_num' order by arrival_num, mill_order_num, barcode";
ora_parse($cursor, $stmt);
ora_exec($cursor);

ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows = ora_numrows($cursor);

if ($rows == 0 || $row['POW_ARRIVAL_NUM'] == "") {
  header("Location: vessel_billing.php?input=$lr_num");
  exit;
}

// initialize variables
// one vessel might have more than one voyage # and we make one bill for each voyage #
$voyage_total = 0;  
$vessel_total = 0;
$curr_voyage = "";
$curr_mill_order_num = "";
$first_entry = true;
$data = array();

// display data
do {
  $voyage = $row['ARRIVAL_NUM'];
  if ($voyage == $curr_voyage) {
    $voyage = "";
    $voyage_total += $row['ORIG_GROSS_WEIGHT'];
  } else {
    // new voyage, print voyage sub-total
    if (!$first_entry) {
      $voyage_total = round($voyage_total/1000, 2);

      array_push($data, array('voyage'=>'', 
			      'mill_order'=>'', 
			      'barcode'=>'', 
			      'date_received'=>'Voyage Sub-Total (MT):',
			      'gross_weight'=>$voyage_total));
      // add a blank line
      array_push($data, array('voyage'=>'', 'mill_order'=>'', 'barcode'=>'', 'date_received'=>'', 'gross_weight'=>''));
    } else {
      //unset the flag
      $first_entry = false;
    }
      
    $voyage_total = $row['ORIG_GROSS_WEIGHT'];
    $curr_voyage = $voyage;
  }
  
  $mill_order_num = $row['MILL_ORDER_NUM'];
  if($mill_order_num == $curr_mill_order_num) {
    $mill_order_num = "";
  } else {
    $curr_mill_order_num = $mill_order_num;
  }

  $date_received = date("m/d/Y", strtotime($row['DATE_RECEIVED']));

  // print current line
  array_push($data, array('voyage'=>$voyage, 
			  'mill_order'=>$mill_order_num, 
			  'barcode'=>$row['BARCODE'], 
			  'date_received'=>$date_received,
			  'gross_weight'=>$row['ORIG_GROSS_WEIGHT']));

  $vessel_total += $row['ORIG_GROSS_WEIGHT'];
} while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

// print last voyage sub-total
$voyage_total = round($voyage_total/1000, 2);
array_push($data, array('voyage'=>'', 
			'mill_order'=>'', 
			'barcode'=>'', 
			'date_received'=>'Voyage Sub-Total:',
			'gross_weight'=>$voyage_total . " MT"));

// add a blank line
array_push($data, array('voyage'=>'', 'mill_order'=>'', 'barcode'=>'', 'date_received'=>'', 'gross_weight'=>''));

// print vessel total
$vessel_total = round($vessel_total/1000, 2);
array_push($data, array('voyage'=>'', 
			'mill_order'=>'', 
			'barcode'=>'', 
			'date_received'=>'Vessel Total:',
			'gross_weight'=>$vessel_total . " MT"));



$pdf->ezTable($data, array('voyage'=>"Voyage #", 'mill_order'=>"Mill Order #", 'barcode'=>"Roll ID", 
			   'date_received'=>'Date Scanned In', 'gross_weight'=>'Gross Weight (KG)'), 
	      '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>580));

$today = date('m/j/y');
$format = "Port of Wilmington, " . $today;

$pdf->line(20,40,578,40);
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(20,822,578,822);
$pdf->addText(50,34,6, $format);
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all,'all');

// redirect to a temporary PDF file instead of directly writing to the browser
include("redirect_pdf.php");

?>
