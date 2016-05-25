<?
/* Adam Walter, 8/25/07
*  Cron job that auto-generates timesheets for people who have not
*  (Out of office, inatentiveness, sheer laziness, whatever).
*
*  Cron expected to be run at 9:59 ---Monday Morning---, unless changes are
*  Made to the TS program.  If changes ARE made, very carefully
*  Scrutinize the Insert_TS routine and the variable $last_week.
*
*	MAJOR UPDATE (04/20/2008)
*	It has been determined that the hard cutoff of Monday @10AM was too strict,
*	(and even if it wasn't, too many people with memory issues or transportation
*	problems were resulting in "misses"), so the logic is changing as follows:
*	--- If no sheet exsists, continue to create the default 40
*	--- If sheet exists in submitted form, continue to do nothing
*	--- If sheet exists in unsubmitted form, LEAVE CURRENT sheet, but
*	------ swap status to "conditional", to allow supv option to
*	------ conditionally approve
********************************************************************************/


   $last_week = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 7, date('Y')));
   $last_week_readable = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 7, date('Y')));

/*
	// these for use when testing this script 
   $last_week = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 6, date('Y')));
   $last_week_readable = date('mm/dd/yyyy', mktime(0,0,0,date('m'), date('d') - 6, date('Y')));
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
   $OutputCursor = ora_open($conn);
   $FunctionCursor = ora_open($conn);

	$sql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE' AND FIRST_WEEK_PAID_DATE <= '".$last_week."'";
	ora_parse($OuterCursor, $sql);
	ora_exec($OuterCursor);
	while(ora_fetch_into($OuterCursor, $OuterRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '".$last_week."' AND EMPLOYEE_ID = '".$OuterRow['EMPLOYEE_ID']."'";
		ora_parse($InnerCursor, $sql);
		ora_exec($InnerCursor);
		if(!ora_fetch_into($InnerCursor, $InnerRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// no record, create one
			echo "\r\nInsert ".$OuterRow['EMPLOYEE_ID']."\r\n";
			Insert_TS($OuterRow['EMPLOYEE_ID'], $FunctionCursor);

//			Send_Mail($OuterRow['EMPLOYEE_ID'], "insert", $FunctionCursor);
		} else {
			// record exists...
			if($InnerRow['STATUS'] == 'APPROVED' || $InnerRow['STATUS'] == 'SUBMITTED'){
				// existing record is valid, do nothing
			} else {
				$sql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'AutoConditional - By Cron' FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$OuterRow['EMPLOYEE_ID']."' AND WEEK_START_MONDAY = '".$last_week."')";
				ora_parse($OutputCursor, $sql);
				ora_exec($OutputCursor);

				$sql = "UPDATE TIME_SUBMISSION SET CONDITIONAL_SUBMISSION = 'Y' WHERE WEEK_START_MONDAY = '".$last_week."' AND EMPLOYEE_ID = '".$OuterRow['EMPLOYEE_ID']."'"; 
				ora_parse($OutputCursor, $sql);
				ora_exec($OutputCursor);

//				Send_Mail($OuterRow['EMPLOYEE_ID'], "conditionalized", $FunctionCursor);
				
/*				
				// existing record invalid (either DENIED or ON HOLD);
				// move existing record to history table, purge, and create.
				
				$sql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'Autopurge - By Cron' FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$OuterRow['EMPLOYEE_ID']."' AND WEEK_START_MONDAY = '".$last_week."')";
				ora_parse($OutputCursor, $sql);
				ora_exec($OutputCursor);

				$sql = "DELETE FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '".$last_week."' AND EMPLOYEE_ID = '".$OuterRow['EMPLOYEE_ID']."'";
				ora_parse($OutputCursor, $sql);
				ora_exec($OutputCursor);

			echo "\r\nPurge ".$OuterRow['EMPLOYEE_ID']."\r\n";
				Insert_TS($OuterRow['EMPLOYEE_ID'], $FunctionCursor);

				Send_Mail($OuterRow['EMPLOYEE_ID'], "purged", $FunctionCursor); */
			}
		}
	}


// this function both creates a week for a given employee,
// and emails said employee + employee's supervisor of its action.
function Insert_TS($employee, $FunctionCursor){ // week is always last week

//   global $last_week, $last_week_readable; 

	$Monday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 7, date('Y')));
	$Tuesday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 6, date('Y')));
	$Wednesday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 5, date('Y')));
	$Thursday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 4, date('Y')));
	$Friday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 3, date('Y')));
	$Saturday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 2, date('Y')));
	$Sunday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));

/*
	// these values exist for my testing this program.  Numbers change depending on date tested.
	$Monday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 6, date('Y')));
	$Tuesday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 5, date('Y')));
	$Wednesday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 4, date('Y')));
	$Thursday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 3, date('Y')));
	$Friday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 2, date('Y')));
	$Saturday = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
	$Sunday = date('d-M-Y', mktime(0,0,0,date('m'), date('d'), date('Y')));
*/
	// grab all-Timesheet totals for given year
	$sql = "SELECT NVL(SUM(WEEK_TOTAL_TOTAL), 0) THE_TOTAL, 
         NVL(SUM(WEEK_TOTAL_REG), 0) THE_REG, 
         NVL(SUM(WEEK_TOTAL_HOLIDAY), 0) THE_HOLIDAY, 
         NVL(SUM(WEEK_TOTAL_VACATION), 0) THE_VACATION, 
         NVL(SUM(WEEK_TOTAL_PERSONAL), 0) THE_PERSONAL, 
         NVL(SUM(WEEK_TOTAL_SICK), 0) THE_SICK, 
         NVL(SUM(WEEK_TOTAL_OVERTIME), 0) THE_OVERTIME 
         FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$employee."' AND WEEK_START_MONDAY < '".$Monday."' AND FRI_DATE >= '01-JAN-".substr($Monday, -4)."' AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$YTD_WEEK_START_TOTAL_TOTAL = $FunctionRow['THE_TOTAL'];
	$YTD_WEEK_START_TOTAL_REG = $FunctionRow['THE_REG'];
	$YTD_WEEK_START_TOTAL_HOLIDAY = $FunctionRow['THE_HOLIDAY'];
	$YTD_WEEK_START_TOTAL_VACATION = $FunctionRow['THE_VACATION'];
	$YTD_WEEK_START_TOTAL_PERSONAL = $FunctionRow['THE_PERSONAL'];
	$YTD_WEEK_START_TOTAL_SICK = $FunctionRow['THE_SICK'];
	$YTD_WEEK_START_TOTAL_OVERTIME = $FunctionRow['THE_OVERTIME'];

	// grab #weeks worked for given year
	$sql = "SELECT COUNT(*) THE_WEEKS FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$employee."' AND FRI_DATE >= '01-JAN-".substr($Monday, -4)."' AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$NoOfWeeks = $FunctionCursor['THE_WEEKS'] + 1;

	// oddball calculation, given that at 1 week, the numerator *should* always be 0 anyway.
	// but as I don't want unapproved timesheet holdovers across years to screw up calculations in
	// an unforseen way...
	if($NoOfWeeks == 1){
		$YTD_WEEK_START_AVERAGE_OT = 0;
	} else {
		$YTD_WEEK_START_AVERAGE_OT = $YTD_WEEK_START_TOTAL_OVERTIME / $NoOfWeeks;
	}

	// get employee's current aggregated values.
	// note that if employee has old, pending timesheets, these inserted values will be incorrect after this function runs;
	// however, the updating procedure done by the supervisors at the Approval screen in the main TS program
	// will backdate and correctly fill the values as necessary; further, said incorrect values will not be
	// shown to the employee, as if they have pending timesheets, the aggregate value fields are hidden.
	$sql = "SELECT NVL(VACATION_WEEKLY_RATE, 0) VAC_RATE, NVL(SICK_WEEKLY_RATE, 0) SICK_RATE, NVL(VACATION_YTD_REMAIN, 0) VAC_BAL, NVL(PERSONAL_YTD_REMAIN, 0) PER_BAL, NVL(SICK_YTD_REMAIN, 0) SICK_BAL, SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."'";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$WEEKLY_ACCRUAL_VACATION = $FunctionRow['VAC_RATE'];
	$WEEKLY_ACCRUAL_SICK = $FunctionRow['SICK_RATE'];
	$YTD_WEEK_START_VACATION_BAL = $FunctionRow['VAC_BAL'];
	$YTD_WEEK_START_PERSONAL_BAL = $FunctionRow['PER_BAL'];
	$YTD_WEEK_START_SICK_BAL = $FunctionRow['SICK_BAL'];

	if ($FunctionRow['SUPERVISOR_ID'] == "N/A"){
		$status = "APPROVED";
	} else {
		$status = "ON HOLD";
	}

	// 2 steps to put record into DB.
	// while I could "technically" do this in a single statement, for ease of code read-ability,
	// I am doing a small insert, followed by a large update.
	$sql = "INSERT INTO TIME_SUBMISSION (EMPLOYEE_ID, WEEK_START_MONDAY, WEEK_END_SUNDAY, STATUS) VALUES ('".$employee."', '".$Monday."', '".$Sunday."', 'ON HOLD')";
	echo $sql."\n";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
			
	$sql = "UPDATE TIME_SUBMISSION SET
			STATUS = '".$status."',
			SUBMISSION_PC_USERID = 'cron',
			SUBMISSION_PC = 'cron',
			SUBMISSION_DATETIME = SYSDATE,
			MON_DATE = '".$Monday."',
			MON_TOTAL = 8,
			MON_REG = 8,
			MON_HOLIDAY = 0,
			MON_VACATION = 0,
			MON_PERSONAL = 0,
			MON_SICK = 0,
			MON_OVERTIME = 0,
			TUE_DATE = '".$Tuesday."',
			TUE_TOTAL = 8,
			TUE_REG = 8,
			TUE_HOLIDAY = 0,
			TUE_VACATION = 0,
			TUE_PERSONAL = 0,
			TUE_SICK = 0,
			TUE_OVERTIME = 0,
			WED_DATE = '".$Wednesday."',
			WED_TOTAL = 8,
			WED_REG = 8,
			WED_HOLIDAY = 0,
			WED_VACATION = 0,
			WED_PERSONAL = 0,
			WED_SICK = 0,
			WED_OVERTIME = 0,
			THU_DATE = '".$Thursday."',
			THU_TOTAL = 8,
			THU_REG = 8,
			THU_HOLIDAY = 0,
			THU_VACATION = 0,
			THU_PERSONAL = 0,
			THU_SICK = 0,
			THU_OVERTIME = 0,
			FRI_DATE = '".$Friday."',
			FRI_TOTAL = 8,
			FRI_REG = 8,
			FRI_HOLIDAY = 0,
			FRI_VACATION = 0,
			FRI_PERSONAL = 0,
			FRI_SICK = 0,
			FRI_OVERTIME = 0,
			SAT_TOTAL = 0,
			SAT_DATE = '".$Saturday."',
			SAT_REG = 0,
			SAT_OVERTIME = 0,
			SUN_DATE = '".$Sunday."',
			SUN_TOTAL = 0,
			SUN_REG = 0,
			SUN_OVERTIME = 0,
			WEEK_TOTAL_TOTAL = 40,
			WEEK_TOTAL_REG = 40,
			WEEK_TOTAL_HOLIDAY = 0,
			WEEK_TOTAL_VACATION = 0,
			WEEK_TOTAL_PERSONAL = 0,
			WEEK_TOTAL_SICK = 0,
			WEEK_TOTAL_OVERTIME = 0,
			WEEKLY_ACCRUAL_VACATION = ".($WEEKLY_ACCRUAL_VACATION / 1).",
			WEEKLY_ACCRUAL_SICK = ".($WEEKLY_ACCRUAL_SICK / 1).",
			YTD_WEEK_START_VACATION_BAL = ".($YTD_WEEK_START_VACATION_BAL / 1).",
			YTD_WEEK_START_PERSONAL_BAL = ".($YTD_WEEK_START_PERSONAL_BAL / 1).",
			YTD_WEEK_START_SICK_BAL = ".($YTD_WEEK_START_SICK_BAL / 1).",
			YTD_WEEK_START_AVERAGE_OT = '".$YTD_WEEK_START_AVERAGE_OT."',
			CONDITIONAL_SUBMISSION = 'Y',
			YTD_WEEK_START_TOTAL_TOTAL = '".$YTD_WEEK_START_TOTAL_TOTAL."',
			YTD_WEEK_START_TOTAL_REG = '".$YTD_WEEK_START_TOTAL_REG."',
			YTD_WEEK_START_TOTAL_HOLIDAY = '".$YTD_WEEK_START_TOTAL_HOLIDAY."',
			YTD_WEEK_START_TOTAL_VACATION = '".$YTD_WEEK_START_TOTAL_VACATION."',
			YTD_WEEK_START_TOTAL_PERSONAL = '".$YTD_WEEK_START_TOTAL_PERSONAL."',
			YTD_WEEK_START_TOTAL_SICK = '".$YTD_WEEK_START_TOTAL_SICK."',
			YTD_WEEK_START_TOTAL_OVERTIME = '".$YTD_WEEK_START_TOTAL_OVERTIME."'
			WHERE EMPLOYEE_ID = '".$employee."' AND WEEK_START_MONDAY = '".$Monday."'";
//	echo "\r\n".$sql."\r\n";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
}


function Send_Mail($employee, $type, $FunctionCursor){
/*
   global $last_week, $last_week_readable; 
	
	// sends mail to anyone who'se missed / butchered a timesheet.
	// I get the 2 addresses in 2 steps to save myself a do-while loop.
	$mailTo = "";
	$mailSubject = "";
	$mailHeaders = "";
	$body = "";
	
	$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."'";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$mailTo = $FunctionRow['EMAIL_ADDRESS'];
	$mailHeaders = "From:  TimeSheet(NoReplies)\r\n";

	$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."')";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	if(ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailHeaders .= "CC:  ".$FunctionRow['EMAIL_ADDRESS']."\r\n";
	}
	// these two clauses used to return different values, but they no longer do.
	// in the event that changes, I'm leaving the code, to make modifying it easier.
	if($type == "purged"){
		// this one should not happen after april 20th, 2008.
		$mailSubject = "Timesheet NOT Submitted";

		$body = "Since you did not submit a timesheet for the week of ".$last_week_readable." before the cutoff time, the system auto-generated a CONDITIONAL timesheet for a standard 40 hours.\r\n\r\nThe system will not permit you to enter any new timesheets until you review and re-submit all conditional timesheets.   You will be prompted the next time you start the timesheet program.";
	} elseif($type == "insert") {
		$mailSubject = "Timesheet NOT Created";

		$body = "Since you did not create a timesheet for the week of ".$last_week_readable." before the cutoff time, the system auto-generated a CONDITIONAL timesheet for a standard 40 hours.\r\n\r\nThe system will not permit you to enter any new timesheets until you review and re-submit all conditional timesheets.   You will be prompted the next time you start the timesheet program.";
	} else {
		$mailSubject = "Timesheet NOT Submitted";

		$body = "Since you did not submit your timesheet for the week of ".$last_week_readable." before the cutoff time, the system has amended your timesheet for the week into CONDITIONAL status.\r\n\r\nThe conditional timesheet has been presented to your supervisor for approval, but you will have to confirm it before you are allowed to enter future timesheets.\r\n\r\nYou will be prompted the next time you start the timesheet program.";
	}

	 mail($mailTo, $mailSubject, $body, $mailHeaders); */
//	echo $mailTo."\n".$mailSubject."\n".$body."\n".$mailHeaders."\n\n";
}