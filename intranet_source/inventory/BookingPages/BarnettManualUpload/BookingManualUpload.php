<?
  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Booking EDI (manual override) upload";
  $area_type = "INVE";
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("TCONSTANT@RFTEST", "TCONSTANT");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	ora_commitoff($conn);
	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];

	$XLS_rowcounter = 0; // dual-purpose variable, both for total rows updated, or error row if broken.

	if($submit == "Import Manual-Override EDI" && $HTTP_POST_FILES['update_file']['name'] != ""){
		$filename = basename($HTTP_POST_FILES['update_file']['name']);
		$target_path = "./uploads/". $filename.date('mdy');

		if(!move_uploaded_file($HTTP_POST_FILES['update_file']['tmp_name'], $target_path)){
			echo "Error during file upload; please contact TS";
			exit;
		}

		$file = fopen($target_path, "r");
		while(!feof($file)){
			$line = fgets($file);
			if(trim($line) != ""){ // make sure this isnt the blank line at the end of the file
				$XLS_rowcounter++;

				$data = split(",", strtoupper($line));

				if(thorough_check($data, $XLS_rowcounter, $conn, $code)){
					process_line($data, $XLS_rowcounter, $conn, $code, $user);
				}
			}
		}

		ora_commit($conn);
		fclose($file);
		if($XLS_rowcounter != 0){
			echo "<b>Rolls updated successfully.  ".$XLS_rowcounter." Pallets updated.</b><br>";
		} else {
			echo "<font color=\"#FF0000\" size=\"2\">File contained no usable rows.  No update run.</font>";
		}
	}
	
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking paper Manual-EDI upload
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" action="BookingManualUpload.php" method="post" name="the_upload">
	<tr>
		<td colspan="2">File:&nbsp;&nbsp;&nbsp;<input type="file" name="update_file"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Import Manual-Override EDI"></td>
	</tr>
</form>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="2"><a href="ImportCSVInstructionsforBookingPaper.doc" target="ImportCSVInstructionsforBookingPaper.doc">Import File Instructions</a></td>
	</tr>
</table>
<?
	include("pow_footer.php");
?>
<?
function error_out($message, $rowcount, $ora_conn) {
	echo "<font color=\"#FF0000\" size=\"2\">The uploaded was cancelled, due to the following problem:<br>".$message."<br>On line <b>".$rowcount.".  </b><br>Please review and re-enter.  Use the link below to return to the previous screen.</font>"; 
    ora_rollback($ora_conn);
    ora_logoff($ora_conn);
	?>  <br><br><a href="BookingManualUpload.php">Refresh Page</a> <?
    exit;
}

function database_check($ora_conn, $ora_success, $args) {
  if (!$ora_success) {
    echo "<font color=\"#FF0000\" size=\"2\">Error on ".$args."; one or more fields cannot be read by database.  Please correct and re-import file, or contact TS for further details.</font>";
    ora_rollback($ora_conn);
    ora_logoff($ora_conn);
	?>  <br><br><a href="BookingManualUpload.php">Refresh Page</a> <?
    exit;
  }
}

function thorough_check($data, $rowcount, $conn, &$code){
	// this function, which is in fact much larger than the program itself, takes every line of data and checks each field for consistency.
	// the only thing I can't have this function do is parse the input for bad characters; mainly, commas, since this is a CSV file.
	// these checks are being done in a specific order, so if it looks like a bad value might creep into a check, be sure to
	// look at the checks above it.

	// variable listing: 

				$cust = trim($data[0]);
				$PO = trim($data[1]);
//				$junk = trim($data[2]); // empty
				$BOL = trim($data[3]); 
				$LR = trim($data[4]);
				$width = trim($data[5]);
				$width_unit = trim($data[6]);
				$weight = trim($data[7]); 
				$weight_unit = trim($data[8]);
//				$basis_weight = trim($data[9]);
//				$junk = trim($data[10]); // basis weight unit, not used
				$barcode = trim($data[11]);
				$comm = trim($data[12]);
				$linear = trim($data[13]);
				$linear_meas = trim($data[14]);
				$SSCC_code = trim($data[15]);
				$dia = trim($data[16]);
				$dia_unit = trim($data[17]);

	for($i = 0; $i < sizeof($data); $i++){
		$data[$i] = trim($data[$i]);
	}

	// no real reason for 2 cursors, except that I'm copying swaths of code wholesale, and don't feel like aligning them
	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);


	
	// is this roll already in the DB?
	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE REMARK = 'BOOKINGSYSTEM' AND PALLET_ID = '".$barcode."' AND ARRIVAL_NUM = '".$LR."' AND RECEIVER_ID = '".$cust."'";
	$ora_success = @ora_parse($short_term_cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
	$ora_success = @ora_exec($short_term_cursor);
	database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] > 0){
		error_out("Roll already in system.", $rowcount, $conn);
	} 

	// Commodity Code for a given line not in COMMODITY_PROFILE in rf
	$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$comm."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Commodity Code not in our system", $rowcount, $conn);
	}

	// no customer
	if($cust == ""){
		error_out("Customer is blank", $rowcount, $conn);
	}

	// no order
	if($PO == ""){
		error_out("P.O./Sales Order is blank", $rowcount, $conn);
	}

	// no BoL
	if($BOL == ""){
		error_out("BoL is blank", $rowcount, $conn);
	}

	// no LR#
	if($LR == ""){
		error_out("Arrival # is blank", $rowcount, $conn);
	} elseif(!ereg("^([A-Z0-9-])+$", $LR)){
		error_out("Arrival # can only be letters, numbers, and dashes.", $rowcount, $conn);
	}

	// no weight
	if($weight == ""){
		error_out("Weight is blank", $rowcount, $conn);
	}
	// no weight unit
	if($weight_unit == ""){
		error_out("Weight Unit is blank", $rowcount, $conn);
	}
/*
	// no basis weight
	if($basis_weight == ""){
		error_out("Basis Weight is blank", $rowcount, $conn);
	}
*/
	// no barcode
	if($barcode == ""){
		error_out("Barcode is blank", $rowcount, $conn);
	}

	// no linear
	if($linear == ""){
		error_out("Linear value is blank", $rowcount, $conn);
	}
	// no linear meas
	if($linear_meas == ""){
		error_out("Linear Measurement is blank", $rowcount, $conn);
	}

	// invalid SSCC
	$sql = "SELECT * FROM BOOKING_PAPER_GRADE_CODE WHERE SSCC_GRADE_CODE = '".$SSCC_code."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		echo $sql."<br>";
		error_out("SSCC Grade code not in our system", $rowcount, $conn);
	}

	// no width
	if($width == ""){
		error_out("Width is blank", $rowcount, $conn);
	}

	// no width meas
	if($width_unit == ""){
		error_out("Width Measurement is blank", $rowcount, $conn);
	}

	// no diameter
	if($dia == ""){
		error_out("Diameter is blank", $rowcount, $conn);
	}

	// no diameter meas
	if($dia_unit == ""){
		error_out("Diameter Measurement is blank", $rowcount, $conn);
	}


	// Customer Code for a given line not in CUSTOMER_PROFILE in rf
	$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Customer not in our system", $rowcount, $conn);
	}

	return true;
}

function process_line($data, $rowcount, $conn, $code, $user){
// adds the DB line.  This function only gets called if it has already passed the requisite checks.

	// variable listing: 
				$cust = trim($data[0]);
				$PO = trim($data[1]);
//				$junk = trim($data[2]); // empty
				$BOL = trim($data[3]); 
				$LR = trim($data[4]);
				$width = trim($data[5]);
				$width_unit = trim($data[6]);
				$weight = trim($data[7]); 
				$weight_unit = trim($data[8]);
//				$basis_weight = trim($data[9]);
//				$junk = trim($data[10]); // basis weight unit, not used
				$barcode = trim($data[11]);
				$comm = trim($data[12]);
				$linear = trim($data[13]);
				$linear_meas = trim($data[14]);
				$SSCC_code = trim($data[15]);
				$dia = trim($data[16]);
				$dia_unit = trim($data[17]);

	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	$cargo_desc = $data[1]." ".$code." ".$data[3];
/*
	$sql = "SELECT MAX(BOL) THE_MAX FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$max_dockticket = $row['THE_MAX'];
*/
	$sql = "SELECT PRODUCT_CODE FROM BOOKING_PAPER_GRADE_CODE WHERE SSCC_GRADE_CODE = '".$SSCC_code."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$prod_code = $row['PRODUCT_CODE'];

	$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT, QTY_IN_HOUSE, FROM_SHIPPING_LINE, SHIPPING_LINE, PALLET_ID, ARRIVAL_NUM, RECEIVING_TYPE, WEIGHT, WEIGHT_UNIT, SOURCE_NOTE, SOURCE_USER, REMARK) VALUES ('1299', '1', '".$cust."', '1', '1', '', '', '".$barcode."', '".$LR."', 'T', '".$weight."', '".$weight_unit."', 'Manual Upload', '".$user."', 'BOOKINGSYSTEM')";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

/*
SHIPFROMMILL = '".$ship_from_mill."', 
*/

	$sql = "UPDATE BOOKING_ADDITIONAL_DATA SET BOL = '".$BOL."', ORDER_NUM = '".$PO."', PRODUCT_CODE = '".$prod_code."', DIAMETER = '".$dia."', DIAMETER_MEAS = '".$dia_unit."', WIDTH = '".$width."', WIDTH_MEAS = '".$width_unit."', LENGTH = '".$linear."', LENGTH_MEAS = '".$linear_meas."' WHERE PALLET_ID = '".$barcode."' AND ARRIVAL_NUM = '".$LR."' AND RECEIVER_ID = '".$cust."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
}