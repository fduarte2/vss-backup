<?
	include("useful_info.php");
	$CT_cursor = ora_open($conn);
	$CA_cursor = ora_open($conn);
	$DMG_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$Barcode = $HTTP_POST_VARS['pallet'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="dole_pallet_info.php" method="post">
	<tr>
		<td width="15%" align="left">Pallet #:</td>
		<td><input name="pallet" type="text" size="15" maxlength="15" value="<? echo $pallet; ?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Pallets"></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $Barcode != ""){
		$sql = "SELECT CT.ARRIVAL_NUM, CT.BATCH_ID, CT.WEIGHT, CT.WEIGHT_UNIT, NVL(TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YY HH24:MI'), 'NONE') THE_DATE, CT.QTY_IN_HOUSE, CT.BOL, CT.RECEIVER_ID FROM CARGO_TRACKING CT WHERE PALLET_ID = '".$Barcode."'";
		ora_parse($CT_cursor, $sql);
		ora_exec($CT_cursor, $sql);
		if(!ora_fetch_into($CT_cursor, $CT_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font color=\"#FF0000\">No Matches for $Barcode</font>";
		}else{
			do{
				$received = $CT_row['THE_DATE'];
				$arrival_num = $CT_row['ARRIVAL_NUM'];
				$weight = $CT_row['WEIGHT']." ".$CT_row['WEIGHT_UNIT'];
				$code = $CT_row['BATCH_ID'];
				$dock_ticket = $CT_row['BOL'];
				$Customer = $CT_row['RECEIVER_ID'];

				$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND DOCK_TICKET = '".$dock_ticket."'";
				$ora_success = ora_parse($short_term_data_cursor, $sql);
				$ora_success = ora_exec($short_term_data_cursor, $sql);
				ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				
				if($short_term_row['THE_COUNT'] > 0){
					$DMG = "Y";
				} else {
					$DMG = "N";
				}

				if($received == "NONE"){
					$status = "Not Received";
				} elseif($CT_row['QTY_IN_HOUSE'] == "0"){
					$sql = "SELECT ORDER_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YY HH24:MI') THE_DATE FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '6' AND PALLET_ID = '".$Barcode."' AND ACTIVITY_DESCRIPTION IS NULL AND ARRIVAL_NUM = '".$arrival_num."'";
					$ora_success = ora_parse($short_term_data_cursor, $sql);
					$ora_success = ora_exec($short_term_data_cursor, $sql);
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

					$status = "Shipped (ORD#: ".$short_term_data_row['ORDER_NUM']." ON: ".$short_term_data_row['THE_DATE'].")";
				} else {
					$status = "In House";
				}

				$sql = "SELECT CUSTOMER_NAME
						FROM CUSTOMER_PROFILE
						WHERE CUSTOMER_ID = '".$Customer."'";
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
		<td align="left"><font size="2" face="Verdana"><b><? echo $arrival_num; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Dock Ticket #:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $dock_ticket; ?></b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Code #:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $code; ?></b></font></td>
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
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Status:</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b><? echo $status; ?></b></font></td>
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
		<td colspan="7" align="center"><font size="3" face="Verdana"><b>Damage Report:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Damage ID:</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Recorded:</b></font></td>
		<td><font size="2" face="Verdana"><b>Recorded By:</b></font></td>
		<td><font size="2" face="Verdana"><b>Responsibility:</b></font></td>
		<td><font size="2" face="Verdana"><b>Damage Type:</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY:</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Cleared:</b></font></td>
	</tr>
<?
					$sql = "SELECT TO_CHAR(DATE_ENTERED, 'MM/DD/YY HH24:MI') DATE_ENT, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YY HH24:MI'), 'NONE') DATE_CLEAR, DAMAGE_ID, OCCURRED, CHECKER_ID, DMG_TYPE, QUANTITY, QTY_TYPE FROM DOLEPAPER_DAMAGES WHERE ROLL = '".$Barcode."' AND CUSTOMER_ID = '".$Customer."' AND DOCK_TICKET = '".$dock_ticket."' ORDER BY DAMAGE_ID";
					$ora_success = ora_parse($DMG_cursor, $sql);
					$ora_success = ora_exec($DMG_cursor, $sql);
					while(ora_fetch_into($DMG_cursor, $DMG_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						while(strlen($DMG_row['CHECKER_ID']) < 4){
							$DMG_row['CHECKER_ID'] = "0".$DMG_row['CHECKER_ID'];
						}
//						$sql = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '".$DMG_row['CHECKER_ID']."'";
						$sql = "SELECT EMPLOYEE_NAME 
								FROM EMPLOYEE 
								WHERE SUBSTR(EMPLOYEE_ID, -4) = '".$DMG_row['CHECKER_ID']."'
									AND EMPLOYEE_TYPE_ID != 'INACTE'";
						$ora_success = ora_parse($short_term_data_cursor, $sql);
						$ora_success = ora_exec($short_term_data_cursor, $sql);
						ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$emp_login = $short_term_data_row['EMPLOYEE_NAME'];
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DAMAGE_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DATE_ENT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $emp_login; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['OCCURRED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DMG_TYPE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['QUANTITY'].$DMG_row['QTY_TYPE']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $DMG_row['DATE_CLEAR']; ?></font></td>
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