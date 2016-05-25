<?
/*
*	Adam Walter, October 2007.
*
*	More info for Shipped pallets
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
	$current_pallet = "none";
	$pallet_count = 0;
	$shipped_cases = 0;
	$damaged_cases = 0;

	$sql = "SELECT CT.PALLET_ID THE_PALLET, SUBSTR(CT.PALLET_ID, 10, 14) THE_MATCH, to_char(CT.DATE_RECEIVED, 'MM/DD/YYYY HH:MI AM') THE_DATE_IN, 
				to_char(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH:MI AM') THE_DATE_OUT, CT.BATCH_ID, EXPORTER_CODE, CA.ORDER_NUM THE_ORDER, QTY_CHANGE THE_SHIPPED, ACTIVITY_DESCRIPTION THE_DAMAGE, CARGO_SIZE, DC_CARGO_DESC 
			FROM DC_CARGO_TRACKING CT, CARGO_ACTIVITY CA 
			WHERE CT.PALLET_ID = CA.PALLET_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM ".$extra_sql." 
				AND SERVICE_CODE = '6' 
				AND ACTIVITY_DESCRIPTION IS NULL 
				AND CT.ARRIVAL_NUM = '".$vessel."' 
				AND COMMODITY_CODE = '".$comm."' 
				AND RECEIVER_ID = '".$cust."'".$sub_sql."";
	if($count != "ALL"){
		$sql .= " AND TO_NUMBER(CT.BATCH_ID) = '".$count."'";
	}
	if($exporter_code != "ALL"){
		$sql .= " AND EXPORTER_CODE = '".$exporter_code."'";
	}
	if($size != "ALL"){
		$sql .= " AND DC_CARGO_DESC = '".$size."'";
	}
	$sql .= " ORDER BY CT.PALLET_ID, CA.ORDER_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "<tr><td align=\"center\"><font size=\"3\" face=\"Verdana\" color=\"#FF0000\">No pallets matching this criteria.</font></td></tr>";
	} else {
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Order Num</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Packing House</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Count</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Shipped</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Activity Date</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Comments</b></font></td>
	</tr>
<?
		do {
			if($Short_Term_row['THE_MATCH'] != $current_pallet){
				$pallet_count++;
				$current_pallet = $Short_Term_row['THE_MATCH'];
			}
			$shipped_cases += $Short_Term_row['THE_SHIPPED'];
			$temp = split(":", $Short_Term_row['THE_DAMAGE']);
			$damaged_cases += $temp[1];
?>
	<tr>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_ORDER']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['DC_CARGO_DESC']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['EXPORTER_CODE']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['BATCH_ID']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_PALLET']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_SHIPPED']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_DATE_OUT']; ?></font></td>
		<td align="right"><font size="2" face="Verdana"><nobr><? echo $Short_Term_row['THE_DAMAGE']; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="8"><font size="2" face="Verdana"><b>Totals:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Pallets:  ".number_format($pallet_count); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Shipped Cartons:  ".number_format($shipped_cases); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Damaged Cartons:  ".number_format($damaged_cases); ?></b></font></td>
	</tr>
<?
	}
?>
</table>