<?
//   error_reporting(0);
   $conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if($conn < 1){
       	printf("Error logging on to the Oracle Server: ");
       	printf(ora_errorcode($conn));
       	printf("Please try later!");
       	exit;
   }
   $cursor = ora_open($conn);

   $upload = false;

   $scaner_host_error[0] = false;
   $scaner_host_error[1] = false;
 
   $reset_timer = false;

   $array_indx = 0;

   $security_log = "security_log"; 

   $dir ="/web/web_pages/upload/security";
   $sub_dir[0] = "/security";
   $sub_dir[1] = "/security2";
//   $dir="/web/web_pages/upload/scan_files";
   //upload data

  $y = date("y");
  $m = date("m");
  $d = date("d");	  
  $H = date("H");
  $i = date("i");
  $s = date("s");


  $sTime = mktime($H - 2, $i-15, $s, $m, $d, $y);
  $last_upload_time = mktime($H, $i-15, $s, $m, $d, $y);

  $array_indx = 0;
  $count = 0;

  for($i = 0; $i < 2; $i++){
        if ($dh = opendir($dir.$sub_dir[$i])) {
                while (($file = readdir($dh)) !== false) {
                        if ($ipos = strrpos($file, ".")){
                                if (substr($file, $ipos + 1 )=="log"){
                                        $file_array[$array_indx] = $dir.$sub_dir[$i]."/".$file;
                                        $array_indx ++;
                                }
                        }
                }
                closedir($dh);
        }else{
		$scaner_host_error[$i] = true;
 	} 
  }



  for($i = 0; $i < $array_indx; $i++){
        $pos = strrpos($file_array[$i], "/");
        $fName = substr($file_array[$i], $pos+1);
        $path = substr($file_array[$i], 0, $pos+1);

	if (is_file($path.$fName) && $handle = fopen ($path.$fName, "r")){
                $uM = substr($fName,2,2);
                $uD = substr($fName, 4, 2);
                $uY = "20".substr($fName, 0, 2);
                $uDate = $uM."/".$uD."/".$uY;
 
          	while (!feof ($handle)) {
                    	$fLine = fgets($handle);
                      	list($upTime, $upIP, $upDetail) = split(": ", $fLine);
                      	if ($upDetail <>""){
                              	$uH = substr($fLine, 0, 2);
                               	$uMi = substr($fLine, 3, 2);
                       		$uTime = mktime($uH, $uMi, 0, $uM, $uD, $uY);

							
				list($login_id, $activity_date, $activity_time, $location_code)=split("\|", $upDetail);

                       		list($iY, $iM, $iD) = split("\/", $activity_date);
                       		//list($iH, $iMM, $iS) = split(":", $activity_time);

                       		//$iTime = mktime($iH, $iMM, $iS, $iM, $iD, $iY);
 				if ($uTime > $sTime) {
					$count++;
                     			if ($uTime > $sTime && ($activity_date =="2000/01/01" || $activity_date =="2004/01/01" ) {
                            			$reset_timer = true;
                       			}
	
				}
			}
             	}
           	fclose($handle);
	}
  }
   	
  if ($count ==0){
        $sql = "select count(*) from $security_log where activity_date >to_date('".date("m/d/y H:i:s", $sTime)."', 'mm/dd/yy hh24:mi:ss')";

	$statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        
        if(ora_fetch($cursor)){
		$count = ora_getcolumn($cursor, 0);
	}
  }

  //update security_log_upload
  $sql = "select to_char(last_upload_time, 'mm/dd/yy hh24:mi:ss') from security_log_upload";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  if(ora_fetch($cursor)){
	$last_time = ora_getcolumn($cursor, 0);
  }
  $sql = "";
  if ($count > 0 && $last_time <>""){
	$sql = "update security_log_upload set last_upload_time = to_date('".date("m/d/Y H:i:s",$last_upload_time)."','mm/dd/yyyy hh24:Mi:ss')";
  }else if ($count >0 && $last_time == "") {
	$sql = "insert into security_log_upload values (to_date('".date("m/d/Y H:i:s",$last_upload_time)."','mm/dd/yyyy hh24:mi:ss'))";
  }else if ($count ==0 && $last_time ==""){
	$last_time = date("m/d/y H:i:s",$sTime);
	$sql = "insert into security_log_upload values (to_date('$last_time','mm/dd/yy hh24:mi:ss'))";
  }

  if ($sql <>""){
	$statement = ora_parse($cursor, $sql);
  	ora_exec($cursor);
  }


$mailheader ="From:hdadmin@port.state.de.us\r\n";
$mailheader.="Bcc: hdadmin@port.state.de.us";

if ($scaner_host_error[0] || $scaner_host_error[1]){
	$mailTo  = "3025455871@vtext.com,";
        $mailTo .= "3023839500@vtext.com,";
//        $mailTo .= "3028935955@vtext.com,";
        $mailTo .= "3028935624@vtext.com";
	
	$body = "Web server can not link to security scaner host";
        if ($scaner_host_error[0]){
		$body.= " dspc-171/security";
	}
	if ($scaner_host_error[0] && $scaner_host_error[1]){
		$body.= " and ";
	} 
	if ($scaner_host_error[1]){
		$body.= " dspc-160/security";
	}
	$body .= ".";
//$mailTo= "3028935624@vtext.com";

	mail($mailTo, "", $body, $mailheader);


}else if ($reset_timer || $count ==0){
	$mailTo  = "3025455871@vtext.com,";
	$mailTo .= "3023839500@vtext.com,";
//	$mailTo .= "3028935955@vtext.com,";
	$mailTo .= "3028935624@vtext.com";

 //       $mailTo ="rwang@port.state.de.us";
	if ($reset_timer){
		$body = "The security scanner's time setup is WRONG since ".date("m/d/y H:i:s",$sTime);
	} else {
		$body ="There is no security log upload since $last_time";
        }
//$mailTo= "3028935624@vtext.com";

	mail($mailTo, "", $body, $mailheader);
}else{
	$mailTo= "3028935624@vtext.com";
	$body = "Total security scans is $count since ".date("m/d/y H:i:s",$sTime);
	$mailheader ="From:hdadmin@port.state.de.us";
//	mail($mailTo, "", $body, $mailheader);
}

ora_close($cursor);
ora_logoff($conn);
?>
