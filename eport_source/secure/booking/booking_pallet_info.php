<?
	include("useful_info.php");
	$CT_cursor = ora_open($conn);
	$CA_cursor = ora_open($conn);
	$DMG_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

	$Barcode = $_GET['pallet'];
?>
<form name="the_form" action="booking_pallet_info.php" method="get">
	<table border="0" width="100%" cellpadding="4" cellspacing="0">
		<tr>
			<td width="15%" align="left">Pallet #:</td>
			<td><input name="pallet" type="text" size="15" maxlength="15" value="<? echo $pallet; ?>"></td>
		</tr>
		<tr>
			<td colspan="2" align="left"><button type="submit">Retrieve Pallet</button></td>
		</tr>
	</table>
</form>
<?
	if($Barcode != ""){
		if($eport_customer_id != 0){
			$cust_sql = " AND CT.RECEIVER_ID = '".$eport_customer_id."' ";
		} else {
			$cust_sql = " ";
		}
		$sql = "SELECT NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'NONE') THE_DATE, QTY_IN_HOUSE, CT.ARRIVAL_NUM, BAD.BOL, 
					BAD.BOOKING_NUM, BAD.ORDER_NUM P_O, CT.WEIGHT, CT.WEIGHT_UNIT, NVL(CT.CARGO_STATUS, 'GOOD') THE_STAT, CT.RECEIVER_ID,
					ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA,
					bad.warehouse_code
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD,
					UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
				WHERE CT.PALLET_ID = '".$Barcode."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					".$cust_sql."
					AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'";
		$ora_success = ora_parse($CT_cursor, $sql);
		$ora_success = ora_exec($CT_cursor, $sql);
		if(!ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">No Matches for $Barcode</font>";
		}else{
			do {
				$cust = $CT_row['RECEIVER_ID'];
				$received = $CT_row['THE_DATE'];
				$IH = $CT_row['QTY_IN_HOUSE'];
				$LR = $CT_row['ARRIVAL_NUM'];
				$BoL = $CT_row['BOL'];
				$booking = $CT_row['BOOKING_NUM'];
				$PO = $CT_row['P_O'];
				$weight = $CT_row['WEIGHT']." ".$CT_row['WEIGHT_UNIT'];
				$ship_status = $CT_row['THE_STAT'];
				$width_cm = $CT_row['THE_WIDTH'];
				$dia_cm = $CT_row['THE_DIA'];
				$warehouse_code = $CT_row['WAREHOUSE_CODE'];

				$sql = "SELECT COUNT(*) THE_COUNT FROM BOOKING_DAMAGES WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$LR."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				
				if($short_term_row['THE_COUNT'] > 0){
					$DMG = "Y";
				} else {
					$DMG = "N";
				}

				if($received == "NONE"){
					$IH_status = "Not Received";
				} elseif($CT_row['QTY_IN_HOUSE'] == "0"){
					$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YY HH24:MI') THE_DATE FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND PALLET_ID = '".$Barcode."' AND ACTIVITY_DESCRIPTION IS NULL AND ARRIVAL_NUM = '".$LR."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

					$IH_status = "Shipped (ORD#: ".$short_term_data_row['ORDER_NUM']." ON: ".$short_term_data_row['THE_DATE'].")";
				} else {
					$IH_status = "In House";
				}

				$sql = "SELECT CUSTOMER_NAME
						FROM CUSTOMER_PROFILE
						WHERE CUSTOMER_ID = '".$cust."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$custname = $short_term_row['CUSTOMER_NAME'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2">&nbsp;<hr></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Arrival #:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $LR; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>BoL #:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $BoL; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Booking #:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $booking; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>PO #:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $PO; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Warehouse Code:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $warehouse_code; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Width:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $width_cm." / ".round($width_cm / 2.54, 1)."\""; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Dia:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $dia_cm." / ".round($dia_cm / 2.54, 1)."\""; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Weight:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $weight; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Received:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $received; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>HOLD Status:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $ship_status; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>In-House Status:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $IH_status; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Customer:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $custname; ?></b></font></td>
	</tr>
</table>
<?
				if($DMG == "Y"){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="8" align="center"><font size="3" face="Verdana"><b>Damage Report:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Damage ID:</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Recorded:</b></font></td>
		<td><font size="2" face="Verdana"><b>Recorded By:</b></font></td>
		<td><font size="2" face="Verdana"><b>Responsibility:</b></font></td>
		<td><font size="2" face="Verdana"><b>Damage Type:</b></font></td>
		<td><font size="2" face="Verdana"><b>Extra Info:</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Cleared:</b></font></td>
		<td><font size="2" face="Verdana"><b>Cleared By:</b></font></td>
	</tr>
<?
					$sql = "SELECT TO_CHAR(DATE_ENTERED, 'MM/DD/YY HH24:MI') DATE_ENT, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YY HH24:MI'), 'NONE') DATE_CLEAR, DAMAGE_ID, OCCURRED, CHECKER_ENTERED, CHECKER_CLEARED, DAMAGE_TYPE, EXTRA_DESC FROM BOOKING_DAMAGES WHERE PALLET_ID = '".$Barcode."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$LR."' ORDER BY DAMAGE_ID";
					$ora_success = ora_parse($DMG_cursor, $sql);
					$ora_success = ora_exec($DMG_cursor, $sql);
					while(ora_fetch_into($DMG_cursor, $DMG_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						while(strlen($DMG_row['CHECKER_ENTERED']) < 4){
							$DMG_row['CHECKER_ENTERED'] = "0".$DMG_row['CHECKER_ENTERED'];
						}
//						$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$DMG_row['CHECKER_ENTERED']."'";
						$sql = "SELECT EMPLOYEE_NAME 
								FROM EMPLOYEE 
								WHERE SUBSTR(EMPLOYEE_ID, -4) = '".$DMG_row['CHECKER_ENTERED']."'
									AND EMPLOYEE_TYPE_ID != 'INACTE'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$entered_login = $short_term_data_row['EMPLOYEE_NAME'];

						while(strlen($DMG_row['CHECKER_CLEARED']) < 4){
							$DMG_row['CHECKER_CLEARED'] = "0".$DMG_row['CHECKER_CLEARED'];
						}
//						$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$DMG_row['CHECKER_CLEARED']."'";
						$sql = "SELECT EMPLOYEE_NAME 
								FROM EMPLOYEE 
								WHERE SUBSTR(EMPLOYEE_ID, -4) = '".$DMG_row['CHECKER_ENTERED']."'
									AND EMPLOYEE_TYPE_ID != 'INACTE'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$cleared_login = $short_term_data_row['EMPLOYEE_NAME'];
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DAMAGE_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DATE_ENT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $entered_login; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['OCCURRED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DAMAGE_TYPE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['EXTRA_DESC']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DATE_CLEAR']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $cleared_login; ?></font></td>
	</tr>
<?
					}
				}
?>
</table>
<?
			} while(ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>