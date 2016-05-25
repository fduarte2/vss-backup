<?
// ADAM WALTER, April 2006.  Creates a tab-delimited file with a vessel productivity report
// to the same folder that the program resides in.
// this version of the program is different from the one found in
// inventory/productivity_report, as finance doesn't need as much information.
// The SQL statements here are rather ugly, as I need to pull off a conditional
// sql statement involving FOUR different tables.

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
/*
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }
*/

// NECESSARY FUNCTIONS
function compile_backhaul_hours($S, $E, $C, $type, $pay_lunch, $pay_dinner) {
// note:  for reasons I am totally unaware of, I was unable to pass $C straight into strtotime().  My go-around
// was to get the current date with an additional / in the oracle call (down in the main code), then pass it up here
// to do a split command on it, and a combination of date and mktime to put it back together.
	$running_REG_total = 0;
	$running_OT_total = 0;
	$running_DT_total = 0;
	$time_loop = 0;

//	echo $S."ee \n";
//	echo $C."dd \n";
	$temp = split("/", $C);
    $Cdate = date('m/d/Y', mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
	$is_weekend = date("l", mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
//	echo $is_weekend." BH \n";



	$start = strtotime($S);
	$end = strtotime($E);
//	echo $start."ats \n";
//	echo $end."ate \n";
	$OneAM = strtotime($Cdate) + (3600 * 1);
	$SixAM = strtotime($Cdate) + (3600 * 6);
	$SevenAM = strtotime($Cdate) + (3600 * 7);
	$noon = strtotime($Cdate) + (3600 * 12);
	$OnePM = strtotime($Cdate) + (3600 * 13);
	$SixPM = strtotime($Cdate) + (3600 * 18);
	$SevenPM = strtotime($Cdate) + (3600 * 19);
	$midnight = strtotime($Cdate); 
//	$next_midnight = strtotime($Cdate." 11:59 PM") + 60;

	for($time_loop = $start; $time_loop < $end; $time_loop += 900){
		if($time_loop >= $midnight && $time_loop < $OneAM){
			$running_DT_total++;
		}
		if($time_loop >= $SixAM && $time_loop < $SevenAM){
			$running_DT_total++;
		}
		if($time_loop >= $noon && $time_loop < $OnePM && $pay_lunch != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $SixPM && $time_loop < $SevenPM && $pay_dinner != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $OneAM && $time_loop < $SixAM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenPM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenAM && $time_loop < $noon){
			$running_REG_total++;
		}
		if($time_loop >= $OnePM && $time_loop < $SixPM){
			$running_REG_total++;
		}
	}

	if($is_weekend == "Saturday" || $is_weekend == "Sunday"){
		$running_OT_total += $running_REG_total;
		$running_REG_total = 0;
	}



	if($type == 'ST'){
		return ($running_REG_total / 4);
	}
	if($type == 'OT'){
		return ($running_OT_total / 4);
	}
	if($type == 'DT'){
		return ($running_DT_total / 4);
	}
}

function compile_transST_hours($S, $E, $C, $type, $pay_lunch, $pay_dinner) {
	$running_REG_total = 0;
	$running_OT_total = 0;
	$running_DT_total = 0;
	$time_loop = 0;


	$start = strtotime($S);
	$end = strtotime($E);
	$temp = split("/", $C);
    $Cdate = date('m/d/Y', mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
	$is_weekend = date("l", mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
//	echo $is_weekend." trans \n";


//	echo $start."\n";
//	echo $end."\n";
//	echo $C."\n";
	$OneAM = strtotime($Cdate) + (3600 * 1);
	$SixAM = strtotime($Cdate) + (3600 * 6);
	$SevenAM = strtotime($Cdate) + (3600 * 7);
	$EightAM = strtotime($Cdate) + (3600 * 8);
	$noon = strtotime($Cdate) + (3600 * 12);
	$OnePM = strtotime($Cdate) + (3600 * 13);
	$FivePM = strtotime($Cdate) + (3600 * 17);
	$SixPM = strtotime($Cdate) + (3600 * 18);
	$SevenPM = strtotime($Cdate) + (3600 * 19);
	$midnight = strtotime($Cdate); 
//	$next_midnight = strtotime($C." 11:59 PM") + 60;

	for($time_loop = $start; $time_loop < $end; $time_loop += 900){
		if($time_loop >= $midnight && $time_loop < $OneAM){
			$running_DT_total++;
		}
		if($time_loop >= $SixAM && $time_loop < $SevenAM){
			$running_DT_total++;
		}
		if($time_loop >= $noon && $time_loop < $OnePM && $pay_lunch != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $SixPM && $time_loop < $SevenPM && $pay_dinner != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $OneAM && $time_loop < $SixAM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenAM && $time_loop < $EightAM){
			$running_OT_total++;
		}
		if($time_loop >= $FivePM && $time_loop < $SixPM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenPM){
			$running_OT_total++;
		}
		if($time_loop >= $EightAM && $time_loop < $noon){
			$running_REG_total++;
		}
		if($time_loop >= $OnePM && $time_loop < $FivePM){
			$running_REG_total++;
		}
	}

	if($is_weekend == "Saturday" || $is_weekend == "Sunday"){
		$running_OT_total += $running_REG_total;
		$running_REG_total = 0;
	}

	if($type == 'ST'){
		return ($running_REG_total / 4);
	}
	if($type == 'OT'){
		return ($running_OT_total / 4);
	}
	if($type == 'DT'){
		return ($running_DT_total / 4);
	}
}





















	$vessel_number = $HTTP_POST_VARS["vessel_number"];
//  $tonnage = $HTTP_POST_VARS["tonnage"];
	$WAC = $HTTP_POST_VARS["WAC"];
//  $completion_date = $HTTP_POST_VARS["completion_date"];
//  $completion_time = $HTTP_POST_VARS["completion_time"];
//  $commodity = $HTTP_POST_VARS["commodity"];

//  $completion_fulltime = $completion_date." ".$completion_time;
//  $completion_timestamp = strtotime($completion_fulltime);

  $current_date = 0;
  $total_for_date = 0;
  $BH_REG_total = 0;
  $BH_OT_total = 0;
  $BH_DT_total = 0;
  $ST_REG_total = 0;
  $ST_OT_total = 0;
  $ST_DT_total = 0;
  $TR_REG_total = 0;
  $TR_OT_total = 0;
  $TR_DT_total = 0;
  $BH_REG_current = 0;
  $BH_OT_current = 0;
  $BH_DT_current = 0;
  $ST_REG_current = 0;
  $ST_OT_current = 0;
  $ST_DT_current = 0;
  $TR_REG_current = 0;
  $TR_OT_current = 0;
  $TR_DT_current = 0;
  $nice_today = date('m/d/Y')."\t".date('h:i a');
  





?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hours Paid vs. Billed Amount Report:
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
  $conn = ora_logon("labor@LCS", "labor");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $conn2 = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn2 < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn2));
			printf("</body></html>");
			exit;
  }

  $backhaul_hours = 0;
  $standby_hours = 0;
  $transfer_hours = 0;

  $current_date_cursor = ora_open($conn);
  $looping_date_cursor = ora_open($conn);
  $vessel_cursor = ora_open($conn2);
  $advanced_billing_cursor = ora_open($conn2);
  $amount_invoiced_cursor = ora_open($conn2);
  $amount_credited_cursor = ora_open($conn2);
  $short_term_cursor = ora_open($conn2);


// get the vessel name
// NOTE:  there are 3 entries in the database that start with a - sign.  This code will choke on those,
// BUT we should never have to use them for entering a ship.  If we do, the error will be instead of a ship name, 
// we get a single digit number
  $sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$vessel_number;
  $statement = ora_parse($vessel_cursor, $sql);
  ora_exec($vessel_cursor);
  ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $vessel_array = split("-", $vessel_row['VESSEL_NAME']);
  $vessel_name = $vessel_array[1];

// get the number of hours that are backhaul - backhaul
//  $sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID  AND SERVICE_CODE IN ('6111', '6114', '6119', '6110')";
//  echo $sql."\n";
//  $statement = ora_parse($backhaul_cursor, $sql);
//  ora_exec($backhaul_cursor);
//  ora_fetch_into($backhaul_cursor, $backhaul_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//  $backhaul_hours = $backhaul_row['HOURS'];
//  echo $backhaul_row['HOURS']."\n";

  $filename = "Vessel".$vessel_number.".xls";
  $handle = fopen($filename, "w");
  if (!$handle){
  	echo "File ".$filename." could not be opened, please contact TS.\n";
	exit;
  } else {
	fwrite($handle, "\t\t\tDiamond State Port Corp.\t\t\t\t\tReport Date:\n");
	fwrite($handle, "\t\t\tHours Paid vs. Billed Amount\t\t\t\t\t".$nice_today."\n\n\n");
	fwrite($handle, "Vessel: ".$vessel_number."\t\t".$vessel_name."\n\n");
	fwrite($handle, "Hours:\n");
	fwrite($handle, "\t\tBackhaul\t\t\tStandby\t\t\tCold-Storage Transfer\t\t\tTotal\n");
	fwrite($handle, "Date\t\tST\tOT\tDT\tST\tOT\tDT\tST\tOT\tDT\t\n");




// figure out the unique set of dates worked
//  $sql = "SELECT DISTINCT to_char(HIRE_DATE, 'MM/DD/YYYY/hh24:mi') THE_DATE FROM HOURLY_DETAIL WHERE VESSEL_ID = '".$vessel_number."' AND SERVICE_CODE IN ('6110', '6111', '6114', '6119', '6710', '6711', '6714', '6719', '6130', '6131', '6134', '6139') ORDER BY THE_DATE";
  $sql = "SELECT DISTINCT to_char(HIRE_DATE, 'MM/DD/YYYY/hh24:mi') THE_DATE FROM SF_HOURLY_DETAIL WHERE VESSEL_ID = '".$vessel_number."' AND SERVICE_CODE IN ('6110', '6111', '6114', '6119', '6710', '6711', '6714', '6719', '6130', '6131', '6134', '6139') ORDER BY THE_DATE";
  $statement = ora_parse($current_date_cursor, $sql);
  ora_exec($current_date_cursor);
  while(ora_fetch_into($current_date_cursor, $current_date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// reset all necessary counters, but NOT the column totals
	$current_date = $current_date_row['THE_DATE'];
	$temp = split("/", $current_date);
    $nice_date = date('d-M-y', mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
	$total_for_date = 0;
    $BH_REG_current = 0;
    $BH_OT_current = 0;
    $BH_DT_current = 0;
    $ST_REG_current = 0;
    $ST_OT_current = 0;
    $ST_DT_current = 0;
    $TR_REG_current = 0;
    $TR_OT_current = 0;
    $TR_DT_current = 0;

//	$sql = "SELECT SERVICE_CODE, TO_CHAR(START_TIME, 'MM/DD/YYYY hh24:mi') STARTING, TO_CHAR(END_TIME, 'MM/DD/YYYY hh24:mi') ENDING FROM HOURLY_DETAIL WHERE VESSEL_ID = '".$vessel_number."' AND HIRE_DATE = to_date('".$current_date."', 'MM/DD/YYYY/hh24:mi') ";
    $sql = "SELECT SERVICE_CODE, TO_CHAR(START_TIME, 'MM/DD/YYYY hh24:mi') STARTING, TO_CHAR(END_TIME, 'MM/DD/YYYY hh24:mi') ENDING, PAY_LUNCH, PAY_DINNER FROM SF_HOURLY_DETAIL WHERE VESSEL_ID = '".$vessel_number."' AND HIRE_DATE = to_date('".$current_date."', 'MM/DD/YYYY/hh24:mi')";
	$statement = ora_parse($looping_date_cursor, $sql);
    ora_exec($looping_date_cursor);
    while(ora_fetch_into($looping_date_cursor, $looping_date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
// use a function to sum the results
		if($looping_date_row['SERVICE_CODE'] == '6110' || $looping_date_row['SERVICE_CODE'] == '6111' || $looping_date_row['SERVICE_CODE'] == '6114' || $looping_date_row['SERVICE_CODE'] == '6119'){
			$BH_REG_current += compile_backhaul_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'ST', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
			$BH_OT_current += compile_backhaul_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'OT', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
			$BH_DT_current += compile_backhaul_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'DT', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
		}
		if($looping_date_row['SERVICE_CODE'] == '6710' || $looping_date_row['SERVICE_CODE'] == '6711' || $looping_date_row['SERVICE_CODE'] == '6714' || $looping_date_row['SERVICE_CODE'] == '6719'){
			$ST_REG_current += compile_transST_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'ST', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
			$ST_OT_current += compile_transST_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'OT', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
			$ST_DT_current += compile_transST_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'DT', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
		}
		if($looping_date_row['SERVICE_CODE'] == '6130' || $looping_date_row['SERVICE_CODE'] == '6131' || $looping_date_row['SERVICE_CODE'] == '6134' || $looping_date_row['SERVICE_CODE'] == '6139'){
			$TR_REG_current += compile_transST_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'ST', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
			$TR_OT_current += compile_transST_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'OT', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
			$TR_DT_current += compile_transST_hours($looping_date_row['STARTING'], $looping_date_row['ENDING'], $current_date, 'DT', $looping_date_row['PAY_LUNCH'], $looping_date_row['PAY_DINNER']);
		}
	}

	$total_for_date = ($BH_REG_current + $BH_OT_current + $BH_DT_current + $ST_REG_current + $ST_OT_current + $ST_DT_current + $TR_REG_current + $TR_OT_current + $TR_DT_current);
	fwrite($handle, $nice_date."\t\t".number_format($BH_REG_current, 2)."\t".number_format($BH_OT_current, 2)."\t".number_format($BH_DT_current, 2)."\t".number_format($ST_REG_current, 2)."\t".number_format($ST_OT_current, 2)."\t".number_format($ST_DT_current, 2)."\t".number_format($TR_REG_current, 2)."\t".number_format($TR_OT_current, 2)."\t".number_format($TR_DT_current, 2)."\t".number_format($total_for_date, 2)."\n");

    $BH_REG_total += $BH_REG_current;
    $BH_OT_total += $BH_OT_current;
    $BH_DT_total += $BH_DT_current;
    $ST_REG_total += $ST_REG_current;
    $ST_OT_total += $ST_OT_current;
    $ST_DT_total += $ST_DT_current;
    $TR_REG_total += $TR_REG_current;
    $TR_OT_total += $TR_OT_current;
    $TR_DT_total += $TR_DT_current;
  }
  	$overall_total_total = $BH_REG_total + $BH_OT_total + $BH_DT_total + $ST_REG_total + $ST_OT_total + $ST_DT_total + $TR_REG_total + $TR_OT_total + $TR_DT_total;

	$WAC_OT_ADJUST = 1.17; // 17% increase in WAC for OT
	$WAC_DT_ADJUST = 1.5; // 50% increase in WAC for DT

	$WAC_ST_TOTAL = $WAC * ($BH_REG_total + $ST_REG_total + $TR_REG_total);
	$WAC_OT_TOTAL = $WAC * ($BH_OT_total + $ST_OT_total + $TR_OT_total) * $WAC_OT_ADJUST; 
	$WAC_DT_TOTAL = $WAC * ($BH_DT_total + $ST_DT_total + $TR_DT_total) * $WAC_DT_ADJUST; 
	$WAC_OVERALL_TOTAL = $WAC_ST_TOTAL + $WAC_OT_TOTAL + $WAC_DT_TOTAL;


// this is a horrible way to format this, but PHP doesnt provide a varialbe-decimal count equivalent of number_format to my knowledge
// Nor do they have a built-in fuction to determine the # of decimal points.  This is simpler than writing my own function.
// I am guaranteed that values will be 1/4 hour increments, so 2 decimal palces is good.
	if(is_int($BH_REG_total)){
		$BH_REG_total = number_format($BH_REG_total);
	} else {
		$BH_REG_total = number_format($BH_REG_total, 2);
	}
	if(is_int($BH_OT_total)){
		$BH_OT_total = number_format($BH_OT_total);
	} else {
		$BH_OT_total = number_format($BH_OT_total, 2);
	}
	if(is_int($BH_DT_total)){
		$BH_DT_total = number_format($BH_DT_total);
	} else {
		$BH_DT_total = number_format($BH_DT_total, 2);
	}
	if(is_int($ST_REG_total)){
		$ST_REG_total = number_format($ST_REG_total);
	} else {
		$ST_REG_total = number_format($ST_REG_total, 2);
	}
	if(is_int($ST_OT_total)){
		$ST_OT_total = number_format($ST_OT_total);
	} else {
		$ST_OT_total = number_format($ST_OT_total, 2);
	}
	if(is_int($ST_DT_total)){
		$ST_DT_total = number_format($ST_DT_total);
	} else {
		$ST_DT_total = number_format($ST_DT_total, 2);
	}
	if(is_int($TR_REG_total)){
		$TR_REG_total = number_format($TR_REG_total);
	} else {
		$TR_REG_total = number_format($TR_REG_total, 2);
	}
	if(is_int($TR_OT_total)){
		$TR_OT_total = number_format($TR_OT_total);
	} else {
		$TR_OT_total = number_format($TR_OT_total, 2);
	}
	if(is_int($TR_DT_total)){
		$TR_DT_total = number_format($TR_DT_total);
	} else {
		$TR_DT_total = number_format($TR_DT_total, 2);
	}
	if(is_int($overall_total_total)){
		$overall_total_total = number_format($overall_total_total);
	} else {
		$overall_total_total = number_format($overall_total_total, 2);
	}

  fwrite($handle, "\nTotals:\t\t".$BH_REG_total."\t".$BH_OT_total."\t".$BH_DT_total."\t".$ST_REG_total."\t".$ST_OT_total."\t".$ST_DT_total."\t".$TR_REG_total."\t".$TR_OT_total."\t".$TR_DT_total."\t".$overall_total_total."\n\n");
  fwrite($handle, "Total From Above:\t\tHours\t\tWAC\t\tCost\n");
  fwrite($handle, "ST Total:\t\t".($BH_REG_total + $ST_REG_total + $TR_REG_total)."\t\t".money_format('%.2n', $WAC)."\t\t".money_format('%.2n', $WAC_ST_TOTAL)."\n");
  fwrite($handle, "OT Total:\t\t".($BH_OT_total + $ST_OT_total + $TR_OT_total)."\t\t".money_format('%.2n', ($WAC * $WAC_OT_ADJUST))."\t\t".money_format('%.2n', $WAC_OT_TOTAL)."\n");
  fwrite($handle, "DT Total:\t\t".($BH_DT_total + $ST_DT_total + $TR_DT_total)."\t\t".money_format('%.2n', ($WAC * $WAC_DT_ADJUST))."\t\t".money_format('%.2n', $WAC_DT_TOTAL)."\n");
  fwrite($handle, "\t\t--------------------------------------------------------------------------------------\n");
  fwrite($handle, "\tTotal:\t".$overall_total_total."\t\t"."\t\t".money_format('%.2n', $WAC_OVERALL_TOTAL)."\n\n\n");


// above was the hours worked summary, below is the billed tickets summary
  $printed_date_output = "";
  $total_ship_billed = 0;

  fwrite($handle, "Billing:\n");
  fwrite($handle, "Date\t\tType\t\t\tAmount\n\n");
// get the advanced billed portion
  $sql = "SELECT CUSTOMER_ID, COMMODITY_CODE, SERVICE_CODE, SUM(SERVICE_AMOUNT) TOTAL_AMOUNT FROM BILLING WHERE LR_NUM = '".$vessel_number."' AND (BILLING_TYPE = 'BACKHAUL') AND SERVICE_STATUS = 'INVOICED' AND (SERVICE_CODE LIKE '611%' OR SERVICE_CODE LIKE '613%' OR SERVICE_CODE LIKE '671%') GROUP BY CUSTOMER_ID, COMMODITY_CODE, SERVICE_CODE";
  $statement = ora_parse($advanced_billing_cursor, $sql);
  ora_exec($advanced_billing_cursor);
  while(ora_fetch_into($advanced_billing_cursor, $advanced_billing_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  // get the GL_CODE ratio of total billed amount for advanced to backhaul billed for advanced.
	  $sql = "SELECT GB.AMOUNT / GH.AMOUNT THE_AMOUNT FROM GL_ALLOCATION_BODY GB, GL_ALLOCATION_HEADER GH WHERE GB.HEADER_ID = GH.ID AND GH.CUSTOMER_ID = '".$advanced_billing_row['CUSTOMER_ID']."' AND GB.COMMODITY_CODE = '".$advanced_billing_row['COMMODITY_CODE']."' AND GB.SERVICE_CODE = '".$advanced_billing_row['SERVICE_CODE']."'";
	  $statement = ora_parse($short_term_cursor, $sql);
	  ora_exec($short_term_cursor);
	  if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		  $mod = 1;
	  } else {
		  $mod = $short_term_row['THE_AMOUNT'];
	  }
	  $advance_billed = $advanced_billing_row['TOTAL_AMOUNT'] * $mod;
	  
	  $total_ship_billed += $advance_billed;
  }
  fwrite($handle, "---\t\tAdvanced\t\t\t".money_format('%.2n', $total_ship_billed)."\n");

  $sql = "SELECT SERVICE_DATE, LABOR_RATE_TYPE, SUM(SERVICE_AMOUNT) SERVICE_TOTAL FROM BILLING WHERE LR_NUM = '".$vessel_number."' AND BILLING_TYPE IN ('LABOR', 'MISC') AND SERVICE_STATUS = 'INVOICED' AND SERVICE_CODE IN ('6110', '6111', '6114', '6119', '6710', '6711', '6714', '6719', '6130', '6131', '6134', '6139') GROUP BY SERVICE_DATE, LABOR_RATE_TYPE ORDER BY SERVICE_DATE";
  $statement = ora_parse($amount_invoiced_cursor, $sql);
  ora_exec($amount_invoiced_cursor);
  ora_fetch_into($amount_invoiced_cursor, $amount_invoiced_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  if(!$amount_invoiced_row['SERVICE_DATE']){
	  fwrite($handle, "\nNo Invoiced Backhaul Labor tickets for this vessel\n");
  } else {
	  do {
		  if($amount_invoiced_row['LABOR_RATE_TYPE'] != ""){
			  $output = $amount_invoiced_row['LABOR_RATE_TYPE'];
		  } else {
			  $output = "Gang Rates";
		  }

		  $current_date_output = $amount_invoiced_row['SERVICE_DATE'];
		  if($printed_date_output != $current_date_output){
			  fwrite($handle, $current_date_output."\t\t".$output."\t\t\t".money_format('%.2n', $amount_invoiced_row['SERVICE_TOTAL'])."\n");
			  $printed_date_output = $current_date_output;
		  } else {
			  fwrite($handle, "\t\t".$output."\t\t\t".money_format('%.2n', $amount_invoiced_row['SERVICE_TOTAL'])."\n");
		  }
		  $total_ship_billed += $amount_invoiced_row['SERVICE_TOTAL'];
	  }
	  while(ora_fetch_into($amount_invoiced_cursor, $amount_invoiced_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
  }

  $sql = "SELECT SERVICE_DATE, SERVICE_AMOUNT FROM BILLING WHERE SERVICE_STATUS = 'CREDITMEMO' AND BILLING_TYPE = 'CM' AND LR_NUM = '".$vessel_number."' ORDER BY SERVICE_DATE";
  $statement = ora_parse($amount_credited_cursor, $sql);
  ora_exec($amount_credited_cursor);

  while(ora_fetch_into($amount_credited_cursor, $amount_credited_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	  fwrite($handle, $amount_credited_row['SERVICE_DATE']."\t\tCredit\t\t\t".$amount_credited_row['SERVICE_AMOUNT']."\n");
	  $total_ship_billed += $amount_credited_row['SERVICE_AMOUNT'];
  }

  fwrite($handle, "\nTotal:\t\t --- \t\t\t".money_format('%.2n', $total_ship_billed)."\n\n\n");
  }

// closing ledgers
  fwrite($handle, "Description of LCS Backhaul Hours (Service Codes 6110, 6111, 6114, 6119):\n");
  fwrite($handle, "ST:  7AM - noon / 1PM - 6PM\n");
  fwrite($handle, "OT:  1AM - 6AM / 7PM - 12AM\n");
  fwrite($handle, "DT:  6AM - 7AM / noon - 1PM / 6PM - 7PM / 12AM - 1AM\n");
  fwrite($handle, "Sat:  all OT except for DT\n");
  fwrite($handle, "Sun:  all OT except for DT\n\n");
  fwrite($handle, "Description of LCS Standby (Service Codes 6710, 6711, 6714, 6719) and Transfer (Service Codes 6130, 6131, 6134, 6139) Hours:\n");
  fwrite($handle, "ST:  8AM - noon / 1PM - 5PM\n");
  fwrite($handle, "OT:  1AM - 6AM / 7PM - 12AM\n");
  fwrite($handle, "OT:  7AM - 8AM / 5PM - 6PM\n");
  fwrite($handle, "DT:  6AM - 7AM / noon - 1PM / 6PM - 7PM / 12AM - 1AM\n");
  fwrite($handle, "Sat:  all OT except for DT\n");
  fwrite($handle, "Sun:  all OT except for DT\n\n");

  fwrite($handle, "Description of Billing:\n");
  fwrite($handle, "Advanced: Total billed amounts under GL_CODE type \"3071\"\n");
  fwrite($handle, "       COMBINED WITH amounts under BILLING_TYPE of \"BACKHAUL\"\n");
  fwrite($handle, "       And that have a service code in any of the above lists\n");
  fwrite($handle, "DT / ST / OT / MH / DF:  Total billed amounts under billing type \"Labor\"\n");



  fclose($handle);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<td><font size="3" face = "Verdana" color="#0066CC">Report Generated.</font></td>
	</tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana">Click <a href="<? echo $filename; ?>">here</a> to save the file.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
