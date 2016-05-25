<?
/*
*
*	Adam Walter, May 2015
*
*	post-vessel "POW-Barcode-Create" report
*********************************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$emails_sent = 0;

	$sql = "SELECT VP.LR_NUM, VP.VESSEL_NAME
			FROM VESSEL_PROFILE VP, VOYAGE VOY
			WHERE VP.LR_NUM = VOY.LR_NUM
				AND VOY.DATE_DEPARTED <= SYSDATE
			ORDER BY VP.LR_NUM";
	$vessels = ociparse($bniconn, $sql);
	ociexecute($vessels);
	while(ocifetch($vessels)){
		$list = "Vessel ".ociresult($vessels, "VESSEL_NAME")."\r\n\r\n";
		$count = 0;

		$sql = "SELECT COUNT(*) THE_COUNT 
				FROM JOB_QUEUE
				WHERE JOB_DESCRIPTION = 'POWLABELS'
					AND VARIABLE_LIST = '".ociresult($vessels, "LR_NUM")."'";
		$already_run = ociparse($rfconn, $sql);
		ociexecute($already_run);
		ocifetch($already_run);
		if(ociresult($already_run, "THE_COUNT") <= 0){
			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, RECEIVER_ID, CUSTOMER_NAME
					FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CP
					WHERE CT.RECEIVER_ID = CP.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = '".ociresult($vessels, "LR_NUM")."'
						AND PALLET_ID LIKE 'POW%'
						AND DATE_RECEIVED IS NOT NULL
					GROUP BY RECEIVER_ID, CUSTOMER_NAME
					ORDER BY RECEIVER_ID";
			$cust_counts = ociparse($rfconn, $sql);
			ociexecute($cust_counts);
			while(ocifetch($cust_counts)){ 
				$list .= "\t".ociresult($cust_counts, "CUSTOMER_NAME")."        ".ociresult($cust_counts, "THE_COUNT")." Pallets\r\n";
				$count++;
			}
		}

		if($count > 0){
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'POWLABELS'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);

			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "TEST") == "Y"){
				$mailTO = "awalter@port.state.de.us";
				$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
			} else {
				$mailTO = ociresult($email, "TO");
				if(ociresult($email, "CC") != ""){
					$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
				}
				if(ociresult($email, "BCC") != ""){
					$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
				}
			}

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", ociresult($vessels, "VESSEL_NAME"), $mailSubject);
			$body = ociresult($email, "NARRATIVE");
			$body = str_replace("_1_", $list, $body);

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
							JOB_BODY,
							VARIABLE_LIST)
						VALUES
							(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
							'CRON',
							SYSDATE,
							'EMAIL',
							'POWLABELS',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."',
							'".ociresult($vessels, "LR_NUM")."')";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);

				$emails_sent++;
			}
		}
	}





	if($emails_sent <= 0){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'POWLABELS'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
//		if(ociresult($email, "TEST") == "Y"){
		if(true){
			$mailTO = "lstewart@port.state.de.us";
			$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,awalter@port.state.de.us,cmarttinen@port.state.de.us\r\n";
		} else {
//			$mailTO = ociresult($email, "TO");
//			if(ociresult($email, "CC") != ""){
//				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
//			}
//			if(ociresult($email, "BCC") != ""){
//				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
//			}
		}

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", "NONE", $mailSubject);
		$body = "No Vessels with POW BC closed since last email";

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
						JOB_BODY,
						VARIABLE_LIST)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'CRON',
						SYSDATE,
						'EMAIL',
						'POWLABELS',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."',
						'".ociresult($vessels, "LR_NUM")."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
		}
	}