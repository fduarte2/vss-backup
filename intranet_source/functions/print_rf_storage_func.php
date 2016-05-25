<?
/*
*	Adam Walter, Oct-Nov 2015
*
*	This is the script to print live invoices
*	For the "New" Automated Storage Billing System (new as of Oct/Nov 2011)
*
*	This has been moved from the finance folder to the main function folder,
*	So that other applications can call it as needed to get printouts.
*****************************************************************************/
/*
//  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  } */

function GeneratePDF(&$pdf, $start_num, $end_num, $invoice_type, $array_of_specific_invoices, $header_picture, $conn){

//  $Short_Term_Cursor = ora_open($conn);
//  $cursor = ora_open($conn);

	if($array_of_specific_invoices == ""){
		$array_of_specific_invoices = array();
	}

//	$start_num = $HTTP_POST_VARS['start_num'];
//	$end_num = $HTTP_POST_VARS['end_num'];
//	$invoice_type = $HTTP_POST_VARS['invoice_type'];
	if($invoice_type == "INVOICE"){
		$number_field = "INVOICE_NUM";
	} else {
		// its a preinvoice... expand this if-else if more statuses necessary
		$number_field = "BILLING_NUM";
	}

//	include 'class.ezpdf.php';
//	$pdf = new Cezpdf('letter');
//	$pdf = new Cezpdf('letter','landscape');

//	$pdf->ezSetMargins(20,40,20,20);
//	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
//	$pdf->ezStartPageNumbers(720, 32, 9, '','',1);
//	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	if($start_num == "" || $end_num == "" || !is_numeric($start_num) || !is_numeric($end_num)){
		$pdf->ezText("<b>Both Start and End Invoice #s must be specified.</b>", 10, $center);
	} elseif($start_num > $end_num){
		$pdf->ezText("<b>End number must be greater than or equal to starting number.</b>", 10, $center);
	} else {
		$grand_total = 0;

		// ok, lets print some invocies.
		// loop for each invoice#...
		$current_invoice = $start_num;
		while($current_invoice <= $end_num){
			// step 1:  get column headers and page headers from RF_BILLING table.
			$sql = "SELECT BILLING_NUM, ARRIVAL_NUM, SERVICE_UNIT, SERVICE_UNIT2, CUSTOMER_ID, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') THE_INV_DATE,
						TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START_DATE, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_STOP_DATE,
						SERVICE_QTY, SERVICE_QTY2, SERVICE_RATE, SERVICE_AMOUNT, COMMODITY_CODE
					FROM RF_BILLING
					WHERE ".$number_field." = '".$current_invoice."'";
			$invoice_list = ociparse($conn, $sql);
			ociexecute($invoice_list);
			if(ocifetch($invoice_list)){
				$customer = ociresult($invoice_list, "CUSTOMER_ID");
				$bill_num = ociresult($invoice_list, "BILLING_NUM");
				$unit = ociresult($invoice_list, "SERVICE_UNIT");
				$unit2 = ociresult($invoice_list, "SERVICE_UNIT2");
				$inv_date = ociresult($invoice_list, "THE_INV_DATE");
				$start_date = ociresult($invoice_list, "THE_START_DATE");
				$stop_date = ociresult($invoice_list, "THE_STOP_DATE");
				$vessel = ociresult($invoice_list, "ARRIVAL_NUM");
				$qty = ociresult($invoice_list, "SERVICE_QTY");
				$qty2 = ociresult($invoice_list, "SERVICE_QTY2");
				$rate = ociresult($invoice_list, "SERVICE_RATE");
				$inv_total = ociresult($invoice_list, "SERVICE_AMOUNT");
				$grand_total += $inv_total;

				if($vessel === "0"){
//					echo "billnum: ".$bill_num."    vessel:".$vessel."<br>";
					PrintByPoolInstead($conn, $pdf, $invoice_type, $inv_date, $customer, $bill_num, $start_date, $stop_date, $qty, $rate, $current_invoice, $end_num, $header_picture);
				} elseif(ociresult($invoice_list, "COMMODITY_CODE") == "1299"){
					PrintDolePaperInstead($conn, $pdf, $invoice_type, $inv_date, $customer, $vessel, $bill_num, $start_date, $stop_date, $qty, $rate, $current_invoice, $end_num, $header_picture);
				} else {
					$header = GetPrintoutHeader($customer, $vessel, $conn);
					$col_unit_header = GetUnitHeader($unit, $unit2)."(S)\r\n";
					$col_rate_header = "RATE/".$unit."\r\n";

					// step 2:  populate an array of every barcode on this bill

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
					$short_term_data = ociparse($conn, $sql);
					ociexecute($short_term_data);
					if(ocifetch($short_term_data)){
						$barcodes[$BC_array_size] = ociresult($short_term_data, "PALLET_ID");
						$variety[$BC_array_size] = ociresult($short_term_data, "VARIETY_DESCRIPTION");
						$BC_qty[$BC_array_size] = ociresult($short_term_data, "SERVICE_QTY");
						$BC_qty2[$BC_array_size] = ociresult($short_term_data, "SERVICE_QTY2");
						$BC_unit[$BC_array_size] = ociresult($short_term_data, "SERVICE_UNIT");
						$BC_unit2[$BC_array_size] = ociresult($short_term_data, "SERVICE_UNIT2");
						$BC_rate[$BC_array_size] = ociresult($short_term_data, "SERVICE_RATE");
						$BC_amt[$BC_array_size] = ociresult($short_term_data, "SERVICE_AMOUNT");
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
							$BC_subtot_column[$column_counter] = "$".number_format($sub_total, 2);
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
						$BC_rate_column[$column_counter] = "$".number_format($BC_rate[$BC_counter], 2);
						$BC_amt_column[$column_counter] = "$".number_format($BC_amt[$BC_counter], 2);
						$BC_subtot_column[$column_counter] = "";
						$column_counter++;

						$sub_total += $BC_amt[$BC_counter];
					}
											
					$barcodes_column[$column_counter] = "";
					$BC_qty_column[$column_counter] = "";
					$BC_rate_column[$column_counter] = "";
					$BC_amt_column[$column_counter] = "SUBTOTAL:";
					$BC_subtot_column[$column_counter] = "$".number_format($sub_total, 2);
					$column_counter++;

					// step 4:
					// perform a loop per page.  Each page will have it's address header and 30 lines of output.
					// first page will also have bill header; shouldn't cause a page overflow if I print 30 lines max.

					$print_array_index = 0;

					while($print_array_index < $column_counter){
						if($header_picture != ""){
							$pdf->addJpegFromFile($header_picture, 20, 700, 100, 40);
						}
						$pdf->ezSetDy(-5);
						$pdf->ezText("<b>".$invoice_type.":  ".$current_invoice."</b>", 10, $right);
						$pdf->ezSetDy(-5);
						$pdf->ezText("<b>Date:  ".$inv_date."</b>", 10, $right);
						$pdf->ezSetDy(-55);
						$pdf->ezText("<b>".$header."</b>", 10, array('aleft'=>150));
		//				$pdf->ezTable($header, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 'width'=>150,'xpos'=>100));
						$pdf->ezSetDy(-55);

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
							$pdf->ezSetDy(-4);
							$pdf->ezText("STORAGE BILL FOR ".$qty." ".$first_row_print."(S), ".$qty2." ".$second_row_print."(S)  @ $".number_format($rate, 2)." PER ".$unit, 10, array('aleft'=>150));
							$pdf->ezSetDy(-1);
							$pdf->ezText("FOR THE PERIOD OF ".$start_date." THROUGH ".$stop_date, 10, array('aleft'=>150));
							$pdf->ezSetDy(-27);
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
						$pdf->ezTable($print_table, '', '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 
																	'width'=>550, 'rowGap'=>1, 'xPos'=>65, 'xOrientation'=>'right'));

						$print_array_index = $print_array_index + 30;

						if($print_array_index >= $column_counter){
							// passed end of array, print total line.
							$pdf->ezText("---------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 10, $left);
							$pdf->ezText("<b>".$invoice_type." TOTAL:             $".number_format($inv_total, 2)."       </b>", 12, $right);
						}

						// lastly, if there is either more to this invoice, or more invoices coming, start a new page.
						if($print_array_index < $column_counter || $current_invoice < $end_num){
							$pdf->ezNewPage();
							$pdf->ezSetDy(-8);
						}
					}
				}
			}

			$current_invoice++;
			// check if the current set of invoices (if it exists) includes the next one
			if(sizeof($array_of_specific_invoices) != 0){
				while(!in_array($current_invoice, $array_of_specific_invoices) && $current_invoice <= $end_num){
					$current_invoice++;
				}
			}		
		}

		// GRAND TOTAL PAGE
		$pdf->ezNewPage();
		$pdf->ezSetDy(-277);
		$pdf->ezText("GRAND TOTAL:    $".number_format($grand_total, 2), 36, $center);

	}

	
//	include("redirect_pdf.php");
}
















function GetPrintoutHeader($customer, $vessel, $conn){
//	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	if(ocifetch($short_term_data)){
		$vessel_name = "TRUCKED IN CARGO - ".$vessel;
	} else {
		$vessel_name = $vessel." - ".ociresult($short_term_data, "VESSEL_NAME");
	}

	$sql = "SELECT CUSTOMER_NAME, CUSTOMER_ADDRESS1, CUSTOMER_ADDRESS2, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$customer."'";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$customer_name = ociresult($short_term_data, "CUSTOMER_NAME");
	$customer_add1 = ociresult($short_term_data, "CUSTOMER_ADDRESS1");
	$customer_add2 = ociresult($short_term_data, "CUSTOMER_ADDRESS2");
	$customer_citystatezip = ociresult($short_term_data, "CUSTOMER_CITY").", ".ociresult($short_term_data, "CUSTOMER_STATE")." - ".ociresult($short_term_data, "CUSTOMER_ZIP");

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














function GetPrintoutHeaderNoVessel($customer, $conn){
//	$Short_Term_Cursor = ora_open($conn);

	$sql = "SELECT CUSTOMER_NAME, CUSTOMER_ADDRESS1, CUSTOMER_ADDRESS2, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$customer."'";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$customer_name = ociresult($short_term_data, "CUSTOMER_NAME");
	$customer_add1 = ociresult($short_term_data, "CUSTOMER_ADDRESS1");
	$customer_add2 = ociresult($short_term_data, "CUSTOMER_ADDRESS2");
	$customer_citystatezip = ociresult($short_term_data, "CUSTOMER_CITY").", ".ociresult($short_term_data, "CUSTOMER_STATE")." - ".ociresult($short_term_data, "CUSTOMER_ZIP");

	$header_print = $customer_name."\n".$customer_add1."\n".$customer_add2."\n".$customer_citystatezip;

	return $header_print;
}




function PrintByPoolInstead($conn, &$pdf, $invoice_type, $inv_date, $customer, $bill_num, $start_date, $stop_date, $qty, $rate, $current_invoice, $end_num, $header_picture){
	// this is a dole-exclusive bill as of the contract renegotiation on 1/1/2014.
	// Each bill is for 1 day, for any number of vessels.
	// each bill is by pallet.
	// as such, the structure of these pages will be a bit different.

	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

//	$Short_Term_Cursor = ora_open($conn);

	$header = GetPrintoutHeaderNoVessel($customer, $conn);

	$print_detail_lines = 0;
	$invoice_total = 0;
	$first_pass = true;

	$sql = "SELECT DOLE_POOL, SUM(SERVICE_QTY) THE_PLTS, SERVICE_RATE, SUM(SERVICE_AMOUNT) THE_AMT, ARRIVAL_NUM, COMMODITY_CODE
			FROM RF_BILLING_DETAIL
			WHERE SUM_BILL_NUM = '".$bill_num."'
				AND SERVICE_STATUS != 'DELETED'
			GROUP BY DOLE_POOL, SERVICE_RATE, ARRIVAL_NUM, COMMODITY_CODE
			ORDER BY DOLE_POOL";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		// Step 1:  If this is a new page, then print headers.
		if($print_detail_lines == 0){
			if($header_picture != ""){
				$pdf->addJpegFromFile($header_picture, 20, 700, 100, 40);
			}
			if($first_pass == false){
				$pdf->ezNewPage();
				$pdf->ezSetDy(-8);
			} else {
				$first_pass = false;
				if(ociresult($short_term_data, "COMMODITY_CODE") == '5298'){
					$ves_header = "<b>TRUCKED IN CARGO</b>";
				} else {
					$ves_header = "<b>VESSEL CARGO</b>";
				}
			}
			$pdf->ezText("<b>".$invoice_type.":  ".$current_invoice."</b>", 10, $right);
			$pdf->ezSetDy(-5);
			$pdf->ezText("<b>Date:  ".$inv_date."</b>", 10, $right);
			$pdf->ezSetDy(-75);
			$pdf->ezText($ves_header, 10, array('aleft'=>150));
			$pdf->ezSetDy(-5);
			$pdf->ezText("<b>".$header."</b>", 10, array('aleft'=>150));
			$pdf->ezSetDy(-55);

			$pdf->ezSetDy(-1);
			$pdf->ezText("STORAGE BILL FOR THE PERIOD ".$start_date." THROUGH ".$stop_date, 10, array('aleft'=>150));
			$pdf->ezSetDy(-1);
//			$pdf->ezText("  ".$qty." PALLETS @ $".$rate."/PLT", 10, array('aleft'=>150));
			$pdf->ezText("  ".$qty." PALLETS.  DETAILS BELOW.", 10, array('aleft'=>150));
			$pdf->ezSetDy(-27);
		}

		// Step 2:  Print a line
		// NOTE:  the [0] is because the eztable function expects a 2-dimentional array
		$output_array[0] = array("pool"=>"Pool: ".ociresult($short_term_data, "DOLE_POOL"),
								"desc"=>ociresult($short_term_data, "THE_PLTS")." Pallets @ $".ociresult($short_term_data, "SERVICE_RATE")."/plt           Arrival# ".ociresult($short_term_data, "ARRIVAL_NUM"),
								"totaldollar"=>"$".number_format(ociresult($short_term_data, "THE_AMT"), 2));
		$pdf->ezTable($output_array, '', '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 
													'width'=>513, 'rowGap'=>1, 'xPos'=>65, 'xOrientation'=>'right',
													'cols'=>array('pool'=>array('justification'=>'left', 'width'=>100), 
																  'desc'=>array('justification'=>'left', 'width'=>343),
																  'totaldollar'=>array('justification'=>'right', 'width'=>70))));
		$pdf->ezSetDy(-1);
		$print_detail_lines++;
		$invoice_total += ociresult($short_term_data, "THE_AMT");

		//Step 3:  If we have 10 lines, start a newpage.
		if($print_detail_lines == 25){
			$print_detail_lines = 0;
		}
	}

	// total for the Invoice.
	$pdf->ezText("---------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 10, $left);
	$pdf->ezText("<b>".$invoice_type." TOTAL:             $".number_format($invoice_total, 2)."       </b>", 12, $right);
//	$output_array = array("pool"=>"",
//							"desc"=>ociresult($short_term_data, "THE_PLTS")." pallets @ $".ociresult($short_term_data, "SERVICE_RATE")."/plt",
//							"totaldollar"=>"$".ociresult($short_term_data, "THE_AMT")));

	// lastly, if there is more invoices coming, start a new page.
	if($current_invoice < $end_num){
		$pdf->ezNewPage();
		$pdf->ezSetDy(-8);
	}
}
	
function PrintDolePaperInstead($conn, &$pdf, $invoice_type, $inv_date, $customer, $vessel, $bill_num, $start_date, $stop_date, $qty, $rate, $current_invoice, $end_num, $header_picture){
	// this is a dole-paper bill as of HD 9734.

	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

//	$Short_Term_Cursor = ora_open($conn);
//	$subheaders = ora_open($conn);

	$header = GetPrintoutHeader($customer, $vessel, $conn);

	$print_detail_lines = 0;
	$invoice_total = 0;
	$first_pass = true;

	$barcodes = array();
	$BC_qty = array();
//	$BC_unit = array();
	$BC_rate = array();
	$BC_amt = array();

	$barcodes_column = array();
	$BC_qty_column = array();
	$BC_rate_column = array();
	$BC_amt_column = array();
	$BC_subtot_column = array();

	$total = 0;
	$column_counter = 0;

	$sql = "SELECT DISTINCT DOLEPAPER_ID, DOLEPAPER_BOL, DOLEPAPER_ORDER, DOLE_ORIG_CUST
			FROM RF_BILLING_DETAIL
			WHERE SUM_BILL_NUM = '".$bill_num."'
				AND SERVICE_STATUS != 'DELETED'
			ORDER BY DOLEPAPER_ID";
	$subheaders = ociparse($conn, $sql);
	ociexecute($subheaders);
	while(ocifetch($subheaders)){
		$BC_array_size = 0;
		$sub_total = 0;

		$sql = "SELECT PALLET_ID, VARIETY_DESCRIPTION, SERVICE_QTY, SERVICE_QTY2, SERVICE_RATE, SERVICE_AMOUNT, SERVICE_UNIT, SERVICE_UNIT2
				FROM RF_BILLING_DETAIL
				WHERE SUM_BILL_NUM = '".$bill_num."'
					AND DOLEPAPER_ID = '".ociresult($subheaders, "DOLEPAPER_ID")."'
					AND DOLEPAPER_BOL = '".ociresult($subheaders, "DOLEPAPER_BOL")."'
					AND DOLEPAPER_ORDER = '".ociresult($subheaders, "DOLEPAPER_ORDER")."'
					AND SERVICE_STATUS != 'DELETED'
				ORDER BY PALLET_ID";
		$short_term_data = ociparse($conn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
			$barcodes[$BC_array_size] = ociresult($short_term_data, "PALLET_ID");
			$BC_qty[$BC_array_size] = ociresult($short_term_data, "SERVICE_QTY");
//			$BC_unit[$BC_array_size] = ociresult($short_term_data, "SERVICE_UNIT");
			$BC_rate[$BC_array_size] = ociresult($short_term_data, "SERVICE_RATE");
			$BC_amt[$BC_array_size] = ociresult($short_term_data, "SERVICE_AMOUNT");
			$BC_array_size++;
		}

		$dole_cust_sql = "SELECT SUBSTR(CUSTOMER_NAME, 0, 23) THE_CUST
							FROM CUSTOMER_PROFILE
							WHERE CUSTOMER_ID = '".ociresult($subheaders, "DOLE_ORIG_CUST")."'";
		$short_term_data = ociparse($conn, $dole_cust_sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		
		$barcodes_column[$column_counter] = "Cust#: ".ociresult($short_term_data, "THE_CUST");
		$BC_qty_column[$column_counter] = "";
		$BC_rate_column[$column_counter] = "";
		$BC_amt_column[$column_counter] = "";
		$BC_subtot_column[$column_counter] = "";
		$column_counter++;
		$barcodes_column[$column_counter] = "Booking#: ".ociresult($subheaders, "DOLEPAPER_ID");
		$BC_qty_column[$column_counter] = "P.O. ".ociresult($subheaders, "DOLEPAPER_ORDER");
		$BC_rate_column[$column_counter] = "BOL ".ociresult($subheaders, "DOLEPAPER_BOL");
		$BC_amt_column[$column_counter] = "";
		$BC_subtot_column[$column_counter] = "";
		$column_counter++;


		for($BC_counter = 0; $BC_counter < $BC_array_size; $BC_counter++){
			$barcodes_column[$column_counter] = $barcodes[$BC_counter];
			$BC_qty_column[$column_counter] = $BC_qty[$BC_counter];
			$BC_rate_column[$column_counter] = "$".number_format($BC_rate[$BC_counter], 2);
			$BC_amt_column[$column_counter] = "$".number_format($BC_amt[$BC_counter], 2);
			$BC_subtot_column[$column_counter] = "";
			$column_counter++;

			$sub_total += $BC_amt[$BC_counter];
			$total += $BC_amt[$BC_counter];
		}

		$barcodes_column[$column_counter] = "";
		$BC_qty_column[$column_counter] = "";
		$BC_rate_column[$column_counter] = "";
		$BC_amt_column[$column_counter] = "SUBTOTAL:";
		$BC_subtot_column[$column_counter] = "$".number_format($sub_total, 2);
		$column_counter++;

		$barcodes_column[$column_counter] = "";
		$BC_qty_column[$column_counter] = "";
		$BC_rate_column[$column_counter] = "";
		$BC_amt_column[$column_counter] = "";
		$BC_subtot_column[$column_counter] = "";
		$column_counter++;
	}

	$print_array_index = 0;

	while($print_array_index < $column_counter){
		if($header_picture != ""){
			$pdf->addJpegFromFile($header_picture, 20, 700, 100, 40);
		}
		$pdf->ezSetDy(-5);
		$pdf->ezText("<b>".$invoice_type.":  ".$current_invoice."</b>", 10, $right);
		$pdf->ezSetDy(-5);
		$pdf->ezText("<b>Date:  ".$inv_date."</b>", 10, $right);
		$pdf->ezSetDy(-55);
		$pdf->ezText("<b>".$header."</b>", 10, array('aleft'=>150));
		$pdf->ezSetDy(-55);

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
			$pdf->ezSetDy(-4);
			$pdf->ezText("STORAGE BILL FOR ".$qty." NT(S) @ $".number_format($rate, 2)." PER NT", 10, array('aleft'=>150));
			$pdf->ezSetDy(-1);
			$pdf->ezText("FOR THE PERIOD OF ".$start_date." THROUGH ".$stop_date, 10, array('aleft'=>150));
			$pdf->ezSetDy(-27);
		}

		$print_table = array();
		for($i = 0; $i < 30; $i++){
			if(($print_array_index + $i) < sizeof($barcodes_column)){
				array_push($print_table, array("BARCODE\r\n"=>$barcodes_column[($print_array_index + $i)],
													"NT\r\n"=>$BC_qty_column[($print_array_index + $i)],
													"RATE/NT\r\n"=>$BC_rate_column[($print_array_index + $i)],
													"AMOUNT\r\n"=>$BC_amt_column[($print_array_index + $i)],
													"\r\n"=>$BC_subtot_column[($print_array_index + $i)]));
			}
		}
		//,'cols'=>$arrCol)
		$pdf->ezTable($print_table, '', '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>9, 
													'width'=>550, 'rowGap'=>1, 'xPos'=>65, 'xOrientation'=>'right'));

		$print_array_index = $print_array_index + 30;

		if($print_array_index >= $column_counter){
			// passed end of array, print total line.
			$pdf->ezText("---------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 10, $left);
			$pdf->ezText("<b>".$invoice_type." TOTAL:             $".number_format($total, 2)."       </b>", 12, $right);
		}

		// lastly, if there is either more to this invoice, or more invoices coming, start a new page.
		if($print_array_index < $column_counter || $current_invoice < $end_num){
			$pdf->ezNewPage();
			$pdf->ezSetDy(-8);
		}
	}
}