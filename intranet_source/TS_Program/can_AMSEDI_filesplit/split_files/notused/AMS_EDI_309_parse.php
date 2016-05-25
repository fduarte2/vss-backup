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

	$path = "/var/www/html/TS_Program/can_AMSEDI_filesplit/split_files/notused/";
//	$path = "/var/www/html/TS_Testing/can_AMSEDI_filesplit/split_files/";


	$unique_filename_counter = 0;


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read()){
	   	if ($dName == "."  || $dName == ".." || $dName == "AMS_EDI_309_parse.php" || $dName == "AMS_EDI_309_parse.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else {
			// for each file, we are going to...
			$fp = fopen($dName, "r");
			$lloyd = "";
			$voyage = "";
			$vesname = "";
			$BoL = "";
			$consignee = "";
			$broker = "";
			$container = "";
			$casecount = 0;
			$commodity = "";

			$sql = "SELECT KEY_ID FROM CANADIAN_AMSEDI_HEADER
					WHERE SPLIT_FILENAME = '".$dName."'";
			$Short_Term_Data = ociparse($rfconn, $sql);
			ociexecute($Short_Term_Data);
			if(!ocifetch($Short_Term_Data)){
				//BadFileMove($path, $dName, $fp, "", "File ".$dName." Not In Header table", $rfconn);
				echo "Bad File Move\n";
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
						if ($line[1] != "309"){
							//UnusedfileMove($path, $dName, $fp, $key_id, $rfconn);
							echo "Unused File Move\n";
							continue 3;
						}
					break;

					case "M10":  
						// vessel info line
						$lloyd = $line[4];
						$voyage = $line[6];
						$vesname = $line[5];
					break;

					case "LX":  
						$BoL = "";
					break;

					case "M11":  
						// BOL detail
						$BoL = $line[1];
					break;

					case "N1":  
						// BOL detail
						if ($line[1] == "CN") {
							$consignee = $line[2];
						}
						if ($line[1] == "N1") {
							$broker = $line[2];
						}
					break;

					case "VID":  
						// Container Number
						$container = $line[1].$line[2];
					break;

					case "N10":
						$casecount = $line[1];
						$commodity = $line[2];
						//ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $BoL, $qty, $posted_datetime, $action_code, $std_carrier, $comments, $container, $lloyd, $voyage, $vesname, $rfconn);
						echo $lloyd."--".$voyage."--".$vesname."--".$BoL."--".$consignee."--".$broker."--".$container."--".$casecount."--".$commodity;
					break;

					default:
					break;
				}
			}
		
			//FinishedFileMove($path, $dName, $fp, $key_id, $rfconn);
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

	//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
		$mailTO = "archive@port.state.de.us";

		mail($mailTO, "File ".$filename." wasn't a 309, passed over.", "", $mailheaders);
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

	//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
		$mailTO = "archive@port.state.de.us";

		mail($mailTO, "File ".$filename." complete.", "", $mailheaders);
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
	$mailTO = "archive@port.state.de.us";

	mail($mailTO, $Subject_reason, "", $mailheaders);

}

function ValidateAndWriteLineToDB($path, &$filename, $fp, $key_id, $BoL, $qty, $posted_datetime, $action_code, $std_carrier, $comments, $container, $lloyd, $voyage, $vesname, $rfconn){
	if($key_id == "" || $BoL == "" || $qty == "" || $action_code == "" || $std_carrier == "" || $container == "" || $lloyd == "" || $voyage == "" || $vesname == ""){
		BadfileMove($path, $filename, $fp, $key_id, "Missing a required DB value in file ".$filename, $rfconn);
		exit;
	}
	if(!ereg("^([0-9]{8}[ ]{1}[0-9]{6})$", $posted_datetime)){
		BadfileMove($path, $filename, $fp, $key_id, "datetime not in format 'YYYYMMDD HHIISS' in file ".$filename, $rfconn);
		echo $posted_datetime."\n";
		exit;
	}

	$sql = "INSERT INTO CANADIAN_AMSEDI_DETAIL
				(KEY_ID,
				LLOYD_NUM,
				VESNAME,
				VOYAGE_NUM,
				BOL_EQUIV,
				CONTAINER_NUM,
				QTY,
				COMMENTS,
				POSTTIME,
				EDI_CODE,
				CARRIER_CODE)
			VALUES
				('".$key_id."',
				'".$lloyd."',
				'".$vesname."',
				'".$voyage."',
				'".$BoL."',
				'".$container."',
				'".$qty."',
				'".$comments."',
				TO_DATE('".$posted_datetime."', 'YYYYMMDD HH24MISS'),
				'".$action_code."',
				'".$std_carrier."')";
//	echo $sql."\n";
	$insert = ociparse($rfconn, $sql);
	if(ociexecute($insert)){
		// life is good, do nothing
	} else {
		BadfileMove($path, $filename, $fp, $key_id, "DB insert failed for ".$filename, $rfconn);
		exit;
	}

}
