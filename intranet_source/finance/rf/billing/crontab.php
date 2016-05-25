<?
  // Seth Morecraft  11-NOV-03
  // Code to Actually create an entry in crontab

  include("defines.php");
  include("connect.php");

  $user = $HTTP_COOKIE_VARS['financeemail'];

  // Get all of the forms changes
  $cron_job = "RF Storage";
  $cron_date = $HTTP_POST_VARS["date"];
  // Time is hard-coded for RF Storage
  if($cron_job == "RF Storage"){
    $cron_time = "4:00";
  }
  else{
    $cron_time = $HTTP_POST_VARS["time"];
  }

  $datetime = $cron_date . " " . $cron_time;

  if($cron_date = ""){
    header("Location: schedule.php?cron_job=$cron_job&success=2");
    exit;
  }
  // Connect
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }

  // begin a database transaction to wrap up all our updates
  pg_query($conn, "begin");

  $stmt = "select nextval('cron_id_seq')";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
  $rows = pg_num_rows($result);
  if($rows <0){               // query error, should not happen
    die("Error in this query: $stmt. \n" . pg_last_error($conn));
  }else{
    $row = pg_fetch_row($result, 0);
    $cron_id = $row[0];
  }

  $stmt = "insert into crontab values ('$cron_id', '$user', '$cron_job', '$datetime', 'f')";
  //echo "$stmt";
  $result = pg_query($conn, $stmt);
  if (!$result) {
    pg_query($conn, "rollback");
    header("Location: schedule.php?cron_job=$cron_job&success=2");
    exit;
  }
  pg_query($conn, "commit");
  header("Location: schedule.php?cron_job=$cron_job&success=1");
?>
