<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION = 'CLEMCBPWEEXPED'
				AND COMPLETION_STATUS = 'PENDING'
				AND JOB_TYPE = 'EMAIL'";
	$queue = ociparse($rfconn, $sql);
	ociexecute($queue);
	while(ocifetch($queue)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = '".ociresult($queue, "JOB_DESCRIPTION")."'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailTO = ociresult($queue, "JOB_EMAIL_TO");

		$ordernum = ociresult($queue, "VARIABLE_LIST");
		$sql = "SELECT TO_CHAR(LASTUPDATEDATETIME, 'MM/DD/YYYY HH24:MI') COMP_DATE, CONSIGNEEID, CUST 
				FROM MOR_ORDER
				WHERE ORDERNUM = '".$ordernum."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$comp_date = ociresult($short_term_data, "COMP_DATE");
		$custnum = ociresult($short_term_data, "CUST");
		$sql = "SELECT CUSTOMSBROKEROFFICEID
				FROM MOR_CONSIGNEE
				WHERE CONSIGNEEID = '".ociresult($short_term_data, "CONSIGNEEID")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$sql = "SELECT * FROM MOR_BROKER
				WHERE BROKERID = '".ociresult($short_term_data, "CUSTOMSBROKEROFFICEID")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$mailTO = str_replace("_1_", ociresult($short_term_data, "EMAIL1"), $mailTO);
		for($i = 2; $i < 6; $i++){
			if(ociresult($short_term_data, "EMAIL".$i) != ""){
				$mailTO .= ",".ociresult($short_term_data, "EMAIL".$i);
			}
		}

		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
		}


		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";

		if(ociresult($queue, "JOB_EMAIL_CC") != ""){
			$mailheaders .= "Cc: ".ociresult($queue, "JOB_EMAIL_CC")."\r\n";
		}
		if(ociresult($queue, "JOB_EMAIL_BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($queue, "JOB_EMAIL_BCC")."\r\n";
		}
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
		$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";
//		echo $mailheaders;

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_2_", $ordernum, $mailSubject);
		$mailSubject = str_replace("_3_", $comp_date, $mailSubject);

		$body = ociresult($queue, "JOB_BODY");

        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
        $Content.=$body;
        $Content.="\r\n";

		include 'class.ezpdf.php';

		$bol_pdf = new Cezpdf('letter');
		MakeBoL($bol_pdf, $ordernum, $rfconn);
		$bolcode = $bol_pdf->ezOutput();
		$bolFile=chunk_split(base64_encode($bolcode));
        $Content.="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: application/pdf; name=\"".$ordernum."-BoL.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$bolFile;
        $Content.="\r\n";

		$tally_pdf = new Cezpdf('letter','landscape');
		MakeTally($tally_pdf, $ordernum, $custnum, $rfconn);
		$tallycode = $tally_pdf->ezOutput();
		$tallyFile=chunk_split(base64_encode($tallycode));
        $Content.="--MIME_BOUNDRY\r\n"; 
        $Content.="Content-Type: application/pdf; name=\"".$ordernum."-tally.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$tallyFile;
        $Content.="\r\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE SET
						DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETED'
					WHERE
						JOB_ID = '".ociresult($queue, "JOB_ID")."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}
	}



function MakeBoL(&$pdf, $order, $conn){
	$right = array('justification'=>'right');
	$center = array('justification'=>'center');
	$pdf->ezSetMargins(20,20,65,65);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT CARRIERNAME, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') DELV_DATE, TRAILERNUM, TRUCKTAG, SNMGNUM, CUSTOMERPO, MO.CONSIGNEEID, SEALNUM,
				CONSIGNEENAME, MCON.ADDRESS CONADD, MCON.CITY, MCON.STATEPROVINCE CONPROV, MCON.PHONE, MB.BORDERCROSSING, MB.CUSTOMSBROKER, MB.CONTACTNAME, MO.ZUSER2,
				MB.PHONE, MB.FAX, MCOM.DC_COMMODITY_NAME, MCUS.CUSTOMERNAME, MCUS.ADDRESS CUSADD, MCUS.STATEPROVINCE CUSPROV, MCUS.POSTALCODE, MO.CUST
			FROM MOR_ORDER MO, MOR_TRANSPORTER MT, MOR_CONSIGNEE MCON, MOR_BROKER MB, MOR_COMMODITY MCOM, MOR_CUSTOMER MCUS
			WHERE MO.ORDERNUM = '".$order."'
				AND MO.TRANSPORTERID = MT.TRANSPORTID
				AND MO.CONSIGNEEID = MCON.CONSIGNEEID
				AND MCON.CUSTOMSBROKEROFFICEID = MB.BROKERID
				AND MO.COMMODITYCODE = MCOM.PORT_COMMODITY_CODE
				AND MO.CUSTOMERID = MCUS.CUSTOMERID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist; please contact PoW</b>", 14, $center);
	} else {

		$custnum = ociresult($stid, "CUST");
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
					AND CUSTOMER_ID = '".$custnum."'
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

}


function MakeTally(&$pdf, $order, $custnum, $conn){
	$right = array('justification'=>'right');
	$center = array('justification'=>'center');
	$pdf->ezSetMargins(20,20,65,65);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_END,
				CA.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCK') THE_VES, CUSTOMER_NAME
			FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CUSTOMER_PROFILE CP
			WHERE CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
				AND CA.CUSTOMER_ID = CP.CUSTOMER_ID
				AND CA.ORDER_NUM = '".$order."'
				AND CA.CUSTOMER_ID = '".$custnum."'
			GROUP BY CA.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCK'), CUSTOMER_NAME";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist, please contact PoW</b>", 14, $center);
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
					AND CA.CUSTOMER_ID = '".$custnum."'
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

	}

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
