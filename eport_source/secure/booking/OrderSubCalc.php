<?
	include("useful_info.php");

	$booking = $HTTP_GET_VARS['booking'];
	$PO = $HTTP_GET_VARS['PO'];
	$width = $HTTP_GET_VARS['width'];
	$dia = $HTTP_GET_VARS['dia'];
	$SSCC = $HTTP_GET_VARS['SSCC'];
	$order_num = $HTTP_GET_VARS['order'];

	$short_term_data_cursor = ora_open($conn);

	$sql = "SELECT SUM(QTY_IN_HOUSE) IN_HOUSE FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
						UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
					AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
						(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
						WHERE BD.DAMAGE_TYPE = PDC.DAMAGE_CODE
						AND PDC.REJECT_LEVEL = 'REJECT'
						AND BD.DATE_CLEARED IS NULL)
					AND BAD.BOOKING_NUM = '".$booking."'
					AND BAD.ORDER_NUM = '".$PO."'	
					AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$in_house = 0 + $short_term_row['IN_HOUSE'];

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
			FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
			WHERE SERVICE_CODE = '6'
				AND CA.PALLET_ID = BAD.PALLET_ID
				AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
				AND BAD.BOOKING_NUM = '".$booking."'
				AND BAD.ORDER_NUM = '".$PO."'	
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'
			AND ACTIVITY_DESCRIPTION IS NULL
			AND CA.ORDER_NUM = '".$order_num."'";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$already_scanned_this_order = 0 + $short_term_row['THE_SUM'];

	$sql = "SELECT SUM(QTY_TO_SHIP) THE_SUM FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD
			WHERE BO.ORDER_NUM = BOD.ORDER_NUM
			AND BO.STATUS NOT IN ('6', '7', '8')
			AND BO.ORDER_NUM != '".$order_num."'
			AND BOD.P_O = '".$PO."'
			AND BOD.SSCC_GRADE_CODE = '".$SSCC."'
			AND BOD.BOOKING_NUM = '".$booking."'
			AND BOD.DIA = '".$dia."'
			AND BOD.WIDTH = '".$width."'";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$reserved_to_other_orders = 0 + $short_term_row['THE_SUM'];

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
			FROM CARGO_ACTIVITY
			WHERE SERVICE_CODE = '6'
			AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
			AND ORDER_NUM IN 
				(SELECT BO.ORDER_NUM FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD
					WHERE BO.ORDER_NUM = BOD.ORDER_NUM
					AND BO.STATUS NOT IN ('6', '7', '8')
					AND BO.ORDER_NUM != '".$order_num."'
					AND BOD.P_O = '".$PO."'
					AND BOD.SSCC_GRADE_CODE = '".$SSCC."'
					AND BOD.BOOKING_NUM = '".$booking."'
					AND BOD.DIA = '".$dia."'
					AND BOD.WIDTH = '".$width."'
				)";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$already_scanned_on_other_orders = 0 + $short_term_row['THE_SUM'];

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>BK#:  <? echo $booking; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>PO#:  <? echo $PO; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Width:  <? echo $width; ?>cm</b></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Dia:  <? echo $dia; ?>cm</b></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>SSCC Code:  <? echo $SSCC; ?></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Current Idle In House:</font></td>
		<td><font size="2" face="Verdana"><? echo $in_house; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Already Scanned To This Order:</font></td>
		<td><font size="2" face="Verdana">+ <? echo $already_scanned_this_order; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Other Orders Require:</font></td>
		<td><font size="2" face="Verdana">- <? echo $reserved_to_other_orders; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Already Scanned to Other Orders:</font></td>
		<td><font size="2" face="Verdana">+ <? echo $already_scanned_on_other_orders; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Total Available for Order:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $in_house + $already_scanned_this_order - ($reserved_to_other_orders - $already_scanned_on_other_orders); ?></b></font></td>
	</tr>
</table>