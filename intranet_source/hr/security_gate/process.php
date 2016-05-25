<?
  // Start a POW session
  include("pow_session.php");
//  $username = $userdata['username'];

	$username = $HTTP_POST_VARS['submitter'];
  $timestamp = date('Y-m-d H:i:s');

  include("connect.php");
/*
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }
*/
	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		echo "Error logging on to the BNI Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$select_cursor = ora_open($conn);
	$mod_cursor = ora_open($conn);

  // Retrieve the POST Vars
  $num_visitors = $HTTP_POST_VARS["num_visitors"];
//  echo "Processing $num_visitors visitors";
//  $visitor_name = $HTTP_POST_VARS["visitor"];
  $comments = $HTTP_POST_VARS["notes"];
//  $start_date = date('Y-m-d', strtotime($HTTP_POST_VARS["day"]));
	$start_date = $HTTP_POST_VARS["day"];
  $start_time = $HTTP_POST_VARS["start_time"];
//  $start_date .= " $start_time:00:00";
  if($HTTP_POST_VARS["end_day"] != ""){
//    $end_date = date('Y-m-d', strtotime($HTTP_POST_VARS["end_day"]));
    $end_date = $HTTP_POST_VARS["end_day"];
  }
  else{
//    $end_date = date('Y-m-d', strtotime($HTTP_POST_VARS["day"]));
    $end_date = $HTTP_POST_VARS["day"];
  }

  for($x = 1; $x <= $num_visitors; $x++){
    $visitor_name = $HTTP_POST_VARS["visitor$x"];
//    $stmt = "select nextval('gate_request')";
//    $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
//    $row = pg_fetch_row($result, 0);
	$sql = "SELECT MAX(REQUEST_NUMBER) THE_REQ FROM SECURITY_GATE"; 
	ora_parse($select_cursor, $sql);
	ora_exec($select_cursor);
	ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $request_id = $select_row['THE_REQ'] + 1;
//    $request_id = $row[0];

	$sql = "INSERT INTO SECURITY_GATE (REQUEST_NUMBER, REQUESTOR, VISITOR_NAME, REQUEST_TIME, RESERVATION_DATE, END_DATE, COMMENTS)
			VALUES
				('".$request_id."',
				'".$username."',
				'".$visitor_name."',
				SYSDATE,
				TO_DATE('".$start_date." ".$start_time."', 'MM/DD/YYYY HH24'),
				TO_DATE('".$end_date."', 'MM/DD/YYYY'),
				'".$comments."')";
	ora_parse($mod_cursor, $sql);
	ora_exec($mod_cursor);

//    $stmt = "insert into security_gate (request_number, requestor, visitor_name, request_time, reservation_date, end_date, comments) values ('$request_id', '$username', '$visitor_name', '$timestamp', '$start_date', '$end_date', '$comments')";
    //echo "$stmt";
//    $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
  }
  header("Location: index.php");
  exit;
?>
