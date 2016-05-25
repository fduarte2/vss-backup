<?
/*
*	Adam Walter, Feb 2013
*
*	Nightly email to INVE for all "Chilean" Cargos.  
*	Intended so that they can review to check for any mis-commodit'ied truck-ins.
*
***********************************************************************************************/


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$output = "<br><table border=\"1\" width=\"50%\" cellpadding=\"4\" cellspacing=\"0\">";
	$output .= "<tr>
					<td>Commodity Code</td>
					<td>LR#</td>
				</tr>";

	$sql = "SELECT DISTINCT ARRIVAL_NUM, COMMODITY_CODE
			FROM CARGO_TRACKING
			WHERE TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".date('m/d/Y')."'
				AND RECEIVING_TYPE = 'T'
			ORDER BY COMMODITY_CODE, ARRIVAL_NUM";
	$stid = ociparse($rfconn, $sql);
//	echo $sql."\n";
	ociexecute($stid);
	while(ocifetch($stid)){
	$output .= "<tr>
					<td>".ociresult($stid, "COMMODITY_CODE")."</td>
					<td>".ociresult($stid, "ARRIVAL_NUM")."</td>
				</tr>";
	}
	$output .= "</table>";

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'IBLOADSUMM'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}
	$mailheaders .= "Content-Type: text/html\r\n";

	$mailSubject = ociresult($email, "SUBJECT");

	$body = "<html><body>".ociresult($email, "NARRATIVE").$output."</body></html>";
//	$body = str_replace("_0_", $by, $body);
//	$body = str_replace("_1_", $output, $body);

	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
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
					'IBLOADSUMM',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email); 
	}
