<?
/*
*			Adam Walter, May 2010
*			This page allows OPS review Booking inventory
*			based on Sales #s et. al.
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Lookup";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];

	$order_num = $HTTP_POST_VARS['sales'];
	$booking_num = $HTTP_POST_VARS['booking'];
	$received = $HTTP_POST_VARS['rec'];
	$shipped = $HTTP_POST_VARS['ship'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barnett *ONLY* (for now) Lookup Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="BookingLookup.php" method="post">
	<tr>
		<td><font size="2" face="Verdana">Sales Order #:</font>&nbsp;<select name="sales"><option value="">All</option>
<?
	$sql = "SELECT DISTINCT ORDER_NUM FROM BOOKING_ADDITIONAL_DATA WHERE ORDER_NUM IS NOT NULL ORDER BY ORDER_NUM";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['ORDER_NUM']; ?>"<? if($row['ORDER_NUM'] == $order_num){?> selected <?}?>><? echo $row['ORDER_NUM']; ?></option>
<?
	}
?>
				</select></td>
		<td><font size="2" face="Verdana">Booking #:</font>&nbsp;<select name="booking"><option value="">All</option>
<?
	$sql = "SELECT DISTINCT BOOKING_NUM FROM BOOKING_ADDITIONAL_DATA WHERE BOOKING_NUM IS NOT NULL ORDER BY BOOKING_NUM";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['BOOKING_NUM']; ?>"<? if($row['BOOKING_NUM'] == $booking_num){?> selected <?}?>><? echo $row['BOOKING_NUM']; ?></option>
<?
	}
?>
				</select></td>
		<td><font size="2" face="Verdana">Received?</font>&nbsp;<select name="rec"><option value="">All</option>
														<option value="rec" <? if($received == "rec"){?> selected<?}?>>Received</option>
														<option value="unrec" <? if($received == "unrec"){?> selected<?}?>>Not Received</option>
						</select></td>
		<td><font size="2" face="Verdana">Shipped?</font>&nbsp;<select name="ship"><option value="">All</option>
													<option value="ship" <? if($shipped == "ship"){?> selected<?}?>>Shipped</option>
														<option value="unship" <? if($shipped == "unship"){?> selected<?}?>>Not Shipped</option>
						</select></td>
	</tr>
	<tr>
		<td colspan="4" align="center"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
<?
		if($booking_num != "" || $order_num != ""){
			$html_string = "?test=a";
			if($booking_num != ""){
				$html_string .= "&booking_num=".$booking_num;
			}
			if($order_num != ""){
				$html_string .= "&order_num=".$order_num;
			}
			if($received != ""){
				$html_string .= "&received=".$received;
			}
			if($shipped != ""){
				$html_string .= "&shipped=".$shipped;
			}
?>
		<td align="center"><br><a target="BookingPlannerPopup.php<? echo $html_string; ?>" href="BookingPlannerPopup.php<? echo $html_string; ?>">Click Here for a Width Breakdown</a><br></td>
<?
		} else {
?>
		<td align="center"><br>To popup a Width Breakdown, you must specify a Booking# or Order#<br></td>
<?
		}
?>
	</tr>
</table>


<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>#rolls</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight (lbs)</b></font></td>
		<td><font size="2" face="Verdana"><b>Close Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Diameter</b></font></td>
		<td><font size="2" face="Verdana"><b>First Rec Date</b></font></td>
		<td><font size="2" face="Verdana"><b>First Loadout Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrival #</b></font></td>
	</tr>
<?
		$sql = "SELECT BAD.ORDER_NUM, NVL(BAD.BOOKING_NUM, 'NONE') THE_BOOK, COUNT(DISTINCT CT.PALLET_ID) THE_ROLLS,
				ROUND(SUM(CT.WEIGHT * UC1.CONVERSION_FACTOR)) THE_LBS, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA,
				TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_REC, CT.ARRIVAL_NUM
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2
				WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CT.WEIGHT_UNIT = UC1.PRIMARY_UOM
				AND UC1.SECONDARY_UOM = 'LB'
				AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM
				AND UC2.SECONDARY_UOM = 'IN'";
		if($order_num != ""){
			$sql .= " AND BAD.ORDER_NUM = '".$order_num."'";
		}
		if($booking_num != ""){
			$sql .= " AND BAD.BOOKING_NUM = '".$booking_num."'";
		}
		if($received == "rec"){
			$sql .= " AND CT.DATE_RECEIVED IS NOT NULL";
		} elseif($received == "unrec"){
			$sql .= " AND CT.DATE_RECEIVED IS NULL";
		}
		if($shipped == "ship"){
			$sql .= " AND CT.QTY_IN_HOUSE = 0";
		} elseif($shipped == "unship"){
			$sql .= " AND CT.QTY_IN_HOUSE > 0";
		}

		$sql .= " GROUP BY CT.ARRIVAL_NUM, BAD.ORDER_NUM, NVL(BAD.BOOKING_NUM, 'NONE'), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR)";
		$sql .= " ORDER BY BAD.ORDER_NUM, NVL(BAD.BOOKING_NUM, 'NONE'), CT.ARRIVAL_NUM";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="9" align="center"><font size="2" face="Verdana">No Rolls matching search criteria</font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_ROLLS']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_LBS']; ?></font></td>
		<td><font size="2" face="Verdana">Under Construction</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DIA']; ?>"</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_REC']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana">Under Construction</font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

	}
	include("pow_footer.php");
?>