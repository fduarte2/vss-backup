<?
include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$pallet_cursor = ora_open($rf_conn);

	$cust = "";
	while($cust == "" || $cust == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nSTEEL TALLY\nEnter X to exit.\n\n";
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
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(StO1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(StO1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$cust = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$cust = "Invalid";
		}			
		
	}



	$order = "";
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
		database_check($ora_success, "Cannot Retrieve\nOrder\n(StO2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(StO2b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$order = "Invalid";
		}
	}

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

	$sql = "SELECT CT.PALLET_ID, CT.CARGO_DESCRIPTION, CA.QTY_CHANGE, (WEIGHT / QTY_RECEIVED) WT_PER, CA.ACTIVITY_NUM, CA.ARRIVAL_NUM
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.PALLET_ID = CA.PALLET_ID And CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CA.ORDER_NUM = '".$order."' AND CA.CUSTOMER_ID = '".$cust."'
				AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL";
	ora_parse($pallet_cursor, $sql);
	ora_exec($pallet_cursor);
	if(!ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	} else {
		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "Steel_".$order."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

		/* Print Tally Banner Info */
		fwrite($handle, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."\n\n");
		fwrite($handle, "PORT OF WILMINGTON STEEL OUTBOUND TALLY\n\n");
		fwrite($handle, "Port Order#: ".$order."\n");
		fwrite($handle, "Customer: ".$custname."\n");
		fwrite($handle, "Vessel: ".$vesname."\n");
		fwrite($handle, "LICENSE / RAILCAR: ".$licnum."\n\n");
		fwrite($handle, "First Scan: ".$start."\n");
		fwrite($handle, "Last Scan: ".$end."\n");
		fwrite($handle, "Commodity: ".$commname."\n");
		fwrite($handle, "DO#: ".$DO."\n\n");

		$total_weight = 0;
		$total_rolls = 0;

		$the_BC = str_pad("BARCODE", 33);
		$the_mark = str_pad("MARK", 60);
		$the_pcs = str_pad("PCS", 5);
		$the_lb = str_pad("WEIGHT (LB)", 12);
		$the_checker = str_pad("CHECKER", 8);
		fwrite($handle, $the_BC.$the_mark.$the_pcs.$the_lb.$the_checker."\n");
		fwrite($handle, "================================================================================================================================================\n");
		do {
			$emp_name = get_employee_for_print($pallet_row['PALLET_ID'], $vessel, $cust, $pallet_row['ACTIVITY_NUM'], $rf_conn);
			$total_weight += round($pallet_row['WT_PER'] * $pallet_row['QTY_CHANGE']);
			$total_rolls += $pallet_row['QTY_CHANGE'];

			$the_BC = str_pad(trim($pallet_row['PALLET_ID']), 33);
			$the_mark = str_pad(trim($pallet_row['CARGO_DESCRIPTION']), 60);
			$the_pcs= str_pad(trim($pallet_row['QTY_CHANGE']), 5);
			$the_lb = str_pad(number_format($pallet_row['WT_PER'] * $pallet_row['QTY_CHANGE']), 12);
			$the_checker = str_pad(trim($emp_name), 8);
			fwrite($handle, $the_BC.$the_mark.$the_pcs.$the_lb.$the_checker."\n");
		} while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fwrite($handle, "\n");
		fwrite($handle, "================================================================================================================================================\n");

		$disp_total_words = str_pad(trim("TOTALS:"), 93);
		$disp_total_qty = str_pad(trim($total_rolls), 5);
		$disp_total_lb = str_pad(number_format($total_weight), 8);
		fwrite($handle, $disp_total_words.$disp_total_qty.$disp_total_lb."\n");
		fwrite($handle, "\n\n");

		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
		system($tally_print_command);
		exit;
	}