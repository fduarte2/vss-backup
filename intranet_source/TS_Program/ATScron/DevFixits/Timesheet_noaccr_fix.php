<?
/* Adam Walter, 8/25/07
*  
*  There was a bug in the auto-creation script for timesheets that resulted in people
*  Not getting their accrued vacation time.  This script goes back through all timesheets
*  For all employees and gives them their values.
*/



//   $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $OuterCursor = ora_open($conn);
   $InnerCursor = ora_open($conn);
   $Short_Term_Cursor = ora_open($conn);
   $CorrectionCursor = ora_open($conn);
   ora_commitoff($conn);

  
	// initialize variables used later for updating AT_EMPLOYEE
	$vacation_AT_EMP_total = 0;
	$sick_AT_EMP_total = 0;


	$sql = "SELECT EMPLOYEE_ID, VACATION_WEEKLY_RATE, SICK_WEEKLY_RATE FROM AT_EMPLOYEE ORDER BY EMPLOYEE_ID";
	ora_parse($OuterCursor, $sql);
	ora_exec($OuterCursor);
	while(ora_fetch_into($OuterCursor, $OuterRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// get each employee in turn, and their per-week values for vacation and sick
		$employee = $OuterRow['EMPLOYEE_ID'];
		$weekly_value_vac = $OuterRow['VACATION_WEEKLY_RATE'];
		$weekly_value_sick = $OuterRow['SICK_WEEKLY_RATE'];
		$vacation_AT_EMP_total = 0;
		$sick_AT_EMP_total = 0;

		// first week of calculation, as is the first week with errors for anyone.  Reset for each employee.
		$week = mktime(0,0,0,9,3,2007);

		while($week < mktime(0,0,0,10,8,2007)){
			$ora_week = date('d-M-Y', $week);
			// correction to script was made before 10/8/2007 week, so that marks the end of fixes

			$sql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$employee."' AND WEEK_START_MONDAY = '".$ora_week."' AND WEEKLY_ACCRUAL_VACATION = 0 AND WEEKLY_ACCRUAL_SICK = 0";
			ora_parse($InnerCursor, $sql);
			ora_exec($InnerCursor);
			if(!ora_fetch_into($InnerCursor, $InnerRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// this week either doesn't exist for this employee, or WEEKLY_ACCRUAL rates are non-zero, so no fixes this pass
			} else {
				// pre-step:  grab some data for later use
				$YTD_WEEK_END_VACATION_BAL = $InnerRow['YTD_WEEK_END_VACATION_BAL'];

				// step 1:  place accrual numbers into TS record
				$sql = "UPDATE TIME_SUBMISSION SET WEEKLY_ACCRUAL_VACATION = '".$weekly_value_vac."', WEEKLY_ACCRUAL_SICK = '".$weekly_value_sick."' WHERE EMPLOYEE_ID = '".$employee."' AND WEEK_START_MONDAY = '".$ora_week."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				// step 2:  Update all future YTD_START values with the accrual value.  This calculation will not step on any other's toes,
				// as any past-week-approval calculations done later on will over-write these values anyway from the VB side.
				// new YTD_START values necessary for step 3, however, as the recursion continues
				$sql = "UPDATE TIME_SUBMISSION SET YTD_WEEK_START_VACATION_BAL = YTD_WEEK_START_VACATION_BAL + '".$weekly_value_vac."', YTD_WEEK_START_SICK_BAL = YTD_WEEK_START_SICK_BAL + '".$weekly_value_sick."' WHERE EMPLOYEE_ID = '".$employee."' AND WEEK_START_MONDAY > '".$ora_week."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				// Step 3:  Check if YTD_END calculations for this week already done.
				if($YTD_WEEK_END_VACATION_BAL != ""){

					// Step 3a:  add to running totals for later modification of AT_EMP
					$vacation_AT_EMP_total += $weekly_value_vac;
					$sick_AT_EMP_total += $weekly_value_sick;
				}
			}

			// Step 4 (final):  increment week, and loop
			$week += 604800; // seconds in a week
		}

		// With the loops of TIME_SUBMISSION done, increment balance and accrued values in AT_EMPLOYEE for this employee
		$sql = "UPDATE AT_EMPLOYEE SET VACATION_YTD_ACCRUED = VACATION_YTD_ACCRUED + '".$vacation_AT_EMP_total."', VACATION_YTD_REMAIN = VACATION_YTD_REMAIN + '".$vacation_AT_EMP_total."', SICK_YTD_ACCRUED = SICK_YTD_ACCRUED + '".$sick_AT_EMP_total."', SICK_YTD_REMAIN = SICK_YTD_REMAIN + '".$sick_AT_EMP_total."'";


		// calculations over.  loop to next employee.
	}

	// Step 5:  update YTD end values for rows in TIME_SUBMISSION that have had their YTD_END values already calculated prior to this script.
	// With steps 1 and 2 having already been taken care of, it's straight arithmetic going across the row.
	$sql = "UPDATE TIME_SUBMISSION SET YTD_WEEK_END_VACATION_BAL = YTD_WEEK_START_VACATION_BAL + WEEKLY_ACCRUAL_VACATION - WEEK_TOTAL_VACATION, YTD_WEEK_END_SICK_BAL = YTD_WEEK_START_SICK_BAL + WEEKLY_ACCRUAL_SICK - WEEK_TOTAL_SICK WHERE YTD_WEEK_END_VACATION_BAL IS NOT NULL";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_commit($conn);
