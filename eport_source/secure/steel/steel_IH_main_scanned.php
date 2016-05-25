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


/*	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}*/
	$rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($rf_conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rf_conn));
		exit;
	}
	$date_cursor = ora_open($rf_conn);
	$vessel_cursor = ora_open($rf_conn);
	$comm_cursor = ora_open($rf_conn);
	$DO_cursor = ora_open($rf_conn);
	$cargo_cursor = ora_open($rf_conn);
	$Short_Term_Cursor = ora_open($rf_conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$all_vessels = $HTTP_POST_VARS['all_vessels'];
	if($all_vessels == "all_vessels"){
		$vessel = "all_vessels";
	}
	$comm = $HTTP_POST_VARS['comm'];
	$all_comms = $HTTP_POST_VARS['all_comms'];
	if($all_comms == "all_comms"){
		$comm = "all_comms";
	}

	$cust = $HTTP_POST_VARS['cust'];
	$asof_date = $HTTP_POST_VARS['asof_date'];
	$submit = $HTTP_POST_VARS['submit'];
	$DO_list = $HTTP_POST_VARS['DO_list'];

	// give date a default of today
	if($asof_date == ""){
		$asof_date = date('m/d/Y');
	}

	if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $asof_date)) {
		echo "<font size=3 face=Verdana color=#FF0000>The As-Of date must be in MM/DD/YYYY format (was entered as ".$from_date.")</font><br>";
		$from_date = "";
	}

	$DO_sql = "";
	if($submit != ""){
		if($DO_list == ""){
			// we good
		} elseif(!ereg("^[0-9A-Z]+(,[0-9A-Z]+)*$", $DO_list)) {
			echo "<font size=3 face=Verdana color=#FF0000>The Optional DO list must be an alphanumeric list of DO#s, separated by commas.  No other characters are allowed.</font><br>";
			$submit = "";
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
            <font size="5" face="Verdana" color="#0066CC">In-House Inventory Start-of-Day
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="steel_IH_scanned.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>As-Of Start of Day:</b></font></td>
		<td align="left"><input name="asof_date" type="text" size="10" maxlength="10" value="<? echo $asof_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.asof_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
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
/*
				(SELECT OWNER_ID FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
				WHERE CT.LOT_NUM = CM.CONTAINER_NUM
				AND CT.DATE_RECEIVED > SYSDATE - 730
				AND CT.COMMODITY_CODE IN
					(SELECT COMMODITY_CODE
					FROM COMMODITY_PROFILE
					WHERE COMMODITY_TYPE = 'STEEL'
					)
				)
*/
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['CUSTOMER_ID']; ?>" <? if($row['CUSTOMER_ID'] == $cust){?>selected<?}?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
	}
?>		
		</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td align="left"><select name="vessel">
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
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['LR_NUM']; ?>" <? if($row['LR_NUM'] == $vessel){?>selected<?}?>><? echo $row['VESSEL_NAME'] ?></option>
<?
	}
?>
		</select>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="all_vessels" value="all_vessels" <? if($vessel == "all_vessels"){?>checked<?}?>>&nbsp;&nbsp;<font size="2" face="Verdana">All Vessels</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Commodity:</b></font></td>
		<td align="left"><select name="comm">
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
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['COMMODITY_CODE']; ?>" <? if($row['COMMODITY_CODE'] == $comm){?>selected<?}?>><? echo $row['COMMODITY_NAME'] ?></option>
<?
	}
?>
		</select>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="all_comms" value="all_comms" <? if($comm == "all_comms"){?>checked<?}?>>&nbsp;&nbsp;<font size="2" face="Verdana">All Commodities</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>DO# list (Optional):</b></font></td>
		<td align="left"><input type="text" name="DO_list" size="50" maxlength="1000" value="<? echo $DO_list; ?>"></td>
	</tr>		
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Data"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	</form>
</table>
<?
	if($asof_date != "" && $submit != ""){
		$extra_sql = "";

		$filename = $cust."at".date('mdYhis').".xls";
		$handle = fopen("tempfiles/".$filename, "w");
		if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
			exit;
		}
?>
	<table width="100%"><tr><td align="center"><font size="2" face="Verdana"><b><a href="./tempfiles/<? echo $filename; ?>">Click Here</a> for the Excel version.</b></font></td></tr>
<?
/*					<td><font size=\"2\" face=\"Verdana\"><b>Dmgd</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Withdrawn</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Left</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>R/H</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>QTY2</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Unit2</b></font></td>*/
		$output = "<table border=\"2\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
				<tr>
					<td bgcolor=\"99FFFF\"><font size=\"2\" face=\"Verdana\"><b>Vessel</b></font></td>
					<td bgcolor=\"CCFF99\"><font size=\"2\" face=\"Verdana\"><b>Commodity</b></font></td>
					<td bgcolor=\"CCCCFF\"><font size=\"2\" face=\"Verdana\"><b>DO#</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>PltID</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Mark</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>First Date/Time Received</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Qty Expected</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Qty Received</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Qty In-House</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>PCs</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Shipped</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>WT Left</b></font></td>
				</tr>";
		DualWrite($handle, $output);

		if($vessel != "all_vessels"){
			$extra_sql .= " AND CT.ARRIVAL_NUM = '".$vessel."' ";
		} else {
			// nothing
		}
		if($comm != "all_comms"){
			$extra_sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
		} else {
			// nothing
		}

		// 1st sub-totals:  by vessel
		$sql = "SELECT DISTINCT ARRIVAL_NUM 
				FROM CARGO_TRACKING CT 
				WHERE QTY_RECEIVED > 0
					AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
					AND RECEIVER_ID='".$cust."'".$extra_sql."
					AND REMARK != 'NO DO'
					".$DO_sql."
					AND ARRIVAL_NUM != '4321'
					AND 
						(
							(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
							  WHERE PALLET_ID = CT.PALLET_ID
								AND CUSTOMER_ID = CT.RECEIVER_ID
								AND ARRIVAL_NUM = CT.ARRIVAL_NUM
								AND SERVICE_CODE = '6'
								AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
								AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
							) < CT.QTY_RECEIVED 
							OR
							(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
							 WHERE PALLET_ID = CT.PALLET_ID
								AND CUSTOMER_ID = CT.RECEIVER_ID
								AND ARRIVAL_NUM = CT.ARRIVAL_NUM
								AND SERVICE_CODE = '6'
								AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
								AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
							) IS NULL
						)
				ORDER BY ARRIVAL_NUM";
		ora_parse($vessel_cursor, $sql);
		ora_exec($vessel_cursor);
		if(!ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output = "<tr><td colspan=\"12\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
			DualWrite($handle, $output);
		} else {
			do {
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
						WHERE TO_CHAR(LR_NUM) = '".$vessel_row['ARRIVAL_NUM']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$ves_name = $vessel_row['ARRIVAL_NUM']." - UNKNOWN";
				} else {
					$ves_name = $short_term_row['VESSEL_NAME'];
				}

				$output = "<tr><td colspan=\"12\" bgcolor=\"99FFFF\"><font size=\"2\" face=\"Verdana\"><b>".$ves_name."</b></font></td></tr>";
				DualWrite($handle, $output);

				$ves_total_qty_rec = 0;
				$ves_total_qty1 = 0;
				$ves_total_qty2 = 0;
				$ves_total_dmgd = 0;
				$ves_total_withdraw = 0;
				$ves_total_left = 0;
				$ves_total_pending = 0;
				$ves_total_wt_left = 0;

				// 2nd subtotal:  by commodity
				$sql = "SELECT DISTINCT CT.COMMODITY_CODE FROM CARGO_TRACKING CT 
						WHERE QTY_RECEIVED > 0
							AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
							AND CT.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
							AND RECEIVER_ID='".$cust."'".$extra_sql."
							AND REMARK != 'NO DO'
							".$DO_sql."
							AND 
								(
									(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
									  WHERE PALLET_ID = CT.PALLET_ID
										AND CUSTOMER_ID = CT.RECEIVER_ID
										AND ARRIVAL_NUM = CT.ARRIVAL_NUM
										AND SERVICE_CODE = '6'
										AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
										AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
									) < CT.QTY_RECEIVED 
									OR
									(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
									 WHERE PALLET_ID = CT.PALLET_ID
										AND CUSTOMER_ID = CT.RECEIVER_ID
										AND ARRIVAL_NUM = CT.ARRIVAL_NUM
										AND SERVICE_CODE = '6'
										AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
										AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
									) IS NULL
								)
						ORDER BY CT.COMMODITY_CODE";
				ora_parse($comm_cursor, $sql);
				ora_exec($comm_cursor);
				if(!ora_fetch_into($comm_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$output = "<tr><td colspan=\"12\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
					DualWrite($handle, $output);
				} else {
					do {
						$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
								WHERE COMMODITY_CODE = '".$comm_row['COMMODITY_CODE']."'";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$comm_name = $comm_row['COMMODITY_CODE']." - UNKNOWN";
						} else {
							$comm_name = $short_term_row['COMMODITY_NAME'];
						}

						$output = "<tr><td>&nbsp;</td><td colspan=\"11\" bgcolor=\"CCFF99\"><font size=\"2\" face=\"Verdana\"><b>".$comm_name."</b></font></td></tr>";
						DualWrite($handle, $output);

						$comm_total_qty_rec = 0;
						$comm_total_qty1 = 0;
						$comm_total_qty2 = 0;
						$comm_total_dmgd = 0;
						$comm_total_withdraw = 0;
						$comm_total_left = 0;
						$comm_total_pending = 0;
						$comm_total_wt_left = 0;

						// 3rd sub total:  by DO
						$sql = "SELECT DISTINCT REMARK
								FROM CARGO_TRACKING CT
								WHERE QTY_RECEIVED > 0
									AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
									AND CT.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
									AND COMMODITY_CODE = '".$comm_row['COMMODITY_CODE']."'
									AND RECEIVER_ID='".$cust."'".$extra_sql."
									AND REMARK != 'NO DO'
									".$DO_sql."
									AND 
										(
											(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
											  WHERE PALLET_ID = CT.PALLET_ID
												AND CUSTOMER_ID = CT.RECEIVER_ID
												AND ARRIVAL_NUM = CT.ARRIVAL_NUM
												AND SERVICE_CODE = '6'
												AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
												AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
											) < CT.QTY_RECEIVED 
											OR
											(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
											 WHERE PALLET_ID = CT.PALLET_ID
												AND CUSTOMER_ID = CT.RECEIVER_ID
												AND ARRIVAL_NUM = CT.ARRIVAL_NUM
												AND SERVICE_CODE = '6'
												AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
												AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
											) IS NULL
										)
								ORDER BY REMARK";
						ora_parse($DO_cursor, $sql);
						ora_exec($DO_cursor);
						if(!ora_fetch_into($DO_cursor, $DO_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$output = "<tr><td colspan=\"12\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
							DualWrite($handle, $output);
						} else {
							do {
								$output = "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=\"10\" bgcolor=\"CCCCFF\"><font size=\"2\" face=\"Verdana\"><b>".$DO_row['REMARK']."</b></font></td></tr>";
								DualWrite($handle, $output);

								$DO_total_qty_rec = 0;
								$DO_total_qty1 = 0;
								$DO_total_qty2 = 0;
								$DO_total_dmgd = 0;
								$DO_total_withdraw = 0;
								$DO_total_left = 0;
								$DO_total_pending = 0;
								$DO_total_wt_left = 0;

								// main SQL.
								// CA_I = CARGO_ACTIVITY_INCLUDE (part of the calculation)
								// CA_P = CARGO_ACTIVITY_PENDING, used for the 2nd to last column only
								$sql = "SELECT REMARK, CARGO_DESCRIPTION, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') DATE_REC, SUM(QTY_RECEIVED) THE_REC, SUM(QTY_RECEIVED - NVL(CA_I.THE_CHANGE, 0)) QTY_LEFT, SUM(QTY_EXPECTED) THE_EXPEC,
											SUM(((QTY_RECEIVED - NVL(CA_I.THE_CHANGE, 0)) / QTY_RECEIVED) * WEIGHT) WT_LEFT,
											SUM(NVL(CA_P.THE_CHANGE, 0)) PENDING,
											NVL(COMMODITY_UNIT, QTY_UNIT) THE_UNIT,
											CT.PALLET_ID
										FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, CARGO_TRACKING_ADDITIONAL_DATA CTAD, 
											(SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM, NVL(SUM(DECODE(SERVICE_CODE, 7, -1 * QTY_CHANGE, QTY_CHANGE)), 0) THE_CHANGE
												FROM CARGO_ACTIVITY
												WHERE (
														(SERVICE_CODE IN (6, 7, 9, 13) AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID'))
														OR
														(SERVICE_CODE = '11' AND ACTIVITY_NUM != '1')
													  )
													  AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY') 
												GROUP BY PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM
											) CA_I,
											(SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM, NVL(SUM(DECODE(SERVICE_CODE, 7, -1 * QTY_CHANGE, QTY_CHANGE)), 0) THE_CHANGE
												FROM CARGO_ACTIVITY
												WHERE (
														(SERVICE_CODE IN (6, 7, 9, 13) AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID'))
														OR
														(SERVICE_CODE = '11' AND ACTIVITY_NUM != '1')
													  )
													  AND DATE_OF_ACTIVITY > TO_DATE('".$asof_date."','MM/DD/YYYY') 
												GROUP BY PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM
											) CA_P
										WHERE QTY_RECEIVED > 0
											AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
											AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
											AND CT.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
											AND CT.COMMODITY_CODE = '".$comm_row['COMMODITY_CODE']."'
											AND CT.REMARK = '".$DO_row['REMARK']."'
											AND CT.RECEIVER_ID='".$cust."'".$extra_sql."
											AND CT.PALLET_ID = CTAD.PALLET_ID
											AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
											AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
											AND CT.PALLET_ID = CA_I.PALLET_ID(+)
											AND CT.RECEIVER_ID = CA_I.CUSTOMER_ID(+)
											AND CT.ARRIVAL_NUM = CA_I.ARRIVAL_NUM(+)
											AND CT.PALLET_ID = CA_P.PALLET_ID(+)
											AND CT.RECEIVER_ID = CA_P.CUSTOMER_ID(+)
											AND CT.ARRIVAL_NUM = CA_P.ARRIVAL_NUM(+)
											AND CT.REMARK != 'NO DO'
										GROUP BY REMARK, CARGO_DESCRIPTION, NVL(COMMODITY_UNIT, QTY_UNIT), CT.PALLET_ID
										HAVING SUM(QTY_RECEIVED - NVL(CA_I.THE_CHANGE, 0)) > 0
										ORDER BY REMARK, CT.PALLET_ID";
		//						echo $sql."<bR><br>";
								ora_parse($cargo_cursor, $sql);
								ora_exec($cargo_cursor);
								if(!ora_fetch_into($cargo_cursor, $cargo_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
									$output = "<tr><td colspan=\"11\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Cargo In-House for Given Parameters</b></font></td></tr>";
									DualWrite($handle, $output);
								} else {
									do {
//										$BOL = $cargo_row['REMARK'];
										$pallet = $cargo_row['PALLET_ID'];
										$Mark = $cargo_row['CARGO_DESCRIPTION'];
										$DateRcvd = $cargo_row['DATE_REC'];
										$QtyRcvd = $cargo_row['THE_REC'];
										$QtyExpec = $cargo_row['THE_EXPEC'];
										$Unt1 = $cargo_row['THE_UNIT'];
										$Qty1 = $cargo_row['QTY_LEFT'];
										$WtLeft = $cargo_row['WT_LEFT'];
										$pending = $cargo_row['PENDING'];
										
										$comm_total_qty_rec += $QtyRcvd;
										$comm_total_qty1 += $Qty1;
										$comm_total_pending += $pending;
										$comm_total_wt_left += $WtLeft;

										$ves_total_qty_rec += $QtyRcvd;
										$ves_total_qty1 += $Qty1;
										$ves_total_pending += $pending;
										$ves_total_wt_left += $WtLeft;

										$DO_total_qty_rec += $QtyRcvd;
										$DO_total_qty1 += $Qty1;
										$DO_total_pending += $pending;
										$DO_total_wt_left += $WtLeft;

										$output = "<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><font size=\"2\" face=\"Verdana\">".$pallet."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$Mark."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$DateRcvd."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$QtyExpec."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$QtyRcvd."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$Qty1."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$Unt1."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$pending."</font></td>
											<td><font size=\"2\" face=\"Verdana\">".$WtLeft."</font></td>
										</tr>";
										DualWrite($handle, $output);
									} while(ora_fetch_into($cargo_cursor, $cargo_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
								}
								// DO subtotal
								$output = "<tr bgcolor=\"9999FF\">
									<td bgcolor=\"FFFFFF\">&nbsp;</td>
									<td bgcolor=\"FFFFFF\">&nbsp;</td>
									<td colspan=\"5\"><font size=\"2\" face=\"Verdana\"><b>".$DO_row['REMARK']." TOTAL</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_qty_rec."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_qty1."</b></font></td>
									<td>&nbsp;</td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_pending."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$DO_total_wt_left."</b></font></td>
								</tr>";
								DualWrite($handle, $output);
							} while(ora_fetch_into($DO_cursor, $DO_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
						}

						// commodity subtotal
						$output = "<tr bgcolor=\"99CC33\">
							<td bgcolor=\"FFFFFF\">&nbsp;</td>
							<td colspan=\"6\"><font size=\"2\" face=\"Verdana\"><b>".$comm_name." TOTAL</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty_rec."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty1."</b></font></td>
							<td>&nbsp;</td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_pending."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_wt_left."</b></font></td>
						</tr>";
						DualWrite($handle, $output);

					} while(ora_fetch_into($comm_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
				}
		// vessel total

				$output = "<tr bgcolor=\"99CCFF\">
					<td colspan=\"7\"><font size=\"2\" face=\"Verdana\"><b>".$ves_name." TOTAL</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty_rec."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty1."</b></font></td>
					<td>&nbsp;</td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_pending."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_wt_left."</b></font></td>
				</tr>";
				DualWrite($handle, $output);

			} while(ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}




function DualWrite($handle, $output){
	echo $output;
	fwrite($handle, $output);
}