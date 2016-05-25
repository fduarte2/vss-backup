<?
/*
*	Adam Walter, Dec 2009
*
*	Compares CARGO_TRACKING to ORIGINAL_MANFIST to determine disjoints.
***************************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Manifest Comparison";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
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

  $cursor = ora_open($conn);
  $cursor_first = ora_open($conn);
  $cursor_second = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

	$trans = $HTTP_POST_VARS['trans'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $trans != ""){
		$sql = "SELECT NVL(MAX(RUN_ID), 0) THE_MAX FROM MANIFEST_ANALYSIS_HEADER";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$next_run_id = $Short_Term_Row['THE_MAX'] + 1;
		$issue_num = 1;

		// get vessel
		$sql = "SELECT LR_NUM FROM ORIGINAL_MANIFEST_HEADER WHERE TRANSACTION_ID = '".$trans."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel = $Short_Term_Row['LR_NUM'];

		// create header
		$sql = "INSERT INTO MANIFEST_ANALYSIS_HEADER (RUN_ID, ARRIVAL_NUM, DATE_RUN) VALUES ('".$next_run_id."', '".$vessel."', SYSDATE)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		// 1) get all cargo NOT in original manifest.
		$sql = "SELECT * FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND DATE_RECEIVED IS NOT NULL AND PALLET_ID NOT IN (SELECT PALLET FROM ORIGINAL_MANIFEST_DETAILS WHERE TRANSACTION_ID = '".$trans."')";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// split this into another SQL so taht I can order by date_received...
			$sql = "SELECT DATE_RECEIVED, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') NICE_DATE, RECEIVER_ID FROM CARGO_TRACKING WHERE PALLET_ID = '".$first_row['PALLET_ID']."' AND ARRIVAL_NUM = '".$vessel."' ORDER BY DATE_RECEIVED ASC";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$sql = "INSERT INTO MANIFEST_ANALYSIS_DETAILS (RUN_ID, ISSUE_NUM, TRANSACTION_ID, ROW_NUM, DSPC_PALLET_ID, DSPC_RECEIVER_ID, ERROR_MSG)
					VALUES
					('".$next_run_id."',
					'".$issue_num."',
					'0',
					'0',
					'".$first_row['PALLET_ID']."',
					'".$second_row['RECEIVER_ID']."',
					'PoWE1:  ".$first_row['PALLET_ID']." Received at POW but Pallet ID missing in Carle File')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);

			$issue_num++;
		}

		// 2) get all cargo in manifest NOT in CARGO_TRACKING
		$sql = "SELECT * FROM ORIGINAL_MANIFEST_DETAILS OMD, CHILEAN_CUSTOMER_MAP CCM WHERE TRANSACTION_ID = '".$trans."' AND CONSIGNEE = CCM.CHILEAN_CONSIGNEE AND PALLET NOT IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND DATE_RECEIVED IS NOT NULL)";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "INSERT INTO MANIFEST_ANALYSIS_DETAILS (RUN_ID, ISSUE_NUM, TRANSACTION_ID, ROW_NUM, DSPC_PALLET_ID, DSPC_RECEIVER_ID, ERROR_MSG)
					VALUES
					('".$next_run_id."',
					'".$issue_num."',
					'".$first_row['TRANSACTION_ID']."',
					'".$first_row['ROW_NUM']."',
					'N/A',
					'0',
					'PoWE2:  ".$first_row['PALLET']." In Carle File (Row Number ".$first_row['ROW_NUM'].") but did not arrive at POW')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);

			$issue_num++;
		}

		// 3) get cargo from manifest that has a received pallet_id in CARGO_TRACKING, but not it's customer; was not found before shipped out
		$sql = "SELECT * FROM ORIGINAL_MANIFEST_DETAILS OMD, CHILEAN_CUSTOMER_MAP CCM WHERE TRANSACTION_ID = '".$trans."' 
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE 
				AND PALLET IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND DATE_RECEIVED IS NOT NULL)
				AND (PALLET, RECEIVER_ID) IN 
						(SELECT PALLET_ID, CUSTOMER_ID FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$vessel."'
						AND ACTIVITY_NUM = '1' AND SERVICE_CODE = '1')
				AND (PALLET, RECEIVER_ID) IN
						(SELECT PALLET_ID, CUSTOMER_ID FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$vessel."'
						AND ACTIVITY_NUM != '1' AND SERVICE_CODE = '17')";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);

		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT ACTIVITY_DESCRIPTION FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$first_row['PALLET']."' AND SERVICE_CODE = '17' AND ACTIVITY_NUM != '1' AND ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$first_row['CUSTOMER_ID']."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$temp = explode(" ", $second_row['ACTIVITY_DESCRIPTION']);
			$our_consign = $temp[4];
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$our_consign."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$sql = "INSERT INTO MANIFEST_ANALYSIS_DETAILS (RUN_ID, ISSUE_NUM, TRANSACTION_ID, ROW_NUM, DSPC_PALLET_ID, DSPC_RECEIVER_ID, ERROR_MSG)
					VALUES
					('".$next_run_id."',
					'".$issue_num."',
					'".$first_row['TRANSACTION_ID']."',
					'".$first_row['ROW_NUM']."',
					'".$first_row['PALLET']."',
					'0',
					'PoWE3:  Carle file (Row ".$first_row['ROW_NUM']." Pallet ".$first_row['PALLET'].") had incorrect Consignee (".$first_row['CONSIGNEE']."), correct Consignee is ".$second_row['CUSTOMER_NAME']."')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);

			$issue_num++;
		}

		// 4) get cargo from manifest that has a received pallet_id in CARGO_TRACKING, but not it's customer; was found before shipped out
		$sql = "SELECT * FROM ORIGINAL_MANIFEST_DETAILS OMD, CHILEAN_CUSTOMER_MAP CCM WHERE TRANSACTION_ID = '".$trans."' 
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE 
				AND PALLET IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND DATE_RECEIVED IS NOT NULL)
				AND (PALLET, RECEIVER_ID) NOT IN 
						(SELECT PALLET_ID, CUSTOMER_ID FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$vessel."'
						AND ACTIVITY_NUM = '1' AND SERVICE_CODE = '1')";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);

		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT CUSTOMER_ID FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$first_row['PALLET']."' AND SERVICE_CODE = '1' AND ACTIVITY_NUM = '1' AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$our_consign = $second_row['CUSTOMER_ID'];
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$our_consign."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$sql = "INSERT INTO MANIFEST_ANALYSIS_DETAILS (RUN_ID, ISSUE_NUM, TRANSACTION_ID, ROW_NUM, DSPC_PALLET_ID, DSPC_RECEIVER_ID, ERROR_MSG)
					VALUES
					('".$next_run_id."',
					'".$issue_num."',
					'".$first_row['TRANSACTION_ID']."',
					'".$first_row['ROW_NUM']."',
					'".$first_row['PALLET']."',
					'0',
					'PoWE4:  Carle file (Row ".$first_row['ROW_NUM']." Pallet ".$first_row['PALLET'].") had incorrect Consignee (".$first_row['CONSIGNEE']."), correct Consignee is ".$second_row['CUSTOMER_NAME']."')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);

			$issue_num++;
		}

		echo "<font color=\"#0000FF\">Comparison complete; ".($issue_num - 1)." issues recorded.</font><br>";
	}






?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Manifest Comparison
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="manifest_comparison.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="trans">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
   $sql = "SELECT TRANSACTION_ID, LR_NUM, TO_CHAR(UPLOAD_TIME, 'MM/DD/YYYY HH24:MI') THE_UPLOAD FROM ORIGINAL_MANIFEST_HEADER WHERE LR_NUM IN (SELECT LR_NUM FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND VESSEL_FLAG = 'N') ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['TRANSACTION_ID']; ?>"<? if($cur_trans == $row['TRANSACTION_ID']){?> selected <?}?>><? echo $row['TRANSACTION_ID']." - ".$row['LR_NUM']." - ".$row['THE_UPLOAD']; ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Generate Comparison"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>