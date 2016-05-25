<?
/*
*		Adam Walter, Dec 2013
*
*		Parses EDI's from AMS for Canadian Release
*		Stores values in a holding table for later use
*		(Assuming file itself isn't bad)
*
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$path = "/web/web_pages/TS_Program/can_AMSEDI_filesplit/";
//	$path = "/web/web_pages/TS_Testing/can_AMSEDI_filesplit/";

	include("cron_alert.php");
	SendCronBreakNotify("move_350_hist_to_CLR_main.php", "CBP EDI", $rfconn);

	$unique_filename_counter = 0;


	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read()){
	   	if ($dName == "."  || $dName == ".." || $dName == "AMS_EDI_filesplit.php" || $dName == "AMS_EDI_filesplit.sh" || is_dir($dName)){
			// do nothing to directories or the 2 scripts for EDI parsing.
		} else {
			// for each file, we are going to...
			$fp = fopen($dName, "r");
			$file_deposit_string = array();

			while($temp = fgets($fp)){
				// store the line so that, if the file is good, we can write it out
				array_push($file_deposit_string, $temp); 

				// for each line 
				// clear out the quotes
				$temp = str_replace("\"", "", $temp);
				$temp = str_replace("'", "`", $temp);

				// split the line on the standard * character for parsing
				$line = split("\*", trim($temp));

				switch ($line[0]) {

					// validate the line
					case "ISA":  // first line of EDI, must match with the next found IEA line
						$ISA = $line[13];
					break;
					case "IEA":  // If not a match to above, disuse file.
						if ($line[2] != $ISA){
							BadfileMove($path, $dName, $fp, "AMS EDI upload FAILED, IEA value did not match ISA value for file ".$dName, $rfconn);
							exit;
						} else {
							$unique_filename_counter++;
							GoodfileSplit($path, $dName, $fp, $unique_filename_counter, $file_deposit_string, $ISA, $GS, $ST, $vesname, $rfconn);
							$ISA = "";
							$GS = "";
							$ST = "";
							$file_deposit_string = array();
							$vesname = "";
						}
					break;

					case "GS":  // second line of EDI, must match with next found GE line
						$GS = $line[6];
					break;
					case "GE":  // if not a match to above, disuse file.
						if ($line[2] != $GS){
							BadfileMove($path, $dName, $fp, "AMS EDI upload FAILED, GE value did not match GS value for file ".$dName, $rfconn);
							exit;
						} else {
							// nothing to do
						}
					break;
				
					case "ST":  // 4rd line of each EDI "file", must match with next found SE line
						$ST = $line[2];
					break;
					case "SE":  // if not a match to above, disuse file.
						if ($line[2] != $ST){
							BadfileMove($path, $dName, $fp, "AMS EDI upload FAILED, SE value did not match ST value for file ".$dName, $rfconn);
							exit;
						} else {
							// nothing to do
						}
					break;

					case "M10":
						$vesname = substr(str_replace(" ", "", $line[5]), 0, 10);
					break;
				}

			
			} // reached end of file.
			// note that all files would have been parsed in the IEA check.  If there is an unfinished ISA, we need to notify.
			if($ISA != ""){
				BadfileMove($path, $dName, $fp, "AMS EDI upload FAILED, unended ISA value for file ".$dName, $rfconn);
				exit;
			}

			// if we get here, move the file to completed folder.
			GoodfileMove($path, $dName, $fp, $rfconn);
		}
	}

	UpdateCronBreakNotify("AMS_EDI_filesplit.php", "CBP EDI", $rfconn);

function BadfileMove($path, &$dName, $fp, $SubjectReason, $rfconn){
	fclose($fp);
	copy($path."/".$dName , $path."failed/".$dName."-".date('mdYHis'));
	unlink($path."/".$dName);

//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
	$mailTO = "archive@port.state.de.us,awalter@port.state.de.us";

	mail($mailTO, $SubjectReason, "", $mailheaders);

	UpdateCronBreakNotify("AMS_EDI_filesplit.php", "CBP EDI", $rfconn);
}



function GoodfileSplit($path, &$dName, $fp, $unique_filename_counter, $file_deposit_string, $ISA, $GS, $ST, $vesname, $rfconn){
//	$newfile = $dName."-".$GS."-".$ST;
	$newfile = $vesname."-".date('mdYHis')."-".$unique_filename_counter."-".$GS."-".$ST;
	$sql = "INSERT INTO CANADIAN_AMSEDI_HEADER
				(KEY_ID,
				ORIGINAL_FILENAME,
				SPLIT_FILENAME,
				ISA_ID,
				GS_ID,
				ST_ID,
				DATE_FILESPLIT)
			VALUES
				(CANADIAN_AMSEDI_MAINSEQ.NEXTVAL,
				'".$dName."',
				'".$newfile."',
				'".$ISA."',
				'".$GS."',
				'".$ST."',
				SYSDATE)";
//	echo $sql."\n";
	$insert = ociparse($rfconn, $sql);
	if(ociexecute($insert)){
		// proceed with file
		$fp = fopen($path."/split_files/".$newfile, "w");
		for($i = 0; $i < sizeof($file_deposit_string); $i++){
			fwrite($fp, $file_deposit_string[$i]);
		}
		fclose($fp);
	} else {
		BadfileMove($path, $dName, $fp, "AMS EDI upload FAILED, DB error on insert for file ".$dName, $rfconn);
		exit;
	}
}

function GoodfileMove($path, &$dName, $fp, $rfconn){
	fclose($fp);
	copy($path."/".$dName , $path."processed/".$dName);
	unlink($path."/".$dName);

//	$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us";
	$mailTO = "archive@port.state.de.us";

//	mail($mailTO, "AMSEDI file ".$dName." Split Successfully", "", $mailheaders);
}




						
						
						
						
						
						
