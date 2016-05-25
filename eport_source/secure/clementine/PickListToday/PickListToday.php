<?
/* Adam Walter, 8/25/07
*	This file simply displays on Eport a grid of inventory,
*	Packing houses across the top of the grid,
*	Sizes down the left.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);

	// $eport_customer_id comes from index.php
	if($eport_customer_id == 0){
		$extra_sql = "";
	} else {
//		$extra_sql = "AND DCC.PORT_CUSTOMER_ID = '".$eport_customer_id."'";
		$extra_sql = "";
	}

?>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Pick Lists, <? echo date('m/d/Y'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="1" width="65%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Load Type</b></font></td>
		<td><font size="2" face="Verdana"><b>PKG House</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet Quantity</b></font></td>
	</tr>
<?
	$sql = "SELECT DCO.ORDERNUM THE_ORDER, LOADTYPE, PACKINGHOUSE, PICKLISTSIZE, SUM(PALLETQTY) THE_PALLETS 
			FROM DC_ORDER DCO, DC_PICKLIST DCP, DC_CUSTOMER DCC 
			WHERE DCP.ORDERNUM = DCO.ORDERNUM 
				AND DCO.PICKUPDATE >= '".date('d-M-Y')."' 
				AND DCO.PICKUPDATE < '".date('d-M-Y', mktime(0,0,0,date('m'),date('d')+1,date('Y')))."'
				AND DCO.CUSTOMERID = DCC.CUSTOMERID
				".$extra_sql."
			GROUP BY DCO.ORDERNUM, LOADTYPE, PACKINGHOUSE, PICKLISTSIZE ORDER BY THE_ORDER, PACKINGHOUSE, PICKLISTSIZE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['THE_ORDER']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['LOADTYPE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PACKINGHOUSE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PICKLISTSIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_PALLETS']; ?></font></td>
	</tr>
<?
	}
?>
</table>