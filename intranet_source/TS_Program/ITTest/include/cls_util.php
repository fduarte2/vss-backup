<?php
// Desc: utility/tool shared functions
class C_Utility{
	
	// Desc: Utility function to add slashes - add slashes only the magic_quotes_gpc is set to off
	// It is strongly recommended that to turn off the magic quotes in the configuration setting
	function add_slashes($str){
		if (get_magic_quotes_gpc() == 1) {
			return ($str);
		}else{ 
			return (addslashes($str));
		}
	}
}
?>