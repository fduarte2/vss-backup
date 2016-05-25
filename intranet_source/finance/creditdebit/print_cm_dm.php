<?
/*
*	Adam Walter, Aug 2013
*
*	Finance page to print credit and debit memos
*****************************************************************************/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$where = "WHERE BILLING_TYPE IN ('CM', 'DM') ";

	$completed_status = $HTTP_POST_VARS['completed_status'];
	if($completed_status == "FINALIZED"){
		$where .= "AND SERVICE_STATUS IN ('CREDITMEMO', 'DEBITMEMO') ";
	} elseif($completed_status == "PRE"){
		$where .= "AND SERVICE_STATUS IN ('PRECREDIT', 'PREDEBIT') ";
	} elseif($completed_status == "DELETED"){
		$where .= "AND SERVICE_STATUS IN ('DELETED') ";
	}

	$printout_type = $HTTP_POST_VARS['printout_type'];
	if($printout_type == "Memo-Invoice Range"){
		$start_num_inv = $HTTP_POST_VARS['start_num_inv'];
		$end_num_inv = $HTTP_POST_VARS['end_num_inv'];
		$where .= "AND INVOICE_NUM BETWEEN ".$start_num_inv." AND ".$end_num_inv." ";
	} elseif($printout_type == "Single Original Invoice") {
		$inv_num = $HTTP_POST_VARS['inv_num'];
		$where .= "AND ORIG_INVOICE_NUM = '".$inv_num."' ";
	} elseif($printout_type == "Memo Number"){
		$memo_num = $HTTP_POST_VARS['memo_num'];
		$where .= "AND MEMO_NUM = '".$memo_num."' ";
	}

	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter');
//	$pdf = new Cezpdf('letter','landscape');

	$pdf->ezSetMargins(20,40,20,20);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
//	$pdf->ezStartPageNumbers(720, 32, 9, '','',1);
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$grand_total = 0;

	$sql = "SELECT BILLING_NUM, LR_NUM, CUSTOMER_ID, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') THE_INV_DATE, SERVICE_AMOUNT, MEMO_NUM, INVOICE_NUM, SERVICE_DESCRIPTION
			FROM BILLING
			".$where."
			ORDER BY INVOICE_NUM, MEMO_NUM";
//	echo $sql;
//	exit;
	$memo_data = ociparse($bniconn, $sql);
	ociexecute($memo_data);
	if(!ocifetch($memo_data)){
		$pdf->ezText("<b>No Data found for Given Parameters.</b>", 10, $right);
	} else {
		$current_memo = ociresult($memo_data, "MEMO_NUM");
		ResetVars($memo_total, $memo_array);
		PrintoutHeader($pdf, $current_memo, $bniconn);

		do {
			if(ociresult($memo_data, "MEMO_NUM") != $current_memo){
				// new memo detected.
				PrintoutMemoBody($pdf, $current_memo, $memo_array, $bniconn);
				PrintoutFooter($pdf, $current_memo, $memo_total, $bniconn);
				$pdf->EzNewPage();
				$current_memo = ociresult($memo_data, "MEMO_NUM");
				ResetVars($memo_total, $memo_array);
				PrintoutHeader($pdf, $current_memo, $bniconn);
			}

			array_push($memo_array, array('desc'=>ociresult($memo_data, "SERVICE_DESCRIPTION"),
											'amt'=>"$".number_format(ociresult($memo_data, "SERVICE_AMOUNT"), 2)));
			$memo_total += ociresult($memo_data, "SERVICE_AMOUNT");
			$grand_total += ociresult($memo_data, "SERVICE_AMOUNT");

		} while(ocifetch($memo_data));

		PrintoutMemoBody($pdf, $current_memo, $memo_array, $bniconn);
		PrintoutFooter($pdf, $current_memo, $memo_total, $bniconn);
	}

	include("redirect_pdf.php");











function PrintoutHeader(&$pdf, $current_memo, $bniconn){
	$right = array('justification'=>"right");

	$sql = "SELECT LR_NUM, COMMODITY_CODE, CUSTOMER_ID, SERVICE_STATUS, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') THE_DATE
			FROM BILLING
			WHERE MEMO_NUM = '".$current_memo."'";
//	echo $sql;
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vessel = ociresult($short_term_data, "LR_NUM");
	$comm = ociresult($short_term_data, "COMMODITY_CODE");
	$cust = ociresult($short_term_data, "CUSTOMER_ID");
	$status = ociresult($short_term_data, "SERVICE_STATUS");
	$invdate = ociresult($short_term_data, "THE_DATE");

	
	$pdf->ezSetDy(-5);
	$pdf->ezText("<b>Date:  ".$invdate."</b>", 10, $right);
	$pdf->ezSetDy(-5);
	$pdf->ezText("<b>".$status."#:  ".$current_memo."</b>", 10, $right);

	if($vessel == ""){
		$vessel_name = "Unspecified Vessel";
	} else {
		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);	
		if(!ocifetch($short_term_data)){
			$vessel_name = "TRUCKED IN CARGO - ".$vessel;
		} else {
			$vessel_name = $vessel." - ".ociresult($short_term_data, "VESSEL_NAME");
		}
	}

	$sql = "SELECT CUSTOMER_NAME, CUSTOMER_ADDRESS1, CUSTOMER_ADDRESS2, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$cust."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$customer_name = ociresult($short_term_data, "CUSTOMER_NAME");
	$customer_add1 = ociresult($short_term_data, "CUSTOMER_ADDRESS1");
	$customer_add2 = ociresult($short_term_data, "CUSTOMER_ADDRESS2");
	$customer_citystatezip = ociresult($short_term_data, "CUSTOMER_CITY").", ".ociresult($short_term_data, "CUSTOMER_STATE")." - ".ociresult($short_term_data, "CUSTOMER_ZIP");

	$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$comm."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$commname = ociresult($short_term_data, "COMMODITY_NAME");

	$header_print = $customer_name."\n".$customer_add1."\n".$customer_add2."\n".$customer_citystatezip."\n\n".$vessel_name;

	$pdf->ezSetDy(-55);
	$pdf->ezText("<b>".$header_print."</b>", 10, array('aleft'=>150));
}

function ResetVars(&$memo_total, &$memo_array){
	$memo_total = 0;
	$memo_array = array();
	array_push($memo_array, array('desc'=>"<b>DESCRIPTION</b>",
									'amt'=>"<b>AMOUNT</b>"));
}

function PrintoutFooter(&$pdf, $current_memo, $memo_total, $bniconn){
	$right = array('justification'=>"right");
	$center = array('justification'=>"center");
	$pdf->ezText("-------------------------------------------------------------------------------------------------------------------------------------------------------------------", 10, $center);
	$pdf->ezText("<b>Memo    ".$current_memo."                TOTAL:             $".number_format($memo_total, 2)."             </b>", 12, $right);
}

function PrintoutMemoBody(&$pdf, $current_memo, $memo_array, $bniconn){
	$pdf->ezSetDy(-20);
	$pdf->ezTable($memo_array, '', '', array('showHeadings'=>0, 
												'shaded'=>0, 
												'showLines'=>0, 
												'fontSize'=>9, 
												'width'=>550, 
												'rowGap'=>1, 
												'xPos'=>65, 
												'xOrientation'=>'right',
												'cols'=>array('desc'=>array('width'=>'450'))));
}