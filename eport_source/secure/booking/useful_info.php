<?
   $user = $HTTP_COOKIE_VARS["eport_user"];
   $type = $HTTP_COOKIE_VARS["eport_user_type"];
   $sub_type = $HTTP_COOKIE_VARS["eport_user_subtype"];
   $book_flag = $HTTP_COOKIE_VARS["eport_book_flag"];


   if($user == "" || $book_flag != "T"){
	   header("Location: http://www.eportwilmington.com/login/");
   }

	
	
	$conn = ora_logon("SAG_OWNER@RF", "OWNER"); $testflag = "LIVE";
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238"); $testflag = "TEST";
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}


?>