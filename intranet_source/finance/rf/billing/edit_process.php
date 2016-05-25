<?
/* File: edit_process.php (Finance)
 * 
 * Created by Lynn F. Wang on 09/22/2004
 * This program updates a prebill according to user inputs
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// get form values
$reason = trim($HTTP_POST_VARS["reason"]);
$billing_num = trim($HTTP_POST_VARS["billing_num"]);
$customer_id = trim($HTTP_POST_VARS["customer_id"]);
$lr_num = trim($HTTP_POST_VARS["lr_num"]);
$service_date = trim($HTTP_POST_VARS["service_date"]);
$service_start = trim($HTTP_POST_VARS["service_start"]);
$service_stop = trim($HTTP_POST_VARS["service_stop"]);
$rate = trim($HTTP_POST_VARS["rate"]);
$unit = trim($HTTP_POST_VARS["unit"]);

// get the array form data of billing details
$barcode = $HTTP_POST_VARS["barcode"];
$start = $HTTP_POST_VARS["start"];
$end = $HTTP_POST_VARS["end"];
$detail_cases = $HTTP_POST_VARS["detail_cases"];
$detail_rate = $HTTP_POST_VARS["detail_rate"];
$detail_amount = $HTTP_POST_VARS["detail_amount"];

// include the date comparing function
include("compareDate.php");

// check the inputs to make them Oracle insertable
$rate = ($rate == "" ? "null" : $rate);

$timestamp = strtotime($service_date);
if ($timestamp == -1) {			// invalid date format
  die ("The Service Date you entered, $service_date, is not in an acceptable format.\n
        Please use the format as in the following example, 12/31/2003, and try it again");
} else {
  $service_date = date('m/d/Y', $timestamp);
}

$timestamp = strtotime($service_start);
if ($timestamp == -1) {			// invalid date format
  die ("The Start Date you entered, $service_start, is not in an acceptable format.\n
        Please use the format as in the following example, 12/31/2003, and try it again");
} else {
  $service_start = date('m/d/Y', $timestamp); 
}

$timestamp = strtotime($service_stop);
if ($timestamp == -1) {			// invalid date format
  die ("The End Date you entered, $service_stop, is not in an acceptable format.\n
        Please use the format as in the following example, 12/31/2003, and try it again");
} else {
  $service_stop = date('m/d/Y', $timestamp);  
}

// make sure start date is no later than end date
if (compareDate($service_start, $service_stop) > 0) {
  die("Start Date cannot be later than End date!  Please go back to previous page and try it again.");
}

// clean up array form data
$total_pallets = 0;
$total_cases = 0;
$total_amount = 0;

for ($i=0; $i<count($barcode); $i++) {
  if (trim($barcode[$i]) == "") {
    continue;
  }

  $start[$i] = trim($start[$i]);
  if ($start[$i] == "") {
    die("Please enter start date for barcode # " . $barcode[$i] . ", and try again!");
  } else {
    $timestamp = strtotime($start[$i]);
    if ($timestamp == -1) {			// invalid date format
      die ("The Start Date you entered, " . $start[$i] . ", for barcode # " . $barcode[$i] . 
	   " is not in an acceptable format.\n
            Please use the format as in the following example, 12/31/2004, and try again!");
    } else {
      $start[$i] = date('m/d/Y', $timestamp);   // only have date
    }
  }

  $end[$i] = trim($end[$i]);
  if ($end[$i] == "") {
    die("Please enter end date for barcode # " . $barcode[$i] . ", and try again!");
  } else {
    $timestamp = strtotime($end[$i]);
    if ($timestamp == -1) {			// invalid date format
      die ("The End Date you entered, " . $end[$i] . ", for barcode # " . $barcode[$i] . 
	   " is not in an acceptable format.\n
            Please use the format as in the following example, 12/31/2004, and try again!");
    } else {
      $end[$i] = date('m/d/Y', $timestamp);    // only have date
    }
  }

  // make sure start date is no later than end date
  if (compareDate($start[$i], $end[$i]) > 0) {
    die("For barcode # " . $barcode[$i] . ", the State Date, " . $start[$i] . ", is later than the End Date, " . $end[$i] .
	". Please correct them and try again.");
  }

  $detail_cases[$i] = ($detail_cases[$i] == "" ? 0 : $detail_cases[$i]);
  if (!is_numeric($detail_cases[$i]) || $detail_cases[$i] < 0) {
    die("The cases you entered, " . $detail_cases[$i] . ", for barcode # " . $barcode[$i] . 
	" has to be a number greater than 0.  Please correct it and try again");
  } else {
    $total_cases += $detail_cases[$i];
  }


  $detail_rate[$i] = ($detail_rate[$i] == "" ? 0 : $detail_rate[$i]);
  if (!is_numeric($detail_rate[$i]) || $detail_rate[$i] < 0) {
    die("The rate you entered, " . $detail_rate[$i] . ", for barcode # " . $barcode[$i] . 
	" has to a number greater than 0.  Please correct it and try again");
  }

  $detail_amount[$i] = ($detail_amount[$i] == "" ? 0 : $detail_amount[$i]);
  if (!is_numeric($detail_amount[$i]) || $detail_amount[$i] < 0) {
    die("The amount you entered, " . $detail_amount[$i] . ", for barcode # " . $barcode[$i] . 
	" has to be a number greater than 0.  Please correct it and try again");
  } else {
    $total_amount += $detail_amount[$i];
  }

  $total_pallets++;
}

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
$ora_conn = ora_logon("SAG_OWNER@$rf", "OWNER");
//$ora_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
if (!$ora_conn) {
  printf("Cannot logging on to the RF Oracle Server: " . ora_errorcode($ora_conn)) . ". Please report to TS!";
  exit;
}
$cursor = ora_open($ora_conn);

// turn auto-commit off
$ora_success = ora_commitoff($ora_conn);
if (!$ora_success) {
  printf("RF: Cannot Turn Auto Commit Off, " . ora_errorcode($ora_conn) . ". Please report to TS!");
  exit;
}

// table definitions
$rf_billing = "rf_billing";
$rf_billing_detail = "rf_billing_detail";

// get original billing information
$stmt = "select * from $rf_billing where billing_num = $billing_num";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);	
ora_fetch_into($cursor, $bill, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

// get current billing details, if any
$stmt = "select * from $rf_billing_detail where sum_bill_num = $billing_num and service_status = 'PREINVOICE' 
         order by pallet_id";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);	

$curr_pallets = array();
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
  array_push($curr_pallets, $row['PALLET_ID']);
}
 
// get all pallets for this bill, no matter it is deleted or not
$stmt = "select * from $rf_billing_detail where sum_bill_num = $billing_num order by pallet_id";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);	

$all_pallets = array();
while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
  array_push($all_pallets, $row['PALLET_ID']);
}
 
// update the prebill
$stmt = "update $rf_billing set customer_id = $customer_id, arrival_num = '$lr_num', 
            service_date = to_date('$service_date', 'MM/DD/YYYY'), 
            service_start = to_date('$service_start', 'MM/DD/YYYY'), 
            service_stop = to_date('$service_stop', 'MM/DD/YYYY'), 
            service_qty = $total_cases, service_qty2 = $total_pallets, service_rate = $rate, 
            service_amount = $total_amount, service_unit = '$unit'
	 where billing_num = $billing_num";

$ora_success = ora_parse($cursor, $stmt);
if (!$ora_success) {
  ora_rollback($ora_conn);
  printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
  exit;
}

$ora_success = ora_exec($cursor);	
if (!$ora_success) {
  ora_rollback($ora_conn);
  printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
  exit;
}

// update rf billing details
// step 1: update or add a line of record in billing detail
for ($i=0; $i<count($barcode); $i++) {
  if (trim($barcode[$i]) == "") {
    continue;
  }

  if (in_array($barcode[$i], $all_pallets)) {
    // update current billing detail record
    $stmt = "update $rf_billing_detail set customer_id = $customer_id, arrival_num = '$lr_num',
               service_date = to_date('" . $start[$i] . "', 'MM/DD/YYYY'), 
               service_start = to_date('" . $start[$i] . "', 'MM/DD/YYYY'),
               service_stop = to_date('" . $end[$i] . "', 'MM/DD/YYYY'),
               service_qty = " . $detail_cases[$i] . ", service_rate = " . $detail_rate[$i] . 
            ", service_amount = " . $detail_amount[$i] . ", service_unit = '$unit', 
               service_status = 'PREINVOICE' 
             where sum_bill_num = $billing_num and pallet_id = '" . $barcode[$i] . "'";
  } else {
    // insert a new billing detail record

    // get next billing number
    $stmt = "select MAX(BILLING_NUM) as MAX from $rf_billing_detail";
    
    $ora_success = ora_parse($cursor, $stmt);
    if (!$ora_success) {
      ora_rollback($ora_conn);
      printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
      exit;
    }

    $ora_success = ora_exec($cursor); 
    if (!$ora_success) {
      ora_rollback($ora_conn);
      printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
      exit;
    }

    ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $detail_billing_num = $row['MAX'] + 1;
    
    $stmt = "insert into $rf_billing_detail (customer_id, billing_num, sum_bill_num, billing_type, pallet_id, 
                 service_date, service_start, service_stop, service_qty, service_unit, service_rate, service_amount, 
                 service_status, arrival_num, care_of, service_code, commodity_code, asset_code)
             values ($customer_id, $detail_billing_num, $billing_num, 'PLT-STRG', '" . $barcode[$i] . 
                 "', to_date('" . $start[$i] . "', 'MM/DD/YYYY'), to_date('" . $start[$i] . "', 'MM/DD/YYYY'), " . 
                 "to_date('" . $end[$i] . "', 'MM/DD/YYYY'), " . $detail_cases[$i] . ", '$unit', " . 
                 $detail_rate[$i] . ", " . $detail_amount[$i] . ", 'PREINVOICE', '$lr_num', 'Y', 3111, " . 
	         $bill['COMMODITY_CODE'] . ", '" . $bill['ASSET_CODE'] . "')";
  }
  
  $ora_success = ora_parse($cursor, $stmt);
  if (!$ora_success) {
    ora_rollback($ora_conn);
    printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
    exit;
  }

  $ora_success = ora_exec($cursor);	
  if (!$ora_success) {
    // failed to update billing, rollback
    ora_rollback($ora_conn);
    printf("Failed in executing the query, $stmt.  All changes have been rollbacked. Please report to TS!");
    exit;
  }
}
  
// step 2: mark a line of record that is not on the new updating list as "DELETED"
for ($i=0; $i<count($curr_pallets); $i++) {
  $the_pallet = $curr_pallets[$i];
  
  if (!in_array($the_pallet, $barcode)) {
    $stmt = "update $rf_billing_detail set service_status = 'DELETED' 
             where sum_bill_num = $billing_num and pallet_id = '" . $the_pallet . "'";
    
    $ora_success = ora_parse($cursor, $stmt);
    if (!$ora_success) {
      // failed to update billing, rollback
      ora_rollback($ora_conn);
      printf("Failed in executing the query, $stmt.  All changes have been rollbacked.  Please report to TS!");
      exit;
    }
    
    $ora_success = ora_exec($cursor);	
    if (!$ora_success) {
      // failed to update billing, rollback
      ora_rollback($ora_conn);
      printf("Failed in executing the query, $stmt.  All changes have been rollbacked.  Please report to TS!");
      exit;
    }
  }
}  

// log the billing change, record all new values
// change_time gets assigned current time by the database
/*$billing_type = ($bill['BILLING_TYPE'] == "PLT-STRG" ? "RF STORAGE" : "UNKNOWN");

$stmt = "insert into billing_change (username, action, change_reason, billing_num, billing_type, 
            customer_id, service_date, service_start, service_stop, service_qty, service_rate,
            service_amount, service_unit)
         values ('$user', 'Edit RF Prebill', '$reason', $billing_num, '$billing_type', $customer_id, 
            '$service_date', '$service_start', '$service_stop', $total_cases, $rate, $total_amount, '$unit')";
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
ora_commit($ora_conn);
// close database connection
//pg_close($pg_conn);
ora_close($cursor);
ora_logoff($ora_conn);

// unset cookie
setcookie("billing_num", "");

// go back to Delete a Prebill page
header("Location: edit_bill.php?input=$billing_num");

?>
