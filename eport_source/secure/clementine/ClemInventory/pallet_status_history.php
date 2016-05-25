<?
/*
*	Adam Walter, October 2007.
*
*	This page is an auxilary function to page pallet_more_det.php.
*	It takes a pallet from the "remaining" area and gives
*	A status history on it.
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

	$vessel = $HTTP_GET_VARS['vessel'];
	$pallet_id = $HTTP_GET_VARS['pallet_id'];

?>
<table border="1" width="100%" cellpadding="2" cellspacing="0">
	<tr>
		<td align="center"><font size="3" face="Verdana"><b>Barcode:</b></font></td>
		<td align="center"><font size="3" face="Verdana"><b>Inspector:</b></font></td>
		<td align="center"><font size="3" face="Verdana"><b>Inspection Time:</b></font></td>
		<td align="center"><font size="3" face="Verdana"><b>Old Status:</b></font></td>
		<td align="center"><font size="3" face="Verdana"><b>New Status:</b></font></td>
	</tr>
<?
	$sql = "SELECT DIP.PALLET_ID THE_PALLET, DECODE(DIP.PREVIOUS_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_PREV_STATUS, DECODE(DIP.NEW_STATUS, 'R', 'REGRADE', 'H', 'HOSPITAL', 'B', 'BOTH', 'GOOD') THE_NEW_STATUS, INSPECTOR_ID, TO_CHAR(INSPECTION_DATETIME, 'MM/DD/YYYY HH:MI AM') THE_DATETIME FROM DC_INSPECTED_PALLET DIP, DC_INSPECTION_LOG DIL WHERE DIL.TRANSACTION_ID = DIP.TRANSACTION_ID AND DIP.PALLET_ID = '".$pallet_id."' AND DIL.ARRIVAL_NUM = '".$vessel."' ORDER BY INSPECTION_DATETIME";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $row['INSPECTOR_ID']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $row['THE_DATETIME']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $row['THE_PREV_STATUS']; ?></font></td>
		<td align="center"><font size="2" face="Verdana"><? echo $row['THE_NEW_STATUS']; ?></font></td>
	</tr>
<?
	}
?>
</table>