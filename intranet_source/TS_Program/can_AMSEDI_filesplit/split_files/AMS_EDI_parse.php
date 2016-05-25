<?
/*
*		Adam Walter, Dec 2013
*
*		Takes a pre-split AMS edi file, and populates the 
*		data to RF for use in canadian releases
*
*		All file-validity checks are done before the
*		File gets here, so we just need to verify
*		Data.
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$path = "/var/www/html/TS_Program/can_AMSEDI_filesplit/split_files/";
//	$path = "/var/www/html/TS_Testing/can_AMSEDI_filesplit/split_files/";

	include("cron_alert.php");
	SendCronBreakNotify("AMS_EDI_filesplit.php", "CBP EDI", $rfconn);

	$unique_filename_counter = 0;


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read()){
	   	if ($dName == "."  || $dName == ".." || $dName == "AMS_EDI_parse.php" || $dName == "AMS_EDI_parse.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else {
			// for each file, we are going to...
			$fp = fopen($dName, "r");
			$lloyd = "";
			$voyage = "";
			$vesname = "";

			$sql = "SELECT KEY_ID FROM CANADIAN_AMSEDI_HEADER
					WHERE SPLIT_FILENAME = '".$dName."'";
			$Short_Term_Data = ociparse($rfconn, $sql);
			ociexecute($Short_Term_Data);
			if(!ocifetch($Short_Term_Data)){
				BadFileMove($path, $dName, $fp, "", "File ".$dName." Not In Header table", $rfconn);
				continue;
			} else {
				$key_id = ociresult($Short_Term_Data, "KEY_ID");
			}

			while($temp = fgets($fp)){
				// if there's a blank line, it just won't match to any of the cases, so it won't harm us.

				// for each line
				// split the line on the standard * character for parsing
				$line = split("\*", trim($temp));

				switch ($line[0]) {
					case "ST":  
						if ($line[1] == "309"){
							Three09fileMove($path, $dName, $fp, $key_id, $rfconn);
							continue 3;
						} elseif($line[1] != "309" && $line[1] != "350"){
							UnusedfileMove($path, $dName, $fp, $key_id, $rfconn);
							continue 3;
						}
					break;

					case "M10":  
						// vessel info line
						if($line[4] != ""){
							$lloyd = $line[4];

							$sql = "SELECT COUNT(*) THE_COUNT
									FROM CLR_IGNORE_LLOYD
									WHERE LLOYD_NUM = '".$lloyd."'";
							$Short_Term_Data = ociparse($rfconn, $sql);
							ociexecute($Short_Term_Data);
							ocifetch($Short_Term_Data);
							if(ociresult($Short_Term_Data, "THE_COUNT") >= 1){
								UnusedfileMove($path, $dName, $fp, $key_id, $rfconn);
								continue 3;
							}
						} else {
							if(trim($line[5]) == "SOUTHERN BAY"){ 
								$lloyd = "9152181";
							} else {
								$lloyd = "UNKNOWN";
							}
						}
						$voyage = $line[6];
						while(substr($voyage, 0, 1) === "0"){
							$voyage = substr($voyage, 1);
						}
						$vesname = $line[5];
					break;

					case "P4":
						// we only want X4-loops that come after a P4
						$save_flag = true;
					break;

					case "X4":  
						// cargo detail
						$BoL = $line[1];
						$qty = $line[2];
						$posted_datetime = $line[5]." ".$line[6];
//						echo $posted_datetime."\n";
						$action_code = $line[7];
//						$std_carrier = $line[9];
						$std_carrier = "";

						// reset comments and container
						if($line[4] != ""){
							$comments = "(#".$line[4].")  ";
						} else {
							$comments = "";
						}

						$container = "";
					break;

					case "K1":
						// freeform comments
					// taget the space at the end since I may have to concatenate 4 of these lines.  We'll trim it later.
						$comments .= str_replace("'", "`", $line[1])." ";
					break;

					case "N7":
						$container = $line[1].$line[2];
						if($save_flag){
							$comments = substr(trim($comments), 0, 50);
							ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $BoL, $qty, $posted_datetime, $action_code, $std_carrier, $comments, $container, $lloyd, $voyage, $vesname, $rfconn);
						}
					break;

					case "BA1":
						// if we see this segment, we have passed beyond he X4s we want to use.
						// therefore, clear out the flag variable, so we know to ignore any other X4s
						$save_flag = false;
					break;

					default:
					break;
				}
			}
		
			FinishedFileMove($path, $dName, $fp, $key_id, $rfconn);
		}
	}
	UpdateCronBreakNotify("AMS_EDI_parse.php", "CBP EDI", $rfconn);




function Three09fileMove($path, &$filename, $fp, $key_id, $rfconn){
	$sql = "UPDATE CANADIAN_AMSEDI_HEADER
			SET DATE_FILEPARSE = SYSDATE
			WHERE KEY_ID = '".$key_id."'";
	$update = ociparse($rfconn, $sql);
	if(ociexecute($update)){
		// proceed with file
		fclose($fp);
		copy($path."/".$filename , $path."EDI309/".$filename);
		unlink($path."/".$filename);
		$mailheaders = "";

	//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
//		$mailTO = "archive@port.state.de.us";

//		mail($mailTO, "File ".$filename." wasn't a 350, passed over.", "", $mailheaders);
	} else {
		BadfileMove($path, $filename, $fp, $key_id, "Attempt to update DB with parse date failed for ".$filename, $rfconn);
		exit;
	}

}

function UnusedfileMove($path, &$filename, $fp, $key_id, $rfconn){
	$sql = "UPDATE CANADIAN_AMSEDI_HEADER
			SET DATE_FILEPARSE = SYSDATE
			WHERE KEY_ID = '".$key_id."'";
	$update = ociparse($rfconn, $sql);
	if(ociexecute($update)){
		// proceed with file
		fclose($fp);
		copy($path."/".$filename , $path."notused/".$filename);
		unlink($path."/".$filename);
		$mailheaders = "";

		$mailTO = "awalter@port.state.de.us,archive@port.state.de.us";
	//	$mailTO = "archive@port.state.de.us";

//		mail($mailTO, "File ".$filename." wasn't one we process, passed over.", "", $mailheaders);
	} else {
		BadfileMove($path, $filename, $fp, $key_id, "Attempt to update DB with parse date failed for ".$filename, $rfconn);
		exit;
	}

}

function FinishedFileMove($path, &$filename, $fp, $key_id, $rfconn){
	$sql = "UPDATE CANADIAN_AMSEDI_HEADER
			SET DATE_FILEPARSE = SYSDATE
			WHERE KEY_ID = '".$key_id."'";
	$update = ociparse($rfconn, $sql);
	if(ociexecute($update)){
		// proceed with file
		fclose($fp);
		copy($path."/".$filename , $path."processed/".$filename);
		unlink($path."/".$filename);
		$mailheaders = "";

		$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
		//$mailTO = "archive@port.state.de.us";

//		mail($mailTO, "File ".$filename." complete.", "", $mailheaders);
	} else {
		BadfileMove($path, $filename, $fp, $key_id, "Attempt to update DB with parse date failed for ".$filename, $rfconn);
		exit;
	}

}

function BadfileMove($path, &$filename, $fp, $key_id, $Subject_reason, $rfconn){
	$sql = "UPDATE CANADIAN_AMSEDI_HEADER
			SET DATE_FILEPARSE = SYSDATE
			WHERE KEY_ID = '".$key_id."'";
	$update = ociparse($rfconn, $sql);
	ociexecute($update);
	// this may or may not work, if the key even exists.  If not though, we don't want to stop the program.

	// proceed with file
	fclose($fp);
	copy($path."/".$filename , $path."failed/".$filename);
	unlink($path."/".$filename);
	$mailheaders = "";

//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
	$mailTO = "archive@port.state.de.us,awalter@port.state.de.us";

	mail($mailTO, $Subject_reason, "", $mailheaders);

	UpdateCronBreakNotify("AMS_EDI_parse.php", "CBP EDI", $rfconn);

}

function ValidateAndWriteLineToDB($path, &$filename, $fp, $key_id, $BoL, $qty, $posted_datetime, $action_code, $std_carrier, $comments, $container, $lloyd, $voyage, $vesname, $rfconn){
//	if($key_id == "" || $BoL == "" || $qty == "" || $action_code == "" || $std_carrier == "" || $container == "" || $lloyd == "" || $voyage == "" || $vesname == ""){
	if($key_id == "" || $BoL == "" || $qty == "" || $action_code == "" || $container == "" || $lloyd == "" || $voyage == "" || $vesname == ""){
		BadfileMove($path, $filename, $fp, $key_id, "Missing a required DB value in file ".$filename, $rfconn);
		exit;
	}
	if(!ereg("^([0-9]{8}[ ]{1}[0-9]{6})$", $posted_datetime)){
		BadfileMove($path, $filename, $fp, $key_id, "datetime not in format 'YYYYMMDD HHIISS' in file ".$filename, $rfconn);
		echo $posted_datetime."\n";
		exit;
	}

	$sql = "INSERT INTO CLR_AMS_350_EDI
				(KEY_ID,
				AMS350_UNIQ_ID,
				LLOYD_NUM,
				VESNAME,
				VOYAGE_NUM,
				BOL_EQUIV,
				CONTAINER_NUM,
				QTY,
				COMMENTS,
				EDI_DATE_TIME,
				EDI_CODE)
			VALUES
				('".$key_id."',
				CLR_AMS_305_SEQ.NEXTVAL,
				'".$lloyd."',
				'".$vesname."',
				'".$voyage."',
				'".$BoL."',
				'".$container."',
				'".$qty."',
				'".substr($comments, 0, 100)."',
				TO_DATE('".$posted_datetime."', 'YYYYMMDD HH24MISS'),
				'".$action_code."')";
	echo $sql."\n";
	$insert = ociparse($rfconn, $sql);
	if(ociexecute($insert)){
		// life is good, do nothing
	} else {
		BadfileMove($path, $filename, $fp, $key_id, "DB insert failed for ".$filename." on SQL ".$sql, $rfconn);
		exit;
	}

}
