<?
/*
*	Adam Walter, Apr 2016
*
*	In house damages.
*
*	Displays As-of the time report is generated.
********************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

?>
<h1>Dockticket Paper InHouse Damages</h1>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="13" align="center"><font size="3" face="Verdana">InHouse Damaged Dockticket Paper as of:  <? echo date('m/d/Y h:i:s'); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Dock Ticket</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Code</b></font></td>	!-->
		<td><font size="2" face="Verdana"><b>Roll</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival Num</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Damage ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Cleared</b></font></td>
		<td><font size="2" face="Verdana"><b>Occurred</b></font></td>
		<td><font size="2" face="Verdana"><b>DMG Type</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Type</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY In House</b></font></td>
	</tr>
<?
	$sql = "SELECT CT.RECEIVER_ID, DD.DOCK_TICKET, CT.CARGO_DESCRIPTION, DD.ROLL, CT.ARRIVAL_NUM, TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY') THE_REC, DD.DAMAGE_ID,
				TO_CHAR(DD.DATE_CLEARED, 'MM/DD/YYYY') THE_CLEAR, DD.OCCURRED, DD.DMG_TYPE, DD.QUANTITY, DD.QTY_TYPE, CT.QTY_IN_HOUSE
			FROM CARGO_TRACKING CT, DOLEPAPER_DAMAGES DD 
			WHERE CT.RECEIVER_ID = DD.CUSTOMER_ID
				AND CT.PALLET_ID = DD.ROLL
				AND CT.QTY_IN_HOUSE > 0
			ORDER BY DATE_RECEIVED, DAMAGE_ID";
	$dt_data = ociparse($rfconn, $sql);
	ociexecute($dt_data);
	if(!ocifetch($dt_data)){
?>
		<tr><td colspan="13" align="center"><b>None</b></td></tr>
<?
	} else {
//		$total = 0;
		do { 
?>
	<tr>
		<td><? echo ociresult($dt_data, "RECEIVER_ID"); ?></td>
		<td><? echo ociresult($dt_data, "DOCK_TICKET"); ?></td>
		<td><? echo ociresult($dt_data, "CARGO_DESCRIPTION"); ?></td>
		<td><? echo ociresult($dt_data, "ROLL"); ?></td>
		<td><? echo ociresult($dt_data, "ARRIVAL_NUM"); ?></td>
		<td><? echo ociresult($dt_data, "THE_REC"); ?></td>
		<td><? echo ociresult($dt_data, "DAMAGE_ID"); ?></td>
		<td><? echo ociresult($dt_data, "THE_CLEAR"); ?></td>
		<td><? echo ociresult($dt_data, "OCCURRED"); ?></td>
		<td><? echo ociresult($dt_data, "DMG_TYPE"); ?></td>
		<td><? echo ociresult($dt_data, "QUANTITY"); ?></td>
		<td><? echo ociresult($dt_data, "QTY_TYPE"); ?></td>
		<td><? echo ociresult($dt_data, "QTY_IN_HOUSE"); ?></td>
	</tr>
<?
		} while(ocifetch($dt_data));
?>
</table>
<?
	}