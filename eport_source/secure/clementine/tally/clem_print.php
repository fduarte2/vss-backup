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
	$vessel = $HTTP_POST_VARS['vessel'];

	if($order == "" || $cust == "" || $vessel == ""){
		echo "All Fields must be entered";
		exit;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY 
			WHERE ORDER_NUM = '".strtoupper(trim($order))."' 
				AND ARRIVAL_NUM = '".trim($vessel)."' 
				AND CUSTOMER_ID = '".trim($cust)."' 
				AND SERVICE_CODE IN (6, 7, 13)";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	if(ociresult($Short_term_data, "THE_COUNT") <= 0){
		echo "entered Order# (".$order.") is not a valid Clementine Outbound Order# for Customer# ".$cust." and Arrival ".$vessel;
		exit;
	}

	// Sometimes no picklist exists for orders.  If this is the case, set variable here, to prepare for footnote printing.
	$SignatureCheck = "UNSET";
//	$sql = "SELECT COUNT(*) THE_COUNT FROM ".$SignatureSQLAddon." WHERE ORDERNUM = '".$Order_Num."'";
	$sql = "SELECT COUNT(*) THE_COUNT FROM DC_PICKLIST WHERE ORDERNUM = '".$order."'";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	if(!ocifetch($Short_term_data)){
		$Signature_Check = "NOPICK";
		} elseif(ociresult($Short_term_data, "THE_COUNT") == 0){
			$Signature_Check = "NOPICK";
	};
	$custname = GetCustName($cust, $rfconn);
	$vesname = GetVesName($vessel, $rfconn);

	$sql = "SELECT To_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME 
				FROM CARGO_ACTIVITY 
				WHERE ORDER_NUM = '".$order."' 
					AND ARRIVAL_NUM = '".$vessel."' 
					AND CUSTOMER_ID = '".$cust."'";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	$start = ociresult($Short_term_data, "START_TIME");
	$end = ociresult($Short_term_data, "END_TIME");

	/* Print Commodity Name */
	$sql = "SELECT PORT_COMMODITY_CODE || '-' || DC_COMMODITY_NAME THE_COMM 
				FROM DC_EPORT_COMMODITY 
				WHERE PORT_COMMODITY_CODE = 
					(SELECT COMMODITYCODE FROM DC_ORDER WHERE ORDERNUM = '".$order."')";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	$CommodityName = ociresult($Short_term_data, "THE_COMM");

	$total_plt = 0;
	$total_ctn = 0;

?>
	<table>
		<tr>
			<td><font size="1" face="Verdana">Generated from Eport - /clementine/clem_print.php             Printed on <? echo date('m/d/Y h:i:s a'); ?><font></td>
		</tr>
		<tr>
			<td><font size="1" face="Verdana">PORT OF WILMINGTON CLEMENTINE FRUIT OUTBOUND TALLY<font></td>
		</tr>
		<tr>
			<td><font size="1" face="Verdana">Customer: <? echo $custname; ?><font></td>
		</tr>
		<tr>
			<td><font size="1" face="Verdana">Order#: <? echo $order; ?><font></td>
		</tr>
<?
	if($cust == "835") {
?>
		<tr>
			<td><font size="1" face="Verdana">Vessel#: <? echo $vesname; ?><font></td>
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
			<td><font size="1" face="Verdana">Commodity: <? echo $CommodityName; ?><font></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>

	<table>
		<tr>
			<td><font size="1" face="Verdana"><b>BARCODE</b></font></td>
			<td><font size="1" face="Verdana"><b>PKGH</b></font></td>
			<td><font size="1" face="Verdana"><b>WEIGHT</b></font></td>
			<td><font size="1" face="Verdana"><b>SIZE</b></font></td>
			<td><font size="1" face="Verdana"><b>ORIG</b></font></td>
			<td><font size="1" face="Verdana"><b>ACTUAL</b></font></td>
			<td><font size="1" face="Verdana"><b>RG/HSP</b></font></td>
			<td><font size="1" face="Verdana"><b>DMG</b></font></td>
			<td><font size="1" face="Verdana"><b>CONT#</b></font></td>
			<td><font size="1" face="Verdana"><b>CHECKR</b></font></td>
		</tr>

<?
	/* Process the rows of the order*/
	$sql = "SELECT CA.ACTIVITY_NUM, CT.PALLET_ID THE_PALLET, CT.EXPORTER_CODE THE_PKGH, CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, CT.CARGO_SIZE THE_SIZE, CT.BATCH_ID THE_ORIG, 
				CA.QTY_CHANGE THE_CHANGE, CA.SERVICE_CODE THE_SERVICE, CT.CARGO_STATUS THE_STATUS, NVL(CA.BATCH_ID, '0') THE_BATCH, CT.CONTAINER_ID THE_CONTAINER, ACTIVITY_ID THE_CHECKER 
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
			WHERE CT.PALLET_ID = CA.PALLET_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID 
				AND CA.SERVICE_CODE IN (6, 7, 13) 
				AND CA.ORDER_NUM = '".$order."' 
				AND CT.ARRIVAL_NUM = '".$vessel."' 
				AND CT.RECEIVER_ID = '".$cust."'
				AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) 
				AND CA.QTY_CHANGE != 0 
			ORDER BY CT.EXPORTER_CODE, CT.PALLET_ID, CA.ACTIVITY_NUM";
	$pallets = ociparse($rfconn, $sql);
	ociexecute($pallets);
	while(ocifetch($pallets)){
		$total_ctn += ociresult($pallets, "THE_CHANGE");
?>
		<tr>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_PALLET"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_PKGH"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_WEIGHT"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_SIZE"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_ORIG"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_CHANGE"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_STATUS"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_BATCH"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($pallets, "THE_CONTAINER"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo str_pad(trim(get_employee_for_print(ociresult($pallets, "THE_PALLET"), $vessel, $cust, ociresult($pallets, "ACTIVITY_NUM"), $rfconn)),6); ?></font></td>
		</tr>

<?
		if($SignatureCheck <> "NOPICK") {
			// if the sSignatureCheck wasn't already set due to lack of picklist,
			// perform this looping check to see if any pallets don't match the picklist, and throw an exception for later.

			$sql = "SELECT * FROM DC_ORDERDETAIL DO, DC_PICKLIST DP 
						WHERE DO.ORDERNUM = DP.ORDERNUM 
							AND DO.ORDERDETAILID = DP.ORDERDETAILID 
							AND DO.ORDERNUM = '".$order."' 
							AND DP.PACKINGHOUSE = '".trim(ociresult($pallets, "THE_PKGH"))."' 
							AND TO_NUMBER(SIZEHIGH) >= ".trim(ociresult($pallets, "THE_SIZE"))." 
							AND TO_NUMBER(SIZELOW) <= ".trim(ociresult($pallets, "THE_SIZE"));
			// echo $sql_pl." \n";

			$Short_term_data = ociparse($rfconn, $sql);
			ociexecute($Short_term_data);
			if (!ocifetch($Short_term_data)) {
				$SignatureCheck = "SHOW";
			}
		}
	}
?>
		<tr>
			<td colspan="5" align="right"><font size="1" face="Verdana">Total Cases:</font></td>
			<td><font size="1" face="Verdana"><? echo $total_ctn; ?></font></td>
			<td colspan="4">&nbsp;</td>
		</tr>
<?
	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM 
				(SELECT PALLET_ID, SUM(QTY_CHANGE) THE_CHANGE 
				FROM CARGO_ACTIVITY 
				WHERE ARRIVAL_NUM = '".$vessel."' 
					AND CUSTOMER_ID = '".$cust."' 
					AND ORDER_NUM = '".$order."' 
					AND SERVICE_CODE IN (6, 7, 13) 
					AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) 
				GROUP BY PALLET_ID) 
			WHERE THE_CHANGE > 0 ";
	//echo $sql_totalpallets."\n";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	$total_plt = ociresult($Short_term_data, "THE_COUNT");
?>
		<tr>
			<td colspan="5" align="right"><font size="1" face="Verdana">Total Pallets:</font></td>
			<td><font size="1" face="Verdana"><? echo $total_plt; ?></font></td>
			<td colspan="4">&nbsp;</td>
		</tr>
	</table><br>
<?
/* If Customer ID is 835 (used to be 439) print the sub total summary */
	if ($cust == '835') {
?>
	<table>
		<tr>
			<td><font size="1" face="Verdana"><b>WEIGHT</b></font></td>
			<td><font size="1" face="Verdana"><b>CTNS</b></font></td>
			<td><font size="1" face="Verdana"><b>PLTS</b></font></td>
		</tr>
<?
		$sql = "SELECT CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE 
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
				WHERE CT.PALLET_ID = CA.PALLET_ID 
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID 
					AND CA.SERVICE_CODE IN (6, 7, 13) 
					AND CA.ORDER_NUM = '".$order."' 
					AND CT.ARRIVAL_NUM = '".$vessel."' 
					AND CT.RECEIVER_ID = '".$cust."' 
					AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) 
					AND CA.QTY_CHANGE != 0 
				GROUP BY CT.WEIGHT || CT.WEIGHT_UNIT 
				HAVING SUM(CA.QTY_CHANGE) > 0 
				ORDER BY CT.WEIGHT || CT.WEIGHT_UNIT";
	  
		//echo $sql_cust;

		$summary = ociparse($rfconn, $sql);
		ociexecute($summary);
		while(ocifetch($summary)){
?>
		<tr>
			<td><font size="1" face="Verdana"><? echo ociresult($summary, "THE_WEIGHT"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($summary, "THE_CHANGE"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($summary, "THE_PALLETS"); ?></font></td>
		</tr>
<?
		}
?>
	</table><br>
<?
	}	
?>
	<table>
		<tr>
			<td><font size="1" face="Verdana"><b>BOL</b></font></td>
			<td><font size="1" face="Verdana"><b>CTNS</b></font></td>
			<td><font size="1" face="Verdana"><b>PLTS</b></font></td>
		</tr>
<?
	/* Print BOL Sub Totals */
	$sql = "SELECT NVL(CT.BOL, 'NA') THE_BOL, COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE 
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA 
			WHERE CT.PALLET_ID = CA.PALLET_ID 
				AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM 
				AND CA.CUSTOMER_ID = CT.RECEIVER_ID 
				AND CA.SERVICE_CODE IN (6, 7, 13) 
				AND CA.ORDER_NUM = '".$order."' 
				AND CT.ARRIVAL_NUM = '".$vessel."' 
				AND CT.RECEIVER_ID = '".$cust."' 
				AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) 
				AND CA.QTY_CHANGE != 0 
			GROUP BY CT.BOL HAVING SUM(CA.QTY_CHANGE) > 0 
			ORDER BY CT.BOL";

	//echo $sql_cust;
	$summary = ociparse($rfconn, $sql);
	ociexecute($summary);
	while(ocifetch($summary)){
?>
		<tr>
			<td><font size="1" face="Verdana"><? echo ociresult($summary, "THE_BOL"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($summary, "THE_CHANGE"); ?></font></td>
			<td><font size="1" face="Verdana"><? echo ociresult($summary, "THE_PALLETS"); ?></font></td>
		</tr>
<?
	}
?>
	</table><br>

<?
	If ($SignatureCheck == "SHOW") {
?>
<font size="1" face="Verdana">This order has pallets that are not present on the original picklist.<br>
Please obtain an authorized signature.<br><br><br>
X_________________________________________________________<br></font>
<?
	}        
?>













<?
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

function GetVesName($vessel, $rfconn){
	$sql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	$vesname = ociparse($rfconn, $sql);
	ociexecute($vesname);
	if(!ocifetch($vesname)){
		return $vessel."-"."TRUCKED-IN";
	} else {
		return $vessel."-".ociresult($vesname, "VESSEL_NAME");
	}
}

function GetCustName($cust, $rfconn){
	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
	$Short_term_data = ociparse($rfconn, $sql);
	ociexecute($Short_term_data);
	ocifetch($Short_term_data);
	$custname = ociresult($Short_term_data, "CUSTOMER_NAME");

	return $custname;
/*	if(!ocifetch($vesname)){
		return $vessel."-"."TRUCKED-IN";
	} else {
		return $vessel."-".ociresult($vesname, "VESSEL_NAME");
	}
*/
}
