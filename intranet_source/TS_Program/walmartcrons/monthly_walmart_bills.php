<?
/*
*	March 2014
*
*	A monthly report (as of writing, that's the 8th of the month)
*	showing bills to Walmart.
*
****************************************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}
	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$month = date('m', mktime(0,0,0,date('m'),date('d') - 8,date('Y')));
	$year = date('Y', mktime(0,0,0,date('m'),date('d') - 8,date('Y')));

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WMEXPRECAPSTRG'";
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
	$mailSubject = str_replace("_0_", $month."/".$year, $mailSubject);
	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $month."/".$year, $body);

	$running_total = 0;
	$grand_total = 0;
	$any_data = false;

	$output = "<table><tr>
						<td>BILLING TYPE</td>
						<td>SERVICE DESCRIPTION</td>
						<td>SERVICE DATE</td>
						<td>SERVICE AMOUNT</td>
						<td>INVOICE NUM</td>
						<td>INVOICE TYPE</td>
					</tr>";
	$output .= "<tr><td colspan=\"6\">Diamond State Port Corporation</td></tr>";
	$output .= "<tr><td colspan=\"6\">Wal-Mart Invoice Summary For the month of ".$month."/".$year."</td></tr>";
	$output .= "<tr><td colspan=\"6\">TS_Program/walmartcrons/monthly_walmart_bills.php</td></tr>";
	$output .= "<tr><td colspan=\"6\">&nbsp;</td></tr>";

	$sql = "SELECT INVOICE_NUM, DECODE(SERVICE_DESCRIPTION, NULL, DECODE(BILLING_TYPE, 'ADTRKLOAD ', 'ADVANCED TRUCK LOADING', 'UNSPECIFIED'), SERVICE_DESCRIPTION) THE_SERV,
				SERVICE_AMOUNT, BILLING_TYPE, SERVICE_STATUS, INVOICE_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') PRINT_DATE
			FROM BILLING
			WHERE CUSTOMER_ID = '3000'
				AND SERVICE_STATUS NOT IN ('DELETED', 'CREDITMEMO', 'DEBITMEMO')
				AND TO_CHAR(SERVICE_DATE, 'MM/YYYY') = '".$month."/".$year."'
			ORDER BY BILLING_TYPE, SERVICE_DESCRIPTION, INVOICE_DATE, INVOICE_NUM";
	$bni_stuff = ociparse($bniconn, $sql);
	ociexecute($bni_stuff);
	if(!ocifetch($bni_stuff)){
		// no bni stuff, skip
	} else {
		$attach_flag = true;
		// set the "first" subtotal heading
		$temp = explode(" ",ociresult($bni_stuff, "THE_SERV"));
		$current_desc = $temp[0];
		
		do {
			// if this is the end of a "subsection", as defined by the first words of the description having changed...
			$check = explode(" ",ociresult($bni_stuff, "THE_SERV"));
			if($current_desc != $check[0]){
				$output .= "<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>Subtotal</td>
									<td>$".number_format($running_total, 2)."</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>";
				$output .= "<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>";
				$running_total = 0;
				$current_desc = $check[0];
			}
			$output .= "<tr>
								<td>".ociresult($bni_stuff, "BILLING_TYPE")."</td>
								<td>".ociresult($bni_stuff, "THE_SERV")."</td>
								<td>".ociresult($bni_stuff, "PRINT_DATE")."</td>
								<td>$".number_format(ociresult($bni_stuff, "SERVICE_AMOUNT"), 2)."</td>
								<td>".ociresult($bni_stuff, "INVOICE_NUM")."</td>
								<td>".ociresult($bni_stuff, "SERVICE_STATUS")."</td>
							</tr>";
			$running_total += ociresult($bni_stuff, "SERVICE_AMOUNT");
			$grand_total += ociresult($bni_stuff, "SERVICE_AMOUNT");

		} while(ocifetch($bni_stuff));

		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>Subtotal</td>
							<td>$".number_format($running_total, 2)."</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$running_total = 0;

	}

	$sql = "SELECT INVOICE_NUM, SERVICE_DESCRIPTION, SERVICE_AMOUNT, BILLING_TYPE, SERVICE_STATUS, INVOICE_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') PRINT_DATE
			FROM RF_BILLING
			WHERE CUSTOMER_ID = '3000'
				AND SERVICE_STATUS != 'DELETED'
				AND TO_CHAR(SERVICE_DATE, 'MM/YYYY') = '".$month."/".$year."'
			ORDER BY BILLING_TYPE, SERVICE_DESCRIPTION, INVOICE_DATE, INVOICE_NUM";
	$rf_stuff = ociparse($rfconn, $sql);
	ociexecute($rf_stuff);
	if(!ocifetch($rf_stuff)){
		// no rf stuff, skip
	} else {
		$attach_flag = true;
		// set the "first" subtotal heading
//		$temp = explode(" ",ociresult($rf_stuff, "SERVICE_DESCRIPTION"));
//		$current_desc = $temp[0];
		
		do {
			$output .= "<tr>
								<td>".ociresult($rf_stuff, "SERVICE_DESCRIPTION")."</td>
								<td>".ociresult($rf_stuff, "BILLING_TYPE")."</td>
								<td>".ociresult($rf_stuff, "PRINT_DATE")."</td>
								<td>$".number_format(ociresult($rf_stuff, "SERVICE_AMOUNT"), 2)."</td>
								<td>".ociresult($rf_stuff, "INVOICE_NUM")."</td>
								<td>".ociresult($rf_stuff, "SERVICE_STATUS")."</td>
							</tr>";
			$running_total += ociresult($rf_stuff, "SERVICE_AMOUNT");
			$grand_total += ociresult($rf_stuff, "SERVICE_AMOUNT");

		} while(ocifetch($rf_stuff));

		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>Subtotal</td>
							<td>$".number_format($running_total, 2)."</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$running_total = 0;
	}

	// BNI credit memos
	$sql = "SELECT INVOICE_NUM, SERVICE_DESCRIPTION, SERVICE_AMOUNT, BILLING_TYPE, SERVICE_STATUS, INVOICE_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') PRINT_DATE
			FROM BILLING
			WHERE CUSTOMER_ID = '3000'
				AND SERVICE_STATUS = 'CREDITMEMO'
				AND TO_CHAR(SERVICE_DATE, 'MM/YYYY') = '".$month."/".$year."'
			ORDER BY BILLING_TYPE, SERVICE_DESCRIPTION, INVOICE_DATE, INVOICE_NUM";
	$bni_stuff = ociparse($bniconn, $sql);
	ociexecute($bni_stuff);
	if(!ocifetch($bni_stuff)){
		// no bni stuff, skip
	} else {
		$attach_flag = true;

		do {
			// if this is the end of a "subsection", as defined by the first words of the description having changed...

			$output .= "<tr>
								<td>".ociresult($bni_stuff, "BILLING_TYPE")."</td>
								<td>".ociresult($bni_stuff, "SERVICE_DESCRIPTION")."</td>
								<td>".ociresult($bni_stuff, "PRINT_DATE")."</td>
								<td>$".number_format(ociresult($bni_stuff, "SERVICE_AMOUNT"), 2)."</td>
								<td>".ociresult($bni_stuff, "INVOICE_NUM")."</td>
								<td>".ociresult($bni_stuff, "SERVICE_STATUS")."</td>
							</tr>";
			$running_total += ociresult($bni_stuff, "SERVICE_AMOUNT");
			$grand_total += ociresult($bni_stuff, "SERVICE_AMOUNT");

		} while(ocifetch($bni_stuff));

		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>Subtotal</td>
							<td>$".number_format($running_total, 2)."</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$running_total = 0;

	}


	// BNI debit memos
	$sql = "SELECT INVOICE_NUM, SERVICE_DESCRIPTION, SERVICE_AMOUNT, BILLING_TYPE, SERVICE_STATUS, INVOICE_DATE, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') PRINT_DATE
			FROM BILLING
			WHERE CUSTOMER_ID = '3000'
				AND SERVICE_STATUS = 'DEBITMEMO'
				AND TO_CHAR(SERVICE_DATE, 'MM/YYYY') = '".$month."/".$year."'
			ORDER BY BILLING_TYPE, SERVICE_DESCRIPTION, INVOICE_DATE, INVOICE_NUM";
	$bni_stuff = ociparse($bniconn, $sql);
	ociexecute($bni_stuff);
	if(!ocifetch($bni_stuff)){
		// no bni stuff, skip
	} else {
		$attach_flag = true;

		do {
			// if this is the end of a "subsection", as defined by the first words of the description having changed...

			$output .= "<tr>
								<td>".ociresult($bni_stuff, "BILLING_TYPE")."</td>
								<td>".ociresult($bni_stuff, "SERVICE_DESCRIPTION")."</td>
								<td>".ociresult($bni_stuff, "PRINT_DATE")."</td>
								<td>$".number_format(ociresult($bni_stuff, "SERVICE_AMOUNT"), 2)."</td>
								<td>".ociresult($bni_stuff, "INVOICE_NUM")."</td>
								<td>".ociresult($bni_stuff, "SERVICE_STATUS")."</td>
							</tr>";
			$running_total += ociresult($bni_stuff, "SERVICE_AMOUNT");
			$grand_total += ociresult($bni_stuff, "SERVICE_AMOUNT");

		} while(ocifetch($bni_stuff));

		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>Subtotal</td>
							<td>$".number_format($running_total, 2)."</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$output .= "<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>";
		$running_total = 0;

	}




	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	// if there was no data at all...
	if($attach_flag == false){
		$output .= "<tr>
						<td colspan=\"6\">No Invoices for the month of ".$month."/".$year."</td>
					</tr>";
	} else {
		$output .= "<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><b>GRAND TOTAL</b></td>
						<td>$".number_format($grand_total, 2)."</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>";
	}

	$output .= "</table>";

	$attach=chunk_split(base64_encode($output));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"BILLING_SUMMARY_".$month."-".$year.".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach;
	$Content.="\r\n";

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
					'MONTHLYCRON',
					SYSDATE,
					'EMAIL',
					'WMEXPRECAPSTRG',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".$email_row['CC']."',
					'".$email_row['BCC']."',
					'".substr($body, 0, 2000)."')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
	}
