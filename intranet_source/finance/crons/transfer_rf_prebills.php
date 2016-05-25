<?
/*
*		Adam Walter, Feb 2014
*
*		Auto-generation of Transfer bills from RF system
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$user = $HTTP_POST_VARS['user'];
	if($user == ""){
		$user = "cron";
	}

	$extra_CA_where_clause = "";
	if($HTTP_POST_VARS['cust'] != ""){
		$extra_CA_where_clause .= " AND CA.CUSTOMER_ID = '".$HTTP_POST_VARS['cust']."'";
	}
	if($HTTP_POST_VARS['lr_num'] != ""){
		$extra_CA_where_clause .= " AND CA.ARRIVAL_NUM = '".$HTTP_POST_VARS['lr_num']."'";
	}
	// for each billing type, we run a different routine;
	// sadly, each billing type has a different definition of what a bill "is",
	// so it cannot be handled by a single function.  Ya know, unless Marketing
	// redesigns all our contracts ;p.

	$sql = "SELECT NVL(MAX(BILLING_NUM), 301500000) THE_MAX
			FROM BILL_HEADER";
	$billnum = ociparse($bniconn, $sql);
	ociexecute($billnum);
	ocifetch($billnum);
	$billing_num = ociresult($billnum, "THE_MAX");
	
	$current_cust = 0;
	$current_cust_to = 0;
	$current_arv = 0;
	$current_date = 0;

	$billed_array = array();

//				AND (RF_SERVICE_CODE IS NULL OR RF_SERVICE_CODE = '11')
	$sql = "SELECT * FROM NONSTORAGE_RATE
			WHERE (BILL_TYPE IS NULL OR BILL_TYPE = 'TRANSFER')
				AND BILL_ORDER = '1'
				AND SCANNEDORUNSCANNED = 'SCANNED'
			ORDER BY RATEPRIORITY";
	$rates = ociparse($bniconn, $sql);
	ociexecute($rates, OCI_NO_AUTO_COMMIT);
	while(ocifetch($rates)){
		if(ociresult($rates, "UNIT") == "25PLT" || ociresult($rates, "UNIT") == "EACH"){ // assume it is
//					CA.ARRIVAL_NUM, 
			$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_COUNT, MAX(DECODE(ACTIVITY_BILLED, NULL, '0', SUBSTR(ACTIVITY_BILLED, 0, 1))) THE_ACT, 
						CA.ORDER_NUM, CA.CUSTOMER_ID, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_ACT_DATE 
					FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
					WHERE CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CA.PALLET_ID = CT.PALLET_ID
						AND CA.CUSTOMER_ID = CT.RECEIVER_ID
						AND CT.COMMODITY_CODE = RTBC.RF_COMM
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND SERVICE_CODE = '11'
						AND ACTIVITY_NUM != '1'
						AND (TO_MISCBILL = 'B' OR TO_MISCBILL = 'X')
						AND ('".ociresult($rates, "COMMODITYCODE")."' IS NULL OR RTBC.BNI_COMM = '".ociresult($rates, "COMMODITYCODE")."')
						AND ('".ociresult($rates, "CUSTOMERID")."' IS NULL OR CT.RECEIVER_ID = '".ociresult($rates, "CUSTOMERID")."')
						".$extra_CA_where_clause."
					GROUP BY CA.ORDER_NUM, CA.CUSTOMER_ID, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY')
					ORDER BY CA.CUSTOMER_ID, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), CA.ORDER_NUM";
//			echo $sql."\n";;
			$orders = ociparse($rfconn, $sql);
			ociexecute($orders, OCI_NO_AUTO_COMMIT);
			while(ocifetch($orders)){
				if(ociresult($rates, "RATE") != 0){
					//  || $current_arv != ociresult($orders, "ARRIVAL_NUM") 
					$sql = "SELECT CUSTOMER_ID 
							FROM CARGO_ACTIVITY 
							WHERE DATE_OF_ACTIVITY >= TO_DATE('".ociresult($orders, "THE_ACT_DATE")."' ,'MM/DD/YYYY')
								AND DATE_OF_ACTIVITY < TO_DATE('".ociresult($orders, "THE_ACT_DATE")."' ,'MM/DD/YYYY')+1
								AND CUSTOMER_ID<>'".ociresult($orders, "CUSTOMER_ID")."'
								AND ORDER_NUM = ARRIVAL_NUM
								AND ACTIVITY_NUM = '1'
								AND SERVICE_CODE='11'
								AND ARRIVAL_NUM = '".ociresult($orders, "ORDER_NUM")."'";
					$short_term_data = ociparse($rfconn, $sql);
					ociexecute($short_term_data, OCI_NO_AUTO_COMMIT);
					ocifetch($short_term_data);
					$trans_to = ociresult($short_term_data, "CUSTOMER_ID");

					if($current_date != ociresult($orders, "THE_ACT_DATE") || $current_cust_to != $trans_to){
						// $current_cust != ociresult($orders, "CUSTOMER_ID") || 
						// new cust/date of data means new header is needed, with new billing num
						$billing_num++;
						array_push($billed_array, $billing_num);
						$billing_det = 1;

						$sql = "INSERT INTO BILL_HEADER
									(CUSTOMER_ID,
									COMMODITY_CODE,
									SERVICE_CODE,
									BILLING_NUM,
									CREATED_BY,
									SERVICE_STATUS,
									ARRIVAL_NUM,
									SERVICE_DATE,
									BILLING_TYPE,
									BILLED_FROM_SYSTEM)
								VALUES
									('".$trans_to."',
									NVL('".ociresult($rates, "COMMODITYCODE")."', '0'),
									'".ociresult($rates, "BNI_SERVICE_CODE")."',
									'".$billing_num."',
									'".$user."',
									'PREINVOICE',
									'-1',
									TO_DATE('".ociresult($orders, "THE_ACT_DATE")."', 'MM/DD/YYYY'),
									'TRANSFER',
									'RF')";
						$insert_orders = ociparse($bniconn, $sql);
						ociexecute($insert_orders, OCI_NO_AUTO_COMMIT);

//						$current_cust = ociresult($orders, "CUSTOMER_ID");
//						$current_arv = ociresult($orders, "ARRIVAL_NUM");
						$current_date = ociresult($orders, "THE_ACT_DATE");
						$current_cust_to = $trans_to;
					}

					$sql = "INSERT INTO BILL_DETAIL
								(BILLING_NUM,
								DETAIL_LINE,
								ORIG_SERV_AMT,
								SERVICE_AMOUNT,
								ORIG_QTY,
								SERVICE_QTY,
								SERVICE_UNIT,
								ORIG_SERVICE_RATE,
								SERVICE_RATE,
								ORIG_RATE_UNIT,
								SERVICE_RATE_UNIT,
								ORIG_SERV_DESC,
								SERVICE_DESCRIPTION,
								ASSET_CODE,
								RF_ORDER_NUM)
							VALUES
								('".$billing_num."',
								'".$billing_det."',
								'".ociresult($rates, "RATE") * ceil(ociresult($orders, "THE_COUNT") / 25)."',
								'".ociresult($rates, "RATE") * ceil(ociresult($orders, "THE_COUNT") / 25)."',
								'".ceil(ociresult($orders, "THE_COUNT") / 25)."',
								'".ceil(ociresult($orders, "THE_COUNT") / 25)."',
								'25PLT',
								'".ociresult($rates, "RATE")."',
								'".ociresult($rates, "RATE")."',
								'25PLT',
								'25PLT',
								'TRANSFER CHARGES @ $".ociresult($rates, "RATE")."/".ociresult($rates, "UNIT")." (ORDER NO:".ociresult($orders, "ORDER_NUM")." PLTS:".ociresult($orders, "THE_COUNT").")',
								'TRANSFER CHARGES @ $".ociresult($rates, "RATE")."/".ociresult($rates, "UNIT")." (ORDER NO:".ociresult($orders, "ORDER_NUM")." PLTS:".ociresult($orders, "THE_COUNT").")',
								'W".ociresult($orders, "THE_ACT")."00',
								'".ociresult($orders, "ORDER_NUM")."')";
					$insert_orders = ociparse($bniconn, $sql);
					ociexecute($insert_orders, OCI_NO_AUTO_COMMIT);

//								FROM CARGO_ACTIVITY@RFTEST.DSPC
//									AND ARRIVAL_NUM = '".ociresult($orders, "ARRIVAL_NUM")."'
					$sql = "INSERT INTO BILL_SUB_DETAILS
								(BILLING_NUM,
								DETAIL_LINE,
								PALLET_OR_LOT_ID,
								CUSTOMER_ID,
								ARRIVAL_NUM,
								CARGO_SYSTEM,
								ACTIVITY_NUM,
								SUB_DETAIL_LINE)
							(SELECT '".$billing_num."',
									'".$billing_det."',
									PALLET_ID,
									CUSTOMER_ID,
									ARRIVAL_NUM,
									'RF',
									ACTIVITY_NUM,
									ROWNUM
								FROM CARGO_ACTIVITY@RF.PROD
								WHERE CUSTOMER_ID = '".ociresult($orders, "CUSTOMER_ID")."'
									AND ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."'
									AND SERVICE_CODE = '11'
									AND ACTIVITY_NUM != '1'
									AND (TO_MISCBILL = 'B' OR TO_MISCBILL = 'X')
							)";
//					echo $sql."\n";
					$insert_orders = ociparse($bniconn, $sql);
					ociexecute($insert_orders, OCI_NO_AUTO_COMMIT);

					$billing_det++;

					// that's the per-order charge.  Add the per-pallet to it.
					// grab the relevant rate
//								AND (RF_SERVICE_CODE IS NULL OR RF_SERVICE_CODE = '11')
					$sql = "SELECT * FROM NONSTORAGE_RATE
							WHERE (BILL_TYPE IS NULL OR BILL_TYPE = 'TRANSFER')
								AND BILL_ORDER = '2'
								AND SCANNEDORUNSCANNED = 'SCANNED'
								AND (COMMODITYCODE IS NULL OR COMMODITYCODE = '".ociresult($rates, "COMMODITYCODE")."')
								AND (CUSTOMERID IS NULL OR CUSTOMERID = '".ociresult($orders, "CUSTOMER_ID")."')
							ORDER BY RATEPRIORITY";
//					echo $sql."\n";
					$inner_rates = ociparse($bniconn, $sql);
					ociexecute($inner_rates, OCI_NO_AUTO_COMMIT);
					if(ocifetch($inner_rates) && ociresult($inner_rates, "UNIT") == "PLT" && ociresult($inner_rates, "RATE") != 0){
						// we ONLY want the "highest priority" value here.  also, if the "2nd rate" concept goes away from Transfers, this block will just get skipped, so its all good.
						// if they CHANGE the 2ndary bill from per-pallet to somethign else, we'll have a problem... finance REALLY should contact us first if they are going to 
						// make such fundamental changes though (or, if Marketing makes such changes, and finance just has to deal with it)
						$sql = "SELECT COUNT(*) THE_COUNT
								FROM BILL_SUB_DETAILS
								WHERE BILLING_NUM = '".$billing_num."'
									AND DETAIL_LINE = '".($billing_det - 1)."'";
						$short_term_data = ociparse($bniconn, $sql);
						ociexecute($short_term_data);
						ocifetch($short_term_data);
						$count = ociresult($short_term_data, "THE_COUNT");

						$sql = "INSERT INTO BILL_DETAIL
									(BILLING_NUM,
									DETAIL_LINE,
									SERVICE_AMOUNT,
									SERVICE_QTY,
									SERVICE_UNIT,
									SERVICE_RATE,
									SERVICE_RATE_UNIT,
									SERVICE_DESCRIPTION,
									RF_ORDER_NUM)
								VALUES
									('".$billing_num."',
									'".$billing_det."',
									'".round(ociresult($inner_rates, "RATE") * $count, 2)."',
									'".$count."',
									'PLT',
									'".ociresult($inner_rates, "RATE")."',
									'PLT',
									'PER-PALLET TRANSFER AT $".ociresult($inner_rates, "RATE")."/".ociresult($inner_rates, "UNIT")."',
									'".ociresult($orders, "ORDER_NUM")."')";
						$insert_orders = ociparse($bniconn, $sql);
						ociexecute($insert_orders, OCI_NO_AUTO_COMMIT);
						$billing_det++;
					}

					//ARRIVAL_NUM = '".ociresult($orders, "ARRIVAL_NUM")."'
					$sql = "UPDATE CARGO_ACTIVITY
							SET TO_MISCBILL = 'Y'
							WHERE CUSTOMER_ID = '".ociresult($orders, "CUSTOMER_ID")."'
								AND ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."'
								AND SERVICE_CODE = '11'
								AND ACTIVITY_NUM != '1'
								AND (TO_MISCBILL = 'B' OR TO_MISCBILL = 'X')";
					$update_bill = ociparse($rfconn, $sql);
					ociexecute($update_bill, OCI_NO_AUTO_COMMIT);

				} else {
					//ARRIVAL_NUM = '".ociresult($orders, "ARRIVAL_NUM")."'
					$sql = "UPDATE CARGO_ACTIVITY
							SET TO_MISCBILL = 'N'
							WHERE CUSTOMER_ID = '".ociresult($orders, "CUSTOMER_ID")."'
								AND ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."'
								AND SERVICE_CODE = '11'
								AND ACTIVITY_NUM != '1'
								AND (TO_MISCBILL = 'B' OR TO_MISCBILL = 'X')";
					$update_nobill = ociparse($rfconn, $sql);
					ociexecute($update_nobill, OCI_NO_AUTO_COMMIT);
				}
			}
		}
	}

	ocicommit($bniconn);
	ocicommit($rfconn);

	$sql = "UPDATE CARGO_ACTIVITY CA
			SET TO_MISCBILL = 'X'
			WHERE SERVICE_CODE = '11'
				AND ACTIVITY_NUM != '1' ".$extra_CA_where_clause." 
				AND (TO_MISCBILL = 'B' OR TO_MISCBILL = 'X')";
	$update_badbill = ociparse($rfconn, $sql);
	ociexecute($update_badbill);



	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'GENERATERFTRANSFER'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "lstewart@port.state.de.us";
		$mailheaders .= "Bcc: ithomas@port.state.de.us,sadu@port.state.de.us,awalter@port.state.de.us\r\n";
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

	if($billing_num == ociresult($billnum, "THE_MAX")){
		$body = str_replace("_0_", "No Bills were generated this run.", $body);
	} else {
		$output = "<br><table border=\"1\" width=\"60%\" cellpadding=\"4\" cellspacing=\"0\">";
//						<td><b>Arrival</b></td>
		$output .= "<tr>
						<td><b>Prebill#</b></td>
						<td><b>Customer</b></td>
						<td><b>Service Date</b></td>
						<td><b>Amount</b></td>
					</tr>";

		foreach($billed_array as $key=>$bill_num_for_email){
			//, ARRIVAL_NUM
			$sql = "SELECT SUM(SERVICE_AMOUNT) THE_AMT, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_DATE, CUSTOMER_ID  
					FROM BILL_HEADER BH, BILL_DETAIL BD
					WHERE BH.BILLING_NUM = BD.BILLING_NUM
						AND BH.BILLING_NUM = '".$bill_num_for_email."'
					GROUP BY TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY'), CUSTOMER_ID, ARRIVAL_NUM";
//			echo $sql."<br>";
			$email_bills = ociparse($bniconn, $sql);
			ociexecute($email_bills);
			ocifetch($email_bills);
/*
			$sql = "SELECT NVL(VESSEL_NAME, 'TRUCK') THE_VES
					FROM VESSEL_PROFILE
					WHERE TO_CHAR(LR_NUM) = '".ociresult($email_bills, "ARRIVAL_NUM")."'";
			$ves_sql = ociparse($bniconn, $sql);
			ociexecute($ves_sql);
			if(!ocifetch($ves_sql)){
				$vesname = ociresult($email_bills, "ARRIVAL_NUM")." - Trucked In";
			} else {
				$vesname = ociresult($email_bills, "ARRIVAL_NUM")." - ".ociresult($ves_sql, "THE_VES");
			}
*/
			$sql = "SELECT CUSTOMER_NAME
					FROM CUSTOMER_PROFILE
					WHERE TO_CHAR(CUSTOMER_ID) = '".ociresult($email_bills, "CUSTOMER_ID")."'";
			$cust_sql = ociparse($bniconn, $sql);
			ociexecute($cust_sql);
			ocifetch($cust_sql);
			$custname = ociresult($cust_sql, "CUSTOMER_NAME");

//							<td>".$vesname."</td>
			$output .= "<tr>
							<td>".$bill_num_for_email."</td>
							<td>".$custname."</td>
							<td>".ociresult($email_bills, "THE_DATE")."</td>
							<td>".number_format(ociresult($email_bills, "THE_AMT"), 2)."</td>
						</tr>";
		}

		$output .= "</table>";

		$body = str_replace("_0_", $output, $body);
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
					'INSTANT',
					SYSDATE,
					'EMAIL',
					'GENERATERFTRANSFER',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		$update_email = ociparse($rfconn, $sql);
		ociexecute($update_email);
	}
