<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	
//	echo "hi\r\n";
	$sql = "SELECT * FROM JOB_QUEUE
			WHERE COMPLETION_STATUS = 'PENDING'
				AND JOB_DESCRIPTION = 'PAPERRAILCARS'";
	$jobs = ociparse($rfconn, $sql);
	ociexecute($jobs);
	while(ocifetch($jobs)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PAPERRAILCARS'";
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

		$temp = array();
		$temp = explode(";", ociresult($jobs, "VARIABLE_LIST"));
		$tablename = $temp[0];
		$daily_recap_check = $temp[1];

		if($daily_recap_check == "RECAP"){
			$run_date = date('m/d/Y', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
		} else {
			$run_date = date('m/d/Y');
		}

		$sql = "SELECT TO_CHAR(MAX(EMAILTIME), 'HH24:MI:SS') THE_MAX
				FROM ".$tablename."
				WHERE TO_CHAR(EMAILTIME, 'MM/DD/YYYY') = '".$run_date."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$since_subject = "";
		} else {
			$since_subject = " since ".ociresult($short_term_data, "THE_MAX");
		}


		$this_email_railcars = 0;
		$new_list = "<table border=\"1\">";
		$new_list .= "<tr>
					<td><b>RAILCAR</b></td>
					<td><b>SUPERVISOR</b></td>
					<td><b>CHECKER</b></td>
					<td><b>ENTERED TIME</b></td>
				</tr>";
		$sql = "SELECT RAILCAR, MAX(SUPER) THE_SUP, MAX(EMPLOYEE_NAME) THE_EMP, TO_CHAR(MAX(SCANTIME), 'MM/DD/YYYY HH24:MI:SS') THE_SCANTIME 
				FROM ".$tablename." PAP_TAB, EMPLOYEE EMP
				WHERE EMAILTIME IS NULL
					AND PAP_TAB.CHECKER = SUBSTR(EMP.EMPLOYEE_ID, -4)
					AND TO_CHAR(SCANTIME, 'MM/DD/YYYY') = '".$run_date."'
				GROUP BY RAILCAR
				ORDER BY TO_DATE(TO_CHAR(MAX(SCANTIME), 'MM/DD/YYYY HH24:MI:SS'), 'MM/DD/YYYY HH24:MI:SS') DESC";
		$railcars = ociparse($rfconn, $sql);
		ociexecute($railcars);
		while(ocifetch($railcars)){
			$new_list .= "<tr>
						<td>".ociresult($railcars, "RAILCAR")."</td>
						<td>".ociresult($railcars, "THE_SUP")."</td>
						<td>".ociresult($railcars, "THE_EMP")."</td>
						<td>".ociresult($railcars, "THE_SCANTIME")."</td>
					</tr>";
			$this_email_railcars++;
		}
		$new_list .= "</table>";

		$total_railcars = 0;
		$total_list = "<table border=\"1\">";
		$total_list .= "<tr>
					<td><b>RAILCAR</b></td>
					<td><b>SUPERVISOR</b></td>
					<td><b>CHECKER</b></td>
					<td><b>ENTERED TIME</b></td>
				</tr>";
		$sql = "SELECT RAILCAR, MAX(SUPER) THE_SUP, MAX(EMPLOYEE_NAME) THE_EMP, TO_CHAR(MAX(SCANTIME), 'MM/DD/YYYY HH24:MI:SS') THE_SCANTIME 
				FROM ".$tablename." PAP_TAB, EMPLOYEE EMP
				WHERE (EMAILTIME IS NULL OR TO_CHAR(EMAILTIME, 'MM/DD/YYYY') = '".$run_date."')
					AND PAP_TAB.CHECKER = SUBSTR(EMP.EMPLOYEE_ID, -4)
					AND TO_CHAR(SCANTIME, 'MM/DD/YYYY') = '".$run_date."'
				GROUP BY RAILCAR
				ORDER BY TO_DATE(TO_CHAR(MAX(SCANTIME), 'MM/DD/YYYY HH24:MI:SS'), 'MM/DD/YYYY HH24:MI:SS') DESC";
		$railcars = ociparse($rfconn, $sql);
		ociexecute($railcars);
		while(ocifetch($railcars)){
			$total_list .= "<tr>
						<td>".ociresult($railcars, "RAILCAR")."</td>
						<td>".ociresult($railcars, "THE_SUP")."</td>
						<td>".ociresult($railcars, "THE_EMP")."</td>
						<td>".ociresult($railcars, "THE_SCANTIME")."</td>
					</tr>";
			$total_railcars++;
		}
		$total_list .= "</table>";

		$mailSubject = ociresult($email, "SUBJECT");
		$mailSubject = str_replace("_0_", $run_date, $mailSubject);

		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_0_", $run_date, $body);
		$body = str_replace("_1_", $new_list, $body);
		$body = str_replace("_2_", $this_email_railcars, $body);
		$body = str_replace("_3_", $since_subject, $body);
		$body = str_replace("_4_", $total_list, $body);
		$body = str_replace("_5_", $total_railcars, $body);
		$body = str_replace("_br_", "<br>", $body);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "UPDATE ".$tablename."
					SET EMAILTIME = SYSDATE
					WHERE EMAILTIME IS NULL";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);

			$sql = "UPDATE JOB_QUEUE
					SET COMPLETION_STATUS = 'COMPLETED'
					WHERE JOB_ID = '".ociresult($jobs, "JOB_ID")."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}
	}

	// lastly, a check to see if there are previous day railcars that didnt fire off (likely due to a scanner
	// starting to record, but then running out of bateries, of the checker forgetting about it, or whatnot)
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM BOOKINGRAILCARLIST
			WHERE SCANTIME < TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY')
				AND EMAILTIME IS NULL";
	$final_check = ociparse($rfconn, $sql);
	ociexecute($final_check);
	ocifetch($final_check);
	if(ociresult($final_check, "THE_COUNT") >= 1){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					COMPLETION_STATUS,
					VARIABLE_LIST)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'PAPERRAILCARS',
					'PENDING',
					'BOOKINGRAILCARLIST;RECAP')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM DOLEDTRAILCARLIST
			WHERE SCANTIME < TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY')
				AND EMAILTIME IS NULL";
	$final_check = ociparse($rfconn, $sql);
	ociexecute($final_check);
	ocifetch($final_check);
	if(ociresult($final_check, "THE_COUNT") >= 1){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					COMPLETION_STATUS,
					VARIABLE_LIST)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'PAPERRAILCARS',
					'PENDING',
					'DOLEDTRAILCARLIST;RECAP')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}

