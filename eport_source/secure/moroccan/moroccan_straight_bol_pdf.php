<?
	include 'class.ezpdf.php';

//	$order = "FFS1007";
	$order = $HTTP_GET_VARS['order'];
//	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];
	$cust = $HTTP_COOKIE_VARS['eport_mor_customer_id'];


	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	include("db_def.php");


//	$pdf = new Cezpdf('letter','landscape');
	$pdf = new Cezpdf('letter');
	$pdf->ezSetMargins(20,20,65,65);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT CARRIERNAME, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') DELV_DATE, TRAILERNUM, TRUCKTAG, SNMGNUM, CUSTOMERPO, MO.CONSIGNEEID, SEALNUM,
				CONSIGNEENAME, MCON.ADDRESS CONADD, MCON.CITY, MCON.STATEPROVINCE CONPROV, MCON.PHONE, MB.BORDERCROSSING, MB.CUSTOMSBROKER, MB.CONTACTNAME, MO.ZUSER2,
				MB.PHONE, MB.FAX, MCOM.DC_COMMODITY_NAME, MCUS.CUSTOMERNAME, MCUS.ADDRESS CUSADD, MCUS.STATEPROVINCE CUSPROV, MCUS.POSTALCODE
			FROM MOR_ORDER MO, MOR_TRANSPORTER MT, MOR_CONSIGNEE MCON, MOR_BROKER MB, MOR_COMMODITY MCOM, MOR_CUSTOMER MCUS
			WHERE MO.ORDERNUM = '".$order."'
				AND MO.TRANSPORTERID = MT.TRANSPORTID
				AND MO.CONSIGNEEID = MCON.CONSIGNEEID
				AND MCON.CUSTOMSBROKEROFFICEID = MB.BROKERID
				AND MO.COMMODITYCODE = MCOM.PORT_COMMODITY_CODE
				AND MO.CUSTOMERID = MCUS.CUSTOMERID
				AND MO.CUST = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist for customer ".$cust."</b>", 14, $center);
	} else {

		$carriername = ociresult($stid, "CARRIERNAME");
		$delivery = ociresult($stid, "DELV_DATE");
		$cust_po = ociresult($stid, "CUSTOMERPO");
		$snmg = ociresult($stid, "SNMGNUM");
		$seal = ociresult($stid, "SEALNUM");
		$trailer = ociresult($stid, "TRAILERNUM");
		$trucknum = ociresult($stid, "TRUCKTAG");
		$consname = ociresult($stid, "CONSIGNEENAME");
		$consaddr = ociresult($stid, "CONADD");
		$conscity = ociresult($stid, "CITY");
		$consstate = ociresult($stid, "CONPROV");
		$consphone = ociresult($stid, "PHONE");
		$bordercross = ociresult($stid, "BORDERCROSSING");
		$broker = ociresult($stid, "CUSTOMSBROKER");
		$brokerphone = ociresult($stid, "PHONE");
		$brokerfax = ociresult($stid, "FAX");
		$brokercontact = ociresult($stid, "CONTACTNAME");
		$cusname = ociresult($stid, "CUSTOMERNAME");
		$cusaddr = ociresult($stid, "CUSADD");
		$cusstate = ociresult($stid, "CUSPROV");
		$cuspost = ociresult($stid, "POSTALCODE");
		$commname = ociresult($stid, "DC_COMMODITY_NAME");
		$temp_record = ociresult($stid, "ZUSER2");

		$sql = "SELECT COUNT(*) THE_PLT, SUM(QTY_CHANGE) THE_CTN, SUM(WEIGHT * QTY_CHANGE) THE_WEIGHT
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
				WHERE ORDER_NUM = '".$order."'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM";
		$c_a = ociparse($conn, $sql);
		ociexecute($c_a);
		ocifetch($c_a);
		$plts = ociresult($c_a, "THE_PLT");
		$ctns = ociresult($c_a, "THE_CTN");
		$wt = ociresult($c_a, "THE_WEIGHT");

		$pdf->ezSetY(765);
		$pdf->ezText("<b>Motor Carrier</b>", 12, $center);
		$pdf->ezSetY(745);
		$pdf->ezText("<b>Straight Bill of Lading</b>", 12, $center);
		$pdf->ezSetY(730);
		$pdf->ezText("Original NON-Negotiable", 10, $center);

		$pdf->ezSetY(715);
		$pdf->ezText("License", 10, array('aright'=>250,'justification'=>'center'));

		$truck_table = array();
		array_push($truck_table, array('desc'=>"Truck (State)",
									'value'=>""));
		array_push($truck_table, array('desc'=>"Truck (Tag#)",
									'value'=>$trucknum));
		array_push($truck_table, array('desc'=>"Trailer",
									'value'=>$trailer));
		$pdf->ezSetY(705);
		$pdf->ezTable($truck_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>200,
													'fontSize'=>10,
													'xPos'=>'left',
													'xOrientation'=>'right',
													'colGap'=>2));

		$topright_table = array();
		array_push($topright_table, array('desc'=>"Shipper's #",
									'value'=>$order));
		array_push($topright_table, array('desc'=>"SNMG#",
									'value'=>$snmg));
		array_push($topright_table, array('desc'=>"Customer PO",
									'value'=>$cust_po));
		array_push($topright_table, array('desc'=>"Carrier #",
									'value'=>""));
		$pdf->ezSetY(705);
		$pdf->ezTable($topright_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>200,
													'fontSize'=>8,
													'xPos'=>'right',
													'xOrientation'=>'left',
													'colGap'=>2));

		$carrier_table = array();
		array_push($carrier_table, array('namedesc'=>"<b>NAME OF CARRIER</b>",
									'namevalue'=>$carriername,
									'datedesc'=>"<b>DELIVERY DATE</b>",
									'datevalue'=>$delivery));
		$pdf->ezSetY(650);
		$pdf->ezTable($carrier_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>480,
													'fontSize'=>10,
													'colGap'=>2));

		$pdf->ezSetY(625);
		$pdf->ezText("<b>Carrier has received fromt he shipper named herein, the perishable property described below, in good order and condition, except as</b>", 7, $left);
		$pdf->ezText("<b>noted below, marked, consigned and destined as indicated below, pursant to an agreement (arranged by the truck broker, named</b>", 7, $left);
		$pdf->ezText("<b>herein, if any), whereby the motor carrier shown above, in consideration, of the transportation charges to be paid, agrees to carry and</b>", 7, $left);
		$pdf->ezText("<b>deliver said property to the consignee, subject only to the terms and conditions agreed to by the motor carrier, the shipper and truck</b>", 7, $left);
		$pdf->ezText("<b>broker if any.</b>", 7, $left);

		$consign_table = array();
		array_push($consign_table, array('CONSIGNED TO'=>$consname));
		array_push($consign_table, array('CONSIGNED TO'=>$consaddr));
		array_push($consign_table, array('CONSIGNED TO'=>$consstate));
		array_push($consign_table, array('CONSIGNED TO'=>$consphone));

		$pdf->ezSetY(580);
		$pdf->ezTable($consign_table, '', "<b>CONSIGNED TO</b>", array('showHeadings'=>1, 
																	'shaded'=>0, 
																	'showLines'=>2,
																	'fontSize'=>8,
																	'width'=>150,
																	'fontSize'=>8,
																	'titleFontSize'=>10,
																	'xPos'=>'left',
																	'xOrientation'=>'right',
																	'colGap'=>2));

		$shipfrom_table = array();
		array_push($shipfrom_table, array('SHIPPED FROM'=>"BAR IMEX INTL"));
		array_push($shipfrom_table, array('SHIPPED FROM'=>"C/O Port of Wilmington"));
		array_push($shipfrom_table, array('SHIPPED FROM'=>"Wilmington, DE, U.S.A"));

		$pdf->ezSetY(580);
		$pdf->ezTable($shipfrom_table, '', "<b>SHIPPED FROM</b>", array('showHeadings'=>1, 
																		'shaded'=>0, 
																		'showLines'=>2,
																		'fontSize'=>8,
																		'width'=>150,
																		'fontSize'=>10,
																		'titleFontSize'=>10,
																		'colGap'=>2));

		$prepayfreight_table = array();
		array_push($prepayfreight_table, array('IF FREIGHT IS PREPAID, MAIL INVOICE TO:'=>$cusname));
		array_push($prepayfreight_table, array('IF FREIGHT IS PREPAID, MAIL INVOICE TO:'=>$cusaddr));
		array_push($prepayfreight_table, array('IF FREIGHT IS PREPAID, MAIL INVOICE TO:'=>$cusstate));
		array_push($prepayfreight_table, array('IF FREIGHT IS PREPAID, MAIL INVOICE TO:'=>$cuspost));

		$pdf->ezSetY(580);
		$pdf->ezTable($prepayfreight_table, '', "<b>IF FREIGHT IS PREPAID, MAIL INVOICE TO:</b>", array('showHeadings'=>0, 
																									'shaded'=>0, 
																									'showLines'=>2,
																									'fontSize'=>8,
																									'width'=>150,
																									'fontSize'=>10,
																									'titleFontSize'=>8,
																									'xPos'=>'right',
																									'xOrientation'=>'left',
																									'colGap'=>2));

		$pdf->ezSetY(480);
		$pdf->ezText("---   IF FOB DELIVERED   ---", 8, $center);
		$pdf->ezSetY(470);
		$pdf->ezText("---   IF FOB WILMINGTON, DE   ---", 8, $center);
		$pdf->ezSetY(490);
		$pdf->ezText("<b>Freight Charges to be Paid By:</b>", 8, $left);
		$pdf->ezSetY(480);
		$pdf->ezText("<b>Shipper  <u>XXXXXXXXXX</u></b>", 8, $left);
		$pdf->ezSetY(470);
		$pdf->ezText("Receiver  __________", 8, $left);
		$pdf->ezSetY(490);
		$pdf->ezText("Freight Arranged For By:", 8, $right);
		$pdf->ezSetY(480);
		$pdf->ezText("<b>Shipper  <u>XXXXXXXXXX</u></b>", 8, $right);
		$pdf->ezSetY(470);
		$pdf->ezText("Consignee  __________", 8, $right);

		$cargoactivity_table = array();
		array_push($cargoactivity_table, array('ctn'=>"<b>NO of packages</b>",
										'comm'=>"<b>Commodity</b>",
										'wt'=>"<b>Weight</b>",
										'plt'=>"<b>Pallets</b>"));
		array_push($cargoactivity_table, array('ctn'=>$ctns,
										'comm'=>$commname,
										'wt'=>$wt." KG",
										'plt'=>$plts));
		$pdf->ezSetY(450);
		$pdf->ezTable($cargoactivity_table, '', "<b>DESCRIPTION OF ARTICLES AND SPECIAL MARKS</b>", array('showHeadings'=>0, 
																										'shaded'=>0, 
																										'showLines'=>2,
																										'fontSize'=>8,
																										'width'=>500,
																										'fontSize'=>10,
																										'colGap'=>2));

		$pdf->ezSetY(360);
		$pdf->ezText("<b><u>REFRIGERATION INSTRUCTION:</u></b>", 10, $left);
		$pdf->ezSetY(360);
		$pdf->ezText("<b><i>TEMPERATURE TO BE MAINTAINED AT 39 deg. F</i></b>", 10, array('aleft'=>250));
		$pdf->ezSetY(350);
		$pdf->ezText("<b><i>TEMP RECORDER #</i>".$temp_record."</b>", 10, array('aleft'=>250));
		$pdf->ezSetY(340);
		$pdf->ezText("<b>BORDER CROSSING POINT:</b>", 10, $left);
		$pdf->ezSetY(340);
		$pdf->ezText("<b>".$bordercross."</b>", 10, array('aleft'=>250));

		$broker_table = array();
		array_push($broker_table, array('line'=>"Special Instructions"));
		array_push($broker_table, array('line'=>"Customs Broker"));
		array_push($broker_table, array('line'=>$broker));
		array_push($broker_table, array('line'=>$brokercontact));
		array_push($broker_table, array('line'=>$brokerphone));
		array_push($broker_table, array('line'=>$brokerfax));

		$pdf->ezSetY(310);
		$pdf->ezTable($broker_table, '', '', array('showHeadings'=>0, 
															'shaded'=>0, 
															'showLines'=>2,
															'fontSize'=>8,
															'width'=>150,
															'fontSize'=>10,
															'xPos'=>'left',
															'xOrientation'=>'right',
															'colGap'=>2));


		$pdf->ezSetY(310);
		$pdf->ezText("<b>SHIPPER</b>", 8, array('aleft'=>250));
		$pdf->ezSetY(310);
		$pdf->ezText("FRESH FRUIT SA", 8, $right);
		$pdf->ezSetY(300);
		$pdf->ezText("AGADIR, MOROCCO", 8, $right);
		$pdf->ezSetY(280);
		$pdf->ezText("<b>FOR MOTOR CARRIER</b>", 8, array('aleft'=>250));
		$pdf->ezSetY(280);
		$pdf->ezText($carriername, 8, $right);
		$pdf->ezSetY(230);
		$pdf->ezText("<b>DRIVER SIGNATURE</b>", 8, array('aleft'=>250));
		$pdf->ezSetY(230);
		$pdf->ezText("_____________________________________", 8, $right);

		$pdf->ezSetY(190);
		$pdf->ezText("DRIVER TO TELEPHONE RECEIVER", 8, $left);
		$pdf->ezSetY(180);
		$pdf->ezText("EVERY MORNING", 8, $left);
		$pdf->ezSetY(160);
		$pdf->ezText("_____________________________________", 8, $left);
		$pdf->ezSetY(150);
		$pdf->ezText("PHONE COLLECT", 8, $left);

		$pdf->ezSetY(130);
		$pdf->ezText("<b>SEAL# ".$seal."</b>", 10, $left);

		$pdf->ezSetY(190);
		$pdf->ezText("CONSIGNEE RECEIPT", 8, array('aleft'=>250));
		$pdf->ezSetY(180);
		$pdf->ezText("Received above perishable property in good order except as noted:", 8, array('aleft'=>250));
		$pdf->ezSetY(160);
		$pdf->ezText("____________________________________________", 8, array('aleft'=>250));
		$pdf->ezSetY(150);
		$pdf->ezText("Date               Signature", 8, array('aleft'=>250));

//		$pdf->ezSetY(120);
//		$pdf->ezText("TEST", 8, array('aleft'=>250));

	}

	include("redirect_pdf.php");
