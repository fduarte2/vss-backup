<?
	include 'class.ezpdf.php';

	$order = "DC401";
	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(20,20,65,65);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_END,
				CA.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCK') THE_VES, CUSTOMER_NAME
			FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CUSTOMER_PROFILE CP
			WHERE CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
				AND CA.CUSTOMER_ID = CP.CUSTOMER_ID
			GROUP BY CA.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCK'), CUSTOMER_NAME";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist for customer ".$cust."</b>", 14, $center);
	} else {
		$start = ociresult($stid, "THE_START");
		$end = ociresult($stid, "THE_END");
		$lr = ociresult($stid, "ARRIVAL_NUM");
		$vesname = ociresult($stid, "THE_VES");
		$cust = ociresult($stid, "CUSTOMER_NAME");

		$pdf->ezSetY(565);
		$pdf->ezText("PORT OF WILMINGTON TALLY", 14, $left);
		$pdf->ezSetY(545);
		$pdf->ezText("Order:  ".$order, 14, $left);
		$pdf->ezSetY(525);
		$pdf->ezText("Start Time:  ".$start, 12, $left);
		$pdf->ezSetY(525);
		$pdf->ezText("Customer:  ".$cust, 12, $right);
		$pdf->ezSetY(510);
		$pdf->ezText("End Time: ".$end, 12, $left);
		$pdf->ezSetY(510);
		$pdf->ezText("Vessel: ".$lr." - ".$vesname, 12, $right);
		$pdf->ezSetY(490);

		$block_heading = array('colA'=>'<b>Barcode</b>',
								'colB'=>'<b>Commodity</b>',
								'colC'=>'<b>PKG Hse</b>',
								'colD'=>'<b>Weight</b>',
								'colE'=>'<b>Size</b>',
								'colF'=>'<b>Orig. QTY</b>',
								'colG'=>'<b>Actual QTY</b>',
								'colH'=>'<b>RGR/ HSP</b>',
								'colI'=>'<b>Dmg</b>',
								'colJ'=>'<b>Container</b>',
								'colK'=>'<b>Checker</b>');

		$pallet_list = array();
		$pallet_cols = array('colA'=>array('width'=>150, 'justification'=>'left'),
							'colB'=>array('width'=>80, 'justification'=>'left'),
							'colC'=>array('width'=>40, 'justification'=>'left'),
							'colD'=>array('width'=>40, 'justification'=>'left'),
							'colE'=>array('width'=>40, 'justification'=>'left'),
							'colF'=>array('width'=>40, 'justification'=>'left'),
							'colG'=>array('width'=>40, 'justification'=>'left'),
							'colH'=>array('width'=>40, 'justification'=>'left'),
							'colI'=>array('width'=>40, 'justification'=>'left'),
							'colJ'=>array('width'=>80, 'justification'=>'left'),
							'colK'=>array('width'=>90, 'justification'=>'left'));
		$total_plt = 0;
		$total_cs = 0;

		$sql = "SELECT CA.PALLET_ID, MCOM.DC_COMMODITY_NAME, ACTIVITY_ID, CT.EXPORTER_CODE THE_PKG, CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, CT.CARGO_SIZE THE_SIZE,
					CT.BATCH_ID THE_ORIG, CA.QTY_CHANGE THE_CHANGE, CT.CARGO_STATUS THE_STATUS,
					NVL(CA.BATCH_ID, '0') THE_DMG, CT.CONTAINER_ID THE_CONTAINER
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, MOR_COMMODITY MCOM
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.COMMODITY_CODE = MCOM.PORT_COMMODITY_CODE
					AND CA.SERVICE_CODE = '6'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.ORDER_NUM = '".$order."'";
		$pallets = ociparse($conn, $sql);
		ociexecute($pallets);
		while(ocifetch($pallets)){
			array_push($pallet_list, array('colA'=>ociresult($pallets, "PALLET_ID"),
								'colB'=>ociresult($pallets, "DC_COMMODITY_NAME"),
								'colC'=>ociresult($pallets, "THE_PKG"),
								'colD'=>ociresult($pallets, "THE_WEIGHT"),
								'colE'=>ociresult($pallets, "THE_SIZE"),
								'colF'=>ociresult($pallets, "THE_ORIG"),
								'colG'=>ociresult($pallets, "THE_CHANGE"),
								'colH'=>ociresult($pallets, "THE_STATUS"),
								'colI'=>ociresult($pallets, "THE_DMG"),
								'colJ'=>ociresult($pallets, "THE_CONTAINER"),
								'colK'=>get_checker_name(ociresult($pallets, "ACTIVITY_ID"), $conn)));
			$total_plt += 1;
			$total_cs += ociresult($pallets, "THE_CHANGE");
		}

		$pdf->ezTable($pallet_list, $block_heading, '', array('showHeadings'=>1, 
																'shaded'=>0, 
																'showLines'=>1, 
																'fontSize'=>8, 
																'width'=>680, 
																'protectRows'=>5,
																'cols'=>$pallet_cols));

		$total_table = array();
		$total_cols = array('blankcol'=>array('width'=>350, 'justification'=>'left'),
							'totalwords'=>array('width'=>150, 'justification'=>'left'),
							'total'=>array('width'=>180, 'justification'=>'right'));
		array_push($total_table, array('blankcol'=>"",
										'totalwords'=>"Total Cases:",
										'total'=>$total_cs));
		array_push($total_table, array('blankcol'=>"",
										'totalwords'=>"Total Pallets:",
										'total'=>$total_plt));
		$pdf->ezTable($total_table, '', '', array('showHeadings'=>0, 
														'shaded'=>0, 
														'showLines'=>0, 
														'fontSize'=>10, 
														'width'=>680, 
														'protectRows'=>5,
														'cols'=>$total_cols));

		include("redirect_pdf.php");
	}








function get_checker_name($ID, $conn){


	while(strlen($ID) < 5){
		$ID = "0".$ID;
	}

	$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -".strlen($ID).") = '".$ID."'";
	$chkr = ociparse($conn, $sql);
	ociexecute($chkr);
	if(!ocifetch($chkr)){
		return "unknown";
	} else {
		return ociresult($chkr, "THE_EMP");
	}
}

?>
