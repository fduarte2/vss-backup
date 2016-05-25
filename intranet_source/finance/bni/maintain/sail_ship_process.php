<?
  /* File: sail_ship_process.php
   *
   * Lynn F. Wang, 13-SEP-04
   * Set vessel arrival date, departure date and free time end date for selected vessel
   */

  include("pow_session.php");

  // include database_check function
  include("billing_functions.php");

  // Get all of the forms changes
  $lr_num = trim($HTTP_POST_VARS["lr_num"]);
  $arrival_date = trim($HTTP_POST_VARS["arrival_date"]);
  $sailing_date = trim($HTTP_POST_VARS["sailing_date"]);

  // validate arrival date
  $timestamp = strtotime($arrival_date);
  if ($timestamp == -1) {  // invalid date format
    die ("The arrival date you entered, $arrival_date, is not in an acceptable format.\n
	  Please use the format as in the following example, 12/31/2004, and try it again");
  } else {
    $arrival_date = date('m/d/Y', $timestamp);
  }

  // validate sailing date
  $timestamp = strtotime($sailing_date);
  if ($timestamp == -1) {  // invalid date format
    die ("The sailing date you entered, $sailing_date, is not in an acceptable format.\n
	  Please use the format as in the following example, 12/31/2004, and try it again");
  } else {
    $sailing_date = date('m/d/Y', $timestamp);
  }

  // calculate free time end date
  $free_time_end = date("m/d/Y", mktime(0, 0, 0, date("m", strtotime($sailing_date)), 
					date("d", strtotime($sailing_date)) + 31, 
					date("Y", strtotime($sailing_date))));
  // Connect
  $conn = ora_logon("PAPINET@RF", "OWNER");
  if(!$conn){
    echo "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    exit;
  }
  $cursor = ora_open($conn);

  // turn auto commit off
  $stmt = "ora_commitoff";
  $ora_success = ora_commitoff($conn);
  database_check($conn, $ora_success, $stmt);

  // update vessel profile
  $stmt = "update vessel_profile 
           set arrival_date = to_date('$arrival_date', 'MM/DD/YYYY'), 
               ship_sail_date = to_date('$sailing_date', 'MM/DD/YYYY'),
               free_time_end = to_date('$free_time_end', 'MM/DD/YYYY')
           where pow_arrival_num = '$lr_num'";

  $ora_success = ora_parse($cursor, $stmt);
  database_check($conn, $ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  database_check($conn, $ora_success, $stmt);

  // update cargo tracking
  $stmt = "update cargo_tracking set free_time_end = to_date('$free_time_end', 'MM/DD/YYYY') 
           where pow_arrival_num = '$lr_num'";

  $ora_success = ora_parse($cursor, $stmt);
  database_check($conn, $ora_success, $stmt);

  $ora_success = ora_exec($cursor);
  database_check($conn, $ora_success, $stmt);
  
  // commit the updates
  ora_commit($conn);
  ora_logoff($conn);

  // back to previous page
  header("Location: sail_ship.php?in_lr_num=$lr_num");

?>
