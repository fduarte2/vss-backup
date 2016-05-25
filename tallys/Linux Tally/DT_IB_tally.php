<?

include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$short_term_data_cursor2 = ora_open($rf_conn);


	$selection = "oye";
	while($selection != "1" && $selection != "2"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nDockticket IB TALLY\nEnter X to exit.\n\n";
		echo "1)  Print by Railcar\n2)  Print by Dock Ticket\n";
		fscanf(STDIN, "%s\n", $selection);
		$selection = strtoupper($selection);
		if($selection == "X"){
			return;
		}
		$selection = remove_badchars($selection);
	}
	$ARV = "ALL";
	$DT = "ALL";

	if($selection == "1"){
		while($ARV == "ALL" || $ARV == "Invalid"){
			print chr(27)."[H".chr(27)."[2J";
			echo "\nDockticket IB TALLY\nEnter X to exit.\n\n";
			if($ARV != "ALL"){
				echo "Invalid Railcar #\n";
			}
			echo "Railcar#:\n";
			fscanf(STDIN, "%s\n", $ARV);
			$ARV = strtoupper($ARV);
			if($ARV == "X"){
				return;
			}
			$ARV = remove_badchars($ARV);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$ARV."' AND REMARK = 'DOLEPAPERSYSTEM' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nRailcar\n(DtI1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nRailcar\n(DtI1b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$ARV = "Invalid";
			} elseif($short_term_data_row['THE_COUNT'] <= 0){
				$ARV = "Invalid";
			}			
		}

		while($selection_date != "1" && $selection_date != "2"){
			print chr(27)."[H".chr(27)."[2J";
			echo "\nDockticket IB TALLY\nRailcar ".$ARV."\nEnter X to exit.\n\n";
			echo "1)  Today's Date\n2)  Enter Date range\n";
			fscanf(STDIN, "%s\n", $selection_date);
			$selection_date = strtoupper($selection_date);
			if($selection_date == "X"){
				return;
			}
			$selection_date = remove_badchars($selection_date);
		}

		if($selection_date == "2"){
			$start = "";
			while($start == "" || $start == "Invalid"){
				print chr(27)."[H".chr(27)."[2J";
				echo "\nDockticket IB TALLY\nRailcar ".$ARV."\nEnter X to exit.\n\n";
				if($start != ""){
					echo "Invalid Start Date\n";
				}
				echo "Start Date\n (must be in MM/DD/YYYY):\n";
				fscanf(STDIN, "%s\n", $start);
				$start = strtoupper($start);
				if($start == "X"){
					return;
				}
//				$start = remove_badchars($start);

				if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $start)){
//					echo "entered: ".$start."\n";
//					fscanf(STDIN, "%s\n", $junk);
					$start = "Invalid";
				} 			
			}
			$end = "";
			while($end == "" || $end == "Invalid"){
				print chr(27)."[H".chr(27)."[2J";
				echo "\nDockticket IB TALLY\nRailcar ".$ARV."\nEnter X to exit.\n\n";
				if($end != ""){
					echo "Invalid End Date\n";
				}
				echo "End Date\n (must be in MM/DD/YYYY):\n";
				fscanf(STDIN, "%s\n", $end);
				$end = strtoupper($end);
				if($end == "X"){
					return;
				}
//				$end = remove_badchars($end);

				if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $end)){
					$end = "Invalid";
				} 			
			}


		} else {
			$start = date('m/d/Y');
			$end = date('m/d/Y');
		}

	} else {

		while($DT == "ALL" || $DT == "Invalid"){
			print chr(27)."[H".chr(27)."[2J";
			echo "\nDockticket IB TALLY\nEnter X to exit.\n\n";
			if($DT != "ALL"){
				echo "Invalid Dockticket #\n";
			}
			echo "Dock Ticket#:\n";
			fscanf(STDIN, "%s\n", $DT);
			$DT = strtoupper($DT);
			if($DT == "X"){
				return;
			}
			$DT = remove_badchars($DT);

			if($DT != ""){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE BOL = '".$DT."' AND REMARK = 'DOLEPAPERSYSTEM' AND DATE_RECEIVED IS NOT NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDT\n(DtI2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nDT\n(DtI2b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$DT = "Invalid";
				} elseif($short_term_data_row['THE_COUNT'] <= 0){
					$DT = "Invalid";
				}
			}
		}

		$sql = "SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' AND BOL = '".$DT."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nDT\n(DtI2-2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nDT\n(DtI2-2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$ARV = $short_term_data_row['ARRIVAL_NUM'];

	}

	if ($kiosk_name ==  "") {
		$kiosk_name = "Testing";
	}
	echo "\nProcessing Request\n".$ARV."-".$DT."...\n";

	$sql = "SELECT BOL, RECEIVER_ID, CUSTOMER_NAME
			FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CP
			WHERE CT.RECEIVER_ID = CP.CUSTOMER_ID
				AND REMARK = 'DOLEPAPERSYSTEM'";
	if($DT == "ALL"){
		$sql .=" AND ARRIVAL_NUM = '".$ARV."' AND DATE_RECEIVED >= TO_DATE('".$start."', 'MM/DD/YYYY') AND DATE_RECEIVED <= TO_DATE('".$end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
	}
	if($DT != "ALL"){
		$sql .=" AND BOL = '".$DT."'";
	}
	$sql .= " ORDER BY RECEIVER_ID, CUSTOMER_NAME";
//	echo $sql."\n";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\ncust\n(DtI3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\ncust\n(DtI3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$cust = $short_term_data_row['RECEIVER_ID'];
	$custname = $short_term_data_row['CUSTOMER_NAME'];
	$bol_for_conditional_print = $short_term_data_row['BOL'];


	$timedatestamp = date("Y_m_d_H_i_s");
	$filename = "DoleDTIB_".$ARV."_".$DT."_".$timedatestamp;
	$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

	/* Print Tally Banner Info */
	fwrite($handle, "Printed From: ".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."   v2\n\n");
	fwrite($handle, "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Inbound)\n\n");
	fwrite($handle, "Order Number: ".$ARV."\n");
	fwrite($handle, "Customer: ".$custname."\n");
//	fwrite($handle, "Dockticket#:".$DT."\n");

	$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_END 
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT 
			WHERE CA.PALLET_ID = CT.PALLET_ID 
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM 
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID 
				AND CT.REMARK = 'DOLEPAPERSYSTEM' 
				AND SERVICE_CODE IN ('8')"; 
	if($DT != "ALL"){
		$sql .= " AND CT.BOL = '".$DT."'";
	}
	if($DT == "ALL"){
		$sql .= " AND CA.ORDER_NUM = '".$ARV."' AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY') AND DATE_OF_ACTIVITY <= TO_DATE('".$end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
	}
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	fwrite($handle, "First Scan:".$short_term_row['THE_START']."\n");
	fwrite($handle, "Last Scan:".$short_term_row['THE_END']."\n");

	$sql = "SELECT DOLEPAPER_ORIGINAL_MILL 
			FROM CARGO_TRACKING_ADDITIONAL_DATA 
			WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE BOL = '".$bol_for_conditional_print."')";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\ncust\n(DtI3-2a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\ncust\n(DtI3-2b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_data_row['DOLEPAPER_ORIGINAL_MILL'] != ""){
		$sql = "SELECT * FROM DOLEPAPER_EDI_MILL_CODES WHERE MILL_ID = '".$short_term_data_row['DOLEPAPER_ORIGINAL_MILL']."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\ncust\n(DtI3-2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\ncust\n(DtI3-2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['MILL_ID'] != ""){
			fwrite($handle, "From: ".$short_term_data_row['MILL_ID']." - ".$short_term_data_row['MILL_NAME']."\n");
		}
	}

	fwrite($handle, "\n");

	$the_DT = str_pad("TICKET#", 8);
	$the_BC = str_pad("BARCODE", 30);
	$the_mark = str_pad("MARK", 54);
	$the_qty = str_pad("QTY", 4);
	$the_wt = str_pad("WT", 25);
	$the_st = str_pad("ST", 8);
	$the_msf = str_pad("MSF", 10);
	$the_chkr = str_pad("CHECKER", 11);
	$the_dmg = str_pad("DMG", 4);
	$the_received = str_pad("RECEIVED", 15);
	fwrite($handle, $the_DT.$the_BC.$the_mark.$the_qty.$the_wt.$the_st.$the_msf.$the_chkr.$the_dmg.$the_received."\n");
	fwrite($handle, "=========================================================================================================================================================================\n");

	$sql = "SELECT CT.PALLET_ID, CT.CARGO_DESCRIPTION, CA.QTY_CHANGE, CT.QTY_UNIT, CT.WEIGHT, 
				ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) SHORT_TONS, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YY HH24:MI') THE_REC, CT.BOL, NVL(VARIETY, '0') THE_LINEAR_FEET, CARGO_SIZE, CA.CUSTOMER_ID 
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, UNIT_CONVERSION_FROM_BNI UC 
			WHERE CT.PALLET_ID = CA.PALLET_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
				AND CA.SERVICE_CODE = '8' 
				AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM 
				AND UC.SECONDARY_UOM = 'TON'
				AND REMARK = 'DOLEPAPERSYSTEM'";
	if($DT != "ALL"){
		$sql .= " AND CT.BOL = '".$DT."'";
	}
	if($DT == "ALL"){
		$sql .= " AND CA.ORDER_NUM = '".$ARV."' AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY') AND DATE_OF_ACTIVITY <= TO_DATE('".$end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
	}
	$sql .= " ORDER BY CT.BOL, CT.PALLET_ID";

	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$row['PALLET_ID']."' AND DOCK_TICKET = '".$row['BOL']."'";
		ora_parse($short_term_data_cursor2, $sql);
		ora_exec($short_term_data_cursor2);
		ora_fetch_into($short_term_data_cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($row2['THE_COUNT'] > 0){
			$display_damage = 'Y';
			$any_damage = TRUE;
		} else {
			$display_damage = 'N';
		}

		$total_rolls++;
		$total_weight += $row['WEIGHT'];
		$total_ST += $row['SHORT_TONS'];

		$msf = round(($row['THE_LINEAR_FEET'] * ($row['CARGO_SIZE'] / 12)) / 1000, 3);
		$total_msf += $msf;

		$emp_name = get_employee_for_print($row['PALLET_ID'], $ARV, $row['CUSTOMER_ID'], "1", $rf_conn);

		$the_DT = str_pad(trim($row['BOL']), 8);
		$the_BC = str_pad(trim($row['PALLET_ID']), 30);
		$the_mark = str_pad(trim($row['CARGO_DESCRIPTION']), 54);
		$the_qty = str_pad(trim($row['QTY_CHANGE']), 4);
		$the_wt = str_pad(trim($row['WEIGHT']."LB/".round(($row['WEIGHT'] / 2.2), 2)."KG"), 25);
		$the_st = str_pad(trim($row['SHORT_TONS']), 8);
		$the_msf = str_pad(trim($msf), 10);
		$the_chkr = str_pad(trim($emp_name), 11);
		$the_dmg = str_pad(trim($display_damage), 4);	
		$the_received = str_pad(trim($row['THE_REC']), 15);
		fwrite($handle, $the_DT.$the_BC.$the_mark.$the_qty.$the_wt.$the_st.$the_msf.$the_chkr.$the_dmg.$the_received."\n");
	}

	$disp_total_words = str_pad(trim("TOTALS:"), 92);
	$disp_total_rolls = str_pad(trim($total_rolls), 4);
	$disp_total_wt= str_pad(trim($total_weight."LB/".round(($total_weight / 2.2), 2)."KG"), 25);
	$disp_total_st= str_pad(trim($total_ST), 8);
	$disp_total_msf = str_pad(trim($total_msf), 34);
	fwrite($handle, $disp_total_words.$disp_total_rolls.$disp_total_wt.$disp_total_st.$disp_total_msf."\n");
	fwrite($handle, "\n");

	fclose($handle);
	echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
	$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
	system($tally_print_command);

	if($any_damage){
		$filename2 = "DoleDTIB_".$ARV."_".$DT."_DMG_".$timedatestamp;
		$handle2 = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename2.".txt", "w");
		/* Print Tally Banner Info */
		fwrite($handle2, "Printed From: ".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."   v2\n\n");
		fwrite($handle2, "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Inbound)\n---DAMAGE REPORT ---\n\n");
		fwrite($handle2, "Order Number: ".$ARV."\n");
		fwrite($handle2, "Customer: ".$custname."\n");


//		fwrite($handle, "DAMAGE RECAP\n");
		$the_BC = str_pad("BARCODE", 33);
		$the_dmgtype = str_pad("DAMAGE TYPE", 13);
		$the_dmgqty = str_pad("DAMAGE QTY (if applicable)", 27);
		$the_rec_on = str_pad("RECORDED ON", 17);
		$the_was_dmged = str_pad("WAS DAMAGED", 13);
		$the_cleared = str_pad("CLEARED TO SHIP (if was rejected)", 37);
		fwrite($handle2, $the_BC.$the_dmgtype.$the_dmgqty.$the_rec_on.$the_was_dmged.$the_cleared."\n");
		fwrite($handle2, "=========================================================================================================================================================================\n");

		$sql = "SELECT ROLL, DMG_TYPE, OCCURRED, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') WHEN_REC, QUANTITY || QTY_TYPE THE_QUAN, TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI') THE_CLEARED FROM DOLEPAPER_DAMAGES WHERE 1 = 1 ";
		if($DT == "ALL"){
			$sql .= " AND ROLL IN (SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$ARV."' AND SERVICE_CODE = '8' AND ACTIVITY_DESCRIPTION IS NULL
									AND DATE_OF_ACTIVITY >= TO_DATE('".$start."', 'MM/DD/YYYY') AND DATE_OF_ACTIVITY <= TO_DATE('".$end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')  ) ";
		}
		if($DT != "ALL"){
			$sql .= " AND DOCK_TICKET = '".$DT."'";
		}
		$sql .= " AND DOCK_TICKET IN (SELECT BOL FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$ARV."') ORDER BY ROLL, DATE_ENTERED";
//		echo $sql."\n";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$the_BC = str_pad(trim($short_term_row['ROLL']), 33);
			$the_dmgtype = str_pad(trim($short_term_row['DMG_TYPE']), 13);
			$the_dmgqty = str_pad(trim($short_term_row['THE_QUAN']), 27);
			$the_rec_on = str_pad(trim($short_term_row['WHEN_REC']), 17);
			$the_was_dmged = str_pad(trim($short_term_row['OCCURRED']), 13);
			$the_cleared = str_pad(trim($short_term_row['THE_CLEARED']), 37);
			fwrite($handle2, $the_BC.$the_dmgtype.$the_dmgqty.$the_rec_on.$the_was_dmged.$the_cleared."\n");
		}

		fclose($handle2);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename2.".txt";
		system($tally_print_command);

	}
	
	exit;
