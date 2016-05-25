<?
/*
*	Adam Walter, October 2007.
*
*	More info for Awaiting-to-be-received pallets
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
	$total_pallets = 0;
	$total_cases = 0;

	$sql = "SELECT LR_NUM || ' - ' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="7" align="center"><font size="4" face="Verdana" color="#0000C0">Cargo Awaiting Discharge:&nbsp;&nbsp;&nbsp;<? echo $row['THE_VESSEL']; ?></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Cartons</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Location</b></font></td>
	</tr>
<?	
	$sql = "SELECT WAREHOUSE_LOCATION, DC_CARGO_DESC THE_SIZE, EXPORTER_CODE, TO_NUMBER(BATCH_ID) THE_BATCH, PALLET_ID, QTY_RECEIVED FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE = '".$comm."' ".$extra_sql." AND RECEIVER_ID = '".$cust."'".$sub_sql." AND MANIFESTED = 'Y' AND DATE_RECEIVED IS NULL";
	if($count != "ALL"){
		$sql .= " AND TO_NUMBER(BATCH_ID) = '".$count."'";
	}
	if($exporter_code != "ALL"){
		$sql .= " AND EXPORTER_CODE = '".$exporter_code."'";
	}
	if($size != "ALL"){
		$sql .= " AND DC_CARGO_DESC = '".$size."'";
	}
	$sql .= " ORDER BY DC_CARGO_DESC, TO_NUMBER(BATCH_ID), PALLET_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$total_pallets++;
		$total_cases += $Short_Term_row['QTY_RECEIVED'];
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['THE_SIZE']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['EXPORTER_CODE']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['THE_BATCH']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['PALLET_ID']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $Short_Term_row['QTY_RECEIVED']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo "&nbsp;".$Short_Term_row['WAREHOUSE_LOCATION']; ?></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="6"><font size="2" face="Verdana"><b>Totals:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pallets:  <? echo $total_pallets; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cases:  <? echo $total_cases; ?></font></td>
	</tr>
</table>