<?
/*
*		Adam Walter, April 2009
*
*		A report for Dole Dockticket paper, that shows a recap
*		Of Orders for a vessel, based on search criteria.
*
*		Note:  In addition to the on-screen part, this also writes
*		To a *.csv file, linked to from the resulting page.
************************************************************************/
	include 'class.ezpdf.php';

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("TCONSTANT@RFTEST", "TCONSTANT");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn2);
		exit;
	}
	$cursor = ora_open($conn2);
	$short_term_cursor = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$report_date_from = $HTTP_POST_VARS['report_date_from'];
	$report_date_to = $HTTP_POST_VARS['report_date_to'];
	$order_status = $HTTP_POST_VARS['order_status'];
	$destination = $HTTP_POST_VARS['destination'];



	$order_report = $HTTP_POST_VARS['order_report'];
	$PO_report = $HTTP_POST_VARS['PO_report'];
	$PO_recap = $HTTP_POST_VARS['PO_recap'];
	$code_recap = $HTTP_POST_VARS['code_recap'];


	
	if($submit == "" || $vessel == "" || ($order_report == "" && $PO_report == "" && $PO_recap == "" && $code_recap == "")){
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="vessel_recap.php" method="post">
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Vessel Recap</b></font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><b>Vessel:</b>&nbsp;&nbsp;&nbsp;<select name="vessel"><option value="">Select Vessel</option>
<?		
		$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'DOLE' AND LR_NUM IN (SELECT DISTINCT ARRIVAL_NUM FROM DOLEPAPER_ORDER WHERE STATUS IN ('6', '7')) ORDER BY LR_NUM DESC";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){?> selected <?}?>><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
		}
?>		
			</select></td>
	</tr>
	<tr>
		<td align="left">Load Date (from):</td>
		<td align="left"><input name="report_date_from" type="text" size="15" maxlength="15" value="<? echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.report_date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
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
		<td align="left"><input name="report_date_to" type="text" size="15" maxlength="15" value="<? echo $report_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.report_date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="20%" align="left">Status:</td>
		<td align="left"><select name="order_status">
		<option value="all">Complete & Confirmed</option>
		<option value="6" <? if($order_status == "6"){?> selected <?}?>>Complete</option>
		<option value="7" <? if($order_status == "7"){?> selected <?}?>>Confirmed</option>
						</select></td>
	</tr>
	<tr>
		<td width="20%" align="left">Destination:</td>
		<td align="left"><select name="destination"><option value="all">All</option>
<?
		$sql = "SELECT DESTINATION_NB, DESTINATION FROM DOLEPAPER_DESTINATIONS ORDER BY DESTINATION_NB";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['DESTINATION_NB']; ?>"<? if($row['DESTINATION_NB'] == $destination){?> selected <?}?>><? echo $row['DESTINATION']; ?></option>
<?
		}
?>		
			</select></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>
<?
	} else {
		// start the pdf...
		$pdf = new Cezpdf('letter','landscape');
		$pdf->ezSetMargins(20,20,65,65);
		$pdf->ezStartPageNumbers(700,10,8,'right');
		$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
		$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
		$pdf->ezSetDy(-75);

		// start the csv...
		$fp = fopen("vessel_recap.csv", "w");
		if(!$fp){
			echo "can not open file for writing, please contact Port of Wilmington";
			exit;
		}

		$sql = "SELECT VP.LR_NUM, VP.VESSEL_NAME, NVL(TO_CHAR(VO.DATE_DEPARTED, 'MM/DD/YYYY'), 'IN PORT') DATE_SAILED FROM VESSEL_PROFILE VP, VOYAGE VO WHERE VP.LR_NUM = VO.LR_NUM AND VP.LR_NUM = '".$vessel."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $short_term_row['LR_NUM']." - ".$short_term_row['VESSEL_NAME'];
		$sail_date = $short_term_row['DATE_SAILED'];

		$pdf->ezText("<b>Vessel Recap Report, $vessel_name</b>", 16, $center);
		fwrite($fp, "Vessel Recap Report, ".$vessel_name."\n");

		$pdf->ezText("<b>Selected Options:</b>", 14, $center);
		fwrite($fp, "Selected Options:\n");

		if($report_date_from != ""){
			$pdf->ezText("<b>Start Date:  $report_date_from</b>", 12, $center);
			fwrite($fp, "Start Date:  ".$report_date_from."\n");
		} else {
			$pdf->ezText("<b>Start Date:  none specified</b>", 12, $center);
			fwrite($fp, "Start Date:  none specified\n");
		}
		
		if($report_date_to != ""){
			$pdf->ezText("<b>End Date:  $report_date_to</b>", 12, $center);
			fwrite($fp, "End Date:  ".$report_date_to."\n");
		} else {
			$pdf->ezText("<b>End Date:  none specified</b>", 12, $center);
			fwrite($fp, "End Date:  none specified\n");
		}
		
		if($order_status != "all"){
			$sql = "SELECT ST_DESCRIPTION FROM DOLEPAPER_STATUSES WHERE STATUS = '".$order_status."'";
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
		
		if($destination != "all"){
			$sql = "SELECT DESTINATION FROM DOLEPAPER_DESTINATIONS WHERE DESTINATION_NB = '".$destination."'";
			ora_parse($short_term_cursor, $sql);
			ora_exec($short_term_cursor);
			ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$dest_desc = $short_term_row['DESTINATION'];

			$pdf->ezText("<b>Destination:  $dest_desc</b>", 12, $center);
			fwrite($fp, "Destination:  ".$dest_desc."\n");
		} else {
			$pdf->ezText("<b>Destination:  All</b>", 12, $center);
			fwrite($fp, "Destination:  All\n");
		}

		$pdf->ezSetDy(-5);
		$pdf->ezText("<c:alink:https://www.eportwilmington.com/dole/vessel_recap.csv>Click Here for a downloadable CSV version</c:alink>", 12, $center);


		// PART ONE:  If they have the ckeckbox for REPORT BY ORDER
		if($order_report == "yes"){
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;
			$total_distinct_containers = 0;


			$block_heading = array('colA'=>'<b>Load Date</b>',
									'colB'=>'<b>Order</b>',
									'colC'=>'<b>Container</b>',
									'colD'=>'<b>Dock Ticket</b>',
									'colE'=>'<b>Paper Code</b>',
									'colF'=>'<b>Rolls</b>',
									'colG'=>'<b>Weight</b>',
									'colH'=>'<b>S-TON</b>',
									'colI'=>'<b>KG</b>',
									'colJ'=>'<b>Seal</b>');
			$output_array = array();

			$distinct_container_list = array();


			fwrite($fp, "Load Date,Order,Container,Dock Ticket,Paper Code,Rolls,Weight,S-TON,KG,Seal\n");

			$sql = "SELECT DD.CONTAINER_ID, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY') THE_LOAD, DD.ORDER_NUM, CT.BOL, CT.BATCH_ID, SUM(QTY_CHANGE) THE_SHIPPED, SUM(WEIGHT) THE_WEIGHT, SEAL 
					FROM DOLEPAPER_ORDER DD, CARGO_TRACKING CT, CARGO_ACTIVITY CA 
					WHERE CT.PALLET_ID = CA.PALLET_ID 
						AND CT.BOL = CA.BATCH_ID 
						AND CA.ORDER_NUM = TO_CHAR(DD.ORDER_NUM) 
						AND CA.ACTIVITY_DESCRIPTION IS NULL 
						AND CA.SERVICE_CODE = '6' 
						AND DD.ARRIVAL_NUM = '".$vessel."' ";
			if($report_date_from != ""){
				$sql .= "AND DD.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if($report_date_to != ""){
				$sql .= "AND DD.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if($order_status != "all"){
				$sql .= "AND DD.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND DD.STATUS IN ('6', '7') ";
			}
			if($destination != "all"){
				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
			}
			$sql .= " GROUP BY DD.CONTAINER_ID, DD.ORDER_NUM, CT.BOL, CT.BATCH_ID, TO_CHAR(DD.LOAD_DATE, 'MM/DD/YYYY'), SEAL 
						ORDER BY MIN(DATE_OF_ACTIVITY), DD.CONTAINER_ID, CT.BOL";
//			echo $sql;
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// for each line...

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];



				array_push($output_array, array('colA'=>$row['THE_LOAD'],
												'colB'=>$row['ORDER_NUM'],
												'colC'=>$row['CONTAINER_ID'],
												'colD'=>$row['BOL'],
												'colE'=>$row['BATCH_ID'],
												'colF'=>$row['THE_SHIPPED'],
												'colG'=>$row['THE_WEIGHT'],
												'colH'=>round($row['THE_WEIGHT'] / 2000, 2),
												'colI'=>round($row['THE_WEIGHT'] / 2.2, 2),
												'colJ'=>$row['SEAL']));
				fwrite($fp, $row['THE_LOAD'].",".
							$row['ORDER_NUM'].",".
							$row['CONTAINER_ID'].",".
							$row['BOL'].",".
							$row['BATCH_ID'].",".
							$row['THE_SHIPPED'].",".
							$row['THE_WEIGHT'].",".
							round($row['THE_WEIGHT'] / 2000, 2).",".
							round($row['THE_WEIGHT'] / 2.2, 2).",".
							$row['SEAL']."\n");

				if(array_search($row['CONTAINER_ID'], $distinct_container_list) === FALSE){
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
											'colF'=>$total_rolls,
											'colG'=>$total_lbs,
											'colH'=>round($total_lbs / 2000, 2),
											'colI'=>round($total_lbs / 2.2, 2),
											'colJ'=>' '));
			$pdf->ezTable($output_array, $block_heading, "<b>Report by Order</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));


			fwrite($fp, "TOTALS,,".$total_distinct_containers.",,,".$total_rolls.",".$total_lbs.",".round($total_lbs / 2000, 2).",".round($total_lbs / 2.2, 2)."\n\n\n");
		}

		// PART TWO:  If they have the ckeckbox for REPORT BY P.O.
		if($PO_report == "yes"){
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;
			$total_rols_dmg = 0;
			$total_msf = 0;

			$block_heading = array('colA'=>'<b>P.O.</b>',
									'colB'=>'<b>Dock Ticket</b>',
									'colC'=>'<b>Paper Code</b>',
									'colD'=>'<b>Railcar</b>',
									'colE'=>'<b>Rolls</b>',
									'colF'=>'<b>Weight</b>',
									'colG'=>'<b>Damaged</b>',
									'colH'=>'<b>MSF</b>');
			$output_array = array();
			fwrite($fp, "P.O.,Dock Ticket,Paper Code,Railcar,Rolls,Weight,Damaged\n");


			$sql = "SELECT SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')) THE_PO, CT.BOL, CT.BATCH_ID, CT.ARRIVAL_NUM, SUM(QTY_CHANGE) THE_SHIPPED, 
					SUM(WEIGHT) THE_WEIGHT, SUM(QTY_DAMAGED) THE_DMG, SUM((TO_NUMBER(NVL(VARIETY, '0')) * (TO_NUMBER(CARGO_SIZE) / 12)) / 1000) MSF 
					FROM DOLEPAPER_ORDER DD, CARGO_TRACKING CT, CARGO_ACTIVITY CA 
					WHERE CT.PALLET_ID = CA.PALLET_ID 
						AND CT.BOL = CA.BATCH_ID 
						AND CA.ORDER_NUM = TO_CHAR(DD.ORDER_NUM) 
						AND CA.ACTIVITY_DESCRIPTION IS NULL 
						AND CA.SERVICE_CODE = '6' 
						AND DD.ARRIVAL_NUM = '".$vessel."' ";
			if($report_date_from != ""){
				$sql .= "AND DD.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if($report_date_to != ""){
				$sql .= "AND DD.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if($order_status != "all"){
				$sql .= "AND DD.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND DD.STATUS IN ('6', '7') ";
			}
			if($destination != "all"){
				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
			}
			$sql .= " GROUP BY SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')), CT.BOL, CT.BATCH_ID, CT.ARRIVAL_NUM 
						ORDER BY THE_PO, CT.BOL";
//			echo $sql."<br>";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$msf = round($row['MSF'], 1);

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];
				$total_rols_dmg += $row['THE_DMG'];
				$total_msf += $msf;


				array_push($output_array, array('colA'=>$row['THE_PO'],
												'colB'=>$row['BOL'],
												'colC'=>$row['BATCH_ID'],
												'colD'=>$row['ARRIVAL_NUM'],
												'colE'=>$row['THE_SHIPPED'],
												'colF'=>$row['THE_WEIGHT'],
												'colG'=>$row['THE_DMG'],
												'colH'=>$msf));

				fwrite($fp, $row['THE_PO'].",".
							$row['BOL'].",".
							$row['BATCH_ID'].",".
							$row['ARRIVAL_NUM'].",".
							$row['THE_SHIPPED'].",".
							$row['THE_WEIGHT'].",".
							$row['THE_DMG'].",".
							$msf."\n");
			}

			// totals line
			array_push($output_array, array('colA'=>'<b>TOTALS</b>',
											'colB'=>' ',
											'colC'=>' ',
											'colD'=>' ',
											'colE'=>$total_rolls,
											'colF'=>$total_lbs,
											'colG'=>$total_rols_dmg,
											'colH'=>$total_msf));

			$pdf->ezTable($output_array, $block_heading, "<b>Report by P.O.</b>", array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'fontSize'=>10, 'width'=>680, 'protectRows'=>10));

			fwrite($fp, "TOTALS,,,,".$total_rolls.",".$total_lbs.",".$total_rols_dmg.",".$total_msf."\n\n\n");

		}


		// PART THREE:  If they have the ckeckbox for P.O. Recap
		if($PO_recap == "yes"){
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;

			$block_heading = array('colA'=>'<b>P.O.</b>',
									'colB'=>'<b>Rolls</b>',
									'colC'=>'<b>Weight</b>');
			$output_array = array();
			fwrite($fp, "P.O.,Rolls,Weight\n");


			$sql = "SELECT SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')) THE_PO, SUM(QTY_CHANGE) THE_SHIPPED, SUM(WEIGHT) THE_WEIGHT FROM DOLEPAPER_ORDER DD, CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.BOL = CA.BATCH_ID AND CA.ORDER_NUM = TO_CHAR(DD.ORDER_NUM) AND CA.ACTIVITY_DESCRIPTION IS NULL AND CA.SERVICE_CODE = '6' AND DD.ARRIVAL_NUM = '".$vessel."' ";
			if($report_date_from != ""){
				$sql .= "AND DD.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if($report_date_to != ""){
				$sql .= "AND DD.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if($order_status != "all"){
				$sql .= "AND DD.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND DD.STATUS IN ('6', '7') ";
			}
			if($destination != "all"){
				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
			}
			$sql .= " GROUP BY SUBSTR(CARGO_DESCRIPTION, 0, INSTR(CARGO_DESCRIPTION, ' ')) ORDER BY THE_PO";
//			echo $sql;
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];


				array_push($output_array, array('colA'=>$row['THE_PO'],
												'colB'=>$row['THE_SHIPPED'],
												'colC'=>$row['THE_WEIGHT']));

				fwrite($fp, $row['THE_PO'].",".
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
		if($code_recap == "yes"){
			$pdf->ezNewPage();

			$total_rolls = 0;
			$total_lbs = 0;

			$block_heading = array('colA'=>'<b>Paper Code</b>',
									'colB'=>'<b>Rolls</b>',
									'colC'=>'<b>Weight</b>');
			$output_array = array();
			fwrite($fp, "Paper Code,Rolls,Weight\n");


			$sql = "SELECT CT.BATCH_ID, SUM(QTY_CHANGE) THE_SHIPPED, SUM(WEIGHT) THE_WEIGHT FROM DOLEPAPER_ORDER DD, CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.BOL = CA.BATCH_ID AND CA.ORDER_NUM = TO_CHAR(DD.ORDER_NUM) AND CA.ACTIVITY_DESCRIPTION IS NULL AND CA.SERVICE_CODE = '6' AND DD.ARRIVAL_NUM = '".$vessel."' ";
			if($report_date_from != ""){
				$sql .= "AND DD.LOAD_DATE >= TO_DATE('".$report_date_from."', 'MM/DD/YYYY') ";
			}
			if($report_date_to != ""){
				$sql .= "AND DD.LOAD_DATE <= TO_DATE('".$report_date_to."', 'MM/DD/YYYY') ";
			}
			if($order_status != "all"){
				$sql .= "AND DD.STATUS = '".$order_status."' ";
			} else {
				$sql .= "AND DD.STATUS IN ('6', '7') ";
			}
			if($destination != "all"){
				$sql .= "AND DD.DESTINATION_NB ='".$destination."' ";
			}
			$sql .= " GROUP BY CT.BATCH_ID ORDER BY CT.BATCH_ID";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

				$total_rolls += $row['THE_SHIPPED'];
				$total_lbs += $row['THE_WEIGHT'];


				array_push($output_array, array('colA'=>$row['BATCH_ID'],
												'colB'=>$row['THE_SHIPPED'],
												'colC'=>$row['THE_WEIGHT']));

				fwrite($fp, $row['BATCH_ID'].",".
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