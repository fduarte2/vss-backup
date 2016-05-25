<?
  if ($HTTP_POST_VARS['status'] == "Completed"){
	header("location: print1.php?reqId=$reqId");
  	exit;
  }
  include("pow_session.php");
  $user = $userdata['username'];

  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  //get parmeters
  $reqId = $HTTP_POST_VARS[reqId];
  $empName = $HTTP_POST_VARS[empName];
  $empId = $HTTP_POST_VARS[empId];
  $ssn = $HTTP_POST_VARS[ssn];
  $bRate = $HTTP_POST_VARS[bRate];
  $eDate = $HTTP_POST_VARS[eDate];
  $hPay = $HTTP_POST_VARS[hPay];
  $holiday = $HTTP_POST_VARS[holiday];


  $hDate = $HTTP_POST_VARS[hDate];
  $pay = $HTTP_POST_VARS[pay];
  $hour = $HTTP_POST_VARS[hour];
  $gratuities = $HTTP_POST_VARS[gratuities];
  $absent = $HTTP_POST_VARS[absent];

  if (isset($HTTP_POST_VARS['finalize'])){
	$status = "Completed";
  }else{
	$status = "Processed";
  }

  if ($hPay =="")
	$hPay = "null";

  if ($holiday ==""){
	$holiday = "null";
  }else{
	$holiday = "to_date('$holiday','mm/dd/yyyy')";
  }
  
  $size = count($hDate);

  if ($reqId == ""){
	$sql = "select max(request_id) from unemployment_claim_request";
	$success = ora_parse($cursor, $sql);
	do_database_check($success);
	$success = ora_exec($cursor);
        do_database_check($success);
	
	if (ora_fetch($cursor)){
		$reqId = ora_getcolumn($cursor, 0) + 1;
	}else{
		$reqId = 200500001;
	}
 	$sql = "select employee_id from employee where substr(employee_id,4,4) = '$empId'";
        $success = ora_parse($cursor, $sql);
        do_database_check($success);
        $success = ora_exec($cursor);
        do_database_check($success);
	
	if (ora_fetch($cursor)){
		$empId = ora_getcolumn($cursor, 0);
	}

        $sql = "insert into unemployment_claim_request (request_id, employee_id, pay_period_end_date, request_date,status)
                values ($reqId, '$empId', to_date('$eDate','mm/dd/yy'), sysdate, '$status')";
 
        $success = ora_parse($cursor, $sql);
        do_database_check($success);
        $success = ora_exec($cursor);
        do_database_check($success);	
  }
  
  $sql = "insert into unemployment_claim_header_log 
	  (request_id,employee_name,ssn,pay_rate,pay_period_end_date,holiday_pay,holiday,update_time,user_name,status)
	  select request_id,employee_name,ssn,pay_rate,pay_period_end_date,holiday_pay,holiday, update_time,user_name,status
	  from unemployment_claim_header where request_id = $reqId";
  $success = ora_parse($cursor, $sql);
  do_database_check($success);
  $success = ora_exec($cursor);
  do_database_check($success);
 
  $sql = "insert into unemployment_claim_body_log
	  (request_id,date_worked,wage,hours_worked,gratuities,houes_absent,update_time)
	  select request_id,date_worked,wage,hours_worked,gratuities,houes_absent,update_time
	  from unemployment_claim_body where request_id = $reqId";

  $success = ora_parse($cursor, $sql);
  do_database_check($success);
  $success = ora_exec($cursor);
  do_database_check($success);

  $sql = "delete from unemployment_claim_header where request_id = $reqId";
  $success = ora_parse($cursor, $sql);
  do_database_check($success);
  $success = ora_exec($cursor);
  do_database_check($success);

  $sql = "delete from unemployment_claim_body where request_id = $reqId";
  $success = ora_parse($cursor, $sql);
  do_database_check($success);
  $success = ora_exec($cursor);
  do_database_check($success);

  $sql = "insert into unemployment_claim_header
          (request_id,employee_name,ssn,pay_rate,pay_period_end_date,holiday_pay,holiday,update_time,user_name,status)
	  values ($reqId, '$empName','$ssn',$bRate,to_date('$eDate','mm/dd/yyyy'),$hPay,$holiday,sysdate,'$user','$status')";

  $success = ora_parse($cursor, $sql);
  do_database_check($success);
  $success = ora_exec($cursor);
  do_database_check($success);

  for ($i = 0; $i<$size-1; $i++){
        if ($gratuities[$i] == "")
		$gratuities[$i] = 0;

	$sql = "insert into unemployment_claim_body
          	(request_id,date_worked,wage,hours_worked,gratuities,houes_absent,update_time)
	 	select $reqId, to_date('$hDate[$i]','mm/dd/yyyy'),$pay[$i],$hour[$i],$gratuities[$i],$absent[$i], update_time
		from unemployment_claim_header where request_id = $reqId";

        $success = ora_parse($cursor, $sql);
        do_database_check($success);
        $success = ora_exec($cursor);
        do_database_check($success);
//echo $sql;
  }
  
  $sql = "update unemployment_claim_request set status = '$status' where request_id = $reqId";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  ora_commit($conn);
  ora_close($cursor);
  ora_logoff($conn);
  
  if (isset($HTTP_POST_VARS['finalize'])){
?>
<form name="save" action=unemp.php method="post">
<input type="hidden" name="reqId" value="<?= $reqId?>">
</form>
<script language="javascript">
document.save.submit();
</script>	
<?	
  }else{
  	header("location: print1.php?reqId=$reqId");
  }
// my database error handler
function do_database_check($ora_success) {
  global $conn, $cursor;

  if (!$ora_success) {
    ora_rollback($conn);
    ora_close($cursor);
    ora_logoff($conn);

    exit;
  }
}

?>
