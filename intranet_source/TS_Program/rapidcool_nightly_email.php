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

	$sql = "SELECT PALLET_ID, CUSTOMER_NAME, ARRIVAL_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH:MI AM') THE_TIME, 
				QTY_LEFT, ACTIVITY_DESCRIPTION, DATE_OF_ACTIVITY, FROM_LOCATION
			FROM CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP
			WHERE SERVICE_CODE = '23'
				AND CA.CUSTOMER_ID = CP.CUSTOMER_ID
				AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 1, 'MM/DD/YYYY')
				AND ACTIVITY_DESCRIPTION LIKE '%CR%'
				AND (DATE_OF_ACTIVITY) = 
					(SELECT MIN(DATE_OF_ACTIVITY)
							FROM CARGO_ACTIVITY CA2
							WHERE CA2.PALLET_ID = CA.PALLET_ID
								  AND CA2.ARRIVAL_NUM = CA.ARRIVAL_NUM
								  AND CA2.CUSTOMER_ID = CA.CUSTOMER_ID
								  AND CA2.SERVICE_CODE = '23'
								  AND TO_CHAR(CA2.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 1, 'MM/DD/YYYY'))
			ORDER BY ACTIVITY_DESCRIPTION, DATE_OF_ACTIVITY";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// end script
	} else {
		$plts = 0;
		$email_body_replace = "Barcode;Customer;LR;Transfer Time;Description;Qty;From Location\n";
		$altered_pallets = 0;
		do {
			$email_body_replace .= $row['PALLET_ID'].";".
										$row['CUSTOMER_NAME'].";".
										$row['ARRIVAL_NUM'].";".
										$row['THE_TIME'].";".
										$row['ACTIVITY_DESCRIPTION'].";".
										$row['QTY_LEFT'].";".
										$row['FROM_LOCATION']."\n";
			$plts++;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		
		$email_body_replace .= "Total Plts:  ".$plts."\n";

		$txt_attach=chunk_split(base64_encode($email_body_replace));

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'XFERRAPIDCOOL'";
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
//		$mailheaders .= "Content-Type: text/html\r\n";
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE']."\r\n";

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"Rapidcool_".$date.".txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$txt_attach;
		$Content.="\r\n";
		$Content.="--MIME_BOUNDRY--\n";

		$mailSubject = str_replace("_0_", $plts, $mailSubject);
//		$body = str_replace("_1_", $email_body_replace, $body);

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
						'XFERRAPIDCOOL',
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
?>