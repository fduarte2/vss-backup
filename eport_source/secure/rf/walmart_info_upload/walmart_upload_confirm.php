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
	$country = $HTTP_POST_VARS['country'];

	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	if($submit == "Upload" && $cur_ves == ""){
		echo "No Vessel Selected.  Please use your browser's back button to return to the previous page.";
		exit;
	}

	$default_message = "";
	$success_message = "";
//	echo $submit."<br>";
	if($submit == "Upload"){
		if($HTTP_POST_FILES['import_file']['name'] == ""){
			echo "No file uploaded.  Please use you browser's back button to return to the previous page.";
			exit;
		}

		
//		$first_this_year = date("Y")."000001";
		$sql = "SELECT WM_UPLOAD_HIST_UPLOAD_NUM_SEQ.NEXTVAL THE_MAX FROM DUAL";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$upload_num = $row['THE_MAX'];
/*		if($row['THE_MAX'] < $first_this_year){
			$upload_num = $first_this_year;
		} else {
			$upload_num = $row['THE_MAX'] + 1;
		}
*/


		// step 2: get file
		$impfilename = date(mdYhis)."_seq".$upload_num."_".basename($HTTP_POST_FILES['import_file']['name']);
		$target_path_import = "./WMuploadedfiles/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact Port of Wilmington";
			exit;
		}

//		echo "1<br>";
		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$useable_data = array();
//		echo "2<br>";

		// 2b)  populate data array
		// we are "oracle safeguarding" all the strings
		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);
		$badrow = false;
		for ($i = 1; $i <= $data->sheets[0]['numRows'] && $badrow === false; $i++) {
			$useable_data[($i - 1)]["PORT_ORIGIN"] = strtoupper($data->sheets[0]['cells'][$i][1]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["PORT_ORIGIN"]));
			$useable_data[($i - 1)]["EXPORTER"] = strtoupper($data->sheets[0]['cells'][$i][2]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["EXPORTER"]));
			$useable_data[($i - 1)]["BOL"] = strtoupper($data->sheets[0]['cells'][$i][3]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["BOL"]));
			$useable_data[($i - 1)]["BARCODE"] = trim(strtoupper($data->sheets[0]['cells'][$i][4]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["BARCODE"]));
			$useable_data[($i - 1)]["COMMODITY"] = strtoupper($data->sheets[0]['cells'][$i][5]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["COMMODITY"]));
			$useable_data[($i - 1)]["VAR_CODE"] = strtoupper($data->sheets[0]['cells'][$i][6]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["VAR_CODE"]));
			$useable_data[($i - 1)]["VAR_DESC"] = strtoupper($data->sheets[0]['cells'][$i][7]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["VAR_DESC"]));
			$useable_data[($i - 1)]["SIZE_CODE"] = strtoupper($data->sheets[0]['cells'][$i][8]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["SIZE_CODE"]));
			$useable_data[($i - 1)]["LABEL_CODE"] = strtoupper($data->sheets[0]['cells'][$i][9]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["LABEL_CODE"]));
			$useable_data[($i - 1)]["LABEL_DESC"] = strtoupper($data->sheets[0]['cells'][$i][10]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["LABEL_DESC"]));
			$useable_data[($i - 1)]["GROWER_AREA_CODE"] = strtoupper($data->sheets[0]['cells'][$i][11]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["GROWER_AREA_CODE"]));
			$useable_data[($i - 1)]["GROWER_CODE"] = strtoupper($data->sheets[0]['cells'][$i][12]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["GROWER_CODE"]));
			$useable_data[($i - 1)]["CTNCOUNT"] = strtoupper($data->sheets[0]['cells'][$i][13]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["CTNCOUNT"]));
			$useable_data[($i - 1)]["VESSEL_STORE_CODE"] = trim(strtoupper($data->sheets[0]['cells'][$i][14]));
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["VESSEL_STORE_CODE"]));
			$useable_data[($i - 1)]["CONTAINER"] = strtoupper($data->sheets[0]['cells'][$i][15]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["CONTAINER"]));
			$useable_data[($i - 1)]["PREFUMED"] = strtoupper($data->sheets[0]['cells'][$i][16]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["PREFUMED"]));
			$useable_data[($i - 1)]["PACKING_CODE"] = strtoupper($data->sheets[0]['cells'][$i][17]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["PACKING_CODE"]));
			$useable_data[($i - 1)]["PACKING_DESC"] = strtoupper($data->sheets[0]['cells'][$i][18]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["PACKING_DESC"]));
			$useable_data[($i - 1)]["PACKING_NAME"] = strtoupper($data->sheets[0]['cells'][$i][19]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["PACKING_NAME"]));
			$useable_data[($i - 1)]["WM_PO_NUM"] = strtoupper($data->sheets[0]['cells'][$i][20]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["WM_PO_NUM"]));
			$useable_data[($i - 1)]["GROWER_ITEM_NUM"] = strtoupper($data->sheets[0]['cells'][$i][21]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["GROWER_ITEM_NUM"]));
			$useable_data[($i - 1)]["WM_PROGRAM"] = strtoupper($data->sheets[0]['cells'][$i][22]);
				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["WM_PROGRAM"]));
			$useable_data[($i - 1)]["SUPPLIER_PACKDATE"] = strtoupper($data->sheets[0]['cells'][$i][23]);
//				$badrow = ($badrow || !all_chars_good($useable_data[($i - 1)]["SUPPLIER_PACKDATE"])); // we dont care about the character in this field, its got a more specific check anyway
/*
			for($j = 0; $j < sizeof($useable_data[($i - 1)]) && $badrow === false; $j++){
				echo "row: ".$useable_data[($i - 1)][$j]."&nbsp;&nbsp;&nbsp;".$useable_data[($i - 1)]["BOL"]."<br>";
				$badrow = !all_chars_good($useable_data[($i - 1)][$j]);
			}
*/
		}
	
		if($badrow === true){
			$upload_success = "DBSTRICT FAILED";
			echo "File Upload aborted on line ".($i - 1)."; invalid characters detected.  Please use you browser's back button to return to the previous page.";
			write_to_job($user, $cur_ves, $upload_success, $conn, $Short_Term_Cursor);
			exit;
		}

		$result = validate_dbsafe_data($upload_num, $useable_data, $cur_ves, $user_cust_num, $conn, $Short_Term_Cursor);
		if($result != ""){
			$upload_success = "DBSTRICT FAILED";
			echo "File Upload aborted; the following errors prevented uploading the file to the database:<br>".$result."  Please use you browser's back button to return to the previous page.";
			write_to_job($user, $cur_ves, $upload_success, $conn, $Short_Term_Cursor);
			exit;
		}


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
					'".$cur_ves."',
					'PRELOADED')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		for($i = 1; $i < $num_entries; $i++) {
			$sql = "INSERT INTO WALMART_CARGO_RAW_DUMP
						(UPLOAD_NUMBER,
						ROW_NUMBER,
						PORT_OF_ORIGIN,
						EXPORTER,
						BOL,
						PALLET_ID,
						COMMODITY,
						VARIETY_CODE,
						VARIETY_DESCRIPTION,
						SIZE_CODE,
						LABEL_CODE,
						LABEL_DESCRIPTION,
						GROWER_AREA_CODE,
						GROWER_CODE_VARCHAR,
						CASE_COUNT,
						VESSEL_STORE_CODE,
						CONTAINER_NUM,
						PREFUMED,
						PACKING_CODE,
						PACKING_DESCRIPTION,
						PACKING_NAME,
						WM_PO_NUM,
						GROWER_ITEM_NUM,
						WM_PROGRAM,
						SUPPLIER_PACKDATE)
					VALUES
						('".$upload_num."',
						'".$i."',
						'".$useable_data[$i]["PORT_ORIGIN"]."',
						'".$useable_data[$i]["EXPORTER"]."',
						'".$useable_data[$i]["BOL"]."',
						'".$useable_data[$i]["BARCODE"]."',
						'".$useable_data[$i]["COMMODITY"]."',
						'".$useable_data[$i]["VAR_CODE"]."',
						'".$useable_data[$i]["VAR_DESC"]."',
						'".$useable_data[$i]["SIZE_CODE"]."',
						'".$useable_data[$i]["LABEL_CODE"]."',
						'".$useable_data[$i]["LABEL_DESC"]."',
						'".$useable_data[$i]["GROWER_AREA_CODE"]."',
						'".$useable_data[$i]["GROWER_CODE"]."',
						'".$useable_data[$i]["CTNCOUNT"]."',
						'".$useable_data[$i]["VESSEL_STORE_CODE"]."',
						'".$useable_data[$i]["CONTAINER"]."',
						'".$useable_data[$i]["PREFUMED"]."',
						'".$useable_data[$i]["PACKING_CODE"]."',
						'".$useable_data[$i]["PACKING_DESC"]."',
						'".$useable_data[$i]["PACKING_NAME"]."',
						'".$useable_data[$i]["WM_PO_NUM"]."',
						'".$useable_data[$i]["GROWER_ITEM_NUM"]."',
						'".$useable_data[$i]["WM_PROGRAM"]."',
						TO_DATE('".$useable_data[$i]["SUPPLIER_PACKDATE"]."', 'MM/DD/YYYY'))";
//			echo $sql."<br>";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}



		// step 3:  validate and save to raw-dump table		
		$result = validate_upload_to_CT($upload_num, $cur_ves, $user_cust_num, $conn, $Short_Term_Cursor);

		if($result == ""){
			$upload_success = "VALIDATED";
			$success_message = "<font size=\"3\" face=\"Verdana\" color=\"#0000FF\">Upload File Validated;<br>".($num_entries - 1)." Total Rows.</font>";
			write_to_job($user, $cur_ves, $upload_success, $conn, $Short_Term_Cursor);
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
<!-- width="100%" !-->
<table border="1" cellpadding="4" cellspacing="0">
<form name="get_data" action="walmart_upload_index.php" method="post">
<input type="hidden" name="upload_num" value="<? echo $upload_num; ?>">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>">
<input type="hidden" name="country" value="<? echo $country; ?>">
	<tr>
		<td><font size="2" face="Verdana"><b>Port of Origin</b></font></td>
		<td><font size="2" face="Verdana"><b>Exporter</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Size Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Label Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Label Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower Area Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Number of Boxes</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel Store Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Container #</b></font></td>
		<td><font size="2" face="Verdana"><b>Prefumed</b></font></td>
		<td><font size="2" face="Verdana"><b>Packing Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Packing Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Packing Name</b></font></td>
		<td><font size="2" face="Verdana"><b>WM PO #</b></font></td>
		<td><font size="2" face="Verdana"><b>Grower Item #</b></font></td>
		<td><font size="2" face="Verdana"><b>Program Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Supplier Packing Date</b></font></td>
	<tr>
<?
		$sql = "SELECT WCRD.*, TO_CHAR(SUPPLIER_PACKDATE, 'MM/DD/YYYY') THE_DATE 
				FROM WALMART_CARGO_RAW_DUMP WCRD 
				WHERE UPLOAD_NUMBER = '".$upload_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['PORT_OF_ORIGIN']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['EXPORTER']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMODITY']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['VARIETY_CODE']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['VARIETY_DESCRIPTION']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['SIZE_CODE']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['LABEL_CODE']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['LABEL_DESCRIPTION']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['GROWER_AREA_CODE']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['GROWER_CODE_VARCHAR']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['CASE_COUNT']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['VESSEL_STORE_CODE']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['CONTAINER_NUM']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['PREFUMED']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['PACKING_CODE']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['PACKING_DESCRIPTION']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['PACKING_NAME']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['WM_PO_NUM']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['GROWER_ITEM_NUM']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['WM_PROGRAM']; ?></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="23"><input type="submit" name="submit" value="Commit File" <? echo $submit_able; ?>></td>
	</tr>
<?
	}
?>
</table>






<?

function all_chars_good($string){

	if(ereg("^([a-zA-Z0-9 _-])*$", $string)){
		return true;
	} else {
//		echo $string."<br>";
		return false;
	}
}

function validate_dbsafe_data($upload_num, $useable_data, $cur_ves, $user_cust_num, $conn, $Short_Term_Cursor){
	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";
/*
			$useable_data[X]["PORT_ORIGIN"] = [1];
			$useable_data[X]["EXPORTER"] = [2];
			$useable_data[X]["BOL"] = [3];
			$useable_data[X]["BARCODE"] = [4];
			$useable_data[X]["COMMODITY"] = [5];
			$useable_data[X]["VAR_CODE"] = [6];
			$useable_data[X]["VAR_DESC"] = [7];
			$useable_data[X]["SIZE_CODE"] = [8];
			$useable_data[X]["LABEL_CODE"] = [9];
			$useable_data[X]["LABEL_DESC"] = [10];
			$useable_data[X]["GROWER_AREA_CODE"] = [11];
			$useable_data[X]["GROWER_CODE_VARCHAR"] = [12];
			$useable_data[X]["CTNCOUNT"] = [13];
			$useable_data[X]["VESSEL_STORE_CODE"] = [14];
			$useable_data[X]["CONTAINER"] = [15];
			$useable_data[X]["PREFUMED"] = [16];
			$useable_data[X]["PACKING_CODE"] = [17];
			$useable_data[X]["PACKING_DESC"] = [18];
			$useable_data[X]["PACKING_NAME"] = [19];
			$useable_data[X]["WM_PO_NUM"] = [20];
			$useable_data[X]["GROWER_ITEM_NUM"] = [21];
			$useable_data[X]["WM_PROGRAM"] = [22];
			$useable_data[X]["SUPPLIER_PACKDATE"] = [23];
*/

// step 1:  test the first line

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
		|| $useable_data[0]["WM_PROGRAM"] != "PROGRAMTYPE"
		|| $useable_data[0]["SUPPLIER_PACKDATE"] != "SUPPLIERPACKDATE"){
		return "File order does not match to expected format; please reference the Example on the previous page.";
	}


// step 2, for each line...
	$i = 1;
//	$i = 0;
	while($useable_data[$i]["BARCODE"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

// 2 - 1)  validate Port of Origin
		if(strlen($useable_data[$i]["PORT_ORIGIN"]) > 20){
			$return .= "Line: ".$badline." - Port of Origin cannot be longer than 20 characters.<br>";
		}

		if($useable_data[$i]["PORT_ORIGIN"] != "" && !ereg("^([a-zA-Z _-])+$", $useable_data[$i]["PORT_ORIGIN"])){
			$return .= "Line: ".$badline." - Port of Origin cannot contain numbers.<br>";
		}

// 2 - 2)  validate Exporter
		if(strlen($useable_data[$i]["EXPORTER"]) > 20){
			$return .= "Line: ".$badline." - Exporter cannot be longer than 20 characters.<br>";
		}

		if($useable_data[$i]["EXPORTER"] != "" && !ereg("^([0-9a-zA-Z ])+$", $useable_data[$i]["EXPORTER"])){
			$return .= "Line: ".$badline." - Exporter must be alphanumeric.<br>";
		}

// 2 - 3)  validate BoL
		if(strlen($useable_data[$i]["BOL"]) > 20){
			$return .= "Line: ".$badline." - BoL cannot be longer than 20 characters.<br>";
		}

// 2 - 4)  validate pallet id
		if(strlen($useable_data[$i]["BARCODE"]) > 32){
			$return .= "Line: ".$badline." - Pallet numbers cannot be longer than 32 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["BARCODE"])){
			$return .= "Line: ".$badline." - Pallet number must be alphanumeric.<br>";
		}

// 2 - 5)  validate commodity
		if(strlen($useable_data[$i]["COMMODITY"]) > 12){
			$return .= "Line: ".$badline." - Commodity cannot be longer than 12 characters.<br>";
		}

		if($useable_data[$i]["COMMODITY"] != "" && !ereg("^([a-zA-Z ])+$", $useable_data[$i]["COMMODITY"])){
			$return .= "Line: ".$badline." - Commodity must only be letters or spaces.<br>";
		}

// 2 - 6)  validate variety code
		if(strlen($useable_data[$i]["VAR_CODE"]) > 5){
			$return .= "Line: ".$badline." - Variety Code cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["VAR_CODE"])){
			$return .= "Line: ".$badline." - Variety Code must be alphanumeric.<br>";
		}

// 2 - 7)  validate variety description
		if(strlen($useable_data[$i]["VAR_DESC"]) > 15){
			$return .= "Line: ".$badline." - Variety description cannot be longer than 15 characters.<br>";
		}

		if($useable_data[$i]["VAR_DESC"] == ""){
			$return .= "Line: ".$badline." - Variety description is required.<br>";
		}

// 2 - 8)  validate size
		if(strlen($useable_data[$i]["SIZE_CODE"]) > 5){
			$return .= "Line: ".$badline." - Size Code cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([0-9a-zA-Z])+$", $useable_data[$i]["SIZE_CODE"])){
			$return .= "Line: ".$badline." - Size Code must be numeric.<br>";
		}

// 2 - 9)  validate label code
		if(strlen($useable_data[$i]["LABEL_CODE"]) > 5){
			$return .= "Line: ".$badline." - Label Code cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["LABEL_CODE"])){
			$return .= "Line: ".$badline." - Label Code must be alphanumeric.<br>";
		}

// 2 - 10)  validate label description
		if(strlen($useable_data[$i]["LABEL_DESC"]) > 20){
			$return .= "Line: ".$badline." - Label Description cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z _-])+$", $useable_data[$i]["LABEL_DESC"])){
			$return .= "Line: ".$badline." - Label Description cannot contain numbers.<br>";
		}

// 2 - 11)  validate Grower Area Code
		if(strlen($useable_data[$i]["GROWER_AREA_CODE"]) > 4){
			$return .= "Line: ".$badline." - Grower Area Code cannot be longer than 4 characters.<br>";
		}

		if(!ereg("^([0-9])*$", $useable_data[$i]["GROWER_AREA_CODE"])){
			$return .= "Line: ".$badline." - Grower Area Code must be numeric.<br>";
		}

// 2 - 12)  validate Grower Code
		if(strlen($useable_data[$i]["GROWER_CODE"]) > 10){
			$return .= "Line: ".$badline." - Grower Code cannot be longer than 10 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])*$", $useable_data[$i]["GROWER_CODE"])){
			$return .= "Line: ".$badline." - Grower Code must be alphanumeric.<br>";
		}

// 2 - 13)  validate case count
		if(strlen($useable_data[$i]["CTNCOUNT"]) > 3){
			$return .= "Line: ".$badline." - Carton Count cannot be longer than 3 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["CTNCOUNT"])){
			$return .= "Line: ".$badline." - Carton Count must be numeric.<br>";
		}

// 2 - 14)  validate Vessel Store Code (hatchdeck)
		if(strlen($useable_data[$i]["VESSEL_STORE_CODE"]) > 2){
			$return .= "Line: ".$badline." - Vessel Store Code cannot be longer than 2 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["VESSEL_STORE_CODE"])){
			$return .= "Line: ".$badline." - Vessel Store Code must be alphanumeric.<br>";
		}

// 2 - 15)  validate Container
		if(strlen($useable_data[$i]["CONTAINER"]) > 20){
			$return .= "Line: ".$badline." - Container # cannot be longer than 20 characters.<br>";
		}

		if($useable_data[$i]["CONTAINER"] != "" && !ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["CONTAINER"])){
			$return .= "Line: ".$badline." - Container # must be alphanumeric.<br>";
		}

// 2 - 16)  validate "Prefumed"
		if($useable_data[$i]["PREFUMED"] != "Y" && $useable_data[$i]["PREFUMED"] != "N"){
			$return .= "Line: ".$badline." - Prefumed Filed must either be Y or N.<br>";
		}

// 2 - 17)  validate Packing Code
		if(strlen($useable_data[$i]["PACKING_CODE"]) > 10){
			$return .= "Line: ".$badline." - Packing Code cannot be longer than 10 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["PACKING_CODE"])){
			$return .= "Line: ".$badline." - Packing Code must be alphanumeric.<br>";
		}

// 2 - 18)  validate Packing description
		if(strlen($useable_data[$i]["PACKING_DESC"]) > 25){
			$return .= "Line: ".$badline." - Packing description cannot be longer than 25 characters.<br>";
		}

		if($useable_data[$i]["PACKING_DESC"] == ""){
			$return .= "Line: ".$badline." - Packing description is required.<br>";
		}

// 2 - 19)  validate Packing Name
		if(strlen($useable_data[$i]["PACKING_NAME"]) > 30){
			$return .= "Line: ".$badline." - Packing Name cannot be longer than 30 characters.<br>";
		}

		if($useable_data[$i]["PACKING_NAME"] == ""){
			$return .= "Line: ".$badline." - Packing Name is required.<br>";
		}

// 2 - 20)  validate WM-PO #
		if(strlen($useable_data[$i]["WM_PO_NUM"]) > 10){
			$return .= "Line: ".$badline." - PO # cannot be longer than 10 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["WM_PO_NUM"])){
			$return .= "Line: ".$badline." - PO # must be numeric.<br>";
		}

// 2 - 21)  validate Grower Item #
		if(strlen($useable_data[$i]["GROWER_ITEM_NUM"]) > 10){
			$return .= "Line: ".$badline." - Grower Item # cannot be longer than 10 digits.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["GROWER_ITEM_NUM"])){
			$return .= "Line: ".$badline." - Grower Item # must be numeric.<br>";
		}

// 2 - 22) validate the "program type" (I.E. base or storage)
		if(strlen($useable_data[$i]["WM_PROGRAM"]) > 20){
			$return .= "Line: ".$badline." - Program Type cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["WM_PROGRAM"])){
			$return .= "Line: ".$badline." - Program Type must be alphanumeric.<br>";
		}

// 2 - 23) validate the supplier packaging date
		if(strlen($useable_data[$i]["SUPPLIER_PACKDATE"]) > 10){
			$return .= "Line: ".$badline." - Supplier Packaging Date cannot be longer than 10 characters.<br>";
		}

		if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $useable_data[$i]["SUPPLIER_PACKDATE"])) {
			$return .= "Line: ".$badline." - Supplier Packaging Date must be in MM/DD/YYYY format.<br>";
		}

		$i++;
	}

	return $return;
}

function validate_upload_to_CT($upload_num, $cur_ves, $user_cust_num, $conn, $Short_Term_Cursor){
	$return = "";

// 1)  Check for duplicate pallets in uplaod file.  For now, discount file if found (this will be altered later)
/*
	$dup_list = "";
	$sql = "SELECT PALLET_ID, COUNT(*) THE_COUNT FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$upload_num."'
			GROUP BY PALLET_ID
			HAVING COUNT(*) >= 2";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$dup_list .= $Short_Term_Row['PALLET_ID']." ";
	}
	if($dup_list != ""){
		$return .= "The Following Pallet IDs were found in the upload file more than once:  ".$dup_list."<br>";
	}
*/

// 2)  Check to see if they are trying to upload an already-uploaded Barcode (or more than one)
	$already_in_CT_list = "";
	$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$cur_ves."' AND RECEIVER_ID = '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT PALLET_ID FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$upload_num."')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$already_in_CT_list .= $Short_Term_Row['PALLET_ID']." ";
	}
	if($already_in_CT_list != ""){
		$return .= "The Following Pallet IDs have already been uploaded to PoW:  ".$already_in_CT_list."<br>";
	}

// 3)  Check to see if item numbers we haven't reconcilled with commodity codes are present
	$need_comm_code_list = "";
	$sql = "SELECT WICM.ITEM_NUM FROM WM_ITEMNUM_MAPPING WIM, WM_ITEM_COMM_MAP WICM
			WHERE WIM.WM_ITEM_NUM = WICM.ITEM_NUM
			AND WICM.COMMODITY_CODE IS NULL
			AND WIM.ITEM_NUM IN
				(SELECT GROWER_ITEM_NUM FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$upload_num."')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$need_comm_code_list .= $Short_Term_Row['ITEM_NUM']." ";
	}
	if($need_comm_code_list != ""){
		$return .= "The Following Walmart Item #s need to have PoW commodities assigned to them:  ".$need_comm_code_list."; please contact PoW<br>";
	}

// 4)  Check to see if unexpected item numbers are present
	$missing_comm_conversion = "";
	$sql = "SELECT GROWER_ITEM_NUM FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$upload_num."'
			AND GROWER_ITEM_NUM NOT IN
				(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING WHERE WM_ITEM_NUM IS NOT NULL)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$missing_comm_conversion .= $Short_Term_Row['GROWER_ITEM_NUM']." ";
	}
	if($missing_comm_conversion != ""){
		$return .= "The Following Grower Item #s are not found in PoW expected Item Types:  ".$missing_comm_conversion."<br>";

		$sql = "INSERT INTO WM_ITEMNUM_MAPPING
					(ITEM_NUM,
					DATE_UPDATED)
				(SELECT DISTINCT
						GROWER_ITEM_NUM,
						MIN(UPLOAD_DATE)
					FROM WM_UPLOAD_HISTORY WUH, WALMART_CARGO_RAW_DUMP WCRD
					WHERE WUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
					AND GROWER_ITEM_NUM NOT IN
						(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
					GROUP BY GROWER_ITEM_NUM
					)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

// LABEL_CODE,   // MIN(LABEL_CODE),
/*
		$sql = "UPDATE WM_ITEMNUM_MAPPING WIM
				SET
						(COMMODITY,
						VARIETY,
						FIRST_FOUND_IN_FILE) =
					(SELECT
						MIN(COMMODITY),
						MIN(VARIETY_DESCRIPTION),
						'Line ' || MIN(ROW_NUMBER) || '  File ' || MIN(FILENAME)
					FROM WM_UPLOAD_HISTORY WUH, WALMART_CARGO_RAW_DUMP WCRD
					WHERE WUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
					AND WCRD.GROWER_ITEM_NUM = WIM.ITEM_NUM
					AND WUH.UPLOAD_DATE = WIM.DATE_UPDATED)
				WHERE COMMODITY IS NULL
				AND VARIETY IS NULL
				AND LABEL_CODE IS NULL";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
*/
/*		$sql = "INSERT INTO WM_ITEMNUM_MAPPING
					(ITEM_NUM,
					COMMODITY,
					VARIETY,
					LABEL_CODE,
					FIRST_FOUND_IN_FILE,
					DATE_UPDATED)
				(SELECT DISTINCT
						GROWER_ITEM_NUM,
						COMMODITY,
						VARIETY_DESCRIPTION,
						LABEL_CODE,
						'Line ' || ROW_NUMBER || '  File ' || FILENAME,
						SYSDATE
					FROM WM_UPLOAD_HISTORY WUH, WALMART_CARGO_RAW_DUMP WCRD
					WHERE WUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
					AND GROWER_ITEM_NUM NOT IN
						(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
					)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor); */
/*
		$sql = "INSERT INTO WM_ITEM_COMM_MAP
					(ITEM_NUM)
				(SELECT GROWER_ITEM_NUM
					FROM WM_UPLOAD_HISTORY WUH, WALMART_CARGO_RAW_DUMP WCRD
					WHERE WUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
					AND GROWER_ITEM_NUM NOT IN
						(SELECT ITEM_NUM FROM WM_ITEM_COMM_MAP)
					)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor); */
	}

// 5)  Make sure (which should never happen, but...) that no Split Pallets have, within the same pallet, both 
//		"fumed" and "not fumed" parts.
	$split_fumed_list = "";
	$sql = "SELECT PALLET_ID, COUNT(DISTINCT PREFUMED) 
			FROM WALMART_CARGO_RAW_DUMP
			WHERE UPLOAD_NUMBER = '".$upload_num."'
			GROUP BY PALLET_ID
			HAVING COUNT(DISTINCT PREFUMED) >= 2";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$split_fumed_list .= $Short_Term_Row['PALLET_ID']." ";
	}
	if($split_fumed_list != ""){
		$return .= "The Following Pallets had sub-quantites on them which were listed as both fumigated AND not-fumigated:  ".$split_fumed_list."<br>";
	}

// 6)  Make sure that all of the "program types" are valid entries (BASE or STORAGE, although more may show up later)
	$bad_program_list = "";
	$sql = "SELECT WM_PROGRAM FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$upload_num."'
			AND WM_PROGRAM NOT IN
				(SELECT WM_PROGRAM FROM WM_CARGO_TYPE WHERE WM_PROGRAM IS NOT NULL)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$bad_program_list .= $Short_Term_Row['WM_PROGRAM']." ";
	}
	if($bad_program_list != ""){
		$return .= "The Following Wal-Mart Programs were not recognized by PoW:  ".$bad_program_list."<br>";
	}

// 7)  Make sure that no single barcode has 2 different POs or Varietys on it.
	$multi_PO_type_list = "";
//, COUNT(DISTINCT VARIETY_CODE || VARIETY_DESCRIPTION)
	$sql = "SELECT PALLET_ID, COUNT(DISTINCT WM_PO_NUM)
			FROM WALMART_CARGO_RAW_DUMP
			WHERE UPLOAD_NUMBER = '".$upload_num."'
			GROUP BY PALLET_ID
			HAVING COUNT(DISTINCT WM_PO_NUM) >= 2";
//				OR COUNT(DISTINCT VARIETY_CODE || VARIETY_DESCRIPTION) >= 2";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$multi_PO_type_list .= $Short_Term_Row['PALLET_ID']." ";
	}
	if($multi_PO_type_list != ""){
		$return .= "The File contains multiple POs for the following split-pallets:  ".$multi_PO_type_list."<br>";
	}

// 8)  Do a column-by-column comparison to (hopefully) prevent any carbon-copy duplication from getting in.
	$duplicate_line_list = "";
	$sql = "SELECT PORT_OF_ORIGIN, EXPORTER, BOL, PALLET_ID, COMMODITY, VARIETY_CODE, VARIETY_DESCRIPTION,
				SIZE_CODE, LABEL_CODE, LABEL_DESCRIPTION, GROWER_AREA_CODE, GROWER_CODE_VARCHAR, CASE_COUNT,
				VESSEL_STORE_CODE, CONTAINER_NUM, PREFUMED, PACKING_CODE, PACKING_DESCRIPTION,
				PACKING_NAME, WM_PO_NUM, GROWER_ITEM_NUM, WM_PROGRAM, COUNT(*) THE_COUNT, MIN(ROW_NUMBER) THE_MIN, MAX(ROW_NUMBER) THE_MAX
			FROM WALMART_CARGO_RAW_DUMP
			WHERE UPLOAD_NUMBER = '".$upload_num."'
			GROUP BY PORT_OF_ORIGIN, EXPORTER, BOL, PALLET_ID, COMMODITY, VARIETY_CODE, VARIETY_DESCRIPTION,
				SIZE_CODE, LABEL_CODE, LABEL_DESCRIPTION, GROWER_AREA_CODE, GROWER_CODE_VARCHAR, CASE_COUNT,
				VESSEL_STORE_CODE, CONTAINER_NUM, PREFUMED, PACKING_CODE, PACKING_DESCRIPTION,
				PACKING_NAME, WM_PO_NUM, GROWER_ITEM_NUM, WM_PROGRAM
			HAVING COUNT(*) >= 2
			ORDER BY COUNT(*) DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$duplicate_line_list .= $Short_Term_Row['THE_MIN']." -- ".$Short_Term_Row['THE_MAX']."<br>";
	}
	if($multi_PO_type_list != ""){
		$return .= "Duplicate Rows were detected on the following lines within the file:  ".$duplicate_line_list."<br>";
	}


	return $return;
}

function write_to_job($user, $cur_ves, $upload_success, $conn, $Short_Term_Cursor){
/*
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
			'AQCWDI',
			'".$cur_ves."',
			'PENDING')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
*/
}