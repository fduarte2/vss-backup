<?
/*
*	Adam Walter, Jul 2012
*
*	Inventory's default method for "stopping billing" is to just set
*	a "next billing date" sufficiently far int he future.
*
*	This email will notify INVE of any cargo that has a next bill
*	date 5 or mroe years in the future so they don't forget about it
************************************************************************/

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
																	
	$sql = "SELECT * FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
			WHERE CT.LOT_NUM = CM.CONTAINER_NUM
			AND QTY_IN_HOUSE > 0
			AND STORAGE_END >= SYSDATE + (5 * 365)";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$output = "None.";
	} else {
		$output = "<table border=\"1\"><tr>
							<td>LR</td>
							<td>Cust</td>
							<td>BOL</td>
							<td>MARK</td>
							<td>IH</td>
							<td>Reason</td>
						</tr>";
		do {
			$output .= "<tr>
							<td>".ociresult($stid, "LR_NUM")."</td>
							<td>".ociresult($stid, "OWNER_ID")."</td>
							<td>".ociresult($stid, "CARGO_BOL")."</td>
							<td>".ociresult($stid, "CARGO_MARK")."</td>
							<td>".ociresult($stid, "QTY_IN_HOUSE")."</td>
							<td>".ociresult($stid, "COMMENTS")."</td>
						</tr>";
		} while(ocifetch($stid));

		$output .= "</table>";
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'BNISTRGSTOPPED'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);

	$mailTO = ociresult($stid, "TO");
	$mailheaders = "From: ".ociresult($stid, "FROM")."\r\n";

	if(ociresult($stid, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($stid, "CC")."\r\n";
	}
	if(ociresult($stid, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($stid, "BCC")."\r\n";
	}
	$mailheaders .= "Content-Type: text/html\r\n";

	$mailSubject = ociresult($stid, "SUBJECT");

	$body = ociresult($stid, "NARRATIVE");
	$body = str_replace("_0_", "<br>".$output."<br>", $body);

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
					'BNISTRGSTOPPED',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($stid, "CC")."',
					'".ociresult($stid, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
	}
