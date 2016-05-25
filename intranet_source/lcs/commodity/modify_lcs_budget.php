<?
/* August 2006, Adam Walter.
*  This file redone at Jon Jaffe's request to allow for more changes to be affected
*  To the "budget" table on LCS than jsut the budget number itself.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "LCS - Edit Budget";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $Comm = $HTTP_GET_VARS['comm'];
  $Type = $HTTP_GET_VARS['type'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Modify entry to LCS budget table</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="modify_detail" action="/lcs/commodity/index.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
	  <td colspan="3"><font size="3" face="Verdana">Budget Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Commodity:</font></td>
<?
  $sql = "SELECT * FROM COMMODITY WHERE COMMODITY_CODE = '".$Comm."'";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
      <td><font size="2" face="Verdana"><? echo $row['COMMODITY_NAME']; ?></font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td><font size="2" face="Verdana">Type:</font></td>
	  <td><font size="2" face="Verdana"><? echo $Type; ?></font></td>
   </tr>
<?
  $sql = "SELECT * FROM BUDGET WHERE COMMODITY = '".$Comm."' AND TYPE = '".$Type."'";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td><font size="2" face="Verdana">Budget:</font></td>
	  <td><input type="text" name="NewBudget" value="<? echo $row['BUDGET']; ?>" maxlength="5"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td><font size="2" face="Verdana">FTL:</font></td>
	  <td><input type="text" name="NewFtl" value="<? echo $row['FTL']; ?>" maxlength="5"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td><font size="2" face="Verdana">LTL:</font></td>
	  <td><input type="text" name="NewLtl" value="<? echo $row['LTL']; ?>" maxlength="5"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
      <td colspan="2"><input type="submit" name="submit" value="Submit Edit"><br><br></td>
   </tr>
<input type="hidden" name="Command" value="EDIT">
<input type="hidden" name="OldComm" value="<? echo $Comm; ?>">
<input type="hidden" name="OldType" value="<? echo $Type; ?>">
</form>
   <tr>
      <td colspan="4" align="center"><b>---OR---</b></td>
   </tr>
<form name="delete_detail" action="/lcs/commodity/index.php" method="post">
   <tr>
      <td colspan="4" align="center"><input type="submit" name="submit" value="Delete This entry from Budget Table"></td>
   </tr>
<input type="hidden" name="Command" value="DELETE">
<input type="hidden" name="OldComm" value="<? echo $Comm; ?>">
<input type="hidden" name="OldType" value="<? echo $Type; ?>">
</form>
</table>
<?
  include("pow_footer.php");
?>