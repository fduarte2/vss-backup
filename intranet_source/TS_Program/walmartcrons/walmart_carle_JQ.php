<?
/*
*	Adam Walter, OCt/Nov 2010.
*
*	Job Queue for the matching of carle chilean files to
*	GFS-Walmart's.
*************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_select = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT * FROM JOB_QUEUE JQ, EMAIL_DISTRIBUTION ED
			WHERE JQ.JOB_DESCRIPTION = ED.EMAILID
			AND JQ.JOB_DESCRIPTION = 'NOMTCH1'
			AND COMPLETION_STATUS = 'PENDING'";
	ora_parse($cursor_select, $sql);
	ora_exec($cursor_select);
	while(ora_fetch_into($cursor_select, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$not_carle_count = 0;
		$not_walmart_count = 0;

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
		}*/
		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE'];

		$vessel = $email_row['VARIABLE_LIST'];

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $Short_Term_row['VESSEL_NAME'];

		$body = str_replace("_0_", $vessel_name, $body);

		$carle_not_walmart = "";
		$sql = "SELECT PALLET_ID FROM WM_CARLE_IMPORT
				WHERE ARRIVAL_NUM = '".$vessel."'
				AND PALLET_ID NOT IN
					(SELECT PALLET_ID
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".$vessel."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$carle_not_walmart .= "\r\n".$Short_Term_row['PALLET_ID'];
			$not_walmart_count++;
		}
		if($carle_not_walmart == ""){
			$carle_not_walmart = "None.\r\n";
		} else {
			$carle_not_walmart .= "\r\n\r\n";
		}

		$walmart_not_carle = "";
		$sql = "SELECT PALLET_ID FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."'
				AND RECEIVER_ID = '3000'
				AND PALLET_ID NOT IN
					(SELECT PALLET_ID
					FROM WM_CARLE_IMPORT
					WHERE ARRIVAL_NUM = '".$vessel."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$walmart_not_carle .= "\r\n".$Short_Term_row['PALLET_ID'];
			$not_carle_count++;
		}
		if($walmart_not_carle == ""){
			$walmart_not_carle = "None.\r\n";
		} else {
			$walmart_not_carle .= "\r\n\r\n";
		}

		$body = str_replace("_1_", $not_walmart_count, $body);
		$body = str_replace("_2_", $carle_not_walmart, $body);
		$body = str_replace("_3_", $not_carle_count, $body);
		$body = str_replace("_4_", $walmart_not_carle, $body);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE
					SET DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETE',
						JOB_EMAIL_TO = '".$mailTO."',
						JOB_EMAIL_CC = '".$email_row['CC']."',
						JOB_EMAIL_BCC = '".$email_row['BCC']."',
						JOB_BODY = '".substr($body, 0, 2000)."'
					WHERE JOB_ID = '".$email_row['JOB_ID']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		}
	}
