<?
/* Adam Walter, 8/25/07
*	This file simply displays on Eport a grid of inventory,
*	Packing houses across the top of the grid,
*	Sizes down the left.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);

	$packing_house = array();
	$counter_PKG = 0;
	$sizes = array();
	$counter_size = 0;
	$column_total = array();
	$row_total = 0;

	// ths is not my most elegant code, but as I have to have this ready in less than 1 hour, and runtime is apparently irrelevant...
	$sql = "SELECT DISTINCT EXPORTER_CODE FROM CARGO_TRACKING WHERE LENGTH(EXPORTER_CODE) = 4 AND LENGTH(PALLET_ID) = 30 AND COMMODITY_CODE LIKE '560%' AND DATE_RECEIVED IS NOT NULL AND (MARK IS NULL OR MARK != 'SHIPPED') ORDER BY EXPORTER_CODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$packing_house[$counter_PKG] = $row['EXPORTER_CODE'];
		$counter_PKG++;
	}

	$sql = "SELECT DISTINCT CARGO_SIZE FROM CARGO_TRACKING WHERE LENGTH(EXPORTER_CODE) = 4 AND LENGTH(PALLET_ID) = 30 AND COMMODITY_CODE LIKE '560%' AND DATE_RECEIVED IS NOT NULL AND (MARK IS NULL OR MARK != 'SHIPPED') ORDER BY CARGO_SIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sizes[$counter_size] = $row['CARGO_SIZE'];
		$counter_size++;
	}

?>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Clementine On-Port Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<table border="1" width="65%" cellpadding="4" cellspacing="0">

	<!-- below is current inventory !-->

	<tr>
		<td align="center" colspan="<? echo $counter_PKG + 2; ?>"><font size="3" face="Verdana">Actual Inventory <u>NOW</u> (<? echo date('m/d/Y h:m a'); ?>)</font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
<?
	$column_total = array();
	for($temp = 0; $temp < $counter_PKG; $temp++){
?>
		<td><font size="2" face="Verdana"><b><? echo $packing_house[$temp]; ?></b></font></td>
<?
	}
?>
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
	</tr>
<?
	for($temp1 = 0; $temp1 < $counter_size; $temp1++){
		$row_total = 0;
?>
	<tr>
		<td><font size="2" face="Verdana"><b><? echo $sizes[$temp1]; ?></b></font></td>
<?
		for($temp2 = 0; $temp2 < $counter_PKG; $temp2++){
			$sql = "SELECT 'AAA', NVL(COUNT(*), 0) THE_COUNT FROM CARGO_TRACKING WHERE CARGO_SIZE = '".$sizes[$temp1]."' AND EXPORTER_CODE = '".$packing_house[$temp2]."' AND LENGTH(EXPORTER_CODE) = 4 AND LENGTH(PALLET_ID) = 30 AND COMMODITY_CODE LIKE '560%' AND DATE_RECEIVED IS NOT NULL AND (MARK IS NULL OR MARK != 'SHIPPED')";
			ora_parse($Short_Term_Data, $sql);
			ora_exec($Short_Term_Data);
			ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$row_total += $row['THE_COUNT'];
			$column_total[$temp2] += $row['THE_COUNT'];
?>
		<td><font size="2" face="Verdana"><? echo $row['THE_COUNT']; ?></font></td>
<?
		}
		$column_total[$counter_PKG] += $row_total;
?>
		<td><font size="2" face="Verdana"><b><? echo $row_total; ?></b></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
<?
	for($temp3 = 0; $temp3 <= $counter_PKG; $temp3++){
?>
		<td><font size="2" face="Verdana"><b><? echo $column_total[$temp3]; ?></b></font></td>
<?
	}
?>
	</tr>
	<tr>
		<td colspan="<? echo $counter_PKG + 2; ?>">&nbsp;</td>
	</tr>

