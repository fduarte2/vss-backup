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

	$filter_commhigh = $HTTP_POST_VARS['filter_commhigh'];
	if($filter_commhigh == ""){
		$filter_commhigh = $HTTP_GET_VARS['filter_commhigh'];
	}
	if($filter_commhigh != "" && !is_numeric($filter_commhigh)){
		$filter_commhigh = "";
	}
	$filter_commlow = $HTTP_POST_VARS['filter_commlow'];
	if($filter_commlow == ""){
		$filter_commlow = $HTTP_GET_VARS['filter_commlow'];
	}
	if($filter_commlow != "" && !is_numeric($filter_commlow)){
		$filter_commlow = "";
	}
	$commname_like = $HTTP_POST_VARS['commname_like'];
	if($commname_like == ""){
		$commname_like = $HTTP_GET_VARS['commname_like'];
	}
	$commname_like = str_replace("'", "`", $commname_like);
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Commodity Codes</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_commodity_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Commodity Code:</font></td>
		<td><font size="2" face="Verdana"><input name="filter_commlow" type="text" size="10" maxlength="10" value="<? echo $filter_commlow; ?>">&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;<input						name="filter_commhigh" type="text" size="10" maxlength="10" value="<? echo $filter_commhigh; ?>"></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Commodity Name:</font></td>
		<td><input type="text" name="commname_like" size="20" maxlength="20" value="<? echo $commname_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<!--	<tr>
		<td colspan="14" align="center"><a href="moroccan_commodity_addedit_index.php">Add Commodity Reference</a></td>
	</tr> !-->
<?
	$counter = 0;
?>
	<tr>	
		<td><font size="2" face="Verdana"><b><a href="moroccan_commodity_index.php?sort_by=PORT_COMMODITY_CODE&filter_commlow=<? echo $filter_commlow;?>&filter_commhigh=<? echo $filter_commhigh; ?>&commname_like=<? echo $commname_like; ?>">Commodity Code</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commodity_index.php?sort_by=DC_COMMODITY_NAME&filter_commlow=<? echo $filter_commlow;?>&filter_commhigh=<? echo $filter_commhigh; ?>&commname_like=<? echo $commname_like; ?>">Commodity Name</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commodity_index.php?sort_by=HARMONIZEDTARIFF&filter_commlow=<? echo $filter_commlow;?>&filter_commhigh=<? echo $filter_commhigh; ?>&commname_like=<? echo $commname_like; ?>">Harmonized System Tariff</a></b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_commhigh, $filter_commlow, $commname_like, $sort_by, $cust, $conn);
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
		<td><font size="2" face="Verdana"><a href="moroccan_commodity_addedit_index.php?PORT_COMMODITY_CODE=<? echo ociresult($stid, "PORT_COMMODITY_CODE"); ?>"><? echo ociresult($stid, "PORT_COMMODITY_CODE"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DC_COMMODITY_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "HARMONIZEDTARIFF"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_commhigh, $filter_commlow, $commname_like, $sort_by, $cust, $conn){
	$sql = "SELECT * FROM MOR_COMMODITY WHERE CUST = '".$cust."'";
	if($filter_commhigh != ""){
		$sql .= " AND PORT_COMMODITY_CODE <= '".$filter_commhigh."'";
	}
	if($filter_commlow != ""){
		$sql .= " AND PORT_COMMODITY_CODE >= '".$filter_commlow."'";
	}
	if($commname_like != ""){
		$sql .= " AND DC_COMMODITY_NAME LIKE '%".$commname_like."%'";
	}
	$sql .= " ORDER BY ";
	if($sort_by != ""){
		$sql .= $sort_by.",";
	}
	$sql .= " PORT_COMMODITY_CODE";
}
