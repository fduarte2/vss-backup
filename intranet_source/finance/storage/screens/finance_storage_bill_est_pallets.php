<?

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

	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$rec = $HTTP_GET_VARS['rec'];
	$cycle = $HTTP_GET_VARS['cycle'];
	$detail = $HTTP_GET_VARS['detail'];
//	echo "vessel: ".$vessel."<br>";

	$this_season_year = date('Y', mktime(0,0,0,date('m')-9,date('d'),date('Y')));

	$vescomm_array = array();
	$sql = "SELECT DISTINCT ARRIVAL_NUM || ',' || BNI_COMM THE_VALUE
			FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
			WHERE DATE_RECEIVED IS NOT NULL
				AND COMMODITY_CODE = RTBC.RF_COMM
				AND CT.WAREHOUSE_LOCATION != 'SW'";
	if($vessel != ""){
//		echo "vessel: ".$vessel."<br>";
		$sql .= " AND ARRIVAL_NUM = '".$vessel."'";
	}
	if($cust != ""){
		$sql .= " AND RECEIVER_ID = '".$cust."'";
	}
	if($comm != ""){
		$sql .= " AND RTBC.BNI_COMM = '".$comm."'";
	}
	if($rec != ""){
		if($rec == "V"){
			$rec_CT = "S";
		} else {
			$rec_CT = $rec;
		}
		$sql .= " AND RECEIVING_TYPE = '".$rec_CT."'";
	}
	$sql .= "ORDER BY ARRIVAL_NUM || ',' || BNI_COMM";
//	echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){ 
		array_push($vescomm_array, ociresult($stid, "THE_VALUE"));
	}
	$date_bill_start = array();
	$date_bill_end = array();
	
	foreach($vescomm_array as $value){
		// function doesn't return anything, hence a "bogus" variable"
		$bogus = PopulateDurationRage($value, $cycle, $rec, $date_bill_start, $date_bill_end, $rfconn, $bniconn);
	}

	// now, populate 2 arrays:  One with "expected" pallets, and one with "actual" pallets
	$billed_pallet_list = array();
	$billed_cust_list = array();
	$conjoined_billed_array = array();
	$act_count = 0;
	$expected_pallet_list = array();
	$expected_cust_list = array();
	$conjoined_expected_array = array();
	$exp_count = 0;
	foreach($vescomm_array as $value){
		list($local_vessel,$comm) = explode(",", $value);


		$sql = "SELECT LR_NUM FROM LR_CONVERSION
				WHERE OPT_ARRIVAL_NUM = '".$local_vessel."'";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			$orig_ves = $local_vessel;
		} else {
			$orig_ves = ociresult($stid, "LR_NUM");
		}

		// actually billed array
		$sql = "SELECT PALLET_ID, CUSTOMER_ID
				FROM RF_BILLING_DETAIL
				WHERE SERVICE_STATUS != 'DELETED'
					AND SUM_BILL_NUM IN
						(SELECT BILLING_NUM
						FROM RF_BILLING
						WHERE SERVICE_DATE >= TO_DATE('".$date_bill_start[$value]."', 'MM/DD/YYYY')
							AND SERVICE_DATE < TO_DATE('".$date_bill_end[$value]."', 'MM/DD/YYYY')
							AND ARRIVAL_NUM = '".$orig_ves."'
							AND COMMODITY_CODE = '".$comm."'";
		if($cust != ""){
			$sql .= " AND CUSTOMER_ID = '".$cust."'";
		}
		$sql .= " AND SERVICE_STATUS = 'INVOICED')
				ORDER BY CUSTOMER_ID, PALLET_ID";
//		echo $sql."<br>";
		$actual = ociparse($rfconn, $sql);
		ociexecute($actual);
		while(ocifetch($actual)){
			$conjoined_billed_array[$act_count] = ociresult($actual, "PALLET_ID").";".ociresult($actual, "CUSTOMER_ID");
			$billed_pallet_list[$act_count] = ociresult($actual, "PALLET_ID");
			$billed_cust_list[$act_count] = ociresult($actual, "CUSTOMER_ID");
			$act_count++;
		}


		//"expected" billed array
		$sql = "SELECT CT.PALLET_ID, CT.RECEIVER_ID 
				FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
				WHERE CT.ARRIVAL_NUM = '".$local_vessel."'
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.DATE_RECEIVED IS NOT NULL
					AND CT.WAREHOUSE_LOCATION != 'SW'
					AND CT.RECEIVER_ID != '453'";
		if($cust != ""){
			$sql .= " AND CT.RECEIVER_ID = '".$cust."'";
		}
		$sql .= "	AND CT.RECEIVING_TYPE = '".$rec_CT."'
					AND CT.COMMODITY_CODE = RTBC.RF_COMM
					AND RTBC.BNI_COMM = '".$comm."'
					AND (CTAD.SHIPOUTTIME IS NULL 
							OR CTAD.SHIPOUTTIME > TO_DATE('".$date_bill_start[$value]."', 'MM/DD/YYYY')
						)
				ORDER BY RECEIVER_ID, PALLET_ID";
//		echo $sql."<br>";
		$expec = ociparse($rfconn, $sql);
		ociexecute($expec);
		while(ocifetch($expec)){
			$conjoined_expected_array[$exp_count] = ociresult($expec, "PALLET_ID").";".ociresult($expec, "RECEIVER_ID");
			$expected_pallet_list[$exp_count] = ociresult($expec, "PALLET_ID");
			$expected_cust_list[$exp_count] = ociresult($expec, "RECEIVER_ID");
			$exp_count++;
		}
//		print_r($expected_pallet_list);
	}




?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3"><b>Chosen Attributes:
<?
	if($vessel != ""){
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vessel: ".$vessel."<br>";
	}
	if($cust != ""){
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer: ".$cust."<br>";
	}
	if($comm != ""){
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Commodity: ".$comm."<br>";
	}
	if($rec != ""){
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receive Type: ".$rec."<br>";
	}
?>
			<br>Report Created At <? echo date('m/d/Y h:i:s'); ?>
			</b></td>
	</tr>
<? 
	if($detail == "actual"){
?>
	<tr>
		<td colspan="3"><font size="2"><b>BILLED PALLETS for billing cycle #<? echo $cycle; ?></b></font></td>
	</tr>
	<tr>
		<td><b>#</b></td>
		<td><b>Customer</b></td>
		<td><b>Pallet#</b></td>
	</tr>
<?
		$counter = 1;
		for($temp = 0; $temp < $act_count; $temp++){
?>
	<tr>
		<td><font size="2"><? echo $counter; ?></font></td>
		<td><font size="2"><? echo $billed_cust_list[$temp]; ?></font></td>
		<td><font size="2"><? echo $billed_pallet_list[$temp]; ?></font></td>
	</tr>
<?
			$counter++;
		}
	} elseif($detail == "expected"){
?>
	<tr>
		<td colspan="3"><font size="2"><b>EXPECTED PALLETS for billing cycle #<? echo $cycle; ?></b></font></td>
	</tr>
	<tr>
		<td><b>#</b></td>
		<td><b>Customer</b></td>
		<td><b>Pallet#</b></td>
	</tr>
<?
		$counter = 1;
		for($temp = 0; $temp < $exp_count; $temp++){
?>
	<tr>
		<td><font size="2"><? echo $counter; ?></font></td>
		<td><font size="2"><? echo $expected_cust_list[$temp]; ?></font></td>
		<td><font size="2"><? echo $expected_pallet_list[$temp]; ?></font></td>
	</tr>
<?
			$counter++;
		}
	} else { // "both" was chosen
?>
	<tr>
		<td colspan="3"><font size="2"><b>BILLED BUT NOT EXPECTED for billing cycle #<? echo $cycle; ?></b></font></td>
	</tr>
	<tr>
		<td><b>#</b></td>
		<td><b>Customer</b></td>
		<td><b>Pallet#</b></td>
	</tr>
<?
		$counter = 1;
		for($temp = 0; $temp < $act_count; $temp++){
			if(!in_array($conjoined_billed_array[$temp], $conjoined_expected_array)){
?>
	<tr>
		<td><font size="2"><? echo $counter; ?></font></td>
		<td><font size="2"><? echo $billed_cust_list[$temp]; ?></font></td>
		<td><font size="2"><? echo $billed_pallet_list[$temp]; ?></font></td>
	</tr>
<?
				$counter++;
			}
		}
?>
	<tr>
		<td colspan="3">&nbsp;<br>&nbsp;<br>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3"><font size="2"><b>EXPECTED BUT NOT BILLED for billing cycle #<? echo $cycle; ?></b></font></td>
	</tr>
	<tr>
		<td><b>#</b></td>
		<td><b>Customer</b></td>
		<td><b>Pallet#</b></td>
	</tr>
<?
		$counter = 1;
		for($temp = 0; $temp < $exp_count; $temp++){
			if(!in_array($conjoined_expected_array[$temp], $conjoined_billed_array)){
?>
	<tr>
		<td><font size="2"><? echo $counter; ?></font></td>
		<td><font size="2"><? echo $expected_cust_list[$temp]; ?></font></td>
		<td><font size="2"><? echo $expected_pallet_list[$temp]; ?></font></td>
	</tr>
<?
				$counter++;
			}
		}
	}
?>
</table>
<?




















function PopulateDurationRage($vessel_comm, $cycle, $rate_arv, &$date_bill_start, &$date_bill_end, $rfconn, $bniconn){
	list($vessel,$comm) = explode(",", $vessel_comm);

	$sql = "SELECT LR_NUM FROM LR_CONVERSION
			WHERE OPT_ARRIVAL_NUM = '".$vessel."'";
//	echo $sql."<br>";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		$orig_ves = $vessel;
	} else {
		$orig_ves = ociresult($stid, "LR_NUM");
	}


	$duration_array = array(0);
	for($temp = 1; $temp <= 6; $temp++){
		// check for vessel-specific rate first
		$sql = "SELECT RATE, BILLDURATION, FREEDAYS
				FROM RATE
				WHERE ARRIVALNUMBER = '".$orig_ves."'
					AND COMMODITYCODE = '".$comm."'
					AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
					AND (ARRIVALTYPE = '".$rate_arv."' OR ARRIVALTYPE = 'A')
					AND SCANNEDORUNSCANNED = 'SCANNED'
					AND RATE > 0
				ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
//		echo $sql."<br>";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		if(ocifetch($stid)){
			$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
		} else {
			// no vessel-specific rate, get generic all-vessel

			$sql = "SELECT BILLDURATION, FREEDAYS
					FROM RATE
					WHERE COMMODITYCODE = '".$comm."'
						AND RATESTARTDATE <= '".$duration_array[($temp - 1)]."'
						AND (ARRIVALTYPE = '".$rate_arv."' OR ARRIVALTYPE = 'A')
						AND SCANNEDORUNSCANNED = 'SCANNED'
						AND RATE > 0
					ORDER BY RATESTARTDATE DESC, RATEPRIORITY DESC";
//			echo $sql."<br>";
			$stid = ociparse($bniconn, $sql);
			ociexecute($stid);
			ocifetch($stid);
			$duration_array[$temp] = $duration_array[($temp - 1)] + ociresult($stid, "BILLDURATION");
		}
	}
	// wherever we got the data from (you'll notice I used the same "cursor" in both cases", grab the freetime.
	$freetime = ociresult($stid, "FREEDAYS");

	$free_time_starts = "";
	$billing_starts = "";
	$sql = "SELECT TO_CHAR(FREE_TIME_START, 'MM/DD/YYYY') FREE_TIME, 
				TO_CHAR(FREE_TIME_START + ".$freetime.", 'MM/DD/YYYY') FIRST_BILL_DATE
			FROM VOYAGE_FROM_BNI
			WHERE LR_NUM = '".$orig_ves."'";
//	echo $sql."<br>";
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
					AND RTBC.BNI_COMM = '".$comm."'
					AND CT.ARRIVAL_NUM = '".$orig_ves."'
					AND CT.RECEIVING_TYPE = '".$rate_arv."'
					AND CT.DATE_RECEIVED IS NOT NULL";
//		echo $sql."<br>";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$free_time_starts = ociresult($stid, "FREE_TIME");
		$billing_starts = ociresult($stid, "FIRST_BILL_DATE");
	}

	$temp_date = explode("/", $billing_starts);
//	print_r($temp_date)."<br>";
//	print_r($duration_array)."<br>";
	
	$date_bill_start[$vessel_comm] = date('m/d/Y', mktime(0,0,0,$temp_date[0], $temp_date[1] + $duration_array[$cycle - 1], $temp_date[2]));
	$date_bill_end[$vessel_comm] = date('m/d/Y', mktime(0,0,0,$temp_date[0], $temp_date[1] + $duration_array[$cycle], $temp_date[2]));

//	echo "datestart ".date('m/d/Y', mktime(0,0,0,$temp_date[0], $temp_date[1] + $duration_array[$cycle - 1], $temp_date[2]))."<br>";
//	echo $vessel_comm." datestart ".$date_bill_start[$vessel_comm]."<br>";
//	echo $vessel_comm." dateend ".$date_bill_end[$vessel_comm]."<br>";

}