<?
	include("useful_info.php");
	include("get_employee_name.php");
	$main_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$short_term_data_cursor2 = ora_open($conn);

	$current_order_num = "Beef Stew";
	$selected_order_num = $HTTP_POST_VARS['order_num'];
	$selected_load_date = $HTTP_POST_VARS['load_date'];
	$selected_vessel = $HTTP_POST_VARS['vessel'];
	$selected_container = $HTTP_POST_VARS['container'];
	$hide = $HTTP_POST_VARS['hide']
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<?
	if($hide != "hide"){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="scan_tally.php" method="post">
	<tr>
		<td align="center" colspan="2"><font size="4" face="Verdana"><b>Outbound Tally(s)</b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">Please enter one or more of the following:</font></td>
	</tr>
	<tr>
		<td width="15%" align="left">Order #:</td>
		<td><input name="order_num" type="text" size="15" maxlength="15" value="<? echo $selected_order_num; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left">Load Date:</td>
		<td><input name="load_date" type="text" size="15" maxlength="15" value="<? echo $selected_load_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left">Vessel:</td>
		<td><input name="vessel" type="text" size="15" maxlength="15" value="<? echo $selected_vessel; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left">Container:</td>
		<td><input name="container" type="text" size="15" maxlength="15" value="<? echo $selected_container; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="checkbox" value="hide" name="hide" checked>&nbsp;&nbsp;Check here to hide the search boxes (useful for printing)</td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="orders" value="Generate Report"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>
<?
	}
	if($selected_order_num != "" || $selected_load_date != "" || $selected_vessel != "" || $selected_container != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr><td align="center"><font size="4" face="Verdana"><b>Outbound Tally(s)</b></font></td></tr>
</table>
<?
		$sql = "SELECT DO.ORDER_NUM, TO_CHAR(DO.SAIL_DATE, 'MM/DD/YYYY') THE_SAIL, TO_CHAR(DO.LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, VP.VESSEL_NAME, DD.DESTINATION, DS.ST_DESCRIPTION, DO.CONTAINER_ID,
					DO.SEAL, DO.BOOKING_NUM
			FROM DOLEPAPER_ORDER DO, DOLEPAPER_STATUSES DS, VESSEL_PROFILE VP, DOLEPAPER_DESTINATIONS DD 
			WHERE DO.ARRIVAL_NUM = VP.LR_NUM 
			AND DO.STATUS = DS.STATUS 
			AND DO.DESTINATION_NB = DD.DESTINATION_NB";
		if($selected_order_num != ""){
			$sql .= " AND DO.ORDER_NUM = '".$selected_order_num."'";
		}
		if($selected_load_date != ""){
			$sql .= " AND TO_CHAR(DO.LOAD_DATE, 'MM/DD/YYYY') = '".$selected_load_date."'";
		}
		if($selected_vessel != ""){
			$sql .= " AND DO.ARRIVAL_NUM = '".$selected_vessel."'";
		}
		if($selected_container != ""){
			$sql .= " AND DO.CONTAINER_ID = '".$selected_container."'";
		}
//		echo $sql;
		ora_parse($main_cursor, $sql);
		ora_exec($main_cursor);
		if(!ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<hr><font color=\"#ff0000\">No Orders match the type criteria.</font>
<?
		} else {
			$output = "";
			do {
				$order_num = $row['ORDER_NUM'];
				$any_damage = FALSE;
				$total_rolls = 0;
				$total_weight = 0;

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA WHERE ORDER_NUM = '".$order_num."' AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM')";
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] > 0){

					$sql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI') THE_END FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE ORDER_NUM = '".$order_num."' AND CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CT.REMARK = 'DOLEPAPERSYSTEM'"; 
					ora_parse($short_term_data_cursor, $sql);
					ora_exec($short_term_data_cursor);
					ora_fetch_into($short_term_data_cursor, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$first_scan = $row2['THE_START'];
					$last_scan = $row2['THE_END'];

					$output .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\">
									<tr><td><b>Order:  ".$row['ORDER_NUM']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
											<td><b>Booking #:  ".$row['BOOKING_NUM']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
											<td><b>Seal #:  ".$row['SEAL']."</b></td></tr>
									<tr><td colspan=\"3\"><b>Vessel:  ".$row['VESSEL_NAME']."</b> </td></tr>
									<tr><td colspan=\"3\"><b>Load Date:  ".$row['THE_LOAD']."</b> </td></tr>
									<tr><td colspan=\"3\"><b>Sail Date:  ".$row['THE_SAIL']."</b> </td></tr>
									<tr><td colspan=\"3\"><b>Container:  ".$row['CONTAINER_ID']."</b> </td></tr>
									<tr><td colspan=\"3\"><b>Destination:  ".$row['DESTINATION']."</b> </td></tr>
									<tr><td colspan=\"3\"><b>Status:  ".$row['ST_DESCRIPTION']."</b> </td></tr>
									<tr><td colspan=\"3\"><b>First Scan:  ".$first_scan."</b> </td></tr>
									<tr><td colspan=\"3\"><b>Last Scan:  ".$last_scan."</b> </td></tr>
									<tr><td colspan=\"3\">&nbsp;</td></tr>
								</table>";

					$output .= "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
									<tr>
										<td><b>Dock Ticket#</b></td>
										<td><b>Barcode</b></td>
										<td><b>Mark</b></td>
										<td><b>QTY</b></td>
										<td><b>WT</b></td>
										<td><b>(ST)</b></td>
										<td><b>Checker</b></td>
										<td><b>DMG</b></td>
										<td><b>IB #</b></td>
									</tr>";

//	, EMPLOYEE PER		SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID,		AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PER.EMPLOYEE_ID, -4)
					$sql = "SELECT CT.PALLET_ID, CT.CARGO_DESCRIPTION, CA.QTY_CHANGE, CT.QTY_UNIT, CT.WEIGHT, CA.CUSTOMER_ID,
								CT.ARRIVAL_NUM, ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) SHORT_TONS, CT.BOL, CA.ACTIVITY_NUM 
							FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, UNIT_CONVERSION_FROM_BNI UC 
							WHERE CT.PALLET_ID = CA.PALLET_ID 
								AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
								AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
								AND CA.ORDER_NUM = '".$order_num."' 
								AND SERVICE_CODE = '6' 
								AND ACTIVITY_DESCRIPTION IS NULL 
								AND CT.REMARK = 'DOLEPAPERSYSTEM' 
								AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM 
								AND UC.SECONDARY_UOM = 'TON' 
							ORDER BY CT.PALLET_ID";
					ora_parse($short_term_data_cursor, $sql);
					ora_exec($short_term_data_cursor);
					while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$row['PALLET_ID']."' AND DOCK_TICKET = '".$row['BOL']."'";
						ora_parse($short_term_data_cursor2, $sql);
						ora_exec($short_term_data_cursor2);
						ora_fetch_into($short_term_data_cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

						if($row2['THE_COUNT'] > 0){
							$display_damage = 'Y';
							$any_damage = TRUE;
						} else {
							$display_damage = 'N';
						}

						$total_rolls++;
						$total_weight += $row['WEIGHT'];

						$emp_name = get_employee_for_print($row['PALLET_ID'], $row['ARRIVAL_NUM'], $row['CUSTOMER_ID'], $row['ACTIVITY_NUM'], $conn);
						//<td>".$row['LOGIN_ID']."</td>
						$output .= "<tr>
										<td>".$row['BOL']."</td>
										<td>".$row['PALLET_ID']."</td>
										<td>".$row['CARGO_DESCRIPTION']."</td>
										<td>".$row['QTY_CHANGE']."</td>
										<td>".$row['WEIGHT']." LB / ".round(($row['WEIGHT'] / 2.2), 2)." KG</td>
										<td>".$row['SHORT_TONS']."</td>
										<td>".$emp_name."</td>
										<td>".$display_damage."</td>
										<td>".$row['ARRIVAL_NUM']."</td>
									</tr>";
					}

					$output .= "<tr>
									<td colspan=\"3\"><b>Totals:</b></td>
									<td>".$total_rolls."</td>
									<td>".$total_weight." LB / ".round(($total_weight / 2.2), 2)." KG</td>
									<td colspan=\"4\">&nbsp;</td>
								</tr>
							</table>";

					if($any_damage){
						$output .= "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
										<tr>
											<td align=\"center\" colspan=\"6\"><font size=\"2\" face=\"Verdana\"><b>Damage Recap:</b></font></td>
										</tr>
										<tr>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">Barcode:</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">Damage Type:</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">Damage QTY (if applicable):</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">Recorded on:</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">Was Damaged:</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">Cleared to Ship (if originally Rejected):</font></td>
										</tr>";

						$sql = "SELECT ROLL, DMG_TYPE, OCCURRED, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') WHEN_REC, QUANTITY || QTY_TYPE THE_QUAN, TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI') THE_CLEARED FROM DOLEPAPER_DAMAGES WHERE ROLL IN (SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order_num."' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL) ORDER BY ROLL, DATE_ENTERED";
						ora_parse($short_term_data_cursor, $sql);
						ora_exec($short_term_data_cursor);
						while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$output .= "<tr>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$short_term_row['ROLL']."</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$short_term_row['DMG_TYPE']."</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$short_term_row['THE_QUAN']."&nbsp;</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$short_term_row['WHEN_REC']."</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$short_term_row['OCCURRED']."</font></td>
											<td align=\"left\"><font size=\"2\" face=\"Verdana\">".$short_term_row['THE_CLEARED']."&nbsp;</font></td>
										</tr>";
						}
						$output .= "</table>";
					}
					$output .= "<br><br>";
				}
			} while(ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			$filename = "tempXLS/DT-".date('mdYhis').".xls";
			$fp = fopen($filename, "w");
			if(!$fp){
				echo "can not open file for writing, please contact the PoW IT department";
				exit;
			}
			fwrite($fp, $output);
			fclose($fp);
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="3" face="Verdana"><b><a href="<? echo $filename; ?>">Right-Click and choose "Save Target As..." for the Tab-delimited (Excel) version.</a></b></font></td>
	</tr>
</table>

<?
			echo $output;		
		}
	}
?>