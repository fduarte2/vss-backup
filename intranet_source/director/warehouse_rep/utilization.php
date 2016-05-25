<?

if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   $user = $HTTP_COOKIE_VARS[directoruser];
/*
   if($user == ""){
      header("Location: ../../director_login.php");
      exit;
   }
*/
}
   include("connect_data_warehouse.php");


   $ccds_tier = 2;
   $rf_tier = 2;
   $bni_tier = 3;

   $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
   }
   $factor = $HTTP_POST_VARS["factor"];
   if($factor == ""){$factor=29;}
   $run_date = $HTTP_POST_VARS["run_date"];
   $pretty_today = date('m/d/Y', strtotime($run_date));
   $today = date('Y-m-d', strtotime($run_date));

   //check if the report is confirmed
   $sql = "select * from utilization_confirm where report_date = '$today'";
   $result = pg_query($pg_conn, $sql); 
   $rows = pg_num_rows($result); 
   if ($rows > 0){ 
      	$isConfirm = true;
   }else{ 
      	$isConfirm = false; 
   }


   // Report headers
   $bub = "Ocupied Space (Sq.Ft.)";
   $fplts = "C\nPallets on the Floor\n(A / B)";
   $plts = "Pallets ";
   $tier = "B\nTier Height";


   $arrHeading = array('sq_feet'=>' Total','leased'=>'Leased','net_sq_feet'=>'Net','plts'=>'Occupied', 'net_percent'=>'Net','percent'=>'Gross');
   $arrCol = array('sq_feet'=>array('width'=>100, 'justification'=>'center'),
                   'plts'=>array('width'=>80, 'justification'=>'center'),
                   'leased'=>array('width'=>80, 'justification'=>'center'),
		   'net_sq_feet'=>array('width'=>80, 'justification'=>'center'),
		   'net_percent'=>array('width'=>80, 'justification'=>'center'),
                   'percent'=>array('width'=>80,'justification'=>'center'));

   $arrHeading2 = array('net_sq_feet'=>'Net','plts'=>'in Inventory', 'max_positions'=>'Max','floor_positions'=>'Used','empty_floor_positions'=>'Available', 'net_percent'=>'Net');
   $arrCol2 =array('net_sq_feet'=>array('width'=>100, 'justification'=>'center'),
                   'max_positions'=>array('width'=>80, 'justification'=>'center'),
                   'plts'=>array('width'=>80, 'justification'=>'center'),
                   'floor_positions'=>array('width'=>80, 'justification'=>'center'),
                   'net_percent'=>array('width'=>80, 'justification'=>'center'),
                   'empty_floor_positions'=>array('width'=>80,'justification'=>'center'));

   $arrHeading3 = array('sq_feet'=>' Sq. Ft.','utl'=>'Utilization');
   $arrCol3 = array('sq_feet'=>array('width'=>340, 'justification'=>'center'),
                   'utl'=>array('width'=>160,'justification'=>'center'));

   $arrHeading4 = array('sq_feet'=>' Sq. Ft.','plt'=>'Pallets','positions'=>'Floor Positions','utl'=>'Utilization');
   $arrCol4 = array('sq_feet'=>array('width'=>100, 'justification'=>'center'),
		   'plt'=>array('width'=>80, 'justification'=>'center'),
		   'positions'=>array('width'=>240, 'justification'=>'center'),
                   'utl'=>array('width'=>80,'justification'=>'center'));

   // Find out how many warehouses we have to deal with
   $sql = "select distinct warehouse from warehouse_box_detail order by warehouse";
   $wh_result = pg_query($pg_conn, $sql);
   $warehouses = pg_num_rows($wh_result);
   
   // get Monday and Saturday
   $rTime = strtotime($run_date);
   $rDate=getdate($rTime);
   $wday = $rDate['wday'];

   $last_of_mon = mktime(0,0,0,date("m",strtotime($run_date)),date("d",strtotime($run_date)) - ($wday-1)-7, date("Y",strtotime($run_date)));
   $last_of_sat = mktime(0,0,0,date("m",strtotime($run_date)),date("d",strtotime($run_date)) - ($wday-1)-1, date("Y",strtotime($run_date)));
   $lMonday = date("m/d/Y", $last_of_mon);
   $lSaturday = date("m/d/Y", $last_of_sat);

   // Append BNI Data to the array - All Commodities except for Meat
   $data = array();
   $data2 = array();
   $reefer_total_size = 0;
   $reefer_total_bubble = 0;
   $reefer_total_percent = 0;
   $reefer_total_pallets = 0;
   $reefer_total_floor_pallets = 0;
   $reefer_total_net_sq_feet = 0;
   $reefer_total_floor_positions = 0;

   $reefer_ccds_pallets = 0;
   $reefer_rf_pallets = 0;
   $reefer_bni_pallets = 0;

   $data = array();
   $leased_data = array();
   // For each warehouse
   for($i = 0; $i < $warehouses; $i++){
      $total_size = 0;
      $total_leased_size =0;
      $total_committed_size = 0;
      $temp = pg_fetch_row($wh_result, $i);
      $warehouse = $temp[0];
      
      //warehouse size
      $whse_sql = "select * from warehouse_box_detail where warehouse ='$warehouse'";
      $size_result = pg_query($pg_conn, $whse_sql);
      $boxes = pg_num_rows($size_result);
      // For each box in the warehouse - hope to expand this later
      for($x = 0; $x < $boxes; $x++){
        $temp = pg_fetch_row($size_result, $x);
        $box_size = $temp[2];
        $total_size += $box_size;
      }
      $reefer_total_size += $total_size;

      //lease info
      $whse_sql = "select d.*, l.customer from warehouse_box_detail d, warehouse_lease l where d.warehouse = '$warehouse' and d.warehouse = l.warehouse and d.box = l.box and l.start_date <= '$today' and (l.end_date is null or l.end_date >='$today') order by d.warehouse, d.box"; 
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
      $reefer_total_leased_size += $total_leased_size;

      // For each system
      // CCDS
      $ccds_pallets =0;
      $ccds_floor_pallets =0;
      $ccds_space_taken =0;

      $total_sql = "select sum(qty), stacking from cargo_detail where location like '$warehouse%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'CCDS' and date_received <'$today' group by stacking";
//      $total_sql = "select sum(qty) from cargo_detail where location like '$warehouse%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'CCDS' and date_received <'$today'";
      $pallets_result = pg_query($pg_conn, $total_sql);
      //$ccds_count = pg_num_rows($pallets_result);
      $rows = pg_num_rows($pallets_result);
      for ($y = 0; $y < $rows; $y++){
        $temp = pg_fetch_row($pallets_result, $y);
        $pallets = $temp[0];
        $stacking = $temp[1];

        if($pallets == "")
                $pallets = 0;

        if($stacking == "t"){
                $pallets = round($pallets / 2, 0);
	}
        $ccds_pallets += round($pallets, 0);
        $reefer_ccds_pallests += $ccds_pallets;

	$ccds_floor_pallets += round(($pallets / $ccds_tier), 0);

      }
      $ccds_space_taken = round($ccds_floor_pallets * $factor, 0); // This is the Sq Feet used by pallets

      // RF
      $rf_pallets =0;
      $rf_floor_pallets =0;
      $rf_space_taken =0;
 
      $total_sql = "select count(*), stacking from cargo_detail where location like '$warehouse%' and run_date = '$today' and date_received < '$today' and qty >= 10 and qty_unit = 'PLT' and cargo_system = 'RF' group by stacking";
    $pallets_result = pg_query($pg_conn, $total_sql);

      $rows = pg_num_rows($pallets_result);
      for ($y = 0; $y < $rows; $y++){
      	$temp = pg_fetch_row($pallets_result, $y);
      	$pallets = $temp[0];
        $stacking = $temp[1];

      	if($pallets == "")
        	$pallets = 0;

      	$rf_pallets += round($pallets, 0);
	$reefer_rf_pallests += $rf_pallets;

        if($stacking == "S"){
		$rf_floor_pallets +=round(($pallets), 0);
	}else{
      		$rf_floor_pallets += round(($pallets / $rf_tier), 0);
	}
      }
      	$rf_space_taken = round($rf_floor_pallets * $factor, 0); // This is the Sq Feet used by pallets
   

      // BNI
      $total_sql = "select sum(qty) from cargo_detail where location like 'WING $warehouse%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'BNI'  and date_received <'$today'";

//    $total_sql = "select sum(qty) from cargo_detail where location like '%$warehouse%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'BNI' and qty > 1 and date_received <'$today'";
//      $total_sql = "select sum(qty) from cargo_detail where location like '%$warehouse%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'BNI' and qty > 1 ";
      $pallets_result = pg_query($pg_conn, $total_sql);
      $temp = pg_fetch_row($pallets_result, 0);
      $bni_pallets = $temp[0];
      if($bni_pallets == "")
        $bni_pallets = 0;
      $bni_pallets = round($bni_pallets, 0);
      $rf_bni_pallets += $bni_pallets;

      $bni_floor_pallets = round(($bni_pallets / $bni_tier), 0);
      $bni_space_taken = round($bni_floor_pallets * $factor, 0); // This is the Sq Feet used by pallets

      $total_bubble = round($bni_space_taken + $rf_space_taken + $ccds_space_taken, 0);
      $reefer_total_bubble += $total_bubble;
      
      $net_sq_feet = $total_size - $total_leased_size;
      $reefer_total_net_sq_feet += $net_sq_feet;
     
      $floor_positions = round($net_sq_feet / $factor, 0);
      $reefer_total_floor_positions += $floor_positions; 
      
      
      $total_max_bubble = round($bni_max_space_taken + $rf_max_space_taken + $ccds_max_space_taken, 0);
      $reefer_total_max_bubble += $total_max_bubble;
      $total_min_bubble = round($bni_min_space_taken + $rf_min_space_taken + $ccds_min_space_taken, 0);
      $reefer_total_min_bubble += $total_min_bubble;
      $total_avg_bubble = round($bni_avg_space_taken + $rf_avg_space_taken + $ccds_avg_space_taken, 0);
      $reefer_total_avg_bubble += $total_avg_bubble;



      $total_percent = $bni_percent_used + $rf_percent_used + $ccds_percent_used;
//    $total_percentp = $total_percent . "%";
//      $total_percentp = $total_percent ;
      $total_percentp =  round((($total_bubble+$total_leased_size) / $total_size) * 100, 0);   
      if ($net_sq_feet > 0) {
	$net_percent = round(($total_bubble / $net_sq_feet) * 100, 0);
      }else{
	$net_percent = 100;
      }
      
      $reefer_total_percent += $total_percent;

      $whse_total_pallets = $ccds_pallets + $bni_pallets + $rf_pallets;
      $whse_total_floor_pallets = $ccds_floor_pallets + $bni_floor_pallets + $rf_floor_pallets;

      $reefer_total_pallets += $ccds_pallets + $bni_pallets + $rf_pallets;
      $reefer_total_floor_pallets += $ccds_floor_pallets + $bni_floor_pallets + $rf_floor_pallets;

      $reefer_total_avg_pallets +=$ccds_avg_pallets + $bni_avg_pallets + $rf_avg_pallets;

//      $warehouse = "Wing " . $warehouse;

      array_push($data, array('sq_feet'=>$warehouse."-".number_format($total_size, 0, '.', ','),
			      'leased'=>number_format($total_leased_size, 0, '.', ','), 
			      'plts'=>number_format($total_bubble, 0, '.', ','), 
			      'net_sq_feet'=>number_format($net_sq_feet, 0, '.', ','), 
			      'net_percent'=>number_format($net_percent, 0, '.', ',')."%",
			      'percent'=>$total_percentp."%"));

      array_push($data2, array('net_sq_feet'=>$warehouse."-".number_format($net_sq_feet, 0, '.', ','),
			      'max_positions'=>number_format($floor_positions, 0, '.', ','),
                              'plts'=>number_format($whse_total_pallets, 0, '.', ','),
			      'floor_positions'=>number_format($whse_total_floor_pallets, 0, '.', ','),
                              'net_percent'=>number_format($net_percent, 0, '.', ',')."%",
                              'empty_floor_positions'=>number_format(($floor_positions-$whse_total_floor_pallets),0,'.',',')));

/*
      array_push($data, array('whse'=>$warehouse,
			      'sq_feet'=>number_format($total_size, 0, '.', ','),
                              'leased'=>number_format($total_leased_size, 0, '.', ','),
                              'net_sq_feet'=>number_format($net_sq_feet, 0, '.', ','),
                              'plts'=>number_format($whse_total_pallets, 0, '.', ','),
                              'floor_positions'=>number_format($floor_positions, 0, '.', ','),
                              'percent'=>$total_percentp."%"));
*/
   }  // foreach warehouse

   // A 'totals' Chart
//  $data = array();
   $reefer_total_percent = round((($reefer_total_bubble + $reefer_total_leased_size) / $reefer_total_size) * 100, 0);
//   $reefer_total_percentp = $reefer_total_percent . "%";
   $reefer_total_net_percent = round(($reefer_total_bubble / $reefer_total_net_sq_feet) * 100, 0); 
   $reefer_total_percentp = $reefer_total_percent;
   $reefer_total_max_percentp = round((($reefer_total_max_bubble + $reefer_total_leased_size) / $reefer_total_size) * 100, 0);
   $reefer_total_min_percentp = round((($reefer_total_min_bubble + $reefer_total_leased_size) / $reefer_total_size) * 100, 0);
   $reefer_total_avg_percentp = round((($reefer_total_avg_bubble + $reefer_total_leased_size) / $reefer_total_size) * 100, 0);

   $tot_data = array();
   $tot_data2 = array();

   array_push($tot_data, array(
			       'sq_feet'=>'Total-'.number_format($reefer_total_size, 0, '.', ','), 
			       'leased'=>number_format($reefer_total_leased_size, 0, '.', ','), 
			       'plts'=>number_format($reefer_total_bubble, 0, '.', ','), 
			       'net_sq_feet'=>number_format($reefer_total_net_sq_feet, 0, '.', ','), 
			       'net_percent'=>number_format($reefer_total_net_percent, 0, '.', ',')."%",
			       'percent'=>$reefer_total_percentp."%"));

   array_push($tot_data2, array(
                              'net_sq_feet'=>'Total-'.number_format($reefer_total_net_sq_feet, 0, '.', ','),
                              'max_positions'=>number_format($reefer_total_floor_positions, 0, '.', ','),
                              'plts'=>number_format($reefer_total_pallets, 0, '.', ','),
                              'floor_positions'=>number_format($reefer_total_floor_pallets, 0, '.', ','),
                              'net_percent'=>number_format($reefer_total_net_percent, 0, '.', ',')."%",
                              'empty_floor_positions'=>number_format($reefer_total_floor_positions - $reefer_total_floor_pallets, 0,'.',',')));

/*
   array_push($tot_data, array('whse'=>'Total',
			       'sq_feet'=>number_format($reefer_total_size, 0, '.', ','),
                               'leased'=>number_format($reefer_total_leased_size, 0, '.', ','),
                               'net_sq_feet'=>number_format($reefer_total_net_sq_feet, 0, '.', ','),
                               'plts'=>number_format($reefer_total_pallets, 0, '.', ','),
                               'floor_positions'=>number_format($reefer_total_floor_positions, 0, '.', ','),
                               'percent'=>$reefer_total_percentp."%"));
*/
   //plt in other location
   // CCDS total
   $sql = "select sum(qty), stacking from cargo_detail where run_date = '$today' and date_received < '$today' and qty_unit = 'PLT' and cargo_system = 'CCDS' group by stacking";
   $result = pg_query($pg_conn, $sql);
   $rows = pg_num_rows($result);
   for ($r = 0; $r < $rows; $r++){
        $row = pg_fetch_row($result, $r);
        $pallets = $row[0];
        $stacking = $row[1];

        if($pallets == "")
                $pallets = 0;

        if($stacking == "t"){
                $pallets = round($pallets / 2, 0);
        }

        $t_ccds_pallets += round($pallets, 0);
   }

   //RF     
   $sql = "select count(*) from cargo_detail where run_date = '$today' and qty >= 10 and qty_unit = 'PLT' and date_received <'$today' and cargo_system = 'RF'";
   $result = pg_query($pg_conn, $sql);  
   $row = pg_fetch_row($result, 0);  
   $t_rf_pallets = $row[0];  

   //BNI
   $sql = "select sum(qty) from cargo_detail where run_date = '$today' and date_received < '$today'  and qty_unit = 'PLT' and cargo_system = 'BNI'";
   $result = pg_query($pg_conn, $sql); 
   $row = pg_fetch_row($result, 0); 
   $t_bni_pallets = $row[0];    
   
   $t_pallets = $t_ccds_pallets + $t_rf_pallets + $t_bni_pallets;

   $t_other_pallets = round($t_pallets - $reefer_total_pallets, 0);
   
  
   if ($t_other_pallets > 0) {
	//WING F
        //CCDS
        $sql = "select sum(qty),stacking from cargo_detail where location like 'F%' and run_date = '$today' and date_received < '$today' and qty_unit = 'PLT' and cargo_system = 'CCDS' group by stacking"; 
	$result = pg_query($pg_conn, $sql);
        $rows = pg_num_rows($result);
        for ($r = 0; $r < $rows; $r++){
                $row = pg_fetch_row($result, $r);
                $f_pallets = $row[0];
                $stacking = $row[1];
                if($f_pallets == "") $f_pallets = 0;
		if($stacking == "t") $f_pallets = round ($f_pallets / 2, 0);
                $f_ccds_pallets += round($f_pallets, 0);

               
               	$f_ccds_floor_pallets += round(($f_pallets / $ccds_tier), 0);
        }

        $f_ccds_space_taken = round($f_ccds_floor_pallets * $factor, 0); 

	//RF
	$sql = "select count(*), stacking from cargo_detail where location like 'F%' and run_date = '$today' and date_received <'$today' and qty >= 10 and qty_unit = 'PLT' and cargo_system = 'RF' group by stacking";	
        $result = pg_query($pg_conn, $sql);
        $rows = pg_num_rows($result);
	for ($r = 0; $r < $rows; $r++){
		$row = pg_fetch_row($result, $r);
		$f_pallets = $row[0];
		$stacking = $row[1];

        	if($f_pallets == "")
                	$f_pallets = 0;

        	$f_rf_pallets += round($f_pallets, 0);
        	if($stacking == "S"){
                	$f_rf_floor_pallets +=round(($f_pallets), 0);
       		}else{
                	$f_rf_floor_pallets += round(($f_pallets / $rf_tier), 0);
        	}
      	}       
	$f_rf_space_taken = round($f_rf_floor_pallets * $factor, 0);
	
	//BNI
      	$sql = "select sum(qty) from cargo_detail where location like 'WING F%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'BNI' and date_received <'$today'";
       	$result = pg_query($pg_conn, $sql);
      	$row = pg_fetch_row($result, 0);
      	$f_bni_pallets = $row[0];
      	if($f_bni_pallets == "")
        	$f_bni_pallets = 0;
      	$f_bni_pallets = round($f_bni_pallets, 0);

      	$f_bni_floor_pallets = round(($f_bni_pallets / $bni_tier), 0);
      	$f_bni_space_taken = round($f_bni_floor_pallets * $factor, 0);

	$t_f_pallets = round($f_ccds_pallets + $f_rf_pallets + $f_bni_pallets, 0);

        //WING G
        //CCDS
        $sql = "select sum(qty), stacking from cargo_detail where location like 'G%' and run_date = '$today' and date_received < '$today' and qty_unit = 'PLT' and cargo_system = 'CCDS' group by stacking";
        $result = pg_query($pg_conn, $sql);
        $rows = pg_num_rows($result);
        for ($r = 0; $r < $rows; $r++){
		$row = pg_fetch_row($result, $r);
        	$g_pallets = $row[0];
        	$stacking = $row[1];
	      	if($g_pallets == "") $g_pallets = 0;
		if($stacking == "t") $g_pallets = round($g_pallets / 2, 0);
		$g_ccds_pallets += round($g_pallets, 0);

                $g_ccds_floor_pallets += round(($g_pallets / $ccds_tier), 0);
	}
       
        $g_ccds_space_taken = round($g_ccds_floor_pallets * $factor, 0);
         
        //RF 
        $sql = "select count(*), stacking from cargo_detail where location like 'G%' and run_date = '$today' and date_received <'$today' and qty >= 10 and qty_unit = 'PLT' and cargo_system = 'RF' group by stacking";
        $result = pg_query($pg_conn, $sql);
        $rows = pg_num_rows($result); 
        for ($r = 0; $r < $rows; $r++){ 
                $row = pg_fetch_row($result, $r);
                $g_pallets = $row[0]; 
                $stacking = $row[1]; 
 
                if($g_pallets == "") 
                        $g_pallets = 0; 
 
                $g_rf_pallets += round($g_pallets, 0);
                if($stacking == "S"){ 
                        $g_rf_floor_pallets +=round(($g_pallets), 0);
                }else{ 
                        $g_rf_floor_pallets += round(($g_pallets / $rf_tier), 0);
                } 
        } 
        $g_rf_space_taken = round($g_rf_floor_pallets * $factor, 0);

        //BNI 
        $sql = "select sum(qty) from cargo_detail where location like 'WING G%' and run_date = '$today' and qty_unit = 'PLT' and cargo_system = 'BNI' and date_received <'$today'";
        $result = pg_query($pg_conn, $sql); 
        $row = pg_fetch_row($result, 0); 
        $g_bni_pallets = $row[0]; 
        if($g_bni_pallets == "") 
                $g_bni_pallets = 0; 
        $g_bni_pallets = round($g_bni_pallets, 0); 
 
        $g_bni_floor_pallets = round(($g_bni_pallets / $bni_tier), 0);
        $g_bni_space_taken = round($g_bni_floor_pallets * $factor, 0);

	$t_g_pallets = round($g_ccds_pallets + $g_rf_pallets + $g_bni_pallets, 0);

	//other
        $other_pallets = $t_other_pallets - $t_f_pallets - $t_g_pallets;
	$other_pallets = round($other_pallets, 0);
/*
	//CCDS
	$other_ccds_pallets = $t_ccds_pallets - $reefer_ccds_pallets - $f_ccds_pallets - $g_ccds_pallets;
	if ($other_ccds_pallet > 0 ){
		$other_ccds_floor_pallets = round(($other_ccds_pallet / $ccds_tier), 0);
		$other_ccds_space_taken = round($other_ccds_floor_pallets * $factor, 0);
	}

        //RF 
        $other_rf_pallets = $t_rf_pallets - $reefer_rf_pallets - $f_rf_pallets - $g_rf_pallets;
        if ($other_rf_pallet > 0 ){
                $other_rf_floor_pallets = round(($other_rf_pallet / $rf_ti
er), 0);
                $other_rf_space_taken = round($other_rf_floor_pallets * $fac
tor, 0);
	}

        //BNI
        $other_bni_pallets = $t_bni_pallets - $reefer_bni_pallets - $f_bni_pallets - $g_bni_pallets;
        if ($other_bni_pallet > 0 ){
                $other_bni_floor_pallets = round(($other_bni_pallet / $bni_ti
er), 0);
                $other_bni_space_taken = round($other_bni_floor_pallets * $fac
tor, 0);
	}        
	
	$update_total_percent = round((($reefer_total_bubble + $f_ccds_space_taken + $f_rf_space_taken + $f_bni_space_taken + $g_ccds_space_taken + $g_rf_space_taken + $g_bni_space_taken + $other_ccds_space_taken + $other_rf_space_taken + $other_bni_space_taken) / $reefer_total_size) * 100, 0);
*/
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
  
   }


   $last_data = array();
   array_push($last_data, array('whse'=>'Last Week Average', 'sq_feet'=>number_format($reefer_total_size, 0, '.', ','), 'comm'=>'',
                           'leased'=>number_format($reefer_total_leased_size, 0, '.', ','),
                           'plts'=>number_format($reefer_total_avg_pallets, 0, '.', ','), 'tier'=>'',
                           'bub'=>number_format($reefer_total_bubble, 0, '.', ','),
                           'committed'=>number_format($reefer_total_committed_size, 0, '.', ','),
                           'percent'=>$reefer_total_avg_percentp."%"));

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

   if($HTTP_SERVER_VARS["argv"][1] <>  "email"){ 
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
   	$pdf->ezText("<b>Warehouse Utilization</b>", 24, $center);
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


        $pdf->ezSetDy(-60);

        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');


        $pdf->ezTable($heading4, $arrHeading4, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>555,'fontSize'=>12, 'cols'=>$arrCol4));
	$pdf->ezTable($heading2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>555,'fontSize'=>12, 'cols'=>$arrCol2));
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');


        $pdf->ezTable($data2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));

        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-BoldOblique.afm');

        $pdf->ezTable($tot_data2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>580,'fontSize'=>12, 'cols'=>$arrCol2));


   	//$pdf->ezTable($last_data, $arrHeading,'', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>1, 'width'=>510, 'fontSize'=>12,'cols'=>$arrCol));

        $pdf->line(56,461,556,461);
        $pdf->line(236,442,476,442);

	$pdf->line(56,461,56,421);
        $pdf->line(156,461,156,421);
        $pdf->line(236,461,236,421);
        $pdf->line(476,461,476,421);
        $pdf->line(556,461,556,421);

        $pdf->line(316,442,316,421);
        $pdf->line(396,442,396,421);
        

	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');  


        $pdf->ezSetDy(-20);

	$pdf->line(70,300,530,300);
         
        $pdf->addText(80, 290, 10, date('F',  strtotime($run_date) ). " Leases:");
        
  //$pdf->addText(100, 400 , 10, $leased_data[0][cust]);

        for($i = 0; $i < count($leased_data); $i++){
		$pdf->addText(90, 270-$i * 10 , 8, $leased_data[$i][cust]);
        }

   	$pdf->ezSetDy(-15);
   
   	//$pdf->ezText("<i>*Meat and fruit pallets are double stacked. Juice is triple stacked.</i>",9,$left);
        
        if ($isConfirm){
                $format = "This report was confirmed by OPS";
        }else{
                $format = "This report is not confirmed by OPS";
        }

//   	$today = date('m/j/y');
//   	$format = "Port of Wilmington, " . $today . " Printed by " . $user;
   	$pdf->line(50,40,560,40);
   	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	//$pdf->line(70,822,578,822);
   	$pdf->addText(60,34,6, $format);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');

//   	$pdf->ezStream();
        include("redirect_pdf.php");
}else{

  	$mailTO = "rwang@port.state.de.us";

//	$mailTo1 = "gbailey@port.state.de.us,Directors@port.state.de.us";
	$mailTo1 = "gbailey@port.state.de.us,";
//	$mailTo1 .="dniessen@port.state.de.us,";
//	$mailTo1 .="ffitzgerald@port.state.de.us,";
	$mailTo1 .="ithomas@port.state.de.us,";
	$mailTo1 .="rhorne@port.state.de.us,";
//	$mailTo1 .="kskinner@port.state.de.us,";
	$mailTo1 .="parul@port.state.de.us,";
	$mailTo1 .="tkeefer@port.state.de.us";

	$mailTo2 = "jjaffe@port.state.de.us,";
        $mailTo2 .= "wstans@port.state.de.us,";
        $mailTo2 .= "mpallmann@port.state.de.us,";
        $mailTo2 .= "martym@port.state.de.us,";
        $mailTo2 .= "ltreut@port.state.de.us,";
        $mailTo2 .= "bboles@port.state.de.us,";
        $mailTo2 .= "amarkow@port.state.de.us,";
        $mailTo2 .= "jharoldson@port.state.de.us,";
        $mailTo2 .= "msimpson@port.state.de.us,";
        $mailTo2 .= "vnbecker@port.state.de.us,";
        $mailTo2 .= "bdempsey@port.state.de.us,";
	$mailTo2 .= "psherman@port.state.de.us";
	$mailTo2 .= "ddonofrio@port.state.de.us";

        $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "rwang@port.state.de.us\r\n";
        $mailheaders .= "Content-Type: text/html\r\n";
	 
   	$mailsubject = "Warehouse capacity: $reefer_total_percentp% ";

   	$body  = "<html>\r\n";
        $body .= "<body>\r\n";
	$body .= "<table width = 540>\r\n";
        $body .= "<tr><td align=center>\r\n";
	$body .= "<br \><font size = 5 ><b>Warehouse Utilization</b></font><br \><br \>\r\n";
        $body .= "</td></tr>\r\n";
        $body .= "<tr><td>\r\n";
  	$body .= "<table border = 1  width=540 cellspacing=1 cellpadding=1 bgcolor=#000000>\r\n";

        $body .= "<tr bgcolor=#ffffff><td width=360 align=center colspan=4><b>Sq. Ft.</b></td>\r\n";
        $body .= "<td width = 180 align = center colspan= 2><b>Utilization</b></td></tr>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=90 align=center><b>Total</b></td>\r\n";
        $body .= "<td width = 90 align = center><b>Leased</b></td>\r\n";
        $body .= "<td width=90 align=center><b>Net</b></td>\r\n";
        $body .= "<td width=90 align=center><b>Occupied</b></td>\r\n";
//	$body .= "<td width=90 align=center><b>Committed</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Net</b></td>\r\n";
        $body .= "<td width=90 align=center><b>Gross</b></td></tr>\r\n";
	for($i = 0; $i < count($data); $i++){
		$body .= "<tr bgcolor=#ffffff><td align=center>".$data[$i]["sq_feet"]."</td>\r\n";
                $body .= "<td align=center>".$data[$i]["leased"]."</td>\r\n";
                $body .= "<td align=center>".$data[$i]["net_sq_feet"]."</td>\r\n";
                $body .= "<td align=center>".$data[$i]["plts"]."</td>\r\n";
                $body .= "<td align=center>".$data[$i]["net_percent"]."</td>\r\n";
                $body .= "<td align=center>".$data[$i]["percent"]."</td></tr>\r\n";
	}
        $body .= "<tr bgcolor=#ffffff><td align=center><b><i>".$tot_data[0]["sq_feet"]."</i></b></td>\r\n";
        $body .= "<td align=center><b><i>".$tot_data[0]["leased"]."</i></b></td>\r\n";
        $body .= "<td align=center><b><i>".$tot_data[0]["net_sq_feet"]."</i></b></td>\r\n";
        $body .= "<td align=center><b><i>".$tot_data[0]["plts"]."</i></b></td>\r\n";
        $body .= "<td align=center><b><i>".$tot_data[0]["net_percent"]."</i></b></td>\r\n";
        $body .= "<td align=center><b><i>".$tot_data[0]["percent"]."</i></b></td></tr>\r\n";
        $body .= "</table>\r\n";
        if ($t_other_pallets > 0) {
                $body .= "<br \>".$total_other_string."\r\n";
        }        
        $body .= "<br \><br \>\r\n";
        $body .= "</td></tr>\r\n";
        $body .= "<tr><td>\r\n"; 
        $body .= "<table border = 1  width=540 cellspacing=1 cellpadding=1 bgcolor=#000000>\r\n";
 
        $body .= "<tr bgcolor=#ffffff><td width=90 align=center rowspan=2><b>Sq. Ft. <br \>Net</b></td>\r\n";
        $body .= "<td width = 90 align = center rowspan= 2><b>Pallets in Inventory</b></td>\r\n";
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

	$body .= "<br \><br \><font size=4>&nbsp;&nbsp;".date('F', strtotime($run_date)). " Leases:</font>\r\n";
        for($i = 0; $i < count($leased_data); $i++){
             	$body .= "<br \><font size = 2>&nbsp;&nbsp;&nbsp;&nbsp;".$leased_data[$i][cust]."</font>\r\r\n";
        }
     	
        $body .= "</body>\r\n";
        $body .= "</html>\r\n";
  	
        $arg2 = $HTTP_SERVER_VARS["argv"][2];

	if($arg2 <>""){
		$mailsubject = "Warehouse capacity: $reefer_total_percentp%  waiting for confirmation.";
		mail($arg2, $mailsubject, $body, $mailheaders);
		exit;
	}else if($isConfirm == false){
                $body = "";
                $mailsubject = "Warehouse Utilization report has not been confirmed by OPS.";
        }

//$mailTO = "rwang@port.state.de.us";
//   	mail($mailTO, $mailsubject, $body, $mailheaders);
        mail($mailTo1, $mailsubject, $body, $mailheaders);
        mail($mailTo2, $mailsubject, $body, $mailheaders);

}
pg_close($pg_conn );
?>
