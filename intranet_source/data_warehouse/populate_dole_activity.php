<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('Y-m-d');
  
  $cargo_system = "PAPER";

  include("connect_data_warehouse.php");
 
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $cursor = ora_open($conn);

  $eDate = date('m/d/Y');
  $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 60 ,date("Y")));

//  $sDate = "11/14/2001";
//  $eDate = "05/20/1998";
//  $eDate = "11/15/2001";

  $pgsql = "delete from dole_activity 
	  where activity_date >= '$sDate' and activity_date <= '$eDate'";
  $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  

  $i = 0;  
  $vTime = strtotime($sDate);
  $eTime = strtotime($eDate);

  while ($vTime < $eTime){
        $vDate = date('m/d/Y',mktime(0,0,0,date("m",strtotime($sDate)),date("d",strtotime($sDate)) + $i ,date("Y",strtotime($sDate))));
 echo $vDate."\r\n";
        $i++;
        $vTime = strtotime($vDate);

        $sql = "select commodity_code, qty_unit, warehouse_location, sum(qty_received - nvl(qty_change, 0)) 
		from cargo_tracking t, 
		(select lot_num, sum(qty_change) as qty_change from cargo_activity 
		where service_code = 6200 and date_of_activity <to_date('$vDate','mm/dd/yyyy')  group by lot_num) a
		where t.lot_num = a.lot_num(+) and commodity_code in (1272,1299) and 
		date_received <to_date('$vDate','mm/dd/yyyy')
		group by commodity_code, qty_unit, warehouse_location";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

  	while(ora_fetch($cursor)){
		$comm = ora_getcolumn($cursor, 0);
                $unit = ora_getcolumn($cursor, 1);
                $loc = ora_getcolumn($cursor, 2);
                $inv_qty = ora_getcolumn($cursor, 3);
		if ($inv_qty > 0){
    			$pgsql = "insert into dole_activity (activity_date, commodity, unit, location, inv_qty) values
							    ( '$vDate','$comm','$unit',	'$loc', $inv_qty)";
							 
    			$result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));  
		}
	}

	$sql = "select commodity_code, qty_unit, warehouse_location, sum(qty_received)
		from cargo_tracking
		where commodity_code in (1272,1299) and date_received = to_date('$vDate','mm/dd/yyyy')
		group by commodity_code, qty_unit, warehouse_location";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

        while(ora_fetch($cursor)){
                $comm = ora_getcolumn($cursor, 0);
                $unit = ora_getcolumn($cursor, 1);
                $loc = ora_getcolumn($cursor, 2);
                $in_qty = ora_getcolumn($cursor, 3);

		$pgsql = "select * from dole_activity 
			  where activity_date='$vDate' and commodity='$comm' and unit = '$unit' and location = '$loc'";
		$result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
		$rows = pg_num_rows($result);
		if ($rows == 1){
			$pgsql = "update dole_activity set in_qty = $in_qty where activity_date='$vDate' and 
				  commodity='$comm' and unit = '$unit' and location = '$loc'";
		}else{
			$pgsql = "insert into dole_activity (activity_date, commodity, unit, location, in_qty) values
                                                            ( '$vDate','$comm','$unit', '$loc', $in_qty)";
		}
		$result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
	}
	$sql = "select commodity_code, qty_unit, warehouse_location, sum(nvl(qty_change, 0)) 
		from cargo_tracking t, 
		(select lot_num, sum(qty_change) as qty_change from cargo_activity 
		where service_code = 6200 and date_of_activity = to_date('$vDate','mm/dd/yyyy')  group by lot_num) a
		where t.lot_num=a.lot_num and commodity_code in (1272,1299) and date_received<=to_date('$vDate','mm/dd/yyyy')
		group by commodity_code, qty_unit, warehouse_location";
        $sql = "select commodity_code, qty_unit, warehouse_location, sum(nvl(out, 0))
		from (
		select commodity_code, qty_unit, warehouse_location, sum(nvl(qty_change, 0)) out
                from cargo_tracking t,
                (select lot_num, sum(qty_change) as qty_change from cargo_activity
                where service_code = 6200 and date_of_activity = to_date('$vDate','mm/dd/yyyy')  group by lot_num) a
                where t.lot_num=a.lot_num and commodity_code in (1272,1299) and date_received<=to_date('$vDate','mm/dd/yyyy')
                group by commodity_code, qty_unit, warehouse_location
		union all
		select commodity_code, qty_unit, warehouse_location, sum(nvl(qty_change, 0)) out
                from cargo_tracking t,
                (select lot_num, sum(qty_change) as qty_change from cargo_activity
                where service_code = 6200 and date_of_activity < to_date('$vDate','mm/dd/yyyy')  group by lot_num) a
                where t.lot_num=a.lot_num and commodity_code in (1272,1299) and date_received =to_date('$vDate','mm/dd/yyyy')
                group by commodity_code, qty_unit, warehouse_location
		) group by commodity_code, qty_unit, warehouse_location";

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

        while(ora_fetch($cursor)){
                $comm = ora_getcolumn($cursor, 0);
                $unit = ora_getcolumn($cursor, 1);
                $loc = ora_getcolumn($cursor, 2);
                $out_qty = ora_getcolumn($cursor, 3);

                $pgsql = "select * from dole_activity
                          where activity_date='$vDate' and commodity='$comm' and unit = '$unit' and location = '$loc'";
                $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
                $rows = pg_num_rows($result);
                if ($rows == 1){
                        $pgsql = "update dole_activity set out_qty = $out_qty where activity_date='$vDate' and
                                  commodity='$comm' and unit = '$unit' and location = '$loc'";
                }else{
                        $pgsql = "insert into dole_activity (activity_date, commodity, unit, location, out_qty) values
                                                            ( '$vDate','$comm','$unit', '$loc', $out_qty)";
                }
                $result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
        }

  }

?>
