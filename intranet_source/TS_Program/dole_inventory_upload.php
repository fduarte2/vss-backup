<?
/*
*		DECEMBER 2013, Adam Walter.
*
*		Script to accept uploads from Dole files, which we then use to populate
*		C_T and C_A.
*
*		... Yes, that means we are "trusting" dole to give us accurate info.
*		I make no comment as to the excellence of this idea,
*		Just let it be known that it wasn't mine.
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Dole Upload";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$date = $HTTP_POST_VARS['date'];
	if($date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $date)){
		echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format</font>";
		$date = "";
	}
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $date != "" ){
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".".date(mdYhis);
		$target_path_import = "./uploaded_manifests/".$impfilename;

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
			$useable_data[($i - 1)]["ves"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["pool"] = strtoupper(trim($data->sheets[0]['cells'][$i][2]));
//			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["cust"] = 9722;
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["var"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["label"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["size"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["hatch"] = trim($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][10]);
			$useable_data[($i - 1)]["loc"] = trim($data->sheets[0]['cells'][$i][11]);

//			print_r($useable_data[($i - 1)]);
		}

		$upload_valid = validate_upload($useable_data, $vessel, $rfconn);

		if($upload_valid != ""){
			echo "<font color=\"#FF0000\">The following errors were found in the uploaded file:<br><br>".$upload_valid."<br>Please Correct and Resubmit.</font>";
			// this file didn't work, update accordingly.
/*			$sql = "UPDAET WIP_UPLOADS
					SET VALID = 'N'
					WHERE UPLOAD_ID = '".$upload_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor); */
		} else {
			for($i = 2; $i <= $data->sheets[0]['numRows']; $i++){
				$sql = "SELECT PORT_COMM FROM DOLE_COMM_CONV
						WHERe DOLE_COMM = '".$useable_data[($i - 1)]['comm']."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$comm = ociresult($stid, "PORT_COMM");

				$sql = "INSERT INTO CARGO_TRACKING
							(ARRIVAL_NUM,
							RECEIVER_ID,
							PALLET_ID,
							COMMODITY_CODE,
							VARIETY,
							REMARK,
							CARGO_SIZE,
							HATCH,
							QTY_RECEIVED,
							QTY_IN_HOUSE,
							RECEIVING_TYPE,
							WAREHOUSE_LOCATION)
						VALUES
							('".$useable_data[($i - 1)]['ves']."',
							'".$useable_data[($i - 1)]['cust']."',
							'".$useable_data[($i - 1)]['BC']."',
							'".$comm."',
							'".$useable_data[($i - 1)]['var']."',
							'".$useable_data[($i - 1)]['label']."',
							'".$useable_data[($i - 1)]['size']."',
							'".$useable_data[($i - 1)]['hatch']."',
							'".$useable_data[($i - 1)]['qty']."',
							'".$useable_data[($i - 1)]['qty']."',
							'S',
							'".$useable_data[($i - 1)]['loc']."')";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
//				echo $sql."<br>";

				$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
						SET DOLE_POOL = '".$useable_data[($i - 1)]['pool']."'
						WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."'
							AND RECEIVER_ID = '".$useable_data[($i - 1)]['cust']."'
							AND ARRIVAL_NUM = '".$useable_data[($i - 1)]['ves']."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
//				echo $sql."<br>";

				$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							CUSTOMER_ID,
							DATE_OF_ACTIVITY,
							PALLET_ID,
							ARRIVAL_NUM,
							QTY_LEFT)
						(SELECT
							'1',
							'1',
							QTY_RECEIVED,
							'8761',
							RECEIVER_ID,
							TO_DATE('".$date." 12:00:03 AM', 'MM/DD/YYYY HH:MI:SS AM'),
							PALLET_ID,
							ARRIVAL_NUM,
							QTY_RECEIVED
						FROM CARGO_TRACKING
						WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."'
							AND RECEIVER_ID = '".$useable_data[($i - 1)]['cust']."'
							AND ARRIVAL_NUM = '".$useable_data[($i - 1)]['ves']."'
						)";
	//			echo $sql;
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "UPDATE CARGO_TRACKING
						SET DATE_RECEIVED = 
							(SELECT DATE_OF_ACTIVITY
							FROM CARGO_ACTIVITY 
							WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
								AND ARRIVAL_NUM = '".$useable_data[($i - 1)]['ves']."' 
								AND CUSTOMER_ID = '".$useable_data[($i - 1)]['cust']."' 
								AND ACTIVITY_NUM = '1')
						WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
							AND ARRIVAL_NUM = '".$useable_data[($i - 1)]['ves']."' 
							AND RECEIVER_ID = '".$useable_data[($i - 1)]['cust']."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
			
			}
			echo "<font color=\"#0000FF\">".($i - 2)." rows inserted.</font><br>";
		}
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Daily File Upload - Pallet Upload
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="dole_inventory_upload.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>Date:</b>&nbsp;&nbsp;<input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select File:</font>  <font size="2" face="Verdana">(.xls file please)</font></td>
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





function validate_upload($useable_data, $vessel, $rfconn){

	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";

/*
			$useable_data[($i - 1)]["ves"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["pool"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["var"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["label"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["size"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["hatch"] = trim($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][10]);
			$useable_data[($i - 1)]["loc"] = trim($data->sheets[0]['cells'][$i][11]);
*/

// step 1, make sure the header line is in place.
/*	if($useable_data[0]["ves"] != "vessel_ID" ||
		$useable_data[0]["pool"] != "POOL" ||
		$useable_data[0]["cust"] != "IMP" ||
		$useable_data[0]["BC"] != "PLT_ID" ||
		$useable_data[0]["comm"] != "Commodity" ||
		$useable_data[0]["var"] != "Variety" ||
		$useable_data[0]["label"] != "Label" ||
		$useable_data[0]["size"] != "Size" ||
		$useable_data[0]["hatch"] != "Hatch" ||
		$useable_data[0]["qty"] != "Qty" ||
		$useable_data[0]["loc"] != "Loc"){
			$return .= "Header line does not match up as expected";
	}
*/
// step 2, for each line...
	$i = 1; // IGNORE HEADERS
//	$i = 0; // FILE HAS NO HEADERS
	while($useable_data[$i]["ves"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

// 2 - 1)  vessel
		if(strlen($useable_data[$i]["ves"]) > 12){
			$return .= "Line: ".$badline." - Vessel cannot be longer than 12 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["ves"])){
			$return .= "Line: ".$badline." - vessel is required and must be alphanumeric.<br>";
		}

// 2 - 2)  pool
		if(strlen($useable_data[$i]["pool"]) > 10){
			$return .= "Line: ".$badline." - Pool cannot be longer than 10 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["pool"])){
			$return .= "Line: ".$badline." - Pool is required and must be alphanumeric.<br>";
		}

// 2 - 3)  cust
		if(strlen($useable_data[$i]["cust"]) > 6){
			$return .= "Line: ".$badline." - Customer cannot be longer than 6 characters.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["cust"])){
			$return .= "Line: ".$badline." - Customer is required and must be numeric.<br>";
		}

// 2 - 4)  BC
//		if(strlen($useable_data[$i]["BC"]) != 10 && strlen($useable_data[$i]["BC"]) != 18 && strlen($useable_data[$i]["BC"]) != 20){
		if(strlen($useable_data[$i]["BC"]) != 9 && strlen($useable_data[$i]["BC"]) != 10 && strlen($useable_data[$i]["BC"]) != 11 && strlen($useable_data[$i]["BC"]) != 13 && strlen($useable_data[$i]["BC"]) != 18 && strlen($useable_data[$i]["BC"]) != 20){

			$return .= "Line: ".$badline." - Barcode must be 9, 10, 11, 18, or 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
			$return .= "Line: ".$badline." - Barcode is required and must be alphanumeric.<br>";
		}

// 2 - 5)  Comm
		if(strlen($useable_data[$i]["comm"]) > 2){
			$return .= "Line: ".$badline." - Commodity cannot be longer than 2 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["comm"])){
			$return .= "Line: ".$badline." - Commodity is required and must be numeric.<br>";
		}

// 2 - 6)  Variety
		if(strlen($useable_data[$i]["var"]) > 20){
			$return .= "Line: ".$badline." - Variety cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", str_replace(" ", "", $useable_data[$i]["var"]))){
			$return .= $useable_data[$i]["var"]."  Line: ".$badline." - Variety is required and must be alphanumeric.<br>";
		}

// 2 - 7)  Label
		if(strlen($useable_data[$i]["label"]) > 20){
			$return .= "Line: ".$badline." - Label cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["label"])){
			$return .= "Line: ".$badline." - Label is required and must be alphanumeric.<br>";
		}

// 2 - 8)  Size
		if(strlen($useable_data[$i]["size"]) > 6){
			$return .= "Line: ".$badline." - Size cannot be longer than 6 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])*$", $useable_data[$i]["size"])){
			$return .= "Line: ".$badline." - Size  must be alphanumeric.<br>";
		}

// 2 - 9)  Hatch
		if(strlen($useable_data[$i]["hatch"]) > 6){
			$return .= "Line: ".$badline." - Hatch cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])*$", $useable_data[$i]["hatch"])){
			$return .= "Line: ".$badline." - Hatch must be alphanumeric.<br>";
		}

// 2 - 10)  Qty
		if(strlen($useable_data[$i]["qty"]) > 6){
			$return .= "Line: ".$badline." - QTY cannot be longer than 6 characters.<br>";
		}

		if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
			$return .= "Line: ".$badline." - QTY is required and must be numeric.<br>";
		}

// 2 - 11)  Loc
		if(strlen($useable_data[$i]["loc"]) > 12){
			$return .= "Line: ".$badline." - Location cannot be longer than 12 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])*$", $useable_data[$i]["loc"])){
			$return .= "Line: ".$badline." - Location, if entered, must be alphanumeric.<br>";
		}

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

// 4 : make sure this isnt already in the system :)
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$useable_data[$i]["BC"]."'
					AND ARRIVAL_NUM = '".$useable_data[$i]["ves"]."'
					AND RECEIVER_ID = '".$useable_data[$i]["cust"]."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") >= 1){
			$return .= "Line: ".$badline." - Pallet already imported previously.<br>";
		}

		$i++;

	}

	return $return;

}
?>