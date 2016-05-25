<?
/*
*		Adam Walter, Feb 2014
*
*	This program is designed to take a parsed Dole9722 file and
*	Move it to CT.
*
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	// for each pending file...
	$sql = "SELECT TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') THE_DATE, FILE_ID, INSERT_DATE
			FROM DOLE_9722_HEADER
			WHERE SAVE_TO_CT_STATUS = 'PENDING'
			ORDER BY REPORT_DATE, MAN_OR_AUTO DESC";
	$header = ociparse($rfconn, $sql);
	ociexecute($header);
	while(ocifetch($header)){
		$date = ociresult($header, "THE_DATE");
		$lines_in_file = 0;

		// for each row in teh file...
		$sql = "SELECT * FROM DOLE_9722_DETAIL
				WHERE FILE_ID = '".ociresult($header, "FILE_ID")."'
					AND ERROR_INPUT_LINE IS NULL
				ORDER BY ROW_NUM";
		$detail = ociparse($rfconn, $sql);
		ociexecute($detail);
		while(ocifetch($detail)){
			$lines_in_file++;
			$QTY_alter_email = "";
			
			// we dont want to inset a "total" row to CT.
			if(strpos(strtoupper(ociresult($detail, "PALLET_ID")), "PALLET") === false){
				$detail_row["act"] = ociresult($detail, "ACTIVITY");
				$detail_row["pool"] = ociresult($detail, "POOL");
				$detail_row["BC"] = ociresult($detail, "PALLET_ID");
				$detail_row["qty"] = ociresult($detail, "QTY");
				$detail_row["time"] = ociresult($detail, "THE_TIME");
				$detail_row["order"] = ociresult($detail, "THE_ORDER");
				$detail_row["comm"] = ociresult($detail, "COMM");
				$detail_row["var"] = ociresult($detail, "VARIETY");
				$detail_row["hour"] = ociresult($detail, "THE_HOUR");

				$line_result = validate_upload($detail_row, $rfconn);
				if($line_result == ""){

					$sql = "SELECT PORT_COMM FROM DOLE_COMM_CONV
							WHERe DOLE_COMM = '".$detail_row["comm"]."'";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
					ocifetch($stid);
					$comm = ociresult($stid, "PORT_COMM");

					if($detail_row["act"] == 2){
						$sql = "INSERT INTO CARGO_TRACKING
									(ARRIVAL_NUM,
									RECEIVER_ID,
									PALLET_ID,
									COMMODITY_CODE,
									VARIETY,
									QTY_RECEIVED,
									QTY_IN_HOUSE,
									RECEIVING_TYPE)
								VALUES
									('".$detail_row['pool']."',
									'9722',
									'".$detail_row['BC']."',
									'".$comm."',
									'".$detail_row['var']."',
									'".$detail_row['qty']."',
									'".$detail_row['qty']."',
									'T')";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
		//				echo $sql."\n";

						$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
								SET DOLE_POOL = '".$detail_row['pool']."'
								WHERE PALLET_ID = '".$detail_row['BC']."'
									AND RECEIVER_ID = '9722'
									AND ARRIVAL_NUM = '".$detail_row['pool']."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
		//				echo $sql."\n";

						ReceivePallet($detail_row['BC'], 9722, $detail_row['pool'], $date, $detail_row['time'], $rfconn);
					
					} elseif($detail_row["act"] == 3){
						$sql = "SELECT CT.ARRIVAL_NUM 
								FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
								WHERE CT.PALLET_ID = '".$detail_row['BC']."'
									AND CT.RECEIVER_ID = '9722'
									AND QTY_IN_HOUSE > 0
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$ves = ociresult($stid, "ARRIVAL_NUM");

						$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_REC, QTY_IN_HOUSE
								FROM CARGO_TRACKING
								WHERE PALLET_ID = '".$detail_row['BC']."'
									AND RECEIVER_ID = '9722'
									AND ARRIVAL_NUM = '".$ves."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$is_rec = ociresult($stid, "THE_REC");
						if($is_rec == "NONE"){
							ReceivePallet($detail_row['BC'], 9722, $ves, $date, $detail_row['time'], $rfconn);
						}
						$qty_IH = ociresult($stid, "QTY_IN_HOUSE");
						if($detail_row["qty"] > $qty_IH){
							$QTY_alter_email .= "Pallet ".$detail_row['BC']." did not have the requested QTY of ".$detail_row["qty"]." boxes, and so Was shipped out at ".$qty_IH." boxes instead\n";;
							$detail_row["qty"] = $qty_IH;
						}

						$sql = "UPDATE CARGO_TRACKING
								SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$detail_row['qty'];
						$sql .= " WHERE PALLET_ID = '".$detail_row['BC']."' 
									AND RECEIVER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

						$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
								FROM CARGO_ACTIVITY 
								WHERE PALLET_ID = '".$detail_row['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$next_num = ociresult($stid, "THE_MAX") + 1;

						$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM)
								VALUES
								('".$next_num."',
								'6',
								'".$detail_row['qty']."',
								'8761',
								'".$detail_row['order']."',
								'9722',
								TO_DATE('".$date." ".$detail_row["time"].":30', 'MM/DD/YYYY HH24:MI:SS'),
								'".$detail_row['BC']."',
								'".$ves."')";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
					}elseif($detail_row["act"] == 4){
						$sql = "SELECT CT.ARRIVAL_NUM 
								FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
								WHERE CT.PALLET_ID = '".$detail_row['BC']."'
									AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
									AND CT.RECEIVER_ID = '9722'
									AND QTY_IN_HOUSE <= 10
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$ves = ociresult($stid, "ARRIVAL_NUM");

						$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
								FROM CARGO_ACTIVITY 
								WHERE PALLET_ID = '".$detail_row['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$next_num = ociresult($stid, "THE_MAX") + 1;

						$sql = "SELECT ACTIVITY_NUM, QTY_CHANGE, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE 
								FROM CARGO_ACTIVITY 
								WHERE PALLET_ID = '".$detail_row['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'
									AND SERVICE_CODE = '6'
								ORDER BY ACTIVITY_NUM DESC";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$replace_num = ociresult($stid, "ACTIVITY_NUM");
						$qty_to_add = ociresult($stid, "QTY_CHANGE");
						$date_of_original_act = ociresult($stid, "THE_DATE");
						if($date_of_original_act == $date){
							$serv_code = "13";
						} else {
							$serv_code = "7";
						}
						$sql = "UPDATE CARGO_TRACKING
								SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_to_add;
						$sql .= " WHERE PALLET_ID = '".$detail_row['BC']."' 
									AND RECEIVER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

						$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM)
								VALUES
								('".$next_num."',
								'".$serv_code."',
								'".$qty_to_add."',
								'8761',
								'".$detail_row['order']."',
								'9722',
								TO_DATE('".$date." ".$detail_row["time"].":30', 'MM/DD/YYYY HH24:MI:SS'),
								'".$detail_row['BC']."',
								'".$ves."')";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

						$sql = "UPDATE CARGO_ACTIVITY
								SET ACTIVITY_DESCRIPTION = 'RETURN'";
						$sql .= " WHERE PALLET_ID = '".$detail_row['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'
									AND ACTIVITY_NUM = '".$replace_num."'";
	//						echo $sql."\n";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
					}

				} else {
					$sql = "UPDATE DOLE_9722_DETAIL
							SET ERROR_MOVE_TO_DB = '".$line_result."'
							WHERE FILE_ID = '".ociresult($header, "FILE_ID")."'
								AND ROW_NUM = '".ociresult($detail, "ROW_NUM")."'";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
				}
			}

		}

		$sql = "UPDATE DOLE_9722_HEADER
				SET SAVE_TO_CT_STATUS = 'COMPLETE',
					MOVE_TO_CT_DATE = SYSDATE
				WHERE FILE_ID = '".ociresult($header, "FILE_ID")."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);


		// compile error file for email
		$sql = "SELECT DD.*, DECODE(DD.ERROR_INPUT_LINE, NULL, DD.ERROR_MOVE_TO_DB, DD.ERROR_INPUT_LINE) THE_ERROR
				FROM DOLE_9722_DETAIL DD
				WHERE FILE_ID = '".ociresult($header, "FILE_ID")."'
					AND (ERROR_MOVE_TO_DB IS NOT NULL OR ERROR_INPUT_LINE IS NOT NULL)
				ORDER BY DECODE(ERROR_MOVE_TO_DB, NULL, 2, 1), ACTIVITY, ROW_NUM";
		$errors = ociparse($rfconn, $sql);
		ociexecute($errors);
		if(!ocifetch($errors)){
//			$error_xls = "";
			$error_csv = "";
			$body_message = "A total of ".$lines_in_file." pallets were processed.  There were no errors.";
		} else {
			$error_counter = 0;
			$error_csv = "Activity,Pool,Pallet,Boxes,Time,Order,Cm,Var,Hour,Error\n";
/*			$error_xls = "<table>
							<tr>
								<td>Activity</td>
								<td>Pool</td>
								<td>Pallet</td>
								<td>Boxes</td>
								<td>Time</td>
								<td>Order</td>
								<td>Cm</td>
								<td>Var</td>
								<td>Hour</td>
								<td>Error</td>
							</tr>";
*/
			do {
				if(ociresult($errors, "ERROR_MOVE_TO_DB") != ""){
					$error_csv .= ociresult($errors, "ACTIVITY").",".
									ociresult($errors, "POOL").",".
									ociresult($errors, "PALLET_ID").",".
									ociresult($errors, "QTY").",".
									ociresult($errors, "THE_TIME").",".
									ociresult($errors, "THE_ORDER").",".
									ociresult($errors, "COMM").",".
									ociresult($errors, "VARIETY").",".
									ociresult($errors, "THE_HOUR").",".
									ociresult($errors, "THE_ERROR")."\n";
/*
					$error_xls .= "<tr>
										<td>".ociresult($errors, "ACTIVITY")."</td>
										<td>".ociresult($errors, "POOL")."</td>
										<td>".ociresult($errors, "PALLET_ID")."</td>
										<td>".ociresult($errors, "QTY")."</td>
										<td>".ociresult($errors, "THE_TIME")."</td>
										<td>".ociresult($errors, "THE_ORDER")."</td>
										<td>".ociresult($errors, "COMM")."</td>
										<td>".ociresult($errors, "VARIETY")."</td>
										<td>".ociresult($errors, "THE_HOUR")."</td>
										<td>".ociresult($errors, "THE_ERROR")."</td>
									</tr>";
*/
				} else {
					$error_csv .= ociresult($errors, "FULL_LINE").",,,,,,,Could not be Successfully Split by PoW,".ociresult($errors, "THE_ERROR")."\n";
/*
					$error_xls .= "<tr>
										<td colspan=\"8\">".ociresult($errors, "FULL_LINE")."</td>
										<td>Could not be Successfully Split by PoW</td>
										<td>".ociresult($errors, "THE_ERROR")."</td>
									</tr>";
*/
				}
				$error_counter++;
			} while(ocifetch($errors));

//			$error_xls .= "</table>";

			$body_message = "A total of ".$lines_in_file." pallets were processed.\r\n\r\nA total of ".$error_counter." errors were found.  Attached is the error file.  Please fix the pallets and then delete the error description column.  Email the file back to DoleEdi@port.state.de.us.";
		}

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLEACTIVITY'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$mailSubject = ociresult($email, "SUBJECT");
		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_0_", $body_message."\r\n\r\n".$QTY_alter_email, $body);

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";
/*
		$attach = chunk_split(base64_encode(file_get_contents("./dole9722/success/".ociresult($header, "FILENAME"))));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/Excel; name=\"".ociresult($header, "FILENAME")."\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
*/
//		if($error_xls != ""){
		if($error_csv != ""){
			$attach = chunk_split(base64_encode($error_csv));
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/text; name=\"Errors_".date('mdYhis').".csv\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";
		}

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
						'DOLEACTIVITY',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
		}
	}


















function validate_upload($useable_data, $rfconn){

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

/*
// 2 - 1)  Activity Type
	if($useable_data[$i]["act"] != 2 && $useable_data[$i]["act"] != 3 && $useable_data[$i]["act"] != 4){
		$return .= "Activity Type can only be \"2\" or \"3\" or \"4\"\n";
	}

// 2 - 2)  pool
	if(strlen($useable_data[$i]["pool"]) > 10){
		$return .= "Pool cannot be longer than 10 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["pool"])){
		$return .= "Pool is required and must be alphanumeric.\n";
	}

// 2 - 3)  BC
	if(strlen($useable_data[$i]["BC"]) > 32){
		$return .= "Barcode is required and cannot be longer thatn 32 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
		$return .= "Barcode is required and must be alphanumeric.\n";
	}

// 2 - 4)  Qty
	if(strlen($useable_data[$i]["qty"]) > 6){
		$return .= "QTY cannot be longer than 6 characters.\n";
	}

	if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
		$return .= "QTY is required and must be numeric.\n";
	}

// 2 - 5)  Time
	if(strlen($useable_data[$i]["time"]) > 5){
		$return .= "Time cannot be longer than 5 characters.\n";
	}

	if(!ereg("^([0-1]{0,1}[0-9]{1}):([0-5][0-9])$", $useable_data[$i]["time"])) {
		$return .= "Time is required and must be in HH:MM format.\n";
	}

// 2 - 6)  Order
	if(strlen($useable_data[$i]["order"]) > 12){
		$return .= "Order cannot be longer than 12 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["order"])){
		$return .= "Order is required and must be alphanumeric.\n";
	}

// 2 - 7)  Commodity
	if(strlen($useable_data[$i]["comm"]) > 2){
		$return .= "Commodity cannot be longer than 2 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["comm"])){
		$return .= "Commodity is required and must be numeric.\n";
	}

// 2 - 8)  Variety (?)
	if(strlen($useable_data[$i]["var"]) > 20){
		$return .= "Variety cannot be longer than 20 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9 _-])+$", $useable_data[$i]["var"])){
		$return .= "Variety is required and must be alphanumeric.\n";
	}

// 2 - 9)  Hour (??)
	if(strlen($useable_data[$i]["hour"]) > 2){
		$return .= "Hour cannot be longer than 2 characters.\n";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["hour"])){
		$return .= "Hour is required and must be alphanumeric.\n";
	}
*/
// section 3:  valid string, invalid option

// 3 - 1)  Commodity code
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM DOLE_COMM_CONV
			WHERE DOLE_COMM = '".$useable_data["comm"]."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		$return .= "The Commodity entered is not recognized by our system.  Please correct and resubmit or contact Port before resubmitting. \n";
	}
/*
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
	$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') THE_REC
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data["pool"]."'
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
	if(ociresult($stid, "THE_COUNT_IMPORTED") >= 1 && $useable_data["act"] == "2"){
		$return .= "The pallet has already been imported for the Pool #/Pallet ID entered at ".ociresult($stid, "THE_REC")."\n";
	} elseif(ociresult($stid, "THE_COUNT_IMPORTED") < 1 && $useable_data["act"] == "3"){
		$return .= "You cannot ship out a pallet that has not been received\n";
	} 
	
// 5:  make sure it's still "in house" enough for a shipout.
	$sql = "SELECT COUNT(*) THE_IH
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data["pool"]."'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND QTY_IN_HOUSE >0";
//			echo $sql."\n";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_IH") < 1 && $useable_data["act"] == "3"){
		$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') THE_OUT
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = '".$useable_data["BC"]."'
					AND CTAD.DOLE_POOL = '".$useable_data["pool"]."'
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.RECEIVER_ID = '9722'
					AND QTY_IN_HOUSE = 0
					AND CA.SERVICE_CODE = '6'
					AND CA.ACTIVITY_DESCRIPTION IS NULL";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$return .= "The pallet is no longer in house to ship; it shipped out on ".ociresult($stid, "THE_OUT")."\n";
	} elseif(ociresult($stid, "THE_IH") > 1 && $useable_data["act"] == "3"){
		$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') THE_DATE
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".$useable_data["BC"]."'
					AND CTAD.DOLE_POOL = '".$useable_data["pool"]."'
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '9722'
					AND QTY_IN_HOUSE >0";
	//			echo $sql."\n";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		$return .= "The pallet/pool number matches more than one pallet in the system.  The dates received  were: ";
		while(ocifetch($stid)){
			$return .= ociresult($stid, "THE_DATE")." ";
		}
		$return .= "\n";
		
	}

// 6:  If this is a return, make sure it was shipped in the first place.
	$sql = "SELECT COUNT(*) THE_COUNT_OUTBOUND
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data["pool"]."'
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
	if(ociresult($stid, "THE_COUNT_OUTBOUND") > 1 && $useable_data["act"] == "4"){
		$return .= "There is a problem with this return.  Please contact the port to resolve.\n";
	} elseif(ociresult($stid, "THE_COUNT_OUTBOUND") < 1 && $useable_data["act"] == "4"){
		$sql = "SELECT QTY_IN_HOUSE
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".$useable_data["BC"]."'
					AND CTAD.DOLE_POOL = '".$useable_data["pool"]."'
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '9722'
					AND QTY_IN_HOUSE >0";
	//			echo $sql."\n";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$return .= "The pallet is still in-house with ".ociresult($stid, "QTY_IN_HOUSE")." boxes; therefore; it cannot be returned.\n";
	} 



//		$i++;
	return $return;

}




function ReceivePallet($plt, $cust, $ves, $date, $time, $rfconn){
			$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM,
						SERVICE_CODE,
						ORDER_NUM,
						QTY_CHANGE,
						ACTIVITY_ID,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
					(SELECT
						'1',
						DECODE(RECEIVING_TYPE, 'T', '8', '1'),
						DECODE(RECEIVING_TYPE, 'T', ARRIVAL_NUM, NULL),
						QTY_RECEIVED,
						'8761',
						RECEIVER_ID,
						TO_DATE('".$date." ".$time.":00', 'MM/DD/YYYY HH24:MI:SS'),
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_RECEIVED
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$plt."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$ves."'
					)";
//			echo $sql;
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "UPDATE CARGO_TRACKING
					SET DATE_RECEIVED = 
						(SELECT DATE_OF_ACTIVITY
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".$plt."' 
							AND ARRIVAL_NUM = '".$ves."' 
							AND CUSTOMER_ID = '".$cust."' 
							AND ACTIVITY_NUM = '1')
					WHERE PALLET_ID = '".$plt."' 
						AND ARRIVAL_NUM = '".$ves."' 
						AND RECEIVER_ID = '".$cust."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
}





?>













