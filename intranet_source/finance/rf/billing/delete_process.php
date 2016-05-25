<?
/* File: delete_process.php
 * 
 * Created by Lynn F. Wang on 9/17/2004
 * This program marks a prebill as deleted so it won't get processed when we try to make 
 * this prebill part of an invoice
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// get form value
$billing_num = trim($HTTP_POST_VARS["billing_num"]);
$reason = trim($HTTP_POST_VARS["reason"]);

// include database parameters
include("connect.php");
$rf_billing = "rf_billing";

// make PostgreSQL database connection
$pg_conn = pg_connect("host=$host dbname=$db user=$dbuser");
if (!$pg_conn) {
  die("Cannot connect to PostgreSQL database server.  Please report to TS!");
}

// make Oracle database connection
$rf_conn = ora_logon("SAG_OWNER@$rf", "OWNER");
if (!$rf_conn) {
  printf("Cannot log on to the RF Oracle Server: " . ora_errorcode($rf_conn) . ". Please report to TS!");
  exit;
}
$rf_cursor = ora_open($rf_conn);

// turn RF auto-commit off
$ora_success = ora_commitoff($rf_conn);
if (!$ora_success) {
  printf("RF: Cannot Turn Auto Commit Off, " . ora_errorcode($rf_conn) . ". Please report to TS!");
  exit;
}

// update billing record
$stmt = "update $rf_billing set service_status = 'DELETED'where billing_num = $billing_num";

$ora_success = ora_parse($rf_cursor, $stmt);
if (!$ora_success) {
  // rollback
  ora_rollback($rf_conn);
  printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
  exit;
}

$ora_success = ora_exec($rf_cursor);	
if (!$ora_success) {
  // rollback
  ora_rollback($rf_conn);
  printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
  exit;
}

// log the billing change
// change_time gets assigned current time by the database
pg_query($pg_conn, "begin");
$stmt = "insert into billing_change (username, action, change_reason, billing_num, billing_type)
         values ('$user', 'Delete Prebill', '$reason', $billing_num, 'RF STORAGE')";
$result = pg_query($pg_conn, $stmt);
	 
if (!$result) {
  printf("Failed to update PostGreSQL database. All changes will be rollbacked. Please report to TS! ");
  pg_query($pg_conn, "rollback");
  ora_rollback($rf_conn);
} else {
  // commit the updates
  pg_query($pg_conn, "commit");
  ora_commit($rf_conn);
  ora_commit($rf_conn);
}

// close database connection
pg_close($pg_conn);
ora_close($rf_cursor);
ora_logoff($rf_conn);

// unset cookie
setcookie("billing_num", "");

// go back to Delete a Prebill page
header("Location: delete_bill.php?input=$billing_num");

?>
