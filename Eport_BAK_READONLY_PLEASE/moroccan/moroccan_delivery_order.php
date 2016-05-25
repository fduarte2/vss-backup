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

//	$pdf = new Cezpdf('letter','landscape');
	$pdf = new Cezpdf('letter');
	$pdf->ezSetMargins(20,20,65,65);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT CUSTOMER_NAME, VESSELID, NVL(VESSEL_NAME, 'UNKNOWN') THE_VES, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') DEL_DATE, LOADTYPE, COMMODITYCODE
			FROM CUSTOMER_PROFILE CP, VESSEL_PROFILE VP, MOR_ORDER MO
			WHERE MO.VESSELID = VP.LR_NUM(+)
				AND MO.CUST = CP.CUSTOMER_ID
				AND MO.ORDERNUM = '".$order."'
				AND MO.CUST = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist for customer ".$cust."</b>", 14, $center);
	} else {

		$vesname = ociresult($stid, "THE_VES");
		$custname = ociresult($stid, "CUSTOMER_NAME");
		$lr = ociresult($stid, "VESSELID");
		$delivery = ociresult($stid, "DEL_DATE");
		$load = ociresult($stid, "LOADTYPE");
		$comm = ociresult($stid, "COMMODITYCODE");

		$pdf->ezSetY(765);
		$pdf->ezText("<b>DSPC Terminal Delivery Order  ---  ".$load."</b>", 14, $center);
		$pdf->ezSetY(745);
		$pdf->ezText("<b>Clementines  ".$comm."</b>", 14, $center);
		$pdf->ezSetY(725);
		$pdf->ezText("<b>Importer  ".$custname."</b>", 12, $left);
		$pdf->ezSetY(725);
		$pdf->ezText("<b>Order  ".$order."</b>", 12, $right);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezSetY(705);
		$pdf->ezText("*".$cust."*", 48, $left);
		$pdf->ezSetY(705);
		$pdf->ezText("*".$order."*", 48, $right);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$pdf->ezSetY(665);
		$pdf->ezText("<b>Vessel  ".$lr." - ".$vesname."</b>", 12, $left);
		$pdf->ezSetY(665);
		$pdf->ezText("<b>Date  ".$delivery."</b>", 12, $right);
		$pdf->ezSetY(645);
		$pdf->ezText("<b>Truck:  _______________________</b>", 12, $left);
		$pdf->ezSetY(645);
		$pdf->ezText("<b>Checker:  ________________________</b>", 12, $right);

		$sql = "SELECT ORDERQTY, ORDERDETAILID, DESCR
				FROM MOR_ORDERDETAIL MO, MOR_COMMODITYSIZE MC
				WHERE ORDERNUM = '".$order."'
					AND MO.CUST = '".$cust."'
					AND MO.ORDERSIZEID = MC.SIZEID
				ORDER BY ORDERDETAILID";
		$detail_loop = ociparse($conn, $sql);
		ociexecute($detail_loop);
		if(!ocifetch($detail_loop)){
			$pdf->ezText("<b>No order details found</b>", 12, $right);
		} else {
			$count = 0;
			$main_table = array();

			array_push($main_table, array('detline'=>"",
										'reqact'=>"",
										'plt'=>"#PLTS",
										'cases'=>"#CS",
										'pkhse'=>"Station/Pack Hse",
										'size'=>"Size / KG",
										'loc'=>"Location",
										'dcdspc'=>"DC/DSPC"));
			do {
				$count++;
				array_push($main_table, array('detline'=>$count,
											'reqact'=>"req",
											'plt'=>"",
											'cases'=>ociresult($detail_loop, "ORDERQTY"),
											'pkhse'=>"",
											'size'=>ociresult($detail_loop, "DESCR"),
											'loc'=>"",
											'dcdspc'=>"DC"));

				$sql = "SELECT PACKINGHOUSE, PALLETQTY
						FROM MOR_PICKLIST
						WHERE ORDERNUM = '".$order."'
							AND CUST = '".$cust."'
							AND ORDERDETAILID = '".ociresult($detail_loop, "ORDERDETAILID")."'";
				$picklist_loop = ociparse($conn, $sql);
				ociexecute($picklist_loop);
				if(!ocifetch($picklist_loop)){
					array_push($main_table, array('detline'=>"",
												'reqact'=>"act",
												'plt'=>"<b>NO</b>",
												'cases'=>"<b>PICKLIST</b>",
												'pkhse'=>"<b>ENTRIES</b>",
												'size'=>"<b>FOUND</b>",
												'loc'=>"",
												'dcdspc'=>""));
				} else {
					do {
						array_push($main_table, array('detline'=>"",
													'reqact'=>"act",
													'plt'=>ociresult($detail_loop, "PALLETQTY"),
													'cases'=>"",
													'pkhse'=>ociresult($detail_loop, "PACKINGHOUSE"),
													'size'=>"",
													'loc'=>"",
													'dcdspc'=>"DSPC"));
					} while(ocifetch($picklist_loop));
				}
			} while(ocifetch($detail_loop));
		
			$pdf->ezSetY(615);
			$pdf->ezTable($main_table, '', '', array('showHeadings'=>0, 
														'shaded'=>0, 
														'showLines'=>2,
														'fontSize'=>8,
														'width'=>550,
														'fontSize'=>12,
														'colGap'=>2));
		}

		$pdf->ezSetY(140);
		$pdf->ezText("<b>Trailer was cooled to: ______________Degrees F</b>", 10, $left);
		$pdf->ezSetY(140);
		$pdf->ezText("<b>Driver Initials: ______________</b>", 10, $right);
		$pdf->ezSetY(120);
		$pdf->ezText("<b>Cleanliness:  Good:________   Fair:_______  Poor:______         Odor:  Y   N</b>", 10, $left);
		$pdf->ezSetY(100);
		$pdf->ezText("<b>Accepted For Loading:______Yes (Proceed to Load)  ______No (Contact Supervisor)</b>", 10, $left);
		$pdf->ezSetY(80);
		$pdf->ezText("<b>Checker:________________________</b>", 10, $left);
		$pdf->ezSetY(80);
		$pdf->ezText("<b>Driver:__________________________</b>", 10, $right);

		include("redirect_pdf.php");
	}
