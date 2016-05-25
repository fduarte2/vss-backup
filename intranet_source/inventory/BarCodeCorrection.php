<?
/*
*  Adam Walter, July 2008.
*
*	GENERIC BARCODE UPDATE PROGRAM
*	
*	This script will take either a single barcode, or a file of
*	Barcodes, and change the existing barcodes in the RF
*	DB accordingly.
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Argen Juice Barcode Correction";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

	$cust = $HTTP_POST_VARS['cust'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$cont = $HTTP_POST_VARS['cont'];
	$ves_type = $HTTP_POST_VARS['ves_type'];
	$oldBC = $HTTP_POST_VARS['oldBC'];
	$newBC = trim($HTTP_POST_VARS['newBC']);
	$counter = 0;
	
	if($submit != ""){
		if($cust == "" || $vessel == "" || ($ves_type == "cont" && $cont == "")){ // bad data
			echo "<font color=\"#ff0000\" size=\"2\">Please enter the information at the top of the page.</font><br>";

		}elseif($oldBC != "" && $newBC != ""){ // using single-entry
			$counter++;
			
			if(validate_barcode($oldBC, $newBC, $counter) == true){ // entered data is good to go
				update_barcode($oldBC, $newBC);
				echo "<font color=\"#0000ff\" size=\"2\">Barcode Changed.</font><br>";
			}else {} // if not valid, do NOT update.

		}elseif($HTTP_POST_FILES['upload_file']['name'] != ""){ // using file-entry
			$changes = 0;
			$target_path = "./" . basename($HTTP_POST_FILES['upload_file']['name']);
			if(!move_uploaded_file($HTTP_POST_FILES['upload_file']['tmp_name'], $target_path)){ // copying file to server didn't work
				echo "<font color=\"#ff0000\" size=\"2\">File Upload Failed.  Please contact TS.</font><br>";
			
			}else{ // start loop to write from 
				$temp = array();
				$handle = fopen($target_path, "r");
				while(!feof($handle)){
					$counter++;
					$line = fgets($handle);
					$temp = split(",", $line);
					if($line != "" && validate_barcode($temp[0], trim($temp[1]), $counter) == true){ // entered data is good to go
						update_barcode($temp[0], trim($temp[1]));
						$changes++;
					}else {} // if not valid, do NOT update
				}
				fclose($handle);
				unlink($target_path);
				echo "<font color=\"#0000ff\" size=\"2\">$changes Barcodes Changed.</font><br>";
			}

		}else{ // no data was entered, but submit was hit
			echo "<font color=\"#ff0000\" size=\"2\">Please enter Barcode information.</font><br>";
		}
	}





?>
<script language ="javascript">
function changeArrival()
{
	if (document.the_upload.ves_type.value == "vessel"){
		document.the_upload.vessel.disabled = false;
//		document.the_upload.vessel.bgcolor = "#FFFFFF";
		document.the_upload.cont.disabled = true;
		document.the_upload.cont.value = "";
//		document.the_upload.cont.bgcolor = "#F0F0F0";
	} else {
//		document.the_upload.vessel.disabled = true;
//		document.the_upload.vessel.value = "";
//		document.the_upload.vessel.bgcolor = "#F0F0F0"
		document.the_upload.cont.disabled = false;
//		document.the_upload.cont.bgcolor = "#FFFFFF";
	}
}
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barcode Corrections
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form enctype="multipart/form-data" action="BarCodeCorrection.php" method="post" name="the_upload">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2">&nbsp;</td>
		<td align="right"><font size="2" face="Verdana">Arrival number type:</font>
										<select name="ves_type" onchange="changeArrival()">
																<option value="vessel">Vessel</option>
																<option value="cont">Container/Railcar</option>
										</select></td>
	</tr>
	<tr>
		<td width="33%" align="left"><font size="2" face="Verdana">Customer:</font><input name="cust" type="text" size="10" color="#F0F0F0"></td>
		<td width="33%" align="center"><font size="2" face="Verdana">Vessel:</font><input name="vessel" type="text" size="20"></td>
		<td width="33%" align="right"><font size="2" face="Verdana">Container:</font><input name="cont" type="text" size="20" disabled></td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="50%">
		<table border="0" width="100%" cellpadding="4" cellspacing="0"> <!-- single-barcode half of screen !-->
			<tr>
				<td><font size="2" face="Verdana"><b>Single Barcode:</b></font></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Old Barcode:</font><input type="text" name="oldBC" size="40" maxlength="40"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">New Barcode:</font><input type="text" name="newBC" size="40" maxlength="40"></td>
			</tr>
		</table>
		</td>
		<td width="50%">
		<table border="0" width="100%" cellpadding="4" cellspacing="0"> <!-- file-barcode half of screen !-->
			<tr>
				<td><font size="2" face="Verdana"><b>Barcode File:</b></font></td>
			</tr>
			<tr>
				<td><input type="file" name="upload_file"></td>
			</tr>
			<tr>
				<td><font size="2" face="Verdana">Note:  File must be a 2-column *.csv file.  Old barcodes in column 1, new ones in column 2.</font></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">

	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Upload New Barcode(s)">
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="1" face="Verdana">Note:  attempting to use both sides of the page at once will result in only the single-barcode entry being accepted.</font></td>
	</tr>
</table>
</form>

<?
include("pow_footer.php");

function validate_barcode($oldBarcode, $newBarcode, $lineNumber){

	global $cust, $cont, $vessel, $ves_type, $conn, $cursor;

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$oldBarcode."' AND RECEIVER_ID = '".$cust."' ";
	$sql .= "AND ARRIVAL_NUM = '".$vessel."'";
	if($ves_type == "vessel"){
		// do nothing
	}else{
		$sql .= "AND CONTAINER_ID = '".$cont."'";
	}
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($row['THE_COUNT'] == 0){
		echo "<font color=\"#ff0000\" size=\"2\">Line number ".$lineNumber.": no pre-existing barcode matches input to change.</font><br>";
		return false;
	}

	if($row['THE_COUNT'] > 1){
		echo "<font color=\"#ff0000\" size=\"2\">Line number ".$lineNumber.": multiple entries for barcode already in system.  Please contact TS</font><br>";
		return false;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$oldBarcode."' AND RECEIVER_ID = '".$cust."' ";
	$sql .= "AND ARRIVAL_NUM = '".$vessel."' ";
	if($ves_type == "vessel"){
		// do nothing
	}else{
		$sql .= "AND CONTAINER_ID = '".$cont."' ";
	}
	$sql .= "AND DATE_RECEIVED IS NOT NULL";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($row['THE_COUNT'] > 0){
		echo "<font color=\"#ff0000\" size=\"2\">Line number ".$lineNumber.": Pallet already Scanned as Received.  Cannot change.</font><br>";
		return false;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$newBarcode."' AND RECEIVER_ID = '".$cust."' ";
	$sql .= "AND ARRIVAL_NUM = '".$vessel."' ";
	if($ves_type == "vessel"){
		// do nothing
	}else{
		$sql .= "AND CONTAINER_ID = '".$cont."' ";
	}
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($row['THE_COUNT'] > 0){
		echo "<font color=\"#ff0000\" size=\"2\">Line number ".$lineNumber.": Requested New Barcode already taken by Customer for this Vessel.</font><br>";
		return false;
	}

	return true;
}

function update_barcode($oldBarcode, $newBarcode){

	global $cust, $cont, $vessel, $ves_type, $conn, $cursor;

	$sql = "UPDATE CARGO_TRACKING SET PALLET_ID = '".$newBarcode."' WHERE PALLET_ID = '".$oldBarcode."' AND RECEIVER_ID = '".$cust."' ";
	$sql .= "AND ARRIVAL_NUM = '".$vessel."' ";
	if($ves_type == "vessel"){
		// do nothing
	}else{
		$sql .= "AND CONTAINER_ID = '".$cont."' ";
	}
	ora_parse($cursor, $sql);
	ora_exec($cursor);
}
?>