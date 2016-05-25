<?
/*
*	Adam Walter, Nov 2014
*
*	Sends an "EFaxMail" to the canadian-release recipients.
****************************************************************************************/
 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		exit;
	}

	
	$output_DT = "<table border=\"1\">";
	$output_DT .= "<tr>
						<td><b>Customer</b></td>
						<td><b>Dock Ticket</b></td>
						<td><b>Description</b></td>
						<td><b>Code</b></td>
						<td><b>Rolls Received</b></td>
						<td><b>Date Received</b></td>
						<td><b>Rolls Shipped</b></td>
						<td><b>Rolls In House</b></td>
						<td><b>Rolls In House (Reject)</b></td>
						<td><b>Pending Orders</b></td>
						<td><b>Weight In House</b></td>
					</tr>";
	$sql = "SELECT RECEIVER_ID, BOL, CARGO_DESCRIPTION, BATCH_ID, SUM(QTY_RECEIVED) THE_ORIG, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_REC, SUM(QTY_IN_HOUSE) IN_HOUSE
			FROM CARGO_TRACKING
			WHERE DATE_RECEIVED IS NOT NULL
			AND REMARK = 'DOLEPAPERSYSTEM'
			GROUP BY RECEIVER_ID, BOL, CARGO_DESCRIPTION, BATCH_ID
			HAVING SUM(QTY_IN_HOUSE) > 0
			ORDER BY RECEIVER_ID, BOL";
	$dt_data = ociparse($rfconn, $sql);
	ociexecute($dt_data);
	if(!ocifetch($dt_data)){
		$output_DT .= "<tr><td colspan=11 align=center><b>No Shippable In-House Rolls to display</b></td></tr>";
	} else {
		do { // do this for each dock ticket
			$sql = "SELECT SUM(QTY_SHIP) TO_SHIP FROM DOLEPAPER_DOCKTICKET DD, DOLEPAPER_ORDER DPO WHERE DD.ORDER_NUM = DPO.ORDER_NUM AND DD.DOCK_TICKET = '".ociresult($dt_data, "BOL")."' AND DPO.STATUS NOT IN ('6', '7', '8')";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$pending = ociresult($short_term_data, "TO_SHIP");

			$sql = "SELECT SUM(QTY_DAMAGED) THE_DMG, SUM(WEIGHT) THE_WEIGHT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE > 0 AND BOL = '".ociresult($dt_data, "BOL")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$DMG_Inhouse = ociresult($short_term_data, "THE_DMG");
			$WT_Inhouse = ociresult($short_term_data, "THE_WEIGHT");

			$output_DT .= "<tr>
								<td>".ociresult($dt_data, "RECEIVER_ID")."</td>
								<td>".ociresult($dt_data, "BOL")."</td>
								<td>".ociresult($dt_data, "CARGO_DESCRIPTION")."</td>
								<td>".ociresult($dt_data, "BATCH_ID")."</td>
								<td>".ociresult($dt_data, "THE_ORIG")."</td>
								<td>".ociresult($dt_data, "THE_REC")."</td>
								<td>".(ociresult($dt_data, "THE_ORIG") - ociresult($dt_data, "IN_HOUSE"))."</td>
								<td>".ociresult($dt_data, "IN_HOUSE")."</td>
								<td>".(0 + $DMG_Inhouse)."</td>
								<td>".(0 + $pending)."</td>
								<td>".(0 + $WT_Inhouse)."</td>
							</tr>";
		} while(ocifetch($dt_data));
	}
	$output_DT .= "</table>";

	$output_BK = "<table border=\"1\">";
	$output_BK .= "	<tr>
						<td><b>Customer</b></td>
						<td><b>Booking#</b></td>
						<td><b>Inbound PO</b></td>
						<td><b>Grade Code</b></td>
						<td><b>Width</b></td>
						<td><b>Dia</b></td>
						<td><b>AVG weight (lbs)</b></td>
						<td><b>QTY In House</b></td>
						<td><b>Pending Orders</b></td>
					</tr>";
	$sql = "SELECT CT.RECEIVER_ID, COUNT(DISTINCT CT.PALLET_ID) THE_COUNT, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, SSCC_GRADE_CODE,
				ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, BAD.ORDER_NUM, 
				ROUND(AVG(WEIGHT * UC3.CONVERSION_FACTOR), 1) WEIGHT_LB, ROUND(AVG(WEIGHT * UC4.CONVERSION_FACTOR), 1) WEIGHT_KG,
				SUM(DECODE(CARGO_STATUS, NULL, 0, 1)) THE_HOLDS, 
					COUNT(
						(SELECT DISTINCT PALLET_ID 
						FROM BOOKING_DAMAGES BD 
						WHERE CT.PALLET_ID = BD.PALLET_ID
						AND CT.ARRIVAL_NUM = BD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = BD.RECEIVER_ID
						AND DAMAGE_TYPE LIKE 'R%'
						AND DATE_CLEARED IS NULL)) THE_REJECTS
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, 
				UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4, BOOKING_PAPER_GRADE_CODE BPGC
			WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID 
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' 
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' 
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB' 
				AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
				AND CT.DATE_RECEIVED IS NOT NULL
				AND CT.QTY_IN_HOUSE > 0
				AND CT.REMARK = 'BOOKINGSYSTEM'
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
			GROUP BY CT.RECEIVER_ID, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1), SSCC_GRADE_CODE,
				NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM
			ORDER BY CT.RECEIVER_ID, NVL(BOOKING_NUM, '--NONE--'), ORDER_NUM, SSCC_GRADE_CODE, THE_WIDTH, THE_DIA";
	$bk_data = ociparse($rfconn, $sql);
	ociexecute($bk_data);
	if(!ocifetch($bk_data)){
		$output_BK .= "<tr>
							<td align=center colspan=9><b>No Booking Paper Rolls currently in house.</b></td>
						</tr>";
	} else {
		do {
			$sql = "SELECT SUM(QTY_TO_SHIP) TO_SHIP 
					FROM BOOKING_ORDER_DETAILS BOD, BOOKING_ORDERS BO 
					WHERE BO.ORDER_NUM = BOD.ORDER_NUM 
						AND BO.STATUS NOT IN ('6', '7', '8')
						AND BOD.P_O = '".ociresult($bk_data, 'ORDER_NUM')."' 
						AND BOD.BOOKING_NUM = '".ociresult($bk_data, 'THE_BOOK')."' 
						AND BOD.WIDTH = '".ociresult($bk_data, 'THE_WIDTH')."' 
						AND BOD.DIA = '".ociresult($bk_data, 'THE_DIA')."' 
						AND BOD.SSCC_GRADE_CODE = '".ociresult($bk_data, 'SSCC_GRADE_CODE')."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$pending = ociresult($short_term_data, 'TO_SHIP');
/*
			$total_IH += $row['THE_COUNT'];


			if($row['THE_HOLDS'] <= 0){
				$hold_data = "";
			} else {
				$total_IH_hold += $row['THE_HOLDS'];
				$hold_data = "<br>(".$row['THE_HOLDS']." on hold)";
			}
			if($row['THE_REJECTS'] <= 0){
				$reject_print = "";
			} else {
				$total_IH_reject += $row['THE_REJECTS'];
				$reject_print = "<br>--- ".$row['THE_REJECTS']." Reject";
			}
*/
			$output_BK .= "<tr>
								<td>".ociresult($bk_data, 'RECEIVER_ID')."</td>
								<td>".ociresult($bk_data, 'THE_BOOK')."</td>
								<td>".ociresult($bk_data, 'ORDER_NUM')."</td>
								<td>".ociresult($bk_data, 'SSCC_GRADE_CODE')."</td>
								<td>".ociresult($bk_data, 'THE_WIDTH')."cm/".round(ociresult($bk_data, 'THE_WIDTH') / 2.54, 1)."\"</td>
								<td>".ociresult($bk_data, 'THE_DIA')."cm/".round(ociresult($bk_data, 'THE_DIA') / 2.54, 1)."\"</td>
								<td>".ociresult($bk_data, 'WEIGHT_LB')."</td>
								<td>".ociresult($bk_data, 'THE_COUNT')."</td>
								<td>".(0 + $pending)."</td>
							</tr>";
		}while(ocifetch($bk_data));
	}

	$output_BK .= "</table>";


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PAPERROLLSINHSE'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = ociresult($email, "SUBJECT");
//	$mailSubject = str_replace("_0_", $vesname, $mailSubject);

	$body = ociresult($email, "NARRATIVE");

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$attach1=chunk_split(base64_encode($output_DT));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"DockTicket.xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach1;
	$Content.="\r\n";

	$attach2=chunk_split(base64_encode($output_BK));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"Booking.xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach2;
	$Content.="\r\n";

	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
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
					'TRUCKTIMEREPORT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
