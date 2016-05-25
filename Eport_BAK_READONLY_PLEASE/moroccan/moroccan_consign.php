<?
/*
*	Adam Walter, Jul 2013.
*
*	Page for management of commodity-specific sizes of Moroccan products.
*************************************************************************/

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];

	$filter_conshigh = $HTTP_POST_VARS['filter_conshigh'];
	if($filter_conshigh == ""){
		$filter_conshigh = $HTTP_GET_VARS['filter_conshigh'];
	}
	if($filter_conshigh != "" && !is_numeric($filter_conshigh)){
		$filter_conshigh = "";
	}
	$filter_conslow = $HTTP_POST_VARS['filter_conslow'];
	if($filter_conslow == ""){
		$filter_conslow = $HTTP_GET_VARS['filter_conslow'];
	}
	if($filter_conslow != "" && !is_numeric($filter_conslow)){
		$filter_conslow = "";
	}
	$consname_like = $HTTP_POST_VARS['consname_like'];
	if($consname_like == ""){
		$consname_like = $HTTP_GET_VARS['consname_like'];
	}
	$consname_like = str_replace("'", "`", $consname_like);
	$filter_custid = $HTTP_POST_VARS['filter_custid'];
	if($filter_custid == ""){
		$filter_custid = $HTTP_GET_VARS['filter_custid'];
	}
	$filter_custid = str_replace("'", "`", $filter_custid);
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Consignee Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_consign_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">ConsigneeID:</font></td>
		<td><font size="2" face="Verdana"><input name="filter_conslow" type="text" size="10" maxlength="10" value="<? echo $filter_conslow; ?>">&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;<input						name="filter_conshigh" type="text" size="10" maxlength="10" value="<? echo $filter_conshigh; ?>"></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Consignee Name:</font></td>
		<td><input type="text" name="consname_like" size="20" maxlength="20" value="<? echo $consname_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Customer ID:</font></td>
		<td><select name="filter_custid"><option value="">All</option>
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
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="13" align="center"><a href="moroccan_consign_addedit_index.php">Add Consignee Reference</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>	
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CONSIGNEEID&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">ConsigneeID</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=ADDRESS&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Address</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CUSTOMSBROKEROFFICEID&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Broker Office</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CITY&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">City</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CONSIGNEENAME&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Consignee Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CUSTOMERID&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Customer</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=PHONE&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Phone</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=PHONEMOBILE&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Phone (Mobile)</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=POSTALCODE&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Postal Code</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=STATEPROVINCE&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">State/Province</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=COMMENTS&filter_conslow=<? echo $filter_conslow;?>&filter_conshigh=<? echo $filter_conshigh; ?>&consname_like=<? echo $consname_like; ?>&filter_custid=<? echo $filter_custid; ?>">Comments</a></b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_conshigh, $filter_conslow, $consname_like, $filter_custid, $sort_by, $cust, $conn);
//	echo $sql."<br>";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
		$counter++;
		if($counter == 10){
			$bgcolor = "#FFFFCC";
			$counter = 0;
		} else {
			$bgcolor = "#FFFFFF";
		}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">	
		<td><font size="2" face="Verdana"><a href="moroccan_consign_addedit_index.php?CONSIGNEEID=<? echo ociresult($stid, "CONSIGNEEID"); ?>"><? echo ociresult($stid, "CONSIGNEEID"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ADDRESS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMSBROKEROFFICEID")." - ".ociresult($stid, "CONTACTNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CITY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CONSIGNEENAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERID")." - ".ociresult($stid, "CUSTOMERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PHONE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PHONEMOBILE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "POSTALCODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "STATEPROVINCE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_conshigh, $filter_conslow, $consname_like, $filter_custid, $sort_by, $cust, $conn){
	$sql = "SELECT MORCONS.CONSIGNEEID, MORCONS.ADDRESS, MORCONS.CUSTOMSBROKEROFFICEID, MORBRO.CONTACTNAME, MORCONS.CITY, MORCONS.CONSIGNEENAME, 
				MORCONS.CUSTOMERID, MORCUST.CUSTOMERNAME, MORCONS.PHONE, MORCONS.PHONEMOBILE, MORCONS.POSTALCODE, MORCONS.STATEPROVINCE, MORCONS.COMMENTS FROM 
			MOR_CONSIGNEE MORCONS, MOR_CUSTOMER MORCUST, MOR_BROKER MORBRO
			WHERE MORCONS.CUST = '".$cust."'
				AND MORCONS.CUSTOMERID = MORCUST.CUSTOMERID
				AND MORCONS.CUSTOMSBROKEROFFICEID = MORBRO.BROKERID";
	if($filter_conshigh != ""){
		$sql .= " AND CONSIGNEEID <= '".$filter_conshigh."'";
	}
	if($filter_conslow != ""){
		$sql .= " AND CONSIGNEEID >= '".$filter_conslow."'";
	}
	if($consname_like != ""){
		$sql .= " AND CONSIGNEENAME LIKE '%".$consname_like."%'";
	}
	if($filter_custid != ""){
		$sql .= " AND MORCONS.CUSTOMERID = '".$filter_custid."'";
	}
	$sql .= " ORDER BY ";
	if($sort_by != ""){
		$sql .= $sort_by.",";
	}
	$sql .= " CONSIGNEEID";
}
