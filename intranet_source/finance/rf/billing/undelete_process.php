<?
/* File: undelete_process.php
 * 
 * Created by Lynn F. Wang on 09/17/04
 * This program undelete a prebill previously marked as deleted so it will get processed 
 * when we try to make invoices out of prebills
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// get form value
$billing_num = $HTTP_POST_VARS["billing_num"];
$reason = $HTTP_POST_VARS["reason"];

// include database parameters
include("connect.php");
$rf_billing = "rf_billing";

// make PostgreSQL database connection
$pg_conn = pg_connect("host=$host dbname=$db user=$dbuser");
if (!$pg_conn) {
  die("Cannot open connection to PostgreSQL database server.  Please report to TS!");
}

// make Oracle database connection
$ora_conn = ora_logon("SAG_OWNER@$rf", "OWNER");
if (!$ora_conn) {
  printf("Cannot log on to the RF Server: " . ora_errorcode($ora_conn) . ". Please report to TS!");
  exit;
}
$cursor = ora_open($ora_conn);

// turn RF auto-commit off
$ora_success = ora_commitoff($ora_conn);
if (!$ora_success) {
  printf("RF: Cannot Turn Auto Commit Off, " . ora_errorcode($ora_conn) . ". Please report to TS!");
  exit;
}

// update billing record
$stmt = "update $rf_billing set service_status = 'PREINVOICE' where billing_num = $billing_num";

$ora_success = ora_parse($cursor, $stmt);
if (!$ora_success) {
  // rollback
  ora_rollback($ora_conn);
  printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
  exit;
}

$ora_success = ora_exec($cursor);	
if (!$ora_success) {
  // rollback
  ora_rollback($ora_conn);
  printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
  exit;
}

// log the billing change
// change_time gets assigned current time by the database
pg_query($pg_conn, "begin");
$stmt = "insert into billing_change (username, action, change_reason, billing_num, billing_type)
         values ('$user', 'Un-Delete Prebill', '$reason', $billing_num, 'RF STORAGE')";
$result = pg_query($pg_conn, $stmt);

if (!$result) {
  printf("Failed to update PostGreSQL database. All changes will be rollbacked. Please report to TS! ");
  pg_query($pg_conn, "rollback");
  ora_rollback($ora_conn);
} else {
  // commit the updates
  pg_query($pg_conn, "commit");
  ora_commit($ora_conn);
}

// close database connection
pg_close($pg_conn);
ora_close($cursor);
ora_logoff($ora_conn);

// unset cookie
setcookie("billing_num", "");

// go back to Delete a Prebill page
header("Location: undelete_bill.php?input=$billing_num");

?>
