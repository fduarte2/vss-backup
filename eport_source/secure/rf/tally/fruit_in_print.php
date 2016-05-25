<?
/*
*	Note:  this program mimics the logic found in the tally print
*	Routine of Tampa in /home/kiosk/fruit_tally_in.php
*
*****************************************************************************/
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}
		
	$order = $HTTP_POST_VARS['order'];
	$cust = $HTTP_POST_VARS['cust'];

	if($order == "" || $cust == ""){
		echo "An Order# must be entered";
		exit;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND CUSTOMER_ID = '".$cust."' AND ACTIVITY_NUM = '1'";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	if(ociresult($Short_term_data, "THE_COUNT") <= 0){
		echo "entered Order# (".$order.") is not a valid Chilean Inbound Order# for Customer# ".$cust;
		exit;
	}

	// alright, so this order exists...
	$sql = "SELECT NVL(TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') START_TIME,
			NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM'), '') END_TIME
			FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."' AND ACTIVITY_NUM = '1' AND CUSTOMER_ID = '".$cust."'";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	if(!ocifetch($Short_term_data)){
		$start = "";
		$end = "";
	} else {
		$start = ociresult($Short_term_data, "START_TIME");
		$end = ociresult($Short_term_data, "END_TIME");
	}

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	$custname = ociresult($Short_term_data, "CUSTOMER_NAME");

	$sql = "SELECT PALLET_ID, QTY_CHANGE, ACTIVITY_NUM, SERVICE_CODE, ARRIVAL_NUM
			FROM CARGO_ACTIVITY
			WHERE ORDER_NUM = '".$order."' AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '1' AND ACTIVITY_DESCRIPTION IS NULL";
	$pallets = ociparse($rfconn, $sql);
	ociexecute($pallets);
	if(!ocifetch($pallets)){
		// should never happen, but
		echo "entered Order# (".$order.") is not a valid Chilean Inbound Order# for Customer# ".$cust;
		exit;
	} else {
?>
		<table>
			<tr>
				<td><font size="1" face="Verdana">Generated from Eport - /rf/fruit_in_print.php             Printed on <? echo date('m/d/Y h:i:s a'); ?><font></td>
			</tr>
			<tr>
				<td><font size="1" face="Verdana">PORT OF WILMINGTON CHILEAN FRUIT INBOUND TALLY<font></td>
			</tr>
			<tr>
				<td><font size="1" face="Verdana">Customer: <? echo $custname; ?><font></td>
			</tr>
			<tr>
				<td><font size="1" face="Verdana">Order#: <? echo $order; ?><font></td>
			</tr>
<?
		if($pallet_row['SERVICE_CODE'] != "11"){
?>
			<tr>
				<td><font size="1" face="Verdana">SHIPPING TYPE:  INBOUND<font></td>
			</tr>
<?
		} else {
?>
			<tr>
				<td><font size="1" face="Verdana">SHIPPING TYPE:  INBOUND - TRANSFER<font></td>
			</tr>
<?
		}
?>
			<tr>
				<td><font size="1" face="Verdana">First Scan: <? echo $start; ?><font></td>
			</tr>
			<tr>
				<td><font size="1" face="Verdana">Last Scan: <? echo $end; ?><font></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>

		<table>
			<tr>
				<td><font size="1" face="Verdana"><b>BARCODE</b></font></td>
				<td><font size="1" face="Verdana"><b>DESCRIPTION</b></font></td>
				<td><font size="1" face="Verdana"><b>QTY</b></font></td>
				<td><font size="1" face="Verdana"><b>CHECKER</b></font></td>
			</tr>
<?
		do {
			$emp_name = get_employee_for_print(ociresult($pallets, "PALLET_ID"), ociresult($pallets, "ARRIVAL_NUM"), $cust, ociresult($pallets, "ACTIVITY_NUM"), $rfconn);
			$total_ctn += ociresult($pallets, "QTY_CHANGE");
			$total_plt++;
?>
			<tr>
				<td><font size="1" face="Verdana"><? echo ociresult($pallets, "PALLET_ID"); ?></font></td>
				<td><font size="1" face="Verdana"><? echo GetPalletDesc(ociresult($pallets, "PALLET_ID"), ociresult($pallets, "ARRIVAL_NUM"), $cust, $swingflag, $rfconn); ?></font></td>
				<td><font size="1" face="Verdana"><? echo ociresult($pallets, "QTY_CHANGE"); ?></font></td>
				<td><font size="1" face="Verdana"><? echo $emp_name; ?></font></td>
			</tr>
<?
		} while(ocifetch($pallets));

		// outbound has a whole list of criteria for being counted on the totals.  Inbound is just 1 per pallet.
?>
			<tr>
				<td colspan="2" align="right"><font size="1" face="Verdana">Total Cases:</font></td>
				<td><font size="1" face="Verdana"><? echo $total_ctn; ?></font></td>
				<td colspan="1">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="right"><font size="1" face="Verdana">Total Pallets:</font></td>
				<td><font size="1" face="Verdana"><? echo $total_plt; ?></font></td>
				<td colspan="1">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4"><font size="1" face="Verdana">**Some, Part, or all of this cargo may have been fumigated with Methyl Bromide.**</td>
			</tr>
			<tr>
				<td colspan="4"><font size="1" face="Verdana">**Esta carga o parte de ella pudo haber sido fumigada con Bromuro de Metilo.**</td>
			</tr>
		</table>
<?
	}







function GetPalletDesc($pallet, $vessel, $cust, &$swingflag, $rfconn){

	$return = "";

	$sql = "SELECT COMMODITY_NAME, DECODE(VARIETY, NULL, '', ' - ' || VARIETY) THE_VAR, 
				DECODE(REMARK, NULL, '', ' - ' || REMARK) THE_REM, 
				DECODE(CARGO_SIZE, NULL, '', ' - ' || CARGO_SIZE) THE_SIZE, 
				NVL(WAREHOUSE_LOCATION, 'NONE') THE_WHS,
				NVL(CONTAINER_ID, 'Not Specified.') THE_CONT
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE PALLET_ID = '".$pallet."' AND RECEIVER_ID = '".$cust."' AND ARRIVAL_NUM = '".$vessel."'
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE";
//	echo $sql."\n";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$sql = "SELECT WDI_RECEIVING_PO, DECODE(WDI_GROWER_CODE, NULL, ' - GRO N/A', ' - GRO ' || WDI_GROWER_CODE) THE_GROW, 
				DECODE(WDI_OUTGOING_ITEM_NUM, NULL, ' - ITM# N/A', ' - ITM# ' || WDI_OUTGOING_ITEM_NUM) THE_ITEM 
			FROM WDI_ADDITIONAL_DATA 
			WHERE WDI_PALLET_ID = '".$pallet."' 
				AND WDI_RECEIVER_ID = '".$cust."' 
				AND WDI_ARRIVAL_NUM = '".$vessel."'";
	$short_term_data2 = ociparse($rfconn, $sql);
	ociexecute($short_term_data2);
	if(ocifetch($short_term_data2) && ociresult($short_term_data2, "WDI_RECEIVING_PO") != ""){
		$return = ociresult($short_term_data, "COMMODITY_NAME")." - ".ociresult($short_term_data, "THE_VAR")." - ".
			ociresult($short_term_data2, "THE_GROW")." - BK# ".ociresult($short_term_data2, "WDI_RECEIVING_PO").ociresult($short_term_data2, "THE_ITEM").
			ociresult($short_term_data, "THE_REM")." - ".ociresult($short_term_data, "THE_SIZE");
	} else {
		$return = ociresult($short_term_data, "COMMODITY_NAME").ociresult($short_term_data, "THE_VAR").ociresult($short_term_data, "THE_REM").ociresult($short_term_data, "THE_SIZE");
	}

	// NOT USED IN INBOUND TALLY
	if(ociresult($short_term_data, "THE_WHS") == "SW"){
		$swingflag = ociresult($short_term_data, "THE_CONT");
	}

	return $return;
}

function get_employee_for_print($Barcode, $LR, $cust, $act_num, $rfconn){
	$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID
			FROM CARGO_ACTIVITY
			WHERE PALLET_ID = '".$Barcode."'
				AND ARRIVAL_NUM = '".$LR."'
				AND CUSTOMER_ID = '".$cust."'
				AND ACTIVITY_NUM = '".$act_num."'";
//	echo $sql."<br>";
//	fscanf(STDIN, "%s\n", $junk);
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);

	$date = ociresult($short_term_data, "THE_DATE");
	$emp_no = ociresult($short_term_data, "ACTIVITY_ID");

	if($emp_no == ""){
		return "UNKNOWN";
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE
			WHERE CHANGE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')";
//	echo $sql."\n";
//	fscanf(STDIN, "%s\n", $junk);
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);
	if(ociresult($main_data, "THE_COUNT") < 1){
		$sql = "SELECT LOGIN_ID THE_EMP
				FROM PER_OWNER.PERSONNEL
				WHERE EMPLOYEE_ID = '".$emp_no."'";
	} else {
//		return $emp_no;
		while(strlen($emp_no) < 5){
			$emp_no = "0".$emp_no;
		}
		$sql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP
				FROM EMPLOYEE
				WHERE SUBSTR(EMPLOYEE_ID, -".strlen($emp_no).") = '".$emp_no."'"; 
	}
//	echo $sql."\n";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	ocifetch($main_data);

	return ociresult($main_data, "THE_EMP");
}
