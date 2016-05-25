<?
  // Seth Morecraft
  // Purpose- Fork Lift Expense GL batch generation
  // Creates a Journal to be pasted into ADI's Journal entry application.
  // Start - 21-JAN-03
  // End   - 21-JAN-03

  include("pow_session.php");

   $glCode = "5199";
   $LTSubAccount = "97LT";

   header("Content-type: text/plain");
   header("Content-disposition: attachment; filename=forklift_expense.txt");
   set_time_limit(120);   	// make reasonably sure the script does not time out on large files

   $date1 = trim($HTTP_POST_VARS['date1']);
   $date2 = trim($HTTP_POST_VARS['date2']);
   $expense = trim($HTTP_POST_VARS['expense']);

   // Log onto LCS (php will handle errors)
   $conn = ora_logon("LABOR@LCS", "LABOR");
   $cursor = ora_open($conn);
   // Log onto BNI
   $connBNI = ora_logon("SAG_OWNER@BNI", "SAG");
   $cursorBNI = ora_open($connBNI);

   $lift_sql = "select duration, location_id, service_code, commodity_code, hire_date 
				from hourly_detail 
				where hire_date between to_date('" .  $date1 . "', 'MM/DD/YY') 
					and to_date('" . $date2 . "', 'MM/DD/YY') 
					and service_code like '6%' 
					and service_code like '%1'
					and commodity_code != '1221'";

   $statement = ora_parse($cursor, $lift_sql);
   ora_exec($cursor);
   
   $i = 0;
   while (ora_fetch($cursor)){
      // SEED all fields
      $duration = ora_getcolumn($cursor, 0);
      $amount = $duration * $expense;
      $amount_array[$i] = round($amount, 2);
      $location_array[$i] = ora_getcolumn($cursor, 1);
      $service_array[$i] = ora_getcolumn($cursor, 2);
      $commodity_array[$i] = ora_getcolumn($cursor, 3);
      $description_array[$i] = "Fork Lift expenses for " . ora_getcolumn($cursor, 4);
      $i++;
   }
   
   for($x = 0; $x < $i; $x++){
      // Refine location
      $loc_sql = "select location_code from location_category where location_id = '" . $location_array[$x] . "'";
      $statement = ora_parse($cursor, $loc_sql);
      ora_exec($cursor);
      $location_array[$x] = ora_getcolumn($cursor, 0);

      // Refine asset
      $asset_sql = "select asset_code from asset_profile where service_location_code = '" . $location_array[$x] . "'";
      $statement = ora_parse($cursorBNI, $asset_sql);
      ora_exec($cursorBNI);
      $asset = ora_getcolumn($cursorBNI, 0);
      if($asset == ""){
        $asset = "0000";
      }

      // Print the DEBIT line
      printf("00,%s,%s,%s,%s,00,%s,,%s\n", $glCode, $service_array[$x], $commodity_array[$x], $asset, $amount_array[$x], $description_array[$x]);
      // Print the CREDIT line
      printf("00,%s,%s,0000,0000,00,,%s,%s\n", $glCode, $LTSubAccount, $amount_array[$x], $description_array[$x]);
   }
   ora_close($cursor);
   ora_close($cursorBNI);
?>
