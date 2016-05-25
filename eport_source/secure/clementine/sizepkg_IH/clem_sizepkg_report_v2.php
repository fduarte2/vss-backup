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
	$row_value = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$show = $HTTP_POST_VARS['show'];
	$cust = $HTTP_POST_VARS['cust'];
	$comm = $HTTP_POST_VARS['comm'];
	$date = $HTTP_POST_VARS['date'];

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
					<option value="" selected>Please Select a Vessel</option>
<?
//					AND CONT_UNLOADING = 'Y'
		$sql = "SELECT DISTINCT
				   VP.ARRIVAL_NUM LR_NUM,
				   LR_NUM || '-' || VESSEL_NAME THE_VESSEL
			FROM VESSEL_PROFILE VP
			INNER JOIN CARGO_TRACKING CT
				  ON CT.ARRIVAL_NUM = VP.ARRIVAL_NUM
			WHERE
				VP.SHIP_PREFIX = 'CLEMENTINES'
			ORDER BY VP.ARRIVAL_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?>  <? } ?>><? echo $row['THE_VESSEL'] ?></option>
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
		<td><font size="2" face="Verdana">Only for date:</font></td>
		<td><input type="text" name="date" size="10" value="<? echo $date; ?>"><a href="javascript:show_calendar('report_form.start_date');" 
					onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=22 height=22 border=0></a>
        </td>
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
		$total_rec = 0;
		$total_ship_dis = 0;
		$total_day = array();

		$sql = "SELECT NVL(TO_CHAR(DATE_DISCHARGED, 'MM/DD/YYYY'), 'STILL WORKING') THE_DISCHARGE
				FROM VESSEL_PROFILE
				WHERE ARRIVAL_NUM = '".$vessel."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$discharge_date = $row['THE_DISCHARGE'];

		$output = "
<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
	<tr>
		<td><font size=\"2\" face=\"Verdana\"><b>".$show."</b></font></td>
		<td><font size=\"2\" face=\"Verdana\"><b>Total Pallets</b></font></td>
		<td><font size=\"2\" face=\"Verdana\"><b>Straight Shipped</b>(Pallets shipped during discharge, which ended on".$discharge_date.")</font></td>";

		$date_list = array();

		$sql = "SELECT DISTINCT TO_CHAR(RUN_DATE, 'DD-MON-YYYY') THE_END_DAY, TO_CHAR(RUN_DATE - 1, 'MM/DD/YYYY') DISP_DATE
				FROM CARGO_DETAIL@BNI
				WHERE LR_NUM = '".$vessel."' ";
		if($date != ""){
			// this is actually redundant, so add a fake false clause so no records show
			$sql .= "AND RUN_DATE - 1 = TO_DATE('".$date."', 'MM/DD/YYYY') ";
		}
		if($cust != "All"){
			$sql .= " AND CARGO_CUSTOMER = '".$cust."' ";
		}
		if($comm != "All"){
			$sql .= " AND COMMODITY_CODE = '".$comm."' ";
		}
		$sql .= "ORDER BY TO_DATE(TO_CHAR(RUN_DATE - 1, 'MM/DD/YYYY'), 'MM/DD/YYYY')";
		ora_parse($dates, $sql);
		ora_exec($dates);
		while(ora_fetch_into($dates, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			array_push($date_list, $row['DISP_DATE']);
			$output .= "<td><font size=\"2\" face=\"Verdana\">".$row['DISP_DATE']."</font></td>";
		}
		$output .= "
	</tr>";

		// columns are set, lets get the rows in
//		$sql_list = "('99999'";
//		$display_list = array();
//		$all_choice_list = array();

		if($show == "SIZE"){
			$field = "DC_CARGO_DESC";
		} else {
			$field = "EXPORTER_CODE";
		}
		$sql = "SELECT ".$field." THE_COLUMN, COUNT(DISTINCT PALLET_ID) THE_REC_PLTS
				FROM DC_CARGO_TRACKING
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
		ora_parse($row_value, $sql);
		ora_exec($row_value);
		while(ora_fetch_into($row_value, $row_value_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

			$output .= "
	</tr>";

			$total_rec += $row_value_row['THE_REC_PLTS'];
			$output .= "<td><font size=\"2\" face=\"Verdana\"><b>".$row_value_row['THE_COLUMN']."</b></font></td>";
			$output .= "<td><font size=\"2\" face=\"Verdana\"><b>".$row_value_row['THE_REC_PLTS']."</b></font></td>";
		
			// shipped during discharge
			//	AND QTY_IN_HOUSE <= 10 ";
			$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_PALLETS
					FROM CARGO_ACTIVITY CA, DC_CARGO_TRACKING CT
					WHERE CA.ARRIVAL_NUM = CT.ARRIVAL_NUM
						AND CA.CUSTOMER_ID = CT.RECEIVER_ID
						AND CA.PALLET_ID = CT.PALLET_ID
						AND SERVICE_CODE = '6'
						AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
						AND CT.ARRIVAL_NUM = '".$vessel."'
						AND CT.".$field." = '".$row_value_row['THE_COLUMN']."'";
			if($discharge_date != "STILL WORKING"){
				$sql .= "AND DATE_OF_ACTIVITY <= TO_DATE('".$discharge_date." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ";
			}
			if($cust != "All"){
				$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
			}
			if($comm != "All"){
				$sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
			}
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$total_ship_dis += $row['THE_PALLETS'];
			$output .= "<td><font size=\"2\" face=\"Verdana\"><b>".$row['THE_PALLETS']."</b></font></td>";

			// and E.O.D values for each day
			for($i = 0; $i < sizeof($date_list); $i++){
				$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS
						FROM DC_CARGO_TRACKING CT, CARGO_DETAIL@BNI CD
						WHERE CT.PALLET_ID = CD.CARGO_ID
							AND CT.ARRIVAL_NUM = CD.LR_NUM
							AND CT.RECEIVER_ID = CD.CARGO_CUSTOMER
							AND CD.RUN_DATE = TO_DATE('".$date_list[$i]."', 'MM/DD/YYYY')
							AND CT.".$field." = '".$row_value_row['THE_COLUMN']."'
							AND CT.ARRIVAL_NUM = '".$vessel."'";
				if($cust != "All"){
					$sql .= " AND CT.RECEIVER_ID = '".$cust."' ";
				}
				if($comm != "All"){
					$sql .= " AND CT.COMMODITY_CODE = '".$comm."' ";
				}
//				echo $sql."<br>";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$output .= "<td><font size=\"2\" face=\"Verdana\">".$row['THE_PALLETS']."</font></td>";
				$total_day[$i] += $row['THE_PALLETS'];
			}
			$output .= "
	</tr>";
		}
		$output .= "
	<tr>
		<td><font size=\"2\" face=\"Verdana\"><b>Totals:</b></font></td>
		<td><font size=\"2\" face=\"Verdana\"><b>".$total_rec."</b></font></td>
		<td><font size=\"2\" face=\"Verdana\"><b>".$total_ship_dis."</b></font></td>";
		for($i = 0; $i < sizeof($date_list); $i++){
			$output .= "
		<td><font size=\"2\" face=\"Verdana\"><b>".$total_day[$i]."</b></font></td>";
		}
		$output .= "
</table>";
	}

	$filename = "./linkedfiles/".date('mdYhis').".xls";
	$handle = fopen($filename, "w");
	fwrite($handle, $output);
	fclose($handle);
	
	$output = str_replace("cellspacing=\"0\">", "cellspacing=\"0\"><tr><td colspan=\"".(3 + sizeof($date_list))."\" align=\"center\"><a href=\"".$filename."\">Click Here for the Excel Version.</a></td></tr>", $output);
	echo $output;
