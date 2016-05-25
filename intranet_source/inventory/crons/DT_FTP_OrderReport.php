<?
/* Adam Walter, Sep 2013
*
*		A cron job that puts an Order Report's worth of inventory data
*		Into an FTP box for Dole Paper's DOCKTICKET program.
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
	$upper_cursor = ora_open($conn);
	$Short_Term_Data = ora_open($conn);

	$sql = "SELECT DISTINCT CONTAINER_ID, ORDER_NUM
			FROM DOLEPAPER_ORDER
			WHERE STATUS IN ('6', '7')
				AND EDI_SENT IS NULL";
	ora_parse($upper_cursor, $sql);
	ora_exec($upper_cursor);
	while(ora_fetch_into($upper_cursor, $upper_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

//		$filename = $upper_row['CONTAINER_ID']."OrderReport".$upper_row['ORDER_NUM'].".csv";
		$filename = date('Ymd')."_".date('hi')."_OrderReport_".$upper_row['ORDER_NUM']."_".$upper_row['CONTAINER_ID'].".csv";
		$handle = fopen($filename, "w");
		if (!$handle){
			mail("awalter@port.state.de.us", "DT-Order report FAILED", "See top", "From:  PoW Cron");
			exit;
		}

		fwrite($handle, "Load Date,Vessel,Voyage,Destination,Order,Container,Dock Ticket,Paper Code,Rolls,Weight,S-TON,KG,Seal,Booking #\n");

		// by-Order file
		$sql = "SELECT DO.CONTAINER_ID, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_LOAD, DD.DESTINATION, VOY.VOYAGE_NUM,
					DO.ORDER_NUM, CT.BOL, CT.BATCH_ID, SUM(QTY_CHANGE) THE_SHIPPED, SUM(WEIGHT) THE_WEIGHT, VP.VESSEL_NAME, SEAL, BOOKING_NUM 
				FROM DOLEPAPER_ORDER DO, CARGO_TRACKING CT, CARGO_ACTIVITY CA, DOLEPAPER_DESTINATIONS DD, VESSEL_PROFILE VP, VOYAGE VOY
				WHERE CT.PALLET_ID = CA.PALLET_ID 
					AND CT.BOL = CA.BATCH_ID 
					AND CA.ORDER_NUM = TO_CHAR(DO.ORDER_NUM)
					AND DO.DESTINATION_NB = DD.DESTINATION_NB
					AND DO.ARRIVAL_NUM = VP.LR_NUM
					AND VOY.LR_NUM = VP.LR_NUM
					AND CA.ACTIVITY_DESCRIPTION IS NULL 
					AND CA.SERVICE_CODE = '6' 
					AND DO.EDI_SENT IS NULL 
					AND DO.STATUS IN ('6', '7')
					AND DO.CONTAINER_ID = '".$upper_row['CONTAINER_ID']."'
					AND DO.ORDER_NUM = '".$upper_row['ORDER_NUM']."'
					GROUP BY DO.CONTAINER_ID, DO.ORDER_NUM, CT.BOL, CT.BATCH_ID, SEAL, BOOKING_NUM,
						TO_CHAR(DO.LOAD_DATE, 'MM/DD/YYYY'), DD.DESTINATION, VP.VESSEL_NAME, VOY.VOYAGE_NUM
					ORDER BY VP.VESSEL_NAME, DD.DESTINATION, MIN(DATE_OF_ACTIVITY), DO.ORDER_NUM, DO.CONTAINER_ID, CT.BOL";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fwrite($handle,
					$row['THE_LOAD'].",".
					$row['VESSEL_NAME'].",".
					$row['VOYAGE_NUM'].",".
					$row['DESTINATION'].",".
					$row['ORDER_NUM'].",".
					$row['CONTAINER_ID'].",".
					$row['BOL'].",".
					$row['BATCH_ID'].",".
					$row['THE_SHIPPED'].",".
					$row['THE_WEIGHT'].",".
					round($row['THE_WEIGHT'] / 2000, 2).",".
					round($row['THE_WEIGHT'] / 2.2, 2).",".
					$row['SEAL'].",".
					$row['BOOKING_NUM']."\n");
		}
		fclose($handle);

		// deposit file...
		$connection = ftp_connect("www.portofwilmington.com");
		if($connection != FALSE){
			$login_status = ftp_login($connection, "dole", "12dole345");
			if($login_status != FALSE){
				if(ftp_put($connection, $filename, $filename, FTP_BINARY)){ // this will either move the file, or fail.  if moved...
					
					$sql = "UPDATE DOLEPAPER_ORDER
							SET EDI_SENT = SYSDATE
							WHERE STATUS IN ('6', '7')
								AND EDI_SENT IS NULL
								AND CONTAINER_ID = '".$upper_row['CONTAINER_ID']."'
								AND ORDER_NUM = '".$upper_row['ORDER_NUM']."'";
					ora_parse($Short_Term_Data, $sql);
					ora_exec($Short_Term_Data);

					ftp_close($connection);
					echo "Success on Container ".$upper_row['CONTAINER_ID']." --- Order ".$upper_row['ORDER_NUM']."\n";
					system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/success".$filename);
				} else { // ftp didn't work.  move file, do nothing.
					echo "could not deposit file\n";
					ftp_close($connection);
					system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename);
				}
			} else {
				echo "could not log in\n";
				ftp_close($connection);
				system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename);
			}
		} else {
			echo "could not connect\n";
			system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename);
		}
	}
