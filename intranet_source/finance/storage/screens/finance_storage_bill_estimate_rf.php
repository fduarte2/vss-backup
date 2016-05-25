<?
/*
*	Adam Walter, Oct 2012
*
*	Inventory's default method for "stopping billing" is to just set
*	a "next billing date" sufficiently far int he future.
*
*	This email will notify INVE of any cargo that has a next bill
*	date 5 or mroe years in the future so they don't forget about it
************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	$this_season_year = date('Y', mktime(0,0,0,date('m')-9,date('d'),date('Y')));
//	$this_season_year = date('Y', mktime(0,0,0,date('m')-9,date('d'),date('Y') - 1));


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Scanned-Storage Reconciliation Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="finance_storage_bill_estimate_rf.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="All">All</option>
						<option value="vessels"<? if($vessel == "vessels"){?> selected <?}?>>All Vessels</option>
						<option value="nonvessels"<? if($vessel == "nonvessels"){?> selected <?}?>>All Non-Vessels</option>
<?
	$sql = "SELECT DISTINCT CT.ARRIVAL_NUM, VP.VESSEL_NAME 
			FROM VESSEL_PROFILE VP, CARGO_TRACKING CT
			WHERE DATE_RECEIVED >= '01-oct-".$this_season_year."'
				AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
				AND VP.LR_NUM != '4321'
			ORDER BY ARRIVAL_NUM";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "ARRIVAL_NUM"); ?>"<? if($vessel == ociresult($stid, "ARRIVAL_NUM")){?> selected <?}?>><? echo ociresult($stid, "ARRIVAL_NUM")."-".ociresult($stid, "VESSEL_NAME"); ?></option>
<?
	}
	$sql = "SELECT DISTINCT ARRIVAL_NUM
			FROM CARGO_TRACKING
			WHERE DATE_RECEIVED >= '01-oct-".$this_season_year."'
				AND ARRIVAL_NUM NOT IN
				(SELECT TO_CHAR(LR_NUM) FROM VESSEL_PROFILE)
			ORDER BY ARRIVAL_NUM";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){ 
?>
					<option value="<? echo ociresult($stid, "ARRIVAL_NUM"); ?>"<? if($vessel == ociresult($stid, "ARRIVAL_NUM")){?> selected <?}?>><? echo ociresult($stid, "ARRIVAL_NUM")." - TRUCK/XFER"; ?></option>
<?
	}
?>
			</select></td>
	<tr>
	<tr>
		<td><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $vessel != ""){
		$filename2 = "StorageRecon".date('mdYhis').".xls";
		$reference_loc = "temp/".$filename2;
		$fullpath = "/web/web_pages/".$reference_loc;
		$handle2 = fopen($fullpath, "w");

		$output = "<table border=\"0\">
						<tr><td>NFT - No Free Time Set</td></tr>
						<tr><td>EXP - Expected</td></tr>
						<tr><td>ACT - Actual</td></tr>
						<tr><td>DIFF - Difference</td></tr>
					</table>";
		fwrite($handle2, $output);
		echo $output;

		$output = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
					<tr>
						<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Report Created At ".date('m/d/Y h:i:s')."</b></font></td>
					</tr>
				</table><br><br>";
		fwrite($handle2, $output);
		echo $output;
		if($vessel == "All" || $vessel == "vessels"){
			$output = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
						<tr>
							<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b>* Finance - please set Arrival and Free Time Start Dates for vessels as soon as possible.</b></font></td>
						</tr>
					</table><br><br>";
			fwrite($handle2, $output);
			echo $output;
		}

		if($vessel == "All"){
			$any_vessels = true;
			$any_nonvessels = true;
		} elseif($vessel == "vessels"){
			$any_vessels = true;
			$any_nonvessels = false;
		} elseif($vessel == "nonvessels"){
			$any_vessels = false;
			$any_nonvessels = true;
		} else {
			$sql = "SELECT DISTINCT RECEIVING_TYPE FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			if(ociresult($stid, "RECEIVING_TYPE") == "S"){
				$any_vessels = true;
				$any_nonvessels = false;
			} else {
				$any_vessels = false;
				$any_nonvessels = true;
			}
		}


		if($any_vessels){
			$ves_list = array();
			$orig_ves_list = array();
//			$arvtype_list = array();
			$total_expected = array();
			$total_actual = array();

			$total_col_d = 0;
			$total_col_e = 0;
			$total_col_f = 0;
			$total_col_g = 0;
			$total_col_h = 0;
			$total_col_i = 0;

			$output = "<table border=\"1\">
							<tr><td colspan=\"29\" align=\"center\"><b>Vessels</b></td></tr>
							<tr bgcolor=\"#FFCC66\">
								<td>Vessel Name and Number</td>
								<td>Arrival Date</td>
								<td>Commodity Type</td>
								<td>Pallets Received</td>
								<td>Dole Pallets</td>
								<td>Free Time Starts</td>
								<td>Pallets Shipped Prior to start of Free Time</td>
								<td>Pallets Present at start of Free Time</td>
								<td>Pallets Shipped During Free Time</td>
								<td>Pallets Received Below \"Treshhold\"</td>
								<td>Free Time Ends</td>
								<td colspan=\"3\">1st Storage Period</td>
								<td colspan=\"3\">2nd Storage Period</td>
								<td colspan=\"3\">3rd Storage Period</td>
								<td colspan=\"3\">4th Storage Period</td>
								<td colspan=\"3\">5th Storage Period</td>
								<td colspan=\"3\">6th+ Storage Period</td>
								<td>Notes</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>&nbsp;</td>
							</tr>";

			if($vessel != "All" && $vessel != "vessels"){
				array_push($ves_list, $vessel);
				$sql = "SELECT LR_NUM FROM LR_CONVERSION
						WHERE OPT_ARRIVAL_NUM = '".$vessel."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				if(!ocifetch($stid)){
					array_push($orig_ves_list, $vessel);
				} else {
					array_push($orig_ves_list, ociresult($stid, "LR_NUM"));
				}
/*				$sql = "SELECT DISTINCT RECEIVING_TYPE 
						FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$vessel."'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				array_push($arvtype_list, ociresult($stid, "RECEIVING_TYPE")); */
			} else {
//				$sql = "SELECT DISTINCT ARRIVAL_NUM, RECEIVING_TYPE, DECODE(RECEIVING_TYPE, 'F', 'X', RECEIVING_TYPE) REC_ORDER 
				$sql = "SELECT DISTINCT ARRIVAL_NUM, NVL(TO_CHAR(LR_NUM), ARRIVAL_NUM) THE_ORIG
						FROM CARGO_TRACKING CT, LR_CONVERSION LC
						WHERE DATE_RECEIVED >= '01-oct-".$this_season_year."'
							AND RECEIVING_TYPE = 'S'
							AND ARRIVAL_NUM != '4321'
							AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
							AND CT.ARRIVAL_NUM = TO_CHAR(LC.OPT_ARRIVAL_NUM(+))
						ORDER BY ARRIVAL_NUM";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					array_push($ves_list, ociresult($stid, "ARRIVAL_NUM"));
					array_push($orig_ves_list, ociresult($stid, "THE_ORIG"));
//					array_push($arvtype_list, ociresult($stid, "RECEIVING_TYPE"));
				}
			}

			for($vesloop = 0; $vesloop < sizeof($ves_list); $vesloop++){
	/*			// if this is the "transition" from shipped to non-ship cargo, make a new table.
				if($vesloop > 0 && $ves_list[($vesloop - 1)] == 'S' && $ves_list[$vesloop] != 'S'){
					$output = "</table><br>
								<table border=\"1\">
									<tr>
										<td>Vessel Name and Number</td>
										<td>Arrival Date</td>
										<td>Commodity Type</td>
										<td>Pallets on Arrival</td>
										<td>Dole Pallets</td>
										<td>Pallets Shipped Prior to start of Free Time</td>
										<td>Pallets Present at start of Free Time</td>
										<td>Pallets Shipped During Free Time</td>
										<td colspan=\"3\">1st Storage Period</td>
										<td colspan=\"3\">2nd Storage Period</td>
										<td colspan=\"3\">3rd Storage Period</td>
										<td colspan=\"3\">4th Storage Period</td>
										<td colspan=\"3\">5th Storage Period</td>
										<td colspan=\"3\">6th+ Storage Period</td>
										<td>Notes</td>
										<td>Pallets needing billing resolution</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>EXP</td>
										<td>ACT</td>
										<td>DIFF</td>
										<td>EXP</td>
										<td>ACT</td>
										<td>DIFF</td>
										<td>EXP</td>
										<td>ACT</td>
										<td>DIFF</td>
										<td>EXP</td>
										<td>ACT</td>
										<td>DIFF</td>
										<td>EXP</td>
										<td>ACT</td>
										<td>DIFF</td>
										<td>EXP</td>
										<td>ACT</td>
										<td>DIFF</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>";
				}
	*/

				$comm_list_name = array();
				$comm_list_num = array();

				$sql = "SELECT DISTINCT NVL(COMMODITY_TYPE, 'UNKNOWN') THE_COMM, BNI_COMM
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, RF_TO_BNI_COMM RTBC
						WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CT.COMMODITY_CODE = RTBC.RF_COMM
							AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
							AND CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
							AND CT.RECEIVING_TYPE = 'S'
						ORDER BY NVL(COMMODITY_TYPE, 'UNKNOWN'), BNI_COMM";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					array_push($comm_list_name, ociresult($stid, "THE_COMM"));
					array_push($comm_list_num, ociresult($stid, "BNI_COMM"));
				}

				for($commloop = 0; $commloop < sizeof($comm_list_name); $commloop++){
					// the per line logic starts here.
	//				echo $ves_list[$vesloop]."<br>".$comm_list_num[$commloop]."<br>".$comm_list_name[$commloop]."<br>";



					// get bill increments (6 discrete increments)
					// NOTE:  we want the MOST GENERIC rate available, so sort results DESCENDING on priority.
					$duration_array = array(0);
					for($temp = 1; $temp <= 6; $temp++){
//						if($arvtype_list[$vesloop] == 'S'){
							$rate_arv = 'V';
//						} else {
//							$rate_arv = $arvtype_list[$vesloop];
//						}
						// check for vessel-specific rate first
						$sql = "SELECT RATE, BILLDURATION, FREEDAYS
								FROM RATE
								WHERE TO_CHAR(ARRIVALNUMBER) = '".$orig_ves_list[$vesloop]."'
									AND COMMODITYCODE = '".$comm_list_num[$commloop]."'
									AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
									AND (ARRIVALTYPE = '".$rate_arv."' OR ARRIVALTYPE = 'A')
									AND SCANNEDORUNSCANNED = 'SCANNED'
									AND RATE > 0
								ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
//						echo $sql."<br>";
						$stid = ociparse($bniconn, $sql);
						ociexecute($stid);
						if(ocifetch($stid)){
							$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
						} else {
							// no vessel-specific rate, get generic all-vessel

							$sql = "SELECT RATE, BILLDURATION, FREEDAYS
									FROM RATE
									WHERE COMMODITYCODE = '".$comm_list_num[$commloop]."'
										AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
										AND (ARRIVALTYPE = '".$rate_arv."' OR ARRIVALTYPE = 'A')
										AND SCANNEDORUNSCANNED = 'SCANNED'
										AND RATE > 0
									ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
							$stid = ociparse($bniconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
						}
					}
					// wherever we got the data from (you'll notice I used the same "cursor" in both cases", grab the freetime.
					$freetime = ociresult($stid, "FREEDAYS");
					$rate = ociresult($stid, "RATE");





					if($rate != 0){
						// we only show this line if it's cargo is actually RF-billed.
						$sql = "SELECT COUNT(*) THE_PALLETS
								FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
									AND CT.RECEIVING_TYPE = 'S'
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND CT.DATE_RECEIVED IS NOT NULL";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$total_for_ves_scanned = ociresult($stid, "THE_PALLETS");
						$total_col_d += $total_for_ves_scanned;

						$sql = "SELECT COUNT(*) THE_PALLETS
								FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
									AND CT.RECEIVING_TYPE = 'S'
									AND CT.RECEIVER_ID = '453'
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND CT.DATE_RECEIVED IS NOT NULL";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$total_for_ves_scanned_dole = ociresult($stid, "THE_PALLETS");
						$total_col_e += $total_for_ves_scanned_dole;


						$free_time_starts = "";
						$billing_starts = "";
						$sql = "SELECT TO_CHAR(FREE_TIME_START, 'MM/DD/YYYY') FREE_TIME, 
									TO_CHAR(FREE_TIME_START + ".$freetime.", 'MM/DD/YYYY') FIRST_BILL_DATE
								FROM VOYAGE_FROM_BNI
								WHERE LR_NUM = '".$orig_ves_list[$vesloop]."'";
		//				echo $sql."<br>";
						$stid = @ociparse($rfconn, $sql);
						@ociexecute($stid);
						if(@ocifetch($stid)){
							$free_time_starts = ociresult($stid, "FREE_TIME");
							$billing_starts = ociresult($stid, "FIRST_BILL_DATE");
						} else {
							// this isnt a ship.
							$sql = "SELECT TO_CHAR(MAX(DATE_RECEIVED), 'MM/DD/YYYY') FREE_TIME,
										TO_CHAR(MAX(DATE_RECEIVED + ".$freetime."), 'MM/DD/YYYY') FIRST_BILL_DATE
									FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
									WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
										AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
										AND CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
										AND CT.RECEIVING_TYPE = 'S'
										AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
										AND CT.DATE_RECEIVED IS NOT NULL";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							$free_time_starts = ociresult($stid, "FREE_TIME");
							$billing_starts = ociresult($stid, "FIRST_BILL_DATE");
						}

						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.DATE_RECEIVED IS NOT NULL
									AND CT.RECEIVER_ID != '453'
									AND CT.RECEIVING_TYPE = 'S'
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CTAD.SHIPOUTTIME < TO_DATE('".$free_time_starts."', 'MM/DD/YYYY')";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$shipped_before_FT_starts = ociresult($stid, "THE_COUNT");

						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.DATE_RECEIVED IS NOT NULL
									AND CT.RECEIVER_ID != '453'
									AND CT.RECEIVING_TYPE = 'S'
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CTAD.SHIPOUTTIME >= TO_DATE('".$free_time_starts."', 'MM/DD/YYYY')
									AND CTAD.SHIPOUTTIME < TO_DATE('".$billing_starts."', 'MM/DD/YYYY')";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$shipped_before_FT_ends = ociresult($stid, "THE_COUNT");

						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.DATE_RECEIVED IS NOT NULL
									AND CT.RECEIVER_ID != '453'
									AND CT.RECEIVING_TYPE = 'S'
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND QTY_RECEIVED <
										(SELECT NVL(QTY_THRESHOLD, 1) 
										FROM MINIMUM_INHOUSE_THRESHOLD MIT, COMMODITY_PROFILE CP
										WHERE MIT.COMMODITY_TYPE(+) = CP.COMMODITY_TYPE
										AND CP.COMMODITY_CODE = CT.COMMODITY_CODE)";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$rec_below_threshold = ociresult($stid, "THE_COUNT");

						// get the 6 expected cycles.
						$expected_pallets_to_bill = array();
						$actual_pallets_billed = array();
						$totalcolumn_pallets_billed = array();
						for($temp = 0; $temp < 6; $temp++){
							$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
									WHERE CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
										AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
										AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
										AND CT.PALLET_ID = CTAD.PALLET_ID
										AND CT.DATE_RECEIVED IS NOT NULL
										AND CT.RECEIVER_ID != '453'
										AND CT.RECEIVING_TYPE = 'S'
										AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
										AND CT.COMMODITY_CODE = RTBC.RF_COMM
										AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
										AND QTY_RECEIVED >=
											(SELECT NVL(QTY_THRESHOLD, 1) 
											FROM MINIMUM_INHOUSE_THRESHOLD MIT, COMMODITY_PROFILE CP
											WHERE MIT.COMMODITY_TYPE(+) = CP.COMMODITY_TYPE
											AND CP.COMMODITY_CODE = CT.COMMODITY_CODE)
										AND (CTAD.SHIPOUTTIME IS NULL 
												OR CTAD.SHIPOUTTIME > TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp].")";
//							echo $sql."<br>";
							$expec = ociparse($rfconn, $sql);
							ociexecute($expec);
							ocifetch($expec);
//							$expected_pallets_to_bill[$temp] = ociresult($expec, "THE_COUNT");
//							$total_expected[$temp] += ociresult($expec, "THE_COUNT");

							$sql = "SELECT COUNT(PALLET_ID) THE_COUNT
									FROM RF_BILLING_DETAIL
									WHERE SERVICE_STATUS != 'DELETED'
										AND SUM_BILL_NUM IN
											(SELECT BILLING_NUM
											FROM RF_BILLING
											WHERE SERVICE_DATE >= TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp]."
												AND SERVICE_DATE < TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[($temp + 1)]."
												AND ARRIVAL_NUM = '".$orig_ves_list[$vesloop]."'
												AND COMMODITY_CODE = '".$comm_list_num[$commloop]."'
												AND SERVICE_STATUS = 'INVOICED')";
//							echo $sql."<br>";
							$actual = ociparse($rfconn, $sql);
							ociexecute($actual);
							ocifetch($actual);
//							$actual_pallets_billed[$temp] = ociresult($actual, "THE_COUNT");
//							$total_actual[$temp] += ociresult($actual, "THE_COUNT");

							$sql = "SELECT DECODE(LEAST(SYSDATE, TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp]."), SYSDATE, 1, 0) TO_HIDE FROM DUAL";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							if($free_time_starts == ""){
								$expected_pallets_to_bill[$temp] = "N/A";
								$actual_pallets_billed[$temp] = "N/A";
//								$totalcolumn_pallets_billed[$temp] = "NFT";
								$totalcolumn_pallets_billed[$temp] = "N/A";
							} elseif(ociresult($stid, "TO_HIDE") != 1){
								$expected_pallets_to_bill[$temp] = ociresult($expec, "THE_COUNT");
								$actual_pallets_billed[$temp] = ociresult($actual, "THE_COUNT");
								$total_expected[$temp] += ociresult($expec, "THE_COUNT");
								$total_actual[$temp] += ociresult($actual, "THE_COUNT");
								$totalcolumn_pallets_billed[$temp] = $actual_pallets_billed[$temp] - $expected_pallets_to_bill[$temp];
							} else {
								$expected_pallets_to_bill[$temp] = "N/A";
								$actual_pallets_billed[$temp] = "N/A";
								$totalcolumn_pallets_billed[$temp] = "N/A";
							}

						}

						$sql = "SELECT NOTE_DESCRIPTION FROM RF_STORAGE_RECON_REPORT_NOTES
								WHERE ARRIVAL_NUM = '".$ves_list[$vesloop]."'
									AND COMMODITY_CODE = '".$comm_list_num[$commloop]."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$notes = ociresult($stid, "NOTE_DESCRIPTION");
						if($notes == ""){
							$notes = "None";
						}

						// that should be all the compiled data.
						// prepare some formatting variables.
						$color_array = array();
						for($temp = 0; $temp < 6; $temp++){
							if(is_numeric($totalcolumn_pallets_billed[$temp]) && $totalcolumn_pallets_billed[$temp] < 0){
								$color_array[$temp] = "#FF6666";
							} elseif(is_numeric($totalcolumn_pallets_billed[$temp]) && $totalcolumn_pallets_billed[$temp] > 0){
								$color_array[$temp] = "#66FF33";
							} elseif($totalcolumn_pallets_billed[$temp] == "NFT") {
								$color_array[$temp] = "#FFFF66";
							} else { // its even or not yet reached this point
								$color_array[$temp] = "#FFFFFF";
							}
						}

						$sql = "SELECT NVL(VESSEL_NAME, LR_NUM || '-UNKNWON') THE_VES FROM VESSEL_PROFILE
								WHERE TO_CHAR(LR_NUM) = '".$ves_list[$vesloop]."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$vesname = ociresult($stid, "THE_VES");
						$sql = "SELECT NVL(TO_CHAR(DATE_ARRIVED, 'MM/DD/YYYY'), 'Not Set') THE_ARV 
								FROM VOYAGE_FROM_BNI WHERE TO_CHAR(LR_NUM) = '".$orig_ves_list[$vesloop]."'";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$arvdate = ociresult($stid, "THE_ARV");

						if($free_time_starts == ""){
							$free_time_starts = "Not Set";
							$shipped_before_FT_starts = "N/A";
							$shipped_before_FT_starts = "N/A";
							$shipped_before_FT_ends = "N/A";
							$line_total_disp = "N/A";
						} else {
							$total_col_f += $shipped_before_FT_starts;
							$total_col_g += $total_for_ves_scanned - ($total_for_ves_scanned_dole + $shipped_before_FT_starts);
							$line_total_disp = $total_for_ves_scanned - ($total_for_ves_scanned_dole + $shipped_before_FT_starts);
							$total_col_h += $shipped_before_FT_ends;
							$total_col_i += $rec_below_threshold;
						}

						if($billing_starts == ""){
							$billing_starts = "Not Set";
						}



						// now... to the output!
						$output .= "<tr>
										<td><font size=\"2\">".$ves_list[$vesloop]."-".$vesname;
									if($orig_ves_list[$vesloop] != $ves_list[$vesloop]){ $output .= "<br>(".$orig_ves_list[$vesloop].")"; }
						$output .=			"</td>
										<td><font size=\"2\">".$arvdate."</td>
										<td><font size=\"2\">".$comm_list_name[$commloop]."(".$comm_list_num[$commloop].")</td>
										<td><font size=\"2\"><nobr>".$total_for_ves_scanned."</nobr></td>
										<td><font size=\"2\"><nobr>".$total_for_ves_scanned_dole."</nobr></td>";
						$output .= "
										<td><font size=\"2\"><nobr>".$free_time_starts."</nobr></td>
										<td><font size=\"2\"><nobr>".($shipped_before_FT_starts)."</nobr></td>
										<td><font size=\"2\"><nobr>".$line_total_disp."</nobr></td>
										<td><font size=\"2\"><nobr>".($shipped_before_FT_ends)."</nobr></td>
										<td><font size=\"2\"><nobr>".($rec_below_threshold)."</nobr></td>
										<td><font size=\"2\"><nobr>".$billing_starts."</nobr></td>";
						$output .= "
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($expected_pallets_to_bill[0], 1, "expected", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($actual_pallets_billed[0], 1, "actual", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td bgcolor=\"".$color_array[0]."\"><font size=\"2\"><nobr>".DisplayDetailHyperlink($totalcolumn_pallets_billed[0], 1, "both", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($expected_pallets_to_bill[1], 2, "expected", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($actual_pallets_billed[1], 2, "actual", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td bgcolor=\"".$color_array[1]."\"><font size=\"2\"><nobr>".DisplayDetailHyperlink($totalcolumn_pallets_billed[1], 2, "both", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($expected_pallets_to_bill[2], 3, "expected", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($actual_pallets_billed[2], 3, "actual", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td bgcolor=\"".$color_array[2]."\"><font size=\"2\"><nobr>".DisplayDetailHyperlink($totalcolumn_pallets_billed[2], 3, "both", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($expected_pallets_to_bill[3], 4, "expected", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($actual_pallets_billed[3], 4, "actual", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td bgcolor=\"".$color_array[3]."\"><font size=\"2\"><nobr>".DisplayDetailHyperlink($totalcolumn_pallets_billed[3], 4, "both", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($expected_pallets_to_bill[4], 5, "expected", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($actual_pallets_billed[4], 5, "actual", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td bgcolor=\"".$color_array[4]."\"><font size=\"2\"><nobr>".DisplayDetailHyperlink($totalcolumn_pallets_billed[4], 5, "both", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($expected_pallets_to_bill[5], 6, "expected", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td><font size=\"2\"><nobr>".DisplayDetailHyperlink($actual_pallets_billed[5], 6, "actual", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>
										<td bgcolor=\"".$color_array[5]."\"><font size=\"2\"><nobr>".DisplayDetailHyperlink($totalcolumn_pallets_billed[5], 6, "both", $ves_list[$vesloop], $comm_list_num[$commloop], "", "V", $rfconn)."</nobr></td>";
						$output .= "
										<td><font size=\"2\"><a href=\"storage_recon_notes.php?vessel=".$ves_list[$vesloop]."&comm=".$comm_list_num[$commloop]."&commname=".$comm_list_name[$commloop]."\" target=\"storage_recon_notes.php?vessel=".$ves_list[$vesloop]."&comm=".$comm_list_num[$commloop]."&commname=".$comm_list_name[$commloop]."\">".$notes."&nbsp;</a></td>
									</tr>";
					}
				}
			}
			$color_array = array();
			for($temp = 0; $temp < 6; $temp++){
				if(($total_expected[$temp] - $total_actual[$temp]) > 0){
					$color_array[$temp] = "#FF6666";
				} elseif(($total_expected[$temp] - $total_actual[$temp]) < 0){
					$color_array[$temp] = "#66FF33";
				} else { // its even
					$color_array[$temp] = "#FFFFFF";
				}
			}

			$output .= "<tr>
							<td>TOTAL</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><nobr>".$total_col_d."</nobr></td>
							<td><nobr>".$total_col_e."</nobr></td>
							<td>&nbsp;</td>
							<td><nobr>".$total_col_f."</nobr></td>
							<td><nobr>".$total_col_g."</nobr></td>
							<td><nobr>".$total_col_h."</nobr></td>
							<td><nobr>".$total_col_i."</nobr></td>
							<td>&nbsp;</td>";
			$output .= "
							<td><nobr>".(0 + $total_expected[0])."</nobr></td>
							<td><nobr>".(0 + $total_actual[0])."</nobr></td>
							<td bgcolor=\"".$color_array[0]."\"><nobr>".(-1 * ($total_expected[0] - $total_actual[0]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[1])."</nobr></td>
							<td><nobr>".(0 + $total_actual[1])."</nobr></td>
							<td bgcolor=\"".$color_array[1]."\"><nobr>".(-1 * ($total_expected[1] - $total_actual[1]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[2])."</nobr></td>
							<td><nobr>".(0 + $total_actual[2])."</nobr></td>
							<td bgcolor=\"".$color_array[2]."\"><nobr>".(-1 * ($total_expected[2] - $total_actual[2]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[3])."</nobr></td>
							<td><nobr>".(0 + $total_actual[3])."</nobr></td>
							<td bgcolor=\"".$color_array[3]."\"><nobr>".(-1 * ($total_expected[3] - $total_actual[3]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[4])."</nobr></td>
							<td><nobr>".(0 + $total_actual[4])."</nobr></td>
							<td bgcolor=\"".$color_array[4]."\"><nobr>".(-1 * ($total_expected[4] - $total_actual[4]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[5])."</nobr></td>
							<td><nobr>".(0 + $total_actual[5])."</nobr></td>
							<td bgcolor=\"".$color_array[5]."\"><nobr>".(-1 * ($total_expected[5] - $total_actual[5]))."</nobr></td>";
			$output .= "
							<td>&nbsp;</td>
						</tr>";

			$output .= "</table>";

			fwrite($handle2, $output);

			echo $output;
		}

		if($any_vessels && $any_nonvessels){
			$output = "<table border=\"0\"><tr><td>&nbsp;</td></tr></table>";
			fwrite($handle2, $output);
			echo $output;
			$output = "<table border=\"0\"><tr><td>&nbsp;</td></tr></table>";
			fwrite($handle2, $output);
			echo $output;
			$output = "<table border=\"0\"><tr><td>&nbsp;</td></tr></table>";
			fwrite($handle2, $output);
			echo $output;
		}

		if($any_nonvessels){
			$cust_list = array();
			$arvtype_list = array();
			$total_expected = array();
			$total_actual = array();

			if($vessel != "All" && $vessel != "nonvessels"){
				$extra_print = "Arrival# ".$vessel." ONLY";
			} else {
				$extra_print = "Non - Vessels";
			}

			$output = "<table border=\"1\">
							<tr><td colspan=\"27\" align=\"center\"><b>".$extra_print."</b></td></tr>
							<tr bgcolor=\"#FFCC66\">
								<td>Customer</td>
								<td>Commodity Type</td>
								<td># of Trucks/Transfers</td>
								<td>Pallets Received</td>
								<td>Pallets Shipped Prior to start of Free Time</td>
								<td>Pallets Present at start of Free Time</td>
								<td>Pallets Still In House (in free time)</td>
								<td>Pallets Shipped During Free Time</td>
								<td colspan=\"3\">1st Storage Period</td>
								<td colspan=\"3\">2nd Storage Period</td>
								<td colspan=\"3\">3rd Storage Period</td>
								<td colspan=\"3\">4th Storage Period</td>
								<td colspan=\"3\">5th Storage Period</td>
								<td colspan=\"3\">6th+ Storage Period</td>
								<td>Notes</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>EXP</td>
								<td>ACT</td>
								<td>DIFF</td>
								<td>&nbsp;</td>
							</tr>";

			if($vessel != "All" && $vessel != "nonvessels"){
				$sql = "SELECT DISTINCT RECEIVING_TYPE, RECEIVER_ID 
						FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$vessel."'
							AND (WAREHOUSE_LOCATION IS NULL OR WAREHOUSE_LOCATION != 'SW')";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
//				echo $sql."<br>";
				while(ocifetch($stid)){
					array_push($cust_list, ociresult($stid, "RECEIVER_ID"));
					array_push($arvtype_list, ociresult($stid, "RECEIVING_TYPE"));
				}
				$vessel_ct_sql = " AND CT.ARRIVAL_NUM = '".$vessel."' ";
				$vessel_rate_sql = " AND (ARRIVALNUMBER = '".$vessel."' OR ARRIVALNUMBER IS NULL)";
				$vessel_rfbilling_sql = " AND RB.ARRIVAL_NUM = '".$vessel."' ";
			} else {
				$sql = "SELECT DISTINCT RECEIVER_ID, RECEIVING_TYPE, DECODE(RECEIVING_TYPE, 'F', 'X', RECEIVING_TYPE) REC_ORDER 
						FROM CARGO_TRACKING
						WHERE DATE_RECEIVED >= '01-sep-".$this_season_year."'
							AND RECEIVING_TYPE != 'S'
							AND (WAREHOUSE_LOCATION IS NULL OR WAREHOUSE_LOCATION != 'SW')
						ORDER BY DECODE(RECEIVING_TYPE, 'F', 'X', RECEIVING_TYPE)";
//				echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					array_push($cust_list, ociresult($stid, "RECEIVER_ID"));
					array_push($arvtype_list, ociresult($stid, "RECEIVING_TYPE"));
				}
				$vessel_ct_sql = "";
				$vessel_rate_sql = "";
				$vessel_rfbilling_sql = "";
			}

			for($custloop = 0; $custloop < sizeof($cust_list); $custloop++){

				$comm_list_name = array();
				$comm_list_num = array();

				$sql = "SELECT DISTINCT NVL(COMMODITY_TYPE, 'UNKNOWN') THE_COMM, BNI_COMM
						FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, RF_TO_BNI_COMM RTBC
						WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
							AND CT.COMMODITY_CODE = RTBC.RF_COMM
							AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
							AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
							AND CT.RECEIVER_ID = '".$cust_list[$custloop]."'
							AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
							".$vessel_ct_sql."
							AND CT.DATE_RECEIVED IS NOT NULL
						ORDER BY NVL(COMMODITY_TYPE, 'UNKNOWN'), BNI_COMM";
//				echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				while(ocifetch($stid)){
					array_push($comm_list_name, ociresult($stid, "THE_COMM"));
					array_push($comm_list_num, ociresult($stid, "BNI_COMM"));
				}

				for($commloop = 0; $commloop < sizeof($comm_list_name); $commloop++){
					$duration_array = array(0);
					for($temp = 1; $temp <= 6; $temp++){
						if($arvtype_list[$custloop] == 'S'){
							$rate_arv = 'V';
						} else {
							$rate_arv = $arvtype_list[$custloop];
						}
						// check for customer-specific rate first
						$sql = "SELECT RATE, BILLDURATION, FREEDAYS, NVL(BILLTOCUSTOMER, '".$cust_list[$custloop]."') BILL_CUST
								FROM RATE
								WHERE CUSTOMERID = '".$cust_list[$custloop]."'
									AND COMMODITYCODE = '".$comm_list_num[$commloop]."'
									AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
									".$vessel_rate_sql."
									AND (ARRIVALTYPE = '".$rate_arv."' OR ARRIVALTYPE = 'A')
									AND SCANNEDORUNSCANNED = 'SCANNED'
								ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
//						echo $sql."<br>";
						$stid = ociparse($bniconn, $sql);
						ociexecute($stid);
						if(ocifetch($stid)){
							$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
						} else {
							// no customer-specific rate, get generic all-vessel

							$sql = "SELECT RATE, BILLDURATION, FREEDAYS, NVL(BILLTOCUSTOMER, '".$cust_list[$custloop]."') BILL_CUST
									FROM RATE
									WHERE COMMODITYCODE = '".$comm_list_num[$commloop]."'
										AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
										".$vessel_rate_sql."
										AND (ARRIVALTYPE = '".$rate_arv."' OR ARRIVALTYPE = 'A')
										AND SCANNEDORUNSCANNED = 'SCANNED'
									ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
//							echo $sql."<br>";
							$stid = ociparse($bniconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
						}
					}
					// wherever we got the data from (you'll notice I used the same "cursor" in both cases", grab the freetime.
					$freetime = ociresult($stid, "FREEDAYS");
					$rate = ociresult($stid, "RATE");
					$billto = ociresult($stid, "BILL_CUST");

					if($rate != 0){
						// we only show this line if it's cargo is actually RF-billed.
						$sql = "SELECT COUNT(*) THE_PALLETS, COUNT(DISTINCT ARRIVAL_NUM) THE_ARVS
								FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
									AND CT.RECEIVER_ID = '".$cust_list[$custloop]."'
									AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
									".$vessel_ct_sql."
									AND CT.DATE_RECEIVED IS NOT NULL";
//						echo $sql."<bR>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$total_for_ves_scanned = ociresult($stid, "THE_PALLETS");
						$distinct_arvs = ociresult($stid, "THE_ARVS");
/*
						$sql = "SELECT COUNT(*) THE_PALLETS
								FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CT.RECEIVER_ID = '".$cust_list[$custloop]."'
									AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
									AND CT.RECEIVER_ID = '453'
									AND CT.DATE_RECEIVED IS NOT NULL";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$total_for_ves_scanned_dole = ociresult($stid, "THE_PALLETS");
*/
/*						$sql = "SELECT TO_CHAR(MAX(DATE_RECEIVED), 'MM/DD/YYYY') FREE_TIME,
									TO_CHAR(MAX(DATE_RECEIVED + ".$freetime."), 'MM/DD/YYYY') FIRST_BILL_DATE
								FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CT.RECEIVER_ID = '".$cust_list[$custloop]."'
									AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
									".$vessel_ct_sql."
									AND CT.DATE_RECEIVED IS NOT NULL";
//						echo $sql."<br>";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$free_time_starts = ociresult($stid, "FREE_TIME");
						$billing_starts = ociresult($stid, "FIRST_BILL_DATE");
*/
						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.RECEIVER_ID = '".$cust_list[$custloop]."'
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.DATE_RECEIVED IS NOT NULL
									AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
									AND CT.RECEIVER_ID != '453'
									AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
									AND CT.COMMODITY_CODE = RTBC.RF_COMM
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									".$vessel_ct_sql."
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CTAD.SHIPOUTTIME < TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$shipped_before_FT_starts = ociresult($stid, "THE_COUNT");

						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.RECEIVER_ID = '".$cust_list[$custloop]."'
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.DATE_RECEIVED IS NOT NULL
									AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
									AND CT.RECEIVER_ID != '453'
									AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
									AND CT.COMMODITY_CODE = RTBC.RF_COMM
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									".$vessel_ct_sql."
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CTAD.SHIPOUTTIME >= TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY')
									AND CTAD.SHIPOUTTIME < TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY') + ".$freetime;
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$shipped_before_FT_ends = ociresult($stid, "THE_COUNT");

						$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
								WHERE CT.RECEIVER_ID = '".$cust_list[$custloop]."'
									AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
									AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
									AND CT.PALLET_ID = CTAD.PALLET_ID
									AND CT.DATE_RECEIVED IS NOT NULL
									AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
									AND CT.RECEIVER_ID != '453'
									AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
									AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
									AND CT.COMMODITY_CODE = RTBC.RF_COMM
									".$vessel_ct_sql."
									AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
									AND CTAD.SHIPOUTTIME IS NULL
									AND CT.DATE_RECEIVED + '".$freetime."' > SYSDATE";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$still_in_free_time = ociresult($stid, "THE_COUNT");

						// get the 6 expected cycles.
						$expected_pallets_to_bill = array();
						$actual_pallets_billed = array();
						$totalcolumn_pallets_billed = array();

						$sql = "SELECT COUNT(*) THE_COUNT, DECODE(SIGN(6 - THE_COUNT), 1, (THE_COUNT - 1), 6) ARRAY_INDEX FROM 
									(SELECT RBD.PALLET_ID, RB.ARRIVAL_NUM, COUNT(*) THE_COUNT
									FROM RF_BILLING RB, RF_BILLING_DETAIL RBD
									WHERE RB.BILLING_NUM = RBD.SUM_BILL_NUM
									AND RB.SERVICE_STATUS = 'INVOICED'
									AND RBD.SERVICE_STATUS != 'DELETED'
									".$vessel_rfbilling_sql."
									AND RB.CUSTOMER_ID = '".$billto."'
									AND RB.COMMODITY_CODE = '".$comm_list_num[$commloop]."'
									AND RB.SERVICE_START >= '01-sep-".$this_season_year."'
									AND RB.ARRIVAL_NUM IN
										(SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE RECEIVING_TYPE = '".$arvtype_list[$custloop]."')
									GROUP BY RBD.PALLET_ID, RB.ARRIVAL_NUM)
								GROUP BY THE_COUNT, DECODE(SIGN(6 - THE_COUNT), 1, (THE_COUNT - 1), 6)";
//						echo $sql."<br>";
						$actual = ociparse($rfconn, $sql);
						ociexecute($actual);
						while(ocifetch($actual)){
							for($temp = 0; $temp <= ociresult($actual, "ARRAY_INDEX"); $temp++){
								// this loop is because we want, for example, all the pallets in "2" bill cycles to be counted in
								// both the 2 AND the 1 column
								$actual_pallets_billed[$temp] += ociresult($actual, "THE_COUNT");
								$total_actual[$temp] += ociresult($actual, "THE_COUNT");
							}
						}

						for($temp = 0; $temp < 6; $temp++){
							$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
									WHERE CT.RECEIVER_ID = '".$cust_list[$custloop]."'
										AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
										AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
										AND CT.PALLET_ID = CTAD.PALLET_ID
										AND CT.DATE_RECEIVED IS NOT NULL
										AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
										AND CT.RECEIVER_ID != '453'
										AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
										AND CT.RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
										AND CT.COMMODITY_CODE = RTBC.RF_COMM
										".$vessel_ct_sql."
										AND RTBC.BNI_COMM = '".$comm_list_num[$commloop]."'
										AND CT.DATE_RECEIVED + ".$freetime." + ".$duration_array[$temp]." < SYSDATE
										AND (CTAD.SHIPOUTTIME IS NULL 
												OR CTAD.SHIPOUTTIME > TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY') + ".$freetime." + ".$duration_array[$temp].")";
//												OR CTAD.SHIPOUTTIME > TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp].")";
//							echo $sql."<br>";
							$expec = ociparse($rfconn, $sql);
							ociexecute($expec);
							ocifetch($expec);
//							$expected_pallets_to_bill[$temp] = ociresult($stid, "THE_COUNT");
//							$total_expected[$temp] += ociresult($stid, "THE_COUNT");

/*							$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT
									FROM RF_BILLING_DETAIL
									WHERE SERVICE_STATUS != 'DELETED'
										AND SUM_BILL_NUM IN
											(SELECT BILLING_NUM
											FROM RF_BILLING
											WHERE SERVICE_DATE >= TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp]."
												AND SERVICE_DATE < TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[($temp + 1)]."
												AND CUSTOMER_ID = '".$billto."'
												AND COMMODITY_CODE = '".$comm_list_num[$commloop]."'
												".$vessel_rfbilling_sql."
												AND SERVICE_STATUS = 'INVOICED')";
							echo $sql."<br>";
							$actual = ociparse($rfconn, $sql);
							ociexecute($actual);
							ocifetch($actual);
//							$actual_pallets_billed[$temp] = ociresult($stid, "THE_COUNT");
//							$total_actual[$temp] += ociresult($stid, "THE_COUNT");*/

							$sql = "SELECT DECODE(LEAST(SYSDATE, 
														MIN(TO_DATE(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), 'MM/DD/YYYY') + ".$freetime." + ".$duration_array[$temp].")), SYSDATE, 1, 0) TO_HIDE
									FROM CARGO_TRACKING CT
									WHERE DATE_RECEIVED >= '01-sep-".$this_season_year."'
										AND (CT.WAREHOUSE_LOCATION IS NULL OR CT.WAREHOUSE_LOCATION != 'SW')
										AND RECEIVING_TYPE = '".$arvtype_list[$custloop]."'
										".$vessel_ct_sql."
										AND RECEIVER_ID = '".$cust_list[$custloop]."'";
							$stid = ociparse($rfconn, $sql);
							ociexecute($stid);
							ocifetch($stid);
							if(ociresult($stid, "TO_HIDE") != 1){
								$expected_pallets_to_bill[$temp] = ociresult($expec, "THE_COUNT");
								$actual_pallets_billed[$temp] = (0 + $actual_pallets_billed[$temp]);
								$total_actual[$temp] = (0 + $total_actual[$temp]);
								$total_expected[$temp] += ociresult($expec, "THE_COUNT");
								$totalcolumn_pallets_billed[$temp] = $actual_pallets_billed[$temp] - $expected_pallets_to_bill[$temp];
							} else {
								// override expected value
								$expected_pallets_to_bill[$temp] = "N/A";
								$actual_pallets_billed[$temp] = "N/A";
								$totalcolumn_pallets_billed[$temp] = "N/A";
							}						
						
						}

						$sql = "SELECT NOTE_DESCRIPTION FROM RF_STORAGE_RECON_REPORT_NOTES
								WHERE ARRIVAL_NUM = '".$cust_list[$custloop]."'
									AND COMMODITY_CODE = '".$comm_list_num[$commloop]."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$notes = ociresult($stid, "NOTE_DESCRIPTION");
						if($notes == ""){
							$notes = "None";
						}

						// that should be all the compiled data.
						// prepare some formatting variables.
						$color_array = array();
						for($temp = 0; $temp < 6; $temp++){
							if(is_numeric($totalcolumn_pallets_billed[$temp]) && $totalcolumn_pallets_billed[$temp] < 0){
								$color_array[$temp] = "#FF6666";
							} elseif(is_numeric($totalcolumn_pallets_billed[$temp]) && $totalcolumn_pallets_billed[$temp] > 0){
								$color_array[$temp] = "#66FF33";
							} else { // its even
								$color_array[$temp] = "#FFFFFF";
							}
						}

						$sql = "SELECT NVL(CUSTOMER_NAME, CUSTOMER_ID || '-UNKNOWN') THE_CUST FROM CUSTOMER_PROFILE
								WHERE CUSTOMER_ID = '".$cust_list[$custloop]."'";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid);
						ocifetch($stid);
						$custname = ociresult($stid, "THE_CUST");
						if($arvtype_list[$custloop] == "T"){
							$vesname = "TRUCK";
						} else {
							$vesname = "TRANSFER";
						}

						// now... to the output!
						$output .= "<tr>
										<td><font size=\"2\"><a href=\"customer_truck_detail.php?cust=".$cust_list[$custloop]."&arv=".$arvtype_list[$custloop]."&comm=".$comm_list_num[$commloop]."\">".$custname."-".$vesname."</a></td>
										<td><font size=\"2\">".$comm_list_name[$commloop]."(".$comm_list_num[$commloop].")</td>
										<td><font size=\"2\">".$distinct_arvs."</td>
										<td><font size=\"2\"><nobr>".$total_for_ves_scanned."</nobr></td>";
						$output .= "
										<td><font size=\"2\"><nobr>".($shipped_before_FT_starts)."</nobr></td>
										<td><font size=\"2\"><nobr>".($total_for_ves_scanned - $shipped_before_FT_starts)."</nobr></td>
										<td><font size=\"2\"><nobr>".($still_in_free_time)."</nobr></td>
										<td><font size=\"2\"><nobr>".($shipped_before_FT_ends)."</nobr></td>";
						$output .= "
										<td><font size=\"2\"><nobr>".($expected_pallets_to_bill[0])."</nobr></td>
										<td><font size=\"2\"><nobr>".($actual_pallets_billed[0])."</nobr></td>
										<td bgcolor=\"".$color_array[0]."\"><font size=\"2\"><nobr>".$totalcolumn_pallets_billed[0]."</nobr></td>
										<td><font size=\"2\"><nobr>".($expected_pallets_to_bill[1])."</nobr></td>
										<td><font size=\"2\"><nobr>".($actual_pallets_billed[1])."</nobr></td>
										<td bgcolor=\"".$color_array[1]."\"><font size=\"2\"><nobr>".$totalcolumn_pallets_billed[1]."</nobr></td>
										<td><font size=\"2\"><nobr>".($expected_pallets_to_bill[2])."</nobr></td>
										<td><font size=\"2\"><nobr>".($actual_pallets_billed[2])."</nobr></td>
										<td bgcolor=\"".$color_array[2]."\"><font size=\"2\"><nobr>".$totalcolumn_pallets_billed[2]."</nobr></td>
										<td><font size=\"2\"><nobr>".($expected_pallets_to_bill[3])."</nobr></td>
										<td><font size=\"2\"><nobr>".($actual_pallets_billed[3])."</nobr></td>
										<td bgcolor=\"".$color_array[3]."\"><font size=\"2\"><nobr>".$totalcolumn_pallets_billed[3]."</nobr></td>
										<td><font size=\"2\"><nobr>".($expected_pallets_to_bill[4])."</nobr></td>
										<td><font size=\"2\"><nobr>".($actual_pallets_billed[4])."</nobr></td>
										<td bgcolor=\"".$color_array[4]."\"><font size=\"2\"><nobr>".$totalcolumn_pallets_billed[4]."</nobr></td>
										<td><font size=\"2\"><nobr>".($expected_pallets_to_bill[5])."</nobr></td>
										<td><font size=\"2\"><nobr>".($actual_pallets_billed[5])."</nobr></td>
										<td bgcolor=\"".$color_array[5]."\"><font size=\"2\"><nobr>".$totalcolumn_pallets_billed[5]."</nobr></td>";
						$output .= "
										<td><font size=\"2\"><a href=\"storage_recon_notes.php?vessel=".$cust_list[$custloop]."&comm=".$comm_list_num[$commloop]."&commname=".$comm_list_name[$commloop]."\" target=\"storage_recon_notes.php?vessel=".$cust_list[$custloop]."&comm=".$comm_list_num[$commloop]."&commname=".$comm_list_name[$commloop]."\">".$notes."&nbsp;</a></td>
									</tr>";
					}
				}
			}
			$color_array = array();
			for($temp = 0; $temp < 6; $temp++){
				if(($total_expected[$temp] - $total_actual[$temp]) > 0){
					$color_array[$temp] = "#FF6666";
				} elseif(($total_expected[$temp] - $total_actual[$temp]) < 0){
					$color_array[$temp] = "#66FF33";
				} else { // its even
					$color_array[$temp] = "#FFFFFF";
				}
			}

			$output .= "<tr>
							<td>TOTAL</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>";
			$output .= "
							<td><nobr>".(0 + $total_expected[0])."</nobr></td>
							<td><nobr>".(0 + $total_actual[0])."</nobr></td>
							<td bgcolor=\"".$color_array[0]."\"><nobr>".(-1 * ($total_expected[0] - $total_actual[0]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[1])."</nobr></td>
							<td><nobr>".(0 + $total_actual[1])."</nobr></td>
							<td bgcolor=\"".$color_array[1]."\"><nobr>".(-1 * ($total_expected[1] - $total_actual[1]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[2])."</nobr></td>
							<td><nobr>".(0 + $total_actual[2])."</nobr></td>
							<td bgcolor=\"".$color_array[2]."\"><nobr>".(-1 * ($total_expected[2] - $total_actual[2]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[3])."</nobr></td>
							<td><nobr>".(0 + $total_actual[3])."</nobr></td>
							<td bgcolor=\"".$color_array[3]."\"><nobr>".(-1 * ($total_expected[3] - $total_actual[3]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[4])."</nobr></td>
							<td><nobr>".(0 + $total_actual[4])."</nobr></td>
							<td bgcolor=\"".$color_array[4]."\"><nobr>".(-1 * ($total_expected[4] - $total_actual[4]))."</nobr></td>
							<td><nobr>".(0 + $total_expected[5])."</nobr></td>
							<td><nobr>".(0 + $total_actual[5])."</nobr></td>
							<td bgcolor=\"".$color_array[5]."\"><nobr>".(-1 * ($total_expected[5] - $total_actual[5]))."</nobr></td>";
			$output .= "
							<td>&nbsp;</td>
						</tr>";

			$output .= "</table>";

			fwrite($handle2, $output);

			echo $output;
		}

		fclose($handle2);
		$xls_link = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b><a href=\"/".$reference_loc."\">Click Here for the Excel File</a></b></font></td>
							</tr>
						</table><br><br>";
		echo $xls_link;
	}

	
	include("pow_footer.php");

























function DisplayDetailHyperlink($display_string, $cycle, $detail, $vessel, $comm, $cust, $rec_type, $rfconn){

	if($display_string == "N/A"){
		return $display_string;
	}
	if($display_string == 0){
		return "0";
	}

	$sql = "SELECT COUNT(*) THE_COUNT
			FROM LU_STORAGE_NODISPLAY_COMM
			WHERE BNI_COMM = '".$comm."'";
//	echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
//	echo "Count: ".ociresult($stid, "THE_COUNT")."<br>";
	if(ociresult($stid, "THE_COUNT") >= 1){
		return $display_string;
	}

	// that's all of the "noshows".
	// onto an actual hyperlink...

	$return_string = "\"finance_storage_bill_est_pallets.php?detail=".$detail."&cycle=".$cycle."&rec=".$rec_type;
	if($vessel != ""){
		$return_string .= "&vessel=".$vessel;
	}
	if($cust != ""){
		$return_string .= "&cust=".$cust;
	}
	if($comm != ""){
		$return_string .= "&comm=".$comm;
	}
	$return_string .= "\"";
	$return_string = "<a href=".$return_string." target=".$return_string.">".$display_string."</a>";

	return $return_string;
}
?>
