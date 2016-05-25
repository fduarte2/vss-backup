<?
//PutEnv("ORACLE_SID=RFTEST");
PutEnv("ORACLE_SID=RF");
PutEnv("ORACLE_HOME=/opt/oracle");
PutEnv("TNS_ADMIN=/opt/oracle/network/admin/PROD");



//$rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
$rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
if (!$rf_conn)
{
	printf("Error logging on to the RF Oracle Server: " . ora_errorcode($rf_conn));
	printf("Please report to TS!");
	exit;
}

function valid_customer_id ($string) {
	$gCustomer_Name = "";
	if (trim(strtoupper($string)) == "X") {return TRUE;};
	if (trim($string) == "") {return FALSE;};
	if (!is_numeric(trim($string))) {return FALSE;};
	if (strlen(trim($string)) > 4) {return FALSE;};

	$rf_conn1 = ora_logon("SAG_OWNER@RF", "OWNER");
	$short_term_data_cursor1 = ora_open($rf_conn1);

	$sql = "SELECT CUSTOMER_NAME CUST_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".trim($string)."'";
	$ora_success = ora_parse($short_term_data_cursor1, $sql);
	$ora_success = ora_exec($short_term_data_cursor1, $sql);
	if (ora_fetch_into($short_term_data_cursor1, $short_term_data_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		$gCustomer_Name = trim($short_term_data_row1['CUST_NAME']);
		echo " ".$gCustomer_Name."\n";
		return TRUE;}
	return FALSE;
}

function valid_arrival_num ($string) {
	$gVessel_Name = "";
	if (strtoupper(trim($string)) == "X") {return TRUE;};
	if (trim($string) == "") {return FALSE;};
	if (!is_numeric(trim($string))) {return FALSE;};
	if (strlen(trim($string)) > 12) {return FALSE;};

	$rf_conn1 = ora_logon("SAG_OWNER@RF", "OWNER");
	$short_term_data_cursor1 = ora_open($rf_conn1);

	$sql = "SELECT VESSEL_NAME VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".trim($string)."'";
	$ora_success = ora_parse($short_term_data_cursor1, $sql);
	$ora_success = ora_exec($short_term_data_cursor1, $sql);
	if (ora_fetch_into($short_term_data_cursor1, $short_term_data_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		$gVessel_Name = trim($short_term_data_row1['VESSEL_NAME']);
		echo " ".trim($string)."-".$gVessel_Name."\n";
		return TRUE;}
	return FALSE;
}

function get_customer_name($string) {
	$rf_conn1 = ora_logon("SAG_OWNER@RF", "OWNER");
	$short_term_data_cursor1 = ora_open($rf_conn1);
	$sql = "SELECT CUSTOMER_NAME CUST_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".trim($string)."'";
	$ora_success = ora_parse($short_term_data_cursor1, $sql);
	$ora_success = ora_exec($short_term_data_cursor1, $sql);
	if (ora_fetch_into($short_term_data_cursor1, $short_term_data_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		return trim($short_term_data_row1['CUST_NAME']);
		}
}

function get_vessel_name($string) {
	$rf_conn1 = ora_logon("SAG_OWNER@RF", "OWNER");
	$short_term_data_cursor1 = ora_open($rf_conn1);
	$sql = "SELECT VESSEL_NAME VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".trim($string)."'";
	$ora_success = ora_parse($short_term_data_cursor1, $sql);
	$ora_success = ora_exec($short_term_data_cursor1, $sql);
	if (ora_fetch_into($short_term_data_cursor1, $short_term_data_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		return trim($short_term_data_row1['VESSEL_NAME']);}
}

function valid_order_num ($customer_id, $vessel_num, $string) {
	if (strtoupper(trim($string)) == "X") {return TRUE;};
	if (trim($string) == "") {return FALSE;};
	if (strlen(trim($string)) > 12) {return FALSE;};

	$rf_conn1 = ora_logon("SAG_OWNER@RF", "OWNER");
	$short_term_data_cursor1 = ora_open($rf_conn1);

	$sql = "SELECT ORDER_NUM FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".strtoupper(trim($string))."' AND ARRIVAL_NUM = '".trim($vessel_num)."' AND CUSTOMER_ID = '".trim($customer_id)."'";
	//echo $sql;
	$ora_success = ora_parse($short_term_data_cursor1, $sql);
	$ora_success = ora_exec($short_term_data_cursor1, $sql);
	if (ora_fetch_into($short_term_data_cursor1, $short_term_data_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		return TRUE;}
	return FALSE;
}

?>