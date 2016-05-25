<?
 /*
*			Adam Walter, May 2010
*			This page allows OPS review inbound activity
******************************************************************/
 // All POW files need this session file included
  include("pow_session.php");

/* 
  // Define some vars for the skeleton page
  $title = "Booking Activity viewer (Inbound)";
  $area_type = "INVE";
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
*/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$main_cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);


	$hide = $HTTP_POST_VARS['hide'];
	$date_from = $HTTP_POST_VARS['date_from'];
	$date_to = $HTTP_POST_VARS['date_to'];
	$cust = $HTTP_POST_VARS['cust'];
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<?
	if($hide != "hide" || ($date_from == "" && $date_to == "")){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="Booking_OB_summary.php" method="post">
	<tr>
		<td align="center" colspan="2"><font size="4" face="Verdana"><b>Outbound Summary</b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">Please enter one or more of the following:</font></td>
	</tr>
	<tr>
		<td width="15%" align="left">Ship Date (from):</td>
		<td><input name="date_from" type="text" size="15" maxlength="15" value="<? echo $date_from; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.date_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left">Ship Date (to):</td>
		<td><input name="date_to" type="text" size="15" maxlength="15" value="<? echo $date_to; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('the_form.date_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left">Customer:</td>
		<td><select name="cust"><option value="All">All</option>
								<option value="314">314"</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="checkbox" value="hide" name="hide" checked>&nbsp;&nbsp;Check here to hide the search boxes (useful for printing)</td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="orders" value="Generate Report"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<hr>&nbsp;</td>
	</tr>
</form>
</table>
<?
	}
	if($date_from != "" || $date_to != ""){
?>
<table border="0" width="60%" cellpadding="4" cellspacing="0">
	<tr><td align="center"><font size="4" face="Verdana"><b>Outbound Summary</b></font></td></tr>
</table>
<?
/*		$sql = "SELECT DATE_OF_ACTIVITY, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY H24:MI:SS') ACT_DATE, CT.ARRIVAL_NUM, 
					NVL(BOOKING_NUM, '--NONE--') THE_BOOK, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, 
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, BAD.ORDER_NUM THE_PO
					COUNT(DISTINCT PALLET_ID) THE_ROLLS,
					SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB, SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, 
					UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2,
					UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4
				WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
				AND CA.ACTIVITY_NUM = '1' ";
*/

		$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') ACT_DATE, CA.ORDER_NUM CA_ORD,
					COUNT(DISTINCT CT.PALLET_ID) THE_ROLLS, BAD.ORDER_NUM BAD_ORD, BAD.BOOKING_NUM,
					SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB, SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, 
					UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4
				WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
				AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
				AND CA.SERVICE_CODE = '6'
				AND CT.REMARK = 'BOOKINGSYSTEM'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION != 'VOID')";
				
		if($date_from != ""){
			$sql .= "AND DATE_OF_ACTIVITY >= TO_DATE('".$date_from."', 'MM/DD/YYYY') ";
		}
		if($date_to != ""){
			$sql .= "AND DATE_OF_ACTIVITY <= TO_DATE('".$date_to." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
		}
		$sql .= "GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), BAD.ORDER_NUM, BAD.BOOKING_NUM, CA.ORDER_NUM
				ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY'), BAD.ORDER_NUM, CA.ORDER_NUM, BAD.BOOKING_NUM";
//		echo $sql;
		ora_parse($main_cursor, $sql);
		ora_exec($main_cursor);
		if(!ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<hr><font color=\"#ff0000\">No Outbound Activity matches the entered criteria.</font>";
		} else {
			$current_date = $row['ACT_DATE'];
	//		$customer = $row['THE_NAME'];
			$date_total_rolls = 0;
			$date_total_weight_lb = 0;
			$date_total_weight_kg = 0;
			$grand_total_rolls = 0;
			$grand_total_weight_lb = 0;
			$grand_total_weight_kg = 0;
?>
<table border="1" width="60%" cellpadding="4" cellspacing="0">
	<tr>
		<td><b>Date</b></td>
		<td><b>OB Order#</b></td>
		<td><b>PO#</b></td>
		<td><b>Booking #</b></td>
		<td><b>Rolls</b></td>
		<td><b>LB</b></td>
		<td><b>KG</b></td>
	</tr>
	<tr>
		<td colspan="7"><b><? echo $current_date; ?></b></td>
	</tr>
<?
			do {
				if($current_date != $row['ACT_DATE']){
?>	
	<tr>
		<td colspan="4"><b>Total:</b></td>
		<td><? echo $date_total_rolls; ?></td>
		<td><? echo $date_total_weight_lb; ?> </td>
		<td><? echo $date_total_weight_kg; ?> </td>
	</tr>
<?
				$current_date = $row['ACT_DATE'];
				$date_total_rolls = 0;
				$date_total_weight_lb = 0;
				$date_total_weight_kg = 0;
?>
	<tr>
		<td colspan="7"><b><? echo $current_date; ?></b></td>
	</tr>
<?
		}
?>	
	<tr>
		<td>&nbsp;</td>
		<td><? echo $row['CA_ORD']; ?></td>
		<td><? echo $row['BAD_ORD']; ?></td>
		<td><? echo $row['BOOKING_NUM']; ?></td>
		<td><? echo $row['THE_ROLLS']; ?></td>
		<td><? echo $row['WEIGHT_LB']; ?></td>
		<td><? echo $row['WEIGHT_KG']; ?></td>
	</tr>

<?
				$date_total_rolls += $row['THE_ROLLS'];
				$grand_total_rolls += $row['THE_ROLLS'];
				$date_total_weight_lb += $row['WEIGHT_LB'];
				$date_total_weight_kg += $row['WEIGHT_KG'];
				$grand_total_weight_lb += $row['WEIGHT_LB'];
				$grand_total_weight_kg += $row['WEIGHT_KG'];

			} while(ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="4">Total:</td>
		<td><? echo $date_total_rolls; ?></td>
		<td><? echo $date_total_weight_lb; ?> </td>
		<td><? echo $date_total_weight_kg; ?> </td>
	</tr>
	<tr>
		<td colspan="4"><b>Grand Total:</b></td>
		<td><? echo $grand_total_rolls; ?></td>
		<td><? echo $grand_total_weight_lb; ?> </td>
		<td><? echo $grand_total_weight_kg; ?> </td>
	</tr>
<?
		}
	}
?>