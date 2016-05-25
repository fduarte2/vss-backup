<?
/* Adam Walter, October 2007.
*  
*  This script is part of the new Administrative Building Timesheet Process.
*
*  This script is used by HR to modify employee week data in the TIME_SUBMISSION table...
*  Well, sort of.
*
*  This code enters data into a table (HR_TIMESHEET_MODIFICATIONS) about very specific
*	Changes to an employee timesheet.  It needs to be noted, however, that said changes
*	Will NOT be made to TIME_SUBMISSION for the week in question; only to all timesheets
*	At and beyond the first one that has not had it's YTD_END calculations made yet.
*
*	After which, it updates AT_EMPLOYEE with the new values.
**************************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "ATS Employee Management";
  $area_type = "HRMS";
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];

  // Provides header / leftnav
  include("pow_header.php");

  if($access_denied || (array_search('HRDIR', $user_types)) === FALSE){
    printf("Access Denied from Employee Editing System");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }
  $cursor = ora_open($conn);
  $ChangeCursor = ora_open($conn);
  $Short_Term_Data = ora_open($conn);
  $FunctionCursor = ora_open($conn);
  

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Edit Employee"){
		$bad_message = "";

		// assign all the variables.
		$first_name = str_replace("\'", "`", $HTTP_POST_VARS['first_name']);
		$last_name = str_replace("\'", "`", $HTTP_POST_VARS['last_name']);
		$supervisor_id = $HTTP_POST_VARS['supervisor_id'];
		$execdir_app = $HTTP_POST_VARS['execdir_app'];
		$department_id = str_replace("'", "`", $HTTP_POST_VARS['department_id']);
		$department_code = $HTTP_POST_VARS['department_code'];
		$email_address = $HTTP_POST_VARS['email_address'];
		$employment_status = $HTTP_POST_VARS['employment_status'];
		$pay_type = $HTTP_POST_VARS['pay_type'];
		$supervisory_status = $HTTP_POST_VARS['supervisory_status'];
		$intranet_login_id = $HTTP_POST_VARS['intranet_login_id'];
		$windows_login_id = $HTTP_POST_VARS['windows_login_id'];
		$first_week_paid_date = $HTTP_POST_VARS['first_week_paid_date'];
		$vacation_carry_over = $HTTP_POST_VARS['vacation_carry_over'];
		$sick_carry_over = $HTTP_POST_VARS['sick_carry_over'];
		$personal_yearly_rate = $HTTP_POST_VARS['personal_yearly_rate'];
		$vacation_weekly_rate = $HTTP_POST_VARS['vacation_weekly_rate'];
		$vacation_YTD_remain = $HTTP_POST_VARS['vacation_YTD_remain'];
		$sick_weekly_rate = $HTTP_POST_VARS['sick_weekly_rate'];
		$sick_YTD_remain = $HTTP_POST_VARS['sick_YTD_remain'];
		$personal_YTD_remain = $HTTP_POST_VARS['personal_YTD_remain'];
		$anniv_date = $HTTP_POST_VARS['anniv_date'];
		$comments = str_replace("'", "`", $HTTP_POST_VARS['comments']);
		$effect_date = $HTTP_POST_VARS['effect_date'];

/*		echo $first_name."<BR>";
		echo $last_name."<BR>";
		echo $supervisor_id."<BR>";
		echo $department_id."<BR>";
		echo $department_code."<BR>";
		echo $email_address."<BR>";
		echo $employment_status."<BR>";
		echo $pay_type."<BR>";
		echo $supervisory_status."<BR>";
		echo $intranet_login_id."<BR>";
		echo $windows_login_id."<BR>";
		echo $first_week_paid_date."<BR>";
		echo $vacation_carry_over."<BR>";
		echo $sick_carry_over."<BR>";
		echo $personal_yearly_rate."<BR>";
		echo $vacation_weekly_rate."<BR>";
		echo $vacation_YTD_remain."<BR>";
		echo $sick_weekly_rate."<BR>";
		echo $sick_YTD_remain."<BR>";
		echo $personal_YTD_remain."<BR>"; */


		// next, we determine if the entered values are good.  Long process incoming, lots of if's.

		if($employment_status == "INACTIVE" || $supervisory_status == "EMPLOYEE"){
			$sql = "SELECT * FROM AT_EMPLOYEE WHERE SUPERVISOR_ID = '".$emp_ID."' AND EMPLOYMENT_STATUS = 'ACTIVE'";
			ora_parse($Short_Term_Data, $sql);
			ora_exec($Short_Term_Data);
			if(ora_fetch_into($Short_Term_Data, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$bad_message = "All active employees must first be assigned a new Supervisor before this employee can be removed from the Supervisory list.";
			}
		}

		if(!is_numeric($department_code)){
			$bad_message = "Department code must be a number";
		}

		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $first_week_paid_date)){
			$bad_message = "First week paid date must be in MM/DD/YYYY format";
		}

		$temp = split("/", $first_week_paid_date);
		if(date("l", mktime(0,0,0, $temp[0], $temp[1], $temp[2])) != "Monday"){
			$bad_message = "First week paid date must be a Monday";
		}

		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $effect_date)){
			$bad_message = "Effective date must be in MM/DD/YYYY format";
		}

		$temp = split("/", $effect_date);
		if(date("l", mktime(0,0,0, $temp[0], $temp[1], $temp[2])) != "Monday"){
			$bad_message = "Effective Date must be a Monday";
		}

		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $anniv_date)){
			$bad_message = "Hire date must be in MM/DD/YYYY format";
		}

		if(!is_numeric($vacation_carry_over)){
			$bad_message = "Vacation Carry Over must be a number";
		}

		if(!is_numeric($sick_carry_over)){
			$bad_message = "Sick Carry Over must be a number";
		}

		if(!is_numeric($personal_yearly_rate)){
			$bad_message = "Personal Yearly Rate must be a number";
		}

		if(!is_numeric($vacation_weekly_rate)){
			$bad_message = "Vacation Weekly Accrual Rate must be a number";
		}

		if(!is_numeric($vacation_YTD_remain)){
			$bad_message = "Vacation YTD Remaining must be a number";
		}

		if(!is_numeric($sick_weekly_rate)){
			$bad_message = "Sick Weekly Accrual Rate must be a number";
		}

		if(!is_numeric($sick_YTD_remain)){
			$bad_message = "Sick YTD Remaining must be a number";
		}

		if(!is_numeric($personal_YTD_remain)){
			$bad_message = "Personal YTD Remaining Rate must be a number";
		}

		if($comments == ""){
			$bad_message = "The Reason for Change field cannot be blank";
		} elseif(!ereg("^[0-9a-zA-Z\. ]+$", $comments)){
			$bad_message = "The Reason for Change field can only contain letters, numbers, spaces, and decimal points.";
		}
		// ok, passed the validation checks.  Time for the SQL.

		if($bad_message == ""){

			$sql = "INSERT INTO AT_EMPLOYEE_HISTORY (SELECT '".$user."', SYSDATE, '".$comments."', ATE.* FROM AT_EMPLOYEE ATE WHERE EMPLOYEE_ID = '".$emp_ID."')";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);


			$sql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."'";
			ora_parse($Short_Term_Data, $sql);
			ora_exec($Short_Term_Data);
			ora_fetch_into($Short_Term_Data, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$sick_change = $sick_YTD_remain - $Short_Term_row['SICK_YTD_REMAIN'];
			$vacation_change = $vacation_YTD_remain - $Short_Term_row['VACATION_YTD_REMAIN'];
			$personal_change = $personal_YTD_remain - $Short_Term_row['PERSONAL_YTD_REMAIN'];

			if($sick_change != 0 || $vacation_change != 0 || $personal_change != 0){
				$sql = "INSERT INTO YTD_ACCRUAL_CHANGES (EMPLOYEE_ID, EXECUTION_DATE, EFFECTIVE_WEEK, UPDATE_COMMENTS, VACATION_CHANGE, PERSONAL_CHANGE, SICK_CHANGE) VALUES ('".$emp_ID."', SYSDATE, to_date('".$effect_date."', 'MM/DD/YYYY'), '".$comments."', ".$vacation_change.", ".$personal_change.", ".$sick_change.")";
				ora_parse($ChangeCursor, $sql);
				ora_exec($ChangeCursor);
			}


			$sql = "UPDATE AT_EMPLOYEE SET
					FIRST_NAME = '".$first_name."',
					LAST_NAME = '".$last_name."',
					SUPERVISOR_ID = '".$supervisor_id."',
					EXECDIR_APP = '".$execdir_app."',
					DEPARTMENT_ID = '".$department_id."',
					DEPARTMENT_CODE = '".$department_code."',
					EMAIL_ADDRESS = '".$email_address."',
					EMPLOYMENT_STATUS = '".$employment_status."',
					PAY_TYPE = '".$pay_type."',
					SUPERVISORY_STATUS = '".$supervisory_status."',
					INTRANET_LOGIN_ID = '".$intranet_login_id."',
					WINDOWS_LOGIN_ID = '".$windows_login_id."',
					FIRST_WEEK_PAID_DATE = to_date('".$first_week_paid_date."', 'MM/DD/YYYY'),
					ANNIVERSARY_DATE = to_date('".$anniv_date."', 'MM/DD/YYYY'),
					VACATION_CARRY_OVER = '".$vacation_carry_over."',
					VACATION_WEEKLY_RATE = '".$vacation_weekly_rate."',
					VACATION_YTD_REMAIN = '".$vacation_YTD_remain."',
					SICK_CARRY_OVER = '".$sick_carry_over."',
					SICK_WEEKLY_RATE = '".$sick_weekly_rate."',
					SICK_YTD_REMAIN = '".$sick_YTD_remain."',
					PERSONAL_YEARLY_RATE = '".$personal_yearly_rate."',
					PERSONAL_YTD_REMAIN = '".$personal_YTD_remain."'
					WHERE EMPLOYEE_ID = '".$emp_ID."'";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);


			$sql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'AT-EMPLOYEE values edited by ".$user."' FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$emp_ID."' AND YTD_WEEK_END_VACATION_BAL IS NULL)";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);


			$sql = "UPDATE TIME_SUBMISSION SET
					WEEKLY_ACCRUAL_SICK = '".$sick_weekly_rate."',
					WEEKLY_ACCRUAL_VACATION = '".$vacation_weekly_rate."',
					YTD_WEEK_START_VACATION_BAL = '".$vacation_YTD_remain."',
					YTD_WEEK_START_PERSONAL_BAL = '".$personal_YTD_remain."',
					YTD_WEEK_START_SICK_BAL = '".$sick_YTD_remain."'
					WHERE EMPLOYEE_ID = '".$emp_ID."' AND YTD_WEEK_END_VACATION_BAL IS NULL";
			ora_parse($ChangeCursor, $sql);
			ora_exec($ChangeCursor);





		// SQL's done, send appropriate email:
		$mailTo = "";
		$mailSubject = "";
		$mailHeaders = "";
		$body = "";
		
		$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."'";
		ora_parse($FunctionCursor, $sql);
		ora_exec($FunctionCursor);
		ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailTo = $FunctionRow['EMAIL_ADDRESS'];
//		$mailTo = "awalter@port.state.de.us";

		$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."')";
		ora_parse($FunctionCursor, $sql);
		ora_exec($FunctionCursor);
		ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailHeaders = "From:  TimeSheet(NoReplies)\r\n";
		$mailHeaders .= "CC:  ".$FunctionRow['EMAIL_ADDRESS'].",skennard@port.state.de.us,".$user_email."\r\n";
//		$mailHeaders .= "Bcc:  awalter@port.state.de.us,ithomas@port.state.de.us\r\n"; -- 2/23/2012
		$mailHeaders .= "Bcc:  hdadmin@port.state.de.us\r\n";

		$mailSubject = "Timesheet Information Edited";

		$body = "HR has just enacted a change on your information in the Atuomatic Timesheet System for the following reason:\r\n\r\n".$comments."\r\n\r\n\r\nYour Updated Per-Hour Information, current as of your latest Approved Timesheet, is:\r\n";

		$sql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."'";
		ora_parse($FunctionCursor, $sql);
		ora_exec($FunctionCursor);
		ora_fetch_into($FunctionCursor, $FunctionRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$body .= "Name:  ".$FunctionRow['FIRST_NAME']." ".$FunctionRow['LAST_NAME']."\r\n";
		$body .= "Vacation Yearly Carry-Over Maximum:  ".$FunctionRow['VACATION_CARRY_OVER']."\r\n";
//		$body .= "Sick Yearly Carry-Over Maximum:  ".$FunctionRow['SICK_CARRY_OVER']."\r\n";
		$body .= "Personal Yearly Allocation:  ".$FunctionRow['PERSONAL_YEARLY_RATE']."\r\n";
		$body .= "Weekly Vacation Accrual:  ".$FunctionRow['VACATION_WEEKLY_RATE']."\r\n";
		$body .= "YTD Vacation Remaining:  ".$FunctionRow['VACATION_YTD_REMAIN']."\r\n";
		$body .= "Weekly Sick Accrual:  ".$FunctionRow['SICK_WEEKLY_RATE']."\r\n";
		$body .= "YTD Sick Remaining:  ".$FunctionRow['SICK_YTD_REMAIN']."\r\n";
		$body .= "YTD Personal Remaining:  ".$FunctionRow['PERSONAL_YTD_REMAIN']."\r\n";

		mail($mailTo, $mailSubject, $body, $mailHeaders);

		}
	}
	



	$emp_ID = $HTTP_POST_VARS['emp_ID'];

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">ATS Employee Management
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="employee_select" action="AT_EMP_edit.php" method="post">
	<tr>
		<td align="center"><font size="3" face="Verdana">Employee ID#:  
				<select name="emp_ID" onchange="document.employee_select.submit(this.form)"><option value="">Select Employee</option>
<?
	$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE ORDER BY EMPLOYEE_ID";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	while(ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['EMPLOYEE_ID']; ?>"><? echo $row['EMPLOYEE_ID']." - ".$row['FIRST_NAME']." ".$row['LAST_NAME']; ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td align="center">&nbsp;<BR><HR></td>
	</tr>
</form>
</table>
<?
	if($emp_ID != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="employee_edit" action="AT_EMP_edit.php" method="post">
<input type="hidden" name="emp_ID" value="<? echo $emp_ID; ?>">
<?
		$sql = "SELECT TO_CHAR(FIRST_WEEK_PAID_DATE, 'MM/DD/YYYY') THE_DATE FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."'";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$first_week = $row['THE_DATE'];

		$sql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($bad_message != ""){
?>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana" color="#FF0000">Your edit could not be processed for the following reason:<BR><? echo $bad_message; ?><BR>No changes saved.</font></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="3" align="center"><font size="4" face="Verdana" color="#0000A0">Employee ID:  <? echo $emp_ID; ?></font><BR><BR></td>
	</tr>
	<tr>
		<td width="15%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana"><b>Current:</b></font></td>
		<td><font size="3" face="Verdana"><b>New:</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">First Name:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['FIRST_NAME']; ?></font></td>
		<td><input type="text" name="first_name" size="12" maxlength="12" value="<? echo $row['FIRST_NAME']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Last Name:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['LAST_NAME']; ?></font></td>
		<td><input type="text" name="last_name" size="20" maxlength="20" value="<? echo $row['LAST_NAME']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Supervisor:</font></td>
<?
		$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$emp_ID."')";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<td width="25%"><font size="2" face="Verdana"><? echo $Short_Term_row['EMPLOYEE_ID']." - ".$Short_Term_row['FIRST_NAME']." ".$Short_Term_row['LAST_NAME']; ?></font></td>
		<td><select name="supervisor_id"><option value="N/A">N/A (Port Director)</option>
<?
		$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE SUPERVISORY_STATUS = 'SUPERVISOR' ORDER BY EMPLOYEE_ID";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		while(ora_fetch_into($Short_Term_Data, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['EMPLOYEE_ID'];?>" <? if($Short_Term_row['EMPLOYEE_ID'] == $row['SUPERVISOR_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['EMPLOYEE_ID']." - ".$Short_Term_row['FIRST_NAME']." ".$Short_Term_row['LAST_NAME']; ?></option>
<?
		}
?>
			</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Approve Executive?:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['EXECDIR_APP']; ?></font></td>
		<td><select name="execdir_app">
				<option value="N">NO</option>
				<option value="Y"<? if($row['EXECDIR_APP'] == "Y"){ ?> selected <? } ?>>YES</option>
			</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Department ID:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['DEPARTMENT_ID']; ?></font></td>
		<td><input type="text" name="department_id" size="10" maxlength="12" value="<? echo $row['DEPARTMENT_ID']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Department Code:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['DEPARTMENT_CODE']; ?></font></td>
		<td><input type="text" name="department_code" size="5" maxlength="4" value="<? echo $row['DEPARTMENT_CODE']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Email:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['EMAIL_ADDRESS']; ?></font></td>
		<td><input type="text" name="email_address" size="30" maxlength="255" value="<? echo $row['EMAIL_ADDRESS']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Employment Status:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['EMPLOYMENT_STATUS']; ?></font></td>
		<td><select name="employment_status">
				<option value="ACTIVE">ACTIVE</option>
				<option value="INACTIVE"<? if($row['EMPLOYMENT_STATUS'] == "INACTIVE"){ ?> selected <? } ?>>INACTIVE</option>
				<option value="SUPV"<? if($row['EMPLOYMENT_STATUS'] == "SUPV"){ ?> selected <? } ?>>LCS - SUPV</option>
			</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Pay Type:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['PAY_TYPE']; ?></font></td>
		<td><select name="pay_type">
				<option value="SALARIED">SALARIED</option>
				<option value="OVERTIME"<? if($row['PAY_TYPE'] == "OVERTIME"){ ?> selected <? } ?>>OVERTIME</option>
			</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Supervisory Status:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['SUPERVISORY_STATUS']; ?></font></td>
		<td><select name="supervisory_status">
				<option value="EMPLOYEE">EMPLOYEE</option>
				<option value="SUPERVISOR"<? if($row['SUPERVISORY_STATUS'] == "SUPERVISOR"){ ?> selected <? } ?>>SUPERVISOR</option>
			</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Intranet Login:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['INTRANET_LOGIN_ID']; ?></font></td>
		<td><input type="text" name="intranet_login_id" size="10" maxlength="12" value="<? echo $row['INTRANET_LOGIN_ID']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Windows Login:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['WINDOWS_LOGIN_ID']; ?></font></td>
		<td><input type="text" name="windows_login_id" size="10" maxlength="12" value="<? echo $row['WINDOWS_LOGIN_ID']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">First Paid Week:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $first_week; ?></font></td>
		<td><input type="text" name="first_week_paid_date" size="10" maxlength="10" value="<? echo $first_week; ?>"><font size="2" face="Verdana">  (MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Hire Date:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $anniv_date; ?></font></td>
		<td><input type="text" name="anniv_date" size="10" maxlength="10" value="<? echo $anniv_date; ?>"><font size="2" face="Verdana">  (MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><BR><font size="3" face="Verdana" color="#0000A0">These 3 fields will take effect<BR>on the first week of <? echo date('Y') + 1; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vacation Maximum Carry-over:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['VACATION_CARRY_OVER']; ?> Hours</font></td>
		<td><input type="text" name="vacation_carry_over" size="10" maxlength="8" value="<? echo 0 + $row['VACATION_CARRY_OVER']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Sick Maximum Carry-over:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['SICK_CARRY_OVER']; ?> Hours</font></td>
		<td><input type="text" name="sick_carry_over" size="10" maxlength="8" value="<? echo 0 + $row['SICK_CARRY_OVER']; ?>">&nbsp;<font size="2" face="Verdana">(Not currently used; leave as shown)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Personal Yearly Rate:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['PERSONAL_YEARLY_RATE']; ?> Hours</font></td>
		<td><input type="text" name="personal_yearly_rate" size="10" maxlength="8" value="<? echo 0 + $row['PERSONAL_YEARLY_RATE']; ?>"></td>
	</tr>
	<tr>
<?
		$sql = "SELECT TO_CHAR(MIN(WEEK_START_MONDAY), 'MM/DD/YYYY') THE_WEEK FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$emp_ID."' AND YTD_WEEK_END_VACATION_BAL IS NULL";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<td colspan="3" align="center"><BR><font size="3" face="Verdana" color="#0000A0">These 5 fields will take effect on</font><BR>
		<font size="4" face="Verdana" color="#0000C0"><? echo $Short_Term_row['THE_WEEK']; ?></font><BR>
		<font size="2" face="Verdana" color="#0000C0">(The week following their most recent submitted, approved, payrolled timesheet)</font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vacation Weekly Rate:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['VACATION_WEEKLY_RATE']; ?> Hours</font></td>
		<td><input type="text" name="vacation_weekly_rate" size="10" maxlength="8" value="<? echo $row['VACATION_WEEKLY_RATE']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Vacation YTD Remaining:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['VACATION_YTD_REMAIN']; ?> Hours</font></td>
		<td><input type="text" name="vacation_YTD_remain" size="10" maxlength="8" value="<? echo $row['VACATION_YTD_REMAIN']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Sick Weekly Rate:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['SICK_WEEKLY_RATE']; ?> Hours</font></td>
		<td><input type="text" name="sick_weekly_rate" size="10" maxlength="8" value="<? echo $row['SICK_WEEKLY_RATE']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Sick YTD Remaining:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['SICK_YTD_REMAIN']; ?> Hours</font></td>
		<td><input type="text" name="sick_YTD_remain" size="10" maxlength="8" value="<? echo $row['SICK_YTD_REMAIN']; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Personal YTD Remaining:</font></td>
		<td width="25%"><font size="2" face="Verdana"><? echo $row['PERSONAL_YTD_REMAIN']; ?> Hours</font></td>
		<td><input type="text" name="personal_YTD_remain" size="10" maxlength="8" value="<? echo $row['PERSONAL_YTD_REMAIN']; ?>"></td>
	</tr>
	<tr>
		<td colspan="3" align="center"?>&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana" color="#0000A0">Other Details</font><BR><font size="2" face="Verdana" color="#0000A0">(These will be recorded in the timesheets,<BR> and districuted in the email to involved parties)<BR></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Date of Effect:</font></td>
		<td width="25%"><font size="2" face="Verdana">Enter the Monday of the week affected by the change; or, if the change is for today onward, enter this week's Monday.)</font></td>
		<td><input type="text" name="effect_date" size="10" maxlength="10"><font size="2" face="Verdana">  (MM/DD/YYYY format)</font></td>
	</tr>

	<tr>
		<td width="15%"><font size="2" face="Verdana">Reason for Change:</font></td>
		<td width="25%"><font size="2" face="Verdana">Specific reason for change; will be recorded AND emailed.</font></td>
		<td><textarea name="comments" cols="50" rows="5"></textarea></td>
	</tr>
	<tr>
		<td colspan="3" align="center"?><input name="submit" type="submit" value="Edit Employee"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");
?>