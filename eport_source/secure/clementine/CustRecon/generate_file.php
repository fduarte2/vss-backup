<?
/* Adam Walter, 8/25/07
*	This file creates a customer-to-order file for Susan Bricks.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$order_cursor = ora_open($conn2);
	$size_cursor = ora_open($conn2);
	$main_cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel_num = $HTTP_POST_VARS['vessel_num'];
	$cust_num = $HTTP_POST_VARS['cust_num'];

	$order_array = array();
	$size_array = array();
	$order_counter = 0;
	$size_counter = 0;

	$column_total = array();


	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel_num."'";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$print_vessel = $row['THE_VESSEL'];

	$sql = "SELECT CUSTOMERID, SUBSTR(CUSTOMERNAME, 1, 10) THE_CUST, CUSTOMERNAME FROM DC_CUSTOMER WHERE CUSTOMERID = '".$cust_num."'";
	ora_parse($Short_Term_Data, $sql);
	ora_exec($Short_Term_Data);
	ora_fetch_into($Short_Term_Data, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$path = "/var/www/secure/clementine/CustRecon/";
	$fName = $vessel_num.$row['THE_CUST']."-".$row['CUSTOMERID'].".csv";
	$filename = $path.$fName;
	
	$fp = fopen($filename, 'w');
	fwrite($fp, $header);

	$sql = "SELECT DISTINCT TRIM(ORDERNUM) THE_ORDER, CUSTOMERPO FROM DC_ORDER DCO, CARGO_ACTIVITY CA WHERE VESSELID = '".$vessel_num."' AND CUSTOMERID = '".$cust_num."' AND TRIM(DCO.ORDERNUM) = CA.ORDER_NUM ORDER BY THE_ORDER";
	ora_parse($order_cursor, $sql);
	ora_exec($order_cursor);
	while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$order_array[$order_counter] = $order_row['THE_ORDER'].";".$order_row['CUSTOMERPO'];
		$order_counter++;
	}

	// SUBSTR(ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
	$sql = "SELECT DISTINCT DC_CARGO_DESC THE_SIZE FROM DC_CARGO_TRACKING CT, CARGO_ACTIVITY CA, DC_ORDER DCO WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.ORDER_NUM = TRIM(DCO.ORDERNUM) AND CA.SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL AND CT.MARK = 'SHIPPED' AND DCO.CUSTOMERID = '".$cust_num."' AND CT.ARRIVAL_NUM = '".$vessel_num."' ORDER BY THE_SIZE";
	ora_parse($size_cursor, $sql);
	ora_exec($size_cursor);
	while(ora_fetch_into($size_cursor, $size_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$size_array[$size_counter] = $size_row['THE_SIZE'];
		$size_counter++;
	}

	if($size_counter == 0 || $order_counter == 0){
		fwrite($fp, "No Orders for customer ".$row['CUSTOMERNAME']." On vessel ".$print_vessel.".");
		fclose($fp);
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$fName");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($filename));

		readfile("$filename");
		exit;
	}

	// first, the size headers
	for($temp = 0; $temp < $size_counter; $temp++){
		fwrite($fp, ",".$size_array[$temp]);
	}
	fwrite($fp, ",Totals\n");

	for($temp1 = 0; $temp1 < $order_counter; $temp1++){
		$row_total = 0;

		$temp_array = split(";", $order_array[$temp1]);
		$order_num = $temp_array[0];
		$PO = $temp_array[1];

		fwrite($fp, $order_num." (PO: ".$PO.")");

		for($temp2 = 0; $temp2 < $size_counter; $temp2++){
			// SUBSTR(CA.ACTIVITY_DESCRIPTION, 1, 3) = 'DMG'
			$sql = "SELECT NVL(SUM(QTY_CHANGE), 0) THE_CHANGE FROM CARGO_ACTIVITY CA, DC_CARGO_TRACKING CT WHERE CA.ARRIVAL_NUM = '".$vessel_num."' AND CA.ORDER_NUM = '".$order_num."' AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CT.PALLET_ID = CA.PALLET_ID AND DC_CARGO_DESC = '".$size_array[$temp2]."' AND CA.SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL";
			ora_parse($main_cursor, $sql);
			ora_exec($main_cursor);
			ora_fetch_into($main_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$row_total += $row['THE_CHANGE'];
			$column_total[$temp2] += $row['THE_CHANGE'];
			fwrite($fp, ",".$row['THE_CHANGE']);
		}
		fwrite($fp, ",".$row_total."\n");
		$column_total[$size_counter] += $row_total;
	}
	fwrite($fp, "Totals:");
	for($temp3 = 0; $temp3 <= $size_counter; $temp3++){
		fwrite($fp, ",".$column_total[$temp3]);
	}

	fclose($fp);
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fName");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($filename));

	readfile("$filename");
	exit;
?>