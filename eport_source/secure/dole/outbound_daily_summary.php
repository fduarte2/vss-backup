<?
/*
*	Adam Walter, April 29, 2009..
*
*	A summary of total railcars, rolls, pounds, and tons
*	Received for given date range.
*************************************************************************/

	include 'class.ezpdf.php';
	include("useful_info.php");

	$order_cursor = ora_open($conn);

	$start_date = $HTTP_POST_VARS['start_date'];
	$end_date = $HTTP_POST_VARS['end_date'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && $start_date != ""){
		$pdf = new Cezpdf('letter','landscape');
		$pdf->ezSetMargins(20,20,65,65);
		$pdf->ezStartPageNumbers(700,10,8,'right');
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
		$pdf->ezSetDy(-20);
		$pdf->ezText("<b>Dole Paper Outbound Summary</b>", 16, $center);
		if($end_date == ""){
			$pdf->ezText("<b>Day of $start_date</b>", 14, $center);
		} else {
			$pdf->ezText("<b>Start Date:  $start_date</b>", 14, $center);
			$pdf->ezText("<b>End Date:  $end_date</b>", 14, $center);
		}
		$pdf->ezNewPage();

		$sql = "SELECT DD.CONTAINER_ID, DD.ORDER_NUM, COUNT(*) THE_ROLLS, SUM(WEIGHT) THE_LBS 
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, DOLEPAPER_ORDER DD 
				WHERE CT.PALLET_ID = CA.PALLET_ID 
					AND CT.REMARK = 'DOLEPAPERSYSTEM' 
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID 
					AND CT.BOL = CA.BATCH_ID 
					AND CA.SERVICE_CODE = '6' 
					AND CA.ACTIVITY_DESCRIPTION IS NULL 
					AND CA.ORDER_NUM = TO_CHAR(DD.ORDER_NUM) 
					AND CA.DATE_OF_ACTIVITY > TO_DATE('".$start_date."', 'MM/DD/YYYY')";
		if($end_date == ""){
			$sql .= " AND CA.DATE_OF_ACTIVITY < TO_DATE('".$start_date." 23:59', 'MM/DD/YYYY HH24:MI')";
		} else {
			$sql .= " AND CA.DATE_OF_ACTIVITY < TO_DATE('".$end_date." 23:59', 'MM/DD/YYYY HH24:MI')";
		}
		$sql .= " GROUP BY DD.CONTAINER_ID, DD.ORDER_NUM 
				ORDER BY DD.CONTAINER_ID, DD.ORDER_NUM";

//		echo $sql;
		ora_parse($order_cursor, $sql);
		ora_exec($order_cursor);
		if(!ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$pdf->ezText("<b>No Records to display</b>", 16, $center);
		} else {
			$total_rolls = 0;
			$total_lbs = 0;

			$block_heading = array('colA'=>'<b>Container</b>',
									'colB'=>'<b>Order#</b>',
									'colC'=>'<b>Rolls</b>',
									'colD'=>'<b>LBS</b>',
									'colE'=>'<b>Tons</b>');
			$output_array = array();

			do {
				array_push($output_array, array('colA'=>$order_row['CONTAINER_ID'],
												'colB'=>$order_row['ORDER_NUM'],
												'colC'=>$order_row['THE_ROLLS'],
												'colD'=>$order_row['THE_LBS'],
												'colE'=>round($order_row['THE_LBS'] / 2000, 2)));
				$total_rolls += $order_row['THE_ROLLS'];
				$total_lbs += $order_row['THE_LBS'];

			} while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			array_push($output_array, array('colA'=>'TOTALS:',
											'colB'=>$total_rolls,
											'colC'=>$total_lbs,
											'colD'=>round($total_lbs / 2000, 2)));

			$pdf->ezTable($output_array, $block_heading, "<b></b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));

		}

		include("redirect_pdf.php");
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="outbound_daily_summary.php" method="post">
	<tr>
		<td align="left">Start Date:  <input name="start_date" type="text" size="15" maxlength="15">  <a href="javascript:show_calendar('the_form.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a><font size="2" face="Verdana"> (required)</font></td>
	</tr>
	<tr>
		<td align="left">End Date:  <input name="end_date" type="text" size="15" maxlength="15">  <a href="javascript:show_calendar('the_form.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="left"><input type="submit" name="submit" value="Generate Summary"></td>
	</tr>
	<tr>
		<td>&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>
