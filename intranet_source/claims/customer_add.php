<?
  include("pow_session.php");

  $action = $HTTP_POST_VARS['action'];
  $customer_id = $HTTP_POST_VARS['customer_id'];

  if($action == "del"){
    $stmt = "update customer_profile set claims = 'N' where customer_id = '$customer_id'";
  }
  else{
    $stmt = "update customer_profile set claims = 'Y' where customer_id = '$customer_id'";
  }

  // Now run the command
  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if (!$bni_conn) {
   printf("Error logging on to the BNI Oracle Server:" . ora_errorcode($conn));
   printf("Please report to TS!");
   exit;
  }
  $cursor = ora_open($bni_conn);
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  header("Location: customer.php");
  exit;
?>
