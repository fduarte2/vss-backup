<?
/* Adam Walter, 8/25/07
*	This file simply displays on Eport a grid of inventory,
*	Packing houses across the top of the grid,
*	Sizes down the left.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$cursor_third = ora_open($conn2);
	$cursor_second = ora_open($conn2);
	$cursor_first = ora_open($conn2);
	$Short_Term_Cursor = ora_open($conn2);

	$S_O_D_pallets = 0;
	$PL_PALLETS = 0;
	$E_O_D_pallets = 0;

	$vessel = $HTTP_POST_VARS['vessel'];
	$status = $HTTP_POST_VARS['status'];
	$raw_cust = $HTTP_POST_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}
	switch ($status){
		case "R":
			$display_header = "REGRADE";
			$extra_sql_one = "= 'R'";
			$extra_sql_two = "= 'REGRADE LOAD'";
			break;
		case "GOOD":
			$display_header = "GOOD";
			$extra_sql_one = "IS NULL";
			$extra_sql_two = "= 'CUSTOMER LOAD'";
			break;
		case "H":
			$display_header = "HOSPITAL";
			$extra_sql_one = "= 'H'";
			$extra_sql_two = "= 'HOSPITAL LOAD'";
			break;
		case "B":
			$display_header = "BOTH";
			$extra_sql_one = "= 'B'";
			$extra_sql_two = "= 'BOTH LOAD'";
			break;
	}

	// $eport_customer_id comes from index.php
	if($eport_customer_id == 0){
		$extra_sql1 = "";
		$extra_sql2 = "";
	} else {
		$extra_sql1 = "AND RECEIVER_ID = '".$eport_customer_id."'";
		$extra_sql2 = "AND CUSTOMER_ID = '".$eport_customer_id."'";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Expected Available Inventories <? echo date('m/d/Y'); ?> <? echo $display_header; ?></font>
         <hr>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="status_check" action="index.php" method="post">
	<tr>
		<td width="30%" align="left"><font size="3" face="Verdana">Status:<BR></font><select name="status"><option value="">Select Status</option>
			<option value="GOOD">Good</option>
			<option value="H">Hospital</option>
			<option value="R">Regrade</option>
			<option value="B">Both</option>
			</select></td>
		<td width="5%">&nbsp;</td>
		<td width="30%" align="center"><font size="3" face="Verdana">Customer:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="cust">
<?
	$sql = "SELECT * FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM EPORT_LOGIN WHERE USER_TYPE = 'CLEMENTINE' AND CUSTOMER_ID != 0)
			".$extra_sql2."
			ORDER BY CUSTOMER_ID";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// we gotta do something strange for oppy-439, so...
		if($Short_Term_row['CUSTOMER_ID'] != '439'){
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?></option>
<?
		} else {
?>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>" <? if($raw_cust == $Short_Term_row['CUSTOMER_ID']){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - NO OPPY</option>
				<option value="<? echo $Short_Term_row['CUSTOMER_ID']; ?>O" <? if($raw_cust == "439O"){ ?> selected <? } ?>><? echo $Short_Term_row['CUSTOMER_NAME']; ?> - OPPY ONLY</option>
<?
		}
	}
?>
			</select></td>
		<td width="5%">&nbsp;</td>
		<td width="30%" align="right"><font size="3" face="Verdana">Vessel:<BR></font><select name="vessel"><option value="">Select Vessel</option>
<?
		$sql = "SELECT DISTINCT CT.ARRIVAL_NUM THE_LR, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE VP, CARGO_TRACKING CT WHERE CT.COMMODITY_CODE LIKE '560%' AND LENGTH(CT.EXPORTER_CODE) = 4 AND CT.ARRIVAL_NUM = VP.LR_NUM ORDER BY CT.ARRIVAL_NUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['THE_LR']; ?>"><? echo $row['THE_VESSEL']; ?></option>
<?
		}
?>				
			</select></td>
	</tr>
	<tr>
		<td colspan="5" align="center"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;<BR><HR></td>
	</tr>
</form>
</table>
<?
	if($status != "" && $vessel != "") {
		$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$display_vessel = $row['THE_NAME'];
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana"><? echo $display_header; ?> Predictions for <? echo $display_vessel; ?></font><BR><font size="2" face="Verdana">(Disclaimer:  Values may change as Daily picklists are modified)</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>PKG house</b></font></td>
		<td><font size="2" face="Verdana"><b>Cumulative Total</b></font></td>
		<td><font size="2" face="Verdana"><b>Current Total</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Non-Complete Picklists</b></font></td>
		<td><font size="2" face="Verdana"><b>End of day Expected Available</b></font></td>
	</tr>
<?
		$sql = "SELECT COUNT(*) THE_TOTAL, SUM(DECODE(MARK, 'SHIPPED', 0, 1)) THE_CURRENT, EXPORTER_CODE, DC_CARGO_DESC THE_SIZE
				FROM DC_CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."'
				AND CARGO_STATUS ".$extra_sql_one."
				AND RECEIVER_ID = '".$cust."'".$sub_sql."
				AND DATE_RECEIVED IS NOT NULL AND DATE_RECEIVED > '01-oct-2007'
				GROUP BY EXPORTER_CODE, DC_CARGO_DESC
				ORDER BY DC_CARGO_DESC, EXPORTER_CODE";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		if(!ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			echo "<font size=\"3\" face=\"Verdana\" color=\"#FF0000\"><b>No pallets in-house on start of day ".date('m/d/Y')."</b></font>";
		} else {
			do {
			$print_size = $first_row['THE_SIZE'];
			$print_PKG = $first_row['EXPORTER_CODE'];
			$cumulative = $first_row['THE_TOTAL'];
			$current = $first_row['THE_CURRENT'];

			$total_cumu += $cumulative;
			$total_current += $current;

//						AND DCC.PORT_CUSTOMER_ID = '".$cust."'
			$sql = "SELECT NVL(SUM(PALLETQTY), 0) THE_SUM 
					FROM DC_ORDER DCO, DC_PICKLIST DCP, DC_CUSTOMER DCC 
					WHERE DCO.ORDERNUM = DCP.ORDERNUM 
						AND DCO.CUSTOMERID = DCC.CUSTOMERID
						AND DCP.PACKINGHOUSE = '".$print_PKG."' 
						AND TO_CHAR(DCP.PICKLISTSIZE) = '".$print_size."' 
						AND DCO.LOADTYPE ".$extra_sql_two." 
						AND DCO.PICKUPDATE >= '".date('d-M-Y')."' 
						AND DCO.PICKUPDATE < '".date('d-M-Y', mktime(0,0,0,date('m'),date('d') + 1,date('Y')))."' 
						AND DCO.VESSELID = '".$vessel."' 
						AND DCO.ORDERSTATUSID IN ('3', '4', '5', '7')";
//			echo $sql;
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$expected_for_day = $second_row['THE_SUM'];

			$PL_PALLETS += $expected_for_day;
			$E_O_D_pallets += ($current - $expected_for_day);
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $print_size; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $print_PKG; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $cumulative; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $current; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $expected_for_day; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $current - $expected_for_day; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}

?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Totals:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo (0 + $total_cumu); ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo (0 + $total_current); ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $PL_PALLETS; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $E_O_D_pallets; ?></b></font></td>
	</tr>
<?
	}
?>
</table>