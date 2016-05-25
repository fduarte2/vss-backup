<?
   function htmlText($text){
        if ($text =="")
                $text = "&nbsp;";
        return $text;
   }

   $uId = $HTTP_GET_VARS[uId];
   $sDate = $HTTP_GET_VARS[sDate];
   $eDate = $HTTP_GET_VARS[eDate];
   $type = $HTTP_GET_VARS[type];
   $isExport =  $HTTP_GET_VARS[export];

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
   }

   $cursor = ora_open($conn);
   $cursor1 = ora_open($conn);

   $arrHeading1 = array('num'=>'<b>No.</b>','date'=>'<b>Date</b>','cust'=>'<b>Customer</b>','comm'=>'<b>Commodity</b>', 'service'=>'<b>Service</b>','st'=>'<b>ST</b>','ot'=>'<b>OT</b>', 'dt'=>'<b>DT</b>','mh'=>'<b>MH</b>','df'=>'<b>DF</b>','tot'=>'<b>Total</b>','status'=>'<b>Status</b>','desc'=>'<b>Description</b>' );
   $arrCol1 =array('mun'=>array('width'=>35,'justification'=>'left'),
		   'date'=>array('width'=>50, 'justification'=>'left'),
                   'cust'=>array('width'=>120, 'justification'=>'left'),
                   'comm'=>array('width'=>130, 'justification'=>'left'),
		   'service'=>array('width'=>50, 'justification'=>'left'),
                   'st'=>array('width'=>41, 'justification'=>'center'),
                   'ot'=>array('width'=>41, 'justification'=>'center'),
                   'dt'=>array('width'=>41, 'justification'=>'center'),
                   'mh'=>array('width'=>41, 'justification'=>'center'),
                   'df'=>array('width'=>41, 'justification'=>'center'),
                   'tot'=>array('width'=>41, 'justification'=>'center'),
                   'status'=>array('width'=>41, 'justification'=>'center'),
                   'desc'=>array('width'=>100, 'justification'=>'left'));

   $data = array();
   $heading1 = array();
   array_push($heading1, $arrHeading1);

   $tHours = 0;

   $sql = "select distinct h.ticket_num, to_char(service_date, 'mm/dd/yy'), bill_status, bill_desc, customer_name, 
	   commodity_name, service_group 
	   from labor_ticket_header h, labor_ticket t, commodity c, customer m
	   where t.ticket_num = h.ticket_num and h.commodity_code = c.commodity_code and h.customer_id = m.customer_id and
	   h.service_date >= to_date('$sDate','mm/dd/yyyy') and h.service_date <= to_date('$eDate','mm/dd/yyyy')";

   if ($uId <>""){
        $sql = $sql." and h.user_id = '$uId'";
   }

   $sql = $sql." order by h.ticket_num";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $tot = 0;
   while (ora_fetch($cursor)){
	$ticket_num = ora_getcolumn($cursor, 0);
        $date = ora_getcolumn($cursor, 1);
	$bill_status = ora_getcolumn($cursor, 2);
        $desc = ora_getcolumn($cursor,3);
	$customer = ora_getcolumn($cursor, 4);
  	$commodity = ora_getcolumn($cursor, 5);
	$service = ora_getcolumn($cursor, 6)."X";


	$sql = "select rate_type, sum(qty * hours) from labor_ticket where ticket_num = '$ticket_num' group by rate_type";
	$statement = ora_parse($cursor1, $sql);
   	ora_exec($cursor1);

       	$lST = 0;
        $lOT = 0;
        $lDT = 0;
        $lMH = 0;
        $lDF = 0;
	$total = 0;

	while (ora_fetch($cursor1)){

		$rType = ora_getcolumn($cursor1, 0);
		$lHours = ora_getcolumn($cursor1, 1);
		$total += $lHours;
		$tot += $lHours;

		switch ($rType){
                case "ST":
                      	$lST = $lHours;
			$tST += $lST;
                        break;
              	case "OT":
                     	$lOT = $lHours;
			$tOT += $lOT;
     	                break;
          	case "DT":
       	               	$lDT = $lHours;
			$tDT += $lDT;
                  	break;
          	case "MH":
         	        $lMH = $lHours;
			$tMH += $lMH;
                        break;
           	case "DF":
     	                $lDF = $lHours;
			$tDF += $lDF;
                }
	}	
        array_push($data, array('num'=>$ticket_num,
                                'date'=>$date,
                               	'cust'=>$customer,
                               	'comm'=>$commodity,
				'service'=>$service,
                               	'st'=>$lST,
				'ot'=>$lOT,
				'dt'=>$lDT,
				'mh'=>$lMH,
				'df'=>$lDF,
				'tot'=>$total,
                               	'status'=>$bill_status,
				'desc'=>$desc));
	}
   array_push($data, array('num'=>'<b>Total</b>',
                      	   'st'=>'<b>'.$tST.'</b>',
           	           'ot'=>'<b>'.$tOT.'</b>',
     	                   'dt'=>'<b>'.$tDT.'</b>',
                           'mh'=>'<b>'.$tMH.'</b>',
                           'df'=>'<b>'.$tDF.'</b>',
                     	   'tot'=>'<b>'.$tot.'</b>'));

if ($isExport <>'Y'){   
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

   $pdf->ezText('<c:alink:labor_ticket.php?sDate='.$sDate.'&eDate='.$eDate.'&uId='.$uId.'&type='.$type.'&export=Y>Export To Excel</c:alink>');
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Labor Ticket</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i> $sDate to $eDate </i></b>", 18, $center);

   $pdf->ezSetDy(-15);
   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>772,'cols'=>$arrCol1));
   $pdf->ezTable($data, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2,'width'=>772, 'cols'=>$arrCol1));
   $pdf->ezStream();
}else{
   $expStr .= "<tr><td align=center colspan=13><font size =5><b>Labor Ticket</b></font></td></tr>";
   $expStr .= "<tr><td align=center colspan=13><font size =4><i>$sDate to $eDate</i></font></td></tr>";
   
   $expStr .= "<tr>";
   $expStr .= "<td><b>No.</b></td>";
   $expStr .= "<td><b>Date</b></td>";
   $expStr .= "<td><b>Customer</b></td>";
   $expStr .= "<td><b>Commodity</b></td>";
   $expStr .= "<td><b>Service</b></td>";
   $expStr .= "<td><b>ST</b></td>";
   $expStr .= "<td><b>OT</b></td>";
   $expStr .= "<td><b>DT</b></td>";
   $expStr .= "<td><b>MH</b></td>";
   $expStr .= "<td><b>DF</b></td>";
   $expStr .= "<td><b>Total</b></td>";
   $expStr .= "<td><b>Status</b></td>";
   $expStr .= "<td><b>Description</b></td>";
   $expStr .= "</tr>";

   $size = count($data);

   for ($i = 0; $i < $size; $i++){
    	$expStr .= "<tr>";
       	$expStr .= "<td>".$data[$i]["num"]."</td>";
        $expStr .= "<td>".$data[$i]["date"]."</td>";
        $expStr .= "<td>".$data[$i]["cust"]."</td>";
        $expStr .= "<td>".$data[$i]["comm"]."</td>";
        $expStr .= "<td>".$data[$i]["service"]."</td>";
        $expStr .= "<td>".$data[$i]["st"]."</td>";
        $expStr .= "<td>".$data[$i]["ot"]."</td>";
        $expStr .= "<td>".$data[$i]["dt"]."</td>";
        $expStr .= "<td>".$data[$i]["mh"]."</td>";
        $expStr .= "<td>".$data[$i]["df"]."</td>";
        $expStr .= "<td>".$data[$i]["tot"]."</td>";
        $expStr .= "<td>".$data[$i]["status"]."</td>";
        $expStr .= "<td>".$data[$i]["desc"]."</td>";
   }
        $table = "<TABLE border=1 CELLSPACING=1>";
        $table .= $expStr;

        $table .= "</table>";
        //export to excel
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: attachment; filename=Export.xls");

        echo $table;
}
?>
