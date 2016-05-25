<?
/*
*
*	Walmart.  "instant" in house amounts
*	this page is in a GENERAL location, with no security
*****************************************************************/

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$itemnum = $HTTP_POST_VARS['itemnum'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Inventory as of US-EST <? echo date('m/d/Y h:i:s a'); ?>
</font><br><font size="3" face="Verdana" color="#0066CC">(To update the information, reload the screen)</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="WM_itemrecap_index.php" method="post">
	<tr>
		<td><font size="2" face="Verdana">Item#:&nbsp;&nbsp;&nbsp;</font><input type="text" name="itemnum" size="10" maxlength="20" value="<? echo $itemnum; ?>">
				<font size="1" face="Verdana">(leave blank for ALL)</font></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6" align="left"><font size="3" face="Verdana"><b>Pallets Available And Order Information</b></font></td>
	</tr>
<!--		<td><font size="2" face="Verdana"><b>Order #s</b></font></td>
		<td><font size="2" face="Verdana"><b>Expected Load Dates</b></font></td>
		<td><font size="2" face="Verdana"><b>Plt on Pending Order</b></font></td>
		<td><font size="2" face="Verdana"><b>Ctn on Pending Order</b></font></td>!-->
	<tr>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Desc</b></font></td>
<!--	<td><font size="2" face="Verdana"><b>Vessel</b></font></td> !-->
		<td><font size="2" face="Verdana"><b>Plt in house</b></font></td>
		<td><font size="2" face="Verdana"><b>Ctn in house</b></font></td>
		<td><font size="2" face="Verdana"><b>Orders</b></font></td>
		<td><font size="2" face="Verdana"><b>Remaining # of plt</b></font></td>
	</tr>
<?
	if($itemnum == ""){
		$item_where = "";
	} else {
		$item_where = " AND BOL = '".$itemnum."'";
	}
/*
	$sql = "SELECT DISTINCT BOL 
			FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
			WHERE CT.RECEIVER_ID = '3000'
				AND CT.QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM IN 
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND WCT.WM_PROGRAM = 'BASE'
				AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
				".$item_where."
			ORDER BY BOL";
*/
	$sql = "SELECT DISTINCT BOL 
			FROM CARGO_TRACKING
			WHERE RECEIVER_ID = '3000'
				AND DATE_RECEIVED IS NOT NULL
				AND QTY_IN_HOUSE > 0
				AND ARRIVAL_NUM IN 
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
				".$item_where."
			ORDER BY BOL";
	$items = ociparse($rfconn, $sql);
	ociexecute($items);
	if(!ocifetch($items)){
?>
	<tr>
		<td colspan="10" align="center"><font size="2" face="Verdana">No In-House Data to Report</font></td>
	</tr>
<?
	} else {
		do {
			if($bgflip == true){
				$bgcolor="#FFFFFF";
				$bgflip = false;
			} else {
				$bgcolor="#FFFFCC";
				$bgflip = true;
			}

?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($items, "BOL"); ?></font></td>
<?
			$sql = "SELECT WM_COMMODITY_NAME
					FROM WM_ITEM_COMM_MAP
					WHERE ITEM_NUM = '".ociresult($items, "BOL")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$desc = ociresult($short_term_data, "WM_COMMODITY_NAME");
?>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "WM_COMMODITY_NAME"); ?></font></td>
<?
			// ARRIVAL_NUM
			//					GROUP BY ARRIVAL_NUM
			//ORDER BY ARRIVAL_NUM		
/*
			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS 
					FROM CARGO_TRACKING CT, WM_CARGO_TYPE WCT
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND CT.RECEIVER_ID = '3000'
						AND CT.QTY_IN_HOUSE > 0
						AND CT.DATE_RECEIVED IS NOT NULL 
						AND CT.ARRIVAL_NUM IN 
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
					HAVING COUNT(DISTINCT PALLET_ID) > 0";
*/
			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS 
					FROM CARGO_TRACKING
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND RECEIVER_ID = '3000'
						AND QTY_IN_HOUSE > 0
						AND DATE_RECEIVED IS NOT NULL 
						AND ARRIVAL_NUM IN 
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND (CARGO_STATUS IS NULL OR CARGO_STATUS != 'HOLD')
					HAVING COUNT(DISTINCT PALLET_ID) > 0";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_ih = ociresult($short_term_data, "THE_PLTS");
			$ctn_ih = ociresult($short_term_data, "THE_CTNS");
?>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_PLTS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_CTNS"); ?></font></td>
<?
//			$arv = ociresult($short_term_data, "ARRIVAL_NUM");
/*
			$sql = "SELECT VESSEL_NAME
					FROM VESSEL_PROFILE
					WHERE LR_NUM = '".$arv."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			if(!ocifetch($short_term_data)){
				$vesname = $arv." - TRUCKED IN";
			} else {
				$vesname = $arv." - ".ociresult($short_term_data, "VESSEL_NAME");
			}
*/
			$sql = "SELECT NVL(SUM(PALLETS), 0) THE_PLTS, NVL(SUM(CASES), 0) THE_CTNS, TO_CHAR(LOAD_DATE, 'MM/DD/YYYY') THE_LOAD, WLH.LOAD_NUM, WLD.DCPO_NUM
					FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
					WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
						AND WLD.DCPO_NUM = WLDI.DCPO_NUM
						AND WLH.LOAD_DATE >= TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY')
						AND WLH.STATUS = 'ACTIVE'
						AND WLDI.ITEM_NUM = '".ociresult($items, "BOL")."'
					GROUP BY TO_CHAR(LOAD_DATE, 'MM/DD/YYYY'), WLH.LOAD_NUM, WLD.DCPO_NUM
					ORDER BY TO_CHAR(LOAD_DATE, 'MM/DD/YYYY'), WLH.LOAD_NUM, WLD.DCPO_NUM";
			$orders = ociparse($rfconn, $sql);
			ociexecute($orders);
			// ocifetch($orders); <—— Gotcha, you nasty little bug! Stop eating the first loop iteration!
			if(!ocifetch($orders)){
?>
		<td align="center"><font size="2" face="Verdana">None</font></td>
<?
			} else {
				$total_plt_needed = 0;
				$total_ctn_needed = 0;
?>
		<td><table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<td><font size="2" face="Verdana"><b>Load Date</b></font></td>
				<td><font size="2" face="Verdana"><b>Load#</b></font></td>
				<td><font size="2" face="Verdana"><b>Order#</b></font></td>
				<td><font size="2" face="Verdana"><b>Original Plt</b></font></td>
				<td><font size="2" face="Verdana"><b>Original Ctn</b></font></td>
				<td><font size="2" face="Verdana"><b>Scanned Plt</b></font></td>
				<td><font size="2" face="Verdana"><b>Scanned Ctn</b></font></td>
				<td><font size="2" face="Verdana"><b>Needed Plt</b></font></td>
				<td><font size="2" face="Verdana"><b>Needed Ctn</b></font></td>
			</tr>
<?
				do {
					$plt_original = ociresult($orders, "THE_PLTS");
					$ctn_original = ociresult($orders, "THE_CTNS");
					$load = ociresult($orders, "LOAD_NUM");
					$order = ociresult($orders, "DCPO_NUM");
					$load_date = ociresult($orders, "THE_LOAD");

					$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, SUM(QTY_CHANGE) THE_SUM
							FROM CARGO_ACTIVITY
							WHERE SERVICE_CODE = '6'
								AND ACTIVITY_DESCRIPTION IS NULL
								AND CUSTOMER_ID = '3000'
								AND PALLET_ID IN
									(SELECT PALLET_ID FROM CARGO_TRACKING
									WHERE RECEIVER_ID = '3000'
									AND BOL = '".ociresult($items, "BOL")."')
								AND ORDER_NUM = '".$order."'";
//					echo $sql."<br>";
					$short_term_data = ociparse($rfconn, $sql);
					ociexecute($short_term_data);
					ocifetch($short_term_data);

					$plt_scanned = ociresult($short_term_data, "THE_COUNT");
					$ctn_scanned = ociresult($short_term_data, "THE_SUM");

					$plt_needed = $plt_original - $plt_scanned;
					$ctn_needed = $ctn_original - $ctn_scanned;

					$total_plt_needed += $plt_needed;
					$total_ctn_needed += $ctn_needed;
?>
			<tr>
				<td><font size="2" face="Verdana"><? echo $load_date; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $load; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $order; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $plt_original; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $ctn_original; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $plt_scanned; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $ctn_scanned; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $plt_needed; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $ctn_needed; ?></font></td>
			</tr>
<?
				} while(ocifetch($orders));
?>
		</table></td>
<?
			}
?>
		<td><font size="2" face="Verdana"><? echo ($plt_ih - $total_plt_needed); ?></font></td>
	</tr>
<?

		} while(ocifetch($items));
	}
?>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr><br><br><br><br><br></td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6" align="left"><font size="3" face="Verdana"><b>Pending Vessel Release</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Desc</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Plts</b></font></td>
		<td><font size="2" face="Verdana"><b>Total ctns</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Vessel's First Pallet Received (if Any)</b></font></td>
	</tr>
<?
//				AND CT.DATE_RECEIVED IS NOT NULL 
	$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, ARRIVAL_NUM, BOL
			FROM CARGO_TRACKING
			WHERE RECEIVER_ID = '3000'
				AND QTY_IN_HOUSE > 0
				AND ARRIVAL_NUM NOT IN 
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				".$item_where."
			GROUP BY BOL, ARRIVAL_NUM
			ORDER BY BOL, ARRIVAL_NUM";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	while(ocifetch($main_data)){
		if($bgflip == true){
			$bgcolor="#FFFFFF";
			$bgflip = false;
		} else {
			$bgcolor="#FFFFCC";
			$bgflip = true;
		}

		$plt_notrel = ociresult($main_data, "THE_PLTS");
		$ctn_notrel = ociresult($main_data, "THE_CTNS");
		$arv = ociresult($main_data, "ARRIVAL_NUM");
		$bol = ociresult($main_data, "BOL");

		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE LR_NUM = '".$arv."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$vesname = $arv." - TRUCKED IN";
		} else {
			$vesname = $arv." - ".ociresult($short_term_data, "VESSEL_NAME");
		}

		$sql = "SELECT WM_COMMODITY_NAME
				FROM WM_ITEM_COMM_MAP
				WHERE ITEM_NUM = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$desc = ociresult($short_term_data, "WM_COMMODITY_NAME");

		$sql = "SELECT TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') THE_DATE
				FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '3000'
					AND ARRIVAL_NUM = '".$arv."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$first_scan = ociresult($main_data, "THE_DATE");

?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $desc; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $plt_notrel; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_notrel; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_scan; ?></font></td>
	</tr>
<?
	}
?>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr><br><br></td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6" align="left"><font size="3" face="Verdana"><b>Pallets With Status = HOLD</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Desc</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Plts</b></font></td>
		<td><font size="2" face="Verdana"><b>Total ctns</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Vessel's First Pallet Received (if Any)</b></font></td>
	</tr>
<?
//				AND CT.DATE_RECEIVED IS NULL 
	$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, ARRIVAL_NUM, BOL
			FROM CARGO_TRACKING
			WHERE RECEIVER_ID = '3000'
				AND QTY_IN_HOUSE > 0
				AND CARGO_STATUS = 'HOLD'
				".$item_where."
			GROUP BY BOL, ARRIVAL_NUM
			ORDER BY BOL, ARRIVAL_NUM";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	while(ocifetch($main_data)){
		if($bgflip == true){
			$bgcolor="#FFFFFF";
			$bgflip = false;
		} else {
			$bgcolor="#FFFFCC";
			$bgflip = true;
		}

		$plt_hold = ociresult($main_data, "THE_PLTS");
		$ctn_hold = ociresult($main_data, "THE_CTNS");
		$arv = ociresult($main_data, "ARRIVAL_NUM");
		$bol = ociresult($main_data, "BOL");

		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE LR_NUM = '".$arv."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$vesname = $arv." - TRUCKED IN";
		} else {
			$vesname = $arv." - ".ociresult($short_term_data, "VESSEL_NAME");
		}

		$sql = "SELECT WM_COMMODITY_NAME
				FROM WM_ITEM_COMM_MAP
				WHERE ITEM_NUM = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$desc = ociresult($short_term_data, "WM_COMMODITY_NAME");

		$sql = "SELECT TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') THE_DATE
				FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '3000'
					AND ARRIVAL_NUM = '".$arv."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$first_scan = ociresult($main_data, "THE_DATE");
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $desc; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $plt_hold; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_hold; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_scan; ?></font></td>
	</tr>
<?
	}
?>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr><br><br></td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="6" align="left"><font size="3" face="Verdana"><b>Pallets Not Yet Scanned In</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Item#</b></font></td>
		<td><font size="2" face="Verdana"><b>Desc</b></font></td>
		<td><font size="2" face="Verdana"><b>Total Plts</b></font></td>
		<td><font size="2" face="Verdana"><b>Total ctns</b></font></td>
		<td><font size="2" face="Verdana"><b>Date of Vessel's First Pallet Received (if Any)</b></font></td>
	</tr>
<?
	$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_PLTS, SUM(QTY_RECEIVED) THE_CTNS, ARRIVAL_NUM, BOL
			FROM CARGO_TRACKING
			WHERE RECEIVER_ID = '3000'
				AND QTY_IN_HOUSE > 0
				AND DATE_RECEIVED IS NULL 
				".$item_where."
			GROUP BY BOL, ARRIVAL_NUM
			ORDER BY BOL, ARRIVAL_NUM";
	$main_data = ociparse($rfconn, $sql);
	ociexecute($main_data);
	while(ocifetch($main_data)){
		if($bgflip == true){
			$bgcolor="#FFFFFF";
			$bgflip = false;
		} else {
			$bgcolor="#FFFFCC";
			$bgflip = true;
		}

		$plt_notscanned = ociresult($main_data, "THE_PLTS");
		$ctn_notscanned = ociresult($main_data, "THE_CTNS");
		$arv = ociresult($main_data, "ARRIVAL_NUM");
		$bol = ociresult($main_data, "BOL");

		$sql = "SELECT VESSEL_NAME
				FROM VESSEL_PROFILE
				WHERE LR_NUM = '".$arv."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$vesname = $arv." - TRUCKED IN";
		} else {
			$vesname = $arv." - ".ociresult($short_term_data, "VESSEL_NAME");
		}

		$sql = "SELECT WM_COMMODITY_NAME
				FROM WM_ITEM_COMM_MAP
				WHERE ITEM_NUM = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$desc = ociresult($short_term_data, "WM_COMMODITY_NAME");

		$sql = "SELECT TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY HH24:MI:SS') THE_DATE
				FROM CARGO_TRACKING
				WHERE RECEIVER_ID = '3000'
					AND ARRIVAL_NUM = '".$arv."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$first_scan = ociresult($main_data, "THE_DATE");
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $desc; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $plt_notscanned; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_notscanned; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_scan; ?></font></td>
	</tr>
<?
	}
?>
</table>
