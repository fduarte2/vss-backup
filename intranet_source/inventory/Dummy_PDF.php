<?
/*
*	Adam Walter, May 2013
*
*	Rudy's (2008) method of printing Barcodes from Dummy is no logner acceptable, as
*	New versions of Word keep breaking the routine.  This page takes a Dummy# 
*	(passed via $HTTP_GET) and prints a PDF.
****************************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");


	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");

	if($bniconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$DM = $HTTP_GET_VARS['DumNum'];
//	echo "DM:".$DM;
//	exit;

	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter','landscape');
	$pdf->ezSetMargins(10,10,10,10);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$header_info = array();
	$header_info['PORT_num'] = $PORT_num;

	$sql = "SELECT DISTINCT VESSEL_NAME, BDW.LR_NUM, CUSP1.CUSTOMER_NAME THE_OWNER, CUSP2.CUSTOMER_NAME BILL_TO, COMMODITY_NAME, INITIAL_ID, TO_CHAR(ORDER_DATE, 'MM/DD/YYYY') ORD_DATE, ORDER_NO,
				TRANSPORT_MODE, TRANSPORT_NO, SUPERVISOR, DELIVER_TO, CARRIER, REMARKS, COMMENTS 
			FROM BNI_DUMMY_WITHDRAWAL BDW, CUSTOMER_PROFILE CUSP1, CUSTOMER_PROFILE CUSP2, VESSEL_PROFILE VP, COMMODITY_PROFILE COMP
			WHERE D_DEL_NO = '".$DM."'
				AND BDW.OWNER_ID = CUSP1.CUSTOMER_ID
				AND BDW.BILL_TO_CUSTOMER = CUSP2.CUSTOMER_ID
				AND BDW.LR_NUM = VP.LR_NUM
				AND BDW.COMMODITY_CODE = COMP.COMMODITY_CODE";
//	echo $sql."<br>";;
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("Dummy#".$DM." not found.", 12, $center);
	} else {

		$header_info['lr_num'] = ociresult($stid, "LR_NUM");
		$header_info['vessel'] = ociresult($stid, "VESSEL_NAME");
		$header_info['owner'] = ociresult($stid, "THE_OWNER");
		$header_info['bill'] = ociresult($stid, "BILL_TO");
		$header_info['commname'] = ociresult($stid, "COMMODITY_NAME");
		$header_info['initial'] = ociresult($stid, "INITIAL_ID");
		$header_info['orderdate'] = ociresult($stid, "ORD_DATE");
		$header_info['ordernum'] = ociresult($stid, "ORDER_NO");
		$header_info['transmode'] = ociresult($stid, "TRANSPORT_MODE");
		$header_info['transnum'] = ociresult($stid, "TRANSPORT_NO");
		$header_info['supv'] = ociresult($stid, "SUPERVISOR");
		$header_info['delv_to'] = ociresult($stid, "DELIVER_TO");
		$header_info['carrier'] = ociresult($stid, "CARRIER");
		$header_info['remarks'] = ociresult($stid, "REMARKS");
		$header_info['comments'] = ociresult($stid, "COMMENTS");

		print_header($pdf, $header_info, $DM, $rfconn);

		$body_info = array();
		$total_qty1 = 0;
		$total_qty2 = 0;
		$total_wt = 0;
		array_push($body_info, array('bol'=>"BoL",
									'mark'=>"Mark",
									'loc'=>"Loc",
									'qty1'=>"Qty1",
									'unit1'=>"Unit1",
									'qty2'=>"Qty2",
									'unit2'=>"Unit2",
									'weight'=>"Weight",
									'code'=>"Code"));

		$sql = "SELECT BOL, MARK, QTY1, UNIT1, QTY2, UNIT2, WEIGHT, LOCATION_NOTE
				FROM BNI_DUMMY_WITHDRAWAL
				WHERE D_DEL_NO = '".$DM."'
				ORDER BY BOL";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			$sql = "SELECT CARGO_LOCATION 
					FROM CARGO_MANIFEST 
					WHERE CARGO_BOL = '".ociresult($stid, "BOL")."'
						AND CARGO_MARK = '".ociresult($stid, "MARK")."'
						AND LR_NUM = '".$header_info['lr_num']."'";
			$location = ociparse($bniconn, $sql);
			ociexecute($location);
			ocifetch($location);

			$total_qty1 += ociresult($stid, "QTY1");
			$total_qty2 += ociresult($stid, "QTY2");
			$total_wt += ociresult($stid, "WEIGHT");

			array_push($body_info, array('bol'=>"<b>".ociresult($stid, "BOL")."</b>",
										'mark'=>"<b>".ociresult($stid, "MARK")."</b>",
										'loc'=>"<b>".ociresult($location, "CARGO_LOCATION")."</b>",
										'qty1'=>"<b>".ociresult($stid, "QTY1")."</b>",
										'unit1'=>"<b>".ociresult($stid, "UNIT1")."</b>",
										'qty2'=>"<b>".ociresult($stid, "QTY2")."</b>",
										'unit2'=>"<b>".ociresult($stid, "UNIT2")."</b>",
										'weight'=>"<b>".ociresult($stid, "WEIGHT")."</b>",
										'code'=>"<b>".ociresult($stid, "LOCATION_NOTE")."</b>"));
		}

		$pdf->ezSetY(485);
		$body_cols = array('bol'=>array('width'=>80, 'justification'=>'left'),
							'mark'=>array('width'=>270, 'justification'=>'left'),
							'loc'=>array('width'=>50, 'justification'=>'left'),
							'qty1'=>array('width'=>50, 'justification'=>'left'),
							'unit1'=>array('width'=>50, 'justification'=>'left'),
							'qty2'=>array('width'=>50, 'justification'=>'left'),
							'unit2'=>array('width'=>50, 'justification'=>'left'),
							'weight'=>array('width'=>100, 'justification'=>'left'),
							'code'=>array('width'=>60, 'justification'=>'left'));
		$pdf->ezTable($body_info, '', '', array('showHeadings'=>0, 
												'shaded'=>0, 
												'showLines'=>0,
												'fontSize'=>10,
												'width'=>760,
												'rowGap'=>1,
												'colGap'=>1,
												'cols'=>$body_cols));

		$pdf->ezSetDy(-10);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezText("*".$DM."*", 48, array('left'=>'500'));
		$pdf->ezSetDy(25);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$pdf->ezText("Dummy Number: ".$DM, 12, $center);

		print_footer($pdf, $total_qty1, $total_qty2, $total_wt, $header_info['comments'], $rfconn);


		include("redirect_pdf.php");
	}





function print_header(&$pdf, $header_info, $DM, $rfconn){
/*
		$header_info['lr_num'] = ociresult($stid, "LR_NUM");
		$header_info['vessel'] = ociresult($stid, "VESSEL_NAME");
		$header_info['owner'] = ociresult($stid, "THE_OWNER");
		$header_info['bill'] = ociresult($stid, "BILL_TO");
		$header_info['commname'] = ociresult($stid, "COMMODITY_NAME");
		$header_info['initial'] = ociresult($stid, "INITIAL_ID");
		$header_info['orderdate'] = ociresult($stid, "ORD_DATE");
		$header_info['ordernum'] = ociresult($stid, "ORDER_NO");
		$header_info['transmode'] = ociresult($stid, "TRANSPORT_MODE");
		$header_info['transnum'] = ociresult($stid, "TRANSPORT_NO");
		$header_info['supv'] = ociresult($stid, "SUPERVISOR");
		$header_info['delv_to'] = ociresult($stid, "DELIVER_TO");
		$header_info['carrier'] = ociresult($stid, "CARRIER");
		$header_info['remarks'] = ociresult($stid, "REMARKS");
*/
	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

	$header_top_array = array();
	$header_mid1_array = array();
	$header_mid2_array = array();

	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

	array_push($header_top_array, array('col1'=>"<b>Vessel: ".$header_info['vessel']."</b>",
										'col2'=>"<b>Owner: ".$header_info['owner']."</b>",
										'col3'=>""));
	array_push($header_top_array, array('col1'=>"<b>Commodity: ".$header_info['commname']."</b>",
										'col2'=>"<b>Dummy Delivery Num: ".$DM."</b>",
										'col3'=>"<b>Initial: ".$header_info['initial']."</b>"));
	$header_top_cols = array('col1'=>array('width'=>300, 'justification'=>'left'),
							'col2'=>array('width'=>330, 'justification'=>'left'),
							'col3'=>array('width'=>130, 'justification'=>'left'));
	$pdf->line(15,570,760,570);

	$pdf->ezSetY(600);
	$pdf->ezTable($header_top_array, '', '', array('showHeadings'=>0, 
														'shaded'=>0, 
														'showLines'=>0,
														'fontSize'=>10,
														'width'=>760,
														'rowGap'=>1,
														'colGap'=>1,
														'cols'=>$header_top_cols));

	array_push($header_mid1_array, array('col1'=>"Order Date: ",
										'col2'=>"Order No: ",
										'col3'=>"Transport Mode: ",
										'col4'=>"Trailer Lic Num: ",
										'col5'=>"Bill To Customer: ",
										'col6'=>"Supervisor: "));
	array_push($header_mid1_array, array('col1'=>$header_info['orderdate'],
										'col2'=>"<b>".$header_info['ordernum']."</b>",
										'col3'=>$header_info['transmode'],
										'col4'=>$header_info['transnum'],
										'col5'=>$header_info['bill'],
										'col6'=>$header_info['supv']));
	$header_mid1_cols = array('col1'=>array('width'=>90, 'justification'=>'left'),
							'col2'=>array('width'=>140, 'justification'=>'left'),
							'col3'=>array('width'=>80, 'justification'=>'left'),
							'col4'=>array('width'=>100, 'justification'=>'left'),
							'col5'=>array('width'=>200, 'justification'=>'left'),
							'col6'=>array('width'=>150, 'justification'=>'left'));
	$pdf->ezSetY(565);
	$pdf->ezTable($header_mid1_array, '', '', array('showHeadings'=>0, 
														'shaded'=>0, 
														'showLines'=>0,
														'fontSize'=>9,
														'width'=>760,
														'rowGap'=>1,
														'colGap'=>1,
														'cols'=>$header_mid1_cols));

	$pdf->ezSetY(540);
	array_push($header_mid2_array, array('col1'=>"Delivery To:\r\n".$header_info['delv_to'],
										'col2'=>"Carrier:\r\n".$header_info['carrier']."\r\nRemarks:\r\n".$header_info['remarks']));
	$header_mid2_cols = array('col1'=>array('width'=>300, 'justification'=>'left'),
							'col2'=>array('width'=>460, 'justification'=>'left'));
	$pdf->ezTable($header_mid2_array, '', '', array('showHeadings'=>0, 
														'shaded'=>0, 
														'showLines'=>0,
														'fontSize'=>8,
														'width'=>760,
														'rowGap'=>1,
														'colGap'=>1,
														'cols'=>$header_mid2_cols));
	$pdf->line(15,490,760,490);


}


function print_footer(&$pdf, $total_qty1, $total_qty2, $total_wt, $comments, $rfconn){
	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

	$footer = array();
	array_push($footer, array('onlycol'=>"Trailer Inspection Information"));
	array_push($footer, array('onlycol'=>" "));
	array_push($footer, array('onlycol'=>"<i>DRIVER MUST CHOCK WHEEL PRIOR TO LOADING  _______(<-- Driver Initials)</i>"));
	array_push($footer, array('onlycol'=>"<i>AND RETURN CHOCK TO HANGER UPON COMPLETION</i>"));
	array_push($footer, array('onlycol'=>"<b>DRIVER:  TRAILER WAS COOLED TO ____ Degrees F ______(<-- Driver Initials)</b>"));
	array_push($footer, array('onlycol'=>"<b>NO Shipper Load & Count:  DRIVER IS RESPONSIBLE FOR CHECKING CARGO AS IT IS LOADED FOR SIGNS OF DAMAGE/LEAKS</b>"));
	array_push($footer, array('onlycol'=>"<b>CLEANLINESS:  ____Good  ____Fair  ____Poor        ODOR:  ____YES  ____NO</b>"));
	array_push($footer, array('onlycol'=>" "));
	array_push($footer, array('onlycol'=>"<b>ACCEPTED FOR LOADING:  ____Yes - Proceed & Load          ____No - Contact Supervisor</b>"));
	array_push($footer, array('onlycol'=>" "));
	array_push($footer, array('onlycol'=>"<b>CHECKER:___________________                         DRIVER:______________________</b>"));
	array_push($footer, array('onlycol'=>"<b>(Signature)                                                       (Signature)</b>"));

	$footer_cols = array('onlycol'=>array('justification'=>'center'));

	$pdf->ezSetY(220);
	$pdf->ezTable($footer, '', '', array('showHeadings'=>0, 
											'shaded'=>0, 
											'showLines'=>0,
											'fontSize'=>8,
											'maxWidth'=>760,
											'minRowSpace'=>-20,
											'rowGap'=>1,
											'colGap'=>1,
											'cols'=>$footer_cols));

	$pdf->line(15,80,760,80);

	$pdf->ezSetY(75);
	$pdf->ezText("TOTAL: ", 10, array('left'=>340));
	$pdf->ezSetY(75);
	$pdf->ezText($total_qty1, 10, array('left'=>390));
	$pdf->ezSetY(75);
	$pdf->ezText($total_qty2, 10, array('left'=>495));
	$pdf->ezSetY(75);
	$pdf->ezText(number_format($total_wt, 2), 10, array('left'=>600));

	$pdf->ezSetY(60);
	$pdf->ezText($comments, 10, $left);

}