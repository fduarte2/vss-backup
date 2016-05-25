<?
// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "TS Frank";
$area_type = "INVE";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
  printf("Access Denied from INVE system");
  include("pow_footer.php");
  exit;
}
?>


<?
include("connect.php");
$conn = ora_logon("SAG_OWNER@RF", "owner");
if($conn < 1){
  printf("Error logging on to the RF_NEW Oracle Server: ");
  printf(ora_errorcode($conn));
  printf("Please try later!");
  exit;
}

$cursor = ora_open($conn);


$sql = "select * from CARGO_TRACKING " . 
        "WHERE ARRIVAL_NUM = '" . $_POST['arrival_num'] . 
        "' AND EXPORTER_CODE = '" . $_POST['exporter_code'] . 
        "' AND CARGO_SIZE = '" . $_POST['cargo_size'] . "'";

//echo $sql . "<p></p>";

ora_parse($cursor, $sql);
ora_exec($cursor);

$results = array();
while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  array_push($results, $row);

}

if( count($results) > 0 ){

  $sql = "update CARGO_TRACKING " .
         "SET WAREHOUSE_LOCATION = '" . $_POST['cargo_loc'] .
         "' WHERE ARRIVAL_NUM = '" . $_POST['arrival_num'] .
         "' AND EXPORTER_CODE= '" . $_POST['exporter_code'] .
         "' AND CARGO_SIZE = '" . $_POST['cargo_size'] . "'";

  //  echo $sql . "<p></p>";

  ora_parse($cursor, $sql);
  ora_exec($cursor);


  ora_commit($conn);

  echo( count($results) . " entries updated." );

}
else{
  echo("No matching records found.  0 entries updated.");
}

ora_close($cursor);
ora_logoff($conn);

?>
<?
// Don't forget the footer
include("pow_footer.php");
?>
