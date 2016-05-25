<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  $user = $userdata['username'];

  $date = $HTTP_POST_VARS['date'];
  $system = $HTTP_POST_VARS['system'];
  $order_num = $HTTP_POST_VARS['order_num'];
  $sup = $HTTP_POST_VARS['sup'];
  $orig_sup = $HTTP_POST_VARS['orig_sup'];

  $view = $HTTP_POST_VARS['view'];
  $view_sup = $HTTP_POST_VARS['view_sup'];

  if ($system =="CCDS"){
        include("connect.php");
        $ccd_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
        if(!$ccd_conn){
                die("Could not open connection to PostgreSQL database server");
        }
  }else if ($system =="BNI"){
        $conn = ora_logon("SAG_OWNER@BNI", "SAG");
        if($conn < 1){
                printf("Error logging on to the Oracle Server: ");
                printf(ora_errorcode($conn));
                printf("Please try later!");
                exit;
        }
        $cursor = ora_open($conn);
  }else if ($system =="RF"){
        $conn = ora_logon("SAG_OWNER@RF", "OWNER");
        if($conn < 1){
                printf("Error logging on to the Oracle Server: ");
                printf(ora_errorcode($conn));
                printf("Please try later!");
                exit;
        }
        $cursor = ora_open($conn);
  }else if ($system =="PAPER"){
        $conn = ora_logon("PAPINET@RF", "OWNER");
        if($conn < 1){
                printf("Error logging on to the Oracle Server: ");
                printf(ora_errorcode($conn));
                printf("Please try later!");
                exit;
        }
        $cursor = ora_open($conn);
  }

  for ($i=0; $i<count($order_num); $i++){
	if ($orig_sup[$i] <> $sup[$i]){ //supervisor changed
		if ($orig_sup[$i]==""){ // insert
			if ($system =="CCDS"){
				$sql = "insert into order_supervisor (date_of_activity, order_num, user_id)
					values ('$date[$i]','$order_num[$i]','$sup[$i]')";
				$result = pg_query($ccd_conn, $sql) or 
					  die("Error in query: $sql. " . pg_last_error($ccd_conn));
			}else{
				$sql = "insert into order_supervisor (date_of_activity, order_num, user_id)
                                        values (to_date('$date[$i]','mm/dd/yyyy'),'$order_num[$i]','$sup[$i]')";
				$statement = ora_parse($cursor, $sql);
        			ora_exec($cursor);
			}
		}else if ($sup[$i] ==""){//delete
                        if ($system =="CCDS"){
                                $sql = "delete from order_supervisor 
					where date_of_activity = '$date[$i]' and order_num = '$order_num[$i]'";
                                $result = pg_query($ccd_conn, $sql) or
                                          die("Error in query: $sql. " . pg_last_error($ccd_conn));
                        }else{
                                $sql = "delete from order_supervisor
                                        where date_of_activity = to_date('$date[$i]','mm/dd/yyyy') and 
					order_num = '$order_num[$i]'";
                                $statement = ora_parse($cursor, $sql);
                                ora_exec($cursor);
                        }
		}else{ //update
			if ($system =="CCDS"){
                                $sql = "update order_supervisor set user_id = '$sup[$i]'
                                        where date_of_activity = '$date[$i]' and order_num = '$order_num[$i]'";
                                $result = pg_query($ccd_conn, $sql) or
                                          die("Error in query: $sql. " . pg_last_error($ccd_conn));
                        }else{
                                $sql = "update order_supervisor set user_id = '$sup[$i]'
                                        where date_of_activity = to_date('$date[$i]','mm/dd/yyyy') and
                                        order_num = '$order_num[$i]'";
                                $statement = ora_parse($cursor, $sql);
                                ora_exec($cursor);
                        }

		}
	}
  }
 
  header("location: index.php?system=$system&view=$view&view_sup=$view_sup");
?>
