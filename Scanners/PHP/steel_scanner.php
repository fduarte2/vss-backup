<?
/*
*	Steel Scanner, originally written Jun/Jul2011.
*
*	This is the last of the GreenBox (VB/EZComm) scanners to be converted to PHP
*
*	This scanner handles the scanning of Steel cargo.
********************************************************************************/

/*******************************************************************************
* MENU FUNCTIONS START HERE ****************************************************
********************************************************************************/

function Unload_Ship($CID) {
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 
		// get and validate ship #
		$Arrival = "XXX";
		while($Arrival == "XXX" || $Arrival == "Invalid"){
			fresh_screen("STEEL\nUnload Ship\nEnter X to exit.");
			if($Arrival != "XXX"){
				echo "Invalid Ship #\n(".$Arrival.")\n";
			}
			echo "LR Num\n";
			fscanf(STDIN, "%s\n", $Arrival);
			$Arrival = strtoupper($Arrival);
			if($Arrival == "X"){
				return;
			}
			

			if(!is_numeric($Arrival)){
				$Arrival = "Invalid";
			}  else {
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nVessel Name\n(US1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nVessel Name\n(US1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$Arrival = "Invalid";
				}
			}
		}
/*
		do{
			fresh_screen("STEEL\nUnload Ship\nEnter X to exit.");
			echo "LR: ".$Arrival."\n\n";
			echo "Type:\n";
			echo " 1: Coils\n";
			echo " 2: Plates\n";
			fscanf(STDIN, "%s\n", $type);
			$type = strtoupper($type);

			if($type == "X"){
				return;
			} elseif($type == 1){
//				$cust = "1858";
//				$type_display = "Coils (1858)";
				$type_display = "Coils";
			} elseif($type == 2){
//				$cust = "6979";
//				$type_display = "Plates (6979)";
				$type_display = "Plates";
			}
		} while ($type != 1 && $type != 2 && $type != "X");
*/
		$Barcode = "";
		fresh_screen("STEEL\nUnload Ship\nEnter X to exit.");
		echo "LR#: ".$Arrival."\n";
//		echo "Type: ".$type_display."\n";
		echo "Barcode:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper($Barcode);

		$scanned_this_session = 0;
		while($Barcode != "X") {
			$Comm = "";
			$continue_pallet = true;

//						AND RECEIVER_ID = '".$cust."'
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
					WHERE PALLET_ID = '".$Barcode."' 
						AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(US1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(US1b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
						WHERE PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					// pallet does not exist
					fresh_screen("STEEL\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				} else {
					// wrong entry
					fresh_screen("STEEL\nUnload Ship\nEnter X to exit.\n\nLR# ".$Arrival."\nHAS NO ENTRY FOR\nBC ".$Barcode, "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			} elseif($short_term_data_row['THE_COUNT'] > 1){
				$cust = "";
				$continue_pallet = Select_Duplicate_Pallet($Barcode, $cust, $Arrival, $Comm, $junk);
			} else {
				$sql = "SELECT RECEIVER_ID, COMMODITY_CODE
						FROM CARGO_TRACKING
						WHERE PALLET_ID = '".$Barcode."' 
							AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US2a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US2b-a)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$cust = $short_term_data_row['RECEIVER_ID'];
				$Comm = $short_term_data_row['COMMODITY_CODE'];
			}



			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' AND SERVICE_CODE NOT IN ('1', '8', '18', '19', '20', '21', '22')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(US15a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(US15b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$activity_count = $short_term_data_row['THE_COUNT'];
				if($activity_count > 0){
					fresh_screen("STEEL\nUnload Ship\n\nBC: ".$Barcode."\nAlready has\nActivity.\nCannot Re-Receive.", "bad");
					$continue_pallet = false;
				}
			}


			if($continue_pallet){
				// get steel pallet data
				$sql = "SELECT NVL(QTY_CHANGE, 0) QTY_RECEIVED, CARGO_DESCRIPTION, WEIGHT, WEIGHT_UNIT, WAREHOUSE_LOCATION, QTY_EXPECTED
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_ACTIVITY CA
						WHERE CT.PALLET_ID = '".$Barcode."' 
						AND CT.RECEIVER_ID = '".$cust."'
						AND CT.ARRIVAL_NUM = '".$Arrival."'
						AND CT.PALLET_ID = CTAD.PALLET_ID 
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CA.PALLET_ID(+) 
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID(+)
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM(+)
						AND CA.ACTIVITY_NUM(+) = 1";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_exp = $short_term_data_row['QTY_EXPECTED'];
				$qty_to_rec = $qty_exp - $qty_rec;
				$code = $short_term_data_row['WAREHOUSE_LOCATION'];
				$weight = $short_term_data_row['WEIGHT'].$short_term_data_row['WEIGHT_UNIT'];
				$mark = substr($short_term_data_row['CARGO_DESCRIPTION'], 0, 14)."\n ".substr($short_term_data_row['CARGO_DESCRIPTION'], 14, 20)."\n ".substr($short_term_data_row['CARGO_DESCRIPTION'], 34);

				$sub_choice = "default";
				while($sub_choice != "" && $sub_choice != "X"){
					fresh_screen("STEEL\nUnload Ship\nEnter X to exit.");
					echo "LR#: ".$Arrival."\n";
//					echo "Type: ".$type_display."\n";
					echo "BC: ".$Barcode."\n\n";
					echo "Code: ".$code."\n";
					echo "Weight: ".$weight."\n";
					echo "Mark: ".$mark."\n";
					echo "Received: ".$qty_rec." of ".$qty_exp."\n";
					echo "QTY TO RECEIVE: ".$qty_to_rec."\n\n";
					echo "C = Change QTY\nEnter=OK X=Exit\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if($sub_choice == "C"){
						system("clear");
						echo "QTY to RECV: ".$qty_to_rec."\n";
						echo "New REC QTY:\n";
						$new_qty = "";
						fscanf(STDIN, "%s\n", $new_qty);
						$new_qty = strtoupper($new_qty);
						if(is_numeric($new_qty) && $new_qty >= 0 && ($new_qty + $qty_rec) <= $qty_exp){
							$qty_to_rec = $new_qty;
						}
					} elseif($sub_choice == "X"){
						fresh_screen("STEEL\nUnload Ship\n\nBC: ".$Barcode."\nCancelled.");
						$continue_pallet = false;
					}
				}
			}

			if($continue_pallet){
				$qty_rec = ($qty_rec + $qty_to_rec);
				// they typed in an "accepted" entry to pallet received value.  Receive.
				$sql = "UPDATE CARGO_TRACKING
						SET DATE_RECEIVED = SYSDATE,
							QTY_IN_HOUSE = '".$qty_rec."'
						WHERE PALLET_ID = '".$Barcode."' 
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to save\nPallet Info\n(US4a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to save\nPallet Info\n(US4b)");

				$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '1'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Pallet\n(US5a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Pallet\n(US5b)");

				$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM,
						SERVICE_CODE,
						QTY_CHANGE,
						ACTIVITY_ID,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
						VALUES
						('1',
						'1',
						'".$qty_rec."',
						'".$emp_no."',
						'".$cust."',
						SYSDATE,
						'".$Barcode."',
						'".$Arrival."',
						'".$qty_rec."')"; 
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to save\nPallet Info\n(US6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to save\nPallet Info\n(US6b)");

				ora_commit($rf_conn);
				$scanned_this_session++;
				$count += $increm_count;
				fresh_screen("STEEL\nUnload Ship\nEnter X to Exit\n\nBC: ".$Barcode."\n\nRECEIVED!");
			}

			fresh_screen("STEEL\nUnload Ship\nEnter X to exit.");
			echo "LR#: ".$Arrival."\n";
			echo "Scanned: ".$scanned_this_session."\n\n";
//			echo "Type: ".$type_display."\n";
			$Barcode = "";
			echo "Barcode:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper($Barcode);
		}
	}
}

function VoidOUT($CID){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 
		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}

			$sql = "SELECT CUSTOMER_ID, LR_NUM, COMMODITY_CODE, SO.DONUM
					FROM STEEL_ORDERS SO, STEEL_PRELOAD_DO_INFORMATION SPDI
					WHERE SO.DONUM = SPDI.DONUM
						AND SO.PORT_ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\n**CANNOT FIND\nORDER# IN SYSTEM", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} else {
				$Arrival = $short_term_data_row['LR_NUM'];
				$cust = $short_term_data_row['CUSTOMER_ID'];
				$comm = $short_term_data_row['COMMODITY_CODE'];
				$DO_num = $short_term_data_row['DONUM'];

				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
						WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 14);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
						WHERE COMMODITY_CODE = '".$comm."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 14);

				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
						WHERE TO_CHAR(LR_NUM) = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$vesname = $Arrival."-".substr($short_term_data_row['VESSEL_NAME'], 0, 9);
			}
		}

		$total_cases = 0;
		$total_pallets = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
				$total_cases += $short_term_data_row['THE_SUM'];
				$total_pallets++;
			}
		}

		fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "LR#: ".$Arrival."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){
			$continue_pallet = true;

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '".$cust."' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				$sql = "SELECT TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, QTY_RECEIVED, 
							QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION 
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
						WHERE CT.PALLET_ID = '".$Barcode."' 
							AND CT.PALLET_ID = CA.PALLET_ID 
							AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
							AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
							AND CA.SERVICE_CODE = '6' 
							AND CA.ORDER_NUM = '".$order_num."' 
							AND CT.RECEIVER_ID = '".$cust."' 
							AND CA.ACTIVITY_DESCRIPTION IS NULL 
						GROUP BY QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_void = $short_term_data_row['THE_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}

			if($continue_pallet){
				if(!validate_comm_to_scannertype($comm, $scanner_type)){
					fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// AT THIS POINT, we have all the info we need to void.

			// display data, request confirmation
			if($continue_pallet){
				$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

				$sub_choice = "";
				while($sub_choice != "N" && $sub_choice != "Y"){
					system("clear");
					echo $Barcode."\n";
					echo "Vesl: ".$vesname."\n";
					echo "Out: ".$date_ship."\n";
					echo "Cust: ".$custname."\n";
					echo "Comm: ".$commname."\n";
					echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
					echo "Loc: ".$loc."\n";
					echo "QTY SHIPPED: ".$qty_to_void."\n\n";
					echo "Void?  Y/N\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if($sub_choice == "N"){
						$continue_pallet = false;
					}
				}
			}

			if($continue_pallet){
				if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
					fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// if void was confirmed, lets do it!
			if($continue_pallet){
				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_to_void.", CARGO_STATUS = NULL WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout6b)");

				$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout7b)");

				// add CA record of void
				$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
				('".($act_num + 1)."',
				'12',
				'".$qty_to_void."',
				'".$emp_no."',
				'".$order_num."',
				'".$cust."',
				SYSDATE,
				'".$Barcode."',
				'".$Arrival."',
				'".($qty_in_house + $qty_to_void)."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout9a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout9b)");

				ora_commit($rf_conn);
				fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.");
				echo $qty_to_void." qty\n";
				echo $Barcode."\n VOIDED FROM ORDER\n\n";
				fscanf(STDIN, "%s\n", $junk);
			}

			$total_cases = 0;
			$total_pallets = 0;

			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout10a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout10b)");
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
					$total_cases += $short_term_data_row['THE_SUM'];
					$total_pallets++;
				}
			}

			fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "LR#: ".$Arrival."\n";
			echo "Ord#: ".$order_num."\n";
			echo $total_cases." in ".$total_pallets." plts\n\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function VoidOUTORDER($CID){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while($continue_function){ // in case they want to move to next one 
		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("STEEL\nVoid Outbound\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}

			$sql = "SELECT CUSTOMER_ID, LR_NUM, COMMODITY_CODE, SO.DONUM
					FROM STEEL_ORDERS SO, STEEL_PRELOAD_DO_INFORMATION SPDI
					WHERE SO.DONUM = SPDI.DONUM
						AND SO.PORT_ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\n**CANNOT FIND\nORDER# IN SYSTEM", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} else {
				$Arrival = $short_term_data_row['LR_NUM'];
				$cust = $short_term_data_row['CUSTOMER_ID'];
				$comm = $short_term_data_row['COMMODITY_CODE'];
				$DO_num = $short_term_data_row['DONUM'];

				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
						WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 14);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
						WHERE COMMODITY_CODE = '".$comm."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 14);

				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
						WHERE TO_CHAR(LR_NUM) = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$vesname = $Arrival."-".substr($short_term_data_row['VESSEL_NAME'], 0, 9);
			}
		}


		// get pallets and cases on order, including partials
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(DISTINCT PALLET_ID) THE_PALLETS FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_cases = $short_term_data_row['THE_SUM'];
		$total_pallets = $short_term_data_row['THE_PALLETS'];

		fresh_screen("CHILEAN FRUIT\nVoid Out-ORDER\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "VOID ENTIRE ORDER?\n";
		echo "Y - Yes\n";
		echo "N - No\n";
		echo "X - Exit function\n";
		fscanf(STDIN, "%s\n", $choice);
		$choice = strtoupper($choice);

		switch($choice){
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
				while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(VoutO5a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(VoutO5b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;

					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$select_row['QTY_CHANGE'].", CARGO_STATUS = NULL WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
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
					'".($select_row['QTY_CHANGE'] + $select_row['QTY_LEFT'])."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO8b)");

				}
				ora_commit($rf_conn);
				fresh_screen("STEEL\nVoid Out-ORDER\nEnter X to exit.");
				echo $order_num."\nVOIDED\n\n";
				fscanf(STDIN, "%s\n", $junk);

			break;
		}
	}
}


function Load_Truck($CID){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 

		// get Order
		$order_num = "";
		$ship_method = "";
		while($order_num == ""){
			fresh_screen("STEEL\nTruck Out\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$sql = "SELECT CUSTOMER_ID, LR_NUM, COMMODITY_CODE, SO.DONUM, NVL(ORDER_STATUS, 'OPEN') THE_STAT
					FROM STEEL_ORDERS SO, STEEL_PRELOAD_DO_INFORMATION SPDI
					WHERE SO.DONUM = SPDI.DONUM
						AND SO.PORT_ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\n**CANNOT FIND\nORDER# IN SYSTEM", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} elseif($short_term_data_row['THE_STAT'] != "OPEN"){
				fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\n**ORDER MARKED AS\n".$short_term_data_row['THE_STAT'].".  ITEMS CANNOT\nBE SCANNED ONTO THIS\nORDER.\nCONTACT INVENTORY***", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} else {
				while($ship_method == ""){
					// this step is due to design specs of HD8440.  In future, it may be determined that this option should not be
					// on the scanners, instead handled by INVE at the steel order screen.  But for now, here it is.
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.");
					echo "Order #:".$order_num."\n";
					echo "Delivery Type:\n";
					echo "  1)Truck\n";
					echo "  2)Railcar\n";
					fscanf(STDIN, "%s\n", $ship_method);
					$ship_method = strtoupper($ship_method);
					if($ship_method == "X"){
						return;
					} elseif($ship_method == "1"){
						$ship_method = "TRUCK";
					} elseif($ship_method == "2"){
						$ship_method = "RAILCAR";
					} else {
						$ship_method = "";
					}
				}
				$Arrival = $short_term_data_row['LR_NUM'];
				$cust = $short_term_data_row['CUSTOMER_ID'];
				$comm = $short_term_data_row['COMMODITY_CODE'];
				$DO_num = $short_term_data_row['DONUM'];

				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
						WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 14);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
						WHERE COMMODITY_CODE = '".$comm."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 14);

				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
						WHERE TO_CHAR(LR_NUM) = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$vesname = $Arrival."-".substr($short_term_data_row['VESSEL_NAME'], 0, 9);
			}
		}

		$total_qty = 0;
		$total_pallets = 0;
		$total_weight = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID
				FROM CARGO_ACTIVITY 
				WHERE CUSTOMER_ID = '".$cust."' 
					AND ORDER_NUM = '".$order_num."' 
					AND SERVICE_CODE IN ('6', '7', '12', '13') 
				GROUP BY PALLET_ID";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT2a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT2b)");
		while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($select_row['THE_SUM'] > 0 || $select_row['THE_SUM'] < 0){
				$total_qty += $select_row['THE_SUM'];
				$total_pallets++;
				$sql = "SELECT (WEIGHT / QTY_RECEIVED) WT_PER
								FROM CARGO_TRACKING CT 
								WHERE PALLET_ID = '".$select_row['PALLET_ID']."' 
									AND CT.RECEIVER_ID = '".$cust."' 
									AND CT.ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$total_weight += ($select_row['THE_SUM'] * $short_term_data_row['WT_PER']);

			}
		}


		fresh_screen("STEEL\nTruck Out\nEnter X to exit\nOr P To Print.");
		echo "Ord#: ".$order_num."\n";
		echo "Delv by: ".$ship_method."\n";
		echo "Cust: ".$custname."\n";
		echo "LR#: ".$vesname."\n";
		echo "Comm: ".$commname."\n";
		echo $total_qty." on ".$total_pallets." BCs\n";
		echo " Wt: ".$total_weight."\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X" && $Barcode != "P"){
			$continue_pallet = true;

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**BARCODE DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM CARGO_TRACKING 
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$Arrival."'
							AND REMARK = '".$DO_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**BARCODE DOES NOT\nMATCH CUST/LR/DO#.\nCONTACT\nINVENTORY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// is it received?
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM CARGO_TRACKING 
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$Arrival."'
							AND REMARK = '".$DO_num."'
							AND DATE_RECEIVED IS NOT NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**BARCODE NOT\nYET RECEIVED**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				if(!validate_customer_to_scannertype($cust, $scanner_type, "Load_Truck")){
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\nCust: ".$cust."\n**BARCODE NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				} elseif(!validate_comm_to_scannertype($comm, $scanner_type)){
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// get other pallet data
				$sql = "SELECT QTY_IN_HOUSE, CARGO_DESCRIPTION, (WEIGHT / QTY_RECEIVED) WT_PER, WAREHOUSE_LOCATION 
						FROM CARGO_TRACKING CT 
						WHERE PALLET_ID = '".$Barcode."' 
							AND CT.RECEIVER_ID = '".$cust."' 
							AND CT.ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_ship = $short_term_data_row['QTY_IN_HOUSE'];
				$weight = round($short_term_data_row['WT_PER']);
				$mark1 = substr($short_term_data_row['CARGO_DESCRIPTION'], 0, 14);
				$mark2 = substr($short_term_data_row['CARGO_DESCRIPTION'], 14, 19);
				$mark3 = substr($short_term_data_row['CARGO_DESCRIPTION'], 33);
				$code = $short_term_data_row['WAREHOUSE_LOCATION'];

				if($qty_in_house <= 0){
					// is it not in house?
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nIN HOUSE**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}

			}

			if($continue_pallet){
				$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

				$sub_choice = "default";
				while($sub_choice != "" && $sub_choice != "X"){
					system("clear");
					echo $Barcode."\n";
					echo "Cust: ".$custname."\n";
					echo "Comm: ".$commname."\n";
					echo "Vesl: ".$vesname."\n";
					echo "Code: ".$code."\n";
					echo "Mark: ".$mark1."\n";
					echo " ".$mark2."\n";
					echo " ".$mark3."\n";
					echo "Wt: ".$weight."LB each\n";
					echo "QTY TO SHIP: ".$qty_to_ship."\n\n";
					echo "C = Change QTY\nEnter=OK X=Exit\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if($sub_choice == "C"){
						system("clear");
						echo "QTY to ship: ".$qty_to_ship."\n";
						echo "MAX Available: ".$qty_in_house."\n\n";
						echo "Ship QTY:\n";
						$new_qty = "";
						fscanf(STDIN, "%s\n", $new_qty);
						$new_qty = strtoupper($new_qty);
						if(is_numeric($new_qty)){
							if($new_qty <= $qty_in_house && $new_qty >= 1){
								$qty_to_ship = $new_qty;
							}
						}
					} elseif($sub_choice == "X"){
						$continue_pallet = false;
					}
				}
			}

			if($continue_pallet){
				if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
					fresh_screen("STEEL\nTruck Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// update cargo_tracking with new QTY_IN_HOUSE, CARGO_STATUS values
				$sql = "UPDATE CARGO_TRACKING
						SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$qty_to_ship." 
						WHERE PALLET_ID = '".$Barcode."' 
							AND RECEIVER_ID = '".$cust."' 
							AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT6b)");

				// insert cargo_activity record for outbound shipment
				$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, BATCH_ID, QTY_LEFT)
						VALUES
						('".($act_num + 1)."',
						'6',
						'".$qty_to_ship."',
						'".$emp_no."',
						'".$order_num."',
						'".$cust."',
						SYSDATE,
						'".$Barcode."',
						'".$Arrival."',
						'".$ship_method."',
						'".($qty_in_house - $qty_to_ship)."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT7b)");

				// transaction complete.
				ora_commit($rf_conn);
			}

			$total_qty = 0;
			$total_pallets = 0;
			$total_weight = 0;

			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID
					FROM CARGO_ACTIVITY 
					WHERE CUSTOMER_ID = '".$cust."' 
						AND ORDER_NUM = '".$order_num."' 
						AND SERVICE_CODE IN ('6', '7', '12', '13') 
					GROUP BY PALLET_ID";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT2a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT2b)");
			while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($select_row['THE_SUM'] > 0 || $select_row['THE_SUM'] < 0){
					$total_qty += $select_row['THE_SUM'];
					$total_pallets++;
					$sql = "SELECT (WEIGHT / QTY_RECEIVED) WT_PER
									FROM CARGO_TRACKING CT 
									WHERE PALLET_ID = '".$select_row['PALLET_ID']."' 
										AND CT.RECEIVER_ID = '".$cust."' 
										AND CT.ARRIVAL_NUM = '".$Arrival."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT1-3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$total_weight += ($select_row['THE_SUM'] * $short_term_data_row['WT_PER']);

				}
			}

			fresh_screen("STEEL\nTruck Out\nEnter X to exit\nOr P To Print.");
			echo "Ord#: ".$order_num."\n";
			echo "Delv by: ".$ship_method."\n";
			echo "Cust: ".$custname."\n";
			echo "LR#: ".$vesname."\n";
			echo "Comm: ".$commname."\n";
			echo $total_qty." on ".$total_pallets." BCs\n";
			echo " Wt: ".$total_weight."\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
		if($Barcode == "P"){
			PrintMobileOrder($order_num);
		}
	}
}


function Info_Pallet(){
	global $rf_conn;
	global $scanner_type;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);



	fresh_screen("STEEL\nPallet Info\nEnter X to exit.");
	echo "Barcode:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){

		$total_pallets = 0;
		$pallet_info = array();
		$curr_pallet = 0;
		$continue = true;

//QTY_RECEIVED, QTY_DAMAGED, CHEP, CARGO_SIZE, VARIETY, WAREHOUSE_LOCATION
		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT RECEIVED YET') DATE_REC, NVL(SUBSTR(VESSEL_NAME, 0, 14), CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM THE_LR,
					SUBSTR(COMMODITY_NAME, 0, 19) THE_COMM, SUBSTR(CUSTOMER_NAME, 0, 19) THE_CUST, RECEIVER_ID, CT.ARRIVAL_NUM, QTY_IN_HOUSE,
					CARGO_DESCRIPTION, WEIGHT, REMARK, WAREHOUSE_LOCATION
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
				WHERE CT.PALLET_ID = '".$Barcode."'
				AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CT.ARRIVAL_NUM = TO_CHAR(VP.ARRIVAL_NUM(+))
				ORDER BY DATE_RECEIVED DESC NULLS LAST";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nInfo\n(IP5a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nInfo\n(IP5b)");
		if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fresh_screen("STEEL\nPallet Info\nEnter X to exit.\n\nNo Pallet with BC\n".$Barcode."\nIn System.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			// at least 1 pallet here
			// put all matching barcodes in an array
			do {
				$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_ACT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$select_row['RECEIVER_ID']."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND (SERVICE_CODE = '6' OR (SERVICE_CODE = '11' AND ACTIVITY_NUM > 1)) AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nInfo\n(IP6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nInfo\n(IP6b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_info[$total_pallets]["order_out"] = "STILL IN HOUSE";
					$pallet_info[$total_pallets]["date_act"] = "";
				} else {
					$pallet_info[$total_pallets]["order_out"] = $short_term_data_row['ORDER_NUM'];
					$pallet_info[$total_pallets]["date_act"] = $short_term_data_row['THE_ACT'];
				}

				$pallet_info[$total_pallets]["code"] = $select_row['WAREHOUSE_LOCATION'];
				$pallet_info[$total_pallets]["cust"] = $select_row['THE_CUST'];
				$pallet_info[$total_pallets]["LR"] = $select_row['THE_LR']."-".$select_row['THE_VES'];
				$pallet_info[$total_pallets]["comm"] = $select_row['THE_COMM'];
//				$pallet_info[$total_pallets]["loc"] = $select_row['WAREHOUSE_LOCATION'];
//				$pallet_info[$total_pallets]["qty_rec"] = $select_row['QTY_RECEIVED'];
//				$pallet_info[$total_pallets]["dmg"] = $select_row['QTY_DAMAGED'];
//				$pallet_info[$total_pallets]["var"] = $select_row['VARIETY'];
//				$pallet_info[$total_pallets]["size"] = $select_row['CARGO_SIZE'];
//				$pallet_info[$total_pallets]["chep"] = $select_row['CHEP'];
//				if($pallet_info[$total_pallets]["LR"] == $select_row['THE_LR']){
					$pallet_info[$total_pallets]["date_rec"] = $select_row['DATE_REC'];
//				} else {
//					$pallet_info[$total_pallets]["date_rec"] = "LR#".$select_row['THE_LR']." ".$select_row['DATE_REC'];
//				}
				$pallet_info[$total_pallets]["i_h"] = $select_row['QTY_IN_HOUSE'];
				$pallet_info[$total_pallets]["mark"] =  substr($select_row['CARGO_DESCRIPTION'], 0, 20)."\n ".
														substr($select_row['CARGO_DESCRIPTION'], 20, 20)."\n ".
														substr($select_row['CARGO_DESCRIPTION'], 40, 20);
				$pallet_info[$total_pallets]["wt"] = $select_row['WEIGHT'];
				$pallet_info[$total_pallets]["DO"] = $select_row['REMARK'];

				$total_pallets++;
			} while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			if($total_pallets > 1){
				fresh_screen("Duplicate Pallet.\nPress \"Enter\"\nto continue.", "bad");
				fscanf(STDIN, "%s\n", $junk);
			}

			$choice = "there is no spoon";
			$display_set_counter = 0; 
			$max_page = $total_pallets;

			// display pallet
			while($choice != "X" && $choice != "R"){
				// DO NOT USE FRESHSCREEN FUNCTION HERE
				// we need all the screen space we can get.
				system("clear");
				echo "BC: ".$Barcode."\n";
				echo $pallet_info[$display_set_counter]["mark"]."\n";
				echo $pallet_info[$display_set_counter]["wt"]." LBS\n";
				echo "Code: ".$pallet_info[$display_set_counter]["code"]."\n";
				echo "QTY INHOUSE: ".$pallet_info[$display_set_counter]["i_h"]."\n";

				echo $pallet_info[$display_set_counter]["date_rec"]."\n";
				if(strpos($pallet_info[$display_set_counter]["date_rec"], "NOT RECEIVED YET")) {
					echo "\n";
				} elseif($pallet_info[$display_set_counter]["order_out"] != "STILL IN HOUSE"){
					echo "ORD#: ".$pallet_info[$display_set_counter]["order_out"]."\n";
				} else {
					echo $pallet_info[$display_set_counter]["order_out"]."\n";
				}
				echo $pallet_info[$display_set_counter]["date_act"]."\n";

				echo $pallet_info[$display_set_counter]["cust"]."\n";
				echo $pallet_info[$display_set_counter]["comm"]."\n";
				echo $pallet_info[$display_set_counter]["LR"]."\n";
				echo $pallet_info[$display_set_counter]["DO"]."\n";


				echo "Page ".($display_set_counter + 1)." of ".$max_page."\n";
				if($display_set_counter > 0){
					echo "P-Prev    ";
				} else {
					echo "          ";
				}
				if($display_set_counter < ($max_page - 1)){
					echo "N-Next    \n";
				} else {
					echo "          \n";
				}

				echo "X-Exit    R-New Plt\n";

				$choice = "";
				fscanf(STDIN, "%s\n", $choice);
				$choice = strtoupper($choice);

				if($choice == "X"){
					$continue = false;
				} elseif($choice == "N" && ($display_set_counter < ($max_page - 1))){
					$display_set_counter++;
				} elseif($choice == "P" && ($display_set_counter > 0)){
					$display_set_counter--;
				}
			}
				
		}

		if($continue){
			fresh_screen("CHILEAN FRUIT\nPallet Info\nEnter X to exit.");
			echo "Barcode:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		} else{
			$Barcode = "X";
		}
	}
}











// AUXILLIARY FUNCTIONS START HERE
function Select_Duplicate_Pallet($Barcode, &$Cust, &$Arrival, &$Commodity){
/* 
*
* THIS FUNCTION ASSUMES A PROPER CHECK HAS BEEN DONE, AND THAT THE ACTUAL DATA PASSED TO IT
* WILL RESULT IN MULTIPLE PALLETS
*
* Given a $Barcode (and optional remaining arguments), will return in said pass-by-reference
* Arguments the unique combination of Customer, Vessel, and Commodity to identify the pallet
* in CARGO_TRACKING.
*
* Regardless if the optional (by reference) arguments have values in them when they get here, they
* WILL have values when this exits.  Unless the function is cancelled.
*
* return value is FALSE if this function is cancelled out of, TRUE otherwise.
****************************************************************************************************/

	global $rf_conn;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$pallet_number = 0;
	$pallet_info = array();
	// if we get to this point, we have duplicates.  Prompt user for choice of which he wants.
	// restart SQL to do this (makes this function easier to follow)
	$sql = "SELECT COMMODITY_NAME, VESSEL_NAME, CT.COMMODITY_CODE, CT.ARRIVAL_NUM, RECEIVER_ID, WAREHOUSE_LOCATION, QTY_RECEIVED FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, VESSEL_PROFILE VP WHERE PALLET_ID = '".$Barcode."' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
	
	if($Cust != ""){
		$sql .= " AND RECEIVER_ID = '".$Cust."'";
	}
	if($Arrival != ""){
		$sql .= " AND CT.ARRIVAL_NUM = '".$Arrival."'";
	}
	if($Commodity != ""){
		$sql .= " AND CT.COMMODITY_CODE = '".$Commodity."'";
	}
	$sql .= " ORDER BY DATE_RECEIVED DESC NULLS LAST";

	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(SDP1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(SDP1b)");
	while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pallet_info[$pallet_number]["cust"] = $short_term_data_row['RECEIVER_ID'];
		$pallet_info[$pallet_number]["LR"] = $short_term_data_row['ARRIVAL_NUM'];
		$pallet_info[$pallet_number]["vesname"] = $short_term_data_row['VESSEL_NAME'];
		$pallet_info[$pallet_number]["comm"] = $short_term_data_row['COMMODITY_CODE'];
		$pallet_info[$pallet_number]["commname"] = $short_term_data_row['COMMODITY_NAME'];
		$pallet_info[$pallet_number]["loc"] = $short_term_data_row['WAREHOUSE_LOCATION'];
		$pallet_info[$pallet_number]["rec"] = $short_term_data_row['QTY_RECEIVED'];

		$pallet_number++;
	}

	$choice = "there is no spoon";
	$display_set_counter = 0; 
	$max_page = $pallet_number;

	fresh_screen("STEEL\nSelect Duplicate\n\nBC: ".$Barcode."\nDuplicate Pallets\nFound.  Please\nSelect:", "bad");
	echo "(Press Enter)\n";
	fscanf(STDIN, "%s\n", $junk);
	// display pallet
	while(true){
		// infinite loop avoided by escape clauses within

		system("clear");
		echo $Barcode."\n";
		echo "Cust: ".$pallet_info[$display_set_counter]["cust"]."\n";
		echo "LR#: ".$pallet_info[$display_set_counter]["LR"]."\n";
		echo substr($pallet_info[$display_set_counter]["vesname"], 0, 19)."\n";
		echo "Comm: ".$pallet_info[$display_set_counter]["commname"]."\n";
		echo "QTY Rcvd: ".$pallet_info[$display_set_counter]["rec"]."\n";

		echo "Page ".($display_set_counter + 1)." of ".$max_page."\n";
		if($display_set_counter > 0){
			echo "P-Prev    ";
		} else {
			echo "          ";
		}
		if($display_set_counter < ($max_page - 1)){
			echo "N-Next    \n";
		} else {
			echo "          \n";
		}

		echo "Enter-Select X-Exit\n";

		$choice = "";
		fscanf(STDIN, "%s\n", $choice);
		$choice = strtoupper($choice);

		if($choice == "X"){
			return false;
		} elseif($choice == "N" && ($display_set_counter < ($max_page - 1))){
			$display_set_counter++;
		} elseif($choice == "P" && ($display_set_counter > 0)){
			$display_set_counter--;
		} elseif($choice == ""){
			$Cust = $pallet_info[$display_set_counter]["cust"];
			$Commodity = $pallet_info[$display_set_counter]["comm"];
			$Arrival = $pallet_info[$display_set_counter]["LR"];
			$qty_rec = $pallet_info[$display_set_counter]["rec"];
			$new_pallet = false;
			return true;
		}
	}
}

function PrintMobileOrder($order){
	global $rf_conn;
	global $scanner_type;

	$short_term_data_cursor = ora_open($rf_conn);
	$pallet_cursor = ora_open($rf_conn);

	while($order == "" || $order == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nSTEEL TALLY\nEnter X to exit.\n\n";
		if($order != ""){
			echo "Invalid Order #\n";
		}
		echo "Order#:\n";
		fscanf(STDIN, "%s\n", $order);
		$order = strtoupper($order);
		if($order == "X"){
			return;
		}
		$order = remove_badchars($order);

		$sql = "SELECT COUNT(*) THE_COUNT FROM STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO WHERE SPDI.DONUM = SO.DONUM AND PORT_ORDER_NUM = '".$order."' AND SPDI.CUSTOMER_ID = '".$cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(PR2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(PR2b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$order = "Invalid";
		}
	}

	$sql = "SELECT CUSTOMER_ID, LR_NUM, COMMODITY_CODE, SO.DONUM, NVL(ORDER_STATUS, 'OPEN') THE_STAT
			FROM STEEL_ORDERS SO, STEEL_PRELOAD_DO_INFORMATION SPDI
			WHERE SO.DONUM = SPDI.DONUM
				AND SO.PORT_ORDER_NUM = '".$order."'";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(PR3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(PR3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$Arrival = $short_term_data_row['LR_NUM'];
	$cust = $short_term_data_row['CUSTOMER_ID'];
	$comm = $short_term_data_row['COMMODITY_CODE'];
	$DO_num = $short_term_data_row['DONUM'];

	$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
			NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
			FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '".$cust."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	if(!ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$start = "";
		$end = "";
	} else {
		$start = $row['START_TIME'];
		$end = $row['END_TIME'];
	}

	$sql = "SELECT VP.LR_NUM, VESSEL_NAME, COMMODITY_NAME, SPDI.DONUM, LICENSE_NUM
			FROM STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
			WHERE SPDI.DONUM = SO.DONUM AND PORT_ORDER_NUM = '".$order."' AND SPDI.CUSTOMER_ID = '".$cust."'
				AND SPDI.LR_NUM = TO_CHAR(VP.LR_NUM) AND SPDI.COMMODITY_CODE = COMP.COMMODITY_CODE";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vessel = $row['LR_NUM'];
	$vesname = $row['LR_NUM']." - ".$row['VESSEL_NAME'];
	$commname = $row['COMMODITY_NAME'];
	$DO = $row['DONUM'];
	$licnum = $row['LICENSE_NUM'];

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$custname = $short_term_data_row['CUSTOMER_NAME'];

	$sql = "SELECT CT.PALLET_ID, CT.CARGO_DESCRIPTION, CA.QTY_CHANGE, (WEIGHT / QTY_RECEIVED) WT_PER, CA.ACTIVITY_NUM, CA.ARRIVAL_NUM, CA.ACTIVITY_ID
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.PALLET_ID = CA.PALLET_ID And CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CA.ORDER_NUM = '".$order."' AND CA.CUSTOMER_ID = '".$cust."'
				AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL";
	ora_parse($pallet_cursor, $sql);
	ora_exec($pallet_cursor);
	if(!ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		echo $sql."\n";
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		return;
	} else {
		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "Steel_".$order."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/mobile/".$filename.".txt", "w");

		/* Print Tally Banner Info */
		fwrite($handle, " From: mobile ".date('m/d/Y h:i:s a')."\n\n");
		fwrite($handle, " P.O.W. STEEL OUTBOUND TALLY\n\n");
		fwrite($handle, " Port Order#: ".$order."\n");
		fwrite($handle, " Customer: ".substr($custname, 0, 25)."\n");
		fwrite($handle, " Vessel: ".$vesname."\n");
		fwrite($handle, " LICENSE / RAILCAR: ".$licnum."\n\n");
		fwrite($handle, " First Scan: ".$start."\n");
		fwrite($handle, " Last Scan: ".$end."\n");
		fwrite($handle, " Commodity: ".substr($commname, 0, 25)."\n");
		fwrite($handle, " DO#: ".$DO."\n\n");

		$total_weight = 0;
		$total_rolls = 0;

		$the_BC = str_pad(" BARCODE", 11);
		$the_mark = str_pad(" MARK", 35);
		$the_pcs = str_pad(" PC", 4);
		$the_lb = str_pad(" WT(LB)", 9);
		$the_checker = str_pad(" CHKR-ID", 8);
		fwrite($handle, $the_BC.$the_pcs.$the_lb.$the_checker."\n");
		fwrite($handle, "   MARK\n");
		fwrite($handle, " ======================================\n");
		do {
//			$emp_name = get_employee_for_print($pallet_row['PALLET_ID'], $vessel, $cust, $pallet_row['ACTIVITY_NUM'], $rf_conn);
			$emp_name = $pallet_row['ACTIVITY_ID'];
			$total_weight += round($pallet_row['WT_PER'] * $pallet_row['QTY_CHANGE']);
			$total_rolls += $pallet_row['QTY_CHANGE'];

			$the_BC = str_pad(trim(substr($pallet_row['PALLET_ID'], 0, 10)), 11);
			$the_mark = str_pad(trim(substr($pallet_row['CARGO_DESCRIPTION'], 0, 35)), 35);
			$the_pcs= str_pad(trim($pallet_row['QTY_CHANGE']), 4);
			$the_lb = str_pad(number_format($pallet_row['WT_PER'] * $pallet_row['QTY_CHANGE']), 9);
			$the_checker = str_pad(trim(substr($emp_name, 0, 8)), 8);
			fwrite($handle, " ".$the_BC.$the_pcs.$the_lb.$the_checker."\n");
			fwrite($handle, "   ".$the_mark."\n");
		} while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fwrite($handle, "\n");
		fwrite($handle, " ======================================\n");

		$disp_total_words = str_pad("TOTALS:", 11);
		$disp_total_qty = str_pad(trim($total_rolls), 4);
		$disp_total_lb = str_pad(number_format($total_weight), 8);
		fwrite($handle, " ".$disp_total_words.$disp_total_qty.$disp_total_lb."\n");
		fwrite($handle, "\n\n");

		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in mobile printer\n";
		$tally_print_command = "lp -d mobile /home/kiosk/printouts/mobile/".$filename.".txt";
		system($tally_print_command);
//		system($tally_print_command);
//		system($tally_print_command);

	}
}















// ---------------MAIN Menu-----------------
// starts here

$scanner_type = "STEEL";

$SID = Login_Super($argv[1]);
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}


do {
	fresh_screen("STEEL");
	echo "1: Super Functions\n";
	echo "2: Checker\n";
	echo "3: Exit Steel\n";
	fscanf(STDIN, "%s\n", $SteelCHOICE);
} while ($SteelCHOICE != 1 && $SteelCHOICE != 2 && $SteelCHOICE != 3);

if($SteelCHOICE == 1){
	do {
		fresh_screen("Steel-Sup");
		echo "1: Exit\n"; 
		fscanf(STDIN, "%s\n", $SteelChkCHOICE);

		switch ($SteelChkCHOICE) {
			case 1:
				echo "under construction\n";
				// will exit
			break;
		}
	}while ($SteelChkCHOICE != 1);

} elseif($SteelCHOICE == 2){
	
	do {
		$subCHOICE = ""; // no submenus at this time, but keep this line around anyway

		if($CID == ""){
			$CID = Login_Checker();
		}
		fresh_screen("Steel-Chkr");
		echo " 1: Super(", $SID, ")\n";
		echo " 2: Chkr(", $CID, ")\n";
		echo " 3: Unload Cargo\n";
		echo " 4: Load Truck/Railcar\n";
//		echo " 6: Order Info\n"; 
		echo " 5: Void\n";
		echo " 6: Pallet Info\n";
//		echo " 8: Pre-Check Dummy\n"; 
		echo " 7: Exit Steel\n"; 
		echo " ENTER (1-7):\n";
		fscanf(STDIN, "%s\n", $SteelMenuCHOICE);

		switch ($SteelMenuCHOICE) {
			case 1:
				$SID = Login_Super();
				$CID = Login_Checker();
			break;
			
			case 2:
				$CID = Login_Checker();
			break;

			case 3:
				Unload_Ship($CID);
			break;

			case 4:
				Load_Truck($CID);
			break;
/*

			case 6:
				Info_Order();
			break;
*/
			case 5:
				fresh_screen("VOID\n1) Order\n2) Barcode");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					VoidOUTORDER($CID);
				} elseif($subCHOICE == "2") {
					VoidOUT($CID);
				}
			break;

			case 6:
				Info_Pallet();
			break;
/*
			case 8:
				Precheck_Dummy();
			break;
*/
			case 7:
				// will exit
			break;

			default:
		}
	} while ($SteelMenuCHOICE != 7);
}
