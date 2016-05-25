<?
/* Adam Walter, May 2007.
*  I am to write a program, semi-analogous to the Meeting Agenda, which instead
*  Handles the "Hot Topics" of the Exec-Directors affairs.  The top-half of the
*  Current Meeting Agenda program can be stripped off (crane% downtime and newspaper
*  Ads run aren't really big time items), but some additional functionality, and
*  Therefore some additional PG tables, will be needed.  This should, indeed, be
*  An exercise in excitment for all.
*
*  This file handles the adding to said page.
**************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Meeting";
  $area_type = "DIRE";
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];


  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Director system");
    include("pow_footer.php");
    exit;
  }

  $today = date('m/d/Y');

  $user_occ = $userdata['user_occ'];
  /*
  if (stristr($user_occ,"Director") == false && array_search('ROOT', $user_types) === false ){
    printf("Access Denied");
    include("pow_footer.php");
    exit;
  }
*/
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

  $auth = $HTTP_POST_VARS['auth'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hot-List (Add)
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="add" action="hot_topic.php#<? echo $auth; ?>" method="post">
<input type="hidden" name="auth" value="<? echo $auth; ?>">
<input type="hidden" name="submit" value="Add Topic">
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Goal / Project / Task:<br>(Required; No double quotes please)&nbsp;&nbsp;&nbsp;</font></td>
		<td valign="center"><font size="2" face="Verdana"><textarea name="new_TOPIC" cols="50" rows="5"></textarea></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Start Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><input type="text" name="new_start_date" value="" size="10" maxlength="10"></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Estimated Completion Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><input type="text" name="new_comp_date" value="" size="10" maxlength="10"></font></td>
	</tr>
	<tr> 
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Status Date:&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $today; ?></font></td>
		<input type="hidden" name="init_stat_date" value="<? echo $today; ?>">
	</tr> 
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">Notes:<br>(Required; No double quotes please)&nbsp;&nbsp;&nbsp;</font></td>
		<td><font size="2" face="Verdana"><textarea name="notes" cols="50" rows="8"></textarea></font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td><font size="2" face="Verdana"><input type="submit" name="varied" value="Save"></td>
		<td>&nbsp;</td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>