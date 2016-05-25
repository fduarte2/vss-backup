<?
  // All POW files need this session file included
  include("pow_session.php");
  include("utility.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Claim";
  $area_type = "CLAI";

  //get database connection
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $rf_conn = ora_logon("SAG_OWNER@$rf", "OWNER");
  if (!$rf_conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $rf_cursor = ora_open($rf_conn);

  $claim_header = "claim_header_rf";
  $claim_body = "claim_body_rf";

  $pallet_id = $HTTP_POST_VARS['pallet_id'];
  $season = $HTTP_POST_VARS['season'];
  $arrival_num = $HTTP_POST_VARS['arrival_num'];
  $claim_body_id = $HTTP_POST_VARS['claim_body_id'];
  $product = $HTTP_POST_VARS['product'];
  $vessel = $HTTP_POST_VARS["vessel"];
  $voyage = $HTTP_POST_VARS["voyage"];
  $bol = $HTTP_POST_VARS["bol"];
  $claim_qty = $HTTP_POST_VARS["claim_qty"];
  if($claim_qty == "")
    $claim_qty = 0;
  $claim_unit = $HTTP_POST_VARS["claim_unit"];
  $claim_price = $HTTP_POST_VARS["claim_price"];
  if($claim_price == "")
    $claim_price = 0;
  $claim_amt = $HTTP_POST_VARS["claim_amt"];
  if($claim_amt == "")
    $claim_amt = 0;
  $port_qty = $HTTP_POST_VARS["port_qty"];
  if($port_qty == "")
    $port_qty = 0;
  $port_amt = $HTTP_POST_VARS["port_amt"];
  if($port_amt == "")
    $port_amt = 0;
  $denied_qty = $HTTP_POST_VARS["denied_qty"];
  if($denied_qty == "")
    $denied_qty = 0;
  $denied_amt = $HTTP_POST_VARS["denied_amt"];
  if($denied_amt == "")
    $denied_amt = 0;
  $ship_line_qty = $HTTP_POST_VARS["ship_line_qty"];
  if($ship_line_qty == "")
    $ship_line_qty = 0;
  $ship_line_amt = $HTTP_POST_VARS["ship_line_amt"];
  if($ship_line_amt == "")
    $ship_line_amt = 0;
  $notes = $HTTP_POST_VARS["notes"];
  $notes = OraSafeString($notes);
  $internal_notes = $HTTP_POST_VARS["internal_notes"];
  $internal_notes = OraSafeString($internal_notes);
  $claim_type = $HTTP_POST_VARS["claim_type"];
  $status = $HTTP_POST_VARS["status"];

  if ($HTTP_POST_VARS['save'] <>"" && $pallet_id <>""){

	//if no arrival_num, try to get from cargo_tracking table
 	if ($arrival_num ==""){
		$current = date('Y');
		if ($season == "" || $season == $current){
			$schema = "SAG_OWNER";
		}else{
			$schema = "ARCH_".$season;
		}
                $sql = "select t.arrival_num from $schema.cargo_tracking t
                        where pallet_id = '$pallet_id' ";

                $statement = ora_parse($rf_cursor, $sql);
                ora_exec($rf_cursor);
                if (ora_fetch($rf_cursor)){
                        $arrival_num = ora_getcolumn($rf_cursor, 0);
                }                
/*	     
		$VES = strtoupper($vessel);
		$sql = "select t.arrival_num from $schema.cargo_tracking t, vessel_profile v
			where pallet_id = '$pallet_id' and t.arrival_num = to_char(v.lr_num) and 
			upper(vessel_name) = '$VES'";

		$statement = ora_parse($rf_cursor, $sql);
                ora_exec($rf_cursor);
                if (ora_fetch($rf_cursor)){
			$arrival_num = ora_getcolumn($rf_cursor, 0);
		}
*/
	}

	if ($claim_body_id <>""){
		$sql = " update $claim_body set pallet_id= '$pallet_id',
						vessel = '$vessel',
						voyage = '$voyage',
						arrival_num = '$arrival_num',
						season = '$season',
						exporter = '$exporter',
						bol = '$bol',
						prod = '$product',
						claim_qty = $claim_qty,
						claim_unit = '$claim_unit',
						claim_price = $claim_price,
						claim_amt = $claim_amt,
						port_qty = $port_qty,
						port_amt = $port_amt,
						denied_qty = $denied_qty,
						denied_amt = $denied_amt,
						ship_line_qty = $ship_line_qty,
						ship_line_amt = $ship_line_amt,
						claim_type = '$claim_type',
						notes = '$notes',
						internal_notes = '$internal_notes',
						status = '$status'
			where claim_id = $claim_id and claim_body_id = $claim_body_id";
	}else{
                $sql = "select max(claim_body_id) from $claim_body";
                $statement = ora_parse($cursor, $sql);
                ora_exec($cursor);
                if (ora_fetch($cursor)){
                        $claim_body_id = ora_getcolumn($cursor, 0) + 1;
                }else{
                        $claim_body_id = 1;
                }

                $sql = "insert into $claim_body
			(claim_id, claim_body_id, pallet_id, vessel, voyage, arrival_num, season, exporter, bol,prod, 
			 claim_qty, claim_unit, claim_price, claim_amt, 
			 port_qty, port_amt, denied_qty, denied_amt, ship_line_qty, ship_line_amt, 
			 claim_type, notes, internal_notes, status) values (
                        $claim_id, $claim_body_id, '$pallet_id','$vessel','$voyage','$arrival_num','$season','$exporter',
			'$bol','$product',$claim_qty, '$claim_unit', $claim_price, $claim_amt, 
			$port_qty, $port_amt, $denied_qty, $denied_amt, $ship_line_qty, $ship_line_amt, 
			'$claim_type','$notes','$internal_notes','$status')";
	}
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
  }
  if ($HTTP_POST_VARS['delete'] <>""){
	if ($claim_body_id <>""){
		$sql = "update $claim_body set status = 'D' 
			where claim_id = $claim_id and claim_body_id = $claim_body_id";
		$statement = ora_parse($cursor, $sql);
	        ora_exec($cursor);
	}
  }
  ora_close($cursor);
  ora_logoff($conn);
  header ("location: rf_claim.php?claim_id=$claim_id");
?>
