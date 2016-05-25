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

		echo "<font color=\"0000FF\">Pallet ".$barcode." Transferred to Port.</font><br>";
		$submit = "";
	}


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Transfer to Port Account (Dockticket)<br></font><font size="3" face="Verdana" color="#0066CC">
</font>
	    
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="select" action="transfer_to_port_DT.php" method="post">
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
?>
<table border="0" cellpadding="4" cellspacing="0">
<?
		$sql = "SELECT * FROM CARGO_TRACKING
				WHERE PALLET_ID = '".$barcode."'
					AND RECEIVER_ID = '".$cust."'
				ORDER BY QTY_IN_HOUSE DESC, DATE_RECEIVED NULLS LAST";
		$rollinfo = ociparse($rfconn, $sql);
		ociexecute($rollinfo);
		// we only want the first record, in case of long-running duplicates.  Hench the order by's.

		$result = ValidateDTPaper($rollinfo, $rfconn);

		if($result != ""){
?>
	<tr>
		<td><font size="2" color="#FF0000">Roll cannot be transferred to Port Account:<br><? echo $result; ?></td>
	</tr>
<?
		} else {
?>
<form name="delete" action="transfer_to_port_DT.php" method="post">
<input type="hidden" name="barcode" value="<? echo $barcode; ?>">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
	<tr>
		<td width="100%"><font size="3" face="Verdana">Roll#:  <? echo $barcode; ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Arrival#:  <? echo ociresult($rollinfo, "ARRIVAL_NUM"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Cust#:  <? echo ociresult($rollinfo, "RECEIVER_ID"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Code:  <? echo ociresult($rollinfo, "BATCH_ID"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">Size:  <? echo ociresult($rollinfo, "CARGO_SIZE"); ?></td>
	</tr>
	<tr>
		<td width="100%"><font size="3" face="Verdana">DT#:  <? echo ociresult($rollinfo, "BOL"); ?></td>
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
	include("pow_footer.php");


function ValidateDTPaper(&$rollinfo, $rfconn){
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

		if(ociresult($rollinfo, "REMARK") != "DOLEPAPERSYSTEM"){
			$return .= "This is not a DOCKTICKET roll of paper.<br>";
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
