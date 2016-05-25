<?
/*
*		Adam Walter, Nov 2008
*
*		Order Entry screen of the Dole paper system.
*
*****************************************************************/

	$short_term_data_cursor = ora_open($conn);


	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Save Order"){
		// here we go!

		$order_num = $HTTP_POST_VARS['order_num'];
		$vessel = $HTTP_POST_VARS['vessel'];
		$destination = $HTTP_POST_VARS['destination'];
		$load_date = $HTTP_POST_VARS['load_date'];
		$sail_date = $HTTP_POST_VARS['sail_date'];
		$status = $HTTP_POST_VARS['status'];
		$dockticket = $HTTP_POST_VARS['dockticket'];
		$code = $HTTP_POST_VARS['code'];
		$qty_ship = $HTTP_POST_VARS['qty_ship'];
		$qty_dmg_ship = $HTTP_POST_VARS['qty_dmg_ship'];
		$PO = $HTTP_POST_VARS['PO'];
		$sealnum = $HTTP_POST_VARS['sealnum'];
		$user_comments = $HTTP_POST_VARS['user_comments'];
		$booking_num = $HTTP_POST_VARS['booking_num'];

		$user_comments = str_replace("\"", "", $user_comments);
		$user_comments = str_replace("\\", "", $user_comments);
		$user_comments = str_replace("'", "`", $user_comments);

		// get current status
		$sql = "SELECT STATUS 
				FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$old_status = $row['STATUS'];

		// update order table.
		$sql = "UPDATE DOLEPAPER_ORDER SET ";
		if($status != ""){
			$sql .= "STATUS = '".$status."', ";
		}
		if($load_date != ""){
			$sql .= "LOAD_DATE = TO_DATE('".$load_date."', 'MM/DD/YYYY'), ";
		}
		if($sail_date != ""){
			$sql .= "SAIL_DATE = TO_DATE('".$sail_date."', 'MM/DD/YYYY'), ";
		}
		if($destination != ""){
			$sql .= "DESTINATION_NB = '".$destination."', ";
		}
		if($vessel != ""){
			$sql .= "ARRIVAL_NUM = '".$vessel."', ";
		}
		if($sealnum != ""){
			$sql .= "SEAL = '".$sealnum."', EPORT_SEAL = '".$sealnum."', ";
		}
		if($booking_num != ""){
			$sql .= "BOOKING_NUM = '".$booking_num."', ";
		}
		$sql .= "USER_COMMENTS = '".$user_comments."' WHERE ORDER_NUM = '".$order_num."'";
/*
		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '".$status."', LOAD_DATE = TO_DATE('".$load_date."', 'MM/DD/YYYY'), SAIL_DATE = TO_DATE('".$sail_date."', 'MM/DD/YYYY'), DESTINATION_NB = '".$destination."', ARRIVAL_NUM = '".$vessel."', USER_COMMENTS = '".$user_comments."' WHERE ORDER_NUM = '".$order_num."'";
*/
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);

		if($old_status == 1 || $old_status == 2 || $old_status == 4 || $old_status == 5){
			// we only want to update bottom half if status was in a "changable" (draft or submitted) state
			$sql = "DELETE FROM DOLEPAPER_DOCKTICKET WHERE ORDER_NUM = '".$order_num."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);

			for($i = 0; $i < 10; $i++){
				$temp = split(" -- ", $dockticket[$i]);

				if($temp[0] != "none"){
					$sql = "INSERT INTO DOLEPAPER_DOCKTICKET (ORDER_NUM, DOCK_TICKET, QTY_SHIP, QTY_DMG_SHIP, CODE, P_O) VALUES ('".$order_num."', '".$temp[0]."', '".$qty_ship[$i]."', '".$qty_dmg_ship[$i]."', '".$code[$i]."', '".$PO[$i]."')";
					ora_parse($short_term_data_cursor, $sql);
					ora_exec($short_term_data_cursor);
				}
			}
		}
	}

	// this part of the code gets a "most recent vessel" information, in case values are not yet set
	// table should ONLY EVER have 1 record in it
	// the "date departed is null" prevents a "most recent" vessel that is now already GONE from populating bottom page
	$sql = "SELECT DM.DESTINATION_NB, DM.ARRIVAL_NUM, TO_CHAR(DM.LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, TO_CHAR(DM.SAIL_DATE, 'MM/DD/YYYY') THE_SAIL FROM DOLEPAPER_MOSTRECENT DM, VOYAGE V WHERE DM.ARRIVAL_NUM = V.LR_NUM AND V.DATE_DEPARTED IS NULL";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	if(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($vessel == ""){
			$vessel = $row['ARRIVAL_NUM'];
		}
		if($destination == ""){
			$destination = $row['DESTINATION_NB'];
		}
		if($load_date == ""){
			$load_date = $row['THE_LOAD'];
		}
		if($sail_date == ""){
			$sail_date = $row['THE_SAIL'];
		}
	}


?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Dole Order Entry Page
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="top_select" action="order_mod.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana">Vessel:</td>
		<td width="4%">&nbsp;</td>
		<td align="left"><select name="vessel"><option value="">Select a Vessel:</option>
<?
	$sql = "SELECT VO.LR_NUM, VP.VESSEL_NAME FROM VOYAGE VO, VESSEL_PROFILE VP WHERE VO.LR_NUM = VP.LR_NUM AND VP.SHIP_PREFIX = 'DOLE' AND (VO.DATE_DEPARTED IS NULL OR VO.DATE_DEPARTED > SYSDATE) ORDER BY VO.LR_NUM";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($vessel == $row['LR_NUM']){?> selected <?}?>><? echo $row['VESSEL_NAME']; ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana">Destination:</td>
		<td width="4%">&nbsp;</td>
		<td align="left"><select name="destination"><option value="">Select a Destination:</option>
<?
	$sql = "SELECT DESTINATION_NB, DESTINATION FROM DOLEPAPER_DESTINATIONS ORDER BY DESTINATION_NB";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['DESTINATION_NB']; ?>"<? if($destination == $row['DESTINATION_NB']){?> selected <?}?>><? echo $row['DESTINATION']; ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana">Loading Date:</td>
		<td width="4%">&nbsp;</td>
		<td align="left"><input type="text" name="load_date" size="20" maxlength="10" value="<? echo $load_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('top_select.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana">Sail Date:</td>
		<td width="4%">&nbsp;</td>
		<td align="left"><input type="text" name="sail_date" size="20" maxlength="10" value="<? echo $sail_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('top_select.sail_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana"><b>Booking#:</b></font></td>
		<td width="4%">&nbsp;</td>
		<td><input type="text" name="booking_num" maxlength="30" size="30" value="<? echo $booking_num; ?>"></td>
	</tr>
	<tr>
		<td align="left" colspan="3"><input type="submit" name="submit" value="Create Order"></td>
	</tr>
</form>
</table>
