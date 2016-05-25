<?
/*
*	May, 2009.
*
*	Page for Dole DT report.
*
************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - Dole Booking Inbound Activity Report";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn2 = ora_logon("SAG_OWNER@BNI", "SAG");
	if($conn2 < 1){
		echo "Error logging on to the BNI Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor_date = ora_open($conn2);
	$cursor_order = ora_open($conn2);
	$cursor_details = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$date_start = $HTTP_POST_VARS['date_start'];
	$date_end = $HTTP_POST_VARS['date_end'];

	$grand_total = 0;
	$grand_total_wt = 0;

	if($submit != "" && $date_start == "" & $date_end == ""){
		echo "<font color=\"#FF0000\">At least one date must be entered</font>";
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Booking Inbound (For Unscanned/BNI Cargo) - Activity Report</font><br><font size="2" face="Verdana" color="#0066CC">Unscanned Paper does not record SSCC Code</font>
         <br />
         <hr>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="the_form" action="dole_book_in.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Start Date (optional):&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><input type="text" name="date_start" size="15" maxlength="10" value="<? echo $date_start; ?>"><a href="javascript:show_calendar('the_form.date_start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">End Date (optional):&nbsp;&nbsp;&nbsp;</font></td>
		<td align="left"><input type="text" name="date_end" size="15" maxlength="10" value="<? echo $date_end; ?>"><a href="javascript:show_calendar('the_form.date_end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="4"><input name="submit" type="submit" value="Generate Report"></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;<hr></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>BOL</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Received</b></font></td>
		<td><font size="2" face="Verdana"><b>WT Received</b></font></td>
	</tr>
<?
		$sql = "SELECT DISTINCT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE FROM CARGO_TRACKING CT, CARGO_MANIFEST CM 
				WHERE CT.LOT_NUM = CM.CONTAINER_NUM";
		if($date_start != ""){
			$sql .= " AND DATE_RECEIVED >= TO_DATE('".$date_start."', 'MM/DD/YYYY')";
		}
		if($date_end != ""){
			$sql .= " AND DATE_RECEIVED <= TO_DATE('".$date_end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
		}
		$sql .= " AND CT.COMMODITY_CODE = '1299' AND OWNER_ID != '312' ORDER BY TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
		ora_parse($cursor_date, $sql);
		ora_exec($cursor_date);
		while(ora_fetch_into($cursor_date, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$total_date = 0;
			$weight_date = 0;
?>
	<tr bgcolor="#00CCFF">
		<td colspan="5"><font size="2" face="Verdana"><b><? echo $date_row['THE_DATE']; ?></b></font></td>
	</tr>
<?
			$sql = "SELECT CARGO_DESCRIPTION, QTY_RECEIVED, CARGO_BOL, CARGO_WEIGHT, CARGO_WEIGHT || ' ' || CARGO_WEIGHT_UNIT THE_WEIGHT FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE CT.LOT_NUM = CM.CONTAINER_NUM AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$date_row['THE_DATE']."' AND CT.COMMODITY_CODE = '1299' AND OWNER_ID != '312' ORDER BY CARGO_BOL";
			ora_parse($cursor_details, $sql);
			ora_exec($cursor_details);
			while(ora_fetch_into($cursor_details, $details_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$total_date += $details_row['QTY_RECEIVED'];
				$weight_date += round($details_row['CARGO_WEIGHT'], 1);
				$grand_total += $details_row['QTY_RECEIVED'];
				$grand_total_wt += round($details_row['CARGO_WEIGHT'], 1);
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $date_row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['CARGO_BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['CARGO_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['THE_WEIGHT']; ?></font></td>
	</tr>
<?
			}
?>
	<tr bgcolor="#CCFFFF">
		<td colspan="3"><font size="2" face="Verdana"><b><? echo $date_row['THE_DATE']; ?> Total:</b></font></td>
		<td colspan="1"><font size="2" face="Verdana"><b><? echo $total_date; ?></b></font></td>
		<td colspan="1"><font size="2" face="Verdana"><b><? echo $weight_date." LB"; ?></b></font></td>
	</tr>
<?
		}
?>
	<tr bgcolor="#FFCC00">
		<td colspan="3"><font size="3" face="Verdana"><b>Grand Total:</b></font></td>
		<td colspan="1"><font size="3" face="Verdana"><b><? echo $grand_total; ?></b></font></td>
		<td colspan="1"><font size="3" face="Verdana"><b><? echo $grand_total_wt." LB"; ?></b></font></td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>