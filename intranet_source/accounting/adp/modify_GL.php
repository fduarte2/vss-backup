<?
/* August 2006, Adam Walter.
*  This file redone at Jon Jaffe's request to allow for more changes to be affected
*  To the "budget" table on LCS than jsut the budget number itself.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "ACCT - Modify GL Table";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $GL_code = $HTTP_GET_VARS['GL_code'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Modify entry to GL Benefit Table</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="modify_detail" action="/accounting/adp/adp_table.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
	  <td colspan="3"><font size="3" face="Verdana">Benefit Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td width="20%"><font size="2" face="Verdana">GL Code:</font></td>
      <td><font size="2" face="Verdana"><? echo $GL_code; ?></font></td>
   </tr>
<?
  $sql = "SELECT * FROM FINANCE_ADP_CONVERSION WHERE GL_ACCT = '".$GL_code."'";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Benefit:</font></td>
	  <td><input type="text" name="NewValue" size="6" value="<? echo $row['BENEFIT_ALLOCATION']; ?>" maxlength="6"><font size="2" face="Verdana">%</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td width="20%"><font size="2" face="Verdana">Description:</font></td>
	  <td><input type="text" name="NewDesc" size="40" value="<? echo $row['DESCRIPTION']; ?>" maxlength="60"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td colspan="2"><input type="submit" name="submit" value="Submit Edit"><br><br></td>
   </tr>
<input type="hidden" name="Command" value="EDIT">
<input type="hidden" name="GL_code" value="<? echo $GL_code; ?>">
</form>
   <tr>
      <td colspan="4" align="center"><b>---OR---</b></td>
   </tr>
<form name="delete_detail" action="/accounting/adp/adp_table.php" method="post">
   <tr>
      <td colspan="4" align="center"><input type="submit" name="submit" value="Delete This entry from Budget Table"></td>
   </tr>
<input type="hidden" name="Command" value="DELETE">
<input type="hidden" name="GL_code" value="<? echo $GL_code; ?>">
</form>
</table>
<?
  include("pow_footer.php");
?>