<?
/*
*	Adam Walter, Nov 23, 2007.
*
*	This webpage is used to both display a picklist, AND create it.
*	Exact specifications signed off on by Marty,
*	And can be found in my documents folder on my PC.
*
*************************************************************************/


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

	$submit = $HTTP_POST_VARS['submit'];
	$order_num = $HTTP_POST_VARS['order_num'];
	$cannot_continue = "";
	$incomplete_orders = "";
	$to_be_checked_orders = array();
	$to_be_checked_counter = 0;



	// a couple validity checks done on order_num
	$sql = "SELECT COUNT(*) THE_COUNT FROM DC_ORDER WHERE TRIM(ORDERNUM) = '".$order_num."' AND ORDERSTATUSID IN ('3', '4', '5', '7')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] == 0){
		$cannot_continue = "This order already completed.  No picklist to display.";
	}	

	$sql = "SELECT COUNT(*) THE_COUNT FROM DC_ORDER WHERE TRIM(ORDERNUM) = '".$order_num."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] == 0){
		$cannot_continue = "This order not in system; no picklist created/displayed.";
	}

	if(substr($order_num, 0, 2) != "LD"){
		$cannot_continue = "LD removed from order number.  Cancelling Picklist Creation.";
	}


	if($cannot_continue == ""){
		// with validity checks done, begin page creation logic.
		// Step 1:  Remove existing picklist to make way for new one.
		$sql = "DELETE FROM DC_DOMESTIC_PICKLIST WHERE TRIM(ORDERNUM) = '".$order_num."'";
//		echo $sql."<BR>";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		// Step 2:  Get key order information
		// We know the order exists and is not complete by previous validity checks.
		$sql = "SELECT * FROM DC_ORDER WHERE TRIM(ORDERNUM) = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$load_type = $Short_Term_row['LOADTYPE'];
		switch($load_type){
			case "CUSTOMER LOAD":
				$extra_sql = "IS NULL";
				break;
			case "REGRADE LOAD":
				$extra_sql = "IN ('R', 'B')";
				break;
			case "HOSPITAL LOAD":
				$extra_sql = "IN ('H', 'B')";
				break;
		}
		$comm = $Short_Term_row['COMMODITYCODE'];
		$cust = $Short_Term_row['CUSTOMERID'];

		// Step 3:  get array of all size/quantities on order.
		// We know the order exists and is not complete by previous validity checks.
		$sql = "SELECT ORDERDETAILID || ',' || ORDERQTY || ',' || SIZELOW THE_DETAIL FROM DC_ORDERDETAIL WHERE TRIM(ORDERNUM) = '".$order_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$to_be_checked_orders[$to_be_checked_counter] = $Short_Term_row['THE_DETAIL'];
			$to_be_checked_counter++;
		}

		// Step 4:  Loop for each entry in Order Detail
		// NOTE:  against previous specs, the orders will NOT be placed in pallets, but in cases.
		// as such, I have to convert the "pallets_requested" variable to actual pallets
		// done via the switch statement.  Not 100% precise, unfortunately.
		for($outer_loop = 0; $outer_loop < $to_be_checked_counter; $outer_loop++){
			$temp = split(",", $to_be_checked_orders[$outer_loop]);
			$order_detail = $temp[0];
			$pallets_requested = $temp[1];
			$requested_size = $temp[2];

			switch ($requested_size){
				case 18:
					$pallets_requested = ceil($pallets_requested / 360);
					break;
				case 20:
					$pallets_requested = ceil($pallets_requested / 360);
					break;
				case 24:
					$pallets_requested = ceil($pallets_requested / 380);
					break;
				case 28:
					$pallets_requested = ceil($pallets_requested / 380);
					break;
				default:
					$pallets_requested = ceil($pallets_requested / 380);
			}

			// Step 4a:  Get all "available" pallets for each container.
			// I.E. status = "AT POW"
			// IMPORTANT NOTE:  this code only good for 26 containers, alphabetically.
			$sql = "SELECT COUNT(*) THE_COUNT, SUBSTR(CONTAINER_ID, 1, 1) THE_EARLIEST, EXPORTER_CODE
					FROM CARGO_TRACKING
					WHERE DATE_RECEIVED IS NOT NULL
					AND CONTAINER_ID IS NOT NULL
					AND COMMODITY_CODE = '".$comm."'
					AND CARGO_SIZE = '".$requested_size."'
					AND DATE_RECEIVED > '01-oct-2007'
					AND (MARK IN ('AT POW', 'AT_POW') OR (MARK = 'SHIPPED' AND (QTY_IN_HOUSE / BATCH_ID) >= 0.9))
					AND CARGO_STATUS ".$extra_sql."
					AND RECEIVER_ID = '".$cust."'
					GROUP BY SUBSTR(CONTAINER_ID, 1, 1), EXPORTER_CODE
					ORDER BY THE_EARLIEST";
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);
			while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC) && $pallets_requested > 0){
				// Step 4b:  For each container with "available" pallets, and while more are still needed,
				// figure out how many are truly available by subtracting
				// (reserved pallets - pallets already scanned to order) from "available" pallets
				$container_check = $first_row['THE_EARLIEST'];
				$PKG_house_check = $first_row['EXPORTER_CODE'];
				$pre_count = $first_row['THE_COUNT'];
//				echo $container_check." ".$PKG_house_check." ".$pre_count."<BR>";

				$sql = "SELECT NVL(SUM(PALLETQTY), 0) THE_SUM FROM DC_DOMESTIC_PICKLIST DDP, DC_ORDER DCO WHERE DCO.ORDERNUM = DDP.ORDERNUM AND DCO.LOADTYPE = '".$load_type."' AND DCO.ORDERSTATUSID IN ('3', '4', '5', '7') AND CONTAINER_ID = '".$container_check."' AND PACKINGHOUSE = '".$PKG_house_check."' AND PICKLISTSIZE = '".$requested_size."' AND CUSTOMERID = '".$cust."' AND COMMODITYCODE = '".$comm."'";
				ora_parse($cursor_second, $sql);
				ora_exec($cursor_second);
				ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$picklist_reserved = $second_row['THE_SUM'];
//				echo $picklist_reserved."<BR>";

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND SUBSTR(CT.CONTAINER_ID, 1, 1) = '".$container_check."' AND CT.EXPORTER_CODE = '".$PKG_house_check."' AND CA.SERVICE_CODE = '6' AND SUBSTR(CA.ACTIVITY_DESCRIPTION, 1, 3) = 'DMG' AND CT.CARGO_SIZE = '".$requested_size."' AND ORDER_NUM IN (SELECT TRIM(ORDERNUM) FROM DC_ORDER WHERE ORDERSTATUSID IN ('3', '4', '5', '7') AND LOADTYPE = '".$load_type."' AND CUSTOMERID = '".$cust."')";
//				echo $sql."<BR>";
				ora_parse($cursor_third, $sql);
				ora_exec($cursor_third);
				ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$picklist_reserved_already_scanned = $third_row['THE_COUNT'];
//				echo $picklist_reserved_already_scanned."<BR>";

				$available_pallets_on_this_combination = $pre_count - ($picklist_reserved - $picklist_reserved_already_scanned);


				// Step 4c:  Apply available pallet count to requested pallets				
				if($available_pallets_on_this_combination <= 0){
					// Step 4c1:  if no palets available for this combo:  pass over
					// should never be < 0, but just in case, if statement includes it.
				} else{
					// Step 4c2:  if available pallets exist...
					$sql = "SELECT NVL(MAX(PICKLISTID), 0) + 1 THE_MAX FROM DC_DOMESTIC_PICKLIST WHERE TRIM(ORDERNUM) = '".$order_num."' AND ORDERDETAILID = '".$order_detail."'";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
					ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$next_id = $Short_Term_row['THE_MAX'];

					if($available_pallets_on_this_combination >= $pallets_requested){
						// Step 4c2-1:  we have plenty, assign full order.
						$sql = "INSERT INTO DC_DOMESTIC_PICKLIST (ORDERNUM, ORDERDETAILID, PICKLISTID, CONTAINER_ID, PACKINGHOUSE, PALLETQTY, PICKLISTSIZE) VALUES ('".$order_num."', '".$order_detail."', '".$next_id."', '".$container_check."', '".$PKG_house_check."', '".$pallets_requested."', '".$requested_size."')";
//						echo $sql."aa<BR>";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						$pallets_requested = 0;
					} else {
						// Step 4c2-2:  we have some, but need more
						$sql = "INSERT INTO DC_DOMESTIC_PICKLIST (ORDERNUM, ORDERDETAILID, PICKLISTID, CONTAINER_ID, PACKINGHOUSE, PALLETQTY, PICKLISTSIZE) VALUES ('".$order_num."', '".$order_detail."', '".$next_id."', '".$container_check."', '".$PKG_house_check."', '".$available_pallets_on_this_combination."', '".$requested_size."')";
//						echo $sql."bb<BR>";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						$pallets_requested -= $available_pallets_on_this_combination;
					}
				}
			}

			if($pallets_requested > 0){
				$incomplete_orders .= "Order ".$order_num.", Detail line ".$order_detail." (Size ".$requested_size.") was unable to prefill ".$pallets_requested." pallets.<BR>";
			}
		}
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Domestic Clementine Picklist Locations
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_pick" action="domestic_picklist.php" method="post">
<!--	<tr>
		<td align="left"><font size="2" face="Verdana" color="#CCAA00"><a href="domestic_picklist.php">Test Refresh</a></font></td>
	</tr> !-->
	<tr>
		<td align="center"><font size="2" face="Verdana">Order #:</font></td>
	</tr>
	<tr>
		<td align="center"><input type="text" name="order_num" size="10" maxlength="12" value="LD"></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Display"></td>
	</tr>
	<tr>
		<td>&nbsp;<HR>&nbsp;</td>
	</tr>
<?
	if($submit == "Display" && $cannot_continue != ""){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000"><? echo $cannot_continue; ?></font></td>
	</tr>
<?
	}
?>
</form>
</table>





<?
	// bottom page part, only displayed if top part all checks out.  Order Header Information displayed.
	if($submit == "Display" && $cannot_continue == ""){
		$sql = "SELECT ORDERSTATUSID, LOADTYPE FROM DC_ORDER WHERE TRIM(ORDERNUM) = '".$order_num."'";
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
		$load_type = $row['LOADTYPE'];
		switch($load_type){
			case "CUSTOMER LOAD":
				$extra_sql = "IS NULL";
				break;
			case "REGRADE LOAD":
				$extra_sql = "IN ('R', 'B')";
				break;
			case "HOSPITAL LOAD":
				$extra_sql = "IN ('H', 'B')";
				break;
		}

		$sql = "SELECT * FROM DC_ORDER WHERE TRIM(ORDERNUM) = '".$order_num."'";
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
<?
	if($incomplete_orders != ""){
?>
	<tr>
		<td colspan="7" align="center"><font size="2" face="Verdana" color="#FF0000"><? echo $incomplete_orders; ?></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="7" align="center">&nbsp;</font></td>
	</tr>
</table>

<?
		// bottom page part, only displayed if top part all checks out.  Order Detail Information displayed.
		$sql = "SELECT SIZELOW, VESSELID, COMMODITYCODE, CUSTOMERID, DCOD.ORDERDETAILID THE_DET FROM DC_ORDER DCO, DC_ORDERDETAIL DCOD WHERE DCO.ORDERNUM = DCOD.ORDERNUM AND TRIM(DCO.ORDERNUM) = '".$order_num."' ORDER BY DCOD.ORDERDETAILID";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$the_size = $first_row['SIZELOW'];
//			$sizehigh = $first_row['SIZEHIGH'];
			$commodity = $first_row['COMMODITYCODE'];
			$vessel = $first_row['VESSELID'];
			$detail_id = $first_row['THE_DET'];
			$customer = $first_row['CUSTOMERID'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="4" width="100%" align="center"><font size="4" face="Verdana">Size:  <? echo $the_size; ?></font></td>
	</tr>
</table>
<?
			// bottom page part, only displayed if top part all checks out.  Picklist-per-detail Information displayed.
			$sql = "SELECT * FROM DC_DOMESTIC_PICKLIST WHERE TRIM(ORDERNUM) = '".$order_num."' AND ORDERDETAILID = '".$detail_id."' ORDER BY PICKLISTID";
//			echo $sql."<BR>";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			if(!ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="4" width="100%" align="center"><font size="2" face="Verdana">No Picklist Available.</font></td>
	</tr>
</table>
<?
			} else {
				do {
					$container_id = substr($second_row['CONTAINER_ID'], 0, 1);
	//				$sizeactual = $second_row['PALLET_LOCATION'];
					$PKG = trim($second_row['PACKINGHOUSE']);
	//				$requested_pal = $second_row['PALLET_QTY'];
					$sizeactual = $second_row['PICKLISTSIZE'];
	//				$PKG = trim($second_row['PACKHOUSEID']);
					$requested_pal = $second_row['PALLETQTY'];
	//				echo $sizeactual."PL<BR>".$PKG."PK<BR>".$requested_pal."RP<BR>";
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="4" align="center" width="100%"><font size="3" face="Verdana">Pick <b><? echo $requested_pal; ?></b> Pallets from the following:</font></td>
	</tr>
	<tr>		
		<td><font size="2" face="Verdana"><b>PKG House</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Size (Actual)</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Container ID</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Pallets Requested</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Location</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallets Available</b></font></td>
	</tr>
<?
//					$displayed_pallets = 0;

					$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(WAREHOUSE_LOCATION, 'UNSPECIFIED') THE_LOC FROM CARGO_TRACKING WHERE COMMODITY_CODE = '".$commodity."' AND ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$customer."' AND (MARK IN ('AT POW', 'AT_POW') OR (MARK = 'SHIPPED' AND (QTY_IN_HOUSE / BATCH_ID) >= 0.9)) AND CARGO_SIZE = '".$sizeactual."' AND EXPORTER_CODE = '".$PKG."' AND SUBSTR(CONTAINER_ID, 1, 1) = '".$container_id."' AND CARGO_STATUS ".$extra_sql." GROUP BY WAREHOUSE_LOCATION ORDER BY THE_COUNT, WAREHOUSE_LOCATION";
//					echo $sql."<BR>";
					ora_parse($cursor_third, $sql);
					ora_exec($cursor_third);
					while(ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $PKG; ?></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $sizeactual; ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo $container_id; ?></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $requested_pal; ?></font></td> !-->
		<td><font size="2" face="Verdana"><? echo $third_row['THE_LOC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_COUNT']; ?></font></td>
	</tr>
<?
					}
?>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="100%">&nbsp;<BR></td>
	</tr>
</table>
<?
				} while (ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}
		}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="100%">&nbsp;<BR></td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>
