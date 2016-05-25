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

	$filter_custhigh = $HTTP_POST_VARS['filter_custhigh'];
	if($filter_custhigh == ""){
		$filter_custhigh = $HTTP_GET_VARS['filter_custhigh'];
	}
	if($filter_custhigh != "" && !is_numeric($filter_custhigh)){
		$filter_custhigh = "";
	}
	$filter_custlow = $HTTP_POST_VARS['filter_custlow'];
	if($filter_custlow == ""){
		$filter_custlow = $HTTP_GET_VARS['filter_custlow'];
	}
	if($filter_custlow != "" && !is_numeric($filter_custlow)){
		$filter_custlow = "";
	}
	$shortname_like = $HTTP_POST_VARS['shortname_like'];
	if($shortname_like == ""){
		$shortname_like = $HTTP_GET_VARS['shortname_like'];
	}
	$shortname_like = str_replace("'", "`", $shortname_like);
	$stateprov_like = $HTTP_POST_VARS['stateprov_like'];
	if($stateprov_like == ""){
		$stateprov_like = $HTTP_GET_VARS['stateprov_like'];
	}
	$stateprov_like = str_replace("'", "`", $stateprov_like);
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Customer Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_cust_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">CustomerID:</font></td>
		<td><font size="2" face="Verdana"><input name="filter_custlow" type="text" size="10" maxlength="10" value="<? echo $filter_custlow; ?>">&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;<input						name="filter_custhigh" type="text" size="10" maxlength="10" value="<? echo $filter_custhigh; ?>"></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Short Name:</font></td>
		<td><input type="text" name="shortname_like" size="20" maxlength="20" value="<? echo $shortname_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">State/Province:</font></td>
		<td><input type="text" name="stateprov_like" size="20" maxlength="20" value="<? echo $stateprov_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="13" align="center"><a href="moroccan_cust_addedit_index.php">Add Customer Reference Table</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>	
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CUSTOMERID&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">CustomerID</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CUSTOMERSHORTNAME&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Customer Short Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CUSTOMERNAME&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Customer Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=ADDRESS&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Address</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=CITY&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">City</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=STATEPROVINCE&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">State/Province</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=POSTALCODE&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Postal Code</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=PHONE&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Phone</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=PHONEMOBILE&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Phone (Mobile)</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=NEEDPARS&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Need PARS</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=ORIGIN&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Origin</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=DESTCODE&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Destination Code</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_cust_index.php?sort_by=COMMENTS&filter_custlow=<? echo $filter_custlow;?>&filter_custhigh=<? echo $filter_custhigh; ?>&shortname_like=<? echo $shortname_like; ?>&stateprov_like=<? echo $stateprov_like; ?>">Comments</a></b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_custhigh, $filter_custlow, $shortname_like, $stateprov_like, $sort_by, $cust, $conn);
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
		if(ociresult($stid, "NEEDPARS") == "1"){
			$disp_needpars = "Yes";
		} else {
			$disp_needpars = "No";
		}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">	
		<td><font size="2" face="Verdana"><a href="moroccan_cust_addedit_index.php?CUSTOMERID=<? echo ociresult($stid, "CUSTOMERID"); ?>"><? echo ociresult($stid, "CUSTOMERID"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERSHORTNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ADDRESS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CITY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "STATEPROVINCE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "POSTALCODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PHONE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PHONEMOBILE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $disp_needpars; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ORIGIN"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DESTCODE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_custhigh, $filter_custlow, $shortname_like, $stateprov_like, $sort_by, $cust, $conn){
	$sql = "SELECT * FROM MOR_CUSTOMER WHERE CUST = '".$cust."'";
	if($filter_custhigh != ""){
		$sql .= " AND CUSTOMERID <= '".$filter_custhigh."'";
	}
	if($filter_custlow != ""){
		$sql .= " AND CUSTOMERID >= '".$filter_custlow."'";
	}
	if($shortname_like != ""){
		$sql .= " AND CUSTOMERSHORTNAME LIKE '%".$shortname_like."%'";
	}
	if($stateprov_like != ""){
		$sql .= " AND STATEPROVINCE LIKE '%".$stateprov_like."%'";
	}
	$sql .= " ORDER BY ";
	if($sort_by != ""){
		$sql .= $sort_by.",";
	}
	$sql .= " CUSTOMERID";
}
