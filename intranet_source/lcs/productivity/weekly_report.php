<?

   $wday = date('w');

   if ($wday == 0)
	$wday = 7;   
   $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 6 - $wday ,date("Y")));
   $eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - $wday ,date("Y")));
$sDate = "12/20/2004";
$eDate = "12/26/2004";
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
   $cursor_bni1 = ora_open($conn_bni);
   $cursor_bni2 = ora_open($conn_bni);
/*
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
*/   
   $productivity = "daily_productivity";


   $arrHeading1 = array('comm'=>'','sup'=>'', 'wing'=>'', 'plan'=>'<b>Plan</b>', 'actual'=>'<b>Actual</b>', 'aProd'=>'','budget'=>'','aProd_budget'=>'<b>Productivity</b>', 'flag'=>'');
   $arrHeading1 = array('sup'=>'', 'comm'=>'', 'wing'=>'', 'actual'=>'Actual', 'aProd'=>'','budget'=>'','aProd_budget'=>'Productivity', 'flag'=>'');
   $arrHeading1 = array('comm'=>'<b>Program</b>','service'=>'<b>Service</b>', 'actual'=>'Actual','aProd_budget'=>'Productivity(T/H)');

   $arrHeading = array('sup'=>'<b>Supervisor</b>', 'comm'=>'<b>Comm</b>', 'wing'=>'<b>Location</b>', 'num'=>'<b>Hire</b>', 'hours'=>'<b>Hours</b>', 'plt'=>'<b>PLT</b>', 'lab'=>'<b>Labor</b>','aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>PLT</b>', 'aProd'=>'<b>Productivity</b>', 'budget'=>'<b>Budget</b>','aProd_budget'=>'<b>- Budget</b>', 'flag'=>'Summary');
   $arrHeading = array('comm'=>'<b>Program</b>', 'service'=>'<b>Service</b>', 'loc'=>'<b>Location</b>','aHours'=>'<b>Hours</b>', 'aPlt'=>'<b>Plts</b>', 'tons'=>'<b>Tons</b>','budget'=>'<b>Budget</b>','aProd'=>'Prod (T/H)', 'result'=>'<b>Variance</b>');

   $arrCol1 = array('service'=>array('width'=>100, 'justification'=>'left'),
                   'comm'=>array('width'=>110, 'justification'=>'center'),
 //                  'plan'=>array('width'=>90, 'justification'=>'center'),
                   'actual'=>array('width'=>135, 'justification'=>'center'),
                   'aProd_budget'=>array('width'=>130,'justification'=>'center'));

   $arrCol = array('service'=>array('width'=>100, 'justification'=>'left'),
                   'comm'=>array('width'=>90, 'justification'=>'left'),
                   'loc'=>array('width'=>80, 'justification'=>'left'),
                //   'num'=>array('width'=>45, 'justification'=>'center'),
 //                  'hours'=>array('width'=>45, 'justification'=>'center'),
 //                  'plt'=>array('width'=>45, 'justification'=>'center'),
                //   'lab'=>array('width'=>45, 'justification'=>'center'),
                   'aHours'=>array('width'=>48, 'justification'=>'center'),
                   'aPlt'=>array('width'=>48, 'justification'=>'center'),
                   'tons'=>array('width'=>48, 'justification'=>'center'),
		   'aProd'=>array('width'=>48, 'justification'=>'center'),
                   'budget'=>array('width'=>48, 'justification'=>'center'),
		   'result'=>array('width'=>55, 'justification'=>'center'), );

   $heading1 = array();
   $heading = array();
   array_push($heading1, $arrHeading1);
   array_push($heading, $arrHeading);

   $data = array();

   $no_budget = "";
   $no_hours = "";
   $no_tons = ""; 

  $vDate = $sDate;

   $date_display1 = date('l m/d/y', strtotime($sDate));
   $date_display2 = date('l m/d/y', strtotime($eDate));

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portait');

   $pdf->ezSetMargins(40,40,50,40);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
//   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm','font'=>'Symbol.afm');

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
   $pdf->ezText("<b>Weekly Productivity</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i>Week of $sDate </i></b>", 14, $center);
//   $pdf->ezText("X-worse than budget.", 10, $right);

   //get lr_num
   $sql = "select lr_num from voyage 
	   where DATE_DEPARTED >=to_date('$sDate','mm/dd/yyyy') and 
	   DATE_DEPARTED  <= to_date('$eDate','mm/dd/yyyy')+1 
           order by lr_num";

   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
   while(ora_fetch($cursor_bni)){
	$lr = ora_getcolumn($cursor_bni, 0);
	if ($vessels <> ""){
	 	$vessels .= ", ". $lr;
	}else{
		$vessels = $lr;
	}
   }

   $sql = "select distinct itg from $productivity p, commodity_itg c
           where p.commodity = c.commodity_code  
           and  date_time >= to_date('$sDate','mm/dd/yyyy')
           and  date_time <= to_date('$eDate','mm/dd/yyyy')
           order by itg";
   $sql = "select distinct itg from commodity_itg"; 
   $statement = ora_parse($cursor_bni2, $sql);
   ora_exec($cursor_bni2);
 
   while (ora_fetch($cursor_bni2)){
	$itg = ora_getcolumn($cursor_bni2, 0);	
	
	$pre_itg ="";
	$dis_itg ="";
	$et_aHours = 0;

   	//Backhaul
	if ($vessels <>""){
        	$SQL[0]="select a.*, b.budget from (
                	select c.itg, 'Backhaul' as service_type, location, sum(hours), sum(qty),sum(tonnage),''
                	from $productivity p, commodity_itg c
                	where p.commodity = c.commodity_code and c.itg = '$itg' and
                	service_type = 'BACKHAUL' and p.vessel in ($vessels)  
                	group by c.itg, location) a, itg_budget b
                	where a.service_type = b.type(+) and a.itg = b.itg(+)
                 	order by a.itg, a.location";
	}
        $SQL[1]="select a.*, b.budget from (
                select  c.itg, service_type, location, sum(hours), sum(qty),sum(tonnage), s.id
                from $productivity p, service_type s, commodity_itg c
                where s.type = p.service_type and p.commodity = c.commodity_code and c.itg = '$itg' and
                s.id in (1,4,5,6) and
                date_time >= to_date('$sDate','mm/dd/yyyy') and date_time <= to_date('$eDate','mm/dd/yyyy')
                group by service_type, c.itg, s.id, location) a, itg_budget b
                where a.service_type = b.type(+) and a.itg = b.itg(+)
                order by a.itg, a.id, a.location";
        //Other
        $SQL[2]="select c.itg, 'Other', '', sum(hours), sum(qty), sum(tonnage),'',''
                from $productivity p, commodity_itg c
                where p.commodity = c.commodity_code and c.itg = '$itg' and
                service_type in ('MAINTENANCE', 'TERMINAL SERVICE', 'NON REVENUE', 'STAND BY') and
		c.itg not in ('Dole', 'Autos') and
                date_time >= to_date('$sDate','mm/dd/yyyy') and date_time <= to_date('$eDate','mm/dd/yyyy')
                group by 'Others', c.itg";

	if ($vessels<>""){
		$start = 0;
	}else{
		$start = 1;
	}
	for($i = $start; $i<3; $i++){
        	$statement = ora_parse($cursor_bni, $SQL[$i]);
        	ora_exec($cursor_bni);
        	while(ora_fetch($cursor_bni)){
                	//$count++;
                	$itg = ora_getcolumn($cursor_bni, 0);
			$service = ora_getcolumn($cursor_bni,1);
			$location = ora_getcolumn($cursor_bni, 2);
                	$aHours = ora_getcolumn($cursor_bni, 3);
                	$aPlt = ora_getcolumn($cursor_bni, 4);
                	$tons = ora_getcolumn($cursor_bni, 5);
                	$budget = ora_getcolumn($cursor_bni, 7);
			
			$service = ucwords(strtolower($service));

			if ($aHours >0){
				$prod = round($tons/$aHours, 2);
				$hours = $aHours;
			}else{
				$prod = "null";
				$hours = "null";
			}

			if ($budget > 0){
				$bud = $budget;
			}else{
				$bud = "null";
			}

			if ($aPlt > 0){
				$plt = $aPlt;
			}else{
				$plt = "null";
			}
			
			if ($tons > 0){
				$ton = $tons;
			}else{
				$ton = "null";
			}
	
			if ($prod > 0 && $budget > 0){
				$var = $prod - $budget;
			}else{
				$var = "null";
			}
			
			
                        $sql = "insert into weekly_productivity
                                (week, itg, service_type, location, hours, qty, tonnage, budget, productivity, variance)
                                values (to_date('$sDate','mm/dd/yyyy'), '$itg','$service','$location', $hours, $plt, 
				$ton,$bud,$prod, $var)"; 
echo $sql."\r\n";
   			$statement = ora_parse($cursor_bni1, $sql);
   			ora_exec($cursor_bni1);


			if ($i == 2){
				$budget = "N/A";
                	}else if($budget =="" ){
                        	$budget = "TBD";
                	}else{
                        	$budget = number_format($budget, 2, '.',',');
                	}

                	if ($dis_itg =="Z-No Commodity")
                        	$dis_itg = "No Commodity";
/*
			if ($i == 2 && $aHours ==0 && $tons == 0 ){
				$prods = "-";
			}else if ($i == 2){
				$prods = "N/A";
	            	}else if ($aHours ==0){
                        	$prods = "-";
                	}else if ($tons ==0){
                        	$prods = "0";
                	}else{
                        	$prods = number_format($tons/$aHours, 2, '.',',');
                	}
*/
                        if ($aHours ==0 && $tons == 0 ){
                                $prods = "-";
			}else if ($i == 2 ){
                                $prods = "N/A";
                        }else if ($aHours ==0){
                                $prods = "N/A";
                        }else{
                                $prods = number_format($tons/$aHours, 2, '.',',');
                        }

			if (is_numeric($budget) && is_numeric($prods)){
				$result = number_format($prods - $budget,2,'.',',');
			}else{
				$result = "";
			}
 
			if (($prods <> "-" && $prods <>"0") && $itg <> "Z-No Commodity"){
				if($pre_itg <>$itg){
                        		$pre_itg = $itg;
                        		$dis_itg = $itg;
				}else{
					$dis_itg ="";
				}

                		if($service <> "Non Revenue" && $itg <>"Z-No Commodity"){
                			if ($budget == "TBD"){
                        			if ($no_budget ==""){
                                			$no_budget = $itg."-".$service;
                        			}else if ($dis_itg <> ""){
                                			$no_budget .= "; ".$dis_itg."-".$service;
                        			}else{
                                			$no_budget .= ", ".$service;
                        			}
					}
				}
				
				$dis_aHours = number_format($aHours, 0, '.',',');
				$dis_aPlt = number_format($aPlt, 0, '.',',');
				$dis_tons = number_format($tons, 0, '.',',');
	
				if ($dis_aHours == "0"){
					$dis_aHours = "N/A";
				}

				if ($dis_aPlt == "0"){
					$dis_aPlt = "N/A";
				}

				if ($dis_tons == "0"){
					$dis_tons = "N/A";
				}

                		array_push($data, array('service'=>$service,
                                        		'comm'=>'<b><i>'.$dis_itg.'</i></b>',
							'loc'=>$location,
                	                	        'aHours'=>$dis_aHours,
		                                        'aPlt'=>$dis_aPlt, 
                        		                'tons'=>$dis_tons,
                                        		'budget'=>$budget,
                                        		'aProd'=>$prods,
							'result'=>$result));
				if ($i ==0) $isBackhaul = true;

                        	$t_pHire += $pHire;
                        	$t_pHours += $pHours;
                        	$t_pPlts += $pPlts;
                        	$t_aHours += $aHours;
                        	$t_aPlts += $aPlt;
                        	$t_tons += $tons;
                	}else{
				$et_aHours += $aHours;
			}	
		}		
        }
	$eTot_aHours += $et_aHours;

	if ($itg =="Z-No Commodity")
		$itg = "No Commodity";
	if ($et_aHours > 0){
		if ($dis_pro ==""){
			$dis_pro = $itg.":".number_format($et_aHours,0,'.',',');
		}else {
			$dis_pro .= ", ".$itg.":".number_format($et_aHours,0,'.',',');
		}		
	}
   }

   $t_pHours = number_format($t_pHours, 0, '.',',');
   $t_pPlts = number_format($t_pPlts, 0,'.',',');
   $t_aHours = number_format($t_aHours, 0, '.',',');
   $t_aPlts = number_format($t_aPlts, 0, '.',',');
   $t_tons = number_format($t_tons, 0, '.',',');


/*
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
*/
   array_push($data, array('service'=>'<b>Total</b>',
                           'comm'=>'',
                           'num'=>'',
                           'hours'=>'<b>'.$t_pHours.'</b>',
                           'plt'=>'<b>'.$tot_pPlts.'</b>',
                           'lab'=>'',
                           'aHours'=>'<b>'.$t_aHours.'</b>',
                           'aPlt'=>'<b>'.$t_aPlts.'</b>',
                           'tons'=>'<b>'.$t_tons.'</b>',
                           'budget'=>'',
                           'aProd'=>''));



  // $pdf->ezSetDy(-15);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
//   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezSetDy(-10);
   $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));

   $pdf->ezSetDy(-15);
   $pdf->ezText("<b><i>*Backhaul productivity is calculated on the day vessel departs.</i></b>", 10, $left);

   if ($eTot_aHours > 0){
	$pdf->ezSetDy(-15);
        $pdf->ezText("<b><i>*$eTot_aHours hours not included ($dis_pro)</i></b>", 10, $left);
   }	

   

/*	
   if ($no_budget <>""){	
   	$pdf->ezSetDy(-15);
   	$pdf->ezText("<b><i> *There is no established budget for:</i></b> <i> $no_budget</i>", 10, $left);
   }
*/
/*   if ($no_hours <>""){
   	$pdf->ezText("<b><i> *Tons moved but no hours:</i></b>", 10, $left);
   	$pdf->ezText("<i> $no_hours</i>", 9, $left);
   	$pdf->ezSetDy(-5);
   }
   if ($no_tons <>""){
   	$pdf->ezText("<b><i> *Have hours but no tons:</i></b>", 10, $left);
   	$pdf->ezText("<i> $no_tons</i>", 9, $left);
        $pdf->ezSetDy(-5);
   }
   if ($ncHours > 0){
        $pdf->ezText("<b><i> *$ncHours hrs was spent on unknown commodity</i></b>", 10, $left);
   }
*/
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
//        $mailheaders .= "Cc: wstans@port.state.de.us,jjaffe@port.state.de.us,rwang@port.state.de.us\r\n";
//        $mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
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

        mail($mailTo1, $mailsubject, $Content, $mailheaders);
//    	mail($mailTo, $mailsubject, $Content, $mailheaders);


?>
