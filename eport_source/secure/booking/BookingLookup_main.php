<?php
/*
*			Adam Walter, May 2010
*			This page allows OPS review Booking inventory
*			based on Sales #s et. al.
******************************************************************/
/*
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Lookup";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

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

	$submit = $_GET['submit'];

	$order_num = $_GET['sales'];
	$booking_num = $_GET['booking'];
	$received = $_GET['rec'];
	$shipped = $_GET['ship'];
	$cust = $_GET['cust'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Lookup Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form action="" method="get">
	<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<?php
		if($eport_customer_id != 0){
	?>
			<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>">
	<?php
		} else {
	?>
		<tr>
			<td colspan="4"><font size="2" face="Verdana">For Customer:&nbsp;</font>
				<select name="cust"><option value="all">All</option>
							<option value="314"<?php if ($cust == "314") { ?> selected <?php } ?>>314</option>
							<option value="338"<?php if ($cust == "338") { ?> selected <?php } ?>>338</option>
					</select></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td><font size="2" face="Verdana">Sales Order #:</font>
				<select name="sales"><option value="">All</option>
	<?php
		if($eport_customer_id != 0){
			$filter_sql = " AND RECEIVER_ID = '".$eport_customer_id."' ";
		} else {
			$filter_sql = " ";
		}

		$sql = "SELECT DISTINCT ORDER_NUM 
				FROM BOOKING_ADDITIONAL_DATA 
				WHERE ORDER_NUM IS NOT NULL
					".$filter_sql."
				ORDER BY ORDER_NUM";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
	?>
					<option value="<?php echo $row['ORDER_NUM']; ?>"<?php if ($row['ORDER_NUM'] == $order_num) { ?> selected <?php } ?>>
						<?php echo $row['ORDER_NUM']; ?>
					</option>
	<?php
		}
	?>
				</select>
			</td>
			<td><font size="2" face="Verdana">Booking #:</font>
				<select name="booking"><option value="">All</option>
	<?php
		$sql = "SELECT DISTINCT BOOKING_NUM 
				FROM BOOKING_ADDITIONAL_DATA 
				WHERE BOOKING_NUM IS NOT NULL 
					".$filter_sql."
				ORDER BY BOOKING_NUM";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	?>
					<option value="<?php echo $row['BOOKING_NUM']; ?>"<?php if ($row['BOOKING_NUM'] == $booking_num) { ?> selected <?php } ?>>
						<?php echo $row['BOOKING_NUM']; ?>
					</option>
	<?php
		}
	?>
				</select>
			</td>
			<td><font size="2" face="Verdana">Received?</font>
				<select name="rec">
					<option value="">All</option>
					<option value="rec" <?php if ($received == "rec") { ?> selected<?php } ?>>Received</option>
					<option value="unrec" <?php if ($received == "unrec") { ?> selected<?php } ?>>Not Received</option>
				</select>
			</td>
			<td><font size="2" face="Verdana">Shipped?</font>
				<select name="ship">
					<option value="">All</option>
					<option value="ship" <?php if ($shipped == "ship") { ?> selected<?php } ?>>Shipped</option>
					<option value="unship" <?php if ($shipped == "unship"){?> selected<?php } ?>>Not Shipped</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<button type="submit" name="submit" value="retrieve">Generate Report</button>
			</td>
		</tr>
	</table>
</form>
<?php
	if ($submit != "") {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
<?php
		if($booking_num != "" || $order_num != ""){
			$html_string = "?test=a";
			if($booking_num != ""){
				$html_string .= "&booking_num=".$booking_num;
			}
			if($order_num != ""){
				$html_string .= "&order_num=".$order_num;
			}
			if($received != ""){
				$html_string .= "&received=".$received;
			}
			if($shipped != ""){
				$html_string .= "&shipped=".$shipped;
			}
			if($cust != ""){
				$html_string .= "&cust=".$cust;
			}
?>
		<td align="center"><br><a target="BookingPlannerPopup.php<?php echo $html_string; ?>" href="BookingPlannerPopup.php<?php echo $html_string; ?>">Width Breakdown</a><br></td>
<?php
		} else {
?>
		<td align="center"><br>To popup a Width Breakdown, you must specify a Booking# or Order#<br></td>
<?php
		}
?>
	</tr>
</table>


<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>#rolls</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight (lbs)</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Diameter</b></font></td>
		<td><font size="2" face="Verdana"><b>First Rec Date</b></font></td>
		<td><font size="2" face="Verdana"><b>First Loadout Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Most Recent Ship Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival #</b></font></td>
		<td><font size="2" face="Verdana"><b>BOL</b></font></td>
	</tr>
<?php

		$sql = "SELECT
					BAD.ORDER_NUM,
					NVL(BAD.BOOKING_NUM, 'NONE') THE_BOOK,
					COUNT(DISTINCT CT.PALLET_ID) THE_ROLLS,
					BAD.BOL,
					ROUND(SUM(CT.WEIGHT * UC1.CONVERSION_FACTOR)) THE_LBS,
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA,
					TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_REC,
					CT.ARRIVAL_NUM,
					NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY'), 'NONE') THE_FIRST,
					NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY'), 'NONE') THE_LAST,
					bad.warehouse_code
				FROM
					CARGO_TRACKING CT,
					BOOKING_ADDITIONAL_DATA BAD,
					UNIT_CONVERSION_FROM_BNI UC1,
					UNIT_CONVERSION_FROM_BNI UC2,
					CARGO_ACTIVITY CA
				WHERE
					CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM(+)
					AND CT.PALLET_ID = CA.PALLET_ID(+)
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID(+)
					AND NVL(CA.ACTIVITY_DESCRIPTION(+), 'YAY') != 'VOID'
					AND CA.SERVICE_CODE(+) = '6'
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
		if($cust != "all"){
			$sql .= " AND CA.CUSTOMER_ID = '".$cust."' ";
		} 

		$sql .= " GROUP BY
					CT.ARRIVAL_NUM,
					BAD.BOL,
					BAD.ORDER_NUM,
					NVL(BAD.BOOKING_NUM, 'NONE'),
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR),
					bad.warehouse_code
				ORDER BY
					BAD.ORDER_NUM,
					NVL(BAD.BOOKING_NUM, 'NONE'),
					CT.ARRIVAL_NUM";
					
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana">No Rolls matching search criteria</font></td>
	</tr>
<?php
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><?php echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_ROLLS']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['WAREHOUSE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_LBS']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_DIA']; ?>"</font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_REC']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_FIRST']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_LAST']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['BOL']; ?></font></td>
	</tr>
<?php
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

	}
	include("pow_footer.php");
?>