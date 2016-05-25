<?
/*  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance - RF Storage";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	REPLACE ABOVE LOGIC WITH EPORT EQUIVALENT
*/

	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}


	$user = $HTTP_COOKIE_VARS["eport_user"];
	$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
//	$user_cust_num = 1982; // FOR TESTING

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);

	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$ip_address = $HTTP_SERVER_VARS['REMOTE_ADDR'];

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$cur_ves."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$cur_ves_name = $Short_Term_Row['VESSEL_NAME'];

// in case they got trigger-happy...
if($HTTP_POST_FILES['import_file']['name'] == ""){
	echo "No file uploaded.  Please use your browser's back button to return to the previous page.";
	exit;
}


// step 1:  get a new TRANSID
//	$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM CHILEAN_PLT_CHANGES_HEADER";
	$sql = "SELECT CHILEAN_FILE_UPLOAD_SEQ.NEXTVAL THE_SEQ FROM dual";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$trans_id = $Short_Term_Row['THE_SEQ'];


// step 2:  get all data from file
// 2a)  get file
	$impfilename = "cust".$user_cust_num."on".date(mdYhis)."file".basename($HTTP_POST_FILES['import_file']['name']);
	$target_path_import = "./uploadedfiles/".$impfilename;

	if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
		system("/bin/chmod a+r $target_path_import");
	} else {
		echo "Error on file upload.  Please contact Port of Wilmington";
		exit;
	}

	include("reader.php");
	$data = new Spreadsheet_Excel_Reader();
	$useable_data = array();

// 2b)  populate data array
	$data->read($target_path_import);
	error_reporting(E_ALL ^ E_NOTICE);

	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
/*		echo $data->sheets[0]['cells'][$i][1].",";
		echo "1299,"; echo "NS472386,";
		echo $data->sheets[0]['cells'][$i][2];
		echo "-"; 		
		echo $data->sheets[0]['cells'][$i][3]; 		
		echo ",312,";
		echo $data->sheets[0]['cells'][$i][6].",";
		echo $data->sheets[0]['cells'][$i][9].",";
		echo $data->sheets[0]['cells'][$i][9].",";
		echo $data->sheets[0]['cells'][$i][10].",";
		echo $data->sheets[0]['cells'][$i][11].",";
		echo $data->sheets[0]['cells'][$i][12].",";
		echo "GRUPO NACION S.A.";
		echo nl2br("\n");
		echo "\n"; echo "\n"; echo "\n";
*/
		$useable_data[($i - 1)]["cust"] = trim($data->sheets[0]['cells'][$i][1]);
		$useable_data[($i - 1)]["pallet"] = trim($data->sheets[0]['cells'][$i][2]);
		$useable_data[($i - 1)]["comm"] = trim($data->sheets[0]['cells'][$i][3]);
		$useable_data[($i - 1)]["variety"] = trim($data->sheets[0]['cells'][$i][4]);
		$useable_data[($i - 1)]["label"] = trim($data->sheets[0]['cells'][$i][5]);
		$useable_data[($i - 1)]["cargo_size"] = trim($data->sheets[0]['cells'][$i][6]);
		$useable_data[($i - 1)]["hatch"] = trim($data->sheets[0]['cells'][$i][7]);
		$useable_data[($i - 1)]["qty_received"] = trim($data->sheets[0]['cells'][$i][8]);
		$useable_data[($i - 1)]["warehouse_location"] = trim($data->sheets[0]['cells'][$i][9]);
		$useable_data[($i - 1)]["grower"] = trim($data->sheets[0]['cells'][$i][10]);
		$useable_data[($i - 1)]["package"] = trim($data->sheets[0]['cells'][$i][11]);
/*
		echo $useable_data[($i - 1)]["cust"]."<br>";
		echo $useable_data[($i - 1)]["pallet"]."<br>";
		echo $useable_data[($i - 1)]["comm"]."<br>";
		echo $useable_data[($i - 1)]["variety"]."<br>";
		echo $useable_data[($i - 1)]["label"]."<br>";
		echo $useable_data[($i - 1)]["cargo_size"]."<br>";
		echo $useable_data[($i - 1)]["hatch"]."<br>";
		echo $useable_data[($i - 1)]["qty_received"]."<br>";
		echo $useable_data[($i - 1)]["warehouse_location"]."<br>";
		echo $useable_data[($i - 1)]["grower"]."<br>";
		echo $useable_data[($i - 1)]["package"]."<br>";
*/
	}

// step 3:  have data, delete existing (if someone backpaged), validate + insert to DB
	$sql = "DELETE FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	$show_page = validate_and_save_data($useable_data, $trans_id, $user_cust_num, $cur_ves, $conn, $user, $ip, $impfilename);

	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'MATCH'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$display_count_good = $Short_Term_Row['THE_COUNT'];
	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'NOTINDB'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$display_count_noDB = $Short_Term_Row['THE_COUNT'];
	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'NOTINFILE'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$display_count_noFILE = $Short_Term_Row['THE_COUNT'];
	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'TOBETRANS'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$display_count_tobetrans = $Short_Term_Row['THE_COUNT'];
	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$cur_ves."' AND RECEIVER_ID = '".$user_cust_num."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$total_vessel = $Short_Term_Row['THE_COUNT'];
/*
	$sql = "INSERT INTO CHILEAN_PLT_CHANGES_HEADER
				(TRANSACTION_ID,
				TRANSACTION_DATETIME,
				USER_ID,
				SOURCE_IP,
				FILE_NAME,
				TOTAL_ROWS,
				GOOD_ROWS,
				ROWS_NOT_IN_DB,
				ROWS_NOT_IN_FILE,
				COMMITTED)
			VALUES
				('".$trans_id."',
				SYSDATE,
				'".$user."',
				'".$ip_address."',
				'".$HTTP_POST_FILES['import_file']['name']."',
				'".($display_count_good + $display_count_noDB + $display_count_noFILE)."',
				'".$display_count_good."',
				'".$display_count_noDB."',
				'".$display_count_noFILE."',
				'N')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
*/

				


// step 4:  start making page...
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Review Upload Summary (Step 2 of 3)
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	// break page is file was bad
	if($show_page != ""){
		echo "An error with the uploaded file was detected:<br>".$show_page."<br>Please correct the file, and use your browser's back button to return to the previous page for re-submission";
		exit;
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3"><font size="2" face="Verdana">Your file has been processed.  Your transaction number is </font><font size="2" face="Verdana" color="#FF0000"><? echo $trans_id; ?></font><font size="2" face="Verdana"><br>Please refer to this number if you need to contact PoW for any reason.<br><br></font><font size="3" face="Verdana"><b>The results of your upload are:</b><br></font></td>
	</tr>
	<tr>
		<td colspan="3"><font size="3" face="Verdana">Vessel:   <? echo $cur_ves." - ".$cur_ves_name; ?></font></td>
	</tr>
	<tr>
		<td colspan="3"><font size="3" face="Verdana"><? echo ($display_count_good + $display_count_noDB + $display_count_tobetrans); ?> Pallets in File:</font></td>
	</tr>
<form name="confirm_form" action="confirm_changes.php" method="post">
<input type="hidden" name="view_cust" value="<? echo $view_cust; ?>">
<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="trans_id" value="<? echo $trans_id; ?>">
	<tr>
		<td><font size="3" face="Verdana"><? echo $display_count_good; ?>  Pallet(s) matched (click "Commit" to finalize the transaction).</font></td>
		<td><font size="3" face="Verdana"><a href="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=MATCH&cust=<? echo $user_cust_num; ?>" target="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=MATCH&cust=<? echo $user_cust_num; ?>">Review</a></font></td>
		<td><input type="submit" name="submit" value="Commit (Step 3 of 3)"></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><? echo $display_count_tobetrans; ?>  Pallet(s) requested, and available to be transferred to you.  Please note:  Actual transfer will not happen until "Commit" is selected.</font></td>
		<td colspan="2"><font size="3" face="Verdana"><a href="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=TOBETRANS&cust=<? echo $user_cust_num; ?>" target="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=TOBETRANS&cust=<? echo $user_cust_num; ?>">Review</a></font></td>
<!--		<td><input type="submit" name="submit" value="Commit (Step 3 of 3)"></td> !-->
	</tr>
</form>
	<tr>
		<td><font size="3" face="Verdana"><? echo $display_count_noDB; ?>  Pallet(s) did not match any available in our system.</font><? if($display_count_noDB > 0){?><font size="3" face="Verdana" color="#FF0000">  Contact Shipping Line to resolve.</font><? } ?></td>
		<td colspan="2"><font size="3" face="Verdana"><a href="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=NOTINDB&cust=<? echo $user_cust_num; ?>" target="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=NOTINDB&cust=<? echo $user_cust_num; ?>">Review</a></font></td>
	</tr>
	<tr>
		<td colspan="3"><br><br></td>
	</tr>
	<tr>
		<td colspan="3"><font size="3" face="Verdana"><b><? echo $total_vessel; ?>  Pallet(s) expected for you on this vessel</b></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">After this upload, <? echo $display_count_noFILE; ?> pallet(s) will still need sort information.</font></td>
		<td colspan="2"><font size="3" face="Verdana"><a href="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=NOTINFILE&cust=<? echo $user_cust_num; ?>" target="match_pallets.php?trans_id=<? echo $trans_id; ?>&type=NOTINFILE&cust=<? echo $user_cust_num; ?>">Review</a></font></td>
	</tr>
</table>
<?
	//include("pow_footer.php");
?>






<? 
function validate_and_save_data($useable_data, $trans_id, $user_cust_num, $cur_ves, $conn, $user, $ip, $impfilename){
	// if file has errors, said errors are returned as a text string.  else, a null string is returned.

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);
/*
		$useable_data[($i - 1)]["cust"] = $data->sheets[0]['cells'][$i][1];
		$useable_data[($i - 1)]["pallet"] = $data->sheets[0]['cells'][$i][2];
		$useable_data[($i - 1)]["comm"] = $data->sheets[0]['cells'][$i][3];
		$useable_data[($i - 1)]["variety"] = $data->sheets[0]['cells'][$i][4];
		$useable_data[($i - 1)]["label"] = $data->sheets[0]['cells'][$i][5];
		$useable_data[($i - 1)]["cargo_size"] = $data->sheets[0]['cells'][$i][6];
		$useable_data[($i - 1)]["hatch"] = $data->sheets[0]['cells'][$i][7];
		$useable_data[($i - 1)]["qty_received"] = $data->sheets[0]['cells'][$i][8];
		$useable_data[($i - 1)]["warehouse_location"] = $data->sheets[0]['cells'][$i][9];
		$useable_data[($i - 1)]["grower"] = $data->sheets[0]['cells'][$i][10];
		$useable_data[($i - 1)]["package"] = $data->sheets[0]['cells'][$i][11];
*/

// step 1:  test the first line
	if($useable_data[0]["cust"] != "IMP"
		|| $useable_data[0]["pallet"] != "PLT_ID"
		|| $useable_data[0]["comm"] != "Commodity"
		|| $useable_data[0]["variety"] != "Variety"
		|| $useable_data[0]["label"] != "Label"
		|| $useable_data[0]["cargo_size"] != "Size"
		|| $useable_data[0]["hatch"] != "Hatch"
		|| $useable_data[0]["qty_received"] != "Qty"
		|| $useable_data[0]["warehouse_location"] != "Loc"
		|| $useable_data[0]["grower"] != "Grower"
		|| $useable_data[0]["package"] != "Package"){
//		echo $useable_data[0]["cust"]." ".$useable_data[0]["pallet"]." ".$useable_data[0]["comm"]." ".$useable_data[0]["variety"]." ".$useable_data[0]["label"]." ".$useable_data[0]["cargo_size"]." ".$useable_data[0]["hatch"]." ".$useable_data[0]["qty_received"]." ".$useable_data[0]["warehouse_location"]." ".$useable_data[0]["grower"]." ".$useable_data[0]["package"]."<BR> ";
		return "File order does not match to expected format; please reference the Example on the previous page.";
	}

// step 2, for each line...
	$i = 1;
	while($useable_data[$i]["cust"] != ""){
		$badline = $i + 1;  // since XLS spreadsheet line #s don't start with Zero.

// 2A)  validate pallet id
		if(strlen($useable_data[$i]["pallet"]) > 32){
			return "Line: ".$badline." - PoW pallet numbers cannot be longer than 32 characters";
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE PALLET_ID = '".$useable_data[$i]["pallet"]."' AND TRANSACTION_ID = '".$trans_id."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$count = $Short_Term_Row['THE_COUNT'];
		if($count > 0){
			return "Line: ".$badline." - Pallet # duplicate detected in file";
		}

// 2B)  validate size
		if(strlen($useable_data[$i]["cargo_size"]) > 6){
			return "Line: ".$badline." - Size field cannot be longer than 6 characters";
		}

// 2C)  validate hatch
		if(strlen($useable_data[$i]["hatch"]) > 5){
			return "Line: ".$badline." - Hatch field cannot be longer than 5 characters";
		}

/*		if(!is_numeric(substr($useable_data[$i]["hatch"], 0, 1))){
			return "Line: ".$badline." - Hatch field must be numbers before letters";
		}
*/
// 2D)  validate QTY_RECEIVED
		if(strlen($useable_data[$i]["qty_received"]) > 6){
			return "Line: ".$badline." - Qty field cannot be longer than 6 characters";
		}

		if(!is_numeric($useable_data[$i]["qty_received"])){
			return "Line: ".$badline." - Qty field must be numeric";
		}
// 2E)  validate LOCATION
		if(strlen($useable_data[$i]["warehouse_location"]) > 12){
			return "Line: ".$badline." - Loc field cannot be longer than 12 characters";
		}

// 2F)  validate PACKAGE
		if(strlen($useable_data[$i]["package"]) > 10){
			return "Line: ".$badline." - Package field cannot be longer than 10 characters";
		}

// 2G)  check whole line for bad characters
		$linecheck = $useable_data[$i]["cust"].$useable_data[$i]["pallet"].$useable_data[$i]["comm"].$useable_data[$i]["variety"].$useable_data[$i]["label"].$useable_data[$i]["cargo_size"].$useable_data[$i]["hatch"].$useable_data[$i]["qty_received"].$useable_data[$i]["warehouse_location"].$useable_data[$i]["grower"].$useable_data[$i]["package"];
		$linecheck = str_replace(" ", "", trim($linecheck));
		if(!ereg("^[A-Za-z0-9:-]+$", $linecheck)){
//			echo $linecheck."<br>";
			return "Line: ".$badline." - Uploads only accept -, :, spaces, or alphanumeric characters";
		}

// step 3)  place in DB

// 3 PRE-STEP:
// Added, Adam Walter, Nov 2010.
// As it stands, Carle and the distributors may no longer use the same barcode to reference the same pallet...
// ... I know, right?
// so I need to perform a conversion check to see if what is uploaded is what someone else THINKS this actually is...
		$pallet_to_match = check_and_maybe_convert($useable_data[$i]["pallet"], $cur_ves);

// 3A)  does it match?
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$pallet_to_match."' AND ARRIVAL_NUM = '".$cur_ves."' AND RECEIVER_ID = '".$user_cust_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$count = $Short_Term_Row['THE_COUNT'];
		
		if($count > 0){
			// perfect match
			$comparison = 'MATCH';
		} else {
			// pallet not a match, determine what else it could be.
			// AND CHILEAN_SORT_FILE_UPLOAD IS NULL";
			// does it exist under another customer #?
			$sql = "SELECT CHILEAN_SORT_FILE_UPLOAD, MANIFESTED_RECEIVER_ID, CT.RECEIVER_ID 
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = '".$pallet_to_match."'
					AND CT.ARRIVAL_NUM = '".$cur_ves."'
					AND CT.RECEIVER_ID != '".$user_cust_num."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// this pallet simply doesn't exist.
				$comparison = 'NOTINDB';
			} elseif($Short_Term_Row['RECEIVER_ID'] == 3000) {
				// WARNING:  attempting to claim walmart pallet!  DO NOT WANT!
				$comparison = 'NOTINDB';
			} else {
				// this pallet exists, but is listed under a different cust#.
				// as of yet, has anyone else claimed it?
				if($Short_Term_Row['CHILEAN_SORT_FILE_UPLOAD'] == ""){
					// not yet.  Give it to the new guy
					$comparison = "TOBETRANS";
				} else {
					// someone else already took it.  is the latest claimant the original owner?
					if($user_cust_num == $Short_Term_Row['MANIFESTED_RECEIVER_ID']){
						// give it back to the original guy
						$comparison = "TOBETRANS";
					} else {
						// this is the 2nd or greater guy trying to take this who isn't the original.  Not happening.
						$comparison = 'NOTINDB';
					}
				}
			}
/*			$unclaimed_count = $Short_Term_Row['THE_COUNT'];

			if($unclaimed_count >= 1){
				// pallet not yet taken by original owner, save original owner data
				// (DB triger will handle the customer conversion in C.T.A.D.)
				$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA SET MANIFESTED_RECEIVER_ID = RECEIVER_ID WHERE ARRIVAL_NUM = '".$cur_ves."' AND PALLET_ID = '".$useable_data[$i]["pallet"]."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "UPDATE CARGO_TRACKING SET RECEIVER_ID = '".$user_cust_num."' WHERE ARRIVAL_NUM = '".$cur_ves."' AND PALLET_ID = '".$useable_data[$i]["pallet"]."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$comparison = 'MATCH';
			} else {
				$comparison = 'NOTINDB';
			} */
		}

// 3B)  The actual write
		$sql = "INSERT INTO CHILEAN_CUSTOMER_PLT_CHANGES
				(PALLET_ID,
				COMMODITY_CODE,
				VARIETY,
				LABEL,
				CARGO_SIZE,
				HATCH,
				QTY,
				WAREHOUSE_LOCATION,
				GROWER,
				PACKAGE,
				TRANSACTION_ID,
				PALLET_TO_DB_COMPARE,
				CT_JOIN_PALLET_ID)
				VALUES
				('".$useable_data[$i]["pallet"]."',
				'".$useable_data[$i]["comm"]."',
				'".$useable_data[$i]["variety"]."',
				'".$useable_data[$i]["label"]."',
				'".$useable_data[$i]["cargo_size"]."',
				'".strtoupper($useable_data[$i]["hatch"])."',
				'".$useable_data[$i]["qty_received"]."',
				'".strtoupper($useable_data[$i]["warehouse_location"])."',
				'".$useable_data[$i]["grower"]."',
				'".$useable_data[$i]["package"]."',
				'".$trans_id."',
				'".$comparison."',
				'".$pallet_to_match."')";
//		echo $sql."<br>";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		// next record...
		$i++;

	}

// step 4:  insert all pallets that this customer owns that were not in file OR not previously updated

	$sql = "INSERT INTO CHILEAN_CUSTOMER_PLT_CHANGES
				(PALLET_ID,
				COMMODITY_CODE,
				VARIETY,
				LABEL,
				CARGO_SIZE,
				HATCH,
				QTY,
				WAREHOUSE_LOCATION,
				GROWER,
				PACKAGE,
				TRANSACTION_ID,
				PALLET_TO_DB_COMPARE)
			SELECT
				CT.PALLET_ID,
				COMMODITY_NAME,
				VARIETY,
				REMARK,
				CARGO_SIZE,
				HATCH,
				QTY_RECEIVED,
				WAREHOUSE_LOCATION,
				CARGO_DESCRIPTION,
				BATCH_ID,
				'".$trans_id."',
				'NOTINFILE'
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '".$user_cust_num."'
				AND CT.ARRIVAL_NUM = '".$cur_ves."'
				AND CTAD.CHILEAN_SORT_FILE_UPLOAD IS NULL
				AND CT.PALLET_ID NOT IN
					(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."')";
//	echo $sql."<br>";
	ora_parse($cursor, $sql);
	ora_exec($cursor);

// step 5:  insert "Header" info
	$sql = "INSERT INTO CHILEAN_PLT_CHANGES_HEADER
				(TRANSACTION_ID,
				USER_ID,
				CUSTOMER_ID,
				SOURCE_IP,
				FILENAME,
				ARRIVAL_NUM,
				EMAIL_SENT)
			VALUES
				('".$trans_id."',
				'".$user."',
				'".$user_cust_num."',
				'".$ip."',
				'".$impfilename."',
				'".$cur_ves."',
				'N')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);




				

	return "";
}



function check_and_maybe_convert($pallet_id, $vessel){
	global $conn;
	$funccursor = ora_open($conn);

	$return = $pallet_id;

	// if it passes this check, it's an exact match, and there is no reason to continue.
	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '".$pallet_id."' AND ARRIVAL_NUM = '".$vessel."'";
	ora_parse($funccursor, $sql);
	ora_exec($funccursor);
	ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] >= 1){
		return $return;
	}

	// well, not an exact match, lets try a 20-down conversion
	// if it matches 20-down, return the 20.
	if(strlen($pallet_id) == 20){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 5, 6) = '".substr($pallet_id, 13, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
		ora_parse($funccursor, $sql);
		ora_exec($funccursor);
		ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] == 1){
			$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 5, 6) = '".substr($pallet_id, 13, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($funccursor, $sql);
			ora_exec($funccursor);
			ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			return $row['PALLET_ID'];
		}
	}

	// how about the 18-down conversion?
	// if it matches 18-down, return the 18.
	if(strlen($pallet_id) == 18){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 5, 6) = '".substr($pallet_id, 11, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
		ora_parse($funccursor, $sql);
		ora_exec($funccursor);
		ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] == 1){
			$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 5, 6) = '".substr($pallet_id, 11, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($funccursor, $sql);
			ora_exec($funccursor);
			ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			return $row['PALLET_ID'];
		}
	}

	// that didn't work, try a 10-up conversion
	// if it matches just either none, return the 10, and the program will error out due to lack of match.
	// if it matches one AND ONLY ONE, return the one it matches (either 18 or 20).
	// if it matches both 18 AND 20, return the 10, so that it errors out in teh calling code with no match.
	$match_to_18 = false;
	$match_to_20 = false;

	if(strlen($pallet_id) == 10){
		// 20
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 14, 6) = '".substr($pallet_id, 4, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
		ora_parse($funccursor, $sql);
		ora_exec($funccursor);
		ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] == 1){
			$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 14, 6) = '".substr($pallet_id, 4, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($funccursor, $sql);
			ora_exec($funccursor);
			ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$match_to_20 = $row['PALLET_ID'];
		}

		//18
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 12, 6) = '".substr($pallet_id, 4, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
		ora_parse($funccursor, $sql);
		ora_exec($funccursor);
		ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] == 1){
			$sql = "SELECT PALLET_ID FROM CARGO_TRACKING WHERE SUBSTR(PALLET_ID, 12, 6) = '".substr($pallet_id, 4, 6)."' AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($funccursor, $sql);
			ora_exec($funccursor);
			ora_fetch_into($funccursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$match_to_18 = $row['PALLET_ID'];
		}

		if($match_to_18 && $match_to_20){
			return $pallet_id;
		}elseif(!$match_to_18 && !$match_to_20){
			return $pallet_id;
		}elseif(!$match_to_18 && $match_to_20){
			return $match_to_20;
		}elseif($match_to_18 && !$match_to_20){
			return $match_to_18;
		}
	}

	// if no conversions work or are applicable, pass barcode back as is, and let code handle the notification.

	return $pallet_id;
}