<?
/* Adam Walter, Apr 2009.
*  
*  This script is part of the Administrative Building Timesheet Process.
*
*  This script is used by HR to unapprove an approved timesheet;
*	Provided that it has not yet been uploaded to ADP.
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
  $function_cursor = ora_open($conn);
  $Short_Term_Data = ora_open($conn);

  $submit = $HTTP_POST_VARS['submit'];
  $employee = $HTTP_POST_VARS['employee'];
  $week_of = $HTTP_POST_VARS['week_of'];

	if($submit == "Get Timesheet"){
		$bad_message = "";

		$sql = "SELECT STATUS, NVL(TO_CHAR(PAYROLL_DATETIME, 'MM/DD/YYYY'), 'NONE') THE_APP FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$employee."' AND TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') = '".$week_of."'";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		if(!ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$bad_message = "No record for Employee ".$employee." for week of ".$week_of.".";
		} elseif($row['STATUS'] != 'APPROVED'){
			$bad_message = "Timesheet for ".$employee.", week of ".$week_of." is currently of status ".$row['STATUS'].".  Cannot Unapprove.";
		} elseif($row['THE_APP'] != 'NONE'){
			$bad_message = "Timesheet for ".$employee.", week of ".$week_of." has already been approved on ".$row['THE_APP'].".  Cannot Unapprove.";
		}

		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $week_of)){
			$bad_message = "Date must be in MM/DD/YYYY format";
		}


	}

	if($submit == "Unapprove Timesheet"){
		$sql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'HR:  Unapproved' FROM TIME_SUBMISSION TS WHERE EMPLOYEE_ID = '".$employee."' AND TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') = '".$week_of."')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "UPDATE TIME_SUBMISSION SET STATUS = 'ON HOLD' WHERE EMPLOYEE_ID = '".$employee."' AND TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') = '".$week_of."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "SELECT EMAIL_ADDRESS, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."'";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$emp_email = $row['EMAIL_ADDRESS'];

		$emp_name = "(".$row['FIRST_NAME']." ".$row['LAST_NAME'].")";

		$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$employee."')";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$sup_email = $row['EMAIL_ADDRESS'];

		$HR_email = "skennard@port.state.de.us";

		$body = "Employee ".$employee." ".$emp_name." timesheet for week of ".$week_of." has been unapproved and placed 'ON HOLD' by HR.  Please resubmit and have reapproved promptly to ensure correct payroll.";
//		$body .= "\r\n\r\n*** THIS IS ONLY A TEST; NO TIMESHEET INFORMATION ACTUALLY CHANGED.***";

		$mailsubject = "Timesheet UNAPPROVED by HR";

		$mailheaders = "From:  PoWMailServer@port.state.de.us\r\n";
		$mailheaders .= "CC:  ".$sup_email.",".$HR_email."\r\n";

		$mailTo = $emp_email;

		mail($mailTo, $mailsubject, $body, $mailheaders);


		echo "<font size=\"3\" face=\"Verdana\" color=\"#000088\">".$employee." timesheet for ".$week_of." Unapproved.  Email notification distributed.</font>";
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">ATS Override Unapproval
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="employee_select" action="ATS_unapprove.php" method="post">
<?
	if($bad_message != ""){
?>
	<tr>
		<td align="left"><font size="3" color="#FF0000" face="Verdana"><? echo $bad_message; ?></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td align="center"><select name="employee"><option value="">Select an Employee:</option>
<?
	$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE' ORDER BY EMPLOYEE_ID";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	while(ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['EMPLOYEE_ID']; ?>"<? if($row['EMPLOYEE_ID'] == $employee){?> selected <?}?>><? echo $row['EMPLOYEE_ID']." - ".$row['FIRST_NAME']." ".$row['LAST_NAME']; ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana">Week Of:&nbsp;&nbsp;&nbsp;</font><input type="text" name="week_of" maxlength="10" size="10" value="<? echo $week_of; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('employee_select.week_of');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Get Timesheet"></td>
	</tr>
</table>
<?
	if($submit == "Get Timesheet" && $bad_message == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="emp_unapprove" action="ATS_unapprove.php" method="post">
<input type="hidden" name="employee" value="<? echo $employee; ?>">
<input type="hidden" name="week_of" value="<? echo $week_of; ?>">
	<tr>
		<td>&nbsp;<br><hr><br>&nbsp;</td>
	</tr>
<?
		$sql = "SELECT WEEK_TOTAL_REG, WEEK_TOTAL_HOLIDAY, WEEK_TOTAL_VACATION, WEEK_TOTAL_PERSONAL, WEEK_TOTAL_SICK, WEEK_TOTAL_OVERTIME, WEEK_TOTAL_TOTAL FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$employee."' AND TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') = '".$week_of."'";
		ora_parse($Short_Term_Data, $sql);
		ora_exec($Short_Term_Data);
		ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td><font size="2" face="Verdana">Employee:&nbsp;&nbsp;&nbsp;<? echo $employee; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Week of:&nbsp;&nbsp;&nbsp;<? echo $week_of; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Regular Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_REG']); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Holiday Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_HOLIDAY']); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Vacation Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_VACATION']); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Personal Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_PERSONAL']); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Sick Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_SICK']); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Overtime Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_OVERTIME']); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Total Hours:&nbsp;&nbsp;&nbsp;<? echo (0 + $row['WEEK_TOTAL_TOTAL']); ?></font></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Unapprove Timesheet"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");
?>
