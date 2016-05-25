<?php
/*
*		Adam Walter, Jul-Aug 2010
*
*		A report for Booking paper, that shows a recap
*		Of Orders for a vessel, based on search criteria.
*
*		Note:  In addition to the on-screen part, this also writes
*		To a *.csv file, linked to from the resulting page.
************************************************************************/
	include("useful_info.php");
	include 'class.ezpdf.php';

//	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("TCONSTANT@RFTEST", "TCONSTANT");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
//	if ($conn2 < 1) {
//		echo "Error logging on to the RF Oracle Server: ";
//		echo ora_errorcode($conn2);
//		exit;
//	}
	$cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];

	$submit = $_POST['submit'];
	$vessel = $_POST['vessel'];
	$report_date_from = $_POST['report_date_from'];
	$report_date_to = $_POST['report_date_to'];
	$order_status = $_POST['order_status'];
	$order_sort_by = $_POST['order_sort_by'];
	$cust = $_POST['cust'];



	$order_report = $_POST['order_report'];
	$PO_report = $_POST['PO_report'];
	$PO_recap = $_POST['PO_recap'];
	$code_recap = $_POST['code_recap'];
	
	if ($submit == "" || $vessel == "" || ($order_report == "" && $PO_report == "" && $PO_recap == "" && $code_recap == "")) {
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="vessel_recap_booking.php" method="post">
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Vessel Recap</b></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><b>Vessel:</b>&nbsp;&nbsp;&nbsp;<select name="vessel"><option value="">Select Vessel</option>
<?php		
		$sql = "SELECT
					LR_NUM,
					VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE
					SHIP_PREFIX = 'DOLE'
					AND LR_NUM IN (SELECT DISTINCT ARRIVAL_NUM
								   FROM DOLEPAPER_ORDER
								   WHERE STATUS IN ('6', '7'))
				ORDER BY LR_NUM DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
						<option value="<?php echo $row['LR_NUM']; ?>"<?php if ($row['LR_NUM'] == $vessel) { ?> selected <?php } ?>><?php echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?php
		}
?>		
			</select></td>
	</tr>
	<tr>
		<td align="left">Load Date (from):</td>
		<td align="left"><input name="report_date_from" type="text" size="15" maxlength="15" value="<?php echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.report_date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
		<td rowspan="4">
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
				<tr>
					<td align="right" width="80%"><font size="2" face="Verdana">Report by Order</font></td>
					<td align="right"><input type="checkbox" name="order_report" value="yes" checked>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td align="right" width="80%"><font size="2" face="Verdana">Report by P.O.</font></td>
					<td align="right"><input type="checkbox" name="PO_report" value="yes" checked>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td align="right" width="80%"><font size="2" face="Verdana">P.O. Recap</font></td>
					<td align="right"><input type="checkbox" name="PO_recap" value="yes" checked>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td align="right" width="80%"><font size="2" face="Verdana">Paper Code Recap</font></td>
					<td align="right"><input type="checkbox" name="code_recap" value="yes" checked>&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="20%" align="left">Load Date (to):</td>
		<td align="left"><input name="report_date_to" type="text" size="15" maxlength="15" value="<?php echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.report_date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="20%" align="left">Status:</td>
		<td align="left"><select name="order_status">
			<option value="all">Complete & Confirmed</option>
			<option value="6" <?php if ($order_status == "6") { ?> selected <?php } ?>>Complete</option>
			<option value="7" <?php if ($order_status == "7") { ?> selected <?php } ?>>Confirmed</option>
						</select></td>
	</tr>
	<tr>
		<td width="20%" align="left">Order By (Report by Order only):</td>
		<td align="left"><select name="order_sort_by">
			<option value="BAD.ORDER_NUM, BAD.BOOKING_NUM, ">PO then Booking</option>
			<option value="BAD.BOOKING_NUM, BAD.ORDER_NUM, ">Booking then PO</option>
			<option value="BAD.BOOKING_NUM, ">Booking</option>
			<option value="BAD.ORDER_NUM, ">PO</option>
			<option value="">Date and Container only</option>
			</select></td>
	</tr>
<?php
	if ($eport_customer_id == 0) {
?>
	<tr>
		<td width="20%" align="left">Customer:</td>
		<td colspan="2"><select name="cust"><option value="all">All</option>
								<option value="314"<?php if ($cust == "314") { ?> selected <?php } ?>>314</option>
								<option value="338"<?php if ($cust == "338") { ?> selected <?php } ?>>338</option>
			</select></td>
	</tr>
<?php
	} else {
?>
	<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>">
<?php
	}
?>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>
<?php
	} else {
		// start the pdf...
		$pdf = new Cezpdf('letter','landscape');
		$pdf->ezSetMargins(20,20,65,65);
		$pdf->ezStartPageNumbers(700,10,8,'right');
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
		$pdf->ezSetDy(-75);

		// start the csv...
		$fp = fopen("tempXLS/vessel_recap.csv", "w");
		if (!$fp) {
			echo "can not open file for writing, please contact Port of Wilmington";
			exit;
		}

		$sql = "SELECT
					VP.LR_NUM,
					VP.VESSEL_NAME,
					NVL(TO_CHAR(VO.DATE_DEPARTED, 'MM/DD/YYYY'), 'IN PORT') DATE_SAILED
				FROM
					VESSEL_PROFILE VP,
					VOYAGE VO
				WHERE
					VP.LR_NUM = VO.LR_NUM
					AND VP.LR_NUM = '".$vessel."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $short_term_row['LR_NUM']." - ".$short_term_row['VESSEL_NAME'];
		$sail_date = $short_term_row['DATE_SAILED'];

		$pdf->ezText("<b>Vessel Recap Report, $vessel_name</b>", 16, $center);
		fwrite($fp, "Vessel Recap Report, ".$vessel_name."\n");

		$pdf->ezText("<b>Selected Options:</b>", 14, $center);
		fwrite($fp, "Selected Options:\n");

		if ($report_date_from != "") {
			$pdf->ezText("<b>Start Date:  $report_date_from</b>", 12, $center);
			fwrite($fp, "Start Date:  ".$report_date_from."\n");
		} else {
			$pdf->ezText("<b>Start Date:  none specified</b>", 12, $center);
			fwrite($fp, "Start Date:  none specified\n");
		}
		
		if ($report_date_to != "") {
			$pdf->ezText("<b>End Date:  $report_date_to</b>", 12, $center);
			fwrite($fp, "End Date:  ".$report_date_to."\n");
		} else {
			$pdf->ezText("<b>End Date:  none specified</b>", 12, $center);
			fwrite($fp, "End Date:  none specified\n");
		}
		
		if ($order_status != "all") {
			$sql = "SELECT ST_DESCRIPTION
					FROM DOLEPAPER_STATUSES
					WHERE STATUS = '".$order_status."'";
			ora_parse($short_term_cursor, $sql);
			ora_exec($short_term_cursor);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$status_desc = $short_term_row['ST_DESCRIPTION'];

			$pdf->ezText("<b>Order Status:  $status_desc</b>", 12, $center);
			fwrite($fp, "Order Status:  ".$status_desc."\n");
		} else {
			$pdf->ezText("<b>Order Status:  Complete & Confirmed</b>", 12, $center);
			fwrite($fp, "Order Status:  Complete & Confirmed\n");
		}

		$pdf->ezSetDy(-5);
		$pdf->ezText("<c:alink:https://www.eportwilmington.com/booking/tempXLS/vessel_recap.csv>Click Here for a downloadable CSV version</c:alink>", 12, $center);

		
		
		// PART ONE:  If they have the ckeckbox for REPORT BY ORDER
		if ($order_report == "yes") {
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;
			$total_distinct_containers = 0;


			$block_heading = array('colA'=>'<b>Load Date</b>',
									'colB'=>'<b>Order</b>',
									'colC'=>'<b>Container</b>',
									'colD'=>'<b>Booking</b>',
									'colE'=>'<b>P.O.</b>',
									'colF'=>'<b>Paper Code</b>',
									'colFF'=>'<b>Wh Code</b>',
									'colG'=>'<b>Rolls</b>',
									'colH'=>'<b>Weight</b>',
									'colI'=>'<b>KG</b>',
									'colJ'=>'<b>Seal</b>');
			$output_array = array();

			$distinct_container_list = array();


			fwrite($fp, "Load Date,Order,Container,Booking,P.O.,Paper Code,Wh Code,Rolls,Weight,KG,Seal\n");

			$sql = "SELECT
						BO.CONTAINER_ID,
						TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_LOAD,
						BO.ORDER_NUM THE_ORDER, 
						BAD.BOOKING_NUM,
						BAD.ORDER_NUM P_O,
						NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN') THE_CODE,
						SUM(QTY_CHANGE) THE_ROLLS,
						SUM(ROUND(WEIGHT * UC.CONVERSION_FACTOR)) THE_WEIGHT,
						bad.warehouse_code
					FROM
						CARGO_TRACKING CT,
						CARGO_ACTIVITY CA,
						BOOKING_ADDITIONAL_DATA BAD,
						UNIT_CONVERSION_FROM_BNI UC,
						BOOKING_ORDERS BO,
						BOOKING_PAPER_GRADE_CODE BPGC
					WHERE
						CT.WEIGHT_UNIT = UC.PRIMARY_UOM
						AND UC.SECONDARY_UOM = 'LB'
						AND BO.ORDER_NUM = CA.ORDER_NUM
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CA.PALLET_ID = BAD.PALLET_ID
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
						AND CA.ACTIVITY_DESCRIPTION IS NULL
						AND CA.SERVICE_CODE = '6'
						AND BO.ARRIVAL_NUM = '".$vessel."' ";
			
			if ($report_date_from != "") {
				$sql .= "AND BO.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if ($report_date_to != "") {
				$sql .= "AND BO.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if ($order_status != "all") {
				$sql .= "AND BO.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND BO.STATUS IN ('6', '7') ";
			}
			if ($cust != "all") {
				$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
			}
//			if ($destination != "all") {
//				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
//			}
			$sql .= " GROUP BY
						BO.CONTAINER_ID,
						BO.ORDER_NUM,
						BAD.BOOKING_NUM,
						BAD.ORDER_NUM,
						NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN'),
						bad.warehouse_code
					  ORDER BY
						".$order_sort_by."
						MIN(DATE_OF_ACTIVITY),
						BO.CONTAINER_ID";
//			echo $sql;
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				// for each line...

				$total_rolls += $row['THE_ROLLS'];
				$total_lbs += $row['THE_WEIGHT'];



				array_push($output_array, array('colA'=>$row['THE_LOAD'],
												'colB'=>$row['THE_ORDER'],
												'colC'=>$row['CONTAINER_ID'],
												'colD'=>$row['BOOKING_NUM'],
												'colE'=>$row['P_O'],
												'colF'=>$row['THE_CODE'],
												'colFF'=>$row['WAREHOUSE_CODE'],
												'colG'=>$row['THE_ROLLS'],
												'colH'=>round($row['THE_WEIGHT'], 2),
												'colI'=>round($row['THE_WEIGHT'] / 2.2, 2),
												'colJ'=>' '));
				fwrite($fp, $row['THE_LOAD'].",".
							$row['THE_ORDER'].",".
							$row['CONTAINER_ID'].",".
							$row['BOOKING_NUM'].",".
							$row['P_O'].",".
							$row['THE_CODE'].",".
							$row['WAREHOUSE_CODE'].",".
							$row['THE_ROLLS'].",".
							round($row['THE_WEIGHT'], 2).",".
							round($row['THE_WEIGHT'] / 2.2, 2)."\n");

				if (array_search($row['CONTAINER_ID'], $distinct_container_list) === FALSE) {
					array_push($distinct_container_list, $row['CONTAINER_ID']);
					$total_distinct_containers++;
				}

			}

			// totals line
			array_push($output_array, array('colA'=>'<b>TOTALS</b>',
											'colB'=>' ',
											'colC'=>$total_distinct_containers,
											'colD'=>' ',
											'colE'=>' ',
											'colF'=>' ',
											'colFF'=>' ',
											'colG'=>$total_rolls,
											'colH'=>round($total_lbs, 2),
											'colI'=>round($total_lbs / 2.2, 2),
											'colJ'=>' '));
			$pdf->ezTable($output_array, $block_heading, "<b>Report by Order</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));


			fwrite($fp, "TOTALS,,".$total_distinct_containers.",,,,,".$total_rolls.",".round($total_lbs, 2).",".round($total_lbs / 2.2, 2)."\n\n\n");
		}

		// PART TWO:  If they have the ckeckbox for REPORT BY P.O.
		if ($PO_report == "yes") {
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;
			$total_rols_dmg = 0;

			$block_heading = array('colA'=>'<b>P.O.</b>',
									'colB'=>'<b>Booking</b>',
									'colC'=>'<b>Paper Code</b>',
									'colCC'=>'<b>Wh Code</b>',
									'colD'=>'<b>Railcar</b>',
									'colE'=>'<b>Rolls</b>',
									'colF'=>'<b>Weight (lb)</b>',
									'colG'=>'<b>Weight (kg)</b>',
									'colH'=>'<b>Damaged</b>');
			$output_array = array();
			fwrite($fp, "P.O.,Booking,Paper Code,Wh Code,Railcar,Rolls,Weight (lb),Weight (kg),Damaged\n");


			$sql = "SELECT
						BAD.ORDER_NUM P_O,
						BAD.BOOKING_NUM,
						NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN') THE_CODE,
						CT.ARRIVAL_NUM, 
						SUM(QTY_CHANGE) THE_SHIPPED,
						SUM(ROUND(WEIGHT * UC.CONVERSION_FACTOR)) THE_WEIGHT,
						SUM(DECODE(QTY_DAMAGED, NULL, 0, 1)) THE_DMG,
						bad.warehouse_code
					FROM
						CARGO_TRACKING CT,
						CARGO_ACTIVITY CA,
						BOOKING_ADDITIONAL_DATA BAD,
						UNIT_CONVERSION_FROM_BNI UC,
						BOOKING_ORDERS BO,
						BOOKING_PAPER_GRADE_CODE BPGC
					WHERE
						CT.WEIGHT_UNIT = UC.PRIMARY_UOM
						AND UC.SECONDARY_UOM = 'LB'
						AND BO.ORDER_NUM = CA.ORDER_NUM
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CA.PALLET_ID = BAD.PALLET_ID
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
						AND CA.ACTIVITY_DESCRIPTION IS NULL
						AND CA.SERVICE_CODE = '6'
						AND BO.ARRIVAL_NUM = '".$vessel."' ";
						
			if ($report_date_from != "") {
				$sql .= "AND BO.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if ($report_date_to != "") {
				$sql .= "AND BO.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if ($order_status != "all") {
				$sql .= "AND BO.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND BO.STATUS IN ('6', '7') ";
			}
			if ($cust != "all") {
				$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
			}
//			if ($destination != "all") {
//				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
//			}
			$sql .= " GROUP BY
						BAD.ORDER_NUM,
						BAD.BOOKING_NUM,
						NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN'),
						CT.ARRIVAL_NUM,
						bad.warehouse_code
					ORDER BY
						BAD.ORDER_NUM,
						BAD.BOOKING_NUM";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];
				$total_rols_dmg += $row['THE_DMG'];


				array_push($output_array, array('colA'=>$row['P_O'],
												'colB'=>$row['BOOKING_NUM'],
												'colC'=>$row['THE_CODE'],
												'colCC'=>$row['WAREHOUSE_CODE'],
												'colD'=>$row['ARRIVAL_NUM'],
												'colE'=>$row['THE_SHIPPED'],
												'colF'=>$row['THE_WEIGHT'],
												'colG'=>round($row['THE_WEIGHT'] / 2.2, 2),
												'colH'=>$row['THE_DMG']));

				fwrite($fp, $row['P_O'].",".
							$row['BOOKING_NUM'].",".
							$row['THE_CODE'].",".
							$row['WAREHOUSE_CODE_CODE'].",".
							$row['ARRIVAL_NUM'].",".
							$row['THE_SHIPPED'].",".
							$row['THE_WEIGHT'].",".
							round($row['THE_WEIGHT'] / 2.2, 2).",".
							$row['THE_DMG']."\n");
			}

			// totals line
			array_push($output_array, array('colA'=>'<b>TOTALS</b>',
											'colB'=>' ',
											'colC'=>' ',
											'colCC'=>' ',
											'colD'=>' ',
											'colE'=>$total_rolls,
											'colF'=>$total_lbs,
											'colG'=>round($total_lbs / 2.2, 2),
											'colH'=>$total_rols_dmg));

			$pdf->ezTable($output_array, $block_heading, "<b>Report by P.O.</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));

			fwrite($fp, "TOTALS,,,,,".$total_rolls.",".$total_lbs.",".round($total_lbs / 2.2, 2).",".$total_rols_dmg."\n\n\n");

		}


		// PART THREE:  If they have the ckeckbox for P.O. Recap
		if ($PO_recap == "yes") {
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;

			$block_heading = array('colA'=>'<b>P.O.</b>',
									'colB'=>'<b>Rolls</b>',
									'colC'=>'<b>Weight (lbs)</b>');
			$output_array = array();
			fwrite($fp, "P.O.,Rolls,Weight (lbs)\n");

			$sql = "SELECT
						BAD.ORDER_NUM,
						SUM(QTY_CHANGE) THE_SHIPPED,
						SUM(ROUND(WEIGHT * UC.CONVERSION_FACTOR)) THE_WEIGHT
					FROM
						CARGO_TRACKING CT,
						CARGO_ACTIVITY CA,
						BOOKING_ADDITIONAL_DATA BAD,
						UNIT_CONVERSION_FROM_BNI UC,
						BOOKING_ORDERS BO
					WHERE
						CT.WEIGHT_UNIT = UC.PRIMARY_UOM
						AND UC.SECONDARY_UOM = 'LB'
						AND BO.ORDER_NUM = CA.ORDER_NUM
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CA.PALLET_ID = BAD.PALLET_ID
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
						AND CA.ACTIVITY_DESCRIPTION IS NULL
						AND CA.SERVICE_CODE = '6'
						AND BO.ARRIVAL_NUM = '".$vessel."' ";
			if ($report_date_from != "") {
				$sql .= "AND BO.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if ($report_date_to != "") {
				$sql .= "AND BO.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if ($order_status != "all") {
				$sql .= "AND BO.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND BO.STATUS IN ('6', '7') ";
			}
			if ($cust != "all") {
				$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
			}
//			if ($destination != "all") {
//				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
//			}
			$sql .= " GROUP BY BAD.ORDER_NUM
					ORDER BY BAD.ORDER_NUM";
//			echo $sql;
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];


				array_push($output_array, array('colA'=>$row['ORDER_NUM'],
												'colB'=>$row['THE_SHIPPED'],
												'colC'=>$row['THE_WEIGHT']));

				fwrite($fp, $row['ORDER_NUM'].",".
							$row['THE_SHIPPED'].",".
							$row['THE_WEIGHT']."\n");
			}

			// totals line
			array_push($output_array, array('colA'=>'<b>TOTALS</b>',
											'colB'=>$total_rolls,
											'colC'=>$total_lbs));

			$pdf->ezTable($output_array, $block_heading, "<b>Recap by P.O.</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>380, 'protectRows'=>10));

			fwrite($fp, "TOTALS,".$total_rolls.",".$total_lbs."\n\n\n");

		}


		// PART FOUR:  If they have the ckeckbox for Paper Code Recap
		if ($code_recap == "yes") {
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;

			$block_heading = array('colA'=>'<b>Paper Code</b>',
									'colB'=>'<b>Rolls</b>',
									'colC'=>'<b>Weight (lbs)</b>');
			$output_array = array();
			fwrite($fp, "Paper Code,Rolls,Weight (lbs)\n");


			$sql = "SELECT
						NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN') THE_CODE,
						SUM(QTY_CHANGE) THE_SHIPPED, 
						SUM(ROUND(WEIGHT * UC.CONVERSION_FACTOR)) THE_WEIGHT
					FROM
						CARGO_TRACKING CT,
						CARGO_ACTIVITY CA,
						BOOKING_ADDITIONAL_DATA BAD,
						UNIT_CONVERSION_FROM_BNI UC,
						BOOKING_ORDERS BO,
						BOOKING_PAPER_GRADE_CODE BPGC
					WHERE
						CT.WEIGHT_UNIT = UC.PRIMARY_UOM
						AND UC.SECONDARY_UOM = 'LB'
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
						AND BO.ORDER_NUM = CA.ORDER_NUM
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CA.PALLET_ID = BAD.PALLET_ID
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
						AND CA.ACTIVITY_DESCRIPTION IS NULL
						AND CA.SERVICE_CODE = '6'
						AND BO.ARRIVAL_NUM = '".$vessel."' ";
			if ($report_date_from != "") {
				$sql .= "AND BO.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if ($report_date_to != "") {
				$sql .= "AND BO.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if ($order_status != "all") {
				$sql .= "AND BO.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND BO.STATUS IN ('6', '7') ";
			}
			if ($cust != "all") {
				$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
			}
//			if ($destination != "all") {
//				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
//			}
			$sql .= " GROUP BY NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN')
					ORDER BY NVL(BPGC.SSCC_GRADE_CODE, 'UNKNOWN')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];


				array_push($output_array, array('colA'=>$row['THE_CODE'],
												'colB'=>$row['THE_SHIPPED'],
												'colC'=>$row['THE_WEIGHT']));

				fwrite($fp, $row['THE_CODE'].",".
							$row['THE_SHIPPED'].",".
							$row['THE_WEIGHT']."\n");
			}

			// totals line
			array_push($output_array, array('colA'=>'<b>TOTALS</b>',
											'colB'=>$total_rolls,
											'colC'=>$total_lbs));

			$pdf->ezTable($output_array, $block_heading, "<b>Recap by Paper Code</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>380, 'protectRows'=>10));

			fwrite($fp, "TOTALS,".$total_rolls.",".$total_lbs."\n\n\n");

		}

		fclose($fp);
		include("redirect_pdf.php");
	}