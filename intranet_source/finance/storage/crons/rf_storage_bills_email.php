<?
/*
*	Aug 2012
*
*	This is a cron task generating from "eport2"
*	designed to mimic one of the existing ones, but with more data
*	For specific clementine orders
*********************************************************************/
//	echo "yo\r\n";
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$G_total_all = 0;
	$G_total_first = 0;
	$G_total_continue = 0;

	$output = "<table border=\"1\">
					<tr>
						<td>Customer</td>
						<td>Arrival Type</td>
						<td>Vessel</td>
						<td>Commodity</td>
						<td>Total Pallets Prebilled</td>
						<td>Pallets - Initial Storage</td>
						<td>Pallets - Ongoing Storage</td>
					</tr>";
	// VESSELS!
	$sql = "SELECT RB.CUSTOMER_ID, RB.ARRIVAL_NUM, RB.COMMODITY_CODE, COUNT(*) THE_PLTS,
				SUM(DECODE(RBD.SERVICE_DESCRIPTION, 'PUT INTO STORAGE', 1, 0)) FIRST_BILLS, SUM(DECODE(RBD.SERVICE_DESCRIPTION, 'PUT INTO STORAGE', 0, 1)) CONTINUE_BILLS
			FROM RF_BILLING RB, RF_BILLING_DETAIL RBD
			WHERE RB.BILLING_NUM = RBD.SUM_BILL_NUM
				AND RB.ARRIVAL_NUM IN
					(SELECT TO_CHAR(LR_NUM) FROM VESSEL_PROFILE)
				AND RBD.SERVICE_STATUS != 'DELETED'
				AND RB.SERVICE_STATUS = 'PREINVOICE'
				AND TO_CHAR(RB.CREATED_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."'
			GROUP BY RB.CUSTOMER_ID, RB.ARRIVAL_NUM, RB.COMMODITY_CODE
			ORDER BY RB.CUSTOMER_ID, RB.ARRIVAL_NUM, RB.COMMODITY_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$output .= "<tr><td align=\"center\" colspan=\"7\">No Vessel Cargo bills.</td></tr>";
	} else {
		$total_all = 0;
		$total_first = 0;
		$total_continue = 0;
		do {

			$sql = "SELECT NVL(VESSEL_NAME, 'UNKNOWN') THE_VES FROM VESSEL_PROFILE WHERE LR_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'";
			$ves_cur = ociparse($rfconn, $sql);
			ociexecute($ves_cur);
			ocifetch($ves_cur);
			$ves_name = ociresult($stid, "ARRIVAL_NUM")."-".ociresult($ves_cur, "THE_VES");

			$sql = "SELECT NVL(CUSTOMER_NAME, 'UNKNOWN') THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".ociresult($stid, "CUSTOMER_ID")."'";
			$cus_cur = ociparse($rfconn, $sql);
			ociexecute($cus_cur);
			ocifetch($cus_cur);
			$cus_name = ociresult($cus_cur, "THE_CUST");

			$sql = "SELECT NVL(COMMODITY_NAME, 'UNKNOWN') THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".ociresult($stid, "COMMODITY_CODE")."'";
			$comm_cur = ociparse($bniconn, $sql);
			ociexecute($comm_cur);
			ocifetch($comm_cur);
			$comm_name = ociresult($comm_cur, "THE_COMM");

			$total_all += ociresult($stid, "THE_PLTS");
			$total_first += ociresult($stid, "FIRST_BILLS");
			$total_continue += ociresult($stid, "CONTINUE_BILLS");

			$output .= "<tr>
							<td>".$cus_name."</td>
							<td>Vessel</td>
							<td>".$ves_name."</td>
							<td>".$comm_name."</td>
							<td>".ociresult($stid, "THE_PLTS")."</td>
							<td>".ociresult($stid, "FIRST_BILLS")."</td>
							<td>".ociresult($stid, "CONTINUE_BILLS")."</td>
						</tr>";
		} while(ocifetch($stid));

		$output .= "<tr>
						<td>TOTAL VESSEL</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>".$total_all."</td>
						<td>".$total_first."</td>
						<td>".$total_continue."</td>
					</tr>";
		$G_total_all += $total_all;
		$G_total_first += $total_first;
		$G_total_continue += $total_continue;

	}



	$output .= "<tr><td colspan=\"7\">&nbsp;</td></tr>";

	// NOT VESSELS!
	$sql = "SELECT RB.CUSTOMER_ID, RB.ARRIVAL_NUM, RB.COMMODITY_CODE, COUNT(*) THE_PLTS,
				SUM(DECODE(RBD.SERVICE_DESCRIPTION, 'PUT INTO STORAGE', 1, 0)) FIRST_BILLS, SUM(DECODE(RBD.SERVICE_DESCRIPTION, 'PUT INTO STORAGE', 0, 1)) CONTINUE_BILLS
			FROM RF_BILLING RB, RF_BILLING_DETAIL RBD
			WHERE RB.BILLING_NUM = RBD.SUM_BILL_NUM
				AND RB.ARRIVAL_NUM NOT IN
					(SELECT TO_CHAR(LR_NUM) FROM VESSEL_PROFILE)
				AND RBD.SERVICE_STATUS != 'DELETED'
				AND RB.SERVICE_STATUS = 'PREINVOICE'
				AND TO_CHAR(RB.CREATED_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."'
			GROUP BY RB.CUSTOMER_ID, RB.ARRIVAL_NUM, RB.COMMODITY_CODE
			ORDER BY RB.CUSTOMER_ID, RB.ARRIVAL_NUM, RB.COMMODITY_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$output .= "<tr><td align=\"center\" colspan=\"7\">No Non-Vessel Cargo bills.</td></tr>";
	} else {
		$total_all = 0;
		$total_first = 0;
		$total_continue = 0;
		do {
			$sql = "SELECT DECODE(RECEIVING_TYPE, 'T', 'TRUCK', 'F', 'TRANSFER', 'UNKNOWN') THE_ARV 
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'
					ORDER BY DECODE(RECEIVING_TYPE, 'T', 'TRUCK', 'F', 'TRANSFER', 'UNKNOWN')";
			$arv_cur = ociparse($rfconn, $sql);
			ociexecute($arv_cur);
			ocifetch($arv_cur);
			$arv_type = ociresult($arv_cur, "THE_ARV");

			if($arv_type == "TRUCK" || $arv_type == "UNKNOWN"){
				// trucks are easy enough.
				$ves_name = ociresult($stid, "ARRIVAL_NUM")." - TRUCK";
			} else {
				// transfer.  see if a legit vessel is available.
				$sql = "SELECT NVL(ORIGINAL_ARRIVAL_NUM, 'NONE') THE_ARV
						FROM CARGO_TRACKING_ADDITIONAL_DATA
						WHERE ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'";
				$arv_cur = ociparse($rfconn, $sql);
				ociexecute($arv_cur);
				ocifetch($arv_cur);
				if(ociresult($arv_cur, "THE_ARV") == "NONE"){
					// no original data.  Just keep the transfer number.
					$ves_name = ociresult($stid, "ARRIVAL_NUM")." - TRANSFER";
				} else {
					// get vessel name
					$sql = "SELECT NVL(VESSEL_NAME, 'UNKNOWN') THE_VES FROM VESSEL_PROFILE WHERE LR_NUM = '".ociresult($arv_cur, "THE_ARV")."'";
					$ves_cur = ociparse($rfconn, $sql);
					ociexecute($ves_cur);
					ocifetch($ves_cur);
					$ves_name = ociresult($arv_cur, "THE_ARV")."-".ociresult($ves_cur, "THE_VES");
				}
			}

			$sql = "SELECT NVL(CUSTOMER_NAME, 'UNKNOWN') THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".ociresult($stid, "CUSTOMER_ID")."'";
			$cus_cur = ociparse($rfconn, $sql);
			ociexecute($cus_cur);
			ocifetch($cus_cur);
			$cus_name = ociresult($cus_cur, "THE_CUST");

			$sql = "SELECT NVL(COMMODITY_NAME, 'UNKNOWN') THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".ociresult($stid, "COMMODITY_CODE")."'";
			$comm_cur = ociparse($bniconn, $sql);
			ociexecute($comm_cur);
			ocifetch($comm_cur);
			$comm_name = ociresult($comm_cur, "THE_COMM");

			$total_all += ociresult($stid, "THE_PLTS");
			$total_first += ociresult($stid, "FIRST_BILLS");
			$total_continue += ociresult($stid, "CONTINUE_BILLS");

			$output .= "<tr>
							<td>".$cus_name."</td>
							<td>".$arv_type."</td>
							<td>".$ves_name."</td>
							<td>".$comm_name."</td>
							<td>".ociresult($stid, "THE_PLTS")."</td>
							<td>".ociresult($stid, "FIRST_BILLS")."</td>
							<td>".ociresult($stid, "CONTINUE_BILLS")."</td>
						</tr>";
		} while(ocifetch($stid));

		$output .= "<tr>
						<td>TOTAL NON-VESSEL</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>".$total_all."</td>
						<td>".$total_first."</td>
						<td>".$total_continue."</td>
					</tr>";
		$G_total_all += $total_all;
		$G_total_first += $total_first;
		$G_total_continue += $total_continue;

	}

	$output .= "<tr><td colspan=\"7\">&nbsp;</td></tr>";

	$output .= "<tr>
					<td><b>GRAND TOTAL ".date('m/d/Y')."</b></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><b>".$G_total_all."</b></td>
					<td><b>".$G_total_first."</b></td>
					<td><b>".$G_total_continue."</b></td>
				</tr>";
	$output .= "</table>";

	// send the email
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'DLYSTGBILL'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);

	$mailTO = ociresult($stid, "TO");
	$mailheaders = "From: ".ociresult($stid, "FROM")."\r\n";

	if(ociresult($stid, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($stid, "CC")."\r\n";
	}
	if(ociresult($stid, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($stid, "BCC")."\r\n";
	}
	$mailheaders .= "Content-Type: text/html\r\n";

	$mailSubject = ociresult($stid, "SUBJECT");
	$mailSubject = str_replace("_0_", date('m/d/Y'), $mailSubject);

	$body = ociresult($stid, "NARRATIVE");
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
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'DLYSTGBILL',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($stid, "CC")."',
					'".ociresult($stid, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$modify = ociparse($rfconn, $sql);
		ociexecute($modify);
	}
