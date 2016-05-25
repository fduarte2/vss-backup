<?
/*
*	Adam Walter, Mar 2011.
*
*	Allows INV to enter expected Walmart Repack-loads (as well
*	As their return dates)
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

  $submit = $HTTP_POST_VARS['submit'];
  $date = $HTTP_POST_VARS['date'];

	if($submit != "" && !ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $date)){
		echo "<font color=\"#FF0000\">Date not in MM/DD/YYYY format (was entered as ".$date.").</font><br>";
	} elseif($submit != ""){
		ob_start();
		@system("../TS_Program/CBP_e_fax/CBP_e_fax_mail.sh \"".$date."\"");
		@system("../TS_Program/CBP_e_fax/CBP_e_faxxls.sh \"".$date."\"");
//		@system("../TS_Testing/CBP_e_fax_mail.sh \"".$date."\"");
//		@system("../TS_Testing/CBP_e_faxxls.sh \"".$date."\"");
		ob_end_clean();		
		echo "<font color=\"#0000FF\">Forcerun complete.  Please check your inbox in a few minutes.</font><br>";
	}
  



?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">CBP notification Force-run</font
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="forcerun" action="forcerun_CBP.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>Force run for Date:</b></font></td>
		<td><input type="text" name="date" size="10" maxlength="10">&nbsp;&nbsp;<a href="javascript:show_calendar('forcerun.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Submit"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
