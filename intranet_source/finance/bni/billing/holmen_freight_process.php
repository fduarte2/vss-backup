<?

/* File: holmen_freight_process.php
 * 
 * Created by Lynn F. Wang on 07/12/2004
 * This program generates or updates Holmen Freifht charge prebill according to user inputs
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// include the database_check function, database_check($ora_conn, $ora_success, $stmt)
include("billing_functions.php");

// watch out not to overwirte the form array variables
// $reconciled, $order_num, $fixed, $surcharge, $paid

if (!is_array($reconciled)) {
  $reconciled = array();
}

// make database connections
include("connect.php");
$order_detail = "order_detail";

$conn = ora_logon("PAPINET@$rf", "OWNER");
$cursor = ora_open($conn);

// turn auto commit off
$stmt = "ora_commitoff";
$ora_success = ora_commitoff($conn);
database_check($conn, $ora_success, $stmt);

for ($i=0; $i<count($order_num); $i++) {
  // for each shipping order

  // check wether it is reconciled
  $checked = in_array($order_num[$i], $reconciled);
  
  if ($checked) {
    $freight_reconciled = "Y";
  } else {
    $freight_reconciled = "";
  }

  // clean up and validate amount
  $fixed[$i] = trim($fixed[$i]);
  $surcharge[$i] = trim($surcharge[$i]);
  $paid[$i] = trim($paid[$i]);

  $fixed[$i] = ($fixed[$i] != "" ? $fixed[$i] : "null");
  $surcharge[$i] = ($surcharge[$i] != "" ? $surcharge[$i]  : "null");
  $paid[$i] = ($paid[$i] != "" ? $paid[$i] : "null");

  // update the amounts
  if ($fixed[$i] != "" || $surcharge[$i] != "" || $paid[$i] != "" || $freight_reconciled != "") {
    $stmt = "update $order_detail set fixed_charge = " . $fixed[$i] . ", surcharge = " . $surcharge[$i] . 
      ", freight_paid = " . $paid[$i] . ", freight_reconciled = '$freight_reconciled' where order_num = '" . 
      $order_num[$i] . "'";

    $ora_success = ora_parse($cursor, $stmt);
    database_check($conn, $ora_success, $stmt);
    
    $ora_success = ora_exec($cursor);	
    database_check($conn, $ora_success, $stmt);
  }
}

ora_commit($conn);
ora_logoff($conn);

header("Location: holmen_freight.php?result=1");
exit;

?>
