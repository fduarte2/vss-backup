<?
/*
*	Adam Walter, Jan/Feb 2008.
*	See description in OSD.php for details.
*
*	MAJOR EDIT JULY 2008:
*	Apparently, this page needs to have the options of breaking
*	Down by either vessel-customer OR vessel-commodity.
*	I am therefore changing OSD.php and this page
*	With 1 big if statement to accomodate this.
**********************************************************************/

include("pow_session.php");
$user = $userdata['username'];
$hostname = $HTTP_SERVER_VARS['HTTP_HOST'];

// connect to PostgreSQL
include("defines.php");
include("connect.php");

include("../claim_function.php");
//include("../claims/claim_function.php");


// make Oracle connection
$conn = ora_logon("SAG_OWNER@RF", "OWNER");
if($conn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	printf(ora_errorcode($conn));
	exit;
}
$cursor = ora_open($conn);

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
   $pdf->ezText("<b>OS & D Report, $start_date --- $end_date</b>", 16, $center);
   $pdf->ezSetDy(-5);

	if($submit == "Vessel-Customer Report"){
	   $block_heading = array('barcode'=>'<b>Barcode</b>',
				'desc'=>'<b>Description</b>',
				'daterec'=>'<b>Date Received</b>',
				'origrec'=>'<b>Received</b>',
				'shipped'=>'<b>Shipped</b>',
				'dmg'=>'<b>Damaged</b>', 
				'recoup' => '<b>Recoup</b>');

		$output_array = array();

		$arrCol = array('barcode'=>array('justification'=>'left'),
				'desc'=>array('width'=>100, 'justification'=>'center'),
				'daterec'=>array('width'=>80, 'justification'=>'center'),
				'origrec'=>array('width'=>70, 'justification'=>'center'),
				'shipped'=>array('width'=>70, 'justification'=>'center'),
				'dmg'=>array('width'=>70, 'justification'=>'center'),
				'recoup'=>array('width'=>70,'justification'=>'right'));

	// top level SQL.
		$sql = "SELECT PALLET_ID, NVL(QTY_DAMAGED, 0) THE_DMG, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE, QTY_RECEIVED,
				NVL((SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL), 0) THE_SHIPPED,
				NVL((SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '9' AND ACTIVITY_DESCRIPTION IS NULL), 0) THE_RECOUP,
				RECEIVER_ID CUST_NUM,
				CUSTOMER_NAME,
				COMMODITY_NAME,
				VP.LR_NUM || '-' || VP.VESSEL_NAME THE_VESSEL
				FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
				WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.DATE_RECEIVED > TO_DATE('".$start_date."', 'MM/DD/YYYY')
				AND CT.DATE_RECEIVED < TO_DATE('".$end_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
				AND (CT.QTY_DAMAGED != 0
					OR
					(SELECT NVL(SUM(QTY_CHANGE), CT.QTY_RECEIVED) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL) != CT.QTY_RECEIVED
					OR
					(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '9' AND ACTIVITY_DESCRIPTION IS NULL) != 0
					)
				AND (CT.COMMODITY_CODE BETWEEN 1 AND 1600
					OR
					CT.COMMODITY_CODE BETWEEN 8000 AND 8999)
				ORDER BY THE_VESSEL, CUSTOMER_NAME, PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// nothing to report
			$pdf->ezText("<b>No OS&D pallets for specified date range.</b>", 12, $center);
		} else {
			// ok, we have items.  Time to get PDF-alicious.
			// First header and starting info.
			$pdf->ezText("<b>".$row['THE_VESSEL']."</b>", 14, $center);
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>".$row['CUSTOMER_NAME']."</b>", 12, $left);
			$pdf->ezSetDy(-10);

			$current_vessel = $row['THE_VESSEL'];
			$current_cust = $row['CUST_NUM'];
			$cust_pallets = 0;
			$cust_damaged = 0;
			$cust_received = 0;
			$cust_recoup = 0;
			$vessel_pallets = 0;
			$vessel_damaged = 0;
			$vessel_received = 0;
			$vessel_recoup = 0;
			$total_pallets = 0;
			$total_damaged = 0;
			$total_received = 0;
			$total_recoup = 0;
			$total_vessels = 1;

			// now, into the loop.
			do {
				// first, figure out if this is a new customer or vessel.
				if($current_cust != $row['CUST_NUM'] || $current_vessel != $row['THE_VESSEL']){
					// end of section.
					// tag the totals on the bottom
					array_push($output_array, array('barcode'=>'<b>Sub-Total for '.$current_cust.'</b>',
							'desc'=>' ',
							'daterec'=>'<b>'.$cust_pallets.' Plts</b>',
							'origrec'=>'<b>'.$cust_received.'</b>',
							'shipped'=>' ',
							'dmg'=>'<b>'.$cust_damaged.'</b>', 
							'recoup'=>'<b>'.$cust_recoup.'</b>'));

					$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>10, 'width'=>680,'cols'=>$arrCol));
					$pdf->ezSetDy(-10);

					$current_cust = $row['CUST_NUM'];
					$cust_pallets = 0;
					$cust_damaged = 0;
					$cust_received = 0;
					$cust_recoup = 0;

					// reset data after printing
					$output_array = array();

					if($current_vessel != $row['THE_VESSEL']){
						// vessel is different.  do this in addition to end of customer block.
						$pdf->ezText("<b>Total for ".$current_vessel.":</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Pallets: ".$vessel_pallets."</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Received: ".$vessel_received."</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Damages: ".$vessel_damaged."</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Recouped: ".$vessel_recoup."</b>", 10, $left);
						$pdf->ezNewPage();


						$pdf->ezText("<b>".$row['THE_VESSEL']."</b>", 14, $center);
						$pdf->ezSetDy(-10);

						$current_vessel = $row['THE_VESSEL'];
						$vessel_pallets = 0;
						$vessel_damaged = 0;
						$vessel_received = 0;
						$vessel_recoup = 0;
						$total_vessels++;
					}

					$pdf->ezText("<b>".$row['CUSTOMER_NAME']."</b>", 12, $left);
					$pdf->ezSetDy(-10);

				}

				// do this regardless, as long as we have rows still.
				$cust_pallets++;
				$cust_damaged += $row['THE_DMG'];
				$cust_received += $row['QTY_RECEIVED'];
				$cust_recoup += $row['THE_RECOUP'];
				$vessel_pallets++;
				$vessel_damaged += $row['THE_DMG'];
				$vessel_received += $row['QTY_RECEIVED'];
				$vessel_recoup += $row['THE_RECOUP'];
				$total_pallets++;
				$total_damaged += $row['THE_DMG'];
				$total_received += $row['QTY_RECEIVED'];
				$total_recoup += $row['THE_RECOUP'];
				array_push($output_array, array('barcode'=>$row['PALLET_ID'],
						'desc'=>$row['COMMODITY_NAME'],
						'daterec'=>$row['THE_DATE'],
						'origrec'=>$row['QTY_RECEIVED'],
						'shipped'=>$row['THE_SHIPPED'],
						'dmg'=>$row['THE_DMG'], 
						'recoup'=>$row['THE_RECOUP']));
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			
			// finish off the report.  same logic as vessel-end block from above,
			// just minus the variable-resetting-per-loop parts.
			array_push($output_array, array('barcode'=>'<b>Sub-Total for '.$row['CUST_NUM'].'</b>',
					'desc'=>' ',
					'daterec'=>'<b>'.$cust_pallets.' Plts</b>',
					'origrec'=>'<b>'.$cust_received.'</b>',
					'shipped'=>' ',
					'dmg'=>'<b>'.$cust_damaged.'</b>', 
					'recoup'=>'<b>'.$cust_recoup.'</b>'));

			$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>10, 'width'=>680,'cols'=>$arrCol));
			$pdf->ezSetDy(-10);

			$pdf->ezText("<b>Total for ".$current_vessel.":</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Pallets: ".$vessel_pallets."</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Received: ".$vessel_received."</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Damages: ".$vessel_damaged."</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Recouped: ".$vessel_recoup."</b>", 10, $left);
			$pdf->ezNewPage();

			$pdf->ezText("<b>Grand Totals, ".$start_date." through ".$end_date.":</b>", 16, $center);
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>     Vessels: ".$total_vessels."</b>", 12, $left);
			$pdf->ezText("<b>     Pallets: ".$total_pallets."</b>", 12, $left);
			$pdf->ezText("<b>     Received: ".$total_received."</b>", 12, $left);
			$pdf->ezText("<b>     Damages: ".$total_damaged."</b>", 12, $left);
			$pdf->ezText("<b>     Recouped: ".$total_recoup."</b>", 12, $left);


		}
	} else {
	   $block_heading = array('barcode'=>'<b>Barcode</b>',
				'desc'=>'<b>Customer</b>',
				'daterec'=>'<b>Date Received</b>',
				'origrec'=>'<b>Received</b>',
				'shipped'=>'<b>Shipped</b>',
				'dmg'=>'<b>Damaged</b>', 
				'recoup' => '<b>Recoup</b>');

		$output_array = array();

		$arrCol = array('barcode'=>array('justification'=>'left'),
				'desc'=>array('width'=>100, 'justification'=>'center'),
				'daterec'=>array('width'=>80, 'justification'=>'center'),
				'origrec'=>array('width'=>70, 'justification'=>'center'),
				'shipped'=>array('width'=>70, 'justification'=>'center'),
				'dmg'=>array('width'=>70, 'justification'=>'center'),
				'recoup'=>array('width'=>70,'justification'=>'right'));

	// top level SQL.
		$sql = "SELECT PALLET_ID, NVL(QTY_DAMAGED, 0) THE_DMG, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE, QTY_RECEIVED,
				NVL((SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL), 0) THE_SHIPPED,
				NVL((SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '9' AND ACTIVITY_DESCRIPTION IS NULL), 0) THE_RECOUP,
				RECEIVER_ID CUST_NUM,
				CUSTOMER_NAME,
				COMMODITY_NAME,
				VP.LR_NUM || '-' || VP.VESSEL_NAME THE_VESSEL
				FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
				WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.DATE_RECEIVED > TO_DATE('".$start_date."', 'MM/DD/YYYY')
				AND CT.DATE_RECEIVED < TO_DATE('".$end_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
				AND (CT.QTY_DAMAGED != 0
					OR
					(SELECT NVL(SUM(QTY_CHANGE), CT.QTY_RECEIVED) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL) != CT.QTY_RECEIVED
					OR
					(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND 
						CA.CUSTOMER_ID = CT.RECEIVER_ID AND SERVICE_CODE = '9' AND ACTIVITY_DESCRIPTION IS NULL) != 0
					)
				AND (CT.COMMODITY_CODE BETWEEN 1 AND 1600
					OR
					CT.COMMODITY_CODE BETWEEN 8000 AND 8999)
				ORDER BY THE_VESSEL, COMMODITY_NAME, CUSTOMER_NAME, PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// nothing to report
			$pdf->ezText("<b>No OS&D pallets for specified date range.</b>", 12, $center);
		} else {
			// ok, we have items.  Time to get PDF-alicious.
			// First header and starting info.
			$pdf->ezText("<b>".$row['THE_VESSEL']."</b>", 14, $center);
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>".$row['COMMODITY_NAME']."</b>", 12, $left);
			$pdf->ezSetDy(-10);

			$current_vessel = $row['THE_VESSEL'];
			$current_comm = $row['COMMODITY_NAME'];
			$comm_pallets = 0;
			$comm_damaged = 0;
			$comm_received = 0;
			$comm_recoup = 0;
			$vessel_pallets = 0;
			$vessel_damaged = 0;
			$vessel_received = 0;
			$vessel_recoup = 0;
			$total_pallets = 0;
			$total_damaged = 0;
			$total_received = 0;
			$total_recoup = 0;
			$total_vessels = 1;

			// now, into the loop.
			do {
				// first, figure out if this is a new customer or vessel.
				if($current_comm != $row['COMMODITY_NAME'] || $current_vessel != $row['THE_VESSEL']){
					// end of section.
					// tag the totals on the bottom
					array_push($output_array, array('barcode'=>'<b>Sub-Total for '.$current_comm.'</b>',
							'desc'=>' ',
							'daterec'=>'<b>'.$comm_pallets.' Plts</b>',
							'origrec'=>'<b>'.$comm_received.'</b>',
							'shipped'=>' ',
							'dmg'=>'<b>'.$comm_damaged.'</b>', 
							'recoup'=>'<b>'.$comm_recoup.'</b>'));

					$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>10, 'width'=>680,'cols'=>$arrCol));
					$pdf->ezSetDy(-10);

					$current_comm = $row['COMMODITY_NAME'];
					$comm_pallets = 0;
					$comm_damaged = 0;
					$comm_received = 0;
					$comm_recoup = 0;

					// reset data after printing
					$output_array = array();

					if($current_vessel != $row['THE_VESSEL']){
						// vessel is different.  do this in addition to end of customer block.
						$pdf->ezText("<b>Total for ".$current_vessel.":</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Pallets: ".$vessel_pallets."</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Received: ".$vessel_received."</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Damages: ".$vessel_damaged."</b>", 10, $left);
	//					$pdf->ezSetDy(-5);
						$pdf->ezText("<b>     Recouped: ".$vessel_recoup."</b>", 10, $left);
						$pdf->ezNewPage();


						$pdf->ezText("<b>".$row['THE_VESSEL']."</b>", 14, $center);
						$pdf->ezSetDy(-10);

						$current_vessel = $row['THE_VESSEL'];
						$vessel_pallets = 0;
						$vessel_damaged = 0;
						$vessel_received = 0;
						$vessel_recoup = 0;
						$total_vessels++;
					}

					$pdf->ezText("<b>".$row['COMMODITY_NAME']."</b>", 12, $left);
					$pdf->ezSetDy(-10);

				}

				// do this regardless, as long as we have rows still.
				$comm_pallets++;
				$comm_damaged += $row['THE_DMG'];
				$comm_received += $row['QTY_RECEIVED'];
				$comm_recoup += $row['THE_RECOUP'];
				$vessel_pallets++;
				$vessel_damaged += $row['THE_DMG'];
				$vessel_received += $row['QTY_RECEIVED'];
				$vessel_recoup += $row['THE_RECOUP'];
				$total_pallets++;
				$total_damaged += $row['THE_DMG'];
				$total_received += $row['QTY_RECEIVED'];
				$total_recoup += $row['THE_RECOUP'];
				array_push($output_array, array('barcode'=>$row['PALLET_ID'],
						'desc'=>$row['CUSTOMER_NAME'],
						'daterec'=>$row['THE_DATE'],
						'origrec'=>$row['QTY_RECEIVED'],
						'shipped'=>$row['THE_SHIPPED'],
						'dmg'=>$row['THE_DMG'], 
						'recoup'=>$row['THE_RECOUP']));
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			
			// finish off the report.  same logic as vessel-end block from above,
			// just minus the variable-resetting-per-loop parts.
			array_push($output_array, array('barcode'=>'<b>Sub-Total for '.$row['COMMODITY_NAME'].'</b>',
					'desc'=>' ',
					'daterec'=>'<b>'.$comm_pallets.' Plts</b>',
					'origrec'=>'<b>'.$comm_received.'</b>',
					'shipped'=>' ',
					'dmg'=>'<b>'.$comm_damaged.'</b>', 
					'recoup'=>'<b>'.$comm_recoup.'</b>'));

			$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>10, 'width'=>680,'cols'=>$arrCol));
			$pdf->ezSetDy(-10);

			$pdf->ezText("<b>Total for ".$current_vessel.":</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Pallets: ".$vessel_pallets."</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Received: ".$vessel_received."</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Damages: ".$vessel_damaged."</b>", 10, $left);
	//		$pdf->ezSetDy(-5);
			$pdf->ezText("<b>     Recouped: ".$vessel_recoup."</b>", 10, $left);
			$pdf->ezNewPage();

			$pdf->ezText("<b>Grand Totals, ".$start_date." through ".$end_date.":</b>", 16, $center);
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>     Vessels: ".$total_vessels."</b>", 12, $left);
			$pdf->ezText("<b>     Pallets: ".$total_pallets."</b>", 12, $left);
			$pdf->ezText("<b>     Received: ".$total_received."</b>", 12, $left);
			$pdf->ezText("<b>     Damages: ".$total_damaged."</b>", 12, $left);
			$pdf->ezText("<b>     Recouped: ".$total_recoup."</b>", 12, $left);


		}
	}

	// redirect to a temporary PDF file instead of directly writing to the browser
	include("redirect_pdf.php");
?>
