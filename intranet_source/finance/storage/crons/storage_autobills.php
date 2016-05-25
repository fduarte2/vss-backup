<?
/*
*	Adam Walter, Oct-Nov 2009
*
*	This is the script to determine the Storage Bills of Cargo
*	For the "New" Automated Storage Billing System (new as of Oct/Nov 2009)
*****************************************************************************/

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "orcl");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  ora_commitoff($bni_conn); // we dont want this going through unless it totally works
  $bni_cursor = ora_open($bni_conn);
  $rate_cursor = ora_open($bni_conn);
  $update_bni_cursor = ora_open($bni_conn);
  $Short_Term_Cursor = ora_open($bni_conn);

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RF");
  if($rf_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);



	$mailTO = "billing@port.state.de.us,sshoemaker@port.state.de.us,vfarkas@port.state.de.us,philhower@port.state.de.us\r\n";
//	$mailTO = "awalter@port.state.de.us\r\n";
	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	
//	$mailsubject = "BNI Autorun Storage Bills Results\r\n";


/*
*	BNI CARGO FIRST
********/

//	$bill_day = date('m/d/Y');
	$bill_day = date('m/d/Y', mktime(0,0,0,date('m'),date('d') - 1,date('Y')));
	$lot_num = "default";
//	$email_bni_bad_details = "\r\n";
	$email_bni_stopbill_details = "\r\n";
	$email_bni_okbill_notify_details = "\r\n";
	$mail_body_summary = "\r\n";

	// AND CUSTOMERID != 312 is because abitibi is billed from RF right now.  HOPEFULLY, that will change.
	//			CT.QTY_IN_HOUSE > 0

	$sql = "SELECT CM.COMMODITY_CODE THE_COMM, CM.LR_NUM THE_VES, CT.OWNER_ID THE_CUST, ORIGINAL_CONTAINER_NUM, WAREHOUSE_LOCATION, CONTAINER_NUM, NVL(GREATEST(((CT.STORAGE_END - CT.FREE_TIME_END) + 1), 0), 0) DAYS_STORED_SO_FAR, QTY_RECEIVED, QTY1_UNIT, QTY2_UNIT, CARGO_WEIGHT_UNIT, QTY_EXPECTED, QTY2_EXPECTED, CARGO_WEIGHT, QTY_IN_HOUSE, STORAGE_CUST_ID, CARGO_MARK, CARGO_BOL 
		FROM CARGO_TRACKING CT, CARGO_MANIFEST CM 
		WHERE CT.LOT_NUM = CM.CONTAINER_NUM AND
			CT.DATE_RECEIVED IS NOT NULL AND
			CT.OWNER_ID != '312' AND
			TO_CHAR(CT.STORAGE_END + 1, 'MM/DD/YYYY') = '".$bill_day."'";
//	echo $sql;
	ora_parse($bni_cursor, $sql);
	ora_exec($bni_cursor);
	while(ora_fetch_into($bni_cursor, $bni_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$make_billing_record_for_this_lot = true;
		$comm = $bni_row['THE_COMM'];
		$vessel = $bni_row['THE_VES'];
		$cust = $bni_row['THE_CUST'];
		$whse = substr($bni_row['WAREHOUSE_LOCATION'], 0, 1);
		$box = substr($bni_row['WAREHOUSE_LOCATION'], 1, 1);
		$orig_cont = $bni_row['ORIGINAL_CONTAINER_NUM'];
		$cont = $bni_row['CONTAINER_NUM'];
		$days_so_far = $bni_row['DAYS_STORED_SO_FAR'];
		$mark = $bni_row['CARGO_MARK'];
		$bol = $bni_row['CARGO_BOL'];

		$sql = "SELECT * FROM RATE WHERE
				(CUSTOMERID = '".$cust."' OR CUSTOMERID IS NULL) AND
				(COMMODITYCODE = '".$comm."' OR COMMODITYCODE IS NULL) AND
				(ARRIVALNUMBER = '".$vessel."' OR ARRIVALNUMBER IS NULL) AND
				(WAREHOUSE = '".$whse."' OR WAREHOUSE IS NULL) AND
				(BOX = '".$box."' OR BOX IS NULL) AND
					(('".$orig_cont."' != '' AND '".$orig_cont."' != '".$cont."' AND ARRIVALTYPE = 'X') OR
					(".$vessel." > 20 AND 
						".$vessel." IN (SELECT LR_NUM FROM VOYAGE) AND
						ARRIVALTYPE = 'V') OR
					('".$vessel."' IN ('3', '4', '7') AND ARRIVALTYPE = 'R') OR
					(".$vessel." <= 20 AND '".$vessel."' NOT IN ('3', '4', '7') AND ARRIVALTYPE = 'T') OR
					ARRIVALTYPE = 'A')
				AND RATESTARTDATE <= ".$days_so_far."
				AND SCANNEDORUNSCANNED = 'UNSCANNED'
				ORDER BY RATEPRIORITY ASC, RATESTARTDATE DESC";
		ora_parse($rate_cursor, $sql);
		ora_exec($rate_cursor);
//		echo $sql."\n";
		if(!ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$email_bni_stopbill_details .= "Lot ".$bni_row['CONTAINER_NUM']." bol ".$bol." mark ".$mark." (cust ".$cust." comm ".$comm." vsl ".$vessel.") has no valid rate in the rate table.  Cannot bill.\r\n";
			$make_billing_record_for_this_lot = false;
		} else {
			$rate = $rate_row['RATE'];
			if($rate_row['BILLTOCUSTOMER'] != ""){
				$bill_to_cust = $rate_row['BILLTOCUSTOMER'];
			} else {
				$bill_to_cust = $bni_row['STORAGE_CUST_ID'];
			}
//			$ratio_in_house_to_bill = $bni_row['QTY_IN_HOUSE'] / $bni_row['QTY_EXPECTED'];
// CHANGED WITH HD 8096
			if($bni_row['QTY_EXPECTED'] == 0){
				$ratio_in_house_to_bill = 0;
				$email_bni_stopbill_details .= "Lot ".$cont." Cust ".$cust." Comm ".$comm." bol ".$bol." mark ".$mark." has had it`s contents 100% transferred since the last bill; autocalculation for this bill has been halted.<br>";
			} else {
				$ratio_in_house_to_bill = Qty_as_of_date($cont, $bill_day, $bni_conn) / $bni_row['QTY_EXPECTED'];
			}

			$bill_duration = $rate_row['BILLDURATION'];
			$bill_duration_unit = $rate_row['BILLDURATIONUNIT'];
			$bill_unit = $rate_row['UNIT'];

			// figure which UOM to bill off of
			if($bill_unit == $bni_row['QTY1_UNIT']){
				$original_receive = $bni_row['QTY_EXPECTED'];
			} elseif($bill_unit == $bni_row['QTY2_UNIT']){
				$original_receive = $bni_row['QTY2_EXPECTED'];
			} else { // check to see if it matches to a Weight
				$sql = "SELECT CONVERSION_FACTOR FROM UNIT_CONVERSION WHERE PRIMARY_UOM = '".$bni_row['CARGO_WEIGHT_UNIT']."' AND SECONDARY_UOM = '".$bill_unit."'";
//				echo $sql."\n";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$email_bni_stopbill_details .= "Lot ".$bni_row['CONTAINER_NUM']." bol ".$bol." mark ".$mark." is measured in ".$bni_row['QTY1_UNIT'].", ".$bni_row['QTY2_UNIT'].", and ".$bni_row['CARGO_WEIGHT_UNIT'].".  The rate table wants to bill it by ".$bill_unit.".  Cannot bill.\r\n";
					$make_billing_record_for_this_lot = false;
				} else {
					$original_receive = $bni_row['CARGO_WEIGHT'] * $short_term_row['CONVERSION_FACTOR'];
				}
			}

			$bill_quantity = $original_receive * $ratio_in_house_to_bill;
			if($bill_unit != $bni_row['QTY1_UNIT']){
				$qty1_for_printing = round($bni_row['QTY_EXPECTED'] * $ratio_in_house_to_bill, 2);
				$qty1_for_printing = "(". $qty1_for_printing . $bni_row['QTY1_UNIT'].")";
			} else {
				$qty1_for_printing = "";
			}
//			echo "orig ".$original_receive." ratio ".$ratio_in_house_to_bill." qty ".$bill_quantity."\n";
			$bill_amount = $bill_quantity * $rate;

//			echo "amount ".$bill_amount."\n";
			if($bill_amount <= 0){
				$make_billing_record_for_this_lot = false;
			}

			// get service code from LOCATION_CATEGORY table, based on warehouse location
			$sql = "SELECT STORAGE_SERVICE_CODE FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = UPPER('".$bni_row['WAREHOUSE_LOCATION']."')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
//			echo $sql."\n";
			if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$email_bni_stopbill_details .= "Lot ".$bni_row['CONTAINER_NUM']."'s warehouse location (".$bni_row['WAREHOUSE_LOCATION'].") has no entry in Location Category Table.  Cannot bill.\r\n";
				$make_billing_record_for_this_lot = false;
			} else {
				$service_code = $short_term_row['STORAGE_SERVICE_CODE'];
			}

			// get next BILLING_NUM for billing table
			$sql = "SELECT MAX(BILLING_NUM) THE_MAX FROM BILLING";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//			echo $sql."\n";
			$billing_num = $short_term_row['THE_MAX'] + 1;

			// Get the Oracle Asset Code
			// Note that if one doesnt exist, we update the email, but do NOT prevent record from writing.
			$sql = "SELECT ASSET_CODE FROM ASSET_PROFILE WHERE SERVICE_LOCATION_CODE = UPPER('".$bni_row['WAREHOUSE_LOCATION']."')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
//			echo $sql."\n";
			if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$email_bni_okbill_notify_details .= "Lot ".$bni_row['CONTAINER_NUM']." bol ".$bol." mark ".$mark." has no valid Asset Code (This will not stop bill from generating).\r\n";
				$asset_code = "";
			} else {
				$asset_code = $short_term_row['ASSET_CODE'];
			}


			// ok, we gots the data... time to tell the DB all about it.
			if($make_billing_record_for_this_lot){
				$sql = "INSERT INTO BILLING (
						CUSTOMER_ID,
						SERVICE_CODE,
						LOT_NUM,
						ACTIVITY_NUM,
						BILLING_NUM,
						EMPLOYEE_ID,
						SERVICE_START,
						SERVICE_STOP,
						SERVICE_AMOUNT,
						SERVICE_STATUS,
						SERVICE_DESCRIPTION,
						SERVICE_RATE,
						LR_NUM,
						ARRIVAL_NUM,
						COMMODITY_CODE,
						INVOICE_NUM,
						SERVICE_DATE,
						SERVICE_QTY,
						SERVICE_NUM,
						THRESHOLD_QTY,
						LEASE_NUM,
						SERVICE_UNIT,
						LABOR_RATE_TYPE,
						LABOR_TYPE,
						PAGE_NUM,
						CARE_OF,
						BILLING_TYPE,
						MEMO_NUM,
						ASSET_CODE)
					SELECT
						'".$bill_to_cust."',
						'".$service_code."',
						'".$cont."',
						'1',
						'".$billing_num."',
						'4',
						STORAGE_END + 1, ";

				if($bill_duration_unit == "DAY"){
					$sql .= "STORAGE_END + ".$bill_duration.", ";
				} else {
					// no one has any idea
					$sql .= "'', ";
				}

				$sql .= "'".round($bill_amount, 2)."',
						'PREINVOICE',
						'STORAGE CHARGE',
						'".$rate."',
						'".$vessel."',
						'1',
						'".$comm."',
						'0',
						STORAGE_END + 1,
						'".round($bill_quantity, 2)."',
						'1',
						'0',
						'0',
						'".$bill_unit."',
						'',
						'',
						'1',
						'Y',
						'STORAGE',
						'".$qty1_for_printing."',
						'".$asset_code."'
						FROM CARGO_TRACKING
						WHERE LOT_NUM = '".$cont."'";
//				echo $sql."\n";
				ora_parse($update_bni_cursor, $sql);
				ora_exec($update_bni_cursor);

				$mail_body_summary .= $cont." bol ".$bol." mark ".$mark." - cust ".$bill_to_cust." - ".$bill_duration." days, $".round($bill_amount, 2)."\r\n";

				$sql = "UPDATE CARGO_TRACKING SET STORAGE_END = ";
				if($bill_duration_unit == "DAY"){
					$sql .= "STORAGE_END + ".$bill_duration." ";
				} else {
					// no one has any idea, defaulting to days
					$sql .= "STORAGE_END + ".$bill_duration." ";
				}
				$sql .= " WHERE LOT_NUM = '".$cont."'";
				ora_parse($update_bni_cursor, $sql);
				ora_exec($update_bni_cursor);
			}
		}
	}

	ora_commit($bni_conn);

//	echo $email_bni_bad_details."\r\n";
//	$mail_body = $mail_body_summary."\r\n\r\nThe following errors/notices were found during the run:\r\n\r\n".$email_bni_bad_details;
//	mail($mailTO, $mailsubject, $mail_body, $mailheaders);

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'BNIAUTOSTORAGE'";
	ora_parse($ED_cursor, $sql);
	ora_exec($ED_cursor);
	ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$mailTO = $email_row['TO'];
	$mailheaders = "From: ".$email_row['FROM']."\r\n";

	if($email_row['CC'] != ""){
		$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
	}
	if($email_row['BCC'] != ""){
		$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
	}

	$mailSubject = $email_row['SUBJECT'];
//	$mailSubject = str_replace("_0_", $free_time_counter, $mailSubject);
//	$mailSubject = str_replace("_1_", $user, $mailSubject);

	$body = $email_row['NARRATIVE'];
	$body = str_replace("_1_", $mail_body_summary."\r\n", $body);
	$body = str_replace("_2_", $email_bni_stopbill_details.$email_bni_okbill_notify_details, $body);

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
					'BNIAUTOSTORAGE',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}






/*
		$rate_per = $bni_row['RATE'];
		$bill_duration = $bni_row['BILLDURATION'];
		$bill_duration_unit = $bni_row['BILLDURATIONUNIT'];
*/


/*
 AND
			(CM.COMMODITY_CODE = RT.COMMODITYCODE OR RT.COMMODITY_CODE IS NULL) AND
			(CM.LR_NUM = RT.ARRIVALNUMBER OR RT.ARRIVALNUMBER IS NULL) AND
			(CT.OWNER_ID = RT.CUSTOMERID OR RT.CUSTOMERID IS NULL) AND
			(CT.WAREHOUSE_LOCATION LIKE RT.WAREHOUSE || '%' OR RT.WAREHOUSE IS NULL) AND
			(SUBSTR(CT.WAREHOUSE_LOCATION, 2, 1) = SUBSTR(RT.BOX, 2, 1) OR RT.BOX IS NULL) AND
				((CM.ORIGINAL_CONTAINER_NUM IS NOT NULL AND CM.CONTAINER_NUM != CM.ORIGINAL_CONTAINER_NUM AND RT.ARRIVAL_TYPE = 'X') OR
				(CM.LR_NUM > 20 AND RT.ARRIVAL_TYPE = 'V') OR
				(CM.LR_NUM IN ('3', '4', '7') AND RT.ARRIVAL_TYPE = 'R') OR
				(CM.LR_NUM <= 20 AND CM.LR_NUM NOT IN ('3', '4', '7') AND RT.ARRIVAL_TYPE = 'T') OR
				RT.ARRIVAL_TYPE = 'A') AND
			(CT.STORAGE_END - CT.FREE_TIME_END <= RT.RATESTARTDATE)
			ORDER BY RATEPRIORITY DESC";
			*/





function Qty_as_of_date($cont, $this_run_date, $bni_conn){
	// NOTE:  this function assumes that $cont is a valid LOT_NUM from CARGO_TRACKING.
	// if it is not (which means the calling routine has a bug), the return value will be "", which equates to 0 for mathematical purposes.
	$bni_cursor = ora_open($bni_conn);
	$Short_Term_Cursor = ora_open($bni_conn);

	$sql = "SELECT QTY_IN_HOUSE FROM CARGO_TRACKING WHERE LOT_NUM = '".$cont."'";
	ora_parse($bni_cursor, $sql);
	ora_exec($bni_cursor);
	ora_fetch_into($bni_cursor, $bni_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$current_IH = $bni_row['QTY_IN_HOUSE'];

	$sql = "SELECT SUM(QTY_CHANGE) THE_CHANGE FROM CARGO_ACTIVITY WHERE LOT_NUM = '".$cont."' AND DATE_OF_ACTIVITY >= TO_DATE('".$this_run_date."', 'MM/DD/YYYY')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$aggregate_activities = $Short_Term_row['THE_CHANGE'];

	return ($current_IH + $aggregate_activities);
}
?>