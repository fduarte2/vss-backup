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
	$cursor = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];

	$non_POW_rows = 0;

	if($submit != ""){
//		$impfilename = basename($HTTP_POST_FILES['import_file']['name']);
		$impfilename = "TEST".basename($HTTP_POST_FILES['import_file']['name']);
		$target_path_import = "./uploads/".$impfilename;

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

		$badrow = false;

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$useable_data[($i - 1)]["load"] = AlterRow($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["carrier"] = AlterRow($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["truck"] = AlterRow($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["PO"] = AlterRow($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["samssc"] = AlterRow($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["bkhltraffic"] = ""; //AlterRow($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["vendor"] = AlterRow($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["loaddate"] = AlterRow($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["delvdate"] = AlterRow($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["temp"] = AlterRow($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["cases"] = AlterRow($data->sheets[0]['cells'][$i][10]);
			$useable_data[($i - 1)]["bins"] = ""; //AlterRow($data->sheets[0]['cells'][$i][12]);
			$useable_data[($i - 1)]["plts"] = AlterRow($data->sheets[0]['cells'][$i][11]);
			$useable_data[($i - 1)]["weight"] = AlterRow($data->sheets[0]['cells'][$i][12]);
			$useable_data[($i - 1)]["proddesc"] = AlterRow($data->sheets[0]['cells'][$i][13]);
			$useable_data[($i - 1)]["item"] = AlterRow($data->sheets[0]['cells'][$i][14]);
			$useable_data[($i - 1)]["pustate"] = AlterRow($data->sheets[0]['cells'][$i][15]);
			$useable_data[($i - 1)]["secondpu"] = ""; //AlterRow($data->sheets[0]['cells'][$i][18]);
			$useable_data[($i - 1)]["thirdpu"] = ""; //AlterRow($data->sheets[0]['cells'][$i][19]);
			$useable_data[($i - 1)]["tlltl"] = ""; //AlterRow($data->sheets[0]['cells'][$i][20]);
			$useable_data[($i - 1)]["singleteam"] = ""; //AlterRow($data->sheets[0]['cells'][$i][21]);
			$useable_data[($i - 1)]["trailerlen"] = ""; //AlterRow($data->sheets[0]['cells'][$i][22]);
			$useable_data[($i - 1)]["pickuploc"] = AlterRow($data->sheets[0]['cells'][$i][16]);
			$useable_data[($i - 1)]["specialinst"] = AlterRow($data->sheets[0]['cells'][$i][17]);

			//  
			if($useable_data[($i - 1)]["PO"] != "" && $useable_data[($i - 1)]["pickuploc"] == "POW" && $useable_data[($i - 1)]["pustate"] == "DE"){
				// verify that, for OUR rows, the data is good.
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["load"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["carrier"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["truck"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["PO"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["samssc"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["bkhltraffic"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["vendor"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["loaddate"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["delvdate"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["temp"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["cases"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["bins"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["plts"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["weight"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["proddesc"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["item"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["pustate"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["secondpu"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["thirdpu"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["tlltl"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["singleteam"]));
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["trailerlen"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["pickuploc"]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["specialinst"]));
			}

			if($useable_data[($i - 1)]["pickuploc"] != "POW"){
				$non_POW_rows++;
			}

//			echo $i." ".$useable_data[($i - 1)]["loaddate"]." ".$useable_data[($i - 1)]["delvdate"]."<br>";
//			echo $i." Load: ".$data->sheets[0]['cellsInfo'][$i][8]['type']." Delv: ".$data->sheets[0]['cellsInfo'][$i][9]['type']."<br>";
//			echo $data->isDate($useable_data[($i - 1)]["loaddate"])."<br>";

			if($useable_data[($i - 1)]["load"] == ""){
				// will work so long as very first line has a load#,
				// needed since file doesn't "copy" the number all the way down.
				$useable_data[($i - 1)]["load"] = $current_load;
			}

			$current_load = $useable_data[($i - 1)]["load"];

			if($i == 1){
				// we don't want to "validate" the column headers
				$badrow = false;
			}

			// 
			if($badrow === true && $useable_data[($i - 1)]["PO"] != "" && $useable_data[($i - 1)]["pickuploc"] == "POW" && $useable_data[($i - 1)]["pustate"] == "DE"){
				$upload_success = "DBSTRICT FAILED";
				echo "File Upload aborted on line ".$i."; invalid characters detected.  Please use you browser's back button to return to the previous page.<br>";
/*				echo $useable_data[($i - 1)]["load"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["carrier"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["truck"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["PO"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["samssc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["bkhltraffic"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["vendor"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["loaddate"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["delvdate"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["temp"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["cases"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["bins"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["plts"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["weight"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["proddesc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["item"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["pustate"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["secondpu"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["thirdpu"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["tlltl"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["singleteam"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["trailerlen"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["pickuploc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[($i - 1)]["specialinst"]."<br>"; */
				exit;
			}
		}

		// array populated.  Make sure the values pass mustard.
		$result = validate_dbsafe_data($upload_num, $useable_data, $i, $conn, $Short_Term_Cursor);
		if($result != ""){
			$upload_success = "DBSTRICT FAILED";
			echo "File Upload aborted; the following errors prevented uploading the file to the database:<br>".$result."  Please use you browser's back button to return to the previous page.";
			exit;
		}

		$sql = "SELECT WM_UPLOAD_HIST_UPLOAD_NUM_SEQ.NEXTVAL THE_MAX FROM DUAL";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$upload_num = $row['THE_MAX'];

		$num_entries = $i - 1;

		$sql = "INSERT INTO WM_UPLOAD_HISTORY
					(UPLOAD_NUMBER,
					UPLOAD_DATE,
					FILENAME,
					FILENAME_APPENDED,
					NUM_ROWS_IN_FILE,
					UPLOAD_BY,
					ARRIVAL_NUM,
					STATUS)
				VALUES
					('".$upload_num."',
					SYSDATE,
					'".basename($HTTP_POST_FILES['import_file']['name'])."',
					'".$impfilename."',
					'".$num_entries."',
					'".$user."',
					'NA',
					'PRELOADED')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$uploaded_rows = 0;
		for($i = 1; $i <= $num_entries; $i++) { // NEED LOGIC HERE
/*				echo $useable_data[$i]["load"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["carrier"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["truck"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["PO"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["samssc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["bkhltraffic"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["vendor"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["loaddate"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["delvdate"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["temp"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["cases"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["bins"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["plts"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["weight"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["proddesc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["item"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["pustate"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["secondpu"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["thirdpu"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["tlltl"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["singleteam"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["trailerlen"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["pickuploc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["specialinst"]."<br>"; */
/*				echo $useable_data[$i]["load"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["PO"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["pickuploc"]."&nbsp;&nbsp;&nbsp;".
					$useable_data[$i]["pustate"]."<br>"; */
			if($useable_data[$i]["PO"] != "" && $useable_data[$i]["pickuploc"] == "POW" && $useable_data[$i]["pustate"] == "DE"){
				$sql = "INSERT INTO WDI_LOADFORM_RAWDUMP
							(UPLOAD_NUM,
							ROW_NUM,
							LOAD_NUM,
							CARRIER,
							TRUCK_NUM,
							DCPO_NUM,
							SAMS_SC,
							BKHL_TRAFFIC,
							VENDOR_NAME,
							LOAD_DATE,
							DELIVERY_DATE,
							TEMP,
							CASES,
							BINS,
							PALLETS,
							WEIGHT,
							PRODUCT_DESCRIPTION,
							ITEM_NUM,
							PU_STATE,
							PU_STATE2,
							PU_STATE3,
							TL_LTL,
							SINGLE_TEAM,
							TRAILER_LENGTH,
							PICK_UP_LOCATION,
							SPECIAL_INSTRUCTIONS)
						VALUES
							('".$upload_num."',
							'".$i."',
							'".$useable_data[$i]["load"]."',
							'".$useable_data[$i]["carrier"]."',
							'".$useable_data[$i]["truck"]."',
							'".$useable_data[$i]["PO"]."',
							'".$useable_data[$i]["samssc"]."',
							'".$useable_data[$i]["bkhltraffic"]."',
							'".$useable_data[$i]["vendor"]."',
							TO_DATE('".$useable_data[$i]["loaddate"]."', 'MM/DD/YYYY'),
							TO_DATE('".$useable_data[$i]["delvdate"]."', 'MM/DD/YYYY'),
							'".$useable_data[$i]["temp"]."',
							'".$useable_data[$i]["cases"]."',
							'".$useable_data[$i]["bins"]."',
							'".$useable_data[$i]["plts"]."',
							'".$useable_data[$i]["weight"]."',
							'".$useable_data[$i]["proddesc"]."',
							'".$useable_data[$i]["item"]."',
							'".$useable_data[$i]["pustate"]."',
							'".$useable_data[$i]["secondpu"]."',
							'".$useable_data[$i]["thirdpu"]."',
							'".$useable_data[$i]["tlltl"]."',
							'".$useable_data[$i]["singleteam"]."',
							'".$useable_data[$i]["trailerlen"]."',
							'".$useable_data[$i]["pickuploc"]."',
							'".$useable_data[$i]["specialinst"]."')";
	//			echo $sql."<br>";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$uploaded_rows++;
			}
		}
		if($uploaded_rows <= 0){
			$upload_success = "DBSTRICT FAILED";
			echo "File Upload aborted; No combinations of<br>Non-blank PO (column D)<br>and Pickup location = POW (column W) and<br>Pickup State = DE (column Q)<br>Were found in file<br>";
//			echo "File Upload aborted; No combinations of<br>Non-blank PO (column D)<br>and Pickup location = POW (column P) <br>Were found in file<br>";
			exit;
		}

/*			
			echo $useable_data[($i - 1)]["load"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["carrier"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["truck"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["PO"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["samssc"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["bkhltraffic"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["vendor"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["loaddate"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["delvdate"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["temp"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["cases"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["bins"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["plts"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["weight"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["proddesc"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["item"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["pustate"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["secondpu"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["thirdpu"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["tlltl"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["singleteam"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["trailerlen"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["pickuploc"]."&nbsp;&nbsp;&nbsp;".
				$useable_data[($i - 1)]["specialinst"]."<br>";
		}
*/
		// step 3:  validate and save to raw-dump table		
		$result = validate_upload_to_CT($upload_num, $conn, $Short_Term_Cursor);
//		echo $result;

		if($result == ""){
			$upload_success = "VALIDATED";
			$warning = per_order_check($upload_num, $result_table, $conn, $Short_Term_Cursor);
			if($warning == false){
				$success_message = $result_table."<br><font size=\"3\" face=\"Verdana\" color=\"#0000FF\">Upload File Validated; all orders cleared.  Press Confirm to save orders to PoW<br>";
			} else {
				$success_message = $result_table."<br><font size=\"3\" face=\"Verdana\" color=\"#FF0000\">Upload File Validated; Not all orders can be filled.<br>If you choose to confirm this file, it will allocate all of the pallets in the Blue lines above, and ignore any orders<br>(or parts of orders) in the red lines.  Previous entries of red lined-items will be maintained.<br>";
			}
			$success_message .= "Note:  ".$non_POW_rows." rows were skipped due to not having a \"PICKUPLOCATION\" of POW.</font><br>";
		} else {
			$upload_success = "REFERENCE FAILED";
			$success_message = "<font size=\"3\" face=\"Verdana\" color=\"#FF0000\">File could not be saved to PoW system; the following problems were detected:<br>".$result."---Please correct the issues and re-upload, or contact PoW for further assistance<br></font>";
		}
		$sql = "UPDATE WM_UPLOAD_HISTORY
				SET STATUS = '".$upload_success."'
				WHERE UPLOAD_NUMBER = '".$upload_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		

	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Upload File Review
</font>
	    <hr>
<?
		echo $success_message;
?>
	 </p>
      </td>
   </tr>
</table>
<?
	if($submit == "Upload"){
		if($result == ""){
			$submit_able = "";
		} else {
			$submit_able = "disabled";
		}
?>
<table border="1" cellpadding="4" cellspacing="0">
<form name="get_data" action="WM_order_auto.php" method="post">
<input type="hidden" name="upload_num" value="<? echo $upload_num; ?>">
	<tr>
		<td colspan="24"><input type="submit" name="submit" value="Commit File" <? echo $submit_able; ?>></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Load#</b></font></td>
		<td><font size="2" face="Verdana"><b>Carrier</b></font></td>
		<td><font size="2" face="Verdana"><b>TRUCK#</b></font></td>
		<td><font size="2" face="Verdana"><b>PO#</b></font></td>
		<td><font size="2" face="Verdana"><b>Sam's/SC</b></font></td>
		<td><font size="2" face="Verdana"><b>Bkhl/Traffic</b></font></td>
		<td><font size="2" face="Verdana"><b>VENDOR NAME</b></font></td>
		<td><font size="2" face="Verdana"><b>LOAD DATE</b></font></td>
		<td><font size="2" face="Verdana"><b>DELV. DATE</b></font></td>
		<td><font size="2" face="Verdana"><b>TEMP</b></font></td>
		<td><font size="2" face="Verdana"><b>CASES</b></font></td>
		<td><font size="2" face="Verdana"><b>Bins</b></font></td>
		<td><font size="2" face="Verdana"><b>PALLETS</b></font></td>
		<td><font size="2" face="Verdana"><b>WEIGHT</b></font></td>
		<td><font size="2" face="Verdana"><b>PRODUCT DESCRIPTION</b></font></td>
		<td><font size="2" face="Verdana"><b>ITEM #</b></font></td>
		<td><font size="2" face="Verdana"><b>P/U STATE</b></font></td>
		<td><font size="2" face="Verdana"><b>2nd p/u state</b></font></td>
		<td><font size="2" face="Verdana"><b>3rd p/u state</b></font></td>
		<td><font size="2" face="Verdana"><b>TL/LTL</b></font></td>
		<td><font size="2" face="Verdana"><b>Single/Team</b></font></td>
		<td><font size="2" face="Verdana"><b>Trailer Length</b></font></td>
		<td><font size="2" face="Verdana"><b>Pick UP Location</b></font></td>
		<td><font size="2" face="Verdana"><b>Special Instructions</b></font></td>
	<tr>
<?
		$sql = "SELECT WLR.*, TO_CHAR(WLR.LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, TO_CHAR(WLR.DELIVERY_DATE, 'MM/DD/YYYY') THE_DELV
				FROM WDI_LOADFORM_RAWDUMP WLR 
				WHERE UPLOAD_NUM = '".$upload_num."'
				ORDER BY ROW_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
							<td><font size="2" face="Verdana"><? echo $row['LOAD_NUM']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['CARRIER']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['TRUCK_NUM']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['DCPO_NUM']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['SAMS_SC']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['BKHL_TRAFFIC']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['VENDOR_NAME']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['THE_LOAD']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['THE_DELV']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['TEMP']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['CASES']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['BINS']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['PALLETS']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['WEIGHT']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['PRODUCT_DESCRIPTION']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['ITEM_NUM']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['PU_STATE']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['PU_STATE2']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['PU_STATE3']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['TL_LTL']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['SINGLE_TEAM']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['TRAILER_LENGTH']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['PICK_UP_LOCATION']; ?>&nbsp;</td>
							<td><font size="2" face="Verdana"><? echo $row['SPECIAL_INSTRUCTIONS']; ?>&nbsp;</td>
	</tr>
<?
		}
	}
?>
</table>
<?
	include("pow_footer.php");









function AlterRow($xls_value){

	$return = $xls_value;
	$return = trim($return);
	$return = strtoupper($return);
	$return = str_replace("'", "`", $return);
	$return = str_replace(",", "", $return);
	$return = str_replace(".", "", $return);

	return $return;
}

function all_chars_good($string){

	if(ereg("^([a-zA-Z0-9 _`/-])*$", $string)){
		return true;
	} else {
//		echo $string."<br>";
		return false;
	}
}

function validate_dbsafe_data($upload_num, $useable_data, $numrows, $conn, $Short_Term_Cursor){
	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";
/*
			$useable_data[($i - 1)]["load"] = AlterRow($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["carrier"] = AlterRow($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["truck"] = AlterRow($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["PO"] = AlterRow($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["samssc"] = AlterRow($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["bkhltraffic"] = AlterRow($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["vendor"] = AlterRow($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["loaddate"] = AlterRow($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["delvdate"] = AlterRow($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["temp"] = AlterRow($data->sheets[0]['cells'][$i][10]);
			$useable_data[($i - 1)]["cases"] = AlterRow($data->sheets[0]['cells'][$i][11]);
			$useable_data[($i - 1)]["bins"] = AlterRow($data->sheets[0]['cells'][$i][12]);
			$useable_data[($i - 1)]["plts"] = AlterRow($data->sheets[0]['cells'][$i][13]);
			$useable_data[($i - 1)]["weight"] = AlterRow($data->sheets[0]['cells'][$i][14]);
			$useable_data[($i - 1)]["proddesc"] = AlterRow($data->sheets[0]['cells'][$i][15]);
			$useable_data[($i - 1)]["item"] = AlterRow($data->sheets[0]['cells'][$i][16]);
			$useable_data[($i - 1)]["pustate"] = AlterRow($data->sheets[0]['cells'][$i][17]);
			$useable_data[($i - 1)]["secondpu"] = AlterRow($data->sheets[0]['cells'][$i][18]);
			$useable_data[($i - 1)]["thirdpu"] = AlterRow($data->sheets[0]['cells'][$i][19]);
			$useable_data[($i - 1)]["tlltl"] = AlterRow($data->sheets[0]['cells'][$i][20]);
			$useable_data[($i - 1)]["singleteam"] = AlterRow($data->sheets[0]['cells'][$i][21]);
			$useable_data[($i - 1)]["trailerlen"] = AlterRow($data->sheets[0]['cells'][$i][22]);
			$useable_data[($i - 1)]["pickuploc"] = AlterRow($data->sheets[0]['cells'][$i][23]);
			$useable_data[($i - 1)]["specialinst"] = AlterRow($data->sheets[0]['cells'][$i][24]);
*/

// step 1:  test the first line
// SKIPPING THIS STEP; column headers have wierd symbols and stuff, don't want to break here.
/*
	if($useable_data[0]["PORT_ORIGIN"] != "PORTORIGIN"
		|| $useable_data[0]["EXPORTER"] != "EXPORTER"
		|| $useable_data[0]["BOL"] != "BOL"
		|| $useable_data[0]["BARCODE"] != "PALLETID"
		|| $useable_data[0]["COMMODITY"] != "COMMODITY"
		|| $useable_data[0]["VAR_CODE"] != "VARIETYCODE"
		|| $useable_data[0]["VAR_DESC"] != "VARIETYDESC"
		|| $useable_data[0]["SIZE_CODE"] != "SIZECODE"
		|| $useable_data[0]["LABEL_CODE"] != "LABELCODE"
		|| $useable_data[0]["LABEL_DESC"] != "LABELDESC"
		|| $useable_data[0]["GROWER_AREA_CODE"] != "GROWERAREACODE"
		|| $useable_data[0]["GROWER_CODE"] != "GROWERCODE"
		|| $useable_data[0]["CTNCOUNT"] != "NUMBERBOXES"
		|| $useable_data[0]["VESSEL_STORE_CODE"] != "VSLSTORECODE"
		|| $useable_data[0]["CONTAINER"] != "CONTAINERNUM"
		|| $useable_data[0]["PREFUMED"] != "PREFUMED"
		|| $useable_data[0]["PACKING_CODE"] != "PACKINGCODE"
		|| $useable_data[0]["PACKING_DESC"] != "PACKINGDESC"
		|| $useable_data[0]["PACKING_NAME"] != "PACKINGNAME"
		|| $useable_data[0]["WM_PO_NUM"] != "WMPONUM"
		|| $useable_data[0]["GROWER_ITEM_NUM"] != "GROWERITEMNUM"
		|| $useable_data[0]["WM_PROGRAM"] != "PROGRAMTYPE"){
		return "File order does not match to expected format; please reference the Example on the previous page.";
	}
*/

// step 2, for each line...
//	$i = 1;
//	$i = 0;
	// start with $i = 1 to skip the column headers
	for($i = 1; $i < $numrows; $i++){
		if($useable_data[$i]["PO"] != "" && $useable_data[$i]["pickuploc"] == "POW" && $useable_data[$i]["pustate"] == "DE"){
			$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

	// 2 - 1)  validate Load#
			if(strlen($useable_data[$i]["load"]) > 12){
				$return .= "Line: ".$badline." - Load# cannot be longer than 12 characters.<br>";
			}

			if($useable_data[$i]["load"] != "" && !ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["load"])) {
				$return .= "Line: ".$badline." - Load# must be Alphanumeric.<br>";
			}

	// 2 - 2)  validate Carrier
			if(strlen($useable_data[$i]["carrier"]) > 6){
				$return .= "Line: ".$badline." - Carrier cannot be longer than 6 characters.<br>";
			}

			if($useable_data[$i]["carrier"] != "" && !ereg("^([0-9a-zA-Z _-])+$", $useable_data[$i]["carrier"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Carrier field.<br>";
			}

	// 2 - 3)  validate Truck#
			if(strlen($useable_data[$i]["truck"]) > 2){
				$return .= "Line: ".$badline." - TRUCK# field cannot be longer than 2 characters.<br>";
			}

			if($useable_data[$i]["truck"] != "" && !ereg("^([0-9])+$", $useable_data[$i]["truck"])){
				$return .= "Line: ".$badline." - TRUCK# must be numeric.<br>";
			}

	// 2 - 4)  validate DCPO field
			if(strlen($useable_data[$i]["PO"]) > 15){
				$return .= "Line: ".$badline." - PO# cannot be longer than 15 characters.<br>";
			}

			if(!ereg("^([0-9])+$", $useable_data[$i]["PO"])){
				$return .= "Line: ".$badline." - PO# number must be numeric.<br>";
			}

	// 2 - 5)  validate commodity
			if(strlen($useable_data[$i]["samssc"]) > 5){
				$return .= "Line: ".$badline." - Sams/SC field cannot be longer than 5 characters.<br>";
			}

			if($useable_data[$i]["samssc"] != "" && !ereg("^([0-9a-zA-Z _-])+$", $useable_data[$i]["samssc"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Sams/SC field.<br>";
			}
/*
	// 2 - 6)  validate BKHL/traffic
			if(strlen($useable_data[$i]["bkhltraffic"]) > 10){
				$return .= "Line: ".$badline." - BKHL/Traffic Field cannot be longer than 10 characters.<br>";
			}

			if($useable_data[$i]["bkhltraffic"] != "" && !ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["bkhltraffic"])){
				$return .= "Line: ".$badline." - BKHL/Traffic Field must be alphanumeric.<br>";
			}
*/
	// 2 - 7)  validate vendor name
			if(strlen($useable_data[$i]["vendor"]) > 25){
				$return .= "Line: ".$badline." - Vendor Name cannot be longer than 25 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _-])*$", $useable_data[$i]["vendor"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Vendor Name.<br>";
			}

	// 2 - 8)  validate load_date
			if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $useable_data[$i]["loaddate"])){
				$return .= "Line: ".$badline." - Load date must be in MM/DD/YYYY format.  ".$useable_data[$i]["loaddate"]."<br>";
			}

	// 2 - 9)  validate delivery date
			if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $useable_data[$i]["delvdate"])){
				$return .= "Line: ".$badline." - delivery date must be in MM/DD/YYYY format.  ".$useable_data[$i]["delvdate"]."<br>";
			}

	// 2 - 10)  validate temperature
			if(strlen($useable_data[$i]["temp"]) > 6){
				$return .= "Line: ".$badline." - Temperature Field cannot be longer than 6 characters.<br>";
			}

			if($useable_data[$i]["temp"] != "" && !ereg("^([0-9 _-])+$", $useable_data[$i]["temp"])) {
				$return .= "Line: ".$badline." - Temperature Field cannot contain letters.<br>";
			}

	// 2 - 11)  validate bin count
			if(strlen($useable_data[$i]["cases"]) > 5){
				$return .= "Line: ".$badline." - Cases Count cannot be longer than 5 characters.<br>";
			}

			if(!ereg("^([0-9])+$", $useable_data[$i]["cases"])){
				$return .= "Line: ".$badline." - Cases Count must be numeric and non-empty.<br>";
			}
/*
	// 2 - 12)  validate bin count
			if(strlen($useable_data[$i]["bins"]) > 5){
				$return .= "Line: ".$badline." - Bin Count cannot be longer than 5 characters.<br>";
			}

			if(!ereg("^([0-9])*$", $useable_data[$i]["bins"])){
				$return .= "Line: ".$badline." - Bin Count must be numeric.<br>";
			}
*/
	// 2 - 13)  validate pallet count
			if(strlen($useable_data[$i]["plts"]) > 5){
				$return .= "Line: ".$badline." - Pallet Count cannot be longer than 5 characters.<br>";
			}

			if(!ereg("^([0-9])+$", $useable_data[$i]["plts"])){
				$return .= "Line: ".$badline." - Pallet Count must be numeric and non-empty.<br>";
			}

	// 2 - 14)  validate weight
			if(strlen($useable_data[$i]["weight"]) > 7){
				$return .= "Line: ".$badline." - Weight Field cannot be longer than 7 digits.<br>";
			}

			if($useable_data[$i]["weight"] != "" && !ereg("^([0-9])+$", $useable_data[$i]["weight"])){
				$return .= "Line: ".$badline." - Weight Field must be numeric.<br>";
			}

	// 2 - 15)  validate product description
			if(strlen($useable_data[$i]["proddesc"]) > 30){
				$return .= "Line: ".$badline." - Product Description cannot be longer than 30 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _`/-])+$", $useable_data[$i]["proddesc"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Product Description.<br>";
			}

	// 2 - 16)  validate item#
			if(strlen($useable_data[$i]["item"]) > 10){
				$return .= "Line: ".$badline." - item # cannot be longer than 10 characters.<br>";
			}

			if(!ereg("^([0-9])+$", $useable_data[$i]["item"])){
				$return .= "Line: ".$badline." - Item # must be numeric.<br>";
			}

	// 2 - 17)  validate hilariously-named pickup-state
			if(strlen($useable_data[$i]["pustate"]) > 2){
				$return .= "Line: ".$badline." - Pick-up State cannot be longer than 2 characters.<br>";
			}

			if(!ereg("^([a-zA-Z])+$", $useable_data[$i]["pustate"])){
				$return .= "Line: ".$badline." - Pick-up State must be letters only.<br>";
			}
/*
	// 2 - 18)  validate pickup-state2
			if(strlen($useable_data[$i]["secondpu"]) > 2){
				$return .= "Line: ".$badline." - Pick-up State #2 cannot be longer than 2 characters.<br>";
			}

			if(!ereg("^([a-zA-Z])*$", $useable_data[$i]["secondpu"])){
				$return .= "Line: ".$badline." - Pick-up State #2 must be letters only.<br>";
			}

	// 2 - 19)  validate pickup-state3
			if(strlen($useable_data[$i]["thirdpu"]) > 2){
				$return .= "Line: ".$badline." - Pick-up State #3 cannot be longer than 2 characters.<br>";
			}

			if(!ereg("^([a-zA-Z])*$", $useable_data[$i]["thirdpu"])){
				$return .= "Line: ".$badline." - Pick-up State #3 must be letters only.<br>";
			}

	// 2 - 20)  validate tlltl ???
			if(strlen($useable_data[$i]["tlltl"]) > 4){
				$return .= "Line: ".$badline." - TL/LTL Field cannot be longer than 4 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _-])*$", $useable_data[$i]["tlltl"])){
				$return .= "Line: ".$badline." - Invalid Characters found in TL/LTL Field.<br>";
			}

	// 2 - 21)  validate singleteam ??
			if(strlen($useable_data[$i]["singleteam"]) > 2){
				$return .= "Line: ".$badline." - Single/Team Field cannot be longer than 2 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _-])*$", $useable_data[$i]["singleteam"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Single/Team Field.<br>";
			}

	// 2 - 22)  validate trailer length
			if(strlen($useable_data[$i]["trailerlen"]) > 4){
				$return .= "Line: ".$badline." - Trailer Length cannot be longer than 4 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _-])*$", $useable_data[$i]["trailerlen"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Trailer Length.<br>";
			}
*/
	// 2 - 23)  validate Pick Up Location
			if(strlen($useable_data[$i]["pickuploc"]) > 15){
				$return .= "Line: ".$badline." - Pickup Location cannot be longer than 15 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _-])+$", $useable_data[$i]["pickuploc"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Pickup Location.<br>";
			}

	// 2 - 24)  validate Special Instructions
			if(strlen($useable_data[$i]["specialinst"]) > 50){
				$return .= "Line: ".$badline." - Special Instructions cannot be longer than 50 characters.<br>";
			}

			if(!ereg("^([0-9a-zA-Z _`/-])*$", $useable_data[$i]["pickuploc"])){
				$return .= "Line: ".$badline." - Invalid Characters found in Special Instructions.<br>";
			}

		}
//		$i++;
	}

	return $return;
}



function validate_upload_to_CT($upload_num, $conn, $Short_Term_Cursor){
	$return = "";

// 1)  Make sure all "first table" values are identical (what with LOAD# being a primary key and all)
	$table_one_dup_list = "";
//				COUNT(DISTINCT TEMP) TEMP_COUNT,
//				OR COUNT(DISTINCT TEMP) >= 2
	$sql = "SELECT LOAD_NUM, 
				COUNT(DISTINCT CARRIER) CAR_COUNT,
				COUNT(DISTINCT LOAD_DATE) LOAD_COUNT,
				COUNT(DISTINCT PU_STATE) PU_COUNT,
				COUNT(DISTINCT PU_STATE2) PU2_COUNT,
				COUNT(DISTINCT PU_STATE3) PU3_COUNT,
				COUNT(DISTINCT TL_LTL) LTL_COUNT,
				COUNT(DISTINCT SINGLE_TEAM) SINGLE_COUNT,
				COUNT(DISTINCT TRAILER_LENGTH) TRAIL_COUNT,
				COUNT(DISTINCT PICK_UP_LOCATION) PUL_COUNT
			FROM WDI_LOADFORM_RAWDUMP 
			WHERE UPLOAD_NUM = '".$upload_num."'
			GROUP BY LOAD_NUM
			HAVING COUNT(DISTINCT CARRIER) >= 2
				OR COUNT(DISTINCT LOAD_DATE) >= 2
				OR COUNT(DISTINCT PU_STATE) >= 2
				OR COUNT(DISTINCT PU_STATE2) >= 2
				OR COUNT(DISTINCT PU_STATE3) >= 2
				OR COUNT(DISTINCT TL_LTL) >= 2
				OR COUNT(DISTINCT SINGLE_TEAM) >= 2
				OR COUNT(DISTINCT TRAILER_LENGTH) >= 2
				OR COUNT(DISTINCT PICK_UP_LOCATION) >= 2";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$table_one_dup_list .= "LOAD# ".$Short_Term_Row['LOAD_NUM']." (";
		if($Short_Term_Row['CAR_COUNT'] >= 2){
			$table_one_dup_list .= " Carrier";
		}
		if($Short_Term_Row['LOAD_COUNT'] >= 2){
			$table_one_dup_list .= " Load Date";
		}
//		if($Short_Term_Row['TEMP_COUNT'] >= 2){
//			$table_one_dup_list .= " Multiple Temperatures on 1 truck?";
//		}
		if($Short_Term_Row['PU_COUNT'] >= 2){
			$table_one_dup_list .= " P/U State";
		}
		if($Short_Term_Row['PU2_COUNT'] >= 2){
			$table_one_dup_list .= " 2nd p/u state";
		}
		if($Short_Term_Row['PU3_COUNT'] >= 2){
			$table_one_dup_list .= " 3rd p/u state";
		}
		if($Short_Term_Row['LTL_COUNT'] >= 2){
			$table_one_dup_list .= " TL/LTL";
		}
		if($Short_Term_Row['SINGLE_COUNT'] >= 2){
			$table_one_dup_list .= " Single/Team";
		}
		if($Short_Term_Row['TRAIL_COUNT'] >= 2){
			$table_one_dup_list .= " Trailer Length";
		}
		if($Short_Term_Row['PUL_COUNT'] >= 2){
			$table_one_dup_list .= " Pick Up Location";
		}
		$table_one_dup_list .= ")<br>";
	}
	if($table_one_dup_list != ""){
		$return .= "The Following Loads had invalid multiple definitions:<br>".$table_one_dup_list;
	}

// 2)  Make sure all "second table" values are identical (what with PO# {order} being a primary key and all)
	$table_two_dup_list = "";
	$sql = "SELECT DCPO_NUM, 
				COUNT(DISTINCT LOAD_NUM) LOAD_COUNT,
				COUNT(DISTINCT SAMS_SC) SAMS_COUNT,
				COUNT(DISTINCT BKHL_TRAFFIC) BKHL_COUNT,
				COUNT(DISTINCT VENDOR_NAME) VENDOR_COUNT,
				COUNT(DISTINCT DELIVERY_DATE) DELV_COUNT
			FROM WDI_LOADFORM_RAWDUMP 
			WHERE UPLOAD_NUM = '".$upload_num."'
			GROUP BY DCPO_NUM
			HAVING COUNT(DISTINCT SAMS_SC) >= 2
				OR COUNT(DISTINCT LOAD_NUM) >= 2
				OR COUNT(DISTINCT BKHL_TRAFFIC) >= 2
				OR COUNT(DISTINCT VENDOR_NAME) >= 2
				OR COUNT(DISTINCT DELIVERY_DATE) >= 2";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$table_two_dup_list .= "PO# ".$Short_Term_Row['DCPO_NUM']." (";
		if($Short_Term_Row['SAMS_COUNT'] >= 2){
			$table_two_dup_list .= " Sam's/SC";
		}
		if($Short_Term_Row['LOAD_COUNT'] >= 2){
			$table_two_dup_list .= " Load#";
		}
		if($Short_Term_Row['BKHL_COUNT'] >= 2){
			$table_two_dup_list .= " Bkhl/Traffic";
		}
		if($Short_Term_Row['VENDOR_COUNT'] >= 2){
			$table_two_dup_list .= " Vendor Name";
		}
		if($Short_Term_Row['DELV_COUNT'] >= 2){
			$table_two_dup_list .= " Delivery Date";
		}
		$table_two_dup_list .= ")<br>";
	}
	if($table_two_dup_list != ""){
		$return .= "The Following PO# had invalid multiple definitions:<br>".$table_two_dup_list;
	}

//	3)  Make sure the first 4 digits of the DCPO_NUM match an existing destiantion
	$no_known_dest_list = "";
	$sql = "SELECT SUBSTR(DCPO_NUM, 0, 4) THE_DEST
			FROM WDI_LOADFORM_RAWDUMP 
			WHERE UPLOAD_NUM = '".$upload_num."'
				AND TO_CHAR(SUBSTR(DCPO_NUM, 0, 4)) NOT IN
				(SELECT DEST_ID FROM WDI_DESTINATION)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$no_known_dest_list .= $Short_Term_Row['THE_DEST']."<br>";
	}
	if($no_known_dest_list != ""){
		$return .= "The Following PO#'s first 4 digits do not match any known Destination:<br>".$no_known_dest_list;
	}

	return $return;
}

function per_order_check($upload_num, &$result_table, $conn, $Short_Term_Cursor){
	$result_table = "<table border=\"1\"><tr>
						<td><font size=\"2\" face=\"Verdana\"><b>Item#</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>Date Requested</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>Pallets Requested</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>Pallets Available</b></font></td>
					</tr>";
	$requested = array();
	$itemnum = array();
	$loaddate = array();
	$warning_flag = false;
	$i = 0;
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

	for($i = 0; $i < sizeof($itemnum); $i++){
		$available[$i] = GetAvailable($upload_num, $itemnum[$i], $loaddate[$i], $conn, $Short_Term_Cursor);

		if($available[$i] < $requested[$i]){
			$color = "#FF0000";
			$warning_flag = true;
		} else {
			$color = "#0000FF";
		}
		$result_table .= "<tr>
							<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$itemnum[$i]."</font></td>
							<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$loaddate[$i]."</font></td>
							<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$requested[$i]."</font></td>
							<td><font size=\"2\" face=\"Verdana\" color=\"".$color."\">".$available[$i]."</font></td>
						</tr>";
	}
	$result_table .= "</table>";

	return $warning_flag;
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
//	echo $sql."<br>";
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
//	echo $sql."<br>";
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
//	echo $sql."<br>";
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
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$scanned_on_pending = $row['THE_COUNT'];

//	echo $itemnum." - ".$loaddate." - ".$IH." - ".$expected." - ".$pending_orders." - ".$scanned_on_pending."<br>";
	$available_amount = ($IH + $expected) - ($pending_orders - $scanned_on_pending);

	return $available_amount;
}