<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('Y-m-d');
  
  $cargo_system = "BNI";

  include("connect.php");
  $db = "data_warehouse";

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);

  $sql = "select warehouse_location, a.commodity_code, qty_unit, qty_in_house, owner_id, date_received from cargo_tracking a, commodity_profile b where qty_in_house > 0 and a.commodity_code = b.commodity_code group by warehouse_location, a.commodity_code, qty_unit, lot_num, qty_in_house, owner_id, date_received order by warehouse_location, a.commodity_code, qty_unit, date_received";
  $sql = "select a.warehouse_location, a.commodity_code, a.qty_unit, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num from cargo_tracking a, commodity_profile b, cargo_manifest c where a.qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num group by a.warehouse_location, a.commodity_code, a.qty_unit, a.lot_num, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num order by warehouse_location, a.commodity_code, qty_unit, date_received";
  $sql = "select a.warehouse_location, a.commodity_code, a.qty_unit, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num, a.storage_end, b.commodity_name, d.vessel_name, e.customer_name, c.cargo_mark, decode(c.lr_num, -1, 'Y', 3, 'Y', 4, 'Y', 10, 'Y', 'N') from cargo_tracking a, commodity_profile b, cargo_manifest c, vessel_profile d, customer_profile e where a.qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num and c.lr_num = d.lr_num(+) and a.owner_id = e.customer_id(+) group by a.warehouse_location, a.commodity_code, a.qty_unit, a.lot_num, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num, a.storage_end, b.commodity_name, d.vessel_name, e.customer_name, c.cargo_mark order by warehouse_location, a.commodity_code, qty_unit, date_received";


  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch($cursor)){
    $location = ora_getcolumn($cursor, 0);
    $commodity = ora_getcolumn($cursor, 1);
    $qty_unit = ora_getcolumn($cursor, 2);
    $qty = ora_getcolumn($cursor, 3);
    $customer = ora_getcolumn($cursor, 4);
    $date_received = ora_getcolumn($cursor, 5);
    $date_received = date('Y-m-d', strtotime($date_received));
    $lot_num = ora_getcolumn($cursor, 6);
    $lr_num = ora_getcolumn($cursor, 7);
    $storage_end = ora_getcolumn($cursor, 8);
    if ($storage_end <>""){
 	$storage_end = "'".date('Y-m-d', strtotime($storage_end))."'";
    }else{
	$storage_end = "null";
    }
    $commodity_name = ora_getcolumn($cursor, 9);
    $vessel_name = ora_getcolumn($cursor, 10);
    $customer_name = ora_getcolumn($cursor, 11);
    $mark = ora_getcolumn($cursor, 12);
    $trucked_in = ora_getcolumn($cursor, 13);

    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num ) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$lot_num', '$customer', '$cargo_system', '$date_received','$lr_num')";
    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num, storage_end, commodity_name, vessel_name, customer_name, cargo_mark, trucked_in) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$lot_num', '$customer', '$cargo_system', '$date_received','$lr_num',$storage_end,'$commodity_name','$vessel_name','$customer_name','$mark','$trucked_in')";

    $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  }

exit;
?>
