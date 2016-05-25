<?
$TransactionStatus = 0 ;

//We don't know which type of file (.txt for Oppenheimer, .dbf of everyone else)
$dest = "/var/www/html/inventory/ship_import/import.dat" ;
set_time_limit(120);   		// make reasonably sure the script does not time out on large files

$source = trim($HTTP_POST_FILES['file1']['tmp_name']);	// uploaded file name
system("/bin/cp -f $source $dest");  // Move the file to the production location
system("/bin/chmod a+r $dest");  // Be sure it's world-readable

$Filename = trim($HTTP_POST_FILES['file1']['name']);	// uploaded file name
$VesselName = trim($HTTP_POST_VARS['txtVessel']) ;
$Description = trim($HTTP_POST_VARS['txtDescription']) ;
$VesselType = trim($HTTP_POST_VARS['optVesselType']) ;
$CargoType = trim($HTTP_POST_VARS['optCargoType']) ;
  
if (file_exists($dest)) {
  include("connect.php");
  
  $conn = ora_logon("SAG_OWNER@RF_NEW", "OWNER");
  if (!conn) {
    printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
    printf("Please report to TS!");
    exit;
  }
  ora_commitoff($conn);
  $cursor = ora_open($conn);

  echo date("M d, Y") . "<br>\n" ;
  echo "Vessel Type: " . $VesselType . "<br>\n" ;
  echo "Cargo Type: " . $CargoType . "<br>\n" ;

  $Shipping = 0 ;
  if ($VesselType == "Pacific Seaways") $Shipping = 8091 ;
  if ($VesselType == "Lauritzen") $Shipping = 8010 ;
  if ($VesselType == "Oppenheimer") $Shipping = 5400 ;
  $FromShipping = 0 ;
  if ($CargoType == "Pacific Seaways") $FromShipping = 8091 ;
  if ($CargoType == "Lauritzen") $FromShipping = 8010 ;
  if ($CargoType == "Oppenheimer") $FromShipping = 5400 ;
  

  $stmt = "SELECT MAX(TRAN_NUM) FROM EMAIL_HEADER" ;
  $out = ora_parse($cursor, $stmt);
  ora_exec($cursor);
  if (ora_fetch($cursor)) {
    $Tran_Num = ora_getcolumn($cursor, 0) + 1 ;
    echo "Transaction #" . $Tran_Num . "<br><br>\n" ;
  }

  //Pacific Seaways
  if ($FromShipping == 8091) include("importpacsea.php") ;

  //Lauritzen
  if ($FromShipping == 8010) include("importlauritzen.php") ;

  //Oppenheimer
  if ($FromShipping == 5400) include("importoppenheimer.php") ;

  if ($TransactionStatus == -1) {
    ora_rollback($conn);
    echo "Transaction not uploaded." ;
  } else {
    ora_commit($conn);
    echo "Processed $iRec pallets with $iErrors rejected.<br>" ;
  }

} else {
  echo "File not found: " . $dest ;
  $TransactionStatus = -1 ;
}

ora_close($cursor);
ora_logoff($conn);
?>
