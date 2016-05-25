<?
/*
*	Adam Walter, Dec 2011.
*
*	Email notification, sent 3 days prior to the end of a "weekly cycle",
*	To inventory if they have not yet submitted their 
*	spot-check of cargo for the accuracy report.
*************************************************************************/


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
//	$cursor_first = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);

	$day = date('d');
	$month = date('m');
	$year = date('Y');
	switch($day){
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
			$print_day = 1;
			$week = 1;
		break;

		case 8:
		case 9:
		case 10:
		case 11:
		case 12:
		case 13:
		case 14:
			$print_day = 8;
			$week = 2;
		break;

		case 15:
		case 16:
		case 17:
		case 18:
		case 19:
		case 20:
		case 21:
			$print_day = 15;
			$week = 3;
		break;

		case 22:
		case 23:
		case 24:
		case 25:
		case 26:
		case 27:
		case 28:
		case 29:
		case 30:
		case 31:
			$print_day = 22;
			$week = 4;
		break;

		default:
			
			exit;
		break;
	}

	$body_replace = "";

	$sql = "SELECT *
			FROM WM_INVENTORY_AUDIT
			WHERE REPORT_YEAR = '".$year."'
				AND REPORT_MONTH = '".$month."'
				AND REPORT_WEEK = '".$week."'";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no entry at all means they still need to add it
		$body_replace = "Warehouse Cycle Spot Check has not been completed for week starting on ".$month."/".$print_day."/".$year."\n\n";
		$body_replace .= "Truck Audit Spot Check has not been completed for week starting on ".$month."/".$print_day."/".$year;
	} else {
		// there's a record.  are both halves filled out?
		if($Short_Term_Row['WH_ENTERED_BY'] == ""){
			// no warehouse check
			$body_replace .= "Warehouse Cycle Spot Check has not been completed for week starting on ".$month."/".$print_day."/".$year."\n\n";
		}
		if($Short_Term_Row['TR_ENTERED_BY'] == ""){
			// no truck check
			$body_replace .= "Truck Audit Spot Check has not been completed for week starting on ".$month."/".$print_day."/".$year;
		}
	}

	if($body_replace != ""){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'WMINVAUDIT'";
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

		$body = str_replace("_0_", $body_replace, $body);

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
						'WEEKLYCRON',
						SYSDATE,
						'EMAIL',
						'WMINVAUDIT',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		}
	}
