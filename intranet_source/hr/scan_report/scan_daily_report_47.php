<?
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
   }
   $cursor = ora_open($conn);
   $cursor1 = ora_open($conn);

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

	$data = array();

        $sql = "select location_code, location_code ||'  '||short_desc
                from $db_location where status ='A' order by location_code";

        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);


	while (ora_fetch($cursor)){
		$loc = ora_getcolumn($cursor, 0);
		$location = ora_getcolumn($cursor, 1);

		$SQL[0]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                	from $db_log
                	where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 6/24 and
                	activity_date < to_date('$SDate', 'mm/dd/yyyy') + 8/24";
                $SQL[1]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 8/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 10/24";
                $SQL[2]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 10/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 12/24";
                $SQL[3]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 12/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 14/24";
                $SQL[4]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 14/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 16/24";
                $SQL[5]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 16/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 18/24";
 
		$detail = array();
		$count = 0;
		for($j = 0; $j < 6; $j++){
			$statement = ora_parse($cursor1, $SQL[$j]);
        		ora_exec($cursor1);
			$k = 0;
			while(ora_fetch($cursor1)){
				$detail[$j][$k]= ora_getcolumn($cursor1, 0);
				$k++;
				if ($count < $k)
					$count = $k;
			}
			if ($k == 0){
				$count_skipped++;
				$detail[$j][0] = $strBlank;
			}else{
				$count_scan += $k;
			}
		}
 		array_push($data, array('loc'=>$location,
					'd06'=>$detail[0][0],
					'd08'=>$detail[1][0],
					'd10'=>$detail[2][0],
					'd12'=>$detail[3][0],
					'd14'=>$detail[4][0],
					'd16'=>$detail[5][0]));
		for ($j = 1; $j < $count; $j++){
			array_push($data, array('loc'=>'',
                                        	'd06'=>$detail[0][$j],
                                        	'd08'=>$detail[1][$j],
                                        	'd10'=>$detail[2][$j],
                                        	'd12'=>$detail[3][$j],
                                        	'd14'=>$detail[4][$j],
                                        	'd16'=>$detail[5][$j]));
		}
	}

        $tot_count_scan += $count_scan;
        $tot_count_skipped += $count_skipped;

        $pdf->ezNewPage();

        $pdf->ezText("<b>SECURITY LOG FOR $SDate DAY</b>", 20, $center);
        $pdf->ezSetDy(-10);

        $pdf->ezTable($data, $arrDayHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>8,'cols'=>$arrDayCol));
        $pdf->ezSetDy(-5);
        $pdf->ezText("<b>Scanned: $count_scan; Skipped: $count_skipped</b>", 12, $left);	

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

        $data = array();
     
        $sql = "select location_code, location_code ||'  '||short_desc 
		from $db_location where status ='A' order by location_code";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);

        while (ora_fetch($cursor)){
                $loc = ora_getcolumn($cursor, 0);
		$location = ora_getcolumn($cursor, 1);
                $SQL[0]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log 
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 18/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 20/24";
                $SQL[1]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 20/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 22/24";
                $SQL[2]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 22/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 24/24";
                $SQL[3]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 24/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 26/24";
                $SQL[4]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 26/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 28/24";
                $SQL[5]="select distinct to_char(activity_date, 'hh24:mi') ||'   '|| login_id  as detail
                        from $db_log
                        where location_code = '$loc' and activity_date >=to_date('$SDate', 'mm/dd/yyyy') + 28/24 and
                        activity_date < to_date('$SDate', 'mm/dd/yyyy') + 30/24";

		$detail = array();
		$count = 0;
                for($j = 0; $j < 6; $j++){
                        $statement = ora_parse($cursor1, $SQL[$j]);
                        ora_exec($cursor1);
                        $k = 0;
                        while(ora_fetch($cursor1)){
                                $detail[$j][$k]= ora_getcolumn($cursor1, 0);
                                $k++;

                                if ($count < $k)
                                        $count = $k;
                        }
                        if ($k == 0){
                                $count_skipped++;
                                $detail[$j][0] = $strBlank;
                        }else{
                                $count_scan += $k;
                        }
                }


                array_push($data, array('loc'=>$location,
                                        'd18'=>$detail[0][0],
                                        'd20'=>$detail[1][0],
                                        'd22'=>$detail[2][0],
                                        'd00'=>$detail[3][0],
                                        'd02'=>$detail[4][0],
                                        'd04'=>$detail[5][0]));

               for ($j = 1; $j < $count; $j++){
                        array_push($data, array('loc'=>'',
                                                'd18'=>$detail[0][$j],
                                                'd20'=>$detail[1][$j],
                                                'd22'=>$detail[2][$j],
                                                'd00'=>$detail[3][$j],
                                                'd02'=>$detail[4][$j],
                                                'd04'=>$detail[5][$j]));
                }

        }



      	$tot_count_scan += $count_scan;
    	$tot_count_skipped += $count_skipped;

	
	if ($i > 0) {
		$pdf->ezNewPage();
	}

      	$pdf->ezText("<b>SECURITY LOG FOR $SDate NIGHT</b>", 20, $center);
    	$pdf->ezSetDy(-10);

       	$pdf->ezTable($data, $arrEveningHeading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>8,'cols'=>$arrEveningCol));
	$pdf->ezSetDy(-5);
	$pdf->ezText("<b>Scanned: $count_scan; Skipped: $count_skipped</b>", 12, $left);

 
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
        $mailTo = "rwang@port.state.de.us";
   	$mailTo1  = "ithomas@port.state.de.us,";
      	$mailTo1 .= "rwang@port.state.de.us,";
       	$mailTo1 .= "mpallmann@port.state.de.us";

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


        	$mailTO = "rwang@port.state.de.us";
//		$mailTO = "ithomas@port.state.de.us";

        	$mailTo1 = "gbailey@port.state.de.us,";
        	$mailTo1 .="skennard@port.state.de.us,";
        	$mailTo1 .="ithomas@port.state.de.us";
       

        	$mailsubject = "Security Log--Total Scanned: ".$tot_count_scan." Skipped: ".$tot_count_skipped;;

        	$mailheaders  = "From: " . "MailServer@port.state.de.us\r\n";
		$mailheaders .= "Cc: bboles@port.state.de.us, phemphill@port.state.de.us,rwang@port.state.de.us,mpallmann@port.state.de.us\r\n";
        	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
        	$mailheaders .= "MIME-Version: 1.0\r\n";
        	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
        	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        	$mailheaders .= "X-Mailer: PHP4\r\n";
        	$mailheaders .= "X-Priority: 3\r\n";
       	 	$maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
        	$maileaders  .= "This is a multi-part Contentin MIME format.\r\n";


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


//        	mail($mailTO, $mailsubject, $Content, $mailheaders);
       		mail($mailTo1, $mailsubject, $Content, $mailheaders);
	}else{
        	$sec_mailTO = "skennard@port.state.de.us";
        	$sec_mailsubject = "Security Log Warning";
        	$sec_mailheaders = "From: hdadmin@port.state.de.us\r\n";
        	$sec_mailheaders .= "cc: ithomas@port.state.de.us, bboles@port.state.de.us, phemphill@port.state.de.us\r\n";
        	$sec_mailheaders .= "Bcc: hdadmin@port.state.de.us,rwang@port.state.de.us,mpallmann@port.state.de.us\\r\n";
        	$sec_body = "Warning:  No Location Scans were done around the port by Security since $sDate 6:00 PM until now as per System Records.\r\n\r\n";
        	$sec_body .= "In case of technical difficulties please open a new case # on Help Desk and email case # to Help Desk Administrator for prompt action.\r\n";

        	mail($sec_mailTO, $sec_mailsubject, $sec_body, $sec_mailheaders);
	
	}
   }

   ora_close($cursor);

?>
