<?
/*
*	Chilean Fruit Scanner, originally written July/Aug2009.
*
*	As part of the massive migration of scanner programs to PHP
*
*	MAJOR EDIT, SEPT 2010:
*	--- new "plan" has it that individual customers can only use special
*	--- specific scanner functions. This scanner is getting the "maiden voyage"
*	--- of new "validated procedure" calls.  The function it calls to is
*	--- in the Staylinkheader.php file.
********************************************************************************/

/*******************************************************************************
* MENU FUNCTIONS START HERE ****************************************************
********************************************************************************/

function Alter_Original_Count($CID){
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.");
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
		database_check($ora_success, "Unable to get\nPallet Info\n(AOC1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(AOC1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet does not exist
			fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
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
			database_check($ora_success, "Unable to get\nPallet Info\n(AOC2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(AOC2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Cust = $short_term_data_row['RECEIVER_ID'];
			$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			$Commodity = $short_term_data_row['COMMODITY_CODE'];
			$qty_rec = $short_term_data_row['QTY_RECEIVED'];
		}

		// find out if this is a split pallet... (which will get blocked)
		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(AOC9a-a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(AOC9a-b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$walmart_split_count = $short_term_data_row['THE_COUNT'];


		// get count data...
		$sql = "SELECT QTY_RECEIVED, VESSEL_NAME, QTY_IN_HOUSE, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."' AND CT.ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(AOC3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(AOC3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$qty_rec = $short_term_data_row['QTY_RECEIVED'];
		$vesname = $short_term_data_row['VESSEL_NAME'];
		$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
		$item_type = $short_term_data_row['THE_ITEM'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."' AND SERVICE_CODE NOT IN ('1', '8', '6', '12')";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(AOC6a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(AOC6b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$any_unallowed_activites = $short_term_data_row['THE_COUNT'];

		if(!validate_customer_to_scannertype($Cust, $scanner_type, "Alter_Original_Count")){
			fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($any_unallowed_activites > 0){
			fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET HAD\nNON-SHIP ACTIVITY\nCANNOT EDIT**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($walmart_split_count > 0){
			fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT-PALLET\nPLASE CONTACT\nOFFICE FOR\nORIGINAL QTY\nCHANGES.**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}


		while($continue){
			fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.");
			echo "BC: ".$Barcode."\n\n";
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "WMitem#: ".$item_type."\n";
			echo "CUST: ".$Cust."\n";
			echo "Rcv: ".$qty_rec."  Inhs: ".$qty_in_house."\n";
			echo "New RCVD QTY: \n";
			$new_qty = "";
			fscanf(STDIN, "%s\n", $new_qty);
			$new_qty = strtoupper($new_qty);

			if($new_qty == "X" || $new_qty == ""){
				// cancel
				$continue = false;
			} elseif(!is_numeric($new_qty)){
				fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY RCVD\nMUST BE\nA NUMBER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif($new_qty < 0) {
				fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY RCVD\nCANNOT BE LESS\nTHAN ZERO**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif($new_qty != round($new_qty)){
				fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY RCVD\nMUST BE A\nWHOLE NUMBER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} else {
				// auto-receive if necessary (not that it ever should be, but...)
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to Auto\nReceive Pallet\n(AOC5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to Auto\nReceive Pallet\n(AOC5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_DATE'] == "NONE"){
					Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, "ship");
				}

				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(AOC4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(AOC4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;

				// update CARGO_TRACKING... but only the QTY_IN_HOUSE if it is still "unaltered".
				$sql = "UPDATE CARGO_TRACKING SET QTY_RECEIVED = '".$new_qty."', QTY_IN_HOUSE = DECODE(QTY_IN_HOUSE, 0, 0, QTY_RECEIVED, ".$new_qty.", QTY_IN_HOUSE) WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(AOC7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(AOC7b)");

				// update CARGO_ACTIVITY record(s)
				$sql = "UPDATE CARGO_ACTIVITY SET QTY_CHANGE = '".$new_qty."' WHERE QTY_CHANGE = '".$qty_rec."' AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(AOC7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(AOC7b)");
				$sql = "UPDATE CARGO_ACTIVITY SET QTY_LEFT = '".$new_qty."' WHERE QTY_LEFT = '".$qty_rec."' AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(AOC8a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(AOC8b)");

				$continue = false;
				ora_commit($rf_conn);

				fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.\n\nBC: ".$Barcode."\nQTY RCVD\nCHANGED TO\n".$new_qty);
				fscanf(STDIN, "%s\n", $junk);		

			}
		}

		fresh_screen("WALMART FRUIT\nEdit Ctn Count\nEnter X to exit.");
		$Barcode = "";
		echo "Barcode:";
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

	fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.");
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
			fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(ReL2a-a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(ReL2a-b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$walmart_split_count = $short_term_data_row['THE_COUNT'];


		if(!validate_customer_to_scannertype($Cust, $scanner_type, "Recoup")){
			fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		} elseif($walmart_split_count > 0) {
			fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT-PALLET\nPLEASE CONTACT\nOFFICE FOR\nRECOUPS.**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}

		// get count data...
		$sql = "SELECT QTY_IN_HOUSE, VESSEL_NAME, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."' AND CT.ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReC3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReC3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
		$vesname = $short_term_data_row['VESSEL_NAME'];
		$item_type = $short_term_data_row['THE_ITEM'];

		while($continue){
			$act_num = get_max_activity_num($Cust, $Barcode, $Arrival);

			// do this if they did not "cancel" or "error" out of above
			fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.");
			echo "BC: ".$Barcode."\n\n";
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "WMitem#: ".$item_type."\n";
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
				fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY IN HOUSE\nMUST BE\nA NUMBER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif($new_qty < 0) {
				fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY IN HOUSE\nCANNOT BE LESS\nTHAN ZERO**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif($new_qty != round($new_qty)){
				fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\n**QTY IN HOUSE\nMUST BE A\nWHOLE NUMBER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} elseif(!is_max_activity_num($act_num, $Cust, $Barcode, $Arrival)){
				fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} else {
				// entry is valid, let's do this!
				$qty_change = $new_qty - $qty_in_house;

				// auto-receive if necessary (not that it ever should be, but...)
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to Auto\nReceive Pallet\n(ReC5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to Auto\nReceive Pallet\n(ReC5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_DATE'] == "NONE"){
					Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, "ship");
				}
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

				if($qty_change > 0){
					$mailSubject = "Pallet recouped with Positive QTY_IN_HOUSE change\r\n";
					$body = "Barcode:  ".$Barcode."\nLR#:  ".$Arrival."\nCust:  ".$Cust."\n\nRecouped from a value of ".$qty_in_house." to ".$new_qty."\r\n";
//					mail("lstewart@port.state.de.us,awalter@port.state.de.us\r\n", $mailSubject, $body, $mailHeaders);
					mail($inv_mail_list, $mailSubject, $body, $mailHeaders);
					$sql = "INSERT INTO SCANNER_EMAIL_LOG (FROM_SCANNER_TYPE, DATE_SENT, EMAIL_TO_LIST, EMAIL_BODY, EMAIL_SUBJECT) VALUES ('WalMART', SYSDATE, '".$inv_mail_list."', '".$body."', '".$mailSubject."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Email\n(ReC8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to\nInsert Email\n(ReC8b)");
					ora_commit($rf_conn);

				}

				// make sure we get out of the while loop, since change was made...
				$continue = false;
				ora_commit($rf_conn);

				fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.\n\nBC: ".$Barcode."\nQTY IN HOUSE\nCHANGED TO\n".$new_qty);
				fscanf(STDIN, "%s\n", $junk);		
			}
		}
		fresh_screen("WALMART FRUIT\nRecoup\nEnter X to exit.");
		$Barcode = "";
		echo "Barcode:";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}
}
/*
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
		fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.");
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
		} elseif($Arrival == "2010399") {
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

	fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.");
	echo "Count: ".$count."\n";
	echo "LR Num: ".$Arrival."\n";
	$Barcode = "";
	while($Barcode == ""){
		echo "Barcode:";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	}

	while(strtoupper($Barcode) != "X"){
		$new_pallet = false;
		$increm_count = 0;
		$Cust = "";
		$Comm = "";
		$qty_rec = "";
		$proceed = false;

		$proceed = Validate_duplicate_get_comm_and_cust($Barcode, $Arrival, $Cust, $Comm, $qty_rec, $new_pallet);

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."' AND SERVICE_CODE NOT IN ('1', '8')";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(US15a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(US15b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($short_term_data_row['THE_COUNT'] > 0){
			// Cannot "receive" a pallet that already has activity against it
			fresh_screen("WALMART FRUIT\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nAlready has\nActivity.\nCannot Receive.", "bad");
		} elseif(!$proceed) {
			// validate pallet function was cancelled
			fresh_screen("WALMART FRUIT\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nCancelled.", "bad");
		}else{
			if($new_pallet){
				// if this is a created pallet, get necessary info (or cancel out)
				while($proceed && ($Cust == "" || !is_numeric($Cust))){
					fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					if($Cust != ""){
						echo "Invalid Cust(".$Cust.")\n";
					}
					echo "CustomerID#: \n";
					fscanf(STDIN, "%s\n", $Cust);
					$Cust = strtoupper($Cust);
					$Cust = remove_badchars($Cust);

					if($Cust == "X"){
						$proceed = false;
					} elseif(!validate_customer_to_scannertype($Cust, $scanner_type, "Unload_Ship")){
						fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nCust: ".$Cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$Cust = "";
					} else {
						// verify legit customer
						$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nCust Info\n(US12a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nCust Info\n(US12b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("WALMART FRUIT\nUnload Ship\n\nInvalid Cust#\n(".$Cust.")");
							fscanf(STDIN, "%s\n", $junk);
							$Cust = "";
						}
					}

				}
				while($proceed && ($Comm == "" || !is_numeric($Comm))){
					fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nUNKNOWN PALLET\nCREATE PALLET");
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
						$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(US13a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(US13b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("WALMART FRUIT\nUnload Ship\n\nInvalid Comm#\n(".$Cust.")");
							fscanf(STDIN, "%s\n", $junk);
							$Comm = "";
						}
					}
				}
				while($proceed && ($qty_rec == "" || !is_numeric($qty_rec))){
					fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nUNKNOWN PALLET\nCREATE PALLET");
					echo "QTY Rcvd#: \n";
					fscanf(STDIN, "%s\n", $qty_rec);
					$qty_rec = strtoupper($qty_rec);
					if($qty_rec == "X"){
						$proceed = false;
					}
				}
			}

			if(!$proceed){
				// new pallet that was cancelled
				fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nCancelled.");
			} elseif(!validate_customer_to_scannertype($Cust, $scanner_type, "Unload_Ship")){
				fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$proceed = false;
			} else {
				// get other pallet info
				$asterisk_array = array();
				$qty_dmg = "";
				$loc = "";
				$variety = "";
				$size = "";
				$chep = "";
				$currcomm = $Comm;
				$currcust = $Cust;

				$sql = "SELECT DATE_RECEIVED, QTY_DAMAGED, WAREHOUSE_LOCATION, VARIETY, CARGO_SIZE, CHEP FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$Comm."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(US4b)");
				if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
					$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
					$variety = $short_term_data_row['VARIETY'];
					$size = $short_term_data_row['CARGO_SIZE'];
					$chep = $short_term_data_row['CHEP'];
					if($short_term_data_row['DATE_RECEIVED'] == ""){
						$increm_count = 1;
					}
				}

				do{
					$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 9) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(US9a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(US9b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$commname = $short_term_data_row['THE_COMM'];

					$sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 9) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(US10a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(US10b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$custname = $short_term_data_row['THE_CUST'];

					system("clear");
					echo $Barcode."\n";
					echo $asterisk_array[0]."1)Cust: ".$custname."\n";
					echo $asterisk_array[1]."2)Comm: ".$commname."\n";
					echo $asterisk_array[2]."3)QTY Rcvd: ".$qty_rec."\n";
					echo $asterisk_array[3]."4)QTY Dmg: ".$qty_dmg."\n";
					echo $asterisk_array[4]."5)Loc: ".$loc."\n";
					echo $asterisk_array[5]."6)Var: ".$variety."\n";
					echo $asterisk_array[6]."7)Size: ".$size."\n";
					echo $asterisk_array[7]."8)Chep: ".$chep."\n";
					echo "\nTo Change Select #\n";
					echo "\"Enter\" to Accept\n";
					echo "\"X\" To Escape\n";

					$Choice = "";
					fscanf(STDIN, "%s\n", $Choice);
					$Choice = strtoupper($Choice);

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
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "loc", $loc)){
								$asterisk_array[4] = "*";
							}
							break;
						case 6:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "var", $variety)){
								$asterisk_array[5] = "*";
							}
							break;
						case 7:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "size", $size)){
								$asterisk_array[6] = "*";
							}
							break;
						case 8:
							if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "chep", $chep)){
								$asterisk_array[7] = "*";
							}
							break;
						case "X":
							fresh_screen("WALMART FRUIT\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nCancelled.");
							break;
						case "":
							if(!$new_pallet){
								$sql = "UPDATE CARGO_TRACKING SET 
										DATE_RECEIVED = SYSDATE,
										RECEIVER_ID = '".$Cust."',
										QTY_RECEIVED = '".$qty_rec."',
										QTY_IN_HOUSE = '".$qty_rec."',
										COMMODITY_CODE = '".$Comm."',
										QTY_DAMAGED = '".$qty_dmg."',
										WAREHOUSE_LOCATION = '".$loc."',
										VARIETY = '".$variety."',
										CARGO_SIZE = '".$size."',
										CHEP = '".$chep."'
										WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$currcomm."' AND RECEIVER_ID = '".$currcust."'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US5a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US5b)");

								$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$currcust."' AND SERVICE_CODE = '1'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US11a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(US11b)");

							} else {
								$sql = "INSERT INTO CARGO_TRACKING 
										(COMMODITY_CODE,
										DATE_RECEIVED,
										QTY_RECEIVED,
										RECEIVER_ID,
										QTY_DAMAGED,
										WAREHOUSE_LOCATION,
										QTY_IN_HOUSE,
										PALLET_ID,
										ARRIVAL_NUM,
										RECEIVING_TYPE,
										CARGO_SIZE,
										VARIETY,
										CHEP)
										VALUES
										('".$Comm."',
										SYSDATE,
										'".$qty_rec."',
										'".$Cust."',
										'".$qty_dmg."',
										'".$loc."',
										'".$qty_rec."',
										'".$Barcode."',
										'".$Arrival."',
										'S',
										'".$size."',
										'".$variety."',
										'".$chep."')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nInsert Pallet\n(US7a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nInsert Pallet\n(US7b)");

								$increm_count = 1;


							}

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
							database_check($ora_success, "Unable to\nInsert Pallet\n(US8a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nInsert Pallet\n(US8b)");

							ora_commit($rf_conn);
							$count += $increm_count;
							fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to Exit\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\n\nRECEIVED!");

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
*/
function Unload_Truck($CID){
	// much as I'd like to just use the "create pallet from scratch" routine, apparently, 
	// since that has more "clicks" to receive than this does, it is unacceptable... :( 
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


		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Unload_Truck")){
				fresh_screen("WALMART FRUIT\nUnload Truck\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(UT1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(UT1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				} 
			}
		}

		// get Order
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			if(!S5_validity_check($order_num)){
				fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.\n\n**ORDER# CAN ONLY\nBE ALPHANUMERIC**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}
		}

		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '8'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(UT8a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(UT8b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallets = $short_term_data_row['THE_COUNT'];
		$total_cases = (0 + $short_term_data_row['THE_SUM']);


		fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "ORD#: ".$order_num."\n\n";
		echo $total_cases." in ".$total_pallets." Plts\n";
		$Barcode = "";
		echo "Barcode:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		while($Barcode == ""){
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
		
		while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
			$Comm = "";
			$qty_rec = "";
			$qty_dmg = "";
			$loc = "";
			$imported_and_can_receive = false;
			$continue_pallet = true;

			// APR 12
			// ADDING EMERGENCY QUALIFIER arrival_num = 2010399
			// to handle emergency cust 399 shiptruck pallets
			// remove sometime after receiving done
			$sql = "SELECT DATE_RECEIVED, QTY_RECEIVED, QTY_DAMAGED, WAREHOUSE_LOCATION, VARIETY, CARGO_SIZE, CHEP, COMMODITY_CODE, ARRIVAL_NUM, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND RECEIVING_TYPE = 'T' AND (ARRIVAL_NUM = '".$order_num."' OR ARRIVAL_NUM = '9999999')";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(UT2a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(UT2b)");
			if(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// pallet exists, check if it is ok to rec
				$qty_rec = $select_row['QTY_RECEIVED'];
				$qty_dmg = $select_row['QTY_DAMAGED'];
				$loc = $select_row['WAREHOUSE_LOCATION'];
				$variety = $select_row['VARIETY'];
				$size = $select_row['CARGO_SIZE'];
				$chep = $select_row['CHEP'];
				$Comm = $select_row['COMMODITY_CODE'];
				$WM_item = $select_row['THE_ITEM'];
				$raw_ARV_num = $select_row['ARRIVAL_NUM'];
				if($select_row['DATE_RECEIVED'] == ""){
//					$increm_count = 1;
					$imported_and_can_receive = true;
				}

/*				if($select_row['DATE_RECEIVED'] == ""){
					$imported_and_can_receive = true;
				}
*/
				$sql = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."' AND SERVICE_CODE != '8'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(UT3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(UT3b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$imported_and_can_receive = true;
				}

				$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."'";
		//		echo $sql."\n";
		//		fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(UT15a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(UT15a-b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$walmart_split_count = $short_term_data_row['THE_COUNT'];

				// ok, the "final countdown"
				if(!$imported_and_can_receive){
					fresh_screen("WALMART FRUIT\nUnload Truck\nEnter X to exit.\n\nCust #: ".$cust."\nORD#: ".$order_num."\nBC: ".$Barcode."\n**ALREADY HAS\nACTIVITY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					if($walmart_split_count > 0){
						fresh_screen("WALMART FRUIT\nUnload Ship\nEnter X to exit.\n\nBC: ".$Barcode."\n**THIS IS A SPLIT-\nWALMART PALLET.\nIF YOU NEED TO \nCHANGE ANYTHING\nBESIDES WAREHOUSE\nLOCATION, CONTACT\nSUPERVISOR.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
					}

					do{

						$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 9) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(US9a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nComm Info\n(US9b)");
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$commname = $short_term_data_row['THE_COMM'];

						system("clear");
						echo $Barcode."\n";
//						echo $asterisk_array[0]."1)Cust: ".$custname."\n";
						echo $asterisk_array[0]."1)Comm: ".$commname."\n";
						echo $asterisk_array[1]."2)QTY Rcvd: ".$qty_rec."\n";
						echo $asterisk_array[2]."3)QTY Dmg: ".$qty_dmg."\n";
						echo $asterisk_array[3]."4)Loc: ".$loc."\n";
						echo $asterisk_array[4]."5)Var: ".$variety."\n";
						echo $asterisk_array[5]."6)Size: ".$size."\n";
						echo $asterisk_array[6]."7)Chep: ".$chep."\n";
						echo "WMitem#: ".$WM_item."\n";
						echo "\nTo Change Select #\n";
						echo "\"Enter\" to Accept\n";
						echo "\"X\" To Escape\n";

						$Choice = "";
						fscanf(STDIN, "%s\n", $Choice);
						$Choice = strtoupper($Choice);

						// if this is a WALMART SPLIT PALLET, and they choose anything *other* than 5, enter or X,
						// we DONT want to let them do it.
						if($walmart_split_count > 0 && ($Choice != "" && $Choice != "X" && $Choice != "4")){
							$Choice = "bad";
						}

						switch($Choice){
//							case 1:
//								if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "cust", $Cust)){
//									$asterisk_array[0] = "*";
//								}
//								break;
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
								if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "var", $variety)){
									$asterisk_array[4] = "*";
								}
								break;
							case 6:
								if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "size", $size)){
									$asterisk_array[5] = "*";
								}
								break;
							case 7:
								if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "chep", $chep)){
									$asterisk_array[6] = "*";
								}
								break;
							case "X":
								fresh_screen("WALMART FRUIT\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nCancelled.");
								break;
							case "":
								// EMERGENCY SQL INSERT
								// cust 399 is about to "shiptruck" us 2500 pallet
								if($raw_ARV_num == "9999999"){
									$sql = "UPDATE CARGO_TRACKING SET ARRIVAL_NUM = '".$order_num."'
											WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$raw_ARV_num."'";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate 2010399\n(UT4a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate 2010399\n(UT4b)");
									ora_commit($rf_conn);
								}

								$sql = "UPDATE CARGO_TRACKING SET 
										DATE_RECEIVED = SYSDATE,
										QTY_RECEIVED = '".$qty_rec."',
										QTY_IN_HOUSE = '".$qty_rec."',
										COMMODITY_CODE = '".$Comm."',
										QTY_DAMAGED = '".$qty_dmg."',
										WAREHOUSE_LOCATION = '".$loc."',
										VARIETY = '".$variety."',
										CARGO_SIZE = '".$size."',
										CHEP = '".$chep."'
										WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(UT4a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to\nUpdate Pallet\n(UT4b)");

								$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."'";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(UT5a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(UT5b)");

								if($walmart_split_count > 0){
									$sql = "UPDATE WDI_SPLIT_PALLETS SET 
											DATE_RECEIVED = NVL(DATE_RECEIVED, SYSDATE),
											WAREHOUSE_LOCATION = '".$loc."'
											WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."'";
									$ora_success = ora_parse($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(US11a-a)");
									$ora_success = ora_exec($modify_cursor, $sql);
									database_check($ora_success, "Unable to\nUpdate Pallet\n(US11a-b)");
								}

								$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES 
								('1',
								'8',
								'".$qty_rec."',
								'".$emp_no."',
								'".$order_num."',
								'".$cust."',
								SYSDATE,
								'".$Barcode."',
								'".$order_num."',
								'".$qty_rec."')";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(UT6a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Cannot Update\nPallet Info\n(UT6b)");

								ora_commit($rf_conn);
								break;
							}
						} while($Choice != "X" && $Choice != "");

/*
					// pallet good to receive.  note we delete cargo_activity; in case it was already received, need to remove said record.
					$sql = "UPDATE CARGO_TRACKING SET DATE_RECEIVED = SYSDATE WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT4a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT4b)");

					$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT5a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT5b)");

					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES 
					('1',
					'8',
					'".$qty_rec."',
					'".$emp_no."',
					'".$order_num."',
					'".$cust."',
					SYSDATE,
					'".$Barcode."',
					'".$order_num."',
					'".$qty_rec."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT6b)");

					ora_commit($rf_conn);
*/
				}
			} else {
				// PALLET NOT IN DB.  Get data.
				// get and validate comm # if not passed
				while(($Comm == "" || $Comm == "Invalid") && $continue_pallet){
					fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.\nNEW PALLET");
					if($Comm != ""){
						echo "Invalid Comm#\n";
					}
					echo "BC: ".$Barcode."\n";
					echo "Commodity#:\n";
					fscanf(STDIN, "%s\n", $Comm);
					$Comm = strtoupper($Comm);
					if($Comm == "X"){
						$continue_pallet = false;
					}

					if(!is_numeric($Comm)){
						$Comm = "Invalid";
					} else {
						$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nCommodity\n(UT7a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nCommodity\n(UT7b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$Comm = "Invalid";
						}
					}
				}

				// get and validate received qty
				while(($qty_rec == "" || $qty_rec == "Invalid") && $continue_pallet){
					fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.\nNEW PALLET");
					if($qty_rec != ""){
						echo "Invalid QTY\n";
					}
					echo "BC: ".$Barcode."\n";
					echo "QTY RCVD:\n";
					fscanf(STDIN, "%s\n", $qty_rec);
					$qty_rec = strtoupper($qty_rec);
					if($qty_rec == "X"){
						$continue_pallet = false;
					}

					if(!is_numeric($qty_rec)){
						$qty_rec = "Invalid";
					}
				}

				// get and validate dmgd qty
				$qty_dmg = "Invalid";
				while($qty_dmg == "Invalid" && $continue_pallet){
					fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.\nNEW PALLET");
					echo "BC: ".$Barcode."\n";
					echo "QTY DMG:\n";
					$qty_dmg = "";
					fscanf(STDIN, "%s\n", $qty_dmg);
					$qty_dmg = strtoupper($qty_dmg);
					if($qty_dmg == "X"){
						$continue_pallet = false;
					}

					if(!is_numeric($qty_dmg) && $qty_dmg != ""){
						$qty_dmg = "Invalid";
					}
				}

				// get loc (no validation)
				if($continue_pallet){
					$loc = "";
					fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.\nNEW PALLET");
					echo "BC: ".$Barcode."\n";
					echo "Location:\n";
					fscanf(STDIN, "%[^[]]\n", $loc);
					$loc = trim(strtoupper($loc));
					if($loc == "X"){
						$continue_pallet = false;
					}
				}

				// if all data entered, then let's do it!
				if($continue_pallet){
					$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, DATE_RECEIVED, QTY_RECEIVED, RECEIVER_ID, QTY_DAMAGED, WAREHOUSE_LOCATION, QTY_IN_HOUSE, PALLET_ID, ARRIVAL_NUM, RECEIVING_TYPE) VALUES
					('".$Comm."',
					SYSDATE,
					'".$qty_rec."',
					'".$cust."',
					'".$qty_dmg."',
					'".$loc."',
					'".$qty_rec."',
					'".$Barcode."',
					'".$order_num."',
					'T')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT8b)");

					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES 
					('1',
					'8',
					'".$qty_rec."',
					'".$emp_no."',
					'".$order_num."',
					'".$cust."',
					SYSDATE,
					'".$Barcode."',
					'".$order_num."',
					'".$qty_rec."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Cannot Update\nPallet Info\n(UT6b)");

					ora_commit($rf_conn);
				}
			}


			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '8'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(UT7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(UT7b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_pallets = $short_term_data_row['THE_COUNT'];
			$total_cases = (0 + $short_term_data_row['THE_SUM']);


			fresh_screen("WALMART FRUIT\nTrucked In Cargo\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "ORD#: ".$order_num."\n\n";
			echo $total_cases." in ".$total_pallets." Plts\n";
			$Barcode = "";
			echo "Barcode:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
			while($Barcode == ""){
				fscanf(STDIN, "%s\n", $Barcode);
				$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
			}

			if($Barcode == "X"){
				$continue_function = false;
			}
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
		$wing = "";
		$email_list_of_changed_owner_pallets = "";

		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Load_Truck")){
				fresh_screen("WALMART FRUIT\nLoad Truck\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
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

		// get Order
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}

			$FIFO_exempt = false;
			if(!S5_validity_check($order_num)){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\n**ORDER# CAN ONLY\nBE ALPHANUMERIC**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			} else {
				$sql = "SELECT COUNT(*) THE_COUNT FROM 
								(SELECT ORDER_NUM FROM WM_EXPECTED_REPACK_ORDER
								UNION
								SELECT DISTINCT TO_CHAR(DCPO_NUM) ORDER_NUM
								FROM WDI_LOAD_DCPO WLD, WDI_LOAD_HEADER WLH
									WHERE STATUS = 'ACTIVE'
									AND WLD.LOAD_NUM = WLH.LOAD_NUM
								UNION
								SELECT DISTINCT ORDER_NUM 
								FROM WDI_OFFSITE_LOAD
								)
							WHERE ORDER_NUM = '".$order_num."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1a-f)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1b-f)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\n**ORDER NOT IN SYSTEM\nCONTACT OFFICE**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$order_num = "";
				} else {
					// this load is valid, figure if it is offsite or not
					$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_OFFSITE_LOAD
							WHERE ORDER_NUM = '".$order_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1a-z)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT1b-z)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] >= 1){
						$lucca = true;
					} else {
						$lucca = false;
					}
					
				}
			}
		}

		// with order# in hand

		// get Wing
		while($wing == ""){
			fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.");
			echo "Wing:\n";
			fscanf(STDIN, "%s\n", $wing);
			$wing = strtoupper($wing);
			if($wing == "X"){
				return;
			}
			$wing = remove_badchars($wing);

			if(strlen($wing) != 1){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\n**WING IS A\nSINGLE LETTER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$wing = "";
			}
		}

		$total_cases = 0;
		$total_pallets = 0;

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
/*
SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallets = $short_term_data_row['THE_COUNT'];
		$total_cases = (0 + $short_term_data_row['THE_SUM']); */

		fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo "Wing: ".$wing."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){
			$continue_pallet = true;

			$Arrival = "";
			$pallet_owner = "";
			$Commodity = "";

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			if($continue_pallet){
				// check if any pallets, exactly 1 pallet, or more than 1 pallet still in house with this barcode
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					// no pallets in house
					$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT18a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT18b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] == 0){
						// no pallet in DB
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\nPALLET NOT\nIN DATABASE.\n\nCONTACT INVENTORY", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					} else {
						// pallet already "out" somehow
						$sql = "SELECT NVL(ORDER_NUM, SUBSTR(ACTIVITY_DESCRIPTION, 21)) THE_DESC FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE IN ('6', '17') AND QTY_LEFT <= 10 ORDER BY ACTIVITY_NUM DESC";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT19a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT19b)");
						if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\nPALLET NOT\nAVAILABLE.\n\nCONTACT INVENTORY\nFOR DETAILS.", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$continue_pallet = false;
						} else {
							fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\nPALLET ALREADY\nSHIPPED\n(".$short_term_data_row['THE_DESC']."),\n\nPress Enter", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$continue_pallet = false;
						}
					}
				} elseif($short_term_data_row['THE_COUNT'] == 1){
					// one pallet in house
					$sql = "SELECT ARRIVAL_NUM, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT4a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT4b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$Arrival = $short_term_data_row['ARRIVAL_NUM'];
					$pallet_owner = $short_term_data_row['RECEIVER_ID'];
				} else {
					// more than 1 in house, determine if *only* one is current customer for order or not
					$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0 AND RECEIVER_ID = '".$cust."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT15a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT15b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] == 1){
						// there is only 1 in house FOR THIS CUSTOMER.  get data for it.
						$sql = "SELECT ARRIVAL_NUM, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0 AND RECEIVER_ID = '".$cust."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT5a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT5b)");
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$Arrival = $short_term_data_row['ARRIVAL_NUM'];
						$pallet_owner = $short_term_data_row['RECEIVER_ID'];
					} else {
						// there is more than 1 in house, and either all of them are not this customer, or more than 1 is.
						$continue_pallet = Select_Duplicate_Pallet($Barcode, $pallet_owner, $Arrival, $Commodity, $junk);
					}
				}

/*					$sql = "SELECT ARRIVAL_NUM, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND QTY_IN_HOUSE > 0 AND RECEIVER_ID = '".$cust."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT5a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT5b)");
					if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						// only one of the in-house pallets belong to the customer for the order, take it.
						$Arrival = $short_term_data_row['ARRIVAL_NUM'];
						$pallet_owner = $short_term_data_row['RECEIVER_ID'];
					} else {
						// multiple pallets in house, none belong to owner of order.
						$continue_pallet = Select_Duplicate_Pallet($Barcode, $pallet_owner, $Arrival, $Commodity, $junk);
					}
				} */
			}

			// ok, now we have other pallet info, check if these big guys named Walmart have anything special on it...
/*			if($continue_pallet){
				$walmart_validation = validate_pallet_for_walmart($Barcode, $order_num, $cust, $Arrival, $pallet_owner);
				if($walmart_validation != ""){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**".$walmart_validation."**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}
*/

			if($continue_pallet){
				// get other pallet data
//				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, CARGO_SIZE, CT.VARIETY, CHEP, QTY_IN_HOUSE, MARK, NVL(TO_CHAR(WM_ITEM_NUM), CT.BATCH_ID) THE_ITEM, CARGO_STATUS, CARGO_TYPE_ID FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP, WM_ITEMNUM_MAPPING WIM WHERE PALLET_ID = '".$Barcode."' AND CT.RECEIVER_ID = '".$pallet_owner."' AND CT.ARRIVAL_NUM = '".$Arrival."' AND CUSP.CUSTOMER_ID = CT.RECEIVER_ID AND COMP.COMMODITY_CODE = CT.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.BATCH_ID = TO_CHAR(WIM.ITEM_NUM(+))";
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, CARGO_SIZE, CT.VARIETY, CHEP, QTY_IN_HOUSE, MARK, NVL(BOL, 'UNKNOWN') THE_ITEM, CARGO_STATUS, WM_PROGRAM, CT.CARGO_TYPE_ID FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP, WM_CARGO_TYPE WCT WHERE PALLET_ID = '".$Barcode."' AND CT.RECEIVER_ID = '".$pallet_owner."' AND CT.ARRIVAL_NUM = '".$Arrival."' AND CUSP.CUSTOMER_ID = CT.RECEIVER_ID AND COMP.COMMODITY_CODE = CT.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT7a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT7b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_ship = $short_term_data_row['QTY_IN_HOUSE'];
				$date_rec = $short_term_data_row['THE_DATE'];
				$variety = $short_term_data_row['VARIETY'];
				$chep = $short_term_data_row['CHEP'];
				$pallet_status = $short_term_data_row['CARGO_STATUS'];
				$cargo_size = $short_term_data_row['CARGO_SIZE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$item_type = $short_term_data_row['THE_ITEM'];
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
				$WM_Program = $short_term_data_row['CARGO_TYPE_ID'];
				$WM_Program_Name = $short_term_data_row['WM_PROGRAM'];
				$PO = $short_term_data_row['MARK'];

				$sql = "SELECT USDA_HOLD FROM CARGO_TRACKING_ADDITIONAL_DATA CT
						WHERE PALLET_ID = '".$Barcode."' AND CT.RECEIVER_ID = '".$pallet_owner."' AND CT.ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT7-1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT7-1b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$USDA_hold = $short_term_data_row['USDA_HOLD'];
			}


			// AT THIS POINT, we have all the info we need to ship.

			// we dont want lucca pallets on base orders (or base on lucca)
			if($continue_pallet && $lucca && $WM_Program_Name == "BASE"){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT PUT BASE\nPALLETS ON LUCCA\nORDERS**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}
			if($continue_pallet && !$lucca && $WM_Program_Name != "BASE"){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT PUT NON-BASE\nPALLETS ON\nBASE ORDER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}


			// WALMART does NOT allow auto-transfers of pallets.  If they want to transfer stuff, use the transfer function.
			if($continue_pallet && ($cust != $pallet_owner)){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT MIX WALMART\nAND NON-WM\nPALLETS/ORDERS**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}


			// make sure in the case of a "changed owner" pallet, it wasn't already shipped under the current cust
			if($continue_pallet && ($cust != $pallet_owner)){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$pallet_owner."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT6b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] > 0){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**ALREADY HAS\nOUTBOUND ACTIVITY\nCANNOT CHANGE\nOWNER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// also check that the new pallet/arrival/owner combo is not taken
			if($continue_pallet && ($cust != $pallet_owner)){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$cust."' AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] > 0){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT CHANGE\nOWNER, ".$cust."\nALREADY HAS PALLET\n".$Barcode."\nIN DATABASE.\nCONTACT INVENTORY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			if($continue_pallet){
				$FIFO_exempt = false;

				// store variable if this is a repack load or not
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM WM_EXPECTED_REPACK_ORDER
						WHERE ORDER_NUM = '".$order_num."'";
//				echo "sql\n".$sql."\n";
//				fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT28a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT28b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0){
					$repack = false;

					// also, figure out if it is "FIFO Over-ridden" for non-repack orders
					$sql = "SELECT NVL(FIFO_OVERRIDE_BY, 'NONE') THE_OVER
							FROM WDI_LOAD_DCPO
							WHERE DCPO_NUM = '".$order_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOverride\n(LT1a-y)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nOverride\n(LT1b-y)");
					if(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)
						&&
						$short_term_data_row['THE_OVER'] != "NONE"){
						$FIFO_exempt = true;
					}

				} else {
					$repack = true;
				}
			}

			// make sure the item scanned matches an item# on the projected orders (good, base orders only)  
			if($continue_pallet && !$lucca && !$repack){ 
				if($item_type == 'MULTIPLE'){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**THIS SPLIT-PALLET\nHAS MULTIPLE ITEM#\nPLEASE CALL\nOFFICE**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}
			
			// check if pallet fits the criteria.  non-lucca only.
			if($continue_pallet && !$lucca){
				if($repack == false){
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM WDI_LOAD_DCPO_ITEMNUMBERS
							WHERE DCPO_NUM = '".$order_num."'
								AND ITEM_NUM = '".$item_type."'
								AND PALLETS > 0";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a-g)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b-g)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] <= 0){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**ITEM DOES NOT\nMATCH ORDER.\nCONTACT OFFICE**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				} else { // repack is true here...
					$sql = "SELECT COUNT(*) THE_COUNT
							FROM WM_EXPECTED_REPACK_ORDER
							WHERE ORDER_NUM = '".$order_num."'
								AND PO_NUM = '".$PO."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a-g)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b-g)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['THE_COUNT'] <= 0){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**ITEM P.O. DOES NOT\nMATCH ORDER P.O.\nCONTACT OFFICE**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}
			}

			// verify that a hold isnt being violated 
			// non-lucca only
			if($continue_pallet && !$lucca){
/*//				echo "status:\n".$pallet_status."\n";
				$sql = "SELECT COUNT(*) THE_COUNT 
						FROM WM_EXPECTED_REPACK_ORDER
						WHERE ORDER_NUM = '".$order_num."'
						AND PO_NUM = '".$PO."'";
//				echo "sql\n".$sql."\n";
//				fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT28a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT28b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); */
				if($repack == false){
					// this is a "normal" order.  dont allow holds.
					if($pallet_status == "HOLD"){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET STATUS ON\n".$pallet_status.", CANNOT SHIP**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					} elseif($USDA_hold == "Y") {
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**USDA HOLD IS SET\nCANNOT SHIP**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				} else {
					// this is a repack order.
					// ONLY allow holds.
					if($pallet_status != "HOLD"){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\n**THIS ORDER IS A\nREPACK, AND MUST\nUSE 'HOLD' PALLETS\nBC: ".$Barcode."\nNOT ON HOLD\nCANNOT SHIP**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}
			}

			if($continue_pallet && $WM_Program == 8){
				$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_VESSEL_RELEASE WHERE LR_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] <= 0){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nVessel: ".$Arrival."\n**NOT YET BEEN\nRELEASED, CANNOT\nSHIP THIS PLT**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// find out of this is a split-pallet for Walmart.
			$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
	//		echo $sql."\n";
	//		fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nActivity\n(LT15a-a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nActivity\n(LT15a-b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$walmart_split_count = $short_term_data_row['THE_COUNT'];

			if($continue_pallet && ($cust != $pallet_owner) && ($walmart_split_count > 0)){
				fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT_PALLET\nAND CANNOT BE\nAUTO-TRANSFERRED**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			if($walmart_split_count == 0){ // this is a run-of-the-mill pallet.  NO SPLITS.

				// make sure this pallet passes FIFO inspection.
				// skip this if the order is a repack
				// also skip if order is lucca (non-base)
/*							REMOVED 03/27/2014:  if C_T has the HOLD marker, then that is sufficient, regardless of splits	
								UNION
								SELECT ARRIVAL_NUM FROM WDI_SPLIT_PALLETS
								WHERE QTY_IN_HOUSE > 0
								AND RECEIVER_ID = '3000'
								AND DATE_RECEIVED IS NOT NULL
								AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
								AND BATCH_ID IN
									(SELECT TO_CHAR(ITEM_NUM)
									FROM WM_ITEMNUM_MAPPING
									WHERE WM_ITEM_NUM = '".$item_type."'
									)
*/
				if($continue_pallet && $repack == false && !$lucca && !$FIFO_exempt){
					$sql = "SELECT * FROM WDI_VESSEL_RELEASE
							WHERE TO_CHAR(LR_NUM) IN
								(SELECT ARRIVAL_NUM FROM CARGO_TRACKING
								WHERE QTY_IN_HOUSE > 0
								AND RECEIVER_ID = '3000'
								AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
								AND DATE_RECEIVED IS NOT NULL
								AND BATCH_ID IN
									(SELECT TO_CHAR(ITEM_NUM)
									FROM WM_ITEMNUM_MAPPING
									WHERE WM_ITEM_NUM = '".$item_type."'
									)
								)
							ORDER BY RELEASE_TIME";
/*					$sql = "SELECT * FROM VOYAGE
							WHERE TO_CHAR(LR_NUM) IN
								(SELECT ARRIVAL_NUM FROM CARGO_TRACKING
								WHERE QTY_IN_HOUSE > 0
								AND RECEIVER_ID = '3000'
								AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
								AND DATE_RECEIVED IS NOT NULL
								AND BATCH_ID IN
									(SELECT TO_CHAR(ITEM_NUM)
									FROM WM_ITEMNUM_MAPPING
									WHERE WM_ITEM_NUM = '".$item_type."'
									)
								)
							AND LR_NUM IN
								(SELECT LR_NUM
								FROM WDI_VESSEL_RELEASE
								)
							ORDER BY DATE_EXPECTED NULLS LAST"; */
//					echo $sql."\n";
//					fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a-f)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b-f)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($short_term_data_row['LR_NUM'] != $Arrival && $short_term_data_row['LR_NUM'] != ""){
						// A-C gets an auto-pass
						if($pallet_status != "A-C"){
							fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**DOES NOT MATCH\nFIFO-ORDER; \nPALLETS FROM\nVESSEL ".$short_term_data_row['LR_NUM']."\nSTILL IN HOUSE.\nPLEASE CONTACT\nOFFICE**", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$continue_pallet = false;
						}
					}
				}

				// display pallet info, change qty_to_ship if desired
				if($continue_pallet){
					$act_num = get_max_activity_num($cust, $Barcode, $Arrival);
					$sub_choice = "default";
					while($sub_choice != "" && $sub_choice != "X"){
						system("clear");
						echo $Barcode."\n";
						echo "Cust: ".$custname."\n";
						echo "Comm: ".$commname."\n";
						echo "WMitem#: ".$item_type."\n";
						echo "LR: ".$Arrival."\n";
						echo "Vesl: ".$vesname."\n";
						echo "Loc: ".$loc."\n";
						echo "Rcvd: ".$qty_rec."  Dmg: ".$qty_dmg."\n";
						echo "Variety: ".$variety."\n";
						echo "Size: ".$cargo_size."\n";
						echo "Type: ".$WM_Program_Name."\n";
						echo "QTY TO SHIP: ".$qty_to_ship."\n\n";
						echo "C = Change QTY\nEnter=OK X=Exit\n";
						$sub_choice = "";
						fscanf(STDIN, "%s\n", $sub_choice);
						$sub_choice = strtoupper($sub_choice);
						if($sub_choice == "C"){
							system("clear");
							echo "QTY to ship: ".$qty_to_ship."\n";
							echo "MAX Available: ".$qty_in_house."\n\n";
							echo "Ship QTY:\n";
							$new_qty = "";
							fscanf(STDIN, "%s\n", $new_qty);
							$new_qty = strtoupper($new_qty);
							if(is_numeric($new_qty)){
								if($new_qty <= $qty_in_house && $new_qty >= 0){
									$qty_to_ship = $new_qty;
								}
							}
						} elseif($sub_choice == "X"){
							$continue_pallet = false;
						}
					}
				}

				if($continue_pallet){
					if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}

/*
				if($continue_pallet && ($cust != $pallet_owner)){
					// if the selected pallet does not match the owner of the order, get verification
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET OWNER IS\nNOT ".$cust.".  DO YOU\nWISH TO CHANGE\nOWNER FROM ".$pallet_owner."\nTO ".$cust."? Y/N**", "bad");
					$selection = "";
					while($selection != "Y" && $selection != "N"){
						fscanf(STDIN, "%s\n", $selection);
						$selection = strtoupper($selection);
					}
					if($selection == "N"){
						$continue_pallet = false;
					}
				}
*/
				
				// WE NOW HAVE EVERYTHING WE NEED.
				// if we have gotten this far, proceed to place on order, and if necessary, "auto-transfer".

				if($continue_pallet){
					// auto-receive if necessary
					if($date_rec == "NONE"){
						Auto_Receive_Pallet($emp_no, $Barcode, $pallet_owner, $Arrival, $Commodity, "ship");
					}

/*					NO AUTOTRANSFERS
					// if not the original owner...
					if($cust != $pallet_owner){

						// make new pallet
						$sql = "INSERT INTO CARGO_TRACKING
								(COMMODITY_CODE,
								CARGO_DESCRIPTION,
								DATE_RECEIVED,
								QTY_RECEIVED,
								RECEIVER_ID,
								QTY_DAMAGED,
								WAREHOUSE_LOCATION,
								QTY_IN_HOUSE,
								CARGO_STATUS,
								FREE_TIME_END,
								BILLING_STORAGE_DATE,
								FROM_SHIPPING_LINE,
								SHIPPING_LINE,
								BILL,
								QTY_IN_STORAGE,
								FUMIGATION_CODE,
								EXPORTER_CODE,
								PALLET_ID,
								ARRIVAL_NUM,
								RECEIVING_TYPE,
								WEIGHT,
								BOL,
								DECK,
								HATCH,
								REMARK,
								CARGO_TYPE_ID,
								SOURCE_NOTE,
								SOURCE_USER)
								(SELECT
								COMMODITY_CODE,
								CARGO_DESCRIPTION,
								DATE_RECEIVED,
								'".$qty_in_house."',
								'".$cust."',
								QTY_DAMAGED,
								WAREHOUSE_LOCATION,
								'".$qty_in_house."',
								CARGO_STATUS,
								FREE_TIME_END,
								BILLING_STORAGE_DATE,
								FROM_SHIPPING_LINE,
								SHIPPING_LINE,
								BILL,
								QTY_IN_STORAGE,
								FUMIGATION_CODE,
								EXPORTER_CODE,
								PALLET_ID,
								ARRIVAL_NUM,
								RECEIVING_TYPE,
								WEIGHT,
								BOL,
								DECK,
								HATCH,
								REMARK,
								CARGO_TYPE_ID,
								'Scanner Outbound Auto-transfer',
								'".$emp_no."'
								FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$pallet_owner."' AND ARRIVAL_NUM = '".$Arrival."')";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nInsert Pallet\n(LT9a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nInsert Pallet\n(LT9b)");

						// adjust old pallet in CT
						$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 0 WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$pallet_owner."' AND ARRIVAL_NUM = '".$Arrival."'"; 
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(LT10a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(LT10b)");

						$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$pallet_owner."' AND ARRIVAL_NUM = '".$Arrival."'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT11a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT11b)");
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$act_num = $short_term_data_row['THE_MAX'] + 1;


						// add CA record of transfer (out)
						$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT)
								VALUES
								('".$act_num."',
								'17',
								'".$qty_in_house."',
								'".$emp_no."',
								'outbound auto-trans sent to ".$cust."',
								'".$pallet_owner."',
								SYSDATE,
								'".$Barcode."',
								'".$Arrival."',
								'0')";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(LT12a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(LT12b)");

						// add CA record of transfer (in)
						$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT)
								VALUES
								('1',
								'17',
								'".$qty_in_house."',
								'".$emp_no."',
								'outbound auto-trans in from ".$pallet_owner."',
								'".$cust."',
								SYSDATE,
								'".$Barcode."',
								'".$Arrival."',
								'".$qty_in_house."')";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(LT13a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(LT13b)");

						// add to list of altered pallets
						$email_list_of_changed_owner_pallets .= $Barcode." was changed from ".$pallet_owner." to ".$cust." by checker ".$CID." in wing ".$loc."\r\n";


						// end of "autotransfer" portion of Truck Out
					}
*/

					// update cargo_tracking with new QTY_IN_HOUSE, -XXX- CARGO_STATUS values
					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$qty_to_ship." WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(LT14a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(LT14b)");
/*
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(LT15a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(LT15b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
					// insert cargo_activity record for outbound shipment
					$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, ACTIVITY_BILLED)
							VALUES
							('".($act_num + 1)."',
							'6',
							'".$qty_to_ship."',
							'".$emp_no."',
							'".$order_num."',
							'".$cust."',
							SYSDATE,
							'".$Barcode."',
							'".$Arrival."',
							'".($qty_in_house - $qty_to_ship)."',
							'".$wing."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(LT13a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(LT13b)");

					// transaction complete.
					ora_commit($rf_conn);
				}
			} else { // THIS IS A SPLIT_PALLET FROM WALMART, GETS A WHOLE LOT OF SPECIAL TREATMENT
				if($continue_pallet){
					if($date_rec == "NONE"){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT PALLET.\nPALLET NOT YET\nRECEIVED, CANNOT\nAUTO-RECEIVE\nSPLIT PALLETS.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;;
					}
				}

				if($continue_pallet){
					fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT PALLET.\nPLEASE REVIEW\nEACH SPLIT\nCAREFULLY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
				}

				$pallet_array = array(); // list of split-pallet IDs
				$qty_array = array(); // qty for each split, array index matches $pallet_array
				$split_max_index = 0;
				$splits_to_ship = 0;

				// populate the array of split-pallets.
				$sql = "SELECT SPLIT_PALLET_ID FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$pallet_owner."' AND ARRIVAL_NUM = '".$Arrival."' ORDER BY SPLIT_PALLET_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT25a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT25a-b)");
				while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_array[$split_max_index] = $short_term_data_row['SPLIT_PALLET_ID'];
					$split_max_index++;
				}

				// with the array populated, we loop through the pallet.
				for($this_split_index = 0; $this_split_index < $split_max_index; $this_split_index++){
					$continue_split = true;

					if($continue_pallet){
						$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, NVL(VESSEL_NAME, WSP.ARRIVAL_NUM) THE_VES, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, CARGO_SIZE, SUB_CUSTID THE_GROWER, WSP.VARIETY, CHEP, QTY_IN_HOUSE, NVL(TO_CHAR(WM_ITEM_NUM), WSP.BATCH_ID) THE_ITEM, CARGO_STATUS, WM_PROGRAM FROM WDI_SPLIT_PALLETS WSP, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP, WM_ITEMNUM_MAPPING WIM, WM_CARGO_TYPE WCT WHERE PALLET_ID = '".$Barcode."' AND WSP.RECEIVER_ID = '".$pallet_owner."' AND WSP.ARRIVAL_NUM = '".$Arrival."' AND CUSP.CUSTOMER_ID = WSP.RECEIVER_ID AND COMP.COMMODITY_CODE = WSP.COMMODITY_CODE AND WSP.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND WSP.BATCH_ID = TO_CHAR(WIM.ITEM_NUM(+)) AND SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."' AND WSP.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT26a-a)");
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT26a-b)");
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
						$qty_to_ship = $short_term_data_row['QTY_IN_HOUSE'];
						$date_rec = $short_term_data_row['THE_DATE'];
						$variety = $short_term_data_row['VARIETY'];
						$chep = $short_term_data_row['CHEP'];
						$grower = $short_term_data_row['THE_GROWER'];
						$pallet_status = $short_term_data_row['CARGO_STATUS'];
						$cargo_size = $short_term_data_row['CARGO_SIZE'];
						$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
						$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
						$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
						$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
						$item_type = $short_term_data_row['THE_ITEM'];
						$qty_rec = $short_term_data_row['QTY_RECEIVED'];
						$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
						$WM_Program_Name = $short_term_data_row['WM_PROGRAM'];
/*
						$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, NVL(VESSEL_NAME, WSP.ARRIVAL_NUM) THE_VES, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, CARGO_SIZE, WSP.VARIETY, SUB_CUSTID THE_GROWER, QTY_IN_HOUSE FROM WDI_SPLIT_PALLETS WSP, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP WHERE WSP.SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."' AND CUSP.CUSTOMER_ID = WSP.RECEIVER_ID AND COMP.COMMODITY_CODE = WSP.COMMODITY_CODE AND WSP.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
						$ora_success = ora_parse($select_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT26a-a)");
						$ora_success = ora_exec($select_cursor, $sql);
						database_check($ora_success, "Unable to get\nPallet Info\n(LT26a-b)");
						ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$qty_in_house = $select_row['QTY_IN_HOUSE'];
						$qty_to_ship = $select_row['QTY_IN_HOUSE'];
						$date_rec = $select_row['THE_DATE'];
						$variety = $select_row['VARIETY'];
						$grower = $select_row['THE_GROWER'];
						$cargo_size = $select_row['CARGO_SIZE'];
						$custname = substr($select_row['CUSTOMER_NAME'], 0, 12);
						$commname = substr($select_row['COMMODITY_NAME'], 0, 12);
						$vesname = substr($select_row['THE_VES'], 0, 12);
						$loc = substr($select_row['WAREHOUSE_LOCATION'], 0, 15);
						$qty_rec = $select_row['QTY_RECEIVED'];
						$qty_dmg = $select_row['QTY_DAMAGED'];
*/
//						if($continue_pallet){
						// make sure this pallet passes FIFO inspection.
						// skip this if the order is a repack
						if($continue_pallet && $repack == false){
							$sql = "SELECT * FROM WDI_VESSEL_RELEASE
									WHERE TO_CHAR(LR_NUM) IN
										(SELECT ARRIVAL_NUM FROM CARGO_TRACKING
										WHERE QTY_IN_HOUSE > 0
										AND RECEIVER_ID = '3000'
										AND DATE_RECEIVED IS NOT NULL
										AND BATCH_ID IN
											(SELECT TO_CHAR(ITEM_NUM)
											FROM WM_ITEMNUM_MAPPING
											WHERE WM_ITEM_NUM = '".$item_type."'
											)
										UNION
										SELECT ARRIVAL_NUM FROM WDI_SPLIT_PALLETS
										WHERE QTY_IN_HOUSE > 0
										AND RECEIVER_ID = '3000'
										AND DATE_RECEIVED IS NOT NULL
										AND BATCH_ID IN
											(SELECT TO_CHAR(ITEM_NUM)
											FROM WM_ITEMNUM_MAPPING
											WHERE WM_ITEM_NUM = '".$item_type."'
											)
										)
									ORDER BY RELEASE_TIME";
/*							$sql = "SELECT * FROM VOYAGE
									WHERE TO_CHAR(LR_NUM) IN
										(SELECT ARRIVAL_NUM FROM CARGO_TRACKING
										WHERE QTY_IN_HOUSE > 0
										AND RECEIVER_ID = '3000'
										AND DATE_RECEIVED IS NOT NULL
										AND BATCH_ID IN
											(SELECT TO_CHAR(ITEM_NUM)
											FROM WM_ITEMNUM_MAPPING
											WHERE WM_ITEM_NUM = '".$item_type."'
											)
										UNION
										SELECT ARRIVAL_NUM FROM WDI_SPLIT_PALLETS
										WHERE QTY_IN_HOUSE > 0
										AND RECEIVER_ID = '3000'
										AND DATE_RECEIVED IS NOT NULL
										AND BATCH_ID IN
											(SELECT TO_CHAR(ITEM_NUM)
											FROM WM_ITEMNUM_MAPPING
											WHERE WM_ITEM_NUM = '".$item_type."'
											)
										)
									AND LR_NUM IN
										(SELECT LR_NUM
										FROM WDI_VESSEL_RELEASE
										)
									ORDER BY DATE_EXPECTED NULLS LAST";*/
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8a-q)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT8b-q)");
							ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							if($short_term_data_row['LR_NUM'] != $Arrival && $short_term_data_row['LR_NUM'] != ""){
								// A-C gets an auto-pass
								if($pallet_status != "A-C"){
									fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\nBC: ".$Barcode."\n**DOES NOT MATCH\nFIFO-ORDER.\nUSE PALLETS FROM\nVESSEL ".$short_term_data_row['LR_NUM']."**", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$continue_split = false;
									$continue_pallet = false;
								}
							}
						}

						if($continue_split){
							$act_num = get_max_activity_num($cust, $Barcode, $Arrival);
							$sub_choice = "default";
							while($sub_choice != "" && $sub_choice != "X" && $sub_choice != "S"){
								system("clear");
								echo $Barcode."\n";
								echo "Cust: ".$custname."\n";
								echo "Comm: ".$commname."\n";
								echo "WMitem#: ".$item_type."\n";
								echo "LR: ".$Arrival."\n";
								echo "Vesl: ".$vesname."\n";
								echo "Loc: ".$loc."  Size: ".$cargo_size."\n";
								echo "Rcvd: ".$qty_rec."  Dmg: ".$qty_dmg."\n";
								echo "Variety: ".$variety."\n";
								echo "Type: ".$WM_Program_Name."\n";
								echo "QTY TO SHIP: ".$qty_to_ship."\n\n";
								echo "C = Change QTY\nEnter=OK \nX= Cancel Pallet\nS = Cancel Split\n";
								$sub_choice = "";
								fscanf(STDIN, "%s\n", $sub_choice);
								$sub_choice = strtoupper($sub_choice);
								if($sub_choice == "C"){
									system("clear");
									echo "QTY to ship: ".$qty_to_ship."\n";
									echo "MAX Available: ".$qty_in_house."\n\n";
									echo "Ship QTY:\n";
									$new_qty = "";
									fscanf(STDIN, "%s\n", $new_qty);
									$new_qty = strtoupper($new_qty);
									if(is_numeric($new_qty)){
										if($new_qty <= $qty_in_house && $new_qty >= 0){
											$qty_to_ship = $new_qty;
										}
									}
								} elseif($sub_choice == "X"){
									$continue_pallet = false;
								} elseif($sub_choice == "S"){
									$continue_split = false;
								}
							}
						}

						// if they didn't cancel the split...
						if($continue_pallet && $continue_split){
							$qty_array[$this_split_index] = $qty_to_ship;
						} else {
							$qty_array[$this_split_index] = 0;
						}

					}
				}

				if($continue_pallet){
					if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
						fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}

				// that should get all of the split-info we need, now lets make the appropriate adjustments.
				if($continue_pallet){
					for($this_split_index = 0; $this_split_index < $split_max_index; $this_split_index++){
						if($qty_array[$this_split_index] > 0){
							// get next CA activity number
							$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nPallet Info\n(LT29a-a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nPallet Info\n(LT29a-b)");
							ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							$act_num = $short_term_data_row['THE_MAX'] + 1;

							// update the relevant split-pallet
							// , CARGO_STATUS = '".$wing."'
							$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$qty_array[$this_split_index]." WHERE SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(LT27a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(LT27a-b)");

							// , CARGO_STATUS = '".$wing."'
							$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$qty_array[$this_split_index]." WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(LT28a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(LT28a-b)");

							$sql = "INSERT INTO CARGO_ACTIVITY
									(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, ACTIVITY_BILLED, BATCH_ID)
									VALUES
									('".$act_num."',
									'6',
									'".$qty_array[$this_split_index]."',
									'".$emp_no."',
									'".$order_num."',
									'".$cust."',
									SYSDATE,
									'".$Barcode."',
									'".$Arrival."',
									'0',
									'".$wing."',
									'".$pallet_array[$this_split_index]."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(LT29a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to\nUpdate Pallet\n(LT29a-b)");

							// commit this split.
							ora_commit($rf_conn);
						}
					}
				}
			}


/*
			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nCustomer\n(LT2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_pallets = $short_term_data_row['THE_COUNT'];
			$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/
			$total_cases = 0;
			$total_pallets = 0;
			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT16a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(LT16b)");
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
					$total_cases += $short_term_data_row['THE_SUM'];
					$total_pallets++;
				}
			}

			fresh_screen("WALMART FRUIT\nTruck Out\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Ord#: ".$order_num."\n";
			echo "Wing: ".$wing."\n";
			echo $total_cases." in ".$total_pallets." plts\n\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}

		// end of input barcodes.  if some pallets had changed owners, dispatch email.
		if($email_list_of_changed_owner_pallets != ""){
			$mailSubject = "Pallet owners changed on outbound order ".$order_num."\r\n";
//			mail("lstewart@port.state.de.us,awalter@port.state.de.us\r\n", $mailSubject, $email_list_of_changed_owner_pallets, $mailHeaders);
			mail($inv_and_fina_mail_info_list, $mailSubject, $body, $mailHeaders);
			$sql = "INSERT INTO SCANNER_EMAIL_LOG (FROM_SCANNER_TYPE, DATE_SENT, EMAIL_TO_LIST, EMAIL_BODY, EMAIL_SUBJECT) VALUES ('WalMART', SYSDATE, '".$inv_and_fina_mail_info_list."', '".$email_list_of_changed_owner_pallets."', '".$mailSubject."')";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nInsert Email\n(LT17a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to\nInsert Email\n(LT17b)");
			ora_commit($rf_conn);

		}
	}
}

function Relocate($CID, $service_code){
	global $rf_conn;
	global $scanner_type;

	if($service_code == 2){
		$titlestring = "Relocate";
	} else {
//		$titlestring = "Trans Cold Strg";
	}

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	fresh_screen("WALMART FRUIT\n".$titlestring."\nEnter X to exit.");
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

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReL1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReL1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet does not exist
			fresh_screen("WALMART FRUIT\n".$titlestring."\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
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

		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(ReL2a-a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(ReL2a-b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$walmart_split_count = $short_term_data_row['THE_COUNT'];

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, WAREHOUSE_LOCATION, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReL3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(ReL3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$split_received = $short_term_data_row['THE_DATE'];
		$cur_loc = $short_term_data_row['WAREHOUSE_LOCATION'];
		$item_type = $short_term_data_row['THE_ITEM'];

		if(!validate_customer_to_scannertype($Cust, $scanner_type, "Relocate")){
			fresh_screen("WALMART FRUIT\nLoad Truck\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}

		while($continue){
			$act_num = get_max_activity_num($Cust, $Barcode, $Arrival);
			// do this if they did not "cancel" or "error" out of above
			fresh_screen("WALMART FRUIT\n".$titlestring."\nEnter X to exit.");
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "WMitem#: ".$item_type."\n";
			echo "CUST: ".$Cust."\n";
			echo "BC: ".$Barcode."\n\n";
			echo "Current Loc: ".$cur_loc."\n";
			echo "New Loc: \n";
			$loc = "";
			fscanf(STDIN, "%[^[]]\n", $loc);
			$loc = trim(strtoupper($loc));

			if($walmart_split_count > 0 && $split_received == "NONE"){
				fresh_screen("WALMART FRUIT\nLoad Truck\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A SPLIT PALLET\nNOT YET RECEIVED\nCANNOT AUTORECEIVE\nSPLIT PALLETS**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$loc = "X";
			}

			if($loc == "X" || $loc == ""){
				$continue = false;
			} elseif(strpos($loc, "CR") !== false || strpos($loc, "RC") !== false) {
				fresh_screen("WALMART FRUIT\nLoad Truck\nEnter X to exit.\n\n**CANNOT XFER PALLET\nINTO ".$loc.", USE\nRAPID COOL\nFUNCTION FOR THAT**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$loc = $cur_loc;
			} elseif(!is_max_activity_num($act_num, $Cust, $Barcode, $Arrival)){
				fresh_screen("WALMART FRUIT\nRelocate\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
			} else {
				$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, NVL(WAREHOUSE_LOCATION, 'NONE') THE_LOC, QTY_IN_HOUSE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$prev_loc = $short_term_data_row['THE_LOC'];
				$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
				if($short_term_data_row['THE_DATE'] == "NONE"){
					Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, "ship");
				}
/*
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL6a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL6b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
				// change the CARGO_TRACKING location
				$sql = "UPDATE CARGO_TRACKING SET WAREHOUSE_LOCATION = '".$loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL4a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL4b)");

				// update any necessary split pallets
				if($walmart_split_count > 0){
					$sql = "UPDATE WDI_SPLIT_PALLETS SET WAREHOUSE_LOCATION = '".$loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4a-a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4b-b)");
				}

				// add CARGO_ACTIVITY record
				$sql = "INSERT INTO CARGO_ACTIVITY 
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, ACTIVITY_DESCRIPTION, FROM_LOCATION) VALUES
						('".($act_num + 1)."', '".$service_code."', '0', '".$emp_no."', '".$Cust."', SYSDATE, '".$Barcode."', '".$Arrival."', '".$qty_trans."', 'MOVE TO: ".$loc."', '".$prev_loc."')";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL5a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to change\nPallet Info\n(ReL5b)");
				
				ora_commit($rf_conn);
				fresh_screen("WALMART FRUIT\n".$titlestring."\nEnter X to exit.");
				echo $Barcode."\nMoved to ".$loc."\n\n";
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		$Barcode = "";
		fresh_screen("WALMART FRUIT\n".$titlestring."\nEnter X to exit.");
		echo "Next Barcode:\n";
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
		fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
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
			fresh_screen("WALMART FRUIT\nTrans_Owner\nEnter X to exit.\n\nCust: ".$cust_from."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
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
		fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
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
			fresh_screen("WALMART FRUIT\nTrans_Owner\nEnter X to exit.\n\nCust: ".$cust_to."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
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
/*
	// VERIFY TO MAKE SURE THIS TRANSFER DOES NOT TRANSFER INTO OR OUT OF WALMART GROUP
	// to prevent the guys outside from complaining, I have to "reload" the function if there is a mismatch, which is why the recursive
	// function call is in here, followed by an immediate return.
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$cust_from."' AND CUSTOMER_GROUP = 'WALMART'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO30a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO30b)");
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] > 0){
			$from = true;
		} else {
			$from = false;
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$cust_to."' AND CUSTOMER_GROUP = 'WALMART'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO31a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO31b)");
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] > 0){
			$to = true;
		} else {
			$to = false;
		}

		if($from != $to){
			fresh_screen("CHILEAN FRUIT\nTransfer Owner\nEnter X to exit.\n\n**CANNOT TRANSFER\nPALLETS INTO\nOR OUT OF\nWALMART GROUP**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			Trans_Owner($CID);
			return;
		}
*/
	// get and validate wing
	while($wing == "" || $wing == "Invalid"){
		fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
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
		fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
		echo "Order #:";
		fscanf(STDIN, "%s\n", $order_num);
		$order_num = strtoupper($order_num);
		if($order_num == "X"){
			return;
		}
		if(!S5_validity_check($order_num)){
			fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\n**ORDER# CAN ONLY\nBE ALPHANUMERIC**", "bad");
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



	fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
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
		$Arrival = "";
		$Commodity = "";
		$qty_rec = "";
		$continue = true;

		// no pallet, 1 pallet, or multiple pallets?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND QTY_IN_HOUSE > 0";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO31a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO31b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			// pallet not available, figure out why
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(T231a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(T231b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				// pallet does not exist
				fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} else {
				// pallet already "out" somehow
				$sql = "SELECT ORDER_NUM THE_DESC FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust_from."' AND SERVICE_CODE IN ('11') AND QTY_LEFT <= 10 ORDER BY ACTIVITY_NUM DESC";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(T233a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(T233b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\nPALLET NOT\nAVAILABLE.\n\nCONTACT INVENTORY\nFOR DETAILS.", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue = false;
				} else {
					fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\nPALLET ALREADY\nTRANSFERRED ON\nORD ".$short_term_data_row['THE_DESC'].",\n\nPress Enter", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue = false;
				}
/*
				// pallet not in house
				fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT IN\nHOUSE.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
*/
			}
		} elseif($short_term_data_row['THE_COUNT'] > 1){
			// multiple pallet
			// PLEASE NOTE:  qty_in_house
			$continue = Select_Duplicate_Pallet($Barcode, $cust_from, $Arrival, $Commodity, $qty_rec);
		} else {
			// single pallet, get info
			$sql = "SELECT ARRIVAL_NUM, COMMODITY_CODE, QTY_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			$Commodity = $short_term_data_row['COMMODITY_CODE'];
			$qty_rec = $short_term_data_row['QTY_RECEIVED'];
		}

		// find out if this is a split pallet... (which will get blocked)
		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."'";
//		echo $sql."\n";
//		fscanf(STDIN, "%s\n", $junk);
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(TO2a-a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nActivity\n(TO2a-b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$walmart_split_count = $short_term_data_row['THE_COUNT'];

		// auto-receive if necessary
		if($continue){
			$sql = "SELECT DATE_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$Commodity."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO12b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['DATE_RECEIVED'] == "" && $walmart_split_count == 0){
				Auto_Receive_Pallet($emp_no, $Barcode, $cust_from, $Arrival, $Commodity, "ship");
			} elseif($short_term_data_row['DATE_RECEIVED'] == "" && $walmart_split_count > 0){
				fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT-PALLET\nPALLET NOT YET\nRECEIVED, CANNOT\nAUTO-RECEIVED\nSPLIT PALLETS**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}
/*
		// verify walmart transfers (no pallet can transfer into, or out of, Walmart group)
		if($continue){
			$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$cust_from."' AND CUSTOMER_GROUP = 'WALMART'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO30a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO30b)");
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_row['THE_COUNT'] > 0){
				$from = true;
			} else {
				$from = false;
			}

			$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$cust_to."' AND CUSTOMER_GROUP = 'WALMART'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO31a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO31b)");
			ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_row['THE_COUNT'] > 0){
				$to = true;
			} else {
				$to = false;
			}
		
			if($from != $to){
				fresh_screen("CHILEAN FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\n**CANNOT TRANSFER\nPALLETS INTO\nOR OUT OF\nWALMART GROUP\nCONTACT INVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}
*/
		// make sure the DESTINATION combination of cust / LR# / pallet isnt taken
		if($continue){
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust_to."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO22a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO22b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] > 0){
				fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nBC: ".$Barcode."\n**CUST ".$cust_to." ALREADY\nHAS PLT ".$Barcode."\nFOR LR# ".$Arrival."\nCONTACT INVENTORY**", "bad");
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

		// ask for dmg qty
		if($continue){
			$act_num = get_max_activity_num($cust_from, $Barcode, $Arrival);
			fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\nBC: ".$Barcode."\nCust: ".$cust_from);
			echo "QTY Damaged:\n";
			$qty_dmg = "nope";
			while($qty_dmg != "X" && (!is_numeric($qty_dmg) || $qty_dmg < 0 || $qty_dmg > $qty_trans)){
				fscanf(STDIN, "%s\n", $qty_dmg);
				$qty_dmg = strtoupper($qty_dmg);
			}
			if($qty_dmg == "X"){
				$continue = false;
				fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$Barcode."\nCancelled", "bad");
				fscanf(STDIN, "%s\n", $junk);
			}
		}

		if($continue){
			if(!is_max_activity_num($act_num, $cust_from, $Barcode, $Arrival)){
				fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		if(!$continue){
			// sub-function cancelled the operation.  Said sub-fuction will handle the display.
			// fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$Barcode."\nCancelled");
		} else {
			// now, update current pallet with outgoing info
			$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 0 WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust_from."' AND COMMODITY_CODE = '".$Commodity."'";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO8a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO8b)");

			if($walmart_split_count > 0){
				$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = 0 WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust_from."' AND COMMODITY_CODE = '".$Commodity."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO8a-a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO8a-b)");
			}
/*
			// get next ACTIVITY_NUM for CARGO_ACTIVITY
			$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO13a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO13b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
			// outgoing activity record
			$sql = "INSERT INTO CARGO_ACTIVITY
					(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, ACTIVITY_BILLED, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".($act_num + 1)."', '11', '".$qty_trans."', '".$emp_no."', '".$order_num."', '".$cust_from."', SYSDATE, '".$wing."', '".$Barcode."', '".$Arrival."', '0')";
			$ora_success = ora_parse($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO16a)");
			$ora_success = ora_exec($modify_cursor, $sql);
			database_check($ora_success, "Unable to update\nPallet Info\n(TO16b)");

			// and insert the new pallet to CARGO_TRACKING...
			$sql = "INSERT INTO CARGO_TRACKING 
					(COMMODITY_CODE, 
					DATE_RECEIVED, 
					QTY_RECEIVED, 
					RECEIVER_ID,
					QTY_IN_HOUSE,
					QTY_DAMAGED,
					CARGO_STATUS,
					FREE_TIME_END,
					BILLING_STORAGE_DATE,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					FUMIGATION_CODE,
					EXPORTER_CODE,
					PALLET_ID,
					ARRIVAL_NUM,
					RECEIVING_TYPE,
					WEIGHT,
					BOL,
					DECK,
					HATCH,
					CARGO_SIZE,
					VARIETY,
					CHEP)
					(SELECT 
					COMMODITY_CODE,
					SYSDATE,
					'".$qty_trans."',
					'".$cust_to."',
					'".$qty_trans."',
					'".$qty_dmg."',
					CARGO_STATUS,
					SYSDATE,
					SYSDATE,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					FUMIGATION_CODE,
					EXPORTER_CODE,
					'".$Barcode."',
					'".$order_num."',
					'F',
					WEIGHT,
					BOL,
					DECK,
					HATCH,
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
			
			// modified Aug 28th 2012.
			// 1 mroe step:  maintain the original ARV#.  so, get the original...
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
			database_check($ora_success, "Unable to get\nPallet Info\n(TO30a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO30b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_plt = (0 + $short_term_data_row['THE_PALLETS']);
			$total_ctn = (0 + $short_term_data_row['THE_CASES']);
//			$total_plt++;
//			$total_ctn += $qty_trans;
		}

		fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
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

/*
		$proceed = true;

		$select_row = array();

		$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."'";
		$ora_success = ora_parse($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet\n(TO4a)");
		$ora_success = ora_exec($select_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet\n(TO4b)");
		$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO9a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(TO9b)");



		if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC) && ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// wrong cust # (EEF that was an ugly if statement, no?)
			fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$Barcode."\n**PALLET DOES NOT\nBELONG TO\nCUST".$cust_from."**", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} elseif($select_row['QTY_IN_HOUSE'] != "" && $select_row['QTY_IN_HOUSE'] < 10) {
			// pallet no longer here.  previous !ora_fetch statement should result in this variable existing.
			fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$Barcode."\n**PALLET NOT\nIN HOUSE**\nCONTACT INVENTORY", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			// scanned pallet (may be) valid, check for state.
			// See if needs to be created, or if there is a duplicate
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO10a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(TO10b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				$proceed = Create_Pallet_From_Scratch($CID, $Barcode, $cust_from, "", "", "");

				if($proceed){ // set the appropriate variables, but only if the previous function wasnt cancelled.
					$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(TO11a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(TO11b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$Arrival = $short_term_data_row['ARRIVAL_NUM'];
					$Commodity = $short_term_data_row['COMMODITY_CODE'];
					$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
				}
			} elseif($short_term_data_row['THE_COUNT'] == 0) {
				$proceed = Select_Duplicate_Pallet($Barcode, $cust_from, $Arrival, $Commodity, $qty_trans);
			} else {
				$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(TO11a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(TO11b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$Commodity = $short_term_data_row['COMMODITY_CODE'];
				$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
			}

			if(!$proceed){
				// a sub-function cancelled the operation.  Said sub-fuction will handle the display.
				// fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.\n\nCust #: ".$cust_from."\nBC: ".$Barcode."\nCancelled");
			} else {
				// ok, we have the pallet, we know the info, lets do the transfer!
				// first up, see if pallet needs to be auto-received.
				$sql = "SELECT DATE_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."' AND COMMODITY_CODE = '".$Commodity."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(TO12a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(TO12b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['DATE_RECEIVED'] == ""){
					Auto_Receive_Pallet($emp_no, $Barcode, $cust_from, $Arrival, $Commodity, "ship");
				}

				// no user inputs between here and ora_commit, please.
				// now, update current pallet with outgoing info
				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = 0, CARGO_STATUS = '".$wing."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust_from."' AND COMMODITY_CODE = '".$Commodity."'";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO8a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO8b)");

				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."'";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(TO13a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(TO13b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;

				$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, ACTIVITY_BILLED, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
						('".$act_num."', '11', '".$qty_trans."', '".$emp_no."', '".$order_num."', '".$cust_from."', SYSDATE, '".$wing."', '".$Barcode."', '".$Arrival."', '0')";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO13a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO13b)");

				// and insert the new pallet...
				$sql = "INSERT INTO CARGO_TRACKING 
						(COMMODITY_CODE, 
						DATE_RECEIVED, 
						QTY_RECEIVED, 
						RECEIVER_ID,
						QTY_IN_HOUSE,
						CARGO_STATUS,
						FREE_TIME_END,
						BILLING_STORAGE_DATE,
						FROM_SHIPPING_LINE,
						SHIPPING_LINE,
						FUMIGATION_CODE,
						EXPORTER_CODE,
						PALLET_ID,
						ARRIVAL_NUM,
						RECEIVING_TYPE,
						WEIGHT,
						BOL,
						DECK,
						HATCH,
						CARGO_SIZE,
						VARIETY,
						CHEP)
						(SELECT 
						COMMODITY_CODE,
						SYSDATE,
						'".$qty_trans."',
						'".$cust_to."',
						'".$qty_trans."',
						'".$wing."',
						SYSDATE,
						SYSDATE,
						FROM_SHIPPING_LINE,
						SHIPPING_LINE,
						FUMIGATION_CODE,
						EXPORTER_CODE,
						'".$Barcode."',
						'".$order_num."',
						'F',
						WEIGHT,
						BOL,
						DECK,
						HATCH,
						CARGO_SIZE,
						VARIETY,
						CHEP
						FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust_from."' AND ARRIVAL_NUM = '".$Arrival."')";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO14a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO14b)");

				$sql = "INSERT INTO CARGO_ACTIVITY 
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, ACTIVITY_BILLED, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
						('1', '11', '".$qty_trans."', '".$emp_no."', '".$order_num."', '".$cust_to."', SYSDATE, '".$wing."', '".$Barcode."', '".$order_num."', '".$qty_trans."')";
//				echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO14a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to update\nPallet Info\n(TO14b)");
				
				ora_commit($rf_conn);
				$total_plt++;
				$total_ctn += $qty_trans;

			}
		}

		fresh_screen("WALMART FRUIT\nTransfer Owner\nEnter X to exit.");
		echo "Order: ".$order_num."\n";
		echo "Cust (From): ".$cust_from."\n";
		echo "Cust (To): ".$cust_to."\n";
		echo "Wing: ".$wing."\n";
		echo $total_ctn." CTN; ".$total_plt." PLT\n";
		echo "Barcode:";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		while($Barcode == ""){
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}

*/

function Info_Pallet(){
	global $rf_conn;
	global $scanner_type;

	$select_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);



	fresh_screen("WALMART FRUIT\nPallet Info\nEnter X to exit.");
	echo "Barcode:\n";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
	while(strtoupper($Barcode) != "X"){

		$total_pallets = 0;
		$pallet_info = array();
		$curr_pallet = 0;
		$continue = true;

		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT RECEIVED YET') DATE_REC, NVL(SUBSTR(VESSEL_NAME, 0, 19), CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM THE_LR,
				SUBSTR(COMMODITY_NAME, 0, 15) THE_COMM, SUBSTR(CUSTOMER_NAME, 0, 15) THE_CUST, RECEIVER_ID, CT.ARRIVAL_NUM, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE,
				CHEP, CARGO_SIZE, VARIETY, WAREHOUSE_LOCATION, NVL(BOL, 'UNKNOWN') THE_ITEM
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
			fresh_screen("WALMART FRUIT\nPallet Info\nEnter X to exit.\n\nNo Pallet with BC\n".$Barcode."\nIn System.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			// at least 1 pallet here
			// put all matching barcodes in an array
			do {
				$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_ACT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$select_row['RECEIVER_ID']."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND (SERVICE_CODE = '6' OR (SERVICE_CODE = '11' AND ACTIVITY_NUM > 1)) AND ACTIVITY_DESCRIPTION IS NULL";
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
				$pallet_info[$total_pallets]["comm"] = $select_row['THE_COMM'];
				$pallet_info[$total_pallets]["wmitem"] = $select_row['THE_ITEM'];
				$pallet_info[$total_pallets]["loc"] = $select_row['WAREHOUSE_LOCATION'];
				$pallet_info[$total_pallets]["qty_rec"] = $select_row['QTY_RECEIVED'];
				$pallet_info[$total_pallets]["dmg"] = $select_row['QTY_DAMAGED'];
				$pallet_info[$total_pallets]["var"] = $select_row['VARIETY'];
				$pallet_info[$total_pallets]["size"] = $select_row['CARGO_SIZE'];
				$pallet_info[$total_pallets]["chep"] = $select_row['CHEP'];
				if($pallet_info[$total_pallets]["LR"] == $select_row['THE_LR']){
					$pallet_info[$total_pallets]["date_rec"] = $select_row['DATE_REC'];
				} else {
					$pallet_info[$total_pallets]["date_rec"] = "LR#".$select_row['THE_LR']." ".$select_row['DATE_REC'];
				}
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
				echo $Barcode."\n";
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
				echo "WMitem#: ".$pallet_info[$display_set_counter]["wmitem"]."\n";
				echo "RV:".$pallet_info[$display_set_counter]["qty_rec"]." Dm:".$pallet_info[$display_set_counter]["dmg"]." In:".$pallet_info[$display_set_counter]["i_h"]."\n";
				echo "Loc: ".$pallet_info[$display_set_counter]["loc"]."\n";
				echo "Var: ".$pallet_info[$display_set_counter]["var"]."\n";
				echo "Size: ".$pallet_info[$display_set_counter]["size"]."  Chep: ".$pallet_info[$display_set_counter]["chep"]."\n";

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
			fresh_screen("WALMART FRUIT\nPallet Info\nEnter X to exit.");
			echo "Barcode:\n";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		} else{
			$Barcode = "X";
		}
	}
}

function Info_Transfer(){
/* 
*	given an order #, shows pallets that had their ownership
*	Moved on said #
*********************************************************************************************/

	global $rf_conn;
	global $scanner_type;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$continue = true; // used to see if someone wants to exit the function, or just put in a new order

	while($continue){
		$cust = "";
		$order_num = "";

		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nTransfer Info\nEnter X to exit.");
			if($cust != ""){
				echo "Invalid Cust #\n";
			}
			echo "Receiving Cust#:\n";
			fscanf(STDIN, "%s\n", $cust);
			$cust = strtoupper($cust);
			if($cust == "X"){
				return;
			}
			$cust = remove_badchars($cust);

			if(!is_numeric($cust)){ // THE RECEIVING CUSTOMER
				$cust = "Invalid";
			} /* elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Info_Transfer")){
				fresh_screen("WALMART FRUIT\nInfo Transfer\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$cust = "Invalid";
			} */ else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(IT1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(IT1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		// get Order
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);
		}

		$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '11' AND ACTIVITY_DESCRIPTION IS NULL AND ACTIVITY_NUM = '1'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IT2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IT2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_cases = $short_term_data_row['THE_SUM'];
		$total_pallets = $short_term_data_row['THE_COUNT'];

		if($total_pallets == 0){
			// break if not valid
			fresh_screen("WALMART FRUIT\nTransfer Info\nEnter X to Exit.\n\nOrder: ".$order_num."\nCust: ".$cust."\nDOES NOT EXIST.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {

			$sql = "SELECT DISTINCT CUSTOMER_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '11' AND ACTIVITY_DESCRIPTION IS NULL AND ACTIVITY_NUM != '1'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IT3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IT3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$from_cust = $short_term_data_row['CUSTOMER_ID'];

			$pallet_number = 0;
			$pallet_info = array();

			// valid order, start retrieving data...
			$sql = "SELECT PALLET_ID, QTY_CHANGE, ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '11' AND ACTIVITY_DESCRIPTION IS NULL AND ACTIVITY_NUM = '1' ORDER BY PALLET_ID";
			$ora_success = ora_parse($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IT4a)");
			$ora_success = ora_exec($select_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IT4b)");

			while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// get vessel name if applicable (and pad with spaces)
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$select_row['ARRIVAL_NUM']."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nVessel Info\n(IT5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nVessel Info\n(IT5b)");
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
				$pallet_info[$pallet_number]["barcode"] = substr($select_row['PALLET_ID'], 0, 15);
				$pallet_info[$pallet_number]["qty_change"] = $select_row['QTY_CHANGE'];

				$pallet_number++;
			}

			$choice = "there is no spoon";
			$display_set_counter = 0; 
			$max_page = ceil($total_pallets / 4);

			while($choice != "X" && $choice != "R"){
				// DO NOT USE FRESHSCREEN FUNCTION HERE
				// we need all the screen space we can get.
				system("clear");
				echo $cust." -> ".$from_cust."\n";
				echo $order_num."\n";
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

/*
SELECT CA_FROM.PALLET_ID, CA_FROM.CUSTOMER_ID, CA_TO.CUSTOMER_ID, CA_TO.QTY_CHANGE 
FROM CARGO_ACTIVITY CA_FROM, CARGO_ACTIVITY CA_TO
WHERE CA_FROM.PALLET_ID = CA_TO.PALLET_ID
AND CA_FROM.ORDER_NUM = CA_TO.ORDER_NUM
AND CA_FROM.ORDER_NUM = CA_TO.ARRIVAL_NUM
AND CA_FROM.SERVICE_CODE = CA_TO.SERVICE_CODE
AND CA_FROM.QTY_CHANGE = CA_TO.QTY_CHANGE
AND CA_TO.SERVICE_CODE = '11'
AND CA_TO.ORDER_NUM = 'TL26697'
AND CA_TO.ACTIVITY_NUM = '1'
AND CA_FROM.ACTIVITY_NUM != '1'
*/

}




function Info_Inbound_Order(){
/*
* Gets listing of pallets on a given inbound truck order, and carton count
*
*******************************************************************************/
	global $rf_conn;
	global $scanner_type;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$continue = true; // used to see if someone wants to exit the function, or just put in a new order

	while($continue){
		$cust = "";
		$order_num = "";

		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Info_Inbound_Order")){
				fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
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
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);
		}

		$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '8' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IO2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(IO2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_cases = $short_term_data_row['THE_SUM'];
		$total_pallets = $short_term_data_row['THE_COUNT'];

		if($total_pallets == 0){
			// break if not valid
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to Exit.\n\nOrder: ".$order_num."\nCust: ".$cust."\nDOES NOT EXIST.", "bad");
			fscanf(STDIN, "%s\n", $junk);
		} else {
			$pallet_number = 0;
			$pallet_info = array();

			// valid order, start retrieving data...
			$sql = "SELECT PALLET_ID, QTY_CHANGE, ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '8' AND ACTIVITY_DESCRIPTION IS NULL ORDER BY PALLET_ID";
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
				$pallet_info[$pallet_number]["barcode"] = substr($select_row['PALLET_ID'], 0, 15);
				$pallet_info[$pallet_number]["qty_change"] = $select_row['QTY_CHANGE'];

				$pallet_number++;
			}

			$choice = "there is no spoon";
			$display_set_counter = 0; 
			$max_page = ceil($total_pallets / 4);

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

function Info_Outbound_Order(){
/*
* Gets listing of pallets on a given outbound order, and vessel name/carton count
*
*******************************************************************************/
	global $rf_conn;
	global $scanner_type;

	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	$continue = true; // used to see if someone wants to exit the function, or just put in a new order

	while($continue){
		$cust = "";
		$order_num = "";

		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Info_Outbound_Order")){
				fresh_screen("WALMART FRUIT\nOut-Order Info\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
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
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to exit.");
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
			fresh_screen("WALMART FRUIT\nInbound Order Info\nEnter X to Exit.\n\nOrder: ".$order_num."\nCust: ".$cust."\nDOES NOT EXIST.", "bad");
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
				$pallet_info[$pallet_number]["barcode"] = substr($select_row['PALLET_ID'], 0, 15);
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
		$cust = "";
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "VoidOUT")){
				fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
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

		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout12b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}		
		}

		$total_cases = 0;
		$total_pallets = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
		while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
				$total_cases += $short_term_data_row['THE_SUM'];
				$total_pallets++;
			}
		}
/*
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallets = $short_term_data_row['THE_COUNT'];
		$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/

		fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
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
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if this pallet belongs to this customer
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout23a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout23b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nBELONG TO ".$cust."\nCHECK PALLET INFO**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}


			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND CUSTOMER_ID = '".$cust."' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// get  scanned pallet info
			if($continue_pallet){
				$sql = "SELECT NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE CT.PALLET_ID = '".$Barcode."' AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CT.RECEIVER_ID = '".$cust."' AND CA.ACTIVITY_DESCRIPTION IS NULL GROUP BY NVL(VESSEL_NAME, CT.ARRIVAL_NUM), NVL(BOL, 'UNKNOWN'), CT.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION";
//				echo $sql;
//				fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_void = $short_term_data_row['THE_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$item_type = $short_term_data_row['THE_ITEM'];
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}


			// AT THIS POINT, we have all the info we need to void.

			// display data, request confirmation
			if($continue_pallet){
				$act_num = get_max_activity_num($cust, $Barcode, $Arrival);
				$sub_choice = "";
				while($sub_choice != "N" && $sub_choice != "Y"){
					system("clear");
					echo $Barcode."\n";
					echo "Vesl: ".$vesname."\n";
					echo "Out: ".$date_ship."\n";
					echo "Cust: ".$custname."\n";
					echo "Comm: ".$commname."\n";
					echo "WMitem#: ".$item_type."\n";
					echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
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
					fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}



			// if void was confirmed, lets do it!
			if($continue_pallet){
				$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_to_void." WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout6b)");

				// as voids are universal, we don't need to "check" for a walmart-split pallet first, we can just update any that are.
				$sql = "UPDATE WDI_SPLIT_PALLETS WSP 
						SET QTY_IN_HOUSE = QTY_IN_HOUSE + 
							(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY CA WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND CA.BATCH_ID = WSP.SPLIT_PALLET_ID)
						WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'
						AND SPLIT_PALLET_ID IN
							(SELECT BATCH_ID
							FROM CARGO_ACTIVITY
							WHERE ORDER_NUM = '".$order_num."'
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL
							AND BATCH_ID IS NOT NULL)";
//				echo $sql."\n";
//				fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout6a-a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout6a-b)");

				$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout7b)");

				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout8a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(Vout8b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $short_term_data_row['THE_MAX'] + 1;

				// add CA record of void
				$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
				('".$act_num."',
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
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout9a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vout9b)");

				ora_commit($rf_conn);
				fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.");
				echo $qty_to_void." cartons\n";
				echo $Barcode."\n VOIDED FROM ORDER\n\n";
				fscanf(STDIN, "%s\n", $junk);
			}


			$total_cases = 0;
			$total_pallets = 0;

			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
			while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($short_term_data_row['THE_SUM'] > 0 || $short_term_data_row['THE_SUM'] < 0){
					$total_cases += $short_term_data_row['THE_SUM'];
					$total_pallets++;
				}
			}
/*
			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vout2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_pallets = $short_term_data_row['THE_COUNT'];
			$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/

			fresh_screen("WALMART FRUIT\nVoid Outbound\nEnter X to exit.");
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

	while($continue_function){ // in case they want to move to next one 
		// get and validate cust #
		$cust = "";
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nVoid Out-ORDER\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "VoidOUTORDER")){
				fresh_screen("WALMART FRUIT\nVoid-Out Order\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VoutO1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VoutO1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nVoid Out-ORDER\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}		
		}

		// get pallets and cases on order, including partials
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(DISTINCT PALLET_ID) THE_PALLETS FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_cases = $short_term_data_row['THE_SUM'];
		$total_pallets = $short_term_data_row['THE_PALLETS'];

		fresh_screen("WALMART FRUIT\nVoid Out-ORDER\nEnter X to exit.");
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
				$sql = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO4a)");
				$ora_success = ora_exec($select_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VoutO4b)");
				while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(VoutO5a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(VoutO5b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;

					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$select_row['QTY_CHANGE']." WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO6b)");

					// as voids are universal, we don't need to "check" for a walmart-split pallet first, we can just update any that are.
					$sql = "UPDATE WDI_SPLIT_PALLETS WSP 
							SET QTY_IN_HOUSE = QTY_IN_HOUSE + 
								(SELECT QTY_CHANGE FROM CARGO_ACTIVITY CA WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND CA.BATCH_ID = WSP.SPLIT_PALLET_ID AND WSP.SPLIT_PALLET_ID = '".$select_row['BATCH_ID']."')
							WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."'
							AND SPLIT_PALLET_ID IN
								(SELECT BATCH_ID
								FROM CARGO_ACTIVITY
								WHERE ORDER_NUM = '".$order_num."'
								AND SERVICE_CODE = '6'
								AND ACTIVITY_DESCRIPTION IS NULL
								AND BATCH_ID IS NOT NULL)";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO6a-a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO6a-b)");

					$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'VOID' WHERE PALLET_ID = '".$select_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$select_row['ARRIVAL_NUM']."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND ACTIVITY_NUM = '".$select_row['ACTIVITY_NUM']."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO7a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO7b)");

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
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(VoutO8b)");

				}
				ora_commit($rf_conn);
				fresh_screen("WALMART FRUIT\nVoid Out-ORDER\nEnter X to exit.");
				echo $order_num."\nVOIDED\n\n";
				fscanf(STDIN, "%s\n", $junk);

			break;
		}
	}
}

function VoidINTruck($CID){
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

		$cust = "";
		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "VoidINTruck")){
				fresh_screen("WALMART FRUIT\nVoid-In Truck\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Vi1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(Vit1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		$order_num = "";
		// get Order
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);
		}

		fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){

			$continue_pallet = true;

			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vit2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vit2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '8' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vit3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vit3b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// check if pallet already has non-truck-in activity
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."' AND SERVICE_CODE NOT IN ('1', '8')";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(Vit4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(Vit4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] > 0){
					fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.\n\nBC: ".$Barcode."\nORD#: ".$order_num."\n**PALLET ALREADY\nHAS ACTIVITY\nAGAINST IT.\nCANNOT VOID\nINBOUND RECEIPT.**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// get  scanned pallet info
			if($continue_pallet){
				$sql = "SELECT NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, QTY_CHANGE, WAREHOUSE_LOCATION, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE CT.PALLET_ID = '".$Barcode."' AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '8' AND CA.ORDER_NUM = '".$order_num."' AND CT.RECEIVER_ID = '".$cust."' AND CA.ARRIVAL_NUM = '".$order_num."' AND CA.ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vit5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Vit5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_to_void = $short_term_data_row['QTY_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$item_type = $short_term_data_row['THE_ITEM'];
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}

			// determine if the cargo was a Split-Pallet
			$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
	//		echo $sql."\n";
	//		fscanf(STDIN, "%s\n", $junk);
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nActivity\n(Vit5a-a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nActivity\n(Vit5a-b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$walmart_split_count = $short_term_data_row['THE_COUNT'];
			if($walmart_split_count > 0){
				fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT-PALLET\nPLEASE CONTACT\nOFFICE TO VOID\nINBOUND RECEIPT.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// AT THIS POINT, we have all the info we need to void.

			// display data, request confirmation
			if($continue_pallet){
				$sub_choice = "";
				while($sub_choice != "N" && $sub_choice != "Y"){
					system("clear");
					echo $Barcode."\n";
					echo "In: ".$date_ship."\n";
					echo "Cust: ".$custname."\n";
					echo "Comm: ".$commname."\n";
					echo "WMitem#: ".$item_type."\n";
					echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
					echo "Loc: ".$loc."\n";
					echo "QTY RCVD: ".$qty_to_void."\n\n";
					echo "Void?  Y/N\n";
					$sub_choice = "";
					fscanf(STDIN, "%s\n", $sub_choice);
					$sub_choice = strtoupper($sub_choice);
					if($sub_choice == "N"){
						$continue_pallet = false;
					}
				}
			}

			// if void was confirmed, lets do it!
			if($continue_pallet){
				$sql = "DELETE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vit6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vit6b)");

				$sql = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$order_num."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('1', '8')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vit7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(Vit7b)");

				ora_commit($rf_conn);
				fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.");
				echo $Barcode."\n VOIDED\n\n";
				fscanf(STDIN, "%s\n", $junk);
			
			}

			fresh_screen("WALMART FRUIT\nVoid Truck-In\nEnter X to exit.");
			echo "Cust: ".$cust."\n";
			echo "Ord#: ".$order_num."\n";
			echo "Barcode:\n";
			$Barcode = "";
			fscanf(STDIN, "%s\n", $Barcode);
			$Barcode = strtoupper(strip_to_alphanumeric($Barcode));
		}
	}
}

function VoidINTruckORDER($CID){
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

		$cust = "";
		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nVoid Truck-In ORDER\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "VoidINTruckORDER")){
				fresh_screen("WALMART FRUIT\nVoid Truck-In ORDER\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VitO1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(VitO1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cust = "Invalid";
				}
			}
		}

		// get Order
		$order_num = "";
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nVoid Truck-In ORDER\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '8'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VitO2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VitO2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}
			
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE SERVICE_CODE != '8' AND (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VitO3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VitO3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] > 0){
				echo "Some Pallets from\nThis truck already\nHave activity.\nCannot Void Inbound\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}

			// DO NOT allow truck-in voids of spit pallets
			$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS
					WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN
						(SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM
						FROM CARGO_ACTIVITY
						WHERE ORDER_NUM = '".$order_num."'
						AND ARRIVAL_NUM = '".$order_num."'
						AND CUSTOMER_ID = '".$cust."'
						AND ACTIVITY_NUM = '1')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VitO3a-a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(VitO3a-b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] > 0){
				fresh_screen("WALMART FRUIT\nVoid Truck-In ORDER\nEnter X to exit.\n\nORD: ".$order_num."\n**CONTAINS WALMART\nSPLIT-PALLETS\nPLEASE CONTACT\nOFFICE TO VOID\nINBOUND RECEIPT.**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}

		}

		fresh_screen("WALMART FRUIT\nVoid Truck-In ORDER\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";

		// get pallets and cases on order, including partials
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(DISTINCT PALLET_ID) THE_PALLETS FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '8'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VitO4a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(VitO4b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_cases = $short_term_data_row['THE_SUM'];
		$total_pallets = $short_term_data_row['THE_PALLETS'];

		fresh_screen("WALMART FRUIT\nVoid Out-ORDER\nEnter X to exit.");
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
				$sql = "DELETE FROM CARGO_TRACKING WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(VitO5a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(VitO5b)");

				$sql = "DELETE FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('8')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(VitO6a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to \nUpdate Pallet\n(VitO6b)");

				ora_commit($rf_conn);
				fresh_screen("WALMART FRUIT\nVoid Truck-In ORDER");
				echo $order_num."\n VOIDED\n\n";
				fscanf(STDIN, "%s\n", $junk);
			break;
		}
	}
}


function Returns($CID, $type){
	// $type is either "dock" or "full"

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
			fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Returns")){
				fresh_screen("WALMART FRUIT\nreturns\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
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

		$order_num = "";
		// get Order
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(Ret12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(Ret12b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Does Not\nExist For\nThis Customer\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}

			$sql = "SELECT COUNT(*) THE_COUNT FROM WM_EXPECTED_REPACK_ORDER WHERE ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(Ret12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(Ret12b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] > 0){
				echo "Order Is\nA WALMART Repack\nPlease Use\nRepack-Return\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}
		}

		$total_cases = 0;
		$total_pallets = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
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
/*
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallets = $short_term_data_row['THE_COUNT'];
		$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/
		fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
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
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// get  scanned pallet info
			if($continue_pallet){
				$sql = "SELECT NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION, CA.BATCH_ID, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE CT.PALLET_ID = '".$Barcode."' AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CT.RECEIVER_ID = '".$cust."' AND CA.ACTIVITY_DESCRIPTION IS NULL GROUP BY NVL(VESSEL_NAME, CT.ARRIVAL_NUM), NVL(BOL, 'UNKNOWN'), CT.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION, CA.BATCH_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_ship = $short_term_data_row['THE_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$item_type = $short_term_data_row['THE_ITEM'];
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}

			// find out if this is a split pallet... (which will get blocked)
			if($continue_pallet){

				$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
		//		echo $sql."\n";
		//		fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(Ret4a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(Ret4a-b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$walmart_split_count = $short_term_data_row['THE_COUNT'];

				if($walmart_split_count > 0){
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT-PALLET\nEACH SPLIT WILL\nNEED TO BE RETURNED\nSEPARATELY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
				}
			}


			// AT THIS POINT, we have all the info we need to void.
			if($walmart_split_count == 0){ // this is a run-of-the-mill pallet.  NO SPLITS.

				// display data, request confirmation
				if($continue_pallet){
					$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

					$sub_choice = "there is no spoon";
					$qty_ret = "no";
					$qty_dmg_ret = "no";
					while($continue_pallet && !is_numeric($qty_ret) && !is_numeric($qty_dmg_ret)){
						system("clear");
						echo $Barcode."\n";
						echo "Vesl: ".$vesname."\n";
						echo "Out: ".$date_ship."\n";
						echo "Cust: ".$custname."\n";
						echo "Comm: ".$commname."\n";
						echo "WMitem#: ".$item_type."\n";
						echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
						echo "Loc: ".$loc."\n";
						echo "QTY SHIPPED: ".$qty_ship."\n";
						echo "QTY Returned:\n";
						$sub_choice = "";
						fscanf(STDIN, "%s\n", $sub_choice);
						$sub_choice = strtoupper($sub_choice);

						if($sub_choice == "X"){
							$continue_pallet = false;
						} elseif($sub_choice <= 0){
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nZero or Less", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$qty_ret = "A";
						} elseif($sub_choice > $qty_ship){
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nMore than\nWas Shipped", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$qty_ret = "A";
						} /*elseif($walmart_split_count > 0 && $sub_choice != $qty_ship){
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nSplit Pallets\nCannot Return\nPartial QTY\nPer Split", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$qty_ret = "A";
						} */ else {
							$qty_ret = $sub_choice;
						}

						if($continue_pallet && $qty_ret != "A"){ // in case they cancelled from the previous choice
							echo "DMG Returned:\n";
							$sub_choice = "";
							fscanf(STDIN, "%s\n", $sub_choice);
							$sub_choice = strtoupper($sub_choice);

							if($sub_choice == "X"){
								$continue_pallet = false;
							} elseif($sub_choice < 0){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nLess than Zero", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_dmg_ret = "A";
							} elseif($sub_choice > $qty_ship){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nMore than\nWas Shipped", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_dmg_ret = "A";
							} elseif($sub_choice == ""){
								$qty_dmg_ret = 0;
							} else {
								$qty_dmg_ret = $sub_choice;
							}
						}
					}
				}

				if($continue_pallet){
					if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
						fresh_screen("WALMART FRUIT\nReturns\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}

				// if we are at this point, they have entered valid data, and not cancelled the return.  lets do it
				if($continue_pallet){
					$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'RETURN' WHERE SERVICE_CODE = '6' AND ORDER_NUM = '".$order_num."' AND ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$cust."'";
	/*				if($split_pallet_id != ""){
						$sql .= " AND BATCH_ID = '".$split_pallet_id."'";
					} else {
						$sql .= " AND BATCH_ID IS NULL";
					}*/
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6b)");
/*
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(Ret7a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(Ret7b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
					// add CA record of return
					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".($act_num + 1)."',
					'".$service_code."',
					'-".$qty_ret."',
					'".$emp_no."',
					'DMG on return: ".$qty_dmg_ret."',
					'".$order_num."',
					'".$cust."',
					SYSDATE,
					'".$Barcode."',
					'".$Arrival."',
					'".($qty_in_house + $qty_ret)."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8b)");

					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_ret.", QTY_DAMAGED = QTY_DAMAGED + ".$qty_dmg_ret." WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9b)");
	/*
					if($walmart_split_count > 0){
						$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_ret.", QTY_DAMAGED = QTY_DAMAGED + ".$qty_dmg_ret." WHERE SPLIT_PALLET_ID '".$split_pallet_id."'";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-b)");
					}
	*/
					if($type == "full"){
						$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = GREATEST(BILLING_STORAGE_DATE, TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')) 
								WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."' AND BILLING_STORAGE_DATE IS NOT NULL";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10b)");
					}

					ora_commit($rf_conn);
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
					echo $qty_ret." cartons\n";
					echo $Barcode."\nRETURNED FROM ORDER\n\n";
					fscanf(STDIN, "%s\n", $junk);
				}

			} else { // WALMART SPLIT PALLET!

				$pallet_array = array(); // list of split-pallet IDs
				$qty_ret_array = array(); // qty for each split, array index matches $pallet_array
				$qty_dmg_array = array(); // damaged aray for each split return
				$split_max_index = 0;
				$splits_to_ship = 0;

				// populate the array of split-pallets.
				$sql = "SELECT SPLIT_PALLET_ID FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' ORDER BY SPLIT_PALLET_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT25a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT25a-b)");
				while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_array[$split_max_index] = $short_term_data_row['SPLIT_PALLET_ID'];
					$split_max_index++;
				}

				// with the array populated, we loop through the pallet.
				for($this_split_index = 0; $this_split_index < $split_max_index; $this_split_index++){
					$continue_split = true;

					$sql = "SELECT NVL(VESSEL_NAME, WSP.ARRIVAL_NUM) THE_VES, WSP.ARRIVAL_NUM, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION, CA.BATCH_ID, NVL(BOL, 'UNKNOWN') THE_ITEM FROM WDI_SPLIT_PALLETS WSP, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE WSP.SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."' AND WSP.PALLET_ID = CA.PALLET_ID AND WSP.RECEIVER_ID = CA.CUSTOMER_ID AND WSP.ARRIVAL_NUM = CA.ARRIVAL_NUM AND WSP.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND WSP.RECEIVER_ID = CUSP.CUSTOMER_ID AND WSP.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CA.ACTIVITY_DESCRIPTION IS NULL AND WSP.SPLIT_PALLET_ID = CA.BATCH_ID GROUP BY NVL(VESSEL_NAME, WSP.ARRIVAL_NUM), NVL(BOL, 'UNKNOWN'), WSP.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION, CA.BATCH_ID";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a-a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a-b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$Arrival = $short_term_data_row['ARRIVAL_NUM'];
					$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
					$qty_ship = $short_term_data_row['THE_CHANGE'];
					$date_ship = $short_term_data_row['THE_DATE'];
					$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
					$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
					$item_type = $short_term_data_row['THE_ITEM'];
					$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
					$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
					$qty_rec = $short_term_data_row['QTY_RECEIVED'];
					$qty_dmg = $short_term_data_row['QTY_DAMAGED'];

					if($continue_split){
						$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

						$sub_choice = "there is no spoon";
						$qty_ret_array[$this_split_index] = "no";
						$qty_dmg_array[$this_split_index] = "no";
						while($continue_pallet && $continue_split && !is_numeric($qty_ret_array[$this_split_index]) && !is_numeric($qty_dmg_array[$this_split_index])){
							system("clear");
							echo $Barcode."\n";
							echo "Vesl: ".$vesname."\n";
							echo "Out: ".$date_ship."\n";
							echo "Cust: ".$custname."\n";
							echo "Comm: ".$commname."\n";
							echo "WMitem#: ".$item_type."\n";
							echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
							echo "Loc: ".$loc."\n";
							echo "QTY SHIPPED: ".$qty_ship."\n";
							echo "QTY Returned:\n";
							echo "S=Skip This Split\nX=Exit\nEnter QTY Returned\n";
							$sub_choice = "";
							fscanf(STDIN, "%s\n", $sub_choice);
							$sub_choice = strtoupper($sub_choice);

							if($sub_choice == "X"){
								$continue_pallet = false;
							} elseif($sub_choice == "S"){
								$continue_split = false;
								$qty_ret_array[$this_split_index] = "A";
							} elseif($sub_choice <= 0){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nZero or Less", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_ret_array[$this_split_index] = "A";
							} elseif($sub_choice > $qty_ship){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nMore than\nWas Shipped", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_ret_array[$this_split_index] = "A";
							} elseif($sub_choice != $qty_ship){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nSplit Pallets\nCannot Return\nPartial QTY\nPer Split", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_ret_array[$this_split_index] = "A";
							}  else {
								$qty_ret_array[$this_split_index] = $sub_choice;
							}

							if($continue_pallet && $continue_split && $qty_ret_array[$this_split_index] != "A"){ 
								// in case they cancelled from the previous choice
								echo "DMG Returned:\n";
								$sub_choice = "";
								fscanf(STDIN, "%s\n", $sub_choice);
								$sub_choice = strtoupper($sub_choice);

								if($sub_choice == "X"){
									$continue_pallet = false;
								} elseif($sub_choice == "S"){
									$continue_split = false;
									$qty_ret_array[$this_split_index] = "A";
								} elseif($sub_choice < 0){
									fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nLess than Zero", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$qty_dmg_array[$this_split_index] = "A";
								} elseif($sub_choice > $qty_ship){
									fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nMore than\nWas Shipped", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$qty_dmg_array[$this_split_index] = "A";
								} elseif($sub_choice == ""){
									$qty_dmg_array[$this_split_index] = 0;
								} else {
									$qty_dmg_array[$this_split_index] = $sub_choice;
								}
							}
						}
					}
				}

				if($continue_pallet){
					if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
						fresh_screen("WALMART FRUIT\nReturns\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}

				if($continue_pallet){
					for($this_split_index = 0; $this_split_index < $split_max_index; $this_split_index++){
						if($qty_ret_array[$this_split_index] != "A" && $qty_ret_array[$this_split_index] > 0){

							$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'RETURN' WHERE SERVICE_CODE = '6' AND ORDER_NUM = '".$order_num."' AND ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$cust."' AND BATCH_ID = '".$pallet_array[$this_split_index]."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a-b)");

							$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nPallet Info\n(Ret7a-a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nPallet Info\n(Ret7a-b)");
							ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							$act_num = $short_term_data_row['THE_MAX'] + 1;

							// add CA record of return
							$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES
							('".$act_num."',
							'".$service_code."',
							'-".$qty_ret_array[$this_split_index]."',
							'".$emp_no."',
							'DMG on return: ".$qty_dmg_array[$this_split_index]."',
							'".$order_num."',
							'".$cust."',
							SYSDATE,
							'".$Barcode."',
							'".$Arrival."',
							'0',
							'".$pallet_array[$this_split_index]."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a-b)");

							$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".(0 + $qty_ret_array[$this_split_index]).", QTY_DAMAGED = QTY_DAMAGED + ".(0 + $qty_dmg_array[$this_split_index])." WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-b)");

							$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".(0 + $qty_ret_array[$this_split_index]).", QTY_DAMAGED = QTY_DAMAGED + ".(0 + $qty_dmg_array[$this_split_index])." WHERE SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret11a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret11a-b)");

							if($type == "full"){
								$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = GREATEST(BILLING_STORAGE_DATE, TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')) 
										WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."' AND BILLING_STORAGE_DATE IS NOT NULL";
								$ora_success = ora_parse($modify_cursor, $sql);
								database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10a)");
								$ora_success = ora_exec($modify_cursor, $sql);
								database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10b)");
							}

							ora_commit($rf_conn);
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
							echo array_sum($qty_ret_array)." cartons\n";
							echo $Barcode."\nRETURNED FROM ORDER\n\n";
							fscanf(STDIN, "%s\n", $junk);
						}
					}
				}
			}


			$total_cases = 0;
			$total_pallets = 0;


			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13') GROUP BY PALLET_ID";
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
/*
			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_pallets = $short_term_data_row['THE_COUNT'];
			$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/
			fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
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

function Return_Repack($CID){
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
/*
	if($type == "full"){
		$service_code = "7";
	} elseif($type == "dock"){
		$service_code = "13";
	} else {
		return;
	}
*/
	$type = "REPACK";
	$service_code = 20;

	$continue_function = true;

	while($continue_function){ // in case they finish order and want to move to next one 

		$cust = "";
		// get and validate cust #
		while($cust == "" || $cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
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
			} elseif(!validate_customer_to_scannertype($cust, $scanner_type, "Returns")){
				fresh_screen("WALMART FRUIT\nreturns\nEnter X to exit.\n\nCust: ".$cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
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

		$order_num = "";
		// get Order
		while($order_num == ""){
			fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
			echo "Order #:\n";
			fscanf(STDIN, "%s\n", $order_num);
			$order_num = strtoupper($order_num);
			if($order_num == "X"){
				return;
			}
			$order_num = remove_badchars($order_num);

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

			$sql = "SELECT COUNT(*) THE_COUNT FROM WM_EXPECTED_REPACK_ORDER WHERE ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(Ret12a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nOrder Info\n(Ret12b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
				echo "Order Is Not\nA WALMART Repack\nPlease Use\nMain Return Options\n(Press Enter)\n";
				fscanf(STDIN, "%s\n", $junk);
				$order_num = "";
			}
		}

		$total_cases = 0;
		$total_pallets = 0;

		// get pallets and cases on order, including partials
		$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, 
				PALLET_ID 
				FROM CARGO_ACTIVITY 
				WHERE CUSTOMER_ID = '".$cust."' 
				AND ORDER_NUM = '".$order_num."' 
				AND SERVICE_CODE IN ('6', '7', '12', '13', '20') 
				GROUP BY PALLET_ID";
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
/*
		$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_pallets = $short_term_data_row['THE_COUNT'];
		$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/
		fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
		echo "Cust: ".$cust."\n";
		echo "Ord#: ".$order_num."\n";
		echo $total_cases." in ".$total_pallets." plts\n\n";
		echo "Barcode:\n";
		$Barcode = "";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

		while($Barcode != "X"){

			$continue_pallet = true;
			$Arrival = "";
			$Commodity = "";

			if(substr($Barcode, -1) != "R"){
				fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEND WITH R.\nCANNOT REPACK-\nRETURN**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// put the original barcode into the right variable.
			$Barcode_with_append = $Barcode;
			$Barcode = substr($Barcode, 0, -1);


			// check if pallet exists at all
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret3b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue_pallet = false;
			}

			// check if pallet is on this order
			if($continue_pallet){
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret4a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret4b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_data_row['THE_COUNT'] == 0){
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED ORDER**", "bad");
					fscanf(STDIN, "%s\n", $junk);
					$continue_pallet = false;
				}
			}

			// get scanned pallet info
			if($continue_pallet){
				$sql = "SELECT NVL(VESSEL_NAME, CT.ARRIVAL_NUM) THE_VES, CT.ARRIVAL_NUM, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION, CA.BATCH_ID, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE CT.PALLET_ID = '".$Barcode."' AND CT.PALLET_ID = CA.PALLET_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CT.RECEIVER_ID = '".$cust."' AND CA.ACTIVITY_DESCRIPTION IS NULL GROUP BY NVL(VESSEL_NAME, CT.ARRIVAL_NUM), NVL(BOL, 'UNKNOWN'), CT.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION, CA.BATCH_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
				$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
				$qty_ship = $short_term_data_row['THE_CHANGE'];
				$date_ship = $short_term_data_row['THE_DATE'];
				$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
				$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
				$item_type = $short_term_data_row['THE_ITEM'];
				$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
				$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
				$qty_rec = $short_term_data_row['QTY_RECEIVED'];
				$qty_dmg = $short_term_data_row['QTY_DAMAGED'];
			}

			// find out if this is a split pallet...
			if($continue_pallet){

				$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
		//		echo $sql."\n";
		//		fscanf(STDIN, "%s\n", $junk);
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(Ret4a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nActivity\n(Ret4a-b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$walmart_split_count = $short_term_data_row['THE_COUNT'];

				if($walmart_split_count > 0){
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\nBC: ".$Barcode."\n**IS A WALMART\nSPLIT-PALLET\nEACH SPLIT WILL\nNEED TO BE RETURNED\nSEPARATELY**", "bad");
					fscanf(STDIN, "%s\n", $junk);
				}
			}


			// AT THIS POINT, we have all the info we need to void.
			if($walmart_split_count == 0){ // this is a run-of-the-mill pallet.  NO SPLITS.

				// display data, request confirmation
				if($continue_pallet){
					$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

					$sub_choice = "there is no spoon";
					$qty_ret = "no";
					$qty_dmg_ret = "no";
					while($continue_pallet && !is_numeric($qty_ret) && !is_numeric($qty_dmg_ret)){
						system("clear");
						echo $Barcode."\n";
						echo "Vesl: ".$vesname."\n";
						echo "Out: ".$date_ship."\n";
						echo "Cust: ".$custname."\n";
						echo "Comm: ".$commname."\n";
						echo "WMitem#: ".$item_type."\n";
						echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
						echo "Loc: ".$loc."\n";
						echo "QTY SHIPPED: ".$qty_ship."\n";
						echo "QTY Returned:\n";
						$sub_choice = "";
						fscanf(STDIN, "%s\n", $sub_choice);
						$sub_choice = strtoupper($sub_choice);

						if($sub_choice == "X"){
							$continue_pallet = false;
						} elseif($sub_choice <= 0){
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nZero or Less", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$qty_ret = "A";
						} elseif($sub_choice > $qty_ship){
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nMore than\nWas Shipped", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$qty_ret = "A";
						} /*elseif($walmart_split_count > 0 && $sub_choice != $qty_ship){
							fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nSplit Pallets\nCannot Return\nPartial QTY\nPer Split", "bad");
							fscanf(STDIN, "%s\n", $junk);
							$qty_ret = "A";
						} */ else {
							$qty_ret = $sub_choice;
						}

						if($continue_pallet && $qty_ret != "A"){ // in case they cancelled from the previous choice
							echo "DMG Returned:\n";
							$sub_choice = "";
							fscanf(STDIN, "%s\n", $sub_choice);
							$sub_choice = strtoupper($sub_choice);

							if($sub_choice == "X"){
								$continue_pallet = false;
							} elseif($sub_choice < 0){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nLess than Zero", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_dmg_ret = "A";
							} elseif($sub_choice > $qty_ship){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nMore than\nWas Shipped", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_dmg_ret = "A";
							} elseif($sub_choice == ""){
								$qty_dmg_ret = 0;
							} else {
								$qty_dmg_ret = $sub_choice;
							}
						}
					}
				}

				if($continue_pallet){
					if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
						fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}


				// if we are at this point, they have entered valid data, and not cancelled the return.  lets do it
				if($continue_pallet){
					$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'RETURN' WHERE SERVICE_CODE = '6' AND ORDER_NUM = '".$order_num."' AND ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$cust."'";
	/*				if($split_pallet_id != ""){
						$sql .= " AND BATCH_ID = '".$split_pallet_id."'";
					} else {
						$sql .= " AND BATCH_ID IS NULL";
					}*/
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6b)");
/*
					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(Ret7a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(Ret7b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;
*/
					// add CA record of return
					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".($act_num + 1)."',
					'".$service_code."',
					'-".$qty_ret."',
					'".$emp_no."',
					'DMG on return: ".$qty_dmg_ret."',
					'".$order_num."',
					'".$cust."',
					SYSDATE,
					'".$Barcode."',
					'".$Arrival."',
					'".($qty_in_house + $qty_ret)."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8b)");

					// auto-hold pallet
					$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".($act_num+2)."',
					'18',
					'0',
					'".$emp_no."',
					'HOLD FROM REPACK',
					'".$order_num."',
					'".$cust."',
					SYSDATE,
					'".$Barcode."',
					'".$Arrival."',
					'".($qty_in_house + $qty_ret)."')";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8b)");

					$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_ret.", QTY_DAMAGED = QTY_DAMAGED + ".$qty_dmg_ret.", CARGO_STATUS = 'HOLD' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9b)");
	/*
					if($walmart_split_count > 0){
						$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_ret.", QTY_DAMAGED = QTY_DAMAGED + ".$qty_dmg_ret." WHERE SPLIT_PALLET_ID '".$split_pallet_id."'";
						$ora_success = ora_parse($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-a)");
						$ora_success = ora_exec($modify_cursor, $sql);
						database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-b)");
					}
	*/
//					if($type == "full"){
					$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = SYSDATE + (1/1440) WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10b)");
//					}

					RepackConvertBarcode($Barcode, $Barcode_with_append, $Arrival, $modify_cursor);

					ora_commit($rf_conn);
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
					echo $qty_ret." cartons\n";
					echo $Barcode."\nRETURNED FROM ORDER\n\n";
					fscanf(STDIN, "%s\n", $junk);
				}

			} else { // WALMART SPLIT PALLET!

				$pallet_array = array(); // list of split-pallet IDs
				$qty_ret_array = array(); // qty for each split, array index matches $pallet_array
				$qty_dmg_array = array(); // damaged aray for each split return
				$split_max_index = 0;
				$splits_to_ship = 0;

				// populate the array of split-pallets.
				$sql = "SELECT SPLIT_PALLET_ID FROM WDI_SPLIT_PALLETS WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."' ORDER BY SPLIT_PALLET_ID";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT25a-a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(LT25a-b)");
				while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_array[$split_max_index] = $short_term_data_row['SPLIT_PALLET_ID'];
					$split_max_index++;
				}

				// with the array populated, we loop through the pallet.
				for($this_split_index = 0; $this_split_index < $split_max_index; $this_split_index++){
					$continue_split = true;

					$sql = "SELECT NVL(VESSEL_NAME, WSP.ARRIVAL_NUM) THE_VES, WSP.ARRIVAL_NUM, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_DATE, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, SUM(QTY_CHANGE) THE_CHANGE, WAREHOUSE_LOCATION, CA.BATCH_ID, NVL(BOL, 'UNKNOWN') THE_ITEM FROM WDI_SPLIT_PALLETS WSP, CARGO_ACTIVITY CA, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP WHERE WSP.SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."' AND WSP.PALLET_ID = CA.PALLET_ID AND WSP.RECEIVER_ID = CA.CUSTOMER_ID AND WSP.ARRIVAL_NUM = CA.ARRIVAL_NUM AND WSP.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND WSP.RECEIVER_ID = CUSP.CUSTOMER_ID AND WSP.COMMODITY_CODE = COMP.COMMODITY_CODE AND CA.SERVICE_CODE = '6' AND CA.ORDER_NUM = '".$order_num."' AND CA.ACTIVITY_DESCRIPTION IS NULL AND WSP.SPLIT_PALLET_ID = CA.BATCH_ID GROUP BY NVL(VESSEL_NAME, WSP.ARRIVAL_NUM), NVL(BOL, 'UNKNOWN'), WSP.ARRIVAL_NUM, CUSTOMER_NAME, COMMODITY_NAME, QTY_RECEIVED, QTY_DAMAGED, QTY_IN_HOUSE, WAREHOUSE_LOCATION, CA.BATCH_ID";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a-a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret5a-b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$Arrival = $short_term_data_row['ARRIVAL_NUM'];
					$qty_in_house = $short_term_data_row['QTY_IN_HOUSE'];
					$qty_ship = $short_term_data_row['THE_CHANGE'];
					$date_ship = $short_term_data_row['THE_DATE'];
					$custname = substr($short_term_data_row['CUSTOMER_NAME'], 0, 12);
					$commname = substr($short_term_data_row['COMMODITY_NAME'], 0, 12);
					$item_type = $short_term_data_row['THE_ITEM'];
					$vesname = substr($short_term_data_row['THE_VES'], 0, 12);
					$loc = substr($short_term_data_row['WAREHOUSE_LOCATION'], 0, 15);
					$qty_rec = $short_term_data_row['QTY_RECEIVED'];
					$qty_dmg = $short_term_data_row['QTY_DAMAGED'];

					if($continue_split){
						$act_num = get_max_activity_num($cust, $Barcode, $Arrival);

						$sub_choice = "there is no spoon";
						$qty_ret_array[$this_split_index] = "no";
						$qty_dmg_array[$this_split_index] = "no";
						while($continue_pallet && $continue_split && !is_numeric($qty_ret_array[$this_split_index]) && !is_numeric($qty_dmg_array[$this_split_index])){
							system("clear");
							echo $Barcode."\n";
							echo "Vesl: ".$vesname."\n";
							echo "Out: ".$date_ship."\n";
							echo "Cust: ".$custname."\n";
							echo "Comm: ".$commname."\n";
							echo "WMitem#: ".$item_type."\n";
							echo "Rcvd:".$qty_rec." Dm:".$qty_dmg." IH:".$qty_in_house."\n";
							echo "Loc: ".$loc."\n";
							echo "QTY SHIPPED: ".$qty_ship."\n";
							echo "QTY Returned:\n";
							echo "S=Skip This Split\nX=Exit\nEnter QTY Returned\n";
							$sub_choice = "";
							fscanf(STDIN, "%s\n", $sub_choice);
							$sub_choice = strtoupper($sub_choice);

							if($sub_choice == "X"){
								$continue_pallet = false;
							} elseif($sub_choice == "S"){
								$continue_split = false;
								$qty_ret_array[$this_split_index] = "A";
							} elseif($sub_choice <= 0){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nZero or Less", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_ret_array[$this_split_index] = "A";
							} elseif($sub_choice > $qty_ship){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot return\nMore than\nWas Shipped", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_ret_array[$this_split_index] = "A";
						/*	} elseif($sub_choice != $qty_ship){
								fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nSplit Pallets\nCannot Return\nPartial QTY\nPer Split", "bad");
								fscanf(STDIN, "%s\n", $junk);
								$qty_ret_array[$this_split_index] = "A";
						*/	} else {
								$qty_ret_array[$this_split_index] = $sub_choice;
							}

							if($continue_pallet && $continue_split && $qty_ret_array[$this_split_index] != "A"){ 
								// in case they cancelled from the previous choice
								echo "DMG Returned:\n";
								$sub_choice = "";
								fscanf(STDIN, "%s\n", $sub_choice);
								$sub_choice = strtoupper($sub_choice);

								if($sub_choice == "X"){
									$continue_pallet = false;
								} elseif($sub_choice == "S"){
									$continue_split = false;
									$qty_ret_array[$this_split_index] = "A";
								} elseif($sub_choice < 0){
									fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nLess than Zero", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$qty_dmg_array[$this_split_index] = "A";
								} elseif($sub_choice > $qty_ship){
									fresh_screen("WALMART FRUIT\nReturns (".$type.")\n\nCannot Damage\nMore than\nWas Shipped", "bad");
									fscanf(STDIN, "%s\n", $junk);
									$qty_dmg_array[$this_split_index] = "A";
								} elseif($sub_choice == ""){
									$qty_dmg_array[$this_split_index] = 0;
								} else {
									$qty_dmg_array[$this_split_index] = $sub_choice;
								}
							}
						}
					}
				}

				if($continue_pallet){
					if(!is_max_activity_num($act_num, $cust, $Barcode, $Arrival)){
						fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.\n\n**ANOTHER SCANNER\nHAS MADE CHANGES\nTO THIS PALLET\nBEFORE THIS SCANNER\nCOULD CONFIRM.\nCANCELLING\nTRANSACTION.\nCONTACT SUPERVISOR\nIF YOU HAVE ANY\nQUESTIONS.\nPRESS ENTER TO\nCONTINUE.**", "bad");
						fscanf(STDIN, "%s\n", $junk);
						$continue_pallet = false;
					}
				}

				if($continue_pallet){
					for($this_split_index = 0; $this_split_index < $split_max_index; $this_split_index++){
						if($qty_ret_array[$this_split_index] != "A" && $qty_ret_array[$this_split_index] > 0){

							$sql = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = 'RETURN' WHERE SERVICE_CODE = '6' AND ORDER_NUM = '".$order_num."' AND ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND CUSTOMER_ID = '".$cust."' AND BATCH_ID = '".$pallet_array[$this_split_index]."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret6a-b)");

							$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$Arrival."'";
							$ora_success = ora_parse($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nPallet Info\n(Ret7a-a)");
							$ora_success = ora_exec($short_term_data_cursor, $sql);
							database_check($ora_success, "Unable to get\nPallet Info\n(Ret7a-b)");
							ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							$act_num = $short_term_data_row['THE_MAX'] + 1;

							// add CA record of return
							$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES
							('".$act_num."',
							'".$service_code."',
							'-".$qty_ret_array[$this_split_index]."',
							'".$emp_no."',
							'DMG on return: ".$qty_dmg_array[$this_split_index]."',
							'".$order_num."',
							'".$cust."',
							SYSDATE,
							'".$Barcode."',
							'".$Arrival."',
							'0',
							'".$pallet_array[$this_split_index]."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a-b)");

							// auto-hold pallet
							$sql = "INSERT INTO CARGO_ACTIVITY (ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES
							('".($act_num + 1)."',
							'18',
							'0',
							'".$emp_no."',
							'HOLD FROM REPACK',
							'".$order_num."',
							'".$cust."',
							SYSDATE,
							'".$Barcode."',
							'".$Arrival."',
							'0',
							'".$pallet_array[$this_split_index]."')";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret8a-b)");

							$sql = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".(0 + $qty_ret_array[$this_split_index]).", QTY_DAMAGED = QTY_DAMAGED + ".(0 + $qty_dmg_array[$this_split_index]).", CARGO_STATUS = 'HOLD' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret9a-b)");

							$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".(0 + $qty_ret_array[$this_split_index]).", QTY_DAMAGED = QTY_DAMAGED + ".(0 + $qty_dmg_array[$this_split_index]).", CARGO_STATUS = 'HOLD' WHERE SPLIT_PALLET_ID = '".$pallet_array[$this_split_index]."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret11a-a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret11a-b)");

//							if($type == "full"){
							$sql = "UPDATE CARGO_TRACKING SET BILLING_STORAGE_DATE = SYSDATE + (1/1440) WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$cust."'";
							$ora_success = ora_parse($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10a)");
							$ora_success = ora_exec($modify_cursor, $sql);
							database_check($ora_success, "Unable to \nUpdate Pallet\n(Ret10b)");
//							}

						}
					}
					RepackConvertBarcode($Barcode, $Barcode_with_append, $Arrival, $modify_cursor);

					ora_commit($rf_conn);
					fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
					echo array_sum($qty_ret_array)." cartons\n";
					echo $Barcode."\nRETURNED FROM ORDER\n\n";
					fscanf(STDIN, "%s\n", $junk);
				}
			}


			$total_cases = 0;
			$total_pallets = 0;


			// get pallets and cases on order, including partials
			$sql = "SELECT NVL(SUM(DECODE(SERVICE_CODE, 12, (-1 * QTY_CHANGE), QTY_CHANGE)), 0) THE_SUM, PALLET_ID FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '".$cust."' AND ORDER_NUM = '".$order_num."' AND SERVICE_CODE IN ('6', '7', '12', '13', '20') GROUP BY PALLET_ID";
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
/*
			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM, COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(Ret2b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_pallets = $short_term_data_row['THE_COUNT'];
			$total_cases = (0 + $short_term_data_row['THE_SUM']);
*/
			fresh_screen("WALMART FRUIT\nReturns (".$type.")\nEnter X to exit.");
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

function NONTWIC($UID){

	global $rf_conn;
	global $scanner_type;

	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.");
	$Barcode = "";
	echo "Next NONTWIC:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = trim(strtoupper($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$sql = "SELECT COUNT(*) THE_COUNT FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nBC Info\n(SAB1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nBC Info\n(SAB1b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] > 0){
			// barcode already exists, update.
			$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET CURRENT_LOCATION = '' WHERE BARCODE = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB2a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB2b)");

			$sql = "INSERT INTO NON_TWIC_SCANS (BARCODE, SCAN_TIME, USER_ID) VALUES ('".$Barcode."', SYSDATE, '".$UID."')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB4a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB4b)");
		

			$sql = "SELECT NEW_STATUS FROM NON_TWIC_STATUS_CHANGES WHERE USER_LOGIN = '".$UID."' AND NEW_STATUS IS NOT NULL";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			if(!ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// do nothing
			} else {
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = '".$short_term_row['NEW_STATUS']."' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($modify_cursor, $sql);
				$ora_success = ora_exec($modify_cursor, $sql);
			}

/*			if($UID == "LANE4" || $UID == "DOLE"){
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = 'OFFPORT' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB3a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB3b)");
			} else {
				$sql = "UPDATE NON_TWIC_TRUCKER_DETAIL SET STATUS = 'INPORT' WHERE BARCODE = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB5a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Update\nBC Info\n(SAB5b)");
			}
*/
			ora_commit($rf_conn);

			$sql = "SELECT NVL(FIRST_NAME, 'NO FIRST NAME') THE_FIRST, NVL(LAST_NAME, 'NO LAST NAME') THE_LAST, NVL(TRAILER_NBR, 'NO LICENSE') THE_LICENSE FROM NON_TWIC_TRUCKER_DETAIL WHERE BARCODE = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot get\nBC Info\n(SAB6a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot get\nBC Info\n(SAB6b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.");
			echo "Barcode\n".$Barcode."\nScanned.\n";
			echo $short_term_data_row['THE_FIRST']."\n";
			echo $short_term_data_row['THE_LAST']."\n";
			echo $short_term_data_row['THE_LICENSE']."\n";
			$Barcode = "";
			echo "Next NONTWIC:";
			fscanf(STDIN, "%s\n", $Barcode);
			trim($Barcode = strtoupper($Barcode));
		
		} else {
			// barcode doesn't exist, alter user
			/*
			$sql = "INSERT INTO NON_TWIC_TRUCKER_DETAIL (BARCODE, CURRENT_LOCATION, STATUS) VALUES ('".$Barcode."', '', 'INPORT')";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB3a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Update\nBC Info\n(SAB3b)");
			*/
			fresh_screen("NONTWIC\nBarcode Scan\nEnter X to exit.\n\n***".$Barcode."\nNot In System.\nContact PoW\nAdmin building.***", "bad");
			fscanf(STDIN, "%s\n", $junk);
			echo "Barcode\n".$Barcode."\nScanned.\n";
			$Barcode = "";
			echo "Next NONTWIC:";
			fscanf(STDIN, "%s\n", $Barcode);
			trim($Barcode = strtoupper($Barcode));
		}

	}

}

function Cold_Storage($CID){
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
		fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.");
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

	fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.\n\nLR#: ".$Arrival."\n");
	$Barcode = "";
	echo "Barcode:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$continue = true;
		$Cust = "";
		$Commodity = "";
		$qty_in_house = 0;


/*		// check if pallet exists at all
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL8a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL8b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.\n\nLR#: ".$Arrival."\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}
*/
		// check if pallet is on this ship
		if($continue){
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL9a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL9b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.\n\nLR#: ".$Arrival."\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED LR#**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		if($continue){
			// no pallet, 1 pallet, or multiple pallets?
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL1b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				// pallet does not exist
				fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} elseif($short_term_data_row['THE_COUNT'] > 1){
				// multiple pallet
				$continue = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Commodity, $qty_in_house);
			} else {
				// single pallet, get info
				$sql = "SELECT CT.ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, VESSEL_NAME FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."' AND CT.ARRIVAL_NUM = '".$Arrival."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Cust = $short_term_data_row['RECEIVER_ID'];
				$vesname = $short_term_data_row['VESSEL_NAME'];
				$Commodity = $short_term_data_row['COMMODITY_CODE'];
			}
		}

		if(!validate_customer_to_scannertype($Cust, $scanner_type, "Cold_Storage")){
			fresh_screen("WALMART FRUIT\nCold Storage\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}

		if($continue){
			$sql = "SELECT WAREHOUSE_LOCATION, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL7b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
			$item_type = $short_term_data_row['THE_ITEM'];
		}

		while($continue){
			// do this if they did not "cancel" or "error" out of above
			fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.");
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "WMitem#: ".$item_type."\n";
			echo "CUST: ".$Cust."\n";
			echo "BC: ".$Barcode."\n\n";
			echo "Loc: ".$loc."\n";
			echo "\n1 - New Loc\n";
			echo "\"Enter\" to Accept\n";
			echo "\"X\" To Escape\n";

/*			$loc = "";
			fscanf(STDIN, "%[^[]]\n", $loc);
			$loc = trim(strtoupper($loc));
*/
			$Choice = "";
			fscanf(STDIN, "%s\n", $Choice);
			$Choice = strtoupper($Choice);

//			echo $Choice."\n";
//			fscanf(STDIN, "%s\n", $junk);


			switch($Choice){
				case 1:
					modify_unload($Barcode, $Arrival, $Cust, $Comm, "loc", $loc);
				break;

				case "X":
					fresh_screen("WALMART FRUIT\nTrans Cold Strg\n\nLR#: ".$Arrival."\nBC: ".$Barcode."\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
					$continue = false;
				break;

				case "":

/*			if($loc == "X" || $loc == ""){
				$continue = false;
			} else {
*/
					$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, NVL(WAREHOUSE_LOCATION, 'NONE') THE_LOC, QTY_IN_HOUSE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$prev_loc = $short_term_data_row['THE_LOC'];
					$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
					if($short_term_data_row['THE_DATE'] == "NONE"){
						Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, "ship");
					}

					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//					echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL6a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL6b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;

					// change the CARGO_TRACKING location
					$sql = "UPDATE CARGO_TRACKING SET WAREHOUSE_LOCATION = '".$loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4b)");

					// add CARGO_ACTIVITY record
					$sql = "INSERT INTO CARGO_ACTIVITY 
							(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, ACTIVITY_DESCRIPTION, FROM_LOCATION) VALUES
							('".$act_num."', '16', '0', '".$emp_no."', '".$Cust."', SYSDATE, '".$Barcode."', '".$Arrival."', '".$qty_trans."', 'MOVE TO: ".$loc."', '".$prev_loc."')";
//					echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL5a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL5b)");

					// In the event of a split-pallet, update that table.  As all of a "pallet" gets transferred,
					// we can just do the change, instead of asking per split.
					$sql = "UPDATE WDI_SPLIT_PALLETS SET WAREHOUSE_LOCATION = '".$loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL5a-a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL5a-b)");
					
					ora_commit($rf_conn);
					fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.");
					echo $Barcode."\nTransfer To\nCold Strg Complete.\n(Set to ".$loc.")\n\n";
					echo "(Press Enter to\nContinue)\n";
					fscanf(STDIN, "%s\n", $junk);
					$continue = false;
				break;
			}
		}

		$Barcode = "";
		fresh_screen("WALMART FRUIT\nTrans Cold Strg\nEnter X to exit.");
		echo "Next Barcode:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	}
}

function Rapid_Cool($CID){
	global $rf_conn;
	global $scanner_type;


	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	// get employee number for use in ACTIVITY_ID later
	$emp_no = get_emp_no($CID);

	$Arrival = "XXX";
/*
	// get and validate ship #
	while($Arrival == "XXX" || $Arrival == "Invalid"){
		fresh_screen("CHILEAN FRUIT\nTo Rapid Cool\nEnter X to exit.");
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
*/

	$CR_loc = "XXX";
	while($CR_loc == "XXX" || $CR_loc == "Invalid"){
		fresh_screen("  Select Chamber:");
		echo "1) CR1\n";
		echo "2) CR2\n";
		echo "3) CR3\n";
		echo "4) CR4\n";
		echo "X) Exit\n";
		fscanf(STDIN, "%s\n", $CR_CHOICE);
		$CR_CHOICE = trim(strtoupper($CR_CHOICE));

		switch ($CR_CHOICE) {
			case 1:
				$CR_loc = "CR1";
			break;
			case 2:
				$CR_loc = "CR2";
			break;
			case 3:
				$CR_loc = "CR3";
			break;
			case 4:
				$CR_loc = "CR4";
			break;
			case "X":
				return;
			break;
			default:
				$CR_loc = "XXX";
			break;
		}

	}

	$this_count = 0;
	fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.\n\nChamber: ".$CR_loc."\nScanned So Far: ".$this_count);
	$Barcode = "";
	echo "Barcode:";
	fscanf(STDIN, "%s\n", $Barcode);
	$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	while(strtoupper($Barcode) != "X" && strtoupper($Barcode) != ""){
		$continue = true;
		$Cust = "";
		$Commodity = "";
		$qty_in_house = 0;
		$Arrival = "";


/*		// check if pallet exists at all
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL8a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL8b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] == 0){
			fresh_screen("CHILEAN FRUIT\nTrans Cold Strg\nEnter X to exit.\n\nLR#: ".$Arrival."\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}

		// check if pallet is on this ship
		if($continue){
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL9a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Cannot Retrieve\nPallet Info\n(ReL9b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				fresh_screen("CHILEAN FRUIT\nTo Rapid Cool\nEnter X to exit.\n\nLR#: ".$Arrival."\nBC: ".$Barcode."\n**PALLET NOT ON\nSELECTED LR#**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}
*/
		if($continue){
			// no pallet, 1 pallet, or multiple pallets?
//			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."'";
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL1a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL1b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] == 0){
				// pallet does not exist
				fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET DOES NOT\nEXIST.  CONTACT\nINVENTORY**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			} elseif($short_term_data_row['THE_COUNT'] > 1){
				// multiple pallet
				$continue = Select_Duplicate_Pallet($Barcode, $Cust, $Arrival, $Commodity, $qty_in_house);
			} else {
				// single pallet, get info
//				$sql = "SELECT CT.ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, VESSEL_NAME FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."' AND CT.ARRIVAL_NUM = '".$Arrival."'";
				$sql = "SELECT CT.ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, VESSEL_NAME FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) AND PALLET_ID = '".$Barcode."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Unable to get\nPallet Info\n(ReL2b)");
				ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$Cust = $short_term_data_row['RECEIVER_ID'];
				$vesname = $short_term_data_row['VESSEL_NAME'];
				$Commodity = $short_term_data_row['COMMODITY_CODE'];
				$Arrival = $short_term_data_row['ARRIVAL_NUM'];
			}
		}

		if(!validate_customer_to_scannertype($Cust, $scanner_type, "Rapid_Cool")){
			fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
			fscanf(STDIN, "%s\n", $junk);
			$continue = false;
		}

		if($continue){
			$sql = "SELECT WAREHOUSE_LOCATION FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
			$ora_success = ora_parse($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL7a)");
			$ora_success = ora_exec($short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nPallet Info\n(ReL7b)");
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$loc = $short_term_data_row['WAREHOUSE_LOCATION'];
			if($loc == $CR_loc){
				fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.\n\nBC: ".$Barcode."\n**PALLET IS ALREADY\nIN ".$CR_loc."**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$continue = false;
			}
		}

		while($continue){
			// do this if they did not "cancel" or "error" out of above
			fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.");
			echo "LR: ".$Arrival."\n";
			echo substr($vesname, 0, 19)."\n";
			echo "COMM: ".$Commodity."\n";
			echo "CUST: ".$Cust."\n";
			echo "BC: ".$Barcode."\n\n";
			echo "Loc: ".$loc."\n";
			echo "Moving To: ".$CR_loc."\n\n";
			echo "\"Enter\" to Accept\n";
			echo "\"X\" To Escape\n";

/*			$loc = "";
			fscanf(STDIN, "%[^[]]\n", $loc);
			$loc = trim(strtoupper($loc));
*/
			$Choice = "";
			fscanf(STDIN, "%s\n", $Choice);
			$Choice = strtoupper($Choice);

//			echo $Choice."\n";
//			fscanf(STDIN, "%s\n", $junk);


			switch($Choice){
//				case 1:
//					modify_unload($Barcode, $Arrival, $Cust, $Comm, "RCloc", $loc);
//				break;

				case "X":
					fresh_screen("WALMART FRUIT\nTo Rapid Cool\n\nLR#: ".$Arrival."\nBC: ".$Barcode."\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
					$continue = false;
				break;

				case "":

/*			if($loc == "X" || $loc == ""){
				$continue = false;
			} else {
*/
					$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_DATE, NVL(WAREHOUSE_LOCATION, 'NONE') THE_LOC, QTY_IN_HOUSE FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL3a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL3b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$prev_loc = $short_term_data_row['THE_LOC'];
					$qty_trans = $short_term_data_row['QTY_IN_HOUSE'];
					if($short_term_data_row['THE_DATE'] == "NONE"){
						Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, "ship");
					}

					$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$Barcode."' AND CUSTOMER_ID = '".$Cust."' AND ARRIVAL_NUM = '".$Arrival."'";
//					echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL6a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nPallet Info\n(ReL6b)");
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$act_num = $short_term_data_row['THE_MAX'] + 1;

					// change the CARGO_TRACKING location
					$sql = "UPDATE CARGO_TRACKING SET WAREHOUSE_LOCATION = '".$CR_loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4b)");
					$sql = "UPDATE WDI_SPLIT_PALLETS SET WAREHOUSE_LOCATION = '".$CR_loc."' WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4a-2)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL4b-2)");

					// add CARGO_ACTIVITY record
					$sql = "INSERT INTO CARGO_ACTIVITY 
							(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, ACTIVITY_DESCRIPTION, FROM_LOCATION) VALUES
							('".$act_num."', '23', '0', '".$emp_no."', '".$Cust."', SYSDATE, '".$Barcode."', '".$Arrival."', '".$qty_trans."', 'MOVE TO: ".$CR_loc."', '".$prev_loc."')";
//					echo $sql."\n"; fscanf(STDIN, "%s\n", $junk);
					$ora_success = ora_parse($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL5a)");
					$ora_success = ora_exec($modify_cursor, $sql);
					database_check($ora_success, "Unable to change\nPallet Info\n(ReL5b)");

					$this_count++;
					
					ora_commit($rf_conn);
					fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.");
					echo $Barcode."\nTransfer Complete.\n(Set to ".$CR_loc.")\n\n";
					echo "(Press Enter to\nContinue)\n";
					fscanf(STDIN, "%s\n", $junk);
					$continue = false;
				break;
			}
		}

		$Barcode = "";
		fresh_screen("WALMART FRUIT\nTo Rapid Cool\nEnter X to exit.\n\nChamber: ".$CR_loc."\nScanned So Far: ".$this_count);
		echo "Next Barcode:\n";
		fscanf(STDIN, "%s\n", $Barcode);
		$Barcode = strtoupper(strip_to_alphanumeric($Barcode));

	}
}




/*******************************************************************************
* AUXILIARY FUNCTIONS START HERE ***********************************************
********************************************************************************/

function RepackConvertBarcode($Barcode, $Barcode_with_append, $Arrival, $modify_cursor){
	$sql = "UPDATE CARGO_ACTIVITY
			SET PALLET_ID = '".$Barcode_with_append."'
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$Arrival."'
				AND CUSTOMER_ID = '3000'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to change\nPallet Info\n(RCB1a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to change\nPallet Info\n(RCB1b)");

	$sql = "UPDATE CARGO_TRACKING
			SET PALLET_ID = '".$Barcode_with_append."'
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$Arrival."'
				AND RECEIVER_ID = '3000'";
	$ora_success = ora_parse($modify_cursor, $sql);
	database_check($ora_success, "Unable to change\nPallet Info\n(RCB2a)");
	$ora_success = ora_exec($modify_cursor, $sql);
	database_check($ora_success, "Unable to change\nPallet Info\n(RCB2b)");
}



function Create_Pallet_From_Scratch($CID, $Barcode, $Cust, $Arrival, $Comm, $type){
/*
* used by Transfer Owner
*
* a function to create a pallet where it is known such a pallet is not currently imported to the DB.
*******************************************************************************************************/
	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);
	$modify_cursor = ora_open($rf_conn);

	// get type (ship or truck) if not passed
	if($type == ""){
		while($type == "" || $type == "Invalid"){
			fresh_screen("WALMART FRUIT\nCreate Pallet\nEnter X to exit.");
			echo "1: Ship\n";
			echo "2: Truck\n";
			echo "X: Cancel\n";
			fscanf(STDIN, "%s\n", $type);
			$type = strtoupper($type);
			if($type == "X"){
				return false;
			}

			if($type != 1 && $type != 2){
				$type = "Invalid";
			}
		}
	}

	// get and validate cust # if not passed
	if($Cust == ""){
		while($Cust == "" || $Cust == "Invalid"){
			fresh_screen("WALMART FRUIT\nCreate Pallet\nEnter X to exit.");
			if($Cust != ""){
				echo "Invalid Cust#\n";
			}
			echo "Customer#:\n";
			fscanf(STDIN, "%s\n", $Cust);
			$Cust = strtoupper($Cust);
			if($Cust == "X"){
				return false;
			}

			if(!is_numeric($Cust)){
				$Cust = "Invalid";
			} elseif(!validate_customer_to_scannertype($Cust, $scanner_type, "Create_Pallet_From_Scratch")){
				fresh_screen("WALMART FRUIT\nCreate Pallet\nEnter X to exit.\n\nCust: ".$Cust."\n**CUSTOMER NOT\nQUALIFIED FOR\nTHIS SCANNER**", "bad");
				fscanf(STDIN, "%s\n", $junk);
				$Cust = "Invalid";
			} else {
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(CPS1a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCustomer\n(CPS1b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$Cust = "Invalid";
				}
			}
		}
	}

	// get and validate comm # if not passed
	if($Comm == ""){
		while($Comm == "" || $Comm == "Invalid"){
			fresh_screen("WALMART FRUIT\nCreate Pallet\nEnter X to exit.");
			if($Comm != ""){
				echo "Invalid Comm#\n";
			}
			echo "Commodity#:\n";
			fscanf(STDIN, "%s\n", $Comm);
			$Comm = strtoupper($Comm);
			if($Comm == "X"){
				return false;
			}

			if(!is_numeric($Comm)){
				$Comm = "Invalid";
			} else {
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCommodity\n(CPS2a)");
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				database_check($ora_success, "Cannot Retrieve\nCommodity\n(CPS2b)");
				if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$Comm = "Invalid";
				}
			}
		}
	}

	// get and validate Arrival # if not passed
	if($Arrival == ""){
		while($Arrival == "" || $Arrival == "Invalid"){
			fresh_screen("WALMART FRUIT\nCreate Pallet\nEnter X to exit.");
			if($Arrival != ""){
				echo "Invalid Arv#\n";
			}
			echo "Arrival#:\n";
			fscanf(STDIN, "%s\n", $Arrival);
			$Arrival = strtoupper($Arrival);
			if($Arrival == "X"){
				return false;
			}
		}
	}

	// get and validate received qty
	if($qty_rec == ""){
		while($qty_rec == "" || $qty_rec == "Invalid"){
			fresh_screen("WALMART FRUIT\nCreate Pallet\nEnter X to exit.");
			if($qty_rec != ""){
				echo "Invalid QTY\n";
			}
			echo "QTY RCVD:\n";
			fscanf(STDIN, "%s\n", $qty_rec);
			$qty_rec = strtoupper($qty_rec);
			if($qty_rec == "X"){
				return false;
			}

			if(!is_numeric($qty_rec)){
				$qty_rec = "Invalid";
			}
		}
	}

	$qty_dmg = "";
	$loc = "";
	$variety = "";
	$size = "";
	$chep = "";

	do{
		$sql = "SELECT SUBSTR(COMMODITY_NAME, 0, 9) THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$Comm."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nComm Info\n(CPS3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nComm Info\n(CPS3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$commname = $short_term_data_row['THE_COMM'];

		$sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 9) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nCust Info\n(CPS4a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nCust Info\n(CPS4b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$custname = $short_term_data_row['THE_CUST'];

		system("clear");
		echo $Barcode."\n";
		echo $asterisk_array[0]."1)Cust: ".$custname."\n";
		echo $asterisk_array[1]."2)Comm: ".$commname."\n";
		echo $asterisk_array[2]."3)QTY Rcvd: ".$qty_rec."\n";
		echo $asterisk_array[3]."4)QTY Dmg: ".$qty_dmg."\n";
		echo $asterisk_array[4]."5)Loc: ".$loc."\n";
		echo $asterisk_array[5]."6)Var: ".$variety."\n";
		echo $asterisk_array[6]."7)Size: ".$size."\n";
		echo $asterisk_array[7]."8)Chep: ".$chep."\n";
		echo "Number -- Change\n";
		echo "Blank -- Accept\n";
		echo "X -- Exit\n";

		$Choice = "";
		fscanf(STDIN, "%s\n", $Choice);
		$Choice = strtoupper($Choice);

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
				if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "loc", $loc)){
					$asterisk_array[4] = "*";
				}
				break;
			case 6:
				if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "var", $variety)){
					$asterisk_array[5] = "*";
				}
				break;
			case 7:
				if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "size", $size)){
					$asterisk_array[6] = "*";
				}
				break;
			case 8:
				if(modify_unload($Barcode, $Arrival, $Cust, $Comm, "chep", $chep)){
					$asterisk_array[7] = "*";
				}
				break;
			case "X":
				fresh_screen("WALMART FRUIT\nUnload Ship\n\nLR Num: ".$Arrival."\nBC: ".$Barcode."\nCancelled.");
				fscanf(STDIN, "%s\n", $junk);
				return false;
			case "":
				$sql = "INSERT INTO CARGO_TRACKING 
						(COMMODITY_CODE,
						DATE_RECEIVED,
						QTY_RECEIVED,
						RECEIVER_ID,
						QTY_DAMAGED,
						WAREHOUSE_LOCATION,
						QTY_IN_HOUSE,
						PALLET_ID,
						ARRIVAL_NUM,
						RECEIVING_TYPE,
						CARGO_SIZE,
						VARIETY,
						CHEP)
						VALUES
						('".$Comm."',
						SYSDATE,
						'".$qty_rec."',
						'".$Cust."',
						'".$qty_dmg."',
						'".$loc."',
						'".$qty_rec."',
						'".$Barcode."',
						'".$Arrival."',
						'S',
						'".$size."',
						'".$variety."',
						'".$chep."')";
				$ora_success = ora_parse($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nInsert Pallet\n(US7a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nInsert Pallet\n(US7b)");

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
				database_check($ora_success, "Unable to\nInsert Pallet\n(US8a)");
				$ora_success = ora_exec($modify_cursor, $sql);
				database_check($ora_success, "Unable to\nInsert Pallet\n(US8b)");

				ora_commit($rf_conn);
				fresh_screen("WALMART FRUIT\nCreate Pallet\n\nBC: ".$Barcode."\n\nCREATED!");
				fscanf(STDIN, "%s\n", $junk);

				break;
		}
	} while($Choice != "X" && $Choice != "");

	return true;
}


function Select_Duplicate_Pallet($Barcode, &$Cust, &$Arrival, &$Commodity, &$qty_rec){
/* used by Trans_Owner and Validate_duplicate_get_comm_and_cust
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
	$sql = "SELECT COMMODITY_NAME, VESSEL_NAME, CT.COMMODITY_CODE, CT.ARRIVAL_NUM, RECEIVER_ID, WAREHOUSE_LOCATION, QTY_RECEIVED, QTY_DAMAGED, VARIETY, CARGO_SIZE, CHEP, QTY_IN_HOUSE, NVL(BOL, 'UNKNOWN') THE_ITEM FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, VESSEL_PROFILE VP WHERE PALLET_ID = '".$Barcode."' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))";
	
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
		$pallet_info[$pallet_number]["wmitem"] = $short_term_data_row['THE_ITEM'];
		$pallet_info[$pallet_number]["loc"] = $short_term_data_row['WAREHOUSE_LOCATION'];
		$pallet_info[$pallet_number]["rec"] = $short_term_data_row['QTY_RECEIVED'];
		$pallet_info[$pallet_number]["dmg"] = $short_term_data_row['QTY_DAMAGED'];
		$pallet_info[$pallet_number]["IH"] = $short_term_data_row['QTY_IN_HOUSE'];
		$pallet_info[$pallet_number]["var"] = $short_term_data_row['VARIETY'];
		$pallet_info[$pallet_number]["size"] = $short_term_data_row['CARGO_SIZE'];
		$pallet_info[$pallet_number]["chep"] = $short_term_data_row['CHEP'];

		$pallet_number++;
	}

	$choice = "there is no spoon";
	$display_set_counter = 0; 
	$max_page = $pallet_number;

	fresh_screen("WALMART FRUIT\nSelect Duplicate\n\nBC: ".$Barcode."\nDuplicate Pallets\nFound.  Please\nSelect:", "bad");
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
		echo "WMitem#: ".$pallet_info[$display_set_counter]["wmitem"]."\n";
		echo "QTY Rcvd: ".$pallet_info[$display_set_counter]["rec"]."\n";
		echo "Dmg: ".$pallet_info[$display_set_counter]["dmg"]."  Inhs: ".$pallet_info[$display_set_counter]["IH"]."\n";
		echo "Loc: ".$pallet_info[$display_set_counter]["loc"]."\n";
		echo "Var: ".$pallet_info[$display_set_counter]["var"]."\n";
		echo "Size: ".$pallet_info[$display_set_counter]["size"]."\n";
		echo "Chep: ".$pallet_info[$display_set_counter]["chep"]."\n";

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


/*
	fresh_screen("WALMART FRUIT\nSelect Duplicate\n\nBC: ".$Barcode."\nDuplicate Pallets\nFound.  Please\nSelect:", "bad");
	fscanf(STDIN, "%s\n", $junk);
	while(true){ // this looks like an infinite loop, but all "valid" entries have a break in them
		system("clear");
		echo $Barcode."\n";
		echo "Cust: ".$pallet_info[$pallet_iteration]["cust"]."\n";
		echo "LR#: ".$pallet_info[$pallet_iteration]["LR"]."\n";
		echo substr($pallet_info[$pallet_iteration]["vesname"], 0, 19)."\n";
		echo "Comm: ".$pallet_info[$pallet_iteration]["commname"]."\n";
		echo "QTY Rcvd: ".$pallet_info[$pallet_iteration]["rec"]."\n";
		echo "Dmg: ".$pallet_info[$pallet_iteration]["dmg"]."  Inhs: ".$pallet_info[$pallet_iteration]["IH"]."\n";
		echo "Loc: ".$pallet_info[$pallet_iteration]["loc"]."\n";
		echo "Var: ".$pallet_info[$pallet_iteration]["var"]."\n";
		echo "Size: ".$pallet_info[$pallet_iteration]["size"]."\n";
		echo "Chep: ".$pallet_info[$pallet_iteration]["chep"]."\n";
		echo "Enter -- Accept\n";
		echo "N -- Next\n";
		echo "X -- Exit\n";

		fscanf(STDIN, "%s\n", $answer);
		$answer = strtoupper($answer);
		if($answer == ""){
			$Cust = $pallet_info[$pallet_iteration]["cust"];
			$Commodity = $pallet_info[$pallet_iteration]["comm"];
			$Arrival = $pallet_info[$pallet_iteration]["LR"];
			$qty_rec = $pallet_info[$pallet_iteration]["rec"];
			$new_pallet = false;
			return true;
		}elseif($answer == "X"){
			return false;
		}elseif($answer == "N"){
			$pallet_iteration = ($pallet_iteration + 1) % $pallet_number;
		}else{
			// bad entry, do nothing
		}

		$answer = "";
	}
*/

}

function Auto_Receive_Pallet($emp_no, $Barcode, $Cust, $Arrival, $Commodity, $type){
/* used by Trans_Owner
*
* Auto-receives a pallet.  Unlike the main receive function (unload ship), 
* which can redefine certain pallet characteristics, this assumes default (already present) values
* and is only used by "shipped" pallets (all "trucked" pallets need to be created, not received).
*
* Nonetheless, I have a "$type" variable in case I ever need to add more to this.
*****************************************************************************************************/

	global $rf_conn;
	global $scanner_type;

	ora_commitoff($rf_conn);		// turn off autocommit, will manually commit after each success
	$modify_cursor = ora_open($rf_conn);
	$short_term_data_cursor = ora_open($rf_conn);

	if($type == "ship"){
		// which came first, this scan, or the vessel's finished date?
		$sql = "SELECT TO_CHAR(LEAST((SYSDATE - (1/1440)), NVL(DATE_FINISHED, SYSDATE)), 'MM/DD/YYYY HH24:MI:SS') THE_RECDATE
				FROM VOYAGE_FROM_BNI WHERE LR_NUM = '".$Arrival."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP1a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP1b)");
		if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			//vessel didnt exist.
			$date_sql = "SYSDATE - (1/1440)";
		} else {
			$date_sql = "TO_DATE('".$short_term_data_row['THE_RECDATE']."', 'MM/DD/YYYY HH24:MI:SS')";
		}

		$sql = "UPDATE CARGO_TRACKING SET 
				DATE_RECEIVED = ".$date_sql."
				WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
		$ora_success = ora_parse($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP2a)");
		$ora_success = ora_exec($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP2b)");

		$sql = "SELECT QTY_RECEIVED FROM CARGO_TRACKING WHERE PALLET_ID = '".$Barcode."' AND ARRIVAL_NUM = '".$Arrival."' AND RECEIVER_ID = '".$Cust."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP3a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to\nUpdate Pallet\n(ARP3b)");
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$qty_rec = $short_term_data_row['QTY_RECEIVED'];

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
				".$date_sql.",
				'".$Barcode."',
				'".$Arrival."',
				'".$qty_rec."')";
		$ora_success = ora_parse($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nInsert Pallet\n(ARP4a)");
		$ora_success = ora_exec($modify_cursor, $sql);
		database_check($ora_success, "Unable to\nInsert Pallet\n(ARP4b)");

		ora_commit($rf_conn);
	
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
	global $scanner_type;

	$was_changed = false;
	$continue_function = true;

	$new_value = "";

	while($continue_function){
		switch($mod_type){
			case "cust":
				fresh_screen("WALMART FRUIT\nModify Customer\nEnter X to exit.");
				echo "Current Customer:\n  ".$return_variable."\nNew Customer:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

//				if($new_value == "X") {
				if(!is_numeric($new_value)) {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify Customer\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$new_value."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(MU1a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nCust Info\n(MU1b)");
					
					if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
						fresh_screen("WALMART FRUIT\nModify Customer\n\nInvalid Customer\n(".$new_value.")", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} else {
						$continue_function = false;
						$return_variable = $new_value;
						$was_changed = true;
					}
				}
			break;

			case "comm":
				fresh_screen("WALMART FRUIT\nModify Commodity\nEnter X to exit.");
				echo "Current Commodity:\n  ".$return_variable."\nNew Commodity:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

//				if($new_value == "X") {
				if(!is_numeric($new_value)) {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify Commodity\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$new_value."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(MU2a)");
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					database_check($ora_success, "Unable to get\nComm Info\n(MU2b)");
					
					if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						fresh_screen("WALMART FRUIT\nModify Commodity\n\nInvalid Commodity\n(".$new_value.")", "bad");
						fscanf(STDIN, "%s\n", $junk);
					} else {
						$continue_function = false;
						$return_variable = $new_value;
						$was_changed = true;
					}
				}
			break;

			case "rcvd":
				fresh_screen("WALMART FRUIT\nModify QTY Rcvd\nEnter X to exit.");
				echo "Expected QTY Rcvd:\n  ".$return_variable."\nActual QTY Rcvd:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify QTY Rcvd\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif(!is_numeric($new_value)){
					fresh_screen("WALMART FRUIT\nModify QTY Rcvd\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "dmg":
				fresh_screen("WALMART FRUIT\nModify QTY DMG\nEnter X to exit.");
				$return_variable = (0 + $return_variable);
				echo "Current QTY DMG:\n  ".$return_variable."\nNew QTY DMG:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify QTY DMG\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif(!is_numeric($new_value)){
					fresh_screen("WALMART FRUIT\nModify QTY DMG\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk);
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "loc":
				fresh_screen("WALMART FRUIT\nModify Location\nEnter X to exit.");
				echo "Current Location:\n  ".$return_variable."\nNew Location:\n";
				fscanf(STDIN, "%[^[]]\n", $new_value);
				$new_value = trim(strtoupper($new_value));

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify Location\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
/*				} elseif(!is_numeric($new_value)){
					fresh_screen("WALMART FRUIT\nModify Location\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); */
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "var":
				fresh_screen("WALMART FRUIT\nModify Variety\nEnter X to exit.");
				echo "Current Variety:\n  ".$return_variable."\nNew Variety:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify Variety\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
/*				} elseif(!is_numeric($new_value)){
					fresh_screen("WALMART FRUIT\nModify Location\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); */
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "size":
				fresh_screen("WALMART FRUIT\nModify Size\nEnter X to exit.");
				echo "Current Size:\n  ".$return_variable."\nNew Size:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify Size\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
/*				} elseif(!is_numeric($new_value)){
					fresh_screen("WALMART FRUIT\nModify Location\n\nEntered Value\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); */
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;

			case "chep":
				fresh_screen("WALMART FRUIT\nModify Chep\nEnter X to exit.");
				echo "Current Chep:\n  ".$return_variable."\nNew Chep:\n";
				fscanf(STDIN, "%s\n", $new_value);
				$new_value = strtoupper($new_value);

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nModify Chep\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif($new_value != "" && $new_value != "Y" && $new_value != "N"){
					fresh_screen("WALMART FRUIT\nModify Chep\n\nMust be Y, N,\nOr Blank\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); 
				} else {
					$continue_function = false;
					$return_variable = $new_value;
					$was_changed = true;
				}
			break;
/*
			case "RCloc":
				fresh_screen("WALMART FRUIT\nRC Location\nEnter X to exit.");
				echo "Current Location:\n  ".$return_variable."\nNew CR-Chamber:\n";
				fscanf(STDIN, "%[^[]]\n", $new_value);
				$new_value = trim(strtoupper($new_value));

				// strip off the CR if entered to homogenize input for rest of function
				if(strtoupper(substr($new_value, 0, 2)) == "CR"){
					$new_value = substr($new_value, 2);
				}

				if($new_value == "X") {
					$continue_function = false;
					fresh_screen("WALMART FRUIT\nRC Location\n\nCancelled.");
					fscanf(STDIN, "%s\n", $junk);
				} elseif(!is_numeric($new_value)){
					fresh_screen("WALMART FRUIT\nRC Location\n\nEntered CR-Location\nNot a Number\n(".$new_value.")", "bad");
					fscanf(STDIN, "%s\n", $junk); 
				} else {
					$continue_function = false;
					$return_variable = "CR".$new_value; // force-add the CR
					$was_changed = true;
				}
			break;
*/
		}
	}

	return $was_changed;
}

function Validate_duplicate_get_comm_and_cust($Barcode, $Arrival, &$Cust, &$Comm, &$qty_rec, &$new_pallet){
/* used by Unload Ship
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
	global $scanner_type;
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

//function validate_pallet_for_walmart($Barcode, $order_num, $cust, $Arrival, $pallet_owner){
/*
*		Used by Truck Out
*	
*		Walmart pallets have far mroe criteria to ship than others.
*
*		Checks include:
*		-- make sure walmart pallets dont go on non-wallmart orders (or visa-versa)
*		-- make sure there are no similar pallets to the one scanned from an earlier vessel (FIFO)
*		-- make sure pallet is NOT ON HOLD
*
*		Errors are palced in return value; calling function will see if return value from THIS function
*		Is a null string or not.
************************************************************************************************/
/*	global $rf_conn;
	global $scanner_type;
	$short_term_data_cursor = ora_open($rf_conn);
	$VERY_short_term_data_cursor = ora_open($rf_conn);
	$select_cursor = ora_open($rf_conn);

	

	// top-level SQL determiens if pallet is WALMARTS.  other checks based on result.

	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$pallet_owner."' AND CUSTOMER_GROUP = 'WALMART'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(VPFM1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(VPFM1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_row['THE_COUNT'] == 0 ){
		// not a walmart pallet.  is order walmart's?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$cust."' AND CUSTOMER_GROUP = 'WALMART'";
		$ora_success = ora_parse($VERY_short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VPFM3a)");
		$ora_success = ora_exec($VERY_short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VPFM3b)");
		ora_fetch_into($VERY_short_term_data_cursor, $VERY_short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($VERY_short_term_row['THE_COUNT'] != 0 ){
			return "CANNOT PUT NON-\nWALMART PALLET\nON WALMART ORDER";
		}
	} else {
		// wallmart pallet.  more checks...
		// is order walmart's?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_MAP WHERE RECEIVER_ID = '".$cust."' AND CUSTOMER_GROUP = 'WALMART'";
		$ora_success = ora_parse($VERY_short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VPFM4a)");
		$ora_success = ora_exec($VERY_short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VPFM4b)");
		ora_fetch_into($VERY_short_term_data_cursor, $VERY_short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($VERY_short_term_row['THE_COUNT'] == 0 ){
			return "CANNOT PUT WALMART\nPALLET ON NON-\nWALMART ORDER";
		}

		
		// is pallet on hold?
		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_ADDITIONAL_DATA WHERE WDI_STATUS = 'HOLD'
				AND WDI_ARRIVAL_NUM = '".$Arrival."'
				AND WDI_RECEIVER_ID = '".$pallet_owner."'
				AND WDI_PALLET_ID = '".$Barcode."'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VPFM2a)");
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		database_check($ora_success, "Unable to get\nPallet Info\n(VPFM2b)");
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] > 0 ){
			// this pallet is on "walmart" hold.  is the order it's going on a special "rejected" load?
			$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_ADDITIONAL_DATA
					WHERE WDI_ARRIVAL_NUM = '".$Arrival."'
					AND WDI_RECEIVER_ID = '".$pallet_owner."'
					AND WDI_PALLET_ID = '".$Barcode."'
					AND WDI_REJECT_LOAD_ORDER_NUM = '".$order_num."'";
			$ora_success = ora_parse($VERY_short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nWalmart Info\n(VPFM5a)");
			$ora_success = ora_exec($VERY_short_term_data_cursor, $sql);
			database_check($ora_success, "Unable to get\nWalmart Info\n(VPFM5b)");
			ora_fetch_into($VERY_short_term_data_cursor, $VERY_short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($VERY_short_term_row['THE_COUNT'] > 0){
				// order is acceptable reject order.  do nothing.
			} else {
				return "PALLET ON HOLD\nCANNOT SHIP";
			}
		}

		// is this pallet the FIFO choice for this order?
		//--- ADD LOGIC HERE
	}

	return "";

}
*/
//function walmart_pallet($Barcode){
/*
*	Function checks if a barcode is for a walmart pallet.
*
*	Individual calling functions can decide what it wants to do with the result.
********************************************************************************/
/*	global $rf_conn;
	global $scanner_type;
	$short_term_data_cursor = ora_open($rf_conn);

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcode."'
			AND RECEIVER_ID = '3000'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(WP1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Unable to get\nPallet Info\n(WP1b)");
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($short_term_row['THE_COUNT'] > 0){
		return true;
	} else {
		return false;
	}
}
*/

// ---------------MAIN Menu-----------------
// starts here

$scanner_type = "WALMART";

$SID = Login_Super($argv[1]);
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}


do {
	fresh_screen("WALMART FRUIT");
	echo "1: Super Functions\n";
	echo "2: Checker\n";
	echo "3: Exit Fruit\n";
	fscanf(STDIN, "%s\n", $DoleSuperCHOICE);
} while ($DoleSuperCHOICE != 1 && $DoleSuperCHOICE != 2 && $DoleSuperCHOICE != 3);

if($DoleSuperCHOICE == 1){
	do {
		fresh_screen("Fruit (Super)");
		echo "1: Exit\n"; 
		fscanf(STDIN, "%s\n", $DoleCHOICE);

		switch ($DoleCHOICE) {
			case 1:
				echo "under construction\n";
				// will exit
			break;
		}
	}while ($DoleCHOICE != 1);

} elseif($DoleSuperCHOICE == 2){
	
	do {
		$subCHOICE = "";

		if($CID == ""){
			$CID = Login_Checker();
		}
		fresh_screen(" Walmart (Checker)");
		echo " 1: Super(", $SID, ")\n";
		echo " 2: Chkr(", $CID, ")\n";
		echo " 3: Unload Cargo\n";
		echo " 4: Recoup/Relocate\n";
		echo " 5: Load Truck\n";
		echo " 6: Returns\n"; 
		echo " 7: Info\n";
		echo " 8: Transfer\n"; 
		echo " 9: Void\n"; 
//		echo " 10: NONTWIC Scan\n"; 
		echo " 10: Exit WM-Fruit\n"; 
		echo " ENTER (1-10):\n";
		fscanf(STDIN, "%s\n", $DoleCHOICE);


		switch ($DoleCHOICE) {
			case 1:
				$SID = Login_Super();
				$CID = Login_Checker();
			break;
			
			case 2:
				$CID = Login_Checker();
			break;

			case 3:
				fresh_screen("Unload:\n\n1) Inbound Truck\n2) Edit Ctn Count");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Unload_Truck($CID);
				} elseif($subCHOICE == "2"){
					Alter_Original_Count($CID);
				}
			break;

			case 4:
				fresh_screen("Please Select:\n\n1) Recoup\n2) Relocate");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Recoup($CID);
				} elseif($subCHOICE == "2"){
					Relocate($CID, 2);
				}
			break;

			case 5:
				Load_Truck($CID);
			break;

			case 6:
				fresh_screen("RETURNS\n1) Dock\n2) Full\n3) Repack");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Returns($CID, "dock");
				} elseif($subCHOICE == "2"){
					Returns($CID, "full");
				} elseif($subCHOICE == "3"){
					Return_Repack($CID);
				}
			break;

			case 7:
				fresh_screen("INFO\n1) Pallet\n2) Outbound Order\n3) Inbound Order\n4) Transfer");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Info_Pallet();
				} elseif($subCHOICE == "2"){
					Info_Outbound_Order();
				} elseif($subCHOICE == "3"){
					Info_Inbound_Order();
				} elseif($subCHOICE == "4"){
					Info_Transfer();
				}
			break;

			case 8:
				fresh_screen("Please Select:\n--Transfer\n\n1) Trans Owner\n2) To Cold Storage\n3) To Rapid Cool");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					Trans_Owner($CID);
				} elseif($subCHOICE == "2"){
					Cold_Storage($CID);
				} elseif($subCHOICE == "3"){
					Rapid_Cool($CID);
				}
			break;

			case 9:
				fresh_screen("VOID\n1) Order\n2) Pallet");
				fscanf(STDIN, "%s\n", $subCHOICE);
				if($subCHOICE == "1"){
					fresh_screen("VOID ORDER\n1) Inbound\n2) Outbound");
					fscanf(STDIN, "%s\n", $subsubCHOICE);
					if($subsubCHOICE == "1"){
						VoidINTruckORDER($CID);
					} elseif($subsubCHOICE == "2"){
						VoidOUTORDER($CID);
					}
				} elseif($subCHOICE == "2"){
					fresh_screen("VOID PALLET\n1) Inbound\n2) Outbound");
					fscanf(STDIN, "%s\n", $subsubCHOICE);
					if($subsubCHOICE == "1"){
						VoidINTruck($CID);
					} elseif($subsubCHOICE == "2"){
						VoidOUT($CID);
					}
				}
			break;

			case 10:
				// will exit
			//	NONTWIC($CID);
			break;

			case 11:
				//
			break;

			default:
		}
	} while ($DoleCHOICE != 10);
}