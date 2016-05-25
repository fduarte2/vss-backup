<?php
/*
*			Adam Walter, May 2010
*			This page allows OPS review Booking inventory
*			based on ARV# et. al.
******************************************************************/
/*
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Lookup";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if ($access_denied) {
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if ($conn < 1) {
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
*/
	include("useful_info.php");
	$cursor = ora_open($conn);

	$submit = $_GET['submit'];

	$timeframe = $_GET['timeframe'];
	$LR = $_GET['LR'];
	$cust = $_GET['cust'];
	if ($LR == "") {
		$LR = "all";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Railcar Lookup Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form name="select" action="" method="get">
	<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<?php
		if ($eport_customer_id != 0) {
	?>
			<input type="hidden" name="cust" value="<?php echo $eport_customer_id; ?>">
	<?php
		} else {
	?>
		<tr>
			<td><font size="2" face="Verdana">For Customer:&nbsp;</font>
			<select name="cust"><option value="all">All</option>
							<option value="314"<?php if ($cust == "314") { ?> selected <?php } ?>>314</option>
							<option value="338"<?php if ($cust == "338") { ?> selected <?php } ?>>338</option>
					</select></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td align="left"><font size="2" face="Verdana">Search History:</font>&nbsp;<select name="timeframe">
							<option value="twomonths">Unreceived/Shipped within last 2 months</option>
							<option value="all" <?php if ($timeframe == "all") { ?> selected <?php } ?>>All Time</option>
					</select></td>
			<td align="right"><font size="2" face="Verdana">Arrival # (optional):</font>&nbsp;
				<input type="text" name="LR" size="15" maxlength="15" value="<?php echo $LR; ?>"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><button type="submit" name="submit" value="get">Retrieve</button></td>
		</tr>
	</table>
</form>

<?php
	if ($submit != "") {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?php
		$sql = "SELECT
					CT.ARRIVAL_NUM,
					NVL(BAD.BOOKING_NUM, '---NONE---') THE_BOOK,
					BAD.ORDER_NUM,
					COUNT(*) THE_COUNT,
					BAD.BOL,
					BAD.SHIPFROMMILL,
					NVL(TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY'), '---NONE---') THE_REC,
					bad.warehouse_code
				FROM
					CARGO_TRACKING CT,
					BOOKING_ADDITIONAL_DATA BAD
				WHERE
					CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID";
		if ($timeframe == "twomonths") {
			$sql .= " AND (CT.QTY_IN_HOUSE > 0 OR CT.ARRIVAL_NUM IN
								(SELECT ARRIVAL_NUM FROM CARGO_ACTIVITY
								WHERE SERVICE_CODE = '6'";
			if ($cust == "all") {
				$sql .= "			AND CUSTOMER_ID IN ('314', '338') ";
			} else {
				$sql .= "			AND CUSTOMER_ID = '".$cust."' ";
			}

			$sql .= "				AND ACTIVITY_DESCRIPTION IS NULL
									AND DATE_OF_ACTIVITY > SYSDATE - 60
								)
						  )";
		}
		if ($LR != "all") {
			$sql .= " AND CT.ARRIVAL_NUM = '".$LR."'";
		}
		if ($cust != "all") {
			$sql .= " AND CT.RECEIVER_ID = '".$cust."'";
		}
		$sql .= " GROUP BY
					CT.ARRIVAL_NUM,
					NVL(BAD.BOOKING_NUM, '---NONE---'),
					BAD.ORDER_NUM,
					BAD.BOL,
					BAD.SHIPFROMMILL,
					bad.warehouse_code";
		$sql .= " ORDER BY
					CT.ARRIVAL_NUM,
					NVL(BAD.BOOKING_NUM, '---NONE---'),
					BAD.ORDER_NUM";
//echo $sql."<br>";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if (!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="red"><b>No Railcars matching selected criteria.</b></font></td>
	</tr>
<?php
		} else {
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Railcar</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking #</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse Code</b></font></td>
		<td><font size="2" face="Verdana"><b>#Rolls Expected</b></font></td>
		<td><font size="2" face="Verdana"><b>BOL</b></font></td>
		<td><font size="2" face="Verdana"><b>Mill</b></font></td>
		<td><font size="2" face="Verdana"><b>First Received</b></font></td>
	</tr>
<?php
			do {
?>
	<tr>
		<td><font size="2" face="Verdana">
				<a target="BookingARVLookupPopup.php?cust=<?php echo $cust; ?>&timeframe=<?php echo $timeframe; ?>&LR=<?php echo $row['ARRIVAL_NUM']; ?>"
					 href="BookingARVLookupPopup.php?cust=<?php echo $cust; ?>&timeframe=<?php echo $timeframe; ?>&LR=<?php echo $row['ARRIVAL_NUM']; ?>">
					<?php echo $row['ARRIVAL_NUM']; ?>
				</a>
			</font>
		</td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['WAREHOUSE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_COUNT']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['SHIPFROMMILL']; ?></font></td>
		<td><font size="2" face="Verdana"><?php echo $row['THE_REC']; ?></font></td>
	</tr>
<?php
			} while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?php
	}
?>