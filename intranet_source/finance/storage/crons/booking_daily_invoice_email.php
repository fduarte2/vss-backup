<?
/*
*	Oct 2015
*
*	Daily Email regarding status of booking paper (for billing)
*
****************************************************************************************/

	include('print_rf_storage_func.php');

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$date = date('m/d/Y');
//	$date = date('m/d/Y', mktime(0,0,0,date('m'),date('d')-7,date('Y')));

//						<td align=\"right\">Days in Storage</td>
	$output = "<table><tr>
						<td align=\"right\">customer</td>
						<td align=\"right\">invoice</td>
						<td align=\"right\">booking#</td>
						<td align=\"right\">PO#</td>
						<td align=\"right\">BOL#</td>
						<td align=\"right\">arrival # rail or truck</td>
						<td align=\"right\">arrival date</td>
						<td align=\"right\">free time expiration</td>
						<td align=\"right\">billable storage days to date</td>
						<td align=\"right\">Days being invoice</td>
						<td align=\"right\">NT</td>
						<td align=\"right\">rate/NT</td>
						<td align=\"right\">current storage charge</td>
					</tr>";

	$lines = 0;

	$invoice_array = array();

	// all "booking" bills are redirected to customer 461, which means I can't join them to CT on receiver_ID.  Hopefully, it won't matter.
	// luckily, all "booking" paper has, and ONLY booking paper has, entries in the B_A_D table, so that's my "hard exclusion" to get only Booking data.
	// if that fails (like in the future if other paper types show up), I might look in CT.REMARK, but since I don't yet know what these
	// hypothetical "other" types have as idintifying aspects, I'll hold that off.
				//FLOOR(SYSDATE - DATE_RECEIVED) DAYS_IN_STOR, 
	$sql = "SELECT CT.RECEIVER_ID, CT.PALLET_ID, BAD.BOOKING_NUM, BAD.ORDER_NUM, BAD.BOL, RB.INVOICE_NUM, CT.ARRIVAL_NUM, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') DATE_REC, TO_CHAR(FREE_TIME_END, 'MM/DD/YYYY') FREE_END,
				FLOOR(RBD.SERVICE_STOP - FREE_TIME_END) + 1 BILLABLE_DAYS, FLOOR(RB.SERVICE_STOP - RB.SERVICE_START) + 1 INVOICE_DAYS, RBD.SERVICE_RATE, RBD.SERVICE_QTY, RBD.SERVICE_AMOUNT
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, RF_BILLING RB, RF_BILLING_DETAIL RBD
			WHERE CT.PALLET_ID = BAD.PALLET_ID
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.PALLET_ID = RBD.PALLET_ID
				AND CT.ARRIVAL_NUM = RBD.ARRIVAL_NUM
				AND RBD.SUM_BILL_NUM = RB.BILLING_NUM
				AND RB.SERVICE_STATUS = 'INVOICED'
				AND TO_CHAR(RB.INVOICE_DATE, 'MM/DD/YYYY') = '".$date."'
			ORDER BY RECEIVER_ID, INVOICE_NUM, PALLET_ID";
	$pallets = ociparse($rfconn, $sql);
	ociexecute($pallets);
	while(ocifetch($pallets)){
//						<td align=\"right\">".ociresult($pallets, "DAYS_IN_STOR")."</td>
		$output .= "<tr>
						<td align=\"right\">".ociresult($pallets, "RECEIVER_ID")."</td>
						<td align=\"right\">".ociresult($pallets, "INVOICE_NUM")."</td>
						<td align=\"right\">".ociresult($pallets, "BOOKING_NUM")."</td>
						<td align=\"right\">".ociresult($pallets, "ORDER_NUM")."</td>
						<td align=\"right\">".ociresult($pallets, "BOL")."</td>
						<td align=\"right\">".ociresult($pallets, "ARRIVAL_NUM")."</td>
						<td align=\"right\">".ociresult($pallets, "DATE_REC")."</td>
						<td align=\"right\">".ociresult($pallets, "FREE_END")."</td>
						<td align=\"right\">".ociresult($pallets, "BILLABLE_DAYS")."</td>
						<td align=\"right\">".ociresult($pallets, "INVOICE_DAYS")."</td>
						<td align=\"right\">".ociresult($pallets, "SERVICE_QTY")."</td>
						<td align=\"right\">$".number_format(ociresult($pallets, "SERVICE_RATE"), 2)."</td>
						<td align=\"right\">$".number_format(ociresult($pallets, "SERVICE_AMOUNT"), 2)."</td>
					</tr>";

		array_push($invoice_array, ociresult($pallets, "INVOICE_NUM"));
	}
	
	$invoice_array = array_unique($invoice_array);
	sort($invoice_array, SORT_NUMERIC);

	$output .= "</table>";

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'BOOKINGCARGOSTORAGE'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = ociresult($email, "SUBJECT");
	if(sizeof($invoice_array) == 0){
		$body = "no Invoices created on ".$date;
	} else {
		$body = ociresult($email, "NARRATIVE");
	}

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$attach=chunk_split(base64_encode($output));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"BOOKING_STORAGE.xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach;
	$Content.="\r\n";

//	$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$ora_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
/*	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("Please try later!");
		exit;
	}*/

	if(sizeof($invoice_array) > 0){
		include 'class.ezpdf.php';
		$pdf = new Cezpdf('letter');
	//	$pdf = new Cezpdf('letter','landscape');

		$pdf->ezSetMargins(20,40,20,20);
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$pdf->ezStartPageNumbers(720, 32, 9, '','',1);
	//	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

		GeneratePDF(&$pdf, min($invoice_array), max($invoice_array), "INVOICE", $invoice_array, "POW_logo_in_black.jpg", $rfconn);
		$pdfcode = $pdf->ezOutput();

		$attach=chunk_split(base64_encode($pdfcode));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"INVOICES.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					DATE_JOB_COMPLETED,
					COMPLETION_STATUS,
					JOB_EMAIL_TO,
					JOB_EMAIL_CC,
					JOB_EMAIL_BCC,
					JOB_BODY)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'BOOKINGCARGOSTORAGE',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}
