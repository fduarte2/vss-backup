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


	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$date_cursor = ora_open($conn);
	$vessel_cursor = ora_open($conn);
	$WO_cursor = ora_open($conn);
	$order_loop_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
/*
	$rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($rf_conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rf_conn));
		exit;
	}
	$exped_cursor = ora_open($rf_conn);
*/

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
<form name="get_data" action="steel_activity.php" method="post">
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
			FROM CUSTOMER_PROFILE CP, CHILEAN_EXPEDITER_CUST_LIST_RF CECL 
			WHERE CECL.EPORT_LOGIN = '".$user."' 
				AND CECL.CUSTOMER_ID = CP.CUSTOMER_ID 
		UNION
			SELECT CP.CUSTOMER_ID, CP.CUSTOMER_NAME
			FROM CUSTOMER_PROFILE CP
			WHERE CUSTOMER_ID = '".$HTTP_COOKIE_VARS["eport_customer_id"]."'
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
	$sql = "SELECT LR_NUM, VESSEL_NAME
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
			ORDER BY LR_NUM DESC";
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
		<td bgcolor="CCFF99"><font size="2" face="Verdana"><b>WO# / Deliver To:</b></font></td>
		<td><font size="2" face="Verdana"><b>Order#</b></font></td>
		<td><font size="2" face="Verdana"><b>Carrier</b></font></td>
		<td><font size="2" face="Verdana"><b>Trailer Lic</b></font></td>
		<td><font size="2" face="Verdana"><b>BL#</b></font></td>
		<td><font size="2" face="Verdana"><b>Mark</b></font></td>
		<td><font size="2" face="Verdana"><b>#PCS</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight</b></font></td>
	<tr>
<?
		if($vessel != "all_vessels"){
			$extra_sql = " AND LR_NUM = '".$vessel."' ";
		} else {
			$extra_sql = "";
		}
		if($comm != "all_comms"){
			$extra_sql = " AND CM.COMMODITY_CODE = '".$comm."' ";
		} else {
			$extra_sql = "";
		}


		// 1st sub-totals:  by date
		$sql = "SELECT DISTINCT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE
				FROM CARGO_ACTIVITY
				WHERE LOT_NUM IN
					(SELECT LOT_NUM FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
					WHERE CT.LOT_NUM = CM.CONTAINER_NUM
					AND OWNER_ID = '".$cust."'".$extra_sql."
					)
				AND SERVICE_CODE = '6200'
				AND DATE_OF_ACTIVITY >= TO_DATE('".$from_date."', 'MM/DD/YYYY')
				AND DATE_OF_ACTIVITY <= TO_DATE('".$to_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
				ORDER BY TO_DATE(TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
		ora_parse($date_cursor, $sql);
		ora_exec($date_cursor);
		if(!ora_fetch_into($date_cursor, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="10" align="center"><font size="2" face="Verdana"><b>No Records in System for Given Parameters</b></font></td>
	<tr>
<?
		} else {
			do {
				$date_total_pcs = 0;
				$date_total_weight = 0;
?>
	<tr>
		<td colspan="10" bgcolor="FFCC99"><font size="2" face="Verdana"><b><? echo $date_row['THE_DATE']; ?></b></font></td>
	<tr>
<?
				// 2nd subtotals:  by vessel
				$sql = "SELECT DISTINCT LR_NUM
						FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
						WHERE CT.LOT_NUM = CM.CONTAINER_NUM
						AND OWNER_ID = '".$cust."'".$extra_sql."
						AND LOT_NUM IN
							(SELECT LOT_NUM FROM CARGO_ACTIVITY
							WHERE SERVICE_CODE = '6200'
							AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
							AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')
							)
						ORDER BY LR_NUM";
				ora_parse($vessel_cursor, $sql);
				ora_exec($vessel_cursor);
				while(ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$vessel_total_pcs = 0;
					$vessel_total_weight = 0;

					$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
							WHERE LR_NUM = '".$vessel_row['LR_NUM']."'";
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
		<td colspan="9" bgcolor="99FFFF"><font size="2" face="Verdana"><b><? echo $vessel_row['LR_NUM']." - ".$vessel_name; ?></b></font></td>
	<tr>
<?
					// 3rd subtotals:  by WO# / delivery to
					$sql = "SELECT DISTINCT REPLACE(DELIVERY_NUM || ' - ' || DELIVER_TO, '''', '`') THE_DELIVER
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
							ORDER BY REPLACE(DELIVERY_NUM || ' - ' || DELIVER_TO, '''', '`')";
					ora_parse($WO_cursor, $sql);
					ora_exec($WO_cursor);
//					echo $sql."<br>";
					while(ora_fetch_into($WO_cursor, $WO_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$WO_total_pcs = 0;
						$WO_total_weight = 0;

?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="8" bgcolor="CCFF99"><font size="2" face="Verdana"><b><? echo $WO_row['THE_DELIVER']; ?></b></font></td>
	<tr>
<?
						// inner loop:  everything in the above loops
						$sql = "SELECT CA.ORDER_NUM, CD.DELIVERY_DESCRIPTION, CD.TRANSPORTATION_NUM, CM.CARGO_BOL, CM.CARGO_MARK,
									CA.QTY_CHANGE, ROUND((CA.QTY_CHANGE / CM.QTY_EXPECTED) * CM.CARGO_WEIGHT, 2) THE_WEIGHT
								FROM CARGO_DELIVERY CD, CARGO_ACTIVITY CA, CARGO_MANIFEST CM
								WHERE CD.LOT_NUM = CM.CONTAINER_NUM
								AND CD.LOT_NUM = CA.LOT_NUM
								AND CD.ACTIVITY_NUM = CA.ACTIVITY_NUM
								AND REPLACE(CD.DELIVERY_NUM || ' - ' || CD.DELIVER_TO, '''', '`') = '".$WO_row['THE_DELIVER']."'
								AND CM.LR_NUM = '".$vessel_row['LR_NUM']."'
								AND DATE_OF_ACTIVITY >= TO_DATE('".$date_row['THE_DATE']."', 'MM/DD/YYYY')
								AND DATE_OF_ACTIVITY <= TO_DATE('".$date_row['THE_DATE']." 23:59:59', 'MM/DD/YYYY HH24:MI:SS')";
						ora_parse($order_loop_cursor, $sql);
						ora_exec($order_loop_cursor);
//						echo $sql."<br>";
						while(ora_fetch_into($order_loop_cursor, $order_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
							$date_total_pcs += $order_row['QTY_CHANGE'];
							$date_total_weight += $order_row['THE_WEIGHT'];
							$vessel_total_pcs += $order_row['QTY_CHANGE'];
							$vessel_total_weight += $order_row['THE_WEIGHT'];
							$WO_total_pcs += $order_row['QTY_CHANGE'];
							$WO_total_weight += $order_row['THE_WEIGHT'];

?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $order_row['ORDER_NUM']; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $order_row['DELIVERY_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $order_row['TRANSPORTATION_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $order_row['CARGO_BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $order_row['CARGO_MARK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $order_row['QTY_CHANGE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $order_row['THE_WEIGHT']; ?></font></td>
	<tr>
<?
						}

					// WO is over, give subtotal
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="6" bgcolor="99CC33"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="99CC33"><font size="2" face="Verdana"><b><? echo $WO_total_pcs; ?></b></font></td>
		<td bgcolor="99CC33"><font size="2" face="Verdana"><b><? echo $WO_total_weight; ?></b></font></td>
	<tr>
<?
					}

				// vessel is over, give subtotal
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="7" bgcolor="99CCFF"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="99CCFF"><font size="2" face="Verdana"><b><? echo $vessel_total_pcs; ?></b></font></td>
		<td bgcolor="99CCFF"><font size="2" face="Verdana"><b><? echo $vessel_total_weight; ?></b></font></td>
	<tr>
<?
				}
			// Date is over, give subtotal
?>
	<tr>
		<td colspan="8" bgcolor="FF9999"><font size="2" face="Verdana"><b>Total:</b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo $date_total_pcs; ?></b></font></td>
		<td bgcolor="FF9999"><font size="2" face="Verdana"><b><? echo $date_total_weight; ?></b></font></td>
	<tr>
<?
			} while(ora_fetch_into($date_cursor, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>