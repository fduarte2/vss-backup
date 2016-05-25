<?
$conn_lcs = ora_logon("LABOR@LCS", "LABOR");
if ($conn_lcs < 1) {
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
}
ora_commitoff($conn_lcs);
$cursor = ora_open($conn_lcs);

$sDate = "07/01/2004";
//$eDate = "06/30/2004";

$sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 30 ,date("Y")));

$eDate = date('m/d/Y');

$table = "lcs_hourly_detail";

$sql = "delete from $table 
	where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <=to_date('$eDate','mm/dd/yyyy')";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
	exit;
}


$sql = "insert into $table (hire_date, work_date, employee, start_time, end_time, earning_type_id, commodity, customer_id, 
	servicecode, vessel_id, equipment_id, location_id, special_code, duration, supervisor, sf_row_number, 
	employee_type_id,pay_lunch, pay_dinner)
	select h.hire_date, h.hire_date, h.employee_id, h.start_time, h.end_time, h.earning_type_id, h.commodity_code, 
	h.customer_id, h.service_code, h.vessel_id, h.equipment_id, h.location_id, h.special_code, 
	h.duration, h.user_id, h.sf_row_number, e.employee_type_id, s.pay_lunch, s.pay_dinner 
	from hourly_detail h, employee e, sf_hourly_detail s
	where h.hire_date >=to_date('$sDate','mm/dd/yyyy') and h.hire_date <=to_date('$eDate','mm/dd/yyyy') and 
	h.start_time < h.end_time and h.employee_id = e.employee_id(+) and h.sf_row_number = s.row_number(+) and 
	h.hire_date =s.hire_date(+) and h.user_id = s.user_id(+) and h.employee_id = s.employee_id(+)";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
	ora_commit($conn_lcs);
}else{
	ora_rollback($conn_lcs);
}

$sql = "insert into $table (hire_date, work_date, employee, start_time, end_time, earning_type_id, commodity, customer_id, 
	servicecode, vessel_id, equipment_id, location_id, special_code, duration, supervisor, sf_row_number, 
	employee_type_id, pay_lunch, pay_dinner)
	select h.hire_date, h.hire_date, h.employee_id, h.start_time, h.hire_date + 1, h.earning_type_id, h.commodity_code, 
	h.customer_id, h.service_code, h.vessel_id, h.equipment_id, h.location_id, h.special_code, 
	(h.hire_date + 1 - h.start_time)*24, h.user_id, h.sf_row_number, e.employee_type_id, s.pay_lunch, s.pay_dinner 
	from hourly_detail h, employee e, sf_hourly_detail s 
	where h.hire_date >=to_date('$sDate','mm/dd/yyyy') and h.hire_date <=to_date('$eDate','mm/dd/yyyy') and
	h.start_time > h.end_time and h.employee_id = e.employee_id(+) and h.sf_row_number = s.row_number(+) and 
	h.hire_date =s.hire_date(+) and h.user_id = s.user_id(+) and h.employee_id = s.employee_id(+)";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
}

$sql = "insert into $table (hire_date, work_date, employee, start_time, end_time, earning_type_id, commodity, customer_id, 
	servicecode, vessel_id, equipment_id, location_id, special_code, duration, supervisor, sf_row_number, 
	employee_type_id, pay_lunch, pay_dinner)
	select h.hire_date, h.hire_date+1, h.employee_id, h.hire_date+1 , h.end_time+1, h.earning_type_id, h.commodity_code, 
	h.customer_id, h.service_code, h.vessel_id, h.equipment_id, h.location_id, h.special_code, 
	(h.end_time - h.hire_date)*24, h.user_id, h.sf_row_number, e.employee_type_id, s.pay_lunch, s.pay_dinner 
	from hourly_detail h, employee e, sf_hourly_detail s
	where h.hire_date >=to_date('$sDate','mm/dd/yyyy') and h.hire_date <=to_date('$eDate','mm/dd/yyyy') and
 	h.start_time > h.end_time and h.employee_id = e.employee_id(+) and h.sf_row_number = s.row_number(+) and 
	h.hire_date =s.hire_date(+) and h.user_id = s.user_id(+) and h.employee_id = s.employee_id(+)";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
}

$sql = "UPDATE $table SET SUPERVISOR = SUPERVISOR ||'-'||(SELECT USER_NAME FROM LCS_USER  
	WHERE USER_ID = SUPERVISOR)
	where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <=to_date('$eDate','mm/dd/yyyy')";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
}

$sql = "UPDATE $table SET EMPLOYEE = EMPLOYEE ||'-'||(SELECT EMPLOYEE_NAME FROM EMPLOYEE  
	WHERE EMPLOYEE_ID = EMPLOYEE)
	where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <=to_date('$eDate','mm/dd/yyyy')";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
}

$sql = "UPDATE $table SET COMMODITY = (SELECT COMMODITY_NAME FROM COMMODITY 
	WHERE TO_CHAR(COMMODITY_CODE) = COMMODITY)
	where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <=to_date('$eDate','mm/dd/yyyy')";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
}

$sql = "UPDATE $table SET SERVICE = (SELECT SERVICE_NAME 
	FROM (SELECT * FROM SERVICE WHERE SERVICE_CODE NOT LIKE '6___' OR STATUS = 'N')A 
	WHERE SERVICE_CODE = SERVICECODE)
	where hire_date >=to_date('$sDate','mm/dd/yyyy') and hire_date <=to_date('$eDate','mm/dd/yyyy')";
$statement = ora_parse($cursor, $sql);
if (ora_exec($cursor)){
        ora_commit($conn_lcs);
}else{
        ora_rollback($conn_lcs);
}

ora_close($cursor);
ora_logoff($conn_lcs);
?>
