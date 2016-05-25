<?
/*
*	Adam Walter, May 2013
*
*	A page to allow entry HD requests
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HD System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$submit = $HTTP_POST_VARS['submit'];
	$del_file = $HTTP_POST_VARS['del_file'];

	if($submit == "Upload File" && $vessel != "" && $cust != ""){
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".addorupdate".date(mdYhis);
		$impfile_sourcenote = basename($HTTP_POST_FILES['import_file']['name']);
//		echo "filename: ".$impfile_sourcenote."<br>";
//		echo "imp-filename: ".substr($impfile_sourcenote, -35)."<br>";
		$target_path_import = "./add_or_update_files/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}

		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$useable_data = array();

		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		$upload_valid = true;

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
//			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["variety"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["label"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["size"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["hatch"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["whs"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["grower"] = trim($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["pkg"] = trim($data->sheets[0]['cells'][$i][10]);

//			print_r($useable_data[($i - 1)]);
//			echo "<br>";
		}

		$upload_valid = validate_upload($useable_data, $vessel, $cust, $rfconn);

		// make sure this isnt a duplicated filename
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE SOURCE_NOTE LIKE '%".$impfile_sourcenote."%'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$previous_filecount = ociresult($stid, "THE_COUNT");

		if($upload_valid != ""){
			echo "<font color=\"#FF0000\">The following errors were found in the uploaded file:<br><br>".$upload_valid."<br>Please Correct and Resubmit.</font>";
			$submit = "";
			// this file didn't work, update accordingly.
/*			$sql = "UPDAET WIP_UPLOADS
					SET VALID = 'N'
					WHERE UPLOAD_ID = '".$upload_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor); */
		} elseif($previous_filecount > 0) {
			echo "<font color=\"#FF0000\">The system has already detected a file uploaded with this filename.<br>Please change the filename and re-upload.</font>";
			$submit = "";
		} else {
			// clear previous data from holding table, in case someoen "prematurely cancelled" this program earlier.
			$sql = "DELETE FROM ADD_OR_UPDATE_HOLD_TABLE";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			// store upload file in holding table
			for($i = 2; $i <= $data->sheets[0]['numRows']; $i++){
				$sql = "INSERT INTO ADD_OR_UPDATE_HOLD_TABLE
							(ARRIVAL_NUM,
							RECEIVER_ID,
							PALLET_ID,
							COMMODITY_CODE,
							VARIETY,
							REMARK,
							CARGO_SIZE,
							HATCH,
							QTY,
							WAREHOUSE_LOCATION,
							CARGO_DESCRIPTION,
							BATCH_ID,
							SOURCE_NOTE,
							SOURCE_USER)
						VALUES
							('".$vessel."',
							'".$cust."',
							'".$useable_data[($i - 1)]["BC"]."',
							'".$useable_data[($i - 1)]["comm"]."',
							'".$useable_data[($i - 1)]["variety"]."',
							'".$useable_data[($i - 1)]["label"]."',
							'".$useable_data[($i - 1)]["size"]."',
							'".$useable_data[($i - 1)]["hatch"]."',
							'".$useable_data[($i - 1)]["qty"]."',
							'".$useable_data[($i - 1)]["whs"]."',
							'".$useable_data[($i - 1)]["grower"]."',
							'".$useable_data[($i - 1)]["pkg"]."',
							'add-or-update:".substr($impfile_sourcenote, -35)."',
							'".$user."')";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
			}
		}
	} elseif($submit == "Commit"){
		$insert_count = 0;
		$update_count = 0;
		$success = true;

		$sql = "SELECT * FROM ADD_OR_UPDATE_HOLD_TABLE";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
					WHERE PALLET_ID = '".ociresult($stid, "PALLET_ID")."'
						AND RECEIVER_ID = '".ociresult($stid, "RECEIVER_ID")."'
						AND ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") >= 1){		
				$sql = "UPDATE CARGO_TRACKING
						SET COMMODITY_CODE = '".ociresult($stid, "COMMODITY_CODE")."',
							QTY_RECEIVED = '".ociresult($stid, "QTY")."',
							QTY_IN_HOUSE = '".ociresult($stid, "QTY")."',
							SOURCE_NOTE = '".ociresult($stid, "SOURCE_NOTE")."',
							SOURCE_USER = '".ociresult($stid, "SOURCE_USER")."'";
				if(ociresult($stid, "VARIETY") != ""){
					$sql .= ", VARIETY = '".ociresult($stid, "VARIETY")."'";
				}
				if(ociresult($stid, "REMARK") != ""){
					$sql .= ", REMARK = '".ociresult($stid, "REMARK")."'";
				}
				if(ociresult($stid, "CARGO_SIZE") != ""){
					$sql .= ", CARGO_SIZE = '".ociresult($stid, "CARGO_SIZE")."'";
				}
				if(ociresult($stid, "HATCH") != ""){
					$sql .= ", HATCH = '".ociresult($stid, "HATCH")."'";
				}
				if(ociresult($stid, "WAREHOUSE_LOCATION") != ""){
					$sql .= ", WAREHOUSE_LOCATION = '".ociresult($stid, "WAREHOUSE_LOCATION")."'";
				}
				if(ociresult($stid, "CARGO_DESCRIPTION") != ""){
					$sql .= ", CARGO_DESCRIPTION = '".ociresult($stid, "CARGO_DESCRIPTION")."'";
				}
				if(ociresult($stid, "BATCH_ID") != ""){
					$sql .= ", BATCH_ID = '".ociresult($stid, "BATCH_ID")."'";
				}
				$sql .= " WHERE PALLET_ID = '".ociresult($stid, "PALLET_ID")."'
							AND RECEIVER_ID = '".ociresult($stid, "RECEIVER_ID")."'
							AND ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."'";
				$short_term_data = ociparse($rfconn, $sql);
				$success = ($success && ociexecute($short_term_data, OCI_NO_AUTO_COMMIT));
				$update_count++;
			} else {
				$sql = "INSERT INTO CARGO_TRACKING
							(PALLET_ID,
							RECEIVER_ID,
							ARRIVAL_NUM,
							COMMODITY_CODE,
							QTY_RECEIVED,
							QTY_IN_HOUSE,
							VARIETY,
							REMARK,
							CARGO_SIZE,
							HATCH,
							WAREHOUSE_LOCATION,
							CARGO_DESCRIPTION,
							BATCH_ID,
							RECEIVING_TYPE,
							SOURCE_NOTE,
							SOURCE_USER)
						(SELECT 
							PALLET_ID,
							RECEIVER_ID,
							ARRIVAL_NUM,
							COMMODITY_CODE,
							QTY,
							QTY,
							VARIETY,
							REMARK,
							CARGO_SIZE,
							HATCH,
							WAREHOUSE_LOCATION,
							CARGO_DESCRIPTION,
							BATCH_ID,
							DECODE(ARRIVAL_NUM, '9999999', 'T', 'S'),
							SOURCE_NOTE,
							SOURCE_USER
						FROM ADD_OR_UPDATE_HOLD_TABLE
						WHERE PALLET_ID = '".ociresult($stid, "PALLET_ID")."'
							AND RECEIVER_ID = '".ociresult($stid, "RECEIVER_ID")."'
							AND ARRIVAL_NUM = '".ociresult($stid, "ARRIVAL_NUM")."')";
//				echo $sql."<br>";
				$short_term_data = ociparse($rfconn, $sql);
				$success = ($success && ociexecute($short_term_data, OCI_NO_AUTO_COMMIT));
				$insert_count++;
			}
		}

		// eliminate the holding table for the next user, to (hopefully) prevent synchronous usage duplication
		$sql = "DELETE FROM ADD_OR_UPDATE_HOLD_TABLE";
		$stid = ociparse($rfconn, $sql);
		$success = ($success && ociexecute($stid, OCI_NO_AUTO_COMMIT));

		if($success === false){
			echo "<font color=\"#FF0000\">Databse Save Failed.  Please contact TS, and take a screenshot of the error message.</font><br>";
			ocirollback($rfconn);
		} else {
			echo "<font color=\"#0000FF\">Pallets Saved.</font><br>";
			echo "<font color=\"#0000FF\">".$insert_count." Inserted and ".$update_count." Updated.</font><br>";
			ocicommit($rfconn);
		}
	} elseif($submit == "Delete File" && $del_file != ""){
		$sql = "DELETE FROM CARGO_TRACKING
				WHERE SOURCE_NOTE = '".$del_file."'
					AND (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) NOT IN
						(SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM
						FROM CARGO_ACTIVITY)";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">Pallets From file ".$del_file." Deleted.</font><br>";
	} elseif($submit == "Upload Locations Only" && $vessel != ""){
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".locations".date(mdYhis);
		$impfile_sourcenote = basename($HTTP_POST_FILES['import_file']['name']);
//		echo "filename: ".$impfile_sourcenote."<br>";
//		echo "imp-filename: ".substr($impfile_sourcenote, -35)."<br>";
		$target_path_import = "./add_or_update_files/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}

		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$useable_data = array();

		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		$upload_valid = true;

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["loc"] = trim($data->sheets[0]['cells'][$i][3]);

//			print_r($useable_data[($i - 1)]);
//			echo "<br>";
		}

		$upload_valid = validate_locations($useable_data, $vessel, $rfconn);

		if($upload_valid != ""){
			echo "<font color=\"#FF0000\">The following errors were found in the uploaded file:<br><br>".$upload_valid."<br>Please Correct and Resubmit.</font>";
			$submit = "";
			// this file didn't work, update accordingly.
/*			$sql = "UPDAET WIP_UPLOADS
					SET VALID = 'N'
					WHERE UPLOAD_ID = '".$upload_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor); */
		} else {
			$updated = 0;
			$no_match = "";
			
			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

				$sql = "SELECT COUNT(*) THE_COUNT
						FROM CARGO_TRACKING
						WHERE PALLET_ID = '".$useable_data[($i - 1)]["BC"]."'
							AND RECEIVER_ID = '".$useable_data[($i - 1)]["cust"]."'
							AND ARRIVAL_NUM = '".$vessel."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				ocifetch($short_term_data);
				if(ociresult($short_term_data, "THE_COUNT") >= 1){
					$sql = "SELECT DATE_RECEIVED
							FROM CARGO_TRACKING
							WHERE PALLET_ID = '".$useable_data[($i - 1)]["BC"]."'
								AND RECEIVER_ID = '".$useable_data[($i - 1)]["cust"]."'
								AND ARRIVAL_NUM = '".$vessel."'
								AND DATE_RECEIVED IS NOT NULL";
					$short_term_data = ociparse($rfconn, $sql);
					ociexecute($short_term_data);
					ocifetch($short_term_data);
					if(ociresult($short_term_data, "DATE_RECEIVED") == ""){
						$no_match .= "Cust ".$useable_data[($i - 1)]["cust"]." - Pallet ".$useable_data[($i - 1)]["BC"]." - LR# ".$vessel." has no Date Received, cannot change current Warehouse Location.<br>";
					} else {

						$updated++;

						$sql = "UPDATE CARGO_TRACKING
								SET WAREHOUSE_LOCATION = '".$useable_data[($i - 1)]["loc"]."'
								WHERE PALLET_ID = '".$useable_data[($i - 1)]["BC"]."'
									AND RECEIVER_ID = '".$useable_data[($i - 1)]["cust"]."'
									AND ARRIVAL_NUM = '".$vessel."'";
						$update_data = ociparse($rfconn, $sql);
						ociexecute($update_data);
					}
				} else {
					$no_match .= "Cust ".$useable_data[($i - 1)]["cust"]." - Pallet ".$useable_data[($i - 1)]["BC"]." not in system for LR# ".$vessel."<br>";
				}
			}

			echo "<font color=\"#0000FF\">".$updated." pallet locations updated.</font><br>";
			if($no_match != ""){
				echo "<font color=\"#FF0000\">The following pallets could not be changed:<br>".$no_match."</font><br>";
			}
		}
	}





?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add or Update 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="add_or_update.php" method="post">
	<tr>
		<td><font size="2" face="Verdana">(Note:  Please choose vessel 9999999 for pending Trucks.<br>Order# will be attached to the trucks at time of scan-receipt)</font></td>
	</tr>
	<tr>
		<td align="left">Arrival:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE (SHIP_PREFIX IN ('ARG FRUIT', 'CHILEAN') OR LR_NUM = '9999999') ORDER BY LR_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "LR_NUM"); ?>"<? if($vessel == ociresult($stid, "LR_NUM")){?> selected <?}?>><? echo ociresult($stid, "LR_NUM")." - ".ociresult($stid, "VESSEL_NAME"); ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td align="left">Customer:  <select name="cust">
								<option value=""<? if($cur_cust == ""){?> selected <?}?>>Select a Customer</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "CUSTOMER_ID"); ?>"<? if($cust == ociresult($stid, "CUSTOMER_ID")){?> selected <?}?>>
									<? echo ociresult($stid, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select File:</font>  <font size="2" face="Verdana">(.xls file please)</font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload File"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Upload File"){
		// NOTE:  the mutli-select boxes do NOT affect the outcome.  They are for display use only 
?>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="save_data" action="add_or_update.php" method="post">
	<tr>
		<td colspan="5" align="center"><input type="submit" name="submit" value="Commit"></td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="40%"><font size="2" face="Verdana"><b>To Add:</b></font></td>
		<td width="10%">&nbsp;</td>
		<td width="40%"><font size="2" face="Verdana"><b>To Update:</b></font></td>
		<td width="5%">&nbsp;</td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="40%"><select name="Add" multiple=true size=10 style="width:230px" readonly>
<?
		$sql = "SELECT PALLET_ID, RECEIVER_ID
				FROM ADD_OR_UPDATE_HOLD_TABLE 
				WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) NOT IN
					(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING)
				ORDER BY PALLET_ID";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "PALLET_ID").ociresult($stid, "RECEIVER_ID"); ?>"><? echo ociresult($stid, "PALLET_ID").
								" (cust".ociresult($stid, "RECEIVER_ID").")"; ?></option>
<?
		}
?>
				</select></td>
		<td width="10%">&nbsp;</td>
		<td width="40%"><select name="Update" multiple=true size=10 style="width:230px" readonly>
<?
		$sql = "SELECT PALLET_ID, RECEIVER_ID
				FROM ADD_OR_UPDATE_HOLD_TABLE 
				WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN
					(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING)
				ORDER BY PALLET_ID";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "PALLET_ID").ociresult($stid, "RECEIVER_ID"); ?>"><? echo ociresult($stid, "PALLET_ID").
								" (cust".ociresult($stid, "RECEIVER_ID").")"; ?></option>
<?
		}
?>
				</select></td>
		<td width="5%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;<hr></td>
	</tr>
</form>
</table>
<?
	}
?>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="del_data" action="add_or_update.php" method="post">
	<tr>
		<td><font size="4" face="Verdana"><b>Delete Previous File:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>(Note:  Can only show files which have NO pallets on them showing any activity, including receiving scan.<br>If your file does not appear, please contact TS)</b></font></td>
	</tr>
	<tr>
		<td align="left">File to delete:  <select name="del_file">
								<option value="">Select file to DELETE</option>
<?
	$sql = "SELECT DISTINCT ARRIVAL_NUM, SOURCE_NOTE
			FROM CARGO_TRACKING
			WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) NOT IN
				(SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM
				FROM CARGO_ACTIVITY)
				AND SOURCE_NOTE LIKE 'add-or-update:%'
			ORDER BY ARRIVAL_NUM";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "SOURCE_NOTE"); ?>"><? echo ociresult($stid, "SOURCE_NOTE")." (LR# ".ociresult($stid, "ARRIVAL_NUM").")"; ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Delete File"><hr></td>
	</tr>
</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_loc" action="add_or_update.php" method="post">
	<tr>
		<td><font size="4" face="Verdana"><b>Change Locations Only:</b></font></td>
	</tr>
	<tr>
		<td align="left">Arrival:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE (SHIP_PREFIX IN ('ARG FRUIT', 'CHILEAN') OR LR_NUM = '9999999') ORDER BY LR_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
								<option value="<? echo ociresult($stid, "LR_NUM"); ?>"<? if($vessel == ociresult($stid, "LR_NUM")){?> selected <?}?>><? echo ociresult($stid, "LR_NUM")." - ".ociresult($stid, "VESSEL_NAME"); ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select File:</font>  <font size="2" face="Verdana">(.xls file please)</font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload Locations Only"><hr></td>
	</tr>
</form>
</table>

<?
	include("pow_footer.php");















function validate_locations($useable_data, $vessel, $rfconn){
	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";

// step 1, make sure no fields have an invalid character (" or , or ' or \)

	foreach($useable_data as $line_no => $line){
		foreach($line as $cellname => $cell){
			if(strpos($cell, "\"") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Doublequotes in input file.<br>";
			}
			if(strpos($cell, "\\") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Backslashes in input file.<br>";
			}
			if(strpos($cell, ",") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Commas in input file.<br>";
			}
			if(strpos($cell, "'") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Singlequotes in input file.  If an apostrophe is necessary, please use a Tick mark (`)<br>";
			}
		}
	}

// step 2, for each line...
	$i = 1; // HEADERS
//	$i = 0; // NO HEADERS
	while($useable_data[$i]["cust"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.
//		echo "Line".$i."<br>";

// 2 - 1)  customer
		if(strlen($useable_data[$i]["cust"]) > 6){
			$return .= "Line: ".$badline." - Customer cannot be longer than 6 characters.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["cust"])){
			$return .= "Line: ".$badline." - Customer is required and must be numeric.<br>";
		}
// 2 - 2)  BC 
		if(strlen($useable_data[$i]["BC"]) > 32){
			$return .= "Line: ".$badline." - Barcode cannot be longer than 32 characters.<br>";
		}
		if(!ereg("^(.)+$", $useable_data[$i]["BC"])){
			$return .= "Line: ".$badline." - Barcode is required.<br>";
		}
// 2 - 3)  location 
		if(strlen($useable_data[$i]["loc"]) > 12){
			$return .= "Line: ".$badline." - Location cannot be longer than 6 characters.<br>";
		}
		if(!ereg("^(.)+$", $useable_data[$i]["loc"])){
			$return .= "Line: ".$badline." - Location is required.<br>";
		}

// section 3:  valid string, invalid option

// 3 - 1)  Customer#
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID = '".$useable_data[$i]["cust"]."'";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Customer# Not in CUSTOMER_PROFILE.<br>";
		}

		$i++;
	}
	return $return;
}





function validate_upload($useable_data, $vessel, $cust, $rfconn){

	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";
/*
			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["variety"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["label"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["size"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["hatch"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["whs"] = trim($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["grower"] = trim(substr($data->sheets[0]['cells'][$i][10], 0, 2));
			$useable_data[($i - 1)]["pkg"] = trim($data->sheets[0]['cells'][$i][11]);
*/
// step 1, make sure no fields have an invalid character (" or , or ' or \)

	foreach($useable_data as $line_no => $line){
		foreach($line as $cellname => $cell){
			if(strpos($cell, "\"") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Doublequotes in input file.<br>";
			}
			if(strpos($cell, "\\") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Backslashes in input file.<br>";
			}
			if(strpos($cell, ",") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Commas in input file.<br>";
			}
			if(strpos($cell, "'") !== false){
				$return .= ($line_no + 1)." - cell ".$cellname." - Cannot use Singlequotes in input file.  If an apostrophe is necessary, please use a Tick mark (`)<br>";
			}
		}
	}
// step 2, for each line...
	$i = 1; // HEADERS
//	$i = 0; // NO HEADERS
	while($useable_data[$i]["cust"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.
//		echo "Line".$i."<br>";
/*
// 2 - 1)  customer
		if(strlen($useable_data[$i]["cust"]) > 6){
			$return .= "Line: ".$badline." - Customer cannot be longer than 6 characters.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["cust"])){
			$return .= "Line: ".$badline." - Customer is required and must be numeric.<br>";
		}
*/

// 2 - 2)  BC 
		if(strlen($useable_data[$i]["BC"]) > 32){
			$return .= "Line: ".$badline." - Barcode cannot be longer than 32 characters.<br>";
		}
		if(!ereg("^(.)+$", $useable_data[$i]["BC"])){
			$return .= "Line: ".$badline." - Barcode is required.<br>";
		}


// 2 - 3)  "comm" 
		if(strlen($useable_data[$i]["comm"]) > 6){
			$return .= "Line: ".$badline." - Commodity Code cannot be longer than 6 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["comm"])){
			$return .= "Line: ".$badline." - Commodity Code is required and must be numeric.<br>";
		}


// 2 - 4)  variety
		if(strlen($useable_data[$i]["variety"]) > 20){
			$return .= "Line: ".$badline." - Variety cannot be longer than 20 characters.<br>";
		}

// 2 - 5)  "label" (CARGO_TRACKING.REMARK)
		if(strlen($useable_data[$i]["label"]) > 20){
			$return .= "Line: ".$badline." - Label cannot be longer than 20 characters.<br>";
		}

// 2 - 6)  size
		if(strlen($useable_data[$i]["size"]) > 6){
			$return .= "Line: ".$badline." - Size cannot be longer than 6 characters.<br>";
		}

// 2 - 7)  hatch
		if(strlen($useable_data[$i]["hatch"]) > 5){
			$return .= "Line: ".$badline." - Hatch cannot be longer than 5 characters.<br>";
		}
/*		if(!ereg("^(.)+$", $useable_data[$i]["hatch"])){
			$return .= "Line: ".$badline." - Hatch is required.<br>";
		}*/

// 2 - 8)  qty
		if(strlen($useable_data[$i]["qty"]) > 6){
			$return .= "Line: ".$badline." - QTY cannot be longer than 6 digits.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
			$return .= "Line: ".$badline." - QTY is required and must be numeric.<br>";
		}

// 2 - 9)  whs
		if(strlen($useable_data[$i]["whs"]) > 12){
			$return .= "Line: ".$badline." - Warehouse cannot be longer than 12 characters.<br>";
		}

// 2 - 10)  grower (CARGO_TRACKING.CARGO_DESCRIPTION)
		if(strlen($useable_data[$i]["grower"]) > 60){
			$return .= "Line: ".$badline." - Grower cannot be longer than 60 characters.<br>";
		}

// 2 - 11)  pkg (CARGO_TRACKING.BATCH_ID)
		if(strlen($useable_data[$i]["pkg"]) > 10){
			$return .= "Line: ".$badline." - PKG-type cannot be longer than 10 characters.<br>";
		}


// section 3:  valid string, invalid option

// 3 - 1)  Shipping Line
/*		$sql = "SELECT COUNT(*) THE_COUNT
				FROM EXCEL_MANIFEST_SORT_ORDER
				WHERE SHIPLINE = '".$useable_data[$i]["shipline"]."'";
		@ora_parse($cursor, $sql);
		@ora_exec($cursor);
		@ora_fetch_into($cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] <= 0){
			$return .= "Line: ".$badline." - Shipping Line invalid; please refer to the Instruction document.<br>";
		}
*/
// 3 - 2)  Commodity code
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM COMMODITY_PROFILE
				WHERE COMMODITY_CODE = '".$useable_data[$i]["comm"]."'";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Commodity Not in COMMODITY_PROFILE.<br>";
		}
/*
// 3 - 3)  Customer#
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CUSTOMER_PROFILE
				WHERE CUSTOMER_ID = '".$useable_data[$i]["cust"]."'";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Customer# Not in CUSTOMER_PROFILE.<br>";
		}
*/
// 4 : make sure this isnt an already-shipped pallet :)
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND CT.RECEIVER_ID = '".$useable_data[$i]["cust"]."'
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CA.SERVICE_CODE IN ('6', '11')
					AND CA.ACTIVITY_NUM != '1'
					";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") >= 1){
			$return .= "Line: ".$badline." - Pallet already has outgoing Activity.  Cannot modify from this screen.<br>";
		}

		$i++;

	}

	return $return;

}
