<?
include("connect.php");

$pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
if (!$pg_conn){
  printf("Could not open connection to database server");
  exit;
}

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

  $sql = "select lr_num, lot_id, r.ccd_lot_id, claim_id, quantity, weight*cost, amount  
	  from ccd_received r,
	  (select * from claim_log 
	   where vessel_type='C' and amount > 0 and completed ='t' and quantity > 1) c
	  where r.ccd_lot_id = c.ccd_lot_id 
	  order by lr_num, lot_id " ;
  $result = pg_query($pg_conn, $sql);

  $rows = pg_num_rows($result);
 
  while ($row = pg_fetch_row($result)) {
	$lr_num = $row[0];
	$lot_id = $row[1];
	$ccd_lot_id = $row[2];
	$claim_id = $row[3];
	$qty = $row[4];
	$tot = $row[5];
	$amt = $row[6];

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
  echo $count;

?>
