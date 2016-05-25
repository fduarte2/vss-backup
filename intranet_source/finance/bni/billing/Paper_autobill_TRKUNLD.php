<?
/*
*	Adam Walter, April 2009.
*
*	This is the first of (I hope in the future) a number of automated scripts
*	That take the BNI.RF_BNI_MISCBILLS and move them into BNI.BILLING.
*
*	This script only handles Dole Dock Ticket Truck Unloading charges, inserting
*	Data in an analogous manner to Chilean Fruit Misc Bill.exe
*
**************************************************************************************/


	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if(!$conn){
		$body = "Error logging on to the BNI Oracle Server: " . ora_errorcode($conn);
		exit;
	}
	ora_commitoff($conn);
	$cursor = ora_open($conn);
	$modify_cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	// some variables for emailing later
//	$body = "";
//	$mailTO = "billing@port.state.de.us\r\n";
//	$mailTO = "awalter@port.state.de.us\r\n";
//	$mailSUBJECT = "Dole Dock Ticket truck unloading preinvoices run complete.\r\n";
//	$mailHEADERS = "From:  PoWMailServer@port.state.de.us\r\n";
//	$mailHEADERS .= "BCC:  awalter@port.state.de.us,lstewart@port.state.de.us,ithomas@port.state.de.us\r\n";

	$header_1272 = false;
	$header_1299 = false;

	// get all outstanding Dole dockticket truck unloading bills in RF_BNI_MISCBILLS...
	$sql = "SELECT TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_DATE, LR_NUM, RBM.CUSTOMER_ID, CUSTOMER_NAME, COMMODITY_CODE, SERVICE_CODE, DESCRIPTION, AMOUNT, ASSET_CODE, ORDER_NUM, ASSET 
			FROM RF_BNI_MISCBILLS RBM, CUSTOMER_PROFILE CP
			WHERE COMMODITY_CODE IN ('1272', '1299') 
				AND SERVICE_CODE = '6221' 
				AND BILLING_FLAG IS NULL
				AND RBM.CUSTOMER_ID = CP.CUSTOMER_ID
			ORDER BY COMMODITY_CODE";
//	echo $sql."\r\n";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT MAX(BILLING_NUM) THE_MAX FROM BILLING";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		// CHANGE, 1/1/2014:
		// All Dolepaper now bills to 313, but the papermills (I.E. the customer numbers from RF) still need to be maintained for subtotal purposes ont he printout.
		$sql = "INSERT INTO BILLING 
					(LR_NUM, 
					CUSTOMER_ID, 
					BILLING_NUM, 
					COMMODITY_CODE, 
					SERVICE_CODE, 
					SERVICE_DESCRIPTION, 
					SERVICE_AMOUNT, 
					PAGE_NUM, 
					EMPLOYEE_ID, 
					SERVICE_STATUS, 
					ARRIVAL_NUM, 
					INVOICE_NUM, 
					SERVICE_QTY, 
					SERVICE_NUM, 
					THRESHOLD_QTY, 
					LEASE_NUM, 
					CARE_OF, 
					BILLING_TYPE, 
					SERVICE_START, 
					SERVICE_STOP, 
					SERVICE_DATE, 
					ASSET_CODE, 
					RF_ORDER_NUM, 
					SUB_CUST_NUM) 
				VALUES 
					('".$row['LR_NUM']."',
					'".$row['CUSTOMER_ID']."',
					'".($short_term_row['THE_MAX'] + 1)."',
					'".$row['COMMODITY_CODE']."',
					'".$row['SERVICE_CODE']."',
					'".$row['ORDER_NUM']." ".$row['DESCRIPTION']."',
					'".$row['AMOUNT']."',
					'1',
					'4',
					'PREINVOICE',
					'1',
					'0',
					'0',
					'1',
					'0',
					'0',
					'1',
					'MISC',
					TO_DATE('".$row['THE_DATE']."', 'MM/DD/YYYY'),
					TO_DATE('".$row['THE_DATE']."', 'MM/DD/YYYY'),
					TO_DATE('".$row['THE_DATE']."', 'MM/DD/YYYY'),
					'".$row['ASSET_CODE']."',
					'".$row['ORDER_NUM']."',
					'".$row['ASSET']."')";
//		echo $sql."\n";
		ora_parse($modify_cursor, $sql);
		ora_exec($modify_cursor);

		if($header_1272 == false && $row['COMMODITY_CODE'] == 1272){
			$body .= "COMMODITY 1272:\n";
			$header_1272 = true;
		}
		if($header_1299 == false && $row['COMMODITY_CODE'] == 1299){
			$body .= "\n\nCOMMODITY 1299:\n";
			$header_1299 = true;
		}
		$body .= $row['THE_DATE']." - ".$row['DESCRIPTION']."; ".$row['CUSTOMER_NAME']." ".$row['ORDER_NUM']."\r\n";
	}

	$sql = "UPDATE RF_BNI_MISCBILLS SET BILLING_FLAG = 'Y' WHERE BILLING_FLAG IS NULL AND COMMODITY_CODE IN ('1272', '1299') AND SERVICE_CODE = '6221'";
	ora_parse($modify_cursor, $sql);
	ora_exec($modify_cursor);
	ora_commit($conn);
/*
	if($body != ""){
		mail($mailTO, $mailSUBJECT, $body, $mailHEADERS);
	} else {
		$body = "Inventory has not posted any unloading for Dole Paper in the system.  No pre-invoices generated.\r\n";
		$mailHEADERS .= "CC:  martym@port.state.de.us,ltreut@port.state.de.us\r\n";
		mail($mailTO, $mailSUBJECT, $body, $mailHEADERS);
	} */

	if($body == ""){
		$body = "Inventory has not posted any unloading for Paper in the system.  No pre-invoices generated.\r\n";
	}

	$replace_body = $body;

//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PAPERTRKUNLOAD'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
//	$mailTO = "awalter@port.state.de.us";
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}

	$mailSubject = ociresult($email, "SUBJECT");

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $replace_body, $body);

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
					'PAPERTRKUNLOAD',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
	}
