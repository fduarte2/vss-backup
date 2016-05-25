<?
/*  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance - RF Storage";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	REPLACE ABOVE LOGIC WITH EPORT EQUIVALENT
*/
//	$user_cust_num = 1982; // FOR TESTING
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
  $VERY_Short_Term_Cursor = ora_open($conn);

	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	$detail_colspan_default = 10;
	$colspan = $detail_colspan_default;

	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != ""){
		if($HTTP_POST_FILES['import_file']['name'] == ""){
			// do nothing
			// echo "No file uploaded.";
		} else {
			$impfilename = "cust".$user_cust_num."on".date(mdYhis)."file".basename($HTTP_POST_FILES['import_file']['name']);
			$target_path_import = "./uploadedfiles/".$impfilename;

			if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
				system("/bin/chmod a+r $target_path_import");
			} else {
				echo "Error on file upload.  Please contact Port of Wilmington";
				exit;
			}

			$updated_rows = 0;
			$error_display = "";

			include("reader.php");
			$data = new Spreadsheet_Excel_Reader();
			$useable_data = array();

			$data->read($target_path_import);
			error_reporting(E_ALL ^ E_NOTICE);

			for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
//				$useable_data[($i - 1)]["pallet"] = trim($data->sheets[0]['cells'][$i][1]);
//				$useable_data[($i - 1)]["loc"] = trim($data->sheets[0]['cells'][$i][2]);

				$pallet = trim($data->sheets[0]['cells'][$i][1]);
				$loc = trim($data->sheets[0]['cells'][$i][2]);

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$cur_ves."'
						AND RECEIVER_ID = '".$user_cust_num."'
						AND PALLET_ID = '".$pallet."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['THE_COUNT'] <= 0){
					// pallet doesn't exist
					$error_display .= "  Line ".$i.":  Pallet ".$pallet." not found in PoW system for Vessel ".$cur_ves."<br>";
				} else {
					$bad_loc = false;

					if(strlen($loc) > 12 || strlen($loc) <= 0){
						$error_display .= "  Line: ".$i.":  Pallet ".$pallet." - Location must be between 1 and 12 characters.<br>";
						$bad_loc = true;
					}

					if(!ereg("^([a-zA-Z0-9])+$", $loc)){
						$error_display .= "  Line: ".$i.":  Pallet ".$pallet." - Location must be alphanumeric.<br>";
						$bad_loc = true;
					}

					if(!$bad_loc){
						// this line passes inspection, do the update

						$sql = "UPDATE CARGO_TRACKING
								SET WAREHOUSE_LOCATION = WAREHOUSE_LOCATION || ' ".$loc."'
								WHERE ARRIVAL_NUM = '".$cur_ves."'
								AND RECEIVER_ID = '".$user_cust_num."'
								AND PALLET_ID = '".$pallet."'";
						ora_parse($cursor, $sql);
						ora_exec($cursor);

						$sql = "UPDATE WDI_SPLIT_PALLETS
								SET WAREHOUSE_LOCATION = WAREHOUSE_LOCATION || ' ".$loc."'
								WHERE ARRIVAL_NUM = '".$cur_ves."'
								AND RECEIVER_ID = '".$user_cust_num."'
								AND PALLET_ID = '".$pallet."'";
						ora_parse($cursor, $sql);
						ora_exec($cursor);

						$updated_rows++;
					}
				}
			}

			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						VARIABLE_LIST,
						COMPLETION_STATUS)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'".$user."',
						SYSDATE,
						'EMAIL',
						'WDIPULLS',
						'".$cur_ves."',
						'PENDING')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

//			mail("awalter@port.state.de.us,sadu@port.state.de.us", "QC uploaded".$cur_ves."\r\n", "Adam looks awesome", "");

			echo "<font color=\"#0000FF\" size=\"4\"><b>".$updated_rows." Successfully Updated.<b></font><br>";
			if($error_display != ""){
				echo "<font color=\"#FF0000\" size=\"4\"><b>The following pallets could not be updated:<br>".$error_display."</b</font><br>";
			}
		}
	}
	
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Upload QC File
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="walmart_QC_update_index.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."' OR RECEIVER_ID IN (SELECT CUSTOMER_ID FROM CHILEAN_EXPEDITER_CUST_LIST WHERE EPORT_LOGIN = '".$user."')) AND LR_NUM NOT IN (SELECT LR_NUM FROM WDI_VESSEL_RELEASE) ORDER BY LR_NUM DESC";

   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT') AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVER_ID = '".$user_cust_num."') AND LR_NUM NOT IN (SELECT LR_NUM FROM WDI_VESSEL_RELEASE) ORDER BY LR_NUM DESC";
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
<?
	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_EXPEDITER_CUST_LIST WHERE EPORT_LOGIN = '".$user."'"; 
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($Short_Term_Row['THE_COUNT'] > 0){
?>
		<td align="left">Customer to view:  <select name="view_cust">
<?
		$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL WHERE CECL.EPORT_LOGIN = '".$user."' AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID ORDER BY CP.CUSTOMER_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($view_cust == $row['CUSTOMER_ID']){?> selected <?}?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
					</select></td>
<?
	}
?>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">(Note:  If the list is empty, there are no vessels available to be QC'ed at this time.<br>We upload the vessel data as soon as we get it from the shipping line.<br>Please check back again.)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Vessel Info"></td>
	</tr>
</form>
</table>
<?
	if($cur_ves != ""){
?>
<br><br>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="file_submit" action="walmart_QC_update_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
	<tr>
		<td align="left"><font size="3" face="Verdana">Select Excel File:</font>  <font size="2" face="Verdana"><!--<a href="ImportXLSInstructions.doc">(File Instructions)</a>!--></font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"></td>
	</tr>
</form>
</table>
<br><br>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT PALLET_ID, NVL(COMMODITY, 'N/A') THE_COMM, NVL(LABEL_CODE, 'N/A') THE_LABEL, HATCH, QTY_RECEIVED, 
					WAREHOUSE_LOCATION, EXPORTER_CODE, NVL(TO_CHAR(ITEM_NUM), 'N/A') THE_ITEM, MARK, NVL(GROWER, 'N/A') THE_GROWER
				FROM CARGO_TRACKING CT, WM_ITEMNUM_MAPPING WIM 
				WHERE ARRIVAL_NUM = '".$cur_ves."' AND RECEIVER_ID = '".$user_cust_num."' 
					AND CT.BATCH_ID = TO_CHAR(WIM.ITEM_NUM(+)) ORDER BY PALLET_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">No cargo registered for current Vessel/Customer combination</font></td>
	</tr>
<?
		} else {
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$user_cust_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
		<td colspan="<? echo $colspan; ?>" align="center"><font size="3" face="Verdana"><b>Expected Cargo For <? echo $Short_Term_Row['CUSTOMER_NAME']; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>PLT_ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Hatch</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty</b></font></td>
		<td><font size="2" face="Verdana"><b>Loc</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower ID#</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower Item #</b></font></td>
		<td><font size="2" face="Verdana"><b>PO</b></font></td>
	</tr>
<?
			do {
				$temp = explode("_", $row['EXPORTER_CODE'], 2);
				if($temp[0] >= 0){
					$grower_id = $temp[0];
				} else {
					$grower_id = "MULTIPLE";
				}
/*				if($row['SUB_CUSTID'] != abs($row['SUB_CUSTID'])){
					$grower_id = 'MULTIPLE';
				} else {
					$grower_id = $row['SUB_CUSTID'];
				} */
						
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_COMM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_LABEL']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['HATCH']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WAREHOUSE_LOCATION']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_GROWER']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $grower_id; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_ITEM']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $row['MARK']; ?>&nbsp;</font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>
</table>
<?
//	include("pow_footer.php");
?>