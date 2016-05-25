<?

   $sDate = "07/01/2002";
   $eDate = "07/31/2004";
/* 
  $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") -3 ,date("Y")));
   $eDate = $sDate;

   $wday = date('D');
   if ($wday =="Mon"){
        $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 3 ,date("Y")));
   }else{
        $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
   }
   $eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 1 ,date("Y")));
*/
//  $vDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") -1 ,date("Y")));

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
   $type[2] = "TERMINAL SERVICE";
   $type[3] = "RAIL CAR HANDLING";
   $type[4] = "CONTAINER HANDLING";
   $type[5] = "MAINTENANCE";
   $type[6] = "NON REVENUE";
   $type[7] = "STAND BY";

   $service_code_in[0] = " and service_code between 6220 and 6229 ";
   $service_code_in[1] = " and (service_code between 6110 and 6119 or service_code between 6130 and 6149) ";
   $service_code_in[2] = " and service_code between 6520 and 6619 ";
   $service_code_in[3] = " and service_code between 6310 and 6319 ";
   $service_code_in[4] = " and service_code between 6410 and 6419 ";
   $service_code_in[5] = " and service_code between 7200 and 7277 ";
   $service_code_in[6] = " and service_code between 7300 and 7399 ";
   $service_code_in[7] = " and service_code like '67__' ";

   $i = 0;
   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);

   while ($sTime <= $eTime){
   	$vDate = date('m/d/Y', $sTime);
echo $vDate."\r\n";
	//get activity data
        $sql = "insert into $productivity (date_time, service_type, commodity, vessel, shipping_line, qty, unit, tonnage)
		select date_time, service_type, commodity, vessel, shipping_line, sum(qty), null, sum(tonnage)
		from productivity 
		where date_time = to_date('$vDate','mm/dd/yyyy')
		group by date_time, service_type, commodity, vessel, shipping_line";
	$statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

	//Get Hours
	for ($i = 0; $i < 8; $i++){
		$sql = "select commodity_code, sum(duration) from hourly_detail 
			where hire_date = to_date('$vDate','mm/dd/yyyy') $service_code_in[$i] and 
			employee_id not in (select user_id from lcs_user)
			group by commodity_code";	

		$statement = ora_parse($cursor_lcs, $sql);
                ora_exec($cursor_lcs);
		while(ora_fetch($cursor_lcs)){

			$comm = ora_getcolumn($cursor_lcs, 0);
			$hours = ora_getcolumn($cursor_lcs, 1);

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

/*
  //calculate productivity
  $sql = "update $productivity set productivity = tonnage / hours where hours > 0 and date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
  $statement = ora_parse($cursor_bni, $sql);
  ora_exec($cursor_bni);


   $arrHeading1 = array('comm'=>'','sup'=>'', 'wing'=>'', 'plan'=>'<b>Plan</b>', 'actual'=>'<b>Actual</b>', 'aProd'=>'','budget'=>'','aProd_budget'=>'<b>Productivity</b>', 'flag'=>'');
   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'plan'=>'Plan', 'actual'=>'Actual', 'aProd'=>'','budget'=>'','aProd_budget'=>'Productivity', 'flag'=>'');
   $arrHeading1 = array('comm'=>'<b>Program</b>','service'=>'<b>Service</b>','plan'=>'Plan', 'actual'=>'Actual','aProd_budget'=>'Productivity(T/H)');

   $arrHeading = array('sup'=>'<b>Supervisor</b>', 'comm'=>'<b>Comm</b>', 'wing'=>'<b>Location</b>', 'num'=>'<b>Hire</b>', 'hours'=>'<b>Hours</b>', 'plt'=>'<b>PLT</b>', 'lab'=>'<b>Labor</b>','aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>PLT</b>', 'aProd'=>'<b>Productivity</b>', 'budget'=>'<b>Budget</b>','aProd_budget'=>'<b>- Budget</b>', 'flag'=>'Summary');
   $arrHeading = array('comm'=>'', 'service'=>'','hours'=>'<b>Hours</b>', 'plt'=>'<b>Plts</b>', 'aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>Plts</b>', 'tons'=>'<b>Tons</b>','budget'=>'<b>Budget</b>','aProd'=>'<b>Actual</b>');

   $arrCol1 = array('service'=>array('width'=>150, 'justification'=>'left'),
                   'comm'=>array('width'=>100, 'justification'=>'center'),
                   'plan'=>array('width'=>90, 'justification'=>'center'),
                   'actual'=>array('width'=>135, 'justification'=>'center'),
                   'aProd_budget'=>array('width'=>130,'justification'=>'center'));

   $arrCol = array('service'=>array('width'=>150, 'justification'=>'left'),
                   'comm'=>array('width'=>100, 'justification'=>'left'),
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

   $vDate = $sDate;

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


     

   $sql = "select distinct service_type, id from $productivity p, service_type s
	   where s.type = p.service_type and  date_time = to_date('$vDate','mm/dd/yyyy')
	   order by id";
   $sql = "select distinct itg from $productivity p, commodity_itg c
	   where p.commodity = c.commodity_code and  date_time = to_date('$vDate','mm/dd/yyyy')
	   order by itg";
   $statement = ora_parse($cursor_bni2, $sql);
   ora_exec($cursor_bni2);
   while (ora_fetch($cursor_bni2)){
	$itg = ora_getcolumn($cursor_bni2, 0);	
	
   	$sql = "select a.*, b.budget from (
	  	select  c.itg, service_type, sum(plan_hire), sum(plan_hours), sum(plan_plts), sum(hours), sum(qty), 
		sum(tonnage), s.id
	   	from $productivity p, service_type s, commodity_itg c
           	where s.type = p.service_type and p.commodity = c.commodity_code and c.itg = '$itg' and
		date_time = to_date('$vDate','mm/dd/yyyy')
           	group by service_type, c.itg, s.id) a, itg_budget b
	   	where a.service_type = b.type(+) and a.itg = b.itg(+)
	   	order by a.itg, a.id";


   	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);
   	$pre_service = "";
   	$pre_itg = "";

       	$st_pHours = 0;
      	$st_pPlts = 0;
     	$st_aHours = 0;
     	$st_aPlts  = 0;
      	$st_tons = 0;

   	while(ora_fetch($cursor_bni)){
		$service = ora_getcolumn($cursor_bni, 1);
        	$itg = ora_getcolumn($cursor_bni, 0);
        	$pHire = ora_getcolumn($cursor_bni, 2);
        	$pHours = ora_getcolumn($cursor_bni, 3);
        	$pPlts = ora_getcolumn($cursor_bni, 4);
        	$aHours = ora_getcolumn($cursor_bni, 5);
        	$aPlt = ora_getcolumn($cursor_bni, 6);
        	$tons = ora_getcolumn($cursor_bni, 7);
        	$budget = ora_getcolumn($cursor_bni, 9);
       
        
		$service = ucwords(strtolower($service));
		if($budget ==""){
			$budget = "No Budget";
		}else{
			$budget = number_format($budget, 2, '.',',');
		}

		if ($pre_itg <> $itg){
			$pre_itg = $itg;
			$dis_itg = $itg;
		}else{
			$dis_itg = "";
		}
        	if ($dis_itg =="Z-No Commodity")
			$dis_itg = "No Commodity";

		$pos = strpos($comm, '-');
		if ($pos > 0)
			$comm = substr($comm, $pos + 1);

        	if ($aHours ==0){
			$prods = "-";
		}else if ($tons ==0){
			$prods = "0";
		}else{
			$prods = number_format($tons/$aHours, 2, '.',',');
		}

        	$tot_pHours +=$pHours;
		$tot_pPlts += $pPlts;
		$tot_aHours += $aHours;
		$tot_aPlts  += $aPlt;
		$tot_tons += $tons;

                $st_pHours +=$pHours;
                $st_pPlts += $pPlts;
                $st_aHours += $aHours;
                $st_aPlts  += $aPlt;
                $st_tons += $tons;


		array_push($data, array('service'=>$service,
					'comm'=>$dis_itg,
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

	$st_pHours = number_format($st_pHours, 0, '.',',');
	$st_pPlts = number_format($st_pPlts, 0,'.',',');
	$st_aHours = number_format($st_aHours, 0, '.',',');
	$st_aPlts = number_format($st_aPlts, 0, '.',',');
	$st_tons = number_format($st_tons, 0, '.',',');

        array_push($data, array('service'=>'',
                                'comm'=>'<b><i>Sub Total</i></b>',
                                'num'=>'',
                                'hours'=>'<b><i>'.$st_pHours.'</i></b>',
                                'plt'=>'<b><i>'.$st_pPlts.'</i></b>',
                                'lab'=>'',
                                'aHours'=>'<b><i>'.$st_aHours.'</i></b>',
                                'aPlt'=>'<b><i>'.$st_aPlts.'</i></b>',
                                'tons'=>'<b><i>'.$st_tons.'</i></b>',
                                'budget'=>'',
                                'aProd'=>''));
   }
   $tot_pHours = number_format($tot_pHours, 0, '.',',');
   $tot_pPlts = number_format($tot_pPlts, 0,'.',',');
   $tot_aHours = number_format($tot_aHours, 0, '.',',');
   $tot_aPlts = number_format($tot_aPlts, 0, '.',',');
   $tot_tons = number_format($tot_tons, 0, '.',',');

   array_push($data, array('service'=>'',
                    	   'comm'=>'<b>Total</b>',
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
//        $mailTo1 .= ",ithomas@port.state.de.us";

        $mailTo  = "gbailey@port.state.de.us,";
        $mailTo .= "tkeefer@port.state.de.us,";
//        $mailTo .= "ffitzgerald@port.state.de.us,";
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

*/
?>
