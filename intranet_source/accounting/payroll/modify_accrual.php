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

     // Connect to BNI
   $ora_connBNI = ora_logon("SAG_OWNER@BNI", "SAG");
   if (!$ora_connBNI) {
      printf("Error logging on to Oracle Server: ");
      printf(ora_errorcode($ora_connBNI));
      exit;
   }
   $BNIcursor = ora_open($ora_connBNI);
   if (!$BNIcursor) {
      printf("Error opening a cursor on Oracle Server: ");
      printf(ora_errorcode($BNIcursor));
      exit;
   }


	$emp_type = $HTTP_GET_VARS['emp_type'];
	$pay_code = $HTTP_GET_VARS['pay_code'];

	$sql = "SELECT * FROM PARTIAL_WEEK_ACCRUAL_MAP WHERE EMP_TYPE = '".$emp_type."' AND PAY_CODE = '".$pay_code."'";
	ora_parse($BNIcursor, $sql);
	ora_exec($BNIcursor);
	ora_fetch_into($BNIcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$gl_code = $row['GL_CODE'];
	$multiplier = $row['MULTIPLIER'];
	$avg_wage = $row['AVERAGE_WAGE'];

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Modify Accrual Table entry</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="modify_detail" action="/accounting/payroll/accrual_table.php" method="post">
<input type="hidden" name="emp_type" value="<? echo $emp_type; ?>">
<input type="hidden" name="pay_code" value="<? echo $pay_code; ?>">
<input type="hidden" name="Command" value="EDIT">
   <tr>
      <td width="1%">&nbsp;</td>
	  <td colspan="3"><font size="3" face="Verdana">Accrual Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td width="20%"><font size="2" face="Verdana">Employee Type:</font></td>
      <td><font size="2" face="Verdana"><? echo $emp_type; ?></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td width="20%"><font size="2" face="Verdana">Pay Code:</font></td>
      <td><font size="2" face="Verdana"><? echo $pay_code; ?></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">GL Code:</font></td>
	  <td><input type="text" name="gl_code" size="10" value="<? echo $gl_code; ?>" maxlength="4"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Average Wage:</font></td>
	  <td><font size="2" face="Verdana">$</font><input type="text" name="avg_wage" size="10" value="<? echo $avg_wage; ?>" maxlength="5"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Pay Rate Multiplier:</font></td>
	  <td><font size="2" face="Verdana">X</font><input type="text" name="multiplier" size="10" value="<? echo $multiplier; ?>" maxlength="3"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td colspan="2"><input type="submit" name="submit" value="Submit Edit"><br><br></td>
   </tr>
</form>
   <tr>
      <td colspan="4" align="center"><b>---OR---</b></td>
   </tr>
<form name="delete_detail" action="/accounting/payroll/accrual_table.php" method="post">
   <tr>
      <td colspan="4" align="center"><input type="submit" name="submit" value="Delete This entry from Budget Table"></td>
   </tr>
<input type="hidden" name="Command" value="DELETE">
<input type="hidden" name="emp_type" value="<? echo $emp_type; ?>">
<input type="hidden" name="pay_code" value="<? echo $pay_code; ?>">
</form>
</table>
<?
  include("pow_footer.php");
?>