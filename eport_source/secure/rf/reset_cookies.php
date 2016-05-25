<?
  // Resets the cookies for the eport system
  $user = $HTTP_COOKIE_VARS["eport_user"];
  if($user == ""){
      header("Location: ../ccds_login.php");
      exit;
   }
   include("connect.php");
   // open a connection to the database server
   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");

   $sql = "select * from eport_login where username = '$user'";
   $result = pg_query($connection, $query) or 
             die("Error in query: $query. " .  pg_last_error($connection));
   $row = pg_fetch_row($result, 0);
   // Get the database information
   $username = $row[EPORT_USERNAME];
   $customer_id = $row[EPORT_CUSTOMER_ID];
   $email = $row[EPORT_EMAIL];
   $theme = $row[EPORT_THEME];

   // Set the cookies properly
   setcookie("eport_user", "$username", time() + 86400);
   setcookie("eport_email", "$mail", time() + 86400);
   setcookie("eport_theme", "$theme", time() + 86400);
   setcookie("eport_customer_id", "$customer_id", time() + 86400);

   // And get out of here...
   header("Location: index.php");
?>
