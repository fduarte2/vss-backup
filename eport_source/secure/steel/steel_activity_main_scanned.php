<?
/*
*	Adam Walter, Jan 2011.
*
*	This page is a listing of all OUTGOING activity
*	Options:
*		- Date Range (required)
*		- Customer (forced)
*		- Vessel (optional)
*************************************************************************/

	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];
	$view_cust = $HTTP_POST_VARS['view_cust'];
	if($view_cust != ""){
		$user_cust_num = $view_cust;
	} else {
		$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	}


/*	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
*/
	$rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
	// $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($rf_conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rf_conn));
		exit;
	}
	$exped_cursor = ora_open($rf_conn);
	$date_cursor = ora_open($rf_conn);
	$vessel_cursor = ora_open($rf_conn);
	$DO_cursor = ora_open($rf_conn);
	$order_cursor = ora_open($rf_conn);
	$pallet_loop_cursor = ora_open($rf_conn);
	$Short_Term_Cursor = ora_open($rf_conn);


	$vessel = $HTTP_POST_VARS['vessel'];
	$all_vessels = $HTTP_POST_VARS['all_vessels'];
	if($all_vessels == "all_vessels"){
		$vessel = "all_vessels";
	}
	$comm = $HTTP_POST_VARS['comm'];
	$all_comms = $HTTP_POST_VARS['all_comms'];
	if($all_comms == "all_comms"){
		$comm = "all_comms";
	}

	$cust = $HTTP_POST_VARS['cust'];
	$from_date = $HTTP_POST_VARS['from_date'];
	$to_date = $HTTP_POST_VARS['to_date'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit != "" && ($from_date == "" || $to_date == "")){
		echo "<font size=3 face=Verdana color=#FF0000>Both dates must be entered</font><br>";
	}

	if($from_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $from_date)) {
		echo "<font size=3 face=Verdana color=#FF0000>Starting Date must be in MM/DD/YYYY format (was entered as ".$from_date.")</font><br>";
		$from_date = "";
	}
	if($to_date != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $to_date)) {
		echo "<font size=3 face=Verdana color=#FF0000>Ending Date must be in MM/DD/YYYY format (was entered as ".$to_date.")</font><br>";
		$to_date = "";
	}


?>
<script language="JavaScript" src="../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Outbound Activity
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="steel_activity_scanned.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Date From:</b></font></td>
		<td align="left"><input name="from_date" type="text" size="10" maxlength="10" value="<? echo $from_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.from_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Date Through:</b></font></td>
		<td align="left"><input name="to_date" type="text" size="10" maxlength="10" value="<? echo $to_date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.to_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Customer:</b></font></td>
		<td align="left"><select name="cust">
<?
	$sql = "SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME 
			FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
			WHERE CECL.EPORT_LOGIN = '".$user."' 
				AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
		UNION
			SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
			FROM CUSTOMER_PROFILE CP
			WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
			AND CUSTOMER_ID != 0
		ORDER BY CUSTOMER_ID";
/*
				(SELECT OWNER_ID FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
				WHERE CT.LOT_NUM = CM.CONTAINER_NUM
				AND CT.DATE_RECEIVED > SYSDATE - 730
				AND CT.COMMODITY_CODE IN
					(SELECT COMMODITY_CODE
					FROM COMMODITY_PROFILE
					WHERE COMMODITY_TYPE = 'STEEL'
					)
				)
*/
//	echo $sql."<br><br>";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['CUSTOMER_ID']; ?>" <? if($row['CUSTOMER_ID'] == $cust){?>selected<?}?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
	}
?>		
		</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td align="left"><select name="vessel">
<?
/*	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE LR_NUM IN
				(SELECT LR_NUM FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
				WHERE CT.LOT_NUM = CM.CONTAINER_NUM
				AND CT.DATE_RECEIVED > SYSDATE - 730
				AND OWNER_ID IN 
					(SELECT CP.CUSTOMER_ID 
						FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST_RF CECL 
						WHERE CECL.EPORT_LOGIN = '".$user."' 
							AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
					UNION
						SELECT CP.CUSTOMER_ID
						FROM CUSTOMER_PROFILE CP
						WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
					)
				)
			ORDER BY LR_NUM DESC";*/
	$sql = "SELECT LR_NUM, VESSEL_NAME
			FROM VESSEL_PROFILE
			WHERE SHIP_PREFIX = 'STEEL'
				AND TO_CHAR(LR_NUM) IN
					(SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE DATE_RECEIVED > SYSDATE - 730 
						AND RECEIVER_ID IN
							(SELECT CP.CUSTOMER_ID 
								FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
								WHERE CECL.EPORT_LOGIN = '".$user."' 
									AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
							UNION
								SELECT CP.CUSTOMER_ID
								FROM CUSTOMER_PROFILE CP
								WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
							)
					)";
				
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['LR_NUM']; ?>" <? if($row['LR_NUM'] == $vessel){?>selected<?}?>><? echo $row['VESSEL_NAME'] ?></option>
<?
	}
?>
		</select>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="all_vessels" value="all_vessels" <? if($vessel == "all_vessels"){?>checked<?}?>>&nbsp;&nbsp;<font size="2" face="Verdana">All Vessels</font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana"><b>Commodity:</b></font></td>
		<td align="left"><select name="comm">
<?
	$sql = "SELECT COMMODITY_CODE, COMMODITY_NAME
			FROM COMMODITY_PROFILE
			WHERE COMMODITY_TYPE = 'STEEL'
				AND COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM CARGO_TRACKING
					WHERE DATE_RECEIVED > SYSDATE - 730
						AND RECEIVER_ID IN
						(SELECT CP.CUSTOMER_ID 
							FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST CECL 
							WHERE CECL.EPORT_LOGIN = '".$user."' 
								AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
						UNION
							SELECT CP.CUSTOMER_ID
							FROM CUSTOMER_PROFILE CP
							WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
						)
					)
			ORDER BY COMMODITY_CODE";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
					<option value="<? echo $row['COMMODITY_CODE']; ?>" <? if($row['COMMODITY_CODE'] == $comm){?>selected<?}?>><? echo $row['COMMODITY_NAME'] ?></option>
<?
	}
?>
		</select>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="all_comms" value="all_comms" <? if($comm == "all_comms"){?>checked<?}?>>&nbsp;&nbsp;<font size="2" face="Verdana">All Commodities</font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Records"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	</form>
</table>

<?
	if($from_date != "" && $to_date != "" && $submit != ""){
?>
<table border="2" width="100%" cellpadding="2" cellspacing="0">
	<tr>
		<td bgcolor="FFCC99"><font size="2" face="Verdana"><b>Date</b></font></td>
		<td bgcolor="99FFFF"><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td bgcolor="CCFF99"><font size="2" face="Verdana"><b>DO# / Deliver To:</b></font></td>
		<td bgcolor="CCCCFF"><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Carrier</b></font></td>
		<td><font size="2" face="Verdana"><b>Trailer Lic</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Mark</b></font></td>
		<td><font size="2" face="Verdana"><b>#PCS</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
	<tr>
<?
		if($vessel != "all_vessels"){
			$extra_sql = " AND ARRIVAL_NUM = '".$vessel."' ";
		} else {
			$extra_sql = "";
		}
		if($comm != "all_comms"){
			$extra_sql = " AND CT.COMMODITY_CODE = '".$comm."' ";
		} else {
			$extra_sql = "";
		}


		// 1st sub-totals:  by date
		$sql = "SELECT DISTINCT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE
				FROM CARGO_ACTIVITY
				WHERE PALLET_ID IN
					(SELECT PALLET_ID 
						FROM CARGO_TRACKING CT
						WHERE RECEIVER_ID = '".$cust."'".$extra_sql."
					)
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND DATE_OF_ACTIVITY >= TO_DATE('".$from_date."', 'MM/DD/YYYY')
					AND DATE_OF_ACTIVITY <= TO_DATE('".$to_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
				ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
		ora_parse($date_cursor, $sql);
		ora_exec($date_cursor);
		if(!ora_fetch_into($date_cursor, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="12" align="center"><font size="2" face="Verdana"><b>No Records in System for Given Parameters</b></font></td>
	<tr>
<?
		} else {
			do {
				$date_total_pcs = 0;
				$date_total_weight = 0;
?>
	<tr>
		<td colspan="12" bgcolor="FFCC99"><font size="2" face="Verdana"><b><? echo $date_row['THE_DATE']; ?></b></font></td>
	<tr>
<?
				// 2nd subtotals:  by vessel
				$sql = "SELECT DISTINCT ARRIVAL_NUM
						FROM CARGO_TRACKING CT
						WHERE RECEIVER_ID = '".$cust."'".$extra_sql."
						AND (PALLET_ID, ARRIVAL_NUM, RECEIVER_ID) IN
							(SELECT PALLET_ID, ARRIVAL_NUM, CUSTOMER_ID 
							FROM CARGO_ACTIVITY
							WHERE SERVICE_CODE = '6'
							AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
							AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
							AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
							)
						ORDER BY ARRIVAL_NUM";
				ora_parse($vessel_cursor, $sql);
				ora_exec($vessel_cursor);
				while(ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$vessel_total_pcs = 0;
					$vessel_total_weight = 0;

					$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
							WHERE LR_NUM = '".$vessel_row['ARRIVAL_NUM']."'";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
					if(!ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$vessel_name = "unknown";
					} else {
						$vessel_name = $short_term_row['VESSEL_NAME'];
					}
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="11" bgcolor="99FFFF"><font size="2" face="Verdana"><b><? echo $vessel_row['ARRIVAL_NUM']." - ".$vessel_name; ?></b></font></td>
	<tr>
<?
					// 3rd subtotals:  by DO# / delivery to
/*					$sql = "SELECT DISTINCT REPLACE(DELIVERY_NUM || ' - ' || DELIVER_TO, '''', '`') THE_DELIVER
							FROM CARGO_DELIVERY
							WHERE (LOT_NUM, ACTIVITY_NUM) IN
								(SELECT LOT_NUM, ACTIVITY_NUM
								FROM CARGO_ACTIVITY
								WHERE SERVICE_CODE = '6200'
								AND DELIVERY_NUM > 0
								AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
								AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
								AND LOT_NUM IN
									(SELECT LOT_NUM FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
									WHERE CT.LOT_NUM = CM.CONTAINER_NUM
									AND OWNER_ID = '".$cust."'
									AND LR_NUM = '".$vessel_row['LR_NUM']."'
									)
								)
							ORDER BY REPLACE(DELIVERY_NUM || ' - ' || DELIVER_TO, '''', '`')"; */
					$sql = "SELECT DISTINCT REMARK FROM CARGO_TRACKING CT
							WHERE (PALLET_ID, ARRIVAL_NUM, RECEIVER_ID) IN
								(SELECT PALLET_ID, ARRIVAL_NUM, CUSTOMER_ID
								FROM CARGO_ACTIVITY
								WHERE SERVICE_CODE = '6'
									AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
									AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
									AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
								)
								AND RECEIVER_ID = '".$cust."'".$extra_sql."
								AND ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
							ORDER BY REMARK";					
					ora_parse($DO_cursor, $sql);
					ora_exec($DO_cursor);
//					echo $sql."<br>";
					while(ora_fetch_into($DO_cursor, $WO_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$WO_total_pcs = 0;
						$WO_total_weight = 0;

						$sql = "SELECT * FROM STEEL_SHIPPING_TABLE
								WHERE SHIP_TO_ID = (SELECT SHIP_TO_ID FROM STEEL_PRELOAD_DO_INFORMATION WHERE DONUM = '".$WO_row['REMARK']."')";
//						echo $sql."<br><br>";
						ora_parse($Short_Term_Cursor, $sql);
						ora_exec($Short_Term_Cursor);
						ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
						$third_subsection_string = $WO_row['REMARK']." - ".$short_term_row['NAME']."  ".$short_term_row['ADDRESS_1']."  ".$short_term_row['ADDRESS_2']."  ".$short_term_row['CITY'].",  ".$short_term_row['STATE']."  ".$short_term_row['ZIP'];
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="10" bgcolor="CCFF99"><font size="2" face="Verdana"><b><? echo $third_subsection_string; ?></b></font></td>
	<tr>
<?
						// 4th sub total: by order
						$sql = "SELECT DISTINCT CA.ORDER_NUM
								FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, STEEL_CARRIERS SC, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO
								WHERE CA.PALLET_ID = CT.PALLET_ID
									AND CA.CUSTOMER_ID = CT.RECEIVER_ID
									AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
									AND CA.SERVICE_CODE = '6'
									AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
									AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
									AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
									AND CA.ORDER_NUM = SO.PORT_ORDER_NUM
									AND SO.DONUM = SPDI.DONUM
									AND SO.ORDER_STATUS = 'COMPLETE'
									AND SPDI.CARRIER_ID = SC.CARRIER_ID
									AND CT.RECEIVER_ID = '".$cust."'".$extra_sql."
									AND CT.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
									AND CT.REMARK = '".$WO_row['REMARK']."'
								ORDER BY CA.ORDER_NUM";
						ora_parse($order_cursor, $sql);
						ora_exec($order_cursor);
	//					echo $sql."<br>";
						while(ora_fetch_into($order_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$order_total_pcs = 0;
							$order_total_weight = 0;
?>

	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="9" bgcolor="CCCCFF"><font size="2" face="Verdana"><b><? echo $order_row['ORDER_NUM']." ".$order_row['BOL']; ?>&nbsp;</b></font></td>
	</tr>
<?
						// inner loop:  everything in the above loops
/*						$sql = "SELECT CA.ORDER_NUM, CD.DELIVERY_DESCRIPTION, CD.TRANSPORTATION_NUM, CM.CARGO_BOL, CM.CARGO_MARK,
									CA.QTY_CHANGE, ROUND((CA.QTY_CHANGE / CM.QTY_EXPECTED) * CM.CARGO_WEIGHT, 2) THE_WEIGHT
								FROM CARGO_DELIVERY CD, CARGO_ACTIVITY CA, CARGO_MANIFEST CM
								WHERE CD.LOT_NUM = CM.CONTAINER_NUM
								AND CD.LOT_NUM = CA.LOT_NUM
								AND CD.ACTIVITY_NUM = CA.ACTIVITY_NUM
								AND REPLACE(CD.DELIVERY_NUM || ' - ' || CD.DELIVER_TO, '''', '`') = '".$WO_row['THE_DELIVER']."'
								AND CM.LR_NUM = '".$vessel_row['LR_NUM']."'
								AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
								AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')"; */
/*						$sql = "SELECT CA.ORDER_NUM, SC.NAME, SO.LICENSE_NUM, SPDI.BOL, CT.CARGO_DESCRIPTION, 
									SUM(CA.QTY_CHANGE) THE_SUM, SUM((CA.QTY_CHANGE / CT.QTY_RECEIVED) * CT.WEIGHT) THE_WEIGHT
								FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, STEEL_CARRIERS SC, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO
								WHERE CA.PALLET_ID = CT.PALLET_ID
									AND CA.CUSTOMER_ID = CT.RECEIVER_ID
									AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
									AND CA.SERVICE_CODE = '6'
									AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
									AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
									AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
									AND CA.ORDER_NUM = SO.PORT_ORDER_NUM
									AND SO.DONUM = SPDI.DONUM
									AND SO.ORDER_STATUS = 'COMPLETE'
									AND SPDI.CARRIER_ID = SC.CARRIER_ID
									AND CT.RECEIVER_ID = '".$cust."'
									AND CT.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
									AND CT.REMARK = '".$WO_row['REMARK']."'
								GROUP BY CA.ORDER_NUM, SC.NAME, SO.LICENSE_NUM, SPDI.BOL, CT.CARGO_DESCRIPTION
								ORDER BY CA.ORDER_NUM, CT.CARGO_DESCRIPTION";*/
							$sql = "SELECT SC.NAME, SO.LICENSE_STATE || ' ' || SO.LICENSE_NUM THE_LIC_NAME, CT.PALLET_ID, CT.CARGO_DESCRIPTION, SPDI.BOL, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS') DATE_REC,
										SUM(CA.QTY_CHANGE) THE_SUM, SUM((CA.QTY_CHANGE / CT.QTY_RECEIVED) * CT.WEIGHT) THE_WEIGHT
									FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, STEEL_CARRIERS SC, STEEL_PRELOAD_DO_INFORMATION SPDI, STEEL_ORDERS SO
									WHERE CA.PALLET_ID = CT.PALLET_ID
										AND CA.CUSTOMER_ID = CT.RECEIVER_ID
										AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
										AND CA.SERVICE_CODE = '6'
										AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
										AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
										AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
										AND CA.ORDER_NUM = SO.PORT_ORDER_NUM
										AND CA.ORDER_NUM = '".$order_row['ORDER_NUM']."'
										AND SO.DONUM = SPDI.DONUM
										AND SO.ORDER_STATUS = 'COMPLETE'
										AND SPDI.CARRIER_ID = SC.CARRIER_ID
										AND CT.RECEIVER_ID = '".$cust."'".$extra_sql."
										AND CT.ARRIVAL_NUM = '".$vessel_row['ARRIVAL_NUM']."'
										AND CT.REMARK = '".$WO_row['REMARK']."'
									GROUP BY SC.NAME, SO.LICENSE_STATE || ' ' || SO.LICENSE_NUM, CT.PALLET_ID, CT.CARGO_DESCRIPTION, SPDI.BOL, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS')
									ORDER BY CT.CARGO_DESCRIPTION, CT.PALLET_ID";
							ora_parse($pallet_loop_cursor, $sql);
							ora_exec($pallet_loop_cursor);
	//						echo $sql."<br>";
							while(ora_fetch_into($pallet_loop_cursor, $pallet_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
								$date_total_pcs += $pallet_row['THE_SUM'];
								$date_total_weight += $pallet_row['THE_WEIGHT'];
								$vessel_total_pcs += $pallet_row['THE_SUM'];
								$vessel_total_weight += $pallet_row['THE_WEIGHT'];
								$order_total_pcs += $pallet_row['THE_SUM'];
								$order_total_weight += $pallet_row['THE_WEIGHT'];
								$WO_total_pcs += $pallet_row['THE_SUM'];
								$WO_total_weight += $pallet_row['THE_WEIGHT'];

?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['THE_LIC_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['DATE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['CARGO_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['THE_SUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_row['THE_WEIGHT']; ?></font></td>
	<tr>
<?
							}

					// order is over, give subtotal
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="7" bgcolor="9999FF"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="9999FF"><font size="2" face="Verdana"><b><? echo $order_total_pcs; ?></b></font></td>
		<td bgcolor="9999FF"><font size="2" face="Verdana"><b><? echo $order_total_weight; ?></b></font></td>
	<tr>
<?
						}

					// WO is over, give subtotal
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="8" bgcolor="99CC33"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="99CC33"><font size="2" face="Verdana"><b><? echo $WO_total_pcs; ?></b></font></td>
		<td bgcolor="99CC33"><font size="2" face="Verdana"><b><? echo $WO_total_weight; ?></b></font></td>
	<tr>
<?
					}

				// vessel is over, give subtotal
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="9" bgcolor="99CCFF"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="99CCFF"><font size="2" face="Verdana"><b><? echo $vessel_total_pcs; ?></b></font></td>
		<td bgcolor="99CCFF"><font size="2" face="Verdana"><b><? echo $vessel_total_weight; ?></b></font></td>
	<tr>
<?
				}
			// Date is over, give subtotal
?>
	<tr>
		<td colspan="10" bgcolor="FF9999"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo $date_total_pcs; ?></b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo $date_total_weight; ?></b></font></td>
	<tr>
<?
			} while(ora_fetch_into($date_cursor, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>