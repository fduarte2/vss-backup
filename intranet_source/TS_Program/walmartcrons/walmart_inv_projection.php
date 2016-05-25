<?
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail("awalter@port.state.de.us", "walmart project 'sploded", "walmart projection DB access failure", $mailheaders);
    exit;
  }

  $select_cursor1 = ora_open($conn);         
  $select_cursor2 = ora_open($conn);         
  $Short_Term_Cursor = ora_open($conn);

	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 1, date('Y')));
//	$date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')));
//	$date = '05/16/2015';

	$xls_file = "<TABLE border=0 CELLSPACING=1>";
	$xls_file .= "<tr><td colspan=6>&nbsp;</td>
						<td>Est. #Plts</td>
						<td>Est. Avg #Ctns</td>
						<td>Est. #Plts</td>
						<td>Est.#Ctns</td>
						<td>Est. #Plts</td>
						<td>Est.#Ctns</td>
						<td>Est. #Plts</td>
						<td>Est.#Ctns</td>
						<td>#Plts Orders</td>
						<td>Avg #Ctns Orders</td>
						<td colspan=4>&nbsp;</td>
						<td>Est. #Plts</td>
						<td>Est. Avg #Ctns</td>
					</tr>";
	$xls_file .= "<tr><td colspan=4>&nbsp;</td>
						<td>Pallets</td>
						<td>Cartons</td>
						<td>Starting</td>
						<td>Starting</td>
						<td>Scheduled</td>
						<td>Scheduled</td>
						<td>Returning</td>
						<td>Returning</td>
						<td>Leaving</td>
						<td>Leaving</td>
						<td>PO</td>
						<td>PO</td>
						<td>Est. #Plts</td>
						<td>Est.#Ctns</td>
						<td>Est. #Plts</td>
						<td>Est.#Ctns</td>
						<td>Ending</td>
						<td>Ending</td>
					</tr>";
	$xls_file .= "<tr><td>Item Desc</td>
						<td>Item No</td>
						<td>Customer</td>
						<td>Date</td>
						<td>On Hand</td>
						<td>On Hand</td>
						<td>Inventory</td>
						<td>Inventory</td>
						<td>Arrival</td>
						<td>Arrival</td>
						<td>from Repack</td>
						<td>from Repack</td>
						<td>for Repack</td>
						<td>for Repack</td>
						<td>Allocation</td>
						<td>Allocation</td>
						<td>Rejected</td>
						<td>Rejected</td>
						<td>On QC or USDA Hold</td>
						<td>On QC or USDA Hold</td>
						<td>Inventory</td>
						<td>Inventory</td>
					</tr></table>";
	$xls_file .= "<TABLE border=1 CELLSPACING=1>";

	$sql = "SELECT DISTINCT WM_COMMODITY_NAME, WM_ITEM_NUM
			FROM WM_ITEMNUM_MAPPING WIM, WM_ITEM_COMM_MAP WICM
			WHERE TO_CHAR(WIM.ITEM_NUM) IN
				(SELECT BATCH_ID FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
				WHERE QTY_IN_HOUSE > 0
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND RECEIVER_ID IN 
					(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
					WHERE CUSTOMER_GROUP = 'WALMART')
				AND (DATE_RECEIVED IS NOT NULL 
						OR ARRIVAL_NUM NOT IN 
						(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
					)
				AND WCT.WM_PROGRAM = 'BASE'
				)
			AND WIM.WM_ITEM_NUM = WICM.ITEM_NUM
			ORDER BY WM_COMMODITY_NAME";	
	ora_parse($select_cursor1, $sql);
	ora_exec($select_cursor1);
	if(!ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body = "No cargo projected\r\n";
	} else {
		$body = "See Attached File\r\n";
		do {
/*
					AND (ARRIVAL_NUM, RECEIVER_ID, PALLET_ID) NOT IN
						(SELECT WDI_ARRIVAL_NUM, WDI_RECEIVER_ID, WDI_PALLET_ID
						FROM WDI_ADDITIONAL_DATA
						WHERE WDI_STATUS = 'HOLD')
					AND (ARRIVAL_NUM, RECEIVER_ID, PALLET_ID) IN
						(SELECT WDI_ARRIVAL_NUM, WDI_RECEIVER_ID, WDI_PALLET_ID
						FROM WDI_ADDITIONAL_DATA WAD
						WHERE WDI_OUTGOING_ITEM_NUM = '".$select_row1['WDI_OUTGOING_ITEM_NUM']."'
						AND WDI_PROGRAM_TYPE = 'BASE')
*/
			// get end-of-previous-day numbers
			$sql = "SELECT NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM, NVL(COUNT(DISTINCT CT.PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE QTY_IN_HOUSE > 0
						AND CTAD.PALLET_ID = CT.PALLET_ID
						AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
						AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND DATE_RECEIVED IS NOT NULL
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
						AND USDA_HOLD IS NULL
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND CT.BATCH_ID IN
							(SELECT TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
							WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')
						AND CT.ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$IH_pallets = $short_term_row['THE_COUNT'];
			$IH_cases = $short_term_row['THE_SUM'];
//			echo $sql."\n".$select_row1['WM_ITEM_NUM']."\n".$IH_pallets."\n".$IH_cases."\n\n";
			$sql = "SELECT NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM, NVL(COUNT(DISTINCT CT.PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE QTY_IN_HOUSE > 0
						AND CTAD.PALLET_ID = CT.PALLET_ID
						AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
						AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND DATE_RECEIVED IS NOT NULL
						AND (CT.CARGO_STATUS LIKE '%HOLD%'
							OR
							USDA_HOLD = 'Y')
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND CT.BATCH_ID IN
							(SELECT  TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
							WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')
						AND CT.ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$IH_pallets_hold = $short_term_row['THE_COUNT'];
			$IH_cases_hold = $short_term_row['THE_SUM'];
//					AND CT.CARGO_STATUS LIKE '%HOLD%'
			$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_SUM, NVL(COUNT(DISTINCT CA.PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT, CARGO_ACTIVITY CA, WM_EXPECTED_REPACK_ORDER WERO
					WHERE CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CA.ORDER_NUM = WERO.ORDER_NUM
					AND CT.MARK = TO_CHAR(WERO.PO_NUM)
					AND WCT.WM_PROGRAM = 'BASE'
					AND DATE_RECEIVED IS NOT NULL
					AND CA.SERVICE_CODE = 6
					AND WERO.STATUS != 'COMPLETE'
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
						WHERE CUSTOMER_GROUP = 'WALMART')
					AND CT.BATCH_ID IN
						(SELECT  TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
						WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')
					AND CT.ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$out_to_repack_plt = $short_term_row['THE_COUNT'];
			$out_to_repack_cases = $short_term_row['THE_SUM'];
			$sql = "SELECT NVL(SUM(QTY_CHANGE * -1), 0) THE_SUM, NVL(COUNT(DISTINCT CA.PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT, CARGO_ACTIVITY CA, WM_EXPECTED_REPACK_ORDER WERO
					WHERE CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CA.ORDER_NUM = WERO.ORDER_NUM
					AND CT.MARK = TO_CHAR(WERO.PO_NUM)
					AND WCT.WM_PROGRAM = 'BASE'
					AND DATE_RECEIVED IS NOT NULL
					AND CA.SERVICE_CODE = 20
					AND WERO.STATUS != 'COMPLETE'
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
					AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
						WHERE CUSTOMER_GROUP = 'WALMART')
					AND CT.BATCH_ID IN
						(SELECT TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
						WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')
					AND CT.ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$in_from_repack_plt = $short_term_row['THE_COUNT'];
			$in_from_repack_cases = $short_term_row['THE_SUM'];
			$xls_file .= "<tr><td>".$select_row1['WM_COMMODITY_NAME']."</td><td>".$select_row1['WM_ITEM_NUM']."</td>
							<td></td><td>".$date."</td><td>".$IH_pallets."</td><td>".$IH_cases."</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>".$in_from_repack_plt."</td>
							<td>".$in_from_repack_cases."</td>
							<td>".$out_to_repack_plt."</td>
							<td>".$out_to_repack_cases."</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>".$IH_pallets_hold."</td>
							<td>".$IH_cases_hold."</td>
							<td></td>
							<td></td></tr>";

			$est_ending_plts = $IH_pallets;
			$est_ending_cases = $IH_cases;

			for($day_offset = 0; $day_offset < 9; $day_offset++){
//			for($day_offset = -25; $day_offset < -16; $day_offset++){
				$proj_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_offset, date('Y')));
				$est_starting_plts = $est_ending_plts;
				$est_starting_cases = $est_ending_cases;
/*
						AND (CT.ARRIVAL_NUM, CT.RECEIVER_ID, CT.PALLET_ID) IN
							(SELECT WDI_ARRIVAL_NUM, WDI_RECEIVER_ID, WDI_PALLET_ID
							FROM WDI_ADDITIONAL_DATA WAD
							WHERE WDI_OUTGOING_ITEM_NUM = '".$select_row1['WDI_OUTGOING_ITEM_NUM']."'
							AND WDI_PROGRAM_TYPE = 'BASE')
*/
				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM
						FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
						WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
							AND CTAD.PALLET_ID = CT.PALLET_ID
							AND CTAD.RECEIVER_ID = CT.RECEIVER_ID
							AND CTAD.ARRIVAL_NUM = CT.ARRIVAL_NUM
							AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
							AND WCT.WM_PROGRAM = 'BASE'
							AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
							AND USDA_HOLD IS NULL
							AND CT.RECEIVER_ID IN 
								(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
								WHERE CUSTOMER_GROUP = 'WALMART')
							AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
							AND CT.BATCH_ID IN
								(SELECT TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
								WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')
							AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),	
									NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
//				echo $sql;
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_arv = $short_term_row['THE_COUNT'];
				$exp_crtn_arv = $short_term_row['THE_SUM'];

				$sql = "SELECT NVL(SUM(PALLETS), 0) THE_PLTS, NVL(SUM(CASES), 0) THE_CTNS
						FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
						WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
						AND WLD.DCPO_NUM = WLDI.DCPO_NUM
						AND WLH.STATUS = 'ACTIVE'
						AND TO_CHAR(WLH.LOAD_DATE, 'MM/DD/YYYY') = '".$proj_date."'
						AND WLDI.ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_shipout = $short_term_row['THE_PLTS'];
				$exp_crtn_shipout = $short_term_row['THE_CTNS'];

				$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, SUM(QTY_CHANGE) THE_SUM
						FROM CARGO_ACTIVITY
						WHERE SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL
						AND CUSTOMER_ID = '3000'
						AND PALLET_ID IN
							(SELECT PALLET_ID FROM CARGO_TRACKING
							WHERE RECEIVER_ID = '3000'
							AND BOL = '".$select_row1['WM_ITEM_NUM']."')
						AND ORDER_NUM IN
							(SELECT TO_CHAR(WLD.DCPO_NUM)
							FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
							WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
							AND WLD.DCPO_NUM = WLDI.DCPO_NUM
							AND WLH.STATUS = 'ACTIVE'
							AND TO_CHAR(WLH.LOAD_DATE, 'MM/DD/YYYY') = '".$proj_date."'
							AND WLDI.ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')";
//				echo $sql."\n";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_shipout = $exp_plt_shipout - $short_term_row['THE_COUNT'];
				$exp_crtn_shipout = $exp_crtn_shipout - $short_term_row['THE_SUM'];

				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM
						FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT
						WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
						AND CARGO_STATUS LIKE '%HOLD%'
						AND CT.BATCH_ID IN
							(SELECT TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
							WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."')
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
//				echo $sql;
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_hold_arv = $short_term_row['THE_COUNT'];
				$exp_crtn_hold_arv = $short_term_row['THE_SUM'];
/*
				$sql = "SELECT NVL(PLT_COUNT, 0) THE_PLT, NVL(CASE_COUNT, 0) THE_CASE
						FROM WM_EXPECTED_REPACK_ORDER
						WHERE ORDER_NUM NOT IN
							(SELECT ORDER_NUM FROM CARGO_ACTIVITY 
							WHERE SERVICE_CODE = '6'
							AND CUSTOMER_ID = '3000'
							)
						AND TO_CHAR(PO_NUM) IN
							(SELECT MARK FROM CARGO_TRACKING WHERE BATCH_ID IN
								(SELECT TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
								WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."'
								)
							)
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(EXPECTED_DATE + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$est_repack_plts_out = $short_term_row['THE_PLT'];
					$est_repack_cases_out = $short_term_row['THE_CASE'];
				} else {
*/					$est_repack_plts_out = "";
					$est_repack_cases_out = "";
/*				}

				$sql = "SELECT NVL(RET_PLT_COUNT, 0) THE_PLT, NVL(RET_CASE_COUNT, 0) THE_CASE
						FROM WM_EXPECTED_REPACK_ORDER
						WHERE ORDER_NUM NOT IN
							(SELECT ORDER_NUM FROM CARGO_ACTIVITY 
							WHERE SERVICE_CODE = '20'
							AND CUSTOMER_ID = '3000'
							)
						AND TO_CHAR(PO_NUM) IN
							(SELECT MARK FROM CARGO_TRACKING WHERE BATCH_ID IN
								(SELECT TO_CHAR(ITEM_NUM) FROM WM_ITEMNUM_MAPPING
								WHERE WM_ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."'
								)
							)
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(RET_EXPECTED_DATE + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$est_repack_plts_in = $short_term_row['THE_PLT'];
					$est_repack_cases_in = $short_term_row['THE_CASE'];
				} else {
*/					$est_repack_plts_in = "";
					$est_repack_cases_in = "";
//				}

				$est_ending_plts = $est_starting_plts + $exp_plt_arv - $exp_plt_shipout;
				$est_ending_cases = $est_starting_cases + $exp_crtn_arv - $exp_crtn_shipout;

				$xls_file .= "<tr><td></td><td></td><td></td>
								<td>".$proj_date."</td>
								<td></td><td></td>
								<td>".$est_starting_plts."</td>
								<td>".$est_starting_cases."</td>
								<td>".$exp_plt_arv."</td>
								<td>".$exp_crtn_arv."</td>
								<td>".$est_repack_plts_in."</td>
								<td>".$est_repack_cases_in."</td>
								<td>".$est_repack_plts_out."</td>
								<td>".$est_repack_cases_out."</td>
								<td>".$exp_plt_shipout."</td>
								<td>".$exp_crtn_shipout."</td>
								<td></td>
								<td></td>
								<td>".$exp_plt_hold_arv."</td>
								<td>".$exp_crtn_hold_arv."</td>
								<td>".$est_ending_plts."</td>
								<td>".$est_ending_cases."</td>
							</tr>";
			}
						
		} while(ora_fetch_into($select_cursor1, $select_row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));






//				AND ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
//				AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
				WHERE QTY_IN_HOUSE > 0
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND RECEIVER_ID IN 
					(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
					WHERE CUSTOMER_GROUP = 'WALMART')
				AND CT.BOL = 'MULTIPLE'
				AND (DATE_RECEIVED IS NOT NULL 
						OR ARRIVAL_NUM NOT IN 
						(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
					)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] >= 1){
			// there are multi-pallets with different item #s.


			// get end-of-previous-day numbers
			$sql = "SELECT NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM, NVL(COUNT(DISTINCT PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
					WHERE QTY_IN_HOUSE > 0
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND DATE_RECEIVED IS NOT NULL
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
						WHERE CUSTOMER_GROUP = 'WALMART')
					AND CT.BOL = 'MULTIPLE'
					AND ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$IH_pallets_multi = $short_term_row['THE_COUNT'];
			$IH_cases_multi = $short_term_row['THE_SUM'];
			$sql = "SELECT NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM, NVL(COUNT(DISTINCT PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
					WHERE QTY_IN_HOUSE > 0
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND DATE_RECEIVED IS NOT NULL
					AND CT.CARGO_STATUS LIKE '%HOLD%'
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
						WHERE CUSTOMER_GROUP = 'WALMART')
					AND CT.BOL = 'MULTIPLE'
					AND ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$IH_pallets_multi_hold = $short_term_row['THE_COUNT'];
			$IH_cases_multi_hold = $short_term_row['THE_SUM'];
			$xls_file .= "<tr><td>MULTIPLE</td><td>MULTIPLE</td>
							<td></td><td>".$date."</td><td>".$IH_pallets_multi."</td><td>".$IH_cases_multi."</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>".$IH_pallets_multi_hold."</td>
							<td>".$IH_cases_multi_hold."</td>
							<td></td>
							<td></td></tr>";

			$est_ending_plts = $IH_pallets_multi;
			$est_ending_cases = $IH_cases_multi;

			for($day_offset = 0; $day_offset < 9; $day_offset++){
				$proj_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_offset, date('Y')));
				$est_starting_plts = $est_ending_plts;
				$est_starting_cases = $est_ending_cases;
/*
						AND (CT.ARRIVAL_NUM, CT.RECEIVER_ID, CT.PALLET_ID) IN
							(SELECT WDI_ARRIVAL_NUM, WDI_RECEIVER_ID, WDI_PALLET_ID
							FROM WDI_ADDITIONAL_DATA WAD
							WHERE WDI_OUTGOING_ITEM_NUM = '".$select_row1['WDI_OUTGOING_ITEM_NUM']."'
							AND WDI_PROGRAM_TYPE = 'BASE')
*/
				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM
						FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT
						WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
						AND CT.BOL = 'MULTIPLE'
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
//				echo $sql;
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_arv = $short_term_row['THE_COUNT'];
				$exp_crtn_arv = $short_term_row['THE_SUM'];
/*
				$sql = "SELECT NVL(SUM(PLT_COUNT), 0) THE_PLTS, NVL(SUM(CARTON_COUNT), 0) THE_CTNS
						FROM WDI_ORDER_PROJ_DETAILS WOPJ, WDI_ORDER_PROJECTION WOP
						WHERE WOPJ.ORDER_NUM = WOP.ORDER_NUM
						AND TO_CHAR(WOP.DATE_EXPECTED, 'MM/DD/YYYY') = '".$proj_date."'
						AND WOPJ.ITEM_NUM = '".$select_row1['WM_ITEM_NUM']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_shipout = $short_term_row['THE_PLTS'];
				$exp_crtn_shipout = $short_term_row['THE_CTNS'];
*/
				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM
						FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT
						WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND CT.CARGO_STATUS LIKE '%HOLD%'
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
						AND CT.BOL = 'MULTIPLE'
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
//				echo $sql;
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_arv_hold = $short_term_row['THE_COUNT'];
				$exp_crtn_arv_hold = $short_term_row['THE_SUM'];

				$exp_plt_shipout = 0;
				$exp_crtn_shipout = 0;

				$est_ending_plts = $est_starting_plts + $exp_plt_arv - $exp_plt_shipout;
				$est_ending_cases = $est_starting_cases + $exp_crtn_arv - $exp_crtn_shipout;

				$xls_file .= "<tr><td></td><td></td><td></td>
								<td>".$proj_date."</td>
								<td></td><td></td>
								<td>".$est_starting_plts."</td>
								<td>".$est_starting_cases."</td>
								<td>".$exp_plt_arv."</td>
								<td>".$exp_crtn_arv."</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>".$exp_plt_shipout."</td>
								<td>".$exp_crtn_shipout."</td>
								<td></td>
								<td></td>
								<td>".$exp_plt_arv_hold."</td>
								<td>".$exp_crtn_arv_hold."</td>
								<td>".$est_ending_plts."</td>
								<td>".$est_ending_cases."</td>
							</tr>";
			}
		}






		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
				WHERE QTY_IN_HOUSE > 0
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND RECEIVER_ID IN 
					(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
					WHERE CUSTOMER_GROUP = 'WALMART')
				AND CT.BATCH_ID != 'MULTIPLE'
				AND CT.BATCH_ID NOT IN
					(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
				AND ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] >= 1){
			// there are pallets that don't match anything in the mapping table.
			// get end-of-previous-day numbers
			$sql = "SELECT NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM, NVL(COUNT(DISTINCT PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
					WHERE QTY_IN_HOUSE > 0
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND DATE_RECEIVED IS NOT NULL
					AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
						WHERE CUSTOMER_GROUP = 'WALMART')
					AND CT.BATCH_ID != 'MULTIPLE'
					AND CT.BATCH_ID NOT IN
						(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
					AND ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$IH_plt_ukn = $short_term_row['THE_COUNT'];
			$IH_cases_ukn = $short_term_row['THE_SUM'];
			$sql = "SELECT NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM, NVL(COUNT(DISTINCT PALLET_ID), 0) THE_COUNT
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
					WHERE QTY_IN_HOUSE > 0
					AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
					AND WCT.WM_PROGRAM = 'BASE'
					AND DATE_RECEIVED IS NOT NULL
					AND CT.CARGO_STATUS LIKE '%HOLD%'
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
						WHERE CUSTOMER_GROUP = 'WALMART')
					AND CT.BATCH_ID != 'MULTIPLE'
					AND CT.BATCH_ID NOT IN
						(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
					AND ARRIVAL_NUM IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$IH_plt_ukn_hold = $short_term_row['THE_COUNT'];
			$IH_cases_ukn_hold = $short_term_row['THE_SUM'];
			$xls_file .= "<tr><td>UNKNOWN</td><td>UNKNOWN</td>
							<td></td><td>".$date."</td><td>".$IH_plt_ukn."</td><td>".$IH_cases_ukn."</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>".$IH_plt_ukn_hold."</td>
							<td>".$IH_cases_ukn_hold."</td>
							<td></td>
							<td></td></tr>";

			$est_ending_plts = $IH_plt_ukn;
			$est_ending_cases = $IH_cases_ukn;

			for($day_offset = 0; $day_offset < 9; $day_offset++){
				$proj_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') + $day_offset, date('Y')));
				$est_starting_plts = $est_ending_plts;
				$est_starting_cases = $est_ending_cases;
/*
						AND (CT.ARRIVAL_NUM, CT.RECEIVER_ID, CT.PALLET_ID) IN
							(SELECT WDI_ARRIVAL_NUM, WDI_RECEIVER_ID, WDI_PALLET_ID
							FROM WDI_ADDITIONAL_DATA WAD
							WHERE WDI_OUTGOING_ITEM_NUM = '".$select_row1['WDI_OUTGOING_ITEM_NUM']."'
							AND WDI_PROGRAM_TYPE = 'BASE')
*/
				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM
						FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT
						WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS NOT LIKE '%HOLD%')
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
						AND CT.BATCH_ID != 'MULTIPLE'
						AND CT.BATCH_ID NOT IN
							(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
//				echo $sql;
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_arv = $short_term_row['THE_COUNT'];
				$exp_crtn_arv = $short_term_row['THE_SUM'];

				$sql = "SELECT NVL(SUM(PALLETS), 0) THE_PLTS, NVL(SUM(CASES), 0) THE_CTNS
						FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
						WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
						AND WLD.DCPO_NUM = WLDI.DCPO_NUM
						AND WLH.STATUS = 'ACTIVE'
						AND TO_CHAR(WLH.LOAD_DATE, 'MM/DD/YYYY') = '".$proj_date."'
						AND WLDI.ITEM_NUM NOT IN
							(SELECT WM_ITEM_NUM FROM WM_ITEMNUM_MAPPING)";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_shipout = $short_term_row['THE_PLTS'];
				$exp_crtn_shipout = $short_term_row['THE_CTNS'];

				$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, SUM(QTY_CHANGE) THE_SUM
						FROM CARGO_ACTIVITY
						WHERE SERVICE_CODE = '6'
						AND ACTIVITY_DESCRIPTION IS NULL
						AND CUSTOMER_ID = '3000'
						AND ORDER_NUM IN
							(SELECT TO_CHAR(WLD.DCPO_NUM)
							FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
							WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
							AND WLD.DCPO_NUM = WLDI.DCPO_NUM
							AND WLH.STATUS = 'ACTIVE'
							AND TO_CHAR(WLH.LOAD_DATE, 'MM/DD/YYYY') = '".$proj_date."'
							AND WLDI.ITEM_NUM NOT IN
								(SELECT WM_ITEM_NUM FROM WM_ITEMNUM_MAPPING))";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_shipout = $exp_plt_shipout - $short_term_row['THE_COUNT'];
				$exp_crtn_shipout = $exp_crtn_shipout - $short_term_row['THE_SUM'];

				$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT, NVL(SUM(QTY_IN_HOUSE), 0) THE_SUM
						FROM CARGO_TRACKING CT, VOYAGE VOY, WM_CARGO_TYPE WCT
						WHERE CT.ARRIVAL_NUM = TO_CHAR(VOY.LR_NUM)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND CT.CARGO_STATUS LIKE '%HOLD%'
						AND CT.RECEIVER_ID IN 
							(SELECT RECEIVER_ID FROM CHILEAN_CUSTOMER_MAP_V2
							WHERE CUSTOMER_GROUP = 'WALMART')
						AND (CT.ARRIVAL_NUM NOT IN (SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE))
						AND CT.BATCH_ID != 'MULTIPLE'
						AND CT.BATCH_ID NOT IN
							(SELECT ITEM_NUM FROM WM_ITEMNUM_MAPPING)
						AND TO_CHAR(GREATEST(TO_DATE('".date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 0, date('Y')))."', 'MM/DD/YYYY'),				NVL(VOY.DATE_DEPARTED + 1, TO_DATE('01/01/1980', 'MM/DD/YYYY'))), 'MM/DD/YYYY') = '".$proj_date."'";
//				echo $sql;
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$exp_plt_hold_arv_ukn = $short_term_row['THE_COUNT'];
				$exp_crtn_hold_arv_ukn = $short_term_row['THE_SUM'];

				$est_ending_plts = $est_starting_plts + $exp_plt_arv - $exp_plt_shipout;
				$est_ending_cases = $est_starting_cases + $exp_crtn_arv - $exp_crtn_shipout;

				$xls_file .= "<tr><td></td><td></td><td></td>
								<td>".$proj_date."</td>
								<td></td><td></td>
								<td>".$est_starting_plts."</td>
								<td>".$est_starting_cases."</td>
								<td>".$exp_plt_arv."</td>
								<td>".$exp_crtn_arv."</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>".$exp_plt_shipout."</td>
								<td>".$exp_crtn_shipout."</td>
								<td></td>
								<td></td>
								<td>".$exp_plt_hold_arv_ukn."</td>
								<td>".$exp_crtn_hold_arv_ukn."</td>
								<td>".$est_ending_plts."</td>
								<td>".$est_ending_cases."</td>
							</tr>";
			}
		}



	}



	$xls_file .= "</table>";

	$xls_attach=chunk_split(base64_encode($xls_file));

/*
//	$mailTo = "awalter@port.state.de.us,hdadmin@port.state.de.us,sadu@port.state.de.us";
	$mailTo = "wilmingt47@wal-mart.com";
	$mailsubject = "Pallet Projection for ".$date."\r\n";

	$mailheaders = "From: " . "pownoreply@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "jaime.salazar@wal-mart.com,wdi@port.state.de.us,Jeff.Hicks@wal-mart.com,schargr@wal-mart.com\r\n";
//	$mailheaders .= "Cc: " . "wdi@port.state.de.us\r\n";
	$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,archive@port.state.de.us\r\n";
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

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WALMARTINVPROJ'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
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
	$Content.="Content-Type: application/pdf; name=\"PalletProjReport".$date.".xls\"\r\n";
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
					'WALMARTINVPROJ',
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