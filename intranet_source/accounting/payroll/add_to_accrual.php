<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Payroll Accrual";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }

   // connect to LCS database
   $ora_conn = ora_logon("LABOR@LCS", "LABOR");
   if (!$ora_conn) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_conn));
      exit;
   }

   // create a cursor
   $cursor = ora_open($ora_conn);
   if (!$cursor) {
      printf("Error opening a cursor on Oracle Server: ");
      printf(ora_errorcode($cursor));
      exit;
   }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add entry to Accrual table</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="add_detail" action="/accounting/payroll/accrual_table.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="3"><font size="3" face="Verdana">Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">GL Code:</font></td>
	  <td><select name="emp_type"><option value="">Select Employee Type:</option>
<?
	$sql = "SELECT DISTINCT EMPLOYEE_TYPE_ID FROM EMPLOYEE ORDER BY EMPLOYEE_TYPE_ID";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['EMPLOYEE_TYPE_ID']; ?>"><? echo $row['EMPLOYEE_TYPE_ID']; ?></option>
<?
	}
?>
					</select></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Pay Code:</font></td>
	  <td><select name="pay_code"><option value="">Select a Pay Code:
<?
	$sql = "SELECT DISTINCT EARNING_TYPE_ID FROM HOURLY_DETAIL WHERE HIRE_DATE > TO_DATE('01/01/2006', 'MM/DD/YYYY') AND EARNING_TYPE_ID IS NOT NULL ORDER BY EARNING_TYPE_ID";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['EARNING_TYPE_ID']; ?>"><? echo $row['EARNING_TYPE_ID']; ?></option>
<?
	}
?>
					</select></td>	
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">GL Code:</font></td>
	  <td><input type="text" maxlength="4" size="10" name="gl_code"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Average Pay Rate:</font></td>
	  <td><input type="text" maxlength="5" size="10" name="avg_wage"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Pay Rate Multiplier:</font></td>
	  <td><input type="text" maxlength="3" size="10" name="multiplier"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td colspan="2"><input type="submit" name="submit" value="Add to GL Table"></td>
   </tr>
<input type="hidden" name="Command" value="ADD">
</form>
</table>
<?
  include("pow_footer.php");
?>