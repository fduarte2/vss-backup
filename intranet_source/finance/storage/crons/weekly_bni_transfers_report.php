<?
/*
*
*	Adam Walter, Jan 2010
*
*	This program, designed to run WEEKLY, will send emails alerting Finance
*	To any BNI-cargo transfers for the week run.
*	Part of the 2009-2010 "new" storage system.
*********************************************************************************/

	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  $header_cursor = ora_open($conn);         // general purpose
  $modify_cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($rf_conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);


//	$mailTO = "billing@port.state.de.us,sshoemaker@port.state.de.us,vfarkas@port.state.de.us,philhower@port.state.de.us\r\n";
//	$mailTO = "awalter@port.state.de.us\r\n";
//	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n"; 
//	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	

//				AND 1 = 2
	$sql = "SELECT CM.COMMODITY_CODE, CT.LOT_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_TRANS, CA.CUSTOMER_ID THE_REC, 
			CT.OWNER_ID THE_FROM, CM.LR_NUM, CA.QTY_CHANGE, CP1.CUSTOMER_NAME THE_FROM_NAME, CP2.CUSTOMER_NAME THE_REC_NAME,
			COMP.COMMODITY_NAME THE_COMM
			FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP1, CUSTOMER_PROFILE CP2, COMMODITY_PROFILE COMP
			WHERE CT.LOT_NUM = CM.CONTAINER_NUM
				AND CT.LOT_NUM = CA.LOT_NUM
				AND CA.SERVICE_CODE = '6120'
				AND CT.OWNER_ID = CP1.CUSTOMER_ID
				AND CA.CUSTOMER_ID = CP2.CUSTOMER_ID
				AND CM.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CA.DATE_OF_ACTIVITY >= TO_DATE(TO_CHAR(SYSDATE - 6, 'MM/DD/YYYY'), 'MM/DD/YYYY')
				AND CA.DATE_OF_ACTIVITY <= TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')
			ORDER BY CT.LOT_NUM, DATE_OF_ACTIVITY";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ // 5/19/2015 AW : mail now handled farther down
//		$mailSubject = "---NO--- BNI transfers last week (TEST for HD#10035)\r\n";
		$mailSubject = "---NO--- BNI transfers last week\r\n";
		$output_file = "";
//		mail($mailTO, $mailsubject, "no BNI transfers last week\r\n", $mailheaders);
	} else {
		$output_file = "LOT,VESSEL,COMM,NEW OWNER,PREVIOUS OWNER,TRANSFERRED DATE,TRANSFERRED QTY\r\n";
//		$mailsubject = "BNI cargo transferred in last week\r\n";
		do {
			$output_file .= $Short_Term_Row['LOT_NUM'].",".
							$Short_Term_Row['LR_NUM'].",".
							$Short_Term_Row['THE_COMM'].",".
							$Short_Term_Row['THE_REC_NAME'].",".
							$Short_Term_Row['THE_FROM_NAME'].",".
							$Short_Term_Row['THE_TRANS'].",".
							$Short_Term_Row['QTY_CHANGE']."\r\n";
		} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$File=chunk_split(base64_encode($output_file));
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'BNIWKTRANS'";
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
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
//		$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
	$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";

	if($output_file != ""){
		$mailSubject = $email_row['SUBJECT'];
	//		$mailSubject = str_replace("_0_", $deleted_count, $mailSubject);
	//		$mailSubject = str_replace("_1_", $user, $mailSubject);

		$body = $email_row['NARRATIVE'];
	//		$body = str_replace("_2_", $user." has deleted the following Prebills:\r\n".$mail_details, $body);

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"BNITrans.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File;
		$Content.="\r\n";
	} else {
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= "No Transfers.";
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
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'BNIWKTRANS',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}
	
//		mail($mailTO, $mailsubject, $Content, $mailheaders);
//	}
?>