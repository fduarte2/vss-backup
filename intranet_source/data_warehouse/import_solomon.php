<?
  $path = "/web/web_pages/upload/";	 
  $basefile[0] = "gl.csv"; 

  $logfile = "log.txt";

  $log_handle = fopen($path.$logfile, "a+"); 
 
  //get connect
   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
        printf("Error logging on to the lcs Oracle Server: ");
        printf(ora_errorcode($conn_rf));
        printf("Please try later!");
        exit;
   }
   ora_commitoff($conn);
   $cursor = ora_open($conn);

$j = 0;
for($i = 0; $i < count($basefile); $i++){
  //parse file
  if ($handle = fopen ($path.$basefile[$i], "r")){
	while (!feof ($handle)) {
            $fLine = fgets($handle);
            $j++;
	      
//		$fLine = fgets($handle);
		list(
                        $acct,
                        $batnbr,
                        $cramt,
                        $curycramt,
                        $curydramt,
                        $curyeffdate,
                        $curyid,
                        $curymultdiv,
                        $curyrate,
                        $curyratetype,
                        $dramt,
                        $extrefnbr,
                        $fiscyr,
                        $id,
                        $jrnltype,
                        $lineid,
                        $linenbr,
                        $module,
                        $noteid,
                        $origacct,
                        $origsub,
                        $perent,
                        $perpost,
                        $posted,
                        $refnbr,
                        $rlsed,
                        $sub,
                        $trandate,
                        $trandesc,
                        $trantype,
                        $user1,
                        $user2,
                        $user3,
                        $user4
		) 
		= split(",",$fLine);
		
		$acct = str_replace('"',"",$acct);
                $batnbr = str_replace('"',"",$batnbr);
                $curyid = str_replace('"',"",$curyid);
                $curymultdiv = str_replace('"',"",$curymultdiv);
                $curyratetype = str_replace('"',"",$curyratetype);
                $extrefnbr = str_replace('"',"",$extrefnbr);
                $fiscyr = str_replace('"',"",$fiscyr);
                $id = str_replace('"',"",$id);
                $jrnltype = str_replace('"',"",$jrnltype);
                $module = str_replace('"',"",$module);
                $origacct = str_replace('"',"",$origacct);
                $origsub = str_replace('"',"",$origsub);
                $perent = str_replace('"',"",$perent);
                $perpost = str_replace('"',"",$perpost);
                $posted = str_replace('"',"",$posted);
                $refnbr = str_replace('"',"",$refnbr);
                $rlsed = str_replace('"',"",$rlsed);
                $sub = str_replace('"',"",$sub);
                $trandesc = str_replace('"',"",$trandesc);
                $trantype = str_replace('"',"",$trantype);
                $user1 = str_replace('"',"",$user1);
                $user2 = str_replace('"',"",$user2);

		if ($curyeffdate == "00/00/00"){
		 	$curyeffdate = 'null';
		}else{
			$curyeffdate = "to_date('$curyeffdate','mm/dd/yy')";
		}
		
		$id = str_replace("'","''", $id);
		$trandesc = str_replace("'", "''", $trandesc);

		$trandate = str_replace("**", substr($fiscyr, 3,2), $trandate);
				
		$sql = "insert into solomon_gl (
			acct,
			batnbr,
			cramt,
			curycramt,
			curydramt,
			curyeffdate,
			curyid,
			curymultdiv,
			curyrate,
			curyratetype,
			dramt,
			extrefnbr,
			fiscyr,
			id,
			jrnltype,
			lineid,
			linenbr,
			module,
			noteid,
			origacct,
			origsub,
			perent,
			perpost,
			posted,
			refnbr,
			rlsed,
			sub,
			trandate,
			trandesc,
			trantype,
			user1,
			user2,
			user3,
			user4
 			) values (
                        $acct,
                        '$batnbr',
                        $cramt,
                        $curycramt,
                        $curydramt,
                        $curyeffdate,
                        '$curyid',
                        '$curymultdiv',
                        $curyrate,
                        '$curyratetype',
                        $dramt,
                        '$extrefnbr',
                        '$fiscyr',
                        '$id',
                        '$jrnltype',
                        $lineid,
                        $linenbr,
                        '$module',
                        $noteid,
                        '$origacct',
                        '$origsub',
                        $perent,
                        $perpost,
                        '$posted',
                        '$refnbr',
                        '$rlsed',
                        '$sub',
                        to_date('$trandate','mm/dd/yy'),
                        '$trandesc',
                        '$trantype',
                        '$user1',
                        '$user2',
                        $user3,
                        $user4
			)";

              	$statement = ora_parse($cursor, $sql);
	        if (!ora_exec($cursor)){
			fwrite($log_handle, $sql."\r\n");
		}
		ora_commit($conn);
	  
	}
	
  }
}

?>

