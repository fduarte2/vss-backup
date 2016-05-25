<?
/*
*	Mar 2014.
*
*	email, tied into Canadian load release, that sends emails whenever
*	a ships is "done with it's swingloads"
*****************************************************************************************/

	include("pow_session.php");

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'LASTSWINGLOAD'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}
	$mailheaders .= "Content-Type: text/html\r\n";

//	$mailSubject = ociresult($email, "SUBJECT");
//	$mailSubject = str_replace("_0_", $order_num, $mailSubject);
//	$body = ociresult($email, "NARRATIVE");
	
	
	
	$sql = "SELECT DISTINCT CMD.ARRIVAL_NUM
			FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
			WHERE CARGO_MODE = 'SWING'
				AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
				AND CMD.CLR_KEY = CTMJ.MAIN_CLR_KEY
				AND TO_CHAR(GATEPASS_PDF_DATE, 'MM/DD/YYYY') = '".date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')))."'
				AND CMD.ARRIVAL_NUM NOT IN (SELECT CMD.ARRIVAL_NUM 
										FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
										WHERE GATEPASS_PDF_DATE IS NULL
											AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
											AND CMD.CLR_KEY = CTMJ.MAIN_CLR_KEY
											AND CARGO_MODE = 'SWING'
										UNION
										SELECT '999999' FROM DUAL)
			ORDER BY CMD.ARRIVAL_NUM";
//	echo $sql."\n";
	$vessels = ociparse($rfconn, $sql);
	ociexecute($vessels);
	while(ocifetch($vessels)){
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
				WHERE LR_NUM = '".ociresult($vessels, "ARRIVAL_NUM")."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$ves_name = ociresult($vessels, "ARRIVAL_NUM")." - ".ociresult($short_term_data, "VESSEL_NAME");

		$output = "<table border=1>
						<tr>
							<td>Customer</td>
							<td>Container</td>
							<td>BoL</td>
							<td>Pallets</td>
							<td>Cases</td>
							<td>Gate Pass Date/Time</td>
							<td>Order#</td>
							<td>Scanned Pallets</td>
							<td>Scanned Cases</td>
						</tr>";
		$sql = "SELECT CONSIGNEE, CMD.CONTAINER_NUM, CMD.BOL_EQUIV, TO_CHAR(GATEPASS_PDF_DATE, 'MM/DD/YYYY HH24:MI:SS') THE_GATEPASS, PLTCOUNT, QTY
				FROM CLR_TRUCK_LOAD_RELEASE CTLR, CLR_MAIN_DATA CMD, CLR_TRUCK_MAIN_JOIN CTMJ
				WHERE CARGO_MODE = 'SWING'
					AND CTLR.PORT_ID = CTMJ.TRUCK_PORT_ID
					AND CMD.CLR_KEY = CTMJ.MAIN_CLR_KEY
					AND GATEPASS_PDF_DATE IS NOT NULL
					AND CMD.ARRIVAL_NUM = '".ociresult($vessels, "ARRIVAL_NUM")."'
				ORDER BY CONSIGNEE, CMD.CONTAINER_NUM, CMD.BOL_EQUIV";
		$lines = ociparse($rfconn, $sql);
		ociexecute($lines);
		while(ocifetch($lines)){
			$sql = "SELECT CA.ORDER_NUM, COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
					WHERE CT.PALLET_ID = CA.PALLET_ID
						AND CT.RECEIVER_ID = CA.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
						AND CA.SERVICE_CODE = '6'
						AND CA.ACTIVITY_DESCRIPTION IS NULL
						AND CT.CONTAINER_ID = '".ociresult($lines, "CONTAINER_NUM")."'
						AND CT.ARRIVAL_NUM = '".ociresult($vessels, "ARRIVAL_NUM")."'
					GROUP BY CA.ORDER_NUM
					ORDER BY CA.ORDER_NUM";
			$order_plts = ociparse($rfconn, $sql);
			ociexecute($order_plts);
			if(!ocifetch($order_plts)){
				$output .= "<tr>
								<td>".ociresult($lines, "CONSIGNEE")."</td>
								<td>".ociresult($lines, "CONTAINER_NUM")."</td>
								<td>".ociresult($lines, "BOL_EQUIV")."</td>
								<td>".ociresult($lines, "PLTCOUNT")."</td>
								<td>".ociresult($lines, "QTY")."</td>
								<td>".ociresult($lines, "THE_GATEPASS")."</td>
								<td colspan=\"3\">No Scanned Orders Found</td>
							</tr>";
			} else {
				$output .= "<tr>
								<td>".ociresult($lines, "CONSIGNEE")."</td>
								<td>".ociresult($lines, "CONTAINER_NUM")."</td>
								<td>".ociresult($lines, "BOL_EQUIV")."</td>
								<td>".ociresult($lines, "PLTCOUNT")."</td>
								<td>".ociresult($lines, "QTY")."</td>
								<td>".ociresult($lines, "THE_GATEPASS")."</td>
								<td>".ociresult($order_plts, "ORDER_NUM")."</td>
								<td>".ociresult($order_plts, "THE_PLTS")."</td>
								<td>".ociresult($order_plts, "THE_CTNS")."</td>
							</tr>";
				while(ocifetch($order_plts)){
					$output .= "<tr>
									<td colspan=\"6\">&nbsp;</td>
									<td>".ociresult($order_plts, "ORDER_NUM")."</td>
									<td>".ociresult($order_plts, "THE_PLTS")."</td>
									<td>".ociresult($order_plts, "THE_CTNS")."</td>
								</tr>";
				}
			}
		}
		$output .= "</table>";

		$mailSubject = str_replace("_0_", $ves_name, ociresult($email, "SUBJECT"));
		$body = str_replace("_1_", "<html><body>".$output."</body></html>", ociresult($email, "NARRATIVE"));
		$body = str_replace("_br_", "<br>", $body);

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
						'LASTSWINGLOAD',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);
		}
	}