<?
/*
*		Adam Walter, Sep 2013
*
*		Program parses AMS-releases for Canadian
*		Release... err, "Program"
*****************************************************************/

//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$path = "/web/web_pages/TS_Program/canadian_rel_edis/";
//	$path = "/var/www/html/TS_Testing/uploads";


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read()) {
		echo $dName."\r\n";
	   	if ($dName == "."  || $dName == ".." || $dName == "readme.txt" || $dName == "canadian_ams_edi_parse.php" || $dName == "canadian_ams_edi_parse.sh" || is_dir($dName)){
			// do nothing to directories or the script files
		} else {
			$linenum = 0;
			// here there be Dragons... and file pointers.
			$fp = fopen($dName, "r");
			$file_message = "";
//			$dName = $dName.".".date('mdYhi');

			while($temp = fgets($fp)){
				$linenum++;
				$line = explode(",", RemoveUnwantedChars($temp), 11);
				$line_message = "";

				$line_message = ValidateLine($line, $rfconn);

				if($line_message == ""){
//					echo "line: ".$temp."\r\n";
					InsertGoodLine($line, $dName, $temp, $linenum, $rfconn);
				} else {
					InsertBadLine($line, $dName, $temp, $linenum, $line_message, $rfconn);
				}
			}

			fileMove($path, $dName, $fp, "good");
		}
	}
	














function RemoveUnwantedChars($temp){
	$return = $temp;

	$return = str_replace("'", "`", $return);
	$return = str_replace("\"", "", $return);
	$return = str_replace("\\", "", $return);

	return $return;
}

function fileMove($path, &$filename, $fp, $type){
	global $conn2;

	// as of the original writing (9/23/2013), I have no qualifiers for a "failed" upload (a single bad line is just skipped over).  But should one arise, the code is here.


	$newfile = $filename;

	switch ($type) {
		case "bad":
			fclose($fp);
//			copy($path."/".$filename , $path."failed/".$filename.".".date('mdYhi'));
			copy($path."/".$filename , $path."failed/".$newfile);
			unlink($path."/".$filename);
		break;

		case "good":
			fclose($fp);
//			copy($path."/".$filename , $path."complete/".$filename.".".date('mdYhi'));
			copy($path."/".$filename , $path."success/".$newfile);
			unlink($path."/".$filename);
		break;

	}

	$filename = $newfile;
}

function  ValidateLine($line, $rfconn){
	$return = "";
	// precheck:  array size
	if(count($line) != 11){
		$return .= "The CSV must contain exactly 11 fields.\r\n";
	}

	// first, existance + lengths.
	if(strlen($line[0]) > 20){
		$return .= "Lloyds code cannot be longer than 20 characters.\r\n";
	}
	if(strlen($line[1]) > 20){
		$return .= "Vessel Name cannot be longer than 20 characters.\r\n";
	}
	if(strlen($line[2]) > 20){
		$return .= "BoL cannot be longer than 20 characters.\r\n";
	}
	if(strlen($line[3]) > 20){
		$return .= "Container number cannot be longer than 20 characters.\r\n";
	}
	if(strlen($line[4]) > 1){
		$return .= "Released Flag be longer than 1 character.\r\n";
	}
	if(strlen($line[5]) > 1){
		$return .= "Hold Flag be longer than 1 character.\r\n";
	}
	if(strlen($line[6]) > 7){
		$return .= "Container Count cannot be longer than 7 digits.\r\n";
	}
	if(strlen($line[7]) > 40){
		$return .= "Released Contaienr Count cannot be longer than 7 digits.\r\n";
	}
	if(strlen($line[8]) > 100){
		$return .= "Disposition Code cannot be longer than 100 characters.\r\n";
	}
	if(strlen($line[9]) != 13){
		$return .= "DateTime field must be exactly 13 characters.\r\n";
	}
	if(strlen($line[10]) > 2000){
		$return .= "Release Check cannot be longer than 5000 characters.\r\n";
	}

	// next, check the date
	$temp = explode(" ", $line[9]);
	if(strlen($temp[0]) != 6 || strlen($temp[1]) != 6){
		$return .= "Date field must be in `YYMMDD HHMISS` format.\r\n";
	}

	return $return;
}

function InsertGoodLine($line, $dName, $full_line, $linenum, $rfconn){
	$sql = "INSERT INTO CANADIAN_AMS_EDIS
				(ROW_NUM,
				LINE_NUM,
				FILENAME,
				PROCESSED_ON,
				LLOYD_NUM,
				VESSEL_NAME,
				BOL,
				CONTAINER_NUM,
				RELEASED,
				HOLD,
				CONTAINER_CNT,
				RELEASE_CNT,
				LAST_DISP_CODE,
				DATE_TIME,
				RELEASE_CHK_DESC,
				FINAL_PROCESS,
				FULL_LINE)
			VALUES
				(CANADIAN_AMS_REL_SEQ.NEXTVAL,
				'".$linenum."',
				'".$dName."',
				SYSDATE,
				'".$line[0]."',
				'".$line[1]."',
				'".$line[2]."',
				'".$line[3]."',
				'".$line[4]."',
				'".$line[5]."',
				'".$line[6]."',
				'".$line[7]."',
				'".$line[8]."',
				TO_DATE('".$line[9]."', 'YYMMDD HH24MISS'),
				'".$line[10]."',
				'N',
				'".$full_line."')";
//	echo $sql."\r\n";
	$update_data = ociparse($rfconn, $sql);
	ociexecute($update_data);
}

function InsertBadLine($line, $dName, $full_line, $linenum, $line_message, $rfconn){
	$sql = "INSERT INTO CANADIAN_AMS_EDIS
				(ROW_NUM,
				LINE_NUM,
				FILENAME,
				PROCESSED_ON,
				BAD_REASON,
				FULL_LINE)
			VALUES
				(CANADIAN_AMS_REL_SEQ.NEXTVAL,
				'".$linenum."',
				'".$dName."',
				SYSDATE,
				'".$line_message."',
				'".$full_line."')";
	$update_data = ociparse($rfconn, $sql);
	ociexecute($update_data);
}
