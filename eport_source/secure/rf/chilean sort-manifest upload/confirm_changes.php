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

	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}

	$cur_ves = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$trans_id = $HTTP_POST_VARS['trans_id'];

	// DECEMBER 2015
	// To-Be-transferred cargo is back, but under a new name.
	// swap the customer ID, then let the MATCH statement handle the rest
	$sql = "UPDATE CARGO_TRACKING CT
			SET RECEIVER_ID = '".$user_cust_num."'
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID != '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE IN ('TOBEEXCHG'))";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	
	$sql = "UPDATE CARGO_TRACKING CT
			SET (VARIETY, REMARK, CARGO_SIZE, WAREHOUSE_LOCATION, CARGO_DESCRIPTION, BATCH_ID, QTY_RECEIVED, QTY_IN_HOUSE) = 
				(SELECT VARIETY, LABEL, CARGO_SIZE, WAREHOUSE_LOCATION, GROWER, PACKAGE, QTY, QTY
				FROM CHILEAN_CUSTOMER_PLT_CHANGES CCPC
				WHERE CCPC.TRANSACTION_ID = '".$trans_id."'
				AND PALLET_TO_DB_COMPARE IN ('MATCH', 'TOBEEXCHG')
				AND CT.PALLET_ID = CCPC.CT_JOIN_PALLET_ID)
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID = '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE IN ('MATCH', 'TOBEEXCHG'))";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
/*
	// and the matches-to-others, insert them...
	$sql = "INSERT INTO CARGO_TRACKING
				(VARIETY, REMARK, CARGO_SIZE, WAREHOUSE_LOCATION, CARGO_DESCRIPTION, BATCH_ID, RECEIVER_ID, ARRIVAL_NUM, PALLET_ID, 
					QTY_RECEIVED, QTY_IN_HOUSE, COMMODITY_CODE, HATCH, FUMIGATION_CODE, RECEIVING_TYPE, SOURCE_NOTE, SOURCE_USER)
			(SELECT CCPC.VARIETY, CCPC.LABEL, CCPC.CARGO_SIZE, CCPC.WAREHOUSE_LOCATION, CCPC.GROWER, CCPC.PACKAGE, '".$user_cust_num."', '".$cur_ves."', CT_JOIN_PALLET_ID, 
					QTY, QTY, CT.COMMODITY_CODE, CT.HATCH, CT.FUMIGATION_CODE, CT.RECEIVING_TYPE, 'Eport-Sort-Trans #".$trans_id."', '".$user."'
				FROM CHILEAN_CUSTOMER_PLT_CHANGES CCPC, CARGO_TRACKING CT 
				WHERE CCPC.TRANSACTION_ID = '".$trans_id."'
				AND PALLET_TO_DB_COMPARE = 'TOBEINSERT'
				AND CT.PALLET_ID = CCPC.CT_JOIN_PALLET_ID
				AND CT.ARRIVAL_NUM = '".$cur_ves."'
				AND CT.RECEIVER_ID != '".$user_cust_num."'
				AND CT.PALLET_ID IN
					(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE = 'TOBEINSERT'))";
	echo $sql."<br>";
//	exit;
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
*/
	// 4/7/2016 -- RAW INSERTS for pallets that were not in carle (or if carle hasnt shown up)
	$sql = "INSERT INTO CARGO_TRACKING
				(VARIETY, REMARK, CARGO_SIZE, WAREHOUSE_LOCATION, CARGO_DESCRIPTION, BATCH_ID, RECEIVER_ID, ARRIVAL_NUM, PALLET_ID, HATCH, RECEIVING_TYPE, 
					QTY_RECEIVED, QTY_IN_HOUSE, COMMODITY_CODE, SOURCE_NOTE, SOURCE_USER)
			(SELECT CCPC.VARIETY, CCPC.LABEL, CCPC.CARGO_SIZE, CCPC.WAREHOUSE_LOCATION, CCPC.GROWER, CCPC.PACKAGE, '".$user_cust_num."', '".$cur_ves."', CCPC.CT_JOIN_PALLET_ID, CCPC.HATCH, 'S',
					CCPC.QTY, CCPC.QTY, CCPC.COMMODITY_CODE, 'Eport-Sort-Trans #".$trans_id."', '".$user."'
				FROM CHILEAN_CUSTOMER_PLT_CHANGES CCPC 
				WHERE CCPC.TRANSACTION_ID = '".$trans_id."'
					AND PALLET_TO_DB_COMPARE = 'NOTINDB')";
//	echo $sql."<br>";
//	exit;
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);


	// update the C_T_ADDITIONAL_DATA table, to show that this pallet has already been "sorted"
	$sql = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA CTAD
			SET CHILEAN_SORT_FILE_UPLOAD = SYSDATE
			WHERE ARRIVAL_NUM = '".$cur_ves."'
			AND RECEIVER_ID = '".$user_cust_num."'
			AND PALLET_ID IN
				(SELECT CT_JOIN_PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$trans_id."' AND PALLET_TO_DB_COMPARE IN ('MATCH', 'TOBEEXCHG'))";
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