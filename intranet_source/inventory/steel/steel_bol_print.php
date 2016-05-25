<?
  // All POW files need this session file included
  include("pow_session.php");


	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	$PORT_num = $HTTP_POST_VARS['PORT_num'];
//	$clerk = $HTTP_POST_VARS['clerk'];
	$sql = "SELECT TLS_ROW_ID
			FROM STEEL_ORDERS
			WHERE PORT_ORDER_NUM = '".$PORT_num."'";
//	echo $sql;
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		echo "PORT Order# ".$PORT_num." Not Found in system.  Please use your browser's Back Button to return to the previous screen.";
		exit;
	} elseif($clerk == ""){
		echo "Clerk's initials must be entered.  Please use your browser's Back Button to return to the previous screen.";
		exit;
	} elseif($submit == "COMPLETE") {
		$sql = "UPDATE TLS_TRUCK_LOG
				SET CHECKED_OUT_BY = '".$clerk."',
					TIME_OUT = SYSDATE
				WHERE RECORD_ID = '".ociresult($stid, "TLS_ROW_ID")."'";
//		echo $sql;
		$update = ociparse($bniconn, $sql);
		ociexecute($update);

		$sql = "UPDATE STEEL_ORDERS
				SET ORDER_STATUS = 'COMPLETE'
				WHERE PORT_ORDER_NUM = '".$PORT_num."'";
	//	echo $sql;
		$update_rf = ociparse($rfconn, $sql);
		ociexecute($update_rf);
	}

	$header_info = array();
	$header_info['PORT_num'] = $PORT_num;

?>
<table width="100%" border="0">
<?
	$sql = "SELECT NVL(ORDER_STATUS, 'DRAFT') THE_STAT
			FROM STEEL_ORDERS
			WHERE PORT_ORDER_NUM = '".$PORT_num."'";
//	echo $sql;
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_STAT") == "DRAFT"){
?>
	<tr>
		<td colspan="2" align="center"><font size="3" face="Verdana"><b>DRAFT - NOT FINALIZED</b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b><a href="steel_bol_print.php?PORT_num=<? echo $PORT_num; ?>&clerk=<? echo $clerk; ?>&submit=COMPLETE">Confirm & Complete Order</a></b></font></td>
	</tr>
<?
	}
?>
	<tr>
		<td width="60%"><IMG SRC="./POW_logo_in_black.jpg" HEIGHT="58" WIDTH="180"><br><font size="1" face="Verdana">One Hausel Road, Wilmington, Delaware  19801-5852 -- (302) 472-PORT (7678) * Fax (302) 472-7740</font></td>
		<td><font size="2" face="Verdana"><b>PORT ORDER AND TALLY<br>PO NO. <? echo $PORT_num; ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><br></td>
	</tr>
</table>
<?
	// get data
	$sql = "SELECT CUSTOMER_ID, LR_NUM, COMMODITY_CODE, SO.DONUM, SC.NAME CAR_NAME, SST.NAME SHIP_NAME, BOL, REMARK_1, REMARK_2,
				ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP, DRIVER_NAME, LICENSE_NUM, LICENSE_STATE, CLERK_INITIALS, TRUCKING_COMPANY, TLS_ROW_ID
			FROM STEEL_ORDERS SO, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_SHIPPING_TABLE SST, STEEL_CARRIERS SC
			WHERE SO.DONUM = SPDI.DONUM
				AND SC.CARRIER_ID = SPDI.CARRIER_ID
				AND SST.SHIP_TO_ID = SPDI.SHIP_TO_ID
				AND SO.PORT_ORDER_NUM = '".$PORT_num."'";
//	echo $sql."<br>";;
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$header_info['arrival'] = ociresult($stid, "LR_NUM");
	$header_info['cust'] = ociresult($stid, "CUSTOMER_ID");
	$header_info['comm'] = ociresult($stid, "COMMODITY_CODE");
	$header_info['DO_num'] = ociresult($stid, "DONUM");
	$header_info['carrier'] = ociresult($stid, "CAR_NAME");
	$header_info['ship_to'] = ociresult($stid, "SHIP_NAME");
	$header_info['bol'] = ociresult($stid, "BOL");
	$header_info['add1'] = ociresult($stid, "ADDRESS_1");
	$header_info['add2'] = ociresult($stid, "ADDRESS_2");
	$header_info['city'] = ociresult($stid, "CITY");
	$header_info['state'] = ociresult($stid, "STATE");
	$header_info['zip'] = ociresult($stid, "ZIP");
	$header_info['driver'] = ociresult($stid, "DRIVER_NAME");
	$header_info['lic_num'] = ociresult($stid, "LICENSE_NUM");
	$header_info['lic_state'] = ociresult($stid, "LICENSE_STATE");
	$header_info['clerk'] = ociresult($stid, "CLERK_INITIALS");
	$header_info['trucker'] = ociresult($stid, "TRUCKING_COMPANY");
	$header_info['rem1'] = ociresult($stid, "REMARK_1");
	$header_info['rem2'] = ociresult($stid, "REMARK_2");
	$tls_row = ociresult($stid, "TLS_ROW_ID");

	$sql = "SELECT TO_CHAR(TIME_OUT, 'MM/DD/YYYY') THE_OUT
			FROM SAG_OWNER.TLS_TRUCK_LOG@BNI
			WHERE RECORD_ID = '".$tls_row."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
//	echo $sql;
	$header_info['ord_date'] = ociresult($stid, "THE_OUT");

	$sql = "SELECT CUSTOMER_NAME, CUSTOMER_ADDRESS1, CUSTOMER_ADDRESS2, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP 
			FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID = '".$header_info['cust']."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$header_info['custname'] = ociresult($stid, "CUSTOMER_NAME");
	$header_info['custadd1'] = ociresult($stid, "CUSTOMER_ADDRESS1");
	$header_info['custadd2'] = ociresult($stid, "CUSTOMER_ADDRESS2");
	$header_info['custcity'] = ociresult($stid, "CUSTOMER_CITY");
	$header_info['custstate'] = ociresult($stid, "CUSTOMER_STATE");
	$header_info['custzip'] = ociresult($stid, "CUSTOMER_ZIP");

	$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE
			WHERE COMMODITY_CODE = '".$header_info['comm']."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$header_info['commname'] = ociresult($stid, "COMMODITY_NAME");

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$header_info['arrival']."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$header_info['vesname'] = $header_info['arrival']."-".ociresult($stid, "VESSEL_NAME");

?>
<table width="100%" border="1" cellspacing="0" cellpadding="2">
	<tr>
		<td width="25%" align="center"><font size="2" face="Verdana">ORDER DATE<br><? echo $header_info['ord_date']; ?></font></td>
		<td width="25%" align="center"><font size="2" face="Verdana">CUSTOMER DO<br><? echo $header_info['DO_num']; ?></font></td>
		<td width="25%" align="center"><font size="2" face="Verdana">MFST BOL NO.<br><? echo $header_info['bol']; ?></font></td>
		<td width="25%" align="center"><font size="2" face="Verdana">CHECK IN<br><? echo $header_info['clerk']; ?></font></td>
	</tr>
	<tr>
		<td width="25%" align="center"><font size="2" face="Verdana">CARRIER PICK UP<br><? echo $header_info['trucker']; ?></font></td>
		<td width="25%" align="center"><font size="2" face="Verdana">TRAILER/TRUCK NO.<br><? echo $header_info['lic_num']."   ".$header_info['lic_state']; ?></font></td>
		<td width="25%" align="center"><font size="2" face="Verdana">MASTER CARRIER.<br><? echo $header_info['carrier']; ?></font></td>
		<td width="25%" align="center"><font size="2" face="Verdana">CHECK OUT<br><? echo $clerk; ?></font></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana">FOR DELIVERY TO:</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana">BILL TO:</font></td>
	</tr>
	<tr>
		<td  colspan="2" width="50%"><font size="2" face="Verdana"><? echo $header_info['ship_to']."<br>".$header_info['add1']."<br>".$header_info['add2']."<br>".$header_info['city'].", ".$header_info['state']."  ".$header_info['zip']; ?></font></td>
		<td  colspan="2" width="50%"><font size="2" face="Verdana"><? echo $header_info['custname']."<br>".$header_info['custadd1']."<br>".$header_info['custadd2'].", ".$header_info['state']."  ".$header_info['zip']."<br>".$header_info['custcity'].", ".$header_info['custstate']."  ".$header_info['custzip']; ?></font></td>
	</tr>
</table>
<table width="100%" border="1" cellspacing="0" cellpadding="2">
	<tr>
		<td colspan="3"><font size="2" face="Verdana">COMMODITY<br><? echo $header_info['commname']; ?></font></td>
		<td colspan="3"><font size="2" face="Verdana">VESSEL<br><? echo $header_info['vesname']; ?></font></td>
	</tr>
<?
	$qty = 0;
	$wt = 0;
	$sql = "SELECT CT.PALLET_ID, CARGO_DESCRIPTION, WAREHOUSE_LOCATION, QTY_CHANGE, (WEIGHT / QTY_RECEIVED) WT_PER
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, COMMODITY_PROFILE CP
			WHERE RECEIVER_ID = '".$header_info['cust']."'
				AND CT.ARRIVAL_NUM = '".$header_info['arrival']."'
				AND REMARK = '".$header_info['DO_num']."'
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CA.SERVICE_CODE = '6'
				AND CA.ACTIVITY_DESCRIPTION IS NULL
				AND CA.ORDER_NUM = '".$PORT_num."'
				AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND CP.COMMODITY_TYPE = 'STEEL'";
//	echo $sql;
	$ship_loop = ociparse($rfconn, $sql);
	ociexecute($ship_loop);
	if(!ocifetch($ship_loop)){
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana"><b>No Pallets on this Order</b><br><br><br><br></font></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td width="10%"><font size="2" face="Verdana">QTY</font></td>
		<td><font size="2" face="Verdana">U/MR</font></td>
		<td><font size="2" face="Verdana">PALLET ID</font></td>
		<td><font size="2" face="Verdana">CARGO MARK DESCRIPTION</font></td>
		<td><font size="2" face="Verdana">CODE</font></td>
		<td width="10%"><font size="2" face="Verdana">WEIGHT</font></td>
	</tr>
<?
		do {
?>
	<tr>
		<td><font size="1" face="Verdana"><? echo ociresult($ship_loop, "QTY_CHANGE"); ?></font></td>
		<td><font size="1" face="Verdana">PC</font></td>
		<td><font size="1" face="Verdana"><? echo ociresult($ship_loop, "PALLET_ID"); ?></font></td>
		<td><font size="1" face="Verdana"><? echo substr(ociresult($ship_loop, "CARGO_DESCRIPTION"), 0, 40); ?></font></td>
		<td><font size="1" face="Verdana"><? echo ociresult($ship_loop, "WAREHOUSE_LOCATION"); ?></font></td>
		<td><font size="1" face="Verdana"><? echo ociresult($ship_loop, "QTY_CHANGE") * round(ociresult($ship_loop, "WT_PER")); ?></font></td>
	</tr>
<?
			$qty += ociresult($ship_loop, "QTY_CHANGE");
			$wt += (ociresult($ship_loop, "QTY_CHANGE") * round(ociresult($ship_loop, "WT_PER")));

		} while(ocifetch($ship_loop));
	}
?>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b><? echo $qty; ?></b></font></td>
		<td colspan="4"><font size="2" face="Verdana"><b>TOTALS</b></font></td>
		<td width="10%"><font size="2" face="Verdana"><b><? echo $wt; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="6"><font size="2" face="Verdana">SPECIAL NOTES & REMARKS:<br><? echo $header_info['rem1']."<br>".$header_info['rem2']; ?></font></td>
	</tr>
</table>
<?
	$sql = "SELECT FOOTER_NOTES FROM STEEL_BOL_FOOTERS
			WHERE COMMODITY_CODE = '".$header_info['comm']."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(ocifetch($stid)){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td width="100%" align="right"><font size="2" face="Verdana"><b><? echo ociresult($stid, "FOOTER_NOTES"); ?></b></font></td>
	</tr>
</table>
<?
	} 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td width="100%" align="left"><br><font size="2" face="Verdana"><b>Signature: ____________________________________________________________________</b></font></td>
	</tr>
</table>
