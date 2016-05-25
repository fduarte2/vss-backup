<?
/*
*
*	Modified from A. Walter code for Chilean.  Modified by I. Thomas 02/24/2014
*
*	A screen for TS to "upload" an Original Argenfruit Manifest for future reference.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Argen Fruit Upload";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Argen Fruit system");
    include("pow_footer.php");
    exit;
  }


 $conn = ora_logon("SAG_OWNER@RF", "OWNER"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $vessel != ""){
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".".date(mdYhis);
		$target_path_import = "./uploaded_manifests/argenfruit/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact Port of Wilmington";
			exit;
		}

		$handle = fopen($target_path_import, "r");
		$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM ARGENFRUIT_MANIFEST_HEADER";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$next_trans_id = $Short_Term_Row['THE_MAX'] + 1;
		$row_id = 0;

		$error_msg = "";
		while($temp = fgets($handle)){
			$row_id++;
			$line[$row_id] = explode(",", $temp);

			// a couple preparations...
			$line[$row_id][7] = trim(str_replace("-", "", $line[$row_id][7]));
			$line[$row_id][5] = trim(str_replace("(", "", $line[$row_id][5]));
			$line[$row_id][5] = trim(str_replace(")", "", $line[$row_id][5]));
		}

		$upload_valid = validate_upload($line, $vessel, $conn);

		if($upload_valid != ""){
			echo "<font color=\"#FF0000\">Could not accept file.<br><br>".$upload_valid."<br>Please correct and resubmit.</font><br>";
		} else { 

			for($i = 1; $i <= $row_id; $i++){

				if($line[$i][0] == "VESSEL"){
					// do nothing
				} else {

					$sql = "INSERT INTO ARGENFRUIT_MANIFEST_DETAILS
							(
								VESSEL, 
								CONTAINER_NO,         
								PALLET_NO,
								STATUS_CD,
								COMMODITY,            
								VARIETY,
								PACK_TYPE,
								PACK_STYLE,
								GRADE,
								BRAND,
								CARGO_SIZE,
								PALL_MARK,
								UNIT_PALL,
								CRTNS,
								PALLET,
								GROWER_CODE,
								IMPORT_CODE,
								VOUCHER_NUM,
								INVOICE,
								TRANSACTION_ID,
								ROW_NUM)
						VALUES
							('".$line[$i][0]."',
							'".$line[$i][1]."',
							'".$line[$i][2]."',
							'".$line[$i][3]."',
							'".$line[$i][4]."',
							'".trim($line[$i][5])."',
							'NA',
							'NA',
							'NA',
							'".$line[$i][6]."',
							'".$line[$i][7]."',
							'NA',
							'0',
							'".$line[$i][8]."',
							'0',
							'NA',
							'".$line[$i][9]."',
							'".substr($line[$i][10], 0, 7)."',
							'0',
							'".$next_trans_id."',
							'".$i."')";
//					echo $sql."<br>";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
				}
			}

			$sql = "INSERT INTO ARGENFRUIT_MANIFEST_HEADER
					(UPLOAD_TIME,
					FILENAME,
					RECORDCOUNT,
					USER_ID,
					TRANSACTION_ID,
					PUSHED_TO_CT,
					LR_NUM)
					SELECT
					SYSDATE,
					'".basename($HTTP_POST_FILES['import_file']['name'])."',
					COUNT(*),
					'".$user."',
					'".$next_trans_id."',
					'N',
					'".$vessel."'
					FROM ARGENFRUIT_MANIFEST_DETAILS
					WHERE TRANSACTION_ID = '".$next_trans_id."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);

			$sql = "SELECT RECORDCOUNT FROM ARGENFRUIT_MANIFEST_HEADER WHERE TRANSACTION_ID = '".$next_trans_id."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			echo "<font color=\"#0000FF\">".$Short_Term_Row['RECORDCOUNT']." rows inserted.</font><br>";
		
		}
		ora_commit($conn);

	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Argenfruit Upload Page.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="argenfruit_upload.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'ARG FRUIT' ORDER BY LR_NUM DESC";
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
		<td align="left"><font size="3" face="Verdana">Select File:</font>  <font size="2" face="Verdana">(Comma-separated .txt or .csv file please.  Make sure no commas in the entire data file. )</font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");





















function validate_upload($useable_data, $vessel, $conn){
  $Short_Term_Cursor = ora_open($conn);

	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";

/*
	1 - Vessel
	2 - HatchDeck
	3 - Barcode
	4 - Status
	5 - Commodity
	6 - Variety
	7 - Brand
	8 - Size
	9 - Cartons
	10- Import Code
	11- Voucher
*/


// step 2, for each line...
	$i = 2; 
	while($useable_data[$i][0] != ""){
		$badline = $i;  // since XLS spreadsheet line #s don't start with Zero.

// 2 - 1)  vessel
		if(strlen($useable_data[$i][0]) > 20){
			$return .= "Line: ".$badline." - Vessel (".$useable_data[$i][0].") cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([ a-zA-Z0-9])+$", $useable_data[$i][0])){
			$return .= "Line: ".$badline." - Vessel (".$useable_data[$i][0].") is required and must be alphanumeric.<br>";
		}

// 2 - 2)  hatch
		if(strlen($useable_data[$i][1]) > 20){
			$return .= "Line: ".$badline." - HatchDeck (".$useable_data[$i][1].") cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i][1])){
			$return .= "Line: ".$badline." - HatchDeck (".$useable_data[$i][1].") is required and must be alphanumeric.<br>";
		}

// 2 - 3)  pallet
		if(strlen($useable_data[$i][2]) > 32){
			$return .= "Line: ".$badline." - Pallet (".$useable_data[$i][2].") cannot be longer than 32 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i][2])){
			$return .= "Line: ".$badline." - Pallet (".$useable_data[$i][2].") is required and must be alphanumeric.<br>";
		}

// 2 - 4)  status
		if(strlen($useable_data[$i][3]) > 20){
			$return .= "Line: ".$badline." - Status (".$useable_data[$i][3].") cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i][3])){
			$return .= "Line: ".$badline." - Status (".$useable_data[$i][3].") is required and must be alphanumeric.<br>";
		}

// 2 - 5)  Comm
		if(strlen($useable_data[$i][4]) > 30){
			$return .= "Line: ".$badline." - Commodity (".$useable_data[$i][4].") cannot be longer than 30 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i][4])){
			$return .= "Line: ".$badline." - Commodity (".$useable_data[$i][4].") is required and must be alphanumeric.<br>";
		}

// 2 - 6)  Variety
		if(strlen($useable_data[$i][5]) > 30){
			$return .= "Line: ".$badline." - Variety (".$useable_data[$i][5].") cannot be longer than 30 characters.<br>";
		}

		if(!ereg("^([/ a-zA-Z0-9-])+$", $useable_data[$i][5])){
			$return .= "Line: ".$badline." - Variety (".$useable_data[$i][5].") is required and must be alphanumeric.<br>";
		}
/*
// 2 - 7)  Pack Type
		if(strlen($useable_data[$i][6]) > 100){
			$return .= "Line: ".$badline." - PackType (".$useable_data[$i][6].") cannot be longer than 100 characters.<br>";
		}

		if(!ereg("^([#%/ \.a-zA-Z0-9-])+$", $useable_data[$i][6])){
			$return .= "Line: ".$badline." - PackType (".$useable_data[$i][6].") is required and must be alphanumeric, or is allowed to contain -, %, $, /, ., and spaces.<br>";
		}

// 2 - 8)  Pack Style
		if(strlen($useable_data[$i][7]) > 100){
			$return .= "Line: ".$badline." - PackStyle (".$useable_data[$i][7].") cannot be longer than 100 characters.<br>";
		}

		if(!ereg("^([#%/ \.a-zA-Z0-9-])+$", $useable_data[$i][7])){
			$return .= "Line: ".$badline." - PackStyle (".$useable_data[$i][7].") must be alphanumeric, or is allowed to contain -, %, $, /, ., and spaces.<br>";
		}

// 2 - 9)  Grade
		if(strlen($useable_data[$i][8]) > 10){
			$return .= "Line: ".$badline." - Grade (".$useable_data[$i][8].") cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])*$", $useable_data[$i][8])){
			$return .= "Line: ".$badline." - Grade (".$useable_data[$i][8].") must be alphanumeric.<br>";
		}
*/
// 2 - 7)  Brand
		if(strlen($useable_data[$i][6]) > 30){
			$return .= "Line: ".$badline." - Brand (".$useable_data[$i][6].") cannot be longer than 30 characters.<br>";
		}

		if(!ereg("^([ a-zA-Z0-9-])+$", $useable_data[$i][6])){
			$return .= "Line: ".$badline." - Brand (".$useable_data[$i][6].") is required and must be alphanumeric.<br>";
		}

// 2 - 8)  Size
		if(strlen($useable_data[$i][7]) > 6){
			$return .= "Line: ".$badline." - Size (".$useable_data[$i][7].") cannot be longer than 6 characters.<br>";
		}

		if(!is_numeric($useable_data[$i][7])){
			$return .= "Line: ".$badline." - Size (".$useable_data[$i][7]."), if entered, must be numeric.<br>";
		}
/*
// 2 - 12)  PallMark
		if(strlen($useable_data[$i][11]) > 30){
			$return .= "Line: ".$badline." - PallMark (".$useable_data[$i][11].") cannot be longer than 30 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i][11])){
			$return .= "Line: ".$badline." - PallMark (".$useable_data[$i][11]."), if entered, must be alphanumeric.<br>";
		}

// 2 - 13)  UnitPall
		if(strlen($useable_data[$i][12]) > 5){
			$return .= "Line: ".$badline." - UnitPall (".$useable_data[$i][12].") cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([0-9])*$", $useable_data[$i][12])){
			$return .= "Line: ".$badline." - UnitPall (".$useable_data[$i][12]."), if entered, must be numeric.<br>";
		}
*/
// 2 - 9)  QTY
		if(strlen($useable_data[$i][8]) > 5){
			$return .= "Line: ".$badline." - Cartons (".$useable_data[$i][8].") cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([0-9])*$", $useable_data[$i][8])){
			$return .= "Line: ".$badline." - Cartons (".$useable_data[$i][8].") is required and must be numeric.<br>";
		}
/*
// 2 - 15)  Pallet
		if(strlen($useable_data[$i][14]) > 10){
			$return .= "Line: ".$badline." - PalletCnt (".$useable_data[$i][14].") cannot be longer than 10 characters.<br>";
		}

		if(!is_numeric($useable_data[$i][14])){
			$return .= "Line: ".$badline." - PalletCnt (".$useable_data[$i][14].") is required and must be numeric.<br>";
		}

// 2 - 16)  Grower
		if(strlen($useable_data[$i][15]) > 100){
			$return .= "Line: ".$badline." - Grower (".$useable_data[$i][15].") cannot be longer than 100 characters.<br>";
		}

		if(!ereg("^([/ a-zA-Z0-9-])*$", $useable_data[$i][15])){
			$return .= "Line: ".$badline." - Grower (".$useable_data[$i][15].") is required and must be alphanumeric, spaces, and /.<br>";
		}
*/
// 2 - 10)  ImportCode
		if(strlen($useable_data[$i][9]) > 20){
			$return .= "Line: ".$badline." - ImportCode (".$useable_data[$i][9].") cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([#/ a-zA-Z0-9-])+$", $useable_data[$i][9])){
			$return .= "Line: ".$badline." - ImportCode (".$useable_data[$i][9].") is required and must be alphanumeric.<br>";
		}

// 2 - 11)  Voucher
		if(strlen($useable_data[$i][10]) > 10){
			$return .= "Line: ".$badline." - Voucher (".$useable_data[$i][10].") cannot be longer than 10 characters.<br>";
		}

		if(!ereg("^([# a-zA-Z0-9-])+$", trim($useable_data[$i][10]))){
			$return .= "Line: ".$badline." - Voucher (".$useable_data[$i][10].") is required and must be alphanumeric, or contain #s.<br>";
		}
/*
// section 3:  valid string, invalid option

// 3 - 1)  Commodity code
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM DOLE_COMM_CONV
				WHERE DOLE_COMM = '".$useable_data[$i]["comm"]."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Commodity Not in Dole Conversion Table.<br>";
		}

// 3 - 2)  Customer#
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

// 3 - 3)  Vessel
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM VESSEL_PROFILE
				WHERE LR_NUM = '".$useable_data[$i]["ves"]."'";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Vessel# Not in VESSEL_PROFILE.<br>";
		}
*/
// 4 : make sure this isnt already in the system :)
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$useable_data[$i][2]."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '1626'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] >= 1){
			$return .= "Line: ".$badline." - Pallet already imported previously.<br>";
		}

		$i++;

	}

	return $return;

}
?>