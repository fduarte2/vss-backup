<?


include("pow_session.php");
$user = $userdata['username'];

// get form values
$reason = trim($HTTP_POST_VARS["reason"]);
$invoice_num = $HTTP_POST_VARS["invoice_num"];

// get the array form data
$billing_num = $HTTP_POST_VARS["billing_num"];
$detail_line = $HTTP_POST_VARS["detail_line"];
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
//$ora_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
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
  $stmt = "update bill_detail set commodity_code_det = " . $commodity_code[$i] . ", service_code_det = " . $service_code[$i] . ", gl_code = " . $gl_code[$i] . ", asset_code = '" . $asset_code[$i] . "' 
			where billing_num = '".$billing_num[$i]."'
			and detail_line = '".$detail_line[$i]."'";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);	

  if (!$ora_success) {
    ora_rollback($ora_conn);
    printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
    exit;
  }
}
$stmt = "update bill_header
			set (commodity_code, service_code) = (select commodity_code_det, service_code_det from bill_detail where billing_num = '".$billing_num[0]."' and detail_line = '1')
		where billing_num = '".$billing_num[0]."'";
//echo $stmt;
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);	
if (!$ora_success) {
	ora_rollback($ora_conn);
	printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
	exit;
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
header("Location: update_invoice_v2.php?input=$invoice_num");

?>
