<?
 
  $url = "http://svr-app01/helpdesk/email_open_job.asp";

  $cmd = "/usr/bin/wget -q " .$url;

//  echo "$cmd";
  // Run the command
  system("$cmd");
?>
