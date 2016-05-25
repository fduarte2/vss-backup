<?
/*
*	Adam Walter, Feb 2009
*
*	This page is a report for in-house rolls of Dole
*	Paper, and current orders against them.
*
*	Displays As-of the time report is generated.
********************************************************/

	include 'class.ezpdf.php';
	include("useful_info.php");
	$short_term_data_cursor = ora_open($conn);
	$dockticket_cursor = ora_open($conn);


	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(20,20,65,65);
	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->ezSetDy(-20);
	$pdf->ezText("<b>Dole Paper Availability Report</b>", 16, $center);
	$pdf->ezText("<b>".date('m/d/Y h:i')."</b>", 14, $center);
	$pdf->ezSetDy(-5);

	$sql = "SELECT RECEIVER_ID, BOL, CARGO_DESCRIPTION, BATCH_ID, SUM(QTY_RECEIVED) THE_ORIG, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_REC, SUM(QTY_IN_HOUSE) IN_HOUSE
			FROM CARGO_TRACKING
			WHERE DATE_RECEIVED IS NOT NULL
			AND REMARK = 'DOLEPAPERSYSTEM'
			GROUP BY RECEIVER_ID, BOL, CARGO_DESCRIPTION, BATCH_ID
			HAVING SUM(QTY_IN_HOUSE) > 0
			ORDER BY RECEIVER_ID, BOL";
	ora_parse($dockticket_cursor, $sql);
	ora_exec($dockticket_cursor);
	if(!ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<b>No Shippable In-House Rolls to display</b>", 16, $center);
	} else {
		$block_heading = array('colA'=>'<b>Customer</b>',
								'colB'=>'<b>Dock Ticket</b>',
								'colC'=>'<b>Description</b>',
								'colD'=>'<b>Code</b>',
								'colE'=>'<b>Rolls Received</b>',
								'colF'=>'<b>Date Received</b>',
								'colG'=>'<b>Rolls Shipped</b>',
								'colH'=>'<b>Rolls In House</b>',
								'colI'=>'<b>Rolls In House (Reject)</b>',
								'colJ'=>'<b>Pending Orders</b>',
								'colK'=>'<b>Weight In House</b>');

		$output_array = array();

		do { // do this for each dock ticket
			$sql = "SELECT SUM(QTY_SHIP) TO_SHIP FROM DOLEPAPER_DOCKTICKET DD, DOLEPAPER_ORDER DPO WHERE DD.ORDER_NUM = DPO.ORDER_NUM AND DD.DOCK_TICKET = '".$dockticket_row['BOL']."' AND DPO.STATUS NOT IN ('6', '7', '8')";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$pending = $short_term_row['TO_SHIP'];

			$sql = "SELECT SUM(QTY_DAMAGED) THE_DMG, SUM(WEIGHT) THE_WEIGHT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE > 0 AND BOL = '".$dockticket_row['BOL']."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$DMG_Inhouse = $short_term_row['THE_DMG'];
			$WT_Inhouse = $short_term_row['THE_WEIGHT'];

/*			$sql = "SELECT SUM(WEIGHT) THE_WEIGHT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE > 0 AND BOL = '".$dockticket_row['BOL']."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
*/
//			echo $dockticket_row['THE_ORIG']." ".$dockticket_row['IN_HOUSE']." ".($dockticket_row['THE_ORIG'] - $dockticket_row['IN_HOUSE'])."\n";
//			if($dockticket_row['IN_HOUSE'] != 0){
				array_push($output_array, array('colA'=>$dockticket_row['RECEIVER_ID'],
												'colB'=>$dockticket_row['BOL'],
												'colC'=>$dockticket_row['CARGO_DESCRIPTION'],
												'colD'=>$dockticket_row['BATCH_ID'],
												'colE'=>$dockticket_row['THE_ORIG'],
												'colF'=>$dockticket_row['THE_REC'],
												'colG'=>($dockticket_row['THE_ORIG'] - $dockticket_row['IN_HOUSE']),
												'colH'=>$dockticket_row['IN_HOUSE'],
												'colI'=>(0 + $DMG_Inhouse),
												'colJ'=>(0 + $pending),
												'colK'=>(0 + $WT_Inhouse)));
//			}
		} while(ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$pdf->ezText("<b>Inventory for Dockticket Paper</b>", 12, $center);
		$pdf->ezSetDy(-5);
		$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>1, 'fontSize'=>10, 'width'=>680, 'cols'=>array('colC'=>array('width'=>80))));

		$pdf->ezSetDy(-10);
	}
/*
	$sql = "SELECT DOCK_TICKET, SUM(QTY_IN_HOUSE) THE_SUM FROM CARGO_TRACKING CT, DOLEPAPER_DAMAGES DD
			WHERE CT.BOL = DD.DOCK_TICKET
			AND CT.QTY_IN_HOUSE > 0
			AND CT.PALLET_ID = DD.ROLL
			AND CT.RECEIVER_ID = DD.CUSTOMER_ID
			AND DD.DATE_CLEARED IS NULL
			AND DD.DMG_TYPE LIKE 'R%'
			GROUP BY DOCK_TICKET
			ORDER BY DOCK_TICKET";
	ora_parse($dockticket_cursor, $sql);
	ora_exec($dockticket_cursor);
	if(!ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<b>No Rejected In-House Rolls</b>", 16, $center);
	} else {
		$block_heading = array('colA'=>'<b>Dock Ticket</b>',
								'colB'=>'<b>Rejected Rolls</b>');
		$output_array = array();

		do { // do this for each dock ticket
			array_push($output_array, array('colA'=>$dockticket_row['DOCK_TICKET'],
											'colB'=>$dockticket_row['THE_SUM']));

		} while(ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$pdf->ezText("<b>In-House Rejected Rolls</b>", 12, $center);
		$pdf->ezSetDy(-5);
		$pdf->ezTable($output_array, $block_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>1, 'fontSize'=>10, 'width'=>200));

		$pdf->ezSetDy(-10);
	} */
	include("redirect_pdf.php");
?>