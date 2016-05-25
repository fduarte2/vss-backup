<?php
  // Cron job to auto-release elements imported from the system.
  include("pow_session.php");
  include("defines.php");
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  $cursor = ora_open($conn);

  $timestamp = date('Y-m-d H:i:s');
  $today = date('Y-m-d');

  $lr_num = $HTTP_POST_VARS['lr_num'];
  $ship_date = $HTTP_POST_VARS['new_date'];

  // check if the date is in acceptable format
  $timestamp = strtotime($ship_date);
  if ($timestamp == -1) {  // invalid date format
    die ("The sailing date you entered, $ship_date, is not in an acceptable format.\n
	  Please use the format as in the following example, 12/31/2003, and try it again");
  } else {
    $ship_date = date('m/d/Y', $timestamp);
  }

  // step 2: update cargo_tracking
  $stmt = "update cargo_tracking set date_received = to_date('$ship_date', 'MM/DD/YYYY') where lot_num in (select container_num from cargo_manifest where lr_num = '$lr_num' and cargo_mark not like 'TR*%')";
  $statement = ora_parse($cursor, $stmt);
  ora_exec($cursor);

  header("Location: index.php?input=1");
  exit;
?>
