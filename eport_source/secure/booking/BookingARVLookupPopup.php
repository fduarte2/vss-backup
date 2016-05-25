<?php
/*
*			Adam Walter, May 2010
*			This page allows OPS review Booking inventory
*			based on Railcar
*			Printable details
******************************************************************/

/*
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
*/
	include("useful_info.php");
	$cursor = ora_open($conn);

	$timeframe = $HTTP_GET_VARS['timeframe'];
	$LR = $HTTP_GET_VARS['LR'];
	$cust = $HTTP_GET_VARS['cust'];
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?php
		$sql = "SELECT
					CT.ARRIVAL_NUM,
					NVL(BAD.BOOKING_NUM, '---NONE---') THE_BOOK,
					BAD.ORDER_NUM,
					BPGC.SSCC_GRADE_CODE,
					COUNT(*) THE_COUNT,
					BAD.BOL,
					BAD.SHIPFROMMILL,
					NVL(TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY'), '---NONE---') THE_REC
				FROM
					CARGO_TRACKING CT,
					BOOKING_ADDITIONAL_DATA BAD,
					BOOKING_PAPER_GRADE_CODE BPGC
				WHERE
					CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE";
		if ($timeframe == "twomonths") {
			$sql .= " AND (CT.QTY_IN_HOUSE > 0 OR CT.ARRIVAL_NUM IN
								(
									SELECT ARRIVAL_NUM FROM CARGO_ACTIVITY
									WHERE SERVICE_CODE = '6'";
				if($cust == "all"){
					$sql .= " 		AND CUSTOMER_ID IN ('314', '338', '517') ";
				} else {
					$sql .= " 		AND CUSTOMER_ID = '".$cust."' ";
				}

				$sql .= " 			AND ACTIVITY_DESCRIPTION IS NULL
									AND DATE_OF_ACTIVITY > SYSDATE - 60
								)
						  )";
		}
		if ($LR != "all") {
			$sql .= " AND CT.ARRIVAL_NUM = '".$LR."'";
		}
		if ($cust != "all") {
			$sql .= " AND CT.RECEIVER_ID = '".$cust."'";
		}
		$sql .= " GROUP BY
					CT.ARRIVAL_NUM,
					NVL(BAD.BOOKING_NUM, '---NONE---'),
					BAD.ORDER_NUM,
					BAD.BOL,
					BAD.SHIPFROMMILL,
					BPGC.SSCC_GRADE_CODE";
		$sql .= " ORDER BY
					CT.ARRIVAL_NUM,
					NVL(BAD.BOOKING_NUM, '---NONE---'),
					BAD.ORDER_NUM,
					BPGC.SSCC_GRADE_CODE";
					
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000"><b>No Railcars matching selected criteria.</b></font></td>
	</tr>
<?php
		} else {
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Railcar</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking #</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Grade Code</b></font></td>
		<td><font size="2" face="Verdana"><b>#Rolls Expected</b></font></td>
		<td><font size="2" face="Verdana"><b>BOL</b></font></td>
		<td><font size="2" face="Verdana"><b>Mill</b></font></td>
		<td><font size="2" face="Verdana"><b>First Received</b></font></td>
	</tr>
<?php
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><?php echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['SSCC_GRADE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['SHIPFROMMILL']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_REC']; ?></font></td>
	</tr>
<?php
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>&nbsp;<br>&nbsp;<br>&nbsp;</td>
	</tr>
</table>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?php
	$sql = "SELECT
				CT.ARRIVAL_NUM,
				CT.PALLET_ID,
				BAD.BOOKING_NUM,
				NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '---NONE---') THE_REC,
				BPGC.SSCC_GRADE_CODE,
				BAD.ORDER_NUM,
				ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH,
				ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA,
				NVL(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), '---NONE---') THE_SHIP_TIME,
				ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB,
				NVL(CA.ORDER_NUM, '---NONE---') OB_ORDER,
				bad.warehouse_code
			FROM
				CARGO_TRACKING CT,
				SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD,
				CARGO_ACTIVITY CA,
				BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1,
				UNIT_CONVERSION_FROM_BNI UC2,
				UNIT_CONVERSION_FROM_BNI UC3
			WHERE
				CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM(+) = BAD.ARRIVAL_NUM
				AND CA.PALLET_ID(+) = BAD.PALLET_ID
				AND CA.CUSTOMER_ID(+) = BAD.RECEIVER_ID
				AND CA.SERVICE_CODE(+) = '6'
				AND CA.ACTIVITY_DESCRIPTION(+) IS NULL
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND CT.ARRIVAL_NUM = '$LR' ";
	if($cust != "all"){
		$sql .= " AND CT.RECEIVER_ID = '".$cust."'";
	}
	$sql .=	"	AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM
				AND UC1.SECONDARY_UOM = 'CM'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM
				AND UC2.SECONDARY_UOM = 'CM'
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM
				AND UC3.SECONDARY_UOM = 'LB'
			ORDER BY
				PALLET_ID";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000"><b>No details matching selected criteria.</b></font></td>
	</tr>
<?php
	} else {
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Railcar</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Grade Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking #</b></font></td>
		<td><font size="2" face="Verdana"><b>Diamater</b></font></td>
		<td><font size="2" face="Verdana"><b>Width</b></font></td>
		<td><font size="2" face="Verdana"><b>Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
		<td><font size="2" face="Verdana"><b>Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>OB Order/Container</b></font></td>
	</tr>
<?php
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><?php echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['WAREHOUSE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['SSCC_GRADE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['BOOKING_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_DIA']."cm/".round($row['THE_DIA'] / 2.54, 1)."\""; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_WIDTH']."cm/".round($row['THE_WIDTH'] / 2.54, 1)."\""; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['WEIGHT_LB']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_SHIP_TIME']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['OB_ORDER']; ?></font></td>
<?php
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
</table>