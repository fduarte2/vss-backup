<?php
	include 'class.ezpdf.php';
	include("useful_info.php");

	$per_page = 0;

	$order_cursor = ora_open($conn);
	$booking_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$load_date = $HTTP_POST_VARS['load_date'];
	$status = $HTTP_POST_VARS['status'];
	$cust = $HTTP_POST_VARS['cust'];
//	$destination = $HTTP_POST_VARS['destination'];
	if ($load_date != "") {

		$pdf = new Cezpdf('letter','landscape');
		$pdf->ezSetMargins(20,20,65,65);
		$pdf->ezStartPageNumbers(700,10,8,'right');
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
		$pdf->ezSetDy(-20);
		$pdf->ezText("<b>Booking Paper Loading Order Sheet</b>", 16, $center);
		$pdf->ezText("<b>$load_date --- Status $status</b>", 14, $center);
//		$pdf->ezText("<b>Destination:  $dest_name</b>", 14, $center);
		$pdf->ezSetDy(-5);

		$lines = 0;

		if ($cust != "all") {
			$cust_sql = " AND CUSTOMER_ID = '".$cust."' ";
		} else {
			$cust_sql = " ";
		}
		$sql = "SELECT
					ORDER_NUM,
					CONTAINER_ID THE_CONT,
					ORDER_COMMENT,
					CUSTOMER_ID 
				FROM BOOKING_ORDERS 
				WHERE
					STATUS IN ".$status." 
					AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."'
					".$cust_sql;
/*		if ($destination != "ALL") {
			$sql .= " AND DESTINATION_NB = '".$destination."'";
		}*/
		$sql .= " ORDER BY ORDER_NUM";
//		echo $sql;
		// AND STATUS IN ('2', '3', '5')
		ora_parse($order_cursor, $sql);
		ora_exec($order_cursor);
		if (!ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			$pdf->ezText("<b>No Pending Orders to display</b>", 16, $center);
		} else {
//			$pdf->ezText("<b>Orders of Status $status for $load_date</b>", 16, $center);

			do { // do this for each order
				if ($per_page >= 3) {
					$pdf->ezNewPage();
					$per_page = 0;
				}
				$per_page++;

				$order_num = $order_row['ORDER_NUM'];
				$container_id = $order_row['THE_CONT'];
				$user_comments = $order_row['ORDER_COMMENT'];
				$order_cust = $order_row['CUSTOMER_ID'];
				$total_exp_wt = 0;

//				$pdf->ezText("<b>Order: $order_num                        Scan:                           Container: $container_id</b>", 12, $center);

				$block_heading = array('colA' => '<b>Booking #</b>',
										'colB' => '<b>PO #</b>',
										'colBB' => '<b>Warehouse Code</b>',
										'colBBB' => '<b>Grade Code</b>',
										'colC' => '<b>Width</b>',
										'colD' => '<b>Diameter</b>',
										'colE' => '<b>Avg Wt (lbs)</b>',
										'colF' => '<b>Qty To Ship</b>',
										'colG' => '<b>Qty in House</b>');
				$output_array = array();
										

				$sql = "SELECT
							od.booking_num,
							od.p_o,
							od.width,
							od.dia,
							od.qty_to_ship,
							wc.warehouse_code,
							od.sscc_grade_code
						FROM booking_order_details od
						LEFT JOIN booking_warehouse_code wc
							ON od.width * 10 = wc.width
							AND od.booking_num = wc.booking_num
							AND od.sscc_grade_code = wc.sscc_grade_code
						WHERE
							od.order_num = '$order_num' 
						ORDER BY
							od.booking_num,
							od.p_o";
				ora_parse($booking_cursor, $sql);
				ora_exec($booking_cursor);
				
				while (ora_fetch_into($booking_cursor, $booking_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
					$booking = $booking_row['BOOKING_NUM'];
					$qty_ship = $booking_row['QTY_TO_SHIP'];
					$p_o = $booking_row['P_O'];
					$whCode = $booking_row['WAREHOUSE_CODE'];
					$width = $booking_row['WIDTH'];
					$dia = $booking_row['DIA'];
					$gradeCode = $booking_row['SSCC_GRADE_CODE'];

//									AND QTY_IN_HOUSE > 0
					$sql = "SELECT
								SUM(QTY_IN_HOUSE) IN_HOUSE,
								ROUND(AVG(WEIGHT * UC3.CONVERSION_FACTOR)) THE_WEIGHT 
							FROM
								CARGO_TRACKING CT,
								BOOKING_ADDITIONAL_DATA BAD,
								UNIT_CONVERSION_FROM_BNI UC1,
								UNIT_CONVERSION_FROM_BNI UC2,
								UNIT_CONVERSION_FROM_BNI UC3
							WHERE
								REMARK = 'BOOKINGSYSTEM'
								AND DATE_RECEIVED IS NOT NULL
								AND CT.PALLET_ID = BAD.PALLET_ID
								AND CT.RECEIVER_ID = BAD.RECEIVER_ID
								AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
								AND CT.RECEIVER_ID = '".$order_cust."'
								AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
									(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
									WHERE BD.DAMAGE_TYPE = PDC.DAMAGE_CODE
									AND PDC.REJECT_LEVEL = 'REJECT'
									AND BD.DATE_CLEARED IS NULL
								)
								AND BAD.BOOKING_NUM = '".$booking."'
								AND BAD.ORDER_NUM = '".$p_o."'	
								AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM
									AND UC1.SECONDARY_UOM = 'CM'
									AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
								AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM
									AND UC2.SECONDARY_UOM = 'CM'
									AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'
								AND WEIGHT_UNIT = UC3.PRIMARY_UOM
									AND UC3.SECONDARY_UOM = 'LB'";
					ora_parse($short_term_data_cursor, $sql);
					ora_exec($short_term_data_cursor);
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$in_house = $short_term_data_row['IN_HOUSE'];
					$avg_wt = $short_term_data_row['THE_WEIGHT'];
					$total_exp_wt += ($qty_ship * $avg_wt);
//					$dmg_house = $short_term_data_row['DMG_HOUSE'];

					array_push($output_array, array('colA' => $booking,
													'colB' => $p_o,
													'colBB' => $whCode,
													'colBBB' => $gradeCode,
													'colC' => $width." cm / ".round($width / 2.54, 1)."\"",
													'colD' => $dia." cm / ".round($dia / 2.54, 1)."\"",
													'colE' => $avg_wt,
													'colF' => $qty_ship,
													'colG' => $in_house));
				}

				$pdf->ezTable($output_array, $block_heading, "<b>Order: $order_num                       Container: $container_id              Expected weight of order: $total_exp_wt lbs</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>1, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));

				if ($user_comments != "") {
					$pdf->ezText("Notes:  ".$user_comments, 10, left);
				}

				$pdf->ezSetDy(-20);
			} while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}


		include("redirect_pdf.php");
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<form name="the_form" action="daily_orders_booking.php" method="post">
	<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<?php
		if ($eport_customer_id != 0) {
	?>
			<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>">
	<?php
		} else {
	?>
		<tr>
			<td align="center"><font size="2" face="Verdana">For Customer:&nbsp;&nbsp;</font>
			<select name="cust"><option value="all">All</option>
							<option value="314"<?php if ($cust == "314") { ?> selected <?php } ?>>314</option>
							<option value="338"<?php if ($cust == "338") { ?> selected <?php } ?>>338</option>
					</select></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td align="center">Loading Date:  <input name="load_date" type="text" size="15" maxlength="15">  <a href="javascript:show_calendar('the_form.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
		</tr>
	<!--	<tr>
			<td align="center">Destination:  <select name="destination"><option value="ALL">All</option>
	<?php
		$sql = "SELECT
					DESTINATION_NB,
					DESTINATION
				FROM DOLEPAPER_DESTINATIONS
				ORDER BY DESTINATION_NB";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
	?>
									<option value="<?php echo $row['DESTINATION_NB']; ?>"><?php echo $row['DESTINATION']; ?></option>
	<?php
		}
	?>
									</select></td>
		</tr> !-->
		<tr>
			<td align="center">Status:&nbsp;&nbsp;	<select name="status"><!--<option value="All">All</option>!-->
					<option value="(2, 5)" selected>2+5 (Submitted / Revision Submitted)</option>
	<?php
			$sql = "SELECT * FROM DOLEPAPER_STATUSES ORDER BY STATUS";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			while (ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				//if ($row['STATUS'] == 2) { selected }
	?>
									<option value="(<?php echo $row['STATUS']; ?>)"><?php echo $row['STATUS']." - ".$row['ST_DESCRIPTION'];?></option>
	<?php
			}
	?>
				</select></td>
		</tr>
		<tr>
			<td align="center"><input type="submit" name="orders" value="Generate List"></td>
		</tr>
		<tr>
			<td>&nbsp;<hr>&nbsp;</td>
		</tr>
	</table>
</form>
