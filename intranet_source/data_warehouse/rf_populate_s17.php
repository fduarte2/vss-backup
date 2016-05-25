<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
//  $run_date = date('Y-m-d');
  $run_date = date('d-M-Y');
  
//  $cargo_system = "RF";

//  include("connect_data_warehouse.php");
/* 
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/
/*  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);
*/
  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  $bni_cursor = ora_open($bni_conn);

//  $qty_unit = "PLT";
//  $sql = "select warehouse_location, c.commodity_code, qty_unit, qty_in_house, owner_id, cargo_bol from cargo_tracking a, cargo_manifest c, commodity_profile b where qty_in_house > 0 and a.commodity_code = b.commodity_code and a.lot_num = c.container_num group by warehouse_location, c.commodity_code, qty_unit, lot_num, qty_in_house, owner_id, cargo_bol order by warehouse_location, commodity_code, qty_unit";
//  $sql = "select warehouse_location, commodity_code, qty_in_house, receiver_id, pallet_id, date_received, stacking from cargo_tracking where qty_in_house > 4 and free_time_end is not null";
//  $sql = "select warehouse_location, commodity_code, qty_in_house, receiver_id, pallet_id, date_received, stacking, arrival_num from cargo_tracking where DATE_RECEIVED IS NOT NULL AND QTY_IN_HOUSE > 5 AND RECEIVER_ID <> 453";

  // 11/27/2006- Per Marty's request to exclude three vessels ('9481', '9492', '4321')
  // 9/11/2007, edited Adam walter to include "t.FROM_SHIPPING_LINE <> '8012'" (excluding RF kiwis, essentially)
  // 4/10/2008: edited Adam Walter to include "t.commodity_code not in ('3302', '3304', '3326') to exclude steel
/*  $sql = "select t.warehouse_location, t.commodity_code, t.qty_in_house, t.receiver_id, t.pallet_id, t.date_received, t.stacking, t.arrival_num, t.billing_storage_date, t.commodity_code ||'-'||c.commodity_name, t.mark, decode(v.vessel_name, null, null,t.arrival_num ||'-'||v.vessel_name), decode(t.receiving_type, 'T', 'Y', 'N'),(t.qty_in_house/t.qty_received) * 2000  from cargo_tracking t, commodity_profile c, vessel_profile v  where t.DATE_RECEIVED IS NOT NULL AND t.QTY_IN_HOUSE > 0 AND t.RECEIVER_ID <> 453 and t.commodity_code = c.commodity_code(+) and t.arrival_num = to_char(v.lr_num(+)) and t.commodity_code not in ('3302', '3304', '3326') and t.ARRIVAL_NUM NOT IN ('9481', '9492', '4321') and (t.FROM_SHIPPING_LINE <> '8012' or t.FROM_SHIPPING_LINE is null)";
*/
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
		STACKING,
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
		'PLT',
		CT.PALLET_ID,
		CT.RECEIVER_ID,
		'RF',
		CT.DATE_RECEIVED,
		CT.STACKING,
		CT.ARRIVAL_NUM,
		CT.BILLING_STORAGE_DATE,
		COMP.COMMODITY_CODE || '-' || COMP.COMMODITY_NAME,
		decode(CT.RECEIVING_TYPE, 'T', '', VP.VESSEL_NAME),
		CUSP.CUSTOMER_NAME,
		CT.MARK,
		decode(CT.RECEIVING_TYPE, 'T', 'Y', 'N'),
		DECODE(QTY_RECEIVED, 0, 0, (CT.QTY_IN_HOUSE/CT.QTY_RECEIVED) * 2000)
		FROM CARGO_TRACKING_FROM_RF CT, COMMODITY_PROFILE_FROM_RF COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP
		WHERE CT.QTY_IN_HOUSE > 0
		AND CT.QTY_IN_HOUSE >= NVL((SELECT QTY_THRESHOLD FROM MINIMUM_INHOUSE_THRESHOLD@RF.PROD WHERE COMMODITY_TYPE = COMP.COMMODITY_TYPE), 1)
		AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
		AND COMP.COMMODITY_TYPE <> 'ARG JUICE'
		AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
		AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID(+)
		AND CT.DATE_RECEIVED IS NOT NULL
		AND CT.RECEIVER_ID != '453'
		AND (CT.COMMODITY_CODE NOT IN ('3302', '3304', '3326')
			OR
			CT.RECEIVER_ID IN ('1858', '1860', '6979', '1865')
			)
		AND CT.ARRIVAL_NUM NOT IN ('9481', '9492', '4321')
		AND (CT.FROM_SHIPPING_LINE <> '8012' or CT.FROM_SHIPPING_LINE is null)";
  $statement = ora_parse($bni_cursor, $sql);
  ora_exec($bni_cursor);
/*
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
    $stacking = ora_getcolumn($cursor, 6);
    $arrival_num = ora_getcolumn($cursor, 7);
    $storage_end = ora_getcolumn($cursor, 8);
    if ($storage_end <> ""){
	$storage_end = "'".date('Y-m-d', strtotime($storage_end))."'";
    }else{
	$storage_end = "null";
    }
    $commodity_name = ora_getcolumn($cursor, 9);
    $mark = ora_getcolumn($cursor, 10);
    $vessel_name = ora_getcolumn($cursor, 11);
    $trucked_in = ora_getcolumn($cursor, 12);
    $weight = ora_getcolumn($cursor, 13);

    $customer_name = str_replace("'", "\'", $customer_name);
    $mark = str_replace("'", "\'", $mark);

//    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, stacking, lr_num) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$cargo_id', '$customer', '$cargo_system', '$date_received', '$stacking', '$arrival_num')";
    $pgsql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, stacking, lr_num, storage_end, commodity_name, cargo_mark, vessel_name, trucked_in, weight) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$cargo_id', '$customer', '$cargo_system', '$date_received', '$stacking', '$arrival_num',$storage_end,'$commodity_name','$mark','$vessel_name','$trucked_in','$weight')";

    $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  }

  $pgsql = "select distinct cargo_customer from cargo_detail where cargo_system = 'RF' and run_date = '$run_date'";
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
			$pgsql = "update cargo_detail set customer_name = '$cust_name' where cargo_system = 'RF' and run_date = '$run_date' and cargo_customer = '$cust_id'";
			pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));

		}
	}
  }
*/

exit;
?>
