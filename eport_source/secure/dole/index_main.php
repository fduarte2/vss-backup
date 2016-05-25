<?
/*
*		Adam Walter, Sep/Oct 2008
*
*		MAIN SCREEN of the Dole paper system.
*
*****************************************************************/

	$cursor = ora_open($conn);
	$order_cursor = ora_open($conn);
	$dockticket_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);


	// initializations
	$GT_loaded = 0;
	$GT_DMG = 0;
	$GT_QTY_TO_SHIP = 0;
	$GT_DMG_loaded = 0;

	$ORDER_loaded = 0;
	$ORDER_DMG = 0;
	$ORDER_QTY_TO_SHIP = 0;
	$DMG_loaded = 0;

	$mainbox_colspan = 6;

	$vessel = $HTTP_POST_VARS['vessel'];
	$destination = $HTTP_POST_VARS['destination'];
	$load_date = $HTTP_POST_VARS['load_date'];
	$order_num = $HTTP_POST_VARS['order_num'];
	$action = $HTTP_POST_VARS['action'];
	$show_status = $HTTP_POST_VARS['show_status'];

	if($vessel == ""){
		$vessel = $HTTP_GET_VARS['vessel'];
	}
	if($destination == ""){
		$destination = $HTTP_GET_VARS['destination'];
	}
	if($load_date == ""){
		$load_date = $HTTP_GET_VARS['load_date'];
	}
	if($order_num == ""){
		$order_num = $HTTP_GET_VARS['order_num'];
	}
	if($action == ""){
		$action = $HTTP_GET_VARS['action'];
	}

	if($load_date != ""){
		$load_date = urldecode($load_date);
		$url_safe_load_date = str_replace("/", "%2F", $load_date);
	}


	if($action == "submit_order"){
		// used by both the one-stop-shop link in this page, and by a redirect from INV's submission page to here.
		$special_note = $HTTP_POST_VARS['special_note'];

		$yes_or_no = $HTTP_POST_VARS['yes_or_no'];

		if(($yes_or_no == "Submit Order" && $special_note != "") || $yes_or_no == ""){

			$sql = "SELECT STATUS FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($row['STATUS'] == "1"){
				$new_stat = "2";
			} elseif($row['STATUS'] == "4"){
				$new_stat = "5";
			}

			$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '".$new_stat."'"; 
			if($special_note != ""){
				$sql .= ", SPECIAL_EXCEPTION = SPECIAL_EXCEPTION\r\n || ' --- ".$special_note."'";
			}
			$sql .= " WHERE ORDER_NUM = '".$order_num."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}

		$sql = "SELECT ARRIVAL_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE, DESTINATION_NB FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel = $row['ARRIVAL_NUM'];
		$load_date = $row['THE_DATE'];
		$destination = $row['DESTINATION_NB'];
	}

	if($action == "start_load"){
		// used by PORT to start loading an order.

		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '3' WHERE ORDER_NUM = '".$order_num."'"; 
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "SELECT ARRIVAL_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE, DESTINATION_NB FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel = $row['ARRIVAL_NUM'];
		$load_date = $row['THE_DATE'];
		$destination = $row['DESTINATION_NB'];
	}

	if($action == "stop_load"){
		// used by PORT to stop loading an order.

		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '4' WHERE ORDER_NUM = '".$order_num."'"; 
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "SELECT ARRIVAL_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE, DESTINATION_NB FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel = $row['ARRIVAL_NUM'];
		$load_date = $row['THE_DATE'];
		$destination = $row['DESTINATION_NB'];
	}

	if($action == "load_all"){
		// used by PORT to start loading all orders for a given set.

		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '3' WHERE ARRIVAL_NUM = '".$vessel."' AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."' AND DESTINATION_NB = '".$destination."' AND (STATUS = '2' OR STATUS = '5')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

	}

	if($action == "submit_all"){
		// used by PORT and CUST to start loading all orders for a given set.

		$special_note = $HTTP_POST_VARS['special_note'];
		$yes_or_no = $HTTP_POST_VARS['yes_or_no'];

		if($yes_or_no == "Yes, Submit All Orders"){

			if($sub_type == "PORT" && $special_note == ""){
				echo "<font color=\"#FF0000\">Could not submit all orders; Port employees must enter a reason</font>";
			} else {

			$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = DECODE(STATUS, 1, 2, 5)";
			if($sub_type == "PORT"){
				$sql .= ", SPECIAL_EXCEPTION = SPECIAL_EXCEPTION\r\n || ' --- ".$special_note."' ";
			}
			$sql .= "WHERE ARRIVAL_NUM = '".$vessel."' AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."' AND DESTINATION_NB = '".$destination."' AND (STATUS = '1' OR STATUS = '4')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			}
		}
	}
/*
	if($action == "confirm_all"){
		// used by CUST to cofirm all (displayed) completed orders
		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '7' WHERE ARRIVAL_NUM = '".$vessel."' AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."' AND DESTINATION_NB = '".$destination."' AND STATUS = '6'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

	}
*/

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Order Review
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="2" cellspacing="0">
<form name="sel_vessel" action="index.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td align="left"><select name="vessel" onchange="document.sel_vessel.submit(this.form)">
<?
	$sql = "SELECT VP.LR_NUM, VP.VESSEL_NAME FROM VESSEL_PROFILE VP, VOYAGE VY WHERE VP.LR_NUM = VY.LR_NUM AND VP.SHIP_PREFIX = 'DOLE' AND (VY.DATE_DEPARTED IS NULL OR VY.DATE_DEPARTED > SYSDATE) ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="">No Dole-Outgoing vessels registered at PoW</option>
<?
	} else {
?>
					<option value="">Select a Vessel</option>
<?
		do {
?>
					<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){?> selected <?}?>><? echo $row['VESSEL_NAME']; ?></option>
<?
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>
				</select></td>
	</tr>
</form>
<?
	if($vessel != ""){		// everything below here only if vessel is chosen
		$sql = "SELECT * FROM DOLEPAPER_ORDER WHERE ARRIVAL_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="2" align="left">No Orders yet placed for this vessel.  Please <a href="order.php?vessel=<? echo $vessel; ?>">Click Here</a> To add an order.</td>
	</tr>
<?
		} else {
?>
<form name="sel_everything" action="index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td colspan="2"><a href="order_entry.php?vessel=<? echo $vessel; ?>">New Date/Destination</a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Destination:</b></font></td>		
		<td align="left"><select name="destination"><option value="">Select a Destination</option>
<?
			$sql = "SELECT DISTINCT DD.DESTINATION, DD.DESTINATION_NB FROM DOLEPAPER_DESTINATIONS DD, DOLEPAPER_ORDER DO WHERE DD.DESTINATION_NB = DO.DESTINATION_NB AND DO.ARRIVAL_NUM = '".$vessel."' ORDER BY DESTINATION";
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
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Loading Date:</b></font></td>		
		<td align="left"><select name="load_date"><option value="">Select a Loading Date</option>
<?
			$sql = "SELECT DISTINCT TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE FROM DOLEPAPER_ORDER WHERE ARRIVAL_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
									<option value="<? echo $row['THE_DATE']; ?>"<? if($row['THE_DATE'] == $load_date){?> selected <?}?>><? echo $row['THE_DATE']; ?></option>
<?
			}
?>
							</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Status:</b></font></td>		
		<td align="left"><select name="show_status">
			<option value="ALL (no Cancelled)" <? if($show_status == "ALL (no Cancelled)"){?> selected <?}?>>ALL (no Cancelled)</option>
			<option value="ALL" <? if($show_status == "ALL"){?> selected <?}?>>ALL</option>
			<option value="Draft" <? if($show_status == "Draft"){?> selected <?}?>>Draft</option>
			<option value="Submitted" <? if($show_status == "Submitted"){?> selected <?}?>>Submitted</option>
			<option value="Loading" <? if($show_status == "Loading"){?> selected <?}?>>Loading</option>
			<option value="Halted" <? if($show_status == "Halted"){?> selected <?}?>>Halted</option>
			<option value="Complete" <? if($show_status == "Complete"){?> selected <?}?>>Complete</option>
			<option value="Confirmed" <? if($show_status == "Confirmed"){?> selected <?}?>>Confirmed</option>
			<option value="Cancelled" <? if($show_status == "Cancelled"){?> selected <?}?>>Cancelled</option>
							</select></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Order Info"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>

<table border="0" width="100%" cellpadding="2" cellspacing="0">
<?
		}

		if($destination != "" && $load_date != ""){	// in addition to vessel check above, only do the below if destination and date chosen
			$sql = "SELECT TO_CHAR(SAIL_DATE, 'MM/DD/YYYY') THE_SAIL FROM DOLEPAPER_ORDER WHERE ARRIVAL_NUM = '".$vessel."' AND DESTINATION_NB = '".$destination."' AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."'";
//			echo $sql;
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$sail_date = $row['THE_SAIL'];
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$vessel_name = $row['VESSEL_NAME'];
			$sql = "SELECT DESTINATION FROM DOLEPAPER_DESTINATIONS WHERE DESTINATION_NB = '".$destination."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$destination_name = $row['DESTINATION'];
?>
	<tr>
		<td align="left"><font size="3" face="Verdana"><b>Vessel:  <? echo $vessel_name; ?></b></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana"><b>Destination:  <? echo $destination_name; ?></b></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana"><b>Loading Date:  <? echo $load_date; ?></b></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana"><b>Sail Date:  <? echo $sail_date; ?></b></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana"><b>Displayed Status:  <? echo $show_status; ?></b></font></td>
	</tr>
	<tr>
		<td>&nbsp;<hr>&nbsp;</td>
	</tr>
<!--	<tr>
		<td align="left">
			<form name="new_order" action="order.php" method="post">
				<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
				<input type="hidden" name="destination" value="<? echo $destination; ?>">
				<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
				<input type="submit" name="submit" value="Add Order to List">
			</form>
		</td>
	</tr> !-->
<?
			if($show_status == "ALL" || $show_status == "Draft" || $show_status == "ALL (no Cancelled)"){
?>
	<tr>
		<td align="left">
			<form name="submit_all" action="submit_all.php" method="post">
				<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
				<input type="hidden" name="destination" value="<? echo $destination; ?>">
				<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
				<input type="submit" name="submit" value="Submit All Displayed Orders">
			</form>
		</td>
	</tr>
<?
			}
			if($sub_type == "PORT" && ($show_status == "ALL" || $show_status == "Submitted" || $show_status == "ALL (no Cancelled)")){
?>
	<tr>
		<td align="left">
			<form name="load_all" action="index.php" method="post">
				<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
				<input type="hidden" name="destination" value="<? echo $destination; ?>">
				<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
				<input type="hidden" name="action" value="load_all">
				<input type="submit" name="submit" value="Start Loading All Submitted Orders">
			</form>
		</td>
	</tr>
<?
			}
?>
	<tr>
		<td>
		<table border="0" style=" border: 1px solid black;" width="100%" cellpadding="2" cellspacing="0">
<?
			$sql = "SELECT DO.ORDER_NUM, DS.STATUS, DS.ST_DESCRIPTION, DO.CONTAINER_ID FROM DOLEPAPER_ORDER DO, DOLEPAPER_STATUSES DS WHERE DO.ARRIVAL_NUM = '".$vessel."' AND DO.DESTINATION_NB = '".$destination."' AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."' AND DO.STATUS = DS.STATUS";
			$sql = $sql . getStatusSQLAddon($show_status);
			ora_parse($order_cursor, $sql);
			ora_exec($order_cursor);
			if(!ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td align="center">This Vessel/Destination/Loading Date/Status combination has no orders</td>
			</tr>
<?
			} else {
?>
			<tr>
				<td align="left"><font size="2" face="Verdana"><b>Order#</b></font></td>
				<td align="left"><font size="2" face="Verdana"><b>Order Status</b></font></td>
				<td align="left"><font size="2" face="Verdana"><b>Container ID</b></font></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
<?
				do {	// this is the outer loop for orders in the Main Dole Page.
					$ORDER_loaded = 0;
					$ORDER_DMG = 0;
					$ORDER_QTY_TO_SHIP = 0;
					$DMG_loaded = 0;
?>
			<tr>
				<a name="<? echo $order_row['ORDER_NUM']; ?>"></a>
				<td align="left"><input type="textbox" style="background-color: #F38787;" size="30" readonly="readonly" value="<? echo $order_row['ORDER_NUM']; ?>"></td>
				<td align="left"><input type="textbox" style="background-color: #F38787;" size="30" readonly="readonly" value="<? echo $order_row['ST_DESCRIPTION']; ?>"></td>
				<td align="left"><input type="textbox" style="background-color: #F38787;" size="30" readonly="readonly" value="<? echo $order_row['CONTAINER_ID']; ?>"></td>
				<td align="center">
<?
				// this if-block displays the "1-stop shopping" link, (login and status dependant)
				if(($order_row['STATUS'] == "1" || $order_row['STATUS'] == "4") && $sub_type == "CUST"){
?>
									<a href="index.php?action=submit_order&order_num=<? echo $order_row['ORDER_NUM']; ?>">Submit</a>
<?
				} elseif(($order_row['STATUS'] == "1" || $order_row['STATUS'] == "4") && $sub_type == "PORT")  {
?>
									<a href="submit_exception.php?order_num=<? echo $order_row['ORDER_NUM']; ?>">Submit</a>
<?
				} elseif($order_row['STATUS'] == "3" && $sub_type == "PORT") {
?>
									<a href="index.php?action=stop_load&order_num=<? echo $order_row['ORDER_NUM']; ?>">Stop Loading</a>
<?
				} elseif(($order_row['STATUS'] == "2" || $order_row['STATUS'] == "5") && $sub_type == "PORT") {
?>
									<a href="index.php?action=start_load&order_num=<? echo $order_row['ORDER_NUM']; ?>">Start Loading</a>
<?
				} else {
?>
									&nbsp;
<?
				}
?>
									</td>
				<td align="center"><a href="order_mod.php?order_num=<? echo $order_row['ORDER_NUM']; ?>">Order Details</a></td>
				<td align="center">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><font size="2" face="Verdana"><b>Dock Ticket</b></font></td>
				<td align="left"><font size="2" face="Verdana"><b>Rolls Ordered</b></font></td>
				<td align="left"><font size="2" face="Verdana"><b>DMG Rolls Orderd</b></font></td>
				<td align="left"><font size="2" face="Verdana"><b>Rolls Loaded/DMG</b></font></td>
				<td align="left">&nbsp;</td>
			</tr>
<?
					$sql = "SELECT DOCK_TICKET, QTY_SHIP, QTY_DMG_SHIP FROM DOLEPAPER_DOCKTICKET WHERE ORDER_NUM = '".$order_row['ORDER_NUM']."' ORDER BY DOCK_TICKET";
					ora_parse($dockticket_cursor, $sql);
					ora_exec($dockticket_cursor);
					if(!ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="<? echo $mainbox_colspan; ?>" align="center"><font size="3" face="Verdana"><b>---No Dock Tickets for <? echo $order_row['ORDER_NUM']; ?>---</b></font></td>
			</tr>
<?
					} else {		// this is the inner loop for dock tickets per order
						do {
							$good_loaded = 0;
							$dmg_loaded = 0;
							if($bgcolor == "#D4D48F"){
								$bgcolor = "#F0F0F0";
							} else {
								$bgcolor = "#D4D48F";
							}
							$sql = "SELECT QTY_DAMAGED, ACTIVITY_DESCRIPTION FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.RECEIVER_ID = CA.CUSTOMER_ID AND CA.ORDER_NUM = '".$order_row['ORDER_NUM']."' AND CT.BOL = '".$dockticket_row['DOCK_TICKET']."' AND SERVICE_CODE = '6' AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')";
//							echo $sql;
							ora_parse($short_term_data_cursor, $sql);
							ora_exec($short_term_data_cursor);
							while(ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
								$good_loaded++;
								if($short_term_data_row['QTY_DAMAGED'] != 0){
									$dmg_loaded++;
								}
							}
?>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="text" size="30" style="background-color: <? echo $bgcolor; ?>;" readonly="readonly" value="<? echo $dockticket_row['DOCK_TICKET']; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: <? echo $bgcolor; ?>;" readonly="readonly" value="<? echo $dockticket_row['QTY_SHIP']; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: <? echo $bgcolor; ?>;" readonly="readonly" value="<? echo $dockticket_row['QTY_DMG_SHIP']; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: <? echo $bgcolor; ?>;" readonly="readonly" value="<? echo $good_loaded." / ".$dmg_loaded; ?>"></td>
				<td align="left">&nbsp;</font></td>
			</tr>
<?
							$ORDER_loaded += $good_loaded;
							$ORDER_DMG += $dockticket_row['QTY_DMG_SHIP'];
							$ORDER_QTY_TO_SHIP += $dockticket_row['QTY_SHIP'];
							$DMG_loaded += $dmg_loaded;

							$GT_loaded += $good_loaded;
							$GT_DMG += $dockticket_row['QTY_DMG_SHIP'];
							$GT_QTY_TO_SHIP += $dockticket_row['QTY_SHIP'];
							$GT_DMG_loaded += $dmg_loaded;
							
						} while(ora_fetch_into($dockticket_cursor, $dockticket_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
			<tr>
				<td>&nbsp;</td>
				<td align="center"><font size="2" face="Verdana"><b>Order Totals:</b></font></td>
				<td align="left"><input type="text" size="30" style="background-color: #8FD3D4;" readonly="readonly" value="<? echo $ORDER_QTY_TO_SHIP; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: #8FD3D4;" readonly="readonly" value="<? echo $ORDER_DMG; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: #8FD3D4;" readonly="readonly" value="<? echo $ORDER_loaded." / ".$DMG_loaded; ?>"></td>
				<td>&nbsp;</td>
			</tr>
<?
					}
				} while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
			<tr>
				<td>&nbsp;</td>
				<td align="center"><font size="2" face="Verdana"><b>Grand Totals:</b></font></td>
				<td align="left"><input type="text" size="30" style="background-color: #8FBDD4;" readonly="readonly" value="<? echo $GT_QTY_TO_SHIP; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: #8FBDD4;" readonly="readonly" value="<? echo $GT_DMG; ?>"></td>
				<td align="left"><input type="text" size="30" style="background-color: #8FBDD4;" readonly="readonly" value="<? echo $GT_loaded." / "
.$GT_DMG_loaded; ?>"></td>
				<td>&nbsp;</td>
			</tr>
<?
			}
?>
		</table></td>
	</tr>
<!--	<tr>
		<td align="left">
			<form name="new_order" action="order.php" method="post">
				<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
				<input type="hidden" name="destination" value="<? echo $destination; ?>">
				<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
				<input type="submit" name="submit" value="Add Order to List">
			</form>
		</td>
	</tr> !-->
<?
			if($show_status == "ALL" || $show_status == "Draft" || $show_status == "ALL (no Cancelled)"){
?>
	<tr>
		<td align="left">
			<form name="submit_all" action="submit_all.php" method="post">
				<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
				<input type="hidden" name="destination" value="<? echo $destination; ?>">
				<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
				<input type="submit" name="submit" value="Submit All Displayed Orders">
			</form>
		</td>
	</tr>
<?
			}
			if($sub_type == "PORT" && ($show_status == "ALL" || $show_status == "Submitted" || $show_status == "ALL (no Cancelled)")){
?>
	<tr>
		<td align="left">
			<form name="load_all" action="index.php" method="post">
				<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
				<input type="hidden" name="destination" value="<? echo $destination; ?>">
				<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
				<input type="hidden" name="action" value="load_all">
				<input type="submit" name="submit" value="Start Loading All Submitted Orders">
			</form>
		</td>
	</tr>
<?
			}
		}
	}



function getStatusSQLAddon($selection){
	switch($selection){
		case "ALL":
			return "";
		break;

		case "ALL (no Cancelled)":
			return " AND DS.STATUS != '8' ";
		break;

		case "Draft":
			return " AND DS.STATUS = '1' ";
		break;

		case "Submitted":
			return " AND DS.STATUS IN ('2', '5') ";
		break;

		case "Loading":
			return " AND DS.STATUS = '3' ";
		break;

		case "Halted":
			return " AND DS.STATUS = '4' ";
		break;

		case "Complete":
			return " AND DS.STATUS = '6' ";
		break;

		case "Confirmed":
			return " AND DS.STATUS = '7' ";
		break;

		case "Cancelled":
			return " AND DS.STATUS = '8' ";
		break;
	}
}
?>