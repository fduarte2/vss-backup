<?

  // All POW files need this session file included
  include("pow_session.php");
  // Define some vars for the skeleton page
  $title = "Ship Import";
  $area_type = "INVE";

  include("pow_header.php");
?>

<font size="5" face="Verdana" color="#0066CC">Ship Manifest Import</font><br>
<hr>
<font size="2" face="Verdana"><b>Importing Records</b></font><br>
<br>

<?
 
  $TransactionStatus = 0 ;
  if (trim($HTTP_POST_VARS['Cmd']) == "New") {
    include("newimport.php") ;
  }
  if (trim($HTTP_POST_VARS['Cmd']) == "Pop") {
    include("connect.php");

    $Tran_Num = $HTTP_POST_VARS['TranNum'] ;

    $conn = ora_logon("SAG_OWNER@RF", "OWNER");
    if (!conn) {
      printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
      printf("Please report to TS!");
      exit;
    }
    ora_commitoff($conn);
    $cursor = ora_open($conn);

    $stmt = "SELECT * FROM EMAIL_HEADER WHERE TRAN_NUM=" . $Tran_Num ;
    $out = ora_parse($cursor, $stmt);
    if (!ora_exec($cursor)){
      $TransactionStatus = -1 ; //Error Don't continue
      echo "Error retrieving Transaction #" . $Tran_Num ;
    }
    while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      echo "Transaction #" . $Tran_Num . "<br>" ;
      echo "Import File: " . $row["IMPORT_FILE"] . "<br>" ;
      echo "Vessel: " . $row["VESSEL_NAME"] . "<br>" ;
      echo "Date Imported: " . $row["DATE_IMPORTED"] . "<br>" ;
      $Popped = $row["DB_POPULATED"] ;
      $VesselName = $row["VESSEL_NAME"] ;
    }

    $stmt = "SELECT MAX(REC_NUM) FROM EMAIL_MANIFEST WHERE TRAN_NUM=" . $Tran_Num ;
    $out = ora_parse($cursor, $stmt);
    if (!ora_exec($cursor)){
      $TransactionStatus = -1 ; //Error Don't continue
      echo "Error retrieving Transaction #" . $Tran_Num ;
    }
    while (ora_fetch_into($cursor, $row)){
      $iRec = $row[0] ;
    }
 
    ora_close($cursor);
    ora_logoff($conn);
    
  }
?>
<br>
<br>
<?

  if ($TransactionStatus == 0) {
    if ($Popped)
      echo "<i>This Transaction has already been populated to the database</i><br>" ;
    else
      include("populateform.php") ;
  }

  include("pow_footer.php");

?>

