<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $dayOfWeek = date('w');

   $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") -2 ,date("Y")));
   $eDate = $sDate;


   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("</body></html>");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor1 = ora_open($conn);
   $cursor2 = ora_open($conn);
   $tHours = 0;


   $arrHeading1 = array('sup'=>'','comm'=>'','service'=>'','desp'=>'', 'sHours'=>'<b>Straight Time</b>', 'other'=>'<b>OT/DT/MH/DF</b>');
   $arrHeading2 = array('sup'=>'<b>Supervisor</b>', 'comm'=>'<b>Commodity</b>','service'=>'<b>Service</b>','desp'=>'','pReg'=>'<b>Paid</b>', 'lST'=>'<b>Tickets</b>', 'sVar'=>'<b>Diff</b>', 'pOther'=>'<b>Paid</b>','lOther'=>'<b>Tickets</b>','oVar'=>'<b>Diff</b>');

                   
   $arrCol1 =array('sup'=>array('width'=>100, 'justification'=>'left'),
                   'comm'=>array('width'=>170, 'justification'=>'left'),
                   'service'=>array('width'=>50, 'justification'=>'left'),
                   'desp'=>array('width'=>150, 'justification'=>'left'),
                   'sHours'=>array('width'=>140, 'justification'=>'center'),
                   'other'=>array('width'=>140, 'justification'=>'center'),);

   $arrCol2 = array('sup'=>array('width'=>100, 'justification'=>'left'),
                   'comm'=>array('width'=>170, 'justification'=>'left'),
                   'service'=>array('width'=>50, 'justification'=>'left'),
                   'desp'=>array('width'=>150, 'justification'=>'left'),
                   'pReg'=>array('width'=>45, 'justification'=>'center'),
                   'lST'=>array('width'=>55, 'justification'=>'center'),
                   'sVar'=>array('width'=>40, 'justification'=>'center'),
                   'pOther'=>array('width'=>45, 'justification'=>'center'),
                   'lOther'=>array('width'=>55, 'justification'=>'center'),
                   'oVar'=>array('width'=>40, 'justification'=>'center'));


   $heading1 = array();
   $heading2 = array();
   array_push($heading1, $arrHeading1);
   array_push($heading2, $arrHeading2);
   $data = array();

   $pre_sup = "";
   $dis_sup = "";

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



   $tot_pReg = 0;
   $tot_lReg = 0;
   $tot_pOther = 0;
   $tot_lOther = 0;

   $sql = "select * from (
           select distinct u.user_name       
	   from hourly_detail h, commodity c, lcs_user u
           where h.user_id = u.user_id and c.commodity_code = h.commodity_code
           and h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
           union
           select distinct u.user_name
           from labor_ticket_header h, commodity c, lcs_user u
           where h.user_id = u.user_id and c.commodity_code = h.commodity_code
           and h.service_date >= to_date('$sDate','mm/dd/yyyy') and h.service_date <= to_date('$eDate','mm/dd/yyyy') ) a
           order by user_name";

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   while (ora_fetch($cursor)){
        $sup = ora_getcolumn($cursor, 0);
        $stot_pReg = 0;
        $stot_lReg = 0;
        $stot_pOther = 0;
        $stot_lOther = 0;
        $pre_comm = "";

   	$sql = "select * from (
           	select distinct commodity_name, h.commodity_code, substr(service_code, 0, 3) as service_group
           	from hourly_detail h, commodity c, lcs_user u
           	where h.user_id = u.user_id and c.commodity_code = h.commodity_code 
           	and h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
		and lower(u.user_name) ='".strtolower($sup)."'
           	union
           	select distinct commodity_name, h.commodity_code, to_char(service_group)
           	from labor_ticket_header h, commodity c, lcs_user u
           	where h.user_id = u.user_id and c.commodity_code = h.commodity_code
           	and h.service_date >= to_date('$sDate','mm/dd/yyyy') and h.service_date <= to_date('$eDate','mm/dd/yyyy')
		 and lower(u.user_name) ='".strtolower($sup)."' ) a
           	order by commodity_name, service_group";

   	$statement = ora_parse($cursor1, $sql);
   	ora_exec($cursor1);

   	while (ora_fetch($cursor1)){
   		$comm = ora_getcolumn($cursor1, 0);
     		$commodity_code = ora_getcolumn($cursor1, 1);
     		$service = ora_getcolumn($cursor1, 2);
		
		if ($sup <>$pre_sup){
			$dis_sup = $sup;
			$pre_sup = $sup;
		}else{
			$dis_sup = "";
		}

		if ($comm <> $pre_comm ){
			$pre_comm = $comm;
			$dis_comm = $comm;
		}else{
			$dis_comm = "";
		}

    		$sql = "select sum(duration)
               		from hourly_detail h, lcs_user u
             		where h.user_id = u.user_id and
           		lower(u.user_name) ='".strtolower($sup)."' and commodity_code = $commodity_code and
             		h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
           		and earning_type_id = 'REG' and substr(service_code, 0, 3) = $service";

    		$statement = ora_parse($cursor2, $sql);
    		ora_exec($cursor2);

    		if (ora_fetch($cursor2)){
          		$pReg = ora_getcolumn($cursor2, 0);
       		}else{
             		$pReg = 0;
     		}

    		$sql = "select sum(duration)
             		from hourly_detail h, lcs_user u
           		where h.user_id = u.user_id and
           		lower(u.user_name) ='".strtolower($sup)."' and commodity_code = $commodity_code and
              		h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
             		and earning_type_id <> 'REG' and substr(service_code, 0, 3) = $service";

     		$statement = ora_parse($cursor2, $sql);
        	ora_exec($cursor2);

       		if (ora_fetch($cursor2)){
        		$pOther = ora_getcolumn($cursor2, 0);
     		}else{
        		$pOther = 0;
   		}

   		$sql = "select sum(hours) from labor_ticket where rate_type = 'ST'  and ticket_num in
            		(select distinct h.ticket_num from labor_ticket_header h, labor_ticket t, lcs_user u
            		where t.ticket_num = h.ticket_num and u.user_id = h.user_id and
            		lower(u.user_name) ='".strtolower($sup)."' and h.service_date >= to_date('$sDate','mm/dd/yyyy') and
              		h.service_date <= to_date('$eDate','mm/dd/yyyy')
              		and h.commodity_code = '$commodity_code' and h.service_group = $service) ";
    		$statement = ora_parse($cursor2, $sql);
        	ora_exec($cursor2);
    		if (ora_fetch($cursor2)){
           		$lReg = ora_getcolumn($cursor2, 0);
    		}else{
              		$lReg = 0;
      		}

    		$sql = "select sum(hours) from labor_ticket where rate_type <> 'ST'  and ticket_num in
          		(select distinct h.ticket_num from labor_ticket_header h, labor_ticket t, lcs_user u
            		where t.ticket_num = h.ticket_num and u.user_id = h.user_id and
            		lower(u.user_name) ='".strtolower($sup)."' and h.service_date >= to_date('$sDate','mm/dd/yyyy') and
            		h.service_date <= to_date('$eDate','mm/dd/yyyy')
           		and h.commodity_code = '$commodity_code' and h.service_group = $service) ";
    		$statement = ora_parse($cursor2, $sql);
     		ora_exec($cursor2);
      		if (ora_fetch($cursor2)){
           		$lOther = ora_getcolumn($cursor2, 0);
      		}else{
              		$lOther = 0;
      		}


   		$sql = "select service_name from service where service_code like '$service%' order by service_code";
     		$statement = ora_parse($cursor2, $sql);
    		ora_exec($cursor2);
     		if (ora_fetch($cursor2)){
          		$service_name = ora_getcolumn($cursor2, 0);
            		$pos = strpos($service_name, "Sup");
             		if ($pos > 0)  $service_name = substr($service_name, 0, $pos-1);
       		}else{
            		$service_name = $service;
     		}

     		$stot_pReg += $pReg;
      		$stot_lReg += $lReg;
      		$stot_pOther += $pOther;
       		$stot_lOther += $lOther;

                $tot_pReg += $pReg;
                $tot_lReg += $lReg;
                $tot_pOther += $pOther;
                $tot_lOther += $lOther;


      		array_push($data, array('sup'=>$dis_sup,
                                	'comm'=>$comm,
                         	  	'service'=>$service.X,
                               		'desp'=>$service_name,
                              		'pReg'=>number_format($pReg,1,'.',','),
               		               	'lST'=>number_format($lReg,1,'.',','),
                               		'sVar'=>number_format($pReg-$lReg,1,'.',','),
                              		'pOther'=>number_format($pOther,1,'.',','),
                            		'lOther'=>number_format($lOther,1,'.',','),
                              		'oVar'=>number_format($pOther - $lOther,1,'.',',')));
   	}
   	array_push($data, array('sup'=>'<b>Sub Total</b>',
                           	'comm'=>'',
                       	   	'service'=>'',
                           	'desp'=>'',
                           	'pReg'=>'<b>'.number_format($stot_pReg,1,'.',',').'</b>',
                           	'lST'=>'<b>'.number_format($stot_lReg,1,'.',',').'</b>',
                           	'sVar'=>'<b>'.number_format($stot_pReg-$stot_lReg,1,'.',',').'</b>',
                           	'pOther'=>'<b>'.number_format($stot_pOther,1,'.',',').'</b>',
                           	'lOther'=>'<b>'.number_format($stot_lOther,1,'.',',').'</b>',
                           	'oVar'=>'<b>'.number_format($stot_pOther - $stot_lOther,1,'.',',').'</b>'));
   }
   array_push($data, array('sup'=>'<b>Total</b>',
                     	   'comm'=>'',
                           'service'=>'',
                           'desp'=>'',
                           'pReg'=>'<b>'.number_format($tot_pReg,1,'.',',').'</b>',
                           'lST'=>'<b>'.number_format($tot_lReg,1,'.',',').'</b>',
                           'sVar'=>'<b>'.number_format($tot_pReg-$tot_lReg,1,'.',',').'</b>',
                           'pOther'=>'<b>'.number_format($tot_pOther,1,'.',',').'</b>',
                           'lOther'=>'<b>'.number_format($tot_lOther,1,'.',',').'</b>',
                           'oVar'=>'<b>'.number_format($tot_pOther - $tot_lOther,1,'.',',').'</b>'));



   	$pdf->ezSetDy(-10);
        $pdf->ezText("<b>Daily Report</b>", 24, $center);
   	$pdf->ezText("<b>Hours Paid vs. Labor Ticketed</b>", 24, $center);
   	$pdf->ezSetDy(-10);
	$day = date('l', strtotime($sDate));
   	$pdf->ezText("<b><i>for: $day $sDate </i></b>", 18, $center);
   	$pdf->ezSetDy(-15);

   	$pdf->ezSetDy(-15);
  	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
   	$pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   	$pdf->ezTable($heading2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

   	$pdf->ezTable($data, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));
   
//   $pdf->ezStream();

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "rwang@port.state.de.us";
//	$mailTo1 .=",ithomas@port.state.de.us";

        $mailTo  = "vfarkas@port.state.de.us,";
        $mailTo .= "wstans@port.state.de.us,";

	$tot_paid = $tot_pReg + $tot_pOther;
	$tot_ticketed = $tot_lReg + $tot_lOther;
	$pct = number_format(100*$tot_ticketed/$tot_paid, 0, '.',',');
	$dis_date = date('m/d/y',strtotime($sDate));
        $mailsubject = "Hours for $dis_date - Paid: $tot_paid  Ticketed: $tot_ticketed  = $pct%";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";

//        $mailheaders .= "Cc: ffitzgerald@port.state.de.us,";
	$mailheaders .= "Cc: ithomas@port.state.de.us,";
	$mailheaders .= "parul@port.state.de.us,";
	$mailheaders .= "rwang@port.state.de.us,";

        $mailheaders .= "dcastag@port.state.de.us,";
        $mailheaders .= "ddonofrio@port.state.de.us,";
        $mailheaders .= "dgamble@port.state.de.us,";
        $mailheaders .= "gharris@port.state.de.us,";
        $mailheaders .= "mcutler@port.state.de.us,";
        $mailheaders .= "michaelp@port.state.de.us,";
        $mailheaders .= "paulk@port.state.de.us,";
        $mailheaders .= "rbetts@port.state.de.us,";
        $mailheaders .= "rcorbin@port.state.de.us,";
        $mailheaders .= "rsmith@port.state.de.us\r\n";

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
        $Content.="Content-Type: application/pdf; name=\"Daily Report Hours Paid vs. Labor Ticketed.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$File;
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n";

//      mail($mailTo1, $mailsubject, $Content, $mailheaders);
       mail($mailTo, $mailsubject, $Content, $mailheaders);



?>
