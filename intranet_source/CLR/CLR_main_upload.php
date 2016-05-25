<?
/*
*  Adam Walter, Oct 2014.
*
*	uploads new lines to CLR's Main Data Table
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Argen Juice Barcode Correction";
  $area_type = "CLR";

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
//		printf(ora_errorcode($rfconn));
		exit;
	}
	$pagename = "CLR_main_upload";
	include("page_security.php");
//	$security_allowance = PageSecurityCheck($user, $pagename, "test");
	$security_allowance = PageSecurityCheck($user, $pagename, "");




	$EDI_entry = array();
	$LR = array();
	$Lloyd = array();
	$Voyage = array();
	$Vesname = array();
	$Shipline = array();
	$bol = array();
	$cust = array();
	$cont = array();
	$plts = array();
	$cases = array();
	$comm = array();
	$weight = array();
	$weight_unit = array();
	$broker = array();
	$load = array();
	$mode = array();
	$Amendtype = array();
	$Amendcode = array();
	$Action = array();
	$Destination = array();
	$submit = $HTTP_POST_VARS['submit'];
	$origin = $HTTP_POST_VARS['origin'];
	if($submit == "Upload Single Entry"){
		$EDI_entry[0] = "EDI ID#";
		$LR[0] = "MATCHED ARRIVAL #";
		$Lloyd[0] = "LLOYD#";
		$Voyage[0] = "VOYAGE#";
		$Vesname[0] = "VESSEL NAME";
		$Shipline[0] = "SHIP LINE";
		$bol[0] = "BOL";
		$cust[0] = "CONSIGNEE CODE";
		//skip custname
		$cont[0] = "CONTAINER#";
		$plts[0] = "PALLET COUNT";
		$cases[0] = "CASE COUNT";
		$comm[0] = "COMMODITY CODE";
		//SKIP COMMNAME
		$weight[0] = "WEIGHT";
		$weight_unit[0] = "WT UNIT";
		// skip lbs and eachweight
		$broker[0] = "BROKER";
		$load[0] = "LOAD TYPE";
		$mode[0] = "MODE";
		$Amendtype[0] = "AMEND TYPE";
		$Amendcode[0] = "AMEND CODE";
		$Action[0] = "ACTION";
		$Destination[0] = "DESTINATION";

		$EDI_entry[1] = "";
		$LR[1] = filter_input($HTTP_POST_VARS['LR']);
		$Lloyd[1] = filter_input($HTTP_POST_VARS['Lloyd']);
		$Voyage[1] = filter_input($HTTP_POST_VARS['Voyage']);
		while(substr($Voyage[1], 0, 1) === "0"){
			$Voyage[1] = substr($Voyage[1], 1);
		}
		$Vesname[1] = GetVesName($LR[1], $rfconn);
		$Shipline[1] = filter_input($HTTP_POST_VARS['Shipline']);
		$bol[1] = filter_input($HTTP_POST_VARS['bol']);
		$cust[1] = filter_input($HTTP_POST_VARS['cust']);
		$custname[1] = GetCustName($cust[1], $rfconn);
		$cont[1] = filter_input($HTTP_POST_VARS['cont']);
		$plts[1] = filter_input($HTTP_POST_VARS['plts']);
		$cases[1] = filter_input($HTTP_POST_VARS['cases']);
		$comm[1] = filter_input($HTTP_POST_VARS['comm']);
		$commname[1] = GetCommName($comm[1], $rfconn);
		$weight[1] = filter_input($HTTP_POST_VARS['weight']);
		$weight_unit[1] = filter_input($HTTP_POST_VARS['weight_unit']);
		// skip lbs and eachweight
		$broker[1] = filter_input($HTTP_POST_VARS['broker']);
		$load[1] = filter_input($HTTP_POST_VARS['load']);
		$mode[1] = filter_input($HTTP_POST_VARS['mode']);
		$Amendtype[1] = "";
		$Amendcode[1] = "";
		$Action[1] = "A";
		$Destination[1] = filter_input($HTTP_POST_VARS['dest']);

		$arraymax = 1;
	} elseif($submit == "Upload File Entry"){
		$impfilename = basename($HTTP_POST_FILES['upload_file']['name']).".".date(mdYhis);
		$target_path_import = "./uploaded_files/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['upload_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}

		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();

		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$EDI_entry[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][1]);
			$LR[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][2]);
			$Lloyd[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][3]);
			$Voyage[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][4]);
			while(substr($Voyage[($i - 1)], 0, 1) === "0"){
				$Voyage[($i - 1)] = substr($Voyage[($i - 1)], 1);
			}
			$Vesname[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][5]);
			$Shipline[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][6]);
			$bol[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][7]);
			$cust[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][8]);
			$custname[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][9]);
			$cont[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][10]);
			$plts[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][11]);
			$cases[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][12]);
			$comm[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][13]);
			$commname[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][14]);
			$weight[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][15]);
			$weight_unit[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][16]);
			// skip lbs and eachweight
			$broker[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][19]);
			$load[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][20]);
			$mode[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][21]);
			$Amendtype[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][22]);
			$Amendcode[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][23]);
			$Destination[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][24]);
			$Action[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][25]);

			if($cont[($i - 1)] == ""){
				$cont[($i - 1)] = "NC";
			}
		}

		$arraymax = ($i - 2); // minus 1 for the fact that the XLS_SPREADSHEET_READER starts at 1 instead of zero, and 1 more because its a "for" loop, so $i will be one higher than the size at the end


	}
	if($submit != ""){
		// above populated the arrays, do they pass mustard?
//		$error_msg = VerifyInput($arraymax, $submit, $LR, $cust, $comm, $cont, $bol, $broker, $plts, $cases, $mode, $load, $weight, $weight_unit, $EDI_entry, $rfconn);
		$error_msg = VerifyInput($arraymax, $submit, $EDI_entry, $LR, $Lloyd, $Voyage, $Vesname, $Shipline, $bol, $cust, $custname, $cont, $plts, $cases, $comm, $commname, $weight, $weight_unit, $broker, $mode, $load, $Amendtype, $Amendcode, $Destination, $Action, $rfconn);

		if($error_msg == ""){
			// no errors.  starting with first valid input line, start updating
			$i = 1;
			while($i <= $arraymax){
				if($EDI_entry[$i] == "" || $Action[$i] == "A"){
					$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE
							WHERE CUSTOMER_ID = '".$cust[$i]."'";
					$short_term_data = ociparse($rfconn, $sql);
					ociexecute($short_term_data);
					ocifetch($short_term_data);
					$custname[$i] = ociresult($short_term_data, "CUSTOMER_NAME");

					$sql = "INSERT INTO CLR_MAIN_DATA
								(CLR_KEY,
								ARRIVAL_NUM,
								LLOYD_NUM,
								VOYAGE_NUM,
								VESNAME,
								CUSTOMER_ID,
								COMMODITY_CODE,
								COMMODITY,
								CONTAINER_NUM,
								BOL_EQUIV,
								CONSIGNEE,
								BROKER,
								PLTCOUNT,
								QTY,
								SHIPLINE,
								CARGO_MODE,
								LOAD_TYPE,
								WEIGHT,
								WEIGHT_UNIT,
								BROKER_RELEASE,
								DESTINATION,
								ORIGIN_M11,
								MOST_RECENT_EDIT_DATE,
								MOST_RECENT_EDIT_BY,
								ORIGINAL_INSERT)
							VALUES
								(CLR_MAIN_DATA_SEQ.NEXTVAL,
								'".$LR[$i]."',
								'".$Lloyd[$i]."',
								'".$Voyage[$i]."',
								'".$Vesname[$i]."',
								'".$cust[$i]."',
								'".$comm[$i]."',
								'".$commname[$i]."',
								'".$cont[$i]."',
								'".$bol[$i]."',
								'".$custname[$i]."',
								'".$broker[$i]."',
								'".$plts[$i]."',
								'".$cases[$i]."',
								'".$Shipline[$i]."',
								'".$mode[$i]."',
								'".$load[$i]."',
								'".$weight[$i]."',
								'".$weight_unit[$i]."',
								SYSDATE,
								'".$Destination[$i]."',
								'".$origin."',
								SYSDATE,
								'".$user."',
								SYSDATE)";
					$inserts = ociparse($rfconn, $sql);
					ociexecute($inserts);
				}

				if($EDI_entry[$i] != ""){
					$sql = "UPDATE CLR_AMSEDI_DETAIL_309
							SET PUSH_TO_CLR_ON = SYSDATE
							WHERE KEY_ID || '-' || ENTRY_NUM = '".$EDI_entry[$i]."'";
					$inserts = ociparse($rfconn, $sql);
					ociexecute($inserts);
				}

				$i++;
			}
			echo "<font color=\"#0000FF\">".$arraymax." record(s) added.<br></font>";

		} else {
			echo "<font color=\"#FF0000\">Could not process request, for the following reason:<br>".$error_msg."<br>Please correct and resubmit.<br></font>";
		}
	}

		

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Manual Entry
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form enctype="multipart/form-data" action="CLR_main_upload.php" method="post" name="the_upload">
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><a href="CLR_upload_template.xls"><font size="3" face="Verdana"><b>Click Here</a> (and choose "Save") For the Template Excel File</b></font></td>
	</tr>
	<tr>
		<td width="50%">
		<table border="0" width="100%" cellpadding="4" cellspacing="0"> <!-- single-entry half of screen !-->
			<tr>
				<td colspan="2" align="center"><font size="3" face="Verdana"><b>Single Entry:</b></font></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Arrival#:</font></td><td><select name="LR"><option value="">Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
				WHERE LLOYD_NUM IS NOT NULL
			ORDER BY LR_NUM DESC";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"><? echo ociresult($short_term_data, "LR_NUM")." - ".ociresult($short_term_data, "VESSEL_NAME"); ?></option>
<?
	}
?>
				</select></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Lloyd's #:</font></td><td><input type="text" name="Lloyd" size="20" maxlength="20"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Voyage#:</font></td><td><input type="text" name="Voyage" size="20" maxlength="20"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Origin:</font></td><td><select name="origin">
							<option value="CHILE">CHILE</option>
							<option value="MOROCCO">MOROCCO</option>
							<option value="ARGENTINA">ARGENTINA</option>
				</select></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Destination:</font></td><td><select name="dest">
							<option value="DOMESTIC">DOMESTIC</option>
							<option value="CANADIAN">CANADIAN</option>
				</select></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Shipping Line:</font></td><td><input type="text" name="Shipline" size="10" maxlength="20"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Customer# (Consignee):</font></td><td><select name="cust"><option value="">Select a Customer</option>
<?
	$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME
			FROM CUSTOMER_PROFILE
			ORDER BY CUSTOMER_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>"><? echo ociresult($short_term_data, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
				</select></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Commodity#:</font></td><td><select name="comm"><option value="">Select a Commodity</option>
<?
	$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
			FROM COMMODITY_PROFILE
			ORDER BY COMMODITY_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
						<option value="<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?>"><? echo ociresult($short_term_data, "COMMODITY_NAME"); ?></option>
<?
	}
?>
				</select></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Container# (optional):</font></td><td><input type="text" name="cont" size="20" maxlength="20"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">BoL:</font></td><td><input type="text" name="bol" size="20" maxlength="20"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Broker (optional):</font></td><td><input type="text" name="broker" size="20" maxlength="60"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Pallets:</font></td><td><input type="text" name="plts" size="10" maxlength="10"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Cases:</font></td><td><input type="text" name="cases" size="6" maxlength="6"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Weight (optional):</font></td><td><input type="text" name="weight" size="10" maxlength="10"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Weight Unit:</font></td><td><input type="text" name="weight_unit" size="2" maxlength="2"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Cargo Mode#:</font></td><td><select name="mode"><option value="">Select a Mode</option>
							<option value="HTH">HTH</option>
							<option value="SWING">SWING</option>
							<option value="STRIP">STRIP</option>
							<option value="FUME">FUME</option>
							<option value="PREFUMED">PREFUME</option>
							<option value="PREINSPD">PREINSPD</option>
							<option value="BREAKBULK">BREAKBULK</option>
				</select></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Load Type#:</font></td><td><select name="load"><option value="">Select a Load Type</option>
							<option value="FCL">FCL</option>
							<option value="LCL">LCL</option>
							<option value="BBULK">BBULK</option>
				</select></td>
			</tr>
<?
	if(strpos($security_allowance, "M") !== false){
?>
			<tr>
				<td align="center" colspan="2"><input type="submit" name="submit" value="Upload Single Entry">
			</tr>
<?
	}
?>
		</table>
		</td>
		<td width="50%" valign="top">
		<table border="0" width="100%" cellpadding="4" cellspacing="0"> <!-- file-entry half of screen !-->
			<tr>
				<td align="center"><font size="3" face="Verdana"><b>Multiline File:</b> (The columns in the file need to match the columns in the sample which can be obtained by clicking above)</font></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana"><b>Origin:</b></font>&nbsp;&nbsp;<select name="origin">
							<option value="CHILE">CHILE</option>
							<option value="MOROCCO">MOROCCO</option>
							<option value="ARGENTINA">ARGENTINA</option>
				</select></td>
			</tr>
			<tr>
				<td><input type="file" name="upload_file"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Note:  please use the template file link above to get a copy of a "blank" upload form.</font></td>
			</tr>
<?
	if(strpos($security_allowance, "M") !== false){
?>
			<tr>
				<td><input type="submit" name="submit" value="Upload File Entry">
			</tr>
<?
	}
?>
		</table>
		</td>
	</tr>
</table>


<?
	include("pow_footer.php");






function filter_input($input){
	$return = $input;
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);
	$return = trim($return);
	$return = strtoupper($return);

	return $return;
}

//function VerifyInput($arraymax, $submit, $LR, $cust, $comm, $cont, $bol, $broker, $plts, $cases, $mode, $load, $weight, $weight_unit, $EDI_entry, $rfconn){
//function VerifyInput($arraymax, $submit, $EDI_entry, $LR, $Lloyd, $Voyage, $Vesname, $Shipline, $bol, $cust, $cont, $plts, $cases, $comm, $weight, $weight_unit, $broker, $mode, $load, $Amendtype, $Amendcode, $Action, $rfconn){
function VerifyInput($arraymax, $submit, $EDI_entry, $LR, $Lloyd, $Voyage, $Vesname, $Shipline, $bol, $cust, $custname, $cont, $plts, $cases, $comm, $commname, $weight, $weight_unit, $broker, $mode, $load, $Amendtype, $Amendcode, $Destination, $Action, $rfconn){
	$return = "";

	if($arraymax < 1){
		// hard-error.  cancel immediately.
		return "The Uploaded file was empty.";
	}

//	if($LR[0] != "ARRIVAL" || $cust[0] != "CUSTOMER" || $comm[0] != "COMMODITY" || $cont[0] != "CONTAINER" || $bol[0] != "BOL" || $broker[0] != "BROKER" || $plts[0] != "PLTS" || $cases[0] != "CASES" || 
//		$mode[0] != "CARGO MODE" || $load[0] != "LOAD TYPE" || $weight[0] != "WEIGHT" || $weight_unit[0] != "WEIGHT UNIT" || $EDI_entry[0] != "EDI ENTRY"){
	if($EDI_entry[0] != "EDI ID#" || $LR[0] != "MATCHED ARRIVAL #" || $Lloyd[0] != "LLOYD#" || $Voyage[0] != "VOYAGE#" || $Vesname[0] != "VESSEL NAME" || $Shipline[0] != "SHIP LINE" || $bol[0] != "BOL" || $cust[0] != "CONSIGNEE CODE" || $cont[0] != "CONTAINER#" || $plts[0] != "PALLET COUNT" || $cases[0] != "CASE COUNT" || $comm[0] != "COMMODITY CODE" || $weight[0] != "WEIGHT" || $weight_unit[0] != "WT UNIT" || $broker[0] != "BROKER" || $load[0] != "LOAD TYPE" || $mode[0] != "MODE" || $Amendtype[0] != "AMEND TYPE" || $Amendcode[0] != "AMEND CODE" || $Destination[0] != "DESTINATION" || $Action[0] != "ACTION"){ 
	// check the "header" row
		// hard-error.  cancel immediately.
		echo $EDI_entry[0]."<br>".$LR[0]."<br>".$Lloyd[0]."<br>".$Voyage[0]."<br>".$Vesname[0]."<br>".$Shipline[0]."<br>".$cust[0]."<br>".$comm[0]."<br>".$cont[0]."<br>";
		echo $bol[0]."<br>".$broker[0]."<br>".$plts[0]."<br>".$cases[0]."<br>".$mode[0]."<br>".$load[0]."<br>".$weight[0]."<br>".$weight_unit[0]."<br>".$Destination[0]."<br>";
		return "The Uploaded file was not in the proper column order.";
	}


	// for each row...
	for($i = 1; $i <= $arraymax; $i++){

		// is this an existing EDI?
		if($EDI_entry[$i] != ""){
			$sql = "SELECT TO_CHAR(PUSH_TO_CLR_ON, 'MM/DD/YYYY HH24:MI') THE_PUSH
					FROM CLR_AMSEDI_DETAIL_309
					WHERE KEY_ID || '-' || ENTRY_NUM = '".$EDI_entry[$i]."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$return .= "Line ".$i.": An EDI-line number was given, but no corresponding line was found in the Pending EDI table.<br>";
			} elseif(ociresult($short_term_data, "THE_PUSH") != ""){
				$return .= "Line ".$i.": The given EDI entry number has already been moved to the CLR main page.<br>";
			}
		}

		// LR check
		if($LR[$i] == ""){
			$return .= "Line ".$i.": was missing it's Arrival# value.<br>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM VESSEL_PROFILE
					WHERE TO_CHAR(LR_NUM) = '".$LR[$i]."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0){
				$return .= "Line ".$i.": Arrival Number not found in Vessel Profile.<br>";
			}
		}

		// LLoyds check
		if($Lloyd[$i] == ""){
			$return .= "Line ".$i.": was missing it's Lloyd's#.<br>";
		}

		// Voyage# check
		if($Voyage[$i] == ""){
			$return .= "Line ".$i.": was missing it's Voyage#.<br>";
		}

		// Vesname check
		if($Vesname[$i] == ""){
			$return .= "Line ".$i.": was missing it's Vessel Name.<br>";
		}

		// shipline check
		if($Shipline[$i] == ""){
			$return .= "Line ".$i.": was missing it's Shipping Line.<br>";
		}

		// BoL check
		if($bol[$i] == ""){
			$return .= "Line ".$i.": was missing it's BoL.<br>";
		}

		// customer check
		if($cust[$i] == ""){
			$return .= "Line ".$i.": was missing it's Customer# value.<br>";
		} elseif(!is_numeric($cust[$i])){
			$return .= "Line ".$i.": Customer# must be numeric.<br>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CUSTOMER_PROFILE
					WHERE CUSTOMER_ID = '".$cust[$i]."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0){
				$return .= "Line ".$i.": Customer Number not found in Customer Profile.<br>";
			}
		}

		// customer name check
		if($custname[$i] == ""){
			$return .= "Line ".$i.": was missing it's Customer Name.<br>";
		}


		// Container check
		if($cont[$i] == "NA"){
			$return .= "Line ".$i.": Container cannot be NA (if there is no container, just leave this column blank).<br>";
		}

		// Pallets check
		if($plts[$i] == ""){
			$return .= "Line ".$i.": was missing it's Pallet Count.<br>";
		} elseif(!is_numeric($plts[$i])) {
			$return .= "Line ".$i.": Pallet count must be numeric.<br>";
		} elseif($plts[$i] != round($plts[$i]) || $plts[$i] < 0) {
			$return .= "Line ".$i.": Pallet count must be a positive, whole number.<br>";
		}

		// Cases check
		if($cases[$i] == ""){
			$return .= "Line ".$i.": was missing it's Case Count.<br>";
		} elseif(!is_numeric($cases[$i])) {
			$return .= "Line ".$i.": Case count must be numeric.<br>";
		} elseif($cases[$i] != round($cases[$i]) || $cases[$i] < 0) {
			$return .= "Line ".$i.": Case count must be a positive, whole number.<br>";
		}


		// commodity check
		if($comm[$i] == ""){
			$return .= "Line ".$i.": was missing it's Commodity value.<br>";
		} elseif(!is_numeric($comm[$i])){
			$return .= "Line ".$i.": Commodity must be numeric.<br>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM COMMODITY_PROFILE
					WHERE COMMODITY_CODE = '".$comm[$i]."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0){
				$return .= "Line ".$i.": Commodity Number not found in Commodity Profile.<br>";
			}
		}

		// commodity name check
		if($commname[$i] == ""){
			$return .= "Line ".$i.": was missing it's Commodity Name.<br>";
		}


		// Weight check
		// required.  Do not remove...?
		if($weight[$i] == ""){
			// only do the check if a value is entered
			if(!is_numeric($weight[$i])) {
				$return .= "Line ".$i.": Weight must be numeric.<br>";
			} elseif($weight[$i] != weight($cases[$i]) || $weight[$i] < 0) {
				$return .= "Line ".$i.": Weight must be a positive, whole number.<br>";
			}
		}

		// a weight unit is needed if weight wasn't null
		if($weight_unit[$i] == "" && $weight[$i] != ""){
			$return .= "Line ".$i.": was missing it's Weight Unit.<br>";
		}


		// no Broker check

		// Mode check
		if($mode[$i] == ""){
			$return .= "Line ".$i.": was missing it's Mode (Column U).<br>";
		} elseif($mode[$i] != "HTH" && $mode[$i] != "SWING" && $mode[$i] != "STRIP" && $mode[$i] != "FUME" && $mode[$i] != "PREFUME" && $mode[$i] != "PREINSPD" && $mode[$i] != "HOLD" && $mode[$i] != "UNKNOWN" && $mode[$i] != "BREAKBULK"){
			$return .= "Line ".$i.": Mode (".$mode[$i].") was not an acceptable value.<br>";
		}

		// Load Type check
		if($load[$i] != "" && ($load[$i] != "FCL" && $load[$i] != "FCL/LCL" && $load[$i] != "FCL/FCL" && $load[$i] != "LCL/LCL" && $load[$i] != "BBULK" && $load[$i] != "LCL" && $load[$i] != "HOLD")){
//			$return .= "Line ".$i.": was missing it's Load Type.<br>";
//		} elseif($load[$i] != "FCL" && $load[$i] != "LCL"){
			$return .= "Line ".$i.": Load Type (".$load[$i].") was not an acceptable value (FCL, LCL, FCL/LCL, FCL/FCL, LCL/LCL, BBULK, HOLD, or left blank).<br>";
		}

		// Action check
		if($EDI_entry[$i] != "" && ($Action[$i] != "A" && $Action[$i] != "D")){
			$return .= "Line ".$i.": Action (".$Action[$i].") must either be A (for Add to Main Board) or D (For Discard from Pending) for any line with an EDI ID# value.<br>";
		}

		// Destination check
		if($Destination[$i] != "CANADIAN" && $Destination[$i] != "DOMESTIC"){
			$return .= "Line ".$i.": Destination must either be CANADIAN or DOMESTIC.<br>";
		}


		// by-column checks done.  check the whole value now, if we haven't hit an error yet.
//						AND COMMODITY_CODE = '".$comm[$i]."'";
		if($return == ""){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CLR_MAIN_DATA
					WHERE ARRIVAL_NUM = '".$LR[$i]."'
						AND CUSTOMER_ID = '".$cust[$i]."'
						AND BOL_EQUIV = '".$bol[$i]."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") >= 1){
				$return .= "Line ".$i.": the Arrival/Customer/BoL combination was already found in CLR.<br>";
			}
		}

	}

	if($submit == "Upload Single Entry"){
		$return = str_replace("Line 1", "Single Entry", $return);
	}

	return $return;
}

function GetCustName($cust, $rfconn){
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	
	return ociresult($short_term_data, "CUSTOMER_NAME");
}

function GetCommName($comm, $rfconn){
	$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$comm."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	
	return ociresult($short_term_data, "COMMODITY_NAME");
}

function GetVesName($LR, $rfconn){
	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".$LR."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	
	return ociresult($short_term_data, "VESSEL_NAME");
}
