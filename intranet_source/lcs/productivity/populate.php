<?
$today = date('m/d/Y');
$yesterday = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
//$vDate = $HTTP_POST_VARS[vDate];

$wday = date('D');
if ($wday =="Mon"){
   $arrDate[0]=$today;
   $arrDate[1]=date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
   $arrDate[2]=date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 2 ,date("Y")));
   $arrDate[2]=date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 3 ,date("Y")));

}else{
   $arrDate[0] = $today;
   $arrDate[1] = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
}

$wday = date('D');
if ($wday =="Mon"){
        $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 3 ,date("Y")));
}else{
        $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
}
$eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));



//if ($vDate =="") $vDate = $yesterday;

//$vDate2 = $today;   

//$vDate = '05/26/2004';
//$vDate2 = '05/27/2004';
//$vDate = '06/01/2004';
//$vDate2 = '06/02/2004';


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
   $type[5] = "MAINTENANCE";
   $type[6] = "NON REVENUE";
   $type[7] = "STAND BY";

   $service_code_in[0] = " and (service_code between 6220 and 6229 ) ";
   $service_code_in[1] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6149) ";
   $service_code_in[2] = " and service_code between 6520 and 6619 ";
   $service_code_in[3] = " and service_code between 6310 and 6319 ";
   $service_code_in[4] = " and service_code between 6410 and 6419 ";
   $service_code_in[5] = " and service_code between 7200 and 7277 ";
   $service_code_in[6] = " and service_code between 7300 and 7399 ";
   $service_code_in[7] = " and service_code like '67__' ";


$sTime = strtotime($sDate);
$eTime = strtotime($eDate);
while ($sTime <= $eTime){
   	$vDate = date('m/d/Y', $sTime);
	//next day
        $sTime += 24*60*60;

	$sql = "delete from productivity where hire_date = to_date('$vDate','mm/dd/yyyy')";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);

	$sql = "delete from prod_hourly_detail where hire_date = to_date('$vDate','mm/dd/yyyy')";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);

        //get hire plan
	$sql = "insert into productivity (hire_date, service_type, supervisor, sup_id, location, num_of_hire, tot_hours, num_of_ftl, num_of_ltl, commodity_code, plts, budget) select hire_date, type, supervisor, sup_id, location, sum(num_of_hire), sum(tot_hours), sum(num_of_ftl), sum(num_of_ltl), commodity, sum(plts), budget from hire_plan where hire_date = to_date('$vDate','mm/dd/yyyy') group by hire_date, type, supervisor, sup_id, location, commodity, budget ";
 
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);

	$sql = "update productivity set commodity_code = 0 where hire_date = to_date('$vDate','mm/dd/yyyy') and (commodity_code is null or commodity_code ='')";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);
	
	//get hours for hourly_detail
	$sql = "insert into prod_hourly_detail (user_id, location_id, commodity_code, duration, employee_id, hire_date, service_code) select user_id, upper(location_id), commodity_code, duration, employee_id, hire_date, service_code from hourly_detail where user_id <> employee_id and hire_date =to_date('$vDate','mm/dd/yyyy')";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);

	//get actural hours 
	for($i = 0; $i < 8; $i++){
		$sql = "select user_id, upper(location_id), commodity_code, sum(duration) 
			from prod_hourly_detail 
			where hire_date =to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] 
			group by user_id, upper(location_id), commodity_code";
		$statement = ora_parse($cursor_lcs, $sql);
		ora_exec($cursor_lcs);
		while(ora_fetch($cursor_lcs)){
        		$user_id = ora_getcolumn($cursor_lcs, 0);
        		$location = ora_getcolumn($cursor_lcs, 1);
        		$commodity = ora_getcolumn($cursor_lcs, 2);
        		$hours = ora_getcolumn($cursor_lcs, 3);
        		$labor = $hours / 8;

        		$sql = "select * from productivity 
				where service_type = '$type[$i]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				sup_id = '$user_id' and commodity_code = '$commodity' and location = '$location'";

        		$statement = ora_parse($cursor_lcs2, $sql);
        		ora_exec($cursor_lcs2);
        		if (ora_fetch($cursor_lcs2)){
                		$sql = "update productivity set actual_hours = '$hours', num_of_lab = '$labor' 
					where service_type = '$type[$i]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
					sup_id = '$user_id' and commodity_code = '$commodity'  and location = '$location'";
        		}else{
                		$sql = "update prod_hourly_detail set isplan = 'N' 
					where user_id = '$user_id' and location_id = '$location' and 
					commodity_code = '$commodity' and 
					hire_date =to_date('$vDate','mm/dd/yyyy') $service_code_in[$i]";
        		}

        		$statement = ora_parse($cursor_lcs2, $sql);
        		ora_exec($cursor_lcs2);
		}
	}

	
	//get plts
	//truckloading
	//bni 
	$sql = "select t.commodity_code, sum(a.qty_change), substr(upper(t.warehouse_location), 1, 6) 
		from cargo_tracking t, cargo_activity a 
		where a.lot_num = t.lot_num and a.service_code = 6200 and t.commodity_code <> '1272' and 
		date_of_activity =to_date('$vDate','mm/dd/yyyy') 
		group by t.commodity_code,  substr(upper(t.warehouse_location), 1, 6)";

	$statement = ora_parse($cursor_bni, $sql);
	ora_exec($cursor_bni);
	while(ora_fetch($cursor_bni)){
		$commodity = ora_getcolumn($cursor_bni, 0);
		$qty = ora_getcolumn($cursor_bni, 1);
		$location = ora_getcolumn($cursor_bni, 2);
		$sql = "select * from productivity 
			where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location'";

		$statement = ora_parse($cursor_lcs, $sql);
		ora_exec($cursor_lcs);
		if(ora_fetch($cursor_lcs)){
			$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location' ";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
  	      	}else{
                	$sql = "insert into productivity (service_type, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[0]',to_date('$vDate','mm/dd/yyyy'),'$commodity','$location', '$qty','N')";
		}	
//		$statement = ora_parse($cursor_lcs, $sql);
//        	ora_exec($cursor_lcs);
	}

	//rf
	$sql = "select p.employee_id, decode(substr(t.arrival_num, 0, 3),'103','5409','100','5411','5101') as commodity_code,
 		sum(round(a.qty_change/t.qty_received, 2)) 
		from cargo_tracking t, cargo_activity a, activity_log l, per_owner.personnel2 p 
		where t.qty_received > 0 and  a.service_code = 6 and 
		a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and 
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity < to_date('$vDate','mm/dd/yyyy')+1 and 
		a.pallet_id = l.pallet_id and a.arrival_num = l.arrival_num and a.customer_id = l.customer_id and 
		activity_date = date_of_activity and l.supervisor_name = p.login_id(+) 
		group by  decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101'), p.employee_id ";

	$statement = ora_parse($cursor_rf, $sql);
	ora_exec($cursor_rf);
	while (ora_fetch($cursor_rf)){
		$user_id = ora_getcolumn($cursor_rf, 0);
		$commodity = ora_getcolumn($cursor_rf, 1);
		$qty = ora_getcolumn($cursor_rf, 2);
		
        	$sql = "select * from productivity 
			where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and sup_id = '$user_id' ";
        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
		if (ora_fetch($cursor_lcs)){
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and sup_id = '$user_id'";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
        	}else{ 
                	$sql = "insert into productivity (service_type, sup_id, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[0]','$user_id', to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location', '$qty','N')";
		}
//        	$statement = ora_parse($cursor_lcs, $sql); 
//        	ora_exec($cursor_lcs); 
	}

	//Holmen paper
        $sql = "select p.employee_id, '4374', count(*)
                from cargo_activity a, order_detail o, per_owner.personnel2 p
                where a.order_num = o.order_num and a.service_code = 2 and transport_mode = 'Road'
                and (a.activity_status is null or (a.activity_status <>'VOID' and a.activity_status <>'CANCELED')) and
                activity_date >=to_date('$vDate','mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and
                a.sup_name = p.login_id(+) group by p.employee_id, '4374'";

	$statement = ora_parse($cursor_paper, $sql);
	ora_exec($cursor_paper);
	while (ora_fetch($cursor_paper)){
        	$user_id = ora_getcolumn($cursor_paper, 0);
        	$commodity = ora_getcolumn($cursor_paper, 1);
        	$qty = ora_getcolumn($cursor_paper, 2);
	
        	$sql = "select * from productivity 
			where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and sup_id = '$user_id' ";
        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
        	if (ora_fetch($cursor_lcs)){
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and sup_id = '$user_id' ";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
               	}else{
                	$sql = "insert into productivity (service_type, sup_id, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[0]','$user_id', to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location', '$qty','N')";
        	}
//	        $statement = ora_parse($cursor_lcs, $sql);
//        	ora_exec($cursor_lcs);
	}
	
	//ccds
	$sql = "select r.ship_type, sum(a.pallets), substr(i.location, 1, 1)  
		from ccd_activity a, ccd_received r, ccd_inventory i  
		where a.transaction_type ='SHIPPED' and a.ccd_lot_id = r.ccd_lot_id and a.ccd_lot_id = i.ccd_lot_id and 
		a.from_customer_id = i.customer_id and execution_date ='$vDate'  
		group by r.ship_type, substr(i.location, 1, 1)";
        $sql = "select subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity),
                sum(a.pallets), substr(i.location, 1, 1)
                from ccd_activity a, ccd_received r, ccd_inventory i, ccd_product p
                where a.transaction_type ='SHIPPED' and a.ccd_lot_id = r.ccd_lot_id and i.ccd_lot_id = a.ccd_lot_id and
                a.from_customer_id = i.customer_id and i.product = p.product and execution_date ='$vDate'
                group by subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity),substr(i.location, 1, 1)";

	$result = pg_query($conn_ccd, $sql) or 
		die("Error in query: $sql. " .  pg_last_error($conn_ccd));
	$rows = pg_num_rows($result);

	for($j = 0; $j < $rows; $j++){
        	$row=pg_fetch_row($result, $j); 

        	$commodity = $row[0];
        	$qty = $row[1];
		$location = $row[2];
        	$sql = "select * from productivity 
			where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and location = 'WING $location'";

        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
        	if(ora_fetch($cursor_lcs)){
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[0]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and location = 'WING $location'";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);  
	      	}else{
                	$sql = "insert into productivity (service_type, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[0]',to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location','$qty','N')";
        	}
//		$statement = ora_parse($cursor_lcs, $sql);
//	        ora_exec($cursor_lcs);	
	}

	//BACKHAUL
	//bni
	$sql = "select commodity_code, sum(qty_received), substr(upper(warehouse_location), 1, 6) 
		from cargo_tracking 
		where date_received =to_date('$vDate','mm/dd/yyyy') and commodity_code <>'1272' 
		group by commodity_code, upper(warehouse_location)";  
	$statement = ora_parse($cursor_bni, $sql);
	ora_exec($cursor_bni);
	while(ora_fetch($cursor_bni)){
        	$commodity = ora_getcolumn($cursor_bni, 0);
        	$qty = ora_getcolumn($cursor_bni, 1);
        	$location = ora_getcolumn($cursor_bni, 2);
        	$sql = "select * from productivity 
			where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location'";
  
        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
        	if(ora_fetch($cursor_lcs)){
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location' ";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
        	}else{
                	$sql = "insert into productivity (service_type, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[1]',to_date('$vDate','mm/dd/yyyy'),'$commodity','$location', '$qty','N')";
        	}
//        	$statement = ora_parse($cursor_lcs, $sql);
//	        ora_exec($cursor_lcs);
	}

	//rf 
	$sql = "select p.employee_id, decode(substr(t.arrival_num, 0, 3),'103','5409','100','5411','5101') as commodity_code,
 		sum(round(a.qty_change/t.qty_received, 2)) 
		from cargo_tracking t, cargo_activity a, activity_log l, per_owner.personnel2 p 
		where (t.receiving_type is null or t.receiving_type <> 'T') and t.qty_received > 0 and a.service_code = 1 and
		a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and 
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <to_date('$vDate','mm/dd/yyyy')+1 and
 		a.pallet_id = l.pallet_id and a.arrival_num = l.arrival_num and a.customer_id = l.customer_id and 
		activity_date = date_of_activity and l.supervisor_name = p.login_id(+) 
		group by decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101'), p.employee_id ";

	$statement = ora_parse($cursor_rf, $sql);
	ora_exec($cursor_rf);
	while (ora_fetch($cursor_rf)){
        	$user_id = ora_getcolumn($cursor_rf, 0);
        	$commodity = ora_getcolumn($cursor_rf, 1);
     		$qty = ora_getcolumn($cursor_rf, 2); 
        	$sql = "select * from productivity 
			where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and sup_id = '$user_id'"; 
        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
        	if (ora_fetch($cursor_lcs)){      
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and sup_id = '$user_id'";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
            	}else{
                	$sql = "insert into productivity (service_type, sup_id, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[1]','$user_id', to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location', '$qty','N')";
        	}
//        	$statement = ora_parse($cursor_lcs, $sql);
//        	ora_exec($cursor_lcs);
	}

	//Holmen paper
	$sql = "select p.employee_id, '4374', count(*) 
		from cargo_activity a, per_owner.personnel2 p 
		where a.service_code = 1 and 
		(a.activity_status is null or (a.activity_status <>'VOID' and a.activity_status <>'CANCELED')) and 
		activity_date >=to_date('$vDate','mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and 
		a.sup_name = p.login_id(+) group by p.employee_id, '4374'";
	$statement = ora_parse($cursor_paper, $sql);
	ora_exec($cursor_paper);
	while (ora_fetch($cursor_paper)){
        	$user_id = ora_getcolumn($cursor_paper, 0);
        	$commodity = ora_getcolumn($cursor_paper, 1);
        	$qty = ora_getcolumn($cursor_paper, 2);
        	$sql = "select * from productivity 
			where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and sup_id = '$user_id'";
        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
        	if (ora_fetch($cursor_lcs)){
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and sup_id = '$user_id'";
        	        $statement = ora_parse($cursor_lcs, $sql);
        		ora_exec($cursor_lcs);
		}else{
                	$sql = "insert into productivity (service_type, sup_id, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[0]','$user_id', to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location', '$qty','N')";
        	}
//        $statement = ora_parse($cursor_lcs, $sql);
//        ora_exec($cursor_lcs);
}

	//ccds
	$sql = "select r.ship_type, sum(a.pallets), substr(i.location, 1, 1)  from ccd_activity a, ccd_received r, ccd_inventory i  where a.transaction_type ='RECEIVED' and a.ccd_lot_id = r.ccd_lot_id and a.ccd_lot_id = i.ccd_lot_id and a.from_customer_id = i.customer_id and execution_date ='$vDate'  group by r.ship_type, substr(i.location, 1, 1)";
        $sql = "select subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity),
                sum(a.pallets), substr(i.location, 1, 1)
                from ccd_activity a, ccd_received r, ccd_inventory i, ccd_product p
                where a.transaction_type ='RECEIVED' and a.ccd_lot_id = r.ccd_lot_id and i.ccd_lot_id = a.ccd_lot_id and
                a.from_customer_id = i.customer_id and i.product = p.product and execution_date ='$vDate'
                group by subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity),substr(i.location, 1, 1)";

	$result = pg_query($conn_ccd, $sql) or die("Error in query: $sql. " .  pg_last_error($conn_ccd));
	$rows = pg_num_rows($result);
 
	for($j = 0; $j < $rows; $j++){
        	$row=pg_fetch_row($result, $j);
 
        	$commodity = $row[0];
        	$qty = $row[1];
        	$location = $row[2];
        	$sql = "select * from productivity 
			where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
			commodity_code = '$commodity' and location = 'WING $location'";
 
        	$statement = ora_parse($cursor_lcs, $sql);
        	ora_exec($cursor_lcs);
        	if(ora_fetch($cursor_lcs)){
                	$sql = "update productivity set actual_plts = '$qty' 
				where service_type = '$type[1]' and hire_date = to_date('$vDate','mm/dd/yyyy') and 
				commodity_code = '$commodity' and location = 'WING $location'";
        		$statement = ora_parse($cursor_lcs, $sql);
        		ora_exec($cursor_lcs);
        	}else{
                	$sql = "insert into productivity (service_type, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[1]',to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location','$qty','N')";
        	}
//        	$statement = ora_parse($cursor_lcs, $sql);
//        	ora_exec($cursor_lcs);
	} 

        //Rail Car Handling
        //Holmen Paper
        $sql = "select p.employee_id, '4374', count(*)
                from cargo_activity a, order_detail o, per_owner.personnel2 p
                where a.order_num = o.order_num and a.service_code = 2 and transport_mode = 'Rail'
                and (a.activity_status is null or (a.activity_status <>'VOID' and a.activity_status <>'CANCELED')) and
                activity_date >=to_date('$vDate','mm/dd/yyyy') and activity_date < to_date('$vDate','mm/dd/yyyy')+1 and
                a.sup_name = p.login_id(+) group by p.employee_id, '4374'";

        $statement = ora_parse($cursor_paper, $sql);
        ora_exec($cursor_paper);
        while (ora_fetch($cursor_paper)){
                $user_id = ora_getcolumn($cursor_paper, 0);
                $commodity = ora_getcolumn($cursor_paper, 1);
                $qty = ora_getcolumn($cursor_paper, 2);

                $sql = "select * from productivity
                        where service_type = '$type[3]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                        commodity_code = '$commodity' and sup_id = '$user_id' ";
                $statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
                if (ora_fetch($cursor_lcs)){
                        $sql = "update productivity set actual_plts = '$qty'
                                where service_type = '$type[3]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                                commodity_code = '$commodity' and sup_id = '$user_id' ";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
                }else{
                        $sql = "insert into productivity (service_type, sup_id, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[3]','$user_id', to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location', '$qty','N')";
                }
//              $statement = ora_parse($cursor_lcs, $sql);
//              ora_exec($cursor_lcs);
        }
	
        //bni Dole paper
        $sql = "select commodity_code, sum(qty_received), substr(upper(warehouse_location), 1, 6)
                from cargo_tracking
                where date_received =to_date('$vDate','mm/dd/yyyy') and commodity_code ='1272'
                group by commodity_code, upper(warehouse_location)";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
        while(ora_fetch($cursor_bni)){
                $commodity = ora_getcolumn($cursor_bni, 0);
                $qty = ora_getcolumn($cursor_bni, 1);
                $location = ora_getcolumn($cursor_bni, 2);
                $sql = "select * from productivity
                        where service_type = '$type[3]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                        commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location'";

                $statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
                if(ora_fetch($cursor_lcs)){
                        $sql = "update productivity set actual_plts = '$qty'
                                where service_type = '$type[3]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                                commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location' ";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
                }else{
                        $sql = "insert into productivity (service_type, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[3]',to_date('$vDate','mm/dd/yyyy'),'$commodity','$location', '$qty','N')";
                }
//              $statement = ora_parse($cursor_lcs, $sql);
//              ora_exec($cursor_lcs);
        }

	//Container Handling
	//dole paper
        $sql = "select t.commodity_code, sum(a.qty_change), substr(upper(t.warehouse_location), 1, 6)
                from cargo_tracking t, cargo_activity a
                where a.lot_num = t.lot_num and a.service_code = 6200 and t.commodity_code = '1272' and
                date_of_activity =to_date('$vDate','mm/dd/yyyy')
                group by t.commodity_code, upper(t.warehouse_location)";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
        while(ora_fetch($cursor_bni)){
                $commodity = ora_getcolumn($cursor_bni, 0);
                $qty = ora_getcolumn($cursor_bni, 1);
                $location = ora_getcolumn($cursor_bni, 2);
                $sql = "select * from productivity
                        where service_type = '$type[4]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                        commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location'";

                $statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
                if(ora_fetch($cursor_lcs)){
                        $sql = "update productivity set actual_plts = '$qty'
                                where service_type = '$type[4]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                                commodity_code = '$commodity' and substr(upper(location), 1, 6) = '$location' ";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
                }else{
                        $sql = "insert into productivity (service_type, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[4]',to_date('$vDate','mm/dd/yyyy'),'$commodity','$location', '$qty','N')";
                }
//              $statement = ora_parse($cursor_lcs, $sql);
//              ora_exec($cursor_lcs);
        }

        //rf
        $sql = "select p.employee_id, decode(substr(t.arrival_num, 0, 3),'103','5409','100','5411','5101') as commodity_code,
                sum(round(a.qty_change/t.qty_received, 2))
                from cargo_tracking t, cargo_activity a, activity_log l, per_owner.personnel2 p
                where t.receiving_type = 'T' and t.qty_received > 0 and a.service_code = 1  and
                a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and
                date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <to_date('$vDate','mm/dd/yyyy')+1 and
                a.pallet_id = l.pallet_id and a.arrival_num = l.arrival_num and a.customer_id = l.customer_id and
                activity_date = date_of_activity and l.supervisor_name = p.login_id(+)
                group by decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101'), p.employee_id ";

        $statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
                $user_id = ora_getcolumn($cursor_rf, 0);
                $commodity = ora_getcolumn($cursor_rf, 1);
                $qty = ora_getcolumn($cursor_rf, 2);
                $sql = "select * from productivity
                        where service_type = '$type[4]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                        commodity_code = '$commodity' and sup_id = '$user_id'";
                $statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
                if (ora_fetch($cursor_lcs)){
                        $sql = "update productivity set actual_plts = '$qty'
                                where service_type = '$type[4]' and hire_date = to_date('$vDate','mm/dd/yyyy') and
                                commodity_code = '$commodity' and sup_id = '$user_id'";
                        $statement = ora_parse($cursor_lcs, $sql);
                        ora_exec($cursor_lcs);
                }else{
                        $sql = "insert into productivity (service_type, sup_id, hire_date, commodity_code, location, actual_plts, isplan) values ('$type[4]','$user_id', to_date('$vDate','mm/dd/yyyy'),'$commodity', 'WING $location', '$qty','N')";
                }
//              $statement = ora_parse($cursor_lcs, $sql);
//              ora_exec($cursor_lcs);
        }

	
	//insert hours not in the hire plan
	$sql = "select user_id, count(distinct employee_id), sum(duration) 
		from prod_hourly_detail where isplan = 'N' and hire_date =to_date('$vDate','mm/dd/yyyy') group by user_id";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);
	while(ora_fetch($cursor_lcs)){
		$user_id = ora_getcolumn($cursor_lcs, 0);
		$lab = ora_getcolumn($cursor_lcs, 1);
        	$hrs = ora_getcolumn($cursor_lcs, 2);

		$sql = "insert into productivity (hire_date, sup_id, actual_hours, num_of_lab, isplan) 
			values (to_date('$vDate','mm/dd/yyyy'),'$user_id', '$hrs', '$lab','N')";
 		$statement = ora_parse($cursor_lcs2, $sql);
        	ora_exec($cursor_lcs2);
	}


	//calculation
	//supervisor name
	$sql = "update productivity set supervisor = (select user_name from lcs_user 
		where user_id = sup_id) where hire_date=to_date('$vDate', 'mm/dd/yyyy') and supervisor is null";
	$statement = ora_parse($cursor_lcs, $sql); 
	ora_exec($cursor_lcs);

	//budget
	$sql = "update productivity set budget = 
		(select budget from budget where commodity = commodity_code and type = service_type) 
		where hire_date=to_date('$vDate', 'mm/dd/yyyy') and budget is null";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);

	//update prod_report_date table
	$sql = "insert into prod_report_date (report_date) values (to_date('$vDate', 'mm/dd/yyyy'))";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);
 

}
?> 
