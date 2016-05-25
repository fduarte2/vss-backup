<?
/*
*	Adam Walter, Feb 20, 2008.
*
*	This page is a one-stop shop for Chilean Data.
*	Displays pallets and commoditites (all),
*	Date and qty received (only for those received),
*	And date and quantity shipped (only for those with activity records)
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
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
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

	if($submit != ""){

		// all validity checks done on previous page.  If we are in this part, start the update.
		$upload_num = $HTTP_POST_VARS['upload_num'];
		$requested = array();
		$itemnum = array();
		$loaddate = array();
		$i = 0;

		// get item-date combinations...
		$sql = "SELECT SUM(PALLETS) THE_PLT, ITEM_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE
				FROM WDI_LOADFORM_RAWDUMP 
				WHERE UPLOAD_NUM = '".$upload_num."'
				GROUP BY ITEM_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$requested[$i] = $Short_Term_Row['THE_PLT'];
			$itemnum[$i] = $Short_Term_Row['ITEM_NUM'];
			$loaddate[$i] = $Short_Term_Row['THE_DATE'];
			$i++;
		}

		$result_table = "<table border=\"1\"><tr>
							<td><font size=\"2\" face=\"Verdana\"><b>Item#</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Date Requested</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Pallets Requested</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>Pallets Available</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>PO#s</b></font></td>
						</tr>";

		// for each combination...
		for($i = 0; $i < sizeof($itemnum); $i++){
			// figure out if we have enough...
			$available[$i] = GetAvailable($upload_num, $itemnum[$i], $loaddate[$i], $conn, $Short_Term_Cursor);

			if($available[$i] < $requested[$i]){
				// if we do not have enough for this combo...
				$color = "#FF0000";
				$header_message = "Orders Skipped due to Overbooked Item:<br>";
			} else {
				// if we have enough for this combo...
				$color = "#0000FF";
				$header_message = "Order Projections Saved:<br>";
				UpdateItems($upload_num, $itemnum[$i], $loaddate[$i], $conn);
			}
			$result_table .= "<tr>
				<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$itemnum[$i]."</font></td>
				<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$loaddate[$i]."</font></td>
				<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$requested[$i]."</font></td>
				<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$available[$i]."</font></td>
				<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$header_message.GetOrders($upload_num, $itemnum[$i], $loaddate[$i], $conn, $Short_Term_Cursor)."</font></td>
							</tr>";

		}
		$result_table .= "</table><br><br>";

		echo "<font size=\"3\" face=\"Verdana\"><b>File Committed.  Results:<br></font>".$result_table;
		UpdateOrders($upload_num, $conn);
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">WM Projected Order Upload</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<!-- <font size="2" face="Verdana"><a href="ImportXLSInstructions.doc">(File Instructions)</a></font>!-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="upload_form" action="WM_order_auto_confirm.php" method="post">
	<tr>
		<td align="left"><font size="3" face="Verdana">Select Excel File:</font>&nbsp;&nbsp;<font size="2" face="Verdana"><a href="ImportXLSInstructionsForWMUpload.doc">(File Instructions)</a></font>&nbsp;&nbsp;<font size="2" face="Verdana"><a href="SampleForWMLoadProcess.xls">(Sample Spreadsheet)</a></font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"<? if($user_cust_num == "3000"){?> disabled <?}?>></td>
	</tr>
</form>
</table>

<?
	include("pow_footer.php");






function UpdateItems($upload_num, $itemnum, $loaddate, $conn){
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

/*
				AND LOAD_NUM IN
					(SELECT LOAD_NUM 
					FROM WDI_LOAD_HEADER
					WHERE LOAD_DATE = TO_DATE('".$loaddate."', 'MM/DD/YYYY')
					)
*/
	// remove all previous PO-item combinations that fit the item-date combination specified
	$sql = "DELETE FROM WDI_LOAD_DCPO_ITEMNUMBERS
			WHERE ITEM_NUM = '".$itemnum."'
				AND DCPO_NUM IN
					(SELECT DCPO_NUM
					FROM WDI_LOADFORM_RAWDUMP
					WHERE UPLOAD_NUM = '".$upload_num."'
					)";
	ora_parse($mod_cursor, $sql);
	ora_exec($mod_cursor);

	// add in fresh line-orders
	$sql = "INSERT INTO WDI_LOAD_DCPO_ITEMNUMBERS
				(LOAD_NUM,
				TRUCK_NUM,
				DCPO_NUM,
				ITEM_NUM,
				CASES,
				BINS,
				PALLETS,
				WEIGHT,
				PRODUCT_DESCRIPTION,
				SPECIAL_INSTRUCTIONS)
			(SELECT 
				LOAD_NUM,
				TRUCK_NUM,
				DCPO_NUM,
				ITEM_NUM,
				DECODE(PALLETS, 0, 0, CASES),
				BINS,
				PALLETS,
				WEIGHT,
				PRODUCT_DESCRIPTION,
				SPECIAL_INSTRUCTIONS
			FROM WDI_LOADFORM_RAWDUMP
			WHERE UPLOAD_NUM = '".$upload_num."'
				AND LOAD_DATE = TO_DATE('".$loaddate."', 'MM/DD/YYYY')
				AND ITEM_NUM = '".$itemnum."'
			)";
	ora_parse($mod_cursor, $sql);
	ora_exec($mod_cursor);
}





function UpdateOrders($upload_num, $conn){
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	// update load header table.  Values are guaranteed unique by previous script, so will use MIN() grouping function for consistent SQL.
	$sql = "SELECT DISTINCT LOAD_NUM 
			FROM WDI_LOADFORM_RAWDUMP
			WHERE UPLOAD_NUM = '".$upload_num."'";
	ora_parse($cursor_first, $sql);
	ora_exec($cursor_first);
//	echo $sql."<br>";
	while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM WDI_LOAD_HEADER
				WHERE LOAD_NUM = '".$first_row['LOAD_NUM']."'";
		ora_parse($cursor_second, $sql);
		ora_exec($cursor_second);
		ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($second_row['THE_COUNT'] <= 0){
			// record not yet in header table.  insert.
			$sql = "INSERT INTO WDI_LOAD_HEADER
						(LOAD_NUM,
						LOAD_DATE,
						TEMP,
						PU_STATE,
						PU_LOCATION,
						CARRIER,
						PU_STATE2,
						PU_STATE3,
						TL_LTL,
						SINGLE_TEAM,
						TRAILER_LENGTH,
						STATUS)
					(SELECT
						LOAD_NUM,
						MIN(LOAD_DATE),
						MIN(TEMP),
						MIN(PU_STATE),
						MIN(PICK_UP_LOCATION),
						MIN(CARRIER),
						MIN(PU_STATE2),
						MIN(PU_STATE3),
						MIN(TL_LTL),
						MIN(SINGLE_TEAM),
						MIN(TRAILER_LENGTH),
						'ACTIVE'
					FROM WDI_LOADFORM_RAWDUMP
					WHERE UPLOAD_NUM = '".$upload_num."'
					AND LOAD_NUM = '".$first_row['LOAD_NUM']."'
					GROUP BY LOAD_NUM
					)";
		} else {
			// record already in upload_table.  update.
			$sql = "UPDATE WDI_LOAD_HEADER WLH
					SET (
						LOAD_DATE,
						TEMP,
						PU_STATE,
						PU_LOCATION,
						CARRIER,
						PU_STATE2,
						PU_STATE3,
						TL_LTL,
						SINGLE_TEAM,
						TRAILER_LENGTH,
						STATUS
						)
					=
						(SELECT 
							MIN(LOAD_DATE),
							MIN(TEMP),
							MIN(PU_STATE),
							MIN(PU_LOCATION),
							MIN(CARRIER),
							MIN(PU_STATE2),
							MIN(PU_STATE3),
							MIN(TL_LTL),
							MIN(SINGLE_TEAM),
							MIN(TRAILER_LENGTH),
							'ACTIVE'
						FROM WDI_LOADFORM_RAWDUMP WLR
						WHERE WLH.LOAD_NUM = WLR.LOAD_NUM
							AND WLR.UPLOAD_NUM = '".$upload_num."'
						)
					WHERE LOAD_NUM = '".$first_row['LOAD_NUM']."'";
		}
//		echo $sql."<br>";
		ora_parse($mod_cursor, $sql);
		ora_exec($mod_cursor);
	}


	// update order table.
	$sql = "SELECT DISTINCT DCPO_NUM 
			FROM WDI_LOADFORM_RAWDUMP
			WHERE UPLOAD_NUM = '".$upload_num."'";
	ora_parse($cursor_first, $sql);
	ora_exec($cursor_first);
	while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM WDI_LOAD_DCPO
				WHERE DCPO_NUM = '".$first_row['DCPO_NUM']."'";
		ora_parse($cursor_second, $sql);
		ora_exec($cursor_second);
		ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($second_row['THE_COUNT'] <= 0){
			// record not yet in header table.  insert.
			$sql = "INSERT INTO WDI_LOAD_DCPO
						(LOAD_NUM,
						DCPO_NUM,
						SAMS_SC,
						BKHL_TRAFFIC,
						VENDOR_NAME,
						DELIVERY_DATE,
						DEST_ID
						)
					(SELECT
						MIN(LOAD_NUM),
						'".$first_row['DCPO_NUM']."',
						MIN(SAMS_SC),
						MIN(BKHL_TRAFFIC),
						MIN(VENDOR_NAME),
						MIN(DELIVERY_DATE),
						MIN(SUBSTR('".$first_row['DCPO_NUM']."', 0, 4))
					FROM WDI_LOADFORM_RAWDUMP
					WHERE UPLOAD_NUM = '".$upload_num."'
					AND DCPO_NUM = '".$first_row['DCPO_NUM']."'
					)";
		} else {
			// record already in upload_table.  update.
			$sql = "UPDATE WDI_LOAD_DCPO WLD
					SET (
						LOAD_NUM,
						SAMS_SC,
						BKHL_TRAFFIC,
						VENDOR_NAME,
						DELIVERY_DATE,
						DEST_ID
						)
					=
						(SELECT 
							MIN(LOAD_NUM),
							MIN(SAMS_SC),
							MIN(BKHL_TRAFFIC),
							MIN(VENDOR_NAME),
							MIN(DELIVERY_DATE),
							MIN(SUBSTR(DCPO_NUM, 0, 4))
						FROM WDI_LOADFORM_RAWDUMP WLR
						WHERE WLD.DCPO_NUM = WLR.DCPO_NUM
							AND UPLOAD_NUM = '".$upload_num."'
						)
					WHERE DCPO_NUM = '".$first_row['DCPO_NUM']."'";
		}
//		echo $sql."<br>";
		ora_parse($mod_cursor, $sql);
		ora_exec($mod_cursor);
	}
}				




function GetAvailable($upload_num, $itemnum, $loaddate, $conn, $Short_Term_Cursor){
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
						WHERE DATE_DEPARTED + 1 <= TO_DATE('".$loaddate."', 'MM/DD/YYYY')
					)
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
				AND BOL = '".$itemnum."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$expected = $row['THE_COUNT'];

	// ALL in-system orders today or later 
	// (sysdate - 1 because all dates are timeless, so anytime the day before will "=" the next day)
	// (we'll worry about scanned-complete ones in next step)
	$sql = "SELECT SUM(WLDI.PALLETS) THE_COUNT
			FROM WDI_LOAD_DCPO WLD, WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH
			WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
				AND WLD.DCPO_NUM = WLDI.DCPO_NUM
				AND WLH.STATUS = 'ACTIVE'
				AND WLH.LOAD_DATE >= (SYSDATE - 1)
				AND WLDI.ITEM_NUM = '".$itemnum."'
				AND WLD.DCPO_NUM NOT IN 
				(SELECT DCPO_NUM 
					FROM WDI_LOADFORM_RAWDUMP
					WHERE UPLOAD_NUM = '".$upload_num."'
				)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$pending_orders = $row['THE_COUNT'];
/*
	// and orders that THIS upload will call for, so that the upload file itself doesn't cause
	// a self-referential overbooking
	$sql = "SELECT SUM(PALLETS) THE_EXTRA_COUNT
			FROM WDI_LOADFORM_RAWDUMP
			WHERE UPLOAD_NUM = '".$upload_num."'
				AND ITEM_NUM = '".$itemnum."'
				AND LOAD_DATE <= TO_DATE('".$loaddate."', 'MM/DD/YYYY')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$pending_orders += $row['THE_EXTRA_COUNT'];
*/


	// pallet count scanned out on orders WITHIN the orders in the "$pending_orders" list
	$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_COUNT
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CUSTOMER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
				AND ORDER_NUM IN
					(SELECT DISTINCT TO_CHAR(WLD.DCPO_NUM)
						FROM WDI_LOAD_DCPO WLD, WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH
						WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
							AND WLD.DCPO_NUM = WLDI.DCPO_NUM
							AND WLH.STATUS = 'ACTIVE'
							AND WLH.LOAD_DATE >= (SYSDATE - 1)
							AND WLDI.ITEM_NUM = '".$itemnum."'
							AND WLD.DCPO_NUM NOT IN 
							(SELECT DCPO_NUM 
								FROM WDI_LOADFORM_RAWDUMP
								WHERE UPLOAD_NUM = '".$upload_num."'
							)
					)
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CT.BOL = '".$itemnum."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$scanned_on_pending = $row['THE_COUNT'];

	$available_amount = ($IH + $expected) - ($pending_orders - $scanned_on_pending);

	return $available_amount;
}



function GetOrders($upload_num, $itemnum, $loaddate, $conn, $Short_Term_Cursor){
	$return = "";
	$sql = "SELECT DISTINCT DCPO_NUM
			FROM WDI_LOADFORM_RAWDUMP
			WHERE UPLOAD_NUM = '".$upload_num."'
				AND LOAD_DATE = TO_DATE('".$loaddate."', 'MM/DD/YYYY')
				AND ITEM_NUM = '".$itemnum."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
//	echo $sql."<br>";
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$return .= "<br>".$row['DCPO_NUM'];
	}

	return $return;
}
?>