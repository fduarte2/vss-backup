<?
/*
*	Adam Walter, Nov 2013
*
*	ends a daily email to OHL about clementine vessel cargo in house, and shipped.
*************************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'OHLREPORT'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = ociresult($email, "SUBJECT");

	$body = ociresult($email, "NARRATIVE");


//	$sql = "SELECT DECODE(RECEIVER_ID, '439', 'DOMINION-CITRUS', 'BURNAC') THE_CUST, CT.ARRIVAL_NUM, MAX(DATE_OF_ACTIVITY) THE_DATE, MAX(QTY_IN_HOUSE) IN_HOUSE
//			GROUP BY CT.ARRIVAL_NUM, DECODE(RECEIVER_ID, '439', 'DOMINION-CITRUS', 'BURNAC')
//			ORDER BY CT.ARRIVAL_NUM, DECODE(RECEIVER_ID, '439', 'DOMINION-CITRUS', 'BURNAC')";
	$sql = "SELECT DECODE(RECEIVER_ID, '835', 'IPPOLITO', 'BURNAC') THE_CUST, CT.ARRIVAL_NUM, MAX(DATE_OF_ACTIVITY) THE_DATE, MAX(QTY_IN_HOUSE) IN_HOUSE
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.COMMODITY_CODE LIKE '56%'
			AND CT.COMMODITY_CODE != '5606'
			AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
			AND CT.RECEIVER_ID = CA.CUSTOMER_ID
			AND CT.PALLET_ID = CA.PALLET_ID
			AND CT.DATE_RECEIVED IS NOT NULL
			AND CT.ARRIVAL_NUM != '4321'
			GROUP BY CT.ARRIVAL_NUM, DECODE(RECEIVER_ID, '835', 'IPPOLITO', 'BURNAC')
			HAVING (MAX(QTY_IN_HOUSE) > 10 
					OR
					MAX(DATE_OF_ACTIVITY) > TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')
				   )
			ORDER BY CT.ARRIVAL_NUM, DECODE(RECEIVER_ID, '835', 'IPPOLITO', 'BURNAC')";
	$spreadsheets = ociparse($rfconn, $sql);
	ociexecute($spreadsheets);
	if(!ocifetch($spreadsheets)){
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= "No Current Vessels to Display.";
		$Content.="\r\n";
	} else {
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		do {
			$vessel = ociresult($spreadsheets, "ARRIVAL_NUM");
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$vesname = $vessel." - ".ociresult($short_term_data, "VESSEL_NAME");

			$output = "<table><tr>
							<td colspan=\"7\">Customer: ".ociresult($spreadsheets, "THE_CUST")."<br>Vessel: ".$vesname."</td></tr>";
			$output .= "<tr>
							<td>BOL</td>
							<td>Size</td>
							<td>T&E</td>
							<td>Order#</td>
							<td>Beginning Carton Count</td>
							<td>Cartons Shipped</td>
							<td>Cartons Remaining</td>
						</tr>";

			if(ociresult($spreadsheets, "THE_CUST") == "IPPOLITO"){
//				$sql_extra = "RECEIVER_ID = '439'";
				$sql_extra = "RECEIVER_ID = '835'";
			} else {
//				$sql_extra = "RECEIVER_ID != '439'";
				$sql_extra = "RECEIVER_ID != '835'";
			}
			$sql = "SELECT SUM(QTY_RECEIVED) THE_START, NVL(BOL, 'NO BOL') THE_BOL, CARGO_SIZE
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND COMMODITY_CODE != '5606'
						AND COMMODITY_CODE LIKE '56%'
						AND DATE_RECEIVED IS NOT NULL
						AND ".$sql_extra." 
					GROUP BY NVL(BOL, 'NO BOL'), CARGO_SIZE
					ORDER BY NVL(BOL, 'NO BOL'), CARGO_SIZE";
			$bols = ociparse($rfconn, $sql);
			ociexecute($bols);
			while(ocifetch($bols)){
				$total = ociresult($bols, "THE_START");
				$output .= "<tr>
								<td>".ociresult($bols, "THE_BOL")."</td>
								<td>".ociresult($bols, "CARGO_SIZE")."</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>".ociresult($bols, "THE_START")."</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>";
				$sql = "SELECT T_E_FILE, BOL_EQUIV, SUM(QTY_CHANGE) THE_CTNS
						FROM CARGO_ACTIVITY CA, CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
						WHERE CA.ARRIVAL_NUM = CMD.ARRIVAL_NUM
							AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
							AND CMD.CLR_KEY = CTMJ.MAIN_CLR_KEY
							AND CA.ORDER_NUM = CMD.BOL_EQUIV
							AND CA.ARRIVAL_NUM = '".$vessel."'
							AND CA.SERVICE_CODE = '6'
							AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
							AND (CA.PALLET_ID, CA.ARRIVAL_NUM, CA.CUSTOMER_ID) IN
								(SELECT PALLET_ID, ARRIVAL_NUM, CUSTOMER_ID
								FROM CARGO_TRACKING
								WHERE ARRIVAL_NUM = '".$vessel."'
									AND COMMODITY_CODE != '5606'
									AND COMMODITY_CODE LIKE '56%'
									AND DATE_RECEIVED IS NOT NULL
									AND ".$sql_extra." 
									AND BOL = '".ociresult($bols, "THE_BOL")."'
									AND CARGO_SIZE = '".ociresult($bols, "CARGO_SIZE")."'
								)
						GROUP BY T_E_FILE, BOL_EQUIV
						ORDER BY T_E_FILE, BOL_EQUIV";
				$orders = ociparse($rfconn, $sql);
				ociexecute($orders);
				while(ocifetch($orders)){
					$output .= "<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>".ociresult($orders, "T_E_FILE")."</td>
									<td>".ociresult($orders, "BOL_EQUIV")."</td>
									<td>&nbsp;</td>
									<td>".ociresult($orders, "THE_CTNS")."</td>
									<td>&nbsp;</td>
								</tr>";
					$total -= ociresult($orders, "THE_CTNS");
				}
				$output .= "<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>".$total."</td>
							</tr>";
				$output .= "<tr><td colspan=\"7\">&nbsp;</td></tr>";
			}
			$output .= "</table>";

			$File=chunk_split(base64_encode($output));
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/xls; name=\"".$vessel.ociresult($spreadsheets, "THE_CUST").date('mdY').".xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$File;
			$Content.="\r\n";
		} while(ocifetch($spreadsheets));
	}

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
					'OHLREPORT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
//		echo $sql."\n";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
	