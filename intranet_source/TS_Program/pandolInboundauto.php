<?
/*
*	Adam Walter, April 2009
*
* due to... last minute contract negotiations, Pandol products in A2 are NOT billed the same general rate.
* I am "hard coding" them out of RF-BNI MISC BILLS and writing this script to deal with them.
* with any luck, the whole concept of RF-BNI misc bills will go away in the future, repalced by a contract-driven billing system...
*
*
*
************************************************************************************/

  // Connect to RF
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    echo "no logon error.  Please do a rain dance\n";
    exit;
  }
  $select_cursor = ora_open($conn);
  $short_term_cursor = ora_open($conn);
  $modify_cursor = ora_open($conn);
  ora_commitoff($conn);

  $conn2 = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn2 = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if(!$conn2){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn2);
    echo "no logon error.  Please do a rain dance\n";
    exit;
  }
  $modify_cursor_bni = ora_open($conn2);
  ora_commitoff($conn2);

  $date = "03/30/2009";
//  $date = date('m/d/Y');

	$email_body = "";
//	$mailTo = "awalter@port.state.de.us,lstewart@port.state.de.us\r\n";
	$mailTo = "awalter@port.state.de.us,lstewart@port.state.de.us,cfoster@port.state.de.us,philhower@port.state.de.us\r\n";
	$mailSubject = "PANDOL A2 INBOUND TRUCK LOADING MISCBILLS CREATED\r\n";
	$mailHeaders = "From:  POWMailserver@port.state.de.us\r\n";

	$sql = "SELECT COUNT(*) PLTCOUNT,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM, ";
	$sql .=	" COMMODITY_CODE FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE ";
	$sql .=	" DATE_OF_ACTIVITY >= TO_DATE('".$date."' ,'MM/DD/YYYY') ";
	$sql .=	" AND DATE_OF_ACTIVITY < TO_DATE('".$date."' ,'MM/DD/YYYY')+1 ";
	$sql .=	" AND TO_MISCBILL IS NULL AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') ";
	$sql .=	" AND SERVICE_CODE ='8' AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM ";
	$sql .=	" AND CT.RECEIVER_ID=CA.CUSTOMER_ID ";
	$sql .=	" AND CT.PALLET_ID=CA.PALLET_ID";
	$sql .=	" AND CT.PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE RECEIVER_ID = '1608' AND WAREHOUSE_LOCATION = 'A2') ";
	$sql .=	" GROUP BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),COMMODITY_CODE";
	$sql .=	" ORDER BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,COMMODITY_CODE";
//	echo $sql."\n";
	$ora_success = ora_parse($select_cursor, $sql);
	$ora_success = ora_exec($select_cursor); 
	while(ora_fetch_into($select_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$service_date = $row['ACT_DATE'];
		$cust = $row['CUSTOMER_ID'];
		$LR = $row['ARRIVAL_NUM'];
		$order = $row['ORDER_NUM'];
		$comm = $row['COMMODITY_CODE'];
		$service_qty = $row['PLTCOUNT'];
		$cases = $row['CASES'];
		$avg_wt = getAVGweight($comm);
		$weight = $service_qty * $avg_wt;
		$amt = 1.86 * ($weight / 100);
		$service = 6221;
		$desc = "INBOUND TRUCKLOADING IN/OUT @ $1.86 / CWT (Order = ".$order.", PLT = ".$service_qty.", Cases = ".$cases." PANDOL A2 RATE)";
		$rf_serv = 8;
		$asset = "W000";

		$sql = "INSERT INTO RF_BNI_MISCBILLS
			(SERVICE_DATE,
			CUSTOMER_ID,
			LR_NUM,
			ORDER_NUM,
			COMMODITY_CODE,
			SERVICE_QTY,
			CASES,
			AVG_WT,
			WEIGHT,
			AMOUNT,
			SERVICE_CODE,
			DESCRIPTION,
			RF_SERVICE_CODE,
			ASSET_CODE)
			VALUES
			(TO_DATE('".$service_date."', 'MM/DD/YYYY'),
			'".$cust."',
			'".$LR."',
			'".$order."',
			'".$comm."',
			'".$service_qty."',
			'".$cases."',
			'".$avg_wt."',
			'".$weight."',
			'".$amt."',
			'".$service."',
			'".$desc."',
			'".$rf_serv."',
			'".$asset."')";
		$ora_success = ora_parse($modify_cursor_bni, $sql);
		$ora_success = ora_exec($modify_cursor_bni); 

		$email_body .= $service_date." ".$weight." @ 1.86 / CWT = ".$amt." Order = ".$order." (PANDOL A2 SPECIAL RATE)\r\n";
	}

    $sql = "UPDATE CARGO_ACTIVITY SET TO_MISCBILL='Y' WHERE TO_MISCBILL IS NULL";
    $sql .= " AND DATE_OF_ACTIVITY >= TO_DATE('".$date."','MM/DD/YYYY')";
    $sql .= " AND DATE_OF_ACTIVITY < TO_DATE('".$date."','MM/DD/YYYY')+1 ";
    $sql .= " AND SERVICE_CODE ='8' AND ACTIVITY_DESCRIPTION IS NULL ";
    $sql .= " AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE RECEIVER_ID = '1608' AND WAREHOUSE_LOCATION = 'A2')";

	$ora_success = ora_parse($modify_cursor, $sql);
	$ora_success = ora_exec($modify_cursor);

	if($email_body != ""){
		mail($mailTo, $mailSubject, $email_body, $mailHeaders);
	}
	ora_commit($conn);
	ora_commit($conn2);

function getAVGweight($comm){
	switch($comm){
		case 8081:
			return 42;
		break;

		case 8091:
			return 14;
		break;

		case 8096:
			return 20;
		break;

		case 8044:
			return 20;
		break;

		case 8104:
			return 5.5;
		break;

		case 8092:
			return 11;
		break;

		case 5106:
			return 31.7;
		break;

		case 8060:
			return 20;
		break;

		case 8105:
			return 22;
		break;

		case 8095:
			return 17.5;
		break;

		case 5306:
			return 42;
		break;

		case 8093:
			return 17.5;
		break;

		case 8082:
			return 42;
		break;

		case 8106:
			return 8;
		break;

		case 8094:
			return 17;
		break;

		case 5302:
			return 25;
		break;

		case 5310:
			return 20;
		break;

	}
}