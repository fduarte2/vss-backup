<?

   include("pow_session.php");
   $user = $userdata['username'];

   // Define some vars for the skeleton page
   $title = "Current Temperature Report";
   $area_type = "SUPV";


   $basefile = "temp_s17.txt";

   $dir = '/var/www/html/upload/pdf_files';
   if (!file_exists($dir)) {
     mkdir ($dir, 0775);
   }

   $basefile = tempnam($dir . '/', '') . '.txt';

  // http vars
   $user = "rwang";
   $pass = "6rel55ax";
   $url = "https://pow.icetec.biz/apps/getTemps.cgi";
   $url = "https://pow.icetec.biz/apps/quickview.cgi";

   // Build the command
   $cmd = "/usr/bin/wget --http-user=" . $user . " --http-passwd=" . $pass . " -O " . $basefile . " " . $url;

 
   // Run the command
   system("$cmd");


   $range = 2.5;
   // initiate the pdf writer
//   include 'class.ezpdf.php';
//   $pdf = new Cezpdf('letter','portrait');


   include("connect_data_warehouse.php");
 
   $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pg_conn){
      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
   }
  
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $cursor = ora_open($conn);

   $lcs_conn = ora_logon("LABOR@LCS", "LABOR");
   if ($lcs_conn < 1) {
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($lcs_conn));
        printf("Please try later!");
        exit;
   }
   $lcs_cursor = ora_open($lcs_conn);
   $lcs_cursor2 = ora_open($lcs_conn);

   $run_date = $HTTP_POST_VARS["run_date"];
   if ($run_date ==""){
        $run_date = date('m/d/Y');
   }

   $pretty_today = date('m/d/Y', strtotime($run_date));
   $today = date('Y-m-d', strtotime($run_date));

   $deg = chr(248);

   $arrHeading1 = array('box'=>'','temp'=>'<b>Current</b>','req'=>'<b>Temperature Request</b>');
   $arrCol1 = array('box'=>array('width'=>29, 'justification'=>'center'),
                   'temp'=>array('width'=>95, 'justification'=>'center'),
                   'req'=>array('width'=>370, 'justification'=>'center'));
   $arrHeading = array('box'=>'<b>WING</b>','prod'=>'<b>Commodity</b>','temp'=>'<b>Current (Icetec)</b>','avg'=>'<b>24hr Avg (Icetec)</b>','actul_set'=>'<b> Set For (E&M)</b>','set_point'=>'<b>Request (OPS)</b>','effective'=>'<b>Start</b>','expired'=>'<b>End</b>','user'=>'<b>By</b>','rId'=>'<b>Req#</b>','comments'=>'<b>Requestor Comments</b>','status'=>'<b>E&M</b>');

   $arrCol = array('box'=>array('width'=>35, 'justification'=>'center'),
                   'prod'=>array('width'=>60, 'justification'=>'center'),
                   'temp'=>array('width'=>55, 'justification'=>'center'),
                   'actul_set'=>array('width'=>55, 'justification'=>'center'),
                   'avg'=>array('width'=>55, 'justification'=>'center'),
                   'set_point'=>array('width'=>50, 'justification'=>'center'),
                   'effective'=>array('width'=>42, 'justification'=>'center'),
                   'expired'=>array('width'=>42, 'justification'=>'center'),
                   'user'=>array('width'=>45, 'justification'=>'center'),
                   'rId'=>array('width'=>30, 'justification'=>'center'),
                   'comments'=>array('width'=>190,'justification'=>'left'),
                   'status'=>array('width'=>100,'justification'=>'left'));


   $heading = array();
   $heading1 = array();
   array_push($heading, $arrHeading);
   array_push($heading1, $arrHeading1);

   $sql = "select * from product_temp";
   ora_parse($cursor, $sql);
   ora_exec($cursor);

   $prodTemp = array();
   while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        $prodTemp[$row['PRODUCT']]['LOW_ALERT']=$row['LOW_ALERT'];
        $prodTemp[$row['PRODUCT']]['HIGH_ALERT']=$row['HIGH_ALERT'];
        $prodTemp[$row['PRODUCT']]['LOW_WARNING']=$row['LOW_WARNING'];
        $prodTemp[$row['PRODUCT']]['HIGH_WARNING']=$row['HIGH_WARNING'];
   }

   //get data
   $data[0] = array();
   $data[1] = array();
   $data[2] = array();
   $data[3] = array();
   $data[4] = array();

   $warehouse[0] = "WING A";
   $warehouse[1] = "WING B";
   $warehouse[2] = "WING C";
   $warehouse[3] = "WING D";
   $warehouse[4] = "WING E";

/*
   $sql = "select distinct whse, box from warehouse_location order by whse, box";
   $result = pg_query($pg_conn, $sql);
   $rows = pg_num_rows($result);
   for ($i = 0; $i < $rows; $i++){
        $row = pg_fetch_row($result, 0);
        $whse = $row[0];
	$box = $row[1];
	
	$arrTemp[$whse][$box] = "";
   }
*/
   //parse file
   if ($handle = fopen ($basefile, "r")){
        while (!feof ($handle)) {
                $fLine = fgets($handle);
                if (substr($fLine, 0, 1) != "#"){
                        list($location, $temp, $setpoint, $avg_temp)=split(",", $fLine);
                        $location = trim($location);
                        $temp = trim($temp);
                        $setpoint = trim($setpoint);
			$avg_temp = trim($avg_temp);

                        $whse = substr($location, 0,1);
                        $box = substr($location, 1, -4);

                        if ($temp <>"") $temp = number_format($temp, 1);
                        if ($avg_temp <>"") $avg_temp = number_format($avg_temp, 1);

                        $arrTemp[$whse][$box] = $temp;
                        $arrAvgTemp[$whse][$box] = $avg_temp;
/*			
			$sql = "select whse, box from warehouse_location where whse||old_box||'TEMP' = '$location'";
   			$result = pg_query($pg_conn, $sql);
   			$rows = pg_num_rows($result);
			if ($rows > 0){
				$row = pg_fetch_row($result, 0);
        			$whse = $row[0];
        			$box = $row[1];
				
				if ($temp <>"") $temp = number_format($temp, 1);
	                        if ($avg_temp <>"") $avg_temp = number_format($avg_temp, 1);

				$arrTemp[$whse][$box] = $temp;
				$arrAvgTemp[$whse][$box] = $avg_temp;
			}
*/
		}
	}
        fclose($handle);
        unlink($basefile);
   }

   $sql = "select distinct whse, box from warehouse_location order by whse, box";
   $result = pg_query($pg_conn, $sql);
   $rows = pg_num_rows($result);
        
   $pre_whse = "A";
   $j = 0;

   for ($i = 0; $i < $rows; $i++){
        $row = pg_fetch_row($result, $i);
        $whse = $row[0];
        $box = $row[1];
        $whse_box = $whse."-".$box;

   	$sql =  "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name, comments
        	from temperature_req
          	where (whse, box, req_id) in
          	(select whse, box, max(req_id) from temperature_req
          	where effective <= sysdate and (expired is null or expired >= sysdate and whse='$whse' and box=$box)
          	group by whse, box)";
        $sql =  "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name, comments,
		req_id, low_temp, high_temp
                from temperature_req
                where (whse, box, req_id) in
                (select whse, box, max(req_id) from temperature_req
                where effective <= sysdate and whse='$whse' and box=$box
                group by whse, box)";

        $sql =  "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name, r.comments,
                req_id, low_temp, high_temp, s.comments
                from temperature_req r, warehouse_status s
                where (whse, box, req_id) in
                (select whse, box, max(req_id) from temperature_req
                where effective <= sysdate and whse='$whse' and box=$box
                group by whse, box) and warehouse_box = '$whse_box'";

   	$statement = ora_parse($lcs_cursor, $sql);
   	ora_exec($lcs_cursor);
   	
   	if(ora_fetch($lcs_cursor)){
        	$whse = ora_getcolumn($lcs_cursor, 0);
        	$box = ora_getcolumn($lcs_cursor, 1);
        	$effective = ora_getcolumn($lcs_cursor, 2);
		if ($effective <>"") $effective = date('m/d/y', strtotime($effective));
        	$expired = ora_getcolumn($lcs_cursor, 3);
		if ($expired <>""){
			$expired_time = strtotime($expired);
			$expired = date('m/d/y', strtotime($expired));
		}
        	$set_point = ora_getcolumn($lcs_cursor, 4);
        	$actual_set_point = ora_getcolumn($lcs_cursor, 5);
        	$prod = ora_getcolumn($lcs_cursor, 6);
        	$user = ora_getcolumn($lcs_cursor, 7);
        	$comments = ora_getcolumn($lcs_cursor, 8);
		$comments = substr($comments, 0, 45);
		$rId = ora_getcolumn($lcs_cursor, 9);
                $low = ora_getcolumn($lcs_cursor, 10);
                $high = ora_getcolumn($lcs_cursor, 11);
		$status = ora_getcolumn($lcs_cursor, 12);

	}else{
		$effective = "";
		$expired = "";
		$set_point = "";
		$actual_set_point = "";
		$prod = "";
		$user = "";
		$comments = "";
		$rId = "";
		$status = "";
	}
		$temp = $arrTemp[$whse][$box];
		$avg_temp = $arrAvgTemp[$whse][$box];

                if ($temp =="")
                        $temp = "Unavailable";
                if ($avg_temp =="")
                        $avg_temp = "Unavailable";

                if ($temp <> "" && $temp <> "Unavailable" && $set_point <>"Shut Down" ){
                        if (($temp-$set_point) > $prodTemp[$prod]['HIGH_ALERT'] ||
                            ($set_point - $temp) > $prodTemp[$prod]['LOW_ALERT']) {
                                $out_of_range ++;
                                //$avg_temp = $avg_temp."*";
                                if ($strOut_of_range == ""){
                                        $strOut_of_range .= $whse."-".$box;
                                }else{
                                        $strOut_of_range .= ", ".$whse."-".$box;
                                }
                                $box = $box."*";
                        }
                }
/*

                        if (($prod=="Meat" && ($avg_temp>10 || $avg_temp<0)) || ($prod <>"Meat" && abs($avg_temp-$set_point)>$range)) {
                                $out_of_range ++;
                                //$avg_temp = $avg_temp."*";
                                if ($strOut_of_range == ""){
                                        $strOut_of_range .= $whse."-".$box;
                                }else{
                                        $strOut_of_range .= ", ".$whse."-".$box;
                                }
				$box = $box."*";
                        }
                }

                if ($avg_temp <> "Shut Down" && $low <>"" && $high <>""){
                        if ($temp < $low || $temp > $high){
                                $out_of_range ++;
                                $temp = $temp."*";
                                if ($strOut_of_range == ""){
                                        $strOut_of_range .= $whse."-".$box;
                                }else{
                                        $strOut_of_range .= ", ".$whse."-".$box;
                                }
                        }
                }

        	if ($temp <>"" && $prod <>"" && $prod <>"Box Empty"){
              		$sql = "select * from product_temp
                        	where product = '$prod' and low_temp <= $temp and high_temp >= $temp";
               		ora_parse($lcs_cursor2, $sql);
               		ora_exec($lcs_cursor2);
               		if (ora_fetch($lcs_cursor2) == false){
              			$out_of_range ++;
                		$temp = $temp."*";
               		}
      		}
*/
		if ($expired_time < time()){
			$set_point = "Expired";
			//$comments = "";
		}

       		if ($set_point == ""){
            		$req_expired ++;
             		$set_point = "None";
             		$effective = "None";
              		$expired = "None";
              		$prod = "None";
            		$user = "None";
			$comments = "";
      		}
           
     		if ($actual_set_point == ""){
         		$not_set ++;
     			$actual_set_point = "Not Set";
       		}

       		if ($whse <> $pre_whse) {
              		$j++;
               		$pre_whse = $whse;
       		}
	        if ($actual_set_point == "Repair"){
                        $set_point = "N/A";
                        $temp = "Repair";
                        $avg_temp = "Repair";
		}
    		if ($temp =="")
          		$temp = "Unavailable";
                if ($avg_temp =="")
                        $avg_temp = "Unavailable";

  		if ($prod == "" && $set_point <> "None" )
          		$prod = "Empty";

      		if ($box <> "Unknown"){
             		$comm = ucwords(strtolower($comm));
           		$pComm = ucwords(strtolower($pComm));
            		array_push($data[$j], array('whse'=>$whse, 'box'=>$whse.'-'.$box, 'temp'=>$temp, 'avg'=>$avg_temp,'actul_set'=>$actual_set_point, 'set_point'=>$set_point, 'effective'=>$effective, 'expired'=>$expired, 'prod'=>$prod,'user'=>$user,'comments'=>$comments,'rId'=>$rId,'status'=>$status));
    		}
	
   }
   ora_close($lcs_cursor);
   ora_close($lcs_cursor2);
   ora_logoff($conn);
   pg_close($pg_conn );
  
	

//   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
   	// initiate the pdf writer
   	include 'class.ezpdf.php';
   	$pdf = new Cezpdf('letter','landscape');

   	$pdf->ezSetMargins(10,10,10,10);
   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
  	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   	$pdf->setFontFamily('Helvetica.afm', $tmp);

   	// Write out the intro.
   	// Print Receiving Header
//   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<b>Daily Temperature Log</b>", 20, $center);
   	$pdf->ezSetDy(-10);
   	$pdf->ezText("<i>". date('m/d/Y h:i A')." </i>", 10, $center);

   	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
/*
        $pdf->ezSetDy(-5);
        $pdf->ezText("Expected-what supervisor expects to be in the box.", 8, $right);
        $pdf->ezText("Actual-what is in the box per the inventory system.", 8, $right);
        $pdf->ezText("Possible-cargo coded only at warehouse level-box number not recorded.", 8, $right);
*/


	for ($i = 0; $i < 5; $i++) {
		$pdf->ezSetDy(-10);
//                $pdf->ezText("<b>".$warehouse[$i]."</b>", 8, $left);
//		$pdf->ezSetDy(-2);
		if ($i == 0){
//        		$pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'width'=>494,'fontSize'=>8,'cols'=>$arrCol1));
        		$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>494,'fontSize'=>8,'cols'=>$arrCol));

		}
/*
                        $pdf->line(19,630,593,630);
                        $pdf->line(48,616,593,616);
                        $pdf->line(19,630,19,602);
                        $pdf->line(48,630,48,602);
                        $pdf->line(138,630,138,602);
                        $pdf->line(323,630,323,602);
                        $pdf->line(593,630,593,602);
                        $pdf->line(93,616,93,602);
                        $pdf->line(188,616,188,602);
                        $pdf->line(233,616,233,602);
                        $pdf->line(393,616,393,602);
                        $pdf->line(493,616,493,602);

                        $pdf->line(59,663,553,663);
                        $pdf->line(88,649,553,649);
                        $pdf->line(59,663,59,636);
                        $pdf->line(88,663,88,636);
                        $pdf->line(183,663,183,636);
                        $pdf->line(553,663,553,636);
                        
                        $pdf->line(133,649,133,636);
                        $pdf->line(233,649,233,636);
                        $pdf->line(313,649,313,636);
                        $pdf->line(393,649,393,636);
                        $pdf->line(483,649,483,636);
*/
//               	$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>575,'fontSize'=>8,'cols'=>$arrCol));

  		$pdf->ezTable($data[$i], $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>575,'fontSize'=>8,'cols'=>$arrCol));
	}
	if ($out_of_range > 0)
		$pdf->ezText("<i>*Out-of-Range</i> (Temperature ragne: Meat 0-10°F; Other Requested temp±".$range."°F)", 8,left);

/*
   	$today = date('m/j/y');
   	$format = "Port of Wilmington, Printed on:" . $today;  
   	$pdf->line(20,40,590,40);

  	$all = $pdf->openObject();
   	$pdf->saveState();
   	$pdf->setStrokeColor(0,0,0,1);
   	//$pdf->line(70,822,578,822);
   	$pdf->addText(25,34,6, $format);
   	$pdf->restoreState();
   	$pdf->closeObject();
   	$pdf->addObject($all,'all');
*/
        $pdf->ezStream();

?>
