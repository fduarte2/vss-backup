<?
/*
*	Adam Walter, Oct-Nov-Dec 2009
*
*	This is the script to determine the Initial Billing Dates of Cargo
*	For the "New" Automated Storage Billing System (new as of Oct/Nov/Dec 2009)
*****************************************************************************/

/*
*	BNI CARGO FIRST
********/

	echo "Starting BNI set free time\n";

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  $bni_cursor = ora_open($bni_conn);
  $update_bni_cursor = ora_open($bni_conn);
  $Short_Term_Cursor = ora_open($bni_conn);

  $email_bni_bad_settime = "";

	$lot_num = "default";

	$sql = "SELECT * FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, RATE RT WHERE
			CT.STORAGE_END IS NULL AND
			CT.LOT_NUM = CM.CONTAINER_NUM AND
			CT.DATE_RECEIVED IS NOT NULL AND
			(CM.COMMODITY_CODE = RT.COMMODITYCODE OR RT.COMMODITYCODE IS NULL) AND
			(CM.LR_NUM = RT.ARRIVALNUMBER OR RT.ARRIVALNUMBER IS NULL) AND
			(CT.OWNER_ID = RT.CUSTOMERID OR RT.CUSTOMERID IS NULL) AND
			(CT.WAREHOUSE_LOCATION LIKE RT.WAREHOUSE || '%' OR RT.WAREHOUSE IS NULL) AND
			(CM.EXPORTER_ID = RT.FRSHIPPINGLINE OR RT.FRSHIPPINGLINE IS NULL) AND
			(CM.EXPORTER_ID = RT.FRSHIPPINGLINE OR RT.FRSHIPPINGLINE IS NULL) AND
			(SUBSTR(CT.WAREHOUSE_LOCATION, 2, 1) = SUBSTR(RT.BOX, 2, 1) OR RT.BOX IS NULL) AND
				((CM.ORIGINAL_CONTAINER_NUM IS NOT NULL AND CM.CONTAINER_NUM != CM.ORIGINAL_CONTAINER_NUM AND 
					CM.ORIGINAL_CONTAINER_NUM IN (SELECT LOT_NUM FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6120') AND
					RT.ARRIVALTYPE = 'X') OR
				(CM.LR_NUM > 20 AND 
					CM.LR_NUM IN (SELECT LR_NUM FROM VOYAGE) AND
					RT.ARRIVALTYPE = 'V') OR
				(CM.LR_NUM IN ('3', '4', '7') AND RT.ARRIVALTYPE = 'R') OR
				(CM.LR_NUM <= 20 AND CM.LR_NUM NOT IN ('3', '4', '7') AND RT.ARRIVALTYPE = 'T') OR
				RT.ARRIVALTYPE = 'A')
			AND SCANNEDORUNSCANNED = 'UNSCANNED' 
		ORDER BY LOT_NUM, RATEPRIORITY ASC, RATESTARTDATE ASC";
//	echo $sql;
	ora_parse($bni_cursor, $sql);
	ora_exec($bni_cursor);
	while(ora_fetch_into($bni_cursor, $bni_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($lot_num != $bni_row['LOT_NUM']){ // we only want to get the "highest priority" row of each lot
			if($bni_row['ORIGINAL_CONTAINER_NUM'] != "" && $bni_row['ORIGINAL_CONTAINER_NUM'] != $bni_row['LOT_NUM']){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE LOT_NUM = '".$bni_row['ORIGINAL_CONTAINER_NUM']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] == 0){
					$email_bni_bad_settime .= $bni_row['LOT_NUM']." appears as transferred cargo, but no transfer record can be found for it\r\n";
				}
			}


			// set free time start LOGIC HERE
			$free_time_start_date = "";
			$vessel_flag = false;
			$sql = "SELECT LR_NUM, FREE_TIME_START THE_FIN FROM VOYAGE WHERE LR_NUM = '".$bni_row['LR_NUM']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// NOT A VESSEL, assume free day start = when it got here
				$free_time_start_date = $bni_row['DATE_RECEIVED'];
			} elseif(is_numeric($bni_row['LR_NUM']) && ($bni_row['LR_NUM'] < 20)){
				// NOT A VESSEL, assume free day start = when it got here
				$free_time_start_date = $bni_row['DATE_RECEIVED'];
			} elseif($short_term_row['THE_FIN'] != ""){
				// Is a vessel AND has it has a "sail date" set.  use free time start field.
				$free_time_start_date = $short_term_row['THE_FIN'];
				$vessel_flag = true;
			} else {
				// a vessel, but FREE_TIME_START not yet set.  do nothing.
			}
			if($free_time_start_date != ""){ // if we have a start date, apply various free time logic
//				ECHO $bni_row['LOT_NUM']."  ".$free_time_start_date."\r\n";
				$sql = "UPDATE CARGO_TRACKING SET START_FREE_TIME = '".$free_time_start_date."', FREE_DAYS = '".$bni_row['FREEDAYS']."', STORAGE_CUST_ID = OWNER_ID WHERE LOT_NUM = '".$bni_row['LOT_NUM']."'";
				ora_parse($update_bni_cursor, $sql);
				ora_exec($update_bni_cursor);
/*
				if($vessel_flag == true){
					$sql = "UPDATE VOYAGE SET FREE_TIME_START = '".$free_time_start_date."' WHERE LR_NUM = '".$bni_row['LR_NUM']."'";
					ora_parse($update_bni_cursor, $sql);
					ora_exec($update_bni_cursor);
				}
*/
				$sql = "SELECT TO_CHAR(START_FREE_TIME, 'MM/DD/YYYY') THE_START FROM CARGO_TRACKING WHERE LOT_NUM = '".$bni_row['LOT_NUM']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$temp = split("/", $short_term_row['THE_START']);
				$fts_month = $temp[0];
				$fts_day = $temp[1];
				$fts_year = $temp[2];


				// set free time end
				$free_day_increment = $bni_row['FREEDAYS'];

				if($bni_row['WEEKENDS'] == "Y"){
					// do nothing, free days = free days straight-up
				} else {
					// increment # of free days for every weekend
					for($temp = 0; $temp <= $free_day_increment; $temp++){
						$day = date('w', mktime(0,0,0,$fts_month,$fts_day+$temp,$fts_year)); 
						if($day == 0 || $day == 6){
							$free_day_increment++;
						}
					}
				}
				
				// NOTE:  STORAGE_END gets 1 day less than FREE_TIME_END due to a necessary compatability issue with existing routines.
				// If it helps, think about it this way:
				// --- If the free time ends for a piece of cargo on the 10th of January, then the end date of the "storage" they were getting
				// --- free credit for is the end-of-day on the 9th.
				$sql = "UPDATE CARGO_TRACKING SET FREE_TIME_END = START_FREE_TIME + ".$free_day_increment.", STORAGE_END = START_FREE_TIME + ".($free_day_increment - 1)." WHERE LOT_NUM = '".$bni_row['LOT_NUM']."'";
				ora_parse($update_bni_cursor, $sql);
				ora_exec($update_bni_cursor);
			}

			$lot_num = $bni_row['LOT_NUM'];
		}
	}



/*
*	Now set for RF
************************/
	echo "now starting RF set free time\r\n";
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  // open cursors
  $cursor = ora_open($conn);
  $modify_cursor = ora_open($conn);
  $rate_cursor = ora_open($conn);
  $Short_Term_Cursor_RF = ora_open($conn);
  $ED_cursor = ora_open($conn);

  $email_rf_bad_settime = "";
  $total_pallets_set = 0;
  $per_vessel_array = array();
//	include("./storage_bills_functions.php"); // TEST LOCATION
	include("storage_bills_functions.php");

	$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CT.RECEIVER_ID, CT.COMMODITY_CODE, CT.RECEIVING_TYPE, CT.QTY_RECEIVED, CT.FROM_SHIPPING_LINE, CTAD.SPECIAL_RETURN_TYPE,
				TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY') DATE_REC, CT.WAREHOUSE_LOCATION, CT.WEIGHT, CT.WEIGHT_UNIT, CT.STACKING,
				TO_CHAR(CT.FREE_TIME_END, 'MM/DD/YYYY') END_FREE_TIME,
				NVL(GREATEST(FLOOR(CT.BILLING_STORAGE_DATE - CT.FREE_TIME_END), 0), 0) DAYS_STORED_SO_FAR
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.BILLING_STORAGE_DATE IS NULL
				AND CT.DATE_RECEIVED IS NOT NULL
				AND (CT.BILL IS NULL OR CT.BILL = 'X')
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
			ORDER BY CT.ARRIVAL_NUM";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
//	database_check($ora_success, "Unable get information from cargo_tracking");
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$set_f_t_pallet = true;

		$pallet_id = $row['PALLET_ID']; // used to be trim(), removed 9/24/2012
		$pallet_id_trim = substr(trim($row['PALLET_ID']), 0, 25);
		$vessel = $row['ARRIVAL_NUM'];
		$org_vessel = Get_Original_Vessel($conn, $vessel);
		$cust = $row['RECEIVER_ID'];
		$receiving_type = trim($row['RECEIVING_TYPE']);
/*		if($receiving_type == "F"){
			// if this is a transfer, get the original receive type
			$receiving_type = GetOrigRecType($pallet_id_trim, $vessel, $cust, $conn);
		}*/
		if($receiving_type == "S"){
			// bni, and the rate table, use V for vessel.  Do same here.
			$receiving_type = "V";
		}
//		$qty_received = $row['QTY_RECEIVED'];
		$rf_comm = $row['COMMODITY_CODE'];
		$date_received = $row['DATE_REC'];
//		$free_time_end = $row['END_FREE_TIME'];
		$whse = substr($row['WAREHOUSE_LOCATION'], 0, 1);
		$box = substr($row['WAREHOUSE_LOCATION'], 1, 1);
		$full_whse_location = $row['WAREHOUSE_LOCATION'];
		$days_so_far = $row['DAYS_STORED_SO_FAR'];
//		$weight = $row['WEIGHT'];
//		$weight_unit = $row['WEIGHT_UNIT'];
		$stacking = $row['STACKING'];
		$from_shipping_line = $row['FROM_SHIPPING_LINE'];
		$specialreturn = $row['SPECIAL_RETURN_TYPE'];
							
		$bni_comm = GetBNIComm($rf_comm, $cust, $receiving_type, $conn);
		if(!$bni_comm){
			$set_f_t_pallet = false;
			$email_rf_bad_settime .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$vessel.") Cannot set free time; No valid BNI-conversion found for RF commodity ".$rf_comm."\r\n";
		}

		if($set_f_t_pallet){
			$rate_zero_no_bill = false;
			$sql = "SELECT FREEDAYS, WEEKENDS, RATE FROM RATE WHERE
				(CUSTOMERID = '".$cust."' OR CUSTOMERID IS NULL) AND
				(COMMODITYCODE = '".$bni_comm."' OR COMMODITYCODE IS NULL) AND
				(TO_CHAR(ARRIVALNUMBER) = '".$org_vessel."' OR ARRIVALNUMBER IS NULL) AND
				(WAREHOUSE = '".$whse."' OR WAREHOUSE IS NULL) AND
				(FRSHIPPINGLINE = '".$from_shipping_line."' OR FRSHIPPINGLINE IS NULL) AND
				(BOX = '".$box."' OR BOX IS NULL) AND
					(ARRIVALTYPE = 'A' OR ARRIVALTYPE = '".$receiving_type."')						
				AND RATESTARTDATE <= ".$days_so_far."
				AND SCANNEDORUNSCANNED = 'SCANNED'
				AND (STACKING = '".$stacking."' OR STACKING IS NULL)
				AND (SPECIALRETURN = '".$specialreturn."' OR SPECIALRETURN IS NULL)
				ORDER BY RATEPRIORITY ASC, RATESTARTDATE DESC";
//			echo $sql."\n";
			ora_parse($rate_cursor, $sql);
			ora_exec($rate_cursor);
			if(!ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$email_rf_bad_settime .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$org_vessel.") has no valid freetime value in the rate table.\r\n";
				$set_f_t_pallet = false;
			} elseif($rate_row['RATE'] == 0) {
				$rate_zero_no_bill = true;	
			} else {
				$freedays = $rate_row['FREEDAYS'];
			}
		}

		if($set_f_t_pallet){
			if($receiving_type == "V"){
				// free time for a vessel-based pallet.
				$sql = "SELECT NVL(TO_CHAR(FREE_TIME_START, 'MM/DD/YYYY'), 'NONE') THE_START FROM VOYAGE_FROM_BNI
						WHERE TO_CHAR(LR_NUM) = '".$org_vessel."'";
				ora_parse($Short_Term_Cursor_RF, $sql);
				ora_exec($Short_Term_Cursor_RF);
		//		echo $sql."\n";
				if(!ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$email_rf_bad_settime .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$org_vessel.") vessel not yet entered.\r\n";
					$set_f_t_pallet = false;
				} elseif($Short_Term_Row_RF['THE_START'] == "NONE") {
					$email_rf_bad_settime .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$org_vessel.") vessel freetime not yet set for this already-received cargo.\r\n";
					$set_f_t_pallet = false;
				} else {
					$free_start = $Short_Term_Row_RF['THE_START'];
				}
			} else {
				// non-vessel cargo.  freetime starts from date of receipt.
				$free_start = $date_received;
			}
		}
/*
				$temp = split("/", $short_term_row['THE_START']);
				$fts_month = $temp[0];
				$fts_day = $temp[1];
				$fts_year = $temp[2];


				// set free time end
				$free_day_increment = $bni_row['FREEDAYS'];

				if($rate_row['WEEKENDS'] == "Y"){
					// do nothing, free days = free days straight-up
				} else {
					// increment # of free days for every weekend
					for($temp = 0; $temp <= $free_day_increment; $temp++){
						$day = date('w', mktime(0,0,0,$fts_month,$fts_day+$temp,$fts_year)); 
						if($day == 0 || $day == 6){
							$free_day_increment++;
						}
					}
				}
*/

		if(DoNotBill($whse.$box, $conn) || $rate_zero_no_bill === true) {
			// special rules say not to bill this.
			$sql = "UPDATE CARGO_TRACKING
					SET BILL = 'F'
					WHERE PALLET_ID = '".$pallet_id."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$vessel."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			$ora_success = ora_exec($modify_cursor);
		} elseif($set_f_t_pallet){
			$temp = split("/", $free_start);
			$fts_month = $temp[0];
			$fts_day = $temp[1];
			$fts_year = $temp[2];
			if($rate_row['WEEKENDS'] == "Y"){
				// do nothing, free days = free days straight-up
			} else {
				// increment # of free days for every weekend
				for($temp = 0; $temp <= $freedays; $temp++){
					$day = date('w', mktime(0,0,0,$fts_month,$fts_day+$temp,$fts_year)); 
					if($day == 0 || $day == 6){
						$freedays++;
					}
				}
			}

			$free_time_end = date('m/d/Y', mktime(0,0,0, $fts_month, $fts_day + $freedays, $fts_year));

			$sql = "UPDATE CARGO_TRACKING
					SET FREE_TIME_END = TO_DATE('".$free_time_end."', 'MM/DD/YYYY'),
						BILLING_STORAGE_DATE = TO_DATE('".$free_time_end."', 'MM/DD/YYYY'),
						BILL = NULL
					WHERE PALLET_ID = '".$pallet_id."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

//			echo $pallet_id."\r\n".$sql."\r\n";
			$total_pallets_set++;
			array_push($per_vessel_array, $vessel);

			// free time set.
		} else {
			// bad pallet.  Mark as unsettable (for now)
			$sql = "UPDATE CARGO_TRACKING
					SET BILL = 'X'
					WHERE PALLET_ID = '".$pallet_id."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$vessel."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			$ora_success = ora_exec($modify_cursor);
		}

		// next pallet.
	}
	
//	if($total_pallets_set > 0 || $email_rf_bad_settime != ""){ // from now on, we ALWAYS email 10/24/2012
	if(true){
		if($email_rf_bad_settime == ""){
			$email_rf_bad_settime = "\r\n\r\nNone.\r\n\r\n";
		} else {
			$email_rf_bad_settime = "\r\n\r\n".$email_rf_bad_settime."\r\n\r\n";
		}

		// if there is a single bill OR a single broken pallet
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'RFFREETIMESET'";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
		ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['TEST'] == "Y"){
			$mailTO = "lstewart@port.state.de.us";
	//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
			$mailheaders .= "Cc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
		} else {
			$mailTO = $email_row['TO'];
			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
		}

		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE'];
		
		$body = str_replace("_0_", (0 + $total_pallets_set), $body);
		$body = str_replace("_1_", $email_rf_bad_settime, $body);
		$this_vessel = 0;
		$output_string = "\r\n\r\n";
		for($temp = 0; $temp < sizeof($per_vessel_array); $temp++){
			$this_vessel++;

			if($per_vessel_array[($temp + 1)] != $per_vessel_array[$temp]){
				$sql = "SELECT NVL(VESSEL_NAME, 'TRUCK/TRANS') THE_VES 
						FROM VESSEL_PROFILE
						WHERE ARRIVAL_NUM = '".$per_vessel_array[$temp]."'";
				ora_parse($Short_Term_Cursor_RF, $sql);
				ora_exec($Short_Term_Cursor_RF);
				if(!ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$output_string .= $per_vessel_array[$temp]." - TRUCK/TRANSFER:\t".$this_vessel."\r\n";
					$this_vessel = 0;
				} else {
					$output_string .= $per_vessel_array[$temp]." - ".$Short_Term_Row_RF['THE_VES'].":\t".$this_vessel."\r\n";
					$this_vessel = 0;
				}
			}
		}
		$output_string .= "\r\n";
		$body = str_replace("_2_", $output_string, $body);


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
						'RFFREETIMESET',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
		}
	}





function GetOrigRecType($pallet_id_trim, $vessel, $cust, $conn){
	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT ORIGINAL_RECEIVING_TYPE
			FROM CARGO_TRACKING_ADDITIONAL_DATA
			WHERE SUBSTR(PALLET_ID, 1, 25) = '".$pallet_id_trim."'
				AND ARRIVAL_NUM = '".$vessel."'
				AND RECEIVER_ID = '".$cust."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	return $short_term_row['ORIGINAL_RECEIVING_TYPE'];
	// might return nothing, but rest of calling code can handle an empty return.
}

function GetBNIComm($rf_comm, $cust, $receiving_type, $conn){
	$Short_Term_Cursor = ora_open($conn);

	if($cust == 9722 && $receiving_type == "V"){
		return "5103";
	} elseif($cust == 9722 && $receiving_type == "T"){
		return "5298";
	}

	$sql = "SELECT BNI_COMM
			FROM RF_TO_BNI_COMM
			WHERE RF_COMM = '".$rf_comm."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return false;
	} else {
		return $short_term_row['BNI_COMM'];
	}
}
