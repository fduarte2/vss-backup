<?
/*
*	Adam Walter, Apr 2014
*
*	Dumps a database report for Walmart Loads for Inventory to read.
*
***********************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$start_rec = $HTTP_POST_VARS['start_rec'];
	$end_rec = $HTTP_POST_VARS['end_rec'];
	if($submit == "Retrieve" && $start_rec == "" && $end_rec == ""){
		echo "<font color=\"#FF0000\">Please enter at least 1 date value</font>";
		$submit = "";
	}

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Truck Checkin Log Report</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="select" action="walmart_check_log.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date (From):  </font></td>
		<td><input type="text" name="start_rec" size="15" maxlength="10" value="<? echo $start_rec; ?>"><a href="javascript:show_calendar('select.start_rec');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date (To):  </font></td>
		<td><input type="text" name="end_rec" size="15" maxlength="10" value="<? echo $end_rec; ?>"><a href="javascript:show_calendar('select.end_rec');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve"){
?>
<table width="100%" border="1">
	<tr>
		<td><font size="2" face="Verdana"><b>Load#</b></font></td>
		<td><font size="2" face="Verdana"><b>Load Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Temp</b></font></td>
		<td><font size="2" face="Verdana"><b>PU-State</b></font></td>
		<td><font size="2" face="Verdana"><b>Truck Checkin Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Truck Checkout Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Driver Name</b></font></td>
		<td><font size="2" face="Verdana"><b>Truck Lic Number</b></font></td>
		<td><font size="2" face="Verdana"><b>Trucking Company</b></font></td>
		<td><font size="2" face="Verdana"><b>Appointment Date/Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Lic State</b></font></td>
	</tr>
<?
		$sql = "SELECT LOAD_NUM, TEMP, PU_STATE, DRIVER_NAME, TRUCK_LICENSE_NUMBER, TRUCKING_COMPANY, LICENSE_STATE,
					TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, NVL(TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY HH24:MI:SS'), 'NONE') THE_CHECKIN,
					NVL(TO_CHAR(TRUCK_CHECKOUT_TIME, 'MM/DD/YYYY HH24:MI:SS'), 'NONE') THE_CHECKOUT,
					NVL(TO_CHAR(APPOINTMENT_DATETIME, 'MM/DD/YYYY HH24:MI:SS'), 'NONE') THE_APPOINT
				FROM WDI_LOAD_HEADER
				WHERE STATUS != 'CANCELLED'";
		if($start_rec != ""){
			$sql .= " AND LOAD_DATE >= TO_DATE('".$start_rec."', 'MM/DD/YYYY')";
		}
		if($end_rec != ""){
			$sql .= " AND LOAD_DATE <= TO_DATE('".$end_rec."', 'MM/DD/YYYY')";
		}
		$sql .= " ORDER BY LOAD_NUM";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
?>
	<tr>
		<td colspan="11" align="center"><font size="2" face="Verdana">No Loads found in specified date range.</font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "LOAD_NUM"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_LOAD"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TEMP"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "PU_STATE"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_CHECKIN"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_CHECKOUT"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "DRIVER_NAME"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCK_LICENSE_NUMBER"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "TRUCKING_COMPANY"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_APPOINT"); ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "LICENSE_STATE"); ?>&nbsp;</font></td>
	</tr>
<?
			} while(ocifetch($short_term_data));
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>