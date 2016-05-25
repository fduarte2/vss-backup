<?
/*
*
*	Adam Walter, Oct 2013.
*
*	reminder to Clementine people about discharged vessels still with "any" picklists.
*
***********************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT DCO.ORDERNUM FROM DC_ORDER DCO, DC_PICKLIST DCP
			WHERE DCO.ORDERNUM = DCP.ORDERNUM
				AND ORDERSTATUSID NOT IN ('8', '9', '10')
				AND TRIM(PACKINGHOUSE) = '9999'
				AND VESSELID != 4321
				AND TO_CHAR(VESSELID) IN
						(SELECT TO_CHAR(LR_NUM) 
						FROM VESSEL_PROFILE
						WHERE DATE_DISCHARGED IS NOT NULL
							AND (DATE_DISCHARGED + 2) <= SYSDATE
							AND SHIP_PREFIX = 'CLEMENTINES'
						UNION
						SELECT DISTINCT CT.ARRIVAL_NUM FROM CARGO_TRACKING CT, VESSEL_PROFILE VP
						WHERE CT.ARRIVAL_NUM = TO_CHAR(LR_NUM)
							AND SHIP_PREFIX = 'CLEMENTINES'
							AND DATE_DISCHARGED IS NULL
							AND DATE_RECEIVED IS NOT NULL
							AND DATE_RECEIVED <= SYSDATE - 2
						)
			ORDER BY DCO.ORDERNUM";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$order_list = "None.";
	} else {
		$order_list = "";
		do {
			$order_list .= ociresult($stid, "ORDERNUM")."\r\n";
		} while(ocifetch($stid));
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'CLEMANYPKREMIND'";
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

	$mailSubject = ociresult($email, "SUBJECT");
	$body = ociresult($email, "NARRATIVE");

	$body = str_replace("_br_", "\r\n", $body);
	$body = str_replace("_0_", $order_list, $body);

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
					'CLEMANYPKREMIND',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
