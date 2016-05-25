<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
//  $run_date = date('Y-m-d');
  $run_date = date('d-M-Y');
  
  $cargo_system = "BNI";

  include("connect_data_warehouse.php");
/*  
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);

//  $sql = "select warehouse_location, a.commodity_code, qty_unit, qty_in_house, owner_id, date_received from cargo_tracking a, commodity_profile b where qty_in_house > 0 and a.commodity_code = b.commodity_code group by warehouse_location, a.commodity_code, qty_unit, lot_num, qty_in_house, owner_id, date_received order by warehouse_location, a.commodity_code, qty_unit, date_received";
//  $sql = "select a.warehouse_location, a.commodity_code, a.qty_unit, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num from cargo_tracking a, commodity_profile b, cargo_manifest c where a.qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num group by a.warehouse_location, a.commodity_code, a.qty_unit, a.lot_num, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num order by warehouse_location, a.commodity_code, qty_unit, date_received";
//  $sql = "select a.warehouse_location, a.commodity_code, a.qty_unit, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num, a.storage_end, b.commodity_name, d.vessel_name, e.customer_name, c.cargo_mark, decode(c.lr_num, -1, 'Y', 3, 'Y', 4, 'Y', 10, 'Y', 'N'), (a.qty_in_house/qty_expected)*c.cargo_weight from cargo_tracking a, commodity_profile b, cargo_manifest c, vessel_profile d, customer_profile e where a.qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num and c.lr_num = d.lr_num(+) and a.owner_id = e.customer_id(+)";

  $sql = "INSERT INTO CARGO_DETAIL
		(RUN_DATE,
		LOCATION,
		COMMODITY_CODE,
		QTY,
		QTY_UNIT,
		CARGO_ID,
		CARGO_CUSTOMER,
		CARGO_SYSTEM,
		DATE_RECEIVED,
		LR_NUM,
		STORAGE_END,
		COMMODITY_NAME,
		VESSEL_NAME,
		CUSTOMER_NAME,
		CARGO_MARK,
		TRUCKED_IN,
		WEIGHT)
		SELECT
		'".$run_date."',
		CT.WAREHOUSE_LOCATION,
		CT.COMMODITY_CODE,
		CT.QTY_IN_HOUSE,
		CT.QTY_UNIT,
		CT.LOT_NUM,
		CT.OWNER_ID,
		'BNI',
		CT.DATE_RECEIVED,
		CM.LR_NUM,
		CT.STORAGE_END,
		COMP.COMMODITY_NAME,
		VP.VESSEL_NAME,
		CUSP.CUSTOMER_NAME,
		CM.CARGO_MARK,
		decode(CM.LR_NUM, -1, 'Y', 3, 'Y', 4, 'Y', 10, 'Y', 'N'),
		(CT.QTY_IN_HOUSE/CM.QTY_EXPECTED)*CM.CARGO_WEIGHT
		FROM CARGO_TRACKING CT, CARGO_MANIFEST CM, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
		WHERE CT.QTY_IN_HOUSE > 0
		AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
		AND CT.COMMODITY_CODE NOT IN ('5011', '5013')
		AND CT.LOT_NUM = CM.CONTAINER_NUM
		AND CM.LR_NUM = VP.LR_NUM(+)
		AND CT.OWNER_ID = CUSP.CUSTOMER_ID(+)";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);



  
  // 12/20/2006-SQL statemanet was modified to add 'WING ' to warehouse A if not already exist and exclude 'A SHED'	  
  //$sql ="select decode(UPPER(substr(a.warehouse_location, 1,1)), 'A', 'WING ' || a.warehouse_location,a.warehouse_location ), a.commodity_code, a.qty_unit, a.qty_in_house, a.owner_id, a.date_received, a.lot_num, c.lr_num, a.storage_end, b.commodity_name, d.vessel_name, e.customer_name, c.cargo_mark, decode(c.lr_num, -1, 'Y', 3, 'Y', 4, 'Y', 10, 'Y', 'N'), (a.qty_in_house/qty_expected)*c.cargo_weight from cargo_tracking a, commodity_profile b, cargo_manifest c, vessel_profile d, customer_profile e where a.qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num and c.lr_num = d.lr_num(+) and a.owner_id = e.customer_id(+) and UPPER(a.WAREHOUSE_LOCATION) <> 'A SHED'";
/*  
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
    $vessel_name=str_replace("'", "", $vessel_name);
    $customer_name = ora_getcolumn($cursor, 11);
    $mark = ora_getcolumn($cursor, 12);
    $trucked_in = ora_getcolumn($cursor, 13);
    $weight = ora_getcolumn($cursor, 14);

    $customer_name = str_replace("'", "\'", $customer_name);
    $mark = str_replace("'", "\'", $mark);
   
//    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num ) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$lot_num', '$customer', '$cargo_system', '$date_received','$lr_num')";
    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num, storage_end, commodity_name, vessel_name, customer_name, cargo_mark, trucked_in, weight) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$lot_num', '$customer', '$cargo_system', '$date_received','$lr_num',$storage_end,'$commodity_name','$vessel_name','$customer_name','$mark','$trucked_in','$weight')";

    $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  }
*/
exit;
?>
