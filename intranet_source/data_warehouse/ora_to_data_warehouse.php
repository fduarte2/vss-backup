<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
//  $run_date = '08/03/2004';
  $sDate = '10/01/2004';
  $eDate = '10/01/2004';

  $cargo_system = "BNI";


  include("connect_data_warehouse.php");

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
  }

  include("connect.php");

  
  $qty_unit = "PLT";

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($rf_conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $rf_cursor = ora_open($rf_conn);
 
  $strSql ="select * from cargo_detail where run_date >=to_date('07/07/2004','mm/dd/yyyy') and run_date <=to_date('07/07/2004','mm/dd/yyyy')";
  $strSql = "select * from cargo_detail 
	     where run_date >=to_date('$sDate','mm/dd/yyyy') and run_date <=to_date('$eDate','mm/dd/yyyy') and 
	     cargo_system='$cargo_system'";


  $statement = ora_parse($rf_cursor, $strSql);
  ora_exec($rf_cursor);

  while (ora_fetch($rf_cursor)){
	$run_date = ora_getcolumn($rf_cursor,0);
        $location = ora_getcolumn($rf_cursor,1);
        $location2 = ora_getcolumn($rf_cursor,2);
        $commodity = ora_getcolumn($rf_cursor,3);
        $qty = ora_getcolumn($rf_cursor,4);
        $qty_unit = ora_getcolumn($rf_cursor,5);
        $cargo_id = ora_getcolumn($rf_cursor,6);
        $cust_id = ora_getcolumn($rf_cursor,7);
        $cargo_system = ora_getcolumn($rf_cursor,8);
        $date_received = ora_getcolumn($rf_cursor,9);
        $stacking = ora_getcolumn($rf_cursor,10);
        $lr_num = ora_getcolumn($rf_cursor,11);
        $customer_name= ora_getcolumn($rf_cursor,12);
        $commodity_name = ora_getcolumn($rf_cursor,13);
        $mark = ora_getcolumn($rf_cursor,14);
        $storage_end= ora_getcolumn($rf_cursor,15);
        $vessel = ora_getcolumn($rf_cursor,16);
        $trucked_in = ora_getcolumn($rf_cursor,17);

	if (trim($storage_end) =="") {
		$storage_end = "null";
	}else{
		$storage_end = "'$storage_end'";
	}

    	$strSql = "insert into cargo_detail (run_date, location, location2, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num, stacking, commodity_name, cargo_mark, customer_name,storage_end, vessel_name,trucked_in) values ('$run_date', '$location', '$location2','$commodity', '$qty', '$qty_unit', '$cargo_id', '$cust_id', '$cargo_system', '$date_received', '$lr_num', '$stacking','$commodity_name','$mark','$customer_name', $storage_end, '$vessel', '$trucked_in')";
  	$result = pg_query($pg_conn, $strSql) or die("Error in query: $strSql. " . pg_last_error($pg_conn));

  }
exit;
?>
