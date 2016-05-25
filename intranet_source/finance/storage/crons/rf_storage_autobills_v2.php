<?
/*
*	Adam Walter, May/June 2012
*
*	V2 of the Rf-storage nightly script.  
*	Compared to V1, Billing date is no longer a qualifier, just a filter.
*
***********************************************************************************************/

//	include("./storage_bills_functions.php"); // TEST LOCATION
	include("storage_bills_functions.php");

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$modify_cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);

	echo "Starting RF autobill routine\n";
	$one_day_buffer = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 2, date('Y')));

//	$message = Make_RF_Prebills($conn, "", "", "", "", "", "", "", "");
	$message = Make_RF_Prebills($conn, "", "", "", "", $one_day_buffer, "", "", "");
//		echo $message;
	if($message == ""){
		$message = "No Pallets to Bill.";
	}
	$message = Get_display($message, "text");
	echo $message;

	// MAIL GOES HERE
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'RFAUTOSTORAGE'";
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

	$mailSubject = str_replace("_0_", date('m/d/Y'), $mailSubject);
	
	$body = str_replace("_1_", "\r\n\r\n".$message."\r\n\r\n", $body);

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
					'RFAUTOSTORAGE',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($modify_cursor, $sql);
		ora_exec($modify_cursor);
	}

?>