<?
/*
*			Adam Walter, June 2010
*			Pages gives Barnett Inventory as of
*			The exact time page is loaded.
******************************************************************/
/*
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

//	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
*/
	$cursor = ora_open($conn);
	$short_term_data = ora_open($conn);
	$cust_cursor = ora_open($conn);
	if($eport_customer_id == 0){
		$cust = $_GET['cust'];
	} else {
		$cust = $eport_customer_id;
	}

	if($testflag == "LIVE"){
		$headerprint = "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
	} else {
		$headerprint = "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left"><? echo $headerprint; ?>
	    <font size="5" face="Verdana" color="#0066CC">Booking Paper REAL TIME Inventory
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>
<?
	if($eport_customer_id == 0){
?>
<form name="filter" action="BookingRIGHTNOW.php" method="get">
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
	<tr>
		<td colspan="10" align="center"><font size="3" face="Verdana">Inventory As Of:  <? echo date('m/d/Y h:i:s'); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Booking#</b></font></td>
		<td><font size="2" face="Verdana"><b>Inbound PO</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Grade Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Width</b></font></td>
		<td><font size="2" face="Verdana"><b>Dia</b></font></td>
		<td><font size="2" face="Verdana"><b>AVG weight (lbs)</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Pending Orders</b></font></td>
	</tr>

<?
		$total_IH = 0;
		$total_IH_reject = 0;
		$total_IH_hold = 0;

		$cust_sql = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE
					WHERE CUSTOMER_ID IN ('314', '338', '517')";
		if($cust != "all"){
			$cust_sql .= " AND CUSTOMER_ID = '".$cust."' ";
		} 
		$cust_sql .= " ORDER BY CUSTOMER_ID ";
		$ora_success = ora_parse($cust_cursor, $cust_sql);
		$ora_success = ora_exec($cust_cursor, $cust_sql);
		while(ora_fetch_into($cust_cursor, $cust_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

			$sql = "SELECT CT.RECEIVER_ID, COUNT(DISTINCT CT.PALLET_ID) THE_COUNT, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, SSCC_GRADE_CODE,
						ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, BAD.ORDER_NUM, 
						ROUND(AVG(WEIGHT * UC3.CONVERSION_FACTOR), 1) WEIGHT_LB, ROUND(AVG(WEIGHT * UC4.CONVERSION_FACTOR), 1) WEIGHT_KG,
						SUM(DECODE(CARGO_STATUS, NULL, 0, 1)) THE_HOLDS, 
							COUNT(
								(SELECT DISTINCT PALLET_ID 
								FROM BOOKING_DAMAGES BD 
								WHERE CT.PALLET_ID = BD.PALLET_ID
								AND CT.ARRIVAL_NUM = BD.ARRIVAL_NUM
								AND CT.RECEIVER_ID = BD.RECEIVER_ID
								AND DAMAGE_TYPE LIKE 'R%'
								AND DATE_CLEARED IS NULL)) THE_REJECTS,
						bad.warehouse_code
					FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, 
						UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4, BOOKING_PAPER_GRADE_CODE BPGC
					WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID 
						AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' 
						AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' 
						AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB' 
						AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG'
						AND CT.DATE_RECEIVED IS NOT NULL
						AND CT.QTY_IN_HOUSE > 0
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE
						AND CT.RECEIVER_ID = '".$cust_row['CUSTOMER_ID']."'
					GROUP BY CT.RECEIVER_ID, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1), SSCC_GRADE_CODE,
						NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM,
						bad.warehouse_code
					ORDER BY CT.RECEIVER_ID, NVL(BOOKING_NUM, '--NONE--'), ORDER_NUM, SSCC_GRADE_CODE, THE_WIDTH, THE_DIA";
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor, $sql);
			if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td align="center" colspan="10"><font size="2" face="Verdana" color="#FF0000"><b>No Booking Paper Rolls currently in house for customer <? echo $cust_row['CUSTOMER_ID']; ?>.</b></font></td>
	</tr>
<?
			} else {
				do {
					$sql = "SELECT SUM(QTY_TO_SHIP) TO_SHIP 
							FROM BOOKING_ORDER_DETAILS BOD, BOOKING_ORDERS BO 
							WHERE BO.ORDER_NUM = BOD.ORDER_NUM 
								AND BO.STATUS NOT IN ('6', '7', '8')
								AND BOD.P_O = '".$row['ORDER_NUM']."' 
								AND BOD.BOOKING_NUM = '".$row['THE_BOOK']."' 
								AND BOD.WIDTH = '".$row['THE_WIDTH']."' 
								AND BOD.DIA = '".$row['THE_DIA']."' 
								AND BOD.SSCC_GRADE_CODE = '".$row['SSCC_GRADE_CODE']."'";
					$ora_success = ora_parse($short_term_data, $sql);
					$ora_success = ora_exec($short_term_data, $sql);
					ora_fetch_into($short_term_data, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$pending = $short_term_row['TO_SHIP'];

					$total_IH += $row['THE_COUNT'];


					if($row['THE_HOLDS'] <= 0){
						$hold_data = "";
					} else {
						$total_IH_hold += $row['THE_HOLDS'];
						$hold_data = "<br>(".$row['THE_HOLDS']." on hold)";
					}
					if($row['THE_REJECTS'] <= 0){
						$reject_print = "";
					} else {
						$total_IH_reject += $row['THE_REJECTS'];
						$reject_print = "<br>--- ".$row['THE_REJECTS']." Reject";
					}
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_BOOK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ORDER_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WAREHOUSE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['SSCC_GRADE_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_WIDTH']; ?>cm/<? echo round($row['THE_WIDTH'] / 2.54, 1); ?>"</font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DIA']; ?>cm/<? echo round($row['THE_DIA'] / 2.54, 1); ?>"</font></td>
		<td><font size="2" face="Verdana"><? echo $row['WEIGHT_LB']; ?></font></td>
		<td><font size="2" face="Verdana"><a href="BookingRIGHTNOWdetails.php?cust=<? echo $row['RECEIVER_ID']; ?>&book=<? echo $row['THE_BOOK']; ?>&order=<? echo $row['ORDER_NUM']; ?>&width=<? echo $row['THE_WIDTH']; ?>&dia=<? echo $row['THE_DIA']; ?>&avgwt=<? echo $row['WEIGHT_LB']; ?>&count=<? echo $row['THE_COUNT']; ?>&grade=<? echo $row['SSCC_GRADE_CODE']; ?>" ><? echo $row['THE_COUNT'].$hold_data; ?></a><? echo $reject_print; ?></font></td>
		<td><font size="2" face="Verdana"><a href="BookingRIGHTNOWorderpendingdetails.php?&book=<? echo $row['THE_BOOK']; ?>&po=<? echo $row['ORDER_NUM']; ?>&width=<? echo $row['THE_WIDTH']; ?>&dia=<? echo $row['THE_DIA']; ?>&grade=<? echo $row['SSCC_GRADE_CODE']; ?>" ><? echo (0 + $pending); ?></a></font></td>
	</tr>
<?
				} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="8"><font size="2" face="Verdana"><b>Total for Customer <? echo $cust_row['CUSTOMER_ID']; ?>:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_IH;
									if($total_IH_hold > 0){ echo "<br>(".$total_IH_hold." on hold)"; }
									if($total_IH_reject > 0){ echo "<br>--- ".$total_IH_reject." Reject"; } ?>
						</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
			}
		}
?>
</table>
<?
	}
?>