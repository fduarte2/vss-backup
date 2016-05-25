<?

/*
*	Adam Walter, Oct/Nov 2012
*	
*	The old way of sending temperature emails is being shut down (finally),
*	Which means I get to use the EMAIL_DISTRIBUTION table instead
*	of the mutt-mail system.  Therefore, I am scrapping the old
*	code, and using the intranet-based script as a new base.
*
*	One day, I'll make the intranet and email scripts the same file, but for now...
***************************************************************************************/

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
   //$url = "https://pow.icetec.biz/apps/getTemps.cgi";
   //$url = "https://pow.icetec.biz/apps/quickview.cgi";
   // avoid DNS resolution IO 01/24/06
//   $url = "https://141.158.16.13/apps/quickview.cgi";
   $url = "https://216.158.41.163/apps/quickview.cgi";
   // Build the command
   $cmd = "/usr/bin/wget --no-check-certificate --http-user=" . $user . " --http-passwd=" . $pass . " -O " . $basefile . " " . $url;

 
   // Run the command
   system("$cmd");


//   include("connect_data_warehouse2.php");
 
//   $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
//   if(!$pg_conn){
//      die("Could not open connection to PostgreSQL DATA_WAREHOUSE database server");
//   }
  
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $cursor = ora_open($conn);

   $lcs_conn = ora_logon("LABOR@LCS", "LABOR");
//   $lcs_conn = ora_logon("LABOR@BNITEST", "LABOR");
   if ($lcs_conn < 1) {
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($lcs_conn));
        printf("Please try later!");
        exit;
   }
   $lcs_cursor = ora_open($lcs_conn);
   $lcs_cursor2 = ora_open($lcs_conn);
   $warehouse_cursor = ora_open($lcs_conn);

   $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//   $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
   if($rf_conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($rf_conn));
     	printf("</body></html>");
       	exit;
   }   
   $Short_Term_Cursor = ora_open($rf_conn);
   $ED_cursor = ora_open($rf_conn);

   $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//   $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
   if($bni_conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($bni_conn));
     	printf("</body></html>");
       	exit;
   }   
   $Short_Term_Cursor_BNI = ora_open($bni_conn);

   $run_date = $HTTP_POST_VARS["run_date"];
   if ($run_date ==""){
        $run_date = date('m/d/Y');
   }

   $pretty_today = date('m/d/Y', strtotime($run_date));
   $today = date('Y-m-d', strtotime($run_date));

   $deg = chr(248);

   //get data
   $data[0] = array();
   $data[1] = array();
   $data[2] = array();
   $data[3] = array();
   $data[4] = array();
   $data[5] = array();

   $warehouse[0] = "WING A";
   $warehouse[1] = "WING B";
   $warehouse[2] = "WING C";
   $warehouse[3] = "WING D";
   $warehouse[4] = "WING E";
   $warehouse[5] = "WING H";

   //parse file
   if ($handle = fopen ($basefile, "r")){
        while (!feof ($handle)) {
                $fLine = fgets($handle);
                if (substr($fLine, 0, 1) != "#"){
                        list($location, $temp, $setpoint, $avg_temp, $quality)=split(",", $fLine);
                        $location = trim($location);
                        $temp = trim($temp);
			$temp2=trim($temp);
                        $setpoint = trim($setpoint);
			$quality = trim($quality);
		
			$whse = substr($location, 0,1);
			$box = substr($location, 1, -4);

	            	if ($temp <>"") $temp = number_format($temp, 1);
                     	if ($avg_temp <>"") $avg_temp = number_format($avg_temp, 1);

			$arrTemp[$whse][$box] = $temp;
                        $arrAvgTemp[$whse][$box] = $avg_temp;
			$status[$whse][$box] = $quality;
		}
	}
        fclose($handle);
        unlink($basefile);
   }

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


   $sql = "SELECT WHSE, BOX, TEMPERATURE_DISPLAY FROM WAREHOUSE_LOCATION ORDER BY WHSE, BOX";
   $statement = ora_parse($warehouse_cursor, $sql);
   ora_exec($warehouse_cursor);
//   ora_fetch_into($warehouse_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//   echo $row['whse'];
        
   $pre_whse = "A";
   $j = 0;

   while(ora_fetch_into($warehouse_cursor, $warehouse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        $whse = $warehouse_row['WHSE'];
//		echo "whse: ".$whse." \n";
        $box = $warehouse_row['BOX'];
//		echo $box." \n";
        $whse_box = $whse."-".$box;
//		echo $whse_box;
		if($warehouse_row['TEMPERATURE_DISPLAY'] == "Y"){
			$display_count = true;
		} else {
			$display_count = false;
		}
//		echo $display_count;
        $sql =  "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name, comments,
		req_id
                from temperature_req
                where (whse, box, req_id) in
                (select whse, box, max(req_id) from temperature_req
                where effective <= sysdate and whse='$whse' and box=$box
                group by whse, box)";
//    echo $sql." \n";
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
		$comments = substr($comments, 0, 50);
		$rId = ora_getcolumn($lcs_cursor, 9);
	}else{
		$effective = "";
		$expired = "";
		$set_point = "";
		$actual_set_point = "";
		$prod = "";
		$user = "";
		$comments = "";
		$rId = "";
	}

		$temp = $arrTemp[$whse][$box];
        $avg_temp = $arrAvgTemp[$whse][$box];
		$quality = $status[$whse][$box];
		// default colors
		$bgcolor = "bgcolor=lightgreen";
		$fcolor = "#000000";

        //Color Coding Starts Here
		// Priority 1:  if no feed, set to blue
		if ($quality != "Good") {
			$bgcolor = "bgcolor=blue";
			$fcolor = "$FFFFFF";
			$temp = "Invalid Icetec Data";
		} elseif (($set_point == "Shut Down") || ($actual_set_point == "Shut Down") || ($actual_set_point == "Repair") || ($prod == "Empty")) {
		// priority 2:  if box not in use, leave green (do nothing)
		} elseif ($actual_set_point == "E&M Override") {
		// priority 3:  if temp in override mode, make white
			$bgcolor = "bgcolor=white";
		} elseif ( ($temp - $set_point) * ($temp - $set_point) > $prodTemp[$prod][HIGH_WARNING] * $prodTemp[$prod][HIGH_WARNING] ) {
		// priority 4:  if high variance, make red
			$bgcolor = "bgcolor=red";
		} elseif ( ($temp - $set_point) * ($temp - $set_point) > $prodTemp[$prod][HIGH_ALERT] * $prodTemp[$prod][HIGH_ALERT] ) {
		// priority 5:  if low variance, make yellow
			$bgcolor = "bgcolor=yellow";
		}
		// lastly, if no substantial variance, don't change color.



			if ($actual_set_point == "") {
				$actual_set_point = "MISSING";
			}
			// special exception:  box in use, and time expired.  make red.
			if ($expired_time < time()){
				$set_point = "EXPIRED";
				$bgcolor = "bgcolor=red";
			}

                if ($whse <> $pre_whse) {
			$j++;
			$pre_whse = $whse;
		}

      		if ($box <> "Unknown"){
             		$comm = ucwords(strtolower($comm));
           		$pComm = ucwords(strtolower($pComm));
            //		array_push($data[$j], array('whse'=>$whse, 'box'=>$box, 'temp'=>$temp, 'avg'=>$avg_temp,'actul_set'=>$actual_set_point, 'set_point'=>$set_point, 'effective'=>$effective, 'expired'=>$expired, 'prod'=>$prod,'user'=>$user,'comments'=>$comments,'rId'=>$rId,'bgcolor'=>$bgcolor,'fcolor'=>$fcolor));
					array_push($data[$j], array('whse'=>$whse, 'box'=>$box, 'temp'=>$temp, 'avg'=>$actual_set_point,'actul_set'=>$actual_set_point, 'set_point'=>$set_point , 'effective'=>$effective, 'expired'=>$expired, 'prod'=>$prod,'user'=>$user,'comments'=>$comments,'rId'=>$rId,'bgcolor'=>$bgcolor,'fcolor'=>$fcolor,'display_count'=>$display_count));
    		
    		}
	
   }
   ora_close($lcs_cursor);
   ora_close($lcs_cursor2);
   ora_close($warehouse_cursor);
   ora_logoff($conn);

   include("utility.php");  	

$output = "<html>
<style>td{font:16px; color:#ffffff}</style>
<center>
<table bgcolor=#ffffff>
   <tr>
	<td width=5%>&nbsp;</td>
	<td align=center><b><font size = 4 color=#000000>Warehouse Temperature Status</font></b></td>
	<td width=5%>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td align=center><i><font size = 3 color=#000000>".date('m/d/Y h:i:s A')."</font></i></td>
        <td>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td align=center><i><font size = 2 color=#000000>* indicates this is under customer control</font></i></td>
        <td>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td>
	<table border=0 width=1080 cellspacing=1 cellpadding=1 bgcolor=#ffffff>
	    <tr bgcolor=#000000>
	   	<td width=80 height=30><b>Wing</b></td>
                <td width=80><b>Box</b></td>
                <td width=140><b>Supervisor</b></td>
                <td width=140><b>Commodity</b></td>
                <td width=180><b>Start</b></td>
				<td width=180><b>End</b></td>
                <td width=140><b>Plts</b></td>
                <td width=140><b>Request (OPS)</b></td>
                <td width=180><b>Current (Icetec)</b></td>
                <td width=180><b>E&amp;M Target</b></td>
                <td width=80><b>Req#</b></td>

	    </tr>";


	for($i= 0; $i < 6; $i++){
	$rowCount = count($data[$i]);
/*	if($data[$i][0]['whse'] == 'A'){
		$rowCount -= 1;
	} */
/*	if($data[$i][0]['whse'] == 'B'){
		$rowCount -= 2;
	}*/
		for ($j= 0; $j < count($data[$i]); $j++){       
		$myRange=html($data[$i][$j]['whse']).html($data[$i][$j]['box']);
	
		 if(true/*$myRange != 'B1' && $myRange != 'B2'*/){ 
						$output .= "<tr bgcolor=#000000>";
						if (($j == 0) /*|| $myRange == 'B4') *//*&& $myRange != 'B1'*/) {
							$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
									WHERE DATE_RECEIVED IS NOT NULL AND
											DATE_RECEIVED < SYSDATE AND
												((COMMODITY_CODE LIKE '708%' AND QTY_IN_HOUSE > 10)
													OR
												 (COMMODITY_CODE LIKE '541%' AND QTY_IN_HOUSE > 10)
													OR
												 (COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
													OR
												 ((COMMODITY_CODE = '5310' OR COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
													OR
												 (COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10)
													OR
												(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
												AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
												AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
												AND ARRIVAL_NUM != '4321'
												AND UPPER(WAREHOUSE_LOCATION) LIKE '".$data[$i][$j]['whse']."%'";
							ora_parse($Short_Term_Cursor, $sql);
							ora_exec($Short_Term_Cursor);
							ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							$whse_total[$i]['whse'] = $data[$i][$j]['whse'];
							$whse_total[$i]['plts'] = $Short_Term_row['THE_COUNT'];

							$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING 
									WHERE (UPPER(WAREHOUSE_LOCATION) LIKE '".$data[$i][$j]['whse']."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$data[$i][$j]['whse']."%') 
									AND DATE_RECEIVED IS NOT NULL
									AND DATE_RECEIVED < SYSDATE
									AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
							ora_parse($Short_Term_Cursor_BNI, $sql);
							ora_exec($Short_Term_Cursor_BNI);
							ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
							$whse_total[$i]['plts'] += $Short_Term_row['THE_SUM'];
							

							
							$output .= "<td rowspan=".$rowCount." valign=top><b>".html($data[$i][$j]['whse'])."</b></td>";
						} 	
						
										
							//$data[$i][$j]['set_point']="E&M Overide";
							if($data[$i][$j]['set_point']=="E&M Overide")
							{
							}
									
						 $sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING 
								WHERE DATE_RECEIVED IS NOT NULL AND
										DATE_RECEIVED < SYSDATE AND
											((COMMODITY_CODE LIKE '708%' AND QTY_IN_HOUSE > 10)
                                                OR
                                             (COMMODITY_CODE LIKE '541%' AND QTY_IN_HOUSE > 10)
                                                OR
                                             (COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
												OR
											 ((COMMODITY_CODE = '5310' OR COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
												OR
											 (COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10)
												OR
											(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
											AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
											AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
											AND ARRIVAL_NUM != '4321'
											AND UPPER(WAREHOUSE_LOCATION) LIKE '".$myRange."%'";
							ora_parse($Short_Term_Cursor, $sql);
							ora_exec($Short_Term_Cursor);
							ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							$in_this_box = $Short_Term_row['THE_COUNT']; 

							$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM FROM CARGO_TRACKING 
									WHERE (UPPER(WAREHOUSE_LOCATION) LIKE '".$myRange."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$myRange."%') 
									AND DATE_RECEIVED IS NOT NULL 
									AND DATE_RECEIVED < SYSDATE
									AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
							ora_parse($Short_Term_Cursor_BNI, $sql);
							ora_exec($Short_Term_Cursor_BNI);
							ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC); 
							$in_this_box += $Short_Term_row['THE_SUM']; 

							$whse_total[$i]['plts'] -= $in_this_box;
							if(!$data[$i][$j]['display_count']){
								$in_this_box = "*";
							} else {
								$in_this_box = round($in_this_box);
							} 
							// super special-exception:  shut down boxes WITH CARGO IN THEM get "oranged" for immediate... noticification?
							if(($data[$i][$j]['set_point'] == "Shut Down" || $data[$i][$j]['actual_set_point'] == "Shut Down") && $in_this_box > 0 && $in_this_box != "*"){
								$data[$i][$j]['bgcolor'] = "bgcolor=orange";
							}

						
							$output .= "<td height=25 ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['box'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['user'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['prod'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['effective'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['expired'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".$in_this_box."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['set_point'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['temp'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['avg'])."</font></td>
							<td ".$data[$i][$j]['bgcolor'].">
						<font color=".$data[$i][$j]['fcolor'].">".html($data[$i][$j]['rId'])."</font></td>
						</tr>";
		 } 
              }      
	}	 


$output .=	"</table>
	</td>
        <td width=2%>&nbsp;</td>
   </tr>
</table>
Box NOT assigned for:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	for($i= 0; $i < 6; $i++){
		if($whse_total[$i]['plts'] > 0){
			$output .= $whse_total[$i]['plts']." plt(s) in ".$whse_total[$i]['whse']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	} 
$output .= "</body>
</html>";



	$File=chunk_split(base64_encode($output));

	$dist = $argv[1]; // SPECIFIED IN CALLED SHELL FILE
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = '".$dist."'";
	ora_parse($ED_cursor, $sql);
	ora_exec($ED_cursor);
	ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   
	$mailTO = $email_row['TO'];
	$mailheaders = "From: ".$email_row['FROM']."\r\n";

	if($email_row['CC'] != ""){
		$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
	}
	if($email_row['BCC'] != ""){
		$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = $email_row['SUBJECT'];

	$body = $email_row['NARRATIVE'];
	$body = str_replace("_newline_", "\r\n", $body);

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/html; name=\"Daily_Temperature_Report.html\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY--\n";

	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					DATE_JOB_COMPLETED,
					COMPLETION_STATUS,
					JOB_EMAIL_TO,
					JOB_EMAIL_CC,
					JOB_EMAIL_BCC,
					JOB_BODY)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'".$dist."',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
	}
