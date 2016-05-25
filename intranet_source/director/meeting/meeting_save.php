<?php
$username = $userdata['username'];
$user_email = $userdata['user_email'];

include("connect.php");
$data = "meeting_date";

  // open a connection to the database server
  $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
  $temp = 0;

   if (!$connection){
      printf("Could not open connection to database server.   Please go back to <a href=\"director_login.php\"> Director Login Page</a> and try again later!<br />");
      exit;
   }


$query ="select sub_type from ccd_user where email = '".$user_email."'";
$result = pg_query($connection, $query) or die("Error in query: $query. " .  pg_last_error($connection));
$rows = pg_num_rows($result);
 if ($rows >0){
     $row = pg_fetch_row($result, 0);
     $user_type = $row[0];
 }

//$user_type = $HTTP_COOKIE_VARS[user_sub_type];
//echo($user_type);
$mId = $HTTP_POST_VARS[mId];
/*
$daily_hires = $HTTP_POST_VARS[daily_hires];
$jobs_filled = $HTTP_POST_VARS[jobs_filled];
$veh_searched = $HTTP_POST_VARS[veh_searched];
$new_hires = $HTTP_POST_VARS[new_hires];
$resignations = $HTTP_POST_VARS[resignations];
$ads_run = $HTTP_POST_VARS[ads_run];
$accidents = $HTTP_POST_VARS[accidents];
$drug_test= $HTTP_POST_VARS[drug_test];
$bids = $HTTP_POST_VARS[bids];
$low_bid = $HTTP_POST_VARS[low_bid];
$accepted = $HTTP_POST_VARS[accepted];
$rejected = $HTTP_POST_VARS[rejected];
$num_of_scans = $HTTP_POST_VARS[num_of_scans];
$num_of_checkers = $HTTP_POST_VARS[num_of_checkers];
$network_uptime = $HTTP_POST_VARS[network_uptime];
$crane_downtime = $HTTP_POST_VARS[crane_downtime];
$ave_truck_times = $HTTP_POST_VARS[ave_truck_times];
$ave_num_of_trucks = $HTTP_POST_VARS[ave_num_of_trucks];
$accident_desc = $HTTP_POST_VARS[accident_desc];
*/
  $exec_agenda_row = $HTTP_POST_VARS[exec_agenda_row];
  $admin_agenda_row = $HTTP_POST_VARS[admin_agenda_row];
  $op_agenda_row = $HTTP_POST_VARS[op_agenda_row];
  $mk_agenda_row = $HTTP_POST_VARS[mk_agenda_row];
  $ts_agenda_row = $HTTP_POST_VARS[ts_agenda_row];
  $hr_agenda_row = $HTTP_POST_VARS[hr_agenda_row];
  $fina_agenda_row = $HTTP_POST_VARS[fina_agenda_row];
  $eng_agenda_row = $HTTP_POST_VARS[eng_agenda_row];

/*
$data = "meeting_detail";

$query = "select *  from $data where meeting_id = $mId";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));
$rows = pg_num_rows($result);

if($rows >0){

    if($user_type =="EXEC") {	
	$query ="update $data set daily_hires ='".$daily_hires."', jobs_filled='".$jobs_filled."', veh_searched='".$veh_searched.
		"', new_hires = '".$new_hires."', resignations = '".$resignations."', ads_run ='".$ads_run.
		"', accidents = '".$accidents."', drug_test ='".$drug_test."', bids = '".$bids.
		"', low_bid = '".$low_bid."', accepted = '".$accepted."', rejected = '".$rejected.
		"', num_of_scans = '".$num_of_scans."', num_of_checkers ='".$num_of_checkers."', network_uptime = '".$network_uptime.
		"', crane_downtime ='".$crane_downtime."', average_truck_times = '".$ave_truck_times."', average_num_of_trucks ='".$ave_num_of_trucks.
		"', description = '".$accident_desc."' where meeting_id = $mId";

    }else if ($user_type =="HR"){
         $query ="update $data set daily_hires ='".$daily_hires."', jobs_filled='".$jobs_filled."', veh_searched='".$veh_searched.
                "', new_hires = '".$new_hires."', resignations = '".$resignations."', ads_run ='".$ads_run.
                "', accidents = '".$accidents."', drug_test ='".$drug_test.
                "', low_bid = '".$low_bid."', accepted = '".$accepted."', rejected = '".$rejected.
                "', description = '".$accident_desc."' where meeting_id = $mId";

    }else if ($user_type=="TECH"){
         $query ="update $data set drug_test ='".$drug_test."', low_bid = '".$low_bid."', accepted = '".$accepted."', rejected = '".$rejected.
                "', num_of_scans = '".$num_of_scans."', num_of_checkers ='".$num_of_checkers."', network_uptime = '".$network_uptime.
                "' where meeting_id = $mId";
    }else if ($user_type=="OPS"){
        $query ="update $data set drug_test ='".$drug_test."', low_bid = '".$low_bid."', accepted = '".$accepted."', rejected = '".$rejected.
                "', crane_downtime ='".$crane_downtime."', average_truck_times = '".$ave_truck_times."', average_num_of_trucks ='".$ave_num_of_trucks.
                "' where meeting_id = $mId";
    }else if ($user_type=="ENG"){
        $query ="update $data set drug_test ='".$drug_test."', bids = '".$bids.
                "', low_bid = '".$low_bid."', accepted = '".$accepted."', rejected = '".$rejected.
                "' where meeting_id = $mId";
    }else{
        $query ="update $data set drug_test ='".$drug_test."', low_bid = '".$low_bid."', accepted = '".$accepted."', rejected = '".$rejected.
                "' where meeting_id = $mId";

    }
	
}else {
	$query ="insert into $data (meeting_id, daily_hires, jobs_filled, veh_searched, new_hires, resignations, ads_run, accidents,drug_test, bids, low_bid,
		accepted, rejected, num_of_scans, num_of_checkers, network_uptime, crane_downtime, average_truck_times, average_num_of_trucks, description )
		values (".$mId.",'".$daily_hires."','".$jobs_filled."','".$veh_searched."','".$new_hires."','".$resignations.
		"','".$ads_run."','".$accidents."','".$drug_test."','".$bids."','".$low_bid."','".$accepted."','".$rejected."','".$num_of_scans.
		"','".$num_of_checkers."','".$network_uptime."','".$crane_downtime."','".$ave_truck_times."','".$ave_num_of_trucks."','".$accident_desc."')";
}
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));


if ($user_type =="EXEC" || $user_type =="MRKT"){
$data = "mk_visit";
$query = "delete from $data where meeting_id=$mId";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<3; $i++){
	$cust[$i] = $HTTP_POST_VARS["customer".$i];
	$vDate[$i] = $HTTP_POST_VARS["v_date".$i];
	$pur[$i] = $HTTP_POST_VARS["purpose".$i];
	$tScheduled[$i] = $HTTP_POST_VARS["t_scheduled".$i];
	$tDate[$i]= $HTTP_POST_VARS["t_date".$i];
	$cont[$i] = $HTTP_POST_VARS["contracts".$i];

	if ($cust[$i] !="" || $vDate[$i] !="" || $pur[$i] !="" || $tScheduled[$i] !="" || $tDate[$i] !="" || $cont[$i] !="") {
		$query=	"insert into $data (meeting_id, customers, v_date, purpose, tours_scheduled, t_date, contracts) values (
			".$mId.",'".$cust[$i]."','".$vDate[$i]."','".$pur[$i]."','".$tScheduled[$i]."','".$tDate[$i]."','".$cont[$i]."')";
		$result = pg_query($connection, $query) or
		          die("Error in query: $query. " .  pg_last_error($connection));

	}
}

$data = "mk_quotes";
$query = "delete from $data where meeting_id=$mId";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<3; $i++){
        $quotes[$i] = $HTTP_POST_VARS["rate_quotes".$i];
        $des[$i] = $HTTP_POST_VARS["desc".$i];
        $pBus[$i] = $HTTP_POST_VARS["p_business".$i];
        $dBus[$i] = $HTTP_POST_VARS["d_business".$i];
        
        if ($quotes[$i] !="" || $des[$i] !="" || $pBus[$i] !="" || $dBus[$i] !="" ) {
                $query= "insert into $data (meeting_id, rate_quotes,description,potential_business, departing_business) values (
                        ".$mId.",'".$quotes[$i]."','".$des[$i]."','".$pBus[$i]."','".$dBus[$i]."')";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));

        }
}
}
*/

// edited Adam Walter, March 4, 2008.  Although it appears the problem is caused solely by human error,
// I am modifying the following 8 blocks to prevent duplicates, by seeing if a given combination
// is already in the table.
if($user_type =="EXEC" || $user_type =="ADMIN"){
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'EXEC'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<$exec_agenda_row; $i++){
        $exec_ag[$i] = $HTTP_POST_VARS["exec_agenda".$i];

        if ($exec_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($exec_ag[$i])."' and dept = 'EXEC'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($exec_ag[$i])."','EXEC')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}

if($user_type =="EXEC" || $user_type=="ADMIN"){ 
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'ADMIN'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<$admin_agenda_row; $i++){
        $admin_ag[$i] = $HTTP_POST_VARS["admin_agenda".$i];

        if ($admin_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($admin_ag[$i])."' and dept = 'ADMIN'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($admin_ag[$i])."','ADMIN')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}

if($user_type =="EXEC" || $user_type=="OPS"){
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'OPS'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));


for($i=0; $i<$op_agenda_row; $i++){
        $op_ag[$i] = $HTTP_POST_VARS["op_agenda".$i];

        if ($op_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($op_ag[$i])."' and dept = 'OPS'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($op_ag[$i])."','OPS')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}

if($user_type =="EXEC" || $user_type=="MRKT"){
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'MRKT'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<$mk_agenda_row; $i++){
        $mk_ag[$i] = $HTTP_POST_VARS["mk_agenda".$i];

        if ($mk_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($mk_ag[$i])."' and dept = 'MRKT'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($mk_ag[$i])."','MRKT')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}

if($user_type =="EXEC" || $user_type=="TECH"){
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'TECH'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<$ts_agenda_row; $i++){
        $ts_ag[$i] = $HTTP_POST_VARS["ts_agenda".$i];

        if ($ts_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($ts_ag[$i])."' and dept = 'TECH'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($ts_ag[$i])."','TECH')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}


if($user_type =="EXEC" || $user_type=="HR"){
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'HR'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));

for($i=0; $i<$hr_agenda_row; $i++){
        $hr_ag[$i] = $HTTP_POST_VARS["hr_agenda".$i];

        if ($hr_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($hr_ag[$i])."' and dept = 'HR'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($hr_ag[$i])."','HR')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}

if($user_type =="EXEC" || $user_type=="FINA"){

$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'FINA'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));


for($i=0; $i<$fina_agenda_row; $i++){
        $fina_ag[$i] = $HTTP_POST_VARS["fina_agenda".$i];

        if ($fina_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($fina_ag[$i])."' and dept = 'FINA'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($fina_ag[$i])."','FINA')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}

if($user_type =="EXEC" || $user_type=="ENG"){
$data = "meeting_agenda";
$query = "delete from $data where meeting_id=$mId and dept = 'ENG'";
$result = pg_query($connection, $query) or
          die("Error in query: $query. " .  pg_last_error($connection));


for($i=0; $i<$eng_agenda_row; $i++){
        $eng_ag[$i] = $HTTP_POST_VARS["eng_agenda".$i];

        if ($eng_ag[$i] !="" ) {
				$query = "select * from $data where meeting_id = ".$mId." and items = '".trim($eng_ag[$i])."' and dept = 'ENG'";
                $result = pg_query($connection, $query) or
                          die("Error in query: $query. " .  pg_last_error($connection));
				$row_count = pg_num_rows($result);
				if($row_count == 0){
					$query= "insert into $data (meeting_id, items, dept) values (
							".$mId.",'".trim($eng_ag[$i])."','ENG')";
					$result = pg_query($connection, $query) or
							  die("Error in query: $query. " .  pg_last_error($connection));
				}
        }
}
}
?>
