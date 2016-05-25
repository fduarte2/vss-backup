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
	$Short_Term_Cursor = ora_open($conn2);

	$vessel = $HTTP_POST_VARS['vessel'];
	$raw_cust = $HTTP_POST_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}
	$comm = $HTTP_POST_VARS['comm'];

	$packing_house = array();
	$counter_PKG = 0;
	$sizes = array();
	$counter_size = 0;
	$column_total = array();
	$row_total = 0;
	// $eport_customer_id comes from index.php
	if($eport_customer_id == 0){
		$extra_sql = "";
	} else {
		$extra_sql = "AND CUSTOMER_ID = '".$eport_customer_id."'";
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Clementine On-Port Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="index.php" method="post">
	<tr>
		<td align="center">
		<table border="0" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td width="33%" align="left"><select name="vessel"><option value="">Please Select Vessel:
<?
	$sql = "SELECT DISTINCT
				   VP.ARRIVAL_NUM THE_LR,
				   LR_NUM || '-' || VESSEL_NAME THE_VESSEL
			FROM VESSEL_PROFILE VP
			INNER JOIN CARGO_TRACKING CT
				  ON CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
			WHERE
				VP.SHIP_PREFIX = 'CLEMENTINES'
			ORDER BY VP.ARRIVAL_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['THE_LR']; ?>" <? if($vessel == $row['THE_LR']){ ?> selected <? } ?>><? echo $row['THE_VESSEL']; ?></option>
<?
	}
?>				
				</select></td>
				<td width="33%" align="center"><select name="cust">
<?
	$sql = "SELECT *
			FROM CUSTOMER_PROFILE
			WHERE
				CUSTOMER_ID IN (
					SELECT CUSTOMER_ID
					FROM EPORT_LOGIN
					WHERE
						USER_TYPE = 'CLEMENTINE'
						AND CUSTOMER_ID != 0
					)
			".$extra_sql."
			ORDER BY CUSTOMER_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// we gotta do something strange for oppy-439, so...
		if($Short_Term_row['CUSTOMER_ID'] != '439'){
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?></option>
<?
		} else {
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($raw_cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - NO OPPY</option>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>O" <? if($raw_cust == "439O"){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - OPPY ONLY</option>
<?
		}
	} 
?>
				</select></td>
				<td width="33%" align="right">Commodity:  <select name="comm">
<?
	$sql = "SELECT
				COMMODITY_CODE,
				COMMODITY_NAME
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_TYPE = 'CLEMENTINES'
			ORDER BY COMMODITY_CODE";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['COMMODITY_CODE']; ?>" <? if($comm == $Short_Term_row['COMMODITY_CODE']){ ?> selected <? } ?>><? echo $Short_Term_row['COMMODITY_CODE']." - ".$Short_Term_row['COMMODITY_NAME']; ?></option>
<?
	}
?>
			</select></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td align="center"><input name="submit" type="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td>&nbsp;<HR></td>
	</tr>
</form>
</table>

<?
	if ($vessel != "" && $cust != ""){
		$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$print_vessel = $row['THE_VESSEL'];

		// ths is not my most elegant code, but as I have to have this ready in less than 1 hour, and runtime is apparently irrelevant...
		$sql = "SELECT DISTINCT EXPORTER_CODE FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND COMMODITY_CODE = '".$comm."' AND DATE_RECEIVED IS NOT NULL ORDER BY EXPORTER_CODE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$packing_house[$counter_PKG] = $row['EXPORTER_CODE'];
			$counter_PKG++;
		}

		$sql = "SELECT DISTINCT DC_CARGO_DESC FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND COMMODITY_CODE = '".$comm."' AND DATE_RECEIVED IS NOT NULL ORDER BY DC_CARGO_DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sizes[$counter_size] = $row['DC_CARGO_DESC'];
			$counter_size++;
		}

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">


	<tr>
		<td align="center" colspan="<? echo $counter_PKG + 2; ?>"><font size="3" face="Verdana">Actual Inventory <u>NOW</u> (<? echo date('m/d/Y h:m a'); ?>) For Vessel <? echo $print_vessel; ?> --- Commodity <? echo $comm; ?></font></td>
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
				$sql = "SELECT 'AAA', NVL(COUNT(*), 0) THE_COUNT FROM DC_CARGO_TRACKING WHERE DC_CARGO_DESC = '".$sizes[$temp1]."' AND EXPORTER_CODE = '".$packing_house[$temp2]."' AND ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND COMMODITY_CODE = '".$comm."' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' AND (MARK IS NULL OR MARK != 'SHIPPED')";
/*				if($burnac == "burnac"){
					$sql .= " AND UPPER(REMARK) = 'BURNAC'";
				} elseif($burnac == "noburnac"){
					$sql .= " AND REMARK IS NULL";
				} */
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$row_total += $row['THE_COUNT'];
				$column_total[$temp2] += $row['THE_COUNT'];
?>
		<td><font size="2" face="Verdana"><? echo (0 + $row['THE_COUNT']); ?></font></td>
<?
			}
			$column_total[$counter_PKG] += $row_total;
?>
		<td><font size="2" face="Verdana"><b><? echo (0 + $row_total); ?></b></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
<?
		for($temp3 = 0; $temp3 <= $counter_PKG; $temp3++){
?>
		<td><font size="2" face="Verdana"><b><? echo (0 +$column_total[$temp3]); ?></b></font></td>
<?
		}
?>
	</tr>
	<tr>
		<td colspan="<? echo $counter_PKG + 2; ?>">&nbsp;</td>
	</tr>
</table>
<BR>
<table border="1" width="100%" cellpadding="4" cellspacing="0">


	<tr>
		<td align="center" colspan="<? echo $counter_PKG + 2; ?>"><font size="3" face="Verdana">Actual <b>GOOD</b> Inventory <u>NOW</u> (<? echo date('m/d/Y h:m a'); ?>) For Vessel <? echo $print_vessel; ?> --- Commodity <? echo $comm; ?></font></td>
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
				$sql = "SELECT 'AAA', NVL(COUNT(*), 0) THE_COUNT FROM DC_CARGO_TRACKING WHERE DC_CARGO_DESC = '".$sizes[$temp1]."' AND EXPORTER_CODE = '".$packing_house[$temp2]."' AND ARRIVAL_NUM = '".$vessel."' AND DATE_RECEIVED IS NOT NULL AND RECEIVER_ID = '".$cust."'".$sub_sql." AND COMMODITY_CODE = '".$comm."' AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS IS NULL";
/*				if($burnac == "burnac"){
					$sql .= " AND UPPER(REMARK) = 'BURNAC'";
				} elseif($burnac == "noburnac"){
					$sql .= " AND REMARK IS NULL";
				} */
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$row_total += $row['THE_COUNT'];
				$column_total[$temp2] += $row['THE_COUNT'];
?>
		<td><font size="2" face="Verdana"><? echo (0 + $row['THE_COUNT']); ?></font></td>
<?
			}
			$column_total[$counter_PKG] += $row_total;
?>
		<td><font size="2" face="Verdana"><b><? echo (0 + $row_total); ?></b></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
<?
		for($temp3 = 0; $temp3 <= $counter_PKG; $temp3++){
?>
		<td><font size="2" face="Verdana"><b><? echo (0 + $column_total[$temp3]); ?></b></font></td>
<?
		}
?>
	</tr>
	<tr>
		<td colspan="<? echo $counter_PKG + 2; ?>">&nbsp;</td>
	</tr>
<?
	}
?>
</table>