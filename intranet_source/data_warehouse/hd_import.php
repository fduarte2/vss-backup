<? 
  $basefile = "HelpDesk1.csv"; 

  
  //get connect
  include("connect.php");
  $host = "localhost";
  $db="ccds";
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }


  //parse file
  if ($handle = fopen ($basefile, "r")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);

		for ($i = 0; $i < 9; $i++){
			$pos = strpos($fLine, ",");
			$hd[$i] = substr($fLine, 0, $pos);
			$fLine = substr($fLine, $pos+1);
		}
		//$hd[9] = $fLine;
		$hd[9] = addslashes($fLine);
		$hd[9] = substr($hd[9], 0, 199);

		if ($hd[7] ==""){
			$hd[7] = "null";
		}else{
			$hd[7] = "'".$hd[7]."'";
		}
		$sql = "insert into ts_help_desk(case_nbr, date, user_name, priority, dead_line, target, status, reviewed, assigned_to, problem) values ('$hd[0]','$hd[1]','$hd[2]','$hd[3]','$hd[4]','$hd[5]','$hd[6]',$hd[7],'$hd[8]','$hd[9]')";
		
		$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
	}
	//	list($case_nbr,$cDate, $user, $priority, $deadline, $target, $status, $reviewed, $assigned)=split(",", $fLine 9);
  }

?>

