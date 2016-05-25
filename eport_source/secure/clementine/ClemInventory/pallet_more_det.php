<?
/*
*	Adam Walter, October 2007.
*
*	This page is an auxilary function to page pallet_display.php.
*	Why I have to splice this out to it's own page is... well, not my call.
*******************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
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
	$count = $HTTP_GET_VARS['count'];
	$exporter_code = $HTTP_GET_VARS['exporter_code'];

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
		<td colspan="7" align="center"><font size="4" face="Verdana" color="#0000C0"><? echo $page_header; ?> Inventory at Port of Wilmington:&nbsp;&nbsp;&nbsp;<? echo $row['THE_VESSEL']; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Status</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Location</b></font></td>
	</tr>
<?	
	$sql = "SELECT WAREHOUSE_LOCATION, TO_NUMBER(CARGO_SIZE) THE_SIZE, EXPORTER_CODE, TO_NUMBER(BATCH_ID) THE_BATCH, PALLET_ID, QTY_RECEIVED, DECODE(CARGO_STATUS, 'H', 'HOSPITAL', 'R', 'REGRADE', 'B', 'BOTH', 'GOOD') THE_STATUS FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE = '".$comm."' ".$extra_sql." AND (MARK != 'SHIPPED' OR MARK IS NULL) AND RECEIVER_ID = '".$cust."'".$sub_sql." AND DATE_RECEIVED IS NOT NULL";
		if($count != "ALL"){
			$sql .= " AND TO_NUMBER(BATCH_ID) = '".$count."'";
		}
		if($exporter_code != "ALL"){
			$sql .= " AND EXPORTER_CODE = '".$exporter_code."'";
		}
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
		$sql .= " ORDER BY THE_STATUS, TO_NUMBER(CARGO_SIZE), TO_NUMBER(BATCH_ID), PALLET_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['THE_STATUS']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['THE_SIZE']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['EXPORTER_CODE']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['THE_BATCH']; ?></font></td>
<?
			$sql = "SELECT * FROM DC_INSPECTED_PALLET WHERE PALLET_ID = '".$Short_Term_row['PALLET_ID']."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			if(!ora_fetch_into($cursor, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['PALLET_ID']; ?></font></td>
<?			
			} else {
?>
		<td align="center"><font size="2" face="Verdana"><b><a href="pallet_status_history.php?vessel=<? echo $vessel; ?>&pallet_id=<? echo $Short_Term_row['PALLET_ID']; ?>"><? echo $Short_Term_row['PALLET_ID']; ?></a></b></font></td>
<?
			}
?>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['QTY_RECEIVED']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo "&nbsp;".$Short_Term_row['WAREHOUSE_LOCATION']; ?></font></td>
	</tr>
<?
		}
?>
</table>