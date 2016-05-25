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

	$path = "/var/www/html/TS_Program/can_AMSEDI_filesplit/split_files/EDI309/";
//	$path = "/var/www/html/TS_Testing/can_AMSEDI_filesplit/split_files/EDI309/";


	$unique_filename_counter = 0;


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read()){
	   	if ($dName == "."  || $dName == ".." || $dName == "AMS_EDI_309_parse.php" || $dName == "AMS_EDI309_parse.sh" || is_dir($dName)){
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
			$amend_code = "";
			$amend_type = "";
			$data_date = "";

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
						if ($line[1] != "309"){
							BadFileMove($path, $dName, $fp, $key_id, "File ".$dName." was in the 309 folder, but was not a 309", $rfconn);
							continue 3;
						}
					break;

					case "M10":  
						// vessel info line
						$shipper = $line[1];
						$lloyd = $line[4];
						$voyage = $line[6];
						while(substr($voyage, 0, 1) === "0"){
							$voyage = substr($voyage, 1);
						}
						$vesname = $line[5];
					break;

					case "LX":  
						$BoL = "";
						$amend_code = "";
						$amend_type = "";
					break;

					case "P4":  
						$data_date = $line[2]." ".$line[1];
					break;

					case "M11":  
						$BoL = $line[1];																
						$casecount = $line[3];						
						$origin = str_replace("'", "`", $line[10]);						
						$origin = str_replace("\"", "", $origin);
						$weight = $line[5];
						$weight_unt = $line[6];
					break;

					case "M13":
//						$BoL = $line[4];
						$amend_code = $line[3];
						$amend_type = $line[6];
						if($amend_code == "D"){
							// a "D" file ENDS ON THIS LINE.  process this line immediately.
							ValidateAndWriteDeleteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $line[4], $amend_code, $amend_type, $data_date, $rfconn);
						}
					break;

					case "N1":  
						// BOL detail
						if ($line[1] == "CN") {
							$consignee = str_replace("'", "`", $line[2]);
							$consignee = str_replace("\"", "", $consignee);
						}
						if ($line[1] == "N1") {
							$broker = str_replace("'", "`", $line[2]);
							$broker = str_replace("\"", "", $broker);
						}
					break;

					case "VID":  
						// Container Number
						$container = $line[2].$line[3];													
						if($casecount > 0 && $casecount != ""){
							$key_for_N10 = "";
							ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $origin, $key_for_N10, $amend_code, $amend_type, $data_date, $weight, $weight_unt, $rfconn);
						}
					break;

					case "N10":
//						$casecount = $line[1];															
						$N10 = str_replace("'", "`", $line[2]);
						$N10 = str_replace("\"", "", $N10);
						UpdateWithN10($path, $dName, $fp, $key_for_N10, $key_id, $N10, $rfconn);
//						if($casecount > 0 && $casecount != ""){
//							ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $N10, $rfconn);
//							ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $origin, $N10, $amend_code, $amend_type, $data_date, $rfconn);
//							ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $origin, $N10, $amend_code, $amend_type, $data_date, $weight, $weight_unt, $rfconn);
//						}
//						echo $lloyd."--".$voyage."--".$vesname."--".$BoL."--".$consignee."--".$broker."--".$container."--".$casecount."--".$commodity;
					break;

					default:
					break;
				}
			}
		
			FinishedFileMove($path, $dName, $fp, $key_id, $rfconn);
		}
	}



/*
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
*/
function FinishedFileMove($path, &$filename, $fp, $key_id, $rfconn){
	// after file is inserted, see if the LLoyd's # is batch-ignored
	$sql = "SELECT DISTINCT LLOYD_NUM, VOYAGE_NUM, VESNAME
			FROM CLR_AMSEDI_DETAIL_309
			WHERE KEY_ID = '".$key_id."'";
	$combos = ociparse($rfconn, $sql);
	ociexecute($combos);
	while(ocifetch($combos)){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_LLOYD_ARRIVAL_MAP
				WHERE LLOYD_NUM = '".ociresult($combos, "LLOYD_NUM")."'
					AND VOYAGE_NUM = '".ociresult($combos, "VOYAGE_NUM")."'
					AND SHIP_NAME = '".ociresult($combos, "VESNAME")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") < 1){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CLR_IGNORE_LLOYD
					WHERE LLOYD_NUM = '".ociresult($combos, "LLOYD_NUM")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") >= 1){
				// this is a batch-ignored item.  add it to conversion table.
				$sql = "INSERT INTO CLR_LLOYD_ARRIVAL_MAP
							(LLOYD_NUM,
							VOYAGE_NUM,
							SHIP_NAME,
							CLR_IGNORE,
							SET_ON,
							SET_BY)
						VALUES
							('".ociresult($combos, "LLOYD_NUM")."',
							'".ociresult($combos, "VOYAGE_NUM")."',
							'".ociresult($combos, "VESNAME")."',
							'Y',
							SYSDATE,
							'Lloyd Auto-Ignore')";
				$insert = ociparse($rfconn, $sql);
				if(ociexecute($insert)){
					// insert worked.
				} else {
					BadfileMove($path, $dName, $fp, $key_id, "Pre-assignment of ignored Lloyd failed on ".$filename, $rfconn);
					exit;
				}
			}
		}
	}


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

//		$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
		$mailTO = "archive@port.state.de.us";
//		$mailTO = "archive@port.state.de.us,awalter@port.state.de.us";

//		mail($mailTO, "AMS309 File ".$filename." complete.", "", $mailheaders);
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

//	echo "filename: ".$filename." --\n";
//	echo "reason: ".$Subject_reason." --\n";
	// proceed with file
	fclose($fp);
	copy($path."/".$filename , $path."failed/".$filename);
	unlink($path."/".$filename);
	$mailheaders = "";

//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
	$mailTO = "archive@port.state.de.us";
	$mailTO = "archive@port.state.de.us,awalter@port.state.de.us";

	mail($mailTO, $Subject_reason, "", $mailheaders);

}
 
function UpdateWithN10($path, &$dName, $fp, $key_for_N10, $key_id, $N10, $rfconn){
	if($key_for_N10 == "" || $N10 == "" || $key_id == ""){
		BadfileMove($path, $filename, $fp, $key_id, "AMS309 N10 Line not valid in file ".$filename, $rfconn);
		exit;
	}
	if($shipper == "TGBS"){
		$temp = explode(",", $N10);
		$commodity = $temp[0];
		$temp2 = explode("PALLETS", $temp[1]);
		$plt_count = trim(str_replace("ON", "", $temp2[0]));
		if(!is_numeric($plt_count)){
			$plt_count = "TBD";
		}
//		$extra_comments = trim($temp2[1]);
	} elseif($shipper == "CSVV"){
		$commodity = GetCSVVComm($N10);
		$plt_count = "TBD";
//		$extra_comments = GetConditionCSVV($N10);
	} elseif($shipper == "NKLC"){
		$temp = explode(",", $N10);
		$commodity = $temp[0];
		$plt_count = "TBD";
//		$extra_comments = GetConditionNKLC($N10);
	} else {
		$plt_count = "TBD";
		$commodity = "";
	}

	$sql = "UPDATE CLR_AMSEDI_DETAIL_309
			SET FULL_N10_LINE = SUBSTR(FULL_N10_LINE || '<br>' || '".$N10."', 0, 500),
				COMMODITY = DECODE(COMMODITY, NULL, '".$commodity."', '".$commodity."', '".$commodity."', 'MIXED'),
				PLTCOUNT = DECODE(PLTCOUNT, 'TBD', '".$plt_count."', DECODE('".$plt_count."', 'TBD', 'TBD', PLTCOUNT + TO_NUMBER('".$plt_count."')))
			WHERE KEY_ID = '".$key_id."'
				AND ENTRY_NUM = '".$key_for_N10."'";
//	echo $sql."\n";
	$update = ociparse($rfconn, $sql);
	if(ociexecute($update)){
		// life is good, do nothing
	} else {
		BadfileMove($path, $dName, $fp, $key_id, "AMS309 DB insert failed for N10-Update of ".$dName, $rfconn);
		exit;
	}

}

function ValidateAndWriteDeleteLineToDB($path, &$dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $amend_code, $amend_type, $data_date, $rfconn){
	if($key_id == "" || $BoL == ""){
		BadfileMove($path, $dName, $fp, $key_id, "AMS309 -D- Missing a required DB value in file ".$dName, $rfconn);
		exit;
	}

	$sql = "SELECT NVL(MAX(ENTRY_NUM), 0) + 1 THE_NUM
			FROM CLR_AMSEDI_DETAIL_309
			WHERE KEY_ID = '".$key_id."'";
	$maxnum = ociparse($rfconn, $sql);
	ociexecute($maxnum);
	ocifetch($maxnum);
	$key_for_D = ociresult($maxnum, "THE_NUM");

	$sql = "INSERT INTO CLR_AMSEDI_DETAIL_309
				(KEY_ID,
				ENTRY_NUM,
				LLOYD_NUM,
				VOYAGE_NUM,
				VESNAME,
				SHIPLINE,
				BOL_EQUIV,
				AMEND_TYPE,
				AMEND_CODE,
				DATA_DATE,
				IGNORE_FOR_CLR)
			VALUES
				('".$key_id."',
				'".$key_for_D."',
				'".$lloyd."',
				'".$voyage."',
				'".$vesname."',
				'".$shipper."',
				'".$BoL."',
				'".$amend_type."',
				'".$amend_code."',
				TO_DATE('".$data_date."', 'YYYYMMDD HH24MI'),
				'Y')";
	$insert = ociparse($rfconn, $sql);
	if(ociexecute($insert)){
		// life is good, do nothing
	} else {
		BadfileMove($path, $dName, $fp, $key_id, "AMS309 -D- DB insert failed for ".$dName, $rfconn);
		exit;
	}
}


//function ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $N10, $rfconn){
//function ValidateAndWriteLineToDB($path, $dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $origin, $N10, $amend_code, $amend_type, $data_date, $rfconn){
function ValidateAndWriteLineToDB($path, &$dName, $fp, $key_id, $shipper, $lloyd, $voyage, $vesname, $BoL, $consignee, $broker, $container, $casecount, $origin, &$key_for_N10, $amend_code, $amend_type, $data_date, $weight, $weight_unt, $rfconn){
	if($key_id == "" || $BoL == "" || $casecount == ""){
		BadfileMove($path, $dName, $fp, $key_id, "AMS309 Missing a required DB value in file ".$dName, $rfconn);
		exit;
	}
/*
	if($shipper == "TGBS"){
		$temp = explode(",", $N10);
		$commodity = $temp[0];
		$temp2 = explode("PALLETS", $temp[1]);
		$plt_count = trim(str_replace("ON", "", $temp2[0]));
		if(!is_numeric($plt_count)){
			$plt_count = "TBD";
		}
		$extra_comments = trim($temp2[1]);
	} elseif($shipper == "CSVV"){
		$commodity = GetCSVVComm($N10);
		$plt_count = "TBD";
		$extra_comments = GetConditionCSVV($N10);
	} elseif($shipper == "NKLC"){
		$temp = explode(",", $N10);
		$commodity = $temp[0];
		$plt_count = "TBD";
		$extra_comments = GetConditionNKLC($N10);
	} else {*/
		$commodity = "";
		$plt_count = "TBD";
		$extra_comments = "";
//	}

	switch($weight_unt){
		case "E":
			$DB_wt_unit = "MT";
		break;
		case "K":
			$DB_wt_unit = "KG";
		break;
		case "L":
			$DB_wt_unit = "LB";
		break;
		case "M":
			$DB_wt_unit = "T";
		break;
		case "S":
			$DB_wt_unit = "ST";
		break;
		case "T":
			$DB_wt_unit = "LT";
		break;
		default:
			BadfileMove($path, $dName, $fp, $key_id, "Weight unit of ".$weight_unt." NOT a valid 309 entry in ".$filename, $rfconn);
		break;
	}

	$sql = "SELECT NVL(MAX(ENTRY_NUM), 0) + 1 THE_NUM
			FROM CLR_AMSEDI_DETAIL_309
			WHERE KEY_ID = '".$key_id."'";
	$maxnum = ociparse($rfconn, $sql);
	ociexecute($maxnum);
	ocifetch($maxnum);
	$key_for_N10 = ociresult($maxnum, "THE_NUM");

	$sql = "INSERT INTO CLR_AMSEDI_DETAIL_309
				(KEY_ID,
				ENTRY_NUM,
				LLOYD_NUM,
				VOYAGE_NUM,
				VESNAME,
				BOL_EQUIV,
				CONSIGNEE,
				BROKER,
				CONTAINER_NUM,
				QTY,
				SHIPLINE,
				COMMODITY,
				PLTCOUNT,
				DESCR,
				FULL_N10_LINE,
				AMEND_TYPE,
				AMEND_CODE,
				DATA_DATE,
				ORIGIN_M11,
				WEIGHT,
				WEIGHT_UNIT,
				IGNORE_FOR_CLR)
			VALUES
				('".$key_id."',
				'".$key_for_N10."',
				'".$lloyd."',
				'".$voyage."',
				'".$vesname."',
				'".$BoL."',
				'".$consignee."',
				'".$broker."',
				'".$container."',
				'".$casecount."',
				'".$shipper."',
				'".$commodity."',
				'".$plt_count."',
				'".$extra_comments."',
				'',
				'".$amend_type."',
				'".$amend_code."',
				TO_DATE('".$data_date."', 'YYYYMMDD HH24MI'),
				'".$origin."',
				'".$weight."',
				'".$DB_wt_unit."',
				'Y')";
// 	echo $sql."\n";
	$insert = ociparse($rfconn, $sql);
	if(ociexecute($insert)){
		// life is good, do nothing
	} else {
		BadfileMove($path, $dName, $fp, $key_id, "AMS309 DB insert failed for ".$filename, $rfconn);
		exit;
	}

}



function GetCSVVComm($N10){
	if(strpos(strtoupper($N10), "PEACHES") !== false){
		return "PEACHES";
	}
	if(strpos(strtoupper($N10), "GRAPES") !== false){
		return "GRAPES";
	}
	if(strpos(strtoupper($N10), "CHERRIES") !== false){
		return "CHERRIES";
	}
	if(strpos(strtoupper($N10), "PLUMS") !== false){
		return "PLUMS";
	}
	if(strpos(strtoupper($N10), "NECTARINES") !== false){
		return "NECTARINES";
	}
	if(strpos(strtoupper($N10), "AVOCADOS") !== false){
		return "AVOCADOS";
	}
	if(strpos(strtoupper($N10), "BLUEBERRIES") !== false){
		return "BLUEBERRIES";
	}
	if(strpos(strtoupper($N10), "APRICOTS") !== false){
		return "APRICOTS";
	}
	if(strpos(strtoupper($N10), "ARANDANOS") !== false){
		return "ARANDANOS";
	}
	if(strpos(strtoupper($N10), "FRUITS") !== false){
		return "FRUITS";
	}
	return "";
}


function GetConditionCSVV($N10){
	if(strpos(strtoupper($N10), "USDA") !== false){
		return "USDA_INSPECTION";
	} elseif(strpos(strtoupper($N10), "UNFUM") !== false){
		return "UNFUMIGATED";
	} elseif(strpos(strtoupper($N10), "PRECL") !== false){
		return "PRECLEARED";
	} elseif(strpos(strtoupper($N10), "FUM") !== false){
		return "FUMIGATED";
	} else {
		return "TBD";
	}
	return "";
}

function GetConditionNKLC($N10){
	if(strpos(strtoupper($N10), "USDA") !== false){
		return "USDA_INSPECTION";
	} elseif(strpos(strtoupper($N10), "UNFUM") !== false){
		return "UNFUMIGATED";
	} elseif(strpos(strtoupper($N10), "PRECL") !== false){
		return "PRECLEARED";
	} elseif(strpos(strtoupper($N10), "FUM") !== false){
		return "FUMIGATED";
	} else {
		return "TBD";
	}
	return "";
}
