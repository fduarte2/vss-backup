<?
/*
*		Adam Walter, Sep/Oct 2008
*
*	This program is designed to parse an incoming-EDI file from
*	Abitibi.  In the future, it may be expanded to other customers.
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
	$mailTO = "awalter@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us,draczkowski@port.state.de.us";

	$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "lstewart@port.state.de.us,ltreut@port.state.de.us,awalter@port.state.de.us,moslowe@port.state.de.us\r\n"; 
//	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";	

	$path = "/web/web_pages/TS_Program/abiEDI/";

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

	   	if ($dName == "."  || $dName == ".." || $dName == "abitibiEDI.php" || $dName == "abitibiEDI.sh" || is_dir($dName)){
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
							mail($mailTO, "Abitibi EDI upload FAILED, EDI - IEA value did not match ISA value for file ".$dName, $mailheaders);
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
							mail($mailTO, "Abitibi EDI upload FAILED, EDI - GE value did not match GS value for file ".$dName, $mailheaders);
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
						$container_id = "";
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
										mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " EDI - file ".$dName.", PoW system does not accept EDIs that are not original, cancellations, or re-issues.", $mailheaders);
										exit;
									}
								break;

								case "MEA":  
									// measurements.  we are after rollcount of file, found near the top of the segment.
									// MEA is used many times; once the rollcount is set per segment, we want to keep it.
									if($line[1] == "CT" && $line[4] == "RL" && $rolls_in_this_segment == 0){ 
										// we only want to set this if it's the first time for the current order
										$rolls_in_this_segment = $line[3];
									}
								break;

								case "N1":  // company info.  we want ST and SU types for now.
									if ($line[1] == "ST"){
										$remark = substr(str_replace(",", "", $line[2]), 0, 20);
									} elseif ($line[1] == "SU"){
										$cust = getCustomerNumber($line[4]);
										if($cust == FALSE){
											fileMove($path, $dName, $fp, "bad");
											mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, "EDI - customer ".$line[4]." not recognized in file ".$dName, $mailheaders);
											exit;
										}
									}
								break;

								case "TD3":  // railcar / truck number.
									$container_id = $line[2].$line[3];
								break;

								case "REF": // reference (order#) value.  there will be multiple REF lines per file, we only want the one for "MI".
									if ($line[1] == "MI"){
										$millnumber = $line[2];
									}
								break;

								case "PO4":  // measurement per roll (pack)
									$rolls_per_pack = $line[1];
									$batch_id = "-".($line[2] * getWidthMeasurement($line[3]));
									$cargo_description = ($line[2] * getWidthMeasurement($line[3]))."x".($line[12] * getWidthMeasurement($line[13]))." ".$millnumber;
								break;


								case "HL":  // indicates start of a 2-line block of data for a single roll, assuming position 3 is an "I"
									if ($line[3] == "I"){

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
									}
								break;

								case "SE": // closing line for any blocks opened by ST.  must match ST value, or disuse file.
									if ($line[2] != $ST){
										fileMove($path, $dName, $fp, "bad");
										mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " EDI - SE ".$line[2]." did not match ST value ".$ST." in file ".$dName, $mailheaders);
										exit;
									} elseif($rolls_uploaded_so_far_this_segment != $rolls_in_this_segment) {
										fileMove($path, $dName, $fp, "bad");
										mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, "EDI - mill order ".$millnumber." number of rolls (".$rolls_uploaded_so_far_this_segment.") mismatched to expected value of (".$rolls_in_this_segment.") in file ".$dName, $mailheaders);
										exit;
									} else {
										$ST = "";
									}
								break;

								default:  // an EDI line we don't need
								break;
							}
						} while($line[0] != "SE");  // end internal ST-SE block loop
					
						$emailBreakdown .= $millnumber."\t".$container_id."\t".$rolls_in_this_segment_successful;
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
				mail($mailTO, "Abitibi EDI upload COMPLETE EDI - file ".$dName, "Upload complete, ".$total_rolls_in_file." in file, ".$total_rolls_accepted." accepted.  Breakdown:\r\n".$emailBreakdown."\r\n\r\nExceptions noted:\r\n".$list_of_unchangeable_rolls, $mailheaders);
			} else {
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, EDI - file ".$dName, "No GE or IEA segment closing found.", $mailheaders);
			}
		}
	}

















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

function Deal_With_Roll($cargo_description, $cust, $rolls_per_pack, $batch_id, $barcode, $weight, $weight_unit, $remark, $container_id, $EDItype, $millnumber, &$list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL){
	global $conn2;
	$short_term_cursor = ora_open($conn2);
	$change_cursor = ora_open($conn2);

	// decides what to do with a given roll, based on if this is an original, cancellation, or re-issue EDI block.
	// pass-by-reference variable $list_of_unchangeable_rolls keeps running track for email later

	switch ($EDItype) {
		case "00":		// original

			// check to make sure this roll isn't already present.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be entered as Original EDI receipt)\r\n";
				return FALSE;
			}

			// go ahead and insert it
			$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT, QTY_IN_HOUSE, BATCH_ID, PALLET_ID, ARRIVAL_NUM, WEIGHT, WEIGHT_UNIT, REMARK, CONTAINER_ID, RECEIVING_TYPE, SOURCE_NOTE, SOURCE_USER) VALUES ('1299', '".$cargo_description."', '1', '".$cust."', '".$rolls_per_pack."', '1', '".$batch_id."', '".$barcode."', '4', '".$weight."', '".$weight_unit."', '".$remark."', '".$container_id."', 'T', 'EDI, ' || TO_CHAR(SYSDATE, 'MM/DD/YYYY HH24:MI') || 'File ".$dName."', 's-16')";
//			echo $sql."\n";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}

			return TRUE;
		break;

		case "01":		// cancellation
		
			// check to see if roll being cancelled is even in system.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be cancelled; roll not in system.)\r\n";
				return FALSE;
			}

			// check to see if roll being cancelled is already received.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be cancelled; roll already received.)\r\n";
				return FALSE;
			}

			// go ahead and delete it
			$sql = "DELETE FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."'";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}

			return TRUE;
		break;

		case "05":		// re-issue.

			// check to see if roll being re-submitted is already received.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be changed; roll already received.)\r\n";
				return FALSE;
			}

			// check to see if roll being re-submitted is in system, but not yet received.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."' AND DATE_RECEIVED IS NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

				// go ahead and update
				$sql = "UPDATE CARGO_TRACKING SET CARGO_DESCRIPTION = '".$cargo_description."', QTY_UNIT = '".$rolls_per_pack."', BATCH_ID = '".$batch_id."', WEIGHT = '".$weight."', WEIGHT_UNIT = '".$weight_unit."', REMARK = '".$remark."', SOURCE_NOTE = 'EDI, ' || TO_CHAR(SYSDATE, 'MM/DD/YYYY HH24:MI') || 'File ".$dName."' WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '4' AND COMMODITY_CODE = '1299' AND CONTAINER_ID = '".$container_id."' AND DATE_RECEIVED IS NULL";
				$ora_success = ora_parse($change_cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
					exit;
				}
				$ora_success = ora_exec($change_cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not update pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
					exit;
				}

				return TRUE;
			}

			// last case, roll not already present.  Go ahead and insert.
			$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT, QTY_IN_HOUSE, BATCH_ID, PALLET_ID, ARRIVAL_NUM, WEIGHT, WEIGHT_UNIT, REMARK, CONTAINER_ID, RECEIVING_TYPE, SOURCE_NOTE, SOURCE_USER) VALUES ('1299', '".$cargo_description."', '1', '".$cust."', '".$rolls_per_pack."', '1', '".$batch_id."', '".$barcode."', '4', '".$weight."', '".$weight_unit."', '".$remark."', '".$container_id."', 'T', 'EDI, ' || TO_CHAR(SYSDATE, 'MM/DD/YYYY HH24:MI') || 'File ".$dName."', 's-16')";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Abitibi EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				exit;
			}

			return TRUE;
		break;
	}
}