<?
/*
*		Adam Walter, Feb 2014
*
*		Notification of X-bills (AKA cannot find a rate)
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

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'UNBILLABLEREMINDER'";
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
	$mailheaders .= "Content-Type: text/html\r\n";

	$mailSubject = ociresult($email, "SUBJECT");

	$body = "<html><body>".ociresult($email, "NARRATIVE")."</body></html>";




	$output = "<br><table border=\"1\" width=\"60%\" cellpadding=\"4\" cellspacing=\"0\">";
	$output .= "<tr>
					<td><b>Service</b></td>
					<td><b>Barcode</b></td>
					<td><b>Customer</b></td>
					<td><b>Arrival</b></td>
					<td><b>Service Date</b></td>
				</tr>";

	$sql = "SELECT CA.*, SC.SERVICE_NAME, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE 
			FROM CARGO_ACTIVITY CA, SERVICE_CATEGORY SC
			WHERE TO_MISCBILL = 'X'
				AND CA.SERVICE_CODE = SC.SERVICE_CODE
			ORDER BY SC.SERVICE_CODE, CUSTOMER_ID, ARRIVAL_NUM, TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY'), PALLET_ID";
	$entries = ociparse($rfconn, $sql);
	ociexecute($entries);
	if(!ocifetch($entries)){
		$body = str_replace("_0_", "No Unbillable entries in the V2 Bill System.<br>Presently, this includes the following bill Types:<br><br>Transfer", $body);
	} else {
		do {
			$sql = "SELECT NVL(VESSEL_NAME, 'TRUCK') THE_VES
					FROM VESSEL_PROFILE
					WHERE TO_CHAR(LR_NUM) = '".ociresult($entries, "ARRIVAL_NUM")."'";
			$ves_sql = ociparse($bniconn, $sql);
			ociexecute($ves_sql);
			if(!ocifetch($ves_sql)){
				$vesname = ociresult($entries, "ARRIVAL_NUM")." - Truck";
			} else {
				$vesname = ociresult($ves_sql, "THE_VES");
			}

			$sql = "SELECT CUSTOMER_NAME
					FROM CUSTOMER_PROFILE
					WHERE TO_CHAR(CUSTOMER_ID) = '".ociresult($entries, "CUSTOMER_ID")."'";
			$cust_sql = ociparse($bniconn, $sql);
			ociexecute($cust_sql);
			ocifetch($cust_sql);
			$custname = ociresult($cust_sql, "CUSTOMER_NAME");

			
			$output .= "<tr>
							<td>".ociresult($entries, "SERVICE_CODE")." ".ociresult($entries, "SERVICE_NAME")." - (Scanned)</td>
							<td>".ociresult($entries, "PALLET_ID")."</td>
							<td>".$custname."</td>
							<td>".$vesname."</td>
							<td>".ociresult($entries, "THE_DATE")."</td>
						</tr>";
		} while(ocifetch($entries));

		$output .= "</table>";

		$body = str_replace("_0_", $output, $body);

	}


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
					'UNBILLABLEREMINDER',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		$update_email = ociparse($rfconn, $sql);
		ociexecute($update_email);
	}
