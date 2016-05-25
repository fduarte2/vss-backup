<?
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }


  $cursor = ora_open($conn);         
  $cursor2 = ora_open($conn);         
  $Short_Term_Cursor = ora_open($conn);
  $short_term_cursor = ora_open($conn);
  ora_commitoff($conn);

	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
//	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 36, date('Y')));

	$sql = "SELECT WALMART_REPORT_HEADER_SEQ.NEXTVAL THE_VAL FROM DUAL";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$report_num = $row['THE_VAL'];
/*
	$sql = "SELECT SEQ_WALMART_REPORT_UNIQ_ID.NEXTVAL THE_VAL FROM DUAL";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$unique_line_num = $row['THE_VAL'];
*/
	// get data for THIS day
	$sql = "INSERT INTO WDI_DATA_REPORT_HEADER
			(REPORT_TYPE,
			DATE_CREATED,
			HEADER_ID)
			VALUES
			('PLT MVT REPORT',
			TO_DATE('".$date."', 'MM/DD/YYYY'),
			'".$report_num."')";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);



	// MAJOR EDIT, April 2010.
	// Walmart apparently regularly screws up their own PO#s, and we get to fix it for them.  Yay!.
	// This is handled by an Eport page, which takes effect during the nightly run of this.
	$sql = "SELECT OLD_PO, NEW_PO FROM WDI_PO_CHANGES WHERE STATUS = 'PENDING'";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		do {
			// alter the WDI_RECEIVING_PO in WDI_ADDITIONAL_DATA with the change PRIOR to report making

			$sql = "UPDATE WDI_ADDITIONAL_DATA SET WDI_RECEIVING_PO = '".$Short_Term_row['NEW_PO']."'
					WHERE WDI_RECEIVING_PO = '".$Short_Term_row['OLD_PO']."'";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor);
		} while (ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	// also, prior to "new report", make "corrections"...
	// CORRECTIVE LINE
	$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
				(HEADER_ID,
				UNIQUE_IDENTIFIER,
				PALLET_ID,
				LOT_NUMBER,
				PO_NUMBER,
				ITEM_NUMBER,
				RECEIVING_PO,
				LOT_DESCRIPTION,
				REPORT_DATE,
				FACILITY,
				MOVE_TYPE,
				CUSTOMER_ORDER,
				WAREHOUSE_ORDER,
				VESSEL,
				CARTONS,
				MOVE_DATE,
				COMMENTS,
				CHANGE_REASON_DESC,
				LINK_TO_CT_LR_NUM,
				LINK_TO_CT_REC_ID,
				LINK_TO_CA_ACT_NUM,
				LINK_TO_CA_PLT_NUM,
				IS_MOST_RECENT)
			(SELECT
				'".$report_num."',
				WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
				INNER_TABLE.*
				FROM
					(SELECT distinct
					PALLET_ID,
					LOT_NUMBER,
					PO_NUMBER,
					ITEM_NUMBER,
					RECEIVING_PO,
					LOT_DESCRIPTION,
					SYSDATE,
					FACILITY,
					MOVE_TYPE,
					CUSTOMER_ORDER,
					WAREHOUSE_ORDER,
					VESSEL,
					(-1 * CARTONS),
					MOVE_DATE,
					COMMENTS,
					'POW received incorrect PO#',
					LINK_TO_CT_LR_NUM,
					LINK_TO_CT_REC_ID,
					LINK_TO_CA_ACT_NUM,
					LINK_TO_CA_PLT_NUM,
					'C'
					FROM WDI_REPORT_PLT_MOVEMENT WRPM, WDI_PO_CHANGES WPC
					WHERE WRPM.RECEIVING_PO = WPC.OLD_PO
					AND WPC.STATUS = 'PENDING'
					AND IS_MOST_RECENT = 'Y') INNER_TABLE)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	// ADJUSTMENT LINE
	$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
				(HEADER_ID,
				UNIQUE_IDENTIFIER,
				PALLET_ID,
				LOT_NUMBER,
				PO_NUMBER,
				ITEM_NUMBER,
				RECEIVING_PO,
				LOT_DESCRIPTION,
				REPORT_DATE,
				FACILITY,
				MOVE_TYPE,
				CUSTOMER_ORDER,
				WAREHOUSE_ORDER,
				VESSEL,
				CARTONS,
				MOVE_DATE,
				COMMENTS,
				CHANGE_REASON_DESC,
				LINK_TO_CT_LR_NUM,
				LINK_TO_CT_REC_ID,
				LINK_TO_CA_ACT_NUM,
				LINK_TO_CA_PLT_NUM,
				IS_MOST_RECENT)
			(SELECT
				'".$report_num."',
				WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
				INNER_TABLE.*
				FROM
					(SELECT distinct
					PALLET_ID,
					LOT_NUMBER,
					NEW_PO NEW_PO1,
					ITEM_NUMBER,
					NEW_PO NEW_PO2,
					LOT_DESCRIPTION,
					SYSDATE,
					FACILITY,
					MOVE_TYPE,
					CUSTOMER_ORDER,
					WAREHOUSE_ORDER,
					VESSEL,
					CARTONS,
					MOVE_DATE,
					COMMENTS,
					'Corrected PO#',
					LINK_TO_CT_LR_NUM,
					LINK_TO_CT_REC_ID,
					LINK_TO_CA_ACT_NUM,
					LINK_TO_CA_PLT_NUM,
					'Y'
					FROM WDI_REPORT_PLT_MOVEMENT WRPM, WDI_PO_CHANGES WPC
					WHERE WRPM.RECEIVING_PO = WPC.OLD_PO
					AND WPC.STATUS = 'PENDING'
					AND IS_MOST_RECENT = 'Y') INNER_TABLE)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	
	// NEGATION OF ORIGINAL REPORT
	$sql = "UPDATE WDI_REPORT_PLT_MOVEMENT WRPM SET IS_MOST_RECENT = 'N', PO_CHANGE_NUM = 
																	(SELECT PO_CHANGE_NUM FROM WDI_PO_CHANGES WPC
																	WHERE WRPM.RECEIVING_PO = WPC.OLD_PO
																	AND STATUS = 'PENDING')
			WHERE IS_MOST_RECENT = 'Y'
			AND RECEIVING_PO IN
				(SELECT OLD_PO FROM WDI_PO_CHANGES WHERE STATUS = 'PENDING')";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	// notify finaance of the changes.
	$sql = "SELECT * FROM WDI_PO_CHANGES WHERE STATUS = 'PENDING'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else { 
		// send emails regarding changes
		$mailTo = "wdi@port.state.de.us";
		$mailheaders = "From: " . "pownoreply@port.state.de.us\r\n";
//		$mailheaders .= "Cc: " . "archive@port.state.de.us\r\n";
//		$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,hdadmin@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
		$mailheaders .= "Bcc: " . "awalter@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,archive@port.state.de.us\r\n";
		$mailsubject = "Walmart PO changes";
		$body = "";
		do {
			$body .= "Old PO - ".$row['OLD_PO']."\r\n\tNew PO - ".$row['NEW_PO']."\r\n";
		} while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		mail($mailTo, $mailsubject, $body, $mailheaders);
	}



	$sql = "UPDATE WDI_PO_CHANGES SET STATUS = 'EXECUTED' WHERE STATUS = 'PENDING'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	


	// activity done today.  only includes non-altered shipouts, ship-ins, and recoups.  Repacks-out (as indicated by the R%) arent included.
/* AND (CT.BATCH_ID IS NULL OR (CT.BATCH_ID != 'LUCCA' AND CT.BATCH_ID != 'STORAGE'))
				AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
					(SELECT WDI_PALLET_ID, WDI_RECEIVER_ID, WDI_ARRIVAL_NUM
					FROM WDI_ADDITIONAL_DATA
					WHERE WDI_STATUS = 'HOLD')
*/
	$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
				(HEADER_ID,
				UNIQUE_IDENTIFIER,
				PALLET_ID,
				PO_NUMBER,
				RECEIVING_PO,
				ITEM_NUMBER,
				LOT_DESCRIPTION,
				REPORT_DATE,
				FACILITY,
				MOVE_TYPE,
				CUSTOMER_ORDER,
				WAREHOUSE_ORDER,
				VESSEL,
				CARTONS,
				MOVE_DATE,
				COMMENTS,
				CHANGE_REASON_DESC,
				LINK_TO_CT_LR_NUM,
				LINK_TO_CT_REC_ID,
				LINK_TO_CA_ACT_NUM,
				LINK_TO_CA_PLT_NUM,
				IS_MOST_RECENT)
			(SELECT
					'".$report_num."',
					WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
					CT.PALLET_ID,
					CT.MARK,
					CT.MARK,
					CT.BOL,
					CT.VARIETY,
					TO_DATE('".$date."', 'MM/DD/YYYY'),
					'PoW',
					DECODE(CA.SERVICE_CODE, '1', 'IN', '8', 'IN', 'OUT'),
					DECODE(CA.SERVICE_CODE, '1', '', '8', '', CA.ORDER_NUM),
					DECODE(CA.SERVICE_CODE, '1', CT.CONTAINER_ID, '8', CT.CONTAINER_ID, CA.ORDER_NUM),
					DECODE(CA.SERVICE_CODE, '9', 'RECOUP', VP.VESSEL_NAME),
					ABS(CA.QTY_CHANGE),
					DECODE(CA.SERVICE_CODE, '6', CA.DATE_OF_ACTIVITY, GREATEST(CA.DATE_OF_ACTIVITY, WVR.RELEASE_TIME)),
					'',
					'DAILY ACTIVITY',
					CA.ARRIVAL_NUM,
					CA.CUSTOMER_ID,
					CA.ACTIVITY_NUM,
					CA.PALLET_ID,
					'Y'
				FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CARGO_TRACKING_ADDITIONAL_DATA CTAD,
					WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CTAD.PALLET_ID = CT.PALLET_ID
					AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
					AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM(+))
					AND (CA.SERVICE_CODE = '6'
						OR CA.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM))
					AND (CA.ORDER_NUM IS NULL OR CA.ORDER_NUM NOT LIKE 'R%')
					AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND CA.SERVICE_CODE IN ('1', '6', '8')
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
					AND CTAD.USDA_HOLD IS NULL
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND (CA.SERVICE_CODE != '6' OR CA.ACTIVITY_DESCRIPTION IS NULL)
					AND (	
							(CA.SERVICE_CODE IN ('6') 
							AND	TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."')
						OR
							(CA.SERVICE_CODE IN ('1', '8')
							AND TO_CHAR(GREATEST(WVR.RELEASE_TIME, CA.DATE_OF_ACTIVITY), 'MM/DD/YYYY') = '".$date."')
						)
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART'))";
//	echo $sql."\n";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	// outbound repacks.
	$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
				(HEADER_ID,
				UNIQUE_IDENTIFIER,
				PALLET_ID,
				PO_NUMBER,
				RECEIVING_PO,
				ITEM_NUMBER,
				LOT_DESCRIPTION,
				REPORT_DATE,
				FACILITY,
				MOVE_TYPE,
				CUSTOMER_ORDER,
				WAREHOUSE_ORDER,
				VESSEL,
				CARTONS,
				MOVE_DATE,
				COMMENTS,
				CHANGE_REASON_DESC,
				LINK_TO_CT_LR_NUM,
				LINK_TO_CT_REC_ID,
				LINK_TO_CA_ACT_NUM,
				LINK_TO_CA_PLT_NUM,
				IS_MOST_RECENT)
			(SELECT
					'".$report_num."',
					WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
					CT.PALLET_ID,
					CT.MARK,
					CT.MARK,
					CT.BOL,
					CT.VARIETY,
					TO_DATE('".$date."', 'MM/DD/YYYY'),
					'PoW',
					DECODE(CA.SERVICE_CODE, '1', 'IN', '8', 'IN', 'OUT'),
					DECODE(CA.SERVICE_CODE, '1', '', '8', '', CA.ORDER_NUM),
					DECODE(CA.SERVICE_CODE, '1', CT.CONTAINER_ID, '8', CT.CONTAINER_ID, CA.ORDER_NUM),
					DECODE(CA.SERVICE_CODE, '9', 'RECOUP', VP.VESSEL_NAME),
					ABS(CA.QTY_CHANGE),
					DECODE(CA.SERVICE_CODE, '6', CA.DATE_OF_ACTIVITY, GREATEST(CA.DATE_OF_ACTIVITY, WVR.RELEASE_TIME)),
					'',
					'SENT TO REPACK',
					CA.ARRIVAL_NUM,
					CA.CUSTOMER_ID,
					CA.ACTIVITY_NUM,
					CA.PALLET_ID,
					'Y'
				FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP, 
					WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM(+))
					AND CA.SERVICE_CODE = '6'
					AND CA.ORDER_NUM LIKE 'R%'
					AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND (	
							(CA.SERVICE_CODE IN ('6') 
							AND	TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."')
						OR
							(CA.SERVICE_CODE IN ('1', '8')
							AND TO_CHAR(GREATEST(WVR.RELEASE_TIME, CA.DATE_OF_ACTIVITY), 'MM/DD/YYYY') = '".$date."')
						)
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART'))";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);



	// a line for every "repack back in"
	$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
				(HEADER_ID,
				UNIQUE_IDENTIFIER,
				PALLET_ID,
				PO_NUMBER,
				RECEIVING_PO,
				ITEM_NUMBER,
				LOT_DESCRIPTION,
				REPORT_DATE,
				FACILITY,
				MOVE_TYPE,
				CUSTOMER_ORDER,
				WAREHOUSE_ORDER,
				VESSEL,
				CARTONS,
				MOVE_DATE,
				COMMENTS,
				CHANGE_REASON_DESC,
				LINK_TO_CT_LR_NUM,
				LINK_TO_CT_REC_ID,
				LINK_TO_CA_ACT_NUM,
				LINK_TO_CA_PLT_NUM,
				IS_MOST_RECENT)
			(SELECT
					'".$report_num."',
					WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
					CT.PALLET_ID,
					CT.MARK,
					CT.MARK,
					CT.BOL,
					CT.VARIETY,
					TO_DATE('".$date."', 'MM/DD/YYYY'),
					'PoW',
					'In',
					'Back From Repack',
					'',
					VP.VESSEL_NAME,
					ABS(CA.QTY_CHANGE),
					CA.DATE_OF_ACTIVITY,
					'',
					'Back From Repack',
					CA.ARRIVAL_NUM,
					CA.CUSTOMER_ID,
					CA.ACTIVITY_NUM,
					CA.PALLET_ID,
					'Y'
				FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP, WDI_VESSEL_RELEASE WVR,
						WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
					AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND CA.SERVICE_CODE IN ('20')
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) IN
						(SELECT LINK_TO_CA_PLT_NUM, LINK_TO_CT_REC_ID, LINK_TO_CT_LR_NUM
						FROM WDI_REPORT_PLT_MOVEMENT
						WHERE HEADER_ID = '".$report_num."'
						)
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);




	// RECOUP, modified 12/10/2014
	// Part one:  get each pallet recouped that day, the sum of the recoupds, and that pallet's QTY_DAMAGED value.
	$sql = "SELECT SUM(QTY_CHANGE) THE_RECOUP, CA.PALLET_ID, CA.ARRIVAL_NUM, CA.CUSTOMER_ID, QTY_DAMAGED
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT
			WHERE CA.SERVICE_CODE IN ('9')
				AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CA.QTY_CHANGE < 0
				AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
			GROUP BY CA.PALLET_ID, CA.ARRIVAL_NUM, CA.CUSTOMER_ID, QTY_DAMAGED";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $pallets_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
						(HEADER_ID,
						UNIQUE_IDENTIFIER,
						PALLET_ID,
						PO_NUMBER,
						RECEIVING_PO,
						ITEM_NUMBER,
						LOT_DESCRIPTION,
						REPORT_DATE,
						FACILITY,
						MOVE_TYPE,
						CUSTOMER_ORDER,
						WAREHOUSE_ORDER,
						VESSEL,
						CARTONS,
						MOVE_DATE,
						COMMENTS,
						CHANGE_REASON_DESC,
						LINK_TO_CT_LR_NUM,
						LINK_TO_CT_REC_ID,
						LINK_TO_CA_ACT_NUM,
						LINK_TO_CA_PLT_NUM,
						IS_MOST_RECENT)
					(SELECT '".$report_num."',
						WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
						CT.PALLET_ID,
						CT.MARK,
						CT.MARK,
						CT.BOL,
						CT.VARIETY,
						TO_DATE('".$date."', 'MM/DD/YYYY'),
						'PoW',
						'Out',
						CT.MARK,
						'',
						VP.VESSEL_NAME,
						(-1 * QTY_CHANGE),
						CA.DATE_OF_ACTIVITY,
						'',
						'OCEAN CARRIER DAMAGE',
						CA.ARRIVAL_NUM,
						CA.CUSTOMER_ID,
						CA.ACTIVITY_NUM,
						CA.PALLET_ID,
						'Y'
					FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
						AND CT.PALLET_ID = CA.PALLET_ID
						AND CTAD.PALLET_ID = CT.PALLET_ID
						AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
						AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
						AND CA.SERVICE_CODE IN ('9')
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
						AND CTAD.USDA_HOLD IS NULL
						AND CA.QTY_CHANGE < 0
						AND CA.PALLET_ID = '".$pallets_row['PALLET_ID']."'
						AND CA.ARRIVAL_NUM = '".$pallets_row['ARRIVAL_NUM']."'
						AND CA.CUSTOMER_ID = '".$pallets_row['CUSTOMER_ID']."'
						AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."')";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor);
	}



	// pallets off USDA or QC hold
	// NOTE:  there is no indication on the report that these "ins" that it is from a hold, as opposed to a regular receive.
	// that's fine, the end used doesnt care about it either.
	$sql = "INSERT INTO WDI_REPORT_PLT_MOVEMENT
				(HEADER_ID,
				UNIQUE_IDENTIFIER,
				PALLET_ID,
				PO_NUMBER,
				RECEIVING_PO,
				ITEM_NUMBER,
				LOT_DESCRIPTION,
				REPORT_DATE,
				FACILITY,
				MOVE_TYPE,
				CUSTOMER_ORDER,
				WAREHOUSE_ORDER,
				VESSEL,
				CARTONS,
				MOVE_DATE,
				COMMENTS,
				CHANGE_REASON_DESC,
				LINK_TO_CT_LR_NUM,
				LINK_TO_CT_REC_ID,
				LINK_TO_CA_ACT_NUM,
				LINK_TO_CA_PLT_NUM,
				IS_MOST_RECENT)
			(SELECT
					'".$report_num."',
					WALMART_REPORT_UNIQ_ID_SEQ.NEXTVAL,
					CT.PALLET_ID,
					CT.MARK,
					CT.MARK,
					CT.BOL,
					CT.VARIETY,
					TO_DATE('".$date."', 'MM/DD/YYYY'),
					'PoW',
					DECODE(CA.SERVICE_CODE, '26', 'IN', 'OUT'),
					'',
					'',
					DECODE(CA.SERVICE_CODE, '9', 'RECOUP', VP.VESSEL_NAME),
					ABS(CA.QTY_CHANGE),
					DECODE(CA.SERVICE_CODE, '6', CA.DATE_OF_ACTIVITY, GREATEST(CA.DATE_OF_ACTIVITY, WVR.RELEASE_TIME)),
					'',
					'SENT TO REPACK',
					CA.ARRIVAL_NUM,
					CA.CUSTOMER_ID,
					CA.ACTIVITY_NUM,
					CA.PALLET_ID,
					'Y'
				FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP, CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CARGO_TRACKING_ADDITIONAL_DATA CTAD,
					WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CTAD.PALLET_ID = CT.PALLET_ID
					AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
					AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM(+))
					AND CA.SERVICE_CODE IN ('19', '26')
					AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND CT.CARGO_STATUS IS NULL
					AND CTAD.USDA_HOLD IS NULL
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
						(SELECT LINK_TO_CA_PLT_NUM, LINK_TO_CT_REC_ID, LINK_TO_CT_LR_NUM
						FROM WDI_REPORT_PLT_MOVEMENT
						WHERE MOVE_TYPE = 'IN'
							AND CHANGE_REASON_DESC = 'DAILY ACTIVITY'
						)
				)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);



	ora_commit($conn);


	// SCRIPT NOT FINISHED
	// a new table and 3 triggers need to be set up for any Alterations to CARGO_ACTIVITY, so that said changes can be in this file
	// a similar setup was done for Kopke; ask Stephen, he'll know where the kopke ones are, which can be copied and changed.
	// DEC 2015:  ok, by this point, that comment is no longer likely true.  But I think the script is as "finished" as it will get for now.


	// create and email file from report.
	$xls_file = "<TABLE border=1 CELLSPACING=1><tr></tr><tr><td>WALMART</td><td colspan=\"16\"></td></tr><tr></tr>";
	$xls_file .= "<tr>
					<td>Unique Identifier</td>
					<td>Date</td>
					<td>Facility</td>
					<td>Move Type</td>
					<td>Customer Order</td>
					<td>Warehouse Order</td>
					<td>Vesl</td>
					<td>Pallet Id</td>
					<td>Cartons</td>
					<td>Lot Description</td>
					<td>Lot Number</td>
					<td>Move Date</td>
					<td>PO Number</td>
					<td>Item Number</td>
					<td>Receiving PO</td>
					<td>Comments</td>
					<td>Change Reason Desc</td>
				</tr>";

	$sql = "SELECT UNIQUE_IDENTIFIER, TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') REP_DATE, FACILITY, MOVE_TYPE, CUSTOMER_ORDER, WAREHOUSE_ORDER,
	VESSEL, PALLET_ID, CARTONS, LOT_DESCRIPTION, LOT_NUMBER, MOVE_DATE, TO_CHAR(MOVE_DATE, 'MM/DD/YYYY') THE_MOVE, PO_NUMBER, ITEM_NUMBER, RECEIVING_PO, COMMENTS, CHANGE_REASON_DESC
			FROM WDI_REPORT_PLT_MOVEMENT
			WHERE HEADER_ID = '".$report_num."'
			ORDER BY PALLET_ID, MOVE_DATE, CARTONS";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body = "No Movement today.\r\n";
	} else {
		$body = "See Attached File.\r\n";

		do {
			$xls_file .= "<tr>
							<td>".$row['UNIQUE_IDENTIFIER']."</td>
							<td>".$row['REP_DATE']."</td>
							<td>".$row['FACILITY']."</td>
							<td>".$row['MOVE_TYPE']."</td>
							<td>".$row['CUSTOMER_ORDER']."</td>
							<td>".$row['WAREHOUSE_ORDER']."</td>
							<td>".$row['VESSEL']."</td>
							<td>".$row['PALLET_ID']."</td>
							<td>".$row['CARTONS']."</td>
							<td>".$row['LOT_DESCRIPTION']."</td>
							<td>".$row['LOT_NUMBER']."</td>
							<td>".$row['THE_MOVE']."</td>
							<td>".$row['PO_NUMBER']."</td>
							<td>".$row['ITEM_NUMBER']."</td>
							<td>".$row['RECEIVING_PO']."</td>
							<td>".$row['COMMENTS']."</td>
							<td>".$row['CHANGE_REASON_DESC']."</td>
						</tr>";
		} while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	$replace_body = $body;



	$xls_file .= "</table>";

	$xls_attach=chunk_split(base64_encode($xls_file));

//	$mailTo = "awalter@port.state.de.us,hdadmin@port.state.de.us,sadu@port.state.de.us";
//	$mailTo = "wilmingt47@wal-mart.com,diinvct@wal-mart.com";
//	$mailsubject = "Pallet Movement Report for ".$date."\r\n";

//	$mailheaders = "From: " . "NoReplies@POWWDI.com\r\n";
//	$mailheaders .= "Cc: " . "wdi@port.state.de.us,Jeff.Hicks@wal-mart.com,schargr@wal-mart.com\r\n";
//	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";
//  bdempsey@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us,ddonofrio@port.state.de.us

//	$bni_conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
//	$bni_conn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WALMARTPLTACT'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);
/*
	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}
*/
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "lstewart@port.state.de.us";
//		$mailheaders .= "Cc: lstewart@port.state.de.us\r\n";
		$mailheaders .= "Cc: awalter@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $date, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $replace_body, $body);

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"PalletMovementReport".$date.".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$xls_attach;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";

	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
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
					JOB_BODY)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'WALMARTPLTACT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
	}

//	mail($mailTo, $mailsubject, $Content, $mailheaders);
?>