<?
/*
*	Jul-Aug 2013
*
*	Generates an XLS of an order entry for Morocan Clementine program.
***********************************************************************/

//	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$order = "DC401";
	$cust = $HTTP_COOKIE_VARS['eport_customer_id'];

	$sql = "SELECT VESSEL_NAME, TO_CHAR(PARSETABORDER, 'MM/DD/YYYY') ETA_DATE, MCOMM.DC_COMMODITY_NAME COMMNAME, MCUST.CUSTOMERNAME CUSTNAME,
				TO_CHAR(PICKUPDATE, 'MM/DD/YYYY') THE_PICKUP, MCONS.CONSIGNEENAME, MO.DIRECTORDER, MO.CUSTOMERPO, TO_CHAR(DELIVERYDATE, 'MM/DD/YYYY') THE_DELIVERY
			FROM MOR_ORDER MO, MOR_COMMODITY MCOMM, MOR_CUSTOMER MCUST, MOR_CONSIGNEE MCONS, VESSEL_PROFILE VP
			WHERE ORDERNUM = '".$order."'
				AND MO.CUST = '".$cust."'
				AND MO.VESSELID = VP.LR_NUM
				AND MO.COMMODITYCODE = MCOMM.PORT_COMMODITY_CODE
				AND MO.CUSTOMERID = MCUST.CUSTOMERID
				AND MO.CONSIGNEEID = MCONS.CONSIGNEEID";
	$stid = ociparse($conn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$vesname = ociresult($stid, "VESSEL_NAME");
	$eta = ociresult($stid, "ETA_DATE");
	$commname = ociresult($stid, "COMMNAME");
	$custname = ociresult($stid, "CUSTNAME");
	$pickup = ociresult($stid, "THE_PICKUP");
	$consname = ociresult($stid, "CONSIGNEENAME");
	$ordertype = ociresult($stid, "DIRECTORDER");
	$PO = ociresult($stid, "CUSTOMERPO");
	$delivery_date = ociresult($stid, "THE_DELIVERY");

	// next, populate an array with the sizes of the fruit.
	$size_id = array();
	$size_desc = array();
	$array_maxcount = 1;
	$sql = "SELECT * FROM MOR_COMMODITYSIZE
			WHERE CUST = '".$cust."'
				AND ORDER_ENT_EXCEL_COL IS NOT NULL
			ORDER BY ORDER_ENT_EXCEL_COL";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	if(!ocifetch($short_term_data)){
		CreateFile("No commodity sizes found in the Commodity Size table for the currently logged in customer.  Cannot print Entry Form.", $order);
	} else {
		do {
			while(ociresult($short_term_data, "ORDER_ENT_EXCEL_COL") >= $array_maxcount){
				array_push($size_id, "");
				array_push($size_desc, "");
				$array_maxcount++;
			}
			array_push($size_id, ociresult($short_term_data, "ORDER_ENT_EXCEL_COL"));
			array_push($size_desc, ociresult($short_term_data, "DESCR"));
			$array_maxcount++;
		} while(ocifetch($short_term_data));
	}

	$output = "<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\">
				<tr>
					<td><b>VESSEL:</b></td>
					<td colspan=\"3\"><b>".$vesname."</b></td>
				</tr>

				<tr>
					<td><b>ETA:</b></td>
					<td colspan=\"3\"><b>".$eta."</b></td>
				</tr>

				<tr>
					<td><b>COMMODITY:</b></td>
					<td colspan=\"3\"><b>".$commname."</b></td>
				</tr>

				<tr>
					<td><b>CUSTOMER:</b></td>
					<td colspan=\"3\"><b>".$custname."</b></td>
				</tr>";

	$output .= "<tr>";
	for($i = 1; $i < $array_maxcount; $i++){
		$output .= "<td>".$size_desc[$i]."</td>";
	}
	$output .= "</tr>";

	$output .= "<tr bgcolor=\"#FFFF00\">
					<td colspan=\"6\"><b>Total Amount Available</b></td>
					<td colspan=\"".($array_maxcount - 6)."\"></tr>";  // more data goes here?

	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";

	$output .= "<tr>
					<td><b>Pick Up Date</b></td>
					<td><b>Pick Up#</b></td>
					<td><b>Branch</b></td>
					<td><b>Direct/Other</b></td>
					<td><b>Customer PO</b></td>
					<td><b>Delivery Date</b></td>";
	for($i = 7; $i < $array_maxcount; $i++){
		$output .= "<td>&nbsp;</td>";
	}

	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";

	$output .= "<tr>
					<td>".$pickup."</td>
					<td>".$order."</td>
					<td>".$consname."</td>
					<td>".$ordertype."</td>
					<td>".$PO."</td>
					<td>".$delivery_date."</td>";
	for($i = 7; $i < $array_maxcount; $i++){
		if($size_id[$i] == ""){
			$output .= "<td>&nbsp;</td>";
		} else {
			$sql = "SELECT ORDERQTY FROM MOR_ORDERDETAIL 
					WHERE ORDERNUM = '".$order."'
						AND CUST = '".$cust."'
						AND ORDERSIZEID = '".$size_id[$i]."'";
			$short_term_data = ociparse($conn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$output .= "<td>&nbsp;</td>";
			} else {
				$output .= "<td>".ociresult($short_term_data, "ORDERQTY")."</td>";
			}
		}
	}
	$output .= "</tr>";

	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";

	$output .= "<tr>
					<td colspan=\"6\"><b>Total To Be Shipped:</b></td>";
	for($i = 7; $i < $array_maxcount; $i++){
		if($size_id[$i] == ""){
			$output .= "<td>&nbsp;</td>";
		} else {
			$sql = "SELECT NVL(SUM(ORDERQTY), 0) THE_SUM FROM MOR_ORDERDETAIL 
					WHERE ORDERNUM = '".$order."'
						AND CUST = '".$cust."'
						AND ORDERSIZEID = '".$size_id[$i]."'";
			$short_term_data = ociparse($conn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$output .= "<td>&nbsp;</td>";
			} else {
				$output .= "<td><b>".ociresult($short_term_data, "THE_SUM")."</b></td>";
			}
		}
	}
	$output .= "</tr>";

	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";
	$output .= "<tr>
					<td colspan=\"".$array_maxcount."\">&nbsp;</td>
				</tr>";

	$output .= "<tr>
					<td colspan=\"6\"><b>Balance Remaining:</b></td>";
	for($i = 7; $i < $array_maxcount; $i++){
		if($size_id[$i] == ""){
			$output .= "<td>&nbsp;</td>";
		} else {
			$sql = "SELECT SUM(ORDERQTY) THE_SUM FROM MOR_ORDERDETAIL 
					WHERE ORDERNUM = '".$order."'
						AND CUST = '".$cust."'
						AND ORDERSIZEID = '".$size_id[$i]."'";
			$short_term_data = ociparse($conn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$output .= "<td>&nbsp;</td>";
			} else {
				$output .= "<td><b>".(-1 * ociresult($short_term_data, "THE_SUM"))."</b></td>";
			}
		}
	}
	$output .= "</tr>";

	$output .= "</table>";

	CreateFile($output, $order);










function CreateFile($input, $order){
	$filename = "OrderEntry".$order."AsOf".date('mdYhis').".xls";
	$fp = fopen("./files/".$filename, "w");
	if(!$fp){
		echo "error opening file.  Please contact the TS of the Port of Wilmington.";
		include("../footer.php");
		exit;
	}
	fwrite($fp, $input);
	fclose($fp);

?>
	<a href="./files/<? echo $filename; ?>">Click Here</a> to download the Excel Spreadsheet version of this Entry Form.<br><br>
<?
	echo $input;
	include("../footer.php");
	exit;
}
