<?
/*
*		Adam Walter, April 2009
*
*		A report for Dole Dockticket paper, that shows a recap
*		Of Orders for a vessel, based on search criteria.
*
*		Note:  In addition to the on-screen part, this also writes
*		To a *.csv file, linked to from the resulting page.
************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$report_date_from = $HTTP_POST_VARS['report_date_from'];
	$report_date_to = $HTTP_POST_VARS['report_date_to'];
//	$order_status = $HTTP_POST_VARS['order_status'];
	$destination = $HTTP_POST_VARS['destination'];

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="cont_listing_form.php" method="post">
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Container Listing by Destination.</b></font></td>
	</tr>
	<tr>
		<td width="20%" align="left">Vessel</td>
		<td align="left"><select name="vessel"><option value="">Select Vessel</option>
<?		
		$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'DOLE' AND LR_NUM IN (SELECT DISTINCT ARRIVAL_NUM FROM DOLEPAPER_ORDER WHERE STATUS IN ('6', '7')) ORDER BY LR_NUM DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
		}
?>		
			</select></td>
		<td rowspan="2">
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
				<tr>
					<td align="left">Load Date (from):</td>
					<td align="left"><input name="report_date_from" type="text" size="15" maxlength="15" value="<? echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.report_date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
				</tr>
				<tr>
					<td width="20%" align="left">Load Date (to):</td>
					<td align="left"><input name="report_date_to" type="text" size="15" maxlength="15" value="<? echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.report_date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="20%" align="left">Destination:</td>
		<td align="left"><select name="destination"><option value="all">All</option>
<?
		$sql = "SELECT DESTINATION_NB, DESTINATION FROM DOLEPAPER_DESTINATIONS ORDER BY DESTINATION_NB";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['DESTINATION_NB']; ?>"<? if($row['DESTINATION_NB'] == $destination){?> selected <?}?>><? echo $row['DESTINATION']; ?></option>
<?
		}
?>		
			</select></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>
<?
	if($vessel != ""){
		$sql = "SELECT DD.CONTAINER_ID, SUM(QTY_CHANGE) THE_SUM, SUM(WEIGHT * CONVERSION_FACTOR) THE_WEIGHT, DD.ORDER_NUM, SEAL
				FROM DOLEPAPER_ORDER DD, CARGO_ACTIVITY CA, CARGO_TRACKING CT, UNIT_CONVERSION_FROM_BNI UCFB
				WHERE TO_CHAR(DD.ORDER_NUM) = CA.ORDER_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CA.SERVICE_CODE = '6'
					AND CA.ACTIVITY_DESCRIPTION IS NULL
					AND UCFB.SECONDARY_UOM = 'KG'
					AND UCFB.PRIMARY_UOM = CT.WEIGHT_UNIT
					AND DD.ARRIVAL_NUM = '".$vessel."' ";
		if($report_date_from != ""){
			$sql .= "AND DD.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
		}
		if($report_date_to != ""){
			$sql .= "AND DD.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
		}
		if($destination != "all"){
			$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
		}
/*		if($order_status != "all"){
			$sql .= "AND DD.STATUS = '".$order_status."' ";
		} else {
			$sql .= "AND DD.STATUS IN ('6', '7') ";
		} */

		$sql .=	" GROUP BY DD.ORDER_NUM, DD.CONTAINER_ID, SEAL ORDER BY DD.ORDER_NUM, DD.CONTAINER_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<hr><font color="#00BB00">No data matches the search criteria.</font>
<?
		} else {
			$row_counter = 0;
			$total_rolls = 0;
			$total_weight = 0;
			$output = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td><b>No.</b></td>
								<td><b>Container#</b></td>
								<td><b>DT Load#</b></td>
								<td><b>Seal#</b></td>
								<td><b>Cont. Type</b></td>
								<td><b>Weight</b></td>
								<td><b>Weight UOM</b></td>
								<td><b>Rolls</b></td>
								<td><b>UNIT UOM</b></td>
							</tr>";
			do {
				$row_counter++;

				$output .= "<tr>
								<td>".$row_counter."</td>
								<td>".$row['CONTAINER_ID']."</td>
								<td>".$row['ORDER_NUM']."</td>
								<td>".$row['SEAL']."</td>
								<td>&nbsp;</td>
								<td>".round($row['THE_WEIGHT'])."</td>
								<td>KG</td>
								<td>".$row['THE_SUM']."</td>
								<td>ROLL</td>
							</tr>";
				$total_rolls += $row['THE_SUM'];
				$total_weight += $row['THE_WEIGHT'];

			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		
			$output .= "<tr>
							<td>&nbsp;</td>
							<td><b>Totals</b></td>
							<td colspan=\"3\">&nbsp;</td>
							<td>".round($total_weight)."</td>
							<td>&nbsp;</td>
							<td>".$total_rolls."</td>
							<td>&nbsp;</td>
						</tr>
					</table>";
			$filename = "tempXLS/DTvsl".$vessel."-".date('mdYhis').".xls";
			$fp = fopen($filename, "w");
			if(!$fp){
				echo "can not open file for writing, please contact the PoW IT department";
				exit;
			}
			fwrite($fp, $output);
			fclose($fp);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="3" face="Verdana"><b><a href="<? echo $filename; ?>">Right-Click and choose "Save Target As..." for the Excel version.</a></b></font></td>
	</tr>
</table>

<?
			echo $output;		
		}
	}
?>