<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('Y-m-d');
  
  $cargo_system = "CCDS";

  include("connect.php");

  $ccds_pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$ccds_pg_conn){
      die("Could not open connection to PostgreSQL CCDS database server");
  }
  
  include("connect_data_warehouse.php");

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
  }

  $qty_unit = "PLT";

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  $bni_cursor = ora_open($bni_conn);

//  $sql = "select warehouse_location, c.commodity_code, qty_unit, qty_in_house, owner_id, cargo_bol from cargo_tracking a, cargo_manifest c, commodity_profile b where qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num group by warehouse_location, c.commodity_code, qty_unit, lot_num, qty_in_house, owner_id, cargo_bol order by warehouse_location, commodity_code, qty_unit";
  $sql = "select r.location, i.pallets, i.customer_id, i.ccd_lot_id, r.receiving_date, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), r.lr_num, r.inspected from ccd_inventory i, ccd_product p, ccd_received r where i.pallets > 0 and i.product = p.product and i.ccd_lot_id = r.ccd_lot_id";
  $sql = "select r.location, i.pallets, i.customer_id, i.ccd_lot_id, r.receiving_date, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), r.lr_num, r.inspected, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity)||'-'|| p.product_name, r.mark, i.customer_id||'-'||c.customer_name from  ccd_inventory i left outer join  ccd_product p on i.product = p.product left outer join ccd_customer c on i.customer_id = c.customer_id join ccd_received r on  i.ccd_lot_id = r.ccd_lot_id  where  i.pallets > 0 ";

  $sql = "select r.location, i.pallets, i.customer_id, i.ccd_lot_id, r.receiving_date, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), r.lr_num, r.inspected, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity)||'-'|| p.product_name, r.mark, i.customer_id||'-'||c.customer_name, subst_vals(storage_charge_to,'',h.storage_end ,storage_charge_to), subst_vals(r.lr_num, -1, 'Y', 'N'), i.gross_weight from  ccd_inventory i left outer join  ccd_product p on i.product = p.product left outer join ccd_customer c on i.customer_id = c.customer_id left outer join (select ccd_lot_id, customer_id, max(storage_charge_to) as storage_end from inventory_history group by ccd_lot_id, customer_id) h on i.ccd_lot_id = h.ccd_lot_id and i.customer_id = h.customer_id join ccd_received r on  i.ccd_lot_id = r.ccd_lot_id  where  i.pallets > 0 ";
  $result = pg_query($ccds_pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($ccds_pg_conn));
  $rows = pg_num_rows($result);
  for($i = 0; $i < $rows; $i++){
    $row = pg_fetch_row($result, $i);
    $location = $row[0];
    $qty = $row[1];
    $customer = $row[2];
    $cargo_id = $row[3];
    $date_received = $row[4];
    $date_received = date('Y-m-d', strtotime($date_received));
    $commodity = $row[5];
    $lr_num = $row[6];
    $ins = $row[7];
    $commodity_name = $row[8];
    $mark = $row[9];
    $customer_name = $row[10];
    $storage_end = $row[11];
    if ($storage_end <>""){
	$storage_end = "'".date('Y-m-d', strtotime($storage_end))."'";
    }else{
	$storage_end = "null";
    }
    $trucked_in = $row[12];
    $weight = $row[13];

    $customer_name = str_replace("'", "\'", $customer_name);
    $mark = str_replace("'", "\'", $mark);

    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num, stacking, commodity_name, cargo_mark, customer_name,storage_end, trucked_in, weight) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$cargo_id', '$customer', '$cargo_system', '$date_received', '$lr_num', '$ins','$commodity_name','$mark','$customer_name', $storage_end, '$trucked_in','$weight')";
    $pg_result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  }
/*
  //update customer
  $pgsql = "select distinct cargo_customer from cargo_detail where cargo_system = 'CCDS' and run_date = '$run_date'";
  $result = pg_query($pg_conn2, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn2));
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
                        $pgsql = "update cargo_detail set customer_name = '$cust_name' where cargo_system = 'CCDS' and run_date = '$run_date' and cargo_customer = '$cust_id'";
                        pg_query($pg_conn2, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn2));
                }
        }
  }
*/
  //update vessel
  $pgsql = "select distinct lr_num from cargo_detail where cargo_system = 'CCDS' and run_date = '$run_date'";
  $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  $rows = pg_num_rows($result);

  for ($i = 0; $i < $rows; $i++){
        $row = pg_fetch_row($result, $i);
        $lr_num = $row[0];

        if ($lr_num <>""){
                $sql = "select vessel_name from vessel_profile where lr_num = $lr_num";
                $statement = ora_parse($bni_cursor, $sql);
                ora_exec($bni_cursor);
                if (ora_fetch($bni_cursor)){
                        $vessel_name = ora_getcolumn($bni_cursor, 0);
                        $pgsql = "update cargo_detail set vessel_name = '$vessel_name' where cargo_system = 'CCDS' and run_date = '$run_date' and lr_num = '$lr_num'";
                        pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
                }
        }
  }

exit;
?>
