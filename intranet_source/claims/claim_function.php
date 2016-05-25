<?
include_once ("utility.php");
// try printing it with list($customer_id, $customer_name) = each($customer)
function getAllCustomerList($bni_cursor){
   $stmt = "select customer_id, customer_name from customer_profile where customer_id >= 3 and customer_status = 'ACTIVE' order by customer_name";
  ora_parse($bni_cursor, $stmt);
  ora_exec($bni_cursor);
  $customer = array();
  while (ora_fetch($bni_cursor)){
    $temp_customer_id = ora_getcolumn($bni_cursor, 0);
    $temp_customer_name = ora_getcolumn($bni_cursor, 1);
    if (strpos($temp_customer_name, $temp_customer_id) !== FALSE){
	$new_customer_name = substr($temp_customer_name, strlen($temp_customer_id)+1);
    }
    else{
      $new_customer_name = $temp_customer_name;
    }
    
    $customer[$temp_customer_id] = trim($new_customer_name);
  }
  asort($customer);
  return $customer;
}

// try printing it with list($customer_id, $customer_name) = each($customer)
function getClaimCustomerList($bni_cursor, $system=""){
  if ($system <>""){
	$stmt = "select distinct c.customer_id, c.customer_name 
		from customer_profile c, claim_customer_list l
		where c.customer_id = l.customer_id and system = '$system' and customer_status = 'ACTIVE'
		order by customer_name";
  }else{
        $stmt = "select distinct c.customer_id, c.customer_name
                from customer_profile c, claim_customer_list l
                where c.customer_id = l.customer_id and customer_status = 'ACTIVE'
                order by customer_name";
  }
  ora_parse($bni_cursor, $stmt);
  ora_exec($bni_cursor);
  $customer = array();
  while (ora_fetch($bni_cursor)){
    $temp_customer_id = ora_getcolumn($bni_cursor, 0);
    $temp_customer_name = ora_getcolumn($bni_cursor, 1);
    if (strpos($temp_customer_name, $temp_customer_id) !== FALSE){
        $new_customer_name = substr($temp_customer_name, strlen($temp_customer_id)+1);
    }
    else{
      $new_customer_name = $temp_customer_name;
    }
    $customer[$temp_customer_id] = trim($new_customer_name);
  }
  asort($customer);
  return $customer;
}

function addClaimCustomer($bni_cursor, $cust, $system){
  $count = count($cust);
  for($i=0; $i < $count; $i++){
      list($cust_id[$i], $cust_name[$i]) = split(",", $cust[$i]);

      $sql = "insert into claim_customer_list (customer_id, system)
              values ($cust_id[$i],'$system')";
      ora_parse($bni_cursor, $sql);
      ora_exec($bni_cursor);	

  }
}

function removeClaimCustomer($bni_cursor, $cust, $system){
  $count = count($cust);
  for($i=0; $i < $count; $i++){
      list($cust_id[$i], $cust_name[$i]) = split(",", $cust[$i]);

      $sql = "delete from  claim_customer_list where system= '$system' and customer_id = $cust_id[$i]";
      ora_parse($bni_cursor, $sql);
      ora_exec($bni_cursor);
  }
}

// try printing it with list($customer_id, $customer_name) = each($customer)
function getRFSeasonCustomerList($bni_cursor, $season, $cargo_type, $LR="ship"){
	if($LR == "truck"){
		$table = "claim_season_customer_rf_truck";
	} else {
		$table = "claim_season_customer_rf";
	}
  $sql = "select customer_id, customer_name
           from $table where season='$season' and claim_cargo_type = '$cargo_type'
          order by customer_id";
  ora_parse($bni_cursor, $sql);
  ora_exec($bni_cursor);
  $customer = array();
  while (ora_fetch($bni_cursor)){
    $temp_customer_id = ora_getcolumn($bni_cursor, 0);
    $temp_customer_name = ora_getcolumn($bni_cursor, 1);
    if (strpos($temp_customer_name, $temp_customer_id) !== FALSE){
        $new_customer_name = substr($temp_customer_name, strlen($temp_customer_id)+1);
    }
    else{
      $new_customer_name = $temp_customer_name;
    }
    $customer[$temp_customer_id] = trim($new_customer_name);
  }
  asort($customer);
  return $customer;
}

function getRFSeason($bni_cursor){
  $sql = "select distinct season from claim_header_rf order by season";
  ora_parse($bni_cursor, $sql);
  ora_exec($bni_cursor);
  $season = array();
  $i = 0;
  while (ora_fetch($bni_cursor)){
     $season[$i] = ora_getcolumn($bni_cursor, 0);
     $i++;
  }
  return $season;
}

function addRFSeasonCustomer($bni_cursor, $season, $cust, $cargo_type, $LR="ship"){
	if($LR == "truck"){
		$table = "claim_season_customer_rf_truck";
	} else {
		$table = "claim_season_customer_rf";
	}
  $count = count($cust);
  for($i=0; $i < $count; $i++){
      list($cust_id[$i], $cust_name[$i]) = split(",", $cust[$i]);
      $cust_name[$i] = OraSafeString($cust_name[$i]);
      $sql = "insert into $table (season, customer_id, customer_name, claim_cargo_type)
              values ('$season', $cust_id[$i], '$cust_name[$i]', '$cargo_type')";
      ora_parse($bni_cursor, $sql);
      ora_exec($bni_cursor);
  }
}

function removeRFSeasonCustomer($bni_cursor, $season, $cust, $cargo_type, $LR="ship"){
	if($LR == "truck"){
		$table = "claim_season_customer_rf_truck";
	} else {
		$table = "claim_season_customer_rf";
	}
  $count = count($cust);
  for($i=0; $i < $count; $i++){
      $sql = "delete from $table where season = '$season' and customer_id = $cust[$i] and claim_cargo_type = '$cargo_type'";
      ora_parse($bni_cursor, $sql);
      ora_exec($bni_cursor);
  }

}

// try printing it with list($customer_id, $customer_name) = each($customer)
function getSeasonCustomerList($pg_conn, $season){
  $sql = "select customer_id, customer_name 
	   from claim_season_customer where season='$season' 
	  order by customer_id";
  $result = pg_query($pg_conn, $sql) or
                die("Error in query: $sql. " .  pg_last_error($pg_conn));
  $rows = pg_num_rows($result);
  $customer = array();
  for($i = 0; $i < $rows; $i++){
     	$row=pg_fetch_row($result, $i);
       
    	$customer_id = $row[0];
        $customer_name = $row[1];
    	$customer[$customer_id] = $customer_name;
  }
  asort($customer);
  return $customer;
}

function getSeason($pg_conn){
  $sql = "select season from claim_season order by id";
  $result = pg_query($pg_conn, $sql) or
		die("Error in query: $sql. " . pg_last_error($pg_conn));
  $rows =  pg_num_rows($result);
  $season = array();
  for($i = 0; $i < $rows; $i++){
	$row = pg_fetch_row($result, $i);
	$season[$i] = $row[0];
  }
  return $season;
}

function addSeasonCustomer($pg_conn, $season, $cust){
  $count = count($cust);
  for($i=0; $i < $count; $i++){
      list($cust_id[$i], $cust_name[$i]) = split(",", $cust[$i]);
      $sql = "insert into claim_season_customer (season, customer_id, customer_name)
	      values ('$season', $cust_id[$i],'$cust_name[$i]')";
      pg_query($pg_conn, $sql) or
                die("Error in query: $sql. " . pg_last_error($pg_conn));

  }  
}

function removeSeasonCustomer($pg_conn, $season, $sCust){
  $count = count($sCust);
  for($i=0; $i < $count; $i++){
      $sql = "delete from  claim_season_customer where season = '$season' and customer_id = $sCust[$i]";

      pg_query($pg_conn, $sql) or
                die("Error in query: $sql. " . pg_last_error($pg_conn));

  }

}
?>
