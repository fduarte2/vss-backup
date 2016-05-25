<?
/* Adam Walter, 8/25/07
*	This file displays all non-good inventory from a vessel
*	Regardless of whether it's still here or not,
*	nor when it was labelled as non-good.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Cursor = ora_open($conn2);

	$submit = $HTTP_POST_VARS['submit'];
	$comm = $HTTP_POST_VARS['comm'];
	$vessel_num = $HTTP_POST_VARS['vessel_num'];
	$status = $HTTP_POST_VARS['status'];
	$raw_cust = $HTTP_POST_VARS['cust'];
	if($raw_cust == "439O"){
		$cust = "439";
		$sub_sql = " AND SUB_CUSTID = '1512' ";
	} else {
		$cust = $raw_cust;
		$sub_sql = " AND (SUB_CUSTID IS NULL OR SUB_CUSTID != '1512') ";
	}
	
	switch($status){
		case "R":
			$sql_stat = "REGRADE";
			break;
		case "H":
			$sql_stat = "HOSPITAL";
			break;
		default:
			$sql_stat = "";
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
         <font size="5" face="Verdana" color="#0066CC">Reg / Hosp by Vessel</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="data_pick" action="index.php" method="post">
	<tr>
		<td width="20%" align="left"><font size="3" face="Verdana">Vessel:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="vessel_num">
<?
	$sql = "SELECT DISTINCT
				   VP.ARRIVAL_NUM THE_NUM,
				   LR_NUM || '-' || VESSEL_NAME THE_VES
			FROM VESSEL_PROFILE VP
			INNER JOIN CARGO_TRACKING CT
				  ON CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
			WHERE
				VP.SHIP_PREFIX = 'CLEMENTINES'
			ORDER BY VP.ARRIVAL_NUM";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['THE_NUM']; ?>"<? if($Short_Term_row['THE_NUM'] == $vessel_num){ ?> selected <? } ?>><? echo $Short_Term_row['THE_VES']; ?></option>
<?
	}
?>
			</select></td>
		<td width="5%">&nbsp;</td>
		<td width="20%" align="center"><font size="3" face="Verdana">Status:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="status">
				<option value="R">Regrade</option>
				<option value="H">Hospital</option>
			</select></td>
		<td width="10%">&nbsp;</td>
		<td width="20%" align="center"><font size="3" face="Verdana">Customer:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="cust">
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
		<td width="20%" align="right"><font size="3" face="Verdana">Commodity:&nbsp;&nbsp;&nbsp;&nbsp;</font><select name="comm">
<?
	$sql = "SELECT * FROM DC_EPORT_COMMODITY ORDER BY PORT_COMMODITY_CODE";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	while(ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
				<option value="<? echo $Short_Term_row['PORT_COMMODITY_CODE']; ?>" <? if($comm == $Short_Term_row['PORT_COMMODITY_CODE']){ ?> selected <? } ?>><? echo $Short_Term_row['PORT_COMMODITY_CODE']." - ".$Short_Term_row['DC_COMMODITY_NAME']; ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><input name="submit" type="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td colspan="7"><BR><HR></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
		$grand_total = 0;

		$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel_num."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td colspan="9" align="center"><font size="3" face="Verdana"><? echo $Short_Term_row['THE_NAME']; ?> <? echo $sql_stat; ?> Inventory</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>PKG</b></font></td>
		<td><font size="2" face="Verdana"><b>Carton Count</b></font></td>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Scan Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Received Carton Count</b></font></td>
		<td><font size="2" face="Verdana"><b>DMG cartons</b></font></td>
	</tr>
<? 
		$pallet_total = 0;
		$current_size_PKG = "";

		$sql = "SELECT DC_CARGO_DESC THE_SIZE, EXPORTER_CODE, BATCH_ID THE_EXPECTED, QTY_RECEIVED THE_RECEIVED, CT.PALLET_ID THE_PALLET, DECODE(THE_TIME, NULL, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH:MI AM'), TO_CHAR(THE_TIME, 'MM/DD/YYYY HH:MI AM')) THE_DATE, QTY_DAMAGED
				FROM DC_CARGO_TRACKING CT, 
				(SELECT MIN(DIL.INSPECTION_DATETIME) THE_TIME, PALLET_ID 
	   		   			FROM SAG_OWNER.DC_INSPECTED_PALLET DIP,
	   		   	 		SAG_OWNER.DC_INSPECTION_LOG DIL
				WHERE DIP.TRANSACTION_ID = DIL.TRANSACTION_ID
				AND ARRIVAL_NUM = '".$vessel_num."'
				AND NEW_STATUS IN ('".$status."', 'B')
				GROUP BY PALLET_ID) IT
		WHERE CT.PALLET_ID = IT.PALLET_ID (+)
		AND CT.ARRIVAL_NUM = '".$vessel_num."'
		AND CT.CARGO_STATUS IN ('".$status."', 'B')
		AND CT.DATE_RECEIVED > '01-oct-2007'
		AND CT.COMMODITY_CODE = '".$comm."'
		AND CT.RECEIVER_ID = '".$cust."'".$sub_sql."
		ORDER BY THE_SIZE, EXPORTER_CODE, THE_PALLET";
//		echo $sql;
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ // for each record
			if($row['THE_SIZE'].$row['EXPORTER_CODE'] != $current_size_PKG){ // if this is a new size/PKG combination
				$current_size_PKG = $row['THE_SIZE'].$row['EXPORTER_CODE']; // Set current size/PKG
				if($pallet_total > 0){ // I.E. not the first run
?>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b><? echo $pallet_total; ?> PLTS</b></font></td>
		<td colspan="7">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="9">&nbsp;</td>
	</tr>
<?
					$pallet_total = 0;
				}
			}
			$pallet_total++;
			$grand_total++;
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['THE_SIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['EXPORTER_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_EXPECTED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo substr($row['THE_PALLET'], 17, 6); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $row['QTY_DAMAGED']); ?></font></td>
	</tr>
<?
		}
?>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b><? echo $pallet_total; ?> PLTS</b></font></td>
		<td colspan="7">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="9" align="center"><font size="3" face="Verdana"><b>Grand Total:  <? echo $grand_total; ?></b></font></td>
	</tr>
</table>
<?
	}
?>