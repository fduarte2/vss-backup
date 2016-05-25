<?
  // Populates the data_warehouse.cargo_detail table using data from BNI
  $run_date = date('Y-m-d');
      // Adam Walter, May 22, 2007.  The report that follows this script is taking
	  // over 8 hours to run.  I am adding a sent email both here and at file end
	  // to figure out if this is the culprit
//	  mail("awalter@port.state.de.us", "holmen population check", "first of 2 test mails");
  
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

  $qty_unit = "ROLL";

  $eDate = date('m/d/Y');
  $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 30 ,date("Y")));
  
  $sDate = "06/15/2004";

  $pgsql = "delete from holmen_activity 
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
	$sql = "(select v.pow_arrival_num, v.vessel_name, t.mill_order_num, t.barcode, 
		t.gross_weight as inv_weight, 1 as inv_qty, 
		0 as in_weight, 0 as in_qty, 
		0 as out_weight, 0 as out_qty, '' as order_num, '' as destination, '' as transport_mode 
		from vessel_profile v, cargo_tracking t, 
		(select * from cargo_activity where service_code = 2 and 
		(activity_status is null or activity_status <>'VOID') and activity_date < to_date('$vDate','mm/dd/yyyy')) a
		where t.arrival_num = a.arrival_num(+) and t.mill_order_num = a.mill_order_num(+) and 
		t.barcode = a.barcode(+) and a.activity_date is null and t.date_received is not null and 
		t.date_received < to_date('$vDate','mm/dd/yyyy') and t.arrival_num = v.arrival_num)
		union all
		(select v.pow_arrival_num, v.vessel_name, t.mill_order_num, t.barcode, 
		0 as inv_weight, 0 as inv_qty, 
		t.gross_weight as in_weight, 1 as in_qty, 
		0 as out_weight, 0 as out_qty, '' as order_num, '' as destination, '' as transport_mode
		from vessel_profile v, cargo_tracking t
		where t.date_received is not null and trunc(t.date_received,'dd') = to_date('$vDate','mm/dd/yyyy')
		and t.arrival_num = v.arrival_num)
		union all
		(select v.pow_arrival_num, v.vessel_name, t.mill_order_num, t.barcode, 
		0 as inv_weight, 0 as inv_qty, 
		0 as in_weight, 0 as in_qty, 
		t.gross_weight as out_weight, 1 as out_qty, o.order_num, o.destination, o.transport_mode 
		from vessel_profile v, cargo_tracking t, order_detail o,
		(select * from cargo_activity where service_code = 2 and 
		(activity_status is null or activity_status <>'VOID')) a
		where t.arrival_num = a.arrival_num and t.mill_order_num = a.mill_order_num and t.barcode = a.barcode and
		a.activity_date is not null and trunc(a.activity_date, 'dd') =  to_date('$vDate','mm/dd/yyyy') and
		t.date_received is not null and t.arrival_num = v.arrival_num and a.order_num = o.order_num)";

     $sql = "select pow_arrival_num, vessel_name, mill_order_num, '', sum(inv_weight), sum(inv_qty), 
		sum(in_weight), sum(in_qty), sum(out_weight), sum(out_qty), order_num, destination, transport_mode
		from (
		(select v.pow_arrival_num, v.vessel_name, t.mill_order_num, t.barcode,
                t.gross_weight as inv_weight, 1 as inv_qty,
                0 as in_weight, 0 as in_qty,
                0 as out_weight, 0 as out_qty, '' as order_num, '' as destination, '' as transport_mode
                from vessel_profile v, cargo_tracking t,
                (select * from cargo_activity where service_code = 2 and
                (activity_status is null or (activity_status <>'CANCELLED' and activity_status <>'VOID')) and 
		activity_date < to_date('$vDate','mm/dd/yyyy')) a
                where t.arrival_num = a.arrival_num(+) and t.mill_order_num = a.mill_order_num(+) and
                t.barcode = a.barcode(+) and a.activity_date is null and t.date_received is not null and
                t.date_received < to_date('$vDate','mm/dd/yyyy') and t.arrival_num = v.arrival_num)
                union all
                (select v.pow_arrival_num, v.vessel_name, t.mill_order_num, t.barcode,
                0 as inv_weight, 0 as inv_qty,
                t.gross_weight as in_weight, 1 as in_qty,
                0 as out_weight, 0 as out_qty, '' as order_num, '' as destination, '' as transport_mode
                from vessel_profile v, cargo_tracking t
                where t.date_received is not null and trunc(t.date_received,'dd') = to_date('$vDate','mm/dd/yyyy')
                and t.arrival_num = v.arrival_num)
                union all
                (select v.pow_arrival_num, v.vessel_name, t.mill_order_num, t.barcode,
                0 as inv_weight, 0 as inv_qty,
                0 as in_weight, 0 as in_qty,
                t.gross_weight as out_weight, 1 as out_qty, o.order_num, o.destination, o.transport_mode
                from vessel_profile v, cargo_tracking t, order_detail o,
                (select * from cargo_activity where service_code = 2 and
                (activity_status is null or (activity_status <>'CANCELLED' and activity_status <>'VOID'))) a
                where t.arrival_num = a.arrival_num and t.mill_order_num = a.mill_order_num and t.barcode = a.barcode and
                a.activity_date is not null and trunc(a.activity_date, 'dd') =  to_date('$vDate','mm/dd/yyyy') and
                t.date_received is not null and t.arrival_num = v.arrival_num and a.order_num = o.order_num)
		) u group by pow_arrival_num, vessel_name, mill_order_num,order_num, destination, transport_mode";

  	$statement = ora_parse($cursor, $sql);
  	ora_exec($cursor);
  	while(ora_fetch($cursor)){
		$lr_num = ora_getcolumn($cursor, 0);
                $vessel = ora_getcolumn($cursor, 1);
                $mill_order_num = ora_getcolumn($cursor, 2);
                $barcode = ora_getcolumn($cursor, 3);
                $inv_weight = ora_getcolumn($cursor, 4);
                $inv_qty = ora_getcolumn($cursor, 5);
                $in_weight= ora_getcolumn($cursor, 6);
                $in_qty = ora_getcolumn($cursor, 7);
                $out_weight = ora_getcolumn($cursor, 8);
                $out_qty = ora_getcolumn($cursor, 9);
                $order_num = ora_getcolumn($cursor, 10);
                $destination = ora_getcolumn($cursor, 11);
		$transport_mode = ora_getcolumn($cursor, 12);

    		$pgsql = "insert into holmen_activity ( activity_date, 
							lr_num, 
							vessel,
							mill_order_num, 
							barcode, 
							inv_weight,
							inv_qty,
							in_weight,
							in_qty,
							out_weight,
							out_qty,
							order_num,
							destination,
							transport_mode ) values
						      ( '$vDate',
							'$lr_num',
							'$vessel',
							'$mill_order_num',
							'$barcode',
							$inv_weight,
							$inv_qty,
							$in_weight,
							$in_qty,
							$out_weight,
							$out_qty,
							'$order_num',
							'$destination',
							'$transport_mode')";
							 
    		$result = pg_query($pg_conn, $pgsql) or die("Error in query: $pgsql. " . pg_last_error($pg_conn));
  
	}
  }
	  // second of the test emails, described at the top of the file
//	  mail("awalter@port.state.de.us", "holmen population check(2)", "second of 2 test mails");

?>
