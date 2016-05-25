<?
/*
*	Adam Walter, Oct 2012.
*
*	This page is a listing of all InHouse as of a date
*************************************************************************/

	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	
	
	$vessel = $HTTP_POST_VARS['vessel'];
	$comm = $HTTP_POST_VARS['comm'];
	$cust = $HTTP_POST_VARS['cust'];
	$submit = $HTTP_POST_VARS['submit'];
	$DO_list = $HTTP_POST_VARS['DO_list'];

	$DO_sql = "";
	if($submit != ""){
		if($DO_list == ""){
			// we good
		} elseif(!ereg("^[0-9A-Z]+(,[0-9A-Z]+)*$", $DO_list)) {
			echo "<font size=3 face=Verdana color=#FF0000>The Optional DO list must be an alphanumeric list of DO#s, separated by commas.  No other characters are allowed.</font><br>";
			$submit = "";
			$cust = "";
		} else {
			// we have a DO list, and it's valid.
			$DO_sql = " AND REMARK IN ('".str_replace(",", "','", $DO_list)."') ";
		}
	}
?>
<script language="JavaScript" src="../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">DO History Lookup</font><br>
			<font size="4" face="Verdana" color="#0066CC">At least one of the following need to be entered:  Vessel, Commodity, or an entry in the DO#-List box
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="40%">
			<table border="1" rules="none" width="100%" cellpadding="4" cellspacing="0">
			<form name="get_ves_data" action="steel_DO_scanned.php" method="post">
				<tr valign="top">
					<td width="15%" align="left"><font size="2" face="Verdana"><b>Customer:</b></font></td>
					<td align="left"><select name="cust">
<?
	$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME 
			FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
			WHERE CECL.EPORT_LOGIN = '".$user."' 
				AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
		UNION
			SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
			FROM CUSTOMER_PROFILE CP
			WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
			AND CUSTOMER_ID != 0
		ORDER BY CUSTOMER_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
								<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>" <? if(ociresult($short_term_data, "CUSTOMER_ID") == $cust){?>selected<?}?>>
										<? echo ociresult($short_term_data, "CUSTOMER_NAME"); ?></option>
<?
	}
?>		
					</select></td>
				</tr>
				<tr>
					<td width="15%" align="left"><font size="2" face="Verdana"><b>Vessel:</b></font></td>
					<td align="left"><select name="vessel"><option value="">All</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) IN
				(SELECT ARRIVAL_NUM FROM CARGO_TRACKING CT
				WHERE CT.DATE_RECEIVED > SYSDATE - 730
				AND RECEIVER_ID IN 
					(SELECT CP.CUSTOMER_ID 
						FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
						WHERE CECL.EPORT_LOGIN = '".$user."' 
							AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
					UNION
						SELECT CP.CUSTOMER_ID
						FROM CUSTOMER_PROFILE CP
						WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
					)
				)
				AND LR_NUM != '4321'
			ORDER BY LR_NUM DESC";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
								<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>" <? if(ociresult($short_term_data, "LR_NUM") == $vessel){?>selected<?}?>>
										<? echo ociresult($short_term_data, "VESSEL_NAME"); ?></option>
<?
	}
?>
					</select></td>
				</tr>
				<tr>
					<td width="15%" align="left"><font size="2" face="Verdana"><b>Commodity:</b></font></td>
					<td align="left"><select name="comm"><option value="">All</option>
<?
	$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_TYPE = 'STEEL'
				AND COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM CARGO_TRACKING
					WHERE DATE_RECEIVED > SYSDATE - 730
						AND RECEIVER_ID IN
						(SELECT CP.CUSTOMER_ID 
							FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
							WHERE CECL.EPORT_LOGIN = '".$user."' 
								AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
						UNION
							SELECT CP.CUSTOMER_ID
							FROM CUSTOMER_PROFILE CP
							WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
						)
					)
			ORDER BY COMMODITY_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
								<option value="<? echo ociresult($short_term_data, "COMMODITY_CODE"); ?>" <? if(ociresult($short_term_data, "COMMODITY_CODE") == $comm){?>selected<?}?>>
										<? echo ociresult($short_term_data, "COMMODITY_NAME"); ?></option>
<?
	}
?>
					</select></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"></td>
				</tr>
			</form>
			</table>
		</td>
		<td valign="center" align="center" width="10%"><font size="3" face="Verdana"><b>---OR---</b></font></td>
		<td width="40%">
			<table border="1" rules="none" width="100%" cellpadding="4" cellspacing="0">
			<form name="get_DO_data" action="steel_DO_scanned.php" method="post">
				<tr valign="top">
					<td><font size="2" face="Verdana"><b>Customer:</b></font></td>
					<td align="left"><select name="cust">
<?
	$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME 
			FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
			WHERE CECL.EPORT_LOGIN = '".$user."' 
				AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
		UNION
			SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
			FROM CUSTOMER_PROFILE CP
			WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
			AND CUSTOMER_ID != 0
		ORDER BY CUSTOMER_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
								<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>" <? if(ociresult($short_term_data, "CUSTOMER_ID") == $cust){?>selected<?}?>>
										<? echo ociresult($short_term_data, "CUSTOMER_NAME"); ?></option>
<?
	}
?>		
					</select></td>
				</tr>
				<tr>
					<td colspan="2" align="left"><font size="2" face="Verdana"><b>DO# list (separated by commas.  Do not use spaces.):</b></font></td>
				</tr>
				<tr>
					<td colspan="2" align="left"><input type="text" name="DO_list" size="50" maxlength="1000" value="<? echo $DO_list; ?>"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"></td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
<?
	if($cust != "" && ($vessel != "" || $comm != "" || $DO_list != "")){
		$extra_sql = "";
		if($cust != ""){
			$extra_sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
		}
		if($vessel != ""){
			$extra_sql .= " AND CT.ARRIVAL_NUM = '".$vessel."' ";
		}
		if($comm != ""){
			$extra_sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
		}
		if($DO_drilldown != ""){
			$extra_sql .= " AND CT.REMARK = '".$DO_drilldown."' ";
		}

?>
	<tr>
		<td colspan="3">
			<table border="0" width="100%" cellpadding="4" cellspacing="0">
			<form name="get_DO_dropdown_data" action="steel_DO_scanned.php" method="post">
			<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
			<input type="hidden" name="cust" value="<? echo $cust; ?>">
			<input type="hidden" name="comm" value="<? echo $comm; ?>">
			<input type="hidden" name="DO_list" value="<? echo $DO_list; ?>">
				<tr>
					<td align="center"><font size="3" face="Verdana"><b>Based on the Above Data, the following DO#s have been found, if you wish to narrow down your search:</b></font></td>
				</tr>
				<tr>
					<td align="center"><select name="DO_drilldown"><option value="">All</option>
<?
		$sql = "SELECT DISTINCT REMARK 
				FROM CARGO_TRACKING CT 
				WHERE QTY_RECEIVED > 0
					".$extra_sql."
					AND REMARK != 'NO DO'
					".$DO_sql."
					AND ARRIVAL_NUM != '4321'
				ORDER BY REMARK";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
?>
								<option value="<? echo ociresult($short_term_data, "REMARK"); ?>" <? if(ociresult($short_term_data, "REMARK") == $DO_drilldown){?>selected<?}?>>
										<? echo ociresult($short_term_data, "REMARK"); ?></option>
<?
		}
?>		
					</select></td>
				</tr>
				<tr>
					<td align="center"><input type="submit" name="submit" value="Retrieve Data"></td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</table>
<?
		$filename = $cust."at".date('mdYhis').".xls";
		$handle = fopen("tempfiles/".$filename, "w");
		if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
			exit;
		}
?>
<table width="100%"><tr><td align="center"><font size="2" face="Verdana"><b><a href="./tempfiles/<? echo $filename; ?>">Click Here</a> for the Excel version.</b></font></td></tr></table>
<?
		$output = "<table border=\"2\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
			<tr>
				<td bgcolor=\"99FFFF\"><font size=\"2\" face=\"Verdana\"><b>Vessel</b></font></td>
				<td bgcolor=\"CCFF99\"><font size=\"2\" face=\"Verdana\"><b>Commodity</b></font></td>
				<td bgcolor=\"CCCCFF\"><font size=\"2\" face=\"Verdana\"><b>DO#</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>PltID</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Mark</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Received Weight</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>First Date/Time Received</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Qty Expected</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Qty Received</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Qty In-House</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>PCs</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Shipped</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Order#</b></font></td>
				<td><font size=\"2\" face=\"Verdana\"><b>Date Shipped</b></font></td>
			</tr>";
		DualWrite($handle, $output);

		// 1st sub-totals:  by vessel
		$sql = "SELECT DISTINCT ARRIVAL_NUM 
				FROM CARGO_TRACKING CT 
				WHERE QTY_RECEIVED > 0
					".$extra_sql."
					AND REMARK != 'NO DO'
					".$DO_sql."
					AND ARRIVAL_NUM != '4321'
				ORDER BY ARRIVAL_NUM";
		$report_vessels = ociparse($rfconn, $sql);
		ociexecute($report_vessels);
		if(!ocifetch($report_vessels)){
			$output = "<tr><td colspan=\"14\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
			DualWrite($handle, $output);
		} else {
			do {
				$sql = "SELECT VESSEL_NAME 
						FROM VESSEL_PROFILE
						WHERE TO_CHAR(LR_NUM) = '".ociresult($report_vessels, "ARRIVAL_NUM")."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
				if(!ocifetch($short_term_data)){
					$ves_name = ociresult($report_vessels, "ARRIVAL_NUM")." - UNKNOWN";
				} else {
					$ves_name = ociresult($short_term_data, "VESSEL_NAME");
				}

				$output = "<tr><td colspan=\"14\" bgcolor=\"99FFFF\"><font size=\"2\" face=\"Verdana\"><b>".$ves_name."</b></font></td></tr>";
				DualWrite($handle, $output);


				$ves_total_qty_rec = 0;
				$ves_total_qty1 = 0;
				$ves_total_qty2 = 0;
				$ves_total_dmgd = 0;
				$ves_total_withdraw = 0;
				$ves_total_qty_IH = 0;
//				$ves_total_pending = 0;
				$ves_total_wt_start = 0;

				// 2nd subtotal:  by commodity
				$sql = "SELECT DISTINCT CT.COMMODITY_CODE 
						FROM CARGO_TRACKING CT 
						WHERE QTY_RECEIVED > 0
							AND CT.ARRIVAL_NUM = '".ociresult($report_vessels, "ARRIVAL_NUM")."'
							".$extra_sql."
							AND REMARK != 'NO DO'
							".$DO_sql."
						ORDER BY CT.COMMODITY_CODE";
				$report_comms = ociparse($rfconn, $sql);
				ociexecute($report_comms);
				if(!ocifetch($report_comms)){
					$output = "<tr><td colspan=\"14\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
					DualWrite($handle, $output);
				} else {
					do {
						$sql = "SELECT COMMODITY_NAME 
								FROM COMMODITY_PROFILE
								WHERE COMMODITY_CODE = '".ociresult($report_comms, "COMMODITY_CODE")."'";
						$short_term_data = ociparse($rfconn, $sql);
						ociexecute($short_term_data);
						if(!ocifetch($short_term_data)){
							$comm_name = ociresult($report_comms, "COMMODITY_CODE")." - UNKNOWN";
						} else {
							$comm_name = ociresult($short_term_data, "COMMODITY_NAME");
						}

						$output = "<tr><td>&nbsp;</td><td colspan=\"13\" bgcolor=\"CCFF99\"><font size=\"2\" face=\"Verdana\"><b>".$comm_name."</b></font></td></tr>";
						DualWrite($handle, $output);

						$comm_total_qty_rec = 0;
						$comm_total_qty1 = 0;
						$comm_total_qty2 = 0;
						$comm_total_dmgd = 0;
						$comm_total_withdraw = 0;
						$comm_total_qty_IH = 0;
//						$comm_total_pending = 0;
						$comm_total_wt_start = 0;

						// 3rd sub total:  by DO
						$sql = "SELECT DISTINCT REMARK
								FROM CARGO_TRACKING CT
								WHERE QTY_RECEIVED > 0
									AND CT.ARRIVAL_NUM = '".ociresult($report_vessels, "ARRIVAL_NUM")."'
									AND COMMODITY_CODE = '".ociresult($report_comms, "COMMODITY_CODE")."'
									".$extra_sql."
									AND REMARK != 'NO DO'
									".$DO_sql."
								ORDER BY REMARK";
						$report_DOs = ociparse($rfconn, $sql);
						ociexecute($report_DOs);
						if(!ocifetch($report_DOs)){
							$output = "<tr><td colspan=\"14\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
							DualWrite($handle, $output);
						} else {
							do {
								$output = "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=\"12\" bgcolor=\"CCCCFF\"><font size=\"2\" face=\"Verdana\"><b>".ociresult($report_DOs, "REMARK")."</b></font></td></tr>";
								DualWrite($handle, $output);

								$DO_total_qty_rec = 0;
								$DO_total_qty1 = 0;
								$DO_total_qty2 = 0;
								$DO_total_dmgd = 0;
								$DO_total_withdraw = 0;
								$DO_total_qty_IH = 0;
//								$DO_total_pending = 0;
								$DO_total_wt_start = 0;

								$sql = "SELECT CARGO_DESCRIPTION, TO_CHAR(MIN(CT.DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') DATE_REC, SUM(CT.WEIGHT) THE_WT, 
											SUM(CT.QTY_RECEIVED) THE_REC, SUM(CT.QTY_IN_HOUSE) THE_IH, SUM(QTY_EXPECTED) THE_EXPEC,
											NVL(COMMODITY_UNIT, QTY_UNIT) THE_UNIT,	CT.PALLET_ID
										FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, CARGO_TRACKING_ADDITIONAL_DATA CTAD 
										WHERE CT.QTY_RECEIVED > 0
											AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
											AND CT.ARRIVAL_NUM = '".ociresult($report_vessels, "ARRIVAL_NUM")."'
											AND CT.COMMODITY_CODE = '".ociresult($report_comms, "COMMODITY_CODE")."'
											AND CT.REMARK = '".ociresult($report_DOs, "REMARK")."'
											".$extra_sql."
											AND CT.PALLET_ID = CTAD.PALLET_ID
											AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
											AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
											AND CT.REMARK != 'NO DO'
										GROUP BY CARGO_DESCRIPTION, NVL(COMMODITY_UNIT, QTY_UNIT), CT.PALLET_ID
										ORDER BY CT.PALLET_ID";
								$report_lines = ociparse($rfconn, $sql);
								ociexecute($report_lines);
								if(!ocifetch($report_lines)){
									$output = "<tr><td colspan=\"14\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Cargo In-House for Given Parameters</b></font></td></tr>";
									DualWrite($handle, $output);
								} else {
									do {
										if($bgcolor == "#FFFFFF"){
											$bgcolor = "#FFFFEE";
										} else {
											$bgcolor = "#FFFFFF";
										}

										// now, decide what to do with each pallet.
										// if no activity, we show the row with no data under last columns (activity being a non-voided outbound or return)
										// if >= 1 activity, we show rows going across
										$sql = "SELECT COUNT(*) THE_COUNT 
												FROM CARGO_ACTIVITY 
												WHERE ARRIVAL_NUM = '".ociresult($report_vessels, "ARRIVAL_NUM")."' 
													AND CUSTOMER_ID = '".$cust."' 
													AND PALLET_ID = '".ociresult($report_lines, "PALLET_ID")."' 
													AND SERVICE_CODE = '6' 
													AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) 
													AND ACTIVITY_NUM != '1'";
										$short_term_data = ociparse($rfconn, $sql);
										ociexecute($short_term_data);
										if(!ocifetch($short_term_data)){
											$act_count = 0;
										} else {
											$act_count = ociresult($short_term_data, "THE_COUNT");
										}
										$rowcount = max(1, $act_count);

										$pallet = ociresult($report_lines, "PALLET_ID");
										$Mark = ociresult($report_lines, "CARGO_DESCRIPTION");
										$DateRcvd = ociresult($report_lines, "DATE_REC");
										$QtyRcvd = ociresult($report_lines, "THE_REC");
										$QtyExpec = ociresult($report_lines, "THE_EXPEC");
										$Unt1 = ociresult($report_lines, "THE_UNIT");
										$QtyIH = ociresult($report_lines, "THE_IH");
										$WtStart = ociresult($report_lines, "THE_WT");

										$comm_total_qty_rec += $QtyRcvd;
										$comm_total_qty_IH += $QtyIH;
//										$comm_total_pending += $pending;
										$comm_total_wt_start += $WtStart;

										$ves_total_qty_rec += $QtyRcvd;
										$ves_total_qty_IH += $QtyIH;
//										$ves_total_pending += $pending;
										$ves_total_wt_start += $WtStart;

										$DO_total_qty_rec += $QtyRcvd;
										$DO_total_qty_IH += $QtyIH;
//										$DO_total_pending += $pending;
										$DO_total_wt_start += $WtStart;

										$output = "<tr bgcolor=\"".$bgcolor."\">
											<td rowspan=\"".$rowcount."\">&nbsp;</td>
											<td rowspan=\"".$rowcount."\">&nbsp;</td>
											<td rowspan=\"".$rowcount."\">&nbsp;</td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$pallet."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$Mark."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$WtStart."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$DateRcvd."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$QtyExpec."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$QtyRcvd."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$QtyIH."</font></td>
											<td rowspan=\"".$rowcount."\"><font size=\"2\" face=\"Verdana\">".$Unt1."</font></td>";
										
										if($act_count == 0){
											$output .= "<td rowspan=\"1\" colspan=\"3\" align=\"center\">---NONE---</td></tr>";
										} else {
											$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') ACT_DATE, ORDER_NUM, QTY_CHANGE
													FROM CARGO_ACTIVITY
													WHERE ARRIVAL_NUM = '".ociresult($report_vessels, "ARRIVAL_NUM")."' 
														AND CUSTOMER_ID = '".$cust."' 
														AND PALLET_ID = '".ociresult($report_lines, "PALLET_ID")."' 
														AND SERVICE_CODE = '6' 
														AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) 
														AND ACTIVITY_NUM != '1'
													ORDER BY ACTIVITY_NUM";
											$activities = ociparse($rfconn, $sql);
											ociexecute($activities);
											ocifetch($activities);
											$output .= "<td>".ociresult($activities, "QTY_CHANGE")."</td>
														<td>".ociresult($activities, "ORDER_NUM")."</td>
														<td>".ociresult($activities, "ACT_DATE")."</td></tr>";
											while(ocifetch($activities)){
												$output .= "<tr>
																<td>".ociresult($activities, "QTY_CHANGE")."</td>
																<td>".ociresult($activities, "ORDER_NUM")."</td>
																<td>".ociresult($activities, "ACT_DATE")."</td></tr>";
											}
										}

										DualWrite($handle, $output);
									} while(ocifetch($report_lines));
								}

								// DO total
								$output = "<tr bgcolor=\"9999FF\">
									<td bgcolor=\"FFFFFF\">&nbsp;</td>
									<td bgcolor=\"FFFFFF\">&nbsp;</td>
									<td colspan=\"3\"><font size=\"2\" face=\"Verdana\"><b>".ociresult($report_DOs, "REMARK")." TOTAL</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_wt_start."</b></font></td>
									<td colspan=\"2\">&nbsp;</td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_qty_rec."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_qty_IH."</b></font></td>
									<td>&nbsp;</td>
									<td colspan=\"3\">&nbsp;</td>
								</tr>";
								DualWrite($handle, $output);
							} while(ocifetch($report_DOs));
						}

						// commodity total
						$output = "<tr bgcolor=\"99CC33\">
							<td bgcolor=\"FFFFFF\">&nbsp;</td>
							<td colspan=\"4\"><font size=\"2\" face=\"Verdana\"><b>".ociresult($report_comms, "COMMODITY_CODE")." TOTAL</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_wt_start."</b></font></td>
							<td colspan=\"2\">&nbsp;</td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty_rec."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty_IH."</b></font></td>
							<td>&nbsp;</td>
							<td colspan=\"3\">&nbsp;</td>
						</tr>";
						DualWrite($handle, $output);
					} while(ocifetch($report_comms));
				}

				// vessel total
				$output = "<tr bgcolor=\"99CCFF\">
					<td colspan=\"5\"><font size=\"2\" face=\"Verdana\"><b>".$ves_name." TOTAL</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_wt_start."</b></font></td>
					<td colspan=\"2\">&nbsp;</td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty_rec."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty_IH."</b></font></td>
					<td>&nbsp;</td>
					<td colspan=\"3\">&nbsp;</td>
				</tr>";
				DualWrite($handle, $output);
			} while(ocifetch($report_vessels));
		}
	}
?>
</table>
<?


















function DualWrite($handle, $output){
	echo $output;
	fwrite($handle, $output);
}