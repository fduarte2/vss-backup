<?
/*
*	Sends out email notifications to Walmart that a ship can be QC'ed.
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

	$sql = "SELECT * FROM JOB_QUEUE WHERE JOB_DESCRIPTION = 'AQCWDI'
			AND COMPLETION_STATUS = 'PENDING'
			AND JOB_TYPE = 'EMAIL'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$variable_list = split(";", $row['VARIABLE_LIST']);

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'AQCWDI'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

//		$mailTO = $email_row['TO'];
		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['TEST'] == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,fduarte@port.state.de.us,lstewart@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
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
		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
*/
		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE'];

		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, CARGO_TYPE_ID
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$variable_list[0]."'
				AND RECEIVER_ID IN
					(SELECT RECEIVER_ID FROM SCANNER_ACCESS
					WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CARGO_TYPE_ID
				ORDER BY CARGO_TYPE_ID";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$total_so_far[$short_term_row['CARGO_TYPE_ID']] = $short_term_row['THE_COUNT'];
		}

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$variable_list[0]."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $short_term_row['VESSEL_NAME'];


		// attach a file of base pallets
		$xls_file_base = 	"<TABLE border=0 CELLSPACING=1>";
		$xls_file_base .= "<tr><td colspan=1>Pallet ID</td></tr>";
		$sql = "SELECT PALLET_ID FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$variable_list[0]."'
				AND RECEIVER_ID IN
					(SELECT RECEIVER_ID FROM SCANNER_ACCESS
					WHERE VALID_SCANNER = 'WALMART')
				AND CARGO_TYPE_ID = '8'
				ORDER BY PALLET_ID";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$xls_file_base .= "<tr><td colspan=1>No Base Pallets Found</td></tr>";
		} else {
			do {
				$xls_file_base .= "<tr><td colspan=1>".$short_term_row['PALLET_ID']."</td></tr>";
			} while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$xls_file_base .= "</table>";
		$xls_attach_base=chunk_split(base64_encode($xls_file_base));


		// attach a file of offsite pallets
		$xls_file_offsite = 	"<TABLE border=0 CELLSPACING=1>";
		$xls_file_offsite .= "<tr><td colspan=1>Pallet ID</td></tr>";
		$sql = "SELECT PALLET_ID FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$variable_list[0]."'
				AND RECEIVER_ID IN
					(SELECT RECEIVER_ID FROM SCANNER_ACCESS
					WHERE VALID_SCANNER = 'WALMART')
				AND CARGO_TYPE_ID = '9'
				ORDER BY PALLET_ID";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$xls_file_offsite .= "<tr><td colspan=1>No Offsite Pallets Found</td></tr>";
		} else {
			do {
				$xls_file_offsite .= "<tr><td colspan=1>".$short_term_row['PALLET_ID']."</td></tr>";
			} while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$xls_file_offsite .= "</table>";
		$xls_attach_offsite=chunk_split(base64_encode($xls_file_offsite));


		// compile the raw totals
		$total_list = "\r\n";
		$sql = "SELECT CARGO_TYPE_ID, WM_PROGRAM FROM WM_CARGO_TYPE ORDER BY CARGO_TYPE_ID";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$total_list .= $row2['WM_PROGRAM']."  ".$total_so_far[$row2['CARGO_TYPE_ID']]."\r\n";
		}


		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$body = str_replace("_0_", $vessel_name, $body);
		$body = str_replace("_1_", $variable_list[1], $body);
		$body = str_replace("_2_", $total_list, $body);

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"BasePallets.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$xls_attach_base;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"OffSite.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$xls_attach_offsite;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY--\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
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