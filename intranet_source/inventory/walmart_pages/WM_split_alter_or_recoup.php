<?

//
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
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$modify_cursor = ora_open($conn);
	$cursor = ora_open($conn);

	$bottom_cols = 6;

	$submit = $HTTP_POST_VARS['submit'];
	$barcode = $HTTP_POST_VARS['barcode'];
	$arv = $HTTP_POST_VARS['arv'];

	if($submit == "Alter Pallet"){
		// single value
		$rowcount = $HTTP_POST_VARS['rowcount'];

		// radio button value
		$adjust_type = $HTTP_POST_VARS['adjust_type'];

		// arrays
		$new_val = $HTTP_POST_VARS['new_val'];
		$split_id = $HTTP_POST_VARS['split_id'];
		$oldIH = $HTTP_POST_VARS['oldIH'];
		$oldREC = $HTTP_POST_VARS['oldREC'];

		$error_msg = "";

		for($i = 0; $i < $rowcount; $i++) {
			// check to make sure each value isn't bad.
			if($new_val[$i] < 0 || !is_numeric($new_val[$i]) || $new_val[$i] != round($new_val[$i]) ) {
				$error_msg .= "Cannot change InHouse value to ".$new_val[$i]." on line ".($i + 1).".<br>";
			}
		}

		if($error_msg != ""){
			echo "<font color=\"#FF0000\">Changes not saved.  Reason:<br>".$error_msg."</font><br>";
		} else {
			// above checks passed, edit split-pallets as specified.

			if($adjust_type == "recoup"){
				// adjust each line with a recoup if needed
				for($i = 0; $i < $rowcount; $i++) {
					if($new_val[$i] != $oldIH[$i]) { // we only recoup a value that has actually changed
						$changed_val = $new_val[$i] - $oldIH[$i];

						$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_MAX FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$barcode."' AND CUSTOMER_ID = '3000' AND ARRIVAL_NUM = '".$arv."'";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor, $sql);
						ora_fetch_into($Short_Term_Cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$act_num = $short_term_data_row['THE_MAX'] + 1;
						
						// update CARGO_TRACKING
						$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = '".$new_val[$i]."' WHERE PALLET_ID = '".$barcode."' AND ARRIVAL_NUM = '".$arv."' AND RECEIVER_ID = '3000' AND SPLIT_PALLET_ID = '".$split_id[$i]."'";
						ora_parse($modify_cursor, $sql);
						ora_exec($modify_cursor, $sql);

						// add CARGO_ACTIVITY record
						$sql = "INSERT INTO CARGO_ACTIVITY 
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT, BATCH_ID) VALUES
								('".$act_num."', '9', '".$changed_val."', '-3', '3000', SYSDATE, '".$barcode."', '".$arv."', '".$new_val[$i]."', '".$split_id[$i]."')";
						ora_parse($modify_cursor, $sql);
						ora_exec($modify_cursor, $sql);
					}
				}

				// with the individual splits updated, update main record
				$sql = "UPDATE CARGO_TRACKING CT
						SET QTY_IN_HOUSE = 
								(SELECT SUM(QTY_IN_HOUSE) 
								FROM WDI_SPLIT_PALLETS
								WHERE PALLET_ID = '".$barcode."' 
									AND ARRIVAL_NUM = '".$arv."' 
									AND RECEIVER_ID = '3000'
								)
						WHERE PALLET_ID = '".$barcode."' 
							AND ARRIVAL_NUM = '".$arv."' 
							AND RECEIVER_ID = '3000'";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor, $sql);

			} else {
				// adjust each line for QTY_RECEIVED if necessary
				for($i = 0; $i < $rowcount; $i++) {
					if($new_val[$i] != $oldIH[$i]) { // we only recoup a value that has actually changed

						$sql = "UPDATE WDI_SPLIT_PALLETS SET QTY_IN_HOUSE = '".$new_val[$i]."', QTY_RECEIVED = '".$new_val[$i]."' 
								WHERE PALLET_ID = '".$barcode."' 
									AND ARRIVAL_NUM = '".$arv."' 
									AND RECEIVER_ID = '3000' 
									AND SPLIT_PALLET_ID = '".$split_id[$i]."'";
						ora_parse($modify_cursor, $sql);
						ora_exec($modify_cursor, $sql);
					}
				}

				// new CT record
				$sql = "UPDATE CARGO_TRACKING CT
						SET (QTY_IN_HOUSE, QTY_RECEIVED) = 
								(SELECT SUM(QTY_IN_HOUSE), SUM(QTY_RECEIVED) 
								FROM WDI_SPLIT_PALLETS
								WHERE PALLET_ID = '".$barcode."' 
									AND ARRIVAL_NUM = '".$arv."' 
									AND RECEIVER_ID = '3000'
								)
						WHERE PALLET_ID = '".$barcode."' 
							AND ARRIVAL_NUM = '".$arv."' 
							AND RECEIVER_ID = '3000'";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor, $sql);

				// update the CARGO_ACTIVITY receipt record (if it exists)
				$sql = "UPDATE CARGO_ACTIVITY
						SET (QTY_CHANGE, QTY_LEFT) = 
							(SELECT QTY_IN_HOUSE, QTY_IN_HOUSE 
							FROM CARGO_TRACKING
							WHERE PALLET_ID = '".$barcode."' 
								AND ARRIVAL_NUM = '".$arv."' 
								AND RECEIVER_ID = '3000'
							)
						WHERE PALLET_ID = '".$barcode."' 
							AND ARRIVAL_NUM = '".$arv."' 
							AND CUSTOMER_ID = '3000'
							AND SERVICE_CODE IN (1, 8)
							AND ACTIVITY_NUM = '1'";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor, $sql);
			}

			echo "<font color=\"#0000FF\">Modifications Saved.</font><br>";
		}
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">WalMart Split Pallet - Alter Original/Recoup Screen</font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a></font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="filter" action="WM_split_alter_or_recoup.php" method="post">
	<tr>
		<td>Barcode:<input type="text" name="barcode" value="<? echo $barcode; ?>" size="35" maxlength="35"></td>
	</tr>
	<tr>
		<td>LR#:<input type="text" name="arv" value="<? echo $arv; ?>" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Barcode"></td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
</form>
</table>
<?
	if($barcode != "" && $arv != ""){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND ARRIVAL_NUM = '".$arv."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$ct_rec = $short_term_row['THE_COUNT'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_SPLIT_PALLETS
				WHERE PALLET_ID = '".$barcode."'
					AND ARRIVAL_NUM = '".$arv."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$wsp_rec = $short_term_row['THE_COUNT'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$barcode."' AND CUSTOMER_ID = '3000' AND ARRIVAL_NUM = '".$arv."' AND SERVICE_CODE NOT IN ('1', '8', '18', '19', '20', '21', '22')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor, $sql);
		ora_fetch_into($Short_Term_Cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$any_qtyrec_preventing_activites = $short_term_data_row['THE_COUNT'];
		if($any_qtyrec_preventing_activites >= 1){
			$allow_qtyrec = false;
		} else {
			$allow_qtyrec = true;
		}

		$row_num = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="alter" action="WM_split_alter_or_recoup.php" method="post">
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
<input type="hidden" name="arv" value="<? echo $arv; ?>">
<?
		if($ct_rec <= 0){
?>
	<tr>
		<td><font color="#FF0000">Pallet Does Not Exist</font></td>
	</tr>
<?
		} elseif($ct_rec > 0 && $wsp_rec <= 0) {
?>
	<tr>
		<td><font color="#FF0000">Pallet Exists, but is not a Split Pallet</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="<? echo $bottom_cols; ?>" align="center"><font size="3" face="Verdana"><b>Recoup:&nbsp;<input type="radio" name="adjust_type" value="recoup" checked>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Change QTY RCVD:&nbsp;<input type="radio" name="adjust_type" value="qty_rec" <? if(!$allow_qtyrec){?> disabled <?}?>></b></font>
			<? if(!$allow_qtyrec){?><br><font size="2" face="Verdana" color="#FF0000">Cannot edit QTY_RECEIVED; activities already present on pallet</font><?}?>
		</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Split Pallet ID#</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Received</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Item Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Change InHouse To:</b></font></td>
	</tr>
<?
			$sql = "SELECT SPLIT_PALLET_ID, QTY_IN_HOUSE, QTY_RECEIVED, BOL, NVL(ITEM_DESCRIPTION, 'UNKNOWN') THE_DESC
					FROM WDI_SPLIT_PALLETS WSP, WM_ITEMNUM_MAPPING WIM
					WHERE WSP.BOL = TO_CHAR(WIM.WM_ITEM_NUM(+))
						AND WSP.PALLET_ID = '".$barcode."'
						AND WSP.ARRIVAL_NUM = '".$arv."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
	<input type="hidden" name="split_id[<? echo $row_num; ?>]" value="<? echo $row['SPLIT_PALLET_ID']; ?>">
	<input type="hidden" name="oldIH[<? echo $row_num; ?>]" value="<? echo $row['QTY_IN_HOUSE']; ?>">
	<input type="hidden" name="oldREC[<? echo $row_num; ?>]" value="<? echo $row['QTY_RECEIVED']; ?>">
		<td><font size="2" face="Verdana"><? echo $row['SPLIT_PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_IN_HOUSE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DESC']; ?></font></td>
		<td><font size="2" face="Verdana"><input name="new_val[<? echo $row_num; ?>]" type="text" size="3" maxlength="3" value="<? echo $row['QTY_IN_HOUSE']; ?>"></font></td>
	</tr>
<?
				$row_num++;
			}
?>
	<tr>
		<td colspan="<? echo $bottom_cols; ?>" align="center"><input type="submit" name="submit" value="Alter Pallet"></td>
	</tr>
<input type="hidden" name="rowcount" value="<? echo $row_num; ?>">
</form>
</table>
<?
		}
	}
	include("pow_footer.php");
?>