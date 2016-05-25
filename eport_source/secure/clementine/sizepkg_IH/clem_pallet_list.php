<?
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$dates = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_GET_VARS['vessel'];
	$col = $HTTP_GET_VARS['col'];
	$show = $HTTP_GET_VARS['show'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$date = $HTTP_GET_VARS['date'];

?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet</b></font></td>
	</tr>
<?
	if($show == "SIZE"){
		$field = "CARGO_SIZE";
	} else {
		$field = "EXPORTER_CODE";
	}

	$sql = "SELECT DISTINCT CT.PALLET_ID
			FROM CARGO_TRACKING CT, CARGO_DETAIL@BNI CD
			WHERE CT.PALLET_ID = CD.CARGO_ID
				AND CT.ARRIVAL_NUM = CD.LR_NUM
				AND CT.RECEIVER_ID = CD.CARGO_CUSTOMER ";
	if($date != "total"){
		$sql .= "AND CD.RUN_DATE = '".$date."' ";
	}
	$sql .= "	AND CT.ARRIVAL_NUM = '".$vessel."'
				AND ".$field." = '".$col."' ";
	if($cust != "All"){
		$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
	}
	if($comm != "All"){
		$sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
	}
	$sql .= "ORDER BY PALLET_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
	</tr>
<?
	}
?>
</table>