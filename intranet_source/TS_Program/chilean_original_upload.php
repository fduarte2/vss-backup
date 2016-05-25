<?
/*
*
*	Adam Walter, Dec 2009.
*
*	A screen for TS to "upload" an Original Chilean Manifest for future reference.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Chilean Upload";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from NPSA system");
    include("pow_footer.php");
    exit;
  }


  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $vessel != ""){
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']).".".date(mdYhis);
		$target_path_import = "./uploaded_manifests/".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact Port of Wilmington";
			exit;
		}

		$handle = fopen($target_path_import, "r");
		$sql = "SELECT NVL(MAX(TRANSACTION_ID), 0) THE_MAX FROM ORIGINAL_MANIFEST_HEADER";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$next_trans_id = $Short_Term_Row['THE_MAX'] + 1;
		$row_id = 0;

		while($temp = fgets($handle)){
			$row_id++;
			$line = explode(";", $temp);

			if($line[0] == "VOYAGE"){
				// do nothing
			} else {
/*				$sql = "INSERT INTO ORIGINAL_MANIFEST_DETAILS
						(VOYAGE,
						HATCH,
						DECK,
						CONTAINER,
						EXPORTER,
						CONSIGNEE,
						CONDITION,
						DUS,
						COMPANY,
						PALLET,
						ORIGINAL,
						CASES,
						COMMODITY,
						WEIGHT,
						LOADING_PORT,
						DISCHARGE_PORT,
						TOTAL_WEIGHT,
						TRANSACTION_ID,
						ROW_NUM)
					VALUES
						('".$line[0]."',
						'".$line[1]."',
						'".$line[2]."',
						'".$line[3]."',
						'".$line[4]."',
						'".$line[5]."',
						'".$line[6]."',
						'".$line[7]."',
						'".$line[8]."',
						'".$line[9]."',
						'".$line[10]."',
						'".$line[11]."',
						'".$line[12]."',
						'".$line[13]."',
						'".$line[14]."',
						'".$line[15]."',
						'".$line[16]."',
						'".$next_trans_id."',
						'".$row_id."')";*/
				$sql = "INSERT INTO ORIGINAL_MANIFEST_DETAILS_V2
						(VOYAGE,
						HATCH,
						DECK,
						CONTAINER,
						EXPORTER,
						EXPORTER_CODE,
						CONSIGNEE,
						CONSIGNEE_CODE,
						CONDITION,
						CONDITION_CODE,
						DUS,
						COMPANY,
						PALLET,
						CASES,
						COMMODITY,
						COMMODITY_CODE_FILE,
						WEIGHT,
						LOADING_PORT,
						DISCHARGE_PORT,
						TOTAL_WEIGHT,
						BOL,
						CORRECTED_BOL,
						TRANSACTION_ID,
						ROW_NUM)
					VALUES
						('".$line[0]."',
						'".$line[1]."',
						'".$line[2]."',
						'".$line[3]."',
						'".$line[4]."',
						'".$line[5]."',
						'".$line[6]."',
						'".$line[7]."',
						'".$line[8]."',
						'".$line[9]."',
						'".$line[10]."',
						'".$line[11]."',
						'".$line[12]."',
						'".$line[13]."',
						'".$line[14]."',
						'".$line[15]."',
						'".$line[16]."',
						'".$line[17]."',
						'".$line[18]."',
						'".$line[19]."',
						'".$line[20]."',
						'".trim(str_replace(",", "", $line[20]))."',
						'".$next_trans_id."',
						'".$row_id."')";
//				echo $sql."<br>";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
			}
		}
		ora_commit($conn);

		$sql = "INSERT INTO ORIGINAL_MANIFEST_HEADER
				(UPLOAD_TIME,
				FILENAME,
				RECORDCOUNT,
				USER_ID,
				TRANSACTION_ID,
				PUSHED_TO_CT,
				LR_NUM)
				SELECT
				SYSDATE,
				'".basename($HTTP_POST_FILES['import_file']['name'])."',
				COUNT(*),
				'".$user."',
				'".$next_trans_id."',
				'N',
				'".$vessel."'
				FROM ORIGINAL_MANIFEST_DETAILS_V2
				WHERE TRANSACTION_ID = '".$next_trans_id."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "SELECT RECORDCOUNT FROM ORIGINAL_MANIFEST_HEADER WHERE TRANSACTION_ID = '".$next_trans_id."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		echo "<font color=\"#0000FF\">".$Short_Term_Row['RECORDCOUNT']." rows inserted.</font><br>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Chilean Upload Page.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="chilean_original_upload.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($cur_ves == $row['LR_NUM']){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select File:</font>  <font size="2" face="Verdana">(semicolon-separated .txt or .csv file please)</font></td>
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
?>