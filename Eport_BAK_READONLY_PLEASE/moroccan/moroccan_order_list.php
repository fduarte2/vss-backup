<?
/*
*
*	Adam Walter, Aug-Sep 2013.
*
*	Main Listing screen for Moroccan Clementine Orders.
*
***********************************************************************************/

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$custname = ociresult($stid, "CUSTOMER_NAME");

	$ordernum_like = $HTTP_POST_VARS['ordernum_like'];
	$filter_comm = $HTTP_POST_VARS['filter_comm'];
	$filter_custid = $HTTP_POST_VARS['filter_custid'];
	$filter_ordstatus = $HTTP_POST_VARS['filter_ordstatus'];
	if($filter_ordstatus == ""){
		$filter_ordstatus = "default";
	}
//	echo "ord: ".$$HTTP_POST_VARS['filter_ordstatus']."<br>";
	$filter_TEstatus = $HTTP_POST_VARS['filter_TEstatus'];
	$filter_datestart = $HTTP_POST_VARS['filter_datestart'];
	if($filter_datestart != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $filter_datestart)){
		echo "<font color=\"#FF0000\">Entered Starting date of ".$filter_datestart." not valid; must be in MM/DD/YYYY format.<br></font>";
		$filter_datestart = "";
	}
	$filter_dateend = $HTTP_POST_VARS['filter_dateend'];
	if($filter_dateend != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $filter_dateend)){
		echo "<font color=\"#FF0000\">Entered Ending date of ".$filter_dateend." not valid; must be in MM/DD/YYYY format.<br></font>";
		$filter_dateend = "";
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Order List</font></td>
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
<form name="get_data" action="moroccan_order_list_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Order#:</font></td>
		<td><font size="2" face="Verdana"><input name="ordernum_like" type="text" size="10" maxlength="10" value="<? echo $ordernum_like; ?>">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Commodity Code:</font></td>
		<td><select name="filter_comm"><option value="All">All</option>
<?
	$sql = "SELECT *
			FROM MOR_COMMODITY
			WHERE CUST = '".$cust."'
			ORDER BY PORT_COMMODITY_CODE";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "PORT_COMMODITY_CODE"); ?>"<? if(ociresult($stid, "PORT_COMMODITY_CODE") == $filter_comm){?> selected <?}?>><? echo ociresult($stid, "DC_COMMODITY_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Customer ID:</font></td>
		<td><select name="filter_custid"><option value="All">All</option>
<?
	$sql = "SELECT DISTINCT CUSTOMERID, CUSTOMERNAME
			FROM MOR_CUSTOMER
			WHERE CUST = '".$cust."'
			ORDER BY CUSTOMERID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "CUSTOMERID"); ?>"<? if(ociresult($stid, "CUSTOMERID") == $filter_custid){?> selected <?}?>><? echo ociresult($stid, "CUSTOMERID")." - ".ociresult($stid, "CUSTOMERNAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Order Status:</font></td>
		<td><select name="filter_ordstatus"><option value="All">All</option>
											<option value="default"<? if("default" == $filter_ordstatus || $filter_ordstatus == ""){?> selected <?}?>>Non-Complete, Non-Cancelled Orders</option>
<?
	$sql = "SELECT ORDERSTATUSID, DESCR
			FROM MOR_ORDERSTATUS
			ORDER BY ORDERSTATUSID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "ORDERSTATUSID"); ?>"<? if(ociresult($stid, "ORDERSTATUSID") == $filter_ordstatus){?> selected <?}?>><? echo ociresult($stid, "DESCR"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">TE Status:</font></td>
		<td><select name="filter_TEstatus"><option value="All">All</option>
			<option value="RECEIVED"<? if("RECEIVED" == $filter_TEstatus){?> selected <?}?>><? echo "RECEIVED"; ?></option>
			<option value="PENDING"<? if("PENDING" == $filter_TEstatus){?> selected <?}?>><? echo "PENDING"; ?></option>
			</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Pickup Date</font></td>
		<td><font size="2" face="Verdana">From:&nbsp;&nbsp;<input type="text" name="filter_datestart" size="10" maxlength="10" value="<? echo $filter_datestart; ?>">&nbsp;&nbsp;&nbsp;&nbsp;To:&nbsp;&nbsp;<input type="text" name="filter_dateend" size="10" maxlength="10" value="<? echo $filter_dateend; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="25" align="center"><a href="order_modify_index.php">Add New Order</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Order Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Consignee</b></font></td>
		<td><font size="2" face="Verdana"><b>Pick Up Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Delivery Date</b></font></td>
		<td><font size="2" face="Verdana"><b>TE Status</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Direct Order</b></font></td>
		<td><font size="2" face="Verdana"><b>Load Type</b></font></td>
		<td><font size="2" face="Verdana"><b>SNMG#</b></font></td>
		<td><font size="2" face="Verdana"><b>Transporter</b></font></td>
		<td><font size="2" face="Verdana"><b>Driver Name</b></font></td>
		<td><font size="2" face="Verdana"><b>Truck Tag</b></font></td>
		<td><font size="2" face="Verdana"><b>Trailer#</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Carton Count</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Plt Count</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Box Damage</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Weight</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Price</b></font></td>
		<td><font size="2" face="Verdana"><b>Transport Charges</b></font></td>
		<td><font size="2" face="Verdana"><b>Comments</b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_custid, $ordernum_like, $filter_comm, $filter_ordstatus, $filter_TEstatus, $filter_datestart, $filter_dateend, "", $cust, $conn);
//	echo $sql."<br>";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		$counter++;
		if($counter == 2){
			$bgcolor = "#FFFFCC";
			$counter = 0;
		} else {
			$bgcolor = "#FFFFFF";
		}
?>
<form name="alter<? echo $counter; ?>" action="order_modify_index.php" method="post">
<input type="hidden" name="ordernum" value="<? echo ociresult($stid, "ORDERNUM"); ?>">
	<tr bgcolor="<? echo $bgcolor; ?>">	
		<td><input type="submit" name="submit" value="Edit"></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ORDERNUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DESCR"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CONSIGNEENAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_PICKUP"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_DELV"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TESTATUS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "VESSEL_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DC_COMMODITY_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERPO"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DIRECTORDER"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "LOADTYPE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "SNMGNUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CARRIERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DRIVERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TRUCKTAG"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TRAILERNUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TOTALCOUNT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TOTALPALLETCOUNT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TOTALBOXDAMAGED"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TOTALQUANTITYKG"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TOTALPRICE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "TRANSPORTCHARGES"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
</form>
<?
	}
?>
</table>







<?
function MakeSQL(&$sql, $filter_custid, $ordernum_like, $filter_comm, $filter_ordstatus, $filter_TEstatus, $filter_datestart, $filter_dateend, $sort_by, $cust, $conn){
	$sql = "SELECT MO.ORDERNUM, MOS.DESCR, MCUST.CUSTOMERNAME, MCON.CONSIGNEENAME, TO_CHAR(PICKUPDATE, 'MM/DD/YYYY') THE_PICKUP, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') THE_DELV,
				MO.TESTATUS, VP.VESSEL_NAME, MCOM.DC_COMMODITY_NAME, MO.CUSTOMERPO, MO.DIRECTORDER, MO.LOADTYPE, MO.SNMGNUM, MT.CARRIERNAME, MO.DRIVERNAME,
				MO.TRUCKTAG, MO.TRAILERNUM, MO.TOTALCOUNT, MO.TOTALPALLETCOUNT, MO.TOTALBOXDAMAGED, MO.TOTALQUANTITYKG, MO.TOTALPRICE, MO.TRANSPORTCHARGES, MO.COMMENTS
			FROM MOR_ORDER MO, MOR_ORDERSTATUS MOS, MOR_CUSTOMER MCUST, MOR_CONSIGNEE MCON, VESSEL_PROFILE VP, MOR_COMMODITY MCOM, MOR_TRANSPORTER MT
			WHERE MO.ORDERSTATUSID = MOS.ORDERSTATUSID
				AND MO.CUSTOMERID = MCUST.CUSTOMERID
				AND MO.CONSIGNEEID = MCON.CONSIGNEEID
				AND MO.VESSELID = VP.LR_NUM
				AND MO.COMMODITYCODE = MCOM.PORT_COMMODITY_CODE
				AND MO.TRANSPORTERID = MT.TRANSPORTID
				AND MO.CUST = '".$cust."'";
	if($filter_custid != "All" && $filter_custid != ""){
		$sql .= " AND MO.CUSTOMERID = '".$filter_custid."'";
	}
	if($ordernum_like != ""){
		$sql .= " AND MO.ORDERNUM LIKE '%".$ordernum_like."%'";
	}
	if($filter_comm != "All" && $filter_comm != ""){
		$sql .= " AND MO.COMMODITYCODE = '".$filter_comm."'";
	}
	if($filter_ordstatus != "All" && $filter_ordstatus != ""){
		if($filter_ordstatus == "default"){
			$sql .= " AND MO.ORDERSTATUSID <= 8";
		} else {
			$sql .= " AND MO.ORDERSTATUSID = '".$filter_ordstatus."'";
		}
	}
	if($filter_TEstatus != "All" && $filter_TEstatus != ""){
		$sql .= " AND MO.TESTATUS = '".$filter_TEstatus."'";
	}
	if($filter_datestart != "All" && $filter_datestart != ""){
		$sql .= " AND MO.PICKUPDATE >= TO_DATE('".$filter_datestart."', 'MM/DD/YYYY')";
	}
	if($filter_dateend != "All" && $filter_dateend != ""){
		$sql .= " AND MO.PICKUPDATE <= TO_DATE('".$filter_dateend."', 'MM/DD/YYYY')";
	}

	$sql .= " ORDER BY MO.ORDERNUM";
	
}

