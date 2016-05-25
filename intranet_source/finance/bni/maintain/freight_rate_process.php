<?

/* File: freight_rate_process.php
 * 
 * Created by Lynn F. Wang on 11/5/2004
 * This program updates Holmen Freifht rate table
 *
 */

include("pow_session.php");
$user = $userdata['username'];

// include the database_check function, database_check($ora_conn, $ora_success, $stmt)
include("billing_functions.php");

// here come our form array variables
// $surcharge, $destination, $carrier, $rating, $fixed

// make database connections
include("connect.php");
$conn = ora_logon("PAPINET@$rf", "OWNER");
$cursor = ora_open($conn);

// turn auto commit off
$stmt = "ora_commitoff";
$ora_success = ora_commitoff($conn);
database_check($conn, $ora_success, $stmt);

$curr_list = array();   // current list of destination and carrier
$orig_list = array();   // original list of destination and carrier

// get original rate information
$stmt = "select * from freight_rate_matrix order by destination, carrier";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);

while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
  array_push($orig_list, array('destination'=>$row['DESTINATION'], 'carrier'=>$row['CARRIER']));
}

// process new rate line items
for ($i=0; $i<count($destination); $i++) {
  // for each line item

  // clean up form values
  $destination[$i] = trim($destination[$i]);
  $carrier[$i] = trim($carrier[$i]);
  $fixed[$i] = trim($fixed[$i]);
  $rating[$i] = trim($rating[$i]);

  // ignore if either destination or carrier is not specified
  if ($destination[$i] == "" || $carrier[$i] == "") {
    continue;
  }

  // keep a record of current line item
  $curr_pair = array('destination'=>$destination[$i], 'carrier'=>$carrier[$i]);
  array_push($curr_list, $curr_pair);

  // update the amounts
  if (in_array($curr_pair, $orig_list)) {
    $stmt = "update freight_rate_matrix set carrier_rating = " . $rating[$i] . ", fixed_rate = " . $fixed[$i] . 
      ", surcharge_percent = " . $surcharge . " where destination = '" . $destination[$i] . 
      "' and carrier = '" . $carrier[$i] . "'";
  } else {
    $stmt = "insert into freight_rate_matrix values ('" . $destination[$i] . "', '" . $carrier[$i] . "', " . 
      $rating[$i] . ", " . $fixed[$i] . ", " . $surcharge . ", 1)";
  }

  $ora_success = ora_parse($cursor, $stmt);
  database_check($conn, $ora_success, $stmt);
  
  $ora_success = ora_exec($cursor);	
  database_check($conn, $ora_success, $stmt);
}

// delete line item from the rate table that is not on the new list
foreach ($orig_list as $arr_value) {
  if (!in_array($arr_value, $curr_list)) {
    // get current destination and carrier
    while(list($curr_key, $curr_value) = each($arr_value)) {
      if ($curr_key == 'destination') {
	$curr_destination = $curr_value;
      }

      if ($curr_key == 'carrier') {
	$curr_carrier = $curr_value;
      }
    }

    $stmt = "delete from freight_rate_matrix where destination = '$curr_destination' and carrier = '$curr_carrier'";
    $ora_success = ora_parse($cursor, $stmt);
    database_check($conn, $ora_success, $stmt);
    
    $ora_success = ora_exec($cursor);	
    database_check($conn, $ora_success, $stmt);
  }
}

ora_commit($conn);
ora_logoff($conn);

header("Location: freight_rate.php?feedback=1");
exit;

?>
