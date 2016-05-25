<?
  // Sets certain permanent values for eport...
  include("connect.php");
  include("defines.php");
  $user = $HTTP_COOKIE_VARS["eport_user"];
  $customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
  $email = $HTTP_COOKIE_VARS["eport_email"];
  $theme = $HTTP_COOKIE_VARS["eport_theme"];
/*  if($theme == ""){
     $theme = "DEFAULT";
  }

  $value_connection = pg_connect ("host=$host dbname=$db user=$dbuser");
  $query = "select * from eport_themes where theme = '$theme'";
  $result = pg_query($value_connection, $query) or
             die("Error in query: $query. " .  pg_last_error($value_connection));
  $row = pg_fetch_row($result, 0); */
  $bg_color = "";
  $fg_color = "";
  $link = "#000080";
  $vlink = "#000080";
  $alink = "#000080";
  $big_text = "#0066CC";
  $left_color = "#4D76A5";
  $left_text = "#EECD11";
  // Ok, all vars are set
?>
