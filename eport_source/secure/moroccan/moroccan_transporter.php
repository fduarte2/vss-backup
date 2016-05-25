<?
/*
*	Adam Walter, Jul 2013.
*
*	Page for management of commodity-specific sizes of Moroccan products.
*************************************************************************/

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

	$filter_transhigh = $HTTP_POST_VARS['filter_transhigh'];
	if($filter_transhigh == ""){
		$filter_transhigh = $HTTP_GET_VARS['filter_transhigh'];
	}
	if($filter_transhigh != "" && !is_numeric($filter_transhigh)){
		$filter_transhigh = "";
	}
	$filter_translow = $HTTP_POST_VARS['filter_translow'];
	if($filter_translow == ""){
		$filter_translow = $HTTP_GET_VARS['filter_translow'];
	}
	if($filter_translow != "" && !is_numeric($filter_translow)){
		$filter_translow = "";
	}
	$carriername_like = $HTTP_POST_VARS['carriername_like'];
	if($carriername_like == ""){
		$carriername_like = $HTTP_GET_VARS['carriername_like'];
	}
	$carriername_like = str_replace("'", "`", $carriername_like);
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Transporter List</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_transporter_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Transporter ID:</font></td>
		<td><font size="2" face="Verdana"><input name="filter_translow" type="text" size="10" maxlength="10" value="<? echo $filter_translow; ?>">&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;<input						name="filter_transhigh" type="text" size="10" maxlength="10" value="<? echo $filter_transhigh; ?>"></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Carrier Name:</font></td>
		<td><input type="text" name="carriername_like" size="20" maxlength="20" value="<? echo $carriername_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="14" align="center"><a href="moroccan_transporter_addedit_index.php">Add Transporter Reference</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>	
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=TRANSPORTID&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">TransportID</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=CARRIERNAME&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Carrier Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=CONTACTNAME&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Contact Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=EMAIL&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Email</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=FAX&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Fax</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=IRSNUM&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">IRS#</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=PHONE1&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Phone1</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=PHONE2&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Phone2</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=PHONECELL1&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Phone (Cell) 1</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=PHONECELL2&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Phone (Cell) 2</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE1GTAMILTONWHITBY&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate1: GTA Milton Whitby</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE2CAMBRIDGE&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate2: Cambridge</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE3OTTAWA&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate3: Ottawa</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE4MONTREAL&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate4: Montreal</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE5QUEBEC&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate5: Quebec</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE6MONCTON&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate6: Moncton</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE7DEBERT&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate7: Debert</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=RATE8OTHER&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Rate8: Other</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=USBONDNUM&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">US Bond#</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_transporter_index.php?sort_by=COMMENTS&filter_translow=<? echo $filter_translow;?>&filter_transhigh=<? echo $filter_transhigh; ?>&carriername_like=<? echo $carriername_like; ?>">Comments</a></b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_transhigh, $filter_translow, $carriername_like, $sort_by, $cust, $conn);
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
		<td><font size="2" face="Verdana"><a href="moroccan_transporter_addedit_index.php?TRANSPORTID=<? echo ociresult($stid, "TRANSPORTID"); ?>"><? echo ociresult($stid, "TRANSPORTID"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CARRIERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CONTACTNAME"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "EMAIL"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "FAX"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "IRSNUM"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "PHONE1"); ?></font></td>
		<td><font size="2" face="Verdana"><nobr>&nbsp;<? echo ociresult($stid, "PHONE2"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr>&nbsp;<? echo ociresult($stid, "PHONECELL1"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr>&nbsp;<? echo ociresult($stid, "PHONECELL2"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr>&nbsp;<? echo ociresult($stid, "RATE1GTAMILTONWHITBY"); ?></nobr></font></td>
		<td><font size="2" face="Verdana"><nobr>&nbsp;<? echo ociresult($stid, "RATE2CAMBRIDGE"); ?></nobr></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "RATE3OTTAWA"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "RATE4MONTREAL"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "RATE5QUEBEC"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "RATE6MONCTON"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "RATE7DEBERT"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "RATE8OTHER"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "USBONDNUM"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_transhigh, $filter_translow, $carriername_like, $sort_by, $cust, $conn){
	$sql = "SELECT * FROM MOR_TRANSPORTER WHERE CUST = '".$cust."'";
	if($filter_transhigh != ""){
		$sql .= " AND TRANSPORTID <= '".$filter_transhigh."'";
	}
	if($filter_translow != ""){
		$sql .= " AND TRANSPORTID >= '".$filter_translow."'";
	}
	if($carriername_like != ""){
		$sql .= " AND CARRIERNAME LIKE '%".$carriername_like."%'";
	}
	$sql .= " ORDER BY ";
	if($sort_by != ""){
		$sql .= $sort_by.",";
	}
	$sql .= " TRANSPORTID";
}
