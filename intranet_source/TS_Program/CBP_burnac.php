<?
/*
*	Adam Walter, Nov, 2011.
*
*	Cron auto-email to CBP (burnac) regarding 
*	Inspection of Clementine pallets
*************************************************************************/
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$pallet_list_cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);

	$mail_date = date('m/d/Y');

	$body_1 = "\r\n";
	$sql = "SELECT
				vessel_name,
				pallet_id
			FROM cargo_tracking_ext cte
			INNER JOIN vessel_profile vp
				ON cte.arrival_num = vp.arrival_num
			WHERE
				cbp_inspection_status = 'OPENED' 
				AND cbp_inspection_date IS NOT NULL 
				AND TO_CHAR(cbp_inspection_date, 'MM/DD/YYYY') = '$mail_date'
				AND CTE.receiver_id = '1656'
			ORDER BY
				vessel_name,
				pallet_id";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body_1 .= "None.";
	} else {
		do {
			$body_1 .= "\t".$row['VESSEL_NAME']."\t".$row['PALLET_ID']."\r\n";
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	$body_1 .= "\r\n\r\n";

	$body_2 .= "\r\n";
	$sql = "SELECT
				COUNT(distinct pallet_id) the_count,
				vessel_name,
				ct.arrival_num
			FROM cargo_tracking ct
			INNER JOIN vessel_profile vp
				ON ct.arrival_num = vp.arrival_num
			LEFT JOIN voyage
				ON voyage.lr_num = vp.lr_num
			WHERE
				date_received IS NOT NULL 
				AND date_received <= SYSDATE - 10 
				AND commodity_code LIKE '56%' 
				AND commodity_code != '5606' 
				AND ct.arrival_num != '4321' 
				AND voyage.port_of_destination IS NULL
				AND ct.mark = 'AT POW' 
				AND ct.receiver_id = '1656'
			GROUP BY
				vessel_name,
				ct.arrival_num 
			ORDER BY
				vessel_name";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body_2 .= "No Pallets older than 10 days.";
	} else {
		do {
			$body_2 .= "\t".$row['VESSEL_NAME'].":\t".$row['THE_COUNT']." pallets\r\n";
			$sql = "SELECT DISTINCT
						pallet_id
					FROM cargo_tracking 
					WHERE
						date_received IS NOT NULL 
						AND date_received <= SYSDATE - 10 
						AND commodity_code LIKE '56%' 
						AND commodity_code != '5606' 
						AND mark = 'AT POW'
						AND receiver_id = '1656'
						AND arrival_num = '".$row['ARRIVAL_NUM']."'";
			ora_parse($pallet_list_cursor, $sql);
			ora_exec($pallet_list_cursor);
			while(ora_fetch_into($pallet_list_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$body_2 .= "\t\t".$pallet_row['PALLET_ID']."\r\n";
			}
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	$sql = "SELECT *
			FROM email_distribution
			WHERE emailid = 'CPBBURNAC'";
	ora_parse($ED_cursor, $sql);
	ora_exec($ED_cursor);
	ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$mailTO = $email_row['TO'];
	$mailheaders = "From: ".$email_row['FROM']."\r\n";

	if($email_row['CC'] != ""){
		$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
	}
	if($email_row['BCC'] != ""){
		$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
	}

	$mailSubject = $email_row['SUBJECT'];

	$body = $email_row['NARRATIVE'];

	$body = str_replace("_0_", $mail_date, $body);
	$body = str_replace("_1_", $body_1, $body);
	$body = str_replace("_2_", $body_2, $body);

	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
		$sql = "INSERT INTO job_queue
					(job_id,
					submitter_id,
					submission_datetime,
					job_type,
					job_description,
					date_job_completed,
					completion_status,
					job_email_to,
					job_email_cc,
					job_email_bcc,
					job_body)
				VALUES
					(job_queue_jobid_seq.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'CPBBURNAC',
					SYSDATE,
					'COMPLETED',
					'$mailTO',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}
