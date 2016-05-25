<?
/*
*
*	Adam Walter, Aug-Sep 2013.
*
*	Picklist Listing screen for Moroccan Clementine Orders.
*
***********************************************************************************/

/*
//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
*/
	include("db_def.php");


//	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$custname = ociresult($stid, "CUSTOMER_NAME");


	$ordernum = $HTTP_POST_VARS['ordernum'];
	$submit = $HTTP_POST_VARS['submit'];

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Order Picklist</font></td>
	 </tr>
	 <tr>
	  <td align="left"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_picklist_list_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Order#:</font></td>
		<td><font size="2" face="Verdana"><input name="ordernum" type="text" size="10" maxlength="10" value="<? echo $ordernum; ?>"></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="View Order"><hr></td>
	</tr>
</form>
</table>
<?
	if($ordernum != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT MOS.DESCR, MCUST.CUSTOMERNAME, MCON.CONSIGNEENAME, TO_CHAR(PICKUPDATE, 'MM/DD/YYYY') THE_PICKUP,
					VP.VESSEL_NAME, MO.LOADTYPE, MO.COMMENTS, MO.VESSELID, MO.ORDERSTATUSID
				FROM MOR_ORDER MO, MOR_ORDERSTATUS MOS, MOR_CUSTOMER MCUST, MOR_CONSIGNEE MCON, VESSEL_PROFILE VP
				WHERE MO.ORDERSTATUSID = MOS.ORDERSTATUSID
					AND MO.CUSTOMERID = MCUST.CUSTOMERID
					AND MO.CONSIGNEEID = MCON.CONSIGNEEID
					AND MO.VESSELID = VP.LR_NUM
					AND MO.CUST = '".$cust."'
					AND MO.ORDERNUM = '".$ordernum."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Order# <? echo $ordernum; ?> Not found in system for customer <? echo $cust; ?></b></font></td>
	</tr>
<?
		} else {
			$formnum = 0;
?>
	<tr>
		<td colspan="4"><font size="3" face="Verdana"><b>Order Information:</b></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Vessel:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "VESSELID")." - ".ociresult($stid, "VESSEL_NAME"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Order#:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><b><? echo $ordernum; ?></b></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Customer:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERNAME"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Pickup Date:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "THE_PICKUP"); ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Consignee:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "CONSIGNEENAME"); ?></font></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Comments:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
	<tr>
		<td width="10%" align="right"><font size="2" face="Verdana">Orderstatus:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "ORDERSTATUSID")." - ".ociresult($stid, "DESCR"); ?></font>
			<? if(ociresult($stid, "ORDERSTATUSID") =="2"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="order_modify_index?ordernum=<? echo $ordernum; ?>&setstat=3">Set Status To: Picklist Complete</a><?}?></td>
		<td width="10%" align="right"><font size="2" face="Verdana">Load Type:</font></td>
		<td width="40%" align="left"><font size="2" face="Verdana"><? echo ociresult($stid, "LOADTYPE"); ?></font></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
			$sql = "SELECT ORDERDETAILID, COMMENTS, ORDERQTY, ORDERSIZEID, MOD.PRICE, MOD.SIZEHIGH, MOD.SIZELOW, MOD.WEIGHTKG, DESCR
					FROM MOR_ORDERDETAIL MOD, MOR_COMMODITYSIZE MC
					WHERE ORDERNUM = '".$ordernum."'
						AND MOD.ORDERSIZEID = MC.SIZEID
						AND MOD.CUST = '".$cust."'";
			$details = ociparse($conn, $sql);
			ociexecute($details);
			if(!ocifetch($details)){
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Order# <? echo $ordernum; ?> has no Details chosen for it; cannot generate picklist.</b></font></td>
	</tr>
<?
			} else {
?>
	<tr bgcolor="#FFFFCC">
		<td><font size="2" face="Verdana">Order Size</font></td>
		<td><font size="2" face="Verdana">Order QTY (Cartons)</font></td>
		<td><font size="2" face="Verdana">Size (Low)</font></td>
		<td><font size="2" face="Verdana">Size (High)</font></td>
		<td><font size="2" face="Verdana">Weight (KG/Carton)</font></td>
		<td><font size="2" face="Verdana">Comments</font></td>
	</tr>
	<tr bgcolor="#EEFFEE">
		<td bgcolor="#FFFFFF">&nbsp;</td>
		<td bgcolor="#FFFFFF">&nbsp;</td>
		<td><font size="2" face="Verdana">PackHouse</font></td>
		<td><font size="2" face="Verdana">Pallet QTY</font></td>
		<td><font size="2" face="Verdana">Pick List Size</font></td>
		<td><font size="2" face="Verdana">Comments</font></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
<?
				do {
					$formnum++;
?>
	<tr bgcolor="#FFFFCC">
		<td><font size="2" face="Verdana"><? echo ociresult($details, "DESCR"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($details, "ORDERQTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($details, "SIZELOW"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($details, "SIZEHIGH"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($details, "WEIGHTKG"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($details, "COMMENTS"); ?></font></td>
	</tr>
<?
					$sql = "SELECT * FROM MOR_PICKLIST
							WHERE ORDERNUM = '".$ordernum."'
								AND ORDERDETAILID = '".ociresult($details, "ORDERDETAILID")."'
								AND CUST = '".$cust."'";
					$picklist = ociparse($conn, $sql);
					ociexecute($picklist);
					while(ocifetch($picklist)){
?>
	<tr bgcolor="#EEFFEE">
	<form name="picklist[<? echo $formnum; ?>]" action="moroccan_picklist_modify_index.php" method="post">
	<input type="hidden" name="ordernum" value="<? echo $ordernum; ?>">
	<input type="hidden" name="orderdet" value="<? echo ociresult($details, "ORDERDETAILID"); ?>">
	<input type="hidden" name="picklist" value="<? echo ociresult($picklist, "PICKLISTID"); ?>">
		<td colspan="2" align="right"><input type="submit" name="submit" value="Edit Picklist Entry"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($picklist, "PACKINGHOUSE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($picklist, "PALLETQTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($picklist, "PICKLISTSIZE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($picklist, "COMMENTS"); ?></font></td>
	</form>
	</tr>
<?
					}
					$formnum++;
?>
	<tr bgcolor="#EEFFEE">
	<form name="picklist[<? echo $formnum; ?>]" action="moroccan_picklist_modify_index.php" method="post">
	<input type="hidden" name="ordernum" value="<? echo $ordernum; ?>">
	<input type="hidden" name="orderdet" value="<? echo ociresult($details, "ORDERDETAILID"); ?>">
	<input type="hidden" name="picklist" value="NEW">
		<td colspan="6" align="center"><input type="submit" name="submit" value="New Picklist Entry for this Order Detail"></td>
	</form>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
<?
				} while(ocifetch($details));
			}
		}
?>
</table>
<?
	}
