<? 
  $basefile = "temp_s17.txt"; 

  // http vars 
  $user = "rwang"; 
  $pass = "6rel55ax"; 
  $url = "https://pow.icetec.biz/apps/getTemps.cgi"; 
  $url = "https://pow.icetec.biz/apps/quickview.cgi";

  // Build the command 
  $cmd = "/usr/bin/wget --http-user=" . $user . " --http-passwd=" . $pass . " -O " . $basefile . " " . $url; 

  echo "$cmd"; 
  // Run the command 
  system("$cmd"); 

  $today = date('m/d/Y');
  $timeStamp = date('m/d/Y H:i');
 
  //get connect
  include("connect_data_warehouse.php");
 
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $lcs_conn = ora_logon("LABOR@LCS", "LABOR");
  if ($lcs_conn < 1) {
	printf("Error logging on to the LCS Oracle Server: ");
	printf(ora_errorcode($lcs_conn));
	printf("Please try later!");
	exit;
  }
  $lcs_cursor = ora_open($lcs_conn);
/*
  $bni_conn = ora_logon("SAG_OWNER@BNI","SAG");
  if ($bni_conn < 1) {
	printf("Error logging on to the BNI Oracle Server: ");
	printf(ora_errorcode($bni_conn));
	printf("Please try later!");
	exit;
  }
  $bni_cursor = ora_open($bni_conn);
*/
  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if ($rf_conn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	printf(ora_errorcode($rf_conn));
	printf("Please try later!");
	exit;
  }
  $rf_cursor = ora_open($rf_conn);

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
			if ($avg_temp <>"")
				$avg_temp = number_format($avg_temp, 2, '.','');

			if ($location <>""){//insert into database
				$sql = "insert into warehouse_temperature (date, location, temperature, setpoint, avg_temp) values ('$timeStamp','$location','$temp','$setpoint','$avg_temp')";
				$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
				
				//insert into RF
				$sql = "insert into warehouse_temperature (run_date, location, temperature, setpoint) values (to_date('$timeStamp','mm/dd/yyyy hh24:mi'),'$location','$temp','$setpoint')";
				$statement = ora_parse($rf_cursor, $sql);
  				ora_exec($rf_cursor);

			}
		}
	}
	fclose($handle);
	unlink($basefile);
  }


  //populate request
/*
  $sql = "select r.whse, r.box, r.effective, r.new_set_point, r.product, r.user_name from temperature_req r, (select t.whse, t.box, t.effective, max(t.req_date) as req_date from temperature_req t, (select whse, box, max(effective) as effective_date from temperature_req where mail_sent = 'Y' and effective <= sysdate group by whse, box) m1 where t.whse=m1.whse and t.box = m1.box and t.effective = m1.effective_date group by t.whse, t.box, t.effective)  m where  r.whse = m.whse and r.box= m.box and r.effective = m.effective and r.req_date = m.req_date";

  $statement = ora_parse($lcs_cursor, $sql);
  ora_exec($lcs_cursor);
  while(ora_fetch($lcs_cursor)){
  	$whse = ora_getcolumn($lcs_cursor, 0);
	$box = ora_getcolumn($lcs_cursor, 1);
	$effective_date = ora_getcolumn($lcs_cursor, 2);
	$set_point = ora_getcolumn($lcs_cursor, 3);
	$commodity = ora_getcolumn($lcs_cursor, 4);
	$user = ora_getcolumn($lcs_cursor, 5);

	$sql = "insert into temperature_req (run_date, whse, box, effective_date, set_point, commodity, user_name) values ('$timeStamp','$whse','$box','$effective_date','$set_point','$commodity','$user')";
        $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. ".pg_last_error($pg_conn));
  }
*/

  $sql = "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name
          from temperature_req
          where effective <= sysdate and (expired is null or expired >= sysdate)
          order by req_id desc";
  $sql = "select whse, box, effective, expired, new_set_point, actual_set_point, product, user_name, comments
          from temperature_req
          where (whse, box, req_id) in 
	  (select whse, box, max(req_id) from temperature_req  
	  where effective <= sysdate and (expired is null or expired >= sysdate)
          group by whse, box)";
  $sql = "select whse, box, to_char(effective,'mm/dd/yyyy hh24:mi'), to_char(expired,'mm/dd/yyyy hh24:mi'), 
	  new_set_point, actual_set_point, product, user_name, comments, req_id, low_temp, high_temp
          from temperature_req
          where (whse, box, req_id) in
          (select whse, box, max(req_id) from temperature_req
          where effective <= sysdate 
          group by whse, box)";
  $sql = "select whse, box, to_char(effective,'mm/dd/yyyy hh24:mi'), to_char(expired,'mm/dd/yyyy hh24:mi'),
          new_set_point, actual_set_point, product, user_name, r.comments, req_id, low_temp, high_temp,s.comments
          from temperature_req r, warehouse_status s
          where (whse, box, req_id) in
          (select whse, box, max(req_id) from temperature_req
          where effective <= sysdate
          group by whse, box) and warehouse_box = whse||'-'||box";

  $statement = ora_parse($lcs_cursor, $sql);
  ora_exec($lcs_cursor);

  while(ora_fetch($lcs_cursor)){
        $whse = ora_getcolumn($lcs_cursor, 0);
        $box = ora_getcolumn($lcs_cursor, 1);
        $effective_date = ora_getcolumn($lcs_cursor, 2);
        $expiration_date = ora_getcolumn($lcs_cursor, 3);
        $set_point = ora_getcolumn($lcs_cursor, 4);
        $actual_set_point = ora_getcolumn($lcs_cursor, 5);
        $commodity = ora_getcolumn($lcs_cursor, 6);
        $user = ora_getcolumn($lcs_cursor, 7);
	$comments = ora_getcolumn($lcs_cursor, 8);
        $rId = ora_getcolumn($lcs_cursor, 9);
	$low = ora_getcolumn($lcs_cursor, 10);
	$high = ora_getcolumn($lcs_cursor, 11);
        $status = ora_getcolumn($lcs_cursor, 12);

 	if ($expiration_date ==""){
		$expiration_date = "null";
	}else{
		$expiration_date = "'".$expiration_date."'";
	}
        $sql = "insert into temperature_req (run_date, req_id, whse, box, effective_date, expiration_date, set_point, low_temp, high_temp, actual_set_point, commodity, user_name,comments,status) values ('$timeStamp', $rId, '$whse','$box','$effective_date',$expiration_date,'$set_point','$low','$high','$actual_set_point','$commodity','$user','$comments','$status')";

        $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. ".pg_last_error($pg_conn));
  }

  //get commodity
  $sql = "select whse, box, commodity_name, sum(qty) from ((select w.whse, b.box, commodity_name, sum(subst_vals(stacking, 'f', qty, round(qty/2,0))) as qty from cargo_detail d left outer join (select distinct whse from warehouse_location) w on d.location like w.whse||'%' or d.location like 'WING '||w.whse||'%' left outer join warehouse_location b on d.location like b.whse||b.box||'%' or d.location like 'WING '||b.whse||b.box||'%' where d.run_date = '$today' and date_received < run_date and cargo_system = 'CCDS' group by w.whse, b.box, commodity_name) union all (select w.whse, b.box, commodity_name, sum(qty) from cargo_detail d left outer join (select distinct whse from warehouse_location) w on d.location like w.whse||'%' or d.location like 'WING '||w.whse||'%' left outer join warehouse_location b on d.location like b.whse||b.box||'%' or d.location like 'WING '||b.whse||b.box||'%' where d.run_date = '$today' and date_received < run_date and cargo_system = 'BNI' group by w.whse, b.box, commodity_name) union all (select w.whse, b.box, commodity_name, float8(count(*)) as qty from cargo_detail d left outer join (select distinct whse from warehouse_location) w on d.location like w.whse||'%' or d.location like 'WING '||w.whse||'%' left outer join warehouse_location b on d.location like b.whse||b.box||'%' or d.location like 'WING '||b.whse||b.box||'%' where d.run_date = '$today' and date_received < run_date and cargo_system = 'RF' group by w.whse, b.box, commodity_name)) a where whse <>'' group by whse, box, commodity_name order by whse, box, sum(qty) desc";
  $result = pg_query($pg_conn, $sql) or die("Error in query: $sql.".pg_last_error($pg_conn));
  $rows = pg_num_rows($result);
  $pre_whse = "";
  $pre_box = "";
  for ($i = 0; $i < $rows; $i++){
	$row = pg_fetch_row($result, $i);

	$whse  = $row[0];
	$box = $row[1];
        $comm = $row[2];
        $qty = $row[3];
        if ($whse <> $pre_whse || $box <> $pre_box) {
		$pre_whse = $whse;
                $pre_box = $box;
		$pos = strpos($comm, "-");
		if ($pos > 0){
			$comm = substr($comm, $pos + 1);
		}
		if ($box =="") $box = "Unknown";
		$location = $whse.$box;
		//insert into warehouse_commodity
		$sql = "insert into warehouse_commodity (run_date, location, commodity, qty) values ('$timeStamp','$location','$comm', $qty)";
		$res = pg_query($pg_conn, $sql) or die("Error in query: $sql. ".pg_last_error($pg_conn));
	}
  }

?>

