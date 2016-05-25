<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Clementine Inventory Report";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Supervisors system");
    include("pow_footer.php");
    exit;
  }

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Clementine Report Information Entry</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="JohnDoe" action="clementine_com_report.php" method="post">
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Customer Number:</font></td>
		<td><input type="text" name="customer_list" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Commodity Number:</font></td>
		<td><input type="text" name="commodity_choice" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="20%"><font size="2" face="Verdana">Report Type:</font></td>
		<td><font size="2" face="Verdana"><input type="radio" name="report_type" value="all" checked>All Vessels&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="report_type" value="totals">Totals Only</font></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><input type="submit" name="submit" value="Run Real-Time Inventory"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>