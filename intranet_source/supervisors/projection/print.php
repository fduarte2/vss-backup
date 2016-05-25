<?
  $user_id = $HTTP_POST_VARS['user_id'];
  $sDate = $HTTP_POST_VARS['sDate'];
  $eDate = $HTTP_POST_VARS['eDate'];

  $conn_lcs = ora_logon("LABOR@LCS", "LABOR");
  if($conn_lcs < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_lcs = ora_open($conn_lcs);

  $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn_bni < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_bni = ora_open($conn_bni);

  $service_type[0]="BACKHAUL";
  $service_type[1]="TRUCKLOADING";
  $service_type[2]="CONTAINER HANDLING";
  $service_type[3]="RAIL CAR HANDLING";
  $service_type[4]="INSPECTION";

  $column[0]="backhaul";
  $column[1]="truckloading";
  $column[2]="container_handling";
  $column[3]="rail_car_handling";
  $column[4]="inspection";

  $service_code[0] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6149) ";
  $service_code[1] = " and service_code between 6220 and 6229 ";
  $service_code[2] = " and service_code between 6410 and 6419 ";
  $service_code[3] = " and service_code between 6310 and 6319 ";
  $service_code[4] = " and service_code between 6520 and 6529 ";

   $sql = "select count(*) from pref_commodity 
	   where user_id='$user_id' and 
	   working_date >= to_date('$sDate','mm/dd/yyyy') and working_date <= to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   if (ora_fetch($cursor_bni)){
	$comm_count = ora_getcolumn($cursor_bni, 0);
   }else{
	$comm_count = 0;
   }

   $arrHeading = array('comm'=>'<b>Commodity</b>','qty'=>'<b>Quantity</b>','tons'=>'<b>Tons</b>');
   $arrCol = array('comm'=>array('width'=>250, 'justification'=>'left'),
                   'qty'=>array('width'=>100, 'justification'=>'center'),
                   'tons'=>array('width'=>100, 'justification'=>'center'));
   $arrHeading1 = array('service'=>'<b>Service</b>','comm'=>'<b>Commodity</b>','hours'=>'<b>Hours</b>');
   $arrCol1 = array('service'=>array('width'=>140, 'justification'=>'left'),
		    'comm'=>array('width'=>250, 'justification'=>'left'),
                    'hours'=>array('width'=>60, 'justification'=>'center'));

   $heading = array();
   array_push($heading, $arrHeading);
   $heading1 = array();
   array_push($heading1, $arrHeading1);


        include 'class.ezpdf.php';
        $pdf = new Cezpdf('letter','portrait');

        $pdf->ezSetMargins(40,40,80,80);
        $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
        $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
        $pdf->setFontFamily('Helvetica.afm', $tmp);

        $today = date('m/j/y h:i A');
        $format = "Port of Wilmington, Printed on:" . $today;
        $pdf->line(50,40,560,40);

        $all = $pdf->openObject();
        $pdf->saveState();
        $pdf->setStrokeColor(0,0,0,1);
        //$pdf->line(70,822,578,822);
        $pdf->addText(60,34,6, $format);
        $pdf->restoreState();
        $pdf->closeObject();
        $pdf->addObject($all,'all');


        // Write out the intro.
        // Print Receiving Header
        $pdf->ezSetDy(-10);
        $pdf->ezText("<b>Activity vs. Hours</b>", 24, $center);
        $pdf->ezSetDy(-10);
	$pdf->ezText("<b><i>Supervisor: ". $HTTP_POST_VARS['user']."</b> </i>", 12, $center);
        $pdf->ezSetDy(-10);
        $pdf->ezText("<b><i>From $sDate to $eDate </i></b>", 12, $center);
	
        $pdf->ezSetDy(-20);
        $pdf->ezText("<b>ACTIVITY FOR SERVICE/COMMODITIES YOU REQUESTED</b>", 14, $center);
        $pdf->ezSetDy(-10);
        $pdf->ezText("(These are the service/commodities that YOU set as your preferences)", 12, $center);


   $hData = array();
   $tHours = 0;

   for($i= 0; $i < 4; $i++){
	$data[$i] = array();

	$sql = "select distinct commodity_code from pref_commodity 
		where user_id='$user_id' and $column[$i] = 'Y' and 
		working_date >= to_date('$sDate','mm/dd/yyyy') and working_date <= to_date('$eDate','mm/dd/yyyy')";
	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);
	$comm = array();
	$j = 0;
        while (ora_fetch($cursor_bni)){
		$comm[$j] = ora_getcolumn($cursor_bni, 0);
		$j++;
	}
	
 	for($k = 0; $k < $j; $k++){
		$commodity_name = "";
		$qty = 0;
		$tons = 0;
		//get tonnage
		$sql = "select commodity_name, sum(qty), sum(tonnage) from productivity p, pref_commodity c
			where c.user_id='$user_id' and $column[$i] = 'Y' and
                	working_date >= to_date('$sDate','mm/dd/yyyy') and working_date <= to_date('$eDate','mm/dd/yyyy') and
			p.commodity = c.commodity_code and p.date_time = c.working_date and c.commodity_code = '$comm[$k]'
			and service_type = '$service_type[$i]'
			group by commodity_name";

		$statement = ora_parse($cursor_bni, $sql);
        	ora_exec($cursor_bni);
		if (ora_fetch($cursor_bni)){
			$commodity_name = ora_getcolumn($cursor_bni, 0);
			$qty = ora_getcolumn($cursor_bni, 1);
			$tons = ora_getcolumn($cursor_bni,2);
		}
		
                if ($qty > 0 || $tons >0){
                        array_push($data[$i], array('comm'=>$commodity_name,
                                                    'qty'=>round($qty),
                                                    'tons'=>round($tons)));
		}
	}

	//getHours
	$sql = "select commodity_code, sum(duration) from hourly_detail
		where user_id = '$user_id' and 
		hire_date >= to_date('$sDate','mm/dd/yyyy') and
		hire_date <= to_date('$eDate','mm/dd/yyyy') and
                employee_id not in (select employee_id from employee where employee_type_id ='SUPV')
        	$service_code[$i]
		group by commodity_code";
	$statement = ora_parse($cursor_lcs, $sql);
        ora_exec($cursor_lcs);
	$count = 0;
       	while (ora_fetch($cursor_lcs)){
		$comm_code = ora_getcolumn($cursor_lcs, 0);
		$hours = ora_getcolumn($cursor_lcs, 1);

		$tHours +=$hours;

		$sql = "select commodity_name from commodity_profile where commodity_code='$comm_code'";
		$statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
		if (ora_fetch($cursor_bni)){
			$cName = ora_getcolumn($cursor_bni, 0);
		}else{
			$cName = "";
		}
	
		if ($count ==0){
			array_push($hData, array('service'=>'<b>'.$service_type[$i].'</b>',
						 'comm'=>$cName,
						 'hours'=>$hours));
		}else{
                        array_push($hData, array('service'=>'',
                                                 'comm'=>$cName,
                                                 'hours'=>$hours));
                }
		$count++;
	}
	
	if (count($data[$i]) > 0){
		$pdf->ezSetDy(-20);
        	$pdf->ezText("<b>$service_type[$i]</b>", 12, $center);
        	$pdf->ezSetDy(-10);
		$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'fontSize'=>10,'cols'=>$arrCol));
		$pdf->ezTable($data[$i], $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'fontSize'=>10,'cols'=>$arrCol));
	}				
   }

   //get total hours
   $sql = "select sum(duration) from hourly_detail
           where user_id = '$user_id' and
           hire_date >= to_date('$sDate','mm/dd/yyyy') and
           hire_date <= to_date('$eDate','mm/dd/yyyy') and
           employee_id not in (select employee_id from employee where employee_type_id ='SUPV')";
   $statement = ora_parse($cursor_lcs, $sql);
   ora_exec($cursor_lcs);
   if(ora_fetch($cursor_lcs)){
	$total_hours = ora_getcolumn($cursor_lcs, 0);
   }
   $other_hours = $total_hours - $tHours;

   if ($other_hours > 0)
	array_push($hData, array('service'=>'<b>Other</b>',
                                 'comm'=>'',
                                 'hours'=>$other_hours));
 
   array_push($hData, array('service'=>'<b>Total</b>',
                            'comm'=>'',
                            'hours'=>'<b>'.$total_hours.'</b>'));

   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>ALL LABOR HOURS YOU ENTERED</b>", 14, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'fontSize'=>10,'cols'=>$arrCol1));
   $pdf->ezTable($hData, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'fontSize'=>10,'cols'=>$arrCol1));
   $pdf->ezSetDy(-15);
   $pdf->ezText("<b><i>* Backhaul activity is calculated on the day vessel departs.</i></b>", 10, $left);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i>* Ideally, service & commodities you requested must match service & commodities of hours you entered</i></b>", 10, $left);

   $pdf->ezStream();

?>
