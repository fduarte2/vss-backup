<?
/*
*	Apr 2016.
*
*	email sending notification of daily clementine transfers
*****************************************************************************************/


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CLEMCHGREC'";
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

	$include_table = "<table border=\"1\">
						<tr>
							<td><b>Pallet ID</b></td>
							<td><b>Original Receiver</b></td>
							<td><b>New Receiver</b></td>
							<td><b>Changed by</b></td>
						</tr>";

	$sql = "SELECT CA_FROM.PALLET_ID, CA_FROM.CUSTOMER_ID FROM_CUST, CA_TO.CUSTOMER_ID TO_CUST, CA_TO.ACTIVITY_ID
			FROM CARGO_ACTIVITY CA_FROM, CARGO_ACTIVITY CA_TO
			WHERE CA_FROM.PALLET_ID = CA_TO.PALLET_ID
				AND CA_FROM.ORDER_NUM = CA_TO.ORDER_NUM
				AND CA_FROM.ACTIVITY_NUM != 1
				AND CA_TO.ACTIVITY_NUM = 1
				AND CA_FROM.SERVICE_CODE = 11
				AND CA_TO.SERVICE_CODE = 11
				AND CA_TO.PALLET_ID IN
					(SELECT DISTINCT PALLET_ID
					FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
					WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
						AND CP.COMMODITY_TYPE = 'CLEMENTINES')
				AND CA_FROM.DATE_OF_ACTIVITY >= (SYSDATE - 1)
			ORDER BY CA_FROM.PALLET_ID";
	$pallets = ociparse($rfconn, $sql);
	ociexecute($pallets);
	while(ocifetch($pallets)){
		$include_table .= "<tr>
								<td>".ociresult($pallets, "PALLET_ID")."</td>
								<td>".GetCust(ociresult($pallets, "FROM_CUST"), $rfconn)."</td>
								<td>".GetCust(ociresult($pallets, "TO_CUST"), $rfconn)."</td>
								<td>".GetChecker(ociresult($pallets, "ACTIVITY_ID"), $rfconn)."</td>
							</tr>";
	}
	$include_table .= "</table>";

	$body = "<html><body>".ociresult($email, "NARRATIVE")."</body></html>";
	$body = str_replace("_0_", $include_table, $body);

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
					'CLEMCHGREC',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);
	}


function GetCust($cust_num, $rfconn){
	$sql = "SELECT CUSTOMER_NAME
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$cust_num."'";
	$cust = ociparse($rfconn, $sql);
	ociexecute($cust);
	if(!ocifetch($cust)){
		return $cust_num;
	} else {
		return ociresult($cust, "CUSTOMER_NAME");
	}
}

function GetChecker($act_id, $rfconn){
	$sql = "SELECT EMPLOYEE_NAME
			FROM EMPLOYEE
			WHERE SUBSTR(EMPLOYEE_ID, -4) = '".$act_id."'";
	$emp = ociparse($rfconn, $sql);
	ociexecute($emp);
	if(!ocifetch($emp)){
		return $act_id;
	} else {
		return ociresult($emp, "EMPLOYEE_NAME");
	}
}

