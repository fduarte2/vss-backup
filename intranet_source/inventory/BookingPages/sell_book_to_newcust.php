<?
/*
*	Adam Walter, May 2016.
*
*	This page handles the sale of paper between customers (booking)
*
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");   echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Transfer Roll"){
		$barcode = $HTTP_POST_VARS['barcode'];
		$cust = $HTTP_POST_VARS['cust'];
		$arrival_num = $HTTP_POST_VARS['arrival_num'];
		$new_cust = $HTTP_POST_VARS['new_cust'];
		$order_num = $HTTP_POST_VARS['order_num'];
		$new_book = $HTTP_POST_VARS['new_book'];

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND ARRIVAL_NUM = '".$arrival_num."'
					AND RECEIVER_ID = '".$new_cust."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if($new_cust == "" || $order_num == "" || $new_book == ""){
			echo "<font size=\"2\" color=\"#FF0000\">a New Customer, Booking#, and a Order# must be entered.</font>";
		} elseif(ociresult($short_term_data, "THE_COUNT") >= 1){
			echo "<font size=\"2\" color=\"#FF0000\">This roll/arv#/New Customer combination already exists in the Database.  Please contact TS.</font>";
		} else {
			$sql = "SELECT NVL(MAX(ACTIVITY_NUM), 1) THE_MAX
					FROM CARGO_ACTIVITY
					WHERE CUSTOMER_ID = '".$cust."'
						AND PALLET_ID = '".$barcode."'
						AND ARRIVAL_NUM = '".$arrival_num."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$act_num = ociresult($short_term_data, "THE_MAX");

			$sql = "UPDATE CARGO_TRACKING 
					SET QTY_IN_HOUSE = 0 
					WHERE PALLET_ID = '".$barcode."' 
						AND ARRIVAL_NUM = '".$arrival_num."' 
						AND RECEIVER_ID = '".$cust."'";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);
			
			$sql = "INSERT INTO CARGO_ACTIVITY
					(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('".($act_num + 1)."', '11', '1', '3', '".$order_num."', '".$cust."', SYSDATE, '".$barcode."', '".$arrival_num."', '0')";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);

			$sql = "INSERT INTO CARGO_TRACKING 
						(COMMODITY_CODE,
						CARGO_DESCRIPTION,
						DATE_RECEIVED, 
						QTY_RECEIVED, 
						RECEIVER_ID,
						QTY_IN_HOUSE,
						QTY_DAMAGED,
						FROM_SHIPPING_LINE,
						SHIPPING_LINE,
						FUMIGATION_CODE,
						EXPORTER_CODE,
						PALLET_ID,
						ARRIVAL_NUM,
						RECEIVING_TYPE,
						WEIGHT,
						WEIGHT_UNIT,
						MARK,
						BOL,
						BATCH_ID,
						DECK,
						HATCH,
						CARGO_SIZE,
						VARIETY,
						REMARK,
						CHEP)
					(SELECT 
						COMMODITY_CODE,
						CARGO_DESCRIPTION,
						SYSDATE,
						'1',
						'".$new_cust."',
						'1',
						QTY_DAMAGED,
						FROM_SHIPPING_LINE,
						SHIPPING_LINE,
						FUMIGATION_CODE,
						EXPORTER_CODE,
						PALLET_ID,
						ARRIVAL_NUM,
						'F',
						WEIGHT,
						WEIGHT_UNIT,
						MARK,
						BOL,
						BATCH_ID,
						DECK,
						HATCH,
						CARGO_SIZE,
						VARIETY,
						REMARK,
						CHEP
					FROM CARGO_TRACKING 
					WHERE PALLET_ID = '".$barcode."' 
						AND RECEIVER_ID = '".$cust."' 
						AND ARRIVAL_NUM = '".$arrival_num."')";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);

			$sql = "INSERT INTO CARGO_ACTIVITY 
					(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM, QTY_LEFT) VALUES
					('1', '11', '1', '3', '".$order_num."', '".$new_cust."', SYSDATE, '".$barcode."', '".$arrival_num."', '1')";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);

			$sql = "UPDATE BOOKING_ADDITIONAL_DATA
						SET (BOL,
							SHIPFROMMILL,
							ORDER_NUM,
							PRODUCT_CODE,
							DIAMETER,
							DIAMETER_MEAS,
							WIDTH,
							WIDTH_MEAS,
							LENGTH,
							LENGTH_MEAS,
							BOOKING_NUM,
							WAREHOUSE_CODE) =
						(SELECT 
							BOL,
							SHIPFROMMILL,
							ORDER_NUM,
							PRODUCT_CODE,
							DIAMETER,
							DIAMETER_MEAS,
							WIDTH,
							WIDTH_MEAS,
							LENGTH,
							LENGTH_MEAS,
							'".$new_book."',
							WAREHOUSE_CODE
						FROM BOOKING_ADDITIONAL_DATA
						WHERE PALLET_ID = '".$barcode."' 
							AND RECEIVER_ID = '".$cust."' 
							AND ARRIVAL_NUM = '".$arrival_num."')
					WHERE PALLET_ID = '".$barcode."' 
						AND RECEIVER_ID = '".$new_cust."' 
						AND ARRIVAL_NUM = '".$arrival_num."'";
			$modify = ociparse($rfconn, $sql);
			ociexecute($modify);
					

			echo "<font size=\"3\" color=\"#0000FF\">Roll Transferred</font>";

			$submit = "";
		}
	}
						

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Sell Booking Paper to new Customer
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="select" action="sell_book_to_newcust.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Roll#:  </font></td>
		<td><input type="text" name="barcode" size="32" maxlength="32" value="<? echo $barcode; ?>"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Customer#:  </font></td>
		<td><input type="text" name="cust" size="6" maxlength="6" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Roll"></td>
	</tr>
</form>
</table>

<?
	if($submit != ""){
		$barcode = $HTTP_POST_VARS['barcode'];
		$cust = $HTTP_POST_VARS['cust'];
		$arrival_num = $HTTP_POST_VARS['arrival_num'];

?>
<table border="0" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT ARRIVAL_NUM 
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND DATE_RECEIVED IS NOT NULL
					AND QTY_IN_HOUSE > 0";
//		echo $sql."<br>";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(ocifetch($short_term_data)){
			$arrival_num = ociresult($short_term_data, "ARRIVAL_NUM");
		}

		$result = Validate_Roll_Select($barcode, $arrival_num, $cust, $rfconn);

		$sql = "SELECT CT.ARRIVAL_NUM, BAD.BOOKING_NUM, DIAMETER, DIAMETER_MEAS, WIDTH, WIDTH_MEAS, CT.WEIGHT, CT.WEIGHT_UNIT, CT.DATE_RECEIVED, CT.QTY_IN_HOUSE, CT.REMARK 
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE CT.PALLET_ID = '".$barcode."'
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.ARRIVAL_NUM = '".$arrival_num."'
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				ORDER BY QTY_IN_HOUSE DESC, DATE_RECEIVED NULLS LAST";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);

		if($result != ""){
?>
	<tr>
		<td><font size="2" color="#FF0000">Roll cannot be sold:<br><? echo $result; ?></font></td>
	</tr>
<?
		} else {
?>
<form name="delete" action="sell_book_to_newcust.php" method="post">
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="arrival_num" value="<? echo $arrival_num; ?>">
	<tr>
		<td width="100%"><font size="3" face="Verdana">Roll#:  <? echo $barcode; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Arrival#:  <? echo $arrival_num; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Cust#:  <? echo $cust; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Diamater:  <? echo ociresult($short_term_data, "DIAMETER")." ".ociresult($short_term_data, "DIAMETER_MEAS"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Width:  <? echo ociresult($short_term_data, "WIDTH")." ".ociresult($short_term_data, "WIDTH_MEAS"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Weight:  <? echo ociresult($short_term_data, "WEIGHT")." ".ociresult($short_term_data, "WEIGHT_UNIT"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Booking#:  <input type="text" name="new_book" size="20" maxlength="20" value="<? echo ociresult($short_term_data, "BOOKING_NUM"); ?>"></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Sell to Customer:  <input type="text" name="new_cust" size="6" maxlength="6" value="<? echo $new_cust; ?>"></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Order# (for record-keeping):  <input type="text" name="order_num" size="12" maxlength="12" value="<? echo $order_num; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Transfer Roll"></td>
	</tr>
</form>
<?
		}
?>
</table>
<?
	}
	include("pow_footer.php");






function Validate_Roll_Select($Barcodes, $LRs, $custs, $rfconn){

	$return = "";

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING
			WHERE PALLET_ID = '".$Barcodes."'
				AND RECEIVER_ID = '".$custs."'
				AND ARRIVAL_NUM = '".$LRs."'
				AND REMARK = 'BOOKINGSYSTEM'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	if(ociresult($short_term_data, "THE_COUNT") <= 0){
		$return .= "Line ".$i.":  Pallet ".$Barcodes." does not exist, or is not in house, or is not yet received, for customer ".$custs.".<br>";
	} else {
		$sql = "SELECT QTY_IN_HOUSE, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NR') THE_REC
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$Barcodes."'
					AND RECEIVER_ID = '".$custs."'
					AND ARRIVAL_NUM = '".$LRs."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "QTY_IN_HOUSE") <= 0){
			$return .= "Line ".$i.":  Pallet ".$Barcodes." / vessel ".$LRs." / customer ".$custs.":  Pallet Not in House.<br>";
		}
		if(ociresult($short_term_data, "THE_REC") == 'NR'){
			$return .= "Line ".$i.":  Pallet ".$Barcodes." / vessel ".$LRs." / customer ".$custs.":  Never Received; Cannot XFer.<br>";
		}
	}


	return $return;
}
