<?
/*
*	Adam Walter, OCt/Nov 2010.
*
*	Email inventory about paper shorts
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

	// Dolepaper
	$dole_output = "<br><table border=\"1\" width=\"50%\" cellpadding=\"4\" cellspacing=\"0\">";;
	$dole_output .= "<tr>
						<td><b>Railcar</b></td>
						<td><b>Dock Ticket</b></td>
						<td><b>".$date." Received RollCount</b></td>
						<td><b>Short as of ".$date." RollCount</b></td>
					</tr>";
	$this_dole_railcar = "";
	$this_dole_DT = "";

	$sql = "SELECT ARRIVAL_NUM, BOL, 
				SUM(DECODE(DATE_RECEIVED, NULL, 1, 0)) THE_SHORT,
				SUM(DECODE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '".$date."', 1, 0)) THE_REC 
			FROM CARGO_TRACKING
			WHERE REMARK = 'DOLEPAPERSYSTEM'
			AND ARRIVAL_NUM IN
				(SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING
				WHERE REMARK IN ('DOLEPAPERSYSTEM')
				AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$date."')
			GROUP BY ARRIVAL_NUM, BOL
			HAVING SUM(DECODE(DATE_RECEIVED, NULL, 1, 0)) > 0
				AND SUM(DECODE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '".$date."', 1, 0)) > 0
			ORDER BY ARRIVAL_NUM, BOL";
	ora_parse($cursor_select, $sql);
	ora_exec($cursor_select);
	while(ora_fetch_into($cursor_select, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$dole_output .= "<tr>";

		if($select_row['ARRIVAL_NUM'] != $this_dole_railcar){
			$this_dole_railcar = $select_row['ARRIVAL_NUM'];
			$dole_output .= "<td>".$this_dole_railcar."</td>";
			$this_dole_DT = "";
		} else {
			$dole_output .= "<td>&nbsp;</td>";
		}

		if($select_row['BOL'] != $this_dole_DT){
			$this_dole_DT = $select_row['BOL'];
			$dole_output .= "<td>".$this_dole_DT."</td>";
		} else {
			$dole_output .= "<td>&nbsp;</td>";
		}

		$dole_output .= "<td>".$select_row['THE_REC']."</td>
						<td>".$select_row['THE_SHORT']."</td></tr>";
	}
	$dole_output .= "</table>";

	// Booking System
	$booking_output = "<br><table border=\"1\" width=\"50%\" cellpadding=\"4\" cellspacing=\"0\">";;
	$booking_output .= "<tr>
						<td><b>Railcar</b></td>
						<td><b>PO</b></td>
						<td><b>".$date." Received RollCount</b></td>
						<td><b>Short as of ".$date." RollCount</b></td>
					</tr>";
	$this_booking_railcar = "";
	$this_booking_PO = "";

	$sql = "SELECT CT.ARRIVAL_NUM, ORDER_NUM, 
				SUM(DECODE(DATE_RECEIVED, NULL, 1, 0)) THE_SHORT,
				SUM(DECODE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '".$date."', 1, 0)) THE_REC 
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
			WHERE REMARK = 'BOOKINGSYSTEM'
			AND CT.PALLET_ID = BAD.PALLET_ID
			AND CT.RECEIVER_ID = BAD.RECEIVER_ID
			AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
			AND CT.ARRIVAL_NUM IN
				(SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING
				WHERE REMARK IN ('BOOKINGSYSTEM')
				AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$date."')
			GROUP BY CT.ARRIVAL_NUM, ORDER_NUM
			HAVING SUM(DECODE(DATE_RECEIVED, NULL, 1, 0)) > 0
				AND SUM(DECODE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '".$date."', 1, 0)) > 0
			ORDER BY CT.ARRIVAL_NUM, ORDER_NUM";
	ora_parse($cursor_select, $sql);
	ora_exec($cursor_select);
	while(ora_fetch_into($cursor_select, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$booking_output .= "<tr>";

		if($select_row['ARRIVAL_NUM'] != $this_booking_railcar){
			$this_booking_railcar = $select_row['ARRIVAL_NUM'];
			$booking_output .= "<td>".$this_booking_railcar."</td>";
			$this_booking_PO = "";
		} else {
			$booking_output .= "<td>&nbsp;</td>";
		}

		if($select_row['ORDER_NUM'] != $this_booking_PO){
			$this_booking_PO = $select_row['ORDER_NUM'];
			$booking_output .= "<td>".$this_booking_PO."</td>";
		} else {
			$booking_output .= "<td>&nbsp;</td>";
		}

		$booking_output .= "<td>".$select_row['THE_REC']."</td>
							<td>".$select_row['THE_SHORT']."</td></tr>";
	}
	$booking_output .= "</table>";


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PAPRSHRT'";
	ora_parse($cursor_email, $sql);
	ora_exec($cursor_email);
	ora_fetch_into($cursor_email, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

//	if($this_dole_railcar != "" || $this_booking_railcar != ""){ 

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

	$body = "<html><body>".$email_row['NARRATIVE']."</body></html>";

	if($this_dole_railcar != ""){
		$body = str_replace("_0_", $dole_output, $body);
	} else {
		$body = str_replace("_0_", "<br>None.<br>", $body);
	}

	if($this_booking_railcar != ""){
		$body = str_replace("_1_", $booking_output, $body);
	} else {
		$body = str_replace("_1_", "<br>None.<br>", $body);
	}

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
					'PAPRSHRT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($mod_cursor, $sql);
		ora_exec($mod_cursor);
	}
