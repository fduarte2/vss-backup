<?
/*
*	Adam Walter, Dec 2014.
*
*		Another Walmart reject report.
*************************************************************************/


 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		exit;
	}

	$date = date('m/d/Y');


	$output = "<table border = \"1\">";
	$output .= "<tr>
					<td>&nbsp;</td>
					<td>Date of Report:</td>
					<td>".$date."</td>
					<td colspan=\"18\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td>&nbsp;</td>
					<td>IDC:</td>
					<td>PoW</td>
					<td colspan=\"18\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"21\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td>&nbsp;</td>
					<td>Date of Rejection</td>
					<td>PO Number</td>
					<td>Vessel Name</td>
					<td>Voyage#</td>
					<td>Supplier Name</td>
					<td># of Cases Rejected</td>
					<td># of Pallets Rejected</td>
					<td>Item Description</td>
					<td>Reason for Rejection</td>
					<td>Disposition of Product</td>
					<td>D* Number</td>
					<td>Date Product Moved from IDC Facility</td>
					<td>R* Number</td>
					<td>Date Product Returned from Repack</td>
					<td>#Cases Back from Repack</td>
					<td>#Pallets Back from Repack</td>
					<td>Passed or Rejected 2nd time</td>
					<td>Cases Loss from Repack</td>
					<td>Comments</td>
					<td>Status</td>
				</tr>";

	$sql = "SELECT ORDER_NUM, PO_NUM, PLT_COUNT, CASE_COUNT, EXPECTED_DATE, RET_PLT_COUNT, RET_CASE_COUNT, RET_EXPECTED_DATE,
				TO_CHAR(EXPECTED_DATE, 'MM/DD/YYYY') EXPEC_OUT, TO_CHAR(RET_EXPECTED_DATE, 'MM/DD/YYYY') EXPEC_IN, TO_CHAR(DATE_REJECTION, 'MM/DD/YYYY') REJ_DATE, 
				STATUS, SUPPLIER_NAME, ITEM_DESCRIPTION, REJECT_REASON, DISPOSITION, VESSEL_NAME, REPORT_ROW_NUM
			FROM WM_EXPECTED_REPACK_ORDER
			WHERE DATE_REJECTION >= '01-aug-2015'
			ORDER BY REPORT_ROW_NUM";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$output .= "<tr>
						<td>".ociresult($short_term_data, "REPORT_ROW_NUM")."</td>
						<td>".ociresult($short_term_data, "REJ_DATE")."</td>
						<td>".ociresult($short_term_data, "PO_NUM")."</td>
						<td>".ociresult($short_term_data, "VESSEL_NAME")."</td>
						<td>&nbsp;</td>
						<td>".ociresult($short_term_data, "SUPPLIER_NAME")."</td>
						<td>".ociresult($short_term_data, "CASE_COUNT")."</td>
						<td>".ociresult($short_term_data, "PLT_COUNT")."</td>
						<td>".ociresult($short_term_data, "ITEM_DESCRIPTION")."</td>
						<td>".ociresult($short_term_data, "REJECT_REASON")."</td>
						<td>".ociresult($short_term_data, "DISPOSITION")."</td>
						<td>".ociresult($short_term_data, "ORDER_NUM")."</td>
						<td>".ociresult($short_term_data, "EXPEC_OUT")."</td>
						<td>&nbsp;</td>
						<td>".ociresult($short_term_data, "EXPEC_IN")."</td>
						<td>".ociresult($short_term_data, "RET_CASE_COUNT")."</td>
						<td>".ociresult($short_term_data, "RET_PLT_COUNT")."</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>".ociresult($short_term_data, "STATUS")."</td>
					</tr>";
	}
	$output .= "</table>";

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'WMREJECTSTATUSRPT'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
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
	$mailSubject = str_replace("_0_", $date, $mailSubject);
	$mailSubject = str_replace("_1_", $total_pallets, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $replace_body, $body);
	$xls_attach=chunk_split(base64_encode($output));

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"RejectReport".$date.".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$xls_attach;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";

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
					'CRON',
					SYSDATE,
					'EMAIL',
					'WMREJECTSTATUSRPT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}

?>