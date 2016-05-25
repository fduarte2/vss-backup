<?
/*
*	Adam Walter, OCt/Nov 2010.
*
*	Email inventory about paper damages
*************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_select = ora_open($conn);
	$cursor_email = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$date = date('m/d/Y');
//	$date = '11/10/2010';
/*
	$sql = "SELECT DISTINCT ARRIVAL_NUM, DOCK_TICKET
			FROM CARGO_TRACKING CT, DOLEPAPER_DAMAGES DD
			WHERE CT.PALLET_ID = DD.ROLL
				AND CT.BOL = DD.DOCK_TICKET
				AND CT.RECEIVER_ID = DD.CUSTOMER_ID
				AND TO_CHAR(DD.DATE_ENTERED, 'MM/DD/YYYY') = '".$date."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
*/

/*
					<td><b>QTY</b></td>
					<td><b>Date/Time</b></td>
					<td><b>Responsibility</b></td>
*/
	$output = "<br><table border=\"1\" width=\"60%\" cellpadding=\"4\" cellspacing=\"0\">";
	$output .= "<tr>
					<td><b>Manifest</b></td>
					<td><b>Dock Ticket</b></td>
					<td><b>Arrival#</b></td>
					<td><b>Order First Received</b></td>
				</tr>";
	$this_ORD = "";
	$this_DT = "";
	$this_ARV = "";

	// DD.DMG_TYPE, DD.QUANTITY || DD.QTY_TYPE THE_TYPE, DD.OCCURRED, DD.DAMAGE_ID, CT.PALLET_ID
	$sql = "SELECT CT.BOL, CT.CARGO_DESCRIPTION, CT.ARRIVAL_NUM, 
				TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI') REC_ON
			FROM CARGO_TRACKING CT, DOLEPAPER_DAMAGES DD, PAPER_DAMAGE_CODES PDC
			WHERE CT.PALLET_ID = DD.ROLL
				AND CT.BOL = DD.DOCK_TICKET
				AND CT.RECEIVER_ID = DD.CUSTOMER_ID
				AND TO_CHAR(DD.DATE_ENTERED, 'MM/DD/YYYY') = '".$date."'
				AND DD.DMG_TYPE = PDC.DAMAGE_CODE
				AND PDC.REJECT_LEVEL = 'REJECT'
			GROUP BY CT.BOL, CT.CARGO_DESCRIPTION, CT.ARRIVAL_NUM
			ORDER BY CT.BOL, CT.CARGO_DESCRIPTION, CT.ARRIVAL_NUM";
	ora_parse($cursor_select, $sql);
	ora_exec($cursor_select);
	while(ora_fetch_into($cursor_select, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// NOTE:  this used to display cargo in a tiered format; said format is no longer used,
		// but the logic is still being kept around in case the specs change.  And it's useful
		// for determining if an email should even be sent or not.
		$output .= "<tr>";

		$temp = split(" ", $select_row['CARGO_DESCRIPTION']);
		if($temp[2] != $this_ORD){
			$this_ORD = $temp[2];
			$output .= "<td>".$this_ORD."</td>";
			$this_DT = "";
			$this_ARV = "";
		} else {
			$output .= "<td>&nbsp;</td>";
		}

		if($select_row['BOL'] != $this_DT){
			$this_DT = $select_row['BOL'];
			$output .= "<td>".$this_DT."</td>";
			$this_ARV = "";
		} else {
			$output .= "<td>&nbsp;</td>";
		}

		if($select_row['ARRIVAL_NUM'] != $this_ARV){
			$this_ARV = $select_row['ARRIVAL_NUM'];
			$output .= "<td>".$this_ARV."</td>";
		} else {
			$output .= "<td>&nbsp;</td>";
		}
/*
						<td>".$select_row['THE_TYPE']."</td>
						<td>".$select_row['DMG_ON']."</td>
						<td>".$select_row['OCCURRED']."</td>
*/
		$output .= "<td>".$select_row['REC_ON']."</td></tr>";



/*
		if($select_row['VARIETY'] != $this_ORD){
			$this_ORD = $select_row['VARIETY'];
			$output .= "<tr><td colspan=\"7\">".$this_ORD."</td></tr>";
		}
		if($select_row['BOL'] != $this_DT){
			$this_DT = $select_row['BOL'];
			$output .= "<tr><td>&nbsp;</td><td colspan=\"6\">".$this_DT."</td></tr>";
		}
		if($select_row['PALLET_ID'] != $this_plt){
			$this_plt = $select_row['PALLET_ID'];
			$output .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=\"5\">".$this_plt."</td></tr>";
		}

		$output .= "<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>".$select_row['DMG_TYPE']."</td>
						<td>".$select_row['THE_TYPE']."</td>
						<td>".$select_row['DMG_ON']."</td>
						<td>".$select_row['OCCURRED']."</td>
					</tr>";
*/
	}

	$output .= "<tr><td colspan=\"5\">Norfolk Southern, this is a list of all receipts for Paper that contain Damage & Reject.  If any of the numbers listed in the Arrival Number Column is not a railcar number it does not reflect a receipt via railcar. If this column shows a valid railcar number, BE ADVISED THAT WE ARE NOTIFYING YOU OF DAMAGE AND REJECT RECEIVED ON EACH OF THESE CARS. SPECIFIC DAMAGE & REJECT NOTICE WILL FOLLOW UNDER SEPARATE EMAIL.</td></tr>";
	$output .= "</table>";

	if($this_ORD != ""){  // we only send email if there was actually damage
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PAPERDR'";
		ora_parse($cursor_email, $sql);
		ora_exec($cursor_email);
		ora_fetch_into($cursor_email, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

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
		$mailSubject = str_replace("_1_", $date, $mailSubject);

		$body = "<html><body>".$email_row['NARRATIVE']."</body></html>";

		$body = str_replace("_0_", $output, $body);

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
						'PAPERDR',
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
