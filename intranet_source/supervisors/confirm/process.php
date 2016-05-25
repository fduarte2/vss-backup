<?
  include("pow_session.php");
  $user = $userdata['username'];
 
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  ora_commitoff($conn);
  $cursor = ora_open($conn);

  $ship = $HTTP_POST_VARS['ship'];
  list($lr_num, $vessel_name) = split("-", $ship, 2);
  
  $sql = "delete from css_discharge where arrival_num = '$lr_num'";
  $ora_success = ora_parse($cursor, $sql);
  check_rollback($ora_success);
  $ora_success = ora_exec($cursor);
  check_rollback($ora_success);
    
  $sql = "insert into ccd_cargo_verified_damage (lot_id, arrival_num, pallet_id, mark, damage_type_id, qty_damaged)
	  select lot_id, arrival_num, pallet_id, mark, damage_type_id, qty_damaged from ccd_cargo_damaged
	  where arrival_num = '$lr_num'";
  $ora_success = ora_parse($cursor, $sql);
  check_rollback($ora_success);
  $ora_success = ora_exec($cursor);
  check_rollback($ora_success);	

  $sql = "insert into ccd_cargo_damaged_verify_log (arrival_num, user_name, time_verified) values
	  ('$lr_num','$user',sysdate)";
  $ora_success = ora_parse($cursor, $sql);
  check_rollback($ora_success);
  $ora_success = ora_exec($cursor);
  check_rollback($ora_success);

  ora_commit($conn);
  ora_close($cursor);
  ora_logoff($conn);

  header ("location: index.php");

  function check_rollback($ora_success)
  {
	global $conn, $cursor;
	if (!$ora_success){
		ora_rollback($conn);
		ora_close($cursor);
		ora_logoff($conn);
	
		echo "Confirm failed......Please contact with TS!";
		exit;
	}
  }
?>