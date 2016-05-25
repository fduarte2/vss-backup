<?

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);

	
	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$var = $HTTP_GET_VARS['var'];
	if($var != "all"){
		$extra_sql = "AND VARIETY = '".$var."'";
	} else {
		$extra_sql = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Argentine Fruit Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Voucher#</b></font></td>
		<td><font size="2" face="Verdana"><b>Grade</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Pall No.</b></font></td>
		<td><font size="2" face="Verdana"><b>Received On</b></font></td>
	</tr>
<?
	$sql = "SELECT CT.*, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'Not Received') THE_REC 
			FROM CARGO_TRACKING CT
			WHERE ARRIVAL_NUM = '".$vessel."' 
				AND RECEIVER_ID = '".$cust."'
				AND COMMODITY_CODE = '".$comm."' 
				".$extra_sql."
				AND QTY_IN_HOUSE = 0 
			ORDER BY VARIETY, BATCH_ID, REMARK, CARGO_SIZE, PALLET_ID";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$temp = explode("-", $Short_Term_row['REMARK']);
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['QTY_IN_HOUSE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['VARIETY']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['BATCH_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $temp[0]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['CARGO_SIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $temp[1]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_row['THE_REC']; ?></font></td>
	</tr>
<?
	}
?>
</table>