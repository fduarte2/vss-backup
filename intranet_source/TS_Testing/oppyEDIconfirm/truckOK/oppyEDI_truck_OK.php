<?
/*
*		Adam Walter, May 2009
*
*	This script parses truck "OK" messages for email distibution.
*
*****************************************************************/

//	$path = "/web/web_pages/TS_Program/oppyEDIconfirm/truckOK";
	$path = "/web/web_pages/TS_Testing/oppyEDIconfirm/truckOK";

//	$mailTO = "awalter@port.state.de.us\r\n";
//	$mailTO = "ltreut@port.state.de.us,martym@port.state.de.us,bdempsey@port.state.de.us\r\n";
	$mailTO = "awalter@port.state.de.us,ltreut@port.state.de.us,draczkowski@port.state.de.us,bdempsey@port.state.de.us,schapman@port.state.de.us,mbillin@port.state.de.us,lsanchez@port.state.de.us,mslowe@port.state.de.us,scorbin@port.state.de.us\r\n";
	$mailHeaders = "From:  PoWMailServer@port.state.de.us\r\n";
//	$mailHeaders .= "CC:  lstewart@port.state.de.us\r\n";
	$mailHeaders .= "BCC:  awalter@port.state.de.us,archive@port.state.de.us,lstewart@port.state.de.us\r\n";

	// Connect to RF
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if(!$conn){
		$body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	// we want to parse each file that may be present.
	chdir($path);
	$dir = dir(".");
	$dir-> rewind();
	while ($dName = $dir->read())
	{
		$body = "";
		$mail_sub_order_list = "";

	   	if ($dName == "."  || $dName == ".." || $dName == "oppyEDI_truck_OK.php" || is_dir($dName)){
			// do nothing to directories.
		} else{
			$fp = fopen($dName, "r");

			$line_1 = fgets($fp);
			while($line = fgets($fp)){
				$load = trim(substr($line, 1, 10));
				$order = trim(substr($line, 11, 10));
				$response = trim(substr($line, 201, 10));

				$mail_sub_order_list .= $order." ";

				$body .= "LOAD#:  ".$load."  ORDER#:  ".$order."  STATUS:  ".$response."\r\n";
			}

			if($load == ""){
				$load = "Not Specified";
			}

			$mailSubject = "Oppenheimer Truck Notification Results, Load ".$load." (".trim($mail_sub_order_list).")";
			fclose($fp);
			system("mv -f ".$dName." ./ok/".$dName);
			mail($mailTO, $mailSubject, $body, $mailHeaders);
		}
	}

?>