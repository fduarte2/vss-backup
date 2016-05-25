<?
//	echo "what5?\n";


  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail("awalter@port.state.de.us", "walmart aging 'sploded", "walmart projection DB access failure", $mailheaders);
    exit;
  }

  $select_cursor1 = ora_open($conn);         
  $select_cursor2 = ora_open($conn);         
  $Short_Term_Cursor = ora_open($conn);

	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
	$total_pallets = 0;

	$any_pending = true;
	$any_here = true;

//	echo "what4?\n";


	$xls_file = "<TABLE border=1 CELLSPACING=1>";
	$xls_file .= "<tr><td>QC</td>
						<td>USDA</td>
						<td>&nbsp;</td>
						<td>Days</td>
						<td>Master</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>Cargo</td>
						<td>&nbsp;</td>
						<td>Vesl</td>
						<td>Supplier</td>
					</tr>";
	$xls_file .= "<tr><td>Comment</td>
						<td>Held?</td>
						<td>Vessel Date</td>
						<td>In</td>
						<td>Item No.</td>
						<td>Item Description</td>
						<td>Booking PO</td>
						<td>Pallet ID</td>
						<td>Location</td>
						<td>Cartons</td>
						<td>No.</td>
						<td>Pack Date</td>
					</tr>";

	$sql = "SELECT WALMART_REPORT_HEADER_SEQ.NEXTVAL THE_VAL FROM DUAL";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$report_num = $row['THE_VAL'];

	$sql = "INSERT INTO WDI_DATA_REPORT_HEADER
			(REPORT_TYPE,
			DATE_CREATED,
			HEADER_ID)
			VALUES
			('AGING REPORT',
			TO_DATE('".$date."', 'MM/DD/YYYY'),
			'".$report_num."')";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor);

//	echo "what3?\n";


	 // pending pallets.
	$sql = "SELECT CT.PALLET_ID, TO_CHAR(RELEASE_TIME, 'MM/DD/YYYY') RELEASED, ROUND(SYSDATE - RELEASE_TIME) DAYS_HERE, NVL(WIM.WM_ITEM_NUM, 0000) THE_ITEM, CT.WAREHOUSE_LOCATION,
				NVL(WIM.VARIETY, 'MULTIPLE') THE_VAR, QTY_IN_HOUSE, CT.ARRIVAL_NUM || '-' || NVL(VP.VESSEL_NAME, 'UNKNOWN') THE_VES, CT.CARGO_STATUS, CTAD.USDA_HOLD, 
				NVL(SORT_ORDER_NUMBER, 9999) THE_SORT, NVL(TO_CHAR(CTAD.SUPPLIER_PACKDATE, 'MM/DD/YYYY'), 'NONE') THE_PACKDATE, CT.MARK
			FROM CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_ITEMNUM_MAPPING WIM, VESSEL_PROFILE VP, WDI_AGING_STATUS_REPORT_ORDER WASRO, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.QTY_IN_HOUSE > 0
			AND CTAD.PALLET_ID = CT.PALLET_ID
			AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
			AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
			AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
			AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
			AND CT.BATCH_ID = TO_CHAR(WIM.ITEM_NUM(+))
			AND DATE_RECEIVED IS NULL
			AND NVL(CARGO_STATUS, 'GOOD') = WASRO.STATUS_ID(+)
			AND CT.RECEIVER_ID IN 
				(SELECT RECEIVER_ID FROM SCANNER_ACCESS
				WHERE VALID_SCANNER = 'WALMART')
			GROUP BY CT.PALLET_ID, TO_CHAR(RELEASE_TIME, 'MM/DD/YYYY'), ROUND(SYSDATE - RELEASE_TIME), NVL(WIM.VARIETY, 'MULTIPLE'), NVL(TO_CHAR(CTAD.SUPPLIER_PACKDATE, 'MM/DD/YYYY'), 'NONE'), CT.WAREHOUSE_LOCATION, CTAD.USDA_HOLD,
				NVL(WIM.WM_ITEM_NUM, 0000), QTY_IN_HOUSE, CT.ARRIVAL_NUM || '-' || NVL(VP.VESSEL_NAME, 'UNKNOWN'), CT.CARGO_STATUS,  NVL(SORT_ORDER_NUMBER, 9999), CT.MARK
			ORDER BY THE_SORT, DAYS_HERE DESC, NVL(WIM.WM_ITEM_NUM, 0000), CT.PALLET_ID";
	ora_parse($select_cursor1, $sql);
	ora_exec($select_cursor1);
	if(!ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$any_pending = false;
//		$body = "No pallets in house.\r\n\r\n";
	} else {
/*		$body = "\r\n\r\n";

*/

		do {
			$total_pallets++;

//			if($select_row1['DATE_RECEIVED'] == ""){
				$select_row1['CARGO_STATUS'] = "PENDING  ".$select_row1['CARGO_STATUS'];
//			}

			$xls_file .= "<tr><td>".$select_row1['CARGO_STATUS']."</td>
								<td>".$select_row1['USDA_HOLD']."</td>
								<td>".$select_row1['RELEASED']."</td>
								<td>".$select_row1['DAYS_HERE']."</td>
								<td>".$select_row1['THE_ITEM']."</td>
								<td>".$select_row1['THE_VAR']."</td>
								<td>".$select_row1['MARK']."</td>
								<td>".$select_row1['PALLET_ID']."</td>
								<td>".$select_row1['WAREHOUSE_LOCATION']."</td>
								<td>".$select_row1['QTY_IN_HOUSE']."</td>
								<td>".$select_row1['THE_VES']."</td>
								<td>".$select_row1['THE_PACKDATE']."</td>
							</tr>";

			$sql = "INSERT INTO WDI_AGING_REPORT
						(HEADER_ID,
						QC_COMMENT,
						USDA_HOLD,
						VESSEL_DATE,
						DAYS_HERE,
						MASTER_ITEM_NO,
						ITEM_DESC,
						PALLET_ID,
						WAREHOUSE_LOCATION,
						CARTONS,
						ARRIVAL_NUM,
						SUPPLIER_PACKDATE)
					VALUES
						('".$report_num."',
						'".$select_row1['CARGO_STATUS']."',
						'".$select_row1['USDA_HOLD']."',
						TO_DATE('".$select_row1['RELEASED']."', 'MM/DD/YYYY'),
						'".$select_row1['DAYS_HERE']."',
						'".$select_row1['THE_ITEM']."',
						'".$select_row1['THE_VAR']."',
						'".$select_row1['PALLET_ID']."',
						'".$select_row1['WAREHOUSE_LOCATION']."',
						'".$select_row1['QTY_IN_HOUSE']."',
						'".$select_row1['THE_VES']."',
						DECODE('".$select_row1['THE_PACKDATE']."', 'NONE', NULL, to_date('".$select_row1['THE_PACKDATE']."', 'MM/DD/YYYY')))";
			$ora_success = ora_parse($Short_Term_Cursor, $sql);
			$ora_success = ora_exec($Short_Term_Cursor);
		}  while(ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

//	echo "what?\n";

	 // received pallets.
	$sql = "SELECT CT.PALLET_ID, TO_CHAR(RELEASE_TIME, 'MM/DD/YYYY') RELEASED, ROUND(SYSDATE - RELEASE_TIME) DAYS_HERE,	NVL(WIM.WM_ITEM_NUM, 0000) THE_ITEM, CT.WAREHOUSE_LOCATION,
				NVL(WIM.VARIETY, 'MULTIPLE') THE_VAR, QTY_IN_HOUSE, CT.ARRIVAL_NUM || '-' || NVL(VP.VESSEL_NAME, 'UNKNOWN') THE_VES, CT.CARGO_STATUS, CTAD.USDA_HOLD, 
				NVL(SORT_ORDER_NUMBER, 9999) THE_SORT, NVL(TO_CHAR(CTAD.SUPPLIER_PACKDATE, 'MM/DD/YYYY'), 'NONE') THE_PACKDATE, CT.MARK
			FROM CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_ITEMNUM_MAPPING WIM, VESSEL_PROFILE VP, WDI_AGING_STATUS_REPORT_ORDER WASRO, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.QTY_IN_HOUSE > 0
			AND CTAD.PALLET_ID = CT.PALLET_ID
			AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
			AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
			AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
			AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
			AND CT.BATCH_ID = TO_CHAR(WIM.ITEM_NUM(+))
			AND DATE_RECEIVED IS NOT NULL
			AND NVL(CARGO_STATUS, 'GOOD') = WASRO.STATUS_ID(+)
			AND CT.RECEIVER_ID IN 
				(SELECT RECEIVER_ID FROM SCANNER_ACCESS
				WHERE VALID_SCANNER = 'WALMART')
			GROUP BY CT.PALLET_ID, TO_CHAR(RELEASE_TIME, 'MM/DD/YYYY'), ROUND(SYSDATE - RELEASE_TIME), NVL(WIM.VARIETY, 'MULTIPLE'), NVL(TO_CHAR(CTAD.SUPPLIER_PACKDATE, 'MM/DD/YYYY'), 'NONE'), CT.WAREHOUSE_LOCATION, CTAD.USDA_HOLD,
				NVL(WIM.WM_ITEM_NUM, 0000), QTY_IN_HOUSE, CT.ARRIVAL_NUM || '-' || NVL(VP.VESSEL_NAME, 'UNKNOWN'), CT.CARGO_STATUS,  NVL(SORT_ORDER_NUMBER, 9999), CT.MARK
			ORDER BY THE_SORT, DAYS_HERE DESC, NVL(WIM.WM_ITEM_NUM, 0000), CT.PALLET_ID";
	ora_parse($select_cursor1, $sql);
	ora_exec($select_cursor1);
	if(!ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$any_here = false;
//		$body = "No pallets in house.\r\n\r\n";
	} else {
/*		$body = "\r\n\r\n";

*/

		do {
			$total_pallets++;

			$xls_file .= "<tr><td>".$select_row1['CARGO_STATUS']."</td>
								<td>".$select_row1['USDA_HOLD']."</td>
								<td>".$select_row1['RELEASED']."</td>
								<td>".$select_row1['DAYS_HERE']."</td>
								<td>".$select_row1['THE_ITEM']."</td>
								<td>".$select_row1['THE_VAR']."</td>
								<td>".$select_row1['MARK']."</td>
								<td>".$select_row1['PALLET_ID']."</td>
								<td>".$select_row1['WAREHOUSE_LOCATION']."</td>
								<td>".$select_row1['QTY_IN_HOUSE']."</td>
								<td>".$select_row1['THE_VES']."</td>
								<td>".$select_row1['THE_PACKDATE']."</td>
							</tr>";

			$sql = "INSERT INTO WDI_AGING_REPORT
						(HEADER_ID,
						QC_COMMENT,
						USDA_HOLD,
						VESSEL_DATE,
						DAYS_HERE,
						MASTER_ITEM_NO,
						ITEM_DESC,
						PALLET_ID,
						WAREHOUSE_LOCATION,
						CARTONS,
						ARRIVAL_NUM,
						SUPPLIER_PACKDATE)
					VALUES
						('".$report_num."',
						'".$select_row1['CARGO_STATUS']."',
						'".$select_row1['USDA_HOLD']."',
						TO_DATE('".$select_row1['RELEASED']."', 'MM/DD/YYYY'),
						'".$select_row1['DAYS_HERE']."',
						'".$select_row1['THE_ITEM']."',
						'".$select_row1['THE_VAR']."',
						'".$select_row1['PALLET_ID']."',
						'".$select_row1['WAREHOUSE_LOCATION']."',
						'".$select_row1['QTY_IN_HOUSE']."',
						'".$select_row1['THE_VES']."',
						DECODE('".$select_row1['THE_PACKDATE']."', 'NONE', NULL, to_date('".$select_row1['THE_PACKDATE']."', 'MM/DD/YYYY')))";
//			echo $sql."\n";
			$ora_success = ora_parse($Short_Term_Cursor, $sql);
			$ora_success = ora_exec($Short_Term_Cursor);
		}  while(ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

//	echo "what2?\n";


	if(!$any_pending && !$any_here){
		$body = "No pallets in house.\r\n\r\n";
	} else {
		$body = "\r\n\r\n";
	}

	$xls_file .= "</table>";

	$xls_attach=chunk_split(base64_encode($xls_file));
//	$mailTo = "awalter@port.state.de.us,archive@port.state.de.us,sadu@port.state.de.us";

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WALMARTAGING'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
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
	$mailSubject = str_replace("_0_", $date, $mailSubject);
	$mailSubject = str_replace("_1_", $total_pallets, $mailSubject);
	if(ociresult($email, "TEST") == "Y"){
		$mailSubject = "THIS IS A TEST - PLEASE IGNORE. ".$mailSubject;
	}

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $replace_body, $body);
/*
	$mailTo = "wilmingt47@wal-mart.com";
	$mailsubject = "Pallet Aging Report for ".$date.", ".$total_pallets." pallets.\r\n";
//	$mailsubject = "Pallet Aging Report (V2 test) for ".$date.", ".$total_pallets." pallets.\r\n";

	$mailheaders = "From: " . "pownoreply@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "jaime.salazar@wal-mart.com,wdi@port.state.de.us,Jeff.Hicks@wal-mart.com,schargr@wal-mart.com\r\n";
	$mailheaders .= "Bcc: " . "sadu@port.state.de.us,awalter@port.state.de.us,lstewart@port.state.de.us,archive@port.state.de.us\r\n";
//  bdempsey@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us,ddonofrio@port.state.de.us
*/
	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"PalletAging".$date.".xls\"\r\n";
//	$Content.="Content-Type: application/pdf; name=\"PalletAgingV2Test".$date.".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$xls_attach;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";

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
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'WALMARTAGING',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}

//	mail($mailTo, $mailsubject, $Content, $mailheaders);
?>