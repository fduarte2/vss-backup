<?
// ADAM WALTER, April 2006.  Creates a tab-delimited file with a vessel productivity report
// to the same folder that the program resides in.

  // All POW files need this session file included
  include("pow_session.php");

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
  





?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Productivity Report Generation Result:
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

  $main_data_cursor = ora_open($conn);
  $sum_hours_cursor = ora_open($conn);
  $distinct_employee_cursor = ora_open($conn);
  $backhaul_cursor = ora_open($conn);
  $vessel_cursor = ora_open($conn2);
  $budget_cursor = ora_open($conn2);
  $commodity_cursor = ora_open($conn2);
  $employee_name_cursor = ora_open($conn);
  $berth_cursor = ora_open($conn2);

  for($counter = 0; $counter < 5; $counter++){
	  if($commodity_input_array[$counter] != "" && $tonnage_input_array[$counter] != ""){
		  $commodity = $commodity_input_array[$counter];
		  $tonnage = $tonnage_input_array[$counter];

		// get the vessel name
		// NOTE:  there are 3 entries in the database that start with a - sign.  This code will choke on those,
		// BUT we should enver have to use them for entering a ship.  If we do, the error will be instead of a ship name, 
		// we get a single digit number
		  $sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$vessel_number;
		  $statement = ora_parse($vessel_cursor, $sql);
		  ora_exec($vessel_cursor);
		  ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $vessel_array = split("-", $vessel_row['VESSEL_NAME']);
		  $vessel_name = $vessel_array[1];

		// get the number of hours that are backhaul
		  $sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID  AND SERVICE_CODE LIKE '61%' AND SERVICE_CODE NOT LIKE '61%0' AND A.COMMODITY_CODE = '".$commodity."'";
		//  echo $sql."\n";
		  $statement = ora_parse($backhaul_cursor, $sql);
		  ora_exec($backhaul_cursor);
		  ora_fetch_into($backhaul_cursor, $backhaul_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $backhaul_hours = $backhaul_row['HOURS'];
		//  echo $backhaul_row['HOURS']."\n";

		// get the vessel's berth
		  $sql = "SELECT BERTH_NUM FROM VOYAGE WHERE LR_NUM = ".$vessel_number;
		  $statement = ora_parse($berth_cursor, $sql);
		  ora_exec($berth_cursor);
		  ora_fetch_into($berth_cursor, $berth_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $berth = $berth_row['BERTH_NUM'];

		// get total hours
		  $sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE = '".$commodity."'";
		  $statement = ora_parse($sum_hours_cursor, $sql);
		  ora_exec($sum_hours_cursor);
		  ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $total_hours = $sum_hours_row['HOURS'];

		// calculate other numbers
		  $other_hours = $total_hours - $backhaul_hours;
		  if($backhaul_hours != 0){
			  $productivity = $tonnage / $backhaul_hours;
		  }
		//  echo $total_hours."\n".$backhaul_hours."\n".$other_hours."\n";

		// get the number of employees
		  $sql = "SELECT COUNT (DISTINCT EMPLOYEE_ID) EMPLS FROM HOURLY_DETAIL WHERE VESSEL_ID = ".$vessel_number." AND COMMODITY_CODE = '".$commodity."'";
		  $statement = ora_parse($distinct_employee_cursor, $sql);
		  ora_exec($distinct_employee_cursor);
		  ora_fetch_into($distinct_employee_cursor, $distinct_employee_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $employees = $distinct_employee_row['EMPLS'];

		// get the commodity name
		  $sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = ".$commodity;
		  $statement = ora_parse($commodity_cursor, $sql);
		  ora_exec($commodity_cursor);
		  ora_fetch_into($commodity_cursor, $commodity_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $commodity_array = split("-", $commodity_row['COMMODITY_NAME']);
		  $commodity_name = $commodity_array[1];

		// get the budgeted hours.  to DB is wierd on this one... it either has a 4 digits value (normal),
		// or a 2-digit value fiollowed by underscores, to indicate that any code with those first 2 digits
		// gets the same budget.  Not my preferred storage method, but... whatever.
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





		  $filename = "Vessel".$vessel_number."summary-comm".$commodity.".xls";
		  $handle = fopen($filename, "w");
		  if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
		  } else {

		// the summary for this commodity

			fwrite($handle, $vessel_name."\t\t\t\tBerth:".$berth."\t\tCompleted: ".$completion_fulltime."\n\n");
			fwrite($handle, "\tTONS\t\tBACKHAUL HOURS\t\tPRODUCTIVITY (TONS/HOUR)\t\t\tBUDGET (TONS/HOUR)\t\t\tProductivity to Budget\n");
			fwrite($handle, "\t".$tonnage."\t\t".$backhaul_hours."\t\t".round($productivity, 1)."\t\t\t".$budget."\t\t\tVariance\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t".$variance."\n\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tPercentage to Budget\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tVariance\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t".$efficiency."%\n");
			fwrite($handle, "\tTons\n");
			fwrite($handle, "\t\tLR Num\t\tCommodity\t\tTonnage\n");
			fwrite($handle, "\t\t".$vessel_number."\t\t".$commodity_name."\t\t".$tonnage."\n\n");
			fwrite($handle, "\tHours\n");
			fwrite($handle, "\t\tLR Num\t\tDistinct Employees\t\tTotal Hours\t\t".$commodity." - Backhaul\t\tOther\n");
			fwrite($handle, "\t\t".$vessel_number."\t\t".$employees."\t\t".$total_hours."\t\t".$backhaul_hours."\t\t".$other_hours."\n\n");
			fwrite($handle, "\tHours by Supervisor\n");
			fwrite($handle, "\t\tLR Num\t\tSupervsor\t\tHours Used\n");
			$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE = '".$commodity."' GROUP BY USER_NAME ORDER BY USER_NAME";
			$statement = ora_parse($sum_hours_cursor, $sql);
			ora_exec($sum_hours_cursor);
			while(ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$supervisor_name = $sum_hours_row['USER_NAME'];
				$supervisor_hours = $sum_hours_row['HOURS'];
				fwrite($handle, "\t\t".$vessel_number."\t\t".$supervisor_name."\t\t".$supervisor_hours."\n");
			}
			fwrite($handle, "\t\t\t\tTOTAL\t\t".$total_hours."\n\n\n\n\n");
			fclose($handle);
		  }
	  }
  }

		// this part of the code is to report any backhauls on commodity codes not caught by the inputted values.  Logically it is the same as above, but with some SQL differences, as well as some symantic changes.

		  $sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$vessel_number;
		  $statement = ora_parse($vessel_cursor, $sql);
		  ora_exec($vessel_cursor);
		  ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $vessel_array = split("-", $vessel_row['VESSEL_NAME']);
		  $vessel_name = $vessel_array[1];

		// get the number of hours that are backhaul
		  $sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID  AND SERVICE_CODE LIKE '61%' AND SERVICE_CODE NOT LIKE '61%0'";
  		  for($temp = 0; $temp < 5; $temp++){
			  if($commodity_input_array[$temp] != ""){
				  $sql .= " AND A.COMMODITY_CODE != '".$commodity_input_array[$temp]."'";
			  }
		  }

		//  echo $sql."\n";
		  $statement = ora_parse($backhaul_cursor, $sql);
		  ora_exec($backhaul_cursor);
		  ora_fetch_into($backhaul_cursor, $backhaul_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $backhaul_hours = $backhaul_row['HOURS'];
		//  echo $backhaul_row['HOURS']."\n";

		// get the vessel's berth
		  $sql = "SELECT BERTH_NUM FROM VOYAGE WHERE LR_NUM = ".$vessel_number;
		  $statement = ora_parse($berth_cursor, $sql);
		  ora_exec($berth_cursor);
		  ora_fetch_into($berth_cursor, $berth_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $berth = $berth_row['BERTH_NUM'];

		// get total hours, different for this part as we have from 1 to 5 possible codes
		  $sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID";
		  for($temp = 0; $temp < 5; $temp++){
			  if($commodity_input_array[$temp] != ""){
				  $sql .= " AND A.COMMODITY_CODE != '".$commodity_input_array[$temp]."'";
			  }
		  }
		  $statement = ora_parse($sum_hours_cursor, $sql);
		  ora_exec($sum_hours_cursor);
		  ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		  $total_hours = $sum_hours_row['HOURS'];

		// calculate other numbers
		  $other_hours = $total_hours - $backhaul_hours;
		  if($backhaul_hours != 0){
			  $productivity = $tonnage / $backhaul_hours;
		  }
		//  echo $total_hours."\n".$backhaul_hours."\n".$other_hours."\n";

		// get the number of employees
		  $sql = "SELECT COUNT (DISTINCT EMPLOYEE_ID) EMPLS FROM HOURLY_DETAIL WHERE VESSEL_ID = ".$vessel_number;
		  for($temp = 0; $temp < 5; $temp++){
			  if($commodity_input_array[$temp] != ""){
				  $sql .= " AND COMMODITY_CODE != '".$commodity_input_array[$temp]."'";
			  }
		  }
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




		  $filename = "Vessel".$vessel_number."summary-nonSpecified.xls";
		  $handle = fopen($filename, "w");
		  if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
		  } else {

		// the summary for non-specified commodity codes

			fwrite($handle, $vessel_name."\t\t\t\tBerth:".$berth."\t\tCompleted: ".$completion_fulltime."\n\n");
			fwrite($handle, "\tTONS\t\tBACKHAUL HOURS\t\tPRODUCTIVITY (TONS/HOUR)\t\t\tBUDGET (TONS/HOUR)\t\t\tProductivity to Budget\n");
			fwrite($handle, "\tN/A\t\t".$backhaul_hours."\t\tN/A\t\t\tN/A\t\t\tN/A\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t\n\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tPercentage to Budget\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tVariance\n");
			fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tN/A\n");
			fwrite($handle, "\tTons\n");
			fwrite($handle, "\t\tLR Num\t\tCommodity\t\tTonnage\n");
			fwrite($handle, "\t\t".$vessel_number."\t\tOther\t\tN/A\n\n");
			fwrite($handle, "\tHours\n");
			fwrite($handle, "\t\tLR Num\t\tDistinct Employees\t\tTotal Hours\t\tBackhaul\t\tOther\n");
			fwrite($handle, "\t\t".$vessel_number."\t\t".$employees."\t\t".$total_hours."\t\t".$backhaul_hours."\t\t".$other_hours."\n\n");
			fwrite($handle, "\tHours by Supervisor\n");
			fwrite($handle, "\t\tLR Num\t\tSupervsor\t\tHours Used\n");
			$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID";
			for($temp = 0; $temp < 5; $temp++){
			  if($commodity_input_array[$temp] != ""){
				  $sql .= " AND COMMODITY_CODE != '".$commodity_input_array[$temp]."'";
			  }
			}
			$sql .= " GROUP BY USER_NAME ORDER BY USER_NAME";

			$statement = ora_parse($sum_hours_cursor, $sql);
			ora_exec($sum_hours_cursor);
			while(ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$supervisor_name = $sum_hours_row['USER_NAME'];
				$supervisor_hours = $sum_hours_row['HOURS'];
				fwrite($handle, "\t\t".$vessel_number."\t\t".$supervisor_name."\t\t".$supervisor_hours."\n");
			}
			fwrite($handle, "\t\t\t\tTOTAL\t\t".$total_hours."\n\n\n\n\n");
			fclose($handle);
		  }



		  $filename = "Vessel".$vessel_number."details.xls";
		  $handle = fopen($filename, "w");
		  if (!$handle){
			  echo "File ".$filename." could not be opened, please contact TS.\n";
		  } else {

		// the 2nd section, backhaul only
			$total_backhaul = 0;
			$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE AND A.SERVICE_CODE LIKE '61%' AND A.SERVICE_CODE NOT LIKE '61%0' ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
			$statement = ora_parse($main_data_cursor, $sql);
			ora_exec($main_data_cursor);
			ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$columnheaders = "VESSEL\tSERVICE\tUSER NAME\tHIRE DATE\tEMPLOYEE ID\tEMPLOYEE NAME\tHOURS\tCOMMODITY CODE\tCUSTOMER ID\tEARNING TYPE\tSERVICE CODE\tEQUIPMENT ID\tLOCATION ID\tSTART TIME\tEND TIME\tUSER ID\tSPECIAL CODE\tEXCEPTION\tREMARK\n";

			fwrite($handle, "Backhaul Hours Detail\n");
			fwrite($handle, $columnheaders);
			do {
				$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
				echo $sql."\n";
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
			$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE AND (A.SERVICE_CODE NOT LIKE '61%' OR A.SERVICE_CODE LIKE '61%0') ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
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
			$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel_number." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
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

	echo $vessel_number."\n";
	echo $tonnage."\n";
	echo $completion_fulltime."\n";
	echo $completion_timestamp."\n";
?>


<? include("pow_footer.php"); ?>
