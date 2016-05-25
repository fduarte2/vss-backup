<?
/*
*		Adam Walter, MArch 2009
*
*	This script grabs textfiles from Dole repository and deposits
*	Them in a directory for later EDI parsing.
*
*****************************************************************/

	$pathin = "/web/web_pages/TS_Program/DoleEDI/preprocess/";
//	$pathin = "/web/web_pages/TS_Testing/DoleEDI/preprocess/";
	$pathout = "/web/web_pages/TS_Program/DoleEDI/";
//	$pathout = "/web/web_pages/TS_Testing/DoleEDI/";


$connection = ftp_connect("portofwilmington.com");

if($connection != FALSE){

	// ifpcorp
	$login_status = ftp_login($connection, "ifpuser", "ifp12345");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				echo $contents[$i]."\n";
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}


	// ipaper
	$login_status = ftp_login($connection, "ipaper", "ipaper1234");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}


	// pkgcorp
	$login_status = ftp_login($connection, "pkgcorp", "pkgc1234");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}

	
	// Grief
	$login_status = ftp_login($connection, "greif", "12Greif34");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}

	// Temple
	$login_status = ftp_login($connection, "temple", "23temple45");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}

	// Georgia Pacific
	$login_status = ftp_login($connection, "gpacific", "!Pacific12$");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}

	// Hood
	$login_status = ftp_login($connection, "hood", "$Hood987!$");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
//			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
//				if(ftp_get($connection, "/web/web_pages/TS_Testing/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
//				if(ftp_get($connection, "/web/web_pages/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
				if(ftp_get($connection, "/var/www/html/TS_Program/DoleEDI/preprocess/".$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
//			}
		}

	}


	ftp_close($connection);

}


// we want to parse each "preprocessed" file that may be present.
// this part will read each file, replace ">" and "^" signs with line breaks, and rewrite the file.
// it will operate on ALL edi files; the ones without ">" or "^" signs will essentially just get a name change.
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

