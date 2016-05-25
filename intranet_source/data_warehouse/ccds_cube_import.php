<? 
  $basefile = "0217_ccds.csv"; 

  
  //get connect
  include("connect_data_warehouse.php");
$host = "dspc-s17";
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }


  //parse file
  $pre_comm = "";
  $pre_cust = "";
  $pre_vess = "";
  $pre_mark = "";
  if ($handle = fopen ($basefile, "r")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);
		list($run_date, $sys, $trucked_in, $comm, $cust, $vess, $mark, $id, $location, $unit, $received, $storage_end, $qty, $insp) = split(",",$fLine); 
		if ($comm ==""){
			$comm = $pre_comm;
		}else{
			$pre_comm = $comm;
		}

                if ($cust ==""){
                        $cust = $pre_cust;
                }else{
                        $pre_cust = $cust;
                }

                if ($vess ==""){
                        $vess = $pre_vess;
                }else{
                        $pre_vess = $vess;
                }
                if ($mark ==""){
                        $mark = $pre_mark;
                }else{
                        $pre_mark = $mark;
                }

		$pos = strpos($comm, "-");
		$comm_code = substr($comm, 0, $pos);

		$pos = strpos($cust, "-");
                $cust_id = substr($cust, 0, $pos);

                $pos = strpos($vess, "-");
                $lr_num = substr($vess, 0, $pos);

		if (trim($storage_end) =="") {
			$storage_end = "null";
		}else{
			$storage_end = "'".trim($storage_end)."'";
		}
		if (trim($insp) =="Y"){
			$insp = "t";
			$qty = 2 * $qty;
		}else if (trim($insp) =="N"){
			$insp = "f";
		}

		$sql = "insert into cargo_detail (run_date, location, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, lr_num, storage_end, commodity_name, vessel_name, customer_name, cargo_mark, trucked_in, stacking) values ('$run_date', '$location', '$comm_code', $qty, '$unit', '$id', '$cust_id', '$sys', '$received','$lr_num',$storage_end,'$comm','$vess','$cust','$mark','$trucked_in','$insp')";

		
		$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
	}
	//	list($case_nbr,$cDate, $user, $priority, $deadline, $target, $status, $reviewed, $assigned)=split(",", $fLine 9);
  }

?>

