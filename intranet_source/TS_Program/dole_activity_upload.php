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
	$filetype = $HTTP_POST_VARS['filetype'];

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
			if($filetype == "manual"){
				if(trim($data->sheets[0]['cells'][$i][1]) != ""){
					// only save if a new value found
					$useable_data[($i - 1)]["act"] = substr(trim($data->sheets[0]['cells'][$i][1]), 0, 1);
					$useable_data[($i - 1)]["act_full"] = trim($data->sheets[0]['cells'][$i][1]);
				} else {
					// if blank field, keep the previous value.  If this is the first pass, this file will fail later on, so no biggie.
					$useable_data[($i - 1)]["act"] = $useable_data[($i - 2)]["act"];
					$useable_data[($i - 1)]["act_full"] = $useable_data[($i - 2)]["act_full"];
				}
				if(trim($data->sheets[0]['cells'][$i][2]) != ""){
					// same as column 1
					$useable_data[($i - 1)]["pool"] = strtoupper(trim($data->sheets[0]['cells'][$i][2]));
				} else {
					$useable_data[($i - 1)]["pool"] = $useable_data[($i - 2)]["pool"];
				}
	//			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][3]);
	//			$useable_data[($i - 1)]["BC"] = ConvertBC(trim($data->sheets[0]['cells'][$i][3]), $useable_data[($i - 1)]["pool"], $rfconn);
				$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][3]);
				$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][4]);
				$useable_data[($i - 1)]["time"] = trim($data->sheets[0]['cells'][$i][5]);
				$useable_data[($i - 1)]["order"] = strtoupper(trim($data->sheets[0]['cells'][$i][6]));
				$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][7]);
				$useable_data[($i - 1)]["var"] = trim($data->sheets[0]['cells'][$i][8]);
				$useable_data[($i - 1)]["hour"] = trim($data->sheets[0]['cells'][$i][9]);
	//			$useable_data[($i - 1)]["loc"] = trim($data->sheets[0]['cells'][$i][11]);
			} else {
				if(trim($data->sheets[0]['cells'][$i][1]) != ""){
					// only save if a new value found
					$useable_data[($i - 1)]["act"] = substr(trim($data->sheets[0]['cells'][$i][1]), 0, 1);
					$useable_data[($i - 1)]["act_full"] = trim($data->sheets[0]['cells'][$i][1]);
				} else {
					// if blank field, keep the previous value.  If this is the first pass, this file will fail later on, so no biggie.
					$useable_data[($i - 1)]["act"] = $useable_data[($i - 2)]["act"];
					$useable_data[($i - 1)]["act_full"] = $useable_data[($i - 2)]["act_full"];
				}
				if(trim($data->sheets[0]['cells'][$i][5]) != ""){
					// same as column 1
					$useable_data[($i - 1)]["pool"] = strtoupper(trim($data->sheets[0]['cells'][$i][5]));
				} else {
					$useable_data[($i - 1)]["pool"] = $useable_data[($i - 2)]["pool"];
				}
				$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][7]);
				$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][10]);
				$useable_data[($i - 1)]["time"] = trim($data->sheets[0]['cells'][$i][11]);
				$useable_data[($i - 1)]["order"] = strtoupper(trim($data->sheets[0]['cells'][$i][12]));
				$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][13]);
				$useable_data[($i - 1)]["var"] = trim($data->sheets[0]['cells'][$i][15]);
				$useable_data[($i - 1)]["hour"] = trim($data->sheets[0]['cells'][$i][16]);
			}

			if($i == 1){
				// if this is the first line, check to make sure its in order.
				if($useable_data[0]["act_full"] != "Activity" ||
					$useable_data[0]["pool"] != "POOL" ||
					$useable_data[0]["BC"] != "Pallet Number" ||
					$useable_data[0]["qty"] != "Boxes" ||
					$useable_data[0]["time"] != "Time" ||
					$useable_data[0]["order"] != "ORDER" ||
					($useable_data[0]["comm"] != "Commodity" && $useable_data[0]["comm"] != "Com") ||
					$useable_data[0]["var"] != "Variety" ||
					$useable_data[0]["hour"] != "Hour"){
						echo "<font color=\"#FF0000\">Header line does not match up as expected.  Cancelling upload.</font><br>";
						include("pow_footer.php");
						exit;
			//			print_r($useable_data[0]);
				}
			}

//			print_r($useable_data[($i - 1)]);

		}

		for($i = 2; $i <= $data->sheets[0]['numRows']; $i++){
			// skip "total" lines
			if(strpos(strtoupper($useable_data[($i - 1)]["BC"]), "PALLET") === false && $useable_data[($i - 1)]["BC"] != ""){
				// only do this line if it checks out
				$line_result = validate_upload($useable_data, ($i - 1), $vessel, $rfconn);
				if($line_result == ""){

					$sql = "SELECT PORT_COMM FROM DOLE_COMM_CONV
							WHERe DOLE_COMM = '".$useable_data[($i - 1)]['comm']."'";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
					ocifetch($stid);
					$comm = ociresult($stid, "PORT_COMM");
	//					echo "Line ".$i."   ".$sql."<br>";


					if($useable_data[($i - 1)]["act"] == 2){
						$sql = "INSERT INTO CARGO_TRACKING
									(ARRIVAL_NUM,
									RECEIVER_ID,
									PALLET_ID,
									COMMODITY_CODE,
									VARIETY,
									QTY_RECEIVED,
									QTY_IN_HOUSE,
									RECEIVING_TYPE)
								VALUES
									('".$useable_data[($i - 1)]['pool']."',
									'9722',
									'".$useable_data[($i - 1)]['BC']."',
									'".$comm."',
									'".$useable_data[($i - 1)]['var']."',
									'".$useable_data[($i - 1)]['qty']."',
									'".$useable_data[($i - 1)]['qty']."',
									'T')";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
		//				echo $sql."<br>";

						$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
								SET DOLE_POOL = '".$useable_data[($i - 1)]['pool']."'
								WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."'
									AND RECEIVER_ID = '9722'
									AND ARRIVAL_NUM = '".$useable_data[($i - 1)]['pool']."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
		//				echo $sql."<br>";

						ReceivePallet($useable_data[($i - 1)]['BC'], 9722, $useable_data[($i - 1)]['pool'], $date, $useable_data[($i - 1)]['time'], $rfconn);
					
					} elseif($useable_data[($i - 1)]["act"] == 3){
						$sql = "SELECT CT.ARRIVAL_NUM 
								FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
								WHERE CT.PALLET_ID = '".$useable_data[($i - 1)]['BC']."'
									AND CT.RECEIVER_ID = '9722'
									AND QTY_IN_HOUSE > 0
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM";
	//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$ves = ociresult($stid, "ARRIVAL_NUM");

						$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'NONE') THE_REC
								FROM CARGO_TRACKING
								WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."'
									AND RECEIVER_ID = '9722'
									AND ARRIVAL_NUM = '".$ves."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$is_rec = ociresult($stid, "THE_REC");
						if($is_rec == "NONE"){
							ReceivePallet($useable_data[($i - 1)]['BC'], 9722, $ves, $date, $useable_data[($i - 1)]['time'], $rfconn);
						}

						$sql = "UPDATE CARGO_TRACKING
								SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".$useable_data[($i - 1)]['qty'];
						$sql .= " WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
									AND RECEIVER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

						$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
								FROM CARGO_ACTIVITY 
								WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$next_num = ociresult($stid, "THE_MAX") + 1;

						$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM)
								VALUES
								('".$next_num."',
								'6',
								'".$useable_data[($i - 1)]['qty']."',
								'8761',
								'".$useable_data[($i - 1)]['order']."',
								'9722',
								TO_DATE('".$date." ".$useable_data[($i - 1)]["time"].":30', 'MM/DD/YYYY HH24:MI:SS'),
								'".$useable_data[($i - 1)]['BC']."',
								'".$ves."')";
	//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
					}elseif($useable_data[($i - 1)]["act"] == 4){
						$sql = "SELECT CT.ARRIVAL_NUM 
								FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
								WHERE CT.PALLET_ID = '".$useable_data[($i - 1)]['BC']."'
									AND CTAD.DOLE_POOL = '".$useable_data[($i - 1)]["pool"]."'
									AND CT.RECEIVER_ID = '9722'
									AND QTY_IN_HOUSE <= 10
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$ves = ociresult($stid, "ARRIVAL_NUM");

						$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
								FROM CARGO_ACTIVITY 
								WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$next_num = ociresult($stid, "THE_MAX") + 1;

						$sql = "SELECT ACTIVITY_NUM, QTY_CHANGE 
								FROM CARGO_ACTIVITY 
								WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'
									AND SERVICE_CODE = '6'
								ORDER BY ACTIVITY_NUM DESC";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$replace_num = ociresult($stid, "ACTIVITY_NUM");
						$qty_to_add = ociresult($stid, "QTY_CHANGE");

						$sql = "UPDATE CARGO_TRACKING
								SET QTY_IN_HOUSE = QTY_IN_HOUSE + ".$qty_to_add;
						$sql .= " WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
									AND RECEIVER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

						$sql = "INSERT INTO CARGO_ACTIVITY
								(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM)
								VALUES
								('".$next_num."',
								'7',
								'".$qty_to_add."',
								'8761',
								'".$useable_data[($i - 1)]['order']."',
								'9722',
								TO_DATE('".$date." ".$useable_data[($i - 1)]["time"].":30', 'MM/DD/YYYY HH24:MI:SS'),
								'".$useable_data[($i - 1)]['BC']."',
								'".$ves."')";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

						$sql = "UPDATE CARGO_ACTIVITY
								SET ACTIVITY_DESCRIPTION = 'RETURN'";
						$sql .= " WHERE PALLET_ID = '".$useable_data[($i - 1)]['BC']."' 
									AND CUSTOMER_ID = '9722' 
									AND ARRIVAL_NUM = '".$ves."'
									AND ACTIVITY_NUM = '".$replace_num."'";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);

					}

				} else {
					echo "<font color=\"#FF0000\">Line ".$i." was skipped in the uploaded file for the following error(s): ".$line_result."<br></font>";
				}
			}
		}
		echo "<font color=\"#0000FF\">".($i - 2)." rows parsed.</font><br>";
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Daily File Upload - Activities
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="dole_activity_upload.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>Date:</b>&nbsp;&nbsp;<input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>File Type:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Automatic<input type="radio" name="filetype" value="auto" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manual Correction<input type="radio" name="filetype" value="manual"></font></td>
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





function validate_upload($useable_data, $i, $vessel, $rfconn){

	// each cell has it's own validation, as well as a few "global" checks.
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	$return = "";


// step 1, make sure the header line is in place.
/*	if($useable_data[0]["act_full"] != "Activity" ||
		$useable_data[0]["pool"] != "POOL" ||
		$useable_data[0]["BC"] != "Pallet Number" ||
		$useable_data[0]["qty"] != "Boxes" ||
		$useable_data[0]["time"] != "Time" ||
		$useable_data[0]["order"] != "ORDER" ||
		$useable_data[0]["comm"] != "Commodity" ||
		$useable_data[0]["var"] != "Variety" ||
		$useable_data[0]["hour"] != "Hour"){
			$return .= "Header line does not match up as expected<br>";
//			print_r($useable_data[0]);
	}
*/
// step 2, for each line...
//	$i = 1; // IGNORE HEADERS
//	$i = 0; // FILE HAS NO HEADERS
//	while($useable_data[$i]["act"] != ""){
//		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

//		if(strpos(strtoupper($useable_data[$i]["act_full"]), "TOTAL") === false){
			// we are skipping "total" lines


// 2 - 1)  Activity Type
	if($useable_data[$i]["act"] != 2 && $useable_data[$i]["act"] != 3 && $useable_data[$i]["act"] != 4){
		$return .= "Activity Type can only be \"2\" or \"3\" or \"4\"<br>";
	}

// 2 - 2)  pool
	if(strlen($useable_data[$i]["pool"]) > 10){
		$return .= "Pool cannot be longer than 10 characters.<br>";
	}

	if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["pool"])){
		$return .= "Pool is required and must be alphanumeric.<br>";
	}

// 2 - 3)  BC
	if(strlen($useable_data[$i]["BC"]) > 32){
		$return .= "Barcode is required and cannot be longer thatn 32 characters.<br>";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
		$return .= "Barcode is required and must be alphanumeric.<br>";
	}

// 2 - 4)  Qty
	if(strlen($useable_data[$i]["qty"]) > 6){
		$return .= "QTY cannot be longer than 6 characters.<br>";
	}

	if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
		$return .= "QTY is required and must be numeric.<br>";
	}

// 2 - 5)  Time
	if(strlen($useable_data[$i]["time"]) > 5){
		$return .= "Time cannot be longer than 5 characters.<br>";
	}

	if(!ereg("^([0-1]{0,1}[0-9]{1}):([0-5][0-9])$", $useable_data[$i]["time"])) {
		$return .= "Time is required and must be in HH:MM format.<br>";
	}

// 2 - 6)  Order
	if(strlen($useable_data[$i]["order"]) > 12){
		$return .= "Order cannot be longer than 12 characters.<br>";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["order"])){
		$return .= "Order is required and must be alphanumeric.<br>";
	}

// 2 - 7)  Commodity
	if(strlen($useable_data[$i]["comm"]) > 2){
		$return .= "Commodity cannot be longer than 2 characters.<br>";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["comm"])){
		$return .= "Commodity is required and must be numeric.<br>";
	}

// 2 - 8)  Variety (?)
	if(strlen($useable_data[$i]["var"]) > 20){
		$return .= "Variety cannot be longer than 20 characters.<br>";
	}

	if(!ereg("^([a-zA-Z0-9 _-])+$", $useable_data[$i]["var"])){
		$return .= "Variety is required and must be alphanumeric.<br>";
	}

// 2 - 9)  Hour (??)
	if(strlen($useable_data[$i]["hour"]) > 2){
		$return .= "Hour cannot be longer than 2 characters.<br>";
	}

	if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["hour"])){
		$return .= "Hour is required and must be alphanumeric.<br>";
	}

// section 3:  valid string, invalid option
/*
// 3 - 1)  Commodity code
	$sql = "SELECT COUNT(*) THE_COUNT
			FROM DOLE_COMM_CONV
			WHERE DOLE_COMM = '".$useable_data[$i]["comm"]."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") <= 0){
		$return .= "Commodity Not in Dole Conversion Table.<br>";
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
		$return .= "Customer# Not in CUSTOMER_PROFILE.<br>";
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
		$return .= "Vessel# Not in VESSEL_PROFILE.<br>";
	}
*/
// 4 : make sure this isnt already in the system :)
	$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data[$i]["pool"]."'
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND CA.ACTIVITY_NUM = '1'";
//			echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT_IMPORTED") >= 1 && $useable_data[$i]["act"] == "2"){
		$return .= "Pallet already imported previously.<br>";
	} elseif(ociresult($stid, "THE_COUNT_IMPORTED") < 1 && $useable_data[$i]["act"] == "3"){
		$return .= "Cannot have shipout info for a pallet that isn't imported<br>";
	} 
	
// 5:  make sure it's still "in house" enough for a shipout.
	$sql = "SELECT COUNT(*) THE_IH
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data[$i]["pool"]."'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND QTY_IN_HOUSE >= '".$useable_data[$i]["qty"]."'";
//			echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_IH") < 1 && $useable_data[$i]["act"] == "3"){
		$return .= "Pallet showing as no longer in house and/or insufficient shipout qty for request, cannot enact outbound shipment entry.<br>";
	} elseif(ociresult($stid, "THE_IH") > 1 && $useable_data[$i]["act"] == "3"){
		$return .= "Multiple Pallets Inhouse with sufficient qty to ship match this barcode.<br>";
	}

// 6:  If this is a return, make sure it was shipped in the first place.
	$sql = "SELECT COUNT(*) THE_COUNT_OUTBOUND
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = '".$useable_data[$i]["BC"]."'
				AND CTAD.DOLE_POOL = '".$useable_data[$i]["pool"]."'
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '9722'
				AND CA.ACTIVITY_NUM != '1'
				AND CA.SERVICE_CODE = '6'
				AND CA.ACTIVITY_DESCRIPTION IS NULL";
//			echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT_OUTBOUND") > 1 && $useable_data[$i]["act"] == "4"){
		$return .= "Pallet has multiple outbound shipments.  Please handle return manually.<br>";
	} elseif(ociresult($stid, "THE_COUNT_OUTBOUND") < 1 && $useable_data[$i]["act"] == "4"){
		$return .= "Pallet is not shipped out; cannot return.<br>";
	} 



//		$i++;
	return $return;

}




function ReceivePallet($plt, $cust, $ves, $date, $time, $rfconn){
			$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM,
						SERVICE_CODE,
						ORDER_NUM,
						QTY_CHANGE,
						ACTIVITY_ID,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
					(SELECT
						'1',
						DECODE(RECEIVING_TYPE, 'T', '8', '1'),
						DECODE(RECEIVING_TYPE, 'T', ARRIVAL_NUM, NULL),
						QTY_RECEIVED,
						'8761',
						RECEIVER_ID,
						TO_DATE('".$date." ".$time.":00', 'MM/DD/YYYY HH24:MI:SS'),
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_RECEIVED
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$plt."'
						AND RECEIVER_ID = '".$cust."'
						AND ARRIVAL_NUM = '".$ves."'
					)";
//			echo $sql;
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "UPDATE CARGO_TRACKING
					SET DATE_RECEIVED = 
						(SELECT DATE_OF_ACTIVITY
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".$plt."' 
							AND ARRIVAL_NUM = '".$ves."' 
							AND CUSTOMER_ID = '".$cust."' 
							AND ACTIVITY_NUM = '1')
					WHERE PALLET_ID = '".$plt."' 
						AND ARRIVAL_NUM = '".$ves."' 
						AND RECEIVER_ID = '".$cust."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
}



/*
function ConvertBC($pallet_id, $pool, $rfconn){

	$return = $pallet_id;

	// if it passes this check, it's an exact match, and there is no reason to continue.
	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND PALLET_ID = '".$pallet_id."' 
				AND DOLE_POOL = '".$pool."'
				AND CT.RECEIVER_ID = '9722'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		return $return;
	}

	$six_digit_pallet = GetSixDigitPallet($pallet_id);

	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND (
					SUBSTR(PALLET_ID, 5, 6) = '".$six_digit_pallet."'
					OR
					SUBSTR(PALLET_ID, 14, 6) = '".$six_digit_pallet."'
					OR
					SUBSTR(PALLET_ID, 12, 6) = '".$six_digit_pallet."'
					)
				AND DOLE_POOL = '".$pool."'
				AND CT.RECEIVER_ID = '9722'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 2){
		// multiple matches.  send back the original pallet, becasue this is bad.
		return $pallet_id;
	} elseif(ociresult($stid, "THE_COUNT") == 1){
		// just 1 found.  grab it and return it.
		$sql = "SELECT CT.PALLET_ID 
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND (
						SUBSTR(PALLET_ID, 5, 6) = '".$six_digit_pallet."'
						OR
						SUBSTR(PALLET_ID, 14, 6) = '".$six_digit_pallet."'
						OR
						SUBSTR(PALLET_ID, 12, 6) = '".$six_digit_pallet."'
						)
					AND DOLE_POOL = '".$pool."'
					AND CT.RECEIVER_ID = '9722'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		return ociresult($stid, "PALLET_ID");
	}

	
	
	// if no conversions work or are applicable, pass barcode back as is, and let code handle the notification.

	return $pallet_id;
}
*/


/*
function GetSixDigitPallet($pallet_id){
	if(strlen($pallet_id) == 10){
		return substr($pallet_id, 4, 6);
	}
	if(strlen($pallet_id) == 18){
		return substr($pallet_id, 11, 6);
	}
	if(strlen($pallet_id) == 20){
		return substr($pallet_id, 13, 6);
	}

	return $pallet_id;
}
*/
?>