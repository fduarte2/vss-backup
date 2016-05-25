<?
  include("pow_session.php");

  // The form processor
  $lot_id = $HTTP_POST_VARS["lot_free"];
  $schema = $HTTP_POST_VARS["schema"];

  if($lot_id == ""){
     setcookie("pallet_id", "", time() + 28800);
     header("Location: add_rf.php?input=0");
     exit;
  }

  $stmt = "select * from $schema.cargo_tracking where pallet_id like '%$lot_id'";
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $cursor = ora_open($conn);
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);

  include("connect.php");
  // Connect
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$pg_conn){
   die("Could not open connection to database server");
  }


  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  $pallet_id = $row['PALLET_ID'];
  if($pallet_id == ""){
    header("Location: add_rf.php?input=1");
    exit;
  }

  $input = 0;
  // Now see if we already have made a claim for this
  $stmt = "select * from claim_log where rf_pallet_id = '$pallet_id'";
  $result = pg_query($pg_conn, $stmt);
  $rows = pg_num_rows($result);
  if($rows > 0){
    $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
    $claim = $row['claim_id'];
    $input = 2;
  }
   
  // set the cookie for ccd lot id and lr_num
  setcookie("pallet_id", $pallet_id, time() + 28800);
  setcookie("schema", $schema, time() + 28800);
  header("Location: add_rf.php?input=$input&claim=$claim");
  exit;
?>
