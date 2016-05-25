<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('d-M-y');
  
  $cargo_system = "RF";


  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);

  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($bni_conn));
    printf("Please try later!");
    exit;
  }
  $bni_cursor = ora_open($bni_conn);

  $qty_unit = "PLT";
  
  $sql = "select t.warehouse_location, t.commodity_code, t.qty_in_house, t.receiver_id, t.pallet_id, t.date_received, t.stacking, t.arrival_num, t.billing_storage_date, t.commodity_code ||'-'||c.commodity_name, t.mark, decode(v.vessel_name, null, null,t.arrival_num ||'-'||v.vessel_name), decode(t.receiving_type, 'T', 'Y', 'N'),(t.qty_in_house/t.qty_received) * 2000  from cargo_tracking t, commodity_profile c, vessel_profile v  where t.DATE_RECEIVED IS NOT NULL AND t.QTY_IN_HOUSE > 0 AND t.RECEIVER_ID <> 453 and t.commodity_code = c.commodity_code(+) and t.arrival_num = to_char(v.lr_num(+))";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch($cursor)){
    $location = ora_getcolumn($cursor, 0);
    $commodity = ora_getcolumn($cursor, 1);
    $qty = ora_getcolumn($cursor, 2);
    $customer = ora_getcolumn($cursor, 3);
    $cargo_id = ora_getcolumn($cursor, 4);
    $date_received = ora_getcolumn($cursor, 5);
    $date_received = date('d-M-y', strtotime($date_received));
    $stacking = ora_getcolumn($cursor, 6);
    $arrival_num = ora_getcolumn($cursor, 7);
    $storage_end = ora_getcolumn($cursor, 8);
    if ($storage_end <> ""){
	$storage_end = "'".date('d-M-y', strtotime($storage_end))."'";
    }else{
	$storage_end = "null";
    }
    $commodity_name = ora_getcolumn($cursor, 9);
    $mark = ora_getcolumn($cursor, 10);
    $vessel_name = ora_getcolumn($cursor, 11);
    $trucked_in = ora_getcolumn($cursor, 12);
    $weight = ora_getcolumn($cursor, 13);


    $strSql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, stacking, lr_num, storage_end, commodity_name, cargo_mark, vessel_name, trucked_in, weight) values ('$run_date', '$location', '$commodity', '$qty', '$qty_unit', '$cargo_id', '$customer', '$cargo_system', '$date_received', '$stacking', '$arrival_num',$storage_end,'$commodity_name','$mark','$vessel_name','$trucked_in', '$weight')";

    $statement2 = ora_parse($cursor2, $strSql);
    ora_exec($cursor2);

  }

  $strSql = "select distinct cargo_customer from cargo_detail where cargo_system = 'RF' and run_date = '$run_date'";
  

  $statement = ora_parse($cursor, $strSql);
  ora_exec($cursor);
  while(ora_fetch($cursor)){
 	$cust_id = ora_getcolumn($cursor, 0);
	
	if ($cust_id <>""){
		$sql = "select customer_name from customer_profile where customer_id = $cust_id";
		$statement = ora_parse($bni_cursor, $sql);
  		ora_exec($bni_cursor);
  		if (ora_fetch($bni_cursor)){
			$cust_name = ora_getcolumn($bni_cursor, 0);
			$sql = "update cargo_detail set customer_name = '$cust_name' where cargo_system = 'RF' and run_date = '$run_date' and cargo_customer = '$cust_id'";
			$statement2 = ora_parse($cursor2, $sql);
  			ora_exec($cursor2);


		}
	}
  }


exit;
?>
