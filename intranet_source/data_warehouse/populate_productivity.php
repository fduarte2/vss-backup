<?

/* Adam Walter, March 1, 2007.
*  Original Author:  unknown.
*
*  There seems to be logic problem in this code resulting in an off-by-1 error:
*  Specifically, eDate (end date) is set to day - 1, but the while loop was a < sign,
*  Meaning that the date that lands on "eDate" was skipped in the loop, meaning that
*  Only data from 2 days and before was recorded.
*
*  It should also be noted that since the creation of this file, a new method for
*  backing up DB tables was put into place.  Correcting this file for the new method
*  would be near impossible to test; basically, the "archive schema" for defining
*  tables cargo_tracking and cargo_activity is no longer accurate, and must be such
*  that any table after a "backup" breakpoint is named cargo_tracking_XXXX (where 
*  XXXX is the fiscal year number).  At this time, however, there is no written
*  policy that dictates when said back is done (and the old cargo_trackign is purged)
*  and so I cannot code for it.  As of this typing, the variable "$days" is defined
*  In the cronjob that runs this to be 30, so as long as the "backup purge" is never
*  done less than 30 days after the "backup breakpoint" is reached, then no problems
*  will occur.  So far, it hasn't.
*
*  if it occurs in the future that it turns out a fix does need to be made... then
*  I'll make it.
***********************************************************************************/

//   $sDate = "07/01/2004";
//   $eDate = "08/04/2004";
//   $eDate = "07/01/2003";

   $days = $HTTP_SERVER_VARS["argv"][1]; 
echo $days." days\n";
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
   $eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y"))); 

//   $sDate = "01/01/2005";
//   $eDate = "06/22/2003";   
   // open a connection to the database server
   include("connect.php");
/*
   $conn_ccd = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$conn_ccd){
      	die("Could not open connection to PostgreSQL database server");
   }
*/
//   $conn_bni = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
   $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn_bni < 1){
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   }
   $cursor_bni = ora_open($conn_bni);
   $cursor_bni2 = ora_open($conn_bni);

//   $conn_rf = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
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


   //clear productivity table
   $sql = "delete from productivity where date_time >= to_date('$sDate','mm/dd/yyyy') and date_time <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $vTime = strtotime($sDate);
   $eTime = strtotime($eDate);
   $d = 0;
   while ($vTime <= $eTime){
        $vDate = date('m/d/Y',mktime(0,0,0,date("m",strtotime($sDate)),date("d",strtotime($sDate)) + $d ,date("Y",strtotime($sDate))));
        $d++;
        $vTime = strtotime($vDate);
echo $vDate."\r\n";
	//truckloading
	//bni 
/*   	$sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
           	select date_of_activity, '$type[0]', t.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
		decode(t.commodity_code, '4963', (sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received,                 m.qty_expected))*3/2), (sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received,                           m.qty_expected))/2000))
           	from cargo_tracking t, cargo_manifest m, cargo_activity a
           	where a.lot_num = m.container_num and a.lot_num = t.lot_num and  a.service_code = 6200 and
           	date_of_activity = to_date('$vDate','mm/dd/yyyy') and t.commodity_code <> '1272' and qty_received > 0
           	group by date_of_activity, t.commodity_code, m.qty1_unit"; */
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_of_activity, '$type[0]', m.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
                decode(m.commodity_code, '4963', (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                     m.qty_expected))*3/2), (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                               m.qty_expected))/2000))
                from cargo_manifest m, cargo_activity a, cargo_delivery d
                where a.lot_num = m.container_num and  a.service_code = 6200 and
				a.lot_num = d.lot_num and a.activity_num = d.activity_num and d.delivery_num > 0 and d.transportation_mode = 'TRUCK' and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code <> '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code, m.qty1_unit";
   	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);

	//rf
     /*   $sql = "select decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101) as commodity_code, 
		sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight  
		from (select * from cargo_tracking union all select * from arch_2003.cargo_tracking) t, 
		(select * from cargo_activity union all select * from arch_2003.cargo_activity) a, 
		commodity_profile c, commodity_weight w 
		where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and t.commodity_code = c.commodity_code 
		and  a.pallet_id = t.pallet_id and a.service_code = 6 and   
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
		group by  decode(t.shipping_line, 5400, c.avg_lb_per_case, 5101)"; */


/*        $sql = "select decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101') as commodity_code,
                sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight
                from (select * from arch_2002.cargo_tracking union all select * from arch_2003.cargo_tracking) t,
                (select * from arch_2002.cargo_activity_arch union all 
		select * from arch_2003.cargo_activity union all
		select * from arch_2004.cargo_activity) a,commodity_weight w
                where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and 
		a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and 
		t.commodity_code = w.commodity_code and
                date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
                group by  decode(substr(t.arrival_num, 0, 3), '103', '5409','100', '5411', '5101')"; */

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
        //$sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101', t.commodity_code),
        //        sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight
        //        from $cargo_tracking t, $cargo_activity a,commodity_weight w
        //        where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and
        //        a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and
        //        date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
        //        group by  decode(substr(t.arrival_num, 0, 3), '103','5409','102','5411','100','5411','200','5101', t.commodity_code)";

	
	// Commented out by pwu-2006/11/17
	//$sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101', t.commodity_code),
        //        sum(a.qty_change/t.qty_received) as qty, round(sum(a.qty_change * w.weight)/2000) as weight
        //        from $cargo_tracking t, $cargo_activity a,commodity_weight w
        //        where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and
        //        a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and
        //        date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
        //        group by  decode(substr(t.arrival_num, 0, 3), '103','5409','102','5411','100','5411','200','5101', t.commodity_code)";

	
	// Added by pwu-2006/11/17
	// Commented by pwu-2007/01/10
	//$sql = "select decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101', t.commodity_code),
       //         count(a.PALLET_ID) as qty,  round(count(a.PALLET_ID)/1.05) as weight
       //         from $cargo_tracking t, $cargo_activity a,commodity_weight w
       //         where t.qty_received > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and
       //         a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and
       //         date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
       //         group by  decode(substr(t.arrival_num, 0, 3), '103','5409','102','5411','100','5411','200','5101', t.commodity_code)";

	// Added by pwu-2007/01/10
/*	$sql = "select p.prod_group, count(a.PALLET_ID) as qty,  round(count(a.PALLET_ID)/1.05) as weight
                from $cargo_tracking t, $cargo_activity a,commodity_weight w, prod_commodity_group p
                where t.qty_received > 0 and a.qty_change > 0 and t.commodity_code = w.commodity_code(+) and  a.pallet_id = t.pallet_id and
                a.arrival_num = t.arrival_num and a.customer_id = t.receiver_id and a.service_code = 6 and t.commodity_code = p.commodity_code(+) and
                date_of_activity >=to_date('$vDate','mm/dd/yyyy') and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
                group by p.prod_group";;*/
	
	// newest mod, Adam Walter 3/10/2013.  NOT ALL RF CARGO is a "1 barcode to 1 quantity" ratio anymore, so the SELECT needs to change.
	// another mod... a.BATCH_ID is because finance wants to specifically separate steel cargo by shipout type
	$sql = "select p.prod_group, sum(decode(cp.commodity_type, 'STEEL', qty_change, 1)) as qty, 
					sum(decode(cp.commodity_type, 'STEEL', a.qty_change * (t.weight / qty_received), (2000 / 1.05))) / 2000 as weight
			from $cargo_tracking t, $cargo_activity a,commodity_weight w, prod_commodity_group p, commodity_profile cp
			where t.qty_received > 0 and a.qty_change > 0 
				and t.commodity_code = w.commodity_code(+) 
				and t.commodity_code = cp.commodity_code
				and a.pallet_id = t.pallet_id 
				and a.arrival_num = t.arrival_num 
				and a.customer_id = t.receiver_id 
				and a.service_code = 6
				and (a.activity_description is null or a.activity_description != 'VOID')
				and t.commodity_code = p.commodity_code(+) 
				and date_of_activity >=to_date('$vDate','mm/dd/yyyy') 
				and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
				and (a.BATCH_ID IS NULL OR a.BATCH_ID != 'RAILCAR')
				and (
					(t.commodity_code not in (select commodity_code from PRODUCTIVITY_SPECIF_CUST_COMM))
					or
					((t.commodity_code, t.receiver_id) in (select commodity_code, customer_id from PRODUCTIVITY_SPECIF_CUST_COMM))
					)
			group by p.prod_group";
//	echo $sql."\n";

	// LAME HARD CODING ALERT (Adam Walter, Aug2009)
	// as the commodity code, by itself, is not enough to distinguish the new Oppy cargo on the productivity report,
	// i am adjusting the above SQL to decode shipping line 8454 into specific commodities, otherwise join to the prod_commodity_group table
	$statement = ora_parse($cursor_rf, $sql);
	ora_exec($cursor_rf);
	while (ora_fetch($cursor_rf)){
			$comm = ora_getcolumn($cursor_rf, 0);
			$qty = round(ora_getcolumn($cursor_rf, 1));
			$weight = round(ora_getcolumn($cursor_rf, 2));
			$sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
				values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', null, null, $qty, 'PLT', $weight)";
			echo $sql."\n";
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
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', null, null, $qty, 'ROLL', $weight)";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
		}
        }
/*
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
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[0]','$comm', null, null, $qty, 'PLT', $weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
*/	
	//Backhaul
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
		select to_date('$vDate','mm/dd/yyyy'), type, commodity_code, lr_num, null, sum(qty), qty1_unit, sum(weight) 
		from (
                select '$type[1]' as type, commodity_code, lr_num, null, 
		sum(qty_expected) as qty, qty1_unit, 
                decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000,
                'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)) as weight
                from cargo_manifest 
                where lr_num > 1000 and commodity_code <>'1272' and lr_num in 
                (select lr_num from voyage where to_char(date_departed, 'mm/dd/yyyy') =  '$vDate') 
                group by lr_num, commodity_code, qty1_unit, upper(cargo_weight_unit)) a
		group by type, commodity_code, lr_num, qty1_unit";
 
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);	

        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
		select date_received, type, commodity_code, lr_num, null, sum(qty), qty1_unit, sum(weight) from (
                select date_received,'$type[1]' as type, m.commodity_code, lr_num, null,sum(qty_expected) as qty,m.qty1_unit,
                decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000,
                'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)) as weight
                from cargo_tracking t, cargo_manifest m
                where lr_num < 10 and  t.lot_num = m.container_num and to_char(date_received, 'mm/dd/yyyy') ='$vDate' and
                t.commodity_code <>'1272' 
                group by date_received, lr_num, m.commodity_code, m.qty1_unit, upper(cargo_weight_unit)) a
		group by date_received, type, commodity_code, lr_num, qty1_unit"; 
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
		// outbound railcar BNI
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_of_activity, '$type[3]', m.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
                decode(m.commodity_code, '4963', (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                     m.qty_expected))*3/2), (sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change,                               m.qty_expected))/2000))
                from cargo_manifest m, cargo_activity a, cargo_delivery d
                where a.lot_num = m.container_num and  a.service_code = 6200 and
				a.lot_num = d.lot_num and a.activity_num = d.activity_num and d.delivery_num > 0 and d.transportation_mode = 'RAILCAR' and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code <> '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code, m.qty1_unit";
   	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);

		// outbound STEEL - RF
		// please note:  this is a near-copy of RF-truckloading SQL.  It contains a LOT of unnecessary where clauses;
		// however, since the SQL is known to work, now is not the time to change any but the necessary part.
		$sql = "select p.prod_group, sum(decode(cp.commodity_type, 'STEEL', qty_change, 1)) as qty, 
						sum(decode(cp.commodity_type, 'STEEL', a.qty_change * (t.weight / qty_received), (2000 / 1.05))) / 2000 as weight
				from $cargo_tracking t, $cargo_activity a,commodity_weight w, prod_commodity_group p, commodity_profile cp
				where t.qty_received > 0 and a.qty_change > 0 
					and t.commodity_code = w.commodity_code(+) 
					and t.commodity_code = cp.commodity_code
					and a.pallet_id = t.pallet_id 
					and a.arrival_num = t.arrival_num 
					and a.customer_id = t.receiver_id 
					and a.service_code = 6
					and (a.activity_description is null or a.activity_description != 'VOID')
					and t.commodity_code = p.commodity_code(+) 
					and date_of_activity >=to_date('$vDate','mm/dd/yyyy') 
					and date_of_activity <=to_date('$vDate','mm/dd/yyyy') +1
					and a.BATCH_ID = 'RAILCAR'
					and (
						(t.commodity_code not in (select commodity_code from PRODUCTIVITY_SPECIF_CUST_COMM))
						or
						((t.commodity_code, t.receiver_id) in (select commodity_code, customer_id from PRODUCTIVITY_SPECIF_CUST_COMM))
						)
				group by p.prod_group";
//		echo $sql."\n";
		$statement = ora_parse($cursor_rf, $sql);
		ora_exec($cursor_rf);
		while (ora_fetch($cursor_rf)){
			$comm = ora_getcolumn($cursor_rf, 0);
			$qty = round(ora_getcolumn($cursor_rf, 1));
			$weight = round(ora_getcolumn($cursor_rf, 2));
			$sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
				values (to_date('$vDate','mm/dd/yyyy'), '$type[3]','$comm', null, null, $qty, 'PLT', $weight)";
			echo $sql."\n";
			$statement = ora_parse($cursor_bni, $sql);
			ora_exec($cursor_bni);  
		}

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
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[3]','$comm', null, null, $qty, 'ROLL', $weight)";

                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
                }
        }

        //bni dole paper
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_received, '$type[3]', t.commodity_code, lr_num, null, sum(qty_received), m.qty1_unit,
                round(sum(m.cargo_weight * qty_received / decode(m.qty_expected, 0, qty_received, m.qty_expected))/2000)
                from cargo_tracking t, cargo_manifest m
                where t.lot_num = m.container_num and date_received =to_date('$vDate','mm/dd/yyyy') and
                t.commodity_code ='1272' and qty_received > 0 
                group by date_received, lr_num, t.commodity_code, m.qty1_unit";
/*
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select to_date('$vDate','mm/dd/yyyy'), '$type[3]', commodity_code, lr_num, null, sum(qty_expected),
                qty1_unit, sum(cargo_weight)/2000
                from cargo_manifest
                where lr_num > 8000 and commodity_code ='1272' and (lr_num, commodity_code) in
                (select lr_num, m.commodity_code from cargo_manifest m , cargo_tracking t 
		where m.container_num = t.lot_num and qty_received > 0
                group by lr_num, m.commodity_code having min(date_received)  =  to_date('$vDate','mm/dd/yyyy'))
                group by lr_num, commodity_code, qty1_unit";
*/
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
        $sql = "insert into productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_of_activity, '$type[4]', m.commodity_code, null, null, sum(a.qty_change), m.qty1_unit,
                sum(m.cargo_weight * qty_change / decode(m.qty_expected, 0, qty_change, m.qty_expected))/2000
                from cargo_manifest m, cargo_activity a
                where a.lot_num = m.container_num and  a.service_code = 6200 and
                date_of_activity = to_date('$vDate','mm/dd/yyyy') and m.commodity_code = '1272' and qty_change > 0
                group by date_of_activity, m.commodity_code, m.qty1_unit";

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
                (select * from arch_2002.cargo_activity_arch union all select * from arch_2003.cargo_activity)  a, 
		commodity_weight w
                where t.qty_received > 0  and  a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and 
		a.customer_id = t.receiver_id and a.service_code = 1  and  
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'T'
                and t.commodity_code = w.commodity_code(+)
                group by  decode(substr(t.arrival_num, 0, 3), '103', '5409', '100', '5411', '5101')";

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
                sum(round(a.qty_change/t.qty_received, 2)), round(sum(a.qty_change * w.weight)/2000)
                from $cargo_tracking t, $cargo_activity a, commodity_weight w
                where t.qty_received > 0  and  a.pallet_id = t.pallet_id and a.arrival_num = t.arrival_num and
                a.customer_id = t.receiver_id and a.service_code = 1  and  
		date_of_activity >=to_date('$vDate','mm/dd/yyyy') and
                date_of_activity < to_date('$vDate','mm/dd/yyyy') +1 and t.receiving_type = 'T'
                and t.commodity_code = w.commodity_code(+)
                group by  decode(substr(t.arrival_num, 0, 3),'103','5409','102','5411','100','5411','200','5101','5101')";

        $statement = ora_parse($cursor_rf, $sql);
        ora_exec($cursor_rf);
        while (ora_fetch($cursor_rf)){
                $comm = ora_getcolumn($cursor_rf, 0);
                $qty = ora_getcolumn($cursor_rf, 1);
                $weight = ora_getcolumn($cursor_rf, 2);

                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[4]','$comm', null, null, $qty,'PLT',$weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
/*
        //INSPECTION
        //ccds inspection
        $sql = "select subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity),
                sum(pallets_received), round(sum(r.gross_weight)/2000), r.ship_type
                from ccd_received r, ccd_product p
                where inspection_date is not null and r.product = p.product and inspection_date ='$vDate'
                group by subst_vals(r.ship_type, 'K', p.ky_commodity,p.ccd_commodity), r.ship_type";
        $result = pg_query($conn_ccd, $sql) or die("Error in query: $sql. " . pg_last_error($conn_ccd));
        $rows = pg_num_rows($result);
        for($i = 0; $i < $rows; $i++){
                $row = pg_fetch_row($result, $i);
                $comm = $row[0];
                $qty = $row[1];
                $weight = $row[2];
                $sql = "insert into productivity (date_time, service_type,commodity, vessel, shipping_line, qty,unit,tonnage)
                        values (to_date('$vDate','mm/dd/yyyy'), '$type[5]','$comm', null, null, $qty, 'PLT', $weight)";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
*/

	//Get Hours
	for ($i = 0; $i < count($type); $i++){
		$sql = "select commodity_code, sum(duration) from hourly_detail 
			where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and 
			employee_id not in (select employee_id from employee where employee_type_id ='SUPV')
			group by commodity_code";	
		if ($i ==1)
			$sql = "select commodity_code, sum(duration), vessel_id from hourly_detail
                        	where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                        	employee_id not in (select employee_id from employee where employee_type_id ='SUPV')
                        	group by commodity_code, vessel_id";


		$statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
		while(ora_fetch($cursor_lcs)){

			$comm = ora_getcolumn($cursor_lcs, 0);
			$hours = ora_getcolumn($cursor_lcs, 1);
			
			if ($i ==1){ //backhaul
				$lr_num = ora_getcolumn($cursor_lcs, 2);

				$vessel_id = " and vessel_id = $lr_num ";
				$vessel = " and vessel = $lr_num ";
			}else{
				$lr_num = "";
				$vessel_id = " ";
				$vessel = " ";
			}

			//get supervisor
			$sql = "select user_name, sum(duration) from hourly_detail h, lcs_user u
                        	where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                        	employee_id not in (select employee_id from employee where employee_type_id ='SUPV') 
				and commodity_code = '$comm' and h.user_id = u.user_id $vessel_id
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
                                employee_id not in (select employee_id from employee where employee_type_id ='SUPV') 
				and commodity_code = '$comm' $vessel_id
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
			
			$sql = "select count(*) from productivity 
				where date_time = to_date('$vDate','mm/dd/yyyy') and 
				service_type = '$type[$i]' and commodity = '$comm' $vessel";
			$statement = ora_parse($cursor_bni, $sql);
                        ora_exec($cursor_bni);
			$pRow = 0;
			if (ora_fetch($cursor_bni)){
				$pRow = ora_getcolumn($cursor_bni, 0);
			}	
			if ($pRow ==0){
				$sql = "insert into productivity (date_time, service_type,commodity, 
					supervisor1, supervisor2, supervisor3, checkers, laborers, operators, hours, vessel)
 					values (to_date('$vDate','mm/dd/yyyy'), '$type[$i]','$comm', '$sup[0]','$sup[1]', 
					'$sup[2]', $chk, $lab, $opt, $hours, '$lr_num' )";
                		$statement = ora_parse($cursor_bni, $sql);
                		ora_exec($cursor_bni);
			}else if ($pRow == 1){
				$sql = "update productivity set 
					supervisor1 = '$sup[0]', 
					supervisor2 = '$sup[1]',
					supervisor3 = '$sup[2]',
					checkers = $chk,
					laborers = $lab,
					operators = $opt,
					hours = $hours
					where date_time = to_date('$vDate','mm/dd/yyyy') and
					service_type = '$type[$i]' and commodity = '$comm' $vessel";
				$statement = ora_parse($cursor_bni, $sql);
                                ora_exec($cursor_bni);
			}else{
				$sql = "select sum(tonnage) from productivity
                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
					service_type = '$type[$i]' and commodity = '$comm' $vessel";
                                $statement = ora_parse($cursor_bni2, $sql);
                                ora_exec($cursor_bni2);
                                if (ora_fetch($cursor_bni2)){
					$tWeight = ora_getcolumn($cursor_bni2, 0);
				}else{
					$tWeight = 0;
				}
				$sql = "select unit, tonnage from productivity
	                                where date_time = to_date('$vDate','mm/dd/yyyy') and
					service_type = '$type[$i]' and commodity = '$comm' $vessel";
                                $statement = ora_parse($cursor_bni2, $sql);
                                ora_exec($cursor_bni2);
				while (ora_fetch($cursor_bni2)){
					$unit = ora_getcolumn($cursor_bni2, 0);
					$uWeight = ora_getcolumn($cursor_bni2, 1);
					
					if ($tWeight > 0){
						$uHours = ($uWeight/$tWeight)* $hours;
						$uChk = ($uWeight/$tWeight)* $chk;
						$uLab = ($uWeight/$tWeight)* $lab;
						$uOpt = ($uWeight/$tWeight)* $opt;
					}else{
						$uHours = 0;
					}
					$sql = "update productivity set
	                                        supervisor1 = '$sup[0]',
        	                                supervisor2 = '$sup[1]',
                	                        supervisor3 = '$sup[2]',
                        	                checkers = $uChk,
                                	        laborers = $uLab,
                                        	operators = $uOpt,
                                        	hours = $uHours
	                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
						service_type='$type[$i]' and commodity='$comm' and unit='$unit' $vessel";
        	        	     	$statement = ora_parse($cursor_bni, $sql);
                	       		ora_exec($cursor_bni);
				}
			}
		}		
	}
	 
   }
  
   //Get commodity_name
  $sql = "update productivity set commodity_name = (select commodity_name from commodity_profile where commodity_code = commodity) where commodity_name is null and date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);
	
  //Get budget
  $sql = "update productivity set budget = (select budget from budget where type = service_type and commodity like commodity_code) where date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);

  $sql = "update productivity set budget = 9999 where budget is null";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);


  //populate productivity_hire_plan
   $productivity = "productivity_hire_plan";

   //clear productivity table
   $sql = "delete from $productivity where date_time >= to_date('$sDate','mm/dd/yyyy') and date_time <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $i = 0;
   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);
   $vTime = strtotime($sDate);
   $eTime = strtotime($eDate);
   $d = 0;
   while ($vTime < $eTime){
        $vDate = date('m/d/Y',mktime(0,0,0,date("m",strtotime($sDate)),date("d",strtotime($sDate)) + $d ,date("Y",strtotime($sDate))));
        $d++;
        $vTime = strtotime($vDate);
echo $productivity.$vDate."\r\n";
        //get activity data
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
                select date_time, service_type, commodity, vessel, shipping_line, sum(qty), null, sum(tonnage)
                from productivity
                where date_time = to_date('$vDate','mm/dd/yyyy')
                group by date_time, service_type, commodity, vessel, shipping_line";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        //Get Hours
        for ($i = 0; $i < count($type); $i++){
		if ($i == 1){
			$isBackhaul = true;
		}else{
			$isBackhaul = false;
		}
		if ($isBackhaul == true){
			$sql = "select commodity_code, sum(duration), vessel_id from hourly_detail
                                where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                                employee_id not in (select user_id from lcs_user)
                                group by commodity_code, vessel_id";
		}else{
                        $sql = "select commodity_code, sum(duration) from hourly_detail
                                where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and
                                employee_id not in (select user_id from lcs_user)
                                group by commodity_code";
		}
                $statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
                while(ora_fetch($cursor_lcs)){

                        $comm = ora_getcolumn($cursor_lcs, 0);
                        $hours = ora_getcolumn($cursor_lcs, 1);
			if ($isBackhaul == true){
				$vessel_id = ora_getcolumn($cursor_lcs, 2);
			}else{
				$vessel_id = "";
			}
			if ($isBackhaul == true){
                                $sql = "select count(*) from $productivity
                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
                                        service_type = '$type[$i]' and commodity = '$comm' and vessel = '$vessel_id'";
			}else{
                        	$sql = "select count(*) from $productivity
                                	where date_time = to_date('$vDate','mm/dd/yyyy') and
                                	service_type = '$type[$i]' and commodity = '$comm'";
                        }
			$statement = ora_parse($cursor_bni, $sql);
                        ora_exec($cursor_bni);
                        $pRow = 0;
                        if (ora_fetch($cursor_bni)){
                                $pRow = ora_getcolumn($cursor_bni, 0);
                        }
                        if ($pRow ==0){
				if($isBackhaul == true){
                                        $sql = "insert into $productivity (date_time, service_type,commodity,vessel, hours) 
						values
                                                (to_date('$vDate','mm/dd/yyyy'), '$type[$i]','$comm', '$vessel_id', $hours )";
				}else{
                                	$sql = "insert into $productivity (date_time, service_type,commodity, hours) values
                                        	(to_date('$vDate','mm/dd/yyyy'), '$type[$i]','$comm', $hours )";
				}
                                $statement = ora_parse($cursor_bni, $sql);
                                ora_exec($cursor_bni);
                        }else if ($pRow == 1){
				if($isBackhaul == true){
                                	$sql = "update $productivity set
                                        	hours = $hours
                                        	where date_time = to_date('$vDate','mm/dd/yyyy') and
                                        	service_type = '$type[$i]' and commodity = '$comm' and vessel = '$vessel_id'";
                                }else{
				        $sql = "update $productivity set
                                                hours = $hours
                                                where date_time = to_date('$vDate','mm/dd/yyyy') and
                                                service_type = '$type[$i]' and commodity = '$comm'";
				}
                                $statement = ora_parse($cursor_bni, $sql);
                                ora_exec($cursor_bni);
                        }else{
				if($isBackhaul == true){
                                	$sql = "select sum(tonnage) from $productivity
                                        	where date_time = to_date('$vDate','mm/dd/yyyy') and
                                        	service_type = '$type[$i]' and commodity = '$comm' and vessel = '$vessel_id'";
				}else{
                                        $sql = "select sum(tonnage) from $productivity
                                                where date_time = to_date('$vDate','mm/dd/yyyy') and
                                                service_type = '$type[$i]' and commodity = '$comm'";
				}
                                $statement = ora_parse($cursor_bni2, $sql);
                                ora_exec($cursor_bni2);
                                if (ora_fetch($cursor_bni2)){
                                        $tWeight = ora_getcolumn($cursor_bni2, 0);
                                }else{
                                        $tWeight = 0;
                                }
				if($isBackhaul == true){
                                        $sql = "select unit, tonnage from $productivity
                                                where date_time = to_date('$vDate','mm/dd/yyyy') and
                                                service_type = '$type[$i]' and commodity = '$comm' and vessel = '$vessel_id'";
				}else{
	                                $sql = "select unit, tonnage from $productivity
        	                                where date_time = to_date('$vDate','mm/dd/yyyy') and
        	                                service_type = '$type[$i]' and commodity = '$comm'";
				}
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
					if($isBackhaul == true){
                                        	$sql = "update $productivity set
                                                	hours = $uHours
                                                	where date_time = to_date('$vDate','mm/dd/yyyy') and
                                                	service_type='$type[$i]' and commodity='$comm' and 
							vessel = '$vessel_id' and unit='$unit'";
					}else{
                                                $sql = "update $productivity set
                                                        hours = $uHours
                                                        where date_time = to_date('$vDate','mm/dd/yyyy') and
                                                        service_type='$type[$i]' and commodity='$comm' and unit='$unit'";
					}
                                        $statement = ora_parse($cursor_bni, $sql);
                                        ora_exec($cursor_bni);
                                }
                        }
                }
                //get hire_plan

                $sql = "select nvl(commodity, 0), sum(num_of_hire), sum(tot_hours), sum(plts)
                        from hire_plan
                        where hire_date = to_date('$vDate','mm/dd/yyyy') and type='$type[$i]'
                        group by nvl(commodity, 0)";
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
	
?>
