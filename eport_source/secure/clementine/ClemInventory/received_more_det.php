<?
/*
*	Adam Walter, October 2007.
*
*	More info for Received pallets
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
	$pallet_count = 0;
	$received_cases = 0;
	$damaged_cases = 0;

	$sql = "SELECT DC_CARGO_DESC THE_SIZE, PALLET_ID, EXPORTER_CODE, BATCH_ID, to_char(DATE_RECEIVED, 'MM/DD/YYYY HH:MI AM') THE_DATE, 
				NVL(QTY_DAMAGED, 0) THE_DAMAGE, QTY_RECEIVED, DECODE(CARGO_STATUS, 'H', 'HOSPITAL', 'R', 'REGRADE', 'B', 'BOTH', ' ') THE_STATUS 
			FROM DC_CARGO_TRACKING 
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND COMMODITY_CODE = '".$comm."' 
				AND RECEIVER_ID = '".$cust."'".$sub_sql;
	if($count != "ALL"){
		$sql .= " AND TO_NUMBER(BATCH_ID) = '".$count."'";
	}
	if($exporter_code != "ALL"){
		$sql .= " AND EXPORTER_CODE = '".$exporter_code."'";
	}
	if($size != "ALL"){
		$sql .= " AND DC_CARGO_DESC = '".$size."'";
	}
	$sql .= " AND DATE_RECEIVED IS NOT NULL ORDER BY TO_NUMBER(CARGO_SIZE), EXPORTER_CODE, BATCH_ID, PALLET_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "<tr><td align=\"center\"><font size=\"3\" face=\"Verdana\" color=\"#FF0000\">No pallets matching this criteria.</font></td></tr>";
	} else {
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Scan Time</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Received Cartons</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Damaged Cartons</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Hosp/Regrade</b></font></td>
	</tr>
<?
		do {
			$pallet_count++;
			$received_cases += $Short_Term_row['QTY_RECEIVED'];
			$damaged_cases += $Short_Term_row['QTY_DAMAGED'];
?>
	<tr>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_SIZE']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['EXPORTER_CODE']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['BATCH_ID']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['PALLET_ID']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_DATE']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['QTY_RECEIVED']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo 0 + $Short_Term_row['THE_DAMAGE']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_STATUS']."&nbsp;"; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="8"><font size="2" face="Verdana"><b>Totals:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Pallets:  ".$pallet_count; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Received Cartons:  ".number_format($received_cases); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Damaged Cartons:  ".number_format($damaged_cases); ?></b></font></td>
	</tr>
<?
	}
?>
</table>