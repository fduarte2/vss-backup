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
//	echo "hi\r\n";

// not 439 anymore, now 835
//				AND ZUSER4 IS NULL
	$sql = "SELECT * FROM DC_ORDER
			WHERE ORDERSTATUSID = '9'
				AND COMMODITYCODE != '5606'
				AND TRIM(ORDERNUM) IN
					(SELECT ORDER_NUM FROM CARGO_ACTIVITY
					WHERE CUSTOMER_ID = '835'
					AND SERVICE_CODE = 6)
				AND ORDERNUM NOT IN
					(SELECT ORDERNUM FROM DC_ORDER_EMAILED_BARTHCO)
			ORDER BY ORDERNUM";
	$orders = ociparse($rfconn, $sql);
	echo $sql."\r\n";
	ociexecute($orders);
	if(!ocifetch($orders)){
		// no orders to email.  exit.
		exit;
	} else {
		do {
			$raw_order_num = ociresult($orders, "ORDERNUM");
			$order_num = trim(ociresult($orders, "ORDERNUM"));
			$vessel_num = trim(ociresult($orders, "VESSELID"));
			$raw_cust_num = ociresult($orders, "CUSTOMERID");
//			$cust_num = trim(ociresult($orders, "CUSTOMERID"));
//			$cust_num = 439;
			$cust_num = 835;

			// for each tally...
			$output = "<table><tr><td colspan=\"10\" align=\"center\">PORT OF WILMINGTON TALLY</td></tr>";

			$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_SHIP FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel_num."'";
			$shortermdata = ociparse($rfconn, $sql);
			ociexecute($shortermdata);
			if(!ocifetch($shortermdata)){
				$vesname = "UKN";
			} else {
				$vesname = $vessel_num."-".ociresult($shortermdata, "THE_SHIP");
			}

			$sql = "SELECT CUSTOMERNAME FROM DC_CUSTOMER WHERE CUSTOMERID ='".$raw_cust_num."'";
			$shortermdata = ociparse($rfconn, $sql);
			ociexecute($shortermdata);
			if(!ocifetch($shortermdata)){
				$custname = "UKN";
			} else {
				$custname = ociresult($shortermdata, "CUSTOMERNAME");
			}

			$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME
					FROM CARGO_ACTIVITY 
					WHERE ORDER_NUM = '".$order_num."' 
						AND ARRIVAL_NUM = '".$vessel_num."'
						AND CUSTOMER_ID = '".$cust_num."'";
			$shortermdata = ociparse($rfconn, $sql);
			ociexecute($shortermdata);
			if(!ocifetch($shortermdata)){
				$starttime = "UKN";
				$endtime = "UKN";
			} else {
				$starttime = ociresult($shortermdata, "START_TIME");
				$endtime = ociresult($shortermdata, "END_TIME");
			}

			// not that DC_DOMESTIC is in use as of this writing, but jsut in case...
			if($vessel_num != $cust_num){
				$picklist_table = "DC_PICKLIST";
			} else {
				$picklist_table = "DC_DOMESTIC_PICKLIST";
			}

			// we have the header lines, start the output.

			$output .= "<tr><td colspan=\"5\">START TIME: ".$starttime."</td><td colspan=\"5\"></td></tr>";
			$output .= "<tr><td colspan=\"5\">END TIME: ".$endtime."</td><td colspan=\"5\">ORDER NUMBER: ".$order_num."</td></tr>";
			$output .= "<tr><td colspan=\"5\">VESSEL: ".$vesname."</td><td colspan=\"5\">CUSTOMER: ".$custname."</td></tr>";
			$output .= "<tr><td>BARCODE</td>
							<td>PKG</td>
							<td>WEIGHT</td>
							<td>SIZE</td>
							<td>ORIG QTY</td>
							<td>ACTUAL QTY</td>
							<td>RGR/HOS</td>
							<td>DMG</td>
							<td>CONT#</td>
							<td>CHECKER</td>
						</tr>";

			// and now, we start getting a pallet list.
//						AND CT.RECEIVER_ID = '".$cust_num."'
			$sql = "SELECT CA.ACTIVITY_NUM, CT.PALLET_ID THE_PALLET, CT.EXPORTER_CODE THE_PKG, CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, CT.CARGO_SIZE THE_SIZE,
						CT.BATCH_ID THE_ORIG, CA.QTY_CHANGE THE_CHANGE, CA.SERVICE_CODE THE_SERVICE, CT.CARGO_STATUS THE_STATUS,
						CA.BATCH_ID THE_DMG, CT.CONTAINER_ID THE_CONTAINER
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID
						AND CA.SERVICE_CODE IN (6, 7, 13)
						AND CA.ORDER_NUM = '".$order_num."' 
						AND CT.ARRIVAL_NUM = '".$vessel_num."'
						AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL)
						AND CA.QTY_CHANGE != 0 
					ORDER BY CT.EXPORTER_CODE, CT.PALLET_ID, CA.ACTIVITY_NUM";
			$pallets = ociparse($rfconn, $sql);
			ociexecute($pallets);
//			echo $sql."\r\n";
			if(!ocifetch($pallets)){
				$output .= "No pallets showing; if this is an error, please contact the Port of Wilmington.";
			} else {

				do {
					$barcode = ociresult($pallets, "THE_PALLET");
					$PKG = ociresult($pallets, "THE_PKG");
					$WT = ociresult($pallets, "THE_WEIGHT");
					$size = ociresult($pallets, "THE_SIZE");
					$origcnt = ociresult($pallets, "THE_ORIG");

					$actualcnt = ociresult($pallets, "THE_CHANGE");
					if(ociresult($pallets, "THE_CHANGE") == 7){
						$actualcnt .= "FR";
					} elseif(ociresult($pallets, "THE_CHANGE") == 13){
						$actualcnt .= "DR";
					}

					$sql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".ociresult($pallets, "THE_PALLET")."'";
					$shortermdata = ociparse($rfconn, $sql);
					ociexecute($shortermdata);
					if(!ocifetch($shortermdata)){
						$checker = ociresult($pallets, "ACTIVITY_NUM");
					} else {
						$checker = ociresult($shortermdata, "LOGIN_ID");
					}

					$status = ociresult($pallets, "THE_STATUS");
					$dmg = ociresult($pallets, "THE_DMG");
					$cont = ociresult($pallets, "THE_CONTAINER");

					$output .= "<tr><td>PID:".$barcode."</td>
									<td>".$PKG."</td>
									<td>".$WT."</td>
									<td>".$size."</td>
									<td>".$origcnt."</td>
									<td>".$actualcnt."</td>
									<td>".$status."</td>
									<td>".$dmg."</td>
									<td>".$cont."</td>
									<td>".$checker."</td>
								</tr>";
				} while(ocifetch($pallets));
			}

			$total_cases = 0;
			$total_pallets = 0;

			$sql = "SELECT PALLET_ID, SUM(QTY_CHANGE) THE_CHANGE 
					FROM CARGO_ACTIVITY 
					WHERE ORDER_NUM = '".$order_num."'
						AND SERVICE_CODE IN (6, 7, 13) 
						AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL)
						AND CUSTOMER_ID = '".$cust_num."' 
						AND ARRIVAL_NUM = '".$vessel_num."'
					GROUP BY PALLET_ID";
			$shortermdata = ociparse($rfconn, $sql);
			ociexecute($shortermdata);
			while(ocifetch($shortermdata)){
				if(ociresult($shortermdata, "THE_CHANGE") > 0){
					$total_cases += ociresult($shortermdata, "THE_CHANGE");
					$total_pallets++;
				}
			}

			$output .= "<tr><td colspan=\"4\"></td>
							<td>Total Cases: ".$total_cases."</td>
							<td>Total Pallets: ".$total_pallets."</td>
							<td colspan=\"4\"></td>
						</tr>";

			$output .= "<tr><td>SUBTOTALS:</td><td colspan=\"9\"></td></tr>";
			$output .= "<tr><td colspan=\"1\"></td>
							<td>WEIGHT</td>
							<td>CTNS</td>
							<td>PLTS</td>
							<td colspan=\"6\"></td>
						</tr>";

			// and, the summation lines
//                        AND CT.RECEIVER_ID = '".$cust_num."'
			$sql = "SELECT CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT,
						COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
					WHERE CT.PALLET_ID = CA.PALLET_ID 
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
						AND CA.CUSTOMER_ID = CT.RECEIVER_ID
                        AND CA.SERVICE_CODE IN (6, 7, 13)
                        AND CA.ORDER_NUM = '".$order_num."'
                        AND CT.ARRIVAL_NUM = '".$vessel_num."'
                        AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL)
                        AND CA.QTY_CHANGE != 0
                        GROUP BY CT.WEIGHT || CT.WEIGHT_UNIT
                        HAVING SUM(CA.QTY_CHANGE) > 0
                        ORDER BY CT.WEIGHT || CT.WEIGHT_UNIT";
			$summary = ociparse($rfconn, $sql);
			ociexecute($summary);
			while(ocifetch($summary)){
				$output .= "<tr><td></td>
								<td>".ociresult($summary, "THE_WEIGHT")."</td>
								<td>".ociresult($summary, "THE_CHANGE")."</td>
								<td>".ociresult($summary, "THE_PALLETS")."</td>
								<td colspan=\"6\"></td>
							</tr>";
			}

			$output .= "</table>";

			// send the email
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'DCTALLYSUBTOTALS'";
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
			$mailheaders .= "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
			$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
			$mailheaders .= "X-Mailer: PHP4\r\n";
			$mailheaders .= "X-Priority: 3\r\n";
			$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

			$mailSubject = ociresult($stid, "SUBJECT");
			$mailSubject = str_replace("_0_", date('m/d/Y'), $mailSubject);
			$mailSubject = str_replace("_1_", $order_num, $mailSubject);

			$body = ociresult($stid, "NARRATIVE");
			$body = str_replace("_2_", $order_num, $body);

			$attach=chunk_split(base64_encode($output));
			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;
			$Content.="\r\n";

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/xls; name=\"".$order_num.".xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";

			$Content.="--MIME_BOUNDRY--\n";

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
							'CONSTANTCRON',
							SYSDATE,
							'EMAIL',
							'DCTALLYSUBTOTALS',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($stid, "CC")."',
							'".ociresult($stid, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$modify = ociparse($rfconn, $sql);
				ociexecute($modify);
			}


			// wrap up the order...
			$sql = "INSERT INTO DC_ORDER_EMAILED_BARTHCO
						(ORDERNUM, DATE_SENT)
					VALUES
						('".$raw_order_num."', SYSDATE)";
//					SET ZUSER4 = 'EMAILED'
//					WHERE ORDERNUM = '".$raw_order_num."'";
			echo $sql."\r\n";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);

			// and that's it.  next order.
		} while(ocifetch($orders));
	}