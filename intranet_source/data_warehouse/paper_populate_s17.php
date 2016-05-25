<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('Y-m-d');
  
  $cargo_system = "PAPER";

  include("connect_data_warehouse.php");
 
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $conn = ora_logon("PAPINET@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  $bni_cursor = ora_open($bni_conn);

  $qty_unit = "ROLL";
  $sql = "select t.warehouse_location, '4374', t.qty_in_house, t.supplier_id, t.barcode, t.date_received, t.arrival_num, t.STORAGE_CHARGE_TO, '4374-Holmen Paper', decode(v.vessel_name, null, null,t.pow_arrival_num ||'-'||v.vessel_name), t.supplier_name, t.mill_order_num, t.gross_weight from cargo_tracking t, vessel_profile v  where t.DATE_RECEIVED IS NOT NULL AND t.QTY_IN_HOUSE > 0  and t.arrival_num = v.arrival_num(+)";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch($cursor)){
    $location = ora_getcolumn($cursor, 0);
    $commodity = ora_getcolumn($cursor, 1);
    $qty = ora_getcolumn($cursor, 2);
    $customer = ora_getcolumn($cursor, 3);
    $cargo_id = ora_getcolumn($cursor, 4);
    $date_received = ora_getcolumn($cursor, 5);
    $date_received = date('Y-m-d', strtotime($date_received));
    $arrival_num = ora_getcolumn($cursor, 6);
    $storage_end = ora_getcolumn($cursor, 7);
    if ($storage_end <> ""){
	$storage_end = "'".date('Y-m-d', strtotime($storage_end))."'";
    }else{
	$storage_end = "null";
    }
    $commodity_name = ora_getcolumn($cursor, 8);   
    $vessel_name = ora_getcolumn($cursor, 9);
    $customer_name = ora_getcolumn($cursor, 10);
    $mill_order_num = ora_getcolumn($cursor, 11);
    $weight = ora_getcolumn($cursor, 12) / 0.454;
    $mark = $mill_order_num."-".$arrival_num;

    $customer_name = str_replace("'", "\'", $customer_name);
    $mark = str_replace("'", "\'", $mark);


    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, stacking, lr_num, customer_name, cargo_mark, weight) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$cargo_id', '$customer', '$cargo_system', '$date_received', '$stacking', '$arrival_num', '$customer_name','$mark', '$weight')";
    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, stacking, lr_num, storage_end, commodity_name, cargo_mark, vessel_name, trucked_in, customer_name, weight) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$cargo_id', '$customer', '$cargo_system', '$date_received', '', '$arrival_num',$storage_end,'$commodity_name','$mark','$vessel_name','N','$customer_name','$weight')";

    $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  }
/*
  $pgsql = "select distinct cargo_customer from cargo_detail where cargo_system = '$cargo_system' and run_date = '$run_date'";
  $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  $rows = pg_num_rows($result);

  for ($i = 0; $i < $rows; $i++){
	$row = pg_fetch_row($result, $i);
 	$cust_id = $row[0];
	
	if ($cust_id <>""){
		$sql = "select customer_name from customer_profile where customer_id = $cust_id";
		$statement = ora_parse($bni_cursor, $sql);
  		ora_exec($bni_cursor);
  		if (ora_fetch($bni_cursor)){
			$cust_name = ora_getcolumn($bni_cursor, 0);
			$pgsql = "update cargo_detail set customer_name = '$cust_name' where cargo_system = '$cargo_system' and run_date = '$run_date' and cargo_customer = '$cust_id'";
			pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));

		}
	}
  }

*/
exit;
?>
