<?
/*
*	Adam Walter, Apr 2013
*
*	Email that sends notification of pallets shipped... 
*	but still with QTY in house.
*****************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$date = date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
//	$date = '04/01/2013';

	$sql = "SELECT CT.RECEIVER_ID, CP.CUSTOMER_NAME, CT.ARRIVAL_NUM, NVL(VP.VESSEL_NAME, 'TRUCK') THE_VES, CA.ORDER_NUM, COUNT(DISTINCT CA.PALLET_ID) THE_COUNT
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP, VESSEL_PROFILE VP
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CP.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
				AND CA.SERVICE_CODE = '6'
				AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				AND (ACTIVITY_DESCRIPTION IS NULL)
				AND CT.QTY_IN_HOUSE > 0
				AND CT.COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IN ('CHILEAN', 'ARG FRUIT', 'BRAZILLIAN', 'PERUVIAN'))
			GROUP BY CT.RECEIVER_ID, CP.CUSTOMER_NAME, CT.ARRIVAL_NUM, NVL(VP.VESSEL_NAME, 'TRUCK'), CA.ORDER_NUM
			ORDER BY CT.RECEIVER_ID, CA.ORDER_NUM, CT.ARRIVAL_NUM";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$output = "No pallets shipped yesterday with lingering quantities in house";
	} else {
		$output = "<table border=\"1\">
					<tr>
						<td><b>Customer#</b></td>
						<td><b>Customer Name</b></td>
						<td><b>Vessel#</b></td>
						<td><b>Vessel Name</b></td>
						<td><b>Outbound Order</b></td>
						<td><b>Count of Pallets with residual QTY</b></td>
					</tr>";
		do {
			$output .= "<tr>
							<td>".ociresult($stid, "RECEIVER_ID")."</td>
							<td>".ociresult($stid, "CUSTOMER_NAME")."</td>
							<td>".ociresult($stid, "ARRIVAL_NUM")."</td>
							<td>".ociresult($stid, "THE_VES")."</td>
							<td>".ociresult($stid, "ORDER_NUM")."</td>
							<td>".ociresult($stid, "THE_COUNT")."</td>
						</tr>";
		} while(ocifetch($stid));
		$output .= "</table><br><br><table border=\"1\">
						<tr>
							<td><b>Customer#</b></td>
							<td><b>Customer Name</b></td>
							<td><b>Vessel#</b></td>
							<td><b>Vessel Name</b></td>
							<td><b>Outbound Order</b></td>
							<td><b>PalletID</b></td>
							<td><b>Original QTY Received</b></td>
							<td><b>QTY Shipped ".$date."</b></td>
							<td><b>Total QTY Shipped as of now</b></td>
							<td><b>QTY in House, NOW</b></td>
						</tr>";
		$sql = "SELECT CT.RECEIVER_ID, CP.CUSTOMER_NAME, CT.ARRIVAL_NUM, NVL(VP.VESSEL_NAME, 'TRUCK') THE_VES, CA.ORDER_NUM,
					CT.PALLET_ID, CT.QTY_RECEIVED, SUM(CA.QTY_CHANGE) THE_SUM, CT.QTY_IN_HOUSE
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP, VESSEL_PROFILE VP
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CP.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
					AND (ACTIVITY_DESCRIPTION IS NULL)
					AND CA.SERVICE_CODE = '6'
					AND CT.QTY_IN_HOUSE > 0
					AND CT.COMMODITY_CODE IN
						(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IN ('CHILEAN', 'ARG FRUIT', 'BRAZILLIAN', 'PERUVIAN'))
				GROUP BY CT.RECEIVER_ID, CP.CUSTOMER_NAME, CT.ARRIVAL_NUM, NVL(VP.VESSEL_NAME, 'TRUCK'), CA.ORDER_NUM,
					CT.PALLET_ID, CT.QTY_RECEIVED, CT.QTY_IN_HOUSE
				ORDER BY CT.QTY_IN_HOUSE DESC, CT.RECEIVER_ID, CT.ARRIVAL_NUM, CA.ORDER_NUM, CT.PALLET_ID";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			$sql = "SELECT SUM(CA.QTY_CHANGE) THE_SUM
					FROM CARGO_ACTIVITY CA
					WHERE CA.CUSTOMER_ID = '".ociresult($stid, "RECEIVER_ID")."'
						AND CA.ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'
						AND CA.PALLET_ID = '".ociresult($stid, "PALLET_ID")."'
						AND (ACTIVITY_DESCRIPTION IS NULL)
						AND CA.SERVICE_CODE = '6'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);

			$output .= "<tr>
							<td>".ociresult($stid, "RECEIVER_ID")."</td>
							<td>".ociresult($stid, "CUSTOMER_NAME")."</td>
							<td>".ociresult($stid, "ARRIVAL_NUM")."</td>
							<td>".ociresult($stid, "THE_VES")."</td>
							<td>".ociresult($stid, "ORDER_NUM")."</td>
							<td>".ociresult($stid, "PALLET_ID")."</td>
							<td>".ociresult($stid, "QTY_RECEIVED")."</td>
							<td>".ociresult($stid, "THE_SUM")."</td>
							<td>".ociresult($short_term_data, "THE_SUM")."</td>
							<td>".ociresult($stid, "QTY_IN_HOUSE")."</td>
						</tr>";
		}
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DLYPLTRPT'";
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
	$mailSubject = str_replace("_0_", $date, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $output, $body);

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
					'DLYPLTRPT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
