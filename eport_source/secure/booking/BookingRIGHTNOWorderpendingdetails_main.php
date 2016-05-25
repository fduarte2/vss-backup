<?php
/*
*			Adam Walter, Sep 2015
*
*			A drilldown for pending orders
*			hyperlinked from BookingRIGHTNOW.php.
******************************************************************/

$cursor = ora_open($conn);
if ($eport_customer_id == 0) {
	$cust = $HTTP_POST_VARS['cust'];
} else {
	$cust = $eport_customer_id;
}
if ($testflag == "LIVE") {
	$headerprint = "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
} else {
	$headerprint = "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
}

$book = $_GET['book'];
$po = $_GET['po'];
$width = $_GET['width'];
$dia = $_GET['dia'];
$grade = $_GET['grade'];


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left"><?php echo $headerprint; ?>
	    <font size="5" face="Verdana" color="#0066CC">Booking Paper REAL TIME Inventory - Pending Orders 
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Booking#:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $book; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>PO#:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $po; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Width:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $width; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Diameter:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $dia; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>SSCC Grace Code:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo $grade; ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Warehouse Code:</b></font></td>
		<td><font size="2" face="Verdana"><?php echo getWarehouseCode($width, $book, $grade); ?></font></td>
	</tr>
</table>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Pending Orders:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Rolls Requested</b></font></td>
	</tr>
<?php
$sql = "SELECT
			bo.order_num,
			bod.qty_to_ship
		FROM booking_order_details bod
		LEFT JOIN booking_orders bo
			ON bo.order_num = bod.order_num
		WHERE
			bo.status NOT IN ('6', '7', '8')
			AND bod.p_o = '$po' 
			AND bod.booking_num = '$book' 
			AND bod.width = '$width' 
			AND bod.dia = '$dia' 
			AND bod.sscc_grade_code = '$grade'";
$ora_success = ora_parse($cursor, $sql);
$ora_success = ora_exec($cursor, $sql);
if (!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>None</b></font></td>
	</tr>
<?php
} else {
	do {
?>
	<tr>
		<td><font size="2" face="Verdana"><?php echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['QTY_TO_SHIP']; ?></font></td>
	</tr>
<?php
	} while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
}


function getWarehouseCode($width, $bookingNum, $gradeCode)
{
	global $cursor;
	$sql = "SELECT warehouse_code
			FROM booking_warehouse_code
			WHERE
				sscc_grade_code = '$gradeCode'
				AND width = '$width' * 10
				AND booking_num = '$bookingNum'";
	
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	
	return $row['WAREHOUSE_CODE'];
}