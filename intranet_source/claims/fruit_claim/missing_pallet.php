<?
/*
*	Adam Walter, Jan/Feb 2008.
*	A PDF output that shows all "missing" pallets.
**********************************************************************/

include("pow_session.php");
$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

include("../claim_function.php");


// make Oracle connection
$conn = ora_logon("SAG_OWNER@RF", "OWNER");
if($conn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	printf(ora_errorcode($conn));
	exit;
}
$cursor = ora_open($conn);

$submit = $HTTP_POST_VARS['submit'];
$start_date = $HTTP_POST_VARS['start_date'];
$end_date = $HTTP_POST_VARS['end_date'];

// Initiate PDF
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(20,20,65,65);
	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->ezSetDy(-20);
	$pdf->ezText("<b>Missing Pallet Report</b>", 16, $center);
	$pdf->ezSetDy(-5);
	if($submit != ""){
		$pdf ->ezText("<b>".$start_date." - ".$end_date."</b>", 12, $center);
		$pdf ->ezSetDy(-5);
	}

   $block_heading = array('barcode'=>'<b>Barcode</b>',
			'ship'=>'<b>Vessel</b>',
			'daterec'=>'<b>Date Received</b>',
			'comm'=>'<b>Commodity</b>',
			'origrec'=>'<b>Received</b>',
			'missing'=>'<b>Missing</b>');

	$output_array = array();

	$arrCol = array('barcode'=>array('justification'=>'left'),
			'ship'=>array('width'=>150, 'justification'=>'center'),
			'daterec'=>array('width'=>120, 'justification'=>'center'),
			'comm'=>array('width'=>80, 'justification'=>'center'),
			'origrec'=>array('width'=>70, 'justification'=>'center'),
			'missing'=>array('width'=>70, 'justification'=>'center'));

// top level SQL.
	$sql = "SELECT CUSTOMER_NAME, CT.RECEIVER_ID CUST_NUM, VP.LR_NUM || '-' || VP.VESSEL_NAME THE_VESSEL, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_REC, CT.PALLET_ID THE_PALLET, QTY_CHANGE, COMMODITY_NAME, QTY_RECEIVED FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CUSTOMER_PROFILE	CP,	COMMODITY_PROFILE CPRO, VESSEL_PROFILE VP 
			WHERE CT.PALLET_ID = CA.PALLET_ID AND 
			CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND
			CT.RECEIVER_ID = CA.CUSTOMER_ID AND
			CT.COMMODITY_CODE = CPRO.COMMODITY_CODE AND
			CT.ARRIVAL_NUM = VP.LR_NUM AND
			CT.RECEIVER_ID = CP.CUSTOMER_ID AND
			CA.ORDER_NUM = 'MISSING'
			AND CA.SERVICE_CODE = '6'
			AND (CA.ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID') ";
	if ($submit != ""){
		$sql .= "AND DATE_RECEIVED >= to_date('".$start_date."', 'MM/DD/YYYY') AND DATE_RECEIVED <= to_date('".$end_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
	}
			$sql .= "ORDER BY CUSTOMER_NAME, THE_VESSEL, THE_REC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// nothing to report
		$pdf->ezText("<b>No Missing pallets</b>", 12, $center);
	} else {
		$pdf->ezText("<b>".$row['CUSTOMER_NAME']."</b>", 12, $left);
		$pdf->ezSetDy(-10);

		$current_cust = $row['CUST_NUM'];
		$cust_pallets = 0;
		$cust_missing = 0;
		$total_pallets = 0;
		$total_missing = 0;
		$total_customers = 1;

		// now, into the loop.
		do {
			// first, figure out if this is a new customer.
			if($current_cust != $row['CUST_NUM']){
				// end of section.
				// tag the totals on the bottom
				array_push($output_array, array('barcode'=>'<b>Sub-Total for '.$current_cust.'</b>',
						'ship'=>' ',
						'daterec'=>'<b>'.$cust_pallets.' Plts</b>',
						'comm'=>' ',
						'origrec'=>' ',
						'missing'=>'<b>'.$cust_missing.'</b>'));

				$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>10, 'width'=>680,'cols'=>$arrCol));
				$pdf->ezSetDy(-10);

				$current_cust = $row['CUST_NUM'];
				$cust_pallets = 0;
				$cust_missing = 0;
				$total_customers++;

				$pdf->ezText("<b>".$row['CUSTOMER_NAME']."</b>", 12, $left);
				$pdf->ezSetDy(-10);

				$output_array = array();

			}

			// do this regardless, as long as we have rows still.
			$cust_pallets++;
			$cust_missing += $row['QTY_CHANGE'];
			$total_pallets++;
			$total_missing += $row['QTY_CHANGE'];
			array_push($output_array, array('barcode'=>$row['THE_PALLET'],
					'ship'=>$row['THE_VESSEL'],
					'daterec'=>$row['THE_REC'],
					'comm'=>$row['COMMODITY_NAME'],
					'origrec'=>$row['QTY_RECEIVED'],
					'missing'=>$row['QTY_CHANGE']));

		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		// finish off the report.  same logic as customer-end block from above,
		// just minus the variable-resetting-per-loop parts.
		array_push($output_array, array('barcode'=>'<b>Sub-Total for '.$current_cust.'</b>',
				'ship'=>' ',
				'daterec'=>'<b>'.$cust_pallets.' Plts</b>',
				'comm'=>' ',
				'origrec'=>' ',
				'missing'=>'<b>'.$cust_missing.'</b>'));

		$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>10, 'width'=>680,'cols'=>$arrCol));
		$pdf->ezSetDy(-10);


		$pdf->ezText("<b> Grand Totals:</b>", 16, $left);
		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>     Customers: ".$total_customers."</b>", 12, $left);
		$pdf->ezText("<b>     Pallets: ".$total_pallets."</b>", 12, $left);
		$pdf->ezText("<b>     Missing: ".$total_missing."</b>", 12, $left);

	}

	// redirect to a temporary PDF file instead of directly writing to the browser
	include("redirect_pdf.php");
?>
