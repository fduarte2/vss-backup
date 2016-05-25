<?
/*
*		Adam Walter, Apr 2010
*
*	This program is designed to parse an incoming-EDI file from
*	Barnett, nee booking paper.  
*
*****************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	ora_commitoff($conn2);
	$cursor = ora_open($conn2);

	// email variables that don't change throughout the program
//	$mailTO = "hdadmin@port.state.de.us";
	$mailTO = "TPenot@nortonlilly.com";

	$mailheaders = "From: " . "NoReplies@POWEDI.com\r\n";
	$mailheaders .= "Cc: " . "Ithomas@port.state.de.us\r\n";
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,hdadmin@port.state.de.us,ltreut@port.state.de.us\r\n";	
//	$mailheaders .= "Bcc: " . "awalter@port.state.de.us\r\n";	

	$path = "/web/web_pages/TS_Program/BarnettEDI/";
//	$path = "/web/web_pages/TS_Testing/BarnettEDI/";

	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{
		// initialization
		$emailBreakdown = "Order\tVehicle\t\tCount\r\n";
		$total_rolls_in_file = 0;
		$total_rolls_accepted = 0;
		$BoLlist = "";
		$list_of_unchangeable_rolls = "";

	   	if ($dName == "."  || $dName == ".." || $dName == "BarnettEDI.php" || $dName == "BarnettEDI.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else{
			// here there be Dragons... and file pointers.
			$fp = fopen($dName, "r");
			
			while($temp = fgets($fp)){
				// nested switch-while statements to reference EDI blocks
				$line = split("\*", trim($temp));

				switch ($line[0]) {

					case "ISA":  // first line of EDI, must match with last line of file
						$ISA = $line[13];
					break;
					case "IEA":  // last line of file.  If not a match to above, disuse file.
						if ($line[2] != $ISA){
							fileMove($path, $dName, $fp, "bad");
							mail($mailTO, "Barnett EDI Status: ERROR, EDI - IEA value did not match ISA value for file ".$dName, $mailheaders);
							exit;
						} else {
							$ISA = "";
						}
					break;

					case "GS":  // second line of EDI, must match with 2nd to last line of file
						$GS = $line[6];
					break;
					case "GE":  // second to last line of EDI.  if not a match to above, disuse file.
						if ($line[2] != $GS){
							fileMove($path, $dName, $fp, "bad");
							mail($mailTO, "Barnett EDI Status: ERROR, EDI - GE value did not match GS value for file ".$dName, $mailheaders);
							exit;
						} else {
							$GS = "";
						}
					break;

					case "ST":  // will show at least once.  must match with SE line.  Starts an internal loop.
						// some variable initializations.
						$ST = $line[2];
						$rolls_in_this_segment = 0;
						$rolls_in_this_segment_successful = 0;
						$rolls_uploaded_so_far_this_segment = 0;
//						$container_id = "";
						$emailBoL = "";

						do {
							$temp = fgets($fp);
							$line = split("\*", trim($temp));

							// this internal switch statement is for each ST segment.
							switch ($line[0]) {

								// BoL line.
								case "BSN":  // EDI line with BoL in it; we don't store it though.  email use only.
									$emailBoL = $line[2];

									$EDItype = $line[1];

									if($line[1] != "01" && $line[1] != "05" && $line[1] != "00"){
										fileMove($path, $dName, $fp, "bad");
										mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " EDI - file ".$dName.", PoW system does not accept EDIs that are not original, cancellations, or re-issues.", $mailheaders);
										exit;
									}
								break;
/*
								case "MEA":  
									// measurements.  we are after rollcount of file, found near the top of the segment.
									// MEA is used many times; once the rollcount is set per segment, we want to keep it.
									if($line[1] == "CT" && $line[4] == "RL" && $rolls_in_this_segment == 0){ 
										// we only want to set this if it's the first time for the current order
										$rolls_in_this_segment = $line[3];
									}
								break;
*/

								case "N1":  // company info.  we want ST and SU types for now.
									if ($line[1] == "ST"){
										$ST_rec_id = $line[4];
									} elseif ($line[1] == "SF"){
										$ship_from_mill = $line[4];

/*										$cust = getCustomerNumber($line[4]);
										if($cust == FALSE){
											fileMove($path, $dName, $fp, "bad");
											mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, "EDI - customer ".$line[4]." not recognized in file ".$dName, $mailheaders);
											exit;
										} */
									}
								break;

								case "TD3":  // railcar / truck number.
									$ARV_num = $line[2].$line[3];
								break;


								case "REF": // reference (order#) value.  there will be multiple REF lines per file, we only want the one for "MI".
									if ($line[1] == "BM"){
										$BOL = $line[2];
									}
								break;

								case "LIN": // reference (order#) value.  there will be multiple REF lines per file, we only want the one for "MI".
									if ($line[2] == "GC"){
										$prod_code = $line[3];
									}
								break;

								case "PRF": // reference (order#) value.  there will be multiple REF lines per file, we only want the one for "MI".
									$PO = $line[1];
								break;
/*
								case "REF": // reference (order#) value.  there will be multiple REF lines per file, we only want the one for "MI".
									if ($line[1] == "MI"){
										$millnumber = $line[2];
									}
								break;
*/
/*
								case "PO4":  // measurement per roll (pack)
									$rolls_per_pack = $line[1];
									$batch_id = "-".($line[2] * getWidthMeasurement($line[3]));
									$cargo_description = ($line[2] * getWidthMeasurement($line[3]))."x".($line[12] * getWidthMeasurement($line[13]))." ".$millnumber;
								break;
*/

								case "HL":  // indicates start of a 5-line block of data for a single roll, assuming position 3 is an "I"
									if ($line[3] == "I"){
										for($i = 0; $i < 5; $i++){
											$temp = fgets($fp);
											$line = split("\*", trim($temp));
											switch($line[2]){
												case "SN":
													// barcode line
													$barcode = $line[3];
												break;

												case "WD":
													// paper width line
													$roll_width = $line[3];
													$width_unit = strtoupper($line[4]);
												break;

												case "GW":
													// weight line
													$weight = $line[3];
													$weight_unit = strtoupper($line[4]);
												break;

												case "LN":
													$linear = $line[3];
													$linear_unit = strtoupper($line[4]);
												break;

												case "DI":
													$diameter = $line[3];
													$diameter_unit = strtoupper($line[4]);
												break;

												default:
													// other lines, not that there are any, are unneeded.
												break;
											}
										}
/*
										// first line is barcode (may be in position 3 or 5)
										$temp = fgets($fp);
										$line = split("\*", trim($temp));
										if($line[2] == "RO"){
											$barcode = $line[3];
										} else { // in this case, spot 2&3 are package data, 4&5 are roll data
											$barcode = $line[5];
										}

										// second line is gross weight
										$temp = fgets($fp);
										$line = split("\*", trim($temp));
										$weight = $line[3];
										$weight_unit = $line[4];

										$successful_import = Deal_With_Roll($cargo_description, $cust, $rolls_per_pack, $batch_id, $barcode, $weight, $weight_unit, $remark, $container_id, $EDItype, $millnumber, $list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL);

										$rolls_uploaded_so_far_this_segment++;
										$total_rolls_in_file++;
										if($successful_import == TRUE){
											$total_rolls_accepted++;
											$rolls_in_this_segment_successful++;
										}
										*/

										$successful_import = Deal_With_Roll($ARV_num, $BOL, $ST_rec_id, $ship_from_mill, $PO, $prod_code, $barcode, $diameter, $diameter_unit, $roll_width, $width_unit, $linear, $linear_unit, $weight, $weight_unit,  $EDItype, $list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL);

										$rolls_uploaded_so_far_this_segment++;
										$total_rolls_in_file++;
										if($successful_import == TRUE){
											$total_rolls_accepted++;
											$rolls_in_this_segment_successful++;
										}


									
									}
								break;

								case "SE": // closing line for any blocks opened by ST.  must match ST value, or disuse file.
									if ($line[2] != $ST){
										fileMove($path, $dName, $fp, "bad");
										mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " EDI - SE ".$line[2]." did not match ST value ".$ST." in file ".$dName, $mailheaders);
										exit;
									} else {
										$ST = "";
									}
								break;

								default:  // an EDI line we don't need
								break;
							}
						} while($line[0] != "SE");  // end internal ST-SE block loop

						$emailBreakdown .= $PO."\t".$ARV_num."\t".$rolls_in_this_segment_successful;
						if($EDItype == "01"){
							$emailBreakdown .= " removed due to cancellation.\r\n";
						}elseif($EDItype == "00"){
							$emailBreakdown .= " added.\r\n";
						}else{
							$emailBreakdown .= " added/modified due to EDI reissue.\r\n";
						}


					break;
					
					default:  // an EDI line that we don't need
					break;
				}
			} // end file parse

			// if we haven't broken execution to this point, commit the data in the file, and copy to "complete"
			// unless the file's closing lines aren't present...
			if($ISA == "" && $GS == ""){
				fileMove($path, $dName, $fp, "good");
				mail($mailTO, "Barnett EDI Status: COMPLETE EDI - file ".$dName, "Upload complete, ".$total_rolls_in_file." in file, ".$total_rolls_accepted." accepted.  Breakdown:\r\n".$emailBreakdown."\r\n\r\nExceptions noted:\r\n".$list_of_unchangeable_rolls, $mailheaders);
			} else {
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, EDI - file ".$dName, "No GE or IEA segment closing found.", $mailheaders);
			}
		}
	}

/*
function getWidthMeasurement($unit){
	global $conn2;

	// eventually, this should be DB driven.
	// returns the conversion factor to MM from $unit.

	if(strtoupper($unit) == "CM"){
		return 10;
	}
	if(strtoupper($unit) == "MM"){
		return 1;
	}
}
*/

function fileMove($path, $filename, $fp, $type){
	global $conn2;

	switch ($type) {
		case "bad":
			ora_rollback($conn2);
			fclose($fp);
			copy($path."/".$filename , $path."/failed/".$filename);
			unlink($path."/".$filename);
		break;

		case "good":
			ora_commit($conn2);
			fclose($fp);
			copy($path."/".$filename , $path."/complete/".$filename);
			unlink($path."/".$filename);
		break;
	}
}

/*
function getCustomerNumber($EDI_N1_SU_position4value){
	global $conn2;

	// for now, we only have 1 customer using this, so I'm hardcoding.  
	// If more customers use this script, change this function to a DB driven one.
	// hence why i include the global above.

	if($EDI_N1_SU_position4value == "001670769"){
		return 312;
	} else {
		return FALSE;
	}
}
*/
function Deal_With_Roll($ARV_num, $BOL, $ST_rec_id, $ship_from_mill, $PO, $prod_code, $barcode, $diameter, $diameter_unit, $roll_width, $width_unit, $linear, $linear_unit, $weight, $weight_unit, $EDItype, &$list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL){
	global $conn2;
	$short_term_cursor = ora_open($conn2);
	$change_cursor = ora_open($conn2);

	// decides what to do with a given roll, based on if this is an original, cancellation, or re-issue EDI block.
	// pass-by-reference variable $list_of_unchangeable_rolls keeps running track for email later

	switch ($EDItype) {
		case "00":		// original

			// check to make sure this roll isn't already present.
			$sql = "SELECT * FROM TEMP_BARNETT_DATA WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$ship_from_mill." - ".$BOL." (Could not be entered as Original EDI receipt)\r\n";
				return FALSE;
			}

			// go ahead and insert it
			$sql = "INSERT INTO TEMP_BARNETT_DATA (TD3RR_ARRIVAL_NUM, REFBM_BOL, N1ST_RECEIVER_ID, N1SF_SHIPFROMMILL, PRF_ORDER_NUM, LINGC_PRODUCT_CODE, LINSN_PALLET_ID, MEADI_DIAMETER, MEAWD_WIDTH, MEALN_LENGTH, MEAGW_WEIGHT, MEAGW_WEIGHTUNIT, MEALN_LENGTH_MEAS, MEAWD_WIDTH_MEAS, MEADI_DIAMETER_MEAS) VALUES ('".$ARV_num."', '".$BOL."', '".$ST_rec_id."', '".$ship_from_mill."', '".$PO."', '".$prod_code."', '".$barcode."', '".$diameter."', '".$roll_width."', '".$linear."', '".$weight."', '".$weight_unit."', '".$linear_unit."', '".$width_unit."', '".$diameter_unit."')";
//			echo $sql."\n";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}

			return TRUE;
		break;

		case "01":		// cancellation
		
			// check to see if roll being cancelled is even in system.
			$sql = "SELECT * FROM TEMP_BARNETT_DATA WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$ship_from_mill." - ".$BOL." (Could not be cancelled; roll not in system.)\r\n";
				return FALSE;
			}

			// check to see if roll being cancelled is already received.
			$sql = "SELECT * FROM TEMP_BARNETT_DATA WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."' AND SHIPOUT_ORDER IS NOT NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$ship_from_mill." - ".$BOL." (Could not be cancelled; roll already shipped out.)\r\n";
				return FALSE;
			}

			// go ahead and delete it
			$sql = "DELETE FROM TEMP_BARNETT_DATA WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."'";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}

			return TRUE;
		break;

		case "05":		// re-issue.

			// check to see if roll being cancelled is already received.
			$sql = "SELECT * FROM TEMP_BARNETT_DATA WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."' AND SHIPOUT_ORDER IS NOT NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$ship_from_mill." - ".$BOL." (Could not be changed; roll already shipped out.)\r\n";
				return FALSE;
			}

			// check to see if roll being re-submitted is in system, but not yet received.
			$sql = "SELECT * FROM TEMP_BARNETT_DATA WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

				// go ahead and update
				$sql = "UPDATE TEMP_BARNETT_DATA SET N1ST_RECEIVER_ID = '".$ST_rec_id."', N1SF_SHIPFROMMILL = '".$ship_from_mill."', PRF_ORDER_NUM = '".$PO."', LINGC_PRODUCT_CODE = '".$prod_code."', MEADI_DIAMETER = '".$diameter."', MEAWD_WIDTH = '".$roll_width."', MEALN_LENGTH = '".$linear."', MEAGW_WEIGHT = '".$weight."', MEAGW_WEIGHTUNIT = '".$weight_unit."', MEALN_LENGTH_MEAS = '".$linear_unit."', MEAWD_WIDTH_MEAS = '".$width_unit."', MEADI_DIAMETER_MEAS = '".$diameter_unit."' WHERE LINSN_PALLET_ID = '".$barcode."' AND TD3RR_ARRIVAL_NUM = '".$ARV_num."' AND REFBM_BOL = '".$BOL."' AND SHIPOUT_ORDER IS NULL";
				$ora_success = ora_parse($change_cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
					exit;
				}
				$ora_success = ora_exec($change_cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not update pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
					exit;
				}

				return TRUE;
			}

			// last case, roll not already present.  Go ahead and insert.
			$sql = "INSERT INTO TEMP_BARNETT_DATA (TD3RR_ARRIVAL_NUM, REFBM_BOL, N1ST_RECEIVER_ID, N1SF_SHIPFROMMILL, PRF_ORDER_NUM, LINGC_PRODUCT_CODE, LINSN_PALLET_ID, MEADI_DIAMETER, MEAWD_WIDTH, MEALN_LENGTH, MEAGW_WEIGHT, MEAGW_WEIGHTUNIT, MEALN_LENGTH_MEAS, MEAWD_WIDTH_MEAS, MEADI_DIAMETER_MEAS) VALUES ('".$ARV_num."', '".$BOL."', '".$ST_rec_id."', '".$ship_from_mill."', '".$PO."', '".$prod_code."', '".$barcode."', '".$diameter."', '".$roll_width."', '".$linear."', '".$weight."', '".$weight_unit."', '".$linear_unit."', '".$width_unit."', '".$diameter_unit."')";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Barnett EDI Status: ERROR, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}

			return TRUE;
		break;
	}
}