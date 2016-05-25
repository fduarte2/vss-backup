<?
/*
*	Adam Walter, Mar 2016
*
*	Nightly outbound order report to the "sealdsweet" argfruit customer.
****************************************************************************************/
 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$date = date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '1624'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$custname = ociresult($short_term_data, "BCC");

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'SEALDSWEETORDERS'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "lstewart@port.state.de.us";
//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
		$mailheaders .= "Cc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
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
	$mailSubject = str_replace("_0_", $date, $mailSubject);

	$body = ociresult($email, "NARRATIVE");

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";





	$sql = "SELECT DISTINCT ORDER_NUM 
			FROM CARGO_ACTIVITY 
			WHERE CUSTOMER_ID = '1624' 
				AND ACTIVITY_NUM != '1'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
	$orders = ociparse($rfconn, $sql);
	ociexecute($orders);
	if(!ocifetch($orders)){
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= "No Orders for the date of ".$date.".\r\n";
	} else {
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		do {
			$total_ctn = 0;
			$total_plt = 0;

			$tally = "Generated On:  ".date('m/d/Y h:i:s a')."\r\n\r\n";
			$tally .= "PORT OF WILMINGTON FRUIT OUTBOUND TALLY\r\n";
			$tally .= "Customer: ".$custname."\r\n";
			$tally .= "Order#: ".ociresult($orders, "ORDER_NUM")."\r\n";
			$tally .= "SHIPPING TYPE:  OUTBOUND\r\n";

			$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
						NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
					FROM CARGO_ACTIVITY 
					WHERE ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."' 
						AND ACTIVITY_NUM != '1'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND CUSTOMER_ID = '1624'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$start = "";
				$end = "";
			} else {
				$start = ociresult($short_term_data, "START_TIME");
				$end = ociresult($short_term_data, "END_TIME");
			}

			$tally .= "First Scan: ".$start."\r\n";
			$tally .= "Last Scan: ".$end."\r\n\r\n";

			$the_BC = str_pad("BARCODE", 20);
			$the_desc = str_pad("DESCRIPTION", 70);
			$the_qty = str_pad("QTY", 6);
			$the_ves = str_pad("VESSEL", 31);
			$the_checker = str_pad("CHECKER", 8);

			$tally .= $the_BC.$the_desc.$the_qty.$the_ves.$the_checker."\r\n";
			$tally .= "======================================================================================================================================\r\n";



			$sql = "SELECT PALLET_ID, QTY_CHANGE, ACTIVITY_NUM, ARRIVAL_NUM, SERVICE_CODE, 
						DECODE(SERVICE_CODE, '5', 'TR', '7', 'FR', '9', 'RE', '12', 'VO', '13', 'DR', '') THE_SERV
					FROM CARGO_ACTIVITY
					WHERE ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."' 
						AND CUSTOMER_ID = '1624'
						AND SERVICE_CODE NOT IN ('1', '8', '12', '18', '19', '21', '22') 
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					ORDER BY PALLET_ID, ACTIVITY_NUM";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			while(ocifetch($short_term_data)){
				$emp_name = get_employee_for_print(ociresult($short_term_data, "PALLET_ID"), 
													ociresult($short_term_data, "ARRIVAL_NUM"), $cust_num, 
													ociresult($short_term_data, "ACTIVITY_NUM"), $rfconn);
				$total_ctn += ociresult($short_term_data, "QTY_CHANGE");
				$total_plt++;

				$the_BC = str_pad(trim(ociresult($short_term_data, "PALLET_ID")), 20);
				$the_desc = str_pad(GetPalletDesc(ociresult($short_term_data, "PALLET_ID"), ociresult($short_term_data, "ARRIVAL_NUM"), $cust_num, $rfconn), 70);
				$the_qty = str_pad(ociresult($short_term_data, "QTY_CHANGE"), 6);
				$the_ves = str_pad(GetVesName(ociresult($short_term_data, "ARRIVAL_NUM"), $rfconn), 31);
				$the_checker = str_pad(trim($emp_name), 8);
				$tally .= $the_BC.$the_desc.$the_qty.$the_ves.$the_checker."\r\n";
			}

			$tally .= "\r\n";
			$tally .= "\r\n";
			$disp_total_spaces = str_pad("", 70);
			$disp_total_words = str_pad(trim("Total Cases:"), 20);
			$disp_total_qty = str_pad(trim($total_ctn), 6);
			$tally .= $disp_total_spaces.$disp_total_words.$disp_total_qty."\r\n";

			$disp_total_spaces = str_pad("", 70);
			$disp_total_words = str_pad(trim("Total Pallets:"), 20);
			$disp_total_qty = str_pad(trim($total_plt), 6);
			$tally .= $disp_total_spaces.$disp_total_words.$disp_total_qty."\r\n";
			$tally .= "\r\n";

			$attach = chunk_split(base64_encode($tally));
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/text; name=\"tally".ociresult($orders, "ORDER_NUM").".txt\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";

		} while(ocifetch($orders));
	}

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
					'SEALDSWEETORDERS',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}

function get_employee_for_print($Barcode, $LR, $cust, $act_num, $rfconn){

	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$LR."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$date = ociresult($short_term_data, "THE_DATE");
	$emp_no = ociresult($short_term_data, "ACTIVITY_ID");

	if($emp_no == ""){
		return "UNKNOWN";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE
			WHERE CHANGE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") < 1){
		$sql = "SELECT LOGIN_ID THE_EMP
				FROM PER_OWNER.PERSONNEL
				WHERE EMPLOYEE_ID = '".$emp_no."'";
	} else {
//		return $emp_no;
		while(strlen($emp_no) < 5){
			$emp_no = "0".$emp_no;
		}
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($emp_no).") = '".$emp_no."'"; 
	}
//	echo $sql."\n";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	return ociresult($short_term_data, "THE_EMP");
}

function GetPalletDesc($pallet, $vessel, $cust, $rfconn){

	$return = "";

	$sql = "SELECT COMMODITY_NAME, DECODE(VARIETY, NULL, '', ' - ' || VARIETY) THE_VAR, 
				DECODE(REMARK, NULL, '', ' - ' || REMARK) THE_REM, 
				DECODE(CARGO_SIZE, NULL, '', ' - ' || CARGO_SIZE) THE_SIZE, 
				DECODE(BATCH_ID, NULL, '', ' - ' || BATCH_ID) THE_VOUCHER, 
				NVL(WAREHOUSE_LOCATION, 'NONE') THE_WHS,
				NVL(CONTAINER_ID, 'Not Specified.') THE_CONT
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE PALLET_ID = '".$pallet."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$vessel."'
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE";
//	echo $sql."\n";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$return = ociresult($short_term_data, "COMMODITY_NAME").ociresult($short_term_data, "THE_VAR").ociresult($short_term_data, "THE_REM").ociresult($short_term_data, "THE_VOUCHER").ociresult($short_term_data, "THE_SIZE");

	// NOT USED IN INBOUND TALLY
//	if($short_term_data_row['THE_WHS'] == "SW"){
//		$swingflag = $short_term_data_row['THE_CONT'];
//	}

	return $return;
}



function GetVesName($vessel, $rfconn){

	$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		return "TRUCKED-IN";
	} else {
		return $vessel."-".ociresult($short_term_data, "VESSEL_NAME");
	}
}
