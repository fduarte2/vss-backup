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


	$today = date('m/d/Y');

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

//TEMP_RECORDER, CARRIER, TRUCK_SEAL, 
	$sql = "SELECT TEMP, DESCR, LICENSE_STATE, TRUCK_LICENSE_NUMBER, TRUCKING_COMPANY,
				NVL(TO_CHAR(APPOINTMENT_DATETIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') THE_APPOINT,
				NVL(TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') THE_IN,
				NVL(TO_CHAR(TRUCK_CHECKOUT_TIME, 'MM/DD/YYYY HH:MI AM'), 'NONE') THE_OUT
			FROM WDI_LOAD_HEADER WLH, WDI_TRUCK_ARRIVAL_STATUS WTAS
			WHERE LOAD_NUM = '".$load_num."'
				AND WTAS.ORDERSTATUSID = WLH.ARRIVAL_STATUS";
//	echo $sql;
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$temperature = $short_term_row['TEMP'];
//	$temp_recorder = $short_term_row['TEMP_RECORDER'];
//	$seal = $short_term_row['TRUCK_SEAL'];
	$arv_descr = $short_term_row['DESCR'];
	$appoint = $short_term_row['THE_APPOINT'];
	$checkin = $short_term_row['THE_IN'];
	$checkout = $short_term_row['THE_OUT'];
//	$seal = $short_term_row['TRUCK_SEAL'];
	$lic_num = $short_term_row['TRUCK_LICENSE_NUMBER'];
	$lic_state = $short_term_row['LICENSE_STATE'];
//	$carrier = $short_term_row['CARRIER'];
	$carrier = $short_term_row['TRUCKING_COMPANY'];

//	echo $lic_num." ".$lic_state;

	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter');
	$pdf -> ezSetMargins(10,10,10,10);

	$need_newpage = false;
	for($i = 0; $i < sizeof($dcpo); $i++){
		$sql = "SELECT TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_DATE
				FROM CARGO_ACTIVITY
				WHERE CUSTOMER_ID = '3000'
					AND ORDER_NUM = '".$dcpo[$i]."'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND SERVICE_CODE = '6'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$print_date = $short_term_row['THE_DATE'];

		if($need_newpage){
			$pdf->ezNewPage();
		}
		$need_newpage = true;

		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');
		$pdf->ezSetY(765);
		$pdf->ezText("<b>MOTOR CARRIER</b>", 14, $center);
		$pdf->ezSetY(750);
		$pdf->ezText("<b>STRAIGHT BILL OF LADING</b>", 14, $center);
		$pdf->ezSetY(735);
		$pdf->ezText("ORIGINAL NON-NEGOTIABLE", 10, $center);

		$pdf->ezSetY(720);
		$pdf->ezText("                            LICENSE", 12, $left);
		$pdf->ezSetY(705);
		$pdf->ezText("                 STATE      TAG NUMBER", 12, $left);
		$pdf->ezSetY(690);
		$pdf->ezText("TRAILER  ___________________", 12, $left);
		$pdf->ezSetY(690);
		$pdf->ezText("                      ".$lic_state."           ".$lic_num, 12, $left);

		$pdf->ezSetY(710);
		$pdf->ezText("<b>ORDER NUMBER:    ___________________</b>", 12, $right);
		$pdf->ezSetY(710);
		$pdf->ezText($dcpo[$i], 12, $right);
		$pdf->ezSetY(695);
		$pdf->ezText("LOAD:     ___________________", 12, $right);
		$pdf->ezSetY(695);
		$pdf->ezText($load_num, 12, $right);

		$pdf->ezSetY(665);
		$pdf->ezText("<b>NAME OF CARRIER: ___________________________</b>", 12, $left);
		$pdf->ezSetY(665);
		$pdf->ezText("                                           ".$carrier, 12, $left);
		$pdf->ezSetY(665);
		$pdf->ezText("<b>DATE:  ____________________</b>", 12, $right);
		$pdf->ezSetY(665);
//		$pdf->ezText($today, 12, $right);
		$pdf->ezText($print_date, 12, $right);
	 

		$disclaimer_table = array();
		array_push($disclaimer_table, array('words'=>"Carrier has received from the shipper named herein, the perishable property described below, in good order and condition, except as noted"));
		array_push($disclaimer_table, array('words'=>"below, marked, consigned and destined as indicated below, pursuant to an agreement (arranged by the truck broker, named herein, if any),"));
		array_push($disclaimer_table, array('words'=>"whereby the motor carrier shown above, in consideration of the transportation charges to be paid, agrees to carry and deliver said property to"));
		array_push($disclaimer_table, array('words'=>"the consignee, subject only to the terms and conditions agreed to by the motor carrier, the shipper, and truck broker if any."));

		$pdf->ezSetY(645);
		$pdf->ezTable($disclaimer_table,
					'',
					'',
					array('cols'=>array('words'=>array('justification'=>'center')),
									'fontSize'=>8,
									'shaded'=>0, 
									'showLines'=>1,
									'showHeadings'=>0,
									'width'=>545));


		$pdf->ezSetY(580);

		$address_table = array();
		$sql = "SELECT DEST_NAME, DEST_ADDR1, DEST_CITY || ', ' || DEST_STATE || '  ' || DEST_ZIP THE_STATE, TEMP_RECORDER, TRUCK_SEAL, PRODUCTS_OF
				FROM WDI_DESTINATION WD, WDI_LOAD_DCPO WLD
				WHERE WD.DEST_ID(+) = WLD.DEST_ID
					AND WLD.DCPO_NUM = '".$dcpo[$i]."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$destination_name = $short_term_row['DEST_NAME'];
		$destination_addr = $short_term_row['DEST_ADDR1'];
		$destination_state = $short_term_row['THE_STATE'];
		$temp_recorder = $short_term_row['TEMP_RECORDER'];
		$seal = $short_term_row['TRUCK_SEAL'];
		$prod_of = $short_term_row['PRODUCTS_OF'];

		array_push($address_table, array('left'=>$destination_name,
										'center'=>"Wal Mart",
										'right'=>"Wal Mart"));
		array_push($address_table, array('left'=>$destination_addr,
										'center'=>"C/O Port of Wilmington",
										'right'=>"601 N. Walton Blvd."));
		array_push($address_table, array('left'=>$destination_state,
										'center'=>"Wilmington, DE, U.S.A",
										'right'=>"Bentonville, AR  72716-0410\nAP MANAGER"));
		$pdf->ezTable($address_table, array('left'=>"CONSIGNED TO:",
											'center'=>"SHIPPED FROM",
											'right'=>"IF FREIGHT IS PREPAID,\nMAIL INVOICE TO:"),
									"",
									array('cols'=>array('left'=>array('justification'=>'left', 'width'=>180),
														'center'=>array('justification'=>'left', 'width'=>185),
														'right'=>array('justification'=>'left')),
											'shaded'=>0,
											'showLines'=>1, 
											'width'=>545));


		$pdf->ezSetDy(-10);
		$pdf->ezText("<b>FREIGHT CHARGES TO BE PAID BY:</b>", 10, $left);
		$pdf->ezSetDy(10);
		$pdf->ezText("FREIGHT ARRANGED FOR BY:", 10, $right);
		$pdf->ezText("Shipper: XXXXXXX", 10, $left);
		$pdf->ezSetDy(10);
		$pdf->ezText("Shipper: xxxxxxxxxx", 10, $right);
		$pdf->ezSetDy(-20);


		$detail_array = array();
		$sql = "SELECT WEIGHT, PRODUCT_DESCRIPTION, ITEM_NUM
				FROM WDI_LOAD_DCPO_ITEMNUMBERS
				WHERE DCPO_NUM = '".$dcpo[$i]."'
				ORDER BY ITEM_NUM";
		ora_parse($select_cursor, $sql);
		ora_exec($select_cursor);
		while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

			$sql = "SELECT COUNT(*) THE_PLTS, SUM(THE_SUM) SUM_CASES, THE_COUN FROM
					(
						SELECT CA.PALLET_ID, NVL(COUNTRY_NAME, 'NA') THE_COUN, SUM(CA.QTY_CHANGE) THE_SUM
						FROM CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD, COUNTRY COUN
						WHERE CA.ORDER_NUM = '".$dcpo[$i]."'
							AND CA.SERVICE_CODE <> 12
							AND (CA.ACTIVITY_DESCRIPTION IS NULL OR (CA.ACTIVITY_DESCRIPTION <>'VOID'))
							AND CA.PALLET_ID IN
								(SELECT PALLET_ID FROM CARGO_TRACKING
								WHERE RECEIVER_ID = '3000'
									AND BOL = '".$select_row['ITEM_NUM']."')
							AND CA.PALLET_ID = CTAD.PALLET_ID
							AND CA.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
							AND CA.CUSTOMER_ID = CTAD.RECEIVER_ID
							AND CTAD.COUNTRY_CODE = COUN.COUNTRY_CODE(+)
						GROUP BY CA.PALLET_ID, NVL(COUNTRY_NAME, 'NA')
						HAVING SUM(QTY_CHANGE) > 0
					)
					GROUP BY THE_COUN";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				array_push($detail_array, array('plt'=>$short_term_row['THE_PLTS'],
												'cases'=>$short_term_row['SUM_CASES'],
												'desc'=>$select_row['PRODUCT_DESCRIPTION'],
												'item'=>$select_row['ITEM_NUM'],											
												'wt'=>$select_row['WEIGHT'],
												'country'=>$short_term_row['THE_COUN']));
			}
		}
		$pdf->ezTable($detail_array, array('plt'=>"NO of PALLETS",
											'cases'=>"NO of CASES",
											'desc'=>"DESCRIPTION OF ARTICLES AND SPECIAL MARKS",
											'item'=>"ITEM#",
											'wt'=>"WEIGHT",
											'country'=>"PRODUCT OF"),
									"",
									array('cols'=>array('plt'=>array('justification'=>'left'),
														'cases'=>array('justification'=>'left'), //, 'width'=>70
														'wt'=>array('justification'=>'left'),
														'desc'=>array('justification'=>'left'),
														'item'=>array('justification'=>'left'),
														'loc'=>array('justification'=>'left'),
														'country'=>array('justification'=>'left')),
											'shaded'=>0, 
											'showLines'=>2,
											'titleFontSize'=>12,
											'width'=>545));

		$pdf->ezSetDy(-30);
//		$pdf->ezText("<b>PRODUCT OF ".$prod_of."</b>", 12, $center);
		$pdf->ezSetDy(-10);
		$pdf->ezText("SEE ATTACHED TALLY FOR PALLET NUMBERS", 8, $center);
		$pdf->ezSetDy(8);
		$pdf->ezText("________________________________________________________________________________", 10, $center);
		$pdf->ezSetDy(-5);
		$pdf->ezText("<b>REFRIGERATION INSTRUCTION:    TEMPERATURE TO BE MAINTAINED AT:  ".$temperature."</b>", 10, $left);
		$pdf->ezSetDy(10);
		$pdf->ezText("_____________________________", 10, $left);

		$pdf->ezSetDy(-25);
		$pdf->ezText("TEMP RECORDER:  ".$temp_recorder, 10, $left);
		$pdf->ezSetDy(10);
		$pdf->ezText("TRUCK SEAL:  _____________________________", 10, $right);
		$pdf->ezSetDy(10);
		$pdf->ezText("_____________________________", 10, $left);
		$pdf->ezSetDy(13);
		$pdf->ezText($seal, 10, $right);

		$pdf->ezSetDy(-25);
		$pdf->ezText("<b>DRIVER SIGNATURE:  _______________________________</b>", 10, $left);
		$pdf->ezSetDy(8);
		$pdf->ezText("Some, Part, or All of this cargo may have\nbeen fumigated with Methyl Bromide", 8, $right);

		$pdf->ezText("APPT Datetime: ".$appoint, 10, $left);
		$pdf->ezText("Arrival Datetime: ".$checkin, 10, $left);
		$pdf->ezText("Checkout Datetime: ".$checkout, 10, $left);
		$pdf->ezText("---  ".$arv_descr, 10, $left);
	}


	include("redirect_pdf.php");
