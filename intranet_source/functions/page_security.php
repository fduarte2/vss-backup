<?
function PageSecurityCheck($user, $pagename, $testflag){
	if($testflag == "test"){
		$sec_conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	} else {
		$sec_conn = ocilogon("SAG_OWNER", "SAG", "BNI");
	}
	if($sec_conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$sql = "SELECT PERMISSION_LIST
			FROM INTRANET_USERS_PAGEPERMISSIONS
			WHERE USERNAME = '".$user."'
				AND PAGE_NAME = '".$pagename."'";
	$security = ociparse($sec_conn, $sql);
	ociexecute($security);
	if(!ocifetch($security) || ociresult($security, "PERMISSION_LIST") == ""){
		$security_allowance = "N";
	} else {
		$security_allowance = ociresult($security, "PERMISSION_LIST");
	}

	if(strpos($security_allowance, "N") !== false){
		echo "<font color=\"#FF0000\" size=\"5\">User not authorized to view this page.</font>";
		exit;
	}

	return $security_allowance;
}