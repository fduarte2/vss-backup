<?
/*
*	Sends out email notifications to Walmart that a ship NEEDS TO be QC'ed.
*
*	Nov/Dec 2010.
****************************************************************************/

//  $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);
   $short_term_cursor = ora_open($conn);

   $mod_cursor = ora_open($conn);

	$sql = "SELECT LR_NUM FROM VESSEL_PROFILE
			WHERE SHIP_PREFIX = 'CHILEAN'
			AND LR_NUM NOT IN
				(SELECT LR_NUM FROM WDI_VESSEL_RELEASE)
			AND TO_CHAR(LR_NUM) IN
				(SELECT ARRIVAL_NUM FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '3000')
			AND TO_CHAR(LR_NUM) NOT IN
				(SELECT COMPLETION_NOTES FROM JOB_QUEUE
				WHERE JOB_DESCRIPTION = 'AQCVRWDI'
				AND COMPLETION_STATUS = 'COMPLETE'
				AND COMPLETION_NOTES IS NOT NULL)";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$row['LR_NUM']."'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$total_plts = $row2['THE_COUNT'];
		echo "total: ".$total_plts."\n";

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$row['LR_NUM']."'
				AND DATE_RECEIVED IS NOT NULL";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$so_far_received = $row2['THE_COUNT'];
		echo "so far: ".$so_far_received."\n";
/*
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$row['LR_NUM']."'
				AND DATE_RECEIVED IS NOT NULL
				AND DATE_RECEIVED < SYSDATE - (5 / 1440)";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$received_as_of_last_five_min_cron = $row2['THE_COUNT'];
		echo "5 min ago: ".$received_as_of_last_five_min_cron."\n";
*/
		$current_ratio = $so_far_received / $total_plts;
//		$five_min_ago_ratio = $received_as_of_last_five_min_cron / $total_plts;

		if($current_ratio >= 0.9){ //  && $five_min_ago_ratio < 0.9
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'AQCVRWDI'";
			ora_parse($cursor2, $sql);
			ora_exec($cursor2);
			ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$mailheaders = "From: ".$email_row['FROM']."\r\n";
			if($email_row['TEST'] == "Y"){
				$mailTO = "awalter@port.state.de.us";
				$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
			} else {
				$mailTO = $email_row['TO'];
				if($email_row['CC'] != ""){
					$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
				}
				if($email_row['BCC'] != ""){
					$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
				}
			}
/*
			$mailTO = $email_row['TO'];
			$mailheaders = "From: ".$email_row['FROM']."\r\n";

			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
*/
			$mailSubject = $email_row['SUBJECT'];

			$body = $email_row['NARRATIVE'];

			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$row['LR_NUM']."'";
			ora_parse($short_term_cursor, $sql);
			ora_exec($short_term_cursor);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$body = str_replace("_0_", $short_term_row['VESSEL_NAME'], $body);

			if(mail($mailTO, $mailSubject, $body, $mailheaders)){
				$sql = "INSERT INTO JOB_QUEUE
							(JOB_ID,
							SUBMITTER_ID,
							SUBMISSION_DATETIME,
							JOB_TYPE,
							JOB_DESCRIPTION,
							DATE_JOB_COMPLETED,
							COMPLETION_STATUS,
							COMPLETION_NOTES,
							JOB_EMAIL_TO,
							JOB_EMAIL_CC,
							JOB_EMAIL_BCC,
							JOB_BODY)
						VALUES
							(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
							'CONSTANTCRON',
							SYSDATE,
							'EMAIL',
							'AQCVRWDI',
							SYSDATE,
							'COMPLETE',
							'".$row['LR_NUM']."',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($mod_cursor, $sql);
				ora_exec($mod_cursor);
			}
		}
	}
