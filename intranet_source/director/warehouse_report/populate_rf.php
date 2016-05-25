<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('Y-m-d');

  $cargo_system = "RF";

  include("connect.php");
  $host = "172.22.15.70";
  $db = "data_warehouse";
  $dbuser = "admin";

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);

  $qty_unit = "PLT";

  $sql = "select to_char(date_of_activity, 'mm/dd/yyyy'), t.warehouse_location, round(sum(a.qty_change/t.qty_received), 2), sum(a.qty_change * w.weight), decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101) as bni_comm_code from arch_2003.cargo_tracking t, arch_2003.cargo_activity a, commodity_profile c, commodity_weight w where t.commodity_code = w.commodity_code(+) and t.commodity_code = c.commodity_code and  a.pallet_id = t.pallet_id and a.service_code = 6 group by  to_char(date_of_activity, 'mm/dd/yyyy'), t.warehouse_location, decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101)";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch($cursor)){
	$aDate = ora_getcolumn($cursor, 0);
	$location = ora_getcolumn($cursor, 1);
	$qty = ora_getcolumn($cursor, 2);
	$weight = ora_getcolumn($cursor, 3)/2000;
	if ($weight == "") $weight = 0;
	$comm = ora_getcolumn($cursor, 4);

	$insSql = "insert into rf_truckloading (date_of_activity, commodity_code, qty, qty_unit, weight, location) values ('$aDate','$comm',$qty,'PLT',$weight,'$location')";
	$result = pg_query($pg_conn, $insSql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));

  }
?>
