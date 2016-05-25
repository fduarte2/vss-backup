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

	$filter_custid = $HTTP_POST_VARS['filter_custid'];
	$commodity_like = $HTTP_POST_VARS['commodity_like'];
	$size_like = $HTTP_POST_VARS['size_like'];
	$sort_by = $HTTP_GET_VARS['sort_by'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td align="left"><font size="5" face="Verdana" color="#0066CC">Moroccan Customer-Price List</font></td>
	  <td align="right"><font size="4" face="Verdana" color="#0066CC">Customer:  <? echo $custname; ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><font size="4" face="Verdana" color="#0066CC">Time:  <? echo date('m/d/Y h:i:s'); ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="moroccan_custprice_index.php" method="post">
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
		<td width="10%"><font size="2" face="Verdana">Commodity Code:</font></td>
		<td><select name="commodity_like"><option value="All">All</option>
<?
	$sql = "SELECT DISTINCT COMMODITY_CODE, COMMODITY_NAME
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_TYPE = 'CLEMENTINES'
			ORDER BY COMMODITY_CODE";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "COMMODITY_CODE"); ?>"<? if(ociresult($stid, "COMMODITY_CODE") == $commodity_like){?> selected <?}?>><? echo ociresult($stid, "COMMODITY_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Size:</font></td>
		<td><select name="size_like"><option value="All">All</option>
<?
	$sql = "SELECT DISTINCT SIZEID, DESCR
			FROM MOR_COMMODITYSIZE
			WHERE CUST = '".$cust."'
			ORDER BY SIZEID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
			<option value="<? echo ociresult($stid, "SIZEID"); ?>"<? if(ociresult($stid, "SIZEID") == $size_like){?> selected <?}?>><? echo ociresult($stid, "SIZEID")." - ".ociresult($stid, "DESCR"); ?></option>
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
		<td colspan="6" align="center"><a href="moroccan_custprice_addedit_index.php">Add Customer_Price Reference</a></td>
	</tr>
<?
	$counter = 0;
?>
	<tr>
<!--		<td>&nbsp;</td> !-->
		<td><font size="2" face="Verdana"><b>CustomerID</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Effective Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Price</b></font></td>
		<td><font size="2" face="Verdana"><b>Comments</b></font></td>
	</tr>
<?
	MakeSQL($sql, $filter_custid, $commodity_like, $size_like, $sort_by, $cust, $conn);
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
<!--		<td><font size="2" face="Verdana"><a href="moroccan_custprice_addedit_index.php?CUSTOMERID=<? echo ociresult($stid, "CUSTOMERID"); ?>&COMMODITY_CODE=<? echo ociresult($stid, "COMMODITY_CODE"); ?>&SIZEID=<? echo ociresult($stid, "SIZEID"); ?>&EFFECTIVEDATE=<? echo ociresult($stid, "THE_DATE"); ?>">Edit</a></font></td> !-->
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "CUSTOMERID")." - ".ociresult($stid, "CUSTOMERNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMMODITY_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "SIZEID")." - ".ociresult($stid, "DESCR"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PRICE"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($stid, "COMMENTS"); ?></font></td>
	</tr>
<?
	}
?>
</table>
<?








function MakeSQL(&$sql, $filter_custid, $commodity_like, $size_like, $sort_by, $cust, $conn){
	$sql = "SELECT MCUSTP.CUSTOMERID, MORCUST.CUSTOMERNAME, MCUSTP.COMMODITY_CODE, COMP.COMMODITY_NAME, MCUSTP.SIZEID, MORSIZE.DESCR,
				TO_CHAR(EFFECTIVEDATE, 'MM/DD/YYYY') THE_DATE, MCUSTP.PRICE, MCUSTP.COMMENTS
			FROM MOR_CUSTPRICE MCUSTP, MOR_CUSTOMER MORCUST, MOR_COMMODITYSIZE MORSIZE, COMMODITY_PROFILE COMP
			WHERE MCUSTP.CUST = '".$cust."'
				AND MCUSTP.CUSTOMERID = MORCUST.CUSTOMERID
				AND MCUSTP.COMMODITY_CODE = COMP.COMMODITY_CODE
				AND MCUSTP.SIZEID = MORSIZE.SIZEID";
	if($filter_custid != "All" && $filter_custid != ""){
		$sql .= " AND MCUSTP.CUSTOMERID = '".$filter_custid."'";
	}
	if($commodity_like != "All" && $commodity_like != ""){
		$sql .= " AND MCUSTP.COMMODITY_CODE = '".$commodity_like."'";
	}
	if($size_like != "All" && $size_like != ""){
		$sql .= " AND MCUSTP.SIZEID = '".$size_like."'";
	}
	$sql .= " ORDER BY ";
	if($sort_by != ""){
		$sql .= "MCUSTP.".$sort_by.",";
	}
	$sql .= " MCUSTP.CUSTOMERID, MCUSTP.COMMODITY_CODE, MCUSTP.SIZEID, EFFECTIVEDATE";
}
