<?
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
// $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
//    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $short_term_cursor = ora_open($conn);

	$LR = $HTTP_GET_VARS['LR'];
	$DT = $HTTP_GET_VARS['DT'];

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="11" align="center"><font size="3" face="Verdana"><b>Arrival:  <? echo $LR; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DockTicket:  <? echo $DT; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Arrival#</b></font></td>
		<td><font size="2" face="Verdana"><b>DT#</b></font></td>
		<td><font size="2" face="Verdana"><b>Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Cargo Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
		<td><font size="2" face="Verdana"><b>Basis-Weight</b></font></td>
		<td><font size="2" face="Verdana"><b>In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Damaged</b></font></td>
	</tr>
<?
	$sql = "SELECT ARRIVAL_NUM, BOL, BATCH_ID CODE, PALLET_ID, CARGO_DESCRIPTION, COMMODITY_CODE, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') THE_DATE,
				WEIGHT, MARK BASIS_WEIGHT, QTY_IN_HOUSE, QTY_DAMAGED 
			FROM CARGO_TRACKING
			WHERE ARRIVAL_NUM = '".$LR."'
				AND BOL = '".$DT."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor, $sql);
	while(ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['CARGO_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMODITY_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WEIGHT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BASIS_WEIGHT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_IN_HOUSE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_DAMAGED']; ?></font></td>
	</tr>
<?
	}
?>
</table>