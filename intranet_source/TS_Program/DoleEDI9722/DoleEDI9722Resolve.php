<?
/*
*	March 2014
*
*	Script takes data from Dole9722 upload,
*	moves it to an interim raw_dump table,
*	and (tries) to move it into C_T and C_A.
*
*	Results emailed at end.
****************************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}


	$path = "/web/web_pages/TS_Program/DoleEDI9722/";
//	$path = "/web/web_pages/TS_Testing/DoleEDI9722/";


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{
	   	if ($dName == "."  || $dName == ".." || $dName == "DoleEDI9722Resolve.php" || $dName == "DoleEDI9722Resolve.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else {
			$QTY_alter_email = "";

			$sql = "SELECT DOLE9722_UPLOAD_SEQ.NEXTVAL THE_NEXT FROM DUAL";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$id = ociresult($stid, "THE_NEXT");

			$sql = "INSERT INTO DOLE9722_RAW_FILE_HEADER
						(FILE_ID,
						FILENAME,
						UPLOAD_TIME)
					VALUES
						('".$id."',
						'".$dName."',
						SYSDATE)";
//			echo $sql;
//			exit;
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			// perform some file-level stuff.  if a WHOLE FILE is bad, send a notification and exit the script.
			$fp = fopen($dName, "r");
			if(!$fp){
				BadFileMove($path, $dName, $fp, $id, "File could not be opened for reading.  Please contact PoW.", $rfconn);
				exit;
			}
			$temp = explode("_", $dName);
			if(date('w', mktime(0,0,0,substr($temp[3], 4, 2),substr($temp[3], 6, 2),substr($temp[3], 0, 4))) == "0" &&
						date('w', mktime(0,0,0,date('m'),date('d'),date('Y'))) == "0") {
				$date = substr($temp[3], 4, 2)."/".(substr($temp[3], 6, 2) - 1)."/".substr($temp[3], 0, 4);
			} else {
				$date = substr($temp[3], 4, 2)."/".substr($temp[3], 6, 2)."/".substr($temp[3], 0, 4);
			}
//			echo "date: ".$date."\n";
//			echo "filename check: ".date('w', mktime(0,0,0,substr($temp[3], 4, 2),substr($temp[3], 6, 2),substr($temp[3], 0, 4)))."\n";
//			echo "current date check: ".date('w', mktime(0,0,0,date('m'),date('d'),date('Y')))."\n";

			if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $date)){
				BadFileMove($path, $dName, $fp, $id, "Filename did not contain the Date in the expected location.", $rfconn);
				exit;
			}
			if(strpos(strtoupper($dName), "CSV") === false){
				BadFileMove($path, $dName, $fp, $id, "File was not a CSV.", $rfconn);
				exit;
			}

			// alright, file level checked out.
			// populate all fields into an array
			$row_num = 1;
			while($temp = fgets($fp)){
				$temp = str_replace("'", "`", $temp);
				$temp = str_replace("\"", "", $temp);
				$temp = str_replace("\\", "", $temp);
				$temp_split = explode(",", $temp);

				$useable_data[$row_num]["text43"] = trim($temp_split[0]);
				$useable_data[$row_num]["text54"] = trim($temp_split[1]);
				$useable_data[$row_num]["text30"] = trim($temp_split[2]);
				$useable_data[$row_num]["text40"] = trim($temp_split[3]);
				$useable_data[$row_num]["text65"] = trim($temp_split[4]);
				$useable_data[$row_num]["text72"] = trim($temp_split[5]);
				$useable_data[$row_num]["text77"] = trim($temp_split[6]);
				$useable_data[$row_num]["text47"] = trim($temp_split[7]);
				$useable_data[$row_num]["group1"] = trim($temp_split[8]);
				$useable_data[$row_num]["group2"] = trim($temp_split[9]);
				$useable_data[$row_num]["pallet_id"] = trim($temp_split[10]);
				$useable_data[$row_num]["boxes2"] = trim($temp_split[11]);
				$useable_data[$row_num]["transtime"] = trim($temp_split[12]);
				$useable_data[$row_num]["order_num"] = trim($temp_split[13]);
				$useable_data[$row_num]["comm"] = trim($temp_split[14]);
				$useable_data[$row_num]["var"] = trim($temp_split[15]);
				$useable_data[$row_num]["act_type"] = trim($temp_split[16]);
				$useable_data[$row_num]["text61"] = trim($temp_split[17]);
				$useable_data[$row_num]["text62"] = trim($temp_split[18]);
				$useable_data[$row_num]["text63"] = trim($temp_split[19]);
				$useable_data[$row_num]["text56"] = trim($temp_split[20]);
				$useable_data[$row_num]["text50"] = trim($temp_split[21]);
				$useable_data[$row_num]["text51"] = trim($temp_split[22]);
				$useable_data[$row_num]["text52"] = trim($temp_split[23]);
				$useable_data[$row_num]["text38"] = trim($temp_split[24]);
				$useable_data[$row_num]["text39"] = trim($temp_split[25]);
				$useable_data[$row_num]["text41"] = trim($temp_split[26]);
				$useable_data[$row_num]["fullrow"] = trim($temp);

				if(substr($useable_data[$row_num]["pallet_id"], 0, 2) == "00"){
					$useable_data[$row_num]["pallet_id"] = substr($useable_data[$row_num]["pallet_id"], 2);
				}

				// sadly, we are forced to accept non-time values in the time field, and do the calculations ourselves.
				if(is_numeric($useable_data[$row_num]["transtime"]) && strpos($useable_data[$row_num]["transtime"], ":") === false){
					$hours = floor($useable_data[$row_num]["transtime"] * 24);
					$mins = round((($useable_data[$row_num]["transtime"]* 24) - $hours) * 60);
					$useable_data[$row_num]["transtime"] = $hours.":".$mins;
//					echo "newtime: ".$useable_data[$row_num]["transtime"]."\n";
				}
				$timetemp = explode(":", $useable_data[$row_num]["transtime"]);
				if($timetemp[1] > 59){
					$useable_data[$row_num]["transtime"] = $timetemp[0].":59";
				}

				$row_num++;
			}

			// next, check each row for DB validity
			// start at $i = 2, so that we ignore the header row
			for($i = 2; ($i < $row_num) && $useable_data[$i]["fullrow"] != ""; $i++){
				$valid = ValidateDBStrict($useable_data[$i], $i, $rfconn);
//				echo "i: ".$i."\n";
				if($valid == ""){
					// passed DB strict test.
					$sql = "INSERT INTO DOLE9722_RAW_FILE_DETAIL
								(FILE_ID,
								ROW_NUM,
								TEXTBOX43,
								TEXTBOX54,
								TEXTBOX30,
								TEXTBOX40,
								TEXTBOX65,
								TEXTBOX72,
								TEXTBOX77,
								TEXTBOX47,
								GROUP1,
								GROUP2,
								PALLET_NBR,
								BOXES2,
								TRANSTIME,
								ORDER_NBR,
								COMMODITY,
								VARIETY,
								ACTIVITY_TYPE,
								TEXTBOX61,
								TEXTBOX62,
								TEXTBOX63,
								TEXTBOX56,
								TEXTBOX50,
								TEXTBOX51,
								TEXTBOX52,
								TEXTBOX38,
								TEXTBOX39,
								TEXTBOX41,
								FULL_LINE)
							VALUES
								('".$id."',
								'".$i."',
								'".$useable_data[$i]["text43"]."',
								'".$useable_data[$i]["text54"]."',
								'".$useable_data[$i]["text30"]."',
								'".$useable_data[$i]["text40"]."',
								'".$useable_data[$i]["text65"]."',
								'".$useable_data[$i]["text72"]."',
								'".$useable_data[$i]["text77"]."',
								'".$useable_data[$i]["text47"]."',
								'".$useable_data[$i]["group1"]."',
								'".$useable_data[$i]["group2"]."',
								'".$useable_data[$i]["pallet_id"]."',
								'".$useable_data[$i]["boxes2"]."',
								'".$useable_data[$i]["transtime"]."',
								'".$useable_data[$i]["order_num"]."',
								'".$useable_data[$i]["comm"]."',
								'".$useable_data[$i]["var"]."',
								'".$useable_data[$i]["act_type"]."',
								'".$useable_data[$i]["text61"]."',
								'".$useable_data[$i]["text62"]."',
								'".$useable_data[$i]["text63"]."',
								'".$useable_data[$i]["text56"]."',
								'".$useable_data[$i]["text50"]."',
								'".$useable_data[$i]["text51"]."',
								'".$useable_data[$i]["text52"]."',
								'".$useable_data[$i]["text38"]."',
								'".$useable_data[$i]["text39"]."',
								'".$useable_data[$i]["text41"]."',
								'".$useable_data[$i]["fullrow"]."')";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
				} else {
//					$sql = "UPDATE DOLE9722_RAW_FILE_HEADER
//							SET INVALID_DB_LINES = INVALID_DB_LINES + 1
//							WHERE FILE_ID = '".$id."'";
//					$stid = ociparse($rfconn, $sql);
//					ociexecute($stid);

					$sql = "INSERT INTO DOLE9722_RAW_FILE_DETAIL
								(FILE_ID,
								ROW_NUM,
								FULL_LINE,
								ERROR_DB_STRICT)
							VALUES
								('".$id."',
								'".$i."',
								'".$useable_data[$i]["fullrow"]."',
								'".$valid."')";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
				}
			}


			// all lines are in DB (in some form or another)
			// next, we take the DB-VALID lines, and see if they can be mvoed to CT and CA.
			$sql = "SELECT *
					FROM DOLE9722_RAW_FILE_DETAIL
					WHERE FILE_ID = '".$id."'
					ORDER BY TRANSTIME, PALLET_NBR";
			$detail_list = ociparse($rfconn, $sql);
			ociexecute($detail_list);
			while(ocifetch($detail_list)){
				$detail_row["row"] = ociresult($detail_list, "ROW_NUM");
				$detail_row["act"] = substr(ociresult($detail_list, "ACTIVITY_TYPE"), 0, 1);
				$detail_row["pool"] = ociresult($detail_list, "GROUP2");
				$detail_row["BC"] = ociresult($detail_list, "PALLET_NBR");
				$detail_row["qty"] = ociresult($detail_list, "BOXES2");
				$detail_row["time"] = ociresult($detail_list, "TRANSTIME");
				$detail_row["order"] = ociresult($detail_list, "ORDER_NBR");
				$detail_row["comm"] = ociresult($detail_list, "COMMODITY");
				$detail_row["var"] = ociresult($detail_list, "VARIETY");

				// for each row, ordered by time "input", we verify the row...
				$valid_activity = Validate_record($detail_row, $rfconn);

				if($valid_activity == ""){
					$total_counts["good"]++;

					// get our commodity code
					$sql = "SELECT PORT_COMM FROM DOLE_COMM_CONV
							WHERE DOLE_COMM = '".$detail_row["comm"]."'";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
					ocifetch($stid);
					$comm = ociresult($stid, "PORT_COMM");

					switch($detail_row["act"]){
						case "2": // this is a "truck in"
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

							$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
									SET DOLE_POOL = '".$detail_row['pool']."'
									WHERE PALLET_ID = '".$detail_row['BC']."'
										AND RECEIVER_ID = '9722'
										AND ARRIVAL_NUM = '".$detail_row['pool']."'";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);

							ReceivePallet($detail_row['BC'], 9722, $detail_row['pool'], $date, $detail_row['time'], $rfconn);
						break;

						case "3": // this is a ship-out
							$sql = "SELECT CT.ARRIVAL_NUM 
									FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
									WHERE CT.PALLET_ID = '".$detail_row['BC']."'
										AND CT.RECEIVER_ID = '9722'
										AND QTY_IN_HOUSE > 0
										AND CT.PALLET_ID = CTAD.PALLET_ID
										AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
										AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							$ves = ociresult($stid, "ARRIVAL_NUM");

							// how much is actually being shipped?
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
								// receive it if for some reason it wasnt already
								ReceivePallet($detail_row['BC'], 9722, $ves, $date, $detail_row['time'], $rfconn);
							}
							$qty_IH = ociresult($stid, "QTY_IN_HOUSE");
							if($detail_row["qty"] > $qty_IH){
								$QTY_alter_email .= "Pallet ".$detail_row['BC']." did not have the requested QTY of ".$detail_row["qty"]." boxes, and so Was shipped out at ".$qty_IH." boxes instead\r\n";
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
							echo $sql."\n";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
						break;

						case "4": // this is a return
							$sql = "SELECT CT.ARRIVAL_NUM 
									FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
									WHERE CT.PALLET_ID = '".$detail_row['BC']."'
										AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
										AND CT.RECEIVER_ID = '9722'
										AND QTY_IN_HOUSE <= 10
										AND CT.PALLET_ID = CTAD.PALLET_ID
										AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
										AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							$ves = ociresult($stid, "ARRIVAL_NUM");

							$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
									FROM CARGO_ACTIVITY 
									WHERE PALLET_ID = '".$detail_row['BC']."' 
										AND CUSTOMER_ID = '9722' 
										AND ARRIVAL_NUM = '".$ves."'";
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
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);

							$sql = "UPDATE CARGO_ACTIVITY
									SET ACTIVITY_DESCRIPTION = 'RETURN'";
							$sql .= " WHERE PALLET_ID = '".$detail_row['BC']."' 
										AND CUSTOMER_ID = '9722' 
										AND ARRIVAL_NUM = '".$ves."'
										AND ACTIVITY_NUM = '".$replace_num."'";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
						break;
					}
				} else {
					$sql = "UPDATE DOLE9722_RAW_FILE_DETAIL
							SET ERROR_VALID_DAT = '".$valid_activity."'
							WHERE FILE_ID = '".$id."'
								AND ROW_NUM = '".$detail_row["row"]."'";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
				}
			}

			// end of file.  wrap it up.
//			$sql = "UPDATE DOLE_9722_HEADER
//					SET PUSH_TO_CTCA_ON = SYSDATE
//					WHERE FILE_ID = '".$id."'";
//			$update = ociparse($rfconn, $sql);
//			ociexecute($update);

			GoodFileMove($path, $dName, $fp, $id, $QTY_alter_email, $rfconn);
		
		}

		// next file
	}





function GoodFileMove($path, &$filename, $fp, $id, $QTY_alter_email, $rfconn){
	$newfile = $filename.".".date('mdYhis');

	$sql = "UPDATE DOLE9722_RAW_FILE_HEADER
			SET PUSH_TO_CTCA_ON = SYSDATE,
				FINAL_FILENAME = '".$newfile."'
			WHERE FILE_ID = '".$id."'";
	$update = ociparse($rfconn, $sql);
	ociexecute($update);


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLE9722WANDINGRSLT'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
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
	$mailSubject = str_replace("_0_", $filename, $mailSubject);
	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $total_counts["good"], $body);

//	if($total_counts["bad"] <= 0){
//		$body = str_replace("_2_", "", $body);
//		$error_csv = "";
//	} else {
//		$message = $total_counts["bad"]." lines could not be processed.  See the attached Error File for details.";
//		$body = str_replace("_2_", $message, $body);

//		$error_csv = "Activity,Pool,Pallet,Boxes,Time,Order,Cm,Var,Error,OriginalLine\n";
	$sql = "SELECT DD.*, DECODE(DD.ERROR_DB_STRICT, NULL, DD.ERROR_VALID_DAT, DD.ERROR_DB_STRICT) THE_ERROR
			FROM DOLE9722_RAW_FILE_DETAIL DD
			WHERE FILE_ID = '".$id."'
				AND (ERROR_DB_STRICT IS NOT NULL OR ERROR_VALID_DAT IS NOT NULL)
			ORDER BY ROW_NUM";
	$errors = ociparse($rfconn, $sql);
	ociexecute($errors);
	if(!ocifetch($errors)){
		$body = str_replace("_2_", "", $body);
		$error_csv = "";
	} else {
		$error_csv = "Activity,Pool,Pallet,Boxes,Time,Order,Cm,Var,Error,OriginalLine\n";
		$badlines = 0;
		do {
			$badlines++;
			$error_csv .= ociresult($errors, "ACTIVITY_TYPE").",".
							ociresult($errors, "GROUP2").",".
							ociresult($errors, "PALLET_NBR").",".
							ociresult($errors, "BOXES2").",".
							ociresult($errors, "TRANSTIME").",".
							ociresult($errors, "ORDER_NBR").",".
							ociresult($errors, "COMMODITY").",".
							ociresult($errors, "VARIETY").",".
							ociresult($errors, "THE_ERROR").",".
							ociresult($errors, "FULL_LINE")."\n";		
		} while(ocifetch($errors));
		$body = str_replace("_2_", $badlines." lines could not be processed.  See the attached Error File for details.", $body);
	}
	

	if($QTY_alter_email != ""){
		$body .= "\r\n\r\nOther Notifications:\r\n".$QTY_alter_email;
	}

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$attach = chunk_split(base64_encode(file_get_contents($path.$filename)));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/csv; name=\"".$filename."\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach;
	$Content.="\r\n";

	if($error_csv != ""){
		$attach = chunk_split(base64_encode($error_csv));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/csv; name=\"Errors.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

	fclose($fp);
	copy($path."/".$filename , $path."processed/".$newfile);
	unlink($path."/".$filename);

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
					'INSTANT',
					SYSDATE,
					'EMAIL',
					'DOLE9722WANDINGRSLT',
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




function BadFileMove($path, &$filename, $fp, $id, $reason, $rfconn){

	$newfile = $filename.".".date('mdYhis');

	$sql = "UPDATE DOLE9722_RAW_FILE_HEADER
			SET PUSH_TO_CTCA_ON = SYSDATE,
				FINAL_FILENAME = '".$newfile."'
			WHERE FILE_ID = '".$id."'";
	$update = ociparse($rfconn, $sql);
	ociexecute($update);


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOLE9722WANDINGBAD'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
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
	$mailSubject = str_replace("_0_", $filename, $mailSubject);
	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $reason, $body);

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$attach = chunk_split(base64_encode(file_get_contents($path.$filename)));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/csv; name=\"".$filename."\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach;
	$Content.="\r\n";

	fclose($fp);
	copy($path.$filename , $path."failed/".$newfile);
	unlink($path.$filename);

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
					'INSTANT',
					SYSDATE,
					'EMAIL',
					'DOLE9722WANDINGBAD',
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



function ValidateDBStrict($useable_data, $rownum, $rfconn){
	$return = "";

	if(strlen($useable_data["text43"]) != 4){
		$return .= "Line: ".$rownum." - Textbox43 must be 4 characters.  ";
	}
	if(strlen($useable_data["text54"]) != 4){
		$return .= "Line: ".$rownum." - Textbox54 must be 4 characters.  ";
	}
	if(strlen($useable_data["text30"]) != 13){
		$return .= "Line: ".$rownum." - Textbox30 must be 13 characters.  ";
	}
	if(strlen($useable_data["text40"]) != 4){
		$return .= "Line: ".$rownum." - Textbox40 must be 4 characters.  ";
	}
	if(strlen($useable_data["text65"]) != 5){
		$return .= "Line: ".$rownum." - Textbox65 must be 5 characters.  ";
	}
	if(strlen($useable_data["text72"]) != 3){
		$return .= "Line: ".$rownum." - Textbox72 must be 3 characters.  ";
	}
	if(strlen($useable_data["text77"]) != 7){
		$return .= "Line: ".$rownum." - Textbox77 must be 7 characters.  ";
	}
	if(strlen($useable_data["text47"]) > 13){
		$return .= "Line: ".$rownum." - Textbox47 must be at most 13 characters.  ";
	}
	if(strlen($useable_data["group1"]) > 20){
		$return .= "Line: ".$rownum." - Group1 cannot be longer than 20 characters.  ";
	}
	if(strlen($useable_data["group2"]) > 20){
		$return .= "Line: ".$rownum." - Group2 cannot be longer than 20 characters.  ";
	}
	if(strlen($useable_data["pallet_id"]) > 32){
		$return .= "Line: ".$rownum." - PALLET_NBR cannot be longer than 32 characters.  ";
	}

	if(strlen($useable_data["boxes2"]) > 7){
		$return .= "Line: ".$rownum." - Boxes2 cannot be longer than 7 digits.  ";
	}
	if(!is_numeric($useable_data["boxes2"])){
		$return .= "Line: ".$rownum." - Boxes2 must be a number.  ";
	}

	if(strlen($useable_data["transtime"]) > 20){
		$return .= "Line: ".$rownum." - TRANSTIME cannot be longer than 20 characters.  ";
	}
	if(strlen($useable_data["order_num"]) > 6){
		$return .= "Line: ".$rownum." - ORDER_NBR cannot be longer than 6 characters.  ";
	}
	if(strlen($useable_data["comm"]) > 20){
		$return .= "Line: ".$rownum." - COMMODITY cannot be longer than 20 characters.  ";
	}
	if(strlen($useable_data["var"]) > 20){
		$return .= "Line: ".$rownum." - VARIETY cannot be longer than 20 characters.  ";
	}
	if(strlen($useable_data["act_type"]) > 20){
		$return .= "Line: ".$rownum." - ACTIVITY_TYPE cannot be longer than 20 characters.  ";
	}
	if(strlen($useable_data["text61"]) > 15){
		$return .= "Line: ".$rownum." - Textbox61 must be at most 15 characters.  ";
	}
	if(strlen($useable_data["text62"]) > 5){
		$return .= "Line: ".$rownum." - Textbox62 must be at most 5 characters.  ";
	}
	if(strlen($useable_data["text63"]) > 5){
		$return .= "Line: ".$rownum." - Textbox63 must be at most 5 characters.  ";
	}
	if(strlen($useable_data["text56"]) > 15){
		$return .= "Line: ".$rownum." - Textbox56 must be at most 15 characters.  ";
	}
	if(strlen($useable_data["text50"]) > 15){
		$return .= "Line: ".$rownum." - Textbox50 must be at most 15 characters.  ";
	}
	if(strlen($useable_data["text51"]) > 10){
		$return .= "Line: ".$rownum." - Textbox51 must be 10 characters.  ";
	}
	if(strlen($useable_data["text52"]) > 5){
		$return .= "Line: ".$rownum." - Textbox52 must be at most 5 characters.  ";
	}
	if(strlen($useable_data["text38"]) > 11){
		$return .= "Line: ".$rownum." - Textbox38 must be at most 11 characters.  ";
	}
	if(strlen($useable_data["text39"]) > 5){
		$return .= "Line: ".$rownum." - Textbox39 must be at most 5 characters.  ";
	}
	if(strlen($useable_data["text41"]) > 5){
		$return .= "Line: ".$rownum." - Textbox41 must be at most 5 characters.  ";
	}

	return $return;
}

function Validate_record($detail_row, $rfconn){
	$return = "";

	if(!ereg("^([0-1]{0,1}[0-9]{1}):([0-9]{2})$", $detail_row["time"])) {
		$return .= "Time is required and must be in HH:MM format.  ";
	}

	// does this commodity code jive with our conversion table?
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM DOLE_COMM_CONV
			WHERE DOLE_COMM = '".$detail_row["comm"]."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		$return .= "The Commodity entered (".$detail_row["comm"].")is not recognized by our system.  ";
	}

	if($detail_row["act"] != "2" && $detail_row["act"] != "3" && $detail_row["act"] != "4"){
		$return .= "The first character of the Activity Type is expected to be either a 2 3 or 4.  ";
	}

	if($detail_row["act"] == "2"){
		// truck-in specific checks
		$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') THE_REC
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
					AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '9722'
					AND CA.ACTIVITY_NUM = '1'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT_IMPORTED") >= 1){
			$return .= "The pallet has already been imported for the Pool #/Pallet ID entered at ".ociresult($stid, "THE_REC")."  ";
		}
	}

	if($detail_row["act"] == "3"){
		// ship out specific checks
		$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') THE_REC
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
					AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '9722'
					AND CA.ACTIVITY_NUM = '1'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT_IMPORTED") < 1){
			$return .= "You cannot ship out a pallet that has not been received.  Solution:  Manually upload the pallet ID file and then ship it out.  ";
		} else {
			$sql = "SELECT COUNT(*) THE_IH
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
						AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '9722'
						AND QTY_IN_HOUSE >0";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "THE_IH") < 1){
				$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') THE_OUT
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_ACTIVITY CA
						WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
							AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
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
				$return .= "The pallet is no longer in house to ship; it shipped out on ".ociresult($stid, "THE_OUT")."  ";
			} elseif(ociresult($stid, "THE_IH") > 1){
				$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') THE_DATE
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
						WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
							AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
							AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
							AND CT.PALLET_ID = CTAD.PALLET_ID
							AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
							AND CT.RECEIVER_ID = '9722'
							AND QTY_IN_HOUSE >0";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				$return .= "The pallet/pool number matches more than one pallet in the system.  The dates received were: ";
				while(ocifetch($stid)){
					$return .= ociresult($stid, "THE_DATE")." ";
				}
				$return .= "  ";
			}
		}
	}
	if($detail_row["act"] == "4"){
		// returns specific checks
		$sql = "SELECT COUNT(*) THE_COUNT_OUTBOUND
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
					AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
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
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT_OUTBOUND") > 1){
			$return .= "There is a problem with this return.  Please contact TS.  ";
		} elseif(ociresult($stid, "THE_COUNT_OUTBOUND") < 1){
			$sql = "SELECT QTY_IN_HOUSE
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = '".$detail_row["BC"]."'
						AND CTAD.DOLE_POOL = '".$detail_row["pool"]."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '9722'
						AND QTY_IN_HOUSE >0";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$return .= "The pallet is still in-house with ".ociresult($stid, "QTY_IN_HOUSE")." boxes; therefore; it cannot be returned.  ";
		} 
	}

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


