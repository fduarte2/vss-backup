<?
include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$pallet_cursor = ora_open($rf_conn);
$summary_cursor = ora_open($rf_conn);

	$order = "";
	while($order == "" || $order == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nJUICE TALLY\nEnter X to exit.\n\n";
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM BNI_DUMMY_WITHDRAWAL WHERE D_DEL_NO = '".$order."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(JO1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(JO1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$order = "Invalid";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND SERVICE_CODE = '6'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder\n(JO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder\n(JO2b)");
			if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$order = "Invalid";
			} elseif($short_term_data_row['THE_COUNT'] <= 0){
				$order = "Invalid";
			}
		}
	}

	if ($kiosk_name ==  "") {
		$kiosk_name = "Testing";
	}
	echo "\nProcessing Request\n".$order."...\n";

	$sql = "SELECT * FROM BNI_DUMMY_WITHDRAWAL WHERE D_DEL_NO = '".$order."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder\n(JO3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder\n(JO3b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vessel = $short_term_data_row['LR_NUM'];
	$cust = $short_term_data_row['OWNER_ID'];
	$comm = $short_term_data_row['COMMODITY_CODE'];
	$unit = $short_term_data_row['UNIT2'];
	if($short_term_data_row['ORDER_NO'] == ""){
		$rec_order = "NONE";
	} else {
		$rec_order = $short_term_data_row['ORDER_NO'];
	}

	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nVessel\n(JO4a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nVessel\n(JO4b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vesname = $short_term_data_row['THE_VESSEL'];

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCustomer\n(JO5a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCustomer\n(JO5b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$custname = $short_term_data_row['CUSTOMER_NAME'];

	$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$comm."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCommodity\n(JO6a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCommodity\n(JO6b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$commname = $short_term_data_row['COMMODITY_NAME'];

	$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') THE_DATE 
			FROM CARGO_ACTIVITY
            WHERE ORDER_NUM = '".$order."' 
				AND SERVICE_CODE = '6'
            AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder\n(JO7a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder\n(JO7b)");
	ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$firstscan = $short_term_data_row['THE_DATE'];

	//and, the main loop.
	$sql = "SELECT CA.PALLET_ID THE_PALLET, QTY_CHANGE THE_CHANGE, NVL(JUICE_REBANDS, 0) THE_DAMAGE,  BOL, CARGO_DESCRIPTION, ACTIVITY_NUM, CA.ARRIVAL_NUM, CA.CUSTOMER_ID
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE ORDER_NUM = '".$order."' 
				AND SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND CA.PALLET_ID = CT.PALLET_ID 
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM 
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID(+) 
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID(+) 
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM(+)";
	ora_parse($pallet_cursor, $sql);
	ora_exec($pallet_cursor);
	if(!ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	} else {
		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "Juice_".$order."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

		/* Print Tally Banner Info */
		fwrite($handle, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."\n\n");
		fwrite($handle, "PORT OF WILMINGTON\bJUICE TALLY\n\n");
		fwrite($handle, str_pad("Order: ".$order, 70)."Vessel: ".$vesname."\n\n");

		fwrite($handle, str_pad("Scanned Starting: ".$firstscan, 70)."Customer:  ".$custname."\n");
		fwrite($handle, str_pad("Receiving Order: ".$rec_order, 70)."Commodity:  ".$commname."\n\n");

		$total_bc = 0;
		$total_reband = 0;
//		$total_lb = 0;
//		$total_kg = 0;

		$the_bol = str_pad("BOL", 15);
		$the_BC = str_pad("BARCODE", 33);
		$the_mark = str_pad("HEADMARK/LOT", 60);
		$the_qty = str_pad("QTY/UOM", 15);
		$the_reband = str_pad("REBAND", 7);
		$the_checker = str_pad("CHECKER", 8);
		fwrite($handle, $the_bol.$the_BC.$the_mark.$the_qty.$the_reband.$the_checker."\n");
		fwrite($handle, "================================================================================================================================================\n");
		do {
			$emp_name = get_employee_for_print($pallet_row['THE_PALLET'], $vessel, $cust, $pallet_row['ACTIVITY_NUM'], $rf_conn);
			$the_bol = str_pad(trim($pallet_row['BOL']), 15);
			$the_BC = str_pad(trim($pallet_row['THE_PALLET']), 33);
			$the_mark = str_pad(trim($pallet_row['CARGO_DESCRIPTION']), 60);
			$the_qty = str_pad(trim($pallet_row['THE_CHANGE']." ".$unit), 15);
			if($pallet_row['THE_DAMAGE'] == 0){
				$the_reband = str_pad(trim("N"), 7);
			} else {
				$the_reband = str_pad(trim("Y"), 7);
				$total_reband++;
			}
			$the_checker = str_pad(trim($emp_name), 8);
			$total_bc += $pallet_row['THE_CHANGE'];
			fwrite($handle, $the_bol.$the_BC.$the_mark.$the_qty.$the_reband.$the_checker."\n");
		} while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fwrite($handle, "================================================================================================================================================\n");
		fwrite($handle, str_pad(trim(""), 80).str_pad(trim("Totals:"), 28).str_pad(trim($total_bc), 10)."Reband: ".$total_reband."\n\n\n\n");
		fwrite($handle, "Driver has verified cargo loaded with no visible signs of damage/leaks\n\n");
		fwrite($handle, "Driver Signature:  _______________________________________________\n\n");
		fwrite($handle, "TRL#:  ___________________________________________________________\n\n");

		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
		system($tally_print_command);
		system($tally_print_command);
		system($tally_print_command);
		exit;

	}