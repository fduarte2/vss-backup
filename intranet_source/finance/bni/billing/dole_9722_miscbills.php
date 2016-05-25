<?
/*
*		Adam Walter, Feb 2014
*
*		Moves 9722 "miscbills" direcly from RF into BNI.BILLING,
*		Skipping the RF_BNI_MISCBILLS step entirely.
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$bill_records = 0;
	$total_dollars = 0;

	$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CASES, DECODE(CA.SERVICE_CODE, '8', 6221, '7', 6220) THE_BNI_SERV, SERVICE_CODE, 
				TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ORDER_NUM, DECODE(RECEIVING_TYPE, 'T', '5298', '5103') THE_BNI_COMM
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
			WHERE CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CA.SERVICE_CODE IN ('8', '7')
				AND CA.TO_MISCBILL IS NULL
				AND CUSTOMER_ID = '9722'
			GROUP BY DECODE(CA.SERVICE_CODE, '8', 6221, '7', 6220), 
				SERVICE_CODE, 
				TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'),
				ORDER_NUM, 
				DECODE(RECEIVING_TYPE, 'T', '5298', '5103')";
	//echo $sql;
	$CA = ociparse($rfconn, $sql);
	ociexecute($CA);
	while(ocifetch($CA)){
		$sql = "SELECT RATE 
				FROM RFBNI_MISC_BILL_RATE
				WHERE RF_SERVICE_CODE = '".ociresult($CA, "SERVICE_CODE")."'
					AND BNI_COMMODITY_DEF = '".ociresult($CA, "THE_BNI_COMM")."'";
		$rate = ociparse($bniconn, $sql);
		ociexecute($rate);
		if(!ocifetch($rate)){
			mail("awalter@port.state.de.us", "dole 9722 miscbill rate not found", "Service:  ".ociresult($CA, "SERVICE_CODE")."\nComm:  ".ociresult($CA, "THE_BNI_COMM"), "From: archive@port.state.de.us\n");
			exit;
		} else {
			$rate = ociresult($rate, "RATE");
		}

		if(ociresult($CA, "SERVICE_CODE") == "8"){
			$desc = "INBOUND TRUCKLOADING IN/OUT @ $".number_format($rate, 2)."/PLT (ORDER NO:".ociresult($CA, "ORDER_NUM")." PLTS:".ociresult($CA, "THE_PLTS")." CASES:".ociresult($CA, "THE_CASES").")";
		} else {
			$desc = "RETURNS TRUCKLOADING IN/OUT @ $".number_format($rate, 2)."/PLT (ORDER NO:".ociresult($CA, "ORDER_NUM")." PLTS:".ociresult($CA, "THE_PLTS")." CASES:".ociresult($CA, "THE_CASES").")";
		}

		$sql = "INSERT INTO BILLING
					(CUSTOMER_ID,
					SERVICE_CODE,
					BILLING_NUM,
					EMPLOYEE_ID,
					SERVICE_START,
					SERVICE_STOP,
					SERVICE_DATE,
					SERVICE_AMOUNT,
					SERVICE_STATUS,
					SERVICE_DESCRIPTION,
					LR_NUM,
					ARRIVAL_NUM,
					COMMODITY_CODE,
					SERVICE_QTY,
					RF_ORDER_NUM,
					BILLING_TYPE,
					PAGE_NUM,
					CARE_OF,
					ASSET_CODE)
				(SELECT
					'453',
					'".ociresult($CA, "THE_BNI_SERV")."',
					MAX(BILLING_NUM) + 1,
					'4',
					TO_DATE('".ociresult($CA, "THE_DATE")."', 'MM/DD/YYYY'),
					TO_DATE('".ociresult($CA, "THE_DATE")."', 'MM/DD/YYYY'),
					TO_DATE('".ociresult($CA, "THE_DATE")."', 'MM/DD/YYYY'),
					'".(ociresult($CA, "THE_PLTS") * $rate)."',
					'PREINVOICE',
					'".$desc."',
					'-1',
					'1',
					'".ociresult($CA, "THE_BNI_COMM")."',
					'".ociresult($CA, "THE_PLTS")."',
					'".ociresult($CA, "ORDER_NUM")."',
					'MISC',
					'1',
					'1',
					'W000'
				FROM BILLING)";
		//echo $sql."\n";
		if ($rate > 0) {
			$insert = ociparse($bniconn, $sql);
			ociexecute($insert);
			// Insert Security Fee

			$sql = "SELECT RATE_CHARGE FROM SECURITY_CHARGE_RATES WHERE COMMODITY_CODE = '".ociresult($CA, "THE_BNI_COMM")."'"; 
//			echo $sql;
			$security_rate = ociparse($bniconn, $sql); 
			ociexecute($security_rate); 
			if(!ocifetch($security_rate)){ 
				mail("awalter@port.state.de.us", "dole 9722 security rate not found", "\nComm:  ".ociresult($CA, "THE_BNI_COMM"), "From: archive@port.state.de.us\n"); 
				exit; 
			} 
			else { 
				$security_rate = ociresult($security_rate, "RATE_CHARGE"); 
						
				$desc = "SECURITY FEE FOR ORDER No:".ociresult($CA, "ORDER_NUM")." PLTS:".ociresult($CA, "THE_PLTS")." NT:".ociresult($CA, "THE_PLTS")." RATE @$".number_format($security_rate, 2)."/NT"; 
			
				$sql = "INSERT INTO BILLING (CUSTOMER_ID, SERVICE_CODE, BILLING_NUM, EMPLOYEE_ID, SERVICE_START, SERVICE_STOP, SERVICE_DATE, SERVICE_AMOUNT, SERVICE_STATUS, SERVICE_DESCRIPTION, LR_NUM, ARRIVAL_NUM, COMMODITY_CODE, SERVICE_QTY, RF_ORDER_NUM, BILLING_TYPE, ASSET_CODE) (SELECT '453', '2214', MAX(BILLING_NUM) + 1, '4', TO_DATE('".ociresult($CA, "THE_DATE")."', 'MM/DD/YYYY'), TO_DATE('".ociresult($CA, "THE_DATE")."', 'MM/DD/YYYY'), TO_DATE('".ociresult($CA, "THE_DATE")."', 'MM/DD/YYYY'), '".(ociresult($CA, "THE_PLTS") * $security_rate)."', 'PREINVOICE', '".$desc."', '-1', '1', '".ociresult($CA, "THE_BNI_COMM")."', '".ociresult($CA, "THE_PLTS")."', '".ociresult($CA, "ORDER_NUM")."', 'MISC', 'W000' FROM BILLING)";

				//echo $sql;

				$insert = ociparse($bniconn, $sql);

				ociexecute($insert);
				$bill_records++;
				$total_dollars += (ociresult($CA, "THE_PLTS") * $security_rate);
			} 
		}

		$sql = "UPDATE CARGO_ACTIVITY
				SET TO_MISCBILL = 'Y'
				WHERE SERVICE_CODE = '".ociresult($CA, "SERVICE_CODE")."'
					AND TO_MISCBILL IS NULL
					AND CUSTOMER_ID = '9722'
					AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".ociresult($CA, "THE_DATE")."'
					AND ORDER_NUM = '".ociresult($CA, "ORDER_NUM")."'
					AND SERVICE_CODE = '".ociresult($CA, "SERVICE_CODE")."'";
		
		//echo $sql."\n";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
		
		if ($rate > 0) {
			$bill_records++;
			$total_dollars += (ociresult($CA, "THE_PLTS") * $rate);
		}
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLE9722MISC'";
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
	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $bill_records, $body);
	$body = str_replace("_1_", $total_dollars, $body);

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
					'DOLE9722MISC',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
