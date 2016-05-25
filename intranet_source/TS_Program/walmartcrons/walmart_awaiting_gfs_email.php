<?
/*
*	Sends out email notifications to Walmart that a ship has arrived.
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

	$sql = "SELECT * FROM JOB_QUEUE WHERE JOB_DESCRIPTION = 'NVE1'
			AND COMPLETION_STATUS = 'PENDING'
			AND JOB_TYPE = 'EMAIL'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'NVE1'";
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

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$row['VARIABLE_LIST']."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$body = str_replace("_0_", $short_term_row['VESSEL_NAME'], $body);
		$mailSubject = str_replace("_0_", $short_term_row['VESSEL_NAME'], $mailSubject);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE SET
						DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETE',
						JOB_EMAIL_TO = '".$mailTO."',
						JOB_EMAIL_CC = '".$email_row['CC']."',
						JOB_EMAIL_BCC = '".$email_row['BCC']."',
						JOB_BODY = '".substr($body, 0, 2000)."'
					WHERE
						JOB_ID = '".$row['JOB_ID']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		}
	}