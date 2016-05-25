<?
/*
*	Sends out email notifications of the ships schedule.
*
*	Feb 2011.
****************************************************************************/

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);
   $short_term_cursor = ora_open($conn);
   $mod_cursor = ora_open($conn);


		
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'VSCHEDULE'";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

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


	$mailSubject = $email_row['SUBJECT'];

	$body = $email_row['NARRATIVE'];

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body."\r\n";

	$attachment = chunk_split(base64_encode(file_get_contents('/web/web_pages/ship_schedule/POWShipSchedule.pdf')));

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"ShipSchedule".$date.".pdf\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attachment;
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
					'CONSTANTCRON',
					SYSDATE,
					'EMAIL',
					'VSCHEDULE',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($mod_cursor, $sql);
		ora_exec($mod_cursor);
	}


/*	
	$mailsubject = "test Schedule Attachment\r\n";
	$mailTo = "ithomas@port.state.de.us";


	$mailheaders = "From: " . "NoReplies@POWWDI.com\r\n";
//	$mailheaders .= "Bcc: " . "lstewart@port.state.de.us\r\n";

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= "This is a Test of the Emergency Ship Schedule Attachment System.\r\nHad this been an actual emergency, I would have ended up writing this script at 4AM Sunday morning.\r\n";

	$attachment = chunk_split(base64_encode(file_get_contents('POWShipSchedule.pdf')));

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"ShipSchedule".$date.".pdf\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attachment;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";

	mail($mailTo, $mailsubject, $Content, $mailheaders); */
?>
