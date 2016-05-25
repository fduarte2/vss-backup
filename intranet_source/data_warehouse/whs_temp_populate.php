<? 
  $basefile = "temp.txt"; 

  // http vars 
  $user = "rwang"; 
  $pass = "6rel55ax"; 
  $url = "https://pow.icetec.biz/apps/getTemps.cgi"; 

  // Build the command 
  $cmd = "/usr/bin/wget --http-user=" . $user . " --http-passwd=" . $pass . " -O " . $basefile . " " . $url; 

  echo "$cmd"; 
  // Run the command 
  system("$cmd"); 

  $today = date('m/d/Y');
  $timeStamp = date('m/d/Y H:i');
  
  //get connect
  include("connect.php");
  $db = "data_warehouse";
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

  $bni_conn = ora_logon("SAG_OWNER@BNI","SAG");
  if ($bni_conn < 1) {
	printf("Error logging on to the BNI Oracle Server: ");
	printf(ora_errorcode($bni_conn));
	printf("Please try later!");
	exit;
  }
  $bni_cursor = ora_open($bni_conn);

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
			list($location, $temp, $setpoint)=split(",", $fLine);
			$location = trim($location);
			$temp = trim($temp);
			$setpoint = trim($setpoint);
			
			if ($location <>""){//insert into database
				$sql = "insert into warehouse_temperature (date, location, temperature, setpoint) values ('$timeStamp','$location','$temp','$setpoint')";
				$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
			}
		}
	}
	fclose($handle);
	unlink($basefile);
  }


  //populate request
  $sql = "select r.whse, r.box, r.effective, r.new_set_point, r.product, r.user_name from temperature_req r, (select t.whse, t.box, t.effective, max(t.req_date) as req_date from temperature_req t, (select whse, box, max(to_date(effective, 'mm/dd/yyyy')) as effective_date from temperature_req where mail_sent = 'Y' and to_date(effective, 'mm/dd/yyyy') <= sysdate group by whse, box) m1 where t.whse=m1.whse and t.box = m1.box and to_date(t.effective,'mm/dd/yyyy') = m1.effective_date group by t.whse, t.box, t.effective)  m where  r.whse = m.whse and r.box= m.box and r.effective = m.effective and r.req_date = m.req_date";
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

  //get commodity
  $sql = "select a.location, a.commodity_code, sum(a.plt)  from ((select l.whse||l.box as location, d.commodity_code, sum(d.qty) as plt from cargo_detail d, warehouse_location l where d.run_date ='$today' and d.cargo_system ='CCDS' and l.box <>'Unknown' and d.location like l.whse||l.box||'%' group by l.whse||l.box, d.commodity_code)  union (select l.whse||l.box as location, d.commodity_code, sum(d.qty) as plt from cargo_detail d, warehouse_location l where d.run_date='$today' and cargo_system ='CCDS'  and l.box = 'Unknown' and d.location = l.whse group by l.whse||l.box, d.commodity_code) union (select l.whse||l.box as location, d.commodity_code, sum(d.qty) as plt from cargo_detail d, warehouse_location l where d.run_date ='$today' and d.cargo_system = 'BNI' and d.qty_unit <>'BIN' and l.box <>'Unknown' and d.location like  'WING '||l.whse||l.box||'%' group by l.whse||l.box, d.commodity_code)  union (select l.whse||l.box as location, d.commodity_code, sum(d.qty) as plt from cargo_detail d, warehouse_location l where d.run_date='$today' and cargo_system ='BNI' and d.qty_unit <>'BIN'  and l.box = 'Unknown' and d.location = 'WING '||l.whse group by l.whse||l.box, d.commodity_code) union  (select l.whse||l.box as location, d.commodity_code, sum(d.qty/4) as plt from cargo_detail d, warehouse_location l where d.run_date ='$today' and d.cargo_system ='BNI' and d.qty_unit = 'BIN' and l.box <>'Unknown' and d.location like  'WING '||l.whse||l.box||'%' group by l.whse||l.box, d.commodity_code)  union (select l.whse||l.box as location, d.commodity_code, sum(d.qty/4) as plt from cargo_detail d, warehouse_location l where d.run_date='$today' and cargo_system ='BNI' and d.qty_unit = 'BIN' and l.box = 'Unknown' and d.location = 'WING '||l.whse group by l.whse||l.box, d.commodity_code) union (select l.whse||l.box as location, d.commodity_code, float8(count(*)) as plt from cargo_detail d, warehouse_location l where d.run_date ='$today' and d.cargo_system ='RF' and l.box <>'Unknown' and d.location like l.whse||l.box||'%' group by l.whse||l.box, d.commodity_code) union (select l.whse||l.box as location, d.commodity_code, float8(count(*)) as plt from cargo_detail d, warehouse_location l where d.run_date = '$today' and d.cargo_system = 'RF' and l.box ='Unknown' and d.location = l.whse group by l.whse||l.box, d.commodity_code)) a group by a.location, a.commodity_code order by a.location, max(a.plt) desc";
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

