<?
/* Adam Walter, 8/25/07
*	This script creates 4 files, and then mails them to Inigo
*	For the Clementine System.
*
*	This program is the same as clementine_daily_stuff.php, except that it also
*	Reads from a file a list of pallet ID's and only includes in the output
*	Pallets in said file.  Should only be run once, but will leave source code
*	Around in case.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);

	$print_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));
//	$ora_date = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
	$print_yesterday = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));

	$readDataArray = array();
	$readCounter = 1;
	$readFilename = "19Pal.txt";
	$handle = fopen($readFilename, "r");
	while(!feof($handle)){
		$readDataArray[$readCounter] = substr(trim(fgets($handle)), 10);
//		$readDataArray[$readCounter] = trim(fgets($handle));
		$readCounter++;
	}
	echo count($readDataArray);
	fclose($handle);



	$filename1 = "InventoryRunOnce.txt";
	$handle = fopen($filename1, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info all-inventory FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	// file #1:  all inhouse pallets
	$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND (MARK IS NULL OR MARK != 'SHIPPED') ORDER BY PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		fwrite($handle, "No Pallets in Inventory as of ".$print_date);
		fclose($handle);
	} else {
		do {
			if($index = array_search($row['PALLET_ID'], $readDataArray)){
//				echo $row['PALLET_ID']."   ".$readDataArray[$index]."   ".$index."\n";
				fwrite($handle, $row['PALLET_ID']."\n");
			}
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fclose($handle);
	}



	$filename2 = "RegradeHospitalRunOnce.txt";
	$handle = fopen($filename2, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info hosp-inventory FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	// file #2:  all non-good pallets
	$sql = "SELECT PALLET_ID, DECODE(CARGO_STATUS, 'H', 'HOSPITAL', 'R', 'REGRADE', 'B', 'BOTH', 'ERROR') THE_STATUS FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS IN ('H', 'B', 'R') ORDER BY PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		fwrite($handle, "No Pallets in Hospital Inventory as of ".$print_date);
		fclose($handle);
	} else {
		do {
			if(array_search(substr($row['PALLET_ID'], 10), $readDataArray)){
				fwrite($handle, $row['PALLET_ID'].",".$row['THE_STATUS']."\n");
			}
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fclose($handle);
	}



	$filename3 = "ShippedYesterdayRunOnce.txt";
	$handle = fopen($filename3, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info shipped FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	// file #3:  all shipped YESTERDAY pallets
	$sql = "SELECT CT.PALLET_ID THE_PALLET, DECODE(CT.CARGO_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_STATUS, 
			TRIM(DCO.ORDERNUM) THE_ORDER, DCCON.CONSIGNEENAME THE_CONSIGNEE, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_DATE,
			NVL(DCO.CUSTOMERPO, 'NO PO') THE_PO, DCO.LOADTYPE THE_LOADTYPE, DCCUST.CUSTOMERNAME THE_CUST
			FROM CARGO_TRACKING CT, DC_ORDER DCO, DC_CUSTOMER DCCUST, DC_CONSIGNEE DCCON, CARGO_ACTIVITY CA
			WHERE CT.PALLET_ID = CA.PALLET_ID
			AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
			AND LENGTH(CT.EXPORTER_CODE) = 4
			AND CT.COMMODITY_CODE LIKE '560%'
			AND CA.SERVICE_CODE = '6'
			AND SUBSTR(CA.ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
			AND CA.ORDER_NUM = TRIM(DCO.ORDERNUM)
			AND DCO.CUSTOMERID = DCCUST.CUSTOMERID
			AND DCO.CONSIGNEEID = DCCON.CONSIGNEEID";
//			AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$print_yesterday."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		fwrite($handle, "No Pallets shipped on ".$print_yesterday);
		fclose($handle);
	} else {
		$counter = 0;
		do {
//			echo array_search(substr($row['THE_PALLET'], 10), $readDataArray)."  ".substr($row['THE_PALLET'], 10)." ".$counter."\n";
			if($index = array_search(substr($row['THE_PALLET'], 10), $readDataArray) && substr($row['THE_PALLET'], 0, 3) != "POW"){
				echo substr($row['THE_PALLET'], 10)."   ".$readDataArray[$index]."   ".$index."\n";
				fwrite($handle, $row['THE_PALLET'].",".$row['THE_ORDER'].",".$row['THE_CUST'].",".$row['THE_CONSIGNEE'].",".$row['THE_LOADTYPE'].",".$row['THE_STATUS'].",".$row['THE_PO'].",".$row['THE_DATE']."\n");
			}
//			$counter++;
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fclose($handle);
	}


	$filename4 = "UnloadedYesterdayRunOnce.txt";
	$handle = fopen($filename4, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine daily info unloaded FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	// file #4:  all unloaded YESTERDAY pallets
	$sql = "SELECT PALLET_ID, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE FROM CARGO_TRACKING WHERE COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND (MARK IS NULL OR MARK != 'SHIPPED') ORDER BY PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		fwrite($handle, "No Pallets Unloaded From Ship on ".$print_yesterday);
		fclose($handle);
	} else {
		do {
			if(array_search(substr($row['PALLET_ID'], 10), $readDataArray)){
				fwrite($handle, $row['PALLET_ID'].",".$row['THE_DATE']."\n");
			}
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		fclose($handle);
	}

	$cmd = "echo \" \" | mutt -s \"Port records, ".$print_date."\" -a ".$filename1." -a ".$filename2." -a ".$filename3." -a ".$filename4."  awalter@port.state.de.us ithomas@port.state.de.us";

//	$cmd = "echo \" \" | mutt -s \"Port records, ".$print_date."\" -a ".$filename1." -a ".$filename2." -a ".$filename3." -a ".$filename4." souhnoun@menara.ma n.laayouni@freshfruitmorocco.org laila@transinformatique.com -c ithomas@port.state.de.us -c awalter@port.state.de.us -c martym@port.state.de.us -c ltreut@port.state.de.us -c s.bricks@dominioncitrus.com";
	system($cmd);
	
?>


