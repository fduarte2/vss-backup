<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION IN ('WAREHOUSEHPLTCT')
				AND COMPLETION_STATUS = 'PENDING'
				AND JOB_TYPE = 'EMAIL'
				AND SUBMISSION_DATETIME + (RUN_DELAY_MINUTES / 1440) < SYSDATE";
	$queue = ociparse($rfconn, $sql);
	ociexecute($queue);
	while(ocifetch($queue)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'WAREHOUSEHPLTCT'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$varlist = explode(";", ociresult($queue, "VARIABLE_LIST"));
		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", $varlist[0], $mailSubject);
		$mailSubject = str_replace("_1_", $varlist[1], $mailSubject);

		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_2_", $varlist[2], $body);


		
		$mailTO = ociresult($email, "TO");
//		echo "mailto: ".$mailTO."\n";
		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";

		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC");
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC");
		}
//		echo $mailheaders;
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		if(file_exists($varlist[3])){
			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;
			$Content.="\r\n";

			$attach = chunk_split(base64_encode(file_get_contents($varlist[3])));
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/text; name=\"".$varlist[3]."\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";
		} else {
			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= "The bill has been generated; The backup file can be obtained from Eportwilmington, on the same page as was used for uploading.";
			$Content.="\r\n";
		}

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE SET
						DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETED'
					WHERE
						JOB_ID = '".ociresult($queue, "JOB_ID")."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		// file not copied yet, do nothing, email will wait
		}
	}
	