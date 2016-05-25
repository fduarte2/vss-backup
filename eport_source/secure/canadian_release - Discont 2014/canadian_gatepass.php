<?
/*
*	Adam Walter, Sep 2013
*
*	Make a PDF of a canadian-released chilean vessel gatepass
****************************************************************************************/

	include("can_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$vessel = $HTTP_GET_VARS['vessel'];
	$cont = $HTTP_GET_VARS['cont'];
	$bol = $HTTP_GET_VARS['bol'];

	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

	// initiate the pdf writer
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter');
	$pdf->ezSetMargins(10,10,10,10);
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT TRUCK_COMPANY, REEFER_PLATE_STATE, REEFER_PLATE_NUM, CONTAINER_NUM, BORDER_CROSSING, SEAL_NUM, GATEPASS_NUM, PORT_OF_ENTRY, AMS_PORT_CODE, T_E_SIGNAUTH_BY,
				TO_CHAR(GATEPASS_ISSUED, 'MM/DD/YYYY') GATEPASS_DATE, TO_CHAR(GATEPASS_ISSUED, 'HH:MI:SS AM') GATEPASS_TIME, CAN_PORT_OF_ENTRY, PARSPORTOFENTRYNUM, CONSIGNEE
			FROM CANADIAN_LOAD_RELEASE CLR, CANADIAN_BORDERCROSSING CB
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'
				AND CLR.BORDER_CROSSING = CB.BORDERCROSSING_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
		

	$pdf->ezSetY(715);
	$pdf->ezText("<b>PORT OF WILMINGTON</b>", 12, $center);
	$pdf->ezSetY(705);
	$pdf->ezText("<b>GATE PASS</b>", 12, $center);
//	$pdf->ezSetY(705);
//	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(675);
	$pdf->ezText("Company:  ".ociresult($short_term_data, "CONSIGNEE"), 10, array('aleft'=>205));
	$pdf->ezSetY(675);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(655);
	$pdf->ezText("Trucking Company:  ".ociresult($short_term_data, "TRUCK_COMPANY"), 10, array('aleft'=>205));
	$pdf->ezSetY(655);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(635);
	$pdf->ezText("License #:  ".ociresult($short_term_data, "REEFER_PLATE_STATE")."  -  ".ociresult($short_term_data, "REEFER_PLATE_NUM"), 10, array('aleft'=>205));
	$pdf->ezSetY(635);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(615);
	$pdf->ezText("Container #:  ".ociresult($short_term_data, "CONTAINER_NUM"), 10, array('aleft'=>205));
	$pdf->ezSetY(615);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(575);
	$pdf->ezText("Date:  ".ociresult($short_term_data, "GATEPASS_DATE"), 10, array('aleft'=>205));
	$pdf->ezSetY(575);
	$pdf->ezText("Time:  ".ociresult($short_term_data, "GATEPASS_TIME"), 10, array('aleft'=>295));
	$pdf->ezSetY(575);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(535);
	$pdf->ezText("Authorized Signature", 10, array('aleft'=>205));
	$pdf->ezSetY(545);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(465);
	$pdf->ezText("<b>NOTICE TO TRUCK DRIVER</b>", 12, $center);
	$pdf->ezSetY(435);
	$pdf->ezText("<b>This Pass Must be Signed and</b>", 12, $center);
	$pdf->ezSetY(425);
	$pdf->ezText("<b>Surrendered at the Gate House</b>", 12, $center);
	$pdf->ezSetY(405);
	$pdf->ezText("<b>Please Observe All Safety</b>", 12, $center);
	$pdf->ezSetY(395);
	$pdf->ezText("<b>Rules and Regulations</b>", 12, $center);

	$pdf->ezSetY(365);
	$pdf->ezText("Order #:  ".$bol, 10, array('aleft'=>205));
	$pdf->ezSetY(365);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(345);
	$pdf->ezText("Pass #:  ".ociresult($short_term_data, "GATEPASS_NUM"), 10, array('aleft'=>205));
	$pdf->ezSetY(345);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(325);
	$pdf->ezText("Seal #:  ".ociresult($short_term_data, "SEAL_NUM"), 10, array('aleft'=>205));
	$pdf->ezSetY(325);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(305);
	$pdf->ezText("Verified By:  ".ociresult($short_term_data, "T_E_SIGNAUTH_BY"), 10, array('aleft'=>205));
	$pdf->ezSetY(305);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$pdf->ezSetY(275);
	$pdf->ezText("<b>BORDER CROSSING:</b>", 10, array('aleft'=>205));
	$pdf->ezSetY(255);
	$pdf->ezText("USA-Side:  ".ociresult($short_term_data, "PORT_OF_ENTRY")." / ".ociresult($short_term_data, "AMS_PORT_CODE"), 10, array('aleft'=>205));
	$pdf->ezSetY(255);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));
	$pdf->ezSetY(235);
	$pdf->ezText("Canada-Side:  ".ociresult($short_term_data, "CAN_PORT_OF_ENTRY")." / ".ociresult($short_term_data, "PARSPORTOFENTRYNUM"), 10, array('aleft'=>205));
	$pdf->ezSetY(235);
	$pdf->ezText("<b>_________________________________________</b>", 10, array('aleft'=>205));

	$sql = "UPDATE CANADIAN_LOAD_RELEASE
			SET GATEPASS_PDF_DATE = SYSDATE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'
				AND GATEPASS_PDF_DATE IS NULL";
//	echo $sql;
	$update_data = ociparse($rfconn, $sql);
	ociexecute($update_data);


	include("redirect_pdf.php");
