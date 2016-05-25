<?
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }
  $Short_Term_Cursor = ora_open($conn);

	$user_cust_num = $HTTP_GET_VARS['cust'];

	$trans_id = $HTTP_GET_VARS['trans_id'];
	$type = $HTTP_GET_VARS['type'];

	$sql = "SELECT * FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = '".$type."' ORDER BY PALLET_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "No pallets to report";
	} else {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>IMP</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT_ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Hatch</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty</b></font></td>
		<td><font size="2" face="Verdana"><b>Loc</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower</b></font></td>
		<td><font size="2" face="Verdana"><b>Package</b></font></td>
	</tr>
<?
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $user_cust_num; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMODITY_CODE']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['VARIETY']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['REMARK']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['CARGO_SIZE']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['HATCH']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WAREHOUSE_LOCATION']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['GROWER']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['PACKAGE']; ?>&nbsp;</font></td>
	</tr>
<?
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>