<?
include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$pallet_cursor = ora_open($rf_conn);
$summary_cursor = ora_open($rf_conn);
$damage_cursor = ora_open($rf_conn);

	$cust = "";
	while($cust == "" || $cust == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nBooking IB TALLY\nEnter X to exit.\n\n";
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(FrO1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(FrO1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$cust = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$cust = "Invalid";
		}			
		
	}


	$ARV = "";
	while($ARV == "" || $ARV == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nBooking IB TALLY\nEnter X to exit.\n\n";
		if($ARV != ""){
			echo "Invalid Railcar #\n";
		}
		echo "Railcar#:\n";
		fscanf(STDIN, "%s\n", $ARV);
		$ARV = strtoupper($ARV);
		if($ARV == "X"){
			return;
		}
		$ARV = remove_badchars($ARV);

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$ARV."' AND REMARK = 'BOOKINGSYSTEM' AND RECEIVER_ID = '".$cust."' AND DATE_RECEIVED IS NOT NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nRailcar\n(BkI1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nRailcar\n(BkI1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$ARV = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$ARV = "Invalid";
		}			
	}


	$manifest = "beans";
	while($manifest == "beans" || $manifest == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nBooking IB TALLY\nEnter X to exit.\n\n";
		if($manifest != "beans"){
			echo "Invalid Manifest #\n";
		}
		echo "Manifest (optional)#:\n";
//		echo "(or \"all\" for All)\n";
		fscanf(STDIN, "%[^[]]\n", $manifest);
		$manifest = trim(strtoupper($manifest));
		if($manifest == "X"){
			return;
		}
		$manifest = remove_badchars($manifest);

		if($manifest != "" && $manifest != "ALL"){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
					WHERE CT.PALLET_ID = BAD.PALLET_ID
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '".$cust."'
						AND BAD.BOL = '".$manifest."' AND REMARK = 'BOOKINGSYSTEM' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nmanifest\n(BkI2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nmanifest\n(BkI2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$manifest = "Invalid";
			} elseif($short_term_data_row['THE_COUNT'] <= 0){
				$manifest = "Invalid";
			}
		}
	}
	// there is no such thing as an "all manifest", so if they dont choose one, grab it instead.
	if($manifest == ""){
		$sql = "SELECT MAX(DATE_OF_ACTIVITY) THE_DATE, BOL 
				FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD 
				WHERE CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM 
					AND CA.PALLET_ID = BAD.PALLET_ID 
					AND CA.CUSTOMER_ID = BAD.RECEIVER_ID 
					AND CA.ORDER_NUM = '".$ARV."'
					AND CA.CUSTOMER_ID = '".$cust."'
					AND CA.ACTIVITY_NUM = '1' 
				GROUP BY BOL 
				ORDER BY THE_DATE DESC";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$manifest = $short_term_data_row['BOL'];
	}

	$WHScode = "franks";
	while($WHScode == "franks" || $WHScode == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nBooking IB TALLY\nEnter X to exit.\n\n";
		if($WHScode != "franks"){
			echo "Invalid Manifest #\n";
		}
		echo "WHS Code\n(optional)#:\n";
//		echo "(or \"all\" for All)\n";
		fscanf(STDIN, "%[^[]]\n", $WHScode);
		$WHScode = trim(strtoupper($WHScode));
		if($WHScode == "X"){
			return;
		}
		$WHScode = remove_badchars($WHScode);

		if($WHScode != "" && $WHScode != "ALL"){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
					WHERE CT.PALLET_ID = BAD.PALLET_ID
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '".$cust."'
						AND BAD.WAREHOUSE_CODE = '".$WHScode."' AND REMARK = 'BOOKINGSYSTEM' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nmanifest\n(BkI3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nmanifest\n(BkI3b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$WHScode = "Invalid";
			} elseif($short_term_data_row['THE_COUNT'] <= 0){
				$WHScode = "Invalid";
			}
		}
	}
	if($WHScode != ""){
		$WSC_code_SQL = " AND WAREHOUSE_CODE = '".$WHScode."' ";
	} else {
		$WSC_code_SQL = " ";
	}


	if ($kiosk_name ==  "") {
		$kiosk_name = "Testing";
	}
	echo "\nProcessing Request\n".$ARV."...\n";

	$any_damage = false;

	$sql = "SELECT CT.PALLET_ID, QTY_RECEIVED, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, 
				ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, 
				NVL(BOOKING_NUM, '--NONE--') THE_BOOK, BAD.ORDER_NUM, LENGTH || ' ' || LENGTH_MEAS THE_LINEAR, 
				BAD.BOL THE_MANIFEST, ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB, ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG,
				DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN, CA.CUSTOMER_ID
			FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4
			Where CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  
				AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
				AND BAD.BOL = '".$manifest."'
				".$WSC_code_SQL."
				AND CA.ACTIVITY_NUM = '1'
				AND CA.CUSTOMER_ID = '".$cust."'
				AND CA.ORDER_NUM = '".$ARV."'
			ORDER BY NVL(BOOKING_NUM, '--NONE--')";
	ora_parse($pallet_cursor, $sql);
	ora_exec($pallet_cursor);
	if(!ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	} else {
		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "BookIB_".$ARV."_".$manifest."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

		/* Print Tally Banner Info */
		fwrite($handle, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."\n\n");
		fwrite($handle, "PORT OF WILMINGTON COMMERCIAL PAPER INBOUND TALLY\n\n");
		$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		fwrite($handle, "Customer: ".$row['CUSTOMER_NAME']."\n");
		fwrite($handle, "Railcar: ".$ARV."\n");
		if($manifest != ""){
			fwrite($handle, "Manifest#: ".$manifest."\n");
		}
		if($WHScode != ""){
			fwrite($handle, "Warehouse Code: ".$WHScode."\n");
		}

		$total_rolls = 0;
		$total_lb = 0;
		$total_kg = 0;

		$the_BC = str_pad("BARCODE", 30);
		$the_qty = str_pad("QTY", 4);
		$the_size= str_pad("SIZE (cm)", 10);
		$the_dia = str_pad("DIA (cm)", 9);
		$the_prod = str_pad("PRODUCT", 12);
		$the_bk = str_pad("BOOKING#", 13);
		$the_order = str_pad("ORDER", 10);
		$the_linear = str_pad("LINEAR MEAS", 12);
		$the_recman = str_pad("REC. MANIFEST", 14);
		$the_lb = str_pad("LB", 8);
		$the_kg = str_pad("KG", 8);
		$the_dmg = str_pad("DMG", 4);
		$the_checker = str_pad("CHECKER", 8);
		fwrite($handle, $the_BC.$the_qty.$the_size.$the_dia.$the_prod.$the_bk.$the_order.$the_linear.$the_received.$the_recman.$the_lb.$the_kg.$the_dmg.$the_checker."\n");
		fwrite($handle, "================================================================================================================================================\n");
		
		do {
			$emp_name = get_employee_for_print($pallet_row['PALLET_ID'], $ARV, $pallet_row['CUSTOMER_ID'], "1", $rf_conn);
			$total_rolls++;
			$total_lb += $pallet_row['WEIGHT_LB'];
			$total_kg += $pallet_row['WEIGHT_KG'];

			if(trim($pallet_row['DAMAGE_YN']) == "Y"){
				$any_damage = true;
			}

			$the_BC = str_pad(trim($pallet_row['PALLET_ID']), 30);
			$the_qty = str_pad(trim($pallet_row['QTY_RECEIVED']), 4);
			$the_size= str_pad(trim($pallet_row['THE_WIDTH']), 10);
			$the_dia = str_pad(trim($pallet_row['THE_DIA']), 9);
			$the_prod = str_pad(trim($pallet_row['THE_CODE']), 12);
			$the_bk = str_pad(trim($pallet_row['THE_BOOK']), 13);
			$the_order = str_pad(trim($pallet_row['ORDER_NUM']), 10);
			$the_linear = str_pad(trim($pallet_row['THE_LINEAR']), 12);
			$the_recman = str_pad(trim($pallet_row['THE_MANIFEST']), 14);
			$the_lb = str_pad(trim($pallet_row['WEIGHT_LB']), 8);
			$the_kg = str_pad(trim($pallet_row['WEIGHT_KG']), 8);
			$the_dmg = str_pad(trim($pallet_row['DAMAGE_YN']), 4);
			$the_checker = str_pad(trim($emp_name), 8);
			fwrite($handle, $the_BC.$the_qty.$the_size.$the_dia.$the_prod.$the_bk.$the_order.$the_linear.$the_recman.$the_lb.$the_kg.$the_dmg.$the_checker."\n");
		} while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		fwrite($handle, "\n");
		$disp_total_words = str_pad(trim("TOTALS:"), 30);
		$disp_total_rolls = str_pad(trim($total_rolls), 84);
		$disp_total_lb= str_pad(trim($total_lb), 8);
		$disp_total_kg = str_pad(trim($total_kg), 8);
		fwrite($handle, $disp_total_words.$disp_total_rolls.$disp_total_lb.$disp_total_kg."\n");
		fwrite($handle, "\n\n");

		// part 2:  summary
		$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI:SS AM'), '') START_TIME,
					NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI:SS AM'), '') END_TIME 
				FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD
				WHERE CA.ARRIVAL_NUM = '".$ARV."'
					AND ACTIVITY_NUM = '1'
					AND CA.PALLET_ID = BAD.PALLET_ID
					AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
					AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CA.CUSTOMER_ID = '".$cust."'
					".$WSC_code_SQL."
					AND BAD.BOL = '".$manifest."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$start = $row['START_TIME'];
		$end = $row['END_TIME'];

		$sql = "SELECT SUM(QTY_RECEIVED) THE_REC, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, 
					NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, 
                    BAD.ORDER_NUM, BAD.BOL THE_MANIFEST, SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB, SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG 
                FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, 
					BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 
                WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID 
                    AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID 
                    AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM 
					AND UC1.SECONDARY_UOM = 'CM'  
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM 
					AND UC2.SECONDARY_UOM = 'CM'  
					AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM 
					AND UC3.SECONDARY_UOM = 'LB'  
					AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM 
					AND UC4.SECONDARY_UOM = 'KG' 
                    AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+) 
                    AND CA.ACTIVITY_NUM = '1' 
                    AND BAD.BOL = '".$manifest."'
                    AND CA.ORDER_NUM = '".$ARV."'
					".$WSC_code_SQL."
					AND CA.CUSTOMER_ID = '".$cust."'
                    AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY HH:MI:SS AM') 
                    AND DATE_OF_ACTIVITY <= TO_DATE('".$end."', 'MM/DD/YYYY HH:MI:SS AM') 
                GROUP BY ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR), NVL(SSCC_GRADE_CODE, '--NONE--'), NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM, BAD.BOL";
		ora_parse($summary_cursor, $sql);
		ora_exec($summary_cursor);
		ora_fetch_into($summary_cursor, $summary_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$disp_total_words = str_pad("Receiving Summary:", 30);
		fwrite($handle, $disp_total_words."\n");
		do {
			$the_BC = str_pad("", 30);
			$the_qty = str_pad(trim($summary_row['THE_REC']), 4);
			$the_size= str_pad(trim($summary_row['THE_WIDTH']), 10);
			$the_dia = str_pad(trim($summary_row['THE_DIA']), 9);
			$the_prod = str_pad(trim($summary_row['THE_CODE']), 12);
			$the_bk = str_pad(trim($summary_row['THE_BOOK']), 13);
			$the_order = str_pad(trim($summary_row['ORDER_NUM']), 10);
			$the_linear = str_pad("", 12);
			$the_recman = str_pad(trim($summary_row['THE_MANIFEST']), 14);
			$the_lb = str_pad(trim($summary_row['WEIGHT_LB']), 8);
			$the_kg = str_pad(trim($summary_row['WEIGHT_KG']), 8);
			fwrite($handle, $the_BC.$the_qty.$the_size.$the_dia.$the_prod.$the_bk.$the_order.$the_linear.$the_recman.$the_lb.$the_kg.$the_dmg.$the_checker."\n");
		} while(ora_fetch_into($summary_cursor, $summary_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fwrite($handle, "\n\n");

		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
		system($tally_print_command);

		
		// part 3:  dmage recap
		if($any_damage){
			$filename2 = "BookIB_".$ARV."_".$manifest."_DMG_".$timedatestamp;
			$handle2 = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename2.".txt", "w");

			/* Print Tally Banner Info */
			fwrite($handle2, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."\n\n");
			fwrite($handle2, "PORT OF WILMINGTON\nBOOKING INBOUND TALLY\nDAMAGE REPORT\n\n");
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			fwrite($handle2, "Customer: ".$row['CUSTOMER_NAME']."\n");
			fwrite($handle2, "Railcar: ".$ARV."\n");
			if($manifest != ""){
				fwrite($handle2, "Manifest#: ".$manifest."\n");
			}

			$sql = "SELECT BAD.PALLET_ID, NVL(BAD.BOOKING_NUM, 'NONE') THE_BOOK, DAMAGE_ID, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') THE_DATE, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI'), 'NO') THE_CLEARED, 
						CHECKER_ENTERED, NVL(CHECKER_CLEARED, '') CHECK_CLEARED, DAMAGE_TYPE, NVL(EXTRA_DESC, ' ') THE_QUAN, OCCURRED 
                    FROM BOOKING_DAMAGES BD, BOOKING_ADDITIONAL_DATA BAD 
                    WHERE BAD.RECEIVER_ID = '".$cust."' 
						AND BAD.ARRIVAL_NUM = '".$ARV."'
						".$WSC_code_SQL."
						AND BD.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND BD.RECEIVER_ID = BAD.RECEIVER_ID AND BAD.PALLET_ID = BD.PALLET_ID 
						AND BAD.PALLET_ID IN 
							(SELECT PALLET_ID 
							FROM CARGO_ACTIVITY 
							WHERE ARRIVAL_NUM = '".$ARV."' 
								AND CUSTOMER_ID = '".$cust."' 
								AND ACTIVITY_NUM = '1' 
								AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY HH:MI:SS AM') 
								AND DATE_OF_ACTIVITY <= TO_DATE('".$end."', 'MM/DD/YYYY HH:MI:SS AM') 
							) 
					ORDER BY PALLET_ID, DAMAGE_ID";
			ora_parse($damage_cursor, $sql);
			ora_exec($damage_cursor);
			ora_fetch_into($damage_cursor, $damage_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$the_book = str_pad("BOOKING#", 20);
			$the_BC = str_pad("BARCODE", 33);
			$the_dmg = str_pad("DMG TYPE", 8);
			$the_qty = str_pad("QTY", 10);
			$the_rec = str_pad("RECORDED", 25);
			$the_resp = str_pad("RESPONSIBLE", 15);
			$the_clear = str_pad("CLEARED ON", 25);
			fwrite($handle2, $the_book.$the_BC.$the_dmg.$the_qty.$the_rec.$the_resp.$the_clear."\n");
			do{
				$the_book = str_pad(trim($damage_row['THE_BOOK']), 20);
				$the_BC = str_pad(trim($damage_row['PALLET_ID']), 33);
				$the_dmg = str_pad(trim($damage_row['DAMAGE_TYPE']), 8);
				$the_qty = str_pad(trim($damage_row['THE_QUAN']), 10);
				$the_rec = str_pad(trim($damage_row['THE_DATE']), 25);
				$the_resp = str_pad(trim($damage_row['OCCURRED']), 15);
				$the_clear = str_pad(trim($damage_row['THE_CLEARED']), 25);
				fwrite($handle2, $the_book.$the_BC.$the_dmg.$the_qty.$the_rec.$the_resp.$the_clear."\n");
			} while(ora_fetch_into($damage_cursor, $damage_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			fclose($handle2);
			$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename2.".txt";
			system($tally_print_command);
		}

		exit;
	}