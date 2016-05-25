<?php
 /*
*			Adam Walter, May 2010
*			This page allows OPS review inbound activity
******************************************************************/
/*
// All POW files need this session file included
include("pow_session.php");

/* 
// Define some vars for the skeleton page
$title = "Booking Activity viewer (Inbound)";
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
$main_cursor = ora_open($conn);
$short_term_data_cursor = ora_open($conn);
$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];


$hide = $_GET['hide'];
$date_from = $_GET['date_from'];
$date_to = $_GET['date_to'];
$cust = $_GET['cust'];
?>

<!DOCTYPE html>
<html>
	<head>
		<script src="/functions/calendar.js"></script>
		<title>Inbound Summary</title>
	</head>
	<body>
		<?php if ($hide != "hide" || ($date_from == "" && $date_to == "")) { ?>
		<form name="the_form" action="" method="get">
			<table border="0" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" colspan="2"><font size="4" face="Verdana"><b>Inbound Summary</b></font></td>
				</tr>
				<tr>
					<td colspan="2" align="left"><font size="3" face="Verdana">Please enter one or more of the following:</font></td>
				</tr>
				<tr>
					<td width="15%" align="left">Receive Date (from):</td>
					<td><input name="date_from" type="text" size="15" maxlength="15" value="<?php echo $date_from; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
				</tr>
				<tr>
					<td width="15%" align="left">Receive Date (to):</td>
					<td><input name="date_to" type="text" size="15" maxlength="15" value="<?php echo $date_to; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
				</tr>
			<?php if ($eport_customer_id == 0) { ?>
				<tr>
					<td width="15%" align="left">Customer:</td>
					<td><select name="cust"><option value="all">All</option>
									<option value="314"<?php if($cust == "314"){?> selected <?}?>>314</option>
									<option value="338"<?php if($cust == "338"){?> selected <?}?>>338</option>
									<option value="517"<?php if($cust == "517"){?> selected <?}?>>517</option>
						</select></td>
				</tr>
			<?php } else { ?>
				<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>">
			<?php } ?>
				<tr>
					<td colspan="2" align="left">
						<label><input type="checkbox" value="hide" name="hide">
							Check here to hide the search boxes (useful for printing)
						</label>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left"><button type="submit">Generate Report</button></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;<hr>&nbsp;</td>
				</tr>
			</table>
		</form>
		<?php } ?>
		
		<?php if ($date_from != "" || $date_to != "") { ?>
		<table border="0" width="60%" cellpadding="4" cellspacing="0">
			<tr><td align="center"><font size="4" face="Verdana"><b>Inbound Summary</b></font></td></tr>
		</table>
		<?php
				if ($cust != "all") {
					$cust_sql = " AND ct.receiver_id = '$cust' ";
				} else {
					$cust_sql = " ";
				}

				$sql = "SELECT
							TO_CHAR(date_of_activity, 'MM/DD/YYYY') act_date,
							ct.arrival_num,
							bpgc.sscc_grade_code,
							COUNT(distinct ct.pallet_id) the_rolls,
							SUM(DECODE(date_received, null, 0, 1)) the_rec,
							bad.order_num,
							bad.booking_num,
							SUM(ROUND(weight * uc3.conversion_factor)) weight_lb,
							SUM(ROUND(weight * uc4.conversion_factor)) weight_kg,
							TO_CHAR(MIN(date_received), 'MM/DD/YYYY HH24:MI:SS') first_rec,
							bad.warehouse_code
						FROM cargo_tracking ct
						INNER JOIN booking_additional_data bad
							ON ct.arrival_num = bad.arrival_num
							AND ct.pallet_id = bad.pallet_id
							AND ct.receiver_id = bad.receiver_id
						INNER JOIN cargo_activity ca
							ON ca.arrival_num = bad.arrival_num
							AND ca.pallet_id = bad.pallet_id
							AND ca.customer_id = bad.receiver_id
						INNER JOIN booking_paper_grade_code bpgc
							ON bad.product_code = bpgc.product_code
						INNER JOIN unit_conversion_from_bni uc3
							ON ct.weight_unit = uc3.primary_uom
							AND uc3.secondary_uom = 'LB'
						INNER JOIN unit_conversion_from_bni uc4
							ON  ct.weight_unit = uc4.primary_uom
							AND uc4.secondary_uom = 'KG'
						WHERE
							ca.activity_num = '1' $cust_sql ";
						
				if ($date_from != "") {
					$sql .= "AND date_received >= TO_DATE('".$date_from."', 'MM/DD/YYYY') ";
				}
				if ($date_to != "") {
					$sql .= "AND date_received <= TO_DATE('".$date_to." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
				}
				$sql .= "GROUP BY
							TO_CHAR(date_of_activity, 'MM/DD/YYYY'),
							ct.arrival_num,
							bad.order_num,
							bad.booking_num,
							bpgc.sscc_grade_code,
							warehouse_code
						ORDER BY
							TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY'),
							ct.arrival_num,
							bad.order_num,
							bad.booking_num,
							bpgc.sscc_grade_code";
				
				ora_parse($main_cursor, $sql);
				ora_exec($main_cursor);
				if (!ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					echo "<hr><font color=\"#ff0000\">No Inbound Activity matches the entered criteria.</font>";
				} else {
					$current_date = $row['ACT_DATE'];
			//		$customer = $row['THE_NAME'];
					$date_total_rolls_exp = 0;
					$date_total_rolls_rcvd = 0;
					$date_total_weight_lb = 0;
					$date_total_weight_kg = 0;
					$grand_total_rolls_exp = 0;
					$grand_total_rolls_rcvd = 0;
					$grand_total_weight_lb = 0;
					$grand_total_weight_kg = 0;
		?>
		<table border="1" cellpadding="4" cellspacing="0">
			<tr>
				<td><b>Date</b></td>
				<td><b>Arrival</b></td>
				<td><b>Order#</b></td>
				<td><b>Warehouse Code</b></td>
				<td><b>Booking #</b></td>
				<td><b>Grade Code</b></td>
				<td><b>First Received</b></td>
				<td><b>Expt'd Rolls</b></td>
				<td><b>Rcv'd Rolls</b></td>
				<td><b>LB</b></td>
				<td><b>KG</b></td>
			</tr>
			<tr>
				<td colspan="11"><b><?php echo $current_date; ?></b></td>
			</tr>
		<?php
					do {
						if($current_date != $row['ACT_DATE']){
		?>	
			<tr>
				<td colspan="7"><b>Total:</b></td>
				<td><?php echo $date_total_rolls_exp; ?></td>
				<td><?php echo $date_total_rolls_rcvd; ?></td>
				<td><?php echo $date_total_weight_lb; ?> </td>
				<td><?php echo $date_total_weight_kg; ?> </td>
			</tr>
		<?php
						$current_date = $row['ACT_DATE'];
						$date_total_rolls_exp = 0;
						$date_total_rolls_rcvd = 0;
						$date_total_weight_lb = 0;
						$date_total_weight_kg = 0;
		?>
			<tr>
				<td colspan="11"><b><?php echo $current_date; ?></b></td>
			</tr>
		<?php
				}
				$expect = GetExpected($row['ARRIVAL_NUM'], $row['ORDER_NUM'], $row['BOOKING_NUM'], $row['SSCC_GRADE_CODE'], $conn);
		?>	
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $row['ARRIVAL_NUM']; ?></td>
				<td><?php echo $row['ORDER_NUM']; ?></td>
				<td><?php echo $row['WAREHOUSE_CODE']; ?></td>
				<td><?php echo $row['BOOKING_NUM']; ?></td>
				<td><?php echo $row['SSCC_GRADE_CODE']; ?></td>
				<td><?php echo $row['FIRST_REC']; ?></td>
				<td><?php echo $expect; ?></td>
				<td><?php echo $row['THE_REC']; ?></td>
				<td><?php echo $row['WEIGHT_LB']; ?></td>
				<td><?php echo $row['WEIGHT_KG']; ?></td>
			</tr>

		<?php
						$date_total_rolls_exp += $expect;
						$grand_total_rolls_exp += $expect;
						$date_total_rolls_rcvd += $row['THE_REC'];
						$grand_total_rolls_rcvd += $row['THE_REC'];
						$date_total_weight_lb += $row['WEIGHT_LB'];
						$date_total_weight_kg += $row['WEIGHT_KG'];
						$grand_total_weight_lb += $row['WEIGHT_LB'];
						$grand_total_weight_kg += $row['WEIGHT_KG'];

					} while(ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		?>
			<tr>
				<td colspan="7">Total:</td>
				<td><?php echo $date_total_rolls_exp; ?></td>
				<td><?php echo $date_total_rolls_rcvd; ?></td>
				<td><?php echo $date_total_weight_lb; ?> </td>
				<td><?php echo $date_total_weight_kg; ?> </td>
			</tr>
			<tr>
				<td colspan="7"><b>Grand Total:</b></td>
				<td><?php echo $grand_total_rolls_exp; ?></td>
				<td><?php echo $grand_total_rolls_rcvd; ?></td>
				<td><?php echo $grand_total_weight_lb; ?> </td>
				<td><?php echo $grand_total_weight_kg; ?> </td>
			</tr>
		<?php
				}
			}
?>
	</body>
</html>




<?php
function GetExpected($vessel, $order, $booking, $sscc, $conn) {
	$short_term_data_cursor = ora_open($conn);

	$sql = "SELECT
				COUNT(DISTINCT ct.pallet_id) the_plts
			FROM cargo_tracking ct,
				booking_additional_data bad,
				booking_paper_grade_code bpgc
			WHERE
				ct.arrival_num = bad.arrival_num
				AND ct.pallet_id = bad.pallet_id
				AND ct.receiver_id = bad.receiver_id
				AND bad.product_code = bpgc.product_code
				AND ct.arrival_num = '$vessel'
				AND bpgc.sscc_grade_code = '$sscc'
				AND bad.order_num  = '$order'
				AND bad.booking_num = '$booking.'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	return $row['THE_PLTS'];
}

?>