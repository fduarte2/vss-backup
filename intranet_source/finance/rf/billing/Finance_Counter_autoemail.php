<?
/*
*  Adam Walter, November 2006.
*  This program is found in the same directory as other fruit counters that finance has asked for.
*  The other programs show data in a webpage; this one emails when thresholds are passed.
********************************************************************************************/

	$conn = ora_logon("SAG_Owner@BNI", "SAG");
	if($conn < 1){
		exit;
	}
	$main_data_cursor = ora_open($conn);
	$vessel_name_cursor = ora_open($conn);
	$is_emailed_cursor = ora_open($conn);

	if(date('m') == 12){
		$most_recent_dec_first_ora = date('d-M-Y', mktime(0, 0, 0, 12, 1, date('Y')));
	} else {
		$most_recent_dec_first_ora = date('d-M-Y', mktime(0, 0, 0, 12, 1, date('Y') - 1));
	}
	echo $most_recent_dec_first_ora."\n";

	$mailTO = "philhower@port.state.de.us,lmizikar@port.state.de.us,mmatthews@port.state.de.us";
//	$mailTO = "awalter@port.state.de.us";
	$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
	$mailheaders = "CC: " . "awalter@port.state.de.us,lstewart@port.state.de.us\r\n";

	$mailbody = "";
	$mailsubject = "Fruit Billing Rates Quantity Update";



// part one, for backhaul
	$sql = "SELECT CM.LR_NUM VESSEL, SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5103') AND V.VESSEL_OPERATOR_ID <> '6564' AND V.DATE_FINISHED > '".$most_recent_dec_first_ora."' GROUP BY CM.LR_NUM ORDER BY CM.LR_NUM";
	$statement = ora_parse($main_data_cursor, $sql);
	ora_exec($main_data_cursor);
	$pallet_count = 0;
	$yet_to_be_emailed = TRUE;


	while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($pallet_count < 100000 && $pallet_count + $main_data_row['PALLETS'] > 100000){
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'BACKHAUL' AND QUANTITY = 100000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
				$pallet_overage = $pallet_count - 100000;
				$overage_ratio = $pallet_overage / $main_data_row['PALLETS'];
				$overage_lbs = $overage_ratio * $main_data_row['WEIGHT'];
				$mailbody .= "Notification:  Backhaul billing threshold reached (100000 pallets).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nPallets over 100000: ".$pallet_overage;
				$mailbody .= "\r\nWeight of cargo over 100000 pallets: ".round($overage_lbs, 2)." LBS (".round(($overage_lbs / 2000), 2)." Tons)";
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'BACKHAUL', 100000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
			}
		} else {
			$pallet_count = $pallet_count + $main_data_row['PALLETS'];
		}
	}



// part 2, for Truck Loading
	$sql = "SELECT CM.LR_NUM VESSEL, SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5901', '5900') AND V.DATE_FINISHED > '".$most_recent_dec_first_ora."' GROUP BY CM.LR_NUM ORDER BY CM.LR_NUM";
	$statement = ora_parse($main_data_cursor, $sql);
	ora_exec($main_data_cursor);
	$pallet_count = 0;


	while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($pallet_count < 30000 && $pallet_count + $main_data_row['PALLETS'] > 30000){
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'TRUCKLOADING' AND QUANTITY = 30000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
				$pallet_overage = $pallet_count - 30000;
				$overage_ratio = $pallet_overage / $main_data_row['PALLETS'];
				$overage_lbs = $overage_ratio * $main_data_row['WEIGHT'];
				$mailbody .= "Notification:  Truckloading billing threshold reached (30000 pallets).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nPallets over 30000: ".$pallet_overage;
				$mailbody .= "\r\nWeight of cargo over 30000 pallets: ".round($overage_lbs, 2)." LBS (".round(($overage_lbs / 2000), 2)." Tons)";
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'TRUCKLOADING', 30000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
			}
		} elseif($pallet_count < 100000 && $pallet_count + $main_data_row['PALLETS'] > 100000) {
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'TRUCKLOADING' AND QUANTITY = 100000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
				$pallet_overage = $pallet_count - 100000;
				$overage_ratio = $pallet_overage / $main_data_row['PALLETS'];
				$overage_lbs = $overage_ratio * $main_data_row['WEIGHT'];
				$mailbody .= "Notification:  Truckloading billing threshold reached (100000 pallets).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nPallets over 100000: ".$pallet_overage;
				$mailbody .= "\r\nWeight of cargo over 100000 pallets: ".round($overage_lbs, 2)." LBS (".round(($overage_lbs / 2000), 2)." Tons)";
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'TRUCKLOADING', 100000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
			}
		} else {
			$pallet_count = $pallet_count + $main_data_row['PALLETS'];
		}
	}



// part 3, for Wharfage
	$sql = "SELECT CM.LR_NUM VESSEL, SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5103') AND V.VESSEL_OPERATOR_ID <> '6564' AND V.DATE_FINISHED > '".$most_recent_dec_first_ora."' GROUP BY CM.LR_NUM ORDER BY CM.LR_NUM";
	$statement = ora_parse($main_data_cursor, $sql);
	ora_exec($main_data_cursor);
	$pallet_count = 0;


	while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($pallet_count < 30000 && $pallet_count + $main_data_row['PALLETS'] > 30000){
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'WHARFAGE' AND QUANTITY = 30000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
				$pallet_overage = $pallet_count - 30000;
				$overage_ratio = $pallet_overage / $main_data_row['PALLETS'];
				$overage_lbs = $overage_ratio * $main_data_row['WEIGHT'];
				$mailbody .= "Notification:  Wharfage billing threshold reached (30000 pallets).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nPallets over 30000: ".$pallet_overage;
				$mailbody .= "\r\nWeight of cargo over 30000 pallets: ".round($overage_lbs, 2)." LBS (".round(($overage_lbs / 2000), 2)." Tons)";
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'WHARFAGE', 30000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$pallet_count = $pallet_count + $main_data_row['PALLETS'];
			}
		} else {
			$pallet_count = $pallet_count + $main_data_row['PALLETS'];
		}
	}



// part 4, Terminal Service
	$sql = "SELECT CM.LR_NUM VESSEL, SUM(QTY2_EXPECTED) CARTONS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5900', '5901') AND V.DATE_FINISHED > '".$most_recent_dec_first_ora."' GROUP BY CM.LR_NUM ORDER BY CM.LR_NUM";
	$statement = ora_parse($main_data_cursor, $sql);
	ora_exec($main_data_cursor);
	$carton_count = 0;


	while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($carton_count < 3000000 && $carton_count + $main_data_row['CARTONS'] > 3000000){
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'TERMSERV' AND QUANTITY = 3000000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$carton_count = $carton_count + $main_data_row['CARTONS'];
				$carton_overage = $carton_count - 3000000;
				$mailbody .= "Notification:  Terminal Service billing threshold reached (3000000 cartons).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nCartons over 3000000: ".$carton_overage;
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'TERMSERV', 3000000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$carton_count = $carton_count + $main_data_row['CARTONS'];
			}
		} elseif($carton_count < 4000000 && $carton_count + $main_data_row['CARTONS'] > 4000000) {
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'TERMSERV' AND QUANTITY = 4000000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$carton_count = $carton_count + $main_data_row['CARTONS'];
				$carton_overage = $carton_count - 4000000;
				$mailbody .= "Notification:  Terminal Service billing threshold reached (4000000 cartons).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nCartons over 4000000: ".$carton_overage;
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'TERMSERV', 4000000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$carton_count = $carton_count + $main_data_row['CARTONS'];
			}
		} elseif($carton_count < 5000000 && $carton_count + $main_data_row['CARTONS'] > 5000000) {
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'TERMSERV' AND QUANTITY = 5000000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$carton_count = $carton_count + $main_data_row['CARTONS'];
				$carton_overage = $carton_count - 5000000;
				$mailbody .= "Notification:  Terminal Service billing threshold reached (5000000 cartons).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nCartons over 5000000: ".$carton_overage;
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'TERMSERV', 5000000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$carton_count = $carton_count + $main_data_row['CARTONS'];
			}
		} elseif($carton_count < 9600000 && $carton_count + $main_data_row['CARTONS'] > 9600000) {
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);


			$sql = "SELECT COUNT(*) THE_COUNT FROM FINANCE_EMAIL_DATES WHERE EMAIL_DATE = '".$most_recent_dec_first_ora."' AND EMAIL_TYPE = 'TERMSERV' AND QUANTITY = 9600000";
			$statement = ora_parse($is_emailed_cursor, $sql);
			ora_exec($is_emailed_cursor);
			ora_fetch_into($is_emailed_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] == 0){
				$carton_count = $carton_count + $main_data_row['CARTONS'];
				$carton_overage = $carton_count - 9600000;
				$mailbody .= "Notification:  Terminal Service billing threshold reached (9600000 cartons).";
				$mailbody .= "\r\nVessel ".$vessel_name_row['VESSEL_NAME'];
				$mailbody .= "\r\nCartons over 9600000: ".$carton_overage;
				$mailbody .= "\r\n\r\n";

				$sql = "INSERT INTO FINANCE_EMAIL_DATES (EMAIL_DATE, EMAIL_TYPE, QUANTITY) VALUES ('".$most_recent_dec_first_ora."', 'TERMSERV', 9600000)";
				$statement = ora_parse($is_emailed_cursor, $sql);
				ora_exec($is_emailed_cursor);
			} else {
				$carton_count = $carton_count + $main_data_row['CARTONS'];
			}
		} else {
			$carton_count = $carton_count + $main_data_row['CARTONS'];
		}
	}



// Now we determine if emails get sent.

	if($mailbody != ""){
		mail($mailTO, $mailsubject, $mailbody, $mailheaders);
	} // simple, no?
