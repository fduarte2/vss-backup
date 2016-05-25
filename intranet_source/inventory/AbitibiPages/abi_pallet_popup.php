<?
/*
*
*		Adam Walter, Oct 2008
*
*		A short page to return some pallet values.
*		This page is called by a "target" link, and therefore has
*		No header, footer, or login-security.  Plain text display
**************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);
	$func_cursor = ora_open($conn);

	$step = $HTTP_POST_VARS['step'];
	$container = $HTTP_POST_VARS['container'];
	$booking = $HTTP_POST_VARS['booking'];
	$used_list = $HTTP_POST_VARS['used_list'];

	$used_list .= $container.",";

	if($step == "new-booking"){
		$used_list = "nnn,";
	}
?>

<? 
	if($booking == ""){
?>
<script type="text/javascript">
window.resizeTo(350, 300);
</script>
<?
	}
?>

<html>
<body>
<table width="300">
<form name="book_form" action="abi_pallet_popup.php" method="post">
<input type="hidden" name="step" value="new-booking">
	<tr>
		<td colspan="3" align="center"><select name="booking" onchange="document.book_form.submit(this.form)">
								<option value="">Select a Booking#</option>
<?
	$sql = "SELECT SUBSTR(CT.CARGO_DESCRIPTION, 0, INSTR(CT.CARGO_DESCRIPTION, ' ') - 1) THE_BOOK, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_DATE FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.RECEIVER_ID = '312' AND CT.COMMODITY_CODE = '1299' AND CT.DATE_RECEIVED IS NOT NULL AND CT.BATCH_ID NOT LIKE '-%' AND CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION LIKE 'DMG%' AND DATE_OF_ACTIVITY >= (SYSDATE - 61) GROUP BY SUBSTR(CT.CARGO_DESCRIPTION, 0, INSTR(CT.CARGO_DESCRIPTION, ' ') - 1) ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY') DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['THE_BOOK']; ?>" <? if($row['THE_BOOK'] == $booking){ ?> selected <? } ?>><? echo $row['THE_BOOK']." (latest outgoing: ".$row['THE_DATE'].")"; ?></option>
<?
	}
?>						</select></td>
	</tr>
</form>
<tr>
	<td colspan="3">&nbsp;<hr>&nbsp;</td>
</tr>
<?
	if($booking != ""){
?>
<form name="the_results" action="abi_pallet_popup.php" method="post">
<input type="hidden" name="used_list" value="<? echo $used_list; ?>">
<input type="hidden" name="booking" value="<? echo $booking; ?>">
	<tr>
		<td colspan="3" align="center"><select name="container">
								<option value="">Select a Container#</option>
<?
	$sql = "SELECT DISTINCT ORDER_NUM FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND PALLET_ID IN (SELECT DISTINCT PALLET_ID FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION LIKE '".$booking."%') ORDER BY ORDER_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if(!array_search($row['ORDER_NUM'], split(",", $used_list))){
?>
							<option value="<? echo $row['ORDER_NUM']; ?>"><? echo $row['ORDER_NUM']; ?></option>
<?
		}
	}
?>
					</select></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="step" value="continue"></td>
	</tr>
</form>
<?
	}
	if($container != "" && $booking != ""){
		$dmg_break = FALSE;
?>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana"><b><? echo $container; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana">Pallet</font></td>
	</tr>
<?
		$sql = "SELECT CT.PALLET_ID, CT.WEIGHT, DECODE(QTY_DAMAGED, '0', 'N', 'Y') THE_DMG FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION LIKE 'DMG%' AND CA.ORDER_NUM = '".$container."' AND CT.CARGO_DESCRIPTION LIKE '".$booking."%' ORDER BY PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor, $sql);
//		echo $sql;
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="3"><br><br><br></td>
	</tr>
<?
	}
?>
</table>
</body>
</html>
