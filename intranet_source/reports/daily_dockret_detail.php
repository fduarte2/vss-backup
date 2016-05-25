<?
/*
*	Adam Walter, July 10, 2008.
*
*	Ex-CRYSTAL report:  Daily Transfers
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");
  include("specific_page_access.php");

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

  $report_access = "daily_transfer_det";

  if(specific_page_access($report_access, $user) === false){
    printf("Access Denied from This Report.");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Daily Dock Return Summary Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="daily_dockret_detail_pdf.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Date:  </font></td>
		<td><input type="text" name="the_date" size="15" maxlength="10" value="<? echo $set_start_rec; ?>"><a href="javascript:show_calendar('get_data.the_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>