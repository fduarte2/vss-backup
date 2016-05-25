<?
/*
*	April 2009
*
*	Berth Utilization Report
*********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - Berth Utilization";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	// Connect to BNI
	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
	if(!$conn){
		$body = "Error logging on to the BNI Oracle Server: " . ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$date_from = $HTTP_POST_VARS['report_date_from'];
	$date_to = $HTTP_POST_VARS['report_date_to'];


?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Berth Utilization Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="berth_util.php" method="post">
	<tr>
		<td width="15%" align="left">From:</td>
		<td align="left"><input name="report_date_from" type="text" size="15" maxlength="15" value="<? echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.report_date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left">To:</td>
		<td align="left"><input name="report_date_to" type="text" size="15" maxlength="15" value="<? echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.report_date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Generate Report"){
		$temp_date_from = split("/", $date_from);
		$temp_date_to = split("/", $date_to);

		$total_report_duration = mktime(23,59,0,$temp_date_to[0],$temp_date_to[1],$temp_date_to[2]) - mktime(0,0,0,$temp_date_from[0],$temp_date_from[1],$temp_date_from[2]);
		$total_report_days = round($total_report_duration / (60*60*24), 0);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="100%" colspan="3">&nbsp;<br><hr>&nbsp;</td>
	</tr>
	<tr>
		<td width="100%" align="center" colspan="3"><font size="3" face="Verdana"><b>Berth Utilization Summary, for <? echo $total_report_days; ?> days (<? echo $date_from; ?> to <? echo $date_to; ?>)</b></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center">
			<table border="1" width="66%" cellpadding="4" cellspacing="0">
				<tr>
					<td><font size="2" face="Verdana"><b>Berth</b></font></td>
					<td><font size="2" face="Verdana"><b>Time Occupied (days)</b></font></td>
					<td><font size="2" face="Verdana"><b>%</b></font></td>
				</tr>
<?
		$sql = "SELECT BN.BERTH_NUM, NVL(SUM(LEAST(DATE_DEPARTED, TO_DATE('".$date_to." 23:59', 'MM/DD/YYYY HH24:MI'))
											 - 
											 GREATEST(DATE_ARRIVED, TO_DATE('".$date_from."', 'MM/DD/YYYY'))), 0) THE_DAYS
				FROM
					(SELECT DISTINCT BERTH_NUM FROM VOYAGE WHERE BERTH_NUM IS NOT NULL) BN,
					(SELECT BERTH_NUM, VP.LR_NUM, DATE_ARRIVED, DATE_DEPARTED FROM VESSEL_PROFILE VP, VOYAGE VO
					WHERE VP.LR_NUM = VO.LR_NUM
					AND (TO_DATE('".$date_from."', 'MM/DD/YYYY') BETWEEN DATE_ARRIVED AND DATE_DEPARTED
						OR
						TO_DATE('".$date_to." 23:59', 'MM/DD/YYYY HH24:MI') BETWEEN DATE_ARRIVED AND DATE_DEPARTED
						OR
						(TO_DATE('".$date_to." 23:59', 'MM/DD/YYYY HH24:MI') > DATE_DEPARTED AND
						 TO_DATE('".$date_from."', 'MM/DD/YYYY') < DATE_ARRIVED)
						)
					AND BERTH_NUM IS NOT NULL) UTIL
				WHERE BN.BERTH_NUM = UTIL.BERTH_NUM(+)
				GROUP BY BN.BERTH_NUM
				ORDER BY BN.BERTH_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$percent = ($row['THE_DAYS'] / ($total_report_duration / (60*60*24))) * 100;
						// # of days used divided by total report (converted to days from epoch seconds)
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo $row['BERTH_NUM']; ?></font></td>
					<td><font size="2" face="Verdana"><? echo round($row['THE_DAYS'], 1); ?></font></td>
					<td><font size="2" face="Verdana"><? echo round($percent, 1); ?></font></td>
				</tr>
<?
		}
?>
			</table></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<br><hr>&nbsp;</td>
	</tr>
	<tr>
		<td width="100%" align="center" colspan="3"><font size="3" face="Verdana"><b>Berth Utilization Details, <? echo $date_from; ?> to <? echo $date_to; ?></b></font></td>
	</tr>
	<tr>
		<td width="100%" align="center" colspan="3"><font size="2" face="Verdana"><b>Note:  Vessels arriving before, or leaving after,<br>the report date range have their dates truncated to the report dates.</b></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center">
			<table border="1" cellpadding="4" cellspacing="0">
				<tr>
					<td><font size="2" face="Verdana"><b>Berth</b></font></td>
					<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
					<td><font size="2" face="Verdana"><b>Arrived</b></font></td>
					<td><font size="2" face="Verdana"><b>Departed</b></font></td>
				</tr>
<?
		$sql = "SELECT BN.BERTH_NUM, NVL(UTIL.VESSEL_NAME, 'NONE') THE_VES,
				NVL(TO_CHAR(GREATEST(DATE_ARRIVED, TO_DATE('".$date_from."', 'MM/DD/YYYY')), 'MM/DD/YYYY HH24:MI'), 'NONE') NICE_ARR, 
				NVL(TO_CHAR(LEAST(DATE_DEPARTED, TO_DATE('".$date_to."', 'MM/DD/YYYY')), 'MM/DD/YYYY HH24:MI'), 'NONE') NICE_DEP
				FROM
					(SELECT DISTINCT BERTH_NUM FROM VOYAGE WHERE BERTH_NUM IS NOT NULL) BN,
					(SELECT BERTH_NUM, VP.VESSEL_NAME, DATE_ARRIVED, DATE_DEPARTED FROM VESSEL_PROFILE VP, VOYAGE VO
					WHERE VP.LR_NUM = VO.LR_NUM
					AND (TO_DATE('".$date_from."', 'MM/DD/YYYY') BETWEEN DATE_ARRIVED AND DATE_DEPARTED
						OR
						TO_DATE('".$date_to."', 'MM/DD/YYYY') BETWEEN DATE_ARRIVED AND DATE_DEPARTED
						OR
						(TO_DATE('".$date_to."', 'MM/DD/YYYY') > DATE_DEPARTED AND
						 TO_DATE('".$date_from."', 'MM/DD/YYYY') < DATE_ARRIVED)
						)
					AND BERTH_NUM IS NOT NULL) UTIL
				WHERE BN.BERTH_NUM = UTIL.BERTH_NUM(+)
				ORDER BY BERTH_NUM, DATE_ARRIVED";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						// # of days used divided by total report (converted to days from epoch seconds)
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo $row['BERTH_NUM']; ?></font></td>
					<td><font size="2" face="Verdana"><? echo $row['THE_VES']; ?></font></td>
					<td><font size="2" face="Verdana"><? echo $row['NICE_ARR']; ?></font></td>
					<td><font size="2" face="Verdana"><? echo $row['NICE_DEP']; ?></font></td>
				</tr>
<?
		}
?>
			</table></td>
		<td>&nbsp;</td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>