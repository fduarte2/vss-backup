<?
/*
*	Clementine Scanner, originally written Aug2010.
*
*	As part of the massive migration of scanner programs to PHP.
*
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

	$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$emp_no."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nScanner Name\n(US1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nScanner Name\n(US1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$emp_name = $short_term_data_row['LOGIN_ID'];

	$Arrival = "XXX";

	// get and validate ship #
	while($Arrival == "XXX" || $Arrival == "Invalid"){
		fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.");
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
			database_check($ora_success, "Cannot Retrieve\nVessel Name\n(US2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nVessel Name\n(US2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$Arrival = "Invalid";
			}
		}
	}

	$count = 0;

	fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.");
	echo "Count: ".$count."\n";
	echo "LR Num: ".$Arrival."\n";
	$Barcode = "";
	while($Barcode == ""){
		echo "Barcode:";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}

	while(strtoupper($Barcode) != "X"){
		$Barcode = ten_to_full_BC($Barcode);
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);
/*		system("clear");
		echo "a-".substr($Barcode, 0, 13);
		echo "\n";
		echo "b-".substr($Barcode, 13);
		fscanf(STDIN, "%s\n", $junk); */
		$new_pallet = false;
		$increm_count = 0;
		$Cust = "";
		$Comm = "";
		$qty_rec = "";
		$proceed = false;
		$pkg_hse = "";
		$hosp = "";
		$size = "";
		$rec_type = "";

		
		// determine if this pallets exists, is singular, or is multiple (and select which one).  Or allow user to cancel operation.
		$proceed = Validate_duplicate_get_comm_and_cust($Barcode, $Arrival, $Cust, $Comm, $qty_rec, $new_pallet);

		$sql = "SELECT RECEIVING_TYPE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nARV Type\n(US3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nARV Type\n(US3b)");
		if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$rec_type = $short_term_data_row['RECEIVING_TYPE'];
		} else {
			$rec_type = "";
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."' AND SERVICE_CODE NOT IN ('1', '8')";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(US4a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(US4b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($rec_type != "S" && $rec_type != ""){
			// Cannot "receive" a pallet that isn't vessel-based with this function
			fresh_screen("CLEMENTINE\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nNot a Ship-\nBased Pallet.\nCannot Receive.", "bad");
		} elseif($short_term_data_row['THE_COUNT'] > 0){
			// Cannot "receive" a pallet that already has activity against it
			fresh_screen("CLEMENTINE\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nAlready has\nActivity.\nCannot Receive.", "bad");
		} elseif(!$proceed) {
			// validate pallet function was cancelled
			fresh_screen("CLEMENTINE\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nCancelled.", "bad");
		}else{
			if($new_pallet){
				// if this is a created pallet, get necessary info (or cancel out)
				while($proceed && ($Cust == "" || !is_numeric($Cust))){
					fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					if($Cust != ""){
						echo "Invalid Cust(".$Cust.")\n";
					}
					echo "CustomerID#: \n";
					fscanf(STDIN, "%s\n", $Cust);
					$Cust = strtoupper($Cust);
					$Cust = remove_badchars($Cust);

					if($Cust == "X"){
						$proceed = false;
					} elseif(!is_numeric($Cust)) {
						// do nothing, will not pass recheck
					} else {
						// verify legit customer
						$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nCust Info\n(US5a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nCust Info\n(US5b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("CLEMENTINE\nUnload Ship\n\nInvalid Cust#\n(".$Cust.")");
							fscanf(STDIN, "%s\n", $junk);
							$Cust = "";
						}
					}

				}
				while($proceed && ($Comm == "" || !is_numeric($Comm))){
					fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					if($Comm != ""){
						echo "Invalid Comm(".$Comm.")\n";
					}
					echo "Commodity#: \n";
					fscanf(STDIN, "%s\n", $Comm);
					$Comm = strtoupper($Comm);

					if($Comm == "X"){
						$proceed = false;
					} else {
						// verify legit commodity
						$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."' AND COMMODITY_TYPE = 'CLEMENTINES'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(US6a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(US6b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("CLEMENTINE\nUnload Ship\n\nInvalid Comm#\n(".$Comm.")");
							fscanf(STDIN, "%s\n", $junk);
							$Comm = "";
						}
					}
				}

				while($proceed && ($qty_rec == "" || !is_numeric($qty_rec))){
					fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "QTY Rcvd#: \n";
					fscanf(STDIN, "%s\n", $qty_rec);
					$qty_rec = strtoupper($qty_rec);
					if($qty_rec == "X"){
						$proceed = false;
					}
				}

				while($proceed && $pkg_hse == ""){
					fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "PKG HSE: \n";
					fscanf(STDIN, "%s\n", $pkg_hse);
					$pkg_hse = strtoupper($pkg_hse);
					if($pkg_hse == "X"){
						$proceed = false;
					}
				}
			
				while($proceed && ($size == "" || !is_numeric($size))){
					fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "Size: \n";
					fscanf(STDIN, "%s\n", $size);
					$size = strtoupper($size);
					if($size == "X"){
						$proceed = false;
					}
				}

				$hosp = "default";
				while($proceed && $hosp == "default"){
					fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "Hosp (Y/N)?: \n";
					fscanf(STDIN, "%s\n", $hosp);
					$hosp = strtoupper($hosp);
					if($hosp == "X"){
						$proceed = false;
					} elseif($hosp != "Y"){
						$hosp = "N";
					}
				}

			}

			if(!$proceed){
				// new pallet that was cancelled
				fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nCancelled.");
			} elseif(!validate_comm_to_scannertype($Comm, $scanner_type)){
				fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET COMMODITY\nNOT QUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$proceed = false;
			} else {
				// get other pallet info
				$asterisk_array = array();
				$qty_dmg = 0;
				$loc = "";
				$prev_hosp = "";
				$new_hosp = "";
//				$variety = "";
//				$chep = "";
				$currcomm = $Comm;
				$currcust = $Cust;

				$sql = "SELECT DATE_RECEIVED, QTY_DAMAGED, WAREHOUSE_LOCATION, CARGO_STATUS, CARGO_SIZE, EXPORTER_CODE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$Comm."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US7a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US7b)");
				if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
					$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
//					$variety = $short_term_data_row['VARIETY'];
					$size = $short_term_data_row['CARGO_SIZE'];
//					$chep = $short_term_data_row['CHEP'];
					$pkg_hse = $short_term_data_row['EXPORTER_CODE'];
					$prev_hosp = $short_term_data_row['CARGO_STATUS'];
					if($prev_hosp == "H" || $prev_hosp == "B"){
						$hosp = "Y";
					} else {
						$hosp = "";
					}
					if($short_term_data_row['DATE_RECEIVED'] == ""){
						$increm_count = 1;
					}
				}

				$sql = "SELECT NVL(PLT_FAULT, 'N') THE_FAULT 
						FROM CARGO_TRACKING_ADDITIONAL_DATA 
						WHERE PALLET_ID = '".$Barcode."' 
							AND ARRIVAL_NUM = '".$Arrival."' 
							AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nCust Info\n(US7-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nCust Info\n(US7-1b)");
				if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$fault = $short_term_data_row['THE_FAULT'];
				} else {
					$fault = "N";
				}


				do{
					$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 9) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(US8a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(US8b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$commname = $short_term_data_row['THE_COMM'];

					$sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 9) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(US9a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(US9b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$custname = $short_term_data_row['THE_CUST'];

//					echo $asterisk_array[5]."6)Var: ".$variety."\n";
//					echo $asterisk_array[7]."8)Chep: ".$chep."\n";
					system("clear");
					echo $disp_barcode."\n";
					echo $asterisk_array[0]."1)Cust: ".$custname."\n";
					echo $asterisk_array[1]."2)Comm: ".$commname."\n";
					echo $asterisk_array[2]."3)QTY Rcvd: ".$qty_rec."\n";
					echo $asterisk_array[3]."4)QTY Dmg: ".$qty_dmg."\n";
					echo $asterisk_array[4]."5)Faulty?: ".$fault."\n";
					echo $asterisk_array[5]."6)Loc: ".$loc."\n";
					echo $asterisk_array[6]."7)Size: ".$size."\n";
					echo $asterisk_array[7]."8)PKG HSE: ".$pkg_hse."\n";
					echo $asterisk_array[8]."9)Hosp: ".$hosp."\n";
					echo "To Change Select #\n";
					echo "\"Enter\" to Accept\n";
					echo "\"X\" To Escape\n";

					$Choice = "";
					fscanf(STDIN, "%s\n", $Choice);
					$Choice = strtoupper($Choice);

/*						case 6:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "var", $variety)){
								$asterisk_array[5] = "*";
							}
							break; */
/*						case 8:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "chep", $chep)){
								$asterisk_array[7] = "*";
							}
							break; */
					switch($Choice){
						case 1:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "cust", $Cust)){
								$asterisk_array[0] = "*";
							}
							break;
						case 2:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "comm", $Comm)){
								$asterisk_array[1] = "*";
							}
							break;
						case 3:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "rcvd", $qty_rec)){
								$asterisk_array[2] = "*";
							}
							break;
						case 4:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "dmg", $qty_dmg)){
								$asterisk_array[3] = "*";
							}
							break;
						case 5:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "fault", $fault)){
								$asterisk_array[4] = "*";
							}
							break;
						case 6:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "loc", $loc)){
								$asterisk_array[4] = "*";
							}
							break;
						case 7:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "size", $size)){
								$asterisk_array[5] = "*";
							}
							break;
						case 8:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "pkg_hse", $pkg_hse)){
								$asterisk_array[6] = "*";
							}
							break;
						case 9:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "hosp", $hosp)){
								$asterisk_array[7] = "*";
							}
							break;
						case "X":
							fresh_screen("CLEMENTINE\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nCancelled.");
							break;
						case "":
							CTE_VERIFY($Barcode, $Arrival, $Cust, $currcust);

							if(!$new_pallet){
// 										VARIETY = '".$variety."',
//										CHEP = '".$chep."'
								$sql = "UPDATE CARGO_TRACKING SET 
										DATE_RECEIVED = NVL(DATE_RECEIVED, SYSDATE),
										RECEIVER_ID = '".$Cust."',
										QTY_RECEIVED = '".$qty_rec."',
										QTY_IN_HOUSE = '".$qty_rec."',
										COMMODITY_CODE = '".$Comm."',
										QTY_DAMAGED = '".$qty_dmg."', ";
										if($hosp == "Y"){
											if($prev_hosp == "B" || $prev_hosp == "R"){
												$new_hosp = "B";
											} else {
												$new_hosp = "H";
											}
										} else {
											if($prev_hosp == "B" || $prev_hosp == "R"){
												$new_hosp = "R";
											} else {
												$new_hosp = "";
											}
										}
										$sql .= "CARGO_STATUS = '".$new_hosp."',
										WAREHOUSE_LOCATION = '".$loc."',
										EXPORTER_CODE = '".$pkg_hse."',
										MARK = 'AT POW',
										CARGO_SIZE = '".$size."'
										WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$currcomm."' AND RECEIVER_ID = '".$currcust."'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US10a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US10b)");

								$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$currcust."' AND SERVICE_CODE IN ('1', '8')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US11a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US11b)");

								if($asterisk_array[7] == "*"){ // do this if the Hospital Status has changed
									$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM DC_INSPECTION_LOG";
									$ora_success = ora_parse($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(US12a)");
									$ora_success = ora_exec($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(US12b)");
									ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
									$next_trans = $short_term_data_row['THE_MAX'] + 1;

									if($hosp == "Y"){
										$desc = "HOSPITAL";
									} else {
										$desc = "NULL";
									}

									$sql = "INSERT INTO DC_INSPECTION_LOG
												(TRANSACTION_ID,
												INSPECTOR_ID,
												INSPECTION_DATETIME,
												ACTION_TYPE,
												NUM_PALLETS,
												ARRIVAL_NUM)
											VALUES
												('".$next_trans."',
												'".$emp_name."',
												SYSDATE,
												'".$desc."',
												'1',
												'".$Arrival."')";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US13a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US13b)");

									$sql = "INSERT INTO DC_INSPECTED_PALLET
												(PALLET_ID,
												TRANSACTION_ID,
												PREVIOUS_STATUS,
												NEW_STATUS)
											VALUES
												('".$Barcode."',
												'".$next_trans."',
												'".$prev_hosp."',
												'".$new_hosp."')";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US14a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US14b)");
								}
							} else {
								if($hosp == "Y"){
									$new_hosp = "H";
								} else {
									$new_hosp = "";
								}

								$weight = get_weight($size, $qty_rec);

								$sql = "INSERT INTO CARGO_TRACKING 
										(COMMODITY_CODE,
										CARGO_DESCRIPTION,
										DATE_RECEIVED,
										QTY_RECEIVED,
										BATCH_ID,
										RECEIVER_ID,
										QTY_DAMAGED,
										WAREHOUSE_LOCATION,
										QTY_IN_HOUSE,
										PALLET_ID,
										ARRIVAL_NUM,
										FROM_SHIPPING_LINE,
										SHIPPING_LINE,
										RECEIVING_TYPE,
										CARGO_SIZE,
										WEIGHT,
										WEIGHT_UNIT,
										EXPORTER_CODE,
										CARGO_STATUS,
										MARK,
										CARGO_TYPE_ID)
										VALUES
										('".$Comm."',
										'".$pkg_hse."-".$size."-".$qty_rec."',
										SYSDATE,
										'".$qty_rec."',
										'".$qty_rec."',
										'".$Cust."',
										'".$qty_dmg."',
										'".$loc."',
										'".$qty_rec."',
										'".$Barcode."',
										'".$Arrival."',
										'6000',
										'6000',
										'S',
										'".$size."',
										'".$weight."',
										'KG',
										'".$pkg_hse."',
										'".$new_hosp."',
										'AT POW',
										'1')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nInsert Pallet\n(US15a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nInsert Pallet\n(US15b)");

								$increm_count = 1;

								if($hosp == "Y"){
									$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM DC_INSPECTION_LOG";
									$ora_success = ora_parse($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(US16a)");
									$ora_success = ora_exec($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(US16b)");
									ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
									$next_trans = $short_term_data_row['THE_MAX'] + 1;


									$sql = "INSERT INTO DC_INSPECTION_LOG
												(TRANSACTION_ID,
												INSPECTOR_ID,
												INSPECTION_DATETIME,
												ACTION_TYPE,
												NUM_PALLETS,
												ARRIVAL_NUM)
											VALUES
												('".$next_trans."',
												'".$emp_name."',
												SYSDATE,
												'HOSPITAL',
												'1',
												'".$Arrival."')";
//									echo $sql."\n";
//									fscanf(STDIN, "%s\n", $junk);
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US17a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US17b)");

									$sql = "INSERT INTO DC_INSPECTED_PALLET
												(PALLET_ID,
												TRANSACTION_ID,
												PREVIOUS_STATUS,
												NEW_STATUS)
											VALUES
												('".$Barcode."',
												'".$next_trans."',
												'',
												'H')";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US18a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(US18b)");
								}
							}

							// HD 9363
							// new field in C_T_A_D, gets updated regardless of how pallet got into system
							$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
									SET PLT_FAULT = '".$fault."' 
									WHERE PALLET_ID = '".$Barcode."' 
										AND ARRIVAL_NUM = '".$Arrival."' 
										AND RECEIVER_ID = '".$Cust."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US18-1a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US18-1b)");

							// NOTE:  as we can only be at this point in the code, if there is NO (or is NO LONGER) activity
							// against this "new" pallet, activity_num gets a '1' by default
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
									'".$Cust."',
									SYSDATE,
									'".$Barcode."',
									'".$Arrival."',
									'".$qty_rec."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US19a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US19b)");

							ora_commit($rf_conn);
							$count += $increm_count;
							fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to Exit\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\n\nRECEIVED!");

							break;

						default:
							fresh_screen("CLEMENTINE\nUnload Ship\nEnter X to exit.\n\n**PLEASE FINISH\nRECEIVING PALLET\n".$disp_barcode."**", "bad");
							fscanf(STDIN, "%s\n", $junk);
							break;
					}
				} while($Choice != "X" && $Choice != "");
			}
		}

		echo "Count: ".$count."\n";
		echo "LR Num: ".$Arrival."\n";
		$Barcode = "";
		while($Barcode == ""){
			echo "Barcode:";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function Unload_Truck($CID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$emp_no."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nScanner Name\n(UT1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nScanner Name\n(UT1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$emp_name = $short_term_data_row['LOGIN_ID'];

	$Cust = "";
	$Container = "";

	// get and validate cust #
	while($Cust == "" || !is_numeric($Cust)){
		fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.");
		if($Cust != ""){
			echo "Invalid Cust(".$Cust.")\n";
		}
		echo "CustomerID#: \n";
		fscanf(STDIN, "%s\n", $Cust);
		$Cust = strtoupper($Cust);
		$Cust = remove_badchars($Cust);

		if($Cust == "X"){
			return;
		} elseif(!is_numeric($Cust)) {
			// do nothing, will not pass recheck
		} else {
			// verify legit customer
			$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nCust Info\n(UT2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nCust Info\n(UT2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("CLEMENTINE\nUnload Ship\n\nInvalid Cust#\n(".$Cust.")");
				fscanf(STDIN, "%s\n", $junk);
				$Cust = "";
			}
		}
	}

	// get "CONTAINER" (ship or train #)
	while($Container == ""){
		fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.");
		echo "CONTAINER: \n";
		fscanf(STDIN, "%s\n", $Container);
		$Container = strtoupper($Container);
		if($Container == "X"){
			return;
		}
	}

	$count = 0;

	fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.");
	echo "Count: ".$count."\n";
	echo "Cust: ".$Cust."\n";
	echo "CONT: ".$Container."\n";
	$Barcode = "";
	while($Barcode == ""){
		echo "Barcode:";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}

	while(strtoupper($Barcode) != "X"){
		$Barcode = ten_to_full_BC($Barcode);
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);
		$new_pallet = false;
		$increm_count = 0;
//		$Cust = "";
		$Comm = "";
		$qty_rec = "";
		$proceed = false;
		$pkg_hse = "";
		$hosp = "";
		$size = "";
		$rec_type = "";


		// determine if this pallets exists.  Or allow user to cancel operation.
		$proceed = Validate_duplicate_get_comm_and_cust($Barcode, $Cust, $Cust, $Comm, $qty_rec, $new_pallet);

		$sql = "SELECT RECEIVING_TYPE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nARV Type\n(UT3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nARV Type\n(UT3b)");
		if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$rec_type = $short_term_data_row['RECEIVING_TYPE'];
		} else {
			$rec_type = "";
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Cust."' AND SERVICE_CODE NOT IN ('1', '8')";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(UT4a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(UT4b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($rec_type != "T" && $rec_type != ""){
			// Cannot "receive" a pallet that isn't vessel-based with this function
			fresh_screen("CLEMENTINE\nUnload Truck\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nNot a Truck-\nBased Pallet.\nCannot Receive.", "bad");
		} elseif($short_term_data_row['THE_COUNT'] > 0){
			// Cannot "receive" a pallet that already has activity against it
			fresh_screen("CLEMENTINE\nUnload Ship\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nAlready has\nActivity.\nCannot Receive.", "bad");
		} elseif(!$proceed) {
			// validate pallet function was cancelled
			fresh_screen("CLEMENTINE\nUnload Ship\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nCancelled.", "bad");
		}else{
			if($new_pallet){
				// if this is a created pallet, get necessary info (or cancel out)
				while($proceed && ($Comm == "" || !is_numeric($Comm))){
					fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					if($Comm != ""){
						echo "Invalid Comm(".$Comm.")\n";
					}
					echo "Commodity#: \n";
					fscanf(STDIN, "%s\n", $Comm);
					$Comm = strtoupper($Comm);

					if($Comm == "X"){
						$proceed = false;
					} else {
						// verify legit commodity
						$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."' AND COMMODITY_TYPE = 'CLEMENTINES'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(UT6a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(UT6b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("CLEMENTINE\nUnload Truck\n\nInvalid Comm#\n(".$Comm.")");
							fscanf(STDIN, "%s\n", $junk);
							$Comm = "";
						}
					}
				}

				while($proceed && ($qty_rec == "" || !is_numeric($qty_rec))){
					fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "QTY Rcvd#: \n";
					fscanf(STDIN, "%s\n", $qty_rec);
					$qty_rec = strtoupper($qty_rec);
					if($qty_rec == "X"){
						$proceed = false;
					}
				}

				while($proceed && $pkg_hse == ""){
					fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "PKG HSE: \n";
					fscanf(STDIN, "%s\n", $pkg_hse);
					$pkg_hse = strtoupper($pkg_hse);
					if($pkg_hse == "X"){
						$proceed = false;
					}
				}
			
				while($proceed && ($size == "" || !is_numeric($size))){
					fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "Size: \n";
					fscanf(STDIN, "%s\n", $size);
					$size = strtoupper($size);
					if($size == "X"){
						$proceed = false;
					}
				}

				$hosp = "default";
				while($proceed && $hosp == "default"){
					fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "Hosp (Y/N)?: \n";
					fscanf(STDIN, "%s\n", $hosp);
					$hosp = strtoupper($hosp);
					if($hosp == "X"){
						$proceed = false;
					} elseif($hosp != "Y"){
						$hosp = "N";
					}
				}

			}

			if(!$proceed){
				// new pallet that was cancelled
				fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to exit.\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nCancelled.");
			} else {
				// get other pallet info
				$asterisk_array = array();
				$qty_dmg = 0;
				$loc = "";
				$prev_hosp = "";
				$new_hosp = "";
//				$variety = "";
//				$chep = "";
				$currcomm = $Comm;
//				$currcust = $Cust;


				$sql = "SELECT DATE_RECEIVED, QTY_DAMAGED, WAREHOUSE_LOCATION, CARGO_STATUS, CARGO_SIZE, EXPORTER_CODE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Cust."' AND COMMODITY_CODE = '".$Comm."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(UT7a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(UT7b)");
				if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
					$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
//					$variety = $short_term_data_row['VARIETY'];
					$size = $short_term_data_row['CARGO_SIZE'];
//					$chep = $short_term_data_row['CHEP'];
					$pkg_hse = $short_term_data_row['EXPORTER_CODE'];
					$prev_hosp = $short_term_data_row['CARGO_STATUS'];
					if($prev_hosp == "H" || $prev_hosp == "B"){
						$hosp = "Y";
					} else {
						$hosp = "";
					}
					if($short_term_data_row['DATE_RECEIVED'] == ""){
						$increm_count = 1;
					}
				}

				do{
					$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 9) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(UT8a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(UT8b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$commname = $short_term_data_row['THE_COMM'];

					system("clear");
					echo $disp_barcode."\n";
//					echo $asterisk_array[0]."1)Cust: ".$custname."\n";
					echo $asterisk_array[0]."1)Comm: ".$commname."\n";
					echo $asterisk_array[1]."2)QTY Rcvd: ".$qty_rec."\n";
					echo $asterisk_array[2]."3)QTY Dmg: ".$qty_dmg."\n";
					echo $asterisk_array[3]."4)Loc: ".$loc."\n";
					echo $asterisk_array[4]."5)Size: ".$size."\n";
					echo $asterisk_array[5]."6)PKG HSE: ".$pkg_hse."\n";
					echo $asterisk_array[6]."7)Hosp: ".$hosp."\n";
					echo "To Change Select #\n";
					echo "\"Enter\" to Accept\n";
					echo "\"X\" To Escape\n";

					$Choice = "";
					fscanf(STDIN, "%s\n", $Choice);
					$Choice = strtoupper($Choice);

					switch($Choice){
/*						case 1:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "cust", $Cust)){
								$asterisk_array[0] = "*";
							}
							break; */
						case 1:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "comm", $Comm)){
								$asterisk_array[0] = "*";
							}
							break;
						case 2:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "rcvd", $qty_rec)){
								$asterisk_array[1] = "*";
							}
							break;
						case 3:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "dmg", $qty_dmg)){
								$asterisk_array[2] = "*";
							}
							break;
						case 4:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "loc", $loc)){
								$asterisk_array[3] = "*";
							}
							break;
						case 5:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "size", $size)){
								$asterisk_array[4] = "*";
							}
							break;
						case 6:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "pkg_hse", $pkg_hse)){
								$asterisk_array[5] = "*";
							}
							break;
						case 7:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "hosp", $hosp)){
								$asterisk_array[6] = "*";
							}
							break;
						case "X":
							fresh_screen("CLEMENTINE\nUnload Truck\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\nCancelled.");
							break;
						case "":
							CTE_VERIFY($Barcode, $Cust, $Cust, $currcust);

							if(!$new_pallet){
//										RECEIVER_ID = '".$Cust."',
								$sql = "UPDATE CARGO_TRACKING SET 
										DATE_RECEIVED = NVL(DATE_RECEIVED, SYSDATE),
										QTY_RECEIVED = '".$qty_rec."',
										QTY_IN_HOUSE = '".$qty_rec."',
										COMMODITY_CODE = '".$Comm."',
										QTY_DAMAGED = '".$qty_dmg."', ";
										if($hosp == "Y"){
											if($prev_hosp == "B" || $prev_hosp == "R"){
												$new_hosp = "B";
											} else {
												$new_hosp = "H";
											}
										} else {
											if($prev_hosp == "B" || $prev_hosp == "R"){
												$new_hosp = "R";
											} else {
												$new_hosp = "";
											}
										}
										$sql .= "CARGO_STATUS = '".$new_hosp."',
										WAREHOUSE_LOCATION = '".$loc."',
										EXPORTER_CODE = '".$pkg_hse."',
										MARK = 'AT POW',
										CONTAINER_ID = '".$Container."',
										CARGO_SIZE = '".$size."'
										WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Cust."' AND COMMODITY_CODE = '".$currcomm."' AND RECEIVER_ID = '".$currcust."'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(UT10a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(UT10b)");

								$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Cust."' AND CUSTOMER_ID = '".$currcust."' AND SERVICE_CODE IN ('1', '8')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(UT11a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(UT11b)");

								if($asterisk_array[6] == "*"){ // do this if the Hospital Status has changed
									$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM DC_INSPECTION_LOG";
									$ora_success = ora_parse($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(UT12a)");
									$ora_success = ora_exec($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(UT12b)");
									ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
									$next_trans = $short_term_data_row['THE_MAX'] + 1;

									if($hosp == "Y"){
										$desc = "HOSPITAL";
									} else {
										$desc = "NULL";
									}

									$sql = "INSERT INTO DC_INSPECTION_LOG
												(TRANSACTION_ID,
												INSPECTOR_ID,
												INSPECTION_DATETIME,
												ACTION_TYPE,
												NUM_PALLETS,
												ARRIVAL_NUM)
											VALUES
												('".$next_trans."',
												'".$emp_name."',
												SYSDATE,
												'".$desc."',
												'1',
												'".$Cust."')";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT13a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT13b)");

									$sql = "INSERT INTO DC_INSPECTED_PALLET
												(PALLET_ID,
												TRANSACTION_ID,
												PREVIOUS_STATUS,
												NEW_STATUS)
											VALUES
												('".$Barcode."',
												'".$next_trans."',
												'".$prev_hosp."',
												'".$new_hosp."')";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT14a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT14b)");
								}
							} else {
								if($hosp == "Y"){
									$new_hosp = "H";
								} else {
									$new_hosp = "";
								}

								$weight = get_weight($size, $qty_rec);

								$sql = "INSERT INTO CARGO_TRACKING 
										(COMMODITY_CODE,
										CARGO_DESCRIPTION,
										DATE_RECEIVED,
										QTY_RECEIVED,
										BATCH_ID,
										RECEIVER_ID,
										QTY_DAMAGED,
										WAREHOUSE_LOCATION,
										QTY_IN_HOUSE,
										PALLET_ID,
										ARRIVAL_NUM,
										FROM_SHIPPING_LINE,
										SHIPPING_LINE,
										RECEIVING_TYPE,
										CARGO_SIZE,
										WEIGHT,
										WEIGHT_UNIT,
										EXPORTER_CODE,
										CARGO_STATUS,
										MARK,
										CARGO_TYPE_ID,
										CONTAINER_ID)
										VALUES
										('".$Comm."',
										'".$pkg_hse."-".$size."-".$qty_rec."',
										SYSDATE,
										'".$qty_rec."',
										'".$qty_rec."',
										'".$Cust."',
										'".$qty_dmg."',
										'".$loc."',
										'".$qty_rec."',
										'".$Barcode."',
										'".$Cust."',
										'".$Cust."',
										'".$Cust."',
										'T',
										'".$size."',
										'".$weight."',
										'KG',
										'".$pkg_hse."',
										'".$new_hosp."',
										'AT POW',
										'1',
										'".$Container."')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nInsert Pallet\n(UT15a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nInsert Pallet\n(UT15b)");

								$increm_count = 1;

								if($hosp == "Y"){
									$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM DC_INSPECTION_LOG";
									$ora_success = ora_parse($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(UT16a)");
									$ora_success = ora_exec($short_term_data_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(UT16b)");
									ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
									$next_trans = $short_term_data_row['THE_MAX'] + 1;


									$sql = "INSERT INTO DC_INSPECTION_LOG
												(TRANSACTION_ID,
												INSPECTOR_ID,
												INSPECTION_DATETIME,
												ACTION_TYPE,
												NUM_PALLETS,
												ARRIVAL_NUM)
											VALUES
												('".$next_trans."',
												'".$emp_name."',
												SYSDATE,
												'HOSPITAL',
												'1',
												'".$Cust."')";
//									echo $sql."\n";
//									fscanf(STDIN, "%s\n", $junk);
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT17a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT17b)");

									$sql = "INSERT INTO DC_INSPECTED_PALLET
												(PALLET_ID,
												TRANSACTION_ID,
												PREVIOUS_STATUS,
												NEW_STATUS)
											VALUES
												('".$Barcode."',
												'".$next_trans."',
												'',
												'H')";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT18a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Insp\n(UT18b)");
								}
							}

							// NOTE:  as we can only be at this point in the code, if there is NO (or is NO LONGER) activity
							// against this "new" pallet, activity_num gets a '1' by default
							$sql = "INSERT INTO CARGO_ACTIVITY
									(ACTIVITY_NUM,
									SERVICE_CODE,
									QTY_CHANGE,
									ACTIVITY_ID,
									CUSTOMER_ID,
									ORDER_NUM,
									DATE_OF_ACTIVITY,
									PALLET_ID,
									ARRIVAL_NUM,
									QTY_LEFT)
									VALUES
									('1',
									'8',
									'".$qty_rec."',
									'".$emp_no."',
									'".$Cust."',
									'".$Container."',
									SYSDATE,
									'".$Barcode."',
									'".$Cust."',
									'".$qty_rec."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(UT19a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(UT19b)");

							ora_commit($rf_conn);
							$count += $increm_count;
							fresh_screen("CLEMENTINE\nUnload Truck\nEnter X to Exit\n\nCust: ".$Cust."\nCONT: ".$Container."\nBC: ".$disp_barcode."\n\nRECEIVED!");

							break;
					}
				} while($Choice != "X" && $Choice != "");
			}
		}

		echo "Count: ".$count."\n";
		echo "Cust: ".$Cust."\n";
		echo "CONT: ".$Container."\n";
		$Barcode = "";
		while($Barcode == ""){
			echo "Barcode:";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
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
		$cust = "";
		$order_num = "";
		$seal_num = "";
//		$wing = "";

		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.");
			if($cust != ""){
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if($cust == "X"){
				return;
			}
			$cust = remove_badchars($cust);

			if(!is_numeric($cust)){
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		$prefix = which_clem_tables($cust);

		$sql = "SELECT COUNT(*) THE_COUNT FROM CLEM_ALTERNATE_LOADTRUCK WHERE CUSTOMER_ID = '".$cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(LTXa)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(LTXb)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] >= 1){
			// this customer's orders are no longer part of the order process as of 10/10/2011.  Redirecting these orders to a different function.
			no_picklist_Load_Truck($CID, $cust);
			return;
		}

		// get Order
		while($order_num == "" || $order_num == "Invalid"){
			fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT ORDERSTATUSID FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fresh_screen("CLEMENTINE\nTruck Out\n\nInvalid Order#\nOrder ".$order_num."\ndoes not exist.");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "Invalid";
			} elseif($short_term_data_row['ORDERSTATUSID'] != "4"){
				fresh_screen("CLEMENTINE\nTruck Out\n\nInvalid Order#\n".$order_num." not in\nValid Load Status");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "Invalid";
			} else {
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM ".$prefix."_ORDER DCO, ".$prefix."_PICKLIST DCP
						WHERE DCO.ORDERNUM = DCP.ORDERNUM
							AND DCO.ORDERNUM = '".$order."'
							AND TRIM(PACKINGHOUSE) = '9999'
							AND TO_CHAR(VESSELID) IN
									(SELECT TO_CHAR(LR_NUM) 
									FROM VESSEL_PROFILE
									WHERE DATE_DISCHARGED IS NOT NULL
										AND (DATE_DISCHARGED + 2) <= SYSDATE
										AND SHIP_PREFIX = 'CLEMENTINES'
									UNION
									SELECT DISTINCT CT.ARRIVAL_NUM FROM CARGO_TRACKING CT, VESSEL_PROFILE VP
									WHERE CT.ARRIVAL_NUM = TO_CHAR(LR_NUM)
										AND SHIP_PREFIX = 'CLEMENTINES'
										AND DATE_DISCHARGED IS NULL
										AND DATE_RECEIVED IS NOT NULL
										AND DATE_RECEIVED <= SYSDATE - 2
									)
						ORDER BY DCO.ORDERNUM";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT2-2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT2-2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] >= 1){
					fresh_screen("CLEMENTINE\nTruck Out\n\nInvalid Order#\n".$order_num." still has\n\"ANY\" picklist items\nafter the discharge\ngrace period.\nContact Annie Rizzo.");
					fscanf(STDIN, "%s\n", $junk);
					$order_num = "Invalid";
				}
			}
		}
/*
		// get and validate seal #
		while($seal_num == "" || $seal_num == "Invalid"){
			fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.");
			if($seal_num != ""){
				echo "Invalid Seal #\n";
			}
			echo "Seal#:\n";
			fscanf(STDIN, "%s\n", $seal_num);
			$seal_num = strtoupper($seal_num);
			if($seal_num == "X"){
				return;
			}
			$seal_num = remove_badchars($seal_num);

			$seal_check = loadout_CLR_sealscan($cust, $order_num, $seal_num);
			if($seal_check != ""){
				fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\n**".$seal_check."**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$seal_num = "";
			}
		}
*/
		$sql = "SELECT VESSELID, COMMODITYCODE, LOADTYPE FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$order_ARV = $short_term_data_row['VESSELID'];
		$order_comm = $short_term_data_row['COMMODITYCODE'];
		switch($short_term_data_row['LOADTYPE']){
			case "CUSTOMER LOAD":
				$load_status = "";
				break;
			case "REGRADE LOAD":
				$load_status = "R";
				break;
			case "HOSPITAL LOAD":
				$load_status = "H";
				break;
			default:
				$load_status = "CALL OFFICE";
				break;
		}

		if($order_ARV == "440" || $order_ARV == "441"){
			$order_type = "domestic";
			$PL_table = $prefix."_DOMESTIC_PICKLIST";
		} else {
			$order_type = "export";
			$PL_table = $prefix."_PICKLIST";
		}

		$choice = "";

		while($choice != "X" && $choice != "N"){
			$order_detail = array();
			$order_det_high_size = array();
			$order_det_low_size = array();
			$order_det_ctn_ordered = array();
			$order_det_plt_ordered = array();
			$order_det_ctn_scanned = array();
			$order_det_plt_scanned = array();
			$order_det_weight = array();
			$order_index = 0;
	
			// populate above arrays with current order info
			$sql = "SELECT DOD.ORDERDETAILID, SIZEHIGH THE_HIGH, SIZELOW THE_LOW, ORDERQTY THE_CASES, DOD.WEIGHTKG,
					SUM(PALLETQTY) THE_PALLETS
					FROM ".$prefix."_ORDERDETAIL DOD, ".$PL_table." DP
					WHERE DOD.ORDERNUM = '".$order_num."'
					AND DOD.ORDERNUM = DP.ORDERNUM(+)
					AND DOD.ORDERDETAILID = DP.ORDERDETAILID(+)
					AND DOD.SIZEHIGH >= DP.PICKLISTSIZE(+)
					AND DOD.SIZELOW <= DP.PICKLISTSIZE(+)
					GROUP BY DOD.ORDERDETAILID, ORDERQTY, SIZEHIGH, SIZELOW, WEIGHTKG
					ORDER BY ORDERDETAILID";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT4b)");
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$order_detail[$order_index] = $short_term_data_row['ORDERDETAILID'];
				$order_det_high_size[$order_index] = $short_term_data_row['THE_HIGH'];
				$order_det_low_size[$order_index] = $short_term_data_row['THE_LOW'];
				$order_det_ctn_ordered[$order_index] = $short_term_data_row['THE_CASES'];
				$order_det_plt_ordered[$order_index] = $short_term_data_row['THE_PALLETS'];
				$order_det_weight[$order_index] = $short_term_data_row['WEIGHTKG'];
				$order_index++;
			}

			// picklist size = 0 is the user's shorthand for "any" until we get out of Eprot2-clems.
			for($temp = 0; $temp < sizeof($order_detail); $temp++){
				$sql = "SELECT SUM(QTY_CHANGE) THE_CTN
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, ".$prefix."_ORDERDETAIL DCO
						WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CA.ORDER_NUM = '".$order_num."'
						AND CA.SERVICE_CODE = '6'
						AND TRIM(DCO.ORDERNUM) = CA.ORDER_NUM
						AND DCO.ORDERDETAILID = '".$order_detail[$temp]."'
						AND CT.CARGO_SIZE >= '".$order_det_low_size[$temp]."'
						AND CT.CARGO_SIZE <= '".$order_det_high_size[$temp]."'
						AND CT.WEIGHT = '".$order_det_weight[$temp]."'
						AND (CA.ACTIVITY_DESCRIPTION IS NULL OR (CA.ACTIVITY_DESCRIPTION != 'VOID' AND CA.ACTIVITY_DESCRIPTION != 'RETURN'))
						AND (CT.CARGO_SIZE, CT.EXPORTER_CODE) IN
							(SELECT TRIM(PICKLISTSIZE), TRIM(PACKINGHOUSE)
							FROM ".$PL_table."
							WHERE ORDERDETAILID = '".$order_detail[$temp]."'
							UNION
							SELECT CT.CARGO_SIZE, TRIM(PACKINGHOUSE)
							FROM ".$PL_table."
							WHERE ORDERDETAILID = '".$order_detail[$temp]."'
								AND TRIM(PICKLISTSIZE) = 0)";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT5b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$order_det_ctn_scanned[$temp] = 0;
				} else {
					$order_det_ctn_scanned[$temp] = $short_term_data_row['THE_CTN'];
				}

				$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLT
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
						WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CA.ORDER_NUM = '".$order_num."'
						AND CA.SERVICE_CODE = '6'
						AND (CA.ACTIVITY_DESCRIPTION IS NULL OR (CA.ACTIVITY_DESCRIPTION != 'VOID' AND CA.ACTIVITY_DESCRIPTION != 'RETURN'))
						AND (CT.CARGO_SIZE, CT.EXPORTER_CODE) IN
							(SELECT TRIM(PICKLISTSIZE), TRIM(PACKINGHOUSE)
							FROM ".$PL_table."
							WHERE ORDERDETAILID = '".$order_detail[$temp]."'
							UNION
							SELECT CT.CARGO_SIZE, TRIM(PACKINGHOUSE)
							FROM ".$PL_table."
							WHERE ORDERDETAILID = '".$order_detail[$temp]."'
								AND TRIM(PICKLISTSIZE) = 0)";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$order_det_plt_scanned[$temp] = 0;
				} else {
					$order_det_plt_scanned[$temp] = $short_term_data_row['THE_PLT'];
				}
				


			}
			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLT, SUM(QTY_CHANGE) THE_SUM
					FROM CARGO_ACTIVITY
					WHERE ORDER_NUM = '".$order_num."'
						AND SERVICE_CODE = '6'
						AND CUSTOMER_ID = '".$cust."'
						AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6a-a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT6a-b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$order_ctn_all_scanned = $short_term_data_row['THE_SUM'];
			$order_plt_all_scanned = $short_term_data_row['THE_PLT'];

			$sql = "SELECT SUM(ORDERQTY) THE_CTN FROM ".$prefix."_ORDERDETAIL WHERE ORDERNUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT7a-a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT7a-b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$order_ctn_req = $short_term_data_row['THE_CTN'];

			$sql = "SELECT SUM(PALLETQTY) THE_PLT FROM ".$PL_table." WHERE ORDERNUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT8a-a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT8a-b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$order_plt_req = $short_term_data_row['THE_PLT'];

			// order load complete.  Print to screen, and get scan.
			system("clear");
			echo "ORDER: ".$order_num."\n";
			echo "CURRENTLY:\n";
			echo "  ".(0 + $order_plt_all_scanned)." plts\n";
			echo "  ".(0 + $order_ctn_all_scanned)." ctns\n";
			echo "REQUESTED:\n";
			echo "  ".(0 + $order_plt_req)." plts\n";
			echo "  ".(0 + $order_ctn_req)." ctns\n";
/*
			system("clear");
			for($i=0; ($i < sizeof($order_detail) && $i < 6); $i++){
				// 6 entries max, to prevent screen overflow.  Yes, I remembered to add this comment.
				if($order_det_plt_ordered[$i] <= 0){
					$trail_data = "\n";
				} else {
					$trail_data = " ".(0 + $order_det_plt_scanned[$i])." of ".$order_det_plt_ordered[$i]."plt\n";
				}

				echo $order_det_low_size[$i]."-".$order_det_high_size[$i].$trail_data;
				echo " ".(0 + $order_det_ctn_scanned[$i])."ctn (max ".$order_det_ctn_ordered[$i].")\n";
			}
*/
			echo "N-NewOrder/ X-Exit\nOr Scan Barcode:\n";
			$choice = "";
			fscanf(STDIN, "%s\n", $choice);
			$choice = strtoupper($choice);
			if($choice != "N" && $choice != "X"){
				$Barcode = $choice;
				$Barcode = ten_to_full_BC($Barcode);
				$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

				$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_ARV."'";
//				echo $sql."\n";
//				fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT7a)");
				$ora_success = ora_exec($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nOrder Info\n(LT7b)");
				if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// Pallet incorrect / not in DB
					$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(LT8a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(LT8b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] <= 0){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**INVALID PALLET\nCONTACT INVENTORY**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} else {
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nMATCH CUST/ARV#\nFOR THIS ORDER**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					}
				} else {
									
					// pallet exists, validate...
					// commodity match
					if($select_row['COMMODITY_CODE'] != $order_comm){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nMATCH COMMODITY\nFOR THIS ORDER**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} elseif($select_row['DATE_RECEIVED'] == ""){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET NOT\nRECEIVED,\nCANNOT SHIP**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					/*} elseif(is_released_350($cust, $Barcode, $order_ARV) != ""){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**".is_released_350($cust, $Barcode, $order_ARV)."**", "bad");
						fscanf(STDIN, "%s\n", $junk); */
					/*}  elseif($load_status != $select_row['CARGO_STATUS']){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nMATCH STATUS (".$select_row['CARGO_STATUS'].")\nFOR THIS\nORDER(".$load_status.")**", "bad");
						fscanf(STDIN, "%s\n", $junk); */
					} elseif($select_row['QTY_IN_HOUSE'] <= 0){
						$sql = "SELECT ORDER_NUM FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID = '".$Barcode."' ORDER BY DATE_OF_ACTIVITY";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT8Ta)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT8Tb)");
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET HAS ZERO\nQTY IN HOUSE\nSHIPPED ON ORDER\n".$short_term_data_row['ORDER_NUM']."**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} /*elseif($select_row['MARK'] == "SHIPPED"){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET STATUS IS\nALREADY SHIPPED**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					}*/ elseif(strtoupper(substr($order_num, 0, 3)) == "BUR" && strtoupper($select_row['REMARK']) != "BURNAC"){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**BURNAC ORDER +\nNON-BURNAC PALLET**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} elseif(strtoupper(substr($order_num, 0, 3)) != "BUR" && strtoupper($select_row['REMARK']) == "BURNAC"){
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**BURNAC PALLET +\nNON-BURNAC ORDER**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} elseif((substr($order_num, 0, 2) == "LD" && $select_row['SUB_CUSTID'] != '1512') || 
									(substr($order_num, 0, 2) != "LD" && $select_row['SUB_CUSTID'] == '1512')) {
						fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\n**Orders starting with LD\ncan only accept\nOppenheimer pallets,\nand Oppenheimer\npallets  can only be on\nLD orders.\nDo Not Ship**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} else {
						// pallet passes basic tests, compare to order specifics and already-scanned qty's
						if($order_type == "domestic"){
							$check_sucess = validate_domestic_order($order_num, $Barcode, $cust, $order_ARV, $order_detail, $order_det_high_size, $order_det_low_size, $order_det_ctn_ordered, $order_det_ctn_scanned);
						} else {
//							$check_sucess = validate_export_order($order_num, $Barcode, $cust, $load_status, $order_ARV, $order_detail, $order_det_high_size, $order_det_low_size, $order_det_ctn_ordered, $order_det_ctn_scanned, $order_det_plt_ordered, $order_det_plt_scanned);
							$check_sucess = validate_export_order($order_num, $Barcode, $cust, $load_status, $order_ARV, $order_detail, $order_det_high_size, $order_det_low_size, $order_det_ctn_ordered, $order_det_ctn_scanned, $order_det_plt_ordered, $order_det_plt_scanned, $order_det_weight);
						}

						if($check_sucess != ""){
							fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\n".$check_sucess, "bad");
							fscanf(STDIN, "%s\n", $junk);
						} else {
							// pallet passes all checks
							// get other pallet data
							$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, WAREHOUSE_LOCATION, CARGO_SIZE, EXPORTER_CODE, CARGO_STATUS, QTY_IN_HOUSE FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP WHERE PALLET_ID = '".$Barcode."' AND CT.RECEIVER_ID = '".$cust."' AND CT.ARRIVAL_NUM = '".$order_ARV."' AND CUSP.CUSTOMER_ID = CT.RECEIVER_ID AND COMP.COMMODITY_CODE = CT.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT9a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT9b)");
							ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

							$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
							$qty_to_ship = $short_term_data_row['QTY_IN_HOUSE'];
							$date_rec = $short_term_data_row['THE_DATE'];
							$cargo_size = $short_term_data_row['CARGO_SIZE'];
							$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
							$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
							$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
//							$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
							$qty_ship_dmg = 0;
							$pkg_hse = $short_term_data_row['EXPORTER_CODE'];
							$status = $short_term_data_row['CARGO_STATUS'];

							$sub_choice = "noodles";
							$accept_pallet = true;
							while($sub_choice != "" && $sub_choice != "X"){
								$act_num = get_max_activity_num($cust, $Barcode, $order_ARV);

								system("clear");
								echo $disp_barcode."\n";
								echo "Cust: ".$custname."\n";
								echo "Comm: ".$commname."\n";
//								echo "LR: ".$Arrival."\n";
								echo "Vesl: ".$vesname."\n";
//								echo "Loc: ".$loc."\n";
//								echo "Variety: ".$variety."\n";
								echo "Size: ".$cargo_size." PKG: ".$pkg_hse."\n";
								echo "Status: ".$status."\n";
								echo "DMG TO SHIP: ".$qty_ship_dmg."\n";
								echo "QTY TO SHIP: ".$qty_to_ship."\n\n";
								echo "C=CHNG QTY SHPD\nD=CHNG DMG SHPD\nEnter=OK X=Exit\n";
								$sub_choice = "";
								fscanf(STDIN, "%s\n", $sub_choice);
								$sub_choice = strtoupper($sub_choice);
								if($sub_choice == "C"){
									system("clear");
									echo "QTY to ship: ".$qty_to_ship."\n";
									echo "MAX Available: ".$qty_in_house."\n\n";
									echo "New QTY to Ship:\n";
									$new_qty = "";
									fscanf(STDIN, "%s\n", $new_qty);
									$new_qty = strtoupper($new_qty);
									if(is_numeric($new_qty)){
										if($new_qty <= $qty_in_house && $new_qty >= 0 && $new_qty >= $qty_ship_dmg){
											$qty_to_ship = $new_qty;
										}
									}
								} elseif($sub_choice == "X"){
									$accept_pallet = false;
								} elseif($sub_choice == "D"){
									system("clear");
									echo "DMG QTY to ship: ".$qty_ship_dmg."\n";
									echo "MAX Available: ".$qty_in_house."\n\n";
									echo "New DMG QTY to Ship\n";
									$new_qty = "";
									fscanf(STDIN, "%s\n", $new_qty);
									$new_qty = strtoupper($new_qty);
									if(is_numeric($new_qty)){
										if($qty_ship_dmg <= $qty_in_house && $qty_ship_dmg >= 0 && $qty_ship_dmg <= $new_qty){
											$qty_ship_dmg = $new_qty;
										}
									}
								}
							}

							if($accept_pallet){
								if(!is_max_activity_num($act_num, $cust, $Barcode, $order_ARV)){
									fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$accept_pallet = false;
								}
							}

							// if not cancelled, then let's do this
							if($accept_pallet){
/*								$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY
										WHERE PALLET_ID = '".$Barcode."'
										AND ARRIVAL_NUM = '".$order_ARV."'
										AND CUSTOMER_ID = '".$cust."'";
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT10a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT10b)");
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$new_act_num = $short_term_data_row['THE_MAX'] + 1;
*/
								$sql = "SELECT QTY_THRESHOLD FROM MINIMUM_INHOUSE_THRESHOLD
										WHERE COMMODITY_TYPE = 'CLEMENTINES'";
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nThreshold Info\n(LT19a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nThreshold Info\n(LT19b)");
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$min_threshold = $short_term_data_row['QTY_THRESHOLD'];

								$sql = "UPDATE CARGO_TRACKING
										SET QTY_IN_HOUSE = (QTY_IN_HOUSE - ".$qty_to_ship.")";
//								if($qty_to_ship >= 5){
								if(($qty_in_house - $qty_to_ship) >= $min_threshold){
									$sql .= ", MARK = 'AT POW'";
								} else {
									$sql .= ", MARK = 'SHIPPED'";
								}
								$sql .=	" WHERE PALLET_ID = '".$Barcode."'
										AND ARRIVAL_NUM = '".$order_ARV."'
										AND RECEIVER_ID = '".$cust."'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT11a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT11b)");

								// only want the "first" shipment, so shipouttime is null
								$sql = "UPDATE CARGO_TRACKING_EXT
										SET SHIPOUTTIME = SYSDATE
										WHERE PALLET_ID = '".$Barcode."'
										AND ARRIVAL_NUM = '".$order_ARV."'
										AND RECEIVER_ID = '".$cust."'
										AND SHIPOUTTIME IS NULL";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT12a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT12b)");

								$sql = "INSERT INTO CARGO_ACTIVITY
										(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, ORDER_NUM, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, BATCH_ID, QTY_LEFT)
										VALUES
										('".($act_num + 1)."',
										'6',
										'".$qty_to_ship."',
										'".$emp_no."',
										'".$cust."',
										'".$order_num."',
										SYSDATE,
										'".$Barcode."',
										'".$order_ARV."',
										'".$qty_ship_dmg."',
										'".($qty_in_house - $qty_to_ship)."')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT13a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT13b)");
/*
								// new as of Nov 2014 season
								$check_clr_fail = is_released_350($cust, $Barcode, $order_ARV);
								if($check_clr_fail != ""){
									$check_clr_fail = str_replace("\\n", " ", $check_clr_fail);
									$success = 'N';
								} else {
									$check_clr_fail = "";
									$success = 'Y';
								}
								$sql = "INSERT INTO CLR_SCAN_VALIDITY_LOG
											(PALLET_ID,
											CUSTOMER_ID,
											ARRIVAL_NUM,
											SCAN_TIME,
											SUCCESS,
											FAIL_REASON)
										VALUES
											('".$Barcode."',
											'".$cust."',
											'".$order_ARV."',
											SYSDATE,
											'".$success."',
											'".$check_clr_fail."')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT14a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(LT14b)");

								// tag this combo into CLR_TRUCK_MAIN_JOIN if needed.
								// WARNING:  big code chunk ahead.
								$bol = "";
								$cont = "";
								$clr_key = "";
								$truck_ID = "";
								$nofind_sql = "";
								$sql = "SELECT BOL, NVL(CONTAINER_ID, 'NC') THE_CONT
										FROM CARGO_TRACKING
										WHERE RECEIVER_ID = '".$cust."'
											AND PALLET_ID = '".$Barcode."'
											AND ARRIVAL_NUM = '".$order_ARV."'";
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT15a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT15b)");
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$bol = $short_term_data_row['BOL'];
								$cont = $short_term_data_row['THE_CONT'];
								if($nofind_sql == "" && ($bol == "" || $cont == "")){
									$nofind_sql = $sql;
								}
								$sql = "SELECT CLR_KEY
										FROM CLR_MAIN_DATA
										WHERE ARRIVAL_NUM = '".$order_ARV."'
											AND BOL_EQUIV = '".$bol."'
											AND CONTAINER_NUM = '".$cont."'";
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(LT16a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(LT16b)");
							//	echo $sql."\n";
							//	fscanf(STDIN, "%s\n", $SuperID);
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$clr_key = $short_term_data_row['CLR_KEY'];
								if($nofind_sql == "" && $clr_key == ""){
									$nofind_sql = $sql;
								}
								$sql = "SELECT PORT_ID
										FROM CLR_TRUCK_LOAD_RELEASE
										WHERE CLEM_ORDER_NUM = '".$order_num."'";
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT17a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT17b)");
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								$truck_ID = $short_term_data_row['PORT_ID'];
								if($nofind_sql == "" && $truck_ID == ""){
									$nofind_sql = $sql;
								}
								$sql = "SELECT COUNT(*) THE_COUNT
										FROM CLR_TRUCK_MAIN_JOIN
										WHERE TRUCK_PORT_ID = '".$truck_ID."'
											AND MAIN_CLR_KEY = '".$clr_key."'";
								$ora_success = ora_parse($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT18a)");
								$ora_success = ora_exec($short_term_data_cursor, $sql);
								database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT18b)");
								ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
								if($short_term_data_row['THE_COUNT'] <= 0 && $clr_key != "" && $truck_ID != ""){
									$sql = "INSERT INTO CLR_TRUCK_MAIN_JOIN
												(TRUCK_PORT_ID,
												MAIN_CLR_KEY)
											VALUES
												('".$truck_ID."',
												'".$clr_key."')";
									$ora_success = ora_parse($short_term_data_cursor, $sql);
									database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT19a)");
									$ora_success = ora_exec($short_term_data_cursor, $sql);
									database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT19b)");
								} elseif($short_term_data_row['THE_COUNT'] >= 1 && ($clr_key == "" || $truck_ID == "")){
									$sql = "INSERT INTO CLR_PALLET_NOFIND_JOINS
												(PALLET_ID,
												RECEIVER_ID,
												ARRIVAL_NUM,
												BOL,
												CONTAINER_NUM,
												FOUND_CLR_TRUCKKEY,
												FOUND_CLR_MAINKEY,
												NOFIND_SQL,
												SCANNERTYPE,
												SCANNED_ON)
											VALUES
												('".$Barcode."',
												'".$cust."',
												'".$order_ARV."',
												'".$bol."',
												'".$cont."',
												'".$truck_ID."',
												'".$clr_key."',
												'".str_replace("'", "`", $nofind_sql)."',
												'CLEMENTINE',
												SYSDATE)";
									$ora_success = ora_parse($short_term_data_cursor, $sql);
									database_check($ora_success, "Cannot Save\nBackup\n(LT19-2a)");
									$ora_success = ora_exec($short_term_data_cursor, $sql);
									database_check($ora_success, "Cannot Save\nBackup\n(LT19-2b)");
								}

*/

								// transaction complete.
								ora_commit($rf_conn);
								fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n\nSHIPPED!");
								fscanf(STDIN, "%s\n", $junk);
							}
						}
					}
				}
			}
		}

		if($choice == "X"){
			$continue_function = false;
		}

		if($choice == "N"){
			// new order, do nothing and let loop restart
		}
	}
}

function no_picklist_Load_Truck($CID, $cust){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	// $continue_function = true;   --- WE ARENT DOING THIS.  The "continue_function" of the calling routine is responsible for function restart, not here.

	while($order_num == ""){
		fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.");
		echo "Order #:\n";
		fscanf(STDIN, "%s\n", $order_num);
		$order_num = strtoupper($order_num);
		if($order_num == "X"){
			return;
		}
		if(!S5_validity_check($order_num)){
			fresh_screen("CLEMENTINE\nTruck Out\nEnter X to exit.\n\n**ORDER# CAN ONLY\nBE ALPHANUMERIC**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$order_num = "";
		}
	}

	$total_cases = 0;
	$total_pallets = 0;
	// get pallets and cases on order, including partials
	$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT1b)");
	while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
			$total_cases += $short_term_data_row['THE_SUM'];
			$total_pallets++;
		}
	}

	fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.");
	echo "Cust: ".$cust."\n";
	echo "Ord#: ".$order_num."\n";
	echo $total_cases." in ".$total_pallets." plts\n\n";
	echo "Barcode:\n";
	$Barcode = "";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while($Barcode != "X"){
		$Barcode = ten_to_full_BC($Barcode);
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

		$continue_pallet = true;

		$Arrival = "";
		$Commodity = "";

		if($continue_pallet){
			// check if any pallets, exactly 1 pallet, or more than 1 pallet still in house with this barcode
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				// no pallets in house
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT13a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT13b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] == 0){
						// no pallet in DB
						fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nBC: ".$disp_barcode."\nPALLET NOT\nIN DATABASE.\n\nCONTACT INVENTORY", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					} else {
						// bad customer
						fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nBC: ".$disp_barcode."\nPALLET DOES NOT\nBELONG TO CUST\n#".$cust."-\nDO NOT SHIP", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				} else {
					// pallet already "out" somehow
					$sql = "SELECT NVL(ORDER_NUM, SUBSTR(ACTIVITY_DESCRIPTION, 21)) THE_DESC FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE IN ('6', '17') ORDER BY ACTIVITY_NUM DESC";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT4a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT4b)");
					if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nBC: ".$disp_barcode."\nPALLET NOT\nAVAILABLE.\n\nCONTACT INVENTORY\nFOR DETAILS.", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					} else {
						fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nBC: ".$disp_barcode."\nPALLET ALREADY\nSHIPPED\n(".$short_term_data_row['THE_DESC']."),\n\nPress Enter", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}
			} elseif($short_term_data_row['THE_COUNT'] == 1){
				// one pallet in house
				$sql = "SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND QTY_IN_HOUSE > 0";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			} else {
				// there is more than 1 in house for this customer (I.E. different vessels)
				$continue_pallet = Select_Duplicate_Pallet($Barcode, $cust, $Arrival, $Commodity, $junk);
			}
		}

		if($continue_pallet){
			if(!validate_customer_to_scannertype($cust, $scanner_type, "Load_Truck")){
				fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nCust: ".$cust."\n**BARCODE NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
		}

		if($continue_pallet){
			// pallet passes all checks
			// get other pallet data
			$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, WAREHOUSE_LOCATION, CARGO_SIZE, EXPORTER_CODE, CARGO_STATUS, QTY_IN_HOUSE FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP WHERE PALLET_ID = '".$Barcode."' AND CT.RECEIVER_ID = '".$cust."' AND CT.ARRIVAL_NUM = '".$Arrival."' AND CUSP.CUSTOMER_ID = CT.RECEIVER_ID AND COMP.COMMODITY_CODE = CT.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT6a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT6b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
			$qty_to_ship = $short_term_data_row['QTY_IN_HOUSE'];
			$date_rec = $short_term_data_row['THE_DATE'];
			$cargo_size = $short_term_data_row['CARGO_SIZE'];
			$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
			$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
			$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
//							$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
			$qty_ship_dmg = 0;
			$pkg_hse = $short_term_data_row['EXPORTER_CODE'];
			$status = $short_term_data_row['CARGO_STATUS'];
		}

		if($continue_pallet){
			if($date_rec == "NONE"){
				fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n**PALLET NOT\nRECEIVED,\nCANNOT SHIP**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
		}

		// minimal checks are done.  Accept pallet for shipping.
		if($continue_pallet){
			$sub_choice = "noodles";
			$accept_pallet = true;
			while($sub_choice != "" && $sub_choice != "X"){
				$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

				system("clear");
				echo $disp_barcode."\n";
				echo "Cust: ".$custname."\n";
				echo "Comm: ".$commname."\n";
//								echo "LR: ".$Arrival."\n";
				echo "Vesl: ".$vesname."\n";
//								echo "Loc: ".$loc."\n";
//								echo "Variety: ".$variety."\n";
				echo "Size: ".$cargo_size." PKG: ".$pkg_hse."\n";
//				echo "Status: ".$status."\n";
				echo "DMG TO SHIP: ".$qty_ship_dmg."\n";
				echo "QTY TO SHIP: ".$qty_to_ship."\n\n";
				echo "C=CHNG QTY SHPD\nD=CHNG DMG SHPD\nEnter=OK X=Exit\n";
				$sub_choice = "";
				fscanf(STDIN, "%s\n", $sub_choice);
				$sub_choice = strtoupper($sub_choice);
				if($sub_choice == "C"){
					system("clear");
					echo "QTY to ship: ".$qty_to_ship."\n";
					echo "MAX Available: ".$qty_in_house."\n\n";
					echo "New QTY to Ship:\n";
					$new_qty = "";
					fscanf(STDIN, "%s\n", $new_qty);
					$new_qty = strtoupper($new_qty);
					if(is_numeric($new_qty)){
						if($new_qty <= $qty_in_house && $new_qty >= 0 && $new_qty >= $qty_ship_dmg){
							$qty_to_ship = $new_qty;
						}
					}
				} elseif($sub_choice == "X"){
					$accept_pallet = false;
				} elseif($sub_choice == "D"){
					system("clear");
					echo "DMG QTY to ship: ".$qty_ship_dmg."\n";
					echo "MAX Available: ".$qty_in_house."\n\n";
					echo "New DMG QTY to Ship\n";
					$new_qty = "";
					fscanf(STDIN, "%s\n", $new_qty);
					$new_qty = strtoupper($new_qty);
					if(is_numeric($new_qty)){
						if($qty_ship_dmg <= $qty_in_house && $qty_ship_dmg >= 0 && $qty_ship_dmg <= $new_qty){
							$qty_ship_dmg = $new_qty;
						}
					}
				}
			}

			if($accept_pallet){
				if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
					fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$accept_pallet = false;
				}
			}

			// if not cancelled, then let's do this
			if($accept_pallet){
/*				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$Arrival."'
						AND CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT10a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT10b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$new_act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				$sql = "SELECT QTY_THRESHOLD FROM MINIMUM_INHOUSE_THRESHOLD
						WHERE COMMODITY_TYPE = 'CLEMENTINES'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nThreshold Info\n(LT19a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nThreshold Info\n(LT19b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$min_threshold = $short_term_data_row['QTY_THRESHOLD'];

				$sql = "UPDATE CARGO_TRACKING
						SET QTY_IN_HOUSE = (QTY_IN_HOUSE - ".$qty_to_ship.")";
						if(($qty_in_house - $qty_to_ship) >= $min_threshold){
							$sql .= ", MARK = 'AT POW'";
						} else {
							$sql .= ", MARK = 'SHIPPED'";
						}
						$sql .= " WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$Arrival."'
						AND RECEIVER_ID = '".$cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT11a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT11b)");

				// only want the "first" shipment, so shipouttime is null
				$sql = "UPDATE CARGO_TRACKING_EXT
						SET SHIPOUTTIME = SYSDATE
						WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$Arrival."'
						AND RECEIVER_ID = '".$cust."'
						AND SHIPOUTTIME IS NULL";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT12a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT12b)");

				$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, ORDER_NUM, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, BATCH_ID, QTY_LEFT)
						VALUES
						('".($act_num + 1)."',
						'6',
						'".$qty_to_ship."',
						'".$emp_no."',
						'".$cust."',
						'".$order_num."',
						SYSDATE,
						'".$Barcode."',
						'".$Arrival."',
						'".$qty_ship_dmg."',
						'".($qty_in_house - $qty_to_ship)."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT13a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT13b)");
/*
				// new as of Nov 2014 season
				$check_clr_fail = is_released_350($cust, $Barcode, $Arrival);
				if($check_clr_fail != ""){
					$check_clr_fail = str_replace("\\n", " ", $check_clr_fail);
					$success = 'N';
				} else {
					$check_clr_fail = "";
					$success = 'Y';
				}
				$sql = "INSERT INTO CLR_SCAN_VALIDITY_LOG
							(PALLET_ID,
							CUSTOMER_ID,
							ARRIVAL_NUM,
							SCAN_TIME,
							SUCCESS,
							FAIL_REASON)
						VALUES
							('".$Barcode."',
							'".$cust."',
							'".$Arrival."',
							SYSDATE,
							'".$success."',
							'".$check_clr_fail."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT14a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Cannot Update\nPallet Info\n(LT14b)");

				// tag this combo into CLR_TRUCK_MAIN_JOIN if needed.
				// WARNING:  big code chunk ahead.
				$bol = "";
				$cont = "";
				$clr_key = "";
				$truck_ID = "";
				$nofind_sql = "";
				$sql = "SELECT BOL, NVL(CONTAINER_ID, 'NC') THE_CONT
						FROM CARGO_TRACKING
						WHERE RECEIVER_ID = '".$cust."'
							AND PALLET_ID = '".$Barcode."'
							AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT15a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nBOL/CONT\n(LT15b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$bol = $short_term_data_row['BOL'];
				$cont = $short_term_data_row['THE_CONT'];
				if($nofind_sql == "" && ($bol == "" || $cont == "")){
					$nofind_sql = $sql;
				}
				$sql = "SELECT CLR_KEY
						FROM CLR_MAIN_DATA
						WHERE ARRIVAL_NUM = '".$Arrival."'
							AND BOL_EQUIV = '".$bol."'
							AND CONTAINER_NUM = '".$cont."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(LT16a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(LT16b)");
			//	echo $sql."\n";
			//	fscanf(STDIN, "%s\n", $SuperID);
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$clr_key = $short_term_data_row['CLR_KEY'];
				if($nofind_sql == "" && $clr_key == ""){
					$nofind_sql = $sql;
				}
				$sql = "SELECT PORT_ID
						FROM CLR_TRUCK_LOAD_RELEASE
						WHERE TRIM(CLEM_ORDER_NUM) = '".$order_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nTruck Activity\n(LT17a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nTruck Activity\n(LT17b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$truck_ID = $short_term_data_row['PORT_ID'];
				if($nofind_sql == "" && $truck_ID == ""){
					$nofind_sql = $sql;
				}
				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CLR_TRUCK_MAIN_JOIN
						WHERE TRUCK_PORT_ID = '".$truck_ID."'
							AND MAIN_CLR_KEY = '".$clr_key."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(LT18a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nRelease Activity\n(LT18b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0 && $clr_key != "" && $truck_ID != ""){
					$sql = "INSERT INTO CLR_TRUCK_MAIN_JOIN
								(TRUCK_PORT_ID,
								MAIN_CLR_KEY)
							VALUES
								('".$truck_ID."',
								'".$clr_key."')";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Save\nCargo Join\n(LT19a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Save\nCargo Join\n(LT19b)");
				} elseif($short_term_data_row['THE_COUNT'] >= 1 && ($clr_key == "" || $truck_ID == "")){
					$sql = "INSERT INTO CLR_PALLET_NOFIND_JOINS
								(PALLET_ID,
								RECEIVER_ID,
								ARRIVAL_NUM,
								BOL,
								CONTAINER_NUM,
								FOUND_CLR_TRUCKKEY,
								FOUND_CLR_MAINKEY,
								NOFIND_SQL,
								SCANNERTYPE,
								SCANNED_ON)
							VALUES
								('".$Barcode."',
								'".$cust."',
								'".$Arrival."',
								'".$bol."',
								'".$cont."',
								'".$truck_ID."',
								'".$clr_key."',
								'".str_replace("'", "`", $nofind_sql)."',
								'CLEMENTINE',
								SYSDATE)";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Save\nBackup\n(LT19-2a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Save\nBackup\n(LT19-2b)");
				}
*/



				// transaction complete.
				ora_commit($rf_conn);
				fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.\n\nORDER: ".$order_num."\nBC: ".$disp_barcode."\n\nSHIPPED!");
				fscanf(STDIN, "%s\n", $junk);
			}
		}

		$total_cases = 0;
		$total_pallets = 0;
		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(npLT1b)");
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
				$total_cases += $short_term_data_row['THE_SUM'];
				$total_pallets++;
			}
		}

		fresh_screen("CLEMENTINE\n  (NON-DC)\nTruck Out\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}

function Recoup($CID){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.");
	$Barcode = "";
	echo "Barcode:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$continue = true;
		$Cust = "";
		$Arrival = "";
		$Commodity = "";
		$qty_in_house = 0;
		$qty_rec = 0;

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReC1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReC1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet does not exist
			fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($short_term_data_row['THE_COUNT'] > 1){
			// multiple pallet
			// PLEASE NOTE:  qty_in_house
			$continue = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Commodity, $qty_rec);
		} else {
			// single pallet, get info
			$sql = "SELECT ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, QTY_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReC2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReC2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Cust = $short_term_data_row['RECEIVER_ID'];
			$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			$Commodity = $short_term_data_row['COMMODITY_CODE'];
			$qty_rec = $short_term_data_row['QTY_RECEIVED'];
		}

		if(!validate_customer_to_scannertype($Cust, $scanner_type, "Recoup")){
			fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($Commodity != "5606"){
			fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**CAN ONLY RECOUP\nDOMESTIC CARGO**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}

		// get more data...
		if($continue){
			$sql = "SELECT QTY_IN_HOUSE, VESSEL_NAME, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE 
					FROM CARGO_TRACKING CT, VESSEL_PROFILE VP 
					WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) 
						AND PALLET_ID = '".$Barcode."' 
						AND CT.ARRIVAL_NUM = '".$Arrival."' 
						AND RECEIVER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReC3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReC3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
			$vesname = $short_term_data_row['VESSEL_NAME'];
			if($short_term_data_row['THE_DATE'] == "NONE"){
				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT RECOUP\nUNRECEIVED CARGO**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		while($continue){
			$act_num = get_max_activity_num($Cust, $Barcode, $Arrival);

			// do this if they did not "cancel" or "error" out of above
			fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.");
			echo "BC: ".$Barcode."\n\n";
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "CUST: ".$Cust."\n";
			echo "Rcv: ".$qty_rec."  Inhs: ".$qty_in_house."\n";
			echo "New In-House QTY: \n";
			$new_qty = "";
			fscanf(STDIN, "%s\n", $new_qty);
			$new_qty = strtoupper($new_qty);

			

			if($new_qty == "X" || $new_qty == ""){
				// cancel
				$continue = false;
			} elseif(!is_numeric($new_qty)){
				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY IN HOUSE\nMUST BE\nA NUMBER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif($new_qty < 0) {
				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY IN HOUSE\nCANNOT BE LESS\nTHAN ZERO**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif($new_qty != round($new_qty)){
				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY IN HOUSE\nMUST BE A\nWHOLE NUMBER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif(!is_max_activity_num($act_num, $Cust, $Barcode, $Arrival)){
				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} elseif(($new_qty - $qty_in_house) > 0){
				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT ADD CARTONS\nRecoup is for Losing\nDamaged Cartons Only**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} else {
				// entry is valid, let's do this!
				$qty_change = $new_qty - $qty_in_house;
/*				
				// auto-receive if necessary (not that it ever should be, but...)
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to Auto\nReceive Pallet\n(ReC5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to Auto\nReceive Pallet\n(ReC5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_DATE'] == "NONE"){
					Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, "ship");
				} */
/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReC4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReC4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
*/

				// update CARGO_TRACKING
				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = '".$new_qty."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReC6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReC6b)");

				// add CARGO_ACTIVITY record
				$sql = "INSERT INTO CARGO_ACTIVITY 
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
						('".($act_num + 1)."', '9', '".$qty_change."', '".$emp_no."', '".$Cust."', SYSDATE, '".$Barcode."', '".$Arrival."', '".$new_qty."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReC7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReC7b)");
/*
				if($qty_change > 0){
					$mailSubject = "Pallet recouped with Positive QTY_IN_HOUSE change\r\n";
					$body = "Barcode:  ".$Barcode."\nLR#:  ".$Arrival."\nCust:  ".$Cust."\n\nRecouped from a value of ".$qty_in_house." to ".$new_qty."\r\n";
//					mail("lstewart@port.state.de.us,awalter@port.state.de.us\r\n", $mailSubject, $body, $mailHeaders);
					mail($inv_mail_list, $mailSubject, $body, $mailHeaders);
					$sql = "INSERT INTO SCANNER_EMAIL_LOG (FROM_SCANNER_TYPE, DATE_SENT, EMAIL_TO_LIST, EMAIL_BODY, EMAIL_SUBJECT) VALUES ('Chilean', SYSDATE, '".$inv_mail_list."', '".$body."', '".$mailSubject."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Email\n(ReC8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Email\n(ReC8b)");
					ora_commit($rf_conn);

				}
*/
				// make sure we get out of the while loop, since change was made...
				$continue = false;
				ora_commit($rf_conn);

				fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\nQTY IN HOUSE\nCHANGED TO\n".$new_qty);
				fscanf(STDIN, "%s\n", $junk);		
			}
		}
		fresh_screen("CLEMENTINE\nRecoup\nEnter X to exit.");
		$Barcode = "";
		echo "Barcode:";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}


function Trans_Owner($CID){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$cust_from = "";
	$cust_to = "";
	$wing = "";
	$order_num = "";

	// get and validate cust_from #
	while($cust_from == "" || $cust_from == "Invalid"){
		fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.");
		if($cust_from != ""){
			echo "Invalid Cust #\n";
		}
		echo "From Customer#:\n";
		fscanf(STDIN, "%s\n", $cust_from);
		$cust_from = strtoupper($cust_from);
		if($cust_from == "X"){
			return;
		}

		if(!is_numeric($cust_from)){
			$cust_from = "Invalid";
		} elseif(!validate_customer_to_scannertype($cust_from, $scanner_type, "Trans_Owner")){
			fresh_screen("CLEMENTINE\nTrans_Owner\nEnter X to exit.\n\nCust: ".$cust_from."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$cust_from = "Invalid";
		} else {
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust_from."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(TO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(TO2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$cust_from = "Invalid";
			} 
		}
	}

	// get and validate cust_to #
	while($cust_to == "" || $cust_to == "Invalid"){
		fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.");
		if($cust_to != ""){
			echo "Invalid Cust #\n";
		}
		echo "To Customer#:\n";
		fscanf(STDIN, "%s\n", $cust_to);
		$cust_to = strtoupper($cust_to);
		if($cust_to == "X"){
			return;
		}

		if(!is_numeric($cust_to)){
			$cust_to = "Invalid";
		} elseif(!validate_customer_to_scannertype($cust_to, $scanner_type, "Trans_Owner")){
			fresh_screen("CLEMENTINE\nTrans_Owner\nEnter X to exit.\n\nCust: ".$cust_to."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$cust_to = "Invalid";
		} else {
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust_to."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(TO3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(TO3b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$cust_to = "Invalid";
			}
		}
	}

	// get and validate wing
	while($wing == "" || $wing == "Invalid"){
		fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.");
		if($wing != ""){
			echo "Invalid Wing\n";
		}
		echo "Wing:\n";
		fscanf(STDIN, "%s\n", $wing);
		$wing = strtoupper($wing);
		if($wing == "X"){
			return;
		}

		if($wing != "A" && $wing != "B" && $wing != "C" && $wing != "D" && $wing != "E" && $wing != "F" && $wing != "G"){
			$wing = "Invalid";
		} 
	}

	// get Order
	while($order_num == ""){
		fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.");
		echo "Order #:";
		fscanf(STDIN, "%s\n", $order_num);
		$order_num = strtoupper($order_num);
		if($order_num == "X"){
			return;
		}
		if(!S5_validity_check($order_num)){
			fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\n**ORDER# CAN ONLY\nBE ALPHANUMERIC**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$order_num = "";
		}
	}

	$sql = "SELECT SUM(QTY_CHANGE) THE_CASES, COUNT(*) THE_PALLETS FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust_from."' AND SERVICE_CODE = '11'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(TO30a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(TO30b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$total_plt = (0 + $short_term_data_row['THE_PALLETS']);
	$total_ctn = (0 + $short_term_data_row['THE_CASES']);

	fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.");
	echo "Order: ".$order_num."\n";
	echo "Cust (From): ".$cust_from."\n";
	echo "Cust (To): ".$cust_to."\n";
	echo "Wing: ".$wing."\n";
	echo $total_ctn." CTN; ".$total_plt." PLT\n";
	echo "Barcode:";
	$Barcode = "";
	while($Barcode == ""){
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}

	while($Barcode != "X"){
		$Barcode = ten_to_full_BC($Barcode);
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

		$Arrival = "";
		$Commodity = "";
		$qty_rec = "";
		$continue = true;
		$qty_dmg = "";

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND QTY_IN_HOUSE > 0";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO31-1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO31-1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet not available, figure out why
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO31a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO31b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				// pallet does not exist
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;				
			} else {
				// pallet not in house
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT IN\nHOUSE.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		} elseif($short_term_data_row['THE_COUNT'] > 1){
			// multiple pallet
			// PLEASE NOTE:  qty_in_house is not set here
			$continue = Select_Duplicate_Pallet($Barcode, $cust_from, $Arrival, $Commodity, $qty_rec);
		} else {
			// single pallet, get info
			$sql = "SELECT ARRIVAL_NUM, COMMODITY_CODE, QTY_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND QTY_IN_HOUSE > 0";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			$Commodity = $short_term_data_row['COMMODITY_CODE'];
//			$qty_rec = $short_term_data_row['QTY_RECEIVED'];
		}

		if($continue){
			if(!validate_comm_to_scannertype($Commodity, $scanner_type)){
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		if($continue){
			$sql = "SELECT DATE_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$Commodity."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO12b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['DATE_RECEIVED'] == ""){
//				Auto_Receive_Pallet($emp_no, $Barcode, $cust_from, $Arrival, $Commodity, "ship");
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**MUST BE RECEIVED\nBEFORE TRANSFER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		// make sure the DESTINATION combination of cust / LR# / pallet isnt taken
		if($continue){
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$order_num."' AND RECEIVER_ID = '".$cust_to."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO22a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO22b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] > 0){
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**CUST ".$cust_to." ALREADY\nHAS PLT ".$Barcode."\nFOR LR# ".$order_num."\nCONTACT INVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		// get current in-house qty
		if($continue){
			$sql = "SELECT QTY_IN_HOUSE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$Commodity."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO21a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO21b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
		}

		// ask for dmg qty if needed
		if($continue){
			$act_num = get_max_activity_num($cust_from, $Barcode, $Arrival);

			//$qty_dmg = "nope";
			while($qty_dmg != "X" && (!is_numeric($qty_dmg) || $qty_dmg < 0 || $qty_dmg > $qty_trans)){
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\nBC: ".$disp_barcode."\nCust: ".$cust_from);
				echo "QTY Damaged:\n";
				fscanf(STDIN, "%s\n", $qty_dmg);
				$qty_dmg = strtoupper($qty_dmg);
			}
			if($qty_dmg == "X"){
				$continue = false;
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$disp_barcode."\nCancelled", "bad");
				fscanf(STDIN, "%s\n", $junk);
			}
		}

		if($continue){
			if(!is_max_activity_num($act_num, $cust_from, $Barcode, $Arrival)){
				fresh_screen("CLEMENTINE\nTransfer Owner\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		if(!$continue){
			// sub-function cancelled the operation.  Said sub-fuction will handle the display.
			// fresh_screen("CHILEAN FRUIT\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$Barcode."\nCancelled");
		} else {
			// now, update current pallet with outgoing info
			$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 0, CARGO_STATUS = '".$wing."', MARK = 'TRANS' 
					WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust_from."' AND COMMODITY_CODE = '".$Commodity."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO8a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO8b)");

			// outgoing activity record
			$sql = "INSERT INTO CARGO_ACTIVITY
					(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, ACTIVITY_BILLED, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".($act_num + 1)."', '11', '".$qty_trans."', '".$emp_no."', '".$order_num."', '".$cust_from."', SYSDATE, '".$wing."', '".$Barcode."', '".$Arrival."', '0')";
//			echo $sql."\n";
//			fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO16a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO16b)");

			// and insert the new pallet to CARGO_TRACKING...
			$sql = "INSERT INTO CARGO_TRACKING 
						(COMMODITY_CODE,
						CARGO_DESCRIPTION,
						WAREHOUSE_LOCATION,
						DATE_RECEIVED, 
						QTY_RECEIVED, 
						RECEIVER_ID,
						QTY_IN_HOUSE,
						QTY_DAMAGED,
						FROM_SHIPPING_LINE,
						SHIPPING_LINE,
						FUMIGATION_CODE,
						EXPORTER_CODE,
						PALLET_ID,
						ARRIVAL_NUM,
						RECEIVING_TYPE,
						WEIGHT,
						WEIGHT_UNIT,
						BATCH_ID,
						BOL,
						DECK,
						HATCH,
						MARK,
						CARGO_SIZE,
						VARIETY,
						CHEP)
					(SELECT 
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						WAREHOUSE_LOCATION,
						SYSDATE,
						'".$qty_trans."',
						'".$cust_to."',
						'".$qty_trans."',
						'".$qty_dmg."',
						FROM_SHIPPING_LINE,
						SHIPPING_LINE,
						FUMIGATION_CODE,
						EXPORTER_CODE,
						'".$Barcode."',
						'".$order_num."',
						'F',
						WEIGHT,
						WEIGHT_UNIT,
						BATCH_ID,
						BOL,
						DECK,
						HATCH,
						'AT POW',
						CARGO_SIZE,
						VARIETY,
						CHEP
						FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."')";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO14a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO14b)");

			// and the new CARGO_ACTIVITY record.
			$sql = "INSERT INTO CARGO_ACTIVITY 
					(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, ACTIVITY_BILLED, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('1', '11', '".$qty_trans."', '".$emp_no."', '".$order_num."', '".$cust_to."', SYSDATE, '".$wing."', '".$Barcode."', '".$order_num."', '".$qty_trans."')";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO15a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO15b)");

			// 1 more step:  maintain the original ARV#.  so, get the original...
			// (note, we do this at the end to let the trigger that makes the CTAD record do it's job after the SQLs above)
			$sql = "SELECT NVL(ORIGINAL_ARRIVAL_NUM, 'NONE') THE_ARV FROM CARGO_TRACKING_ADDITIONAL_DATA
					WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust_from."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO25a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO25b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//			echo $sql;
//			fscanf(STDIN, "%s\n", $junk);
			if($short_term_data_row['THE_ARV'] != "NONE"){
				$orig_arv = $short_term_data_row['THE_ARV'];
			} else {
				$orig_arv = $Arrival;
			}
//			echo "orig - ";
//			fscanf(STDIN, "%s\n", $junk);
			// and then update the new record
			$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA SET ORIGINAL_ARRIVAL_NUM = '".$orig_arv."'
					WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_to."' AND ARRIVAL_NUM = '".$order_num."'";
//			echo $sql;
//			fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO26a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO26b)");
			
			ora_commit($rf_conn);
			$sql = "SELECT SUM(QTY_CHANGE) THE_CASES, COUNT(*) THE_PALLETS FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust_from."' AND SERVICE_CODE = '11'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO31a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO31b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_plt = (0 + $short_term_data_row['THE_PALLETS']);
			$total_ctn = (0 + $short_term_data_row['THE_CASES']);
//			$total_plt++;
//			$total_ctn += $qty_trans;
		}

		fresh_screen("CHILEAN FRUIT\nTransfer Owner\nEnter X to exit.");
		echo "Order: ".$order_num."\n";
		echo "Cust (From): ".$cust_from."\n";
		echo "Cust (To): ".$cust_to."\n";
		echo "Wing: ".$wing."\n";
		echo $total_ctn." CTN; ".$total_plt." PLT\n";
		echo "Barcode:";
		$Barcode = "";
		while($Barcode == ""){
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}




function Relocate($CID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.");
	$Barcode = "";
	echo "Barcode:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$Barcode = ten_to_full_BC($Barcode);
		$continue = true;
		$Cust = "";
		$Arrival = "";
		$Commodity = "";
		$qty_in_house = 0;
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReL1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReL1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet does not exist
			fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($short_term_data_row['THE_COUNT'] > 1){
			// multiple pallet
			$continue = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Commodity, $qty_in_house);
		} else {
			// single pallet, get info
			$sql = "SELECT CT.ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, VESSEL_NAME FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Cust = $short_term_data_row['RECEIVER_ID'];
			$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			$vesname = $short_term_data_row['VESSEL_NAME'];
			$Commodity = $short_term_data_row['COMMODITY_CODE'];
		}

		if($continue){
			$sql = "SELECT DATE_RECEIVED, QTY_IN_HOUSE, WAREHOUSE_LOCATION FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$Arrival."'
					AND RECEIVER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL7b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['DATE_RECEIVED'] == ""){
				// make sure it's already received, if not, tell scanner to use "receive" function
				fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT YET\nRECEIVED.  PLEASE\nSET LOCATION\nBY RECEIVING IT**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} elseif($short_term_data_row['QTY_IN_HOUSE'] == "" || $short_term_data_row['QTY_IN_HOUSE'] <= 0){
				// make sure it's still here
				fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT IN\nHOUSE.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} else {
				$cur_loc = $short_term_data_row['WAREHOUSE_LOCATION'];
			}
		}


		while($continue){
			$act_num = get_max_activity_num($Cust, $Barcode, $Arrival);

			// do this if they did not "cancel" or "error" out of above
			fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.");
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "CUST: ".$Cust."\n";
			echo "BC: ".$disp_barcode."\n\n";
			echo "Loc: ".$cur_loc."\n";
			echo "New Loc: \n";
			$loc = "";
			fscanf(STDIN, "%[^[]]\n", $loc);
			$loc = trim(strtoupper($loc));

			if($loc == "X" || $loc == ""){
				$continue = false;
			} elseif(!is_max_activity_num($act_num, $Cust, $Barcode, $Arrival)){
				fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} else {
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, NVL(WAREHOUSE_LOCATION, 'NONE') THE_LOC, QTY_IN_HOUSE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$prev_loc = $short_term_data_row['THE_LOC'];
				$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				// change the CARGO_TRACKING location
				$sql = "UPDATE CARGO_TRACKING SET WAREHOUSE_LOCATION = '".$loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL5a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL5b)");

				// add CARGO_ACTIVITY record
				$sql = "INSERT INTO CARGO_ACTIVITY 
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, ACTIVITY_DESCRIPTION, FROM_LOCATION) VALUES
						('".($act_num + 1)."', '2', '0', '".$emp_no."', '".$Cust."', SYSDATE, '".$Barcode."', '".$Arrival."', '".$qty_trans."', 'MOVE TO: ".$loc."', '".$prev_loc."')";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL6b)");
				
				ora_commit($rf_conn);
				fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.");
				echo $disp_barcode."\nMoved to ".$loc."\n\n";
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		$Barcode = "";
		fresh_screen("CLEMENTINE\nRelocate\nEnter X to exit.");
		echo "Next Barcode:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	}
}

function Info_Pallet(){
	global $rf_conn;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);


	fresh_screen("CLEMENTINE\nPallet Info\nEnter X to exit.");
	echo "Barcode:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){
		$Barcode = ten_to_full_BC($Barcode);
//		echo $Barcode."\n";
//		fscanf(STDIN, "%s\n", $junk);

		$total_pallets = 0;
		$pallet_info = array();
		$curr_pallet = 0;
		$continue = true;
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT RECEIVED YET') DATE_REC, NVL(SUBSTR(VESSEL_NAME, 0, 19), CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM THE_LR,
				SUBSTR(COMMODITY_NAME, 0, 15) THE_COMM, SUBSTR(CUSTOMER_NAME, 0, 15) THE_CUST, RECEIVER_ID, CT.ARRIVAL_NUM, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE,
				EXPORTER_CODE, CARGO_SIZE, CARGO_STATUS, WAREHOUSE_LOCATION
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
			WHERE CT.PALLET_ID = '".$Barcode."'
			AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
			AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
			AND COMP.COMMODITY_TYPE = 'CLEMENTINES'
			AND CT.ARRIVAL_NUM = TO_CHAR(VP.ARRIVAL_NUM(+))
			ORDER BY DATE_RECEIVED DESC NULLS LAST";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nInfo\n(IP1a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nInfo\n(IP1b)");
		if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fresh_screen("CLEMENTINE\nPallet Info\nEnter X to exit.\n\nNo Pallet with BC\n".$disp_barcode."\nIn System.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			// at least 1 pallet here
			// put all matching barcodes in an array
			do {
				$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_ACT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$select_row['RECEIVER_ID']."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND (SERVICE_CODE = '6' OR (SERVICE_CODE = '11' AND ACTIVITY_NUM > 1)) AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
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
				switch($select_row['CARGO_STATUS']){
					case "":
						$pallet_info[$total_pallets]["status"] = "GOOD";
					break;

					case "R":
						$pallet_info[$total_pallets]["status"] = "REGRADE";
					break;

					case "H":
						$pallet_info[$total_pallets]["status"] = "HOSPITAL";
					break;

					case "B":
						$pallet_info[$total_pallets]["status"] = "HOSP + REG";
					break;

					default:
						$pallet_info[$total_pallets]["status"] = "HUH?";
					break;
				}

				$pallet_info[$total_pallets]["cust"] = $select_row['THE_CUST'];
				$pallet_info[$total_pallets]["LR"] = $select_row['THE_VES'];
				$pallet_info[$total_pallets]["comm"] = $select_row['THE_COMM'];
				$pallet_info[$total_pallets]["loc"] = $select_row['WAREHOUSE_LOCATION'];
				$pallet_info[$total_pallets]["qty_rec"] = $select_row['QTY_RECEIVED'];
				$pallet_info[$total_pallets]["dmg"] = $select_row['QTY_DAMAGED'];
				$pallet_info[$total_pallets]["pkg_hse"] = $select_row['EXPORTER_CODE'];
				$pallet_info[$total_pallets]["size"] = $select_row['CARGO_SIZE'];
//				if($pallet_info[$total_pallets]["LR"] == $select_row['THE_LR']){
					$pallet_info[$total_pallets]["date_rec"] = $select_row['DATE_REC'];
//				} else {
//					$pallet_info[$total_pallets]["date_rec"] = "LR#".$select_row['THE_LR']." ".$select_row['DATE_REC'];
//				}
				$pallet_info[$total_pallets]["i_h"] = $select_row['QTY_IN_HOUSE'];

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
				echo $disp_barcode."\n";
				echo $pallet_info[$display_set_counter]["LR"]."\n";
				echo $pallet_info[$display_set_counter]["date_rec"]."\n";

				if($pallet_info[$display_set_counter]["date_rec"] == "NOT RECEIVED YET"){
					echo "\n";
				} elseif($pallet_info[$display_set_counter]["order_out"] != "STILL IN HOUSE"){
					echo "ORD#: ".$pallet_info[$display_set_counter]["order_out"]."\n";
				} else {
					echo $pallet_info[$display_set_counter]["order_out"]."\n";
				}

				echo $pallet_info[$display_set_counter]["date_act"]."\n";
				echo $pallet_info[$display_set_counter]["cust"]."\n";
				echo $pallet_info[$display_set_counter]["comm"]."\n";
				echo "RV:".$pallet_info[$display_set_counter]["qty_rec"]." Dm:".$pallet_info[$display_set_counter]["dmg"]." In:".$pallet_info[$display_set_counter]["i_h"]."\n";
				echo "Loc: ".$pallet_info[$display_set_counter]["loc"]."\n";
				echo "Status: ".$pallet_info[$display_set_counter]["status"]."\n";
				echo "Size: ".$pallet_info[$display_set_counter]["size"]." HSE: ".$pallet_info[$display_set_counter]["pkg_hse"]."\n";

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
			fresh_screen("CLEMENTINE\nPallet Info\nEnter X to exit.");
			echo "Barcode:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		} else{
			$Barcode = "X";
		}
	}
}

function Info_Outbound_Order(){
/*
* Gets listing of pallets on a given outbound order, and vessel name/carton count
*
*******************************************************************************/
	global $rf_conn;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$continue = true; // used to see if someone wants to exit the function, or just put in a new order

	while($continue){
		$cust = "";
		$order_num = "";

		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("CLEMENTINE\nTruckout Info\nEnter X to exit.");
			if($cust != ""){
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if($cust == "X"){
				return;
			}
			$cust = remove_badchars($cust);

			if(!is_numeric($cust)){
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(IO1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(IO1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		// get Order
		while($order_num == ""){
			fresh_screen("CLEMENTINE\nTruckout Info\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);
		}
				
		$total_pallets = 0;
		$total_cases = 0;
		$total_pallets_for_display = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IO2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IO2b)");
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
				$total_cases += $short_term_data_row['THE_SUM'];
				$total_pallets++;
			}
		}

		if($total_pallets == 0){
			// break if not valid
			fresh_screen("CLEMENTINE\nInbound Order Info\nEnter X to Exit.\n\nOrder: ".$order_num."\nCust: ".$cust."\nDOES NOT EXIST.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			$pallet_number = 0;
			$pallet_info = array();

			// valid order, start retrieving data...
			$sql = "SELECT PALLET_ID, DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE) THE_CHANGE, ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') ORDER BY PALLET_ID, ACTIVITY_NUM";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IO3a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IO3b)");

			while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// get vessel name if applicable (and pad with spaces)
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$select_row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nVessel Info\n(IO4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nVessel Info\n(IO4b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_info[$pallet_number]["LR"] = $select_row['ARRIVAL_NUM'];
					for($i = 0; $i < (15-strlen(substr($select_row['ARRIVAL_NUM'], 0, 15))); $i++){
						$pallet_info[$pallet_number]["LR"] .= " ";
					}
				} else {
					$pallet_info[$pallet_number]["LR"] = substr($short_term_data_row['VESSEL_NAME'], 0, 15);
					for($i = 0; $i < (15-strlen(substr($short_term_data_row['VESSEL_NAME'], 0, 15))); $i++){
						$pallet_info[$pallet_number]["LR"] .= " ";
					}
				}
				if(strlen($select_row['PALLET_ID']) >= 25){
					$pallet_info[$pallet_number]["barcode"] = substr($select_row['PALLET_ID'], -17);
				} else {
					$pallet_info[$pallet_number]["barcode"] = substr($select_row['PALLET_ID'], 0, 15);
				}
				$pallet_info[$pallet_number]["qty_change"] = $select_row['THE_CHANGE'];

				$pallet_number++;
			}

			$choice = "there is no spoon";
			$display_set_counter = 0; 
			$max_page = ceil($pallet_number / 4);

			while($choice != "X" && $choice != "R"){
				// DO NOT USE FRESHSCREEN FUNCTION HERE
				// we need all the screen space we can get.
				system("clear");
				echo $cust." / ".$order_num."\n";
				echo $total_cases." In ".$total_pallets." Plts\n";
				for($i = ($display_set_counter * 4); $i < ($display_set_counter * 4) + 4; $i++){
					if($pallet_info[$display_set_counter]["barcode"] != ""){ // only write to screen if has data
						echo "BC: ".$pallet_info[$i]["barcode"]."\n";
						echo $pallet_info[$i]["LR"]." ".$pallet_info[$i]["qty_change"]."\n";
					}
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
	}
}

function VoidOUT($CID){
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

	while($continue_function){ // in case they finish order and want to move to next one 
		// get and validate cust #
		$cust = "";
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.");
			if($cust != ""){
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if($cust == "X"){
				return;
			}
			$cust = remove_badchars($cust);

			if(!is_numeric($cust)){
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Vout1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Vout1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		$prefix = which_clem_tables($cust);

		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}
			
			$sql = "SELECT ORDERSTATUSID FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout13a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout13b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['ORDERSTATUSID'] == 9){
				echo "Order Marked As\nCompleted.\nCannot Void\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}

		}

		$total_cases = 0;
		$total_pallets = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) GROUP BY PALLET_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout3b)");
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
				$total_cases += $short_term_data_row['THE_SUM'];
				$total_pallets++;
			}
		}

		fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){
			$Barcode = ten_to_full_BC($Barcode);
			$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);
			$continue_pallet = true;

			$Arrival = "";
			$Commodity = "";

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if this pallet belongs to this customer
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout5a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout5b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nBELONG TO ".$cust."\nCHECK PALLET INFO**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '".$cust."' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout6b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// get  scanned pallet info
			if($continue_pallet){
				$sql = "SELECT NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE CT.PALLET_ID = '".$Barcode."' AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CT.RECEIVER_ID = '".$cust."' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) GROUP BY NVL(VESSEL_NAME, CT.ARRIVAL_NUM), CT.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout7a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout7b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_void = $short_term_data_row['THE_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
//				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
//				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}

			// AT THIS POINT, we have all the info we need to void.
			// display data, request confirmation
			if($continue_pallet){
				$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

				$sub_choice = "";
				while($sub_choice != "N" && $sub_choice != "Y"){
					system("clear");
					echo $disp_barcode."\n";
					echo "Vesl: ".$vesname."\n";
					echo "Out: ".$date_ship."\n";
					echo "Cust: ".$custname."\n";
					echo "Comm: ".$commname."\n";
//					echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
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
					fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// if void was confirmed, lets do it!
			if($continue_pallet){
				$sql = "UPDATE CARGO_TRACKING SET MARK = 'AT POW', QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_to_void." WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout8a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout8b)");

				$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout9a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout9b)");
/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout10a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout10b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
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
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout11a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout11b)");

				ora_commit($rf_conn);
				fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.");
				echo $qty_to_void." cartons\n";
				echo $disp_barcode."\n VOIDED FROM ORDER\n\n";
				fscanf(STDIN, "%s\n", $junk);
			}

			$total_cases = 0;
			$total_pallets = 0;

			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) GROUP BY PALLET_ID";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout12b)");
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
					$total_cases += $short_term_data_row['THE_SUM'];
					$total_pallets++;
				}
			}

			fresh_screen("CLEMENTINE\nVoid Outbound\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
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
		$cust = "";
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("CLEMENTINE\nVoid Out-ORDER\nEnter X to exit.");
			if($cust != ""){
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if($cust == "X"){
				return;
			}
			$cust = remove_badchars($cust);

			if(!is_numeric($cust)){
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Vouto1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Vouto1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		$prefix = which_clem_tables($cust);

		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("CLEMENTINE\nVoid Out-ORDER\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}		

			$sql = "SELECT ORDERSTATUSID FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto9a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto9b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['ORDERSTATUSID'] == 9){
				echo "Order Marked As\nCompleted.\nCannot Void\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}

		}

		// get pallets and cases on order, including partials
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(DISTINCT PALLET_ID) THE_PALLETS FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_cases = $short_term_data_row['THE_SUM'];
		$total_pallets = $short_term_data_row['THE_PALLETS'];

		fresh_screen("CLEMENTINE\nVoid Out-ORDER\nEnter X to exit.");
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
				$sql = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
				$ora_success = ora_parse($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto4a)");
				$ora_success = ora_exec($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vouto4b)");
				while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(Vouto5a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(Vouto5b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;

					$sql = "UPDATE CARGO_TRACKING SET MARK = 'AT POW', QTY_IN_HOUSE = QTY_IN_HOUSE + ".$select_row['QTY_CHANGE']." WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Vouto6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Vouto6b)");

					$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) AND ACTIVITY_NUM = '".$select_row['ACTIVITY_NUM']."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Vouto7a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Vouto7b)");

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
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Vouto8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Vouto8b)");

				}
				ora_commit($rf_conn);
				fresh_screen("CLEMENTINE\nVoid Out-ORDER\nEnter X to exit.");
				echo $order_num."\nVOIDED\n\n";
				fscanf(STDIN, "%s\n", $junk);

			break;
		}
	}
}

function Returns($CID, $type){
	// $type is either "dock" or "full"

	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$cust = "";
	$order_num = "";
	if($type == "full"){
		$service_code = "7";
	} elseif($type == "dock"){
		$service_code = "13";
	} else {
		return;
	}

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 
		$cust = "";
		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.");
			if($cust != ""){
				echo "Invalid Cust #\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if($cust == "X"){
				return;
			}
			$cust = remove_badchars($cust);

			if(!is_numeric($cust)){
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Ret1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Ret1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		$prefix = which_clem_tables($cust);

		$order_num = "";
		// get Order
		while($order_num == ""){
			fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			if($order_num != ""){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret12a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret12b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0){
					echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
					fscanf(STDIN, "%s\n", $junk);
					$order_num = "";
				}
			}

			if($order_num != ""){
				$sql = "SELECT COUNT(*) THE_COUNT FROM ".$prefix."_ORDERSTATUS DCS, ".$prefix."_ORDER DO
						WHERE DCS.ORDERSTATUSID = DO.ORDERSTATUSID
						AND DO.ORDERNUM = '".$order_num."'
						AND DCS.ORDERSTATUSID IN ('5', '8', '9', '10')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret12-2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret12-2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] > 0){
					echo "Order Status\nDoes not allow\nReturns.\n(Press Enter)\n";
					fscanf(STDIN, "%s\n", $junk);
					$order_num = "";
				}
			}
		}

		$total_cases = 0;
		$total_pallets = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) GROUP BY PALLET_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2b)");
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
				$total_cases += $short_term_data_row['THE_SUM'];
				$total_pallets++;
			}
		}

		fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){
			$Barcode = ten_to_full_BC($Barcode);
			$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

			$continue_pallet = true;
			$Arrival = "";
			$Commodity = "";

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '".$cust."' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// get  scanned pallet info
			if($continue_pallet){
				$sql = "SELECT NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE CT.PALLET_ID = '".$Barcode."' AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CT.RECEIVER_ID = '".$cust."' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) GROUP BY NVL(VESSEL_NAME, CT.ARRIVAL_NUM), CT.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_void = $short_term_data_row['THE_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
//				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
//				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}

			// AT THIS POINT, we have all the info we need to void.
			// display data, request confirmation
			if($continue_pallet){
				$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

				$sub_choice = "";
				while($sub_choice != "N" && $sub_choice != "Y"){
					system("clear");
					echo $disp_barcode."\n";
					echo "Vesl: ".$vesname."\n";
					echo "Out: ".$date_ship."\n";
					echo "Cust: ".$custname."\n";
					echo "Comm: ".$commname."\n";
//					echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
					echo "Loc: ".$loc."\n";
					echo "QTY SHIPPED: ".$qty_to_void."\n\n";
					echo "Return?  Y/N\n";
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
					fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// if return was confirmed, lets do it!
			if($continue_pallet){
				$sql = "UPDATE CARGO_TRACKING SET MARK = 'AT POW', QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_to_void." WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6b)");

				$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'RETURN' WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret7b)");
/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Ret8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Ret8b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				// add CA record of void
				$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
				('".($act_num + 1)."',
				'".$service_code."',
				'-".$qty_to_void."',
				'".$emp_no."',
				'".$order_num."',
				'".$cust."',
				SYSDATE,
				'".$Barcode."',
				'".$Arrival."',
				'".($qty_in_house + $qty_to_void)."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9b)");

				if($type == "full"){
/*					$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = SYSDATE + (1/1440) WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10b)");*/

//					$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = SYSDATE + (1/1440) 
					$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = GREATEST(BILLING_STORAGE_DATE, TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')) 
							WHERE PALLET_ID = '".$Barcode."' 
								AND ARRIVAL_NUM = '".$Arrival."' 
								AND RECEIVER_ID = '".$cust."'
								AND BILLING_STORAGE_DATE IS NOT NULL";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10b)");
				}

				ora_commit($rf_conn);
				fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.");
				echo $qty_to_void." cartons\n";
				echo $disp_barcode."\n RETURNED\n\n";
				fscanf(STDIN, "%s\n", $junk);
			}

			$total_cases = 0;
			$total_pallets = 0;

			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN')) GROUP BY PALLET_ID";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret11a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret11b)");
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
					$total_cases += $short_term_data_row['THE_SUM'];
					$total_pallets++;
				}
			}

			fresh_screen("CLEMENTINE\nReturns (".$type.")\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Ord#: ".$order_num."\n";
			echo $total_cases." in ".$total_pallets." plts\n\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function Hospital($CID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.");
	$Barcode = "";
	echo "Barcode:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$Barcode = ten_to_full_BC($Barcode);
		$continue = true;
		$Cust = "";
		$Arrival = "";
		$Commodity = "";
		$qty_in_house = 0;
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(H1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(H1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet does not exist
			fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($short_term_data_row['THE_COUNT'] > 1){
			// multiple pallet
			$continue = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Commodity, $qty_in_house);
		} else {
			// single pallet, get info
			$sql = "SELECT CT.ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, VESSEL_NAME FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(H2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(H2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Cust = $short_term_data_row['RECEIVER_ID'];
			$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			$vesname = $short_term_data_row['VESSEL_NAME'];
			$Commodity = $short_term_data_row['COMMODITY_CODE'];
		}

		if($continue){
			$sql = "SELECT DATE_RECEIVED, QTY_IN_HOUSE, WAREHOUSE_LOCATION, CARGO_STATUS FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$Arrival."'
					AND RECEIVER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(H3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(H3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['DATE_RECEIVED'] == ""){
				// make sure it's already received, if not, tell scanner to use "receive" function
				fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT YET\nRECEIVED.  PLEASE\nSET LOCATION\nBY RECEIVING IT**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} elseif($short_term_data_row['QTY_IN_HOUSE'] == "" || $short_term_data_row['QTY_IN_HOUSE'] <= 0){
				// make sure it's still here
				fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.\n\nBC: ".$disp_barcode."\n**PALLET NOT IN\nHOUSE.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} else {
				$cur_status = $short_term_data_row['CARGO_STATUS'];
				switch($cur_status){
					case "":
						$db_action_desc = "HOSPITAL";
						$changed_stat = "H";
						$disp_status = "NOT HOSP";
					break;
					case "R":
						$db_action_desc = "HOSPITAL";
						$changed_stat = "B";
						$disp_status = "NOT HOSP";
					break;
					case "H":
						$db_action_desc = "NULL";
						$changed_stat = "";
						$disp_status = "IN HOSP";
					break;
					case "B":
						$db_action_desc = "NULL";
						$changed_stat = "R";
						$disp_status = "IN HOSP";
					break;

					default:
						$continue = false;
					break;
				}		 
			}
		}

		while($continue){
			// do this if they did not "cancel" or "error" out of above
			fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.");
			echo "BC: ".$disp_barcode."\n\n";
			echo "Status: ".$disp_status."\n";
			echo "New Status: \n";
			if($cur_status == "" || $cur_status == "R"){
				echo " Y) Put In Hosp\n";
			} else {
				echo " Y) Remove Hosp\n";
			}
//			echo " N) Cancel\n";
			$new_stat = "";
			fscanf(STDIN, "%[^[]]\n", $new_stat);
			$new_stat = trim(strtoupper($new_stat));

			if($new_stat == "X" || $new_stat == "" || $new_stat == "N"){
				$continue = false;
			} else {
				// change the CARGO_TRACKING location
				$sql = "UPDATE CARGO_TRACKING SET CARGO_STATUS = '".$changed_stat."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(H4a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(H4b)");

				$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM DC_INSPECTION_LOG";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Pallet\n(H5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Pallet\n(H5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$next_trans = $short_term_data_row['THE_MAX'] + 1;

				$sql = "INSERT INTO DC_INSPECTION_LOG
							(TRANSACTION_ID,
							INSPECTOR_ID,
							INSPECTION_DATETIME,
							ACTION_TYPE,
							NUM_PALLETS,
							ARRIVAL_NUM)
						VALUES
							('".$next_trans."',
							'".$CID."',
							SYSDATE,
							'".$db_action_desc."',
							'1',
							'".$Arrival."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Insp\n(H6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Insp\n(H6b)");

				$sql = "INSERT INTO DC_INSPECTED_PALLET
							(PALLET_ID,
							TRANSACTION_ID,
							PREVIOUS_STATUS,
							NEW_STATUS)
						VALUES
							('".$Barcode."',
							'".$next_trans."',
							'".$cur_status."',
							'".$changed_stat."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Insp\n(H7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nUpdate Insp\n(H7b)");

				ora_commit($rf_conn);
				fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.");
				echo $disp_barcode."\nchanged.\n\n";
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		$Barcode = "";
		fresh_screen("CLEMENTINE\nHospital\nEnter X to exit.");
		echo "Next Barcode:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	}
}

function Unload_COOL($CID){
	global $rf_conn;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$emp_no."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nScanner Name\n(US1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nScanner Name\n(US1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$emp_name = $short_term_data_row['LOGIN_ID'];

	$Arrival = "11950";
	$vesname = "COOL EXPRESO";
	$Cust = "1";
	$Comm = "5606";
	$commname = "USA Clems.";

	$Hatch = "XXX";

	// get and validate ship #
	while($Hatch == "XXX" || $Hatch == "Invalid"){
		fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.");
		if($Hatch != "XXX"){
			echo "Invalid Hatch\n(".$Hatch.")\n";
		}
		echo "Hatch\n";
		fscanf(STDIN, "%s\n", $Hatch);
		$Hatch = strtoupper($Hatch);
		if($Hatch == "X"){
			return;
		}
		
//		$sql = "SELECT WAREHOUSE_LOCATION FROM CLEMENTINE_DISCHARGE WHERE HATCH_ID = '".$Hatch."'";
//		$ora_success = ora_parse($short_term_data_cursor, $sql);
//		database_check($ora_success, "Cannot Retrieve\nHatch Data\n(US2a)");
//		$ora_success = ora_exec($short_term_data_cursor, $sql);
//		database_check($ora_success, "Cannot Retrieve\nHatch Data\n(US2b)");
//		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//			$Hatch = "Invalid";
//		} else {
			$loc = "W".$Hatch;
//		}
	}

	$count = 0;
	fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.");
	echo "Count: ".$count."\n";
	echo "LR Num: ".$Arrival."\n";
	$Barcode = "";
	while($Barcode == ""){
		echo "Barcode:";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}

	while(strtoupper($Barcode) != "X"){
		$Barcode = ten_to_full_BC($Barcode);
		$disp_barcode = substr($Barcode, 0, 13)."\n".substr($Barcode, 13);
		$increm_count = 0;
		$qty_rec = "";
		$proceed = true;
		$pkg_hse = "";
		$size = "";

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(US4a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(US4b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($short_term_data_row['THE_COUNT'] > 0){
			$new_pallet = false;
			$sql = "SELECT QTY_RECEIVED, QTY_DAMAGED, CARGO_SIZE, EXPORTER_CODE
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcode."'
						AND ARRIVAL_NUM = '".$Arrival."'
						AND RECEIVER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nActivity\n(US4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nActivity\n(US4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$qty_rec = $short_term_data_row['QTY_RECEIVED'];
			$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			$size = $short_term_data_row['CARGO_SIZE'];
			$pkg_hse = $short_term_data_row['EXPORTER_CODE'];

		}else{
			$new_pallet = true;
			$qty_dmg = 0;
			while($proceed && ($qty_rec == "" || !is_numeric($qty_rec))){
				fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.\n\nLR Num: ".$Arrival."\nHatch: ".$Hatch."\nBC: ".$disp_barcode);
				echo "QTY Rcvd#: \n";
				fscanf(STDIN, "%s\n", $qty_rec);
				$qty_rec = strtoupper($qty_rec);
				if($qty_rec == "X"){
					$proceed = false;
				}
			}

			while($proceed && $pkg_hse == ""){
				fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.\n\nLR Num: ".$Arrival."\nHatch: ".$Hatch."\nBC: ".$disp_barcode);
				echo "PKG HSE: \n";
				fscanf(STDIN, "%s\n", $pkg_hse);
				$pkg_hse = strtoupper($pkg_hse);
				if($pkg_hse == "X"){
					$proceed = false;
				}
			}
		
			while($proceed && ($size == "" || !is_numeric($size))){
				fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.\n\nLR Num: ".$Arrival."\nHatch: ".$Hatch."\nBC: ".$disp_barcode);
				echo "Size: \n";
				fscanf(STDIN, "%s\n", $size);
				$size = strtoupper($size);
				if($size == "X"){
					$proceed = false;
				}
			}
		}
		

		if(!$proceed){
			// new pallet that was cancelled
			fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.\n\nLR Num: ".$Arrival."\nHatch: ".$Hatch."\nBC: ".$disp_barcode."\nCancelled.");
		} else {
			// get other pallet info
			$asterisk_array = array();

			do{
				fresh_screen("LR Num: ".$Arrival."\nCust: ".$cust."\nComm: ".$commname."\nHatch: ".$Hatch);
				echo $disp_barcode."\n";
				echo $asterisk_array[0]."1)QTY Rcvd: ".$qty_rec."\n";
				echo $asterisk_array[1]."2)QTY Dmg: ".$qty_dmg."\n";
				echo $asterisk_array[2]."3)Size: ".$size."\n";
				echo $asterisk_array[3]."4)PKG HSE: ".$pkg_hse."\n";
				echo "\nTo Change Select #\n";
				echo "\"Enter\" to Accept\n";
				echo "\"X\" To Escape\n";

				$Choice = "";
				fscanf(STDIN, "%s\n", $Choice);
				$Choice = strtoupper($Choice);

				switch($Choice){
					case 1:
						if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "rcvd", $qty_rec)){
							$asterisk_array[0] = "*";
						}
						break;
					case 2:
						if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "dmg", $qty_dmg)){
							$asterisk_array[1] = "*";
						}
						break;
					case 3:
						if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "size", $size)){
							$asterisk_array[2] = "*";
						}
						break;
					case 4:
						if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "pkg_hse", $pkg_hse)){
							$asterisk_array[3] = "*";
						}
						break;
					case "X":
						fresh_screen("CLEMENTINE\nUnload COOL\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\nCancelled.");
						break;
					case "":
						CTE_VERIFY($Barcode, $Arrival, $Cust, $Cust);

						$weight = get_weight($size, $qty_rec);

						if($new_pallet){
							$sql = "INSERT INTO CARGO_TRACKING 
									(COMMODITY_CODE,
									CARGO_DESCRIPTION,
									DATE_RECEIVED,
									QTY_RECEIVED,
									BATCH_ID,
									RECEIVER_ID,
									QTY_DAMAGED,
									WAREHOUSE_LOCATION,
									QTY_IN_HOUSE,
									PALLET_ID,
									ARRIVAL_NUM,
									FROM_SHIPPING_LINE,
									SHIPPING_LINE,
									RECEIVING_TYPE,
									CARGO_SIZE,
									WEIGHT,
									WEIGHT_UNIT,
									EXPORTER_CODE,
									MARK,
									CARGO_TYPE_ID)
									VALUES
									('".$Comm."',
									'".$pkg_hse."-".$size."-".$qty_rec."',
									SYSDATE,
									'".$qty_rec."',
									'".$qty_rec."',
									'".$Cust."',
									'".$qty_dmg."',
									'".$loc."',
									'".$qty_rec."',
									'".$Barcode."',
									'".$Arrival."',
									'6000',
									'6000',
									'S',
									'".$size."',
									'".$weight."',
									'KG',
									'".$pkg_hse."',
									'AT POW',
									'1')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US15a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US15b)");

							$increm_count = 1;

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
									'".$Cust."',
									SYSDATE,
									'".$Barcode."',
									'".$Arrival."',
									'".$qty_rec."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US19a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US19b)");
						} else {
							$sql = "UPDATE CARGO_TRACKING
									SET QTY_RECEIVED = '".$qty_rec."',
										QTY_IN_HOUSE = '".$qty_rec."',
										BATCH_ID = '".$qty_rec."',
										QTY_DAMAGED = '".$qty_dmg."',
										CARGO_SIZE = '".$size."',
										EXPORTER_CODE = '".$pkg_hse."',
										CARGO_DESCRIPTION = '".$pkg_hse."-".$size."-".$qty_rec."'
									WHERE PALLET_ID = '".$Barcode."'
										AND ARRIVAL_NUM = '".$Arrival."'
										AND RECEIVER_ID = '".$Cust."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(US15a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(US15b)");

							$sql = "UPDATE CARGO_ACTIVITY
									SET QTY_CHANGE = '".$qty_rec."',
										QTY_LEFT = '".$qty_rec."'
									WHERE PALLET_ID = '".$Barcode."'
										AND ARRIVAL_NUM = '".$Arrival."'
										AND CUSTOMER_ID = '".$Cust."'
										AND ACTIVITY_NUM = '1'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(US19a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(US19b)");
						}

						ora_commit($rf_conn);
						$count += $increm_count;
						fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to Exit\n\nLR Num: ".$Arrival."\nBC: ".$disp_barcode."\n\nRECEIVED!");

						break;

					default:
						fresh_screen("CLEMENTINE\nUnload COOL\nEnter X to exit.\n\n**PLEASE FINISH\nRECEIVING PALLET\n".$disp_barcode."**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						break;
				}
			} while($Choice != "X" && $Choice != "");
		}

		echo "Count: ".$count."\n";
		echo "LR Num: ".$Arrival."\n";
		$Barcode = "";
		while($Barcode == ""){
			echo "Barcode:";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}







/*******************************************************************************
* AUXILIARY FUNCTIONS START HERE ***********************************************
********************************************************************************/

function which_clem_tables($customer_ID){
	global $rf_conn;

	$short_term_data_cursor = ora_open($rf_conn);

	if($customer_ID == 270){
		return "MOR";
	}

	return "DC";
}



function ten_to_full_BC($entered_BC){
/*
*	Used by most functions; if someone types in ONLY TEN digits of a barcode, the program should see if there is
*	A BC in the system for which this string of 10 or more digits exists, and if so, transpose it into the function.
*	If no match is found, just pass the BC back unchanged; each function handles "nonexistance" differently.
*
*	apparently, Inventory is big on this.  Not that I blame them, keying in 32-digits at once can be a pain in the semicolon.
******************************************************************************************************************************/
	global $rf_conn;

	$short_term_data_cursor = ora_open($rf_conn);

	// does this pallet_id match exactly to one in the system?
	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$entered_BC."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(ttfO1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(ttfO1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_data_row['THE_COUNT'] >= 1){
		return $entered_BC;
	}

	// does this pallet match a 10-digit intermediary in the system?
	if(strlen($entered_BC) >= 10){
		$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE PALLET_ID LIKE '611%".$entered_BC."%'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(ttfO2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(ttfO2b)");
		if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			return $short_term_data_row['PALLET_ID'];
		} 
	}

	return $entered_BC;
}


function validate_domestic_order($order_num, $Barcode, $cust, $order_ARV, $order_detail, $order_det_high_size, $order_det_low_size, $order_det_ctn_ordered, $order_det_ctn_scanned){

/* Used by Truck Out, determines if a pallet matches, and fits, on a portion
*	of a Domestic order
*******************************************************************************************/
	global $rf_conn;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);
	$order_det_value = "";
	$index = "";

	// determine which order_det# this pallet belongs to
	$sql = "SELECT QTY_IN_HOUSE, CARGO_SIZE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_ARV."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VDO1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VDO1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$pallet_size = $short_term_data_row['CARGO_SIZE'];
	$cartons = $short_term_data_row['QTY_IN_HOUSE'];

	// get the specific part of order this would go on
	for($i = 0; $i < sizeof($order_detail); $i++){
		if($pallet_size <= $order_det_high_size[$i] && $pallet_size >= $order_det_low_size[$i]){
			$order_det_value = $order_detail[$i];
			$index = $i;
		}
	}

	if($order_det_value == ""){
		// no portion of order matched this size
		return "**PALLET DOES NOT\nMATCH ANY PART\nOF ORDER**";
	}

	if(($order_det_ctn_scanned[$index] + $cartons) > $order_det_ctn_ordered[$index]){
		// pallet would push this portion of order over requested amount
		return "**PALLET QTY (".$cartons.")\nMORE THAN NEEDED\nFOR DETAIL (".$order_det_value.")\n (has ".$order_det_ctn_scanned[$index]." of ".$order_det_ctn_ordered[$index].")";
	}

	// if got here, it passed check
	return "";
}

//function validate_export_order($order_num, $Barcode, $cust, $load_status, $order_ARV, $order_detail, $order_det_high_size, $order_det_low_size, $order_det_ctn_ordered, $order_det_ctn_scanned, $order_det_plt_ordered, $order_det_plt_scanned){
function validate_export_order($order_num, $Barcode, $cust, $load_status, $order_ARV, $order_detail, $order_det_high_size, $order_det_low_size, $order_det_ctn_ordered, $order_det_ctn_scanned, $order_det_plt_ordered, $order_det_plt_scanned, $order_det_weight){
/* Used by Truck Out, determines if a pallet matches, and fits, on a portion
*	of an Export order
*******************************************************************************************/
	global $rf_conn;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);
	$order_det_value = "";
	$index = "";

	$no_match = "";

	$prefix = which_clem_tables($cust);

	// determine which order_det# this pallet belongs to
	$sql = "SELECT QTY_IN_HOUSE, EXPORTER_CODE, CARGO_SIZE, CARGO_STATUS, WEIGHT 
			FROM CARGO_TRACKING 
			WHERE PALLET_ID = '".$Barcode."' 
				AND RECEIVER_ID = '".$cust."' 
				AND ARRIVAL_NUM = '".$order_ARV."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$pallet_size = $short_term_data_row['CARGO_SIZE'];
	$cartons = $short_term_data_row['QTY_IN_HOUSE'];
	$pkg_hse = $short_term_data_row['EXPORTER_CODE'];
	$pallet_status = $short_term_data_row['CARGO_STATUS'];
	$wt = $short_term_data_row['WEIGHT'];

	$sql = "SELECT DCOD.ORDERDETAILID, DCOD.SIZEHIGH, DCOD.SIZELOW, SUM(PALLETQTY) THE_QTY
			FROM ".$prefix."_PICKLIST DCP, ".$prefix."_ORDERDETAIL DCOD
			WHERE (PACKINGHOUSE = '".$pkg_hse."' OR TRIM(PACKINGHOUSE) = '9999')
				AND (PICKLISTSIZE = '".$pallet_size."'
					OR
						(PICKLISTSIZE = '0'
						AND
						DCOD.SIZEHIGH >= ".$pallet_size." 
						AND
						DCOD.SIZELOW <= ".$pallet_size."
						)
					)
				AND DCOD.ORDERNUM = '".$order_num."'
				AND DCOD.ORDERDETAILID = DCP.ORDERDETAILID
				AND DCOD.WEIGHTKG = '".$wt."'
			GROUP BY DCOD.ORDERDETAILID, DCOD.SIZEHIGH, DCOD.SIZELOW";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$ora_success = ora_parse($select_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO2a)");
	$ora_success = ora_exec($select_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO2b)");
	if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// order doesnt specify this type of pallet
		$no_match = "**PALLET NOT VALID\n PKG-HSE ".$pkg_hse."\n SIZE ".$pallet_size."\n WEIGHT ".$wt."\nNOT ON\n THIS ORDER**\n";
	} else {
		$needed_plt = $select_row['THE_QTY'];
		$any_low = $select_row['SIZELOW'];
		$any_high = $select_row['SIZEHIGH'];

		/*
		// get pallet count for this part of order, see if filled
		for($i = 0; $i < sizeof($order_detail); $i++){
			if($order_detail[$i] == $select_row['ORDERDETAILID']){
				$index = $i;
			}
		}

		echo $order_detail[$index]." ".$order_det_plt_scanned[$index]." ".$order_det_plt_ordered[$index]."\n";
		fscanf(STDIN, "%s\n", $junk);
		if($order_det_plt_scanned[$index] >= $order_det_plt_ordered[$index]){
			// this picklist portion filled.
				// NOTE:  this part can be expanded to show all picklists in this detail if it is determined
				// that a more robust error message is needed.  I just don't have the time to do it before 3:30PM today.
			return "**PALLET NOT NEEDED\n PKGHSE ".$pkg_hse."\nSIZE ".$pallet_size."\nALREADY FULL**";
		}
		*/

		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_COUNT 
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CA.ORDER_NUM = '".$order_num."'
					AND CA.SERVICE_CODE = '6'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR (CA.ACTIVITY_DESCRIPTION != 'VOID' AND CA.ACTIVITY_DESCRIPTION != 'RETURN'))
					AND CT.EXPORTER_CODE = '".$pkg_hse."'
					AND (CT.CARGO_SIZE = '".$pallet_size."'
						OR
							(TO_NUMBER(CT.CARGO_SIZE) <= ".$any_high."
							AND
							TO_NUMBER(CT.CARGO_SIZE) >= ".$any_low."
							)
						)
					AND CT.WEIGHT = '".$wt."'
					AND CT.PALLET_ID != '".$Barcode."'";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$scanned_plt = $short_term_data_row['THE_COUNT'];

		// MAJOR CHANGE, oct 17 2013
		// due to the fact that "any packhouse" now exists, in addition to the above "scanned plt" equation, we have to add into that
		// the amount of pallets scanned under any other packing house, up to a maximum of the number of pallets requested
		// under the "9999" packhouse in the picklist.

		$sql = "SELECT NVL(LEAST(SUM(PALLETQTY), COUNT(DISTINCT CA.PALLET_ID)), 0) THE_LEAST
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, ".$prefix."_PICKLIST DCP
				WHERE CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CA.ORDER_NUM = '".$order_num."'
					AND CA.SERVICE_CODE = '6'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR (CA.ACTIVITY_DESCRIPTION != 'VOID' AND CA.ACTIVITY_DESCRIPTION != 'RETURN'))
					AND CT.EXPORTER_CODE != '".$pkg_hse."'
					AND (CT.CARGO_SIZE = '".$pallet_size."'
						OR
							(TO_NUMBER(CT.CARGO_SIZE) <= ".$any_high."
							AND
							TO_NUMBER(CT.CARGO_SIZE) >= ".$any_low."
							)
						)
					AND CT.PALLET_ID != '".$Barcode."'
					AND CT.WEIGHT = '".$wt."'
					AND CA.ORDER_NUM = TRIM(DCP.ORDERNUM)
					AND TRIM(DCP.ORDERDETAILID) = '".$select_row['ORDERDETAILID']."'
					AND DCP.PACKINGHOUSE = '9999'";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO3-1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO3-1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$scanned_plt += $short_term_data_row['THE_LEAST'];

		if($scanned_plt >= $needed_plt){
			$no_match = "**PALLET NOT NEEDED\n PKGHSE ".$pkg_hse."\n SIZE ".$pallet_size."\n WEIGHT ".$wt."\nALREADY FULL**\n";
		} else {
			$sql = "SELECT SUM(PALLETQTY) THE_SUM FROM ".$prefix."_PICKLIST WHERE ORDERNUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO4b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$ordered_plt = $short_term_data_row['THE_SUM'];

			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT
					FROM CARGO_ACTIVITY
					WHERE SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR (ACTIVITY_DESCRIPTION != 'VOID' AND ACTIVITY_DESCRIPTION != 'RETURN'))
					AND ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO5a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO5b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$scanned_plt = $short_term_data_row['THE_COUNT'];

			if($scanned_plt >= $ordered_plt){
				$no_match = "**PALLET NOT NEEDED\n ORDER ".$order_num."\nALREADY FULL**\n";
			}
		}
	}

	if($load_status != $pallet_status){
		switch($load_status){
			case "":
				$load_status = "CUST";
				break;
			case "H":
				$load_status = "HOSP";
				break;
			case "R":
				$load_status = "REGR";
				break;
		}
		switch($pallet_status){
			case "":
				$pallet_status = "GOOD";
				break;
			case "H":
				$pallet_status = "HOSP";
				break;
			case "R":
				$pallet_status = "REGR";
				break;
			case "B":
				$pallet_status = "BOTH";
				break;
		}
		$no_match .= "**PALLET (".$pallet_status.") DOES\nNOT MATCH STATUS\nOF ORDER (".$load_status.")**\n";
	}


	if($no_match != ""){
		// PALLET DOES NOT FIT CURRENT ORDER

		$sql = "SELECT ORDERSTATUSID FROM ".$prefix."_ORDER WHERE ORDERNUM = '".$order_num."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO21a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VEO21b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		// if the order itself has been completed, then that's the end of it.  pallet invalid
		if($short_term_data_row['ORDERSTATUSID'] != "4"){
			return "**CANNOT OVERRIDE\nORDER HAS BEEN\nCLOSED**";
		} else {
			// Do they want it anyway?
			fresh_screen("CLEMENTINE\nPALLET OVERRIDE\nEnter X to exit.", "bad");
			echo $no_match;
			echo "Do You Want To\nAdd This Pallet to\nOrder Anyway?(Y/N)\n";
			fscanf(STDIN, "%s\n", $choice);
			$choice = strtoupper($choice);

			if($choice == "Y"){
				// they want to override.
				return "";
			} else {
				// They don't actually want this pallet after all.
				return "**NON-MATCHING\nPALLET OVERRIDE\nCANCELLED**";
			}
		}
	} else {
		// Pallet matched, no problems here
		return "";
	}
	
}
		





function get_weight($size, $qty){
/* given a clementine size and casecount, returns the "boxweight" used in CARGO_TRACKING
*******************************************************************************************/
	if($qty == 450){
		return "1.8";
	}

	if($size <= 50){
		return "2.3";
	} else {
		return "10";
	}
}

function CTE_VERIFY($Barcode, $Arrival, $Cust, $prev_cust){
/* used by Unload Ship
*	If no record for this pallet exists in CARGO_TRACKING_EXTENSION, make it.
*	If record exists for $prev_cust
*********************************************************************************************************/
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	if($prev_cust != $Cust){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_EXT
				WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$Arrival."'
				AND RECEIVER_ID = '".$prev_cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(CTEV1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(CTEV1b)");
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] > 0){
			$sql = "UPDATE CARGO_TRACKING_EXT
					SET RECEIVER_ID = '".$Cust."'
					WHERE PALLET_ID = '".$Barcode."'
					AND ARRIVAL_NUM = '".$Arrival."'
					AND RECEIVER_ID = '".$prev_cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(CTEV2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(CTEV2b)");

			return;
		}
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_EXT
			WHERE PALLET_ID = '".$Barcode."'
			AND ARRIVAL_NUM = '".$Arrival."'
			AND RECEIVER_ID = '".$Cust."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(CTEV1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(CTEV1b)");
	ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($row['THE_COUNT'] <= 0){
		$sql = "INSERT INTO CARGO_TRACKING_EXT
					(PALLET_ID, ARRIVAL_NUM, RECEIVER_ID)
				VALUES
					('".$Barcode."', '".$Arrival."', '".$Cust."')";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(CTEV2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(CTEV2b)");
		
		return;
	}
}

function Validate_duplicate_get_comm_and_cust($Barcode, $Arrival, &$Cust, &$Comm, &$qty_rec, &$new_pallet){
/* used by Unload Ship & Unload Truck
* determines if a pallet exists, and, if it does AND is a duplicate (for this vessel/palletID combo) which one the user wants.
*
* When this function ends, either $new_pallet will be true, the update is cancelled, OR a commodity and customer are chosen.
* At least, that's the way it should work.
*
* Return values are the Pass-by-reference variables in the function call.  They can be overwritten in the course of this function
*
* function value is FALSE if this function was cancelled out of, TRUE otherwise.
******************************************************************************************************************************/
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$proceed = true;
	
	$sql = "SELECT COMMODITY_CODE, RECEIVER_ID, QTY_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
	$ora_success = ora_parse($select_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(VCC1a)");
	$ora_success = ora_exec($select_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(VCC1b)");
	if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no record means its a new pallet.  note and exit function.
		$new_pallet = true;
		return true;
	}else{
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VCC2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VCC2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		
		if($short_term_data_row['THE_COUNT'] == 1){
			// a single entry means is NOT a duplicate pallet, but it is manifested
			// set appropriately from earlier SQL
			$Cust = $select_row['RECEIVER_ID'];
			$Comm = $select_row['COMMODITY_CODE'];
			$qty_rec = $select_row['QTY_RECEIVED'];
			$new_pallet = false;
			return true;
		} else {
			$proceed = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Comm, $qty_rec);

			if($proceed == false){
				return false;
			} else {
				$new_pallet = false;
				return true;
			}

		}
	}
}

function modify_unload($Barcode, $Arrival, $Cust, $Comm, $mod_type, &$return_variable){
/* used by Unload Ship
* use to edit any of the data lines for a pallet prior to receipt
*
* First 4 arguments not used as of this time; if more error checking needed, may as well already supply
* Them to this function
*****************************************************************************************************************************/
	global $rf_conn;
	$short_term_data_cursor = ora_open($rf_conn);

	$was_changed = false;
	$continue_function = true;

	$new_value = "";

	while($continue_function){
		switch($mod_type){
			case "cust":
				fresh_screen("CLEMENTINE\nModify Customer\nEnter X to exit.");
				echo "Current Customer:\n  ".$return_variable."\nNew Customer:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

//				if($new_value == "X") {
				if(!is_numeric($new_value)) {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify Customer\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$new_value."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(MU1a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(MU1b)");
					
					if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
						fresh_screen("CLEMENTINE\nModify Customer\n\nInvalid Customer\n(".$new_value.")", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} else {
						$continue_function = false;
						$return_variable = $new_value;
						$was_changed = true;
					}
				}
			break;

			case "comm":
				fresh_screen("CLEMENTINE\nModify Commodity\nEnter X to exit.");
				echo "Current Commodity:\n  ".$return_variable."\nNew Commodity:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

//				if($new_value == "X") {
				if(!is_numeric($new_value)) {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify Commodity\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$new_value."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(MU2a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(MU2b)");
					
					if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						fresh_screen("CLEMENTINE\nModify Commodity\n\nInvalid Commodity\n(".$new_value.")", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} else {
						$continue_function = false;
						$return_variable = $new_value;
						$was_changed = true;
					}
				}
			break;

			case "rcvd":
				fresh_screen("CLEMENTINE\nModify QTY Rcvd\nEnter X to exit.");
				echo "Expected QTY Rcvd:\n  ".$return_variable."\nActual QTY Rcvd:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify QTY Rcvd\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif(!is_numeric($new_value)){
					fresh_screen("CLEMENTINE\nModify QTY Rcvd\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "dmg":
				fresh_screen("CLEMENTINE\nModify QTY DMG\nEnter X to exit.");
				$return_variable = (0 + $return_variable);
				echo "Current QTY DMG:\n  ".$return_variable."\nNew QTY DMG:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify QTY DMG\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif(!is_numeric($new_value)){
					fresh_screen("CLEMENTINE\nModify QTY DMG\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "loc":
				fresh_screen("CLEMENTINE\nModify Location\nEnter X to exit.");
				echo "Current Location:\n  ".$return_variable."\nNew Location:\n";
				fscanf(STDIN, "%[^[]]\n", $new_value);
				$new_value = trim(strtoupper($new_value));

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify Location\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
/*				} elseif(!is_numeric($new_value)){
					fresh_screen("CLEMENTINE\nModify Location\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); */
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "pkg_hse":
				fresh_screen("CLEMENTINE\nModify PKGHSE\nEnter X to exit.");
				echo "Current PKG:\n  ".$return_variable."\nNew PKG:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify PKG\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
/*				} elseif(!is_numeric($new_value)){
					fresh_screen("CLEMENTINE\nModify Location\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); */
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "size":
				fresh_screen("CLEMENTINE\nModify Size\nEnter X to exit.");
				echo "Current Size:\n  ".$return_variable."\nNew Size:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify Size\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
/*				} elseif(!is_numeric($new_value)){
					fresh_screen("CLEMENTINE\nModify Location\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); */
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "hosp":
				if($return_variable == "" || $return_variable == "N"){
					$printout = "N";
				} else {
					$printout = "Y";
				}

				fresh_screen("CLEMENTINE\nModify Hosp\nEnter X to exit.");
				echo "Hosp Status:\n  ".$printout."\nNew Hosp (Y/N):\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CLEMENTINE\nModify Hosp\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif($new_value != "" && $new_value != "Y" && $new_value != "N"){
					fresh_screen("CLEMENTINE\nModify Hosp\n\nMust be Y, N,\nOr Blank\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); 
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "fault":
				fresh_screen("CLEMENTINE\nModify Fault\nEnter X to exit.");
				echo "Current Fault:\n  ".$return_variable."\nNew Fault:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("CHILEAN FRUIT\nModify Fault\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif($new_value != "Y" && $new_value != "N"){
					fresh_screen("CHILEAN FRUIT\nModify Fault\n\nMust be Y or N\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); 
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;
		}
	}

	return $was_changed;
}

function Select_Duplicate_Pallet($Barcode, &$Cust, &$Arrival, &$Commodity, &$qty_rec){
/* used by Validate_duplicate_get_comm_and_cust
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
	$sql = "SELECT COMMODITY_NAME, VESSEL_NAME, CT.COMMODITY_CODE, CT.ARRIVAL_NUM, RECEIVER_ID, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, CARGO_STATUS, CARGO_SIZE, EXPORTER_CODE, QTY_IN_HOUSE FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, VESSEL_PROFILE VP WHERE PALLET_ID = '".$Barcode."' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
	
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
		$pallet_info[$pallet_number]["pkg_hse"] = $short_term_data_row['EXPORTER_CODE'];
		$pallet_info[$pallet_number]["size"] = $short_term_data_row['CARGO_SIZE'];
		$pallet_info[$pallet_number]["hosp"] = $short_term_data_row['CARGO_STATUS'];

		$pallet_number++;
	}

	$choice = "there is no spoon";
	$display_set_counter = 0; 
	$max_page = $pallet_number;

	fresh_screen("CLEMENTINE\nSelect Duplicate\n\nBC: ".$Barcode."\nDuplicate Pallets\nFound.  Please\nSelect:", "bad");
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
		echo "PKGHSE: ".$pallet_info[$display_set_counter]["pkg_hse"]."\n";
		echo "Size: ".$pallet_info[$display_set_counter]["size"]."\n";
		echo "Hosp: ".$pallet_info[$display_set_counter]["hosp"]."\n";

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




// ---------------MAIN Menu-----------------
// starts here

$scanner_type = "CLEMENTINES";

$SID = Login_Super($argv[1]);
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}


do {
	fresh_screen("CLEMENTINES");
	echo "1: Super Functions\n";
	echo "2: Checker\n";
	echo "3: Exit CLEM\n";
	fscanf(STDIN, "%s\n", $SuperCHOICE);
} while ($SuperCHOICE != 1 && $SuperCHOICE != 2 && $SuperCHOICE != 3);

if($SuperCHOICE == 1){
	do {
		fresh_screen("Fruit (Super)");
		echo "1: Exit\n"; 
		fscanf(STDIN, "%s\n", $CHOICE);

		switch ($CHOICE) {
			case 1:
				echo "under construction\n";
				// will exit
			break;
		}
	}while ($CHOICE != 1);

} elseif($SuperCHOICE == 2){

	
	do {
		$CHOICE = "";
		$subCHOICE = "";

		if($CID == ""){
			$CID = Login_Checker();
		}
		fresh_screen(" Clem (Checker)");
		echo " 1: Super(", $SID, ")\n";
		echo " 2: Chkr(", $CID, ")\n";
		echo " 3: Unload Cargo\n";
		echo " 4: Relocate\n";
//		echo " 4: Recoup/Relocate\n";
		echo " 5: Load Truck\n";
		echo " 6: Return\n";
		echo " 7: Info\n";
		echo " 8: Hospital\n";
		echo " 9: Void\n";
		echo " 10: Transfer\n";
		echo " 11: Exit CLEM\n"; 
		echo " ENTER (1-11):\n";
		fscanf(STDIN, "%s\n", $CHOICE);

		switch ($CHOICE) {
			case 1:
				$SID = Login_Super();
				$CID = Login_Checker();
			break;
			
			case 2:
				$CID = Login_Checker();
			break;

			case 3:
				fresh_screen("Unload:\n\n1) Ship\n2) Inbound Truck"); 
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Unload_Ship($CID);
				} elseif($subCHOICE == "2"){
					Unload_Truck($CID);
				} /*elseif($subCHOICE == "3"){
					Unload_COOL($CID);
				} */
			break;

			case 4:
				Relocate($CID);
			break;
/*
			case 4:
				fresh_screen("Please Select:\n\n1) Recoup\n2) Relocate");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Recoup($CID);
				} elseif($subCHOICE == "2"){
					Relocate($CID);
				}
			break;
*/
			case 5:
				Load_Truck($CID);
			break;

			case 6:
				fresh_screen("RETURNS\n1) Dock\n2) Full");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Returns($CID, "dock");
				} elseif($subCHOICE == "2"){
					Returns($CID, "full");
				}
			break;

			case 7:
				fresh_screen("INFO\n1) Pallet\n2) Truck Out");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Info_Pallet();
				} elseif($subCHOICE == "2"){
					Info_Outbound_Order();
				}
			break;

			case 8:
				Hospital($CID);
			break;

			case 9:
				fresh_screen("VOID\n1) Order\n2) Pallet");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					VoidOUTORDER($CID);
				} elseif($subCHOICE == "2"){
					VoidOUT($CID);
				}
			break;

			case 10:
				Trans_Owner($CID);
			break;

			case 11:
				// will exit
			break;
		}
	} while ($CHOICE != 11);
}