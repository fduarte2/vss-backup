<?
/*
*	Adam Walter, Jun 2011.
*
*	Cron to "pre-transfer cargo" fro Argen Juice.
*
*	Runs every minute to read transfers from BNI, and try to 
*	Transfer them to RF.  Email sent at end, either with
*	Success message, or reason for failure.
*************************************************************************/

	$BNIconn = ora_logon("SAG_OWNER@BNI", "SAG");
	$RFconn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$BNIconn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
//	$RFconn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($RFconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($RFconn));
		exit;
	}
	if($BNIconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($BNIconn));
		exit;
	}

	$RF_modify_cursor = ora_open($RFconn);
	$RF_Short_Term_Cursor = ora_open($RFconn);
	$cursor_email = ora_open($RFconn);

	$BNI_modify_cursor = ora_open($BNIconn);
	$BNI_Selection_Cursor = ora_open($BNIconn);

	// select all juice-based, not-yet-RF-transferred
	// transfers from BNI

	// delivery_num < 0 makes sure we only get stransfers
	// to_char(owner_id) != deliver to makes sure we dont make entries for "transferring to itself"
	$sql = "SELECT CARGO_BOL, CARGO_MARK, OWNER_ID, DELIVER_TO, CARGO_MARK, CM.COMMODITY_CODE, DELIVERY_NUM, QTY_CHANGE, CM.LR_NUM, CT.LOT_NUM, CA.ACTIVITY_NUM,
				DATE_OF_ACTIVITY
			FROM CARGO_ACTIVITY CA, CARGO_ACTIVITY_EXT CAE, CARGO_DELIVERY CD, CARGO_TRACKING CT, CARGO_MANIFEST CM, COMMODITY_PROFILE CP
			WHERE CT.LOT_NUM = CM.CONTAINER_NUM
				AND CT.LOT_NUM = CA.LOT_NUM
				AND CA.LOT_NUM = CAE.LOT_NUM
				AND CA.ACTIVITY_NUM = CAE.ACTIVITY_NUM
				AND CA.LOT_NUM = CD.LOT_NUM
				AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
				AND CAE.TRANSFER IS NULL
				AND CD.DELIVERY_NUM < 0
				AND CA.SERVICE_CODE = '6120'
				AND TO_CHAR(OWNER_ID) != DELIVER_TO
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND CP.COMMODITY_TYPE = 'JUICE'";
//	echo $sql."\n";
	ora_parse($BNI_Selection_Cursor, $sql);
	ora_exec($BNI_Selection_Cursor);
	while(ora_fetch_into($BNI_Selection_Cursor, $BNI_select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$can_RFTRANS = true;

		$trans_lot = $BNI_select_row['LOT_NUM'];
		$trans_act = $BNI_select_row['ACTIVITY_NUM'];
		$trans_bol = $BNI_select_row['CARGO_BOL'];
		$trans_mark = strip_BNI_transfer_marks$BNI_select_row['CARGO_MARK']);
		$trans_from = $BNI_select_row['OWNER_ID'];
		$trans_to = $BNI_select_row['DELIVER_TO'];
		$trans_comm = $BNI_select_row['COMMODITY_CODE'];
		$trans_id = $BNI_select_row['DELIVERY_NUM'];
		$trans_count = $BNI_select_row['QTY_CHANGE'];
		$trans_ARV = $BNI_select_row['LR_NUM'];
		$trans_date = $BNI_select_row['DATE_OF_ACTIVITY'];
/*************************************************************************
*	ALL VALIDITY CHECKING IS BEING DISABLED
*
*	If a transfer passes mustard in BNI, --AND-- since no one
*	(customer or port) cares about individual barcodes in juice,
*	may as well jsut take any transfer request and
*	pass it straight through
**************************************************************************
		$sql = "SELECT SUM(QTY_IN_HOUSE) THE_IH
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$trans_ARV."'
					AND COMMODITY_CODE = '".$trans_comm."'
					AND RECEIVER_ID = '".$trans_from."'
					AND BOL = '".$trans_bol."'
					AND '".$trans_mark."' LIKE CARGO_DESCRIPTION";
		ora_parse($RF_Short_Term_Cursor, $sql);
		ora_exec($RF_Short_Term_Cursor);
		ora_fetch_into($RF_Short_Term_Cursor, $RF_Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$IH = $RF_Short_Term_Row['THE_IH'];
//		echo $sql."\n";
//		if($RF_Short_Term_Row['THE_IH'] < $trans_count){
//			$can_RFTRANS = false;
//		}
		$sql = "SELECT SUM(QTY_LEFT_TO_TRANS) STILL_NEEDED
				FROM ARGJUICE_TRANSFERS
				WHERE CUSTOMER_FROM = '".$trans_from."'
					AND COMMODITY_CODE = '".$trans_comm."'
					AND ARRIVAL_NUM = '".$trans_ARV."'
					AND BOL = '".$trans_bol."'
					AND CARGO_DESCRIPTION = '".$trans_mark."'";
		ora_parse($RF_Short_Term_Cursor, $sql);
		ora_exec($RF_Short_Term_Cursor);
		ora_fetch_into($RF_Short_Term_Cursor, $RF_Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$still_needed = $RF_Short_Term_Row['STILL_NEEDED'];

		$sql = "SELECT SUM(QTY_LEFT_TO_TRANS) TRANS_IN_AVAILABLE
				FROM ARGJUICE_TRANSFERS
				WHERE CUSTOMER_TO = '".$trans_from."'
					AND COMMODITY_CODE = '".$trans_comm."'
					AND ARRIVAL_NUM = '".$trans_ARV."'
					AND BOL = '".$trans_bol."'
					AND CARGO_DESCRIPTION = '".$trans_mark."'";
		ora_parse($RF_Short_Term_Cursor, $sql);
		ora_exec($RF_Short_Term_Cursor);
		ora_fetch_into($RF_Short_Term_Cursor, $RF_Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$trans_in_available = $RF_Short_Term_Row['TRANS_IN_AVAILABLE'];

		$total_avail = ($IH + $trans_in_available) - $still_needed;

		if($total_avail < $trans_count){
			$can_RFTRANS = false;
		}

		if($can_RFTRANS){ */

		$sql = "INSERT INTO ARGJUICE_TRANSFERS
					(ARRIVAL_NUM,
					COMMODITY_CODE,
					CUSTOMER_FROM,
					CUSTOMER_TO,
					TRANSFER_NUM,
					BOL,
					CARGO_DESCRIPTION,
					QTY_TO_TRANS,
					QTY_LEFT_TO_TRANS,
					ACTUAL_DATE,
					INSERT_BY,
					INSERT_ON)
				VALUES
					('".$trans_ARV."',
					'".$trans_comm."',
					'".$trans_from."',
					'".$trans_to."',
					'".$trans_id."',
					'".$trans_bol."',
					'".$trans_mark."',
					'".$trans_count."',
					'".$trans_count."',
					'".$trans_date."',
					'CRON',
					SYSDATE)";
		echo $sql."\n";
		ora_parse($RF_modify_cursor, $sql);
		ora_exec($RF_modify_cursor);

		$sql = "UPDATE CARGO_ACTIVITY_EXT
				SET TRANSFER = 'Y'
				WHERE LOT_NUM = '".$trans_lot."'
					AND ACTIVITY_NUM = '".$trans_act."'";
		ora_parse($BNI_modify_cursor, $sql);
		ora_exec($BNI_modify_cursor);

		// send out the email.
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'JUICETRANSYES'";
		ora_parse($cursor_email, $sql);
		ora_exec($cursor_email);
		ora_fetch_into($cursor_email, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailTO = $email_row['TO'];
		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
//			$mailheaders .= "Content-Type: text/html\r\n";
		$body = $email_row['NARRATIVE'];
		$body = str_replace("_0_", "\r\n\r\nVessel:  ".$trans_ARV."\r\nFrom Customer:  ".$trans_from."\r\nTo Customer:  ".$trans_to."\r\nBoL:  ".$trans_bol."\r\nMark:  ".$trans_mark."\r\nNumber to Allow Scanner To Transfer:  ".$trans_count, $body);

		$mailSubject = $email_row['SUBJECT'];
//			echo $trans_id."   ".$mailSubject."\n";
		$mailSubject = str_replace("_1_", $trans_id, $mailSubject);
//			echo $trans_bol."   ".$mailSubject."\n";
		$mailSubject = str_replace("_2_", $trans_bol, $mailSubject);

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
						'CONSTANTCRON',
						SYSDATE,
						'EMAIL',
						'JUICETRANSYES',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($RF_modify_cursor, $sql);
			ora_exec($RF_modify_cursor);
		}
/*			}

		} else {
			$sql = "UPDATE CARGO_ACTIVITY_EXT
					SET TRANSFER = 'F'
					WHERE LOT_NUM = '".$trans_lot."'
						AND ACTIVITY_NUM = '".$trans_act."'";
			ora_parse($BNI_modify_cursor, $sql);
			ora_exec($BNI_modify_cursor);

			// send out the email.
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'JUICETRANSNO'";
			ora_parse($cursor_email, $sql);
			ora_exec($cursor_email);
			ora_fetch_into($cursor_email, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$mailTO = $email_row['TO'];
			$mailheaders = "From: ".$email_row['FROM']."\r\n";
			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
//			$mailheaders .= "Content-Type: text/html\r\n";
			$body = $email_row['NARRATIVE'];
			$body = str_replace("_0_", "\r\n\r\nVessel:  ".$trans_ARV."\r\nFrom Customer:  ".$trans_from."\r\nTo Customer:  ".$trans_to."\r\nBoL:  ".$trans_bol."\r\nMark:  ".$trans_mark."\r\n", $body);

			$mailSubject = $email_row['SUBJECT'];
			$mailSubject = str_replace("_1_", $trans_id, $mailSubject);
			$mailSubject = str_replace("_2_", $trans_bol, $mailSubject);

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
							'CONSTANTCRON',
							SYSDATE,
							'EMAIL',
							'JUICETRANSNO',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($RF_modify_cursor, $sql);
				ora_exec($RF_modify_cursor);
			} 
		} */
	}









function strip_BNI_transfer_marks($mark){
// gets rid of thsoe god-awful TR* indicators from BNI
	$check = strpos($mark, "TR*");
	if($check === false){
		return $mark;
	} else {
		return substr($mark, 0, $check);
	}
}

?>