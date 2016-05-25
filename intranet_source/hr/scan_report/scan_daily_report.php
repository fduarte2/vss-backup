<?
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
   }
   $cursor = ora_open($conn);
   $db_log = "security_log";
   $db_location = "security_location";

   if (isset($HTTP_POST_VARS[export])){
     	$export = true;
   }else{
    	$export = false;
   }

   if ($HTTP_SERVER_VARS["argv"][1] == "email" || $HTTP_SERVER_VARS["argv"][1] == "email_msg" ){
	$sendMail = true;
	if ($HTTP_SERVER_VARS["argv"][1] == "email"){
		$senMsg = false;
	}else{
		$sendMsg = true;
	}
   } else {
	$sendMail = false;
   }


   if($sendMail == false){
   
        include("pow_session.php");
   	$user = $userdata['username'];
   	
   	$sDate = $HTTP_POST_VARS[start_date];
   	$eDate = $HTTP_POST_VARS[end_date];
   }else {

	$dWeek = date('D');
   
	if ($dWeek =="Sat" || $dWeek == "Sun"){
		exit;  //No email for Sat and Sun
	}else if ($dWeek == "Mon"){//start from Fri
		$start_Date = mktime(0,0,0,date("m"),date("d")-3,date("Y"));
        }else{//start from yesterday	
		$start_Date = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
	}
	$sDate = date('m/d/Y',$start_Date); 
 

	$eDate = date('m/d/Y');

        //is holiday
        $sql = "select * from holiday where holiday =to_date('$eDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
                exit;
        }
        $sql = "select * from holiday where holiday >=to_date('$sDate','mm/dd/yyyy') and holiday <to_date('$eDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
		$sDate = date('m/d/Y', mktime(0,0,0,date("m", $start_Date),date("d", $start_Date)-1,date("Y", $start_Date)));
	}

   }


   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
  
   $pdf->ezSetMargins(20,20,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $pdf->ezStartPageNumbers(300, 10, 8, '','',1);
   // Write out the intro.
   // Print Receiving Header

   $today = date('m/j/y H:i');
   $format = "Printed On: " . $today;
 //  $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
 // $pdf->line(45,30,555,30);
   $pdf->addText(480,10,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   $arrEveningHeading = array('loc'=>"<b>Location</b>",
			      'd18'=>"<b>6 PM - 8 PM</b>",
			      'd20'=>"<b>8 PM - 10 PM</b>",
                              'd22'=>"<b>10 PM - 12 AM</b>",
                              'd00'=>"<b>12 AM - 2 AM</b>",
                              'd02'=>"<b>2 AM - 4 AM</b>",
                              'd04'=>"<b>4 AM - 6 AM</b>");

   $arrEveningCol = array('loc'=>array('width'=>75, 'justification'=>'left'),
                      	  'd18'=>array('width'=>80, 'justification'=>'left'),
                          'd20'=>array('width'=>80, 'justification'=>'left'),
                          'd22'=>array('width'=>80, 'justification'=>'left'),
                          'd00'=>array('width'=>80, 'justification'=>'left'),
                          'd02'=>array('width'=>80, 'justification'=>'left'),
                          'd04'=>array('width'=>80, 'justification'=>'left'));

   $arrDayHeading = array('loc'=>"<b>Location</b>",
                              'd06'=>"<b>6 AM - 8 AM</b>",
                              'd08'=>"<b>8 AM - 10 AM</b>",
                              'd10'=>"<b>10 AM - 12 PM</b>",
                              'd12'=>"<b>12 PM - 2 PM</b>",
                              'd14'=>"<b>2 PM - 4 PM</b>",
                              'd16'=>"<b>4 PM - 6 PM</b>");

   $arrDayCol = array('loc'=>array('width'=>75, 'justification'=>'left'),
                          'd06'=>array('width'=>80, 'justification'=>'left'),
                          'd08'=>array('width'=>80, 'justification'=>'left'),
                          'd10'=>array('width'=>80, 'justification'=>'left'),
                          'd12'=>array('width'=>80, 'justification'=>'left'),
                          'd14'=>array('width'=>80, 'justification'=>'left'),
                          'd16'=>array('width'=>80, 'justification'=>'left'));

   $strBlank = "<b>SKIPPED</b>";	

   $tot_count_scan = 0;
   $tot_count_skipped = 0;


   $sql = "select to_date('".$eDate."','mm/dd/yyyy')-to_date('".$sDate."','mm/dd/yyyy') from dual";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (ora_fetch($cursor)){
   	$days = ora_getcolumn($cursor, 0);
   }
   
   if ($days <=0 ){
	exit;
   }

 
   for ($i = 0; $i < $days; $i++){
       $sql = "select to_char(to_date('$sDate','mm/dd/yyyy') + $i, 'mm/dd/yyyy'),
                       to_char(to_date('$sDate','mm/dd/yyyy') + $i + 1, 'mm/dd/yyyy'),
                       to_char(to_date('$sDate','mm/dd/yyyy') + $i, 'D')
                from dual";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
                $SDate = ora_getcolumn($cursor, 0);
                $EDate = ora_getcolumn($cursor, 1);
                $DayOfWeek = ora_getcolumn($cursor, 2);
        }

   	$sql = "select * from holiday where holiday =to_date('$SDate','mm/dd/yyyy')";
   	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor); 
   	if (ora_fetch($cursor)){
        	$isHoliday = true; 
   	}else{
		$isHoliday = false;
	}
//     if((($sendMail || $i > 0) && ($DayOfWeek == 7 || $DayOfWeek == 1))){
     if(($i > 0) && ($DayOfWeek == 7 || $DayOfWeek == 1 || $isHoliday)){

        $count_scan = 0;
        $count_skipped = 0;
        $sql = "select l.location_code ||'  '|| l.short_desc, D06.detail, D08.detail, D10.detail, D12.detail, D14.detail, D16.detail
                from $db_location l,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 6/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy') + 8/24) D06,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 8/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 10/24) D08,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 10/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 12/24) D10,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 12/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 14/24) D12,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 14/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 16/24) D14,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 16/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 18/24) D16
                where   l.status = 'A' and
                        l.location_code = D06.location_code(+) and
                        l.location_code = D08.location_code(+) and
                        l.location_code = D10.location_code(+) and
                        l.location_code = D12.location_code(+) and
                        l.location_code = D14.location_code(+) and
                        l.location_code = D16.location_code(+)
                order by l.location_code, D06.detail, D08.detail, D10.detail, D12.detail, D14.detail, D16.detail";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

        $pre_loc = "";
        $pre_d06 = "";
        $pre_d08 = "";
        $pre_d10 = "";
        $pre_d12 = "";
        $pre_d14 = "";
        $pre_d16 = "";

        $data = array();
        while (ora_fetch($cursor)){
                $loc = trim(ora_getcolumn($cursor, 0));
                $d06 = trim(ora_getcolumn($cursor, 1));
                $d08 = trim(ora_getcolumn($cursor, 2));
                $d10 = trim(ora_getcolumn($cursor, 3));
                $d12 = trim(ora_getcolumn($cursor, 4));
                $d14 = trim(ora_getcolumn($cursor, 5));
                $d16 = trim(ora_getcolumn($cursor, 6));

                if ($loc <> $pre_loc){
	                $pre_loc = $loc;
        	        $pre_d06 = $d06;
                	$pre_d08 = $d08;
                	$pre_d10 = $d10;
                	$pre_d12 = $d12;
                	$pre_d14 = $d14;
                	$pre_d16 = $d16;

                        if ($d06 ==""){
                                $d06 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d08 ==""){
                                $d08 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d10 ==""){
                                $d10 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d12 ==""){
                                $d12 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d14 ==""){
                                $d14 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d16 ==""){
                                $d16 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                }else{
                        $loc = "";
                        if ($d06 == $pre_d06){
                                $d06 = "";
                        }else{
				$pre_d06 = $d06;
                                $count_scan += 1;
                        }
                        if ($d08 == $pre_d08){
                                $d08 = "";
                        }else{
				$pre_d08 = $d08;
                                $count_scan += 1;
                        }
                        if ($d10 == $pre_d10){
                                $d10 = "";
                        }else{
				$pre_d10 = $d10;
                                $count_scan += 1;
                        }
                        if ($d12 == $pre_d12){
                                $d12 = "";
                        }else{
				$pre_d12 = $d12;
                                $count_scan += 1;
                        }
                        if ($d14 == $pre_d14){
                                $d14 = "";
                        }else{
				$pre_d14 = $d14;
                                $count_scan += 1;
                        }
                        if ($d16 == $pre_d16){
                                $d16 = "";
                        }else{
				$pre_d16 = $d16;
                                $count_scan += 1;
                        }
                }
                
                if ($d06 <>"" || $d08 <>"" || $d10 <>"" || $d12 <>"" || $d14 <>"" || $d16 <>"" ) {
                array_push($data, array('loc'=>$loc,'d06'=>$d06,'d08'=>$d08,'d10'=>$d10,'d12'=>$d12,'d14'=>$d14,'d16'=>$d16));
                }
        }

        $tot_count_scan += $count_scan;
        $tot_count_skipped += $count_skipped;


        $pdf->ezNewPage();

        $pdf->ezText("<b>SECURITY LOG FOR $SDate DAY</b>", 20, $center);
        $pdf->ezSetDy(-15);

        $pdf->ezTable($data, $arrDayHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>8,'cols'=>$arrDayCol));
        $pdf->ezSetDy(-5);
        $pdf->ezText("<b>   Scanned: $count_scan; Skipped: $count_skipped</b>", 12, $left);
//        $pdf->ezSetDy(-5);
//        $pdf->ezText("<b>   Skipped: $count_skipped</b>", 12, $left);	

	if($export){
		$expStr .= "<tr><td align=center colspan=7><font size =5><b>SECURITY LOG FOR $SDate DAY</b></font></td></tr>";
		$expStr .= "<tr>";
		$expStr .= "<td><b>Location</b></td>";
                $expStr .= "<td><b>6AM-8AM</b></td>";
                $expStr .= "<td><b>8AM-10AM</b></td>";
                $expStr .= "<td><b>10AM-12PM</b></td>";
                $expStr .= "<td><b>12PM-2PM</b></td>";
                $expStr .= "<td><b>2PM-4PM</b></td>";
                $expStr .= "<td><b>4PM-6PM</b></td>";
		$expStr .= "</tr>";

		$size = count($data);

		for ($a = 0; $a < $size; $a++){
			$expStr .= "<tr>";
			$expStr .= "<td>".$data[$a]["loc"]."</td>";
			$expStr .= "<td>".$data[$a]["d06"]."</td>";
                        $expStr .= "<td>".$data[$a]["d08"]."</td>";
                        $expStr .= "<td>".$data[$a]["d10"]."</td>";
                        $expStr .= "<td>".$data[$a]["d12"]."</td>";
                        $expStr .= "<td>".$data[$a]["d14"]."</td>";
                        $expStr .= "<td>".$data[$a]["d16"]."</td>";
                        $expStr .= "</tr>";

		}
		$expStr .= "<tr><td colspan=7><b>   Scanned: $count_scan</b></td></tr>";
		$expStr .= "<tr><td colspan=7><b>   Skipped: $count_skipped</b></td></tr>";
		$expStr .= "<tr><td colspan=7>&nbsp;</td></tr>";


	}
    } 



	$count_scan = 0;
	$count_skipped = 0;
	$sql = "select l.location_code ||'  '||l.short_desc, D18.detail, D20.detail, D22.detail, D00.detail, D02.detail, D04.detail
	 	from $db_location l,
		(select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
		from $db_log 
		where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 18/24 and    
	  	activity_date < to_date('$SDate', 'mm/dd/yyyy') + 20/24) D18,
		(select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 20/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 22/24) D20,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 22/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 24/24) D22,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 24/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 26/24) D00,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'  '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 26/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 28/24) D02,
                (select location_code, to_char(activity_date, 'hh24:mi') ||'  '|| login_id  as detail
                from $db_log
                where activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 28/24 and
                activity_date < to_date('$SDate', 'mm/dd/yyyy')+ 30/24) D04 
                where 	l.status = 'A' and
			l.location_code = D18.location_code(+) and
      			l.location_code = D20.location_code(+) and
	  		l.location_code = D22.location_code(+) and
                        l.location_code = D00.location_code(+) and
                        l.location_code = D02.location_code(+) and
                        l.location_code = D04.location_code(+)
		order by l.location_code, D18.detail, D20.detail, D22.detail, D00.detail, D02.detail, D04.detail";
	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);
	
	$pre_loc = "";
        $pre_d18 = "";
        $pre_d20 = "";
        $pre_d22 = "";
        $pre_d00 = "";
        $pre_d02 = "";
        $pre_d04 = "";

	$data = array();
   	while (ora_fetch($cursor)){
        	$loc = trim(ora_getcolumn($cursor, 0));
                $d18 = trim(ora_getcolumn($cursor, 1));
                $d20 = trim(ora_getcolumn($cursor, 2));
                $d22 = trim(ora_getcolumn($cursor, 3));
                $d00 = trim(ora_getcolumn($cursor, 4));
                $d02 = trim(ora_getcolumn($cursor, 5));
                $d04 = trim(ora_getcolumn($cursor, 6));

		if ($loc <> $pre_loc){
			$pre_loc = $loc;
                	$pre_d18 = $d18;
                	$pre_d20 = $d20;
                	$pre_d22 = $d22;
                	$pre_d00 = $d00;
                	$pre_d02 = $d02;
                	$pre_d04 = $d04;

			if ($d18 ==""){
				$d18 =$strBlank;
				$count_skipped += 1;
			}else{
				$count_scan += 1;
			}
                        if ($d20 ==""){
                                $d20 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d22 ==""){
                                $d22 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d00 ==""){
                                $d00 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d02 ==""){
                                $d02 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
                        if ($d04 ==""){
                                $d04 =$strBlank;
                                $count_skipped += 1;
                        }else{
                                $count_scan += 1;
                        }
		}else{
			$loc = "";
			if ($d18 == $pre_d18){
				$d18 = "";
			}else{
				$pre_d18 = $d18;
			 	$count_scan += 1;
			}
                        if ($d20 == $pre_d20){
                                $d20 = "";
                        }else{
				$pre_d20 = $d20;
				$count_scan += 1;
                        }
                        if ($d22 == $pre_d22){
                                $d22 = "";
                        }else{
				$pre_d22 = $d22;
                                $count_scan += 1;
                        }
                        if ($d00 == $pre_d00){
                                $d00 = "";
                        }else{
				$pre_d00 = $d00;
                                $count_scan += 1;
                        }
                        if ($d02 == $pre_d02){
                                $d02 = "";
                        }else{
				$pre_d02 = $d02;
                                $count_scan += 1;
                        }
                        if ($d04 == $pre_d04){
                                $d04 = "";
                        }else{
				$pre_d04 = $d04;
                                $count_scan += 1;
                        }
                }	

		
		if ($d18 <>"" || $d20 <>"" || $d22 <>"" || $d00 <>"" || $d02 <>"" || $d04 <>"" ) { 
		array_push($data, array('loc'=>$loc,'d18'=>$d18,'d20'=>$d20,'d22'=>$d22,'d00'=>$d00,'d02'=>$d02,'d04'=>$d04));
		}
   	}
      	$tot_count_scan += $count_scan;
    	$tot_count_skipped += $count_skipped;

	
	if ($i > 0) {
		$pdf->ezNewPage();
	}

      	$pdf->ezText("<b>SECURITY LOG FOR $SDate NIGHT</b>", 20, $center);
    	$pdf->ezSetDy(-15);

       	$pdf->ezTable($data, $arrEveningHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>8,'cols'=>$arrEveningCol));
	$pdf->ezSetDy(-5);
	$pdf->ezText("<b>   Scanned: $count_scan; Skipped: $count_skipped</b>", 12, $left);
//	$pdf->ezSetDy(-5);
//        $pdf->ezText("<b>   Skipped: $count_skipped</b>", 12, $left);
 

        if($export){
                $expStr .="<tr><td align=center colspan=7><font size=5><b>SECURITY LOG FOR $SDate NIGHT</b></font></td></tr>";
                $expStr .= "<tr>";
                $expStr .= "<td><b>Location</b></td>";
                $expStr .= "<td><b>6PM-8PM</b></td>";
                $expStr .= "<td><b>8PM-10PM</b></td>";
                $expStr .= "<td><b>10PM-12AM</b></td>";
                $expStr .= "<td><b>12AM-2AM</b></td>";
                $expStr .= "<td><b>2AM-4AM</b></td>";
                $expStr .= "<td><b>4AM-6AM</b></td>";
                $expStr .= "</tr>";

                $size = count($data);

                for ($a = 0; $a < $size; $a++){
                        $expStr .= "<tr>";
                        $expStr .= "<td>".$data[$a]["loc"]."</td>";
                        $expStr .= "<td>".$data[$a]["d18"]."</td>";
                        $expStr .= "<td>".$data[$a]["d20"]."</td>";
                        $expStr .= "<td>".$data[$a]["d22"]."</td>";
                        $expStr .= "<td>".$data[$a]["d00"]."</td>";
                        $expStr .= "<td>".$data[$a]["d02"]."</td>";
                        $expStr .= "<td>".$data[$a]["d04"]."</td>";
                        $expStr .= "</tr>";

                }
                $expStr .= "<tr><td colspan=7><b>   Scanned: $count_scan</b></td></tr>";
                $expStr .= "<tr><td colspan=7><b>   Skipped: $count_skipped</b></td></tr>";
                $expStr .= "<tr><td colspan=7>&nbsp;</td></tr>";

        }

 }
/*
// This section makes a special page for the railroad tracks, but is no longer necessary.
   $arrHeading = array('title'=>"",
                       'time'=>"<b>Time</b>",
                       'id'=>"<b>ID</b>");

   $arrCol = array('title'=>array('width'=>100, 'justification'=>'left'),
                          'time'=>array('width'=>80, 'justification'=>'left'),
                          'id'=>array('width'=>80, 'justification'=>'left'));

   for ($i = 0; $i < $days; $i++){
       $data = array();
       $count_scan = 0;
       $count_skipped = 0;

       $sql = "select to_char(to_date('$sDate','mm/dd/yyyy') + $i, 'mm/dd/yyyy'),
                       to_char(to_date('$sDate','mm/dd/yyyy') + $i + 1, 'mm/dd/yyyy'),
                       to_char(to_date('$sDate','mm/dd/yyyy') + $i, 'D')
                from dual";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
                $SDate = ora_getcolumn($cursor, 0);
                $EDate = ora_getcolumn($cursor, 1);
                $DayOfWeek = ora_getcolumn($cursor, 2);
        }

        $sql = "select * from holiday where holiday =to_date('$SDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        if (ora_fetch($cursor)){
                $isHoliday = true;
        }else{
                $isHoliday = false;
        }
//     if((($sendMail || $i > 0) && ($DayOfWeek == 7 || $DayOfWeek == 1))){
	$title = array();
	if ($DayOfWeek == 7 || $DayOfWeek == 1 || $isHoliday){
                $title[0] = "06:00AM-08:00AM";
                $title[1] = "08:00AM-10:00AM";
                $title[2] = "10:00AM-12:00PM";
                $title[3] = "12:00PM-02:00PM";
                $title[4] = "02:00PM-04:00PM";
                $title[5] = "04:00PM-06:00PM";
                $title[6] = "06:00PM-08:00PM";
                $title[7] = "08:00PM-10:00PM";
                $title[8] = "10:00PM-12:00AM";
                $title[9] = "12:00AM-02:00AM";
                $title[10] = "02:00AM-04:00AM";
                $title[11] = "04:00AM-06:00AM";

	}else{
        	$title[0] = "08:00AM-08:30AM";
        	$title[1] = "08:30AM-09:00AM";
        	$title[2] = "09:00AM-09:30AM";
        	$title[3] = "09:30AM-10:00AM";
        	$title[4] = "10:00AM-10:30AM";
        	$title[5] = "10:30AM-11:00AM";
        	$title[6] = "11:00AM-11:30AM";
        	$title[7] = "11:30AM-12:00PM";
        	$title[8] = "12:00PM-12:30PM";
        	$title[9] = "12:30PM-01:00PM";
        	$title[10] = "01:00PM-01:30PM";
        	$title[11] = "01:30PM-02:00PM";
        	$title[12] = "02:00PM-02:30PM";
        	$title[13] = "02:30PM-03:00PM";
        	$title[14] = "03:00PM-03:30PM";
        	$title[15] = "03:30PM-04:00PM";
        	$title[16] = "04:00PM-04:30PM";
        	$title[17] = "04:30PM-05:00PM";
        	$title[18] = "06:00PM-08:00PM";
        	$title[19] = "08:00PM-10:00PM";
        	$title[20] = "10:00PM-12:00AM";
        	$title[21] = "12:00AM-02:00AM";
        	$title[22] = "02:00AM-04:00AM";
        	$title[23] = "04:00AM-06:00AM";
        } 	
*/
/*
       if(($i > 0) && ($DayOfWeek == 7 || $DayOfWeek == 1 || $isHoliday)){

        $title[0] = "06:00AM-06:30AM";
        $title[1] = "06:30AM-07:00AM";
        $title[2] = "07:00AM-07:30AM";
        $title[3] = "07:30AM-08:00AM";
        $title[4] = "08:00AM-08:30AM";
        $title[5] = "08:30AM-09:00AM";
        $title[6] = "09:00AM-09:30AM";
        $title[7] = "09:30AM-10:00AM";
        $title[8] = "10:00AM-10:30AM";
        $title[9] = "10:30AM-11:00AM";
        $title[10] = "11:00AM-11:30AM";
        $title[11] = "11:30AM-12:00PM";
        $title[12] = "12:00PM-12:30PM";
        $title[13] = "12:30PM-01:00PM";
        $title[14] = "01:00PM-01:30PM";
        $title[15] = "01:30PM-02:00PM";
        $title[16] = "02:00PM-02:30PM";
        $title[17] = "02:30PM-03:00PM";
        $title[18] = "03:00PM-03:30PM";
        $title[19] = "03:30PM-04:00PM";
        $title[20] = "04:00PM-04:30PM";
        $title[21] = "04:30PM-05:00PM";
        $title[22] = "05:00PM-05:30PM";
        $title[23] = "05:30PM-06:00PM";
        $title[24] = "06:00PM-06:30PM";
        $title[25] = "06:30PM-07:00PM";
        $title[26] = "07:00PM-07:30PM";
        $title[27] = "07:30PM-08:00PM";
        $title[28] = "08:00PM-08:30PM";
        $title[29] = "08:30PM-09:00PM";
        $title[30] = "09:00PM-09:30PM";
        $title[31] = "09:30PM-10:00PM";
        $title[32] = "10:00PM-10:30PM";
        $title[33] = "10:30PM-11:00PM";
        $title[34] = "11:00PM-11:30PM";
        $title[35] = "11:30PM-12:00AM";
        $title[36] = "12:00AM-12:30AM";
        $title[37] = "12:30AM-01:00AM";
        $title[38] = "01:00AM-01:30AM";
        $title[39] = "01:30AM-02:00AM";
        $title[40] = "02:00AM-02:30AM";
        $title[41] = "02:30AM-03:00AM";
        $title[42] = "03:00AM-03:30AM";
        $title[43] = "03:30AM-04:00AM";
        $title[44] = "04:00AM-04:30AM";
        $title[45] = "04:30AM-05:00AM";
        $title[46] = "05:00AM-05:30AM";
	$title[47] = "05:30AM-06:00AM";
*/
/*
	$iSDay = 0;
	$iEDay = 0;

        for ($j = 0; $j < count($title); $j++){
		$sTime = substr($title[$j], 0, 7);
		$eTime = substr($title[$j], 8, 7);
		
		$sAM = substr($title[$j], 5, 2);
		$sHour = substr($title[$j],0,2);
		$eAM = substr($title[$j], 13, 2);
		$eHour = substr($title[$j],8,2);

		if ($eAM =="AM" && $eHour == "12"){
			$iEDay = 1;
		}
		if ($sAM == "AM" && $sHour == "12"){
			$iSDay = 1;
		}

		$sql = "select distinct to_char(activity_date, 'hh12:mi AM') , login_id from $db_log
	                where location_code = '47T' and
                	activity_date >=to_date('$SDate $sTime', 'mm/dd/yyyy hh:miAM') + $iSDay and
                	activity_date < to_date('$SDate $eTime', 'mm/dd/yyyy hh:miAM') + $iEDay ";
//echo $sql;
        	$statement = ora_parse($cursor, $sql);
        	ora_exec($cursor);
		
		$k = 0;
		while(ora_fetch($cursor)){
			$time = ora_getcolumn($cursor, 0);
			$id = ora_getcolumn($cursor, 1);
		
			if ($k ==0){
				$Title = $title[$j];
			}else{
				$Title = "";
			}
			$k++;
			array_push($data, array('title'=>'<b>'.$Title.'</b>',
					  	 'time'=>$time,
						 'id'=>$id));
		}
		if ($k ==0){
			$count_skipped +=1;
			array_push($data, array('title'=>'<b>'.$title[$j].'</b>',
                                                'time'=>$strBlank,
                                                'id'=>''));

		}else{
			$count_scan += $k;
		}
	}
        $tot_count_scan += $count_scan;
        $tot_count_skipped += $count_skipped;

        $pdf->ezNewPage();

        $pdf->ezText("<b>SECURITY LOG OF RAILROAD TRACK ENTRANCE</b>", 14, $center);
        $pdf->ezText("<b>$SDate 6:00AM -- $EDate 6:00AM</b>", 12, $center);
			
        $pdf->ezSetDy(-10);

        $pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>8,'cols'=>$arrCol));
        $pdf->ezSetDy(-5);
        $pdf->ezText("<b>                                        Scanned: $count_scan; Skipped: $count_skipped</b>", 12, $left);

   }
*/
  if($export){
   	$table = "<TABLE border=1 CELLSPACING=1>";
   	$table .= $expStr;

	$table .= "</table>";
   	//export to excel
   	header("Content-Type: application/vnd.ms-excel; name='excel'");
   	header("Content-Disposition: attachment; filename=Export.xls");

   	echo $table;

  }else if (!$sendMail ){
   	$pdf ->ezStream();
  }else if ($sendMsg){
        	$mailTo = "lstewart@port.state.de.us";
   		$mailTo1  = "ithomas@port.state.de.us,";
      	$mailTo1 .= "lstewart@port.state.de.us,";
	$mailTo1 .= "awalter@port.state.de.us,";
      	//$mailTo1 .= "mpallmann@port.state.de.us";

      	$mailsubject = "Security Log--Total Scanned: ".$tot_count_scan." Skipped: ".$tot_count_skipped;
      	$mailheaders  = "From: " . "MailServer@port.state.de.us\r\n";

        mail($mailTo1, $mailsubject, "", $mailheaders);

  }else{
/*
	$DayOfWeek = ('D');
	
	if ($DayOfWeek =="Sun" || $DayOfWeek =="Mon"){
		$sTime = "06:00 AM";
        }else{
		$sTime = "06:00 PM";
        }
*/
	$sql = "select count(*) from $db_log where activity_date >= to_date('$sDate 06:00 PM', 'mm/dd/yyyy hh:mi AM') and activity_date < to_date('$eDate 06:00 AM', 'mm/dd/yyyy hh:mi AM')";

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor); 
	
	if (ora_fetch($cursor)){
		$tot = ora_getcolumn($cursor, 0);
	}
 
	if ($tot > 0){
		$warning = false;
        }else{
		$warning = true;
        }

        if (!$warning){
        	// output
        	$pdfcode = $pdf->ezOutput();

        	$File=chunk_split(base64_encode($pdfcode));


        	$mailTO = "lstewart@port.state.de.us";
		$mailTO = "ithomas@port.state.de.us";
		$mailTO = "awalter@port.state.de.us";

        	$mailTo1 = "gbailey@port.state.de.us,";
        	$mailTo1 .="skennard@port.state.de.us,";
        	$mailTo1 .="ithomas@port.state.de.us,";
		$mailTo1 .="fvignuli@port.state.de.us";
       

        	$mailsubject = "Security Log--Total Scanned: ".$tot_count_scan." Skipped: ".$tot_count_skipped;;

        	$mailheaders  = "From: " . "MailServer@port.state.de.us\r\n";
		$mailheaders .= "Cc: meskridge@port.state.de.us, phemphill@port.state.de.us,lstewart@port.state.de.us, awalter@port.state.de.us\r\n";
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
        	//$Content.=" Just sent you the attached file for review.\n";
        	$Content.="\r\n";
        	$Content.="--MIME_BOUNDRY\r\n";
        	$Content.="Content-Type: application/pdf; name=\"Security Log.pdf\"\r\n";
        	$Content.="Content-disposition: attachment\r\n";
        	$Content.="Content-Transfer-Encoding: base64\r\n";
        	$Content.="\r\n";
        	$Content.=$File;
        	$Content.="\r\n";
        	$Content.="--MIME_BOUNDRY--\n";


//        	mail($mailTO, $mailsubject, $Content, $mailheaders);   //for testing purposes
       	mail($mailTo1, $mailsubject, $Content, $mailheaders);
	}else{
        	$sec_mailTO = "skennard@port.state.de.us";
        	$sec_mailsubject = "Security Log Warning";
        	$sec_mailheaders = "From: hdadmin@port.state.de.us\r\n";
        	$sec_mailheaders .= "cc: ithomas@port.state.de.us, meskridge@port.state.de.us, phemphill@port.state.de.us, awalter@port.state.de.us, lstewart@port.state.de.us\r\n";
        	$sec_mailheaders .= "Bcc: hdadmin@port.state.de.us\r\n";
        	$sec_body = "Warning:  No Location Scans were done around the port by Security since $sDate 6:00 PM until now as per System Records.\r\n\r\n";
        	$sec_body .= "In case of technical difficulties please open a new case # on Help Desk and email case # to Help Desk Administrator for prompt action.\r\n";

        	mail($sec_mailTO, $sec_mailsubject, $sec_body, $sec_mailheaders);
	
	}
   }

   ora_close($cursor);

?>
