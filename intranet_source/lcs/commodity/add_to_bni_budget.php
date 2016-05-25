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
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add entry to BNI budget table</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="add_detail" action="/lcs/commodity/budget.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="3"><font size="3" face="Verdana">Budget Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Commodity:</font></td>
	  <td><select name="NewComm">
<?
  $sql = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
           <option value="<? echo $row['COMMODITY_CODE']; ?>"><? echo $row['COMMODITY_NAME']; ?></option>
<?
  }
?>
           </select></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Type:</font></td>
	  <td><select name="NewType">
<?
  $sql = "SELECT TYPE FROM SERVICE_TYPE ORDER BY TYPE";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
          <option value="<? echo $row['TYPE']; ?>"><? echo $row['TYPE']; ?></option>
<?
  }
?>
          </select></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Budget:</font></td>
	  <td><input type="text" maxlength="5" size="10" name="NewBudget"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Budget Group:</font></td>
	  <td><select name="NewGrouping">
<?
  $sql = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
           <option value="<? echo $row['COMMODITY_CODE']; ?>"><? echo $row['COMMODITY_NAME']; ?></option>
<?
  }
?>
           </select></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td colspan="2"><input type="submit" name="submit" value="Add to Budget"></td>
   </tr>
<input type="hidden" name="Command" value="ADD">
</form>
</table>
<?
  include("pow_footer.php");
?>