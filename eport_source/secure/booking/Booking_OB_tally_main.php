<?
	include("useful_info.php");
	include("get_employee_name.php");
	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$orders_cursor = ora_open($conn);
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];


	$submit = $_GET['submit'];
	$order_num = $_GET['order_num'];
	$date = $_GET['date'];
	$cust = $_GET['cust'];

	if($submit != "" && $order_num == "" && $date == ""){
		echo "<font color=\"#FF0000\">Either Order or Date must be entered.</font><br>";
		$submit = "";
	}

	if($submit != "" && $cust == ""){
		echo "<font color=\"#FF0000\">Please Enter a Customer #</font><br>";
		$submit = "";
	}

	if($testflag == "LIVE"){
		$headerprint = "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
	} else {
		$headerprint = "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left"><? echo $headerprint; ?>
	    <font size="5" face="Verdana" color="#0066CC">Booking Outbound Tally
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form name="get_data" action="" method="get">
<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana">Order:  </font></td>
		<td><input type="text" name="order_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">---OR---</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Date:  </font></td>
		<td><input type="text" name="date" size="10" maxlength="10"><a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
<?
	if($eport_customer_id != 0){
?>
		<input type="hidden" name="cust" value="<? echo $eport_customer_id; ?>">
<?
	} else {
?>
	<tr>
		<td><font size="2" face="Verdana">For Customer:&nbsp;</font></td>
		<td><select name="cust"><option value="all">All</option>
						<option value="314"<? if($cust == "314"){?> selected <?}?>>314</option>
						<option value="338"<? if($cust == "338"){?> selected <?}?>>338</option>
						<option value="517"<? if($cust == "517"){?> selected <?}?>>517</option>
				</select></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</table>
</form>
<?
	if($submit != ""){
		if($cust != "all"){
			$cust_sql = " AND CA.CUSTOMER_ID = '".$cust."' ";
		} else {
			$cust_sql = " ";
		}

		$sql = "SELECT DISTINCT ORDER_NUM
				FROM BOOKING_ORDERS CA
				WHERE 1 = 1".$cust_sql;
		if($order_num != ""){
			$sql .= " AND ORDER_NUM = '".$order_num."'";
		} else {
			$sql .= " AND TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') = '".$date."'";
		}
//		echo $sql;
		ora_parse($orders_cursor, $sql);
		ora_exec($orders_cursor);
		if(!ora_fetch_into($orders_cursor, $orders_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana"><b>Nothing matches Search Criteria.</b></font></td>
	</tr>
</table>
<?
		} else {
			$output = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
			do {

				$sql = "SELECT CONTAINER_ID, SEAL_NUM, BOOKING_NUM FROM BOOKING_ORDERS WHERE ORDER_NUM = '".$orders_row['ORDER_NUM']."'";
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);
				if(!ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$cont = "";
					$seal = "";
					$masterbook = "";
				} else {
					$cont = $row['CONTAINER_ID'];
					$seal = $row['SEAL_NUM'];
					$masterbook = $row['BOOKING_NUM'];
				}

				$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
						NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
						FROM CARGO_ACTIVITY CA
						WHERE ORDER_NUM = '".$orders_row['ORDER_NUM']."'".$cust_sql;
				ora_parse($short_term_data_cursor, $sql);
				ora_exec($short_term_data_cursor);
				if(!ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$start = "";
					$end = "";
				} else {
					$start = $row['START_TIME'];
					$end = $row['END_TIME'];
				}

				$output .= "<tr><td colspan=\"2\" align=\"left\"><font size=\"3\" face=\"Verdana\"><b>Order:  ".$orders_row['ORDER_NUM']."</b></font></td>
								<td colspan=\"10\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Master Booking #:  ".$masterbook."</b></td>
								<td colspan=\"2\" align=\"right\"><font size=\"3\" face=\"Verdana\"><b>Start Time:  ".$start."</b></font></td></tr>";
				$output .= "<tr><td colspan=\"2\" align=\"left\"><font size=\"3\" face=\"Verdana\"><b>Container:  ".$cont."</b></font></td>
								<td colspan=\"10\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Seal#:  ".$seal."</b>&nbsp;</td>
								<td colspan=\"2\" align=\"right\"><font size=\"3\" face=\"Verdana\"><b>End Time:  ".$end."</b></font></td></tr>";
				$output .= "<tr><td><font size=\"2\" face=\"Verdana\"><b>Barcode</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>QTY</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Warehouse Code</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Size (cm)</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>DIA (cm)</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Product</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Booking#</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Order</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Linear Meas</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Rec. Manifest</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>LB</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>KG</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>DMG</b></font></td>
								<td><font size=\"2\" face=\"Verdana\"><b>Checker</b></font></td></tr>";

				$total_rolls = 0;
				$total_lb = 0;
				$total_kg = 0;

//					EMPLOYEE PERS		AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)	 SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID THE_CHECKER
			$sql = "SELECT
						CT.PALLET_ID,
						QTY_CHANGE,
						ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR, 1) THE_WIDTH, 
						ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR, 1) THE_DIA,
						NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, 
						NVL(BOOKING_NUM, '--NONE--') THE_BOOK,
						CA.ARRIVAL_NUM, CA.CUSTOMER_ID,
						CA.ACTIVITY_NUM,
						BAD.ORDER_NUM,
						LENGTH || ' ' || LENGTH_MEAS THE_LINEAR,
						BAD.BOL THE_MANIFEST,
						ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB,
						ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG,
						DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN,
						bad.warehouse_code
					FROM
						CARGO_TRACKING CT,
						BOOKING_ADDITIONAL_DATA BAD,
						CARGO_ACTIVITY CA,
						BOOKING_PAPER_GRADE_CODE BPGC, 
						UNIT_CONVERSION_FROM_BNI UC1,
						UNIT_CONVERSION_FROM_BNI UC2,
						UNIT_CONVERSION_FROM_BNI UC3, 
						UNIT_CONVERSION_FROM_BNI UC4 
					WHERE
						CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CT.PALLET_ID = BAD.PALLET_ID
						AND CT.RECEIVER_ID = BAD.RECEIVER_ID
						AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
						AND CA.PALLET_ID = BAD.PALLET_ID
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
						AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM
						AND UC1.SECONDARY_UOM = 'CM'  
						AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM
						AND UC2.SECONDARY_UOM = 'CM'  
						AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM
						AND UC3.SECONDARY_UOM = 'LB'  
						AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM
						AND UC4.SECONDARY_UOM = 'KG'
						AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
						AND CA.SERVICE_CODE = '6' 
						AND CA.ACTIVITY_DESCRIPTION IS NULL
						$cust_sql
						AND CA.ORDER_NUM = '".$orders_row['ORDER_NUM']."'
					ORDER BY NVL(BOOKING_NUM, '--NONE--')";
				ora_parse($cursor, $sql);
				ora_exec($cursor);
				if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana"><b>No rolls currently on this order.</b></font></td>
	</tr>
</table>
<?
				} else {
					do {
						$emp_name = get_employee_for_print($row['PALLET_ID'], $row['ARRIVAL_NUM'], $row['CUSTOMER_ID'], $row['ACTIVITY_NUM'], $conn);
						//<td><font size=\"2\" face=\"Verdana\">".$row['THE_CHECKER']."</font></td>
						$output .= "<tr><td><font size=\"2\" face=\"Verdana\">".$row['PALLET_ID']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['QTY_CHANGE']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['WAREHOUSE_CODE']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_WIDTH']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_DIA']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_CODE']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_BOOK']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['ORDER_NUM']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_LINEAR']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_MANIFEST']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['WEIGHT_LB']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['WEIGHT_KG']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['DAMAGE_YN']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$emp_name."</font></td></tr>";
						$total_rolls++;
						$total_lb += $row['WEIGHT_LB'];
						$total_kg += $row['WEIGHT_KG'];
					} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

					$output .= "<tr><td><font size=\"2\" face=\"Verdana\"><b>Totals:</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>".$total_rolls."</b></font></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><font size=\"2\" face=\"Verdana\"><b>$total_lb</b></font></td>
									<td><font size=\"2\" face=\"Verdana\"><b>$total_kg</b></font></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>";
									

					$output .= "<tr><td colspan=\"8\">&nbsp;</td>
									<td><font size=\"2\" face=\"Verdana\">Tare Weight:</font></td>
									<td colspan=\"2\">&nbsp;</td>
									<td colspan=\"3\">&nbsp;</td>
								</tr>";
					$output .= "<tr><td colspan=\"8\">&nbsp;</td>
									<td><font size=\"2\" face=\"Verdana\"><b>Gross Weight:</b></font></td>
									<td colspan=\"2\">&nbsp;</td>
									<td colspan=\"3\">&nbsp;</td>
								</tr>";
					$output .= "<tr><td colspan=\"14\">&nbsp;</td></tr>";

					$output .= "<tr><td colspan=\"14\" align=\"left\"><font size=\"2\" face=\"Verdana\"><b>Outgoing Summary</b></font></td></tr>";

					$sql = "SELECT
								SUM(QTY_RECEIVED) THE_REC,
								ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, 
								ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA,
								NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, 
								NVL(BOOKING_NUM, '--NONE--') THE_BOOK,
								BAD.ORDER_NUM, BAD.BOL THE_MANIFEST, 
								SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB,
								SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG,
								bad.warehouse_code
							FROM
								CARGO_TRACKING CT,
								SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD,
								CARGO_ACTIVITY CA,
								BOOKING_PAPER_GRADE_CODE BPGC,
								UNIT_CONVERSION_FROM_BNI UC1,
								UNIT_CONVERSION_FROM_BNI UC2,
								UNIT_CONVERSION_FROM_BNI UC3,
								UNIT_CONVERSION_FROM_BNI UC4
							WHERE
								CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
								AND CT.PALLET_ID = BAD.PALLET_ID
								AND CT.RECEIVER_ID = BAD.RECEIVER_ID
								AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM
								AND CA.PALLET_ID = BAD.PALLET_ID
								AND CA.CUSTOMER_ID = BAD.RECEIVER_ID
								AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM
								AND UC1.SECONDARY_UOM = 'CM'  
								AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM
								AND UC2.SECONDARY_UOM = 'CM'  
								AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM
								AND UC3.SECONDARY_UOM = 'LB'  
								AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM
								AND UC4.SECONDARY_UOM = 'KG'
								AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+)
								AND CA.SERVICE_CODE = '6' 
								AND CA.ACTIVITY_DESCRIPTION IS NULL
								$cust_sql
								AND CA.ORDER_NUM = '".$orders_row['ORDER_NUM']."'
							GROUP BY
								ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR),
								ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR), 
								NVL(SSCC_GRADE_CODE, '--NONE--'),
								NVL(BOOKING_NUM, '--NONE--'),
								BAD.ORDER_NUM,
								BAD.BOL,
								bad.warehouse_code
							ORDER BY
								NVL(BOOKING_NUM, '--NONE--')";
					ora_parse($cursor, $sql);
					ora_exec($cursor);
					while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$output .= "<tr><td><font size=\"2\" face=\"Verdana\">&nbsp;</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_REC']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['WAREHOUSE_CODE']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_WIDTH']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_DIA']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_CODE']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_BOOK']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['ORDER_NUM']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">&nbsp;</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['THE_MANIFEST']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['WEIGHT_LB']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">".$row['WEIGHT_KG']."</font></td>
										<td><font size=\"2\" face=\"Verdana\">&nbsp;</font></td>
										<td><font size=\"2\" face=\"Verdana\">&nbsp;</font></td></tr>";
					}
					$output .= "<tr><td colspan=\"14\">&nbsp;</td></tr><tr><td colspan=\"14\">&nbsp;</td></tr>";

				} 
			} while(ora_fetch_into($orders_cursor, $orders_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			$output .= "</table>";

			$filename = "tempXLS/".$order_num."-".date('mdYhis').".xls";
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
