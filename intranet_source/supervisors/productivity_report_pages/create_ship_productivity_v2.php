<?
/*
*	this is the new version of the Productivity Report.
*
*	It differs fromt he old version in that the MUTT mail routine has been ousted (finally), 
*	And that it attaches 2 files now (xls and html versions).
*
*	Also, I'm excising the tab-delimtied xls versions and going for table-formatted.
********************************************************************************/

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

   $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//   $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
   if($rf_conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($rf_conn));
     	printf("</body></html>");
       	exit;
   }   
   $ED_cursor = ora_open($rf_conn);

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



	$sql = "SELECT SVC.*, to_char(TIME_COMPLETE1, 'MM/DD/YYYY HH12:MI AM') TIME1, to_char(TIME_COMPLETE2, 'MM/DD/YYYY HH12:MI AM') TIME2, to_char(TIME_COMPLETE3, 'MM/DD/YYYY HH12:MI AM') TIME3,
				to_char(TIME_COMPLETE4, 'MM/DD/YYYY HH12:MI AM') TIME4, to_char(TIME_COMPLETE5, 'MM/DD/YYYY HH12:MI AM') TIME5, to_char(TIME_ENTERED1, 'MM/DD/YYYY HH12:MI AM') TIME_EN1,
				to_char(TIME_ENTERED2, 'MM/DD/YYYY HH12:MI AM') TIME_EN2, to_char(TIME_ENTERED3, 'MM/DD/YYYY HH12:MI AM') TIME_EN3, to_char(TIME_ENTERED4, 'MM/DD/YYYY HH12:MI AM') TIME_EN4,
				to_char(TIME_ENTERED5, 'MM/DD/YYYY HH12:MI AM') TIME_EN5 
			FROM SUPER_VESSEL_CLOSE SVC 
			WHERE PROD_REPORT_RUN = 'R'";
//	echo $sql."\n";
	ora_parse($per_vessel_cursor, $sql);
	ora_exec($per_vessel_cursor);
	while(ora_fetch_into($per_vessel_cursor, $per_vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// each vessel gets a separate email.
		$output_main = "<table>";
		$output_exceptions = "<table>";
		$output_details = "<table>";

		$vessel = $per_vessel_row['VESSEL'];
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$vessel;
		ora_parse($vessel_cursor, $sql);
		ora_exec($vessel_cursor);
		ora_fetch_into($vessel_cursor, $vessel_name, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_long_name = $vessel_name['VESSEL_NAME'];
		$vessel_array = split("-", $vessel_name['VESSEL_NAME']);
		$vessel_name = $vessel_array[1];

		//array-ify all the possible values.  FYI NOTE:  on average, only the first 2 get used. 
		$commodity_array = array($per_vessel_row['COMMODITY1'], $per_vessel_row['COMMODITY2'], $per_vessel_row['COMMODITY3'], $per_vessel_row['COMMODITY4'], $per_vessel_row['COMMODITY5']);
		$tonnage_array = array($per_vessel_row['TONNAGE1'], $per_vessel_row['TONNAGE2'], $per_vessel_row['TONNAGE3'], $per_vessel_row['TONNAGE4'], $per_vessel_row['TONNAGE5']);
		$QTY_array = array($per_vessel_row['QTY1'], $per_vessel_row['QTY2'], $per_vessel_row['QTY3'], $per_vessel_row['QTY4'], $per_vessel_row['QTY5']);
		$measurement_array = array($per_vessel_row['MEASUREMENT1'], $per_vessel_row['MEASUREMENT2'], $per_vessel_row['MEASUREMENT3'], $per_vessel_row['MEASUREMENT4'], $per_vessel_row['MEASUREMENT5']);
		$budget_array = array($per_vessel_row['BUDGET1'], $per_vessel_row['BUDGET2'], $per_vessel_row['BUDGET3'], $per_vessel_row['BUDGET4'], $per_vessel_row['BUDGET5']);
		$budget_per_array = array($per_vessel_row['BUDGET_PER1'], $per_vessel_row['BUDGET_PER2'], $per_vessel_row['BUDGET_PER3'], $per_vessel_row['BUDGET_PER4'], $per_vessel_row['BUDGET_PER5']);
		$super_array = array($per_vessel_row['SUPER1'], $per_vessel_row['SUPER2'], $per_vessel_row['SUPER3'], $per_vessel_row['SUPER4'], $per_vessel_row['SUPER5']);
		$time_complete_array = array($per_vessel_row['TIME1'], $per_vessel_row['TIME2'], $per_vessel_row['TIME3'], $per_vessel_row['TIME4'], $per_vessel_row['TIME5']);
		$time_entered_array = array($per_vessel_row['TIME_ENTERED1'], $per_vessel_row['TIME_ENTERED2'], $per_vessel_row['TIME_ENTERED3'], $per_vessel_row['TIME_ENTERED4'], $per_vessel_row['TIME_ENTERED5']);

		//Making an Oracle list off of an array is a pain.  But necessary to determine ALL commodities I need to pass over.
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
				$tonnage = $tonnage_array[$current_commodity];
				$QTY = $QTY_array[$current_commodity];
				$measurement = $measurement_array[$current_commodity];
				$budget = $budget_array[$current_commodity];
				$budget_per = $budget_per_array[$current_commodity];
				$super = $super_array[$current_commodity];
				$time_complete = $time_complete_array[$current_commodity];
				$time_entered = $time_entered_array[$current_commodity];

				// Commodity Groups.  $commodity_listing is formatted to be passed to an "IN" clause
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

				if($budget_per == 'TONS'){
					$measure_header = 'TONS';
					$measure_value = $tonnage;
				} else {
					$measure_header = 'UNITS';
					$measure_value = $QTY;
				}


				$output_main.= "<tr><td align=\"left\" colspan=\"12\">This Vessel has ".$exception_hours." hours coded to commodities not expected on the vessel.  See Details in ".$vessel."Exceptions.xls</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"12\">Below is the Summary for Commodity Group ".$commodity.":</td></tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"5\">".$vessel_name."</td>
									<td align=\"left\" colspan=\"2\">Berth:".$berth."</td>
									<td align=\"left\" colspan=\"3\">Completed: ".$time_complete."</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">".$measure_header."</td>
									<td align=\"left\" colspan=\"2\">BACKHAUL HOURS</td>
									<td align=\"left\" colspan=\"3\">PRODUCTIVITY (".$measure_header."/HOUR)</td>
									<td align=\"left\" colspan=\"3\">BUDGET (".$measure_header."/HOUR)</td>
									<td align=\"left\">Productivity to Budget</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">".$measure_value."</td>
									<td align=\"left\" colspan=\"2\">".$backhaul_hours."</td>
									<td align=\"left\" colspan=\"3\">".round($productivity, 1)."</td>
									<td align=\"left\" colspan=\"3\">".$budget."</td>
									<td align=\"left\">Variance</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"11\">&nbsp;</td>
									<td align=\"left\">".$variance."</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"11\">&nbsp;</td>
									<td align=\"left\">Percentage to Budget</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"11\">&nbsp;</td>
									<td align=\"left\">Variance</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"11\">&nbsp;</td>
									<td align=\"left\">".$efficiency."%</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\">&nbsp;</td>
									<td align=\"left\" colspan=\"11\">".$measure_header."</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">LR Num</td>
									<td align=\"left\" colspan=\"2\">Commodity</td>
									<td align=\"left\" colspan=\"2\">Commodity</td>
									<td align=\"left\" colspan=\"4\">".$measure_header."</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">".$vessel."</td>
									<td align=\"left\" colspan=\"2\">".$commodity_name."</td>
									<td align=\"left\" colspan=\"2\">".$measure_value."</td>
									<td align=\"left\" colspan=\"4\">".$measure_header."</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\">&nbsp;</td>
									<td align=\"left\" colspan=\"11\">Hours</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">LR Num</td>
									<td align=\"left\" colspan=\"2\">Distinct Employees</td>
									<td align=\"left\" colspan=\"2\">Total Hours</td>
									<td align=\"left\" colspan=\"2\">Backhaul Hours</td>
									<td align=\"left\" colspan=\"2\">Non-Backhaul Hours</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">".$vessel."</td>
									<td align=\"left\" colspan=\"2\">".$employees."</td>
									<td align=\"left\" colspan=\"2\">".$total_hours."</td>
									<td align=\"left\" colspan=\"2\">".$backhaul_hours."</td>
									<td align=\"left\" colspan=\"2\">".$non_backhaul_hours."</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\">&nbsp;</td>
									<td align=\"left\" colspan=\"11\">Hours By Supervisor</td>
								</tr>";
				$output_main.= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">LR Num</td>
									<td align=\"left\" colspan=\"2\">Supervsor</td>
									<td align=\"left\" colspan=\"2\">Hours Used</td>
									<td align=\"left\" colspan=\"4\">&nbsp;</td>
								</tr>";
				$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.COMMODITY_CODE IN ".$commodity_listing." GROUP BY USER_NAME ORDER BY USER_NAME";
				$statement = ora_parse($sum_hours_cursor, $sql);
				ora_exec($sum_hours_cursor);
				while(ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$supervisor_name = $sum_hours_row['USER_NAME'];
					$supervisor_hours = $sum_hours_row['HOURS'];
					$output_main.= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
										<td align=\"left\" colspan=\"2\">".$vessel."</td>
										<td align=\"left\" colspan=\"2\">".$supervisor_name."</td>
										<td align=\"left\" colspan=\"2\">".$supervisor_hours."</td>
										<td align=\"left\" colspan=\"4\">&nbsp;</td>
									</tr>";
				}

				$output_main.= "<tr><td align=\"left\" colspan=\"4\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">TOTAL</td>
									<td align=\"left\" colspan=\"6\">".$total_hours."</td>
								</tr>";
				$output_main .= "<tr><td align=\"left\" colspan=\"12\">&nbsp;</td></tr>";
				$output_main .= "<tr><td align=\"left\" colspan=\"12\">&nbsp;</td></tr>";
				$output_main .= "<tr><td align=\"left\" colspan=\"12\">&nbsp;</td></tr>";
				$output_main .= "<tr><td align=\"left\" colspan=\"12\">The following commodities are included in this report:</td></tr>";

				$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$commodity."' ORDER BY COMMODITY_CODE";
				$statement = ora_parse($generic_cursor, $sql);
				ora_exec($generic_cursor);
				while(ora_fetch_into($generic_cursor, $generic_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$output_main.= "<tr><td align=\"left\">&nbsp;</td>
										<td align=\"left\" colspan=\"11\">".$generic_row['COMMODITY_NAME']."</td>
									</tr>";
				}

				$output_main .= "<tr><td align=\"left\" colspan=\"12\">The service codes calculated for backhaul are any that begin with '61' or '641', but do not end in 0, and are not 6113.</td></tr><tr><td colspan=\"12\">&nbsp;</td></tr><tr><td colspan=\"12\">&nbsp;</td></tr>";

			}
		}
		$output_main.= "</table>";


// this section generates the same file that the above for loop does, but for
// commodities not specified for the given vessel

// get the number of hours that are backhaul
		$sql = "SELECT SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID  AND ((SERVICE_CODE LIKE '61%' AND SERVICE_CODE NOT LIKE '61%0' AND SERVICE_CODE != '6113') OR (SERVICE_CODE LIKE '641%' AND SERVICE_CODE != '6140')) AND A.COMMODITY_CODE NOT IN ".$non_commodity_listing;
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

		// get the number of employees
		$sql = "SELECT COUNT (DISTINCT EMPLOYEE_ID) EMPLS FROM HOURLY_DETAIL WHERE COMMODITY_CODE NOT IN ".$non_commodity_listing." AND VESSEL_ID = ".$vessel;
		$statement = ora_parse($distinct_employee_cursor, $sql);
		ora_exec($distinct_employee_cursor);
		ora_fetch_into($distinct_employee_cursor, $distinct_employee_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$employees = $distinct_employee_row['EMPLS'];

		$output_exceptions .= "<tr><td align=\"left\" colspan=\"12\">This Vessel has ".$exception_hours." hours coded to commodities not expected on the vessel.  Details are below:</td>
								</tr>";
		$output_exceptions .= "<tr><td align=\"left\" colspan=\"5\">".$vessel_name."</td>
									<td align=\"left\" colspan=\"2\">Berth:".$berth."</td>
									<td align=\"left\" colspan=\"3\">Completed: ".$time_complete."</td>
								</tr>";
		$output_exceptions .= "<tr><td align=\"left\">&nbsp;</td>
								<td align=\"left\" colspan=\"4\">Incorrectly coded by Supervisors</td>
								<td align=\"left\" colspan=\"7\">Hours</td>
								</tr>";
		$sql = "SELECT USER_NAME, SUM(DURATION) HOURS FROM HOURLY_DETAIL A, LCS_USER B WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND COMMODITY_CODE NOT IN ".$non_commodity_listing." GROUP BY USER_NAME ORDER BY USER_NAME";
		$statement = ora_parse($sum_hours_cursor, $sql);
		ora_exec($sum_hours_cursor);
		while(ora_fetch_into($sum_hours_cursor, $sum_hours_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$supervisor_name = $sum_hours_row['USER_NAME'];
			$supervisor_hours = $sum_hours_row['HOURS'];
			$output_exceptions .= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
										<td align=\"left\" colspan=\"3\">".$supervisor_name."</td>
										<td align=\"left\" colspan=\"7\">".$supervisor_hours."</td>
									</tr>";
		}

		$output_exceptions .= "<tr><td align=\"left\" colspan=\"2\">&nbsp;</td>
									<td align=\"left\" colspan=\"3\">TOTAL</td>
									<td align=\"left\" colspan=\"7\">".$exception_hours."</td>
								</tr>";

		$output_exceptions .= "<tr><td align=\"left\" colspan=\"12\">The service codes calculated for backhaul are any that begin with '61' or '641', but do not end in 0, and are not 6113.</td></tr>";

		$output_exceptions .= "</table>";






		$total_backhaul = 0;
		$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE AND ((A.SERVICE_CODE LIKE '61%' AND A.SERVICE_CODE NOT LIKE '61%0' AND A.SERVICE_CODE != '6113') OR (A.SERVICE_CODE LIKE '641%' AND A.SERVICE_CODE != '6410')) ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
		ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$output_details .= "<tr>
								<td align=\"left\" colspan = \"19\">Backhaul Hours Detail</td>
							</tr>";
		$output_details .= "<tr>
								<td align=\"left\">VESSEL</td>
								<td align=\"left\">SERVICE</td>
								<td align=\"left\">USER NAME</td>
								<td align=\"left\">HIRE DATE</td>
								<td align=\"left\">EMPLOYEE ID</td>
								<td align=\"left\">EMPLOYEE NAME</td>
								<td align=\"left\">HOURS</td>
								<td align=\"left\">COMMODITY CODE</td>
								<td align=\"left\">CUSTOMER ID</td>
								<td align=\"left\">EARNING TYPE</td>
								<td align=\"left\">SERVICE CODE</td>
								<td align=\"left\">EQUIPMENT ID</td>
								<td align=\"left\">LOCATION ID</td>
								<td align=\"left\">START TIME</td>
								<td align=\"left\">END TIME</td>
								<td align=\"left\">USER ID</td>
								<td align=\"left\">SPECIAL CODE</td>
								<td align=\"left\">EXCEPTION</td>
								<td align=\"left\">REMARK</td>
							</tr>";

		do {
			$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
			$statement = ora_parse($employee_name_cursor, $sql);
			ora_exec($employee_name_cursor);
			ora_fetch_into($employee_name_cursor, $employee_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$output_details .= "<tr>
									<td align=\"left\">".$main_data_row['VESSEL_ID']."</td>
									<td align=\"left\">".$main_data_row['SERVICE_NAME']."</td>
									<td align=\"left\">".$main_data_row['USER_NAME']."</td>
									<td align=\"left\">".$main_data_row['HIRE_DATE']."</td>
									<td align=\"left\">".$main_data_row['EMPLOYEE_ID']."</td>
									<td align=\"left\">".$employee_name_row['EMPLOYEE_NAME']."</td>
									<td align=\"left\">".$main_data_row['DURATION']."</td>
									<td align=\"left\">".$main_data_row['COMMODITY_CODE']."</td>
									<td align=\"left\">".$main_data_row['CUSTOMER_ID']."</td>
									<td align=\"left\">".$main_data_row['EARNING_TYPE_ID']."</td>
									<td align=\"left\">".$main_data_row['SERVICE_CODE']."</td>
									<td align=\"left\">".$main_data_row['EQUIPMENT_ID']."</td>
									<td align=\"left\">".$main_data_row['LOCATION_ID']."</td>
									<td align=\"left\">".$main_data_row['START_TIME']."</td>
									<td align=\"left\">".$main_data_row['END_TIME']."</td>
									<td align=\"left\">".$main_data_row['USER_ID']."</td>
									<td align=\"left\">".$main_data_row['SPECIAL_CODE']."</td>
									<td align=\"left\">".$main_data_row['EXCEPTION']."</td>
									<td align=\"left\">".$main_data_row['REMARK']."</td>
								</tr>";

				$total_backhaul += $main_data_row['DURATION'];
		} while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		$output_details .= "<tr><td align=\"left\" colspan=\"4\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">TOTAL</td>
									<td align=\"left\" colspan=\"13\">".$total_backhaul."</td>
								</tr>";

		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";
		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";
		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";
		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";

		$total_nonbackhaul = 0;
		$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE AND ((A.SERVICE_CODE NOT LIKE '61%' AND A.SERVICE_CODE NOT LIKE '641%') OR A.SERVICE_CODE LIKE '61%0' OR A.SERVICE_CODE = '6410' OR A.SERVICE_CODE = '6113') ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
		ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$output_details .= "<tr>
								<td align=\"left\" colspan = \"19\">Non-Backhaul Hours Detail</td>
							</tr>";
		$output_details .= "<tr>
								<td align=\"left\">VESSEL</td>
								<td align=\"left\">SERVICE</td>
								<td align=\"left\">USER NAME</td>
								<td align=\"left\">HIRE DATE</td>
								<td align=\"left\">EMPLOYEE ID</td>
								<td align=\"left\">EMPLOYEE NAME</td>
								<td align=\"left\">HOURS</td>
								<td align=\"left\">COMMODITY CODE</td>
								<td align=\"left\">CUSTOMER ID</td>
								<td align=\"left\">EARNING TYPE</td>
								<td align=\"left\">SERVICE CODE</td>
								<td align=\"left\">EQUIPMENT ID</td>
								<td align=\"left\">LOCATION ID</td>
								<td align=\"left\">START TIME</td>
								<td align=\"left\">END TIME</td>
								<td align=\"left\">USER ID</td>
								<td align=\"left\">SPECIAL CODE</td>
								<td align=\"left\">EXCEPTION</td>
								<td align=\"left\">REMARK</td>
							</tr>";

		do {
			$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
			$statement = ora_parse($employee_name_cursor, $sql);
			ora_exec($employee_name_cursor);
			ora_fetch_into($employee_name_cursor, $employee_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$output_details .= "<tr>
									<td align=\"left\">".$main_data_row['VESSEL_ID']."</td>
									<td align=\"left\">".$main_data_row['SERVICE_NAME']."</td>
									<td align=\"left\">".$main_data_row['USER_NAME']."</td>
									<td align=\"left\"><nobr>".$main_data_row['HIRE_DATE']."</nobr></td>
									<td align=\"left\">".$main_data_row['EMPLOYEE_ID']."</td>
									<td align=\"left\">".$employee_name_row['EMPLOYEE_NAME']."</td>
									<td align=\"left\">".$main_data_row['DURATION']."</td>
									<td align=\"left\">".$main_data_row['COMMODITY_CODE']."</td>
									<td align=\"left\">".$main_data_row['CUSTOMER_ID']."</td>
									<td align=\"left\">".$main_data_row['EARNING_TYPE_ID']."</td>
									<td align=\"left\">".$main_data_row['SERVICE_CODE']."</td>
									<td align=\"left\">".$main_data_row['EQUIPMENT_ID']."</td>
									<td align=\"left\">".$main_data_row['LOCATION_ID']."</td>
									<td align=\"left\"><nobr>".$main_data_row['START_TIME']."</nobr></td>
									<td align=\"left\"><nobr>".$main_data_row['END_TIME']."</nobr></td>
									<td align=\"left\">".$main_data_row['USER_ID']."</td>
									<td align=\"left\">".$main_data_row['SPECIAL_CODE']."</td>
									<td align=\"left\">".$main_data_row['EXCEPTION']."</td>
									<td align=\"left\">".$main_data_row['REMARK']."</td>
								</tr>";
			$total_nonbackhaul += $main_data_row['DURATION'];
		} while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$output_details .= "<tr><td align=\"left\" colspan=\"4\">&nbsp;</td>
									<td align=\"left\" colspan=\"2\">TOTAL</td>
									<td align=\"left\" colspan=\"13\">".$total_nonbackhaul."</td>
								</tr>";

		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";
		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";
		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";
		$output_details .= "<tr><td align=\"left\" colspan=\"19\">&nbsp;</td></tr>";

		$sql = "SELECT VESSEL_ID, SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID, DURATION, COMMODITY_CODE, CUSTOMER_ID, EARNING_TYPE_ID, A.SERVICE_CODE, EQUIPMENT_ID, LOCATION_ID, START_TIME, END_TIME, A.USER_ID, SPECIAL_CODE, EXCEPTION, REMARK, TIME_ENTRY, TIME_UPDATE, SF_ROW_NUMBER FROM HOURLY_DETAIL A, LCS_USER B, SERVICE C WHERE VESSEL_ID = ".$vessel." AND A.USER_ID = B.USER_ID AND A.SERVICE_CODE = C.SERVICE_CODE ORDER BY SERVICE_NAME, USER_NAME, HIRE_DATE, EMPLOYEE_ID";
	//  echo $sql."\n";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
		ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$output_details .= "<tr>
							<td align=\"left\" colspan = \"19\">Total Records</td>
						</tr>";
		$output_details .= "<tr>
								<td align=\"left\">VESSEL</td>
								<td align=\"left\">SERVICE</td>
								<td align=\"left\">USER NAME</td>
								<td align=\"left\">HIRE DATE</td>
								<td align=\"left\">EMPLOYEE ID</td>
								<td align=\"left\">EMPLOYEE NAME</td>
								<td align=\"left\">HOURS</td>
								<td align=\"left\">COMMODITY CODE</td>
								<td align=\"left\">CUSTOMER ID</td>
								<td align=\"left\">EARNING TYPE</td>
								<td align=\"left\">SERVICE CODE</td>
								<td align=\"left\">EQUIPMENT ID</td>
								<td align=\"left\">LOCATION ID</td>
								<td align=\"left\">START TIME</td>
								<td align=\"left\">END TIME</td>
								<td align=\"left\">USER ID</td>
								<td align=\"left\">SPECIAL CODE</td>
								<td align=\"left\">EXCEPTION</td>
								<td align=\"left\">REMARK</td>
							</tr>";

		do {
			$sql = "SELECT EMPLOYEE_NAME FROM EMPLOYEE WHERE EMPLOYEE_ID = '".$main_data_row['EMPLOYEE_ID']."'";
			$statement = ora_parse($employee_name_cursor, $sql);
			ora_exec($employee_name_cursor);
			ora_fetch_into($employee_name_cursor, $employee_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$output_details .= "<tr>
									<td align=\"left\">".$main_data_row['VESSEL_ID']."</td>
									<td align=\"left\">".$main_data_row['SERVICE_NAME']."</td>
									<td align=\"left\">".$main_data_row['USER_NAME']."</td>
									<td align=\"left\"><nobr>".$main_data_row['HIRE_DATE']."</nobr></td>
									<td align=\"left\">".$main_data_row['EMPLOYEE_ID']."</td>
									<td align=\"left\">".$employee_name_row['EMPLOYEE_NAME']."</td>
									<td align=\"left\">".$main_data_row['DURATION']."</td>
									<td align=\"left\">".$main_data_row['COMMODITY_CODE']."</td>
									<td align=\"left\">".$main_data_row['CUSTOMER_ID']."</td>
									<td align=\"left\">".$main_data_row['EARNING_TYPE_ID']."</td>
									<td align=\"left\">".$main_data_row['SERVICE_CODE']."</td>
									<td align=\"left\">".$main_data_row['EQUIPMENT_ID']."</td>
									<td align=\"left\">".$main_data_row['LOCATION_ID']."</td>
									<td align=\"left\"><nobr>".$main_data_row['START_TIME']."</nobr></td>
									<td align=\"left\"><nobr>".$main_data_row['END_TIME']."</nobr></td>
									<td align=\"left\">".$main_data_row['USER_ID']."</td>
									<td align=\"left\">".$main_data_row['SPECIAL_CODE']."</td>
									<td align=\"left\">".$main_data_row['EXCEPTION']."</td>
									<td align=\"left\">".$main_data_row['REMARK']."</td>
								</tr>";
		} while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

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

		// so how 'bout some emails?
		$File_main=chunk_split(base64_encode($output_main));
		$File_exceptions=chunk_split(base64_encode($output_exceptions));
		$File_details=chunk_split(base64_encode($output_details));

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'PRODUCTIVITYREPORT'";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
		ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	   
		$mailTO = $email_row['TO'];
		$mailheaders = "From: ".$email_row['FROM']."\r\n";

		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$mailSubject = $email_row['SUBJECT'];
		$mailSubject = str_replace("_0_", $vessel_long_name, $mailSubject);

		$body = $email_row['NARRATIVE'];
		$body = str_replace("_1_", $last_person_entered, $body);
		$body = str_replace("_2_", $last_time_entered, $body);
		$body = str_replace("_newline_", "\r\n", $body);

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/html; name=\"".$vessel."MainReport.html\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File_main;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/html; name=\"".$vessel."Exceptions.html\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File_exceptions;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/html; name=\"".$vessel."Details.html\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File_details;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/html; name=\"".$vessel."MainReport.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File_main;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/html; name=\"".$vessel."Exceptions.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File_exceptions;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/html; name=\"".$vessel."Details.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$File_details;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY--\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'DAILYCRON',
						SYSDATE,
						'EMAIL',
						'PRODUCTIVITYREPORT',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
		}

		$sql = "UPDATE SUPER_VESSEL_CLOSE SET PROD_REPORT_RUN = 'Y', DATE_REPORT_RUN = SYSDATE WHERE VESSEL = '".$vessel."'";
		ora_parse($vessel_update_cursor, $sql);
		ora_exec($vessel_update_cursor);

		// and that's the vessel.
		// loop in case there are more.
	}