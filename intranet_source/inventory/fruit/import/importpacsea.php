<?
echo "<u><b>Errors</b></u><br>\n<br>\n" ;

$dbFile = dbase_open($dest, 0) ;

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

  if ($dbrec["CONDITION"] == "NFU")
    $Fumigation = "N" ;
  else
    $Fumigation = "" ;

  $Pallet_ID = $dbrec["PLT_ID"] ;
  //if (strlen($Pallet_ID) == 0) $Pallet_ID = $dbrec["DETA1"] ;

  $Cases = $dbrec["CASES"] ;
  //if (strlen($Cases) == 0) $Cases = $dbrec["COLUMNA"] ;

  $CommodityCode = $dbrec["CFRUIT"] ;

  $stmt = "INSERT INTO EMAIL_MANIFEST (TRAN_NUM,REC_NUM,PALLET_ID,CASES,COMMODITY_CODE,VARIETY,WEIGHT,CUSTOMER_ID,HATCH" ;
  $stmt .= ",VOYAGE,FROM_SHIPPING_LINE, SHIPPING_LINE,BL,CUSTOMER_NAME,FUMIGATION) VALUES ($Tran_Num,$iRec" ;
  $stmt .= ",'" . $Pallet_ID . "'," . $Cases . "," . $CommodityCode . ",'" . $dbrec["VARIETY"] . "'" ;
  $stmt .= "," . $dbrec["WEIGHT"] * 2.2 . "," . $dbrec["CCONSIGNEE"] . ",'" . $dbrec["HOLD"] . "'" ;
  $stmt .= ",'" . $dbrec["VOYAGE"] . "'," . $FromShipping . "," . $Shipping . ",'" . $dbrec["BL"] . "'" ;
  $stmt .= ",'" . $dbrec["CONSIGNEE"] . "','" . $Fumigation . "')" ;

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


