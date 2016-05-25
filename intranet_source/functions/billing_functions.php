<?
// Function definitions for billing
include("compareDate.php");

// Return the 1st Prebill of this run
function getCCDSPrebillNumber() {
  $year = date('Y');
  $month = date('m');
  
  if ($month == '01') {
    $prebill_num = $year - 1 . '12' . '001';
  } else {
    $prebill_num = $year . $month - 1 . '001';
  }

  return $prebill_num;
}


// Return the 1st Prebill of this run
function getPrebillNumber() {
//  $year = date('Y');
	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");  
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
	$sql = "SELECT MAX(SUBSTR(INVOICE_NUM, 3, 2)) THE_YEAR
			FROM BILLING";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$temp_year = ociresult($stid, "THE_YEAR");
	$year = date('Y', mktime(0,0,0,date('m'), date('d'), $temp_year));

  $month = date('m');
  $prebill_num = $year . $month . '001';

  return $prebill_num;
}


// Return the 1st Invoice of this run
function getInvoiceNumber($ora_conn, $cursor) {

  $stmt = "select max(invoice_num) max_num from billing";
  $ora_success = ora_parse($cursor, $stmt);
  database_check($ora_conn, $ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  database_check($ora_conn, $ora_success, $stmt);

  ora_fetch_into($cursor, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//	$current_fiscal_year = date('y', mktime(0,0,0,date('m'), date('d') + 182, date('Y')));
//	$current_fiscal_year = date('y', mktime(0,0,0,date('m'), date('d'), date('Y')));

//	$first_invoice_of_FY = '98'.$current_fiscal_year.'00001';

//	if($row2['MAX_NUM'] < $first_invoice_of_FY){
//		$invoice_num = $first_invoice_of_FY;
//	} else {
		$invoice_num = $row2['MAX_NUM'] + 1;
//	}

	return $invoice_num;


}


// Return the next available billing number
function getBillingNumber($ora_conn, $cursor) {

  $stmt = "select max(billing_num) max_num from billing";
  $ora_success = ora_parse($cursor, $stmt);
  database_check($ora_conn, $ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  database_check($ora_conn, $ora_success, $stmt);

  ora_fetch_into($cursor, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $billing_num = $row2['MAX_NUM'] + 1;

  return $billing_num;
}


// save the invoices to a safe place for reprinting, and
// name the file with the starting invoice number
function redirect_invoices ($pdf, $start_inv_num, $end_inv_num, $billing_type) {
  $pdfcode = $pdf->ezOutput();
  
  $dir = '/var/www/html/invoices';
  if (!file_exists($dir)) {
    mkdir ($dir, 0775);
  }
  
  $bill_type_id = getBillType($billing_type);
  $fname = $dir . '/' . $start_inv_num . '-' . $end_inv_num . '-' . $bill_type_id . '.pdf';

  $fp = fopen($fname, 'w');
  fwrite($fp, $pdfcode);
  fclose($fp);
  
  list($junk1, $junk2, $junk3, $junk4, $junk5, $filename) = split("/", $fname);
  header("Location: /invoices/$filename");
}


//------------------Notice---------------------------------------------------|
//  You do not need to supply your own connection to teh database to these   |
//  functions.  They will use the live DB and thier own connect string       |
//  use the live DB and thier own connect string                             |
//---------------------------------------------------------------------------|

/**
 * Takes a type_id from the table billing_type and returns a name
 */
function getBillName($type){
//  include("/web/web_pages/functions/connect.php");
//  $conn = pg_connect("host=$host dbname=$db user=$dbuser");

$bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//$bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
if (!$bni_conn) {
  printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($bni_conn));
  printf("Please report to TS!\n");
  exit;
}
$cursor = ora_open($bni_conn);

	$sql = "select TYPE_NAME from billing_type where type_id = '".$type."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$return = "Unknown Billing Type";  // with type_id = 0;
	} else {
		$return = $row['TYPE_NAME'];
	}
/*
	$result = pg_query($conn, $sql);
	$rows = pg_num_rows($result);

	if ($rows > 0) {
	$row = pg_fetch_array($result, 0, PGSQL_ASSOC);
		$return = $row['type_name'];
	} else {
		$return = "Unknown Billing Type";  // with type_id = 0;
	}
*/
	return $return;
}


/**
 * Takes a name from the table billing_type and returns a type_id
 */
function getBillType($name){
//  include("/web/web_pages/functions/connect.php");
//  $conn = pg_connect("host=$host dbname=$db user=$dbuser");
$bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
//$bni_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
if (!$bni_conn) {
  printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($bni_conn));
  printf("Please report to TS!\n");
  exit;
}
$cursor = ora_open($bni_conn);

	$sql = "select TYPE_ID from billing_type where type_name = '".$name."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	    $return = 0;
	} else {
	    $return = $row['TYPE_ID'];
	}
/*
  $result = pg_query($conn, $sql);
  $rows = pg_num_rows($result);

  if ($rows > 0) {
    $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
    $return = $row['type_id'];
  } else {
    // type id 0 for Unknown Billing Type
    $return = 0;
  }
*/
  return $return;
}


// return conversion factor given the primary and secondary unit of measure
function getConversionFactor($cursor, $primary_uom, $secondary_uom) {
  // clean up the inputs
  $primary_uom = trim($primary_uom);
  $secondary_uom = trim($secondary_uom);

  $stmt = "select * from unit_conversion where primary_uom = '$primary_uom' and secondary_uom = '$secondary_uom'";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rows = ora_numrows($cursor);
  
  if ($rows <= 0) {
    $factor = -1;
  } else {
    $factor = trim($row['CONVERSION_FACTOR']);
  }

  return $factor;
}


// return Vessel Name of a given LR #
function getVesselName($cursor, $lr_num) {

//	if($lr_num == ""){
//		echo "eh?";
//		exit;
//	}
  $stmt = "select * from vessel_profile where lr_num = '$lr_num'";
//  if($lr_num == ""){
//	  echo $stmt;
//	  exit;
//  }
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
/*  if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	$vessel_name = "UKN";
  } else {
	$vessel_name = strtoupper(trim($row['VESSEL_NAME']));
  }*/
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $vessel_name = strtoupper(trim($row['VESSEL_NAME']));
  return $vessel_name;
}


// return Customer Information of a given customer ID
function getCustomerInfo($ora_conn, $cursor, $customer_id, $type="default") {

  // get information from database
  $stmt = "select * from customer_profile where customer_id = $customer_id";
  $ora_success = ora_parse($cursor, $stmt);
  database_check($ora_conn, $ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  database_check($ora_conn, $ora_success, $stmt);
  ora_fetch_into($cursor, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  // get customer name without customer id and then put customer id afterwards
  list($code, $customer_name) = split("-", $row2['CUSTOMER_NAME'], 2);
  $code = trim($code);
  $customer_name = trim($customer_name);

  if (!is_numeric($code)) {
    $customer_name = trim($row2['CUSTOMER_NAME']);
  }

  $customer_info = $customer_name . "   " . $customer_id;

  $address1 = trim($row2['CUSTOMER_ADDRESS1']);
  $address2 = trim($row2['CUSTOMER_ADDRESS2']);

  if($type=="claims"){
	  // apparently, WALMART doesnt like its claims going to their billing address?
	  // so we modify this so that if a "claims adddress" exists, use it.
	  if(trim($row2['CUSTOMER_CLAIM_ADDRESS1']) != ""){
		  $address1 = trim($row2['CUSTOMER_CLAIM_ADDRESS1']);
		  $address2 = trim($row2['CUSTOMER_CLAIM_ADDRESS2']);
	  }
  }

  if ($address1 != "") {
    $customer_info .= "\n" . $address1;
  }
  if ($address2 != "") {
    // put SUITE info right after address 1
    if ((strpos($address2, 'SUITE') === 0) && 
	(strlen($address2) <= 10)) {
      $customer_info .= ", " . $address2;
    } else {
      $customer_info .= "\n" . $address2;
    }
  }

  $city = trim($row2['CUSTOMER_CITY']);
  if($type=="claims"){
	  if(trim($row2['CUSTOMER_CLAIM_CITY']) != ""){
		  $city = trim($row2['CUSTOMER_CLAIM_CITY']);
	  }
  }

  if ($city != "") {
    $customer_info .= "\n" . $city . ", ";
  }

  $state = trim($row2['CUSTOMER_STATE']);
  if($type=="claims"){
	  if(trim($row2['CUSTOMER_CLAIM_STATE']) != ""){
		  $state = trim($row2['CUSTOMER_CLAIM_STATE']);
	  }
  }

  if ($state != "") {
    $customer_info .= $state . " ";
  }

  $zip = trim($row2['CUSTOMER_ZIP']);
  if($type=="claims"){
	  if(trim($row2['CUSTOMER_CLAIM_ZIP']) != ""){
		  $zip = trim($row2['CUSTOMER_CLAIM_ZIP']);
	  }
  }

  if ($zip != "") {
    $customer_info .= $zip;
  }

  $country_code = trim($row2['COUNTRY_CODE']);
  if ($country_code != "" && $country_code != "US") {
    $country_name = getCountryName($cursor, $country_code);
    $customer_info .= "\n" . $country_name;
  }

  $customer_info = strtoupper($customer_info);
  return $customer_info;
}


// return Country Name of a given Country Code
function getCountryName($cursor, $country_code) {

  $stmt = "select * from country where country_code = '$country_code'";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  return trim($row['COUNTRY_NAME']);
}


// return BNI storage rate information
function getStorageDays($cursor, $lot_num, $service_start) {
  // get free time end date
  $stmt = "select * from cargo_tracking where lot_num = '$lot_num'";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $free_time_end = date("m/d/Y", strtotime($row['FREE_TIME_END']));
  $service_start = date("m/d/Y", strtotime($service_start));
  $num_days = compareDate($service_start, $free_time_end) + 1;  // free_time_end day is the 1st day

  return $num_days;
}


// return BNI storage rate information
// Edit, Adam Walter, May 2007.  As it is now decided, CUSTOMER_NUM should play a role in determining
// the service rate.  Only 2 active pages (as of this re-write) use this function, so it shouldn't be
// THAT hard to pull off... (print_prebill_process.php and run_invoice_process.php, both in finance/bni/billing)
// --- MAJOR EDIT, FEB 2010
// BNI Storage has been *completely* rewritten, so this function had to change to read the new table.
// said new table uses defined priorities, so I get to eliminate the whole "customer first vessel second" crap-ola from here :)
// I've deleted (rather than commenting) the old function, as with the new table in place, the old code would be bad to go back to.
function getStorageRateInfo($cursor, $lr_num, $service_code, $commodity_code, $rate, $unit, $num_days, $billing_num, $customer_id) {

  // clean up the unit
	$unit = trim(strtoupper($unit));


	$sql = "SELECT * FROM RATE WHERE
			RATE = '".$rate."' AND
			(CUSTOMERID = '".$customer_id."' OR CUSTOMERID IS NULL OR BILLTOCUSTOMER = '".$customer_id."') AND
			(COMMODITYCODE = '".$commodity_code."' OR COMMODITYCODE IS NULL) AND
			(ARRIVALNUMBER = '".$lr_num."' OR ARRIVALNUMBER IS NULL) AND
			RATESTARTDATE <= GREATEST(".($num_days).", 0) AND
			SERVICECODE = '".$service_code."'
			ORDER BY RATEPRIORITY ASC, RATESTARTDATE DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo $sql."<br><br>";
		printf("The service rate of Prebill (with Billing #: $billing_num ) is $" . $rate . ", which is not found 
			  in the storage rate table for Service - $service_code and Commodity - $commodity_code. Please edit the prebill 
			  to reflect the updated rate or change the rate back temporarily to justify this prebill and try again!\n");
		exit;
	} else {
		// Billing asked for 2 digits after the decimal point
		$rate = number_format($rate, 2, '.', '');

		if ($row['BILLDURATION'] > 1) {
			$rate_info = "$".$rate." / ".trim($row['UNIT'])." / ".$row['BILLDURATION']." ".$row['BILLDURATIONUNIT']."S";
		} else {
//			$rate_info = "$" . "$rate / " . trim($row['UNIT']) . " / " . $duration . " " . $duration_unit;
			$rate_info = "$".$rate." / ".trim($row['UNIT'])." / ".$row['BILLDURATION']." ".$row['BILLDURATIONUNIT'];
		}

		$rate_info .= " OR FRACTION THEREOF";

		return $rate_info;
	}
}


// return BNI vessel rate information
function getVesselRateInfo($cursor, $service_code, $commodity_code, $rate, $billing_num) {

  if (trim($commodity_code) == "") {
    $commodity_code = 0;
  }

  $stmt = "select * from vessel_rate where service_code = $service_code and commodity_code = $commodity_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rows = ora_numrows($cursor);
  
  // the rate for commodity_code 0 is the default
  if ($rows <= 0) {
    $stmt = "select * from vessel_rate where service_code = $service_code and commodity_code = 0";
    ora_parse($cursor, $stmt);
    ora_exec($cursor);
    ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  }

  /* disable the checking of the rate  -- LFW, 3/26/04 Per Jean
  else {
    if ($rate != $row['SERVICE_RATE']) {
      // the rate was updated
      $service = getServiceName($cursor, $service_code);
      $commodity = getCommodityName($cursor, $commodity_code);

      printf("The service rate of Prebill (with Billing #: $billing_num ), $" . $rate . ", is not equal to " . 
	     $row['SERVICE_RATE'] . ", which is the rate in the vessel rate table for Service - $service and 
             Commodity - $commodity. Please edit the prebill to reflect the updated rate or change the rate 
             back temporarily to justify this prebill and try again!\n");
      exit;
    }
  }
  */

  $unit1 = trim($row['UNIT1']);
  $unit2 = trim($row['UNIT2']);

  if ($unit1 != 'EA') {
    $rate_info = "$" . number_format($rate, 2, '.', ',') . " / " . $unit1;
  } else {
    $rate_info = "$" . number_format($rate, 2, '.', ',') . " EACH";
  }

  if ($unit2 != "") {
    $rate_info .= " / " . $unit2;
  } 

  if ($row['MINIMUM_CHARGE'] > 0) {
    $rate_info .= ",  MINIMUM CHARGE OF $" . number_format($row['MINIMUM_CHARGE'], 2, '.', ',');
  }

  return $rate_info;
}


// return BNI vessel rate information
function getVesselRateUnit1($cursor, $service_code, $commodity_code) {

  if (trim($commodity_code) == "") {
    $commodity_code = 0;
  }

  $stmt = "select * from vessel_rate where service_code = $service_code and commodity_code = $commodity_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rows = ora_numrows($cursor);
  
  if ($rows <= 0) {
    $stmt = "select * from vessel_rate where service_code = $service_code and commodity_code = 0";
    ora_parse($cursor, $stmt);
    ora_exec($cursor);
    ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  }

  $unit1 = trim($row['UNIT1']);

  return $unit1;
}


// return service name with service code for given service code
function getServiceName($cursor, $service_code) {

  $stmt = "select * from service_category where service_code = $service_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  return trim($row['SERVICE_NAME']);
}


// return service name without service code for given service code
function getServiceNameNoCode($cursor, $service_code) {

  $stmt = "select * from service_category where service_code = $service_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  $has_hyphen = strpos($row['SERVICE_NAME'], "-");
  if ($has_hyphen === false) {
    $service_name = $row['SERVICE_NAME'];
  } else {
    list($code, $service_name) = split("-", $row['SERVICE_NAME'], 2);
  }

  $service_name = trim($service_name);
  return $service_name;
}


// return commodity name for given commodity code
function getCommodityName($cursor, $commodity_code) {

  $stmt = "select * from commodity_profile where commodity_code = $commodity_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  return trim($row['COMMODITY_NAME']);
}


// return commodity name for given commodity code
function getCommodityNameNoCode($cursor, $commodity_code) {

  $stmt = "select * from commodity_profile where commodity_code = $commodity_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  $has_hyphen = strpos($row['COMMODITY_NAME'], "-");
  if ($has_hyphen === false) {
    $commodity_name = $row['COMMODITY_NAME'];
  } else {
    list($code, $commodity_name) = split("-", $row['COMMODITY_NAME'], 2);
  }

  $commodity_name = trim($commodity_name);
  return $commodity_name;
}


// return commodity unit for given commodity code
function getCommodityUnit($cursor, $commodity_code) {

  $stmt = "select * from commodity_profile where commodity_code = $commodity_code";
  ora_parse($cursor, $stmt);
  ora_exec($cursor);
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  
  return trim($row['COMMODITY_UNIT']);
}


// Database error handler
function database_check($ora_conn, $ora_success, $stmt) {
  if (!$ora_success) {
    printf('Database error occurred on query, "' . $stmt . '". ' .
	   "All database updates will be rollbacked.  Please report to TS!\n");
    ora_rollback($ora_conn);
    ora_logoff($ora_conn);
    exit;
  }
}



// database error handler for two databases
function two_database_check($bni_conn, $rf_conn, $ora_success, $stmt) {

  if (!$ora_success) {
    printf('Database error occurred on query, "' . $stmt . '". ' .
	   "All database updates will be rollbacked.  Please report to TS!\n");

    ora_rollback($rf_conn);
    ora_logoff($rf_conn);

    ora_rollback($bni_conn);
    ora_logoff($bni_conn);

    exit;
  }
}

// Gets the customer list from BNI in a user-friendly array
// try printing it with list($customer_name, $customer_id) = each($customer)
function getCustomerList($bni_cursor, $claims=""){
 if($claims == "Y"){
   $stmt = "select customer_id, customer_name from customer_profile where customer_id >= 3 and customer_status = 'ACTIVE' and claims = 'Y' order by customer_name";
 }
 else if($claims == "N"){
   $stmt = "select customer_id, customer_name from customer_profile where customer_id >= 3 and customer_status = 'ACTIVE' and (claims != 'Y' or claims is null) order by customer_name";
 }
 else{
   $stmt = "select customer_id, customer_name from customer_profile where customer_id >= 3 and customer_status = 'ACTIVE' order by customer_name";
  }
  ora_parse($bni_cursor, $stmt);
  ora_exec($bni_cursor);
  $customer = array();
  while (ora_fetch($bni_cursor)){
    $temp_customer_id = ora_getcolumn($bni_cursor, 0);
    $temp_customer_name = ora_getcolumn($bni_cursor, 1);
    if (strpos($temp_customer_name, "-") !== FALSE){
      list($junk, $new_customer_name) = split("-", $temp_customer_name, 2);
    } 
    else{
      $new_customer_name = $temp_customer_name;
    }
    $customer[$new_customer_name] = $temp_customer_id;
  }
  ksort($customer);
  return $customer;
}

?>
