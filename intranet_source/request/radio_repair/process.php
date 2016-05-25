<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Radio Repair Request";
  $area_type = "SUPV";

  $date = date('m/d/Y');
  $user = $userdata['username'];

  $ticket_no = $HTTP_POST_VARS['ticket_no'];
  $model = $HTTP_POST_VARS['model'];
  $serial = $HTTP_POST_VARS['serial'];

  $problem = $HTTP_POST_VARS['problem'];
  $replace = $HTTP_POST_VARS['replace'];
  $channels = $HTTP_POST_VARS['channels'];

  $isNew = false;

  include("connect.php");
  $db = "ccds";

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  if ($ticket_no ==""){
        $isNew = true;
	$sql = "select max(ticket_no) from radio_repair_header";
	$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
	$row = pg_fetch_row($result, 0);
	$ticket_no = 1 + $row[0];

	$sql = "insert into radio_repair_header (ticket_no, date, user_name, model_no, serial_no, problem)
	 	values ($ticket_no, '$date', '$user','$model','$serial','$problem')"; 
  }else{
	$sql = "update radio_repair_header set
		model_no = '$model', serial_no = '$serial', problem='$problem'
		where ticket_no = '$ticket_no'";
  }

  pg_query($pg_conn, $sql);

  if ($isNew == false){
	$sql = "delete from radio_replace_requested where ticket_no = '$ticket_no'";
	pg_query($pg_conn, $sql);

	$sql = "delete from radio_channel_requested where ticket_no = '$ticket_no'";
	pg_query($pg_conn, $sql);
  }

  for ($i = 0; $i < count($replace); $i++){
        $sql = "insert into radio_replace_requested (ticket_no, replace_id)
                values ('$ticket_no', $replace[$i])";
        pg_query($pg_conn, $sql);
  }  
  
  for ($i = 0; $i < count($channels); $i++){
	$sql = "insert into radio_channel_requested (ticket_no, channel_id)
		values ('$ticket_no', $channels[$i])";
	pg_query($pg_conn, $sql);
  }

  header("location: index.php?ticket_no=$ticket_no");
?>
