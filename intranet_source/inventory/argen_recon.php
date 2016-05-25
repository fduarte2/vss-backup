<?
/*
*	Adam Walter, July 10, 2008.
*
*	This page is a one-stop shop for argen Juice.
*	Displays pallets, BoL, Mark (all),
*	Date received (only for those received),
*	And date and quantity shipped (only for those with activity records)
*************************************************************************/

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
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$bniconn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$bniconn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($bniconn));
		exit;
	}

	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$Short_Term_Cursor_BNI = ora_open($bniconn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$show = $HTTP_POST_VARS['show'];
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Argen Juice Reconciliation Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="argen_recon.php" method="post">
	<tr>
		<td align="left"><font size="2" face="Verdana">Vessel:  <select name="vessel">
						<option value="">Please Select a Vessel</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'ARG JUICE' ORDER BY LR_NUM DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL'] ?></option>
<?
		}
?>
					</select></font></td>
		<td align="center"><font size="2" face="Verdana">Show Records:  <select name="show">
														<option value="all">All</option>
														<option value="act"<? if($show == "act"){ ?> selected <? } ?>>With Outbound Activity</option>
														<option value="noact"<? if($show == "noact"){ ?> selected <? } ?>>No Outbound Activity</option>
														<option value="NR"<? if($show == "NR"){ ?> selected <? } ?>>Not Received</option>
								</select></font></td>
		<td align="right"><font size="2" face="Verdana">Customer:  <select name="cust">
														<option value="ALL">ALL</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_STATUS = 'ACTIVE' ORDER BY CUSTOMER_ID ASC";
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
		<td colspan="3" align="center"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	if($vessel != "" && $cust != ""){
		$total_pallets = 0;
		$total_pallets_received = 0;
		$total_cases_received = 0;
//		$total_pallets_acted_on = 0;
		$total_pallets_trans_out = 0;
		$total_pallets_delivered = 0;
		$total_cases_acted_on = 0;		// note: this one will fluctuate +/- as returns happen
		$current_cust = "none";

		$sub_total_pallets = 0;
		$sub_pallets_received = 0;
//		$sub_pallets_acted_on = 0;
		$sub_pallets_trans_out = 0;
		$sub_pallets_delivered = 0;

		$lines_shown = false;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr bgcolor="#CC3333">
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>Mark</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY Rec</b></font></td>
		<td><font size="2" face="Verdana"><b>Activity Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY of Activity</b></font></td>
		<td><font size="2" face="Verdana"><b>Activity Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Activity</b></font></td>
		<td><font size="2" face="Verdana"><b>Activity Order #</b></font></td>
	</tr>
<?
		// top level SQL, gets all pallets associated with this ship/customer.
		$sql = "SELECT PALLET_ID, CUSTOMER_ID, BOL, CARGO_DESCRIPTION MARK, COMMODITY_NAME, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI:SS'), 'NR') THE_REC, 
					QTY_RECEIVED, CUSTOMER_NAME, DECODE(QTY_UNIT, 'B', 'BIN', 'BI', 'BIN', 'D', 'DRUM', 'DR', 'DRUM', 'P', 'PLT', 'UKW') THE_UNIT
				FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, CUSTOMER_PROFILE CUSTP WHERE ARRIVAL_NUM = '".$vessel."'";
		if($cust != "ALL"){
			$sql .= " AND RECEIVER_ID = '".$cust."'";
		}
		if($show == "NR"){
			$sql .= " AND DATE_RECEIVED IS NULL";
		}
		$sql .= "	AND CT.COMMODITY_CODE = CP.COMMODITY_CODE 
					AND CT.RECEIVER_ID = CUSTP.CUSTOMER_ID 
					AND CT.COMMODITY_CODE LIKE '503%' 
				ORDER BY CUSTOMER_ID, BOL, MARK, PALLET_ID";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

			// now, decide what to do with each pallet.
			// if a new customer, make a breakline...

			if($first_row['BOL'] != $current_bol && $lines_shown == true){
//				echo "firstbol: ".$first_row['BOL']."  curbol: ".$current_bol."  linesshown: ".$lines_shown."<br>";
/*				if($show == "NR"){
					$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE LIKE '503%' AND BOL = '".$current_bol."'";
					ora_parse($Short_Term_Cursor, $sql);
					ora_exec($Short_Term_Cursor);
					ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
					$expected_line = " (Expt'd Plts in RF: ".$Short_Term_row['THE_COUNT'].")";
				} else {*/
					$expected_line = "";
//				}
?>
<tr>
	<td bgcolor="#AD5CFF"><font size="2" face="Verdana"><b>BOL <? echo $current_bol; ?> totals:</b></font></td>
	<td colspan="2"><font size="2" face="Verdana">Plt: <? echo $sub_total_pallets.$expected_line; ?></font></td>
	<td colspan="2"><font size="2" face="Verdana">Plt Rcvd: <? echo $sub_pallets_received; ?></font></td>
<!--	<td colspan="5"><font size="2" face="Verdana">Pallet Activity: <? echo $sub_pallets_acted_on; ?></font></td> !-->
	<td colspan="5"><font size="2" face="Verdana">Pallet Activity:<br><? echo $sub_pallets_delivered." Delivered<br>".$sub_pallets_trans_out." Transferred Out"; ?></font></td> 
</tr>
<?
				$sub_total_pallets = 0;
				$sub_pallets_received = 0;
//				$sub_pallets_acted_on = 0;				
				$sub_pallets_trans_out = 0;
				$sub_pallets_delivered = 0;
				$current_bol = $first_row['BOL'];
				$lines_shown = false;
			}
			if($first_row['CUSTOMER_ID'] != $current_cust){
?>
	<tr bgcolor="#3380CC">
		<td colspan="10"><font size="2" face="Verdana"><b><? echo $first_row['CUSTOMER_NAME']; ?></b></font></td>
	</tr>
<?
				$current_cust = $first_row['CUSTOMER_ID'];
			}

			// if no activity, we show the row with no data under last 4 columns (activity being a non-voided outbound or return)
			// if >= 1 activity, we show rows going across
			$sql = "SELECT COUNT(*) THE_COUNT 
					FROM CARGO_ACTIVITY 
					WHERE ARRIVAL_NUM = '".$vessel."' 
						AND PALLET_ID = '".$first_row['PALLET_ID']."'
						AND CUSTOMER_ID = '".$first_row['CUSTOMER_ID']."'
						AND SERVICE_CODE NOT IN ('1', '2', '8', '9', '12', '16') 
						AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL)";
			ora_parse($cursor_second, $sql);
			ora_exec($cursor_second);
			ora_fetch_into($cursor_second, $second_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

/*			$sql = "SELECT SUM(QTY_CHANGE) THE_SUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '".$first_row['PALLET_ID']."' AND ARRIVAL_NUM = '".$vessel."' AND CUSTOMER_ID = '".$first_row['CUSTOMER_ID']."' AND SERVICE_CODE = '9'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$recoup = $Short_Term_row['THE_SUM']; */
/*
			$total_pallets++;
			$sub_total_pallets++;
			if($first_row['THE_REC'] != "NR"){
				$total_pallets_received++;
				$sub_pallets_received++;
				$total_cases_received += $first_row['QTY_RECEIVED'];
			}
*/
			if($second_row['THE_COUNT'] == 0 && $show != "act"){
				$current_bol = $first_row['BOL'];
				$lines_shown = true;
				// if no activity, we show the row with no data under last 4 columns

				$total_pallets++;
				$sub_total_pallets++;
				if($first_row['THE_REC'] != "NR"){
					$total_pallets_received++;
					$sub_pallets_received++;
					$total_cases_received += $first_row['QTY_RECEIVED'];
				}

?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $first_row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['BOL']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['MARK']; ?></font></td>
		<td <? if ($first_row['THE_REC'] == "NR") {?> bgcolor="#33CC00" <?} else {?> bgcolor="#FFFFFF" <?}?>><font size="2" face="Verdana"><? echo $first_row['THE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['QTY_RECEIVED']." ".$first_row['THE_UNIT']; ?></font></td>
		<td colspan="5" align="center"><font size="2" face="Verdana">---NONE---</font></td>
	</tr>
<?
			} elseif($second_row['THE_COUNT'] != 0 && $show != "noact") {
				$current_bol = $first_row['BOL'];
				$lines_shown = true;
				// we show rows, with the count of the previous query being the RowCount of the pallet in the table

				$total_pallets++;
				$sub_total_pallets++;
				if($first_row['THE_REC'] != "NR"){
					$total_pallets_received++;
					$sub_pallets_received++;
					$total_cases_received += $first_row['QTY_RECEIVED'];
				}

?>
	<tr>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['PALLET_ID']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['BOL']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['MARK']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>" <? if ($first_row['THE_REC'] == "NR") {?> bgcolor="#33CC00" <?} else {?> bgcolor="#FFFFFF" <?}?>><font size="2" face="Verdana"><? echo $first_row['THE_REC']; ?></font></td>
		<td rowspan="<? echo $second_row['THE_COUNT']; ?>"><font size="2" face="Verdana"><? echo $first_row['QTY_RECEIVED']." ".$first_row['THE_UNIT']; ?></font></td>
<?
				// now we get the activities.  SAME WHERE CLAUSE as above (save for the join to SERVICE_CATEGORY); 
				// be sure of it, or this goes wonky.
				$sql = "SELECT ACTIVITY_NUM, QTY_CHANGE, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') THE_ACT, UPPER(SERVICE_NAME) THE_SERV, 
							NVL(ORDER_NUM, 'N/A') THE_ORD, CUSTOMER_ID, CA.SERVICE_CODE, ACTIVITY_DESCRIPTION
						FROM CARGO_ACTIVITY CA, SERVICE_CATEGORY SC 
						WHERE ARRIVAL_NUM = '".$vessel."' 
							AND PALLET_ID = '".$first_row['PALLET_ID']."' 
							AND CUSTOMER_ID = '".$first_row['CUSTOMER_ID']."'
							AND CA.SERVICE_CODE NOT IN ('1', '2', '8', '9', '12', '16') 
							AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) 
							AND CA.SERVICE_CODE = SC.SERVICE_CODE 
						ORDER BY ACTIVITY_NUM";
				ora_parse($cursor_third, $sql);
				ora_exec($cursor_third);
				ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

				// do this for the first record
//				$total_pallets_acted_on++;
//				$sub_pallets_acted_on++;
				if($third_row['SERVICE_CODE'] == "6" && $third_row['ACTIVITY_DESCRIPTION'] != "RETURN"){
					$total_pallets_delivered++;
					$sub_pallets_delivered++;
				} elseif($third_row['SERVICE_CODE'] == "11" && $third_row['ACTIVITY_NUM'] != "1"){
					$sub_pallets_trans_out++;
					$total_pallets_trans_out++;
				}
				$total_cases_acted_on += $third_row['QTY_CHANGE'];
?>
		<td <? if($third_row['CUSTOMER_ID'] != $first_row['CUSTOMER_ID']){ echo "bgcolor=\"#FFFF29\""; }?>><font size="2" face="Verdana"><? echo $third_row['CUSTOMER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['QTY_CHANGE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_SERV']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_ACT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_ORD']; ?></font></td>
<?
				while(ora_fetch_into($cursor_third, $third_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// and this if there is more than 1 activity, repeate this procedure
					if($third_row['SERVICE_CODE'] == "6" && $third_row['ACTIVITY_DESCRIPTION'] != "RETURN"){
						$total_pallets_delivered++;
						$sub_pallets_delivered++;
					} elseif($third_row['SERVICE_CODE'] == "11" && $third_row['ACTIVITY_NUM'] != "1"){
						$sub_pallets_trans_out++;
						$total_pallets_trans_out++;
					}
					$total_cases_acted_on += $third_row['QTY_CHANGE'];
?>
	</tr>
	<tr>
		<td <? if($third_row['CUSTOMER_ID'] != $first_row['CUSTOMER_ID']){ echo "bgcolor=\"#FFFF29\""; }?>><font size="2" face="Verdana"><? echo $third_row['CUSTOMER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['QTY_CHANGE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_SERV']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_ACT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $third_row['THE_ORD']; ?></font></td>
<?
				}
?>
	</tr>
<?
				// lastly, if the final activity was a RETURN, we remove this pallet from the counter at the bottom.
				// we are only interested in service codes of ship or return, hence the "code in" clause
/*				$sql = "SELECT MAX(DATE_OF_ACTIVITY), SERVICE_CODE 
						FROM CARGO_ACTIVITY 
						WHERE PALLET_ID = '".$first_row['PALLET_ID']."' 
							AND CUSTOMER_ID = '".$first_row['CUSTOMER_ID']."' 
							AND ARRIVAL_NUM = '".$vessel."' 
							AND SERVICE_CODE IN ('6', '7', '11', '13') 
							AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) 
						GROUP BY SERVICE_CODE 
						ORDER BY MAX(DATE_OF_ACTIVITY) DESC";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				if($row['SERVICE_CODE'] == "7" || $row['SERVICE_CODE'] == "13"){
					$total_pallets_acted_on--;
					$sub_pallets_acted_on--;
				} */
			}
		}
/*
		if($show == "NR"){
			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND COMMODITY_CODE LIKE '503%' AND BOL = '".$current_bol."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$expected_line = " (Expt'd Plts in RF: ".$Short_Term_row['THE_COUNT'].")";
		} else { */
			$expected_line = "";
//		} 
?>
<tr>
	<td bgcolor="#AD5CFF"><font size="2" face="Verdana"><b>BOL <? echo $current_bol; ?> totals:</b></font></td>
	<td colspan="2"><font size="2" face="Verdana">Plt: <? echo $sub_total_pallets.$expected_line; ?></font></td>
	<td colspan="2"><font size="2" face="Verdana">Plt Rcvd: <? echo $sub_pallets_received; ?></font></td>
<!--	<td colspan="5"><font size="2" face="Verdana">Pallet Activity: <? echo $sub_pallets_acted_on; ?></font></td> !-->
	<td colspan="5"><font size="2" face="Verdana">Pallet Activity:<br><? echo $sub_pallets_delivered." Delivered<br>".$sub_pallets_trans_out." Trans Out"; ?></font></td> 
</tr>
<tr>
	<td colspan="10" align="center"><font size="2" face="Verdana"><b>TOTALS:</b></font></td>
</tr>
<?
		$sql = "SELECT SUM(QTY_RECEIVED) THE_REC 
				FROM CARGO_TRACKING CT, CARGO_MANIFEST CM
				WHERE CT.LOT_NUM = CM.CONTAINER_NUM
					AND LR_NUM = '".$vessel."'
					AND ORIGINAL_CONTAINER_NUM IS NULL";
		ora_parse($Short_Term_Cursor_BNI, $sql);
		ora_exec($Short_Term_Cursor_BNI);
		ora_fetch_into($Short_Term_Cursor_BNI, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$expec = $row['THE_REC'];
?>
<tr>
	<td colspan="2"><font size="2" face="Verdana">Plt: <? echo $total_pallets; ?></font></td>
	<td><font size="2" face="Verdana">Original Plt Count:<br><? echo $expec; ?></font></td>
	<td><font size="2" face="Verdana">Plt Rcvd: <? echo $total_pallets_received; ?></font></td>
	<td><font size="2" face="Verdana">Unit2 Rcvd: <? echo $total_cases_received; ?></font></td>
	<td colspan="2"><font size="2" face="Verdana">Unit2 Activity: <? echo $total_cases_acted_on; ?></font></td>
<!--	<td colspan="3"><font size="2" face="Verdana">Pallet Activity: <? echo $total_pallets_acted_on; ?></font></td> !-->
	<td colspan="3"><font size="2" face="Verdana">Pallet Activity:<br><? echo $total_pallets_delivered." Delivered<br>".$total_pallets_trans_out." Transferred Out"; ?></font></td> 
</tr>
<?
	}
	include("pow_footer.php");
?>