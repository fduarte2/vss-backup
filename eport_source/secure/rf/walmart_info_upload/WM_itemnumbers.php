<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);


  $submit = $HTTP_POST_VARS['submit'];

	if($submit == "Create New Item"){
		$grower = $HTTP_POST_VARS['grower'];
		$comm = $HTTP_POST_VARS['comm'];
		$var = $HTTP_POST_VARS['var'];
		$label = $HTTP_POST_VARS['label'];
		$walmart = $HTTP_POST_VARS['walmart'];
		$grower_desc = $HTTP_POST_VARS['grower_desc'];
		$item_desc = $HTTP_POST_VARS['item_desc'];
//		$sams = $HTTP_POST_VARS['sams'];

		if($grower != "" && $comm != "" && $var != "" && $label != "" && ($walmart != "" || $sams != "") && $grower_desc != "" && $item_desc != ""){
			// all fields entered.  Validate checks.
			$insert = true;
			
			if($insert == true){
				$sql = "SELECT COUNT(*) THE_COUNT FROM WM_ITEMNUM_MAPPING WHERE ITEM_NUM = '".$grower."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] >= 1){
					echo "<font color=\"#FF0000\">Grower Item # ".$grower." already in system.</font>";
					$insert = false;
				}
			}

			if($insert == true){
				$sql = "SELECT COUNT(*) THE_COUNT FROM WM_ITEM_COMM_MAP
						WHERE ITEM_NUM = '".$walmart."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] <= 0){
					echo "<font color=\"#FF0000\">Walmart Item # ".$walmart." not yet in PoW system; please contact PoW so that we can assign one of our commodity codes, and then resubmit the information.</font>";
					$insert = false;
				}
			}

			if($insert == true){
				// SAMS_ITEM_NUM,
				// '".$sams."',
				$sql = "INSERT INTO WM_ITEMNUM_MAPPING
							(ITEM_NUM,
							COMMODITY,
							VARIETY,
							LABEL_CODE,
							WM_ITEM_NUM,
							GROWER,
							ITEM_DESCRIPTION,
							FIRST_FOUND_IN_FILE,
							UPDATED_BY,
							DATE_UPDATED)
						VALUES
							('".$grower."',
							'".$comm."',
							'".$var."',
							'".$label."',
							'".$walmart."',
							'".$grower_desc."',
							'".$item_desc."',
							'Manual Entry',
							'".$user."',
							SYSDATE)";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

/*				$sql = "INSERT INTO WM_ITEM_COMM_MAP
							(ITEM_NUM)
						VALUES
							('".$grower."')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor); */

				echo "<font color=\"#0000FF\">Grower Item # ".$grower." added.</font>";
			}
		} else {
			echo "<font color=\"#FF0000\">All fields must be entered to add a new item.</font>";
		}
	}

	if($submit == "Save New Item(s)"){
		$grower = $HTTP_POST_VARS['grower'];
		$walmart = $HTTP_POST_VARS['walmart'];
		$grower_desc = $HTTP_POST_VARS['grower_desc'];
		$item_desc = $HTTP_POST_VARS['item_desc'];
		$label_code = $HTTP_POST_VARS['label_code'];// the shipping program; I.E. SAMS or WM
//		$sams = $HTTP_POST_VARS['sams'];

		$i = 0;
		$updated = 0;

		while($grower[$i] != ""){
			// SAMS_ITEM_NUM = '".$sams[$i]."'
			if(($walmart[$i] != "" || $sams[$i] != "") && $grower_desc[$i] != "" && $item_desc[$i] != "" && $label_code[$i] != ""){
				$sql = "UPDATE WM_ITEMNUM_MAPPING
						SET WM_ITEM_NUM = '".$walmart[$i]."',
						GROWER = '".$grower_desc[$i]."',
						LABEL_CODE = '".$label_code[$i]."',
						ITEM_DESCRIPTION = '".$item_desc[$i]."'
						WHERE ITEM_NUM = '".$grower[$i]."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				$updated++;
			}

			$i++;
		}

		echo "<font color=\"#0000FF\">".$updated." new items saved.</font>";
	}

	if($submit == "Edit Item(s)"){
		$grower = $HTTP_POST_VARS['grower'];
		$walmart = $HTTP_POST_VARS['walmart'];
//		$sams = $HTTP_POST_VARS['sams'];
		$pre_walmart = $HTTP_POST_VARS['pre_walmart'];
//		$pre_sams = $HTTP_POST_VARS['pre_sams'];
		$grower_desc = $HTTP_POST_VARS['grower_desc'];
		$item_desc = $HTTP_POST_VARS['item_desc'];
		$pre_grower_desc = $HTTP_POST_VARS['pre_grower_desc'];
		$pre_item_desc = $HTTP_POST_VARS['pre_item_desc'];
		$label_code = $HTTP_POST_VARS['label_code'];  // the shipping program; I.E. SAMS or WM
		$pre_label_code = $HTTP_POST_VARS['pre_label_code'];

		$i = 0;
		$updated = 0;

		while($grower[$i] != ""){
			// SAMS_ITEM_NUM = '".$sams[$i]."'
			if($walmart[$i] != $pre_walmart[$i] || $sams[$i] != $pre_sams[$i] || $grower_desc[$i] != $pre_grower_desc[$i] || $item_desc[$i] != $pre_item_desc[$i] || $pre_label_code[$i] != $label_code[$i]){
				$sql = "UPDATE WM_ITEMNUM_MAPPING
						SET WM_ITEM_NUM = '".$walmart[$i]."',
						GROWER = '".$grower_desc[$i]."',
						LABEL_CODE = '".$label_code[$i]."',
						ITEM_DESCRIPTION = '".$item_desc[$i]."'
						WHERE ITEM_NUM = '".$grower[$i]."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);

				$updated++;
			}

			$i++;
		}

		echo "<font color=\"#0000FF\">".$updated." items updated.</font>";
	}
/*
	if($submit != ""){
		$sql = "INSERT INTO WM_ITEM_COMM_MAP
					(ITEM_NUM)
				(SELECT DISTINCT WM_ITEM_NUM
					FROM WM_ITEMNUM_MAPPING
					WHERE WM_ITEM_NUM IS NOT NULL
					AND WM_ITEM_NUM NOT IN
						(SELECT ITEM_NUM FROM WM_ITEM_COMM_MAP)
				)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
	}
	*/
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Item Numbers
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="create" action="WM_itemnumbers_index.php" method="post">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>New Entry</b></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana"><b>Grower Item#:</b></font></td>
		<td><input type="text" name="grower" size="15" maxlength="10"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Grower:</b></font></td>
		<td><input type="text" name="grower_desc" size="15" maxlength="10"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Commodity:</b></font></td>
		<td><input type="text" name="comm" size="15" maxlength="12"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Variety:</b></font></td>
		<td><input type="text" name="var" size="25" maxlength="20"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Label Code:</b></font></td>
		<td><select name="label">
				<option value="WM">WM</option>
				<option value="SAMS">SAMS</option>
				</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Walmart/SAMS Master Item#</b></font></td>
		<td><input type="text" name="walmart" size="15" maxlength="10"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Item Description:</b></font></td>
		<td><input type="text" name="item_desc" size="15" maxlength="10"></td>
	</tr>
<!--	<tr>
		<td><font size="2" face="Verdana"><b>Sam's Club Item#:</b></font></td>
		<td><input type="text" name="sams" size="15" maxlength="10"></td>
	</tr> !-->
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create New Item"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="add" action="WM_itemnumbers_index.php" method="post">
	<tr>
		<td colspan="8" align="center"><font size="3" face="Verdana"><b>Add Item #s</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Grower Item#:</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity:</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Label Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Found in File</b></font></td>
		<td><font size="2" face="Verdana"><b>Walmart/SAMS Master Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Item Description</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Sam's Club Item#</b></font></td> !-->
	</tr>
<?
	$i = 0;

	// AND SAMS_ITEM_NUM IS NULL 
	$sql = "SELECT * FROM WM_ITEMNUM_MAPPING WHERE WM_ITEM_NUM IS NULL ORDER BY ITEM_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<input type="hidden" name="grower[<? echo $i; ?>]" value="<? echo $short_term_row['ITEM_NUM']; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo $short_term_row['ITEM_NUM']; ?></font></td>
		<td><input type="text" name="grower_desc[<? echo $i; ?>]" size="30" maxlength="35"></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['COMMODITY']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['VARIETY']; ?>&nbsp;</font></td>
		<td><select name="label_code[<? echo $i; ?>]"><option value=""></option>
				<option value="WM"<? if($short_term_row['LABEL_CODE'] == "WM"){?> selected <?}?>>WM</option>
				<option value="SAMS"<? if($short_term_row['LABEL_CODE'] == "SAMS"){?> selected <?}?>>SAMS</option>
			</select></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['FIRST_FOUND_IN_FILE']; ?>&nbsp;</font></td>
		<td><input type="text" name="walmart[<? echo $i; ?>]" size="15" maxlength="10"></td>
		<td><input type="text" name="item_desc[<? echo $i; ?>]" size="30" maxlength="40"></td>
<!--		<td><input type="text" name="sams[<? echo $i; ?>]" size="15" maxlength="10"></td> !-->
	</tr>
<?
		$i++;
	}
	if($i > 0){
?>
	<tr>
		<td colspan="8" align="center"><input type="submit" name="submit" value="Save New Item(s)"></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="8" align="center"><font color="#FF0000">No Numbers Awaiting Information</font></td>
	</tr>
<?
	}
?>
</form>
</table>
<br><br>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="edit" action="WM_itemnumbers_index.php" method="post">
	<tr>
		<td colspan="8" align="center"><font size="3" face="Verdana"><b>Edit Item #s</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Grower Item#:</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity:</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Label Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Found in File</b></font></td>
		<td><font size="2" face="Verdana"><b>Walmart/SAMS Master Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Item Description</b></font></td>
<!--		<td><font size="2" face="Verdana"><b>Sam's Club Item#</b></font></td> !-->
	</tr>
<?
	$i = 0;

	// OR SAMS_ITEM_NUM IS NOT NULL 
	$sql = "SELECT * FROM WM_ITEMNUM_MAPPING WHERE WM_ITEM_NUM IS NOT NULL ORDER BY ITEM_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<input type="hidden" name="grower[<? echo $i; ?>]" value="<? echo $short_term_row['ITEM_NUM']; ?>">
	<input type="hidden" name="pre_walmart[<? echo $i; ?>]" value="<? echo $short_term_row['WM_ITEM_NUM']; ?>">
	<input type="hidden" name="pre_sams[<? echo $i; ?>]" value="<? echo $short_term_row['SAMS_ITEM_NUM']; ?>">
	<input type="hidden" name="pre_grower_desc[<? echo $i; ?>]" value="<? echo $short_term_row['GROWER']; ?>">
	<input type="hidden" name="pre_item_desc[<? echo $i; ?>]" value="<? echo $short_term_row['ITEM_DESCRIPTION']; ?>">
	<input type="hidden" name="pre_label_code[<? echo $i; ?>]" value="<? echo $short_term_row['LABEL_CODE']; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo $short_term_row['ITEM_NUM']; ?></font></td>
		<td><input type="text" name="grower_desc[<? echo $i; ?>]" size="30" maxlength="35" value="<? echo $short_term_row['GROWER']; ?>"></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['COMMODITY']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['VARIETY']; ?>&nbsp;</font></td>
		<td><select name="label_code[<? echo $i; ?>]"><option value=""></option>
				<option value="WM"<? if($short_term_row['LABEL_CODE'] == "WM"){?> selected <?}?>>WM</option>
				<option value="SAMS"<? if($short_term_row['LABEL_CODE'] == "SAMS"){?> selected <?}?>>SAMS</option>
			</select></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['FIRST_FOUND_IN_FILE']; ?>&nbsp;</font></td>
		<td><input type="text" name="walmart[<? echo $i; ?>]" size="15" maxlength="10" value="<? echo $short_term_row['WM_ITEM_NUM']; ?>"></td>
		<td><input type="text" name="item_desc[<? echo $i; ?>]" size="30" maxlength="40" value="<? echo $short_term_row['ITEM_DESCRIPTION']; ?>"></td>
<!--		<td><input type="text" name="sams[<? echo $i; ?>]" size="15" maxlength="10" value="<? echo $short_term_row['SAMS_ITEM_NUM']; ?>"></td> !-->
	</tr>
<?
		$i++;
	}
	if($i > 0){
?>
	<tr>
		<td colspan="8" align="center"><input type="submit" name="submit" value="Edit Item(s)"></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td colspan="8" align="center"><font color="#FF0000">No Known Numbers In System</font></td>
	</tr>
<?
	}
?>
</form>
