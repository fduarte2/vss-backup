<?
/*
*		Adam Walter, June 2014.
*
*		The general-purpose rewrite of our (non storage) billing routines.
*
*		This has been a VERY long time in coming.  I only hope I can predict enough
*		future possibilities so that the "general purposeness: of this program
*		Carries itself at least into the next decade, if not longer.
*********************************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

//	include("billing_functions.php");



	// first, some variables.  I do this inline rather than just make a general
	// "get var" fucntion due to how functions can/cannot read the HTTP_POST_VARS array.

	$run_type = $HTTP_POST_VARS["generate_type"]; // Invoice or PreInvoice
	$lr_num = $HTTP_POST_VARS["lr_num"];
	$cust = $HTTP_POST_VARS["cust"];
	$comm = $HTTP_POST_VARS["comm"];

	$bill_type_array = array(); // Termserv, truckloading, dockage, etc.
	$billing_type = $HTTP_POST_VARS["selected_type"];	
// for x = 1 to total types, push array



	// TEST ASSIGNMENTS
	array_push($bill_type_array, $billing_type);
//	$run_type = "INVOICE";

	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','portrait');
	$pdf->openHere('XYZ', 0, 800, 1.25);
	$pdf -> ezSetMargins(20,20,30,30);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

	$start_num = getMaxInvoiceNumber($run_type, $bniconn) + 1;
	$current_num = $start_num - 1;

	foreach($bill_type_array as $this_bill_type){
//		$bill_table_method = GetBillMethod($this_bill_type, $bniconn);

//		if($bill_table_method == "Two Tables"){
			InvoicesGenerate($pdf, $current_num, $this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn);
//		} else {
//			ThreeTableInvoiceGenerate($pdf, $current_num, $this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn);
//		}
	}

	// we've got a compiled PDF, now we...
	if($run_type == "PREINVOICE" || $start_num > $current_num){ // 2nd clause is for if no bills were created
		include("redirect_pdf.php");
	} else {
		redirect_invoices($pdf, $start_num, $current_num, $this_bill_type);
	}

	






function InvoicesGenerate(&$pdf, &$current_num, $this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn){
	$grand_total = 0;

	$sql = GetSQLHeader($this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn);
	$bills = ociparse($bniconn, $sql);
	ociexecute($bills);
	while(ocifetch($bills)){
		$current_num++;

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM BILL_SUB_DETAILS
				WHERE BILLING_NUM = '".ociresult($bills, "BILLING_NUM")."'
					AND SUB_DETAIL_AMT IS NOT NULL";
		$table_count_check = ociparse($bniconn, $sql);
		ociexecute($table_count_check);
		ocifetch($table_count_check);
		if(ociresult($table_count_check, "THE_COUNT") > 0){
			$invoice_total = ThreeTableInvoiceGenerate($pdf, $current_num, $this_bill_type, $run_type, $bills, $lr_num, $cust, $comm, $bniconn);
		} else {
			$invoice_total = TwoTableInvoiceGenerate($pdf, $current_num, $this_bill_type, $run_type, $bills, $lr_num, $cust, $comm, $bniconn);
		}

		$grand_total += $invoice_total;

	}

	$print_bill_type = GetPrintBilltype($this_bill_type);
	$pdf->ezSetY(525);
	if($grand_total > 0){
		$pdf->ezText("GRAND TOTAL FOR ".$print_bill_type.":   $".number_format($grand_total, 2), 12, array('justification'=>'center')); 
	} else {
		$pdf->ezText("NO BILLS FOUND FOR ".$print_bill_type." WITHIN SEARCH CRITERIA", 12, array('justification'=>'center')); 
	}


}





function TwoTableInvoiceGenerate(&$pdf, &$current_num, $this_bill_type, $run_type, $bills, $lr_num, $cust, $comm, $bniconn){
//	$grand_total = 0;

//	$sql = GetSQLHeader($this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn);
//	$bills = ociparse($bniconn, $sql);
//	ociexecute($bills);
//	while(ocifetch($bills)){
		$total = 0;
//		$current_num++;


		$linecount = 0;  // used so that if the bill needs multiple lines, we know to print the headers each page
		// print bill header
		CompileBillHeader($blockA, $blockB, $run_type, ociresult($bills, "BILLING_NUM"), $current_num, ociresult($bills, "CUSTOMER_ID"), ociresult($bills, "ARRIVAL_NUM"), $bniconn, $this_bill_type);

		PrintPageHeader($pdf, $blockA, $blockB, $this_bill_type);
//		PrintBlockA($blockA);
//		PrintBlockB($blockB);

		// now, line-by-line bill details
		$last_disp_date = "";
		$sql_det = GetSQLDetail(ociresult($bills, "BILLING_NUM"), $bniconn);
		$bill_details = ociparse($bniconn, $sql_det);
		ociexecute($bill_details);
		while(ocifetch($bill_details)){
			// print detail lines
			CompileTwoTableBillDetail($blockC, $blockD, $blockE, $blockF, ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), $run_type, $last_disp_date, $bniconn);

			if($linecount > 10/* arbitrary value */){
				// start new page; done before the line, else we might have a page with nothing but a header and a total
				$pdf->ezNewPage();
				PrintPageHeader($pdf, $blockA, $blockB, $this_bill_type);
				$linecount = 0;
			}

			PrintPageDetail($pdf, $blockC, $blockD, $blockE, $blockF);
//			PrintBlockC($blockC);
//			PrintBlockD($blockD);
//			PrintBlockE($blockE);
			$linecount++;

			$total += ociresult($bill_details, "SERVICE_AMOUNT");
//			$grand_total += ociresult($bill_details, "SERVICE_AMOUNT");

		}

		$pdf->ezSetDy(-10);
		$pdf->ezText($run_type." TOTAL:                    $".number_format($total, 2)."  ", 10, array('justification'=>'right')); 
		$pdf->ezNewPage();

		if($run_type == "INVOICE"){
			$sql = "UPDATE BILL_HEADER
					SET SERVICE_STATUS = 'INVOICED',
						INVOICE_DATE = TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY'),
						INVOICE_NUM = '".$current_num."'
					WHERE BILLING_NUM = '".ociresult($bills, "BILLING_NUM")."'";
			$invoice_set = ociparse($bniconn, $sql);
			ociexecute($invoice_set);
		}
//	}

//	if($grand_total > 0){
//		$pdf->ezText("GRAND TOTAL FOR ".$this_bill_type.":   $".number_format($grand_total, 2), 12, array('justification'=>'center')); 
//	} else {
//		$pdf->ezText("NO BILLS FOUND FOR ".$this_bill_type." WITHIN SEARCH CRITERIA", 12, array('justification'=>'center')); 
//	}

	return $total;
}

function ThreeTableInvoiceGenerate(&$pdf, &$current_num, $this_bill_type, $run_type, $bills, $lr_num, $cust, $comm, $bniconn){
//	$grand_total = 0;

//	$sql = GetThreeTableSQLHeader($this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn);
//	$bills = ociparse($bniconn, $sql);
//	ociexecute($bills);
//	while(ocifetch($bills)){
	$total = 0;
//		$sub_total = 0;
//		$current_num++;


	$linecount = 0;  // used so that if the bill needs multiple lines, we know to print the headers each page
	// print bill header
	CompileBillHeader($blockA, $blockB, $run_type, ociresult($bills, "BILLING_NUM"), $current_num, ociresult($bills, "CUSTOMER_ID"), ociresult($bills, "ARRIVAL_NUM"), $bniconn, $this_bill_type);

	PrintPageHeader($pdf, $blockA, $blockB, $this_bill_type);

	// now, line-by-line bill details
	$sql_det = GetSQLDetail(ociresult($bills, "BILLING_NUM"), $bniconn);
	$bill_details = ociparse($bniconn, $sql_det);
	ociexecute($bill_details);
	while(ocifetch($bill_details)){
		$sub_total = 0;
		// print start of sub-areas
		CompileThreeTableBillDetail($blockC, $blockD, $blockE, $blockF, ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), $run_type, ociresult($bills, "THE_DATE"), $bniconn);

		if($linecount > 17/* arbitrary value */){
			// start new page; done before the line, else we might have a page with nothing but a header and a total
			$pdf->ezNewPage();
			PrintPageHeader($pdf, $blockA, $blockB, $this_bill_type);
			$linecount = 0;
		}

		PrintPageDetail($pdf, $blockC, $blockD, $blockE, $blockF);
		$linecount++;

		$last_disp_date = "";
		$sql_sub_det = GetSQLSubDetail(ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), $bniconn);
		$bill_sub_details = ociparse($bniconn, $sql_sub_det);
		ociexecute($bill_sub_details);
		while(ocifetch($bill_sub_details)){
			CompileThreeTableBillSubDetail($subblockC, $subblockD, $subblockE, $subblockF, ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), 
						ociresult($bill_sub_details, "SUB_DETAIL_LINE"), $run_type, $last_disp_date, $bniconn);

			if($linecount > 17/* arbitrary value */){
				// start new page; done before the line, else we might have a page with nothing but a header and a total
				$pdf->ezNewPage();
				PrintPageHeader($pdf, $blockA, $blockB, $this_bill_type);
				$linecount = 0;

				PrintPageDetail($pdf, $blockC, $blockD, $blockE, $blockF);
				$linecount++;
			}

			PrintPageDetail($pdf, $subblockC, $subblockD, $subblockE, $subblockF);
			$linecount++;

			$sub_total += ociresult($bill_sub_details, "SUB_DETAIL_AMT");
		}

		$more_info = GetSubtotalText(ociresult($bills, "BILLING_NUM"), ociresult($bill_details, "DETAIL_LINE"), $bniconn);

		$pdf->ezSetDy(-10);
		$pdf->ezText("_____________", 10, array('justification'=>'right')); 
		$pdf->ezSetDy(-5);
		$pdf->ezText("<b>".$run_type." Subtotal".$more_info."                    $".number_format($sub_total, 2)."  </b>", 10, array('justification'=>'right')); 
		$pdf->ezSetDy(-5);
		$pdf->ezText("____________    ______________________________________________________________________    _____________", 10, array('justification'=>'right')); 
		$pdf->ezSetDy(-10);
		$pdf->ezSetDy(-10);
//			$pdf->ezNewPage();
		$linecount += 3;

		$total += $sub_total;
	}

	$pdf->ezSetDy(-10);
	$pdf->ezText($run_type." TOTAL:                    $".number_format($total, 2)."  ", 10, array('justification'=>'right')); 
	$pdf->ezNewPage();

	if($run_type == "INVOICE"){
		$sql = "UPDATE BILL_HEADER
				SET SERVICE_STATUS = 'INVOICED',
					INVOICE_DATE = TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY'),
					INVOICE_NUM = '".$current_num."'
				WHERE BILLING_NUM = '".ociresult($bills, "BILLING_NUM")."'";
		$invoice_set = ociparse($bniconn, $sql);
		ociexecute($invoice_set);
	}

	return $total;
}






function GetSQLHeader($this_bill_type, $run_type, $lr_num, $cust, $comm, $bniconn){
	$sql = "SELECT BH.*, TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') THE_DATE 
			FROM BILL_HEADER BH
			WHERE SERVICE_STATUS = 'PREINVOICE'
				AND BILLING_TYPE = '".$this_bill_type."'";
	if($lr_num != ""){
		$sql .= " AND ARRIVAL_NUM = '".$lr_num."'";
	}
	if($cust != ""){
		$sql .= " AND CUSTOMER_ID = '".$cust."'";
	}
	if($comm != ""){
		$sql .= " AND COMMODITY_CODE = '".$comm."'";
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






function CompileBillHeader(&$blockA, &$blockB, $run_type, $bill_num, $current_num, $cust, $lr_num, $bniconn, $this_bill_type){

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
	} elseif($lr_num == "-1"){
		$vessel_name = "";
	}
	if($this_bill_type == "LEASE"){
		$vessel_name = "";
	}

	$blockA = array();
	if($run_type == "PREINVOICE"){
		$display_num = $bill_num;
	} else {
		$display_num = $current_num;
	}
    array_push($blockA, array('first'=>'', 'second'=>'', 'third'=>$run_type." No:", 'fourth'=>$display_num));
    array_push($blockA, array('first'=>'', 'second'=>'', 'third'=>$run_type." Date:", 'fourth'=>date('m/d/Y')));
	
	$blockB = array();
    array_push($blockB, array('first'=>'', 'second'=>$customer_info . "\n\n\n" . $vessel_name, 
				    'third'=>'', 'fourth'=>''));
}
	
function CompileTwoTableBillDetail(&$blockC, &$blockD, &$blockE, &$blockF, $bill_num, $detail_line, $run_type, &$date, $bniconn){
	$sql = "SELECT BD.*, TO_CHAR(BD.SERVICE_DATE_DETAIL, 'MM/DD/YYYY') THE_DATE 
			FROM BILL_DETAIL BD
			WHERE BILLING_NUM = '".$bill_num."' 
				AND DETAIL_LINE = '".$detail_line."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$desc = ociresult($short_term_data, "SERVICE_DESCRIPTION");
	if($run_type == "PREINVOICE"){
//		$desc = "Billing#: ".$bill_num."  Detail Line: ".$detail_line." ".$desc;
		$desc = "Detail Line: ".$detail_line." ".$desc;
	}
	if($date != ociresult($short_term_data, "THE_DATE")){
		$disp_date = ociresult($short_term_data, "THE_DATE");
		$date = ociresult($short_term_data, "THE_DATE");
	} else {
		$disp_date = "";
	}

	$blockC = array();
	array_push($blockC, array('first'=>$disp_date, 'second'=>$desc, 'third'=>"$".number_format(ociresult($short_term_data, "SERVICE_AMOUNT"), 2)));
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
//	array_push($blockC, array('first'=>ociresult($short_term_data, "THE_DATE"), 'second'=>$desc, 'third'=>""));
	array_push($blockC, array('first'=>"", 'second'=>$desc, 'third'=>""));
}

function CompileThreeTableBillSubDetail(&$blockC, &$blockD, &$blockE, &$blockF, $bill_num, $detail_line, $sub_detail_line, $run_type, &$date, $bniconn){
	$sql = "SELECT BSD.*, TO_CHAR(BSD.SUB_DETAIL_DATE, 'MM/DD/YYYY') THE_DATE 
			FROM BILL_SUB_DETAILS BSD
			WHERE BILLING_NUM = '".$bill_num."' 
			AND DETAIL_LINE = '".$detail_line."' 
			AND SUB_DETAIL_LINE = '".$sub_detail_line."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$desc = ociresult($short_term_data, "SERVICE_SUB_DESC");
	if($date != ociresult($short_term_data, "THE_DATE")){
		$disp_date = ociresult($short_term_data, "THE_DATE");
		$date = ociresult($short_term_data, "THE_DATE");
	} else {
		$disp_date = "";
	}

	$blockC = array();
	array_push($blockC, array('first'=>$disp_date, 'second'=>$desc, 'third'=>"$".number_format(ociresult($short_term_data, "SUB_DETAIL_AMT"), 2)));
}




function getCountryNameOCI($bniconn, $country_code) {

	$sql = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = '".$country_code."'";
	$country_details = ociparse($bniconn, $sql);
	ociexecute($country_details);
	ocifetch($country_details);

	return ociresult($country_details, "COUNTRY_NAME");
}




function PrintPageHeader(&$pdf, $blockA, $blockB, $this_bill_type){
    $pdf->ezSetY(740);

    $pdf->ezTable($blockA, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>250), 
					  'second'=>array('justification'=>'left', 'width'=>128),
					  'third'=>array('justification'=>'right', 'width'=>118), 
					  'fourth'=>array('justification'=>'right', 'width'=>62)),
			  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));

    $pdf->ezSetY(670);
    $pdf->ezTable($blockB, array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>''), 
		  '', array('cols'=>array('first'=>array('justification'=>'left', 'width'=>120), 
					  'second'=>array('justification'=>'left', 'width'=>288),
					  'third'=>array('justification'=>'right', 'width'=>88), 
					  'fourth'=>array('justification'=>'right', 'width'=>62)),
			  'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>558));

	$bill_text = GetBillText($this_bill_type);

	if($bill_text != ""){
		$pdf->ezSetY(550);
		$pdf->ezText("<b>*** ".$bill_text." ***</b>", 10, array('justification'=>'center')); 
	}

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


function getMaxInvoiceNumber($run_type, $bniconn){
	$sql = "SELECT GREATEST(NVL(MAX(INVOICE_NUM), 0), 301500000) THE_MAX 
			FROM BILL_HEADER";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	return ociresult($short_term_data, "THE_MAX");
}







function redirect_invoices($pdf, $start_num, $current_num, $billing_type){
	$pdfcode = $pdf->ezOutput();

	$dir = '/var/www/html/invoices';
//	$dir = '/var/www/html/TS_Testing';
	if (!file_exists($dir)) {
	mkdir ($dir, 0775);
	}

	$fname = $dir . '/' . $start_num . '-' . $current_num . '-' . $billing_type . '.pdf';

	$fp = fopen($fname, 'w');
	fwrite($fp, $pdfcode);
	fclose($fp);

	list($junk1, $junk2, $junk3, $junk4, $junk5, $filename) = split("/", $fname);
	header("Location: /invoices/$filename");
//	header("Location: /TS_Testing/$filename");
}





function GetBillMethod($this_bill_type, $bniconn){
	if($this_bill_type == "LABOR"){
		return "Two Tables";
	} else {
		return "Three Tables";
	}
}



function GetPrintBilltype($bill_type){
	// this is in the running for "most hardcoded function" I've ever written.
	// well... then I had to write GetBillText...
	if($bill_type == "4PDI"){
		return "Terminal Services/Transfers";
	} else {
		return $bill_type;
	}

	return $bill_type;
}



function GetBillText($this_bill_type){
	if($this_bill_type == "TRUCK UNLOADING"){
		return "TRUCK UNLOADING CHARGE";
	}

	return "";
}



function GetSubtotalText($bill_num, $detail_line, $bniconn){
	$default_return = ":";
	
	$sql = "SELECT *
			FROM BILL_HEADER BH, BILL_DETAIL BD
			WHERE BH.BILLING_NUM = BD.BILLING_NUM
				AND BD.DETAIL_LINE = '".$detail_line."'
				AND BH.BILLING_NUM = '".$bill_num."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "CUSTOMER_ID") == "313" && (ociresult($short_term_data, "COMMODITY_CODE") == "1272" || ociresult($short_term_data, "COMMODITY_CODE") == "1299")){
		// dolepaper specific subtotal line
		return " for ".ociresult($short_term_data, "SERVICE_DESCRIPTION");
	}

	return $default_return;
}