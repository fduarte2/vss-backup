<?
  include("pow_session.php");
  $user = $userdata['username'];
 
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  ora_commitoff($conn);
  $cursor = ora_open($conn);

  $ship = $HTTP_POST_VARS['ship'];
  list($lr_num, $vessel_name) = split("-", $ship, 2);
  
  

  $sql = "delete from CCD_CARGO_DAMAGED where 
  	   	 		 ARRIVAL_NUM not in (select arrival_num from CCD_CARGO_VERIFIED_DAMAGE) 
				 and arrival_num = '$lr_num'";

  $ora_success = ora_parse($cursor, $sql);
  check_rollback($ora_success);
  $ora_success = ora_exec($cursor);
  check_rollback($ora_success);	
  
  $sql = "

insert into CCD_CARGO_DAMAGED (lot_id, arrival_num, pallet_id, mark, damage_type_id, qty_damaged)	  
  	select       D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, MIN(T.MARK),'TR' , D.TORN 	 
				   from css_discharge D, CCD_CARGO_TRACKING T 
				   where D.LOT_ID = T.LOT_ID AND D.ARRIVAL_NUM = T.ARRIVAL_NUM 
				   AND  D.arrival_num = '$lr_num' and D.TORN > 0
				   GROUP BY D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, D.TORN
				   union
	select       D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, MIN(T.MARK),'FB' ,D.FRZBURN 	 
				   from css_discharge D, CCD_CARGO_TRACKING T 
				   where D.LOT_ID = T.LOT_ID AND D.ARRIVAL_NUM = T.ARRIVAL_NUM 
				   AND  D.arrival_num = '$lr_num' and D.FRZBURN > 0
				   GROUP BY D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, D.FRZBURN  
				   union
	select       D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, MIN(T.MARK),'EP' ,D.EXPOSED 	 
				   from css_discharge D, CCD_CARGO_TRACKING T 
				   where D.LOT_ID = T.LOT_ID AND D.ARRIVAL_NUM = T.ARRIVAL_NUM 
				   AND  D.arrival_num = '$lr_num' and D.EXPOSED > 0
				   GROUP BY D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, D.EXPOSED  
				   union	
	select       D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, MIN(T.MARK),'OD' ,D.OILDMG 	 
				   from css_discharge D, CCD_CARGO_TRACKING T 
				   where D.LOT_ID = T.LOT_ID AND D.ARRIVAL_NUM = T.ARRIVAL_NUM 
				   AND  D.arrival_num = '$lr_num' and D.OILDMG > 0
				   GROUP BY D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, D.OILDMG  
				   union 
	select       D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, MIN(T.MARK),'DS' ,D.DROPSTEV 	 
				   from css_discharge D, CCD_CARGO_TRACKING T 
				   where D.LOT_ID = T.LOT_ID AND D.ARRIVAL_NUM = T.ARRIVAL_NUM 
				   AND  D.arrival_num = '$lr_num' and D.DROPSTEV > 0
				   GROUP BY D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, D.DROPSTEV  
				   union 			   
	select       D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, MIN(T.MARK),'DP' ,D.DROPPORT 	 
				   from css_discharge D, CCD_CARGO_TRACKING T 
				   where D.LOT_ID = T.LOT_ID AND D.ARRIVAL_NUM = T.ARRIVAL_NUM 
				   AND  D.arrival_num = '$lr_num' and D.DROPPORT > 0
				   GROUP BY D.LOT_ID, D.ARRIVAL_NUM, D.PALLET_ID, D.DROPPORT  
	";

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


