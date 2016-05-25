<?
/* Adam Walter, June 2007.
*  
*  This script will re-calculate the ENTIRE TIME_SUBMISSION table,
*  going from the values in the "INITIAL" fields of 
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

   //get DB connection
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");

  if($conn < 1){
    	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $ChangeCursor = ora_open($conn);
   $Short_Term_Data = ora_open($conn);
   ora_commitoff($conn);

	
	$employee = array();
	$employee_row_counter = 0;


	// pre-scripting:  Alter data in AT_EMPLOYEE accordingly to allow future calculations.
	// ignoring the 2 inactive employees (stans and don), as well as E408178 (L Sanchez).
	$sql = "UPDATE AT_EMPLOYEE SET 
			VACATION_YTD_ACCRUED = 0,
			VACATION_YTD_TAKEN = 0,
			VACATION_YTD_REMAIN = INITIAL_VACATION,
			SICK_YTD_ACCRUED = 0,
			SICK_YTD_TAKEN = 0,
			SICK_YTD_REMAIN = INITIAL_SICK,
			PERSONAL_YTD_TAKEN = 0,
			PERSONAL_YTD_REMAIN = INITIAL_PERSONAL
			WHERE EMPLOYMENT_STATUS = 'ACTIVE'
			AND EMPLOYEE_ID != 'E408178'";
	ora_parse($ChangeCursor, $sql);
	ora_exec($ChangeCursor);


	// get a list of all EMPLOYEE_ID's, and store them in an ARRAY for futher use.
	// getting the ID's this way prevents a cursor overlap error later on.
	$sql = "SELECT EMPLOYEE_ID FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE' AND EMPLOYEE_ID != 'E408178' ORDER BY EMPLOYEE_ID";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	while(ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$employee[$employee_row_counter] = $Short_Term_Row['EMPLOYEE_ID'];
		$employee_row_counter++;
	}


	for($i = 0; $i < sizeof($employee); $i++){
		// assign current employee
		$current_employee = $employee[$i];

		// varaiable to determine if conditional timesheet is hit, which prevents further calculations
		$found_conditional = false;
		$no_more_timesheets = false;

		// get employee's first week in T_Sub
		$sql = "SELECT TO_CHAR(MIN(WEEK_START_MONDAY), 'MM/DD/YYYY') THE_WEEK FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$current_employee."'";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$week = $Short_Term_Row['THE_WEEK'];
		$temp = split("/", $week);
		$week = mktime(0,0,0,$temp[0],$temp[1],$temp[2]);



		while(!$found_conditional && !$no_more_timesheets){ 
			// once we find a conditional timesheet, or hit the last record in the table, NO FURTHER calculations are done for this employee
			$ora_week = date('d-M-Y', $week);

			$sql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$current_employee."' AND WEEK_START_MONDAY = '".$ora_week."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// no more records for this employee.  Break loop.
				$no_more_timesheets = true;
			} else {
				if($row['STATUS'] != 'APPROVED' || $row['CONDITIONAL_SUBMISSION'] == 'Y'){
					// no further calculations for this employee due to conditional timesheet.  Set the "loop-break" variable.
					$found_conditional = true;
				} else {
					// time for calculations.

					// step 1:  get the raw values.
					$TOTAL_TOTAL = $row['YTD_WEEK_START_TOTAL_TOTAL'] + $row['WEEK_TOTAL_TOTAL'];
					$TOTAL_REG = $row['YTD_WEEK_START_TOTAL_REG'] + $row['WEEK_TOTAL_REG'];
					$TOTAL_HOLIDAY = $row['YTD_WEEK_START_TOTAL_HOLIDAY'] + $row['WEEK_TOTAL_HOLIDAY'];
					$TOTAL_VACATION = $row['YTD_WEEK_START_TOTAL_VACATION'] + $row['WEEK_TOTAL_VACATION'];
					$TOTAL_PERSONAL = $row['YTD_WEEK_START_TOTAL_PERSONAL'] + $row['WEEK_TOTAL_PERSONAL'];
					$TOTAL_SICK = $row['YTD_WEEK_START_TOTAL_SICK'] + $row['WEEK_TOTAL_SICK'];
					$TOTAL_OVERTIME = $row['YTD_WEEK_START_TOTAL_OVERTIME'] + $row['WEEK_TOTAL_OVERTIME'];
					$VAC_BAL = $row['YTD_WEEK_START_VACATION_BAL'] + $row['WEEKLY_ACCRUAL_VACATION'] - $row['WEEK_TOTAL_VACATION'];
					$SICK_BAL = $row['YTD_WEEK_START_SICK_BAL'] + $row['WEEKLY_ACCRUAL_SICK'] - $row['WEEK_TOTAL_SICK'];
					$PERS_BAL = $row['YTD_WEEK_START_PERSONAL_BAL'] - $row['WEEK_TOTAL_PERSONAL'];

					$sql = "SELECT COUNT(*) THE_COUNT FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$current_employee."' AND
							WEEK_START_MONDAY <= '".$ora_week."' AND WEEK_START_MONDAY > '01-JAN-".substr($ora_week, -4)."'";
					ora_parse($Short_Term_Data, $sql);
					ora_exec($Short_Term_Data);
					ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$weeks_for_overtime = $Short_Term_Row['THE_COUNT'];
					$avg_OT = $TOTAL_OVERTIME / $weeks_for_overtime;


					// step 2:  Update AT_EMPLOYEE table.
					$sql = "UPDATE AT_EMPLOYEE SET
							VACATION_YTD_ACCRUED = VACATION_YTD_ACCRUED + '".$row['WEEKLY_ACCRUAL_VACATION']."',
							VACATION_YTD_TAKEN = '".$TOTAL_VACATION."',
							VACATION_YTD_REMAIN = '".$VAC_BAL."',
							SICK_YTD_ACCRUED = SICK_YTD_ACCRUED + '".$row['WEEKLY_ACCRUAL_SICK']."',
							SICK_YTD_TAKEN = '".$TOTAL_SICK."',
							SICK_YTD_REMAIN = '".$SICK_BAL."',
							PERSONAL_YTD_TAKEN = '".$TOTAL_PERSONAL."',
							PERSONAL_YTD_REMAIN = '".$PERS_BAL."'
							WHERE EMPLOYEE_ID = '".$current_employee."'";
					ora_parse($ChangeCursor, $sql);
					ora_exec($ChangeCursor);


					// step 3:  Update all following week's YTD_START values.  Note:  if no following weeks exist,
					// this next routine will update 0 rows (which is correct in that case).
					// This logic is consistent with the VB code's population of these fields, which is that
					// if a week is created before previous weeks are non-conditionally approved, it grabs
					// the current value in AT_EMPLOYEE for that value (which we just placed in AT_EMPLOYEE with step 2)
					$sql = "UPDATE TIME_SUBMISSION SET
							YTD_WEEK_START_TOTAL_TOTAL = '".$TOTAL_TOTAL."',
							YTD_WEEK_START_TOTAL_REG = '".$TOTAL_REG."',
							YTD_WEEK_START_TOTAL_HOLIDAY = '".$TOTAL_HOLIDAY."',
							YTD_WEEK_START_TOTAL_VACATION = '".$TOTAL_VACATION."',
							YTD_WEEK_START_TOTAL_PERSONAL = '".$TOTAL_PERSONAL."',
							YTD_WEEK_START_TOTAL_SICK = '".$TOTAL_SICK."',
							YTD_WEEK_START_TOTAL_OVERTIME = '".$TOTAL_OVERTIME."',
							YTD_WEEK_START_AVERAGE_OT = '".$avg_OT."',
							YTD_WEEK_START_VACATION_BAL = '".$VAC_BAL."',
							YTD_WEEK_START_PERSONAL_BAL = '".$PERS_BAL."',
							YTD_WEEK_START_SICK_BAL = '".$SICK_BAL."'
							WHERE EMPLOYEE_ID = '".$current_employee."'
							AND WEEK_START_MONDAY > '".$ora_week."'";
					ora_parse($ChangeCursor, $sql);
					ora_exec($ChangeCursor);


					// step 4:  adjust the YTD_END values for the current week.
					// this step is saved until last because once executed, the database cursor for the current
					// week will no longer be valid; but with no rows left to calculate, that is a non issue.
					$sql = "UPDATE TIME_SUBMISSION SET
							YTD_WEEK_END_TOTAL_TOTAL = '".$TOTAL_TOTAL."',
							YTD_WEEK_END_TOTAL_REG = '".$TOTAL_REG."',
							YTD_WEEK_END_TOTAL_HOLIDAY = '".$TOTAL_HOLIDAY."',
							YTD_WEEK_END_TOTAL_VACATION = '".$TOTAL_VACATION."',
							YTD_WEEK_END_TOTAL_PERSONAL = '".$TOTAL_PERSONAL."',
							YTD_WEEK_END_TOTAL_SICK = '".$TOTAL_SICK."',
							YTD_WEEK_END_TOTAL_OVERTIME = '".$TOTAL_OVERTIME."',
							YTD_WEEK_END_AVERAGE_OT = '".$avg_OT."',
							YTD_WEEK_END_VACATION_BAL = '".$VAC_BAL."',
							YTD_WEEK_END_PERSONAL_BAL = '".$PERS_BAL."',
							YTD_WEEK_END_SICK_BAL = '".$SICK_BAL."'
							WHERE EMPLOYEE_ID = '".$current_employee."'
							AND WEEK_START_MONDAY = '".$ora_week."'";
					ora_parse($ChangeCursor, $sql);
					ora_exec($ChangeCursor);
				}
			}
			// update complete for this employee for this week.  Increment week, and loop to next.
			$week += 604800;
		}
		// this employee is complete.  loop to next.
	}
	ora_commit($conn);

?>