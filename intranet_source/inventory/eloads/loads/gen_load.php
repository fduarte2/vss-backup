<?
  include("../eloads_globals.php");
  if(! $load_num ){
    $load_num = $HTTP_POST_VARS["load_num"];
  }
  $strlen = strlen($load_num);
  if($load_num == "" || $strlen != 10){
    echo "Invalid Load number $load_num!  Please go back and try again!";
    exit;
  }
  
  $stmt = "select * from eloads_picklist where load_num = '$load_num'";
  $conn = ora_logon(RF, PASS);
  $cursor = ora_open($conn);
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);
  database_check($conn, $ora_success, "Selecting from eloads_picklist failed.");
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $test_load_num = $row['LOAD_NUM'];
  $time = date('m/d/Y H:i:s');
  if($test_load_num == ""){
    // New load- do an insert
    $stmt = "insert into eloads_picklist (load_num, req_date, status) values ('$load_num', to_date('$time', 'MM/DD/YYYY HH24:MI:SS'), '" . fileStart . "')";
  }
  else{
    $stmt = "update eloads_picklist set req_date = to_date('$time', 'MM/DD/YYYY
HH24:MI:SS'), status = '" . fileStart . "', create_date = null where load_num = '$load_num'";
  }
  // Now execute
  $ora_success = ora_parse($cursor, $stmt);
  database_check($conn, $ora_success, "Inserting into eloads_picklist failed.");
  $ora_success = ora_exec($cursor);
  database_check($conn, $ora_success, "Inserting into eloads_picklist failed.");
  ora_commit($conn);
  header("Location: index.php");
?>
