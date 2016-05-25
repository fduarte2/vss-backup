<?
/* Adam Walter, 8/25/07
*
*	This page allows the "changing" of a Monday's worth of ST time
*	for CASA's and CASB's to be "upgraded" to OT.
*
*	This should only be used for MLK day.
*
*	Due to the possibility of someone entering time after this page is
*	Used, there is a qualifier that it can only be used on a Monday of
*	The *previous* week.
*
*	It is IMPERATIVE that this page be run AFTER the LCS cutoff for a
*	Given week.  Changing employee time after the fact would
*	Wreck havoc.
***************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "MLK Overtime Upgrade page";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");

  if($access_denied){
    printf("Access Denied from HRMS");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("LABOR@LCS", "LABOR");
//	$conn = ora_logon("LABOR@BNITEST", "LABOR_DEV");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	if(date('l' == "Sunday")){ // to prevent a bug with "$last_monday" calculation.
		echo "page cannot be accessed Sundays";
		exit;
	}

	$last_monday = date('m/d/Y', strtotime("last sunday")- 518400); // looks wierd, but gets most recent sunday, then subtracts 6 days from it
	$check_date = $HTTP_POST_VARS['check_date'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Update Hours"){
		if($last_monday != $check_date){
			echo "<font color=\"#FF0000\">Date entered does not match date expected; please re-type, or contact TS if more assistance is needed</font><br>";
		} else { // update da stuff!
			$sql = "SELECT COUNT(*) THE_COUNT, SUM(DURATION) TOTAL_HOURS FROM HOURLY_DETAIL HD, EMPLOYEE EMP WHERE HD.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND TO_CHAR(HIRE_DATE, 'MM/DD/YYYY') = '".$last_monday."' AND EMP.EMPLOYEE_TYPE_ID IN ('CASB', 'REGR') AND HD.EARNING_TYPE_ID IN ('ST', 'REG')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$emp_records_ST = $Short_Term_row['THE_COUNT'];
			$total_hours_ST = $Short_Term_row['TOTAL_HOURS'];

			$sql = "SELECT COUNT(*) THE_COUNT, SUM(DURATION) TOTAL_HOURS FROM HOURLY_DETAIL HD, EMPLOYEE EMP WHERE HD.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND TO_CHAR(HIRE_DATE, 'MM/DD/YYYY') = '".$last_monday."' AND EMP.EMPLOYEE_TYPE_ID IN ('CASB', 'REGR') AND HD.EARNING_TYPE_ID IN ('DT')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$emp_records_DT = $Short_Term_row['THE_COUNT'];
			$total_hours_DT = $Short_Term_row['TOTAL_HOURS'];

			$sql = "UPDATE HOURLY_DETAIL SET EARNING_TYPE_ID = 'OT' WHERE TO_CHAR(HIRE_DATE, 'MM/DD/YYYY') = '".$last_monday."' AND EARNING_TYPE_ID IN ('ST', 'REG') AND EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM EMPLOYEE WHERE EMPLOYEE_TYPE_ID IN ('CASB', 'REGR'))";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			echo "<font color=\"#FF0000\">Date:  ".$last_monday.".<br>".$total_hours_ST." Updated to OT.<br>".$total_hours_DT."  DT hours untouched.</font><br>";

			$mailTo1 = "skennard@port.state.de.us,";
			$mailTo1 .= "tscott@port.state.de.us,";
			$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
			$mailheaders .= "Bcc: " . "awalter@port.state.de.us,ithomas@port.state.de.us\r\n";

			$mailsubject = $last_monday." Straight time updated to OT\r\n";

			$body = $user." has activated the MLK script to update Monday straight time to Overtime for REGR and CASB employees.\r\n".$total_hours_ST." hours have been modified.\r\n";

			mail($mailTo1, $mailsubject, $body, $mailheaders);
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">HR "ST to OT" Upgrade
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="yaya" action="ST_to_OT_upgrade.php" method="post">
	<tr>
		<td><font size="3" face="Verdana"><b>Previous Monday:  <? echo $last_monday; ?></b></font></td>
	</tr>
	<tr>
		<td>&nbsp;<br>&nbsp;</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Date to Upgrade to Overtime:&nbsp;&nbsp;&nbsp;</b><input type="text" name="check_date" size="15" maxlength="10">&nbsp;&nbsp;(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>NOTE:  due to the very sensitive nature of wide-scale changing of hours,<br>re-entering the date is used as a preventative precaution.</b></font></td>
	</tr>
	<tr>
		<td><font color="#0000FF" size="2" face="Verdana"><b>Be sure to run this page after calculating LCS time, but before using the "Export to ADP" page.</b></font></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Update Hours"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>