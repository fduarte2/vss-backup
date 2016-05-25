<?
/*---------------------------------------------------------------------------
 *  Seth Morecraft  11-NOV-03
 *  Main Program Definition for RF Storage run
 *  should be run from crontab- a web page might time out (IE settings)
 *-------------------------------------------------------------------------*/

  // Standard definitions for used functions
  include("defines.php");
  include("connect.php");
  include("compareDate.php");

  // Table Definitions (to switch to backup mode)
  $cargo_tracking = "CARGO_TRACKING";
  $cargo_activity = "CARGO_ACTIVITY";
  $rf_billing = "RF_BILLING";
  $rf_billing_detail = "RF_BILLING_DETAIL";
  $storage_bill_log = "STORAGE_BILL_LOG";

  // Default service code is always 3111 for storage
  $service_code = "3111";

  // Mail Headers and such for detailed message for user.
  $mailsubject = "Scheduled job Status report";
  $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
  $mailheaders .= "Cc: " . "morecraf@port.state.de.us\r\n";
  $mailTO = $argv[1];
  $body = "";

  // Connect to RF
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  // Turn Autocommit OFF! (Just in case)
  $ora_success = ora_commitoff($conn);
  database_check($ora_success, "Unable to change to CommitOff mode!");
  // open cursors
  $cursor = ora_open($conn);         // general purpose
  $cursor1 = ora_open($conn);        // other use
  $cursor2 = ora_open($conn);        // other use
  $rate_cursor = ora_open($conn);    // Rate Lookup

  // Connect to BNI to get the Asset Codes
  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if(!$bni_conn){
    $body = "Error logging on to the BNI Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  // Turn Autocommit OFF! (Just in case)
  $ora_success = ora_commitoff($bni_conn);
  database_check($ora_success, "Unable to change to CommitOff mode!");
  // open cursors
  $bni_cursor = ora_open($bni_conn);         // general purpose

  // Find out which dates we need to run
  $today = date('m/d/Y');
  //$stmt = "select to_char(cut_off_date, 'MM/DD/YYYY') as cut_off_date, to_char(cut_off_date, 'YYYYMMDD') formatted from $storage_bill_log order by formatted desc";
  //$ora_success = ora_parse($cursor, $stmt);
  //$ora_success = ora_exec($cursor); 
  //database_check($ora_success, "Unable to find date range to run!  Check the $storage_bill_log Table");
  //ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  //$start_date = $row['CUT_OFF_DATE'];
  $start_date = "03/23/2004";
  $end_date = "03/30/2004";
  //$end_date = date('m/d/Y', mktime(0,0,0, date("m", strtotime($start_date)), (date("d", strtotime($start_date)) + 7), date("y", strtotime($start_date))));
  if(compareDate($today, $end_date) <= 0){
    $body .= "You are trying to run RF Storage with an Cut off Date greater than or equal to today! Please try again after $end_date\n";
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  $body .= "RF Storage Run for:\nStart Date: $start_date - End Date: $end_date\n\n";
  // See if we can get a MAX(BILLING_NUM) from RF_BILLING_DETAIL before we go
  // too far...
  $stmt = "select MAX(BILLING_NUM) as MAX from $rf_billing_detail";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor); 
  database_check($ora_success, "Unable to get a MAX Billing_Num from $rf_billing_detail");
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $billing_num = $row['MAX'];
  $billing_num++;  // This is the first billing_num for any pre-bill we make
  // Also get MAX(BILLING_NUM) from RF_BILLING
  $stmt = "select MAX(BILLING_NUM) as MAX from $rf_billing";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor); 
  database_check($ora_success, "Unable to get a MAX Billing_Num from $rf_billing");
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rf_billing_num = $row['MAX'];
  $rf_billing_num++;  // This is the first billing_num for any pre-bill we make
  
  // Update Shipping Line for NULL types (Most likely created pallets)
  $body .= "Setting Default Shipping Line if none is present.... ";

  // 5400 is Oppenheimer- set shipping_line(s)
  //$stmt = "update $cargo_tracking set from_shipping_line = '5400', shipping_line = '5400' where arrival_num like '1%' and receiving_type = 'S' and from_shipping_line is null and date_received is not null and receiver_id not in ('1', '453') and commodity_code <> 5841";
  //$ora_success = ora_parse($cursor, $stmt);
  //$ora_success = ora_exec($cursor); 
  //database_check($ora_success, "Unable to set Default Shipping line for Oppenheimer");

  // 8091 is PAC Seaways- set shipping_line(s)
  //$stmt = "update $cargo_tracking set from_shipping_line = '8091', shipping_line = '8091' where arrival_num like '20%' and receiving_type = 'S' and from_shipping_line is null and date_received is not null and receiver_id not in ('1', '453') and commodity_code <> 5841";
  //$ora_success = ora_parse($cursor, $stmt);
  //$ora_success = ora_exec($cursor); 
  //database_check($ora_success, "Unable to set Default Shipping line for Chilean Fruit");

  // Update statement billing_storage_date = date_received for trucked in cargo
  //$body .= "done.\nSetting billing date for trucked in cargo.... ";
  //$stmt = "update $cargo_tracking set billing_storage_date = date_received where receiving_type in ('T', 'F') and date_received >= to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and date_received <= to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and billing_storage_date is null";
  //$ora_success = ora_parse($cursor, $stmt);
  //$ora_success = ora_exec($cursor);
  //database_check($ora_success, "Unable to set billing date for trucked in cargo");

  // Main Loop to set the QTY_IN_STORAGE field for billing- also sets the BILL
  // field to 1 or 0 (True or False)
  // NOTE: We only do this 1st loop for Chilean Fruit!
  $body .= "done.\nSetting quantity in storage for $cargo_tracking.... ";
  $stmt = "select to_char(BILLING_STORAGE_DATE, 'MM/DD/YYYY HH24:MI:SS') as BILLING_STORAGE_DATE, PALLET_ID, ARRIVAL_NUM, RECEIVER_ID, RECEIVING_TYPE, FREE_TIME_END, QTY_RECEIVED  from $cargo_tracking where billing_storage_date between to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and arrival_num = '2004029' and receiver_id = '1608' and bill = '1' order by date_received";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($ora_success, "Unable get information from $cargo_tracking");
  
  /*
   *  Process Each element to set the QTY_IN_STORAGE and BILL values in 
   *  $cargo_tracking. This will then be used to grab everything where BILL = 1
   *  The QTY_IN_STORAGE will be what we use to bill of of.
   *  The current step is VITAL since it sets us up for billing.
   *  Also Note that BILL does not get set to 0 here.
   */
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    // Get infor from the ROW
    $pallet_id = trim($row['PALLET_ID']);
    $arrival_num = $row['ARRIVAL_NUM'];
    $receiver_id = $row['RECEIVER_ID'];
    $billing_storage_date = date('m/d/Y H:i:s', strtotime($row['BILLING_STORAGE_DATE']));
    $billing_storage_date_notime = date('m/d/Y', strtotime($row['BILLING_STORAGE_DATE']));
    $free_time_end = date('m/d/Y', strtotime($row['FREE_TIME_END']));
    $receiving_type = trim($row['RECEIVING_TYPE']);
    $qty_received = $row['QTY_RECEIVED'];
    
    // Note this is not ORDERED BY DATE OF ACTIVITY!!!!!
    $activity_stmt = "select qty_left, to_char(date_of_activity, 'MM/DD/YYYY') as date_of_activity, service_code from $cargo_activity where pallet_id = '$pallet_id' and arrival_num = '$arrival_num' and customer_id = '$receiver_id' and date_of_activity <= to_date('$billing_storage_date', 'MM/DD/YYYY HH24:MI:SS') ORDER BY ACTIVITY_NUM";
    $ora_success = ora_parse($cursor1, $activity_stmt);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to get $cargo_activity for Pallet: $pallet_id");
    
    // Trucked in with equal free_time
    if($recesaTiving_type == "T"){
      $free_time_end = $billing_storage_date_notime;
    }
    // Modification per HD Request 1118
    // If pallet is transferred to a customer and then delivered on the SAME
    // day, then no storage charge should be incured
    $activity_id = 0;
    $activity_date = "";

    $last_activity_id = 0;
    $last_activity_date = "";
    $no_bill = 0;

    // Get the last line of activity
    while(ora_fetch_into($cursor1, $activity_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      $activity_id = $activity_row['SERVICE_CODE'];
      $activity_date = $activity_row['DATE_OF_ACTIVITY'];
      $qty_left = $activity_row['QTY_LEFT'];

      // Per change 1118
      if($last_activity_id == "5"){ // Transfer
        if($activity_id == "6"){  // Shipped
          if($activity_date == $last_activity_date){
            $no_bill = 1;
          }
        }
      } // End 1118
      // Set vals for next run
      $last_activity_date = $activity_date;
      $last_activity_id = $activity_id;

    }
    $qty_left = $activity_row['QTY_LEFT'];
    if($qty_left > 0){
      $update_stmt = "update $cargo_tracking set qty_in_storage = '$qty_left', bill = '1' where pallet_id = '$pallet_id' and arrival_num = '$arrival_num' and receiver_id = '$receiver_id'";
      $ora_success = ora_parse($cursor1, $update_stmt);
      $ora_success = ora_exec($cursor1);
      database_check($ora_success, "Unable to set QTY_IN_STORAGE for Pallet: $pallet_id");
    }
  } // End QTY_IN_STORAGE Loop

  // Grab all Elements that need to be billed
  $body .= "done.\nGenerating Pre-Invoices.... ";
  $stmt = "select * from $cargo_tracking where arrival_num = '2004029' and billing_storage_date between to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and BILL = '1' and qty_in_storage > 0";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($ora_success, "Unable to get BILL = 1 elements from $cargo_tracking");

  /*  ---------------------------------------------------------------------
   *  Process each element with BILL = 1 and make a RF_BILLING_DETAIL line-
   *  this will be compiled in the final step to generate a SINGLE line in
   *  RF_BILLING
   */
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    // Initialize all variables
    $pallet_id = trim($row['PALLET_ID']);
    $arrival_num = $row['ARRIVAL_NUM'];
    $receiver_id = $row['RECEIVER_ID'];
    $billing_storage_date = date('m/d/Y H:i:s', strtotime($row['BILLING_STORAGE_DATE']));
    $billing_storage_date_notime = date('m/d/Y', strtotime($row['BILLING_STORAGE_DATE']));
    $free_time_end = $row['FREE_TIME_END'];
    // Take care of trucked in
    if($free_time_end == ""){
      $free_time_end = date('m/d/Y', strtotime($row['DATE_RECEIVED']));
    }
    else{
      $free_time_end = date('m/d/Y', strtotime($row['FREE_TIME_END']));
    }
    $receiving_type = trim($row['RECEIVING_TYPE']);
    $qty_received = $row['QTY_RECEIVED'];
    $qty_in_storage = $row['QTY_IN_STORAGE'];
    $stacking = $row['STACKING'];
    $from_shipping_line = $row['FROM_SHIPPING_LINE'];
    $to_shipping_line = $row['SHIPPING_LINE'];
    $commodity_code = $row['COMMODITY_CODE'];
    $location = $row['WAREHOUSE_LOCATION'];
    $sub_custid = $row['SUB_CUSTID'];

    // Billing Variables
    $billing_qty = 0;        // Program (Here & Below)
    $bill_amount = 0;
    $weight_bill_flag = 0;
    $storage_rate = 0;       // Database (Here & Below)
    $storage_unit = "N/A";

    // Get the billing variables set up for this pallet
    if($receiver_id == "1986"){
      $weight_bill_flag = 1;
      $billing_qty = $qty_in_storage;
    }
    else{
      $billing_qty = $qty_in_storage / $qty_received;
    }
    $storage_rate = getRate($receiver_id, $from_shipping_line, $to_shipping_line, $stacking);
    $storage_duration = getDuration($receiver_id, $from_shipping_line, $to_shipping_line, $stacking);
    $storage_unit = getUnit($receiver_id, $from_shipping_line, $to_shipping_line, $stacking);
    $commodity_code = getCommodity($from_shipping_line, $to_shipping_line);

    // Descriptions  This needs to happen after we know the duration
    // For Vessels, the billing_storage_date_notime will be equal to free_time_end
    if(compareDate($billing_storage_date_notime, $free_time_end) <= 0){
      $description = "PUT INTO STORAGE";
      $service_start = $free_time_end;
      $service_stop = date('m/d/Y', mktime(0,0,0, date('m', strtotime($free_time_end)), date('d', strtotime($free_time_end)) + $storage_duration, date('Y', strtotime($free_time_end))));
      $next_service_start = date('m/d/Y', mktime(0,0,0, date('m', strtotime($free_time_end)), date('d', strtotime($free_time_end)) + $storage_duration + 1, date('Y', strtotime($free_time_end))));
    }
    else{
      $description = "ONGOING STORAGE";
      $service_start = $billing_storage_date_notime;
      $service_stop = date('m/d/Y', mktime(0,0,0, date('m', strtotime($billing_storage_date)), date('d', strtotime($billing_storage_date)) + $storage_duration, date('Y', strtotime($billing_storage_date))));
      $next_service_start = date('m/d/Y', mktime(0,0,0, date('m', strtotime($billing_storage_date)), date('d', strtotime($billing_storage_date)) + $storage_duration + 1, date('Y', strtotime($billing_storage_date))));
    }
 
    // 1986 Bills by weight
    if($weight_bill_flag){
      // Error checking is done within getWeight()
      $weight = getWeight($cargo_commodity_code);
      $bill_amount = ($billing_qty * $storage_rate * $weight) / 100;
    }
    // Normal customers bill by our calculated billing_qty
    else{
      $bill_amount = $billing_qty * $storage_rate;
    }

    // Figure out the Asset Code - Error handlers are included here
    $asset_code = getAsset($location);

    // Make a line in RF_BILLING_DETAIL
    // Do not make invoices for $0
    if($bill_amount >= 1){
      // Big ol' insert statement for RF_BILLING_DETAIL
      $billing_line = "insert into $rf_billing_detail (CUSTOMER_ID, SERVICE_CODE, PALLET_ID, ACTIVITY_NUM, BILLING_NUM, EMPLOYEE_ID, SERVICE_START, SERVICE_STOP, SERVICE_AMOUNT, SERVICE_STATUS, SERVICE_DESCRIPTION, ARRIVAL_NUM, COMMODITY_CODE, INVOICE_NUM, SERVICE_DATE, SERVICE_QTY, SERVICE_NUM, THRESHOLD_QTY, LEASE_NUM, SERVICE_UNIT, SERVICE_RATE, PAGE_NUM, CARE_OF, BILLING_TYPE, STACKING, ASSET_CODE) values ('$receiver_id', '$service_code', '$pallet_id', '1', '$billing_num', '4', to_date('$service_start', 'MM/DD/YYYY'), to_date('$service_stop', 'MM/DD/YYYY'), '$bill_amount', 'PREINVOICE', '$description', '$arrival_num', '$commodity_code', '0', to_date('$service_start', 'MM/DD/YYYY'), '$qty_in_storage', '1', '0', '0', '$storage_unit', '$storage_rate', '1', 'Y', 'PLT-STRG', '$stacking', '$asset_code')";
      $ora_success = ora_parse($cursor1, $billing_line);
      $ora_success = ora_exec($cursor1);
      database_check($ora_success, "Unable to insert bill for $billing_num- Pallet: $pallet_id - \n$billing_line");
      
      // Increment $billing_num
      $billing_num++;

      // Now update cargo_tracking for next run
      $update_stmt = "update $cargo_tracking set billing_storage_date = to_date('$next_service_start', 'MM/DD/YYYY'), bill = '0' where pallet_id = '$pallet_id' and arrival_num = '$arrival_num' and receiver_id = '$receiver_id'";
      $ora_success = ora_parse($cursor2, $update_stmt);
      $ora_success = ora_exec($cursor2);
      database_check($ora_success, "Unable to update $cargo_tracking for Pallet: $pallet_id");
    }
  } // End BILL Loop

  //$body .= "done.\nCreating DATE entries for next storage run.... ";
  // Here we make an entry in STORAGE_BILL_LOG to ensure no days are missed
  //$stmt = "insert into $storage_bill_log (run_date, start_date, cut_off_date) values (to_date('$today', 'MM/DD/YYYY'), to_date('$start_date', 'MM/DD/YYYY'), to_date('$end_date', 'MM/DD/YYYY'))";
  //$ora_success = ora_parse($cursor, $stmt);
  //$ora_success = ora_exec($cursor);
  //database_check($ora_success, "Unable to add entry in $storage_bill_log");

  // At this point, we commit our changes - the hard part is over
/*  $body .= "done.\nCommiting $rf_billing_detail changes.... ";
  $ora_success = ora_commit($conn);
  database_check($ora_success, "Unable to commit $rf_billing_detail changes");
*/

  $body .= "done.\nCompiling Pre-Invoice Detail into Pre-Invoice Headers....\nINVOICE DETAILS:\n";
  /*
   * This is the final step in which we break down line items from
   * RF_BILLING_DETAIL and compile them into an invoice header in RF_BILLING
   * We finally get to use $rf_billing_num here (Checked in the start)
   */
  $stmt = "select arrival_num, customer_id, commodity_code, service_rate, stacking, service_start, service_stop, sum(service_amount) amount, sum(service_qty) quantity, count(*) qty2 from $rf_billing_detail where sum_bill_num is null and service_status = 'PREINVOICE' group by arrival_num, customer_id, commodity_code, service_rate, stacking, service_start, service_stop";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($ora_success, "Unable to get information from $rf_billing_detail");
  // Reset report variables
  $total_amount = 0;
  $invoices = 0;
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    // first grab all the information
    $sub_custid = $row['SUB_CUSTID'];
    $arrival_num = $row['ARRIVAL_NUM'];
    $customer_id = $row['CUSTOMER_ID'];
    $commodity_code = $row['COMMODITY_CODE'];
    $stacking = $row['STACKING'];
    $service_start = date('m/d/Y', strtotime($row['SERVICE_START']));
    $service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));
    $amount = $row['AMOUNT'];
    $quantity = $row['QUANTITY'];
    $qty2 = $row['QTY2'];
    $rate = $row['SERVICE_RATE'];
    
    // Figure out the UNIT & Asset Code
    $unit_stmt = "select distinct(service_unit) unit, distinct(asset_code) asset_code from $rf_billing_detail where arrival_num = '$arrival_num' and customer_id = '$customer_id' and commodity_code = '$commodity_code'";
    $ora_success = ora_parse($cursor1, $stmt);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to get distinct(service_unit) from $rf_billing_detail");
    ora_fetch_into($cursor1, $unit_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $unit = $unit_row['UNIT'];
    $asset_code = $unit_row['ASSET_CODE'];
    
    $prebill_line = "insert into $rf_billing (BILLING_NUM, SERVICE_CODE, ARRIVAL_NUM, CUSTOMER_ID, COMMODITY_CODE, ACTIVITY_NUM, EMPLOYEE_ID, SERVICE_STATUS, SERVICE_DESCRIPTION, INVOICE_NUM, SERVICE_NUM, THRESHOLD_QTY, LEASE_NUM, SERVICE_UNIT, SERVICE_QTY, SERVICE_QTY2, SERVICE_AMOUNT, STACKING, SERVICE_START, SERVICE_STOP, SERVICE_DATE, LABOR_RATE_TYPE, LABOR_TYPE, PAGE_NUM, CARE_OF, BILLING_TYPE, ASSET_CODE, SERVICE_RATE) values ('$rf_billing_num', '$service_code', '$arrival_num', '$customer_id', '$commodity_code', '1', '4', 'PREINVOICE', 'STORAGE', '0', '1', '0', '0', '$unit', '$quantity', '$qty2', '$amount', '$stacking', to_date('$service_start', 'MM/DD/YYYY'), to_date('$service_stop', 'MM/DD/YYYY'), to_date('$service_start', 'MM/DD/YYYY'), '', '', '1', 'Y', 'PLT-STRG', '$asset_code', '$rate')";
    $ora_success = ora_parse($cursor1, $prebill_line);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to insert into $rf_billing");

    $body .= "Pre-Invoice: $customer_id - $qty2 pallets = $$amount\n";
 
    // Update RF_BILLING_DETAIL to cross-reference the billing numbers
    if($stacking == ""){
    $update_stmt = "update $rf_billing_detail set sum_bill_num = '$rf_billing_num' where arrival_num = '$arrival_num' and commodity_code = '$commodity_code' and customer_id = '$customer_id' and stacking is null and service_status = 'PREINVOICE' and sum_bill_num is null";
    }
    else{
      $update_stmt = "update $rf_billing_detail set sum_bill_num = '$rf_billing_num' where arrival_num = '$arrival_num' and commodity_code = '$commodity_code' and customer_id = '$customer_id' and stacking = '$stacking' and service_status = 'PREINVOICE' and sum_bill_num is null";
    }
    $ora_success = ora_parse($cursor1, $update_stmt);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to update $rf_billing_detail\n $update_stmt");

    // Increment the rf_billing_num
    $rf_billing_num++;

    // Update the reports total
    $total_amount += $amount;
    $invoices++;
  }
  $body .= "\nCreated $invoices Invoices totalling $ $total_amount\n";

  // do a final COMMIT
  $body .= "\n.... done.\nCommiting $rf_billing changes.... ";
  $ora_success = ora_commit($conn);
  database_check($ora_success, "Unable to commit $rf_billing changes");
  $body .= " done.";

  // Send a useful message out to the user who ran this application
  $body .= "\n    Job Completed Successfully!\n";
  mail($mailTO, $mailsubject, $body, $mailheaders);
  // All Done
  exit;

/*---------------------------------------------------------------------------
 *  FUNCTION DEFINITIONS
 *-------------------------------------------------------------------------*/

// Function to check the return value from Oracle- also gets a message from
// the caller to pass on to the user to be meaningful
function database_check($oracle_return, $message){
  global $conn, $body, $mailTO, $mailsubject, $mailheaders;
  if(!$oracle_return){
    // Here, we have encountered an error!
    $body .= $message;
    $body .= "\nTS --- Oracle Error: " . ora_errorcode($conn);
    $body .= "\nUser --- THIS JOB HAS FAILED!\n";
    ora_rollback($conn);
    $body .= "TRANSACTION HAS BEEN ROLLED BÆCK!\n";
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
}

// Function to get the customer's rate matrix values 
function getRate($customer_id, $from_shipping_line, $shipping_line, $stacking){
  global $conn, $rate_cursor;
  if($customer_id == "1986"){
    if($stacking == ""){
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id'";
    }
    else{
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id' and stacking = '$stacking'";
    }
  }
  else{
    if($from_shipping_line != ""){
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line'";
      }
      else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking = '$stacking'";
      }
    }
    else{
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and customer_id is null";
      }
      else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and stacking = '$stacking' and customer_id is null";
      }
    }
  }
  $ora_success = ora_parse($rate_cursor, $rate_stmt);
  $ora_success = ora_exec($rate_cursor);
  database_check($ora_success, "Unable to get RF_STORAGE_RATE for customer: $customer_id\n$rate_stmt");
  ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rate = trim($rate_row['RATE']);
  if($rate == ""){
    // Here I use database_check as a general error handler
    database_check(-1, "Found a NULL rate for customer: $customer_id");
  }
  return $rate;
}

// Function to get the customer's rate matrix values (Unit)
function getUnit($customer_id, $from_shipping_line, $shipping_line, $stacking){
  global $conn, $rate_cursor;
  if($customer_id == "1986"){
    if($stacking == ""){
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id'";
    }
    else{
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id' and stacking = '$stacking'";
    }
  }
  else{
    if($from_shipping_line != ""){
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line'";
      }
      else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking = '$stacking'";
      }
    }
    else{
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and customer_id is null";
      }
      else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and stacking = '$stacking' and customer_id is null";
      }
    }
  }
  $ora_success = ora_parse($rate_cursor, $rate_stmt);
  $ora_success = ora_exec($rate_cursor);
  database_check($ora_success, "Unable to get RF_STORAGE_RATE for customer: $customer_id\n$rate_stmt");
  ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $unit = $rate_row['UNIT'];
  if($unit == ""){
    // Here I use database_check as a general error handler
    database_check(-1, "Found a NULL unit for customer: $customer_id");
  }
  return $unit;
}

// Function to get the customer's rate matrix values (Commodity Code)
function getDuration($customer_id, $from_shipping_line, $shipping_line, $stacking){
  global $conn, $rate_cursor;
  if($customer_id == "1986"){
    if($stacking == ""){
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id'";
    }
    else{
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id' and stacking = '$stacking'";
    }
  }
  else{
    if($from_shipping_line != ""){
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line'";
      }
      else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking = '$stacking'";
      }
    }
    else{
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and customer_id is null";
      }
      else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and stacking = '$stacking' and customer_id is null";
      }
    }
  }
  $ora_success = ora_parse($rate_cursor, $rate_stmt);
  $ora_success = ora_exec($rate_cursor);
  database_check($ora_success, "Unable to get RF_STORAGE_RATE for customer: $customer_id\n$rate_stmt");
  ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $duration = $rate_row['BILL_DURATION'];
  if($duration == ""){
    // Here I use database_check as a general error handler
    database_check(-1, "Found a NULL unit for customer: $customer_id");
  }
  return $duration;
}

// Function to get the weight of a commodity
function getWeight($cargo_commodity_code){
  global $rate_cursor, $conn;
  $rate_stmt = "select * from commodity_weight where commodity_code = '$cargo_commodity_code'";
  $ora_success = ora_parse($rate_cursor, $rate_stmt);
  $ora_success = ora_exec($rate_cursor);
  database_check($ora_success, "Unable to get WEIGHT for commodity: $cargo_commodity_code\n$rate_stmt");
  ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $weight = $rate_row['WEIGHT'];
  if($weight == ""){
    database_check(-1, "Unable to get WEIGHT for commodity: $cargo_commodity_code");
  }
  return $weight;
}

// Function to get the Asset Code based on the Warehouse Location
function getAsset($location){
  global $bni_cursor, $bni_conn;
  
  // Find a lookup key based on what our location looks like
  if(preg_match("/a/i", "$location"))
    $lookup = "WING A";
  else if(preg_match("/b/i", "$location"))
    $lookup = "WING B";
  else if(preg_match("/c/i", "$location"))
    $lookup = "WING C";
  else if(preg_match("/d/i", "$location"))
    $lookup = "WING D";
  else if(preg_match("/e/i", "$location"))
    $lookup = "WING E";
  else if(preg_match("/f/i", "$location"))
    $lookup = "WING F";
  else if(preg_match("/g/i", "$location"))
    $lookup = "WING G";
  else
    $lookup = "0000";

  $asset_stmt = "select * from asset_profile where service_location_code = '$lookup'";
  $ora_success = ora_parse($bni_cursor, $asset_stmt);
  $ora_success = ora_exec($bni_cursor);
  database_check($ora_success, "Unable to get ASSET CODE for location: $location \n$asset_stmt");
  ora_fetch_into($bni_cursor, $asset, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $asset_code = $asset['ASSET_CODE'];
  if($asset_code == "")
    $asset_code = "0000";
  return $asset_code;
}

// Commodity Codes set by the billing department
function getCommodity($from_shipping_line, $to_shipping_line){
  // Default to 0000
  $return = "0000";
  // Basically, we see which commodity code to use for these items using a
  // bunch of if statements- this is all included in documentation from finance

  /*
  Please use - 5198 for trucked in cargo Pac Seaways
               5298 for trucked in cargo Dole

               5100 for  Lauritzen cargo on a Pac Seaway vessel
               5101 for  PacSeaway cargo
               5103 for Dole cargo on a Pac Seaway vessel

               5200 for Lauritzen cargo on a Dole ship      
               5201 for Pac Seaway cargo on a Dole Ship
               5203    for Dole Cargo   
  */

  if($to_shipping_line == ""){
    $return = "5198";  // Trucked in
  }
  else if($to_shipping_line == "8091"){  // Pac-Sw
    if($from_shipping_line == "8091"){  // Pac-Sw
      $return = "5101";
    }
    if($from_shipping_line == "8010"){ // Lauritzen
      $return = "5100";
    }
    if($from_shipping_line == "453"){  // Dole
      $return = "5103";
    }
  }
  else if($from_shipping_line == "453"){  // Dole
    if($from_shipping_line == "8091"){  // Pac-Sw
      $return = "5201";
    }
    if($from_shipping_line == "8010"){ // Lauritzen
      $return = "5200";
    }
    if($from_shipping_line == "453"){  // Dole
      $return = "5203";
    }
  }
  return $return;
}
?>
