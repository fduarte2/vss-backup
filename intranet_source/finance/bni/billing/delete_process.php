<?
/* File: delete_process.php
 * 
 * Created by Lynn F. Wang on 8/20/2003
 * This program marks a prebill as deleted so it won't get processed when we try to make 
 * this prebill part of an invoice
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// get form value
$billing_num = trim($HTTP_POST_VARS["billing_num"]);
$lr_num = trim($HTTP_POST_VARS["lr_num"]);
$billing_type = trim($HTTP_POST_VARS["billing_type"]);
$customer_id = trim($HTTP_POST_VARS["customer_id"]);
$reason = trim($HTTP_POST_VARS["reason"]);
$whole_labor_ticket = $HTTP_POST_VARS["whole_labor_ticket"];

// include database parameters
include("connect.php");
/*
// make PostgreSQL database connection
$pg_conn = pg_connect("host=$host dbname=$db user=$dbuser");
if (!$pg_conn) {
  die("Cannot connect to PostgreSQL database server.  Please report to TS!");
}
*/
// make Oracle database connection
$bni_conn = ora_logon("SAG_OWNER@$bni", "SAG");
if (!$bni_conn) {
  printf("Cannot log on to the BNI Oracle Server: " . ora_errorcode($bni_conn) . ". Please report to TS!");
  exit;
}
$bni_cursor = ora_open($bni_conn);

// turn BNI auto-commit off
$ora_success = ora_commitoff($bni_conn);
if (!$ora_success) {
  printf("BNI: Cannot Turn Auto Commit Off, " . ora_errorcode($bni_conn) . ". Please report to TS!");
  exit;
}

// get labor ticket if it is a LABOR bill
if ($billing_type == 'LABOR') {
  // updates for Labor bill
  $stmt = "select labor_ticket_num from billing 
           where billing_num = $billing_num and billing_type = '$billing_type' and lr_num = $lr_num 
           and customer_id = $customer_id";

  $ora_success = ora_parse($bni_cursor, $stmt);
  if (!$ora_success) {
    // rollback
    ora_rollback($bni_conn);
    printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
    exit;
  }    

  $ora_success = ora_exec($bni_cursor);	
  if (!$ora_success) {
    // rollback
    ora_rollback($bni_conn);
    printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
    exit;
  }    

  ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  if ($row['LABOR_TICKET_NUM'] != "") {
    // has a labor ticket #
    $labor_ticket_num = $row['LABOR_TICKET_NUM'];
  } else {
    $labor_ticket_num = "";
  }
}

// update billing record
if ($billing_type == 'LABOR' && $whole_labor_ticket == "on") {
  $stmt = "update billing set service_status = 'DELETED'
	   where billing_type = '$billing_type' and lr_num = $lr_num and customer_id = $customer_id
           and labor_ticket_num = $labor_ticket_num";
} else {
  $stmt = "update billing set service_status = 'DELETED'
	   where billing_num = $billing_num and billing_type = '$billing_type' and lr_num = $lr_num 
           and customer_id = $customer_id";
}

$ora_success = ora_parse($bni_cursor, $stmt);
if (!$ora_success) {
  // rollback
  ora_rollback($bni_conn);
  printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
  exit;
}

$ora_success = ora_exec($bni_cursor);	
if (!$ora_success) {
  // rollback
  ora_rollback($bni_conn);
  printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
  exit;
}

// if it is FREIGHT (Holmen Freight) bill, mark the shipping order as unbilled
if ($billing_type == 'FREIGHT') {
  // updates for holmen freight
  $rf_conn = ora_logon("PAPINET@$rf", "OWNER");
  if (!$rf_conn) {
    printf("Cannot log on to the RF Oracle Server: " . ora_errorcode($rf_conn) . ". Please report to TS!\n");
    ora_rollback($bni_conn);
    exit;
  }
  $rf_cursor = ora_open($rf_conn);

  // turn RF auto-commit off
  $ora_success = ora_commitoff($rf_conn);
  if (!$ora_success) {
    ora_rollback($bni_conn);
    printf("RF: Cannot turn auto-commit off. All changes have been rollbacked. Please report to TS!");
    exit;
  }
  
  // first get the order #
  $stmt = "select RF_ORDER_NUM from billing 
           where billing_num = $billing_num and billing_type = '$billing_type' and lr_num = $lr_num 
           and customer_id = $customer_id";

  $ora_success = ora_parse($bni_cursor, $stmt);
  if (!$ora_success) {
    // rollback
    ora_rollback($bni_conn);
    printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
    exit;
  }    

  $ora_success = ora_exec($bni_cursor);	
  if (!$ora_success) {
    // rollback
    ora_rollback($bni_conn);
    printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
    exit;
  }    

  ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  if ($row['RF_ORDER_NUM'] != "") {
    $stmt = "update order_detail set freight_charge_status = null
  	     where order_num = '" . $row['RF_ORDER_NUM'] . "'";

    $ora_success = ora_parse($rf_cursor, $stmt);
    if (!$ora_success) {
      // rollback
      ora_rollback($bni_conn);
      ora_rollback($rf_conn);
      printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
      exit;
    }
    
    $ora_success = ora_exec($rf_cursor);	
    if (!$ora_success) {
      // rollback
      ora_rollback($bni_conn);
      ora_rollback($rf_conn);
      printf("Error occured on query, $stmt. All changes have been rollbacked. Please report to TS!");
      exit;
    }
  }
}

// if it is LABOR bill with a labor ticket # and there is no other bills associated with it, 
// then update billing status of this labor ticket
if ($billing_type == 'LABOR') {
  // updates for Labor bill
  if ($labor_ticket_num != "") {
    // has a labor ticket #
    // check if we have other labor bills having this labor ticket # that are not deleted
    $stmt = "select * from billing where labor_ticket_num = $labor_ticket_num and billing_num <> $billing_num and service_status <> 'DELETED'";
    
    $ora_success = ora_parse($bni_cursor, $stmt);
    if (!$ora_success) {
      // rollback
      ora_rollback($bni_conn);
      printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
      exit;
    }    

    $ora_success = ora_exec($bni_cursor);	
    if (!$ora_success) {
      // rollback
      ora_rollback($bni_conn);
      printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
      exit;
    }    
    
    ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $rows = ora_numrows($bni_cursor);
    
    // mark the labor ticket as Not billed yet (N), if there is no other bills having the same #
    if ($rows == 0) {
      // make Oracle database connection
      $lcs_conn = ora_logon("LABOR@$lcs", "LABOR");
      if (!$lcs_conn) {
	printf("Cannot log on to the LCS Oracle Server: " . ora_errorcode($lcs_conn) . ". Please report to TS!");
	ora_rollback($bni_conn);
	exit;
      }
      $lcs_cursor = ora_open($lcs_conn);
      
      // LCS: turn auto-commit off
      $ora_success = ora_commitoff($lcs_conn);
      if (!$ora_success) {
	printf("LCS: Cannot Turn Auto Commit Off, " . ora_errorcode($lcs_conn) . ". Please report to TS!");
	ora_rollback($bni_conn);
	exit;
      }

      // update labor ticket
      $stmt = "update labor_ticket_header set bill_status = 'N'
               where ticket_num = $labor_ticket_num";
      $ora_success = ora_parse($lcs_cursor, $stmt);
      if (!$ora_success) {
	// rollback
	ora_rollback($bni_conn);
	ora_rollback($lcs_conn);
	printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
	exit;
      } 

      $ora_success = ora_exec($lcs_cursor);	
      if (!$ora_success) {
	// rollback
	ora_rollback($bni_conn);
	ora_rollback($lcs_conn);
	printf("Failed in query, $stmt. All changes have been rollbacked. Please report to TS!");
	exit;
      } 
    }
  }
}
/*
// log the billing change
// change_time gets assigned current time by the database
pg_query($pg_conn, "begin");
$stmt = "insert into billing_change (username, action, change_reason, billing_num, billing_type, 
            customer_id, lr_num)
         values ('$user', 'Delete Prebill', '$reason', $billing_num, '$billing_type', 
            $customer_id, $lr_num)";
$result = pg_query($pg_conn, $stmt);
	 
if (!$result) {
  printf("Failed to update PostGreSQL database. All changes will be rollbacked. Please report to TS! ");
  pg_query($pg_conn, "rollback");
  ora_rollback($bni_conn);

  // if there is RF connection
  if ($rf_conn) {
    ora_rollback($rf_conn);
  }

  // if there is LCS connection
  if ($lcs_conn) {
    ora_rollback($lcs_conn);
  }
} else {*/
  // commit the updates
//  pg_query($pg_conn, "commit");
  ora_commit($bni_conn);

  if ($rf_conn) {
    ora_commit($rf_conn);
  }

  if ($lcs_conn) {
    ora_commit($lcs_conn);
  }
//}

// close database connection
//pg_close($pg_conn);
ora_close($bni_cursor);
ora_logoff($bni_conn);

// close RF connection
if ($rf_conn) {
  ora_close($rf_cursor);
  ora_logoff($rf_conn);
}

// close LCS connection
if ($lcs_conn) {
  ora_close($lcs_cursor);
  ora_logoff($lcs_conn);
}

// unset cookie
setcookie("billing_num", "");

// go back to Delete a Prebill page
header("Location: delete_bill.php?input=$billing_num");

?>
