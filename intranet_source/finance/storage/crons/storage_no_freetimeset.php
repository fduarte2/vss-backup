<?
/*
*	Adam Walter, Jan 2010
*
*	This sends an email (to be run daily, early-morning) that reports to
*	Finance-based individuals any BNI ships that have not yet
*	Had their free time set (thereby preventing autobilling)
*****************************************************************************/

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  $Short_Term_Cursor = ora_open($bni_conn);

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($rf_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);
  $Short_Term_Cursor_RF = ora_open($rf_conn);


	$mailTO = "billing@port.state.de.us,sshoemaker@port.state.de.us,vfarkas@port.state.de.us,philhower@port.state.de.us\r\n";
	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	
/*
	$sql = "SELECT * FROM VESSEL_PROFILE VP, VOYAGE VOY
			WHERE VOY.LR_NUM = VP.LR_NUM
			AND VOY.FREE_TIME_START IS NULL
			AND VOY.DATE_ARRIVED IS NOT NULL
			AND VOY.DATE_ARRIVED > '01-jan-2010'
			AND VP.LR_NUM IN
			(SELECT LR_NUM FROM CARGO_MANIFEST CM, CARGO_TRACKING CT
			WHERE CM.CONTAINER_NUM = CT.LOT_NUM
			AND CT.DATE_RECEIVED IS NOT NULL)
			ORDER BY VESSEL_NAME";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor, $sql);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailsubject = "---NO--- BNI vessels awaiting freetime entry\r\n";
		mail($mailTO, $mailsubject, "no BNI vessels awaiting freetime entry\r\n", $mailheaders);
	} else {
		$body = "";
		$mailsubject = "BNI vessels awaiting freetime entry\r\n";
		do {
			$body .= $row['VESSEL_NAME']."\r\n";
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		mail($mailTO, $mailsubject, $body, $mailheaders);
	}
*/

	$free_time_counter = 0;
//	$body = "";
	$scanned_list = "\r\n";
	$unscanned_list = "\r\n";

	$sql = "SELECT * FROM VESSEL_PROFILE VP, VOYAGE VOY
			WHERE VOY.LR_NUM = VP.LR_NUM
			AND VP.LR_NUM != '4321'
			AND VOY.FREE_TIME_START IS NULL
			AND VOY.DATE_ARRIVED IS NOT NULL
			AND VOY.DATE_ARRIVED > '01-jan-2010'
			AND VP.LR_NUM IN
			(SELECT LR_NUM FROM CARGO_MANIFEST CM, CARGO_TRACKING CT
			WHERE CM.CONTAINER_NUM = CT.LOT_NUM
			AND CT.DATE_RECEIVED IS NOT NULL)
			ORDER BY VESSEL_NAME";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor, $sql);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		$body .= "---NO--- Unscanned Vessels\r\n";
		$unscanned_list .= "\t---NONE---\r\n";
	} else {
//		$body .= "Unscanned Vessels:\r\n";
		do {
			$unscanned_list .= "\t".$row['VESSEL_NAME']."\r\n";
			$free_time_counter++;
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

	}

	$sql = "SELECT VP.LR_NUM, VP.VESSEL_NAME
			FROM VESSEL_PROFILE VP, VOYAGE_FROM_BNI VOY
			WHERE VOY.FREE_TIME_START IS NULL
			AND VP.LR_NUM != '4321'
			AND VP.LR_NUM = VOY.LR_NUM
			AND TO_CHAR(VP.LR_NUM) IN
				(SELECT DISTINCT ARRIVAL_NUM
				FROM CARGO_TRACKING
				WHERE DATE_RECEIVED IS NOT NULL
				AND DATE_RECEIVED > '01-oct-2011'
				AND RECEIVING_TYPE = 'S'
				AND COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE
					WHERE COMMODITY_TYPE IN ('CHILEAN', 'CLEMENTINES', 'STEEL'))
				)
			ORDER BY VESSEL_NAME";
	ora_parse($Short_Term_Cursor_RF, $sql);
	ora_exec($Short_Term_Cursor_RF, $sql);
	if(!ora_fetch_into($Short_Term_Cursor_RF, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		$body .= "---NO--- Scanned Vessels\r\n";
		$scanned_list .= "\t---NONE---\r\n";
	} else {
//		$body .= "\r\nScanned Vessels:\r\n";
		do {
			$scanned_list .= "\t".$row['LR_NUM']."-".$row['VESSEL_NAME']."\r\n";
			$free_time_counter++;
		} while(ora_fetch_into($Short_Term_Cursor_RF, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

	} 
//	$mailsubject = "Vessels awaiting Free Time:  ".$free_time_counter."\r\n";

//	mail($mailTO, $mailsubject, $body, $mailheaders);

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'VSLSNEEDFREETIME'";
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
	$mailSubject = str_replace("_0_", $free_time_counter, $mailSubject);
//	$mailSubject = str_replace("_1_", $user, $mailSubject);

	$body = $email_row['NARRATIVE'];
	$body = str_replace("_1_", $unscanned_list, $body);
	$body = str_replace("_2_", $scanned_list, $body);

	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
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
					'VSLSNEEDFREETIME',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}

?>