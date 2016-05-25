<?
/*
*	Adam Walter, July 10, 2008.
*
*	Ex-CRYSTAL report:  Daily Outbound Activity
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");
  include("specific_page_access.php");

  // Define some vars for the skeleton page
  $title = "Reports System";
  $area_type = "RPTS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from RPTS system");
    include("pow_footer.php");
    exit;
  }

  $report_access = "order_details";

  if(specific_page_access($report_access, $user) === false){
    printf("Access Denied from This Report.");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	$cursor_first = ora_open($conn);
//	$cursor_second = ora_open($conn);
//	$cursor_third = ora_open($conn);
//	$Short_Term_Cursor = ora_open($conn);

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Order Details Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="order_details_pdf.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Date (Start):  </font></td>
		<td><input type="text" name="date_start" size="15" maxlength="10" value="<? echo $set_start_rec; ?>"><a href="javascript:show_calendar('get_data.date_start');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Date (End):  </font></td>
		<td><input type="text" name="date_end" size="15" maxlength="10" value="<? echo $set_start_rec; ?>"><a href="javascript:show_calendar('get_data.date_end');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Cust:  </font></td>
		<td><select name="cust">
<?
	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE
			ORDER BY CUSTOMER_ID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "CUSTOMER_ID"); ?>"><? echo ociresult($stid, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>