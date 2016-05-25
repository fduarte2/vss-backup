<?
/*
*	Adam Walter, Jan 2013
*
*	Sub-page of the Expected Finance bill report page.
*	Generates per-arv# report for a selected customer.
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

	$cust = $HTTP_GET_VARS['cust'];
	$arv = $HTTP_GET_VARS['arv'];
	$comm = $HTTP_GET_VARS['comm'];
	if($arv == "T"){
		$arvname = "Truck";
	} else {
		$arvname = "Transfer";
	}

	$this_season_year = date('Y', mktime(0,0,0,date('m')-9,date('d'),date('Y')));
//	$this_season_year = date('Y', mktime(0,0,0,date('m')-9,date('d'),date('Y') - 1));


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Scanned-Storage Customer Non-vessel Breakdown
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($cust != "" && $arv != ""){
		// that should ALWAYS be true, but ya know...
		$filename2 = "StorageReconNon-vesCust".$cust."on".date('mdYhis').".xls";
		$reference_loc = "temp/".$filename2;
		$fullpath = "/web/web_pages/".$reference_loc;
		$handle2 = fopen($fullpath, "w");

		$output = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
					<tr>
						<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Report Created At ".date('m/d/Y h:i:s')."</b></font></td>
					</tr>
				</table><br><br>";
		fwrite($handle2, $output);
		echo $output;

		$ves_list = array();
//			$arvtype_list = array();
		$total_expected = array();
		$total_actual = array();

		$total_col_d = 0;
		$total_col_e = 0;
		$total_col_f = 0;
		$total_col_g = 0;
		$total_col_h = 0;

		$output = "<table border=\"1\">
						<tr><td colspan=\"26\" align=\"center\"><b>Customer ".$cust." Commodity ".$comm." Receiving Type ".$arvname."</b></td></tr>
						<tr bgcolor=\"#FFCC66\">
							<td>Arrival Number</td>
							<td>Arrival Date</td>
							<td>Pallets Received</td>
							<td>Free Time Starts</td>
							<td>Pallets Shipped Prior to start of Free Time</td>
							<td>Pallets Present at start of Free Time</td>
							<td>Pallets Shipped During Free Time</td>
							<td>Free Time Ends</td>
							<td colspan=\"3\">1st Storage Period</td>
							<td colspan=\"3\">2nd Storage Period</td>
							<td colspan=\"3\">3rd Storage Period</td>
							<td colspan=\"3\">4th Storage Period</td>
							<td colspan=\"3\">5th Storage Period</td>
							<td colspan=\"3\">6th+ Storage Period</td>
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
						</tr>";

		$sql = "SELECT DISTINCT ARRIVAL_NUM 
				FROM CARGO_TRACKING
				WHERE DATE_RECEIVED >= '01-sep-".$this_season_year."'
					AND RECEIVING_TYPE = '".$arv."'
					AND RECEIVER_ID = '".$cust."'
					AND COMMODITY_CODE IN
						(SELECT RF_COMM FROM RF_TO_BNI_COMM WHERE BNI_COMM = '".$comm."')
				ORDER BY ARRIVAL_NUM";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
			array_push($ves_list, ociresult($stid, "ARRIVAL_NUM"));
		}

		for($vesloop = 0; $vesloop < sizeof($ves_list); $vesloop++){
			$duration_array = array(0);
			for($temp = 1; $temp <= 6; $temp++){
				$sql = "SELECT RATE, BILLDURATION, FREEDAYS
						FROM RATE
						WHERE ARRIVALNUMBER = '".$ves_list[$vesloop]."'
							AND COMMODITYCODE = '".$comm."'
							AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
							AND (ARRIVALTYPE = '".$arv."' OR ARRIVALTYPE = 'A')
							AND SCANNEDORUNSCANNED = 'SCANNED'
						ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid);
				if(ocifetch($stid)){
					$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
				} else {
					// no vessel-specific rate, get generic all-vessel

					$sql = "SELECT RATE, BILLDURATION, FREEDAYS
							FROM RATE
							WHERE COMMODITYCODE = '".$comm."'
								AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
								AND (ARRIVALTYPE = '".$arv."' OR ARRIVALTYPE = 'A')
								AND SCANNEDORUNSCANNED = 'SCANNED'
							ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
//					echo $sql."<br>";
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
							AND RTBC.BNI_COMM = '".$comm."'
							AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
							AND CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
							AND CT.RECEIVING_TYPE = '".$arv."'
							AND CT.RECEIVER_ID = '".$cust."'
							AND CT.DATE_RECEIVED IS NOT NULL";
//				echo $sql."<br>";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$total_for_ves_scanned = ociresult($stid, "THE_PALLETS");
				$total_col_d += $total_for_ves_scanned;

				$free_time_starts = "";
				$billing_starts = "";
				$sql = "SELECT TO_CHAR(MAX(DATE_RECEIVED), 'MM/DD/YYYY') FREE_TIME,
							TO_CHAR(MAX(DATE_RECEIVED + ".$freetime."), 'MM/DD/YYYY') FIRST_BILL_DATE
						FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
						WHERE CT.COMMODITY_CODE = RTBC.RF_COMM
							AND RTBC.BNI_COMM = '".$comm."'
							AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
							AND CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
							AND CT.RECEIVING_TYPE = '".$arv."'
							AND CT.RECEIVER_ID = '".$cust."'
							AND CT.DATE_RECEIVED IS NOT NULL";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$free_time_starts = ociresult($stid, "FREE_TIME");
				$billing_starts = ociresult($stid, "FIRST_BILL_DATE");


				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
						WHERE CT.ARRIVAL_NUM = '".$ves_list[$vesloop]."'
							AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
							AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
							AND CT.PALLET_ID = CTAD.PALLET_ID
							AND CT.DATE_RECEIVED IS NOT NULL
							AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
							AND CT.RECEIVER_ID != '453'
							AND CT.RECEIVING_TYPE = '".$arv."'
							AND CT.RECEIVER_ID = '".$cust."'
							AND CT.COMMODITY_CODE = RTBC.RF_COMM
							AND RTBC.BNI_COMM = '".$comm."'
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
							AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
							AND CT.RECEIVER_ID != '453'
							AND CT.RECEIVING_TYPE = '".$arv."'
							AND CT.RECEIVER_ID = '".$cust."'
							AND CT.COMMODITY_CODE = RTBC.RF_COMM
							AND RTBC.BNI_COMM = '".$comm."'
							AND CTAD.SHIPOUTTIME >= TO_DATE('".$free_time_starts."', 'MM/DD/YYYY')
							AND CTAD.SHIPOUTTIME < TO_DATE('".$billing_starts."', 'MM/DD/YYYY')";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$shipped_before_FT_ends = ociresult($stid, "THE_COUNT");

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
								AND CT.DATE_RECEIVED >= '01-sep-".$this_season_year."'
								AND CT.RECEIVER_ID != '453'
								AND CT.RECEIVING_TYPE = '".$arv."'
								AND CT.RECEIVER_ID = '".$cust."'
								AND CT.COMMODITY_CODE = RTBC.RF_COMM
								AND RTBC.BNI_COMM = '".$comm."'
								AND (CTAD.SHIPOUTTIME IS NULL 
										OR CTAD.SHIPOUTTIME > TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp].")";
					$expec = ociparse($rfconn, $sql);
					ociexecute($expec);
					ocifetch($expec);

					$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT
							FROM RF_BILLING_DETAIL
							WHERE SERVICE_STATUS != 'DELETED'
								AND SUM_BILL_NUM IN
									(SELECT BILLING_NUM
									FROM RF_BILLING
									WHERE SERVICE_DATE >= TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp]."
										AND SERVICE_DATE < TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[($temp + 1)]."
										AND ARRIVAL_NUM = '".$ves_list[$vesloop]."'
										AND COMMODITY_CODE = '".$comm."'
										AND CUSTOMER_ID = '".$cust."'
										AND SERVICE_STATUS = 'INVOICED')";
					$actual = ociparse($rfconn, $sql);
					ociexecute($actual);
					ocifetch($actual);

					$sql = "SELECT DECODE(LEAST(SYSDATE, TO_DATE('".$billing_starts."', 'MM/DD/YYYY') + ".$duration_array[$temp]."), SYSDATE, 1, 0) TO_HIDE FROM DUAL";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid);
					ocifetch($stid);
					if($free_time_starts == ""){
						$expected_pallets_to_bill[$temp] = "N/A";
						$actual_pallets_billed[$temp] = "N/A";
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

				$sql = "SELECT TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_ARV
						FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$ves_list[$vesloop]."'
							AND RECEIVER_ID = '".$cust."'
							AND DATE_RECEIVED IS NOT NULL";
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
				}

				if($billing_starts == ""){
					$billing_starts = "Not Set";
				}

				// now... to the output!
				$output .= "<tr>
								<td><font size=\"2\">".$ves_list[$vesloop]."</td>
								<td><font size=\"2\">".$arvdate."</td>
								<td><font size=\"2\"><nobr>".$total_for_ves_scanned."</nobr></td>";
				$output .= "
								<td><font size=\"2\"><nobr>".$free_time_starts."</nobr></td>
								<td><font size=\"2\"><nobr>".($shipped_before_FT_starts)."</nobr></td>
								<td><font size=\"2\"><nobr>".$line_total_disp."</nobr></td>
								<td><font size=\"2\"><nobr>".($shipped_before_FT_ends)."</nobr></td>
								<td><font size=\"2\"><nobr>".$billing_starts."</nobr></td>";
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
				$output .= "</tr>";
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
						<td><nobr>".$total_col_d."</nobr></td>
						<td>&nbsp;</td>
						<td><nobr>".$total_col_f."</nobr></td>
						<td><nobr>".$total_col_g."</nobr></td>
						<td><nobr>".$total_col_h."</nobr></td>
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
		$output .= "</tr>";

		$output .= "</table>";

		fwrite($handle2, $output);

		echo $output;

		fclose($handle2);
		$xls_link = "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
							<tr>
								<td align=\"center\"><font size=\"3\" face=\"Verdana\"><b><a href=\"/".$reference_loc."\">Click Here for the Excel File</a></b></font></td>
							</tr>
						</table><br><br>";
		echo $xls_link;	
	}

