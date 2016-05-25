<?
/*
*	Aug/Sep 2011
*
*	This is part of the new (as of 2011) rf-storage system.
*	This file checks for any pallets marked as "unbillable" by
*	The RF-auto-storage programs and notifies Finance about them.
*
*	... Constantly.
*****************************************************************************/

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  // open cursors
  $cursor = ora_open($conn);
  $ED_cursor = ora_open($conn);

	$notify_list = "";

	// first, vessels.  FREE_TIME_START comes from the BNI-table VOYAGE, which is "viewed" in RF.
	$sql = "SELECT PALLET_ID, ARRIVAL_NUM, RECEIVER_ID
			FROM CARGO_TRACKING
			WHERE DATE_RECEIVED IS NOT NULL
				AND BILL = 'X'
			ORDER BY PALLET_ID, ARRIVAL_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no bad pallets at all.  End sscript.
	} else {
		do {
			$notify_list .= $row['PALLET_ID']."  (LR#".$row['ARRIVAL_NUM']."  Cust#".$row['RECEIVER_ID'].")\r\n";
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'RFSTORAGEFIX'";
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
		
		$body = str_replace("_0_", "\r\n\r\n".(0 + $total_pallets_set)."\r\n\r\n", $body);
		$body = str_replace("_1_", $notify_list, $body);

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
						'RFSTORAGEFIX',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		}
	}
