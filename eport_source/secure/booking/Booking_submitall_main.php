<?
/*
*			Adam Walter, Jun 2010
*			This page allows Booking Paper Order-Makers
*			The ability to submit every order for the day
******************************************************************/

	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	if($eport_customer_id == 0){
		$cust = $HTTP_POST_VARS['cust'];
	} else {
		$cust = $eport_customer_id;
	}
	
	if($submit == "Submit All Orders"){
		if($cust != "all"){
			$cust_sql = " AND CUSTOMER_ID = '".$cust."' ";
		} else {
			$cust_sql = " ";
		}

		$sql = "UPDATE BOOKING_ORDERS
				SET STATUS = '2'
				WHERE STATUS = '1'
					".$cust_sql."
					AND ORDER_NUM IN
						(SELECT ORDER_NUM FROM BOOKING_ORDER_DETAILS WHERE QTY_TO_SHIP > 0)";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Paper Submit All Orders
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>


<?
	if($eport_customer_id == 0){
?>
<form name="filter" action="Booking_submitall.php" method="post">
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
<form name="get_data" action="Booking_submitall.php" method="post">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<?
		if($cust != "all"){
			$cust_sql = " AND CUSTOMER_ID = '".$cust."' ";
		} else {
			$cust_sql = " ";
		}

		$sql = "SELECT BO.ORDER_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_DATE, ORDER_COMMENT, NVL(SUM(QTY_TO_SHIP), 0) THE_SUM
				FROM BOOKING_ORDERS BO, BOOKING_ORDER_DETAILS BOD
				WHERE BO.ORDER_NUM = BOD.ORDER_NUM
					AND BO.STATUS = '1'
					".$cust_sql."
				GROUP BY BO.ORDER_NUM, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY'), ORDER_COMMENT
				HAVING NVL(SUM(QTY_TO_SHIP), 0) > 0
				ORDER BY BO.ORDER_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="#0000FF">No Orders awaiting submission.</font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Load Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Ordered Rolls</b></font></td>
		<td><font size="2" face="Verdana"><b>Comments</b></font></td>
	</tr>
<?
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_SUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_COMMENT']; ?>&nbsp;</font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
	<tr>
		<td colspan="4" align="center"><input name="submit" type="submit" value="Submit All Orders"></td>
	</tr>
</form>
</table>
<?
	}
?>