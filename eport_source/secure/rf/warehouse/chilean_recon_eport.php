<?
/*
*	Adam Walter, Feb 2010.
*
*	This page is a one-stop shop for Chilean Data.
*	Displays pallets and commoditites (all),
*	Date and qty received (only for those received),
*	And date and quantity shipped (only for those with activity records)
*
*	This used to be on S-16 (and a backup copy can still be located there,
*	Probably), but as it was a nice report for all to use,
*	It migrated to Eport.
*************************************************************************/

/*
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 */
	$user = $HTTP_COOKIE_VARS["eport_user"];

	$conn = ora_logon("SAG_OWNER@RF", "OWNER"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	if($ves_type == "truck"){
		$vessel = $HTTP_POST_VARS['truck'];
	} else {
		$vessel = $HTTP_POST_VARS['vessel'];
	}
	$cust = $HTTP_POST_VARS['cust'];
	$show = $HTTP_POST_VARS['show'];
	$comm = $HTTP_POST_VARS['comm'];

	$all_cust = $HTTP_POST_VARS['all_cust'];
	$duplicate = $HTTP_POST_VARS['duplicate'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Chilean/Argentine Fruit Reconciliation Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="chilean_recon_eport_index.php" method="post">
	<tr>
		<td align="left">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><input type="radio" name="ves_type" value="vessel"<? if($ves_type != "truck"){?> checked <?}?>></td>
					<td><font size="2" face="Verdana">Vessel:&nbsp;&nbsp;<select name="vessel">
								<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX IN ('CHILEAN', 'ARG FRUIT') ORDER BY LR_NUM DESC";
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
					<td colspan="2">---OR---</td>
				</tr>
				<tr>
					<td><input type="radio" name="ves_type" value="truck"<? if($ves_type == "truck"){?> checked <?}?>></td>
					<td><font size="2" face="Verdana">Trucked In:&nbsp;&nbsp;<select name="truck">
						<option value="">Please Select an Order#</option>
<?
		$sql = "SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING 
				WHERE COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IN ('CHILEAN', 'ARG FRUIT', 'BRAZILLIAN', 'PERUVIAN'))
				AND RECEIVING_TYPE = 'T'
				ORDER BY ARRIVAL_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['ARRIVAL_NUM']; ?>"<? if($row['ARRIVAL_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['ARRIVAL_NUM'] ?></option>
<?
		}
?>

					</select></font></td>
				</tr>
			</table></td>
		<td align="center"><font size="2" face="Verdana">Customer:<br><select name="cust">
														<option value="">Please Select a Customer</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_STATUS = 'ACTIVE'"; 
		if($eport_customer_id != 0){
			if($eport_customer_id == "9999"){
				$sql .= "AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
			} else {
				$sql .= "AND CUSTOMER_ID = '".$eport_customer_id."' ";
			}
		}
		$sql .= " ORDER BY CUSTOMER_ID ASC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
		}
?>
												<select></font></td>
		<td align="right"><font size="2" face="Verdana">&nbsp;&nbsp;&nbsp;<? if($eport_customer_id == 0){?>All-Customer <?}?>Condensed</font><br><input type="checkbox" name="all_cust" value="all"<? if($all_cust != ""){?> checked <?}?>><br><font size="2" face="Verdana">Duplicate Barcode Report</font><br><input type="checkbox" name="duplicate" value="all"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Commodity (non-condensed):<br><select name="comm">
						<option value="All">All</option>
<?
		$sql = "SELECT COMMODITY_CODE, COMMODITY_CODE || '-' || COMMODITY_NAME THE_COMM FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['COMMODITY_CODE']; ?>"<? if($row['COMMODITY_CODE'] == $comm){ ?> selected <? } ?>><? echo $row['THE_COMM'] ?></option>
<?
		}
?>
					</select></font></td>
		<td>&nbsp;</td>
		<td align="right"><font size="2" face="Verdana">Show Records (non-condensed):<br><select name="show">
														<option value="all">All</option>
														<option value="act"<? if($show == "act"){ ?> selected <? } ?>>With Outbound Activity</option>
														<option value="noact"<? if($show == "noact"){ ?> selected <? } ?>>No Outbound Activity</option>
														<option value="norec"<? if($show == "norec"){ ?> selected <? } ?>>Not Received</option>
								</select></font></td>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	$csv_output = "";
	$filename = $vessel."-".date('mdYhis').".csv";
	$reference_loc = "/rf/warehouse/safe_to_delete_files/".$filename;
	$fullpath = "/var/www/secure/".$reference_loc;
	$handle = fopen($fullpath, "w");

	if($vessel != "" && $cust != "" && $all_cust == "" && $duplicate == ""){
		/*************************************************************************************
		*	This is for a one-customer detail report
		*
		**************************************************************************************/
		$total_pallets = 0;
		$total_pallets_received = 0;
		$total_cases_received = 0;
		$total_pallets_acted_on = 0;
		$delivery_transfer = 0;
		$total_cases_acted_on = 0;		// note: this one will fluctuate +/- as returns happen

		if($comm == "All"){
			$comm_sql = "";
		} else {
			$comm_sql = "AND CT.COMMODITY_CODE = '".$comm."' ";
		}

		if($show == "norec"){
			$rec_sql = "AND DATE_RECEIVED IS NULL ";
		} else {
			$rec_sql = "";
		}
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="10" align="center"><font size="3" face="Verdana\"><b><a href="<? echo $reference_loc; ?>">Click Here for the .CSV File</a></b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse (Assigned)</b></font></td>
		<td><font size="2" face="Verdana"><b>Warehouse (Actual)</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY of Activity</b></font></td>
		<td><font size="2" face="Verdana"><b>Activity Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Activity</b></font></td>
		<td><font size="2" face="Verdana"><b>Activity Order #</b></font></td>
	</tr>
<?
		$csv_output .= "Barcode,Commodity,Date Received,QTY Received,Warehouse (Assigned),Warehouse (Actual),QTY of Activity,Activity Type,Date of Activity,Activity Order #\n";

		// top level SQL, gets all pallets associated with this ship/customer.
		$sql = "SELECT CT.PALLET_ID, COMMODITY_NAME, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NR') THE_REC, QTY_RECEIVED, WAREHOUSE_LOCATION, ACTUAL_LOCATION 
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, CARGO_TRACKING_ADDITIONAL_DATA CTAD 
				WHERE CT.ARRIVAL_NUM = '".$vessel."' 
					AND CT.RECEIVER_ID = '".$cust."' 
					AND CT.COMMODITY_CODE = CP.COMMODITY_CODE
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CTAD.PALLET_ID
					".$comm_sql.$rec_sql."
				ORDER BY CT.PALLET_ID";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			
			// now, decide what to do with each pallet.
			// if no activity, we show the row with no data under last 4 columns (activity being a non-voided outbound or return)
			// if >= 1 activity, we show rows going across
			$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$cust."' AND PALLET_ID = '".$first_row['PALLET_ID']."' AND SERVICE_CODE NOT IN ('1', '2', '8', '9', '12', '16', '24') AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) AND NOT (SERVICE_CODE = '17' AND QTY_CHANGE = QTY_LEFT) AND ACTIVITY_NUM != '1' ";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$first_row['PALLET_ID']."' AND ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$cust."' AND SERVICE_CODE = '9'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$recoup = $Short_Term_row['THE_SUM'];

			$total_pallets++;
			if($first_row['THE_REC'] != "NR"){
				$total_pallets_received++;
				$total_cases_received += $first_row['QTY_RECEIVED'];
			}

			if($second_row['THE_COUNT'] == 0 && $show != "act"){
				// if no activity, we show the row with no data under last 4 columns

?>
	<tr <? if ($first_row['THE_REC'] == "NR") {?> bgcolor="#33CC00" <?} else {?> bgcolor="#FFFFFF" <?}?>>
		<td><font size="2" face="Verdana"><? echo $first_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['COMMODITY_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['THE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['QTY_RECEIVED']; if($recoup != 0){ echo " (".$recoup." Recouped)"; }?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['WAREHOUSE_LOCATION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['ACTUAL_LOCATION']; ?></font></td>
		<td colspan="4" align="center"><font size="2" face="Verdana">---NONE---</font></td>
	</tr>
<?
				$csv_output .= $first_row['PALLET_ID'].",".$first_row['COMMODITY_NAME'].",".$first_row['THE_REC'].",".$first_row['QTY_RECEIVED'];
				if($recoup != 0){ $csv_output .= " (".$recoup." Recouped)"; }
				$csv_output .= ",".$first_row['WAREHOUSE_LOCATION'].",".$first_row['ACTUAL_LOCATION'];
				$csv_output .= ",---NONE---\n";

			} elseif($second_row['THE_COUNT'] != 0 && $show != "noact") {
				// we show rows, with the count of the previous query being the RowCount of the pallet in the table

?>
	<tr>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['PALLET_ID']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['COMMODITY_NAME']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['THE_REC']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['QTY_RECEIVED']; if($recoup != 0){ echo " (".$recoup." Recouped)"; }?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['WAREHOUSE_LOCATION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['ACTUAL_LOCATION']; ?></font></td>
<?
				// now we get the activities.  SAME WHERE CLAUSE as above (save for the join to SERVICE_CATEGORY); 
				// be sure of it, or this goes wonky.
				$sql = "SELECT ACTIVITY_NUM, QTY_CHANGE, CA.SERVICE_CODE, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') THE_ACT, UPPER(SERVICE_NAME) THE_SERV, DECODE(CA.SERVICE_CODE, 17, SUBSTR(ACTIVITY_DESCRIPTION, 21), NVL(ORDER_NUM, 'N/A')) THE_ORD FROM CARGO_ACTIVITY CA, SERVICE_CATEGORY SC WHERE ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$cust."' AND PALLET_ID = '".$first_row['PALLET_ID']."' AND CA.SERVICE_CODE NOT IN ('1', '2', '8', '9', '12', '16', '24') AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) AND NOT (CA.SERVICE_CODE = '17' AND QTY_CHANGE = QTY_LEFT) AND CA.SERVICE_CODE = SC.SERVICE_CODE AND CA.ACTIVITY_NUM != '1' ORDER BY ACTIVITY_NUM";
				ora_parse($cursor_third, $sql);
				ora_exec($cursor_third);
				ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				// do this for the first record
				$total_pallets_acted_on++;
				if($third_row['SERVICE_CODE'] == 6 || $third_row['SERVICE_CODE'] == 11){
					$delivery_transfer++;
				}
				$total_cases_acted_on += $third_row['QTY_CHANGE'];
				if($third_row['SERVICE_CODE'] == 17){
					$bgcolor = "#80FFFE";
				} else {
					$bgcolor = "#FFFFFF";
				}
?>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['QTY_CHANGE']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['THE_SERV']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['THE_ACT']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['THE_ORD']; ?></font></td>
<?
				$csv_output .= $first_row['PALLET_ID'].",".$first_row['COMMODITY_NAME'].",".$first_row['THE_REC'].",".$first_row['QTY_RECEIVED'];
				if($recoup != 0){ $csv_output .= " (".$recoup." Recouped)"; }
				$csv_output .= ",".$first_row['WAREHOUSE_LOCATION'].",".$first_row['ACTUAL_LOCATION'].",".$third_row['QTY_CHANGE'].",".$third_row['THE_SERV'].",".$third_row['THE_ACT'].",".$third_row['THE_ORD']."\n";

				while(ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// and this if there is more than 1 activity, repeate this procedure
					$total_cases_acted_on += $third_row['QTY_CHANGE'];
					if($third_row['SERVICE_CODE'] == 17){
						$bgcolor = "#80FFFE";
					} else {
						$bgcolor = "#FFFFFF";
					}

?>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><? echo $first_row['WAREHOUSE_LOCATION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['ACTUAL_LOCATION']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['QTY_CHANGE']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['THE_SERV']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['THE_ACT']; ?></font></td>
		<td bgcolor="<? echo $bgcolor; ?>"><font size="2" face="Verdana"><? echo $third_row['THE_ORD']; ?></font></td>
<?
//					$csv_output .= $first_row['PALLET_ID'].",".$first_row['COMMODITY_NAME'].",".$first_row['THE_REC'].",".$first_row['QTY_RECEIVED'];
					$csv_output .= $first_row['PALLET_ID'].",".$first_row['COMMODITY_NAME'].",".$first_row['THE_REC'].",";
//					if($recoup != 0){ $csv_output .= " (".$recoup." Recouped)"; }
					$csv_output .= ",".$first_row['WAREHOUSE_LOCATION'].",".$first_row['ACTUAL_LOCATION'].",".$third_row['QTY_CHANGE'].",".$third_row['THE_SERV'].",".$third_row['THE_ACT'].",".$third_row['THE_ORD']."\n";

				}
?>
	</tr>
<?
				// lastly, if the final activity was a RETURN, we remove this pallet from the counter at the bottom.
				// we are only interested in service codes of ship or return, hence the "code in" clause
				$sql = "SELECT MAX(DATE_OF_ACTIVITY), SERVICE_CODE FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$first_row['PALLET_ID']."' AND CUSTOMER_ID = '".$cust."' AND ARRIVAL_NUM = '".$vessel."' AND SERVICE_CODE IN ('6', '7', '11', '13') AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) GROUP BY SERVICE_CODE ORDER BY MAX(DATE_OF_ACTIVITY) DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['SERVICE_CODE'] == "7" || $row['SERVICE_CODE'] == "13"){
					$total_pallets_acted_on--;
				}
			}
		}

		$csv_output .= "TOTALS\n";
		$csv_output .= "Plt: ".$total_pallets.",,Plt Rcvd: ".$total_pallets_received.",Ctn Rcvd: ".$total_cases_received.",,,Case Activity: ".
								$total_cases_acted_on.",Pallet Activity: ".$total_pallets_acted_on.",Deliveries/Transfers: ".$delivery_transfer."\n";

		fwrite($handle, $csv_output);
		fclose($handle);
?>
<tr>
	<td colspan="10" align="center"><font size="2" face="Verdana"><b>TOTALS:</b></font></td>
</tr>
<tr>
	<td><font size="2" face="Verdana">Plt: <? echo $total_pallets; ?></font></td>
	<td>&nbsp;</td>
	<td><font size="2" face="Verdana">Plt Rcvd: <? echo $total_pallets_received; ?></font></td>
	<td><font size="2" face="Verdana">Ctn Rcvd: <? echo $total_cases_received; ?></font></td>
	<td colspan="2">&nbsp;</td>
	<td><font size="2" face="Verdana">Case Activity: <? echo $total_cases_acted_on; ?></font></td>
	<td colspan="2"><font size="2" face="Verdana">Pallet Activity: <? echo $total_pallets_acted_on; ?></font></td>
	<td><font size="2" face="Verdana">Deliveries/Transfers: <? echo $delivery_transfer; ?></font></td>
</tr>
<?
		$sql = "SELECT NVL(SUM(QTY), 0) THE_CASES, NVL(SUM(PLTCOUNT), 0) THE_PLTS 
			FROM CLR_MAIN_DATA
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CUSTOMER_ID = '".$cust."'";
		ora_parse($cursor_second, $sql);
		ora_exec($cursor_second);
		if(!ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$cases_expected_clr = "N/A";
			$plt_expected_clr = "N/A";
		} else {
			$cases_expected_clr = $second_row['THE_CASES'];
			$plt_expected_clr = $second_row['THE_PLTS'];
		}
?>
<tr>
	<td><font size="2" face="Verdana">Mnfstd Plts (Main Board):</font></td>
	<td><font size="2" face="Verdana"><? echo $plt_expected_clr; ?></font></td>
	<td colspan="8">&nbsp;</td>
</tr>
<tr>
	<td><font size="2" face="Verdana">Mnfstd Ctns (Main Board):</font></td>
	<td><font size="2" face="Verdana"><? echo $cases_expected_clr; ?></font></td>
	<td colspan="8">&nbsp;</td>
</tr>
</table>
<?
	} elseif($vessel != "" && $all_cust != ""){
		$bgcolor = "#EEEEEE";
		/*************************************************************************************
		*	This is for an all-customer detail report
		*
		**************************************************************************************/
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>PLTs Expected<br>(Chilean Uploads only)</b></font></td>
		<td><font size="2" face="Verdana"><b>CTNs Expected<br>(Chilean Uploads only)</b></font></td>
		<td><font size="2" face="Verdana"><b>PLTs Expected<br>(Main Board)</b></font></td>
		<td><font size="2" face="Verdana"><b>CTNs Expected<br>(Main Board)</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT Received</b></font></td>
		<td><font size="2" face="Verdana"><b>CTN Received</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>CTN Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>PLT w/Damage</b></font></td>
		<td><font size="2" face="Verdana"><b>CTN Damaged</b></font></td>
	</tr>
<?
		$total_plt_expec_clr = 0;
		$total_cases_expec_clr = 0;
		$total_plt_expec = 0;
		$total_cases_expec = 0;
		$total_plt_rec = 0;
		$total_cases_rec = 0;
		$total_plt_rec_autotrans = 0;
		$total_cases_rec_autotrans = 0;
		$total_plt_sent_autotrans = 0;
		$total_cases_sent_autotrans = 0;
		$total_plt_ship = 0;
		$total_cases_ship = 0;
		$total_plt_dmg = 0;
		$total_cases_dmg = 0;


		$sql = "SELECT RECEIVER_ID, SUM(DECODE(DATE_RECEIVED, NULL, 0, 1)) THE_PLT, SUM(DECODE(DATE_RECEIVED, NULL, 0, QTY_RECEIVED)) THE_CASES 
				FROM CARGO_TRACKING 
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND COMMODITY_CODE IN
						(SELECT COMMODITY_CODE 
						FROM COMMODITY_PROFILE 
						WHERE COMMODITY_TYPE IN ('CHILEAN', 'ARG FRUIT')
						) AND RECEIVER_ID <> '9722' ";
//						)";
		if($eport_customer_id != 0){
			if($eport_customer_id == "9999"){
				$sql .= "AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
			} else {
				$sql .= "AND RECEIVER_ID = '".$eport_customer_id."' ";
			}
//			$sql .= " AND RECEIVER_ID = '".$eport_customer_id."'";
		}
		$sql .= " GROUP BY RECEIVER_ID ORDER BY RECEIVER_ID";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$plt_rec = $first_row['THE_PLT'];
			$case_rec = $first_row['THE_CASES'];

			// figure out any received via autotrans
			$sql = "SELECT COUNT(*) THE_PLT, SUM(QTY_CHANGE) THE_CASES FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '17' AND ACTIVITY_NUM = '1' AND ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$first_row['RECEIVER_ID']."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($second_row['THE_PLT'] == 0){
				$plt_rec_autotrans = 0;
				$case_rec_autotrans = 0;
			} else {
				$plt_rec_autotrans = $second_row['THE_PLT'];
				$case_rec_autotrans = $second_row['THE_CASES'];

//				$plt_rec -= $plt_rec_autotrans;
//				$case_rec -= $case_rec_autotrans;
			}

			// subtract any auto-trans'ed out
			$sql = "SELECT COUNT(*) THE_PLT, SUM(QTY_CHANGE) THE_CASES FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '17' AND ACTIVITY_NUM != '1' AND ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$first_row['RECEIVER_ID']."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($second_row['THE_PLT'] == 0){
				$plt_sent_autotrans = 0;
				$case_sent_autotrans = 0;
			} else {
				$plt_sent_autotrans = $second_row['THE_PLT'];
				$case_sent_autotrans = $second_row['THE_CASES'];

				$plt_rec -= $plt_sent_autotrans;
				$case_rec -= $case_sent_autotrans;
			}

			$total_plt_rec += $plt_rec;
			$total_cases_rec += $case_rec;
//			$total_plt_rec_autotrans += $plt_rec_autotrans;
//			$total_cases_rec_autotrans += $case_rec_autotrans;
//			$total_plt_sent_autotrans += $plt_sent_autotrans;
//			$total_cases_sent_autotrans += $case_sent_autotrans;

			// expected pallets (CLR)
			$sql = "SELECT NVL(SUM(QTY), 0) THE_CASES, NVL(SUM(PLTCOUNT), 0) THE_PLTS 
					FROM CLR_MAIN_DATA
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CUSTOMER_ID = '".$first_row['RECEIVER_ID']."'";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			if(!ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$cases_expected_clr = "N/A";
				$plt_expected_clr = "N/A";
			} else {
				$cases_expected_clr = $second_row['THE_CASES'];
				$plt_expected_clr = $second_row['THE_PLTS'];
				$total_plt_expec_clr += $second_row['THE_PLTS'];
				$total_cases_expec_clr += $second_row['THE_CASES'];
			}

			// expected pallets (Chilean Sort Upload)
//					AND OMD.CONSIGNEE = CCM.CHILEAN_CONSIGNEE_NAME
			$sql = "SELECT NVL(SUM(CASES), 0) THE_SUM, COUNT(*) THE_COUNT 
					FROM ORIGINAL_MANIFEST_DETAILS_V2 OMD, ORIGINAL_MANIFEST_HEADER OMH, CHILEAN_CUSTOMER_MAP_V2 CCM
					WHERE OMD.TRANSACTION_ID = OMH.TRANSACTION_ID
					AND OMD.CONSIGNEE_CODE = CCM.CHILEAN_CONSIGNEE_CODE
					AND CCM.RECEIVER_ID = '".$first_row['RECEIVER_ID']."'
					AND OMH.TRANSACTION_ID NOT IN  ('17', '19')
					AND OMH.TRANSACTION_ID IN (SELECT TRANSACTION_ID FROM ORIGINAL_MANIFEST_HEADER WHERE LR_NUM = '".$vessel."' AND PUSHED_TO_CT = 'Y')";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			if(!ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$cases_expected = "N/A";
				$plt_expected = "N/A";
			} else {
				if($first_row['RECEIVER_ID'] != 3000){
					$cases_expected = $second_row['THE_SUM'];
					$plt_expected = $second_row['THE_COUNT'];
					$total_plt_expec += $second_row['THE_COUNT'];
					$total_cases_expec += $second_row['THE_SUM'];
				} else {
					// WALMART cannot be trusted in the carle file
					$cases_expected = 0;
					$plt_expected = 0;
				}
			}

			// pallets with damage
			$sql = "SELECT COUNT(*) THE_PLT, SUM(QTY_DAMAGED) THE_CASES FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND RECEIVER_ID = '".$first_row['RECEIVER_ID']."' AND QTY_DAMAGED > 0";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if($second_row['THE_PLT'] == 0){
				$plt_dmg = 0;
				$case_dmg = 0;
			} else {
				$plt_dmg = $second_row['THE_PLT'];
				$case_dmg = $second_row['THE_CASES'];

				$total_plt_dmg -= $plt_dmg;
				$total_case_dmg -= $case_dmg;
			}


			// shipped pallets
			$sql = "SELECT SUM(QTY_CHANGE) THE_CASES, COUNT(DISTINCT PALLET_ID) THE_PLT FROM CARGO_ACTIVITY WHERE SERVICE_CODE IN ('6', '11', '7', '13') AND ACTIVITY_NUM != '1' AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) AND ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$first_row['RECEIVER_ID']."' HAVING SUM(QTY_CHANGE) > 0";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			if(!ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$plt_ship = 0;
				$case_ship = 0;
			} else {
				$plt_ship = $second_row['THE_PLT'];
				$case_ship = $second_row['THE_CASES'];
				$total_plt_ship += $second_row['THE_PLT'];
				$total_cases_ship += $second_row['THE_CASES'];
			}

			if($bgcolor == "#EEEEEE"){
				$bgcolor = "#FFFFFF";
			} else {
				$bgcolor = "#EEEEEE";
			}


?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><b><? echo $first_row['RECEIVER_ID']; ?></b></font></td>
		<td <? if($first_row['RECEIVER_ID'] == 3000){?> bgcolor="#FF0000" <?}?>><font size="2" face="Verdana"><? echo $plt_expected; ?>&nbsp;</font></td> 
		<td <? if($first_row['RECEIVER_ID'] == 3000){?> bgcolor="#FF0000" <?}?>><font size="2" face="Verdana"><? echo $cases_expected; ?>&nbsp;</font></td> 
		<td><font size="2" face="Verdana"><? echo (0 + $plt_expected_clr); ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $cases_expected_clr); ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $plt_rec); ?></font>
								<? if($plt_rec_autotrans > 0 || $plt_sent_autotrans > 0){ ?>
									<font size="2" face="Verdana"> -- Autotrans:&nbsp;</font>				
									<? } ?>
								<? if($plt_rec_autotrans > 0) { ?>
									<font size="2" face="Verdana"><? echo $plt_rec_autotrans; ?> in&nbsp;&nbsp;</font>
									<? } ?>
								<? if($plt_sent_autotrans > 0) { ?>
									<font size="2" face="Verdana" color="FF0000"><? echo $plt_sent_autotrans; ?> out</font>
									<? } ?></td>
		<td><font size="2" face="Verdana"><? echo (0 + $case_rec); ?></font>
								<? if($case_rec_autotrans > 0 || $case_sent_autotrans > 0){ ?>
									<font size="2" face="Verdana"> -- Autotrans:&nbsp;</font>				
									<? } ?>
								<? if($case_rec_autotrans > 0) { ?>
									<font size="2" face="Verdana"><? echo $case_rec_autotrans; ?> in&nbsp;&nbsp;</font>
									<? } ?>
								<? if($case_sent_autotrans > 0) { ?>
									<font size="2" face="Verdana" color="FF0000"><? echo $case_sent_autotrans; ?> out</font>
									<? } ?></td>
		<td><font size="2" face="Verdana"><? echo (0 + $plt_ship); ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $case_ship); ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $plt_dmg); ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $case_dmg); ?></font></td>
	</tr>
<?
		}
?>
	<tr>
<!--		<td colspan="9">&nbsp;</td> !-->
		<td colspan="11">&nbsp;</td>
	</tr>
	<tr bgcolor="#DDFFDD">
		<td><font size="2" face="Verdana"><b>Totals: </b></font></td>
		<td><font size="2" face="Verdana"><? echo $total_plt_expec; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_cases_expec; ?></font></td> 
		<td><font size="2" face="Verdana"><? echo $total_plt_expec_clr; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_cases_expec_clr; ?></font></td> 
		<td><font size="2" face="Verdana"><? echo $total_plt_rec; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_cases_rec; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_plt_ship; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $total_cases_ship; ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $total_plt_dmg); ?></font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $total_case_dmg); ?></font></td>
	</tr>
</table>
<?
	} elseif($vessel != "" && $duplicate != ""){
		$bgcolor = "#EEEEEE";
		/*************************************************************************************
		*	This is for a duplicate barcode detail report
		*
		**************************************************************************************/
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<?

		$sql = "SELECT CUSP.CUSTOMER_NAME, CT.COMMODITY_CODE || '-' || COMP.COMMODITY_NAME THE_COMM, CT.PALLET_ID, VARIETY, REMARK, CARGO_SIZE, QTY_RECEIVED, HATCH, SOURCE_NOTE, SOURCE_USER
				FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUSP, COMMODITY_PROFILE COMP, (SELECT PALLET_ID, COUNT(*)
																						FROM CARGO_TRACKING
																						WHERE ARRIVAL_NUM = '".$vessel."'
																						AND RECEIVER_ID != '9722'";
																						if($eport_customer_id != 0){
																							if($eport_customer_id == "9999"){
																								$sql .= "AND RECEIVER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE USERNAME = '".$user."') ";
																							} else {
																								$sql .= "AND RECEIVER_ID = '".$eport_customer_id."' ";
																							}
																						}
																						$sql .= "
																						GROUP BY PALLET_ID
																						HAVING COUNT(*) >= 2) THE_DUPS
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID
					AND CT.PALLET_ID = THE_DUPS.PALLET_ID
				ORDER BY PALLET_ID, CUSTOMER_NAME";
//		echo $sql."<br>";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		if(!ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Hatch</b></font></td>
		<td><font size="2" face="Verdana"><b>Uploaded Source</b></font></td>
		<td><font size="2" face="Verdana"><b>Uploaded By</b></font></td>
	</tr>
	<tr>
		<td colspan="10" align="center"><font size="2" face="Verdana"><b>No Duplicate pallets for selected vessel.</b></font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td colspan="10" align="center"><font size="2" face="Verdana"><b>Duplicate Barcode List</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><b>Label</b></font></td>
		<td><font size="2" face="Verdana"><b>Size</b></font></td>
		<td><font size="2" face="Verdana"><b>Cases</b></font></td>
		<td><font size="2" face="Verdana"><b>Hatch</b></font></td>
		<td><font size="2" face="Verdana"><b>Uploaded Source</b></font></td>
		<td><font size="2" face="Verdana"><b>Uploaded By</b></font></td>
	</tr>
<?
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $first_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['CUSTOMER_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['COMMODITY_CODE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['VARIETY']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['REMARK']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['CARGO_SIZE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['QTY_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['HATCH']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['SOURCE_NOTE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['SOURCE_USER']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
?>
