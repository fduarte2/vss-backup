<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION IN ('CANHOLDAMS', 'CANHOLDOHL', 'CANHOLDLINE', 'CANMODECHANGE')
				AND COMPLETION_STATUS = 'PENDING'
				AND JOB_TYPE = 'EMAIL'";
	$queue = ociparse($rfconn, $sql);
	ociexecute($queue);
	while(ocifetch($queue)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = '".ociresult($queue, "JOB_DESCRIPTION")."'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailTO = ociresult($queue, "JOB_EMAIL_TO");
		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";

		if(ociresult($queue, "JOB_EMAIL_CC") != ""){
			$mailheaders .= "Cc: ".ociresult($queue, "JOB_EMAIL_CC");
		}
		if(ociresult($queue, "JOB_EMAIL_BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($queue, "JOB_EMAIL_BCC");
		}
//		echo $mailheaders;

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", ociresult($queue, "VARIABLE_LIST"), $mailSubject);

		$body = ociresult($queue, "JOB_BODY");

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE SET
						DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETED'
					WHERE
						JOB_ID = '".ociresult($queue, "JOB_ID")."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}
	}
	