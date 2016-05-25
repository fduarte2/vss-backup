<?
/*
*			Adam Walter, June 2010
*			Pages gives Barnett Inventory pallet list, as of
*			The exact time page is loaded, called from 
*			BookingRIGHTNOW.php
******************************************************************/



	include("useful_info.php");
/*	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}*/

	if($testflag == "LIVE"){
		$headerprint = "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
	} else {
		$headerprint = "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	}

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$cust = $HTTP_GET_VARS['cust'];
	$order_num = $HTTP_GET_VARS['order'];
	$booking = $HTTP_GET_VARS['book'];
	$width = $HTTP_GET_VARS['width'];
	$dia = $HTTP_GET_VARS['dia'];
	$avgwt = $HTTP_GET_VARS['avgwt'];
	$count = $HTTP_GET_VARS['count'];
	$grade = $HTTP_GET_VARS['grade'];

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Clear Selected Rolls To Ship"){
		$cust = $HTTP_POST_VARS['cust'];
		$order_num = $HTTP_POST_VARS['order'];
		$booking = $HTTP_POST_VARS['book'];
		$width = $HTTP_POST_VARS['width'];
		$dia = $HTTP_POST_VARS['dia'];
		$avgwt = $HTTP_POST_VARS['avgwt'];
		$count = $HTTP_POST_VARS['count'];

		$clear = $HTTP_POST_VARS['clear'];
		$LR = $HTTP_POST_VARS['LR'];

		for($i = 0; $i < $count; $i++){
			if($clear[$i] != ""){
				$sql = "UPDATE CARGO_TRACKING SET CARGO_STATUS = NULL
						WHERE PALLET_ID = '".$clear[$i]."'
							AND RECEIVER_ID = '".$cust."'
							AND ARRIVAL_NUM = '".$LR[$i]."'
							AND QTY_IN_HOUSE > 0
							AND DATE_RECEIVED IS NOT NULL
							AND CARGO_STATUS IS NOT NULL";
				ora_parse($cursor, $sql);
				ora_exec($cursor, $sql);

				$sql = "UPDATE BOOKING_DAMAGES
						SET DATE_CLEARED = SYSDATE
						WHERE PALLET_ID = '".$clear[$i]."' 
							AND ARRIVAL_NUM = '".$LR[$i]."'
							AND RECEIVER_ID = '".$cust."'
							AND DATE_CLEARED IS NULL
							AND DAMAGE_TYPE IN
							(SELECT DAMAGE_TYPE FROM PAPER_DAMAGE_CODES WHERE REJECT_LEVEL = 'REJECT')";
				ora_parse($cursor, $sql);
				ora_exec($cursor, $sql);
			}
		}
	}

	 echo $headerprint; 
?>
<script type="text/javascript">
window.resizeTo(650, 500);
</script>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="remove_reject" method="post" action="BookingRIGHTNOWdetails.php">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="order" value="<? echo $order_num; ?>">
<input type="hidden" name="book" value="<? echo $booking; ?>">
<input type="hidden" name="width" value="<? echo $width; ?>">
<input type="hidden" name="dia" value="<? echo $dia; ?>">
<input type="hidden" name="avgwt" value="<? echo $avgwt; ?>">
<input type="hidden" name="count" value="<? echo $count; ?>">
	<tr>
		<td><font size="2" face="Verdana"><b>Customer:  <? echo $cust; ?></b></font></td>
		<td><font size="2" face="Verdana"><b>Inbound PO:  <? echo $order_num; ?></b></font></td>
		<td><font size="2" face="Verdana"><b>Booking:  <? echo $booking; ?></b></font></td>
		<td><font size="2" face="Verdana"><b>Width:  <? echo $width; ?>cm/<? echo round($width / 2.54, 1); ?>"</b></font></td>
		<td><font size="2" face="Verdana"><b>Diameter:  <? echo $dia; ?>cm/<? echo round($dia / 2.54, 1); ?>"</b></font></td>
		<td><font size="2" face="Verdana"><b>AVG lbs:  <? echo $avgwt; ?></b></font></td>
		<td><font size="2" face="Verdana"><b>Total Rolls:  <? echo $count; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><input type="submit" name="submit" value="Clear Selected Rolls To Ship"></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Roll #</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td colspan="2"><font size="2" face="Verdana"><b>Arrival # - Show On Order</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td>&nbsp;</td>
	</tr>

<?
	$counter = 0;
	$sql = "SELECT CT.PALLET_ID, CARGO_STATUS, CT.ARRIVAL_NUM, BAD.BOL, TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
			WHERE REMARK = 'BOOKINGSYSTEM'
				AND QTY_IN_HOUSE > 0
				AND DATE_RECEIVED IS NOT NULL
				AND CT.RECEIVER_ID = '".$cust."'
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND BAD.BOOKING_NUM = '".$booking."'
				AND BAD.ORDER_NUM = '".$order_num."'	
                AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
                AND BPGC.SSCC_GRADE_CODE = '".$grade."'    
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'
			ORDER BY DATE_RECEIVED DESC, CT.PALLET_ID";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$extra_info = "";

		if($row['CARGO_STATUS'] != ""){
			$extra_info .= " -- On HOLD";
		}

		$sql = "SELECT COUNT(*) THE_COUNT 
				FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
				WHERE BD.DAMAGE_TYPE = PDC.DAMAGE_CODE
					AND PALLET_ID = '".$row['PALLET_ID']."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$row['ARRIVAL_NUM']."'
					AND DATE_CLEARED IS NULL
					AND PDC.REJECT_LEVEL = 'REJECT'";
		$ora_success = ora_parse($short_term_data_cursor, $sql);
		$ora_success = ora_exec($short_term_data_cursor, $sql);
		ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_data_row['THE_COUNT'] > 0){
			$extra_info .= " -- REJECT";
		}
?>
	<tr>
		<input type="hidden" name="LR[<? echo $counter; ?>]" value="<? echo $row['ARRIVAL_NUM']; ?>">
		<td colspan="2"><font size="2" face="Verdana"><? echo $row['PALLET_ID'].$extra_info; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BOL'].$extra_info; ?></font></td>
		<td colspan="2"><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM'].$extra_info; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_REC'].$extra_info; ?></font></td>
<?
		if($extra_info != ""){
?>
		<td><input type="checkbox" name="clear[<? echo $counter; ?>]" value="<? echo $row['PALLET_ID']; ?>"><font size="2" face="Verdana">&nbsp;Clear To Ship</font></td>
<?
		} else {
?>
		<td>&nbsp;</td>
<?
		}
?>
	</tr>
<?
	$counter++;
	}
?>
</form>
</table>