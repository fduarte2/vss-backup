<?
/*
*	Sends out email notifications to Walmart -
*		Lucca Pallets exist in their name.
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

   $body_string = "";

//			AND DATE_RECEIVED IS NOT NULL

	$sql = "SELECT NVL(VESSEL_NAME, 'TRUCK') THE_VES, PALLET_ID
			FROM CARGO_TRACKING CT, VESSEL_PROFILE VP, WM_CARGO_TYPE WCT
			WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
			AND CT.RECEIVER_ID = '3000'
			AND QTY_IN_HOUSE > 0
			AND DATE_RECEIVED IS NOT NULL
			AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
			AND WCT.WM_PROGRAM = 'OFFSITE'
			ORDER BY THE_VES, PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// end script
	} else {

		do {
			$body_string .= $row['PALLET_ID']." (".$row['THE_VES'].")\r\n";
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'ALSPPWDI'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['TEST'] == "Y"){
			$mailTO = "martym@port.state.de.us,ltreut@port.state.de.us";
			$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
			$mailheaders .= "Bcc: archive@port.state.de.us,sadu@port.state.de.us,awalter@port.state.de.us\r\n";
		} else {
			$mailTO = $email_row['TO'];
			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
		}

		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE'];

		$body = str_replace("_0_", $body_string, $body);

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
						'ALSPPWDI',
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