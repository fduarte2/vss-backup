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

	$filter_brokerhigh = $HTTP_POST_VARS['filter_brokerhigh'];
	if($filter_brokerhigh == ""){
		$filter_brokerhigh = $HTTP_GET_VARS['filter_brokerhigh'];
	}
	if($filter_brokerhigh != "" && !is_numeric($filter_brokerhigh)){
		$filter_brokerhigh = "";
	}
	$filter_brokerlow = $HTTP_POST_VARS['filter_brokerlow'];
	if($filter_brokerlow == ""){
		$filter_brokerlow = $HTTP_GET_VARS['filter_brokerlow'];
	}
	if($filter_brokerlow != "" && !is_numeric($filter_brokerlow)){
		$filter_brokerlow = "";
	}
	$bordercross_like = $HTTP_POST_VARS['bordercross_like'];
	if($bordercross_like == ""){
		$bordercross_like = $HTTP_GET_VARS['bordercross_like'];
	}
	$bordercross_like = str_replace("'", "`", $bordercross_like);
	$custombroker_like = $HTTP_POST_VARS['custombroker_like'];
	if($custombroker_like == ""){
		$custombroker_like = $HTTP_GET_VARS['custombroker_like'];
	}
	$custombroker_like = str_replace("'", "`", $custombroker_like);
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Broker Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_broker_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Customs Broker ID:</font></td>
		<td><font size="2" face="Verdana"><input name="filter_brokerlow" type="text" size="10" maxlength="10" value="<? echo $filter_brokerlow; ?>">&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;<input						name="filter_brokerhigh" type="text" size="10" maxlength="10" value="<? echo $filter_brokerhigh; ?>"></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Border Crossing:</font></td>
		<td><input type="text" name="bordercross_like" size="20" maxlength="20" value="<? echo $bordercross_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Customs Broker:</font></td>
		<td><input type="text" name="custombroker_like" size="20" maxlength="20" value="<? echo $custombroker_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="14" align="center"><a href="moroccan_broker_addedit_index.php">Add Broker Reference</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>	
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=BROKERID&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">BrokerID</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=CLIENT&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Client</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=BORDERCROSSING&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Border Crossing</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=CUSTOMSBROKER&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Customs Broker</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=CONTACTNAME&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Contact Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=PHONE&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Phone</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=FAX&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Fax</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=EMAIL1&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Email #1</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=EMAIL2&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Email #2</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=EMAIL3&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Email #3</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=EMAIL4&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Email #4</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=EMAIL5&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Email #5</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=DESTINATIONS&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Destinations</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_broker_index.php?sort_by=COMMENTS&filter_brokerlow=<? echo $filter_brokerlow;?>&filter_brokerhigh=<? echo $filter_brokerhigh; ?>&bordercross_like=<? echo $bordercross_like; ?>&custombroker_like=<? echo $custombroker_like; ?>">Comments</a></b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_brokerhigh, $filter_brokerlow, $bordercross_like, $custombroker_like, $sort_by, $cust, $conn);
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
		<td><font size="2" face="Verdana"><a href="moroccan_broker_addedit_index.php?BROKERID=<? echo ociresult($stid, "BROKERID"); ?>"><? echo ociresult($stid, "BROKERID"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CLIENT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "BORDERCROSSING"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMSBROKER"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CONTACTNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PHONE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "FAX"); ?></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo ociresult($stid, "EMAIL1"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo ociresult($stid, "EMAIL2"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo ociresult($stid, "EMAIL3"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo ociresult($stid, "EMAIL4"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr><? echo ociresult($stid, "EMAIL5"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DESTINATIONS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_brokerhigh, $filter_brokerlow, $bordercross_like, $custombroker_like, $sort_by, $cust, $conn){
	$sql = "SELECT * FROM MOR_BROKER WHERE CUST = '".$cust."'";
	if($filter_brokerhigh != ""){
		$sql .= " AND BROKERID <= '".$filter_brokerhigh."'";
	}
	if($filter_brokerlow != ""){
		$sql .= " AND BROKERID >= '".$filter_brokerlow."'";
	}
	if($bordercross_like != ""){
		$sql .= " AND BORDERCROSSING LIKE '%".$bordercross_like."%'";
	}
	if($custombroker_like != ""){
		$sql .= " AND CUSTOMSBROKER LIKE '%".$custombroker_like."%'";
	}
	$sql .= " ORDER BY ";
	if($sort_by != ""){
		$sql .= $sort_by.",";
	}
	$sql .= " BROKERID";
}
