<?

$user = $HTTP_COOKIE_VARS[directoruser];
if($user == ""){
      header("Location: ../../director_login.php");
      exit;
}

if (isset($HTTP_POST_VARS[reset])){
   setCookie("sDate","");
   setCookie("eDate","");
   header("Location: index.php");
   exit;
}

if (isset($HTTP_POST_VARS[export])){
   $export = true;
}else{
   $export = false;
}

$today = date('m/d/y g:i A');

$sDate = $HTTP_POST_VARS["sDate"];
$eDate = $HTTP_POST_VARS["eDate"];
setCookie("sDate", $sDate);
setCookie("eDate", $eDate);

$start_date = date('m/d/Y',strtotime($sDate));
$end_date = date('m/d/Y', strtotime($eDate));

include("connect.php");

// open a connection to the database server
$conn_ccd = pg_connect ("host=$host dbname=$db user=$dbuser");


if (!$conn_ccd){
        printf("Error logging on to the CCDS Server: ");
        printf("Please try later!");
        exit;
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

$conn_lcs = ora_logon("LABOR@LCS", "LABOR");
if($conn_lcs < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
}
$cursor_lcs = ora_open($conn_lcs);


if (!$export){
	$manifest_heading = array('comm'=>'<b>Commodity</b>', 'qty'=>'<b>Qty</b>', 'qty_unit'=>'<b>Unit</b>', 'weight'=>'<b>Tons</b>', 'tot_rev'=>'<b>Tot Rev $</b>','rev_per_ton'=>'<b>Rev $/Ton</b>','reg'=>'<b>REG</b>', 'ot'=>'<b>OT</b>', 'dt'=>'<b>DT</b>','tot_hours'=>'<b>Tot Hrs</b>', 'hours_per_ton'=>'<b>Hrs/Ton</b>');
	$manifest_col = array('comm'=>array('width'=>165, 'justification'=>'left'),
                      'qty'=>array('width'=>55, 'justification'=>'right'),
                      'qty_unit'=>array('width'=>50, 'justification'=>'right'),
                      'weight'=>array('width'=>55, 'justification'=>'right'),
//                      'lines'=>array('width'=>45, 'justification'=>'center'),
//                      'dockage'=>array('width'=>50, 'justification'=>'center'),
//                      'wharfage'=>array('width'=>52, 'justification'=>'center'),
//                      'backhaul'=>array('width'=>52, 'justification'=>'center'),
                      'tot_rev'=>array('width'=>60, 'justification'=>'center'),
                      'rev_per_ton'=>array('width'=>60, 'justification'=>'center'),
                      'reg'=>array('width'=>55, 'justification'=>'center'),
                      'ot'=>array('width'=>50, 'justification'=>'center'),
                      'dt'=>array('width'=>50, 'justification'=>'center'),
                      'tot_hours'=>array('width'=>65, 'justification'=>'center'),
                      'hours_per_ton'=>array('width'=>55, 'justification'=>'center'));


	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(40,40,50,40);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);

//	$today = date('m/d/y g:i A');
	// Print Report title
	$pdf->ezText("<b>Warehouse Report</b>", 24, $center);
	$pdf->ezSetDy(-15);
	$pdf->ezText("<b><i>Date: ".$start_date." to ".$end_date."</i> </b>", 12, $center);
	$pdf->ezSetDy(-10);

	$pdf->ezStartPageNumbers(400, 20, 8, '','',1);

	$format = "Printed On: " . $today;
 
	$all = $pdf->openObject();
	$pdf->saveState();
	$pdf->setStrokeColor(0,0,0,1);
	$pdf->addText(650, 580,8, $format);
	$pdf->restoreState();
	$pdf->closeObject();
	$pdf->addObject($all,'all');
}

//Truckloading
$trload_data = array();
$tot_qty = 0;
$tot_weight = 0;

$tot_rev = 0;
$tot_rev_per_ton = 0;

$tot_hours = 0;
$reg_hours = 0;
$ot_hours = 0;
$dt_hours = 0;

$tot_reg_hours = 0;
$tot_ot_hours = 0;
$tot_dt_hours = 0;
$total_hours = 0;

$i = 0;
 
$pre_comm_code = 0;

$sql ="select earning_type_id, sum(duration) from hourly_detail where commodity_code = 0 and service_code between 6221 and 6229 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by earning_type_id order by earning_type_id desc";
$statement = ora_parse($cursor_lcs, $sql);
ora_exec($cursor_lcs);

while(ora_fetch($cursor_lcs)){
     	$type = ora_getcolumn($cursor_lcs, 0);
       	if ($type == "REG") {
          	$reg_hours = ora_getcolumn($cursor_lcs, 1);
		$tot_reg_hours += $reg_hours;
       	}else if ($type == "OT"){
          	$ot_hours = ora_getcolumn($cursor_lcs, 1);
		$tot_ot_hours += $ot_hours;
      	}else if ($type == "DT"){
         	$dt_hours = ora_getcolumn($cursor_lcs, 1);
		$tot_dt_hours += $dt_hours;
        }
        $tot_hours += ora_getcolumn($cursor_lcs, 1);
}	
$total_hours += $tot_hours;

//$hours_per_ton = ($tot_hours / $tot_weight);

if ($total_hours > 0){
	$i ++;
	array_push($trload_data, array('comm'=>'NULL COMMODITY (0)', 'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',','), 'hours_per_ton'=>number_format($hours_per_ton, 3, '.',',')));

}

$sql = "select decode(r.commodity_code, null, to_char(c.commodity_code), r.commodity_code), decode(r.commodity_name, null, c.commodity_name, r.commodity_name), sum(a.qty_change), t.qty_unit, sum(m.cargo_weight * a.qty_change / m.qty_expected)
from cargo_tracking t, cargo_manifest m, cargo_activity a, commodity_profile c, truckloading_rate r
where t.commodity_code like r.commodity_code(+) and t.commodity_code <> 1272 and t.commodity_code not like '3___' and t.commodity_code not like '4___' and t.commodity_code = c.commodity_code and a.lot_num = m.container_num and a.lot_num = t.lot_num and a.service_code = 6200 and date_of_activity >=to_date('$start_date','mm/dd/yyyy') and date_of_activity <=to_date('$end_date','mm/dd/yyyy') group by  decode(r.commodity_code, null, to_char(c.commodity_code), r.commodity_code), decode(r.commodity_name, null, c.commodity_name, r.commodity_name), qty_unit";
 $sql = "select decode(r.commodity_code, null, to_char(c.commodity_code), r.commodity_code), decode(r.commodity_name, null, c.commodity_name, r.commodity_name), sum(a.qty_change), sum(m.cargo_weight * a.qty_change / m.qty_expected) from cargo_tracking t, cargo_manifest m, cargo_activity a, commodity_profile c, truckloading_rate r where t.commodity_code like r.commodity_code(+) and t.commodity_code <> 1272 and t.commodity_code not like '3___' and t.commodity_code not like '4___' and t.commodity_code = c.commodity_code and a.lot_num = m.container_num and a.lot_num = t.lot_num and a.service_code = 6200 and date_of_activity >=to_date('$start_date','mm/dd/yyyy') and date_of_activity <=to_date('$end_date','mm/dd/yyyy') group by  decode(r.commodity_code, null, to_char(c.commodity_code), r.commodity_code), decode(r.commodity_name, null, c.commodity_name, r.commodity_name)";
$statement = ora_parse($cursor_bni, $sql);
ora_exec($cursor_bni);

while(ora_fetch($cursor_bni)){
    	$comm_code = ora_getcolumn($cursor_bni, 0);
      	$comm_name = ora_getcolumn($cursor_bni, 1);
       	$pos=strpos($comm_name, "-");
      	if ($pos > 0){
   		$comm_name= strtoupper(substr($comm_name, $pos+1)." (".substr($comm_name,0,$pos).")");
      	}else{
        	$comm_name = strtoupper($comm_name);
   	}
  	$qty = ora_getcolumn($cursor_bni, 2);
//    	$qty_unit = ora_getcolumn($cursor_bni, 3);
// 	$weight = ora_getcolumn($cursor_bni, 4)/2000;
	$weight = ora_getcolumn($cursor_bni, 3)/2000;
	
	$sql = "select distinct qty_unit from cargo_tracking t, cargo_activity a  where commodity_code like '".$comm_code."' and a.lot_num = t.lot_num and date_of_activity >=to_date('$start_date','mm/dd/yyyy') and date_of_activity <=to_date('$end_date','mm/dd/yyyy')";
//echo $sql;
	$statement = ora_parse($cursor_bni2, $sql); 
ora_exec($cursor_bni2); 
 	$count = 0;
	while(ora_fetch($cursor_bni2)){ 
        	$qty_unit = ora_getcolumn($cursor_bni2, 0);
		$count ++;
	} 
        if ($count > 1 ) $qty_unit = "COMB";  

   	$tot_qty += $qty;
     	$tot_weight += $weight;

	$rev = 0;
	$rev_per_ton = 0;
	$tot_hours = 0;
	$reg_hours = 0;
	$ot_hours = 0;
	$dt_hours = 0;
	$hours_per_ton  = 0;

if ($pre_comm_code <> $comm_code){
	//get rate
	$sql = "select rate from truckloading_rate where commodity_code ='".$comm_code."'";
	$statemnet = ora_parse($cursor_bni2, $sql);
	ora_exec($cursor_bni2);
	if (ora_fetch($cursor_bni2)){
		$rate = ora_getcolumn($cursor_bni2, 0);
	}else{
		$rate = 0;
	}  
	
	$sql ="select earning_type_id, sum(duration) from hourly_detail where commodity_code like '".$comm_code."' and service_code between 6221 and 6229 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by earning_type_id order by earning_type_id desc";
	$statement = ora_parse($cursor_lcs, $sql);
	ora_exec($cursor_lcs);

	while(ora_fetch($cursor_lcs)){
        	$type = ora_getcolumn($cursor_lcs, 0);
        	if ($type == "REG") {
                	$reg_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_reg_hours += $reg_hours;
        	}else if ($type == "OT"){
                	$ot_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_ot_hours += $ot_hours;
        	}else if ($type == "DT"){
                	$dt_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_dt_hours += $dt_hours;
        	}
        	$tot_hours += ora_getcolumn($cursor_lcs, 1);
	}
	$total_hours += $tot_hours;

	if ($weight <> 0){
		$hours_per_ton = ($tot_hours / $weight);
	}else{
		$hours_per_ton = 0;
	}
}
        $rev = $qty * $rate;
        $tot_rev += $rev;
        if ($weight <> 0){
                $rev_per_ton =  $rev / $weight;
        }else{
                $rev_per_ton = 0;
        }

	$pre_comm_code = $comm_code;

     	$i +=1;
	
	array_push($trload_data, array('comm'=>$comm_name, 'qty'=>number_format($qty, 0, '.',','), 'qty_unit'=>$qty_unit, 'weight'=>number_format($weight, 0, '.', ','), 'tot_rev'=>'$'.number_format($rev, 0,'.',','),'rev_per_ton'=>'$'.number_format($rev_per_ton, 2, '.', ','),'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',','), 'hours_per_ton'=>number_format($hours_per_ton, 3, '.',',')));
}
//rf
$sql = "select c.commodity_code, c.commodity_name, sum(a.qty_change/t.qty_received), 'PLT', sum(a.qty_change * w.weight), c.avg_lb_per_case as bni_comm_code from arch_2003.cargo_tracking t, arch_2003.cargo_activity a, commodity_profile c, commodity_weight w where t.commodity_code = w.commodity_code(+) and t.commodity_code = c.commodity_code and  a.pallet_id = t.pallet_id and a.service_code = 6 and date_of_activity >=to_date('$start_date','mm/dd/yyyy') and date_of_activity <=to_date('$end_date','mm/dd/yyyy') group by  c.commodity_code, c.commodity_name, c.avg_lb_per_case";
$statement = ora_parse($cursor_rf, $sql);
ora_exec($cursor_rf);

while(ora_fetch($cursor_rf)){
        $comm_code = ora_getcolumn($cursor_rf, 0);
        $comm_name = ora_getcolumn($cursor_rf, 1)." (".$comm_code.")";
	$bni_comm_code = ora_getcolumn($cursor_rf, 5);
        if ($bni_comm_code <>""){
		$sql = "select commodity_code, commodity_name from commodity_profile where commodity_code = $bni_comm_code";
		$statement2=ora_parse($cursor_bni2, $sql);
		ora_exec($cursor_bni2);
		if (ora_fetch($cursor_bni2)){
			$comm_code = ora_getcolumn($cursor_bni2, 0);
			$comm_name = ora_getcolumn($cursor_bni2, 1);
		} 
	}

        $pos=strpos($comm_name, "-");
        if ($pos > 0){
                $comm_name= strtoupper(substr($comm_name, $pos+1)." (".substr($comm_name,0,$pos).")");
        }else{
                $comm_name = strtoupper($comm_name);
        }
        $qty = ora_getcolumn($cursor_rf, 2);
        $qty_unit = ora_getcolumn($cursor_rf, 3);
        $weight = ora_getcolumn($cursor_rf, 4)/2000;

        $tot_qty += $qty;
        $tot_weight += $weight;

        $rev = 0;
        $rev_per_ton = 0;
        $tot_hours = 0;
        $reg_hours = 0;
        $ot_hours = 0;
        $dt_hours = 0;
	$hours_per_ton = 0;

if ($pre_comm_code <> $comm_code){
        //get rate
        $sql = "select rate from truckloading_rate where commodity_code = '".$comm_code."'";
        $statemnet = ora_parse($cursor_bni2, $sql);
        ora_exec($cursor_bni2);
        if (ora_fetch($cursor_bni2)){
                $rate = ora_getcolumn($cursor_bni2, 0);
        }else{
                $rate = 0;
        }

        $sql ="select earning_type_id, sum(duration) from hourly_detail where commodity_code = $comm_code and service_code between 6221 and 6229 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by earning_type_id order by earning_type_id desc";
        $statement = ora_parse($cursor_lcs, $sql);
        ora_exec($cursor_lcs);

        while(ora_fetch($cursor_lcs)){
                $type = ora_getcolumn($cursor_lcs, 0);
                if ($type == "REG") {
                        $reg_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_reg_hours += $reg_hours;
                }else if ($type == "OT"){
                        $ot_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_ot_hours += $ot_hours;
                }else if ($type == "DT"){
                        $dt_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_dt_hours += $dt_hours;
                }
                $tot_hours += ora_getcolumn($cursor_lcs, 1);
        }
	$total_hours += $tot_hours;
	if ($weight <> 0){
        	$hours_per_ton = ($tot_hours / $weight);
	}else{
		$hours_per_ton = 0;
	}
}
        $rev = $qty * $rate;
        $tot_rev += $rev;
        if ($weight <> 0){
                $rev_per_ton =  $rev / $weight;
        }else{
                $rev_per_ton = 0;
        }

	$pre_comm_code = $comm_code;
        $i +=1;

        array_push($trload_data, array('comm'=>$comm_name, 'qty'=>number_format($qty, 0, '.',','), 'qty_unit'=>$qty_unit, 'weight'=>number_format($weight, 0, '.', ','), 'tot_rev'=>'$'.number_format($rev, 0,'.',','),'rev_per_ton'=>'$'.number_format($rev_per_ton, 2, '.', ','), 'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',','), 'hours_per_ton'=>number_format($hours_per_ton, 3, '.',',')));
}

$sql = "select r.ship_type, sum(a.pallets), 'PLT', sum(a.gross_weight) from ccd_activity a, ccd_received r where a.ccd_lot_id = r.ccd_lot_id and execution_date >='$start_date' and execution_date <='$end_date' group by r.ship_type order by r.ship_type";
$result = pg_query($conn_ccd, $sql) or
                die("Error in query: $sql. " .  pg_last_error($conn_ccd));
$rows = pg_num_rows($result);
for($j = 0; $j < $rows; $j++){
        $row=pg_fetch_row($result, $j);
  
	$ship_type = $row[0];
        if ($ship_type =="C"){
		$comm_code = 5841;
        	$comm_name = "C&S Meat (5841)";
	}else{
		$comm_code = 5741;
		$comm_name = "KY Beef (5741)";
	}
        $pos=strpos($comm_name, "-");
       
        $qty = $row[1];
        $qty_unit = $row[2];
        $weight = $row[3]/2000;

        $tot_qty += $qty;
        $tot_weight += $weight;

        $rev = 0;
        $rev_per_ton = 0;
        $tot_hours = 0;
        $reg_hours = 0;
        $ot_hours = 0;
        $dt_hours = 0;
        $hours_per_ton = 0;

if ($pre_comm_code <> $comm_code){
        //get rate
        $sql = "select rate from truckloading_rate where commodity_code ='".$comm_code."'";
        $statemnet = ora_parse($cursor_bni2, $sql);
        ora_exec($cursor_bni2);
        if (ora_fetch($cursor_bni2)){
                $rate = ora_getcolumn($cursor_bni2, 0);
        }else{
                $rate = 0;
        }

        $sql ="select earning_type_id, sum(duration) from hourly_detail where commodity_code = $comm_code and service_code between 6221 and 6229 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by earning_type_id order by earning_type_id desc";
        $statement = ora_parse($cursor_lcs, $sql);
        ora_exec($cursor_lcs);

        while(ora_fetch($cursor_lcs)){
                $type = ora_getcolumn($cursor_lcs, 0);
                if ($type == "REG") {
                        $reg_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_reg_hours += $reg_hours;
                }else if ($type == "OT"){
                        $ot_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_ot_hours += $ot_hours;
                }else if ($type == "DT"){
                        $dt_hours = ora_getcolumn($cursor_lcs, 1);
			$tot_dt_hours += $dt_hours;
                }
                $tot_hours += ora_getcolumn($cursor_lcs, 1);
        }
	$total_hours += $tot_hours;
	if ($weight <> 0){	
        	$hours_per_ton = ($tot_hours / $weight);
	}else{
		$hours_per_ton = 0;
	}
}
        $rev = $qty * $rate;
        $tot_rev += $rev;
        if ($weight <> 0){
                $rev_per_ton =  $rev / $weight;
        }else{
                $rev_per_ton = 0;
        }

	$pre_comm_code = $comm_code;

        $i +=1;

        array_push($trload_data, array('comm'=>$comm_name, 'qty'=>number_format($qty, 0, '.',','), 'qty_unit'=>$qty_unit, 'weight'=>number_format($weight, 0, '.', ','), 'tot_rev'=>'$'.number_format($rev, 0,'.',','),'rev_per_ton'=>'$'.number_format($rev_per_ton, 2, '.', ','),'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',','), 'hours_per_ton'=>number_format($hours_per_ton, 3, '.',',')));
}

$hours_per_ton = ($total_hours / $tot_weight);
$tot_rev_per_ton = $tot_rev / $tot_weight;
array_push($trload_data, array('comm'=>'<b><i>Total</i></b>', 'qty'=>'<b>'.number_format($tot_qty, 0, '.',',').'</b>', 'qty_unit'=>"", 'weight'=>'<b>'.number_format($tot_weight,0,'.',',').'</b>', 'tot_rev'=>'<b>$'.number_format($tot_rev, 0,'.',',').'</b>','rev_per_ton'=>'<b>$'.number_format($tot_rev_per_ton, 2, '.', ',').'</b>','reg'=>'<b>'.number_format($tot_reg_hours,1,'.',',').'</b>', 'ot'=>'<b>'.number_format($tot_ot_hours,1,'.',',').'</b>','dt'=>'<b>'.number_format($tot_dt_hours,1,'.',',').'</b>', 'tot_hours'=>'<b>'.number_format($total_hours, 1, '.',',').'</b>', 'hours_per_ton'=>'<b>'.number_format($hours_per_ton, 3, '.',',').'</b>'));

$pdf->ezSetDy(-20);
$pdf->ezText("<b>TRUCKLOADING</b>"." (Service code 6221 to 6229)", 14, $center);
$pdf->ezSetDy(-5);

$pdf->ezTable($trload_data, $manifest_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9,'cols'=>$manifest_col));

/*
//Transfer to cold storage
$trans_data = array();
$tot_qty = 0;
$tot_weight = 0;

$tot_hours = 0;
$reg_hours = 0;
$ot_hours = 0;
$dt_hours = 0;

$tot_reg_hours = 0;
$tot_ot_hours = 0;
$tot_dt_hours = 0;
$total_hours = 0;

$i = 0;


$sql = "select t.commodity_code, r.duration, o.duration, d.duration, t.duration from (select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6131 and 6139 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'REG' group by commodity_code) r, (select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6131 and 6139 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'OT' group by commodity_code) o,(select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6131 and 6139 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'DT' group by commodity_code) d,(select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6131 and 6139 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by commodity_code) t where t.commodity_code = r.commodity_code(+) and t.commodity_code = o.commodity_code(+) and t.commodity_code = d.commodity_code(+) order by t.commodity_code";

$statement = ora_parse($cursor_lcs, $sql);
ora_exec($cursor_lcs);

while(ora_fetch($cursor_lcs)){
	$comm_code = ora_getcolumn($cursor_lcs, 0);
        
	$sql = "select commodity_name from commodity_profile where commodity_code = $comm_code";
	$statement = ora_parse($cursor_bni, $sql);
	ora_exec($cursor_bni);
	if (ora_fetch($cursor_bni)){
		$comm_name = ora_getcolumn($cursor_bni, 0);
		$pos=strpos($comm_name, "-");
                if ($pos > 0){
                        $comm_name= strtoupper(substr($comm_name, $pos+1)." (".substr($comm_name,0,$pos).")");
                }else{
                        $comm_name = strtoupper($comm_name);
                }
	}
	$reg_hours = 0;
	$ot_hours = 0;
	$dt_hours = 0;
	$tot_hours = 0;

        $reg_hours = ora_getcolumn($cursor_lcs, 1);
       	$tot_reg_hours += $reg_hours;
     
      	$ot_hours = ora_getcolumn($cursor_lcs, 2);
    	$tot_ot_hours += $ot_hours;
         
     	$dt_hours = ora_getcolumn($cursor_lcs, 3);
        $tot_dt_hours += $dt_hours;
             
        $tot_hours += ora_getcolumn($cursor_lcs, 4);
        $total_hours += $tot_hours;

	$i ++;
	
	array_push($trans_data, array('comm'=>$comm_name, 'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',',')));

}
	array_push($trans_data, array('comm'=>'<b><i>Total</i></b>', 'reg'=>'<b>'.number_format($tot_reg_hours,1,'.',',').'</b>', 'ot'=>'<b>'.number_format($tot_ot_hours,1,'.',',').'</b>','dt'=>'<b>'.number_format($tot_dt_hours,1,'.',',').'</b>', 'tot_hours'=>'<b>'.number_format($total_hours, 1, '.',',').'</b>'));

$pdf->ezSetDy(-20);
$pdf->ezText("<b>TRANSFER TO COLD STORAGE</b>"." (Service code 6131 to 6139)", 14, $center);
$pdf->ezSetDy(-5);
        
$pdf->ezTable($trans_data, $manifest_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9,'cols'=>$manifest_col));
*/
//TERMINAL SERVICE
$term_data = array();
$tot_qty = 0;
$tot_weight = 0;

$tot_hours = 0;
$reg_hours = 0;
$ot_hours = 0;
$dt_hours = 0;

$tot_reg_hours = 0;
$tot_ot_hours = 0;
$tot_dt_hours = 0;
$total_hours = 0;

$i = 0;


$sql = "select t.commodity_code, r.duration, o.duration, d.duration, t.duration from (select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6521 and 6619 and service_code not like '%0' and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'REG' group by commodity_code) r, (select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6521 and 6619 and service_code not like '%0' and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'OT' group by commodity_code) o,(select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6521 and 6619 and service_code not like '%0' and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'DT' group by commodity_code) d,(select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6521 and 6619 and service_code not like '%0' and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by commodity_code) t where t.commodity_code = r.commodity_code(+) and t.commodity_code = o.commodity_code(+) and t.commodity_code = d.commodity_code(+) order by t.commodity_code";

$statement = ora_parse($cursor_lcs, $sql);
ora_exec($cursor_lcs);

while(ora_fetch($cursor_lcs)){
        $comm_code = ora_getcolumn($cursor_lcs, 0);

        $sql = "select commodity_name from commodity_profile where commodity_code = $comm_code";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
        if (ora_fetch($cursor_bni)){
                $comm_name = ora_getcolumn($cursor_bni, 0);
                $pos=strpos($comm_name, "-");
                if ($pos > 0){
                        $comm_name= strtoupper(substr($comm_name, $pos+1)." (".substr($comm_name,0,$pos).")");
                }else{
                        $comm_name = strtoupper($comm_name);
                }
        }
        $reg_hours = 0;
        $ot_hours = 0;
        $dt_hours = 0;
        $tot_hours = 0;

        $reg_hours = ora_getcolumn($cursor_lcs, 1);
        $tot_reg_hours += $reg_hours;

        $ot_hours = ora_getcolumn($cursor_lcs, 2);
        $tot_ot_hours += $ot_hours;

        $dt_hours = ora_getcolumn($cursor_lcs, 3);
        $tot_dt_hours += $dt_hours;

        $tot_hours += ora_getcolumn($cursor_lcs, 4);
        $total_hours += $tot_hours;

        $i ++;

        array_push($term_data, array('comm'=>$comm_name, 'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',',')));

}
        array_push($term_data, array('comm'=>'<b><i>Total</i></b>', 'reg'=>'<b>'.number_format($tot_reg_hours,1,'.',',').'</b>', 'ot'=>'<b>'.number_format($tot_ot_hours,1,'.',',').'</b>','dt'=>'<b>'.number_format($tot_dt_hours,1,'.',',').'</b>', 'tot_hours'=>'<b>'.number_format($total_hours, 1, '.',',').'</b>'));

$pdf->ezSetDy(-20);
$pdf->ezText("<b>TERMINAL SERVICE</b>"." (Service code 6521 to 6619)", 14, $center);
$pdf->ezSetDy(-5);

$pdf->ezTable($term_data, $manifest_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9,'cols'=>$manifest_col));

//CONSOLIDATION
$cons_data = array();
$tot_qty = 0;
$tot_weight = 0;

$tot_hours = 0;
$reg_hours = 0;
$ot_hours = 0;
$dt_hours = 0;

$tot_reg_hours = 0;
$tot_ot_hours = 0;
$tot_dt_hours = 0;
$total_hours = 0;

$i = 0;


$sql = "select t.commodity_code, r.duration, o.duration, d.duration, t.duration from (select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6571 and 6579 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'REG' group by commodity_code) r, (select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6571 and 6579 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'OT' group by commodity_code) o,(select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6571 and 6579 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') and earning_type_id = 'DT' group by commodity_code) d,(select commodity_code, sum(duration) as duration from hourly_detail where service_code between 6571 and 6579 and hire_date >=to_date('$start_date','mm/dd/yyyy') and hire_date <=to_date('$end_date','mm/dd/yyyy') group by commodity_code) t where t.commodity_code = r.commodity_code(+) and t.commodity_code = o.commodity_code(+) and t.commodity_code = d.commodity_code(+) order by t.commodity_code";

$statement = ora_parse($cursor_lcs, $sql);
ora_exec($cursor_lcs);

while(ora_fetch($cursor_lcs)){
        $comm_code = ora_getcolumn($cursor_lcs, 0);

        $sql = "select commodity_name from commodity_profile where commodity_code = $comm_code";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
        if (ora_fetch($cursor_bni)){
                $comm_name = ora_getcolumn($cursor_bni, 0);
                $pos=strpos($comm_name, "-");
                if ($pos > 0){
                        $comm_name= strtoupper(substr($comm_name, $pos+1)." (".substr($comm_name,0,$pos).")");
                }else{
                        $comm_name = strtoupper($comm_name);
                }
        }
        $reg_hours = 0;
        $ot_hours = 0;
        $dt_hours = 0;
        $tot_hours = 0;

        $reg_hours = ora_getcolumn($cursor_lcs, 1);
        $tot_reg_hours += $reg_hours;

        $ot_hours = ora_getcolumn($cursor_lcs, 2);
        $tot_ot_hours += $ot_hours;

        $dt_hours = ora_getcolumn($cursor_lcs, 3);
        $tot_dt_hours += $dt_hours;

        $tot_hours += ora_getcolumn($cursor_lcs, 4);
        $total_hours += $tot_hours;

        $i ++;

        array_push($cons_data, array('comm'=>$comm_name, 'reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>number_format($tot_hours, 1, '.',',')));

}
        array_push($cons_data, array('comm'=>'<b><i>Total</i></b>', 'reg'=>'<b>'.number_format($tot_reg_hours,1,'.',',').'</b>', 'ot'=>'<b>'.number_format($tot_ot_hours,1,'.',',').'</b>','dt'=>'<b>'.number_format($tot_dt_hours,1,'.',',').'</b>', 'tot_hours'=>'<b>'.number_format($total_hours, 1, '.',',').'</b>'));

$pdf->ezSetDy(-20);
$pdf->ezText("<b>CONSOLIDATION</b>"." (Service code 6571 to 6579)", 14, $center);
$pdf->ezSetDy(-5);

$pdf->ezTable($cons_data, $manifest_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9,'cols'=>$manifest_col));

/*
$sql = "select p.lr_num, p.vessel_name, to_char(v.date_departed, 'mm/dd/yyyy') from voyage v, vessel_profile p where p.lr_num = v.lr_num and v.date_departed >=to_date('$start_date','mm/dd/yyyy') and v.date_departed <=to_date('$end_date','mm/dd/yyyy') order by v.date_departed";

$statement = ora_parse($cursor, $sql);
ora_exec($cursor);

while(ora_fetch($cursor)){
    	$lr_num = ora_getcolumn($cursor, 0);
    	$vName = ora_getcolumn($cursor, 1);
	$date_departed = ora_getcolumn($cursor, 2);

	$sql = "select vessel_id from vessel_conversion where lr_num = ".$lr_num;
        $statement2 = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
	while(ora_fetch($cursor2)){
		$lr_num .=", ".ora_getcolumn($cursor2, 0);
	}


	$sql ="select m.commodity_code, c.commodity_name, sum(m.qty_expected) as qty, m.qty1_unit, sum(m.cargo_weight) as weight from cargo_manifest m, commodity_profile c where m.commodity_code = c.commodity_code and m.lr_num in ($lr_num) group by m.lr_num, m.commodity_code, c.commodity_name, m.qty1_unit order by m.commodity_code";  
	$statement2 = ora_parse($cursor2, $sql);
	ora_exec($cursor2);

	$manifest_data = array();	
	$tot_qty = 0;
	$tot_weight = 0;


	$i = 0;
	while(ora_fetch($cursor2)){
    		$comm_code = ora_getcolumn($cursor2, 0);
    		$comm_name = ora_getcolumn($cursor2, 1);
                $pos=strpos($comm_name, "-");
                if ($pos > 0){
                	$comm_name= strtoupper(substr($comm_name, $pos+1)." (".substr($comm_name,0,$pos).")");
        	}else{
                	$comm_name = strtoupper($comm_name);
        	}
		$qty = ora_getcolumn($cursor2, 2);
		$qty_unit = ora_getcolumn($cursor2, 3);
		$weight = ora_getcolumn($cursor2, 4)/2000;
		
		$tot_qty += $qty;
		$tot_weight += $weight;

		$i +=1;
		if ($export && $i == 1) {
                	$excel .= "<tr><td align='center' colspan=15><br \>".$vName."<br \>Departed on: ".$date_departed."</td></tr>";
                	$excel .= "<tr><td><b>Commodity</b></td><td><b>Qty</b></td><td><b>Unit</b></td><td><b>Tons</b></td><td><b>Lines</b></td><td><b>Dockage</b></td><td><b>Wharfage</b></td><td><b>Backhaul</b></td><td><b>Tot Rev $</b></td><td><b>Rev $/Ton</b></td><td><b>REG</b></td><td><b>OT</b></td><td><b>DT</b></td><td><b>Tot hrs</b></td><td><b>Hrs/Ton</b></td></tr>";
		}else if (!$export && $i ==1){
        		$pdf->ezSetDy(-20);
        		$pdf->ezText("<b>".$vName."</b>", 14, $center);
        		$pdf->ezSetDy(-5);
        		$pdf->ezText("<b>Departed on: ".$date_departed."</b>", 10, $center);
        		$pdf->ezSetDy(-10);

		}
		
		if ($export){
			$excel .= "<tr><td>".$comm_name."</td>";
			$excel .= "<td>".number_format($qty, 0, '.',',')."</td>";
			$excel .= "<td>".$qty_unit."</td>";
			$excel .= "<td>".number_format($weight, 0, '.', ',')."</td>";
			$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
               	 	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td></tr>";
		}else{
			array_push($manifest_data, array('comm'=>$comm_name, 'qty'=>number_format($qty, 0, '.',','), 'qty_unit'=>$qty_unit, 'weight'=>number_format($weight, 0, '.', ',')));		
		}
	}
  	
	if ($tot_weight == 0){
		continue;	
	}
*/	
/*
	$pdf->ezSetDy(-20);
	$pdf->ezText("<b>".$vName."</b>", 14, $center);
	$pdf->ezSetDy(-5);
	$pdf->ezText("<b>Departed on: ".$date_departed."</b>", 10, $center);
        $pdf->ezSetDy(-10);	
*/
/*
	$tot_revenue = 0;


	
	//revenue billed for Lines
	$sql = "select sum(service_amount) from billing where lr_num in ($lr_num) and service_status='INVOICED' and service_code between 1200 and 1223";
	$statement2 = ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	$lines = 0;
        while(ora_fetch($cursor2)){
		$lines = ora_getcolumn($cursor2, 0);
	}

	
	//revenue billed for Dockage
        $sql = "select sum(service_amount) from billing where lr_num in ($lr_num) and service_status='INVOICED' and service_code between 1110 and 1145";

        $statement2 = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
	$dockage = 0;
        while(ora_fetch($cursor2)){
                $dockage = ora_getcolumn($cursor2, 0);
        }

        //revenue billed for Wharfage
        $sql = "select sum(service_amount) from billing where lr_num in ($lr_num) and service_status='INVOICED' and service_code between 2111 and 2133";
        $statement2 = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
	$wharfage = 0;
        while(ora_fetch($cursor2)){
                $wharfage = ora_getcolumn($cursor2, 0);
        }

        //revenue billed for Backhaul
        $sql = "select sum(service_amount) from billing where lr_num in ($lr_num) and service_status='INVOICED' and service_code between 6111 and 6119";
        $statement2 = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
	$backhaul = 0;
        while(ora_fetch($cursor2)){
                $backhaul = ora_getcolumn($cursor2, 0);
        }

	$tot_revenue = $lines + $dockage + $wharfage + $backhaul;
	$rev_per_ton = ($tot_revenue / $tot_weight);

	$sql ="select earning_type_id, sum(duration) from hourly_detail where vessel_id in ($lr_num) and service_code between 6111 and 6119 and employee_id not in (select user_id from lcs_user) group by earning_type_id order by earning_type_id desc";
        $statement2 = ora_parse($cursor_lcs, $sql);
        ora_exec($cursor_lcs);
	$tot_hours = 0;
	$reg_hours = 0;
	$ot_hours = 0;
	$dt_hours = 0;
        while(ora_fetch($cursor_lcs)){
                $type = ora_getcolumn($cursor_lcs, 0);
		if ($type == "REG") {
			$reg_hours = ora_getcolumn($cursor_lcs, 1);
		}else if ($type == "OT"){
			$ot_hours = ora_getcolumn($cursor_lcs, 1);
		}else if ($type == "DT"){
			$dt_hours = ora_getcolumn($cursor_lcs, 1);
		}
		$tot_hours += ora_getcolumn($cursor_lcs, 1);
        }
	$hours_per_ton = ($tot_hours / $tot_weight);

	if ($export){
		$excel .= "<tr><td>Total</td>";
		$excel .= "<td>".number_format($tot_qty, 0, '.',',')."</td>";
        	$excel .= "<td></td>";
		$excel .= "<td>".number_format($tot_weight,0,'.',',')."</td>";
        	$excel .= "<td>$".number_format($lines, 0, '.',',')."</td>";
       		$excel .= "<td>$".number_format($dockage, 0,'.',',')."</td>";
        	$excel .= "<td>$".number_format($wharfage, 0, '.',',')."</td>";
		$excel .= "<td>$".number_format($backhaul, 0, '.',',')."</td>";
		$excel .= "<td>$".number_format($tot_revenue, 0,'.',',')."</td>";
		$excel .= "<td>$".number_format($rev_per_ton, 2, '.', ',')."</td>";
		$excel .= "<td>".number_format($reg_hours,1,'.',',')."</td>";
		$excel .= "<td>".number_format($ot_hours,1,'.',',')."</td>";
		$excel .= "<td>".number_format($dt_hours,1,'.',',')."</td>";
		$excel .= "<td>".number_format($tot_hours, 1,'.',',')."</td>";
		$excel .= "<td>".number_format($hours_per_ton, 3, '.',',')."</td>";
	} else {
		array_push($manifest_data, array('comm'=>'<b><i>Total</i></b>', 'qty'=>'<b>'.number_format($tot_qty, 0, '.',',').'</b>', 'qty_unit'=>"", 'weight'=>'<b>'.number_format($tot_weight,0,'.',',').'</b>', 'lines'=>'$'.number_format($lines, 0,'.',','), 'dockage'=>'$'.number_format($dockage, 0,'.',','), 'wharfage'=>'$'.number_format($wharfage, 0, '.',','), 'backhaul'=>'$'.number_format($backhaul,0,'.',','), 'tot_rev'=>'<b>$'.number_format($tot_revenue, 0,'.',',').'</b>','rev_per_ton'=>'<b><i>$'.number_format($rev_per_ton, 2, '.', ',').'</i></b>','reg'=>number_format($reg_hours,1,'.',','), 'ot'=>number_format($ot_hours,1,'.',','),'dt'=>number_format($dt_hours,1,'.',','), 'tot_hours'=>'<b>'.number_format($tot_hours, 1, '.',',').'</b>', 'hours_per_ton'=>'<b><i>'.number_format($hours_per_ton, 3, '.',',').'</i></b>'));
	
        $pdf->ezTable($manifest_data, $manifest_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>9,'cols'=>$manifest_col));
	}
}
	if($export){
   		$table = "<TABLE border=1 CELLSPACING=1>";
   		$table .= "<tr><td colspan=15 align=center><font size = 6><b>Vessle Report</b></font><br/><b><i>Departure Date: ".$start_date." to ".$end_date."</i> </b><br \>Printed on: ".$today."</td></tr>";
   		$table .= "$excel";

   		$table .= "</table>";

   		//export to excel
   		header("Content-Type: application/vnd.ms-excel; name='excel'");
   		header("Content-Disposition: attachment; filename=Export.xls");

   		echo ("$table");
	}else {
		$pdf->ezStream();
	}
*/


$pdf->ezStream();

?>
