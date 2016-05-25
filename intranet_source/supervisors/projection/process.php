<?
  $user_id = $HTTP_POST_VARS['user_id'];
  $date = $HTTP_POST_VARS['date'];

  $commodity = $HTTP_POST_VARS['commodity'];

  $comm = $HTTP_POST_VARS['comm'];
  $pref_comm = $HTTP_POST_VARS['pref_comm'];

  $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn_bni < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_bni = ora_open($conn_bni);

  $type = $HTTP_POST_VARS['type'];

  if ($type=="setDefault"){
	$strDate1 = "null";
	$strDate2 = " working_date is null";
  }else{
	$strDate1 = "to_date('$date','mm/dd/yyyy')";
	$strDate2 = " working_date = to_date('$date','mm/dd/yyyy') ";
  } 
  
  if ($HTTP_POST_VARS['save'] <>"" || $HTTP_POST_VARS['add'] <>""){
	$sql = "delete from pref_commodity
		where user_id='$user_id' and $strDate2 ";
	$statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

       	
	for ($i= 0; $i<count($commodity); $i++){
               	$sql = "insert into pref_commodity(user_id, working_date, commodity_code)
                       	values ('$user_id', $strDate1, '$commodity[$i]')";
               	$statement = ora_parse($cursor_bni, $sql);
               	ora_exec($cursor_bni);
	}

        $backhaul = $HTTP_POST_VARS['backhaul'];

	for($i = 0; $i < count($backhaul); $i++){
		$sql = "update pref_commodity set backhaul = 'Y'
			where user_id='$user_id' and $strDate2 and
                        commodity_code = '$backhaul[$i]'";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
	}

        $truckloading = $HTTP_POST_VARS['truckloading'];
        for($i = 0; $i < count($truckloading); $i++){
                $sql = "update pref_commodity set truckloading = 'Y'
                        where user_id='$user_id' and $strDate2 and
                        commodity_code = '$truckloading[$i]'";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }

        $container_handling = $HTTP_POST_VARS['container_handling'];
        for($i = 0; $i < count($container_handling); $i++){
                $sql = "update pref_commodity set container_handling = 'Y'
                        where user_id='$user_id' and $strDate2 and
                        commodity_code = '$container_handling[$i]'";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }

        $rail_car_handling = $HTTP_POST_VARS['rail_car_handling'];
        for($i = 0; $i < count($rail_car_handling); $i++){
                $sql = "update pref_commodity set rail_car_handling = 'Y'
                        where user_id='$user_id' and $strDate2 and
                        commodity_code = '$rail_car_handling[$i]'";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }

        $inspection = $HTTP_POST_VARS['inspection'];
        for($i = 0; $i < count($inspection); $i++){
                $sql = "update pref_commodity set inspection = 'Y'
                        where user_id='$user_id' and $strDate2 and
                        commodity_code = '$inspection[$i]'";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }

  }
  if ($HTTP_POST_VARS['add'] <>""){
        for ($i= 0; $i<count($comm); $i++){
                $sql = "insert into pref_commodity(user_id, working_date, commodity_code)
                        values ('$user_id', $strDate1, '$comm[$i]')";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
  }

  if ($HTTP_POST_VARS['remove'] <>""){
        for ($i=0; $i <count($pref_comm); $i++){
                $sql = "delete from pref_commodity
                        where user_id='$user_id' and $strDate2 and
                        commodity_code = '$pref_comm[$i]'";
                $statement = ora_parse($cursor_bni, $sql);
                ora_exec($cursor_bni);
        }
  }
  if ($type=="setDefault"){
  	header("location: index.php?date=$date&type=$type");
  }else{
	header("location: index.php?date=$date");
  }
?>
