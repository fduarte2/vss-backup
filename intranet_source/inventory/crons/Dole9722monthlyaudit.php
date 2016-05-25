<?
/*
*	Adam Walter, May 2014
*
*	Cron report for Dole-9722 pallet audit
*
*	This script should be run very late night (11:50PM or so) so that
*	scans for that day will still show up on that month's audit
************************************************************************/

	$month_check = date('m/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
	$day_value = date('d');

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM JOB_QUEUE
			WHERE JOB_DESCRIPTION = 'DOLEAUDITRESULTS'
				AND TO_CHAR(DATE_JOB_COMPLETED, 'MM/YYYY') = '".$month_check."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		// there was a successful email already for "this month".  stop script.
		exit;
	}

	$send_warning = true;


	// next, check if a valid audit scan was completed for this month.
	$sql = "SELECT COUNT(*) THE_COUNT, TRANS_ID
			FROM DOLE9722_AUDITS
			WHERE TO_CHAR(SCAN_TIME, 'MM/YYYY') = '".$month_check."'
			GROUP BY TRANS_ID
			HAVING COUNT(*) = 10 OR COUNT(*) = 9
			ORDER BY TRANS_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){

		// nope.  do we alert anyone?
		if($day_value >= 8){
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEAUDITREMINDER'";
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

			$send_warning = true;
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
							'DOLEAUDITREMINDER',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			}
		}
	} else {
		$todays_count = ociresult($stid, "THE_COUNT");

		$failed_count = 0;
		$passed_count = 0;

		// take the first valid set-of-10
		$trans_for_report = ociresult($stid, "TRANS_ID");

		$output = "<br><br><table border=\"1\">
						<tr>
							<td><b>Location Checked</b></td>
							<td><b>Checker ID</b></td>
							<td><b>Pallet ID Scanned</b></td>
							<td><b>Time Of Scan</b></td>
							<td><b>In Dole`s Data?</b></td>
							<td><b>Date Received Per Dole Data</b></td>
							<td><b>Date Shipped Per Dole Data</b></td>
							<td><b>Results of Check</b></td>
							<td><b>Multiple Pallets?</b></td>
						</tr>";

		$sql = "SELECT PALLET_ID, TO_CHAR(SCAN_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_SCAN, SCREEN_LOC, CHECKER
				FROM DOLE9722_AUDITS
				WHERE TRANS_ID = '".$trans_for_report."'
				ORDER BY PALLET_ID";
		$pallets = ociparse($rfconn, $sql);
		ociexecute($pallets);
		while(ocifetch($pallets)){
			$scan_date = ociresult($pallets, "THE_SCAN");
			$output .= "<tr>
							<td>".ociresult($pallets, "SCREEN_LOC")."</td>
							<td>".ociresult($pallets, "CHECKER")."-".get_employee_for_print(ociresult($pallets, "CHECKER"), $rfconn)."</td>
							<td>".ociresult($pallets, "PALLET_ID")."</td>
							<td>".ociresult($pallets, "THE_SCAN")."</td>";
			$sql = "SELECT COUNT(*) THE_COUNT, MAX(ARRIVAL_NUM) THE_CHOSEN_ARV 
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".ociresult($pallets, "PALLET_ID")."'
						AND RECEIVER_ID = '9722'";
			$dole_check = ociparse($rfconn, $sql);
			ociexecute($dole_check);
			ocifetch($dole_check);
			if(ociresult($dole_check, "THE_COUNT") == 0){
				$output .= "<td>No</td><td>&nbsp;</td><td>&nbsp;</td><td>FAILED</td><td>&nbsp;</td></tr>";
				$failed_count++;
			} else {
				if(ociresult($dole_check, "THE_COUNT") >= 2){
					$multiple_report = "Yes";
				} else {
					$multiple_report = "";
				}

				$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC
						FROM CARGO_TRACKING
						WHERE PALLET_ID = '".ociresult($pallets, "PALLET_ID")."'
							AND RECEIVER_ID = '9722'
							AND ARRIVAL_NUM = '".ociresult($dole_check, "THE_CHOSEN_ARV")."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$date_rec = ociresult($short_term_data, "DATE_REC");

				$sql = "SELECT TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') DATE_OUT
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".ociresult($pallets, "PALLET_ID")."'
							AND CUSTOMER_ID = '9722'
							AND ARRIVAL_NUM = '".ociresult($dole_check, "THE_CHOSEN_ARV")."'
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				$date_out = ociresult($short_term_data, "DATE_OUT");

				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CARGO_TRACKING
						WHERE PALLET_ID = '".ociresult($pallets, "PALLET_ID")."'
							AND RECEIVER_ID = '9722'
							AND ARRIVAL_NUM = '".ociresult($dole_check, "THE_CHOSEN_ARV")."'
							AND DATE_RECEIVED <= TO_DATE('".$scan_date."', 'MM/DD/YYYY HH24:MI:SS')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "THE_COUNT") >= 1){
					$date_rec_check = true;
				} else {
					$date_rec_check = false;
				}

				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".ociresult($pallets, "PALLET_ID")."'
							AND CUSTOMER_ID = '9722'
							AND ARRIVAL_NUM = '".ociresult($dole_check, "THE_CHOSEN_ARV")."'
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL
							AND DATE_OF_ACTIVITY <= TO_DATE('".$scan_date."', 'MM/DD/YYYY HH24:MI:SS')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "THE_COUNT") >= 1){
					$date_out_check = false;
				} else {
					$date_out_check = true;
				}

				if(!$date_out_check || !$date_rec_check){
					$passed = "FAILED";
					$failed_count++;
				} else {
					$passed = "PASSED";
					$passed_count++;
				}


				$output .= "<td>Yes</td>
							<td>".$date_rec."</td>
							<td>".$date_out."</td>
							<td>".$passed."</td>
							<td>".$multiple_report."</td></tr>";
			}
		}
		$output .= "<tr><td colspan=\"9\"><b>Total Passed: ".$passed_count." of ".$todays_count."<br><br>Total Failed: ".$failed_count." of ".$todays_count."</b></td></tr>";
		$output .= "</table>";

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEAUDITRESULTS'";
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
		$mailSubject = str_replace("_0_", $month_check, $mailSubject);

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
						'DOLEAUDITRESULTS',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($stid, "CC")."',
						'".ociresult($stid, "BCC")."',
						'".substr($body, 0, 2000)."')";
//			echo $sql."\n";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			$send_warning = false;
		}
	}











function get_employee_for_print($Emp, $rfconn){
/*
	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$LR."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);

	$date = ociresult($stid, "THE_DATE");
	$emp_no = ociresult($stid, "ACTIVITY_ID");

	if($emp_no == ""){
		return "UNKNOWN";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE
			WHERE CHANGE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") < 1){
		$sql = "SELECT LOGIN_ID THE_EMP
				FROM PER_OWNER.PERSONNEL
				WHERE EMPLOYEE_ID = '".$emp_no."'";
	} else {
//		return $emp_no;
		while(strlen($emp_no) < 5){
			$emp_no = "0".$emp_no;
		} */
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($Emp).") = '".$Emp."'"; 
//	}
//	echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);

	return ociresult($stid, "THE_EMP");
}
