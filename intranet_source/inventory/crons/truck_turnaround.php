<?
/*
*	Adam Walter, Nov 2014
*
*	Sends an "EFaxMail" to the canadian-release recipients.
****************************************************************************************/
 
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		exit;
	}

	$date_start = date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
//	$date_start = '10/08/2014';
//	$date_end = date('m/d/Y');

	
	$output_XLS = "<table border=\"1\"><tr><td colspan=\"12\" align=\"center\"><b>TRUCK IN/OUT REPORT FOR ".$date_start."</b></td></tr>";
	$output_XLS .= "<tr><td colspan=\"12\" align=\"center\"><b>NON-WALMART FRUIT</b></td></tr>";
	$output_XLS .= "<tr>
						<td><b>Commodity</b></td>
						<td><b>Trucking Company / ID</b></td>
						<td><b>Driver Name</b></td>
						<td><b>Check In<br>(D)</b></td>
						<td><b>Check Out<br>(E)</b></td>
						<td><b>Dwell Time<br>(E - D)</b></td>
						<td><b>Order #</b></td>
						<td><b>First Scan<br>(H)</b></td>
						<td><b>Last Scan<br>(I)</b></td>
						<td><b>Scan Time<br>(I - H)</b></td>
						<td><b>Wait to Check Out<br>(I - E)</b></td>
						<td><b>Comments/Notes</b></td>
					</tr>";

	// Clementine section
	$sql = "SELECT CTLR.*, TO_CHAR(CHECKIN, 'MM/DD/YYYY HH24:MI') THE_IN, TO_CHAR(CHECKOUT, 'MM/DD/YYYY HH24:MI') THE_OUT, SUBSTR(COMMODITY, 0, 15) THE_COMM 
			FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
			WHERE ((TO_CHAR(CHECKIN, 'MM/DD/YYYY') = '".$date_start."' AND CHECKOUT IS NOT NULL)
					OR 
					(TO_CHAR(CHECKOUT, 'MM/DD/YYYY') = '".$date_start."' AND CHECKIN IS NOT NULL))
				AND CLEM_ORDER_NUM IS NOT NULL
				AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
				AND CTMJ.MAIN_CLR_KEY = CMD.CLR_KEY
			ORDER BY SUBSTR(COMMODITY, 0, 15), CHECKIN";
//	echo $sql."<br>";
//	echo "ORD:".trim(ociresult($main_data, "ORDERNUM")).";";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	while(ocifetch($main_data)){
		GetScanTimes($start, $end, trim(ociresult($main_data, "CLEM_ORDER_NUM")), $rfconn);
		$output_XLS .= "<tr>
							<td>".ociresult($main_data, "THE_COMM")."</td>
							<td>".ociresult($main_data, "TRUCKING_COMPANY")."</td>
							<td>".ociresult($main_data, "DRIVER_NAME")."</td>
							<td>".DispTime(ociresult($main_data, "THE_IN"), $date_start)."</td>
							<td>".DispTime(ociresult($main_data, "THE_OUT"), $date_start)."</td>
							<td>".GetTimeDifference(ociresult($main_data, "THE_IN"), ociresult($main_data, "THE_OUT"), $rfconn)."</td>
							<td>".trim(ociresult($main_data, "CLEM_ORDER_NUM"))."</td>
							<td>".DispTime($start, $date_start)."</td>
							<td>".DispTime($end, $date_start)."</td>
							<td>".GetTimeDifference($start, $end, $rfconn)."</td>
							<td>".GetTimeDifference($end, ociresult($main_data, "THE_OUT"), $rfconn)."</td>
							<td>&nbsp;</td>
						</tr>";
	}






	// Walmart section
	$output_XLS .= "<tr></tr><tr></tr><tr></tr><tr><td colspan=\"12\" align=\"center\"><b>WALMART</b></td></tr>";
	$output_XLS .= "<tr>
						<td><b>Commodity</b></td>
						<td><b>Trucking Company / ID</b></td>
						<td><b>Driver Name</b></td>
						<td><b>Check In<br>(D)</b></td>
						<td><b>Check Out<br>(E)</b></td>
						<td><b>Dwell Time<br>(E - D)</b></td>
						<td><b>Order #</b></td>
						<td><b>First Scan<br>(H)</b></td>
						<td><b>Last Scan<br>(I)</b></td>
						<td><b>Scan Time<br>(I - H)</b></td>
						<td><b>Wait to Check Out<br>(I - E)</b></td>
						<td><b>Comments/Notes</b></td>
					</tr>";
	$sql = "SELECT WLH.*, TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY HH24:MI') THE_IN, TO_CHAR(TRUCK_CHECKOUT_TIME, 'MM/DD/YYYY HH24:MI') THE_OUT, WLD.DCPO_NUM
			FROM WDI_LOAD_DCPO WLD, WDI_LOAD_HEADER WLH
			WHERE WLD.LOAD_NUM = WLH.LOAD_NUM
				AND
				((TO_CHAR(TRUCK_CHECKIN_TIME, 'MM/DD/YYYY') = '".$date_start."' AND TRUCK_CHECKOUT_TIME IS NOT NULL)
					OR 
					(TO_CHAR(TRUCK_CHECKOUT_TIME, 'MM/DD/YYYY') = '".$date_start."' AND TRUCK_CHECKIN_TIME IS NOT NULL))
			ORDER BY TRUCK_CHECKIN_TIME";
//	echo $sql;
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	while(ocifetch($main_data)){
		GetScanTimes($start, $end, ociresult($main_data, "DCPO_NUM"), $rfconn);
		$output_XLS .= "<tr>
							<td>CHILEAN</td>
							<td>".ociresult($main_data, "TRUCKING_COMPANY")."</td>
							<td>".ociresult($main_data, "DRIVER_NAME")."</td>
							<td>".DispTime(ociresult($main_data, "THE_IN"), $date_start)."</td>
							<td>".DispTime(ociresult($main_data, "THE_OUT"), $date_start)."</td>
							<td>".GetTimeDifference(ociresult($main_data, "THE_IN"), ociresult($main_data, "THE_OUT"), $rfconn)."</td>
							<td>".ociresult($main_data, "DCPO_NUM")."</td>
							<td>".DispTime($start, $date_start)."</td>
							<td>".DispTime($end, $date_start)."</td>
							<td>".GetTimeDifference($start, $end, $rfconn)."</td>
							<td>".GetTimeDifference($end, ociresult($main_data, "THE_OUT"), $rfconn)."</td>
							<td>&nbsp;</td>
						</tr>";
	}





	// everything else section
	$output_XLS .= "<tr></tr><tr></tr><tr></tr><tr><td colspan=\"12\" align=\"center\"><b>OTHER</b></td></tr>";
/*	$output_XLS .= "<tr>
						<td><b>Commodity</b></td>
						<td><b>Trucking Company / ID</b></td>
						<td><b>Driver Name</b></td>
						<td><b>Check In<br>(D)</b></td>
						<td><b>Check Out<br>(E)</b></td>
						<td><b>Dwell Time<br>(E - D)</b></td>
						<td><b>Order #</b></td>
						<td><b>First Scan<br>(H)</b></td>
						<td><b>Last Scan<br>(I)</b></td>
						<td><b>Scan Time<br>(I - H)</b></td>
						<td><b>Wait to Check Out<br>(I - E)</b></td>
					</tr>"; */
	$output_XLS .= "<tr>
						<td><b>Commodity</b></td>
						<td><b>Trucking Company / ID</b></td>
						<td><b>Driver Name</b></td>
						<td><b>Check In<br>(D)</b></td>
						<td><b>Check Out<br>(E)</b></td>
						<td><b>Dwell Time<br>(E - D)</b></td>
						<td colspan=\"5\">&nbsp;</td>
						<td><b>Comments/Notes</b></td>
					</tr>"; 
	$sql = "SELECT TTL.*, TO_CHAR(TIME_IN, 'MM/DD/YYYY HH24:MI') THE_IN, TO_CHAR(TIME_OUT, 'MM/DD/YYYY HH24:MI') THE_OUT, COMMODITY_GROUP_NAME
			FROM TLS_TRUCK_LOG TTL, TLS_COMMODITY_GROUP TCG, TLS_COMMODITY_PROFILE TCP
			WHERE ((TO_CHAR(TIME_IN, 'MM/DD/YYYY') = '".$date_start."' AND TIME_OUT IS NOT NULL)
					OR 
					(TO_CHAR(TIME_OUT, 'MM/DD/YYYY') = '".$date_start."' AND TIME_IN IS NOT NULL))
				AND TTL.COMMODITY_CODE = TCP.COMMODITY_CODE
				AND TCP.COMMODITY_GROUP = TCG.COMMODITY_GROUP_ID
			ORDER BY COMMODITY_GROUP_NAME, TIME_IN";
	$main_data = ociparse($bniconn, $sql);
	ociexecute($main_data);
	while(ocifetch($main_data)){
		GetScanTimes($start, $end, ociresult($main_data, "BOL"), $rfconn);
/*		$output_XLS .= "<tr>
							<td>".ociresult($main_data, "COMMODITY_NAME")."</td>
							<td>".ociresult($main_data, "TRUCKING_COMPANY")."</td>
							<td>".ociresult($main_data, "DRIVER_NAME")."</td>
							<td>".DispTime(ociresult($main_data, "THE_IN"), $date_start)."</td>
							<td>".DispTime(ociresult($main_data, "THE_OUT"), $date_start)."</td>
							<td>".GetTimeDifference(ociresult($main_data, "THE_IN"), ociresult($main_data, "THE_OUT"), $rfconn)."</td>
							<td>".ociresult($main_data, "BOL")."</td>
							<td>".DispTime($start, $date_start)."</td>
							<td>".DispTime($end, $date_start)."</td>
							<td>".GetTimeDifference($start, $end, $rfconn)."</td>
							<td>".GetTimeDifference($end, ociresult($main_data, "THE_OUT"), $rfconn)."</td>
						</tr>";*/
		$output_XLS .= "<tr>
							<td>".ociresult($main_data, "COMMODITY_GROUP_NAME")."</td>
							<td>".ociresult($main_data, "TRUCKING_COMPANY")."</td>
							<td>".ociresult($main_data, "DRIVER_NAME")."</td>
							<td>".DispTime(ociresult($main_data, "THE_IN"), $date_start)."</td>
							<td>".DispTime(ociresult($main_data, "THE_OUT"), $date_start)."</td>
							<td>".GetTimeDifference(ociresult($main_data, "THE_IN"), ociresult($main_data, "THE_OUT"), $rfconn)."</td>
							<td colspan=\"5\">&nbsp;</td>
							<td>".ociresult($main_data, "COMMENTS")."</td>
						</tr>";
	}


	$output_XLS .= "</table>";

//	echo $output_XLS;


	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'TRUCKTIMEREPORT'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
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
//	$mailSubject = str_replace("_0_", $vesname, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $date_start, $body);

	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	$attach1=chunk_split(base64_encode($output_XLS));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"TruckTimes".date('m/d/Y').".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach1;
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
					'DAILYCRON',
					SYSDATE,
					'EMAIL',
					'TRUCKTIMEREPORT',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}



















function GetScanTimes(&$start, &$end, $order, $rfconn){
	$sql = "SELECT TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_END, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_START
			FROM CARGO_ACTIVITY
			WHERE ORDER_NUM = '".$order."'
				AND SERVICE_CODE = '6'
				AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$start = ociresult($short_term_data, "THE_START");
	$end = ociresult($short_term_data, "THE_END");
}

function GetDCCompany($transporterID, $rfconn){
	$sql = "SELECT * FROM WHAT?";
}

function GetTimeDifference($start, $end, $rfconn){
	$sql = "SELECT ROUND((TO_DATE('".$end."', 'MM/DD/YYYY HH24:MI') - TO_DATE('".$start."', 'MM/DD/YYYY HH24:MI')) * 1440) THE_MINUTES
			FROM DUAL";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$minutes = ociresult($short_term_data, "THE_MINUTES");

	$hour = floor($minutes / 60);
	$minutes = $minutes % 60;

	return $hour."h  ".$minutes."m";
}

function DispTime($date_disp, $date_compare){
	$temp = explode(" ", $date_disp);

	if($temp[0] === $date_compare){
		return $temp[1];
	} else {
		return $date_disp;
	}
}