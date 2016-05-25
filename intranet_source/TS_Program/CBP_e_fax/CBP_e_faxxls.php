<?
/*
*	Adam Walter, Oct 2013
*
*	Sends an XLS sheet of the previous day's border crossing fax-mails.
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
		$sql_where = " AND cbp_fax > (SYSDATE - 1)";
	} else {
		$sql_where = " AND TO_CHAR(cbp_fax, 'MM/DD/YYYY') = '".$date."'";
	}

	// initiate the pdf writer
//	include 'class.ezpdf.php';
	$mailSubject = "Excel Version of Faxes sent to Border Crossings, ".date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
//	$mailSubject = "THIS IS A TEST Excel Version of Faxes sent to Border Crossings, ".date('m/d/Y', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
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

	$output = "<table>";

//				AND cbp_fax > (SYSDATE - 1)
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
				".$sql_where."
				AND cmd.arrival_num != '4321'
				AND voyage.port_of_destination IS NULL";
//	echo $sql."\n";
	$ves_data = ociparse($rfconn, $sql);
	ociexecute($ves_data);
	if(!ocifetch($ves_data)){
		// nothing to do, notify, end script
		mail("Zachary.pillarelli@cbp.dhs.gov,CBPAIDE@dhs.gov", $mailSubject, "No faxes sent to border crossing.", $mailheaders);
		exit;
	} else { do {
		$mailTO = "Zachary.pillarelli@cbp.dhs.gov,CBPAIDE@dhs.gov\r\n";
//		$mailSubject .= "   ".str_replace("-", "", ociresult($short_term_data, "FAX_NUMBER"));
//		$mailTO = "awalter@port.state.de.us\r\n";

		
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$sql = "SELECT
					NVL(vessel_name, 'NONE') the_ves,
					lloyd_num
				FROM vessel_profile
				WHERE
					TO_CHAR(lr_num) = '".ociresult($ves_data, "ARRIVAL_NUM")."'";
//		echo $sql."\n";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$vesname = ociresult($short_term_data, "THE_VES");
		$lloyd_num = ociresult($short_term_data, "LLOYD_NUM");
		$sql = "SELECT
					TO_CHAR(MIN(date_received), 'MM/DD/YYYY') the_date
				FROM cargo_tracking
				WHERE
					arrival_num = '".ociresult($ves_data, "ARRIVAL_NUM")."'";
//		echo $sql."\n";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$datearv = ociresult($short_term_data, "THE_DATE");

		$output .= "<tr><td colspan=\"17\"><b>Vessel/Voyage:  ".$vesname."  ".$lloyd_num."  Arrival Date: ".$datearv."</td></tr>";

//							<td><font size=\"2\"><b>Order #</b></font></td>
		$output .= "<tr>
							<td><font size=\"2\"><b>Exit Border Port/Code</b></font></td>
							<td><font size=\"2\"><b>Container/Trailer #</b></font></td>
							<td><font size=\"2\"><b>Type</b></font></td>
							<td><font size=\"2\"><b>Consignee</b></font></td>
							<td><font size=\"2\"><b>Order #</b></font></td>
							<td><font size=\"2\"><b>Ocean B/L#</b></font></td>
							<td><font size=\"2\"><b>T&E# / EAN#</b></font></td>
							<td><font size=\"2\"><b>Pallet Count</b></font></td>
							<td><font size=\"2\"><b>Cases</b></font></td>
							<td><font size=\"2\"><b>Origin</b></font></td>
							<td><font size=\"2\"><b>Commodity</b></font></td>
							<td><font size=\"2\"><b>In-Bond Transit Seal #</b></font></td>
							<td><font size=\"2\"><b>Seal Date/Time</b></font></td>
							<td><font size=\"2\"><b>Initials</b></font></td>
							<td><font size=\"2\"><b>Gate Pass #</b></font></td>
							<td><font size=\"2\"><b>Gate Pass Generated</b></font></td>
							<td><font size=\"2\"><b>CBP Fax Sent</b></font></td>
						</tr>";


//					AND SEALER IS NOT NULL				TRAILER_NUM,
//DECODE(CARGO_MODE, 'HTH', 'HTH', CONTAINER_NUM) CONT_FIELD,
//					AND CBP_FAX > (SYSDATE - 1)
		$sql = "SELECT
					cmd.bol_equiv,
					origin_m11,
					port_id,
					shipline,
					DECODE(pickup_type, 'SWING', 'TRANSLOAD', pickup_type) the_type,
					DECODE(pickup_type, 'BREAKBULK', trail_reef_plate_num || trail_reef_plate_state, cmd.container_num) the_cbp_cont,
					consignee,
					commodity,
					NVL(truck_te_num, t_e_num) the_te,
					seal_num,
					pltcount,
					qty,
					port_of_entry,
					ams_port_code,
					checkout_init,
					trail_reef_plate_num,
					trail_reef_plate_state,
					gatepass_num,
					TO_CHAR(gatepass_made_on, 'MM/DD/YYYY HH24:MI:SS') the_pass,
					TO_CHAR(cbp_fax, 'MM/DD/YYYY HH24:MI:SS') the_fax,
					TO_CHAR(seal_time, 'MM/DD/YYYY HH24:MI:SS') seal_time
				FROM clr_truck_load_release ctlr
				INNER JOIN clr_truck_main_join ctmj
					ON ctlr.port_id = ctmj.truck_port_id
				INNER JOIN clr_main_data cmd
					ON cmd.clr_key = ctmj.main_clr_key
				INNER JOIN clr_bordercrossing cb
					ON ctlr.border_crossing = cb.bordercrossing_id
				WHERE
					seal_num IS NOT NULL
					AND cmd.arrival_num = '".ociresult($ves_data, "ARRIVAL_NUM")."'
					$sql_where
				ORDER BY
					border_crossing,
					consignee,
					cmd.bol_equiv";
//		echo $sql."\n";
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
				//substr(ociresult($main_data, "T_AND_E_DISPLAY"), 0, strlen(ociresult($main_data, "T_AND_E_DISPLAY")) - 4)
				/*
				if(ociresult($main_data, "COMMODITY") == "CLEMENTINE"){
					$org = "MOROCCO";
				} else {
					$org = "CHILEAN";
				}
				*/
//									<td><font size=\"2\">".ociresult($main_data, "BOL")."</font></td>
				$output .= "<tr>
									<td><font size=\"2\">".ociresult($main_data, "PORT_OF_ENTRY")." - ".ociresult($main_data, "AMS_PORT_CODE")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "THE_CBP_CONT")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "THE_TYPE")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "CONSIGNEE").$sub_consignee."</font></td>
									<td><font size=\"2\">".$order."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "SHIPLINE")."-".ociresult($main_data, "BOL_EQUIV")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "THE_TE")."</font></td>
									<td><font size=\"2\">".$plts."</font></td>
									<td><font size=\"2\">".$cases." CS</font></td>
									<td><font size=\"2\">".ociresult($main_data, "ORIGIN_M11")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "COMMODITY")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "SEAL_NUM")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "SEAL_TIME")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "CHECKOUT_INIT")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "GATEPASS_NUM")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "THE_PASS")."</font></td>
									<td><font size=\"2\">".ociresult($main_data, "THE_FAX")."</font></td>
								</tr>";

			} while (ocifetch($main_data));
			$output .= "<tr><td colspan=\"17\"><b>* indicates multiple BOL's.  Displayed BOL matches the largest count of pallets.</td></tr>";
			$output .= "<br><br>";



		}
	} while(ocifetch($ves_data)); 

	$output .= "</table>";

	$attach1=chunk_split(base64_encode($output));
	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/xls; name=\"DailyFaxes".date('m/d/Y').".xls\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$attach1;
	$Content.="\r\n";

	mail($mailTO, $mailSubject, $Content, $mailheaders);

	}



function GetOrdNum($truck_ID, &$plts, &$cases, $rfconn){
	$sql = "SELECT * 
			FROM clr_truck_load_release
			WHERE port_id = '".$truck_ID."'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data)){
		$return = "UNKNOWN"; // the CHILEAN logic goes here, for now, nothing
	} else {
		$return = ociresult($main_data, "CLEM_ORDER_NUM");
		$cust = ociresult($main_data, "CUSTOMER_ID");
	}

	if(ociresult($main_data, "PICKUP_TYPE") == "HTH"){
		$sql = "SELECT
					cmd.qty,
					cmd.pltcount
				FROM clr_truck_load_release ctlr
				INNER JOIN clr_truck_main_join ctmj
					ON ctlr.port_id = ctmj.truck_port_id
				INNER JOIN clr_main_data cmd
					ON ctmj.main_clr_key = cmd.clr_key
				WHERE
					ctlr.port_id = '$truck_ID'";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
		$plts = ociresult($main_data, "PLTCOUNT");
	//	echo "plts: ".$plts."\n";
		$cases = ociresult($main_data, "QTY");
	//	echo "cases: ".$cases."\n";
	} elseif (ociresult($main_data, "PICKUP_TYPE") == "SWING"){
		$sql = "SELECT
					COUNT(DISTINCT ca.pallet_id) the_plts,
					SUM(qty_change) the_cases
				FROM cargo_activity ca
				INNER JOIN clr_truck_load_release ctlr
					ON ca.arrival_num = ctlr.arrival_num
					AND ca.customer_id = ctlr.customer_id
				INNER JOIN cargo_tracking ct
					ON ct.container_id = ctlr.container_num
					AND ct.receiver_id = ca.customer_id
					AND ct.arrival_num = ca.arrival_num
					AND ct.pallet_id = ca.pallet_id
				WHERE
					ctlr.port_id = '$truck_ID'
					AND service_code = '6'
					AND activity_description IS NULL";
	//	echo $sql."\n";
		$main_data = ociparse($rfconn, $sql);
		ociexecute($main_data);
		ocifetch($main_data);
		$plts = ociresult($main_data, "THE_PLTS");
	//	echo "plts: ".$plts."\n";
		$cases = ociresult($main_data, "THE_CASES");
	//	echo "cases: ".$cases."\n";
	} else {
		$sql = "SELECT
					COUNT(DISTINCT pallet_id) the_plts,
					SUM(qty_change) the_cases
				FROM cargo_activity
				WHERE
					order_num = TRIM('$return')
					AND customer_id = '$cust'
					AND service_code = '6'
					AND (activity_description IS NULL OR activity_description != 'VOID')";
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
	$sql = "SELECT clem_order_num
			FROM clr_truck_load_release
			WHERE port_id = '$truck_ID'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);
	if(ociresult($main_data, "CLEM_ORDER_NUM") == ""){
		return "";
	}
	$ordernum = ociresult($main_data, "CLEM_ORDER_NUM");

	$sql = "SELECT consigneeid
			FROM dc_order
			WHERE ordernum = '$ordernum'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data) || ociresult($main_data, "CONSIGNEEID") == ""){
		return "";
	}
	$consignee_num = ociresult($main_data, "CONSIGNEEID");

	$sql = "SELECT SUBSTR(consigneename, 0, 15) the_name
			FROM dc_consignee
			WHERE consigneeid = '$consignee_num'";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	if(!ocifetch($main_data) || ociresult($main_data, "THE_NAME") == ""){
		return "";
	}

	return " / ".ociresult($main_data, "THE_NAME");
}