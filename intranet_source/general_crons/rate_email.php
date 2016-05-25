<?
/*
*	Adam Walter, Mar 2012.
*
*	Email notification about rate changes
*************************************************************************/

//	$bni_conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	$bni_conn = ocilogon("SAG_OWNER", "SAG", "BNI");

//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");


	$stid = ociparse($bni_conn, "SELECT DECODE(AUDIT_FLAG, 'AFTER UPD', 'UPDATED', AUDIT_FLAG) THE_ACTION, TO_CHAR(AUDIT_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_SUBMIT,
									AUDIT_USERNAME, ROW_NUM, DESCRIPTION, CUSTOMERID, COMMODITYCODE, RATEPRIORITY, FRSHIPPINGLINE, TOSHIPPINGLINE, ARRIVALNUMBER,
									ARRIVALTYPE, FREEDAYS, WEEKENDS, HOLIDAYS, BILLDURATION, BILLDURATIONUNIT, RATESTARTDATE, RATE, SERVICECODE, UNIT, STACKING,
									WAREHOUSE, BOX, BILLTOCUSTOMER, XFRDAYCREDIT, SCANNEDORUNSCANNED, SPECIALRETURN
								FROM ALL_RATE_AUDIT
								WHERE EMAIL_SENT IS NULL
									AND AUDIT_FLAG IN ('AFTER UPD', 'INSERTED', 'DELETED')
								ORDER BY AUDIT_TIME ASC");
	ociexecute($stid);
	while(ocifetch($stid)){
		$action = ociresult($stid, "THE_ACTION");
		$type = ociresult($stid, "DESCRIPTION");
		$on = ociresult($stid, "THE_SUBMIT");
		$by = ociresult($stid, "AUDIT_USERNAME");
		$row_num = ociresult($stid, "ROW_NUM");

		$cust = ociresult($stid, "CUSTOMERID");
		$comm = ociresult($stid, "COMMODITYCODE");
		$priority = ociresult($stid, "RATEPRIORITY");
		$fr_line = ociresult($stid, "FRSHIPPINGLINE");
		$to_line = ociresult($stid, "TOSHIPPINGLINE");
		$LR = ociresult($stid, "ARRIVALNUMBER");
		$arv_type = ociresult($stid, "ARRIVALTYPE");
		$freedays = ociresult($stid, "FREEDAYS");
		$weekends = ociresult($stid, "WEEKENDS");
		$holidays = ociresult($stid, "HOLIDAYS");
		$duration = ociresult($stid, "BILLDURATION");
		$duration_unit = ociresult($stid, "BILLDURATIONUNIT");
		$start_date = ociresult($stid, "RATESTARTDATE");
		$rate = ociresult($stid, "RATE");
		$service = ociresult($stid, "SERVICECODE");
		$unit = ociresult($stid, "UNIT");
		$stacking = ociresult($stid, "STACKING");
		$warehouse = ociresult($stid, "WAREHOUSE");
		$box = ociresult($stid, "BOX");
		$bill_to = ociresult($stid, "BILLTOCUSTOMER");
		$xfer_credit = ociresult($stid, "XFRDAYCREDIT");
		$scanned = ociresult($stid, "SCANNEDORUNSCANNED");
		$specialreturn = ociresult($stid, "SPECIALRETURN");

		$output = "<br><table border=\"1\" width=\"50%\" cellpadding=\"4\" cellspacing=\"0\">";
		$output .= "<tr>
						<td>Action</td>
						<td>".$action."</td>
					</tr>";
		$output .= "<tr>
						<td>Type</td>
						<td>".$type."</td>
					</tr>";
		$output .= "<tr>
						<td>On</td>
						<td>".$on."</td>
					</tr>";
		$output .= "<tr>
						<td>By</td>
						<td>".$by."</td>
					</tr>";
		$output .= "<tr>
						<td>Row#</td>
						<td>".$row_num."</td>
					</tr>";
		$output .= "<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Customer#</td>
						<td>".$cust."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Commodity#</td>
						<td>".$comm."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Rate Priority</td>
						<td>".$priority."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>From-Line</td>
						<td>".$fr_line."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>To-line#</td>
						<td>".$to_line."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Arrival#</td>
						<td>".$LR."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Arrival Type</td>
						<td>".$arv_type."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Free Days</td>
						<td>".$freedays."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Weekends?</td>
						<td>".$weekends."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Holidays?</td>
						<td>".$holidays."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Bill Duration</td>
						<td>".$duration."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Bill Duration Unit</td>
						<td>".$duration_unit."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Minimum # of storage days before this rate takes effect</td>
						<td>".$start_date."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Rate</td>
						<td>".$rate."</td>
					</tr>";
		$output .= "<tr>
						<td>Billed Service Code</td>
						<td>".$service."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Bill-By unit</td>
						<td>".$unit."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Stacking?</td>
						<td>".$stacking."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Warehouse</td>
						<td>".$warehouse."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Box</td>
						<td>".$box."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Bill-To Customer?</td>
						<td>".$bill_to."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Credit Stored days on transfer?</td>
						<td>".$xfer_credit."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Bill From Scanned or Unscanned system?</td>
						<td>".$scanned."&nbsp;</td>
					</tr>";
		$output .= "<tr>
						<td>Special Return</td>
						<td>".$specialreturn."&nbsp;</td>
					</tr>";
		$output .= "</table>";

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'RATECHANGE'";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailTO = ociresult($email, "TO");
		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}

		$mailheaders .= "Content-Type: text/html\r\n";

		$mailSubject = ociresult($email, "SUBJECT");

		$body = "<html><body>".ociresult($email, "NARRATIVE")."</body></html>";
		$body = str_replace("_0_", $by, $body);
		$body = str_replace("_1_", $output, $body);

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
						'RATECHANGE',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rf_conn, $sql);
			ociexecute($email);

			$sql = "UPDATE ALL_RATE_AUDIT
					SET EMAIL_SENT = SYSDATE
					WHERE EMAIL_SENT IS NULL
						AND AUDIT_FLAG IN ('AFTER UPD', 'INSERTED', 'DELETED')
						AND ROW_NUM = '".$row_num."'";
			$email = ociparse($bni_conn, $sql);
			ociexecute($email);
		}
	}