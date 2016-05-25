<?
/* Adam Walter, 3/10/08
*
*	This file creates a .csv file that is sent to Kopke customer 1131
*	Showing their in-house inventory as of the time of it's running.
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

	$filename1 = "/web/web_pages/TS_Program/kopke_email/pltsIntoPOW".date('mdY').".csv";
	$handle = fopen($filename1, "w");
	if (!$handle){
//		echo "could not create kopke file";
		mail("awalter@port.state.de.us", "Kopke daily info into-inventory FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	$print_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));

//	$sql = "SELECT PALLET_ID, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE, QTY_RECEIVED, QTY_IN_HOUSE, WAREHOUSE_LOCATION FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND QTY_IN_HOUSE > 0 AND RECEIVER_ID = '1131' AND ARRIVAL_NUM != '4321' ORDER BY PALLET_ID";
	$sql = "SELECT PALLET_ID, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE, WAREHOUSE_LOCATION,
			CT.ARRIVAL_NUM, NVL(VESSEL_NAME, 'TRUCKEDIN') THE_VES
			FROM CARGO_TRACKING CT, VESSEL_PROFILE VP
			WHERE CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
			AND DATE_RECEIVED IS NOT NULL
			AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$print_date."'
			AND RECEIVER_ID = '1131'
			ORDER BY CT.ARRIVAL_NUM, WAREHOUSE_LOCATION, PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		fwrite($handle, "No Pallets received on ".$print_date);
	} else {
		do {
//			fwrite($handle, $row['PALLET_ID'].",".$row['QTY_IN_HOUSE'].",".$row['WAREHOUSE_LOCATION']."\n");
//			fwrite($handle, $row['PALLET_ID'].",".$row['THE_DATE'].",".$row['QTY_RECEIVED'].",".$row['QTY_IN_HOUSE']."\n");
			fwrite($handle, $row['ARRIVAL_NUM'].",".$row['THE_VES'].",".$row['WAREHOUSE_LOCATION'].",".$row['PALLET_ID'].",".$row['THE_DATE']."\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	fclose($handle);

//	$cmd = "echo \" \" | mutt -s \"Port inventory, received ".$print_date."\" -a ".$filename1." awalter@port.state.de.us";
	$cmd = "echo \" \" | mutt -s \"Port inventory, received ".$print_date."\" -a ".$filename1." awalter@port.state.de.us -c sadu@port.state.de.us -c ithomas@port.state.de.us";

//	$cmd = "echo \" \" | mutt -s \"Port inventory, received ".$print_date."\" -a ".$filename1." powinv@kopkefruit.com -c bdempsey@port.state.de.us -b sadu@port.state.de.us -b hdadmin@port.state.de.us -b awalter@port.state.de.us";
	system($cmd);
?>