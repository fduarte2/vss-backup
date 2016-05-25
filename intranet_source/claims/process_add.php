<?
  include("pow_session.php");
  // Seth Morecraft  11-OCT-03
  include("connect.php");
  // Connect
/*  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  } */

	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if (!$conn) {
	 printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	 printf("Please report to TS!");
	 exit;
	}
	$cursor = ora_open($conn);
	$ex_postgres_cursor = ora_open($conn);   


  $user = $HTTP_COOKIE_VARS['claims_user'];
  // Find out the system
  $system = $HTTP_POST_VARS["system"];
  $claim_id = $HTTP_POST_VARS["claim_id"];
  if($claim_id == ""){
  // This means that we have a new claim- lets get the next val
//    $stmt = "select nextval('claim_id_seq')";
    $sql = "select CLAIM_ID_ORACLE_SEQ.NEXTVAL THE_VAL from DUAL";
//    $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
//    $rows = pg_num_rows($result);
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	if(!ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//    if($rows <0){               // query error, should not happen
      die("Error in this query: $sql. \n");
    }else{
//      $row = pg_fetch_row($result, 0);
      $claim_id = $row["THE_VAL"];
    }
  }
  // At this point, we have a new claim_id
  // Create a unique Line Number
//  $stmt = "select nextval('claim_line_id_seq')";
  $sql = "select CLAIM_LINE_ID_SEQ.NEXTVAL THE_VAL from DUAL";
//  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));
//  $rows = pg_num_rows($result);
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	if(!ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//  if($rows <0){               // query error, should not happen
    die("Error in this query: $stmt. \n");
  }else{
//    $row = pg_fetch_row($result, 0);
    $line_id = $row["THE_VAL"];
  }

  // Get all of the values- who cares about system>???
  $customer_id = $HTTP_POST_VARS["customer_id"];
  $vessel_name = $HTTP_POST_VARS["vessel_name"];
  $voyage = $HTTP_POST_VARS["voyage"];
  if($voyage == ""){
    $voyage = " ";
  }
  $ccd_lot_id = $HTTP_POST_VARS["ccd_lot_id"];
  if($ccd_lot_id == ""){
    $ccd_lot_id = "0";
  }
  $ccd_mark = $HTTP_POST_VARS["mark"];
  $vessel_bl = $HTTP_POST_VARS["vessel_bl"];
  if($vessel_bl == ""){
    $vessel_bl = "0";
  }
  $bl = $HTTP_POST_VARS["bl"];
  if($bl == ""){
    $bl = "0";
  }
  $bni_mark = $HTTP_POST_VARS["bni_mark"];
  $pallet_id = $HTTP_POST_VARS["pallet_id"];
  if($pallet_id == ""){
    $pallet_id = "0";
  }
  $claim_date = $HTTP_POST_VARS["claim_date"];
  $received_date = $HTTP_POST_VARS["received_date"];
  $invoice_num = $HTTP_POST_VARS["invoice_num"];
  if($invoice_num == "")
    $invoice_num = 0;
  $quantity = $HTTP_POST_VARS["quantity"];
  if($quantity == "")
    $quantity = 0;
  $unit = $HTTP_POST_VARS["unit"];
  $weight = $HTTP_POST_VARS["weight"];
  if($weight == "")
    $weight = 0;
  $cost = $HTTP_POST_VARS["cost"];
  if($cost == "")
    $cost = 0;
  $claimed = $HTTP_POST_VARS["claimed"];
  if($claimed == "")
    $claimed = 0;
  $denied = $HTTP_POST_VARS["denied"];
  if($denied == "")
    $denied = 0;
  $denied_qty = $HTTP_POST_VARS["denied_qty"];
  if($denied_qty == "")
    $denied_qty = 0;
  $notes = $HTTP_POST_VARS["notes"];
  $notes = addslashes($notes);
  $internal_notes = $HTTP_POST_VARS["internal_notes"];
  $internal_notes = addslashes($internal_notes);
  $letter_type = $HTTP_POST_VARS["letter_type"];
  $shipper = $HTTP_POST_VARS["shipper"];
  if($shipper == "")
    $shipper = 0;
  $ship_type = $HTTP_POST_VARS["ship_type"];
  $product_name = $HTTP_POST_VARS["product_name"];
  $ccd_cut = $HTTP_POST_VARS["ccd_cut"];
  $amount = $HTTP_POST_VARS["amount"];
  if($amount == "")
    $amount = 0;
  $shipper_amount = $HTTP_POST_VARS["shipper_amount"];
  if($shipper_amount == ""){
    $shipper_amount = "0";
  }
  $claim_type = $HTTP_POST_VARS["claim_type"];
  $exporter = $HTTP_POST_VARS["exporter"];
  $include_denied = $HTTP_POST_VARS["include_denied"];
  if($include_denied == "on"){
    $include_denied = "t";
  }
  else{
    $include_denied = "f";
  }
  $gallons = $HTTP_POST_VARS["gallons"];
  if($gallons == ""){
    $gallons = 0;
  }

  if($pallet_id == "LABOR INVOICE" || $pallet_id == "MISC"){
    $product_name = "";
  }

  $claim_date = date('m/d/Y', strtotime($claim_date));
  $received_date = date('m/d/Y', strtotime($received_date));
  $entered_date = date('m/d/Y');

  $sql = "insert into claim_log_oracle 
				(CLAIM_ID, 
				VESSEL_NAME, 
				VOYAGE, 
				CLAIM_DATE, 
				CUSTOMER_INVOICE_NUM, 
				CUSTOMER_ID, 
				AMOUNT, 
				CCD_LOT_ID, 
				CCD_MARK, 
				RF_PALLET_ID, 
				BNI_BL, 
				BNI_MARK, 
				VESSEL_BL, 
				QUANTITY, 
				WEIGHT, 
				COST, 
				QUANTITY_CLAIMED, 
				DENIED_QUANTITY, 
				NOTES, 
				SYSTEM, 
				COMPLETED, 
				UNIT, 
				LINE_ID, 
				INTERNAL_NOTES, 
				LETTER_TYPE, 
				SHIPPER_QTY, 
				VESSEL_TYPE, 
				PRODUCT_NAME, 
				CCD_CUT, 
				SHIPPER_AMOUNT, 
				RECEIVED_DATE, 
				ENTERED_DATE, 
				INCLUDE_DEINED, 
				GALLONS, 
				CLAIM_TYPE, 
				DENIED_QTY, 
				EXPORTER) 
			values 
				('$claim_id', 
				'$vessel_name', 
				'$voyage', 
				TO_DATE('$claim_date', 'MM/DD/YYYY'),
				'$invoice_num', 
				'$customer_id', 
				'$amount', 
				'$ccd_lot_id', 
				'$ccd_mark', 
				'$pallet_id', 
				'$bl', 
				'$bni_mark', 
				'$vessel_bl', 
				'$quantity', 
				'$weight', 
				'$cost', 
				'$claimed', 
				'$denied', 
				'$notes', 
				'$system', 
				'f', 
				'$unit', 
				'$line_id', 
				'$internal_notes', 
				'$letter_type', 
				'$shipper', 
				'$ship_type', 
				'$product_name', 
				'$ccd_cut', 
				'$shipper_amount', 
				TO_DATE('$received_date', 'MM/DD/YYYY'), 
				TO_DATE('$entered_date', 'MM/DD/YYYY'),
				'$include_denied', 
				'$gallons', 
				'$claim_type', 
				'$denied_qty', 
				'$exporter')";
//	echo $sql."<br>";
//  $result = pg_query($conn, $stmt);
	ora_parse($ex_postgres_cursor, $sql);
	$success = ora_exec($ex_postgres_cursor);
  if (!$success) {
    printf("Error in query: $sql");
//    pg_query($conn, "rollback");
    exit;
  }
  header("Location: add.php?claim_id=$claim_id&system=$system");
  exit;
?>
