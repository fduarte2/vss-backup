<?
/*
*	Adam Walter, Oct 2013
*
*	Sends an "EFaxMail" to the canadian-release recipients.
****************************************************************************************/
 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$date = $argv[1];
	if($date == ""){
		$sql_where = " AND gatepass_pdf_date <= (SYSDATE - 30/1440) AND cbp_fax IS NULL";
		$sql_set = " cbp_fax = SYSDATE ";
	} else {
		$sql_where = " AND TO_CHAR(gatepass_pdf_date, 'MM/DD/YYYY') = '".$date."'";
		$sql_set = " cbp_fax = TO_DATE('".$date." 04:11:00', 'MM/DD/YYYY HH24:MI:SS') ";
	}

	// initiate the pdf writer
	include 'class.ezpdf.php';
	$mailSubject = "Diamond State Port Corporation /Port of Wilmington CBP AG Seal Compliance Report";
//	$mailSubject = "THIS IS A TEST Diamond State Port Corporation /Port of Wilmington CBP AG Seal Compliance Report";
//	$mailSubject = "TS LOG for ".date('m/d/Y');
	$body = " ";
//	$body = "THIS IS A TEST OF THE FAX SYSTEM.  PLEASE IGNORE.";

	$mailheaders = "From: pownoreply@port.state.de.us\r\n";
//	$mailheaders .= "Cc: ithomas@port.state.de.us,awalter@port.state.de.us\r\n";
	$mailheaders .= "Cc: ltreut@port.state.de.us,martym@port.state.de.us,draczkowski@port.state.de.us\r\n";
	$mailheaders .= "Bcc: sadu@port.state.de.us,lstewart@port.state.de.us,awalter@port.state.de.us,archive@port.state.de.us\r\n";
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$right = array('justification'=>"right");
	$center = array('justification'=>"center");

//				AND CBP_FAX IS NULL
//				AND GATEPASS_PDF_DATE <= (SYSDATE - 30/1440)
	$sql = "SELECT DISTINCT
				border_crossing
			FROM clr_truck_load_release ctlr
			INNER JOIN clr_truck_main_join ctmj
				ON ctlr.port_id = ctmj.truck_port_id
			INNER JOIN clr_main_data cmd
				ON cmd.clr_key = ctmj.main_clr_key
			LEFT JOIN voyage
				ON TO_CHAR(voyage.lr_num) = cmd.arrival_num
			WHERE
				seal_num IS NOT NULL
				AND seal_time IS NOT NULL
				AND border_crossing != '12'
				$sql_where
				AND cmd.arrival_num != '4321'
				AND voyage.port_of_destination IS NULL";
	$border_data = ociparse($rfconn, $sql);
	ociexecute($border_data);
	if(!ocifetch($border_data)){
		// nothing to do, notify, end script
		mail("Zachary.pillarelli@cbp.dhs.gov,CBPAIDE@dhs.gov", $mailSubject, "For your information only: No trucks were loaded prior to the time this automatic email was generated.", $mailheaders);
		exit;
	} else { do {
		// we send 1 email for each border.
		$sql = "SELECT fax_number
				FROM clr_bordercrossing
				WHERE bordercrossing_id = '".ociresult($border_data, "BORDER_CROSSING")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$mailTO = str_replace("-", "", ociresult($short_term_data, "FAX_NUMBER"))."@rcfax.com,Zachary.pillarelli@cbp.dhs.gov,CBPAIDE@dhs.gov\r\n";
		$mailSubject .= "   ".str_replace("-", "", ociresult($short_term_data, "FAX_NUMBER"));
//		$mailTO = "3024727838@rcfax.com\r\n";
//		$mailTO = "awalter@port.state.de.us,lstewart@port.state.de.us\r\n";

		
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

//		$mailTO_XLS = "Zachary.pillarelli@cbp.dhs.gov\r\n";
//		$mailTO_XLS = "ithomas@port.state.de.us\r\n";
		$mailTO_XLS = $mailTO;

		$Content_XLS="--MIME_BOUNDRY\r\n";
		$Content_XLS.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content_XLS.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content_XLS.="\r\n";
		$Content_XLS.= $body;
		$Content_XLS.="\r\n";

//					AND CBP_FAX IS NULL
//					AND GATEPASS_PDF_DATE <= (SYSDATE - 30/1440)
		$sql = "SELECT DISTINCT
					cmd.arrival_num 
				FROM clr_truck_load_release ctlr
				INNER JOIN clr_truck_main_join ctmj
					ON ctlr.port_id = ctmj.truck_port_id
				INNER JOIN clr_main_data cmd
					ON cmd.clr_key = ctmj.main_clr_key
				LEFT JOIN voyage
					ON TO_CHAR(voyage.lr_num) = cmd.arrival_num
				WHERE
					seal_num IS NOT NULL
					AND seal_time IS NOT NULL
					AND border_crossing = '".ociresult($border_data, "BORDER_CROSSING")."'
					".$sql_where."
					AND cmd.arrival_NUM != '4321'
					AND voyage.port_of_destination IS NULL";
//		echo $sql."\n";
		$arv_data = ociparse($rfconn, $sql);
		ociexecute($arv_data);
		if(!ocifetch($arv_data)){
			// nothing to do, end script
			mail("archive@port.state.de.us\r\n", "ERROR:  CBP-PDF-Fax cannot be sent", "CBP-fax was held up.  Please contact TS to examine the situation.", $mailheaders);
			exit;
		} else { do {
			// we want 1 attachment per ship
			$pdf = new Cezpdf('letter','landscape');
			$pdf->ezSetMargins(10,10,10,10);
			$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
			$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

			$pdf->ezSetY(555);
			$pdf->ezText("<b>Diamond State Port Corporation/Port of Wilmington</b>", 12, $left);
			$pdf->ezSetY(555);
			$pdf->ezText("<b>CBP AG Seal Compliance Report</b>", 12, $right);
			$pdf->ezSetY(545);
			$pdf->ezText("<b>CBP AG Wilmington DE</b>", 12, $left);
			$pdf->ezSetY(545);
			$pdf->ezText("<b>Office: 302-573-6187</b>", 12, $right);
			$pdf->ezSetY(535);
			$pdf->ezText("<b>Fax: 302-573-6050</b>", 12, $right);
			$pdf->ezSetY(525);
			$pdf->ezText("<b>cbpaide@dhs.gov</b>", 12, $right);

			$sql = "SELECT NVL(VESSEL_NAME, 'NONE') THE_VES, LLOYD_NUM FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".ociresult($arv_data, "ARRIVAL_NUM")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$vesname = ociresult($short_term_data, "THE_VES");
			$lloyd_num = ociresult($short_term_data, "LLOYD_NUM");
			$sql = "SELECT TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_DATE FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".ociresult($arv_data, "ARRIVAL_NUM")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$datearv = ociresult($short_term_data, "THE_DATE");

			$pdf->ezSetY(505);
			$pdf->ezText("<b>Vessel/Voyage: ".$vesname."          ".$lloyd_num."         Arrival Date: ".$datearv."</b>", 10, $center);

			$output_XLS = "<table>
							<tr>
								<td>B/L#</td>
								<td>Trailer license plate # and state/province</td>
								<td>Type</td>
								<td>Consignee</td>
								<td>Origin</td>
								<td>T&E# / EAN#</td>
								<td>IN-BOND TRANSIT Seal #</td>
								<td>Cargo</td>
								<td>Cases</td>
								<td>Exit Border Port / Code</td>
								<td>Entered T&E DB</td>
								<td>Initials / Date Sealed</td>
							</tr>";

			$output = array();
			array_push($output, array('bol'=>"<b>B/L#</b>",
										'trailer'=>"<b>Trailer license\nplate #\nand state/\nprovince</b>",
										'type'=>"<b>Type</b>",
										'cons'=>"<b>Consignee</b>",
										'org'=>"<b>Origin</b>",
										'te_num'=>"<b>T&E# /\nEAN#</b>",
										'seal_num'=>"<b>IN-BOND TRANSIT\nSeal #</b>",
										'cargo'=>"<b>Cargo</b>",
										'cases'=>"<b>Cases</b>",
										'border'=>"<b>Exit Border\nPort / Code</b>",
										'te_db'=>"<b>Entered\nT&E DB</b>",
										'cbpa'=>"<b>Initials /\nDate Sealed</b>"));





//DECODE(CARGO_MODE, 'HTH', 'HTH', CONTAINER_NUM) CONT_FIELD,
//						AND CBP_FAX IS NULL
//						AND GATEPASS_PDF_DATE <= (SYSDATE - 30/1440)
			$sql = "SELECT
						cmd.bol_equiv,
						origin_m11,
						port_id,
						shipline,
						DECODE(pickup_type, 'swing', 'transload', pickup_type) the_type,
						consignee,
						commodity,
						NVL(truck_te_num, t_e_num) the_te,
						seal_num,
						qty,
						port_of_entry,
						ams_port_code,
						checkout_init,
						TO_CHAR(gatepass_made_on, 'mm/dd/yyyy') the_pass,
						trail_reef_plate_num,
						trail_reef_plate_state,
						port_id
					FROM clr_truck_load_release ctlr
					INNER JOIN clr_truck_main_join ctmj
						ON ctlr.port_id = ctmj.truck_port_id
					INNER JOIN clr_main_data cmd
						ON cmd.clr_key = ctmj.main_clr_key
					INNER JOIN clr_bordercrossing cb
						ON ctlr.border_crossing = cb.bordercrossing_id
					WHERE
						seal_num IS NOT NULL
						AND seal_time IS NOT NULL
						AND border_crossing = '".ociresult($border_data, "BORDER_CROSSING")."'
						AND cmd.arrival_num = '".ociresult($arv_data, "ARRIVAL_NUM")."'
						$sql_where";
//			echo $sql."\n";
			$main_data = ociparse($rfconn, $sql);
			ociexecute($main_data);
			if(!ocifetch($main_data)){
				// nothing to do, end script
				exit;
			} else {
		
				do {
					$plts = 0;
					$cases = 0;
					$order = GetOrdNum(ociresult($main_data, "PORT_ID"), $plts, $cases, $rfconn);
					$sub_consignee = Get2ndConsignee(ociresult($main_data, "PORT_ID"), $rfconn);
					/*
					if(ociresult($main_data, "CARGO_TYPE") == "CLEMENTINE"){
						$org = "MOROCCO";
					} elseif(ociresult($main_data, "CARGO_TYPE") == "ARG FRUIT") {
						$org = "ARGENTINA";
					} else {
						$org = "CHILE";
					}
					*/
					//substr(ociresult($main_data, "T_AND_E_DISPLAY"), 0, strlen(ociresult($main_data, "T_AND_E_DISPLAY")) - 4)
					array_push($output, array('bol'=>ociresult($main_data, "SHIPLINE")."-".ociresult($main_data, "BOL_EQUIV"),
												'trailer'=>strtoupper(ociresult($main_data, "TRAIL_REEF_PLATE_NUM")." / ".ociresult($main_data, "TRAIL_REEF_PLATE_STATE")),
												'type'=>ociresult($main_data, "THE_TYPE"),
												'cons'=>ociresult($main_data, "CONSIGNEE").$sub_consignee,
												'org'=>ociresult($main_data, "ORIGIN_M11"),
												'te_num'=>ociresult($main_data, "THE_TE"),
												'seal_num'=>ociresult($main_data, "SEAL_NUM"),
												'cargo'=>ociresult($main_data, "COMMODITY"),
												'cases'=>$cases." CS",
												'border'=>ociresult($main_data, "PORT_OF_ENTRY")." - ".ociresult($main_data, "AMS_PORT_CODE"),
												'te_db'=>" ",
												'cbpa'=>ociresult($main_data, "CHECKOUT_INIT")." / ".ociresult($main_data, "THE_PASS")));

					$output_XLS .= "
									<tr>
										<td>".ociresult($main_data, "SHIPLINE")."-".ociresult($main_data, "BOL_EQUIV")."</td>
										<td>".strtoupper(ociresult($main_data, "TRAIL_REEF_PLATE_NUM")." / ".ociresult($main_data, "TRAIL_REEF_PLATE_STATE"))."</td>
										<td>".ociresult($main_data, "THE_TYPE")."</td>
										<td>".ociresult($main_data, "CONSIGNEE").$sub_consignee."</td>
										<td>".ociresult($main_data, "ORIGIN_M11")."</td>
										<td>".ociresult($main_data, "THE_TE")."</td>
										<td>".ociresult($main_data, "SEAL_NUM")."</td>
										<td>".ociresult($main_data, "COMMODITY")."</td>
										<td>".$cases." CS</td>
										<td>".ociresult($main_data, "PORT_OF_ENTRY")." - ".ociresult($main_data, "AMS_PORT_CODE")."</td>
										<td> </td>
										<td>".ociresult($main_data, "CHECKOUT_INIT")." / ".ociresult($main_data, "THE_PASS")."</td>
									</tr>";

				} while (ocifetch($main_data));
	
				$pdf->ezTable($output, '', '', array('showHeadings'=>0, 
														'shaded'=>0, 
														'showLines'=>2,
														'fontSize'=>8,
														'width'=>720,
														'rowGap'=>1,
														'colGap'=>1));
				$pdf->ezText("<b>          * indicates multiple BOL's.  Displayed BOL matches the largest count of pallets.</b>", 10, $left);
				$pdfcode = $pdf->ezOutput();

				$output_XLS .= "</table>";
				$output_XLS .= "<br><br>* indicates multiple BOL's.  Displayed BOL matches the largest count of pallets.";

				$attach1=chunk_split(base64_encode($pdfcode));
				$Content.="--MIME_BOUNDRY\r\n";
				$Content.="Content-Type: application/pdf; name=\"".ociresult($arv_data, "ARRIVAL_NUM").".pdf\"\r\n";
				$Content.="Content-disposition: attachment\r\n";
				$Content.="Content-Transfer-Encoding: base64\r\n";
				$Content.="\r\n";
				$Content.=$attach1;
				$Content.="\r\n";

				$attach2=chunk_split(base64_encode($output_XLS));
				$Content_XLS.="--MIME_BOUNDRY\r\n";
				$Content_XLS.="Content-Type: application/excel; name=\"".ociresult($arv_data, "ARRIVAL_NUM").".xls\"\r\n";
				$Content_XLS.="Content-disposition: attachment\r\n";
				$Content_XLS.="Content-Transfer-Encoding: base64\r\n";
				$Content_XLS.="\r\n";
				$Content_XLS.=$attach2;
				$Content_XLS.="\r\n";

			}
		} while(ocifetch($arv_data)); }

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
//						AND ARRIVAL_NUM != '4321'";
//						AND GATEPASS_PDF_DATE <= (SYSDATE - 30/1440)
//						AND CBP_FAX IS NOT NULL
			$sql = "UPDATE clr_truck_load_release
					SET ".$sql_set."
					WHERE
						seal_time IS NOT NULL
						$sql_where
						AND seal_num IS NOT NULL
						AND border_crossing = '".ociresult($border_data, "BORDER_CROSSING")."'";
			$update_data = ociparse($rfconn, $sql);
			ociexecute($update_data);
//			echo $sql."<br>";

			// if the PDF was successfullty sent, send an XLS copy as well.  this one doesn't trigger any updates, so no "if" clause.
//			mail($mailTO_XLS, $mailSubject, $Content_XLS, $mailheaders);
		}



		} while(ocifetch($border_data));
	}


















/*		$output = "<table border=\"1\" width=\"100%\">
						<tr>
							<td><font size=\"2\"><b>B/L#</b></font></td>
							<td><font size=\"2\"><b>Container</b></font></td>
							<td><font size=\"2\"><b>Consignee</b></font></td>
							<td><font size=\"2\"><b>Origin</b></font></td>
							<td><font size=\"2\"><b>T&E# / EAN#</b></font></td>
							<td><font size=\"2\"><b>CBP Seal #</b></font></td>
							<td><font size=\"2\"><b>Cargo</b></font></td>
							<td><font size=\"2\"><b>Cases / KG</b></font></td>
							<td><font size=\"2\"><b>Exit Border Port</b></font></td>
							<td><font size=\"2\"><b>Entered T&E DB</b></font></td>
							<td><font size=\"2\"><b>CBPA Badge and Date</b></font></td>
						</tr>";*/
/*			$output .= "<tr>
							<td><font size=\"2\">".ociresult($main_data, "BOL")."</font></td>
							<td><font size=\"2\">".ociresult($main_data, "CONTAINER_NUM")."</font></td>
							<td><font size=\"2\">".ociresult($main_data, "CONSIGNEE")."</font></td>
							<td><font size=\"2\">CHILE</font></td>
							<td><font size=\"2\">".ociresult($main_data, "T_AND_E_DISPLAY")."</font></td>
							<td><font size=\"2\">".ociresult($main_data, "SEAL_NUM")."</font></td>
							<td><font size=\"2\">".ociresult($main_data, "COMMODITY")."</font></td>
							<td><font size=\"2\">".ociresult($main_data, "CASES")." CS</font></td>
							<td><font size=\"2\">".ociresult($main_data, "BORDER_CROSSING")."</font></td>
							<td><font size=\"2\"> </font></td>
							<td><font size=\"2\">".ociresult($main_data, "T_E_SIGNAUTH_BY")."///".ociresult($main_data, "THE_PASS")."</font></td>
						</tr>";*/
//		$output .= "</table>";


function GetOrdNum($truck_ID, &$plts, &$cases, $rfconn){
	$sql = "SELECT * 
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE PORT_ID = '".$truck_ID."'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data)){
		$return = "UNKNOWN"; // the CHILEAN logic goes here, for now, nothing
	} else {
		$return = ociresult($main_data, "CLEM_ORDER_NUM");
		$cust = ociresult($main_data, "CUSTOMER_ID");
	}

	if(ociresult($main_data, "PICKUP_TYPE") == "HTH"){
		$sql = "SELECT CMD.QTY, CMD.PLTCOUNT
				FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_TRUCK_MAIN_JOIN CTMJ, CLR_MAIN_DATA CMD
				WHERE CTLR.PORT_ID = '".$truck_ID."'
					AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
					AND CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
		$plts = ociresult($main_data, "PLTCOUNT");
	//	echo "plts: ".$plts."\n";
		$cases = ociresult($main_data, "QTY");
	//	echo "cases: ".$cases."\n";
	} elseif (ociresult($main_data, "PICKUP_TYPE") == "SWING"){
		$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CASES
				FROM CARGO_ACTIVITY CA, CLR_TRUCK_LOAD_RELEASE CTLR, CARGO_TRACKING CT
				WHERE CTLR.PORT_ID = '".$truck_ID."'
					AND CT.CONTAINER_ID = CTLR.CONTAINER_NUM
					AND CA.ARRIVAL_NUM = CTLR.ARRIVAL_NUM
					AND CA.CUSTOMER_ID = CTLR.CUSTOMER_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND SERVICE_CODE = '6'
					AND ACTIVITY_DESCRIPTION IS NULL";
	//	echo $sql."\n";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
		$plts = ociresult($main_data, "THE_PLTS");
	//	echo "plts: ".$plts."\n";
		$cases = ociresult($main_data, "THE_CASES");
	//	echo "cases: ".$cases."\n";
	} else {
		$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CASES
				FROM CARGO_ACTIVITY
				WHERE ORDER_NUM = TRIM('".$return."')
					AND CUSTOMER_ID = '".$cust."'
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
	//	echo $sql."\n";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
		$plts = ociresult($main_data, "THE_PLTS");
	//	echo "plts: ".$plts."\n";
		$cases = ociresult($main_data, "THE_CASES");
	//	echo "cases: ".$cases."\n";
	}

	return $return;

}


function Get2ndConsignee($truck_ID, $rfconn){
	$sql = "SELECT CLEM_ORDER_NUM
			FROM CLR_TRUCK_LOAD_RELEASE
			WHERE PORT_ID = '".$truck_ID."'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);
	if(ociresult($main_data, "CLEM_ORDER_NUM") == ""){
		return "";
	}
	$ordernum = ociresult($main_data, "CLEM_ORDER_NUM");

	$sql = "SELECT CONSIGNEEID
			FROM DC_ORDER
			WHERE ORDERNUM = '".$ordernum."'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data) || ociresult($main_data, "CONSIGNEEID") == ""){
		return "";
	}
	$consignee_num = ociresult($main_data, "CONSIGNEEID");

	$sql = "SELECT SUBSTR(CONSIGNEENAME, 0, 15) THE_NAME
			FROM DC_CONSIGNEE
			WHERE CONSIGNEEID = '".$consignee_num."'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data) || ociresult($main_data, "THE_NAME") == ""){
		return "";
	}

	return " / ".ociresult($main_data, "THE_NAME");
}