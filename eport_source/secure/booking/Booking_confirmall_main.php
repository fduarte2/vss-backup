<?
/*
*			Adam Walter, Jun 2010
*			This page allows Booking Paper Order-Makers
*			The ability to submit every order for the day
******************************************************************/

	$cursor = ora_open($conn);
	if($eport_customer_id == 0){
		$cust = $HTTP_POST_VARS['cust'];
	} else {
		$cust = $eport_customer_id;
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Confirm All Orders"){
		if($cust != "all"){
			$cust_sql = " AND CUSTOMER_ID = '".$cust."' ";
		} else {
			$cust_sql = " ";
		}

		$sql = "UPDATE BOOKING_ORDERS
				SET STATUS = '7'
				WHERE STATUS = '6'".$cust_sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Paper Confirm All Orders
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>
<?
	if($eport_customer_id == 0){
?>
<form name="filter" action="Booking_confirmall_index.php" method="post">
	<font size="2" face="Verdana"><b>Customer: </b></font>
			<select name="cust" onchange="document.filter.submit(this.form)">
						<option value="">Select a Customer</option>
						<option value="all"<? if($cust == "all"){?> selected <?}?>>All</option>
						<option value="314"<? if($cust == "314"){?> selected <?}?>>314</option>
						<option value="338"<? if($cust == "338"){?> selected <?}?>>338</option>
						<option value="517"<? if($cust == "517"){?> selected <?}?>>517</option>
			</select>
</form>
<?
	}
	if($cust != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="Booking_confirmall_index.php" method="post">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<?
		if($cust != "all"){
			$cust_sql = " AND BO.CUSTOMER_ID = '".$cust."' ";
		} else {
			$cust_sql = " ";
		}

		$sql = "SELECT BO.ORDER_NUM, TO_CHAR(BO.LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, VP.VESSEL_NAME, BO.CONTAINER_ID 
					FROM BOOKING_ORDERS BO, DOLEPAPER_STATUSES DS, VESSEL_PROFILE VP 
				WHERE BO.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM) 
					AND BO.STATUS = DS.STATUS 
					AND BO.STATUS = '6'
					".$cust_sql."
				ORDER BY ORDER_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana" color="#0000FF">No Orders awaiting confirmation.</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="3" align="center"><input name="submit" type="submit" value="Confirm All Orders"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Container</b></font></td>
	</tr>
<?
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['VESSEL_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['CONTAINER_ID']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
	<tr>
		<td colspan="3" align="center"><input name="submit" type="submit" value="Confirm All Orders"></td>
	</tr>
</form>
</table>
<?
	}
?>