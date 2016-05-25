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
	$PO = $_GET['PO'];
	$booking = $_GET['booking'];
	$cust = $_GET['cust'];
?>

<!DOCTYPE html>
<html>
	<head>
		<script src="/functions/calendar.js"></script>
		<title>Outbound Summary</title>
	</head>
	<body>
		<?php if ($hide != "hide" || ($date_from == "" && $date_to == "" && $booking == "" && $PO == "")) { ?>
		<form name="the_form" action="Booking_OB_summary.php" method="get">
			<table border="0" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" colspan="2"><font size="4" face="Verdana"><b>Outbound Summary</b></font></td>
				</tr>
				<tr>
					<td colspan="2" align="left"><font size="3" face="Verdana">Please enter one or more of the following:</font></td>
				</tr>
				<tr>
					<td width="15%" align="left">Ship Date (from):</td>
					<td><input name="date_from" type="text" size="15" maxlength="15" value="<?php echo $date_from; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
				</tr>
				<tr>
					<td width="15%" align="left">Ship Date (to):</td>
					<td><input name="date_to" type="text" size="15" maxlength="15" value="<?php echo $date_to; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
				</tr>
				<tr>
					<td width="15%" align="left">PO#:</td>
					<td><input name="PO" type="text" size="15" maxlength="15" value="<?php echo $PO; ?>"></td>
				</tr>
				<tr>
					<td width="15%" align="left">Booking#:</td>
					<td><input name="booking" type="text" size="15" maxlength="15" value="<?php echo $booking; ?>"></td>
				</tr>
			<?php if ($eport_customer_id == 0) { ?>
				<tr>
					<td width="15%" align="left">Customer:</td>
					<td><select name="cust"><option value="all">All</option>
									<option value="314"<?php if($cust == "314"){?> selected <?php } ?>>314</option>
									<option value="338"<?php if($cust == "338"){?> selected <?php } ?>>338</option>
									<option value="517"<?php if($cust == "517"){?> selected <?php } ?>>517</option>
						</select></td>
				</tr>
			<?php } else { ?>
				<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>">
			<?php
				}
			?>
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
		<?php
			}
			if ($date_from != "" || $date_to != "" || $booking != "" || $PO != "") {
		?>
		<table border="0" width="60%" cellpadding="4" cellspacing="0">
			<tr><td align="center"><font size="4" face="Verdana"><b>Outbound Summary</b></font></td></tr>
		</table>
		<?php

				if ($cust != "all") {
					$cust_sql = " AND ct.receiver_id = '".$cust."' ";
				} else {
					$cust_sql = " ";
				}

				$sql = "SELECT
							TO_CHAR(date_of_activity, 'MM/DD/YYYY') AS act_date,
							CA.order_num AS ca_ord,
							bo.container_id,
							bpgc.sscc_grade_code,
							COUNT(DISTINCT ct.pallet_id) AS the_rolls,
							bad.order_num AS bad_ord,
							bad.booking_num,
							SUM(ROUND(weight * uc3.conversion_factor)) AS weight_lb,
							SUM(ROUND(weight * uc4.conversion_factor)) AS weight_kg,
							bad.warehouse_code
						FROM
							cargo_tracking ct,
							booking_additional_data bad,
							cargo_activity ca,
							booking_orders bo,
							booking_paper_grade_code bpgc,
							unit_conversion_from_bni uc3,
							unit_conversion_from_bni uc4
						WHERE
							ct.arrival_num = bad.arrival_num
							AND ct.pallet_id = bad.pallet_id
							AND ct.receiver_id = bad.receiver_id
							AND ca.arrival_num = bad.arrival_num
							AND ca.pallet_id = bad.pallet_id
							AND ca.customer_id = bad.receiver_id
							AND ct.weight_unit = uc3.primary_uom
							AND uc3.secondary_uom = 'LB'
							AND ct.weight_unit = uc4.primary_uom
							AND uc4.secondary_uom = 'KG'
							AND ca.order_num = bo.order_num
							AND ca.service_code = '6'
							AND ct.remark = 'BOOKINGSYSTEM'
							AND bad.product_code = bpgc.product_code
							AND (ca.activity_description IS NULL OR ca.activity_description != 'VOID')".$cust_sql;
						
				if ($date_from != "") {
					$sql .= "AND date_of_activity >= TO_DATE('".$date_from."', 'MM/DD/YYYY') ";
				}
				if ($date_to != "") {
					$sql .= "AND date_of_activity <= TO_DATE('".$date_to." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
				}
				if ($booking != "") {
					$sql .= "AND bad.booking_num = '".$booking."'";
				}
				if ($PO != "") {
					$sql .= "AND bad.order_num = '".$PO."'";
				}
				$sql .= "GROUP BY
							TO_CHAR(date_of_activity, 'MM/DD/YYYY'),
							bad.order_num,
							bad.booking_num,
							ca.order_num,
							bo.container_id,
							bpgc.sscc_grade_code,
							bad.warehouse_code
						ORDER BY
							act_date,
							bad.order_num,
							ca.order_num,
							bad.booking_num,
							bo.container_id,
							bpgc.sscc_grade_code";
		//		echo $sql;
				ora_parse($main_cursor, $sql);
				ora_exec($main_cursor);
				if(!ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					echo "<hr><font color=\"#ff0000\">No Outbound Activity matches the entered criteria.</font>";
				} else {
					$current_date = $row['ACT_DATE'];
			//		$customer = $row['THE_NAME'];
					$date_total_rolls = 0;
					$date_total_weight_lb = 0;
					$date_total_weight_kg = 0;
					$grand_total_rolls = 0;
					$grand_total_weight_lb = 0;
					$grand_total_weight_kg = 0;
		?>
		<table border="1" width="60%" cellpadding="4" cellspacing="0">
			<tr>
				<td><b>Date</b></td>
				<td><b>OB Order#</b></td>
				<td><b>PO#</b></td>
				<td><b>Warehouse Code</b></td>
				<td><b>Booking #</b></td>
				<td><b>Grade Code</b></td>
				<td><b>Container ID</b></td>
				<td><b>Rolls</b></td>
				<td><b>LB</b></td>
				<td><b>KG</b></td>
			</tr>
			<tr>
				<td colspan="10"><b><?php echo $current_date; ?></b></td>
			</tr>
		<?php
					do {
						if($current_date != $row['ACT_DATE']){
		?>	
			<tr>
				<td colspan="7"><b>Total:</b></td>
				<td><?php echo $date_total_rolls; ?></td>
				<td><?php echo $date_total_weight_lb; ?> </td>
				<td><?php echo $date_total_weight_kg; ?> </td>
			</tr>
		<?php
						$current_date = $row['ACT_DATE'];
						$date_total_rolls = 0;
						$date_total_weight_lb = 0;
						$date_total_weight_kg = 0;
		?>
			<tr>
				<td colspan="10"><b><?php echo $current_date; ?></b></td>
			</tr>
		<?php } ?>	
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $row['CA_ORD']; ?></td>
				<td><?php echo $row['BAD_ORD']; ?></td>
				<td><?php echo $row['WAREHOUSE_CODE']; ?></td>
				<td><?php echo $row['BOOKING_NUM']; ?></td>
				<td><?php echo $row['SSCC_GRADE_CODE']; ?></td>
				<td><?php echo $row['CONTAINER_ID']; ?></td>
				<td><?php echo $row['THE_ROLLS']; ?></td>
				<td><?php echo $row['WEIGHT_LB']; ?></td>
				<td><?php echo $row['WEIGHT_KG']; ?></td>
			</tr>

		<?php
						$date_total_rolls += $row['THE_ROLLS'];
						$grand_total_rolls += $row['THE_ROLLS'];
						$date_total_weight_lb += $row['WEIGHT_LB'];
						$date_total_weight_kg += $row['WEIGHT_KG'];
						$grand_total_weight_lb += $row['WEIGHT_LB'];
						$grand_total_weight_kg += $row['WEIGHT_KG'];

					} while(ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		?>
			<tr>
				<td colspan="7">Total:</td>
				<td><?php echo $date_total_rolls; ?></td>
				<td><?php echo $date_total_weight_lb; ?> </td>
				<td><?php echo $date_total_weight_kg; ?> </td>
			</tr>
			<tr>
				<td colspan="7"><b>Grand Total:</b></td>
				<td><?php echo $grand_total_rolls; ?></td>
				<td><?php echo $grand_total_weight_lb; ?> </td>
				<td><?php echo $grand_total_weight_kg; ?> </td>
			</tr>
		<?php
				}
			}
		?>
	</body>
</html>