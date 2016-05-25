<?
/*
*	Argen Juice Scanner, originally written Apr/May2011.
*
*	As part of the massive migration of scanner programs to PHP
*
********************************************************************************/

/*******************************************************************************
* MENU FUNCTIONS START HERE ****************************************************
********************************************************************************/

function Load_Truck($CID){
	global $rf_conn;
//	global $bni_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

//	$bni_autotrans_check_cursor = ora_open($bni_conn);
	$bni_autotrans_check_cursor = ora_open($rf_conn);
	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 
		$DM_cust = "";
		$DM_num = "";
		$DM_comm = "";
		$DM_LR = "";
		$DM_ordered = "";
		$DM_total_on_order = "";

		$DM_cust_disp = "";
		$DM_comm_disp = "";
		$DM_LR_disp = "";

		while($DM_num == ""){
			fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.");
			echo "DM #:\n";
			fscanf(STDIN, "%s\n", $DM_num);
			$DM_num = strtoupper($DM_num);
			if($DM_num == "X"){
				return;
			}
			if(!S5_validity_check($DM_num)){
				fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**ORDER# CAN ONLY\nBE ALPHANUMERIC**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$DM_num = "";
			}
			$sql = "SELECT COUNT(DISTINCT LR_NUM) SHIPS, COUNT(DISTINCT COMMODITY_CODE) COMMS, COUNT(DISTINCT OWNER_ID) CUSTS
					FROM BNI_DUMMY_WITHDRAWAL
					WHERE D_DEL_NO = '".$DM_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nDummy Info\n(LT1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nDummy Info\n(LT1b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['SHIPS'] == 0 || $short_term_data_row['COMMS'] == 0 || $short_term_data_row['CUSTS'] == 0){
				fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**DM# ".$DM_num."\nDOES NOT EXIST**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$DM_num = "";
			} elseif($short_term_data_row['SHIPS'] != 1 || $short_term_data_row['COMMS'] != 1 || $short_term_data_row['CUSTS'] != 1){
				fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**DM# ".$DM_num."\nSHOWS INVALID\nCUST/COMM/LR#\nCONTACT OFFICE**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$DM_num = "";
			} else {
				// DM exists, get relevant data

				// HD 7916 - IS THERE DRUMS ON THIS ORDER?
				// if so, use QTY2 as the "counting variable" instead.
				// this is a hardcode, which isn't normally desired, but that was the instructions.
				$sql = "SELECT COUNT(*) THE_COUNT FROM BNI_DUMMY_WITHDRAWAL
						WHERE D_DEL_NO = '".$DM_num."' AND (UNIT2 = 'DRUM' OR UNIT2 = 'DR')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDummy Info\n(LT1-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDummy Info\n(LT1-2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] > 0){
					$sql_sum_by = "QTY2";
				} else {
					$sql_sum_by = "QTY1";
				}

				$sql = "SELECT LR_NUM, OWNER_ID, COMMODITY_CODE, SUM(".$sql_sum_by.") THE_SUM 
						FROM BNI_DUMMY_WITHDRAWAL
						WHERE D_DEL_NO = '".$DM_num."' GROUP BY D_DEL_NO, LR_NUM, OWNER_ID, COMMODITY_CODE";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDummy Info\n(LT2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDummy Info\n(LT2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_cust = $short_term_data_row['OWNER_ID'];
				$DM_comm = $short_term_data_row['COMMODITY_CODE'];
				$DM_LR = $short_term_data_row['LR_NUM'];
				$DM_ordered = $short_term_data_row['THE_SUM'];

				$sql = "SELECT SUBSTR(VESSEL_NAME, 0, 17) THE_SHIP FROM VESSEL_PROFILE WHERE LR_NUM = '".$DM_LR."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nLR Info\n(LT3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nLR Info\n(LT3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_LR_disp = $DM_LR."-".$short_term_data_row['THE_SHIP'];
							
				$sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 16) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$DM_cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCust Info\n(LT4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCust Info\n(LT4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_cust_disp = $short_term_data_row['THE_CUST'];
							
				$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 16) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$DM_comm."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nComm Info\n(LT5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nComm Info\n(LT5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_comm_disp = $DM_comm."-".$short_term_data_row['THE_COMM'];
							
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM CARGO_ACTIVITY 
						WHERE ORDER_NUM = '".$DM_num."' 
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_total_on_order = $short_term_data_row['THE_COUNT'];

				if($DM_total_on_order >= $DM_ordered){
					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.", "bad");
					echo $DM_num."\n";
					echo "LR#: ".$DM_LR_disp."\n";
					echo "Owner: ".$DM_cust_disp."\n";
					echo "Comm: ".$DM_comm_disp."\n";
					echo "Order Total:  ".$DM_ordered."\n";
					echo "Scanned Out:  ".$DM_total_on_order."\n\n";
					echo "**DM# ".$DM_num."\nORDER ALREADY FULL**\n";
//					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**DM# ".$DM_num."\nORDER ALREADY FULL**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$DM_num = "";
				}
			}			
		}

		fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.");
		echo $DM_num."\n";
		echo "LR#: ".$DM_LR_disp."\n";
		echo "Owner: ".$DM_cust_disp."\n";
		echo "Comm: ".$DM_comm_disp."\n";
		echo "Order Total:  ".$DM_ordered."\n";
		echo "Scanned Out:  ".$DM_total_on_order."\n";
		echo "Scan Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){
			$continue_pallet = true;
			$plt_Arrival = "";
			$plt_owner = "";
			$plt_comm = "";

			if($continue_pallet){
				// check if any pallets, exactly 1 pallet, or more than 1 pallet still in house with this barcode
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					// no pallets in house
					$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT9a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT9b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] == 0){
						// no pallet in DB
						fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nIN DATABASE.\n\nCONTACT INVENTORY**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					} else {
						// pallet already "out" somehow
						$sql = "SELECT ACTIVITY_NUM, NVL(ORDER_NUM, SUBSTR(ACTIVITY_DESCRIPTION, 21)) THE_DESC FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND SERVICE_CODE IN ('6') AND QTY_LEFT <= 0 ORDER BY ACTIVITY_NUM DESC";
//						echo $sql."\n";
//						fscanf(STDIN, "%s\n", $junk);
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT10a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT10b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nAVAILABLE.\n\nCONTACT INVENTORY\nFOR DETAILS.**", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$continue_pallet = false;
						} else {
							fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET ALREADY\nSHIPPED\nON ".$short_term_data_row['THE_DESC']."**\n\nPress Enter", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$continue_pallet = false;
						}
					}
				} elseif($short_term_data_row['THE_COUNT'] == 1){
					// one pallet in house
					$sql = "SELECT ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT11a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT11b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$plt_Arrival = $short_term_data_row['ARRIVAL_NUM'];
					$plt_owner = $short_term_data_row['RECEIVER_ID'];
					$plt_comm = $short_term_data_row['COMMODITY_CODE'];
				} else {
					// more than 1 in house, choose.
					$continue_pallet = Select_Duplicate_Pallet($Barcode, $plt_owner, $plt_Arrival, $plt_comm);
				}
			}

			if($continue_pallet && $DM_comm != $plt_comm){
				fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nComm: ".$plt_comm."\n**DOES NOT MATCH\nTHIS ORDER\n--DO NOT SHIP--**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
			if($continue_pallet && $DM_LR != $plt_Arrival){
				fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nLR#: ".$plt_Arrival."\n**DOES NOT MATCH\nTHIS ORDER\n--DO NOT SHIP--**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			if($continue_pallet){
				if(!validate_customer_to_scannertype($plt_owner, $scanner_type, "Load_Truck")){
					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nCust: ".$cust."\n**BARCODE NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// get other pallet data
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, QTY_IN_HOUSE, WAREHOUSE_LOCATION,
							QTY_RECEIVED, QTY_DAMAGED, BOL, CARGO_DESCRIPTION
						FROM CARGO_TRACKING CT
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$plt_owner."'
							AND ARRIVAL_NUM = '".$plt_Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT12a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT12b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_ship = $short_term_data_row['QTY_IN_HOUSE'];
				$date_rec = $short_term_data_row['THE_DATE'];
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
				$plt_bol = $short_term_data_row['BOL'];
				$plt_mark = $short_term_data_row['CARGO_DESCRIPTION'];
			}

			// figure out which line of the Dummy this pallet falls under, and if it is still needed
			//				AND BDW.OWNER_ID = CT.RECEIVER_ID
			if($continue_pallet){
				$sql = "SELECT BDW.BOL BOL, SUM(BDW.".$sql_sum_by.") QTY1, CT.CARGO_DESCRIPTION CTMARK
						FROM BNI_DUMMY_WITHDRAWAL BDW, CARGO_TRACKING CT
						WHERE BDW.LR_NUM = CT.ARRIVAL_NUM 
							AND BDW.COMMODITY_CODE = CT.COMMODITY_CODE
							AND INSTR(BDW.MARK, TRIM(CT.CARGO_DESCRIPTION)) > 0
							AND BDW.BOL = CT.BOL
							AND BDW.D_DEL_NO = '".$DM_num."'
							AND CT.PALLET_ID = '".$Barcode."'
						GROUP BY BDW.BOL, CT.CARGO_DESCRIPTION";
				$ora_success = ora_parse($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT13a)");
				$ora_success = ora_exec($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT13b)");
				if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**--DO NOT SHIP--\nBOL: ".$plt_bol."\nMrk: ".substr($plt_mark, 0, 20)."\nDOES NOT MATCH\nANY SINGLE ITEM IN\nDM# ".$DM_num."**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				} else {

					// pallet matched a line, but does that line still need items?
					$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT 
							FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
							WHERE CT.PALLET_ID = CA.PALLET_ID
								AND CA.ORDER_NUM = '".$DM_num."'
								AND CA.SERVICE_CODE = '6'
								AND CT.RECEIVER_ID = CA.CUSTOMER_ID
								AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
								AND ACTIVITY_DESCRIPTION IS NULL
								AND CT.BOL = '".$select_row['BOL']."'
								AND '".$select_row['CTMARK']."' = CT.CARGO_DESCRIPTION";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT14a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT14b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] >= $select_row['QTY1']){
						fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**--DO NOT SHIP--\nDM# ".$DM_num."\nALREADY FULL OF\nBOL: ".$plt_bol."\nMrk: ".substr($plt_mark, 0, 20)."**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
/*					} else {
						// ok, it does still need that item... is this a pallet of a different customer that is being autotransed?
						if($check_autotrans_flag){
							$sql = "SELECT COUNT(*) THE_COUNT 
									FROM ARGJUICE_TRANSFERS
									WHERE CUSTOMER_FROM = '".$plt_owner."'
										AND CUSTOMER_TO = '".$DM_cust."'
										AND COMMODITY_CODE = '".$DM_comm."'
										AND ARRIVAL_NUM = '".$DM_LR."'
										AND BOL = '".$select_row['BOL']."'
										AND '".$select_row['CTMARK']."' = CARGO_DESCRIPTION
										AND QTY_LEFT_TO_TRANS > 0";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(LT31a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(LT31b)");
							ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							if($short_term_data_row['THE_COUNT'] <= 0){
								// no autotrans available, give same error as above for a DM-plt customer mismatch
								fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n **QTY TO SHIP >\nQTY TRANSFERRED\nCALL OFFICE**", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$continue_pallet = false;
							}
						} */
					}
				}
			}

			// find out if this specific pallet needs an autotrans to be used
			$check_autotrans_flag = false;
			if($continue_pallet && $DM_cust != $plt_owner){
//				$path_to_final_cust = array();
//				$path_to_final_cust[0] = $plt_owner;

				$sql = "SELECT OWNER_ID  
						FROM BNI_CARGO_TRACKING CT, BNI_CARGO_MANIFEST CM
						WHERE CT.LOT_NUM = CM.CONTAINER_NUM
							AND CARGO_BOL = '".$plt_bol."'
							AND CARGO_MARK LIKE TRIM('".$plt_mark."') || '%'
							AND LR_NUM = '".$plt_Arrival."'
							AND CM.COMMODITY_CODE = '".$plt_comm."'";
				$ora_success = ora_parse($bni_autotrans_check_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT18a)");
				$ora_success = ora_exec($bni_autotrans_check_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT18b)");
				if(!ora_fetch_into($bni_autotrans_check_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ 
//				$path_to_final_cust = get_path_of_valid_transfers($Barcode, $DM_cust, $plt_owner, $DM_comm, $DM_LR, $plt_bol, $path_to_final_cust);
//				if($short_term_data_row['THE_COUNT'] > 0){
//				if($path_to_final_cust !== false){
					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\nCust: ".$plt_owner."\n**DOES NOT MATCH\nTHIS ORDER\n--DO NOT SHIP--**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				} else {
					$check_autotrans_flag = true;
				}
			}

			// display pallet info, change qty_to_ship if desired
			if($continue_pallet){
				$act_num = get_max_activity_num($plt_owner, $Barcode, $plt_Arrival);
				$sub_choice = "default";
				while($sub_choice != "X" && $sub_choice != "" && !is_numeric($sub_choice)){
					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n");
					echo $Barcode."\n";
//					echo "Cust: ".$custname."\n";
//					echo "Comm: ".$commname."\n";
					echo "LR: ".$DM_LR_disp."\n";
//					echo "Vesl: ".$vesname."\n";
//					echo "Loc: ".$loc."\n";
//					echo "Rcvd: ".$qty_rec."  Dmg: ".$qty_dmg."\n";
//					echo "Variety: ".$variety."\n";
					echo "BOL: ".$plt_bol."\n";
					echo $plt_mark."\n\n";
					echo "# Of Rebands:\n(Blank=0)\n";
//					echo "C = Change QTY\nEnter=OK X=Exit\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if($sub_choice == ""){
						$reband_val = 0;
					} elseif($sub_choice == "X"){
						$continue_pallet = false;
					} else {
						$reband_val = $sub_choice;
					}
				}
			}

			// make sure no one *else* has been screwing with this pallet while we were waiting for a response...
			if($continue_pallet){
				if(!is_max_activity_num($act_num, $plt_owner, $Barcode, $plt_Arrival)){
					fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.]nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// at this point, we commit the pallet to order.
				if($date_rec == "NONE"){
					Auto_Receive_Pallet($emp_no, $Barcode, $plt_owner, $plt_Arrival);
				}
				if($check_autotrans_flag){
					$plt_owner = Auto_Trans_Pallet($emp_no, $Barcode, $plt_owner, $DM_cust, $plt_Arrival);
//					$plt_owner = Auto_Trans_Pallet($emp_no, $Barcode, $plt_Arrival, $path_to_final_cust);
					$act_num = get_max_activity_num($plt_owner, $Barcode, $plt_Arrival);
				}

				$sql = "UPDATE CARGO_TRACKING
						SET QTY_IN_HOUSE = 0
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$plt_owner."'
							AND ARRIVAL_NUM = '".$plt_Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT15a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT15b)");

				$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
						SET JUICE_REBANDS = '".$reband_val."'
						WHERE PALLET_ID = '".$Barcode."'
							AND RECEIVER_ID = '".$plt_owner."'
							AND ARRIVAL_NUM = '".$plt_Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT15a-a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT15b-a)");
/*
				$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$plt_owner."' AND ARRIVAL_NUM = '".$plt_Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT16a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT16b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
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
							'6',
							'1',
							'".$emp_no."',
							'".$DM_num."',
							'".$plt_owner."',
							SYSDATE,
							'".$Barcode."',
							'".$plt_Arrival."',
							'0')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT17a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(LT17b)");

				ora_commit($rf_conn);
//				$DM_total_on_order++;

				fresh_screen("ARGEN JUICE\nLoad Truck\nEnter X to Exit\n\nBC: ".$Barcode."\n\nAccepted On Order\n(Press Enter)");
				fscanf(STDIN, "%s\n", $junk);
			}


			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_ACTIVITY 
					WHERE ORDER_NUM = '".$DM_num."' 
						AND SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$DM_total_on_order = $short_term_data_row['THE_COUNT'];

			fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.");
			echo $DM_num."\n";
			echo "LR#: ".$DM_LR_disp."\n";
			echo "Own: ".$DM_cust_disp."\n";
			echo "Com: ".$DM_comm_disp."\n";
			echo "Order Total:  ".$DM_ordered."\n";
			echo "Scanned Out:  ".$DM_total_on_order."\n";

			if($DM_total_on_order >= $DM_ordered){
				echo "Order Filled.";
				fscanf(STDIN, "%s\n", $junk);
				$Barcode = "X";
			} else {
				echo "Scan Barcode:\n";
				$Barcode = "";
				fscanf(STDIN, "%s\n", $Barcode);
				$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
			}
		}
	}
}

function Unload_Ship($CID) {
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$Arrival = "XXX";

	// get and validate ship #
	while($Arrival == "XXX" || $Arrival == "Invalid"){
		fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.");
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
		} else {
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

	$count = 0;

	fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.");
	echo "Count: ".$count."\n";
	echo "LR Num: ".$Arrival."\n";
	echo "Scan Barcode:\n";
	$Barcode = "";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while($Barcode != "X"){
		$Cust = "";
		$Comm = "";
		$continue_pallet = true;

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(US1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(US1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			if(substr($Barcode, 0, 3) != "POW"){
				// pallet does not exist, and is not relabel
				fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			} else {
				// this is a relabelled (read:  poorly labelled) pallet.
				Relabel($emp_no, $Barcode, $Arrival, "Unload_Ship");
				fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**POW-RELABEL\nACCEPTED.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false; // relabel function takes care of it, no sense continuing here
			}
		} elseif($short_term_data_row['THE_COUNT'] > 1){
			// multiple pallet
			// PLEASE NOTE:  qty_in_house
			$continue_pallet = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Comm);
		} else {
			// single pallet, get info
			$sql = "SELECT RECEIVER_ID, COMMODITY_CODE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(US2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(US2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Cust = $short_term_data_row['RECEIVER_ID'];
			$Comm = $short_term_data_row['COMMODITY_CODE'];
		}

		if($continue_pallet){
			if(!validate_customer_to_scannertype($Cust, $scanner_type, "Unload_Ship")){
				fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
		}

		if($continue_pallet){
			// get additional pallet data
			$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') DATE_REC, VESSEL_NAME, CUSTOMER_NAME, 
						COMMODITY_NAME, CARGO_DESCRIPTION, WAREHOUSE_LOCATION, BATCH_ID, BOL
					FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
					WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
						AND CT.PALLET_ID = '".$Barcode."'
						AND CT.RECEIVER_ID = '".$Cust."'
						AND CT.ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(US4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(US4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$date_rec = $short_term_data_row['DATE_REC'];
			$ves_name = substr($short_term_data_row['VESSEL_NAME'], 0, 13);
			$cust_name = substr($short_term_data_row['CUSTOMER_NAME'], 0, 18);
			$comm_name = substr($short_term_data_row['COMMODITY_NAME'], 0, 18);
			$mark = substr($short_term_data_row['CARGO_DESCRIPTION'], 0, 18);
			$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
			$code = $short_term_data_row['BATCH_ID'];
			$bol = $short_term_data_row['BOL'];
		}

		if($continue_pallet){
			// was it already received?
			if($date_rec != ""){
				fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.\nBC: ".$Barcode."\n**ALREADY RECEIVED**", "bad");
				echo "Cust: ".$cust_name."\n";
				echo "Comm: ".$comm_name."\n";
				echo "Loc: ".$loc."\n";
				echo "MK: ".$mark."\n";
				echo "Code: ".$code."\n";
				echo "BoL: ".$bol."\n";
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
		}

		if($continue_pallet){
			// display data and ask for strapcount
			$sub_choice = "default";
//			while($sub_choice != "X" && $sub_choice != "" && !is_numeric($sub_choice)){
			while($sub_choice != "X" && !is_numeric($sub_choice)){
				system("clear");
				echo $Barcode."\n";
				echo "Cust: ".$cust_name."\n";
				echo "LR#: ".$Arrival."-".$ves_name."\n";
				echo "Comm: ".$comm_name."\n";
				echo "MK: ".$mark."\n";
				echo "Loc: ".$loc."\n";
				echo "Code: ".$code."\n\n";
				echo "Enter Strap Count\n(Must Enter\nTo Commit)\n(X=Exit):\n";
				$sub_choice = "";
				fscanf(STDIN, "%s\n", $sub_choice);
				$sub_choice = strtoupper($sub_choice);
				if($sub_choice == "X"){
					$continue_pallet = false;
				} elseif($sub_choice == "") {
					fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.");
					echo "A number must\nBe Entered.";
					fscanf(STDIN, "%s\n", $junk);
					$sub_choice = "default";
				} elseif(is_numeric($sub_choice) && ($sub_choice < 0 || $sub_choice != round($sub_choice))){
					fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.");
					echo "Entered Strapcount\n".$sub_choice."\nIs Not Valid.";
					fscanf(STDIN, "%s\n", $junk);
					$sub_choice = "default";
				} else {
					$strap_val = $sub_choice;
				}
			}
		}

		if($continue_pallet){
			// yep, safe to receive.
			$sql = "UPDATE CARGO_TRACKING
					SET DATE_RECEIVED = SYSDATE,
						QTY_RECEIVED = 1,
						QTY_IN_HOUSE = 1
					WHERE PALLET_ID = '".$Barcode."'
						AND RECEIVER_ID = '".$Cust."'
						AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nReceive Pallet\n(US5a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nReceive Pallet\n(US5b)");
						
			$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
					SET JUICE_STRAPCOUNT = '".$strap_val."'
					WHERE PALLET_ID = '".$Barcode."'
						AND RECEIVER_ID = '".$Cust."'
						AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nReceive Pallet\n(US6a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nReceive Pallet\n(US6b)");
						
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
					'1',
					'".$emp_no."',
					'".$Cust."',
					SYSDATE,
					'".$Barcode."',
					'".$Arrival."',
					'1')"; 
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nReceive Pallet\n(US7a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nReceive Pallet\n(US7b)");
		
			ora_commit($rf_conn);
			$count++;
			fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to Exit\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\n\nRECEIVED!");
			fscanf(STDIN, "%s\n", $junk);
		}

		fresh_screen("ARGEN JUICE\nUnload Ship\nEnter X to exit.");
		echo "Count: ".$count."\n";
		echo "LR Num: ".$Arrival."\n";
		echo "Scan Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function Info_Pallet(){
	global $rf_conn;
	global $scanner_type;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);



	fresh_screen("ARGEN JUICE\nPallet Info\nEnter X to exit.");
	echo "Barcode:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){

		$total_pallets = 0;
		$pallet_info = array();
		$curr_pallet = 0;
		$continue = true;

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT RECEIVED YET') DATE_REC, 
				NVL(SUBSTR(VESSEL_NAME, 0, 19), 'TRUCK') THE_VES, CT.ARRIVAL_NUM THE_LR,
				COMP.COMMODITY_CODE || '-' || SUBSTR(COMMODITY_NAME, 0, 15) THE_COMM, SUBSTR(CUSTOMER_NAME, 0, 15) THE_CUST,
				BOL, CARGO_DESCRIPTION, CT.RECEIVER_ID, WAREHOUSE_LOCATION, BATCH_ID
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
			fresh_screen("ARGEN JUICE\nPallet Info\nEnter X to exit.\n\nNo Pallet with BC\n".$Barcode."\nIn System.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			// at least 1 pallet here
			// put all matching barcodes in an array
			do {
				$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_ACT 
						FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' 
							AND CUSTOMER_ID = '".$select_row['RECEIVER_ID']."' 
							AND ARRIVAL_NUM = '".$select_row['THE_LR']."' 
							AND (SERVICE_CODE = '6' OR (SERVICE_CODE = '11' AND ACTIVITY_NUM > 1)) 
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
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

				$pallet_info[$total_pallets]["cust"] = $select_row['THE_CUST'];
				$pallet_info[$total_pallets]["LR"] = $select_row['THE_VES'];
				$pallet_info[$total_pallets]["LRnum"] = $select_row['THE_LR'];
				$pallet_info[$total_pallets]["comm"] = $select_row['THE_COMM'];
				$pallet_info[$total_pallets]["bol"] = $select_row['BOL'];
				$pallet_info[$total_pallets]["lot"] = $select_row['CARGO_DESCRIPTION'];
				$pallet_info[$total_pallets]["loc"] = $select_row['WAREHOUSE_LOCATION'];
				$pallet_info[$total_pallets]["code"] = $select_row['BATCH_ID'];
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
//				$pallet_info[$total_pallets]["i_h"] = $select_row['QTY_IN_HOUSE'];

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
				echo $Barcode."\n";
				echo $pallet_info[$display_set_counter]["LRnum"]."-".$pallet_info[$display_set_counter]["LR"]."\n";
				echo $pallet_info[$display_set_counter]["date_rec"]."\n";

				if($pallet_info[$display_set_counter]["date_rec"] == "NOT RECEIVED YET") {
					echo "\n";
				} elseif($pallet_info[$display_set_counter]["order_out"] != "STILL IN HOUSE"){
					echo "ORD#: ".$pallet_info[$display_set_counter]["order_out"]."\n";
				} else {
					echo $pallet_info[$display_set_counter]["order_out"]."\n";
				}

				echo $pallet_info[$display_set_counter]["date_act"]."\n";
				echo $pallet_info[$display_set_counter]["cust"]."\n";
				echo $pallet_info[$display_set_counter]["comm"]."\n";
/*				echo "RV:".$pallet_info[$display_set_counter]["qty_rec"]." Dm:".$pallet_info[$display_set_counter]["dmg"]." In:".$pallet_info[$display_set_counter]["i_h"]."\n";
				echo "Loc: ".$pallet_info[$display_set_counter]["loc"]."\n";
				echo "Var: ".$pallet_info[$display_set_counter]["var"]."\n";
				echo "Size: ".$pallet_info[$display_set_counter]["size"]."  Chep: ".$pallet_info[$display_set_counter]["chep"]."\n";
*/
				echo "BoL: ".$pallet_info[$display_set_counter]["bol"]." Loc:".$pallet_info[$display_set_counter]["loc"]."\n";
				echo "Code: ".$pallet_info[$display_set_counter]["code"]."\n";
				echo substr($pallet_info[$display_set_counter]["lot"], 0, 22)."\n";

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
			fresh_screen("ARGEN JUICE\nPallet Info\nEnter X to exit.");
			echo "Barcode:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		} else{
			$Barcode = "X";
		}
	}
}

function Info_Order(){
	global $rf_conn;
	global $scanner_type;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);



	fresh_screen("ARGEN JUICE\nOrder Info\nEnter X to exit.");
	echo "DM#:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){

		$total_pallets = 0;
		$pallet_info = array();
		$curr_pallet = 0;
		$continue = true;

		$sql = "SELECT PALLET_ID
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = '".$Barcode."'
					AND SERVICE_CODE = '6'
					AND ACTIVITY_DESCRIPTION IS NULL
				ORDER BY PALLET_ID";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nInfo\n(IO1a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nInfo\n(IO1b)");
		if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fresh_screen("ARGEN JUICE\nOrder Info\nEnter X to exit.\n\nNo Order\n".$Barcode."\nIn System.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			do {
				$pallet_info[$total_pallets] = $select_row['PALLET_ID'];
				$total_pallets++;
			} while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			if($total_pallets > 12){
				fresh_screen("Multiple Pages.\nPress \"Enter\"\nto continue.", "bad");
				fscanf(STDIN, "%s\n", $junk);
			}

			$choice = "there is no spoon";
			$display_set_counter = 0; 
			$max_page = ceil($total_pallets / 11);

			// display pallet
			while($choice != "X" && $choice != "R"){
				system("clear");
				echo $Barcode."\n\n";
				for($i = 0; $i < 11; $i++){
					echo " ".$pallet_info[(($display_set_counter * 12) + $i)]."\n";
				}

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

				echo "X-Exit    R-New Ord\n";
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
			fresh_screen("ARGEN JUICE\nOrder Info\nEnter X to exit.");
			echo "DM#:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		} else{
			$Barcode = "X";
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

	$cust = "";
	$order_num = "";

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 
		// get and validate cust #
		$DM_cust = "";
		$DM_num = "";
		$DM_comm = "";
		$DM_LR = "";
		$DM_ordered = "";
		$DM_total_on_order = "";

		$DM_cust_disp = "";
		$DM_comm_disp = "";
		$DM_LR_disp = "";

		while($DM_num == ""){
			fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.");
			echo "DM#:\n";
			fscanf(STDIN, "%s\n", $DM_num);
			$DM_num = strtoupper($DM_num);
			if($DM_num == "X"){
				return;
			}
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM BNI_DUMMY_WITHDRAWAL
					WHERE D_DEL_NO = '".$DM_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nDummy Info\n(VO1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nDummy Info\n(VO1b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("ARGEN JUICE\nTruck Out\nEnter X to exit.\n\n**DM# ".$DM_num."\nDOES NOT EXIST**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$DM_num = "";
			} else {
				// DM exists, get relevant data
				$sql = "SELECT LR_NUM, OWNER_ID, COMMODITY_CODE, SUM(QTY1) THE_SUM 
						FROM BNI_DUMMY_WITHDRAWAL
						WHERE D_DEL_NO = '".$DM_num."' GROUP BY LR_NUM, OWNER_ID, COMMODITY_CODE";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDummy Info\n(VO2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDummy Info\n(VO2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_cust = $short_term_data_row['OWNER_ID'];
				$DM_comm = $short_term_data_row['COMMODITY_CODE'];
				$DM_LR = $short_term_data_row['LR_NUM'];
				$DM_ordered = $short_term_data_row['THE_SUM'];

				$sql = "SELECT SUBSTR(VESSEL_NAME, 0, 17) THE_SHIP FROM VESSEL_PROFILE WHERE LR_NUM = '".$DM_LR."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nLR Info\n(VO3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nLR Info\n(VO3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_LR_disp = $DM_LR."-".$short_term_data_row['THE_SHIP'];
							
				$sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 16) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$DM_cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCust Info\n(VO4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCust Info\n(VO4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_cust_disp = $short_term_data_row['THE_CUST'];
							
				$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 16) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$DM_comm."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nComm Info\n(VO5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nComm Info\n(VO5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_comm_disp = $DM_comm."-".$short_term_data_row['THE_COMM'];
							
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM CARGO_ACTIVITY 
						WHERE ORDER_NUM = '".$DM_num."' 
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VO6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VO6b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$DM_total_on_order = $short_term_data_row['THE_COUNT'];
			}
		}

		fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.");
		echo "LR#: ".$DM_LR_disp."\n";
		echo "Own: ".$DM_cust_disp."\n";
		echo "Com: ".$DM_comm_disp."\n";
		echo "Order Total:  ".$DM_ordered."\n";
		echo "Scanned Out:  ".$DM_total_on_order."\n";
		echo "Scan Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){
			$continue_pallet = true;

			$Arrival = "";
			$Commodity = "";

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout7b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
/*
			if($continue_pallet){
				// check if this pallet belongs to the customer this (voiding) order is for
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$DM_cust."' AND ARRIVAL_NUM = '".$DM_LR."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout23a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout23b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nBELONG TO ".$DM_cust."\nCHECK PALLET INFO**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}
*/
			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$DM_num."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '".$DM_cust."' AND ARRIVAL_NUM = '".$DM_LR."' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout8b)");
//				echo $sql."\n";
//				fscanf(STDIN, "%s\n", $junk);
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// get additional pallet data
				$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') DATE_REC, VESSEL_NAME, CUSTOMER_NAME, 
							COMMODITY_NAME, CARGO_DESCRIPTION, BOL
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
						WHERE CT.COMMODITY_CODE = COMP.COMMODITY_CODE
							AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
							AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
							AND CT.PALLET_ID = '".$Barcode."'
							AND CT.RECEIVER_ID = '".$DM_cust."'
							AND CT.ARRIVAL_NUM = '".$DM_LR."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout9a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout9b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$date_rec = $short_term_data_row['DATE_REC'];
				$ves_name = substr($short_term_data_row['VESSEL_NAME'], 0, 13);
				$cust_name = substr($short_term_data_row['CUSTOMER_NAME'], 0, 18);
				$comm_name = substr($short_term_data_row['COMMODITY_NAME'], 0, 18);
				$lot = substr($short_term_data_row['CARGO_DESCRIPTION'], 0, 24);
				$bol = $short_term_data_row['BOL'];
//				$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
//				$code = $short_term_data_row['BATCH_ID'];
			}

			// display pallet info, change qty_to_ship if desired
			if($continue_pallet){
				$act_num = get_max_activity_num($DM_cust, $Barcode, $DM_LR);
				$sub_choice = "default";
				while($sub_choice != "X" && $sub_choice != "N" && $sub_choice != "Y"){
					fresh_screen("ARGEN JUICE\nVoid Out\nEnter X to exit.\n\n");
					echo $Barcode."\n";
					echo $lot."\n";
					echo $bol."\n\n"; 
					echo "Void? (Y/N)\n";

					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if($sub_choice == "Y"){
						$continue_pallet = true; // redundant, but easier to read in program :)
					} elseif($sub_choice == "X" || $sub_choice == "N"){
						$continue_pallet = false;
					} 
				}
			}

			// make sure no OTHER scanner has screwed with this pallet in the meantime
			if($continue_pallet){
				if(!is_max_activity_num($act_num, $DM_cust, $Barcode, $DM_LR)){
					fresh_screen("ARGEN JUICE\nVoid Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.]nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				// alright, let's void this sucker!
/*				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
							AND CUSTOMER_ID = '".$DM_cust."'
							AND ARRIVAL_NUM = '".$DM_LR."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout10a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout10b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID'
						WHERE PALLET_ID = '".$Barcode."'
							AND CUSTOMER_ID = '".$DM_cust."'
							AND ARRIVAL_NUM = '".$DM_LR."'
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL
							AND ORDER_NUM = '".$DM_num."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout11a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout11b)");

				// add CA record of void
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
							'".$DM_num."',
							'".$DM_cust."',
							SYSDATE,
							'".$Barcode."',
							'".$DM_LR."',
							'1')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout12a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout12b)");

				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 1 
						WHERE PALLET_ID = '".$Barcode."' 
						AND RECEIVER_ID = '".$DM_cust."' 
						AND ARRIVAL_NUM = '".$DM_LR."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout13a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout13b)");

				// do an extra check to see if this bin was shipped out on an autotransfer:
				$sql = "SELECT * FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
							AND CUSTOMER_ID = '".$DM_cust."'
							AND ARRIVAL_NUM = '".$DM_LR."'
							AND ACTIVITY_NUM = '".($act_num - 1)."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout14a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout14b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['SERVICE_CODE'] == 11 && stristr($short_term_data_row['ORDER_NUM'], 'VOID') === false) {
					Void_Autotransfer($Barcode, $DM_cust, $DM_LR, ($act_num - 1), $bol, $lot);
				}

				ora_commit($rf_conn);
				fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.");
				echo $Barcode."\n VOIDED FROM ORDER\n\n";
				fscanf(STDIN, "%s\n", $junk);
			}

			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_ACTIVITY 
					WHERE ORDER_NUM = '".$DM_num."' 
						AND SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VO14a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VO14b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$DM_total_on_order = $short_term_data_row['THE_COUNT'];

			fresh_screen("ARGEN JUICE\nVoid Outbound\nEnter X to exit.");
			echo "LR#: ".$DM_LR_disp."\n";
			echo "Own: ".$DM_cust_disp."\n";
			echo "Com: ".$DM_comm_disp."\n";
			echo "Order Total:  ".$DM_ordered."\n";
			echo "Scanned Out:  ".$DM_total_on_order."\n";
			echo "Scan Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function Precheck_Dummy(){
	global $rf_conn;
	global $scanner_type;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$continue = true;

	while($continue){
		fresh_screen("ARGEN JUICE\nPreCheck Dummy\nEnter X to exit.");
		echo "DM#:\n";
		fscanf(STDIN, "%s\n", $DM_num);
		$DM_num = strtoupper($DM_num);
		if($DM_num != "X"){

			$total_lines = 0;
			$DM_info = array();
			$curr_line = 0;
			$continue = true;

			$sql = "SELECT BDW.LR_NUM, OWNER_ID, BDW.COMMODITY_CODE,
						NVL(SUBSTR(VESSEL_NAME, 0, 19), 'TRUCK') THE_VES, 
						SUBSTR(COMMODITY_NAME, 0, 15) THE_COMM, 
						SUBSTR(CUSTOMER_NAME, 0, 15) THE_CUST, 
						SUM(QTY1) THE_SUM, BOL,
						DECODE(INSTR(MARK, 'TR*'), 0, MARK, SUBSTR(MARK, 1, LENGTH(MARK) - 5)) THE_MARK
					FROM BNI_DUMMY_WITHDRAWAL BDW, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
					WHERE D_DEL_NO = '".$DM_num."'
						AND BDW.LR_NUM = VP.LR_NUM
						AND BDW.COMMODITY_CODE = COMP.COMMODITY_CODE
						AND BDW.OWNER_ID = CUSP.CUSTOMER_ID
					GROUP BY D_DEL_NO, BDW.LR_NUM, OWNER_ID, BDW.COMMODITY_CODE, BOL,
						NVL(SUBSTR(VESSEL_NAME, 0, 19), 'TRUCK'),
						SUBSTR(COMMODITY_NAME, 0, 15),
						SUBSTR(CUSTOMER_NAME, 0, 15),
						DECODE(INSTR(MARK, 'TR*'), 0, MARK, SUBSTR(MARK, 1, LENGTH(MARK) - 5))";
//			echo $sql."\n";
//			fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nInfo\n(PcD1a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nInfo\n(PcD1b)");
			if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("ARGEN JUICE\nPallet Info\nEnter X to exit.\n\nNo Pallet with BC\n".$Barcode."\nIn System.", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} else {
				do {
					$DM_info[$total_lines]["arv"] = $select_row['LR_NUM']."-".$select_row['THE_VES'];
					$DM_info[$total_lines]["cust"] = $select_row['THE_CUST'];
					$DM_info[$total_lines]["comm"] = $select_row['COMMODITY_CODE']."-".$select_row['THE_COMM'];
					$DM_info[$total_lines]["bol"] = $select_row['BOL'];
					$DM_info[$total_lines]["lot"] = $select_row['THE_MARK'];
					$DM_info[$total_lines]["ordered"] = $select_row['THE_SUM'];

					$sql = "SELECT COUNT(*) THE_COUNT 
							FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT 
							WHERE ORDER_NUM = '".$DM_num."' 
								AND SERVICE_CODE = '6' 
								AND ACTIVITY_DESCRIPTION IS NULL
								AND CA.PALLET_ID = CT.PALLET_ID
								AND CA.CUSTOMER_ID = CT.RECEIVER_ID
								AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
								AND CT.BOL = '".$select_row['BOL']."' 
								AND INSTR('".$select_row['THE_SUM']."', CT.CARGO_DESCRIPTION) > 0";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOrder Info\n(PcD2a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOrder Info\n(PcD2b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$DM_info[$total_lines]["scanned"] = $short_term_data_row['THE_COUNT'];

					$sql = "SELECT COUNT(*) AVAILABLE_PALLET_COUNT 
							FROM BNI_DUMMY_WITHDRAWAL BDW, CARGO_TRACKING CT 
							WHERE BDW.D_DEL_NO = '".$DM_num."' 
								AND TO_CHAR(BDW.LR_NUM) = CT.ARRIVAL_NUM 
								AND BDW.COMMODITY_CODE = CT.COMMODITY_CODE 
								AND BDW.BOL = CT.BOL 
								AND INSTR(BDW.MARK, CT.CARGO_DESCRIPTION) > 0
								AND BDW.BOL = '".$select_row['BOL']."'
								AND INSTR(BDW.MARK, '".$select_row['THE_MARK']."') > 0
								AND CT.QTY_IN_HOUSE > 0";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOrder Info\n(PcD3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOrder Info\n(PcD3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$DM_info[$total_lines]["avail"] = $short_term_data_row['AVAILABLE_PALLET_COUNT'];

					$total_lines++;
				} while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$choice = "there is no spoon";
				$display_set_counter = 0; 
				$max_page = $total_lines;

				// display pallet
				while($choice != "X" && $choice != "R"){
					// DO NOT USE FRESHSCREEN FUNCTION HERE
					// we need all the screen space we can get.
					system("clear");
//					echo $DM_num." ".$choice."\n";
					echo $DM_num."\n";
					echo $DM_info[$display_set_counter]["arv"]."\n";
					echo $DM_info[$display_set_counter]["cust"]."\n";
					echo $DM_info[$display_set_counter]["comm"]."\n\n";
					echo "BOL: ".$DM_info[$display_set_counter]["bol"]."\n";
					echo $DM_info[$display_set_counter]["lot"]."\n";
					echo "Ordered: ".$DM_info[$display_set_counter]["ordered"]."\n";
					echo "Scanned: ".$DM_info[$display_set_counter]["scanned"]."\n";
					echo "Available: ".$DM_info[$display_set_counter]["avail"]."\n\n";

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

					echo "X-Exit    R-New DM\n";
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
		} else {
			$continue = false;
		}
	}
}


function NoCheckScan($CID){
	global $rf_conn;
//	global $bni_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$running_count = 0;

	fresh_screen("ARGEN JUICE\nCargo List Scan\nEnter X to exit.");
	echo "Scan Barcode:\n";
	$Barcode = "";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while($Barcode != "X"){
		$sql = "INSERT INTO NOCHECKSCAN_TABLE
					(PALLET_ID,
					SCAN_TIME,
					CHECKERID)
				VALUES
					('".$Barcode."',
					SYSDATE,
					'".$CID."')";
		$ora_success = ora_parse($modify_cursor, $sql);
		database_check($ora_success, "Unable to \nInsert Scan\n(NCS1a)");
		$ora_success = ora_exec($modify_cursor, $sql);
		database_check($ora_success, "Unable to \nInsert Scan\n(NCS1b)");

		ora_commit($rf_conn);
		$running_count++;

		fresh_screen("ARGEN JUICE\nCargo List Scan\nEnter X to exit.");
		echo "Scanned this Session:\n  ".$running_count." Barcodes\n\n";
		echo "Scan Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}

}





/*******************************************************************************
* AUXILIARY FUNCTIONS START HERE ***********************************************
********************************************************************************/

function get_path_of_valid_transfers($Barcode, $DM_cust, $current_iteration_owner, $DM_comm, $DM_LR, $plt_bol, $path_to_destination){
/* 
*	This function is used to populate array "$path_to_destination" with the list of customers
*	That a pallet needs to get transferred through.  
*
****************************************************************************************************/
	global $rf_conn;
	global $scanner_type;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

// CHECK FOR FAIL-BASE CASE:
// if the current iteration's "path to destination" includes a duplicate customer# in it's list, then this
// recursive iteration has hit a loop.  Break out to prevent hangup.
// logic is:  SEARCH ARRAY for the last value in said array.  If returned index != last index of array, there's a duplicate.  
	if(array_search($path_to_destination[sizeof($path_to_destination) - 1], $path_to_destination) != sizeof($path_to_destination) - 1){
		return false;
	}

// CHECK FOR SUCCESS-BASE CASE:
// if there exists an available pending transfer from the current customer straight to the
// destination order's customer, take it and finish.
	$sql = "SELECT COUNT(*) AVAILABLE_COUNT 
			FROM ARGJUICE_TRANSFERS
			WHERE CUSTOMER_FROM = '".$current_iteration_owner."'
				AND CUSTOMER_TO = '".$DM_cust."'
				AND COMMODITY_CODE = '".$DM_comm."'
				AND ARRIVAL_NUM = '".$DM_LR."'
				AND BOL = '".$plt_bol."'
				AND QTY_LEFT_TO_TRANS > 0";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nTransfer Info\n(gPoVT1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nTransfer Info\n(gPoVT1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_data_row['AVAILABLE_COUNT'] > 0){
		// bingo, base case hit.  return success.
		$path_to_destination[sizeof($path_to_destination)] = $DM_cust;
		return $path_to_destination;
	}

// ok, base case didnt work.  next up is to see if there are any recursive steps to try

	$sql = "SELECT CUSTOMER_TO 
			FROM ARGJUICE_TRANSFERS
			WHERE CUSTOMER_FROM = '".$current_iteration_owner."'
				AND CUSTOMER_TO != '".$DM_cust."'
				AND COMMODITY_CODE = '".$DM_comm."'
				AND ARRIVAL_NUM = '".$DM_LR."'
				AND BOL = '".$plt_bol."'
				AND QTY_LEFT_TO_TRANS > 0";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nTransfer Info\n(gPoVT1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nTransfer Info\n(gPoVT1b)");
	while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$path_to_destination[sizeof($path_to_destination)] = $short_term_data_row['CUSTOMER_TO'];
		get_path_of_valid_transfers($Barcode, $DM_cust, $short_term_data_row['CUSTOMER_TO'], $DM_comm, $DM_LR, $plt_bol, $path_to_destination);
	}

// final exit case:
// if we're here, that means there are no infintie loops, no correct current path, and no more paths to try.
// exit function.
	return false;
}



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
* WILL have values when this exists.  Unless the function is cancelled.
*
* return value is FALSE if this function is cancelled out of, TRUE otherwise.
****************************************************************************************************/

	global $rf_conn;
	global $scanner_type;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$pallet_number = 0;
	$pallet_info = array();
	// if we get to this point, we have duplicates.  Prompt user for choice of which he wants.
	// restart SQL to do this (makes this function easier to follow)
	$sql = "SELECT COMMODITY_NAME, VESSEL_NAME, CT.COMMODITY_CODE, CT.ARRIVAL_NUM, RECEIVER_ID, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, VESSEL_PROFILE VP WHERE PALLET_ID = '".$Barcode."' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
	
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
		$pallet_info[$pallet_number]["dmg"] = $short_term_data_row['QTY_DAMAGED'];
		$pallet_info[$pallet_number]["IH"] = $short_term_data_row['QTY_IN_HOUSE'];
//		$pallet_info[$pallet_number]["var"] = $short_term_data_row['VARIETY'];
//		$pallet_info[$pallet_number]["size"] = $short_term_data_row['CARGO_SIZE'];
//		$pallet_info[$pallet_number]["chep"] = $short_term_data_row['CHEP'];

		$pallet_number++;
	}

	$choice = "there is no spoon";
	$display_set_counter = 0; 
	$max_page = $pallet_number;

	fresh_screen("ARGEN JUICE\nSelect Duplicate\n\nBC: ".$Barcode."\nDuplicate Pallets\nFound.  Please\nSelect:", "bad");
	echo "(Press Enter)\n";
	fscanf(STDIN, "%s\n", $junk);
	// display pallet
	while(true){
		// infinite loop avoided by escape clauses within

		// DO NOT USE FRESHSCREEN FUNCTION HERE
		// we need all the screen space we can get.
		system("clear");
		echo $Barcode."\n";
		echo "Cust: ".$pallet_info[$display_set_counter]["cust"]."\n";
		echo "LR#: ".$pallet_info[$display_set_counter]["LR"]."\n";
		echo substr($pallet_info[$display_set_counter]["vesname"], 0, 19)."\n";
		echo "Comm: ".$pallet_info[$display_set_counter]["commname"]."\n";
		echo "QTY Rcvd: ".$pallet_info[$display_set_counter]["rec"]."\n";
		echo "Dmg: ".$pallet_info[$display_set_counter]["dmg"]."  Inhs: ".$pallet_info[$display_set_counter]["IH"]."\n";
		echo "Loc: ".$pallet_info[$display_set_counter]["loc"]."\n";
//		echo "Var: ".$pallet_info[$display_set_counter]["var"]."\n";
//		echo "Size: ".$pallet_info[$display_set_counter]["size"]."\n";
//		echo "Chep: ".$pallet_info[$display_set_counter]["chep"]."\n";

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

function Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival){
/* 
*
* Auto-receives a pallet.  Unlike the main receive function (unload ship), 
* which can redefine certain pallet characteristics, this assumes default (already present) values
* and is only used by someone using an "activity" function on an unreceived pallet.
*
* This function MUST have an UNRECEIVED pallet passed to it, lest it go nuts.
*****************************************************************************************************/

	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "UPDATE CARGO_TRACKING
			SET DATE_RECEIVED = SYSDATE - (1/24),
				QTY_IN_HOUSE = '1',
				QTY_IN_STORAGE = '0'
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$Arrival."'
				AND RECEIVER_ID = '".$Cust."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP1a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP1b)");

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
			'1',
			'".$emp_no."',
			'".$Cust."',
			(SYSDATE - (1 / 24)),
			'".$Barcode."',
			'".$Arrival."',
			'1')";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ARP2a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ARP2b)");

	ora_commit($rf_conn);
}

function Auto_Trans_Pallet($emp_no, $Barcode, $cust_from, $cust_to, $Arrival){
/*
*	Transfers a pallet at time of DM-scan-out
*	if this function has been called, it means that
*	Transfer availability has already been verified
*
*	Return value is the NEW customer that owns the pallet.
**************************************************************************/
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT BOL, CARGO_DESCRIPTION, COMMODITY_CODE
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust_from."'
				AND ARRIVAL_NUM = '".$Arrival."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(ATP1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(ATP1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$plt_bol = $short_term_data_row['BOL'];
	$plt_mark = $short_term_data_row['CARGO_DESCRIPTION'];
	$plt_comm = $short_term_data_row['COMMODITY_CODE'];
/*
	// note:  this statement can return more than one row; but we are only interested in
	// decrementing one of the counters, so we only take 1 of the transfer#s
	$sql = "SELECT TRANSFER_NUM, ACTUAL_DATE
			FROM ARGJUICE_TRANSFERS
			WHERE CUSTOMER_FROM = '".$cust_from."'
				AND CUSTOMER_TO = '".$cust_to."'
				AND COMMODITY_CODE = '".$plt_comm."'
				AND ARRIVAL_NUM = '".$Arrival."'
				AND BOL = '".$plt_bol."'
				AND CARGO_DESCRIPTION = '".$plt_mark."'
				AND QTY_LEFT_TO_TRANS > 0";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(ATP2a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(ATP2b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$trans_num = $short_term_data_row['TRANSFER_NUM'];
	$trans_date = $short_term_data_row['ACTUAL_DATE'];
*/
	$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_ACT
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND CUSTOMER_ID = '".$cust_from."'
				AND ARRIVAL_NUM = '".$Arrival."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(ATP3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(ATP3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$act_num = ($short_term_data_row['THE_ACT'] + 1);

	$sql = "INSERT INTO CARGO_TRACKING
				(COMMODITY_CODE, 
				CARGO_DESCRIPTION, 
				DATE_RECEIVED, 
				QTY_RECEIVED,
				QTY_UNIT,
				WAREHOUSE_LOCATION,
				RECEIVER_ID, 
				QTY_IN_HOUSE, 
				FUMIGATION_CODE, 
				EXPORTER_CODE, 
				PALLET_ID, 
				ARRIVAL_NUM, 
				RECEIVING_TYPE, 
				BATCH_ID,
				MANIFESTED, 
				BOL, 
				DECK, 
				HATCH, 
				CARGO_TYPE_ID, 
				SOURCE_NOTE)
			(SELECT
				COMMODITY_CODE,
				CARGO_DESCRIPTION,
				SYSDATE,
				QTY_RECEIVED,
				QTY_UNIT,
				WAREHOUSE_LOCATION,
				'".$cust_to."', 
				QTY_IN_HOUSE, 
				FUMIGATION_CODE, 
				EXPORTER_CODE, 
				PALLET_ID, 
				ARRIVAL_NUM, 
				RECEIVING_TYPE, 
				BATCH_ID,
				MANIFESTED, 
				BOL, 
				DECK, 
				HATCH, 
				CARGO_TYPE_ID,
				'Auto-trans (".$cust_from.")'
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust_from."'
				AND ARRIVAL_NUM = '".$Arrival."'
			)";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP4a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP4b)");

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
				QTY_LEFT,
				ACTIVITY_DESCRIPTION)
			(SELECT
				'1',
				'11',
				QTY_RECEIVED,
				'".$emp_no."',
				'AUTOTRANS-IN',
				RECEIVER_ID,
				DATE_RECEIVED,
				PALLET_ID,
				ARRIVAL_NUM,
				QTY_RECEIVED,
				'".$cust_from."'
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust_to."'
				AND ARRIVAL_NUM = '".$Arrival."'
			)";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP5a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP5b)");

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
				QTY_LEFT,
				ACTIVITY_DESCRIPTION)
			(SELECT
				'".$act_num."',
				'11',
				QTY_IN_HOUSE,
				'".$emp_no."',
				'AUTOTRAN-OUT',
				RECEIVER_ID,
				SYSDATE,
				PALLET_ID,
				ARRIVAL_NUM,
				'0',
				'".$cust_to."'
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust_from."'
				AND ARRIVAL_NUM = '".$Arrival."'
			)";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP8a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP8b)");

	$sql = "UPDATE CARGO_TRACKING
			SET QTY_IN_HOUSE = 0
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust_from."'
				AND ARRIVAL_NUM = '".$Arrival."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP6a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP6b)");

	$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
			SET JUICE_STRAPCOUNT = 0,
				JUICE_REBANDS = 0
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust_from."'
				AND ARRIVAL_NUM = '".$Arrival."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP7a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP7b)");
/*
	$sql = "UPDATE ARGJUICE_TRANSFERS
			SET QTY_LEFT_TO_TRANS = (QTY_LEFT_TO_TRANS - 1)
			WHERE CUSTOMER_FROM = '".$cust_from."'
				AND CUSTOMER_TO = '".$cust_to."'
				AND COMMODITY_CODE = '".$plt_comm."'
				AND ARRIVAL_NUM = '".$Arrival."'
				AND BOL = '".$plt_bol."'
				AND CARGO_DESCRIPTION = '".$plt_mark."'
				AND TRANSFER_NUM = '".$trans_num."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP9a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to\nInsert Pallet\n(ATP9b)");
*/
	return $cust_to;

	ora_commit($rf_conn);
}





function Relabel($emp_no, $Barcode, $Arrival, $calling_func){
/*
*	Inserts into CARGO_TRACKING a new juice record for a POW-relabel
*	Also receives said pallet (since the checker just put a sticker on it,
*	We can safely assume it's, ya know, here and stuff)
*
*******************************************************************************************/
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	if($calling_func == "Unload_Ship"){
		$sql = "INSERT INTO CARGO_TRACKING
					(COMMODITY_CODE, 
					CARGO_DESCRIPTION, 
					DATE_RECEIVED, 
					QTY_RECEIVED, 
					WAREHOUSE_LOCATION,
					RECEIVER_ID, 
					QTY_IN_HOUSE, 
					FUMIGATION_CODE, 
					EXPORTER_CODE, 
					PALLET_ID, 
					ARRIVAL_NUM, 
					RECEIVING_TYPE, 
					BATCH_ID,
					MANIFESTED, 
					BOL, 
					DECK, 
					HATCH, 
					CARGO_TYPE_ID, 
					SOURCE_NOTE)
				VALUES
					('0',
					'BAD BARCODE',
					SYSDATE,
					'1',
					'N/A',
					'-1',
					'1',
					'N',
					'N/A',
					'".$Barcode."',
					'".$Arrival."',
					'S',
					'N/A',
					'N',
					'N/A',
					'N/A',
					'N/A',
					'1',
					'Bad Barcode; Relabeled at POW')";
		$ora_success = ora_parse($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nInsert Pallet\n(ReLab1a)");
		$ora_success = ora_exec($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nInsert Pallet\n(ReLab1b)");

		Auto_Receive_Pallet($emp_no, $Barcode, "-1", $Arrival);
	}
}

function Void_Autotransfer($Barcode, $cust, $LR, $activity_num, $bol, $cargo_desc){
/* function deletes a pallet created by an auto-transfer-shipout.
****************************************************************/

	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT ACTIVITY_DESCRIPTION FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND CUSTOMER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$LR."'
				AND ACTIVITY_NUM = '".$activity_num."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(VA1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(VA1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//	$undone_autotrans = $short_term_data_row['ORDER_NUM'];
	$return_to_cust = $short_term_data_row['ACTIVITY_DESCRIPTION'];

/*
	$sql = "SELECT CUSTOMER_FROM
			FROM ARGJUICE_TRANSFERS
			WHERE CUSTOMER_TO = '".$cust."'
				AND ARRIVAL_NUM = '".$LR."'
				AND TRANSFER_NUM = '".$undone_autotrans."'
				AND BOL = '".$bol."'
				AND CARGO_DESCRIPTION = '".$cargo_desc."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(VA2a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(VA2b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$return_to_cust = $short_term_data_row['CUSTOMER_FROM'];
*/
	$sql = "SELECT ACTIVITY_NUM FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND CUSTOMER_ID = '".$return_to_cust."'
				AND ARRIVAL_NUM = '".$LR."'
				AND SERVICE_CODE = '11'
				AND ORDER_NUM = 'AUTOTRAN-OUT'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(VA3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nTransfer Info\n(VA3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$undo_autotrans_orig_cust_act = $short_term_data_row['ACTIVITY_NUM'];

	// we got the data, undo the autotrans...
	$sql = "UPDATE CARGO_ACTIVITY
			SET ORDER_NUM = 'VOIDED'
			WHERE PALLET_ID = '".$Barcode."'
				AND CUSTOMER_ID = '".$return_to_cust."'
				AND ARRIVAL_NUM = '".$LR."'
				AND ACTIVITY_NUM = '".$undo_autotrans_orig_cust_act."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA4a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA4b)");

	$sql = "UPDATE CARGO_TRACKING
			SET QTY_IN_HOUSE = 1
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$return_to_cust."'
				AND ARRIVAL_NUM = '".$LR."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA5a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA5b)");

	// eliminate the "transferred" pallet
	$sql = "DELETE FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND CUSTOMER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$LR."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA6a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA6b)");

	$sql = "DELETE FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcode."'
				AND RECEIVER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$LR."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA7a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA7b)");
/*
	// lastly, put the pallet "back" into the pending autotransfer record
	$sql = "UPDATE ARGJUICE_TRANSFERS
			SET QTY_LEFT_TO_TRANS = QTY_LEFT_TO_TRANS + 1
			WHERE CUSTOMER_TO = '".$cust."'
				AND CUSTOMER_FROM = '".$return_to_cust."'
				AND ARRIVAL_NUM = '".$LR."'
				AND TRANSFER_NUM = '".$undone_autotrans."'
				AND BOL = '".$bol."'
				AND CARGO_DESCRIPTION = '".$cargo_desc."'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA8a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Cannot Update\nTransfer Info\n(VA8b)");
*/
	// done.
	ora_commit($rf_conn);
}



// ---------------MAIN Menu-----------------
// starts here

$scanner_type = "ARG JUICE";

$SID = Login_Super($argv[1]);
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}


do {
	fresh_screen("ARGEN JUICE");
	echo "1: Super Functions\n";
	echo "2: Checker\n";
	echo "3: Exit Juice\n";
	fscanf(STDIN, "%s\n", $ArgenSuperCHOICE);
} while ($ArgenSuperCHOICE != 1 && $ArgenSuperCHOICE != 2 && $ArgenSuperCHOICE != 3);

if($ArgenSuperCHOICE == 1){
	do {
		fresh_screen("Juice (Super)");
		echo "1: Exit\n"; 
		fscanf(STDIN, "%s\n", $ArgenCHOICE);

		switch ($ArgenCHOICE) {
			case 1:
				echo "under construction\n";
				// will exit
			break;
		}
	}while ($ArgenCHOICE != 1);

} elseif($ArgenSuperCHOICE == 2){
	
	do {
		$subCHOICE = ""; // no submenus at this time, but keep this line around anyway

		if($CID == ""){
			$CID = Login_Checker();
		}
		fresh_screen(" Juice (Checker)");
		echo " 1: Super(", $SID, ")\n";
		echo " 2: Chkr(", $CID, ")\n";
		echo " 3: Unload Cargo\n";
		echo " 4: Load Truck\n";
		echo " 5: Pallet Info\n";
		echo " 6: Order Info\n"; 
		echo " 7: Void Outbound\n";
		echo " 8: Pre-Check Dummy\n"; 
		echo " 9: Cargo List Scan\n"; 
		echo " 10: Exit Juice\n"; 
		echo " ENTER (1-10):\n";
		fscanf(STDIN, "%s\n", $ArgenCHOICE);

		switch ($ArgenCHOICE) {
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

			case 5:
				Info_Pallet();
			break;

			case 6:
				Info_Order();
			break;

			case 7:
				VoidOUT($CID);
			break;

			case 8:
				Precheck_Dummy();
			break;

			case 9:
				NoCheckScan($CID);
			break;

			case 10:
				// will exit
			break;

			default:
		}
	} while ($ArgenCHOICE != 10);
}
