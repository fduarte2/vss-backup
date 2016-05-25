<?
/*
*		Adam Walter, Mar 2009
*
*	This program is designed to parse an incoming-EDI file from
*	Dole customers.
*
*****************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("TCONSTANT@RFTEST", "TCONSTANT");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	ora_commitoff($conn2);
	$cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	// email variables that don't change throughout the program
	$mailTO = "sayers@port.state.de.us,ltreut@port.state.de.us,draczkowski@port.state.de.us";
//	$mailTO = "awalter@port.state.de.us";

	$mailheaders = "From: " . "pownoreply@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "martym@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "lstewart@port.state.de.us,awalter@port.state.de.us,archive@port.state.de.us\r\n";	

	$path = "/web/web_pages/TS_Program/DoleEDI/";
//	$path = "/web/web_pages/TS_Testing/DoleEDI/";


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{
		// initialization
		$emailBreakdown = "P.O.\t\tVEHICLE\tCust\tCount\r\n";
		$total_rolls_in_file = 0;
		$total_rolls_accepted = 0;
		$BoLlist = "";
		$list_of_unchangeable_rolls = "";
		

	   	if ($dName == "."  || $dName == ".." || $dName == "DoleEDI.php" || $dName == "DoleEDI.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else{
			// here there be Dragons... and file pointers.
			$fp = fopen($dName, "r");
//			$dName = $dName.".".date('mdYhi');

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
							mail($mailTO, "Dole EDI upload FAILED, EDI - IEA value did not match ISA value for file ".$dName, $mailheaders);
							Record_EDI($dName, "Pre-ST", "failed", "bad ISA/IEA");
							exit;
						} else {
							$ISA = "";
						}
					break;

					case "GS":  // second line of EDI, must match with 2nd to last line of file
						$GS = $line[6];
						$sender = GetSender($line);
						if($sender == FALSE){
							fileMove($path, $dName, $fp, "bad");
							mail($mailTO, "Dole EDI upload FAILED, unable to resolve Sender information for customer conversion in file ".$dName, $mailheaders);
							exit;
						}
					break;
					case "GE":  // second to last line of EDI.  if not a match to above, disuse file.
						if ($line[2] != $GS){
							fileMove($path, $dName, $fp, "bad");
							mail($mailTO, "Dole EDI upload FAILED, EDI - GE value did not match GS value for file ".$dName, $mailheaders);
							Record_EDI($dName, "Pre-ST", "failed", "bad GS/GE");
							exit;
						} else {
							$GS = "";
						}
					break;

					case "ST":  // will show at least once.  must match with SE line.  Starts an internal loop.
						// some variable initializations.
						$ST = $line[2];
//						$rolls_in_this_segment = 0;
						$rolls_in_this_segment_successful = 0;
//						$rolls_uploaded_so_far_this_segment = 0;
						$container_id = "";
						$emailBoL = "";
						$mill_from = "";
						$basis_weight = "";
						$basis_weight_flag = false;


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
										mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " EDI - file ".$dName.", PoW system does not accept EDIs that are not original, cancellations, or re-issues.", $mailheaders);
										Record_EDI($dName, $ST, "failed", "bad BSN");
										exit;
									}
								break;

								case "REF": // reference (order#) value.  We just want "BM"
									if ($line[1] == "BM"){
										$cargo_desc_part_3 = $line[2];
									}
								break;

								// measurement.  This is the measurement OUTSIDE of the HL lines, which gives
								// us our basis weight.  Hopefully.
								case "MEA":  // take this at all times.
									if($line[2] == "BW"){ 
										$basis_weight = $line[3];
										$basis_weight_units = "LB";
										$basis_weight_flag = true;
									}
								break;
								case "LIN": 
									if ($line[2] == "GC" && $basis_weight_flag == false){ // we only take this is no MEA*WT*BW line is available.
										$basis_weight = substr($line[3], 3, 2);
										$basis_weight_units = "LB";
									}
								break;



								case "N1":  // company info.  From the EDI; IFP sends us in the ST line, PCA/IP in the SF line
//									echo "sender: ".$sender." line1: ".$line[1]."\n";
//									if (($line[1] == "ST" && substr($sender, 0, 3) == substr($line[4], 0, 3)) || ($line[1] == "SF" && substr($sender, 0, 3) == substr($line[4], 0, 3))){
//									if (($line[1] == "ST" && substr($sender, 0, 3) == "IFP") || ($line[1] == "SF" && (substr($sender, 0, 3) == "PCA" || substr($sender, 0, 3) == "IPC"))){
									if($line[1] == "SF"){
										$cust = getCustomerNumber($line[4], $cust_type);
										if($cust == FALSE){
											fileMove($path, $dName, $fp, "bad");
											mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, "EDI - customer ".$line[4]." not recognized in file ".$dName, $mailheaders);
											Record_EDI($dName, $ST, "failed", "bad N1 customer");
											exit;
										} elseif($cust_type == "BOOKING"){
											fileMove($path, $dName, $fp, "booking");
											mail($mailTO, "Dole EDI upload NOT IMPORTED (Booking), BoL ".$emailBoL, "EDI - ".$dName." Is For Booking Paper.", $mailheaders);
											Record_EDI($dName, $ST, "failed", "Booking EDI in DT system");
											exit;
										}
									}

									if (($line[1] == "SF" && substr($sender, 0, 3) == "IFP") || ($line[1] == "ST" && (substr($sender, 0, 3) == "PCA" || substr($sender, 0, 3) == "IPC"))){
										$mill_from = $line[4];
									}
								break;

								case "TD3":  // railcar / truck number.
									$vessel = $line[2].$line[3];
									$replace = array(" ", "&", "'");
									$vessel = str_replace($replace, "", $vessel);
								break;

								case "HL":  // "lines".  An I is a "line item", an O is an "overall line".  not a zero, the letter O.
									if ($line[3] == "O"){
										// will only happen once per ST segment.
										$temp = fgets($fp);
										$line = split("\*", trim($temp));
										if(strtoupper($line[0]) == "PRF"){
											$cargo_desc_part_1 = $line[1];
										} else {
											$cargo_desc_part_1 = $line[2];
										}

									} elseif ($line[3] == "I"){
										// 1 iteration per barcode per ST segment.
										// there are 5 lines that come after the "I", but (now) 3 companies have diff orders
										// so a 5-iteration for loops to get 5 lines, and a switch to parse them.

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
													$roll_width = get_width_inches($line[3], trim($line[4]));
													if($roll_width == FALSE){
														fileMove($path, $dName, $fp, "bad");
														mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, "EDI - roll width unit ".$line[4]." not recognized in file ".$dName, $mailheaders);
														Record_EDI($dName, $ST, "failed", "unrecognized width measurement");
														exit;
													}
												break;

												case "GW":
													// weight line
													$weight = $line[3];
													$weight_unit = $line[4];
												break;

												case "LN":
													$linear_feet = $line[3];
												break;

												default:
													// other lines unneeded.
												break;
											}
										}

/*										// first line is barcode 
										$temp = fgets($fp);
										$line = split("\*", trim($temp));
										$barcode = $line[3];

										// second line is unnecessary
										$temp = fgets($fp);

										// third line has paper width
										$temp = fgets($fp);
										$line = split("\*", trim($temp));
										$roll_width = $line[3];

										// fourth line is unnecessary
										$temp = fgets($fp);

										// fifth line is weight
										$temp = fgets($fp);
										$line = split("\*", trim($temp));
										$weight = $line[3];
										$weight_unit = $line[4];
*/
										$batch_id = GetBatchID($basis_weight, $roll_width);
										if($batch_id == FALSE){
											fileMove($path, $dName, $fp, "bad");
											mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, "EDI - weight ".$basis_weight." width ".$roll_width." no conversion available in ".$dName, $mailheaders);
											Record_EDI($dName, $ST, "failed", "no weightXwidth conversion available");
											exit;
										}

										$cargo_desc_part_2 = $batch_id;

										$cargo_description = $cargo_desc_part_1." ".$cargo_desc_part_2." ".$cargo_desc_part_3;

//										$successful_import = Deal_With_Roll($cargo_description, $cust, $rolls_per_pack, $batch_id, $barcode, $weight, $weight_unit, $remark, $container_id, $EDItype, $millnumber, $list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL);

										$duplicate_CTPK_check = Duplicate_CTPK_Roll($cust, $barcode, $vessel);
										if($duplicate_CTPK_check != ""){
											fileMove($path, $dName, $fp, "bad");
											mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, "BC: ".$barcode."\n\nLR: ".$vessel."\nCust: ".$cust."\n\nis already in the system under\n\n".$duplicate_CTPK_check, $mailheaders);
											Record_EDI($dName, $ST, "failed", "CT Primary Key violation");
											exit;
										}

										$successful_import = Deal_With_Roll($cargo_description, $cust, 1, $batch_id, $barcode, $vessel, $roll_width, $roll_width, $weight, $weight_unit, $basis_weight, $linear_feet, $EDItype, $list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL);

//										$rolls_uploaded_so_far_this_segment++;
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
										mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " EDI - SE ".$line[2]." did not match ST value ".$ST." in file ".$dName, $mailheaders);
										Record_EDI($dName, $ST, "failed", "bad ST/SE");
										exit;
									} else {
										$ST = "";
									}
								break;


								default:  // an EDI line we don't need
								break;
							}
						} while($line[0] != "SE");  // end internal ST-SE block loop
					
						$emailBreakdown .= $cargo_desc_part_1."\t".$vessel."\t".$cust."\t".$rolls_in_this_segment_successful;
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
				$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA SET DOLEPAPER_ORIGINAL_MILL = '".$mill_from."' WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION LIKE '".$cargo_desc_part_1."%".$cargo_desc_part_3."')";
				$ora_success = ora_parse($cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not add original mill info in file ".$dName.".  EDI upload aborted.", $mailheaders);
					Record_EDI($dName, $ST, "failed", "CTAD original mill fault");
					exit;
				}
				$ora_success = ora_exec($cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not add original mill info in file ".$dName.".  EDI upload aborted.", $mailheaders);
					Record_EDI($dName, $ST, "failed", "CTAD original mill fault");
					exit;
				}

				fileMove($path, $dName, $fp, "good");
				mail($mailTO, "Dole EDI upload COMPLETE EDI - file ".$dName, "Upload complete, ".$total_rolls_in_file." in file, ".$total_rolls_accepted." accepted.  Breakdown:\r\n".$emailBreakdown."\r\n\r\nExceptions noted:\r\n".$list_of_unchangeable_rolls, $mailheaders);
				Record_EDI($dName, $ST, "passed", "");
			} else {
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, EDI - file ".$dName, "No GE or IEA segment closing found.", $mailheaders);
				Record_EDI($dName, $ST, "failed", "bad closing");
			}
		}
	}



function get_width_inches($value, $unit_meas){
	global $conn2;
	$short_term_cursor = ora_open($conn2);

	if($unit_meas == "IN"){
		return $value;
	} else {
		$sql = "SELECT CONVERSION_FACTOR FROM UNIT_CONVERSION_FROM_BNI WHERE PRIMARY_UOM = '".$unit_meas."' AND SECONDARY_UOM = 'IN'";
		$ora_success = ora_parse($short_term_cursor, $sql);
		$ora_success = ora_exec($short_term_cursor, $sql);
		if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			return FALSE;
		} else {
			return round($value * $row['CONVERSION_FACTOR'], 3);
		}
	}
}

function GetSender($line){
//	global $conn2;
//	$short_term_cursor = ora_open($conn2);
/*
	*	due to ever increasing numbers of EDI senders, and the currently observed phenominon that ONLY the IFP ones
	*	come to use "backwards", I am changing this function to only check "is or is not this EDI from IFP".
	*	the only part of code this value is used in is the determination of customer #, so that part has also
	*	been edited in the main code.
	$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS WHERE EDI_CODE LIKE '".substr($line[2], 0, 3)."%'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] > 0){
		return $line[2];
	} 

	$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS WHERE EDI_CODE LIKE '".substr($line[3], 0, 3)."%'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] > 0){
		return $line[3];
	} 

	// no valid conversion
	return FALSE;
*/

	if(substr($line[2], 0, 3) == "IFP" || substr($line[3], 0, 3) == "IFP"){
		return "IFP";
	} else {
		return "notIFP";
	}
}






function fileMove($path, &$filename, $fp, $type){
	global $conn2;


	$newfile = $filename.".".date('mdYhi');

	switch ($type) {
		case "bad":
			ora_rollback($conn2);
			fclose($fp);
//			copy($path."/".$filename , $path."failed/".$filename.".".date('mdYhi'));
			copy($path."/".$filename , $path."failed/".$newfile);
			unlink($path."/".$filename);
		break;

		case "good":
			ora_commit($conn2);
			fclose($fp);
//			copy($path."/".$filename , $path."complete/".$filename.".".date('mdYhi'));
			copy($path."/".$filename , $path."complete/".$newfile);
			unlink($path."/".$filename);
		break;

		case "booking":
			ora_rollback($conn2);
			fclose($fp);
//			copy($path."/".$filename , $path."booking/".$filename.".".date('mdYhi'));
			copy($path."/".$filename , $path."booking/".$newfile);
			unlink($path."/".$filename);
		break;
	}

	$filename = $newfile;
}

function getCustomerNumber($EDI_N1_value, &$type){
	global $conn2;
	$short_term_cursor = ora_open($conn2);

	$sql = "SELECT CUSTOMER_ID, PAPER_TYPE FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS WHERE EDI_CODE = '".$EDI_N1_value."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$type = $row['PAPER_TYPE'];
		return $row['CUSTOMER_ID'];
	} else {
		return FALSE;
	}

}

function GetBatchID($basis_weight, $roll_width){
	global $conn2;
	$short_term_cursor = ora_open($conn2);

	$sql = "SELECT PAPER_CODE FROM DOLEPAPER_EDI_IMPORT_CODES WHERE BASIS_WEIGHT = '".$basis_weight."' AND PAPER_WIDTH = '".$roll_width."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return $row['PAPER_CODE'];
	} else {
		return FALSE;
	}
}

function Deal_With_Roll($cargo_description, $cust, $rolls_per_pack, $batch_id, $barcode, $arrival_num, $cargo_size, $paper_width, $weight, $weight_unit, $basis_weight, $linear_feet, $EDItype, &$list_of_unchangeable_rolls, $path, $dName, $fp, $mailTO, $mailheaders, $emailBoL){

	echo "desc: ".$cargo_description."\n";
	echo "cust: ".$cust."\n";
	echo "rpp: ".$rolls_per_pack."\n";
	echo "batch: ".$batch_id."\n";
	echo "bc: ".$barcode."\n";
	echo "arv: ".$arrival_num."\n";
	echo "size: ".$cargo_size."\n";
	echo "width: ".$paper_width."\n";
	echo "wt: ".$weight."\n";
	echo "unit: ".$weight_unit."\n";
	echo "bw: ".$basis_weight."\n";
	echo "lf: ".$linear_feet."\n";
	echo "EDItype: ".$EDItype."\n";


	global $conn2;
	$short_term_cursor = ora_open($conn2);
	$change_cursor = ora_open($conn2);

	// determine if this needs a new Dock Ticket #, or is part of an old shipment
	$sql = "SELECT MAX(BOL) THE_MAX FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$max_dock_ticket = $short_term_row['THE_MAX'];

	$sql = "SELECT BOL FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION = '".$cargo_description."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$dock_ticket = $max_dock_ticket + 1;
	} else {
		$dock_ticket = $short_term_row['BOL'];
	}

	// decides what to do with a given roll, based on if this is an original, cancellation, or re-issue EDI block.
	// pass-by-reference variable $list_of_unchangeable_rolls keeps running track for email later


	switch ($EDItype) {
		case "00":		// original

			// check to make sure this roll isn't already present.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND CARGO_DESCRIPTION = '".$cargo_description."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$cargo_description." (Could not be entered as Original EDI receipt)\r\n";
				return FALSE;
			}

			// go ahead and insert it
			$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT, QTY_IN_HOUSE, BATCH_ID, PALLET_ID, ARRIVAL_NUM, CARGO_SIZE, MARK, WEIGHT, WEIGHT_UNIT, REMARK, VARIETY, RECEIVING_TYPE, BOL, SOURCE_NOTE, SOURCE_USER) VALUES ('1272', '".$cargo_description."', '1', '".$cust."', '".$rolls_per_pack."', '1', '".$batch_id."', '".$barcode."', '".$arrival_num."', '".$cargo_size."', '".$basis_weight."', '".$weight."', '".$weight_unit."', 'DOLEPAPERSYSTEM', '".$linear_feet."', 'T', '".$dock_ticket."', '".$dName."', 's-16')";
//			echo $sql."\n";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				Record_EDI($dName, $ST, "failed", "SQL fault CT");
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.\r\n\r\nSQL:".$sql, $mailheaders);
				Record_EDI($dName, $ST, "failed", "SQL fault CT");
				exit;
			}

			return TRUE;
		break;

		case "01":		// cancellation
		
			// check to see if roll being cancelled is even in system.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND CARGO_DESCRIPTION = '".$cargo_description."'";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be cancelled; roll not in system.)\r\n";
				return FALSE;
			}

			// check to see if roll being cancelled is already received.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND CARGO_DESCRIPTION = '".$cargo_description."' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be cancelled; roll already received.)\r\n";
				return FALSE;
			}

			// go ahead and delete it
			$sql = "DELETE FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND CARGO_DESCRIPTION = '".$cargo_description."'";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				Record_EDI($dName, $ST, "failed", "SQL fault CT");
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.\r\n\r\nSQL:".$sql, $mailheaders);
				Record_EDI($dName, $ST, "failed", "SQL fault CT");
				exit;
			}

			return TRUE;
		break;

		case "05":		// re-issue.

			// check to see if roll being re-submitted is already received.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND CARGO_DESCRIPTION = '".$cargo_description."' AND DATE_RECEIVED IS NOT NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$list_of_unchangeable_rolls .= $barcode." - ".$millnumber." (Could not be changed; roll already received.)\r\n";
				return FALSE;
			}

			// check to see if roll being re-submitted is in system, but not yet received.
			$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND CARGO_DESCRIPTION = '".$cargo_description."' AND DATE_RECEIVED IS NULL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			if(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

				// go ahead and update
				$sql = "UPDATE CARGO_TRACKING SET CARGO_DESCRIPTION = '".$cargo_description."', QTY_UNIT = '".$rolls_per_pack."', BATCH_ID = '".$batch_id."', WEIGHT = '".$weight."', WEIGHT_UNIT = '".$weight_unit."', REMARK = 'DOLEPAPERSYSTEM', VARIETY = '".$linear_feet."', CARGO_SIZE = '".$cargo_size."', MARK = '".$basis_weight."', BOL = '".$dock_ticket."', SOURCE_NOTE = '".$dName."' WHERE PALLET_ID = '".$barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$arrival_num."' AND COMMODITY_CODE = '1272' AND DATE_RECEIVED IS NULL";
				$ora_success = ora_parse($change_cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
					Record_EDI($dName, $ST, "failed", "SQL fault CT");
					exit;
				}
				$ora_success = ora_exec($change_cursor, $sql);
				if (!$ora_success){
					fileMove($path, $dName, $fp, "bad");
					mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not update pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
					Record_EDI($dName, $ST, "failed", "SQL fault CT");
					exit;
				}

				return TRUE;
			}

			// last case, roll not already present.  Go ahead and insert.
			$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT, QTY_IN_HOUSE, BATCH_ID, PALLET_ID, ARRIVAL_NUM, CARGO_SIZE, MARK, WEIGHT, WEIGHT_UNIT, REMARK, VARIETY, RECEIVING_TYPE, BOL, SOURCE_NOTE, SOURCE_USER) VALUES ('1272', '".$cargo_description."', '1', '".$cust."', '".$rolls_per_pack."', '1', '".$batch_id."', '".$barcode."', '".$arrival_num."', '".$cargo_size."', '".$basis_weight."', '".$weight."', '".$weight_unit."', 'DOLEPAPERSYSTEM', '".$linear_feet."', 'T', '".$dock_ticket."', '".$dName."', 's-16')";
			$ora_success = ora_parse($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not parse pallet ".$barcode." in file ".$dName.".  EDI upload aborted.", $mailheaders);
				Record_EDI($dName, $ST, "failed", "SQL fault CT");
				exit;
			}
			$ora_success = ora_exec($change_cursor, $sql);
			if (!$ora_success){
				fileMove($path, $dName, $fp, "bad");
				mail($mailTO, "Dole EDI upload FAILED, BoL ".$emailBoL, " DB Error; could not insert pallet ".$barcode." in file ".$dName.".  EDI upload aborted.\r\n\r\nSQL:".$sql, $mailheaders);
				Record_EDI($dName, $ST, "failed", "SQL fault CT");
				exit;
			}

			return TRUE;
		break;
	}
}

function Record_EDI($fName, $ST_segment, $result, $reason){
	global $conn2;
//	$short_term_cursor = ora_open($conn2);
	$change_cursor = ora_open($conn2);

	$sql = "INSERT INTO EDI_FILE_HISTORY
				(FILENAME,
				DATE_PARSED,
				ST_SEGMENT,
				FILE_RESULT,
				CARGO_SYSTEM,
				SHORT_REASON)
			VALUES
				('".$fName."',
				SYSDATE,
				'".$ST_segment."',
				'".$result."',
				'DOCKTICKET',
				'".$reason."')";
	$ora_success = ora_parse($change_cursor, $sql);
	$ora_success = ora_exec($change_cursor, $sql);
}

function Duplicate_CTPK_Roll($cust, $barcode, $vessel){
	global $conn2;
	$short_term_cursor = ora_open($conn2);

	$sql = "SELECT CARGO_DESCRIPTION, BOL, SOURCE_NOTE
			FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$barcode."'
				AND RECEIVER_ID = '".$cust."'
				AND ARRIVAL_NUM = '".$vessel."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no duplicates, return nothing
		return "";
	} else {
		// this pallet duplicated another one with the same PoW PK but different Dole specifics.  Return details for mailing.
		return "Cargo Description:  ".$row['CARGO_DESCRIPTION']."\nDock Ticket#:  ".$row['BOL']."\nPrevious Upload Details:  ".$row['SOURCE_NOTE'];
	}
}