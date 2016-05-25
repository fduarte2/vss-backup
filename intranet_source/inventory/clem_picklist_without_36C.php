<?
/*
*	Adam Walter, October 2007
*
*	This page takes as input a Clementine Order number (2007 season format)
*	And returns a list of packinghouses on the order, and locations of
*	Pallets that fit the criteria
********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$order_present = true;
	if($submit == "Retrieve"){
		$sql = "SELECT * FROM DC_ORDER WHERE ORDERNUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$order_present = false;
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Clementine Picklist Locations
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_pick" action="clem_picklist_loc.php" method="post">
	<tr>
		<td align="center"><font size="2" face="Verdana">Order #:</font></td>
	</tr>
	<tr>
		<td align="center"><input type="text" name="order_num" size="10" maxlength="12"></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td>&nbsp;<HR>&nbsp;</td>
	</tr>
<?
	if($submit == "Retrieve" && !$order_present){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">Order not present in System.  Please call TS if this is in error.</font></td>
	</tr>
<?
	}
?>
</form>
</table>
<?
	if($order_num != "" && $order_present){
		$sql = "SELECT ORDERSTATUSID FROM DC_ORDER WHERE ORDERNUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		switch($row['ORDERSTATUSID']){
			case "3":
				$status = "Pick List Complete";
				break;
			case "4":
				$status = "Loading";
				break;
			case "7":
				$status = "Revised Pick List Complete";
				break;
			case "8":
				$status = "Confirmed";
				break;
			case "9":
				$status = "Order Complete";
				break;
		}

		$sql = "SELECT * FROM DC_ORDER WHERE ORDERNUM = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$commodity = $row['COMMODITYCODE'];
		$vessel = $row['VESSELID'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="7" align="center"><font size="4" face="Verdana">PickList Locations</font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3" face="Verdana">Order Number:  <? echo $order_num; ?></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="2" face="Verdana">Status:  <? echo $status; ?></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center">&nbsp;</font></td>
	</tr>
</table>
<?
		$sql = "SELECT SIZELOW, SIZEHIGH, VESSELID, COMMODITYCODE, DCOD.ORDERDETAILID THE_DET FROM DC_ORDER DCO, DC_ORDERDETAIL DCOD WHERE DCO.ORDERNUM = DCOD.ORDERNUM AND DCO.ORDERNUM = '".$order_num."' ORDER BY DCOD.ORDERDETAILID";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sizelow = $first_row['SIZELOW'];
			$sizehigh = $first_row['SIZEHIGH'];
			$commodity = $first_row['COMMODITYCODE'];
			$vessel = $first_row['VESSELID'];
			$detail_id = $first_row['THE_DET'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="100%"><font size="3" face="Verdana">Size (Low):  <? echo $sizelow; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Size (High):  <? echo $sizehigh; ?></font></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>PKG House</b></font></td>
		<td><font size="2" face="Verdana"><b>Size (Actual)</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets Requested</b></font></td>
		<td><font size="2" face="Verdana"><b>Location</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets Available</b></font></td>
	</tr>
<?
			$sql = "SELECT * FROM DC_PICKLIST WHERE ORDERNUM = '".$order_num."' AND ORDERDETAILID = '".$detail_id."' ORDER BY PICKLISTID";
//			echo $sql."<BR>";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			while(ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//				$sizeactual = $second_row['PALLET_LOCATION'];
				$PKG = trim($second_row['PACKINGHOUSE']);
//				$requested_pal = $second_row['PALLET_QTY'];
				$sizeactual = $second_row['PICKLISTSIZE'];
//				$PKG = trim($second_row['PACKHOUSEID']);
				$requested_pal = $second_row['PALLETQTY'];
//				echo $sizeactual."PL<BR>".$PKG."PK<BR>".$requested_pal."RP<BR>";

				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(WAREHOUSE_LOCATION, 'UNSPECIFIED') THE_LOC FROM CARGO_TRACKING WHERE COMMODITY_CODE = '".$commodity."' AND ARRIVAL_NUM = '".$vessel."' AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_SIZE = '".$sizeactual."' AND EXPORTER_CODE = '".$PKG."' GROUP BY WAREHOUSE_LOCATION ORDER BY WAREHOUSE_LOCATION";
//				echo $sql."<BR>";
				ora_parse($cursor_third, $sql);
				ora_exec($cursor_third);
				while(ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $PKG; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $sizeactual; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $requested_pal; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_LOC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_COUNT']; ?></font></td>
	</tr>
<?
				}
			}
?>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="100%">&nbsp;<BR></td>
	</tr>
</table>
<?
		}
	}
	include("pow_footer.php");
?>
