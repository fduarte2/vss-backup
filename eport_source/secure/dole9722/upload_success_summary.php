<?
/*
*		Adam Walter, Jan 2015.
*
*		Dole9722 review board
*********************************************************************************/


	$pagename = "dole9722_upload_report";
	include("dole9722_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}


	$start = $HTTP_POST_VARS['start'];
	$end = $HTTP_POST_VARS['end'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $start == ""){
		echo "<font color=\"#FF0000\">Start date is required.</font>";
		$submit = "";
	}

?>


<script type="text/javascript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Upload Success Summary
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="upload_success_summary_index.php" method="post">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Starting Date:</font></td>
		<td align="left"><input type="text" name="start" value="<? echo $start; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select.start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Ending Date:</font></td>
		<td><input type="text" name="end" value="<? echo $end; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select.end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Results"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Results"){
		$total_2 = 0;
		$total_3 = 0;
		$total_4 = 0;
		$total_total = 0;

		$sql = "SELECT TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') THE_DATE, 
					SUM(DECODE(ERROR_INPUT_LINE, NULL, DECODE(ERROR_MOVE_TO_DB, NULL, DECODE(IGNORE_LINE, NULL, DECODE(ACTIVITY, 2, 1, 0), 0), 0), 0)) THE_TWOS,
					SUM(DECODE(ERROR_INPUT_LINE, NULL, DECODE(ERROR_MOVE_TO_DB, NULL, DECODE(IGNORE_LINE, NULL, DECODE(ACTIVITY, 3, 1, 0), 0), 0), 0)) THE_THREES,
					SUM(DECODE(ERROR_INPUT_LINE, NULL, DECODE(ERROR_MOVE_TO_DB, NULL, DECODE(IGNORE_LINE, NULL, DECODE(ACTIVITY, 4, 1, 0), 0), 0), 0)) THE_FOURS,
					COUNT(*) JUST_IN_CASE
				FROM DOLE_9722_HEADER DH, DOLE_9722_DETAIL DD
				WHERE DH.FILE_ID = DD.FILE_ID";
		if($start != ""){
			$sql .= " AND REPORT_DATE >= TO_DATE('".$start."', 'MM/DD/YYYY')";
		}
		if($end != ""){
			$sql .= " AND REPORT_DATE < TO_DATE('".$end."', 'MM/DD/YYYY') + 1";
		}
		$sql .= "GROUP BY TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') 
				ORDER BY TO_DATE(TO_CHAR(REPORT_DATE, 'MM/DD/YYYY'), 'MM/DD/YYYY')";

		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			echo "<font color=\"#FF0000\">No Entries match searched criteria</font><br>";
		} else {

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>2 (Transfer In)</b></font></td>
		<td><font size="2" face="Verdana"><b>3 (Ship Out)</b></font></td>
		<td><font size="2" face="Verdana"><b>Shipouts Needing Correction</b></font></td>
		<td><font size="2" face="Verdana"><b>4 (Returns)</b></font></td>
		<td><font size="2" face="Verdana"><b>Net Outs (3 - 4)</b></font></td>
	</tr>
<?
			do {
				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#EEEEEE";
				} else {
					$bgcolor = "#FFFFFF";
				}

				$sql = "SELECT COUNT(*) THE_COUNT
						FROM DOLE_9722_DETAIL DD, DOLE_9722_HEADER DH
						WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".ociresult($stid, "THE_DATE")."'
							AND DD.FILE_ID = DH.FILE_ID
							AND ACTIVITY = '3'
							AND ERROR_MOVE_TO_DB LIKE '%was never received%'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);


//				$line_total = ociresult($stid, "THE_TWOS") + ociresult($stid, "THE_THREES") + ociresult($stid, "THE_FOURS");
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><a href="upload_success_details_index.php?act=2&date=<? echo urlencode(ociresult($stid, "THE_DATE")); ?>"><? echo ociresult($stid, "THE_TWOS"); ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="upload_success_details_index.php?act=3&date=<? echo urlencode(ociresult($stid, "THE_DATE")); ?>"><? echo ociresult($stid, "THE_THREES"); ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="no_exist_shipout_index.php?date=<? echo urlencode(ociresult($stid, "THE_DATE")); ?>"><? echo ociresult($short_term_data, "THE_COUNT"); ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="upload_success_details_index.php?act=4&date=<? echo urlencode(ociresult($stid, "THE_DATE")); ?>"><? echo ociresult($stid, "THE_FOURS"); ?></a></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $line_total; ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo (ociresult($stid, "THE_THREES") - ociresult($stid, "THE_FOURS")); ?></font></td>
	</tr>
<?
				$total_2 += ociresult($stid, "THE_TWOS");
				$total_3 += ociresult($stid, "THE_THREES");
				$total_4 += ociresult($stid, "THE_FOURS");
				$total_total += $line_total;
			} while(ocifetch($stid));
?>
	<tr bgcolor="#DDEEDD">
		<td><font size="2" face="Verdana"><b>Totals</b></font></td>
		<td><font size="2" face="Verdana"><? echo $total_2; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_3; ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $total_4; ?></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $total_total; ?></font></td> !-->
		<td><font size="2" face="Verdana">&nbsp;</font></td>
	</tr>
	
<?
		}
?>
</table>
<?
	}
?>