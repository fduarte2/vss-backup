<?
/*
*	Adam Walter, Feb 2015
*
*	Vandenburg (2206) already gets weekly emails regarding
*	Bills Generated.  the V2 program, however,
*	Wouldnt fit that program well, so I'm
*	"outsourcing" it to it's own cron.
*************************************************************/

	include 'class.ezpdf.php';
	include("bill_v2_pdf.php");
/*   $date = $HTTP_SERVER_VARS["argv"][1]; 
   if($date == ""){
	   $date = date('m/d/Y');
   }
14 8*/
//	$start_offset = 14;
//	$end_offset = 8;

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'VANDENBURGINV2'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "lstewart@port.state.de.us";
//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
		$mailheaders .= "Bcc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,cmarttinen@port.state.de.us,fduarte@port.state.de.us\r\n";
		$mailSubject = "THIS IS A TEST. PLEASE IGNORE. ".ociresult($email, "SUBJECT");
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
		$mailSubject = ociresult($email, "SUBJECT");
	}

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$body = ociresult($email, "NARRATIVE");
//	$body = str_replace("_0_", "<br>".$output."<br>", $body);

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";


	$sql = "SELECT DISTINCT BILLING_TYPE
			FROM BILL_HEADER
			WHERE CUSTOMER_ID = '2206'
				AND EXPORT_FILE IS NULL
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE NOT IN ('LEASE')";
	$bill_types = ociparse($bniconn, $sql);
	ociexecute($bill_types);
	if(!ocifetch($bill_types)){
//		$compiled_body_output = "No V2 bills this week.";
	} else {
//		$compiled_body_output = "";
		do {
//			$compiled_body_output .= ociresult($bill_types, "BILLING_TYPE")." Bills:  ";
//			$bill_counter = 0;

			$pdf = "";
			$pdf = new Cezpdf('letter','portrait');
			$pdf->openHere('XYZ', 0, 800, 1.25);
			$pdf -> ezSetMargins(20,20,30,30);
			$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

			GenerateV2PDF($pdf, ociresult($bill_types, "BILLING_TYPE"), "INVOICED", "", "2206", "", "", "no", "POW_logo_in_black.jpg", $bniconn);

			$pdfcode = $pdf->ezOutput();
			$attach=chunk_split(base64_encode($pdfcode));

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"".ociresult($bill_types, "BILLING_TYPE").".pdf\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";

			$sql = "UPDATE BILL_HEADER
					SET EXPORT_FILE = 'EMAILED'
					WHERE CUSTOMER_ID = '2206'
						AND EXPORT_FILE IS NULL
						AND SERVICE_STATUS = 'INVOICED'
						AND BILLING_TYPE = '".ociresult($bill_types, "BILLING_TYPE")."'";
			$update = ociparse($bniconn, $sql);
			ociexecute($update);
		} while(ocifetch($bill_types));
	}

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
					'WEEKLYCRON',
					SYSDATE,
					'EMAIL',
					'VANDENBURGINV2',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);
	}
