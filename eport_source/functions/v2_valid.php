<?
// this function really only got used by the Dole-H pages.
function PageUserCheck($user, $DB_field, $conn){
	$sql = "SELECT ".$DB_field." FROM EPORT_LOGIN_V2
			WHERE USERNAME = '".$user."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		return false;
	}
	if(ociresult($stid, $DB_field) != ""){
		return ociresult($stid, $DB_field);
	} else {
		return false;
	}

	return false;
}




function CustV2UserCheck($user, $cust_DB_field, $rfconn){
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM EPORT_LOGIN_CUSTPERMISSIONS
			WHERE USERNAME = '".$user."'
				AND CUSTOMER_ID = 0";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
//	echo $sql."<br>";
	if(ociresult($stid, "THE_COUNT") >= 1){
		return "";
	} else {
//		echo "test";
		return " AND ".$cust_DB_field." IN 
				(SELECT CUSTOMER_ID FROM EPORT_LOGIN_CUSTPERMISSIONS WHERE USERNAME = '".$user."') ";
	}
}


function ShipLineV2UserCheck($user, $ship_DB_field, $rfconn){
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM EPORT_LOGIN_SHIPLINEPERMS
			WHERE USERNAME = '".$user."'
				AND SHIP_LINE = '0'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
//	echo $sql."<br>";
	if(ociresult($stid, "THE_COUNT") >= 1){
		return "";
	} else {
//		echo "test";
		return " AND ".$ship_DB_field." IN 
				(SELECT SHIP_LINE FROM EPORT_LOGIN_SHIPLINEPERMS WHERE USERNAME = '".$user."') ";
	}
}


