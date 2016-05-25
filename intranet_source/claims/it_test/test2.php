<?
include("connect.php");

$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$pg_conn){
  printf("Could not open connection to database server");
  exit;
}

  $bni_conn = ora_logon("SAG_OWNER@bni", "sag");
  if (!$bni_conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $bni_cursor = ora_open($bni_conn);

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $sql = "select tracking_num, claim_id, claim_qty, claim_amt, port_qty, port_amt 
	  from claim_body 
	  where ship_type = 'C' and status = 'C' and port_amt > 0 and (is2ndclaim is null or is2ndClaim = '')";

  ora_parse($bni_cursor, $sql);
  ora_exec($bni_cursor);
   
  while (ora_fetch($bni_cursor)){
	$ccd_lot_id = ora_getcolumn($bni_cursor, 0);
        $claim_id = ora_getcolumn($bni_cursor, 1);
        $qty = ora_getcolumn($bni_cursor, 2);
        $tot = ora_getcolumn($bni_cursor, 3);
        $port_qty = ora_getcolumn($bni_cursor, 4);
        $amt = ora_getcolumn($bni_cursor, 5);

	$sql = "select lr_num, lot_id from ccd_received where ccd_lot_id = '$ccd_lot_id'";

  	$result = pg_query($pg_conn, $sql);

  	$rows = pg_num_rows($result);
 
  	while ($row = pg_fetch_row($result)) {
		$lr_num = $row[0];
		$lot_id = $row[1];
	

		$sql = "select count(*) from ccd_cargo_damaged where lot_id ='$lot_id' and arrival_num ='$lr_num'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		while(ora_fetch($cursor)){
			$dCount = ora_getcolumn($cursor, 0);
			if ($dCount > 1){
				$count ++;
				echo $lr_num."-".$lot_id."-".$ccd_lot_id."-".$claim_id.",".$qty.",".$tot.",".$amt."<br \>";
			}
		}
  	}
  }
  echo $count;

?>
