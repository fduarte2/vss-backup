<?
/* Adam Walter, 10/24/08
*
*	This is cronned to run at regular (1 hour) intervals.
*	It makes a file similar to the "shipped out" one in the
*	Clementine_daily_stuff.php script, and ftp-deposits them.
*
*	Updated Oct 29 2010:  ATIVITY_DESCRIPTION is now null for normal shipments,
*	Just like Chilean fruit.
*********************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Data = ora_open($conn);
	$date = date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
	$datetext = date('m-d-Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));

	// step 1:  sql for the file:
/*	$sql = "SELECT CT.PALLET_ID THE_PALLET, DECODE(CT.CARGO_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_STATUS, 
				TRIM(DCO.ORDERNUM) THE_ORDER, DCCON.CONSIGNEENAME THE_CONSIGNEE, DCCUST.CUSTOMERID CUST_ID, DCCON.CONSIGNEEID CON_ID,
				NVL(DCO.CUSTOMERPO, 'NO PO') THE_PO, DCO.LOADTYPE THE_LOADTYPE, DCCUST.CUSTOMERNAME THE_CUST, VP.VESSEL_NAME THE_VES
			FROM CARGO_TRACKING CT, DC_ORDER DCO, DC_CUSTOMER DCCUST, DC_CONSIGNEE DCCON, CARGO_ACTIVITY CA, VESSEL_PROFILE VP
			WHERE CT.PALLET_ID = CA.PALLET_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND DCO.VESSELID = VP.LR_NUM
				AND LENGTH(CT.EXPORTER_CODE) = 4
				AND CT.COMMODITY_CODE LIKE '560%'
				AND CA.SERVICE_CODE = '6'
				AND CA.ACTIVITY_DESCRIPTION IS NULL
				AND CA.ORDER_NUM = TRIM(DCO.ORDERNUM)
				AND DCO.CUSTOMERID = DCCUST.CUSTOMERID
				AND DCO.CONSIGNEEID = DCCON.CONSIGNEEID
				AND DCO.ORDERSTATUSID = '9'
				AND CT.ARRIVAL_NUM != '4321'
				AND CA.ORDER_NUM NOT IN (SELECT ORDER_NUM FROM DC_EDI_SHIP_OUT)";*/
















	$filename = "outbound.txt";
	$handle = fopen($filename, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	$sql = "SELECT CA.ORDER_NUM, CT.PALLET_ID, CT.WEIGHT, CT.CARGO_SIZE, CT.QTY_RECEIVED, CA.QTY_CHANGE
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				AND CT.RECEIVER_ID = 835
				AND CT.DATE_RECEIVED IS NOT NULL
				AND CA.SERVICE_CODE = 6
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
			ORDER BY CA.ORDER_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		exit;
	} else {

		// step 2:  write the file
//		$filename = "RecentShipOut".date('mdYhi').".txt";

		do {
//			$temp = split("-", $row['THE_VES']);
//			$vessel_id = $temp[0];
//			$cust_id = $row['CUST_ID'];
//			$conn_id = $row['CON_ID'];

			fwrite($handle, $row['ORDER_NUM'].",".$row['PALLET_ID'].",".$row['WEIGHT'].",".$row['CARGO_SIZE'].",".$row['QTY_RECEIVED'].",".$row['QTY_CHANGE'].";\n");
//			fwrite($handle, $vessel_id.",".$row['THE_PALLET'].",".$row['THE_ORDER'].",".$cust_id.",".$row['THE_CUST'].",".$conn_id.",".$row['THE_CONSIGNEE'].",".$row['THE_LOADTYPE'].",".$row['THE_STATUS'].",".$row['THE_PO'].";\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	fclose($handle);

	// step 3:  with file, FTP deposit it
//	$connection = ftp_connect("196.217.243.242");
	$connection = ftp_connect("72.38.242.30");
	if($connection != FALSE){
//		$login_status = ftp_login($connection, "inigo", "usport08");
		$login_status = ftp_login($connection, "POW", "Ippolito");
		if($login_status != FALSE){
			if(ftp_chdir($connection, "Outbound") && ftp_put($connection, $filename, $filename, FTP_BINARY)){ // this will either move the file, or fail.  if moved...
/*				
				$sql = "SELECT DISTINCT ORDER_NUM FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT 
						WHERE CA.ORDER_NUM NOT IN (SELECT ORDER_NUM FROM DC_EDI_SHIP_OUT)
							AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
							AND CT.COMMODITY_CODE LIKE '560%'
							AND CA.SERVICE_CODE = '6'
							AND CA.ACTIVITY_DESCRIPTION IS NULL
							AND CT.PALLET_ID = CA.PALLET_ID
							AND CT.ARRIVAL_NUM != '4321'
							AND LENGTH(CT.EXPORTER_CODE) = 4";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$sql = "INSERT INTO DC_EDI_SHIP_OUT (ORDER_NUM, TIMESTAMP) VALUES ('".$row['ORDER_NUM']."', SYSDATE)";
					ora_parse($Short_Term_Data, $sql);
					ora_exec($Short_Term_Data);
				}
*/
				system("/bin/mv $filename ~/ClemFiles/success".$filename.$datetext);
			} else { // ftp didn't work.  move file, do nothing.
				echo "Could Not Place ClemFile in Directory\n";
				mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED - Could Not Place ClemFile in Directory", "See top", "From:  PoW Cron");
				system("/bin/mv $filename ~/ClemFiles/failed".$filename.$datetext);
			}
		} else {
			echo "could not log in\n";
			mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED - could not log in", "See top", "From:  PoW Cron");
			system("/bin/mv $filename ~/ClemFiles/failed".$filename.$datetext);
		}
	} else {
		echo "could not connect\n";
		mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED - could not connect", "See top", "From:  PoW Cron");
		system("/bin/mv $filename ~/ClemFiles/failed".$filename.$datetext);
	}





	// second half:  uploaded pallets, via raw file string
	$filename = "PalletFile.txt";
	$handle = fopen($filename, "w");
	if (!$handle){
		mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	$sql = "SELECT *
			FROM CARGO_TRACKING_ADDITIONAL_DATA CT
			WHERE CT.RECEIVER_ID = 835
				AND CT.CLEM_CT_RAWSTRING IS NOT NULL
				AND CLEM_CT_UPLOAD_TOFTP IS NULL
			ORDER BY CT.CLEM_CT_RAWSTRING";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		exit; // no new data, end script
	} else {

		// step 2:  write the file

		do {
			fwrite($handle, $row['CLEM_CT_RAWSTRING'].";\n");
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	fclose($handle);

	// step 3:  with file, FTP deposit it
	$connection = ftp_connect("72.38.242.30");
	if($connection != FALSE){
		$login_status = ftp_login($connection, "POW", "Ippolito");
		if($login_status != FALSE){
			if(ftp_chdir($connection, "PalletFile") && ftp_put($connection, $filename, $filename, FTP_BINARY)){ // this will either move the file, or fail.  if moved...
				
				$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
						SET CLEM_CT_UPLOAD_TOFTP = SYSDATE
						WHERE RECEIVER_ID = 835
							AND CLEM_CT_RAWSTRING IS NOT NULL
							AND CLEM_CT_UPLOAD_TOFTP IS NULL";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				system("/bin/mv $filename ~/ClemFiles/success".$filename.$datetext);
			} else { // ftp didn't work.  move file, do nothing.
				echo "Could Not Place ClemFile in Directory\n";
				mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED - Could Not Place ClemFile in Directory", "See top", "From:  PoW Cron");
				system("/bin/mv $filename ~/ClemFiles/failed".$filename.$datetext);
			}
		} else {
			echo "could not log in\n";
			mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED - could not log in", "See top", "From:  PoW Cron");
			system("/bin/mv $filename ~/ClemFiles/failed".$filename.$datetext);
		}
	} else {
		echo "could not connect\n";
		mail("awalter@port.state.de.us", "Clementine nightly info shipped FTP FAILED - could not connect", "See top", "From:  PoW Cron");
		system("/bin/mv $filename ~/ClemFiles/failed".$filename.$datetext);
	}
