<?
/*
*	Adam Walter, Nov 2011
*
*	because we are willing to spend any amount of time doing
*	Anything for anyone, here is a script that mails
*	Vandenburg (2206), on a daily basis, pretty much
*	Every bill we send them... in BOTH pdf and csv.
*************************************************************/

	include 'class.ezpdf.php';
/*   $date = $HTTP_SERVER_VARS["argv"][1]; 
   if($date == ""){
	   $date = date('m/d/Y');
   }
14 8*/
	$start_offset = 14;
	$end_offset = 8;

//	$start_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - $start_offset, date('Y')));
//	$end_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - $end_offset, date('Y')));
//	echo $start_date."   ".$end_date."\n";

//  $RF_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $RF_conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($RF_conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($RF_conn));
    	printf("Please try later!");
    	exit;
   }
   $RF_cursor = ora_open($RF_conn);
   $ED_cursor = ora_open($RF_conn); // for email
   $RF_Detail_Cursor = ora_open($RF_conn);
   $RF_Short_Term_Cursor = ora_open($RF_conn);

//  $BNI_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  $BNI_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($BNI_conn < 1){
    	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($BNI_conn));
    	printf("Please try later!");
    	exit;
   }
   $BNI_cursor = ora_open($BNI_conn);
   $BNI_Short_Term_Cursor = ora_open($BNI_conn);

	$labor_counter = 0;
	$misc_counter = 0;
	$lease_counter = 0;
	$storage_counter = 0;
	$labor_counter_csv = 0;
	$labor_counter_details_csv = 0;
	$storage_counter_pdf = 0;
	$combo_counter_csv = 0;

	$labor_pdf = "";
	$labor_csv = "";
	$labor_details_csv = "";
	$misc_pdf = "";
	$storage_csv = "";
	$storage_pdf = "";
	$combo_csv = "";

	$labor_details_dateclause = "('01/01/1950'";


	// LABOR TICKET SECTION ***************************
	// Labor Pdf
//				AND INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= '15-dec-2011'
	$sql = "SELECT TO_CHAR(SERVICE_START, 'MM/DD/YYYY') START_DATE, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') END_DATE, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_SERV,
				TO_CHAR(SERVICE_START, 'HH:MI AM') START_TIME, TO_CHAR(SERVICE_STOP, 'HH:MI AM') STOP_TIME, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, 
				INVOICE_NUM, VESSEL_NAME, SERVICE_DESCRIPTION, SERVICE_QTY, SERVICE_RATE, SERVICE_AMOUNT, LABOR_TICKET_NUM
			FROM BILLING BIL, VESSEL_PROFILE VP
			WHERE BIL.LR_NUM = VP.LR_NUM
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE = 'LABOR'
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			ORDER BY INVOICE_NUM, LABOR_TICKET_NUM, SERVICE_DESCRIPTION";
	ora_parse($BNI_Short_Term_Cursor, $sql);
	ora_exec($BNI_Short_Term_Cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		$first_page = true;
//		$first_LT = true;
		$labor_pdf = new Cezpdf('letter');
		$labor_pdf->ezSetMargins(20,20,65,65);
		$labor_pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$labor_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

		$current_invoice = "beans"; // make sure we know when to move to next invoice
		$current_LT = "beans"; // make sure we know when to move to next ticket
		

		do {
//			echo $BNI_Short_Term_row['INVOICE_NUM']."\n";
			if($BNI_Short_Term_row['INVOICE_NUM'] != $current_invoice){
				if($first_page != true){
					$desc_amt = "$".number_format($LT_subtotal, 2);
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'Subtotal:',
													'totaldollar'=>$desc_amt));
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'',
													'totaldollar'=>''));
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'',
													'totaldollar'=>''));

					$desc_amt = "$".number_format($invoice_total, 2);
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'INVOICE TOTAL:',
													'totaldollar'=>$desc_amt));
					$labor_pdf->ezTable($detail_array, '', '', array('showHeadings'=>0, 
																'shaded'=>0, 
																'showLines'=>0,
																'fontSize'=>8,
																'width'=>550,
																'colGap'=>2,
																'cols'=>array('datefield'=>array('justification'=>'left', 'width'=>50),
																			'desc'=>array('justification'=>'left', 'width'=>400),
																			'totaltext'=>array('justification'=>'right', 'width'=>50),
																			'totaldollar'=>array('justification'=>'right', 'width'=>50))));
					$labor_counter++;
					$labor_pdf->ezNewPage();
					$labor_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
				} else {
					$first_page = false;
				}
				$labor_pdf->ezText("Invoice Date:  ".$BNI_Short_Term_row['INV_DATE'], 10, $right);
				$labor_pdf->ezSetDy(-5);
				$labor_pdf->ezText("***LABOR CHARGE***", 14, $center);
				$labor_pdf->ezSetDy(-10);
				$labor_pdf->ezText("Invoice#  ".$BNI_Short_Term_row['INVOICE_NUM'], 12, $center);
				$labor_pdf->ezSetDy(-5);
				$labor_pdf->ezText("Vessel:  ".$BNI_Short_Term_row['VESSEL_NAME'], 12, $center);
				$labor_pdf->ezSetDy(-15);

				$current_invoice = $BNI_Short_Term_row['INVOICE_NUM'];
													
				$detail_array = array();
				$invoice_total = 0;
				$LT_subtotal = 0;
				$first_LT = true;
			}

			list($service, $employee, $temp) = split(":", $BNI_Short_Term_row['SERVICE_DESCRIPTION'], 3);
			$service = trim($service);
			$employee = trim($employee);
			
			if ($employee == "") {
			  $employee = "OPERATOR";
			}

			if ($row['SERVICE_QTY'] != 1) {
			  $employee .= "S";
			}

			if($BNI_Short_Term_row['LABOR_TICKET_NUM'] != $current_LT){
				if($first_LT != true){
					$desc_amt = "$".number_format($LT_subtotal, 2);
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'Subtotal:',
													'totaldollar'=>$desc_amt));
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'',
													'totaldollar'=>''));
					$LT_subtotal = 0;
				} else {
					$first_LT = false;
				}
				if($BNI_Short_Term_row['LABOR_TICKET_NUM'] == ""){
					$desc_line = $service.":";
				} else {
					$desc_line = $service."  (LT #: ".$BNI_Short_Term_row['LABOR_TICKET_NUM'].")";
				}
				array_push($detail_array, array('datefield'=>$BNI_Short_Term_row['THE_SERV'],
												'desc'=>$desc_line,
												'totaltext'=>'',
												'totaldollar'=>''));
				$current_LT = $BNI_Short_Term_row['LABOR_TICKET_NUM'];
				$LT_subtotal = 0;
			}

			if ($BNI_Short_Term_row['START_TIME'] == "12:00 AM" && $BNI_Short_Term_row['STOP_TIME'] == "12:00 AM"){
				if($BNI_Short_Term_row['SERVICE_RATE'] != 0 && $BNI_Short_Term_row['SERVICE_QTY'] != 0){
					$hours = round($BNI_Short_Term_row['SERVICE_AMOUNT'] / $BNI_Short_Term_row['SERVICE_RATE'] / $BNI_Short_Term_row['SERVICE_QTY'], 1);
				} else {
					$hours = "UNKNOWN";
				}

				$desc_line = $BNI_Short_Term_row['SERVICE_QTY']." ".$employee.":  ".$hours." HRS @ $".$BNI_Short_Term_row['SERVICE_RATE']." / HR";
				$desc_amt = $BNI_Short_Term_row['SERVICE_AMOUNT'];
			} else {
				$desc_line = $BNI_Short_Term_row['SERVICE_QTY']." ".$employee.":  ".$BNI_Short_Term_row['START_TIME']." - ".$BNI_Short_Term_row['STOP_TIME']." @ $". $BNI_Short_Term_row['SERVICE_RATE']." / HR";
				$desc_amt = $BNI_Short_Term_row['SERVICE_AMOUNT'];
			}

			$invoice_total += $desc_amt;
			$LT_subtotal += $desc_amt;
			$desc_amt = "$".number_format($desc_amt, 2);

			array_push($detail_array, array('datefield'=>'',
											'desc'=>$desc_line,
											'totaltext'=>'',
											'totaldollar'=>$desc_amt));
		} while(ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$desc_amt = "$".number_format($LT_subtotal, 2);
		array_push($detail_array, array('datefield'=>'',
										'desc'=>'',
										'totaltext'=>'Subtotal:',
										'totaldollar'=>$desc_amt));
		array_push($detail_array, array('datefield'=>'',
										'desc'=>'',
										'totaltext'=>'',
										'totaldollar'=>''));
		array_push($detail_array, array('datefield'=>'',
										'desc'=>'',
										'totaltext'=>'',
										'totaldollar'=>''));

		$desc_amt = "$".number_format($invoice_total, 2);
		array_push($detail_array, array('datefield'=>'',
										'desc'=>'',
										'totaltext'=>'INVOICE TOTAL:',
										'totaldollar'=>$desc_amt));
		$labor_pdf->ezTable($detail_array, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>0,
													'fontSize'=>8,
													'width'=>550,
													'colGap'=>2,
													'cols'=>array('datefield'=>array('justification'=>'left', 'width'=>50),
																'desc'=>array('justification'=>'left', 'width'=>400),
																'totaltext'=>array('justification'=>'right', 'width'=>50),
																'totaldollar'=>array('justification'=>'right', 'width'=>50))));
		$labor_counter++;
	}


	// labor csv
	// calculate each of the days billed amounts...
	$labor_details_csv = "INVOICE#,DATE SCANNED,ORDER NUMBER,TRANSACTION TYPE,ARRIVAL NUM,PALLET ID,SCAN TIME\n";
	$combo_csv = "INVOICE#,INVOICE DATE,LR,VESSEL,SERVICE,PALLET ID,DESCRIPTION,AMOUNT,BILLED QTY\n";
//	$labor_csv = "INVOICE#,INVOICE DATE,LR,VESSEL,SERVICE,PALLET ID,DESCRIPTION,AMOUNT,BILLED QTY\n";
//	for($day = $start_offset; $day >= $end_offset; $day--){
//		$this_day = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - $day, date('Y')));
		// calculate each of the days billed amounts...
//					AND TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') = '".$this_day."'
//				AND INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= '15-dec-2011'
	$sql = "SELECT TO_CHAR(SERVICE_START, 'MM/DD/YYYY HH24:MI:SS') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY HH24:MI:SS') THE_STOP,  TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_SERV,
				INVOICE_NUM, SUM(SERVICE_AMOUNT) THE_SUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE
			FROM BILLING BIL, VESSEL_PROFILE VP
			WHERE BIL.LR_NUM = VP.LR_NUM
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE = 'LABOR'
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			GROUP BY TO_CHAR(SERVICE_START, 'MM/DD/YYYY HH24:MI:SS'), TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY HH24:MI:SS'),
				INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY'), TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY')
			ORDER BY INVOICE_NUM, TO_CHAR(SERVICE_START, 'MM/DD/YYYY HH24:MI:SS'), TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY HH24:MI:SS')";
	ora_parse($BNI_cursor, $sql);
	ora_exec($BNI_cursor);
//		echo $sql."\n";
	while(ora_fetch_into($BNI_cursor, $BNI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		$dollar_value = $BNI_row['THE_SUM'];
		$start_time = $BNI_row['THE_START'];
		$stop_time = $BNI_row['THE_STOP'];
		$invoice_num = $BNI_row['INVOICE_NUM'];
		$invoice_date = $BNI_row['INV_DATE'];
		$serv_date = $BNI_row['THE_SERV'];

		if($dollar_value == 0){
//			$labor_csv .= "No Labor bills for ".$this_day."\n";
		} else {
			$combo_csv .= $invoice_num.",".$invoice_date.",N/A,N/A,";
			$combo_csv .= "LABOR,See Detail,LABOR TICKET,".number_format($dollar_value, 2, ".", "").",N/A\n";

//			$labor_details_dateclause .= ", '".$serv_date."'";

			$sql = "SELECT SUM(SERVICE_CODE) THE_COUNT
					FROM BILLING
					WHERE INVOICE_NUM = '".$invoice_num."'
						AND SERVICE_STATUS = 'INVOICED'
						AND BILLING_TYPE = 'LABOR'
						AND EXPORT_FILE IS NULL
						AND CUSTOMER_ID = '2206'
						AND SERVICE_CODE IN ('6220', '6221', '6224', '6225', '6229')";
			ora_parse($BNI_Short_Term_Cursor, $sql);
			ora_exec($BNI_Short_Term_Cursor);
			ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($BNI_Short_Term_Row['THE_COUNT'] >= 1){
				// HD TS-09
				// if this is a "active" invoice as determined by the "service code" clause above, then we add the pallets scanned
				// during NON-STRAIGHTTIME hours to a detail file.
				$sql = "SELECT PALLET_ID, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') SCAN_DATE, TO_CHAR(DATE_OF_ACTIVITY, 'HH24:MI:SS') SCAN_TIME, ORDER_NUM,
							DECODE(SERVICE_CODE, '6', 'SHIP OUT', '8', 'TRUCK IN', 'UNKNOWN') THE_SERVICE, ARRIVAL_NUM
						FROM CARGO_ACTIVITY CA
						WHERE DATE_OF_ACTIVITY <= TO_DATE('".$stop_time."', 'MM/DD/YYYY HH24:MI:SS')
							AND DATE_OF_ACTIVITY >= TO_DATE('".$start_time."', 'MM/DD/YYYY HH24:MI:SS')
							AND (
								TO_CHAR(DATE_OF_ACTIVITY, 'HH24') = '12'
								OR
								TO_NUMBER(TO_CHAR(DATE_OF_ACTIVITY, 'HH24')) >= 17
								OR
								TO_NUMBER(TO_CHAR(DATE_OF_ACTIVITY, 'HH24')) < 8
								OR
								TRIM(TO_CHAR(DATE_OF_ACTIVITY, 'DAY')) = 'SATURDAY'
								OR
								TRIM(TO_CHAR(DATE_OF_ACTIVITY, 'DAY')) = 'SUNDAY'
								OR
								TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') IN
									(SELECT TO_CHAR(HOLIDAY_DATE, 'MM/DD/YYYY')
									FROM HOLIDAY_SCHEDULE)
								)
							AND CUSTOMER_ID = '2206'
							AND SERVICE_CODE IN ('6', '8')
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY'), ORDER_NUM, PALLET_ID";
				ora_parse($RF_Detail_Cursor, $sql);
				ora_exec($RF_Detail_Cursor);
				echo $sql."\n";
				if(!ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$labor_details_csv .= $invoice_num."  has no pallets scanned for Truck Loading or Unloading on ".substr($start_time, 0, 10).".\n";
				} else {
					do {
						$labor_details_csv .= $invoice_num.",".$RF_Detail_row['SCAN_DATE'].",".$RF_Detail_row['ORDER_NUM'].",".
												$RF_Detail_row['THE_SERVICE'].",".$RF_Detail_row['ARRIVAL_NUM'].",".$RF_Detail_row['PALLET_ID'].",".$RF_Detail_row['SCAN_TIME']."\n";
					} while(ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
				}
			}

			

			
			// we have a $ value for this day.  get any and all time-appropriate pallets to "divvy" it up on.
/*			$sql = "SELECT DISTINCT PALLET_ID, NVL(VESSEL_NAME, 'UNKNOWN') THE_VES, CA.ARRIVAL_NUM
					FROM CARGO_ACTIVITY CA, VESSEL_PROFILE VP
					WHERE DATE_OF_ACTIVITY <= TO_DATE('".$stop_time."', 'MM/DD/YYYY HH24:MI:SS')
						AND DATE_OF_ACTIVITY >= TO_DATE('".$start_time."', 'MM/DD/YYYY HH24:MI:SS')
						AND CUSTOMER_ID = '2206'
						AND SERVICE_CODE = '6'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND CA.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					ORDER BY PALLET_ID";
			ora_parse($RF_Detail_Cursor, $sql);
			ora_exec($RF_Detail_Cursor);
			echo $sql."\n";
			if(!ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$combo_csv .= $invoice_num.",".$invoice_date.",N/A,N/A,LABOR,None Reported,LABOR TICKET,".number_format($dollar_value, 2, ".", "").",N/A\n";
			} else {
				$pallet_list = array();
				$pallet_LR_list = array();
				$pallet_vesname_list = array();
				$pallet_counter = 0;
				do {
					$pallet_list[$pallet_counter] = $RF_Detail_row['PALLET_ID'];
					$pallet_LR_list[$pallet_counter] = $RF_Detail_row['ARRIVAL_NUM'];
					$pallet_vesname_list[$pallet_counter] = $RF_Detail_row['THE_VES'];
					$pallet_counter++;
				} while(ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

				$per_pallet_value = round(($dollar_value / $pallet_counter), 2);

				for($i = 0; $i < $pallet_counter; $i++){
					$combo_csv .= $invoice_num.",".$invoice_date.",".$pallet_LR_list[$i].",".$pallet_vesname_list[$i].",";
					$combo_csv .= "LABOR,".$pallet_list[$i].",LABOR TICKET,".number_format($per_pallet_value, 2, ".", "").",1\n";
				}
			}
//			$labor_csv .= "\n"; */
			$combo_counter_csv++;
		}
	}
//	$labor_details_dateclause .= ")";
//		$labor_csv .= "\n";

/*	
	$sql = "SELECT PALLET_ID, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') SCAN_DATE, TO_CHAR(DATE_OF_ACTIVITY, 'HH24:MI:SS') SCAN_TIME, ORDER_NUM,
				DECODE(SERVICE_CODE, '6', 'SHIP OUT', '8', 'TRUCK IN', 'UNKNOWN') THE_SERVICE
			FROM CARGO_ACTIVITY CA
			WHERE TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') IN ".$labor_details_dateclause."
				AND (
					TO_CHAR(DATE_OF_ACTIVITY, 'HH24') = '12'
					OR
					TO_NUMBER(TO_CHAR(DATE_OF_ACTIVITY, 'HH24')) >= 17
					OR
					TO_NUMBER(TO_CHAR(DATE_OF_ACTIVITY, 'HH24')) < 8
					OR
					TO_CHAR(DATE_OF_ACTIVITY, 'DAY') = 'SATURDAY'
					OR
					TO_CHAR(DATE_OF_ACTIVITY, 'DAY') = 'SUNDAY'
					OR
					TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') IN
						(SELECT TO_CHAR(HOLIDAY_DATE, 'MM/DD/YYYY')
						FROM HOLIDAY_SCHEDULE)
					)
				AND CUSTOMER_ID = '2206'
				AND SERVICE_CODE IN ('6', '8')
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
			ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY'), ORDER_NUM, PALLET_ID";
	ora_parse($RF_Detail_Cursor, $sql);
	ora_exec($RF_Detail_Cursor);
	echo $sql."\n";
	while(ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$labor_details_csv .= $RF_Detail_row['SCAN_DATE'].",".$RF_Detail_row['ORDER_NUM'].",".
								$RF_Detail_row['THE_SERVICE'].",".$RF_Detail_row['PALLET_ID'].",".$RF_Detail_row['SCAN_TIME']."\n";
	}
*/




	// LEASE SECTION *****************************************
	// lease csv
	$sql = "SELECT INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, 'N/A' THE_LR, 'N/A' THE_VESNAME, BILLING_TYPE, SERVICE_DESCRIPTION, SERVICE_AMOUNT, 'N/A' SERV_QTY
			FROM BILL_HEADER BH, BILL_DETAIL BD
			WHERE BH.BILLING_NUM = BD.BILLING_NUM
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE = 'LEASE'
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			ORDER BY INVOICE_NUM, SERVICE_DESCRIPTION";
	ora_parse($BNI_Short_Term_Cursor, $sql);
	ora_exec($BNI_Short_Term_Cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {

		do {
			$BNI_Short_Term_row = str_replace(",", "", $BNI_Short_Term_row);

			$combo_csv .= $BNI_Short_Term_row['INVOICE_NUM'].",".$BNI_Short_Term_row['INV_DATE'].",".$BNI_Short_Term_row['THE_LR'].",".$BNI_Short_Term_row['THE_VESNAME'].",";
			$combo_csv .= $BNI_Short_Term_row['BILLING_TYPE'].",N/A,".$BNI_Short_Term_row['SERVICE_DESCRIPTION'].",";
			$combo_csv .= number_format($BNI_Short_Term_row['SERVICE_AMOUNT'], 2, ".", "").",".$BNI_Short_Term_row['SERV_QTY']."\n";

//			$storage_csv .= ",,,,,,TOTAL:,"."$".number_format($RF_row['SERVICE_AMOUNT'], 2, ".", "")."\n\n";
			$combo_counter_csv++;

		} while(ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}


	// lease PDF
	// as per Inigo instruction, I am purging the "multiple lease" logic from this, and assuming that 1 run of this program will only have 1 invoice#.
	// that one invoice can still have multiple lines, of course.
	$sql = "SELECT INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, 'N/A' THE_LR, 'N/A' THE_VESNAME, BILLING_TYPE, SERVICE_DESCRIPTION, SERVICE_AMOUNT, 'N/A' SERV_QTY,
				TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_SERV, SERVICE_RATE, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') START_TIME, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') STOP_TIME
			FROM BILL_HEADER BH, BILL_DETAIL BD
			WHERE BH.BILLING_NUM = BD.BILLING_NUM
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE = 'LEASE'
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			ORDER BY INVOICE_NUM, SERVICE_DESCRIPTION";
	ora_parse($BNI_cursor, $sql);
	ora_exec($BNI_cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($BNI_cursor, $BNI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		$invoice_total = 0;
		$lease_pdf = new Cezpdf('letter');
		$lease_pdf->ezSetMargins(20,20,65,65);
		$lease_pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
//		$lease_pdf->addJpegFromFile('POW_logo_in_black.jpg', 420, 700, 100, 40);
		$lease_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

		$lease_pdf->ezText("Invoice#  ".$BNI_row['INVOICE_NUM'], 12, $right);
		$lease_pdf->ezSetDy(-3);
		$lease_pdf->ezText("Invoice Date:  ".$BNI_row['INV_DATE'], 12, $right);
		$lease_pdf->ezSetDy(-25);

		$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '2206'";
		ora_parse($BNI_Short_Term_Cursor, $sql);
		ora_exec($BNI_Short_Term_Cursor);
		ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$lease_pdf->ezText($BNI_Short_Term_row['CUSTOMER_NAME'], 10, array('aleft'=>130));
		$lease_pdf->ezSetDy(-2);
		$lease_pdf->ezText($BNI_Short_Term_row['CUSTOMER_ADDRESS1'], 10, array('aleft'=>130));
		$lease_pdf->ezSetDy(-2);
		$lease_pdf->ezText($BNI_Short_Term_row['CUSTOMER_ADDRESS2'], 10, array('aleft'=>130));
		$lease_pdf->ezSetDy(-2);
		$lease_pdf->ezText($BNI_Short_Term_row['CUSTOMER_CITY'].",".$BNI_Short_Term_row['CUSTOMER_STATE']."  ".$BNI_Short_Term_row['CUSTOMER_ZIP'], 10, array('aleft'=>130));
		$lease_pdf->ezSetDy(-50);

		$detail_array = array();

		do {
			$desc_amt = $BNI_row['SERVICE_AMOUNT'];
			$invoice_total += $desc_amt;
			$desc_amt = "$".number_format($desc_amt, 2);

			$descript = $BNI_row['SERVICE_DESCRIPTION'];

			array_push($detail_array, array('datefield'=>$BNI_row['THE_SERV'],
											'desc'=>$descript,
											'totaltext'=>'',
											'totaldollar'=>$desc_amt));
		} while(ora_fetch_into($BNI_cursor, $BNI_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$desc_amt = "$".number_format($invoice_total, 2);
		array_push($detail_array, array('datefield'=>'',
										'desc'=>'',
										'totaltext'=>'INVOICE TOTAL:',
										'totaldollar'=>$desc_amt));

		$lease_pdf->ezTable($detail_array, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>0,
													'fontSize'=>8,
													'width'=>550,
													'colGap'=>2,
													'cols'=>array('datefield'=>array('justification'=>'left', 'width'=>50),
																'desc'=>array('justification'=>'left', 'width'=>395),
																'totaltext'=>array('justification'=>'right', 'width'=>70),
																'totaldollar'=>array('justification'=>'right', 'width'=>35))));


		$lease_counter++;
	}






	// MISCBILL SECTION ***************************	
	// miscbill csv
	$sql = "SELECT TO_CHAR(SERVICE_START, 'MM/DD/YYYY') START_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_SERV,
				TO_CHAR(SERVICE_START, 'HH:MI AM') START_TIME, TO_CHAR(SERVICE_STOP, 'HH:MI AM') STOP_TIME, BIL.LR_NUM, BILLING_TYPE,
				INVOICE_NUM, VESSEL_NAME, SERVICE_DESCRIPTION, SERVICE_QTY, SERVICE_RATE, SERVICE_AMOUNT, LABOR_TICKET_NUM
			FROM BILLING BIL, VESSEL_PROFILE VP
			WHERE BIL.LR_NUM = VP.LR_NUM
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE NOT IN ('LABOR', 'LEASE', 'STORAGE', 'CM', 'DM')
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			ORDER BY INVOICE_NUM, LABOR_TICKET_NUM, SERVICE_DESCRIPTION";
	ora_parse($BNI_Short_Term_Cursor, $sql);
	ora_exec($BNI_Short_Term_Cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {

		do {
			$BNI_Short_Term_row = str_replace(",", "", $BNI_Short_Term_row);

			$combo_csv .= $BNI_Short_Term_row['INVOICE_NUM'].",".$BNI_Short_Term_row['INV_DATE'].",".$BNI_Short_Term_row['LR_NUM'].",".$BNI_Short_Term_row['VESSEL_NAME'].",";
			$combo_csv .= $BNI_Short_Term_row['BILLING_TYPE'].",N/A,".$BNI_Short_Term_row['SERVICE_DESCRIPTION'].",";
			$combo_csv .= number_format($BNI_Short_Term_row['SERVICE_AMOUNT'], 2, ".", "").",".$BNI_Short_Term_row['SERVICE_QTY']."\n";

//			$storage_csv .= ",,,,,,TOTAL:,"."$".number_format($RF_row['SERVICE_AMOUNT'], 2, ".", "")."\n\n";
			$combo_counter_csv++;

		} while(ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}


	// miscbill PDF
	$sql = "SELECT TO_CHAR(SERVICE_START, 'MM/DD/YYYY') START_DATE, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') END_DATE, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_SERV,
				TO_CHAR(SERVICE_START, 'HH:MI AM') START_TIME, TO_CHAR(SERVICE_STOP, 'HH:MI AM') STOP_TIME, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE,
				INVOICE_NUM, VESSEL_NAME, SERVICE_DESCRIPTION, SERVICE_QTY, SERVICE_RATE, SERVICE_AMOUNT, LABOR_TICKET_NUM, BILLING_TYPE, LOT_NUM, SERVICE_RATE, BILLING_NUM
			FROM BILLING BIL, VESSEL_PROFILE VP
			WHERE BIL.LR_NUM = VP.LR_NUM
				AND SERVICE_STATUS = 'INVOICED'
				AND BILLING_TYPE NOT IN ('LABOR', 'LEASE', 'STORAGE', 'CM', 'DM')
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			ORDER BY INVOICE_NUM, LABOR_TICKET_NUM, SERVICE_DESCRIPTION";
	ora_parse($BNI_Short_Term_Cursor, $sql);
	ora_exec($BNI_Short_Term_Cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		$first_page = true;
		$misc_pdf = new Cezpdf('letter');
		$misc_pdf->ezSetMargins(20,20,65,65);
		$misc_pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$misc_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

		$current_invoice = "beans"; // make sure we know when to move to next invoice
		do {
//			echo $BNI_Short_Term_row['INVOICE_NUM']."\n";
			if($BNI_Short_Term_row['INVOICE_NUM'] != $current_invoice){
				if($first_page != true){
					$desc_amt = "$".number_format($invoice_total, 2);
					array_push($detail_array, array('datefield'=>'',
													'desc'=>'',
													'totaltext'=>'INVOICE TOTAL:',
													'totaldollar'=>$desc_amt));
					$misc_pdf->ezTable($detail_array, '', '', array('showHeadings'=>0, 
																'shaded'=>0, 
																'showLines'=>0,
																'fontSize'=>8,
																'width'=>550,
																'colGap'=>2,
																'cols'=>array('datefield'=>array('justification'=>'left', 'width'=>50),
																			'desc'=>array('justification'=>'left', 'width'=>400),
																			'totaltext'=>array('justification'=>'right', 'width'=>50),
																			'totaldollar'=>array('justification'=>'right', 'width'=>50))));
					$misc_counter++;
					$misc_pdf->ezNewPage();
					$misc_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
				} else {
					$first_page = false;
				}
				$misc_pdf->ezText("Invoice Date:  ".$BNI_Short_Term_row['INV_DATE'], 10, $right);
				$misc_pdf->ezSetDy(-5);
				$misc_pdf->ezText("***MISCELLANEOUS CHARGE***", 14, $center);
				$misc_pdf->ezSetDy(-10);
				$misc_pdf->ezText("Invoice#  ".$BNI_Short_Term_row['INVOICE_NUM'], 12, $center);
				$misc_pdf->ezSetDy(-5);
				$misc_pdf->ezText("Vessel:  ".$BNI_Short_Term_row['VESSEL_NAME'], 12, $center);
				$misc_pdf->ezSetDy(-15);

				$current_invoice = $BNI_Short_Term_row['INVOICE_NUM'];
													
				$detail_array = array();
				$invoice_total = 0;
			}
			
			$desc_amt = $BNI_Short_Term_row['SERVICE_AMOUNT'];
			$invoice_total += $desc_amt;
			$desc_amt = "$".number_format($desc_amt, 2);

			if(trim($BNI_Short_Term_row['BILLING_TYPE']) != 'ADTRKLOAD'){
				$descript = $BNI_Short_Term_row['SERVICE_DESCRIPTION'];
			} else {
				$descript = "ADVANCED TRUCK LOADING -- Billing# ".$BNI_Short_Term_row['BILLING_NUM']."; Pallets: ".$BNI_Short_Term_row['LOT_NUM']."; Weight: ".$BNI_Short_Term_row['SERVICE_QTY']."LB @ $".$BNI_Short_Term_row['SERVICE_RATE']." / PLT";
			}

			array_push($detail_array, array('datefield'=>$BNI_Short_Term_row['THE_SERV'],
											'desc'=>$descript,
											'totaltext'=>'',
											'totaldollar'=>$desc_amt));
		} while(ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		$desc_amt = "$".number_format($invoice_total, 2);
		array_push($detail_array, array('datefield'=>'',
										'desc'=>'',
										'totaltext'=>'INVOICE TOTAL:',
										'totaldollar'=>$desc_amt));
//		print_r($detail_array);
		$misc_pdf->ezTable($detail_array, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>0,
													'fontSize'=>8,
													'width'=>550,
													'colGap'=>2,
													'cols'=>array('datefield'=>array('justification'=>'left', 'width'=>50),
																'desc'=>array('justification'=>'left', 'width'=>400),
																'totaltext'=>array('justification'=>'right', 'width'=>50),
																'totaldollar'=>array('justification'=>'right', 'width'=>50))));
		$misc_counter++;
	}









	// STORAGE SECTION ***************************
	// storage csv
		// note:  RF storage actually has 2 tables (header and detail), which means I dont have to pseudo-invent nested, 1-off while loops.  yay.
//				AND RF.INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
//				AND RF.INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= '15-dec-2011'
	$sql = "SELECT INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, RF.ARRIVAL_NUM,
				NVL(VP.VESSEL_NAME, 'TRUCK/TRANSFER') THE_VES, SERVICE_AMOUNT, BILLING_NUM
			FROM RF_BILLING RF, VESSEL_PROFILE VP
			WHERE RF.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
				AND RF.CUSTOMER_ID = '2206'
				AND EXPORT_FILE IS NULL
				AND RF.SERVICE_STATUS = 'INVOICED'
			ORDER BY INVOICE_DATE, INVOICE_NUM";
	ora_parse($RF_cursor, $sql);
	ora_exec($RF_cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
//		$storage_csv = "INVOICE#,INVOICE DATE,LR,VESSEL,SERVICE,PALLET ID,DESCRIPTION,AMOUNT,BILLED QTY\n";

		do {
//			echo $RF_row['INVOICE_NUM']."\n";

			$sql = "SELECT * FROM RF_BILLING_DETAIL
					WHERE SUM_BILL_NUM = '".$RF_row['BILLING_NUM']."'
						AND SERVICE_STATUS != 'DELETED'";
			ora_parse($RF_Detail_Cursor, $sql);
			ora_exec($RF_Detail_Cursor);
//			echo $sql."\n";
			while(ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$combo_csv .= $RF_row['INVOICE_NUM'].",".$RF_row['INV_DATE'].",".$RF_Detail_row['ARRIVAL_NUM'].",".$RF_row['THE_VES'].",";
				$combo_csv .= "STORAGE".",".$RF_Detail_row['PALLET_ID'].",".$RF_Detail_row['SERVICE_DESCRIPTION'].",";
				$combo_csv .= number_format($RF_Detail_row['SERVICE_AMOUNT'], 2, ".", "").",".$RF_Detail_row['SERVICE_QTY']."\n";
//				$invoice_total += 0;
			}

//			$storage_csv .= ",,,,,,TOTAL:,"."$".number_format($RF_row['SERVICE_AMOUNT'], 2, ".", "")."\n\n";
			$combo_counter_csv++;

		} while(ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}


	// storage pdf
//				AND	INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
//				AND INVOICE_DATE >= '15-dec-2011'
	$first_bill = true;
	$storage_pdf = new Cezpdf('letter');
	$storage_pdf->ezSetMargins(20,20,65,65);
	$storage_pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$storage_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	
	$sql = "SELECT BILLING_NUM, ARRIVAL_NUM, SERVICE_UNIT, SERVICE_UNIT2, CUSTOMER_ID, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') THE_INV_DATE,
				TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START_DATE, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_STOP_DATE,
				SERVICE_QTY, SERVICE_QTY2, SERVICE_RATE, SERVICE_AMOUNT, INVOICE_NUM
			FROM RF_BILLING
			WHERE SERVICE_STATUS = 'INVOICED'
				AND EXPORT_FILE IS NULL
				AND CUSTOMER_ID = '2206'
			ORDER BY INVOICE_DATE, INVOICE_NUM";
	ora_parse($RF_cursor, $sql);
	ora_exec($RF_cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing
	} else {
		do {
			if($first_bill == false){
				$storage_pdf->ezNewPage();
				$storage_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
			} else {
				$first_bill = false;
			}
			$current_invoice = $RF_row['INVOICE_NUM'];
			$customer = $RF_row['CUSTOMER_ID'];
			$bill_num = $RF_row['BILLING_NUM'];
			$unit = $RF_row['SERVICE_UNIT'];
			$unit2 = $RF_row['SERVICE_UNIT2'];
			$inv_date = $RF_row['THE_INV_DATE'];
			$start_date = $RF_row['THE_START_DATE'];
			$stop_date = $RF_row['THE_STOP_DATE'];
			$vessel = $RF_row['ARRIVAL_NUM'];
			$qty = $RF_row['SERVICE_QTY'];
			$qty2 = $RF_row['SERVICE_QTY2'];
			$rate = $RF_row['SERVICE_RATE'];
			$inv_total = $RF_row['SERVICE_AMOUNT'];
			$grand_total += $inv_total;

			$header = GetPrintoutHeader($customer, $vessel, $RF_conn);
			$col_unit_header = GetUnitHeader($unit, $unit2)."(S)\r\n";
			$col_rate_header = "RATE/".$unit."\r\n";

			$barcodes = array();
			$variety = array();
			$BC_qty = array();
			$BC_qty2 = array();
			$BC_unit = array();
			$BC_unit2 = array();
			$BC_rate = array();
			$BC_amt = array();
			$BC_array_size = 0;
			$sql = "SELECT PALLET_ID, VARIETY_DESCRIPTION, SERVICE_QTY, SERVICE_QTY2, SERVICE_RATE, SERVICE_AMOUNT, SERVICE_UNIT, SERVICE_UNIT2
					FROM RF_BILLING_DETAIL
					WHERE SUM_BILL_NUM = '".$bill_num."'
						AND SERVICE_STATUS != 'DELETED'
					ORDER BY VARIETY_DESCRIPTION, PALLET_ID";
			ora_parse($RF_Short_Term_Cursor, $sql);
			ora_exec($RF_Short_Term_Cursor);
			while(ora_fetch_into($RF_Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$barcodes[$BC_array_size] = $short_term_row['PALLET_ID'];
				$variety[$BC_array_size] = $short_term_row['VARIETY_DESCRIPTION'];
				$BC_qty[$BC_array_size] = $short_term_row['SERVICE_QTY'];
				$BC_qty2[$BC_array_size] = $short_term_row['SERVICE_QTY2'];
				$BC_unit[$BC_array_size] = $short_term_row['SERVICE_UNIT'];
				$BC_unit2[$BC_array_size] = $short_term_row['SERVICE_UNIT2'];
				$BC_rate[$BC_array_size] = $short_term_row['SERVICE_RATE'];
				$BC_amt[$BC_array_size] = $short_term_row['SERVICE_AMOUNT'];
				$BC_array_size++;
			}

			// step 3:  using variety as a "subtotal" qualifier, expand the arrays with subtotal lines
			// this will make the use of a table later for formatting possible
			$barcodes_column = array();
			$BC_qty_column = array();
			$BC_rate_column = array();
			$BC_amt_column = array();
			$BC_subtot_column = array();

			$barcodes_column[0] = $variety[0];
			$BC_qty_column[0] = "";
			$BC_rate_column[0] = "";
			$BC_amt_column[0] = "";
			$BC_subtot_column[0] = "";

			$current_variety = $variety[0];
			$sub_total = 0;
			$column_counter = 1;

			for($BC_counter = 0; $BC_counter < $BC_array_size; $BC_counter++){
				if($variety[$BC_counter] != $current_variety){
					$barcodes_column[$column_counter] = "";
					$BC_qty_column[$column_counter] = "";
					$BC_rate_column[$column_counter] = "";
					$BC_amt_column[$column_counter] = "SUBTOTAL:";
					$BC_subtot_column[$column_counter] = number_format($sub_total, 2);
					$sub_total = 0;
					$column_counter++;

					$barcodes_column[$column_counter] = "";
					$BC_qty_column[$column_counter] = "";
					$BC_rate_column[$column_counter] = "";
					$BC_amt_column[$column_counter] = "";
					$BC_subtot_column[$column_counter] = "";
					$column_counter++;

					$barcodes_column[$column_counter] = $variety[$BC_counter];
					$BC_qty_column[$column_counter] = "";
					$BC_rate_column[$column_counter] = "";
					$BC_amt_column[$column_counter] = "";
					$BC_subtot_column[$column_counter] = "";
					$column_counter++;

					$current_variety = $variety[$BC_counter];
				}

				$barcodes_column[$column_counter] = $barcodes[$BC_counter];
				$BC_qty_column[$column_counter] = GetLineDisplay($BC_qty[$BC_counter], $BC_qty2[$BC_counter], $BC_unit[$BC_counter], $BC_unit2[$BC_counter]);
				$BC_rate_column[$column_counter] = $BC_rate[$BC_counter];
				$BC_amt_column[$column_counter] = number_format($BC_amt[$BC_counter], 2);
				$BC_subtot_column[$column_counter] = "";
				$column_counter++;

				$sub_total += $BC_amt[$BC_counter];
			}
			$barcodes_column[$column_counter] = "";
			$BC_qty_column[$column_counter] = "";
			$BC_rate_column[$column_counter] = "";
			$BC_amt_column[$column_counter] = "SUBTOTAL:";
			$BC_subtot_column[$column_counter] = number_format($sub_total, 2);
			$column_counter++;
										
			// step 4:
			// perform a loop per page.  Each page will have it's address header and 30 lines of output.
			// first page will also have bill header; shouldn't cause a page overflow if I print 30 lines max.

			$print_array_index = 0;

			while($print_array_index < $column_counter){
				$storage_pdf->ezSetDy(-5);
				$storage_pdf->ezText("<b>INVOICE:  ".$current_invoice."</b>", 10, $right);
				$storage_pdf->ezSetDy(-5);
				$storage_pdf->ezText("<b>Date:  ".$inv_date."</b>", 10, $right);
				$storage_pdf->ezSetDy(-55);
				$storage_pdf->ezText("<b>".$header."</b>", 10, array('aleft'=>150));
//				$pdf->ezTable($header, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 'width'=>150,'xpos'=>100));
				$storage_pdf->ezSetDy(-55);

				if($print_array_index == 0){
					if($unit == "PLT"){
						$first_row_print = "PALLET";
					} else {
						$first_row_print = $unit;
					}
					if($unit2 == "PLT"){
						$second_row_print = "PALLET";
					} else {
						$second_row_print = $unit2;
					}
//					$pdf->ezText("Period of", 10, $left);
//					$pdf->ezSetDy(-1);
//					$pdf->ezText("<b>".$start_date."</b>", 10, $left);
//					$pdf->ezSetDy(-1);
//					$pdf->ezText("Through", 10, $left);
//					$pdf->ezSetDy(-1);
//					$pdf->ezText("<b>".$stop_date."</b>", 10, $left);
//					$pdf->ezSetDy(8);
					$storage_pdf->ezSetDy(-4);
					$storage_pdf->ezText("STORAGE BILL FOR ".$qty." ".$first_row_print."(S), ".$qty2." ".$second_row_print."(S)  @ $".number_format($rate, 2)." PER ".$unit, 10, array('aleft'=>150));
					$storage_pdf->ezSetDy(-1);
					$storage_pdf->ezText("FOR THE PERIOD OF ".$start_date." THROUGH ".$stop_date, 10, array('aleft'=>150));
					$storage_pdf->ezSetDy(-27);
				}

				$print_table = array();
				for($i = 0; $i < 30; $i++){
					if(($print_array_index + $i) < sizeof($barcodes_column)){
						array_push($print_table, array("BARCODE\r\n"=>$barcodes_column[($print_array_index + $i)],
															$col_unit_header=>$BC_qty_column[($print_array_index + $i)],
															$col_rate_header=>$BC_rate_column[($print_array_index + $i)],
															"AMOUNT\r\n"=>$BC_amt_column[($print_array_index + $i)],
															"\r\n"=>$BC_subtot_column[($print_array_index + $i)]));
					}
				}
				//,'cols'=>$arrCol)
				$storage_pdf->ezTable($print_table, '', '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 
																'width'=>550, 'rowGap'=>1, 'xPos'=>65, 'xOrientation'=>'right'));

				$print_array_index = $print_array_index + 30;

				if($print_array_index >= $column_counter){
					// passed end of array, print total line.
					$storage_pdf->ezText("------------------------------------------------------------------------------------------------------------------------------------------------", 10, $left);
					$storage_pdf->ezText("<b>INVOICE TOTAL:             ".number_format($inv_total, 2)."       </b>", 12, $right);
				}

				// lastly, if there is either more to this invoice, or more invoices coming, start a new page.
				if($print_array_index < $column_counter){
					$storage_pdf->ezNewPage();
					$storage_pdf->addJpegFromFile('POW_logo_in_black.jpg', 20, 700, 100, 40);
					$storage_pdf->ezSetDy(-8);
				}
			}
			$storage_counter_pdf++;
		} while(ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}








	// SEND DA EMAIL!
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'VANDENBERGINV'";
	ora_parse($ED_cursor, $sql);
	ora_exec($ED_cursor);
	ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   
	$mailheaders = "From: ".$email_row['FROM']."\r\n";

	if($email_row['TEST'] == "Y"){
		$mailTO = "awalter@port.state.de.us,ithomas@port.state.de.us";
	} else {
		$mailTO = $email_row['TO'];
		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
	}

	$mailSubject = $email_row['SUBJECT'];

	$body = $email_row['NARRATIVE'];
	$body = str_replace("_0_", $labor_counter, $body);
	$body = str_replace("_1_", $misc_counter, $body);
	$body = str_replace("_2_", $storage_counter_pdf, $body);
	$body = str_replace("_3_", $lease_counter, $body);

	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	if($labor_counter > 0){
		$pdfcode = $labor_pdf->ezOutput();
		$attach=chunk_split(base64_encode($pdfcode));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"LaborBills.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		$attach=chunk_split(base64_encode($labor_details_csv));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"LaborOrders.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}
	if($combo_counter_csv > 0){
		$attach=chunk_split(base64_encode($combo_csv));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"AllBills.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

	if($misc_counter > 0){
		$pdfcode = $misc_pdf->ezOutput();
		$attach=chunk_split(base64_encode($pdfcode));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"Miscellaneous.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

	if($lease_counter > 0){
		$pdfcode = $lease_pdf->ezOutput();
		$attach=chunk_split(base64_encode($pdfcode));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"Lease.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}
/*
	if($storage_counter > 0){
		$attach=chunk_split(base64_encode($storage_csv));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"Storage.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	} */
	if($storage_counter_pdf > 0){
		$pdfcode = $storage_pdf->ezOutput();
		$attach=chunk_split(base64_encode($pdfcode));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"Storage.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

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
					'WEEKLYCRON',
					SYSDATE,
					'EMAIL',
					'VANDENBERGINV',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);

		// mark all invoices as "sent" so future runs don't grab em
		$sql = "UPDATE RF_BILLING
				SET EXPORT_FILE = 'EMAILED'
				WHERE SERVICE_STATUS = 'INVOICED'
					AND EXPORT_FILE IS NULL
					AND CUSTOMER_ID = '2206'";
		ora_parse($RF_cursor, $sql);
		ora_exec($RF_cursor);

		$sql = "UPDATE BILLING
				SET EXPORT_FILE = 'EMAILED'
				WHERE SERVICE_STATUS = 'INVOICED'
					AND BILLING_TYPE NOT IN ('LABOR', 'LEASE', 'STORAGE', 'CM', 'DM')
					AND EXPORT_FILE IS NULL
					AND CUSTOMER_ID = '2206'";
		ora_parse($BNI_cursor, $sql);
		ora_exec($BNI_cursor);

		$sql = "UPDATE BILL_HEADER
				SET EXPORT_FILE = 'EMAILED'
				WHERE SERVICE_STATUS = 'INVOICED'
					AND BILLING_TYPE = 'LEASE'
					AND EXPORT_FILE IS NULL
					AND CUSTOMER_ID = '2206'";
		ora_parse($BNI_cursor, $sql);
		ora_exec($BNI_cursor);

		$sql = "UPDATE BILLING
				SET EXPORT_FILE = 'EMAILED'
				WHERE SERVICE_STATUS = 'INVOICED'
					AND BILLING_TYPE = 'LABOR'
					AND EXPORT_FILE IS NULL
					AND CUSTOMER_ID = '2206'";
		ora_parse($BNI_cursor, $sql);
		ora_exec($BNI_cursor);

	}












function GetPrintoutHeader($customer, $vessel, $conn){
	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$vessel_name = "TRUCKED IN CARGO - ".$vessel;
	} else {
		$vessel_name = $vessel." - ".$short_term_row['VESSEL_NAME'];
	}

	$sql = "SELECT CUSTOMER_NAME, CUSTOMER_ADDRESS1, CUSTOMER_ADDRESS2, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$customer."'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$customer_name = $short_term_row['CUSTOMER_NAME'];
	$customer_add1 = $short_term_row['CUSTOMER_ADDRESS1'];
	$customer_add2 = $short_term_row['CUSTOMER_ADDRESS2'];
	$customer_citystatezip = $short_term_row['CUSTOMER_CITY'].", ".$short_term_row['CUSTOMER_STATE']." - ".$short_term_row['CUSTOMER_ZIP'];

	$header_print = $vessel_name."\n".$customer_name."\n".$customer_add1."\n".$customer_add2."\n".$customer_citystatezip;

	return $header_print;
}

function GetUnitHeader($unit, $unit2){
	if($unit == "PLT"){
		return $unit2;
	} elseif($unit2 == "PLT"){
		return $unit;
	} else {
		return $unit." (".$unit2.")";
	}
}

function GetLineDisplay($qty, $qty2, $unit, $unit2){
	if($unit == "PLT"){
		return $qty2;
	} elseif($unit2 == "PLT"){
		return $qty;
	} else {
		return $qty." (".$qty2.")";
	}
}