<?
/*
*		Adam Walter, MArch 2014
*
*	This script grabs textfiles from Dole-9722 repository and deposits
*	Them in a directory for later EDI parsing.
*
*****************************************************************/

	$path = "/web/web_pages/TS_Program/DoleEDI9722/";
//	$path = "/web/web_pages/TS_Testing/DoleEDI9722/";


$connection = ftp_connect("portofwilmington.com");

if($connection != FALSE){

	// ifpcorp
	$login_status = ftp_login($connection, "dole9722", "Dole9722456");

	if($login_status != FALSE){
//		ftp_pasv($connection, true );

		$contents = ftp_nlist($connection, ".");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
				if(ftp_get($connection, $path.$contents[$i], "./".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "./".$contents[$i]);
				}
//			}
		}

	}

	ftp_close($connection);
}
