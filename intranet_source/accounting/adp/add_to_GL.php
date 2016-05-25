<?
/* August 2006, Adam Walter.
*  This file redone at Jon Jaffe's request to allow for more changes to be affected
*  To the "budget" table on LCS than jsut the budget number itself.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "ACCT - Add to GL table";
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
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add entry to GL Benefit table</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="add_detail" action="/accounting/adp/adp_table.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="3"><font size="3" face="Verdana">Benefit Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">GL Code:</font></td>
	  <td><input type="text" size="10" maxlength="6" name="GL_code"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Benefit %:</font></td>
	  <td><input name="NewValue" size="10" maxlength="6" type="text"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Description:</font></td>
	  <td><input type="text" maxlength="60" size="40" name="NewDesc"></td>
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