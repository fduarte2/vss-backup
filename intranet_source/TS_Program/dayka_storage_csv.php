<?
/*
*	Adam Walter, Jan 2013
*
*	A csv to customer 402, sent to finance on a weekly basis
*	For them to review and then forward to the customer.
*************************************************************/
	echo "begin\n";

//  $RF_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $RF_conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($RF_conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($RF_conn));
    	printf("Please try later!");
    	exit;
   }
   $RF_cursor = ora_open($RF_conn);
   $ED_cursor = ora_open($RF_conn); // for email
   $RF_Detail_Cursor = ora_open($RF_conn);
   $RF_Short_Term_Cursor = ora_open($RF_conn);






	$combo_csv = "INVOICE#,INVOICE DATE,LR,VESSEL,SERVICE,PALLET ID,DESCRIPTION,AMOUNT,BILLED QTY\n";
	// storage csv
		// note:  RF storage actually has 2 tables (header and detail), which means I dont have to pseudo-invent nested, 1-off while loops.  yay.
//				AND RF.INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
//				AND RF.INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= '15-dec-2011'
	$sql = "SELECT INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, RF.ARRIVAL_NUM,
				VP.VESSEL_NAME, SERVICE_AMOUNT, BILLING_NUM
			FROM RF_BILLING RF, VESSEL_PROFILE VP
			WHERE RF.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
				AND RF.CUSTOMER_ID = '402'
				AND EXPORT_FILE IS NULL
				AND RF.SERVICE_STATUS = 'INVOICED'
			ORDER BY INVOICE_DATE, INVOICE_NUM";
	ora_parse($RF_cursor, $sql);
	ora_exec($RF_cursor);
	echo $sql."\n";
	if(!ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
//		$storage_csv = "INVOICE#,INVOICE DATE,LR,VESSEL,SERVICE,PALLET ID,DESCRIPTION,AMOUNT,BILLED QTY\n";

		do {
//			echo $RF_row['INVOICE_NUM']."\n";

			$sql = "SELECT * FROM RF_BILLING_DETAIL
					WHERE SUM_BILL_NUM = '".$RF_row['BILLING_NUM']."'
						AND SERVICE_STATUS != 'DELETED'";
			ora_parse($RF_Detail_Cursor, $sql);
			ora_exec($RF_Detail_Cursor);
			echo $sql."\n";
			while(ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$combo_csv .= $RF_row['INVOICE_NUM'].",".$RF_row['INV_DATE'].",".$RF_Detail_row['ARRIVAL_NUM'].",".$RF_row['VESSEL_NAME'].",";
				$combo_csv .= "STORAGE".",".$RF_Detail_row['PALLET_ID'].",".$RF_Detail_row['SERVICE_DESCRIPTION'].",";
				$combo_csv .= number_format($RF_Detail_row['SERVICE_AMOUNT'], 2, ".", "").",".$RF_Detail_row['SERVICE_QTY']."\n";
//				$invoice_total += 0;
			}

//			$storage_csv .= ",,,,,,TOTAL:,"."$".number_format($RF_row['SERVICE_AMOUNT'], 2, ".", "")."\n\n";
			$combo_counter_csv++;

		} while(ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	$sql = "UPDATE RF_BILLING
			SET EXPORT_FILE = 'EMAILED'
			WHERE SERVICE_STATUS = 'INVOICED'
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '402'";
	ora_parse($RF_cursor, $sql);
	ora_exec($RF_cursor);

	// SEND DA EMAIL!
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'DAYKAINV'";
	ora_parse($ED_cursor, $sql);
	ora_exec($ED_cursor);
	ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   
	$mailTO = $email_row['TO'];
	$mailheaders = "From: ".$email_row['FROM']."\r\n";

	if($email_row['CC'] != ""){
		$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
	}
	if($email_row['BCC'] != ""){
		$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
	}

	$mailSubject = $email_row['SUBJECT'];

	$body = $email_row['NARRATIVE'];
	$body = str_replace("_0_", $labor_counter, $body);
	$body = str_replace("_1_", $misc_counter, $body);
	$body = str_replace("_2_", $storage_counter_pdf, $body);

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	if($combo_counter_csv > 0){
		$attach=chunk_split(base64_encode($combo_csv));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"AllBills.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

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
					'WEEKLYCRON',
					SYSDATE,
					'EMAIL',
					'DAYKAINV',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}
