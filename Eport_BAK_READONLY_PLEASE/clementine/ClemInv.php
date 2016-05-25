<?
/*
*	Adam Walter, October 2007.
*
*	This page displays a list of clementines per ship;
*	broken down into:
*		What we expect from Morocco
*		What we actually got from Morocco
*		What we've shipped so far off that vessel
*		What remains off that vessel
*			In good condition
*			Regraded (R)
*			Hospital (H)
*			Both Regrade and Hospital (B)
*
*	Hyperlinks are provided for non-0 fields for exact pallet ID's.
*******************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$Outer_Cursor = ora_open($conn);
	$Inner_Cursor = ora_open($conn);

	$size_array = array();
	$vessel_num = $HTTP_POST_VARS['vessel_num'];
	$comm = $HTTP_POST_VARS['comm'];
	$raw_cust = $HTTP_POST_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}
	$submit = $HTTP_POST_VARS['submit'];
	$bad_message = "";
	// $eport_customer_id comes from index.php

	if($eport_customer_id == 0){
//		$extra_sql1 = "";
		$extra_sql2 = "";
	} else {
//		$extra_sql1 = "AND RECEIVER_ID = '".$eport_customer_id."'";
		$extra_sql2 = "AND CUSTOMER_ID = '".$eport_customer_id."'";
	}

	
	if($submit == "Retrieve"){
		$index = 0;
		// populate an array with possible sizes
		// union of DC_CARGO_SUMMARY's sizes (what they say they sent us)
		// and CARGO_TRACKING's sizes (what we actually got)

		$sql = "SELECT DISTINCT DC_CARGO_DESC THE_SIZE FROM
				(SELECT CARGO_SIZE AS DC_CARGO_DESC FROM SAG_OWNER.DC_CARGO_SUMMARY WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."'
				UNION
				SELECT DC_CARGO_DESC FROM SAG_OWNER.DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND RECEIVER_ID = '".$cust."')
				ORDER BY THE_SIZE";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$bad_message = "There are no records for this commodity on this vessel; neither expected nor received";
			// echo $bad_message;
		} else {
			do {
				$size_array[$index] = $Short_Term_row['THE_SIZE'];
				// echo $Short_Term_row['THE_SIZE']."<BR>";
				$index++;
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Clementine Inventory</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="data_pick" action="index.php" method="post">
	<tr>
		<td width="30%" align="left"><font size="3" face="Verdana">Vessel:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="vessel_num">
<?
	$sql = "SELECT DISTINCT CT.ARRIVAL_NUM THE_NUM, VP.VESSEL_NAME THE_VES FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = VP.LR_NUM AND CT.COMMODITY_CODE LIKE '560%' AND LENGTH(PALLET_ID) = 30 AND LENGTH(EXPORTER_CODE) = 4 AND WEIGHT_UNIT IS NOT NULL ORDER BY THE_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['THE_NUM']; ?>"<? if($Short_Term_row['THE_NUM'] == $vessel_num){ ?> selected <? } ?>><? echo $Short_Term_row['THE_NUM']." - ".$Short_Term_row['THE_VES']; ?></option>
<?
	}
?>
			</select></td>
		<td width="5%">&nbsp;</td>
		<td width="30%" align="center"><font size="3" face="Verdana">Customer:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="cust">
<?
	$sql = "SELECT * FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM EPORT_LOGIN WHERE USER_TYPE = 'CLEMENTINE' AND CUSTOMER_ID != 0)
			".$extra_sql2."
			ORDER BY CUSTOMER_ID";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// we gotta do something strange for oppy-439, so...
		if($Short_Term_row['CUSTOMER_ID'] != '439'){
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?></option>
<?
		} else {
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($raw_cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - NO OPPY</option>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>O" <? if($raw_cust == "439O"){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - OPPY ONLY</option>
<?
		}
	}
?>
			</select></td>
		<td width="5%">&nbsp;</td>
		<td width="30%" align="right"><font size="3" face="Verdana">Commodity:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="comm">
<?
	$sql = "SELECT * FROM DC_EPORT_COMMODITY ORDER BY PORT_COMMODITY_CODE";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['PORT_COMMODITY_CODE']; ?>" <? if($comm == $Short_Term_row['PORT_COMMODITY_CODE']){ ?> selected <? } ?>><? echo $Short_Term_row['PORT_COMMODITY_CODE']." - ".$Short_Term_row['DC_COMMODITY_NAME']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" align="center"><input name="submit" type="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td colspan="5"><BR><HR></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
		if($bad_message != ""){
			// bad selection
?>
<table border="0" width="100%" cellpadding="2" cellspacing="0"> 
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000"><? echo $bad_message; ?></font><HR></td>
	</tr>
</table>
<?
		} else {
			$m_p_e_total = 0;
			$m_c_e_total = 0;
			$m_p_s_total = 0;
			$m_c_s_total = 0;
			$a_d_p_total = 0;
			$a_d_c_total = 0;
			$p_p_r_total = 0;
			$p_c_r_total = 0;
			$p_s_total = 0;
			$c_s_total = 0;
			$r_g_p_total = 0;
			$r_r_p_total = 0;
			$r_h_p_total = 0;
			$r_b_p_total = 0;
			$r_t_p_total = 0;
			$r_g_c_total = 0;
			$r_r_c_total = 0;
			$r_h_c_total = 0;
			$r_b_c_total = 0;
			$r_t_c_total = 0;
?>
<table border="1" width="100%" cellpadding="2" cellspacing="0"> 
	<!-- table headers !-->
	<tr>
		<td>
			<th rowspan="2"></th>
<!--			<th colspan="2"><font size="2" face="Verdana">Moroccan Expected</font></th> !-->
			<th colspan="2"><font size="2" face="Verdana">Moroccan Scanned*</font></th>
			<th colspan="2"><font size="2" face="Verdana">Awaiting Discharge</font></th>
			<th colspan="2"><font size="2" face="Verdana">Port Received</font></th>
			<th colspan="2"><font size="2" face="Verdana">Shipped</font></th>
			<th colspan="2"><font size="2" face="Verdana">Good</font></th>
			<th colspan="2"><font size="2" face="Verdana">Regrade</font></th>
			<th colspan="2"><font size="2" face="Verdana">Hospital</font></th>
			<th colspan="2"><font size="2" face="Verdana">Both</font></th>
			<th colspan="2"><font size="2" face="Verdana">Total</font></th>
		</td>
	</tr>
	<tr>
		<td>
<!--			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th> !-->
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
			<th><font size="2" face="Verdana">Pallets</font></th>
			<th><font size="2" face="Verdana">Cartons</font></th>
		</td>
	</tr>
	<!-- end table headers !-->
<?
			for($i = 0; $i < sizeof($size_array); $i++){
				// get data, store for later display
/*				$sql = "SELECT NVL(SUM(EXPECTED_PALLET), 0) PAL_TOTAL, NVL(SUM(EXPECTED_CASES), 0) CASE_TOTAL, TO_NUMBER(CARGO_SIZE) FROM DC_CARGO_SUMMARY WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND TO_NUMBER(CARGO_SIZE) = '".$size_array[$i]."' GROUP BY TO_NUMBER(CARGO_SIZE)";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$moroccan_pallets_expected = $Short_Term_row['PAL_TOTAL'];
					$moroccan_cases_expected = $Short_Term_row['CASE_TOTAL'];
					$m_p_e_total += $moroccan_pallets_expected;
					$m_c_e_total += $moroccan_cases_expected;
				} else {
					$moroccan_pallets_expected = 0;
					$moroccan_cases_expected = 0;
				} */

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(BATCH_ID), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND HATCH IS NOT NULL AND UPPER(HATCH) != 'XX' AND MANIFESTED = 'Y' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$moroccan_pallet_shipped = $Short_Term_row['THE_PALLETS'];
					$moroccan_cases_shipped = $Short_Term_row['THE_CASES'];
					$m_p_s_total += $moroccan_pallet_shipped;
					$m_c_s_total += $moroccan_cases_shipped;
				} else {
					$moroccan_pallet_shipped = 0;
					$moroccan_cases_shipped = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND DATE_RECEIVED IS NULL AND MANIFESTED = 'Y' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$awaiting_discharge_pallet = $Short_Term_row['THE_PALLETS'];
					$awaiting_discharge_case = $Short_Term_row['THE_CASES'];
					$a_d_p_total += $awaiting_discharge_pallet;
					$a_d_c_total += $awaiting_discharge_case;
				} else {
					$awaiting_discharge_pallet = 0;
					$awaiting_discharge_case = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$port_pallet_received = $Short_Term_row['THE_PALLETS'];
					$port_cases_received = $Short_Term_row['THE_CASES'];
					$p_p_r_total += $port_pallet_received;
					$p_c_r_total += $port_cases_received;
				} else {
					$port_pallet_received = 0;
					$port_cases_received = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED - QTY_IN_HOUSE), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND MARK = 'SHIPPED' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_shipped = $Short_Term_row['THE_PALLETS'];
					$cases_shipped = $Short_Term_row['THE_CASES'];
					$p_s_total += $pallet_shipped;
					$c_s_total += $cases_shipped;
				} else {
					$pallet_shipped = 0;
					$cases_shipped = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS IS NULL AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$good_pallets_remain = $Short_Term_row['THE_PALLETS'];
					$good_cases_remain = $Short_Term_row['THE_CASES'];
					$r_g_p_total += $good_pallets_remain;
					$r_g_c_total += $good_cases_remain;
				} else {
					$good_pallets_remain = 0;
					$good_cases_remain = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS = 'R' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$regrade_pallets_remain = $Short_Term_row['THE_PALLETS'];
					$regrade_cases_remain = $Short_Term_row['THE_CASES'];
					$r_r_p_total += $regrade_pallets_remain;
					$r_r_c_total += $regrade_cases_remain;
				} else {
					$regrade_pallets_remain = 0;
					$regrade_cases_remain = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS = 'H' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$hospital_pallets_remain = $Short_Term_row['THE_PALLETS'];
					$hospital_cases_remain = $Short_Term_row['THE_CASES'];
					$r_h_p_total += $hospital_pallets_remain;
					$r_h_c_total += $hospital_cases_remain;
				} else {
					$hospital_pallets_remain = 0;
					$hospital_cases_remain = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND (MARK IS NULL OR MARK != 'SHIPPED') AND CARGO_STATUS = 'B' AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$both_pallets_remain = $Short_Term_row['THE_PALLETS'];
					$both_cases_remain = $Short_Term_row['THE_CASES'];
					$r_b_p_total += $both_pallets_remain;
					$r_b_c_total += $both_cases_remain;
				} else {
					$both_pallets_remain = 0;
					$both_cases_remain = 0;
				}

				$sql = "SELECT NVL(COUNT(*), 0) THE_PALLETS, NVL(SUM(QTY_RECEIVED), 0) THE_CASES, DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND DC_CARGO_DESC = '".$size_array[$i]."' AND RECEIVER_ID = '".$cust."'".$sub_sql." AND (MARK IS NULL OR MARK != 'SHIPPED') AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007' GROUP BY DC_CARGO_DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$total_pallets_remain = $Short_Term_row['THE_PALLETS'];
					$total_cases_remain = $Short_Term_row['THE_CASES'];
					$r_t_p_total += $total_pallets_remain;
					$r_t_c_total += $total_cases_remain;
				} else {
					$total_pallets_remain = 0;
					$total_cases_remain = 0;
				}




?>
	<tr>
		<th colspan="2"><font size="3" face="Verdana"><? echo $size_array[$i]; ?></font></th>
<!--		<td align="right"><font size="2" face="Verdana"><a href="detailsvessel<? echo $vessel_num; ?>.xls" target="detailsvessel<? echo $vessel_num; ?>.xls"><? echo number_format($moroccan_pallets_expected); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="detailsvessel<? echo $vessel_num; ?>.xls" target="detailsvessel<? echo $vessel_num; ?>.xls"><? echo number_format($moroccan_cases_expected); ?></a></font></td> !-->
<!--		<td align="right"><font size="2" face="Verdana"><a href="ShippedVessel<? echo $vessel_num; ?>.txt" target="ShippedVessel<? echo $vessel_num; ?>.txt"><? echo number_format($moroccan_pallet_shipped); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="ShippedVessel<? echo $vessel_num; ?>.txt" target="ShippedVessel<? echo $vessel_num; ?>.txt"><? echo number_format($moroccan_cases_shipped); ?></a></font></td> !-->
		<td align="right"><font size="2" face="Verdana"><? echo number_format($moroccan_pallet_shipped); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($moroccan_cases_shipped); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=AWAITING&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=AWAITING&damage_status=no"><? echo number_format($awaiting_discharge_pallet); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=AWAITING&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=AWAITING&damage_status=no"><? echo number_format($awaiting_discharge_case); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=RECEIVED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=RECEIVED&damage_status=no"><? echo number_format($port_pallet_received); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=RECEIVED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=RECEIVED&damage_status=no"><? echo number_format($port_cases_received); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=SHIPPED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=SHIPPED&damage_status=no"><? echo number_format($pallet_shipped); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=SHIPPED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=SHIPPED&damage_status=no"><? echo number_format($cases_shipped); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=good" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=good"><? echo number_format($good_pallets_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=good" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=good"><? echo number_format($good_cases_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=R" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=R"><? echo number_format($regrade_pallets_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=R" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=R"><? echo number_format($regrade_cases_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=H" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=H"><? echo number_format($hospital_pallets_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=H" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=H"><? echo number_format($hospital_cases_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=B" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=B"><? echo number_format($both_pallets_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=B" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=B"><? echo number_format($both_cases_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=all" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=all"><? echo number_format($total_pallets_remain); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=all" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=<? echo $size_array[$i]; ?>&type=REMAIN&damage_status=all"><? echo number_format($total_cases_remain); ?></a></font></td>
	</tr>
<?
			}
?>
	<tr>
		<td colspan="20">&nbsp;</td>
	</tr>
	<tr>
		<th colspan="2"><font size="3" face="Verdana">Totals:</font></th>
<!--		<td align="right"><font size="2" face="Verdana"><a href="detailsvessel<? echo $vessel_num; ?>.xls"><? echo number_format($m_p_e_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="detailsvessel<? echo $vessel_num; ?>.xls"><? echo number_format($m_c_e_total); ?></a></font></td> !-->
<!--		<td align="right"><font size="2" face="Verdana"><a href="ShippedVessel<? echo $vessel_num; ?>.txt" target="ShippedVessel<? echo $vessel_num; ?>.txt"><? echo number_format($m_p_s_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="ShippedVessel<? echo $vessel_num; ?>.txt" target="ShippedVessel<? echo $vessel_num; ?>.txt"><? echo number_format($m_c_s_total); ?></a></font></td> !-->
		<td align="right"><font size="2" face="Verdana"><? echo number_format($m_p_s_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($m_c_s_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=AWAITING&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=AWAITING&damage_status=no"><? echo number_format($a_d_p_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=AWAITING&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=AWAITING&damage_status=no"><? echo number_format($a_d_c_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=RECEIVED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=RECEIVED&damage_status=no"><? echo number_format($p_p_r_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=RECEIVED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=RECEIVED&damage_status=no"><? echo number_format($p_c_r_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=SHIPPED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=SHIPPED&damage_status=no"><? echo number_format($p_s_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=SHIPPED&damage_status=no" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=SHIPPED&damage_status=no"><? echo number_format($c_s_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=good" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=good"><? echo number_format($r_g_p_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=good" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=good"><? echo number_format($r_g_c_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=R" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=R"><? echo number_format($r_r_p_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=R" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=R"><? echo number_format($r_r_c_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=H" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=H"><? echo number_format($r_h_p_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=H" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=H"><? echo number_format($r_h_c_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=B" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=B"><? echo number_format($r_b_p_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=B" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=B"><? echo number_format($r_b_c_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=all" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=all"><? echo number_format($r_t_p_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=all" target="pallet_display.php?vessel=<? echo $vessel_num; ?>&cust=<? echo $raw_cust; ?>&comm=<? echo $comm; ?>&size=ALL&type=REMAIN&damage_status=all"><? echo number_format($r_t_c_total); ?></a></font></td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td align="left"><font size="2" face="Verdana">* File sent from FFM of pallets scanned at Agadir onto the vessel.  This file also includes pallets NOT scanned, which correspond to rows that are missing date/time scanned.  Such rows are easy to spot; they are shorter than the other rows and have a hatch/deck of xx.</font><BR><BR></td>
	</tr>
</table>
<?
		}
	}
?>