<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION = 'DOLEFRIBPALLET'
				AND COMPLETION_STATUS = 'PENDING'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	while(ocifetch($email)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEFRIBPALLET'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$mailSubject = ociresult($short_term_data, "SUBJECT");
		$mailheaders = "From: ".ociresult($short_term_data, "FROM")."\r\n";

		$mailTO = ociresult($email, "JOB_EMAIL_TO");
		$mailheaders .= "Cc: ".ociresult($email, "JOB_EMAIL_CC")."\r\n";
		$mailheaders .= "Bcc: ".ociresult($email, "JOB_EMAIL_BCC")."\r\n";
		$body = ociresult($email, "JOB_BODY");
		$ID = ociresult($email, "JOB_ID");
		$vartemp = ociresult($email, "VARIABLE_LIST");
		$varlist = explode(";", $vartemp);


		$mailSubject = str_replace("_0_", $varlist[0], $mailSubject);
		$mailSubject = str_replace("_1_", $varlist[1], $mailSubject);
		$mailSubject = str_replace("_2_", $varlist[2], $mailSubject);
		$mailSubject = str_replace("_3_", $varlist[3], $mailSubject);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE
					SET COMPLETION_STATUS = 'COMPLETED'
					WHERE JOB_ID = '".$ID."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}
	}

	$sql = "SELECT * FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION = 'DOLEFROBPALLET'
				AND COMPLETION_STATUS = 'PENDING'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	while(ocifetch($email)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEFROBPALLET'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$mailSubject = ociresult($short_term_data, "SUBJECT");
		$mailheaders = "From: ".ociresult($short_term_data, "FROM")."\r\n";

		$mailTO = ociresult($email, "JOB_EMAIL_TO");
		$mailheaders .= "Cc: ".ociresult($email, "JOB_EMAIL_CC")."\r\n";
		$mailheaders .= "Bcc: ".ociresult($email, "JOB_EMAIL_BCC")."\r\n";
		$body = ociresult($email, "JOB_BODY");
		$ID = ociresult($email, "JOB_ID");
		$vartemp = ociresult($email, "VARIABLE_LIST");
		$varlist = explode(";", $vartemp);

		$mailSubject = str_replace("_0_", $varlist[0], $mailSubject);
		$mailSubject = str_replace("_1_", $varlist[1], $mailSubject);
		$mailSubject = str_replace("_2_", $varlist[2], $mailSubject);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE
					SET COMPLETION_STATUS = 'COMPLETED'
					WHERE JOB_ID = '".$ID."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}
	}
