<?
/*
*	Adam Walter, Mar 2014.
*
*	intermediary page for argen fruit availability and orders
*******************************************************************/
/*
//	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
*/

// NOTE:  variables are set in the parent page, as they are already needed up there.
/*	$vessel = $HTTP_GET_VARS['vessel'];
	$cust = $HTTP_GET_VARS['cust'];
	$comm = $HTTP_GET_VARS['comm'];
	$var = $HTTP_GET_VARS['var'];
//	$category = $HTTP_GET_VARS['category'];
	if($var != "all"){
		$extra_sql = "AND VARIETY = '".$var."'";
	} else {
		$extra_sql = "";
	}
*/
	$page_header = "";
	$sql = makeSQL($vessel, $cust, $comm, $var);
//	echo $sql;

//	$pallet_count = 0;
	$case_count = 0;
?>
<table border="1" width="100%" cellpadding="2" cellspacing="0">
<form name="get_data" action="Avail_Drill_index.php?vessel=<? echo $vessel; ?>&cust=1626&comm=<? echo $comm; ?>&var=<? echo $var; ?>" method="post">
<!--<input type="hidden" name="vessel" value="<? $vessel; ?>">
<input type="hidden" name="cust" value="<? $cust; ?>">
<input type="hidden" name="comm" value="<? $comm; ?>">
<input type="hidden" name="var" value="<? $var; ?>"> !-->
	<tr>
		<td colspan="7" align="center"><font size="4" face="Verdana" color="#0000C0">Available Inventory Details as of <? echo date('m/d/Y h:i:s a', mktime(date('G')-3,date('i'),date('s'),date('m'),date('d'),date('Y'))); ?> PST<br>To go back, click on "Inventory" Link on left.  </font><font size="4" face="Verdana" color="#FF0000">Do not use Browser's Back Button.</font><font size="4" face="Verdana" color="#0000C0">  The Back Button will not refresh the totals.</font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><b>Voucher</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Code</b></font></td> 
		<td align="center"><font size="2" face="Verdana"><b>Size</b></font></td>
		<td align="center" bgcolor="#99FF99"><font size="2" face="Verdana"><b>Available Cartons</b></font></td>
		<td align="center"><font size="2" face="Verdana"><b>Select</b></font></td>
	</tr>
<?
	$maxline = 0;
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$maxline++;

		$available = GetAvailableCartonsForDisp(ociresult($short_term_data, "THE_VOUCH"), ociresult($short_term_data, "THE_VAR"), ociresult($short_term_data, "THE_CODE"), 
								ociresult($short_term_data, "THE_SIZE"), $user, $rfconn);
		if($available > 0){
?>
	<input type="hidden" name="voucher[<? echo $maxline; ?>]" value="<? echo ociresult($short_term_data, "THE_VOUCH"); ?>">
	<input type="hidden" name="commodity[<? echo $maxline; ?>]" value="<? echo ociresult($short_term_data, "COMM_CODE"); ?>">
	<input type="hidden" name="variety[<? echo $maxline; ?>]" value="<? echo ociresult($short_term_data, "THE_VAR"); ?>">
	<input type="hidden" name="import_code[<? echo $maxline; ?>]" value="<? echo ociresult($short_term_data, "THE_CODE"); ?>">
	<input type="hidden" name="import_size[<? echo $maxline; ?>]" value="<? echo ociresult($short_term_data, "THE_SIZE"); ?>">
	<input type="hidden" name="cartons[<? echo $maxline; ?>]" value="<? echo $available; ?>">
	<tr>
		<td align="center"><font size="2" face="Verdana"><b><? echo ociresult($short_term_data, "THE_VOUCH"); ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo ociresult($short_term_data, "THE_COMM"); ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo ociresult($short_term_data, "THE_VAR"); ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo ociresult($short_term_data, "THE_CODE"); ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><b><? echo ociresult($short_term_data, "THE_SIZE"); ?></b></font></td>
		<td align="center" bgcolor="#99FF99"><font size="2" face="Verdana"><b><? echo $available; ?></b></font></td>
		<td align="center"><font size="2" face="Verdana"><input type="checkbox" name="include[<? echo $maxline; ?>]" value="Y">
				&nbsp;<input type="text" name="qty_entered[<? echo $maxline; ?>]" size="4" maxlength="4" value="0">cartons</font></td>
	</tr>
<?
			$case_count += $available;
		}
	}
?>
	<input type="hidden" name="maxline" value="<? echo $maxline; ?>">
	<tr>
		<td colspan="7" align="right"><font size="2" face="Verdana"><b>Add To Order</b><br><select name="drop_order_num"><option value="">Select an Order</option>
<?
		$sql = "SELECT ORDER_NUM FROM ARGENFRUIT_ORDER_HEADER WHERE STATUS IN ('2', '1') ORDER BY ORDER_NUM";
		$orders = ociparse($rfconn, $sql);
		ociexecute($orders);
		while(ocifetch($orders)){
?>
							<option value="<? echo ociresult($orders, "ORDER_NUM"); ?>"><? echo ociresult($orders, "ORDER_NUM"); ?></option>
<?
		}
?>
					</select>
		<br><b>----------OR----------<br>Create Order</b><br>Order#:  <input type="text" name="order_num" size="20" maxlength="20"><br>Customer (only needed if new order): <select name="patacust"><option value="">Select a Customer</option>
<?
	$sql = "SELECT CUSTOMER_CODE, CUSTOMER_CODE || '-' || CUSTOMER_NAME THE_NAME
			FROM EXP_CUSTOMER
			ORDER BY CUSTOMER_CODE";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
					<option value="<? echo ociresult($stid, "CUSTOMER_CODE"); ?>"<? if(ociresult($stid, "CUSTOMER_CODE") == $patacust){?> selected <?}?>><? echo ociresult($stid, "THE_NAME"); ?></option>
<?
	}
?>			
				</select><br><input type="submit" name="submit" value="Commit"></td>
	</tr>
	<tr>
		<td colspan="7"><font size="2" face="Verdana"><b>Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "Cartons:  ".number_format($case_count); ?></b></font></td>
	</tr>
</form>
</table>















<?
function makeSQL($vessel, $cust, $comm, $var){
	if($var != "all"){
		$extra_sql = "AND VARIETY = '".$var."'";
	} else {
		$extra_sql = "";
	}

	$sql = "SELECT VARIETY THE_VAR,
				BATCH_ID THE_VOUCH,
				BOL THE_CODE,
				CARGO_SIZE THE_SIZE,
				CP.COMMODITY_NAME THE_COMM,
				CP.COMMODITY_CODE COMM_CODE,
				NVL(SUM(QTY_IN_HOUSE), 0) CASE_TOTAL
			FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
			WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
				AND ARRIVAL_NUM = '".$vessel."' 
				AND RECEIVER_ID = '".$cust."'
				AND CT.COMMODITY_CODE = '".$comm."' 
				AND QTY_IN_HOUSE > 0 
				".$extra_sql."
			GROUP BY VARIETY, BATCH_ID, CARGO_SIZE, CP.COMMODITY_NAME, BOL, CP.COMMODITY_CODE
			ORDER BY BATCH_ID, CP.COMMODITY_NAME, VARIETY, BOL, CARGO_SIZE";
//	echo $sql."<br><br>";
	return $sql;
	
}

function GetAvailableCartonsForDisp($voucher, $variety, $import_code, $import_size, $user, $rfconn){
//				AND (CARGO_SIZE = '".$import_size."' OR BOL = '".$import_code."')
	$sql = "SELECT SUM(QTY_IN_HOUSE) THE_SUM
			FROM CARGO_TRACKING
			WHERE BATCH_ID = '".$voucher."'
				AND VARIETY = '".$variety."'
				AND ('".$import_size."' IS NULL OR CARGO_SIZE = '".$import_size."')
				AND ('".$import_code."' IS NULL OR BOL = '".$import_code."')
				AND DATE_RECEIVED IS NOT NULL
				AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM ARGFRUIT_EXPED WHERE LOGIN_NAME = '".$user."')";
//	echo $sql."<br><br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$IH = ociresult($short_term_data, "THE_SUM");

	// the amount of pallets already reserved on other orders...
//				AND (AOD.IMPORT_CODE = '".$import_code."' OR AOD.IMPORT_SIZE = '".$import_size."')
	$sql = "SELECT SUM(CARTONS) THE_SUM
			FROM ARGENFRUIT_ORDER_DETAIL AOD, ARGENFRUIT_ORDER_HEADER AOH
			WHERE AOD.VOUCHER_NUM = '".$voucher."'
				AND AOD.VARIETY = '".$variety."'
				AND AOH.STATUS IN ('1', '2', '3')
				AND AOD.ORDER_NUM = AOH.ORDER_NUM
				AND (IMPORT_CODE IS NULL OR IMPORT_CODE = '".$import_code."')
				AND (IMPORT_SIZE IS NULL OR IMPORT_SIZE = '".$import_size."')";
//	echo $sql."<br><br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$reserved = ociresult($short_term_data, "THE_SUM");

	// this one is a bit trickier... we want all pallets scanned-out on orders that aren't yet done.
	// we need this value to "add back into" the values above above, since said pallet, while not being "in house",
	// DOES cancel 1 of the "reserved" pallets.
//				AND (CARGO_SIZE = '".$import_size."' OR BOL = '".$import_code."')
	$sql = "SELECT SUM(QTY_CHANGE) THE_SUM
			FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
				AND CT.PALLET_ID = CA.PALLET_ID
				AND CT.RECEIVER_ID = CA.CUSTOMER_ID
				AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM ARGFRUIT_EXPED WHERE LOGIN_NAME = '".$user."')
				AND CT.BATCH_ID = '".$voucher."'
				AND VARIETY = '".$variety."'
				AND ('".$import_size."' IS NULL OR CT.CARGO_SIZE = '".$import_size."')
				AND ('".$import_code."' IS NULL OR CT.BOL = '".$import_code."')
				AND DATE_RECEIVED IS NOT NULL
				AND CA.SERVICE_CODE = '6'
				AND (CA.ACTIVITY_DESCRIPTION IS NULL)
				AND CA.ORDER_NUM IN
					(SELECT ORDER_NUM FROM ARGENFRUIT_ORDER_HEADER
					WHERE STATUS IN ('1', '2', '3'))";
//	echo $sql."<br><br>";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$scanned_on_reserve = ociresult($short_term_data, "THE_SUM");

	$total_available = ($IH + $scanned_on_reserve) - $reserved;
//	echo "IH: ".$IH."   res: ".$reserved."      scanned: ".$scanned_on_reserve."<br>";

	return $total_available;
}
