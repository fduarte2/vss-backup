<?

//   $sDate = "07/01/2004";
//   $eDate = "08/04/2004";
//   $eDate = "07/01/2003";

   $days = $HTTP_SERVER_VARS["argv"][1]; 
echo $days;
   if ($days ==""){
   	$wday = date('D');
   	if ($wday =="Mon"){
      		$sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 3 ,date("Y")));
   	}else{
		$sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
   	}
   }else{
	$sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - $days ,date("Y")));
   } 
   $eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d")  ,date("Y"))); 
$sDate = "12/20/2004";
echo $sDate."\r\n";

echo $eDate."\r\n";

//   $sDate = "07/01/2004";
//   $eDate = "07/08/2004";   
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

   $type[0] = "TRUCKLOADING";
   $type[1] = "BACKHAUL";
   $type[2] = "TERMINAL SERVICE";
   $type[3] = "RAIL CAR HANDLING";
   $type[4] = "CONTAINER HANDLING";
   $type[5] = "INSPECTION";
   $type[6] = "MAINTENANCE";
   $type[7] = "NON REVENUE";
   $type[8] = "STAND BY";

   $service_code_in[0] = " and service_code between 6220 and 6229 ";
   $service_code_in[1] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6149) ";
   $service_code_in[2] = " and service_code between 6530 and 6619 ";
   $service_code_in[3] = " and service_code between 6310 and 6319 ";
   $service_code_in[4] = " and service_code between 6410 and 6419 ";
   $service_code_in[5] = " and service_code between 6520 and 6529 ";
   $service_code_in[6] = " and service_code between 7200 and 7277 ";
   $service_code_in[7] = " and service_code between 7300 and 7399 ";
   $service_code_in[8] = " and service_code like '67__' ";

   $i = 0;
   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);

   $productivity = "daily_productivity";

   //clear productivity table
   $sql = "delete from $productivity where date_time >= to_date('$sDate','mm/dd/yyyy') and date_time <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $vTime = strtotime($sDate);
   $eTime = strtotime($eDate);
   $d = 0;
   while ($vTime < $eTime){
        $vDate = date('m/d/Y',mktime(0,0,0,date("m",strtotime($sDate)),date("d",strtotime($sDate)) + $d ,date("Y",strtotime($sDate))));
        $d++;
        $vTime = strtotime($vDate);
echo $vDate."\r\n";
	//truckloading
	//bni 
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage, 
		location)
                select date_of_activity, '$type[0]', m.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
                decode(m.commodity_code, '4963', (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                    m.qty_expected))*3/2), 
		(sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change, m.qty_expected))/2000)), 
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)
                from cargo_manifest m, cargo_activity a
                where a.lot_num = m.container_num and  a.service_code = 6200 and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code <> '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code, m.qty1_unit, 
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)";
   	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);

	//rf
	if (strtotime($vDate) < strtotime('12/01/2002')){
		$cargo_tracking = "arch_2002.cargo_tracking";
		$cargo_activity = "arch_2002.cargo_activity_arch";
	}else if (strtotime($vDate) >= strtotime('12/01/2002') && strtotime($vDate) < strtotime('12/01/2003') ){
		$cargo_tracking = "arch_2003.cargo_tracking";
                $cargo_activity = "arch_2003.cargo_activity";
        }else if (strtotime($vDate) >= strtotime('12/01/2003') && strtotime($vDate) < strtotime('12/01/2004') ){
                $cargo_tracking = "arch_2004.cargo_tracking";
                $cargo_activity = "arch_2004.cargo_activity";
	}else {
     		$cargo_tracking = "cargo_tracking";
                $cargo_activity = "cargo_activity";
	}
        $sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101'),
                sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight,
		decode(substr(t.warehouse_location, 1,1), 'A','A','C','C','D','D','E','E','F','F','G','G','N/A') as location
                from $cargo_tracking t, $cargo_activity a,commodity_weight w
                where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and
                a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and
                date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
                group by  decode(substr(t.arrival_num, 0, 3), '103','5409','102','5411','100','5411','200','5101','5101'),
		decode(substr(t.warehouse_location, 1,1), 'A','A','C','C','D','D','E','E','F','F','G','G','N/A') ";

	$statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
		$comm = ora_getcolumn($cursor_rf, 0);
                $qty = ora_getcolumn($cursor_rf, 1);
                $weight = ora_getcolumn($cursor_rf, 2);
		$location = ora_getcolumn($cursor_rf, 3);
		$sql = "insert into $productivity (date_time, service_type,commodity, qty,unit,tonnage, location)
			values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', $qty, 'PLT', $weight, '$location')";
		$statement = ora_parse($cursor_bni, $sql);
	        ora_exec($cursor_bni);  
  	}

	//Holmen Paper
	$sql = "select count(*), round(sum(gross_weight)/0.454/2000), 
		decode(substr(warehouse_location, 1,4), 'WING', substr(warehouse_location, 6,1), warehouse_location)
		from cargo_activity a, order_detail o 
		where a.order_num = o.order_num and service_code = 2 and transport_mode = 'Road' and 
		activity_date >= to_date('$vDate', 'mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and 
		(activity_status is null or (activity_status <> 'VOID' and activity_status <> 'CANCELLED'))
		group by decode(substr(warehouse_location, 1,4),'WING', substr(warehouse_location, 6,1),warehouse_location)";
        $statement = ora_parse($cursor_paper, $sql);
        ora_exec($cursor_paper);
        while (ora_fetch($cursor_paper)){
                $comm = "4374";
                $qty = ora_getcolumn($cursor_paper, 0);
                $weight = ora_getcolumn($cursor_paper, 1);
		$location = ora_getcolumn($cursor_paper, 2);
		if ($qty > 0){
                $sql = "insert into $productivity (date_time, service_type,commodity, qty,unit,tonnage, location)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', $qty, 'ROLL', $weight, '$location')";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
		}
        }

        //ccds
	$sql = "select subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), 
		sum(a.pallets), round(sum(a.gross_weight)/2000), substr(r.location, 1,1)
		from ccd_activity a, ccd_received r, ccd_product p 
		where a.transaction_type ='SHIPPED' and a.ccd_lot_id = r.ccd_lot_id and 
		r.product = p.product and execution_date ='$vDate' 
		group by subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), substr(r.location, 1,1)"; 
  	$result = pg_query($conn_ccd, $sql) or die("Error in query: $sql. " . pg_last_error($conn_ccd));
  	$rows = pg_num_rows($result);
  	for($i = 0; $i < $rows; $i++){
		$row = pg_fetch_row($result, $i);
                $comm = $row[0];
                $qty = $row[1];
                $weight = $row[2];
		$location = $row[3];
                $sql = "insert into $productivity (date_time, service_type,commodity, qty,unit,tonnage, location)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', $qty, 'PLT', $weight, '$location')";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
	
	//Backhaul
        $sql = "insert into $productivity (date_time, service_type,commodity,vessel,shipping_line,qty,unit,tonnage,location)
		select to_date('$vDate','mm/dd/yyyy'),type,commodity_code,lr_num,null,sum(qty),qty1_unit,sum(weight),location
		from (
                select '$type[1]' as type, commodity_code, lr_num, null, 
		sum(qty_expected) as qty, qty1_unit, 
                decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000,
                'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)) as weight,
		decode(substr(cargo_location, 1,4), 'WING', substr(cargo_location, 6,1), cargo_location) as location
                from cargo_manifest 
                where lr_num > 1000 and commodity_code <>'1272' and lr_num in 
                (select lr_num from voyage where to_char(date_departed, 'mm/dd/yyyy') =  '$vDate') 
                group by lr_num, commodity_code, qty1_unit, upper(cargo_weight_unit),
		decode(substr(cargo_location, 1,4), 'WING', substr(cargo_location, 6,1), cargo_location)) a
		group by type, commodity_code, lr_num, qty1_unit, location";
 
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);	

        $sql = "insert into $productivity (date_time,service_type,commodity,vessel,shipping_line,qty,unit,tonnage,location)
		select date_received, type, commodity_code, lr_num, null, sum(qty), qty1_unit, sum(weight), location
		 from (
                select date_received,'$type[1]' as type, m.commodity_code, lr_num, null,sum(qty_expected) as qty,m.qty1_unit,
                decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000,
                'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)) as weight,
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location) as location
                from cargo_tracking t, cargo_manifest m
                where lr_num < 10 and  t.lot_num = m.container_num and to_char(date_received, 'mm/dd/yyyy') ='$vDate' and
                t.commodity_code <>'1272' 
                group by date_received, lr_num, m.commodity_code, m.qty1_unit, upper(cargo_weight_unit),
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)) a
		group by date_received, type, commodity_code, lr_num, qty1_unit, location"; 
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	//Rail Car Handling
        //Holmen Paper
        $sql = "select count(*), round(sum(gross_weight)/0.454/2000),
		decode(substr(warehouse_location, 1,4), 'WING', substr(warehouse_location, 6,1), warehouse_location)
                from cargo_activity a, order_detail o
                where a.order_num = o.order_num and service_code = 2 and transport_mode = 'Rail' and
                activity_date >= to_date('$vDate', 'mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and
                (activity_status is null or (activity_status <> 'VOID' and activity_status <> 'CANCELLED'))
		group by decode(substr(warehouse_location, 1,4),'WING', substr(warehouse_location, 6,1),warehouse_location)";
        $statement = ora_parse($cursor_paper, $sql);
        ora_exec($cursor_paper);
        while (ora_fetch($cursor_paper)){
                $comm = "4374";
                $qty = ora_getcolumn($cursor_paper, 0);
                $weight = ora_getcolumn($cursor_paper, 1);
		$location = ora_getcolumn($cursor_paper, 2);
                if ($qty > 0){
                $sql = "insert into $productivity (date_time, service_type,commodity, qty,unit,tonnage, location)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[3]','$comm', $qty, 'ROLL', $weight, '$location')";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
                }
        }

        //bni dole paper
        $sql = "insert into $productivity (date_time,service_type,commodity,vessel,shipping_line,qty,unit,tonnage,location)
                select date_received, '$type[3]', t.commodity_code, lr_num, null, sum(qty_received), m.qty1_unit,
                round(sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received, m.qty_expected))/2000),
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)
                from cargo_tracking t, cargo_manifest m
                where t.lot_num = m.container_num and date_received =to_date('$vDate','mm/dd/yyyy') and
                t.commodity_code ='1272' and qty_received > 0 
                group by date_received, lr_num, t.commodity_code, m.qty1_unit,
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);


	//Container Handling
        //bni dole paper
        $sql = "insert into $productivity (date_time,service_type,commodity,vessel,shipping_line,qty,unit,tonnage,location)
                select date_of_activity, '$type[4]', m.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
                sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change, m.qty_expected))/2000,
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)
                from cargo_manifest m, cargo_activity a
                where a.lot_num = m.container_num and  a.service_code = 6200 and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code = '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code, m.qty1_unit,
		decode(substr(m.cargo_location, 1,4), 'WING', substr(m.cargo_location, 6,1), m.cargo_location)";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        //rf
        if (strtotime($vDate) < strtotime('12/01/2002')){
                $cargo_tracking = "arch_2002.cargo_tracking";
                $cargo_activity = "arch_2002.cargo_activity_arch";
        }else if (strtotime($vDate) >= strtotime('12/01/2002') && strtotime($vDate) < strtotime('12/01/2003') ){
                $cargo_tracking = "arch_2003.cargo_tracking";
                $cargo_activity = "arch_2003.cargo_activity";
        }else if (strtotime($vDate) >= strtotime('12/01/2003') && strtotime($vDate) < strtotime('12/01/2004') ){
                $cargo_tracking = "arch_2004.cargo_tracking";
                $cargo_activity = "arch_2004.cargo_activity";
        }else {
                $cargo_tracking = "cargo_tracking";
                $cargo_activity = "cargo_activity";
        }
        $sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101'),
                sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000),
		decode(substr(t.warehouse_location, 1,1), 'A','A','C','C','D','D','E','E','F','F','G','G','N/A')
                from $cargo_tracking t, $cargo_activity a, commodity_weight w
                where t.qty_received > 0  and  a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and
                a.customer_id = t.receiver_id and a.service_code = 1  and  
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'T'
                and t.commodity_code = w.commodity_code(+)
                group by  decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101'),
		decode(substr(t.warehouse_location, 1,1), 'A','A','C','C','D','D','E','E','F','F','G','G','N/A')";

        $statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
                $comm = ora_getcolumn($cursor_rf, 0);
                $qty = ora_getcolumn($cursor_rf, 1);
                $weight = ora_getcolumn($cursor_rf, 2);
		$location = ora_getcolumn($cursor_rf,3);
                $sql = "insert into $productivity (date_time,service_type,commodity, qty,unit,tonnage, location)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[4]','$comm', $qty,'PLT',$weight, '$location')";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }

        //INSPECTION
        //ccds inspection
        $sql = "select subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity),
                sum(pallets_received), round(sum(r.gross_weight)/2000), substr(r.location, 1,1)
                from ccd_received r, ccd_product p
                where inspection_date is not null and r.product = p.product and inspection_date ='$vDate'
                group by subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), substr(r.location, 1,1)";
        $result = pg_query($conn_ccd, $sql) or die("Error in query: $sql. " . pg_last_error($conn_ccd));
        $rows = pg_num_rows($result);
        for($i = 0; $i < $rows; $i++){
                $row = pg_fetch_row($result, $i);
                $comm = $row[0];
                $qty = $row[1];
                $weight = $row[2];
		$location = $row[3];
                $sql = "insert into $productivity (date_time, service_type,commodity, qty,unit,tonnage, location)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[5]','$comm', $qty, 'PLT', $weight, '$location')";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }


	//Get Hours
	for ($i = 0; $i < count($type); $i++){
		if ($i ==1){
                        $sql = "select commodity_code, sum(duration), vessel_id, location_id from hourly_detail
                                where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                                employee_id not in (select employee_id from employee where employee_type_id ='SUPV')
                                group by commodity_code, vessel_id, location_id";
		}else if ($i == 0 || $i ==3 || $i == 4 || $i ==5){
			$sql = "select commodity_code, sum(duration), location_id from hourly_detail
                        	where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                        	employee_id not in (select employee_id from employee where employee_type_id ='SUPV')
                        	group by commodity_code, location_id";
		}else{
			$sql = "select commodity_code, sum(duration) from hourly_detail 
				where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and 
				employee_id not in (select employee_id from employee where employee_type_id ='SUPV')
				group by commodity_code";
		}	

		$statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
		while(ora_fetch($cursor_lcs)){

			$comm = ora_getcolumn($cursor_lcs, 0);
			$hours = ora_getcolumn($cursor_lcs, 1);
			
			if ($i ==1){ //backhaul
				$lr_num = ora_getcolumn($cursor_lcs, 2);
				$loc = ora_getcolumn($cursor_lcs, 3);
			}else if($i == 0 || $i ==3 || $i == 4 || $i ==5){
				$loc = ora_getcolumn($cursor_lcs, 2);
				$lr_num = "";
			}else{
				$lr_num = "";
				$loc = "";
			}

			if ($lr_num <>""){
				$vessel = " and vessel = '$lr_num'";
			}else {
				$vessel = "";
			}
			if ($loc <>""){
				if (strtoupper(substr($loc, 0, 4)) == "WING"){
					$loc = strtoupper(substr($loc, 5, 1));
				}
				$strLoc = " and location = '$loc'";
			}else{
				$strLoc = "";
			}

			$sql = "select count(*) from $productivity
                                where date_time = to_date('$vDate','mm/dd/yyyy') and
                                service_type = '$type[$i]' and commodity = '$comm' $vessel $strLoc";
			$statement = ora_parse($cursor_bni, $sql);
                        ora_exec($cursor_bni);
                        $pRow = 0;
                        if (ora_fetch($cursor_bni)){
                                $pRow = ora_getcolumn($cursor_bni, 0);
                        }
                        if ($pRow ==0){
                                $sql = "insert into $productivity (date_time, service_type,commodity,hours, vessel, location)
                                        values (to_date('$vDate','mm/dd/yyyy'),'$type[$i]','$comm',$hours,'$lr_num','$loc')";
                        }else {
                                $sql = "update $productivity set
                                        hours = $hours
                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
                                        service_type = '$type[$i]' and commodity = '$comm' $vessel $strLoc";
			}
                        $statement = ora_parse($cursor_bni, $sql);
                        ora_exec($cursor_bni);
		}
	}
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
	
?>
