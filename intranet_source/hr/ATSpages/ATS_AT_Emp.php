<?
/* Adam Walter, June 2007.
*  
*  This script is part of the new Administrative Building Timesheet Process.
*
*  This script is used by HR to enter initial employee data in the AT_EMPLOYEE table.
*
*  Not the most elegant code I've ever written (BNIReports still holds that title),
*  But as I have to rush through everything these days, and I can keep it straight
*  In my head, it will suffice.
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
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }
  $cursor = ora_open($conn);
  $function_cursor = ora_open($conn);
  $Short_Term_Data = ora_open($conn);

  $submit = $HTTP_POST_VARS['submit'];
  $emp_ID = $HTTP_POST_VARS['emp_ID'];
  $top_message = $HTTP_POST_VARS['top_message'];



	// initial error check for validity
	// function error_test_empmodify also purges single quotes from fields expecting to possibly contain them, like names like O'Malley,
	// and replaces them with ` marks (the character just under the tilde)
	if($submit != ""){ // a button has been pressed
		if($submit == "Select Employee"){
			$bad_message = error_test_empselect($emp_ID);
		} else { // modification or addition button pressed
			$first_name = $HTTP_POST_VARS['first_name'];
			$last_name = $HTTP_POST_VARS['last_name'];
			$super_id = $HTTP_POST_VARS['super_id'];
			$department_id = $HTTP_POST_VARS['department_id'];
			$email_addy = $HTTP_POST_VARS['email_addy'];
			$emp_status = $HTTP_POST_VARS['emp_status'];
			$pay_type = $HTTP_POST_VARS['pay_type'];
			$super_status = $HTTP_POST_VARS['super_status'];
			$intra_login = $HTTP_POST_VARS['intra_login'];
			$window_login = $HTTP_POST_VARS['window_login'];
			$vac_rate = $HTTP_POST_VARS['vac_rate'];
			$vac_remain = $HTTP_POST_VARS['vac_remain'];
			$sick_rate = $HTTP_POST_VARS['sick_rate'];
			$sick_remain = $HTTP_POST_VARS['sick_remain'];
			$pers_remain = $HTTP_POST_VARS['pers_remain'];
			$department_code = $HTTP_POST_VARS['department_code'];
			$first_week = $HTTP_POST_VARS['first_week'];
			$emp_ID = $HTTP_POST_VARS['emp_ID'];
			$anniv_date = $HTTP_POST_VARS['anniv_date'];

			$bad_message = error_test_empmodify($first_name, $last_name, $super_id, $department_id, $email_addy, $emp_status, $pay_type, $super_status, $intra_login, $window_login, $vac_rate, $vac_remain, $sick_rate, $sick_remain, $pers_remain, $department_code, $first_week, $anniv_date);
		}
	}

	// double check to make sure there aren't existing,
	// approved, non-conditional timesheets.  If there are, this entry
	// screen cannot be used.
	// this error message supercedes all others.
	$sql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$emp_ID."' AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	if(ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// this employee cannot have this screen used to modify them.
		$bad_message = "Employee already has approved timesheets; cannot update.  Please call TS.";
	}

	// now we know if, for the button they pressed, data is valid.
		// if this was an attempted change, perform said change
	if($bad_message == ""){
		if($submit == "Update Employee"){
			$sql = "UPDATE AT_EMPLOYEE SET
					FIRST_NAME = '".$first_name."', 
					LAST_NAME = '".$last_name."',
					SUPERVISOR_ID = '".$super_id."',
					DEPARTMENT_ID = '".$department_id."',
					EMAIL_ADDRESS = '".$email_addy."',
					EMPLOYMENT_STATUS = '".$emp_status."',
					PAY_TYPE = '".$pay_type."',
					SUPERVISORY_STATUS = '".$super_status."',
					INTRANET_LOGIN_ID = '".$intra_login."',
					WINDOWS_LOGIN_ID = '".$window_login."',
					VACATION_WEEKLY_RATE = '".$vac_rate."',
					VACATION_YTD_REMAIN = '".$vac_remain."',
					SICK_WEEKLY_RATE = '".$sick_rate."',
					SICK_YTD_REMAIN = '".$sick_remain."',
					PERSONAL_YTD_REMAIN = '".$pers_remain."',
					DEPARTMENT_CODE = '".$department_code."',
					FIRST_WEEK_PAID_DATE = to_date('".$first_week."', 'MM/DD/YYYY'),
					INITIAL_VACATION = '".$vac_remain."',
					INITIAL_SICK = '".$sick_remain."',
					INITIAL_PERSONAL = '".$pers_remain."',
					ANNIVERSARY_DATE = to_date('".$anniv_date."', 'MM/DD/YYYY')
					WHERE EMPLOYEE_ID = '".$emp_ID."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}

		if($submit == "Add Employee"){
			$sql = "INSERT INTO AT_EMPLOYEE (FIRST_NAME,
					LAST_NAME,
					SUPERVISOR_ID,
					DEPARTMENT_ID,
					EMAIL_ADDRESS,
					EMPLOYMENT_STATUS,
					PAY_TYPE,
					SUPERVISORY_STATUS,
					INTRANET_LOGIN_ID,
					WINDOWS_LOGIN_ID,
					VACATION_WEEKLY_RATE,
					VACATION_YTD_REMAIN,
					SICK_WEEKLY_RATE,
					SICK_YTD_REMAIN,
					PERSONAL_YTD_REMAIN,
					DEPARTMENT_CODE,
					FIRST_WEEK_PAID_DATE,
					EMPLOYEE_ID,
					INITIAL_VACATION,
					INITIAL_SICK,
					INITIAL_PERSONAL,
					ANNIVERSARY_DATE)
						VALUES ('".$first_name."',
					'".$last_name."',
					'".$super_id."',
					'".$department_id."',
					'".$email_addy."',
					'".$emp_status."',
					'".$pay_type."',
					'".$super_status."',
					'".$intra_login."',
					'".$window_login."',
					'".$vac_rate."',
					'".$vac_remain."',
					'".$sick_rate."',
					'".$sick_remain."',
					'".$pers_remain."',
					'".$department_code."',
					to_date('".$first_week."', 'MM/DD/YYYY'),
					'".$emp_ID."',
					'".$vac_remain."',
					'".$sick_remain."',
					'".$pers_remain."',
					to_date('".$anniv_date."', 'MM/DD/YYYY'))";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
	}
					
	// with said change made (or not), proceed to get the values to put into the boxes
	// this will overwrite the previous values in the variables (assuming a non-bad entry),
	// but we've already sued them for what we need.
	if($submit != "" && $bad_message == ""){
		$sql = "SELECT ATE.*, TO_CHAR(FIRST_WEEK_PAID_DATE, 'MM/DD/YYYY') THE_DATE, TO_CHAR(ANNIVERSARY_DATE, 'MM/DD/YYYY') THE_ANNIV FROM AT_EMPLOYEE ATE WHERE EMPLOYEE_ID = '".$emp_ID."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// if a record exists, put its data into the website
			$first_name = $row['FIRST_NAME'];
			$last_name = $row['LAST_NAME'];
			$super_id = $row['SUPERVISOR_ID'];
			$department_id = $row['DEPARTMENT_ID'];
			$email_addy = $row['EMAIL_ADDRESS'];
			$emp_status = $row['EMPLOYMENT_STATUS'];
			$pay_type = $row['PAY_TYPE'];
			$super_status = $row['SUPERVISORY_STATUS'];
			$intra_login = $row['INTRANET_LOGIN_ID'];
			$window_login = $row['WINDOWS_LOGIN_ID'];
			$vac_rate = $row['VACATION_WEEKLY_RATE'];
			$vac_remain = $row['VACATION_YTD_REMAIN'];
			$sick_rate = $row['SICK_WEEKLY_RATE'];
			$sick_remain = $row['SICK_YTD_REMAIN'];
			$pers_remain = $row['PERSONAL_YTD_REMAIN'];
			$department_code = $row['DEPARTMENT_CODE'];
			$first_week = $row['THE_DATE'];
			$anniv_date = $row['THE_ANNIV'];

			$top_message = "Employee:  ".$first_name." ".$last_name;
		} else {
			// new employee
			$top_message = "New Employee";
		}
	}



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

<? 
	if ($submit == "" || ($submit == "Select Employee" && $bad_message != "")){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="employee_select" action="ATS_AT_Emp.php" method="post">
	<tr>
		<td align="center"><font size="3" face="Verdana">Employee ID#:  <input type="text" name="emp_ID" maxlength="7" size="10"></font></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Select Employee"></td>
	</tr>
<?
		if($bad_message != ""){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000"><? echo $bad_message; ?></font></td>
	</tr>
<?
		}
?>
</form>
</table>
<?
	} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="employee_data" action="ATS_AT_Emp.php" method="post">
<input type="hidden" name="emp_ID" value="<? echo $emp_ID; ?>">
<input type="hidden" name="top_message" value="<? echo $top_message; ?>">
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana">Employee  <? echo $emp_ID; ?>  </font><font size="2" face="Verdana"><a href="ATS_AT_Emp.php">(Change Employee ID)</a></font></td>
	</tr>
	<tr>
		<td colspan="4" align="center"><b><font size="3" face="Verdana"><? echo $top_message; ?></font></b></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
<?
		if($bad_message != ""){
?>
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana" color="#FF0000"><? echo $bad_message; ?></font></td>
	</tr>
<?
		}
		if(($submit == "Add Employee" || $submit == "Update Employee") && $bad_message == ""){
?>
	<tr>
		<td colspan="4" align="center"><font size="4" face="Verdana">Saved!</font></td>
	</tr>
<?
		}
?>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">First Name:</font></td>
		<td width="25%" align="left"><input type="text" name="first_name" size="25" maxlength="12" value="<? echo $first_name; ?>"></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Last Name:</font></td>
		<td width="25%" align="right"><input type="text" name="last_name" size="25" maxlength="20" value="<? echo $last_name; ?>"></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Supervisor ID:</font></td>
		<td width="25%" align="left"><select name="super_id">
										<option value="N/A">N/A (Exec Dir)</option>
<?
		$sql = "SELECT * FROM AT_EMPLOYEE WHERE SUPERVISORY_STATUS = 'SUPERVISOR'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($row['EMPLOYEE_ID'] != $emp_ID){
?>
					<option value="<? echo $row['EMPLOYEE_ID']; ?>" <? if($row['EMPLOYEE_ID'] == $super_id){ ?> selected <? } ?>><? echo $row['EMPLOYEE_ID']." - ".$row['FIRST_NAME']." ".$row['LAST_NAME']; ?></option>
<?
			}
		}
?>									</select></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Department ID:</font></td>
		<td width="25%" align="right"><input type="text" name="department_id" size="25" maxlength="12" value="<? echo $department_id; ?>"></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Email:</font></td>
		<td width="25%" align="left"><input type="text" name="email_addy" size="25" maxlength="60" value="<? echo $email_addy; ?>"></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Employment Status:</font></td>
		<td width="25%" align="right"><select name="emp_status">
										<option value="ACTIVE">ACTIVE</option>
										<option value="INACTIVE" <? if($emp_status == "INACTIVE"){ ?> selected <? } ?>>INACTIVE</option>
										</select></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Pay Type:</font></td>
		<td width="25%" align="left"><select name="pay_type">
										<option value="SALARIED">SALARIED</option>
										<option value="OVERTIME" <? if($pay_type == "OVERTIME"){ ?> selected <? } ?>>OVERTIME</option>
										</select></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Supervisory Status:</font></td>
		<td width="25%" align="right"><select name="super_status">
										<option value="EMPLOYEE">EMPLOYEE</option>
										<option value="SUPERVISOR" <? if($super_status == "SUPERVISOR"){ ?> selected <? } ?>>SUPERVISOR</option>
										</select></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Intranet Login:</font></td>
		<td width="25%" align="left"><input type="text" name="intra_login" size="25" maxlength="12" value="<? echo $intra_login; ?>"></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Windows Login:</font></td>
		<td width="25%" align="right"><input type="text" name="window_login" size="25" maxlength="12" value="<? echo $window_login; ?>"></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Vacation Weekly Accrual (Hours):</font></td>
		<td width="25%" align="left"><input type="text" name="vac_rate" size="25" maxlength="12" value="<? echo $vac_rate; ?>"></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Vacation Hours Remaining:</font></td>
		<td width="25%" align="right"><input type="text" name="vac_remain" size="25" maxlength="12" value="<? echo $vac_remain; ?>"></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Sick Weekly Accrual (Hours):</font></td>
		<td width="25%" align="left"><input type="text" name="sick_rate" size="25" maxlength="12" value="<? echo $sick_rate; ?>"></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Sick Hours Remaining:</font></td>
		<td width="25%" align="right"><input type="text" name="sick_remain" size="25" maxlength="12" value="<? echo $sick_remain; ?>"></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Department Code:</font></td>
		<td width="25%" align="left"><input type="text" name="department_code" size="25" maxlength="12" value="<? echo $department_code; ?>"></td>
		<td width="25%" align="right"><font size="2" face="Verdana">Personal Hours Remaining:</font></td>
		<td width="25%" align="right"><input type="text" name="pers_remain" size="25" maxlength="12" value="<? echo $pers_remain; ?>"></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">First Full Paid Week:</font></td>
		<td width="25%" align="left"><input type="text" name="first_week" size="25" maxlength="12" value="<? echo $first_week; ?>"></td>
		<td width="25%" align="right">&nbsp;</td>
		<td width="25%" align="right">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana">Hire Date:</font></td>
		<td width="25%" align="left"><input type="text" name="anniv_date" size="25" maxlength="12" value="<? echo $anniv_date; ?>"></td>
		<td width="25%" align="right">&nbsp;</td>
		<td width="25%" align="right">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="center"><input type="submit" name="submit" value="<? if($top_message == "New Employee"){ echo "Add Employee"; } else { echo "Update Employee"; } ?>"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");
?>




<?

function error_test_empmodify(&$first_name, &$last_name, $super_id, $department_id, $email_addy, $emp_status, $pay_type, $super_status, $intra_login, $window_login, $vac_rate, $vac_remain, $sick_rate, $sick_remain, $pers_remain, $department_code, $first_week, $anniv_date){
	// this function takes all the entered data, and makes sure each field is a valid entry.

	// first name:  only qualifier, not blank.
	if($first_name == ""){
		return "First name field blank.  No data saved.";
	} else {
		$first_name = str_replace("\\'", "`", $first_name);
	}

	// last name:  only qualifier, not blank.
	if($last_name == ""){
		return "Last name field blank.  No data saved.";
	} else {
		$last_name = str_replace("\\'", "`", $last_name);
	}

	if($email_addy == ""){
		return "Email field blank.  No data saved.";
	}

	if($intra_login == ""){
		return "Intranet Login field blank.  No data saved.";
	}

	if($window_login == ""){
		return "Windows Login field blank.  No data saved.";
	}

	if($email_addy == ""){
		return "Email field blank.  No data saved.";
	}

	if($vac_rate == ""){
		return "Vacation Accrual Rate field blank.  No data saved.";
	} elseif (!is_numeric($vac_rate)){
		return "Vacation Accrual Rate must be a number.  No data saved.";
	}

	if($vac_remain == ""){
		return "Vacation Hours Remain field blank.  No data saved.";
	} elseif (!is_numeric($vac_remain)){
		return "Vacation Hours Remaining must be a number.  No data saved.";
	}

	if($sick_rate == ""){
		return "Sick Accrual Rate field blank.  No data saved.";
	} elseif (!is_numeric($sick_rate)){
		return "Sick Accrual Rate must be a number.  No data saved.";
	}

	if($sick_remain == ""){
		return "Sick Hours Remain field blank.  No data saved.";
	} elseif (!is_numeric($sick_remain)){
		return "Sick Hours Remain must be a number.  No data saved.";
	}

	if($pers_remain == ""){
		return "Personal Hours Remain field blank.  No data saved.";
	} elseif (!is_numeric($pers_remain)){
		return "Personal Hours Remain must be a number.  No data saved.";
	}

	if($department_code == ""){
		return "Department Code field blank.  No data saved.";
	}

	if($first_week == ""){
		return "Personal Hours Remain field blank.  No data saved.";
	} elseif(!ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $first_week)){
		return "First Week Paid must be in MM/DD/YYYY format.  No data saved.";
	}

	if($anniv_date == ""){
		return "Anniversary Date field blank.  No data saved.";
	} elseif(!ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $first_week)){
		return "Hire Date must be in MM/DD/YYYY format.  No data saved.";
	}

	return "";
}



function error_test_empselect($emp_ID){
	// this fuction makes sure the entered Employee is valid.
	// I.E. the letter E followed by 6 digits.

	if(strlen($emp_ID) != 7){
		return "Employee ID entered was not 7 characters long";
	}

	if(substr($emp_ID, 0, 1) != "E"){
		return "Employee ID entered did not begin with `E`";
	}

	return "";
}
