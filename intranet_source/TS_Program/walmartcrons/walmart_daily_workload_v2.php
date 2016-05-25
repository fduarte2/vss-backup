<?
/*
*		Adam Walter
*		June 2015
*
*		V2 of the "daily walmart workload" report
*
*		DEC 2015 : added USDA_HOLD clause
*****************************************************************/
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$date = date('w', mktime(0,0,0,date('m'), date('d'), date('Y')));
	if($date == 6){
		$date_offset = 0;  // if saturday, today IS the starting day
	} else {
		// we need to "backdate" to nearest saturday
		$date_offset = ($date + 1) * -1;
	}

//	$date_offset = -273; // testing


	$output = "<table border=\"1\"><tr bgcolor=\"grey\"><td><b>Workload Summary</b></td><td><b>Wilmington</b></td></tr>";

	// Part 1:  cases received
	$output .= "<tr><td><b>Date</b></td><td>&nbsp;</td></tr>";
	$total = 0;
	
	for($this_date = 0; $this_date < 7; $this_date++){
		$day_add = $this_date + $date_offset;  // redundant, but I find it easy to read 1 equation at a time.
		$date_this_loop = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_add, date('Y')));

//					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM(+))
		$sql = "SELECT SUM(QTY_RECEIVED) CTNS_REC
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
					AND CA.SERVICE_CODE IN ('1', '8')
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
					AND USDA_HOLD IS NULL
					AND WCT.WM_PROGRAM = 'BASE'
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND TO_CHAR(GREATEST(WVR.RELEASE_TIME, CA.DATE_OF_ACTIVITY), 'MM/DD/YYYY') = '".$date_this_loop."'
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')";
		$result = ociparse($rfconn, $sql);
		ociexecute($result);
		ocifetch($result);
		$ctns = (0 + ociresult($result, "CTNS_REC"));
		$total += $ctns;

		if($day_add > 0){ // if we haven't "reach this date" yet, dont show the total
			$output .= "<tr><td>".$date_this_loop."</td><td>&nbsp;</td></tr>";
		} else {
			$output .= "<tr><td>".$date_this_loop."</td><td>".$ctns."</td></tr>";
		}
	}
	
	$output .= "<tr bgcolor=\"yellow\"><td><b>Total Cases Received</b></td><td><b>".$total."</b></td></tr>";



	// Part 2:  containers received
	$output .= "<tr><td><b>Date</b></td><td>&nbsp;</td></tr>";
	$total = 0;
	
	for($this_date = 0; $this_date < 7; $this_date++){
		// WE DO NOT GET CONTAINERS.
		// this loop sis here just to print the boxes.  If we ever do get containers, modify this loop to put them in.
		$day_add = $this_date + $date_offset;  // redundant, but I find it easy to read 1 equation at a time.
		$date_this_loop = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_add, date('Y')));

		if($day_add > 0){ // if we haven't "reach this date" yet, dont show the total
			$output .= "<tr><td>".$date_this_loop."</td><td>&nbsp;</td></tr>";
		} else {
			$output .= "<tr><td>".$date_this_loop."</td><td>0</td></tr>";
		}
	}
	
	$output .= "<tr bgcolor=\"yellow\"><td><b>Total Containers Received</b></td><td><b>".$total."</b></td></tr>";



	// Part 3:  Pallets received
	$output .= "<tr><td><b>Date</b></td><td>&nbsp;</td></tr>";
	$total = 0;
	
	for($this_date = 0; $this_date < 7; $this_date++){
		$day_add = $this_date + $date_offset;  // redundant, but I find it easy to read 1 equation at a time.
		$date_this_loop = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_add, date('Y')));

		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) PLT_REC
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
					AND CA.SERVICE_CODE IN ('1', '8')
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
					AND USDA_HOLD IS NULL
					AND WCT.WM_PROGRAM = 'BASE'
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND TO_CHAR(GREATEST(WVR.RELEASE_TIME, CA.DATE_OF_ACTIVITY), 'MM/DD/YYYY') = '".$date_this_loop."'
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')";
		$result = ociparse($rfconn, $sql);
		ociexecute($result);
		ocifetch($result);
		$plts = (0 + ociresult($result, "PLT_REC"));
		$total += $plts;

		if($day_add > 0){ // if we haven't "reach this date" yet, dont show the total
			$output .= "<tr><td>".$date_this_loop."</td><td>&nbsp;</td></tr>";
		} else {
			$output .= "<tr><td>".$date_this_loop."</td><td>".$plts."</td></tr>";
		}
	}
	
	$output .= "<tr bgcolor=\"yellow\"><td><b>Total Pallets Received</b></td><td><b>".$total."</b></td></tr>";



	// Part 4:  Cases shipped
	$output .= "<tr><td><b>Date</b></td><td>&nbsp;</td></tr>";
	$total = 0;
	
	for($this_date = 0; $this_date < 7; $this_date++){
		$day_add = $this_date + $date_offset;  // redundant, but I find it easy to read 1 equation at a time.
		$date_this_loop = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_add, date('Y')));

		$sql = "SELECT SUM(QTY_CHANGE) CTNS_OUT
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
					AND CA.SERVICE_CODE IN ('6')
					AND ACTIVITY_DESCRIPTION IS NULL
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'				
					AND CA.ORDER_NUM IN
						(SELECT TO_CHAR(DCPO_NUM) FROM WDI_LOAD_DCPO_ITEMNUMBERS)
					AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date_this_loop."'";
		$result = ociparse($rfconn, $sql);
		ociexecute($result);
		ocifetch($result);
		$ctns = (0 + ociresult($result, "CTNS_OUT"));
		$total += $ctns;

		if($day_add > 0){ // if we haven't "reach this date" yet, dont show the total
			$output .= "<tr><td>".$date_this_loop."</td><td>&nbsp;</td></tr>";
		} else {
			$output .= "<tr><td>".$date_this_loop."</td><td>".$ctns."</td></tr>";
		}
	}
	
	$output .= "<tr bgcolor=\"yellow\"><td><b>Total Cases Shipped</b></td><td><b>".$total."</b></td></tr>";


	// Part 5:  Trucks Shipped
	$output .= "<tr><td><b>Date</b></td><td>&nbsp;</td></tr>";
	$total = 0;
	
	for($this_date = 0; $this_date < 7; $this_date++){
		$day_add = $this_date + $date_offset;  // redundant, but I find it easy to read 1 equation at a time.
		$date_this_loop = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_add, date('Y')));

		$sql = "SELECT COUNT(*) THE_TRUCKS
				FROM WDI_LOAD_HEADER
				WHERE TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$date_this_loop."'
					AND STATUS != 'CANCELLED'";
		$result = ociparse($rfconn, $sql);
		ociexecute($result);
		ocifetch($result);
		$trucks = (0 + ociresult($result, "THE_TRUCKS"));
		$total += $trucks;

		if($day_add > 0){ // if we haven't "reach this date" yet, dont show the total
			$output .= "<tr><td>".$date_this_loop."</td><td>&nbsp;</td></tr>";
		} else {
			$output .= "<tr><td>".$date_this_loop."</td><td>".$trucks."</td></tr>";
		}
	}
	
	$output .= "<tr bgcolor=\"yellow\"><td><b>Total Trucks Shipped</b></td><td><b>".$total."</b></td></tr>";


	// Part 6:  Pallets Shipped
	$output .= "<tr><td><b>Date</b></td><td>&nbsp;</td></tr>";
	$total = 0;
	
	for($this_date = 0; $this_date < 7; $this_date++){
		$day_add = $this_date + $date_offset;  // redundant, but I find it easy to read 1 equation at a time.
		$date_this_loop = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_add, date('Y')));

		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) PLTS_OUT
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
					AND CA.SERVICE_CODE IN ('6')
					AND ACTIVITY_DESCRIPTION IS NULL
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'				
					AND CA.ORDER_NUM IN
						(SELECT TO_CHAR(DCPO_NUM) FROM WDI_LOAD_DCPO_ITEMNUMBERS)
					AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date_this_loop."'";
		$result = ociparse($rfconn, $sql);
		ociexecute($result);
		ocifetch($result);
		$plts = (0 + ociresult($result, "PLTS_OUT"));
		$total += $plts;

		if($day_add > 0){ // if we haven't "reach this date" yet, dont show the total
			$output .= "<tr><td>".$date_this_loop."</td><td>&nbsp;</td></tr>";
		} else {
			$output .= "<tr><td>".$date_this_loop."</td><td>".$plts."</td></tr>";
		}
	}
	
	$output .= "<tr bgcolor=\"yellow\"><td><b>Total Pallets Shipped</b></td><td><b>".$total."</b></td></tr>";



	$output .= "</table>";

	$xls_attach=chunk_split(base64_encode($output));


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WALMARTDLYWORK'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y'))), $mailSubject);

	$body = ociresult($email, "NARRATIVE");

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"Daily_Workload_Summary_".$date.".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$xls_attach;
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
					'WALMARTDLYWORK',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
	