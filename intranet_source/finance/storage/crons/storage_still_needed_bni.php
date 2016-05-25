<?
/*
*	Adam Walter, Jan 2010
*
*	This sends an email (to be run daily, early-morning) that reports to
*	Finance-based individuals any BNI cargo that are awaiting billing
*	Should be cronned AFTER the nightly storage run.
*****************************************************************************/

	$rfconn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$rfconn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rfconn));
		exit;
	}
	$ED_cursor = ora_open($rfconn);

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }

  $cursor = ora_open($bni_conn);
  $Short_Term_Cursor = ora_open($bni_conn);


//	$mailTO = "billing@port.state.de.us,sshoemaker@port.state.de.us,vfarkas@port.state.de.us,philhower@port.state.de.us\r\n";
	$mailTO = "awalter@port.state.de.us\r\n";
	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "martym@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	

/*
				(QTY_IN_HOUSE + (SELECT SUM(QTY_CHANGE) THE_CHANGE 
								FROM CARGO_ACTIVITY CA 
								WHERE CA.LOT_NUM = CT.LOT_NUM 
								AND CA.DATE_OF_ACTIVITY > GREATEST(CT.FREE_TIME_END, CT.STORAGE_END)))
				> 0
*/
	$sql = "SELECT CM.COMMODITY_CODE THE_COMM, VP.VESSEL_NAME THE_VES, CM.LR_NUM, CP1.CUSTOMER_NAME THE_OWNER, OWNER_ID, CP2.CUSTOMER_NAME BILL_TO, 
					CONTAINER_NUM, GREATEST(FREE_TIME_END, STORAGE_END), TO_CHAR(STORAGE_END + 1, 'MM/DD/YYYY') NEXT_STORAGE, WAREHOUSE_LOCATION,
					TO_CHAR(VOY.FREE_TIME_START, 'MM/DD/YYYY') THE_FREE_START, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_REC, CARGO_BOL
			FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, VESSEL_PROFILE VP, CUSTOMER_PROFILE CP1, CUSTOMER_PROFILE CP2, VOYAGE VOY WHERE
			CM.LR_NUM = VP.LR_NUM AND
			CM.LR_NUM = VOY.LR_NUM AND 
			CT.OWNER_ID = CP1.CUSTOMER_ID AND
			CT.STORAGE_CUST_ID = CP2.CUSTOMER_ID(+) AND
			CT.LOT_NUM = CM.CONTAINER_NUM AND
			CT.DATE_RECEIVED IS NOT NULL AND
			CT.OWNER_ID NOT IN ('312', '1') AND
			QTY_IN_HOUSE > 0 AND
			GREATEST(FREE_TIME_END, STORAGE_END) < SYSDATE
			AND TO_DATE(TO_CHAR(STORAGE_END + 1, 'MM/DD/YYYY'), 'MM/DD/YYYY') < SYSDATE
			ORDER BY GREATEST(FREE_TIME_END, STORAGE_END)";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailsubject = "---NO--- BNI cargo awaiting storage run\r\n";
		mail($mailTO, $mailsubject, "no BNI cargo awaiting bills\r\n", $mailheaders);
	} else {
//		$output_file = "LOT,OWNER,BILL TO,VESSEL,COMM,RECEIVED,FREE TIME START,NEXT STORAGE START\r\n";
		$output_file = "LOT,OWNER,BoL,VESSEL,COMM,RECEIVED,FREE TIME START,NEXT STORAGE START\r\n";
		$mailsubject = "BNI cargo awaiting storage run\r\n";
		do {
/*			$sql = "SELECT NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY'), 'NONE') THE_TRANS
					FROM CARGO_ACTIVITY 
					WHERE LOT_NUM = '".$row['CONTAINER_NUM']."'
					AND SERVICE_CODE = '6120'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor, $sql);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
*/
			if(HasRate($row, $rfconn, $bni_conn)){
				$output_file .= $row['CONTAINER_NUM'].",".
								$row['THE_OWNER'].",".
	//							$row['BILL_TO'].",".
								$row['CARGO_BOL'].",".
								$row['THE_VES'].",".
								$row['THE_COMM'].",".
								$row['THE_REC'].",".
	//							$short_term_row['THE_TRANS'].",".
								$row['THE_FREE_START'].",".
								$row['NEXT_STORAGE']."\r\n";
			}
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$File=chunk_split(base64_encode($output_file));

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'BNISTNEEDED'";
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

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"BNINeedsStorage.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File;
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
						'BNISTNEEDED',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
		}
	}



function HasRate($data, $rfconn, $bni_conn){
	$cursor = ora_open($bni_conn);

	$sql = "SELECT * FROM RATE 
			WHERE (CUSTOMERID = '".$data['OWNER_ID']."' OR CUSTOMERID IS NULL) AND
				(COMMODITYCODE = '".$data['THE_COMM']."' OR COMMODITYCODE IS NULL) AND
				(ARRIVALNUMBER = '".$data['LR_NUM']."' OR ARRIVALNUMBER IS NULL) AND
				(WAREHOUSE = '".substr($data['WAREHOUSE_LOCATION'], 0, 1)."' OR WAREHOUSE IS NULL) AND
				SCANNEDORUNSCANNED = 'UNSCANNED'
			ORDER BY RATEPRIORITY ASC, RATESTARTDATE DESC";
	echo $sql."\n";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// NO RATE FOUND.
		// send back message to PUT this in the email for finance to look at.
		return true;
	} else {
		do {
			if($row['RATE'] != 0){
				// this lot has a rate, send back a message to PUT this in the email for finance to look at
				return true;
			}
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		// if we get here, rates exist, but they are all 0, we should NOT put this in the email.
		return false;
	}

	// if we get HERE, then this function broke spectacularly.  send back the lot in the email; better safe than sorry.
	return true;
}