<?
/* Stephen Adu 12/22/2010
*
*	This file creates a .xls file that is sent to Vandenberg customer 2206
*	Showing their in-house inventory after current vessel discharge
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
//	$Short_Data = ora_open($conn2);
	$filename1 = "/web/web_pages/general_crons/VandenbergPltsRcvd".date('mdY', mktime(0,0,0,date('m'), date('d') - 1, date('Y'))).".csv";
	$handle = fopen($filename1, "w");
	if (!$handle){
//		echo "could not create Vandenberg file";
		mail("sadu@port.state.de.us", "Van Vessel Info FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	$print_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));

		$sql = "select arrival_num, pallet_id, qty_received, to_char(date_received, 'mm/dd/yyyy') DATE_SCANNED, 
		to_char(date_received, 'HH:MI:SSAM ') TIME_SCANNED from cargo_tracking
		where to_char(date_received, 'mm/dd/yyyy') = to_char(sysdate - 1, 'mm/dd/yyyy')
		and receiver_id = 2206
		and arrival_num != '4321'
		and date_received is not null
		order by date_received asc";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		fwrite($handle, "No Pallets in this Vessel ".$print_date);
	} else {
		fwrite($handle, "PALLET ID,QTY_RECEIVED,DATE SCANNED,TIME SCANNED\n");
		do {
			fwrite($handle, $row['PALLET_ID'].",".$row['QTY_RECEIVED'].",".$row['DATE_SCANNED'].",".$row['TIME_SCANNED']."\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	fclose($handle);

	$cmd = "echo \" \" | mutt -s \"Vandenberg Vessel Data ".$print_date."\" -a ".$filename1." jmcewing@jacvandenberg.com -c tdeissler@jacvandenberg.com -b sadu@port.state.de.us -b hdadmin@port.state.de.us";
//	$cmd = "echo \" \" | mutt -s \"Vandenberg Vessel Data ".$print_date."\" -a ".$filename1." awalter@port.state.de.us -c ithomas@port.state.de.us";
	system($cmd);

	$cmd_remove = "rm -f ".$filename1;
	system($cmd_remove);
?>