<?
/*
*	Adam Walter, Sep 2013.
*
*	splash page showing release status of containers.
*************************************************************************/

	$pagename = "dole9722_activity";  
	include("dole9722_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}


	$date = $HTTP_POST_VARS['date'];
	if($date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $date)){
		echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format</font>";
		$date = "";
	}
	$submit = $HTTP_POST_VARS['submit'];
	$filetype = $HTTP_POST_VARS['filetype'];

	if($submit != "" && $date != "" ){
		$impfilename = str_replace(" ", "", basename($HTTP_POST_FILES['import_file']['name'])).".".date(mdYhis);
		$target_path_import = "./uploaded_activity/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}

		$sql = "SELECT DOLE9722_UPLOAD_SEQ.NEXTVAL THE_NEXT FROM DUAL";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$id = ociresult($stid, "THE_NEXT");

		$sql = "INSERT INTO DOLE_9722_HEADER
					(FILE_ID,
					FILENAME,
					INSERT_DATE,
					REPORT_DATE,
					MAN_OR_AUTO,
					SAVE_TO_CT_STATUS)
				VALUES
					('".$id."',
					'".$impfilename."',
					SYSDATE,
					TO_DATE('".$date."', 'MM/DD/YYYY'),
					'MANUAL',
					'AUTO')";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);

		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$useable_data = array();

		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		$start_row = "";
		$temp = 1;
		while($start_row == "" && $temp < $data->sheets[0]['numRows']){
			if($data->sheets[0]['cells'][$temp][1] != ""){
				$start_row = $temp;
			}
			$temp++;
		}



		for ($i = $start_row ; $i <= $data->sheets[0]['numRows']; $i++) {
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
			$useable_data[($i - 1)]["BC"] = strtoupper(trim($data->sheets[0]['cells'][$i][3]));
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["time"] = str_replace("60", "59", trim($data->sheets[0]['cells'][$i][5]));
			$useable_data[($i - 1)]["order"] = strtoupper(trim($data->sheets[0]['cells'][$i][6]));
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["var"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["hour"] = trim($data->sheets[0]['cells'][$i][9]);
//			$useable_data[($i - 1)]["loc"] = trim($data->sheets[0]['cells'][$i][11]);
			if(substr($useable_data[($i - 1)]["BC"], 0, 2) == "00"){
				$useable_data[($i - 1)]["BC"] = substr($useable_data[($i - 1)]["BC"], 2);
			}
			if(substr($useable_data[($i - 1)]["BC"], 0, 1) == "Z"){
				$useable_data[($i - 1)]["BC"] = substr($useable_data[($i - 1)]["BC"], 1);
			}
	

			if($i == $start_row){
				// if this is the first line, check to make sure its in order.
				if($useable_data[($start_row  - 1)]["act_full"] != "Activity" ||
					$useable_data[($start_row  - 1)]["pool"] != "POOL" ||
					$useable_data[($start_row  - 1)]["BC"] != "PALLET NUMBER" ||
					$useable_data[($start_row  - 1)]["qty"] != "Boxes" ||
					$useable_data[($start_row  - 1)]["time"] != "Time" ||
					$useable_data[($start_row  - 1)]["order"] != "ORDER" ||
					($useable_data[($start_row  - 1)]["comm"] != "Commodity" && $useable_data[($start_row  - 1)]["comm"] != "Com") ||
					$useable_data[($start_row  - 1)]["var"] != "Variety" ||
					$useable_data[($start_row  - 1)]["hour"] != "Hour"){
						echo "<font color=\"#FF0000\">Column Headings do not match up as expected.<BR>Column headings must be as follows:<br>
								Column A:  Activity (entered: ".$useable_data[($start_row  - 1)]["act_full"].")<br>
								Column B:  Pool (entered: ".$useable_data[($start_row  - 1)]["pool"].")<br>
								Column C:  Pallet Number (entered: ".$useable_data[($start_row  - 1)]["BC"].")<br>
								Column D:  Boxes (entered: ".$useable_data[($start_row  - 1)]["qty"].")<br>
								Column E:  Time (entered: ".$useable_data[($start_row  - 1)]["time"].")<br>
								Column F:  Order (entered: ".$useable_data[($start_row  - 1)]["order"].")<br>
								Column G:  Commodity (or Com) (entered: ".$useable_data[($start_row  - 1)]["comm"].")<br>
								Column H:  Variety (entered: ".$useable_data[($start_row  - 1)]["var"].")<br>
								Column I:  Hour (entered: ".$useable_data[($start_row  - 1)]["hour"].")<br><br>
								Cancelling upload.  Please fix column headings and ensure they are in the order described above and Re-Upload.</font><br>";
						system("/bin/mv ".$target_path_import." ".$target_path_import."errorheader");
						exit;
				}
			}
		}

		for($i = ($start_row + 1); $i <= $data->sheets[0]['numRows']; $i++){
			validate_write_DBstrict($useable_data, ($i - 1), $id, $rfconn);
		}

		// raw lines (maybe) in DB.  now we process those.
		perform_DB_move($id, $date, $rfconn);

		displayResults($id, $rfconn);
	}




?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Manual File Upload - Activities
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="dole9722_v2_index.php" method="post">
	<tr>
		<td><a href="./uploaded_activity/?M=D">View Past Uploads</a><br><br></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Date:</b>&nbsp;&nbsp;<input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
		<br>
		<font size="2" face="Verdana">(This is used as the Date of the activity for all Pallets in the file.)</font>
	</tr>
<!--	<tr>
		<td><font size="2" face="Verdana"><b>File Type:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Automatic<input type="radio" name="filetype" value="auto" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manual Correction<input type="radio" name="filetype" value="manual"></font></td>
	</tr> !-->
	<tr>
		<td><a href="Activity1.xls">Click Here</a> For a sample Activity File.</td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select File:</font>  <font size="2" face="Verdana">(.xls file please)</font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file" size="100"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"></td>
	</tr>
</form>
</table>
















<?
function validate_write_DBstrict($useable_data, $i, $file_id, $rfconn){
	// each cell has it's own validation
	// for the script to have goten this far, we know that all fields comprise
	// only of alphanumeric, spaces, dashes, or underscore characters.

	// step 1.  not much.
	$error = "";

	$full_line = $useable_data[$i]["act_full"]."_".$useable_data[$i]["pool"]."_".$useable_data[$i]["BC"]."_".$useable_data[$i]["qty"]."_".$useable_data[$i]["time"]."_".
					$useable_data[$i]["order"]."_".$useable_data[$i]["comm"]."_".$useable_data[$i]["var"]."_".$useable_data[$i]["hour"];

	$sql = "INSERT INTO DOLE_9722_DETAIL
				(FILE_ID,
				ROW_NUM,
				FULL_LINE)
			VALUES
				('".$file_id."',
				'".($i + 1)."',
				'".$full_line."')";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
			
	if(strpos(strtoupper($useable_data[$i]["BC"]), "PALLET") !== false || strtoupper($useable_data[$i]["BC"]) == ""){
		$sql = "UPDATE DOLE_9722_DETAIL
				SET IGNORE_LINE = 'Y'
				WHERE FILE_ID = '".$file_id."'
					AND ROW_NUM = '".($i + 1)."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
	} else {

		// step 2, for each line...
	// 2 - 1)  Activity Type
		if($useable_data[$i]["act"] != 2 && $useable_data[$i]["act"] != 3 && $useable_data[$i]["act"] != 4){
			$error .= "Activity Type can only be \"2\" or \"3\" or \"4\"<br>";
		}

	// 2 - 2)  pool
		if(strlen($useable_data[$i]["pool"]) > 10){
			$error .= "Pool cannot be longer than 10 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["pool"])){
			$error .= "Pool is required and must be alphanumeric.<br>";
		}

	// 2 - 3)  BC
		if(strlen($useable_data[$i]["BC"]) > 32){
			$error .= "Barcode is required and cannot be longer thatn 32 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
			$error .= "Barcode is required and must be alphanumeric.<br>";
		}


	// 2 - 4)  Qty
		if(strlen($useable_data[$i]["qty"]) > 6){
			$error .= "QTY cannot be longer than 6 characters.<br>";
		}

		if($useable_data[$i]["act"] != 3){
			if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
				$error .= "QTY is required and must be numeric (negative values aren't allowed for Activity type ".$useable_data[$i]["act"].").<br>";
			}
		} else {
			if(!ereg("^([0-9-])+$", $useable_data[$i]["qty"])){
				$error .= "QTY is required and must be numeric.<br>";
			}
		}

	// 2 - 5)  Time
		if(strlen($useable_data[$i]["time"]) > 5){
			$error .= "Time cannot be longer than 5 characters.<br>";
		}

		if(!ereg("^([0-1]{0,1}[0-9]{1}):([0-5][0-9])$", $useable_data[$i]["time"])) {
			$error .= "Time is required and must be in HH:MM format.<br>";
		}

	// 2 - 6)  Order
		if(strlen($useable_data[$i]["order"]) > 12){
			$error .= "Order cannot be longer than 12 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["order"])){
			$error .= "Order is required and must be alphanumeric.<br>";
		}

	// 2 - 7)  Commodity
		if(strlen($useable_data[$i]["comm"]) > 2){
			$error .= "Commodity cannot be longer than 2 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["comm"])){
			$error .= "Commodity is required and must be alphanumeric.<br>";
		}

	// 2 - 8)  Variety (?)
		if(strlen($useable_data[$i]["var"]) > 20){
			$error .= "Variety cannot be longer than 20 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9 _-])+$", $useable_data[$i]["var"])){
			$error .= "Variety is required and must be alphanumeric.<br>";
		}

	// 2 - 9)  Hour (??)
		if(strlen($useable_data[$i]["hour"]) > 2){
			$error .= "Hour cannot be longer than 2 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["hour"])){
			$error .= "Hour is required and must be alphanumeric.<br>";
		}


		if($error != ""){// && strpos(strtoupper($useable_data[$rowcount]["BC"]), "PALLET") === false){
			$sql = "UPDATE DOLE_9722_DETAIL
					SET ERROR_INPUT_LINE = '".$error."'
					WHERE FILE_ID = '".$file_id."'
						AND ROW_NUM = '".($i + 1)."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
		} else {
			$sql = "UPDATE DOLE_9722_DETAIL
					SET ACTIVITY = '".$useable_data[$i]["act"]."',
						POOL = '".$useable_data[$i]["pool"]."',
						PALLET_ID = '".$useable_data[$i]["BC"]."',
						QTY = '".$useable_data[$i]["qty"]."',
						THE_TIME = '".$useable_data[$i]["time"]."',
						THE_ORDER = '".$useable_data[$i]["order"]."',
						COMM = '".$useable_data[$i]["comm"]."',
						VARIETY = '".$useable_data[$i]["var"]."',
						THE_HOUR = '".$useable_data[$i]["hour"]."'
					WHERE FILE_ID = '".$file_id."'
						AND ROW_NUM = '".($i + 1)."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
		}
	}

}

function perform_DB_move($id, $date, $rfconn){
	$sql = "SELECT * FROM DOLE_9722_DETAIL
			WHERE FILE_ID = '".$id."'
				AND ERROR_INPUT_LINE IS NULL
				AND IGNORE_LINE IS NULL
			ORDER BY TO_DATE(THE_TIME, 'HH24:MI'), ROW_NUM";
	$lines = ociparse($rfconn, $sql);
	ociexecute($lines);
	while(ocifetch($lines)){
		$error_insert = "";

		// 1 : make sure this isnt already in the system :)
		$sql = "SELECT COUNT(*) THE_COUNT_IMPORTED, TO_CHAR(MAX(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') DATE_REC
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
					AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
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
		if(ociresult($stid, "THE_COUNT_IMPORTED") >= 1 && ociresult($lines, "ACTIVITY") == "2"){
			$error_insert .= "Pallet already imported previously at ".ociresult($stid, "DATE_REC").".<br>";
		} elseif(ociresult($stid, "THE_COUNT_IMPORTED") < 1 && ociresult($lines, "ACTIVITY") == "3"){
			$error_insert .= "Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." was never received into the system, and therefore cannot be shipped out.<br>";
		} 
		
		// 2:  make sure it's still "in house" enough for a shipout.
		if(ociresult($lines, "ACTIVITY") == "3" && ociresult($lines, "QTY") >= 0){
			$plts_that_ever_existed = 0; //initialize in case SQL returns no rows later
			$sql = "SELECT COUNT(*) TOTAL_EVER
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
						AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '9722'
						AND DATE_RECEIVED IS NOT NULL";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$plts_that_ever_existed = ociresult($stid, "TOTAL_EVER");

			$sql = "SELECT COUNT(*) THE_IH
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
						AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '9722'
						AND QTY_IN_HOUSE >= '".ociresult($lines, "QTY")."'";
		//			echo $sql."<br>";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "THE_IH") > 1){
				$error_insert .= "Multiple Pallets Inhouse with sufficient qty to ship match this barcode.  Please contact PoW.<br>";
			} elseif(ociresult($stid, "THE_IH") < 1 && $plts_that_ever_existed >= 1) {
				// no pallets available.  error message possibility 1:  no outbound activity yet, just too high a qty
				$sql = "SELECT COUNT(*) THE_IH
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
						AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '9722'
						AND QTY_IN_HOUSE = QTY_RECEIVED
						AND QTY_IN_HOUSE < '".ociresult($lines, "QTY")."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				if(ociresult($stid, "THE_IH") >= 1){
					//error message possibility 2:
					$error_insert .= "Requested Shipment Amount for Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." is larger than the pallet`s actual original quantity.<br>";
				} else {
					// ok, so its not just an overshipment.  get the remaining amount and shipout data.
					$sql = "SELECT QTY_IN_HOUSE, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') DATE_OUT
							FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
							WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
								AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
								AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
								AND CT.PALLET_ID = CA.PALLET_ID
								AND CT.RECEIVER_ID = CA.CUSTOMER_ID
								AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
								AND CT.PALLET_ID = CTAD.PALLET_ID
								AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
								AND CT.RECEIVER_ID = '9722'
								AND CA.ACTIVITY_NUM != '1'
								AND CA.SERVICE_CODE = '6'
								AND CA.ACTIVITY_DESCRIPTION IS NULL
								AND QTY_IN_HOUSE < '".ociresult($lines, "QTY")."'
							GROUP BY QTY_IN_HOUSE";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
					if(ocifetch($stid)){
						// well, there's a problem.  which is it?
						if(ociresult($stid, "QTY_IN_HOUSE") < 1){
							//error message possibility 3:
							$error_insert .= "Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." shipped out as of ".ociresult($stid, "DATE_OUT")."<br>";
						} else {
							//error message possibility 4:
							$error_insert .= "Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." cases In-House: ".ociresult($stid, "QTY_IN_HOUSE")." Cannot ship out ".ociresult($lines, "QTY")." cases.<br>Pallet was partially shipped on ".ociresult($stid, "DATE_OUT")."<br>";
						}
					} else {
						//error message possibility 5:
						// (note:  this is basically a "something wrong with the DB" error.  If you see this, check for missing CA records, invalid QTY_IN_HOUSEs, or whatnot)
						$error_insert .= "Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." current In-House qty of ".ociresult($lines, "QTY")." Cannot ship out ".ociresult($lines, "QTY").".<br>Please contact PoW if you feel this is in error.<br>";
					}
				}
			}
		} elseif(ociresult($lines, "ACTIVITY") == "3" && ociresult($lines, "QTY") < 0){
			// this is an "unship".
			// is there a "ship" that it counters?
			$sql = "SELECT COUNT(*) LINES
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
						AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.RECEIVER_ID = '9722'
						AND CA.SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL
						AND CA.ORDER_NUM = '".ociresult($lines, "THE_ORDER")."'
						AND CA.QTY_CHANGE = (-1 * ".ociresult($lines, "QTY").")
						AND DATE_RECEIVED IS NOT NULL";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "LINES") > 1){
				$error_insert .= "Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." cannot unship; multiple ship lines found in system.  Contact PoW.<br>";
			} elseif(ociresult($stid, "LINES") < 1){
				$error_insert .= "Pallet ID: ".ociresult($lines, "PALLET_ID").", Pool#: ".ociresult($lines, "POOL")." cannot unship; no outbound shipment matching the QTY found.<br>";
			}
		}


		// 3:  If this is a return, make sure it was shipped in the first place.
		$sql = "SELECT COUNT(*) THE_COUNT_OUTBOUND
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
					AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '9722'
					AND QTY_IN_HOUSE != QTY_RECEIVED
					AND CA.ACTIVITY_NUM != '1'
					AND CA.SERVICE_CODE = '6'
					AND CA.ACTIVITY_DESCRIPTION IS NULL";
	//			echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT_OUTBOUND") > 1 && ociresult($lines, "ACTIVITY") == "4"){
			$error_insert .= "Pallet has multiple outbound shipments.  Please handle return manually.<br>";
		} elseif(ociresult($stid, "THE_COUNT_OUTBOUND") < 1 && ociresult($lines, "ACTIVITY") == "4"){
			$error_insert .= "Pallet is not shipped out; cannot return.<br>";
		} 

		if($error_insert != ""){// && strpos(strtoupper($useable_data[$rowcount]["BC"]), "PALLET") === false){
			$sql = "UPDATE DOLE_9722_DETAIL
					SET ERROR_MOVE_TO_DB = '".$error_insert."'
					WHERE FILE_ID = '".$id."'
						AND ROW_NUM = '".ociresult($lines, "ROW_NUM")."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		} else {
			$sql = "SELECT PORT_COMM FROM DOLE_COMM_CONV
					WHERE DOLE_COMM = '".ociresult($lines, "COMM")."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$comm = ociresult($stid, "PORT_COMM");

			$sql = "SELECT FILENAME
					FROM DOLE_9722_HEADER
					WHERE FILE_ID = '".$id."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$fname = ociresult($stid, "FILENAME");
			if(strlen($fname) > 50){
				$fname = substr($fname, -50);
			}

			if(ociresult($lines, "ACTIVITY") == 2){
				$sql = "INSERT INTO CARGO_TRACKING
							(ARRIVAL_NUM,
							RECEIVER_ID,
							PALLET_ID,
							COMMODITY_CODE,
							VARIETY,
							QTY_RECEIVED,
							QTY_IN_HOUSE,
							WEIGHT,
							WEIGHT_UNIT,
							RECEIVING_TYPE,
							SOURCE_NOTE)
						VALUES
							('".ociresult($lines, "POOL")."',
							'9722',
							'".ociresult($lines, "PALLET_ID")."',
							'".$comm."',
							'".ociresult($lines, "VARIETY")."',
							'".ociresult($lines, "QTY")."',
							'".ociresult($lines, "QTY")."',
							'1950',
							'LB',
							'T',
							'".$fname."')";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
//				echo $sql."<br>";

				$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
						SET DOLE_POOL = '".ociresult($lines, "POOL")."'
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
							AND RECEIVER_ID = '9722'
							AND ARRIVAL_NUM = '".ociresult($lines, "POOL")."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
//				echo $sql."<br>";

				ReceivePallet(ociresult($lines, "PALLET_ID"), 9722, ociresult($lines, "POOL"), $date, ociresult($lines, "THE_TIME"), $rfconn);
			
			} elseif(ociresult($lines, "ACTIVITY") == 3 && ociresult($lines, "QTY") >= 0){ // shipout
				$sql = "SELECT CT.ARRIVAL_NUM 
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
						WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
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
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
							AND RECEIVER_ID = '9722'
							AND ARRIVAL_NUM = '".$ves."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$is_rec = ociresult($stid, "THE_REC");
				if($is_rec == "NONE"){
					ReceivePallet(ociresult($lines, "PALLET_ID"), 9722, $ves, $date, ociresult($lines, "THE_TIME"), $rfconn);
				}

				$sql = "UPDATE CARGO_TRACKING
						SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".ociresult($lines, "QTY");
				$sql .= " WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
							AND RECEIVER_ID = '9722' 
							AND ARRIVAL_NUM = '".$ves."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
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
						'".ociresult($lines, "QTY")."',
						'8761',
						'".ociresult($lines, "THE_ORDER")."',
						'9722',
						TO_DATE('".$date." ".ociresult($lines, "THE_TIME").":30', 'MM/DD/YYYY HH24:MI:SS'),
						'".ociresult($lines, "PALLET_ID")."',
						'".$ves."')";
//						echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
			}elseif(ociresult($lines, "ACTIVITY") == 3 && ociresult($lines, "QTY") < 0){ // "unship" (to us, that's a Dock Return)
				$sql = "SELECT CA.ARRIVAL_NUM, CA.ACTIVITY_NUM
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_ACTIVITY CA
						WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
							AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
							AND CT.RECEIVER_ID = '9722'
							AND QTY_IN_HOUSE != QTY_RECEIVED
							AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
							AND CT.PALLET_ID = CA.PALLET_ID
							AND CT.RECEIVER_ID = CA.CUSTOMER_ID
							AND CT.PALLET_ID = CTAD.PALLET_ID
							AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
							AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
							AND CA.ORDER_NUM = '".ociresult($lines, "THE_ORDER")."'
							AND CA.QTY_CHANGE = (-1 * ".ociresult($lines, "QTY").")
							AND DATE_RECEIVED IS NOT NULL";
//						echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$ves = ociresult($stid, "ARRIVAL_NUM");
				$act_num = ociresult($stid, "ACTIVITY_NUM");

				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX 
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
							AND CUSTOMER_ID = '9722' 
							AND ARRIVAL_NUM = '".$ves."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$next_num = ociresult($stid, "THE_MAX") + 1;

				$sql = "UPDATE CARGO_TRACKING
						SET QTY_IN_HOUSE = QTY_IN_HOUSE - ".ociresult($lines, "QTY")."
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
							AND RECEIVER_ID = '9722' 
							AND ARRIVAL_NUM = '".$ves."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "UPDATE CARGO_ACTIVITY
						SET ACTIVITY_DESCRIPTION = 'RETURN'
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
							AND CUSTOMER_ID = '9722' 
							AND ARRIVAL_NUM = '".$ves."'
							AND ACTIVITY_NUM = '".$act_num."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM)
						VALUES
						('".$next_num."',
						'13',
						(-1 * ".ociresult($lines, "QTY")."),
						'8761',
						'".ociresult($lines, "THE_ORDER")."',
						'9722',
						TO_DATE('".$date." ".ociresult($lines, "THE_TIME").":30', 'MM/DD/YYYY HH24:MI:SS'),
						'".ociresult($lines, "PALLET_ID")."',
						'".$ves."')";
//						echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

			}elseif(ociresult($lines, "ACTIVITY") == 4){
				$sql = "SELECT CT.ARRIVAL_NUM 
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
						WHERE CT.PALLET_ID = '".ociresult($lines, "PALLET_ID")."'
							AND CTAD.DOLE_POOL = '".ociresult($lines, "POOL")."'
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
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
							AND CUSTOMER_ID = '9722' 
							AND ARRIVAL_NUM = '".$ves."'";
//						echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$next_num = ociresult($stid, "THE_MAX") + 1;

				$sql = "SELECT ACTIVITY_NUM, QTY_CHANGE 
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
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
				$sql .= " WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
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
						'".ociresult($lines, "THE_ORDER")."',
						'9722',
						TO_DATE('".$date." ".ociresult($lines, "THE_TIME").":30', 'MM/DD/YYYY HH24:MI:SS'),
						'".ociresult($lines, "PALLET_ID")."',
						'".$ves."')";
//						echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

				$sql = "UPDATE CARGO_ACTIVITY
						SET ACTIVITY_DESCRIPTION = 'RETURN'";
				$sql .= " WHERE PALLET_ID = '".ociresult($lines, "PALLET_ID")."' 
							AND CUSTOMER_ID = '9722' 
							AND ARRIVAL_NUM = '".$ves."'
							AND ACTIVITY_NUM = '".$replace_num."'";
//						echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);

			}
		}
	}
}

function displayResults($id, $rfconn){
	$good_lines = 0;

	$sql = "SELECT * 
			FROM DOLE_9722_DETAIL
			WHERE FILE_ID = '".$id."'
			ORDER BY ROW_NUM";
	$lines = ociparse($rfconn, $sql);
	ociexecute($lines);
	while(ocifetch($lines)){
		if(ociresult($lines, "ERROR_INPUT_LINE") != ""){
			echo "<font color=\"#FF0000\">Line ".ociresult($lines, "ROW_NUM")." could not be accepted from file; ".ociresult($lines, "ERROR_INPUT_LINE")."<br></font>";
		} elseif(ociresult($lines, "ERROR_MOVE_TO_DB") != ""){
			echo "<font color=\"#FF0000\">Line ".ociresult($lines, "ROW_NUM")." could not be applied to PoW system; ".ociresult($lines, "ERROR_MOVE_TO_DB")."<br></font>";
		} elseif(ociresult($lines, "IGNORE_LINE") != ""){
			// do nothing
		} else {
			$good_lines++;
		}
	}

	echo "<font color=\"#0000FF\"><br>".$good_lines." Lines processed in PoW system.<br></font>";
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
