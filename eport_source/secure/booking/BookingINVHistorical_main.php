<?
/*
*			Adam Walter, Jun 2010
*			This page allows OPS to view booking paper
*			Inventory up to a given date
******************************************************************/
/*
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Inventory";
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

*/
	$cursor = ora_open($conn);

	$book = $_GET['book'];
	$cust = $_GET['cust'];
	$date = $_GET['date'];
	$PO = $_GET['PO'];

	$submit = $_GET['submit'];

	if($submit != "" && $book == "" && $cust == "" && $date == "" && $PO == ""){
		echo "<font color=\"#FF0000\"> At least 1 search criteria must be entered</font><br>";
		$submit = "";
	}
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Paper Inventory
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form name="get_data" action="BookingINVHistorical.php" method="get">
	<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<?
		if($eport_customer_id != 0){
	?>
			<input type="hidden" name="cust" value="<? echo $eport_customer_id; ?>">
	<?
		} else {
	?>
		<tr>
			<td><font size="2" face="Verdana">For Customer:&nbsp;&nbsp;</font></td>
			<td><select name="cust"><option value="all">All</option>
							<option value="314"<? if($cust == "314"){?> selected <?}?>>314</option>
							<option value="338"<? if($cust == "338"){?> selected <?}?>>338</option>
					</select></td>
		</tr>
	<?
		}
	?>
		<tr>
			<td><font size="2" face="Verdana">BK#:  </font></td>
			<td><input type="text" name="book" size="20" maxlength="20" value="<?php echo $book; ?>"></td>
		</tr>
		<tr>
			<td><font size="2" face="Verdana">PO#:  </font></td>
			<td><input type="text" name="PO" size="12" maxlength="12" value="<?php echo $PO; ?>"></td>
		</tr>
		<tr>
			<td><font size="2" face="Verdana">As Of Beginning-Of-Day:  </font></td>
			<td><input type="text" name="date" size="10" maxlength="10" value="<?php echo $date; ?>"><a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="./show-calendar.gif" width=24 height=22 border=0></a></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><button type="submit" name="submit" value="get">Generate Report</button><hr></td>
		</tr>
	</table>
</form>
<?
	if($submit != ""){
		$output = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$output .= "<tr><td><font size=\"3\" face=\"Verdana\"><b>Customer</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>BK#</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>PO#</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>BOL (Manifest#)</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Warehouse Code</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Width</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Diameter</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Received</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Shipped</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Rolls In House</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>Weight In House (lbs)</b></font></td></tr>";

		$cust_total_rec = 0;
		$cust_total_ship = 0;
		$cust_total_IH = 0;
		$cust_total_reject = 0;
		$cust_total_wt_IH = 0;
		$grand_total_rec = 0;
		$grand_total_ship = 0;
		$grand_total_IH = 0;
		$grand_total_rej = 0;
		$grand_total_wt_IH = 0;

		$cur_cust = "";
//		$cur_BOL = "";
//		$cur_BK = "";
//		$cur_PO = "";
//		$cur_width = "";
//		$cur_dia = "";

/*					
						SUM(DECODE(CA.SERVICE_CODE, 6, 0, DECODE(DMG_LIST.DATE_CLEARED, NULL, 1, 0))) REJECT_OFF,
					AND CA_MAX.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA_MAX.PALLET_ID = BAD.PALLET_ID AND CA_MAX.CUSTOMER_ID = BAD.RECEIVER_ID
					AND CA.ARRIVAL_NUM(+) = CA_MAX.ARRIVAL_NUM AND CA.CUSTOMER_ID(+) = CA_MAX.CUSTOMER_ID AND CA.PALLET_ID(+) = CA_MAX.PALLET_ID
					AND CA.DATE_OF_ACTIVITY(+) = CA_MAX.THE_MAX
	                AND CA.SERVICE_CODE IN (6, 7, 11, 12, 13)
						AND CA.DATE_OF_ACTIVITY <=  TO_DATE('".$date."', 'MM/DD/YYYY')";

SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 1, 0)) THE_SHIPPED, -- Only count a roll as "shipped" if its latest activity was a 6
SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, 1)) THE_IH_ROLLS, -- Count a roll as in house if it's latest activity was NOT a 6.
SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, DECODE(DMG_LIST.MOST_RECENT_ENTRY_UNCLEARED, NULL, 0, 1))) REJECT_ON,
											-- if it's IH, see if it has an uncleared reject-damage entry
SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, ROUND(WEIGHT * UC3.CONVERSION_FACTOR))) THE_IH_WEIGHT 
											-- if it's IH, sum it's weight to total

sub-table CA_OUT_MAX grabs the latest "in-house status changing" activity record for all pallets
											(service codes 6, 7, 11, 12, 13 are the ones taht change in-house status)

sub-table DMG_LIST is a full record of all reject-level damage that either isn't cleared, or hasn't been cleared as of the entered date.
											(only grabs reject-daamges made up to the given date)

CA_OUT_MAX and DMG_LIST are ***LEFT JOINED*** to the rest of the SQL; if there isn't a damage/activity record for a given pallet, 
										we still want the pallet counted.
			
*/
		$sql = "SELECT CT.RECEIVER_ID, BAD.BOL THE_MANIFEST, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, BAD.ORDER_NUM,
					ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, 
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA,
					COUNT(DISTINCT CT.PALLET_ID) ROLLS_RECEIVED,
					SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 1, 0)) THE_SHIPPED,
					SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, 1)) THE_IH_ROLLS,
					SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, DECODE(DMG_LIST.MOST_RECENT_ENTRY_UNCLEARED, NULL, 0, 1))) REJECT_ON,
					SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, ROUND(WEIGHT * UC3.CONVERSION_FACTOR))) THE_IH_WEIGHT,
					bad.warehouse_code
                FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, 
					UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3,
					(SELECT CA.PALLET_ID, CA.CUSTOMER_ID, CA.ARRIVAL_NUM, CA.SERVICE_CODE
						FROM CARGO_ACTIVITY CA,
							   (SELECT PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM, MAX(DATE_OF_ACTIVITY) THE_MAX_OUT
							   FROM CARGO_ACTIVITY
							   WHERE SERVICE_CODE IN (6, 7, 11, 12, 13) ";
								if($date != ""){
									$sql .= "AND DATE_OF_ACTIVITY <= TO_DATE('".$date."', 'MM/DD/YYYY') ";
								}
				$sql .=		   "AND ACTIVITY_NUM != '1'
							   GROUP BY PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) CA_MAX
						WHERE CA.PALLET_ID = CA_MAX.PALLET_ID
						AND CA.CUSTOMER_ID = CA_MAX.CUSTOMER_ID
						AND CA.ARRIVAL_NUM = CA_MAX.ARRIVAL_NUM
						AND CA.DATE_OF_ACTIVITY = CA_MAX.THE_MAX_OUT
						AND CA.SERVICE_CODE IN (6, 7, 11, 12, 13)
						AND ACTIVITY_NUM != '1' ";
						if($date != ""){
							$sql .= "AND CA.DATE_OF_ACTIVITY <= TO_DATE('".$date."', 'MM/DD/YYYY') ";
						}
				$sql .=	"AND CA.PALLET_ID IN
							(SELECT PALLET_ID FROM CARGO_TRACKING
							WHERE REMARK = 'BOOKINGSYSTEM')) CA_OUT_MAX,
					(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM, MIN(DATE_ENTERED) MOST_RECENT_ENTRY_UNCLEARED
						FROM BOOKING_DAMAGES BD, PAPER_DAMAGE_CODES PDC
						WHERE PDC.DAMAGE_CODE = BD.DAMAGE_TYPE
						AND PDC.REJECT_LEVEL = 'REJECT'
						AND (DATE_CLEARED IS NULL ";
						if($date != ""){
							$sql .= "OR DATE_CLEARED >= TO_DATE('".$date."', 'MM/DD/YYYY')) ";
						} else {
							$sql .= "OR 1 = 2) ";
						}
		$sql .=			"GROUP BY PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) DMG_LIST
                WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID 
					AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  
					AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  
					AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'
					AND CT.DATE_RECEIVED IS NOT NULL
						AND CT.ARRIVAL_NUM = CA_OUT_MAX.ARRIVAL_NUM(+) 
						And CT.PALLET_ID = CA_OUT_MAX.PALLET_ID(+) 
						And CT.RECEIVER_ID = CA_OUT_MAX.CUSTOMER_ID(+) 
					AND CT.ARRIVAL_NUM = DMG_LIST.ARRIVAL_NUM(+) 
						And CT.PALLET_ID = DMG_LIST.PALLET_ID(+) 
						And CT.RECEIVER_ID = DMG_LIST.RECEIVER_ID(+) ";
		if($cust != "all"){
			$sql .= "AND CT.RECEIVER_ID = '".$cust."' ";
		}
		if($book != ""){
			$sql .= "AND BAD.BOOKING_NUM = '".$book."' ";
		}
		if($PO != ""){
			$sql .= "AND BAD.ORDER_NUM = '".$PO."' ";
		}
		if($date != ""){
			$sql .= "AND CT.DATE_RECEIVED <= TO_DATE('".$date."', 'MM/DD/YYYY') 
						AND DMG_LIST.MOST_RECENT_ENTRY_UNCLEARED(+) <= TO_DATE('".$date."', 'MM/DD/YYYY')";
		}

		$sql .= "GROUP BY CT.RECEIVER_ID, BAD.BOL, NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM,
					ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1), 
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1),
					bad.warehouse_code
				HAVING SUM(DECODE(NVL(CA_OUT_MAX.SERVICE_CODE, 7), 6, 0, 1)) > 0
                ORDER BY CT.RECEIVER_ID, BAD.ORDER_NUM, NVL(BOOKING_NUM, '--NONE--'), BAD.BOL, 
					ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1), 
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1)";
//		echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana"><b>No in-house rolls for the search criteria given.</b></font></td>
	</tr>
</table>
<?
		} else {
			do {
				if($row['RECEIVER_ID'] != $cur_cust){ // new customer subsection
					if($cust_total_rec != 0){ // this is NOT the first iteration, where we only want the customer displayed
						if($cust_total_reject > 0){
							$sub_info = "<br>  -- ".$cust_total_reject." Rejects";
						} else {
							$sub_info = "";
						}
						$output .= "<tr bgcolor=\"#79BEB4\">
									<td colspan=\"7\"><font size=\"2\" face=\"Verdana\"><b>".$cur_cust."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_rec."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_ship."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_IH.$sub_info."</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_wt_IH."</b></font></td>
									</tr>";
						$cust_total_rec = 0;
						$cust_total_ship = 0;
						$cust_total_IH = 0;
						$cust_total_reject = 0;
						$cust_total_wt_IH = 0;
					}

					// print out customer heading
					$cur_cust = $row['RECEIVER_ID'];
					$output .= "<tr bgcolor=\"#9E6CA7\"><td colspan=\"11\"><font size=\"2\" face=\"Verdana\"><b>".$cur_cust."</b></font></td></tr>";
				}

				if($row['REJECT_ON'] > 0){
					$sub_info = "<br>  -- ".$row['REJECT_ON']." Rejects";
				} else {
					$sub_info = "";
				}					
				$output .= "<tr><td colspan=\"2\"><font size=\"2\" face=\"Verdana\">".$row['THE_BOOK']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['ORDER_NUM']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_MANIFEST']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['WAREHOUSE_CODE']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_WIDTH']."cm/ ".round($row['THE_WIDTH'] / 2.54, 1)."\"</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_DIA']."cm/ ".round($row['THE_DIA'] / 2.54, 1)."\"</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['ROLLS_RECEIVED']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_SHIPPED']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_IH_ROLLS'].$sub_info."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['THE_IH_WEIGHT']."</font></td></tr>";
				$cust_total_rec += $row['ROLLS_RECEIVED'];
				$cust_total_ship += $row['THE_SHIPPED'];
				$cust_total_IH += $row['THE_IH_ROLLS'];
				$cust_total_reject += $row['REJECT_ON'];
				$cust_total_wt_IH += $row['THE_IH_WEIGHT'];
				$grand_total_rec += $row['ROLLS_RECEIVED'];
				$grand_total_ship += $row['THE_SHIPPED'];
				$grand_total_IH += $row['THE_IH_ROLLS'];
				$grand_total_reject += $row['REJECT_ON'];
				$grand_total_wt_IH += $row['THE_IH_WEIGHT'];

				
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			if($cust_total_reject > 0){
				$sub_info = "<br>  -- ".$cust_total_reject." Rejects";
			} else {
				$sub_info = "";
			}
			$output .= "<tr bgcolor=\"#79BEB4\">
						<td colspan=\"7\"><font size=\"2\" face=\"Verdana\"><b>".$cur_cust."</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_rec."</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_ship."</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_IH.$sub_info."</b></font></td>
						<td><font size=\"2\" face=\"Verdana\"><b>".$cust_total_wt_IH."</b></font></td>
						</tr>";
			if($grand_total_reject > 0){
				$sub_info = "<br>  -- ".$grand_total_reject." Rejects";
			} else {
				$sub_info = "";
			}
			$output .= "<tr bgcolor=\"#57E1CD\">
						<td colspan=\"7\"><font size=\"3\" face=\"Verdana\"><b>Grand Total</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>".$grand_total_rec."</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>".$grand_total_ship."</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>".$grand_total_IH.$sub_info."</b></font></td>
						<td><font size=\"3\" face=\"Verdana\"><b>".$grand_total_wt_IH."</b></font></td>
						</tr>";



			$output .= "</table>";

			$filename = "tempXLS/BookingINVHistorical".date('mdYhis').".xls";
			$fp = fopen($filename, "w");
			if(!$fp){
				echo "can not open file for writing, please contact the PoW IT department";
				exit;
			}
			fwrite($fp, $output);
			fclose($fp);
?>
<font size="3" face="Verdana"><b><a href="<? echo $filename; ?>">Download Report</a></b></font>

<?
			echo $output;		

		}
	}
?>
