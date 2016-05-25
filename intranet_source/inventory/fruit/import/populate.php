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
<font size="2" face="Verdana"><b>Populate Records</b></font><br>
<br>

<?
  $TranNum = $HTTP_POST_VARS['Tran_Num'] ;
  $InsertType = $HTTP_POST_VARS['CreateVessel'] ;
  $LRNum = $HTTP_POST_VARS['LRNum'] ;
  $VesselName = $HTTP_POST_VARS['VesselName'] ;
  $ImportMethod = $HTTP_POST_VARS['ImportMethod'] ;

  include("connect.php") ;

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if (!conn) {
    printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
    printf("Please report to TS!");
    exit;
  }
  ora_commitoff($conn);
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn) ;

  $From_Shipping = 0 ;
  echo "Transaction #" . $TranNum . "<br>" ;
  if ($InsertType == 0) {
    $stmt = "SELECT * FROM EMAIL_MANIFEST WHERE TRAN_NUM=" . $TranNum . " ORDER BY REC_NUM" ;
    $out = ora_parse($cursor, $stmt);
    if (!ora_exec($cursor)){
      echo "Error setting new LR Number" ;
      exit ;
    }
    if (ora_fetch_into($cursor, $tran, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      $From_Shipping = $tran["FROM_SHIPPING_LINE"] ;
    }

    //Insert Vessel
    if ($From_Shipping == 5400) $stmt = "SELECT MAX(LR_NUM) FROM VESSEL_PROFILE WHERE LR_NUM<1019000" ;
    if ($From_Shipping == 8091 or $From_Shipping == 8010 ) $stmt = "SELECT MAX(LR_NUM) FROM VESSEL_PROFILE WHERE LR_NUM<3000000" ;
    $out = ora_parse($cursor, $stmt);
    if (!ora_exec($cursor)){
      echo "Error setting new LR Number" ;
      exit ;
    }
    if (ora_fetch_into($cursor, $row)){
      $LRNum = $row[0] + 1 ;
      $stmt = "INSERT INTO VESSEL_PROFILE(LR_NUM,VESSEL_NAME,VESSEL_STATUS,ARRIVAL_NUM) " ;
      $stmt .= " VALUES ('" . $LRNum . "','" . $VesselName . "','ACTIVE','" . $LRNum . "')" ;
      $out = ora_parse($cursor, $stmt) ;
      if (!ora_exec($cursor)) {
	echo "Error Inserting new LR Number: " . $LRNum ;
	exit;
      }
    }

    //Insert Voyage
    $today = "to_date('" . date("m/d/Y h:i:s A") . "', 'mm/dd/yyyy hh:mi:ss AM')" ;
    $stmt = "INSERT INTO VOYAGE (LR_NUM,ARRIVAL_NUM,VOYAGE_NUM,DATE_EXPECTED,DATE_ARRIVED,DATE_DEPARTED)" ;
    $stmt .= " VALUES ('" . $LRNum . "',1,1," . $today . "," . $today . "," . $today . ")" ; 
    $out = ora_parse($cursor, $stmt) ;
    if (!ora_exec($cursor)) {
      echo "Error creating voyage" ;
      exit ;
    }
  }
  echo "LR Number: " . $LRNum . "<br>" ;

  $stmt = "SELECT * FROM EMAIL_MANIFEST WHERE TRAN_NUM=" . $TranNum . " ORDER BY REC_NUM" ;
  $out = ora_parse($cursor, $stmt);
  if (!ora_exec($cursor)){
    echo "Error setting new LR Number" ;
    exit ;
  }

  $iError = 0 ;
  $iRec = 0 ;
  $iIgnore = 0 ;
  $iUpdate = 0 ;
  $iCount = 0 ;
  while (ora_fetch_into($cursor, $tran, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $bError = false ;

    //map customer
    $DSPCCustomer = 0 ;
    $FromShipping = 0 ;
    if ($tran["FROM_SHIPPING_LINE"] == 8091) $FromShipping = 20 ;
    if ($tran["FROM_SHIPPING_LINE"] == 8010) $FromShipping = 10 ;
    $CustID = $tran["CUSTOMER_ID"] ;
    $stmt = "SELECT CUSTOMER_ID FROM CUSTOMER_MAP WHERE CUSTOMER_TYPE=" . $FromShipping . " AND CUSTOMER_CODE=" . $CustID ;
    $out = ora_parse($cursor2, $stmt);
    if (!ora_exec($cursor2)){
      echo "Error mapping customer " . $CustID . ":" . $tran["CUSTOMER_NAME"] ;
      $bError = true ;
    }
    if (ora_fetch_into($cursor2, $row)){
      $DSPCCustomer = $row[0] ;
    } else {
      echo "Error mapping customer " . $CustID . ":" . $tran["CUSTOMER_NAME"] ;
      $bError = true ;
    }
    
    //check for Commodity descriptions
    $Variety = trim($tran["VARIETY"]) ;
    if (strlen($Variety) == 0) {
      $stmt = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE=" . $tran["COMMODITY_CODE"] ;
      $out = ora_parse($cursor2, $stmt);
      if (!ora_exec($cursor2)){
	echo "Error retrieving Commodity Description<br>" ;
        $bError = true ;
      }
      if (ora_fetch_into($cursor2, $row)){
	$Variety = $row[0] ;
      }
    }

    if (!$bError) {
      //Check for existing pallets
      $stmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID='" . $tran["PALLET_ID"] . "' AND ARRIVAL_NUM='" . $LRNum . "'" ;
      $out = ora_parse($cursor2, $stmt);
      if (!ora_exec($cursor2)){
	echo "Error checking for existing pallets<br>" ;
      	$bError = true ;
      }
      if (ora_fetch_into($cursor2, $row)){
	if ($ImportMethod == 1) {
	  $stmt = "UPDATE CARGO_TRACKING SET COMMODITY_CODE=" . $tran["COMMODITY_CODE"] . ",CARGO_DESCRIPTION='" . $Variety . "'" ;
	  $stmt .= ",QTY_RECEIVED=" . $tran["CASES"] . ",RECEIVER_ID=" . $DSPCCustomer . ",FUMIGATION_CODE='" . $tran["FUMIGATION"] . "'" ;
	  $stmt .= ",SHIPPING_LINE=" . $tran["SHIPPING_LINE"] . ",FROM_SHIPPING_LINE=" . $tran["FROM_SHIPPING_LINE"] . ",WEIGHT=" . $tran["WEIGHT"] ;
	  $stmt .= ",HATCH='" . $tran["HATCH"] . "',MARK='" . $tran["MARK"] . "',BOL='" . $tran["BL"] . "',DECK='" . $tran["DECK"] . "'" ;
	  $stmt .= ",QTY_IN_HOUSE=" . $tran["CASES"] . " WHERE PALLET_ID='" . $tran["PALLET_ID"] . "' AND ARRIVAL_NUM='" . $LRNum . "'" ;
	  $out = ora_parse($cursor2, $stmt);
	  if (!ora_exec($cursor2)){
	    echo "Error inserting Pallet " . $tran["PALLET_ID"] ;
	    $bError = true ;
	  } else {
	    $iUpdate++ ;
	  }
	  //Otherwise we will ignore the pallet
        } else {
	  $iIgnore++ ;
	}
      } else {

	$stmt = "INSERT INTO CARGO_TRACKING (PALLET_ID, ARRIVAL_NUM, COMMODITY_CODE, CARGO_DESCRIPTION, QTY_RECEIVED, RECEIVER_ID, QTY_UNIT" ;
	$stmt .= ", FUMIGATION_CODE, SHIPPING_LINE, FROM_SHIPPING_LINE, WEIGHT, WEIGHT_UNIT, HATCH, MARK, BOL, DECK, RECEIVING_TYPE" ;
	$stmt .= ",MANIFESTED,QTY_IN_HOUSE) VALUES ('" . $tran["PALLET_ID"] . "','" . $LRNum . "'," . $tran["COMMODITY_CODE"] ;
	$stmt .= ",'" . $Variety . "'," . $tran["CASES"] . "," . $DSPCCustomer . ",'G'" ;
	$stmt .= ",'" . $tran["FUMIGATION"] . "'," . $tran["SHIPPING_LINE"] . "," . $tran["FROM_SHIPPING_LINE"] ;
	$stmt .= "," . $tran["WEIGHT"] . ",'LB','" . $tran["HATCH"] . "','" . $tran["MARK"] . "','" . $tran["BL"] . "'" ;
	$stmt .= ",'" . $tran["DECK"] . "','S','Y'," . $tran["CASES"] . ")" ;
	$out = ora_parse($cursor2, $stmt);
	if (!ora_exec($cursor2)){
	  echo "Error inserting Pallet " . $tran["PALLET_ID"] ;
	  $bError = true ;
	} else {
	  $iRec++ ;
	}
      }
    }
    if ($bError) $iError++ ;
    $iCount++ ;
  }

  echo "<br><br>" ;
  echo "Populated " . $iRec . " pallets (" ;
  if ($ImportMethod == 1)
    echo $iUpdate . " updated" ;
  else
    echo $iIgnore . " ignored" ;
  echo ") with " . $iError . " rejected.<br>" ;

  
  ora_close($cursor2) ;
  ora_close($cursor);
  ora_logoff($conn);
    
  include("pow_footer.php");

?>

