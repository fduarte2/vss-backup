<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);
  $VERY_Short_Term_Cursor = ora_open($conn);
  $cursor_email = ora_open($conn);

	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$upload_num = $HTTP_POST_VARS['upload_num'];
	$country = $HTTP_POST_VARS['country'];

	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	if($user_cust_num != "3000"){
		echo "This page is for usage by Walmart customers only.  Please choose a different link from the menu on the left.";
		exit;
	}

	if($submit == "Commit File"){
		// the other webscreen that calls this function does all of the fact-checking; it can ONLY be submitted if all checks are passed.
		// therefore, this routine should be secure.

//					SUB_CUSTID,
//					GROWER_CODE,
//					UPPER(TRIM(WCRD.EXPORTER)),
		$sql = "INSERT INTO WDI_SPLIT_PALLETS
					(SPLIT_PALLET_ID,
					PALLET_ID, 
					COMMODITY_CODE,
					RECEIVER_ID,
					ARRIVAL_NUM,
					QTY_RECEIVED,
					QTY_IN_HOUSE,
					FUMIGATION_CODE,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					EXPORTER_CODE,
					BOL,
					VARIETY,
					HATCH,
					MARK,
					BATCH_ID,
					CARGO_SIZE,
					CONTAINER_ID,
					CARGO_DESCRIPTION,
					REMARK,
					RECEIVING_TYPE,
					MANIFESTED,
					CARGO_TYPE_ID,
					SOURCE_NOTE,
					SOURCE_USER)
				(SELECT
					WDI_SPLIT_PALLETS_SEQ.NEXTVAL,
					UPPER(TRIM(WCRD.PALLET_ID)),
					COMMODITY_CODE,
					'".$user_cust_num."',
					ARRIVAL_NUM,
					CASE_COUNT,
					CASE_COUNT,
					UPPER(TRIM(WCRD.PREFUMED)),
					'8091',
					'8091',
					UPPER(SUBSTR(GROWER_CODE_VARCHAR || '_' || TRIM(WCRD.EXPORTER), 0, 19)),
					TO_CHAR(WM_ITEM_NUM),
					UPPER(TRIM(SUBSTR(WCRD.VARIETY_CODE || WCRD.VARIETY_DESCRIPTION, 0, 20))),
					UPPER(TRIM(WCRD.VESSEL_STORE_CODE)),
					WM_PO_NUM,
					GROWER_ITEM_NUM,
					SIZE_CODE,
					UPPER(TRIM(CONTAINER_NUM)),
					UPPER(TRIM(SUBSTR(PACKING_CODE || '--' || PACKING_DESCRIPTION || '--' || PACKING_NAME, 0, 60))),
					UPPER(TRIM(SUBSTR(WCRD.LABEL_CODE || WCRD.LABEL_DESCRIPTION, 0, 20))),
					'S',
					'Y',
					WCT.CARGO_TYPE_ID,
					SUBSTR(FILENAME_APPENDED, 0, 50),
					'".$user."'
					FROM WM_UPLOAD_HISTORY WCUH, WALMART_CARGO_RAW_DUMP WCRD, WM_ITEM_COMM_MAP WICM, WM_ITEMNUM_MAPPING WIM, WM_CARGO_TYPE WCT
					WHERE WCUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
					AND WCRD.GROWER_ITEM_NUM = WIM.ITEM_NUM
					AND WIM.WM_ITEM_NUM = WICM.ITEM_NUM
					AND WCRD.WM_PROGRAM = WCT.WM_PROGRAM
					AND WCUH.UPLOAD_NUMBER = '".$upload_num."'
					AND
						(SELECT COUNT(DISTINCT WIM_2.WM_ITEM_NUM) FROM WALMART_CARGO_RAW_DUMP WCRD_2, WM_ITEMNUM_MAPPING WIM_2
							WHERE WCRD_2.UPLOAD_NUMBER = '".$upload_num."'
							AND WCRD_2.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
							AND WCRD_2.GROWER_ITEM_NUM = WIM_2.ITEM_NUM
							AND WCRD_2.PALLET_ID = WCRD.PALLET_ID) >= 2
				)";
//		echo $sql."<br>";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

//					MIN(FUMIGATION_CODE), 					FUMIGATION_CODE,
// 					HATCH,					MAX(HATCH),
//					SUB_CUSTID,
//					DECODE(COUNT(DISTINCT SUB_CUSTID), 1, MAX(SUB_CUSTID), (-1 * COUNT(DISTINCT SUB_CUSTID))),
		$sql = "INSERT INTO CARGO_TRACKING
					(PALLET_ID, 
					COMMODITY_CODE,
					RECEIVER_ID,
					ARRIVAL_NUM,
					VARIETY,
					CONTAINER_ID,
					CARGO_SIZE,
					BOL,
					MARK,
					REMARK,
					BATCH_ID,
					EXPORTER_CODE,
					HATCH,
					CARGO_DESCRIPTION,
					FUMIGATION_CODE,
					QTY_RECEIVED,
					QTY_IN_HOUSE,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					RECEIVING_TYPE,
					MANIFESTED,
					CARGO_TYPE_ID,
					SOURCE_NOTE,
					SOURCE_USER)
				(SELECT		
					WDI.PALLET_ID, 
					WDI.COMMODITY_CODE,
					WDI.RECEIVER_ID,
					WDI.ARRIVAL_NUM,
					DECODE(COUNT(DISTINCT WDI.VARIETY), 1, MIN(WDI.VARIETY), 'MULTIPLE'),
					DECODE(COUNT(DISTINCT WDI.CONTAINER_ID), 1, MIN(WDI.CONTAINER_ID), 'MULTIPLE'),
					DECODE(COUNT(DISTINCT WDI.CARGO_SIZE), 1, MIN(WDI.CARGO_SIZE), 'MULTI'),
					DECODE(COUNT(DISTINCT WDI.BOL), 1, MIN(WDI.BOL), 'MULTIPLE'),
					DECODE(COUNT(DISTINCT WDI.MARK), 1, MIN(WDI.MARK), 'MULTIPLE'),
					DECODE(COUNT(DISTINCT WDI.REMARK), 1, MIN(WDI.REMARK), 'MULTIPLE'),
					DECODE(COUNT(DISTINCT WDI.BATCH_ID), 1, MIN(WDI.BATCH_ID), 'MULTIPLE'),
					SUBSTR(DECODE(COUNT(DISTINCT GROWER_CODE_VARCHAR), 1, MAX(GROWER_CODE_VARCHAR), (-1 * COUNT(DISTINCT GROWER_CODE_VARCHAR)))
						|| '_' ||
						DECODE(COUNT(DISTINCT EXPORTER), 1, MIN(EXPORTER), 'MULTIPLE'), 0, 19),
					DECODE(COUNT(DISTINCT WDI.HATCH), 1, MIN(WDI.HATCH), 'MULTI'),
					DECODE(COUNT(DISTINCT WDI.CARGO_DESCRIPTION), 1, MIN(WDI.CARGO_DESCRIPTION), 'MULTIPLE'),
					MIN(WDI.FUMIGATION_CODE),
					SUM(WDI.QTY_RECEIVED),
					SUM(WDI.QTY_IN_HOUSE),
					MAX(WDI.FROM_SHIPPING_LINE),
					MAX(WDI.SHIPPING_LINE),
					MAX(WDI.RECEIVING_TYPE),
					MAX(WDI.MANIFESTED),
					MAX(WDI.CARGO_TYPE_ID),
					MAX(WDI.SOURCE_NOTE),
					MAX(WDI.SOURCE_USER)
				FROM WDI_SPLIT_PALLETS WDI, WALMART_CARGO_RAW_DUMP WCRD
				WHERE WDI.PALLET_ID = WCRD.PALLET_ID
					AND WCRD.UPLOAD_NUMBER = '".$upload_num."'
					AND WDI.ARRIVAL_NUM = '".$cur_ves."'
				GROUP BY WDI.PALLET_ID, WDI.COMMODITY_CODE, WDI.RECEIVER_ID, WDI.ARRIVAL_NUM
				)";
//		echo $sql."<br>";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

//					SUB_CUSTID,
//						DECODE(COUNT(DISTINCT GROWER_CODE), 1, MAX(GROWER_CODE), (-1 * COUNT(DISTINCT GROWER_CODE))),
//						DECODE(COUNT(DISTINCT EXPORTER), 1, MIN(EXPORTER), 'MULTIPLE'),
		$sql = "INSERT INTO CARGO_TRACKING
					(PALLET_ID, 
					COMMODITY_CODE,
					RECEIVER_ID,
					ARRIVAL_NUM,
					QTY_RECEIVED,
					QTY_IN_HOUSE,
					FUMIGATION_CODE,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					EXPORTER_CODE,
					BOL,
					VARIETY,
					HATCH,
					MARK,
					BATCH_ID,
					CARGO_SIZE,
					CONTAINER_ID,
					CARGO_DESCRIPTION,
					REMARK,
					RECEIVING_TYPE,
					MANIFESTED,
					CARGO_TYPE_ID,
					SOURCE_NOTE,
					SOURCE_USER)
				(SELECT
						UPPER(TRIM(WCRD.PALLET_ID)),
						COMMODITY_CODE,
						'".$user_cust_num."',
						ARRIVAL_NUM,
						SUM(CASE_COUNT),
						SUM(CASE_COUNT),
						MAX(UPPER(TRIM(WCRD.PREFUMED))),
						'8091',
						'8091',
						SUBSTR(DECODE(COUNT(DISTINCT GROWER_CODE_VARCHAR), 1, MAX(GROWER_CODE_VARCHAR), (-1 * COUNT(DISTINCT GROWER_CODE_VARCHAR)))
							|| '_' ||
							DECODE(COUNT(DISTINCT EXPORTER), 1, MIN(EXPORTER), 'MULTIPLE'), 0, 19),
						MAX(TO_CHAR(WM_ITEM_NUM)),
						DECODE(COUNT(DISTINCT SUBSTR(WCRD.VARIETY_CODE || WCRD.VARIETY_DESCRIPTION, 0, 20)), 1, MIN(SUBSTR(WCRD.VARIETY_CODE || WCRD.VARIETY_DESCRIPTION, 0, 20)), 'MULTIPLE'),
						DECODE(COUNT(DISTINCT VESSEL_STORE_CODE), 1, MIN(VESSEL_STORE_CODE), 'MULTI'),
						DECODE(COUNT(DISTINCT WM_PO_NUM), 1, MIN(WM_PO_NUM), 'MULTIPLE'),
						DECODE(COUNT(DISTINCT GROWER_ITEM_NUM), 1, MIN(GROWER_ITEM_NUM), 'MULTIPLE'),
						DECODE(COUNT(DISTINCT SIZE_CODE), 1, MIN(SIZE_CODE), 'MULTI'),
						DECODE(COUNT(DISTINCT CONTAINER_NUM), 1, MIN(CONTAINER_NUM), 'MULTIPLE'),
						DECODE(COUNT(DISTINCT UPPER(TRIM(SUBSTR(PACKING_CODE || '--' || PACKING_DESCRIPTION || '--' || PACKING_NAME, 0, 60)))), 1, MIN(UPPER(TRIM(SUBSTR(PACKING_CODE || '--' || PACKING_DESCRIPTION || '--' || PACKING_NAME, 0, 60)))), 'MULTIPLE'),
						DECODE(COUNT(DISTINCT UPPER(TRIM(SUBSTR(WCRD.LABEL_CODE || WCRD.LABEL_DESCRIPTION, 0, 20)))), 1, MIN(UPPER(TRIM(SUBSTR(WCRD.LABEL_CODE || WCRD.LABEL_DESCRIPTION, 0, 20)))), 'MULTIPLE'),
						'S',
						'Y',
						MAX(WCT.CARGO_TYPE_ID),
						MAX(SUBSTR(FILENAME_APPENDED, 0, 50)),
						'".$user."'
							FROM WM_UPLOAD_HISTORY WCUH, WALMART_CARGO_RAW_DUMP WCRD, WM_ITEM_COMM_MAP WICM, WM_ITEMNUM_MAPPING WIM, WM_CARGO_TYPE WCT
							WHERE WCUH.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
							AND WCRD.GROWER_ITEM_NUM = WIM.ITEM_NUM
							AND WIM.WM_ITEM_NUM = WICM.ITEM_NUM
							AND WCRD.WM_PROGRAM = WCT.WM_PROGRAM
							AND WCUH.UPLOAD_NUMBER = '".$upload_num."'
							AND
								(SELECT COUNT(DISTINCT WIM_2.WM_ITEM_NUM) FROM WALMART_CARGO_RAW_DUMP WCRD_2, WM_ITEMNUM_MAPPING WIM_2
									WHERE WCRD_2.UPLOAD_NUMBER = '".$upload_num."'
									AND WCRD_2.UPLOAD_NUMBER = WCRD.UPLOAD_NUMBER
									AND WCRD_2.GROWER_ITEM_NUM = WIM_2.ITEM_NUM
									AND WCRD_2.PALLET_ID = WCRD.PALLET_ID) = 1
					GROUP BY UPPER(TRIM(WCRD.PALLET_ID)), COMMODITY_CODE, ARRIVAL_NUM
				)";
//		echo $sql."<br>";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA
				SET COUNTRY_CODE = '".$country."'
				WHERE ARRIVAL_NUM = '".$cur_ves."'
					AND RECEIVER_ID = '".$view_cust."'
					AND PALLET_ID IN
					(SELECT PALLET_ID FROM WALMART_CARGO_RAW_DUMP WHERE UPLOAD_NUMBER = '".$upload_num."')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "UPDATE WM_UPLOAD_HISTORY SET STATUS = 'UPLOADED' WHERE UPLOAD_NUMBER = '".$upload_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
/*
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'AQCWDI'";
		ora_parse($cursor_email, $sql);
		ora_exec($cursor_email);
		ora_fetch_into($cursor_email, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailTO = $email_row['TO'];
		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}

		$mailSubject = $email_row['SUBJECT'];
		$body = $email_row['NARRATIVE'];

		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE VP, WM_UPLOAD_HISTORY WUH
				WHERE VP.LR_NUM = WUH.ARRIVAL_NUM
				AND WUH.UPLOAD_NUMBER = '".$upload_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $Short_Term_Row['VESSEL_NAME'];

		$body = str_replace("_0_", $vessel_name, $body);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY,
						VARIABLE_LIST)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'DAILYCRON',
						SYSDATE,
						'EMAIL',
						'PAPERDR',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."',
						'".$vessel_name."')";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		}
		*/
		/*
			$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					JOB_PARAMETERS,
					COMPLETION_STATUS)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'".$user."',
					SYSDATE,
					'EMAIL',
					'AQCWDI',
					'To:  TBD  cc: HDadmin status=commit',
					'COMMITED')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		*/
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT 
				FROM WALMART_CARGO_RAW_DUMP
				WHERE UPLOAD_NUMBER = '".$upload_num."'
				AND WM_PROGRAM = 'BASE'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$distinct_pallet_count = $row['THE_COUNT'];

		$sql = "INSERT INTO JOB_QUEUE
				(JOB_ID,
				SUBMITTER_ID,
				SUBMISSION_DATETIME,
				JOB_TYPE,
				JOB_DESCRIPTION,
				VARIABLE_LIST,
				COMPLETION_STATUS)
			VALUES
				(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
				'".$user."',
				SYSDATE,
				'EMAIL',
				'AQCWDI',
				'".$cur_ves.";".$distinct_pallet_count."',
				'PENDING')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);

		$sql = "INSERT INTO JOB_QUEUE
				(JOB_ID,
				SUBMITTER_ID,
				SUBMISSION_DATETIME,
				JOB_TYPE,
				JOB_DESCRIPTION,
				VARIABLE_LIST,
				COMPLETION_STATUS)
			VALUES
				(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
				'".$user."',
				SYSDATE,
				'EMAIL',
				'WMUPLOADITEMS',
				'".$cur_ves."',
				'PENDING')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);


		echo "<font color=\"#0000FF\"><b>File# ".$upload_num." Committed.</b></font>";
	}






?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Walmart Cargo Upload
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="file_submit" action="walmart_upload_confirm_index.php" method="post">
	<tr>
		<td align="left">Vessel:  <select name="vessel">
								<option value=""<? if($cur_ves == ""){?> selected <?}?>>Select a Vessel</option>
<?
   // POPULATE TOP DROPDOWN BOX
//   $sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' AND TO_CHAR(LR_NUM) IN (SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE QTY_IN_HOUSE > 0 AND DATE_RECEIVED IS NULL AND RECEIVER_ID = '".$user_cust_num."' ORDER BY LR_NUM DESC";
//  AND VESSEL_FLAG = 'Y' 
   $sql = "SELECT VP.LR_NUM, VP.VESSEL_NAME, NVL(TO_CHAR(VOY.DATE_EXPECTED, 'MM/DD/YYYY'), 'NONE') THE_DATE FROM VESSEL_PROFILE VP, VOYAGE VOY WHERE VP.LR_NUM = VOY.LR_NUM AND SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT') AND VOY.DATE_EXPECTED >= SYSDATE - 180 ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($cur_ves == $row['LR_NUM']){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']." (".$row['THE_DATE'].")"; ?></option>
<?
	}
?>
					</select>
		</td>
<?
	$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_EXPEDITER_CUST_LIST WHERE EPORT_LOGIN = '".$user."'"; 
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($Short_Term_Row['THE_COUNT'] > 0){
?>
		<td align="left">Customer to view:  <select name="view_cust">
<?
		$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL WHERE CECL.EPORT_LOGIN = '".$user."' AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID ORDER BY CP.CUSTOMER_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($view_cust == $row['CUSTOMER_ID']){?> selected <?}?>><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
					</select></td>
<?
	}
?>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">(If the list is empty, there are no vessels available for upload at this time.<br>We upload the vessel data as soon as we get it from the shipping line.<br>Please check back again.)</font><br><br><br></td>
	</tr>
	<tr>
		<td align="left">Country of Origin:  <select name="country">
<?
   // POPULATE TOP DROPDOWN BOX
   $sql = "SELECT COUNTRY_CODE, COUNTRY_NAME
			FROM COUNTRY
			ORDER BY COUNTRY_CODE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['COUNTRY_CODE']; ?>"<? if($cur_country == $row['COUNTRY_CODE']){?> selected <?}?>><? echo $row['COUNTRY_NAME']; ?></option>
<?
	}
?>
					</select>
		</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">(If your cargo has more than 1 Country of Origin, please divide the file so that each upload only contains 1)</font><br></td>
	</tr>
<!--	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Vessel Info"></td>
	</tr> !-->
</table>

<br><br>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<!--<input type="hidden" name="vessel" value="<? echo $cur_ves; ?>">
<input type="hidden" name="view_cust" value="<? echo $user_cust_num; ?>"> !-->
	<tr>
		<td align="left"><font size="3" face="Verdana">Sample Excel Spreadsheet:</font>  <font size="2" face="Verdana"><a href="WMUploadFileFormat.xls">(Sample)</a></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Select Excel File:</font>  <font size="2" face="Verdana"><a href="ImportXLSInstructionsforWalmart.doc">(File Instructions)</a></font></td>
	</tr>
	<tr>
		<td><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Upload"></td>
	</tr>
</form>
</table>
