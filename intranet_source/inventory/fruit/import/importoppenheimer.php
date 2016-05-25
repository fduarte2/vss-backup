<?
$hFile = fopen($dest,'r') ;
$seek = "\n" ;
$ImportData = split($seek,fread($hFile, filesize($dest))) ;

echo "<u><b>Errors</b></u><br>\n<br>\n" ;

//Create New Header
$stmt = "INSERT INTO EMAIL_HEADER (TRAN_NUM,IMPORT_FILE,VESSEL_NAME,DESCRIPTION,DATE_IMPORTED)" ;
$stmt .= "VALUES ($Tran_Num,'$Filename','$VesselName','$Description','" . date("d-M-Y") . "')" ;
$out = ora_parse($cursor, $stmt);
if (!ora_exec($cursor)){
  $TransactionStatus = -1 ; //Error - Don't continue
  echo "Error creating new transaction" ;
}

$iErrors = 0 ;//Rejected Counter
$iRec = 0 ;   //Record Counter
$iCount = 1 ; //File Counter
$NumRecs = dbase_numrecords($dbFile) ;
for ($i=0; $i<count($ImportData); $i++) {
  if (is_numeric(substr($ImportData[$i],0,20))) {
    //Add Cargo_manifest
    //Add Voyage cargo table

    $LotNum = substr($ImportData[$i],0,20) ;
    $QtyUnit = "G" ;
    $QtyInHouse = substr($ImportData[$i],27,5) ;
    $CommodityCode = substr($ImportData[$i],43,6) ;
    $BOL = substr($ImportData[$i],49,3) ;
    $Hatch = substr($ImportData[$i],60,5) ;
    $Deck = substr($ImportData[$i],69,4) ;
    if (substr($ImportData[$i],74,5) == "FALSE")
      $Fumigation = "N" ;
    else
      $Fumigation = "" ;
    $ReceiverID = substr($ImportData[$i],84,4) ;
    $Inspect = substr($ImportData[$i],89,4) ;
    $Variety substr($ImportData[$i],94,22) ;
    $Location = substr($ImportData[$i],128,8) ;

    $stmt = "INSERT INTO EMAIL_MANIFEST (TRAN_NUM,REC_NUM,PALLET_ID,CASES,COMMODITY_CODE,VARIETY,CUSTOMER_ID,HATCH," ;
    $stmt .= "FROM_SHIPPING_LINE, SHIPPING_LINE,BL,CUSTOMER_NAME,FUMIGATION) VALUES ($Tran_Num,($i+1)" ;
    $stmt .= ",'" . $LotNum . "'," . $Cases . "," . $CommodityCode . ",'" . $Variety . "'" ;
    $stmt .= "," . $ReceiverID . ",'" . $Hatch . $Deck . "'," ;
    $stmt .= ",5400,5400,'" . $BOL . "','DAVID OPPENHEIMER','" . $Fumigation . "')" ;
    echo $stmt . "<br><br>" ;
  }
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

fclose($hFile) ;

?>


