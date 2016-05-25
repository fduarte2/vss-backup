<?
$dbFile = dbase_open($dest, 0) ;

echo "<u><b>Errors</b></u><br>\n<br>\n" ;

//Create New Header
$stmt = "INSERT INTO EMAIL_HEADER (TRAN_NUM,IMPORT_FILE,VESSEL_NAME,DESCRIPTION,DATE_IMPORTED)" ;
$stmt .= "VALUES ($Tran_Num,'$Filename','$VesselName','$Description','" . date("d-M-Y") . "')" ;
$out = ora_parse($cursor, $stmt);
if (!ora_exec($cursor)){
  $TransactionStatus = -1 ; //Error Don't continue
  echo "Error creating new transaction" ;
}

$iErrors = 0 ;//Rejected Counter
$iRec = 0 ;   //Record Counter
$iCount = 1 ; //File Counter
$NumRecs = dbase_numrecords($dbFile) ;
while ($iCount <= $NumRecs) {
  $dbrec = dbase_get_record_with_names($dbFile,$iCount) ;

  //Lookup Commodity from 'Commodity'
  $stmt = "SELECT * FROM COMMODITY_MAP_DEL WHERE COD_FRU='" . $dbrec["COMMODITY"] . "'" ;
  $out = ora_parse($cursor, $stmt);
  if (!ora_exec($cursor)){
    echo "Could not retrieve commodity: " . $dbrec["COMMODITY"] . " for at record " . $iCount . "<br>" ;
  }
  $Commodity = 0 ;
  if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $Commodity = $row["COMMODITY_CODE"] ;
  }

  //Lookup Customer from 'Exporter'
  $stmt = "SELECT * FROM CUSTOMER_MAP_DEL WHERE COD_CON='" . $dbrec["EXPORTER"] . "'" ;
  $out = ora_parse($cursor, $stmt);
  if (!ora_exec($cursor)){
    echo "Could not retrieve customer: " . $dbrec["EXPORTER"] . " for at record " . $iCount . "<br>" ;
  }
  $Customer = 0 ;
  if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $Customer = $row["CUSTOMER_ID"] ;
  }

  $stmt = "INSERT INTO EMAIL_MANIFEST (TRAN_NUM,REC_NUM,PALLET_ID,CASES,COMMODITY_CODE,VARIETY,CUSTOMER_ID,HATCH," ;
  $stmt .= "VOYAGE,FROM_SHIPPING_LINE, SHIPPING_LINE,MARK,BL,DECK) VALUES ($Tran_Num,$iRec,'" . $dbrec["NRO_CIA"] . "'" ;
  $stmt .= "," . $dbrec["CASES"] . "," . $Commodity . ",'" . $dbrec["VARIETY"] . "'," . $Customer ;
  $stmt .= ",'" . $dbrec["HATCH"] . $dbrec["CHAMBER"] . "','" . $dbrec["VRO_VJE"] . "'," . $FromShipping . "," . $Shipping ;
  $stmt .= ",'" . $dbrec["MARK"] . "','" . $dbrec['BL'] . "','" . $dbrec["CHAMBER"] . "')" ;
  $out = ora_parse($cursor, $stmt);
  if (!ora_exec($cursor)){
    echo "Error at line $iCount: $buffer<br>\n" ;
    echo "SQL: " . $stmt . "<br><br>\n" ;
    $iErrors++ ;
  } else {
    $iRec++ ;
  }
  $iCount++ ;
}

dbase_close($dbFile) ;

?>


