<?
  include("pow_session.php");
  // Seth Morecraft  11-OCT-03
  include("connect.php");
  // Connect
/*  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }
*/
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
  $line_id = $HTTP_POST_VARS["line_id"];

  // Get all of the values- who cares about system>???
  $customer_id = $HTTP_POST_VARS["customer_id"];
  $vessel_name = $HTTP_POST_VARS["vessel_name"];
  $voyage = $HTTP_POST_VARS["voyage"];
  $ccd_lot_id = $HTTP_POST_VARS["ccd_lot_id"];
  if($ccd_lot_id == ""){
    $ccd_lot_id = "0";
  }
  $ccd_mark = $HTTP_POST_VARS["mark"];
  $vessel_bl = $HTTP_POST_VARS["vessel_bl"];
  $bl = $HTTP_POST_VARS["bl"];
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
  $amount = $HTTP_POST_VARS["amount"];
  if($amount == "")
    $amount = 0;
  $shipper_amount = $HTTP_POST_VARS["shipper_amount"];
  if($shipper_amount == "")
    $shipper_amount = 0;
  $claim_type = $HTTP_POST_VARS["claim_type"];
  $exporter = $HTTP_POST_VARS["exporter"];
  $include_denied = $HTTP_POST_VARS["include_denied"];
  if($include_denied == "on"){
    $include_denied = "t";
  }
  else{
    $include_denied = "f";
  }

  if($pallet_id == "LABOR INVOICE"){
    $product_name = "";
  }

  $claim_date = date('m/d/Y', strtotime($claim_date));
  $received_date = date('m/d/Y', strtotime($received_date));

  $sql = "update claim_log_oracle set 
				CLAIM_DATE = TO_DATE('$claim_date', 'MM/DD/YYYY'),
				RECEIVED_DATE = TO_DATE('$received_date', 'MM/DD/YYYY'),
				CUSTOMER_INVOICE_NUM = '$invoice_num', 
				AMOUNT = '$amount', 
				VESSEL_BL = '$vessel_bl', 
				QUANTITY = '$quantity', 
				WEIGHT = '$weight', 
				COST = '$cost', 
				QUANTITY_CLAIMED = '$claimed', 
				DENIED_QUANTITY = '$denied', 
				NOTES = '$notes', 
				UNIT = '$unit', 
				INTERNAL_NOTES = '$internal_notes', 
				SHIPPER_QTY = '$shipper', 
				LETTER_TYPE = '$letter_type', 
				SHIPPER_AMOUNT = '$shipper_amount', 
				CLAIM_TYPE = '$claim_type', 
				VOYAGE = '$voyage', 
				INCLUDE_DEINED = '$include_denied', 
				DENIED_QTY = '$denied_qty', 
				EXPORTER = '$exporter' 
			where CLAIM_ID = '$claim_id' 
				and LINE_ID = '$line_id'";
//  $result = pg_query($conn, $stmt);
	ora_parse($ex_postgres_cursor, $sql);
	$success = ora_exec($ex_postgres_cursor);
  if (!$success) {
    printf("Error in query: $sql");
//    pg_query($conn, "rollback");
    exit;
  }
  header("Location: edit.php?edit_claim_id=$claim_id");
  exit;
?>
