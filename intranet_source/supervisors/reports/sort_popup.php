<?	// popup window attached to sort_upload_summary_v2.php

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFRFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);

	
	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$type = $HTTP_GET_VARS['type'];

	if($type == "NOW"){
		$title = "Currently Assigned";
	} elseif($type == "CARLE"){
		$title = "File From Carle";
	} elseif($type == "MATCH"){
		$title = "Sort Files Matched to Carle";
	} elseif($type == "NOMATCH"){
		$title = "Pallets sent by Carle, but not Sorted";
	} elseif($type == "NODB"){
		$title = "Sort File Pallets not sent by Carle";
	} elseif($type == "NOTRANS"){
		$title = "Requested Customer Transfers (DENIED)";
	} else {// only type left is YESTRANS{
		$title = "Requested Customer Transfers (GRANTED)";
	}

	if($type == "NOW"){
		$sql = "SELECT DISTINCT PALLET_ID THE_PLT, QTY_RECEIVED THE_QTY, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NOT-REC') THE_DATE
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."'
				AND RECEIVER_ID = '".$cust."'
				ORDER BY THE_PLT";
	} elseif($type == "CARLE"){
		$sql = "SELECT DISTINCT PALLET THE_PLT, CASES THE_QTY, 'N/A' THE_DATE
				FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
				WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
				AND OMH.LR_NUM = '".$vessel."'
				AND CCM.RECEIVER_ID = '".$cust."'
				AND OMH.PUSHED_TO_CT = 'Y'
				ORDER BY THE_PLT";
	} elseif($type == "MATCH"){
		$sql = "SELECT DISTINCT PALLET THE_PLT, CASES THE_QTY, 'N/A' THE_DATE
				FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
				WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
				AND OMH.LR_NUM = '".$vessel."'
				AND CCM.RECEIVER_ID = '".$cust."'
				AND OMH.PUSHED_TO_CT = 'Y'
				AND PALLET IN
					(SELECT PALLET_ID
					FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
					WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
					AND CPCH.ARRIVAL_NUM = '".$vessel."'
					AND CPCH.CUSTOMER_ID = '".$cust."'
					AND DATE_CONFIRMED IS NOT NULL
					AND PALLET_TO_DB_COMPARE != 'NOTINFILE'
					)
				ORDER BY THE_PLT";
	} elseif($type == "NOMATCH"){
		$sql = "SELECT DISTINCT PALLET THE_PLT, CASES THE_QTY, 'N/A' THE_DATE
				FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
				WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
				AND OMH.LR_NUM = '".$vessel."'
				AND CCM.RECEIVER_ID = '".$cust."'
				AND OMH.PUSHED_TO_CT = 'Y'
				AND PALLET NOT IN
					(SELECT PALLET_ID
					FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
					WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
					AND CPCH.ARRIVAL_NUM = '".$vessel."'
					AND CPCH.CUSTOMER_ID = '".$cust."'
					AND DATE_CONFIRMED IS NOT NULL
					AND PALLET_TO_DB_COMPARE != 'NOTINFILE'
					)
				ORDER BY THE_PLT";
	} elseif($type == "NODB"){
		$sql = "SELECT DISTINCT PALLET_ID THE_PLT, QTY THE_QTY, 'N/A' THE_DATE
				FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
				WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
				AND CPCH.ARRIVAL_NUM = '".$vessel."'
				AND CPCH.CUSTOMER_ID = '".$cust."'
				AND DATE_CONFIRMED IS NOT NULL
				AND PALLET_ID NOT IN
					(SELECT PALLET
					FROM ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
					WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
					AND OMH.LR_NUM = '".$vessel."'
					AND OMH.PUSHED_TO_CT = 'Y'
					)
				ORDER BY THE_PLT";
	} elseif($type == "NOTRANS"){
		$sql = "SELECT DISTINCT PALLET THE_PLT, CASES THE_QTY, 'N/A' THE_DATE
				FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
				WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
				AND OMH.LR_NUM = '".$vessel."'
				AND CCM.RECEIVER_ID = '".$cust."'
				AND OMH.PUSHED_TO_CT = 'Y'
				AND PALLET IN
					(SELECT PALLET_ID
					FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
					WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
					AND CPCH.ARRIVAL_NUM = '".$vessel."'
					AND CPCH.CUSTOMER_ID != '".$cust."'
					AND DATE_CONFIRMED IS NOT NULL
					)
				AND PALLET IN
					(SELECT PALLET_ID
					FROM CARGO_TRACKING
					WHERE RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$vessel."'
					)
				ORDER BY THE_PLT";
	} else {// only type left is YESTRANS{
		$sql = "SELECT DISTINCT PALLET THE_PLT, CASES THE_QTY, 'N/A' THE_DATE
				FROM CHILEAN_CUSTOMER_MAP CCM, ORIGINAL_MANIFEST_HEADER OMH, ORIGINAL_MANIFEST_DETAILS OMD
				WHERE OMH.TRANSACTION_ID = OMD.TRANSACTION_ID
				AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE
				AND OMH.LR_NUM = '".$vessel."'
				AND CCM.RECEIVER_ID = '".$cust."'
				AND OMH.PUSHED_TO_CT = 'Y'
				AND PALLET IN
					(SELECT PALLET_ID
					FROM CHILEAN_PLT_CHANGES_HEADER CPCH, CHILEAN_CUSTOMER_PLT_CHANGES CCPC
					WHERE CPCH.TRANSACTION_ID = CCPC.TRANSACTION_ID
					AND CPCH.ARRIVAL_NUM = '".$vessel."'
					AND CPCH.CUSTOMER_ID != '".$cust."'
					AND DATE_CONFIRMED IS NOT NULL
					)
				AND PALLET IN
					(SELECT PALLET_ID
					FROM CARGO_TRACKING
					WHERE RECEIVER_ID != '".$cust."'
					)
				ORDER BY THE_PLT";
	}

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3" align="center"><b><? echo $title; ?></b></td>
	</tr>
	<tr>
		<td><b>Pallet</b></td>
		<td><b>QTY</b></td>
		<td><b>Date Received</b></td>
	</tr>
<?
	
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><? echo $short_term_row['THE_PLT']; ?></td>
		<td><? echo $short_term_row['THE_QTY']; ?></td>
		<td><? echo $short_term_row['THE_DATE']; ?></td>
	</tr>
<?
	}
?>
