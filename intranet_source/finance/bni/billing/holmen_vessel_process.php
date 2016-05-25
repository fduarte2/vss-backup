<?
/* File: holmen_vessel_process
 * 
 * Created by Lynn F. Wang on 08/16/04
 * This program generates Holmen vessel billing charges
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// define constant and variables
define("KG_TO_MT", 0.001);

$customer_id = "745";
$commodity_code = "4374";
$billing_num = 0;
$unit = "MT";

$asset_code = "WG00";
$logistics_asset_code = "0000";

$backhaul_rate = 2.29; // changed from 2.24 to 2.29, per Antonia's request - PWU, 1/17/07 HD # 2677 
$storage_rate = 3.68;  // changed from 3.58 to 3.68, per Antonia's request - PWU, 1/17/07 HD # 2677
$loading_rate = 3.98;  // changed from 3.88 to 3.98, per Antonia's request - PWU, 1/17/07 HD # 2677
$dunnage_rate = 0.5 ;  // changed from 0.7 to 0.5, per Antonia's HD Request # 2363  -- PWU, 8/4/06
$logistics_rate = 0.2;

$backhaul_gl_code = "3071";
$storage_gl_code = "3060";
$loading_gl_code = "3074";
$dunnage_gl_code = "3141";
$logistics_gl_code = "3140";

$backhaul_service_code = "6111";
$storage_service_code = "3111";
$loading_service_code = "6211";
$dunnage_service_code = "9732";
$logistics_service_code = "9731";

// make database connections
include("connect.php");
$rf_conn = ora_logon("PAPINET@$rf", "OWNER");
$rf_cursor1 = ora_open($rf_conn);
$rf_cursor2 = ora_open($rf_conn);

$bni_conn = ora_logon("SAG_OWNER@$bni", "SAG");
$bni_cursor = ora_open($bni_conn);

// turn auto commit off
$stmt = "ora_commitoff";
$ora_success = ora_commitoff($rf_conn);
do_database_check($ora_success, $stmt);
$ora_success = ora_commitoff($bni_conn);
do_database_check($ora_success, $stmt);

$has_prebill = false;

// get vessel billing information
$stmt = "select distinct pow_arrival_num, arrival_num, sum(orig_gross_weight) weight 
         from cargo_tracking where date_received is not null and edi_goodsreceipt = 'Y' and 
           (vessel_bill is null or vessel_bill <> 'Y')
         group by pow_arrival_num, arrival_num order by pow_arrival_num, arrival_num";
$ora_success = ora_parse($rf_cursor1, $stmt);
do_database_check($ora_success, $stmt);

$ora_success = ora_exec($rf_cursor1);
do_database_check($ora_success, $stmt);

ora_fetch_into($rf_cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$curr_lr_num = "";

do {
  // process one arrival number at a time
  $lr_num = $row1['POW_ARRIVAL_NUM'];
  $arrival_num = $row1['ARRIVAL_NUM'];

  // get vessel sailing date
  if ($lr_num != $curr_lr_num) {
    $curr_lr_num = $lr_num;
    $sail_date = getSailingDate($lr_num);
  }

  // check and calculate weights in MT
  if ($row1['WEIGHT'] <= 0) {
    continue;
  } else {
    $mt = round($row1['WEIGHT'] * KG_TO_MT, 2);
  }

  $backhaul_amt = round($mt * $backhaul_rate, 2);
  $storage_amt = round($mt * $storage_rate, 2);
  $loading_amt = round($mt * $loading_rate, 2);
  $dunnage_amt = round($mt * $dunnage_rate, 2);
  $logistics_amt = round($mt * $logistics_rate, 2);

  // backhaul charge
  $billing_type = "H_HANDLING";
  $memo = "BACKHAUL";
  $desc = "HANDLING CHARGE:  VOYAGE #: $arrival_num;  $mt MT";
  generate_prebill($billing_type, $backhaul_service_code, $backhaul_gl_code, $asset_code, $backhaul_rate, 
		   $backhaul_amt, $desc, $memo);

  // storage charge
  $billing_type = "H_HANDLING";
  $memo = "STORAGE";
  generate_prebill($billing_type, $storage_service_code, $storage_gl_code, $asset_code, $storage_rate, 
		   $storage_amt, $desc, $memo);
  
  // truckloading charge
  $billing_type = "H_HANDLING";
  $memo = "LOADING";
  generate_prebill($billing_type, $loading_service_code, $loading_gl_code, $asset_code, $loading_rate, 
		   $loading_amt, $desc, $memo);
  
  // dunnage charge
  $billing_type = "H_DUNNAGE";
  $memo = "DUNNAGE";
  $desc = "DUNNAGE CHARGE:  VOYAGE #: $arrival_num;  $mt MT @ $" . "$dunnage_rate / $unit";
  generate_prebill($billing_type, $dunnage_service_code, $dunnage_gl_code, $asset_code, $dunnage_rate, 
		   $dunnage_amt, $desc, $memo);
  
  // logistics charge
  $billing_type = "H_ADMIN";
  $memo = "LOGISTICS";
  $desc = "LOGISTICS CHARGE:  VOYAGE #: $arrival_num;  $mt MT @ $" . "$logistics_rate / $unit";
  generate_prebill($billing_type, $logistics_service_code, $logistics_gl_code, $logistics_asset_code, 
		   $logistics_rate, $logistics_amt, $desc, $memo);
  
  // update cargo tracking
  $stmt = "update cargo_tracking set vessel_bill = 'Y'
           where pow_arrival_num = '$lr_num' and arrival_num = '$arrival_num' and 
           date_received is not null and edi_goodsreceipt = 'Y' and (vessel_bill is null or vessel_bill <> 'Y')";
  $ora_success = ora_parse($rf_cursor2, $stmt);
  do_database_check($ora_success, $stmt);
  
  $ora_success = ora_exec($rf_cursor2);	
  do_database_check($ora_success, $stmt);

} while (ora_fetch_into($rf_cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

if ($has_prebill) {
  // log onto invoicedate
  $end_billing_num = $billing_num;

  $stmt = "select max(id) from invoicedate";
  $ora_success = ora_parse($bni_cursor, $stmt);
  do_database_check($ora_success, $stmt);
  
  $ora_success = ora_exec($bni_cursor);
  do_database_check($ora_success, $stmt);
  
  $ora_success = ora_fetch($bni_cursor);
  do_database_check($ora_success, $stmt);
  
  $id = ora_getcolumn($bni_cursor, 0);
  $id++;
  
  $stmt = "insert into invoicedate (id, run_date, type, bill_type, start_inv_no, end_inv_no)
           values ('$id', sysdate, 'Holmen Vessel', 'B', '$start_billing_num', '$end_billing_num')";
  $ora_success = ora_parse($bni_cursor, $stmt);
  do_database_check($ora_success, $stmt);
  
  $ora_success = ora_exec($bni_cursor);
  do_database_check($ora_success, $stmt);
}

ora_commit($bni_conn);
ora_logoff($bni_conn);

ora_commit($rf_conn);
ora_logoff($rf_conn);

header("Location: holmen_vessel.php?status=done");
exit;


// ----------------------------------------- FUNCTIONS -----------------------------------------

// my database error handler
function do_database_check($ora_success, $stmt) {
  global $rf_conn, $bni_conn;

  if (!$ora_success) {
    printf('Database error occurred on query, "' . $stmt . '". ' .
	   "All database updates will be rollbacked.  Please report to TS!\n");

    ora_rollback($rf_conn);
    ora_logoff($rf_conn);

    ora_rollback($bni_conn);
    ora_logoff($bni_conn);

    exit;
  }
}


// return the next available billing number
function getBillingNumber() {
  global $rf_conn, $bni_conn, $bni_cursor;

  $stmt = "select max(billing_num) max_num from billing";
  $ora_success = ora_parse($bni_cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($bni_cursor);
  do_database_check($ora_success, $stmt);

  ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $billing_num = $row['MAX_NUM'] + 1;

  return $billing_num;
}


// return vessel sail date
function getSailingDate($lr_num) {
  global $rf_conn, $bni_conn, $rf_cursor2;

  $stmt = "select distinct to_char(ship_sail_date, 'DD-MON-YY') sail_date from vessel_profile 
           where pow_arrival_num = '$lr_num'";
  $ora_success = ora_parse($rf_cursor2, $stmt);
  do_database_check($ora_success, $stmt);
  
  $ora_success = ora_exec($rf_cursor2);
  do_database_check($ora_success, $stmt);
  
  ora_fetch_into($rf_cursor2, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  return $row['SAIL_DATE'];
}


// generate prebill
function generate_prebill ($billing_type, $service_code, $gl_code, $asset_code, $rate, $amount, $desc, $memo) {
  global $rf_conn, $bni_conn, $bni_cursor, $lr_num, $arrival_num, $mt, $customer_id, $sail_date,
    $commodity_code, $unit, $has_prebill, $billing_num, $start_billing_num;
  
  $billing_num = getBillingNumber($bni_conn, $bni_cursor);
  
  if (!$has_prebill) {
    $has_prebill = true;
    $start_billing_num = $billing_num;
  }

  $stmt = "insert into billing (lr_num, arrival_num, asset_code, billing_num, billing_type, commodity_code, 
                commodity_name, customer_id, gl_code, service_code, service_date, service_start, service_stop, 
                service_qty, service_unit, service_rate, service_amount, service_status, service_description, 
                memo_num)
           values ($lr_num, $arrival_num, '$asset_code', $billing_num, '$billing_type', $commodity_code, 
                'REEL PAPER', $customer_id, $gl_code, $service_code, '$sail_date', '$sail_date', '$sail_date', 
                $mt, '$unit', $rate, $amount, 'PREINVOICE', '$desc', '$memo')";

  $ora_success = ora_parse($bni_cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($bni_cursor);	
  do_database_check($ora_success, $stmt);
}


?>
