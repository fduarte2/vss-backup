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
  $title = "Inventory - Dole DT inbound/outbound Activity Report";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor_date = ora_open($conn2);
	$cursor_order = ora_open($conn2);
	$cursor_details = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$in_or_out = $HTTP_POST_VARS['in_or_out'];
	$date_start = $HTTP_POST_VARS['date_start'];
	$date_end = $HTTP_POST_VARS['date_end'];

	$grand_total = 0;
	$grand_total_wt = 0;

	if($in_or_out == "Inbound"){
		$service_code_sql = "IN ('1', '8')";
	} else {
		$service_code_sql = "= '6'";
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Dole DT inbound/outbound Activity Report</font>
         <br />
         <hr>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="the_form" action="dole_DT_in_out.php" method="post">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Start Date (optional):&nbsp;&nbsp;&nbsp;</font></td>
		<td width="15%" align="left"><input type="text" name="date_start" size="15" maxlength="10" value="<? echo $date_start; ?>"><a href="javascript:show_calendar('the_form.date_start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
		<td width="30%">&nbsp;</td>
		<td align="left"><font size="2" face="Verdana"><input type="radio" name="in_or_out" value="Inbound" checked>&nbsp;Inbound</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">End Date (optional):&nbsp;&nbsp;&nbsp;</font></td>
		<td width="15%"><input type="text" name="date_end" size="15" maxlength="10" value="<? echo $date_end; ?>"><a href="javascript:show_calendar('the_form.date_end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
		<td width="30%">&nbsp;</td>
		<td align="left"><font size="2" face="Verdana"><input type="radio" name="in_or_out" value="Outbound">&nbsp;Outbound</font></td>
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
		<td colspan="7" align="center"><font size="4" face="Verdana"><b>Dole DT <? echo $in_or_out ?> Report</b></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Date of Activity</font></td>
		<td><font size="3" face="Verdana">Order #</font></td>
		<td><font size="3" face="Verdana">DT #</font></td>
		<td><font size="3" face="Verdana">Grade Code</font></td>
		<td><font size="3" face="Verdana">Pallet ID</font></td>
		<td><font size="3" face="Verdana">Cargo Description</font></td>
		<td><font size="3" face="Verdana">Weight (LBS)</font></td>
	</tr>
<?
		$sql = "SELECT DISTINCT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE FROM CARGO_ACTIVITY WHERE 1 = 1 ";
		if($date_start != ""){
			$sql .= " AND DATE_OF_ACTIVITY >= TO_DATE('".$date_start."', 'MM/DD/YYYY')";
		}
		if($date_end != ""){
			$sql .= " AND DATE_OF_ACTIVITY <= TO_DATE('".$date_end." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
		}
		$sql .= " AND SERVICE_CODE ".$service_code_sql." AND ACTIVITY_DESCRIPTION IS NULL 
		AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS) 
		ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY')";

		ora_parse($cursor_date, $sql);
		ora_exec($cursor_date);
		while(ora_fetch_into($cursor_date, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$total_date = 0;
			$weight_date = 0;
?>
	<tr bgcolor="#66FF00">
		<td colspan="7"><font size="2" face="Verdana"><b><? echo $date_row['THE_DATE']; ?></b></font></td>
	</tr>
<?
			$sql = "SELECT DISTINCT ORDER_NUM FROM CARGO_ACTIVITY WHERE TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date_row['THE_DATE']."' AND SERVICE_CODE ".$service_code_sql." AND ACTIVITY_DESCRIPTION IS NULL AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS) ORDER BY ORDER_NUM";

			ora_parse($cursor_order, $sql);
			ora_exec($cursor_order);
			while(ora_fetch_into($cursor_order, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$total_order = 0;
				$weight_order = 0;
?>
	<tr bgcolor="#FF3300">
		<td>&nbsp;</td>
		<td colspan="6"><font size="2" face="Verdana"><b><? echo $order_row['ORDER_NUM']; ?></b></font></td>
	</tr>
<?
				$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_ACT, ORDER_NUM, CT.BOL, CT.PALLET_ID, CARGO_DESCRIPTION, WEIGHT, CT.BATCH_ID 
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
						WHERE CT.PALLET_ID = CA.PALLET_ID 
							AND CT.REMARK = 'DOLEPAPERSYSTEM' 
							AND CT.BOL = CA.BATCH_ID 
							AND CA.SERVICE_CODE ".$service_code_sql." 
							AND ACTIVITY_DESCRIPTION IS NULL 
							AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS) 
							AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date_row['THE_DATE']."' 
							AND ORDER_NUM = '".$order_row['ORDER_NUM']."' 
						ORDER BY CT.BOL, PALLET_ID";
				ora_parse($cursor_details, $sql);
				ora_exec($cursor_details);
				while(ora_fetch_into($cursor_details, $details_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$total_order++;
					$total_date++;
					$grand_total++;
					$weight_order += $details_row['WEIGHT'];
					$weight_date += $details_row['WEIGHT'];
					$grand_total_wt += $details_row['WEIGHT'];
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $details_row['THE_ACT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['BATCH_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['CARGO_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $details_row['WEIGHT']; ?></font></td>
	</tr>
<?
				}
?>
	<tr bgcolor="#FFCC99">
		<td>&nbsp;</td>
		<td colspan="4"><font size="2" face="Verdana"><b><? echo $order_row['ORDER_NUM']; ?> Total:</b></font></td>
		<td colspan="1"><font size="2" face="Verdana"><b><? echo $total_order; ?></b></font></td>
		<td colspan="1"><font size="2" face="Verdana"><b><? echo $weight_order; ?></b></font></td>
	</tr>
<?
			}
?>
	<tr bgcolor="#CCFF99">
		<td colspan="5"><font size="2" face="Verdana"><b><? echo $date_row['THE_DATE']; ?> Total:</b></font></td>
		<td colspan="1"><font size="2" face="Verdana"><b><? echo $total_date; ?></b></font></td>
		<td colspan="1"><font size="2" face="Verdana"><b><? echo $weight_date; ?></b></font></td>
	</tr>
<?
		}
?>
	<tr bgcolor="#00FFFF">
		<td colspan="5"><font size="3" face="Verdana"><b>Grand Total:</b></font></td>
		<td colspan="1"><font size="3" face="Verdana"><b><? echo $grand_total; ?></b></font></td>
		<td colspan="1"><font size="3" face="Verdana"><b><? echo $grand_total_wt; ?></b></font></td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>
