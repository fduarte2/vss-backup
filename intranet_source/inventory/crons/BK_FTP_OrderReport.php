<?
/* Adam Walter, Apr 2016
*
*	Booking paper report for customer
*********************************************************************************/

//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");

	$sql = "SELECT DISTINCT CONTAINER_ID, ORDER_NUM
			FROM BOOKING_ORDERS
			WHERE STATUS IN ('6', '7')
				AND EDI_SENT IS NULL";
	$orders = ociparse($rf_conn, $sql);
	ociexecute($orders);
	while(ocifetch($orders)){

		$filename = date('Ymd')."_".date('hi')."_CC_OrderReport_".ociresult($orders, "ORDER_NUM")."_".ociresult($orders, "CONTAINER_ID").".csv";
		$handle = fopen($filename, "w");
		if (!$handle){
			mail("awalter@port.state.de.us", "BK-Order report FAILED", "See top", "From:  PoW Cron");
			exit;
		}

		fwrite($handle, "Load Date,Vessel,Voyage,Destination,Order,Container,,Paper Code,Rolls,Weight,S-TON,KG,Seal,Booking #\n");

		// by-Order file
		$sql = "SELECT TO_CHAR(MIN(CA.DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_LOAD, VP.VESSEL_NAME, VOY.VOYAGE_NUM, BO.ORDER_NUM, BO.CONTAINER_ID, 
                    BOD.SSCC_GRADE_CODE, SUM(QTY_CHANGE) THE_SHIPPED,  SUM(WEIGHT) THE_KG, BO.SEAL_NUM, BOD.BOOKING_NUM
                FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, VESSEL_PROFILE VP, VOYAGE VOY, BOOKING_ORDER_DETAILS BOD, BOOKING_ORDERS BO, 
                    BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
                WHERE CT.PALLET_ID = CA.PALLET_ID 
                    AND CA.ORDER_NUM = TO_CHAR(BO.ORDER_NUM)
                    AND BO.ARRIVAL_NUM = VP.LR_NUM
                    AND BO.STATUS IN ('6', '7')
                    AND BO.CONTAINER_ID = '".ociresult($orders, "CONTAINER_ID")."'
                    AND BO.ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."'
                    AND CA.ACTIVITY_DESCRIPTION IS NULL 
                    AND CA.SERVICE_CODE = '6' 
                    AND VOY.LR_NUM = VP.LR_NUM
                    AND BO.ORDER_NUM = BOD.ORDER_NUM
                    AND CT.PALLET_ID = BAD.PALLET_ID
                    AND CT.RECEIVER_ID = BAD.RECEIVER_ID
                    AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
                    AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
                    AND BPGC.SSCC_GRADE_CODE = BOD.SSCC_GRADE_CODE
                    AND BAD.BOOKING_NUM = BOD.BOOKING_NUM
                    AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = BOD.WIDTH
                    AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = BOD.DIA
                    GROUP BY BO.CONTAINER_ID, BO.ORDER_NUM, BOD.SSCC_GRADE_CODE, BO.SEAL_NUM, BOD.BOOKING_NUM,
                        TO_CHAR(BO.LOAD_DATE, 'MM/DD/YYYY'), VP.VESSEL_NAME, VOY.VOYAGE_NUM
                    ORDER BY VP.VESSEL_NAME, MIN(DATE_OF_ACTIVITY), BO.ORDER_NUM, BO.CONTAINER_ID";
		$detail_lines = ociparse($rf_conn, $sql);
		ociexecute($detail_lines);
		while(ocifetch($detail_lines)){
			fwrite($handle,
					ociresult($detail_lines, "THE_LOAD").",".
					ociresult($detail_lines, "VESSEL_NAME").",".
					ociresult($detail_lines, "VOYAGE_NUM").",".
					",".
					ociresult($detail_lines, "ORDER_NUM").",".
					ociresult($detail_lines, "CONTAINER_ID").",".
					",".
					ociresult($detail_lines, "SSCC_GRADE_CODE").",".
					ociresult($detail_lines, "THE_SHIPPED").",".
					round(ociresult($detail_lines, "THE_KG") * 2.2, 2).",".
					round((ociresult($detail_lines, "THE_KG") * 2.2) / 2000, 2).",".
					ociresult($detail_lines, "THE_KG").",".
					ociresult($detail_lines, "SEAL_NUM").",".
					ociresult($detail_lines, "BOOKING_NUM")."\n");
		}
		fclose($handle);

//		echo "order ".ociresult($orders, "ORDER_NUM")." cont ".ociresult($orders, "CONTAINER_ID")." done.\n";

		// deposit file...
		$connection = ftp_connect("www.portofwilmington.com");
		if($connection != FALSE){
			$login_status = ftp_login($connection, "dole", "12dole345");
			if($login_status != FALSE){
				if(ftp_put($connection, $filename, $filename, FTP_BINARY)){ // this will either move the file, or fail.  if moved...
					
					$sql = "UPDATE BOOKING_ORDERS
							SET EDI_SENT = SYSDATE
							WHERE STATUS IN ('6', '7')
								AND EDI_SENT IS NULL
								AND CONTAINER_ID = '".ociresult($orders, "CONTAINER_ID")."'
								AND ORDER_NUM = '".ociresult($orders, "ORDER_NUM")."'";
					$update = ociparse($rf_conn, $sql);
					ociexecute($update);

					ftp_close($connection);
//					echo "Success on Container ".$upper_row['CONTAINER_ID']." --- Order ".$upper_row['ORDER_NUM']."\n";
					system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/success".$filename);
				} else { // ftp didn't work.  move file, do nothing.
//					echo "could not deposit file\n";
					ftp_close($connection);
					system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename);
				}
			} else {
//				echo "could not log in\n";
				ftp_close($connection);
				system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename);
			}
		} else {
//			echo "could not connect\n";
			system("/bin/mv $filename /web/web_pages/inventory/crons/DTFTPdeposits/failed".$filename);
		}
	}
