<?
  include("pow_session.php");
  $id = $HTTP_POST_VARS[id];
  $whse = $HTTP_POST_VARS[whse];
  $box = $HTTP_POST_VARS[box];
  $sDate = trim($HTTP_GET_VARS[sDate]);
  $eDate = trim($HTTP_GET_VARS[eDate]);
  $eSDate = trim($HTTP_GET_VARS[eSDate]);
  $eEDate = trim($HTTP_GET_VARS[eEDate]);
  $cust = $HTTP_POST_VARS[cust];

  if ($HTTP_POST_VARS[save]<>""){
	$isSave = true;
  }else{
	$isSave = false;
  }

  if ($HTTP_POST_VARS[delete]<>""){
	$isDelete = true;
  }else{
	$isDelete = false;
  }
/*  include("connect_data_warehouse.php");
  $db = "data_warehouse";
*/
	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$whse_cursor_bni = ora_open($conn);
	$box_cursor_bni = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$insert_cursor_bni = ora_open($conn);
/*
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/

if($isSave){
  if($sDate ==""){
     	$sDate_str = "start_date is null";
	$sDate = "null";
  }else{
      	$sDate_str = "start_date = '$sDate'";
	$sDate = "'".$sDate."'";
  }
  if($eDate ==""){
       	$eDate_str = "end_date is null";
	$eDate = "null";
  }else{
       	$eDate_str = "end_date = '$eDate'";
	$eDate = "'".$eDate."'";
  }
  if($eSDate ==""){
       	$eSDate_str = "exp_start_date is null";
	$eSDate = "null";
  }else{
       	$eSDate_str = "exp_start_date = '$eSDate'";
	$eSDate = "'".$eSDate."'";
  }
  if($eEDate ==""){
       	$eEDate_str = "exp_end_date is null";
	$eEDate = "null";
  }else{
       	$eEDate_str = "exp_end_date = '$eEDate'";
	$eEDate = "'".$eEDate."'";
  }

  if ($id ==""){
	$sql = "select * from warehouse_lease where warehouse = '$whse' and box = '$box' and $sDate_str and $eDate_str and $eSDate_str and $eEDate_str and customer = '$cust'";
	$ora_success = ora_parse($short_term_data_cursor, $sql);
	$ora_success = ora_exec($short_term_data_cursor, $sql);
	if(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

/*		
		$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
        $rows = pg_num_rows($result);
	
	if ($rows ==0){
*/
		$sql = "SELECT MAX(ID) THE_MAX FROM WAREHOUSE_LEASE";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$next_lease = $short_term_row['THE_MAX'] + 1;

		$sql = "insert into warehouse_lease (id, warehouse, box, start_date, end_date, exp_start_date, exp_end_date, customer) values ('$next_lease', '$whse','$box',$sDate,$eDate,$eSDate,$eEDate,'$cust')";
		$ora_success = ora_parse($insert_cursor_bni, $sql);
		$ora_success = ora_exec($insert_cursor_bni, $sql);

		/* $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn)); */
	}
  }else{
	$sql = "update warehouse_lease set warehouse = '$whse', box = '$box', start_date = $sDate, end_date = $eDate, exp_start_date = $eSDate, exp_end_date = $eEDate, customer = '$cust' where id = $id";
	$ora_success = ora_parse($insert_cursor_bni, $sql);
	$ora_success = ora_exec($insert_cursor_bni, $sql);

	/* $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn)); */
  }
}

if ($isDelete){
	if ($id <>""){
		$sql = "delete from warehouse_lease where id = $id";
		$ora_success = ora_parse($insert_cursor_bni, $sql);
		$ora_success = ora_exec($insert_cursor_bni, $sql);

		/* $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn)); */

	}
}

  
  header("Location: index.php");

?>
