<?
/* Adam Walter, Sep 2013
*
*		A cron job that puts a PO Report's worth of inventory data
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
	$Short_Term_Data = ora_open($conn);

	$sendfile = false;

	// by-PO file
//	$filename2 = date('Ymdhi')."POReport.csv";
	$filename2 = date('Ymd')."_".date('hi')."_POReport.csv";
	$handle2 = fopen($filename2, "w");
	if (!$handle2){
		mail("awalter@port.state.de.us", "DT-daily report FAILED", "See top", "From:  PoW Cron");
		exit;
	}

	fwrite($handle2, "Division,Received,P.O.,Dock Ticket,Paper Code,Railcar,Rolls,Weight,Unit,Damaged,MSF\n");

	$total_rolls = 0;
	$total_lbs = 0;
	$total_rols_dmg = 0;
	
//				AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '10/02/2013'
	$sql = "SELECT SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')) THE_PO, 
				CT.BOL, CT.BATCH_ID, CT.ARRIVAL_NUM, SUM(QTY_RECEIVED) THE_REC, 
				SUM(WEIGHT) THE_WEIGHT, SUM(QTY_DAMAGED) THE_DMG, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') REC_DATE,
				SUM(CT.VARIETY * (CT.CARGO_SIZE / 12) / 1000) THE_MSF
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD 
			WHERE CT.PALLET_ID = CA.PALLET_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.BOL = CA.BATCH_ID 
				AND CA.ACTIVITY_DESCRIPTION IS NULL 
				AND CA.SERVICE_CODE = '8'
				AND REMARK = 'DOLEPAPERSYSTEM'
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CTAD.CLEM_EDI_FTP_DEPOSITED IS NULL
				AND DATE_RECEIVED >= '03-oct-2013'
			GROUP BY SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')), CT.BOL, CT.BATCH_ID, CT.ARRIVAL_NUM 
			ORDER BY THE_PO, CT.BOL";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sendfile = true;
		$total_rolls += $row['THE_SHIPPED'];
		$total_lbs += $row['THE_WEIGHT'];
		$total_rols_dmg += $row['THE_DMG'];

		 
		fwrite($handle2, 
					substr($row['THE_PO'], 0, 3).",".
					$row['THE_REC'].",".
					$row['THE_PO'].",".
					$row['BOL'].",".
					$row['BATCH_ID'].",".
					$row['ARRIVAL_NUM'].",".
					$row['THE_SHIPPED'].",".
					$row['THE_WEIGHT'].",".
					"LB,".
					$row['THE_DMG'].",".
					round($row['THE_MSF'], 3)."\n");
	}

	fclose($handle2);

	// deposit file... 
	if($sendfile === true){
		$connection = ftp_connect("www.portofwilmington.com");
		if($connection != FALSE){
			$login_status = ftp_login($connection, "dole", "12dole345");
			if($login_status != FALSE){
				if(ftp_put($connection, $filename2, $filename2, FTP_BINARY)){ // this will either move the files, or fail.  if moved...
					
//									AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '10/02/2013'
					$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
							SET CLEM_EDI_FTP_DEPOSITED = SYSDATE
							WHERE (PALLET_ID, ARRIVAL_NUM, RECEIVER_ID) IN
								(SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CT.RECEIVER_ID
								FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
								WHERE CT.PALLET_ID = CA.PALLET_ID 
									AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CA.CUSTOMER_ID
									AND CT.BOL = CA.BATCH_ID 
									AND CA.ACTIVITY_DESCRIPTION IS NULL 
									AND CA.SERVICE_CODE = '8'
									AND REMARK = 'DOLEPAPERSYSTEM'
									AND DATE_RECEIVED >= '03-oct-2013'
								)
								AND CLEM_EDI_FTP_DEPOSITED IS NULL";
					ora_parse($Short_Term_Data, $sql);
					ora_exec($Short_Term_Data);

					system("/bin/mv $filename2 /web/web_pages/inventory/crons/DTFTPdeposits/success".$filename2);
				} else { // ftp didn't work.  move file, do nothing.
					echo "could not deposit file\n";
					mail("awalter@port.state.de.us", "Dole Inbound Paper FTP FAILED - Could Not Place File in Directory", "See top", "From:  PoW Cron");
					system("/bin/mv $filename2 /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename2);
				}
			} else {
				echo "could not log in\n";
				mail("awalter@port.state.de.us", "Dole Inbound Paper FTP FAILED - could not log in", "See top", "From:  PoW Cron");
				system("/bin/mv $filename2 /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename2);
			}
		} else {
			echo "could not connect\n";
			mail("awalter@port.state.de.us", "Dole Inbound Paper FTP FAILED - could not connect", "See top", "From:  PoW Cron");
			system("/bin/mv $filename2 /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename2);
		}
	} else {
		// do nothing, no file
	}