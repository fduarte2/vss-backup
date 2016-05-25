<?
/*
*	Adam Walter, Mar 2012.
*
*	Email notification about steel shipments.
*
*	10/25/2012 - have I mentioned I love hard coding?  because HD7949 wants
*	me to break this report down into many individual commodities, with
*	each one being avery specific commodity code.  Avast ye!  Hardcode away!
*************************************************************************/

//	$bni_conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	$bni_conn = ocilogon("SAG_OWNER", "SAG", "BNI");

//	$rf_conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	$rf_conn = ocilogon("SAG_OWNER", "OWNER", "RF");


	$i = 1;

//	for($i = 1; $i <= 29; $i++){
		$output = "<br>";
		$date = date('m/d/Y', mktime(0,0,0,date('m'),date('d')-$i,date('Y')));

		// SCANNED CARGO
		// coils
		$sql = "SELECT SUM(CA.QTY_CHANGE) THE_QTY, SUM((CA.QTY_CHANGE / CT.QTY_RECEIVED) * CT.WEIGHT) THE_LBS
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CT.COMMODITY_CODE IN ('3304', '3302')
					AND CA.SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND DATE_OF_ACTIVITY >= TO_DATE('".$date."', 'MM/DD/YYYY')
					AND DATE_OF_ACTIVITY <= TO_DATE('".$date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
		$stid = ociparse($rf_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Scanned Coils This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Scanned Coils - 3302, 3304</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Coils</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// plates
		$sql = "SELECT SUM(CA.QTY_CHANGE) THE_QTY, SUM((CA.QTY_CHANGE / CT.QTY_RECEIVED) * CT.WEIGHT) THE_LBS
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CA.PALLET_ID = CT.PALLET_ID
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CT.COMMODITY_CODE IN ('3326')
					AND CA.SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND DATE_OF_ACTIVITY >= TO_DATE('".$date."', 'MM/DD/YYYY')
					AND DATE_OF_ACTIVITY <= TO_DATE('".$date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
		$stid = ociparse($rf_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Scanned Plates This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Scanned Plates - 3326</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Plates</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		


		// UNSCANNED
		// trucked coils
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3304', '3302')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No UnScanned Trucked Coils This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>UnScanned Trucked Coils - 3302, 3304</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Coils</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar coils
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3304', '3302')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No UnScanned Railcar Coils This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>UnScanned Railcar Coils - 3302, 3304</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Coils</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// trucked plates
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3326')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No UnScanned Trucked Plates This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>UnScanned Trucked Plates - 3326</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Plates</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar plates
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3326')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No UnScanned Railcar Plates This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>UnScanned Railcar Plates - 3326</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Plates</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// trucked slabs
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3323')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Trucked Slabs This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Trucked Slabs - 3323</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Slabs</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar slabs
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3323')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Railcar Slabs This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Railcar Slabs - 3323</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Slabs</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// trucked rebar
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3328')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Trucked Rebar This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Trucked Rebar - 3328</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Rebar</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar rebar
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3328')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Railcar Rebar This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Railcar Rebar - 3328</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Rebar</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// trucked beams
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3322')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Trucked Beams This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Trucked Beams - 3322</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Beams</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar beams
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3326')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Railcar Beams This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Railcar Beams - 3322</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Beams</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// trucked wire rod
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3312')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Trucked Wire Rods This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Trucked Wire Rods - 3312</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Wire Rods</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar wire rod
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('3312')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Railcar Wire Rods This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Railcar Wire Rods - 3312</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\"># Wire Rods</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// trucked heavy lift
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('2299')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'TRUCK'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Trucked Heavy Lift This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Trucked Heavy Lift - 2299</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\">#</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		// railcar heavy lift
		$sql = "SELECT SUM(QTY_CHANGE) THE_QTY, SUM(QTY_CHANGE * (CARGO_WEIGHT/QTY_EXPECTED)) THE_LBS
				FROM CARGO_ACTIVITY CA, CARGO_DELIVERY CD, CARGO_MANIFEST CM
				WHERE CA.LOT_NUM = CD.LOT_NUM
					AND CA.ACTIVITY_NUM = CD.ACTIVITY_NUM
					AND CA.LOT_NUM = CM.CONTAINER_NUM
					AND CM.COMMODITY_CODE IN ('2299')
					AND CA.SERVICE_CODE = '6200'
					AND CD.TRANSPORTATION_MODE = 'RAILCAR'
					AND CD.DELIVERY_NUM > 0
					AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'";
		$stid = ociparse($bni_conn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_QTY") == "" || ociresult($stid, "THE_QTY") == 0){
			$output .= "<b>No Railcar Heavy Lift This Day.</b><br><br>";
		} else {
			$output .= "<table border=\"1\" width=\"30%\" cellpadding=\"2\" cellspacing=\"0\">
							<tr>
								<td colspan=\"3\" align=\"center\"><b>Railcar Heavy Lift - 2299</b></td>
							</tr>
							<tr>
								<td rowspan=\"2\">#</td>
								<td colspan=\"2\" align=\"center\"><b>Weight</b></td>
							</tr>
							<tr>
								<td>LBS</td>
								<td>TONS</td>
							</tr>
							<tr>
								<td>".ociresult($stid, "THE_QTY")."</td>
								<td>".round(ociresult($stid, "THE_LBS"), 2)."</td>
								<td>".round(ociresult($stid, "THE_LBS") / 2000, 2)."</td>
							</tr>
						</table><br><br>";
		}

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'STEELLOADS'";
		$email = ociparse($rf_conn, $sql);
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
		$mailSubject = str_replace("_0_", $date, $mailSubject);

		$body = "<html><body>".ociresult($email, "NARRATIVE")."</body></html>";
		$body = str_replace("_0_", $date, $body);
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
						'STEELLOADS',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".ociresult($email, "CC")."',
						'".ociresult($email, "BCC")."',
						'".substr($body, 0, 2000)."')";
			$email = ociparse($rf_conn, $sql);
			ociexecute($email);
		}
//	}