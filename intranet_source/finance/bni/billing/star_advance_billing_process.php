<?
/* File: star_advance_bill_process
 * 
 * Created by Richard Wang on 03/23/05
 * This program generates Star vessel advance vessel bill charges
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// define constant and variables
define("KG_TO_MT", 0.001);

$has_prebill = false;
$start_billing_num = 0;
$end_billing_num = 0;

$ship_type="Star Vessel";

$asset_code = "WG00";


// make database connections
include("connect.php");
$conn = ora_logon("SAG_OWNER@$bni", "SAG");
$cursor = ora_open($conn);

// turn auto commit off
$stmt = "ora_commitoff";
$ora_success = ora_commitoff($conn);
do_database_check($ora_success, $stmt);

$lr_nun = $HTTP_POST_VAR['lr_num'];


$has_prebill = false;

 
$sail_date = getSailingDate($lr_num);

//handling charge 
$sql = "select commodity_code, cargo_weight_unit, sum(cargo_weight) weight
	from cargo_manifest
      	where lr_num = $lr_num and cargo_mark not like 'TR*%' and commodity_code in (4592, 4384,4472,4361)
       	group by commodity_code, cargo_weight_unit
      	order by commodity_code, cargo_weight_unit";
$ora_success = ora_parse($cursor, $sql);
$ora_success = ora_exec($cursor);
do_database_check($ora_success, $sql);

$manifest = array();
while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	array_push($manifest, $row);
}

for($i = 0; $i < count($manifest); $i++){
        $commodity_code = $manifest[$i]['COMMODITY_CODE'];;
	$weight = $manifest[$i]['WEIGHT'];
	$unit = $manifest[$i]['CARGO_WEIGHT_UNIT'];
	
	if ($weight > 0){
		$sql = "select bill_to, billing_type, gl_code, service_code, rate, unit, description
			from billing_rate
			where ship_type = '$ship_type' and commodity_code = $commodity_code
			and upper(billing_type) <> 'WHARFAGE'";

		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor);
		do_database_check($ora_success, $sql);
		
		$billing_rate = array();
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			array_push($billing_rate, $row);
		}
		for($j = 0; $j < count($billing_rate); $j++){
			$bill_to = $billing_rate[$j]['BILL_TO'];
			$billing_type = $billing_rate[$j]['BILLING_TYPE'];
			$gl_code =  $billing_rate[$j]['GL_CODE'];
                        $service_code = $billing_rate[$j]['SERVICE_CODE'];
                        $rate = $billing_rate[$j]['RATE'];
                        $rate_unit = $billing_rate[$j]['UNIT'];
                        $description = $billing_rate[$j]['DESCRIPTION'];
			
			if ($gl_code =="")
				$gl_code = "null";

			$sql = "select conversion_factor from unit_conversion
				where secondary_uom = '$unit' and primary_uom = '$rate_unit'";

			$ora_success = ora_parse($cursor, $sql);
	                $ora_success = ora_exec($cursor);
			do_database_check($ora_success, $sql);

			if (ora_fetch($cursor)){
				$factor = ora_getcolumn($cursor, 0);
			}else{
				$factor = 1;
			}	

			$mt = round($weight / $factor, 2);
			$amount = round($mt * $rate, 2);
			$commodity_name = getCommodityName($commodity_code);
			$desc = $description. " ". $commodity_name. " ".$mt.$rate_unit." @$".$rate."/".$rate_unit;
			if ($amount <>0){
				generate_prebill ('Star Ves', $bill_to, $service_code, $gl_code, $asset_code, 
						  $commodity_code, $mt, $rate_unit, $rate, $amount, $desc);
			}  
		}
	}
}

if($start_billing_num > 0 && $end_billing_num >= $start_billing_num){
  $stmt = "select max(id) from invoicedate";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);

  if (ora_fetch($cursor)){
  	$id = ora_getcolumn($cursor, 0);
  	$id++;
  }
  $stmt = "insert into invoicedate (id, run_date, type, bill_type, start_inv_no, end_inv_no)
           values ('$id', sysdate, 'Star Vessel', 'B', '$start_billing_num', '$end_billing_num')";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);
}


//wharfage
$has_prebill = false;
$start_billing_num = 0;
$end_billing_num = 0;

$rate = 2.57;
$rate_unit = "MT";
$default_bill_to = 6561;
$gl_code = 3030;
$service_code = 2111;
$asset_code = "";

$asset_code = get_asset_code($lr_num);

$sql = "select distinct commodity_code 
	from cargo_manifest
	where lr_num = $lr_num and cargo_mark not like 'TR*%'
	order by commodity_code";
$ora_success = ora_parse($cursor, $sql);
$ora_success = ora_exec($cursor);
do_database_check($ora_success, $sql);

$comm = array();
while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        array_push($comm, $row);
}

for($i = 0; $i < count($comm); $i++){
	$commodity_code = $comm[$i]['COMMODITY_CODE'];
        $commodity_name = getCommodityName($commodity_code);
	$default_mt = 0;

	//get default billing rate
    	$sql = "select bill_to, billing_type, gl_code, service_code, rate, unit, description
        	from billing_rate
             	where ship_type = '$ship_type' and upper(billing_type) = 'WHARFAGE' and
              	commodity_code = $commodity_code and bill_to = $default_bill_to ";

        $ora_success = ora_parse($cursor, $sql);
        $ora_success = ora_exec($cursor);
        do_database_check($ora_success, $sql);

      	if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
       		$d_bill_to = $row['BILL_TO'];
		$d_billing_type = $row['BILLING_TYPE'];
		$d_gl_code = $row['GL_CODE'];
		$d_service_code = $row['SERVICE_CODE'];
		$d_rate = $row['RATE'];
		$d_rate_unit = $row['UNIT'];
     	}

	// go through every customer
	$sql = "select recipient_id, cargo_weight_unit, sum(cargo_weight) weight
        	from cargo_manifest
        	where lr_num = $lr_num and commodity_code = $commodity_code and cargo_mark not like 'TR*%' 
        	group by recipient_id, cargo_weight_unit
        	order by recipient_id, cargo_weight_unit";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	do_database_check($ora_success, $sql);

	$manifest = array();
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        	array_push($manifest, $row);
	}

	for($j = 0; $j < count($manifest); $j++){
		$recipient_id = $manifest[$j]['RECIPIENT_ID'];
        	$weight = $manifest[$j]['WEIGHT'];
        	$unit = $manifest[$j]['CARGO_WEIGHT_UNIT'];

		if ($weight > 0 && $recipient <> $default_bill_to){
                	$sql = "select bill_to, billing_type, gl_code, service_code, rate, unit, description
                        	from billing_rate
                        	where ship_type = '$ship_type' and upper(billing_type) = 'WHARFAGE' and 
				commodity_code = $commodity_code and bill_to = $recipient_id ";

                	$ora_success = ora_parse($cursor, $sql);
                	$ora_success = ora_exec($cursor);
                	do_database_check($ora_success, $sql);

        		if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
                		$bill_to = $row['BILL_TO'];
                		$billing_type = $row['BILLING_TYPE'];
                		$gl_code = $row['GL_CODE'];
                		$service_code = $row['SERVICE_CODE'];
                		$rate = $row['RATE'];
                		$rate_unit = $row['UNIT'];

                		$sql = "select conversion_factor from unit_conversion
                        		where secondary_uom = '$unit' and primary_uom = '$rate_unit'";

                		$ora_success = ora_parse($cursor, $sql);
                		$ora_success = ora_exec($cursor);
                		do_database_check($ora_success, $sql);

                		if (ora_fetch($cursor)){
                        		$factor = ora_getcolumn($cursor, 0);
                		}else{
                        		$factor = 1;
                		}

                		$mt = round($weight / $factor, 2);
                		$amount = round($mt * $rate, 2);
//                		$commodity_name = getCommodityName($commodity_code);
//                      $desc = "BOL: ".$bol."; ".$mt." ".$rate_unit." @".$rate."/".$rate_unit;
                		$desc = $commodity_name;
                		if ($amount <>0){
                        		generate_prebill ('S-WHARFAGE', $bill_to, $service_code, $gl_code, $asset_code,
                                       			  $commodity_code, $mt, $rate_unit, $rate, $amount, $desc);
                		}
        		}else{
				$sql = "select conversion_factor from unit_conversion
                                        where secondary_uom = '$unit' and primary_uom = '$d_rate_unit'";

                                $ora_success = ora_parse($cursor, $sql);
                                $ora_success = ora_exec($cursor);
                                do_database_check($ora_success, $sql);

                                if (ora_fetch($cursor)){
                                        $factor = ora_getcolumn($cursor, 0);
                                }else{
                                        $factor = 1;
                                }

                                $default_mt += $weight / $factor;
			}
		}
	}
	if ($default_mt > 0){
		$bill_to = $default_bill_to;
		$service_code = $d_service_code;
		$gl_code = $d_gl_code;
		$mt = round ($default_mt, 2);
		$amount = round($mt * $rate, 2);
		$desc = $commodity_name;
              	if ($amount <>0){
                	generate_prebill ('S-WHARFAGE', $bill_to, $service_code, $gl_code, $asset_code,
                                           $commodity_code, $mt, $rate_unit, $rate, $amount, $desc);
             	}
	}
}

if($start_billing_num > 0 && $end_billing_num >= $start_billing_num){
  $stmt = "select max(id) from invoicedate";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);

  if (ora_fetch($cursor)){
        $id = ora_getcolumn($cursor, 0);
        $id++;
  }
  $stmt = "insert into invoicedate (id, run_date, type, bill_type, start_inv_no, end_inv_no)
           values ('$id', sysdate, 'S-WHARFAGE', 'B', '$start_billing_num', '$end_billing_num')";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);
}

ora_commit($conn);
ora_close($cursor);
ora_logoff($conn);

header("Location: star_advance_billing.php?msg=Invoice Generation Success...");
exit;


// ----------------------------------------- FUNCTIONS -----------------------------------------

// my database error handler
function do_database_check($ora_success, $stmt) {
  global $conn, $cursor;

  if (!$ora_success) {
    printf('Database error occurred on query, "' . $stmt . '". ' .
	   "All database updates will be rollbacked.  Please report to TS!\n");

    ora_rollback($conn);
    ora_close($cursor);
    ora_logoff($conn);

    exit;
  }
}


// return the next available billing number
function getBillingNumber() {
  global $conn, $cursor;

  $stmt = "select max(billing_num) max_num from billing";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);

  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $billing_num = $row['MAX_NUM'] + 1;

  return $billing_num;
}

//return commodiyt name
function getCommodityName($commodity_code)
{
  global $conn, $cursor;
  
  $stmt = "select commodity_name from commodity_profile where commodity_code = $commodity_code";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);

  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  return $row['COMMODITY_NAME'];
}

// return vessel sail datea
function getSailingDate($lr_num) {
  global $conn, $cursor;
  
  $stmt = "select distinct to_char(date_departed, 'DD-MON-YY') sail_date from voyage 
           where lr_num = '$lr_num'";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);
  
  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);
  
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  return $row['SAIL_DATE'];
}


// generate prebill
function generate_prebill ($billing_type, $customer_id, $service_code, $gl_code, $asset_code, $commodity_code, $weight, $unit, $rate, $amount, $desc) {
  global $conn, $cursor, $lr_num, $sail_date, $has_prebill, $start_billing_num, $end_billing_num;
  
  $billing_num = getBillingNumber($bni_conn, $bni_cursor);

  $stmt = "insert into billing (lr_num, arrival_num, asset_code, billing_num, billing_type, commodity_code,
                customer_id, gl_code, service_code, service_date, service_start, service_stop, 
                service_qty, service_unit, service_rate, service_amount, service_status, service_description, 
                memo_num)
           values ($lr_num, 1, '$asset_code', $billing_num, '$billing_type', $commodity_code, 
                $customer_id, $gl_code, $service_code, '$sail_date', '$sail_date', '$sail_date', 
                $weight, '$unit', $rate, $amount, 'PREINVOICE', '$desc', '$memo')";

  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);	
  do_database_check($ora_success, $stmt);

  if ($has_prebill ==false){
	$has_prebill = true;
	$start_billing_num = $billing_num;
        $end_billing_num = $billing_num;
  }else{
	$end_billing_num = $billing_num;
  }
}

// get asset code
function get_asset_code($lr_num){
  global $conn, $cursor;

  $stmt = "select asset_code from asset_profile 
	  where description in (select 'BERTH ' || berth_num from voyage where lr_num = $lr_num)";
  $ora_success = ora_parse($cursor, $stmt);
  do_database_check($ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  do_database_check($ora_success, $stmt);

  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  return $row['ASSET_CODE'];
 
}
?>
