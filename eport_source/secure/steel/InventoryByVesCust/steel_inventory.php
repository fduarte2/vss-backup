<?
/* Adam Walter, 11/19/07
*
*	Steel Eport page for Inventory
***************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$comm = $HTTP_POST_VARS['comm'];
	$stat = $HTTP_POST_VARS['stat'];

	$current_cust = "";
	$current_code = "";
	$current_code_total = 0;

	$overall_total_pallets_received = 0;
	$overall_total_QTY_received = 0;
	$overall_total_pallets_not_received = 0;
	$overall_total_QTY_not_received = 0;

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Steel Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<?
	if($vessel == "" || $comm == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="index.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM IN (SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING WHERE COMMODITY_CODE IN ('3302', '3304', '3326')) ORDER BY LR_NUM DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"><? echo $row['THE_VESSEL'] ?></option>
<?
		}
?>
					</select></font></td>
		<td align="center"><font size="2" face="Verdana">Status:  <select name="stat">
														<option value="all">All</option>
														<option value="scan">Scanned In</option>
														<option value="notscan">Not Scanned In</option>
												<select></font></td>
		<td align="right"><font size="2" face="Verdana">Commodity:  <select name="comm">
														<option value="">Please Select a commodity:</option>
														<option value="3302">3302-Charter Hot Rolled Coils</option>
														<option value="3304">3304-Charter Cold Rolled Coils</option>
														<option value="3326">3326-Charter Steel Plates</option>
												<select></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
</form>
</table>
<?
	} else {
?>
<table border="1" width="100%" cellpadding="3" cellspacing="0">
	<tr>
<?
		$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<td colspan="6" align="center"><font size="3" face="Verdana"><b><? echo $row['THE_NAME']; ?></b></font></td>
	</tr>
<?
		$sql = "SELECT RECEIVER_ID, PALLET_ID, WAREHOUSE_LOCATION, CARGO_DESCRIPTION, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_DATE,						QTY_RECEIVED, WEIGHT 
				FROM CARGO_TRACKING
				WHERE COMMODITY_CODE = '".$comm."'
				AND ARRIVAL_NUM = '".$vessel."'";
		if($stat == "scan"){
			$sql .= " AND DATE_RECEIVED IS NOT NULL";
		} elseif($stat == "notscan"){
			$sql .= " AND DATE_RECEIVED IS NULL";
		}
		$sql .= " ORDER BY RECEIVER_ID, WAREHOUSE_LOCATION, PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#C00000">No pallets found in RF database matching these criteria.</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td align="right"><font size="3" face="Verdana">Pallet ID</font></td>
		<td align="right"><font size="3" face="Verdana">Code</font></td>
		<td align="right"><font size="3" face="Verdana">Description</font></td>
		<td align="right"><font size="3" face="Verdana">Date Received</font></td>
		<td align="right"><font size="3" face="Verdana">QTY</font></td>
		<td align="right"><font size="3" face="Verdana">Lbs</font></td>
	</tr>
<?
			$current_code = $row['WAREHOUSE_LOCATION'];
			do {
				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#F0F0F0";
				} else {
					$bgcolor = "#FFFFFF";
				}
				
				if($current_cust != $row['RECEIVER_ID'] || $current_code != $row['WAREHOUSE_LOCATION']){

					if($current_code_total != 0){
?>
	<tr bgcolor="#33CC00">
		<td align="right"><font size="2" face="Verdana"><b>Sub Total:</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $current_code; ?></b></font></td>
		<td colspan="2"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $current_code_total; ?></b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
<?
						$current_code = $row['WAREHOUSE_LOCATION'];
						$current_code_total = 0;
					}

					if($current_cust != $row['RECEIVER_ID']){
						$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$row['RECEIVER_ID']."'";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="6" bgcolor="#6699FF"><font size="2" face="Verdana"><b><? echo $short_term_row['CUSTOMER_NAME']; ?></b></font></td>
	</tr>
<?
						$current_cust = $short_term_row['CUSTOMER_ID'];
					}
				}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td align="right"><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td align="right"><font size="2" face="Verdana">&nbsp;<? echo $row['WAREHOUSE_LOCATION']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo $row['CARGO_DESCRIPTION']; ?></font></td>
		<td align="right"><font size="2" face="Verdana">&nbsp;<? echo $row['THE_DATE']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo $row['WEIGHT']; ?></font></td>
	</tr>
<?
				$current_code_total += $row['QTY_RECEIVED'];

				if($row['THE_DATE'] != ""){
					$overall_total_pallets_received++;
					$overall_total_QTY_received += $row['QTY_RECEIVED'];
				} else {
					$overall_total_pallets_not_received++;
					$overall_total_QTY_not_received += $row['QTY_RECEIVED'];
				}
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr bgcolor="#33CC00">
		<td align="right"><font size="2" face="Verdana"><b>Sub Total:</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $current_code; ?></b></font></td>
		<td colspan="2"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $current_code_total; ?></b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
<?
			if($stat != "notscan"){
?>
	<tr bgcolor="#CC6600">
		<td align="right" colspan="2"><font size="2" face="Verdana"><b>Received Totals:</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $overall_total_pallets_received; ?> Pallets</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $overall_total_QTY_received; ?> QTY</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
<?
			}
			if($stat != "scan"){
?>
	<tr bgcolor="#CC0066">
		<td align="right" colspan="2"><font size="2" face="Verdana"><b>Non-Received Totals:</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $overall_total_pallets_not_received; ?> Pallets</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b><? echo $overall_total_QTY_not_received; ?> QTY</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>&nbsp;</b></font></td>
	</tr>
<?
			}
?>
</table>
<?
		}
	}
?>