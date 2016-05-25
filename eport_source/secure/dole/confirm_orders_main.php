<?
/*
*		Adam Walter, Sep/Oct 2008
*
*		Mass-confirmation screen; Customers only.
*
*****************************************************************/

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$load_date = $HTTP_POST_VARS['load_date'];
	$submit = $HTTP_POST_VARS['submit'];
	$load_date2 = $HTTP_POST_VARS['load_date2'];

	if($submit == "Confirm Orders"){
		$sql = "UPDATE DOLEPAPER_ORDER SET STATUS = '7' WHERE STATUS = '6' AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$load_date2."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Dole Order Confirmation Page
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<?
	if($load_date == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="confirm_orders.php" method="post">
	<tr>
		<td align="center">Loading Date:  <input name="load_date" type="text" size="15" maxlength="15">  <a href="javascript:show_calendar('the_form.load_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="Retrieve Orders"></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana">Please note:  if you wish to confrim orders individually, please use the<br><b><a href="order_mod.php">Edit Order</a></b><br>Screen instead.</td>
	</tr>
</form>
</table>
<?
	} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="confirm_orders.php" method="post">
<input type="hidden" name="load_date2" value="<? echo $load_date; ?>">
	<tr>
		<td align="center"><input type="submit" name="submit" value="Confirm Orders"></td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
</form>
<?
		$sql = "SELECT DO.ORDER_NUM, TO_CHAR(DO.SAIL_DATE, 'MM/DD/YYYY') THE_SAIL, VP.VESSEL_NAME, DD.DESTINATION, DS.ST_DESCRIPTION, DO.CONTAINER_ID FROM DOLEPAPER_ORDER DO, DOLEPAPER_STATUSES DS, VESSEL_PROFILE VP, DOLEPAPER_DESTINATIONS DD WHERE DO.ARRIVAL_NUM = VP.LR_NUM AND DO.STATUS = DS.STATUS AND DO.DESTINATION_NB = DD.DESTINATION_NB AND TO_CHAR(DO.LOAD_DATE, 'MM/DD/YYYY') = '".$load_date."' AND DO.STATUS = '6' ORDER BY ORDER_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#FF0000">No Orders Awaiting Confirmation:</font></td>
	</tr>
</table>
<?
		} else {
?>
	<tr>
		<td align="center"><font size="3" face="Verdana"><b>Orders Awaiting Confirmation:</b></font></td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><b>Order #</b></td>
		<td><b>Destination</b></td>
		<td><b>Vessel</b></td>
		<td><b>Container</b></td>
		<td><b>Sail Date</b></td>
	</tr>
<?
			do {
?>
	<tr>
		<td><? echo $row['ORDER_NUM']; ?></td>
		<td><? echo $row['DESTINATION']; ?></td>
		<td><? echo $row['VESSEL_NAME']; ?></td>
		<td><? echo $row['CONTAINER_ID']; ?>&nbsp;</td>
		<td><? echo $row['THE_SAIL']; ?></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
?>
