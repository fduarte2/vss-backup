<?
  include("pow_session.php");
  $user = $userdata['username'];

  $today = date('m/d/Y');
  $timeStamp = date('m/d/Y H:i');

  //get connect
//  include("connect_data_warehouse.php");
 
/*  
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
   	printf("Error logging on to the Oracle Server: ");
   	printf(ora_errorcode($conn));
   	exit;
  }
  $Short_Term_Cursor_BNI = ora_open($conn);

  $now = date('m/d/Y');

/*
  $sql = "UPDATE UTILIZATION_HISTORY SET CONFIRMER_ID = '".$user."' WHERE RUN_DATE = to_date('".$now."', 'MM/DD/YYYY')";
  ora_parse($cursor, $sql);
  ora_exec($cursor);

  $sql = "select * from utilization_confirm where report_date = '$today'";

  $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));

  $rows = pg_num_rows($result);
  
  if ($rows == 0 ){
*/
  	$sql ="insert into utilization_confirm (report_date, confirm_date, user_name) values (TO_DATE('$today', 'MM/DD/YYYY'), TO_DATE('$timeStamp', 'MM/DD/YYYY HH24:MI'),'$user')";
	$ora_success = ora_parse($Short_Term_Cursor_BNI, $sql);
	$ora_success = ora_exec($Short_Term_Cursor_BNI, $sql);
	ora_commit($conn);
	ora_close($Short_Term_Cursor_BNI);
	ora_logoff($conn);


/*
  	$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
*/
  	$command = "/web/web_pages/director/warehouse_rep/mail_warehouse_rep1.sh > /dev/null 2>&1";
  	system($command);
  
  	echo "Following daily Warehouse Utilization Report was confirmed by OPS.";
?>
