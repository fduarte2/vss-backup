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

//$eDate1 = "10/31/2004";
//$eDate2 = "10/31/2003";

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

/*
   $arrHeading1 = array('prog'=>'', 'year1'=>'<b>TO-DATE THIS YEAR</b>', 'year2'=>'<b>TO-DATE LAST YEAR</b>');
   $arrHeading1 = array('prog'=>'', 'year1'=>'<b>TO-DATE THIS YEAR</b>', 'year2'=>'<b>TO-DATE LAST YEAR</b>','var'=>'<b>Change</b>');
   $arrHeading1 = array('prog'=>'<b>PROGRAM</b>', 'rev'=>'<b>REVENUE</b>', 'tons'=>'<b>TONNAGE</b>','hours'=>'<b>HOURS</b>','var'=>'<b>CHANGE</b>');

   $arrHeading = array('prog'=>'<b>Program</b>', 'rev1'=>'<b>Revenue</b>', 'tons1'=>'<b>Tons</b>', 'hours1'=>'<b>Hours</b>', 'rev2'=>'<b>Revenue</b>', 'tons2'=>'<b>Tons</b>', 'hours2'=>'<b>Hours</b>');
   $arrHeading = array('prog'=>'<b>Program</b>', 'rev1'=>'<b>Revenue</b>', 'tons1'=>'<b>Tons</b>', 'hours1'=>'<b>Hours</b>', 'rev2'=>'<b>Revenue</b>', 'tons2'=>'<b>Tons</b>', 'hours2'=>'<b>Hours</b>','prof'=>'<b>Profit</b>','prod'=>'<b>Prod</b>','rate'=>'<b>Rate</b>');
   $arrHeading = array('prog'=>'<b>Program</b>', 'rev1'=>'<b>R</b>', 'tons1'=>'<b>T</b>', 'hours1'=>'<b>H</b>', 'rev2'=>'<b>R</b>', 'tons2'=>'<b>T</b>', 'hours2'=>'<b>H</b>','prof'=>'<b>Profit</b>','prod'=>'<b>Prod</b>','rate'=>'<b>Rate</b>');
   $arrHeading = array('prog'=>'<b>Handled</b>', 'rev'=>'<b>FY 05</b>','d_rev'=>'<b>Change</b>', 'tons'=>'<b>FY 05</b>', 'd_tons'=>'<b>Change</b>', 'hours'=>'<b>FY 05</b>', 'd_hours'=>'<b>Change</b>','prof'=>'<b>R/H</b>','prod'=>'<b>T/H</b>','rate'=>'<b>R/T</b>');
*/
   $arrHeading2 = array('prog'=>'<b>Customer</b>', 'rev1'=>'<b>FY 05</b>', 'rev2'=>'<b>CHANGE</b>');
/*
   $arrCol1 = array('prog'=>array('width'=>100, 'justification'=>'left'),
		   'year_tot'=>array('width'=>210, 'justification'=>'center'),
                   'year1'=>array('width'=>210, 'justification'=>'center'),
                   'year2'=>array('width'=>210, 'justification'=>'center'));
   $arrCol1 = array('prog'=>array('width'=>90, 'justification'=>'left'),
                   'year1'=>array('width'=>165, 'justification'=>'center'),
                   'year2'=>array('width'=>165, 'justification'=>'center'),
		   'var'=>array('width'=>150, 'justification'=>'center'));
   $arrCol1 = array('prog'=>array('width'=>90, 'justification'=>'left'),
                   'rev'=>array('width'=>110, 'justification'=>'center'),
                   'tons'=>array('width'=>110, 'justification'=>'center'),
                   'hours'=>array('width'=>110, 'justification'=>'center'),
                   'var'=>array('width'=>90, 'justification'=>'center'));

   $arrCol = array('prog'=>array('width'=>100, 'justification'=>'left'),
                   'rev1'=>array('width'=>70, 'justification'=>'right'),
                   'tons1'=>array('width'=>70, 'justification'=>'right'),
                   'hours1'=>array('width'=>70, 'justification'=>'right'),
                   'rev2'=>array('width'=>70, 'justification'=>'right'),
                   'tons2'=>array('width'=>70, 'justification'=>'right'),
                   'hours2'=>array('width'=>70, 'justification'=>'right'));
   $arrCol = array('prog'=>array('width'=>90, 'justification'=>'left'),
                   'rev1'=>array('width'=>55, 'justification'=>'right'),
                   'tons1'=>array('width'=>55, 'justification'=>'right'),
                   'hours1'=>array('width'=>55, 'justification'=>'right'),
                   'rev2'=>array('width'=>55, 'justification'=>'right'),
                   'tons2'=>array('width'=>55, 'justification'=>'right'),
                   'hours2'=>array('width'=>55, 'justification'=>'right'),
		   'prof'=>array('width'=>50, 'justification'=>'right'),
                   'prod'=>array('width'=>50, 'justification'=>'right'),
                   'rate'=>array('width'=>50, 'justification'=>'right'));
   $arrCol = array('prog'=>array('width'=>90, 'justification'=>'left'),
                   'rev'=>array('width'=>55, 'justification'=>'right'),
                   'd_rev'=>array('width'=>55, 'justification'=>'right'),
                   'tons'=>array('width'=>55, 'justification'=>'right'),
                   'd_tons'=>array('width'=>55, 'justification'=>'right'),
                   'hours'=>array('width'=>55, 'justification'=>'right'),
                   'd_hours'=>array('width'=>55, 'justification'=>'right'),
                   'prof'=>array('width'=>30, 'justification'=>'center'),
                   'prod'=>array('width'=>30, 'justification'=>'center'),
                   'rate'=>array('width'=>30, 'justification'=>'center'));
*/
   $arrCol2 = array('prog'=>array('width'=>300, 'justification'=>'left'),
                   'rev1'=>array('width'=>80, 'justification'=>'center'),
                   'rev2'=>array('width'=>80, 'justification'=>'center'));
/*
   $arrHeading3 = array('itg'=>'<b>Program</b>', 'desc'=>'<b>Commodity</b>');
   $arrCol3 = array('itg'=>array('width'=>80, 'justification'=>'left'),
                   'desc'=>array('width'=>470, 'justification'=>'left'));
*/
//   $heading1 = array();
//   $heading = array();
   $heading2 = array();
//   $heading3 = array();

//   array_push($heading1, $arrHeading1);
//   array_push($heading, $arrHeading);
   array_push($heading2, $arrHeading2);
//   array_push($heading3, $arrHeading3);

   $data = array();
   $data2 = array();
   $data3 = array();

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');

   $pdf->ezSetMargins(40,40,65,65);
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


   $sql = "update revenue_tons_hours set customer_name = null";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = customer 
           where date_time >=to_date('07/01/2003','mm/dd/yyyy') and gl_code = 3095
           and commodity = 0";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "select distinct service, commodity from revenue_tons_hours 
	   where date_time >=to_date('07/01/2003','mm/dd/yyyy') and gl_code = 3095 and commodity <> 0
	   order by service, commodity";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
   $code= array();
   $i = 0;
   while(ora_fetch($cursor_bni)){
	$code[$i]['service'] = ora_getcolumn($cursor_bni, 0);
	$code[$i]['commodity'] = ora_getcolumn($cursor_bni, 1);
	$i++;
   }
   for ($i = 0; $i < count($code); $i++){
	$sql = "select distinct customer from revenue_tons_hours 
		where date_time >=to_date('07/01/2003','mm/dd/yyyy') and gl_code = 3095 and 
		customer is not null  and  
		service = ".$code[$i]['service']." and commodity = ".$code[$i]['commodity']."
           	order by customer";

   	$statement = ora_parse($cursor_bni, $sql);
   	ora_exec($cursor_bni);
	$cust = "";
	while (ora_fetch($cursor_bni)){
		if ($cust <>""){
			$cust .=" / ".ora_getcolumn($cursor_bni, 0);
		}else{
			$cust = ora_getcolumn($cursor_bni, 0);
		}
	}
	$sql = "update revenue_tons_hours set customer_name = '$cust' 
		where date_time >=to_date('07/01/2003','mm/dd/yyyy') and gl_code = 3095 and
                service = ".$code[$i]['service']." and commodity = ".$code[$i]['commodity'];
	$statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);
   }

   $sql = "update revenue_tons_hours set customer_name = 'LAFARGE' 
           where date_time >=to_date('07/01/2003','mm/dd/yyyy') and gl_code = 3095 and commodity = 7230";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'NORFOLK SOUTHERN'
           where date_time >=to_date('07/01/2003','mm/dd/yyyy') and date_time < to_date('07/01/2004','mm/dd/yyyy') and
	   gl_code = 3095 and service = 4111 and commodity = 0 and customer is null";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'US Coast Guard'
           where date_time >=to_date('10/01/2003','mm/dd/yyyy') and date_time < to_date('11/01/2004','mm/dd/yyyy') and
           gl_code = 3095 and service = 4111 and commodity = 0 and customer is null";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'CHIQUITA FRUPACK'
	   where date_time >=to_date('11/01/2003','mm/dd/yyyy') and date_time < to_date('12/01/2004','mm/dd/yyyy') and
           gl_code = 3095 and service = 4211 and commodity = 0 and customer_name is null";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer = customer_name, customer_name = 'OFFICE' 
	   where commodity = 0 and customer_name not in ('NORFOLK SOUTHERN','ROYAL FUMIGATION','HAITI SHIPPING INC.')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'DOLE' 
	   where customer_name like 'DOLE%' or customer_name = 'THE SICO OIL CO'";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'CHIQUITA'
           where customer_name like 'CHIQUITA%'";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'CITROSUCO'
           where customer_name like 'CITROSUCO%'";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $sql = "update revenue_tons_hours set customer_name = 'VOLKSWAGEN'
           where customer_name = 'VORELCO INC'";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
   
   $sql = "select distinct customer from revenue_tons_hours
	   where customer_name = 'OFFICE' and gl_code = 3095 and date_time >=to_date('07/01/2004','mm/dd/yyyy')
	   order by customer";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
   $strCust = "";
   while (ora_fetch($cursor_bni)){
       	if ($strCust <>""){
      		$strCust .=" / ".ora_getcolumn($cursor_bni, 0);
       	}else{
        	$strCust = ora_getcolumn($cursor_bni, 0);
       	}
   }
 

   $sql="select a.customer_name, b.rev, NVL(b.rev,0)-NVL(c.rev,0)
        from
        (select distinct customer_name 
	from revenue_tons_hours
        where date_time >=to_date('07/01/2003','mm/dd/yyyy') and gl_code = 3095) a,
        (select customer_name, sum(revenue) as rev
        from revenue_tons_hours
        where date_time >=to_date('07/01/2004','mm/dd/yyyy') and date_time <= to_date('$lDate1','mm/dd/yyyy')
        and gl_code =3095 group by customer_name) b,
        (select customer_name, sum(revenue) as rev
        from revenue_tons_hours
        where date_time >=to_date('07/01/2003','mm/dd/yyyy') and date_time <= to_date('$lDate2','mm/dd/yyyy')
        and gl_code =3095 group by customer_name) c
        where a.customer_name = b.customer_name(+) and a.customer_name = c.customer_name(+)
        order by NVL(b.rev,0) desc";
    //    order by NVL(b.rev,0)-NVL(c.rev, 0)";

   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);

   $other_rev1 = 0;
   $other_rev2 = 0;
   $tot_rev1 = 0;
   $tot_rev2 = 0;

   while (ora_fetch($cursor_bni)){
        $cust = ora_getcolumn($cursor_bni, 0);
        $rev1 = ora_getcolumn($cursor_bni, 1);
        $rev2 = ora_getcolumn($cursor_bni, 2);
 
/*
        if (abs($rev1) < 100)
                $rev1 = 0;
        if (abs($rev2) < 100)
                $rev2 = 0;

	if ($rev1 <10000 || $cust == "N/A"){
		$other_rev1 +=$rev1;
		$other_rev2 +=$rev2;
	}
*/
        if ($cust== "Other"){
                $other_rev1 +=$rev1;
                $other_rev2 +=$rev2;
        }

	
        $tot_rev1 += $rev1;
        $tot_rev2 += $rev2;

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
/*
	if($cust =="Autos")
		$cust = "Vehicles";
*/
        if ($cust <> "Other"  && ($rev1 <>0 || $rev2 <>0)){
                array_push($data2, array('prog'=>ucwords(strtolower($cust)),
                                        'rev1'=>$dis_rev1,
                                        'rev2'=>$dis_rev2));
        }
  }
   if ($other_rev1 <0){
        $dis_other_rev1 = "(".number_format(-$other_rev1,0,'.',',').")";
   }else{
        $dis_other_rev1 = number_format($other_rev1,0,'.',',');
   }

   if ($other_rev2 <0){
	$dis_other_rev2 = "(".number_format(-$other_rev2,0,'.',',').")";
   }else{
	$dis_other_rev2 = number_format($other_rev2,0,'.',',');
   }

   if ($other_rev1<>0 || $other_rev2 <>0){
   array_push($data2, array('prog'=>'Other',
                           'rev1'=>$dis_other_rev1,
                           'rev2'=>$dis_other_rev2));
   }
   array_push($data2, array('prog'=>'<b>Total</b>',
                           'rev1'=>'<b>'.number_format($tot_rev1,0,'.',',').'</b>',
                           'rev2'=>'<b>'.number_format($tot_rev2,0,'.',',').'</b>'));




   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>LEASE</b>", 24, $center);
   $pdf->ezSetDy(-5);

//   $pdf->ezText("<b><i>(Program grouping being identified by TS/Accounting)</i></b>", 14, $center);


   $pdf->ezText("<b><i>As of $lDate1 </i></b>", 14, $center);
   $pdf->ezSetDy(-5);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
//   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezTable($heading2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $pdf->ezTable($data2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));
   $pdf->ezSetDy(-15);
   $pdf->ezNewPage();
   $pdf->ezText("<i>Office: $strCust</i>", 10, $left);

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "rwang@port.state.de.us";
//        $mailTo1 .= ",ithomas@port.state.de.us";

        $mailTo = "gbailey@port.state.de.us,";
        $mailTo .="tkeefer@port.state.de.us,";
//        $mailTo .="ffitzgerald@port.state.de.us,";
        $mailTo .="ithomas@port.state.de.us,";
        $mailTo .="rhorne@port.state.de.us,";
        $mailTo .="skennard@port.state.de.us,";
        $mailTo .="parul@port.state.de.us";

        $mailsubject = "LEASE REVENUE";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";
        $mailheaders .= "Cc: vfarkas@port.state.de.us,jjaffe@port.state.de.us,rwang@port.state.de.us\r\n";
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
