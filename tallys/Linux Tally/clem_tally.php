<?
include("get_employee_name.php");
$short_term_data_cursor = ora_open($rf_conn);
$short_term_data_cursor_pl = ora_open($rf_conn);

//for ($temp = 0; $temp < 10; $temp++)
//echo "\n";
echo "\nCLEMENTINE TALLY - Enter X to exit.\n\n";

//$Customer_Id = remove_badchars(strtoupper(trim(get_customer_id())));

$Customer_Id = "";
while($Customer_Id == "" || $Customer_Id == "Invalid"){
	print chr(27)."[H".chr(27)."[2J";
	echo "\nCLEMENTINE TALLY\nEnter X to exit.\n\n";
	if($Customer_Id != ""){
		echo "Invalid Customer #\n";
	}
	echo "Customer#: \n";
	fscanf(STDIN, "%s\n", $Customer_Id);
	$order = strtoupper($Customer_Id);
	if($Customer_Id == "X"){
		return;
	}
	$Customer_Id = remove_badchars($Customer_Id);

	$sql = "SELECT COUNT(*) THE_COUNT FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".trim($Customer_Id)."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCustomer\n(DtO1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nCustomer\n(DtO1b)");
	if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$order = "Invalid";
	} elseif($short_term_data_row['THE_COUNT'] <= 0){
		$order = "Invalid";
	}			
}
 
$Arrival_Num = "";
while($Arrival_Num == "" || $Arrival_Num == "Invalid"){
	print chr(27)."[H".chr(27)."[2J";
	echo "\nCLEMENTINE TALLY\nEnter X to exit.\n\n";
	if($Arrival_Num != ""){
		echo "Invalid Arrival #\n";
	}
	echo "Arrival# : \n";
	fscanf(STDIN, "%s\n", $Arrival_Num);
	$Arrival_Num = strtoupper($Arrival_Num);
	if($Arrival_Num == "X"){
		return;
	}
	$Arrival_Num = remove_badchars($Arrival_Num);

	$sql = "SELECT COUNT(*) THE_COUNT FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".trim($Arrival_Num)."'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nArrival\n(DtO1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nArrival\n(DtO1b)");
	if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$Arrival_Num = "Invalid";
	} elseif($short_term_data_row['THE_COUNT'] <= 0){
		$Arrival_Num = "Invalid";
	}			
}

$Order_Num = "";
while($Order_Num == "" || $Order_Num == "Invalid"){
	print chr(27)."[H".chr(27)."[2J";
	echo "\nCLEMENTINE TALLY\nEnter X to exit.\n\n";
	if($Order_Num != ""){
		echo "Invalid Order #\n";
	}
	echo "Order # : \n";
	fscanf(STDIN, "%s\n", $Order_Num);
	$Order_Num = strtoupper($Order_Num);
	if($Order_Num == "X"){
		return;
	}
	$Order_Num = remove_badchars($Order_Num);

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".strtoupper(trim($Order_Num))."' AND ARRIVAL_NUM = '".trim($Arrival_Num)."' AND CUSTOMER_ID = '".trim($Customer_Id)."' AND SERVICE_CODE IN (6, 7, 13)";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder\n(DtO1a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	database_check($ora_success, "Cannot Retrieve\nOrder\n(DtO1b)");
	if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$Order_Num = "Invalid";
	} elseif($short_term_data_row['THE_COUNT'] <= 0){
		$Order_Num = "Invalid";
	}			
}


// this next if looks weird, but:  For domestic product, the vessel num is the customer num.
// Since domestic product pick list comes from one table and the other orders come from another table we set the right table names below.
If (trim($Customer_Id) != trim($Arrival_Num)) {
	$SignatureSQLAddon = "DC_PICKLIST";}
Else {
	$SignatureSQLAddon = "DC_DOMESTIC_PICKLIST"; }

// Sometimes no picklist exists for orders.  If this is the case, set variable here, to prepare for footnote printing.
$SignatureCheck = "UNSET";
$sql = "SELECT COUNT(*) THE_COUNT FROM ".$SignatureSQLAddon." WHERE ORDERNUM = '".$Order_Num."'";
$ora_success = ora_parse($short_term_data_cursor, $sql);
database_check($ora_success, "Cannot Retrieve\nOrder\n(DtO1a)");
$ora_success = ora_exec($short_term_data_cursor, $sql);
database_check($ora_success, "Cannot Retrieve\nOrder\n(DtO1b)");
if(!ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	$Signature_Check = "NOPICK";
	} elseif($short_term_data_row['THE_COUNT'] == 0){
		$Signature_Check = "NOPICK";
};
$gCustomer_Name = get_customer_name($Customer_Id);
$gVessel_Name = get_vessel_name($Arrival_Num);

/* Setting a default value for kiosk to Testing */
if ($kiosk_name ==  "") $kiosk_name = "Testing";

echo "\n   Processing Order ".$Order_Num."...\n";

/* Setting up the File Name String as Customer, Arrival, Order, Date, Time */

$timedatestamp = date("Y_m_d_H_i_s");
$filename = $Customer_Id."_".$Arrival_Num."_".$Order_Num."_".$timedatestamp;
$handle = fopen("/home/kiosk/printouts/".$kiosk_name."/".$filename.".txt", "w");

/* Print Tally Banner Info */
fwrite($handle, "Date/Time:".$timedatestamp."          ");
fwrite($handle, "Printed From:".$kiosk_name."\n");
fwrite($handle, "PORT OF WILMINGTON  - CLEMENTINE TALLY\n\n");

$TotalCases = 0;
$TotalPallets = 0;

/* Print First and Last Scan Times */
$sql_time = "SELECT To_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$Order_Num."' AND ARRIVAL_NUM = '".$Arrival_Num."' AND CUSTOMER_ID = '".$Customer_Id."'";
$ora_success = ora_parse($short_term_data_cursor, $sql_time);
database_check($ora_success, "Unable to get\nOrder Info\n(US3a)");
$ora_success = ora_exec($short_term_data_cursor, $sql_time);
database_check($ora_success, "Unable to get\nOrder Info\n(US3b)");
ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$Start_Time = $short_term_data_row['START_TIME'];
$End_Time = $short_term_data_row['END_TIME'];
fwrite($handle, "FIRST SCAN: ".$Start_Time."          ");
fwrite($handle, "LAST SCAN: ".$End_Time."\n");

/* Print Commodity Name */
$sql_comm = "SELECT PORT_COMMODITY_CODE || '-' || DC_COMMODITY_NAME THE_COMM FROM DC_EPORT_COMMODITY WHERE PORT_COMMODITY_CODE = (SELECT COMMODITYCODE FROM DC_ORDER WHERE ORDERNUM = '".$Order_Num."')";
$ora_success = ora_parse($short_term_data_cursor, $sql_comm);
database_check($ora_success, "Unable to get\nCommodity Info\n(US3a)");
$ora_success = ora_exec($short_term_data_cursor, $sql_comm);
database_check($ora_success, "Unable to get\nCommodity Info\n(US3b)");
ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$CommodityName = trim($short_term_data_row['THE_COMM']);
fwrite($handle, "COMMODITY: ".$CommodityName."          ");

/* Print Customer Name always*/
fwrite($handle, "CUSTOMER: ".$gCustomer_Name."          " );
/* Print Order Number */
fwrite($handle, "ORDER NUMBER: ".$Order_Num."\n");

/* If Customer is 439 (now 835) then print the Vessel Number and Name */
//if($Customer_Id == "439") {
if($Customer_Id == "835") {
		fwrite($handle, "VESSEL: ".$Arrival_Num."-".$gVessel_Name."\n");
		};

/* Print the Column Names */
$the_pallet = str_pad("BARCODE", 43);
$the_pkgh = str_pad("PKGH", 7);
$the_weight = str_pad("WEIGHT", 10);
$the_size = str_pad("SIZE", 7);
$the_orig = str_pad("ORIG", 7);
$the_change = str_pad("ACTUAL", 11);
$the_status = str_pad("RG/HSP", 8);
$the_damage = str_pad("DMG", 7);
$the_container = str_pad("CONT#", 15);
$the_checker = str_pad("CHECKR", 6);
fwrite($handle, $the_pallet.$the_pkgh.$the_weight.$the_size.$the_orig.$the_change.$the_status.$the_damage.$the_container.$the_checker."\n");
fwrite($handle, "=========================================================================================================================\n");

/* Process the rows of the order*/
$sql = "SELECT CA.ACTIVITY_NUM, CT.PALLET_ID THE_PALLET, CT.EXPORTER_CODE THE_PKGH, CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, CT.CARGO_SIZE THE_SIZE, CT.BATCH_ID THE_ORIG, CA.QTY_CHANGE THE_CHANGE, CA.SERVICE_CODE THE_SERVICE, CT.CARGO_STATUS THE_STATUS, NVL(CA.BATCH_ID, '0') THE_BATCH, CT.CONTAINER_ID THE_CONTAINER, ACTIVITY_ID THE_CHECKER FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.SERVICE_CODE IN (6, 7, 13) AND CA.ORDER_NUM = '".$Order_Num."' AND CT.ARRIVAL_NUM = '".$Arrival_Num."' AND CT.RECEIVER_ID = '".$Customer_Id."' AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) AND CA.QTY_CHANGE != 0 ORDER BY CT.EXPORTER_CODE, CT.PALLET_ID, CA.ACTIVITY_NUM";

$ora_success = ora_parse($short_term_data_cursor, $sql);
database_check($ora_success, "Unable to get\nOrder Info\n(US3a)");
$ora_success = ora_exec($short_term_data_cursor, $sql);
database_check($ora_success, "Unable to get\nOrder Info\n(US3b)");

$TotalCases = 0;
$TotalPallets = 0;
/* Print the rows of the order */
While(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	$the_pallet = str_pad(trim($short_term_data_row['THE_PALLET']), 43);
	$the_pkgh = str_pad(trim($short_term_data_row['THE_PKGH']), 7);
	$the_weight = str_pad(trim($short_term_data_row['THE_WEIGHT']), 10);
	$the_size = str_pad(trim($short_term_data_row['THE_SIZE']), 7);
	$the_orig = str_pad(trim($short_term_data_row['THE_ORIG']), 7);
	$the_change = str_pad(trim($short_term_data_row['THE_CHANGE']), 11);
	$the_service = $short_term_data_row['THE_SERVICE'];
	$the_status = str_pad(trim($short_term_data_row['THE_STATUS']), 8);
	$the_damage = str_pad(trim($short_term_data_row['THE_BATCH']), 7);	/* need to fix this */
	$the_container = str_pad(trim($short_term_data_row['THE_CONTAINER']),15);
//	$the_checker = str_pad(trim($short_term_data_row['THE_CHECKER']),6);
	$the_checker = str_pad(trim(get_employee_for_print($short_term_data_row['THE_PALLET'], $Arrival_Num, $Customer_Id, $short_term_data_row['ACTIVITY_NUM'], $rf_conn)),6);
	fwrite($handle, $the_pallet.$the_pkgh.$the_weight.$the_size.$the_orig.$the_change.$the_status.$the_damage.$the_container.$the_checker."\n");
	$TotalCases = $TotalCases + $short_term_data_row['THE_CHANGE'];
	$TotalPallets++;

	If ($SignatureCheck <> "NOPICK") {
		// if the sSignatureCheck wasn't already set due to lack of picklist,
		// perform this looping check to see if any pallets don't match the picklist, and throw an exception for later.

		$sql_pl = "SELECT * FROM DC_ORDERDETAIL DO, ".$SignatureSQLAddon." DP WHERE DO.ORDERNUM = DP.ORDERNUM AND DO.ORDERDETAILID = DP.ORDERDETAILID AND DO.ORDERNUM = '".$Order_Num."' AND DP.PACKINGHOUSE = '".trim($the_pkgh)."' AND TO_NUMBER(SIZEHIGH) >= ".trim($the_size)." AND TO_NUMBER(SIZELOW) <= ".trim($the_size);
		// echo $sql_pl." \n";

		$ora_success = ora_parse($short_term_data_cursor_pl, $sql_pl);
		database_check($ora_success, "Unable to get\nPick No-Pick Info\n(US3a)");
		$ora_success = ora_exec($short_term_data_cursor_pl, $sql_pl);
		database_check($ora_success, "Unable to get\nPick No-Pick Info\n(US3b)");
		if (!ora_fetch_into($short_term_data_cursor_pl, $short_term_data_row_pl, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			$SignatureCheck = "SHOW";
		}
	}
}
//fwrite($handle, $The_Weight.$TotalCases.$TotalPallets."\n");

// Print Order Totals 
fwrite($handle, "=========================================================================================================================\n");
fwrite($handle,"Total Cases: ".$TotalCases."          Total Pallets: ".$TotalPallets."\n\n");
/*
$sql_totalpallets = "SELECT COUNT(*) THE_COUNT FROM (SELECT PALLET_ID, SUM(QTY_CHANGE) THE_CHANGE FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$Order_Num."' AND SERVICE_CODE IN (6, 7, 13) AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) GROUP BY PALLET_ID) WHERE THE_CHANGE > 0";
$ora_success = ora_parse($short_term_data_cursor, $sql_totalpallets);
database_check($ora_success, "Unable to get\nTotal Pallets\n(US3a)");
$ora_success = ora_exec($short_term_data_cursor, $sql_totalpallets);
database_check($ora_success, "Unable to get\nTotal Pallets\n(US3b)");
ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$TotalPallets =$short_term_data_row['THE_COUNT'];
fwrite($handle, "Total Pallets: ".$TotalPallets."\n\n");
*/
// If Customer ID is 439 (now 835) print the sub total summary /
//if ($Customer_Id == '439') {
if ($Customer_Id == '835') {
	$sql_cust = "SELECT CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.SERVICE_CODE IN (6, 7, 13) AND CA.ORDER_NUM = '".$Order_Num."' AND CT.ARRIVAL_NUM = '".$Arrival_Num."' AND CT.RECEIVER_ID = '".$Customer_Id."' AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) AND CA.QTY_CHANGE != 0 GROUP BY CT.WEIGHT || CT.WEIGHT_UNIT HAVING SUM(CA.QTY_CHANGE) > 0 ORDER BY CT.WEIGHT || CT.WEIGHT_UNIT";
  
	//echo $sql_cust;

	$ora_success = ora_parse($short_term_data_cursor, $sql_cust);
	database_check($ora_success, "Unable to get\nSub Total Summary\n(US3a)");
	$ora_success = ora_exec($short_term_data_cursor, $sql_cust);
	database_check($ora_success, "Unable to get\nSub Total Summary\n(US3b)");
	//fwrite($handle, "SUBTOTALS BY WEIGHT:\n");
	$H1 = str_pad("WEIGHT", 11);
	$H2 = str_pad("CTNS", 14);
	$H3 = str_pad("PLTS", 12);
	fwrite($handle, $H1.$H2.$H3."\n");
	While(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	$The_Weight = str_pad(trim($short_term_data_row['THE_WEIGHT']), 11);
	$TotalCases =  str_pad(trim($short_term_data_row['THE_CHANGE']), 14);
	$TotalPallets = str_pad(trim($short_term_data_row['THE_PALLETS']), 12);
	fwrite($handle, $The_Weight.$TotalCases.$TotalPallets."\n");
	}
}	

/* Print BOL Sub Totals */
$sql_cust = "SELECT NVL(CT.BOL, 'NA') THE_BOL, COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.SERVICE_CODE IN (6, 7, 13) AND CA.ORDER_NUM = '".$Order_Num."' AND CT.ARRIVAL_NUM = '".$Arrival_Num."' AND CT.RECEIVER_ID = '".$Customer_Id."' AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) AND CA.QTY_CHANGE != 0 GROUP BY CT.BOL HAVING SUM(CA.QTY_CHANGE) > 0 ORDER BY CT.BOL";

//echo $sql_cust;
$ora_success = ora_parse($short_term_data_cursor, $sql_cust);
database_check($ora_success, "Unable to get\nBOL Sub Total Summary\n(US3a)");
$ora_success = ora_exec($short_term_data_cursor, $sql_cust);
database_check($ora_success, "Unable to get\nBOL Sub Total Summary\n(US3b)");
fwrite($handle, "\n");
$H1 = str_pad("BOL", 20);
$H2 = str_pad("CTNS", 14);
$H3 = str_pad("PLTS", 6);
fwrite($handle, $H1.$H2.$H3."\n");
While(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
$The_BOL = str_pad(trim($short_term_data_row['THE_BOL']), 20);
$TotalCases =  str_pad(trim($short_term_data_row['THE_CHANGE']), 14);
$TotalPallets = str_pad(trim($short_term_data_row['THE_PALLETS']), 6);
fwrite($handle, $The_BOL.$TotalCases.$TotalPallets."\n");
}

If ($SignatureCheck == "SHOW") {
	fwrite($handle, "    This order has pallets that are not present on the original picklist. \n      Please obtain an authorized signature. \n      X_________________________________________________________"); 
}        

fclose($handle);

echo "\n... Order Processed.  Please wait for printout in ".$kiosk_name."\n";

$tally_print_command = "lp -o landscape -o cpi=16 -o landscape -h ".$kiosk_ip.":631 /home/kiosk/printouts/".$kiosk_name."/".$filename.".txt";

//echo "\n".$tally_print_command."\n";

system($tally_print_command);
system($tally_print_command);
system($tally_print_command);

exit(0);

?>

