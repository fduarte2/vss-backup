<?
//	$pathin = "/web/web_pages/TS_Testing/BarnettEDI/preprocess/";
//	$pathout = "/web/web_pages/TS_Testing/BarnettEDI/";
	$pathin = "/web/web_pages/TS_Program/BarnettEDIv2/preprocess/";
	$pathout = "/web/web_pages/TS_Program/BarnettEDIv2/";

$connection = ftp_connect("portofwilmington.com");

if($connection != FALSE){

	// ifpcorp
	$login_status = ftp_login($connection, "barnett", "12barnett34");

	if($login_status != FALSE){

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
				if(ftp_get($connection, $pathin.$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}

	// westrock
	$login_status = ftp_login($connection, "westrock", "44Westrock12!");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
				if(ftp_get($connection, $pathin.$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}


}


chdir($pathin);
$dir = dir(".");
$dir-> rewind();
while ($dName = $dir->read())
{
	if ($dName == "."  || $dName == ".."){
		// do nothing to directories 
	} else{
		$fp = fopen($dName, "r");
		$fp_out = fopen($pathout.$dName, "w");

		while($temp = fgets($fp)){
			$new_lines = array("^", ">", "~");
			fwrite($fp_out, str_replace($new_lines, "\r\n", $temp));
		}
		fclose($fp);
		fclose($fp_out);

		unlink($dName);
	}
}

?>