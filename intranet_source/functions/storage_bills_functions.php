<?
/*
*	Adam Walter, May 2012
*
*	This is a general rewrite of the RF-storage bill generation.
*	
*	this script will only be a set of functions, rather than a fully
*	functioning program.  Multiple other php files will call these
*	functions to perform their duties, so that I can guarantee that
*	Each page will always give the same result when being passed the
*	same arguments (and the same state of the database).
************************************************************************/

function Make_RF_Storage_SQL($barcode, $date_rec_start, $date_rec_end, $bill_date_start, $bill_date_end, $LR, $cust, $comm){

	$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CT.RECEIVER_ID, CT.COMMODITY_CODE, CT.RECEIVING_TYPE, CT.QTY_RECEIVED, CT.QTY_UNIT, CT.FROM_SHIPPING_LINE, CT.BILL, CT.QTY_DAMAGED,
				TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY') DATE_REC, CT.WAREHOUSE_LOCATION, CT.WEIGHT, CT.WEIGHT_UNIT, CT.STACKING, CTAD.SPECIAL_RETURN_TYPE,
				TO_CHAR(CT.FREE_TIME_END, 'MM/DD/YYYY') END_FREE_TIME, NVL(CT.VARIETY, 'No Cargo Description') THE_VARIETY, 
				TO_CHAR(CT.BILLING_STORAGE_DATE, 'MM/DD/YYYY') BILL_DATE, TO_CHAR(CT.FREE_TIME_END, 'MM/DD/YYYY') FIRST_BILL_DATE,
				NVL(GREATEST(FLOOR(CT.BILLING_STORAGE_DATE - CT.FREE_TIME_END), 0), 0) DAYS_STORED_SO_FAR 
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE (BILL IS NULL OR BILL = 'X')
				AND BILLING_STORAGE_DATE <= SYSDATE
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND DATE_RECEIVED IS NOT NULL";
	if($barcode != ""){
		$sql .= " AND CT.PALLET_ID = '".$barcode."' ";
	}
	if($date_rec_start != ""){
		$sql .= " AND CT.DATE_RECEIVED >= TO_DATE('".$date_rec_start."', 'MM/DD/YYYY') ";
	}
	if($date_rec_end != ""){
		$sql .= " AND CT.DATE_RECEIVED <= TO_DATE('".$date_rec_end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
	}
	if($bill_date_start != ""){
		$sql .= " AND CT.BILLING_STORAGE_DATE >= TO_DATE('".$bill_date_start."', 'MM/DD/YYYY') ";
	}
	if($bill_date_end != ""){
		$sql .= " AND CT.BILLING_STORAGE_DATE <= TO_DATE('".$bill_date_end."', 'MM/DD/YYYY') ";
	}
	if($LR != ""){
		$sql .= " AND CT.ARRIVAL_NUM = '".$LR."' ";
	}
	if($cust != ""){
		$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
	}
	if($comm != ""){
		$sql .= " AND CT.COMMODITY_CODE = '".$comm."'";
	}

	return $sql;
}

function Make_RF_Prebills($conn, $barcode, $date_rec_start, $date_rec_end, $bill_date_start, $bill_date_end, $vessel, $cust, $comm){
/* the "Big 'Ol" function.
*
*	At the end of this running of this function, a return is sent to the calling program containing a text string for display
*	To the "medium" that called this function.  the return string carries FORMATTING TEXT that will need to be resolved
*	(though if left unresolved, it won't hurt anything; the displayed text will just look wierd)
*************************************************************************************************************************/

	$recurse_barcode = $barcode;
	$recurse_date_rec_start = $date_rec_start;
	$recurse_date_rec_end = $date_rec_end;
	$recurse_bill_date_start = $bill_date_start;
	$recurse_bill_date_end = $bill_date_end;
	$recurse_vessel = $vessel;
	$recurse_cust = $cust;
	$recurse_comm = $comm;
//	echo "BC: ".$recurse_barcode."<br>";
//	echo "DRS: ".$recurse_date_rec_start."<br>";
//	echo "DRE: ".$recurse_date_rec_end."<br>";
//	echo "BDS: ".$recurse_bill_date_start."<br>";
//	echo "BDE: ".$recurse_bill_date_end."<br>";
//	echo "LR: ".$recurse_vessel."<br>";
//	echo "CUST: ".$recurse_cust."<br>";
//	echo "COMM: ".$recurse_comm."<br>";


  $cursor = ora_open($conn);
  $modify_cursor = ora_open($conn);
  $rate_cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

	// step 1:  initialization
	$start_billing_num = 0;
	$end_billing_num = 0;

	$return_text = "";

	$stmt = "select MAX(BILLING_NUM) as MAX from rf_billing_detail";
	$ora_success = ora_parse($cursor, $stmt);
	$ora_success = ora_exec($cursor); 
	database_check($ora_success, "Unable to get a MAX Billing_Num from $rf_billing_detail");
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$billing_num_detail = $row['MAX'] + 1;

	$stmt = "select MAX(BILLING_NUM) as MAX from rf_billing";
	$ora_success = ora_parse($cursor, $stmt);
	$ora_success = ora_exec($cursor); 
	database_check($ora_success, "Unable to get a MAX Billing_Num from $rf_billing");
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$billing_num_header = $row['MAX'] + 1;

			// CHANGE THIS WHEN NEW "FISCAL YEAR" LOGIC IS MADE
//	$next_year_billing = "24".date('y', mktime(0,0,0,date('m')+6,date('d'), date('y')))."0000";
//	if($billing_num_header < $next_year_billing){
//		$billing_num_header = $next_year_billing;
//	}

	$email_nobill_reason = "";



	// Step 2:  Get the list of pallets to process
	$sql = Make_RF_Storage_SQL($barcode, $date_rec_start, $date_rec_end, $bill_date_start, $bill_date_end, $vessel, $cust, $comm);
//	echo $sql."<br>";
//	exit;

	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	database_check($ora_success, "Unable get information from cargo_tracking");
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no pallets to bill
		return "";
	} else {
		do {
			$bill_pallet = true;
			$pallet_exception = false;
			$above_minimum_threshold = false;

			$pallet_id =$row['PALLET_ID'];
			$pallet_id_trim = substr(trim($row['PALLET_ID']), 0, 25);
			$vessel = $row['ARRIVAL_NUM'];
			$org_vessel = Get_Original_Vessel($conn, $vessel);
			$cust = $row['RECEIVER_ID'];

			$receiving_type = trim($row['RECEIVING_TYPE']);
			if($receiving_type == "S"){
				// bni, and the rate table, use V for vessel.  Do same here.
				$receiving_type = "V";
			}
			
			$qty_received = $row['QTY_RECEIVED'];
			$qty_damaged = $row['QTY_DAMAGED'];
			$qty_unit = $row['QTY_UNIT'];
			$rf_comm = $row['COMMODITY_CODE'];
			$date_received = $row['DATE_REC'];
			$bill_storage_date = $row['BILL_DATE'];
			$bill_status = $row['BILL'];
			$free_time_end = $row['END_FREE_TIME'];
			$first_bill_date = $row['FIRST_BILL_DATE'];
			$whse = substr($row['WAREHOUSE_LOCATION'], 0, 1);
			$box = substr($row['WAREHOUSE_LOCATION'], 1, 1);
			$full_whse_location = $row['WAREHOUSE_LOCATION'];
			$days_so_far = $row['DAYS_STORED_SO_FAR'];
			$weight = $row['WEIGHT'];
			$weight_unit = $row['WEIGHT_UNIT'];
			$stacking = $row['STACKING'];
			$from_shipping_line = $row['FROM_SHIPPING_LINE'];
			$variety = $row['THE_VARIETY'];
			$specialreturn = $row['SPECIAL_RETURN_TYPE'];


			// Adam Walter, Jan 2014.
			// new contract with dole is all about their "Pool#s", so that needs to be retrieved.
			// non-dole cargo should just return a null here.
			$sql = "SELECT DOLE_POOL 
					FROM CARGO_TRACKING_ADDITIONAL_DATA
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND RECEIVER_ID = '".$cust."'
						AND PALLET_ID = '".$pallet_id."'";
			$ora_success = ora_parse($Short_Term_Cursor, $sql);
			$ora_success = ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$dole_pool = $Short_Term_Row['DOLE_POOL'];

			$sql = "";
			// Adam Walter, June 2015.
			// dole paper products need their own storage routines now as well, so get relevant data...
			if($rf_comm == "1299"){
				$sql = "SELECT BOOKING_NUM DOLEPAPER_ID, BOL DOLEPAPER_BOL, ORDER_NUM DOLEPAPER_ORDER
						FROM BOOKING_ADDITIONAL_DATA
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND RECEIVER_ID = '".$cust."'
							AND PALLET_ID = '".$pallet_id."'";
				$ora_success = ora_parse($Short_Term_Cursor, $sql);
				$ora_success = ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$paper_ID = $Short_Term_Row['DOLEPAPER_ID'];
				$paper_bol = $Short_Term_Row['DOLEPAPER_BOL'];
				$paper_order = $Short_Term_Row['DOLEPAPER_ORDER'];
			} else {
				$paper_ID = "";
				$paper_bol = "";
				$paper_order = "";
			}


			$result = GetBNICommAndUNIT($rf_comm, $bni_comm, $cust, $receiving_type, $qty_unit, $conn);
			if(!$result){
				$bill_pallet = false;
				$email_nobill_reason .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$org_vessel.") Cannot be billed; No valid BNI-conversion found for RF commodity ".$rf_comm."\r\n";
			}
/*
			$max_qty_in_house_during_period = Get_Max_In_House($conn, $bill_storage_date, $barcode, $vessel, $cust);
			$above_minimum_threshold = Minimum_Bill_Threshold_Check($conn, $max_qty_in_house_during_period, $rf_comm);
			if(!$above_minimum_threshold){
				$bill_pallet = false;
			}
*/
			if($bill_pallet){
				if(DoNotBill($whse.$box, $conn)){
					$pallet_exception = true;
					$bill_pallet = false;
				}
			}

			if($bill_pallet){
				$sql = "SELECT * FROM RATE WHERE
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
				ora_parse($rate_cursor, $sql);
				ora_exec($rate_cursor);
//				echo $sql."<br>";
				if(!ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$email_nobill_reason .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$org_vessel.") has no valid rate in the rate table.\r\n";
					$bill_pallet = false;
					$rate = false;
				} else {
					$rate = $rate_row['RATE'];
					if($rate_row['BILLTOCUSTOMER'] != ""){
						$bill_to_cust = $rate_row['BILLTOCUSTOMER'];
					} else {
						$bill_to_cust = $cust;
					}
					$bill_duration = $rate_row['BILLDURATION'];
					$bill_duration_unit = $rate_row['BILLDURATIONUNIT'];
					$bill_unit = $rate_row['UNIT'];
					$service_code = $rate_row['SERVICECODE'];

					$max_qty_in_house_during_period = Get_Max_In_House($conn, $bill_storage_date, $bill_duration, $pallet_id, $vessel, $cust);
					$above_minimum_threshold = Minimum_Bill_Threshold_Check($conn, $max_qty_in_house_during_period, $rf_comm, $cust);
					if(!$above_minimum_threshold){
						$bill_pallet = false;
					}

					// figure which UOM to bill off of
					if($bill_unit == "PLT"){
						$qty2 = $qty_received;
						if($cust == "1131"){ // KOPKE IS DIFFERENT because of their concept of "TO_STORAGE" that is sent nightly (Adam W. DEC 2015)
							$qty2 -= $qty_damaged;
						}
						$qty2_unit = $qty_unit;
						$bill_quantity = 1;
					} else { // check to see if it matches to a Weight
						$sql = "SELECT CONVERSION_FACTOR FROM UNIT_CONVERSION_FROM_BNI WHERE PRIMARY_UOM = '".$weight_unit."' AND SECONDARY_UOM = '".$bill_unit."'";
		//				echo $sql."\n";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$email_nobill_reason .= "Pallet ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$org_vessel.") is measured in ".$weight_unit.".  The associated rate is neither a weight unit, nor by Pallet.  Cannot bill.\r\n";
							$bill_pallet = false;
						} else {
							$qty2 = 1;
							$qty2_unit = "PLT";
							$bill_quantity = round($weight * $short_term_row['CONVERSION_FACTOR'], 2);
						}
					}

					$bill_amount = $bill_quantity * $rate;

					if($bill_amount == 0){
						$bill_pallet = false;
					}
				}

				if($bill_pallet){
					// Due to the dual-PHP executable of the new .49 box, this function, which works fine with web pages, fails with shell scripts.
					// I have to write my own equivalent, and exclude the "compareDate.php"'s version
					if(Which_Date_Is_First($bill_storage_date, $first_bill_date) <= 0){
					  $description = "PUT INTO STORAGE";
					}else{
					  $description = "ONGOING STORAGE";
					}
					$service_start = $bill_storage_date;
					$service_stop = date('m/d/Y', mktime(0,0,0, date('m', strtotime($bill_storage_date)), date('d', strtotime($bill_storage_date)) + ($bill_duration - 1), date('Y', strtotime($bill_storage_date))));
					$next_service_start = date('m/d/Y', mktime(0,0,0, date('m', strtotime($bill_storage_date)), date('d', strtotime($bill_storage_date)) + $bill_duration, date('Y', strtotime($bill_storage_date))));

					// FINANCE has requested that service_stop is defined with the above rule (duration - 1, instead of just duration) -- 10/16/2012
//					$service_stop = date('m/d/Y', mktime(0,0,0, date('m', strtotime($bill_storage_date)), date('d', strtotime($bill_storage_date)) + $bill_duration, date('Y', strtotime($bill_storage_date))));
//					$next_service_start = date('m/d/Y', mktime(0,0,0, date('m', strtotime($bill_storage_date)), date('d', strtotime($bill_storage_date)) + $bill_duration + 1, date('Y', strtotime($bill_storage_date))));

					$asset_code = getAsset($full_whse_location, $conn);
				}

//				echo "minthresh - ".$above_minimum_threshold."   rate - ".$rate."   pltexcep - ".$pallet_exception."\n";
				if(!$bill_pallet){
					// not creating a bill.  Was it because this pallet is legitamately done billing?
					if(!$above_minimum_threshold || ($rate !== false && $rate == 0) || $pallet_exception){
						// pallets was not here during entire bill cycle, OR pallets does not receive bills as per rate table, OR has been specifically and permanently ignored.
						$sql = "UPDATE CARGO_TRACKING
								SET BILL = 'F'
								WHERE PALLET_ID = '".$pallet_id."'
									AND RECEIVER_ID = '".$cust."'
									AND ARRIVAL_NUM = '".$vessel."'";
//						echo $sql."<br>";
						$ora_success = ora_parse($modify_cursor, $sql);
						$ora_success = ora_exec($modify_cursor);
						database_check($ora_success, "Unable to mark No-More-Bills status for ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$vessel.")\r\n");
//						echo "PLT: ".$pallet_id."   Cust: ".$cust."   BillDate: ".$bill_storage_date."  -  F\r\n";
					} else {
						// something about this pallet blew up.  Mark unbillable.  Email will state to recipients that this pallet needs looking into.
						$sql = "UPDATE CARGO_TRACKING
								SET BILL = 'X'
								WHERE PALLET_ID = '".$pallet_id."'
									AND RECEIVER_ID = '".$cust."'
									AND ARRIVAL_NUM = '".$vessel."'";
//						echo $sql."<br>";
						$ora_success = ora_parse($modify_cursor, $sql);
						$ora_success = ora_exec($modify_cursor);
						database_check($ora_success, "Unable to mark Unbillable status for ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$vessel.")\r\n");
//						echo "PLT: ".$pallet_id."   Cust: ".$cust."   BillDate: ".$bill_storage_date."  -  X\r\n";
					}
				} else {
					// LETS DO EEET!
					// we make the detail records first.  the "headers" are aggregates, and done later

					// note:  if this was transferred cargo, we will REVERT to the original ARV# at this point to get them on the same bill, if said ARV# exists.
					// not my method of choice, but I'm not finance ;p
/*					if($receiving_type == "F"){
						$sql = "SELECT NVL(ORIGINAL_ARRIVAL_NUM, 'NONE') THE_ARV FROM CARGO_TRACKING_ADDITIONAL_DATA
								WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$cust."'";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						if(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							if($short_term_row['THE_ARV'] != "NONE"){
								$vessel = $short_term_row['THE_ARV'];
							}
						}
					}*/

					$sql = "INSERT INTO RF_BILLING_DETAIL
								(CUSTOMER_ID, SERVICE_CODE, 
								PALLET_ID, ACTIVITY_NUM, 
								BILLING_NUM, EMPLOYEE_ID, 
								SERVICE_START, SERVICE_STOP, 
								SERVICE_AMOUNT, SERVICE_STATUS, 
								SERVICE_DESCRIPTION, ARRIVAL_NUM, 
								COMMODITY_CODE, INVOICE_NUM, 
								SERVICE_DATE, SERVICE_QTY,
								SERVICE_QTY2, SERVICE_UNIT2,
								SERVICE_NUM, THRESHOLD_QTY, 
								LEASE_NUM, SERVICE_UNIT, 
								SERVICE_RATE, PAGE_NUM,
								CARE_OF, BILLING_TYPE, VARIETY_DESCRIPTION,
								STACKING, ASSET_CODE, DOLE_POOL,
								DOLEPAPER_ID, DOLEPAPER_BOL, DOLEPAPER_ORDER, DOLE_ORIG_CUST)
							VALUES
								('".$bill_to_cust."', '".$service_code."',
								'".$pallet_id."', '1',
								'".$billing_num_detail."', '4',
								to_date('".$service_start."', 'MM/DD/YYYY'), to_date('".$service_stop."', 'MM/DD/YYYY'),
								'".$bill_amount."', 'PREINVOICE',
								'".$description."', '".$org_vessel."',
								'".$bni_comm."', '0',
								to_date('".$service_start."', 'MM/DD/YYYY'), '".$bill_quantity."',
								'".$qty2."', '".$qty2_unit."',
								'1', '0',
								'0', '".$bill_unit."',
								'".$rate."', '1',
								'Y', 'PLT-STRG', '".$variety."',
								'".$stacking."', '".$asset_code."', '".$dole_pool."',
								'".$paper_ID."', '".$paper_bol."', '".$paper_order."', '".$cust."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					$ora_success = ora_exec($modify_cursor);
//					echo $sql."<br>";
					database_check($ora_success, "Unable to make bill for ".$pallet_id." (cust ".$cust." comm ".$rf_comm." vsl ".$vessel.")\r\n");
					$billing_num_detail++;

					// update cargo_tracking with next billing date
					$update_stmt = "update cargo_tracking 
									set billing_storage_date = to_date('".$next_service_start."', 'MM/DD/YYYY') 
									where substr(pallet_id, 1, 25) = '".$pallet_id_trim."' 
										and arrival_num = '".$vessel."' 
										and receiver_id = '".$cust."'";
//					echo $update_stmt."<br>";
					$ora_success = ora_parse($modify_cursor, $update_stmt);
					$ora_success = ora_exec($modify_cursor);
					database_check($ora_success, "Unable to update cargo_tracking for Pallet: ".$pallet_id.".  Failed SQL:  $update_stmt");
//					echo "PLT: ".$pallet_id."   Cust: ".$cust."   BillDate: ".$bill_storage_date."  -  Nextdate: ".$next_service_start."\r\n";
				}

				// next pallet.
			}
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	} 


	// with RF_BILLING_DETAIL set, make the headers in RF_BILLING.

	$total_amount = 0;
	$invoices = 0;
	$email_response_line = "";

	$sql = "select arrival_num, customer_id, commodity_code, service_rate, service_unit, service_unit2, service_code,
				stacking, service_start, service_stop, sum(service_amount) amount, sum(service_qty) quantity,
				sum(service_qty2) qty2, count(*) pallets, decode(count(distinct asset_code), 1, max(asset_code), 'W000') the_asset
			from rf_billing_detail 
			where sum_bill_num is null 
				and service_status = 'PREINVOICE'
				and dole_pool is null
			group by arrival_num, customer_id, commodity_code, service_rate, stacking, service_start, 
				service_unit, service_unit2, service_code, service_stop";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	database_check($ora_success, "Unable to get information from rf_billing_detail for rf_billing");
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$arrival_num = $row['ARRIVAL_NUM'];
		$customer_id = $row['CUSTOMER_ID'];
		$service_code = $row['SERVICE_CODE'];
		$commodity_code = $row['COMMODITY_CODE'];
		$rate = $row['SERVICE_RATE'];
		$stacking = $row['STACKING'];
		$service_start = date('m/d/Y', strtotime($row['SERVICE_START']));
		$service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));
		$amount = $row['AMOUNT'];
		$unit = $row['SERVICE_UNIT'];
		$billed_asset = $row['THE_ASSET'];
		$quantity = $row['QUANTITY'];
		$qty2 = $row['QTY2']; // qty_expected if billed in pallets, or pallets if billed in weight (generally; technically, this will be determined by BNI.RATE)
		$unit2 = $row['SERVICE_UNIT2'];

		// DO WE NEED AN ASSET CODE?

		$prebill_line = "insert into rf_billing 
							(BILLING_NUM, SERVICE_CODE, 
							ARRIVAL_NUM, CUSTOMER_ID, 
							COMMODITY_CODE, ACTIVITY_NUM, 
							EMPLOYEE_ID, SERVICE_STATUS, 
							SERVICE_DESCRIPTION, INVOICE_NUM, 
							SERVICE_NUM, THRESHOLD_QTY, 
							LEASE_NUM, SERVICE_UNIT, SERVICE_UNIT2,
							SERVICE_QTY, SERVICE_QTY2, 
							SERVICE_AMOUNT, STACKING, 
							SERVICE_START, SERVICE_STOP, 
							SERVICE_DATE, LABOR_RATE_TYPE, 
							LABOR_TYPE, PAGE_NUM, 
							CARE_OF, BILLING_TYPE, 
							ASSET_CODE, SERVICE_RATE) 
						values 
							('".$billing_num_header."', '".$service_code."', 
							'".$arrival_num."', '".$customer_id."', 
							'".$commodity_code."', '1', 
							'4', 'PREINVOICE', 
							'STORAGE', '0', 
							'1', '0', 
							'0', '".$unit."', '".$unit2."',
							'".$quantity."', '".$qty2."', 
							'".$amount."', '".$stacking."', 
							to_date('".$service_start."', 'MM/DD/YYYY'), to_date('".$service_stop."', 'MM/DD/YYYY'), 
							to_date('".$service_start."', 'MM/DD/YYYY'), '', 
							'', '1', 
							'Y', 'PLT-STRG', 
							'".$billed_asset."', '".$rate."')";
		$ora_success = ora_parse($modify_cursor, $prebill_line);
		$ora_success = ora_exec($modify_cursor);
		database_check($ora_success, "Unable to make Bill Heading.");
//		echo $sql."<br>";

		$email_response_line .= "Prebill #".$billing_num_header."  -  Cust ".$customer_id." -- ".$quantity." ".$unit." = $".number_format($amount, 2)."\r\n";

		if ($start_billing_num == 0) {
		  $start_billing_num = $billing_num_header;
		}

		if($stacking == ""){
			$stacking_sql = "and stacking is null";
		} else {
			$stacking_sql = "and stacking = '".$stacking."'";
		}

		$update_stmt = "update rf_billing_detail 
						set sum_bill_num = '".$billing_num_header."' 
						where arrival_num = '".$arrival_num."' 
							and commodity_code = '".$commodity_code."' 
							and customer_id = '".$customer_id."'
							and service_rate = '".$rate."'
							".$stacking_sql." 
							and service_status = 'PREINVOICE' 
							and service_start = to_date('".$service_start."', 'MM/DD/YYYY') 
							and service_stop = to_date('".$service_stop."', 'MM/DD/YYYY')
							and service_unit = '".$unit."'
							and service_unit2 = '".$unit2."'
							and service_code = '".$service_code."'
							and sum_bill_num is null
							and dole_pool is null";
//		echo $update_stmt."<br>";
		$ora_success = ora_parse($modify_cursor, $update_stmt);
		$ora_success = ora_exec($modify_cursor);
		database_check($ora_success, "Unable to join details to main bill.");

		// Increment the rf_billing_num
		$billing_num_header++;

		// Update the reports total
		$total_amount += $amount;
		$invoices++;
	}

	$sql = "select customer_id, commodity_code, service_rate, service_unit, service_unit2, service_code,
				stacking, service_start, service_stop, sum(service_amount) amount, sum(service_qty) quantity,
				sum(service_qty2) qty2, count(*) pallets, decode(count(distinct asset_code), 1, max(asset_code), 'W000') the_asset
			from rf_billing_detail 
			where sum_bill_num is null 
				and service_status = 'PREINVOICE'
				and dole_pool is not null
			group by customer_id, commodity_code, service_rate, stacking, service_start, 
				service_unit, service_unit2, service_code, service_stop";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	database_check($ora_success, "Unable to get information from rf_billing_detail for rf_billing");
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$arrival_num = "0";
		$customer_id = $row['CUSTOMER_ID'];
		$service_code = $row['SERVICE_CODE'];
		$commodity_code = $row['COMMODITY_CODE'];
		$rate = $row['SERVICE_RATE'];
		$stacking = $row['STACKING'];
		$service_start = date('m/d/Y', strtotime($row['SERVICE_START']));
		$service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));
		$amount = $row['AMOUNT'];
		$unit = $row['SERVICE_UNIT'];
		$billed_asset = $row['THE_ASSET'];
		$quantity = $row['QUANTITY'];
		$qty2 = $row['QTY2']; // qty_expected if billed in pallets, or pallets if billed in weight (generally; technically, this will be determined by BNI.RATE)
		$unit2 = $row['SERVICE_UNIT2'];

		// DO WE NEED AN ASSET CODE?

		$prebill_line = "insert into rf_billing 
							(BILLING_NUM, SERVICE_CODE, 
							ARRIVAL_NUM, CUSTOMER_ID, 
							COMMODITY_CODE, ACTIVITY_NUM, 
							EMPLOYEE_ID, SERVICE_STATUS, 
							SERVICE_DESCRIPTION, INVOICE_NUM, 
							SERVICE_NUM, THRESHOLD_QTY, 
							LEASE_NUM, SERVICE_UNIT, SERVICE_UNIT2,
							SERVICE_QTY, SERVICE_QTY2, 
							SERVICE_AMOUNT, STACKING, 
							SERVICE_START, SERVICE_STOP, 
							SERVICE_DATE, LABOR_RATE_TYPE, 
							LABOR_TYPE, PAGE_NUM, 
							CARE_OF, BILLING_TYPE, 
							ASSET_CODE, SERVICE_RATE) 
						values 
							('".$billing_num_header."', '".$service_code."', 
							'".$arrival_num."', '".$customer_id."', 
							'".$commodity_code."', '1', 
							'4', 'PREINVOICE', 
							'STORAGE', '0', 
							'1', '0', 
							'0', '".$unit."', '".$unit2."',
							'".$quantity."', '".$qty2."', 
							'".$amount."', '".$stacking."', 
							to_date('".$service_start."', 'MM/DD/YYYY'), to_date('".$service_stop."', 'MM/DD/YYYY'), 
							to_date('".$service_start."', 'MM/DD/YYYY'), '', 
							'', '1', 
							'Y', 'PLT-STRG', 
							'".$billed_asset."', '".$rate."')";
		$ora_success = ora_parse($modify_cursor, $prebill_line);
		$ora_success = ora_exec($modify_cursor);
		database_check($ora_success, "Unable to make Bill Heading.");
//		echo $sql."<br>";

		$email_response_line .= "Prebill #".$billing_num_header."  -  Cust ".$customer_id." -- ".$quantity." ".$unit." = $".number_format($amount, 2)."\r\n";

		if ($start_billing_num == 0) {
		  $start_billing_num = $billing_num_header;
		}

		if($stacking == ""){
			$stacking_sql = "and stacking is null";
		} else {
			$stacking_sql = "and stacking = '".$stacking."'";
		}

		$update_stmt = "update rf_billing_detail 
						set sum_bill_num = '".$billing_num_header."' 
						where commodity_code = '".$commodity_code."' 
							and customer_id = '".$customer_id."'
							and service_rate = '".$rate."'
							".$stacking_sql." 
							and service_status = 'PREINVOICE' 
							and service_start = to_date('".$service_start."', 'MM/DD/YYYY') 
							and service_stop = to_date('".$service_stop."', 'MM/DD/YYYY')
							and service_unit = '".$unit."'
							and service_unit2 = '".$unit2."'
							and service_code = '".$service_code."'
							and sum_bill_num is null
							and dole_pool is not null";
//		echo $update_stmt."<br>";
		$ora_success = ora_parse($modify_cursor, $update_stmt);
		$ora_success = ora_exec($modify_cursor);
		database_check($ora_success, "Unable to join details to main bill.");

		// Increment the rf_billing_num
		$billing_num_header++;

		// Update the reports total
		$total_amount += $amount;
		$invoices++;
	}





	if ($start_billing_num != 0) {
		$end_billing_num = $billing_num_header - 1;
	}

	$ora_success = ora_commit($conn);
	database_check($ora_success, "Unable to commit rf_billing changes");

	if($email_response_line != "" || $email_nobill_reason != ""){

		if($email_response_line != ""){
			$return_text .= "<good>".$email_response_line."</good>";
		}
		if($email_nobill_reason != ""){
			$return_text .= "<bad>".$email_nobill_reason."</bad>\r\n";
		}
	}
//	echo $return_text;

	// lastly, we need to loop to make sure there were no "cascading" storage charges.  ONLY RECURSE if a bill was generated.
	if($invoices > 0){
		$return_text .= Make_RF_Prebills($conn, $recurse_barcode, $recurse_date_rec_start, $recurse_date_rec_end, $recurse_bill_date_start, $recurse_bill_date_end, $recurse_vessel, $recurse_cust, $recurse_comm);
	}

	return $return_text;
}





















function DoNotBill($whse, $conn){
	// return true if this pallet should NOT bill
	$Short_Term_Cursor = ora_open($conn);

	// any reasons that a pallet should not get billed that are outside the scope of "rate" go here.
	$sql = "SELECT COUNT(*) THE_COUNT FROM RF_STORAGE_EXCEPTIONS
			WHERE WAREHOUSE_LOCATION = '".$whse."'";
//	echo $sql."\n";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_row['THE_COUNT'] >= 1){
		return true;
	}

	return false;
}

function GetBNICommAndUNIT($rf_comm, &$bni_comm, $cust, $receiving_type, &$qty_unit, $conn){
	$Short_Term_Cursor = ora_open($conn);

	if($cust == 9722 && $receiving_type == "V"){
		$bni_comm = "5103";
		$qty_unit = "PLT";
		return true;
	} elseif($cust == 9722 && $receiving_type == "T"){
		$bni_comm = "5298";
		$qty_unit = "PLT";
		return true;
	}

	$sql = "SELECT BNI_COMM, DEFAULT_QTY_UNIT
			FROM RF_TO_BNI_COMM
			WHERE RF_COMM = '".$rf_comm."'";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return false;
	} else {
		$bni_comm = $short_term_row['BNI_COMM'];
		if($qty_unit == ""){
			$qty_unit = $short_term_row['DEFAULT_QTY_UNIT'];
		}
		return true;
	}
}

function Which_Date_Is_First($a, $b){
	// returns a negative # if $a is earlier than $b, a positive # if $b is earlier than $a, and 0 if they are equal
	if($a == $b){
		return 0;
	}

	// they aren't equal; figure the difference
	$a_temp = explode("/", $a);
	$b_temp = explode("/", $b);
//	print_r($a_temp)."<br>";
//	print_r($b_temp)."<br>";

	// year check
	if($a_temp[2] < $b_temp[2]){
		return -1;
	} elseif($a_temp[2] > $b_temp[2]){
		return 1;
	}

	//month check
	if($a_temp[0] < $b_temp[0]){
		return -1;
	} elseif($a_temp[0] > $b_temp[0]){
		return 1;
	}

	// day check
	if($a_temp[1] < $b_temp[1]){
		return -1;
	} elseif($a_temp[1] > $b_temp[1]){
		return 1;
	}

	return 0; // this only happens on an error, so as not to crash the program.
}

function getAsset($full_whse_location, $conn){
	$Short_Term_Cursor = ora_open($conn);

//	$temp = split(" ", $full_whse_location);
//	$location_split_whsebox_only = $temp[0];

	// check if exact match
	$sql = "SELECT ASSET_CODE FROM ASSET_PROFILE WHERE SERVICE_LOCATION_CODE = '".$full_whse_location."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no match.  Check if generic "WING" of the location clears.
		$wing = substr($full_whse_location, 0, 1);
		$sql = "SELECT ASSET_CODE FROM ASSET_PROFILE WHERE SERVICE_LOCATION_CODE = 'WING ".$wing."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// still no match.  return default.
			return "0000";
		} else {
			// matched to "WING X".  return.
			return $short_term_row['ASSET_CODE'];
		}
	} else {
		// matched to exact parameter.
		return $short_term_row['ASSET_CODE'];
	}
}

function database_check($oracle_return, $message){
  global $conn, $body, $mailTO, $mailsubject, $mailheaders;
  if(!$oracle_return){
    // Here, we have encountered an error! (I'm leaving this comment line in from the last rf-storage program for Humor purposes ;p)
    $body .= $message;
    $body .= "\nTS --- Oracle Error: " . ora_errorcode($conn);
    $body .= "\nUser --- THIS JOB HAS FAILED!\n";
    ora_rollback($conn);
    $body .= "TRANSACTION HAS BEEN ROLLED BÆCK!\n";
    mail("lstewart@port.state.de.us", "SCANNED storage attempt failure", $body, "Cc: archive@port.state.de.us\r\n");
	echo "This transaction could not be processed.  As a precaution, this script has been stopped.  Please contact TS for further details.";
    exit;
  }
}

function Get_Max_In_House($conn, $bill_storage_date, $bill_duration, $barcode, $vessel, $cust){
	$Short_Term_Cursor = ora_open($conn);

	// step 1:  get qty_in_house at start of billing storage date
	// yes, that's a lot of decodes in the select statement.  it says the following:
	// activity number 1 is always added, and if it's not act# 1, then the service code takes over.
	// service code 9 gets its qty_change inverted before the arithmetic (returns are wierd), and since act# >1 shows OUTBOUND
	// activities, we multiply whatever that number is by -1 before adding.  
	// Good thing the only person who has to memorize this is putting it in code ;p.
	// ALSO:  voids are excluded; this includes all service code 12s, AND any "voided" cargo.  we don't want to charge people for errors on our part.
	$sql = "SELECT NVL(SUM(DECODE(ACTIVITY_NUM, 1, QTY_CHANGE, -1 * DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), QTY_CHANGE))), 0) THE_START 
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$barcode."'
				AND CUSTOMER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$vessel."'
				AND SERVICE_CODE != '12'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND DATE_OF_ACTIVITY <= TO_DATE('".$bill_storage_date."', 'MM/DD/YYYY')";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$running_qty = $short_term_row['THE_START'];
	$max_ih_qty = $short_term_row['THE_START'];
//	echo $barcode." ".$max_ih_qty."<br>";

	// next, we check every activity from that point until the end of the storage period.  each time it gets "higher", we update the max_in_house value.
	$sql = "SELECT DECODE(ACTIVITY_NUM, 1, QTY_CHANGE, -1 * DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), QTY_CHANGE)) THE_CHANGE
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$barcode."'
				AND CUSTOMER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$vessel."'
				AND SERVICE_CODE != '12'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND DATE_OF_ACTIVITY > TO_DATE('".$bill_storage_date."', 'MM/DD/YYYY')
				AND DATE_OF_ACTIVITY <= TO_DATE('".$bill_storage_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') + ".$bill_duration."
			ORDER BY ACTIVITY_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$running_qty += $short_term_row['THE_CHANGE'];

		if($running_qty > $max_ih_qty){
			$max_ih_qty = $running_qty;
		}
//		echo $barcode." ".$max_ih_qty."<br>";
	}

	return $max_ih_qty;
}

function Minimum_Bill_Threshold_Check($conn, $max_qty_in_house_during_period, $rf_comm, $cust){
	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT QTY_THRESHOLD
			FROM COMMODITY_PROFILE CP, MINIMUM_INHOUSE_THRESHOLD MIT
			WHERE CP.COMMODITY_TYPE = MIT.COMMODITY_TYPE
				AND CP.COMMODITY_CODE = '".$rf_comm."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// this doesn't have a specific minimum threshold.  assume zero.
		$threshold = 1;
	} else {
		$threshold = $short_term_row['QTY_THRESHOLD'];
	}

	// KOPKE IS treated differently because inventory does not get the recoups in on time and kopke is upset about getting a bill that they should not (Adam W., DEC 2015)
	if($cust == "1131"){
		$threshold = "41";
	}

	// threshold value is the # of QTY_IN_HOUSE where we consider it present for billing.
	if($max_qty_in_house_during_period >= $threshold){
		return true;
	} else {
		return false;
	}
}

function Get_display($text, $display_type){

//	echo "DISPLAY TYPE:<br><br>".$display_type."<br><br>";
	if($display_type == "html"){
		$text = str_replace("<good>", "<font color=\"#0000FF\">", $text);
		$text = str_replace("</good>", "</font>", $text);
		$text = str_replace("<bad>", "<font color=\"#FF0000\">", $text);
		$text = str_replace("</bad>", "</font>", $text);
		$text = str_replace("\r\n", "<br>", $text);
		return $text;
	} elseif($display_type == "text"){
		$text = str_replace("<good>", "", $text);
		$text = str_replace("</good>", "", $text);
		$text = str_replace("<bad>", "***\r\n", $text);
		$text = str_replace("</bad>", "***", $text);
		return $text;
	} else { // bad/no call, return text only
		return $text;
	}
}

function Get_Original_Vessel($conn, $vessel){
	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT * FROM LR_CONVERSION WHERE OPT_ARRIVAL_NUM = '".$vessel."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return $vessel;
	} else {
		return $short_term_row['LR_NUM'];
	}
}

