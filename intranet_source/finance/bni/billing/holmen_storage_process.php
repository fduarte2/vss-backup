<?

/*--------------------------------------------------------------------------------------
 * Lynn F. Wang,  7/23/2004
 *
 * This program generates holmen storage prebills for the passed month.  It generates 
 * the prebill(s) for each arrival #, mill order #, free time end and storage charge
 * to date to keep it billed up to date.
 *--------------------------------------------------------------------------------------
 */

// we process storage for last month, from the 1st day to the last day.
$last_month = date("F", mktime(0, 0, 0, date("m"), 0, date("Y")));
$month_end = date("m/d/Y", mktime(0, 0, 0, date("m"), 0, date("Y")));
$last_period_start = date("m/d/Y", mktime(0, 0, 0, date("m") - 2, 1, date("Y")));

// mail Headers and such for detailed message for user.
$mailsubject = "Holmen Storage Charge - Generation Report";
$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
$mailTO = "lwang@port.state.de.us";
$body = "";

// make database connections
include("connect.php");
include("compareDate.php");

// connect to RF
$rf_conn = ora_logon("PAPINET@$rf", "OWNER");
if(!$rf_conn){
  $body = "Error logging on to RF Database Server: " . ora_errorcode($rf_conn);
  mail($mailTO, $mailsubject, $body, $mailheaders);
  exit;
}

// Turn Autocommit OFF!
$ora_success = ora_commitoff($rf_conn);
database_check($ora_success, "Unable to change to CommitOff mode!");

// open cursors
$rf_cursor1 = ora_open($rf_conn);
$rf_cursor2 = ora_open($rf_conn);

// connect to BNI
$bni_conn = ora_logon("SAG_OWNER@$bni", "SAG");
if(!$bni_conn){
  $body = "Error logging on to BNI Database Server: " . ora_errorcode($bni_conn);
  mail($mailTO, $mailsubject, $body, $mailheaders);
  exit;
}

// turn Autocommit OFF!
$ora_success = ora_commitoff($bni_conn);
database_check($ora_success, "Unable to change to CommitOff mode!");

// open cursors
$bni_cursor = ora_open($bni_conn); 

$body .= "Holmen Storage run for $last_month...\n\n";

// initialize variables
$service_code = "3111";
$commodity_code = "4374";
$asset_code = "WG00";
$gl_code = "3060";
$customer_id = 745;       // Holmen Paper
 
// get storage rate and unit
$stmt = "select * from storage_rate where service_code = $service_code and commodity_code = $commodity_code";
$ora_success = ora_parse($bni_cursor, $stmt);
database_check($ora_success, "\nUnable to Get Storage Rate\n");

$ora_success = ora_exec($bni_cursor);
database_check($ora_success, "\nUnable to Get Storate Rate\n");

ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$duration = $row['DURATION'];
$rate = $row['RATE'];
$unit = trim($row['UNIT']);

// get conversion factor
$stmt = "select * from unit_conversion where primary_uom = 'KG' and secondary_uom = '$unit'";
$ora_success = ora_parse($bni_cursor, $stmt);
database_check($ora_success, "\nUnable to Get Unit Conversion Factor\n");

$ora_success = ora_exec($bni_cursor);
database_check($ora_success, "\nUnable to Get Unit Conversion Factor\n");

ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$conversion_factor = $row['CONVERSION_FACTOR'];

// grab all Elements that need to be billed
// we want to generate the bill(s) for each arrival #, mill order #, free time end and storage charge
// to date to keep it billed to up to date
$body .= "Start generating preinvoices... ";

$stmt = "select ca.*, ct.pow_arrival_num, ct.free_time_end, ct.storage_charge_to, ct.gross_weight as roll_gw from cargo_activity ca, cargo_tracking ct where ca.arrival_num = ct.arrival_num and ca.mill_order_num = ct.mill_order_num and ca.barcode = ct.barcode and ca.activity_status = 'EXECUTED' and ct.free_time_end is not null and ct.free_time_end <= to_date('$month_end', 'MM/DD/YYYY') and (ct.storage_charge_to is null or (ct.storage_charge_to >= to_date('$last_period_start', 'MM/DD/YYYY') and ct.storage_charge_to < to_date('$month_end', 'MM/DD/YYYY'))) order by ca.arrival_num, ca.mill_order_num, ct.free_time_end, ct.storage_charge_to, ca.barcode, activity_date";

$error_msg = "Unable to get information from cargo_activity";
$ora_success = ora_parse($rf_cursor1, $stmt);
database_check($ora_success, $error_msg);

$ora_success = ora_exec($rf_cursor1);
database_check($ora_success, $error_msg);

// fetch the 1st line
ora_fetch_into($rf_cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
$rows = ora_numrows($rf_cursor1);

if ($rows <= 0) {
  $body .= "\nCurrently No Holmen Storage Bills Need To Be Generated!\n";
  mail($mailTO, $mailsubject, $body, $mailheaders);
  exit;
}

// initialize variables
$curr_lr_num = $row1['POW_ARRIVAL_NUM'];
$curr_arrival_num = $row1['ARRIVAL_NUM'];
$curr_mill_order_num = $row1['MILL_ORDER_NUM'];
$curr_barcode = $row1['BARCODE'];

if ($row1['FREE_TIME_END'] != "") {
  $curr_free_time_end = date("m/d/Y", strtotime($row1['FREE_TIME_END']));
} else {
  $curr_free_time_end = "";
}
  
if ($row1['STORAGE_CHARGE_TO'] != "") {
  $curr_storage_charge_to = date("m/d/Y", strtotime($row1['STORAGE_CHARGE_TO']));
} else {
  $curr_storage_charge_to = "";
}

// get the last free storage day
$last_free_day = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($curr_free_time_end)), 
				      date("d", strtotime($curr_free_time_end)) - 1, 
				      date("Y", strtotime($curr_free_time_end))));

// update the date against which we check our inventory
if ($curr_storage_charge_to == "") {
  $storage_start = $curr_free_time_end;
} else {
  $storage_start = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($curr_storage_charge_to)), 
					date("d", strtotime($curr_storage_charge_to)) + 1, 
					date("Y", strtotime($curr_storage_charge_to))));
}

$storage_stop = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($storage_start)), 
				     date("d", strtotime($storage_start)) + $duration - 1, 
				     date("Y", strtotime($storage_start))));

// flags are set this way so the 1st row can be processed
$new_time_frame = false;
$new_mill_order = false;
$new_barcode = true;

$has_prebill = false;
$billing_num = 0;
$start_billing_num = 0; 
$end_billing_num = 0;

// billing information for a specific mill order number, free time end, and storage charge to
$to_bill = false;    // flag to say whether we should bill on current mill order
$bills = array();    // each element carries period start/end, packages, weight for current mill order

// process one row at a time
do {
  // get roll information
  $lr_num = $row1['POW_ARRIVAL_NUM'];
  $arrival_num = $row1['ARRIVAL_NUM'];
  $mill_order_num = $row1['MILL_ORDER_NUM'];
  $barcode = $row1['BARCODE'];

  if ($row1['FREE_TIME_END'] != "") {
    $free_time_end = date("m/d/Y", strtotime($row1['FREE_TIME_END']));
  } else {
    $free_time_end = "";
  }
  
  if ($row1['STORAGE_CHARGE_TO'] != "") {
    $storage_charge_to = date("m/d/Y", strtotime($row1['STORAGE_CHARGE_TO']));
  } else {
    $storage_charge_to = "";
  }

  // check whether it is a new mill order number (it will be, if arrival_num is not the same)
  if ($arrival_num != $curr_arrival_num || $mill_order_num != $curr_mill_order_num) {
    // this will also keep the initial value
    $new_mill_order = true;
  }

  // check whether it is a new time frame
  if ($free_time_end != $curr_free_time_end || $storage_charge_to != $curr_storage_charge_to) {
    $new_time_frame = true;
  }

  // check whether it is a new barcode
  if ($barcode != $curr_barcode) {
    // this will also keep the initial value
    $new_barcode = true;
    $curr_barcode = $barcode;
  }

  // generate the prebill(s) when it is either new time frame or new mill order
  if ($new_mill_order || $new_time_frame) {
    // reset the flags
    $new_time_frame = false;
    $new_mill_order = false;
    
    // generate prebill(s) for current mill order #
    if ($to_bill) {
      generate_prebills();
    }
    
    // update the dates after writing to billing table
    $last_free_day = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($free_time_end)), 
					  date("d", strtotime($free_time_end)) - 1, 
					  date("Y", strtotime($free_time_end))));
    
    // update the date against which we check our inventory
    if ($storage_charge_to == "") {
      $storage_start = $free_time_end;
    } else {
      $storage_start = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($storage_charge_to)), 
					    date("d", strtotime($storage_charge_to)) + 1, 
					    date("Y", strtotime($storage_charge_to))));
    }
    
    $storage_stop = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($storage_start)), 
					 date("d", strtotime($storage_start)) + $duration - 1, 
					 date("Y", strtotime($storage_start))));

    $curr_lr_num = $lr_num;
    $curr_arrival_num = $arrival_num;
    $curr_mill_order_num = $mill_order_num;
    $curr_free_time_end = $free_time_end;
    $curr_storage_charge_to = $storage_charge_to;

    $to_bill = false;
    $bills = array();
  }

  // process a barcode only when we first see it
  if ($new_barcode) {
    // a new barcode
    $new_barcode = false;
    $charge_this_roll = false; 
    $period_start = $storage_start;
    $period_end = "";
    
    // process the 1st period and possible more periods
    for ($i=0; compareDate($period_start, $month_end) <= 0; $i++) {
      // get period end
      $period_end = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($period_start)), 
					date("d", strtotime($period_start)) + $duration - 1, 
					date("Y", strtotime($period_start))));
      
      $stmt = "select * from cargo_activity where arrival_num = '$arrival_num' and mill_order_num = '$mill_order_num' and barcode = '$barcode' and activity_date < to_date('$period_start', 'MM/DD/YYYY') and activity_status = 'EXECUTED' order by activity_date desc";
      
      $error_msg = "Unable to get information from cargo_activity";
      $ora_success = ora_parse($rf_cursor2, $stmt);
      database_check($ora_success, $error_msg);
      
      $ora_success = ora_exec($rf_cursor2);
      database_check($ora_success, $error_msg);
      
      ora_fetch_into($rf_cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($rf_cursor2);
      
      if ($rows > 0) {
	// normal case
	$qty = $row2['QTY_LEFT'];
	$weight = $row2['GROSS_WEIGHT'];
      } else {
	// no entry found, it is a late receiving scan - date received > free time end
	// get qty from cargo tracking
	$qty = 1;
	$weight = $row1['ROLL_GW'];
      }

      if ($qty > 0) {    
	// we billed storage for this roll
	$charge_this_roll = true;
	$to_bill = true;   // for this mill order, free_time_end, storage_charge_to
	
	if (is_null($bills[$i]['period_start'])) {
	  array_push($bills, array('period_start'=>$period_start, 'period_end'=>$period_end, 
				   'packages'=>$qty, 'weight'=>$weight));
	} else {
	  $bills[$i]['packages'] += $qty;
	  $bills[$i]['weight'] += $weight;
	}
      }

      // update period start and end
      $period_start = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($period_end)), 
					   date("d", strtotime($period_end)) + 1, 
					   date("Y", strtotime($period_end))));
    }
    
    // update storage information for this roll
    if (!$charge_this_roll) {
      // we won't bill the roll but mark it as processed so next time it won't show as new
      if ($storage_charge_to == "") {
	$stmt = "update cargo_tracking set storage_charge_to = to_date('$last_free_day', 'MM/DD/YYYY') where arrival_num = '$arrival_num' and mill_order_num = '$mill_order_num' and barcode = '$barcode'";
      }
    } else {
      // update storage information for this roll
      $stmt = "update cargo_tracking set storage_charge_to = to_date('$period_end', 'MM/DD/YYYY') where arrival_num = '$arrival_num' and mill_order_num = '$mill_order_num' and barcode = '$barcode'";
    }
    
    $error_msg = "Unable to update cargo_tracking for Barcode # $barcode";
    $ora_success = ora_parse($rf_cursor2, $stmt);
    database_check($ora_success, $error_msg);
    
    $ora_success = ora_exec($rf_cursor2);
    database_check($ora_success, $error_msg);
  }
} while (ora_fetch_into($rf_cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

// process last mill order #
if ($to_bill) {
  generate_prebills();
}

// write to invoicedate
if ($has_prebill) {
  $stmt = "select max(id) from invoicedate";

  $error_msg = "Cannot Get Information From BNI.Invoicedate";
  $ora_success = ora_parse($bni_cursor, $stmt);
  database_check($ora_success, $error_msg);
  
  $ora_success = ora_exec($bni_cursor);
  database_check($ora_success, $error_msg);
  
  $ora_success = ora_fetch($bni_cursor);
  database_check($ora_success, $error_msg);
  
  $id = ora_getcolumn($bni_cursor, 0);
  $id++;
  
  $stmt = "insert into invoicedate (id, run_date, type, bill_type, start_inv_no, end_inv_no)
           values ('$id', sysdate, 'Holmen Storage', 'B', '$start_billing_num', '$end_billing_num')";

  $error_msg = "Cannot Update BNI.Invoicedate";
  $ora_success = ora_parse($bni_cursor, $stmt);
  database_check($ora_success, $error_msg);
  
  $ora_success = ora_exec($bni_cursor);
  database_check($ora_success, $error_msg);
}

// commit database changes
$ora_success = ora_commit($bni_conn);
database_check($ora_success, "Unable to commit BNI database updates");

$ora_success = ora_commit($rf_conn);
database_check($ora_success, "Unable to commit RF database updates");

// Send a useful message out to tell that the process is done successfully
$body .= "\n\nJob Completed Successfully!\n";
mail($mailTO, $mailsubject, $body, $mailheaders);
exit;


// ------------------------------------ FUNCTIONS ------------------------------------- //

// Function to check the return value from Oracle- also gets a message from
// the caller to pass on to the user to be meaningful
function database_check($ora_success, $message){
  global $rf_conn, $bni_conn, $body, $mailTO, $mailsubject, $mailheaders;

  if (!$ora_success) {
    // here we got an error!
    ora_rollback($rf_conn);
    ora_rollback($bni_conn);
    
    $body .= $message;
    $body .= "\nTHIS JOB HAS FAILED DUE TO DATABASE ERROR!\nPLEASE CONTACT TS!\n";

    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
}


// Return the next available billing number
function getBillingNumber() {
  global $rf_conn, $bni_conn, $bni_cursor, $has_prebill, $start_billing_num, $end_billing_num;

  $stmt = "select max(billing_num) max_num from billing";
  $ora_success = ora_parse($bni_cursor, $stmt);
  database_check($ora_success, "\nUnable to Get Next Billing Number\n");

  $ora_success = ora_exec($bni_cursor);
  database_check($ora_success, "\nUnable to Get Next Billing Number\n");

  ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $billing_num = $row['MAX_NUM'] + 1;

  if (!$has_prebill) {
    $start_billing_num = $billing_num;
    $has_prebill = true;
  }

  // update last billing number
  $end_billing_num = $billing_num;

  return $billing_num;
}


// Generate Billing Records
function generate_prebills() {
  global $bni_conn, $bni_cursor, $billing_num, $curr_arrival_num, $curr_lr_num, $curr_mill_order_num, $service_code, $commodity_code, $asset_code, $gl_code, $customer_id, $rate, $unit, $conversion_factor, $bills;

  $num_bills = count($bills);

  for ($i=0; $i<$num_bills; $i++) {
    // get service qty and amount
    $packages = $bills[$i]['packages'];
    $tons = round($bills[$i]['weight'] * $conversion_factor, 2);
    $amount = round($tons * $rate, 2);
    
    // check to see whether we have a prebill with the same lr_num, mill order # and pay period
    // if yes, update it. otherwise, make a new bill.  
    // note that here we used lr_num, instead of arrival_num.  but it should be ok.
    $stmt = "select * from billing where billing_type = 'H_STORAGE' and service_status = 'PREINVOICE' and lr_num = $curr_lr_num and lot_num = '$curr_mill_order_num' and service_start = to_date('" . $bills[$i]['period_start'] . "', 'MM/DD/YYYY') and service_stop = to_date('" . $bills[$i]['period_end'] . "', 'MM/DD/YYYY')";
    
    $ora_success = ora_parse($bni_cursor, $stmt);
    database_check($ora_success, "\n\nFailed in query, $stmt");
    
    $ora_success = ora_exec($bni_cursor);	
    database_check($ora_success, "\n\nFailed in query, $stmt");

    ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $rows = ora_numrows($bni_cursor);

    if ($rows < 0) {
      $body .= "\n\nFailed in query, $stmt";
      mail($mailTO, $mailsubject, $body, $mailheaders);
      exit;
    } elseif ($rows == 0) {
      // insert a new prebill
      $billing_num = getBillingNumber($bni_conn, $bni_cursor);
      $desc = "VOYAGE #: " . $curr_arrival_num . ";  MILL ORDER #: " . $curr_mill_order_num . 
	";  $packages ROLLS, $tons $unit @ $" . $rate . " / $unit / 30 DAYS";
    
      $stmt = "insert into billing (lr_num, arrival_num, asset_code, billing_num, billing_type, commodity_code, commodity_name, customer_id, gl_code, lot_num, service_code, service_date, service_start, service_stop, service_qty, service_qty2, service_unit, service_rate, service_amount, service_status, service_description) values ($curr_lr_num, $curr_arrival_num, '$asset_code', $billing_num, 'H_STORAGE', $commodity_code, 'REEL PAPER', $customer_id, $gl_code, '$curr_mill_order_num', $service_code, to_date('" . $bills[$i]['period_start'] . "', 'MM/DD/YYYY'), to_date('" . $bills[$i]['period_start'] . "', 'MM/DD/YYYY'), to_date('" . $bills[$i]['period_end'] . "', 'MM/DD/YYYY'), $tons, $packages, '$unit', $rate, $amount, 'PREINVOICE', '$desc')";
    } else {
      // update the existing prebill
      $billing_num = $row['BILLING_NUM'];
      $packages += $row['SERVICE_QTY2'];
      $tons += $row['SERVICE_QTY'];
      $amount = round($tons * $rate, 2);
      $desc = "VOYAGE #: " . $curr_arrival_num . ";  MILL ORDER #: " . $curr_mill_order_num . 
	";  $packages ROLLS, $tons $unit @ $" . $rate . " / $unit / 30 DAYS";

      $stmt = "update billing set service_qty = $tons, service_qty2 = $packages, service_amount = $amount, service_description = '$desc' where billing_type = 'H_STORAGE' and service_status = 'PREINVOICE' and billing_num = $billing_num";
    }

    $ora_success = ora_parse($bni_cursor, $stmt);
    database_check($ora_success, "\n\nUnable to Update BNI.Billing Table\n$stmt");
    
    $ora_success = ora_exec($bni_cursor);	
    database_check($ora_success, "\n\nUnable to Update BNI.Billing Table\n$stmt");
  }
}


?>
