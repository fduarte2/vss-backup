<?
/*
*
*	Adam Walter, May 2013.
*
*	A screen for TS to "upload" juice into RF.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Juice Upload";
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
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".juice".date(mdYhis);
		$target_path_import = "./uploaded_manifests/".$impfilename;
		$source_note_name = substr($impfilename, 0, 50);

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
			$useable_data[($i - 1)]["BC"] = strip_badchars($data->sheets[0]['cells'][$i][1]);
			$useable_data[($i - 1)]["comm"] = strip_badchars($data->sheets[0]['cells'][$i][2]);
			$useable_data[($i - 1)]["headmark"] = strip_badchars($data->sheets[0]['cells'][$i][3]);
			$useable_data[($i - 1)]["bol"] = strip_badchars($data->sheets[0]['cells'][$i][4]);
			$useable_data[($i - 1)]["fullmark"] = strip_badchars($data->sheets[0]['cells'][$i][5], "keep");
			$useable_data[($i - 1)]["cust"] = strip_badchars($data->sheets[0]['cells'][$i][6]);
			$useable_data[($i - 1)]["exporter"] = strip_badchars(substr($data->sheets[0]['cells'][$i][7], 0, 20));
			$useable_data[($i - 1)]["qty"] = strip_badchars($data->sheets[0]['cells'][$i][8]);
			$useable_data[($i - 1)]["qty_rcvd"] = strip_badchars($data->sheets[0]['cells'][$i][9]);
			$useable_data[($i - 1)]["qty_unit"] = strip_badchars(substr($data->sheets[0]['cells'][$i][10], 0, 2));
			$useable_data[($i - 1)]["hatch"] = strip_badchars($data->sheets[0]['cells'][$i][11]);
			$useable_data[($i - 1)]["loc"] = strip_badchars($data->sheets[0]['cells'][$i][12]);
			$useable_data[($i - 1)]["batch"] = strip_badchars($data->sheets[0]['cells'][$i][13]);
			$useable_data[($i - 1)]["rectype"] = strip_badchars($data->sheets[0]['cells'][$i][14]);

//			print_r($useable_data[($i - 1)]);
//			echo "<br>";
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
							COMMODITY_CODE,
							MARK,
							BOL,
							CARGO_DESCRIPTION,
							RECEIVER_ID,
							EXPORTER_CODE,
							QTY_IN_HOUSE,
							QTY_RECEIVED,
							QTY_UNIT,
							HATCH,
							WAREHOUSE_LOCATION,
							BATCH_ID,
							RECEIVING_TYPE,
							ARRIVAL_NUM,
							SOURCE_NOTE,
							SOURCE_USER)
						VALUES
							('".$useable_data[($i - 1)]['BC']."',
							'".$useable_data[($i - 1)]['comm']."',
							'".$useable_data[($i - 1)]['headmark']."',
							'".$useable_data[($i - 1)]['bol']."',
							'".$useable_data[($i - 1)]['fullmark']."',
							'".$useable_data[($i - 1)]['cust']."',
							'".$useable_data[($i - 1)]['exporter']."',
							'".$useable_data[($i - 1)]['qty']."',
							'".$useable_data[($i - 1)]['qty_rcvd']."',
							'".$useable_data[($i - 1)]['qty_unit']."',
							'".$useable_data[($i - 1)]['hatch']."',
							'".$useable_data[($i - 1)]['loc']."',
							'".$useable_data[($i - 1)]['batch']."',
							'".$useable_data[($i - 1)]['rectype']."',
							'".$vessel."',
							'".$source_note_name."',
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

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'ARGJUICEUPLOAD'";
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
							'ARGJUICEUPLOAD',
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
	    <font size="5" face="Verdana" color="#0066CC">Juice Vessel Import
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="juice_import.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'ARG JUICE' ORDER BY LR_NUM DESC";
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
			$useable_data[($i - 1)]["BC"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][1]));
			$useable_data[($i - 1)]["comm"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][2]));
			$useable_data[($i - 1)]["headmark"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][3]));
			$useable_data[($i - 1)]["bol"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][4]));
			$useable_data[($i - 1)]["fullmark"] = trim($data->sheets[0]['cells'][$i][5]);
			$useable_data[($i - 1)]["cust"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][6]));
			$useable_data[($i - 1)]["exporter"] = substr(str_replace(" ", "", trim($data->sheets[0]['cells'][$i][7])), 0, 20);
			$useable_data[($i - 1)]["qty"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][8]));
			$useable_data[($i - 1)]["qty_unit"] = substr(str_replace(" ", "", trim($data->sheets[0]['cells'][$i][9])), 0, 2);
			$useable_data[($i - 1)]["hatch"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][10]));
			$useable_data[($i - 1)]["loc"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][11]));
			$useable_data[($i - 1)]["batch"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][12]));
			$useable_data[($i - 1)]["rectype"] = str_replace(" ", "", trim($data->sheets[0]['cells'][$i][13]));
*/
// step 1, make sure the header line is in place.

// step 2, for each line...
//	$i = 1; // NO HEADERS
	$i = 0;
	while($useable_data[$i]["BC"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.
//		echo "Line".$i."<br>";

// 2 - 1)  BC
		if(strlen($useable_data[$i]["BC"]) > 32){
			$return .= "Line: ".$badline." - Barcode cannot be longer than 32 characters.<br>";
		}
/*
		if(!ereg("^([a-zA-Z0-9-])+$", $useable_data[$i]["BC"])){
			$return .= "Line: ".$badline." - Barcode is required and must be alphanumeric.<br>";
		}
*/

// 2 - 2)  "comm" 
		if(strlen($useable_data[$i]["comm"]) > 12){
			$return .= "Line: ".$badline." - Commodity cannot be longer than 12 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["comm"])){
			$return .= "Line: ".$badline." - Commodity is required and must be numeric.<br>";
		}


// 2 - 3)  "headmark" 
		if(strlen($useable_data[$i]["headmark"]) > 10){
			$return .= "Line: ".$badline." - HeadMark cannot be longer than 10 characters.<br>";
		}
//		$temp = str_replace(array("/", ".", ":", "-"), "", $useable_data[$i]["headmark"]);
//		echo $temp."<br>";
/*		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - HeadMark is required and must be alphanumeric.<br>";
		}
*/

// 2 - 4)  bol
		if(strlen($useable_data[$i]["bol"]) > 20){
			$return .= "Line: ".$badline." - BoL cannot be longer than 20 characters.<br>";
		}
/*		$temp = str_replace(array("/", ".", ":", "-"), "", $useable_data[$i]["bol"]);
		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - BoL is required and must be alphanumeric.<br>";
		}
*/
// 2 - 5)  "fullmark" (CARGO_DESCRIPTION)
		if(strlen($useable_data[$i]["fullmark"]) > 60){
			$return .= "Line: ".$badline." - FullMark cannot be longer than 60 characters.<br>";
		}
/*		$temp = str_replace(array(" ", "/", ".", ":", "-"), "", $useable_data[$i]["fullmark"]);
//		echo "fullmark:".$temp."End<br>";
		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - FullMark is required and must be alphanumeric.<br>";
		}
*/
// 2 - 6)  cust
		if(strlen($useable_data[$i]["cust"]) > 6){
			$return .= "Line: ".$badline." - Customer cannot be longer than 6 characters.<br>";
		}
//		echo "cust:".$useable_data[$i]["cust"]."End<br>";
		if(!ereg("^([0-9])+$", $useable_data[$i]["cust"])){
			$return .= "Line: ".$badline." - Customer is required and must be numeric.<br>";
		}

// 2 - 7)  exporter
		if(strlen($useable_data[$i]["exporter"]) > 20){
			$return .= "Line: ".$badline." - Exporter cannot be longer than 20 characters.<br>";
		}
//		echo "exp:".$useable_data[$i]["exporter"]."End<br>";
/*		$temp = str_replace(array(" ", "/", ".", ":", "-"), "", $useable_data[$i]["exporter"]);
		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - Exporter is required and must be alphanumeric.<br>";
		}
*/
// 2 - 8)  qty
		if(strlen($useable_data[$i]["qty"]) > 8){
			$return .= "Line: ".$badline." - QTY cannot be longer than 6 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["qty"])){
			$return .= "Line: ".$badline." - QTY is required and must be numeric.<br>";
		}

// 2 - 9)  qty "rcvd"
		if(strlen($useable_data[$i]["qty_rcvd"]) > 8){
			$return .= "Line: ".$badline." - QTY RCVD cannot be longer than 6 characters.<br>";
		}
		if(!ereg("^([0-9])+$", $useable_data[$i]["qty_rcvd"])){
			$return .= "Line: ".$badline." - QTY RCVD is required and must be numeric.<br>";
		}

// 2 - 10)  qty_unit
		if(strlen($useable_data[$i]["qty_unit"]) > 2){
			$return .= "Line: ".$badline." - Quantity Unit cannot be longer than 2 characters.<br>";
		}
		if(!ereg("^([BI|DR])+$", $useable_data[$i]["qty_unit"])){
			$return .= "Line: ".$badline." - Quantity Unit is required and must be either BI or DR.<br>";
		}

// 2 - 11)  hatch
		if(strlen($useable_data[$i]["hatch"]) > 5){
			$return .= "Line: ".$badline." - Hatch cannot be longer than 5 characters.<br>";
		}
/*		$temp = str_replace(array(" ", "/", ".", ":", "-"), "", $useable_data[$i]["hatch"]);
		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - Hatch is required and must be alphanumeric.<br>";
		}
*/
// 2 - 12)  location
		if(strlen($useable_data[$i]["loc"]) > 12){
			$return .= "Line: ".$badline." - Location cannot be longer than 6 characters.<br>";
		}
/*		$temp = str_replace(array(" ", "/", ".", ":", "-"), "", $useable_data[$i]["loc"]);
		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - Location is required and must be alphanumeric.<br>";
		}
*/
// 2 - 13)  batch
		if(strlen($useable_data[$i]["batch"]) > 10){
			$return .= "Line: ".$badline." - Batch cannot be longer than 6 characters.<br>";
		}
/*		$temp = str_replace(array(" ", "/", ".", ":", "-"), "", $useable_data[$i]["batch"]);
		if(!ereg("^([a-zA-Z0-9])+$", $temp)){
			$return .= "Line: ".$badline." - Batch is required and must be alphanumeric.<br>";
		}
*/
// 2 - 14)  rcvd-type
		if(strlen($useable_data[$i]["rectype"]) > 1){
			$return .= "Line: ".$badline." - Receiving Type cannot be longer than 1 character.<br>";
		}
		if($useable_data[$i]["rectype"] != 'S'){
			$return .= "Line: ".$badline." - Receiving Type is required and must be S (for now).  It was ".$useable_data[$i]["rectype"]."<br>";
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
					AND COMMODITY_TYPE = 'ARG JUICE'";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") <= 0){
			$return .= "Line: ".$badline." - Commodity Not a \"JUICE\" Commodity# in COMMODITY_PROFILE.<br>";
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
//		echo $sql."<br>";
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

function strip_badchars($value, $keep_spaces="remove"){

	$value =  str_replace("\\", "", $value);
	$value =  str_replace("\"", "", $value);
	$value =  str_replace(",", "", $value);
	$value =  str_replace("'", "`", $value);
	if($keep_spaces != "keep"){
		$value =  str_replace(" ", "", $value);
	}
	$value = trim($value);

	return $value;
}

?>