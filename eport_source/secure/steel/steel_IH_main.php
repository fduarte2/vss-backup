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


	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$date_cursor = ora_open($conn);
	$vessel_cursor = ora_open($conn);
	$comm_cursor = ora_open($conn);
	$cargo_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

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

	// give date a default of today
	if($asof_date == ""){
		$asof_date = date('m/d/Y');
	}

	if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $asof_date)) {
		echo "<font size=3 face=Verdana color=#FF0000>The As-Of date must be in MM/DD/YYYY format (was entered as ".$from_date.")</font><br>";
		$from_date = "";
	}

?>
<script language="JavaScript" src="../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">InHouse Values
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="steel_IH.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>As-Of Start of Day:</b></font></td>
		<td align="left"><input name="asof_date" type="text" size="10" maxlength="10" value="<? echo $asof_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.asof_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Customer:</b></font></td>
		<td align="left"><select name="cust">
<?
	$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME 
			FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST_RF CECL 
			WHERE CECL.EPORT_LOGIN = '".$user."' 
				AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
		UNION
			SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
			FROM CUSTOMER_PROFILE CP
			WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
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
			WHERE LR_NUM IN
				(SELECT LR_NUM FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
				WHERE CT.LOT_NUM = CM.CONTAINER_NUM
				AND CT.DATE_RECEIVED > SYSDATE - 730
				AND OWNER_ID IN 
					(SELECT CP.CUSTOMER_ID 
						FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST_RF CECL 
						WHERE CECL.EPORT_LOGIN = '".$user."' 
							AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
					UNION
						SELECT CP.CUSTOMER_ID
						FROM CUSTOMER_PROFILE CP
						WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
					)
				)
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
						AND OWNER_ID IN
						(SELECT CP.CUSTOMER_ID 
							FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST_RF CECL 
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
		$output = "<table border=\"2\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
				<tr>
					<td bgcolor=\"99FFFF\"><font size=\"2\" face=\"Verdana\"><b>Vessel</b></font></td>
					<td bgcolor=\"CCFF99\"><font size=\"2\" face=\"Verdana\"><b>Commodity</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>BoL</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Mark</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Date Received</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Qty Received</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>QTY1</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Unit1</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>QTY2</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Unit2</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Dmgd</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Withdrawn</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Left</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>Pending</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>WT Left</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>R/H</b></font></td>
				</tr>";
		DualWrite($handle, $output);

		if($vessel != "all_vessels"){
			$extra_sql .= " AND CM.LR_NUM = '".$vessel."' ";
		} else {
			// nothing
		}
		if($comm != "all_comms"){
			$extra_sql .= " AND CM.COMMODITY_CODE = '".$comm."' ";
		} else {
			// nothing
		}

		// 1st sub-totals:  by vessel
		$sql = "SELECT DISTINCT CM.LR_NUM FROM CARGO_TRACKING CT, CARGO_MANIFEST CM 
				WHERE QTY_RECEIVED > 0
					AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
					AND CT.LOT_NUM = CM.CONTAINER_NUM
					AND OWNER_ID='".$cust."'".$extra_sql."
					AND 
						(
							(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
							  WHERE LOT_NUM = CT.LOT_NUM
								AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
							) < CT.QTY_RECEIVED 
							OR
							(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
							 WHERE LOT_NUM = CT.LOT_NUM
								AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
							) IS NULL
						)
				ORDER BY CM.LR_NUM";
		ora_parse($vessel_cursor, $sql);
		ora_exec($vessel_cursor);
		if(!ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output = "<tr><td colspan=\"16\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
			DualWrite($handle, $output);
		} else {
			do {
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
						WHERE LR_NUM = '".$vessel_row['LR_NUM']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$ves_name = $vessel_row['LR_NUM']." - UNKNOWN";
				} else {
					$ves_name = $short_term_row['VESSEL_NAME'];
				}

				$output = "<tr><td colspan=\"16\" bgcolor=\"99FFFF\"><font size=\"2\" face=\"Verdana\"><b>".$ves_name."</b></font></td></tr>";
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
				$sql = "SELECT DISTINCT CM.COMMODITY_CODE FROM CARGO_TRACKING CT, CARGO_MANIFEST CM 
						WHERE QTY_RECEIVED > 0
							AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
							AND CT.LOT_NUM = CM.CONTAINER_NUM
							AND CM.LR_NUM = '".$vessel_row['LR_NUM']."'
							AND OWNER_ID='".$cust."'".$extra_sql."
							AND 
								(
									(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
									  WHERE LOT_NUM = CT.LOT_NUM
										AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
									) < CT.QTY_RECEIVED 
									OR
									(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
									 WHERE LOT_NUM = CT.LOT_NUM
										AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
									) IS NULL
								)
						ORDER BY CM.LR_NUM";
				ora_parse($comm_cursor, $sql);
				ora_exec($comm_cursor);
				if(!ora_fetch_into($comm_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$output = "<tr><td colspan=\"16\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
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

						$output = "<tr><td>&nbsp;</td><td colspan=\"15\" bgcolor=\"CCFF99\"><font size=\"2\" face=\"Verdana\"><b>".$comm_name."</b></font></td></tr>";
						DualWrite($handle, $output);

						$comm_total_qty_rec = 0;
						$comm_total_qty1 = 0;
						$comm_total_qty2 = 0;
						$comm_total_dmgd = 0;
						$comm_total_withdraw = 0;
						$comm_total_left = 0;
						$comm_total_pending = 0;
						$comm_total_wt_left = 0;

						// main SQL.
						$sql = "SELECT * FROM CARGO_TRACKING CT, CARGO_MANIFEST CM 
								WHERE QTY_RECEIVED > 0
									AND CT.DATE_RECEIVED <= TO_DATE('".$asof_date."','MM/DD/YYYY')
									AND CT.LOT_NUM = CM.CONTAINER_NUM
									AND CM.LR_NUM = '".$vessel_row['LR_NUM']."'
									AND CM.COMMODITY_CODE = '".$comm_row['COMMODITY_CODE']."'
									AND OWNER_ID='".$cust."'".$extra_sql."
									AND 
										(
											(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
											  WHERE LOT_NUM = CT.LOT_NUM
												AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
											) < CT.QTY_RECEIVED 
											OR
											(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY 
											 WHERE LOT_NUM = CT.LOT_NUM
												AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')
											) IS NULL
										)
								ORDER BY CM.LR_NUM";
						ora_parse($cargo_cursor, $sql);
						ora_exec($cargo_cursor);
						if(!ora_fetch_into($cargo_cursor, $cargo_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$output = "<tr><td colspan=\"16\" align=\"center\"><font size=\"2\" face=\"Verdana\"><b>No Records in System for Given Parameters</b></font></td></tr>";
							DualWrite($handle, $output);
						} else {
							do {
								$BOL = $cargo_row['CARGO_BOL'];
								$Mark = $cargo_row['CARGO_MARK'];
								$DateRcvd = $cargo_row['DATE_RECEIVED'];
								$QtyRcvd = $cargo_row['QTY_RECEIVED'];
								$QtyDmg = 0 + $cargo_row['QTY_DAMAGED'];
								$Unt1 = $cargo_row['QTY1_UNIT'];
								$Unt2 = $cargo_row['QTY2_UNIT'];
								if($cargo_row['MANIFEST_STATUS'] = "HOLD") {
									$RH = "H";
								} elseif($cargo_row['MANIFEST_STATUS'] = "RELEASED") {
									$RH = "R";
								} else {
									$RH = "&nbsp;";
								}
								
								$Qty1FromCM = $cargo_row['QTY_EXPECTED'];
								$Qty2FromCM = $cargo_row['QTY2_EXPECTED'];
								$WeightFromCM = $cargo_row['CARGO_WEIGHT'];

								//Calculate QTY1, QTY1 Left (same as QTY1), and QTY Withdrawn
								$sql = "SELECT SUM(QTY_CHANGE) QTY_CHANGE 
										FROM CARGO_ACTIVITY 
										WHERE LOT_NUM = '".$cargo_row['LOT_NUM']."'
											AND DATE_OF_ACTIVITY <= TO_DATE('".$asof_date."','MM/DD/YYYY')";
								ora_parse($Short_Term_Cursor, $sql);
								ora_exec($Short_Term_Cursor);
								if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
									$Withdrwn = 0;
								} else {
									$Withdrwn = 0 + $short_term_row['QTY_CHANGE'];
								}

								$Left = $QtyRcvd - $Withdrwn;
								$Qty1 = $Left;

								$WeightRatio = $WeightFromCM / $Qty1FromCM;
								$WtLeft = $WeightRatio * $Qty1;

								$Qty2Ratio = $Qty2FromCM / $Qty1FromCM;
								$Qty2 = $Qty2Ratio * $Qty1;


								//Pending calculation;
								$sql = "SELECT SUM(QTY1) QTY_1 FROM BNI_DUMMY_WITHDRAWAL  
										WHERE LR_NUM = '".$vessel_row['LR_NUM']."'
											AND COMMODITY_CODE = '".$comm_row['COMMODITY_CODE']."' 
											AND OWNER_ID = '".$cust."'
											AND BOL = '".$BOL."' 
											AND MARK = '".$Mark."' 
											AND STATUS IS NULL
											AND ORDER_DATE <= TO_DATE('".$asof_date."','MM/DD/YYYY')";
								ora_parse($Short_Term_Cursor, $sql);
								ora_exec($Short_Term_Cursor);
								if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
									$pending = 0;
								} else {
									$pending = 0 + $short_term_row['QTY_1'];
								}
								
                
								//Also add on the qty of cargo shipped on a date later than the Cut Off
								$sql = "select sum(QTY_CHANGE) QTY_1 
										from cargo_activity 
										where lot_num = '".$cargo_row['LOT_NUM']."'
											and date_of_activity > TO_DATE('".$asof_date."','MM/DD/YYYY')
											and SERVICE_CODE != '6120'";
								ora_parse($Short_Term_Cursor, $sql);
								ora_exec($Short_Term_Cursor);
								if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
									// nothing
								} else {
									$pending += $short_term_row['QTY_1'];
								}

								$comm_total_qty_rec += $QtyRcvd;
								$comm_total_qty1 += $Qty1;
								$comm_total_qty2 += $Qty2;
								$comm_total_dmgd += $QtyDmg;
								$comm_total_withdraw += $Withdrwn;
								$comm_total_left += $Left;
								$comm_total_pending += $pending;
								$comm_total_wt_left += $WtLeft;

								$ves_total_qty_rec += $QtyRcvd;
								$ves_total_qty1 += $Qty1;
								$ves_total_qty2 += $Qty2;
								$ves_total_dmgd += $QtyDmg;
								$ves_total_withdraw += $Withdrwn;
								$ves_total_left += $Left;
								$ves_total_pending += $pending;
								$ves_total_wt_left += $WtLeft;

								$output = "<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><font size=\"2\" face=\"Verdana\">".$BOL."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Mark."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$DateRcvd."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$QtyRcvd."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Qty1."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Unt1."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Qty2."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Unt2."&nbsp;</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$QtyDmg."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Withdrwn."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$Left."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$pending."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$WtLeft."</font></td>
									<td><font size=\"2\" face=\"Verdana\">".$RH."</font></td>
								</tr>";
								DualWrite($handle, $output);

							} while(ora_fetch_into($cargo_cursor, $cargo_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
						}
						// commodity subtotal
						$output = "<tr bgcolor=\"99CC33\">
							<td bgcolor=\"FFFFFF\">&nbsp;</td>
							<td colspan=\"4\"><font size=\"2\" face=\"Verdana\"><b>".$comm_name." TOTAL</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty_rec."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty1."</b></font></td>
							<td>&nbsp;</td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_qty2."</b></font></td>
							<td>&nbsp;</td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_dmgd."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_withdraw."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_left."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$comm_total_pending."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b".$comm_total_wt_left."</b></font></td>
							<td>&nbsp;</td>
						</tr>";
						DualWrite($handle, $output);

					} while(ora_fetch_into($comm_cursor, $comm_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
				}
		// vessel total

				$output = "<tr bgcolor=\"99CCFF\">
					<td colspan=\"5\"><font size=\"2\" face=\"Verdana\"><b>".$ves_name." TOTAL</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty_rec."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty1."</b></font></td>
					<td>&nbsp;</td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_qty2."</b></font></td>
					<td>&nbsp;</td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_dmgd."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_withdraw."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_left."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b>".$ves_total_pending."</b></font></td>
					<td><font size=\"2\" face=\"Verdana\"><b".$ves_total_wt_left."</b></font></td>
					<td>&nbsp;</td>
				</tr>";
				DualWrite($handle, $output);

			} while(ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}




function DualWrite($handle, $output){
	echo $output;
	fwrite($handle, $output);
}