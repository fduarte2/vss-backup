<?

/* File: update_invoice_process.php (Finance)
 * 
 * Created by Lynn F. Wang on 07/20/2004
 * This program updates an invoice according to user inputs
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// get form values
$reason = trim($HTTP_POST_VARS["reason"]);
$invoice_num = $HTTP_POST_VARS["invoice_num"];

// get the array form data
$billing_num = $HTTP_POST_VARS["billing_num"];
$commodity_code = $HTTP_POST_VARS["commodity_code"];
$service_code = $HTTP_POST_VARS["service_code"];
$gl_code = $HTTP_POST_VARS["gl_code"];
$asset_code = $HTTP_POST_VARS["asset_code"];

// include database parameters
include("connect.php");
/*
// make PostgreSQL database connection
$pg_conn = pg_connect("host=$host dbname=$db user=$dbuser");
if (!$pg_conn) {
  die("Cannot open connection to PostgreSQL database server.  Please report to TS!");
}

// start a PostgreSQL transaction
pg_query($pg_conn, "begin");
*/
// make Oracle database connection
$ora_conn = ora_logon("SAG_OWNER@$bni", "SAG");
if (!$ora_conn) {
  die("Cannot logging on to the BNI Oracle Server: " . ora_errorcode($ora_conn)) . ". Please report to TS!";
}
$cursor = ora_open($ora_conn);

// turn BNI auto-commit off
$ora_success = ora_commitoff($ora_conn);
if (!$ora_success) {
  die("BNI: Cannot Turn Auto Commit Off, " . ora_errorcode($ora_conn) . ". Please report to TS!");
}

// update the invoice
$num_bills = count($billing_num);

for ($i=0; $i<$num_bills; $i++) {
  $stmt = "update billing set commodity_code = " . $commodity_code[$i] . ", service_code = " . $service_code[$i] . ", gl_code = " . $gl_code[$i] . ", asset_code = '" . $asset_code[$i] . "' where invoice_num = $invoice_num and billing_num = " . $billing_num[$i];
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);	

  if (!$ora_success) {
    ora_rollback($ora_conn);
    printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
    exit;
  }
}
/*
// log the billing change, record all new values
// change_time gets assigned current time by the database
$stmt = "insert into billing_change (username, action, change_reason, billing_num)
         values ('$user', 'Update Invoice', '$reason', $invoice_num)";
$result = pg_query($pg_conn, $stmt);
	 
if (!$result) {
  ora_rollback($ora_conn);
  pg_query($pg_conn, "rollback");
  printf("Failed in executing the query, $stmt. All changes have been rollbacked. Please report to TS! ");
  exit;
} else { */
  // commit the updates
  ora_commit($ora_conn);
//  pg_query($pg_conn, "commit");
//}

// close database connection
//pg_close($pg_conn);
ora_close($cursor);
ora_logoff($ora_conn);

// unset cookie
setcookie("invoice_num", "");

// go back to Update Invoices page
header("Location: update_invoice.php?input=$invoice_num");

?>
