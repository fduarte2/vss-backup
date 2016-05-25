<?
/*
*	May, 2009.
*
*	Intranet page that dumps Pallet info for an order to 
*	Us
*
************************************************************************************/

//	$path = "/web/web_pages/inventory/oppyEDIconfirm/truckSTUFF";
	$path = "/web/web_pages/TS_Testing/oppyEDIconfirm/truckSTUFF";

//	$mailTO = "awalter@port.state.de.us\r\n";
//	$mailTO = "ltreut@port.state.de.us,martym@port.state.de.us,bdempsey@port.state.de.us\r\n";
	$mailTO = "awalter@port.state.de.us,ltreut@port.state.de.us,draczkowski@port.state.de.us,bdempsey@port.state.de.us,schapman@port.state.de.us,mbillin@port.state.de.us,lsanchez@port.state.de.us,mslowe@port.state.de.us,scorbin@port.state.de.us\r\n";
	$mailHeaders = "From:  PoWMailServer@port.state.de.us\r\n";
//	$mailHeaders .= "CC:  ithomas@port.state.de.us\r\n";
	$mailHeaders .= "BCC:  awalter@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us\r\n";

	$body = "";

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{
	   	if ($dName == "."  || $dName == ".." || $dName == "oppyEDI_truck_stuff.php" || is_dir($dName)){
			// do nothing to directories.
		} else{
			$fp = fopen($dName, "r");
			$body = "";
			$body_extra_instructions = "";

			$mail_sub_order_list = "";
	
			while($line = fgets($fp)){
				if(substr($line, 0, 1) == "H"){
					$load = trim(substr($line, 36, 10));
					$mailSubject = "Truck PickList Details, Load#:  ".$load;
					$temperature = trim(substr($line, 67, 6));
					if($temperature == "" || $temperature == 0){
						$body_extra_instructions .= "PLEASE CHECK TEMPERATURE (MISSING/INCORRECT)\r\n";
					}

//					$temp = trim(substr($line, 67, 6));
//					$temp = "Withdrawal/BOL must show Temp of 33 Deg F";
//					$body .= "Load#:  ".$load."\r\n";

					// make sure "Step 1" (truck check-in) was done...
					$sql = "SELECT COUNT(*) THE_COUNT FROM OPPY_TRUCK_INFO WHERE LOAD_NUM = '".$load."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($row['THE_COUNT'] == 0){
						error_out($mailTO, $mailHeaders, $mailSubject." FAILED", "No Truck Check-In on record for this load", $dName);
					}


					// make sure this load hasn't already been verified
					$sql = "SELECT COUNT(*) THE_COUNT FROM OPPY_PER_ORDER WHERE LOAD_NUM = '".$load."' AND VERIFY_RECEIVED IS NOT NULL";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($row['THE_COUNT'] > 0){
						error_out($mailTO, $mailHeaders, $mailSubject." FAILED", "Load already verified for shipout; cannot accept new picklist.  Please Contact Oppenheimer", $dName);
					}

					// if at this point, proceed... update table 1, clear 2 and 3 (just in case there is data there, normally there isn't)
					$sql = "UPDATE OPPY_TRUCK_INFO SET TEMPERATURE_F = '".$temperature."', VALID_REC_ON = SYSDATE WHERE LOAD_NUM = '".$load."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					$sql = "DELETE FROM OPPY_ORDER_INFO WHERE ORDER_NUM IN (SELECT ORDER_NUM FROM OPPY_PER_ORDER WHERE LOAD_NUM = '".$load."')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					$sql = "DELETE FROM OPPY_PER_ORDER WHERE LOAD_NUM = '".$load."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
				}
				if(substr($line, 0, 1) == "O"){
					$order = trim(substr($line, 1, 10));
					$P_O = trim(substr($line, 22, 20));
					$ship_to_1 = str_replace("'", "`", trim(substr($line, 58, 35)));
					$ship_to_2 = str_replace("'", "`", trim(substr($line, 93, 35)));
					$ship_to_3 = str_replace("'", "`", trim(substr($line, 128, 35)));
					$ship_to_4 = trim(substr($line, 163, 20));
					$ship_to_5 = trim(substr($line, 183, 2));
					$ship_to_6 = trim(substr($line, 185, 10));
//					$ship_to = ereg_replace("[ ]+", " ", $ship_to);
					if($P_O == ""){
						$P_O = "None Specified.";
					}

					$mail_sub_order_list .= $order." ";

					$body .= "Order#:  ".$order."\r\nPO#  ".$P_O."\r\nTemperature:  ".$temperature."\r\n";
					$body .= "Ship To:\r\n";
					$body .= "\tName: ".$ship_to_1."\r\n";
					$body .= "\tAddr1: ".$ship_to_2."\r\n";
					$body .= "\tAddr2: ".$ship_to_3."\r\n";
					$body .= "\tCity: ".$ship_to_4."\r\n";
					$body .= "\tState: ".$ship_to_5."\r\n";
					$body .= "\tZIP: ".$ship_to_6."\r\n";

					$sql = "INSERT INTO OPPY_PER_ORDER 
						(LOAD_NUM,
						ORDER_NUM,
						DELIVER_TO,
						ADDRESS1,
						ADDRESS2,
						DELIVERY_CITY,
						DELIVERY_STATE,
						DELIVERY_ZIP)
						VALUES
						('".$load."',
						'".$order."',
						'".$ship_to_1."',
						'".$ship_to_2."',
						'".$ship_to_3."',
						'".$ship_to_4."',
						'".$ship_to_5."',
						'".$ship_to_6."')";	
					ora_parse($cursor, $sql);
					ora_exec($cursor);

				}
				if(substr($line, 0, 1) == "N"){
					$body .= "SPECIAL NOTE:  ".trim(substr($line, 11, 60))."\r\n";
				}
				if(substr($line, 0, 1) == "C"){
					// "C" might have a number of uses, the only one we care about is temperatures
					// so check if it is for a temperature...
					$type = trim(substr($line, 11, 10));
					$temp_order = trim(substr($line, 1, 10));
					$descrip = str_replace("'", "`", trim(substr($line, 21, 40)));
					$sql = "SELECT COUNT(*) THE_COUNT FROM LU_OPPY_VALID_TEMP_RECORDERS
							WHERE EDI_C_TEMP_CHECK = '".$type."'";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					if($row['THE_COUNT'] >= 1){
						// yup, it's a temperature request.  insert into associated table
						$sql = "INSERT INTO OPPY_ORDER_TEMP_REQUESTS
									(ORDER_NUM, DESCRIP)
								VALUES
									('".$temp_order."', '".$descrip."')";
						ora_parse($cursor, $sql);
						ora_exec($cursor);

						$body .= "Include:  ".$descrip."\r\n";
						$body_extra_instructions .= "---TEMP RECORDER REQUIRED---\r\n";
					}

				}
				if(substr($line, 0, 1) == "P"){
					$count = round(trim(substr($line, 23, 10)), 0);
					$ctn_per_pallet = round(trim(substr($line, 174, 3)));
					$measure = trim(substr($line, 33, 1));
					if($measure == "P"){
						$measure = "Pallets";
					}elseif($measure == "C"){
						$measure = "Cartons";
					}
					$grade = trim(substr($line, 11, 1));
					$plt_type = trim(substr($line, 12, 1));
					if($plt_type == "W"){
						$plt_type = "Wood";
					}elseif($plt_type == "C"){
						$plt_type = "Chep";
					}
					$nt_wt = trim(substr($line, 260, 10));
					$gr_wt = trim(substr($line, 270, 10));

					$type = trim(substr($line, 44, 44));
					$body .= "Requested:\t\t".$count." ".$measure." (".$type.")\r\n";
					$body .= "\t\t\tGrade: ".$grade."; Pallet: ".$plt_type."; Weight: ".$nt_wt."N ".$gr_wt."G\r\n";

					$sql = "INSERT INTO OPPY_ORDER_INFO (ORDER_NUM, CARGO_DESC, CARGO_COUNT, CARTONS_PER_PALLET, N_WT_PER_CARTON) VALUES ('".$order."', '".$type."', '".$count."', '".$ctn_per_pallet."', '".$nt_wt."')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);

				}

			}

			if($body_extra_instructions != ""){
				$body = "*****\r\n".$body_extra_instructions."*****\r\n\r\n".$body;
			}

			$mailSubject .= " (".trim($mail_sub_order_list).")";
			mail($mailTO, $mailSubject, $body, $mailHeaders);

			system("mv -f ".$dName." ./complete/".$dName);

		}
	}
	


function error_out($mailTO, $mailHeaders, $mailSubject, $body, $dName){
	mail($mailTO, $mailSubject, $body, $mailHeaders);
	system("mv -f ".$dName." ./failed/".$dName);
	exit;
}
