<?
  // Seth Morecraft  11-NOV-03
  // Code to Actually create an entry in crontab

  include("defines.php");
  include("connect.php");

  $mailsubject = "Scheduled job Status report";
  $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";

  $end_time = date("m/d/Y G:i", mktime (date("G"),date("i"),0,date("m")  ,date("d"), date("y")));

  // Connect
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }

  // begin a database transaction to wrap up all our updates
  pg_query($conn, "begin");

  $stmt = "select * from crontab where cron_time <= '$end_time' and run = 'f'";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
  $rows = pg_num_rows($result);
  if($rows < 0){               // query error, should not happen
    die("Error in this query: $stmt. \n" . pg_last_error($conn));
  }else if($rows == 0){
    exit;
  }
  
  // Run these jobs and e-mail the user
  for($i = 0; $i < $rows; $i++){
    $body = "";
    $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
    $mailTO = $row['cron_user'];
    list($mailTOs, $domain) = split("@", $mailTO);
    $cron_job = $row['cron_job'];
    $actual_time = $row['cron_time'];
    
    $stmt = "select * from cronjobs where job_name = '$cron_job' and active = 't'";
    echo "$stmt\n";
    $job_result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
    $rows = pg_num_rows($result);
    if($rows <= 0){
      $body .= "Job title not found- please contact TS\n";
    }
    else{
      $row2 = pg_fetch_array($job_result, 0, PGSQL_ASSOC);
      $system_command = $row2['system_command'];
      system("/usr/bin/php $system_command $mailTOs\@$domain");
      $body .= "$cron_job has been completed\n";
    }
    // Send a message to the user
    //mail($mailTO, $mailsubject, $body, $mailheaders);
  }

  // Mark these items as completed
  $stmt = "update crontab set run = 't' where cron_time <= '$end_time' and run = 'f'";
  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
  pg_query($conn, "commit");
?>
