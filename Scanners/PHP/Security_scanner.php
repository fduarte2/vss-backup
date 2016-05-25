<?
/*
*	Clementine Scanner, originally written Sep/Oct2009.
*
*	As part of the massive migration of scanner programs to PHP
*
********************************************************************************/

/*******************************************************************************
* MENU FUNCTIONS START HERE ****************************************************
********************************************************************************/



function Scan_A_Bunch($UID){

	global $rf_conn;

	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.");
	$Barcode = "";
	echo "Next NONTWIC:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = trim(strtoupper($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$sql = "SELECT COUNT(*) THE_COUNT FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nBC Info\n(SAB1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nBC Info\n(SAB1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] > 0){
			// barcode already exists, update.
			$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET CURRENT_LOCATION = '' WHERE BARCODE = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB2b)");

			$sql = "INSERT INTO NON_TWIC_SCANS (BARCODE, SCAN_TIME, USER_ID) VALUES ('".$Barcode."', SYSDATE, '".$UID."')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB4b)");
		
			$sql = "SELECT NEW_STATUS FROM NON_TWIC_STATUS_CHANGES WHERE USER_LOGIN = '".$UID."' AND NEW_STATUS IS NOT NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// do nothing
			} else {
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = '".$short_term_row['NEW_STATUS']."' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				$ora_success = ora_exec($modify_cursor, $sql);
			}


			
/*			if($UID == "LANE4" || $UID == "DOLE"){
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = 'OFFPORT' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB3b)");
			} else {
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = 'INPORT' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB5b)");
			}
*/
			ora_commit($rf_conn);

			$sql = "SELECT NVL(FIRST_NAME, 'NO FIRST NAME') THE_FIRST, NVL(LAST_NAME, 'NO LAST NAME') THE_LAST, NVL(TRAILER_NBR, 'NO LICENSE') THE_LICENSE FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot get\nBC Info\n(SAB6a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot get\nBC Info\n(SAB6b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			if($UID == 'LANE4'){
				fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.", "bad");
				for($i = 0; $i < 100000; $i++){
					// do nothing
				}
			} else {
				fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.");
			}
			echo "Barcode\n".$Barcode."\nScanned.\n";
			echo $short_term_data_row['THE_FIRST']."\n";
			echo $short_term_data_row['THE_LAST']."\n";
			echo $short_term_data_row['THE_LICENSE']."\n";
			$Barcode = "";
			echo "Next NONTWIC:";
			fscanf(STDIN, "%s\n", $Barcode);
			trim($Barcode = strtoupper($Barcode));
		
		} else {
			// barcode doesn't exist, alter user
			/*
			$sql = "INSERT INTO NON_TWIC_TRUCKER_DETAIL (BARCODE, CURRENT_LOCATION, STATUS) VALUES ('".$Barcode."', '', 'INPORT')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB3b)");
			*/
			fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.\n\n***".$Barcode."\nNot In System.\nContact PoW\nAdmin building.***", "bad");
			fscanf(STDIN, "%s\n", $junk);
			echo "Barcode\n".$Barcode."\nScanned.\n";
			$Barcode = "";
			echo "Next NONTWIC:";
			fscanf(STDIN, "%s\n", $Barcode);
			trim($Barcode = strtoupper($Barcode));
		}
	}
}

function Scan_Seal($UID){
	global $rf_conn;
	$type = "All";

	$select_cursor = ora_open($rf_conn);
	$clrmatch_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$seal = "";
	while($seal == "" || $seal == "Invalid"){
		fresh_screen("SCAN SEALS\n  (".$type.")\nEnter X to exit.");
//		echo "Customer\n  ".$cust."\n";
//		echo "Order\n  ".$order."\n";
		if($seal != ""){
			echo "Invalid Seal #\n";
		}
		echo "Scan Seal:\n";
		fscanf(STDIN, "%[^[]]\n", $seal);
		$seal = strtoupper(trim($seal));
		if($seal == "X"){
			return;
		}
		$seal = remove_badchars($seal);

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_TRUCK_LOAD_RELEASE
				WHERE TRIM(SEAL_NUM) = '".$seal."'
					AND SEAL_TIME IS NOT NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn1-1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn1-1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] >= 1){
			fresh_screen("SCAN SEALS\nEnter X to exit.");
			echo "**SEAL #\n".$seal."\nALREADY SEALED.**";
			fscanf(STDIN, "%s\n", $junk);
			$seal = "Invalid";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CLR_ORDER_SEAL_JOIN
					WHERE SEAL_NUM = '".$seal."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn1-2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn1-2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				// is it an eport-2 specific?
	//						AND CLEM_ORDER_NUM IS NOT NULL";
	//						AND TRIM(CLEM_ORDER_NUM) = '".$order."'";
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CLR_TRUCK_LOAD_RELEASE
						WHERE TRIM(SEAL_NUM) = '".$seal."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn1-3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn1-3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0){
					fresh_screen("SCAN SEALS\nEnter X to exit.");
					echo "**SEAL ".$seal."\nDOES NOT\nMATCH ANY SEAL#\nIN SYSTEM\nCONTACT INVENTORY.**";
					fscanf(STDIN, "%s\n", $junk);
					$seal = "Invalid";
				}
			}
		}
	}
	$order_count = $short_term_data_row['THE_COUNT'];
	$scanned_order_array = array();

	// we now have a valid Seal#.
	while(sizeof($scanned_order_array) < $order_count){
		$valid_order = true;

		fresh_screen("SCAN SEALS\nEnter X to exit.");
		echo "SEAL#: ".$seal."\n";
		echo "Orders on Truck: ".$order_count."\n";
		echo "Orders Scanned: ".sizeof($scanned_order_array)."\n";
		echo "Scan Order:\n";
		fscanf(STDIN, "%[^[]]\n", $order);
		$order = strtoupper(trim($order));
		if($order == "X"){
			return;
		}

		// is this order on the already-scanned list?
		if($valid_order){
			if(in_array($order, $scanned_order_array)){
				fresh_screen("SCAN SEALS\nEnter X to exit.");
				echo "**ORDER ".$order."\nALREADY SCANNED\nON SEAL\n".$seal."**";
				fscanf(STDIN, "%s\n", $junk);
				$valid_order = false;
			}
		}

		// is this order matched with this seal?
		if($valid_order){
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CLR_ORDER_SEAL_JOIN
					WHERE SEAL_NUM = '".$seal."'
						AND ORDER_NUM = '".$order."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn2-2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn2-2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				// is it an eport-2 specific?
	//						AND CLEM_ORDER_NUM IS NOT NULL";
	//						AND TRIM(CLEM_ORDER_NUM) = '".$order."'";
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CLR_TRUCK_LOAD_RELEASE
						WHERE TRIM(SEAL_NUM) = '".$seal."'
							AND TRIM(CLEM_ORDER_NUM) = '".$order."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn2-3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn2-3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0){
					fresh_screen("SCAN SEALS\nEnter X to exit.");
					echo "**ORDER ".$order."\nNOT FOUND\nFOR SEAL\n".$seal."\nCONTACT INVENTORY.**";
					fscanf(STDIN, "%s\n", $junk);
					$valid_order = false;
				}
			}
		}

		// do they want to take it?
		if($valid_order){
			$result = "nope";
			while($result != "X" && $result != ""){
				$sql = "SELECT * 
						FROM CLR_TRUCK_LOAD_RELEASE
						WHERE TRIM(SEAL_NUM) = '".$seal."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn3-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn3-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				fresh_screen("SCAN SEALS");
				echo "SEAL#: ".$seal."\n";
				echo "ORDER#: ".$order."\n";
				echo "Driver Name:\n ".$short_term_data_row['DRIVER_NAME']."\n";
				echo "Tag: ".$short_term_data_row['TRAIL_REEF_PLATE_NUM']."\n";
				echo "Press Enter\n to Verify\n or X to Cancel\n";
				fscanf(STDIN, "%[^[]]\n", $result);
				$result = strtoupper(trim($result));
			}

			if($result != "X"){
				array_push($scanned_order_array, $order);
			}
		}

	}

	// we are done scaning orders 
	// final verification
	if($order_count == sizeof($scanned_order_array)){
		$result = "nope";
		while($result != "X" && $result != ""){
			fresh_screen("SCAN SEALS\nEnter X to exit.");
			echo "SEAL#: ".$seal."\n";
			echo "Orders on Truck: ".$order_count."\n";
			echo "Orders Scanned: ".sizeof($scanned_order_array)."\n";
			echo "Order(s) Matched.\n Press Enter\n to Seal Truck\n or X to Cancel\n";
			fscanf(STDIN, "%[^[]]\n", $result);
			$result = strtoupper(trim($result));
		}

		if($result == "X"){
			return;
		} else {
			$sql = "UPDATE CLR_TRUCK_LOAD_RELEASE
					SET SEAL_TIME = SYSDATE,
						LAST_CHANGED_BY = '".$UID."',
						LAST_CHANGED_ON = SYSDATE
					WHERE TRIM(SEAL_NUM) = '".$seal."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Save\nSeal Info\n(SSn4-1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Save\nSeal Info\n(SSn4-1b)");

			// each truck on this seal...
			$sql = "SELECT PORT_ID, TRIM(CLEM_ORDER_NUM) THE_ORD, CUSTOMER_ID, ARRIVAL_NUM
					FROM CLR_TRUCK_LOAD_RELEASE
					WHERE TRIM(SEAL_NUM) = '".$seal."'";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn5-1a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn5-1b)");
			while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// for each line in TLR, find the distinct list of BOL/container on it
				$sql = "SELECT DISTINCT BOL, NVL(CONTAINER_ID, 'NC') THE_CONT
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
						WHERE CT.PALLET_ID = CA.PALLET_ID
							AND CT.RECEIVER_ID = CA.CUSTOMER_ID
							AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
							AND CA.ORDER_NUM = '".$select_row['THE_ORD']."'
							AND CA.CUSTOMER_ID = '".$select_row['CUSTOMER_ID']."'
							AND CA.ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
							AND SERVICE_CODE = '6'";
//				echo $sql."\n";
//				fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($clrmatch_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn6-1a)");
				$ora_success = ora_exec($clrmatch_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn6-1b)");
				while(ora_fetch_into($clrmatch_cursor, $clrmatch_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// for each BOL/container, find if there is a CLR row...
					$sql = "SELECT CLR_KEY
							FROM CLR_MAIN_DATA
							WHERE ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'
								AND BOL_EQUIV = '".$clrmatch_row['BOL']."'
								AND CONTAINER_NUM = '".$clrmatch_row['THE_CONT']."'";
//					echo $sql."\n";
//					fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn7-1a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn7-1b)");
					if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$clr_key = $short_term_data_row['CLR_KEY'];
					} else {
						$clr_key = "";
					}

					// and then, if there is no join...
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM CLR_TRUCK_MAIN_JOIN
							WHERE TRUCK_PORT_ID = '".$select_row['PORT_ID']."'
								AND MAIN_CLR_KEY = '".$clr_key."'";
//					echo $sql."\n";
//					fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn8-1a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn8-1b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] <= 0 && $clr_key != "" && $select_row['PORT_ID'] != ""){

						// make one.
						$sql = "INSERT INTO CLR_TRUCK_MAIN_JOIN
									(TRUCK_PORT_ID,
									MAIN_CLR_KEY)
								VALUES
									('".$select_row['PORT_ID']."',
									'".$clr_key."')";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn9-1a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nSeal Info\n(SSn9-1b)");
					}
				}
			}

			ora_commit($rf_conn);
			fresh_screen("SCAN SEALS");
			echo "SEAL#: ".$seal."\n";
			echo "Complete.\n";
			fscanf(STDIN, "%s\n", $junk);

		}
	}
}
			
					


// ---------------MAIN Menu-----------------
// starts here

global $LR_NUM;

if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}
	$short_term_data_cursor = ora_open($rf_conn);

	
do {
	$mainCHOICE = "";



	while($UID == ""){
		$UID = strtoupper(Login_User());
		$sql = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '".$UID."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nUser Detail\n(Login1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nUser Detail\n(Login1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)
			|| trim($short_term_data_row['COMPANY_NAME']) != "NONTWIC"){
				fresh_screen("This is not\nan authorized\nUser for this\nScanner", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$UID = "";
		}
	}
	fresh_screen("SECURITY");
	echo "1: User(".$UID.")\n"; 
	echo "2: Scan NONTWICs\n"; 
	echo "3: Scan Seal\n"; 
	echo "4: Exit\n"; 
	echo "ENTER (1-3):\n";
	fscanf(STDIN, "%s\n", $mainCHOICE);


	switch ($mainCHOICE) {		
		case 1:
			$UID = "";
		break;

		case 2:
			Scan_A_Bunch($UID);
		break;

		case 3:
/*
			$subCHOICE = 0;
			fresh_screen("SCAN SEALS\n1) Clementine\n2) Chilean/Argen\n");
			fscanf(STDIN, "%s\n", $subCHOICE);
			if($subCHOICE == "1"){
				Scan_Seals_Clem($UID);
			} elseif($subCHOICE == "2"){
				Scan_Seals_Chilean_Argen($UID);
			}
*/
			Scan_Seal($UID);
		break;

		default:
	}
} while ($mainCHOICE != 4);