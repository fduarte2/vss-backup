<?
/*
*
*		Adam Walter, Sep 2009
*
*	After much spewing of hatred towards Postgres and S-17, the warehouse
*	Utilization report is now being move exclusively to Oracle.
*	Further, obsolete aspects (like the CCDS system) are being removed.
*************************************************************************/

/*
*		October '09
*
*	As of this update, all cargo comes from the BNI system with the 
*	EXEPTIONS of:
*	--- Chilean fruit (commodity codes 8XXX)
*	--- special Chilean fruits (commodities 5199, 5104, 5106, 5306, 5199)
*	--- Brazillian fruits (commodity codes 5305 and 5310)
*	--- Dole Dock Tick paper (REMARK = DOLEPAPERSYSTEM)
*	--- Clementines (Commodity Codes 56XX)
*************************************************************************/

	$factor = 29;
	$rf_tier = 2;
	$bni_tier = 3;

//	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$whse_cursor_bni = ora_open($conn);
	$box_cursor_bni = ora_open($conn);
	$Short_Term_Cursor_BNI = ora_open($conn);
	$insert_cursor_bni = ora_open($conn);

//	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		exit;
	}
	$cursor_rf = ora_open($conn2);
	$Short_Term_Cursor_RF = ora_open($conn2);

   $conn3 = ora_logon("LABOR@LCS", "LABOR");
   if($conn3 < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn3));
     	printf("</body></html>");
       	exit;
   }
   $LCScursor = ora_open($conn3);

	
	
	
	$factor = 29; // a "default square feet" value for pallets et. al.

	$run_date = date('m/d/Y');

	//check if the report is confirmed
	$sql = "select * from utilization_confirm where TO_CHAR(report_date, 'MM/DD/YYYY') = '".$run_date."'";
	$ora_success = ora_parse($Short_Term_Cursor_BNI, $sql);
	$ora_success = ora_exec($Short_Term_Cursor_BNI, $sql);
	if(ora_fetch_into($Short_Term_Cursor_BNI, $short_term_row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$isConfirm = true;
	}else{ 
		$isConfirm = false; 
	}

	if ($isConfirm){
		$msg = "This report was confirmed by OPS";
	}else{
		$msg = "This report is not confirmed by OPS";
	}

	// if there is a discrepancy between the numbers this next section generates and some hand-calculated spreadsheet,
	// be sure to check the DB for WAREHOUSE_LOCATION or Lease/Repair inconsistencies.
	// Note the first SQL of this next section; if we have ALREADY generated this data for the day, we do not re-generate.
	// this ensures a consistent count as of the "first running" of this program daily.
	$sql = "SELECT COUNT(*) THE_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."'";
	ora_parse($Short_Term_Cursor_BNI, $sql);
	ora_exec($Short_Term_Cursor_BNI);
	ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	if($Short_Term_Row_BNI['THE_COUNT'] == 0){
		// if there are NO records for this date, then we need to populate the table.  if there are, then we do NOT re-populate it.
		$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
		ora_parse($whse_cursor_bni, $sql);
		ora_exec($whse_cursor_bni);
		while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$whse_total = 0;
			$whse_total_sq_ft = 0;
//			echo "WHSE ".$whse_row['WAREHOUSE']."\n";
/*
			AND DATE_RECEIVED > (SYSDATE - (365 * 2))
*/
			$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM, SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) * (".$factor." / ".$bni_tier.") THE_SQ_FEET FROM CARGO_TRACKING WHERE (UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE']."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse_row['WAREHOUSE']."%') AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
//			echo "SQL ".$sql."\n";
			ora_parse($Short_Term_Cursor_BNI, $sql);
			ora_exec($Short_Term_Cursor_BNI);
			ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//			echo "SUM ".$Short_Term_Row_BNI['THE_SUM']."\n";
			$whse_total += $Short_Term_Row_BNI['THE_SUM'];
//			echo "SQFT ".$Short_Term_Row_BNI['THE_SQ_FEET']."\n";
			$whse_total_sq_ft += $Short_Term_Row_BNI['THE_SQ_FEET'];

/*
						OR
					(REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE = 1))
*/
			$sql = "SELECT COUNT(*) THE_COUNT, SUM(DECODE(STACKING, 'S', 1 / ".$rf_tier.", 1)) * ".$factor." THE_SQ_FEET FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
					((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
						OR
					 ((COMMODITY_CODE = '5310' OR COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
						OR
					 (COMMODITY_CODE IN ('5199', '5104', '5106', '5306', '5199') AND QTY_IN_HOUSE > 10)
						OR
					 (COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10))
					AND RECEIVER_ID != '453'
					AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
					AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
					AND ARRIVAL_NUM != '4321'
					AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE']."%'";
//			echo "RFSQL ".$sql."\n";
			ora_parse($Short_Term_Cursor_RF, $sql);
			ora_exec($Short_Term_Cursor_RF);
			ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//			echo "RFSUM ".$Short_Term_Row_RF['THE_COUNT']."\n";
			$whse_total += $Short_Term_Row_RF['THE_COUNT'];
//			echo "RFSQFT ".$Short_Term_Row_RF['THE_SQ_FEET']."\n";
			$whse_total_sq_ft += $Short_Term_Row_RF['THE_SQ_FEET'];

			$whse_minus_boxes_total = $whse_total;
			$whse_minus_boxes_total_sq_ft = $whse_total_sq_ft;

			$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT, PLT_SQ_FEET) VALUES (SYSDATE, '".$whse_row['WAREHOUSE']."Total', '".round($whse_total)."', '".round($whse_total_sq_ft)."')";
//			echo "INSSQL ".$sql."\n";
			ora_parse($insert_cursor_bni, $sql);
			ora_exec($insert_cursor_bni);

			for($i = 1; $i < 9; $i++){
				$box_total = 0;
				$box_total_sq_ft = 0;

				$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM, SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) * (".$factor." / ".$bni_tier.") THE_SQ_FEET FROM CARGO_TRACKING WHERE (UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE'].$i."%' OR UPPER(WAREHOUSE_LOCATION) LIKE 'WING ".$whse_row['WAREHOUSE'].$i."%') AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
				ora_parse($Short_Term_Cursor_BNI, $sql);
				ora_exec($Short_Term_Cursor_BNI);
				ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$box_total += $Short_Term_Row_BNI['THE_SUM'];
				$box_total_sq_ft += $Short_Term_Row_BNI['THE_SQ_FEET'];

				$sql = "SELECT COUNT(*) THE_COUNT, SUM(DECODE(STACKING, 'S', 1 / ".$rf_tier.", 1)) * ".$factor." THE_SQ_FEET FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
						((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
							OR
						 ((COMMODITY_CODE = '5310' OR COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
							OR
						 (COMMODITY_CODE IN ('5199', '5104', '5106', '5306', '5199') AND QTY_IN_HOUSE > 10)
							OR
						 (COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10))
						AND RECEIVER_ID != '453'
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
						AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
						AND ARRIVAL_NUM != '4321'
						AND UPPER(WAREHOUSE_LOCATION) LIKE '".$whse_row['WAREHOUSE'].$i."%'";
				ora_parse($Short_Term_Cursor_RF, $sql);
				ora_exec($Short_Term_Cursor_RF);
				ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$box_total += $Short_Term_Row_RF['THE_COUNT'];
				$box_total_sq_ft += $Short_Term_Row_RF['THE_SQ_FEET'];

				$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT, PLT_SQ_FEET) VALUES (SYSDATE, '".$whse_row['WAREHOUSE'].$i."', '".round($box_total)."', '".round($box_total_sq_ft)."')";
				ora_parse($insert_cursor_bni, $sql);
				ora_exec($insert_cursor_bni);

				$whse_minus_boxes_total -= $box_total;
				$whse_minus_boxes_total_sq_ft -= $box_total_sq_ft;
			}

			$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT, PLT_SQ_FEET) VALUES (SYSDATE, '".$whse_row['WAREHOUSE']."Other', '".round($whse_minus_boxes_total)."', '".round($whse_minus_boxes_total_sq_ft)."')";
			ora_parse($insert_cursor_bni, $sql);
			ora_exec($insert_cursor_bni);
		}

		// now that all "warehouse accountable" data is stored, proceed to grab ALL data, for the "not in a warehouse" line
		$all_pallets = 0;

		$sql = "SELECT SUM(PALLET_COUNT) THE_PALLETS FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$run_date."' AND WAREHOUSE LIKE '%Total'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$all_pallets_in_whs = $Short_Term_Row_BNI['THE_PALLETS'];

		$sql = "SELECT SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) THE_SUM, SUM(decode(upper(QTY_UNIT), 'DRUM', QTY_IN_HOUSE / 4, QTY_IN_HOUSE)) * (".$factor." / ".$bni_tier.") THE_SQ_FEET FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED < SYSDATE AND QTY_UNIT in ('PLT', 'BIN','DRUM','PLTS' )";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$all_pallets += $Short_Term_Row_BNI['THE_SUM'];

		$sql = "SELECT COUNT(*) THE_COUNT, SUM(DECODE(STACKING, 'S', 1 / ".$rf_tier.", 1)) * ".$factor." THE_SQ_FEET FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND
				((COMMODITY_CODE LIKE '8%' AND QTY_IN_HOUSE > 10)
					OR
				 ((COMMODITY_CODE = '5310' OR COMMODITY_CODE = '5305') AND QTY_IN_HOUSE > 10)
					OR
				 (COMMODITY_CODE IN ('5199', '5104', '5106', '5306', '5199') AND QTY_IN_HOUSE > 10)
					OR
				 (COMMODITY_CODE LIKE '56%' AND QTY_IN_HOUSE > 10))
				AND RECEIVER_ID != '453'
				AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DOLE%'
				AND UPPER(WAREHOUSE_LOCATION) NOT LIKE 'DELETE%'
				AND ARRIVAL_NUM != '4321'";
		ora_parse($Short_Term_Cursor_RF, $sql);
		ora_exec($Short_Term_Cursor_RF);
		ora_fetch_into($Short_Term_Cursor_RF, $Short_Term_Row_RF, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$all_pallets += $Short_Term_Row_RF['THE_COUNT'];

		$all_non_positioned_pallets = max(array(0, $all_pallets - $all_pallets_in_whs));

		$sql = "INSERT INTO WAREHOUSE_DAILY_BREAKDOWN (REPORT_DATE, WAREHOUSE, PALLET_COUNT) VALUES (SYSDATE, 'NotInRep', '".$all_non_positioned_pallets."')";
		ora_parse($insert_cursor_bni, $sql);
		ora_exec($insert_cursor_bni);
	}

	// pallet data now in table, lets compile the output.
	// Sq. Ft. usage first

	$body  = "<html>\r\n";
	$body .= "<body>\r\n";
	$body .= "<table width = 540>\r\n";
	$body .= "<tr><td align=center>\r\n";
	$body .= "<br \><font size = 5 ><b>Reefer Warehouse Utilization</b></font><br \><br \>\r\n";
	$body .= "</td></tr>\r\n";
	$body .= "<tr><td>\r\n";
  	$body .= "<table border = 1  width=580 cellspacing=1 cellpadding=1 bgcolor=#000000>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=400 align=center colspan=5><b>Sq. Ft.</b></td>\r\n";
	$body .= "<td width = 180 align = center colspan= 2><b>Utilization</b></td></tr>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=80 align=center><b>Total<br>T</b></td>\r\n";
	$body .= "<td width=80 align = center><b>Leased<br>L</b></td>\r\n";
	$body .= "<td width=80 align = center><b>Repair<br>R</b></td>\r\n";
	$body .= "<td width=80 align=center><b>Net<br>N</b></td>\r\n";
	$body .= "<td width=80 align=center><b>Occupied<br>O</b></td>\r\n";
//	$body .= "<td width=90 align=center><b>Committed</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Net<br>O / N</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Gross<br>(O + L) / T</b></td></tr>\r\n";

	$total_sq_ft = 0;
	$total_leased = 0;
	$total_repair = 0;
	$total_net = 0;
	$total_occupied = 0;

	$net_sq_ft = array(); // this value gets used in multiple tables, need to save the calculation

	$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
	ora_parse($whse_cursor_bni, $sql);
	ora_exec($whse_cursor_bni);
	while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT SUM(SQ_FEET) THE_SUM FROM WAREHOUSE_BOX_DETAIL WHERE WAREHOUSE = '".$whse_row['WAREHOUSE']."'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$this_row_sq_ft = (0 + $Short_Term_Row_BNI['THE_SUM']);
		$total_sq_ft += $Short_Term_Row_BNI['THE_SUM'];

		$sql = "SELECT SUM(SQ_FEET) THE_SUM FROM WAREHOUSE_BOX_DETAIL WBD, WAREHOUSE_LEASE WL WHERE WBD.WAREHOUSE = WL.WAREHOUSE AND WBD.BOX = WL.BOX AND (START_DATE IS NULL OR START_DATE < SYSDATE) AND (END_DATE IS NULL OR END_DATE > SYSDATE) AND UPPER(CUSTOMER) != 'POW REPAIR' AND WBD.WAREHOUSE = '".$whse_row['WAREHOUSE']."'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$this_row_leased =(0 + $Short_Term_Row_BNI['THE_SUM']);
		$total_leased += $Short_Term_Row_BNI['THE_SUM'];

		$sql = "SELECT SUM(SQ_FEET) THE_SUM FROM WAREHOUSE_BOX_DETAIL WBD, WAREHOUSE_LEASE WL WHERE WBD.WAREHOUSE = WL.WAREHOUSE AND WBD.BOX = WL.BOX AND (START_DATE IS NULL OR START_DATE < SYSDATE) AND (END_DATE IS NULL OR END_DATE > SYSDATE) AND UPPER(CUSTOMER) = 'POW REPAIR' AND WBD.WAREHOUSE = '".$whse_row['WAREHOUSE']."'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$this_row_repair = (0 + $Short_Term_Row_BNI['THE_SUM']);
		$total_repair += $Short_Term_Row_BNI['THE_SUM'];

		$net_sq_ft[$whse_row['WAREHOUSE']] = $this_row_sq_ft - ($this_row_leased + $this_row_repair);
		$total_net_sq_ft += $this_row_sq_ft - ($this_row_leased + $this_row_repair);

		$sql = "SELECT PLT_SQ_FEET FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$run_date."' AND WAREHOUSE = '".$whse_row['WAREHOUSE']."Total'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$this_row_occupied = (0 + $Short_Term_Row_BNI['PLT_SQ_FEET']);
		$total_occupied += $Short_Term_Row_BNI['PLT_SQ_FEET'];

		if($net_sq_ft[$whse_row['WAREHOUSE']] != 0){
			$this_row_net = round(($this_row_occupied / $net_sq_ft[$whse_row['WAREHOUSE']]) * 100);
		} else {
			$this_row_net = 0;
		}

		if($this_row_sq_ft != 0){
			$this_row_gross = round((($this_row_occupied + $this_row_leased) / $this_row_sq_ft) * 100);
		} else {
			$this_row_gross = 0;
		}

		$body .= "<tr bgcolor=#ffffff><td align=center>".$whse_row['WAREHOUSE']."-".number_format($this_row_sq_ft,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_leased,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_repair,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($net_sq_ft[$whse_row['WAREHOUSE']],0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_occupied,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".$this_row_net."%</td>\r\n";
		$body .= "<td align=center>".$this_row_gross."%</td></tr>\r\n";
	}

	$body .= "<tr bgcolor=#ffffff><td width=80 align=center><b><i>Total- ".number_format($total_sq_ft,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".number_format($total_leased,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".number_format($total_repair,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".number_format($total_net_sq_ft,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td width=80 align=center><b><i>".number_format($total_occupied,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td width=90 align=center><b><i>".round(($total_occupied / $total_net_sq_ft) * 100)."%</i></b></td>\r\n";
	$body .= "<td width=90 align=center><b><i>".round((($total_occupied + $total_leased) / $total_sq_ft) * 100)."%</i></b></td></tr>\r\n";
	$body .= "</table>\r\n";


	// Pallet allocation table
	$total_net_sq_ft = 0;
	$total_plts = 0;
	$total_max = 0;
	$total_used = 0;
	$total_available = 0;


	$body .= "<br \><br \>\r\n";
	$body .= "</td></tr>\r\n";
	$body .= "<tr><td>\r\n"; 
	$body .= "<table border = 1  width=580 cellspacing=1 cellpadding=1 bgcolor=#000000>\r\n";

	$body .= "<tr bgcolor=#ffffff><td width=110 align=center rowspan=2><b>Sq. Ft. <br \>Net</b></td>\r\n";
	$body .= "<td width = 130 align = center rowspan= 2><b>Pallets in Inventory</b></td>\r\n";
	$body .= "<td width = 270 align = center colspan= 3><b>Floor Positions</b></td>\r\n";
	$body .= "<td width = 90 align = center rowspan= 2><b>Utilization <br \>Net</b></td></tr>\r\n";
	$body .= "<tr bgcolor=#ffffff><td width=90 align=center><b>Max</b></td>\r\n";
	$body .= "<td width = 90 align = center><b>Used</b></td>\r\n";
	$body .= "<td width=90 align=center><b>Available</b></td></tr>\r\n";	
	
	$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
	ora_parse($whse_cursor_bni, $sql);
	ora_exec($whse_cursor_bni);
	while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$total_net_sq_ft += $net_sq_ft[$whse_row['WAREHOUSE']];

		$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$run_date."' AND WAREHOUSE = '".$whse_row['WAREHOUSE']."Total'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$this_row_plts = $Short_Term_Row_BNI['PALLET_COUNT'];
		$total_plts += $this_row_plts;

		$this_row_max = round($net_sq_ft[$whse_row['WAREHOUSE']] / $factor);
		$total_max += $this_row_max;

		$sql = "SELECT PLT_SQ_FEET FROM WAREHOUSE_DAILY_BREAKDOWN WHERE WAREHOUSE = '".$whse_row['WAREHOUSE']."Total' AND TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$run_date."'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$this_row_used = round($Short_Term_Row_BNI['PLT_SQ_FEET'] / 29);
		$total_used += $this_row_used;

		$this_row_available = $this_row_max - $this_row_used;
		$total_available += $this_row_available;

		if($this_row_max != 0){
			$this_row_net_pct = round(($this_row_used / $this_row_max) * 100);
		} else {
			$this_row_net_pct = 0;
		}


		$body .= "<tr bgcolor=#ffffff><td align=center>".$whse_row['WAREHOUSE']."-".number_format($net_sq_ft[$whse_row['WAREHOUSE']],0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_plts,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_max,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_used,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".number_format($this_row_available,0,'.',',')."</td>\r\n";
		$body .= "<td align=center>".$this_row_net_pct."%</td></tr>\r\n";
	}

	$body .= "<tr bgcolor=#ffffff><td align=center><b><i>Total- ".number_format($total_net_sq_ft,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".number_format($total_plts,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".number_format($total_max,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".number_format($total_used,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".number_format($total_available,0,'.',',')."</i></b></td>\r\n";
	$body .= "<td align=center><b><i>".round(($total_used / $total_max) * 100)."%</i></b></td></tr>\r\n";
	$body .= "</table>\r\n";
	$body .= "</td></tr>\r\n";

  	$body .= "</table>\r\n";

	// the non-warehouse-checked pallets (which may actually be in a warehouse, but due to table WAREHOUSE, wasn't on this days checklist)
	$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$run_date."' AND WAREHOUSE = 'NotInRep'";
	ora_parse($Short_Term_Cursor_BNI, $sql);
	ora_exec($Short_Term_Cursor_BNI);
	ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	
	$body .= "<br \><font size=3>&nbsp;&nbsp;Pallets in F and unrecognized locations:  ".$Short_Term_Row_BNI['PALLET_COUNT']."\r\n";

	// lease/repair readout
	$body .= "<br \><br \><font size=4>&nbsp;&nbsp;Current Leases/Repair:</font>\r\n";
	$sql = "SELECT SQ_FEET, WL.WAREHOUSE || ' ' || WL.BOX THE_WHSE, CUSTOMER FROM WAREHOUSE_LEASE WL, WAREHOUSE_BOX_DETAIL WBD WHERE WL.WAREHOUSE = WBD.WAREHOUSE AND WL.BOX = WBD.BOX AND (WL.START_DATE IS NULL OR WL.START_DATE < SYSDATE - 1) AND (WL.END_DATE IS NULL OR WL.END_DATE > SYSDATE) ORDER BY WL.WAREHOUSE || ' ' || WL.BOX";
	ora_parse($Short_Term_Cursor_BNI, $sql);
	ora_exec($Short_Term_Cursor_BNI);
	if(!ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body .= "None\r\n";
	} else {
		do {
			$body .= "<br \><font size = 2>&nbsp;&nbsp;&nbsp;&nbsp;".$Short_Term_Row_BNI['CUSTOMER']." -- ".$Short_Term_Row_BNI['THE_WHSE']." -- ".$Short_Term_Row_BNI['SQ_FEET']." Sq.Ft.</font>\r\n";
		} while(ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}

	$body .= "<br><br>\r\n";


	// breakdown within boxes
	$body .= "<table border = 1 width=550 cellspacing=1 cellpadding=1>\r\n";
	$body .= "<tr><td>&nbsp;</td><td align=center colspan=10><b>Box Breakdown</b></td></tr>\r\n";
	$body .= "<tr><td align=center width=55>Box</td><td align=center width=55>Total</td>\r\n";
	$body .= "<td align=center width=55><b>1</b></td>\r\n";
	$body .= "<td align=center width=55><b>2</b></td>\r\n";
	$body .= "<td align=center width=55><b>3</b></td>\r\n";
	$body .= "<td align=center width=55><b>4</b></td>\r\n";
	$body .= "<td align=center width=55><b>5</b></td>\r\n";
	$body .= "<td align=center width=55><b>6</b></td>\r\n";
	$body .= "<td align=center width=55><b>7</b></td>\r\n";
	$body .= "<td align=center width=55><b>8</b></td>\r\n";
	$body .= "<td align=center width=55><b>Other</b></td></tr>\r\n";

	$sql = "SELECT WAREHOUSE FROM WAREHOUSE ORDER BY WAREHOUSE";
	ora_parse($whse_cursor_bni, $sql);
	ora_exec($whse_cursor_bni);
	while(ora_fetch_into($whse_cursor_bni, $whse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body .= "<tr><td>".$whse_row['WAREHOUSE']."</td>\r\n";

		$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."' AND WAREHOUSE = '".$whse_row['WAREHOUSE']."Total'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$body .= "<td>".$Short_Term_Row_BNI['PALLET_COUNT']."</td>\r\n";

		for($i = 1; $i < 9; $i++){
			$sql = "SELECT TEMPERATURE_DISPLAY FROM WAREHOUSE_LOCATION WHERE WHSE = '".$whse_row['WAREHOUSE']."' AND BOX = '".$i."'";
			ora_parse($LCScursor, $sql);
			ora_exec($LCScursor);
			if(!ora_fetch_into($LCScursor, $LCS_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ 
				$body .= "<td>--</td>\r\n";
			} elseif($LCS_row['TEMPERATURE_DISPLAY'] == "N"){
				$body .= "<td>*</td>\r\n";
			} else {				
				$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."' AND WAREHOUSE = '".$whse_row['WAREHOUSE'].$i."'";
				ora_parse($Short_Term_Cursor_BNI, $sql);
				ora_exec($Short_Term_Cursor_BNI);
				ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				$body .= "<td>".$Short_Term_Row_BNI['PALLET_COUNT']."</td>\r\n";
			}
		}

		$sql = "SELECT PALLET_COUNT FROM WAREHOUSE_DAILY_BREAKDOWN WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".date('m/d/Y')."' AND WAREHOUSE = '".$whse_row['WAREHOUSE']."Other'";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $Short_Term_Row_BNI, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$body .= "<td>".$Short_Term_Row_BNI['PALLET_COUNT']."</td></tr>\r\n";
	}
	$body .= "<tr><td colspan=\"10\">Boxes with an * are not under DSPC control</td></tr>";
	$body .= "</table>\r\n";

	$body .= "</body>\r\n";
	$body .= "</html>\r\n";





	// REPORT CREATED.
	// what do we do with it?
	$Gross_util_pct = round((($total_occupied + $total_leased) / $total_sq_ft) * 100);
	$leased_total_pct = round(($total_leased / $total_sq_ft) * 100);
	$repair_total_pct = round(($total_repair / $total_sq_ft) * 100);
	$occupied_pct = round(($total_occupied / $total_sq_ft) * 100);
	$empty_sq_ft = round($total_sq_ft - ($total_occupied + $total_leased + $total_repair));
	$empty_pct = round(($empty_sq_ft / $total_sq_ft) * 100);


	$mailTO = "lstewart@port.state.de.us,awalter@port.state.de.us";

	$mailTo1 .= "gbailey@port.state.de.us,";
	$mailTo1 .= "fvignuli@port.state.de.us,";
	$mailTo1 .= "ithomas@port.state.de.us,";
	$mailTo1 .= "rhorne@port.state.de.us,";
	$mailTo1 .= "parul@port.state.de.us,";
	$mailTo1 .= "tkeefer@port.state.de.us,";

	$mailTo1 .= "jjaffe@port.state.de.us,";
	$mailTo1 .= "wstans@port.state.de.us,";
	$mailTo1 .= "ddonofrio@port.state.de.us,";
	$mailTo1 .= "vfarkas@port.state.de.us,";
	$mailTo1 .= "cfoster@port.state.de.us,";
	$mailTo1 .= "martym@port.state.de.us,";
	$mailTo1 .= "amarkow@port.state.de.us,";
	$mailTo1 .= "jharoldson@port.state.de.us,";
	$mailTo1 .= "msimpson@port.state.de.us,";
	$mailTo1 .= "vnbecker@port.state.de.us,";
	$mailTo1 .= "bdempsey@port.state.de.us,";
	$mailTo1 .= "hdadmin@port.state.de.us\r\n";

	$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "lstewart@port.state.de.us,ltreut@port.state.de.us,awalter@port.state.de.us,moslowe@port.state.de.us,sadu@port.state.de.us\r\n"; 
	$mailheaders .= "Cc: " . "lstewart@port.state.de.us,awalter@port.state.de.us,sadu@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
	$mailheaders .= "Content-Type: text/html\r\n";


	$arg1 = $HTTP_SERVER_VARS["argv"][1];
	$mailsubject = "Reefer Whse Utilization:  $Gross_util_pct% - Leased $leased_total_pct% (".number_format($total_leased,0,'.',',')." sf)  Repair $repair_total_pct% (".number_format($total_repair,0,'.',',')." sf) Occupied $occupied_pct% (".number_format($total_occupied,0,'.',',')." sf)  Empty $empty_pct% (".number_format($empty_sq_ft,0,'.',',')." sf).  Factor:  $factor sf/plt";


	if ($arg1 == "Everyone"){
		// the all-port run of the report.  Change the subject if not confirmed.
		if($isConfirm == false){
			$mailsubject .= " (Not Confirmed By Ops)";
		} else {
			$mailsubject .= " (CONFIRMED)";
		}

//		mail($mailTo1, $mailsubject, $body, $mailheaders);
//		mail("lstewart@port.state.de.us,awalter@port.state.de.us", $mailsubject, $body, $mailheaders); // TEST EMAIL
		mail("awalter@port.state.de.us", $mailsubject, $body, $mailheaders); // TEST EMAIL
		exit;
	}else {
		// this is the choice for the early-morning email sent to select INVE members for approval before the mass-email.
		// change the subject line for just INVE

		$mailsubject = "(waiting for confirmation) ".$mailsubject;
		mail($arg1, $mailsubject, $body, $mailheaders);
		exit;
	}
?>
