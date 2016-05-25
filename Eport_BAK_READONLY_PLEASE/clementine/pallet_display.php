<?
/*
*	Adam Walter, October 2007.
*
*	This page is linked to from the "main.php" part of the ClemInventory folder.
*	It displays a list of pallet ids, based on which link was clicked.
*******************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$type = $HTTP_GET_VARS['type'];
	$comm = $HTTP_GET_VARS['comm'];
	$vessel = $HTTP_GET_VARS['vessel'];
	$size = $HTTP_GET_VARS['size'];
	$raw_cust = $HTTP_GET_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}
	$damage_status = $HTTP_GET_VARS['damage_status'];

	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

	if($eport_customer_id == 0){
		$extra_sql = "";
	} else {
		$extra_sql = "";
//		$extra_sql = "AND RECEIVER_ID = '".$eport_customer_id."'";
	}

?>
<table border="1" width="100%" cellpadding="2" cellspacing="0"> 
<?	
	// output for clicking on a "Port Received" link
	if($type == "RECEIVED"){
		$sql = "SELECT LR_NUM || ' - ' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="5" align="center"><font size="4" face="Verdana" color="#0000C0">Inventory Received By Port of Wilmington:&nbsp;&nbsp;&nbsp;<? echo $row['THE_VESSEL']; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
	</tr>
<?
		$sql = "SELECT DC_CARGO_DESC THE_SIZE, EXPORTER_CODE, TO_NUMBER(BATCH_ID) THE_BATCH, COUNT(*) THE_PALLETS, SUM(QTY_RECEIVED) THE_RECEIVED 
				FROM DC_CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE = '".$comm."' AND RECEIVER_ID = '".$cust."'".$sub_sql."";
		if($size != "ALL"){
			$sql .= " AND DC_CARGO_DESC = '".$size."'";
		}
		$sql .= " AND DATE_RECEIVED IS NOT NULL 
				GROUP BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(BATCH_ID) 
				ORDER BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(BATCH_ID)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_SIZE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['EXPORTER_CODE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_BATCH']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><a href="received_more_det.php?cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $Short_Term_row['THE_SIZE']; ?>&exporter_code=<? echo $Short_Term_row['EXPORTER_CODE']; ?>&count=<? echo $Short_Term_row['THE_BATCH']; ?>"><? echo $Short_Term_row['THE_PALLETS']; ?></a></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_RECEIVED']; ?></b></font></td>
	</tr>
<?
		}
	}

	// output for shipped-out pallets.  More confusing than shipped in ;p
	if($type == "SHIPPED"){
//		echo $size;
		$sql = "SELECT LR_NUM || ' - ' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="5" align="center"><font size="4" face="Verdana" color="#0000C0">Shipped Out Cargo For:&nbsp;&nbsp;&nbsp;<? echo $row['THE_VESSEL']; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
	</tr>
<?
		$sql = "SELECT DC_CARGO_DESC THE_SIZE, EXPORTER_CODE, TO_NUMBER(CT.BATCH_ID) THE_BATCH, COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(QTY_CHANGE) THE_SHIPPED 
				FROM DC_CARGO_TRACKING CT, CARGO_ACTIVITY CA 
				WHERE CT.PALLET_ID = CA.PALLET_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM  
				AND SERVICE_CODE = '6' 
				AND ACTIVITY_DESCRIPTION IS NULL 
				AND CT.ARRIVAL_NUM = '".$vessel."' 
				AND COMMODITY_CODE = '".$comm."' 
				AND RECEIVER_ID = '".$cust."'".$sub_sql."";
		if($size != "ALL"){
			$sql .= " AND DC_CARGO_DESC = '".$size."'";
		}
		$sql .= " AND DATE_RECEIVED IS NOT NULL 
				GROUP BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(CT.BATCH_ID) 
				ORDER BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(CT.BATCH_ID)";
//		echo $sql;
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_SIZE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['EXPORTER_CODE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_BATCH']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><a href="shipped_more_det.php?cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $Short_Term_row['THE_SIZE']; ?>&exporter_code=<? echo $Short_Term_row['EXPORTER_CODE']; ?>&count=<? echo $Short_Term_row['THE_BATCH']; ?>"><? echo $Short_Term_row['THE_PALLETS']; ?></a></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_SHIPPED']; ?></b></font></td>
	</tr>
<?
		}
	}

	// output for clicking on a "Remaining" link (good, hospital, regrade, both, or total)
	if($type == "REMAIN"){
		$pallet_count = 0;
		$remain_cases = 0;

		$sql = "SELECT DC_CARGO_DESC THE_SIZE, EXPORTER_CODE, TO_NUMBER(BATCH_ID) THE_BATCH, COUNT(*) THE_PALLETS, SUM(QTY_RECEIVED) THE_RECEIVED FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE = '".$comm."' ".$extra_sql." AND (MARK != 'SHIPPED' OR MARK IS NULL) AND RECEIVER_ID = '".$cust."'".$sub_sql." AND DATE_RECEIVED IS NOT NULL";
		if($size != "ALL"){
			$sql .= " AND DC_CARGO_DESC = '".$size."'";
		}
		if($damage_status == "all"){
			// do nothing
		} elseif($damage_status == "good"){
			$sql .= " AND CARGO_STATUS IS NULL";
		} else {
			$sql .= " AND CARGO_STATUS = '".$damage_status."'";
		}
		$sql .= " GROUP BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(BATCH_ID) ORDER BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(BATCH_ID)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<tr><td align=\"center\"><font size=\"3\" face=\"Verdana\" color=\"#FF0000\">No pallets matching this criteria.</font></td></tr>";
		} else {
			switch($damage_status){
				case "good":
					$page_header = "Good";
					break;
				case "R":
					$page_header = "Regrade";
					break;
				case "H":
					$page_header = "Hospital";
					break;
				case "B":
					$page_header = "Both Regrade AND Hospital";
					break;
				default:
					$page_header = "All";
					break;
			}
			$sql = "SELECT LR_NUM || ' - ' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="5" align="center"><font size="4" face="Verdana" color="#0000C0"><? echo $page_header; ?> Inventory at Port of Wilmington:&nbsp;&nbsp;&nbsp;<? echo $row['THE_VESSEL']; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
	</tr>
<?
			do {
				$pallet_count += $Short_Term_row['THE_PALLETS'];
				$remain_cases += $Short_Term_row['THE_RECEIVED'];
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_SIZE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['EXPORTER_CODE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_BATCH']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><a href="pallet_more_det.php?cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $Short_Term_row['THE_SIZE']; ?>&damage_status=<? echo $damage_status;?>&exporter_code=<? echo $Short_Term_row['EXPORTER_CODE']; ?>&count=<? echo $Short_Term_row['THE_BATCH']; ?>"><? echo $Short_Term_row['THE_PALLETS']; ?></a></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_RECEIVED']; ?></b></font></td>
	</tr>
<?
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5"><font size="2" face="Verdana"><b>Totals:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="pallet_more_det.php?cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $size; ?>&damage_status=<? echo $damage_status;?>&exporter_code=ALL&count=ALL"><? echo "Pallets:  ".number_format($pallet_count); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Remaining Cartons:  ".number_format($remain_cases); ?></b></font></td>
	</tr>
<?
		}
	}

	if($type == "AWAITING"){
		$sql = "SELECT LR_NUM || ' - ' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="7" align="center"><font size="4" face="Verdana" color="#0000C0">Cargo Awaiting Discharge:&nbsp;&nbsp;&nbsp;<? echo $row['THE_VESSEL']; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
	</tr>
<?
		$sql = "SELECT DC_CARGO_DESC THE_SIZE, EXPORTER_CODE, TO_NUMBER(BATCH_ID) THE_BATCH, COUNT(*) THE_PALLETS, SUM(QTY_RECEIVED) THE_RECEIVED 
				FROM DC_CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE = '".$comm."' AND RECEIVER_ID = '".$cust."'".$sub_sql."";
		if($size != "ALL"){
			$sql .= " AND DC_CARGO_DESC = '".$size."'";
		}
		$sql .= "  AND MANIFESTED = 'Y' AND DATE_RECEIVED IS NULL 
				GROUP BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(BATCH_ID) 
				ORDER BY DC_CARGO_DESC, EXPORTER_CODE, TO_NUMBER(BATCH_ID)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_SIZE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['EXPORTER_CODE']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_BATCH']; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><a href="awaiting_more_det.php?cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&vessel=<? echo $vessel; ?>&size=<? echo $Short_Term_row['THE_SIZE']; ?>&exporter_code=<? echo $Short_Term_row['EXPORTER_CODE']; ?>&count=<? echo $Short_Term_row['THE_BATCH']; ?>"><? echo $Short_Term_row['THE_PALLETS']; ?></a></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo $Short_Term_row['THE_RECEIVED']; ?></b></font></td>
	</tr>
<?
		}
	}
?>
</table>