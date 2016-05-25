<?
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

// also, set security privledge
	$sql = "SELECT PERMISSION_LIST
			FROM EPORT_LOGIN_PAGEPERMISSIONS
			WHERE USERNAME = '".$user."'
				AND PAGE_NAME = '".$pagename."'";
	$security = ociparse($rfconn, $sql);
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

// the v2_valid file in the functions folder will hold all of the specific permission checks
include ("v2_valid.php");
?>