<?
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
//	$conn = ora_logon("TCONSTANT@RFTEST", "TCONSTANT");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}

   $user = $HTTP_COOKIE_VARS["eport_user"];
   $type = $HTTP_COOKIE_VARS["eport_user_type"];
   $sub_type = $HTTP_COOKIE_VARS["eport_user_subtype"];
   $DT_flag = $HTTP_COOKIE_VARS["eport_DT_flag"];


   if($user == "" || $DT_flag != "T"){
	   header("Location: http://www.eportwilmington.com/login/");
   }

?>