<?
	include 'class.ezpdf.php';
	include("useful_info.php");

	$order_cursor = ora_open($conn);
	$dockticket_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$load_date = $HTTP_POST_VARS['load_date'];
	$destination = $HTTP_POST_VARS['destination'];
	if($load_date != ""){
		if($destination == "ALL"){
			$dest_name = "ALL";
		}else{
			$sql = "SELECT DESTINATION FROM DOLEPAPER_DESTINATIONS WHERE DESTINATION_NB = '".$destination."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$dest_name = $short_term_data_row['DESTINATION'];
		}

		$pdf = new Cezpdf('letter','landscape');
		$pdf->ezSetMargins(20,20,65,65);
		$pdf->ezStartPageNumbers(700,10,8,'right');
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
		$pdf->ezSetDy(-20);
		$pdf->ezText("<b>Dole Paper Loading Order Sheet</b>", 16, $center);
		$pdf->ezText("<b>$load_date</b>", 14, $center);
		$pdf->ezText("<b>Destination:  $dest_name</b>", 14, $center);
		$pdf->ezSetDy(-5);

		$lines = 0;

		$sql = "SELECT ORDER_NUM, CONTAINER_ID, USER_COMMENTS, BOOKING_NUM FROM DOLEPAPER_ORDER WHERE TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."'";
		if($destination != "ALL"){
			$sql .= " AND DESTINATION_NB = '".$destination."'";
		}
		$sql .= " ORDER BY ORDER_NUM";
//		echo $sql;
		// AND STATUS IN ('2', '3', '5')
		ora_parse($order_cursor, $sql);
		ora_exec($order_cursor);
		if(!ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$pdf->ezText("<b>No Pending Orders to display</b>", 16, $center);
		} else {
			do { // do this for each order
				$order_num = $order_row['ORDER_NUM'];
				$container_id = $order_row['CONTAINER_ID'];
				$user_comments = $order_row['USER_COMMENTS'];
				$booking_num = $order_row['BOOKING_NUM'];

//				$pdf->ezText("<b>Order: $order_num                        Scan:                           Container: $container_id</b>", 12, $center);

				$block_heading = array('colA'=>'<b>Dock Ticket</b>',
										'colB'=>'<b>QTY to ship</b>',
										'colC'=>'<b>Of which ___ is Reject</b>',
										'colD'=>'<b>P.O.</b>',
										'colE'=>'<b>QTY in House</b>',
										'colF'=>'<b>Of which ___ is Reject</b>');
				$output_array = array();
										

				$sql = "SELECT DOCK_TICKET, QTY_SHIP, QTY_DMG_SHIP, P_O FROM DOLEPAPER_DOCKTICKET WHERE ORDER_NUM = '".$order_num."' ORDER BY DOCK_TICKET";
				ora_parse($dockticket_cursor, $sql);
				ora_exec($dockticket_cursor);
				while(ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$dock_ticket = $dockticket_row['DOCK_TICKET'];
					$qty_ship = $dockticket_row['QTY_SHIP'];
					$qty_dmg = $dockticket_row['QTY_DMG_SHIP'];
					$p_o = $dockticket_row['P_O'];

					$sql = "SELECT SUM(QTY_IN_HOUSE) IN_HOUSE, SUM(QTY_DAMAGED) DMG_HOUSE FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' AND BOL = '".$dock_ticket."'";
					ora_parse($short_term_data_cursor, $sql);
					ora_exec($short_term_data_cursor);
					ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$in_house = $short_term_data_row['IN_HOUSE'];
					$dmg_house = $short_term_data_row['DMG_HOUSE'];

					array_push($output_array, array('colA'=>$dock_ticket,
													'colB'=>$qty_ship,
													'colC'=>$qty_dmg,
													'colD'=>$p_o,
													'colE'=>$in_house,
													'colF'=>$dmg_house));
				}

				$pdf->ezTable($output_array, $block_heading, "<b>Order: $order_num              Booking #: $booking_num</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>1, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));

				if($user_comments != ""){
					$pdf->ezText("Notes:  ".$user_comments, 10, left);
				}

				$pdf->ezSetDy(-20);
			} while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}


		include("redirect_pdf.php");
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="daily_orders.php" method="post">
	<tr>
		<td align="center">Loading Date:  <input name="load_date" type="text" size="15" maxlength="15">  <a href="javascript:show_calendar('the_form.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="center">Destination:  <select name="destination"><option value="ALL">All</option>
<?
	$sql = "SELECT DESTINATION_NB, DESTINATION FROM DOLEPAPER_DESTINATIONS ORDER BY DESTINATION_NB";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['DESTINATION_NB']; ?>"><? echo $row['DESTINATION']; ?></option>
<?
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
</form>
</table>
