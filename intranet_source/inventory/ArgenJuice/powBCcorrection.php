<?
/*
*  Adam Walter, July 2007.
*
*  This program takes as input a .csv file and a ship number, and
*  Goes through the RF database's Cargo Tracking table, updating
*  Any entries for argentine juice that were forced to have a 
*  "POW" sticker placed on them; and then remvoe the original record.
------------------------------------------------------------------------
*
*	REVEISION, Nov 2010.
*
*	Inventory has asked to b able to fix *any* barcode; as such, I am removing
*	The POW-restriction in the checks.  This will allow them to update any juice
*	BC
*
*
*  File format for the *.csv is, at least at the time I am writing
*  this, is given as a hyperlink help file on the bottom of this page.
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
  ora_commitoff($conn);

  $LR_num = $HTTP_POST_VARS['LR_num'];
  $submit = $HTTP_POST_VARS['submit'];
  $XLS_rowcounter = 0; // dual-purpose variable, both for total rows updated, or error row if broken.

	if($LR_num != ""){
		$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '".$LR_num."'";
		$ora_success = @ora_parse($cursor, $sql);
		database_check($conn, $ora_success, "Row: ".$rowcount);
		$ora_success = @ora_exec($cursor);
		database_check($conn, $ora_success, "Row: ".$rowcount);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			error_out("Entered Vessel (".$LR_num.") Not used for RF cargo", 'N/A', $conn);
		}
	}
	
	if($submit == "Update Vessel" && $HTTP_POST_FILES['update_file']['name'] != "" && $LR_num != ""){
		$filename = basename($HTTP_POST_FILES['update_file']['name']);
		$target_path = "./uploads/". $filename;

		if(!move_uploaded_file($HTTP_POST_FILES['update_file']['tmp_name'], $target_path)){
			echo "Error during file upload; please contact TS";
			exit;
		}

		$file = fopen($target_path, "r");
		while(!feof($file)){
			$line = fgets($file);
			if(trim($line) != ""){ // make sure this isnt the blank line at the end of the file
				$XLS_rowcounter++;

				$data = split(",", $line);

				$newBC = trim($data[0]);
				$oldBC = trim($data[1]);
				$comm = trim($data[2]);
//				$unit = trim($data[3]); // DB field cannot support values longer than 2
				$unit = substr(trim($data[3]), 0, 1);
				$BoL = trim($data[4]);
//				$junk = trim($data[5]); // not used
				$mark = trim($data[6]);
//				$junk = trim($data[7]); // not used
				$qty = trim($data[8]);
				$hatch = trim($data[9]);
				$cust = trim($data[10]);
				$WH_loc = trim($data[11]);
				$batch = trim($data[12]);

				thorough_check($data, $XLS_rowcounter, $LR_num, $conn, $cursor);

		
				$sql = "UPDATE CARGO_TRACKING SET COMMODITY_CODE = '".$comm."', CARGO_DESCRIPTION = '".$mark."', QTY_RECEIVED = '".$qty."', RECEIVER_ID = '".$cust."', QTY_UNIT = '".$unit."', WAREHOUSE_LOCATION = '".$WH_loc."', BATCH_ID = '".$batch."', MANIFESTED = 'Y', BOL = '".$BoL."', HATCH = '".$hatch."' WHERE PALLET_ID = '".$newBC."' AND ARRIVAL_NUM = '".$LR_num."'";
				$ora_success = @ora_parse($cursor, $sql);
				database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
				$ora_success = @ora_exec($cursor);
				database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);

				$sql = "DELETE FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$LR_num."' AND PALLET_ID = '".$oldBC."'";
				$ora_success = @ora_parse($cursor, $sql);
				database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
				$ora_success = @ora_exec($cursor);
				database_check($conn, $ora_success, "Row: ".$XLS_rowcounter);
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
<!-- <a href="powBCcorrection.php">Refresh Page</a> !-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barcode Corrections (Argen Juice)
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" action="powBCcorrection.php" method="post" name="the_upload">
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana">LR#:</td>
		<td><input type="text" name="LR_num" size="10" maxlength="8">
<?
		if($submit == "Update Vessel" && $LR_num == ""){
			echo "<font color=\"#FF0000\" size=\"2\">Please enter a Vessel</font>";
		}
?>			</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana">File:</td>
		<td><input type="file" name="update_file"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Update Vessel"></td>
	</tr>
</form>
	<tr>
		<td colspan="3">&nbsp;<br>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="3"><a href="ImportCSVInstructions.doc" target="ImportCSVInstructions.doc">Import File Instructions</a></td>
	</tr>
</table>
<?
	include("pow_footer.php");

function database_check($ora_conn, $ora_success, $args) {
  if (!$ora_success) {
    echo "<font color=\"#FF0000\" size=\"2\">Error on ".$args."; one or more fields cannot be read by database.  Please correct and re-import file, or contact TS for further details.</font>";
    ora_rollback($ora_conn);
    ora_logoff($ora_conn);
	?>  <br><br><a href="powBCcorrection.php">Refresh Page</a> <?
    exit;
  }
}

function error_out($message, $rowcount, $ora_conn) {
	echo "<font color=\"#FF0000\" size=\"2\">The uploaded was cancelled, due to the following problem:<br>".$message."<br>On line <b>".$rowcount.".  </b><br>Please review and re-enter.  Use the link below to return to the previous screen.</font>"; 
    ora_rollback($ora_conn);
    ora_logoff($ora_conn);
	?>  <br><br><a href="powBCcorrection.php">Refresh Page</a> <?
    exit;
}

function thorough_check($data, $rowcount, $LR_num, $conn, $cursor){
	// this function, which is in fact much larger than the program itself, takes every line of data and checks each field for consistency.
	// the only thing I can't have this function do is parse the input for bad characters; mainly, commas, since this is a CSV file.
	// these checks are being done in a specific order, so if it looks like a bad value might creep into a check, be sure to
	// look at the checks above it.

	// variable listing: 
/*	$data[0] = New Barcode
	$data[1] = Old Barcode (or 'NEW')
	$data[2] = Commodity #
	$data[3] = Quantity unit (usually BIN)
	$data[4] = Bill of Lading
	$data[5] = Not used
	$data[6] = Cargo Mark
	$data[7] = Not used
	$data[8] = Quantity (usually 1)
	$data[9] = hatch
	$data[10] = Customer #
	$data[11] = Warehouse location
	$data[12] = warehouse code (AKA batch id to the RF table)
*/


	// new label doesn't start with "POW"
	// --- CHECK REMOVED.  Inventory wants to use this to fix *any* barcode.
/*
	if(substr($data[0], 0, 3) != "POW"){
		error_out("Invalid POW relabel value", $rowcount, $conn);
	}
*/
	// POW label value not in RF system
	$sql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '".trim($data[0])."' AND ARRIVAL_NUM = '".$LR_num."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("BC not used on any pallet for this vessel; cannot be updated", $rowcount, $conn);
	}

	// Old Barcode is neither valid nor "NEW", or has already been received
	if($data[1] == 'NEW'){
		// make sure it's still here
		$sql = "SELECT QTY_IN_HOUSE
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".trim($data[1])."' AND ARRIVAL_NUM = '".$LR_num."'";
		$ora_success = @ora_parse($cursor, $sql);
		database_check($conn, $ora_success, "Row: ".$rowcount);
		$ora_success = @ora_exec($cursor);
		database_check($conn, $ora_success, "Row: ".$rowcount);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['QTY_IN_HOUSE'] <= 0){
			error_out("Pallet no longer in house; cannot be updated", $rowcount, $conn);
		}
	} else {
		$sql = "SELECT * FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP 
				WHERE PALLET_ID = '".trim($data[1])."' AND ARRIVAL_NUM = '".$LR_num."'
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE";
		$ora_success = @ora_parse($cursor, $sql);
		database_check($conn, $ora_success, "Row: ".$rowcount);
		$ora_success = @ora_exec($cursor);
		database_check($conn, $ora_success, "Row: ".$rowcount);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			error_out("Original BC neither in manifest nor 'NEW'; cannot be updated", $rowcount, $conn);
		} else {
			if($row['DATE_RECEIVED'] != ""){
				error_out("Original Barcode already successfully received", $rowcount, $conn);
			} elseif($row['COMMODITY_TYPE'] != "ARG JUICE"){
				error_out("Cannot alter Non-Juice barcodes with this screen", $rowcount, $conn);
			}
		}
	}



	// Commodity Code for a given line not in COMMODITY_PROFILE in rf
	$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".trim($data[2])."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Commodity Code not in our system", $rowcount, $conn);
	} else {
		if($row['COMMODITY_TYPE'] != "ARG JUICE"){
			error_out("Cannot change a pallet to a Non-Juice commodity", $rowcount, $conn);
		}
	}

	// no quantity measurement
	if(trim($data[3]) == ""){
		error_out("Quantity Unit is blank", $rowcount, $conn);
	}

	// no BoL
	if(trim($data[4]) == ""){
		error_out("BoL is blank", $rowcount, $conn);
	}

	// no Mark
	if(trim($data[6]) == ""){
		error_out("Cargo Mark is blank", $rowcount, $conn);
	}

	// no Quantity
	if(trim($data[8]) == ""){
		error_out("Quantity is blank", $rowcount, $conn);
	}

	// no Hatch
	if(trim($data[9]) == ""){
		error_out("Hatch is blank", $rowcount, $conn);
	}

	// Customer Code for a given line not in CUSTOMER_PROFILE in rf
	$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".trim($data[10])."'";
	$ora_success = @ora_parse($cursor, $sql);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	$ora_success = @ora_exec($cursor);
	database_check($conn, $ora_success, "Row: ".$rowcount);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		error_out("Customer not in our system", $rowcount, $conn);
	}

	// no Warehouse location
	if(trim($data[11]) == ""){
		error_out("Warehouse Location is blank", $rowcount, $conn);
	}

	// no Warehouse code (AKA batch ID)
	if(trim($data[12]) == ""){
		error_out("Warehouse Code is blank", $rowcount, $conn);
	}
}

?>