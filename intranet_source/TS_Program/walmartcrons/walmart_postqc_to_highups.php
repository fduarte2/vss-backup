<?
/*
*	Sends out email notifications to Walmart -
*		Notification to WM bigwigs on a QC release.
*
*	Mar 2014.
****************************************************************************/

//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("Please try later!");
		exit;
	}
	$cursor = ora_open($conn);
	$cursor2 = ora_open($conn);
	$CT_cursor = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	$sql = "SELECT JOB_ID, VARIABLE_LIST, TO_CHAR(SUBMISSION_DATETIME, 'MM/DD/YYYY HH24:MI') THE_DATE
			FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION = 'WMAFTERQCRLS'
				AND COMPLETION_STATUS = 'PENDING'
				AND JOB_TYPE = 'EMAIL'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$vessel = $row['VARIABLE_LIST'];
		$submission = $row['THE_DATE'];

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$ves_name = $short_term_row['VESSEL_NAME'];

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'WMAFTERQCRLS'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['TEST'] == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,fduarte@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = $email_row['TO'];
			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
		}
		$mailheaders .= "Content-Type: text/html\r\n";

		$mailSubject = $email_row['SUBJECT'];
		$mailSubject = str_replace("_0_", $vessel." - ".$ves_name, $mailSubject);
		$mailSubject = str_replace("_1_", $submission, $mailSubject);

		$body = $email_row['NARRATIVE'];

		$this_nohold_plts = 0;
		$this_nohold_ctns = 0;

		$output_nohold = "<table border=1>
						<tr>
							<td>WM Item #</td>
							<td># of Pallets</td>
							<td># of Cartons</td>
							<td>Product Name</td>
						</tr>";

		$sql = "SELECT CT.BOL, COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, NVL(UPPER(WIM.VARIETY), 'UNKNOWN') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.BOL, NVL(UPPER(WIM.VARIETY), 'UNKNOWN')
				ORDER BY CT.BOL";
		ora_parse($CT_cursor, $sql);
		ora_exec($CT_cursor);
		while(ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$this_nohold_plts += $CT_row['THE_PLTS'];
			$this_nohold_ctns += $CT_row['THE_CTNS'];
			$output_nohold .= "<tr>
									<td>".$CT_row['BOL']."</td>
									<td>".$CT_row['THE_PLTS']."</td>
									<td>".$CT_row['THE_CTNS']."</td>
									<td>".$CT_row['THE_VAR']."</td>
								</tr>";
		}
		$output_nohold .= "</table><br><br>";

		$this_hold_plts = 0;
		$this_hold_ctns = 0;

		$output_hold = "<table border=1>
						<tr>
							<td>WM Item #</td>
							<td># of Pallets</td>
							<td># of Cartons</td>
							<td>Product Name</td>
							<td>Held By</td>
						</tr>";

		// DEC 2015.  USDA holds now in play, must get them in too
		// so, 3 SQLs here
		$sql = "SELECT CT.BOL, COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, NVL(UPPER(WIM.VARIETY), 'UNKNOWN') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND CT.CARGO_STATUS = 'HOLD'
					AND USDA_HOLD IS NULL
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.BOL, NVL(UPPER(WIM.VARIETY), 'UNKNOWN')
				ORDER BY CT.BOL";
		ora_parse($CT_cursor, $sql);
		ora_exec($CT_cursor);
		if(!ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//			$output_hold .= "<tr><td colspan=\"4\">NO cargo has been placed on hold yet by QC for this vessel</td></tr>";
		} else {
			do {
				$this_hold_plts += $CT_row['THE_PLTS'];
				$this_hold_ctns += $CT_row['THE_CTNS'];
				$output_hold .= "<tr>
										<td>".$CT_row['BOL']."</td>
										<td>".$CT_row['THE_PLTS']."</td>
										<td>".$CT_row['THE_CTNS']."</td>
										<td>".$CT_row['THE_VAR']."</td>
										<td>WALMART</td>
									</tr>";
			} while(ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		$sql = "SELECT CT.BOL, COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, NVL(UPPER(WIM.VARIETY), 'UNKNOWN') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
					AND USDA_HOLD = 'Y'
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.BOL, NVL(UPPER(WIM.VARIETY), 'UNKNOWN')
				ORDER BY CT.BOL";
		ora_parse($CT_cursor, $sql);
		ora_exec($CT_cursor);
		if(!ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//			$output_hold .= "<tr><td colspan=\"4\">NO cargo has been placed on hold yet by QC for this vessel</td></tr>";
		} else {
			do {
				$this_hold_plts += $CT_row['THE_PLTS'];
				$this_hold_ctns += $CT_row['THE_CTNS'];
				$output_hold .= "<tr>
										<td>".$CT_row['BOL']."</td>
										<td>".$CT_row['THE_PLTS']."</td>
										<td>".$CT_row['THE_CTNS']."</td>
										<td>".$CT_row['THE_VAR']."</td>
										<td>USDA</td>
									</tr>";
			} while(ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		$sql = "SELECT CT.BOL, COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, NVL(UPPER(WIM.VARIETY), 'UNKNOWN') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND CT.CARGO_STATUS = 'HOLD'
					AND USDA_HOLD = 'Y'
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.BOL, NVL(UPPER(WIM.VARIETY), 'UNKNOWN')
				ORDER BY CT.BOL";
		ora_parse($CT_cursor, $sql);
		ora_exec($CT_cursor);
		if(!ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//			$output_hold .= "<tr><td colspan=\"4\">NO cargo has been placed on hold yet by QC for this vessel</td></tr>";
		} else {
			do {
				$this_hold_plts += $CT_row['THE_PLTS'];
				$this_hold_ctns += $CT_row['THE_CTNS'];
				$output_hold .= "<tr>
										<td>".$CT_row['BOL']."</td>
										<td>".$CT_row['THE_PLTS']."</td>
										<td>".$CT_row['THE_CTNS']."</td>
										<td>".$CT_row['THE_VAR']."</td>
										<td>WALMART + USDA</td>
									</tr>";
			} while(ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

		$output_hold .= "</table><br><br>";



		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS 
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '3000'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$distinct_pallet_count = $short_term_row['THE_PLTS'];
		$cartons = $short_term_row['THE_CTNS'];

		$body = str_replace("_2_", $distinct_pallet_count." pallets, ".$cartons." cartons", $body);
		$body = str_replace("_3_", $this_nohold_plts." pallets, ".$this_nohold_ctns." cartons", $body);
		$body = str_replace("_4_", "<html><body>".$output_nohold."</body></html>", $body);
		$body = str_replace("_br_", "<br>", $body);
//		echo "hold: ".$this_hold_plts."\n";
		if($this_hold_plts > 0){
//			$body .= "The following cargo has been placed on hold by QC for this vessel --- total of _5_:<br>_6_";
			$body .= "The following cargo has been placed on hold for this vessel --- total of ".$this_hold_plts." pallets, ".$this_hold_ctns." cartons:<br><html><body>".$output_hold."</body></html>";
		} else {
			$body .= "NO cargo has been placed on hold yet by QC for this vessel";
		}
//			$body = str_replace("_5_", $this_hold_plts." pallets, ".$this_hold_ctns." cartons", $body);
//			$body = str_replace("_6_", "<html><body>".$output_hold."</body></html>", $body);

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