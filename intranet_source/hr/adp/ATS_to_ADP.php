<?
/* Adam Walter, June 2007.
*  
*  This script is part of the new Administrative Building Timesheet Process.
*  What it does is takes the values entered into the database by the ATS
*  Program, and converts them into a .CSV file for importing into ADP.
*
*  Instructions on said file can be found in sourcesafe, along with this code and
*  All of the ATS program files.
*
*
*******************  MAJOR REVISION 10/8/2007 - ???  ***********
*
*  In an attempt to increase sanity on the part of me, I am purging all YTD_END
*  calculations from the main VB timesheet program, and adding them to this
*  Script.  In essence, I am changing the paradigm of the program from 
*  "Your YTD values are calculated from when the supervisor hits approve"
*  to
*  "Your YTD values are calculated when ADP is processed"
*  By taking the multiple locations the calculations reside in and moving them
*  To one file, I should cut down on many a bugged accrual.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

   //get DB connection
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238"); 
  $conn = ora_logon("SAG_OWNER@BNI", "SAG"); 

  if($conn < 1){
    	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $autoapproval_cursor_outer = ora_open($conn);
   $autoapproval_cursor_inner = ora_open($conn);
   $ChangeCursor = ora_open($conn);
   $FunctionCursor = ora_open($conn);
   $Short_Term_Data = ora_open($conn);
//   ora_commitoff($conn);

  //get parmeters
  // Define some vars for the skeleton page
  $title = "ATS to ADP conversion";
  $area_type = "HRMS";
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $sDate = $HTTP_POST_VARS['start_date'];
//  $eDate = $HTTP_POST_VARS[end_date];
	$week_in_question_print = $sDate;
	$temp = split("/", $sDate);
	$week_in_question = date('d-M-Y', mktime(0,0,0,$temp[0],$temp[1],$temp[2]));

//	First up, we will do a catch-all calculation.
//	I will check to make sure every employee's timesheet is in "approved" status.
//	Any that are not, will be purged to the TIME_SUB_HISTORY table, and a default 40
//	Created and conditionally approved.
//	Note:  as this is run after the Monday-10AM cron autocreation script, I am guaranteed that
//	All active employees will have some from of timesheet in the system.
//	While it does share the "moving to TIME_SUB_HISTORY" aspect of Timesheet_autocreate.php in the
//	TS_Program/ATScron folder, this script does NOT calculate YTD_START or weekly accrual values (as they will
//	Already be present and not need changing).

	
	$sql = "SELECT EMPLOYEE_ID FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE'";
//	echo $sql;
	ora_parse($autoapproval_cursor_outer, $sql);
	ora_exec($autoapproval_cursor_outer);
	while(ora_fetch_into($autoapproval_cursor_outer, $Outer_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$Outer_Row['EMPLOYEE_ID']."' AND WEEK_START_MONDAY = '".$week_in_question."'			AND STATUS != 'APPROVED'"; 
//		echo $sql."<BR>";
		ora_parse($autoapproval_cursor_inner, $sql);
		ora_exec($autoapproval_cursor_inner);
		if(!ora_fetch_into($autoapproval_cursor_inner, $Inner_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// existing timesheet approved, or no timesheet (shouldn't happen) do nothing
//			echo "no good<BR>";
		} else {
			$sql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'HR:  Purged + autocreated for ADP' FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$Outer_Row['EMPLOYEE_ID']."' AND WEEK_START_MONDAY = '".$week_in_question."')";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);
//			echo $sql."<BR>";

			$sql = "UPDATE TIME_SUBMISSION SET
					SUBMISSION_PC_USERID = 'HR',
					SUBMISSION_PC = 'ADP',
					SUBMISSION_DATETIME = SYSDATE,
					SIGN_OFF_EMPID = 'HR',
					SIGN_OFF_PC_USERID = 'HR',
					SIGN_OFF_PC = 'ADP',
					SIGN_OFF_DATETIME = SYSDATE,
					MON_TOTAL = 8,
					MON_REG = 8,
					MON_HOLIDAY = 0,
					MON_VACATION = 0,
					MON_PERSONAL = 0,
					MON_SICK = 0,
					MON_OVERTIME = 0,
					TUE_TOTAL = 8,
					TUE_REG = 8,
					TUE_HOLIDAY = 0,
					TUE_VACATION = 0,
					TUE_PERSONAL = 0,
					TUE_SICK = 0,
					TUE_OVERTIME = 0,
					WED_TOTAL = 8,
					WED_REG = 8,
					WED_HOLIDAY = 0,
					WED_VACATION = 0,
					WED_PERSONAL = 0,
					WED_SICK = 0,
					WED_OVERTIME = 0,
					THU_TOTAL = 8,
					THU_REG = 8,
					THU_HOLIDAY = 0,
					THU_VACATION = 0,
					THU_PERSONAL = 0,
					THU_SICK = 0,
					THU_OVERTIME = 0,
					FRI_TOTAL = 8,
					FRI_REG = 8,
					FRI_HOLIDAY = 0,
					FRI_VACATION = 0,
					FRI_PERSONAL = 0,
					FRI_SICK = 0,
					FRI_OVERTIME = 0,
					SAT_TOTAL = 0,
					SAT_REG = 0,
					SAT_HOLIDAY = 0,
					SAT_VACATION = 0,
					SAT_PERSONAL = 0,
					SAT_SICK = 0,
					SAT_OVERTIME = 0,
					SUN_TOTAL = 0,
					SUN_REG = 0,
					SUN_HOLIDAY = 0,
					SUN_VACATION = 0,
					SUN_PERSONAL = 0,
					SUN_SICK = 0,
					SUN_OVERTIME = 0,
					WEEK_TOTAL_TOTAL = 40,
					WEEK_TOTAL_REG = 40,
					WEEK_TOTAL_HOLIDAY = 0,
					WEEK_TOTAL_VACATION = 0,
					WEEK_TOTAL_PERSONAL = 0,
					WEEK_TOTAL_SICK = 0,
					WEEK_TOTAL_OVERTIME = 0,
					STATUS = 'APPROVED',
					CONDITIONAL_SUBMISSION = 'Y'
					WHERE EMPLOYEE_ID = '".$Outer_Row['EMPLOYEE_ID']."' AND WEEK_START_MONDAY = '".$week_in_question."'";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);
//			echo $sql;

			Send_Mail($Outer_Row['EMPLOYEE_ID'], $FunctionCursor);
		}
	}
//	ora_commit($conn);







//	Starting here is the major calculation for YTD_END values
//  Note:  The actual logic that follows isn't too difficult.  However, I cannot swear to the runtimes
//  As the size of the table increases.  The more people have been screwing up their timesheets, the 
//  longer this script will take; consistency-wise, however, since the calculations get completed before
//  the CSV file is generated, HR cannot accidentally get the file and forget the calculations.

//  This function works by finding the first week (for each employee) where the YTD end values have not yet
//  Been calculated.  It then determines if said calculations should be done (I.E. is said week a 
//  Non-conditionally approved timesheet).  If not, it breaks the loop, if so, it does the necessary
//  Calculations, and then repeats the check on the same employee (knowing that the week just calculated
//  Will no longer be the minimal week with no calculations).

//  I can do this because, assuming this is the ONLY way for YTD_END values to get into the TIME_SUBMISSION
//  table, I always know that all weeks prior to the one being checked will be non-conditionally approved,
//  And further, that all weeks following the one being checked will NOT have their YTD_END values
//  Yet calculated... and therefore, by simply altering the YTD_START values for any week following
//  The one being checked, consistant calculations are being done throughout.

	$employee = array();
	$emp_vacationmax = array();
	$employee_row_counter = 0;

	// get a list of all EMPLOYEE_ID's, and store them in an ARRAY for futher use.
	// getting the ID's this way prevents a cursor overlap error later on.
	$sql = "SELECT EMPLOYEE_ID, VACATION_CARRY_OVER FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE' ORDER BY EMPLOYEE_ID";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	while(ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$employee[$employee_row_counter] = $Short_Term_Row['EMPLOYEE_ID'];
		$emp_vacationmax[$employee_row_counter] = $Short_Term_Row['VACATION_CARRY_OVER'];
		$employee_row_counter++;
	}


	for($i = 0; $i < sizeof($employee); $i++){
		// assign current employee
		$current_employee = $employee[$i];

		// varaiable to determine if conditional timesheet is hit, which prevents further calculations
		$found_conditional = false;
		$no_more_timesheets = false;

		// get employee's first uncalculated week in T_Sub
		$sql = "SELECT TO_CHAR(MIN(WEEK_START_MONDAY), 'MM/DD/YYYY') THE_WEEK FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$current_employee."' AND YTD_WEEK_END_VACATION_BAL IS NULL";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$week = $Short_Term_Row['THE_WEEK'];
		$temp = split("/", $week);
		$week = mktime(0,0,0,$temp[0],$temp[1],$temp[2]);

		while(!$found_conditional && !$no_more_timesheets){ 
			// once we find a conditional timesheet, or hit the last record in the table, NO FURTHER calculations are done for this employee
			$ora_week = date('d-M-Y', $week);

			$sql = "SELECT TS.*, TO_CHAR(TS.FRI_DATE, 'MM/DD/YYYY') THE_WEEK FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$current_employee."' AND WEEK_START_MONDAY = '".$ora_week."'";
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
					$new_year_week = split("/", $row['THE_WEEK']);
					// time for calculations.

					// step 1:  get the raw values.
					// if this is a year-lapse, most of these revert to 0.
					if($new_year_week[0] == 1 && $new_year_week[1] >= 1 && $new_year_week[1] <= 7){
						// new year
						$TOTAL_TOTAL = $row['WEEK_TOTAL_TOTAL'];
						$TOTAL_REG = $row['WEEK_TOTAL_REG'];
						$TOTAL_HOLIDAY = $row['WEEK_TOTAL_HOLIDAY'];
						$TOTAL_VACATION = $row['WEEK_TOTAL_VACATION'];
						$TOTAL_PERSONAL = $row['WEEK_TOTAL_PERSONAL'];
						$TOTAL_SICK = $row['WEEK_TOTAL_SICK'];
						$TOTAL_OVERTIME = $row['WEEK_TOTAL_OVERTIME'];
						if(($row['YTD_WEEK_START_VACATION_BAL'] - $row['WEEK_TOTAL_VACATION']) > $emp_vacationmax[$i]){
							$VAC_BAL = $emp_vacationmax[$i] + $row['WEEKLY_ACCRUAL_VACATION'];
						} else {
							$VAC_BAL = $row['YTD_WEEK_START_VACATION_BAL'] + $row['WEEKLY_ACCRUAL_VACATION'] - $row['WEEK_TOTAL_VACATION'];
						}
						$SICK_BAL = $row['YTD_WEEK_START_SICK_BAL'] + $row['WEEKLY_ACCRUAL_SICK'] - $row['WEEK_TOTAL_SICK'];
						$PERS_BAL = 48;
					} else {
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
					}

					// Note to future programmers:  This code will NOT correctly calculate AVG OT for
					// the first week of a year.  Due to, once again, a massive time contraint however,
					// and the fact that the VB app itself determines what displays as average OT,
					// I am leaving it this way just so that AVG_OT in the database has a value, and
					// continuing on.
					$sql = "SELECT COUNT(*) THE_COUNT FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$current_employee."' AND
							WEEK_START_MONDAY <= '".$ora_week."' AND FRI_DATE > '01-JAN-".substr($ora_week, -4)."'";
					ora_parse($Short_Term_Data, $sql);
					ora_exec($Short_Term_Data);
					ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$weeks_for_overtime = $Short_Term_Row['THE_COUNT'];
					if($weeks_for_overtime != 0){
						$avg_OT = $TOTAL_OVERTIME / $weeks_for_overtime;
					} else {
						$avg_OT = $TOTAL_OVERTIME;
					}

					// step 2:  Update AT_EMPLOYEE table.
					// step 2a:  If this is first week of year, relocate AT_EMPLOYEE value before modifying.
					// REMOVED, a change to AT_EMPLOYEE is breaking this statement, and we need it run NOW
/*
					if($new_year_week[0] == 1 && $new_year_week[1] >= 1 && $new_year_week[1] <= 7){
						$sql = "INSERT INTO AT_EMPLOYEE_HISTORY (SELECT ATE.*, '".$user."', SYSDATE, 'End of year record' FROM AT_EMPLOYEE ATE WHERE EMPLOYEE_ID = '".$current_employee."')";
						ora_parse($ChangeCursor, $sql);
						ora_exec($ChangeCursor);
					}
*/
					// step 2b:  Update the AT_EMPLOYEE record.
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

		
					// step 4:  if this is the first week of the year, update TIME_SUBMISSION accordingly.
					if($new_year_week[0] == 1 && $new_year_week[1] >= 1 && $new_year_week[1] <= 7){
					// step 4a: Move existing record (if exists) to TIME_SUB_HISTORY
						$sql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'Year end record' FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$current_employee."' AND WEEK_START_MONDAY = '".$ora_week."')";
						ora_parse($ChangeCursor, $sql);
						ora_exec($ChangeCursor);

					// step 4b:  Update week.  SICK_BAL is NOT changed, since it has no carryover property (as of 12/21/2007)
					// if vacation maximum passed, make entry in YTD_ACCRUAL_CHANGES
						if($VAC_BAL != $row['YTD_WEEK_START_VACATION_BAL'] + $row['WEEKLY_ACCRUAL_VACATION'] - $row['WEEK_TOTAL_VACATION']){
							$temp = ($emp_vacationmax[$i] + $row['WEEKLY_ACCRUAL_VACATION'])- $row['YTD_WEEK_START_VACATION_BAL'];
							$sql = "INSERT INTO YTD_ACCRUAL_CHANGES (EMPLOYEE_ID, EXECUTION_DATE, EFFECTIVE_WEEK, UPDATE_COMMENTS, VACATION_CHANGE, PERSONAL_CHANGE, SICK_CHANGE) VALUES ('".$current_employee."', SYSDATE, '".$ora_week."', 'Year End CarryOver cap passed', ".$temp.", 0, 0)";
//							echo $sql;
							ora_parse($ChangeCursor, $sql);
							ora_exec($ChangeCursor);
						} else {
//							$temp = $row['YTD_WEEK_START_VACATION_BAL'];
						}
/* NOT USED

						$sql = "UPDATE TIME_SUBMISSION SET
								YTD_WEEK_START_TOTAL_TOTAL = '0',
								YTD_WEEK_START_TOTAL_REG = '0',
								YTD_WEEK_START_TOTAL_HOLIDAY = '0',
								YTD_WEEK_START_TOTAL_VACATION = '0',
								YTD_WEEK_START_TOTAL_PERSONAL = '0',
								YTD_WEEK_START_TOTAL_SICK = '0',
								YTD_WEEK_START_TOTAL_OVERTIME = '0',
								YTD_WEEK_START_AVERAGE_OT = '0',
								YTD_WEEK_START_VACATION_BAL = '".$temp."',
								YTD_WEEK_START_PERSONAL_BAL = '48'
								WHERE EMPLOYEE_ID = '".$current_employee."'
								AND WEEK_START_MONDAY = '".$ora_week."'";
						ora_parse($ChangeCursor, $sql);
						ora_exec($ChangeCursor);
*/
					}



					// step 5:  adjust the YTD_END values for the current week.
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




//					echo $current_employee."    ".$ora_week."<BR>";
				}
			}
			// update complete for this employee for this week.  Increment week, and loop to next.
			$week += 604800;
		}
		// this employee is complete.  loop to next.
	}
//	ora_commit($conn);














  // and now, onto the generation of the ADP file.



  list($m, $d, $y) = split("/", $sDate);
  $batch_id = $m.$d.substr($y,2,2)."A";

  $header = "Co Code,Batch ID,File #,Reg Hours,O/T Hours,Hours 3 Code,Hours 3 Amount\r\n";


   $path = "/web/web_pages/upload/ADP_Files/";
   $fName = "EPI5ZEAS.CSV";

   $fp = fopen($path.$fName, 'w');
   fwrite($fp, $header);

	$sql = "SELECT ATE.DEPARTMENT_CODE THE_DEPT, TS.EMPLOYEE_ID THE_EMP, WEEK_TOTAL_REG, WEEK_TOTAL_OVERTIME, WEEK_TOTAL_HOLIDAY, WEEK_TOTAL_VACATION, WEEK_TOTAL_PERSONAL, WEEK_TOTAL_SICK 
			FROM AT_EMPLOYEE ATE, TIME_SUBMISSION TS 
			WHERE ATE.EMPLOYEE_ID = TS.EMPLOYEE_ID 
				AND TS.WEEK_START_MONDAY = TO_DATE('".$sDate."', 'MM/DD/YYYY') 
				AND TS.STATUS = 'APPROVED' 
			ORDER BY TS.EMPLOYEE_ID";
	$statement = ora_parse($cursor, $sql);
    ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No timesheets have been approved for the week of $sDate.  Please use your browser's back button to return to the previous screen.";
		exit;
	} else {
		do {
//			$job_cost = $row['THE_DEPT']."-0000-00";
			$job_cost = "0000-0000-00";
			if($row['THE_EMP'] != "E408655"){
				$file_num = substr($row['THE_EMP'], -4);
			} else {
				$file_num = substr($row['THE_EMP'], -6);
			}

			fwrite($fp, "5ZE,".$batch_id.",".$file_num.",".$row['WEEK_TOTAL_REG'].",".$row['WEEK_TOTAL_OVERTIME'].",,\r\n");
//			fwrite($fp, "5ZE,".$batch_id.",".$file_num.",".$job_cost.",".$row['WEEK_TOTAL_REG'].",".$row['WEEK_TOTAL_OVERTIME'].",,\r\n");

			if($row['WEEK_TOTAL_HOLIDAY'] != 0 && $row['WEEK_TOTAL_HOLIDAY'] != ""){
				fwrite($fp, "5ZE,".$batch_id.",".$file_num.",,,D,".$row['WEEK_TOTAL_HOLIDAY']."\r\n");
			}

			if($row['WEEK_TOTAL_VACATION'] != 0 && $row['WEEK_TOTAL_VACATION'] != ""){
				fwrite($fp, "5ZE,".$batch_id.",".$file_num.",,,V,".$row['WEEK_TOTAL_VACATION']."\r\n");
			}

			if($row['WEEK_TOTAL_PERSONAL'] != 0 && $row['WEEK_TOTAL_PERSONAL'] != ""){
				fwrite($fp, "5ZE,".$batch_id.",".$file_num.",,,P,".$row['WEEK_TOTAL_PERSONAL']."\r\n");
			}

			if($row['WEEK_TOTAL_SICK'] != 0 && $row['WEEK_TOTAL_SICK'] != ""){
				fwrite($fp, "5ZE,".$batch_id.",".$file_num.",,,S,".$row['WEEK_TOTAL_SICK']."\r\n");
			}
		
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
	
	fclose($fp);

	$sql = "UPDATE TIME_SUBMISSION SET PAYROLL_USERID = '".$user."', PAYROLL_DATETIME = SYSDATE WHERE WEEK_START_MONDAY = TO_DATE('".$sDate."', 'MM/DD/YYYY') AND STATUS = 'APPROVED'";
	$statement = ora_parse($cursor, $sql);
    ora_exec($cursor);

  $filename = $path.$fName;
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Disposition: attachment; filename=$fName");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".filesize($filename));

  readfile("$filename"); 
  exit();









function Send_Mail($employee, $FunctionCursor){

   global $week_in_question, $week_in_question_print, $user_email; 

	// sends mail to anyone who'se missed / butchered a timesheet.
	// I get the 2 addresses in 2 steps to save myself a do-while loop.
	$mailTo = "";
	$mailSubject = "";
	$mailHeaders = "From:  TimeSheet(NoReplies)\r\n";
	$body = "";
	
	$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."'";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

//	echo $sql."<BR>";
	$mailTo = $FunctionRow['EMAIL_ADDRESS'];

	$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."')";
	ora_parse($FunctionCursor, $sql);
	ora_exec($FunctionCursor);
	if(ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	//	$mailHeaders .= "CC:  ".$FunctionRow['EMAIL_ADDRESS'].",skennard@port.state.de.us,".$user_email."\r\n";
		$mailHeaders .= "CC:  ".$FunctionRow['EMAIL_ADDRESS'].",".$user_email."\r\n";
	} else {
		// should never happen
		$mailHeaders .= "CC:  hdadmin@port.state.de.us,".$user_email."\r\n";
	}
//	$mailTo .= ",skennard@port.state.de.us,".$user_email;

//	$mailHeaders .= "CC:  ".$FunctionRow['EMAIL_ADDRESS']."\r\n";
	$mailSubject = "Timesheet NOT Approved";

	$body = "There was no approved timesheet for you for the week of ".$week_in_question_print.".  Therefore, a standard 40-hour sheet was created for you and conditioanlly approved automatically.\r\n\r\nThe system will not permit you to enter any new timesheets until you review and re-submit all conditional timesheets.   You will be prompted the next time you start the timesheet program.";

//	mail($mailTo, $mailSubject, $body, $mailHeaders);
//	echo $mailTo."<BR>".$mailSubject."<BR>".$body."<BR>".$mailHeaders."<BR><BR>";
}
?>