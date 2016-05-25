<?
// ADAM WALTER, April 2006.  Creates a tab-delimited file with a vessel productivity report
// to the same folder that the program resides in.
//
// MAJOR EDIT:  January 18-19, 2007.
// It has been determined that some commodities are supposed to be grouped for the purpose
// of this report.  The "grouping" area is defined as GROUP_CODE in the COMMODITY_PROFILE table on BNI
// so I've had to modify the SQL statements to account for this.

  // All POW files need this session file included
/*  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
*/


/*
  $vessel_number = $HTTP_POST_VARS["vessel_number"];
  $completion_date = $HTTP_POST_VARS["completion_date"];
  $completion_time = $HTTP_POST_VARS["completion_time"];
  $commodity1 = $HTTP_POST_VARS["commodity1"];
  $commodity2 = $HTTP_POST_VARS["commodity2"];
  $commodity3 = $HTTP_POST_VARS["commodity3"];
  $commodity4 = $HTTP_POST_VARS["commodity4"];
  $commodity5 = $HTTP_POST_VARS["commodity5"];
  $tonnage1 = $HTTP_POST_VARS["tonnage1"];
  $tonnage2 = $HTTP_POST_VARS["tonnage2"];
  $tonnage3 = $HTTP_POST_VARS["tonnage3"];
  $tonnage4 = $HTTP_POST_VARS["tonnage4"];
  $tonnage5 = $HTTP_POST_VARS["tonnage5"];

  $commodity_input_array = array($commodity1, $commodity2, $commodity3, $commodity4, $commodity5);
  $tonnage_input_array = array($tonnage1, $tonnage2, $tonnage3, $tonnage4, $tonnage5); 

  $completion_fulltime = $completion_date." ".$completion_time;
  $completion_timestamp = strtotime($completion_fulltime);

  $productivity = 1;
  $budget = 1;
  $efficiency = 0;
  
*/





  $conn = ora_logon("labor@LCS", "labor");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $conn2 = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn2 = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn2 < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn2));
			printf("</body></html>");
			exit;
  }

  $generic_cursor = ora_open($conn2);
  $generic_cursor_LCS = ora_open($conn);
  $per_vessel_cursor = ora_open($conn2);	
  $main_data_cursor = ora_open($conn);
  $sum_hours_cursor = ora_open($conn);
  $distinct_employee_cursor = ora_open($conn);
  $backhaul_cursor = ora_open($conn);
  $vessel_cursor = ora_open($conn2);
  $budget_cursor = ora_open($conn2);
  $budget_cursor_LCS = ora_open($conn);
  $commodity_cursor = ora_open($conn2);
  $employee_name_cursor = ora_open($conn);
  $berth_cursor = ora_open($conn2);
  $commodity_name_cursor = ora_open($conn2);
  $vessel_update_cursor = ora_open($conn2);

// some universal variables
//  $non_spec_array = array();




// this is going to be one ginormous loop, as there may be multiple vessels that are awaiting reports.

  $sql = "SELECT SVC.*, to_char(TIME_COMPLETE1, 'MM/DD/YYYY HH12:MI AM') TIME1, to_char(TIME_COMPLETE2, 'MM/DD/YYYY HH12:MI AM') TIME2, to_char(TIME_COMPLETE3, 'MM/DD/YYYY HH12:MI AM') TIME3, to_char(TIME_COMPLETE4, 'MM/DD/YYYY HH12:MI AM') TIME4, to_char(TIME_COMPLETE5, 'MM/DD/YYYY HH12:MI AM') TIME5, to_char(TIME_ENTERED1, 'MM/DD/YYYY HH12:MI AM') TIME_EN1, to_char(TIME_ENTERED2, 'MM/DD/YYYY HH12:MI AM') TIME_EN2, to_char(TIME_ENTERED3, 'MM/DD/YYYY HH12:MI AM') TIME_EN3, to_char(TIME_ENTERED4, 'MM/DD/YYYY HH12:MI AM') TIME_EN4, to_char(TIME_ENTERED5, 'MM/DD/YYYY HH12:MI AM') TIME_EN5 FROM SUPER_VESSEL_CLOSE SVC WHERE PROD_REPORT_RUN = 'R'";
  ora_parse($per_vessel_cursor, $sql);
  ora_exec($per_vessel_cursor);

// for each vessel...
// Note:  each vessel has its own email, which is why the email command
// is found within this loop (at the end of it)
	while(ora_fetch_into($per_vessel_cursor, $per_vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	    $email_attachment_command = "";

// get the vessel name
// NOTE:  there are 3 entries in the database that start with a - sign.  This code will choke on those,
// BUT we should never have to use them for entering a ship.  If we do, the error will be instead of a ship name, 
// we get a single digit number
		$vessel = $per_vessel_row['VESSEL'];
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$vessel;
		ora_parse($vessel_cursor, $sql);
		ora_exec($vessel_cursor);
		ora_fetch_into($vessel_cursor, $vessel_name, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_long_name = $vessel_name['VESSEL_NAME'];
		$vessel_array = split("-", $vessel_name['VESSEL_NAME']);
		$vessel_name = $vessel_array[1];
		$email_subject_command = "Productivity Report for ".$vessel_name;

// place all 5 possible entries into arrays...
		$commodity_array = array($per_vessel_row['COMMODITY1'], $per_vessel_row['COMMODITY2'], $per_vessel_row['COMMODITY3'], $per_vessel_row['COMMODITY4'], $per_vessel_row['COMMODITY5']);
		$tonnage_array = array($per_vessel_row['TONNAGE1'], $per_vessel_row['TONNAGE2'], $per_vessel_row['TONNAGE3'], $per_vessel_row['TONNAGE4'], $per_vessel_row['TONNAGE5']);
		$QTY_array = array($per_vessel_row['QTY1'], $per_vessel_row['QTY2'], $per_vessel_row['QTY3'], $per_vessel_row['QTY4'], $per_vessel_row['QTY5']);
		$measurement_array = array($per_vessel_row['MEASUREMENT1'], $per_vessel_row['MEASUREMENT2'], $per_vessel_row['MEASUREMENT3'], $per_vessel_row['MEASUREMENT4'], $per_vessel_row['MEASUREMENT5']);
		$budget_array = array($per_vessel_row['BUDGET1'], $per_vessel_row['BUDGET2'], $per_vessel_row['BUDGET3'], $per_vessel_row['BUDGET4'], $per_vessel_row['BUDGET5']);
		$budget_per_array = array($per_vessel_row['BUDGET_PER1'], $per_vessel_row['BUDGET_PER2'], $per_vessel_row['BUDGET_PER3'], $per_vessel_row['BUDGET_PER4'], $per_vessel_row['BUDGET_PER5']);
		$super_array = array($per_vessel_row['SUPER1'], $per_vessel_row['SUPER2'], $per_vessel_row['SUPER3'], $per_vessel_row['SUPER4'], $per_vessel_row['SUPER5']);
		$time_complete_array = array($per_vessel_row['TIME1'], $per_vessel_row['TIME2'], $per_vessel_row['TIME3'], $per_vessel_row['TIME4'], $per_vessel_row['TIME5']);
		$time_entered_array = array($per_vessel_row['TIME_ENTERED1'], $per_vessel_row['TIME_ENTERED2'], $per_vessel_row['TIME_ENTERED3'], $per_vessel_row['TIME_ENTERED4'], $per_vessel_row['TIME_ENTERED5']);

// Added Jan18:  Making an Oracle list off of an array is a pain.  But necessary to determine ALL commodities I need to pass over.
// My crazy-stupid initial description of $non_commodity_listing is because I have to choose whether to put commas before or after
// elements in the for loop, before is easier, but to do that I need a default first entry.  I hope to the almighty god of Rutabegas that
// we never use a commodity code of 98989898 at the port...

		$non_commodity_listing = "('98989898'";
		for($temp = 0; $temp < 5; $temp++){
			if($commodity_array[$temp] != ""){
				$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$commodity_array[$temp]."'";
				$statement = ora_parse($generic_cursor, $sql);
				ora_exec($generic_cursor);
				while(ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$non_commodity_listing .= ",'".$generic_row['COMMODITY_CODE']."'";
				}
			}
		}
		$non_commodity_listing .= ")";
//		echo $non_commodity_listing;
		$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE NOT IN ".$non_commodity_listing;
		$statement = ora_parse($generic_cursor_LCS, $sql);
		ora_exec($generic_cursor_LCS);
		ora_fetch_into($generic_cursor_LCS, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($generic_row['HOURS'] == 0 || $generic_row['HOURS'] == ""){
			$exception_hours = 'no';
		} else {
			$exception_hours = $generic_row['HOURS'];
		}


// now loop for each possible commodity value 1-5
		for($current_commodity = 0; $current_commodity < 5; $current_commodity++){

// do the report if and only if a commodity exists
			if($commodity_array[$current_commodity] != ""){

// finally, start compiling the report(s)
				$commodity = $commodity_array[$current_commodity];
//				array_push($non_spec_array, $commodity);
				$tonnage = $tonnage_array[$current_commodity];
				$QTY = $QTY_array[$current_commodity];
				$measurement = $measurement_array[$current_commodity];
				$budget = $budget_array[$current_commodity];
				$budget_per = $budget_per_array[$current_commodity];
				$super = $super_array[$current_commodity];
				$time_complete = $time_complete_array[$current_commodity];
				$time_entered = $time_entered_array[$current_commodity];
				// added Jan18, to account for commodity groups.  $commodity_listing is formatted to be passed to an "IN" clause
				// in an Oracle SQL statement.
				$sql = "SELECT DISTINCT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$commodity."'";
				$statement = ora_parse($generic_cursor, $sql);
				ora_exec($generic_cursor);
				ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_listing = "('".$generic_row['COMMODITY_CODE']."'";
				while(ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$commodity_listing .= ",'".$generic_row['COMMODITY_CODE']."'";
				}
				$commodity_listing .= ")";



// get the number of hours that are backhaul
				$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID  AND ((SERVICE_CODE LIKE '61%' AND SERVICE_CODE NOT LIKE '61%0' AND SERVICE_CODE != '6113') OR (SERVICE_CODE LIKE '641%' AND SERVICE_CODE != '6140')) AND A.COMMODITY_CODE IN ".$commodity_listing;
				$statement = ora_parse($backhaul_cursor, $sql);
				ora_exec($backhaul_cursor);
				ora_fetch_into($backhaul_cursor, $backhaul_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$backhaul_hours = $backhaul_row['HOURS'];
						
// get the vessel's berth
				$sql = "SELECT BERTH_NUM FROM VOYAGE WHERE LR_NUM = ".$vessel;
				$statement = ora_parse($berth_cursor, $sql);
				ora_exec($berth_cursor);
				ora_fetch_into($berth_cursor, $berth_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$berth = $berth_row['BERTH_NUM'];

// get total hours for commodity in question
				$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE IN ".$commodity_listing;
				echo $sql."\n";
				$statement = ora_parse($sum_hours_cursor, $sql);
				ora_exec($sum_hours_cursor);
				ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$total_hours = $sum_hours_row['HOURS'];

// get the non-backhaul hours
/*				$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID  AND ((SERVICE_CODE NOT LIKE '61%' AND SERVICE_CODE NOT LIKE '641%') OR SERVICE_CODE = '6410' OR SERVICE_CODE LIKE '61%0') AND A.COMMODITY_CODE IN ".$commodity_listing;
				$statement = ora_parse($sum_hours_cursor, $sql);
				ora_exec($sum_hours_cursor);
				ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$non_backhaul_hours = $sum_hours_row['HOURS'];
*/				$non_backhaul_hours = $total_hours - $backhaul_hours;


// calculate other numbers (including a short-lived temporary variable)
				if($budget_per == 'TONS'){
					$value = $tonnage;
				} else {
					$value = $QTY;
				}
				if($backhaul_hours != 0){
					$productivity = $value / $backhaul_hours;
				} else {
					$productivity = 0;
				}


// get the number of employees
				$sql = "SELECT COUNT (DISTINCT EMPLOYEE_ID) EMPLS FROM HOURLY_DETAIL WHERE VESSEL_ID = ".$vessel." AND COMMODITY_CODE IN ".$commodity_listing;
				$statement = ora_parse($distinct_employee_cursor, $sql);
				ora_exec($distinct_employee_cursor);
				ora_fetch_into($distinct_employee_cursor, $distinct_employee_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$employees = $distinct_employee_row['EMPLS'];

// get the commodity name
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = ".$commodity;
				echo $sql."\n";
				$statement = ora_parse($commodity_cursor, $sql);
				ora_exec($commodity_cursor);
				ora_fetch_into($commodity_cursor, $commodity_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name_array = split("-", $commodity_row['COMMODITY_NAME']);
				$commodity_name = $commodity_name_array[1];

// we already have budget, so do some calculations with it...
				if($budget != 0){
					$efficiency = round((($productivity * 100) / $budget), 1);
					$efficiency -= 100;
				}
				$variance = round(($productivity - $budget), 1);

// and now some actual file writing.
//				$filename = "/web/web_pages/TS_Testing/ship_prod_reports/".$vessel."Group".$commodity.".xls";
				$filename = "/web/web_pages/supervisors/productivity_report_pages/reports/".$vessel."Group".$commodity.".xls";
				$handle = fopen($filename, "w");
				if (!$handle){
					echo "File ".$filename." could not be opened, please contact TS.\n";
				} else {
					if($budget_per == 'TONS'){
						$measure_header = 'TONS';
						$measure_value = $tonnage;
					} else {
						$measure_header = 'UNITS';
						$measure_value = $QTY;
					}

// the summary for this commodity

					fwrite($handle, "This Vessel has ".$exception_hours." hours coded to commodities not expected on the vessel.  See Details in ".$vessel."Exceptions.xls\n\n");
					fwrite($handle, "Below is the Summary for Commodity Group ".$commodity.":"); 
					fwrite($handle, $vessel_name."\t\t\t\tBerth:".$berth."\t\tCompleted: ".$time_complete."\n\n");
					fwrite($handle, "\t".$measure_header."\t\tBACKHAUL HOURS\t\tPRODUCTIVITY (".$measure_header."/HOUR)\t\t\tBUDGET (".$measure_header."/HOUR)\t\t\tProductivity to Budget\n");
					fwrite($handle, "\t".$measure_value."\t\t".$backhaul_hours."\t\t".round($productivity, 1)."\t\t\t".$budget."\t\t\tVariance\n");
					fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t".$variance."\n\n");
					fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tPercentage to Budget\n");
					fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tVariance\n");
					fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t".$efficiency."%\n");
					fwrite($handle, "\t".$measure_header."\n");
					fwrite($handle, "\t\tLR Num\t\tCommodity\t\t".$measure_header."\n");
					fwrite($handle, "\t\t".$vessel."\t\t".$commodity_name."\t\t".$measure_value."\n\n");
					fwrite($handle, "\tHours\n");
					fwrite($handle, "\t\tLR Num\t\tDistinct Employees\t\tTotal Hours\t\tBackhaul Hours\t\tNon-Backhaul Hours\n");
					fwrite($handle, "\t\t".$vessel."\t\t".$employees."\t\t".$total_hours."\t\t".$backhaul_hours."\t\t".$non_backhaul_hours."\n\n");
					fwrite($handle, "\tHours by Supervisor\n");
					fwrite($handle, "\t\tLR Num\t\tSupervsor\t\tHours Used\n");
					$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE IN ".$commodity_listing." GROUP BY USER_NAME ORDER BY USER_NAME";
					$statement = ora_parse($sum_hours_cursor, $sql);
					ora_exec($sum_hours_cursor);
					while(ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$supervisor_name = $sum_hours_row['USER_NAME'];
						$supervisor_hours = $sum_hours_row['HOURS'];
						fwrite($handle, "\t\t".$vessel."\t\t".$supervisor_name."\t\t".$supervisor_hours."\n");
					}
					fwrite($handle, "\t\t\t\tTOTAL\t\t".$total_hours."\n\n\n");

					// added Jan18:  Adding commodity lists to bottom of report
					fwrite($handle, "The following commodities are included in this report:\n");
					$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$commodity."' ORDER BY COMMODITY_CODE";
					$statement = ora_parse($generic_cursor, $sql);
					ora_exec($generic_cursor);
					while(ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						fwrite($handle, "\t".$generic_row['COMMODITY_NAME']."\n");
					}

					fwrite($handle, "\n\nThe service codes calculated for backhaul are any that begin with '61' or '641', but do not end in 0, and are not 6113.");

					fclose($handle);
				}
				$email_attachment_command .= "-a ".$filename." ";
			}
		}

// this section generates the same file that the above for loop does, but for
// commodities not specified for the given vessel




// get the number of hours that are backhaul
		$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID  AND ((SERVICE_CODE LIKE '61%' AND SERVICE_CODE NOT LIKE '61%0' AND SERVICE_CODE != '6113') OR (SERVICE_CODE LIKE '641%' AND SERVICE_CODE != '6140')) AND A.COMMODITY_CODE NOT IN ".$non_commodity_listing;
/* -----Removed due to no logner necessary Jan18
		$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID  AND SERVICE_CODE LIKE '61%' AND SERVICE_CODE NOT LIKE '61%0'";
		for($temp = 0; $temp < 5; $temp++){
			if($non_spec_array[$temp] != ""){
				$sql .= " AND A.COMMODITY_CODE != '".$non_spec_array[$temp]."'";
			}
		}
*/
		$statement = ora_parse($backhaul_cursor, $sql);
		ora_exec($backhaul_cursor);
		ora_fetch_into($backhaul_cursor, $backhaul_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$backhaul_hours = $backhaul_row['HOURS'];

// get the vessel's berth
		$sql = "SELECT BERTH_NUM FROM VOYAGE WHERE LR_NUM = ".$vessel;
		$statement = ora_parse($berth_cursor, $sql);
		ora_exec($berth_cursor);
		ora_fetch_into($berth_cursor, $berth_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$berth = $berth_row['BERTH_NUM'];

// get total hours, different for this part as we have from 1 to 5 possible codes
//		$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE NOT IN ".$non_commodity_listing;
/* -----Removed due to no logner necessary Jan18
		$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID";
		for($temp = 0; $temp < 5; $temp++){
			if($non_spec_array[$temp] != ""){
				$sql .= " AND A.COMMODITY_CODE != '".$non_spec_array[$temp]."'";
			}
		}
*/
//		$statement = ora_parse($sum_hours_cursor, $sql);
//		ora_exec($sum_hours_cursor);
//		ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//		$total_hours = $sum_hours_row['HOURS'];

// calculate other numbers (including a short-lived temporary variable)
// well, we would, but we dont use these numbers in the Non-Spec report, so no need to get them.
//		$other_hours = $total_hours - $backhaul_hours;
/*		if($measurement == 'TONS'){
			$value = $tonnage;
		} else {
			$value = $QTY;
		}
		if($backhaul_hours != 0){
			$productivity = $value / $backhaul_hours;
		}
*/

		// get the number of employees
		$sql = "SELECT COUNT (DISTINCT EMPLOYEE_ID) EMPLS FROM HOURLY_DETAIL WHERE COMMODITY_CODE NOT IN ".$non_commodity_listing." AND VESSEL_ID = ".$vessel;
/* -----Removed due to no logner necessary Jan18
		$sql = "SELECT COUNT (DISTINCT EMPLOYEE_ID) EMPLS FROM HOURLY_DETAIL WHERE VESSEL_ID = ".$vessel;
		for($temp = 0; $temp < 5; $temp++){
			if($non_spec_array[$temp] != ""){
				$sql .= " AND COMMODITY_CODE != '".$non_spec_array[$temp]."'";
			}
		} 
*/
		$statement = ora_parse($distinct_employee_cursor, $sql);
		ora_exec($distinct_employee_cursor);
		ora_fetch_into($distinct_employee_cursor, $distinct_employee_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$employees = $distinct_employee_row['EMPLS'];

		/* get the commodity name... NOT USED IN THIS ITERATION
		  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = ".$commodity;
		  $statement = ora_parse($commodity_cursor, $sql);
		  ora_exec($commodity_cursor);
		  ora_fetch_into($commodity_cursor, $commodity_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $commodity_array = split("-", $commodity_row['COMMODITY_NAME']);
		  $commodity_name = $commodity_array[1];
/*
		// get the budgeted hours.  to DB is wierd on this one... it either has a 4 digits value (normal),
		// or a 2-digit value fiollowed by underscores, to indicate that any code with those first 2 digits
		// gets the same budget.  Not my preferred storage method, but... whatever.
		/* this is NOT USED in the non-specified section
		  $sql = "SELECT BUDGET FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$commodity."'";
		//  echo $sql."\n";
		  $statement = ora_parse($budget_cursor, $sql);
		  ora_exec($budget_cursor);
		  ora_fetch_into($budget_cursor, $budget_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  if (ora_numrows($budget_cursor) > 0) {
			  $budget = $budget_row['BUDGET'];
		  } else {
			  $first_two = substr($commodity, 0, 2);
			  $sql = "SELECT BUDGET FROM BUDGET WHERE TYPE = 'BACKHAUL' AND COMMODITY_CODE = '".$first_two."__'";
		//	  echo $sql."\n";
			  $statement = ora_parse($budget_cursor, $sql);
			  ora_exec($budget_cursor);
			  ora_fetch_into($budget_cursor, $budget_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			  $budget = $budget_row['BUDGET'];
		  }
		  if($budget != 0){
			  $efficiency = round((($productivity * 100) / $budget), 1);
			  $efficiency -= 100;
		  }
		  $variance = round(($productivity - $budget), 1);
*/




		  $filename = "/web/web_pages/supervisors/productivity_report_pages/reports/".$vessel."Exceptions.xls";
		  $handle = fopen($filename, "w");
		  if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
		  } else {

		// the summary for non-specified commodity codes

				fwrite($handle, "This Vessel has ".$exception_hours." hours coded to commodities not expected on the vessel.  Details are below:\n\n");
				fwrite($handle, $vessel_name."\t\t\t\tBerth:".$berth."\t\tCompleted: ".$completion_fulltime."\n\n");
/*
				fwrite($handle, "\tTONS\t\tBACKHAUL HOURS\t\tPRODUCTIVITY (TONS/HOUR)\t\t\tBUDGET (TONS/HOUR)\t\t\tProductivity to Budget\n");
				fwrite($handle, "\tN/A\t\t".$backhaul_hours."\t\tN/A\t\t\tN/A\t\t\tN/A\n");
				fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t\n\n");
				fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tPercentage to Budget\n");
				fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tVariance\n");
				fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tN/A\n");
				fwrite($handle, "\tTons\n");
				fwrite($handle, "\t\tLR Num\t\tCommodity\t\tTonnage\n");
				fwrite($handle, "\t\t".$vessel."\t\tOther\t\tN/A\n\n");
				fwrite($handle, "\tHours\n");
				fwrite($handle, "\t\tLR Num\t\tDistinct Employees\t\tTotal Hours\t\tBackhaul\t\tOther\n");
				fwrite($handle, "\t\t".$vessel."\t\t".$employees."\t\t".$total_hours."\t\t".$backhaul_hours."\t\t".$other_hours."\n\n");
*/
				fwrite($handle, "\tIncorrectly coded by Supervisors\t\t\t\tHours\n");
//				fwrite($handle, "\t\tLR Num\t\tSupervsor\t\tHours Used\n");
				$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND COMMODITY_CODE NOT IN ".$non_commodity_listing." GROUP BY USER_NAME ORDER BY USER_NAME";
/* -----Removed due to no logner necessary Jan18
				$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID";
				for($temp = 0; $temp < 5; $temp++){
				  if($non_spec_array[$temp] != ""){
					  $sql .= " AND COMMODITY_CODE != '".$non_spec_array[$temp]."'";
				  }
				}
				$sql .= " GROUP BY USER_NAME ORDER BY USER_NAME";
*/
				$statement = ora_parse($sum_hours_cursor, $sql);
				ora_exec($sum_hours_cursor);
				while(ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$supervisor_name = $sum_hours_row['USER_NAME'];
					$supervisor_hours = $sum_hours_row['HOURS'];
//					fwrite($handle, "\t\t".$vessel."\t\t".$supervisor_name."\t\t".$supervisor_hours."\n");
					fwrite($handle, "\t\t".$supervisor_name."\t\t\t".$supervisor_hours."\n");
				}
				fwrite($handle, "\t\tTOTAL\t\t\t".$exception_hours."\n");

				fwrite($handle, "\n\nThe service codes calculated for backhaul are any that begin with '61' or '641', but do not end in 0, and are not 6113.");

				fclose($handle);
		  }

		  $email_attachment_command .= "-a ".$filename." ";



		  $filename = "/web/web_pages/supervisors/productivity_report_pages/reports/".$vessel."Details.xls";
		  $handle = fopen($filename, "w");
		  if (!$handle){
			  echo "File ".$filename." could not be opened, please contact TS.\n";
		  } else {

		// the 2nd section, backhaul only
			$total_backhaul = 0;
			$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE AND ((A.SERVICE_CODE LIKE '61%' AND A.SERVICE_CODE NOT LIKE '61%0' AND A.SERVICE_CODE != '6113') OR (A.SERVICE_CODE LIKE '641%' AND A.SERVICE_CODE != '6410')) ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
			$statement = ora_parse($main_data_cursor, $sql);
			ora_exec($main_data_cursor);
			ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$columnheaders = "VESSEL\tSERVICE\tUSER NAME\tHIRE DATE\tEMPLOYEE ID\tEMPLOYEE NAME\tHOURS\tCOMMODITY CODE\tCUSTOMER ID\tEARNING TYPE\tSERVICE CODE\tEQUIPMENT ID\tLOCATION ID\tSTART TIME\tEND TIME\tUSER ID\tSPECIAL CODE\tEXCEPTION\tREMARK\n";

			fwrite($handle, "Backhaul Hours Detail\n");
			fwrite($handle, $columnheaders);
			do {
				$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
//				echo $sql."\n";
				$statement = ora_parse($employee_name_cursor, $sql);
				ora_exec($employee_name_cursor);
				ora_fetch_into($employee_name_cursor, $employee_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$information = $main_data_row['VESSEL_ID']."\t".$main_data_row['SERVICE_NAME']."\t".$main_data_row['USER_NAME']."\t".$main_data_row['HIRE_DATE']."\t".$main_data_row['EMPLOYEE_ID']."\t".$employee_name_row['EMPLOYEE_NAME']."\t".$main_data_row['DURATION']."\t".$main_data_row['COMMODITY_CODE']."\t".$main_data_row['CUSTOMER_ID']."\t".$main_data_row['EARNING_TYPE_ID']."\t".$main_data_row['SERVICE_CODE']."\t".$main_data_row['EQUIPMENT_ID']."\t".$main_data_row['LOCATION_ID']."\t".$main_data_row['START_TIME']."\t".$main_data_row['END_TIME']."\t".$main_data_row['USER_ID']."\t".$main_data_row['SPECIAL_CODE']."\t".$main_data_row['EXCEPTION']."\t".$main_data_row['REMARK']."\n";
		//		echo $information."\n";
				fwrite($handle, $information);
				$total_backhaul += $main_data_row['DURATION'];
			} while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			fwrite($handle, "\t\t\t\tTOTAL\t\t".$total_backhaul."\n");
			fwrite($handle, "\n\n\n\n\n");

		// the 3rd section, non-backhaul only
			$total_nonbackhaul = 0;
			$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE AND ((A.SERVICE_CODE NOT LIKE '61%' AND A.SERVICE_CODE NOT LIKE '641%') OR A.SERVICE_CODE LIKE '61%0' OR A.SERVICE_CODE = '6410' OR A.SERVICE_CODE = '6113') ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
			$statement = ora_parse($main_data_cursor, $sql);
			ora_exec($main_data_cursor);
			ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$columnheaders = "VESSEL\tSERVICE\tUSER NAME\tHIRE DATE\tEMPLOYEE ID\tEMPLOYEE NAME\tHOURS\tCOMMODITY CODE\tCUSTOMER ID\tEARNING TYPE\tSERVICE CODE\tEQUIPMENT ID\tLOCATION ID\tSTART TIME\tEND TIME\tUSER ID\tSPECIAL CODE\tEXCEPTION\tREMARK\n";

			fwrite($handle, "Non-Backhaul Hours Detail\n");
			fwrite($handle, $columnheaders);
			do {
				$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
				$statement = ora_parse($employee_name_cursor, $sql);
				ora_exec($employee_name_cursor);
				ora_fetch_into($employee_name_cursor, $employee_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$information = $main_data_row['VESSEL_ID']."\t".$main_data_row['SERVICE_NAME']."\t".$main_data_row['USER_NAME']."\t".$main_data_row['HIRE_DATE']."\t".$main_data_row['EMPLOYEE_ID']."\t".$employee_name_row['EMPLOYEE_NAME']."\t".$main_data_row['DURATION']."\t".$main_data_row['COMMODITY_CODE']."\t".$main_data_row['CUSTOMER_ID']."\t".$main_data_row['EARNING_TYPE_ID']."\t".$main_data_row['SERVICE_CODE']."\t".$main_data_row['EQUIPMENT_ID']."\t".$main_data_row['LOCATION_ID']."\t".$main_data_row['START_TIME']."\t".$main_data_row['END_TIME']."\t".$main_data_row['USER_ID']."\t".$main_data_row['SPECIAL_CODE']."\t".$main_data_row['EXCEPTION']."\t".$main_data_row['REMARK']."\n";
		//		echo $information."\n";
				fwrite($handle, $information);
				$total_nonbackhaul += $main_data_row['DURATION'];
			} while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			fwrite($handle, "\t\t\t\tTOTAL\t\t".$total_nonbackhaul."\n");
			fwrite($handle, "\n\n\n\n\n");


		// The 4th, all-encompasing section
			$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
		//  echo $sql."\n";
				$statement = ora_parse($main_data_cursor, $sql);
				ora_exec($main_data_cursor);
				ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			
				$columnheaders = "VESSEL\tSERVICE\tUSER NAME\tHIRE DATE\tEMPLOYEE ID\tEMPLOYEE NAME\tHOURS\tCOMMODITY CODE\tCUSTOMER ID\tEARNING TYPE\tSERVICE CODE\tEQUIPMENT ID\tLOCATION ID\tSTART TIME\tEND TIME\tUSER ID\tSPECIAL CODE\tEXCEPTION\tREMARK\n";
				fwrite($handle, "Total Records\n");
				fwrite($handle, $columnheaders);
				do {
					$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
					$statement = ora_parse($employee_name_cursor, $sql);
					ora_exec($employee_name_cursor);
					ora_fetch_into($employee_name_cursor, $employee_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$information = $main_data_row['VESSEL_ID']."\t".$main_data_row['SERVICE_NAME']."\t".$main_data_row['USER_NAME']."\t".$main_data_row['HIRE_DATE']."\t".$main_data_row['EMPLOYEE_ID']."\t".$employee_name_row['EMPLOYEE_NAME']."\t".$main_data_row['DURATION']."\t".$main_data_row['COMMODITY_CODE']."\t".$main_data_row['CUSTOMER_ID']."\t".$main_data_row['EARNING_TYPE_ID']."\t".$main_data_row['SERVICE_CODE']."\t".$main_data_row['EQUIPMENT_ID']."\t".$main_data_row['LOCATION_ID']."\t".$main_data_row['START_TIME']."\t".$main_data_row['END_TIME']."\t".$main_data_row['USER_ID']."\t".$main_data_row['SPECIAL_CODE']."\t".$main_data_row['EXCEPTION']."\t".$main_data_row['REMARK']."\n";
		//			echo $information."\n";
					fwrite($handle, $information);
				} while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			}
			fclose($handle);

			$email_attachment_command .= "-a ".$filename." ";

// now that we've (theoretically) compiled the list of attached files for this vessel...

// Due to Inigo's extremely fine attention to detail (in this case, a single word in a single email), I have
// To come up with a convoluted way of figuring out which DB column the greatest time for a vessel resides in,
// Then extract that time, and the person who entered said time, for placement in said email... oye.
// then I get to do a 5-step if-then, for even more fun.
			$sql = "SELECT to_char(GREATEST(DECODE(TIME_ENTERED1, NULL, TO_DATE('1/1/2001', 'MM/DD/YYYY'), TIME_ENTERED1),
				DECODE(TIME_ENTERED2, NULL, TO_DATE('1/1/2001', 'MM/DD/YYYY'), TIME_ENTERED2),
				DECODE(TIME_ENTERED3, NULL, TO_DATE('1/1/2001', 'MM/DD/YYYY'), TIME_ENTERED3),
				DECODE(TIME_ENTERED4, NULL, TO_DATE('1/1/2001', 'MM/DD/YYYY'), TIME_ENTERED4),
				DECODE(TIME_ENTERED5, NULL, TO_DATE('1/1/2001', 'MM/DD/YYYY'), TIME_ENTERED5)), 'MM/DD/YYYY HH24:MI:SS') BIG
				FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
			$statement = ora_parse($generic_cursor, $sql);
			ora_exec($generic_cursor);
			ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$last_time_entered = $generic_row['BIG'];

			$sql = "SELECT to_char(TIME_ENTERED1, 'MM/DD/YYYY HH24:MI:SS') TIME1, to_char(TIME_ENTERED2, 'MM/DD/YYYY HH24:MI:SS') TIME2, to_char(TIME_ENTERED3, 'MM/DD/YYYY HH24:MI:SS') TIME3, to_char(TIME_ENTERED4, 'MM/DD/YYYY HH24:MI:SS') TIME4, to_char(TIME_ENTERED5, 'MM/DD/YYYY HH24:MI:SS') TIME5, SUPER1, SUPER2, SUPER3, SUPER4, SUPER5 FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
			$statement = ora_parse($generic_cursor, $sql);
			ora_exec($generic_cursor);
			ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($generic_row['TIME1'] == $last_time_entered){
				$last_person_entered = $generic_row['SUPER1'];
			}
			if($generic_row['TIME2'] == $last_time_entered){
				$last_person_entered = $generic_row['SUPER2'];
			}
			if($generic_row['TIME3'] == $last_time_entered){
				$last_person_entered = $generic_row['SUPER3'];
			}
			if($generic_row['TIME4'] == $last_time_entered){
				$last_person_entered = $generic_row['SUPER4'];
			}
			if($generic_row['TIME5'] == $last_time_entered){
				$last_person_entered = $generic_row['SUPER5'];
			}
			
//			$cmd = "echo \"Productivity Report for vessel ".$vessel_name;
			$cmd = "echo \"Last data entry was completed by ".$last_person_entered." at ".$last_time_entered;

			if($per_vessel_row['SUPER1'] != ""){
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$per_vessel_row['COMMODITY1']."'";
				$statement = ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name = $commodity_name_row['COMMODITY_NAME'];

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE1']." (".$per_vessel_row['QTY1']." ".$per_vessel_row['MEASUREMENT1'].") Closed by ".$per_vessel_row['SUPER1']." at ".$per_vessel_row['TIME1']." (Time entered at ".$per_vessel_row['TIME_EN1'].")";

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE1']." TONS Closed by ".$per_vessel_row['SUPER1']." at ".$per_vessel_row['TIME1']." (Time entered at ".$per_vessel_row['TIME_EN1'].")";
			}
			if($per_vessel_row['SUPER2'] != ""){
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$per_vessel_row['COMMODITY2']."'";
				$statement = ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name = $commodity_name_row['COMMODITY_NAME'];

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE2']." (".$per_vessel_row['QTY2']." ".$per_vessel_row['MEASUREMENT2'].") Closed by ".$per_vessel_row['SUPER2']." at ".$per_vessel_row['TIME2']." (Time entered at ".$per_vessel_row['TIME_EN2'].")";

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE2']." TONS Closed by ".$per_vessel_row['SUPER2']." at ".$per_vessel_row['TIME2']." (Time entered at ".$per_vessel_row['TIME_EN2'].")";
			}
			if($per_vessel_row['SUPER3'] != ""){
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$per_vessel_row['COMMODITY3']."'";
				$statement = ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name = $commodity_name_row['COMMODITY_NAME'];

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE3']." (".$per_vessel_row['QTY3']." ".$per_vessel_row['MEASUREMENT3'].") Closed by ".$per_vessel_row['SUPER3']." at ".$per_vessel_row['TIME3']." (Time entered at ".$per_vessel_row['TIME_EN3'].")";

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE3']." TONS Closed by ".$per_vessel_row['SUPER3']." at ".$per_vessel_row['TIME3']." (Time entered at ".$per_vessel_row['TIME_EN3'].")";
			}
			if($per_vessel_row['SUPER4'] != ""){
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$per_vessel_row['COMMODITY4']."'";
				$statement = ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name = $commodity_name_row['COMMODITY_NAME'];

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE4']." (".$per_vessel_row['QTY4']." ".$per_vessel_row['MEASUREMENT4'].") Closed by ".$per_vessel_row['SUPER4']." at ".$per_vessel_row['TIME4']." (Time entered at ".$per_vessel_row['TIME_EN4'].")";

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE4']." TONS Closed by ".$per_vessel_row['SUPER4']." at ".$per_vessel_row['TIME4']." (Time entered at ".$per_vessel_row['TIME_EN4'].")";
			}
			if($per_vessel_row['SUPER5'] != ""){
				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$per_vessel_row['COMMODITY5']."'";
				$statement = ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name = $commodity_name_row['COMMODITY_NAME'];

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE5']." (".$per_vessel_row['QTY5']." ".$per_vessel_row['MEASUREMENT5'].") Closed by ".$per_vessel_row['SUPER5']." at ".$per_vessel_row['TIME5']." (Time entered at ".$per_vessel_row['TIME_EN5'].")";

//				$cmd .= "\nCommodity ".$commodity_name." --- ".$per_vessel_row['TONNAGE5']." TONS Closed by ".$per_vessel_row['SUPER5']." at ".$per_vessel_row['TIME5']." (Time entered at ".$per_vessel_row['TIME_EN5'].")";
			}
//			$cmd .= " \" | mutt -s \"".$vessel_long_name."	Productivity\" ".$email_attachment_command."awalter@port.state.de.us";
			$cmd .= " \" | mutt -s \"".$vessel_long_name."	Productivity\" ".$email_attachment_command."fvignuli@port.state.de.us -c OPSSupervisors@port.state.de.us -c vfarkas@port.state.de.us -c jjaffe@port.state.de.us -c ddonofrio@port.state.de.us -c wstans@port.state.de.us -c ltreut@port.state.de.us -c Martym@port.state.de.us -c jharoldson@port.state.de.us -c SeniorManagers@port.state.de.us -b awalter@port.state.de.us -b lstewart@port.state.de.us";
			system($cmd);
//			echo $cmd;

		$ora_now = date('m/d/Y H:i');
		$sql = "UPDATE SUPER_VESSEL_CLOSE SET PROD_REPORT_RUN = 'Y', DATE_REPORT_RUN = to_date('".$ora_now."', 'MM/DD/YYYY HH24:MI') WHERE VESSEL = '".$vessel."'";
		ora_parse($vessel_update_cursor, $sql);
		ora_exec($vessel_update_cursor);

	}


/*
	echo $vessel_number."\n";
	echo $tonnage."\n";
	echo $completion_fulltime."\n";
	echo $completion_timestamp."\n";
*/
?>


