<?
/*
*	Adam Walter, Nov 2013
*
*	Report to break down EOD (and current) totals of Clementines by either size or PKG house.
*****************************************************************************************************/

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$dates = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$show = $HTTP_POST_VARS['show'];
	$cust = $HTTP_POST_VARS['cust'];
	$comm = $HTTP_POST_VARS['comm'];

   if($eport_customer_id != 0){
	   echo "<font color=\"#FF0000\">User not authorized for this page.  Cancelling Script.";
	   exit;
   }



?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
		<font size="5" face="Verdana" color="#0066CC">Actual Inventory NOW 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="clem_sizepkg_report_index.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel:</b>&nbsp;&nbsp;</td>
		<td><select name="vessel">
					<option value="">Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
				WHERE SHIP_PREFIX IN ('CLEMENTINES')
					AND TO_CHAR(LR_NUM) IN
						(SELECT ARRIVAL_NUM FROM CARGO_TRACKING)
				ORDER BY LR_NUM DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL'] ?></option>
<?
		}
?>
				</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Customer:</font></td>
		<td><select name="cust">
						<option value="All">All</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME 
				FROM CUSTOMER_PROFILE 
				WHERE CUSTOMER_STATUS = 'ACTIVE'
					AND CUSTOMER_ID IN (SELECT RECEIVER_ID FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP
										WHERE CT.COMMODITY_CODE = CP.COMMODITY_CODE
										AND CP.COMMODITY_TYPE = 'CLEMENTINES')
				ORDER BY CUSTOMER_ID ASC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
		}
?>
					<select></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Commodity</font></td>
		<td><select name="comm">
						<option value="All">All</option>
<?
		$sql = "SELECT COMMODITY_CODE, COMMODITY_CODE || '-' || COMMODITY_NAME THE_COMM FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = 'CLEMENTINES' ORDER BY COMMODITY_CODE";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['COMMODITY_CODE']; ?>"<? if($row['COMMODITY_CODE'] == $comm){ ?> selected <? } ?>><? echo $row['THE_COMM'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Group by:</font></td>
		<td><select name="show">
					<option value="">Please Select an Option</option>
						<option value="SIZE"<? if($show == "SIZE"){ ?> selected <? } ?>>Size</option>
						<option value="PKG HSE"<? if($show == "PKG HSE"){ ?> selected <? } ?>>PKG House</option>
					</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $vessel != "" && $show != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b><? echo $show; ?></b></font></td>
<?
		$sql_list = "('99999'";
		$display_list = array();
		$all_choice_list = array();

		if($show == "SIZE"){
			$field = "CARGO_SIZE";
		} else {
			$field = "EXPORTER_CODE";
		}
		$sql = "SELECT ".$field." THE_COLUMN, COUNT(DISTINCT PALLET_ID) THE_REC_PLTS
				FROM CARGO_TRACKING
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND DATE_RECEIVED IS NOT NULL ";
		if($cust != "All"){
			$sql .= " AND RECEIVER_ID = '".$cust."'";
		}
		if($comm != "All"){
			$sql .= " AND COMMODITY_CODE = '".$comm."'";
		}
		$sql .= "GROUP BY ".$field."
				ORDER BY ".$field;
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			array_push($all_choice_list, $row['THE_COLUMN']);
			$display_list[$row['THE_COLUMN']] = $row['THE_REC_PLTS'];
			$sql_list .= ", '".$row['THE_COLUMN']."'";
			echo "<td><font size=\"2\" face=\"Verdana\"><b>".$row['THE_COLUMN']."</b></font></td>";
		}
?>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Total Pallets</b></font></td>
<?
		// totals first (at the top, no less) as a proof of concept.
		for($i = 0; $i < sizeof($all_choice_list); $i++){
?>
		<td><font size="2" face="Verdana"><a href="clem_pallet_list.php?show=<? echo $show; ?>&vessel=<? echo $vessel; ?>&col=<? echo $all_choice_list[$i]; ?>&date=total&cust=<? echo $cust; ?>&comm=<? echo $comm; ?>" target="pallet_list.php?show=<? echo $show; ?>&vessel=<? echo $vessel; ?>&col=<? echo $all_choice_list[$i]; ?>&date=total&cust=<? echo $cust; ?>&comm=<? echo $comm; ?>"><? echo $display_list[$all_choice_list[$i]]; ?></a></font></td>
<?
		}
		$sql = "SELECT NVL(TO_CHAR(DATE_DISCHARGED, 'MM/DD/YYYY'), 'STILL WORKING') THE_DISCHARGE
				FROM VESSEL_PROFILE
				WHERE ARRIVAL_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$discharge_date = $row['THE_DISCHARGE'];

?>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Straight Shipped<br>(Pallets shipped during discharge, which ended on <? echo $discharge_date; ?>)</b></font></td>
<?
	// columns are now set.  Proceed with each line.
		$display_list = array();
		$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_PALLETS, ".$field." THE_COLUMN
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT
				WHERE CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
					AND CA.CUSTOMER_ID = CT.RECEIVER_ID
					AND CA.PALLET_ID = CT.PALLET_ID
					AND SERVICE_CODE = '6'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND CT.ARRIVAL_NUM = '".$vessel."'
					AND QTY_IN_HOUSE <= 10 ";
		if($discharge_date != "STILL WORKING"){
			$sql .= "AND DATE_OF_ACTIVITY <= TO_DATE('".$discharge_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
		}
		if($cust != "All"){
			$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
		}
		if($comm != "All"){
			$sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
		}
		$sql .= "GROUP BY ".$field."
				ORDER BY ".$field;
	//	echo $sql."<br>";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$display_list[$row['THE_COLUMN']] = $row['THE_PALLETS'];
		}
		for($i = 0; $i < sizeof($all_choice_list); $i++){
	?>
		<td><font size="2" face="Verdana"><? echo (0 + $display_list[$all_choice_list[$i]]); ?></font></td>
	<?
		}
	?>
	</tr>
	<tr bgcolor="99CCCC">
		<td><font size="2" face="Verdana"><b>Left In Inventory at End of Day</b></font></td>
		<td colspan="<? echo sizeof($all_choice_list); ?>">&nbsp;</td>
	</tr>
<?
		$sql = "SELECT DISTINCT TO_CHAR(RUN_DATE, 'DD-MON-YYYY') THE_END_DAY, TO_CHAR(RUN_DATE - 1, 'MM/DD/YYYY') DISP_DATE
				FROM CARGO_DETAIL@BNI
				WHERE LR_NUM = '".$vessel."' ";
		if($discharge_date == "STILL WORKING"){
			// this is actually redundant, so add a fake false clause so no records show
			$sql .= "AND 1 = 2 ";
		} else {
			$sql .= "AND RUN_DATE - 1 > TO_DATE('".$discharge_date."', 'MM/DD/YYYY') ";
		}
		if($cust != "All"){
			$sql .= " AND CARGO_CUSTOMER = '".$cust."' ";
		}
		if($comm != "All"){
			$sql .= " AND COMMODITY_CODE = '".$comm."' ";
		}
		$sql .= "ORDER BY TO_DATE(TO_CHAR(RUN_DATE - 1, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
	//	echo $sql."<br>";
		ora_parse($dates, $sql);
		ora_exec($dates);
		while(ora_fetch_into($dates, $date_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $date_row['DISP_DATE']; ?></font></td>
<?
			$display_list = array();
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, CT.".$field." THE_COLUMN
					FROM CARGO_TRACKING CT, CARGO_DETAIL@BNI CD
					WHERE CT.PALLET_ID = CD.CARGO_ID
						AND CT.ARRIVAL_NUM = CD.LR_NUM
						AND CT.RECEIVER_ID = CD.CARGO_CUSTOMER
						AND CD.RUN_DATE = '".$date_row['THE_END_DAY']."'
						AND CT.ARRIVAL_NUM = '".$vessel."'";
			if($cust != "All"){
				$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
			}
			if($comm != "All"){
				$sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
			}
			$sql .= "GROUP BY CT.".$field."
					ORDER BY CT.".$field;
	//		echo $sql."<br>";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$display_list[$row['THE_COLUMN']] = $row['THE_PALLETS'];
			}
			for($i = 0; $i < sizeof($all_choice_list); $i++){
	?>
		<td><font size="2" face="Verdana"><a href="clem_pallet_list.php?show=<? echo $show; ?>&vessel=<? echo $vessel; ?>&col=<? echo $all_choice_list[$i]; ?>&date=<? echo $date_row['THE_END_DAY']; ?>&cust=<? echo $cust; ?>&comm=<? echo $comm; ?>" target="pallet_list.php?show=<? echo $show; ?>&vessel=<? echo $vessel; ?>&col=<? echo $all_choice_list[$i]; ?>&date=<? echo $date_row['THE_END_DAY']; ?>&cust=<? echo $cust; ?>&comm=<? echo $comm; ?>"><? echo (0 + $display_list[$all_choice_list[$i]]); ?></a></font></td>
<?
			}
?>
	</tr>
<?
		}
?>
</table>
<?
	}
?>
	
