<?

   $wday = date('w');

   if ($wday == 0)
	$wday = 7;   
   $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 6 - $wday  ,date("Y")));
   $eDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - $wday ,date("Y")));

   $eDate1 = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - $wday ,date("Y")));
   $eDate2 = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - $wday ,date("Y")-1));

   $lDate1 = date('m/d/Y',mktime(0,0,0,date("m"),0,date("Y")));
   $lDate2 = date('m/d/Y',mktime(0,0,0,date("m"),0,date("Y")-1));

//$eDate1 = "01/30/2005";
//$eDate2 = "01/30/2004";

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

   $arrHeading1 = array('prog'=>'<b>PROGRAM</b>', 'rev'=>'<b>REVENUE</b>', 'tons'=>'<b>TONNAGE</b>','hours'=>'<b>HOURS</b>','var'=>'<b>CHANGE</b>');
   $arrHeading = array('prog'=>'<b>Handled</b>', 'rev'=>'<b>FY 05</b>','d_rev'=>'<b>Change</b>', 'tons'=>'<b>FY 05</b>', 'd_tons'=>'<b>Change</b>', 'hours'=>'<b>FY 05</b>', 'd_hours'=>'<b>Change</b>','prof'=>'<b>R/H</b>','prod'=>'<b>T/H</b>','rate'=>'<b>R/T</b>');
   $arrHeading = array('prog'=>'<b>Handled</b>', 'rev'=>'<b>FY 06</b>','d_rev'=>'<b>Change</b>', 'tons'=>'<b>FY 06</b>', 'd_tons'=>'<b>Change</b>', 'hours'=>'<b>FY 06</b>', 'd_hours'=>'<b>Change</b>','prof'=>'<b>R/H</b>','prod'=>'<b>T/H</b>','rate'=>'<b>R/T</b>');
   $arrHeading3 = array('itg'=>'<b>Program</b>', 'desc'=>'<b>Commodity</b>');
   $arrCol1 = array('prog'=>array('width'=>90, 'justification'=>'left'),
                   'rev'=>array('width'=>130, 'justification'=>'center'),
                   'tons'=>array('width'=>130, 'justification'=>'center'),
                   'hours'=>array('width'=>130, 'justification'=>'center'),
                   'var'=>array('width'=>90, 'justification'=>'center'));
   $arrCol = array('prog'=>array('width'=>90, 'justification'=>'left'),
                   'rev'=>array('width'=>65, 'justification'=>'right'),
                   'd_rev'=>array('width'=>65, 'justification'=>'right'),
                   'tons'=>array('width'=>65, 'justification'=>'right'),
                   'd_tons'=>array('width'=>65, 'justification'=>'right'),
                   'hours'=>array('width'=>65, 'justification'=>'right'),
                   'd_hours'=>array('width'=>65, 'justification'=>'right'),
                   'prof'=>array('width'=>30, 'justification'=>'center'),
                   'prod'=>array('width'=>30, 'justification'=>'center'),
                   'rate'=>array('width'=>30, 'justification'=>'center'));
   $arrCol3 = array('itg'=>array('width'=>80, 'justification'=>'left'),
		   'desc'=>array('width'=>470, 'justification'=>'left'));
   $heading1 = array();
   $heading = array();
//   $heading2 = array();
   $heading3 = array();

   array_push($heading1, $arrHeading1);
   array_push($heading, $arrHeading);
//   array_push($heading2, $arrHeading2);
   array_push($heading3, $arrHeading3);

   $data = array();
   $data2 = array();
   $data3 = array();

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');

   $pdf->ezSetMargins(20,20,65,65);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');


   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $format = "Printed On: " . date('m/d/y g:i A');

   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->addText(45, 770, 8, "Confidential");
   $pdf->addText(480, 770,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   // Write out the intro.
   // Print Receiving Header
   $pdf->ezText("<b>OPERATIONAL</b>", 24, $center);
   $pdf->ezText("<b>(All revenues other than lease/rent)</b>", 14, $center);
   $pdf->ezSetDy(-5);
   $pdf->ezText("<b><i>As of $eDate1 </i></b>", 14, $center);
   $pdf->ezText("X-worse than last year.     ", 10, $right);
//   $pdf->ezSetDy(-5);


   $sql="select a.itg, NVL(b.rev,0)-NVL(c.rev,0), NVL(b.tons,0)-NVL(c.tons,0), NVL(b.hours,0)-NVL(c.hours,0),
        b.rev, b.tons, b.hours, c.rev, c.tons, c.hours, i.itg_group
        from
        (select distinct itg from commodity_itg) a,
        (select itg, sum(revenue) as rev, sum(weight) as tons, sum(hours) as hours
        from view_revenue_tons_hours
        where date_time >=to_date('07/01/2005','mm/dd/yyyy') and date_time <=to_date('$eDate1','mm/dd/yyyy')
        and (gl_code is null or gl_code <>3095)
        group by itg) b,
        (select itg, sum(revenue) as rev, sum(weight) as tons, sum(hours) as hours
        from view_revenue_tons_hours
        where date_time >=to_date('07/01/2004','mm/dd/yyyy') and date_time <=to_date('$eDate2','mm/dd/yyyy')
        and (gl_code is null or gl_code <>3095)
        group by itg) c, itg i
        where a.itg = b.itg(+) and a.itg = c.itg(+) and a.itg = i.itg(+)
        order by i.itg_group, NVL(b.rev,0)-NVL(c.rev, 0)";		
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $tot_rev = 0;
   $tot_tons = 0;
   $tot_hours = 0;
   $tot_rev1 = 0;
   $tot_tons1 = 0;
   $tot_hours1 = 0;
   $tot_rev2 = 0;
   $tot_tons2 = 0;
   $tot_hours2 = 0;

   $pre_group = 1;

   while (ora_fetch($cursor_bni)){
	$itg = ora_getcolumn($cursor_bni, 0);
	$d_rev = ora_getcolumn($cursor_bni, 1);
	$d_tons = ora_getcolumn($cursor_bni, 2);
	$d_hours = ora_getcolumn($cursor_bni, 3);
        $rev1 = ora_getcolumn($cursor_bni, 4); 
        $tons1 = ora_getcolumn($cursor_bni, 5);
        $hours1 = ora_getcolumn($cursor_bni, 6);
        $rev2 = ora_getcolumn($cursor_bni, 7);
        $tons2 = ora_getcolumn($cursor_bni, 8);
        $hours2 = ora_getcolumn($cursor_bni, 9);
	$group = ora_getcolumn($cursor_bni, 10);

	if (abs($rev1) < 100)
		$rev1 = 0;
	if (abs($rev2) < 100)
		$rev2 = 0;

	if ($hours1 >0){
		$t_h1 = $tons1/$hours1;
		$r_h1 = $rev1/$hours1;
	}else{
		$t_h1 = 0;
		$r_h1 = 0;
	}

	if ($hours2 > 0){
		$t_h2 = $tons2/$hours2;
		$r_h2 = $rev2/$hours2;
	}else{
		$t_h2 = 0;
		$r_h2 = 0;
	}
	
	if ($tons1 > 0){
		$r_t1 = $rev1/$tons1;
	}else{
		$r_t1 = 0;
	}
	if ($tons2 > 0){
		$r_t2 = $rev2/$tons2;
	}else{
		$r_t2 = 0;
	}

	
        $tot_rev += $rev;
        $tot_tons += $tons;
        $tot_hours += $hours;	
	$tot_rev1 += $rev1;
	$tot_tons1 += $tons1;
	$tot_hours1 += $hours1;
        $tot_rev2 += $rev2;
        $tot_tons2 += $tons2; 
        $tot_hours2 += $hours2;

        if ($rev1 < 0){
        	$dis_rev1 = "(".number_format(-$rev1,0,'.',',').")";
        }else{
		$dis_rev1 = number_format($rev1,0,'.',',');
	} 

        if ($rev2 < 0){
                $dis_rev2 = "(".number_format(-$rev2,0,'.',',').")";
        }else{
                $dis_rev2 = number_format($rev2,0,'.',',');
        }


	if ($d_rev < 0){
		$dif_rev = "(".number_format(-$d_rev,0,'.',',').")";
	}else{
		$dif_rev = number_format($d_rev,0,'.',',');
	}

        if ($d_tons < 0){
                $dif_tons = "(".number_format(-$d_tons,0,'.',',').")";
        }else{
                $dif_tons = number_format($d_tons,0,'.',',');
        }
        if ($d_hours < 0){
                $dif_hours = "(".number_format(-$d_hours,0,'.',',').")";
        }else{
                $dif_hours = number_format($d_hours,0,'.',',');
        }
/*
	if ($itg =="Autos")
		$itg = "Vehicles";
*/
	if ($itg =="Z-No Commodity"){
		$dis_prod = "";
		$dis_rate = "";
		$dis_prof = "";
		$nc_rev1 = $dis_rev1;
		$nc_tons1 = $tons1;
		$nc_hours1 = $hours1;
		$nc_d_rev = $dif_rev;
		$nc_d_tons = $dif_tons;
		$nc_d_hours = $dif_hours;
	}else{
		$dis_prod = number_format($t_h1-$t_h2,2,'.',',');
		$dis_rate = number_format($r_t1-$r_t2,2,'.',',');
		$dis_prof = number_format($r_h1-$r_h2,2,'.',',');
	}

	if (round($r_h1-$r_h2,1) >= 0.1){
		$dis_prof = "";
	}else if (round($r_h2-$r_h1,1) >= 0.1){
		$dis_prof = "X";
	}else{
		$dis_prof = "";
	}
        if (round($t_h1-$t_h2,1) >= 0.1){
                $dis_prod = "";
        }else if (round($t_h2-$t_h1,1) >= 0.1){
                $dis_prod = "X";
        }else{
                $dis_prod = "";
        }
        if (round($r_t1-$r_t2,1) >= 0.1){
                $dis_rate = "";
        }else if (round($r_t2-$r_t1,1) >= 0.1){
                $dis_rate = "X";
        }else{
                $dis_rate = "";
        }

	if ($rev1 <>0 || $tons1 <>0 || $hours1 <>0 || $rev2<>0 || $tons2 <>0 || $hours2 <>0){
	   if ($group == $pre_group && $itg <> "Z-No Commodity" ){
                array_push($data, array('prog'=>$itg,
                                        'rev'=>$dis_rev1,
                                        'tons'=>number_format($tons1,0,'.',','),
                                        'hours'=>number_format($hours1,0,'.',','),
                                        'd_rev'=>$dif_rev,
                                        'd_tons'=>$dif_tons,
                                        'd_hours'=>$dif_hours,
                                        'prof'=>$dis_prof,
                                        'prod'=>$dis_prod,
                                        'rate'=>$dis_rate));

	   }else if ($group <> $pre_group && $group == 2){
		$pre_group = $group;
                array_push($data, array('prog'=>"Other",
                                        'rev'=>$nc_rev1,
                                        'tons'=>number_format($nc_tons1,0,'.',','),
                                        'hours'=>number_format($nc_hours1,0,'.',','),
                                        'd_rev'=>$nc_d_rev,
                                        'd_tons'=>$nc_d_tons,
                                        'd_hours'=>$nc_d_hours));
                array_push($data, array('prog'=>'<b>Unhandled</b>',
                                        'rev'=>"",
                                        'tons'=>"",
                                        'hours'=>"",
                                        'd_rev'=>"",
                                        'd_tons'=>"",
                                        'd_hours'=>""));
                array_push($data, array('prog'=>$itg,
                                        'rev'=>$dis_rev1,
                                        'tons'=>number_format($tons1,0,'.',','),
                                        'hours'=>number_format($hours1,0,'.',','),
                                        'd_rev'=>$dif_rev,
                                        'd_tons'=>$dif_tons,
                                        'd_hours'=>$dif_hours,
                                        'prof'=>$dis_prof,
                                        'prod'=>$dis_prod,
                                        'rate'=>$dis_rate));
	   }else if ($group <> $pre_group && $group == 3){
		$pre_group = $group;
                array_push($data, array('prog'=>'<b>New</b>',
                                        'rev'=>"",
                                        'tons'=>"",
                                        'hours'=>"",
                                        'd_rev'=>"",
                                        'd_tons'=>"",
                                        'd_hours'=>""));
                array_push($data, array('prog'=>$itg,
                                        'rev'=>$dis_rev1,
                                        'tons'=>number_format($tons1,0,'.',','),
                                        'hours'=>number_format($hours1,0,'.',','),
                                        'd_rev'=>$dif_rev,
                                        'd_tons'=>$dif_tons,
                                        'd_hours'=>$dif_hours,
                                        'prof'=>$dis_prof,
                                        'prod'=>$dis_prod,
                                        'rate'=>$dis_rate));
	   }
	} 

  }
   array_push($data, array('prog'=>'<b>Total</b>',
                           'rev'=>'<b>'.number_format($tot_rev1,0,'.',',').'</b>',
                           'tons'=>'<b>'.number_format($tot_tons1,0,'.',',').'</b>',
                           'hours'=>'<b>'.number_format($tot_hours1,0,'.',',').'</b>',
                           'd_rev'=>'<b>'.number_format($tot_rev1-$tot_rev2,0,'.',',').'</b>',
                           'd_tons'=>'<b>'.number_format($tot_tons1-$tot_tons2,0,'.',',').'</b>',
                           'd_hours'=>'<b>'.number_format($tot_hours1-$tot_hours2,0,'.',',').'</b>'));


   $pdf->ezSetDy(-5);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   $sql = "select itg, description from commodity_itg order by itg, description";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
   
   $pre_itg = "";
   while(ora_fetch($cursor_bni)){
	$itg = ora_getcolumn($cursor_bni, 0);
	$desc = ora_getcolumn($cursor_bni, 1);

	if ($itg =="Z-No Commodity")
		$itg = "Other";

	if ($pre_itg =="" || $itg == $pre_itg){
		$pre_itg = $itg;

		if( $display_desc ==""){
			$display_desc = $desc;
		}else{
			$display_desc .= ", ".$desc;
		}
	}else{
		array_push($data3, array('itg'=>$pre_itg,
					 'desc'=>$display_desc));
		$pre_itg = $itg;
		$display_desc = $desc;
	}
   }
	array_push($data3, array('itg'=>$pre_itg,
                                 'desc'=>$display_desc));
   
    
   $pdf->ezNewPage();
   $pdf->ezText("<b>Program Detail</b>", 24, $center);
   $pdf->ezSetDy(-10);

   $pdf->ezTable($heading3, $arrHeading3, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol3));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($data3, $arrHeading3, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol3));

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "lstewart@port.state.de.us";
//        $mailTo1 .= ",ithomas@port.state.de.us";

        $mailTo = "gbailey@port.state.de.us,";
        $mailTo .="tkeefer@port.state.de.us,";
        $mailTo .="fvignuli@port.state.de.us,";
        $mailTo .="ithomas@port.state.de.us,";
        $mailTo .="rhorne@port.state.de.us,";
        $mailTo .="skennard@port.state.de.us,";
        $mailTo .="parul@port.state.de.us";

        $mailsubject = "RTH INDEX";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";
        $mailheaders .= "Cc: vfarkas@port.state.de.us,jjaffe@port.state.de.us,lstewart@port.state.de.us\r\n";
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


?>
