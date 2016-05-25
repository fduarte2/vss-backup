<?
  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Dole EDI (manual override) upload";
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
		$target_path = "./uploads/". $filename;

		if(!move_uploaded_file($HTTP_POST_FILES['update_file']['tmp_name'], $target_path)){
			echo "Error during file upload; please contact TS";
			exit;
		}

		if(substr(basename($HTTP_POST_FILES['update_file']['name']), -3) != "csv"){
			echo "File is not a CSV.";
			exit;
		}


		$file = fopen($target_path, "r");
		while(!feof($file)){
			$line = fgets($file);
			if(trim($line) != ""){ // make sure this isnt the blank line at the end of the file
				$XLS_rowcounter++;

				$data = split(",", $line);

				$cust = trim($data[0]);
				$PO = trim($data[1]);
//				$junk = trim($data[2]);
				$load = trim($data[3]); 
				$LR = trim($data[4]);
				$size = trim($data[5]);
				$weight = trim($data[6]); 
//				$junk = trim($data[7]);  // weight unit, not used
//				$junk = trim($data[8]); 
				$basis_weight = trim($data[9]);
//				$junk = trim($data[10]); // basis weight unit, not used
				$barcode = trim($data[11]);
				$comm = trim($data[12]);
				$linear_feet = trim($data[13]);

				if(thorough_check($data, $XLS_rowcounter, $conn, $code)){
					process_line($data, $XLS_rowcounter, $conn, $code, $user);
				}
			}
		}

		ora_commit($conn);
		fclose($file);
		if($XLS_rowcounter != 0){
			echo "<b>Vessel ".$LR_num." updated successfully.  ".$XLS_rowcounter." Pallets updated.</b><br>";
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
	    <font size="5" face="Verdana" color="#0066CC">Dole DT paper Manual-EDI upload
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" action="DoleDTupload.php" method="post" name="the_upload">
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
		<td align="center" colspan="2"><a href="ImportCSVInstructions.doc" target="ImportCSVInstructions.doc">Import File Instructions</a></td>
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
	?>  <br><br><a href="DoleDTupload.php">Refresh Page</a> <?
    exit;
}

function database_check($ora_conn, $ora_success, $args) {
  if (!$ora_success) {
    echo "<font color=\"#FF0000\" size=\"2\">Error on ".$args."; one or more fields cannot be read by database.  Please correct and re-import file, or contact TS for further details.</font>";
    ora_rollback($ora_conn);
    ora_logoff($ora_conn);
	?>  <br><br><a href="DoleDTupload.php">Refresh Page</a> <?
    exit;
  }
}

function thorough_check($data, $rowcount, $conn, &$code){
	// this function, which is in fact much larger than the program itself, takes every line of data and checks each field for consistency.
	// the only thing I can't have this function do is parse the input for bad characters; mainly, commas, since this is a CSV file.
	// these checks are being done in a specific order, so if it looks like a bad value might creep into a check, be sure to
	// look at the checks above it.

	// variable listing: 
/*	$data[0] = customer
	$data[1] = P.O.
	$data[2] = blank
	$data[3] = Load #
	$data[4] = ARV #
	$data[5] = size
	$data[6] = weight
	$data[7] = "LB"
	$data[8] = blank
	$data[9] = basis weight
	$data[10] = "LB"
	$data[11] = barcode
	$data[12] = comm
*/
	for($i = 0; $i < sizeof($data); $i++){
		$data[$i] = trim($data[$i]);
	}

	// no real reason for 2 cursors, except that I'm copying swaths of code wholesale, and don't feel like aligning them
	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);


	// make sure there is a valid paper code FIRST
	$sql = "SELECT PAPER_CODE FROM DOLEPAPER_EDI_IMPORT_CODES WHERE BASIS_WEIGHT = '".$data[9]."' AND PAPER_WIDTH = '".$data[5]."'";
	$ora_success = @ora_parse($short_term_cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
	$ora_success = @ora_exec($short_term_cursor);
	database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
	if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("No Paper code conversion available", $rowcount, $conn);
	} else {
		$code = $row['PAPER_CODE'];
	}

	// is this roll already in the DB?
	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' AND PALLET_ID = '".$data[11]."' AND BATCH_ID = '".$code."' AND RECEIVER_ID = '".$data[0]."' AND ARRIVAL_NUM = '".$data[4]."'";
	$ora_success = @ora_parse($short_term_cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
	$ora_success = @ora_exec($short_term_cursor);
	database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] > 0){
		error_out("Roll already in system.", $rowcount, $conn);
	} 

	// Commodity Code for a given line not in COMMODITY_PROFILE in rf
	$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$data[12]."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Commodity Code not in our system", $rowcount, $conn);
	}

	// no customer
	if(trim($data[0]) == ""){
		error_out("Customer is blank", $rowcount, $conn);
	}

	// no customer
	if(trim($data[1]) == ""){
		error_out("P.O. is blank", $rowcount, $conn);
	}

	// no Load #
	if(trim($data[3]) == ""){
		error_out("Load # is blank", $rowcount, $conn);
	}

	// no/bad LR#
	if(trim($data[4]) == ""){
		error_out("Arrival # is blank", $rowcount, $conn);
	} elseif(!ereg("^([A-Z0-9-])+$", $data[4])){
		error_out("Arrival # can only be letters, numbers, and dashes.", $rowcount, $conn);
	}

	// no Size
	if(trim($data[5]) == ""){
		error_out("Size is blank", $rowcount, $conn);
	}

	// no weight
	if(trim($data[6]) == ""){
		error_out("Weight is blank", $rowcount, $conn);
	}

	// no basis weight
	if(trim($data[9]) == ""){
		error_out("basis Weight is blank", $rowcount, $conn);
	}

	// no barcode
	if(trim($data[11]) == ""){
		error_out("Barcode is blank", $rowcount, $conn);
	}

	// no linear feet
	if(trim($data[13]) == ""){
		error_out("Linear Feet is blank", $rowcount, $conn);
	} elseif(!is_numeric(trim($data[13]))){
		error_out("Linear Feet was non-numeric (file has ".trim($data[13]).")", $rowcount, $conn);
	}


	// Customer Code for a given line not in CUSTOMER_PROFILE in rf
	$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$data[0]."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Customer not in our system", $rowcount, $conn);
	}

	// Customer Code for a given line not in CUSTOMER_PROFILE in rf
	$sql = "SELECT * FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS WHERE CUSTOMER_ID = '".$data[0]."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Customer not listed as a DOLEPAPER-DOCKTICKET customer.<br>Please call TS, and tell them to look at the DOLEPAPER_EDI_IMPORT_CUSTOMERS table.", $rowcount, $conn);
	}

	return true;
}

function process_line($data, $rowcount, $conn, $code, $user){
// adds the DB line.  This function only gets called if it has already passed the requisite checks.

	// variable listing: 
/*	$data[0] = customer
	$data[1] = P.O.
	$data[2] = blank
	$data[3] = Load #
	$data[4] = ARV #
	$data[5] = size
	$data[6] = weight
	$data[7] = "LB"
	$data[8] = blank
	$data[9] = basis weight
	$data[10] = "LB"
	$data[11] = barcode
	$data[12] = comm
	$data[13] = linear Feet
*/

	for($i = 0; $i < sizeof($data); $i++){
		$data[$i] = trim($data[$i]);
	}

	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

	$cargo_desc = $data[1]." ".$code." ".$data[3];

	$sql = "SELECT MAX(BOL) THE_MAX FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor);
	ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$max_dockticket = $row['THE_MAX'];

	$sql = "SELECT BOL FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION = '".$cargo_desc."'";
	$ora_success = ora_parse($short_term_cursor, $sql);
	$ora_success = ora_exec($short_term_cursor);
	if(!ora_fetch_into($short_term_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$dock_ticket = $max_dockticket + 1;
	} else {
		$dock_ticket = $row['BOL'];
	}

	$sql = "INSERT INTO CARGO_TRACKING (COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT, QTY_IN_HOUSE, BATCH_ID, PALLET_ID, ARRIVAL_NUM, CARGO_SIZE, MARK, WEIGHT, WEIGHT_UNIT, REMARK, RECEIVING_TYPE, BOL, VARIETY, SOURCE_NOTE, SOURCE_USER) VALUES ('1272', '".$cargo_desc."', '1', '".$data[0]."', '1', '1', '".$code."', '".$data[11]."', '".$data[4]."', '".$data[5]."', '".$data[9]."', '".$data[6]."', 'LB', 'DOLEPAPERSYSTEM', 'T', '".$dock_ticket."', '".$data[13]."', TO_CHAR(SYSDATE, 'MM/DD/YY HH24:MI') || ' Manual (EDI) Insert', '".$user."')";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
}