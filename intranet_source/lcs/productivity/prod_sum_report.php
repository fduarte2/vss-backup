<?

   $sDate = "07/01/2002";
   $eDate = "06/30/2003";
   $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") -3 ,date("Y")));
   $eDate = $sDate;


//   $eDate = "07/01/2003";

   // open a connection to the database server
   include("connect.php");

   $conn_ccd = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$conn_ccd){
      	die("Could not open connection to PostgreSQL database server");
   }

   $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn_bni < 1){
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   }
   $cursor_bni = ora_open($conn_bni);
   $cursor_bni2 = ora_open($conn_bni);

   $conn_rf = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn_rf < 1){
        printf("Error logging on to the RF Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   $cursor_rf = ora_open($conn_rf);

   $conn_paper = ora_logon("PAPINET@RF", "OWNER");
   if($conn_paper < 1){
        printf("Error logging on to the RF Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   $cursor_paper = ora_open($conn_paper);

   $conn_lcs = ora_logon("LABOR@LCS", "LABOR");
   if($conn_lcs < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
   }
   $cursor_lcs = ora_open($conn_lcs);
   $cursor_lcs2 = ora_open($conn_lcs);
   
   $productivity = "productivity_hire_plan";

   //clear productivity table
   $sql = "delete from $productivity where date_time >= to_date('$sDate','mm/dd/yyyy') and date_time <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);



   $type[0] = "TRUCKLOADING";
   $type[1] = "BACKHAUL";
   $type[2] = "TERMINAL SERVICE CUSTOMER";
   $type[3] = "TERMINAL SERVICE DSPC";
   $type[4] = "RAIL CAR HANDLING";
   $type[5] = "CONTAINER HANDLING";
   $type[6] = "MAINTENANCE";
   $type[7] = "NON REVENUE";
   $type[8] = "STAND BY";

   $service_code_in[0] = " and service_code between 6220 and 6229 ";
   $service_code_in[1] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6149) ";
   $service_code_in[2] = " and service_code between 6520 and 6559 ";
   $service_code_in[3] = " and service_code between 6560 and 6619 ";
   $service_code_in[4] = " and service_code between 6310 and 6319 ";
   $service_code_in[5] = " and service_code between 6410 and 6419 ";
   $service_code_in[6] = " and service_code between 7200 and 7277 ";
   $service_code_in[7] = " and service_code between 7300 and 7399 ";
   $service_code_in[8] = " and service_code like '67__' ";

   $i = 0;
   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);
   while ($sTime <= $eTime){
   	$vDate = date('m/d/Y', $sTime);
//echo $vDate."\r\n";
	//truckloading
	//bni 
   	$sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
           	select date_of_activity, '$type[0]', t.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
		decode(t.commodity_code, '4963', (sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received,                 m.qty_expected))*3/2), (sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received,                           m.qty_expected))/2000))
           	from cargo_tracking t, cargo_manifest m, cargo_activity a
           	where a.lot_num = m.container_num and a.lot_num = t.lot_num and  a.service_code = 6200 and
           	date_of_activity = to_date('$vDate','mm/dd/yyyy') and t.commodity_code <> '1272' and qty_received > 0
           	group by date_of_activity, t.commodity_code, m.qty1_unit";
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_of_activity, '$type[0]', m.commodity_code, null, null, sum(a.qty_change), null,
                decode(m.commodity_code, '4963', (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                     m.qty_expected))*3/2), (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                               m.qty_expected))/2000))
                from cargo_manifest m, cargo_activity a
                where a.lot_num = m.container_num and  a.service_code = 6200 and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code <> '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code";
   	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);

	//rf
        $sql = "select decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101) as commodity_code, 
		sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight  
		from (select * from cargo_tracking union all select * from arch_2003.cargo_tracking) t, 
		(select * from cargo_activity union all select * from arch_2003.cargo_activity) a, 
		commodity_profile c, commodity_weight w 
		where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and t.commodity_code = c.commodity_code 
		and  a.pallet_id = t.pallet_id and a.service_code = 6 and t.commodity_code = w.commodity_code and  
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
		group by  decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101)";


        $sql = "select decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101') as commodity_code,
                sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight
                from (select * from arch_2002.cargo_tracking union all select * from arch_2003.cargo_tracking) t,
                (select * from arch_2002.cargo_activity_arch union all select * from arch_2003.cargo_activity) a,commodity_weight w
                where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and 
		a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and 
		t.commodity_code = w.commodity_code and
                date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
                group by  decode(substr(t.arrival_num, 0, 3), '103', '5409','100', '5411', '5101')";
	if (strtotime($vDate) < strtotime('12/01/2002')){
		$cargo_tracking = "arch_2002.cargo_tracking";
		$cargo_activity = "arch_2002.cargo_activity_arch";
	}else if (strtotime($vDate) >= strtotime('12/01/2002') && strtotime($vDate) < strtotime('12/01/2003') ){
		$cargo_tracking = "arch_2003.cargo_tracking";
                $cargo_activity = "arch_2003.cargo_activity";
	}else {
     		$cargo_tracking = "cargo_tracking";
                $cargo_activity = "cargo_activity";
	}
        $sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101'),
                sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight
                from $cargo_tracking t, $cargo_activity a,commodity_weight w
                where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and
                a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and
                t.commodity_code = w.commodity_code and
                date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
                group by  decode(substr(t.arrival_num, 0, 3), '103','5409','102','5411','100','5411','200','5101','5101')";


	$statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
		$comm = ora_getcolumn($cursor_rf, 0);
                $qty = ora_getcolumn($cursor_rf, 1);
                $weight = ora_getcolumn($cursor_rf, 2);
		$sql = "insert into $productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
			values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', null, null, $qty, null, $weight)";
		$statement = ora_parse($cursor_bni, $sql);
	        ora_exec($cursor_bni);  
  	}

	//Holmen Paper
	$sql = "select count(*), round(sum(gross_weight)/0.454/2000)
		from cargo_activity a, order_detail o 
		where a.order_num = o.order_num and service_code = 2 and transport_mode = 'Road' and 
		activity_date >= to_date('$vDate', 'mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and 
		(activity_status is null or (activity_status <> 'VOID' and activity_status <> 'CANCELLED'))";
        $statement = ora_parse($cursor_paper, $sql);
        ora_exec($cursor_paper);
        while (ora_fetch($cursor_paper)){
                $comm = "4374";
                $qty = ora_getcolumn($cursor_paper, 0);
                $weight = ora_getcolumn($cursor_paper, 1);
		if ($qty > 0){
                $sql = "insert into $productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', null, null, $qty, null, $weight)";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
		}
        }

        //ccds
	$sql = "select subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), 
		sum(a.pallets), round(sum(a.gross_weight)/2000), r.ship_type 
		from ccd_activity a, ccd_received r, ccd_inventory i, ccd_product p 
		where a.transaction_type ='SHIPPED' and a.ccd_lot_id = r.ccd_lot_id and i.ccd_lot_id = a.ccd_lot_id and 
		a.from_customer_id = i.customer_id and i.product = p.product and execution_date ='$vDate' 
		group by subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), r.ship_type"; 
  	$result = pg_query($conn_ccd, $sql) or die("Error in query: $sql. " . pg_last_error($conn_ccd));
  	$rows = pg_num_rows($result);
  	for($i = 0; $i < $rows; $i++){
		$row = pg_fetch_row($result, $i);
                $comm = $row[0];
                $qty = $row[1];
                $weight = $row[2];
                $sql = "insert into $productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', null, null, $qty, null, $weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
	
	//Backhaul
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select to_date('$vDate','mm/dd/yyyy'), '$type[1]', commodity_code, lr_num, null, sum(qty_expected), 
                null, sum(cargo_weight)/2000
                from cargo_manifest 
                where lr_num > 8000 and commodity_code <>'1272' and lr_num in 
                (select lr_num from voyage where to_char(date_departed, 'mm/dd/yyyy') =  '$vDate') 
                group by lr_num, commodity_code";
 
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);	

        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_received, '$type[1]', m.commodity_code, lr_num, null, sum(qty_expected), null,
                decode(m.commodity_code, '4963', sum(m.cargo_weight)*3/2, sum(m.cargo_weight)/2000)
                from cargo_tracking t, cargo_manifest m
                where lr_num < 10 and  t.lot_num = m.container_num and to_char(date_received, 'mm/dd/yyyy') ='$vDate' and
                t.commodity_code <>'1272' 
                group by date_received, lr_num, m.commodity_code"; 
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
/*
	//bni
	$sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_received, '$type[1]', t.commodity_code, lr_num, null, sum(qty_received), m.qty1_unit,
                decode(t.commodity_code, '4963', (sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received, 		      m.qty_expected))*3/2), (sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received, 			    m.qty_expected))/2000))
		from cargo_tracking t, cargo_manifest m
		where t.lot_num = m.container_num and date_received =to_date('$vDate','mm/dd/yyyy') and 
		t.commodity_code <>'1272' and qty_received > 0 and cargo_mark not like 'TR*%'
		group by date_received, lr_num, t.commodity_code, m.qty1_unit";
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select to_date('$vDate','mm/dd/yyyy'), '$type[1]', commodity_code, lr_num, null, sum(qty_expected), 
		qty1_unit, sum(cargo_weight)/2000
                from cargo_manifest 
                where lr_num > 8000 and commodity_code <>'1272' and (lr_num, commodity_code) in 
		(select lr_num, m.commodity_code from cargo_manifest m , cargo_tracking t 
		where m.container_num = t.lot_num and t.qty_received > 0 
		group by lr_num, m.commodity_code having min(date_received)  =  to_date('$vDate','mm/dd/yyyy')) 
                group by lr_num, commodity_code, qty1_unit";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_received, '$type[1]', m.commodity_code, lr_num, null, sum(qty_expected), m.qty1_unit,
                decode(m.commodity_code, '4963', sum(m.cargo_weight)*3/2, sum(m.cargo_weight)/2000)
                from cargo_tracking t, cargo_manifest m
                where lr_num < 10 and  t.lot_num = m.container_num and date_received =to_date('$vDate','mm/dd/yyyy') and
                t.commodity_code <>'1272' 
                group by date_received, lr_num, m.commodity_code, m.qty1_unit";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	//rf
	$sql = "select t.arrival_num, t.shipping_line, decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101), 
		sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000) 
		from cargo_tracking t, cargo_activity a, commodity_profile c, commodity_weight w
		where t.qty_received > 0 and t.commodity_code = c.commodity_code and  a.pallet_id = t.pallet_id and 
		a.service_code = 1  and  date_of_activity >=to_date('$vDate','mm/dd/yyyy') and 
		date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'S' and
		t.commodity_code = w.commodity_code 
		group by  t.arrival_num, t.shipping_line, decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101)";
        $sql = "select t.arrival_num, t.shipping_line, decode(substr(t.arrival_num, 0, 3), '103', '5409','100', '5411', '5101'),
                sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000)
                from (select * from cargo_tracking union all select * from arch_2003.cargo_tracking) t, 
		(select * from cargo_activity union all select * from arch_2003.cargo_activity) a, commodity_weight w
                where t.qty_received > 0 and  a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and 
                a.customer_id = t.receiver_id and a.service_code = 1  and  date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'S' and
                t.commodity_code = w.commodity_code
                group by  t.arrival_num, t.shipping_line, decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101')";


        $statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
		$lr_num = ora_getcolumn($cursor_rf, 0);
                $sLine = ora_getcolumn($cursor_rf, 1);
                $comm = ora_getcolumn($cursor_rf, 2);
                $qty = ora_getcolumn($cursor_rf, 3);
                $weight = ora_getcolumn($cursor_rf, 4);
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[1]','$comm', '$lr_num', '$sLine', $qty,'PLT',$weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
 
	//Holmen Paper
	$sql = "select  pow_arrival_num, count(*) as qty, round(sum(gross_weight)/0.454/2000)
		from cargo_tracking t
		where date_received is not null and  
		date_received >=to_date('$vDate','mm/dd/yyyy') and date_received <to_date('$vDate','mm/dd/yyyy')+1  
		group by pow_arrival_num";
        $statement = ora_parse($cursor_paper, $sql);
        ora_exec($cursor_paper);
        while (ora_fetch($cursor_paper)){
                $comm = "4374";
                $lr_num = ora_getcolumn($cursor_paper, 0);
                $qty = ora_getcolumn($cursor_paper, 1);
                $weight = ora_getcolumn($cursor_paper, 2);
               // if ($qty > 0){
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[1]','$comm', '$lr_num', null, $qty, 'ROLL', $weight)";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
               // }
        }

	//ccds
	$sql = "select r.lr_num, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), sum(a.pallets), 
		round(sum(a.gross_weight)/2000), r.ship_type  
		from ccd_activity a, ccd_received r, ccd_inventory i, ccd_product p  
		where a.transaction_type ='RECEIVED' and a.ccd_lot_id = r.ccd_lot_id and a.ccd_lot_id = i.ccd_lot_id and 
		a.from_customer_id = i.customer_id and execution_date ='$vDate' and i.product = p.product 
		group by r.lr_num, subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), r.ship_type";
        $result = pg_query($conn_ccd, $sql) or die("Error in query: $sql. " . pg_last_error($conn_ccd));
        $rows = pg_num_rows($result);
        for($i = 0; $i < $rows; $i++){
                $row = pg_fetch_row($result, $i);
                $lr_num = $row[0];
		$comm = $row[1];
                $qty = $row[2];
                $weight = $row[3];
		$sLine = $row[4];
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[1]','$comm','$lr_num','$sLine', $qty, 'PLT', $weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
*/

	//Rail Car Handling
        //Holmen Paper
        $sql = "select count(*), round(sum(gross_weight)/0.454/2000)
                from cargo_activity a, order_detail o
                where a.order_num = o.order_num and service_code = 2 and transport_mode = 'Rail' and
                activity_date >= to_date('$vDate', 'mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and
                (activity_status is null or (activity_status <> 'VOID' and activity_status <> 'CANCELLED'))";
        $statement = ora_parse($cursor_paper, $sql);
        ora_exec($cursor_paper);
        while (ora_fetch($cursor_paper)){
                $comm = "4374";
                $qty = ora_getcolumn($cursor_paper, 0);
                $weight = ora_getcolumn($cursor_paper, 1);
                if ($qty > 0){
                $sql = "insert into $productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[4]','$comm', null, null, $qty, 'null', $weight)";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
                }
        }

        //bni dole paper
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_received, '$type[4]', t.commodity_code, lr_num, null, sum(qty_received), null,
                round(sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received, m.qty_expected))/2000)
                from cargo_tracking t, cargo_manifest m
                where t.lot_num = m.container_num and date_received =to_date('$vDate','mm/dd/yyyy') and
                t.commodity_code ='1272' and qty_received > 0 and cargo_mark not like 'TR*%'
                group by date_received, lr_num, t.commodity_code";
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select to_date('$vDate','mm/dd/yyyy'), '$type[1]', commodity_code, lr_num, null, sum(qty_expected),
                null, sum(cargo_weight)/2000
                from cargo_manifest
                where lr_num > 8000 and commodity_code ='1272' and (lr_num, commodity_code) in
                (select lr_num, m.commodity_code from cargo_manifest m , cargo_tracking t 
		where m.container_num = t.lot_num and qty_received > 0
                group by lr_num, m.commodity_code having min(date_received)  =  to_date('$vDate','mm/dd/yyyy'))
                group by lr_num, commodity_code";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);


	//Container Handling
        //bni dole paper
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_of_activity, '$type[5]', t.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
                round(sum(m.cargo_weight * a.qty_change / m.qty_expected)/2000)
                from cargo_tracking t, cargo_manifest m, cargo_activity a
                where a.lot_num = m.container_num and a.lot_num = t.lot_num and  a.service_code = 6200 and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and t.commodity_code = '1272'
                group by date_of_activity, t.commodity_code, m.qty1_unit";
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_of_activity, '$type[5]', m.commodity_code, null, null, sum(a.qty_change), null,
                sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change, m.qty_expected))/2000
                from cargo_manifest m, cargo_activity a
                where a.lot_num = m.container_num and  a.service_code = 6200 and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code = '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        //rf
        $sql = "select decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101),
                sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000)
                from cargo_tracking t, cargo_activity a, commodity_profile c, commodity_weight w
                where t.qty_received > 0 and t.commodity_code = c.commodity_code and  a.pallet_id = t.pallet_id and
                a.service_code = 1  and  date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'T'
		and t.commodity_code = w.commodity_code 
                group by  decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101)";
        $sql = "select decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101'),
                sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000)
                from (select * from arch_2002.cargo_tracking union all select * from arch_2003.cargo_tracking) t,
                (select * from arch_2002.cargo_activity_arch union all select * from arch_2003.cargo_activity)  a, commodity_weight w
                where t.qty_received > 0  and  a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and 
		a.customer_id = t.receiver_id and a.service_code = 1  and  date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'T'
                and t.commodity_code = w.commodity_code
                group by  decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101')";

        if (strtotime($vDate) < strtotime('12/01/2002')){
                $cargo_tracking = "arch_2002.cargo_tracking";
                $cargo_activity = "arch_2002.cargo_activity_arch";
        }else if (strtotime($vDate) >= strtotime('12/01/2002') && strtotime($vDate) < strtotime('12/01/2003') ){
                $cargo_tracking = "arch_2003.cargo_tracking";
                $cargo_activity = "arch_2003.cargo_activity";
        }else {
                $cargo_tracking = "cargo_tracking";
                $cargo_activity = "cargo_activity";
        }
        $sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101'),
                sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000)
                from $cargo_tracking t, $cargo_activity a, commodity_weight w
                where t.qty_received > 0  and  a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and
                a.customer_id = t.receiver_id and a.service_code = 1  and  
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'T'
                and t.commodity_code = w.commodity_code
                group by  decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101')";

        $statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
                $comm = ora_getcolumn($cursor_rf, 0);
                $qty = ora_getcolumn($cursor_rf, 1);
                $weight = ora_getcolumn($cursor_rf, 2);

                $sql = "insert into $productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[5]','$comm', null, null, $qty,null,$weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }


	//Get Hours
	for ($i = 0; $i < 9; $i++){
		$sql = "select commodity_code, sum(duration) from hourly_detail 
			where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and 
			employee_id not in (select user_id from lcs_user)
			group by commodity_code";	

		$statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
		while(ora_fetch($cursor_lcs)){

			$comm = ora_getcolumn($cursor_lcs, 0);
			$hours = ora_getcolumn($cursor_lcs, 1);

/*
			//get supervisor
			$sql = "select user_name, sum(duration) from hourly_detail h, lcs_user u
                        	where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                        	employee_id not in (select user_id from lcs_user) and commodity_code = '$comm'
				and h.user_id = u.user_id
                        	group by user_name order by sum(duration) desc";
			$statement = ora_parse($cursor_lcs2, $sql);
                	ora_exec($cursor_lcs2);
			$j = 0;
			$sup = array();
                	while(ora_fetch($cursor_lcs2)){
				$sup[$j] = ora_getcolumn($cursor_lcs2, 0);
				$j++; 	
			}
			//get number of checker, laborer and operator
			$sql = "select substr(service_code, 4, 1), count(distinct employee_id)
				from hourly_detail 
				where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                                employee_id not in (select user_id from lcs_user) and commodity_code = '$comm'
                                group by substr(service_code, 4, 1)";

                        $statement = ora_parse($cursor_lcs2, $sql);
                        ora_exec($cursor_lcs2);
                        $opt = 0;
			$lab = 0;
			$chk = 0;
                        while(ora_fetch($cursor_lcs2)){
				$Type = ora_getcolumn($cursor_lcs2, 0);
				$cnt = ora_getcolumn($cursor_lcs2, 1);
				
				if ($Type =="1") $opt = $cnt;
				if ($Type =="4") $lab = $cnt;
				if ($Type =="9") $chk = $cnt;
			}
*/			
			$sql = "select count(*) from $productivity 
				where date_time = to_date('$vDate','mm/dd/yyyy') and 
				service_type = '$type[$i]' and commodity = '$comm'";
			$statement = ora_parse($cursor_bni, $sql);
                        ora_exec($cursor_bni);
			$pRow = 0;
			if (ora_fetch($cursor_bni)){
				$pRow = ora_getcolumn($cursor_bni, 0);
			}	
			if ($pRow ==0){
				$sql = "insert into $productivity (date_time, service_type,commodity, hours) values 
					(to_date('$vDate','mm/dd/yyyy'), '$type[$i]','$comm', $hours )";
                		$statement = ora_parse($cursor_bni, $sql);
                		ora_exec($cursor_bni);
			}else if ($pRow == 1){
				$sql = "update $productivity set 
					hours = $hours
					where date_time = to_date('$vDate','mm/dd/yyyy') and
					service_type = '$type[$i]' and commodity = '$comm'";
				$statement = ora_parse($cursor_bni, $sql);
                                ora_exec($cursor_bni);
			}else{
				$sql = "select sum(tonnage) from $productivity
                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
					service_type = '$type[$i]' and commodity = '$comm'";
                                $statement = ora_parse($cursor_bni2, $sql);
                                ora_exec($cursor_bni2);
                                if (ora_fetch($cursor_bni2)){
					$tWeight = ora_getcolumn($cursor_bni2, 0);
				}else{
					$tWeight = 0;
				}
				$sql = "select unit, tonnage from $productivity
	                                where date_time = to_date('$vDate','mm/dd/yyyy') and
					service_type = '$type[$i]' and commodity = '$comm'";
                                $statement = ora_parse($cursor_bni2, $sql);
                                ora_exec($cursor_bni2);
				while (ora_fetch($cursor_bni2)){
					$unit = ora_getcolumn($cursor_bni2, 0);
					$uWeight = ora_getcolumn($cursor_bni2, 1);
					
					if ($tWeight > 0){
						$uHours = ($uWeight/$tWeight)* $hours;
					}else{
						$uHours = 0;
					}
					$sql = "update $productivity set
                                        	hours = $uHours
	                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
						service_type='$type[$i]' and commodity='$comm' and unit='$unit'";
        	        	     	$statement = ora_parse($cursor_bni, $sql);
                	       		ora_exec($cursor_bni);
				}
			}
		}
		
		//get hire_plan

		$sql = "select commodity, sum(num_of_hire), sum(tot_hours), sum(plts) 
			from hire_plan
			where hire_date = to_date('$vDate','mm/dd/yyyy') and type='$type[$i]'
			group by commodity";
 		$statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
		while (ora_fetch($cursor_lcs)){
			$comm = ora_getcolumn($cursor_lcs, 0);
			$hire = ora_getcolumn($cursor_lcs, 1);
			$hours = ora_getcolumn($cursor_lcs, 2);
			$plts = ora_getcolumn($cursor_lcs, 3);
			
			$sql = "select count(*) from $productivity
				where date_time = to_date('$vDate','mm/dd/yyyy') and
                        	service_type='$type[$i]' and commodity='$comm'";
			$statement = ora_parse($cursor_bni, $sql);
                      	ora_exec($cursor_bni);
			$pRow = 0;
                        if (ora_fetch($cursor_bni)){
                                $pRow = ora_getcolumn($cursor_bni, 0);
                        }
			if ($pRow ==0){
				$sql = "insert into $productivity (date_time, service_type,commodity, plan_hire, plan_hours, 
					plan_plts) values
                           		(to_date('$vDate','mm/dd/yyyy'), '$type[$i]','$comm', $hire, $hours, $plts )";

			}else{
				$sql = "update $productivity set
					plan_hire = $hire,
                                       	plan_hours = $hours,
					plan_plts = $plts
                                      	where date_time = to_date('$vDate','mm/dd/yyyy') and
                                   	service_type='$type[$i]' and commodity='$comm'";
			}
	            	$statement = ora_parse($cursor_bni, $sql);
                       	ora_exec($cursor_bni);
		}
	}
	//next day
	$sTime += 24*60*60; 
   }
  
   //Get commodity_name
  $sql = "update $productivity set commodity_name = (select commodity_name from commodity_profile where commodity_code = commodity) where commodity_name is null and date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);
	
  //Get budget
  $sql = "update $productivity set budget = (select budget from budget where type = service_type and commodity like commodity_code) where date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);

  $sql = "update $productivity set budget = 9999 where budget is null";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);


  //calculate productivity
  $sql = "update $productivity set productivity = tonnage / hours where hours > 0 and date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);


   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'plan'=>'<b>Plan</b>', 'actual'=>'<b>Actual</b>', 'aProd'=>'','budget'=>'','aProd_budget'=>'<b>Productivity</b>', 'flag'=>'');
   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'plan'=>'Plan', 'actual'=>'Actual', 'aProd'=>'','budget'=>'','aProd_budget'=>'Productivity', 'flag'=>'');
   $arrHeading1 = array('service'=>'<b>Service</b>', 'comm'=>'<b>Commodiity</b>','plan'=>'Plan', 'actual'=>'Actual','aProd_budget'=>'Productivity(T/H)');

   $arrHeading = array('sup'=>'<b>Supervisor</b>', 'comm'=>'<b>Comm</b>', 'wing'=>'<b>Location</b>', 'num'=>'<b>Hire</b>', 'hours'=>'<b>Hours</b>', 'plt'=>'<b>PLT</b>', 'lab'=>'<b>Labor</b>','aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>PLT</b>', 'aProd'=>'<b>Productivity</b>', 'budget'=>'<b>Budget</b>','aProd_budget'=>'<b>- Budget</b>', 'flag'=>'Summary');
   $arrHeading = array('service'=>'', 'comm'=>'', 'hours'=>'<b>Hours</b>', 'plt'=>'<b>Plts</b>', 'aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>Plts</b>', 'tons'=>'<b>Tons</b>','budget'=>'<b>Budget</b>','aProd'=>'<b>Actual</b>');

   $arrCol1 = array('service'=>array('width'=>150, 'justification'=>'left'),
                   'comm'=>array('width'=>200, 'justification'=>'center'),
                   'plan'=>array('width'=>90, 'justification'=>'center'),
                   'actual'=>array('width'=>135, 'justification'=>'center'),
                   'aProd_budget'=>array('width'=>130,'justification'=>'center'));

   $arrCol = array('service'=>array('width'=>150, 'justification'=>'left'),
                   'comm'=>array('width'=>200, 'justification'=>'left'),
                //   'num'=>array('width'=>45, 'justification'=>'center'),
                   'hours'=>array('width'=>45, 'justification'=>'center'),
                   'plt'=>array('width'=>45, 'justification'=>'center'),
                //   'lab'=>array('width'=>45, 'justification'=>'center'),
                   'aHours'=>array('width'=>45, 'justification'=>'center'),
                   'aPlt'=>array('width'=>45, 'justification'=>'center'),
                   'tons'=>array('width'=>45, 'justification'=>'center'),
		   'aProd'=>array('width'=>70, 'justification'=>'center'),
                   'budget'=>array('width'=>60, 'justification'=>'center') );

   $heading1 = array();
   $heading = array();
   array_push($heading1, $arrHeading1);
   array_push($heading, $arrHeading);

   $data = array();

   $date_display = date('l m/d/y', strtotime($vDate));

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','landscape');

   $pdf->ezSetMargins(40,40,50,40);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $format = "Printed On: " . date('m/d/y g:i A');

   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->addText(650, 580,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Productivity Summary</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i>For $date_display</i></b>", 18, $center);
   $pdf->ezSetDy(-5);


   $sql = "select service_type, commodity_name, plan_hire, plan_hours, plan_plts, hours, qty, tonnage, budget, productivity
	   from $productivity
	   where date_time = to_date('$vDate','mm/dd/yyyy')
	   order by service_type, commodity";  
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
   $pre_service = "";
   while(ora_fetch($cursor_bni)){
	$service = ora_getcolumn($cursor_bni, 0);
        $comm = ora_getcolumn($cursor_bni, 1);
        $pHire = ora_getcolumn($cursor_bni, 2);
        $pHours = ora_getcolumn($cursor_bni, 3);
        $pPlts = ora_getcolumn($cursor_bni, 4);
        $aHours = ora_getcolumn($cursor_bni, 5);
        $aPlt = ora_getcolumn($cursor_bni, 6);
        $tons = ora_getcolumn($cursor_bni, 7);
        $budget = ora_getcolumn($cursor_bni, 8);
        $prods = ora_getcolumn($cursor_bni, 9);
        
	if($budget ==9999){
		$budget = "No Budget";
	}else{
		$budget = number_format($budget, 2, '.',',');
	}

	if ($pre_service <> $service){
		$pre_service = $service;
		$dis_service = $service;
	}else{
		$dis_service = "";
	}

	$pos = strpos($comm, '-');
	if ($pos > 0)
		$comm = substr($comm, $pos + 1);

        if ($aHours ==0){
		$prods = "-";
	}else if ($aPlt ==0){
		$prods = "0";
	}else{
		$prods = number_format($prods, 2, '.',',');
	}

        $tot_pHours +=$pHours;
	$tot_pPlts += $pPlts;
	$tot_aHours += $aHours;
	$tot_aPlts  += $aPlt;
	$tot_tons += $tons;


	array_push($data, array('service'=>$dis_service,
				'comm'=>$comm,
				'num'=>number_format($pHire,0, '.',','),
				'hours'=>number_format($pHours, 0, '.',','),
				'plt'=>number_format($pPlts, 0,'.',','),
				'lab'=>number_format($aHours/8, 1,'.',','),
				'aHours'=>number_format($aHours, 0, '.',','),
				'aPlt'=>number_format($aPlt, 0, '.',','),
				'tons'=>number_format($tons, 0, '.',','),
				'budget'=>$budget,
				'aProd'=>$prods));
	
   }
	$tot_pHours = number_format($tot_pHours, 0, '.',',');
	$tot_pPlts = number_format($tot_pPlts, 0,'.',',');
	$tot_aHours = number_format($tot_aHours, 0, '.',',');
	$tot_aPlts = number_format($tot_aPlts, 0, '.',',');
	$tot_tons = number_format($tot_tons, 0, '.',',');

        array_push($data, array('service'=>'<b>Total</b>',
                                'comm'=>'',
                                'num'=>'',
                                'hours'=>'<b>'.$tot_pHours.'</b>',
                                'plt'=>'<b>'.$tot_pPlts.'</b>',
                                'lab'=>'',
                                'aHours'=>'<b>'.$tot_aHours.'</b>',
                                'aPlt'=>'<b>'.$tot_aPlts.'</b>',
                                'tons'=>'<b>'.$tot_tons.'</b>',
                                'budget'=>'',
                                'aProd'=>''));


   $pdf->ezSetDy(-15);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
	
//   $pdf->ezStream();

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "rwang@port.state.de.us";

        $mailTo  = "gbailey@port.state.de.us,";
        $mailTo .= "ffitzgerald@port.state.de.us,";
        $mailTo .= "ithomas@port.state.de.us,";
        $mailTo .= "parul@port.state.de.us";

        $mailsubject = "Productivity Summary";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";
        $mailheaders .= "Cc: wstans@port.state.de.us,jjaffe@port.state.de.us,ddonofrio@port.state.de.us\r\n";

        $mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
        $mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        $mailheaders .= "X-Mailer: PHP4\r\n";
        $mailheaders .= "X-Priority: 3\r\n";
        $mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
        $mailheaders  .= "This is a multi-part Contentin MIME format.\r\n";

        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
//        $Content.="http://dspc-s16/lcs/productivity/index2.php?vDate=".$vDate."\r\n";
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: application/pdf; name=\"Productivity Summary.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$File;
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n";

//        mail($mailTo1, $mailsubject, $Content, $mailheaders);
      	mail($mailTo, $mailsubject, $Content, $mailheaders);


?>
