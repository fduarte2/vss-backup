<?

include("pow_session.php");

$user = $userdata['username'];

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

$conn = ora_logon("SAG_OWNER@BNI", "SAG");
if($conn < 1){
	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
}
$cursor = ora_open($conn);
$cursor2 = ora_open($conn);
$cursor3 = ora_open($conn);

$conn_lcs = ora_logon("LABOR@LCS", "LABOR");
if($conn_lcs < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn_lcs));
        printf("Please try later!");
        exit;
}
$cursor_lcs = ora_open($conn_lcs);

if (!$export){
	$manifest_heading = array('comm'=>'<b>Commodity</b>', 'qty'=>'<b>Qty</b>', 'qty_unit'=>'<b>Unit</b>', 'weight'=>'<b>Tons</b>','lines'=>'<b>Lines</b>','dockage'=>'<b>Dockage</b>', 'wharfage'=>'<b>Wharfage</b>', 'backhaul'=>'<b>Backhaul</b>', 'tot_rev'=>'<b>Tot Rev $</b>','rev_per_ton'=>'<b>Rev $/Ton</b>','reg'=>'<b>REG</b>', 'ot'=>'<b>OT</b>', 'dt'=>'<b>DT</b>','tot_hours'=>'<b>Tot Hrs</b>', 'hours_per_ton'=>'<b>Hrs/Ton</b>');
	$manifest_col = array('comm'=>array('width'=>165, 'justification'=>'left'),
                      'qty'=>array('width'=>35, 'justification'=>'right'),
                      'qty_unit'=>array('width'=>40, 'justification'=>'right'),
                      'weight'=>array('width'=>40, 'justification'=>'right'),
                      'lines'=>array('width'=>45, 'justification'=>'center'),
                      'dockage'=>array('width'=>50, 'justification'=>'center'),
                      'wharfage'=>array('width'=>52, 'justification'=>'right'),
                      'backhaul'=>array('width'=>52, 'justification'=>'right'),
                      'tot_rev'=>array('width'=>55, 'justification'=>'right'),
                      'rev_per_ton'=>array('width'=>55, 'justification'=>'right'),
                      'reg'=>array('width'=>35, 'justification'=>'center'),
                      'ot'=>array('width'=>30, 'justification'=>'center'),
                      'dt'=>array('width'=>30, 'justification'=>'center'),
                      'tot_hours'=>array('width'=>45, 'justification'=>'center'),
                      'hours_per_ton'=>array('width'=>45, 'justification'=>'center'));


	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(40,40,50,40);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);

//	$today = date('m/d/y g:i A');
	// Print Report title
	$pdf->ezText("<b>Vessel Report</b>", 24, $center);
	$pdf->ezSetDy(-15);
	$pdf->ezText("<b><i>Departure Date: ".$start_date." to ".$end_date."</i> </b>", 12, $center);
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



$sql = "select p.lr_num, p.vessel_name, to_char(v.date_departed, 'mm/dd/yyyy') from voyage v, vessel_profile p where p.lr_num = v.lr_num and v.date_departed >=to_date('$start_date 00:00:01','mm/dd/yyyy hh24:mi:ss') and v.date_departed <=to_date('$end_date 23:59:59','mm/dd/yyyy hh24:mi:ss') order by v.date_departed";

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

        	//revenue billed for Wharfage
        	$sql = "select sum(service_amount) from billing where lr_num in ($lr_num) and commodity_code = $comm_code and service_status='INVOICED' and service_code between 2111 and 2133";
        	$statement3 = ora_parse($cursor3, $sql);
        	ora_exec($cursor3);
        	$wharfage = 0;
        	while(ora_fetch($cursor3)){
                	$wharfage = ora_getcolumn($cursor3, 0);
        	}

        	//revenue billed for Backhaul
        	$sql = "select sum(service_amount) from billing where lr_num in ($lr_num) and commodity_code = $comm_code  and service_status='INVOICED' and service_code between 6111 and 6119";
        	$statement3 = ora_parse($cursor3, $sql);
        	ora_exec($cursor3);
        	$backhaul = 0;
        	while(ora_fetch($cursor3)){
                	$backhaul = ora_getcolumn($cursor3, 0);
        	}
                $revenue =  $wharfage + $backhaul;
        	$rev_per_ton = ($revenue / $weight);


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
                	$excel .= "<td>$".number_format($wharfage, 0, '.',',')."</td>";
                	$excel .= "<td>$".number_format($backhaul, 0, '.',',')."</td>";
                	$excel .= "<td>$".number_format($revenue, 0,'.',',')."</td>";
                	$excel .= "<td>$".number_format($rev_per_ton, 2, '.', ',')."</td>";
               	 	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td>";
                	$excel .= "<td></td></tr>";
		}else{
			array_push($manifest_data, array('comm'=>$comm_name, 'qty'=>number_format($qty, 0, '.',','), 'qty_unit'=>$qty_unit, 'weight'=>number_format($weight, 0, '.', ','),'wharfage'=>'$'.number_format($wharfage, 0, '.',','), 'backhaul'=>'$'.number_format($backhaul,0,'.',','), 'tot_rev'=>'$'.number_format($revenue, 0,'.',','),'rev_per_ton'=>'$'.number_format($rev_per_ton, 2, '.', ',')));		
		}
	}
  	
	if ($tot_weight == 0){
		continue;	
	}
	
/*
	$pdf->ezSetDy(-20);
	$pdf->ezText("<b>".$vName."</b>", 14, $center);
	$pdf->ezSetDy(-5);
	$pdf->ezText("<b>Departed on: ".$date_departed."</b>", 10, $center);
        $pdf->ezSetDy(-10);	
*/
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

	$sql ="select earning_type_id, sum(duration) from hourly_detail where vessel_id in ($lr_num) and (service_code between 6110 and 6119 or service_code between 6130 and 6149) and employee_id not in (select user_id from lcs_user) group by earning_type_id order by earning_type_id desc";
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
   		$table .= "<tr><td colspan=15 align=center><font size = 6><b>Vessel Report</b></font><br/><b><i>Departure Date: ".$start_date." to ".$end_date."</i> </b><br \>Printed on: ".$today."</td></tr>";
   		$table .= "$excel";

   		$table .= "</table>";

   		//export to excel
   		header("Content-Type: application/vnd.ms-excel; name='excel'");
   		header("Content-Disposition: attachment; filename=Export.xls");

   		echo ("$table");
	}else {
		$pdf->ezStream();
	}
?>
