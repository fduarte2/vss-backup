<?
/*
*	May, 2009.
*
*	Intranet page that dumps a Truck Arrival Notification to 
*	Oppenheimer (which they will respond to)
*
************************************************************************************/

	include("oppy_make_tally_include.php");

//	$path = "/web/web_pages/TS_Program/oppyEDIconfirm/truckOK";
	$path = "/web/web_pages/TS_Testing/oppyEDIconfirm/truckCONFIRMS";

  // All POW files need this session file included
//  include("pow_session.php");

 
	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor = ora_open($conn2);
	$cursor2 = ora_open($conn2);

//	$mailTO = "awalter@port.state.de.us\r\n";
	$mailTO = "awalter@port.state.de.us,ltreut@port.state.de.us,draczkowski@port.state.de.us,bdempsey@port.state.de.us,schapman@port.state.de.us,mbillin@port.state.de.us,lsanchez@port.state.de.us,mslowe@port.state.de.us,scorbin@port.state.de.us\r\n";
	$mailHeaders = "From:  PoWMailServer@port.state.de.us\r\n";
	$mailHeaders .= "CC:  lstewart@port.state.de.us,ryawe@oppy.com,stabu@oppy.com,patmc@oppy.com,katbu@oppy.com\r\n";
//	$mailHeaders .= "BCC:  awalter@port.state.de.us,ithomas@port.state.de.us,lstewart@port.state.de.us\r\n";

	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{
		$body = "";

		$mail_sub_order_list = "";

	   	if ($dName == "."  || $dName == ".." || $dName == "oppy_parse_confirm.php" || is_dir($dName)){
			// do nothing to directories.
		} else{
			$fp = fopen($dName, "r");
			$line_1 = fgets($fp);
			$load = trim(substr($line_1, 36, 10));

			$sql = "SELECT COUNT(*) THE_COUNT FROM OPPY_PER_ORDER WHERE LOAD_NUM = '".$load."' AND VERIFY_RECEIVED IS NOT NULL";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['THE_COUNT'] > 0){
				// load was already finalized, we don't want to deal with this again.
				// might happen if numerous requests were sent at once.
				system("mv -f ".$dName." ./processed/".$dName);
				$mailSubject = "Oppenheimer:  LOAD# ".$load." duplicate confirmation received.  Duplication discarded.";
				mail($mailTO, $mailSubject, "", $mailHeaders);
				exit;
			}

			if(substr($line_1, 178, 1) == "N"){
				$mailSubject = "Oppenheimer:  LOAD# ".substr($line_1, 36, 10)." NOT VERIFIED, CONTACT OPPENHEIMER";
				$body = "Load marked as unshippable, please contact Oppenheimer for details.  Load will need to be re-submitted for verification, or manually handled.\r\n";
				$sql = "UPDATE OPPY_PER_ORDER SET VERIFY_SENT = NULL, VERIFY_SENT_BY = NULL WHERE LOAD_NUM = '".$load."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				system("mv -f ".$dName." ./processed/".$dName);
				mail($mailTO, $mailSubject, $body, $mailHeaders);

			}elseif($line = fgets($fp)){
				$mailSubject = "Oppenheimer:  LOAD# ".$load." NOT VERIFIED";
				$body = "The following pallets were listed as bad:\r\n";
				do {
					if(trim(substr($line, 0, 1)) == "P"){
						$body .= trim(substr($line, 24, 20))."\r\n";
					}
				} while($line = fgets($fp));
				system("mv -f ".$dName." ./processed/".$dName);
				mail($mailTO, $mailSubject, $body, $mailHeaders);


			} else {
				fclose($fp); // we know it's good, nothing left in file, free up to use later

				$mailSubject = "Oppenheimer:  LOAD# ".$load." VERIFIED";
				$body = ""; 

//				$load = trim(substr($line_1, 36, 10));
				$filename = "ConfirmShip".$load;

				$connection = ftp_connect("portofwilmington.com");
				if($connection != FALSE){
					$login_status = ftp_login($connection, "oppy", "1oppy2345");
					if($login_status != FALSE){
						$fp = fopen("/web/web_pages/temp/".$filename, "w");
						if($fp != FALSE){
							fwrite($fp, "H"); // required value
							fwrite($fp, "SHIP "); // FILE CODE
							fwrite($fp, "Shipping                      "); // FILE DESCRIPTION
							fwrite($fp, $load); // LOAD#
							for($temp = 0; $temp < (10-strlen($load)); $temp++){
								fwrite($fp, " "); // LOAD# PADDING
							}
							fwrite($fp, "WIL"); // LOC
							fwrite($fp, date('m/d/Y'));
							fwrite($fp, "                                   ");// TRUCK Name
							fwrite($fp, "               "); // LICENCE
							fwrite($fp, "  "); // STATE
							fwrite($fp, date('m/d/Y')); // date in
							fwrite($fp, date('H:i')); // time in
							fwrite($fp, "          "); // date out
							fwrite($fp, "     "); // time out
							fwrite($fp, "      "); // temperature
							fwrite($fp, "      "); // USER CODE
							fwrite($fp, date('m/d/Y')); // date entered
							fwrite($fp, date('H:i')); // time entered
							fwrite($fp, "          "); // mode
							fwrite($fp, " "); // confirmation status
							fwrite($fp, "            "); // seal #
							fwrite($fp, "\n");

							$attachment = "";
							$sql = "SELECT DISTINCT ORDER_NUM FROM OPPY_PER_ORDER WHERE LOAD_NUM = '".$load."'";
							ora_parse($cursor, $sql);
							ora_exec($cursor);
							while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
								$order = $row['ORDER_NUM'];

								$mail_sub_order_list .= $order." ";

								$body .= "ORDER#  ".$order."\n";
								$attachment .= NewTallyTable($order, $conn2);

								fwrite($fp, "O"); // required value
								fwrite($fp, $order);// ORDER#
								for($temp = 0; $temp < (10-strlen($order)); $temp++){
									fwrite($fp, " "); // ORDER# PADDING
								}
								fwrite($fp, "\n");

								$sql = "SELECT PALLET_ID, QTY_CHANGE FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND CUSTOMER_ID = '1512' ORDER BY PALLET_ID";
								ora_parse($cursor2, $sql);
								ora_exec($cursor2);
								while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
									$body .= "PalletID\t\t".$row2['PALLET_ID']."\n";

									$pallet_count++;

									fwrite($fp, "P"); // required value
									fwrite($fp, $order);// ORDER#
									for($temp = 0; $temp < (10-strlen($order)); $temp++){
										fwrite($fp, " "); // ORDER# PADDING
									}
									fwrite($fp, "   "); // sequence number

									if(strlen($row2['PALLET_ID']) <= 10){
										fwrite($fp, $row2['PALLET_ID']); // pallet tag
										for($temp = 0; $temp < (10-strlen($row2['PALLET_ID'])); $temp++){
											fwrite($fp, " "); // PALLET_ID PADDING
										}
										fwrite($fp, "                    "); // alternate pallet tag
									} else {
										fwrite($fp, "          "); //pallet tag
										fwrite($fp, $row2['PALLET_ID']); // alt pallet tag
										for($temp = 0; $temp < (20-strlen($row2['PALLET_ID'])); $temp++){
											fwrite($fp, " "); // ALTERNATE PALLET_ID PADDING
										}
									}

									fwrite($fp, "                                        "); // "reserved" ?
									fwrite($fp, $row2['QTY_CHANGE']); // cartons
									for($temp = 0; $temp < (10-strlen($row2['QTY_CHANGE'])); $temp++){
										fwrite($fp, " "); // CARTON COUNT PADDING
									}
									fwrite($fp, "  "); // status code
									fwrite($fp, " "); // status confirmation
									fwrite($fp, "          "); // "date last repacked"
									fwrite($fp, "\n");
								}


							}

							$mailSubject .= " (".trim($mail_sub_order_list).")";

							fclose($fp);
							$fileput = ftp_put($connection, "./ship-out/".$filename, "/web/web_pages/temp/".$filename, FTP_BINARY);
							if($fileput != FALSE){
								$attach=chunk_split(base64_encode($attachment));
								$mailHeaders .= "MIME-Version: 1.0\r\n";
								$mailHeaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
								$mailHeaders .= "X-Sender: MailServer@port.state.de.us\r\n";
								$mailHeaders .= "X-Mailer: PHP4\r\n";
								$mailHeaders .= "X-Priority: 3\r\n";
								$mailHeaders .= "This is a multi-part Content in MIME format.\r\n";

								$Content="--MIME_BOUNDRY\r\n";
								$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
								$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
								$Content.="\r\n";
								$Content.= $body;
								$Content.="\r\n";
								$Content.="\r\n";

								$Content.="--MIME_BOUNDRY\r\n";
								$Content.="Content-Type: application/pdf; name=\"Tally.xls\"\r\n";
								$Content.="Content-disposition: attachment\r\n";
								$Content.="Content-Transfer-Encoding: base64\r\n";
								$Content.="\r\n";
								$Content.=$attach;
								$Content.="\r\n";
								$Content.="--MIME_BOUNDRY--\n";

								mail($mailTO, $mailSubject, $Content, $mailHeaders);

								system("mv -f ".$dName." ./processed/".$dName);

								$sql = "UPDATE OPPY_PER_ORDER SET VERIFY_RECEIVED = SYSDATE, VERIFY_REC_BY = 'EDICONFIRM' WHERE LOAD_NUM = '".$load."'";
								ora_parse($cursor, $sql);
								ora_exec($cursor);
/*								$sql = "UPDATE OPPY_TRUCK_INFO SET VALID_REC_ON = SYSDATE WHERE LOAD_NUM = '".$load."'";
								ora_parse($cursor, $sql);
								ora_exec($cursor); */

							} else {
								$body = "";
								$mailSubject = "Oppy Confirmation FAILED, FTP refused file.  Please contact TS";
								mail($mailTO, $mailSubject, $body, $mailHeaders);
							}
							unlink("/web/web_pages/temp/".$filename); // delete temp file either way
						} else {
							$body = "";
							$mailSubject = "Oppy Confirmation FAILED, Could not Create File.  Please contact TS";
							mail($mailTO, $mailSubject, $body, $mailHeaders);
						}
					} else {
						$body = "";
						$mailSubject = "Oppy Confirmation FAILED, Oppy FTP not receiving files.  Please contact TS";
						mail($mailTO, $mailSubject, $body, $mailHeaders);
					}
					ftp_close($connection);
				} else {
					$body = "";
					$mailSubject = "Oppy Confirmation FAILED, Oppy FTP not accepting connections.  Please contact TS";
					mail($mailTO, $mailSubject, $body, $mailHeaders);
				}
			}
		}
	}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
/*
			$line_1 = fgets($fp);
			$line_2 = fgets($fp);

			$load = trim(substr($line_2, 1, 10));
			$order = trim(substr($line_2, 11, 10));

			$response = trim(substr($line_2, 201, 10));

//			echo $response."\n";

			if($response == "OK" || $response == "NOCHECKIN"){
				$mailSubject = "Oppenheimer Truck CONFIRMED, Order ".$order." - Load ".$load;
				system("mv -f ".$dName." ./ok/".$dName);
			} else {
				$mailSubject = "Oppenheimer Truck NOT Confirmed, Order ".$order." - Load ".$load;
				system("mv -f ".$dName." ./notok/".$dName);
			}

			mail($mailTO, $mailSubject, " ", $mailHeaders);
		}
	}
*/

/*
function NewTallyTable($order, $conn2){
	$cursor = ora_open($conn2);
	$cursor2 = ora_open($conn2);

	$return = "<table border=\"0\" width=\"80%\" cellpadding=\"2\" cellspacing=\"0\">";

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '1512'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$name = $row['CUSTOMER_NAME'];

	$sql = "SELECT MAX(ACTIVITY_ID) THE_MAX, 
				TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_START_DATE,
				TO_CHAR(MIN(DATE_OF_ACTIVITY), 'HH24:MI') THE_START_TIME,
				TO_CHAR(MIN(DATE_OF_ACTIVITY), 'HH24:MI') THE_END_TIME
			FROM CARGO_ACTIVITY
			WHERE ORDER_NUM = '".$order."'
				AND CUSTOMER_ID = '1512'
				AND SERVICE_CODE NOT IN (12, 18, 19, 21, 22)
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$date = $row['THE_START_DATE'];
	$S_time = $row['THE_START_TIME'];
	$E_time = $row['THE_END_TIME'];

	$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$row['THE_MAX']."'";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$checker = $row2['LOGIN_ID'];

	$return .= "<tr>
					<td colspan=\"7\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>PORT OF WILMINGTON TALLY</b></font></td>
				</tr>
				<tr>
					<td colspan=\"3\" align=\"left\"><font size=\"3\" face=\"Verdana\">Customer: ".$name."</font></td>
					<td>&nbsp;</td>
					<td colspan=\"3\" align=\"right\"><font size=\"3\" face=\"Verdana\">Date: ".$date."</font></td>
				</tr>
				<tr>
					<td colspan=\"6\">&nbsp;</td>
					<td align=\"right\"><font size=\"3\" face=\"Verdana\">Start: ".$S_time."</font></td>
				</tr>
				<tr>
					<td colspan=\"6\">&nbsp;</td>
					<td align=\"right\"><font size=\"3\" face=\"Verdana\">Finish: ".$E_time."</font></td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"left\"><font size=\"3\" face=\"Verdana\">Checker: ".$checker."</font></td>
					<td colspan=\"3\">&nbsp;</td>
					<td colspan=\"2\" align=\"right\"><font size=\"3\" face=\"Verdana\">Shipping Type: Outbound</font></td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"left\"><font size=\"3\" face=\"Verdana\">Order Num: ".$order."</font></td>
					<td colspan=\"5\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"7\">&nbsp;</td>
				</tr>
				<tr>
					<td align=\"left\"><font size=\"2\" face=\"Verdana\"><b>BARCODE</b></font></td>
					<td>&nbsp;</td>
					<td align=\"left\"><font size=\"2\" face=\"Verdana\"><b>DESCRIPTION</b></font></td>
					<td>&nbsp;</td>
					<td align=\"left\"><font size=\"2\" face=\"Verdana\"><b>QTY</b></font></td>
					<td>&nbsp;</td>
					<td align=\"left\"><font size=\"2\" face=\"Verdana\"><b>VESSEL</b></font></td>
				</tr>
				<tr>
					<td colspan=\"7\">&nbsp;</td>
				</tr>";

	// alright, the header's done...
	$this_pallet = "nope";
	$cases_on_this_pallet = 0;
	$plt_total = 0;
	$case_total = 0;
	$sql = "SELECT CA.PALLET_ID, CT.VARIETY, CT.REMARK, CT.CARGO_SIZE, COMP.COMMODITY_NAME, CA.QTY_CHANGE, NVL(VESSEL_NAME, 'TRUCKIN') THE_VES
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, COMMODITY_PROFILE COMP, VESSEL_PROFILE VP
			WHERE CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
				AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND CA.ORDER_NUM = '".$order."'
				AND CA.CUSTOMER_ID = '1512'
				AND SERVICE_CODE NOT IN (12, 18, 19, 21, 22)
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')
				ORDER BY PALLET_ID, ACTIVITY_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row['PALLET_ID'] != $this_pallet){
			$plt_total++;
			$case_total += $row['QTY_CHANGE'];
			$cases_on_this_pallet = $row['QTY_CHANGE'];
		} else {
			// more activities after the initial pallet-ship. so we...
			if($cases_on_this_pallet > 0 && ($cases_on_this_pallet + $row['QTY_CHANGE']) <= 0){
				// this activity pushes the pallet OUT of the order
				$plt_total--;
			} elseif ($cases_on_this_pallet <= 0 && ($cases_on_this_pallet + $row['QTY_CHANGE']) > 0){
				// re-ship activity places removed pallet BACK ON order
				$plt_total++;
			}

			$cases_on_this_pallet += $row['QTY_CHANGE'];
			$case_total += $row['QTY_CHANGE'];
		}

		$return .= "<tr>
						<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$row['PALLET_ID']."</font></td>
						<td>&nbsp;</td>
						<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$row['COMMODITY_NAME']." - ".$row['VARIETY']." - ".$row['REMARK']." - ".$row['CARGO_SIZE']."</font></td>
						<td>&nbsp;</td>
						<td align=\"left\"><font size=\"2\" face=\"Verdana\"><b>".$row['QTY_CHANGE']."</b></font></td>
						<td>&nbsp;</td>
						<td align=\"left\"><font size=\"2\" face=\"Verdana\"><b>".$row['THE_VES']."</b></font></td>
					</tr>";
	}
						
	$return .= "<tr>
					<td colspan=\"3\" align=\"left\">&nbsp;</td>
					<td align=\"right\"><font size=\"3\" face=\"Verdana\"><b>Total Count: ".$case_total."</b></font></td>
					<td colspan=\"3\" align=\"right\">&nbsp;</td>
				</tr>";
	$return .= "<tr>
					<td colspan=\"3\" align=\"left\">&nbsp;</td>
					<td align=\"right\"><font size=\"3\" face=\"Verdana\"><b>Total Pallets: ".$plt_total."</b></font></td>
					<td colspan=\"3\" align=\"right\">&nbsp;</td>
				</tr>";

					
	$return .= "</table><br><br>";
	
	return $return;
}
*/