<?
include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$short_term_data_cursor2 = ora_open($rf_conn);
$main_cursor = ora_open($rf_conn);
$pallet_cursor = ora_open($rf_conn);

	$order = "";
	while($order == "" || $order == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nDockticket OB TALLY\nEnter X to exit.\n\n";
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(DtO1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(DtO1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$order = "Invalid";
		}			
	}
/*
	$ARV = "";
	while($ARV == "" || $ARV == "Invalid"){
		system("clear");
		echo "\nDockticket OB TALLY\nEnter X to exit.\n\n";
		if($cust != ""){
			echo "Invalid Railcar #\n";
		}
		echo "Railcar#:\n";
		echo "(or \"all\" for All)\n";
		fscanf(STDIN, "%s\n", $ARV);
		$ARV = strtoupper($ARV);
		if($ARV == "X"){
			return;
		}
		$ARV = remove_badchars($ARV);

		if($ARV != "" && $ARV != "ALL"){
			$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_ORDER WHERE ARRIVAL_NUM = '".$ARV."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nRailcar\n(DtO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nRailcar\n(DtO2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$ARV = "Invalid";
			} elseif($short_term_data_row['THE_COUNT'] <= 0){
				$ARV = "Invalid";
			}	
		}
	}

	$cont = "";
	while($cont == "" || $cont == "Invalid"){
		system("clear");
		echo "\nDockticket OB TALLY\nEnter X to exit.\n\n";
		if($cust != ""){
			echo "Invalid Railcar #\n";
		}
		echo "Railcar#:\n";
		echo "(or \"all\" for All)\n";
		fscanf(STDIN, "%s\n", $cont);
		$cont = strtoupper($cont);
		if($cont == "X"){
			return;
		}
		$cont = remove_badchars($cont);

		if($cont != "" && $cont != "ALL"){
			$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_ORDER WHERE CONTAINER_ID = '".$cont."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nContainer\n(DtO3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nContainer\n(DtO3b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$cont = "Invalid";
			} elseif($short_term_data_row['THE_COUNT'] <= 0){
				$cont = "Invalid";
			}	
		}
	}
*/
	if ($kiosk_name ==  "") {
		$kiosk_name = "Testing";
	}
	echo "\nProcessing Request\n".$order."...\n";

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA WHERE ORDER_NUM = '".$order."' AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM')";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_row['THE_COUNT'] <= 0){
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	}

	$sql = "SELECT DO.ORDER_NUM, TO_CHAR(DO.SAIL_DATE, 'MM/DD/YYYY') THE_SAIL, TO_CHAR(DO.LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, VP.VESSEL_NAME, DD.DESTINATION, DS.ST_DESCRIPTION, DO.CONTAINER_ID,
				NVL(DO.SEAL, 'NONE') THE_SEAL, DO.BOOKING_NUM
		FROM DOLEPAPER_ORDER DO, DOLEPAPER_STATUSES DS, VESSEL_PROFILE VP, DOLEPAPER_DESTINATIONS DD 
		WHERE DO.ARRIVAL_NUM = VP.LR_NUM 
		AND DO.STATUS = DS.STATUS 
		AND DO.DESTINATION_NB = DD.DESTINATION_NB";
	if($order != ""){
		$sql .= " AND DO.ORDER_NUM = '".$order."'";
	}
	if($ARV != ""){
		$sql .= " AND DO.ARRIVAL_NUM = '".$ARV."'";
	}
	if($cont != ""){
		$sql .= " AND DO.CONTAINER_ID = '".$cont."'";
	}
	ora_parse($main_cursor, $sql);
	ora_exec($main_cursor);
	if(!ora_fetch_into($main_cursor, $main_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// this should never happen, but...
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	} else {
		$total_rolls = 0;
		$total_weight = 0;

		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "DoleDTOB_".$order."_".$ARV."_".$cont."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

		/* Print Tally Banner Info */
		$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_END FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE ORDER_NUM = '".$order."' AND CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CT.REMARK = 'DOLEPAPERSYSTEM' AND SERVICE_CODE = '6'"; 
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		fwrite($handle, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."   v2\n\n");
		fwrite($handle, "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Outbound)\n\n");
		fwrite($handle, str_pad("Order Number: ".$order, 60)."Destination:".$main_row['DESTINATION']."\n");
		fwrite($handle, str_pad("Vessel: ".$main_row['VESSEL_NAME'], 60)."First Scan: ".$short_term_row['THE_START']."\n");
		fwrite($handle, str_pad("Container: ".$main_row['CONTAINER_ID'], 60)."Last Scan: ".$short_term_row['THE_END']."\n");
		fwrite($handle, str_pad("Booking# :".$main_row['BOOKING_NUM'], 60)."Seal#:".$main_row['THE_SEAL']."\n\n");
//		fwrite($handle, "Seal#:".$main_row['SEAL']."\n");
//		fwrite($handle, "Load Date:".$main_row['THE_LOAD']."\n");
//		fwrite($handle, "Sail Date:".$main_row['THE_SAIL']."\n");
//		fwrite($handle, "Destination:".$main_row['DESTINATION']."\n");
//		fwrite($handle, "Status:".$main_row['ST_DESCRIPTION']."\n");

//		fwrite($handle, "First Scan:".$short_term_row['THE_START']."\n");
//		fwrite($handle, "Last Scan:".$short_term_row['THE_END']."\n\n");

		$the_DT = str_pad("TICKET#", 8);
		$the_BC = str_pad("BARCODE", 33);
		$the_mark = str_pad("MARK", 61);
		$the_qty = str_pad("QTY", 4);
		$the_wt = str_pad("WT", 25);
		$the_st = str_pad("ST", 4);
		$the_chkr = str_pad("CHECKER", 8);
		$the_dmg = str_pad("DMG", 4);
		$the_IB = str_pad("IB#", 15);
		fwrite($handle, $the_DT.$the_BC.$the_mark.$the_qty.$the_wt.$the_st.$the_chkr.$the_dmg.$the_IB."\n");
		fwrite($handle, "==========================================================================================================================================================================\n");

		$sql = "SELECT CT.PALLET_ID, CT.CARGO_DESCRIPTION, CA.QTY_CHANGE, CT.QTY_UNIT, CT.WEIGHT, CA.CUSTOMER_ID,
					CT.ARRIVAL_NUM, ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) SHORT_TONS, CT.BOL, CA.ACTIVITY_NUM 
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, UNIT_CONVERSION_FROM_BNI UC 
				WHERE CT.PALLET_ID = CA.PALLET_ID 
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CA.ORDER_NUM = '".$order."' 
					AND SERVICE_CODE = '6' 
					AND ACTIVITY_DESCRIPTION IS NULL 
					AND CT.REMARK = 'DOLEPAPERSYSTEM' 
					AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM 
					AND UC.SECONDARY_UOM = 'TON' 
				ORDER BY CT.PALLET_ID";
		ora_parse($pallet_cursor, $sql);
		ora_exec($pallet_cursor);
		while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$pallet_row['PALLET_ID']."' AND DOCK_TICKET = '".$pallet_row['BOL']."'";
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
			$total_weight += $pallet_row['WEIGHT'];

			$emp_name = get_employee_for_print($pallet_row['PALLET_ID'], $pallet_row['ARRIVAL_NUM'], $pallet_row['CUSTOMER_ID'], $pallet_row['ACTIVITY_NUM'], $rf_conn);

			$the_DT = str_pad(trim($pallet_row['BOL']), 8);
			$the_BC = str_pad(trim($pallet_row['PALLET_ID']), 33);
			$the_mark = str_pad(trim($pallet_row['CARGO_DESCRIPTION']), 61);
			$the_qty = str_pad(trim($pallet_row['QTY_CHANGE']), 4);
			$the_wt = str_pad(trim($pallet_row['WEIGHT']."LB/".round(($pallet_row['WEIGHT'] / 2.2), 2)."KG"), 25);
			$the_st = str_pad(trim($pallet_row['SHORT_TONS']), 4);
			$the_chkr = str_pad(trim($emp_name), 8);
			$the_dmg = str_pad(trim($display_damage), 4);	
			$the_IB = str_pad(trim($pallet_row['ARRIVAL_NUM']), 15);
			fwrite($handle, $the_DT.$the_BC.$the_mark.$the_qty.$the_wt.$the_st.$the_chkr.$the_dmg.$the_IB."\n");
		}

		$disp_total_words = str_pad(trim("TOTALS:"), 102);
		$disp_total_rolls = str_pad(trim($total_rolls), 4);
		$disp_total_wt= str_pad(trim($total_weight."LB/".round(($total_weight / 2.2), 2)."KG"), 29);
		fwrite($handle, $disp_total_words.$disp_total_rolls.$disp_total_wt."\n");
		fwrite($handle, "\n");

		if($any_damage){
			fwrite($handle, "DAMAGE RECAP\n");
			$the_BC = str_pad("BARCODE", 33);
			$the_dmgtype = str_pad("DAMAGE TYPE", 13);
			$the_dmgqty = str_pad("DAMAGE QTY (if applicable)", 27);
			$the_rec_on = str_pad("RECORDED ON", 17);
			$the_was_dmged = str_pad("WAS DAMAGED", 13);
			$the_cleared = str_pad("CLEARED TO SHIP (if was rejected)", 37);
			fwrite($handle, $the_BC.$the_dmgtype.$the_dmgqty.$the_rec_on.$the_was_dmged.$the_cleared."\n");
			fwrite($handle, "==========================================================================================================================================================================\n");

			$sql = "SELECT ROLL, DMG_TYPE, OCCURRED, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') WHEN_REC, QUANTITY || QTY_TYPE THE_QUAN, TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI') THE_CLEARED FROM DOLEPAPER_DAMAGES WHERE ROLL IN (SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL) ORDER BY ROLL, DATE_ENTERED";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$the_BC = str_pad(trim($short_term_row['ROLL']), 33);
				$the_dmgtype = str_pad(trim($short_term_row['DMG_TYPE']), 13);
				$the_dmgqty = str_pad(trim($short_term_row['THE_QUAN']), 27);
				$the_rec_on = str_pad(trim($short_term_row['WHEN_REC']), 17);
				$the_was_dmged = str_pad(trim($short_term_row['OCCURRED']), 13);
				$the_cleared = str_pad(trim($short_term_row['THE_CLEARED']), 37);
				fwrite($handle, $the_BC.$the_dmgtype.$the_dmgqty.$the_rec_on.$the_was_dmged.$the_cleared."\n");
			}
		}

		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
		system($tally_print_command);
		exit;
	}