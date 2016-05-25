<?
/*
*	Adam Walter, Dec 2009.
*
*	This page takes a previously-uploaded Original Chilean Manifest
*	And mvoes it to CT, provided it's "valid".
*************************************************************************/



  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Manifest Conversion";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$mod_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$trans = $HTTP_POST_VARS['trans'];
	$submit = $HTTP_POST_VARS['submit'];

	echo $submit ." ".$trans."<br>";


	if($submit != "" && $trans != ""){
		ora_commitoff($conn);  // we don't want the file going in unless ALL liens are "ok"

		$sql = "SELECT LR_NUM FROM ORIGINAL_MANIFEST_HEADER WHERE TRANSACTION_ID = '".$trans."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel = $row['LR_NUM'];
/*
		$sql = "SELECT SHIP_PREFIX FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$ship_type = $row['SHIP_PREFIX'];
*/
		$error_msg = Validate_full_file($Short_Term_Cursor, $trans, $mod_cursor);
		if($error_msg != ""){
			// there is something wrong in the file that should get addressed before the upload commenses
			echo "<font color=\"#FF0000\">Upload aborted; the following errors were detected:  <br><br>".$error_msg."</font><br>";
		} else {
			$sql = "DELETE FROM WM_CARLE_IMPORT WHERE ARRIVAL_NUM = '".$vessel."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);

			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						COMPLETION_STATUS,
						JOB_TYPE,
						JOB_DESCRIPTION,
						VARIABLE_LIST)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'".$user."',
						SYSDATE,
						'PENDING',
						'EMAIL',
						'NOMTCH1',
						'".$vessel."')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);

			// file as a *whole* is ok, but still check individual lines
			$sql = "SELECT * FROM ORIGINAL_MANIFEST_DETAILS_V2 OMD WHERE TRANSACTION_ID = '".$trans."'";
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);
			while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$msg = validate_manifest_row($first_row, $Short_Term_Cursor);
				if($msg == ""){
					write_to_ct($first_row, $vessel, $mod_cursor, $Short_Term_Cursor, $user);
				} else {
					$error_msg .= $msg."<br>";
				}
			}

			if($error_msg == ""){
				ora_commit($conn);
				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				echo "<font color=\"#0000FF\">".$row['THE_COUNT']." Records Now Present for vessel ".$vessel.".</font><br>";

				$sql = "SELECT COUNT(*) THE_COUNT FROM WM_CARLE_IMPORT WHERE ARRIVAL_NUM = '".$vessel."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				echo "<font color=\"#0000FF\">".$row['THE_COUNT']." Wal-Mart Pallets extricated for vessel ".$vessel.".</font><br>";

				$sql = "UPDATE ORIGINAL_MANIFEST_HEADER SET PUSH_TO_CT_USER = '".$user."', PUSH_TO_CT_DATETIME = SYSDATE, PUSHED_TO_CT = 'Y' WHERE TRANSACTION_ID = '".$trans."'";
				ora_parse($mod_cursor, $sql);
				ora_exec($mod_cursor);
			} else {
				ora_rollback($conn);
				echo "<font color=\"#FF0000\">Upload aborted; the following errors were detected:  <br><br>".$error_msg."</font><br>";
			}
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Chilean Manifest to Cargo Tracking Application
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="chilean_manifest_to_CT.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">File:  <select name="trans">
						<option value="">Please Select an Original File:</option>
<?
//			WHERE TO_CHAR(LR_NUM) NOT IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING)
	$sql = "SELECT TO_CHAR(UPLOAD_TIME, 'MM/DD/YYYY HH24:MI') THE_UPLOAD, FILENAME, LR_NUM, TRANSACTION_ID
			FROM ORIGINAL_MANIFEST_HEADER
			ORDER BY LR_NUM DESC, TRANSACTION_ID DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $row['TRANSACTION_ID']; ?>"><? echo $row['LR_NUM']." - Transaction ".$row['TRANSACTION_ID']." (".$row['FILENAME'].") uploaded at ".$row['THE_UPLOAD']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
<!--	<tr>
		<td><font size="3" face="Verdana"><b>Yay-nie:  This program is currently in TEST mode.  Please inform me when you are going to use it.</b></font></td>
	</tr> !-->
	<tr>
		<td><input type="submit" name="submit" value="Move Manifest"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");


function Validate_full_file($Short_Term_Cursor, $trans, $mod_cursor){
	// returns an error message detailing any found errors or "" if no errors.
	$return = "";

	$sql = "SELECT DISTINCT COMMODITY, COMMODITY_CODE_FILE FROM ORIGINAL_MANIFEST_DETAILS_V2 WHERE TRANSACTION_ID = '".$trans."' AND COMMODITY_CODE_FILE NOT IN
			(SELECT CHILEAN_COMMODITY_CODE FROM CHILEAN_COMMODITY_MAP_V2 WHERE DSPC_COMMODITY_CODE IS NOT NULL)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// life is good
	} else {
		$return .= "Commodities (";
		do {
			$return .= $row['COMMODITY'].", ";

			$sql = "SELECT * FROM CHILEAN_COMMODITY_MAP_V2 WHERE CHILEAN_COMMODITY_CODE = '".$row['COMMODITY_CODE_FILE']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
			if(!ora_fetch_into($mod_cursor, $mod_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// only do this is a blank line isn't already waiting
				$sql = "INSERT INTO CHILEAN_COMMODITY_MAP_V2 (CHILEAN_COMMODITY_CODE, CHILEAN_COMMODITY_NAME) VALUES ('".$row['COMMODITY_CODE_FILE']."', '".$row['COMMODITY']."')";
				ora_parse($mod_cursor, $sql);
				ora_exec($mod_cursor);
			}
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		$return .= ") did not match any completed entry in the CHILEAN_COMMODITY_MAP_V2 Table.<br>Entries have been added to the table; please fill it out and re-run this program.<br><br>";
	}

	$sql = "SELECT DISTINCT CONSIGNEE, CONSIGNEE_CODE FROM ORIGINAL_MANIFEST_DETAILS_V2 WHERE TRANSACTION_ID = '".$trans."' AND CONSIGNEE_CODE NOT IN
			(SELECT CHILEAN_CONSIGNEE_CODE FROM CHILEAN_CUSTOMER_MAP_V2 WHERE RECEIVER_ID IS NOT NULL)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// life is good
	} else {
		$return .= "Consignees (";
		do {
			$return .= $row['CONSIGNEE'].", ";

			$sql = "SELECT * FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CHILEAN_CONSIGNEE_CODE = '".$row['CONSIGNEE_CODE']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
			if(!ora_fetch_into($mod_cursor, $mod_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// only do this is a blank line isn't already waiting
				$sql = "INSERT INTO CHILEAN_CUSTOMER_MAP_V2 (CHILEAN_CONSIGNEE_CODE, CHILEAN_CONSIGNEE_NAME) VALUES ('".$row['CONSIGNEE_CODE']."', '".$row['CONSIGNEE']."')";
				ora_parse($mod_cursor, $sql);
				ora_exec($mod_cursor);
			}
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		$return .= ") did not match any completed entry in the CHILEAN_CUSTOMER_MAP_V2 Table.<br>Entries have been added to the table; please fill it out and re-run this program.<br><br>";
	}

	$sql = "SELECT DISTINCT CONDITION, CONDITION_CODE FROM ORIGINAL_MANIFEST_DETAILS_V2 WHERE TRANSACTION_ID = '".$trans."' AND CONDITION_CODE NOT IN
			(SELECT CONDITION_CODE FROM CHILEAN_FUMED_MAP_V2 WHERE FUMED_TYPE IS NOT NULL)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// life is good
	} else {
		$return .= "Fume codes (";
		do {
			$return .= $row['CONDITION'].", ";

			$sql = "SELECT * FROM CHILEAN_FUMED_MAP_V2 WHERE CONDITION_CODE = '".$row['CONDITION_CODE']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
			if(!ora_fetch_into($mod_cursor, $mod_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// only do this is a blank line isn't already waiting
				$sql = "INSERT INTO CHILEAN_FUMED_MAP_V2 (CONDITION_CODE, CONDITION_NAME) VALUES ('".$row['CONDITION_CODE']."', '".$row['CONDITION']."')";
				ora_parse($mod_cursor, $sql);
				ora_exec($mod_cursor);
			}
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		$return .= ") did not match any completed entry in the CHILEAN_FUMED_MAP_V2 Table.<br>Entries have been added to the table; please fill it out and re-run this program.<br><br>";
	}

	$sql = "SELECT DISTINCT COMPANY FROM ORIGINAL_MANIFEST_DETAILS_V2 WHERE TRANSACTION_ID = '".$trans."' AND COMPANY NOT IN
			(SELECT PSW_COMPANY_CODE FROM CHILEAN_PSW_COMPANY_MAP_V2 WHERE FROM_SHIPPING_LINE IS NOT NULL)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// life is good
	} else {
		$return .= "Company codes (";
		do {
			$return .= $row['COMPANY'].", ";

			$sql = "SELECT * FROM CHILEAN_PSW_COMPANY_MAP_V2 WHERE PSW_COMPANY_CODE = '".$row['COMPANY']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
			if(!ora_fetch_into($mod_cursor, $mod_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// only do this is a blank line isn't already waiting
				$sql = "INSERT INTO CHILEAN_PSW_COMPANY_MAP_V2 (PSW_COMPANY_CODE) VALUES ('".$row['COMPANY']."')";
				ora_parse($mod_cursor, $sql);
				ora_exec($mod_cursor);
			}
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		$return .= ") did not match any completed entry in the CHILEAN_PSW_COMPANY_MAP_V2 Table.<br>Entries have been added to the table; please fill it out and re-run this program.<br><br>";
	}

	// EXPORTER CODES
	// this one is NOT necessary for uploading; do not generate error messages or block program execution
	$sql = "SELECT DISTINCT EXPORTER, EXPORTER_CODE FROM ORIGINAL_MANIFEST_DETAILS_V2 WHERE TRANSACTION_ID = '".$trans."' AND EXPORTER_CODE NOT IN
			(SELECT EXPORTER_CODE FROM CHILEAN_PSW_EXPORTER_MAP_V2)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// life is good
	} else {
		// need to add lines
		do {
			$sql = "INSERT INTO CHILEAN_PSW_EXPORTER_MAP_V2 (PSW_EXPORTER_CODE, PSW_EXPORTER_NAME) VALUES ('".$row['EXPORTER_CODE']."', '".$row['EXPORTER']."')";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	$sql = "SELECT PALLET, COUNT(*) THE_COUNT FROM ORIGINAL_MANIFEST_DETAILS_V2 WHERE TRANSACTION_ID = '".$trans."'
			GROUP BY PALLET
			HAVING COUNT(*) > 1
			ORDER BY COUNT(*) DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// life is good
	} else {
		$return .= "Barcodes (";
		do {
			$return .= $row['PALLET'].", ";
		} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		$return .= ") showed up multiple times in your input file.  Please make sure each Barcode has only one entry.";
	}

	return $return;
}



function validate_manifest_row($OMD_record, $Short_Term_Cursor){
/*
	// Commodity good?
	$sql = "SELECT * FROM CHILEAN_COMMODITY_MAP WHERE CHILEAN_COMMODITY_CODE = '".$OMD_record['COMMODITY']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return "Line ".$OMD_record['ROW_ID']." did not match any entry in the Commodity Matching Table.";
	}


	// Customer good?
	$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_UOM = '".substr($OMD_record['COMMODITY'], 0, 8)."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return "Line ".$OMD_record['ROW_ID']." did not match any entry in the Customer Matching Table.";
	}


	// Fume good?  (heh)
	$sql = "SELECT * FROM CHILEAN_FUMED_MAP WHERE CONDITION = '".$OMD_record['CONDITION']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		return "Line ".$OMD_record['ROW_ID']." did not match any entry in the Fumigation Matching Table.";
	}

*/
	// all the checks for now.
	return "";


}


function write_to_ct($OMD_record, $vessel, $mod_cursor, $Short_Term_Cursor, $user){
	// sub queries will work because their existence was checked before this function is run

	$sql = "SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CHILEAN_CONSIGNEE_CODE = '".$OMD_record['CONSIGNEE_CODE']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$cust = $row['RECEIVER_ID'];

	if($cust == "3000"){
		$table = "WM_CARLE_IMPORT";
	} else {
		$table = "CARGO_TRACKING";
	}

	$sql = "SELECT DSPC_COMMODITY_CODE FROM CHILEAN_COMMODITY_MAP_V2 WHERE CHILEAN_COMMODITY_CODE = '".$OMD_record['COMMODITY_CODE_FILE']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$comm = $row['DSPC_COMMODITY_CODE'];

	$sql = "SELECT FUMED_TYPE FROM CHILEAN_FUMED_MAP_V2 WHERE CONDITION_CODE = '".$OMD_record['CONDITION_CODE']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$fume = $row['FUMED_TYPE'];

	$sql = "SELECT FROM_SHIPPING_LINE FROM CHILEAN_PSW_COMPANY_MAP_V2 WHERE PSW_COMPANY_CODE = '".$OMD_record['COMPANY']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$shipline = $row['FROM_SHIPPING_LINE'];

	$sql = "SELECT FILENAME FROM ORIGINAL_MANIFEST_HEADER WHERE TRANSACTION_ID = '".$OMD_record['TRANSACTION_ID']."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$filename = $row['FILENAME'];

	$sql = "INSERT INTO ".$table."
			(PALLET_ID,
			ARRIVAL_NUM,
			QTY_RECEIVED,
			BOL,
			COMMODITY_CODE,
			RECEIVER_ID,
			EXPORTER_CODE,
			WEIGHT,
			WEIGHT_UNIT,
			DECK,
			HATCH,
			CONTAINER_ID,
			FUMIGATION_CODE,
			CARGO_DESCRIPTION,
			FROM_SHIPPING_LINE,
			SHIPPING_LINE,
			QTY_IN_HOUSE,
			CARGO_TYPE_ID,
			RECEIVING_TYPE,
			MANIFESTED,
			SOURCE_NOTE,
			SOURCE_USER)
			VALUES
			('".strtoupper(trim($OMD_record['PALLET']))."',
			'".$vessel."',
			".$OMD_record['CASES'].",
			'".$OMD_record['CORRECTED_BOL']."',
			".$comm.",
			".$cust.",
			'".substr($OMD_record['EXPORTER'], 0, 20)."',
			".($OMD_record['TOTAL_WEIGHT'] + 0).",
			'KG',
			'".$OMD_record['DECK']."',
			NVL('".trim($OMD_record['HATCH'].$OMD_record['DECK'])."', 'CONT'),
			'".$OMD_record['CONTAINER']."',
			'".$fume."',
			'',
			'".$shipline."',
			8091,
			".$OMD_record['CASES'].",
			'1',
			'S',
			'Y',
			'".$filename."',
			'".$user."')";
//	echo $sql."<br>";
	ora_parse($mod_cursor, $sql);
	ora_exec($mod_cursor);

}
?>