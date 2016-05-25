<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for TS to "upload" stell into RF.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Chilean Upload";
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
//		printf(ora_errorcode($conn));
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $vessel != ""){
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
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["code"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["mark"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["wt"] = round(trim($data->sheets[0]['cells'][$i][5]), 2);
			$useable_data[($i - 1)]["wtunit"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["DO"] = trim($data->sheets[0]['cells'][$i][9]);

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
			for($i = 1; $i <= $data->sheets[0]['numRows']; $i++){
				$sql = "INSERT INTO CARGO_TRACKING
							(PALLET_ID,
							WAREHOUSE_LOCATION,
							CARGO_DESCRIPTION,
							QTY_RECEIVED,
							QTY_IN_HOUSE,
							WEIGHT,
							WEIGHT_UNIT,
							COMMODITY_CODE,
							RECEIVER_ID,
							REMARK,
							RECEIVING_TYPE,
							ARRIVAL_NUM,
							SOURCE_NOTE,
							SOURCE_USER)
						VALUES
							('".$useable_data[($i - 1)]['BC']."',
							'".$useable_data[($i - 1)]['code']."',
							'".$useable_data[($i - 1)]['mark']."',
							'".$useable_data[($i - 1)]['qty']."',
							'".$useable_data[($i - 1)]['qty']."',
							'".$useable_data[($i - 1)]['wt']."',
							'".$useable_data[($i - 1)]['wtunit']."',
							'".$useable_data[($i - 1)]['comm']."',
							'".$useable_data[($i - 1)]['cust']."',
							'".$useable_data[($i - 1)]['DO']."',
							'S',
							'".$vessel."',
							'".$impfilename."',
							'".$user."')";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
//				echo $sql."<br>";
			}
			echo "<font color=\"#0000FF\">".$i." rows inserted.</font><br>";

			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
			$vescurs = ociparse($rfconn, $sql);
			ociexecute($vescurs);
			ocifetch($vescurs);
			$vesname = $vessel." - ".ociresult($vescurs, "VESSEL_NAME");

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'STEELUPLOAD'";
			$email = ociparse($rfconn, $sql);
			ociexecute($email);
			ocifetch($email);

			$mailTO = ociresult($email, "TO");
			$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
			if(ociresult($email, "TEST") == "Y"){
				$mailTO = "awalter@port.state.de.us";
				$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
			} else {
				$mailTO = ociresult($email, "TO");
				if(ociresult($email, "CC") != ""){
					$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
				}
				if(ociresult($email, "BCC") != ""){
					$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
				}
			}
			$mailheaders .= "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
			$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
			$mailheaders .= "X-Mailer: PHP4\r\n";
			$mailheaders .= "X-Priority: 3\r\n";
	//		$mailheaders .= "Return-Path: MailServer@port.state.de.us\r\n";
			$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", $vesname, $mailSubject);

			$body = ociresult($email, "NARRATIVE");
			$body = str_replace("_1_", $i, $body);

			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= $body;
			$Content.="\r\n";

			$attach = chunk_split(base64_encode(file_get_contents($target_path_import)));
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/xls; name=\"".$HTTP_POST_FILES['import_file']['name']."\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$attach;
			$Content.="\r\n";

			if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
				$sql = "INSERT INTO JOB_QUEUE
							(JOB_ID,
							SUBMITTER_ID,
							SUBMISSION_DATETIME,
							JOB_TYPE,
							JOB_DESCRIPTION,
							DATE_JOB_COMPLETED,
							COMPLETION_STATUS,
							JOB_EMAIL_TO,
							JOB_EMAIL_CC,
							JOB_EMAIL_BCC,
							JOB_BODY)
						VALUES
							(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
							'INSTANTCRON',
							SYSDATE,
							'EMAIL',
							'STEELUPLOAD',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);
			}
		
		
		}
	}






?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">SSAB Steel Vessel Import
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="steel_import.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'STEEL' ORDER BY LR_NUM DESC";
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
			$useable_data[($i - 1)]["BC"] = trim($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["code"] = trim($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["mark"] = trim($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["qty"] = trim($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["wt"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["wtunit"] = trim($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][7]);
			$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["DO"] = trim($data->sheets[0]['cells'][$i][9]);
*/
// step 1, make sure the header line is in place.
/*	if($useable_data[0]["BC"] != "plt_id" ||
		$useable_data[0]["code"] != "warehouse" ||
		$useable_data[0]["mark"] != "cargo_desc" ||
		$useable_data[0]["qty"] != "qty" ||
		$useable_data[0]["wt"] != "weight" ||
		$useable_data[0]["wtunit"] != "weight_unit" ||
		$useable_data[0]["comm"] != "comm" ||
		$useable_data[0]["cust"] != "receiver" ||
		$useable_data[0]["DO"] != "DO"){
			$return .= "Header line does not match up as expected";
	}
*/
// step 2, for each line...
//	$i = 1; // NO HEADERS
	$i = 0;
	while($useable_data[$i]["BC"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

// 2 - 1)  BC
		if(strlen($useable_data[$i]["BC"]) > 32){
			$return .= "Line: ".$badline." - Barcode cannot be longer than 32 characters.<br>";
		}

		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
			$return .= "Line: ".$badline." - Barcode is required and must be alphanumeric.<br>";
		}


// 2 - 2)  "code" (WHS location)
		if(strlen($useable_data[$i]["code"]) > 12){
			$return .= "Line: ".$badline." - Code cannot be longer than 12 characters.<br>";
		}
		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["code"])){
			$return .= "Line: ".$badline." - Code is required and must be alphanumeric.<br>";
		}


// 2 - 3)  "mark" (CARGO_DESCRIPTION)
		if(strlen($useable_data[$i]["mark"]) > 60){
			$return .= "Line: ".$badline." - Mark cannot be longer than 60 characters.<br>";
		}
		$temp = str_replace(" ", "", $useable_data[$i]["mark"]);
		if(!ereg("^([a-zA-Z0-9\/\.-])+$", $temp)){
			$return .= "Line: ".$badline." - Mark is required and must be alphanumeric.<br>";
		}


// 2 - 4)  qty
		if(strlen($useable_data[$i]["qty"]) > 3){
			$return .= "Line: ".$badline." - QTY cannot be longer than 3 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
			$return .= "Line: ".$badline." - QTY is required and must be numeric.<br>";
		}

// 2 - 5)  weight
		if(strlen($useable_data[$i]["wt"]) > 9){
			$return .= "Line: ".$badline." - Weight cannot be longer than 8 numbers and a decimal.<br>";
		}
		if(!ereg("^([0-9]{1,6}[\.]{0,1}[0-9]{0,2})+$", $useable_data[$i]["wt"])){
			$return .= "Line: ".$badline." - Weight is required and must be numeric.<br>";
		}

// 2 - 6)  weight unit
		if(strlen($useable_data[$i]["wtunit"]) > 2){
			$return .= "Line: ".$badline." - Weight Unit cannot be longer than 2 characters.<br>";
		}
//		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["wtunit"])){
		if(!ereg("^([L][B])$", $useable_data[$i]["wtunit"])){
			$return .= "Line: ".$badline." - Weight Unit is required and must be LB (for now).<br>";
		}

// 2 - 7)  commodity
		if(strlen($useable_data[$i]["comm"]) > 12){
			$return .= "Line: ".$badline." - Commodity cannot be longer than 12 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["comm"])){
			$return .= "Line: ".$badline." - Commodity is required and must be numeric.<br>";
		}

// 2 - 8)  customer
		if(strlen($useable_data[$i]["cust"]) > 6){
			$return .= "Line: ".$badline." - Receiver ID cannot be longer than 6 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["cust"])){
			$return .= "Line: ".$badline." - Receiver ID is required and must be numeric.<br>";
		}

// 2 - 9)  DO
		if(strlen($useable_data[$i]["DO"]) > 10){
			$return .= "Line: ".$badline." - Delivery Order# cannot be longer than 6 characters.<br>";
		}
		if(!ereg("^([a-zA-Z0-9])+$", $useable_data[$i]["DO"])){
			$return .= "Line: ".$badline." - Delivery Order# is required and must be alphanumeric.<br>";
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
				WHERE COMMODITY_CODE = '".$useable_data[$i]["comm"]."'
					AND COMMODITY_TYPE = 'STEEL'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Commodity Not a \"STEEL\" Commodity# in COMMODITY_PROFILE.<br>";
		}

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

// 4 : make sure this isnt already in the system :)
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$useable_data[$i]["BC"]."'
					AND ARRIVAL_NUM = '".$vessel."'
					AND RECEIVER_ID = '".$useable_data[$i]["cust"]."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") >= 1){
			$return .= "Line: ".$badline." - Pallet already imported.<br>";
		}

		$i++;

	}

	return $return;

}
?>