<?
/* I.Thomas, 10/23/13
*	Modifed from A. Walter's script for Kopke.  
*   This script creates emails of Nightly Clem position and sends them to specified recipients
*
*	This program expecting to be cron'ed just AFTER midnight.  
*
*	HD8847 and HD8850  Daily Clementine email for Canadian and US Cargo for Billing
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);
	$ED_cursor = ora_open($conn2);

	$print_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));
	$print_yesterday = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
	
	//All Clementine inhouse pallets
	$sql = "SELECT CP.COMMODITY_CODE THE_COMM, COMMODITY_NAME COMM_NAME, CT.ARRIVAL_NUM THE_ARRIVAL, NVL(VESSEL_NAME, 'TRUCKED IN') ARR_NAME, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, VESSEL_PROFILE VP WHERE DATE_RECEIVED IS NOT NULL AND CT.ARRIVAL_NUM != '4321' and QTY_IN_HOUSE > 10 AND CT.COMMODITY_CODE LIKE '560%' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND CT.ARRIVAL_NUM = VP.ARRIVAL_NUM (+) GROUP BY CP.COMMODITY_CODE,  COMMODITY_NAME, CT.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCKED IN') ORDER BY CP.COMMODITY_CODE, COMMODITY_NAME, CT.ARRIVAL_NUM";
	//$sql = "SELECT CP.COMMODITY_CODE THE_COMM, COMMODITY_NAME COMM_NAME, CT.ARRIVAL_NUM THE_ARRIVAL, NVL(VESSEL_NAME, 'TRUCKED IN') ARR_NAME, COUNT(*) THE_COUNT FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, VESSEL_PROFILE VP WHERE DATE_RECEIVED IS NOT NULL AND QTY_IN_HOUSE > 10 AND CT.COMMODITY_CODE LIKE '560%' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND CT.ARRIVAL_NUM = VP.ARRIVAL_NUM (+) GROUP BY CP.COMMODITY_CODE,  COMMODITY_NAME, CT.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCKED IN') ORDER BY CP.COMMODITY_CODE, COMMODITY_NAME, CT.ARRIVAL_NUM";

	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$output_1 = "No Pallets in Inventory at the start of ".$print_date;
	} else {
		$output_1 = "Pallets in House that have more than 10 cases for commodity codes 560X at the start of ".$print_date.".\r\n\r\n\r\n";
		$output_1 .= "COMMODITY CODE - COMMODITY NAME - ARRIVAL NUM - VESSEL NAME:  PALLET COUNT\r\n\r\n";
		do {
			$output_1 .= $row['THE_COMM']." - ".$row['COMM_NAME']." - ".$row['THE_ARRIVAL']." - ".$row['ARR_NAME'].": ".$row['THE_COUNT']."\r\n";
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'NIGHTLYCLEMPOSITION'";
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
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = $email_row['SUBJECT'];
	$mailSubject = str_replace("_0_", date('m/d/Y'), $mailSubject);

	$body = $email_row['NARRATIVE']."\r\n";
	$body .= $output_1;

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
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
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'NIGHTLYCLEMPOSITION',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
//		echo $sql."\r\n";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}

?>


