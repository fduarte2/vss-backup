<?
	include("useful_info.php");
	include("get_employee_name.php");
	$main_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$short_term_data_cursor2 = ora_open($conn);

	$current_railcar = "Beef Stew";
	$selected_railcar = $HTTP_POST_VARS['railcar'];
	$selected_receive_date = $HTTP_POST_VARS['receive_date'];
	$selected_dock_ticket = $HTTP_POST_VARS['dock_ticket'];
	$hide = $HTTP_POST_VARS['hide']
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<?
	if($hide != "hide"){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="IB_scan_tally.php" method="post">
	<tr>
		<td align="center" colspan="2"><font size="4" face="Verdana"><b>Inbound Tally(s)</b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">Please enter one or more of the following:</font></td>
	</tr>
	<tr>
		<td width="15%" align="left">Railcar #:</td>
		<td><input name="railcar" type="text" size="15" maxlength="15" value="<? echo $selected_railcar; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left">Receive Date:</td>
		<td><input name="receive_date" type="text" size="15" maxlength="15" value="<? echo $selected_receive_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.receive_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left">Dock Ticket:</td>
		<td><input name="dock_ticket" type="text" size="15" maxlength="15" value="<? echo $selected_dock_ticket; ?>"></td>
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
	if($selected_railcar != "" || $selected_receive_date != "" || $selected_dock_ticket != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr><td align="center"><font size="4" face="Verdana"><b>Inbound Tally(s)</b></font></td></tr>
</table>
<?
		$sql = "SELECT DISTINCT CT.ARRIVAL_NUM THE_VES, CP.CUSTOMER_NAME THE_NAME 
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP 
				WHERE CT.REMARK = 'DOLEPAPERSYSTEM' 
					AND CT.PALLET_ID = CA.PALLET_ID 
					AND CT.RECEIVER_ID = CP.CUSTOMER_ID 
					AND CA.SERVICE_CODE = '8' 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM";
		if($selected_railcar != ""){
			$sql .= " AND CT.ARRIVAL_NUM = '".$selected_railcar."'";
		}
		if($selected_receive_date != ""){
			$sql .= " AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$selected_receive_date."'";
		}
		if($selected_dock_ticket != ""){
			$sql .= " AND CT.BOL = '".$selected_dock_ticket."'";
		}
//		echo $sql;
		ora_parse($main_cursor, $sql);
		ora_exec($main_cursor);
		if(!ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<hr><font color=\"#ff0000\">No Inbound Railcars match the typed criteria.</font>
<?
		} else {
			$output = "";
			do {
				$current_railcar = $row['THE_VES'];
				$customer = $row['THE_NAME'];
				$any_damage = FALSE;
				$total_rolls = 0;
				$total_weight = 0;
				$total_MSF = 0;

				$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA WHERE ORDER_NUM = '".$current_railcar."' AND CA.SERVICE_CODE = '8'";
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($short_term_row['THE_COUNT'] > 0){
					$output .= "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
									<tr><td><b>Railcar:  ".$current_railcar."</b> </td></tr>
									<tr><td><b>Customer:  ".$customer."</b> </td></tr>
								</table>";

					$output .= "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
									<tr>
										<td><b>Dock Ticket#</b></td>
										<td><b>Barcode</b></td>
										<td><b>Mark</b></td>
										<td><b>QTY</b></td>
										<td><b>WT</b></td>
										<td><b>(ST)</b></td>
										<td><b>MSF</b></td>
										<td><b>Checker</b></td>
										<td><b>DMG</b></td>
										<td><b>Date Received</b></td>
									</tr>";

//		EMPLOYEE PER		SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID,		AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PER.EMPLOYEE_ID, -4)
					$sql = "SELECT CT.PALLET_ID, CT.CARGO_DESCRIPTION, CA.QTY_CHANGE, CT.QTY_UNIT, CT.WEIGHT, 
								ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) SHORT_TONS, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_REC, CT.BOL, NVL(VARIETY, '0') THE_LINEAR_FEET, CARGO_SIZE, CA.CUSTOMER_ID 
							FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, UNIT_CONVERSION_FROM_BNI UC 
							WHERE CT.PALLET_ID = CA.PALLET_ID 
								AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
								AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
								AND CA.ORDER_NUM = '".$current_railcar."' 
								AND CA.SERVICE_CODE = '8' 
								AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM 
								AND UC.SECONDARY_UOM = 'TON'";
					if($selected_receive_date != ""){
						$sql .= " AND TO_CHAR(CA.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$selected_receive_date."'";
					}
					if($selected_dock_ticket != ""){
						$sql .= " AND CT.BOL = '".$selected_dock_ticket."'";
					}
					$sql .= " ORDER BY CT.BOL, CT.PALLET_ID";

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

						$msf = round(($row['THE_LINEAR_FEET'] * ($row['CARGO_SIZE'] / 12)) / 1000, 3);
						$total_msf += $msf;

						$emp_name = get_employee_for_print($row['PALLET_ID'], $current_railcar, $row['CUSTOMER_ID'], "1", $conn);
						//<td>".$row['LOGIN_ID']."</td>
						$output .= "<tr>
										<td>".$row['BOL']."</td>
										<td>".$row['PALLET_ID']."</td>
										<td>".$row['CARGO_DESCRIPTION']."</td>
										<td>".$row['QTY_CHANGE']."</td>
										<td>".$row['WEIGHT']." LB / ".round(($row['WEIGHT'] / 2.2), 2)." KG</td>
										<td>".$row['SHORT_TONS']."</td>
										<td>".$msf."</td>
										<td>".$emp_name."</td>
										<td>".$display_damage."</td>
										<td>".$row['THE_REC']."</td>
									</tr>";

					}
					$output .= "<tr>
									<td colspan=\"3\"><b>Totals:</b></td>
									<td>".$total_rolls."</td>
									<td>".$total_weight." LB / ".round(($total_weight / 2.2), 2)." KG</td>
									<td>&nbsp;</td>
									<td>".$total_msf."</td>
									<td colspan=\"3\">&nbsp;</td>
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

						$sql = "SELECT ROLL, DMG_TYPE, OCCURRED, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') WHEN_REC, QUANTITY || QTY_TYPE THE_QUAN, TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI') THE_CLEARED FROM DOLEPAPER_DAMAGES WHERE 1 = 1 ";
						if($selected_railcar != ""){
							$sql .= " AND ROLL IN (SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$selected_railcar."' AND SERVICE_CODE = '8' AND ACTIVITY_DESCRIPTION IS NULL) ";
						}
						if($selected_receive_date != ""){
							$sql .= " AND ROLL IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = '".$selected_receive_date."') ";
						}
						if($selected_dock_ticket != ""){
							$sql .= " AND DOCK_TICKET = '".$selected_dock_ticket."'";
						}
						$sql .= " AND DOCK_TICKET IN (SELECT BOL FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$current_railcar."') ORDER BY ROLL, DATE_ENTERED";
//						echo $sql;
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

			$filename = "tempXLS/DTi-".date('mdYhis').".xls";
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