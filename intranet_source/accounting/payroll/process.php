<?
// Let us check it from the very beginning

   // Lynn F. Wang
   // Purpose- Payroll Accrual Batch Generator for Oracle Applications-GL
   // Creates a Journal to be pasted into Oracle ADI's Journal entry application.
   // Start - 21-JAN-03
   // End   - 03-MAR-03

/* Adam Walter, Feb 2007
*  This code was designed with a lot of hard-coded factors, which led to a lot of
*  Modifications over the years, and a bunch of annoying-off finance reports.
*  I am adjusting it to remove such items.
*
*********************************************************************************************/

   // check user authrization
   include("pow_session.php");

   // these "variables" are removed as of Feb 2007, and replaced farther down with non-hard-coded versions.
//   $pay_rate = 16.7;
//   $benefits = .28;
//   $ot_benefits = .0765;

   // generate plain text file
   header("Content-type: text/plain");
//   header("Content-type: application/vnd.ms-excel");
   header("Content-disposition: attachment; filename=payroll_accrual.txt");
//   header("Content-disposition: attachment; filename=payroll_accrual.xls");

   set_time_limit(320);   	// make reasonably sure the script does not time out on large files

   $start_date = trim($HTTP_POST_VARS["start_date"]);
   $end_date = trim($HTTP_POST_VARS["end_date"]);

   $total = 0;
   $benefits_total = 0;

   if ($start_date == "" || $end_date == "") {
      printf("Start Date and End Date must be entered before we can go further!");
      exit;
   }

   // connect to LCS database
   $ora_conn = ora_logon("LABOR@LCS", "LABOR");
   if (!$ora_conn) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_conn));
      exit;
   }
   // Connect to BNI
   $ora_connBNI = ora_logon("SAG_OWNER@BNI", "SAG");
   if (!$ora_connBNI) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_connBNI));
      exit;
   }

   // create a cursor
   $cursor = ora_open($ora_conn);
   if (!$cursor) {
      printf("Error opening a cursor on Oracle Server: ");
      printf(ora_errorcode($cursor));
      exit;
   }
   $BNIcursor = ora_open($ora_connBNI);
   if (!$BNIcursor) {
      printf("Error opening a cursor on Oracle Server: ");
      printf(ora_errorcode($BNIcursor));
      exit;
   }

   // Build Data Array.  Unchanged since 2007.
   $sql = "select H.EARNING_TYPE_ID, H.SERVICE_CODE, H.COMMODITY_CODE, H.EQUIPMENT_ID, H.LOCATION_ID, H.SPECIAL_CODE, H.DURATION, H.REG_HRS, H.OT_HRS, E.CERIDIAN_TYPE_ID, E.EMPLOYEE_TYPE_ID from HOURLY_DETAIL H, EMPLOYEE E where H.EMPLOYEE_ID = E.EMPLOYEE_ID AND (HIRE_DATE >= to_date('" . $start_date . "', 'MM/DD/YYYY') and HIRE_DATE <= to_date('" . $end_date . "', 'MM/DD/YYYY')) ORDER BY E.EMPLOYEE_TYPE_ID";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   $i = 0;
   while(ora_fetch($cursor)){
     $earning_type[$i] = ora_getcolumn($cursor, 0);
     $service_code[$i] = ora_getcolumn($cursor, 1);
     $commodity_code[$i] = ora_getcolumn($cursor, 2);
     $equipment_id[$i] = ora_getcolumn($cursor, 3);
     $location_id[$i] = ora_getcolumn($cursor, 4);
     $special_code[$i] = ora_getcolumn($cursor, 5);
     $duration[$i] = ora_getcolumn($cursor, 6);
     $reg_hrs[$i] = ora_getcolumn($cursor, 7);
     $ot_hrs[$i] = ora_getcolumn($cursor, 8);
     $ceridian_type[$i] = ora_getcolumn($cursor, 9);
     $employee_type[$i] = ora_getcolumn($cursor, 10);
     // Business rule #1 Don't take the value if there is no hours to log...
     if(($duration[$i] + $reg_hrs[$i] + $ot_hrs[$i]) > 0){
       $i++;
     }
   }
   $total_amt = 0;
   // Make lines for each entry
   for($x = 0; $x < $i; $x++){
     // Reset the values
     $gl = ""; $service = ""; $comm = ""; $asst = ""; $other = ""; $amount = "";
     $description = "";
//     $ot = 0;

     $service = $service_code[$x];
     // Reset the commodity code
     if($commodity_code[$x] == "9699"){
        $commodity_code[$x] = "0000";
     }
     if($commodity_code[$x] == "0"){
        $commodity_code[$x] = "0000";
     }
     $comm = $commodity_code[$x];

     // Get a location code
     $loc_sql1 = "select location_code from location_category where location_id = '" . $location_id[$x] . "'";
     $statement = ora_parse($cursor, $loc_sql1);
     ora_exec($cursor);
     while(ora_fetch($cursor)){
       $loc = ora_getcolumn($cursor, 0);
     }

     // Use a location code to get an asset code
     if($loc != ""){
       $loc_sql = "select asset_code from asset_profile where service_location_code = '" . $loc . "'";
       $statement = ora_parse($BNIcursor, $loc_sql);
       ora_exec($BNIcursor);
       while(ora_fetch($BNIcursor)){
         $asst = ora_getcolumn($BNIcursor, 0);
       }
     }
     // After all that, we better have an asset code- but in case...
     if($asst == ""){
       $asst = "0000";
     }

     // Freezer pay, or some special stuff?
// Got rid of this Feb 2007
/*	 if($special_code[$x] == "1401"){
       $other = "50";
     }
     else{
*/       $other = "00";
 //    }

     // Check the Rules from the old Payroll Accrual Program
     // Actually, this is a lot eaiser- we just get a description and set the
     // GL Code :)
     $description = "Payroll Accrual: $employee_type[$x] $earning_type[$x]";

	 
//	 this is the biggest example of hard-coded ridiculousness I have ever seen.  And as such, it's going away Feb 2007
/*	 switch($employee_type[$x]){
       case "CASB":
         if($earning_type[$x] == "OT"){
           $gl = "4052";
           $ot = 1;
         }
         else{
           $gl = "4022";
         }
         break;
       case "CASC":
         if($earning_type[$x] == "OT"){
           $gl = "4060";
           $ot = 1;
         }
         else{
           $gl = "4030";
         }
         break;
       case "CAS":
         if($earning_type[$x] == "OT"){
           $gl = "4060";
           $ot = 1;
         }
         else{
           $gl = "4030";
         }
         break;
       case "REGR":
         if($earning_type[$x] == "OT"){
           $gl = "4021";
           $ot = 1;
         }
         else{
           $gl = "4051";
         }
         break;
       case "GUARD":
         if($earning_type[$x] == "OT"){
           $gl = "4055";
           $ot = 1;
         }
         else{
           $gl = "4025";
         }
         break;
       case "CASG":
         if($earning_type[$x] == "OT"){
           $gl = "4055";
           $ot = 1;
         }
         else{
           $gl = "4025";
         }
         break;
       case "SUPV":
         if($earning_type[$x] == "OT"){
           $gl = "4048";
           $ot = 1;
         }
         else{
           $gl = "4018";
         }
         break;
       default:
         if($earning_type[$x] == "OT"){
           $gl = "4021";
           $ot = 1;
         }
         else{
           $gl = "4051";
         }
     } // switch()

     // Figure in the dollar amount
     if($ot == 1){
       $amount = $duration[$x] * (1.5 * $pay_rate);
       $benefits_total += $amount * $ot_benefits;
     }
     else{
       $amount = $duration[$x] * $pay_rate;
       $benefits_total += $amount * $benefits;
     }
*/
	$sql = "SELECT * FROM PARTIAL_WEEK_ACCRUAL_MAP WHERE EMP_TYPE = '".$employee_type[$x]."' AND PAY_CODE = '".$earning_type[$x]."'";
	$statement = ora_parse($BNIcursor, $sql);
	ora_exec($BNIcursor);
	ora_fetch_into($BNIcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$gl = $row['GL_CODE'];
	$pay_rate = $row['AVERAGE_WAGE'];
	$multiplier = $row['MULTIPLIER'];

	$amount = $duration[$x] * $pay_rate * $multiplier;


    // Round and figure a total...
    $amount = round($amount, 2);
    $total = $total + $amount;
    // Ok, print the line...
    printf("00,%s,%s,%s,%s,%s,%s,,%s\n", $gl, $service, $comm, $asst, $other, $amount, $description);
//    printf("00\t%s\t%s\t%s\t%s\t%s\t%s\t\t%s\n", $gl, $service, $comm, $asst, $other, $amount, $description);


	// Adam Walter, Jan 2008.
	// The "benefits" lines are going back in, but not in the hard-coded method above.
	// One benefits line for each normal line
	$sql = "SELECT BENEFIT_ALLOCATION FROM FINANCE_ADP_CONVERSION WHERE GL_ACCT = '".$gl."'";
	$statement = ora_parse($BNIcursor, $sql);
	ora_exec($BNIcursor);
	ora_fetch_into($BNIcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$benefit_allocation = $row['BENEFIT_ALLOCATION'];
	$benefit_line_amount = round($amount * $benefit_allocation / 100, 2);
	$benefits_description = "Benefits (".$benefit_allocation."%)";
	$benefits_total += $benefit_line_amount;

	printf("00,4199,%s,%s,%s,%s,%s,,%s\n", $service, $comm, $asst, $other, $benefit_line_amount, $benefits_description);
//	printf("00\t4199\t%s\t%s\t%s\t%s\t%s\t\t%s\n", $service, $comm, $asst, $other, $benefit_line_amount, $benefits_description);

   } // for()
   // Next-to-last line will have benefits (Debit)
   // Feb 2007:  Not anymroe it doesnt
//   printf("00,2320,0000,0000,0000,00,%s,,Benefits Total", $benefits_total);
   // Last line will have the Credit amount (total)
   $total = round($total, 2);
   printf("00,2320,0000,0000,0000,00,,%s,Payroll Accrual\n", $total);
//   printf("00\t2320\t0000\t0000\t0000\t00\t\t%s\tPayroll Accrual\n", $total);
   $benefits_total = round($benefits_total, 2);
   printf("00,4199,9764,0000,0000,00,,%s,Benefits Accrual", $benefits_total);
//   printf("00\t4199\t0000\t0000\t0000\t00\t\t%s\tBenefits Accrual", $benefits_total);

?>
