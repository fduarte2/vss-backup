<?
	// NOTE:  due to information made available to us afte the fact, the "inbound PO" is
	// actually an OUTBOUND po.  but as the tables are set, it is easier just to leave the variable name as is.


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Walmart";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238"); echo "<font color=\"#FF0000\">Currently using the RF.TEST database!</font><br/>";
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$modify_cursor = ora_open($conn);
	$select_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Add DCPO"){
		$load_num = $HTTP_POST_VARS['load_num'];
		$new_dcpo = $HTTP_POST_VARS['new_dcpo'];

		$sql = "SELECT LOAD_NUM FROM WDI_LOAD_DCPO
				WHERE DCPO_NUM = '".$new_dcpo."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">Cannot Add DCPO# ".$new_dcpo." to Load# ".$load_num.",<br>It is already used on Load# ".$row['LOAD_NUM'].";<br>Please use your browser's back Button to return to the previous page.";
			exit;
		} else {
			$sql = "INSERT INTO WDI_LOAD_DCPO
						(LOAD_NUM,
						DCPO_NUM)
					VALUES
						('".$load_num."',
						'".$new_dcpo."')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
		}

		$dcpo_num = $new_dcpo;
	}

	if($submit == "Update Orders/Save"){
		$dcpo_num = $HTTP_POST_VARS['dcpo_num'];

		// single values
		$delv_date = $HTTP_POST_VARS['delv_date'];
		$sams_sc = $HTTP_POST_VARS['sams_sc'];
		$bkhl_traffic = $HTTP_POST_VARS['bkhl_traffic'];
		$vendor_name = $HTTP_POST_VARS['vendor_name'];
		$dest_id = $HTTP_POST_VARS['dest_id'];
		$temp_rec = $HTTP_POST_VARS['temp_rec'];
		$seal = $HTTP_POST_VARS['seal'];
//		$prod_of = $HTTP_POST_VARS['prod_of'];

		// arrays
		$item = $HTTP_POST_VARS['item'];
		$count = $HTTP_POST_VARS['count'];
		$cartons = $HTTP_POST_VARS['cartons'];
		$weight = $HTTP_POST_VARS['weight'];
		$prod_desc = $HTTP_POST_VARS['prod_desc'];
		$spec_inst = $HTTP_POST_VARS['spec_inst'];
		$truck_num = $HTTP_POST_VARS['truck_num'];

		$sql = "SELECT TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE FROM WDI_LOAD_HEADER
				WHERE LOAD_NUM IN
					(SELECT LOAD_NUM FROM WDI_LOAD_DCPO WHERE DCPO_NUM = '".$dcpo_num."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$expc_date = $row['THE_DATE'];

		// validate the rows before saving...
//		$result = all_rows_valid($item, $count, $cartons, $weight, $prod_desc, $spec_inst, $truck_num, $expc_date, $dcpo_num, $delv_date, $sams_sc, $bkhl_traffic, $vendor_name, $prod_of, $conn);
		$result = all_rows_valid($item, $count, $cartons, $weight, $prod_desc, $spec_inst, $truck_num, $expc_date, $dcpo_num, $delv_date, $sams_sc, $bkhl_traffic, $vendor_name, "", $conn);
		if($result == ""){
//						PRODUCTS_OF = '".$prod_of."'
			$sql = "UPDATE WDI_LOAD_DCPO SET 
						SAMS_SC = '".$sams_sc."',
						DELIVERY_DATE = TO_DATE('".$delv_date."', 'MM/DD/YYYY'),
						BKHL_TRAFFIC = '".$bkhl_traffic."',
						VENDOR_NAME = '".$vendor_name."',
						DEST_ID = '".$dest_id."',
						TEMP_RECORDER = '".$temp_rec."',
						TRUCK_SEAL = '".$seal."'
					WHERE DCPO_NUM = '".$dcpo_num."'";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
			$sql = "DELETE FROM WDI_LOAD_DCPO_ITEMNUMBERS WHERE DCPO_NUM = '".$dcpo_num."'";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

			for($i = 0; $i < 10; $i++){
				if($item[$i] != "" && $count[$i] != ""){
					$sql = "INSERT INTO WDI_LOAD_DCPO_ITEMNUMBERS 
								(DCPO_NUM, 
								ITEM_NUM, 
								PALLETS, 
								CASES,
								WEIGHT,
								PRODUCT_DESCRIPTION,
								SPECIAL_INSTRUCTIONS,
								TRUCK_NUM) 
							VALUES
								('".$dcpo_num."', 
								'".$item[$i]."', 
								'".$count[$i]."', 
								'".$cartons[$i]."', 
								'".$weight[$i]."', 
								'".$prod_desc[$i]."', 
								'".$spec_inst[$i]."',
								'".$truck_num[$i]."')";
					ora_parse($modify_cursor, $sql);
					ora_exec($modify_cursor);
				}
			}
		} else {
			// entered rows didn't pass mustard, display issue.
			echo $result;
		}
	}

	$sql = "SELECT TO_CHAR(DELIVERY_DATE, 'MM/DD/YYYY') THE_DELV, VENDOR_NAME, BKHL_TRAFFIC, SAMS_SC, DEST_ID, TEMP_RECORDER, TRUCK_SEAL, PRODUCTS_OF
			FROM WDI_LOAD_DCPO
			WHERE DCPO_NUM = '".$dcpo_num."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$sams_sc = $row['SAMS_SC'];
	$delv_date = $row['THE_DELV'];
	$bkhl_traffic = $row['BKHL_TRAFFIC'];
	$vendor_name = $row['VENDOR_NAME'];
	$dest_id = $row['DEST_ID'];
	$temp_rec = $row['TEMP_RECORDER'];
	$seal = $row['TRUCK_SEAL'];
//	$prod_of = $row['PRODUCTS_OF'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Projected DCPO Details for:  <? echo $dcpo_num; ?></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="update_form" action="walmart_proj_detail.php" method="post">
<input type="hidden" name="dcpo_num" value="<? echo $dcpo_num; ?>">
	<tr>
		<td width="15%">Delivery Date:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="delv_date" size="10" maxlength="10" value="<? echo $delv_date; ?>"><!--<a href="javascript:show_calendar('update_form.date_exp');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>!--></td><br><br>
	</tr>
	<tr>
		<td width="15%">SAMS/SC:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="sams_sc" size="5" maxlength="5" value="<? echo $sams_sc; ?>">
	</tr>
	<tr>
		<td width="15%">BKHL/Traffic:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="bkhl_traffic" size="10" maxlength="10" value="<? echo $bkhl_traffic; ?>">
	</tr>
	<tr>
		<td width="15%">Vendor Name:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="vendor_name" size="25" maxlength="25" value="<? echo $vendor_name; ?>">
	</tr>
	<tr>
		<td width="15%">Temp Recorder:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="temp_rec" size="20" maxlength="20" value="<? echo $temp_rec; ?>">
	</tr>
	<tr>
		<td width="15%">Seal#:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="seal" size="15" maxlength="15" value="<? echo $seal; ?>">
	</tr>
<!--	<tr>
		<td width="15%">Product Of:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="prod_of" size="15" maxlength="40" value="<? echo $prod_of; ?>">
	</tr> !-->
	<tr>
		<td width="15%">Destination:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><select name="dest_id"><option value="">Select a Destination:</option>
<?
		$sql = "SELECT DEST_ID, DEST_ADDR1, DEST_CITY, DEST_STATE FROM WDI_DESTINATION 
				ORDER BY DEST_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['DEST_ID']; ?>" <? if($row['DEST_ID'] == $dest_id){ ?> selected <?}?>><? echo $row['DEST_ID']." - ".$row['DEST_ADDR1']." - ".$row['DEST_CITY']." - ".$row['DEST_STATE']; ?></option> 
<?
		}
?>
				</select></td>

	</tr>
		
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>Item #</td>
		<td># Pallets</td>
		<td># Cartons</td>
		<td>Weight</td>
		<td>Product Description</td>
		<td>Special Instructions</td>
		<td>Truck#</td>
	</tr>
<?
	$row_num = 0;
	$sql = "SELECT PALLETS, ITEM_NUM, CASES, WEIGHT, PRODUCT_DESCRIPTION, SPECIAL_INSTRUCTIONS, TRUCK_NUM
			FROM WDI_LOAD_DCPO_ITEMNUMBERS
			WHERE DCPO_NUM = '".$dcpo_num."'
			ORDER BY ITEM_NUM";
	ora_parse($select_cursor, $sql);
	ora_exec($select_cursor);
	while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><select name="item[<? echo $row_num; ?>]">
						<option value="">Select an Item#</option>
<?
		$sql = "SELECT DISTINCT WM_ITEM_NUM FROM WM_ITEMNUM_MAPPING 
				ORDER BY WM_ITEM_NUM";
	/*		$sql = "SELECT DISTINCT WDI_OUTGOING_ITEM_NUM FROM WDI_ADDITIONAL_DATA WHERE WDI_PALLET_ID IN
				(SELECT PALLET_ID FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0)
				ORDER BY WDI_OUTGOING_ITEM_NUM"; */
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['WM_ITEM_NUM']; ?>" <? if($row['WM_ITEM_NUM'] == $select_row['ITEM_NUM']){ ?> selected <?}?>><? echo $row['WM_ITEM_NUM']; ?></option>
<?
		}
?>
				</select></td>
		<td><input name="count[<? echo $row_num; ?>]" type="text" size="5" maxlength="5" value="<? echo $select_row['PALLETS']; ?>"></td>
		<td><input name="cartons[<? echo $row_num; ?>]" type="text" size="5" maxlength="5" value="<? echo $select_row['CASES']; ?>"></td>
		<td><input name="weight[<? echo $row_num; ?>]" type="text" size="7" maxlength="7" value="<? echo $select_row['WEIGHT']; ?>"></td>
		<td><input name="prod_desc[<? echo $row_num; ?>]" type="text" size="20" maxlength="30" value="<? echo $select_row['PRODUCT_DESCRIPTION']; ?>"></td>
		<td><input name="spec_inst[<? echo $row_num; ?>]" type="text" size="20" maxlength="50" value="<? echo $select_row['SPECIAL_INSTRUCTIONS']; ?>"></td>
		<td><input name="truck_num[<? echo $row_num; ?>]" type="text" size="2" maxlength="2" value="<? echo $select_row['TRUCK_NUM']; ?>"></td>
	</tr>
<?
		$row_num++;
	}

	for($i = $row_num; $i < 10; $i++){
?>
	<tr>
		<td><select name="item[<? echo $i; ?>]">
						<option value="">Select an Item#</option>
<?
		$sql = "SELECT DISTINCT WM_ITEM_NUM FROM WM_ITEMNUM_MAPPING 
				ORDER BY WM_ITEM_NUM";
	/*		$sql = "SELECT DISTINCT WDI_OUTGOING_ITEM_NUM FROM WDI_ADDITIONAL_DATA WHERE WDI_PALLET_ID IN
				(SELECT PALLET_ID FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0)
				ORDER BY WDI_OUTGOING_ITEM_NUM"; */
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['WM_ITEM_NUM']; ?>"><? echo $row['WM_ITEM_NUM']; ?></option>
<?
		}
?>
				</select></td>
		<td><input name="count[<? echo $i; ?>]" type="text" size="5" maxlength="5" value=""></td>
		<td><input name="cartons[<? echo $i; ?>]" type="text" size="5" maxlength="5" value=""></td>
		<td><input name="weight[<? echo $i; ?>]" type="text" size="7" maxlength="7" value=""></td>
		<td><input name="prod_desc[<? echo $i; ?>]" type="text" size="20" maxlength="30" value=""></td>
		<td><input name="spec_inst[<? echo $i; ?>]" type="text" size="20" maxlength="50" value=""></td>
		<td><input name="truck_num[<? echo $i; ?>]" type="text" size="2" maxlength="2" value=""></td>
	</tr>
<?
	}
?>
	
	
	<tr>
		<td colspan="7"><input type="submit" name="submit" value="Update Orders/Save"></td>
	</tr>
</form>
<form name="return" action="walmart_proj.php" method="post">
<input type="hidden" name="order_num" value="<? echo $dcpo_num; ?>">
	<tr>
		<td colspan="2"><input name="submit" type="submit" value="Return To Load Screen"></td>
		<td align="center" colspan="3"><a target="walmart_proj_bol.php?dcpo=<? echo $dcpo_num; ?>" href="walmart_proj_bol.php?dcpo=<? echo $dcpo_num; ?>">Print BoL</a></td>
		<td align="right" colspan="2"><a target="walmart_proj_picklist.php?dcpo=<? echo $dcpo_num; ?>" href="walmart_proj_picklist.php?dcpo=<? echo $dcpo_num; ?>">Print Picklist</a></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");



function all_rows_valid($item, $count, $cartons, $weight, $prod_desc, $spec_inst, $truck_num, $date_exp, $order_num, $delv_date, $sams_sc, $bkhl_traffic, $vendor_name, $prod_of, $conn){
	$Short_Term_Cursor = ora_open($conn);
	$select_cursor = ora_open($conn);

	$return = "";

	// validity-check everything to make sure the entered values aren't objectable (strigns in dates, or whatnot)
	if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $delv_date)) {
		$return .= "<font size=\"3\" color=\"#FF0000\">Delivery Date must be in MM/DD/YYYY format. - ".$delv_date." Invalid</font><br>";
	}
	if($sams_sc != "" && !ereg("^([0-9a-zA-Z _-])+$", $sams_sc)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Sams/SC field. - ".$sams_sc." unusable</font><br>";
	}
	if($bkhl_traffic != "" && !ereg("^([a-zA-Z0-9])+$", $bkhl_traffic)){
		$return .= "<font size=\"3\" color=\"#FF0000\">BKHL/Traffic Field must be alphanumeric. - ".$bkhl_traffic." unusable</font><br>";
	}
	if(!ereg("^([0-9a-zA-Z _-])*$", $vendor_name)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Vendor Name. - ".$vendor_name." unusable</font><br>";
	}
/*	if(!ereg("^([0-9a-zA-Z _-])*$", $prod_of)){
		$return .= "<font size=\"3\" color=\"#FF0000\">Invalid Characters found in Product-Of field. - ".$prod_of." unusable</font><br>";
	} */

	for($i = 0; $i < 10; $i++){
		$badline = $i + 1;

		// dont care about blank lines...
		if($item[$i] != "" && $count[$i] != ""){
			// cases
			if(!ereg("^([0-9])+$", $cartons[$i])){
				$return .= "<font size=\"3\" color=\"#FF0000\">Line: ".$badline." - Cases Count must be numeric and non-empty.  (entered:  ".$cartons[$i].")</font><br>";
			}
			if(!ereg("^([0-9])+$", $count[$i])){
				$return .= "<font size=\"3\" color=\"#FF0000\">Line: ".$badline." - Pallet Count must be numeric and non-empty.  (entered:  ".$count[$i].")</font><br>";
			}
			if($weight[$i] != "" && !ereg("^([0-9])+$", $weight[$i])){
				$return .= "<font size=\"3\" color=\"#FF0000\">Line: ".$badline." - Weight Field must be numeric.  (entered:  ".$weight[$i].")</font><br>";
			}
			if(!ereg("^([0-9a-zA-Z _`/-])+$", $prod_desc[$i])){
				$return .= "<font size=\"3\" color=\"#FF0000\">Line: ".$badline." - Invalid Characters found in Product Description.  (entered:  ".$prod_desc[$i].")</font><br>";
			}
			if(!ereg("^([0-9a-zA-Z _`/-])*$", $spec_inst[$i])){
				$return .= "<font size=\"3\" color=\"#FF0000\">Line: ".$badline." - Invalid Characters found in Special Instructions.  (entered:  ".$spec_inst[$i].")</font><br>";
			}
			if($truck_num[$i] != "" && !ereg("^([0-9])+$", $truck_num[$i])){
				$return .= "<font size=\"3\" color=\"#FF0000\">Line: ".$badline." - TRUCK# must be numeric.  (entered:  ".$truck_num[$i].")</font><br>";
			}
		}
	}


	// if the above was checked out, do the below...
	if($return == ""){
		// 10 lines of possible shipments, for each line...
		for($i = 0; $i < 10; $i++){

			// only need to check if the line has data in it
			if($item[$i] != "" && $count[$i] != ""){

				$IH = 0;
				$pending_orders = 0;
				$expected = 0;
				$scanned_on_pending = 0;

				$available = GetValidCount($IH, $pending_orders, $expected, $scanned_on_pending, $item[$i], $date_exp, $order_num, $conn);

				if($count[$i] > $available){
					$return .= "<font size=\"3\" color=\"#FF0000\">Line #".($i + 1)." has prevented order from saving; insufficient quantity.<br></font>
								<table border=\"1\" cellpadding=\"2\">
									<tr>
										<td colspan=\"2\" align=\"center\"><font color=\"#FF0000\" size=\"2\"><b>ITEM ".$item[$i]."</b></font></td>
									</tr>
									<tr>
										<td><font color=\"#FF0000\" size=\"2\">In House:</td>
										<td><a href=\"IH_det.php?itemnum=".$item[$i]."\" target=\"IH_det.php?itemnum=".$item[$i]."\">".$IH."</a></td>
									</tr>
									<tr>
										<td><font color=\"#FF0000\" size=\"2\">Expected By ".$date_exp.":</td>
										<td><a href=\"exp_det.php?itemnum=".$item[$i]."&date=".$date_exp."\" target=\"exp_det.php?itemnum=".$item[$i]."&date=".$date_exp."\">".$expected."</a></td>
									</tr>
									<tr>
										<td><font color=\"#FF0000\" size=\"2\">Already Reserved:</td>
										<td><a href=\"proj_det.php?itemnum=".$item[$i]."&order=".$order_num."\" target=\"proj_det.php?itemnum=".$item[$i]."&order=".$order_num."\">".$pending_orders."</a></td>
									</tr>
									<tr>
										<td><font color=\"#FF0000\" size=\"2\">Reserved Orders Already Scanned:</td>
										<td><a href=\"ship_det.php?itemnum=".$item[$i]."&order=".$order_num."\" target=\"ship_det.php?itemnum=".$item[$i]."&order=".$order_num."\">".$scanned_on_pending."</a></td>
									</tr>
									<tr>
										<td><font color=\"#FF0000\" size=\"2\"><b>AVAILABLE:</b></td>
										<td><font color=\"#FF0000\" size=\"2\"><b>".$available."</b></td>
									</tr>
									<tr>
										<td><font color=\"#FF0000\" size=\"2\"><b>ATTEMPTED TO ORDER:</b></td>
										<td><font color=\"#FF0000\" size=\"2\"><b>".$count[$i]."</b></td>
									</tr>
								</table><br>";
				}
			}
		}
	}

	return $return;
}


function GetValidCount(&$IH, &$pending_orders, &$expected, &$scanned_on_pending, $itemnum, $date_exp, $order_num, $conn){
	$Short_Term_Cursor = ora_open($conn);
	$select_cursor = ora_open($conn);

	// in house, fairly straightforward.
	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
			WHERE CT.RECEIVER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM IN
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
				AND DATE_RECEIVED IS NOT NULL
				AND BOL = '".$itemnum."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$IH = $row['THE_COUNT'];

	// pallets not yet "ours"
	//DATE_DEPARTED >= SYSDATE AND 
	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
			WHERE CT.RECEIVER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM NOT IN
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND CT.ARRIVAL_NUM IN
					(SELECT TO_CHAR(LR_NUM) FROM VOYAGE
						WHERE DATE_DEPARTED <= TO_DATE('".$date_exp."', 'MM/DD/YYYY')
					)
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
				AND BOL = '".$itemnum."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$expected = $row['THE_COUNT'];

	// ALL orders today or later 
	// (sysdate - 1 because all dates are timeless, so anytime the day before will "=" the next day)
	// (we'll worry about scanned-complete ones in next step)
	$sql = "SELECT NVL(SUM(PALLETS), 0) THE_COUNT
			FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
			WHERE WLDI.DCPO_NUM = WLD.DCPO_NUM
				AND WLD.LOAD_NUM = WLH.LOAD_NUM
				AND WLH.STATUS = 'ACTIVE'
				AND WLH.LOAD_DATE >= (SYSDATE - 1)
				AND WLDI.ITEM_NUM = '".$itemnum."'
				AND WLDI.DCPO_NUM != '".$order_num."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$pending_orders = $row['THE_COUNT'];

	// pallet count scanned out on orders WITHIN the orders in the "$pending_orders" list
//				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
	$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_COUNT
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CUSTOMER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND ORDER_NUM IN
					(SELECT TO_CHAR(WLDI.DCPO_NUM)
					FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
					WHERE WLDI.DCPO_NUM = WLD.DCPO_NUM
						AND WLD.LOAD_NUM = WLH.LOAD_NUM
						AND WLH.STATUS = 'ACTIVE'
						AND WLH.LOAD_DATE >= (SYSDATE - 1)
						AND WLDI.ITEM_NUM = '".$itemnum."'
						AND WLDI.DCPO_NUM != '".$order_num."')
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				AND CT.BOL = '".$itemnum."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$scanned_on_pending = $row['THE_COUNT'];

	$available_amount = ($IH + $expected) - ($pending_orders - $scanned_on_pending);

	return $available_amount;
}