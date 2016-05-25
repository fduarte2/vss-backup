<?
/*
*  Adam Walter, Dec 2014.
*
*	uploads xls File for a type of V2 bill
*	(termserv and transfer combined)
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Combo Billing";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}


	$submit = $HTTP_POST_VARS['submit'];
	$LR = $HTTP_POST_VARS['LR'];
//	$comm = $HTTP_POST_VARS['comm'];
	$date = $HTTP_POST_VARS['date'];
	if($submit == "Create Prebills"){
		$cust = $HTTP_POST_VARS['cust'];
		$comm = $HTTP_POST_VARS['comm'];
		$f_total = $HTTP_POST_VARS['f_total'];
		$xfer_total = $HTTP_POST_VARS['xfer_total'];

		$i = 2;
		$total_bills = 0;
		$billed_bills = 0;
		while(is_numeric($cust[$i])){
			$this_bill = CreatePrebill($LR, $comm[$i], $date, $cust[$i], $f_total[$i], $xfer_total[$i], $bniconn, $user);
			if($this_bill > 0){
				$total_bills += $this_bill;
				$billed_bills++;
			}

			$i++;
		}

		echo "<font color=\"#0000FF\">".$billed_bills." Bills Created for a total of $".number_format($total_bills, 2).".  Please go to the <a href=\"./print_prebill.php\">Print Preinvoice Page</a> to View.</font><br>";
	}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Terminal Services File Upload
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form enctype="multipart/form-data" action="ForPDI.php" method="post" name="the_upload">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Arrival#:</font></td>
		<td><select name="LR"><option value="">Select a Vessel</option>
<?
//				WHERE SHIP_PREFIX = 'CHILEAN'
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
			ORDER BY LR_NUM DESC";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if(ociresult($short_term_data, "LR_NUM") == $LR){?> selected <?}?>><? echo ociresult($short_term_data, "LR_NUM")." - ".ociresult($short_term_data, "VESSEL_NAME"); ?></option>
<?
	}
?>
		</select></td>
	</tr>
<!--	<tr>
		<td><font size="2" face="Verdana">Commodity#:</font></td>
		<td><select name="comm"><option value="">Select a Commodity</option>
<?
	$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
			FROM COMMODITY_PROFILE
			ORDER BY COMMODITY_CODE";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?>"<? if(ociresult($short_term_data, "COMMODITY_CODE") == "5101"){?> selected <?}?>>
							<? echo ociresult($short_term_data, "COMMODITY_NAME"); ?></option>
<?
	}
?>
		</select></td>
	</tr> !-->
	<tr>
		<td align="left"><font size="3" face="Verdana">Service Date:</td><td><input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>"></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">File:</td><td><input type="file" name="upload_file"></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Upload Excel">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">(File Format MUST be .xls)</font></td>
	</tr>
</form>
<?
	if($submit == "Upload Excel"){
		$impfilename = date(mdYhis).".".basename($HTTP_POST_FILES['upload_file']['name']);
		$target_path_import = "./uploaded_files/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['upload_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
			exit;
		}

		if($date == "" || $LR == "" || $impfilename == ""){
			echo "<font color=\"#FF0000\">Date, Ship, and File must all be chosen.  Cancelling Upload.</font><br>";
			exit;
		}

		include("reader.php");
		$data = new Spreadsheet_Excel_Reader();

		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
//			echo "cust-".$i."- ".filter_input($data->sheets[0]['cells'][$i][1])."<br>";
			$cust[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][1]);
//			echo "custname-".$i."- ".filter_input($data->sheets[0]['cells'][$i][2])."<br>";
			$custname[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][2]);
//			echo "comm-".$i."- ".filter_input($data->sheets[0]['cells'][$i][3])."<br>";
			$comm[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][3]);
//			echo "ftotal-".$i."- ".filter_input($data->sheets[0]['cells'][$i][4])."<br>";
			$f_total[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][4]);
//			echo "xfertotal-".$i."- ".filter_input($data->sheets[0]['cells'][$i][5])."<br>";
			$xfer_total[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][5]);
			if($f_total[($i - 1)] == ""){
				$f_total[($i - 1)] = "0";
			}
			if($xfer_total[($i - 1)] == ""){
				$xfer_total[($i - 1)] = "0";
			}
//			$xfer_dolewalmart[($i - 1)] = filter_input($data->sheets[0]['cells'][$i][5]);
//			if($xfer_dolewalmart[($i - 1)] == ""){
//				$xfer_dolewalmart[($i - 1)] = "0";
//			}
		}
		$arraymax = ($i - 2); // minus 1 for the fact that the XLS_SPREADSHEET_READER starts at 1 instead of zero, and 1 more because its a "for" loop, so $i will be one higher than the size at the end

		$error = ValidateData($LR, $comm, $date, $cust, $f_total, $xfer_total, ($arraymax -2), $bniconn); // subtract 2 from arraymax here because the last two "lines" in the file are totals
		//$xfer_dolewalmart, 

		if($error != ""){
			echo "<font color=\"#FF0000\">Could not accept file:<br>".$error."<br>Please Correct and Resubmit.</font><br>";
			$submit = "";
		} else {
/*			$i = 2; // the first line is a Header row
			while($i <= $arraymax){
				CreatePrebill($LR, $comm[$i], $date, $cust[$i], $f_total[$i], $xfer_total[$i], $bniconn, $user);
				//$xfer_dolewalmart[$i], 
				$i++;
			}

			echo "<font color=\"#0000FF\">Bills Uploaded.  Please go to the Print Preinvoice Page to View.</font><br>";*/
?>
<form name="make_bills" action="ForPDI.php" method="post">
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<input type="hidden" name="arraymax" value="<? echo $arraymax; ?>">
<input type="hidden" name="LR" value="<? echo $LR; ?>">
<input type="hidden" name="date" value="<? echo $date; ?>">
<!--	<tr>
		<td><font size="2" face="Verdana"><b>Rate:</b></font></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><? echo $f_total[1]; ?></td>
		<td><? echo $xfer_total[1]; ?></td>
	</tr> !-->
	<tr>
		<td><font size="2" face="Verdana"><b>Cust ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer Name</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Terminal Service Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Transfer Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Wharfage Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Security Pallets</b></font></td>
	</tr>
<?
			$total_custs = 0;
			$total_f_plts = 0;
			$total_xfer_plts = 0;
			for ($i = 2; $i <= $arraymax; $i++){
				if(is_numeric($cust[$i])){
					$total_custs++;
					$total_f_plts += $f_total[$i];
					$total_xfer_plts += $xfer_total[$i];

					GetRate($cust[$i], $LR, $comm[$i], "1", $testrate_f, $unit, $asset, $bni_serv, $bniconn);
					GetRate($cust[$i], $LR, $comm[$i], "2", $testrate_xfer, $unit, $asset, $bni_serv, $bniconn);

?>
	<tr>
		<td>
			<input type="hidden" name="cust[<? echo $i; ?>]" value="<? echo $cust[$i]; ?>">
			<input type="hidden" name="comm[<? echo $i; ?>]" value="<? echo $comm[$i]; ?>">
			<input type="hidden" name="f_total[<? echo $i; ?>]" value="<? echo $f_total[$i]; ?>">
			<input type="hidden" name="xfer_total[<? echo $i; ?>]" value="<? echo $xfer_total[$i]; ?>">

			<font size="2" face="Verdana"><? echo $cust[$i]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $custname[$i]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $comm[$i]; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $f_total[$i]; ?></font></td> <!--  (Rate: $<? echo $testrate_f; ?>) !-->
		<td><font size="2" face="Verdana"><? echo $xfer_total[$i]; ?></font></td> <!--  (Rate: $<? echo $testrate_xfer; ?>) !-->
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?
				}
			}
?>
	<tr><td colspan="7">&nbsp;</td></tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Totals:</b></font></td>
		<td><font size="2" face="Verdana"><? echo $total_custs; ?></font></td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $total_f_plts; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_xfer_plts; ?></font></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7"><input type="submit" name="submit" value="Create Prebills"></td>
	</tr>
</form>
</table>
<?
		}
	}

	include("pow_footer.php");




function filter_input($input){
	$return = $input;
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);
	$return = trim($return);
//	$return = strtoupper($return);

	return $return;
}


function ValidateData($LR, $comm, $date, $cust, $f_total, $xfer_total, $arraymax, $bniconn){
	//$xfer_dolewalmart, 
	$return = "";

	if($arraymax < 1){
		// hard-error.  cancel immediately.
		return "The Uploaded file was empty.";
	}
	if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $date)) {
		return "Date was not in MM/DD/YYYY format.";
	}


	// for each row...
	for($i = 3; $i <= $arraymax; $i++){

		// customer check
		if($cust[$i] == ""){
			$return .= "Line ".$i.": was missing it's Customer# value.<br>";
		} elseif(!is_numeric($cust[$i])){
			$return .= "Line ".$i.": Customer# must be numeric.<br>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CUSTOMER_PROFILE
					WHERE CUSTOMER_ID = '".$cust[$i]."'";
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0){
				$return .= "Line ".$i.": Customer Number not found in Customer Profile.<br>";
			}
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
			$short_term_data = ociparse($bniconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") <= 0){
				$return .= "Line ".$i.": Commodity not found in Commodity Profile.<br>";
			}
		}


		// F-Pallets check
		if($f_total[$i] == ""){
			$return .= "Line ".$i.": was missing it's Pallets-in-F.<br>";
		} elseif(!is_numeric($f_total[$i])) {
			$return .= "Line ".$i.": Pallets-in-F count must be numeric.<br>";
		} elseif($f_total[$i] != round($f_total[$i]) || $f_total[$i] < 0) {
			$return .= "Line ".$i.": Pallets-in-F count must be a positive, whole number.<br>";
		}

		// transfer check
		if($xfer_total[$i] == ""){
			$return .= "Line ".$i.": was missing it's Transferred Pallet Count.<br>";
		} elseif(!is_numeric($xfer_total[$i])) {
			$return .= "Line ".$i.": Transferred Pallet Count count must be numeric.<br>";
		} elseif($xfer_total[$i] != round($xfer_total[$i]) || $xfer_total[$i] < 0) {
			$return .= "Line ".$i.": Transferred Pallet Count count must be a positive, whole number.<br>";
		}
/*
		// transfer-dole check
		if($xfer_dolewalmart[$i] == ""){
			$return .= "Line ".$i.": was missing it's Dole/Walmart Xfer Count.<br>";
		} elseif(!is_numeric($xfer_dolewalmart[$i])) {
			$return .= "Line ".$i.": Dole/Walmart Xfer Count count must be numeric.<br>";
		} elseif($xfer_dolewalmart[$i] != round($xfer_dolewalmart[$i]) || $xfer_dolewalmart[$i] < 0) {
			$return .= "Line ".$i.": Dole/Walmart Xfer Count count must be a positive, whole number.<br>";
		}
*/
		// rate found?
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM NONSTORAGE_RATE
				WHERE (BILL_TYPE = '4PDI')
					AND (CUSTOMERID IS NULL OR CUSTOMERID = '".$cust[$i]."')
					AND (ARRIVAL_NUM IS NULL OR ARRIVAL_NUM = '".$LR."')
					AND (COMMODITYCODE IS NULL OR COMMODITYCODE = '".$comm[$i]."')
					AND SCANNEDORUNSCANNED = 'UNSCANNED'";
//		echo $sql."<br>";
		$rates = ociparse($bniconn, $sql);
		ociexecute($rates);
		ocifetch($rates);
		if(ociresult($rates, "THE_COUNT") <= 0){
			$return .= "Line ".$i.": Could not find a Rate for the Arrival/Customer/Commodity combination.<br>";
		}
	}

	return $return;
}



function CreatePrebill($LR, $comm, $date, $cust, $f_total, $xfer_total, $bniconn, $user){
	//$xfer_dolewalmart,
	$xfer_dolewalmart = 0;
	$total_total = 0;

	$sql = "SELECT NVL(MAX(BILLING_NUM), 301500000) + 1 THE_MAX
			FROM BILL_HEADER";
	$billnum = ociparse($bniconn, $sql);
	ociexecute($billnum);
	ocifetch($billnum);
	$billing_num = ociresult($billnum, "THE_MAX");

	$billing_det = 1;
/*
	$sql = "SELECT RATE, UNIT, ASSET_CODE, BNI_SERVICE_CODE
			FROM NONSTORAGE_RATE
			WHERE (BILL_TYPE = '4PDI')
				AND (CUSTOMERID IS NULL OR CUSTOMERID = '".$cust."')
				AND (ARRIVAL_NUM IS NULL OR ARRIVAL_NUM = '".$LR."')
				AND COMMODITYCODE = '".$comm."'
				AND SCANNEDORUNSCANNED = 'UNSCANNED'
				AND BILL_ORDER = '".$billing_det."'
			ORDER BY RATEPRIORITY";
	$rates = ociparse($bniconn, $sql);
	ociexecute($rates);
	ocifetch($rates);
	$rate = ociresult($rates, "RATE");
*/
	GetRate($cust, $LR, $comm, $billing_det, $rate, $unit, $asset, $bni_serv, $bniconn);


	if($rate > 0){
		if($f_total > 0){
			$desc_db = "TERMINAL SERVICE CHARGES:  ".$f_total." PALLET(S) @ $".number_format($rate, 2)."/".$unit;

			$amt = $f_total * $rate;
			$total_total += $amt;

			$sql = "INSERT INTO BILL_DETAIL
						(BILLING_NUM,
						DETAIL_LINE,
						SERVICE_AMOUNT,
						SERVICE_QTY,
						SERVICE_UNIT,
						SERVICE_RATE,
						SERVICE_RATE_UNIT,
						SERVICE_DESCRIPTION,
						SERVICE_CODE_DET,
						SERVICE_DATE_DETAIL,
						ASSET_CODE)
					VALUES
						('".$billing_num."',
						'".$billing_det."',
						'".$amt."',
						'".$f_total."',
						'".$unit."',
						'".$rate."',
						'".$unit."',
						'".$desc_db."',
						'".$bni_serv."',
						TO_DATE('".$date."', 'MM/DD/YYYY'),
						'".$asset."')";
			$insert_bill = ociparse($bniconn, $sql);
			ociexecute($insert_bill);
		}
	}

	$billing_det = 2;
/*
	$sql = "SELECT RATE, UNIT, ASSET_CODE, BNI_SERVICE_CODE
			FROM NONSTORAGE_RATE
			WHERE (BILL_TYPE = '4PDI')
				AND (CUSTOMERID IS NULL OR CUSTOMERID = '".$cust."')
				AND (ARRIVAL_NUM IS NULL OR ARRIVAL_NUM = '".$LR."')
				AND COMMODITYCODE = '".$comm."'
				AND SCANNEDORUNSCANNED = 'UNSCANNED'
				AND BILL_ORDER = '".$billing_det."'
			ORDER BY RATEPRIORITY";
	$rates = ociparse($bniconn, $sql);
	ociexecute($rates);
	ocifetch($rates);
	$rate = ociresult($rates, "RATE");
*/
	GetRate($cust, $LR, $comm, $billing_det, $rate, $unit, $asset, $bni_serv, $bniconn);

	if($rate > 0){
		if(($xfer_total + $xfer_dolewalmart) > 0){
			$billing_det = 2;
			$desc_db = "PALLET TRANSFER CHARGES:  ".($xfer_total + $xfer_dolewalmart)." PALLET(S) @ $".number_format($rate, 2)."/".$unit;
			$amt = ($xfer_total + $xfer_dolewalmart) * $rate;
			$total_total += $amt;

			$sql = "INSERT INTO BILL_DETAIL
						(BILLING_NUM,
						DETAIL_LINE,
						SERVICE_AMOUNT,
						SERVICE_QTY,
						SERVICE_UNIT,
						SERVICE_RATE,
						SERVICE_RATE_UNIT,
						SERVICE_DESCRIPTION,
						SERVICE_CODE_DET,
						SERVICE_DATE_DETAIL,
						ASSET_CODE)
					VALUES
						('".$billing_num."',
						'".$billing_det."',
						'".$amt."',
						'".($xfer_total + $xfer_dolewalmart)."',
						'".$unit."',
						'".$rate."',
						'".$unit."',
						'".$desc_db."',
						'".$bni_serv."',
						TO_DATE('".$date."', 'MM/DD/YYYY'),
						'".$asset."')";
			$insert_bill = ociparse($bniconn, $sql);
			ociexecute($insert_bill);
		}
	}

	if($total_total > 0){
		$sql = "INSERT INTO BILL_HEADER
					(CUSTOMER_ID,
					COMMODITY_CODE,
					SERVICE_CODE,
					BILLING_NUM,
					CREATED_BY,
					SERVICE_STATUS,
					ARRIVAL_NUM,
					SERVICE_DATE,
					BILLING_TYPE,
					BILLED_FROM_SYSTEM)
				VALUES
					('".$cust."',
					'".$comm."',
					'".$bni_serv."',
					'".$billing_num."',
					'".$user."',
					'PREINVOICE',
					'".$LR."',
					TO_DATE('".$date."', 'MM/DD/YYYY'),
					'4PDI',
					'BNI')";
		$insert_bill = ociparse($bniconn, $sql);
		ociexecute($insert_bill);
	}

	return $total_total;
}

function GetRate($cust, $LR, $comm, $billing_det, &$rate, &$unit, &$asset, &$bni_serv, $bniconn){
	$sql = "SELECT RATE, UNIT, ASSET_CODE, BNI_SERVICE_CODE
			FROM NONSTORAGE_RATE
			WHERE (BILL_TYPE = '4PDI')
				AND (CUSTOMERID IS NULL OR CUSTOMERID = '".$cust."')
				AND (ARRIVAL_NUM IS NULL OR ARRIVAL_NUM = '".$LR."')
				AND (COMMODITYCODE IS NULL OR COMMODITYCODE = '".$comm."')
				AND SCANNEDORUNSCANNED = 'UNSCANNED'
				AND BILL_ORDER = '".$billing_det."'
			ORDER BY RATEPRIORITY";
	$rates = ociparse($bniconn, $sql);
	ociexecute($rates);
	ocifetch($rates);
	$rate = ociresult($rates, "RATE");
	$unit = ociresult($rates, "UNIT");
	$asset = ociresult($rates, "ASSET_CODE");
	$bni_serv = ociresult($rates, "BNI_SERVICE_CODE");
}
