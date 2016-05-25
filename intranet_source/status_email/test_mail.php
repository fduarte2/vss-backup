<?
 
  $url = "http://svr-app01/helpdesk/email_test.asp";
  
  $user = "rwang";
  $pass = "6rel55ax";
  
  // Build the command
  $cmd = "/usr/bin/wget --http-user=" . $user . " --http-passwd=" . $pass ." ''".$url;
  $cmd = "/usr/bin/wget -q " .$url;

//  echo "$cmd";
  // Run the command
  system("$cmd");

?>
