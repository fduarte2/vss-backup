<?
/*
*		Adam Walter, Sep/Oct 2008
*
*	This script grabs textfiles from an Abitibi site and deposits
*	Them in a directory for later EDI parsing.
*
*****************************************************************/

$connection = ftp_connect("elink.ediwise.com");

if($connection != FALSE){

	$login_status = ftp_login($connection, "dolewhse", "whsedole");

	if($login_status != FALSE){

		$contents = ftp_nlist($connection, "dolewhse");

//		var_dump($contents);

		for($i=0; $i < sizeof($contents); $i++){
			if($contents[$i] != "00_index.txt" && $contents[$i] != "00_index.htm"){
				if(ftp_get($connection, "/web/web_pages/TS_Program/abiEDI/".$contents[$i], "dolewhse/".$contents[$i], FTP_BINARY)){
					ftp_delete($connection, "dolewhse/".$contents[$i]);
//					mail("awalter@port.state.de.us", "Abitibi EDI received, ".$contents[$i], "Handle:  Adam", "From:  ABIEDI");
				}
			}
		}

		ftp_close($connection);
	}
}
