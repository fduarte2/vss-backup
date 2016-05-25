<?
/*
*	MAr 2014.
*
*	bol-print for Argen Fruit expediting
*****************************************************************************************/

	include("pow_session.php");
	include 'class.ezpdf.php';

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$order_num = $HTTP_GET_VARS['order_num'];
	$sendmail = false;
	$send_lucca_email = false;
	$cust_num = "1626";

	$sql = "SELECT CHECK_OUT, STATUS
			FROM ARGENFRUIT_CHECKIN_ID ACI, ARGENFRUIT_ORDER_HEADER AOH
			WHERE ACI.CHECKIN_ID(+) = AOH.CHECKIN_ID
				AND ORDER_NUM = '".$order_num."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "CHECK_OUT") == "" && ociresult($short_term_data, "STATUS") == "3"){
		$sql = "UPDATE ARGENFRUIT_CHECKIN_ID SET CHECK_OUT = SYSDATE
				WHERE CHECKIN_ID = (SELECT CHECKIN_ID FROM ARGENFRUIT_ORDER_HEADER WHERE ORDER_NUM = '".$order_num."')
					AND CHECK_OUT IS NULL";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "UPDATE ARGENFRUIT_ORDER_HEADER SET STATUS = '9'
				WHERE ORDER_NUM = '".$order_num."'
					AND STATUS = '3'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "UPDATE ARGENFRUIT_ORDER_HEADER 
				SET BOL_SEQ = (SELECT MAX(BOL_SEQ) + 1 FROM ARGENFRUIT_ORDER_HEADER)
				WHERE ORDER_NUM = '".$order_num."'
					AND BOL_SEQ IS NULL";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sendmail = true;
	}

	$pdf = new Cezpdf('letter');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);
	$pdf->addJpegFromFile('bridges_bol.jpg', 0, 0, 600, 800);

	$sql = "SELECT AOH.ORDER_NUM, ECUST.CUSTOMER_NAME, ECUST.CUSTOMER_ADDRESS, ECON.CONSIGNEE_NAME, AOH.SPECIAL_INST, ECUST.CUSTOMER_CODE,
				AOH.CUSTOMER_PO, NVL(TO_CHAR(ACI.CHECK_OUT, 'MM/DD/YYYY HH24:MI'), 'N/A') DATE_SHIP,
				AT.COMPANY_NAME, AT.TRANSPORT_ID, TO_CHAR(AOH.EXPECTED_DATE, 'MM/DD/YYYY') THE_DATE,
				ACI.TRUCK_LIC_AND_STATE, ACI.TRAILER_LIC_AND_STATE, ACI.DRIVER_PHONE, ACI.TEMP_RECORDER, ACI.SIGNATURE, AOH.BOL_SEQ
			FROM EXP_CUSTOMER ECUST, EXP_CONSIGNEE ECON, ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_TRANSPORT AT, ARGFRUIT_STATUS AST, ARGENFRUIT_CHECKIN_ID ACI
			WHERE AOH.CUSTOMER_ID = ECUST.CUSTOMER_CODE(+)
				AND AOH.CONSIGNEE_ID = ECON.CONSIGNEE_CODE(+)
				AND AOH.STATUS = AST.STATUS
				AND AOH.CHECKIN_ID = ACI.CHECKIN_ID(+)
				AND AOH.TRANSPORT_ID = AT.TRANSPORT_ID(+)
				AND AOH.ORDER_NUM = '".$order_num."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$cust = ociresult($short_term_data, "CUSTOMER_NAME");
	$cust_code = ociresult($short_term_data, "CUSTOMER_CODE");
	$cust_addr = ociresult($short_term_data, "CUSTOMER_ADDRESS");
	$cons = ociresult($short_term_data, "CONSIGNEE_NAME");
	$ship_out = ociresult($short_term_data, "DATE_SHIP");
	$cust_po = ociresult($short_term_data, "CUSTOMER_PO");
	$trans = ociresult($short_term_data, "TRANSPORT_ID")." ".ociresult($short_term_data, "COMPANY_NAME");
	$driver_num = ociresult($short_term_data, "CHECKIN_ID");
	$special_inst = ociresult($short_term_data, "SPECIAL_INST");
	$lic = ociresult($short_term_data, "TRUCK_LIC_AND_STATE");
	$trailer_lic = ociresult($short_term_data, "TRAILER_LIC_AND_STATE");
	$driver_phone = ociresult($short_term_data, "DRIVER_PHONE");
	$temp_rec = ociresult($short_term_data, "TEMP_RECORDER");
	$signature = ociresult($short_term_data, "SIGNATURE");
	$seq = ociresult($short_term_data, "BOL_SEQ");

	if($sendmail == true && $cust_code == "LUC.CA"){
		$send_lucca_email = true;
	}

	$pdf->addText(55, 730, 10, $seq);
	$pdf->addText(95, 643, 10, $lic);
	$pdf->addText(95, 620, 10, $trailer_lic);
	$pdf->addText(150, 598, 10, $trans);

	$pdf->addText(400, 679, 10, $order_num);
	$pdf->addText(400, 649, 10, $cust_po);
	$pdf->addText(400, 622, 10, $driver_phone);
	$pdf->addText(440, 598, 10, $ship_out);

	$pdf->addText(38, 457, 10, $cust);
	if($cons != ""){
		$pdf->addText(38, 437, 10, $cons);
		$pdf->addText(38, 416, 10, $cust_addr);
	} else {
		$pdf->addText(38, 437, 10, $cust_addr);
	}


	//BATCH_ID, BOL, CARGO_SIZE, 
	$detail_line_y = 317;
	$sql = "SELECT VARIETY, COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CASES
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ORDER_NUM = UPPER('".$order_num."')
				AND CA.SERVICE_CODE = '6'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL)
				AND CA.CUSTOMER_ID = '".$cust_num."'
			GROUP BY VARIETY";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$pdf->addText(38, $detail_line_y, 10, ociresult($short_term_data, "THE_CASES")." cartons");
		$pdf->addText(135, $detail_line_y, 10, ociresult($short_term_data, "THE_PLTS")." plts");
		$pdf->addText(205, $detail_line_y, 10, "Organic ".ociresult($short_term_data, "VARIETY"));

		$detail_line_y -= 20;
	}

	if($signature != ""){
		$pdf->addJpegFromFile("./signatures/".$signature, 405, 116, 150, 30);
	}



	$pdf->addText(40, 137, 10, $special_inst);
	$pdf->addText(375, 185, 10, $temp_rec);
	$pdf->addText(405, 149, 10, $trans);


	// second PDF, cause the other guys arent allowed to see all the data
	$pdf2 = new Cezpdf('letter');
	$pdf2->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf2->setFontFamily('Helvetica.afm', $tmp);
	$pdf2->addJpegFromFile('bridges_bol.jpg', 0, 0, 600, 800);

	$sql = "SELECT AOH.ORDER_NUM, ECUST.CUSTOMER_NAME, ECUST.CUSTOMER_ADDRESS, ECON.CONSIGNEE_NAME, AOH.SPECIAL_INST, ECUST.CUSTOMER_CODE,
				AOH.CUSTOMER_PO, NVL(TO_CHAR(ACI.CHECK_OUT, 'MM/DD/YYYY HH24:MI'), 'N/A') DATE_SHIP,
				AT.COMPANY_NAME, AT.TRANSPORT_ID, TO_CHAR(AOH.EXPECTED_DATE, 'MM/DD/YYYY') THE_DATE,
				ACI.TRUCK_LIC_AND_STATE, ACI.TRAILER_LIC_AND_STATE, ACI.DRIVER_PHONE, ACI.TEMP_RECORDER, ACI.SIGNATURE
			FROM EXP_CUSTOMER ECUST, EXP_CONSIGNEE ECON, ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_TRANSPORT AT, ARGFRUIT_STATUS AST, ARGENFRUIT_CHECKIN_ID ACI
			WHERE AOH.CUSTOMER_ID = ECUST.CUSTOMER_CODE(+)
				AND AOH.CONSIGNEE_ID = ECON.CONSIGNEE_CODE(+)
				AND AOH.STATUS = AST.STATUS
				AND AOH.CHECKIN_ID = ACI.CHECKIN_ID(+)
				AND AOH.TRANSPORT_ID = AT.TRANSPORT_ID(+)
				AND AOH.ORDER_NUM = '".$order_num."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$cust = ociresult($short_term_data, "CUSTOMER_NAME");
	$cust_code = ociresult($short_term_data, "CUSTOMER_CODE");
	$cust_addr = ociresult($short_term_data, "CUSTOMER_ADDRESS");
	$cons = ociresult($short_term_data, "CONSIGNEE_NAME");
	$ship_out = ociresult($short_term_data, "DATE_SHIP");
	$cust_po = ociresult($short_term_data, "CUSTOMER_PO");
	$trans = ociresult($short_term_data, "TRANSPORT_ID")." ".ociresult($short_term_data, "COMPANY_NAME");
	$driver_num = ociresult($short_term_data, "CHECKIN_ID");
	$special_inst = ociresult($short_term_data, "SPECIAL_INST");
	$lic = ociresult($short_term_data, "TRUCK_LIC_AND_STATE");
	$trailer_lic = ociresult($short_term_data, "TRAILER_LIC_AND_STATE");
	$driver_phone = ociresult($short_term_data, "DRIVER_PHONE");
	$temp_rec = ociresult($short_term_data, "TEMP_RECORDER");
	$signature = ociresult($short_term_data, "SIGNATURE");

	if($sendmail == true && $cust_code == "LUC.CA"){
		$send_lucca_email = true;
	}

	$pdf2->addText(95, 643, 10, $lic);
	$pdf2->addText(95, 620, 10, $trailer_lic);
	$pdf2->addText(150, 598, 10, "N/A");

	$pdf2->addText(400, 679, 10, $order_num);
	$pdf2->addText(400, 649, 10, $cust_po);
	$pdf2->addText(400, 622, 10, "N/A");
	$pdf2->addText(440, 598, 10, $ship_out);

	$pdf2->addText(38, 457, 10, "Bridges Produce");
	$pdf2->addText(38, 437, 10, "P.O.B. 820176");
	$pdf2->addText(38, 416, 10, "Portland, OR  97282");


	//BATCH_ID, BOL, CARGO_SIZE, 
	$detail_line_y = 317;
	$sql = "SELECT VARIETY, COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CASES
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ORDER_NUM = UPPER('".$order_num."')
				AND CA.SERVICE_CODE = '6'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL)
				AND CA.CUSTOMER_ID = '".$cust_num."'
			GROUP BY VARIETY";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$pdf2->addText(38, $detail_line_y, 10, ociresult($short_term_data, "THE_CASES")." cartons");
		$pdf2->addText(135, $detail_line_y, 10, ociresult($short_term_data, "THE_PLTS")." plts");
		$pdf2->addText(205, $detail_line_y, 10, "Organic ".ociresult($short_term_data, "VARIETY"));

		$detail_line_y -= 20;
	}

	if($signature != ""){
		$pdf2->addJpegFromFile("./signatures/".$signature, 405, 116, 150, 30);
	}



	$pdf2->addText(40, 137, 10, $special_inst);
	$pdf2->addText(375, 185, 10, $temp_rec);
	$pdf2->addText(405, 149, 10, $trans);


	if($sendmail){
		// we also need a txt of the tally for this.
		$tally = "";
		$tally .= "Printed On:  ".date('m/d/Y h:i:s a')."\r\n\r\n";
		$tally .= "PORT OF WILMINGTON FRUIT OUTBOUND TALLY\r\n";
		$tally .= "Customer: ".$cust."\r\n";
		$tally .= "Order#: ".$order_num."\r\n";
		$tally .= "SHIPPING TYPE:  OUTBOUND\r\n";

		$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
				NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
				FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND ACTIVITY_NUM != '1' AND CUSTOMER_ID = '".$cust_num."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$start = "";
			$end = "";
		} else {
			$start = ociresult($short_term_data, "START_TIME");
			$end = ociresult($short_term_data, "END_TIME");
		}

		$tally .= "First Scan: ".$start."\r\n";
		$tally .= "Last Scan: ".$end."\r\n\r\n";

		$the_BC = str_pad("BARCODE", 20);
		$the_desc = str_pad("DESCRIPTION", 70);
		$the_qty = str_pad("QTY", 6);
		$the_ves = str_pad("VESSEL", 31);
		$the_checker = str_pad("CHECKER", 8);

		$tally .= $the_BC.$the_desc.$the_qty.$the_ves.$the_checker."\r\n";
		$tally .= "======================================================================================================================================\r\n";

		$sql = "SELECT PALLET_ID, QTY_CHANGE, ACTIVITY_NUM, ARRIVAL_NUM, SERVICE_CODE, 
					DECODE(SERVICE_CODE, '5', 'TR', '7', 'FR', '9', 'RE', '12', 'VO', '13', 'DR', '') THE_SERV
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust_num."'
					AND SERVICE_CODE NOT IN ('1', '8', '12', '18', '19', '21', '22') 
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				ORDER BY PALLET_ID, ACTIVITY_NUM";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
			$emp_name = get_employee_for_print(ociresult($short_term_data, "PALLET_ID"), 
												ociresult($short_term_data, "ARRIVAL_NUM"), $cust_num, 
												ociresult($short_term_data, "ACTIVITY_NUM"), $rfconn);
			$total_ctn += ociresult($short_term_data, "QTY_CHANGE");
			$total_plt++;

			$the_BC = str_pad(trim(ociresult($short_term_data, "PALLET_ID")), 20);
			$the_desc = str_pad(GetPalletDesc(ociresult($short_term_data, "PALLET_ID"), ociresult($short_term_data, "ARRIVAL_NUM"), $cust_num, $rfconn), 70);
			$the_qty = str_pad(ociresult($short_term_data, "QTY_CHANGE"), 6);
			$the_ves = str_pad(GetVesName(ociresult($short_term_data, "ARRIVAL_NUM"), $rfconn), 31);
			$the_checker = str_pad(trim($emp_name), 8);
			$tally .= $the_BC.$the_desc.$the_qty.$the_ves.$the_checker."\r\n";
		}

		$tally .= "\r\n";
		$tally .= "\r\n";
		$disp_total_spaces = str_pad("", 70);
		$disp_total_words = str_pad(trim("Total Cases:"), 20);
		$disp_total_qty = str_pad(trim($total_ctn), 6);
		$tally .= $disp_total_spaces.$disp_total_words.$disp_total_qty."\r\n";
		$disp_total_spaces = str_pad("", 70);
		$disp_total_words = str_pad(trim("Total Pallets:"), 20);
		$disp_total_qty = str_pad(trim($total_plt), 6);
		$tally .= $disp_total_spaces.$disp_total_words.$disp_total_qty."\r\n";
		$tally .= "\r\n";

		// txt file complete






		// email #1
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PATAGONIAJOSEDOCS'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
//		$mailheaders = "From: PoWNoReplies@port.state.de.us\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", $order_num, $mailSubject);
		$body = ociresult($email, "NARRATIVE");
//		$mailSubject = "test dual-attach";
//		$body = "ordernum: ".$order_num;

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$attach = chunk_split(base64_encode($tally));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/text; name=\"tally".$order_num.".txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		$pdfcode2 = $pdf2->ezOutput();
		$File2=chunk_split(base64_encode($pdfcode2));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"bol".$order_num.".pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File2;
		$Content.="\r\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'INSTANT',
						SYSDATE,
						'EMAIL',
						'PATAGONIATALLY',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
		}





		// email #2
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PATAGONIATALLYBOL'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
//		$mailheaders = "From: PoWNoReplies@port.state.de.us\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", $order_num, $mailSubject);
		$body = ociresult($email, "NARRATIVE");
//		$mailSubject = "test dual-attach";
//		$body = "ordernum: ".$order_num;

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$attach = chunk_split(base64_encode($tally));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/text; name=\"tally".$order_num.".txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		$pdfcode = $pdf->ezOutput();
		$File=chunk_split(base64_encode($pdfcode));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"bol".$order_num.".pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File;
		$Content.="\r\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'INSTANT',
						SYSDATE,
						'EMAIL',
						'PATAGONIATALLYBOL',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
		}

		// email #3
		if($send_lucca_email == true){
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PATAGONIALUCCA'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);

			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	//		$mailheaders = "From: PoWNoReplies@port.state.de.us\r\n";
			if(ociresult($email, "TEST") == "Y"){
				$mailTO = "awalter@port.state.de.us";
				$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
			} else {
				$mailTO = ociresult($email, "TO");
				if(ociresult($email, "CC") != ""){
					$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
				}
				if(ociresult($email, "BCC") != ""){
					$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
				}
			}
			$mailheaders .= "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
			$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
			$mailheaders .= "X-Mailer: PHP4\r\n";
			$mailheaders .= "X-Priority: 3\r\n";
			$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", $order_num, $mailSubject);
			$body = ociresult($email, "NARRATIVE");
	//		$mailSubject = "test dual-attach";
	//		$body = "ordernum: ".$order_num;

			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;
			$Content.="\r\n";

			$attach = chunk_split(base64_encode($tally));
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/text; name=\"tally".$order_num.".txt\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";

			if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
				$sql = "INSERT INTO JOB_QUEUE
							(JOB_ID,
							SUBMITTER_ID,
							SUBMISSION_DATETIME,
							JOB_TYPE,
							JOB_DESCRIPTION,
							DATE_JOB_COMPLETED,
							COMPLETION_STATUS,
							JOB_EMAIL_TO,
							JOB_EMAIL_CC,
							JOB_EMAIL_BCC,
							JOB_BODY)
						VALUES
							(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
							'INSTANT',
							SYSDATE,
							'EMAIL',
							'PATAGONIALUCCA',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);
			}
		}


	}
	include("redirect_pdf.php");





function get_employee_for_print($Barcode, $LR, $cust, $act_num, $rfconn){

	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$LR."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$date = ociresult($short_term_data, "THE_DATE");
	$emp_no = ociresult($short_term_data, "ACTIVITY_ID");

	if($emp_no == ""){
		return "UNKNOWN";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE
			WHERE CHANGE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") < 1){
		$sql = "SELECT LOGIN_ID THE_EMP
				FROM PER_OWNER.PERSONNEL
				WHERE EMPLOYEE_ID = '".$emp_no."'";
	} else {
//		return $emp_no;
		while(strlen($emp_no) < 5){
			$emp_no = "0".$emp_no;
		}
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($emp_no).") = '".$emp_no."'"; 
	}
//	echo $sql."\n";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	return ociresult($short_term_data, "THE_EMP");
}

function GetPalletDesc($pallet, $vessel, $cust, $rfconn){

	$return = "";

	$sql = "SELECT COMMODITY_NAME, DECODE(VARIETY, NULL, '', ' - ' || VARIETY) THE_VAR, 
				DECODE(REMARK, NULL, '', ' - ' || REMARK) THE_REM, 
				DECODE(CARGO_SIZE, NULL, '', ' - ' || CARGO_SIZE) THE_SIZE, 
				DECODE(BATCH_ID, NULL, '', ' - ' || BATCH_ID) THE_VOUCHER, 
				NVL(WAREHOUSE_LOCATION, 'NONE') THE_WHS,
				NVL(CONTAINER_ID, 'Not Specified.') THE_CONT
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE PALLET_ID = '".$pallet."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$vessel."'
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE";
//	echo $sql."\n";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$return = "Organic ".ociresult($short_term_data, "COMMODITY_NAME").ociresult($short_term_data, "THE_VAR").ociresult($short_term_data, "THE_REM").ociresult($short_term_data, "THE_VOUCHER").ociresult($short_term_data, "THE_SIZE");

	// NOT USED IN INBOUND TALLY
//	if($short_term_data_row['THE_WHS'] == "SW"){
//		$swingflag = $short_term_data_row['THE_CONT'];
//	}

	return $return;
}



function GetVesName($vessel, $rfconn){

	$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		return "TRUCKED-IN";
	} else {
		return $vessel."-".ociresult($short_term_data, "VESSEL_NAME");
	}
}
