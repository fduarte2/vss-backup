<?
/*
*	Adam Walter, Feb 2013.
*
*	RF report for in-house cargo.
*	1 entry variable:  Vessel.
************************************************************/

  // All POW files need this session file included
  include("pow_session.php");


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];

	$header_info = array();
	$header_info['vessel_num'] = $vessel;

	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter');
	$pdf->ezSetMargins(10,10,10,10);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->ezStartPageNumbers(310, 60, 10);

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(ocifetch($stid)){
		$header_info['vessel_name'] = ociresult($stid, "VESSEL_NAME");
	} else {
		$header_info['vessel_name'] = "Unknown Vessel #";
	}

	$pallet_heading = array('cust'=>'<b>CUSTOMER</b>',
								'BC'=>'<b>PALLET ID</b>',
								'comm'=>'<b>COMMODITY</b>',
								'rec'=>'<b>QTY RCVD</b>',
								'ih'=>'<b>QTY IN HOUSE</b>');
	$cust_total_rec = 0;
	$comm_total_rec = 0;
	$cust_total_ih = 0;
	$comm_total_ih = 0;
	$GT_total_rec = 0;
	$GT_total_ih = 0;
	$table_counter = 0;

	print_header($pdf, $header_info, $rfconn);

	$pallets_heading = array('cust'=>'<b>CUSTOMER</b>',
						'BC'=>'<b>PALLET ID</b>',
						'comm'=>'<b>COMMODITY</b>',
						'rec'=>'<b>QTY RCVD</b>',
						'ih'=>'<b>QTY IN HOUSE</b>');

	
	
	$pallets = array();
	// get data
	$sql = "SELECT CUSTOMER_NAME, RECEIVER_ID, PALLET_ID, COMMODITY_NAME, QTY_RECEIVED, QTY_IN_HOUSE
			FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP
			WHERE CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.DATE_RECEIVED IS NOT NULL
				AND CT.QTY_IN_HOUSE > 0
				AND CT.RECEIVER_ID != '453'
				AND CT.ARRIVAL_NUM = '".$vessel."'
			ORDER BY RECEIVER_ID, COMMODITY_NAME";
	$IH_loop = ociparse($rfconn, $sql);
	ociexecute($IH_loop);
	if(!ocifetch($IH_loop)){
		$pdf->ezSetY(500);
		$pdf->ezText("No In-House pallets showing as available.", 12, $center);
	} else { 
		$pallets = array();
		$cur_cust = ociresult($IH_loop, "CUSTOMER_NAME");
		$cur_comm = ociresult($IH_loop, "COMMODITY_NAME");
		do {
			if($cur_cust != ociresult($IH_loop, "CUSTOMER_NAME")){
				array_push($pallets, array('cust'=>"",
											'BC'=>"",
											'comm'=>"<i>Commodity Sub Total</i>",
											'rec'=>"<b>".$comm_total_rec."</b>",
											'ih'=>"<b>".$comm_total_ih."</b>"));
				$comm_total_rec = 0;
				$comm_total_ih = 0;
				$table_counter++;
				$cur_comm = ociresult($IH_loop, "COMMODITY_NAME");

				array_push($pallets, array('cust'=>"",
											'BC'=>"",
											'comm'=>"<i>Customer Sub Total</i>",
											'rec'=>"<b>".$cust_total_rec."</b>",
											'ih'=>"<b>".$cust_total_ih."</b>"));
				$cust_total_rec = 0;
				$cust_total_ih = 0;
				$table_counter++;
				$cur_cust = ociresult($IH_loop, "CUSTOMER_NAME");
			} elseif($cur_comm != ociresult($IH_loop, "COMMODITY_NAME")){
				array_push($pallets, array('cust'=>"",
											'BC'=>"",
											'comm'=>"<i>Commodity Sub Total</i>",
											'rec'=>"<b>".$comm_total_rec."</b>",
											'ih'=>"<b>".$comm_total_ih."</b>"));
				$comm_total_rec = 0;
				$comm_total_ih = 0;
				$table_counter++;
				$cur_comm = ociresult($IH_loop, "COMMODITY_NAME");
			}
			if($table_counter >= 40){
				$pdf->ezSetY(700);
				$pdf->ezTable($pallets, $pallets_heading, '', array('showHeadings'=>1, 
																	'shaded'=>0, 
																	'showLines'=>0,
																	'fontSize'=>8,
																	'width'=>550,
																	'colGap'=>2));
				$pallets = array();

				$pdf->ezNewPage();
				print_header($pdf, $header_info, $rfconn);
				$table_counter = 0;
			}

			array_push($pallets, array('cust'=>ociresult($IH_loop, "CUSTOMER_NAME"),
										'BC'=>ociresult($IH_loop, "PALLET_ID"),
										'comm'=>ociresult($IH_loop, "COMMODITY_NAME"),
										'rec'=>ociresult($IH_loop, "QTY_RECEIVED"),
										'ih'=>ociresult($IH_loop, "QTY_IN_HOUSE")));
			$table_counter++;
			$cust_total_rec += ociresult($IH_loop, "QTY_RECEIVED");
			$comm_total_rec += ociresult($IH_loop, "QTY_RECEIVED");
			$cust_total_ih += ociresult($IH_loop, "QTY_IN_HOUSE");
			$comm_total_ih += ociresult($IH_loop, "QTY_IN_HOUSE");
			$GT_total_rec += ociresult($IH_loop, "QTY_RECEIVED");
			$GT_total_ih += ociresult($IH_loop, "QTY_IN_HOUSE");

		} while(ocifetch($IH_loop));

		array_push($pallets, array('cust'=>"",
									'BC'=>"",
									'comm'=>"<i>Commodity Sub Total</i>",
									'rec'=>"<b>".$comm_total_rec."</b>",
									'ih'=>"<b>".$comm_total_ih."</b>"));
		array_push($pallets, array('cust'=>"",
									'BC'=>"",
									'comm'=>"<i>Customer Sub Total</i>",
									'rec'=>"<b>".$cust_total_rec."</b>",
									'ih'=>"<b>".$cust_total_ih."</b>"));
		array_push($pallets, array('cust'=>"<b>TOTAL:</b>",
									'BC'=>"",
									'comm'=>"",
									'rec'=>"<b>".$GT_total_rec."</b>",
									'ih'=>"<b>".$GT_total_ih."</b>"));
		$pdf->ezSetY(700);
		$pdf->ezTable($pallets, $pallets_heading, '', array('showHeadings'=>1, 
															'shaded'=>0, 
															'showLines'=>0,
															'fontSize'=>8,
															'width'=>550,
															'colGap'=>2));

	}
	
	include("redirect_pdf.php");











function print_header(&$pdf, $header_info, $rfconn){
/*	$header_info['vessel_num']
	$header_info['vessel_name']
*/

	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

	$pdf->ezSetY(765);
	$pdf->ezText("<b>CARGO IN HOUSE REPORT</b>", 14, $center);

	$pdf->ezSetY(730);
	$pdf->ezText($header_info['vessel_num']."  -  ".$header_info['vessel_name'], 10, $center);
	$pdf->ezSetY(715);
	$pdf->ezText("Run On: ".date('m/d/Y h:i:s a'), 10, $right);
/*	$pdf->ezSetY(700);
	$pdf->ezText("Commodity: ".$header_info['commname'], 10, $left);
	$pdf->ezSetY(685);
	$pdf->ezText("Customer: ".$header_info['custname'], 10, $left);

	$pdf->ezSetY(730);
	$pdf->ezText("Port Order#: ", 12, $right);
	$pdf->ezSetY(715);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
	$pdf->ezText("*".$header_info['PORT_num']."*", 48, $right);
	$pdf->ezSetY(685);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$pdf->ezText($header_info['PORT_num'], 12, $right);

	$pdf->ezSetY(650);
	$pdf->ezText("Load Date: ".date('m/d/Y'), 10, $left);
	$pdf->ezSetY(635);
	$pdf->ezText("PoW Clerk: ".$header_info['clerk'], 10, $left);

	$pdf->ezSetY(605);
	$pdf->ezText("Driver Name: ".$header_info['driver'], 10, $left);
	$pdf->ezSetY(590);
	$pdf->ezText("Trucking Company: ".$header_info['trucker'], 10, $left);
	$pdf->ezSetY(575);
	$pdf->ezText("Main Carrier: ".$header_info['carrier'], 10, $left);
	$pdf->ezSetY(560);
	$pdf->ezText("Ship To: ".$header_info['ship_to'], 10, $left);
	$pdf->ezSetY(545);
	$pdf->ezText("               ".$header_info['add1'], 10, $left);
	$pdf->ezSetY(530);
	$pdf->ezText("               ".$header_info['add2'], 10, $left);
	$pdf->ezSetY(515);
	$pdf->ezText("               ".$header_info['city'].", ".$header_info['state']."  ".$header_info['zip'], 10, $left);

	$pdf->ezSetY(605);
	$pdf->ezText("License Plate#: ".$header_info['lic_num'], 10, $right);
	$pdf->ezSetY(590);
	$pdf->ezText("State: ".$header_info['lic_state'], 10, $right);
	$pdf->ezSetY(530);
	$pdf->ezText("<b>REMARK:</b>", 10, $right);
	$pdf->ezSetY(515);
	$pdf->ezText("<b>".$header_info['remark']."</b>", 10, $right);*/
}
