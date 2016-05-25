<?
/* Adam Walter, March 2008
*
*
*
*	This program is another automated email to kopke; in this one, a list of
*	pallets that were charged storge.  
*	Expected Daily run.
*************************************************************************************/

//   $conn_rf = ora_logon("SAG_OWNER@RF.TEST", "RFOWNER");
/*   $conn_rf = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn_rf < 1){
        printf("Error logging on to the RF Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   $cursor = ora_open($conn_rf); */

//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	if($rf_conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$print_date_start = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));
//	$print_date_start = date('m/d/Y', mktime(0,0,0,10,1,2007));
//	$print_date_end = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + 1, date('Y')));


/*
	$filename1 = "/web/web_pages/TS_Program/kopke_email/StorageOn".date('mdY').".csv";
//	$filename1 = "/web/web_pages/TS_Program/kopke_email/Storage04252008to05042008.csv";
	$handle = fopen($filename1, "w");
	if (!$handle){
//		echo "could not create kopke file";
		mail("awalter@port.state.de.us", "Kopke daily info storage FAILED", "See top", "From:  PoW Cron");
		exit;
	}
*/
	$filename = "StorageOn".date('mdY').".csv";

//			AND RB.INVOICE_DATE >= TO_DATE('".$print_date_start."', 'MM/DD/YYYY')
	$sql = "SELECT CT.PALLET_ID THE_PALLET, RBD.SERVICE_QTY THE_SERVICE, RB.INVOICE_NUM THE_INVOICE, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE, TO_CHAR(RB.SERVICE_START, 'MM/DD/YYYY') THE_SERV FROM
			CARGO_TRACKING CT, RF_BILLING RB, RF_BILLING_DETAIL RBD
			WHERE CT.PALLET_ID = RBD.PALLET_ID
			AND CT.ARRIVAL_NUM = RBD.ARRIVAL_NUM
			AND CT.RECEIVER_ID = RBD.CUSTOMER_ID
			AND CT.ARRIVAL_NUM = RB.ARRIVAL_NUM
			AND CT.RECEIVER_ID = RB.CUSTOMER_ID
			AND RBD.SUM_BILL_NUM = RB.BILLING_NUM
			AND CT.RECEIVER_ID = '1131'
			AND RB.EXPORT_FILE IS NULL
			AND TRIM(RB.SERVICE_STATUS) = 'INVOICED'
			ORDER BY CT.PALLET_ID, DATE_RECEIVED";
	$lines = ociparse($rf_conn, $sql);
	ociexecute($lines);
//	echo $sql;

	if(!ocifetch($lines)){
//		fwrite($handle, "No storage invoices were generated on ".$print_date_start);
		$output = "No storage invoices were generated on ".$print_date_start;
		exit;
	} else {
//		fwrite($handle, "The following storage invoices were generated on ".$print_date_start.". Pallets are listed as shown:\n");
//		fwrite($handle, "INVOICE#,PALLET ID,DATE RECEIVED,QTY,BILL START DATE\n");
		$output = "INVOICE#,PALLET ID,DATE RECEIVED,QTY,BILL START DATE\n";
		do {
//			fwrite($handle, $row['PALLET_ID'].",".$row['QTY_IN_HOUSE']."\n");
//			fwrite($handle, $row['THE_INVOICE'].",".$row['THE_PALLET'].",".$row['THE_DATE'].",".$row['THE_SERVICE'].",".$row['THE_SERV']."\n");
			$output .= ociresult($lines, "THE_INVOICE").",".ociresult($lines, "THE_PALLET").",".ociresult($lines, "THE_DATE").",".ociresult($lines, "THE_SERVICE").",".ociresult($lines, "THE_SERV")."\n";
		} while(ocifetch($lines));

	}
//	fclose($handle);


	$csv_attach=chunk_split(base64_encode($output));


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'KOPKEINTOSTOR'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $print_date_start, $mailSubject);

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

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/csv; name=\"".$filename."\"\r\n";
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
					'KOPKEINTOSTOR',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);

		$sql = "UPDATE RF_BILLING
				SET EXPORT_FILE = 'EMAILED'
				WHERE CUSTOMER_ID = '1131'
					AND EXPORT_FILE IS NULL
					AND TRIM(SERVICE_STATUS) = 'INVOICED'";
		$update = ociparse($rf_conn, $sql);
		ociexecute($update);

	}

//	$cmd = "echo \" \" | mutt -s \"Port inventory, into storage ".$print_date_start."\" -a ".$filename1." awalter@port.state.de.us";

//	$cmd = "echo \" \" | mutt -s \"Port storage invoice(s) (run on ".$print_date_start.")\" -a ".$filename1." pkpk@kopkefruit.com -c martym@port.state.de.us -c bdempsey@port.state.de.us -c billing@port.state.de.us -b sadu@port.state.de.us -b hdadmin@port.state.de.us -b awalter@port.state.de.us";
//	$cmd = "echo \" \" | mutt -s \"Port storage invoice(s) (run on ".$print_date_start.")\" -a ".$filename1." pkpk@kopkefruit.com -c lmizikar@port.state.de.us -c sshoemaker@port.state.de.us -b sadu@port.state.de.us -b archive@port.state.de.us -b awalter@port.state.de.us -b lstewart@port.state.de.us";
//	$cmd = "echo \" \" | mutt -s \"Port storage invoice(s) (run on ".$print_date_start.")\" -a ".$filename1." pkpk@kopkefruit.com -c lmizikar@port.state.de.us -c sshoemaker@port.state.de.us -c mmathews@port.state.de.us -b sadu@port.state.de.us -b archive@port.state.de.us -b awalter@port.state.de.us -b lstewart@port.state.de.us -b ixthomas@gmail.com";

//	system($cmd);


?>