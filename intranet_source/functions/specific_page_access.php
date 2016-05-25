<?

function specific_page_access($page_name, $user){
// checks 1 table to see if this user is authorized for this intranet page.
// user still needs to pass the "valid group" check in pow_header.php

	$bni_conn = ocilogon("SAG_OWNER", "SAG", "BNI");

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM PROGRAM_AUTHORIZATION
			WHERE USER_NAME = '".$user."'
				AND PROGRAM_NAME = '".$page_name."'";
	
	$stid = ociparse($bni_conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		return true;
	} else {
		return false;
	}
}