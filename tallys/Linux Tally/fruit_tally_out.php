<?
include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$pallet_cursor = ora_open($rf_conn);
$desc_cursor = ora_open($rf_conn);

	$cust = "";
	while($cust == "" || $cust == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nFRUIT TALLY\n(Outbound)\nEnter X to exit.\n\n";
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

	$order = "";
	while($order == "" || $order == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nFRUIT TALLY\n(Outbound)\nEnter X to exit.\n\n";
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND CUSTOMER_ID = '".$cust."' AND ACTIVITY_NUM != '1'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(FrO2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(FrO2b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$order = "Invalid";
		}
	}

	$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
			NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
			FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND ACTIVITY_NUM != '1' AND CUSTOMER_ID = '".$cust."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	if(!ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$start = "";
		$end = "";
	} else {
		$start = $row['START_TIME'];
		$end = $row['END_TIME'];
	}

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$custname = $short_term_data_row['CUSTOMER_NAME'];

	$sql = "SELECT PALLET_ID, QTY_CHANGE, ACTIVITY_NUM, ARRIVAL_NUM, SERVICE_CODE, 
				DECODE(SERVICE_CODE, '5', 'TR', '7', 'FR', '9', 'RE', '12', 'VO', '13', 'DR', '') THE_SERV
			FROM CARGO_ACTIVITY
			WHERE ORDER_NUM = '".$order."' AND CUSTOMER_ID = '".$cust."'
				AND SERVICE_CODE NOT IN ('1', '8', '12', '18', '19', '21', '22') 
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
			ORDER BY PALLET_ID, ACTIVITY_NUM";
	ora_parse($pallet_cursor, $sql);
	ora_exec($pallet_cursor);
	if(!ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	} else {
		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "Fruitout_".$order."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

		$swingflag = "";
		$methylflag = false;

		/* Print Tally Banner Info */
		fwrite($handle, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."\n     tampa/home/kiosk/fruit_out.php\n");
		fwrite($handle, "PORT OF WILMINGTON CHILEAN FRUIT OUTBOUND TALLY\n");
		fwrite($handle, "Customer: ".$custname."\n");
		fwrite($handle, "Order#: ".$order."\n");
		if($pallet_row['SERVICE_CODE'] != "11"){
			fwrite($handle, "SHIPPING TYPE:  OUTBOUND\n");
		} else {
			fwrite($handle, "SHIPPING TYPE:  OUTBOUND - TRANSFER\n");
		}
		fwrite($handle, "First Scan: ".$start."\n");
		fwrite($handle, "Last Scan: ".$end."\n\n");

		$the_BC = str_pad("BARCODE", 33);
		$the_desc = str_pad("DESCRIPTION", 65);
		if ($cust == 1131) //1131 is KOPKE
			$the_grower = str_pad("GROWER", 10);
		else $the_grower = str_pad(" ", 10);
		$the_qty = str_pad("QTY", 9);
		$the_ves = str_pad("VESSEL", 35);
		$the_checker = str_pad("CHECKER", 8);
		fwrite($handle, $the_BC.$the_desc.$the_grower.$the_qty.$the_ves.$the_checker."\n");
		fwrite($handle, "===============================================================================================================================================================\n");
		do {
			$emp_name = get_employee_for_print($pallet_row['PALLET_ID'], $pallet_row['ARRIVAL_NUM'], $cust, $pallet_row['ACTIVITY_NUM'], $rf_conn);
			$total_ctn += $pallet_row['QTY_CHANGE'];
			$total_plt++;

			$the_BC = str_pad(trim($pallet_row['PALLET_ID']), 33);
			$the_desc = str_pad(GetPalletDesc($pallet_row['PALLET_ID'], $pallet_row['ARRIVAL_NUM'], $cust, $swingflag, $methylflag, $rf_conn), 65);
			$the_grower = str_pad(GetGrower($pallet_row['PALLET_ID'], $pallet_row['ARRIVAL_NUM'], $cust, $rf_conn), 10);
			$the_qty = str_pad($pallet_row['QTY_CHANGE']." ".$pallet_row['THE_SERV'], 9);
			$the_ves = str_pad(GetVesName($pallet_row['ARRIVAL_NUM'], $rf_conn), 35);
			$the_checker = str_pad(trim($emp_name), 8);
			fwrite($handle, $the_BC.$the_desc.$the_grower.$the_qty.$the_ves.$the_checker."\n");
		} while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fwrite($handle, "===============================================================================================================================================================\n");

		// outbound has a whole list of criteria for being counted on the totals.  Inbound is just 1 per pallet.
		$total_ctn = 0;
		$total_plt = 0;
		$sql = "SELECT PALLET_ID, SUM(DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), 12, (-1 * QTY_CHANGE), QTY_CHANGE)) THE_CTNS
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = '".$order."'
					AND CUSTOMER_ID = '".$cust."'
					AND ACTIVITY_NUM != '1'
				GROUP BY PALLET_ID";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_CTNS'] > 0){
				$total_ctn += $short_term_data_row['THE_CTNS'];
				$total_plt++;
			}
		}
		$disp_total_spaces = str_pad("", 88);
		$disp_total_words = str_pad(trim("Total Cases:"), 20);
		$disp_total_qty = str_pad(trim($total_ctn), 9);
		fwrite($handle, $disp_total_spaces.$disp_total_words.$disp_total_qty."\n");
		$disp_total_spaces = str_pad("", 88);
		$disp_total_words = str_pad(trim("Total Pallets:"), 20);
		$disp_total_qty = str_pad(trim($total_plt), 9);
		fwrite($handle, $disp_total_spaces.$disp_total_words.$disp_total_qty."\n");
		fwrite($handle, "\n");

		if($swingflag != ""){
			fwrite($handle, "**** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD ****\n\n");
			fwrite($handle, "          Swing Load: Container #".$swingflag.".  PoW Clerk must have copy of CBP signed/stamped T&E for issuance of a gate pass. \n\n\n");
			//fwrite($handle, "signed/stamped T&E for issuance of a gate pass.\n");
//			fwrite($handle, "**** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD *** SWING LOAD ****\n\n");
		}

		if($methylflag == true){
			fwrite($handle, "   ******** IMPORTANT NOTE ******** IMPORTANT NOTE ******** IMPORTANT NOTE ******** IMPORTANT NOTE ********* IMPORTANT NOTE ********* IMPORTANT NOTE *********\n");
			fwrite($handle, "         **Some, Part, or all of this cargo may have been fumigated with Methyl Bromide.**\n");
			fwrite($handle, "         **Esta carga o parte de ella pudo haber sido fumigada con Bromuro de Metilo.**\n");
			fwrite($handle, "   ******** IMPORTANT NOTE ******** IMPORTANT NOTE ******** IMPORTANT NOTE ******** IMPORTANT NOTE ********* IMPORTANT NOTE ********* IMPORTANT NOTE *********");
		}


		if($cust == "3000"){
			fwrite($handle, "\n\n".str_pad(trim("Item Desc"), 30).str_pad(trim("Item#"), 20).str_pad(trim("PLT"), 10).str_pad(trim("CTN"), 10)."\n");
			// WALMART SUBTOTALS
			$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_COUNT, SUM(QTY_CHANGE) THE_SUM, BOL, NVL(WM_COMMODITY_NAME, 'N/A') THE_NAME
					FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WM_ITEM_COMM_MAP WICM
					Where CUSTOMER_ID = '".$cust."'
						AND ORDER_NUM = '".$order."'
						AND SERVICE_CODE <>12
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')
						AND CA.PALLET_ID = CT.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CT.BOL = TO_CHAR(WICM.ITEM_NUM(+))
					GROUP BY BOL, NVL(WM_COMMODITY_NAME, 'N/A')
					ORDER BY BOL";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				fwrite($handle, str_pad(trim($short_term_data_row['THE_NAME']), 30).str_pad(trim($short_term_data_row['BOL']), 20).
							str_pad(trim($short_term_data_row['THE_COUNT']), 10).str_pad(trim($short_term_data_row['THE_SUM']), 10)."\n");
			}

			$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_COUNT, SUM(QTY_CHANGE) THE_SUM, BOL, WM_COMMODITY_NAME
					FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WM_ITEM_COMM_MAP WICM
					Where CUSTOMER_ID = '".$cust."'
						AND ORDER_NUM = '".$order."'
						AND SERVICE_CODE = 20
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')
						AND CA.PALLET_ID = CT.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CT.BOL = WICM.ITEM_NUM
					GROUP BY BOL, WM_COMMODITY_NAME
					ORDER BY BOL";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// do nothing
			} else {
				fwrite($handle, "\n\nRepack-Returned Summary\n");
				fwrite($handle, str_pad(trim("Item Desc"), 30).str_pad(trim("Item#"), 20).str_pad(trim("PLT"), 10).str_pad(trim("CTN"), 10)."\n");
				do {
					fwrite($handle, str_pad(trim($short_term_data_row['WM_COMMODITY_NAME']), 30).str_pad(trim($short_term_data_row['BOL']), 20).
								str_pad(trim($short_term_data_row['THE_COUNT']), 10).str_pad(trim($short_term_data_row['THE_SUM']), 10)."\n");
				} while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}
		}



		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
		system($tally_print_command);
		system($tally_print_command);
		system($tally_print_command);
		exit;
	}







	

	
	
	


function GetPalletDesc($pallet, $vessel, $cust, &$swingflag, &$methylflag, $rf_conn){
	$short_term_data_cursor = ora_open($rf_conn);
	$short_term_data_cursor2 = ora_open($rf_conn);

	$return = "";

	$sql = "SELECT COMMODITY_NAME, CT.COMMODITY_CODE,
				DECODE(VARIETY, NULL, '', ' - ' || VARIETY) THE_VAR, 
				DECODE(REMARK, NULL, '', ' - ' || REMARK) THE_REM, 
				DECODE(CARGO_SIZE, NULL, '', ' - ' || CARGO_SIZE) THE_SIZE, 
				NVL(WAREHOUSE_LOCATION, 'NONE') THE_WHS,
				NVL(CONTAINER_ID, 'Not Specified.') THE_CONT
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE PALLET_ID = '".$pallet."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$vessel."'
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE";
//	echo $sql."\n";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$sql = "SELECT WDI_RECEIVING_PO, DECODE(WDI_GROWER_CODE, NULL, ' - GRO N/A', ' - GRO ' || WDI_GROWER_CODE) THE_GROW, 
				DECODE(WDI_OUTGOING_ITEM_NUM, NULL, ' - ITM# N/A', ' - ITM# ' || WDI_OUTGOING_ITEM_NUM) THE_ITEM 
			FROM WDI_ADDITIONAL_DATA 
			WHERE WDI_PALLET_ID = '".$pallet."' 
				AND WDI_RECEIVER_ID = '".$cust."' 
				AND WDI_ARRIVAL_NUM = '".$vessel."'";
	ora_parse($short_term_data_cursor2, $sql);
	ora_exec($short_term_data_cursor2);
	if(ora_fetch_into($short_term_data_cursor2, $short_term_data_row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC) && $short_term_data_row2['WDI_RECEIVING_PO'] != ""){
		$return = $short_term_data_row['COMMODITY_NAME']." - ".$short_term_data_row['THE_VAR']." - ".
			$short_term_data_row2['THE_GROW']." - BK# ".$short_term_data_row2['WDI_RECEIVING_PO'].$short_term_data_row2['THE_ITEM'].
			$short_term_data_row['THE_REM']." - ".$short_term_data_row['THE_SIZE'];
	} else {
		$return = $short_term_data_row['COMMODITY_NAME'].$short_term_data_row['THE_VAR'].$short_term_data_row['THE_REM'].$short_term_data_row['THE_SIZE'];
	}

	// NOT USED IN INBOUND TALLY
	if($short_term_data_row['THE_WHS'] == "SW"){
		$swingflag = $short_term_data_row['THE_CONT'];
	}
	if($short_term_data_row['COMMODITY_CODE'] != "7081" && $short_term_data_row['COMMODITY_CODE'] != "7082"){
		$methylflag = true;
	}
	return $return;
}


function GetGrower($pallet, $vessel, $cust, $rf_conn) {
	if ($cust != 1131) //1131 is KOPKE
		$return = '';
	else {
		$cursor = ora_open($rf_conn);
		
		$sql = "SELECT NVL(CARGO_DESCRIPTION, 'N/A') THE_GROWER
				FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND PALLET_ID = '".$pallet."'";
		
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$return = $row['THE_GROWER'];
	}
	return $return;
}


function GetVesName($vessel, $rf_conn){
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT * FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return $vessel."-"."TRUCKED-IN";
	} else {
		return $vessel."-".$short_term_data_row['VESSEL_NAME'];
	}
}
