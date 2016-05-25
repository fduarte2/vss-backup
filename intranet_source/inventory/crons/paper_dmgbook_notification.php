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
//	$date = '11/03/2010';

	$output = "<br><table border=\"1\" width=\"60%\" cellpadding=\"4\" cellspacing=\"0\">";
	$output .= "<tr>
					<td><b>Manifest</b></td>
					<td><b>Arrival#</b></td>
					<td><b>PO</b></td>
					<td><b>Order First Received</b></td>
					<td><b>Highest Damage</b></td>
				</tr>";
//	$this_ORD = "";
//	$this_ARV = "";
//	$this_PO = "";
	$send_email = false;

	$sql = "SELECT CT.ARRIVAL_NUM, BAD.BOL, BAD.ORDER_NUM,
				TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI') REC_ON, MAX(SUBSTR(DAMAGE_TYPE, 0, 1)) THE_TYPE 
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_DAMAGES BD
			WHERE REMARK = 'BOOKINGSYSTEM'
			AND CT.PALLET_ID = BAD.PALLET_ID
			AND CT.RECEIVER_ID = BAD.RECEIVER_ID
			AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
			AND BD.PALLET_ID = BAD.PALLET_ID
			AND BD.RECEIVER_ID = BAD.RECEIVER_ID
			AND BD.ARRIVAL_NUM = BAD.ARRIVAL_NUM
			AND TO_CHAR(BD.DATE_ENTERED, 'MM/DD/YYYY') = '".$date."'
			GROUP BY CT.ARRIVAL_NUM, BAD.BOL, BAD.ORDER_NUM
			ORDER BY CT.ARRIVAL_NUM, BAD.BOL, BAD.ORDER_NUM";
	ora_parse($cursor_select, $sql);
	ora_exec($cursor_select);
	while(ora_fetch_into($cursor_select, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// NOTE:  this used to display cargo in a tiered format; said format is no longer used,
		// but the logic is still being kept around in case the specs change.  And it's useful
		// for determining if an email should even be sent or not.
		$output .= "<tr>";

//		if($select_row['BOL'] != $this_ORD){
//			$this_ORD = $select_row['BOL'];
//			$output .= "<td>".$this_ORD."</td>";
			$output .= "<td>".$select_row['BOL']."</td>";
//			$this_ARV = "";
//		} else {
//			$output .= "<td>&nbsp;</td>";
//		}

//		if($select_row['ARRIVAL_NUM'] != $this_ARV){
//			$this_ARV = $select_row['ARRIVAL_NUM'];
//			$output .= "<td>".$this_ARV."</td>";
			$output .= "<td>".$select_row['ARRIVAL_NUM']."</td>";
//		} else {
//			$output .= "<td>&nbsp;</td>";
//		}

//		if($select_row['ORDER_NUM'] != $this_PO){
//			$this_PO = $select_row['ORDER_NUM'];
//			$output .= "<td>".$this_PO."</td>";
			$output .= "<td>".$select_row['ORDER_NUM']."</td>";
//		} else {
//			$output .= "<td>&nbsp;</td>";
//		}

		$output .= "<td>".$select_row['REC_ON']."</td>";
		$output .= "<td>".$select_row['THE_TYPE']."</td></tr>";

		$send_email = true;
	}
	$output .= "<tr><td colspan=\"5\">Norfolk Southern, this is a list of all receipts for Paper that contain Damage & Reject.  If any of the numbers listed in the Arrival Number Column is not a railcar number it does not reflect a receipt via railcar. If this column shows a valid railcar number, BE ADVISED THAT WE ARE NOTIFYING YOU OF DAMAGE AND REJECT RECEIVED ON EACH OF THESE CARS. SPECIFIC DAMAGE & REJECT NOTICE WILL FOLLOW UNDER SEPARATE EMAIL.</td></tr>";
	$output .= "</table>";

	if($send_email == true){  // we only send email if there was actually damage
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'BOOKDR'";
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
						'BOOKDR',
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
