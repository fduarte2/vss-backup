<?
/* Adam Walter, June 2007.
*  
*	This webpage ties in with the ATS system; it allows Sylvia to
*	Manage a table by which "anniversary dates" are kept.  Said
*	Table is used in a cron job to send email alerts to indicate
*	Whenever people are entitled to "raises" in their vacation times.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HR - LCS Modify";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }
  include("connect.php");

  $conn = ora_logon("LABOR@LCS", "LABOR");
//  $conn = ora_logon("LABOR@BNITEST", "LABOR");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $service = $HTTP_GET_VARS['service'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Modify LCS-Rate</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="modify_detail" action="LCSRateTable.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
	  <td colspan="3"><font size="3" face="Verdana">Rate Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td width="20%"><font size="2" face="Verdana">Service Type:</font></td>
      <td><font size="2" face="Verdana"><? echo $service; ?></font></td>
   </tr>
<?
  $sql = "SELECT SERVICE_ID, RATE_AMOUNT, TEXT_DESCRIPTION, LAST_UPDATE_BY, TO_CHAR(LAST_UPDATE_ON, 'MM/DD/YYYY HH24:MI') THE_ON 
			FROM LCS_BASE_RATE WHERE SERVICE_ID = '".$service."'";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td width="20%"><font size="2" face="Verdana">Description:</font></td>
      <td><font size="2" face="Verdana"><? echo $row['TEXT_DESCRIPTION']; ?></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Last Updated By:</font></td>
      <td><font size="2" face="Verdana"><? echo $row['LAST_UPDATE_BY']; ?></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Last Updated On:</font></td>
      <td><font size="2" face="Verdana"><? echo $row['THE_ON']; ?></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Hourly Rate:</font></td>
	  <td><input type="text" name="rate" size="6" value="<? echo $row['RATE_AMOUNT']; ?>" maxlength="6"><font size="2" face="Verdana"></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td colspan="2"><input type="submit" name="submit" value="Submit Edit"><br><br></td>
   </tr>
<input type="hidden" name="Command" value="EDIT">
<input type="hidden" name="service" value="<? echo $service; ?>">
</form>
<!--   <tr>
      <td colspan="4" align="center"><b>---OR---</b></td>
   </tr>
<form name="delete_detail" action="/hr/ATSpages/anniversary_table.php" method="post">
   <tr>
      <td colspan="4" align="center"><input type="submit" name="submit" value="Delete This entry from Anniversary Table"></td>
   </tr>
<input type="hidden" name="Command" value="DELETE">
<input type="hidden" name="years" value="<? echo $years; ?>">
</form> !-->
</table>
<?
  include("pow_footer.php");
?>