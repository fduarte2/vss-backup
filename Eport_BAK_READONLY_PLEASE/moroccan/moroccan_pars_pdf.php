<?
	include 'class.ezpdf.php';

	$order = "DC401";
	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	$pdf = new Cezpdf('letter','landscape');
	$pdf = new Cezpdf('letter');
	$pdf->ezSetMargins(20,20,65,65);
//	$pdf->ezStartPageNumbers(700,10,8,'right');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT CARRIERNAME, DRIVERNAME, TO_CHAR(PARSETABORDER, 'MM/DD/YYYY') BORDER_DATE, TO_CHAR(PARSETABORDER, 'HH24:MI') BORDER_TIME,
				TRAILERNUM, TRUCKTAG, PARSDRIVERPHONEMOBILE, PARSCARRIERDISPATCHPHONE, PARSPORTOFENTRYNUM, CUSTOMERPO, PARSBARCODE
			FROM MOR_ORDER MO, MOR_TRANSPORTER MT
			WHERE MO.ORDERNUM = '".$order."'
				AND MO.CUST = '".$cust."'
				AND MO.TRANSPORTERID = MT.TRANSPORTID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$pdf->ezText("<b>Order ".$order." Does not exist for customer ".$cust."</b>", 14, $center);
	} else {

		$carriername = ociresult($stid, "CARRIERNAME");
		$drivername = ociresult($stid, "DRIVERNAME");
		$pars_date = ociresult($stid, "BORDER_DATE");
		$pars_time = ociresult($stid, "BORDER_TIME");
		$trailer = ociresult($stid, "TRAILERNUM");
		$trucknum = ociresult($stid, "TRUCKTAG");
		$driverphone = ociresult($stid, "PARSDRIVERPHONEMOBILE");
		$carrierphone = ociresult($stid, "PARSCARRIERDISPATCHPHONE");
		$pars_port_num = ociresult($stid, "PARSPORTOFENTRYNUM");
		$pars_num = ociresult($stid, "PARSBARCODE");
		$po_num = ociresult($stid, "CUSTOMERPO");


		$pdf->ezSetY(765);
		$pdf->ezText("<b>Dominion Citrus Limited PARS Notification</b>", 14, $center);
		$pdf->ezSetY(745);
		$pdf->ezText("<b>Carrier/driver must fill out this form and fax with all documentation, including Bill of</b>", 12, $center);
		$pdf->ezSetY(730);
		$pdf->ezText("<b>Lading at least 3 hours prior to arrival to ZINN @ fax# (905) 212-9906</b>", 12, $center);
		$pdf->ezSetY(715);
		$pdf->ezText("To confirm the status of your fax, please call Zinn Customers Brokers at 877 212-9901 at least 60 min after faxing", 8, $center);
		$pdf->ezSetY(705);
		$pdf->ezText("All documentation, including Bill of Lading, faxed with cover sheet MUST be submitted to Customers upon arrival", 8, $center);

		$top_table = array();
		array_push($top_table, array('desc'=>"<b>Carrier Name</b>",
									'value'=>$carriername));
		array_push($top_table, array('desc'=>"<b>Driver Name</b>",
									'value'=>$drivername));
		array_push($top_table, array('desc'=>"<b>Driver Cell</b>",
									'value'=>$driverphone));
		array_push($top_table, array('desc'=>"<b>Carrier Dispatch Phone</b>",
									'value'=>$carrierphone));
		$pdf->ezSetY(685);
		$pdf->ezTable($top_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>500,
													'fontSize'=>10,
													'colGap'=>2));

		$pdf->ezSetY(610);
		$pdf->ezText("<b>ETA at Border</b>", 10, $left);

		$border_table = array();
		array_push($border_table, array('datedesc'=>"<b>Date (mm/dd/yyyy)</b>",
									'datevalue'=>$pars_date,
									'timedesc'=>"<b>Time (hh:mm)</b>",
									'timevalue'=>$pars_time));
		$pdf->ezTable($border_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>500,
													'fontSize'=>10,
													'colGap'=>2));

		$truck_table = array();
		array_push($truck_table, array('desc'=>"<b>Truck#</b>",
									'value'=>$trucknum));
		array_push($truck_table, array('desc'=>"<b>Trailer#</b>",
									'value'=>$trailer));
		$sql = "SELECT COUNT(*) THE_PLT, SUM(QTY_CHANGE) THE_CTN
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = '".$order."'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
		$c_a = ociparse($conn, $sql);
		ociexecute($c_a);
		ocifetch($c_a);
		$plts = ociresult($c_a, "THE_PLT");
		$ctns = ociresult($c_a, "THE_CTN");
		array_push($truck_table, array('desc'=>"<b># of Cartons</b>",
									'value'=>$ctns));
		array_push($truck_table, array('desc'=>"<b># of pallets</b>",
									'value'=>$plts));
		$pdf->ezSetY(570);
		$pdf->ezTable($truck_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>250,
													'fontSize'=>10,
													'xpos'=>'left',
													'xOrientation'=>'left',
													'colGap'=>2));


		$pdf->ezSetY(570);
		$pdf->ezText("<b>PARS# ".$pars_num."</b>", 10, array('aleft'=>350,'justification'=>'center'));
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/c39hdw2-barcode.afm');
		$pdf->ezText("*".$pars_num."*", 36, array('aleft'=>350,'justification'=>'center'));
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');












		$pdf->ezSetY(500);
		$pdf->ezText("<b>Purchase Order(s)</b>", 10, $left);
		$pdf->ezText("(All PO's must be listed - Note Format: 012345-67)", 10, $left);

		$po_table = array();
		array_push($po_table, array('desc'=>$po_num,
									'value'=>$order));
		array_push($po_table, array('desc'=>"",
									'value'=>""));
		array_push($po_table, array('desc'=>"",
									'value'=>""));
		array_push($po_table, array('desc'=>"",
									'value'=>""));
		array_push($po_table, array('desc'=>"",
									'value'=>""));
		$pdf->ezTable($po_table, '', '', array('showHeadings'=>0, 
													'shaded'=>0, 
													'showLines'=>2,
													'fontSize'=>8,
													'width'=>250,
													'fontSize'=>10,
													'xpos'=>'left',
													'xOrientation'=>'left',
													'colGap'=>2));

		$pdf->ezSetDy(-15);
		$pdf->ezText("<b>Additional Produce Requirements</b>", 10, $left);

		$pdf->ezSetY(500);
		$pdf->ezText("<b>Port of Entry#</b>", 8, array('aleft'=>350));
		$pdf->ezText("Please Place the 3-digit port code below.", 8, array('aleft'=>350));
		$pdf->ezText("Please see page 2 for a complete list of port codes.", 8, array('aleft'=>350));
		$pdf->ezText("<b>".$pars_port_num."</b>", 10, array('aleft'=>350));
		$pdf->ezText("If you arrive at a different port than indicated, you", 8, array('aleft'=>350));
		$pdf->ezText("must contact Livingston at the number listed above", 8, array('aleft'=>350));
		$pdf->ezText("Prior to submitting documents to customs", 8, array('aleft'=>350));

		$pdf->ezSetY(350);
		$pdf->ezText("If the shipment contains these commodities, the appropriate documents <b>MUST</b> be attached to this fax to permit clearance at the border.", 8, $left);
		$pdf->ezText("<b>Apples -> </b> USDA Inspection", 8, array('aleft'=>100));
		$pdf->ezText("<b>Onions -> </b> USDA Inspection", 8, array('aleft'=>100));
		$pdf->ezText("<b>Potatoes -> </b> USDA Inspection + Phytosanitary Cert. or Sprout Inhibition Treatment Proof", 8, array('aleft'=>100));
		$pdf->ezText("<b>NOTE:  Failed PARS Procedures</b>", 10, $left);
		$pdf->ezText("The original (PARS) CCN must be associated with the shipment by writing or typing of the original PARS CCN on", 8, $left);
		$pdf->ezText("a blank Cargo Control Document (CCD - A8A)", 8, $left);
	}

	include("redirect_pdf.php");

