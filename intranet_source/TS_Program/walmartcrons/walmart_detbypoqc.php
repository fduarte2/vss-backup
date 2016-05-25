<?
/*
*	March 2014
*
*	Sends the email triggered by the walmart_upload.php script on eport
*
****************************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}


	$sql = "SELECT * FROM JOB_QUEUE 
			WHERE JOB_DESCRIPTION = 'WDIVSLDETAILBYPOQC'
			AND COMPLETION_STATUS = 'PENDING'
			AND JOB_TYPE = 'EMAIL'";
	$jobs = ociparse($rfconn, $sql);
	ociexecute($jobs);
	while(ocifetch($jobs)){
		$vessel = ociresult($jobs, "VARIABLE_LIST");

		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE LR_NUM = '".$vessel."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$vesname = $vessel." - ".ociresult($short_term_data, "VESSEL_NAME");

		$detail_xls = "<table><tr><td colspan=\"15\">WALMART</td></tr>";
		$detail_xls .= "<tr><td colspan=\"15\">intranet/TS_Program/walmartcrons/walmart_detbypoqc.php</td></tr>";
		$detail_xls .= "<tr><td>Vessel:</td><td colspan=\"14\">".$vesname."</td></tr>";
		$detail_xls .= "<tr>
							<td>Pallet Id</td>
							<td>Cartons</td>
							<td>Label</td>
							<td>Commodity</td>
							<td>Variety</td>
							<td>Size</td>
							<td>Description</td>
							<td>Grower</td>
							<td>Exporter</td>
							<td>Fum</td>
							<td>Hatch & Deck</td>
							<td>Cont No</td>
							<td>Temp Rec</td>
							<td>Booking P.O. No.</td>
							<td>Inbound Item No</td>
							<td>Dept. No.</td>
						</tr>";
		$sql = "SELECT CT.PALLET_ID, CT.QTY_RECEIVED, CT.EXPORTER_CODE, CP.COMMODITY_NAME, CT.VARIETY, CT.CARGO_SIZE, CT.CARGO_DESCRIPTION,
					CT.FUMIGATION_CODE, CT.HATCH, CT.CONTAINER_ID, CT.MARK, CT.BATCH_ID, DECODE(SUBSTR(CT.MARK, 0, 2), '21', '64', '94') THE_DEPT
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
				WHERE CT.ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '3000'
					AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
				ORDER BY CT.PALLET_ID";
		$details = ociparse($rfconn, $sql);
		ociexecute($details);
		while(ocifetch($details)){
			$exp_split = explode("_", ociresult($details, "EXPORTER_CODE"));
			if($exp_split[0] < 0){
				$exp_split[0] = "MULTIPLE";
			}
			$detail_xls .= "<tr>
								<td>".ociresult($details, "PALLET_ID")."</td>
								<td>".ociresult($details, "QTY_RECEIVED")."</td>
								<td></td>
								<td>".ociresult($details, "COMMODITY_NAME")."</td>
								<td>".ociresult($details, "VARIETY")."</td>
								<td>".ociresult($details, "CARGO_SIZE")."</td>
								<td>".ociresult($details, "CARGO_DESCRIPTION")."</td>
								<td>".$exp_split[0]."</td>
								<td>".$exp_split[1]."</td>
								<td>".ociresult($details, "FUMIGATION_CODE")."</td>
								<td>".ociresult($details, "HATCH")."</td>
								<td>".ociresult($details, "CONTAINER_ID")."</td>
								<td></td>
								<td>".ociresult($details, "MARK")."</td>
								<td>".ociresult($details, "BATCH_ID")."</td>
								<td>".ociresult($details, "THE_DEPT")."</td>
							</tr>";
		};

		$detail_xls .= "</table>";


		
		
		
		
		$plts_tot = 0;
		$ctns_tot = 0;		
		
		$po_sum = "<table><tr><td colspan=\"10\">Walmart Vessel P.O. Summary by Department</td></tr>";
		$po_sum .= "<table><tr><td colspan=\"10\">intranet/TS_Program/walmartcrons/walmart_detbypoqc.php</td></tr>";
		$po_sum .= "<table><tr><td>Vessel:</td><td colspan=\"9\">".$vesname."</td></tr>";
		$po_sum .= "<tr>
						<td>Dept</td>
						<td>P.O. Number</td>
						<td>Grower</td>
						<td>Exporter</td>
						<td>Grower Item Num</td>
						<td>WM Item Num</td>
						<td>Recv PO</td>
						<td>Description</td>
						<td>Pallets</td>
						<td>Cases</td>
					</tr>";
		$po_sum .= "<tr><td colspan=\"10\">064 - Sam's Club</td></tr>";


		// sams first
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(CT.QTY_RECEIVED) THE_CTNS, CT.EXPORTER_CODE, CT.BOL,
					CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND SUBSTR(CT.MARK, 0, 2) = '21'
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.EXPORTER_CODE, CT.BOL, CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON')
				ORDER BY CT.MARK";
		$details = ociparse($rfconn, $sql);
		ociexecute($details);
		if(!ocifetch($details)){
			$po_sum .= "<tr><td colspan=\"10\" align=\"center\">No Sams Club Pallets Found</td></tr>";
		} else {
			$plts_sub = 0;
			$ctns_sub = 0;

			do {
				$plts_sub += ociresult($details, "THE_PLTS");
				$plts_tot += ociresult($details, "THE_PLTS");
				$ctns_sub += ociresult($details, "THE_CTNS");
				$ctns_tot += ociresult($details, "THE_CTNS");

				$temp = explode("_", ociresult($details, "EXPORTER_CODE"));
				if($temp[0] < 0){
					$temp[0] = "MULTIPLE";
				}
//				$exp = $temp[0]."-".$temp[1];

				$po_sum .= "<tr>
								<td></td>
								<td>".ociresult($details, "MARK")."</td>
								<td>".$temp[0]."</td>
								<td>".$temp[1]."</td>
								<td>".ociresult($details, "BATCH_ID")."</td>
								<td>".ociresult($details, "BOL")."</td>
								<td>".ociresult($details, "MARK")."</td>
								<td>".ociresult($details, "THE_VAR")."</td>
								<td>".ociresult($details, "THE_PLTS")."</td>
								<td>".ociresult($details, "THE_CTNS")."</td>
							</tr>";
			} while(ocifetch($details));
			$po_sum .= "<tr><td colspan=\"8\">Dept 064 Totals:</td><td>".$plts_sub."</td><td>".$ctns_sub."</td></tr>";
		}


		$po_sum .= "<tr><td colspan=\"9\">094 - Wal-Mart Supercenter</td></tr>";
		// everyone NOT sams
		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(CT.QTY_RECEIVED) THE_CTNS, CT.EXPORTER_CODE, CT.BOL,
					CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON') THE_VAR
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM
				WHERE CT.BATCH_ID = WIM.ITEM_NUM(+)
					AND CT.BOL = WIM.WM_ITEM_NUM(+)
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND SUBSTR(CT.MARK, 0, 2) != '21'
					AND CT.RECEIVER_ID IN
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				GROUP BY CT.EXPORTER_CODE, CT.BOL, CT.MARK, CT.BATCH_ID, NVL(UPPER(WIM.VARIETY), 'UNKNWON')
				ORDER BY CT.MARK";
		$details = ociparse($rfconn, $sql);
		ociexecute($details);
		if(!ocifetch($details)){
			$po_sum .= "<tr><td colspan=\"10\" align=\"center\">No Walmart Supercenter Pallets Found</td></tr>";
		} else {
			$plts_sub = 0;
			$ctns_sub = 0;

			do {
				$plts_sub += ociresult($details, "THE_PLTS");
				$plts_tot += ociresult($details, "THE_PLTS");
				$ctns_sub += ociresult($details, "THE_CTNS");
				$ctns_tot += ociresult($details, "THE_CTNS");

				$temp = explode("_", ociresult($details, "EXPORTER_CODE"));
				if($temp[0] < 0){
					$temp[0] = "MULTIPLE";
				}
//				$exp = $temp[0]."-".$temp[1];

				$po_sum .= "<tr>
								<td></td>
								<td>".ociresult($details, "MARK")."</td>
								<td>".$temp[0]."</td>
								<td>".$temp[1]."</td>
								<td>".ociresult($details, "BATCH_ID")."</td>
								<td>".ociresult($details, "BOL")."</td>
								<td>".ociresult($details, "MARK")."</td>
								<td>".ociresult($details, "THE_VAR")."</td>
								<td>".ociresult($details, "THE_PLTS")."</td>
								<td>".ociresult($details, "THE_CTNS")."</td>
							</tr>";
			} while(ocifetch($details));
			$po_sum .= "<tr><td colspan=\"8\">Dept 094 Totals:</td><td>".$plts_sub."</td><td>".$ctns_sub."</td></tr>";
		}

		$po_sum .= "<tr><td colspan=\"10\">&nbsp;</td></tr>";
		$po_sum .= "<tr><td colspan=\"8\">Grand Totals:</td><td>".$plts_tot."</td><td>".$ctns_tot."</td></tr>";
		$po_sum .= "</table>";
	

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WDIVSLDETAILBYPOQC'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

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
		$mailSubject = str_replace("_0_", $vesname, $mailSubject);
		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_0_", $vesname, $body);

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM JOB_QUEUE
				WHERE JOB_DESCRIPTION = 'WDIVSLDETAILBYPOQC'
				AND COMPLETION_STATUS = 'COMPLETE'";
		$pre_email = ociparse($rfconn, $sql);
		ociexecute($pre_email);
		ocifetch($pre_email);
		if(ociresult($pre_email, "THE_COUNT") >= 1){
			$body = str_replace("_1_", "\r\n\r\nPlease ignore any previous reports for this vessel\r\n\r\n", $body);
		} else {
			$body = str_replace("_1_", "", $body);
		}

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$attach=chunk_split(base64_encode($po_sum));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"PO_SUMMARY_".$vessel.".xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		$attach=chunk_split(base64_encode($detail_xls));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"VESSEL_DETAIL_".$vessel.".xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE SET
						DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETE',
						JOB_EMAIL_TO = '".$mailTO."',
						JOB_EMAIL_CC = '".ociresult($jobs, "CC")."',
						JOB_EMAIL_BCC = '".ociresult($jobs, "BCC")."',
						JOB_BODY = '".substr($body, 0, 2000)."'
					WHERE
						JOB_ID = '".ociresult($jobs, "JOB_ID")."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}

		// end of email.  loop if more.
	}