<?
	include("useful_info.php");
	include("get_employee_name.php");
	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];


	$submit = $_GET['submit'];
	$arv_num = $_GET['arv_num'];
	$cust = $_GET['cust'];
	$manifest = $_GET['manifest'];

	if($submit != "" && $arv_num == ""){
		echo "<font color=\"#FF0000\">Please Enter an Arrival #</font><br>";
		$submit = "";
	}

	if($submit != "" && $cust == ""){
		echo "<font color=\"#FF0000\">Please Enter a Customer #</font><br>";
		$submit = "";
	}

?>
<!--<script language="JavaScript" src="/functions/calendar.js"></script>!-->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Inbound Tally
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form action="" method="get">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana">Arrival #:  </font></td>
		<td><input type="text" name="arv_num" size="20" maxlength="20" value="<?php echo $arv_num; ?>"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Manifest# (optional):  </font></td>
		<td><input type="text" name="manifest" size="20" maxlength="20" value="<?php echo $manifest; ?>"></td>
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
						<option value="314"<? if ($cust == "314") {?> selected <? } ?>>314</option>
						<option value="338"<? if ($cust == "338") {?> selected <? } ?>>338</option>
						<option value="517"<? if ($cust == "517") {?> selected <? } ?>>517</option>
				</select></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="3" align="center"><button type="submit" name="submit" value="submit">Generate Report</button><hr></td>
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

		if($manifest != ""){
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD 
					WHERE CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM 
						AND CA.PALLET_ID = BAD.PALLET_ID 
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID 
						AND CA.ORDER_NUM = '".$arv_num."' 
						AND CA.ACTIVITY_NUM = '1' 
						".$cust_sql."
						AND BAD.BOL = '".$manifest."'";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($short_term_data_row['THE_COUNT'] <= 0){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana"><b>No rolls received from this Railcar/Manifest Combination.</b></font></td>
	</tr>
</table>
<?
				exit;
			} else {
				$manifest = $manifest;
			}
		} else {
			$sql = "SELECT MAX(DATE_OF_ACTIVITY) THE_DATE, BOL 
					FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD 
					WHERE CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM 
						AND CA.PALLET_ID = BAD.PALLET_ID 
						AND CA.CUSTOMER_ID = BAD.RECEIVER_ID 
						AND CA.ORDER_NUM = '".$arv_num."' 
						AND CA.ACTIVITY_NUM = '1'
						".$cust_sql."
					GROUP BY BOL 
					ORDER BY THE_DATE DESC";
			ora_parse($short_term_data_cursor, $sql);
			ora_exec($short_term_data_cursor);
			ora_fetch_into($short_term_data_cursor, $short_term_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$manifest = $short_term_data_row['BOL'];
		}


		$output = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		$output .= "<tr><td colspan=\"14\" align=\"center\"><font size=\"3\" face=\"Verdana\"><b>Arrival:  ".$arv_num."</b></font></td></tr>";
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

		// 					AND CA.DATE_OF_ACTIVITY >= (SYSDATE - 21)
//					EMPLOYEE PERS		AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)	 SUBSTR(EMPLOYEE_NAME, 0, 8) LOGIN_ID THE_CHECKER
		$sql = "SELECT
					CT.PALLET_ID,
					QTY_RECEIVED,
					ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, 
					ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA,
					NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, 
					NVL(BOOKING_NUM, '--NONE--') THE_BOOK,
					BAD.ORDER_NUM, LENGTH || ' ' || LENGTH_MEAS THE_LINEAR, 
					BAD.BOL THE_MANIFEST,
					ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB,
					ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG,
					DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN,
					CA.CUSTOMER_ID,
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
					AND BAD.BOL = '$manifest'
					AND CA.ACTIVITY_NUM = '1'
					$cust_sql
					AND CA.ORDER_NUM = '$arv_num'
				ORDER BY
					NVL(BOOKING_NUM, '--NONE--')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if (!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
<font size="3" face="Verdana"><b>No rolls received from this railcar in the last 21 days.</b></font>
<?
		} else {
			do {
				$emp_name = get_employee_for_print($row['PALLET_ID'], $arv_num, $row['CUSTOMER_ID'], "1", $conn);
				//<td><font size=\"2\" face=\"Verdana\">".$row['THE_CHECKER']."</font></td>
				$output .= "<tr><td><font size=\"2\" face=\"Verdana\">".$row['PALLET_ID']."</font></td>
								<td><font size=\"2\" face=\"Verdana\">".$row['QTY_RECEIVED']."</font></td>
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
							<td><font size=\"2\" face=\"Verdana\"><b>".$total_lb."</b></font></td>
							<td><font size=\"2\" face=\"Verdana\"><b>".$total_kg."</b></font></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td></tr>";
				
			$output .= "</table>";

			$filename = "tempXLS/".$arv_num."-".date('mdYhis').".xls";
			$fp = fopen($filename, "w");
			if (!$fp) {
				echo "can not open file for writing, please contact the PoW IT department";
				exit;
			}
			fwrite($fp, $output);
			fclose($fp);
?>
<font size="3" face="Verdana"><a href="<? echo $filename; ?>">Download Report</a></font>

<?
			echo $output;		

		}
	}
?>
