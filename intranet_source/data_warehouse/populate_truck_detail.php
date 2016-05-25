<? 
  $basefile = "/web/web_pages/upload/truck.csv"; 

  

  $lcs_conn = ora_logon("LABOR@LCS", "LABOR");
  if ($lcs_conn < 1) {
	printf("Error logging on to the LCS Oracle Server: ");
	printf(ora_errorcode($lcs_conn));
	printf("Please try later!");
	exit;
  }
  $cursor = ora_open($lcs_conn);
  $cursor2 = ora_open($lcs_conn);

  //parse file
  if ($handle = fopen ($basefile, "r")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);
		list($date, $check_in, $start_load, $end_load, $check_out)=split(",", $fLine);
		$date = trim($date);
		$start_load = trim($start_load);
		$end_load = trim($end_load);
		$check_in = trim($check_in);
		$check_out = trim($check_out);
			
		$sql = "insert into truck_time (truck_in_date, check_in_time, start_load_time, end_load_time, check_out_time) values (to_date('$date','mm/dd/yyyy'), to_date('$date $check_in','mm/dd/yyyy hh24:mi'),to_date('$date $start_load','mm/dd/yyyy hh24:mi'),to_date('$date $end_load','mm/dd/yyyy hh24:mi'),to_date('$date $check_out','mm/dd/yyyy hh24:mi'))";

 		$statement = ora_parse($cursor, $sql);
   		ora_exec($cursor);
	}
	fclose($handle);
//	unlink($basefile);
  }

   $sql = "select truck_in_date, to_char(check_in_time, 'hh24'), count(*) from truck_time group by truck_in_date, to_char(check_in_time, 'hh24')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
	$tDate = ora_getcolumn($cursor, 0);
        $h = ora_getcolumn($cursor, 1);
        $count = ora_getcolumn($cursor, 2);
   
        $sql = "insert into truck_in_time_pigeon_point (truck_in_date, type,D, H$h) values ('$tDate', 'CHECK_IN', $count, $count)";
	$statement = ora_parse($cursor2, $sql);
   	ora_exec($cursor2);
   }



   $sql = "select truck_in_date, to_char(check_out_time, 'hh24'), count(*) from truck_time group by truck_in_date, to_char(check_out_time, 'hh24')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
        $tDate = ora_getcolumn($cursor, 0);
        $h = ora_getcolumn($cursor, 1);
        $count = ora_getcolumn($cursor, 2);

        $sql = "insert into truck_in_time_pigeon_point (truck_in_date, type, D, H$h) values ('$tDate', 'CHECK_OUT', $count, $count)";
        $statement = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
   }

   $sql = "select truck_in_date, to_char(start_load_time, 'hh24'), count(*) from truck_time group by truck_in_date, to_char(start_load_time, 'hh24')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
        $tDate = ora_getcolumn($cursor, 0);
        $h = ora_getcolumn($cursor, 1);
        $count = ora_getcolumn($cursor, 2);

        $sql = "insert into truck_in_time_pigeon_point (truck_in_date, type, D, H$h) values ('$tDate', 'START_LOAD', $count, $count)";
        $statement = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
   }

   $sql = "select truck_in_date, to_char(end_load_time, 'hh24'), count(*) from truck_time group by truck_in_date, to_char(end_load_time, 'hh24')";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
        $tDate = ora_getcolumn($cursor, 0);
        $h = ora_getcolumn($cursor, 1);
        $count = ora_getcolumn($cursor, 2);

        $sql = "insert into truck_in_time_pigeon_point (truck_in_date, type, D, H$h) values ('$tDate', 'END_LOAD', $count, $count)";
        $statement = ora_parse($cursor2, $sql);
        ora_exec($cursor2);
   }

?>

