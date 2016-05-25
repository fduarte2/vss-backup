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

        $sql =  "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name, comments,
		req_id
                from temperature_req
                where (whse, box, req_id) in
                (select whse, box, max(req_id) from temperature_req
                where effective <= sysdate and whse='$whse' and box=$box
                group by whse, box)";

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
                $bgcolor = "bgcolor=lightgreen";
                $fcolor = "#000000";

                //Color Coding Starts Here
                if (($set_point == "Shut Down") || ($actual_set_point == "Shut Down") || ($actual_set_point == "Repair")) {
			// Leave Color as Light Green and get out 
	        }
		else
                	if ($actual_set_point == "E&M Override") {
				$bgcolor = "bgcolor=white";
			}
			else 
				if ($quality == "Bad") {
					$bgcolor = "bgcolor=blue";
                                        $temp = "Icetec Error";
		     		}
		     		else 
					if ($temp - $set_point > ($prodTemp[$prod][HIGH_WARNING])) {
			     			$bgcolor = "bgcolor=red";
		        		}
					else 
						if ($temp - $set_point > ($prodTemp[$prod][HIGH_ALERT])) {
							$bgcolor = "bgcolor=yellow";
			     			}

                if ($whse <> $pre_whse) {
			$j++;
			$pre_whse = $whse;
		}

      		if ($box <> "Unknown"){
             		$comm = ucwords(strtolower($comm));
           		$pComm = ucwords(strtolower($pComm));
            //		array_push($data[$j], array('whse'=>$whse, 'box'=>$box, 'temp'=>$temp, 'avg'=>$avg_temp,'actul_set'=>$actual_set_point, 'set_point'=>$set_point, 'effective'=>$effective, 'expired'=>$expired, 'prod'=>$prod,'user'=>$user,'comments'=>$comments,'rId'=>$rId,'bgcolor'=>$bgcolor,'fcolor'=>$fcolor));
					array_push($data[$j], array('whse'=>$whse, 'box'=>$box, 'temp'=>$temp, 'avg'=>$actual_set_point,'actul_set'=>$actual_set_point, 'set_point'=>$set_point , 'effective'=>$effective, 'expired'=>$expired, 'prod'=>$prod,'user'=>$user,'comments'=>$comments,'rId'=>$rId,'bgcolor'=>$bgcolor,'fcolor'=>$fcolor));
    		
    		}
	
   }
   ora_close($lcs_cursor);
   ora_close($lcs_cursor2);
   ora_logoff($conn);
   pg_close($pg_conn );

   include("utility.php");  	
?>
<html>
<style>td{font:16px; color:#ffffff}</style>
<body onload = "setTimeout('window.location.reload()', 600000)">
<center>
<table bgcolor=#ffffff>
   <tr>
	<td width=5%>&nbsp;</td>
	<td align=center><b><font size = 8 color=#000000>Warehouse Temperature Status</font></b></td>
	<td width=5%>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td align=center><i><font size = 3 color=#000000><?= date('m/d/Y h:i:s A')?></font></i></td>
        <td>&nbsp;</td>
   </tr>
   <tr>
        <td>&nbsp;</td>
        <td>
	<table border=0 width=1080 cellspacing=1 cellpadding=1 bgcolor=#ffffff>
	    <tr bgcolor=#000000>
	   	<td width=80 height=30><b>WING</b></td>
                <td width=80><b>Box</b></td>
                <td width=140><b>Supervisor</b></td>
                <td width=140><b>Commodity</b></td>
                <td width=140><b>Request (OPS)</b></td>
                <td width=180><b>Current (Icetec)</b></td>
                <td width=180><b>E&amp;M Target</b></td>
                <td width=80><b>Req#</b></td>

	    </tr>

<?
	for($i= 0; $i < 5; $i++){ 
		for ($j= 0; $j < count($data[$i]); $j++){       
		$myRange=html($data[$i][$j]['whse']).html($data[$i][$j]['box']);
?>	
			      
            <tr bgcolor=#000000>
<?			if ($j == 0) { ?>
                <td rowspan=<?=count($data[$i])?> valign=top><b><?= html($data[$i][$j]['whse'])?></b></td>
<?			} 	?>
			<?if ($myRange!= 'A4') {?>
			
			<?				
				//$data[$i][$j]['set_point']="E&M Overide";
				if($data[$i][$j]['set_point']=="E&M Overide")
				{
				}
			?>			
			
			<td height=25 <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['box'])?></font></td>
                <td <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['user'])?></font></td>
                <td <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['prod'])?></font></td>
                <td <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['set_point'])?></font></td>
                <td <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['temp'])?></font></td>
                <td <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['avg'])?></font></td>
                <td <?=$data[$i][$j]['bgcolor']?>>
			<font color=<?=$data[$i][$j]['fcolor']?>><?= html($data[$i][$j]['rId'])?></font></td>
			<?}?>
            </tr>
<?              }      
	}	 
?>

	</table>
	</td>
        <td width=2%>&nbsp;</td>
   </tr>
</table>
</body>
</html>



