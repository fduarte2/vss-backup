<?
/* Adam Walter, 8/25/07
*	This script creates 4 files, and then mails them to specified recipients
*	For the Clementine System.
*
*	This program expecting to be cron'ed just AFTER midnight.  if not, check
*	The date variables.
*
*	11/1/12 HD7977.  We are changing the subject line to be more user-friendly..
*	And while we are at it, we are restructuring the whole program to use the RF
*	EMAIL_DISTRIBUTION method.  so.. if you see a lot of comments, that's why.
*	This also means that i will no longer be writing files to Intranet.
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
//	$ora_date = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
	$print_yesterday = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));



/*
	$filename1 = "Inventory".date('mdY').".txt";
	$handle = fopen($filename1, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info all-inventory FAILED", "See top", "From:  PoW Cron");
		exit;
	}
*/
	
	// file #1:  all inhouse pallets
	$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND ARRIVAL_NUM != '4321' AND COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND (MARK IS NULL OR MARK != 'SHIPPED') ORDER BY PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$output_1 = "No Pallets in Inventory as of ".$print_date;
//		fclose($handle);
	} else {
		$output_1 = "";
		do {
			$output_1 .= $row['PALLET_ID']."\r\n";
//			fwrite($handle, $row['PALLET_ID']."\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
//		fclose($handle);
	}


/*
	$filename2 = "RegradeHospital".date('mdY').".txt";
	$handle = fopen($filename2, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info hosp-inventory FAILED", "See top", "From:  PoW Cron");
		exit;
	}
*/
	// file #2:  all non-good pallets
	$sql = "SELECT PALLET_ID, DECODE(CARGO_STATUS, 'H', 'HOSPITAL', 'R', 'REGRADE', 'B', 'BOTH', 'ERROR') THE_STATUS FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND ARRIVAL_NUM != '4321' AND COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS IN ('H', 'B', 'R') ORDER BY PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		fwrite($handle, "No Pallets in Hospital Inventory as of ".$print_date);
		$output_2 = "No Pallets in Hospital Inventory as of ".$print_date;
//		fclose($handle);
	} else {
		$output_2 = "";
		do {
			$output_2 .= $row['PALLET_ID'].",".$row['THE_STATUS']."\r\n";
//			fwrite($handle, $row['PALLET_ID'].",".$row['THE_STATUS']."\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
//		fclose($handle);
	}


/*
	$filename3 = "Shipped".date('mdY', mktime(0,0,0,date('m'),date('d') - 1, date('Y'))).".txt";
	$handle = fopen($filename3, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info shipped FAILED", "See top", "From:  PoW Cron");
		exit;
	}
*/
	// file #3:  all shipped YESTERDAY pallets
	// edit:  the bottom SQL is if, for whatever reason, I need to send a file with shipouts for more than 1 day

/*//			AND SUBSTR(CA.ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
	$sql = "SELECT CT.PALLET_ID THE_PALLET, DECODE(CT.CARGO_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_STATUS, 
			TRIM(DCO.ORDERNUM) THE_ORDER, DCCON.CONSIGNEENAME THE_CONSIGNEE, 
			NVL(DCO.CUSTOMERPO, 'NO PO') THE_PO, DCO.LOADTYPE THE_LOADTYPE, DCCUST.CUSTOMERNAME THE_CUST
			FROM CARGO_TRACKING CT, DC_ORDER DCO, DC_CUSTOMER DCCUST, DC_CONSIGNEE DCCON, CARGO_ACTIVITY CA
			WHERE CT.PALLET_ID = CA.PALLET_ID
			AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
			AND LENGTH(CT.EXPORTER_CODE) = 4
			AND CT.COMMODITY_CODE LIKE '560%'
			AND CT.ARRIVAL_NUM != '4321' 
			AND CA.SERVICE_CODE = '6'
			AND CA.ACTIVITY_DESCRIPTION IS NULL
			AND CA.ORDER_NUM = TRIM(DCO.ORDERNUM)
			AND DCO.CUSTOMERID = DCCUST.CUSTOMERID
			AND DCO.CONSIGNEEID = DCCON.CONSIGNEEID
			AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$print_yesterday."'";*/

	$sql = "SELECT CT.PALLET_ID THE_PALLET, DECODE(CT.CARGO_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_STATUS,
				CA.ORDER_NUM THE_ORDER, NVL(DC_STUFF.CONSIGNEENAME, 'N/A') THE_CONSIGNEE,
				NVL(DC_STUFF.THE_PO, 'NO PO') THE_PO, NVL(DC_STUFF.THE_LOADTYPE, 'N/A') THE_LOADTYPE, NVL(DC_STUFF.THE_CUST, 'N/A') THE_CUST
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, (SELECT DCO.ORDERNUM, DCCON.CONSIGNEENAME, NVL(DCO.CUSTOMERPO, 'NO PO') THE_PO, 
														DCO.LOADTYPE THE_LOADTYPE, DCCUST.CUSTOMERNAME THE_CUST 
														FROM DC_ORDER DCO, DC_CUSTOMER DCCUST, DC_CONSIGNEE DCCON
														WHERE DCO.CUSTOMERID = DCCUST.CUSTOMERID
														AND DCO.CONSIGNEEID = DCCON.CONSIGNEEID) DC_STUFF
			WHERE CT.PALLET_ID = CA.PALLET_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND LENGTH(CT.EXPORTER_CODE) = 4
				AND CT.COMMODITY_CODE LIKE '560%'
				AND CT.ARRIVAL_NUM != '4321' 
				AND CA.SERVICE_CODE = '6'
				AND CA.ACTIVITY_DESCRIPTION IS NULL
				AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$print_yesterday."'
				AND CA.ORDER_NUM = TRIM(DC_STUFF.ORDERNUM(+))";
/*
	$sql = "SELECT CT.PALLET_ID THE_PALLET, DECODE(CT.CARGO_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_STATUS, 
			TRIM(DCO.ORDERNUM) THE_ORDER, DCCON.CONSIGNEENAME THE_CONSIGNEE, 
			NVL(DCO.CUSTOMERPO, 'NO PO') THE_PO, DCO.LOADTYPE THE_LOADTYPE, DCCUST.CUSTOMERNAME THE_CUST
			FROM CARGO_TRACKING CT, DC_ORDER DCO, DC_CUSTOMER DCCUST, DC_CONSIGNEE DCCON, CARGO_ACTIVITY CA
			WHERE CT.PALLET_ID = CA.PALLET_ID
			AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
			AND LENGTH(CT.EXPORTER_CODE) = 4
			AND CT.COMMODITY_CODE LIKE '560%'
			AND CT.ARRIVAL_NUM != '4321' 
			AND CA.SERVICE_CODE = '6'
			AND SUBSTR(CA.ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
			AND CA.ORDER_NUM = TRIM(DCO.ORDERNUM)
			AND DCO.CUSTOMERID = DCCUST.CUSTOMERID
			AND DCO.CONSIGNEEID = DCCON.CONSIGNEEID
			AND CT.PALLET_ID IN (
			'611124520034774060320170360028',
			'611124520034778040006680360028')";
*/
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$output_3 = "No Pallets shipped on ".$print_yesterday;
//		fwrite($handle, "No Pallets shipped on ".$print_yesterday);
//		fclose($handle);
	} else {
		$output_3 = "";
		do {
			$output_3 .= $row['THE_PALLET'].",".$row['THE_ORDER'].",".$row['THE_CUST'].",".$row['THE_CONSIGNEE'].",".$row['THE_LOADTYPE'].",".$row['THE_STATUS'].",".$row['THE_PO']."\r\n";
//			fwrite($handle, $row['THE_PALLET'].",".$row['THE_ORDER'].",".$row['THE_CUST'].",".$row['THE_CONSIGNEE'].",".$row['THE_LOADTYPE'].",".$row['THE_STATUS'].",".$row['THE_PO']."\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
//		fclose($handle);
	}

/*
	$filename4 = "Unloaded".date('mdY', mktime(0,0,0,date('m'),date('d') - 1, date('Y'))).".txt";
	$handle = fopen($filename4, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info unloaded FAILED", "See top", "From:  PoW Cron");
		exit;
	}
*/
	// file #4:  all unloaded YESTERDAY pallets
	$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$print_yesterday."' AND COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND ARRIVAL_NUM != '4321' AND (MARK IS NULL OR MARK != 'SHIPPED') ORDER BY PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$output_4 = "No Pallets Unloaded From Ship on ".$print_yesterday;
//		fwrite($handle, "No Pallets Unloaded From Ship on ".$print_yesterday);
//		fclose($handle);
	} else {
		$output_4 = "";
		do {
			$output_4 .= $row['PALLET_ID']."\r\n";
//			fwrite($handle, $row['PALLET_ID']."\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
//		fclose($handle);
	}
/*
//	$cmd = "echo \" \" | mutt -s \"Port records, ".$print_date."\" -a ".$filename1." -a ".$filename2." -a ".$filename3." -a ".$filename4."  awalter@port.state.de.us";

	$cmd = "echo \" \" | mutt -s \"Port records, ".$print_date."\" -a ".$filename1." -a ".$filename2." -a ".$filename3." -a ".$filename4." souhnoun@menara.ma h.gzour@freshfruitexport.com laila@transinformatique.com -c ithomas@port.state.de.us -c awalter@port.state.de.us -c martym@port.state.de.us -c ltreut@port.state.de.us -c hdadmin@port.state.de.us";
	system($cmd);
	
	system("/bin/mv $filename1 ~/ClemFiles/".$filename1);
	system("/bin/mv $filename2 ~/ClemFiles/".$filename2);
	system("/bin/mv $filename3 ~/ClemFiles/".$filename3);
	system("/bin/mv $filename4 ~/ClemFiles/".$filename4);
*/



	$File_1 = chunk_split(base64_encode($output_1));
	$File_2 = chunk_split(base64_encode($output_2));
	$File_3 = chunk_split(base64_encode($output_3));
	$File_4 = chunk_split(base64_encode($output_4));

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'CLEMPORTRECORDS'";
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

	$body = $email_row['NARRATIVE'];

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/html; name=\"Inventory".date('mdY').".txt\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File_1;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/html; name=\"RegradeHospital".date('mdY').".txt\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File_2;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/html; name=\"Shipped".date('mdY', mktime(0,0,0,date('m'),date('d') - 1, date('Y'))).".txt\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File_3;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/html; name=\"Unloaded".date('mdY', mktime(0,0,0,date('m'),date('d') - 1, date('Y'))).".txt\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File_4;
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
					'CLEMPORTRECORDS',
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


