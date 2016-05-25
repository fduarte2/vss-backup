<?
  $path = "/web/web_pages/upload/"; 
  $basefile = "commodity.csv"; 

  
  //get connection
   $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn_bni < 1){
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   }
   $cursor_bni = ora_open($conn_bni);


  //parse file
  if ($handle = fopen ($path.$basefile, "r")){
	while (!feof ($handle)) {
          	$fLine = fgets($handle);
		list($comm, $desc, $itg, $truckloading, $backhaul, $terminal_service, $rail_car, $container, $stand_by) = split(",",$fLine); 
		if ($truckloading =="")
			$truckloading = "null";
		if ($backhaul =="")
			$backhaul = "null";
		if ($terminal_service =="")
			$terminal_service = "null";
		if ($rail_car =="")
			$rail_car = "null";
		if ($container =="")
			$container = "null";
		if ($stand_by =="")
			$stand_by = "null";

		$itg = trim($itg);
		$sql = "insert into commodity_itg (commodity_code, description, itg) values
			('$comm', '$desc','$itg')";
echo $sql;
		$statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);

//		$sql = "insert into itg_budget(itg, truckloading, backhaul, terminal_service, rail_car, container, stand_by)
//			values ('$itg', $truckloading, $backhaul, $terminal_service, $rail_car, $container, $stand_by)";
//		$statement = ora_parse($cursor_bni, $sql);
//                ora_exec($cursor_bni);		
	}
	//	list($case_nbr,$cDate, $user, $priority, $deadline, $target, $status, $reviewed, $assigned)=split(",", $fLine 9);
  }

?>

