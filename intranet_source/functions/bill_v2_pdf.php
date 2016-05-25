<?
/*
*	Adam Walter, Feb 2015
*
*	This program creates PDFs of V2 bills based
*	On any number of search criteria.
*
*
*	this file --does nothing on its own--
*
*
*	It is just an include file for any program that needs
*	To print V2 invoices.
*
*	As such, you need to pass a PDF object to it.  It will
*	Be modified Pass-By-Reference, so that the calling
*	Program can then use the result however it wants.
*************************************************************/

function GenerateV2PDF(&$pdf, $invoice_type, $invoice_or_preinvoice, $lr_num, $cust, $comm, $invoice_or_preinvoice_num, $emailed_or_no, $header_picture, $bniconn){
	/* $pdf: the file itself.

	*	--REQUIRED VALUES--
	*	$invoice_type:  LEASE, TRANSFER, 4PDI, etc.   --REQUIRED--
	*	$invoice_or_preinvoice:  INVOICED or PREINVOICE  --REQUIRED--  (in theory, "DELETED" may be possible as well)
	*
	*	--OPTIONAL VALUES--  
	*	$lr_num:  Only for a given ARRIVAL_NUM (VARCHAR2 in CARGO_TRACKING)
	*	$comm:  Only for a given COMMODITY_CODE (NUMBER in CARGO_TRACKING)
	*	$cust:  Only for a given RECEIVER_ID (NUMBER in CARGO_TRACKING)
	*
	*	$invoice_or_preinvoice_num:  Normally, an "invoice PDF" is for a whole invoice type; if you JUST want a single billed invoice, you can pass this
	*	$emailed_or_no:  If you want to ONLY retrieve invoices that are (or are not) emailed
	*	$header_picture:  If you want a logo on each page of the invoice, pass this.  If not, leave empty.  NOTE:  filename must be present in the directory of the calling program.
	*
	*	Other values:
	*	$bni_conn.  Database connection, so that you can pass this function an invoice in test if needed.
	**************************************************************************************************************************/
	$grand_total = 0;

	$sql = GetSQLHeader($invoice_type, $invoice_or_preinvoice, $lr_num, $cust, $comm, $invoice_or_preinvoice_num, $emailed_or_no, $bniconn);
	$bills = ociparse($bniconn, $sql);
	ociexecute($bills);
	while(ocifetch($bills)){
//		$current_num++;

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM BILL_SUB_DETAILS
				WHERE BILLING_NUM = '".ociresult($bills, "BILLING_NUM")."'
					AND SUB_DETAIL_AMT IS NOT NULL";
		$table_count_check = ociparse($bniconn, $sql);
		ociexecute($table_count_check);
		ocifetch($table_count_check);
		if(ociresult($table_count_check, "THE_COUNT") > 0){
			$invoice_total = ThreeTableInvoiceGenerate($pdf, $bills, $header_picture, $bniconn);
		} else {
			$invoice_total = TwoTableInvoiceGenerate($pdf, $bills, $header_picture, $bniconn);
		}

		$grand_total += $invoice_total;

	}

	$print_bill_type_text = GetPrintBilltype($invoice_type);
	$pdf->ezSetY(525);
	if($grand_total > 0){
		$pdf->ezText("GRAND TOTAL FOR ".$print_bill_type_text.":   $".number_format($grand_total, 2), 12, array('justification'=>'center')); 
	} else {
		$pdf->ezText("NO BILLS FOUND FOR ".$print_bill_type_text." WITHIN SEARCH CRITERIA", 12, array('justification'=>'center')); 
	}


}

function TwoTableInvoiceGenerate(&$pdf, $bills, $header_picture, $bniconn){
	$total = 0;

	$linecount = 0;  // used so that if the bill needs multiple lines, we know to print the headers each page
	// print bill header
	CompileBillHeader($blockA, $blockB, ociresult($bills, "SERVICE_STATUS"), ociresult($bills, "BILLING_NUM"), ociresult($bills, "INVOICE_NUM"), ociresult($bills, "CUSTOMER_ID"), 
						ociresult($bills, "ARRIVAL_NUM"), $bniconn, ociresult($bills, "BILLING_TYPE"), ociresult($bills, "THE_INVOICE_DATE"));
	PrintPageHeader($pdf, $blockA, $blockB, $header_picture);

	$sql_det = GetSQLDetail(ociresult($bills, "BILLING_NUM"), $bniconn);
	$bill_details = ociparse($bniconn, $sql_det);
	ociexecute($bill_details);
	while(ocifetch($bill_details)){

		CompileTwoTableBillDetail($blockC, $blockD, $blockE, $blockF, ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), 
						ociresult($bills, "SERVICE_STATUS"), ociresult($bills, "THE_DATE"), $bniconn);

		if($linecount > 10/* arbitrary value */){
			// start new page; done before the line, else we might have a page with nothing but a header and a total
			$pdf->ezNewPage();
			PrintPageHeader($pdf, $blockA, $blockB, $header_picture);
			$linecount = 0;
		}

		PrintPageDetail($pdf, $blockC, $blockD, $blockE, $blockF);
		$linecount++;

		$total += ociresult($bill_details, "SERVICE_AMOUNT");
	}

	$pdf->ezSetDy(-10);
	$pdf->ezText($run_type." TOTAL:                    $".number_format($total, 2)."  ", 10, array('justification'=>'right')); 
	$pdf->ezNewPage();

	return $total;
}

function ThreeTableInvoiceGenerate(&$pdf, $bills, $header_picture, $bniconn){

	$total = 0;
	$linecount = 0;  // used so that if the bill needs multiple lines, we know to print the headers each page
	// print bill header
	CompileBillHeader($blockA, $blockB, ociresult($bills, "SERVICE_STATUS"), ociresult($bills, "BILLING_NUM"), ociresult($bills, "INVOICE_NUM"), ociresult($bills, "CUSTOMER_ID"), 
						ociresult($bills, "ARRIVAL_NUM"), $bniconn, ociresult($bills, "BILLING_TYPE"), ociresult($bills, "THE_INVOICE_DATE"));

	PrintPageHeader($pdf, $blockA, $blockB, $header_picture);

	$sql_det = GetSQLDetail(ociresult($bills, "BILLING_NUM"), $bniconn);
	$bill_details = ociparse($bniconn, $sql_det);
	ociexecute($bill_details);
	while(ocifetch($bill_details)){
		$sub_total = 0;
		// print start of sub-areas
		CompileThreeTableBillDetail($blockC, $blockD, $blockE, $blockF, ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), 
						ociresult($bills, "SERVICE_STATUS"), ociresult($bills, "THE_DATE"), $bniconn);

		if($linecount > 17/* arbitrary value */){
			// start new page; done before the line, else we might have a page with nothing but a header and a total
			$pdf->ezNewPage();
			PrintPageHeader($pdf, $blockA, $blockB, $header_picture);
			$linecount = 0;
		}

		PrintPageDetail($pdf, $blockC, $blockD, $blockE, $blockF);
		$linecount++;

		$sql_sub_det = GetSQLSubDetail(ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), $bniconn);
		$bill_sub_details = ociparse($bniconn, $sql_sub_det);
		ociexecute($bill_sub_details);
		while(ocifetch($bill_sub_details)){
			CompileThreeTableBillSubDetail($subblockC, $subblockD, $subblockE, $subblockF, ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), 
						ociresult($bill_sub_details, "SUB_DETAIL_LINE"), ociresult($bills, "SERVICE_STATUS"), $date, $bniconn);

			if($linecount > 17/* arbitrary value */){
				// start new page; done before the line, else we might have a page with nothing but a header and a total
				$pdf->ezNewPage();
				PrintPageHeader($pdf, $blockA, $blockB, $header_picture);
				$linecount = 0;

				PrintPageDetail($pdf, $blockC, $blockD, $blockE, $blockF);
				$linecount++;
			}

			PrintPageDetail($pdf, $subblockC, $subblockD, $subblockE, $subblockF);
			$linecount++;

			$sub_total += ociresult($bill_sub_details, "SUB_DETAIL_AMT");
		}

		$pdf->ezSetDy(-10);
		$pdf->ezText($run_type." SUBTOTAL:                    $".number_format($sub_total, 2)."  ", 10, array('justification'=>'right')); 
		$pdf->ezSetDy(-10);
		$pdf->ezSetDy(-10);
//			$pdf->ezNewPage();
		$linecount += 3;

		$total += $sub_total;
	}

	$pdf->ezSetDy(-10);
	$pdf->ezText($run_type." TOTAL:                    $".number_format($total, 2)."  ", 10, array('justification'=>'right')); 
	$pdf->ezNewPage();
}













function GetSQLHeader($invoice_type, $invoice_or_preinvoice, $lr_num, $cust, $comm, $invoice_or_preinvoice_num, $emailed_or_no, $bniconn){
	$sql = "SELECT BH.*, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_DATE, TO_CHAR(NVL(INVOICE_DATE, SYSDATE), 'MM/DD/YYYY') THE_INVOICE_DATE
			FROM BILL_HEADER BH
			WHERE SERVICE_STATUS = '".$invoice_or_preinvoice."'
				AND BILLING_TYPE = '".$invoice_type."'";
	if($lr_num != ""){
		$sql .= " AND ARRIVAL_NUM = '".$lr_num."'";
	}
	if($cust != ""){
		$sql .= " AND CUSTOMER_ID = '".$cust."'";
	}
	if($comm != ""){
		$sql .= " AND COMMODITY_CODE = '".$comm."'";
	}
	if($comm != ""){
		$sql .= " AND COMMODITY_CODE = '".$comm."'";
	}
	if($emailed_or_no == "no"){
		$sql .= " AND EXPORT_FILE IS NULL";
	}elseif($emailed_or_no != ""){
		$sql .= " AND EXPORT_FILE = '".$emailed_or_no."'";
	}
	if($invoice_or_preinvoice == "INVOICED" && $invoice_or_preinvoice_num != ""){
		$sql .= " AND INVOICE_NUM = '".$invoice_or_preinvoice_num."'";
	} elseif($invoice_or_preinvoice != "INVOICED" && $invoice_or_preinvoice_num != ""){
		$sql .= " AND BILLING_NUM = '".$invoice_or_preinvoice_num."'";
	}
	$sql .= " ORDER BY BILLING_NUM";

//	echo $sql."<br>";
	return $sql;
}

function GetSQLDetail($bill_num, $bniconn){
	$sql = "SELECT * FROM BILL_DETAIL
			WHERE BILLING_NUM = '".$bill_num."'
				 AND (DETAIL_LINE_STATUS IS NULL OR DETAIL_LINE_STATUS != 'DELETED')
			ORDER BY DETAIL_LINE";

	return $sql;
}

function GetSQLSubDetail($bill_num, $detail_num, $bniconn){
	$sql = "SELECT * FROM BILL_SUB_DETAILS
			WHERE BILLING_NUM = '".$bill_num."'
				AND DETAIL_LINE = '".$detail_num."'
			ORDER BY SUB_DETAIL_LINE";

	return $sql;
}

function GetPrintBilltype($bill_type){
	// this is in the running for "most hardcoded function" I've ever written.
	if($bill_type == "4PDI"){
		return "Terminal Services/Transfers";
	} else {
		return $bill_type;
	}

	return $bill_type;
}







function CompileBillHeader(&$blockA, &$blockB, $run_type, $bill_num, $invoice_num, $cust, $lr_num, $bniconn, $this_bill_type, $invoice_date){

	$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$cust_details = ociparse($bniconn, $sql);
	ociexecute($cust_details);
	ocifetch($cust_details);

	list($code, $customer_name) = split("-", ociresult($cust_details, "CUSTOMER_NAME"), 2);
	if (!is_numeric($code)) {
		// ust in case the name doesn't have the code prefix
		$customer_name = trim(ociresult($cust_details, "CUSTOMER_NAME"));
	}
	$customer_info = $customer_name . "   " . $cust;

	$address1 = ociresult($cust_details, "CUSTOMER_ADDRESS1");
	$address2 = ociresult($cust_details, "CUSTOMER_ADDRESS2");

	if ($address1 != "") {
		$customer_info .= "\n" . $address1;
	}

	if ($address2 != "") {
		// put SUITE info right after address 1
		if ((strpos($address2, 'SUITE') === 0) && 
		(strlen($address2) <= 10)) {
		  $customer_info .= ", " . $address2;
		} else {
		  $customer_info .= "\n" . $address2;
		}
	}

	$city = ociresult($cust_details, "CUSTOMER_CITY");
/*	if($type=="claims"){
	  if(trim($row2['CUSTOMER_CLAIM_CITY']) != ""){
		  $city = trim($row2['CUSTOMER_CLAIM_CITY']);
	  }
	}*/

	if ($city != "") {
		$customer_info .= "\n" . $city . ", ";
	}

	$state = ociresult($cust_details, "CUSTOMER_STATE");
/*	if($type=="claims"){
	  if(trim($row2['CUSTOMER_CLAIM_STATE']) != ""){
		  $state = trim($row2['CUSTOMER_CLAIM_STATE']);
	  }
	}*/

	if ($state != "") {
		$customer_info .= $state . " ";
	}

	$zip = ociresult($cust_details, "CUSTOMER_ZIP");
/*	if($type=="claims"){
	  if(trim($row2['CUSTOMER_CLAIM_ZIP']) != ""){
		  $zip = trim($row2['CUSTOMER_CLAIM_ZIP']);
	  }
	}*/

	if ($zip != "") {
		$customer_info .= $zip;
	}

	$country_code = ociresult($cust_details, "COUNTRY_CODE");
	if ($country_code != "" && $country_code != "US") {
		$country_name = getCountryNameOCI($bniconn, $country_code);
		$customer_info .= "\n" . $country_name;
	}

	$customer_info = strtoupper($customer_info);

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$lr_num."'";
//	echo $sql."<br>";
//	exit;
	$ves_details = ociparse($bniconn, $sql);
	ociexecute($ves_details);
	ocifetch($ves_details);
	$vessel_name = ociresult($ves_details, "VESSEL_NAME");
	if($vessel_name == ""){
		$vessel_name = $lr_num." - TRUCKED IN CARGO";
	}
	if($this_bill_type == "LEASE"){
		$vessel_name = "";
	}

	$blockA = array();
	if($run_type == "PREINVOICE"){
		$display_num = $bill_num;
	} else {
		$display_num = $invoice_num;
	}
    array_push($blockA, array('first'=>'', 'second'=>'', 'third'=>$run_type." No:", 'fourth'=>$display_num));
	//CMarttinen: changed next line from 'fourth'=>date('m/d/Y') to $invoice_date
    array_push($blockA, array('first'=>'', 'second'=>'', 'third'=>$run_type." Date:", 'fourth'=>$invoice_date));
	
	$blockB = array();
    array_push($blockB, array('first'=>'', 'second'=>$customer_info . "\n\n\n" . $vessel_name, 
				    'third'=>'', 'fourth'=>''));
}

function CompileTwoTableBillDetail(&$blockC, &$blockD, &$blockE, &$blockF, $bill_num, $detail_line, $run_type, $date, $bniconn){
	$sql = "SELECT * FROM BILL_DETAIL WHERE BILLING_NUM = '".$bill_num."' AND DETAIL_LINE = '".$detail_line."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$desc = ociresult($short_term_data, "SERVICE_DESCRIPTION");
	if($run_type == "PREINVOICE"){
//		$desc = "Billing#: ".$bill_num."  Detail Line: ".$detail_line." ".$desc;
		$desc = "Detail Line: ".$detail_line." ".$desc;
	}

	$blockC = array();
	array_push($blockC, array('first'=>$date, 'second'=>$desc, 'third'=>"$".number_format(ociresult($short_term_data, "SERVICE_AMOUNT"), 2)));
}

function CompileThreeTableBillDetail(&$blockC, &$blockD, &$blockE, &$blockF, $bill_num, $detail_line, $run_type, $date, $bniconn){
	$sql = "SELECT BD.*, TO_CHAR(BD.SERVICE_DATE_DETAIL, 'MM/DD/YYYY') THE_DATE 
			FROM BILL_DETAIL BD
			WHERE BILLING_NUM = '".$bill_num."' AND DETAIL_LINE = '".$detail_line."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$desc = ociresult($short_term_data, "SERVICE_DESCRIPTION");
	if($run_type == "PREINVOICE"){
//		$desc = "Billing#: ".$bill_num."  Detail Line: ".$detail_line." ".$desc;
		$desc = "Detail Line: ".$detail_line." ".$desc;
	}

	$blockC = array();
	array_push($blockC, array('first'=>ociresult($short_term_data, "THE_DATE"), 'second'=>$desc, 'third'=>""));
}

function CompileThreeTableBillSubDetail(&$blockC, &$blockD, &$blockE, &$blockF, $bill_num, $detail_line, $sub_detail_line, $run_type, $date, $bniconn){
	$sql = "SELECT * FROM BILL_SUB_DETAILS WHERE BILLING_NUM = '".$bill_num."' AND DETAIL_LINE = '".$detail_line."' AND SUB_DETAIL_LINE = '".$sub_detail_line."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$desc = ociresult($short_term_data, "SERVICE_SUB_DESC");

	$blockC = array();
	array_push($blockC, array('first'=>"", 'second'=>$desc, 'third'=>"$".number_format(ociresult($short_term_data, "SUB_DETAIL_AMT"), 2)));
}

function PrintPageHeader(&$pdf, $blockA, $blockB, $header_picture){
	if($header_picture != ""){
		$pdf->addJpegFromFile($header_picture, 20, 700, 100, 40);
	}

    $pdf->ezSetY(740);

    $pdf->ezTable($blockA, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>250), 
					  'second'=>array('justification'=>'left', 'width'=>128),
					  'third'=>array('justification'=>'right', 'width'=>118), 
					  'fourth'=>array('justification'=>'right', 'width'=>62)),
			  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));

    $pdf->ezSetY(640);
    $pdf->ezTable($blockB, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>120), 
					  'second'=>array('justification'=>'left', 'width'=>288),
					  'third'=>array('justification'=>'right', 'width'=>88), 
					  'fourth'=>array('justification'=>'right', 'width'=>62)),
			  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));

    $pdf->ezSetY(525);
}

function PrintPageDetail(&$pdf, $blockC, $blockD, $blockE, $blockF){
    $pdf->ezTable($blockC, array('first'=>'', 'second'=>'', 'third'=>''),
		  '', array('justification'=>'right',
					'cols'=>array('first'=>array('justification'=>'left', 'width'=>120), 
					  'second'=>array('justification'=>'left', 'width'=>358),
					  'third'=>array('justification'=>'right', 'width'=>80)),
			    'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>588));
}





function getCountryNameOCI($bniconn, $country_code) {

	$sql = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = '".$country_code."'";
	$country_details = ociparse($bniconn, $sql);
	ociexecute($country_details);
	ocifetch($country_details);

	return ociresult($country_details, "COUNTRY_NAME");
}
