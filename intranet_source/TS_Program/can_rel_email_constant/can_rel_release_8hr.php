<?
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	// count of all containers taht are released, but not yet out the door
	$sql = "SELECT ARRIVAL_NUM, COUNT(DISTINCT CONTAINER_NUM) THE_CONTS
			FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
			WHERE BROKER_RELEASE IS NOT NULL
				AND AMS_RELEASE IS NOT NULL
				AND LINE_RELEASE IS NOT NULL
				AND GATEPASS_PDF_DATE IS NULL
				AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
				AND CMD.CLR_KEY = CTMJ.MAIN_CLR_KEY
			GROUP BY ARRIVAL_NUM";
	$ships = ociparse($rfconn, $sql);
	ociexecute($ships);
	while(ocifetch($ships)){
		$vessel = ociresult($ships, "ARRIVAL_NUM");
		$total_cont = ociresult($ships, "THE_CONTS");

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$vesname = ociresult($short_term_data, "VESSEL_NAME");

		$sql = "SELECT COUNT(DISTINCT CONTAINER_NUM) THE_COUNT
				FROM CLR_MAIN_DATA
				WHERE ARRIVAL_NUM = '".$vessel."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$total_all = ociresult($short_term_data, "THE_COUNT");

		$sql = "SELECT DISTINCT CONTAINER_NUM
				FROM CLR_MAIN_DATA
				WHERE BROKER_STATUS IS NOT NULL
					AND AMS_STATUS IS NOT NULL
					AND LINE_STATUS IS NOT NULL
					AND GATEPASS_PDF_DATE IS NULL
					AND ARRIVAL_NUM = '".$vessel."'
					AND GREATEST(BROKER_STATUS, AMS_STATUS, LINE_STATUS) > (SYSDATE - 8/24)
				ORDER BY CONTAINER_NUM";
		$cont_list = "";
		$this_pass = 0;
		$conts = ociparse($rfconn, $sql);
		ociexecute($conts);
		$output = "Summary To Date, Including Containers Below:\r\n".$total_all." Total Container(s) on Vessel\r\n".$total_cont." Total Container(s) released\r\n\r\n\r\nContainer Number(s) Released in last 8 hours:\r\n";
		while(ocifetch($conts)){
			$output .= ociresult($conts, "CONTAINER_NUM")."\r\n";
			$this_pass++;
		}

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CANCONTREL'";
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
		$mailSubject = str_replace("_0_", $this_pass, $mailSubject);
//		$mailSubject = str_replace("_1_", date('m/d/Y h:i a'), $mailSubject);
		$mailSubject = str_replace("_2_", $vessel." - ".$vesname, $mailSubject);


		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_3_", $output."\r\n", $body);

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
						'8HR-CRON',
						SYSDATE,
						'EMAIL',
						'CANCONTREL',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
		}
	}