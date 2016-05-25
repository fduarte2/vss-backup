<?
/*
*	Adam Walter, Jun 2011.
*
*	This page transfers paper rolls into the Port's account (1)
*
*	April 2016:  and now, out of it.  --AW
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");  echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");   echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$modify_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);


	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Transfer To Port"){
		$barcode = $HTTP_POST_VARS['barcode'];
		$cust = $HTTP_POST_VARS['cust'];
		$arrival_num = $HTTP_POST_VARS['arv'];

		$sql = "UPDATE CARGO_ACTIVITY
				SET CUSTOMER_ID = 1
				WHERE PALLET_ID = '".$barcode."'
					AND CUSTOMER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$arrival_num."'";
		ora_parse($modify_cursor, $sql);
		ora_exec($modify_cursor);

		$sql = "UPDATE CARGO_TRACKING
				SET RECEIVER_ID = 1
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$arrival_num."'";
		ora_parse($modify_cursor, $sql);
		ora_exec($modify_cursor);

		$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX
				FROM CARGO_ACTIVITY
				WHERE PALLET_ID = '".$barcode."'
					AND CUSTOMER_ID = '1'
					AND ARRIVAL_NUM = '".$arrival_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$act_num = $Short_Term_Row['THE_MAX'] + 1;

		$sql = "INSERT INTO CARGO_ACTIVITY
					(ACTIVITY_NUM,
					SERVICE_CODE,
					QTY_CHANGE,
					ACTIVITY_ID,
					ACTIVITY_DESCRIPTION,
					CUSTOMER_ID,
					DATE_OF_ACTIVITY,
					PALLET_ID,
					ARRIVAL_NUM,
					QTY_LEFT)
				VALUES
					('".$act_num."',
					'15',
					'1',
					'3',
					'".$cust."',
					'1',
					SYSDATE,
					'".$barcode."',
					'".$arrival_num."',
					'1')";
		ora_parse($modify_cursor, $sql);
		ora_exec($modify_cursor);

		echo "<font color=\"0000FF\">Pallet ".$barcode." Transferred to Port.</font><br>";
		$submit = "";
	}

/*
	if($submit == "Upload File" && $HTTP_POST_FILES['update_file']['name'] != ""){
		$filename = basename($HTTP_POST_FILES['update_file']['name']);
		$target_path = "./uploads/". $filename;
		$XLS_rowcounter = 0;
		$Barcodes = array();
		$LRs = array();
		$custs = array();

		if(!move_uploaded_file($HTTP_POST_FILES['update_file']['tmp_name'], $target_path)){
			echo "Error during file upload; please contact TS";
			exit;
		}

		$file = fopen($target_path, "r");
		while(!feof($file)){
			$line = fgets($file);
			if(trim($line) != ""){ // make sure this isnt the blank line at the end of the file
				$data = split(",", $line);

				if(trim($data[0]) != ""){
					$Barcodes[$XLS_rowcounter] = trim($data[0]);
					$LRs[$XLS_rowcounter] = trim($data[1]);
					$custs[$XLS_rowcounter] = trim($data[2]);
				}

				$XLS_rowcounter++;
			}
		}
		fclose($file);

		
		$response = Validate_File($Barcodes, $LRs, $custs, $XLS_rowcounter, $conn);
		if($response != ""){
			echo "<font color=\"#FF0000\">File could not be accepted:<br>".$response."<br>Please fix and re-upload.</font>";
			$submit = "";
		}
	}
	
	if($submit == "Transfer Cargo"){
		$Barcodes = $HTTP_POST_VARS['Barcodes'];
		$LRs = $HTTP_POST_VARS['LRs'];
		$custs = $HTTP_POST_VARS['custs'];
		$XLS_rowcounter = $HTTP_POST_VARS['XLS_rowcounter'];

		for($i = 1; $i < $XLS_rowcounter; $i++){
			$sql = "UPDATE CARGO_ACTIVITY
					SET CUSTOMER_ID = 1
					WHERE PALLET_ID = '".$Barcodes[$i]."'
						AND CUSTOMER_ID = '".$custs[$i]."'
						AND ARRIVAL_NUM = '".$LRs[$i]."'";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

			$sql = "UPDATE CARGO_TRACKING
					SET RECEIVER_ID = 1
					WHERE PALLET_ID = '".$Barcodes[$i]."'
						AND RECEIVER_ID = '".$custs[$i]."'
						AND ARRIVAL_NUM = '".$LRs[$i]."'";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

			$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX
					FROM CARGO_ACTIVITY
					WHERE PALLET_ID = '".$Barcodes[$i]."'
						AND CUSTOMER_ID = '1'
						AND ARRIVAL_NUM = '".$LRs[$i]."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$act_num = $Short_Term_Row['THE_MAX'] + 1;

			$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM,
						SERVICE_CODE,
						QTY_CHANGE,
						ACTIVITY_ID,
						ACTIVITY_DESCRIPTION,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
					VALUES
						('".$act_num."',
						'15',
						'1',
						'3',
						'".$custs[$i]."',
						'1',
						SYSDATE,
						'".$Barcodes[$i]."',
						'".$LRs[$i]."',
						'1')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
/*
			$sql = "INSERT INTO CARGO_TRACKING
						(COMMODITY_CODE, 
						CARGO_DESCRIPTION, 
						DATE_RECEIVED, 
						QTY_RECEIVED, 
						WAREHOUSE_LOCATION,
						RECEIVER_ID, 
						QTY_IN_HOUSE, 
						FUMIGATION_CODE, 
						EXPORTER_CODE, 
						PALLET_ID, 
						ARRIVAL_NUM, 
						RECEIVING_TYPE, 
						BATCH_ID,
						MANIFESTED, 
						BOL, 
						DECK, 
						HATCH, 
						CARGO_TYPE_ID, 
						SOURCE_NOTE)
					(SELECT
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						SYSDATE,
						'1',
						WAREHOUSE_LOCATION,
						'1', 
						'1', 
						FUMIGATION_CODE, 
						EXPORTER_CODE, 
						PALLET_ID, 
						ARRIVAL_NUM, 
						RECEIVING_TYPE, 
						BATCH_ID,
						MANIFESTED, 
						BOL, 
						DECK, 
						HATCH, 
						CARGO_TYPE_ID,
						'Into Port Inventory'
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcodes[$i]."'
						AND RECEIVER_ID = '".$custs[$i]."'
						AND ARRIVAL_NUM = '".$LRs[$i]."')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

			$sql = "INSERT INTO CARGO_ACTIVITY
						(ACTIVITY_NUM,
						SERVICE_CODE,
						QTY_CHANGE,
						ACTIVITY_ID,
						CUSTOMER_ID,
						DATE_OF_ACTIVITY,
						PALLET_ID,
						ARRIVAL_NUM,
						QTY_LEFT)
					VALUES
						('1',
						'15',
						'1',
						'3',
						'1',
						SYSDATE,
						'".$Barcodes[$i]."',
						'".$LRs[$i]."',
						'1')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);

		}

		echo "<font color=\"#0000FF\"><b>".($XLS_rowcounter - 1)." Rolls moved to Port Account.</b></font><br>";

	}
*/
	if($submit == "Revert Cargo to Owner"){
		$barcode = $HTTP_POST_VARS['barcode'];
		$arv = $HTTP_POST_VARS['arv'];
		$revert = $HTTP_POST_VARS['revert'];
		$maxrows = $HTTP_POST_VARS['maxrows'];

		$returned = 0;

		for($i = 0; $i < $maxrows; $i++){
			if($revert[$i] == "change"){
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$barcode[$i]."'
							AND CUSTOMER_ID = '1'
							AND ARRIVAL_NUM = '".$arv[$i]."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$act_num = $Short_Term_Row['THE_MAX'] + 1;

				$sql = "SELECT ACTIVITY_DESCRIPTION
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$barcode[$i]."'
							AND CUSTOMER_ID = '1'
							AND ARRIVAL_NUM = '".$arv[$i]."'
							AND SERVICE_CODE = '15'
						ORDER BY ACTIVITY_NUM DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$orig_cust = $Short_Term_Row['ACTIVITY_DESCRIPTION'];

				$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							ACTIVITY_DESCRIPTION,
							CUSTOMER_ID,
							DATE_OF_ACTIVITY,
							PALLET_ID,
							ARRIVAL_NUM,
							QTY_LEFT)
						VALUES
							('".$act_num."',
							'15',
							'1',
							'3',
							'Back to ".$orig_cust."',
							'1',
							SYSDATE,
							'".$barcode[$i]."',
							'".$arv[$i]."',
							'1')";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor);

				$sql = "UPDATE CARGO_TRACKING
						SET RECEIVER_ID = ".$orig_cust."
						WHERE PALLET_ID = '".$barcode[$i]."'
							AND RECEIVER_ID = '1'
							AND ARRIVAL_NUM = '".$arv[$i]."'";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor);

				$returned++;
			}
		}

		echo "<font color=\"#0000FF\"><b>".$returned." Rolls have been given back to the customer.</b></font><br>";
		$barcode = "";
	}
?>
<!-- <a href="Paper_to_Port.php">Refresh Page</a> !-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Paper-To-Port-Account Transfer
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="select" action="Paper_to_Port.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Roll#:  </font></td>
		<td><input type="text" name="barcode" size="32" maxlength="32" value="<? echo $barcode; ?>"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Customer#:  </font></td>
		<td><input type="text" name="cust" size="6" maxlength="6" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Roll"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Roll"){
		$barcode = $HTTP_POST_VARS['barcode'];
		$cust = $HTTP_POST_VARS['cust'];

?>
<table border="0" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT ARRIVAL_NUM, QTY_IN_HOUSE 
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND DATE_RECEIVED IS NOT NULL
					AND QTY_IN_HOUSE > 0";
//		echo $sql."<br>";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$arrival_num = $Short_Term_Row['ARRIVAL_NUM'];
		}

		$result = Validate_Roll($barcode, $arrival_num, $cust, $conn);

		$sql = "SELECT BOL, CARGO_DESCRIPTION
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND ARRIVAL_NUM = '".$arrival_num."'
					AND RECEIVER_ID = '".$cust."'
					AND REMARK = 'DOLEPAPERSYSTEM'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($result != ""){
?>
	<tr>
		<td><font size="2" color="#FF0000">Roll cannot be transferred to Port Account:<br><? echo $result; ?></td>
	</tr>
<?
		} else {
?>
<form name="delete" action="Paper_to_Port.php" method="post">
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="arv" value="<? echo $arrival_num; ?>">
	<tr>
		<td width="100%"><font size="3" face="Verdana">Roll#:  <? echo $barcode; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Arrival#:  <? echo $arrival_num; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Cust#:  <? echo $cust; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">DT#:  <? echo $Short_Term_Row['BOL']; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Description:  <? echo $Short_Term_Row['CARGO_DESCRIPTION']; ?></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Transfer To Port"></td>
	</tr>
</form>
<?
		}
?>
</table>
<?
	}
?>

<!--
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" action="Paper_to_Port.php" method="post" name="the_upload">
	<tr>
		<td width="10%"><font size="2" face="Verdana">File:</td>
		<td><input type="file" name="update_file"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Upload File"></td>
	</tr>
</form>
	<tr>
		<td align="center" colspan="2"><a href="TransferToPortAccountCSVInstructions.doc" target="TransferToPortAccountCSVInstructions.doc">Import File Instructions</a></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr></td>
	</tr>
</table>
!-->
<?
	if($submit == "Upload File" && $response == ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="update_data" action="Paper_to_Port.php" method="post">
<input type="hidden" name="XLS_rowcounter" value="<? echo $XLS_rowcounter; ?>">
	<tr>
		<td colspan="5" align="center" bgcolor="#DDDDFF"><font size="3" face="Verdana"><b>Review Carefully, This</font><font size="3" face="Verdana" color="FF0000"> Cannot</font><font size="3" face="Verdana"> be Undone:</b></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="3" face="Verdana"><b>Customer</b></font></td>
		<td><font size="3" face="Verdana"><b>LR#</b></font></td>
		<td><font size="3" face="Verdana">DT#</font></td>
		<td><font size="3" face="Verdana">Description</font></td>
	</tr>
<?
		for($i = 1; $i < $XLS_rowcounter; $i++){
			$sql = "SELECT BOL, CARGO_DESCRIPTION
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcodes[$i]."'
						AND ARRIVAL_NUM = '".$LRs[$i]."'
						AND RECEIVER_ID = '".$custs[$i]."'
						AND REMARK = 'DOLEPAPERSYSTEM'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<input name="Barcodes[<? echo $i;?>]" type="hidden" value="<? echo $Barcodes[$i]; ?>">
	<input name="LRs[<? echo $i;?>]" type="hidden" value="<? echo $LRs[$i]; ?>">
	<input name="custs[<? echo $i;?>]" type="hidden" value="<? echo $custs[$i]; ?>">
	<tr>
		<td><font size="2" face="Verdana"><b><? echo $Barcodes[$i]; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $LRs[$i]; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $custs[$i]; ?></b></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_Row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_Row['CARGO_DESCRIPTION']; ?></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="5" align="center"><input name="submit" type="submit" value="Transfer Cargo"></td>
	</tr>
	<tr>
		<td colspan="5" align="center">&nbsp;<hr></td>
	</tr>
</table>

<?
	} 
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="revert_data" action="Paper_to_Port.php" method="post">
	<tr>
		<td colspan="6" align="center" bgcolor="#FFDDDD"><font size="3" face="Verdana"><b>Return to Previous Owner</b></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="3" face="Verdana"><b>Original Customer</b></font></td>
		<td><font size="3" face="Verdana"><b>LR#</b></font></td>
		<td><font size="3" face="Verdana">DT#</font></td>
		<td><font size="3" face="Verdana">Description</font></td>
		<td><font size="3" face="Verdana">Return</font></td>
	</tr>
<?
	$i = 0;
	$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, CA.ACTIVITY_DESCRIPTION, BOL, CARGO_DESCRIPTION
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.RECEIVER_ID = '1'
				AND CT.REMARK = 'DOLEPAPERSYSTEM'
				AND CA.SERVICE_CODE = '15'
				AND QTY_IN_HOUSE > 0
				AND CA.ACTIVITY_NUM = 
					(SELECT MAX(ACTIVITY_NUM)
					FROM CARGO_ACTIVITY CA2
					WHERE CA.PALLET_ID = CA2.PALLET_ID
						AND CA.ARRIVAL_NUM = CA2.ARRIVAL_NUM
						AND CA.CUSTOMER_ID = CA2.CUSTOMER_ID
						AND CA2.SERVICE_CODE = '15')
			ORDER BY CT.ARRIVAL_NUM, CT.PALLET_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><input type="hidden" name="barcode[<? echo $i; ?>]" value="<? echo $Short_Term_Row['PALLET_ID']; ?>">
			<input type="hidden" name="arv[<? echo $i; ?>]" value="<? echo $Short_Term_Row['ARRIVAL_NUM']; ?>">
			<font size="2" face="Verdana"><? echo $Short_Term_Row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_Row['ACTIVITY_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_Row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_Row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $Short_Term_Row['CARGO_DESCRIPTION']; ?></font></td>
		<td><input type="checkbox" value="change" name="revert[<? echo $i; ?>]"></td>
	</tr>
<?
		$i++;
	}
?>
	<input type="hidden" name="maxrows" value="<? echo $i; ?>">
	<tr>
		<td colspan="6" align="center"><input name="submit" type="submit" value="Revert Cargo to Owner"></td>
	</tr>
</form>
</table>



<?
	include("pow_footer.php");







/*
function Validate_File($Barcodes, $LRs, $custs, $XLS_rowcounter, $conn){
	$Short_Term_Cursor = ora_open($conn);

	$return = "";

	for($i = 1; $i < $XLS_rowcounter; $i++){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcodes[$i]."'
					AND RECEIVER_ID = '".$custs[$i]."'
					AND ARRIVAL_NUM = '".$LRs[$i]."'
					AND REMARK = 'DOLEPAPERSYSTEM'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] <= 0){
			$return .= "Line ".$i.":  Pallet ".$Barcodes[$i]." does not exist for vessel ".$LRs[$i]." / customer ".$custs[$i].".<br>";
		} else {
			$sql = "SELECT QTY_IN_HOUSE, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NR') THE_REC
					FROM CARGO_TRACKING
					WHERE PALLET_ID = '".$Barcodes[$i]."'
						AND RECEIVER_ID = '".$custs[$i]."'
						AND ARRIVAL_NUM = '".$LRs[$i]."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['QTY_IN_HOUSE'] <= 0){
				$return .= "Line ".$i.":  Pallet ".$Barcodes[$i]." / vessel ".$LRs[$i]." / customer ".$custs[$i].":  Pallet Not in House.<br>";
			}
			if($row['THE_REC'] == 'NR'){
				$return .= "Line ".$i.":  Pallet ".$Barcodes[$i]." / vessel ".$LRs[$i]." / customer ".$custs[$i].":  Never Received; Cannot XFer.<br>";
			}
		}

		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcodes[$i]."'
					AND RECEIVER_ID = '1'
					AND ARRIVAL_NUM = '".$LRs[$i]."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['THE_COUNT'] > 0){
			$return .= "Line ".$i.":  Pallet ".$Barcodes[$i]." / vessel ".$LRs[$i]." / customer ".$custs[$i].":  Pallet Already in Port's account.<br>";
		}
	}

	if(strtoupper($Barcodes[0]) != "BARCODE" || strtoupper($custs[0]) != "CUSTOMER"){
		$return = "File headers not in correct order (must be BARCODE, ARRIVAL, CUSTOMER)";
	}

	return $return;
}
*/
function Validate_Roll($Barcodes, $LRs, $custs, $conn){
	$Short_Term_Cursor = ora_open($conn);

	$return = "";

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcodes."'
				AND RECEIVER_ID = '".$custs."'
				AND ARRIVAL_NUM = '".$LRs."'
				AND REMARK = 'DOLEPAPERSYSTEM'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] <= 0){
		$return .= "Line ".$i.":  Pallet ".$Barcodes." does not exist for vessel ".$LRs." / customer ".$custs.".<br>";
	} else {
		$sql = "SELECT QTY_IN_HOUSE, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NR') THE_REC
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcodes."'
					AND RECEIVER_ID = '".$custs."'
					AND ARRIVAL_NUM = '".$LRs."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($row['QTY_IN_HOUSE'] <= 0){
			$return .= "Line ".$i.":  Pallet ".$Barcodes." / vessel ".$LRs." / customer ".$custs.":  Pallet Not in House.<br>";
		}
		if($row['THE_REC'] == 'NR'){
			$return .= "Line ".$i.":  Pallet ".$Barcodes." / vessel ".$LRs." / customer ".$custs.":  Never Received; Cannot XFer.<br>";
		}
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcodes."'
				AND RECEIVER_ID = '1'
				AND ARRIVAL_NUM = '".$LRs."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($row['THE_COUNT'] > 0){
		$return .= "Line ".$i.":  Pallet ".$Barcodes." / vessel ".$LRs." / customer ".$custs.":  Pallet Already in Port's account.<br>";
	}

	return $return;
}
