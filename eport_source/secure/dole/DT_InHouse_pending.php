<?
/*
*	Adam Walter, Apr 2016
*
*	takes a Dockticket value passed from the previous page,
*	And shows all pending orders requiring those rolls.
********************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);

	$DT = $_GET['DT'];
//	$customer = $_GET['customer'];


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dockticket Paper REAL TIME Inventory - Pending Orders 
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana"><b>DT#:</b></font></td>
		<td><font size="3" face="Verdana"><?php echo $DT; ?></font></td>
	</tr>
</table>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Pending Orders:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Rolls Requested</b></font></td>
	</tr>
<?php
	$sql = "SELECT DO.ORDER_NUM, DD.QTY_SHIP
			FROM DOLEPAPER_DOCKTICKET DD, DOLEPAPER_ORDER DO
			WHERE DD.ORDER_NUM = DO.ORDER_NUM
				AND DO.STATUS NOT IN ('6', '7', '8')
				AND DD.DOCK_TICKET = '".$DT."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>None</b></font></td>
	</tr>
<?php
} else {
	do {
?>
	<tr>
		<td><font size="2" face="Verdana"><?php echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['QTY_SHIP']; ?></font></td>
	</tr>
<?php
	} while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
}
?>
</table>