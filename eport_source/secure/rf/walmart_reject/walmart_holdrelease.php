<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
//    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  if($user_cust_num != 3000){
	  echo "user not authorized to use this page.  Please use your browser's back button, or choose another link to continue";
	  exit;
  }


  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);

	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	$default_message = "";
	$success_message = "";
//	echo $submit."<br>";
	if($submit == "Hold Pallet"){
		// check for, and use, Pallet Dropdown Box.
		$pallet = $HTTP_POST_VARS['pallet'];

		if($pallet == ""){
			// they forgot to choose one
			echo "<font size=\"3\" color=\"#FF0000\">No pallet was selected; no status changes made.</font><br>";
		} else {
			$sql = "UPDATE WDI_ADDITIONAL_DATA SET WDI_STATUS = 'HOLD'
					WHERE GROWER_PALLET_ID = '".$pallet."'
					AND WDI_ARRIVAL_NUM = '".$cur_ves."'
					AND WDI_RECEIVER_ID = '".$user_cust_num."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
	}

	if($submit == "Clear Pallet"){
		// clear selected pallet
		$pallet = $HTTP_POST_VARS['pallet'];

		$sql = "UPDATE WDI_ADDITIONAL_DATA SET WDI_STATUS = NULL
				WHERE GROWER_PALLET_ID = '".$pallet."'
				AND WDI_ARRIVAL_NUM = '".$cur_ves."'
				AND WDI_RECEIVER_ID = '".$user_cust_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

	if($submit == "Clear ALL Pallets"){
		// clear ALL pallets from selected vessel

		$sql = "UPDATE WDI_ADDITIONAL_DATA SET WDI_STATUS = NULL
				WHERE WDI_ARRIVAL_NUM = '".$cur_ves."'
				AND WDI_RECEIVER_ID = '".$user_cust_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

	if($submit == "Upload Hold File"){
		if($HTTP_POST_FILES['import_file']['name'] == ""){
			echo "No file uploaded.  Please use you browser's back button to return to the previous page.";
			exit;
		}

		// step 2: get file
		$impfilename = date(mdYhis)."_file"."_seq".$trans_id."_".basename($HTTP_POST_FILES['import_file']['name']);
		$target_path_import = "./WMuploadedfiles/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact Port of Wilmington";
			exit;
		}

		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$useable_data = array();

		// 2b)  populate data array
		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$useable_data[($i - 1)]["PALLET"] = trim($data->sheets[0]['cells'][$i][1]);
		}

		$num_entries = $i - 1;

		// step 3:  validate and save
//		$default_message = "This upload's unique identifier is:  <b>".$trans_id."</b><br>Please reference this number if contacting the Port of Wilmington regarding an upload.<br>";
		$result = validate_data($useable_data, $cur_ves, $conn, $Short_Term_Cursor);

		if($result == ""){
			save_data($useable_data, "", $conn, $cursor, $user_cust_num, $cur_ves);
			$success_message = "<font size=\"3\" face=\"Verdana\" color=\"#0000FF\">".$num_entries." Rows uploaded Successfully.</font>";
		} else {
			$success_message = "<font size=\"3\" face=\"Verdana\" color=\"#FF0000\">File could not be uploaded; the following problems were detected:<br>".$result."---Please correct the file and re-upload, or contact PoW for further assistance<br></font>";
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Reject (Hold) Maintenance
</font>
	    <hr>
	 </p>
      </td>
   </tr>
<?
	if($submit == "Upload Hold File"){
		echo $success_message;
	}	
?>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_ves" action="walmart_holdrelease_index.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel" onchange="document.get_ves.submit(this.form)">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
   // AND LR_NUM NOT IN (SELECT LR_NUM FROM WDI_VESSEL_RELEASE) --- removed
   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND LR_NUM >= 10889 AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."') ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($cur_ves == $row['LR_NUM']){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">(Note:  If the list is empty, there are no vessels available for data upload at this time.<br>Please check back again.)</font></td>
	</tr>
</form>
</table>

<?
	if($cur_ves != ""){
?>
<br><br>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="hold_submit" action="walmart_holdrelease_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
	<tr>
		<td>Pallet:&nbsp;&nbsp;</td>
		<td><select name="pallet">
<?
		$sql = "SELECT DISTINCT GROWER_PALLET_ID FROM WDI_ADDITIONAL_DATA WHERE WDI_ARRIVAL_NUM = '".$cur_ves."' AND WDI_STATUS IS NULL ORDER BY GROWER_PALLET_ID";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['GROWER_PALLET_ID']; ?>"><? echo $row['GROWER_PALLET_ID']; ?></option>
<?
	}
?>
				</select></td>
		<td align="center"><font size="3" face="Verdana"><b>---OR---</b></font></td>
		<td align="right">Upload File:  <input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Hold Pallet"></td>
		<td>&nbsp;</td>
		<td align="right"><input type="submit" name="submit" value="Upload Hold File"></td>
	</tr>
</form>
</table>
<br><br>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT DISTINCT GROWER_PALLET_ID, WDI_RECEIVING_PO, WDI_STATUS
				FROM WDI_ADDITIONAL_DATA
				WHERE WDI_ARRIVAL_NUM = '".$cur_ves."' AND WDI_RECEIVER_ID = '".$user_cust_num."' AND WDI_STATUS IS NOT NULL 
				ORDER BY GROWER_PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">No Pallets for this vessel have any non-normal statuses.</font></td>
	</tr>
<?
		} else {
			$form_num = 0;

			$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$cur_ves."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$ves_name = $Short_Term_Row['LR_NUM']." - ".$Short_Term_Row['VESSEL_NAME'];
?>
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana"><b>Non-normal status pallets for <? echo $ves_name; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Receiving PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Status</b></font></td>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
	</tr>
<?
			do {
				$form_num++;
?>
<form name="delete_one_<? echo $form_num; ?>" action="walmart_holdrelease_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
<input type="hidden" name="pallet" value="<? echo $row['GROWER_PALLET_ID']; ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['GROWER_PALLET_ID']; ?> </font></td>
		<td><font size="2" face="Verdana"><? echo $row['WDI_RECEIVING_PO']; ?> </font></td>
		<td><font size="2" face="Verdana"><? echo $row['WDI_STATUS']; ?> </font></td>
		<td><input name="submit" type="submit" value="Clear Pallet"></font></td>
	</tr>
</form>
<?
				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
<!--<form name="delete_all" action="walmart_holdrelease_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
	<tr>
		<td colspan="4" align="center"><input type="submit" name="submit" value="Clear ALL Pallets"></td>
	</tr>
</form> !-->
<?
		}
?>
	</table>
<?
	}


function validate_data($useable_data, $cur_ves, $conn, $Short_Term_Cursor){
	$return = "";
	
	// step 1:  test the first line
	if($useable_data[0]["PALLET"] != "PALLET"){
		return "File order does not match to expected format.  Expecting single-column XLS file, with a first row value of <b>Pallet</b>, and Barcodes in subsequent lines.";
	}

	// step 2, for each line...
	$i = 1;
	while($useable_data[$i]["PALLET"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

		// 2A)  Pallet ID
		if(strlen($useable_data[$i]["PALLET"]) > 32){
			return "Line: ".$badline." - PoW pallet numbers cannot be longer than 32 characters<br>";
		}

		// 2B)  Existence of pallet
		$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_ADDITIONAL_DATA WHERE GROWER_PALLET_ID = '".$useable_data[$i]["PALLET"]."' AND WDI_ARRIVAL_NUM = '".$cur_ves."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($Short_Term_Row['THE_COUNT'] <= 0){
			return "Line: ".$badline." - Record for this Pallet doesn't exist in PoW database<br>";
		}



		// 2-done)  loop over
		$i++;
	}

	// 3:  function over
	return $return;
}

function save_data($useable_data, $trans_id, $conn, $cursor, $user_cust_num, $cur_ves){
	$i = 1;
	while($useable_data[$i]["PALLET"] != ""){	

		$sql = "UPDATE WDI_ADDITIONAL_DATA
				SET WDI_STATUS = 'HOLD'
				WHERE GROWER_PALLET_ID = '".$useable_data[$i]["PALLET"]."'
				AND WDI_ARRIVAL_NUM = '".$cur_ves."'
				AND WDI_RECEIVER_ID = '".$user_cust_num."'";

		ora_parse($cursor, $sql);
		ora_exec($cursor);

	$i++;
	}
}