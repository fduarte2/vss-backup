<?
/* File: edit_process.php (Finance)
 * 
 * Created by Lynn F. Wang on 10/07/2003
 * This program updates a prebill according to user inputs
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// get form values
$reason = trim($HTTP_POST_VARS["reason"]);
$billing_num = trim($HTTP_POST_VARS["billing_num"]);
$billing_type = trim($HTTP_POST_VARS["billing_type"]);
$customer_id = trim($HTTP_POST_VARS["customer_id"]);
$lr_num = trim($HTTP_POST_VARS["lr_num"]);
$service_date = trim($HTTP_POST_VARS["service_date"]);
$service_start = trim($HTTP_POST_VARS["service_start"]);
$service_stop = trim($HTTP_POST_VARS["service_stop"]);
$qty = trim($HTTP_POST_VARS["qty"]);
$rate = trim($HTTP_POST_VARS["rate"]);
$amount = trim($HTTP_POST_VARS["amount"]);
$unit = trim($HTTP_POST_VARS["unit"]);
$desc = trim($HTTP_POST_VARS["desc"]);
$desc = str_replace("'", "`", $desc);
$desc = str_replace("\\", "", $desc);
$has_details = trim($HTTP_POST_VARS["has_details"]);
$detail_billing_num = trim($HTTP_POST_VARS["detail_billing_num"]);

// get the array form data of billing details
if ($has_details == "true") {
  $lot = $HTTP_POST_VARS["lot"];
  $mark = $HTTP_POST_VARS["mark"];
  $po = $HTTP_POST_VARS["po"];
  $pallets = $HTTP_POST_VARS["pallets"];
  $cases = $HTTP_POST_VARS["cases"];
  $total = $HTTP_POST_VARS["total"];
}

// include the date comparing function
include("compareDate.php");

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
  printf("Cannot logging on to the BNI Oracle Server: " . ora_errorcode($ora_conn)) . ". Please report to TS!";
  exit;
}
$cursor = ora_open($ora_conn);

// get original information of this prebill
$stmt = "select * from billing where billing_num = $billing_num and billing_type = '$billing_type'"; 
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);	
ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

$orig_cust_id = trim($row['CUSTOMER_ID']);
$orig_desc = trim($row['SERVICE_DESCRIPTION']);
$lot_num = trim($row['LOT_NUM']);    // lot number for BNI and ccd_lot_id for CCDS
$order_num = trim($row['RF_ORDER_NUM']);
$product = trim($row['COMMODITY_NAME']);

if ($row['SERVICE_START'] != "") {
  $orig_service_start = date('m/d/Y', strtotime($row['SERVICE_START']));	 
} else {
  $orig_service_start = "";
}

if ($row['SERVICE_STOP'] != "") {
  $orig_service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));	 
} else {
  $orig_service_stop = "";
}

// get original billing details, if any
if ($has_details == "true") {
  $stmt = "select * from billing_detail where billing_num = $detail_billing_num order by lot";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);	
  
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $orig_lots = array();

  do {
    array_push($orig_lots, $row['LOT']);
  } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
}

// check the inputs to make them Oracle insertable
$qty = ($qty == "" ? "null" : $qty);
$rate = ($rate == "" ? "null" : $rate);

if ($service_date == "") {
  $service_date = "null";
} else {
  $timestamp = strtotime($service_date);
  if ($timestamp == -1) {			// invalid date format
    die ("The Service Date you entered, $service_date, is not in an acceptable format.\n
          Please use the format as in the following example, 12/31/2003, and try it again");
  } else {
    $service_date = date('m/d/Y', $timestamp);
  }
}

if ($service_start == "") {
  $service_start = "null";
} else {
  $timestamp = strtotime($service_start);
  if ($timestamp == -1) {			// invalid date format
    die ("The Start Date you entered, $service_start, is not in an acceptable format.\n
          Please use the format as in the following example, 12/31/2003, and try it again");
  } else {
    $new_service_start = date('m/d/Y', $timestamp);    // only have date
    $service_start = date('m/d/Y h:i A', $timestamp);  // have date and time
  }
}

if ($service_stop == "") {
  $service_stop = "null";
} else {
  $timestamp = strtotime($service_stop);
  if ($timestamp == -1) {			// invalid date format
    die ("The End Date you entered, $service_stop, is not in an acceptable format.\n
          Please use the format as in the following example, 12/31/2003, and try it again");
  } else {
    $new_service_stop = date('m/d/Y', $timestamp);    // only have date
    $service_stop = date('m/d/Y h:i A', $timestamp);  // have date and time
  }
}

// make sure start date is no later than end date
if (compareDate($new_service_start, $new_service_stop) > 0) {
  die("Start Date cannot be later than End date!  Please go back to previous page and try it again.");
}

// update the prebill
if ( ($service_date =="null") && ($service_start == "null") && ($service_stop = "null") )
{
	$stmt = "update billing set customer_id = $customer_id, lr_num = $lr_num, 
				service_qty = $qty, service_rate = $rate, service_amount = $amount, 
				service_unit = '$unit', service_description = '$desc'
		 		where billing_num = $billing_num and billing_type = '$billing_type'";
}
else
{
	$stmt = "update billing set customer_id = $customer_id, lr_num = $lr_num, 
				service_date = to_date('$service_date', 'MM/DD/YYYY'), 
				service_start = to_date('$service_start', 'MM/DD/YYYY HH:MI AM'), 
				service_stop = to_date('$service_stop', 'MM/DD/YYYY HH:MI AM'), 
				service_qty = $qty, service_rate = $rate, service_amount = $amount, 
				service_unit = '$unit', service_description = '$desc'
		 where billing_num = $billing_num and billing_type = '$billing_type'";
}
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);	

if (!$ora_success) {
  ora_rollback($ora_conn);
  printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
  exit;
}

// update billing details, if any
if ($has_details == "true") {
  // step 1: update or add a line of record in billing detail
  for ($i=0; $i<count($lot); $i++) {
    $lot[$i] = trim($lot[$i]);
    $mark[$i] = trim($mark[$i]);
    $po[$i] = trim($po[$i]);
    $total_test = trim($total[$i]);
    
    if ($lot[$i] == "") {
      continue;
    }
    
    if (in_array($lot[$i], $orig_lots)) {
      if($total_test != "" && $total_test != "0"){
        $stmt = "update billing_detail set mark = '" . $mark[$i] . "', po = '" . $po[$i] . "', pallet = " . 
	   $pallets[$i] . ", ctn = " . $cases[$i] . ", non_insp_qty = " . $total[$i] . 
	   " where billing_num = $detail_billing_num and lot = '" . $lot[$i] . "'";
      }
      else{
        $stmt = "update billing_detail set mark = '" . $mark[$i] . "', po = '" . $po[$i] . "', pallet = " . 
	   $pallets[$i] . ", ctn = " . $cases[$i] .
	   " where billing_num = $detail_billing_num and lot = '" . $lot[$i] . "'";
      }
    } else {
      if($total_test != "" && $total_test != "0"){
        $stmt = "insert into billing_detail (billing_num, lot, mark, po, pallet, ctn, line_nbr, lr_num, non_insp_qty) 
               values (" .  $detail_billing_num . ", '" . $lot[$i] . "', '" . $mark[$i] . "', '" . $po[$i] . "', " . 
	       $pallets[$i] . ", " . $cases[$i] . ", 0, " . $lr_num . ", " . $total[$i] . ")";
      }
      else{
        $stmt = "insert into billing_detail (billing_num, lot, mark, po, pallet, ctn, line_nbr, lr_num) 
               values (" .  $detail_billing_num . ", '" . $lot[$i] . "', '" . $mark[$i] . "', '" . $po[$i] . "', " . 
	       $pallets[$i] . ", " . $cases[$i] . ", 0, " . $lr_num . ")";
      }
    }
    
    $ora_success = ora_parse($cursor, $stmt);
    $ora_success = ora_exec($cursor);	

    if (!$ora_success) {
      // failed to update billing, rollback
      ora_rollback($ora_conn);
      printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
      exit;
    }
  }

  // step 2: delete a line of record that is not on the new updating list
  for ($i=0; $i<count($orig_lots); $i++) {
    $curr_lot = trim($orig_lots[$i]);

    if (!in_array($curr_lot, $lot)) {
      $stmt = "delete from billing_detail where billing_num = " . $detail_billing_num . " and lot = '" . 
	 $curr_lot . "'";
      $ora_success = ora_parse($cursor, $stmt);
      $ora_success = ora_exec($cursor);	
      
      if (!$ora_success) {
	// failed to update billing, rollback
	ora_rollback($ora_conn);
	printf("Failed in executing the query, $stmt.  All changes have been rollbacked.  Please report to TS!");
	exit;
      }
    }
  }  
}

// succeeded in updating billing record

// For BNI Storage, we need to update cargo_tracking.storage_end field if the End Date user input
// is different from the storage_end
if ($billing_type == 'STORAGE') {
  if (compareDate($orig_service_stop, $new_service_stop) != 0) {
    // update Storage End date only if it is the same as the original Service Stop date
    $stmt = "update cargo_tracking set storage_end = to_date('$new_service_stop', 'MM/DD/YYYY')
             where lot_num = '$lot_num' and storage_end = to_date('$orig_service_stop', 'MM/DD/YYYY')";
    $ora_success = ora_parse($cursor, $stmt);
    $ora_success = ora_exec($cursor);	
    
    if (!$ora_success) {
      ora_rollback($ora_conn);
//      pg_query($pg_conn, "rollback");
      printf("Failed in executing the query, $stmt. All changes have been rollbacked. Please report to TS!");
      exit;
    }
  }
}
/*
// For CCDS Storage, when the dates changed we need to update storage_charge_from and storage_charge_to 
// fields of ccd_inventory if it is Juice or inventory_history for other products
if ($billing_type == 'CCDS/STOR') {
  // Check if it is juice.  We calculate storage for juice according to storage_charge_to of ccd_inventory
  // and that of inventory_history for other products
  if (($product == "APPLE JUICE") || ($product == "KIWI JUICE") || 
      ($product == "JUICE") || ($product == "L. JUICE")) {
    $is_juice = true;
  } else {
    $is_juice = false;
  }
  
  // change BNI customer id's to CCDS customer id's
  $orig_ccds_cust = ccds_customer($orig_cust_id);
  $new_ccds_cust = ccds_customer($customer_id);
  
  // check if customer id is changed.  If it is, update inventory for both customers
  if ($orig_ccds_cust == $new_ccds_cust) {
    // customer didn't change
    if ((compareDate($orig_service_start, $new_service_start) != 0) || 
	(compareDate($orig_service_stop, $new_service_stop) != 0)) {
      if ($is_juice) {  
	// update storage_charge_from only if this billing record is the 1st one of that lot number and that month
	$stmt = "update ccd_inventory set storage_charge_from = '$new_service_start'
                 where ccd_lot_id = '$lot_num' and customer_id = $new_ccds_cust 
                    and storage_charge_from = '$orig_service_start'";
	$result = pg_query($pg_conn, $stmt);
	
	if (!$result) {
	  ora_rollback($ora_conn);
	  pg_query($pg_conn, "rollback");
	  printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS! ");
	  exit;
	}
	  
	// update storage_charge_to only if this billing record is the last one of that lot number and that month
	$stmt = "update ccd_inventory set storage_charge_to = '$new_service_stop'
                 where ccd_lot_id = '$lot_num' and customer_id = $new_ccds_cust
                    and storage_charge_to = '$orig_service_stop'";
	$result = pg_query($pg_conn, $stmt);
	
	if (!$result) {
	  ora_rollback($ora_conn);
	  pg_query($pg_conn, "rollback");
	  printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS! ");
	  exit;
	}
      } else {
	// not juice
	// update database only when this is not a billing record for a customer to pay storage for another
	if (!pay_for_others($orig_desc)) {
	  if ($order_num != "" && is_numeric($order_num)) {
	    $stmt = "update inventory_history 
                     set storage_charge_from = '$new_service_start', storage_charge_to = '$new_service_stop'
                     where ccd_lot_id = '$lot_num' and customer_id = $new_ccds_cust and order_num = $order_num";
	    $result = pg_query($pg_conn, $stmt);

	    if (!$result) {
	      ora_rollback($ora_conn);
	      pg_query($pg_conn, "rollback");
	      printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS! ");
	      exit;
	    }
	  }
	}
      }
    }
  } else {
    // customer changed, we want both customers have the same dates no matter what
    if ($is_juice) {  
      // update storage_charge_from only if this billing record is the 1st one of that lot number and that month
      $stmt = "update ccd_inventory set storage_charge_from = '$new_service_start'
               where ccd_lot_id = '$lot_num' and customer_id in ($new_ccds_cust, $orig_ccds_cust) 
                  and storage_charge_from = '$orig_service_start'";
      $result = pg_query($pg_conn, $stmt);

      if (!$result) {
	ora_rollback($ora_conn);
	pg_query($pg_conn, "rollback");
	printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS! ");
	exit;
      }
      
      // update storage_charge_to only if this billing record is the last one of that lot number and that month
      $stmt = "update ccd_inventory set storage_charge_to = '$new_service_stop'
               where ccd_lot_id = '$lot_num' and customer_id in ($new_ccds_cust, $orig_ccds_cust)
                  and storage_charge_to = '$orig_service_stop'";
      $result = pg_query($pg_conn, $stmt);
      
      if (!$result) {
	ora_rollback($ora_conn);
	pg_query($pg_conn, "rollback");
	printf("Failed in executing the query, $stmt.  All changes have been rollbacked.  Please report to TS! ");
	exit;
      }
    } else {
      // not juice
      if (!pay_for_others($orig_desc)) {
	if ($order_num != "" && is_numeric($order_num)) {
	  $stmt = "update inventory_history 
                   set storage_charge_from = '$new_service_start', storage_charge_to = '$new_service_stop'
                   where ccd_lot_id = '$lot_num' and customer_id in ($new_ccds_cust, $orig_ccds_cust)
                      and order_num = $order_num";
	  $result = pg_query($pg_conn, $stmt);
	  
	  if (!$result) {
	    ora_rollback($ora_conn);
	    pg_query($pg_conn, "rollback");
	    printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS! ");
	    exit;
	  }
	}
      }
    }
  }
}
  
// log the billing change, record all new values
// change_time gets assigned current time by the database
if (($service_date == "null") && ($service_start == "null") && ($service_stop = "null"))
{
	$stmt = "insert into billing_change (username, action, change_reason, billing_num, billing_type, 
				customer_id, lr_num, service_qty, service_rate,
				service_amount, service_unit, service_description)
			 values ('$user', 'Edit Prebill', '$reason', $billing_num, '$billing_type', $customer_id, 
				$lr_num, $qty, $rate, $amount, '$unit', '$desc')";

}
else
{
	$stmt = "insert into billing_change (username, action, change_reason, billing_num, billing_type, 
				customer_id, lr_num, service_date, service_start, service_stop, service_qty, service_rate,
				service_amount, service_unit, service_description)
			 values ('$user', 'Edit Prebill', '$reason', $billing_num, '$billing_type', $customer_id, 
				$lr_num, '$service_date', '$service_start', '$service_stop', $qty, $rate, $amount, '$unit', '$desc')";

}
$result = pg_query($pg_conn, $stmt);
	 
if (!$result) {
  ora_rollback($ora_conn);
  pg_query($pg_conn, "rollback");
  printf("Failed in executing the query, $stmt. All changes have been rollbacked. Please report to TS! ");
  exit;
} else {
  // commit the updates
  ora_commit($ora_conn);
  pg_query($pg_conn, "commit");
}
*/
// close database connection
//pg_close($pg_conn);
ora_close($cursor);
ora_logoff($ora_conn);

// unset cookie
setcookie("billing_num", "");

// go back to Delete a Prebill page
header("Location: edit_bill.php?input=$billing_num");


// -------------------------- Function Definition -----------------------------

// Convert BNI customer ID to CCDS customer ID
// e.g. 90128 -> 128

function ccds_customer($bni_cust_id) {
  if (strpos($bni_cust_id, "90000") === 0) {
    $ccd_cust_id = 0;
  } elseif (strpos($bni_cust_id, "9000") === 0) {
    $ccd_cust_id = substr($bni_cust_id, -1);
  } elseif (strpos($bni_cust_id, "900") === 0) {
    $ccd_cust_id = substr($bni_cust_id, -2);
  } elseif (strpos($bni_cust_id, "90") === 0) {
    $ccd_cust_id = substr($bni_cust_id, -3);
  } elseif ((strpos($bni_cust_id, "9") === 0) && 
	    (strlen($bni_cust_id) == 5)) {
    $ccd_cust_id = substr($bni_cust_id, -4);
  } else {
    $ccd_cust_id = $bni_cust_id;
  }

  return $ccd_cust_id;
}


// Check if this payment is made for others
// 1) Extended storage  2) Strachan pay for all KY Tex/Beef

function pay_for_others($desc) {
  $words = split(" ", $desc);

  if (in_array("Paid", $words)) {
    return true;
  } else {
    return false;
  }
 
}

?>
