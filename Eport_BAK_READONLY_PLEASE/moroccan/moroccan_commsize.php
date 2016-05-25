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

	$filter_sizehigh = $HTTP_POST_VARS['filter_sizehigh'];
	if($filter_sizehigh == ""){
		$filter_sizehigh = $HTTP_GET_VARS['filter_sizehigh'];
	}
	if($filter_sizehigh != "" && !is_numeric($filter_sizehigh)){
		$filter_sizehigh = "";
	}
	$filter_sizelow = $HTTP_POST_VARS['filter_sizelow'];
	if($filter_sizelow == ""){
		$filter_sizelow = $HTTP_GET_VARS['filter_sizelow'];
	}
	if($filter_sizelow != "" && !is_numeric($filter_sizelow)){
		$filter_sizelow = "";
	}
	$desc_like = $HTTP_POST_VARS['desc_like'];
	if($desc_like == ""){
		$desc_like = $HTTP_GET_VARS['desc_like'];
	}
	$desc_like = str_replace("'", "`", $desc_like);
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Moroccan Commodity Size Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_commsize_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Size:</font></td>
		<td><font size="2" face="Verdana"><input name="filter_sizelow" type="text" size="10" maxlength="10" value="<? echo $filter_sizelow; ?>">&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;<input						name="filter_sizehigh" type="text" size="10" maxlength="10" value="<? echo $filter_sizehigh; ?>"></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="3" face="Verdana">Descr:</font></td>
		<td><input type="text" name="desc_like" size="20" maxlength="20" value="<? echo $desc_like; ?>"><font size="2" face="Verdana">(partial values accepted)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Filter Results"><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="7" align="center"><a href="moroccan_commsize_addedit_index.php">Add new Size to Reference Table</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>	
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=SIZEID">SizeID</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=DESCR">Descr</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=PRICE">Price</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=SIZELOW">Size (Low)</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=SIZEHIGH">Size (High)</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=WEIGHTKG">Weight (KG)</a></b></font></td>
		<td><font size="2" face="Verdana"><b><a href="moroccan_commsize_index.php?sort_by=ORDER_ENT_EXCEL_COL">Excel Column<br>(Order Entry Form)</a></b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_sizehigh, $filter_sizelow, $desc_like, $sort_by, $cust, $conn);
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
		<td><font size="2" face="Verdana"><a href="moroccan_commsize_addedit_index.php?SIZEID=<? echo ociresult($stid, "SIZEID"); ?>"><? echo ociresult($stid, "SIZEID"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DESCR"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo money_format("%i", ociresult($stid, "PRICE")); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "SIZELOW"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "SIZEHIGH"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "WEIGHTKG"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "ORDER_ENT_EXCEL_COL"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_sizehigh, $filter_sizelow, $desc_like, $sort_by, $cust, $conn){
	$sql = "SELECT * FROM MOR_COMMODITYSIZE WHERE CUST = '".$cust."'";
	if($filter_sizehigh != ""){
		$sql .= " AND SIZEHIGH = '".$filter_sizehigh."'";
	}
	if($filter_sizelow != ""){
		$sql .= " AND SIZELOW = '".$filter_sizelow."'";
	}
	if($desc_like != ""){
		$sql .= " AND DESCR = '".$desc_like."'";
	}
	$sql .= " ORDER BY";
	if($sort_by != ""){
		$sql .= $sort_by.",";
	}
	$sql .= " SIZEID";
}
