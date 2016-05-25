<?
/*
*		Adam Walter, Feb 2014
*
*	This program is designed to parse an incoming-EDI file from
*	Dole customers.
*
*	Unlike their Paper EDI, which is a standardized 350, this one
*	is made by people who might have formatting issues.
*
*	... I'm not going to specify if the people or the files have the issues.
*
*****************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
//	ora_commitoff($conn2);
	$cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$path = "/web/web_pages/TS_Program/dole9722/";

	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{

	   	if ($dName == "."  || $dName == ".." || $dName == "Dole9722EDI.php" || $dName == "Dole9722EDI.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else{
			// here there be Dragons... and file pointers.
			$fp = fopen($dName, "r");
			$rowcount = 1;
			$useable_data = array();
			$returned_errors = "";
//			$dName = $dName.".".date('mdYhi');

			// put the header into the DB, and commit the transaction
			$sql = "SELECT DOLE_9722_SEQ.NEXTVAL THE_NEXT FROM DUAL";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);
			ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$key_id = $row['THE_NEXT'];

			if(strpos($dName, "Wanding") === false){
				$filetype = "Manual";
			} else {
				$filetype = "Auto";
			}

			$sql = "INSERT INTO DOLE_9722_HEADER
						(FILE_ID,
						FILENAME,
						INSERT_DATE,
						MAN_OR_AUTO,
						SAVE_TO_CT_STATUS)
					VALUES
						('".$key_id."',
						'".$dName."',
						SYSDATE,
						'".$filetype."',
						'PENDING')";
			$ora_success = ora_parse($short_term_cursor, $sql);
			$ora_success = ora_exec($short_term_cursor, $sql);

			if(strpos(strtoupper($dName), "CSV") === false){
				BadfileMove($path, $dName, $fp, $key_id, "Invalid File Format", "File must be in .xls format only.  Please reformat and resubmit.", $conn2);
			}

			while($temp = trim(fgets($fp))){
				$errors_this_line = "";
				$temp_split = explode(";", $temp);

				if($filetype == "Auto"){
					if(trim($temp_split[0]) != ""){
						// only save if a new value found
						$useable_data[$rowcount]["act"] = substr(trim($temp_split[0]), 0, 1);
						$useable_data[$rowcount]["act_full"] = trim($temp_split[0]);
					} else {
						// if blank field, keep the previous value.  If this is the first pass, this file will fail later on, so no biggie.
						$useable_data[$rowcount]["act"] = $useable_data[($rowcount - 1)]["act"];
						$useable_data[$rowcount]["act_full"] = $useable_data[($rowcount - 1)]["act_full"];
					}
					if(trim($temp_split[4]) != ""){
						// same as column 1
						$useable_data[$rowcount]["pool"] = strtoupper(trim($temp_split[4]));
					} else {
						$useable_data[$rowcount]["pool"] = $useable_data[($rowcount - 1)]["pool"];
					}
					$useable_data[$rowcount]["BC"] = trim($temp_split[6]);
					$useable_data[$rowcount]["qty"] = trim($temp_split[9]);
//					$useable_data[$rowcount]["time"] = trim($temp_split[10]);
						$time_split = explode(":", $temp_split[10]);
						$useable_data[$rowcount]["time"] = $time_split[0].":".min($time_split[1], 59);
					$useable_data[$rowcount]["order"] = strtoupper(trim($temp_split[11]));
					$useable_data[$rowcount]["comm"] = trim($temp_split[12]);
					$useable_data[$rowcount]["var"] = trim($temp_split[14]);
					$useable_data[$rowcount]["hour"] = trim($temp_split[15]);
				} else {
					if(trim($temp_split[0]) != ""){
						// only save if a new value found
						$useable_data[$rowcount]["act"] = substr(trim($temp_split[0]), 0, 1);
						$useable_data[$rowcount]["act_full"] = trim($temp_split[0]);
					} else {
						// if blank field, keep the previous value.  If this is the first pass, this file will fail later on, so no biggie.
						$useable_data[$rowcount]["act"] = $useable_data[($rowcount - 1)]["act"];
						$useable_data[$rowcount]["act_full"] = $useable_data[($rowcount - 1)]["act_full"];
					}
					if(trim($temp_split[1]) != ""){
						// same as column 1
						$useable_data[$rowcount]["pool"] = strtoupper(trim($temp_split[1]));
					} else {
						$useable_data[$rowcount]["pool"] = $useable_data[($rowcount - 1)]["pool"];
					}
					$useable_data[$rowcount]["BC"] = trim($temp_split[2]);
					$useable_data[$rowcount]["qty"] = trim($temp_split[3]);
						$time_split = explode(":", $temp_split[4]);
						$useable_data[$rowcount]["time"] = $time_split[0].":".min($time_split[1], 59);
					$useable_data[$rowcount]["order"] = strtoupper(trim($temp_split[5]));
					$useable_data[$rowcount]["comm"] = trim($temp_split[6]);
					$useable_data[$rowcount]["var"] = trim($temp_split[7]);
					$useable_data[$rowcount]["hour"] = trim($temp_split[8]);
				}

				if($rowcount == 11 && $filetype == "Auto" && ($temp != "Activity;;;;Pool;;Pallet Number;;;Boxes;Time;Order;Com;;Variety;Hour")) { 
					//|| ($temp != "Activity;;;;Pool;;Pallet Number;;;Boxes;Time;Order;Com;;Variety;Hour;"))) {
//					echo $temp."\n";
					BadfileMove($path, $dName, $fp, $key_id, "Bad Column Order", "Column Headings incorrect.  They should be in the right order and exactly match: 'Activity, Pool, Pallet Number, Boxes, Time, Order, Com, Variety, Hour'. The file you sent is attached.  Please resolve and resubmit.", $conn2);
				} elseif($rowcount == 1 && $filetype == "Manual" && $temp != "Activity;Pool;Pallet;Boxes;Time;Order;Cm;Var;Hour"){
//					echo $temp."\n";
					BadfileMove($path, $dName, $fp, $key_id, "Bad Column Order", "Column Headings incorrect.   They should be in the right order, and exactly match:  Activity, Pool, Pallet, Boxes, Time, Order, Cm, Var, Hour. The file you sent is attached.   Please resolve  and resubmit.", $conn2);
				}
				if($rowcount == 1 && $filetype == "Auto"){
					if(!ereg("^Report Date: ([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $useable_data[3]["act_full"])){
						BadfileMove($path, $dName, $fp, $key_id, "Invalid Report Date", "Report Date must be in mm/dd/yyyy format.", $conn2);
					} else {
						$date_of_file = str_replace("Report Date: ", "", $useable_data[3]["act_full"]);
						$sql = "UPDATE DOLE_9722_HEADER
								SET REPORT_DATE = TO_DATE('".$date_of_file."', 'MM/DD/YYYY')
								WHERE FILE_ID = '".$key_id."'"; 
						$ora_success = ora_parse($short_term_cursor, $sql);
						$ora_success = ora_exec($short_term_cursor, $sql);
					}
				} elseif($rowcount == 1 && $filetype == "Manual"){
					$tempsplit = explode("X", $dName); // date is in filename before the first capital X
					// echo $dName;
					if(!ereg("^([0-9]{1,2})-([0-9]{1,2})-([0-9]{2})$", $tempsplit[0])){
						BadfileMove($path, $dName, $fp, $key_id, "Invalid Report Date", "Report Date must be in mm-dd-yy format in front of the Filename.", $conn2);
					} else {
						$sql = "UPDATE DOLE_9722_HEADER
								SET REPORT_DATE = TO_DATE('".$tempsplit[0]."', 'MM-DD-YY')
								WHERE FILE_ID = '".$key_id."'"; 
						$ora_success = ora_parse($short_term_cursor, $sql);
						$ora_success = ora_exec($short_term_cursor, $sql);
					}
				}


				if(($rowcount > 11 && $filetype == "Auto") || ($rowcount > 1 && $filetype == "Manual" && $useable_data[$rowcount]["BC"] != "")){
					// these are the actual rows.  Proceed with each one as they come up.
					$sql = "INSERT INTO DOLE_9722_DETAIL
								(FILE_ID,
								ROW_NUM,
								FULL_LINE)
							VALUES
								('".$key_id."',
								'".$rowcount."',
								'".$temp."')";
					$ora_success = ora_parse($short_term_cursor, $sql);
					$ora_success = ora_exec($short_term_cursor, $sql);

//					if(substr_count($temp, ';') > 15){
//						$errors_this_line .= "Too many semicolons   \n";
//					}

					$errors_this_line .= validate_line($useable_data, $rowcount, $vessel, $conn2);

					if($errors_this_line != "" && strpos(strtoupper($useable_data[$rowcount]["BC"]), "PALLET") === false){
						$sql = "UPDATE DOLE_9722_DETAIL
								SET ERROR_INPUT_LINE = '".$errors_this_line."'
								WHERE FILE_ID = '".$key_id."'
									AND ROW_NUM = '".$rowcount."'";
						$ora_success = ora_parse($short_term_cursor, $sql);
						$ora_success = ora_exec($short_term_cursor, $sql);
					} else {
						$sql = "UPDATE DOLE_9722_DETAIL
								SET ACTIVITY = '".$useable_data[$rowcount]["act"]."',
									POOL = '".$useable_data[$rowcount]["pool"]."',
									PALLET_ID = '".$useable_data[$rowcount]["BC"]."',
									QTY = '".$useable_data[$rowcount]["qty"]."',
									THE_TIME = '".$useable_data[$rowcount]["time"]."',
									THE_ORDER = '".$useable_data[$rowcount]["order"]."',
									COMM = '".$useable_data[$rowcount]["comm"]."',
									VARIETY = '".$useable_data[$rowcount]["var"]."',
									THE_HOUR = '".$useable_data[$rowcount]["hour"]."'
								WHERE FILE_ID = '".$key_id."'
									AND ROW_NUM = '".$rowcount."'";
//						echo $sql."\n";
						$ora_success = ora_parse($short_term_cursor, $sql);
						$ora_success = ora_exec($short_term_cursor, $sql);
					}

				}

				$rowcount++;

				if(strpos(strtoupper($useable_data[($rowcount - 1)]["pool"]), "REPORT TOTALS") === false){
					// normal line, do nothing.
				} else {
					// this was the final line.  "read" the next line so taht we don't insert their crazy symbols to our DB.
					$temp = trim(fgets($fp));
				}

			}

			FinishedFileMove($path, $dName, $fp, $key_id, $conn2);
		}
	}


















function BadfileMove($path, &$filename, $fp, $key_id, $DB_reason, $Body_reason, $rfconn){
	// this function is for when something happens in the file "strong" enough for us to cancel the remaining script.
	$short_term_cursor = ora_open($rfconn);
	$ED_cursor = ora_open($rfconn);

	
	$sql = "UPDATE DOLE_9722_HEADER
			SET SAVE_TO_CT_STATUS = '".$DB_reason."'
			WHERE FILE_ID = '".$key_id."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
//	ora_rollback($rfconn);

	// proceed with file
	fclose($fp);
	copy($path."/".$filename , $path."failed/".$filename);
	unlink($path."/".$filename);

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEFILEFORMAT'";
	ora_parse($ED_cursor, $sql);
	ora_exec($ED_cursor);
	ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   

	$mailheaders = "From: ".$email_row['FROM']."\r\n";
	if($email_row['TEST'] == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,fduarte@port.state.de.us,lstewart@port.state.de.us\r\n";
	} else {
		$mailTO = $email_row['TO'];
		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = $email_row['SUBJECT'];
	$body = $email_row['NARRATIVE']."\r\n";
	$body = str_replace("_0_", $Body_reason."\r\n", $body);

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$attach = chunk_split(base64_encode(file_get_contents($path."failed/".$filename)));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/text; name=\"".$filename."\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach;
	$Content.="\r\n";

	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					DATE_JOB_COMPLETED,
					COMPLETION_STATUS,
					JOB_EMAIL_TO,
					JOB_EMAIL_CC,
					JOB_EMAIL_BCC,
					JOB_BODY)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'CONSTANTCRON',
					SYSDATE,
					'EMAIL',
					'DOLEFILEFORMAT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}

	
//	$mailheaders = "";

//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
//	$mailTO = "archive@port.state.de.us,awalter@port.state.de.us";

//	mail($mailTO, "File:  ".$filename."  ".$Subject_reason, "", $mailheaders);

	exit;

}

function FinishedFileMove($path, &$filename, $fp, $key_id, $rfconn){
	$short_term_cursor = ora_open($rfconn);

	$sql = "UPDATE DOLE_9722_HEADER
			SET SAVE_TO_CT_STATUS = 'PENDING'
			WHERE FILE_ID = '".$key_id."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
//	ora_rollback($rfconn);

	// proceed with file
	fclose($fp);
	copy($path."/".$filename , $path."success/".$filename);
	unlink($path."/".$filename);
//	$mailheaders = "";

//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
//	$mailTO = "archive@port.state.de.us,awalter@port.state.de.us";

//	mail($mailTO, "File:  ".$filename."  ".$Subject_reason, "Dole9722EDI Completed.", $mailheaders);

}

function validate_line($useable_data, $i, $vessel, $rfconn){

	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";


// step 1, make sure the header line is in place.
/*	if($useable_data[0]["act_full"] != "Activity" ||
		$useable_data[0]["pool"] != "POOL" ||
		$useable_data[0]["BC"] != "Pallet Number" ||
		$useable_data[0]["qty"] != "Boxes" ||
		$useable_data[0]["time"] != "Time" ||
		$useable_data[0]["order"] != "ORDER" ||
		$useable_data[0]["comm"] != "Commodity" ||
		$useable_data[0]["var"] != "Variety" ||
		$useable_data[0]["hour"] != "Hour"){
			$return .= "Header line does not match up as expected\n";
//			print_r($useable_data[0]);
	}
*/
// step 2, for each line...
//	$i = 1; // IGNORE HEADERS
//	$i = 0; // FILE HAS NO HEADERS
//	while($useable_data[$i]["act"] != ""){
//		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

//		if(strpos(strtoupper($useable_data[$i]["act_full"]), "TOTAL") === false){
			// we are skipping "total" lines


// 2 - 1)  Activity Type
	if($useable_data[$i]["act"] != 2 && $useable_data[$i]["act"] != 3 && $useable_data[$i]["act"] != 4){
		$return .= "The Activity Type is not a 2; 3 or 4.\n";
	}

// 2 - 2)  pool
	if(strlen($useable_data[$i]["pool"]) > 10){
		$return .= "The Pool number exceeds 10 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["pool"])){
		$return .= "The Pool number is required and must be alphanumeric.\n";
	}

// 2 - 3)  BC
	if(strlen($useable_data[$i]["BC"]) > 32){
		$return .= "The Pallet number exceeds 32 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
		$return .= "The Pallet number is required and must be alphanumeric\n";
	}

// 2 - 4)  Qty
	if(strlen($useable_data[$i]["qty"]) > 6){
		$return .= "The Quantity is longer than 6 digits.\n";
	}

	if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
		$return .= "The Quantity is required and must be numeric\n";
	}

// 2 - 5)  Time
//	if(strlen($useable_data[$i]["time"]) > 5){
//		$return .= "Time cannot be longer than 5 characters.\n";
//	}

	if(!ereg("^([0-2]{0,1}[0-9]{1}):([0-9]{2})$", $useable_data[$i]["time"])) {
		$return .= "The Time is required and must be in HH:MM format.\n";
	}

// 2 - 6)  Order
	if(strlen($useable_data[$i]["order"]) > 12){
		$return .= "The Order number exceeds 12 characters\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["order"])){
		$return .= "The Order Number is required and must be alphanumeric\n";
	}

// 2 - 7)  Commodity
	if(strlen($useable_data[$i]["comm"]) > 2){
		$return .= "The Commodity exceeds 2 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["comm"])){
		$return .= "The Commodity is required and must be alphanumeric\n";
	}

// 2 - 8)  Variety (?)
	if(strlen($useable_data[$i]["var"]) > 20){
		$return .= "The Variety exceeds 20 characters.\n";
	}

	if(!ereg("^([&a-zA-Z0-9 _-])+$", $useable_data[$i]["var"])){
		$return .= "The Variety is required and must be alphanumeric (or Ampersands)\n";
	}

// 2 - 9)  Hour (??)
	if(strlen($useable_data[$i]["hour"]) > 2){
		$return .= "The Hour exceeds 2 characters\n";
	}

	if(!ereg("^([0-2]{0,1}[0-9]{1})$", $useable_data[$i]["hour"])){
		$return .= "The Hour is required and must be in HH format and be between 0 and 23\n";
	}

// section 3:  valid string, invalid option
/*
// 3 - 1)  Commodity code
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM DOLE_COMM_CONV
			WHERE DOLE_COMM = '".$useable_data[$i]["comm"]."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		$return .= "Commodity Not in Dole Conversion Table.\n";
	}

// 3 - 2)  Customer#
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$useable_data[$i]["cust"]."'";
//		echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		$return .= "Customer# Not in CUSTOMER_PROFILE.\n";
	}

// 3 - 3)  Vessel
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM VESSEL_PROFILE
			WHERE LR_NUM = '".$useable_data[$i]["ves"]."'";
//		echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		$return .= "Vessel# Not in VESSEL_PROFILE.\n";
	}
*/
// 4 : make sure this isnt already in the system :)
/*
	$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data[$i]["pool"]."'
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND CA.ACTIVITY_NUM = '1'";
//			echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT_IMPORTED") >= 1 && $useable_data[$i]["act"] == "2"){
		$return .= "Pallet already imported previously.\n";
	} elseif(ociresult($stid, "THE_COUNT_IMPORTED") < 1 && $useable_data[$i]["act"] == "3"){
		$return .= "Cannot have shipout info for a pallet that is not imported\n";
	} 
	
// 5:  make sure it's still "in house" enough for a shipout.
	$sql = "SELECT COUNT(*) THE_IH
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data[$i]["pool"]."'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND QTY_IN_HOUSE >= '".$useable_data[$i]["qty"]."'";
//			echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_IH") < 1 && $useable_data[$i]["act"] == "3"){
		$return .= "Pallet showing as no longer in house and/or insufficient shipout qty for request, cannot enact outbound shipment entry.\n";
	} elseif(ociresult($stid, "THE_IH") > 1 && $useable_data[$i]["act"] == "3"){
		$return .= "Multiple Pallets Inhouse with sufficient qty to ship match this barcode.\n";
	}

// 6:  If this is a return, make sure it was shipped in the first place.
	$sql = "SELECT COUNT(*) THE_COUNT_OUTBOUND
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data[$i]["pool"]."'
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND CA.ACTIVITY_NUM != '1'
				AND CA.SERVICE_CODE = '6'
				AND CA.ACTIVITY_DESCRIPTION IS NULL";
//			echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT_OUTBOUND") > 1 && $useable_data[$i]["act"] == "4"){
		$return .= "Pallet has multiple outbound shipments.  Please handle return manually.\n";
	} elseif(ociresult($stid, "THE_COUNT_OUTBOUND") < 1 && $useable_data[$i]["act"] == "4"){
		$return .= "Pallet is not shipped out; cannot return.\n";
	} 
*/


//		$i++;
	return $return;

}
