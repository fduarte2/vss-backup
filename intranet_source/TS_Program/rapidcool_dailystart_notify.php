<?

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $ED_cursor = ora_open($conn);

   $mod_cursor = ora_open($conn);

	// this script only activates once per day tops; only if it hasnt yet run today.
	$sql = "SELECT COUNT(*) THE_COUNT FROM JOB_QUEUE
			WHERE JOB_DESCRIPTION = 'FIRSTPLTRAPIDCOOL'
				AND TO_CHAR(DATE_JOB_COMPLETED, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] > 0){
		exit;
	}

	// if there is no rapidcooling, no need to send notification of it starting.
	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY
			WHERE TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY')
				AND SERVICE_CODE = '23'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] <= 0){
		exit;
	}

	// at this point, we want to send an email.
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'FIRSTPLTRAPIDCOOL'";
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
//	$mailheaders .= "Content-Type: text/html\r\n";

	$mailSubject = $email_row['SUBJECT'];

	$body = $email_row['NARRATIVE'];

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
					'FIRSTPLTRAPIDCOOL',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($mod_cursor, $sql);
		ora_exec($mod_cursor);
	}
?>