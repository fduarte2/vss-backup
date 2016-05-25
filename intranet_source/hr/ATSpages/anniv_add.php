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
  $title = "HR - ATS Anniversary Add";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HR system");
    include("pow_footer.php");
    exit;
  }

  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);
  $user_occ = $userdata['user_occ'];


  include("connect.php");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
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
	    <font size="5" face="Verdana" color="#0066CC">Add entry to Anniversary table</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="add_detail" action="/hr/ATSpages/anniversary_table.php" method="post">
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="2"><font size="3" face="Verdana">Hour Rate Information:</font></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">Years of Service:</font>&nbsp;&nbsp;&nbsp;<input type="text" size="10" maxlength="6" name="years"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td><font size="2" face="Verdana">vacation Time-Off Rate (Hours/Week):</font>&nbsp;&nbsp;&nbsp;<input name="hours" size="10" maxlength="6" type="text"></td>
   </tr>
   <tr>
      <td width="3%" colspan="2">&nbsp;</td>
	  <td colspan="2"><input type="submit" name="submit" value="Add to Anniversary Table"></td>
   </tr>
<input type="hidden" name="Command" value="ADD">
</form>
</table>
<?
  include("pow_footer.php");
?>