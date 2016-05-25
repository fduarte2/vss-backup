<?
/*
*		Adam Walter, Sep/Oct 2008
*
*		Order Entry/Modification screen of the Dole paper system.
*
*		-- edit Aug 27, 2010:  removed all customer restrictions
*		-- on status changes, as per inventory request.  /crosses fingers
*****************************************************************/

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$order_num = $HTTP_POST_VARS['order_num'];
	if($order_num == ""){
		$order_num = $HTTP_GET_VARS['order_num'];
	}


	$submit = $HTTP_POST_VARS['submit'];
	$disp_wt_total = 0;

	if($submit == "Create Order"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$destination = $HTTP_POST_VARS['destination'];
		$load_date = $HTTP_POST_VARS['load_date'];
		$sail_date = $HTTP_POST_VARS['sail_date'];
		$booking_num = $HTTP_POST_VARS['booking_num'];

		$sql = "SELECT NVL(MAX(ORDER_NUM), 1) THE_MAX FROM DOLEPAPER_ORDER";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_MAX'] <= 1000){
			$next_order = 1001;
		} else {
			$next_order = $short_term_row['THE_MAX'] + 1;
		}

		$sql = "INSERT INTO DOLEPAPER_ORDER 
					(ORDER_NUM, 
					ENTERED_BY, 
					STATUS, 
					ENTERED_DATE, 
					LOAD_DATE, 
					SAIL_DATE, 
					DESTINATION_NB, 
					ARRIVAL_NUM, 
					ORDER_FOR,
					BOOKING_NUM) 
				VALUES 
					('".$next_order."', 
					'".$user."', 
					'1', 
					SYSDATE, 
					TO_DATE('".$load_date."', 'MM/DD/YYYY'), 
					TO_DATE('".$sail_date."', 'MM/DD/YYYY'), 
					'".$destination."', 
					'".$vessel."', 
					'CUSTOMER',
					'".$booking_num."')";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);

		$order_num = $next_order;

		$sql = "DELETE FROM DOLEPAPER_MOSTRECENT";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);

		$sql = "INSERT INTO DOLEPAPER_MOSTRECENT (ARRIVAL_NUM, DESTINATION_NB, LOAD_DATE) VALUES ('".$vessel."', '".$destination."', TO_DATE('".$load_date."', 'MM/DD/YYYY'))";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);

		if($sail_date != ""){
			$sql = "UPDATE DOLEPAPER_MOSTRECENT SET SAIL_DATE = TO_DATE('".$sail_date."', 'MM/DD/YYYY')";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
		}
	} elseif($order_num != ""){
		$sql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		if($short_term_row['THE_COUNT'] <= 0){
			echo "<font color=\"#FF0000\"><b>Order# ".$order_num." not found in system.</b></font>";
			$order_num = "";
		}
	}

?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<script type="text/javascript">
function SetData(LineNo){
	var a = document.getElementById("dockticket" + LineNo).value;
	var b = a.split(" -- ");
	if(b[0] != "none"){
		var IH = b[3].split(" ");
		var DMG = b[4].split(" ");
		document.getElementById("PO" + LineNo).value = b[1];
		document.getElementById("qty_ship_of" + LineNo).innerHTML = "<nobr>/ " + IH[0] + "</nobr>";
		document.getElementById("qty_ship_dmg_of" + LineNo).innerHTML = "<nobr>/ " + DMG[0] + "</nobr>";
		document.getElementById("code" + LineNo).value = b[2];
		document.getElementById("qty_ship" + LineNo).value = "";
		document.getElementById("qty_dmg_ship" + LineNo).value = "";
		document.getElementById("avg_wt" + LineNo).innerHTML = 0;
	} else {
		document.getElementById("PO" + LineNo).value = "";
		document.getElementById("qty_ship_of" + LineNo).innerHTML = "<nobr>/ 0</nobr>";
		document.getElementById("qty_ship_dmg_of" + LineNo).innerHTML = "<nobr>/ 0</nobr>";
		document.getElementById("code" + LineNo).value = "";
		document.getElementById("qty_ship" + LineNo).value = "";
		document.getElementById("qty_dmg_ship" + LineNo).value = "";
		document.getElementById("avg_wt" + LineNo).innerHTML = 0;
	}

	total_wt();

}

function changeWT(LineNo){
	var a = document.getElementById("dockticket" + LineNo).value;
	var b = a.split(" -- ");
	var WT = b[5].split(" ");
	if(document.getElementById("qty_ship" + LineNo).value == ""){
		document.getElementById("avg_wt" + LineNo).value = 0;
	} else {
//		alert("hi " + WT[0] + " " + document.getElementById("qty_ship" + LineNo).value + " " + (WT[0] * document.getElementById("qty_ship" + LineNo).value) + " " + );
		document.getElementById("avg_wt" + LineNo).innerHTML = WT[0] * document.getElementById("qty_ship" + LineNo).value;
	}

	total_wt();
}

function total_wt(){
	var total_wt = 0;
	for (i = 0; i < 10; i++){
		total_wt += parseInt(document.getElementById("avg_wt" + i).innerHTML);
	}
	document.getElementById("wt_total9").innerHTML = "Total EST. WT: " + total_wt;
}



function IsNumeric(strString){
   //  check for valid numeric strings	
   var strValidChars = "0123456789.";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++){
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1){
         blnResult = false;
      }
   }
   return blnResult;
}



function validate_form(){

if(!Array.indexOf){
  Array.prototype.indexOf = function(obj){
   for(var i=0; i<this.length; i++){
    if(this[i]==obj){
     return i;
    }
   }
   return -1;
  }
}


	var errorlist = "";
	var a = "";
	var b = "";
	var IH = "";
	var DMG = "";
	var ORD = "";
	var ORD_DMG = "";
	var TicketArray = new Array(10);

	for(loopcount = 0; loopcount < 10; loopcount++){ // we are checking each line of the 10
//alert("hi" + loopcount+ "a");
		document.getElementById("error" + loopcount).innerHTML = ""; // clear out existing error message for this line

		a = document.getElementById("dockticket" + loopcount).value;
		b = a.split(" -- ");
		if(b[0] != "none"){ // If this is an actual line of data
//alert("hi" + loopcount+ "b");

			TicketArray[loopcount] = b[0];
			IH = b[3].split(" ");
			DMG = b[4].split(" ");
			ORD = document.getElementById("qty_ship" + loopcount).value;
			ORD_DMG = document.getElementById("qty_dmg_ship" + loopcount).value;

//alert(TicketArray[loopcount]);
//alert(b[0]);
//alert(TicketArray.indexOf(b[0]));
			if(TicketArray.indexOf(b[0]) < loopcount){
//alert("hi" + loopcount+ "c");

				errorlist = errorlist + "Line " + (loopcount + 1) + " Dock Ticket already used on Line " + (TicketArray.indexOf(b[0]) + 1) + "\n";
				document.getElementById("error" + loopcount).innerHTML = " Dock Ticket already used on Line " + (TicketArray.indexOf(b[0]) + 1);
			} else {
//alert("hi" + loopcount+ "d");

				if(!IsNumeric(ORD)){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY Ordered Not Numeric\n";
					document.getElementById("error" + loopcount).innerHTML = " Order Value isn't a Number";
				} else if(parseInt(ORD, 10) > parseInt(IH, 10)){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY Ordered More than Available\n";
					document.getElementById("error" + loopcount).innerHTML = " Order Value More than Available";
				} else if(ORD == ""){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY Ordered Cannot be Blank\n";
					document.getElementById("error" + loopcount).innerHTML = " Order Value Empty";
				} else if(ORD.indexOf(".") != -1 || ORD.indexOf(",") != -1 ){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY Ordered Cannot Involve Partial Rolls\n";
					document.getElementById("error" + loopcount).innerHTML = " Order Value Must be a Whole Number";
				} 

//alert("hi" + loopcount+ "e");


				if(!IsNumeric(ORD_DMG)){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY-DMG Ordered Not Numeric\n";
					document.getElementById("error" + loopcount).innerHTML = " DMG-Order Value isn't a Number";
				} else if(parseInt(ORD_DMG, 10) > parseInt(DMG, 10)){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY-DMG Ordered More than Available\n";
					document.getElementById("error" + loopcount).innerHTML = " DMG-Order Value More than Available";
				} else if(ORD_DMG == ""){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY-DMG Ordered Cannot be Blank\n";
					document.getElementById("error" + loopcount).innerHTML = " DMG-Order Value Empty";
				} else if(ORD_DMG.indexOf(".") != -1 || ORD_DMG.indexOf(",") != -1 ){
					errorlist = errorlist + "Line " + (loopcount + 1) + " QTY-DMG Ordered Cannot Involve Partial Rolls\n";
					document.getElementById("error" + loopcount).innerHTML = " DMG-Order Value Must be a Whole Number";
				} 
			}

		}
	}

	if(errorlist == ""){
		return true;
	} else {
		alert(errorlist);
		return false;
	}


}
</script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Dole Order Details Page<? if($order_num != ""){?> ORDER#: <? echo $order_num;}?>
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_select_1" action="order_mod.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana">Pending Orders:</td>
		<td align="left"><select name="order_num" onchange="document.order_select_1.submit(this.form)"><option value="">Select an Order:</option>
<?

	$sql = "SELECT ORDER_NUM FROM DOLEPAPER_ORDER 
			WHERE ORDER_FOR = 'CUSTOMER' AND STATUS NOT IN ('7', '8') 
				AND (LOAD_DATE >= SYSDATE - 91 OR LOAD_DATE IS NULL) 
			ORDER BY ORDER_NUM DESC";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $short_term_row['ORDER_NUM']; ?>"<? if($order_num == $short_term_row['ORDER_NUM']){?> selected <?}?>><? echo $short_term_row['ORDER_NUM']; ?></option>
<?
	}
?>
					</select></td>
	</tr>
</form>
<form name="order_select_2" action="order_mod.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="3" face="Verdana">Freeform Order# Entry:</td>
		<td align="left"><input type="text" name="order_num" size="10" maxlength="10" value="<? echo $order_num; ?>"></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><br><hr><br></td>
	</tr>
</table>

<?
	if($order_num != ""){
		// the next 2 sections only display if an order is chosen, of course

		$sql = "SELECT ARRIVAL_NUM, STATUS, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, TO_CHAR(SAIL_DATE, 'MM/DD/YYYY') THE_SAIL, DESTINATION_NB, 
					USER_COMMENTS, STATUS, SEAL, BOOKING_NUM, CONTAINER_ID
				FROM DOLEPAPER_ORDER WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		// later on, this will rpevent them from changing quantites for non-submitted/draft orders
		$no_edit_bottom_1 = "";
		$no_edit_bottom_2 = "";
		$no_edit_top = "";
		if($short_term_row['STATUS'] == 3 || $short_term_row['STATUS'] == 6 || $short_term_row['STATUS'] == 7 || $short_term_row['STATUS'] == 8){
			$no_edit_bottom_1 = "disabled";
			$no_edit_bottom_2 = "readonly";
		}
		
		if($short_term_row['STATUS'] == 3 || $short_term_row['STATUS'] == 7 || $short_term_row['STATUS'] == 8){
			$no_edit_top_1 = "disabled";
			$no_edit_top_2 = "readonly";
		}

		$vessel = $short_term_row['ARRIVAL_NUM'];
		$status = $short_term_row['STATUS'];
		$load_date = $short_term_row['THE_LOAD'];
		$sail_date = $short_term_row['THE_SAIL'];
		$destination = $short_term_row['DESTINATION_NB'];
		$user_comments = $short_term_row['USER_COMMENTS'];
		$sealnum = $short_term_row['SEAL'];
		$booking_num = $short_term_row['BOOKING_NUM'];
		$container = $short_term_row['CONTAINER_ID'];

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_print = $short_term_row['VESSEL_NAME'];

		$sql = "SELECT DESTINATION FROM DOLEPAPER_DESTINATIONS WHERE DESTINATION_NB = '".$destination."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$destination_print = $short_term_row['DESTINATION'];

		$sql = "SELECT ST_DESCRIPTION FROM DOLEPAPER_STATUSES WHERE STATUS = '".$status."'";
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$status_print = $short_term_row['ST_DESCRIPTION'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="order_info" action="order_entry.php" method="post" onsubmit="return validate_form()">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
	<tr>
		<td align="left" width="20%">&nbsp;</td>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Current</b></font></td>
		<td align="left"><font size="2" face="Verdana"><b>New</b></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Vessel:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $vessel_print; ?></td>
		<td align="left"><select name="vessel" <? echo $no_edit_top_1; ?>><option value=""> </option>
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
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Destination:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $destination_print; ?></td>
		<td align="left"><select name="destination" <? echo $no_edit_top_1; ?>><option value=""> </option>
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
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Loading Date:</b></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $load_date; ?></td>
		<td align="left"><input type="text" name="load_date" size="20" maxlength="10" value="<? echo $load_date; ?>" <? echo $no_edit_bottom_2; ?>>&nbsp;&nbsp;<a href="javascript:show_calendar('order_info.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Sail Date:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? if($sail_date == ""){ echo "none"; } else {echo $sail_date;} ?></font></td>
		<td align="left"><input type="text" name="sail_date" maxlength="10" size="12" value="<? echo $sail_date; ?>" <? echo $no_edit_bottom_2; ?>>&nbsp;&nbsp;<a href="javascript:show_calendar('order_info.sail_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Booking#:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $booking_num; ?></font></td>
		<td><input type="text" name="booking_num" maxlength="30" size="30" value="<? echo $booking_num; ?>"></td>
	</tr>
<?
	if($eport_sealorder_auth == "Y"){ // this is set in the order_mod.php file
?>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Seal#:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $sealnum; ?></font></td>
		<td><input type="text" name="sealnum" maxlength="20" size="20" value="<? echo $sealnum; ?>"></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Seal#:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $sealnum; ?></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $sealnum; ?></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Container#:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $container; ?></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $container; ?></font></td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Status:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $status_print; ?></font></td>
		<td align="left">
<?
			// this next large set of if-thens determiens which statuses show to whom and when for change....-ability.
			if($status == "1" || $status == "2"){
?>
						<input type="radio" name="status" value="1" <? if($status == "1"){?> checked <?}?>> Draft<br>
<?
			}
			if($status == "2" || $status == "1"){
?>
						<input type="radio" name="status" value="2" <? if($status == "2"){?> checked <?}?>> Submitted<br>
<?
			}
//			if($status == "3" || $sub_type == "PORT" && ($status == "2" || $status == "5" || $status == "6")){
			if($status == "3" || $status == "2" || $status == "5" || $status == "6"){
?>
						<input type="radio" name="status" value="3" <? if($status == "3"){?> checked <?}?>> Container Loading<br>
<?
			}
//			if($status == "4" || ($sub_type == "PORT" && $status == "3")){
			if($status == "4" || $status == "3"){
?>
						<input type="radio" name="status" value="4" <? if($status == "4"){?> checked <?}?>> Loading Stopped<br>
<?
			}
			if($status == "5" || $status == "4"){
?>
						<input type="radio" name="status" value="5" <? if($status == "5"){?> checked <?}?>> Revision Submitted<br>
<?
			}
//			if($status == "6" || ($sub_type == "PORT" && $status == "3") || ($sub_type == "PORT" && $status == "7")){
			if($status == "6" || $status == "3" || $status == "7"){
?>
						<input type="radio" name="status" value="6" <? if($status == "6"){?> checked <?}?>> Order Complete<br>
<?
			}
			if($status == "7" || ($sub_type == "CUST" && $status == "6")){
?>
						<input type="radio" name="status" value="7" <? if($status == "7"){?> checked <?}?>> Customer Confirmed<br>
<?
			}
//			if($status == "8" || $status == "1" || $status == "2" || $sub_type == "PORT" && ($status == "4" || $status == "5")){
			if($status == "8" || $status == "1" || $status == "2" || $status == "4" || $status == "5"){
?>
						<input type="radio" name="status" value="8" <? if($status == "8"){?> checked <?}?>> Cancelled<br>
<?
			}
?>
			</td>
	</tr>
	<tr>
		<td align="left" width="20%"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left" width="20%"><font size="2" face="Verdana"><? echo $user_comments; ?></font></td>
		<td><textarea name="user_comments" cols="50" rows="10"><? echo $user_comments; ?></textarea></td>
	</tr>
	<tr>
		<td colspan="3"><br><hr><br></td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana">Dock Ticket</font></td>
		<td><font size="2" face="Verdana">Description</font></td>
		<td><font size="2" face="Verdana">Code</font></td>
		<td align="right"><font size="2" face="Verdana">QTY to ship</font></td>
		<td>&nbsp;</td>
		<td align="center"><font size="2" face="Verdana">Of which<br>___<br>is reject</font></td>
		<td>&nbsp;</td>
		<td width="20%">&nbsp;</td>
	</tr>

<?
		// for the dockticket bottom of the page, we loop a DB call to DOLEPAPER_DOCKTICKET.
		// this way we will always have 10 lines, the top ones of which will have saved data in them.

		// anyplace you se ALSC, it means "ALready-SCanned"
		$availability_array = array();
		$j = 0;
		$sql = "SELECT THE_TICKET, THE_DESC, THE_CODE, IN_HOUSE, THE_DMG, THE_WEIGHT, NVL(THE_SHIP_ORDERED, 0) THE_SHIP_ORDERED, NVL(THE_DMG_ORDERED, 0) THE_DMG_ORDERED,
					NVL(AL_SC_ON_ACTIVE_ORDERS, 0) ON_ORDERS, NVL(AL_SC_DMG_ON_ACTIVE_ORDERS, 0) DMG_ON_ORDERS
			FROM
				(SELECT BOL THE_TICKET, CARGO_DESCRIPTION THE_DESC, CT.BATCH_ID THE_CODE, SUM(QTY_RECEIVED) IN_HOUSE, SUM(QTY_DAMAGED) THE_DMG, SUM(WEIGHT) THE_WEIGHT 
				FROM CARGO_TRACKING CT
				WHERE REMARK = 'DOLEPAPERSYSTEM'
				AND DATE_RECEIVED IS NOT NULL
				AND RECEIVER_ID != '1'
				AND (QTY_IN_HOUSE > 0 OR (SELECT COUNT(*) FROM CARGO_ACTIVITY CA WHERE ORDER_NUM = '".$order_num."' AND CA.PALLET_ID =										CT.PALLET_ID AND CA.BATCH_ID = CT.BOL AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL) > 0) 
				GROUP BY BOL, CARGO_DESCRIPTION, CT.BATCH_ID 
				ORDER BY THE_TICKET) 
			AVAILABLE, 
				(SELECT DOCK_TICKET, SUM(QTY_SHIP) THE_SHIP_ORDERED, SUM(QTY_DMG_SHIP) THE_DMG_ORDERED
				FROM DOLEPAPER_ORDER DO, DOLEPAPER_DOCKTICKET DT
				WHERE DO.ORDER_NUM = DT.ORDER_NUM
				AND ORDER_FOR = 'CUSTOMER'
				AND DO.STATUS NOT IN ('6', '7', '8')
				AND DO.ORDER_NUM != '".$order_num."'
				GROUP BY DOCK_TICKET) 
			ORDERED,
				(SELECT CA.BATCH_ID, SUM(QTY_CHANGE) AL_SC_ON_ACTIVE_ORDERS, SUM(QTY_DAMAGED) AL_SC_DMG_ON_ACTIVE_ORDERS
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
				WHERE CT.PALLET_ID = CA.PALLET_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CA.SERVICE_CODE = '6'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')
				AND CA.ORDER_NUM IN
					(SELECT TO_CHAR(ORDER_NUM)
					FROM DOLEPAPER_ORDER
					WHERE ORDER_FOR = 'CUSTOMER'
					AND STATUS NOT IN ('6', '7', '8')
					AND ORDER_NUM != '".$order_num."')
				GROUP BY CA.BATCH_ID)
			AL_SC
		WHERE AVAILABLE.THE_TICKET = ORDERED.DOCK_TICKET(+)
			AND AVAILABLE.THE_TICKET = AL_SC.BATCH_ID(+)
		ORDER BY THE_TICKET";
//				echo $sql;
		ora_parse($short_term_data_cursor, $sql);
		ora_exec($short_term_data_cursor);
		while(ora_fetch_into($short_term_data_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$availability_array['ticket'][$j] = $short_term_row['THE_TICKET'];
			$availability_array['desc'][$j] = $short_term_row['THE_DESC'];
			$availability_array['code'][$j] = $short_term_row['THE_CODE'];
			$availability_array['IH'][$j] = $short_term_row['IN_HOUSE'];
			$availability_array['DMG'][$j] = $short_term_row['THE_DMG'];
			$availability_array['WT'][$j] = $short_term_row['THE_WEIGHT'];
			$availability_array['ord'][$j] = $short_term_row['THE_SHIP_ORDERED'];
			$availability_array['ord_dmg'][$j] = $short_term_row['THE_DMG_ORDERED'];
			$availability_array['alsc_ord'][$j] = $short_term_row['ON_ORDERS'];
			$availability_array['alsc_ord_dmg'][$j] = $short_term_row['DMG_ON_ORDERS'];

			$j++;
		}
		
		$i = 0;
		$sql = "SELECT P_O, DOCK_TICKET, QTY_SHIP, QTY_DMG_SHIP, CODE FROM DOLEPAPER_DOCKTICKET WHERE ORDER_NUM = '".$order_num."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ // this part for existing data
?>
	<tr>
		<td><select name="dockticket[<? echo $i;?>]" id="dockticket<? echo $i; ?>" onchange="SetData(<? echo $i; ?>)" <? echo $no_edit_bottom_1; ?>><option value="none"></option>
<?
			$init_IH = 0;
			$init_DMG = 0;
			for($temp = 0; $temp < $j; $temp++){
?>
						<option value="<? echo
							$availability_array['ticket'][$temp]." -- ".
							$availability_array['desc'][$temp]." -- ".
							$availability_array['code'][$temp]." -- ".
							($availability_array['IH'][$temp] - ($availability_array['ord'][$temp] - $availability_array['alsc_ord'][$temp]))." IH -- ".
							($availability_array['DMG'][$temp] - ($availability_array['ord_dmg'][$temp] - $availability_array['alsc_ord_dmg'][$temp]))." DMG -- ".
							round($availability_array['WT'][$temp] / $availability_array['IH'][$temp])." lbs avg";?>"<?
				if($row['DOCK_TICKET'] == $availability_array['ticket'][$temp]){
					$init_IH = ($availability_array['IH'][$temp] - ($availability_array['ord'][$temp] - $availability_array['alsc_ord'][$temp]));
					$init_DMG = ($availability_array['DMG'][$temp] - ($availability_array['ord_dmg'][$temp] - $availability_array['alsc_ord_dmg'][$temp]));
					$disp_wt = round($availability_array['WT'][$temp] * $row['QTY_SHIP'] / $availability_array['IH'][$temp]);
					?> selected <?
				}?>>
					<? echo 
							$availability_array['ticket'][$temp]." -- ".
							$availability_array['desc'][$temp]." -- ".
							$availability_array['code'][$temp]." -- ".
							($availability_array['IH'][$temp] - ($availability_array['ord'][$temp] - $availability_array['alsc_ord'][$temp]))." IH -- ".
							($availability_array['DMG'][$temp] - ($availability_array['ord_dmg'][$temp] - $availability_array['alsc_ord_dmg'][$temp]))." DMG -- ".
							round($availability_array['WT'][$temp] / $availability_array['IH'][$temp])." lbs avg";?></option>						
<?
			}
?>
					</select></td>
		<td><input type="text" size="20" id="PO<? echo $i;?>" maxlength="30" name="PO[<? echo $i; ?>]" value="<? echo $row['P_O']; ?>" readonly></td>
		<td><input type="text" size="10" id="code<? echo $i;?>" maxlength="20" name="code[<? echo $i; ?>]" value="<? echo $row['CODE']; ?>" readonly></td>
		<td align="right"><input type="text" size="5" id="qty_ship<? echo $i;?>" maxlength="2" name="qty_ship[<? echo $i; ?>]" value="<? echo $row['QTY_SHIP']; ?>" onchange="changeWT(<? echo $i; ?>)" <? echo $no_edit_bottom_2; ?>></td>
		<td><div id="qty_ship_of<? echo $i; ?>"><nobr>/ <? echo $init_IH; ?></nobr></div></td>
		<td align="right"><input type="text" size="5" id="qty_dmg_ship<? echo $i;?>" maxlength="2" name="qty_dmg_ship[<? echo $i; ?>]" value="<? echo $row['QTY_DMG_SHIP']; ?>"<? echo $no_edit_bottom_2; ?>></td>
		<td><div id="qty_ship_dmg_of<? echo $i; ?>"><nobr>/ <? echo $init_DMG; ?></nobr></div></td>
		<td width="20%"><font color="#FF0000"><div id="error<? echo $i; ?>">&nbsp;</div></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div id="avg_wt<? echo $i; ?>"><? echo $disp_wt; $disp_wt_total += $disp_wt; ?></div></td>
		<td>lbs.</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div id="wt_total<? echo $i; ?>"></div></td>
	</tr>
<?
			$i++;
		}
		for(; $i < 10; $i++){ // put the rest of the 10 lines....
?>
	<tr>
		<td><select name="dockticket[<? echo $i;?>]" id="dockticket<? echo $i; ?>" onchange="SetData(<? echo $i; ?>)" <? echo $no_edit_bottom_1; ?>><option value="none"></option>
<?
			for($temp = 0; $temp < $j; $temp++){
?>
						<option value="<? echo
							$availability_array['ticket'][$temp]." -- ".
							$availability_array['desc'][$temp]." -- ".
							$availability_array['code'][$temp]." -- ".
							($availability_array['IH'][$temp] - ($availability_array['ord'][$temp] - $availability_array['alsc_ord'][$temp]))." IH -- ".
							($availability_array['DMG'][$temp] - ($availability_array['ord_dmg'][$temp] - $availability_array['alsc_ord_dmg'][$temp]))." DMG -- ".
							round($availability_array['WT'][$temp] / $availability_array['IH'][$temp])." lbs avg";?>">
					<? echo 
							$availability_array['ticket'][$temp]." -- ".
							$availability_array['desc'][$temp]." -- ".
							$availability_array['code'][$temp]." -- ".
							($availability_array['IH'][$temp] - ($availability_array['ord'][$temp] - $availability_array['alsc_ord'][$temp]))." IH -- ".
							($availability_array['DMG'][$temp] - ($availability_array['ord_dmg'][$temp] - $availability_array['alsc_ord_dmg'][$temp]))." DMG -- ".
							round($availability_array['WT'][$temp] / $availability_array['IH'][$temp])." lbs avg";?></option>	
<?
			}
?>
					</select></td>
		<td><input type="text" size="20" id="PO<? echo $i;?>" maxlength="30" name="PO[<? echo $i; ?>]" value="" readonly></td>
		<td><input type="text" size="10" id="code<? echo $i;?>" maxlength="20" name="code[<? echo $i; ?>]" value="" readonly></td>
		<td align="right"><input type="text" size="5" id="qty_ship<? echo $i;?>" maxlength="2" name="qty_ship[<? echo $i; ?>]" value="" onchange="changeWT(<? echo $i; ?>)" <? echo $no_edit_bottom_2; ?>></td>
		<td><div id="qty_ship_of<? echo $i; ?>"><nobr>/ 0</nobr></div></td>
		<td align="right"><input type="text" size="5" id="qty_dmg_ship<? echo $i;?>" maxlength="2" name="qty_dmg_ship[<? echo $i; ?>]" value=""<? echo $no_edit_bottom_2; ?>></td>
		<td><div id="qty_ship_dmg_of<? echo $i; ?>"><nobr>/ 0</nobr></div></td>
		<td width="20%"><font color="#FF0000"><div id="error<? echo $i; ?>">&nbsp;</div></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div id="avg_wt<? echo $i; ?>">0</div></td>
		<td>lbs.</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div id="wt_total<? echo $i; ?>"><? if($i == 9){ echo "Total EST. WT: ".$disp_wt_total; }?></div></td>
	</tr>
<?
		}
?>
	<tr>
		<td colspan="7" align="center"><input type="submit" name="submit" value="Save Order"></td>
	</tr>
</table>
</form>
<?
	}
?>