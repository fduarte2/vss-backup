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
	$old_PO = $HTTP_POST_VARS['old_PO'];
	$new_PO = $HTTP_POST_VARS['new_PO'];
	$submit = $HTTP_POST_VARS['submit'];

	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	$default_message = "";
	$success_message = "";
//	echo $submit."<br>";
	if($submit == "Change PO"){
		// check for, and use, Pallet Dropdown Box.

		if(!is_numeric($new_PO)){
			echo "<font size=\"3\" color=\"#FF0000\">PO#s must be numeric</font><br>";
		} elseif ($new_PO == "" || $old_PO == ""){
			// they forgot to choose one
			echo "<font size=\"3\" color=\"#FF0000\">Missing PO selection; request cancelled.</font><br>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT FROM WDI_PO_CHANGES
					WHERE STATUS = 'PENDING'
					AND OLD_PO = '".$old_PO."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($Short_Term_row['THE_COUNT'] > 0){
				echo "<font size=\"3\" color=\"#FF0000\">Chosen PO already has a pending change.</font><br>";
			} else {
				$sql = "INSERT INTO WDI_PO_CHANGES
							(OLD_PO,
							NEW_PO,
							EPORT_LOGIN,
							REQUEST_DATE,
							STATUS,
							PO_CHANGE_NUM)
						VALUES
							('".$old_PO."',
							'".$new_PO."',
							'".$user."',
							SYSDATE,
							'PENDING',
							WDI_PO_CHANGE_SEQ.NEXTVAL)";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
			}
		}
	}

	if($submit == "Clear PO Change"){
		// clear selected pallet

		$sql = "UPDATE WDI_PO_CHANGES SET STATUS = 'CANCEL'
				WHERE OLD_PO = '".$old_PO."'
				AND NEW_PO = '".$new_PO."'
				AND STATUS = 'PENDING'";
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
/*
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
*/
?>
<table border="0" width="700" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Alter PO#
</font>
	    <hr>
	 </p>
      </td>
   </tr>
<?
/*
	if($submit == "Upload Hold File"){
		echo $success_message;
	}
*/
?>
</table>

<br><br>
<table border="0" width="700" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="hold_submit" action="walmart_POalter_index.php" method="post">
	<tr>
		<td align="left" width="15%">Old PO:&nbsp;&nbsp;</td>
		<td align="left"><select name="old_PO">
								<option value=""<? if($PO == ""){?> selected <?}?>>Select PO</option>
<?
   $sql = "SELECT DISTINCT MARK 
			FROM CARGO_TRACKING 
			WHERE RECEIVER_ID = '".$view_cust."'
			ORDER BY MARK";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['MARK']; ?>"<? if($PO == $row['MARK']){?> selected <?}?>><? echo $row['MARK']; ?></option>
<?
	}
?>
					</select>
		</td>
<!--		<td align="center"><font size="3" face="Verdana"><b>---OR---</b></font></td>
		<td align="right">Upload File:  <input type="file" name="import_file"></td> !-->
	</tr>
	<tr>
		<td align="left" width="15%">New PO:&nbsp;&nbsp;</td>
		<td align="left"><input name="new_PO" type="text" maxlength="12" size="12"></td>
<!--		<td>&nbsp;</td>
		<td align="right"><input type="submit" name="submit" value="Upload PO File"></td> !-->
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Change PO"></td>
<!--		<td colspan="2">&nbsp;</td> !-->
	</tr>
</form>
</table>
<br><br>

<table border="1" width="700" cellpadding="4" cellspacing="0">
<? 
	$sql = "SELECT OLD_PO, NEW_PO, PO_CHANGE_NUM, STATUS, TO_CHAR(REQUEST_DATE, 'MM/DD/YYYY') THE_REQ FROM WDI_PO_CHANGES ORDER BY STATUS DESC, OLD_PO";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">No PO changes.</font></td>
	</tr>
<?
	} else {
		$form_num = 0;
?>
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana" color="#FF0000">Note:  Pending PO changes will take effect 4AM tomorrow morning</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>CURRENT PO</b></font></td>
		<td><font size="2" face="Verdana"><b>NEW PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Request Made</b></font></td>
		<td><font size="2" face="Verdana"><b>Request #</b></font></td>
		<td><font size="2" face="Verdana"><b>Request Status</b></font></td>
		<td><font size="2" face="Verdana">&nbsp;</font></td>
	</tr>
<?
		do {
			$form_num++;
?>
<form name="delete_one_<? echo $form_num; ?>" action="walmart_POalter_index.php" method="post">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
<input type="hidden" name="old_PO" value="<? echo $row['OLD_PO']; ?>">
<input type="hidden" name="new_PO" value="<? echo $row['NEW_PO']; ?>">
	<tr>
<?
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_TRACKING 
					WHERE MARK = '".$row['OLD_PO']."'
					AND RECEIVER_ID = '".$view_cust."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($Short_Term_row['THE_COUNT'] > 0){
?>
		<td><a target="PO_popup.php?PO=<? echo $row['OLD_PO']; ?>" href="PO_popup.php?PO=<? echo $row['OLD_PO']; ?>"><font size="2" face="Verdana"><? echo $row['OLD_PO']; ?> </font></a></td>
<?
			} else {
?>
		<td><font size="2" face="Verdana"><? echo $row['OLD_PO']; ?> </font></td>
<?
			}
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_TRACKING 
					WHERE MARK = '".$row['NEW_PO']."'
					AND RECEIVER_ID = '".$view_cust."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($Short_Term_row['THE_COUNT'] > 0){
?>
		<td><a target="PO_popup.php?PO=<? echo $row['NEW_PO']; ?>" href="PO_popup.php?PO=<? echo $row['NEW_PO']; ?>"><font size="2" face="Verdana"><? echo $row['NEW_PO']; ?> </font></a></td>
<?
			} else {
?>
		<td><font size="2" face="Verdana"><? echo $row['NEW_PO']; ?> </font></td>
<?
			}
?>
		<td><font size="2" face="Verdana"><? echo $row['THE_REQ']; ?> </font></td>
		<td><font size="2" face="Verdana"><? echo $row['PO_CHANGE_NUM']; ?> </font></td>
		<td><font size="2" face="Verdana"><? echo $row['STATUS']; ?> </font></td>
		<td><? if($row['STATUS'] == 'PENDING'){ ?>
					<input name="submit" type="submit" value="Clear PO Change"><?
				} else { ?>
					&nbsp;
				<? } ?></font></td>
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

/*
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
*/