<?
include("get_employee_name.php");

$short_term_data_cursor = ora_open($rf_conn);
$pallet_cursor = ora_open($rf_conn);
$summary_cursor = ora_open($rf_conn);

	$order = "";
	while($order == "" || $order == "Invalid"){
		print chr(27)."[H".chr(27)."[2J";
		echo "\nBooking OB TALLY\nEnter X to exit.\n\n";
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND SERVICE_CODE = '6' AND CUSTOMER_ID IN ('314', '338', '517')";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(BkO1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nOrder\n(BkO1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order = "Invalid";
		} elseif($short_term_data_row['THE_COUNT'] <= 0){
			$order = "Invalid";
		}			
	}

	if ($kiosk_name ==  "") {
		$kiosk_name = "Testing";
	}
	echo "\nProcessing Request\n".$order."...\n";

	$sql = "SELECT CONTAINER_ID, SEAL_NUM FROM BOOKING_ORDERS WHERE ORDER_NUM = '".$order."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	if(!ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$cont = "";
		$seal = "";
	} else {
		$cont = $row['CONTAINER_ID'];
		$seal = $row['SEAL_NUM'];
	}

	$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
			NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
			FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND SERVICE_CODE = '6' AND CUSTOMER_ID IN ('314', '338', '517')";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	if(!ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$start = "";
		$end = "";
	} else {
		$start = $row['START_TIME'];
		$end = $row['END_TIME'];
	}

    $sql = "SELECT CT.PALLET_ID, QTY_CHANGE, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, 
				ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, 
				NVL(BOOKING_NUM, '--NONE--') THE_BOOK, CA.ARRIVAL_NUM, CA.CUSTOMER_ID, CA.ACTIVITY_NUM,
                BAD.ORDER_NUM, LENGTH || ' ' || LENGTH_MEAS THE_LINEAR, BAD.BOL THE_MANIFEST, ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB,
				ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG, DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN
			FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC, 
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, 
				UNIT_CONVERSION_FROM_BNI UC4 
			WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
                AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
                AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  
				AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
                AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
                AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL
                AND CA.ORDER_NUM = '".$order."'
			ORDER BY NVL(BOOKING_NUM, '--NONE--')";
	ora_parse($pallet_cursor, $sql);
	ora_exec($pallet_cursor);
	if(!ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No order matching this criteria can be found.\nCancelling Print job.";
		fscanf(STDIN, "%s\n", $junk);
		exit;
	} else {
		$timedatestamp = date("Y_m_d_H_i_s");
		$filename = "BookOB_".$order."_".$timedatestamp;
		$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

		/* Print Tally Banner Info */
		fwrite($handle, "Printed From:".$kiosk_name."     On:  ".date('m/d/Y h:i:s a')."\n\n");
		fwrite($handle, "PORT OF WILMINGTON BOOKING OUTBOUND TALLY\n\n");
		fwrite($handle, "Order: ".$order."\n");
		$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$pallet_row['CUSTOMER_ID']."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		fwrite($handle, "Customer: ".$row['CUSTOMER_NAME']."\n");
		fwrite($handle, "Container: ".$cont."\n");
		fwrite($handle, "Seal: ".$seal."\n");
		fwrite($handle, "First Scan: ".$start."\n");
		fwrite($handle, "Last Scan: ".$end."\n");

		$total_rolls = 0;
		$total_lb = 0;
		$total_kg = 0;

		$the_BC = str_pad("BARCODE", 33);
		$the_qty = str_pad("QTY", 4);
		$the_size= str_pad("SIZE (cm)", 10);
		$the_dia = str_pad("DIA (cm)", 9);
		$the_prod = str_pad("PRODUCT", 12);
		$the_bk = str_pad("BOOKING#", 13);
		$the_order = str_pad("ORDER", 7);
		$the_linear = str_pad("LINEAR MEAS", 12);
		$the_recman = str_pad("REC. MANIFEST", 14);
		$the_lb = str_pad("LB", 8);
		$the_kg = str_pad("KG", 8);
		$the_dmg = str_pad("DMG", 4);
		$the_checker = str_pad("CHECKER", 8);
		fwrite($handle, $the_BC.$the_qty.$the_size.$the_dia.$the_prod.$the_bk.$the_order.$the_linear.$the_received.$the_recman.$the_lb.$the_kg.$the_dmg.$the_checker."\n");
		fwrite($handle, "================================================================================================================================================\n");
		do {
			$emp_name = get_employee_for_print($pallet_row['PALLET_ID'], $pallet_row['ARRIVAL_NUM'], $pallet_row['CUSTOMER_ID'], $pallet_row['ACTIVITY_NUM'], $rf_conn);
			$total_rolls++;
			$total_lb += $pallet_row['WEIGHT_LB'];
			$total_kg += $pallet_row['WEIGHT_KG'];

			$the_BC = str_pad(trim($pallet_row['PALLET_ID']), 33);
			$the_qty = str_pad(trim($pallet_row['QTY_CHANGE']), 4);
			$the_size= str_pad(trim($pallet_row['THE_WIDTH']), 10);
			$the_dia = str_pad(trim($pallet_row['THE_DIA']), 9);
			$the_prod = str_pad(trim($pallet_row['THE_CODE']), 12);
			$the_bk = str_pad(trim($pallet_row['THE_BOOK']), 13);
			$the_order = str_pad(trim($pallet_row['ORDER_NUM']), 7);
			$the_linear = str_pad(trim($pallet_row['THE_LINEAR']), 12);
			$the_recman = str_pad(trim($pallet_row['THE_MANIFEST']), 14);
			$the_lb = str_pad(trim($pallet_row['WEIGHT_LB']), 8);
			$the_kg = str_pad(trim($pallet_row['WEIGHT_KG']), 8);
			$the_dmg = str_pad(trim($pallet_row['DAMAGE_YN']), 4);
			$the_checker = str_pad(trim($emp_name), 8);
			fwrite($handle, $the_BC.$the_qty.$the_size.$the_dia.$the_prod.$the_bk.$the_order.$the_linear.$the_recman.$the_lb.$the_kg.$the_dmg.$the_checker."\n");
		} while(ora_fetch_into($pallet_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		fwrite($handle, "\n");
		$disp_total_words = str_pad(trim("TOTALS:"), 33);
		$disp_total_rolls = str_pad(trim($total_rolls), 81);
		$disp_total_lb= str_pad(trim($total_lb), 8);
		$disp_total_kg = str_pad(trim($total_kg), 8);
		fwrite($handle, $disp_total_words.$disp_total_rolls.$disp_total_lb.$disp_total_kg."\n");
		fwrite($handle, "\n\n");

		fwrite($handle, str_pad(trim("Outgoing Summary:"), 33));
		fwrite($handle, "\n");
		fwrite($handle, "================================================================================================================================================\n");

		$sql = "SELECT SUM(QTY_RECEIVED) THE_REC, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, 
				ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, 
				NVL(BOOKING_NUM, '--NONE--') THE_BOOK, BAD.ORDER_NUM, BAD.BOL THE_MANIFEST, 
				SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB, SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG
			FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4
			WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  
				AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
				AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL
				AND CA.ORDER_NUM = '".$order."'
			GROUP BY ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR), 
				NVL(SSCC_GRADE_CODE, '--NONE--'), NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM, BAD.BOL
			ORDER BY NVL(BOOKING_NUM, '--NONE--')";
		ora_parse($summary_cursor, $sql);
		ora_exec($summary_cursor);
		while(ora_fetch_into($summary_cursor, $summary_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$the_BC = str_pad(trim(""), 33);
			$the_qty = str_pad(trim($summary_row['THE_REC']), 4);
			$the_size= str_pad(trim($summary_row['THE_WIDTH']), 10);
			$the_dia = str_pad(trim($summary_row['THE_DIA']), 9);
			$the_prod = str_pad(trim($summary_row['THE_CODE']), 12);
			$the_bk = str_pad(trim($summary_row['THE_BOOK']), 13);
			$the_order = str_pad(trim($summary_row['ORDER_NUM']), 7);
			$the_linear = str_pad(trim(""), 12);
			$the_recman = str_pad(trim($summary_row['THE_MANIFEST']), 14);
			$the_lb = str_pad(trim($summary_row['WEIGHT_LB']), 8);
			$the_kg = str_pad(trim($summary_row['WEIGHT_KG']), 8);
			fwrite($handle, $the_BC.$the_qty.$the_size.$the_dia.$the_prod.$the_bk.$the_order.$the_linear.$the_recman.$the_lb.$the_kg."\n");
		}

		fclose($handle);
		echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";
		$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";
		system($tally_print_command);
		exit;
	}