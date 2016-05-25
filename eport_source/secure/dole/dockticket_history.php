<?
	include("useful_info.php");
	$main_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$dock_ticket = $HTTP_POST_VARS['dock_ticket'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="dockticket_history.php" method="post">
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana"><b>Dock Ticket History</b></font></td>
	</tr>
	<tr>
		<td width="15%" align="left">Dock Ticket #:</td>
		<td><input name="dock_ticket" type="text" size="15" maxlength="15" value="<? echo $dock_ticket; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>

<?
	if($dock_ticket != ""){
		$sql = "SELECT RECEIVER_ID, BOL, CARGO_DESCRIPTION, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_DATE, ARRIVAL_NUM, SUM(QTY_RECEIVED) THE_REC, SUM(QTY_IN_HOUSE) THE_REMAIN FROM CARGO_TRACKING WHERE BOL = '".$dock_ticket."' AND DATE_RECEIVED IS NOT NULL GROUP BY RECEIVER_ID, BOL, CARGO_DESCRIPTION, ARRIVAL_NUM";
		ora_parse($main_cursor, $sql);
		ora_exec($main_cursor);
		if(!ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<hr><font color=\"#ff0000\">No Rolls yet received from this Dock Ticket</font>";
		} else {
			$sum_rec = $row['THE_REC'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr><td><b>Customer:  <? echo $row['RECEIVER_ID']; ?></b> </td></tr>
	<tr><td><b>Dock Ticket:  <? echo $row['BOL']; ?></b> </td></tr>
	<tr><td><b>Mark:  <? echo $row['CARGO_DESCRIPTION']; ?></b> </td></tr>
	<tr><td><b>Received:  <? echo $row['THE_DATE']; ?></b> </td></tr>
	<tr><td><b>QTY Received:  <? echo $row['THE_REC']; ?></b> </td></tr>
	<tr><td><b>Arrival#:  <? echo $row['ARRIVAL_NUM']; ?></b> </td></tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><b>Date</b></td>
		<td><b>Order</b></td>
		<td><b>Container</b></td>
		<td><b>Arrival#</b></td>
		<td><b>QTY</b></td>
		<td><b>Weight</b></td>
	</tr>

<?
			$total_out = 0;

			$sql = "SELECT MIN(TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY')) THE_DATE, CA.ORDER_NUM, SUM(CA.QTY_CHANGE) THE_SUM, CA.ARRIVAL_NUM, DP.CONTAINER_ID, SUM(CT.WEIGHT) THE_WEIGHT, CT.WEIGHT_UNIT FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, DOLEPAPER_ORDER DP WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.BOL = CA.BATCH_ID AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CA.ORDER_NUM = DP.ORDER_NUM AND CA.BATCH_ID = '".$dock_ticket."' AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL GROUP BY CA.ORDER_NUM, CA.ARRIVAL_NUM, DP.CONTAINER_ID, CT.WEIGHT_UNIT ORDER BY CA.ORDER_NUM";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			while(ora_fetch_into($short_term_data_cursor, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$total_out += $row2['THE_SUM'];
?>
	<tr>
		<td><b><? echo $row2['THE_DATE']; ?></b> </td>
		<td><b><? echo $row2['ORDER_NUM']; ?></b> </td>
		<td><b><? echo $row2['CONTAINER_ID']; ?></b>&nbsp;</td>
		<td><b><? echo $row2['ARRIVAL_NUM']; ?></b> </td>
		<td><b><? echo $row2['THE_SUM']; ?></b> </td>
		<td><b><? echo $row2['THE_WEIGHT']." ".$row2['WEIGHT_UNIT']; ?></b> </td>
	</tr>
<?
			}
?>
</table>
<br>
<br>
<?
			$sql = "SELECT SUM(QTY_RECEIVED) THE_REC FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NULL AND BOL = '".$dock_ticket."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$not_rec = $row3['THE_REC'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr><td><b>Total Withdrawn:  <? echo $total_out; ?></b> </td></tr>
	<tr><td><b>Total In-House:  <? echo ($sum_rec - $total_out); ?></b> </td></tr>
<?
			if($not_rec > 0){
?>
	<tr><td><b>***NOTE***:  <? echo $not_rec; ?> Rolls as yet unreceived.</b> </td></tr>
<?
			}
?>
</table>
<?
		}
	}
?>