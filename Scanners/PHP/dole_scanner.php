<?
/*
*
*	Scanner for Dole
*
***********************************************/

function Unload_Cargo($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$Arrival = "";
	$wing = "";

	fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.");
/*	echo "CUST:";
	fscanf(STDIN, "%s\n", $Customer);
	$Customer = strtoupper($Customer);
	if($Customer == "X"){
		return;
	} */
	while($Arrival == ""){
		echo "ARV#: ";
//		fscanf(STDIN, "%s\n", $Arrival);
		fscanf(STDIN, "%[^[]]\n", $Arrival);
		$Arrival = trim(strtoupper($Arrival));
		if($Arrival == "X"){
			return;
		}
//		echo "ARRIVAL #:".$Arrival."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$Arrival = remove_badchars($Arrival);
//		echo "ARRIVAL #:".$Arrival."\n";
//		fscanf(STDIN, "%s\n", $junk);

	}
	while($wing == ""){
		fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.");
		echo "ARV#: ".$Arrival."\n\n";
		echo "Wing:\n";
		fscanf(STDIN, "%s\n", $wing);
		$wing = strtoupper($wing);
		if($wing == "X"){
			return;
		}
		$wing = remove_badchars($wing);
	}

	// get employee number for use in ACTIVITY_ID later
/*	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
	$emp_no = $short_term_data_row['THE_EMP'];*/
	$emp_no = get_emp_no($CID);

/*	$sql = "SELECT BOL FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' AND BOL IS NOT NULL ORDER BY BOL DESC";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(UC7a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(UC7b)");
	if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$dockticket = 10000;
	} else {
		$dockticket = $short_term_row['BOL'];
	}
	$batch_id = "NONE"; */


	fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nWing: ".$wing);

	echo "BC:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){

		$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$Arrival."' AND PALLET_ID = '".$Barcode."' AND DATE_RECEIVED IS NULL AND REMARK = 'DOLEPAPERSYSTEM'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(UC2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(UC2b)");
		ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($row['THE_COUNT'] == "0"){
			// incorrect vessel / already received / not in DB
			$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$Arrival."' AND PALLET_ID = '".$Barcode."'"; 
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC7b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// incorrect vessel / not in DB
				$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(UC8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(UC8b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// not in DB
					fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\n**INVALID PALLET**\nCONTACT INVENTORY", "bad");
				} else {
					// wrong arrival #
					fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\n**PALLET DOES NOT\nMATCH ARV#**", "bad");
				}
			} else {
				// already received
				fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\n**ALREADY RECEIVED**\nAT: ".$short_term_row['THE_DATE'], "bad");
			}
		} elseif($row['THE_COUNT'] > 1) {
			// duplicate Barcode / arrival#
			fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\n**DUPLICATE BARCODE/\nARV# COMBO**\nCONTACT INVENTORY", "bad");
		} else {
			$sql = "SELECT BATCH_ID, BOL, WEIGHT || WEIGHT_UNIT THE_WEIGHT, RECEIVER_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$Arrival."' AND PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC9a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(UC9b)");
			ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			// Receive Pallet
/*
			if($row['BATCH_ID'] != $batch_id){
				$batch_id = $row['BATCH_ID'];
				$dockticket++;
			}	
*/
			$dockticket = $row['BOL'];
			$disp_weight = $row['THE_WEIGHT'];
			$code = $row['BATCH_ID'];
			$Customer = $row['RECEIVER_ID'];

			$sql = "UPDATE CARGO_TRACKING SET DATE_RECEIVED = SYSDATE, QTY_DAMAGED = '0', WAREHOUSE_LOCATION = '".$wing."' WHERE RECEIVER_ID = '".$Customer."' AND ARRIVAL_NUM = '".$Arrival."' AND PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nUpdate Pallet\nReceive Date\n(UC3a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nUpdate Pallet\nReceive Date\n(UC3b)");

			$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES ('1', '8', '1', '".$emp_no."', '".$Arrival."', '".$Customer."', SYSDATE, '".$Barcode."', '".$Arrival."', '1', '".$dockticket."')";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to make\nactivity record\n(UC4a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to make\nactivity record\n(UC4b)");

			ora_commit($rf_conn);

			fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\nCode: ".$code."\nWT: ".$disp_weight."\nDT#: ".$dockticket);
			echo "DMG?  (Y/N)";
			fscanf(STDIN, "%s\n", $DMG);
			if(strtoupper($DMG) == "Y"){
				Advanced_Add_Damage($CID, $Customer, $dockticket, $Barcode, "Pre-Arrival");
			} 

			ora_commit($rf_conn);
			fresh_screen("DOLE SCANNER\nUnload Cargo\nEnter X to exit.\n\nARV #: ".$Arrival."\nBC: ".$Barcode."\nCode: ".$code."\nWT: ".$disp_weight."\nDT#: ".$dockticket."\nRECEIVED!");
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$Customer."' AND ARRIVAL_NUM = '".$Arrival."' AND BOL = '".$dockticket."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get summary\n(UC5a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get summary\n(UC5b)");
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total = $row['THE_COUNT'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$Customer."' AND ARRIVAL_NUM = '".$Arrival."' AND BOL = '".$dockticket."' AND DATE_RECEIVED IS NOT NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get summary\n(UC6a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get summary\n(UC6b)");
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$scanned = $row['THE_COUNT'];


		echo "SCANNED: ".$scanned." of ".$total;
		echo "\nNEXT BC:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}



function VoidIN($CID) {
	global $rf_conn;
	global $inv_and_fina_mail_info_list;
	global $mailHeaders;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$Arrival = "";
	$dock_ticket = "";
	$was_billed = "NO";

	fresh_screen("DOLE SCANNER\nVoid Inbound Roll.\nEnter X to exit.");
	while($Arrival == ""){
		echo "ARV#:";
		fscanf(STDIN, "%s\n", $Arrival);
		$Arrival = strtoupper($Arrival);
		if($Arrival == "X"){
			return;
		} 

		$Arrival = remove_badchars($Arrival);

	}
	while($dock_ticket == "" || !is_numeric($dock_ticket)){
		echo "DOCK TKT:";
		fscanf(STDIN, "%s\n", $dock_ticket);
		$dock_ticket = strtoupper($dock_ticket);
		if($dock_ticket == "X"){
			return;
		} 

		$dock_ticket = remove_badchars($dock_ticket);

	}

	// get employee number for use in ACTIVITY_ID later
/*	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
	$emp_no = $short_term_data_row['THE_EMP'];*/
	$emp_no = get_emp_no($CID);

	fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival);

	echo "BARCODE:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, WEIGHT || WEIGHT_UNIT THE_WEIGHT, BATCH_ID, RECEIVER_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$Arrival."' AND PALLET_ID = '".$Barcode."' AND BOL = '".$dock_ticket."'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VI2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VI2b)");

		if(!ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// Pallet incorrect / not in DB
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VI6a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VI6b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nDT#: ".$dock_ticket."\nBC: ".$Barcode."\n**INVALID PALLET**\nCONTACT INVENTORY", "bad");
			} else {
				fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nDT#: ".$dock_ticket."\nBC: ".$Barcode."\n**PALLET DOES NOT\nMATCH DOCK TICKET**", "bad");
			}
		} elseif($row['THE_DATE'] == "NONE") {
			// Pallet not received, no need to void
			fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nDT#: ".$dock_ticket."\nBC: ".$Barcode."\n**NO VOID NEEDED\nNOT YET RECEIVED**", "bad");
		} elseif($row['QTY_IN_HOUSE'] == "0") {
			// Pallet already shipped, cannot void inbound
			fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nDT#: ".$dock_ticket."\nBC: ".$Barcode."\n**CANNOT VOID IN\nALREADY SHIPPED**", "bad");
		} else {
			// Verify
			$Customer = $row['RECEIVER_ID'];
			$code = $row['BATCH_ID'];
			$weight = $row['THE_WEIGHT'];

			// check if the pallet's inbound truckloading charge was already made, and if so, notify scanner of what will happen
			$sql = "SELECT PALLET_ID, NVL(TO_MISCBILL, 'OK') IS_BILLED FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND SERVICE_CODE = '8'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet CA Info\n(VI7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet CA Info\n(VI7b)");
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_row['IS_BILLED'] == 'Y'){
				fresh_screen("DOLE SCANNER\nVoid Inbound Roll\n\nPALLET BILLED.\nUNRECEIVING WILL\nNOTIFY INVENTORY\nAND FINANCE\nVIA EMAIL.", "bad");
				echo "Press Enter\nto continue";
				fscanf(STDIN, "%s\n", $junk);
				$was_billed = "YES";
			}


			fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nDT#: ".$dock_ticket."\nBC: ".$Barcode."\nWT: ".$weight."\nCode: ".$code);
			echo "Received:\n".$row['THE_DATE']."\nUn-Receive? (Y/N)";
			fscanf(STDIN, "%s\n", $verify);
			if(strtoupper($verify) == "Y"){
				$sql = "UPDATE CARGO_TRACKING SET DATE_RECEIVED = NULL, QTY_DAMAGED = NULL WHERE RECEIVER_ID = '".$Customer."' AND ARRIVAL_NUM = '".$Arrival."' AND PALLET_ID = '".$Barcode."' AND BOL = '".$dock_ticket."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VI3a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VI3b)");

				$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND SERVICE_CODE = '8' AND CUSTOMER_ID = '".$Customer."' AND ORDER_NUM = '".$Arrival."' AND BATCH_ID = '".$dock_ticket."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VI4a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VI4b)");

				$sql = "DELETE FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND CUSTOMER_ID = '".$Customer."' AND DOCK_TICKET = '".$dock_ticket."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Damage\n(VI5a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Damage\n(VI5b)");

				// notify billing if scanner unreceived a billed pallet.
				if($was_billed == "YES"){
					$body = $CID." has unreceived pallet ".$Barcode." (DT ".$dock_ticket.", ARV# ".$Arrival.") from the Dole Paper system.\r\nThis pallet has already been charged an inbound truckloading fee from the DolepaperRFBNItruckloading.exe program.\r\nPlease correct the bill (by editing it in DolepaperRFBNItruckloading.exe if Finance has not billed it yet, or by issuing a credit if the bill has already been made).\r\n";
					$mailSubject = "DOLE PAPER ROLL RECEIPT BILLING UPDATE";

//					echo $inv_and_fina_mail_info_list."\n".$mailSubject."\n".$body."\n".$mailHeaders."\n";
//					fscanf(STDIN, "%s\n", $junk);
					mail($inv_and_fina_mail_info_list, $mailSubject, $body, $mailHeaders);
					$sql = "INSERT INTO SCANNER_EMAIL_LOG (FROM_SCANNER_TYPE, DATE_SENT, EMAIL_TO_LIST, EMAIL_BODY, EMAIL_SUBJECT) VALUES ('Dole_DT', SYSDATE, '".$inv_and_fina_mail_info_list."', '".$body."', '".$mailSubject."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Email\n(VI6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Email\n(VI6b)");
//					fscanf(STDIN, "%s\n", $junk);
//					ora_rollback($rf_conn);
				}

				ora_commit($rf_conn);
				fresh_screen("DOLE SCANNER\nVoid Inbound Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nDT#: ".$dock_ticket."\nBC: ".$Barcode."\nVOIDED!");
			}
		}

		echo "\nNEXT BARCODE:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function VoidOUT($CID) {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$Order = "";
	$dock_ticket = "";

	fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.");
	while($Order == ""){
		echo "OB-ORDER:";
		fscanf(STDIN, "%s\n", $Order);
		$Order = strtoupper($Order);
		if($Order == "X"){
			return;
		}

		$Order = remove_badchars($Order);

		$sql = "SELECT STATUS FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VO2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VO2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$order_status = $short_term_data_row['STATUS'];
		if($order_status != 3) {
			fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\n**CANNOT VOID;\nORDER NOT IN\nLOADING STATUS\nCONTACT OFFICE**", "bad");
			$Order = "";
		}
	}
	while($dock_ticket == "" || !is_numeric($dock_ticket)){
		echo "DOCK TKT:";
		fscanf(STDIN, "%s\n", $dock_ticket);
		$dock_ticket = strtoupper($dock_ticket);
		if($dock_ticket == "X"){
			return;
		} 

		$dock_ticket = remove_badchars($dock_ticket);

	}

	// get employee number for use in ACTIVITY_ID later
/*	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
	$emp_no = $short_term_data_row['THE_EMP'];*/
	$emp_no = get_emp_no($CID);

	fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket);

	echo "BARCODE:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){


		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NONE') THE_DATE, CONTAINER_ID, QTY_IN_HOUSE, CT.ARRIVAL_NUM THE_ARRIVAL FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = '".$Barcode."' AND CT.BOL = '".$dock_ticket."' AND CT.DATE_RECEIVED IS NOT NULL AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VO2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VO2b)");

		if(!ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// Pallet incorrect / not in DB
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VO2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket."\nBC: ".$Barcode."\n**INVALID PALLET**\nCONTACT INVENTORY", "bad");
			} else {
				fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket."\nBC: ".$Barcode."\n**PALLET NOT ON\nCHOSEN ORDER**", "bad");
			}
		} elseif($row['QTY_IN_HOUSE'] != "0") {
			// Pallet not shipped, no need to void
			fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket."\nBC: ".$Barcode."\n**NO VOID NEEDED\nNOT SCANNED OUT**", "bad");
//		} elseif($order_status != 3 && $order_status != 6) {
//		} elseif($order_status != 3) {
			// Pallet not shipped, no need to void
//			fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket."\nBC: ".$Barcode."\n**CANNOT VOID\nORDER, NOT IN\nLOADING STATUS\nCONTACT OFFICE**", "bad");
		} else {
//			$Container = $row['CONTAINER_ID'];
			$Arrival = $row['THE_ARRIVAL'];
/*
			$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND BATCH_ID = '".$dock_ticket."' AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VO3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VO3b)");
			ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$max_act_num = $row['THE_MAX'] + 1;
*/
			$sql = "SELECT QTY_CHANGE, ACTIVITY_NUM, CUSTOMER_ID FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND BATCH_ID = '".$dock_ticket."' AND ORDER_NUM = '".$Order."' AND SERVICE_CODE = '6' AND ARRIVAL_NUM = '".$Arrival."' AND ACTIVITY_DESCRIPTION IS NULL ORDER BY ACTIVITY_NUM DESC";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VO4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(VO4b)");
			ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$act_num = $row['ACTIVITY_NUM'];
			$qty_replaced = $row['QTY_CHANGE'];
			$Customer = $row['CUSTOMER_ID'];

			// Verify
			$act_num = get_max_activity_num($Customer, $Barcode, $Arrival);
			fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket."\nBC: ".$Barcode);
			echo "Shipped:\n".$row['THE_DATE']."\nVoid? (Y/N)";
			fscanf(STDIN, "%s\n", $verify);

			if(strtoupper($verify) == "Y"){
				if(!is_max_activity_num($act_num, $Customer, $Barcode, $Arrival)){
					fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$verify = "N";
				}
			}

			if(strtoupper($verify) == "Y"){
				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_replaced." WHERE ARRIVAL_NUM = '".$Arrival."' AND BOL = '".$dock_ticket."' AND PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Palle\n(VO5a)t");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VO5b)");

				$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$Barcode."' AND SERVICE_CODE = '6' AND BATCH_ID = '".$dock_ticket."' AND ORDER_NUM = '".$Order."' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VO6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VO6b)");

				$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES ('".($act_num + 1)."', '12', '".$qty_replaced."', '".$emp_no."', '".$Order."', '".$Customer."', SYSDATE, '".$Barcode."', '".$Arrival."', '1', '".$dock_ticket."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VO7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(VO7b)");

				$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '3' WHERE ORDER_NUM = '".$Order."'";
				// in case this order was finished, un-finish it.
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(V08a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nVoid Pallet\n(V08b)");
				
				ora_commit($rf_conn);
				fresh_screen("DOLE SCANNER\nVoid-Out Roll\nEnter X to exit.\n\nARV #: ".$Arrival."\nORDER: ".$Order."\nDOCK TKT:".$dock_ticket."\nBC: ".$Barcode."\nVOIDED!");
			}
		}

		echo "\nNEXT BARCODE:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}



function ShipOut($CID) {
	global $rf_conn;
	global $dole_inv_mail_list;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$Order = "";
	$Container = "";

	// get employee number for use in ACTIVITY_ID later
/*	$sql = "SELECT TO_NUMBER(EMPLOYEE_ID) THE_EMP FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$CID."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "User ID not in\nPersonnel\n(UC1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
	$emp_no = $short_term_data_row['THE_EMP'];*/
	$emp_no = get_emp_no($CID);


	while($Order == ""){
		fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.");
		echo "ORDER:";
		fscanf(STDIN, "%s\n", $Order);
		$Order = strtoupper($Order);
		if($Order == "X"){
			return;
		} else {
			$sql = "SELECT TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$Order."' AND STATUS IN ('2', '3', '5')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Bad call to\nOrder(SO9a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Bad call to\nOrder(SO9b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\n**ORDER NOT IN\nSHIPPABLE STATUS\nCONTACT OFFICE**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$Order = "";
			} else {
				// no error sent if order doesn't exist; that is handled farther down
				$load_date = $short_term_data_row['THE_LOAD'];
				if($load_date != date('m/d/Y')){
					fresh_screen("DOLE SCANNER\nShip-Out Roll", "bad");
					echo $Order." Has Load\nDate of ".$load_date."\nDo you Wish to\nContinue? Y/N\n(Inventory will\nbe notified)\n";
					$choice = "";
					while($choice != "Y" && $choice != "N"){
						fscanf(STDIN, "%s\n", $choice);
						$choice = strtoupper($choice);
					}
					if($choice == "Y"){
						$sql = "UPDATE DOLEPAPER_ORDER SET LOAD_DATE = TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY') WHERE ORDER_NUM = '".$Order."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Update\nOrder(SO10a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Update\nOrder(SO10b)");
						ora_commit($rf_conn);

						$notify_body = "Change enacted by Employee ".$CID.".\r\n";
						$notify_subject = "Dole Scanner Update:  Order ".$Order." changed to today (".date('m/d/Y').")\r\n";
						mail($dole_inv_mail_list, $notify_subject, $notify_body, $mailHeaders);

						$sql = "INSERT INTO SCANNER_EMAIL_LOG (FROM_SCANNER_TYPE, DATE_SENT, EMAIL_TO_LIST, EMAIL_BODY, EMAIL_SUBJECT) VALUES ('Dole_DT', SYSDATE, '".$dole_inv_mail_list."', '".$notify_body."', '".$notify_subject."')";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to\nInsert Email\n(SO11a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to\nInsert Email\n(SO11b)");
						ora_commit($rf_conn);
					
					} else {
						$Order = "";
					}
				}
			}
	/*		$sql = "SELECT * FROM PAPER_VALID_CONTAINERS WHERE CONTAINER_ID = '".$Order."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Bad call to\nContainers(SO9a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Bad call to\nContainers(SO9b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nOrder # not\nfound for current\nVessel; retype\nto override\n");
				echo "ORDER:"
				fscanf(STDIN, "%s\n", $Order2);
				$Order2 = strtoupper($Order2);
				if($Order2 == "X"){
					return;
				} elseif($Order2 != $Order){
					fresh_screen("Order #\nMismatch.\n\nExiting\nFunction.");
					return;
				}
			} */
		}
	}

	while($Container == ""){
		fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.");
		echo "ORDER: ".$Order."\n\n";
		echo "CONT#:";
		fscanf(STDIN, "%s\n", $Container);
		$Container = strtoupper($Container);
		if($Container == "X"){
			return;
		} elseif(strlen($Container) != 11 || !ereg("^([a-zA-Z]{4})([0-9]{7})$", $Container)){
			fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\n**INVALID CONTAINER\n".$Container."\nMUST BE 4 LETTERS\nTHEN 7 NUMBERS\nCONTACT SUPV**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$Container = "";
		}

		$Container = remove_badchars($Container);
	}


	/*	$sql = "SELECT * FROM WHATEVER_TABLE_EDI_SENT WHERE ORDER_NUM = '".$Order."' AND BOOKING = '".$Booking."' AND CUSTOMER = '".$Customer."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to retrieve\nOrder (SO11a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to retrieve\nOrder (SO11b)");
		if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fresh_screen("Order ".$Order."\nBooking ".$Booking."\nAlready Finalized.\nPlease see\nSupervisor\nIf you need to\nReopen.");
			fscanf(STDIN, "%s\n", $junk);
			return;
		} */

	$dock_ticket = array();
	$dock_ticket_ordered = array();
	$dock_ticket_ordered_dmg = array();
	$dock_ticket_scanned = array();
	$dock_ticket_scanned_dmg = array();


	// get dock ticket list, and QTY's requested
	$sql = "SELECT DOCK_TICKET, QTY_SHIP, QTY_DMG_SHIP FROM DOLEPAPER_DOCKTICKET WHERE ORDER_NUM = '".$Order."' ORDER BY DOCK_TICKET";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDock Tickets\n(SO11a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDock Tickets\n(SO11b)");
	$temp = 0;
	while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$dock_ticket[$temp] = $short_term_data_row['DOCK_TICKET'];
		$dock_ticket_ordered[$temp] = $short_term_data_row['QTY_SHIP'];
		$dock_ticket_ordered_dmg[$temp] = $short_term_data_row['QTY_DMG_SHIP'];
		$temp++;
	}

	// for each dock ticket, figure the current scanned on
	for($i=0; $i < sizeof($dock_ticket); $i++){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE CA.ORDER_NUM = '".$Order."' AND CT.BOL = '".$dock_ticket[$i]."' AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.PALLET_ID = CT.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nOrder Count\n(SO8a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nOrder Count\n(SO8b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$dock_ticket_scanned[$i] = $short_term_data_row['THE_COUNT'];
	}

	// for each dock ticket, figure the current DAMAGE scanned on
	for($i=0; $i < sizeof($dock_ticket); $i++){
		$sql = "SELECT SUM(QTY_DAMAGED) DMG_COUNT FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.PALLET_ID = CT.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CT.BOL = '".$dock_ticket[$i]."' AND CA.ORDER_NUM = '".$Order."' AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nOrder Count\n(SO9a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nOrder Count\n(SO9b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$dock_ticket_scanned_dmg[$i] = $short_term_data_row['DMG_COUNT'];
	}

	// determines if this order is even scannable.  if not, break it.
	$sql = "SELECT STATUS FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$Order."' AND STATUS IN ('2', '3', '5')";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nOrder Info\n(SO15a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nOrder Info\n(SO15b)");
	if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT STATUS FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nOrder Info\n(SO15a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nOrder Info\n(SO15b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fresh_screen("INVALID ORDER#\nPLEASE CONTACT OFFICE\n\nPress enter to\nreturn to menu", "bad");
		} else {
			fresh_screen("ORDER STATUS\nNOT SET FOR\nLOADING.\nCONTACT OFFICE.\n\nPress enter to\nreturn to menu", "bad");
		}
		fscanf(STDIN, "%s\n", $junk);
	} else {

		// update the DOLEPAPER_ORDER table with the container.
		$sql = "UPDATE DOLEPAPER_ORDER SET CONTAINER_ID = '".$Container."' WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($modify_cursor, $sql);
		database_check($ora_success, "Unable to update\nContainer\n(SO16a)");
		$ora_success = ora_exec($modify_cursor, $sql);
		database_check($ora_success, "Unable to update\nContainer\n(SO16b)");

		// Submitted orders get auto-converted to loading.
		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '3' WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nVoid Pallet\n(V08a)");
		$ora_success = ora_exec($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nVoid Pallet\n(V08b)");

		ora_commit($rf_conn);

		fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\nORDER: ".$Order);
		for($i = 0; $i < sizeof($dock_ticket); $i++){
			echo "#".$dock_ticket[$i].": ".(0 + $dock_ticket_scanned[$i])." of ".$dock_ticket_ordered[$i]."\n";
			if($dock_ticket_ordered_dmg[$i] > 0){
				echo " (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
			}
		}

		$Barcode = "";
		if(array_sum($dock_ticket_scanned) != array_sum($dock_ticket_ordered)){
			echo "SCAN BARCODE:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
		while(strtoupper($Barcode) != "X" && array_sum($dock_ticket_scanned) != array_sum($dock_ticket_ordered)){

			$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, QTY_DAMAGED, ARRIVAL_NUM, BOL, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND BOL IN (SELECT DOCK_TICKET FROM DOLEPAPER_DOCKTICKET WHERE ORDER_NUM ='".$Order."')";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(SO12a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(SO12b)");

			if(!ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// Pallet incorrect / not in DB
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(SO14a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(SO14b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**INVALID PALLET\nCONTACT INVENTORY**", "bad");
				} else {
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**PALLET DOES NOT\nMATCH ANY TICKET\nON THIS ORDER**", "bad");
				}
				echo "NEXT BARCODE:\n";
				fscanf(STDIN, "%s\n", $Barcode);
				$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
			} else {
				// Pallet in DB and (sort of) valid, get rest of necessay data and do checks, do the rest of the checks.

				// get dock ticket for scanned roll
				$array_index_this_loop = array_search($row['BOL'], $dock_ticket);

				// get a flag to see if the roll is so damaged it cannot be sent out
				$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dock_ticket[$array_index_this_loop]."' AND DATE_CLEARED IS NULL AND DMG_TYPE LIKE 'R%'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nDMG Info\n(SO2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nDMG Info\n(SO2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] > 0){
					$rejected = true;
				} else {
					$rejected = false;
				}

				if($row['THE_DATE'] == "NONE") {
					// Pallet not received, can not ship
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**DO NOT SHIP\nNOT YET RECEIVED**", "bad");
				} elseif($row['QTY_IN_HOUSE'] == "0"){
					// Pallet no longer here, can not ship
					$sql = "SELECT ORDER_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(SO3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(SO3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$already_order = $short_term_data_row['ORDER_NUM'];

					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**DO NOT SHIP\nALREADY SHIPPED\nON: ".$already_order."**", "bad");
				} elseif(($row['QTY_DAMAGED'] + $dock_ticket_scanned_dmg[$array_index_this_loop]) > $dock_ticket_ordered_dmg[$array_index_this_loop]){
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**DO NOT SHIP\nMAX DMG FOR\n#".$dock_ticket[$array_index_this_loop]." REACHED**", "bad");
				} elseif($dock_ticket_scanned[$array_index_this_loop] >= $dock_ticket_ordered[$array_index_this_loop]){
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**DO NOT SHIP\nAMOUNT FOR\n#".$dock_ticket[$array_index_this_loop]." REACHED**", "bad");
				} elseif((($dock_ticket_ordered[$array_index_this_loop] - $dock_ticket_scanned[$array_index_this_loop]) < ($dock_ticket_ordered_dmg[$array_index_this_loop] - $dock_ticket_scanned_dmg[$array_index_this_loop])) || ((($dock_ticket_ordered[$array_index_this_loop] - $dock_ticket_scanned[$array_index_this_loop]) == ($dock_ticket_ordered_dmg[$array_index_this_loop] - $dock_ticket_scanned_dmg[$array_index_this_loop])) && $row['QTY_DAMAGED'] == "0")) {
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**DO NOT SHIP\nALL REMAINING\nROLLS FOR #".$dock_ticket[$array_index_this_loop]."\nMUST BE DMG**", "bad");
				} elseif($rejected){
					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\n**DO NOT SHIP\nROLL STATUS\nIS REJECTED**", "bad");
				} else {
					$qty_change = $row['QTY_IN_HOUSE'];
					$arrival_num = $row['ARRIVAL_NUM'];
					$Customer = $row['RECEIVER_ID'];

					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND BATCH_ID = '".$dock_ticket[$array_index_this_loop]."' AND ARRIVAL_NUM = '".$arrival_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(SO4a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(SO4b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$max_act_num = $short_term_data_row['THE_MAX'] + 1;

					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = '0' WHERE BOL = '".$dock_ticket[$array_index_this_loop]."' AND ARRIVAL_NUM = '".$arrival_num."' AND PALLET_ID = '".$Barcode."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nShip Pallet\n(SO5a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nShip Pallet\n(SO5b)");

					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES ('".$max_act_num."', '6', '".$qty_change."', '".$emp_no."', '".$Order."', '".$Customer."', SYSDATE, '".$Barcode."', '".$arrival_num."', '0', '".$dock_ticket[$array_index_this_loop]."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to make\nactivity record\n(SO6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to make\nactivity record\n(SO6b)");

					$dock_ticket_scanned[$array_index_this_loop]++;
					$dock_ticket_scanned_dmg[$array_index_this_loop] += $row['QTY_DAMAGED'];

					ora_commit($rf_conn);

					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order."\nBC: ".$Barcode."\nACCEPTED!");
				}

/*
				if(array_sum($dock_ticket_scanned) == array_sum($dock_ticket_ordered)){
					echo "Order Filled.\n";
					for($i = 0; $i < sizeof($dock_ticket); $i++){
						echo "#".$dock_ticket[$i].": ".(0 + $dock_ticket_scanned[$i])." of ".$dock_ticket_ordered[$i]."\n";
						if($dock_ticket_ordered_dmg[$i] > 0){
							echo " (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
						}
					}
					if(array_sum($dock_ticket_scanned_dmg) != array_sum($dock_ticket_ordered_dmg)){
						echo "NOTE:  QTY DMG\nNOT FILLED!\n";
						echo "PLEASE VOID\nA GOOD PALLET\nAND REFILL\nOR CONTACT OFFICE\n";
					} else {
						$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '6' WHERE ORDER_NUM = '".$Order."'";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to\nfinish order\n(SO15a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to\nfinish order\n(SO15b)");
						ora_commit($rf_conn);
					}
					$Barcode = "X";
					fscanf(STDIN, "%s\n", $junk);
				} else {
					for($i = 0; $i < sizeof($dock_ticket); $i++){
						echo "#".$dock_ticket[$i].": ".(0 + $dock_ticket_scanned[$i])." of ".$dock_ticket_ordered[$i]."\n";
						if($dock_ticket_ordered_dmg[$i] > 0){
							echo "   (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
						}
					}
					echo "NEXT BARCODE:\n";
					fscanf(STDIN, "%s\n", $Barcode);
					$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
				} */
				if(array_sum($dock_ticket_scanned) != array_sum($dock_ticket_ordered)){
					for($i = 0; $i < sizeof($dock_ticket); $i++){
						echo "#".$dock_ticket[$i].": ".(0 + $dock_ticket_scanned[$i])." of ".$dock_ticket_ordered[$i]."\n";
						if($dock_ticket_ordered_dmg[$i] > 0){
							echo "   (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
						}
					}
					echo "NEXT BARCODE:\n";
					fscanf(STDIN, "%s\n", $Barcode);
					$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
				}
			}
		ora_commit($rf_conn); 
		}
		
		// Barcode loop is over.  Scan Seal needed?
		if(array_sum($dock_ticket_scanned) == array_sum($dock_ticket_ordered)){
			echo "Order Filled.\n";
			if($Barcode != ""){
				for($i = 0; $i < sizeof($dock_ticket); $i++){
					echo "#".$dock_ticket[$i].": ".(0 + $dock_ticket_scanned[$i])." of ".$dock_ticket_ordered[$i]."\n";
					if($dock_ticket_ordered_dmg[$i] > 0){
						echo " (DMG:  ".(0 + $dock_ticket_scanned_dmg[$i])." of ".$dock_ticket_ordered_dmg[$i].")\n";
					}
				}
			}
			if(array_sum($dock_ticket_scanned_dmg) != array_sum($dock_ticket_ordered_dmg)){
				echo "NOTE:  QTY DMG\nNOT FILLED!\n";
				echo "PLEASE VOID\nA GOOD PALLET\nAND REFILL\nOR CONTACT OFFICE\n";
				fscanf(STDIN, "%s\n", $junk);
			} else {
				$sealnum = "";
//				$trial_sealnum = "";
/*				$sql = "SELECT SEAL 
						FROM DOLEPAPER_ORDER
						WHERE ORDER_NUM = '".$Order."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nSeal Info\n(SO16a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nSeal Info\n(SO16b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DB_seal_num = $short_term_data_row['SEAL'];
*/
//				$loop = true;

				while($sealnum == ""){
//					fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order);
//					echo "Enter Seal#\n(If unknown,\nenter NO).\n";
//					if($trial_sealnum != ""){
//						echo "Seal#".$trial_sealnum."\nDoes Not Match\nExpected Seal#.\nPlease Rescan.\n";
//					}
					echo "Scan Seal#\n";
					fscanf(STDIN, "%s\n", $sealnum);
					$sealnum = strtoupper(strip_to_alphanumeric($sealnum));

					if($sealnum == ""){
						// do nothing, they skipped it for some reason
					} elseif($sealnum != "X"){
						$seal_confirm = "";
						fresh_screen("DOLE SCANNER\nShip-Out Roll\nEnter X to exit.\n\nORDER: ".$Order);
						echo "Container\n ".$Container."\n";
						echo array_sum($dock_ticket_scanned)." of ".array_sum($dock_ticket_ordered)." Scanned\n";
						echo "Seal#\n ".$sealnum."\n";
						echo "\"Enter\" to Accept\n";
						echo "\"X\" To Cancel\n";
						fscanf(STDIN, "%s\n", $seal_confirm);
						$seal_confirm = strtoupper(strip_to_alphanumeric($seal_confirm));
						if($seal_confirm == ""){
							$sql = "UPDATE DOLEPAPER_ORDER SET SEAL = '".$sealnum."' WHERE ORDER_NUM = '".$Order."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(SO15a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(SO15b)");
							$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '6' WHERE ORDER_NUM = '".$Order."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(SO16a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nfinish order\n(SO16b)");
							ora_commit($rf_conn);
						}
					}
				}
			}
		}
	}
}
/*	$order_confirm = "";
	while($order_confirm != "N" && $order_confirm != "Y"){
		fresh_screen("DOLE SCANNER\nShip-Out Roll");
		if($order_confirm != ""){
			echo "INVALID ENTRY";
		}
		echo "FINALIZE ORDER?\n(Y / N):"
		fscanf(STDIN, "%s\n", $order_confirm);
		$order_confirm = strtoupper($order_confirm);

		if($order_confirm == "Y"){
			$sql = "INSERT INTO WHATEVER_TABLE_EDI_SENT (ORDER_NUM, BOOKING, CUSTOMER, FINALIZED_ON) VALUES ('".$Order."', '".$Booking."', '".$Customer."', SYSDATE)";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to Finalize\n(SO10a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to Finalize\n(SO10b)");
		}
	} */



function RollInfo() {
	global $rf_conn;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("DOLE SCANNER\nRoll Info\nEnter X to exit.");
	echo "BARCODE:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){
		$pallet_count = 0;
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get count\n(RI1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get count\n(RI1b)");
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallet_count = $row['THE_COUNT'];

		$sql = "SELECT ARRIVAL_NUM, BATCH_ID, WEIGHT, WEIGHT_UNIT, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, BOL, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(RI2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(RI2b)");
		while(ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$pallet_count++;

			$received = $row['THE_DATE'];
			$arrival_num = $row['ARRIVAL_NUM'];
			$weight = $row['WEIGHT']." ".$row['WEIGHT_UNIT'];
			$code = $row['BATCH_ID'];
			$dock_ticket = $row['BOL'];
			$Customer = $row['RECEIVER_ID'];

			$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dock_ticket."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nDMG Info\n(RI4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nDMG Info\n(RI4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			
			if($short_term_row['THE_COUNT'] > 0){
				$DMG = "Y";
			} else {
				$DMG = "N";
			}

			if($received == "NONE"){
				$status = "Not Received";
				$DMG = "";
			} else {
				if($row['QTY_IN_HOUSE'] == "0"){
					$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YY HH24:MI') THE_DATE FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND PALLET_ID = '".$Barcode."' AND ACTIVITY_DESCRIPTION IS NULL AND ARRIVAL_NUM = '".$arrival_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get act\n(RI3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get act\n(RI3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

					$status = "Shipped\nORD#: ".$short_term_data_row['ORDER_NUM']."\nON: ".$short_term_data_row['THE_DATE'];
				} else {
					$status = "In House";
				} 
			}

			fresh_screen("DOLE SCANNER\nRoll Info.\nEnter X to exit.\nBC: ".$Barcode."\nARV #: ".$arrival_num."\nDT#: ".$dock_ticket."\nCode: ".$code."\nWT: ".$weight."\nRcvd: ".$received."\nStatus: ".$status);
			
			echo $pallet_count." of ".$total_pallet_count." Pallets.\n";

			if($DMG == "Y"){
				echo "DMG HIST(Y/N)?\n";
				fscanf(STDIN, "%s\n", $dmg_check);
				if(strtoupper($dmg_check) == "Y"){
					Damage_Report($Barcode, $Customer, $dock_ticket);
				}
			} else {
				echo "No DMG on roll\n";
				fscanf(STDIN, "%s\n", $junk); // to prevent the screen from auto-forwarding
			}

			
		}

		if($total_pallet_count == 0){
			fresh_screen("DOLE SCANNER\nRoll Info.\nEnter X to exit.\n\nNo pallet with\nthat barcode\nin system.");
			fscanf(STDIN, "%s\n", $junk);  // prevent screen from jumping
		} 	
		fresh_screen("DOLE_SCANNER\nRoll Info.\nEnter X to exit.");
		echo "NEXT BARCODE:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function Damage_Report($Barcode, $Customer, $dock_ticket){
	global $rf_conn;

	$cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND CUSTOMER_ID = '".$Customer."' AND DOCK_TICKET = '".$dock_ticket."' ORDER BY DAMAGE_ID";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDMG Info\n(DR3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nDMG Info\n(DR3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$total_damage_count = $short_term_data_row['THE_COUNT'];
	$damage_data_row = 0;

	$sql = "SELECT TO_CHAR(DATE_ENTERED, 'MM/DD/YY HH24:MI') DATE_ENT, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YY HH24:MI'), 'NONE') DATE_CLEAR, DAMAGE_ID, OCCURRED, CHECKER_ID, DMG_TYPE, QUANTITY, QTY_TYPE FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND CUSTOMER_ID = '".$Customer."' AND DOCK_TICKET = '".$dock_ticket."' ORDER BY DAMAGE_ID";
	$ora_success = ora_parse($cursor, $sql);
	database_check($ora_success, "Unable to get act\n(DR1a)");
	$ora_success = ora_exec($cursor, $sql);
	database_check($ora_success, "Unable to get act\n(DR1b)");
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC) && strtoupper($continue) != "X"){
		$damage_data_row++;

//		$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$row['CHECKER_ID']."'";
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$row['CHECKER_ID']."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nEMP Info\n(DR2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nEMP Info\n(DR2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$emp_login = $short_term_data_row['LOGIN_ID'];

		fresh_screen("BC: ".$Barcode."\nDOCK TKT: ".$dock_ticket."\nDAMAGE ID: ".$row['DAMAGE_ID']);

		echo "DAMAGED RECORDED:\n ".$row['DATE_ENT']."\n ".$row['OCCURRED']."\n";
		if($row['DATE_CLEAR'] != "NONE"){
			echo "DAMAGED CLEARED:\n ".$row['DATE_CLEAR']."\n";
		}
		echo "BY: ".$emp_login."\n";
		echo "TYPE: ".$row['DMG_TYPE']."\n";
		if($row['QUANTITY'] != ""){
			echo " (".$row['QUANTITY'].$row['QTY_TYPE'].")\n";
		}

		if($damage_data_row < $total_damage_count){
			echo $damage_data_row." of ".$total_damage_count." DMG records.\n";
			echo "\n(Enter for next,\n X to exit)\n";
		} else {
			echo "\nLast DMG Record.";
		}
		fscanf(STDIN, "%s\n", $continue);
	}
}


function Damage() {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$dock_ticket = "";

	fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.");
/*	echo "CUST:";
	fscanf(STDIN, "%s\n", $Customer);
	$Customer = strtoupper($Customer);
	if($Customer == "X"){
		return;
	} */
	while($dock_ticket == "" || !is_numeric($dock_ticket)){
		echo "DOCK TKT:";
		fscanf(STDIN, "%s\n", $dock_ticket);
		$dock_ticket = strtoupper($dock_ticket);
		if($dock_ticket == "X"){
			return;
		} 

		$dock_ticket = remove_badchars($dock_ticket);

	}

	fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket);

	echo "BARCODE:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){
		
		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, RECEIVER_ID, QTY_DAMAGED, BATCH_ID, WEIGHT || WEIGHT_UNIT THE_WEIGHT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND BOL = '".$dock_ticket."'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(D1a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(D1b)");

		if(!ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// Pallet incorrect / not in DB
			$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(D2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(D2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// wrong DT #
				fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket."\nBC: ".$Barcode."\n**PALLET DOES NOT\nMATCH DOCK TICKET**");
			} else {
				fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket."\nBC: ".$Barcode."\n**INVALID PALLET**\nCONTACT INVENTORY");
			}
		} elseif($row['THE_DATE'] == "NONE") {
			// Pallet not received, can not ship
			fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket."\nBC: ".$Barcode."\n**CAN NOT SET DMG\nNOT YET RECEIVED**");
		} elseif($row['QTY_IN_HOUSE'] == "0") {
			// Pallet not received, can not ship
			fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket."\nBC: ".$Barcode."\n**CAN NOT SET DMG\nALREADY SHIPPED**");
		} else {
			$Customer = $row['RECEIVER_ID'];

			fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket."\nBC: ".$Barcode."\nRcvd: ".$row['THE_DATE']."\nCode: ".$row['BATCH_ID']."\nWT: ".$row['THE_WEIGHT']."\nPress Enter\nTo continue");
			fscanf(STDIN, "%s\n", $junk);

			Advanced_Add_Damage($CID, $Customer, $dock_ticket, $Barcode, "Post-Arrival");
			ora_commit($rf_conn);
			fresh_screen("DOLE SCANNER\nDamage Rolls\nEnter X to exit.\n\nDOCK TKT: ".$dock_ticket);
		} 
		echo "\nNEXT BARCODE:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function ChangeCont($CID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("DOLE SCANNER\nChange Cont.\nEnter X to exit.");
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
			fresh_screen("DOLE SCANNER\nChange Cont.\nEnter X to exit.");
			echo "ORDER:";
			fscanf(STDIN, "%s\n", $Order);
			$Order = strtoupper($Order);
			if($Order == "X"){
				return;
			} else {
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM DOLEPAPER_ORDER
						WHERE ORDER_NUM = '".$Order."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nFind Order\n(CC1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nFind Order\n(CC1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] <= 0){
					echo "\nOrder ".$Order."\nNot Found.";
					fscanf(STDIN, "%s\n", $junk);
					$Order = "";
				}
			}
		}

		$sql = "SELECT NVL(CONTAINER_ID, 'NONE') THE_CONT
				FROM DOLEPAPER_ORDER
				WHERE ORDER_NUM = '".$Order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nFind Order\n(CC2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nFind Order\n(CC2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$current_cont = $short_term_row['THE_CONT'];

		$newcont = "";
		$complete = false;
		while($newcont == "" && !$complete){
			fresh_screen("DOLE SCANNER\nChange Cont.\nEnter X to exit.");
			echo "ORDER: ".$Order."\n";
			echo "Current Container:\n  ".$current_cont."\n";
			echo "New Container:\n";
			fscanf(STDIN, "%s\n", $newcont);
			$newcont = strtoupper($newcont);
			if($newcont == "X"){
				return;
			} elseif($newcont == ""){
				// do nothing
			} else {
				$option = "";
				while($option == ""){
					fresh_screen("DOLE SCANNER\nChange Cont.\nEnter X to exit.");
					echo "ORDER: ".$Order."\n";
					echo "Current Container:\n  ".$current_cont."\n";
					echo "New Container:\n  ".$newcont."\n";
					echo "Y-Commit N-Redo\nC-Cancel\n";
					fscanf(STDIN, "%s\n", $option);
					$option = strtoupper($option);
					if($option == "X"){
						return;
					} elseif($option == "N"){
						$newcont = "";
					} elseif($option == "C"){
						$complete = true;
					} elseif($option == "Y"){
						$sql = "UPDATE DOLEPAPER_ORDER
								SET CONTAINER_ID = '".$newcont."'
								WHERE ORDER_NUM = '".$Order."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CC3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CC3b)");
						ora_commit($rf_conn);
						fresh_screen("DOLE SCANNER\nChange Cont.\nEnter X to exit.");
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

	fresh_screen("DOLE SCANNER\nChange Seal\nEnter X to exit.");
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
			fresh_screen("DOLE SCANNER\nChange Seal\nEnter X to exit.");
			echo "ORDER:";
			fscanf(STDIN, "%s\n", $Order);
			$Order = strtoupper($Order);
			if($Order == "X"){
				return;
			} else {
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM DOLEPAPER_ORDER
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

		$sql = "SELECT NVL(SEAL, 'NONE') THE_SEAL
				FROM DOLEPAPER_ORDER
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
			fresh_screen("DOLE SCANNER\nChange Seal\nEnter X to exit.");
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
					fresh_screen("DOLE SCANNER\nChange Seal\nEnter X to exit.");
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
						$sql = "UPDATE DOLEPAPER_ORDER
								SET SEAL = '".$newseal."'
								WHERE ORDER_NUM = '".$Order."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CS3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nUpdate Order\n(CS3b)");
						ora_commit($rf_conn);
						fresh_screen("DOLE SCANNER\nChange Seal\nEnter X to exit.");
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


function EnterRailcar($CID, $SID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("DOLE SCANNER\nEnter Railcars\nEnter X to exit.");
	echo "ENTER RAILCAR\n";
	fscanf(STDIN, "%s\n", $railcar);
	$railcar = strtoupper($railcar);
	if($railcar != "X"){
		do {
/*			if($container == "X"){ // user cancelled
				$sql = "DELETE FROM BOOKINGCONTLIST";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nCancel\n(EC1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nCancel\n(EC1b)");
				return;
			} else { */
				$sql = "INSERT INTO DOLEDTRAILCARLIST
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
				while($continue == ""){
					fresh_screen("DOLE SCANNER\nEnter Railcars");
					echo "RAILCAR ".$railcar."\n";
					echo "Entered.\n\n";
					echo "Entered another\n";
					echo "Railcar? (Y/N)\n";
					fscanf(STDIN, "%s\n", $continue);
					$continue = strtoupper($continue);
					if($continue == "Y"){  // will return to top loop
						fresh_screen("DOLE SCANNER\nEnter Railcars.");
						echo "ENTER RAILCAR\n";
						fscanf(STDIN, "%s\n", $railcar);
						$railcar = strtoupper($railcar);
/*					} elseif($continue == "X"){  // user cancelled
						$sql = "DELETE FROM BOOKINGCONTLIST";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nCancel\n(EC3a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to\nCancel\n(EC3b)");
						return; */
					} elseif($continue == "N"){ // user complete successfully
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
									'DOLEDTRAILCARLIST')";
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
		} while(true);
	}
}











// Auxiliary functions begin here

function Re_Open_Order() {
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("DOLE SCANNER\nRe-Open Order\nEnter X to exit.");
	echo "CUST:";
	fscanf(STDIN, "%s\n", $Customer);
	$Customer = strtoupper($Customer);
	if($Customer == "X"){
		return;
	}
	echo "BOOKING #:";
	fscanf(STDIN, "%s\n", $Booking);
	$Booking = strtoupper($Booking);
	if($Booking == "X"){
		return;
	}
	echo "ORDER:";
	fscanf(STDIN, "%s\n", $Order);
	$Order = strtoupper($Order);
	if($Order == "X"){
		return;
	}

	fresh_screen("DOLE SCANNER\nRe-Open Order\nEnter X to exit.\n\nCUST: ".$Customer."\nORDER: ".$Order."\nBK#: ".$Booking);
	echo "RE-OPEN?  (Y/N)";
	fscanf(STDIN, "%s\n", $verify);
	$verify = strtoupper($verify);

	if($verify = "Y"){
		$sql = "DELETE FROM WHATEVER_TABLE_EDI_SENT WHERE ORDER_NUM = '".$Order."' AND BOOKING = '".$Booking."' AND CUSTOMER = '".$Customer."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to Re-Open\n(ROO1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to Re-Open\n(ROO1b)");
		echo "\nOrder Re-Opened.";
	} else {
		echo "\nOrder Status\nNot Changed:";
	}
}



function Advanced_Add_Damage($CID, $Customer, $dockticket, $Barcode, $When="no longer used"){
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

	$main_choice = "noodles";
	$damage_choice = "ice cream";

	while($main_choice != "X"){
		$sub_choice = "waffles";
		$quantity = "Y";  // default


		fresh_screen("DOLE SCANNER\nDamage Menu\nEnter X to exit.");
		echo "1: Reject\n";
		echo "2: Damage\n"; 
		echo "3: Set OK to Ship\n"; 
		fscanf(STDIN, "%s\n", $main_choice);
		$main_choice = strtoupper($main_choice);

		switch ($main_choice){
			case 1:			// REJECT ROLLS		REJECT ROLLS		REJECT ROLLS		REJECT ROLLS	
				do {
					fresh_screen("DOLE SCANNER\nREJECT Menu");
					echo "Responsibility?\n";
					echo "1-Port\n";
					echo "2-Carrier\n"; 
					fscanf(STDIN, "%s\n", $damage_choice);
					$damage_choice = strtoupper($damage_choice);
				} while($damage_choice < 1 || $damage_choice > 2);

				switch ($damage_choice){ // get damage responsible party
					case 1:
						$damage_choice = "Port";
					break;
					case 2:
						$damage_choice = "Carrier";
					break;
				}


				do {
					fresh_screen("DOLE SCANNER\nREJECT Menu");
					echo "1-RB:Bottom/Top\n";
					echo "2-RC:Crushed Core\n"; 
					echo "3-RE:Edge >10 PLY\n"; 
					echo "4-RS:Side >10 PLY\n"; 
					echo "5-RW:Water\n"; 
					echo "6-RP:Wrapper\n"; 
					echo "7-RO:Out of Round\n"; 
					echo "8-RH:HYDR Oil\n"; 
					echo "9-Exit\n"; 
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
				} while($sub_choice != "X" && ($sub_choice < 1 || $sub_choice > 9));

				switch ($sub_choice){ // get damage code
					case 1:
						$dmg_type = "RB";
						break;
					case 2:
						$dmg_type = "RC";
						break;
					case 3:
						$dmg_type = "RE";
						break;
					case 4:
						$dmg_type = "RS";
						break;
					case 5:
						$dmg_type = "RW";
						break;
					case 6:
						$dmg_type = "RP";
						break;
					case 7:
						$dmg_type = "RO";
						break;
					case 8:
						$dmg_type = "RH";
						break;
				}

				if($sub_choice == 3 || $sub_choice == 4){
					do {
						fresh_screen("DOLE SCANNER\nREJECT Menu\nSize Select\n(X to Cancel)");
						echo "1: 1/8IN\n";
						echo "2: 1/4IN\n"; 
						echo "3: 1/2IN\n"; 
						echo "4: 3/4IN\n"; 
						echo "5: 1IN\n"; 
						echo "6: >1IN\n"; 
						fscanf(STDIN, "%s\n", $quantity);
						$quantity = strtoupper($quantity); 
						if($quantity == 6){
							$qty_type = "IN+";
						} else {
							$qty_type = "IN";
						}
					} while($quantity != "X" && ($quantity < 1 || $quantity > 6));
				}
			
				if($sub_choice != "X" && $sub_choice != 9 && $quantity != "X"){  // they didn't hit "cancel"
					$sql = "SELECT NVL(MAX(DAMAGE_ID), 0) THE_DMG FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dockticket."' AND CUSTOMER_ID = '".$Customer."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nDamage Info(AAD1a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nDamage Info(AAD1b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$damage_id = $short_term_data_row['THE_DMG'] + 1;

					if($quantity != "Y"){	// this is a measurement-necessary input
						switch ($quantity){
							case 1:
								$DB_qty = "0.125";
								break;
							case 2:
								$DB_qty = "0.25";
								break;
							case 3:
								$DB_qty = "0.5";
								break;
							case 4:
								$DB_qty = "0.75";
								break;
							case 5:
								$DB_qty = "1";
								break;
							case 6:
								$DB_qty = "1";
							break;
						}
					} else {
						$DB_qty = "";
						$qty_type = "";
					}

					$sql = "INSERT INTO DOLEPAPER_DAMAGES (ROLL, DOCK_TICKET, CUSTOMER_ID, DAMAGE_ID, DATE_ENTERED, CHECKER_ID, DMG_TYPE, OCCURRED, QUANTITY, QTY_TYPE) VALUES ('".$Barcode."', '".$dockticket."', '".$Customer."', '".$damage_id."', SYSDATE, '".$emp_no."', '".$dmg_type."', '".$damage_choice."', '".$DB_qty."', '".$qty_type."')";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nDamage Info(AAD2a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nDamage Info(AAD2b)");



/*						$sql = "UPDATE DOLEPAPER_DAMAGES SET QUANTITY = '".$DB_qty."', QTY_TYPE = '".$qty_type."' WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dockticket."' AND CUSTOMER_ID = '".$Customer."' AND DAMAGE_ID = '".$damage_id."'";
						echo $sql."\n";
						fscanf(STDIN, "%s\n", $junk);
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD6a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD6b)");
*/

					$sql = "UPDATE CARGO_TRACKING SET QTY_DAMAGED = QTY_RECEIVED WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Customer."' AND BOL = '".$dockticket."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nCT Damage Info(AAD8a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nCT Damage Info(AAD8b)");


				} // update complete for REJECT		REJECT		REJECT		REJECT		ROLLS
			break;

			case 2:		// NON-REJECT		NON-REJECT		NON-REJECT		NON-REJECT		NON-REJECT		
				do {
					fresh_screen("DOLE SCANNER\nNON-REJECT Menu");
					echo "Responsibility?\n";
					echo "1-Port\n";
					echo "2-Carrier\n"; 
					fscanf(STDIN, "%s\n", $damage_choice);
					$damage_choice = strtoupper($damage_choice);
				} while($damage_choice < 1 || $damage_choice > 2);

				switch ($damage_choice){ // get damage responsible party
					case 1:
						$damage_choice = "Port";
					break;
					case 2:
						$damage_choice = "Carrier";
					break;
				}

				do {
					fresh_screen("DOLE SCANNER\nNON-REJECT Menu");
					echo "1-DE:Edge <10 PLY\n"; 
					echo "2-DS:Side <10 PLY\n"; 
					echo "3-DF:Flat Spot\n"; 
					echo "4-DC:Cracked Wrap\n"; 
					echo "5-DL:Loose Header\n"; 
					echo "6-DH:Chaffing\n";
					echo "7-Exit\n"; 
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
				} while($sub_choice != "X" && ($sub_choice < 1 || $sub_choice > 7));

				switch ($sub_choice){ // get damage code
					case 1:
						$dmg_type = "DE";
						break;
					case 2:
						$dmg_type = "DS";
						break;
					case 3:
						$dmg_type = "DF";
						break;
					case 4:
						$dmg_type = "DC";
						break;
					case 5:
						$dmg_type = "DL";
						break;
					case 6:
						$dmg_type = "DH";
						break;
				}
/*
				if($sub_choice == 1 || $sub_choice == 2){
					do {
					fresh_screen("DOLE SCANNER\nDMG Menu\nSize Select\n(X to Cancel)");
					echo "1: 1/8IN\n";
					echo "2: 1/4IN\n"; 
					echo "3: 1/2IN\n"; 
					echo "4: 3/4IN\n"; 
					echo "5: 1IN\n"; 
					fscanf(STDIN, "%s\n", $quantity);
					$quantity = strtoupper($quantity); 
					$qty_type = "IN";
					} while($quantity != "X" && ($quantity < 1 || $quantity > 4));
				}
*/			
				if($sub_choice != "X" && $sub_choice != 7 && $quantity != "X"){  // they didn't hit "cancel"
					$sql = "SELECT NVL(MAX(DAMAGE_ID), 0) THE_DMG FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dockticket."' AND CUSTOMER_ID = '".$Customer."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nDamage Info(AAD4a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nDamage Info(AAD4b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$damage_id = $short_term_data_row['THE_DMG'] + 1;

					$sql = "INSERT INTO DOLEPAPER_DAMAGES (ROLL, DOCK_TICKET, CUSTOMER_ID, DAMAGE_ID, DATE_ENTERED, CHECKER_ID, DMG_TYPE, OCCURRED) VALUES ('".$Barcode."', '".$dockticket."', '".$Customer."', '".$damage_id."', SYSDATE, '".$emp_no."', '".$dmg_type."', '".$damage_choice."')";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nDamage Info(AAD5a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nDamage Info(AAD5b)");
/*
					if($quantity != "Y"){	// this is a measurement-necessary input
						switch ($quantity){
							case 1:
								$DB_qty = 0.125;
								break;
							case 2:
								$DB_qty = 0.25;
								break;
							case 3:
								$DB_qty = 0.5;
								break;
							case 4:
								$DB_qty = 0.75;
								break;
							case 5:
								$DB_qty = 1;
								break;
						}

						$sql = "UPDATE DOLEPAPER_DAMAGES SET QUANTITY = '".$DB_qty."', QTY_TYPE = '".$qty_type."' WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dockticket."' AND CUSTOMER_ID = '".$Customer."' AND DAMAGE_ID = '".$damage_id."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD6a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Insert\nDamage Info(AAD6b)");

					} 
*/
/*					$sql = "UPDATE CARGO_TRACKING SET QTY_DAMAGED = QTY_RECEIVED WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Customer."' AND BOL = '".$dockticket."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nCT Damage Info(AAD8a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Insert\nCT Damage Info(AAD8b)");
*/
				} // update complete for NON-REJECT		NON-REJECT		NON-REJECT		NON-REJECT		ROLLS
			break;

			case 3:
				fresh_screen("DOLE SCANNER\nNON-REJECT Menu");
				echo "TKT#: ".$dockticket."\nBC: ".$Barcode."\n";

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE BOL = '".$dockticket."' AND PALLET_ID = '".$Barcode."' AND (PALLET_ID, BOL) IN (SELECT ROLL, DOCK_TICKET FROM DOLEPAPER_DAMAGES WHERE DATE_CLEARED IS NULL AND DMG_TYPE LIKE 'R%')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Clear\nDamage Info(AAD8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Clear\nDamage Info(AAD8b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					echo "This roll is\n already cleared\nto ship\n";
					fscanf(STDIN, "%s\n", $junk);
				} else {

					echo "Is this roll OK\nTo ship?\n(Y/N)";
					fscanf(STDIN, "%s\n", $verify);
					if(strtoupper($verify) == "Y"){
						$sql = "UPDATE DOLEPAPER_DAMAGES SET DATE_CLEARED = SYSDATE WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dockticket."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Clear\nDamage Info(AAD7a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Clear\nDamage Info(AAD7b)");
						
						echo "Roll Cleared\n";
						fscanf(STDIN, "%s\n", $junk);
					} else {
						echo "Roll NOT Cleared\n";
						fscanf(STDIN, "%s\n", $junk);
					}
				}
			break;
		
		}
		
		if($main_choice == 1 || $main_choice == 2){ // assuming they did some damage function, ask for more
			fresh_screen("DOLE SCANNER\nDamage Menu");
			echo "Would you like\nRecord More Damage\nOn this roll?\n(Y/N):";
			fscanf(STDIN, "%s\n", $main_choice);
			if(strtoupper($main_choice) == "N"){
				$main_choice = "X";
			}
		} else {
			$main_choice = "X";
		}
	}
}











// ---------------MAIN LOGIC-----------------
// starts here

$SID = Login_Super($argv[1]);
// This scanner doesn't use the same "test" environment, so redefine it here.
//ora_logoff($rf_conn);
//$rf_conn = ora_logon("TCONSTANT@RFTEST", "TCONSTANT");
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}


do {
	fresh_screen("Dole Paper");
	echo "1: Super Functions\n";
	echo "2: Checker\n";
	echo "3: Exit Dole\n";
	fscanf(STDIN, "%s\n", $DoleSuperCHOICE);
} while ($DoleSuperCHOICE != 1 && $DoleSuperCHOICE != 2 && $DoleSuperCHOICE != 3);

if($DoleSuperCHOICE == 1){
	do {
		fresh_screen("Dole Paper\n(Super)");
		echo "1: Re-Open Order\n";
		echo "2: Exit Dole\n"; 
		fscanf(STDIN, "%s\n", $DoleCHOICE);

		switch ($DoleCHOICE) {
			case 1:
				echo "under construction\n";
				// Re_Open_Order();
			break;
			
			case 2:
				// will exit
			break;
		}
	}while ($DoleCHOICE != 2);

} elseif($DoleSuperCHOICE == 2){
	
	do {
		if($CID == ""){
			$CID = Login_Checker();
		}
		fresh_screen("Dole Paper\n(Checker)");
		echo "1: Super(", $SID, ")\n";
		echo "2: Checker(", $CID, ")\n";
		echo "3: Unload Cargo\n";
		echo "4: Void\n";
		echo "5: Ship Out\n";
		echo "6: Damage\n"; 
		echo "7: Roll Info\n";
		echo "8: Edit Cont/Seal\n"; 
		echo "9: Enter Railcars\n"; 
		echo "10: Exit Dole\n"; 
		echo "ENTER (1-10):\n";
		fscanf(STDIN, "%s\n", $DoleCHOICE);


		switch ($DoleCHOICE) {
			case 1:
				$SID = Login_Super();
				$CID = Login_Checker();
			break;
			
			case 2:
				$CID = Login_Checker();
			break;

			case 3:
				Unload_Cargo($CID);
			break;

			case 4:
				fresh_screen("VOID PALLET\n1) Inbound\n2) Outbound");
				fscanf(STDIN, "%s\n", $voidCHOICE);
				if($voidCHOICE == "1"){
					VoidIN($CID);
				} elseif($voidCHOICE == "2"){
					VoidOUT($CID);
				}
			break;

			case 5:
				ShipOut($CID);
			break;

			case 6:
				Damage();
			break;

			case 7:
				RollInfo();
			break;

			case 8:
				fresh_screen("CHANGE\n1) Container\n2) Seal");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					ChangeCont($CID);
				} elseif($subCHOICE == "2"){
					ChangeSeal($CID);
				}
			break;

			case 9:
				EnterRailcar($CID, $SID);
			break;

			case 10:
				// will exit
			break;

			default:
		}
	} while ($DoleCHOICE != 10);
}