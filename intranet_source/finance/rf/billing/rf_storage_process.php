<?
/*---------------------------------------------------------------------------
 *  Seth Morecraft  11-NOV-03
 *  Main Program Definition for RF Storage run
 *  should be run from crontab- a web page might time out (IE settings)
 *-------------------------------------------------------------------------*/


/*
*	Adam Walter, Dec 2008.
*	
*	I have modified this script dozens of times now, and it's
*	Ability to handle the job it was given is falling apart.
*	(Fundamental assertions of the script changing and all)
*	This, as well as other parts of RF are in dire need of a
*	Re-write, all stemming from the fact that RF-comm codes and
*	BNI-comm codes are disjoint.  If I (or anyone else) am reading this
*	In the future, PLEASE streamline the 2 system's commodities.
*
***************************************************************************/

  // Standard definitions for used functions
  include("defines.php");
  include("connect.php");
  include("compareDate.php");

  // make sure the script does not time out 
  set_time_limit(0);

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
  $mailheaders .= "Cc: " . "awalter@port.state.de.us, lstewart@port.state.de.us\r\n";
  $mailheaders .= "Bcc: " . "ithomas@port.state.de.us\r\n";
//  $mailTO = $argv[1];
  $mailTO = "cfoster@port.state.de.us, philhower@port.state.de.us";
//  $mailTO = "awalter@port.state.de.us";
  $body = "";
  $email_body_warning = "";

  // Connect to RF
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
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
  $rate_cursor_temp = ora_open($conn);    // Rate Lookup

  // Connect to BNI to get the Asset Codes
  $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
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

  // validate dates
  $timestamp = strtotime($start_date);
  if ($timestamp == -1) {			
    // invalid date format
    $body .= "The start date you entered, $start_date, is not in an acceptable format. Please use the format as in the following example, 12/31/2003, and try it again\n";
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  } else {
    $start_date = date('m/d/Y', $timestamp);
  }

  $timestamp = strtotime($end_date);
  if ($timestamp == -1) {			
    // invalid date format
    $body .= "The end date you entered, $end_date, is not in an acceptable format. Please use the format as in the following example, 12/31/2003, and try it again\n";
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  } else {
    $end_date = date('m/d/Y', $timestamp);
  }

  if(compareDate($start_date, $end_date) > 0){
    $temp = $start_date;
    $start_date = $end_date;
    $end_date = $temp;
  }

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
  $billing_num++;     // This is the first billing_num for billing details

  // Also get MAX(BILLING_NUM) from RF_BILLING
  $stmt = "select MAX(BILLING_NUM) as MAX from $rf_billing";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor); 
  database_check($ora_success, "Unable to get a MAX Billing_Num from $rf_billing");
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rf_billing_num = $row['MAX'];
  $rf_billing_num++;  // This is the first billing_num for the summarizing prebills
  
  $next_year_billing = "24".date('y', mktime(0,0,0,date('m')+6,date('d'), date('y')))."0000";
  if($rf_billing_num < $next_year_billing){
	$rf_billing_num = $next_year_billing;
  }

  $start_billing_num = 0;
  $end_billing_num = 0;
  
  // Update Shipping Line for NULL types (Most likely created pallets)
  $body .= "Setting Default Shipping Line if none is present.... ";

  // 5400 is Oppenheimer - set shipping_line(s)
  $stmt = "update $cargo_tracking set from_shipping_line = '5400', shipping_line = '5400' where arrival_num like '1%' and receiving_type = 'S' and (from_shipping_line is null or from_shipping_line = '0') and date_received is not null and receiver_id not in ('1', '453') and commodity_code <> 5841";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor); 
  database_check($ora_success, "Unable to set Default Shipping line for Oppenheimer");

  // 8091 is PAC Seaways- set shipping_line(s)
  $stmt = "update $cargo_tracking set from_shipping_line = '8091', shipping_line = '8091' where arrival_num like '20%' and receiving_type = 'S' and (from_shipping_line is null or from_shipping_line = '0') and date_received is not null and receiver_id not in ('1', '453') and commodity_code <> 5841";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor); 
  database_check($ora_success, "Unable to set Default Shipping line for Chilean Fruit");

  // Update statement billing_storage_date = date_received for trucked in cargo
  // including transfers
  SetTruckRate($cursor);
/*  $body .= "done.\nSetting billing date for trucked in cargo.... ";
  $stmt = "update $cargo_tracking set billing_storage_date = date_received where receiving_type in ('T', 'F') and date_received >= to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and date_received <= to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and billing_storage_date is null";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($ora_success, "Unable to set billing date for trucked in cargo"); */

  // Main Loop to set the QTY_IN_STORAGE field for billing- also sets the BILL
  // field to 1 or 0 (True or False)
  // NOTE: We only do this 1st loop for Chilean Fruit!
  $body .= "done.\nSetting quantity in storage for $cargo_tracking.... ";
  $stmt = "select to_char(BILLING_STORAGE_DATE, 'MM/DD/YYYY HH24:MI:SS') as BILLING_STORAGE_DATE, PALLET_ID, ARRIVAL_NUM, RECEIVER_ID, COMMODITY_CODE, RECEIVING_TYPE, QTY_RECEIVED, FREE_TIME_END from $cargo_tracking where billing_storage_date between to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and receiver_id not in ('453') order by BILLING_STORAGE_DATE";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($ora_success, "Unable get information from $cargo_tracking");
  
  /*
   *  Process Each element to set the QTY_IN_STORAGE and BILL values in 
   *  $cargo_tracking. This will then be used to grab everything where BILL = 1
   *  The QTY_IN_STORAGE will be what we use to bill of.
   *  The current step is VITAL since it sets us up for billing.
   *  Also Note that BILL does not get set to 0 here.
   */
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    // Get infor from the ROW
    $pallet_id = substr(trim($row['PALLET_ID']), 0, 25);
    $arrival_num = $row['ARRIVAL_NUM'];
    $receiver_id = $row['RECEIVER_ID'];
    $receiving_type = trim($row['RECEIVING_TYPE']);
    $qty_received = $row['QTY_RECEIVED'];
    $free_time_end = date('m/d/Y', strtotime($row['FREE_TIME_END']));
    $billing_storage_date = date('m/d/Y H:i:s', strtotime($row['BILLING_STORAGE_DATE']));
    $billing_storage_date_notime = date('m/d/Y', strtotime($row['BILLING_STORAGE_DATE']));
	$comm_check = $row['COMMODITY_CODE'];
    
    // Note this is not ORDERED BY DATE OF ACTIVITY!!!!!
    $activity_stmt = "select qty_left, to_char(date_of_activity, 'MM/DD/YYYY') as date_of_activity, service_code from $cargo_activity where substr(pallet_id, 1, 25) = '$pallet_id' and arrival_num = '$arrival_num' and customer_id = '$receiver_id' and date_of_activity <= to_date('$billing_storage_date', 'MM/DD/YYYY HH24:MI:SS') ORDER BY ACTIVITY_NUM";
    $ora_success = ora_parse($cursor1, $activity_stmt);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to get $cargo_activity for Pallet: $pallet_id");
//	if($pallet_id == "0120160251" || $pallet_id == "0120160324") {
//		echo $activity_stmt."<br>";
//	}
    
    // Trucked in with equal free_time
/*    if($receiving_type == "T"){
      $free_time_end = $billing_storage_date_notime;
    } */

    // Modification per HD Request 1118
    // If pallet is transferred to a customer and then delivered on the SAME
    // day, then no storage charge should be incured
    $activity_id = 0;
    $activity_date = "";
    $transfer_date = "";
    $no_bill = 0;         // Bill this Activity (Provided QTY is > 0)

    // get the 1st line of activity
    ora_fetch_into($cursor1, $activity_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $rows = ora_numrows($cursor1);

    if ($rows <= 0) {
      // no activity before the billing storage date
      // check whether this pallet has any activity
      $stmt = "select * from $cargo_activity where substr(pallet_id, 1, 25) = '$pallet_id' and arrival_num = '$arrival_num' and customer_id = '$receiver_id'";
      $ora_success = ora_parse($cursor2, $stmt);
      $ora_success = ora_exec($cursor2);
      database_check($ora_success, "Unable to get $cargo_activity for Pallet: $pallet_id");
//		if($pallet_id == "0120160251" || $pallet_id == "0120160324") {
//			echo $stmt."<br>";
//		}

      ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($cursor2);

      if ($rows > 0) {
        // have activities, might have missed the receiving scan
        $qty_left = $qty_received;
      } else {
        // no activity at all, not received
        $no_bill = 1;
        $qty_left = 0;
      }
    } else {
      // have activity before the billing storage date
      do {
	$activity_id = $activity_row['SERVICE_CODE'];
	$activity_date = $activity_row['DATE_OF_ACTIVITY'];
	$qty_left = $activity_row['QTY_LEFT'];
	
	// Per change 1118
	// service code for transfer owner should be 11, LFW, 2/23/05
	if ($activity_id == "11") {
	  // transfer owner
	  $transfer_date = $activity_date;
	}
      }while(ora_fetch_into($cursor1, $activity_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

      // check on the last activity before the billing storage date
      if($activity_id == "6"){  // Shipped
	if($activity_date == $transfer_date){
	  $no_bill = 1;    // Do NOT bill this activity
	}
      }

      $qty_left = $activity_row['QTY_LEFT'];
    }

    // we don't want to billing for carton count <= 10  -- LFW, 3/26/05, Per Tonia Per HD # 1536
	// ---ADAM WALTER, Dec 4th, 2007.  Due to the inability of this program to handle Clementines given the current
	// ---setup, I am disabling this routine for Clementine products (customers 439 and 440).
	// ---Antonia will manually bill them as necessary until a long-term solution is generated.
	if(($qty_left > 10 || ($receiver_id =="312" && $comm_check == "1299")) && $no_bill == 0){
      $update_stmt = "update $cargo_tracking set qty_in_storage = '$qty_left', bill = '1' where substr(pallet_id, 1, 25) = '$pallet_id' and arrival_num = '$arrival_num' and receiver_id = '$receiver_id'";
      $ora_success = ora_parse($cursor1, $update_stmt);
      $ora_success = ora_exec($cursor1);
      database_check($ora_success, "Unable to set QTY_IN_STORAGE for Pallet: $pallet_id");    
    }
  }   // End QTY_IN_STORAGE Loop

  // Grab all Elements that need to be billed
  // we don't want to billing for carton count <= 10  -- LFW, 3/26/05, Per Tonia Per HD # 1536
  $body .= "done.\nGenerating Pre-Invoices.... ";
  $stmt = "select * from $cargo_tracking where billing_storage_date between to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and BILL = '1' and (qty_in_storage > 10 or (receiver_id = '312' and commodity_code = '1299'))";
//  $stmt = "select * from $cargo_tracking where billing_storage_date between to_date('$start_date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and to_date('$end_date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and BILL = '1' and qty_in_storage > 10 and arrival_num = '10051' and receiver_id = '175'";
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
    $pallet_id = substr(trim($row['PALLET_ID']), 0, 25);
    $arrival_num = $row['ARRIVAL_NUM'];
    $receiver_id = $row['RECEIVER_ID'];
    $billing_storage_date = date('m/d/Y H:i:s', strtotime($row['BILLING_STORAGE_DATE']));
    $billing_storage_date_notime = date('m/d/Y', strtotime($row['BILLING_STORAGE_DATE']));
    $free_time_end = $row['FREE_TIME_END'];

    // Take care of trucked in
    if($free_time_end == ""){
      $free_time_end = date('m/d/Y', strtotime($row['DATE_RECEIVED']));
      $free_time_end_for_hack = strtotime($row['DATE_RECEIVED']);
    }else{
      $free_time_end = date('m/d/Y', strtotime($row['FREE_TIME_END']));
      $free_time_end_for_hack = strtotime($row['FREE_TIME_END']);
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
	$weight = $row['WEIGHT'];

    // Billing Variables
    $billing_qty = 0;        // Program (Here & Below)
    $bill_amount = 0;
    $weight_bill_flag = 0;
    $storage_rate = 0;       // Database (Here & Below)
    $storage_unit = "N/A";

    // Get the billing variables set up for this pallet
    // 1986 - DEL MONTE FRESH PRODUCE
    if($receiver_id == "1986" || $receiver_id == "312"){
	  if($receiver_id == "1986"){
	      $weight_bill_flag = 1;
	      $billing_qty = $qty_in_storage;
	  } else {
			$billing_qty = $qty_in_storage * $weight * 0.0011; // billing qty = the net tons for abitibi.  god this code is ugly.
	  }
    }else{
      $billing_qty = $qty_in_storage / $qty_received;
    }

    $commodity_code = getCommodity($receiver_id, $from_shipping_line, $to_shipping_line, $receiving_type, $commodity_code);
    $storage_rate = getRate($receiver_id, $from_shipping_line, $to_shipping_line, $stacking, $receiving_type, $commodity_code, $location);
    $storage_duration = getDuration($receiver_id, $from_shipping_line, $to_shipping_line, $stacking, $receiving_type, $commodity_code, $location);
    $storage_unit = getUnit($receiver_id, $from_shipping_line, $to_shipping_line, $stacking, $receiving_type, $commodity_code);
//    $commodity_code = getCommodity($receiver_id, $from_shipping_line, $to_shipping_line);

//	HARD CODED EXCEPTION, Dec 2009.  A brand new storage system is being put in place, but the current one needs to be able to "tier"
//	storage IMMEDIATELY.  as such, I am hardcoding in this hack-patch; while a terrible practice, this script will be relegated
//	shortly, so a more in-depth alteration is unwarranted.  This takes chilean cargo older than 15(16 to be safe) days and changes its rate/days.
//  the caculation of "> 518400" is a check for 6 days worth of time; this will (should) miss the first billing cycle (as both 0 free days and
//	5 free days are less than 6), bust hit starting on the 2nd (as 10 days and 15 are both higher than 6)

//	--- other times may vary, but following the same principal of "days in seconds"
	if(($commodity_code == "5101" || $commodity_code == "5100" || $commodity_code == "5103" || $commodity_code == "5198" || $commodity_code == "5199") && (strtotime($row['BILLING_STORAGE_DATE']) - $free_time_end_for_hack > 518400)) {
		$storage_duration = 15;
		if($receiving_type == "S"){
//			$storage_rate = 9.50;
			$storage_rate = 9.79;
		} else {
//			$storage_rate = 10;
			$storage_rate = 10.30;
		}
	}
	if(($commodity_code == "5101" || $commodity_code == "5100" || $commodity_code == "5103" || $commodity_code == "5198" || $commodity_code == "5199") && $receiver_id == "399" && $receiving_type == "T" && (strtotime($row['BILLING_STORAGE_DATE']) - $free_time_end_for_hack > 86400)){
		$storage_duration = 15;
		$storage_rate = 11;
	}

		// argen FRUIT?!?!
	  $stmt = "select COUNT(*) THE_COUNT FROM LU_IS_VALID_ARGEN_RATE WHERE COMMODITY_CODE = '".$commodity_code."'";
	  $ora_success = ora_parse($rate_cursor_temp, $stmt);
	  $ora_success = ora_exec($rate_cursor_temp); 
	  database_check($ora_success, "Unable to get distinct(service_unit) from $rf_billing_detail");
	  ora_fetch_into($rate_cursor_temp, $very_temp_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  if((strtotime($row['BILLING_STORAGE_DATE']) - $free_time_end_for_hack > 518400) && $very_temp_row['THE_COUNT'] >= 1 && $receiving_type == "T"){
			$storage_rate = 10.30;
	  }
			

	if($commodity_code == "0000"){
		$email_body_warning .= $pallet_id." recorded with commodity code of zero\n";
	}

    // Descriptions  This needs to happen after we know the duration
    // For Vessels, the billing_storage_date_notime will be equal to free_time_end
    if(compareDate($billing_storage_date_notime, $free_time_end) <= 0){
      $description = "PUT INTO STORAGE";
      $service_start = $free_time_end;
      $service_stop = date('m/d/Y', mktime(0,0,0, date('m', strtotime($free_time_end)), date('d', strtotime($free_time_end)) + $storage_duration, date('Y', strtotime($free_time_end))));
      $next_service_start = date('m/d/Y', mktime(0,0,0, date('m', strtotime($free_time_end)), date('d', strtotime($free_time_end)) + $storage_duration + 1, date('Y', strtotime($free_time_end))));
    }else{
      $description = "ONGOING STORAGE";
      $service_start = $billing_storage_date_notime;
      $service_stop = date('m/d/Y', mktime(0,0,0, date('m', strtotime($billing_storage_date)), date('d', strtotime($billing_storage_date)) + $storage_duration, date('Y', strtotime($billing_storage_date))));
      $next_service_start = date('m/d/Y', mktime(0,0,0, date('m', strtotime($billing_storage_date)), date('d', strtotime($billing_storage_date)) + $storage_duration + 1, date('Y', strtotime($billing_storage_date))));
    }
 
    // 1986 Bills by weight
    if($weight_bill_flag == 1){
      // Error checking is done within getWeight()
		  $weight = getWeight($cargo_commodity_code);
		  $bill_amount = ($billing_qty * $storage_rate * $weight) / 100;
    }else{
      // Normal customers bill by our calculated billing_qty
      $bill_amount = $billing_qty * $storage_rate;
    }

    // Figure out the Asset Code - Error handlers are included here
    $asset_code = getAsset($location);

    // Make a line in RF_BILLING_DETAIL
    // Do not make invoices for $0
    if($bill_amount > 0){
	  // check if this pallet is an exception
	  // if not, perform billing
	  if(!pallet_exception($pallet_id, $receiver_id, $service_code, $arrival_num)){
		  // abitibi doesn't get billed to themselves (???)
		  if($receiver_id == "312"){
			  $bill_to_id = "113";
		  } elseif ($receiver_id == "439"){
			  $bill_to_id = "633";
		  } else {
			  $bill_to_id = $receiver_id;
		  }

		  // Big ol' insert statement for RF_BILLING_DETAIL
		  $billing_line = "insert into $rf_billing_detail (CUSTOMER_ID, SERVICE_CODE, PALLET_ID, ACTIVITY_NUM, BILLING_NUM, EMPLOYEE_ID, SERVICE_START, SERVICE_STOP, SERVICE_AMOUNT, SERVICE_STATUS, SERVICE_DESCRIPTION, ARRIVAL_NUM, COMMODITY_CODE, INVOICE_NUM, SERVICE_DATE, SERVICE_QTY, SERVICE_NUM, THRESHOLD_QTY, LEASE_NUM, SERVICE_UNIT, SERVICE_RATE, PAGE_NUM, CARE_OF, BILLING_TYPE, STACKING, ASSET_CODE) values ('$bill_to_id', '$service_code', '$pallet_id', '1', '$billing_num', '4', to_date('$service_start', 'MM/DD/YYYY'), to_date('$service_stop', 'MM/DD/YYYY'), '$bill_amount', 'PREINVOICE', '$description', '$arrival_num', '$commodity_code', '0', to_date('$service_start', 'MM/DD/YYYY'), '$qty_in_storage', '1', '0', '0', '$storage_unit', '$storage_rate', '1', 'Y', 'PLT-STRG', '$stacking', '$asset_code')";
		  $ora_success = ora_parse($cursor1, $billing_line);
		  $ora_success = ora_exec($cursor1);
		  database_check($ora_success, "Unable to insert bill for $billing_num- Pallet: $pallet_id - \n$billing_line");
//		  echo $billing_line."<br>";
		  
		  // Increment $billing_num
		  $billing_num++;
	  }

      // Now update cargo_tracking for next run
      $update_stmt = "update $cargo_tracking set billing_storage_date = to_date('$next_service_start', 'MM/DD/YYYY'), bill = '0' where substr(pallet_id, 1, 25) = '$pallet_id' and arrival_num = '$arrival_num' and receiver_id = '$receiver_id'";
      $ora_success = ora_parse($cursor2, $update_stmt);
      $ora_success = ora_exec($cursor2);
      database_check($ora_success, "Unable to update $cargo_tracking for Pallet: $pallet_id.  Failed SQL:  $update_stmt");
    }
  } // End BILL Loop

  $body .= "done.\nCreating DATE entries for next storage run.... ";

  // Here we make an entry in STORAGE_BILL_LOG to ensure no days are missed
  $stmt = "insert into $storage_bill_log (run_date, start_date, cut_off_date) values (to_date('$today', 'MM/DD/YYYY'), to_date('$start_date', 'MM/DD/YYYY'), to_date('$end_date', 'MM/DD/YYYY'))";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($ora_success, "Unable to add entry in $storage_bill_log");

  // At this point, the hard part is over
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
    // $sub_custid = $row['SUB_CUSTID'];         // not in the result
    $arrival_num = $row['ARRIVAL_NUM'];
    $customer_id = $row['CUSTOMER_ID'];
    $commodity_code = $row['COMMODITY_CODE'];
    $rate = $row['SERVICE_RATE'];
    $stacking = $row['STACKING'];
    $service_start = date('m/d/Y', strtotime($row['SERVICE_START']));
    $service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));
    $amount = $row['AMOUNT'];
    $quantity = $row['QUANTITY'];
    $qty2 = $row['QTY2'];
    
    // Figure out the UNIT & Asset Code
    $unit_stmt = "select distinct(service_unit) unit, distinct(asset_code) asset_code from $rf_billing_detail where arrival_num = '$arrival_num' and customer_id = '$customer_id' and commodity_code = '$commodity_code'";
    $ora_success = ora_parse($cursor1, $stmt);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to get distinct(service_unit) from $rf_billing_detail");
    ora_fetch_into($cursor1, $unit_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $unit = $unit_row['UNIT'];
    $asset_code = $unit_row['ASSET_CODE'];
    
    // write to rf billing table
    $prebill_line = "insert into $rf_billing (BILLING_NUM, SERVICE_CODE, ARRIVAL_NUM, CUSTOMER_ID, COMMODITY_CODE, ACTIVITY_NUM, EMPLOYEE_ID, SERVICE_STATUS, SERVICE_DESCRIPTION, INVOICE_NUM, SERVICE_NUM, THRESHOLD_QTY, LEASE_NUM, SERVICE_UNIT, SERVICE_QTY, SERVICE_QTY2, SERVICE_AMOUNT, STACKING, SERVICE_START, SERVICE_STOP, SERVICE_DATE, LABOR_RATE_TYPE, LABOR_TYPE, PAGE_NUM, CARE_OF, BILLING_TYPE, ASSET_CODE, SERVICE_RATE) values ('$rf_billing_num', '$service_code', '$arrival_num', '$customer_id', '$commodity_code', '1', '4', 'PREINVOICE', 'STORAGE', '0', '1', '0', '0', '$unit', '$quantity', '$qty2', '$amount', '$stacking', to_date('$service_start', 'MM/DD/YYYY'), to_date('$service_stop', 'MM/DD/YYYY'), to_date('$service_start', 'MM/DD/YYYY'), '', '', '1', 'Y', 'PLT-STRG', '$asset_code', '$rate')";
    $ora_success = ora_parse($cursor1, $prebill_line);
    $ora_success = ora_exec($cursor1);
    database_check($ora_success, "Unable to insert into $rf_billing");

    $body .= "Pre-Invoice: $customer_id - $qty2 pallets = $$amount\n";

    if ($start_billing_num == 0) {
      $start_billing_num = $rf_billing_num;
    }
 
    // Update RF_BILLING_DETAIL to cross-reference the billing numbers
    if($stacking == ""){
      $update_stmt = "update $rf_billing_detail set sum_bill_num = '$rf_billing_num' where arrival_num = '$arrival_num' and commodity_code = '$commodity_code' and customer_id = '$customer_id' and stacking is null and service_status = 'PREINVOICE' and service_start = to_date('$service_start', 'MM/DD/YYYY') and service_stop = to_date('$service_stop', 'MM/DD/YYYY') and sum_bill_num is null";
    }else{
      $update_stmt = "update $rf_billing_detail set sum_bill_num = '$rf_billing_num' where arrival_num = '$arrival_num' and commodity_code = '$commodity_code' and customer_id = '$customer_id' and stacking = '$stacking' and service_status = 'PREINVOICE' and service_start = to_date('$service_start', 'MM/DD/YYYY') and service_stop = to_date('$service_stop', 'MM/DD/YYYY') and sum_bill_num is null";
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

  if ($start_billing_num != 0) {
    $end_billing_num = $rf_billing_num - 1;
  }

  $body .= "\nCreated $invoices Invoices totalling $ $total_amount\n";

  // do a final COMMIT
  $body .= "\n.... done.\nCommiting $rf_billing changes.... ";
  $ora_success = ora_commit($conn);
  database_check($ora_success, "Unable to commit $rf_billing changes");
  $body .= " done.";

  // Send a useful message out to the user who ran this application
  $body .= "\n    Job Completed Successfully!\n";
  if($email_body_warning != ""){
	  $body .= "\nSPECIAL NOTIFICATION:\n".$email_body_warning;
  }
  mail($mailTO, $mailsubject, $body, $mailheaders);

  // All Done. Go back to Interface
  header("Location: rf_storage.php?start=$start_billing_num?end=$end_billing_num");
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
// modified dec 2008 to handle variable truck rates.
function getRate($customer_id, $from_shipping_line, $shipping_line, $stacking, $receiving_type, $commodity_code, $location){
  global $conn, $rate_cursor;

	// SPECIAL EXCEPTION, April 2009:
	// due to fun with marketing, pallets in A2 get a new rate, completely independant of any existing rate, or in fact,
	// any way of cross-referencing the "storage rate table" in the first place.  this hard-coded exception is only
	// expected to last until we re-write RF_BILLING to accomodate contract-driven logic.
	if($location == "A2" && $customer_id = 1608){
		if($stacking == "S"){
			return 17.32;
		} else {
			return 25.98;
		}
	}

	if($receiving_type == "T"){
		$sql = "SELECT RATE FROM RF_STORAGE_RATE WHERE RECEIVE_TYPE = 'T' AND COMMODITY_CODE = '".$commodity_code."' AND CUSTOMER_ID = '".$customer_id."'";
		$ora_success = ora_parse($rate_cursor, $sql);
		$ora_success = ora_exec($rate_cursor);
		if(ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			return $rate_row['RATE'];
		} else {
			// get default truck rate
			$sql = "SELECT RATE FROM RF_STORAGE_RATE WHERE RECEIVE_TYPE = 'T' AND COMMODITY_CODE IS NULL AND CUSTOMER_ID IS NULL";
			$ora_success = ora_parse($rate_cursor, $sql);
			$ora_success = ora_exec($rate_cursor);
			ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			return $rate_row['RATE'];
		}
	}

  if($customer_id == "1986"){
    if($stacking == ""){
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id'";
    }else{
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id' and stacking = '$stacking'";
    }
  }else{
    if($from_shipping_line != ""){
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking is null";
      }else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking = '$stacking'";
      }
    }else{
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and customer_id is null and stacking is null";
      }else{
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
// modified dec 2008 to handle variable truck rates.
function getUnit($customer_id, $from_shipping_line, $shipping_line, $stacking, $receiving_type, $commodity_code){
  global $conn, $rate_cursor;

	if($receiving_type == "T"){
		$sql = "SELECT UNIT FROM RF_STORAGE_RATE WHERE RECEIVE_TYPE = 'T' AND COMMODITY_CODE = '".$commodity_code."' AND CUSTOMER_ID = '".$customer_id."'";
		$ora_success = ora_parse($rate_cursor, $sql);
		$ora_success = ora_exec($rate_cursor);
		if(ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			return $rate_row['UNIT'];
		} else {
			// get default truck rate
			$sql = "SELECT UNIT FROM RF_STORAGE_RATE WHERE RECEIVE_TYPE = 'T' AND COMMODITY_CODE IS NULL AND CUSTOMER_ID IS NULL";
			$ora_success = ora_parse($rate_cursor, $sql);
			$ora_success = ora_exec($rate_cursor);
			ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			return $rate_row['UNIT'];
		}
	}

  if($customer_id == "1986"){
    if($stacking == ""){
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id'";
    }else{
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id' and stacking = '$stacking'";
    }
  }else{
    if($from_shipping_line != ""){
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking is null";
      }else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking = '$stacking'";
      }
    }else{
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and customer_id is null and stacking is null";
      }else{
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

// Function to get the customer's rate matrix values (duration)
// modified dec 2008 to handle variable truck rates.
function getDuration($customer_id, $from_shipping_line, $shipping_line, $stacking, $receiving_type, $commodity_code, $location){
  global $conn, $rate_cursor;

	// SPECIAL EXCEPTION, April 2009:
	// due to fun with marketing, pallets in A2 get a new rate, completely independant of any existing rate, or in fact,
	// any way of cross-referencing the "storage rate table" in the first place.  this hard-coded exception is only
	// expected to last until we re-write RF_BILLING to accomodate contract-driven logic.
	if($location == "A2"){
		return 30;
	}

	
	if($receiving_type == "T"){
		$sql = "SELECT BILL_DURATION FROM RF_STORAGE_RATE WHERE RECEIVE_TYPE = 'T' AND COMMODITY_CODE = '".$commodity_code."' AND CUSTOMER_ID = '".$customer_id."'";
		$ora_success = ora_parse($rate_cursor, $sql);
		$ora_success = ora_exec($rate_cursor);
		if(ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			return $rate_row['BILL_DURATION'];
		} else {
			// get default truck rate
			$sql = "SELECT BILL_DURATION FROM RF_STORAGE_RATE WHERE RECEIVE_TYPE = 'T' AND COMMODITY_CODE IS NULL AND CUSTOMER_ID IS NULL";
			$ora_success = ora_parse($rate_cursor, $sql);
			$ora_success = ora_exec($rate_cursor);
			ora_fetch_into($rate_cursor, $rate_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			return $rate_row['BILL_DURATION'];
		}
	}

  if($customer_id == "1986"){
    if($stacking == ""){
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id'";
    }else{
      $rate_stmt = "select * from rf_storage_rate where customer_id = '$customer_id' and stacking = '$stacking'";
    }
  }else{
    if($from_shipping_line != ""){
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking is null";
      }else{
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line = '$from_shipping_line' and shipping_line = '$shipping_line' and stacking = '$stacking'";
      }
    }else{
      if($stacking == ""){
        $rate_stmt = "select * from rf_storage_rate where from_shipping_line is null and shipping_line is null and customer_id is null and stacking is null";
      }else{
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
// function *only* called (as of right now, Dec2008) for customer 1986.
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
function getCommodity($receiver_id, $from_shipping_line, $to_shipping_line, $receiving_type, $commodity_code){
  // Default to 0000
  $return = "0000";

//  global $conn;
//  $func_cursor = ora_open($conn);

  // Basically, we see which commodity code to use for these items using a
  // bunch of if statements- this is all included in documentation from finance
  // Updated per Finance  -- LFW, 3/11/05

  /*
  Please use - 5198 for trucked in cargo (Pac Seaways, and customers other than Dole)
               5298 for trucked in cargo Dole

               5100 for Lauritzen cargo on a Pac Seaway vessel
               5101 for PacSeaway cargo
               5103 for Dole cargo on a Pac Seaway vessel

               5200 for Lauritzen cargo on a Dole ship      
               5201 for Pac Seaway cargo on a Dole Ship
               5203 for Dole Cargo   
  */

	// edit Adam Walter, Dec 2008.  Patched in to handle the "exception" of brazillians grapes, which despite
	// being part of the chilena system, arent actually part of the chilean system...?
  if($receiving_type == "T" && ($commodity_code == "5310" || $commodity_code == "5305" || $commodity_code == "1299")){
	  return $commodity_code;
  }

  // clementines get no conversion.
  if($from_shipping_line == "6000"){
	  return $commodity_code;
  }

  if($to_shipping_line == ""){
    // Trucked in
    if($receiver_id == "453" || $from_shipping_line == "453"){    // Dole
      $return = "5298";  
    } else {
      // Pac-SW, by default   -- LFW, Per Finance, 3/11/05
      $return = "5198";
    }
  }elseif($to_shipping_line == "8091"){  // Pac-Sw
    if($from_shipping_line == "8010"){   // Lauritzen
      $return = "5100";
    }elseif($from_shipping_line == "453"){    // Dole
      $return = "5103";
	}elseif($commodity_code == "7082"){
	  $return = "5013";
	}elseif($commodity_code == "7081"){
	  $return = "5011";
    }else{
      // Pac-Sw, by default   -- LFW, 3/14/05
      $return = "5101";
    }
	
  }elseif($to_shipping_line == "8010" || $to_shipping_line == "8063"){ // Added Adam Walter, 12/2006
	  $return = "5101";

  }elseif($from_shipping_line == "453"){ // Dole
    if($from_shipping_line == "8091"){   // Pac-Sw
      $return = "5201";
    }elseif($from_shipping_line == "8010"){   // Lauritzen
      $return = "5200";
    }else{ 
      // Dole, by default     -- LFW, 3/14/05
      $return = "5203";
    }
  }elseif($from_shipping_line == "8454"){ // new oppy fruits
		if($commodity_code == "8081"){
			$return = "5409";
		}elseif($commodity_code == "8105"){
			$return = "5411";
		}
  }

  return $return;
}

function pallet_exception($pallet_id, $receiver_id, $service_code, $arrival_num){
// this function checks a table to see if any given combination of factors for this current pallet
// means it is exempt from storage bills.
// returns TRUE if it is an exception (I.E. should not be billed)

	global $conn;
	$cursor3 = ora_open($conn);        // other use
	$cursor4 = ora_open($conn);        // other use

	$sql = "SELECT * FROM RF_BILLING_EXCEPTIONS";
	$ora_success = ora_parse($cursor3, $sql);
	$ora_success = ora_exec($cursor3);
	while(ora_fetch_into($cursor3, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".$pallet_id."'
				AND RECEIVER_ID = '".$receiver_id."'
				AND ARRIVAL_NUM = '".$arrival_num."'
				AND ".$row['WHERE_CLAUSE'];
		$ora_success = ora_parse($cursor4, $sql);
		$ora_success = ora_exec($cursor4);
		if($pallet_id == "0300459128"){
//			echo $sql."<br>";
		}
		if(ora_fetch_into($cursor4, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//			echo $pallet_id." "."TRUE"."<br>";
			return TRUE;
		}
	}


//	echo $pallet_id." "."FALSE"."<br>";
	return FALSE;
}


function SetTruckRate($cursor){
	// first, update truck cargo with a billing date with specific instruction in RF_STORAGE_RATE

	$sql = "UPDATE CARGO_TRACKING A
			SET BILLING_STORAGE_DATE = DATE_RECEIVED 
			WHERE BILLING_STORAGE_DATE IS NULL
			AND RECEIVING_TYPE = 'T'
			AND WAREHOUSE_LOCATION = 'A2'
			AND COMMODITY_CODE LIKE '8%'";
	  $ora_success = ora_parse($cursor, $sql);
	  $ora_success = ora_exec($cursor);
	  database_check($ora_success, "Unable to set billing date for trucked in cargo2");
	
	
	$sql = "UPDATE CARGO_TRACKING A
			SET BILLING_STORAGE_DATE = DATE_RECEIVED + 
				(SELECT FREETIME
				FROM RF_STORAGE_RATE B
				WHERE A.COMMODITY_CODE = B.COMMODITY_CODE
				AND A.RECEIVER_ID = B.CUSTOMER_ID
				AND B.RECEIVE_TYPE = 'T')
			WHERE BILLING_STORAGE_DATE IS NULL
			AND RECEIVING_TYPE = 'T'";
	  $ora_success = ora_parse($cursor, $sql);
	  $ora_success = ora_exec($cursor);
	  database_check($ora_success, "Unable to set billing date for trucked in cargo1");

	// then, set date for all other trucked in cargo based on "default" in RF_STORAGE_RATE
	$sql = "UPDATE CARGO_TRACKING A
			SET BILLING_STORAGE_DATE = DATE_RECEIVED + 
				(SELECT FREETIME
				FROM RF_STORAGE_RATE B
				WHERE B.COMMODITY_CODE IS NULL
				AND B.CUSTOMER_ID IS NULL
				AND B.RECEIVE_TYPE = 'T')
			WHERE BILLING_STORAGE_DATE IS NULL
			AND RECEIVING_TYPE = 'T'";
	  $ora_success = ora_parse($cursor, $sql);
	  $ora_success = ora_exec($cursor);
	  database_check($ora_success, "Unable to set billing date for trucked in cargo2");

}
?>
