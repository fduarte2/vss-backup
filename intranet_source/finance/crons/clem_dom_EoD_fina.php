<?
/*
*	Adam Walter, Oct 2013
*
*	Sends an inventory email to finance... because the cube isnt good enough.
****************************************************************************************/
 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$the_date = date('m/d/Y');

	$output = "<table>
				<tr>
					<td colspan=\"2\"><font size=\"3\" face=\"Verdana\">Daily Clementine Domestic inventory as of ".$the_date."</font></td>
					<td colspan=\"2\">&nbsp;</td>
				</tr>";
	$output .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";

	$sql = "SELECT COMMODITY_CODE, COUNT(DISTINCT PALLET_ID) THE_PLT
			FROM CARGO_TRACKING
			WHERE COMMODITY_CODE = '5606'
				AND ARRIVAL_NUM != '4321'
				AND DATE_RECEIVED IS NOT NULL
				AND QTY_IN_HOUSE >= 10
			GROUP BY COMMODITY_CODE
			ORDER BY COMMODITY_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$output .= "<tr><td colspan=\"2\">None.</td></tr>";
	} else {
		$total_plt = 0;
		do {
			$output .= "<tr>
							<tdfont size=\"3\" face=\"Verdana\">Commodity ".ociresult($stid, "COMMODITY_CODE")." <b>(A)</b></font></td>
							<td>".ociresult($stid, "THE_PLT")."
						</tr>";
			$total_plt += ociresult($stid, "THE_PLT");
		} while(ocifetch($stid));

		$output .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
		$output .= "<tr><td>Max Inventory Allowed <b>(B)</b></td><td>2500</td></tr>";
		$output .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";

		$total_variance = $total_plt - 2500;
		if($total_variance < 0){
			$color="#000000";
//			$total_variance = abs($total_variance);
		} else {
			$color="#000000";
		}

		$output .= "<tr><td>Total Over/Under = (A - B):</td><td><font color=\"".$color."\">".$total_variance."</font></td></tr>";

		$output .= "</table>";
//		echo $output;

	}
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION
			WHERE EMAILID = 'DOMESTICCLEMINV'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}
	$mailheaders .= "Content-Type: text/html\r\n";

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $the_date, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_1_", $output, $body);

	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
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
					'DOMESTICCLEMINV',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
