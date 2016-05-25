<?
/* Adam Walter, 3/10/08
*
*	This file creates a .csv file that is sent to Kopke customer 1131
*	Showing their in-house inventory as of the time of it's running.
*********************************************************************************/
/*
	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);
*/
//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");
/*
	$filename1 = "/web/web_pages/TS_Program/kopke_email/pltsAtPOW".date('mdY').".csv";
	$handle = fopen($filename1, "w");
	if (!$handle){
//		echo "could not create kopke file";
		mail("awalter@port.state.de.us,archive@port.state.de.us", "Kopke daily info all-inventory FAILED", "See top", "From:  PoW Cron");
		exit;
	}
*/

	$print_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));

//CT.WAREHOUSE_LOCATION, NVL(CTAD.ORIGINAL_WAREHOUSE_LOC, CT.WAREHOUSE_LOCATION) ORIG_LOC,
	$sql = "SELECT CT.PALLET_ID, TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE, CT.QTY_RECEIVED, CT.QTY_IN_HOUSE, 
				DECODE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '".$print_date."', NVL(CTAD.ORIGINAL_WAREHOUSE_LOC, CT.WAREHOUSE_LOCATION), CT.WAREHOUSE_LOCATION) REPORTED_LOCATION
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.DATE_RECEIVED IS NOT NULL 
				AND CT.QTY_IN_HOUSE >= 
					(SELECT NVL(QTY_THRESHOLD, 1)
					FROM MINIMUM_INHOUSE_THRESHOLD MIT, COMMODITY_PROFILE CP
					WHERE MIT.COMMODITY_TYPE = CP.COMMODITY_TYPE
					AND CP.COMMODITY_CODE = CT.COMMODITY_CODE)
				AND CT.RECEIVER_ID = '1131' 
				AND CT.ARRIVAL_NUM != '4321' 
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				ORDER BY CT.PALLET_ID";
	$lines = ociparse($rf_conn, $sql);
	ociexecute($lines);
	if(!ocifetch($lines)){
//		fwrite($handle, "No Pallets in Inventory as of ".$print_date);
		$output = "No Pallets in Inventory as of ".$print_date;
	} else {
		$output = "";
		do {
//			fwrite($handle, $row['PALLET_ID'].",".$row['QTY_IN_HOUSE'].",".$row['WAREHOUSE_LOCATION']."\n");
//			fwrite($handle, $row['PALLET_ID'].",".$row['THE_DATE'].",".$row['QTY_RECEIVED'].",".$row['QTY_IN_HOUSE']."\n");
//			$output .= ociresult($lines, "PALLET_ID").",".ociresult($lines, "QTY_IN_HOUSE").",".ociresult($lines, "WAREHOUSE_LOCATION")."\n";
			$output .= ociresult($lines, "PALLET_ID").",".ociresult($lines, "QTY_IN_HOUSE").",".ociresult($lines, "REPORTED_LOCATION")."\n";
		} while(ocifetch($lines));
	}
//	fclose($handle);

//	echo $output;
	$csv_attach=chunk_split(base64_encode($output));


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'KOPKEINVEMAIL'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $print_date, $mailSubject);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "lstewart@port.state.de.us";
//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
		$mailheaders .= "Cc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
		$mailSubject = "THIS IS A TEST - PLEASE IGNORE ".$mailSubject;
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}

	$body = ociresult($email, "NARRATIVE");
//	$body = str_replace("_1_", $replace_body, $body);

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
	$Content.= $body;
	$Content.="\r\n";
//	$filename = "pltsAtPOW".date('mdY').".csv";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"PltsAtPOW".date('mdY').".csv\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$csv_attach;
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
					'KOPKEINVEMAIL',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
	}


//	$cmd = "echo \" \" | mutt -s \"Port inventory, end of day ".$print_date."\" -a ".$filename1." awalter@port.state.de.us ithomas@port.state.de.us";

//	$cmd = "echo \" \" | mutt -s \"Port inventory, end of day ".$print_date."\" -a ".$filename1." powinv@kopkefruit.com -c bdempsey@port.state.de.us -b sadu@port.state.de.us -b hdadmin@port.state.de.us -b awalter@port.state.de.us";
//	$cmd = "echo \" \" | mutt -s \"Port inventory, end of day ".$print_date."\" -a ".$filename1." powinv@kopkefruit.com -c schapman@port.state.de.us -c pcutler@port.state.de.us -c martym@port.state.de.us -b sadu@port.state.de.us -b archive@port.state.de.us -b awalter@port.state.de.us -b lstewart@port.state.de.us -b ixthomas@gmail.com";
//	system($cmd);

?>