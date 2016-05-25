<?
/*
*
*		Adam Walter, Apr 2010
*
*		A short page to return some pallet values, for Walmart.
*		This page is called by a "target" link, Plain text display.
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

	$PO = $HTTP_GET_VARS['PO'];

 ?>
 <table border="1" width="400" cellpadding="4" cellspacing="0">
 <?
	if($PO == ""){
?>
	<tr><td align="center">No PO selected.</td></tr>
<?
	} else {
?>
<script type="text/javascript">
window.resizeTo(500, 700);
</script>

	<tr><td colspan="6" align="center"><font size="3" face="Verdana"><b>Pallets currently on <? echo $PO; ?></b></font></td></tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Case Count</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Supplier</b></font></td>
	</tr>
<?
		$sql = "SELECT GROWER_PALLET_ID, DECODE(VP.VESSEL_NAME, NULL, WDI_ARRIVAL_NUM, VP.VESSEL_NAME) THE_VES,
					WDI_LABEL_DESC, WDI_VARIETY_DESC, WDI_CASES_QTY, WDI_SUPPLIER
				FROM WDI_ADDITIONAL_DATA WAD, VESSEL_PROFILE VP
				WHERE WAD.WDI_ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
				AND WAD.WDI_RECEIVING_PO = '".$PO."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr><td colspan="6"><font size="2" face="Verdana"><b>No Pallets currently on <? echo $PO; ?></b></font></td></tr>
<?
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['GROWER_PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_VES']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WDI_VARIETY_DESC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WDI_CASES_QTY']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WDI_LABEL_DESC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WDI_SUPPLIER']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>
</table>