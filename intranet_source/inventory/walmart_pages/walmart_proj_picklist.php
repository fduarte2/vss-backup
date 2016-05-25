<?
  // All POW files need this session file included
  include("pow_session.php");






	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);
	$select_cursor = ora_open($conn);

	$dcpo = array();

	if($HTTP_GET_VARS['dcpo'] != ""){
		// passed by dcpo num
		$dcpo[0] = $HTTP_GET_VARS['dcpo'];

		$sql = "SELECT LOAD_NUM FROM WDI_LOAD_DCPO
				WHERE DCPO_NUM = '".$dcpo[0]."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$load_num = $short_term_row['LOAD_NUM'];
	} else {
		// passed by load_num
		$load_num = $HTTP_GET_VARS['load'];

		$i = 0;
		$sql = "SELECT DISTINCT DCPO_NUM
				FROM WDI_LOAD_DCPO
				WHERE LOAD_NUM = '".$load_num."'
				ORDER BY DCPO_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$dcpo[$i] = $short_term_row['DCPO_NUM'];
			$i++;
		}
	}
	
	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter');
	$pdf -> ezSetMargins(10,10,10,10);
/*
	$pdf->ezSetDy(-10);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
	$pdf->ezText("*3000A*", 72, $left);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
	$pdf->ezText("*3000A*", 12, $right);
	$pdf->ezSetDy(-10);
*/

	$sql = "SELECT NVL(TO_CHAR(APPOINTMENT_DATETIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') APPOINT, 
				NVL(TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') CHECK_IN,
				NVL(DESCR, 'NONE') THE_STATUS,
				NVL(TO_CHAR(TRUCK_NUMBER), 'NONE') TRUCK_NUM
			FROM WDI_LOAD_HEADER WLH, WDI_TRUCK_ARRIVAL_STATUS WTAS
			WHERE LOAD_NUM = '".$load_num."'
				AND WLH.ARRIVAL_STATUS = WTAS.ORDERSTATUSID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//	echo $sql;
	$appointment = $short_term_row['APPOINT'];
	$check_in = $short_term_row['CHECK_IN'];
	$arrival_stat = trim($short_term_row['THE_STATUS']);
	$truck_num = trim($short_term_row['TRUCK_NUM']);

	$need_newpage = false;
	for($i = 0; $i < sizeof($dcpo); $i++){
		if($need_newpage){
			$pdf->ezNewPage();
		}
		$need_newpage = true;

		//$sql = "SELECT DEST_ID FROM WDI_LOAD_DCPO WHERE DCPO_NUM = '".$dcpo[$i]."'";
		$sql = "SELECT B.DEST_ID, TRUCK_SEAL, TEMP_RECORDER, DEST_ADDR1, DEST_CITY, DEST_STATE FROM WDI_DESTINATION, WDI_LOAD_DCPO B WHERE WDI_DESTINATION.DEST_ID = B.DEST_ID AND DCPO_NUM = '".$dcpo[$i]."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$destination = $short_term_row['DEST_ID'];

		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezSetY(745);
		$pdf->ezText("Appointment Time: ".$appointment, 12, $left);
		$pdf->ezSetY(765);
		$pdf->ezText("Walmart Direct Truck Order", 14, $center);
		$pdf->ezSetY(745);
		$pdf->ezText("Arrival Time: ".$check_in, 12, $right);
		$pdf->ezSetY(725);
		$pdf->ezText("WM Daily Truck#: ".$truck_num, 12, $left);
		$pdf->ezSetY(725);
		$pdf->ezText("Arrival Status: ".$arrival_stat, 12, $right);
		$pdf->ezSetY(705);
		$pdf->ezText("<b>CUSTOMER: WALMART (3000)</b>", 12, $center);
		$pdf->ezSetY(685);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezText("*3000*", 48, $center);
		$pdf->ezSetY(655);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezText("________________________________________________________________________________________", 12, $center);
		$pdf->ezSetY(635);
		$pdf->ezText("ORDER NUMBER:  ".$dcpo[$i], 12, $right);
		$pdf->ezSetY(615);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezText("*".$dcpo[$i]."*", 48, $right);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezSetY(630);
		$pdf->ezText("WAL MART DC                ".substr($destination."-".$short_term_row['DEST_ADDR1']."-".$short_term_row['DEST_CITY']."-".$short_term_row['DEST_STATE'], 0, 50), 12, $left);
		$pdf->ezSetY(586);
		$pdf->ezText("LOAD NUMBER               ".$load_num."     SEAL#: ".$short_term_row['TRUCK_SEAL']."     TEMP RECORDER#: ".$short_term_row['TEMP_RECORDER'], 10, $left);
		$pdf->ezSetY(585);
		$pdf->ezText("________________________________________________________________________________________", 12, $center);
		$pdf->ezSetDy(-10);
//		$pdf->ezText("ORDER DETAILS BELOW", 10, $center);

		$detail_array = array();
		$pallet_sum = 0;
		$case_sum = 0;
		$sql = "SELECT PALLETS, CASES, WEIGHT, PRODUCT_DESCRIPTION, ITEM_NUM
				FROM WDI_LOAD_DCPO_ITEMNUMBERS
				WHERE DCPO_NUM = '".$dcpo[$i]."'
				ORDER BY ITEM_NUM";
		ora_parse($select_cursor, $sql);
		ora_exec($select_cursor);
		while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$location_list = "";
			$need_location_newline = false;
			$vsl_list = "";

			$pallet_sum += $select_row['PALLETS'];
			$case_sum += $select_row['CASES'];

			// dont forget FIFO!
/*			$sql = "SELECT COUNT(*) THE_COUNT, NVL(WAREHOUSE_LOCATION, 'UNKNOWN') THE_HOUSE, CT.ARRIVAL_NUM, V.DATE_EXPECTED, SUBSTR(VESSEL_NAME, 0, 22) THE_VES
					FROM CARGO_TRACKING CT, VESSEL_PROFILE VP, VOYAGE V
					WHERE RECEIVER_ID = '3000'
						AND QTY_IN_HOUSE > 0
						AND DATE_RECEIVED IS NOT NULL
						AND CT.ARRIVAL_NUM IN
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND BOL = '".$select_row['ITEM_NUM']."'
						AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
						AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
						AND VP.LR_NUM = V.LR_NUM
					GROUP BY NVL(WAREHOUSE_LOCATION, 'UNKNOWN'), CT.ARRIVAL_NUM, V.DATE_EXPECTED, SUBSTR(VESSEL_NAME, 0, 22)
					ORDER BY V.DATE_EXPECTED NULLS LAST"; */
			$sql = "SELECT COUNT(*) THE_COUNT, NVL(WAREHOUSE_LOCATION, 'UNKNOWN') THE_HOUSE, CT.ARRIVAL_NUM, V.RELEASE_TIME, SUBSTR(VESSEL_NAME, 0, 22) THE_VES
					FROM CARGO_TRACKING CT, VESSEL_PROFILE VP, WDI_VESSEL_RELEASE V, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND QTY_IN_HOUSE > 0
						AND DATE_RECEIVED IS NOT NULL
						AND BOL = '".$select_row['ITEM_NUM']."'
						AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
						AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
						AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
						AND VP.LR_NUM = V.LR_NUM
					GROUP BY NVL(WAREHOUSE_LOCATION, 'UNKNOWN'), CT.ARRIVAL_NUM, V.RELEASE_TIME, SUBSTR(VESSEL_NAME, 0, 22)
					ORDER BY V.RELEASE_TIME NULLS LAST"; 
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				if($need_location_newline){
					$location_list .= "\n";
					$vsl_list .= "\n";
				}
				$need_location_newline = true;

				$location_list .= $short_term_row['THE_COUNT']." @ ".$short_term_row['THE_HOUSE'];
				$vsl_list .= $short_term_row['ARRIVAL_NUM']." - ".$short_term_row['THE_VES'];

			}

			array_push($detail_array, array('plt'=>$select_row['PALLETS'],
											'cases'=>$select_row['CASES'],
											'wt'=>$select_row['WEIGHT'],
											'desc'=>$select_row['PRODUCT_DESCRIPTION'],
											'item'=>$select_row['ITEM_NUM'],
											'loc'=>$location_list,
											'LR'=>$vsl_list));
		}

		$pdf->ezTable($detail_array, array('plt'=>"PALLETS",
											'cases'=>"CASES",
											'wt'=>"WT",
											'desc'=>"WAL MART Desc",
											'item'=>"ITEM #",
											'loc'=>"POW LOCATION",
											'LR'=>"VESSEL"),
									"ORDER DETAILS BELOW",
									array('cols'=>array('plt'=>array('justification'=>'left', 'width'=>60),
														'cases'=>array('justification'=>'left', 'width'=>60),
														'wt'=>array('justification'=>'left'),
														'desc'=>array('justification'=>'left'),
														'item'=>array('justification'=>'left'),
														'loc'=>array('justification'=>'left'),
														'LR'=>array('justification'=>'left')),
											'shaded'=>0, 
											'showLines'=>2,
											'fontSize'=>8,
											'colGap'=>3,
											'width'=>575));
		$pdf->ezSetDy(-15);
		$total_table = array();
		array_push($total_table, array('pltsum'=>$pallet_sum,
							'casesum'=>$case_sum,
							'text'=>"TOTAL ORDER"));
		$pdf->ezTable($total_table,
					'',
					'',
					array('cols'=>array('pltsum'=>array('justification'=>'left', 'width'=>60),
														'casesum'=>array('justification'=>'left', 'width'=>60),
														'text'=>array('justification'=>'center')),
									'shaded'=>0, 
									'showLines'=>2,
									'showHeadings'=>0,
									'colGap'=>3,
									'width'=>575));

		$pdf->ezSetDy(-20);
		$pdf->ezText("<b>NO SLC - SHIPPER LOAD AND COUNT, DRIVER MUST COUNT LOAD</b>", 12, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("DRIVER MUST CHOCK WHEELS PRIOR TO LOADING ______ (DRIVER INITIALS) & RETURN CHOCK TO HANGER UPON COMPLETION", 6, $center);
		$pdf->ezText("DRIVER TRAILER WAS PRE-COOLED TO 34-36 ___ DEGREES F ______________________ (Driver Initials)", 8, $center);
		$pdf->ezText("CLEANLINESS: ___GOOD   ___FAIR   ___POOR           ODOR: ___YES   ___NO", 8, $center);
		$pdf->ezText("ACCEPTED FOR LOADING: ___YES PROCEED   ___NO - CONTACT SUPERVISOR", 8, $center);
		$pdf->ezText("CHECKER_______________________SIGN    DRIVER______________________________SIGN", 8, $center);

	}
											




/*
   $sql = "select * from customer_profile order by customer_id";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);

   $data = array();

   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC))
   {
      array_push($data, array('name'=>$row['CUSTOMER_NAME'], 'id'=>$row['CUSTOMER_ID'],
               'address1'=>$row['CUSTOMER_ADDRESS1'],
               'city'=>$row['CUSTOMER_CITY'], 'state'=>$row['CUSTOMER_STATE'],
               'zip'=>$row['CUSTOMER_ZIP'], 'phone'=>$row['CUSTOMER_PHONE'], 
               'email'=>$row['CUSTOMER_EMAIL']));
 
   }

 $pdf->ezTable($data, array('name'=>'Customer Name', 'id'=>'Id', 'address1'=>'Address', 'city'=>'City', 'state'=>'State', 'zip'=>'ZipCode', 'phone'=>'Phone', 'email'=>'E-Mail'), '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>776, 'cols'=>array('name'=>array('width'=>100))));
   $format = "Port of Wilmington, " . $today . " Printed by " . $user;
   $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->line(20,822,578,822);
   $pdf->addText(50,34,6, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

 ora_close($cursor);
 ora_logoff($conn_bni);

*/
   // redirect to a temporary PDF file instead of directly writing to the browser
   include("redirect_pdf.php");

?>

