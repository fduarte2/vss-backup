<?
/*
*	Booking Paper Scanner, originally written Apr-Jul 2010.
*
*	As part of the massive migration of scanner programs to PHP
*	Note:  for the moment, this is all BARNETT SPECIFIC, just like the abitibi
*	Scanner is all ABITIBI SPECIFIC (314 and 312, respectively).
*	If/When the 2 customers use the same scanner, then customer_id
*	will need to be entered.
********************************************************************************/

/*******************************************************************************
* MENU FUNCTIONS START HERE ****************************************************
********************************************************************************/

function Unload_Cargo($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$Arrival = "";
	$wing = "";
	
	fresh_screen("BOOKING SCANNER\nUnload Cargo\nEnter X to exit.");
/*	echo "CUST:";
	fscanf(STDIN, "%s\n", $Customer);
	$Customer = strtoupper($Customer);
	if ($Customer == "X") {
		return;
	} */
//	$Customer = "314";
/*
	// get and validate cust #
	while ($Customer == "" || $Customer == "Invalid") {
		fresh_screen("BOOKING Paper\nUnload Cargo\nEnter X to exit.");
		if ($Customer != "") {
			echo "Invalid Cust #\n";
		}
		echo "Customer#:\n";
		fscanf(STDIN, "%s\n", $Customer);
		$Customer = strtoupper($Customer);
		if ($Customer == "X") {
			return;
		}
		$Customer = remove_badchars($Customer);

		if (!is_numeric($Customer)) {
			$Customer = "Invalid";
		} elseif ($Customer != "314" && $Customer != "338") {
			$Customer = "Invalid";
		} else {
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Customer."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1b)");
			if (!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				$Customer = "Invalid";
			}
		}
	}
*/
	
	//Get the arrival #
	while ($Arrival == "") {
		fresh_screen("BOOKING SCANNER\nUnload Cargo\nEnter X to exit.");
//		echo "Cust: ".$Customer."\n";
		echo "ARV#: ";
//		fscanf(STDIN, "%s\n", $Arrival);
		fscanf(STDIN, "%[^[]]\n", $Arrival);
		$Arrival = trim(strtoupper($Arrival));
		if ($Arrival == "X") {
			return;
		}
//		echo "ARRIVAL #:".$Arrival."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$Arrival = remove_badchars($Arrival);
//		echo "ARRIVAL #:".$Arrival."\n";
//		fscanf(STDIN, "%s\n", $junk);
	}
	
	//Get the warehouse letter
	while ($wing == "") {
		fresh_screen("BOOKING SCANNER\nUnload Cargo\nEnter X to exit.");
//		echo "Cust: ".$Customer."\n";
		echo "ARV#: ".$Arrival."\n\n";
		echo "Wing:\n";
		fscanf(STDIN, "%s\n", $wing);
		$wing = strtoupper($wing);
		if ($wing == "X") {
			return;
		}
		$wing = remove_badchars($wing);
	}

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);
	
	//Get the number of received out of total rolls for each warehouse code
	$sql = "SELECT DISTINCT
				ad.warehouse_code,
				COUNT(ad.warehouse_code) OVER (PARTITION BY ad.warehouse_code) AS num_of_rolls,
				SUM(DECODE(ct.date_received, NULL, 0, 1)) OVER (PARTITION BY ad.warehouse_code) AS num_of_rolls_received
			FROM booking_additional_data ad
			LEFT JOIN cargo_tracking ct
				ON ct.pallet_id = ad.pallet_id
				AND ct.arrival_num = ad.arrival_num
				AND ct.receiver_id = ad.receiver_id
			WHERE
				ad.arrival_num = '$Arrival'
			ORDER BY ad.warehouse_code ASC";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(UC5a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(UC5b)");
	
	$whCodes = '';
	while (ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		$whCodes .= "\n  {$row['WAREHOUSE_CODE']}: {$row['NUM_OF_ROLLS_RECEIVED']} of {$row['NUM_OF_ROLLS']} recv'd";
	}

	fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
------------------------
ARV #: $Arrival
Wing: $wing
Warehouse Codes:$whCodes");
	
	//Get the barcode of the roll to receive
	echo "BC:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	
	while (strtoupper($Barcode) != "X") {

		$sql = "SELECT
					NVL(COUNT(*), 0) THE_COUNT
				FROM CARGO_TRACKING
				WHERE
					PALLET_ID = '".$Barcode."'
					AND REMARK = 'BOOKINGSYSTEM'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(UC2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(UC2b)");
		ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if ($row['THE_COUNT'] == "0") {
			// incorrect vessel / already received / not in DB
			$sql = "SELECT
						TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE
					FROM CARGO_TRACKING
					WHERE
						PALLET_ID = '".$Barcode."'
						AND REMARK = 'BOOKINGSYSTEM'";
			
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC7b)");
			
			if (!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				// incorrect vessel / not in DB
				$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(UC8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(UC8b)");
				
				if (!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					// not in DB
					fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
**INVALID ROLL**
CONTACT INVENTORY", "bad");
				} else {
					// wrong arrival #
					fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
**ROLL DOES NOT
  MATCH ARV #**", "bad");
				}
			} else {
				// already received
				fresh_screen("BOOKING SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\n**ALREADY RECEIVED**\nAT: ".$short_term_row['THE_DATE'], "bad");
				fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
**ALREADY RECEIVED**
AT: ".$short_term_row['THE_DATE'], "bad");
			}
		} elseif ($row['THE_COUNT'] > 1) {
			// duplicate Barcode / arrival#
			fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
**DUPLICATE BARCODE**
CONTACT INVENTORY", "bad");
		} else {
			$sql = "SELECT *
					FROM
						CARGO_TRACKING CT,
						BOOKING_ADDITIONAL_DATA BAD,
						BOOKING_PAPER_GRADE_CODE BPGC 
					WHERE
						CT.ARRIVAL_NUM = '".$Arrival."' 
						AND CT.PALLET_ID = '".$Barcode."'
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC9a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC9b)");
			ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			
			// Receive Pallet
			$booking_num = $row['BOOKING_NUM'];
			if ($booking_num == "") {
				// no booking#
				fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
**NO BOOKING #**
CONTACT INVENTORY", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif ($row['DATE_RECEIVED'] != "") {
				fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
**ALREADY RECEIVED**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} else {
				$warehouse_code = $row['WAREHOUSE_CODE'];
				$disp_weight = $row['WEIGHT']." ".$row['WEIGHT_UNIT'];
				$disp_width = $row['WIDTH']." ".$row['WIDTH_MEAS'];
				$disp_dia = $row['DIAMETER']." ".$row['DIAMETER_MEAS'];
				$code = $row['SSCC_GRADE_CODE'];
				$Customer = $row['RECEIVER_ID'];
	//			$Customer = $row['RECEIVER_ID'];

				$sql = "UPDATE
							CARGO_TRACKING
						SET
							DATE_RECEIVED = SYSDATE,
							WAREHOUSE_LOCATION = '".$wing."'
						WHERE
							RECEIVER_ID = '".$Customer."'
							AND ARRIVAL_NUM = '".$Arrival."'
							AND PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Pallet\nReceive Date\n(UC3a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Pallet\nReceive Date\n(UC3b)");

				$sql = "INSERT INTO CARGO_ACTIVITY (
							ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							ORDER_NUM,
							CUSTOMER_ID,
							DATE_OF_ACTIVITY,
							PALLET_ID,
							ARRIVAL_NUM,
							QTY_LEFT
						)
						VALUES (
							'1',
							'8',
							'1',
							'$emp_no',
							'$Arrival',
							'$Customer',
							SYSDATE,
							'$Barcode',
							'$Arrival',
							'1'
						)";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to make\nactivity record\n(UC4a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to make\nactivity record\n(UC4b)");

				ora_commit($rf_conn);

				fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
WAREHOUSE CODE: $warehouse_code
WIDTH: $disp_width
DIA: $disp_dia
WT: $disp_weight
BK#: $booking_num
GRADE CD: $code");
				echo "DMG?  (Y/N)";
				fscanf(STDIN, "%s\n", $DMG);
				if (strtoupper($DMG) == "Y") {
					Advanced_Add_Damage($CID, $Customer, $booking_num, $Arrival, $Barcode, "Pre-Arrival");
				} 

				ora_commit($rf_conn);
				fresh_screen(
"BOOKING SCANNER
Unload Cargo. X to exit.
--------------------
ARV #: $Arrival
BC: $Barcode
WAREHOUSE CODE: $warehouse_code
WIDTH: $disp_width
DIA: $disp_dia
WT: $disp_weight
BK#: $booking_num
GRADE CD: $code
RECEIVED!");

			}
		}
		
		//Get the number of received out of total rolls for each warehouse code
		$sql = "SELECT DISTINCT
					ad.warehouse_code,
					COUNT(ad.warehouse_code) OVER (PARTITION BY ad.warehouse_code) AS num_of_rolls,
					SUM(DECODE(ct.date_received, NULL, 0, 1)) OVER (PARTITION BY ad.warehouse_code) AS num_of_rolls_received
				FROM booking_additional_data ad
				LEFT JOIN cargo_tracking ct
					ON ct.pallet_id = ad.pallet_id
					AND ct.arrival_num = ad.arrival_num
					AND ct.receiver_id = ad.receiver_id
				WHERE
					ad.arrival_num = '$Arrival'
				ORDER BY ad.warehouse_code ASC";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(UC6a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(UC6b)");
		
		$whCodes = '';
		while (ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			$whCodes .= "\n  {$row['WAREHOUSE_CODE']}: {$row['NUM_OF_ROLLS_RECEIVED']} of {$row['NUM_OF_ROLLS']} recv'd";
		}
		echo "Warehouse Codes:$whCodes\nNEXT BC:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function Damage($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
/*	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
	$emp_no = $short_term_data_row['THE_EMP'];*/
	$emp_no = get_emp_no($CID);

	fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.");

	while ($booking == "") {
		echo "BOOKING#:";
		fscanf(STDIN, "%s\n", $booking);
		$booking = strtoupper($booking);
		if ($booking == "X") {
			return;
		} 

		$booking = remove_badchars($booking);

	}

	fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking);

	echo "BARCODE:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while (strtoupper($Barcode) != "X") {

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, CT.RECEIVER_ID, CT.ARRIVAL_NUM 
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE CT.PALLET_ID = '".$Barcode."' 
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND BAD.BOOKING_NUM = '".$booking."'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(D1a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(D1b)");

		if (!ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			// Pallet incorrect / not in DB
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(D2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(D2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($short_term_row['THE_COUNT'] > 0) {
				// wrong BK #
				fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking."\nBC: ".$Barcode."\n**ROLL DOES NOT\nMATCH BOOKING#**");
			} else {
				fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking."\nBC: ".$Barcode."\n**INVALID ROLL**\nCONTACT INVENTORY");
			}
		} elseif ($row['THE_DATE'] == "NONE") {
			// Pallet not received, can not ship
			fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking."\nBC: ".$Barcode."\n**CAN NOT SET DMG\nNOT YET RECEIVED**");
		} elseif ($row['QTY_IN_HOUSE'] == "0") {
			// Pallet not received, can not ship
			fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking."\nBC: ".$Barcode."\n**CAN NOT SET DMG\nALREADY SHIPPED**");
		} else {
			$Customer = $row['RECEIVER_ID'];
			$Arrival = $row['ARRIVAL_NUM'];

			fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking."\nBC: ".$Barcode."\nRcvd: ".$row['THE_DATE']."\nPress Enter\nTo continue");
			fscanf(STDIN, "%s\n", $junk);

			Advanced_Add_Damage($CID, $Customer, $booking, $Arrival, $Barcode, "Post-Arrival");
			ora_commit($rf_conn);
			fresh_screen("BOOKING SCANNER\nDamage Rolls\nEnter X to exit.\n\nBK#: ".$booking);
		} 
		echo "\nNEXT BARCODE:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function RollInfo() {
	global $rf_conn;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("BOOKING SCANNER\nRoll Info\nEnter X to exit.");
	echo "BARCODE:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while (strtoupper($Barcode) != "X") {
		$pallet_count = 0;
		$sql = "SELECT
					COUNT(*) THE_COUNT
				FROM
					CARGO_TRACKING CT,
					BOOKING_ADDITIONAL_DATA BAD
				WHERE
					CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get count\n(RI1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get count\n(RI1b)");
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallet_count = $row['THE_COUNT'];

		$sql = "SELECT
					CT.ARRIVAL_NUM,
					WEIGHT,
					WEIGHT_UNIT,
					NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE,
					DECODE(CARGO_STATUS, 'HOLD', 'ON HOLD', 'OK TO SHIP') HOLD_STATUS,
					QTY_IN_HOUSE,
					BAD.BOL,
					CT.RECEIVER_ID,
					SSCC_GRADE_CODE,
					BOOKING_NUM,
					WIDTH,
					WIDTH_MEAS,
					DIAMETER,
					DIAMETER_MEAS,
					bad.warehouse_code
				FROM
					CARGO_TRACKING CT,
					BOOKING_ADDITIONAL_DATA BAD,
					BOOKING_PAPER_GRADE_CODE BPGC
				WHERE
					CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
					AND CT.PALLET_ID = '".$Barcode."'";
//		echo $sql;
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(RI2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(RI2b)");
		while (ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			$pallet_count++;

			$received = $row['THE_DATE'];
			$arrival_num = $row['ARRIVAL_NUM'];
			$wh_code = $row['WAREHOUSE_CODE'];
			$booking = $row['BOOKING_NUM'];
			$hold_status = "--".$row['THE_STATUS'];
			$weight = $row['WEIGHT']." ".$row['WEIGHT_UNIT'];
			$disp_width = $row['WIDTH']." ".$row['WIDTH_MEAS'];
			$disp_dia = $row['DIAMETER']." ".$row['DIAMETER_MEAS'];
			$code = $row['SSCC_GRADE_CODE'];
			$BOL = $row['BOL'];
			$Customer = $row['RECEIVER_ID'];

			$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_DAMAGES 
					WHERE PALLET_ID = '".$Barcode."' 
					AND RECEIVER_ID = '".$Customer."'
					AND ARRIVAL_NUM = '".$arrival_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nDMG Info\n(RI4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nDMG Info\n(RI4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			
			if ($short_term_row['THE_COUNT'] > 0) {
				$DMG = "Y";
			} else {
				$DMG = "N";
			}

			if ($received == "NONE") {
				$status = "Not Received";
				$DMG = "";
			} else {
				if ($row['QTY_IN_HOUSE'] == "0") {
/*					$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YY HH24:MI') THE_DATE FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND PALLET_ID = '".$Barcode."' AND ACTIVITY_DESCRIPTION IS NULL AND ARRIVAL_NUM = '".$arrival_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get act\n(RI3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get act\n(RI3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
*/
//					$status = "Shipped\nORD#: ".$short_term_data_row['ORDER_NUM']."\nON: ".$short_term_data_row['THE_DATE'];
					$status = "Shipped";
				} else {
					$status = "In House";
				} 
			}

			if ($status == "Shipped") {
				$sql = "SELECT ORDER_NUM FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
						AND CUSTOMER_ID = '".$Customer."'
						AND ARRIVAL_NUM = '".$arrival_num."'
						AND SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nDMG Info\n(RI5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nDMG Info\n(RI5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$order_display = "\n ".$short_term_row['ORDER_NUM'];
			} else {
				$order_display = "";
			}

			fresh_screen(
"BOOKING SCANNER
Roll Info. X to exit.
BC: $Barcode
ARV #: $arrival_num
$hold_status
WH CODE: $wh_code
BK#: $booking
WIDTH: $disp_width
GRADE CD: $code
DIA: $disp_dia
WT: $weight
Rcvd: $received
Status: $status.$order_display");

//			echo $pallet_count." of ".$total_pallet_count." Pallets.\n";


			if ($DMG == "Y") {
				echo "DMG HIST(Y/N)?\n";
				fscanf(STDIN, "%s\n", $dmg_check);
				if (strtoupper($dmg_check) == "Y") {
					Damage_Report($Barcode, $Customer, $arrival_num);
				}
			} else {
				echo "No DMG on roll\n";
				fscanf(STDIN, "%s\n", $junk); // to prevent the screen from auto-forwarding
			}

		}

		if ($total_pallet_count == 0) {
			fresh_screen("BOOKING SCANNER\nRoll Info.\nEnter X to exit.\n\nNo roll with\nthat barcode\nin system.");
			fscanf(STDIN, "%s\n", $junk);  // prevent screen from jumping
		} 	
		fresh_screen("BOOKING_SCANNER\nRoll Info.\nEnter X to exit.");
		echo "NEXT BARCODE:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function Load_Truck($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while ($continue_function) { // in case they finish order and want to move to next one 
		$cust = "";
		$order_num = "";
		$Booking = "";

		// get and validate cust #
		while ($cust == "" || $cust == "Invalid") {
			fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.");
			if ($cust != "") {
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if ($cust == "X") {
				return;
			}
			$cust = remove_badchars($cust);

			if (!is_numeric($cust)) {
				$cust = "Invalid";
			} elseif ($cust != "314" && $cust != "338") {
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1b)");
				if (!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$cust = "Invalid";
				}
			}
		}
/*
		while ($Booking == "" || $Booking == "Invalid") {
			fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.");
			if ($Booking != "") {
				echo "Invalid BK #\n";
			}

			echo "BOOKING #:\n";
			fscanf(STDIN, "%s\n", $Booking);
			$Booking = trim(strtoupper($Booking));
			if ($Booking == "X") {
				return;
			}
			$Booking = remove_badchars($Booking);

			$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_ADDITIONAL_DATA WHERE BOOKING_NUM = '".$Booking."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nBooking\n(LT2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nBooking\n(LT2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($short_term_data_row['THE_COUNT'] <= 0) {
				$Booking = "Invalid";
			}
		}
*/
		// get Order
		while ($order_num == "") {
			fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if ($order_num == "X") {
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT BO.STATUS, DS.ST_DESCRIPTION, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, CONTAINER_ID, CUSTOMER_ID 
					FROM BOOKING_ORDERS BO, DOLEPAPER_STATUSES DS 
					WHERE ORDER_NUM = '".$order_num."' 
						AND BO.STATUS = DS.STATUS";
//			echo $sql;
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Status\n(LT2a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Status\n(LT2b)");
			if (!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.\n\n".$order_num."\n***ORDER DOES\nNOT EXIST.\nCANNOT CONTINUE", "bad");
				$order_num = "";
				fscanf(STDIN, "%s\n", $junk);
			} elseif ($select_row['CUSTOMER_ID'] != $cust) { 
				fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.\n\n".$order_num."\n***ORDER IS NOT FOR\nCUSTOMER ".$cust, "bad");
				$order_num = "";
				fscanf(STDIN, "%s\n", $junk);
			} elseif ($select_row['STATUS'] != "2" && $select_row['STATUS'] != "3" && $select_row['STATUS'] != "5") { 
				fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.\n\n".$order_num."\n***ORDER STATUS IS\n".$select_row['ST_DESCRIPTION']."\nCANNOT CONTINUE", "bad");
				$order_num = "";
				fscanf(STDIN, "%s\n", $junk);
			} else {

				$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_ORDER_DETAILS WHERE ORDER_NUM = '".$order_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Status\n(LT3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Status\n(LT3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['THE_COUNT'] <= 0) {
					fresh_screen("BOOKING Paper\nLoad Out\nEnter X to exit.\n\n".$order_num."\n***NO BOOKINGS\nON ORDER.\nCANNOT CONTINUE", "bad");
					$order_num = "";
					fscanf(STDIN, "%s\n", $junk);
				} else {

					$container_ID = $select_row['CONTAINER_ID'];

					$load_date = $select_row['THE_LOAD'];
					if ($load_date != date('m/d/Y')) {
						fresh_screen("BOOKING Paper\nShip-Out Roll", "bad");
						echo $order_num." Has Load\nDate of ".$load_date."\nDo you Wish to\nContinue? Y/N\n(Inventory will\nbe notified)\n";
						$choice = "";
						while ($choice != "Y" && $choice != "N") {
							fscanf(STDIN, "%s\n", $choice);
							$choice = strtoupper($choice);
						}
						if ($choice == "Y") {
							$sql = "UPDATE BOOKING_ORDERS SET LOAD_DATE = TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY') WHERE ORDER_NUM = '".$order_num."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Update\nOrder(LT4a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Update\nOrder(LT4b)");
							ora_commit($rf_conn);

							$notify_body = "LOAD DATE Change enacted by Employee ".$CID.".\r\n";
							$notify_subject = "Booking Scanner Update:  Order ".$order_num." changed to today (".date('m/d/Y').")\r\n";
							// mail($booking_inv_mail_list, $notify_subject, $notify_body, $mailHeaders);

							$sql = "INSERT INTO SCANNER_EMAIL_LOG (FROM_SCANNER_TYPE, DATE_SENT, EMAIL_TO_LIST, EMAIL_BODY, EMAIL_SUBJECT) VALUES ('BOOKING', SYSDATE, '".$booking_inv_mail_list."', '".$notify_body."', '".$notify_subject."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Email\n(LT5a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Email\n(LT5b)");
							ora_commit($rf_conn);
						
						} else {
							$order_num = "";
						}
					}
				}
			}
		}
		$sql = "UPDATE BOOKING_ORDERS SET STATUS = '3' WHERE ORDER_NUM = '".$order_num."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Update\nOrder(LT20a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Update\nOrder(LT20b)");
		ora_commit($rf_conn);

		$Container = "";
		while ($Container == "") {
			fresh_screen("BOOKING Paper\nShip-Out Roll\nX to Exit");
			echo "ORDER: ".$order_num."\n\n";
			if ($container_ID != "") {
				echo "CONT#: ".$container_ID."\n";
			}
			echo "Enter Container#:\n";
			if ($container_ID != "") {
				echo "(Leave Blank to\nKeep Current #)\n";
			}
			fscanf(STDIN, "%s\n", $Container);
			$Container = strtoupper($Container);
			if ($Container == "X") {
				return;
			}

			$Container = remove_badchars($Container);

			if ($Container == "" && $container_ID != "") {
				$Container = $container_ID;
			}

			if ($Container == "" && $container_ID == "") { // no container entered anywhere.
				fresh_screen("BOOKING Paper\nShip-Out Roll\nX to Exit", "bad");
				echo "A Container#\nMust Be Entered\n";
				fscanf(STDIN, "%s\n", $junk);
			} else { // Container is not empty, either because it was entered, or it was copied from DB
				if (strlen($Container) != 11 || !ereg("^([a-zA-Z]{4})([0-9]{7})$", $Container)) {
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\n**INVALID CONTAINER\n".$Container."\nMUST BE 4 LETTERS\nTHEN 7 NUMBERS\nCONTACT SUPV**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$Container = "";
				} else {
					$sql = "UPDATE BOOKING_ORDERS SET CONTAINER_ID = '".$Container."' WHERE ORDER_NUM = '".$order_num."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Cont#\n(LT6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Cont#\n(LT6b)");
					ora_commit($rf_conn);
					$container_ID = $Container;
				}
			}
		}

		$bookings = array();
		$bookings_ordered = array();
		$bookings_scanned = array();

		$sql = "SELECT BOOKING_NUM, SUM(QTY_TO_SHIP) THE_QTY FROM BOOKING_ORDER_DETAILS 
				WHERE ORDER_NUM = '".$order_num."'
				GROUP BY BOOKING_NUM
				ORDER BY BOOKING_NUM";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT7a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT7b)");
		$temp = 0;
		$booking_where_clause = "('notvalid'"; // used later on for validity check
		while (ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			$bookings[$temp] = $short_term_data_row['BOOKING_NUM'];
			$booking_where_clause .= ",'".$short_term_data_row['BOOKING_NUM']."'";
			$bookings_ordered[$temp] = $short_term_data_row['THE_QTY'];
			$temp++;
		}
		$booking_where_clause .= ")"; 
		for($i=0; $i < sizeof($bookings); $i++) {
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
					WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
					AND BAD.BOOKING_NUM = '".$bookings[$i]."'
					AND CA.ORDER_NUM = '".$order_num."'
					AND CA.SERVICE_CODE = '6' 
					AND CA.ACTIVITY_DESCRIPTION IS NULL";
//			echo $sql."\n";
//			fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nOrder Count\n(LT8a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nOrder Count\n(LT8b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$bookings_scanned[$i] = $short_term_data_row['THE_COUNT'];
		}
/*
		// get pallets and cases on order, including partials
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY 
				WHERE ORDER_NUM = '".$order_num."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_DESCRIPTION IS NULL
				AND SERVICE_CODE = '6'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_rolls = $short_term_data_row['THE_COUNT'];
*/

		fresh_screen("BOOKING Paper\nShip-Out Roll\nX to Exit");
		echo "Order: ".$order_num."\n";
		for($i = 0; $i < sizeof($bookings); $i++) {
			echo "BK#".$bookings[$i].":\n  ".(0 + $bookings_scanned[$i])." of ".$bookings_ordered[$i]."\n";
/*			if ($dock_ticket_ordered_dmg[$i] > 0) {
				echo " (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
			} */
		}
		echo "Barcode:\n";

		$Barcode = "";
		while ($Barcode == "") {
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}

		while ($Barcode != "X") {
			$continue_pallet = true;

			// get some data...
			$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, QTY_DAMAGED, CT.ARRIVAL_NUM,
						BAD.BOOKING_NUM, BAD.ORDER_NUM P_O, CT.WEIGHT, CT.WEIGHT_UNIT, CT.CARGO_STATUS, BPGC.SSCC_GRADE_CODE,
						ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
						UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
					WHERE CT.RECEIVER_ID = '".$cust."'
						AND CT.PALLET_ID = '".$Barcode."'
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
						AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'
						AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'
						AND BAD.BOOKING_NUM IN ".$booking_where_clause;
//			echo $sql."\n";
//			fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT9a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT9b)");

			// check if pallet exists at all
			if (!ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				// Pallet incorrect / not in DB
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT10a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT10b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['THE_COUNT'] <= 0) {
					fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**INVALID ROLL\nCONTACT INVENTORY**", "bad");
				} else {
					fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**INCORRECT\nBOOKING#\nFOR THIS ORDER\n\n  **DO NOT SHIP**", "bad");
				}
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			// Pallet on hold
			} elseif ($row['CARGO_STATUS'] == "HOLD") {
				fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**ROLL ON HOLD**\n\n  **DO NOT SHIP**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			// Pallet not received, can not ship
			} elseif ($row['THE_DATE'] == 'NONE') {
				fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**DO NOT SHIP\nNOT YET RECEIVED**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			// already shipped?
			} elseif ($row['QTY_IN_HOUSE'] == "0") {
				$sql = "SELECT ORDER_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT11a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT11b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$already_order = $short_term_data_row['ORDER_NUM'];

				fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**DO NOT SHIP\nALREADY SHIPPED\nON: ".$already_order."**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			// is this roll a reject?
			} elseif ($row['QTY_DAMAGED'] == "1") {
				$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'
							AND BD.DAMAGE_TYPE = PDC.DAMAGE_CODE
							AND PDC.REJECT_LEVEL = 'REJECT'
							AND BD.DATE_CLEARED IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nDamage Info\n(LT12a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nDamage Info\n(LT12b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['THE_COUNT'] > 0) {
					fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**ROLL IS IN\nREJECT STATUS\n\n  **DO NOT SHIP**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// check to make sure part of the order can still support this roll type (We we already know at least one has the right BK#)
			if ($continue_pallet) {
				for($i=0; $i < sizeof($bookings); $i++) {
					if ($bookings[$i] == $row['BOOKING_NUM']) {
						// BK# match.  is this BK filled?
						if ($bookings_scanned[$i] >= $bookings_ordered[$i]) {
							fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**BK#".$bookings[$i]."\nALREADY FULL\nDO NOT SHIP**", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$continue_pallet = false;
						} else {
							// BK not filled, check if the PO/width/dia combo is still needed
							// first, does this combo even exist?
							$sql = "SELECT QTY_TO_SHIP
									FROM BOOKING_ORDER_DETAILS
									WHERE ORDER_NUM = '".$order_num."'
										AND BOOKING_NUM = '".$bookings[$i]."'
										AND SSCC_GRADE_CODE = '".$row['SSCC_GRADE_CODE']."'
										AND P_O = '".$row['P_O']."'
										AND WIDTH = '".$row['THE_WIDTH']."'
										AND DIA = '".$row['THE_DIA']."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nDamage Info\n(LT13a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nDamage Info\n(LT13b)");
							if (!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
								fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**ORDER DOES NOT HAVE\nBK#:".$bookings[$i]."\nPO#:".$row['P_O']."\nWIDTH:".$row['THE_WIDTH']."cm\nDIA:".$row['THE_DIA']."cm\nSSCC:".$row['SSCC_GRADE_CODE']."\nDO NOT SHIP**", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$continue_pallet = false;
							} else {
								// combo exists.  see if we still need some of this specific combo.
								$total_needed = $short_term_data_row['QTY_TO_SHIP'];

								$sql = "SELECT COUNT(*) THE_COUNT
										FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC, CARGO_ACTIVITY CA,
											UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
										WHERE CT.RECEIVER_ID = '".$cust."'
											AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CT.PALLET_ID = BAD.PALLET_ID 
											AND CT.RECEIVER_ID = BAD.RECEIVER_ID
											AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID 
											AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
											AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
											AND BPGC.SSCC_GRADE_CODE = '".$row['SSCC_GRADE_CODE']."'
											AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' 
											AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$row['THE_WIDTH']."'
											AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'
											AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$row['THE_DIA']."'
											AND BAD.ORDER_NUM = '".$row['P_O']."'
											AND BAD.BOOKING_NUM = '".$bookings[$i]."'
											AND CA.SERVICE_CODE = '6'
											AND CA.ORDER_NUM = '".$order_num."'
											AND CA.ACTIVITY_DESCRIPTION IS NULL";
//								echo $sql."\n";
//								fscanf(STDIN, "%s\n", $junk);
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Unable to get\nDamage Info\n(LT14a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Unable to get\nDamage Info\n(LT14b)");
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$total_so_far_this_combo = $short_term_data_row['THE_COUNT'];

								if ($total_so_far_this_combo >= $total_needed) {
									fresh_screen("BOOKING SCANNER\nLoad Out Roll\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$Barcode."\n**ORDER FULL OF\nBK#:".$bookings[$i]."\nPO#:".$row['P_O']."\nWIDTH:".$row['THE_WIDTH']."cm\nDIA:".$row['THE_DIA']."cm\nSSCC:".$row['SSCC_GRADE_CODE']."\n\n  **DO NOT SHIP**", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$continue_pallet = false;
								}
							}
						}
					}
				}
			}




			// get confirmation
			if ($continue_pallet) {
/*				$sql = "SELECT * FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
						WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CT.PALLET_ID = '".$Barcode."'
						AND CT.RECEIVER_ID = '".$cust."'
						AND CT.ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b)");
				ora_fetch_into($short_term_data_cursor, $data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
*/
				$sub_choice = "default";
				

				while ($sub_choice != "" && $sub_choice != "X") {
					$act_num = get_max_activity_num($cust, $Barcode, $row['ARRIVAL_NUM']);

					fresh_screen("BOOKING Paper\nLoad Out Roll\nEnter X to exit.\nConfirmation");
					echo $Barcode."\n";
					echo "WT: ".$row['WEIGHT']." ".$row['WEIGHT_UNIT']."\n";
					echo "DIA: ".$row['THE_DIA']."cm\n";
					echo "WD: ".$row['THE_WIDTH']."cm\n";
					echo "SSCC: ".$row['SSCC_GRADE_CODE']."\n";
					echo "BK#: ".$row['BOOKING_NUM']."\n";
					echo "Enter=OK X=Exit\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if ($sub_choice == "X") {
						$continue_pallet = false;
					}
				}
			}

			if ($continue_pallet) {
				if (!is_max_activity_num($act_num, $cust, $Barcode, $row['ARRIVAL_NUM'])) {
					fresh_screen("BOOKING Paper\nLoad Out Roll\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}


			// have confirmation, "ship" pallet.
			if ($continue_pallet) {
				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 0
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet\n(LT15a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet\n(LT15b)");
/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT16a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT16b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$max_act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				$sql = "INSERT INTO CARGO_ACTIVITY 
							(ACTIVITY_NUM, 
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID, 
							CUSTOMER_ID, 
							ARRIVAL_NUM, 
							DATE_OF_ACTIVITY, 
							ORDER_NUM,
							PALLET_ID,
							QTY_LEFT)
						VALUES
							('".($act_num + 1)."',
							'6',
							'1',
							'".$emp_no."',
							'".$cust."',
							'".$row['ARRIVAL_NUM']."',
							SYSDATE,
							'".$order_num."',
							'".$Barcode."',
							'0')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet\n(LT17a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet\n(LT17b)");

//				$total_rolls++;
				ora_commit($rf_conn);
			}

			// refresh the screen with updated value.
			$bookings = array();
			$bookings_ordered = array();
			$bookings_scanned = array();

			$sql = "SELECT BOOKING_NUM, SUM(QTY_TO_SHIP) THE_QTY FROM BOOKING_ORDER_DETAILS 
					WHERE ORDER_NUM = '".$order_num."'
					GROUP BY BOOKING_NUM
					ORDER BY BOOKING_NUM";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT18a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT18b)");
			$temp = 0;
//			$booking_where_clause = "('notvalid'"; // used later on for validity check
			while (ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				$bookings[$temp] = $short_term_data_row['BOOKING_NUM'];
//				$booking_where_clause .= ",'".$short_term_data_row['BOOKING_NUM']."'";
				$bookings_ordered[$temp] = $short_term_data_row['THE_QTY'];
				$temp++;
			}
//			$booking_where_clause = ")"; 
			for($i=0; $i < sizeof($bookings); $i++) {
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
						WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
							AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
							AND BAD.BOOKING_NUM = '".$bookings[$i]."'
							AND CA.ORDER_NUM = '".$order_num."'
							AND CA.SERVICE_CODE = '6' 
							AND CA.ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nOrder Count\n(LT19a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nOrder Count\n(LT19b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$bookings_scanned[$i] = $short_term_data_row['THE_COUNT'];
			}
			fresh_screen("BOOKING Paper\nShip-Out Roll\nX to Exit");
			echo "Order: ".$order_num."\n";
			for($i = 0; $i < sizeof($bookings); $i++) {
				echo "BK#".$bookings[$i].":\n  ".(0 + $bookings_scanned[$i])." of ".$bookings_ordered[$i]."\n";
	/*			if ($dock_ticket_ordered_dmg[$i] > 0) {
					echo " (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
				} */
			}
			if (array_sum($bookings_scanned) == array_sum($bookings_ordered)) {
				echo "Order Filled.\n";
				$sealnum = "";
				while ($sealnum == "") {
					echo "Scan Seal#\n";
					fscanf(STDIN, "%s\n", $sealnum);
					$sealnum = strtoupper(strip_to_alphanumeric($sealnum));

					if ($sealnum == "") {
						// do nothing, they skipped it for some reason
					} elseif ($sealnum != "X") {
						$seal_confirm = "";
						fresh_screen("BOOKING Paper\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$order_num);
						echo "Container\n ".$Container."\n";
						echo array_sum($bookings_scanned)." of ".array_sum($bookings_ordered)." Scanned\n";
						echo "Seal#\n ".$sealnum."\n";
						echo "\"Enter\" to Accept\n";
						echo "\"X\" To Cancel\n";
						fscanf(STDIN, "%s\n", $seal_confirm);
						$seal_confirm = strtoupper(strip_to_alphanumeric($seal_confirm));
						if ($seal_confirm == "") {
							$sql = "UPDATE BOOKING_ORDERS SET SEAL_NUM = '".$sealnum."' WHERE ORDER_NUM = '".$order_num."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(LT21a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(LT21b)");
							$sql = "UPDATE BOOKING_ORDERS SET STATUS = '6' WHERE ORDER_NUM = '".$order_num."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(LT22a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(LT22b)");
							ora_commit($rf_conn);
						}
					}
				}
/*
				$sql = "UPDATE BOOKING_ORDERS SET STATUS = '6' WHERE ORDER_NUM = '".$order_num."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nfinish order\n(LT20a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nfinish order\n(LT20b)");
				ora_commit($rf_conn);*/
				$Barcode = "X";
//				fscanf(STDIN, "%s\n", $junk);
			} else {
				$Barcode = "";
				echo "Barcode:\n";
				fscanf(STDIN, "%s\n", $Barcode);
				$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
				while ($Barcode == "") {
					fscanf(STDIN, "%s\n", $Barcode);
					$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
				}
			}
		}
	}
}

function VoidOUTPallet($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$cust = "";
	$order_num = "";

	$continue_function = true;

	while ($continue_function) { // in case they finish order and want to move to next one 
		// get and validate cust #
		while ($cust == "" || $cust == "Invalid") {
			fresh_screen("BOOKING Paper\nVoid Outbound\nEnter X to exit.");
			if ($cust != "") {
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if ($cust == "X") {
				return;
			}
			$cust = remove_badchars($cust);

			if (!is_numeric($cust)) {
				$cust = "Invalid";
			} elseif ($cust != "314" && $cust != "338") {
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VOut1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VOut1b)");
				if (!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$cust = "Invalid";
				}
			}
		}

		// get Order
		$order_num = "";
		while ($order_num == "") {
			fresh_screen("BOOKING Paper\nVoid Outbound\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if ($order_num == "X") {
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY 
					WHERE ORDER_NUM = '".$order_num."'
					AND CUSTOMER_ID = '".$cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($short_term_data_row['THE_COUNT'] <= 0) {
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} else {
				$sql = "SELECT STATUS FROM BOOKING_ORDERS 
						WHERE ORDER_NUM = '".$order_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['STATUS'] == "6") {
					echo "Order Complete\nNo changes allowed\nContact Inventory\n(Press Enter)\n";
					fscanf(STDIN, "%s\n", $junk);
					$order_num = "";
				}
			}
		}

		$total_rolls = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY 
				WHERE ORDER_NUM = '".$order_num."'
				AND CUSTOMER_ID = '".$cust."'
				AND SERVICE_CODE = '6'
				AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_rolls = $short_term_data_row['THE_COUNT'];

		fresh_screen("Barnett Paper\nVoid Outbound\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_rolls." rolls\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while ($Barcode != "X") {
			$continue_pallet = true;

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($short_term_data_row['THE_COUNT'] == 0) {
				fresh_screen("BOOKING Paper\nVoid Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**ROLL DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			if ($continue_pallet) {
				// check if even on this order
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['THE_COUNT'] == 0) {
					fresh_screen("BOOKING Paper\nVoid Out\nEnter X to exit.\n\nBC: ".$Barcode."\nROLL NOT ON\nTHIS ORDER\n\nPress Enter", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// display pallet info
			if ($continue_pallet) {
				$sql = "SELECT WEIGHT, WEIGHT_UNIT, DIAMETER, DIAMETER_MEAS, WIDTH, WIDTH_MEAS, BOOKING_NUM, CT.ARRIVAL_NUM
						FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
						WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CT.PALLET_ID = '".$Barcode."'
						AND CT.RECEIVER_ID = '".$cust."'
						AND CT.PALLET_ID IN
							(SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VOut6b)");
				ora_fetch_into($short_term_data_cursor, $data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$sub_choice = "default";
				while ($sub_choice != "" && $sub_choice != "X") {
					$act_num = get_max_activity_num($cust, $Barcode, $data_row['ARRIVAL_NUM']);

					fresh_screen("BOOKING Paper\nVoid Out Roll\nEnter X to exit.\nConfirmation");
					echo $Barcode."\n";
					echo "WT: ".$data_row['WEIGHT']." ".$data_row['WEIGHT_UNIT']."\n";
					echo "DIA: ".$data_row['DIAMETER']." ".$data_row['DIAMETER_MEAS']."\n";
					echo "WD: ".$data_row['WIDTH']." ".$data_row['WIDTH_MEAS']."\n";
					echo "BK#: ".$data_row['BOOKING_NUM']."\n";
					echo "Enter=OK X=Exit\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if ($sub_choice == "X") {
						$continue_pallet = false;
					}
				}
			}

			if ($continue_pallet) {
				if (!is_max_activity_num($act_num, $cust, $Barcode, $data_row['ARRIVAL_NUM'])) {
					fresh_screen("BOOKING Paper\nVoid Out Roll\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// if void was confirmed, lets do it!
			if ($continue_pallet) {
				$sql = "UPDATE CARGO_TRACKING SET 
						QTY_IN_HOUSE = 1
						WHERE PALLET_ID = '".$Barcode."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$data_row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VOut7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VOut7b)");

				$sql = "UPDATE CARGO_ACTIVITY
						SET ACTIVITY_DESCRIPTION = 'VOID'
						WHERE PALLET_ID = '".$Barcode."'
						AND CUSTOMER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$data_row['ARRIVAL_NUM']."'
						AND ORDER_NUM = '".$order_num."'
						AND SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VOut8a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VOut8b)");

/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$data_row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(VOut9a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(VOut9b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$max_act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM, 
						SERVICE_CODE, 
						QTY_CHANGE,
						ACTIVITY_ID,
						ORDER_NUM,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
						VALUES
						('".($act_num + 1)."',
						'12',
						'1',
						'".$emp_no."',
						'".$order_num."',
						'".$cust."',
						SYSDATE,
						'".$Barcode."',
						'".$data_row['ARRIVAL_NUM']."',
						'1')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(VOut10a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(VOut10b)");

				$sql = "UPDATE BOOKING_ORDERS SET STATUS = '3' WHERE ORDER_NUM = '".$order_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nfinish void\n(VOut11a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nfinish void\n(VOut11b)");


				echo "ROLL VOIDED\n";
				fscanf(STDIN, "%s\n", $junk);



//				echo $sql."\n";
//				fscanf(STDIN, "%s\n", $junk);

				$total_rolls--;
				ora_commit($rf_conn);
			}

			fresh_screen("BOOKING Paper\nVoid Out Roll\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Ord#: ".$order_num."\n";
			echo $total_rolls." rolls\n\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function VoidOUTOrder($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$cust = "";
	$order_num = "";

	$continue_function = true;

	while ($continue_function) { // in case they want to move to next one 
		// get and validate cust #
		$cust = "";
		while ($cust == "" || $cust == "Invalid") {
			fresh_screen("BOOKING Paper\nVoid Out-ORDER\nEnter X to exit.");
			if ($cust != "") {
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if ($cust == "X") {
				return;
			}
			$cust = remove_badchars($cust);

			if (!is_numeric($cust)) {
				$cust = "Invalid";
			} elseif ($cust != "314" && $cust != "338") {
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VOut1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VOut1b)");
				if (!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$cust = "Invalid";
				}
			}
		}

		// get Order
		$order_num = "";
		while ($order_num == "") {
			fresh_screen("BOOKING Paper\nVoid Out-ORDER\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if ($order_num == "X") {
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($short_term_data_row['THE_COUNT'] <= 0) {
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} else {
				$sql = "SELECT STATUS FROM BOOKING_ORDERS 
						WHERE ORDER_NUM = '".$order_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO2-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO2-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['STATUS'] == "6") {
					echo "Order Complete\nNo changes allowed\nContact Inventory\n(Press Enter)\n";
					fscanf(STDIN, "%s\n", $junk);
					$order_num = "";
				}
			}
		}

		// get pallets and cases on order, including partials
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PALLETS FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallets = $short_term_data_row['THE_PALLETS'];

		fresh_screen("BOOKING Paper\nVoid Out-ORDER\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_pallets." rolls\n\n";
		echo "VOID ENTIRE ORDER?\n";
		echo "Y - Yes\n";
		echo "N - No\n";
		echo "X - Exit function\n";
		fscanf(STDIN, "%s\n", $choice);
		$choice = strtoupper($choice);

		switch($choice) {
			case "N":
				// do nothing; will start VOIDOUTORDER over
			break;

			case "X":
				$continue_function = false;
			break;

			case "Y":
				$sql = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO4a)");
				$ora_success = ora_exec($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO4b)");
				while (ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(VoutO5a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(VoutO5b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;

					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 1 WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO6b)");

					$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND ACTIVITY_NUM = '".$select_row['ACTIVITY_NUM']."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO7a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO7b)");

					// add CA record of void
					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".$act_num."',
					'12',
					'".$select_row['QTY_CHANGE']."',
					'".$emp_no."',
					'".$order_num."',
					'".$cust."',
					SYSDATE,
					'".$select_row['PALLET_ID']."',
					'".$select_row['ARRIVAL_NUM']."',
					'1')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO8b)");

					$sql = "UPDATE BOOKING_ORDERS SET STATUS = '2' WHERE ORDER_NUM = '".$order_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to\nfinish void\n(VOutO09a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to\nfinish void\n(VOutO09b)");

				}
				ora_commit($rf_conn);
				fresh_screen("BOOKING Paper\nVoid Out-ORDER\nEnter X to exit.");
				echo $order_num."\nVOIDED\n\n";
				fscanf(STDIN, "%s\n", $junk);

			break;
		}
	}
}

function HoldRoll($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while ($continue_function) { // in case they finish order and want to move to next one 
		$cust = "";
		// get and validate cust #
		while ($cust == "" || $cust == "Invalid") {
			fresh_screen("BOOKING Paper\nAdjust HOLD Status\nEnter X to exit.");
			if ($cust != "") {
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if ($cust == "X") {
				return;
			}
			$cust = remove_badchars($cust);

			if (!is_numeric($cust)) {
				$cust = "Invalid";
			} elseif ($cust != "314" && $cust != "338") {
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(HR1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(HR1b)");
				if (!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$cust = "Invalid";
				}
			}
		}

		fresh_screen("Barnett Paper\nAdjust HOLD Status\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while ($Barcode != "X") {
			$continue_pallet = true;

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0 AND PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR2a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR2b)");
			ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($select_row['THE_COUNT'] <= 0) {
				$continue_pallet = false;
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$cust."' AND PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_data_row['THE_COUNT'] <= 0) {
					// not in DB
					fresh_screen("BOOKING SCANNER\nAdjust HOLD Status\nEnter X to exit.\n\nCust #: ".$cust."\nBC: ".$Barcode."\n**INVALID ROLL**\nCONTACT INVENTORY", "bad");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					// wrong arrival #
					fresh_screen("BOOKING SCANNER\nAdjust HOLD Status\nEnter X to exit.\n\nCust #: ".$cust."\nBC: ".$Barcode."\n**ROLL NOT\nIN HOUSE\nCONTACT INVENTORY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
				}
			} elseif ($select_row['THE_COUNT'] >= 2) {
				$temp = 0;
				$LR_ARRAY = array();

				fresh_screen("BOOKING SCANNER\nAdjust HOLD Status\nEnter X to exit.\n\nCust #: ".$cust."\nBC: ".$Barcode."\nDUPLICATE ROLLS\nPlease Select\nArrival #:", "bad");
				$sql = "SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0 AND PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR4b)");
				while (ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$temp++;
					$LR_ARRAY[$temp] = $short_term_data_row['ARRIVAL_NUM'];
					echo $temp.": ".$LR_ARRAY[$temp]."\n";
				}
				echo "X to Cancel\n";

				$sub_choice = "";
				fscanf(STDIN, "%s\n", $sub_choice);
				$sub_choice = strtoupper($sub_choice);

				if ($sub_choice == "X" || $LR_ARRAY[$sub_choice] == "") {
					$continue_pallet = false;
				} else {
					$Arrival = $LR_ARRAY[$sub_choice];
				}
			} else {
				$sql = "SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0 AND PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			}

			if ($continue_pallet) {
				$sql = "SELECT * FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD 
						WHERE CT.RECEIVER_ID = '".$cust."' AND CT.QTY_IN_HOUSE > 0 
						AND CT.PALLET_ID = '".$Barcode."' AND CT.ARRIVAL_NUM = '".$Arrival."'
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRoll Info\n(HR6b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$cur_status = $short_term_data_row['CARGO_STATUS'];

				$booking_num = $short_term_data_row['BOOKING_NUM'];
				if ($cur_status == "HOLD") {
					$display_line = "HOLD";
				} else {
					$display_line = "NOT ON HOLD";
				}

				$choice = "";
				while ($choice == "") {

					fresh_screen("BOOKING SCANNER\nAdjust HOLD Status\nEnter X to exit.\n\nCust #: ".$cust."\nBC: ".$Barcode."\nARV#: ".$Arrival."\nBK#: ".$booking_num);
					echo "Status: ".$display_line."\n";
					echo "H - Hold\n";
					echo "C - Clear Hold\n";
					echo "X - No Change\n";
					fscanf(STDIN, "%s\n", $choice);
					$choice = strtoupper($choice);

					switch($choice) {
						case "X":
							// do nothing; will go back to select-a-pallet
						break;

						case "H":
							$sql = "UPDATE CARGO_TRACKING SET CARGO_STATUS = 'HOLD' WHERE RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0 AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Update\nRoll Info\n(HR7a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Update\nRoll Info\n(HR7b)");
							ora_commit($rf_conn);
							fresh_screen("BOOKING SCANNER\nAdjust HOLD\nEnter X to exit.\n\nCust #: ".$cust."\nBC: ".$Barcode."\nARV#: ".$Arrival);
							echo "ROLL NOW ON HOLD\n";
							fscanf(STDIN, "%s\n", $junk);
						break;

						case "C":
							$sql = "UPDATE CARGO_TRACKING SET CARGO_STATUS = NULL WHERE RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0 AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Update\nRoll Info\n(HR7a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Update\nRoll Info\n(HR7b)");
							ora_commit($rf_conn);
							fresh_screen("BOOKING SCANNER\nAdjust HOLD\nEnter X to exit.\n\nCust #: ".$cust."\nBC: ".$Barcode."\nARV#: ".$Arrival);
							echo "HOLD STATUS REMOVED\n";
							fscanf(STDIN, "%s\n", $junk);
						break;

						default:
							$choice = "";
						break;
					}
				}
			}

			fresh_screen("Barnett Paper\nAdjust HOLD Status\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function ChangeCont($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("BOOKING SCANNER\nChange Cont.\nEnter X to exit.");
	echo "Password:";
	fscanf(STDIN, "%s\n", $pass);
	$pass = strtoupper($pass);
	if ($pass != "ROLL" && $pass != "X") {
		echo "\nIncorrect\nPassword.";
		fscanf(STDIN, "%s\n", $junk);
		return;
	} elseif ($pass == "X") {
		return;
	}

	while (true) {
		$Order = "";
		while ($Order == "") {
			fresh_screen("BOOKING SCANNER\nChange Cont.\nEnter X to exit.");
			echo "ORDER:";
			fscanf(STDIN, "%s\n", $Order);
			$Order = strtoupper($Order);
			if ($Order == "X") {
				return;
			} else {
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM BOOKING_ORDERS
						WHERE ORDER_NUM = '".$Order."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nFind Order\n(CC1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nFind Order\n(CC1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if ($short_term_row['THE_COUNT'] <= 0) {
					echo "\nOrder ".$Order."\nNot Found.";
					fscanf(STDIN, "%s\n", $junk);
					$Order = "";
				}
			}
		}

		$sql = "SELECT NVL(CONTAINER_ID, 'NONE') THE_CONT
				FROM BOOKING_ORDERS
				WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nFind Order\n(CC2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nFind Order\n(CC2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_cont = $short_term_row['THE_CONT'];

		$newcont = "";
		$complete = false;
		while ($newcont == "" && !$complete) {
			fresh_screen("BOOKING SCANNER\nChange Cont.\nEnter X to exit.");
			echo "ORDER: ".$Order."\n";
			echo "Current Container:\n  ".$current_cont."\n";
			echo "New Container:\n";
			fscanf(STDIN, "%s\n", $newcont);
			$newcont = strtoupper($newcont);
			if ($newcont == "X") {
				return;
			} elseif ($newcont == "") {
				// do nothing
			} else {
				$option = "";
				while ($option == "") {
					fresh_screen("BOOKING SCANNER\nChange Cont.\nEnter X to exit.");
					echo "ORDER: ".$Order."\n";
					echo "Current Container:\n  ".$current_cont."\n";
					echo "New Container:\n  ".$newcont."\n";
					echo "Y-Commit N-Redo\nC-Cancel\n";
					fscanf(STDIN, "%s\n", $option);
					$option = strtoupper($option);
					if ($option == "X") {
						return;
					} elseif ($option == "N") {
						$newcont = "";
					} elseif ($option == "C") {
						$complete = true;
					} elseif ($option == "Y") {
						$sql = "UPDATE BOOKING_ORDERS
								SET CONTAINER_ID = '".$newcont."'
								WHERE ORDER_NUM = '".$Order."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CC3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CC3b)");
						ora_commit($rf_conn);
						fresh_screen("BOOKING SCANNER\nChange Cont.\nEnter X to exit.");
						echo "ORDER: ".$Order."\nChanged CONT to\n".$newcont."\n";
						fscanf(STDIN, "%s\n", $junk);
						$complete = true;
					} else {
						$option = "";
					}
				}
			}
		}
	}
}

function ChangeSeal($CID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("BOOKING SCANNER\nChange Seal\nEnter X to exit.");
	echo "Password:";
	fscanf(STDIN, "%s\n", $pass);
	$pass = strtoupper($pass);
	if($pass != "ROLL" && $pass != "X"){
		echo "\nIncorrect\nPassword.";
		fscanf(STDIN, "%s\n", $junk);
		return;
	} elseif($pass == "X"){
		return;
	}

	while(true){
		$Order = "";
		while($Order == ""){
			fresh_screen("BOOKING SCANNER\nChange Seal\nEnter X to exit.");
			echo "ORDER:";
			fscanf(STDIN, "%s\n", $Order);
			$Order = strtoupper($Order);
			if($Order == "X"){
				return;
			} else {
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM BOOKING_ORDERS
						WHERE ORDER_NUM = '".$Order."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nFind Order\n(CS1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nFind Order\n(CS1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] <= 0){
					echo "\nOrder ".$Order."\nNot Found.";
					fscanf(STDIN, "%s\n", $junk);
					$Order = "";
				}
			}
		}

		$sql = "SELECT NVL(SEAL_NUM, 'NONE') THE_SEAL
				FROM BOOKING_ORDERS
				WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nFind Order\n(CS2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nFind Order\n(CS2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_seal = $short_term_row['THE_SEAL'];

		$newseal = "";
		$complete = false;
		while($newseal == "" && !$complete){
			fresh_screen("BOOKING SCANNER\nChange Seal\nEnter X to exit.");
			echo "ORDER: ".$Order."\n";
			echo "Current Seal:\n  ".$current_seal."\n";
			echo "New Seal:\n";
			fscanf(STDIN, "%s\n", $newseal);
			$newseal = strtoupper($newseal);
			if($newseal == "X"){
				return;
			} elseif($newseal == ""){
				// do nothing
			} else {
				$option = "";
				while($option == ""){
					fresh_screen("BOOKING SCANNER\nChange Seal\nEnter X to exit.");
					echo "ORDER: ".$Order."\n";
					echo "Current Seal:\n  ".$current_seal."\n";
					echo "New Seal:\n  ".$newseal."\n";
					echo "Y-Commit N-Redo\nC-Cancel\n";
					fscanf(STDIN, "%s\n", $option);
					$option = strtoupper($option);
					if($option == "X"){
						return;
					} elseif($option == "N"){
						$newseal = "";
					} elseif($option == "C"){
						$complete = true;
					} elseif($option == "Y"){
						$sql = "UPDATE BOOKING_ORDERS
								SET SEAL_NUM = '".$newseal."'
								WHERE ORDER_NUM = '".$Order."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CS3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CS3b)");
						ora_commit($rf_conn);
						fresh_screen("BOOKING SCANNER\nChange Seal\nEnter X to exit.");
						echo "ORDER: ".$Order."\nChanged SEAL to\n".$newseal."\n";
						fscanf(STDIN, "%s\n", $junk);
						$complete = true;
					} else {
						$option = "";
					}
				}
			}
		}
	}
}

function EnterRailcar($CID, $SID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("BOOKING SCANNER\nEnter Railcars\nEnter X to exit.");
	echo "ENTER RAILCAR\n";
	fscanf(STDIN, "%s\n", $railcar);
	$railcar = strtoupper($railcar);
	if ($railcar != "X") {
		do {
/*			if ($container == "X") { // user cancelled
				$sql = "DELETE FROM BOOKINGCONTLIST";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nCancel\n(EC1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nCancel\n(EC1b)");
				return;
			} else { */
				$sql = "INSERT INTO BOOKINGRAILCARLIST
							(RAILCAR,
							CHECKER,
							SUPER,
							SCANTIME)
						VALUES
							('".$railcar."',
							'".$CID."',
							'".$SID."',
							SYSDATE)";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nAdd Railcar\n(EC2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nAdd Railcar\n(EC2b)");
				$continue = "";
				while ($continue == "") {
					fresh_screen("BOOKING SCANNER\nEnter Railcars");
					echo "RAILCAR ".$railcar."\n";
					echo "Entered.\n\n";
					echo "Entered another\n";
					echo "Railcar? (Y/N)\n";
					fscanf(STDIN, "%s\n", $continue);
					$continue = strtoupper($continue);
					if ($continue == "Y") {  // will return to top loop
						fresh_screen("BOOKING SCANNER\nEnter Railcars.");
						echo "ENTER RAILCAR\n";
						fscanf(STDIN, "%s\n", $railcar);
						$railcar = strtoupper($railcar);
/*					} elseif ($continue == "X") {  // user cancelled
						$sql = "DELETE FROM BOOKINGCONTLIST";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nCancel\n(EC3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nCancel\n(EC3b)");
						return; */
					} elseif ($continue == "N") { // user complete successfully
						$sql = "INSERT INTO JOB_QUEUE
									(JOB_ID,
									SUBMITTER_ID,
									SUBMISSION_DATETIME,
									JOB_TYPE,
									JOB_DESCRIPTION,
									COMPLETION_STATUS,
									VARIABLE_LIST)
								VALUES
									(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
									'".$CID."',
									SYSDATE,
									'EMAIL',
									'PAPERRAILCARS',
									'PENDING',
									'BOOKINGRAILCARLIST')";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nSend Email\n(EC4a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nSend Email\n(EC4b)");
						ora_commit($rf_conn);
						return;
					} else { // messed up the selection
						$continue = "";
					}
				}
//			}
			ora_commit($rf_conn);
		} while (true);
	}
}




/*******************************************************************************
* AUXILIARY FUNCTIONS START HERE ***********************************************
********************************************************************************/

function Damage_Report($Barcode, $Customer, $LR) {
	global $rf_conn;

	$cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_DAMAGES 
			WHERE PALLET_ID = '".$Barcode."' 
			AND RECEIVER_ID = '".$Customer."' 
			AND ARRIVAL_NUM = '".$LR."' 
			ORDER BY DAMAGE_ID";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDMG Info\n(DR3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDMG Info\n(DR3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$total_damage_count = $short_term_data_row['THE_COUNT'];
	$damage_data_row = 0;

	$sql = "SELECT TO_CHAR(DATE_ENTERED, 'MM/DD/YY HH24:MI') DATE_ENT, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YY HH24:MI'), 'NONE') DATE_CLEAR, DAMAGE_ID, OCCURRED, CHECKER_ENTERED, CHECKER_CLEARED, DAMAGE_TYPE, EXTRA_DESC 
			FROM BOOKING_DAMAGES 
			WHERE PALLET_ID = '".$Barcode."' 
			AND RECEIVER_ID = '".$Customer."' 
			AND ARRIVAL_NUM = '".$LR."' 
			ORDER BY DAMAGE_ID";
	$ora_success = ora_parse($cursor, $sql);
	database_check($ora_success, "Unable to get act\n(DR1a)");
	$ora_success = ora_exec($cursor, $sql);
	database_check($ora_success, "Unable to get act\n(DR1b)");
	while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC) && strtoupper($continue) != "X") {
		$damage_data_row++;

		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$row['CHECKER_ENTERED']."'";
//		$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$row['CHECKER_ENTERED']."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nEMP Info\n(DR2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nEMP Info\n(DR2b)");
		@ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$entered_emp = $short_term_data_row['LOGIN_ID'];

		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$row['CHECKER_CLEARED']."'";
//		$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$row['CHECKER_CLEARED']."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nEMP Info\n(DR2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nEMP Info\n(DR2b)");
		@ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$cleared_emp = $short_term_data_row['LOGIN_ID'];

		fresh_screen("BC: ".$Barcode."\nDAMAGE ID: ".$row['DAMAGE_ID']);

		echo "DAMAGED RECORDED:\n ".$row['DATE_ENT']."\n ".$row['OCCURRED']."\n By: ".$entered_emp."\n";
		if ($row['DATE_CLEAR'] != "NONE") {
			echo "DAMAGED CLEARED:\n ".$row['DATE_CLEAR']."\n";
			echo "BY: ".$cleared_emp."\n";
		}
		echo "TYPE: ".$row['DAMAGE_TYPE']."\n";
		if ($row['EXTRA_DESC'] != "") {
			echo " (".$row['EXTRA_DESC'].")\n";
		}

		if ($damage_data_row < $total_damage_count) {
			echo $damage_data_row." of ".$total_damage_count." DMG records.\n";
			echo "\n(Enter for next,\n X to exit)\n";
		} else {
			echo "\nLast DMG Record.";
		}
		fscanf(STDIN, "%s\n", $continue);
	}
}

function Advanced_Add_Damage($CID, $Customer, $booking_num, $Arrival, $Barcode, $When="no longer used") {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit.  This is an AUXILLARY (sp?) function, we will NOT be committing in it.
	$short_term_data_cursor = ora_open($rf_conn);	

	// get employee number for use in ACTIVITY_ID later
/*	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
	$emp_no = $short_term_data_row['THE_EMP'];*/
	$emp_no = get_emp_no($CID);

	$reject_level = 1;
	$sql = "SELECT DISTINCT REJECT_LEVEL FROM PAPER_DAMAGE_CODES 
			WHERE PAPER_SYSTEM IN ('ALL', 'BOOKING')
			ORDER BY REJECT_LEVEL";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDamage Info(AAD1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDamage Info(AAD1b)");
	while (ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		$reject_array[$reject_level] = $short_term_data_row['REJECT_LEVEL'];
		$reject_level++;
	}
	$reject_array[$reject_level] = "Set OK To Ship";

	$reject_choice = "strawberry";
	while ($reject_choice != "X") {
		do {
			fresh_screen("BOOKING SCANNER\nDamage Menu\nEnter X to exit.");
			for($temp = 1; $temp <= sizeof($reject_array); $temp++) {
				echo $temp.":".$reject_array[$temp]."\n";
			}
			echo "X: Enter X to Exit\n";
			fscanf(STDIN, "%s\n", $reject_choice);
			$reject_choice = strtoupper($reject_choice);
		} while ($reject_choice != "X" && ($reject_choice < 1 || $reject_choice >= $temp));

		if ($reject_array[$reject_choice] == "Set OK To Ship") {
			// logic for "releasing" damage
			fresh_screen("BOOKING SCANNER\nCLEAR DMG Menu");
			echo "BK#: ".$booking_num."\nBC: ".$Barcode."\n";
			$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
					WHERE PAPER_SYSTEM IN ('ALL', 'BOOKING')
					AND PDC.DAMAGE_CODE = BD.DAMAGE_TYPE
					AND BD.PALLET_ID = '".$Barcode."' 
					AND BD.ARRIVAL_NUM = '".$Arrival."'
					AND BD.RECEIVER_ID = '".$Customer."'
					AND BD.DATE_CLEARED IS NULL
					AND PDC.REJECT_LEVEL = 'REJECT'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Clear\nDamage Info(AAD5a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Clear\nDamage Info(AAD5b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if ($short_term_data_row['THE_COUNT'] == 0) {
				echo "This roll is\n already cleared\nto ship\n";
				fscanf(STDIN, "%s\n", $junk);
			} else {
				echo "Is this roll OK\nTo ship?\n(Y/N)";
				fscanf(STDIN, "%s\n", $verify);
				if (strtoupper($verify) == "Y") {
					$sql = "UPDATE BOOKING_DAMAGES 
							SET DATE_CLEARED = SYSDATE,
							CHECKER_CLEARED = '".$emp_no."'
							WHERE PALLET_ID = '".$Barcode."' 
							AND ARRIVAL_NUM = '".$Arrival."'
							AND RECEIVER_ID = '".$Customer."'
							AND DATE_CLEARED IS NULL
							AND DAMAGE_TYPE IN
							(SELECT DAMAGE_TYPE FROM PAPER_DAMAGE_CODES WHERE REJECT_LEVEL = 'REJECT')";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Clear\nDamage Info(AAD6a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Clear\nDamage Info(AAD6b)");
					
					echo "Roll Cleared\n";
					fscanf(STDIN, "%s\n", $junk);
				} else {
					echo "Roll NOT Cleared\n";
					fscanf(STDIN, "%s\n", $junk);
				}
			}

		} elseif ($reject_choice != "X") { // if they "broke", we dont do anything else
			// logic for adding damage notation
			do {
				fresh_screen("BOOKING SCANNER\n".$reject_array[$reject_choice]." Menu");
				echo "Responsibility?\n";
				echo "1-Port\n";
				echo "2-Carrier\n"; 
				fscanf(STDIN, "%s\n", $resp_choice);
				$resp_choice = strtoupper($resp_choice);
			} while ($resp_choice < 1 || $resp_choice > 2);
			switch ($resp_choice) { // get damage responsible party
				case 1:
					$resp_choice = "Port";
				break;
				case 2:
					$resp_choice = "Carrier";
				break;
			}

			$type_level = 1;
			$sql = "SELECT DAMAGE_CODE, SCANNER_DESC, EXTRA_DETAIL 
					FROM PAPER_DAMAGE_CODES 
					WHERE PAPER_SYSTEM IN ('ALL', 'BOOKING')
					AND REJECT_LEVEL = '".$reject_array[$reject_choice]."'
					ORDER BY DAMAGE_CODE";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nDamage Info(AAD2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nDamage Info(AAD2b)");
			while (ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				$type_array[$type_level] = $short_term_data_row['DAMAGE_CODE'];
				$type_display[$type_level] = $short_term_data_row['SCANNER_DESC'];
				$type_detail[$type_level] = $short_term_data_row['EXTRA_DETAIL'];
				$type_level++;
			}

			$type_choice = "Doritos";
			while ($type_choice != "X") {
				do {
					fresh_screen("BOOKING SCANNER\n".$reject_array[$reject_choice]." Menu\nEnter X to exit.");
					for($temp = 1; $temp <= sizeof($type_array); $temp++) {
						echo $temp.":".$type_array[$temp]."-".$type_display[$temp]."\n";
					}
					fscanf(STDIN, "%s\n", $type_choice);
					$type_choice = strtoupper($type_choice);
				} while ($type_choice != "X" && ($type_choice < 1 || $type_choice >= $temp));

				if ($type_choice != "X") { // if they broke, we want out, if not, get damage info
					$continue_pallet = TRUE;

					if ($type_detail[$type_choice] == "YES") {
						do {
							fresh_screen("BOOKING SCANNER\n".$reject_array[$reject_choice]." Menu\nSize Select\n(X to Cancel)");
							echo "1: 1/8IN\n";
							echo "2: 1/4IN\n"; 
							echo "3: 1/2IN\n"; 
							echo "4: 3/4IN\n"; 
							echo "5: 1IN\n"; 
							echo "6: >1IN\n"; 
							fscanf(STDIN, "%s\n", $quantity);
							$quantity = strtoupper($quantity); 
						} while ($quantity != "X" && ($quantity < 1 || $quantity > 6));
						switch ($quantity) {
							case 1:
								$extra_desc = "1/8 IN";
								break;
							case 2:
								$extra_desc = "1/4 IN";
								break;
							case 3:
								$extra_desc = "1/2 IN";
								break;
							case 4:
								$extra_desc = "3/4 IN";
								break;
							case 5:
								$extra_desc = "1 IN";
								break;
							case 6:
								$extra_desc = ">1 IN";
							break;
							case "X":
								$continue_pallet = FALSE;
							break;
							default:
								$extra_desc = "";
							break;
						}
					} else {
						$extra_desc = "";
					}

					if ($continue_pallet) {
						$sql = "SELECT NVL(MAX(DAMAGE_ID), 0) THE_DMG FROM BOOKING_DAMAGES WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Customer."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nDamage Info(AAD3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nDamage Info(AAD3b)");
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$damage_id = $short_term_data_row['THE_DMG'] + 1;

						$sql = "INSERT INTO BOOKING_DAMAGES
									(PALLET_ID,
									RECEIVER_ID,
									ARRIVAL_NUM,
									DAMAGE_ID,
									DATE_ENTERED,
									CHECKER_ENTERED,
									DAMAGE_TYPE,
									OCCURRED,
									EXTRA_DESC)
								VALUES
									('".$Barcode."',
									'".$Customer."',
									'".$Arrival."',
									'".$damage_id."',
									SYSDATE,
									'".$emp_no."',
									'".$type_array[$type_choice]."',
									'".$resp_choice."',
									'".$extra_desc."')";
//						echo $sql."\n";
//						fscanf(STDIN, "%s\n", $junk);
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD4a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD4b)");

						$sql = "UPDATE CARGO_TRACKING SET QTY_DAMAGED = '1'
								WHERE PALLET_ID = '".$Barcode."'
								AND RECEIVER_ID = '".$Customer."'
								AND ARRIVAL_NUM = '".$Arrival."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD5a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD5b)");

						$sql = "SELECT CT.ARRIVAL_NUM, WEIGHT, WEIGHT_UNIT, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE,
									DECODE(CARGO_STATUS, 'HOLD', 'ON HOLD', 'OK TO SHIP') HOLD_STATUS,
									QTY_IN_HOUSE, BAD.BOL, CT.RECEIVER_ID, SSCC_GRADE_CODE, BOOKING_NUM, WIDTH, WIDTH_MEAS, DIAMETER, DIAMETER_MEAS
								FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC
									WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
									AND CT.PALLET_ID = BAD.PALLET_ID
									AND CT.RECEIVER_ID = BAD.RECEIVER_ID
									AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
									AND CT.PALLET_ID = '".$Barcode."'
									AND CT.ARRIVAL_NUM = '".$Arrival."'
									AND CT.RECEIVER_ID = '".$Customer."'";
				//		echo $sql;
				//		fscanf(STDIN, "%s\n", $junk);
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(AAD6a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(AAD6b)");
						ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

						$received = $row['THE_DATE'];
						$arrival_num = $row['ARRIVAL_NUM'];
						$booking = $row['BOOKING_NUM'];
						$hold_status = "--".$row['THE_STATUS'];
						$weight = $row['WEIGHT']." ".$row['WEIGHT_UNIT'];
						$disp_width = $row['WIDTH']." ".$row['WIDTH_MEAS'];
						$disp_dia = $row['DIAMETER']." ".$row['DIAMETER_MEAS'];
						$code = $row['SSCC_GRADE_CODE'];
						$BOL = $row['BOL'];
						$Customer = $row['RECEIVER_ID'];

						fresh_screen("BOOKING SCANNER\nRoll Info.\nEnter X to exit.\nBC: ".$Barcode."\nARV #: ".$arrival_num."\n".$hold_status."\nBK#: ".$booking."\nWIDTH: ".$disp_width."\nGRADE CD: ".$code."\nDIA: ".$disp_dia."\nWT: ".$weight."\nRcvd: ".$received."\nAdded DMG:\n  ".$type_display[$type_choice]);
						fscanf(STDIN, "%s\n", $junk);

						$type_choice = "X"; // get it back to the "top" of the damage menu after successful write
					}
				}
			}
		}
	}
}



// ---------------MAIN Menu-----------------
// starts here

$SID = Login_Super($argv[1]);
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}


do {
	fresh_screen("Barnett Paper");
	echo "1: Super Functions\n";
	echo "2: Checker\n";
	echo "3: Exit Barnett\n";
	fscanf(STDIN, "%s\n", $BookSuperCHOICE);
} while ($BookSuperCHOICE != 1 && $BookSuperCHOICE != 2 && $BookSuperCHOICE != 3);

if ($BookSuperCHOICE == 1) {
	do {
		fresh_screen("Barnett (Super)");
		echo "1: Exit\n"; 
		fscanf(STDIN, "%s\n", $SuperCHOICE);

		switch ($SuperCHOICE) {
			case 1:
				echo "under construction\n";
				// will exit
			break;
		}
	}while ($SuperCHOICE != 1);

} elseif ($BookSuperCHOICE == 2) {
	
	do {
		$subCHOICE = "";

		if ($CID == "") {
			$CID = Login_Checker();
		}
		fresh_screen(" Barnett\n(Checker)");
		echo " 1: Super(", $SID, ")\n";
		echo " 2: Chkr(", $CID, ")\n";
		echo " 3: LOAD OUT\n";
		echo " 4: Void\n";
		echo " 5: Receive\n";
		echo " 6: Damage\n"; 
		echo " 7: Roll Info\n"; 
		echo " 8: Hold Roll\n"; 
		echo " 9: Edit Cont/Seal\n"; 
		echo " 10: Enter Railcars\n"; 
		echo " 11: Exit Barnett\n"; 
		echo " ENTER (1-11):\n";
		fscanf(STDIN, "%s\n", $CHKCHOICE);


		switch ($CHKCHOICE) {
			case 1:
				$SID = Login_Super();
				$CID = Login_Checker();
			break;
			
			case 2:
				$CID = Login_Checker();
			break;

			case 3:
				Load_Truck($CID);
			break;

			case 4:
				fresh_screen("VOID OUT\n1) Order\n2) Roll");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if ($subCHOICE == "1") {
					VoidOUTOrder($CID);
				} elseif ($subCHOICE == "2") {
					VoidOUTPallet($CID);
				}
			break;

			case 5:
				Unload_Cargo($CID);
			break;

			case 6:
				Damage($CID);
			break;

			case 7:
				RollInfo();
			break;

			case 8:
				HoldRoll();
			break;

			case 9:
				fresh_screen("CHANGE\n1) Container\n2) Seal");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					ChangeCont($CID);
				} elseif($subCHOICE == "2"){
					ChangeSeal($CID);
				}
			break;

			case 10:
				EnterRailcar($CID, $SID);
			break;
			
			case 11:
				// will exit
			break;
		}
	} while ($CHKCHOICE != 11);
}