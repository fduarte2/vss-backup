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
	$Short_Term_Cursor_Inner = ora_open($conn);
	$Outer_Cursor = ora_open($conn);
	$Inner_Cursor = ora_open($conn);

	$var_array = array();
	$vessel_num = $HTTP_POST_VARS['vessel_num'];
	$comm = $HTTP_POST_VARS['comm'];
//	$raw_cust = $HTTP_POST_VARS['cust'];
//	if($raw_cust == "439O"){
//		$cust = "439";
//		$sub_sql = " AND SUB_CUSTID = '1512' ";
//	} else {
//		$cust = $raw_cust;
//		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
//	}

	$cust = "1626";

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

	
	if($submit == "Retrieve/Refresh Data"){
		$index = 0;
		// populate an array with possible sizes
		// union of DC_CARGO_SUMMARY's sizes (what they say they sent us)
		// and CARGO_TRACKING's sizes (what we actually got)

/*		$sql = "SELECT DISTINCT DC_CARGO_DESC THE_SIZE FROM
				(SELECT CARGO_SIZE AS DC_CARGO_DESC FROM SAG_OWNER.DC_CARGO_SUMMARY WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."'
				UNION
				SELECT DC_CARGO_DESC FROM SAG_OWNER.DC_CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."' AND COMMODITY_CODE = '".$comm."' AND RECEIVER_ID = '".$cust."')
				ORDER BY THE_SIZE"; */
		$sql = "SELECT DISTINCT VARIETY  
				FROM CARGO_TRACKING 
				WHERE ARRIVAL_NUM = '".$vessel_num."' 
					AND RECEIVER_ID = '1626'
					AND COMMODITY_CODE = '".$comm."'
				ORDER BY VARIETY";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$bad_message = "There are no records for this commodity on this vessel; neither expected nor received";
			// echo $bad_message;
		} else {
			do {
				$var_array[$index] = $Short_Term_row['VARIETY'];
				// echo $Short_Term_row['THE_SIZE']."<BR>";
				$index++;
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Argentine Fruit Inventory</font><font size="4" face="Verdana" color="#0066CC"> - Last Refreshed At <? echo date('m/d/Y h:i:s a', mktime(date('G')-3,date('i'),date('s'),date('m'),date('d'),date('Y'))); ?> PST - Click on refresh data button below to reload data.</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="data_pick" action="ArgenFruitInv_index.php" method="post">
	<tr>
		<td width="30%" align="left"><font size="3" face="Verdana">Vessel:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="vessel_num">
<?
//	$sql = "SELECT DISTINCT CT.ARRIVAL_NUM THE_NUM, VP.VESSEL_NAME THE_VES FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE VP.LR_NUM <> '4321' AND CT.ARRIVAL_NUM = VP.LR_NUM AND CT.RECEIVER_ID = '".$cust."' ORDER BY THE_NUM DESC";
	$sql = "SELECT DISTINCT CT.ARRIVAL_NUM THE_NUM, VP.VESSEL_NAME THE_VES FROM CARGO_TRACKING CT, VESSEL_PROFILE VP WHERE CT.ARRIVAL_NUM = VP.LR_NUM AND CT.RECEIVER_ID = '".$cust."' ORDER BY THE_NUM DESC";
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
		<td width="30%" align="center"><font size="3" face="Verdana">Exporter:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="cust">
<?
	$sql = "SELECT * FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$cust."'
			".$extra_sql2."
			ORDER BY CUSTOMER_ID";
//	echo $sql."<br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?></option>
<?
	}
?>
			</select></td>
		<td width="5%">&nbsp;</td>
		<td width="30%" align="right"><font size="3" face="Verdana">Commodity:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="comm">
<?
	$sql = "SELECT COMMODITY_CODE PORT_COMMODITY_CODE, COMMODITY_NAME 
			FROM COMMODITY_PROFILE 
			WHERE COMMODITY_CODE IN (SELECT DISTINCT COMMODITY_CODE FROM CARGO_TRACKING WHERE RECEIVER_ID = '1626')
			ORDER BY PORT_COMMODITY_CODE DESC";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['PORT_COMMODITY_CODE']; ?>" <? if($comm == $Short_Term_row['PORT_COMMODITY_CODE']){ ?> selected <? } ?>><? echo $Short_Term_row['PORT_COMMODITY_CODE']." - ".$Short_Term_row['COMMODITY_NAME']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" align="center"><input name="submit" type="submit" value="Retrieve/Refresh Data"></td>
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

			$d_o_o_total = 0;
			$d_o_c_total = 0;
			$s_o_o_total = 0;
			$s_o_c_total = 0;
			$l_n_o_total = 0;
			$l_n_c_total = 0;
			$t_o_o_total = 0;
			$t_o_c_total = 0;
			$ats_c_total = 0; // available-to-ship
?>
<table border="1" width="100%" cellpadding="2" cellspacing="0"> 
	<!-- table headers !-->
	<tr bgcolor="#FFFF99">
		<td colspan="20" align="center"><b><font size="2" face="Verdana">Legend:  Available To Ship = [Port Received - Shipped] - [Draft Orders + Submitteed Orders + Loading Orders]</font></b></td>
	</tr>
	<tr>
		<td rowspan="2">&nbsp;</td>
		<td colspan="2" align="center"><b><font size="2" face="Verdana">On Vessel</font></b></td>
		<td colspan="2" align="center"><b><font size="2" face="Verdana">Awaiting Discharge</font></b></td>
		<td colspan="2" align="center" bgcolor="#99FF99"><b><font size="2" face="Verdana">Port Received</font></b></td>
		<td colspan="2" align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Shipped</font></b></td>
		<td colspan="2" align="center"><b><font size="2" face="Verdana">At Port</font></b></td>
		<td colspan="2" align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Draft Orders</font></b></td>
		<td colspan="2" align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Submitted Orders</font></b></td>
		<td colspan="2" align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Loading NOW</font></b></td>
		<td colspan="2" align="center"><b><font size="2" face="Verdana">Total Orders</font></b></td>
		<td align="center" bgcolor="#99FF99"><b><font size="2" face="Verdana">Available to Ship</font></b></td>
	</tr>
	<tr>
		<td align="center"><b><font size="2" face="Verdana">Pallets</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Pallets</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Pallets</font></b></td>
		<td align="center" bgcolor="#99FF99"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Pallets</font></b></td>
		<td align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Pallets</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Orders</font></b></td>
		<td align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Orders</font></b></td>
		<td align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Orders</font></b></td>
		<td align="center" bgcolor="#FF9999"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Orders</font></b></td>
		<td align="center"><b><font size="2" face="Verdana">Cartons</font></b></td>
		<td align="center" bgcolor="#99FF99"><b><font size="2" face="Verdana">Cartons</font></b></td>
	</tr>
	<!-- end table headers !-->
<?
			for($i = 0; $i < sizeof($var_array); $i++){
				// get data, store for later display
				$sql = "SELECT NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL, VARIETY 
						FROM CARGO_TRACKING 
						WHERE ARRIVAL_NUM = '".$vessel_num."' 
							AND RECEIVER_ID = '1626'
							AND COMMODITY_CODE = '".$comm."' 
							AND VARIETY = '".$var_array[$i]."'
						GROUP BY VARIETY";
//				echo $sql."<br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$af_pallets_expected = $Short_Term_row['PAL_TOTAL'];
					$af_cases_expected = $Short_Term_row['CASE_TOTAL'];
					$af_p_e_total += $af_pallets_expected;
					$af_c_e_total += $af_cases_expected;
				} else {
					$af_pallets_expected = 0;
					$af_cases_expected = 0;
				}

				$sql = "SELECT NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL, VARIETY 
						FROM CARGO_TRACKING 
						WHERE ARRIVAL_NUM = '".$vessel_num."' 
							AND COMMODITY_CODE = '".$comm."' 
							AND RECEIVER_ID = '1626'
							AND DATE_RECEIVED IS NULL 
							AND VARIETY = '".$var_array[$i]."'
						GROUP BY VARIETY";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$af_pallets_awaiting = $Short_Term_row['PAL_TOTAL'];
					$af_cases_awaiting = $Short_Term_row['CASE_TOTAL'];
					$af_p_a_total += $af_pallets_awaiting;
					$af_c_a_total += $af_cases_awaiting;
				} else {
					$af_pallets_awaiting = 0;
					$af_cases_awaiting = 0;
				}

				
				$sql = "SELECT NVL(COUNT(PALLET_ID), 0) PAL_TOTAL, NVL(SUM(QTY_RECEIVED), 0) CASE_TOTAL, VARIETY 
						FROM CARGO_TRACKING 
						WHERE ARRIVAL_NUM = '".$vessel_num."' 
							AND COMMODITY_CODE = '".$comm."' 
							AND RECEIVER_ID = '1626'
							AND DATE_RECEIVED IS NOT NULL
							AND VARIETY = '".$var_array[$i]."'
						GROUP BY VARIETY";
//				echo $sql."<br><br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$af_pallets_received = $Short_Term_row['PAL_TOTAL'];
					$af_cases_received = $Short_Term_row['CASE_TOTAL'];
					$af_p_r_total += $af_pallets_received;
					$af_c_r_total += $af_cases_received;
				} else {
					$af_pallets_received = 0;
					$af_cases_received = 0;
				}

//							AND QTY_IN_HOUSE = 0 
				$sql = "SELECT NVL(COUNT(DISTINCT CT.PALLET_ID), 0) PAL_TOTAL, NVL(SUM(QTY_CHANGE), 0) CASE_TOTAL, VARIETY 
						FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
						WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
							AND CT.PALLET_ID = CA.PALLET_ID
							AND CT.RECEIVER_ID = CA.CUSTOMER_ID
							AND CT.ARRIVAL_NUM = '".$vessel_num."' 
							AND CT.COMMODITY_CODE = '".$comm."' 
							AND CT.RECEIVER_ID = '1626'
							AND CT.DATE_RECEIVED IS NOT NULL 
							AND CT.VARIETY = '".$var_array[$i]."'
							AND SERVICE_CODE = '6'
							AND ACTIVITY_DESCRIPTION IS NULL
						GROUP BY CT.VARIETY";
//				echo $sql."<br><br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$af_pallets_shipped = $Short_Term_row['PAL_TOTAL'];
					$af_cases_shipped = $Short_Term_row['CASE_TOTAL'];
					$af_p_s_total += $af_pallets_shipped;
					$af_c_s_total += $af_cases_shipped;
				} else {
					$af_pallets_shipped = 0;
					$af_cases_shipped = 0;
				}

				$sql = "SELECT COUNT(DISTINCT AOH.ORDER_NUM) ORDERS, SUM(CARTONS) THE_SUM
						FROM ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_ORDER_DETAIL AOD
						WHERE AOH.ORDER_NUM = AOD.ORDER_NUM
							AND AOH.STATUS = '1'
							AND VARIETY = '".$var_array[$i]."'
							AND AOD.VOUCHER_NUM IN (SELECT BATCH_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$draft_orders = $Short_Term_row['ORDERS'];
					$draft_cartons = (0 + $Short_Term_row['THE_SUM']);
					$d_o_o_total += $draft_orders;
					$d_o_c_total += $draft_cartons;
				} else {
					$draft_orders = 0;
					$draft_cartons = 0;
				}

				$sql = "SELECT COUNT(DISTINCT AOH.ORDER_NUM) ORDERS, SUM(CARTONS) THE_SUM
						FROM ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_ORDER_DETAIL AOD
						WHERE AOH.ORDER_NUM = AOD.ORDER_NUM
							AND AOH.STATUS = '2'
							AND VARIETY = '".$var_array[$i]."'
							AND AOD.VOUCHER_NUM IN (SELECT BATCH_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."')";
//				echo $sql."<br><br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$submitted_orders = $Short_Term_row['ORDERS'];
					$submitted_cartons = (0 + $Short_Term_row['THE_SUM']);
					$s_o_o_total += $submitted_orders;
					$s_o_c_total += $submitted_cartons;
				} else {
					$submitted_orders = 0;
					$submitted_cartons = 0;
				}

				// this little beaut of logic is to make sure that we count Loading orders as well,
				// BUT that any pallets already on said order aren't "double counted" towards the available totals.
				$sql = "SELECT COUNT(DISTINCT AOH.ORDER_NUM) ORDERS, SUM(CARTONS) THE_SUM
						FROM ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_ORDER_DETAIL AOD
						WHERE AOH.ORDER_NUM = AOD.ORDER_NUM
							AND AOH.STATUS = '3'
							AND VARIETY = '".$var_array[$i]."'
							AND AOD.VOUCHER_NUM IN (SELECT BATCH_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."')";
//				echo $sql."<br><br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$loading_orders = $Short_Term_row['ORDERS'];
					$loading_cartons = (0 + $Short_Term_row['THE_SUM']);

					$sql = "SELECT SUM(QTY_CHANGE) THE_CTNS
							FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
							WHERE ORDER_NUM IN (SELECT AOH.ORDER_NUM
												FROM ARGENFRUIT_ORDER_HEADER AOH, ARGENFRUIT_ORDER_DETAIL AOD
												WHERE AOH.ORDER_NUM = AOD.ORDER_NUM
													AND AOH.STATUS = '3'
													AND VARIETY = '".$var_array[$i]."'
													AND AOD.VOUCHER_NUM IN (SELECT BATCH_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_num."'))
								AND SERVICE_CODE = '6'
								AND ACTIVITY_DESCRIPTION IS NULL
								AND CUSTOMER_ID = '1626'
								AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
								AND CT.PALLET_ID = CA.PALLET_ID
								AND CT.RECEIVER_ID = CA.CUSTOMER_ID
								AND CT.VARIETY = '".$var_array[$i]."'";
//					echo $sql."<br><br>";
					ora_parse($Short_Term_Cursor_Inner, $sql);
					ora_exec($Short_Term_Cursor_Inner);
					ora_fetch_into($Short_Term_Cursor_Inner, $Short_Term_Row_Inner, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$loading_cartons -= $Short_Term_Row_Inner['THE_CTNS'];

					$l_o_c_total += $loading_cartons;
					$l_o_o_total += $loading_orders;
				} else {
					$loading_orders = 0;
					$loading_cartons = 0;
				}

				$total_orders = ($draft_orders + $submitted_orders + $loading_orders);
				$total_cartons = ($draft_cartons + $submitted_cartons + $loading_cartons);
//				$total_orders = ($submitted_orders + $loading_orders);
//				$total_cartons = ($submitted_cartons + $loading_cartons);
				$t_o_o_total += $total_orders;
				$t_o_c_total += $total_cartons;

//				$ats_c = (($af_cases_received + $af_cases_awaiting)- $af_cases_shipped) - $total_cartons;
				$ats_c = ($af_cases_received - $af_cases_shipped) - $total_cartons;
				$ats_c_total += $ats_c;



?>
	<tr>
		<td><b><font size="3" face="Verdana"><? echo $var_array[$i]; ?></font></b></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=expected" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=expected"><? echo number_format($af_pallets_expected); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=expected" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=expected"><? echo number_format($af_cases_expected); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=awaiting" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=awaiting"><? echo number_format($af_pallets_awaiting); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=awaiting" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=awaiting"><? echo number_format($af_cases_awaiting); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=received" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=received"><? echo number_format($af_pallets_received); ?></a></font></td>
		<td align="right" bgcolor="#99FF99"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=received" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=received"><? echo number_format($af_cases_received); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=shipped" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=shipped"><? echo number_format($af_pallets_shipped); ?></a></font></td>
		<td align="right" bgcolor="#FF9999"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=shipped" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=shipped"><? echo number_format($af_cases_shipped); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=atpow" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=atpow"><? echo number_format($af_pallets_received - $af_pallets_shipped); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=atpow" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>&category=atpow"><? echo number_format($af_cases_received - $af_cases_shipped); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($draft_orders); ?></font></td>
		<td align="right" bgcolor="#FF9999"><font size="2" face="Verdana"><? echo number_format($draft_cartons); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($submitted_orders); ?></font></td>
		<td align="right" bgcolor="#FF9999"><font size="2" face="Verdana"><? echo number_format($submitted_cartons); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($loading_orders); ?></font></td>
		<td align="right" bgcolor="#FF9999"><font size="2" face="Verdana"><? echo number_format($loading_cartons); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($total_orders); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($total_cartons); ?></font></td>
		<td align="right" bgcolor="#99FF99"><font size="2" face="Verdana"><a href="Avail_Drill_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>"><? echo number_format($ats_c); ?></a></font></td> 
<!--		<td align="right" bgcolor="#99FF99"><font size="2" face="Verdana"><a href="Avail_Drill_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var_array[$i]; ?>"><? echo number_format($ats_c); ?></a></font></td> !-->
	</tr>
<?
			}
?>
	<tr>
		<td colspan="20">&nbsp;</td>
	</tr>
	<tr>
		<td><b><font size="3" face="Verdana">Totals:</font></b></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=expected" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=expected"><? echo number_format($af_p_e_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=expected" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=expected"><? echo number_format($af_c_e_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=awaiting" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=awaiting"><? echo number_format($af_p_a_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=awaiting" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=awaiting"><? echo number_format($af_c_a_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=received" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=received"><? echo number_format($af_p_r_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=received" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=received"><? echo number_format($af_c_r_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=shipped" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=shipped"><? echo number_format($af_p_s_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=shipped" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=shipped"><? echo number_format($af_c_s_total); ?></a></font></td>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=atpow" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=atpow"><? echo number_format($af_p_r_total - $af_p_s_total); ?></a></font>
		<td align="right"><font size="2" face="Verdana"><a href="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=atpow" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all&category=atpow"><? echo number_format($af_c_r_total - $af_c_s_total); ?></a></font>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($d_o_o_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($d_o_c_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($s_o_o_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($s_o_c_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($l_n_o_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($l_n_c_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($t_o_o_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($t_o_c_total); ?></font></td>
		<td align="right"><font size="2" face="Verdana"><? echo number_format($ats_c_total); ?></font></td>
<!--		<td align="right"><font size="2" face="Verdana"><a href="Avail_Drill_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all" target="Argen_Summary_index.php?vessel=<? echo $vessel_num; ?>&cust=1626&comm=<? echo $comm; ?>&var=all"><? echo number_format($ats_c_total); ?></a></font></td> !-->
	</tr>
</table>

<?
		}
	}
?>