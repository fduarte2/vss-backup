<?
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

	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$trans_id = $HTTP_POST_VARS['trans_id'];

	$sql = "UPDATE CARGO_TRACKING CT
			SET (VARIETY, REMARK, CARGO_SIZE, WAREHOUSE_LOCATION, CARGO_DESCRIPTION, BATCH_ID, QTY_RECEIVED, QTY_IN_HOUSE) = 
				(SELECT VARIETY, LABEL, CARGO_SIZE, WAREHOUSE_LOCATION, GROWER, PACKAGE, QTY, QTY
				FROM CHILEAN_CUSTOMER_PLT_CHANGES CCPC
				WHERE CCPC.TRANSACTION_ID = '".$trans_id."'
				AND PALLET_TO_DB_COMPARE = 'MATCH'
				AND CT.PALLET_ID = CCPC.CT_JOIN_PALLET_ID)
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID = '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'MATCH')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	// update the C_T_ADDITIONAL_DATA table, to show that this pallet has already been "sorted"
	$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA CTAD
			SET CHILEAN_SORT_FILE_UPLOAD = SYSDATE
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID = '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'MATCH')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

/*
	// in case already receive-scanned, update that as well.
	$sql = "UPDATE CARGO_ACTIVITY CA
			SET (QTY_CHANGE, QTY_LEFT) =
				(SELECT QTY, QTY
				FROM CHILEAN_CUSTOMER_PLT_CHANGES CCPC
				WHERE CCPC.TRANSACTION_ID = '".$trans_id."'
				AND PALLET_TO_DB_COMPARE = 'MATCH'
				AND CA.PALLET_ID = CCPC.PALLET_ID)
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND CUSTOMER_ID = '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'MATCH')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
*/
	
	// matched pallets (the easy ones) finished.  now update the "transferred" pallets, IF APPLICABLE
	// step 1:  add the MANIFESTED_RECEIVER_ID if this is it's first transfer, as indicated by a null MANIFESTED_RECEIVER_ID
	// (if it is the first, current logic says new guy gets it, no checks)
	$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA CTAD
			SET MANIFESTED_RECEIVER_ID = RECEIVER_ID
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID != '".$user_cust_num."'
			AND MANIFESTED_RECEIVER_ID IS NULL
			AND CHILEAN_SORT_FILE_UPLOAD IS NULL
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'TOBETRANS')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	// update the sort file info.  done if either this is 1st transfer, or if original recipient wants it back.
	$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA CTAD
			SET CHILEAN_SORT_FILE_UPLOAD = SYSDATE,
			PREV_CUST_FROM_SORT_FILE = RECEIVER_ID
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID != '".$user_cust_num."'
			AND (CHILEAN_SORT_FILE_UPLOAD IS NULL OR MANIFESTED_RECEIVER_ID = '".$user_cust_num."')
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'TOBETRANS')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	$sql = "UPDATE CARGO_TRACKING CT
			SET (VARIETY, REMARK, CARGO_SIZE, WAREHOUSE_LOCATION, CARGO_DESCRIPTION, BATCH_ID, RECEIVER_ID, QTY_RECEIVED, QTY_IN_HOUSE) = 
				(SELECT VARIETY, LABEL, CARGO_SIZE, WAREHOUSE_LOCATION, GROWER, PACKAGE, '".$user_cust_num."', QTY, QTY
				FROM CHILEAN_CUSTOMER_PLT_CHANGES CCPC
				WHERE CCPC.TRANSACTION_ID = '".$trans_id."'
				AND PALLET_TO_DB_COMPARE = 'TOBETRANS'
				AND CT.PALLET_ID = CCPC.CT_JOIN_PALLET_ID)
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID != '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'TOBETRANS')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);


	// update header table
	$sql = "UPDATE CHILEAN_PLT_CHANGES_HEADER SET DATE_CONFIRMED = SYSDATE WHERE TRANSACTION_ID = '".$trans_id."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	// redirect to starting page.
	header("Location: ./chilean_pallet_update_index.php");
	exit;
?>