<?php
/*
*		Adam Walter, May/June 2010
*
*		Order Entry/Modification screen of the Booking paper system.
*
*****************************************************************/

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

/*	if ($order_num == "") {
		$order_num = $HTTP_GET_VARS['order_num'];
	}
*/

	$order_num = $_POST['order_num'];
	$submit = $_POST['submit'];
	$save_success = "";
	$message_flag = "order_loaded";
	$cust = $_POST['cust'];

	if ($submit == "Create New Order" && $cust != "") {
		$sql = "SELECT NVL(MAX(TO_NUMBER(SUBSTR(ORDER_NUM, 3))), 0) ORDER_NUM FROM BOOKING_ORDERS"; 
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		// get the order, and just in case a previous order was "on screen", remove that from memory
		$order_num = "BK".($short_term_row['ORDER_NUM'] + 1);
		
		$sql = "INSERT INTO BOOKING_ORDERS (ORDER_NUM, ENTERED_BY, STATUS, ENTERED_DATE, CUSTOMER_ID) VALUES ('".$order_num."', '".$user."', '1', SYSDATE, '".$cust."')";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		$message_flag = "";
	} elseif ($submit == "Save Order") {

		$order_num = $_POST['order_num'];
		$vessel = $_POST['vessel'];
		$load_date = $_POST['load_date'];
		$container = $_POST['container'];
		$masterbook = $_POST['masterbook'];
		$status = $_POST['status'];
		$booking = $_POST['booking'];
		$width = $_POST['width'];
		$qty_ship = $_POST['qty_ship'];
		$dia = $_POST['dia'];
		$PO = $_POST['PO'];
		$SSCC = $_POST['SSCC'];
		$user_comments = $_POST['user_comments'];

		$user_comments = str_replace("\"", "", $user_comments);
		$user_comments = str_replace("\\", "", $user_comments);
		$user_comments = str_replace("'", "`", $user_comments);

		// get current status
		$sql = "SELECT STATUS FROM BOOKING_ORDERS WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$old_status = $row['STATUS'];


		// time to save!

		// update order table.
		$sql = "UPDATE BOOKING_ORDERS SET ";
		if ($status != "") {
			$sql .= "STATUS = '".$status."', ";
		}
		if ($load_date != "") {
			$sql .= "LOAD_DATE = TO_DATE('".$load_date."', 'MM/DD/YYYY'), ";
		}
		if ($container != "") {
			$sql .= "CONTAINER_ID = '".$container."', ";
		}
		if ($masterbook != "") {
			$sql .= "BOOKING_NUM = '".$masterbook."', ";
		}
		if ($vessel != "") {
			$sql .= "ARRIVAL_NUM = '".$vessel."', ";
		}
		if ($cust != "") {
			$sql .= "CUSTOMER_ID = '".$cust."', ";
		}
		$sql .= "ORDER_COMMENT = '".$user_comments."' WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);

		// ONLY save the bottom if the previous status was one that allows changes
		if ($old_status == 1 || $old_status == 2 || $old_status == 4 || $old_status == 5) {
			// also, verify bottom section before saving
			$msg = validate_details($cust, $order_num, $booking, $width, $dia, $PO, $SSCC, $qty_ship);

			if ($msg != "" && substr($msg, 0, 12) != "Total Weight") {
				echo "<font color=\"#FF0000\">Could Not Save Order:<br>".$msg."<br>Please review what you are trying to save below, and correct the issue.</font>";
				$save_success = "no";
				$message_flag = "bad_save";
			} else {
				if ($msg != "") {
					echo "<font color=\"#CD3608\">".$msg."</font>";
				}

				$sql = "DELETE FROM BOOKING_ORDER_DETAILS WHERE ORDER_NUM = '".$order_num."'";
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);

				for($i = 0; $i < 10; $i++) {

					if ($booking[$i] != "" && $qty_ship[$i] != "" && $qty_ship[$i] != 0) {
						$sql = "INSERT INTO BOOKING_ORDER_DETAILS 
									(ORDER_NUM, BOOKING_NUM, QTY_TO_SHIP, WIDTH, DIA, P_O, SSCC_GRADE_CODE) 
								VALUES 
									('".$order_num."', '".$booking[$i]."', '".$qty_ship[$i]."', '".$width[$i]."', '".$dia[$i]."', '".$PO[$i]."', '".$SSCC[$i]."')";
						ora_parse($short_term_data_cursor, $sql);
						ora_exec($short_term_data_cursor);
					}
				}
			}

			$save_success = "yes";
			$message_flag = "good_save";

			// and set the "most recent" save...
			if ($load_date != "" && $vessel != "") {
				$sql = "DELETE FROM BOOKING_MOST_RECENT";
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);

				$sql = "INSERT INTO BOOKING_MOST_RECENT (LAST_USED, DATE_ENTERED, VESSEL_ENTERED)
							VALUES (SYSDATE, TO_DATE('".$load_date."', 'MM/DD/YYYY'), '".$vessel."')";
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);
			}
		}
	} elseif ($submit == "Get/Refresh Available") {
		$message_flag = "refresh_screen";
	}

	switch($message_flag) {
		case "order_loaded":
		case "good_save":
			$display = "<font size=\"3\" color=\"#0000FF\">This Screen is displaying the currently saved Order Data.</font>";
		break;

		case "bad_save":
			$display = "<font size=\"3\" color=\"#FF0000\">Unsaved Data (Save Attempt Failed)</font>";
		break;

		case "refresh_screen":
			$display = "<font size=\"3\" color=\"#CD3608\">Unsaved Data (Last Action:  Refresh Available)</font>";
		break;

		default:
			$display = "";
		break;
	}

?>

<style>* {font-family: Verdana, sans-serif;}</style>

<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Booking Orders Page
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_create" action="" method="post">
	<tr>
		<td align="left"><input type="submit" name="submit" value="Create New Order"></td>
<?php if ($eport_customer_id != 0) { ?>
		<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>"><td>&nbsp;</td>
<?php } else { ?>
		<td>For Customer:
				<select name="cust">
					<option value="">Please Select</option>
					<option value="314">314</option>
					<option value="338">338</option>
				</select>
		</td>
<?php } ?>
	</tr>
</form>
	<tr>
		<td align="left" colspan="2">&nbsp;<br><b>---OR---</b></td>
	</tr>
<form name="top_retrieve_pending" action="Booking_orders.php" method="post">
	<tr>
		<td align="left" width="10%">Pending Orders:</td>
		<td align="left"><select name="order_num" onchange="document.top_retrieve_pending.submit(this.form)"><option value="">Select an Order:</option>
<?php
	if ($eport_customer_id != 0) {
		$more_sql = " AND CUSTOMER_ID = '".$eport_customer_id."' ";
	} else {
		$more_sql = "";
	}

	$sql = "SELECT ORDER_NUM FROM BOOKING_ORDERS 
			WHERE STATUS NOT IN ('7', '8')
				AND (LOAD_DATE >= SYSDATE - 61 OR LOAD_DATE IS NULL)
				".$more_sql."
			ORDER BY TO_NUMBER(SUBSTR(ORDER_NUM, 3)) DESC";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
								<option value="<?php echo $short_term_row['ORDER_NUM']; ?>"<?php if ($order_num == $short_term_row['ORDER_NUM']) { ?> selected <?php }?>><?php echo $short_term_row['ORDER_NUM']; ?></option>
<?php
	}
?>
					</select></td>
	</tr>
</form>
<form name="top_retrieve_finalized" action="Booking_orders.php" method="post">
	<tr>
		<td align="left" width="10%">Finalized Orders:</td>
		<td align="left"><select name="order_num" onchange="document.top_retrieve_finalized.submit(this.form)"><option value="">Select an Order:</option>
<?php

	$sql = "SELECT ORDER_NUM FROM BOOKING_ORDERS 
			WHERE STATUS IN ('7', '8')
				AND (LOAD_DATE >= SYSDATE - 61 OR LOAD_DATE IS NULL)
				".$more_sql."
			ORDER BY ORDER_NUM DESC";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
								<option value="<?php echo $short_term_row['ORDER_NUM']; ?>"<?php if ($order_num == $short_term_row['ORDER_NUM']) { ?> selected <?php }?>><?php echo $short_term_row['ORDER_NUM']; ?></option>
<?php
	}
?>
					</select></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><br><hr><br></td>
	</tr>
</table>
<?php
	if ($order_num != "") {
		// the next sections only display if an order is chosen

		$sql = "SELECT
					ARRIVAL_NUM,
					STATUS,
					TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD,
					ORDER_COMMENT,
					CONTAINER_ID,
					BOOKING_NUM,
					CUSTOMER_ID 
				FROM BOOKING_ORDERS 
				WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$no_edit_bottom_1 = "";
		$no_edit_bottom_2 = "";
		$no_edit_top = "";
		if ($short_term_row['STATUS'] == 3 || $short_term_row['STATUS'] == 6 || $short_term_row['STATUS'] == 7 || $short_term_row['STATUS'] == 8) {
			$no_edit_bottom_1 = "disabled";
			$no_edit_bottom_2 = "readonly";
		}

		if ($short_term_row['STATUS'] == 3 || $short_term_row['STATUS'] == 7 || $short_term_row['STATUS'] == 8) {
			$no_edit_top_1 = "disabled";
			$no_edit_top_2 = "readonly";
		}

		if ($submit == "Get/Refresh Available" || $save_success == "no") {
			// popuate top data based on what "was" on the screen
			$vessel = $_POST['vessel'];
			$load_date = $_POST['load_date'];
			$container = $_POST['container'];
			$masterbook = $_POST['masterbook'];
			$status = $_POST['status'];
			$user_comments = $_POST['user_comments'];
			$cust = $_POST['cust'];

			$user_comments = str_replace("\"", "", $user_comments);
			$user_comments = str_replace("\\", "", $user_comments);
			$user_comments = str_replace("'", "`", $user_comments);
		} else {
			// populate top data with real info
			$vessel = $short_term_row['ARRIVAL_NUM'];
			$status = $short_term_row['STATUS'];
			$load_date = $short_term_row['THE_LOAD'];
			$container = $short_term_row['CONTAINER_ID'];
			$masterbook = $short_term_row['BOOKING_NUM'];
			$user_comments = $short_term_row['ORDER_COMMENT'];
			$cust = $short_term_row['CUSTOMER_ID'];
		}


		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_print = $short_term_row['VESSEL_NAME'];

		$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$cust_print = $short_term_row['CUSTOMER_NAME'];

		$sql = "SELECT ST_DESCRIPTION FROM DOLEPAPER_STATUSES WHERE STATUS = '".$status."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$status_print = $short_term_row['ST_DESCRIPTION'];

		// if no vessel data is present (I.E. none has been entered for this order yet), then grab whatever
		// data was "most recent".  This is done to save Inventory time... no, that can't backfire at all...
		if ($vessel == "") {
			$sql = "SELECT TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY') THE_DATE, VESSEL_ENTERED
					FROM BOOKING_MOST_RECENT
					WHERE LAST_USED >= (SYSDATE - (1/24))";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			if (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				$load_date = $short_term_row['THE_DATE'];
				$vessel = $short_term_row['VESSEL_ENTERED'];
			}
		}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_info" action="Booking_orders.php" method="post"> <!--  onsubmit="return validate_form()" !-->
<input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
	<tr>
		<td align="left" width="20%">&nbsp;</td>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Current</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b>New</b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Vessel:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $vessel_print; ?></td>
		<td align="left"><select name="vessel" <?php echo $no_edit_top_1; ?>>
<?php
	$sql = "SELECT
				VO.LR_NUM,
				VP.VESSEL_NAME
			FROM
				VOYAGE VO,
				VESSEL_PROFILE VP
			WHERE
				VO.LR_NUM = VP.LR_NUM
				AND VP.SHIP_PREFIX = 'DOLE'
				AND (VO.DATE_DEPARTED IS NULL OR VO.DATE_DEPARTED > SYSDATE)
			ORDER BY VO.LR_NUM";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
								<option value="<?php echo $row['LR_NUM']; ?>"<?php if ($vessel == $row['LR_NUM']) { ?> selected <?php }?>><?php echo $row['VESSEL_NAME']; ?></option>
<?php
	}
?>
							<option value="TRUCKOUT"<?php if ($vessel == "TRUCKOUT") { ?> selected <?php }?>>TRUCKOUT</option>
					</select></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Loading Date:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $load_date; ?></td>
		<td align="left"><input type="text" name="load_date" size="20" maxlength="10" value="<?php echo $load_date; ?>" <?php echo $no_edit_top_2; ?>>&nbsp;&nbsp;<a href="javascript:show_calendar('order_info.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Container:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $container; ?></td>
		<td align="left"><input type="text" name="container" size="20" maxlength="20" value="<?php echo $container; ?>" <?php if ($sub_type != "PORT") { ?> readonly <?php }?>></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Master Booking#:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $masterbook; ?></td>
		<td align="left"><input type="text" name="masterbook" size="20" maxlength="20" value="<?php echo $masterbook; ?>" <?php if ($sub_type != "PORT") { ?> readonly <?php }?>></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Customer:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $cust_print; ?></td>
		<td align="left"><select name="cust" <?php echo $no_edit_top_1; ?>><option value=""> </option>
<?php
	if ($eport_customer_id != 0) {
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE 
				WHERE CUSTOMER_ID IN ('".$eport_customer_id."') ORDER BY CUSTOMER_ID";
	} else {
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE 
				WHERE CUSTOMER_ID IN ('314', '338') ORDER BY CUSTOMER_ID";
	}
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
								<option value="<?php echo $row['CUSTOMER_ID']; ?>"<?php if ($cust == $row['CUSTOMER_ID']) { ?> selected <?php }?>><?php echo $row['CUSTOMER_NAME']; ?></option>
<?php
	}
?>
					</select></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Status:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $status_print; ?></font></td>
		<td align="left">
<?php
			// this next large set of if-thens determiens which statuses show to whom and when for change....-ability.
			if ($status == "1" || $status == "2") {
?>
						<label><input type="radio" name="status" value="1" <?php if ($status == "1") { ?> checked <?php }?>> Draft</label><br>
<?php
			}
			if ($status == "2" || $status == "1") {
?>
						<label><input type="radio" name="status" value="2" <?php if ($status == "2") { ?> checked <?php }?>> Submitted</label><br>
<?php
			}
			if ($status == "3" || $sub_type == "PORT" && ($status == "2" || $status == "5" || $status == "6")) {
?>
						<label><input type="radio" name="status" value="3" <?php if ($status == "3") { ?> checked <?php }?>> Container Loading</label><br>
<?php
			}
			if ($status == "4" || ($sub_type == "PORT" && ($status == "3" || $status == "6"))) {
?>
						<label><input type="radio" name="status" value="4" <?php if ($status == "4") { ?> checked <?php }?>> Loading Stopped</label><br>
<?php
			}
			if ($status == "5" || $status == "4") {
?>
						<label><input type="radio" name="status" value="5" <?php if ($status == "5") { ?> checked <?php }?>> Revision Submitted</label><br>
<?php
			}
			if ($status == "6" || ($sub_type == "PORT" && $status == "3") || ($sub_type == "PORT" && $status == "7")) {
?>
						<label><input type="radio" name="status" value="6" <?php if ($status == "6") { ?> checked <?php }?>> Order Complete</label><br>
<?php
			}
			if ($status == "7" || ($sub_type == "CUST" && $status == "6")) {
?>
						<label><input type="radio" name="status" value="7" <?php if ($status == "7") { ?> checked <?php }?>> Customer Confirmed</label><br>
<?php
			}
			if ($status == "8" || $status == "1" || $status == "2" || $sub_type == "PORT" && ($status == "4" || $status == "5")) {
?>
						<label><input type="radio" name="status" value="8" <?php if ($status == "8") { ?> checked <?php }?>> Cancelled</label><br>
<?php
			}
?>
			</td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><?php echo $user_comments; ?></font></td>
		<td><textarea name="user_comments" cols="50" rows="4"><?php echo $user_comments; ?></textarea></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><?php echo $display; ?></td>
	</tr>
	<tr>
		<td align="left"><input type="submit" name="submit" value="Save Order"></td>
		<td colspan="2" align="right"><input type="submit" name="submit" value="Get/Refresh Available"></td>
	</tr>
	<tr>
		<td colspan="3"><br><hr><br></td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana">Booking</font></td>
		<td><font size="2" face="Verdana">Order#</font></td>
		<td><font size="2" face="Verdana">Width</font></td>
		<td><font size="2" face="Verdana">Dia</font></td>
		<td><font size="2" face="Verdana">Grade Code</font></td>
		<td><font size="2" face="Verdana">Warehouse Code</font></td>
		<td align="right"><font size="2" face="Verdana">Avg Weight (lbs)</font></td>
		<td align="right"><font size="2" face="Verdana">QTY to ship</font></td>
		<td align="right"><font size="2" face="Verdana">Out of</font></td>
		<td align="right"><font size="2" face="Verdana">Rejects</font></td>
<!--		<td>&nbsp;</td>
		<td width="20%">&nbsp;</td> !-->
	</tr>
<?php

		$total_weight = 0;
		$total_rolls = 0;
		$i = 0;  // we will be going up to 10 lines

		if ($submit == "Get/Refresh Available" || $save_success == "no") {
			/***
			*
			*	this is called to refresh the screen to update availability.  It is a copy of the choices from the previous screen.
			*******************************************************************************************/
			$booking = $_POST['booking'];
			$PO = $_POST['PO'];
			$width = $_POST['width'];
			$dia = $_POST['dia'];
			$qty_ship = $_POST['qty_ship'];

			while ($booking[$i] != "") {
?>
	<tr>
		<td><select name="booking[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option> <!-- id="dockticket<?php echo $i; ?>" onchange="SetData(<?php echo $i; ?>)"  !-->
		<?php populate_bookings($cust, $order_num, $booking[$i]); ?>
				</select></td>

		<td><select name="PO[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_POs($cust, $order_num, $PO[$i]); ?>
				</select></td>

		<td><select name="width[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_width($cust, $order_num, $width[$i]); ?>
				</select></td>

		<td><select name="dia[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_dia($cust, $order_num, $dia[$i]); ?>
				</select></td>

		<td><select name="SSCC[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option> 
		<?php populate_SSCC($cust, $order_num, $SSCC[$i]); ?>
				</select></td>
		
		<td><?php echo getWarehouseCode($SSCC[$i], $booking[$i], $width[$i] * 10); ?></td>

		<td align="right"><font face="Verdana" size="2"><?php $temp = get_avg_weight($cust, $booking[$i], $PO[$i], $width[$i], $dia[$i], $SSCC[$i]); 
														$total_weight += ($qty_ship[$i] * $temp);
														echo $temp; ?></font></td>

		<td align="right"><input type="text" size="5" maxlength="2" name="qty_ship[<?php echo $i; ?>]" value="<?php $temp = (0 + $qty_ship[$i]);
																												$total_rolls += $temp;
																												echo $temp; ?>" <?php echo $no_edit_bottom_2; ?>></td> 

		<td align="right"><font face="Verdana" size="2"><a href="OrderSubCalc.php?cust=<?php echo $cust; ?>&booking=<?php echo $booking[$i]; ?>&PO=<?php echo $PO[$i]; ?>&width=<?php echo $width[$i]; ?>&dia=<?php echo $dia[$i]; ?>&SSCC=<?php echo $SSCC[$i]; ?>&order=<?php echo $order_num; ?>" target="OrderSubCalc.php?cust=<?php echo $cust; ?>&booking=<?php echo $booking[$i]; ?>&PO=<?php echo $PO[$i]; ?>&width=<?php echo $width[$i]; ?>&dia=<?php echo $dia[$i]; ?>&SSCC=<?php echo $SSCC[$i]; ?>&order=<?php echo $order_num; ?>"><?php echo display_available($cust, $order_num, $booking[$i], $PO[$i], $width[$i], $dia[$i], $SSCC[$i], $junk); ?></a></font></td>

		<td align="right"><font face="Verdana" size="2"><?php echo display_rej($cust, $order_num, $booking[$i], $PO[$i], $width[$i], $dia[$i], $SSCC[$i]); ?></font></td>
	</tr>
<?php
				$i++;
			}
		} else {
			/***
			*
			*	this is called to populate the bottom with CURRENT order info.  I.E., not an availability-refresh.
			*******************************************************************************************/

			$sql = "SELECT P_O, BOOKING_NUM, WIDTH, DIA, QTY_TO_SHIP, SSCC_GRADE_CODE FROM BOOKING_ORDER_DETAILS WHERE ORDER_NUM = '".$order_num."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) { // this part for existing data
?>

	<tr>
		<td><select name="booking[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option> <!-- id="dockticket<?php echo $i; ?>" onchange="SetData(<?php echo $i; ?>)"  !-->
		<?php populate_bookings($cust, $order_num, $row['BOOKING_NUM']); ?>
				</select></td>

		<td><select name="PO[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_POs($cust, $order_num, $row['P_O']); ?>
				</select></td>

		<td><select name="width[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_width($cust, $order_num, $row['WIDTH']); ?>
				</select></td>

		<td><select name="dia[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_dia($cust, $order_num, $row['DIA']); ?>
				</select></td>

		<td><select name="SSCC[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option> 
		<?php populate_SSCC($cust, $order_num, $row['SSCC_GRADE_CODE']); ?>
				</select></td>
		
		<td><?php echo getWarehouseCode($row['SSCC_GRADE_CODE'], $row['BOOKING_NUM'], $row['WIDTH'] * 10); ?></td>
		
		<td align="right"><font face="Verdana" size="2"><?php  $temp = get_avg_weight($cust, $row['BOOKING_NUM'], $row['P_O'], $row['WIDTH'], $row['DIA'], $row['SSCC_GRADE_CODE']);
															$total_weight += ($row['QTY_TO_SHIP'] * $temp);
															echo $temp; ?></font></td>

		<td align="right"><input type="text" size="5" maxlength="2" name="qty_ship[<?php echo $i; ?>]" value="<?php $temp = (0 + $row['QTY_TO_SHIP']);
																												$total_rolls += $temp;
																												echo $temp; ?>" <?php echo $no_edit_bottom_2; ?>></td> 

		<td align="right"><font face="Verdana" size="2"><a href="OrderSubCalc.php?cust=<?php echo $cust; ?>&booking=<?php echo $row['BOOKING_NUM']; ?>&PO=<?php echo $row['P_O']; ?>&width=<?php echo $row['WIDTH']; ?>&dia=<?php echo $row['DIA']; ?>&SSCC=<?php echo $row['SSCC_GRADE_CODE']; ?>&order=<?php echo $order_num; ?>" target="OrderSubCalc.php?cust=<?php echo $cust; ?>&booking=<?php echo $row['BOOKING_NUM']; ?>&PO=<?php echo $row['P_O']; ?>&width=<?php echo $row['WIDTH']; ?>&dia=<?php echo $row['DIA']; ?>&SSCC=<?php echo $row['SSCC_GRADE_CODE']; ?>&order=<?php echo $order_num; ?>"><?php echo display_available($cust, $order_num, $row['BOOKING_NUM'], $row['P_O'], $row['WIDTH'], $row['DIA'], $row['SSCC_GRADE_CODE'], $junk); ?></font></td>

		<td align="right"><font face="Verdana" size="2"><?php echo display_rej($cust, $order_num, $row['BOOKING_NUM'], $row['P_O'], $row['WIDTH'], $row['DIA'], $row['SSCC']); ?></font></td>
	</tr>
<?php
				$i++;
			}
		}
		for(; $i < 10; $i++) { // put the rest of the 10 lines....
?>
	<tr>
		<td><select name="booking[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option> <!-- id="dockticket<?php echo $i; ?>" onchange="SetData(<?php echo $i; ?>)"  !-->
		<?php populate_bookings($cust, $order_num, ""); ?>
				</select></td>

		<td><select name="PO[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_POs($cust, $order_num, ""); ?>
				</select></td>

		<td><select name="width[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_width($cust, $order_num, ""); ?>
				</select></td>

		<td><select name="dia[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option>
		<?php populate_dia($cust, $order_num, ""); ?>
				</select></td>

		<td><select name="SSCC[<?php echo $i;?>]" <?php echo $no_edit_bottom_1; ?>><option value=""></option> 
		<?php populate_SSCC($cust, $order_num, ""); ?>
				</select></td>

		<td align="right"><font face="Verdana" size="2"></font></td>

		<td align="right"><input type="text" size="5" maxlength="2" name="qty_ship[<?php echo $i; ?>]" value="" <?php echo $no_edit_bottom_2; ?>></td> 

		<td align="right"><font face="Verdana" size="2"></font></td>

		<td align="right"><font face="Verdana" size="2"></font></td>
	</tr>
<?php
		}
		if ($total_rolls > 0) {
?>
	<tr>
		<td colspan="9">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $display; ?></td>
		<td align="right"><font face="Verdana" size="2"><b>Totals:</b></font></td>
		<td align="right"><font face="Verdana" size="2"><?php echo $total_weight; ?></font></td>
		<td align="right"><font face="Verdana" size="2"><?php echo $total_rolls; ?></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?php
		}
?>
	<tr>
		<td colspan="5" align="left"><input type="submit" name="submit" value="Save Order"></td>
		<td colspan="4" align="right"><input type="submit" name="submit" value="Get/Refresh Available"></td>
	</tr>
</table>
</form>
<?php
	}
	
?>


<?php

function getWarehouseCode($gradeCode, $bookingNum, $widthMm)
{
	global $conn;
	$cursor = ora_open($conn);
	
	$sql = "SELECT warehouse_code
			FROM booking_warehouse_code
			WHERE
				booking_num = '$bookingNum'
				AND width = '$widthMm'
				AND sscc_grade_code = '$gradeCode'";
	
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$whCode = $row['WAREHOUSE_CODE'];
	
	if ($whCode) {
		return $whCode;
	}
	else return 'N/A';
}

function populate_SSCC($cust, $order_num, $selected)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
//	$return = "";

	$sql = "SELECT DISTINCT SSCC_GRADE_CODE FROM
				((SELECT DISTINCT SSCC_GRADE_CODE 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC
					WHERE REMARK = 'BOOKINGSYSTEM'
						AND QTY_IN_HOUSE > 0
						AND DATE_RECEIVED IS NOT NULL
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CT.RECEIVER_ID = '".$cust."'
						AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM)
				UNION
				(SELECT DISTINCT SSCC_GRADE_CODE FROM BOOKING_ORDER_DETAILS BOD
					WHERE ORDER_NUM = '".$order_num."'))
			ORDER BY SSCC_GRADE_CODE";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if ($short_term_row['SSCC_GRADE_CODE'] == $selected) {
			$select_option = "selected";
		} else {
			$select_option = "";
		}

		echo "<option value=\"".$short_term_row['SSCC_GRADE_CODE']."\" ".$select_option.">".$short_term_row['SSCC_GRADE_CODE']."</option>";
	}

//	return $return;
}

function populate_bookings($cust, $order_num, $selected)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
//	$return = "";

	$sql = "SELECT DISTINCT BOOKING_NUM FROM
				((SELECT DISTINCT BOOKING_NUM FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM)
				UNION
				(SELECT DISTINCT BOOKING_NUM FROM BOOKING_ORDER_DETAILS BOD
					WHERE ORDER_NUM = '".$order_num."'))
			ORDER BY BOOKING_NUM";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if ($short_term_row['BOOKING_NUM'] == $selected) {
			$select_option = "selected";
		} else {
			$select_option = "";
		}

		echo "<option value=\"".$short_term_row['BOOKING_NUM']."\" ".$select_option.">".$short_term_row['BOOKING_NUM']."</option>";
	}

//	return $return;
}

function populate_POs($cust, $order_num, $selected)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
//	$return = "";

	$sql = "SELECT DISTINCT PO FROM
				((SELECT DISTINCT ORDER_NUM PO FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM)
				UNION
				(SELECT DISTINCT P_O PO FROM BOOKING_ORDER_DETAILS BOD
					WHERE ORDER_NUM = '".$order_num."'))
			ORDER BY PO";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if ($short_term_row['PO'] == $selected) {
			$select_option = "selected";
		} else {
			$select_option = "";
		}

		echo "<option value=\"".$short_term_row['PO']."\" ".$select_option.">".$short_term_row['PO']."</option>";
	}

//	return $return;
}

function populate_width($cust, $order_num, $selected)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
//	$return = "";

	$sql = "SELECT DISTINCT WIDTH FROM
				((SELECT DISTINCT ROUND(WIDTH * CONVERSION_FACTOR, 1) WIDTH 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND BAD.WIDTH_MEAS = UC.PRIMARY_UOM
					AND UC.SECONDARY_UOM = 'CM')
				UNION
				(SELECT DISTINCT WIDTH FROM BOOKING_ORDER_DETAILS BOD
					WHERE ORDER_NUM = '".$order_num."'))
			ORDER BY WIDTH";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if ($short_term_row['WIDTH'] == $selected) {
			$select_option = "selected";
		} else {
			$select_option = "";
		}

		$width_inches = round($short_term_row['WIDTH'] / 2.54, 1);

		echo "<option value=\"".$short_term_row['WIDTH']."\" ".$select_option.">".$short_term_row['WIDTH']."cm / ".$width_inches."\"</option>";
	}

//	return $return;
}

function populate_dia($cust, $order_num, $selected)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
//	$return = "";

	$sql = "SELECT DISTINCT DIA FROM
				((SELECT DISTINCT ROUND(DIAMETER * CONVERSION_FACTOR, 1) DIA 
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND BAD.DIAMETER_MEAS = UC.PRIMARY_UOM
					AND UC.SECONDARY_UOM = 'CM')
				UNION
				(SELECT DISTINCT DIA FROM BOOKING_ORDER_DETAILS BOD
					WHERE ORDER_NUM = '".$order_num."'))
			ORDER BY DIA";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while (ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if ($short_term_row['DIA'] == $selected) {
			$select_option = "selected";
		} else {
			$select_option = "";
		}

		$dia_inches = round($short_term_row['DIA'] / 2.54, 1);

		echo "<option value=\"".$short_term_row['DIA']."\" ".$select_option.">".$short_term_row['DIA']."cm / ".$dia_inches."\"</option>";
	}

//	return $return;
}

function get_avg_weight($cust, $booking, $PO, $width, $dia, $SSCC)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
	$return = "";

	if ($booking == "" || $PO == "" || $width == "" || $dia == "") {
		return "";
	}

	$sql = "SELECT ROUND(AVG(WEIGHT * UC3.CONVERSION_FACTOR)) THE_WEIGHT 
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC, 
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3
			WHERE REMARK = 'BOOKINGSYSTEM'
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '".$cust."'
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
				AND WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'
				AND BAD.BOOKING_NUM = '".$booking."'
				AND BAD.ORDER_NUM = '".$PO."'
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'";
//	echo $sql;
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	return $short_term_row['THE_WEIGHT'];
}

function display_available($cust, $order_num, $booking, $PO, $width, $dia, $SSCC, &$return_on_order)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
	$return = "";

	// AND BAD.WIDTH = '".$width."' 					AND BAD.DIAMETER = '".$dia."'";

	$sql = "SELECT SUM(QTY_IN_HOUSE) IN_HOUSE FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
						UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
					AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) NOT IN
						(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
						WHERE BD.DAMAGE_TYPE = PDC.DAMAGE_CODE
						AND PDC.REJECT_LEVEL = 'REJECT'
						AND BD.DATE_CLEARED IS NULL)
					AND BAD.BOOKING_NUM = '".$booking."'
					AND BAD.ORDER_NUM = '".$PO."'	
					AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$in_house = $short_term_row['IN_HOUSE'];

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
			FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
			WHERE SERVICE_CODE = '6'
				AND CA.PALLET_ID = BAD.PALLET_ID
				AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND CA.CUSTOMER_ID = '".$cust."'
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
				AND BAD.BOOKING_NUM = '".$booking."'
				AND BAD.ORDER_NUM = '".$PO."'	
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'
			AND ACTIVITY_DESCRIPTION IS NULL
			AND CA.ORDER_NUM = '".$order_num."'";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$already_scanned_this_order = $short_term_row['THE_SUM'];
	$return_on_order = $already_scanned_this_order;

	$sql = "SELECT SUM(QTY_TO_SHIP) THE_SUM FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD
			WHERE BO.ORDER_NUM = BOD.ORDER_NUM
				AND BO.CUSTOMER_ID = '".$cust."'
				AND BO.STATUS NOT IN ('6', '7', '8')
				AND BO.ORDER_NUM != '".$order_num."'
				AND BOD.P_O = '".$PO."'
				AND BOD.SSCC_GRADE_CODE = '".$SSCC."'
				AND BOD.BOOKING_NUM = '".$booking."'
				AND BOD.DIA = '".$dia."'
				AND BOD.WIDTH = '".$width."'";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$reserved_to_other_orders = $short_term_row['THE_SUM'];

	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
            FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
				UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
            WHERE CA.SERVICE_CODE = '6'
				AND CA.PALLET_ID = CT.PALLET_ID
				AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.RECEIVER_ID = '".$cust."'
				AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
				AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
				AND BAD.BOOKING_NUM = '".$booking."'
				AND BAD.ORDER_NUM = '".$order_num."'    
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'
				AND CA.ACTIVITY_DESCRIPTION IS NULL 
				AND CA.ORDER_NUM IN 
				(SELECT BO.ORDER_NUM FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD
					WHERE BO.ORDER_NUM = BOD.ORDER_NUM
						AND BO.CUSTOMER_ID = '".$cust."'
						AND BO.STATUS NOT IN ('6', '7', '8')
						AND BO.ORDER_NUM != '".$order_num."'
						AND BOD.P_O = '".$PO."'
						AND BOD.SSCC_GRADE_CODE = '".$SSCC."'
						AND BOD.BOOKING_NUM = '".$booking."'
						AND BOD.DIA = '".$dia."'
						AND BOD.WIDTH = '".$width."'
				)";
//	echo $sql."<br>";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$already_scanned_on_other_orders = $short_term_row['THE_SUM'];

	$return = $in_house + $already_scanned_this_order - ($reserved_to_other_orders - $already_scanned_on_other_orders);

	return $return;
}

function display_rej($cust, $order_num, $booking, $PO, $width, $dia)
{
	global $conn;
	$short_term_data_cursor = ora_open($conn);
	$return = "";

	$sql = "SELECT SUM(QTY_IN_HOUSE) IN_HOUSE FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC,
						UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
					WHERE REMARK = 'BOOKINGSYSTEM'
					AND QTY_IN_HOUSE > 0
					AND DATE_RECEIVED IS NOT NULL
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
					AND BPGC.SSCC_GRADE_CODE = '".$SSCC."'
					AND (CT.PALLET_ID, CT.RECEIVER_ID, CT.ARRIVAL_NUM) IN
						(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
						WHERE BD.DAMAGE_TYPE = PDC.DAMAGE_CODE
						AND PDC.REJECT_LEVEL = 'REJECT'
						AND BD.DATE_CLEARED IS NULL)
					AND BAD.BOOKING_NUM = '".$booking."'
					AND BAD.ORDER_NUM = '".$PO."'
					AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) = '".$width."'
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' AND ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) = '".$dia."'";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$return = (0 + $short_term_row['IN_HOUSE']);

	return $return; 
}

function validate_details($cust, $order_num, $booking, $width, $dia, $PO, $SSCC, $qty_ship)
{
	// eachof the passed items is an array of size 10 (though any given index might be empty)

	$return = "";
	$total_weight = 0;

	for($i = 0; $i < 10; $i++) {
		// for each "line"...

		if ($qty_ship[$i] != "" && $qty_ship[$i] != 0 && is_numeric($qty_ship[$i])) {						//if ($booking[$i] != "") {
			// only do this for an entered-quantity line
			if ($width[$i] == "" || $dia[$i] == "" || $PO[$i] == "" || $booking[$i] == "") {
				$return .= "Line ".($i + 1).": Missing Values<br>";
			} else {
				// all data was filled, make sure quantity "passes inspection"
				$on_order_already = "";
				$total_weight += ($qty_ship[$i] * get_avg_weight($cust, $booking[$i], $PO[$i], $width[$i], $dia[$i], $SSCC[$i]));
				$available = display_available($cust, $order_num, $booking[$i], $PO[$i], $width[$i], $dia[$i], $SSCC[$i], $on_order_already);

				if ($qty_ship[$i] > $available) {
					$return .= "Line ".($i + 1).": Attempted to ship more (".$qty_ship[$i].") than is available (".$available.")<br>";
				} elseif ($qty_ship[$i] < $on_order_already) {
					$return .= "Line ".($i + 1).": The order quantity (".$qty_ship[$i].") cannot be less than the quantity already scanned onto the container  (".$on_order_already.").  If you need to reduce the order quantity, you must void one or more rolls first.<br>";
				}
			}
		}
	}

	if ($total_weight > 46000 && $return == "") {
		$return = "Total Weight is ".$total_weight."lbs, orders should normally not exceed 46000lbs.  Order has been saved, but please review order carefully.";
	}

	return $return;
}
