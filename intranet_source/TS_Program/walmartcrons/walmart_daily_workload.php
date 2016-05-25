<?
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $Short_Term_Cursor = ora_open($conn);
  $VERY_Short_Term_Cursor = ora_open($conn);

	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
	$tomorrow = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));

//	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d'), date('Y')));
//	$tomorrow = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + 1, date('Y')));

	$sql = "SELECT WALMART_REPORT_HEADER_SEQ.NEXTVAL THE_VAL FROM DUAL";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$report_num = $row['THE_VAL'];
/*
	$sql = "SELECT SEQ_WALMART_REPORT_UNIQ_ID.NEXTVAL THE_VAL FROM DUAL";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$unique_line_num = $row['THE_VAL'];
*/
	// get data for THIS day
	$sql = "INSERT INTO WDI_DATA_REPORT_HEADER
			(REPORT_TYPE,
			DATE_CREATED,
			HEADER_ID)
			VALUES
			('DAILY WORKLOAD',
			TO_DATE('".$date."', 'MM/DD/YYYY'),
			'".$report_num."')";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	$sql = "INSERT INTO WDI_DAILY_WORKLOAD_RPT (HEADER_ID) VALUES ('".$report_num."')";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	// total cases and pallets unloaded
	/* AND (CT.BATCH_ID IS NULL OR (CT.BATCH_ID != 'LUCCA' AND CT.BATCH_ID != 'STORAGE'))
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
						(SELECT WDI_PALLET_ID, WDI_RECEIVER_ID, WDI_ARRIVAL_NUM
						FROM WDI_ADDITIONAL_DATA
						WHERE WDI_STATUS = 'HOLD')
*/	
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET (TOT_CASES_UNLOADED, TOT_PALLETS_UNLOADED) =
				(SELECT NVL(SUM(QTY_CHANGE), 0), NVL(COUNT(DISTINCT CT.PALLET_ID), 0)
					FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT
					WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM(+))
					AND CA.SERVICE_CODE IN ('1', '8')
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND (CA.SERVICE_CODE IN ('1', '8')
						AND TO_CHAR(GREATEST(WVR.RELEASE_TIME, CA.DATE_OF_ACTIVITY), 'MM/DD/YYYY') = '".$date."')
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
				)
			WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	/* AND (WCT.WM_PROGRAM = 'BASE' OR WCT.WM_PROGRAM IS NULL)
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
						(SELECT WDI_PALLET_ID, WDI_RECEIVER_ID, WDI_ARRIVAL_NUM
						FROM WDI_ADDITIONAL_DATA
						WHERE WDI_STATUS = 'HOLD')
*/
	$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, NVL(COUNT(DISTINCT CT.PALLET_ID), 0) THE_COUNT
					FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WDI_VESSEL_RELEASE WVR, WM_CARGO_TYPE WCT
					WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(WVR.LR_NUM)
					AND CA.SERVICE_CODE IN ('20')
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
					AND CA.CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET TOT_CASES_UNLOADED = TOT_CASES_UNLOADED + ".$short_term_row['THE_SUM'].", TOT_PALLETS_UNLOADED = TOT_PALLETS_UNLOADED + ".$short_term_row['THE_COUNT']." WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor); 

	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET TOT_CONT_UNLOADED = ROUND(TOT_PALLETS_UNLOADED / 20)
			WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor); 

	// figure out how many cases are expected to be shipped tomorrow...
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET CURRENT_DROP_CASES = 
			(SELECT NVL(SUM(CASES), 0) 
				FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
				WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
				AND WLD.DCPO_NUM = WLDI.DCPO_NUM
				AND WLH.STATUS = 'ACTIVE'
				AND TO_CHAR(WLH.LOAD_DATE, 'MM/DD/YYYY') = '".$tomorrow."')
			WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	// ... and then subtract any cases that were put on these orders early
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET CURRENT_DROP_CASES = CURRENT_DROP_CASES - 
				(SELECT NVL(SUM(QTY_CHANGE), 0)
				FROM CARGO_ACTIVITY
				WHERE SERVICE_CODE = '6'
				AND ACTIVITY_DESCRIPTION IS NULL
				AND CUSTOMER_ID = '3000'
				AND ORDER_NUM IN
					(SELECT TO_CHAR(WLDI.DCPO_NUM) 
					FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
					WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
					AND WLD.DCPO_NUM = WLDI.DCPO_NUM
					AND WLH.STATUS = 'ACTIVE'
					AND TO_CHAR(WLH.LOAD_DATE, 'MM/DD/YYYY') = '".$tomorrow."')
				)
			WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET (OLDEST_CARYOVER, CARRYOVER_CASES) =
				(SELECT MIN(THE_DATE), NVL(SUM(THE_DIFFERENCE), 0) FROM
								(SELECT WLD.DCPO_NUM THE_ORDER, WLH.LOAD_DATE THE_DATE, NVL(GREATEST(
				  						  										(SELECT SUM(CASES) 
																				FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI
																				WHERE WLDI.DCPO_NUM = WLD.DCPO_NUM
																				AND WLH.LOAD_DATE < TO_DATE('".$tomorrow."', 'MM/DD/YYYY'))
																				-
																				(SELECT NVL(SUM(QTY_CHANGE), 0)
																				FROM CARGO_ACTIVITY CA
																				WHERE ACTIVITY_DESCRIPTION IS NULL
																				AND SERVICE_CODE = '6'
																				AND CA.DATE_OF_ACTIVITY < TO_DATE('".$tomorrow."', 'MM/DD/YYYY')
																				AND CA.CUSTOMER_ID IN 
																					(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
																				AND ORDER_NUM IN
																					(SELECT TO_CHAR(DCPO_NUM)
																					FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI2
																					WHERE WLDI2.DCPO_NUM = WLD.DCPO_NUM)
																				)
																				, 0)
																				, 0) THE_DIFFERENCE
								FROM WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
								WHERE LOAD_DATE IS NOT NULL
								AND WLH.LOAD_NUM = WLD.LOAD_NUM
								AND STATUS = 'ACTIVE'
								AND DCPO_NUM NOT IN
									(SELECT DCPO_NUM FROM WDI_LOAD_DCPO_ITEMNUMBERS WHERE TO_CHAR(DCPO_NUM) IN
										(SELECT DISTINCT ORDER_NUM
											   FROM CARGO_ACTIVITY
											   WHERE ACTIVITY_DESCRIPTION IS NULL
											   AND SERVICE_CODE = '6'
											   AND DATE_OF_ACTIVITY < TO_DATE('".$tomorrow."', 'MM/DD/YYYY')
											   AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
										)
									)
								GROUP BY WLD.DCPO_NUM, WLH.LOAD_DATE)
					HAVING NVL(SUM(THE_DIFFERENCE), 0) > 0
				)
		WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);

	// this one is a bit tricky... first we have to figure out which loads were "LTL", and THEN get the actual case count (inner loop)
	// AND (CT.BATCH_ID IS NULL OR (CT.BATCH_ID != 'LUCCA' AND CT.BATCH_ID != 'STORAGE'))
	$sql = "SELECT WLD.DCPO_NUM THE_ORDER, COUNT(DISTINCT CA.PALLET_ID) THE_COUNT
			FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD, CARGO_ACTIVITY CA, CARGO_TRACKING CT, WM_CARGO_TYPE WCT
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
			AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
			AND CT.PALLET_ID = CA.PALLET_ID
			AND WLH.LOAD_NUM = WLD.LOAD_NUM
			AND WLD.DCPO_NUM = WLDI.DCPO_NUM
			AND TO_CHAR(WLD.DCPO_NUM) = CA.ORDER_NUM
			AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
			AND WCT.WM_PROGRAM = 'BASE'
			AND WLH.STATUS = 'ACTIVE'
			AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
			AND CA.SERVICE_CODE = '6'
			AND CA.ACTIVITY_DESCRIPTION IS NULL
			GROUP BY WLD.DCPO_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	$truckloads = 0;
	$LTL = 0;
	$truckloads_trailer = 0;
	$LTL_trailer = 0;
	while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT SUM(QTY_CHANGE) THE_CHANGE FROM CARGO_ACTIVITY
				WHERE TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				AND SERVICE_CODE = '6'
				AND ACTIVITY_DESCRIPTION IS NULL
				AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
				AND ORDER_NUM IN
					(SELECT TO_CHAR(DCPO_NUM) FROM WDI_LOAD_DCPO_ITEMNUMBERS WHERE DCPO_NUM = '".$short_term_row['THE_ORDER']."')";
		ora_parse($VERY_Short_Term_Cursor, $sql);
		ora_exec($VERY_Short_Term_Cursor);
		ora_fetch_into($VERY_Short_Term_Cursor, $VERY_short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		if($short_term_row['THE_COUNT'] >= 18){
			$truckloads += $VERY_short_term_row['THE_CHANGE'];
			$truckloads_trailer++;
		} else {
			$LTL += $VERY_short_term_row['THE_CHANGE'];
			$LTL_trailer++;
		}
	}
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET SHIP_CASES_TRUCKLOAD = '".$truckloads."', SHIP_CASES_LTL = '".$LTL."',
			TRUCKLOAD_TRAILERS = '".$truckloads_trailer."', LTL_TRAILERS = '".$LTL_trailer."'
			WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	// ADD MORE HERE
	//
	//
	//
/*
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET TRUCKLOAD_TRAILERS = 
				(SELECT NVL(COUNT(DISTINCT ORDER_NUM), 0)
				FROM WDI_ORDER_PROJ_DETAILS
				WHERE TO_CHAR(INBOUND_PO) IN
					(SELECT DISTINCT ORDER_NUM
					FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
					WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.PALLET_ID = CA.PALLET_ID
					AND (CT.BATCH_ID IS NULL OR (CT.BATCH_ID != 'LUCCA' AND CT.BATCH_ID != 'STORAGE'))
					AND CA.SERVICE_CODE = '6'
					AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP WHERE CUSTOMER_GROUP = 'WALMART')
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
					AND CA.ACTIVITY_DESCRIPTION IS NULL))
			WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
*/



	// AND (CT.BATCH_ID IS NULL OR (CT.BATCH_ID != 'LUCCA' OR CT.BATCH_ID != 'STORAGE'))
	$sql = "UPDATE WDI_DAILY_WORKLOAD_RPT SET SHIP_TOT_PLT =
			(SELECT COUNT(DISTINCT CA.PALLET_ID)
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, WM_CARGO_TYPE WCT
				WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2 WHERE CUSTOMER_GROUP = 'WALMART')
				AND CA.SERVICE_CODE IN ('6')
				AND ACTIVITY_DESCRIPTION IS NULL
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'				
				AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				AND CA.ORDER_NUM IN
					(SELECT TO_CHAR(DCPO_NUM) FROM WDI_LOAD_DCPO_ITEMNUMBERS)
			)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);




	// create and email file from report.
	$xls_file = "<TABLE border=1 CELLSPACING=1><tr></tr>";
	$xls_file .= "<tr>
					<td><b>".$date."</b></td>
					<td>7057</td>
					<td>7095</td>
					<td>7097</td>
					<td>6145</td>
					<td>6844</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;</td>
					<td>Holt - Food</td>
					<td>ICS - Food</td>
					<td>SunFed - Food</td>
					<td>Lucca - Food</td>
					<td>VersaCold - Food</td>
					<td>Wilmington</td>
					<td>TOTAL</td>
					<td>Schwarz - Fixtures</td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;</td>
					<td>Philadelphia</td>
					<td>Colton, CA</td>
					<td>Nogales, AZ</td>
					<td>Vineland, NJ</td>
					<td>Houston, TX</td>
					<td>Delaware</td>
					<td>FOOD</td>
					<td>Murray, KY</td>
				</tr>";
	$xls_file .= "<tr>
					<td  bgcolor=\"#FFFF00\" colspan=9><i><b>CONTAINERS ON YARD</b></i></td>
				</tr>";
	$xls_file .= "<tr>
					<td>Perishable Containers</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>0</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>Fixtures Containers</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>0</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td  bgcolor=\"#FFFF00\" colspan=9><b>Offsite Yard</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>Perishable Containers</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>0</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>Fixtures Containers</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>0</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td  bgcolor=\"#FFFF00\" colspan=9><b><i>Workable Containers - Oldest Date</i></b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>Perishable Containers</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>Fixtures Containers</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td  bgcolor=\"#FFFF00\" colspan=9><b>Receiving</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td colspan=9><b>Perishable Containers</b></td>
				</tr>";

	$sql = "SELECT WDWR.*, TO_CHAR(WDWR.OLDEST_CARYOVER, 'MM/DD/YYYY') THE_DATE FROM WDI_DAILY_WORKLOAD_RPT WDWR WHERE HEADER_ID = '".$report_num."'";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$xls_file .= "<tr>
					<td>Total Cases Unloaded</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['TOT_CASES_UNLOADED']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td bgcolor=\"#00FF00\">Total Pallets Unloaded</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">".$row['TOT_PALLETS_UNLOADED']."</td>
					<td>&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>Total Containers Unloaded</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['TOT_CONT_UNLOADED']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td colspan=9><b>Fixtures Containers</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>Total Cases Unloaded</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>Total Containers Unloaded</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td bgcolor=\"#FFFF00\" colspan=9><b>Order Filling</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td colspan=9><b><i>Current Drop</i></b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;Cases</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['CURRENT_DROP_CASES']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td colspan=9><b><i>Carryover</i></b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;Cases</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['CARRYOVER_CASES']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>Oldest Date on Carryover</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['THE_DATE']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td bgcolor=\"#FFFF00\" colspan=9><b>Shipping</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td colspan=9><b>Cases</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;Truckload</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['SHIP_CASES_TRUCKLOAD']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;LTL</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['SHIP_CASES_LTL']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;Total</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".(0 + $row['SHIP_CASES_LTL'] + $row['SHIP_CASES_TRUCKLOAD'])."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td bgcolor=\"#00FF00\">Total Pallets Shipped</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
					<td bgcolor=\"#00FF00\">".$row['SHIP_TOT_PLT']."</td>
					<td>&nbsp;</td>
					<td bgcolor=\"#00FF00\">&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td colspan=9><b>Trailers</b></td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;Truckload</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['TRUCKLOAD_TRAILERS']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;LTL</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row['LTL_TRAILERS']."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";
	$xls_file .= "<tr>
					<td>&nbsp;&nbsp;&nbsp;Total</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".(0 + $row['TRUCKLOAD_TRAILERS'] + $row['LTL_TRAILERS'])."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>";




	$xls_file .= "</table>";

	$xls_attach=chunk_split(base64_encode($xls_file));
/*
//	$mailTo = "awalter@port.state.de.us,hdadmin@port.state.de.us,sadu@port.state.de.us";
	$mailTo = "wilmingt47@wal-mart.com";
	$mailsubject = "Daily Workload Summary for ".$date."\r\n";
	$body = "See Attached File.\r\n";

	$mailheaders = "From: " . "pownoreply@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "jaime.salazar@wal-mart.com,wdi@port.state.de.us,Jeff.Hicks@wal-mart.com,schargr@wal-mart.com\r\n";
//	$mailheaders .= "Cc: " . "wdi@port.state.de.us\r\n";
	$mailheaders .= "Bcc: " . "sadu@port.state.de.us,awalter@port.state.de.us,lstewart@port.state.de.us,archive@port.state.de.us\r\n";
//  bdempsey@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us,ddonofrio@port.state.de.us
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";
*/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WALMARTDLYWORK'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $date, $mailSubject);

	$body = ociresult($email, "NARRATIVE");

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"Daily_Workload_Summary_".$date.".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$xls_attach;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";


	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					DATE_JOB_COMPLETED,
					COMPLETION_STATUS,
					JOB_EMAIL_TO,
					JOB_EMAIL_CC,
					JOB_EMAIL_BCC,
					JOB_BODY)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'WALMARTDLYWORK',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}

//	mail($mailTo, $mailsubject, $Content, $mailheaders);
?>