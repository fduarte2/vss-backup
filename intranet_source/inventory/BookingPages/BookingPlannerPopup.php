<?
/*
*			Adam Walter, May 2010
*			This page allows OPS review Booking inventory
*			***broken down by width***
*			based on Sales #s et. al.
******************************************************************/


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);

	$order_num = $HTTP_GET_VARS['order_num'];
	$booking_num = $HTTP_GET_VARS['booking_num'];
	$received = $HTTP_GET_VARS['received'];
	$shipped = $HTTP_GET_VARS['shipped'];
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>#rolls</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight (lbs)</b></font></td>
		<td><font size="2" face="Verdana"><b>Close Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Diameter</b></font></td>
		<td><font size="2" face="Verdana"><b>First Rec Date</b></font></td>
		<td><font size="2" face="Verdana"><b>First Loadout Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival #</b></font></td>
	</tr>
<?
	$sql = "SELECT BAD.ORDER_NUM, NVL(BAD.BOOKING_NUM, 'NONE') THE_BOOK, COUNT(DISTINCT CT.PALLET_ID) THE_ROLLS,
			ROUND(SUM(CT.WEIGHT * UC1.CONVERSION_FACTOR)) THE_LBS, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA,
			TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_REC, CT.ARRIVAL_NUM
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
			WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
			AND CT.PALLET_ID = BAD.PALLET_ID
			AND CT.RECEIVER_ID = BAD.RECEIVER_ID
			AND CT.WEIGHT_UNIT = UC1.PRIMARY_UOM
			AND UC1.SECONDARY_UOM = 'LB'
			AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM
			AND UC2.SECONDARY_UOM = 'IN'";
	if($order_num != ""){
		$sql .= " AND BAD.ORDER_NUM = '".$order_num."'";
	}
	if($booking_num != ""){
		$sql .= " AND BAD.BOOKING_NUM = '".$booking_num."'";
	}
	if($received == "rec"){
		$sql .= " AND CT.DATE_RECEIVED IS NOT NULL";
	} elseif($received == "unrec"){
		$sql .= " AND CT.DATE_RECEIVED IS NULL";
	}
	if($shipped == "ship"){
		$sql .= " AND CT.QTY_IN_HOUSE = 0";
	} elseif($shipped == "unship"){
		$sql .= " AND CT.QTY_IN_HOUSE > 0";
	}

	$sql .= " GROUP BY CT.ARRIVAL_NUM, BAD.ORDER_NUM, NVL(BAD.BOOKING_NUM, 'NONE'), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR)";
	$sql .= " ORDER BY BAD.ORDER_NUM, NVL(BAD.BOOKING_NUM, 'NONE'), CT.ARRIVAL_NUM";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana">No Rolls matching search criteria</font></td>
	</tr>
<?
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_ROLLS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_LBS']; ?></font></td>
		<td><font size="2" face="Verdana">Under Construction</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DIA']; ?>"</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_REC']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana">Under Construction</font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
	</tr>
<?
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>&nbsp;<br>&nbsp;<br>&nbsp;</td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?
	$sql = "SELECT DISTINCT ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTHS
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC1
			WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
			AND CT.PALLET_ID = BAD.PALLET_ID
			AND CT.RECEIVER_ID = BAD.RECEIVER_ID
			AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM
			AND UC1.SECONDARY_UOM = 'CM'";
	if($order_num != ""){
		$sql .= " AND BAD.ORDER_NUM = '".$order_num."'";
	}
	if($booking_num != ""){
		$sql .= " AND BAD.BOOKING_NUM = '".$booking_num."'";
	}
	if($received == "rec"){
		$sql .= " AND CT.DATE_RECEIVED IS NOT NULL";
	} elseif($received == "unrec"){
		$sql .= " AND CT.DATE_RECEIVED IS NULL";
	}
	if($shipped == "ship"){
		$sql .= " AND CT.QTY_IN_HOUSE = 0";
	} elseif($shipped == "unship"){
		$sql .= " AND CT.QTY_IN_HOUSE > 0";
	}
	$sql .= " ORDER BY ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR)";
//	echo $sql."<br>";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="2" face="Verdana">No Rolls matching search criteria</font></td>
	</tr>
<?
	} else {
		$width_column = 0;
		$column_totals = array();
		$print_record = array();
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival #</b></font></td>
<?
		do {
			$width_value[$width_column] = $row['THE_WIDTHS'];
			$width_column++;
?>	
		<td><font size="2" face="Verdana"><b><? echo $row['THE_WIDTHS']; ?>CM / <? echo round($row['THE_WIDTHS'] / 2.54); ?>"</b></font></td>
<?
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
	</tr>
<?
	$sql = "SELECT NVL(BOOKING_NUM, 'NONE') THE_BOOK, ORDER_NUM, 
			CT.ARRIVAL_NUM, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTHS, COUNT(DISTINCT CT.PALLET_ID) THE_ROLLS
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC1
			WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
			AND CT.PALLET_ID = BAD.PALLET_ID
			AND CT.RECEIVER_ID = BAD.RECEIVER_ID
			AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM
			AND UC1.SECONDARY_UOM = 'CM'";
	if($order_num != ""){
		$sql .= " AND BAD.ORDER_NUM = '".$order_num."'";
	}
	if($booking_num != ""){
		$sql .= " AND BAD.BOOKING_NUM = '".$booking_num."'";
	}
	if($received == "rec"){
		$sql .= " AND CT.DATE_RECEIVED IS NOT NULL";
	} elseif($received == "unrec"){
		$sql .= " AND CT.DATE_RECEIVED IS NULL";
	}
	if($shipped == "ship"){
		$sql .= " AND CT.QTY_IN_HOUSE = 0";
	} elseif($shipped == "unship"){
		$sql .= " AND CT.QTY_IN_HOUSE > 0";
	}
	$sql .= " GROUP BY NVL(BOOKING_NUM, 'NONE'), ORDER_NUM, CT.ARRIVAL_NUM, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR)";
	$sql .= " ORDER BY NVL(BOOKING_NUM, 'NONE'), ORDER_NUM, CT.ARRIVAL_NUM, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR)";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($row['THE_BOOK'] != $print_record['booking']
			|| $row['ORDER_NUM'] != $print_record['order']
			|| $row['ARRIVAL_NUM'] != $print_record['arv']
			|| $print_record['arv'] == ""){
			if($print_record['arv'] != ""){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $print_record['order']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $print_record['booking']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $print_record['arv']; ?></font></td>
<?
			for($i = 0; $i < sizeof($width_value); $i++){
?>
		<td><font size="2" face="Verdana"><? echo (0 + $print_record[$width_value[$i]]); ?></font></td>
<?
			}
			$print_record = array();
?>
	</tr>
<?
			}
			$print_record['booking'] = $row['THE_BOOK'];
			$print_record['order'] = $row['ORDER_NUM'];
			$print_record['arv'] = $row['ARRIVAL_NUM'];
		}

		$print_record[$row['THE_WIDTHS']] = $row['THE_ROLLS'];
		$column_totals[$row['THE_WIDTHS']] += $row['THE_ROLLS'];
	}

	// and print the last row
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $print_record['order']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $print_record['booking']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $print_record['arv']; ?></font></td>
<?
			for($i = 0; $i < sizeof($width_value); $i++){
?>
		<td><font size="2" face="Verdana"><? echo (0 + $print_record[$width_value[$i]]); ?></font></td>
<?
			}
?>
	</tr>
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>Totals:</b></font></td>
<?
			for($i = 0; $i < sizeof($width_value); $i++){
?>
		<td><font size="2" face="Verdana"><b><? echo (0 + $column_totals[$width_value[$i]]); ?></b></font></td>
<?
			}
?>
	</tr>
</table>
