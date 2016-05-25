<?
/*	---Original Author Unknown---
*
*	HUGE UPDATE, June 2008
*
*	The program is being modified to split repair and lease
*	data into 2 columns.  However, due to its very slow nature,
*	I am also gutting 1/2 the code (the pdf parts), such that anyone
*	Who now tries to view the webpage-half of this will jsut get
*	A default message.  If someone wants the report, they should
*	Check their email.
************************************************************************/

if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   include("pow_session.php");
   $user = $userdata['username'];
}

   include("connect_data_warehouse.php");


   $ccds_tier = 2;
   $rf_tier = 2;
   $bni_tier = 3;

   $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
   }

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
       	exit;
   }
   $cursor = ora_open($conn);
	$whse_cursor_bni = ora_open($conn);
	$box_cursor_bni = ora_open($conn);
	$Short_Term_Cursor_BNI = ora_open($conn);
	$insert_cursor_bni = ora_open($conn);

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		exit;
	}
	$cursor_rf = ora_open($conn2);
	$Short_Term_Cursor_RF = ora_open($conn2);

   $factor = $HTTP_POST_VARS["factor"];
   if($factor == ""){$factor=29;}
   $run_date = $HTTP_POST_VARS["run_date"];
   $pretty_today = date('m/d/Y', strtotime($run_date));
   $today = date('Y-m-d', strtotime($run_date));

   $now = date('m/d/Y');
   $more_now = date('m/d/Y H:i');


   //check if the report is confirmed
   $sql = "select * from utilization_confirm where report_date = '$today'";
   $result = pg_query($pg_conn, $sql); 
   $rows = pg_num_rows($result); 
   if ($rows > 0){ 
      	$isConfirm = true;
   }else{ 
      	$isConfirm = false; 
   }

   if ($isConfirm){
     	$msg = "This report was confirmed by OPS";
   }else{
       	$msg = "This report is not confirmed by OPS";
   }

   // Report headers
   $bub = "Ocupied Space (Sq.Ft.)";
   $fplts = "C\nPallets on the Floor\n(A / B)";
   $plts = "Pallets ";
   $tier = "B\nTier Height";

   // Added Adam Walter, Aug 2009.  And now, a breakdown by box.
   // this MAY OR MAY NOT exactly match up the the above numbers due to leased/repaired boxes, or pallets in crazy locations.
   // I AM NOT GOING TO THE PG DATABASE FOR THIS DANGIT, IT TAKES HOURS TO RETRIEVE.
	$sql = "SELECT COUNT(*) THE_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."'";
	ora_parse($Short_Term_Cursor_BNI, $sql);
	ora_exec($Short_Term_Cursor_BNI);
	ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($Short_Term_Row_BNI['THE_COUNT'] == 0){
		// if there are NO records for this date, then we need to populate the table.  if there are, then we do NOT re-populate it.
		$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
		ora_parse($whse_cursor_bni, $sql);
		ora_exec($whse_cursor_bni);
		while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$whse_total = 0;
			$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING WHERE UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE']."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse_row['WAREHOUSE']."%' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND DATE_RECEIVED > (SYSDATE - (365 * 2)) AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
			ora_parse($Short_Term_Cursor_BNI, $sql);
			ora_exec($Short_Term_Cursor_BNI);
			ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$whse_total += $Short_Term_Row_BNI['THE_SUM'];

			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
					((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
						OR
					(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
					AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
					AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
					AND ARRIVAL_NUM != '4321'
					AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE']."%'";
			ora_parse($Short_Term_Cursor_RF, $sql);
			ora_exec($Short_Term_Cursor_RF);
			ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$whse_total += $Short_Term_Row_RF['THE_COUNT'];

			$whse_minus_boxes_total = $whse_total;

			$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT) VALUES (SYSDATE, '".$whse_row['WAREHOUSE']."Total', '".round($whse_total)."')";
			ora_parse($insert_cursor_bni, $sql);
			ora_exec($insert_cursor_bni);

			for($i = 1; $i < 9; $i++){
				$box_total = 0;

				$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING WHERE UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE'].$i."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse_row['WAREHOUSE'].$i."%' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND DATE_RECEIVED > (SYSDATE - (365 * 2)) AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
				ora_parse($Short_Term_Cursor_BNI, $sql);
				ora_exec($Short_Term_Cursor_BNI);
				ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$box_total += $Short_Term_Row_BNI['THE_SUM'];

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
						((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
							OR
						(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
						AND ARRIVAL_NUM != '4321'
						AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE'].$i."%'";
				ora_parse($Short_Term_Cursor_RF, $sql);
				ora_exec($Short_Term_Cursor_RF);
				ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$box_total += $Short_Term_Row_RF['THE_COUNT'];

				$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT) VALUES (SYSDATE, '".$whse_row['WAREHOUSE'].$i."', '".round($box_total)."')";
				ora_parse($insert_cursor_bni, $sql);
				ora_exec($insert_cursor_bni);

				$whse_minus_boxes_total -= $box_total;
			}

			$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT) VALUES (SYSDATE, '".$whse_row['WAREHOUSE']."Other', '".round($whse_minus_boxes_total)."')";
			ora_parse($insert_cursor_bni, $sql);
			ora_exec($insert_cursor_bni);
		}
	}



//   $arrHeading = array('sq_feet'=>' Total','leased'=>'Leased/Repair','net_sq_feet'=>'Net','plts'=>'Occupied', 'net_percent'=>'Net','percent'=>'Gross');
/*  $arrCol = array('sq_feet'=>array('width'=>100, 'justification'=>'center'),
                   'plts'=>array('width'=>80, 'justification'=>'center'),
                   'leased'=>array('width'=>100, 'justification'=>'center'),
		   'net_sq_feet'=>array('width'=>80, 'justification'=>'center'),
		   'net_percent'=>array('width'=>80, 'justification'=>'center'),
                   'percent'=>array('width'=>80,'justification'=>'center')); */

//   $arrHeading2 = array('net_sq_feet'=>'Net','plts'=>'in Inventory', 'max_positions'=>'Max','floor_positions'=>'Used','empty_floor_positions'=>'Available', 'net_percent'=>'Net');
/*   $arrCol2 =array('net_sq_feet'=>array('width'=>100, 'justification'=>'center'),
                   'max_positions'=>array('width'=>80, 'justification'=>'center'),
                   'plts'=>array('width'=>100, 'justification'=>'center'),
                   'floor_positions'=>array('width'=>80, 'justification'=>'center'),
                   'net_percent'=>array('width'=>80, 'justification'=>'center'),
                   'empty_floor_positions'=>array('width'=>80,'justification'=>'center')); */

//   $arrHeading3 = array('sq_feet'=>' Sq. Ft.','utl'=>'Utilization');
/*   $arrCol3 = array('sq_feet'=>array('width'=>360, 'justification'=>'center'),
                   'utl'=>array('width'=>160,'justification'=>'center')); */

//   $arrHeading4 = array('sq_feet'=>' Sq. Ft.','plt'=>'Pallets','positions'=>'Floor Positions','utl'=>'Utilization');
/*   $arrCol4 = array('sq_feet'=>array('width'=>100, 'justification'=>'center'),
		   'plt'=>array('width'=>100, 'justification'=>'center'),
		   'positions'=>array('width'=>240, 'justification'=>'center'),
                   'utl'=>array('width'=>80,'justification'=>'center')); */

   $data = array();
   $data2 = array();
   $tot_data = array();
   $tot_data2 = array();

   $reefer_total_size = 0;
   $reefer_total_bubble = 0;
   $reefer_total_percent = 0;
   $reefer_total_pallets = 0;
   $reefer_total_floor_pallets = 0;
   $reefer_total_net_sq_feet = 0;
   $reefer_total_floor_positions = 0;

   // get Monday and Saturday
   $rTime = strtotime($run_date);
   $rDate=getdate($rTime);
   $wday = $rDate['wday'];

   $last_of_mon = mktime(0,0,0,date("m",strtotime($run_date)),date("d",strtotime($run_date)) - ($wday-1)-7, date("Y",strtotime($run_date)));
   $last_of_sat = mktime(0,0,0,date("m",strtotime($run_date)),date("d",strtotime($run_date)) - ($wday-1)-1, date("Y",strtotime($run_date)));
   $lMonday = date("m/d/Y", $last_of_mon);
   $lSaturday = date("m/d/Y", $last_of_sat);

   /*
   $sql="select w.warehouse, sq_feet, leased, qty, sqft from 
	(select warehouse, sum(sq_feet) as sq_feet from warehouse_box_detail group by warehouse) w full join
	(select warehouse, sum(qty) as qty, sum(sqft) as sqft from 
	((select warehouse, sum(subst_vals(stacking, 't', qty /2, qty)) *1.0 as qty, 
	sum(subst_vals(stacking, 't', qty/2.0, qty))*$factor/$ccds_tier as sqft 
	from cargo_detail left outer join warehouse  on location like warehouse ||'%'  
	where cargo_system ='CCDS'  and  run_date ='$today'  and date_received < run_date  and qty_unit = 'PLT' 
	group by warehouse )  
	union all 
	(select warehouse, sum(qty)*1.0, sum(qty)*$factor/$bni_tier 
	from cargo_detail left outer join warehouse  on location like  'WING ' || warehouse ||'%' 
	where  cargo_system = 'BNI' and run_date ='$today' and date_received < run_date and qty_unit = 'PLT' 
	group by warehouse)  
	union all 
	(select warehouse, count(*)*1.0, sum(subst_vals(stacking, 'S', qty/qty, qty/qty/$rf_tier))*$factor 
	from cargo_detail left outer join warehouse  on location like warehouse ||'%'  
	where cargo_system = 'RF' and qty >= 10 and run_date  ='$today' and date_received <run_date and qty_unit = 'PLT' 
	group by warehouse)) a group by warehouse) c
	on w.warehouse = c.warehouse left outer join
	(select d.warehouse, sum(sq_feet) as leased  from warehouse_box_detail d, warehouse_lease l 
	where l.start_date <= '$today' and (l.end_date is null or l.end_date >='$today')  and 
	d.warehouse = l.warehouse and d.box = l.box 
	group by d.warehouse) ls on w.warehouse = ls.warehouse
	order by w.warehouse"; 

   $sql="select w.warehouse, sq_feet, leased, qty, sqft from
        (select warehouse, sum(sq_feet) as sq_feet from warehouse_box_detail group by warehouse) w full join
        (select warehouse, sum(qty) as qty, sum(sqft) as sqft from
        ((select warehouse, sum(subst_vals(stacking, 't', qty /2, qty)) *1.0 as qty,
        sum(subst_vals(stacking, 't', qty/2.0, qty))*$factor/$ccds_tier as sqft
        from cargo_detail left outer join warehouse  on location like warehouse ||'%'
        where cargo_system ='CCDS'  and  run_date ='$today'  and date_received < run_date  and qty_unit = 'PLT'
        group by warehouse )
        union all
        (select warehouse, sum(subst_vals(qty_unit, 'DRUM', qty/4.0, qty))*1.0, 
	sum(subst_vals(qty_unit, 'DRUM', qty/4.0, qty))*$factor/$bni_tier
        from cargo_detail left outer join warehouse  on location like  'WING ' || warehouse ||'%'
        where  cargo_system = 'BNI' and run_date ='$today' and date_received < run_date and qty_unit in ('PLT', 'BIN','DRUM')
	and substring(location  from 1 for 7) not in 
	(select 'WING '||warehouse||box from warehouse_lease 
	where start_date <= '$today' and (end_date is null or end_date >='$today'))  
        group by warehouse)
        union all
        (select warehouse, count(*)*1.0, sum(subst_vals(stacking, 'S', qty/qty, qty/qty/$rf_tier))*$factor
        from cargo_detail left outer join warehouse  on location like warehouse ||'%'
        where cargo_system = 'RF' and qty >= 10 and run_date  ='$today' and date_received <run_date and qty_unit = 'PLT'
        group by warehouse)) a group by warehouse) c
        on w.warehouse = c.warehouse left outer join
        (select d.warehouse, sum(sq_feet) as leased  from warehouse_box_detail d, warehouse_lease l
        where l.start_date <= '$today' and (l.end_date is null or l.end_date >='$today')  and
        d.warehouse = l.warehouse and d.box = l.box
        group by d.warehouse) ls on w.warehouse = ls.warehouse
        order by w.warehouse";
   */

   $sql="select w.warehouse, sq_feet, leased, qty, sqft, repair from
        (select warehouse, sum(sq_feet) as sq_feet from warehouse_box_detail group by warehouse) w full join
        (select warehouse, sum(qty) as qty, sum(sqft) as sqft from
        ((select warehouse, sum(subst_vals(stacking, 't', qty /2, qty)) *1.0 as qty,
        sum(subst_vals(stacking, 't', qty/2.0, qty))*$factor/$ccds_tier as sqft
        from cargo_detail left outer join warehouse  on location like warehouse ||'%'
        where cargo_system ='CCDS'  and  run_date ='$today'  and date_received < run_date  and qty_unit = 'PLT'
        group by warehouse )
        union all
        (select warehouse, sum(subst_vals(qty_unit, 'DRUM', qty/4.0, qty))*1.0, 
        sum(subst_vals(qty_unit, 'DRUM', qty/4.0, subst_vals(commodity_code ,5602,qty*$bni_tier, qty)))*$factor/$bni_tier
        from cargo_detail left outer join warehouse  on upper(location) like  'WING ' || warehouse ||'%' or location like warehouse ||'%'
        where  cargo_system = 'BNI' and run_date ='$today' and date_received < run_date and qty_unit in ('PLT', 'BIN','DRUM','PLTS' )
        and substring(location  from 1 for 7) not in 
        (select 'WING '||warehouse||box from warehouse_lease 
        where start_date <= '$today' and (end_date is null or end_date >='$today'))  
        group by warehouse)
        union all
        (select warehouse, count(*)*1.0, sum(subst_vals(stacking, 'S', qty/qty, qty/qty/$rf_tier))*$factor
        from cargo_detail left outer join warehouse  on location like warehouse ||'%'
        where cargo_system = 'RF' and qty >= 10 and run_date  ='$today' and date_received <run_date and qty_unit = 'PLT'
        group by warehouse)) a group by warehouse) c
        on w.warehouse = c.warehouse left outer join
        (select d.warehouse, sum((case when upper(customer) = 'POW REPAIR' then 0 else sq_feet end)) as leased, sum((case when upper(customer) = 'POW REPAIR' then sq_feet else 0 end)) as repair from warehouse_box_detail d, warehouse_lease l
        where l.start_date <= '$today' and (l.end_date is null or l.end_date >='$today')  and
        d.warehouse = l.warehouse and d.box = l.box
        group by d.warehouse) ls on w.warehouse = ls.warehouse
        order by w.warehouse";

   $wh_result = pg_query($pg_conn, $sql);
   $warehouses = pg_num_rows($wh_result);

   for($i = 0; $i < $warehouses; $i++){
         
      $row = pg_fetch_row($wh_result, $i);
      $warehouse = $row[0];
      $total_size = $row[1];
      $total_leased_size = $row[2];
      $total_plts = round($row[3]);
      $total_bubble = round($row[4]);
	  $total_repair_size = $row[5];
    
      $net_sq_feet = $total_size-$total_leased_size;
      
      if ($net_sq_feet > 0){
	  $net_percent = ($total_bubble / $net_sq_feet)* 100;
          $total_percent = (($total_bubble + $total_leased_size) / $total_size) * 100;
      }else{
          $net_percent = 100;
	  $total_percent = 100;
      }
	
      $whse_floor_positions = round($net_sq_feet / $factor);
      $total_floor_pallets = round($total_bubble / $factor);

      $tot_plts +=$total_plts;
      if ($i>4 && $warehouse != "H"){
		if ($warehouse =="F"){
			$t_f_pallets += $total_plts;
		} elseif ($warehouse =="G"){
			$t_g_pallets += $total_plts;
		}else{
			$other_pallets +=$total_plts;
		}

	  continue;
      }

      $reefer_total_size += $total_size;
      $reefer_total_leased_size += $total_leased_size;
      $reefer_total_bubble += $total_bubble;
      $reefer_total_net_sq_feet += $net_sq_feet;
      $reefer_total_net_percent += ($reefer_total_bubble / $reefer_total_net_sq_feet) * 100;
      $reefer_total_floor_positions += $whse_floor_positions;
      $reefer_total_pallets += $total_plts;
      $reefer_total_floor_pallets += $total_floor_pallets;
	  $reefer_total_repair_size += $total_repair_size;
     
      
      array_push($data, array('sq_feet'=>$warehouse."-".number_format($total_size, 0, '.', ','),
                              'leased'=>number_format($total_leased_size, 0, '.', ','),
                              'repair'=>number_format($total_repair_size, 0, '.', ','),
                              'plts'=>number_format($total_bubble, 0, '.', ','),
                              'net_sq_feet'=>number_format($net_sq_feet, 0, '.', ','),
                              'net_percent'=>number_format($net_percent, 0, '.', ',')."%",
                              'percent'=>number_format($total_percent,0,'.',',')."%")); 

      array_push($data2, array('net_sq_feet'=>$warehouse."-".number_format($net_sq_feet, 0, '.', ','),
                              'max_positions'=>number_format($whse_floor_positions, 0, '.', ','),
                              'plts'=>number_format($total_plts, 0, '.', ','),
                              'floor_positions'=>number_format($total_floor_pallets, 0, '.', ','),
                              'net_percent'=>number_format($net_percent, 0, '.', ',')."%",
                              'empty_floor_positions'=>number_format(($whse_floor_positions-$total_floor_pallets),0,'.',','))); 

   }

   if ($reefer_total_net_sq_feet > 0){
   	$reefer_total_net_percent = ($reefer_total_bubble / $reefer_total_net_sq_feet) * 100;
	$reefer_total_leased_percent = ($reefer_total_leased_size / $reefer_total_size) * 100;
	$reefer_total_repair_percent = ($reefer_total_repair_size / $reefer_total_size) * 100;
	$reefer_total_occupied_percent = ($reefer_total_bubble / $reefer_total_size) * 100;
   	$reefer_total_percent = (($reefer_total_bubble + $reefer_total_leased_size) / $reefer_total_size) * 100;

   }else{
        $reefer_total_net_percent = 100;
		$reefer_total_occupied_percent = 100;
		$reefer_total_leased_percent = 100;
        $reefer_total_percent = 100;
		$reefer_total_repair_percent = 100;
   }

   array_push($tot_data, array(
                               'sq_feet'=>'Total-'.number_format($reefer_total_size, 0, '.', ','),
                               'leased'=>number_format($reefer_total_leased_size, 0, '.', ','),
                               'repair'=>number_format($reefer_total_repair_size, 0, '.', ','),
                               'plts'=>number_format($reefer_total_bubble, 0, '.', ','),
                               'net_sq_feet'=>number_format($reefer_total_net_sq_feet, 0, '.', ','),
                               'net_percent'=>number_format($reefer_total_net_percent, 0, '.', ',')."%",
                               'percent'=>number_format($reefer_total_percent,0,'.',',')."%")); 

   array_push($tot_data2, array(
                              'net_sq_feet'=>'Total-'.number_format($reefer_total_net_sq_feet, 0, '.', ','),
                              'max_positions'=>number_format($reefer_total_floor_positions, 0, '.', ','),
                              'plts'=>number_format($reefer_total_pallets, 0, '.', ','),
                              'floor_positions'=>number_format($reefer_total_floor_pallets, 0, '.', ','),
                              'net_percent'=>number_format($reefer_total_net_percent, 0, '.', ',')."%",
                              'empty_floor_positions'=>number_format($reefer_total_floor_positions - $reefer_total_floor_pallets, 0,'.',','))); 

   $t_other_pallets = $tot_plts - $reefer_total_pallets;

   $total_other_detail_string = "";
   if ($t_f_pallets > 0) {
    	$total_other_detail_string = $t_f_pallets." in F";
   }

   if ($t_g_pallets > 0) {
   	if ($total_other_detail_string ==""){
     		$total_other_detail_string .= $t_g_pallets." in G";
   	}else{
   		$total_other_detail_string .= ", ".$t_g_pallets." in G";
        }
   }
   if ($other_pallets > 0) {
      	if ($total_other_detail_string ==""){
             	$total_other_detail_string .= $other_pallets." in NO LOC";
        }else{
             	$total_other_detail_string .= ", ".$other_pallets." in NO LOC";
       	}
  }
  $total_other_string = $t_other_pallets."  Plt(s) in other locations (".$total_other_detail_string.")";

  $leased_data = array();

  $whse_sql = "select d.warehouse, d.box, d.sq_feet, l.customer from warehouse_box_detail d, warehouse_lease l where d.warehouse = l.warehouse and d.box = l.box and l.start_date <= '$today' and (l.end_date is null or l.end_date >='$today') order by d.warehouse, d.box";
  $size_result = pg_query($pg_conn, $whse_sql);
  $boxes = pg_num_rows($size_result);
  // For each box in the warehouse - hope to expand this later
  for($x = 0; $x < $boxes; $x++){
 	$temp = pg_fetch_row($size_result, $x);
        //$box = "Box " . $temp[1];
        $leased_box_size = $temp[2];
        $total_leased_size += $leased_box_size;

        array_push($leased_data, array('cust'=>$temp[3].' -- '.$temp[0].$temp[1].' -- '.number_format($temp[2], 0, '.', ',').' Sq. Ft.'));
   }

	// Added Adam Walter, Sept 26, 2007.
   $paper_rolls_sql = "select qty from cargo_detail where run_date = '$today' and date_received < run_date and qty_unit = 'ROLL' and (location like 'F%' or location like 'WING F%')";
   $paper_results = pg_query($pg_conn, $paper_rolls_sql);
   $paper_result_count = pg_num_rows($paper_results);
   $paper_rolls_count_f = 0;
   for($y = 0; $y < $paper_result_count; $y++){
	   $temp = pg_fetch_row($paper_results, $y);
	   $amount = $temp[0];
	   $paper_rolls_count_f += $amount;
   }

   // Added Adam Walter, Aug 2009.  And now, a breakdown by box.
   // this MAY OR MAY NOT exactly match up the the above numbers due to leased/repaired boxes, or pallets in crazy locations.
   // I AM NOT GOING TO THE PG DATABASE FOR THIS DANGIT, IT TAKES HOURS TO RETRIEVE.
	$box_util_email_textstring = "<table border = 1 width=550 cellspacing=1 cellpadding=1>\r\n";
	$box_util_email_textstring .= "<tr><td>&nbsp;</td><td align=center colspan=10><b>Box Breakdown</b></td></tr>\r\n";
	$box_util_email_textstring .= "<tr><td align=center width=55>Box</td><td align=center width=55>Total</td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>1</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>2</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>3</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>4</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>5</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>6</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>7</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>8</b></td>\r\n";
	$box_util_email_textstring .= "<td align=center width=55><b>Other</b></td></tr>\r\n";

	$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
	ora_parse($whse_cursor_bni, $sql);
	ora_exec($whse_cursor_bni);
	while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$box_util_email_textstring .= "<tr><td>".$whse_row['WAREHOUSE']."</td>";

		$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."' AND WAREHOUSE = '".$whse_row['WAREHOUSE']."Total'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$box_util_email_textstring .= "<td>".$Short_Term_Row_BNI['PALLET_COUNT']."</td>";

		for($i = 1; $i < 9; $i++){
			$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."' AND WAREHOUSE = '".$whse_row['WAREHOUSE'].$i."'";
			ora_parse($Short_Term_Cursor_BNI, $sql);
			ora_exec($Short_Term_Cursor_BNI);
			ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$box_util_email_textstring .= "<td>".$Short_Term_Row_BNI['PALLET_COUNT']."</td>";
		}

		$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."' AND WAREHOUSE = '".$whse_row['WAREHOUSE']."Other'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$box_util_email_textstring .= "<td>".$Short_Term_Row_BNI['PALLET_COUNT']."</td></tr>";
	}



/*
	$box_breakdown_array = array();

	$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
	ora_parse($whse_cursor_bni, $sql);
	ora_exec($whse_cursor_bni);
	while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$whse_total = 0;
		$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING WHERE UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE']."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse_row['WAREHOUSE']."%' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND DATE_RECEIVED > (SYSDATE - (365 * 2)) AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$whse_total += $Short_Term_Row_BNI['THE_SUM'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
				((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
					OR
				(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
				AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
				AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
				AND ARRIVAL_NUM != '4321'
				AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE']."%'";
		ora_parse($Short_Term_Cursor_RF, $sql);
		ora_exec($Short_Term_Cursor_RF);
		ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$whse_total += $Short_Term_Row_RF['THE_COUNT'];

		$whse_minus_boxes_total = $whse_total;

		$box_util_email_textstring .= "<tr><td align=center >".$whse_row['WAREHOUSE']."</td>\r\n";
		$box_util_email_textstring .= "<td align=center >".number_format(round($whse_total))."</td>\r\n";


		for($i = 1; $i < 9; $i ++){
			$sql = "SELECT WAREHOUSE || BOX THE_BOX FROM WAREHOUSE_BOX_DETAIL WHERE WAREHOUSE = '".$whse_row['WAREHOUSE']."' AND BOX = '".$i."'";
			ora_parse($box_cursor_bni, $sql);
			ora_exec($box_cursor_bni);
			if(!ora_fetch_into($box_cursor_bni, $box_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$box_util_email_textstring .= "<td  align=center>0</td>\r\n";
			} else {
				$box_total = 0;

				$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING WHERE UPPER(WAREHOUSE_LOCATION) LIKE '".$box_row['THE_BOX']."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$box_row['THE_BOX']."%' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND DATE_RECEIVED > (SYSDATE - (365 * 2)) AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
				ora_parse($Short_Term_Cursor_BNI, $sql);
				ora_exec($Short_Term_Cursor_BNI);
				ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$box_total += $Short_Term_Row_BNI['THE_SUM'];

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
						((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
							OR
						(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
						AND ARRIVAL_NUM != '4321'
						AND UPPER(WAREHOUSE_LOCATION) LIKE '".$box_row['THE_BOX']."%'";
				ora_parse($Short_Term_Cursor_RF, $sql);
				ora_exec($Short_Term_Cursor_RF);
				ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$box_total += $Short_Term_Row_RF['THE_COUNT'];

				$whse_minus_boxes_total -= $box_total;

				$box_util_email_textstring .= "<td align=center >".number_format(round($box_total))."</td>\r\n";
			}
		}

		$box_util_email_textstring .= "<td align=center >".number_format(round($whse_minus_boxes_total))."</td></tr>\r\n";
	}
*/
	$box_util_email_textstring .= "</table>\r\n";



   $heading = array();
   $heading2 = array();
   $heading3 = array();
   $heading4 = array();

   array_push($heading, $arrHeading);
   array_push($heading2, $arrHeading2);
   array_push($heading3, $arrHeading3);
   array_push($heading4, $arrHeading4);

   $leased_heading = array();
   array_push($leased_heading, $leased_arrHeading);

   if($HTTP_SERVER_VARS["argv"][1] <>  "email"){ /* COMMENTING OUT, June 2008, check notes at top of script.
   	// initiate the pdf writer
   	include 'class.ezpdf.php';
   	$pdf = new Cezpdf('letter','portrait');

   	$pdf->ezSetMargins(40,40,50,40);
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
  	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   	$pdf->setFontFamily('Helvetica.afm', $tmp);

   	// Write out the intro.
   	// Print Receiving Header
   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<b>Reefer Warehouse Utilization</b>", 24, $center);
   	$pdf->ezSetDy(-15);
   	$pdf->ezText("<i>As of $pretty_today 12:01 AM\nAt $factor Sq.Ft./Pallet</i>", 12, $center);
   	$pdf->ezSetDy(-15);

   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');

        $pdf->ezTable($heading3, $arrHeading3, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>555,'fontSize'=>12, 'cols'=>$arrCol3));
   	$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>555,'fontSize'=>12, 'cols'=>$arrCol));
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');


   	$pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));

   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');

   	$pdf->ezTable($tot_data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>580,'fontSize'=>12, 'cols'=>$arrCol));
 

        if ($t_other_pallets > 0) {
                $pdf->addText(70, 500, 10, $total_other_string);
        }


        $pdf->ezSetDy(-40);

        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');


        $pdf->ezTable($heading4, $arrHeading4, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>555,'fontSize'=>12, 'cols'=>$arrCol4));
	$pdf->ezTable($heading2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>555,'fontSize'=>12, 'cols'=>$arrCol2));
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');


        $pdf->ezTable($data2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));

        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');

        $pdf->ezTable($tot_data2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>580,'fontSize'=>12, 'cols'=>$arrCol2));


   	//$pdf->ezTable($last_data, $arrHeading,'', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>1, 'width'=>510, 'fontSize'=>12,'cols'=>$arrCol));

        $pdf->line(46,461,566,461);
        $pdf->line(246,442,486,442);

	$pdf->line(46,461,46,421);
        $pdf->line(146,461,146,421);
        $pdf->line(246,461,246,421);
        $pdf->line(486,461,486,421);
        $pdf->line(566,461,566,421);

        $pdf->line(326,442,326,421);
        $pdf->line(406,442,406,421);
        

	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

	// Added Adam Walter, Sept 26, 2007.
	if($paper_rolls_count_f > 0){
		$pdf->ezSetDy(-20);
		$pdf->addText(60, 290, 10, "Warehouse F paper:  ".$paper_rolls_count_f." Rolls");
	}


        $pdf->ezSetDy(-20);

	$pdf->line(50,300,560,300);
         
        $pdf->addText(60, 290, 10, date('F',  strtotime($run_date) ). " Leases/Repair:");
        
  //$pdf->addText(100, 400 , 10, $leased_data[0][cust]);

        for($i = 0; $i < count($leased_data); $i++){
		$pdf->addText(60, 270-$i * 10 , 8, $leased_data[$i][cust]);
        }

   	$pdf->ezSetDy(-15);
   
   	//$pdf->ezText("<i>*Meat and fruit pallets are double stacked. Juice is triple stacked.</i>",9,$left);
        

//   	$today = date('m/j/y');
//   	$format = "Port of Wilmington, " . $today . " Printed by " . $user;
   	$pdf->line(50,40,560,40);
   	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	//$pdf->line(70,822,578,822);
   	$pdf->addText(60,34,6, $msg);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');

//   	$pdf->ezStream();
        include("redirect_pdf.php"); */
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td align="center">
         <font size="3" face="Verdana">Please check your email for this morning's report.<br>If you did not receive it, please place a request on the Helpdesk to rectify the problem.
         <hr>
      </td>
   </tr>
</table>
<?
		
}else{

 	$mailTO = "lstewart@port.state.de.us,awalter@port.state.de.us";

	$mailTo1 .= "gbailey@port.state.de.us,";
	$mailTo1 .= "fvignuli@port.state.de.us,";
	$mailTo1 .= "ithomas@port.state.de.us,";
	$mailTo1 .= "rhorne@port.state.de.us,";
	$mailTo1 .= "parul@port.state.de.us,";
	$mailTo1 .= "tkeefer@port.state.de.us,";

	$mailTo2 .= "jjaffe@port.state.de.us,";
	$mailTo2 .= "wstans@port.state.de.us,";
	$mailTo2 .= "ddonofrio@port.state.de.us,";
	$mailTo2 .= "vfarkas@port.state.de.us,";
	$mailTo2 .= "ddouglas@port.state.de.us,";
	$mailTo2 .= "martym@port.state.de.us,";
	$mailTo2 .= "amarkow@port.state.de.us,";
	$mailTo2 .= "jharoldson@port.state.de.us,";
	$mailTo2 .= "msimpson@port.state.de.us,";
	$mailTo2 .= "vnbecker@port.state.de.us,";
	$mailTo2 .= "bdempsey@port.state.de.us,";
	$mailTo2 .= "hdadmin@port.state.de.us\r\n";


	$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "lstewart@port.state.de.us,ltreut@port.state.de.us,awalter@port.state.de.us,moslowe@port.state.de.us\r\n"; 
//	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
	$mailheaders .= "Content-Type: text/html\r\n";

	$reefer_total_percent =  number_format($reefer_total_percent,0,'.',',');
	$reefer_total_empty = $reefer_total_size - ($reefer_total_leased_size + $reefer_total_bubble);
	$reefer_total_empty_percent = ($reefer_total_empty / $reefer_total_size) * 100;
	$reefer_total_empty = number_format($reefer_total_empty,0,'.',',');
	$reefer_total_empty_percent = number_format($reefer_total_empty_percent,0,'.',',');
	$reefer_total_bubble = number_format($reefer_total_bubble,0,'.',',');
	$reefer_total_leased_size = number_format($reefer_total_leased_size,0,'.',',');
	$reefer_total_leased_percent = number_format($reefer_total_leased_percent,0,'.',',');
	$reefer_total_net_percent = number_format($reefer_total_net_percent,0,'.',',');
	$reefer_total_net_sq_feet = number_format($reefer_total_net_sq_feet,0,'.',',');
	$reefer_total_occupied_percent = number_format($reefer_total_occupied_percent,0,'.',',');
	$reefer_total_repair_size = number_format($reefer_total_repair_size,0,'.',',');
	$reefer_total_repair_percent = number_format($reefer_total_repair_percent,0,'.',',');


   	$mailsubject = "Reefer Whse Utilization:  $reefer_total_percent% - Leased $reefer_total_leased_percent% ($reefer_total_leased_size sf)  Repair $reefer_total_repair_percent% ($reefer_total_repair_size sf) Occupied $reefer_total_occupied_percent% ($reefer_total_bubble sf)  Empty $reefer_total_empty_percent% ($reefer_total_empty sf).  Factor:  $factor sf/plt";

   	$body  = "<html>\r\n";
	$body .= "<body>\r\n";
	$body .= "<table width = 540>\r\n";
	$body .= "<tr><td align=center>\r\n";
	$body .= "<br \><font size = 5 ><b>Reefer Warehouse Utilization</b></font><br \><br \>\r\n";
	$body .= "</td></tr>\r\n";
	$body .= "<tr><td>\r\n";
  	$body .= "<table border = 1  width=580 cellspacing=1 cellpadding=1 bgcolor=#000000>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=400 align=center colspan=5><b>Sq. Ft.</b></td>\r\n";
	$body .= "<td width = 180 align = center colspan= 2><b>Utilization</b></td></tr>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=80 align=center><b>Total<br>T</b></td>\r\n";
	$body .= "<td width=80 align = center><b>Leased<br>L</b></td>\r\n";
	$body .= "<td width=80 align = center><b>Repair<br>R</b></td>\r\n";
	$body .= "<td width=80 align=center><b>Net<br>N</b></td>\r\n";
	$body .= "<td width=80 align=center><b>Occupied<br>O</b></td>\r\n";
//	$body .= "<td width=90 align=center><b>Committed</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Net<br>O / N</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Gross<br>(O + L) / T</b></td></tr>\r\n";
	for($i = 0; $i < count($data); $i++){
		$body .= "<tr bgcolor=#ffffff><td align=center>".$data[$i]["sq_feet"]."</td>\r\n";
			$body .= "<td align=center>".$data[$i]["leased"]."</td>\r\n";
			$body .= "<td align=center>".$data[$i]["repair"]."</td>\r\n";
			$body .= "<td align=center>".$data[$i]["net_sq_feet"]."</td>\r\n";
			$body .= "<td align=center>".$data[$i]["plts"]."</td>\r\n";
			$body .= "<td align=center>".$data[$i]["net_percent"]."</td>\r\n";
			$body .= "<td align=center>".$data[$i]["percent"]."</td></tr>\r\n";
	}
	$body .= "<tr bgcolor=#ffffff><td width=80 align=center><b><i>".$tot_data[0]["sq_feet"]."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".$tot_data[0]["leased"]."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".$tot_data[0]["repair"]."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".$tot_data[0]["net_sq_feet"]."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".$tot_data[0]["plts"]."</i></b></td>\r\n";
	$body .= "<td width=90 align=center><b><i>".$tot_data[0]["net_percent"]."</i></b></td>\r\n";
	$body .= "<td width=90 align=center><b><i>".$tot_data[0]["percent"]."</i></b></td></tr>\r\n";
	$body .= "</table>\r\n";
	if ($t_other_pallets > 0) {
			$body .= "<br \>".$total_other_string."\r\n";
	}        
	$body .= "<br \><br \>\r\n";
	$body .= "</td></tr>\r\n";
	$body .= "<tr><td>\r\n"; 
	$body .= "<table border = 1  width=580 cellspacing=1 cellpadding=1 bgcolor=#000000>\r\n";

	$body .= "<tr bgcolor=#ffffff><td width=110 align=center rowspan=2><b>Sq. Ft. <br \>Net</b></td>\r\n";
	$body .= "<td width = 130 align = center rowspan= 2><b>Pallets in Inventory</b></td>\r\n";
	$body .= "<td width = 270 align = center colspan= 3><b>Floor Positions</b></td>\r\n";
	$body .= "<td width = 90 align = center rowspan= 2><b>Utilization <br \>Net</b></td></tr>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=90 align=center><b>Max</b></td>\r\n";
	$body .= "<td width = 90 align = center><b>Used</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Available</b></td></tr>\r\n";
	for($i = 0; $i < count($data); $i++){
		$body .= "<tr bgcolor=#ffffff><td align=center>".$data2[$i]["net_sq_feet"]."</td>\r\n";
		$body .= "<td align=center>".$data2[$i]["plts"]."</td>\r\n";
		$body .= "<td align=center>".$data2[$i]["max_positions"]."</td>\r\n";
		$body .= "<td align=center>".$data2[$i]["floor_positions"]."</td>\r\n";
		$body .= "<td align=center>".$data2[$i]["empty_floor_positions"]."</td>\r\n";
		$body .= "<td align=center>".$data2[$i]["net_percent"]."</td></tr>\r\n";
	} 
	$body .= "<tr bgcolor=#ffffff><td align=center><b><i>".$tot_data2[0]["net_sq_feet"]."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".$tot_data2[0]["plts"]."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".$tot_data2[0]["max_positions"]."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".$tot_data2[0]["floor_positions"]."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".$tot_data2[0]["empty_floor_positions"]."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".$tot_data2[0]["net_percent"]."</i></b></td></tr>\r\n";
	$body .= "</table>\r\n"; 
	$body .= "</td></tr>\r\n";

  	$body .= "</table>\r\n";

	// Added Adam Walter, Sept 26, 2007.
	if($paper_rolls_count_f > 0){
		$body .= "<br \><font size=3>&nbsp;&nbsp;Warehouse F paper:  ".$paper_rolls_count_f." Rolls\r\n";
	}

	$body .= "<br \><br \><font size=4>&nbsp;&nbsp;".date('F', strtotime($run_date)). " Leases/Repair:</font>\r\n";
        for($i = 0; $i < count($leased_data); $i++){
             	$body .= "<br \><font size = 2>&nbsp;&nbsp;&nbsp;&nbsp;".$leased_data[$i][cust]."</font>\r\r\n";
        }
	$body .= "<br><br>\r\n";

	$body .= $box_util_email_textstring;
     	
	$body .= "</body>\r\n";
	$body .= "</html>\r\n";
  	
        $arg2 = $HTTP_SERVER_VARS["argv"][2];


	if ($arg2 == "8AM" && $isConfirm ==true){
		// NOTE:  the post-report is NOT actually run at 8AM.  It used to be, but was moved, but since it works, I didn't want to change it.
//	   	mail($mailTO, $mailsubject, $body, $mailheaders);
		exit;
	}else if($arg2 <>"" && $arg2<>"8AM"){
	   /* $sql = "INSERT INTO UTILIZATION_HISTORY (\"RUN_DATE\") VALUES (to_date('".$now."', 'MM/DD/YYYY'))";
	   ora_parse($cursor, $sql);
	   ora_exec($cursor); */


		$mailsubject = "(waiting for confirmation) Reefer Whse Utilization:  $reefer_total_percent% - Leased $reefer_total_leased_percent% ($reefer_total_leased_size sf)  Repair $reefer_total_repair_percent% ($reefer_total_repair_size sf) Occupied $reefer_total_occupied_percent% ($reefer_total_bubble sf)  Empty $reefer_total_empty_percent% ($reefer_total_empty sf)   Factor:  $factor sf/plt";
		mail($arg2, $mailsubject, $body, $mailheaders);
		exit;
	}else if($isConfirm == false){
             //   $body = "";

		$mailsubject = "Reefer Whse Utilization:  $reefer_total_percent% - Leased $reefer_total_leased_percent% ($reefer_total_leased_size sf)  Repair $reefer_total_repair_percent% ($reefer_total_repair_size sf) Occupied $reefer_total_occupied_percent% ($reefer_total_bubble sf)  Empty $reefer_total_empty_percent% ($reefer_total_empty sf)   Factor:  $factor sf/plt";
                $mailsubject .= " (Not been confirmed by OPS)";
        }

		$mailTO = "lstewart@port.state.de.us";
        $mailTO = "ithomas@port.state.de.us";

//   	mail($mailTO, $mailsubject, $body, $mailheaders);
		// ugly, I know, but I had to figure out where to stick the update routine for Oracle in here to be logically consistent.

        mail($mailTo1, $mailsubject, $body, $mailheaders);
        mail($mailTo2, $mailsubject, $body, $mailheaders);

		/* $sql = "UPDATE UTILIZATION_HISTORY SET TOTAL = '".round($reefer_total_size)."', LEASED_REPAIR = '".round(str_replace(",","",$reefer_total_leased_size))."', POW_OCCUPIED = '".round(str_replace(",","",$reefer_total_bubble))."', REPORT_TIME = to_date('".$more_now."', 'MM/DD/YYYY HH24:mi') WHERE RUN_DATE = to_date('".$now."', 'MM/DD/YYYY')";
		ora_parse($cursor, $sql);
		ora_exec($cursor); */
		ora_close($cursor);
		ora_commit($conn);
		ora_logoff($conn);
}
pg_close($pg_conn );
?>
