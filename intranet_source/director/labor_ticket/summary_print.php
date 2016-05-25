<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $user_id = $HTTP_POST_VARS[uId];
   $type = $HTTP_POST_VARS[type];
   $sDate = $HTTP_POST_VARS[sDate];
   $eDate = $HTTP_POST_VARS[eDate];

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }

   $cursor = ora_open($conn);
   
   $tot_bHours = 0;
   $tot_nbHours = 0;

   if ($user_id <>""){
	$uId = " and h.user_id = '$user_id' ";
   }else{
	$uId = "";
   }
 
   $sql = "select to_char(hire_date,'mm/dd/yyyy'),u.user_name,service_type,customer_name,commodity_name,sum(bPaid),sum(nbPaid)
	   from lcs_b_vs_nb_hours h,  commodity c, customer m,  lcs_user u, supervisor_type s
	   where  h.commodity = c.commodity_code and  h.user_id = u.user_id(+) and 
	   h.user_id = s.user_id and s.type = '$type'
           and h.customer = m.customer_id(+)  $uId and
	   h.hire_date >= to_date('$sDate','mm/dd/yyyy') and h.hire_date <= to_date('$eDate','mm/dd/yyyy')
           group by to_char(hire_date,'mm/dd/yyyy'), u.user_name, service_type, customer_name, commodity_name, hire_date 
           order by hire_date, u.user_name, service_type, customer_name, commodity_name";       	   

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $arrHeading1 = array('hDate'=>'<b>Date</b>','sup'=>'<b>Supervisor</b>', 'service'=>'<b>Service</b>','cust'=>'<b>Customer</b>','comm'=>'<b>Commodity</b>', 'bHours'=>'<b>Business Hours</b>', 'nbHours'=>'<b>Non Business Hours</b>');
   $arrCol1 =array('hDate'=>array('width'=>65,'justification'=>'left'),
		   'sup'=>array('width'=>100, 'justification'=>'left'),
                   'service'=>array('width'=>100, 'justification'=>'left'),
                   'cust'=>array('width'=>170, 'justification'=>'left'),
                   'comm'=>array('width'=>170, 'justification'=>'left'),
                   'bHours'=>array('width'=>60, 'justification'=>'center'),
                   'nbHours'=>array('width'=>80, 'justification'=>'center'));

   $data = array();
   $heading1 = array();
   array_push($heading1, $arrHeading1);



   while (ora_fetch($cursor)){
    	$hDate = ora_getcolumn($cursor, 0);
     	$supervisor = ora_getcolumn($cursor, 1);
      	$service = ora_getcolumn($cursor, 2);
     	$customer = ora_getcolumn($cursor, 3);
      	$commodity = ora_getcolumn($cursor, 4);
      	$bHours= ora_getcolumn($cursor, 5);
     	$nbHours = ora_getcolumn($cursor, 6);
      	$tot_bHours += $bHours;
       	$tot_nbHours += $nbHours;

   	array_push($data, array('hDate'=>$hDate,
			   	'sup'=>ucwords(strtolower($supervisor)),
                           	'service'=>ucwords(strtolower($service)),
		  	   	'cust'=>ucwords(strtolower($customer)),
			   	'comm'=>ucwords(strtolower($commodity)),
			   	'bHours'=>$bHours,
			   	'nbHours'=>$nbHours));
   }
   array_push($data, array('hDate'=>'<b>Total</b>',
                           'sup'=>'',
                           'service'=>'',
                           'cust'=>'',
                           'comm'=>'',
                           'bHours'=>'<b>'.$tot_bHours.'</b>',
                           'nbHours'=>'<b>'.$tot_nbHours.'</b>'));

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

   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Total Paid Hours</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i> $sDate to $eDate </i></b>", 18, $center);
//   $pdf->ezSetDy(-15);
   $pdf->ezSetDy(-5);
   $pdf->ezText("<b>$type</b>", 12, $center);
   $pdf->ezSetDy(-15);	
   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezTable($data, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezStream();

?>
