<?
/*
*		Adam Walter, Sept 2015.
*
*		Page to transfer paper rolls to port account
*********************************************************************************/

	// All POW files need this session file included
	include("pow_session.php");

	// Define some vars for the skeleton page
	$title = "Transfer to Port";
	$area_type = "INVE";

	// Provides header / leftnav
	include("pow_header.php");
	if($access_denied){
		printf("Access Denied from INVE system");
		include("pow_footer.php");
		exit;
	}


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	
	$submit = $HTTP_POST_VARS['submit'];
	$barcode = $HTTP_POST_VARS['barcode'];
	$cust = $HTTP_POST_VARS['cust'];

	if($submit == "Transfer To Port"){
		$sql = "SELECT ARRIVAL_NUM, QTY_IN_HOUSE 
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND DATE_RECEIVED IS NOT NULL
					AND QTY_IN_HOUSE > 0";
		$getarv = ociparse($rfconn, $sql);
		ociexecute($getarv);
		ocifetch($getarv);
		$arrival_num = ociresult($getarv, "ARRIVAL_NUM");
//		$qty_trans = ociresult($getarv, "QTY_IN_HOUSE");
		
		$sql = "UPDATE CARGO_ACTIVITY
				SET CUSTOMER_ID = 1
				WHERE PALLET_ID = '".$barcode."'
					AND CUSTOMER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$arrival_num."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "UPDATE CARGO_TRACKING
				SET RECEIVER_ID = 1
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND ARRIVAL_NUM = '".$arrival_num."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX
				FROM CARGO_ACTIVITY
				WHERE PALLET_ID = '".$barcode."'
					AND CUSTOMER_ID = '1'
					AND ARRIVAL_NUM = '".$arrival_num."'";
		$getact = ociparse($rfconn, $sql);
		ociexecute($getact);
		ocifetch($getact);
		$act_num = ociresult($getact, "THE_MAX") + 1;

		$sql = "INSERT INTO CARGO_ACTIVITY
					(ACTIVITY_NUM,
					SERVICE_CODE,
					QTY_CHANGE,
					ACTIVITY_ID,
					ACTIVITY_DESCRIPTION,
					CUSTOMER_ID,
					DATE_OF_ACTIVITY,
					PALLET_ID,
					ARRIVAL_NUM,
					QTY_LEFT)
				VALUES
					('".$act_num."',
					'15',
					'1',
					'3',
					'".$cust."',
					'1',
					SYSDATE,
					'".$barcode."',
					'".$arrival_num."',
					'1')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

/*
		$sql = "SELECT ARRIVAL_NUM, QTY_IN_HOUSE 
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
					AND DATE_RECEIVED IS NOT NULL
					AND QTY_IN_HOUSE > 0";
		$getarv = ociparse($rfconn, $sql);
		ociexecute($getarv);
		ocifetch($getarv);
		$arrival_num = ociresult($getarv, "ARRIVAL_NUM");
		$qty_trans = ociresult($getarv, "QTY_IN_HOUSE");

		$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX
				FROM CARGO_ACTIVITY
				WHERE PALLET_ID = '".$barcode."' 
					AND ARRIVAL_NUM = '".$arrival_num."' 
					AND CUSTOMER_ID = '".$cust."'";
		$getact = ociparse($rfconn, $sql);
		ociexecute($getact);
		ocifetch($getact);
		$next_act = ociresult($getact, "THE_MAX") + 1;

		$sql = "UPDATE CARGO_TRACKING 
				SET QTY_IN_HOUSE = 0 
				WHERE PALLET_ID = '".$barcode."' 
					AND ARRIVAL_NUM = '".$arrival_num."' 
					AND RECEIVER_ID = '".$cust."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "INSERT INTO CARGO_ACTIVITY
				(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM) VALUES
				('".$next_act."', '11', '".$qty_trans."', '4', 'PORTTRANS', '".$cust."', SYSDATE, '".$barcode."', '".$arrival_num."')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "INSERT INTO CARGO_TRACKING 
					(COMMODITY_CODE, 
					CARGO_DESCRIPTION,
					WAREHOUSE_LOCATION,
					DATE_RECEIVED, 
					QTY_RECEIVED, 
					RECEIVER_ID,
					QTY_IN_HOUSE,
					QTY_UNIT,
					QTY_DAMAGED,
					CARGO_STATUS,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					FUMIGATION_CODE,
					EXPORTER_CODE,
					PALLET_ID,
					ARRIVAL_NUM,
					RECEIVING_TYPE,
					WEIGHT,
					WEIGHT_UNIT,
					BOL,
					BILL,
					BATCH_ID,
					MARK,
					REMARK,
					DECK,
					HATCH,
					CARGO_SIZE,
					VARIETY,
					CHEP,
					SOURCE_NOTE,
					SOURCE_USER)
				(SELECT 
					COMMODITY_CODE,
					CARGO_DESCRIPTION,
					WAREHOUSE_LOCATION,
					SYSDATE,
					'".$qty_trans."',
					'1',
					'".$qty_trans."',
					QTY_UNIT,
					QTY_DAMAGED,
					CARGO_STATUS,
					FROM_SHIPPING_LINE,
					SHIPPING_LINE,
					FUMIGATION_CODE,
					EXPORTER_CODE,
					'".$barcode."',
					'".$arrival_num."',
					RECEIVING_TYPE,
					WEIGHT,
					WEIGHT_UNIT,
					BOL,
					BILL,
					BATCH_ID,
					MARK,
					REMARK,
					DECK,
					HATCH,
					CARGO_SIZE,
					VARIETY,
					CHEP,
					'transfer_to_port.php',
					'".$user."'
				FROM CARGO_TRACKING 
				WHERE PALLET_ID = '".$barcode."' 
					AND RECEIVER_ID = '".$cust."' 
					AND ARRIVAL_NUM = '".$arrival_num."')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "INSERT INTO CARGO_ACTIVITY 
				(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ARRIVAL_NUM) VALUES
				('1', '11', '".$qty_trans."', '4', 'PORTTRANS', '1', SYSDATE, '".$barcode."', '".$arrival_num."')";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);

		$sql = "UPDATE BOOKING_ADDITIONAL_DATA BAD1
				SET 
					(BOL, 
					SHIPFROMMILL,
					ORDER_NUM,
					PRODUCT_CODE,
					DIAMETER,
					DIAMETER_MEAS,
					WIDTH,
					WIDTH_MEAS,
					LENGTH,
					LENGTH_MEAS,
					DAMAGE,
					BOOKING_NUM) =
				(SELECT BOL, 
					SHIPFROMMILL,
					ORDER_NUM,
					PRODUCT_CODE,
					DIAMETER,
					DIAMETER_MEAS,
					WIDTH,
					WIDTH_MEAS,
					LENGTH,
					LENGTH_MEAS,
					DAMAGE,
					BOOKING_NUM
				FROM BOOKING_ADDITIONAL_DATA BAD2
				WHERE BAD1.PALLET_ID = BAD2.PALLET_ID
					AND BAD1.ARRIVAL_NUM = BAD2.ARRIVAL_NUM
					AND BAD2.RECEIVER_ID = '".$cust."'

				)
				WHERE BAD1.PALLET_ID = '".$barcode."'
					AND BAD1.RECEIVER_ID = '1'
					AND BAD1.ARRIVAL_NUM = '".$arrival_num."'";
		$update = ociparse($rfconn, $sql);
		ociexecute($update);
*/					

		echo "<font color=\"0000FF\">Pallet ".$barcode." Transferred to Port.</font><br>";
		$submit = "";
	}

	if($submit == "Revert Cargo to Owner"){
		$barcode = $HTTP_POST_VARS['barcode'];
		$arv = $HTTP_POST_VARS['arv'];
		$revert = $HTTP_POST_VARS['revert'];
		$maxrows = $HTTP_POST_VARS['maxrows'];

		$returned = 0;

		for($i = 0; $i < $maxrows; $i++){
			if($revert[$i] == "change"){
				$sql = "SELECT MAX(ACTIVITY_NUM) THE_MAX
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$barcode[$i]."'
							AND CUSTOMER_ID = '1'
							AND ARRIVAL_NUM = '".$arv[$i]."'";
				$getact = ociparse($rfconn, $sql);
				ociexecute($getact);
				ocifetch($getact);
				$act_num = ociresult($getact, "THE_MAX") + 1;

				$sql = "SELECT ACTIVITY_DESCRIPTION
						FROM CARGO_ACTIVITY
						WHERE PALLET_ID = '".$barcode[$i]."'
							AND CUSTOMER_ID = '1'
							AND ARRIVAL_NUM = '".$arv[$i]."'
							AND SERVICE_CODE = '15'
						ORDER BY ACTIVITY_NUM DESC";
				$getcust = ociparse($rfconn, $sql);
				ociexecute($getcust);
				ocifetch($getcust);
				$orig_cust = ociresult($getcust, "ACTIVITY_DESCRIPTION");

				$sql = "INSERT INTO CARGO_ACTIVITY
							(ACTIVITY_NUM,
							SERVICE_CODE,
							QTY_CHANGE,
							ACTIVITY_ID,
							ACTIVITY_DESCRIPTION,
							CUSTOMER_ID,
							DATE_OF_ACTIVITY,
							PALLET_ID,
							ARRIVAL_NUM,
							QTY_LEFT)
						VALUES
							('".$act_num."',
							'15',
							'1',
							'3',
							'Back to ".$orig_cust."',
							'1',
							SYSDATE,
							'".$barcode[$i]."',
							'".$arv[$i]."',
							'1')";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

				$sql = "UPDATE CARGO_TRACKING
						SET RECEIVER_ID = ".$orig_cust."
						WHERE PALLET_ID = '".$barcode[$i]."'
							AND RECEIVER_ID = '1'
							AND ARRIVAL_NUM = '".$arv[$i]."'";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);

				$returned++;
			}
		}

		echo "<font color=\"#0000FF\"><b>".$returned." Rolls have been given back to the customer.</b></font><br>";

		$barcode = "";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Transfer to Port Account (Booking)<br></font><font size="3" face="Verdana" color="#0066CC">
</font>
	    
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="select" action="transfer_to_port_booking.php" method="post">
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
	if($submit == "Retrieve Roll"){
?>
<table border="0" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT CT.ARRIVAL_NUM, BAD.BOOKING_NUM, DIAMETER, DIAMETER_MEAS, WIDTH, WIDTH_MEAS, CT.WEIGHT, CT.WEIGHT_UNIT, CT.DATE_RECEIVED, CT.QTY_IN_HOUSE, CT.REMARK 
				FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD
				WHERE CT.PALLET_ID = '".$barcode."'
					AND CT.RECEIVER_ID = '".$cust."'
					AND CT.PALLET_ID = BAD.PALLET_ID
					AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				ORDER BY QTY_IN_HOUSE DESC, DATE_RECEIVED NULLS LAST";
//		echo $sql."<br>";
		$rollinfo = ociparse($rfconn, $sql);
		ociexecute($rollinfo);
		// we only want the first record, in case of long-running duplicates.  Hench the order by's.

		$result = ValidateBookingPaper($rollinfo, $rfconn);

		if($result != ""){
?>
	<tr>
		<td><font size="2" color="#FF0000">Roll cannot be transferred to Port Account:<br><? echo $result; ?></td>
	</tr>
<?
		} else {
?>
<form name="delete" action="transfer_to_port_booking.php" method="post">
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
	<tr>
		<td width="100%"><font size="3" face="Verdana">Roll#:  <? echo $barcode; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Arrival#:  <? echo ociresult($rollinfo, "ARRIVAL_NUM"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Cust#:  <? echo $cust; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Booking#:  <? echo ociresult($rollinfo, "BOOKING_NUM"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Diamater:  <? echo ociresult($rollinfo, "DIAMETER")." ".ociresult($rollinfo, "DIAMETER_MEAS"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Width:  <? echo ociresult($rollinfo, "WIDTH")." ".ociresult($rollinfo, "WIDTH_MEAS"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Weight:  <? echo ociresult($rollinfo, "WEIGHT")." ".ociresult($rollinfo, "WEIGHT_UNIT"); ?></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Transfer To Port"></td>
	</tr>
</form>
<?
		}
?>
</table>
<?
	}
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="revert_data" action="transfer_to_port_booking.php" method="post">
	<tr>
		<td colspan="8" align="center" bgcolor="#FFDDDD"><font size="3" face="Verdana"><b>Return to Previous Owner</b></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana"><b>Roll#</b></font></td>
		<td><font size="3" face="Verdana"><b>Arrival#</b></font></td>
		<td><font size="3" face="Verdana"><b>Original Cust#</b></font></td>
		<td><font size="3" face="Verdana">Booking#</font></td>
		<td><font size="3" face="Verdana">Diamater</font></td>
		<td><font size="3" face="Verdana">Width</font></td>
		<td><font size="3" face="Verdana">Weight</font></td>
		<td><font size="3" face="Verdana">Return</font></td>
	</tr>
<?
	$i = 0;
	$sql = "SELECT CT.PALLET_ID, CT.ARRIVAL_NUM, BAD.BOOKING_NUM, DIAMETER, DIAMETER_MEAS, WIDTH, WIDTH_MEAS, CT.WEIGHT, CT.WEIGHT_UNIT, CT.DATE_RECEIVED, CT.QTY_IN_HOUSE, CT.REMARK, CA.ACTIVITY_DESCRIPTION 
			FROM CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA
			WHERE CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.RECEIVER_ID = '1'
				AND CA.SERVICE_CODE = '15'
				AND CT.QTY_IN_HOUSE > 0
				AND CT.PALLET_ID = BAD.PALLET_ID
				AND CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = BAD.RECEIVER_ID
				AND CA.ACTIVITY_NUM = 
					(SELECT MAX(ACTIVITY_NUM)
					FROM CARGO_ACTIVITY CA2
					WHERE CA.PALLET_ID = CA2.PALLET_ID
						AND CA.ARRIVAL_NUM = CA2.ARRIVAL_NUM
						AND CA.CUSTOMER_ID = CA2.CUSTOMER_ID
						AND CA2.SERVICE_CODE = '15')
			ORDER BY QTY_IN_HOUSE DESC, DATE_RECEIVED NULLS LAST";
	$roll_list = ociparse($rfconn, $sql);
	ociexecute($roll_list);
	while(ocifetch($roll_list)){
?>
	<tr>
		<td><input type="hidden" name="barcode[<? echo $i; ?>]" value="<? echo ociresult($roll_list, "PALLET_ID"); ?>">
			<input type="hidden" name="arv[<? echo $i; ?>]" value="<? echo ociresult($roll_list, "ARRIVAL_NUM"); ?>">
			<font size="2" face="Verdana"><? echo ociresult($roll_list, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($roll_list, "ARRIVAL_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($roll_list, "ACTIVITY_DESCRIPTION"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($roll_list, "BOOKING_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($roll_list, "DIAMETER")." ".ociresult($roll_list, "DIAMETER_MEAS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($roll_list, "WIDTH")." ".ociresult($roll_list, "WIDTH_MEAS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($roll_list, "WEIGHT")." ".ociresult($roll_list, "WEIGHT_UNIT"); ?></font></td>
		<td><input type="checkbox" value="change" name="revert[<? echo $i; ?>]"></td>
	</tr>
<?
		$i++;
	}
?>
	<input type="hidden" name="maxrows" value="<? echo $i; ?>">
	<tr>
		<td colspan="8" align="center"><input name="submit" type="submit" value="Revert Cargo to Owner"></td>
	</tr>
</form>
</table>
<?
	
	include("pow_footer.php");


function ValidateBookingPaper(&$rollinfo, $rfconn){
	$return = "";

	if(!ocifetch($rollinfo)){
		$return .= "Roll does not exist.<br>";
	} else {
		if(ociresult($rollinfo, "DATE_RECEIVED") == ""){
			$return .= "Roll not received.<br>";
		}

		if(ociresult($rollinfo, "QTY_IN_HOUSE") == "0"){
			$return .= "Roll no longer in house.<br>";
		}

		if(ociresult($rollinfo, "REMARK") != "BOOKINGSYSTEM"){
			$return .= "This is not a BOOKING roll of paper.<br>";
		}

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CARGO_TRACKING
				WHERE PALLET_ID = '".ociresult($rollinfo, "PALLET_ID")."'
					AND RECEIVER_ID = '1'
					AND ARRIVAL_NUM = '".ociresult($rollinfo, "ARRIVAL_NUM")."'";
		$check = ociparse($rfconn, $sql);
		ociexecute($check);
		ocifetch($check);
		if(ociresult($check, "THE_COUNT") >= 1){
			$return .= "This roll/ARV# combination already exists; please contact TS.<br>";
		}
	}

	return $return;
}
