<?
/*
*	Sends out email notifications to Walmart repacked pallets need attention.
*
*	Feb 2011.
****************************************************************************/

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $ARV_cursor = ora_open($conn);
   $WCI_cursor = ora_open($conn);
   $CT_cursor = ora_open($conn);
   $body_cursor = ora_open($conn);
   $ED_cursor = ora_open($conn);
   $short_term_cursor = ora_open($conn);

   $mod_cursor = ora_open($conn);

	$sql = "SELECT DISTINCT WCI.ARRIVAL_NUM, VESSEL_NAME
			FROM WM_CARLE_IMPORT WCI, VESSEL_PROFILE VP
			WHERE WALMART_HATCH_CONVERSION_DATE IS NULL
				AND SHIP_DEPARTED_NO_WALMART_DATE IS NULL
				AND TO_CHAR(VP.LR_NUM) = WCI.ARRIVAL_NUM
			ORDER BY ARRIVAL_NUM";
	ora_parse($ARV_cursor, $sql);
	ora_exec($ARV_cursor);
	if(!ora_fetch_into($ARV_cursor, $ARV_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// No as-of-yet un-hatch-altered pallets.  End script.
	} else {
		do {
			$send_email = false;
			$email_body_replace = ""; // for the resulting email
			$altered_pallets = 0;

			$sql = "SELECT * FROM WM_CARLE_IMPORT
					WHERE WALMART_HATCH_CONVERSION_DATE IS NULL
						AND SHIP_DEPARTED_NO_WALMART_DATE IS NULL
						AND ARRIVAL_NUM = '".$ARV_row['ARRIVAL_NUM']."'
					ORDER BY PALLET_ID";
			ora_parse($WCI_cursor, $sql);
			ora_exec($WCI_cursor);
			if(!ora_fetch_into($WCI_cursor, $WCI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// No pallets for this vessel; not sure why, but just in case this happens, break this loop
			} else {
				do {
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM CARGO_TRACKING
							WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
							AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
							AND RECEIVER_ID = '3000'";
					ora_parse($CT_cursor, $sql);
					ora_exec($CT_cursor);
					ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($CT_row['THE_COUNT'] > 0){
						$send_email = true; // if even 1 pallet matches between the 2 tables, we send some form of email

						$sql = "SELECT NVL(HATCH, 'NONE') THE_HATCH
								FROM CARGO_TRACKING
								WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
								AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
								AND RECEIVER_ID = '3000'";
						ora_parse($body_cursor, $sql);
						ora_exec($body_cursor);
						ora_fetch_into($body_cursor, $body_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$from_hatch = trim($body_row['THE_HATCH']);

						$sql = "SELECT NVL(HATCH, 'NONE') THE_HATCH
								FROM WM_CARLE_IMPORT
								WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
								AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
								AND RECEIVER_ID = '3000'";
						ora_parse($body_cursor, $sql);
						ora_exec($body_cursor);
						ora_fetch_into($body_cursor, $body_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$to_hatch = trim($body_row['THE_HATCH']);

						if($from_hatch != $to_hatch){
							// no need to do this if they are the same.

							$email_body_replace .= "<tr><td>".$WCI_row['PALLET_ID']."</td><td>".$from_hatch."</td><td>".$to_hatch."</td></tr>";

							// update hatch
							$sql = "UPDATE CARGO_TRACKING
									SET HATCH = '".$WCI_row['HATCH']."'
									WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
										AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
										AND RECEIVER_ID = '".$WCI_row['RECEIVER_ID']."'";
							ora_parse($mod_cursor, $sql);
							ora_exec($mod_cursor);

							$sql = "UPDATE WDI_SPLIT_PALLETS
									SET HATCH = '".$WCI_row['HATCH']."'
									WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
										AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
										AND RECEIVER_ID = '".$WCI_row['RECEIVER_ID']."'";
							ora_parse($mod_cursor, $sql);
							ora_exec($mod_cursor);

							$altered_pallets++;
						}

						// we've done what we need to with this pallet, "flag" it for no repeats
						$sql = "UPDATE WM_CARLE_IMPORT
								SET WALMART_HATCH_CONVERSION_DATE = SYSDATE
								WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
									AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
									AND RECEIVER_ID = '".$WCI_row['RECEIVER_ID']."'";
						ora_parse($mod_cursor, $sql);
						ora_exec($mod_cursor);
					}
				} while(ora_fetch_into($WCI_cursor, $WCI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				if($send_email == true){
					// at least 1 pallet was referenced;
					// is it a full-pass, or were there changes?
					if($altered_pallets <= 0){
						// all pallets were matches, proceed with "allgood" email
						$sql = "SELECT * FROM EMAIL_DISTRIBUTION
								WHERE EMAILID = 'WMHATCHMATCH'";
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

						$mailSubject = str_replace("_0_", $ARV_row['ARRIVAL_NUM']."-".$ARV_row['VESSEL_NAME'], $mailSubject);

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
										'CONSTANTCRON',
										SYSDATE,
										'EMAIL',
										'WMHATCHMATCH',
										SYSDATE,
										'COMPLETED',
										'".$mailTO."',
										'".$email_row['CC']."',
										'".$email_row['BCC']."',
										'".substr($body, 0, 2000)."')";
							ora_parse($mod_cursor, $sql);
							ora_exec($mod_cursor);
						}
					} else {
						// there were hatch-alterations done.  Proceed with "oopsie" email.
						$sql = "SELECT * FROM EMAIL_DISTRIBUTION
								WHERE EMAILID = 'WMHATCHNOMATCH'";
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
						$mailheaders .= "Content-Type: text/html\r\n";

						$mailSubject = $email_row['SUBJECT'];

						$body = $email_row['NARRATIVE'];

						$mailSubject = str_replace("_0_", $ARV_row['ARRIVAL_NUM']."-".$ARV_row['VESSEL_NAME'], $mailSubject);

						$body = str_replace("_1_", $altered_pallets, $body);
						$main_body = "<html><body><table border=\"1\"><tr><td>Pallet</td><td>WDI Hatch Deck</td><td>Shipping Line Hatch Deck</td></tr>".$email_body_replace."</table></body></html>";
						$body = str_replace("_2_", $main_body, $body);

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
										'CONSTANTCRON',
										SYSDATE,
										'EMAIL',
										'WMHATCHNOMATCH',
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
				}
			}
		} while(ora_fetch_into($ARV_cursor, $ARV_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	// so we've done all the checks.  Next, lets "cancel out" pallets for vessels WALMART has already released
	$sql = "UPDATE WM_CARLE_IMPORT WCI
			SET SHIP_DEPARTED_NO_WALMART_DATE = SYSDATE
			WHERE SHIP_DEPARTED_NO_WALMART_DATE IS NULL
				AND WALMART_HATCH_CONVERSION_DATE IS NULL
				AND ARRIVAL_NUM IN
				(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
	ora_parse($mod_cursor, $sql);
	ora_exec($mod_cursor);

























/*
	// find all rows in WM_CARLE_IMPORT that are as of yet "unconverted"
	$sql = "SELECT * FROM WM_CARLE_IMPORT
			WHERE WALMART_HATCH_CONVERSION_DATE IS NULL
				AND SHIP_DEPARTED_NO_WALMART_DATE IS NULL
			ORDER BY ARRIVAL_NUM, PALLET_ID";
	ora_parse($WCI_cursor, $sql);
	ora_exec($WCI_cursor);
	if(!ora_fetch_into($WCI_cursor, $WCI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// No as-of-yet un-hatch-altered pallets.  End script.
	} else {
		$email_body_replace = ""; // for the resulting email

		do {
//			echo $WCI_row['ARRIVAL_NUM']."  ".$WCI_row['PALLET_ID']."\n";
//			$update = true;  // by default, we want to update

			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
					AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
					AND RECEIVER_ID = '3000'";
			ora_parse($CT_cursor, $sql);
			ora_exec($CT_cursor);
			ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($CT_row['THE_COUNT'] > 0){
				// this pallet is in CT from both Walmart and Carle files, 
				// and hasn't yet been updated.

				$sql = "SELECT NVL(HATCH, 'NONE') THE_HATCH
						FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
						AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
						AND RECEIVER_ID = '3000'";
				ora_parse($body_cursor, $sql);
				ora_exec($body_cursor);
				ora_fetch_into($body_cursor, $body_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$from_hatch = trim($body_row['THE_HATCH']);

				$sql = "SELECT NVL(HATCH, 'NONE') THE_HATCH
						FROM WM_CARLE_IMPORT
						WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
						AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
						AND RECEIVER_ID = '3000'";
				ora_parse($body_cursor, $sql);
				ora_exec($body_cursor);
				ora_fetch_into($body_cursor, $body_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$to_hatch = trim($body_row['THE_HATCH']);

				if($from_hatch != $to_hatch){
					// no need to do this if they are the same.

					$tabcount = "\t";
					for($i = strlen($WCI_row['PALLET_ID']); $i <= 24; $i += 6){
						$tabcount .= "\t";
					}

					$email_body_replace .= $WCI_row['PALLET_ID'].$tabcount.$WCI_row['ARRIVAL_NUM']."\t\t".$from_hatch."\t\t".$to_hatch."\r\n";

					$sql = "UPDATE CARGO_TRACKING
							SET HATCH = '".$WCI_row['HATCH']."'
							WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
								AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
								AND RECEIVER_ID = '".$WCI_row['RECEIVER_ID']."'";
					ora_parse($mod_cursor, $sql);
					ora_exec($mod_cursor);

					$sql = "UPDATE WDI_SPLIT_PALLETS
							SET HATCH = '".$WCI_row['HATCH']."'
							WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
								AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
								AND RECEIVER_ID = '".$WCI_row['RECEIVER_ID']."'";
					ora_parse($mod_cursor, $sql);
					ora_exec($mod_cursor);

					$sql = "UPDATE WM_CARLE_IMPORT
							SET WALMART_HATCH_CONVERSION_DATE = SYSDATE
							WHERE ARRIVAL_NUM = '".$WCI_row['ARRIVAL_NUM']."'
								AND PALLET_ID = '".$WCI_row['PALLET_ID']."'
								AND RECEIVER_ID = '".$WCI_row['RECEIVER_ID']."'";
					ora_parse($mod_cursor, $sql);
					ora_exec($mod_cursor);
				}			
			}

//			echo "string:  ".$email_body_replace;
		} while(ora_fetch_into($WCI_cursor, $WCI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		if($email_body_replace != ""){
			// at least 1 pallet was altered; therefore, we send an email.

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'WMHATCH'";
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

			$mailSubject = str_replace("_0_", date('m/d/Y h:i'), $mailSubject);

			$main_body = "\r\nPallet\t\t\t\tVessel\told\t\tnew\r\n".$email_body_replace;
			$body = str_replace("_1_", $main_body, $body);

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
							'CONSTANTCRON',
							SYSDATE,
							'EMAIL',
							'WMHATCH',
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
	}


	// next up, we "clear" any pallets of old vessels
	$sql = "UPDATE WM_CARLE_IMPORT
			SET SHIP_DEPARTED_NO_WALMART_DATE = SYSDATE
			WHERE SHIP_DEPARTED_NO_WALMART_DATE IS NULL
				AND WALMART_HATCH_CONVERSION_DATE IS NULL
				AND ARRIVAL_NUM IN
					(SELECT TO_CHAR(LR_NUM)
					FROM VOYAGE
					WHERE (DATE_DEPARTED + 2) < SYSDATE)";
	ora_parse($mod_cursor, $sql);
	ora_exec($mod_cursor);
*/