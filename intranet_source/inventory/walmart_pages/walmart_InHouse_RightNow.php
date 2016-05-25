<?
/*
*
*	Walmart.  "instant" in house amounts
*
*****************************************************************/

// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "Inventory System - Walmart";
$area_type = "INVE";

// Provides header / leftnav
include("pow_header.php");
	if($access_denied){
	printf("Access Denied from INVE system");
	include("pow_footer.php");
	exit;
}

$url_bpo_page = 'WM_PO_InOut.php';

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\">Currently using the RF.TEST database!</font><br/>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Available Inventory as of US-EST <? echo date('m/d/Y h:i:s a'); ?></font><font size="3" face="Verdana">   <a href="index_WM.php">Return to Main Walmart Page</a>
</font><br><font size="3" face="Verdana" color="#0066CC">(To update the information, reload the screen)</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<p>
<font size="2" face="Verdana">Note:<br>1. If an order is being scanned onto a truck at this moment, the pallets already scanned onto that order will neither be counted in (A) nor be counted in (P) since that quantity of the order has been fulfilled.</font><br>
<font size="2" face="Verdana">2. The pallets on hold (columns H and V) are not counted towards any of the prior columns, but presented as FYI in case you are looking for some more pallets.</font>
<p><p>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2">&nbsp;</td>
		<td colspan="2" align="center"><font size="2" face="Verdana">In House (A1) >&nbsp;10 cases</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana">In House (A2) ≤&nbsp;10 cases</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana">Not Scanned into POW (B)</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana">Total Cargo Available(T)</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana">Pending Orders (P)</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Net Available (N)</b></font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana" color="#FF0000">FYI: QC Holds (H)</font></td>
		<td colspan="2" align="center"><font size="2" face="Verdana" color="#FF0000">FYI: Vessel Holds (V)</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Item #</font></td>
		<td><font size="2" face="Verdana">Description</font></td>
		<td><font size="2" face="Verdana">Pallets</font></td>
		<td><font size="2" face="Verdana">Cartons</font></td>
		<td><font size="2" face="Verdana">Pallets</font></td>
		<td><font size="2" face="Verdana">Cartons</font></td>
		<td><font size="2" face="Verdana">Pallets</font></td>
		<td><font size="2" face="Verdana">Cartons</font></td>
		<td><font size="2" face="Verdana">Pallets</font></td>
		<td><font size="2" face="Verdana">Cartons</font></td>
		<td><font size="2" face="Verdana">Pallets</font></td>
		<td><font size="2" face="Verdana">Cartons</font></td>
		<td><font size="2" face="Verdana"><b>Pallets</b></font></td>
		<td><font size="2" face="Verdana"><b>Cartons</b></font></td>
		<td><font size="2" face="Verdana" color="#FF0000">Pallets</font></td>
		<td><font size="2" face="Verdana" color="#FF0000">Cartons</font></td>
		<td><font size="2" face="Verdana" color="#FF0000">Pallets</font></td>
		<td><font size="2" face="Verdana" color="#FF0000">Cartons</font></td>
	</tr>
<?
	$sql = "SELECT DISTINCT CT.BOL, WCM.WM_COMMODITY_NAME
			FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT, WM_ITEM_COMM_MAP WCM
			WHERE CT.RECEIVER_ID = '3000'
				AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
				AND CT.PALLET_ID = CTAD.PALLET_ID
				AND CT.QTY_IN_HOUSE > 0
				AND CT.ARRIVAL_NUM IN 
					(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
				AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
				AND CT.BOL = WCM.ITEM_NUM
				AND WCT.WM_PROGRAM = 'BASE'
				AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
				AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
			ORDER BY BOL";
	$items = ociparse($rfconn, $sql);
	ociexecute($items);
	if(!ocifetch($items)){
?>
	<tr>
		<td colspan="18" align="center"><font size="2" face="Verdana">No In-House Data to Report</font></td>
	</tr>
<?
	} else {
		do {
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND CT.RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.QTY_IN_HOUSE > 10
						AND CT.DATE_RECEIVED IS NOT NULL 
						AND CT.ARRIVAL_NUM IN 
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
						AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
					HAVING COUNT(DISTINCT CT.PALLET_ID) > 0";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_ih1 = ociresult($short_term_data, "THE_PLTS");
			$ctn_ih1 = ociresult($short_term_data, "THE_CTNS");
			
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND CT.RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.QTY_IN_HOUSE between 1 and 10
						AND CT.DATE_RECEIVED IS NOT NULL 
						AND CT.ARRIVAL_NUM IN 
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
						AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
					HAVING COUNT(DISTINCT CT.PALLET_ID) > 0";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_ih2 = ociresult($short_term_data, "THE_PLTS");
			$ctn_ih2 = ociresult($short_term_data, "THE_CTNS");
			
			if($plt_ih2 > 0){
				$plt_ih2_disp = "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">".$plt_ih2."</font>";
				$ctn_ih2_disp = "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">".$ctn_ih2."</font>";
			} else {
				$plt_ih2_disp = "&nbsp;";
				$ctn_ih2_disp = "&nbsp;";
			}
			
			
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND CT.RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.QTY_IN_HOUSE > 0
						AND CT.DATE_RECEIVED IS NULL 
						AND CT.ARRIVAL_NUM IN 
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
						AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
					HAVING COUNT(DISTINCT CT.PALLET_ID) > 0";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_ih_unscanned = ociresult($short_term_data, "THE_PLTS");
			$ctn_ih_unscanned = ociresult($short_term_data, "THE_CTNS");
			
			$plt_ih_total = $plt_ih1 +$plt_ih2  + $plt_ih_unscanned;
			$ctn_ih_total = $ctn_ih1 + $ctn_ih2 + $ctn_ih_unscanned;
			
			
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND CT.RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.QTY_IN_HOUSE > 0
						AND CT.ARRIVAL_NUM NOT IN 
							(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS IS NULL OR CT.CARGO_STATUS != 'HOLD')
						AND (USDA_HOLD IS NULL OR USDA_HOLD != 'Y')
					HAVING COUNT(DISTINCT CT.PALLET_ID) > 0";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_not_released = ociresult($short_term_data, "THE_PLTS");
			$ctn_not_released = ociresult($short_term_data, "THE_CTNS");
			
			if($plt_not_released > 0){
				$plt_not_released_disp = "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">".$plt_not_released."</font>";
				$ctn_not_released_disp = "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">".$ctn_not_released."</font>";
			} else {
				$plt_not_released_disp = "&nbsp;";
				$ctn_not_released_disp = "&nbsp;";
			}

			
			$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_PLTS, SUM(QTY_IN_HOUSE) THE_CTNS
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, WM_CARGO_TYPE WCT
					WHERE BOL = '".ociresult($items, "BOL")."'
						AND CT.RECEIVER_ID = '3000'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.QTY_IN_HOUSE > 0
						AND (CT.DATE_RECEIVED IS NOT NULL 
								OR CT.ARRIVAL_NUM IN 
								(SELECT TO_CHAR(LR_NUM) FROM WDI_VESSEL_RELEASE)
							)
						AND CT.CARGO_TYPE_ID = WCT.CARGO_TYPE_ID
						AND WCT.WM_PROGRAM = 'BASE'
						AND (CT.CARGO_STATUS = 'HOLD' OR USDA_HOLD = 'Y')";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_hold = ociresult($short_term_data, "THE_PLTS");
			$ctn_hold = ociresult($short_term_data, "THE_CTNS");

			if($plt_hold > 0){
				$plt_hold_disp = "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">".$plt_hold."</font>";
				$ctn_hold_disp = "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\">".$ctn_hold."</font>";
			} else {
				$plt_hold_disp = "&nbsp;";
				$ctn_hold_disp = "&nbsp;";
			}


			$sql = "SELECT NVL(SUM(PALLETS), 0) THE_PLTS, NVL(SUM(CASES), 0) THE_CTNS
						FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
						WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
						AND WLD.DCPO_NUM = WLDI.DCPO_NUM
						AND WLH.STATUS = 'ACTIVE'
						AND WLH.LOAD_DATE >= TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY')
						AND WLDI.ITEM_NUM = '".ociresult($items, "BOL")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$plt_needed = ociresult($short_term_data, "THE_PLTS");
			$ctn_needed = ociresult($short_term_data, "THE_CTNS");

			$sql = "SELECT COUNT(DISTINCT PALLET_ID) THE_COUNT, SUM(QTY_CHANGE) THE_SUM
					FROM CARGO_ACTIVITY
					WHERE SERVICE_CODE = '6'
					AND ACTIVITY_DESCRIPTION IS NULL
					AND CUSTOMER_ID = '3000'
					AND PALLET_ID IN
						(SELECT PALLET_ID FROM CARGO_TRACKING
						WHERE RECEIVER_ID = '3000'
						AND BOL = '".ociresult($items, "BOL")."')
					AND ORDER_NUM IN
						(SELECT TO_CHAR(WLD.DCPO_NUM)
						FROM WDI_LOAD_DCPO_ITEMNUMBERS WLDI, WDI_LOAD_HEADER WLH, WDI_LOAD_DCPO WLD
						WHERE WLH.LOAD_NUM = WLD.LOAD_NUM
						AND WLD.DCPO_NUM = WLDI.DCPO_NUM
						AND WLH.STATUS = 'ACTIVE'
						AND WLH.LOAD_DATE >= TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY')
						AND WLDI.ITEM_NUM = '".ociresult($items, "BOL")."')";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);

			$plt_needed = $plt_needed - ociresult($short_term_data, "THE_COUNT");
			$ctn_needed = $ctn_needed - ociresult($short_term_data, "THE_SUM");
			
			$base_url = $url_bpo_page . '?BOL=' . ociresult($items, "BOL") . '&RcvdSts=';
			
			$plt_ih1_disp = "<a href='{$base_url}a1' target='_blank'>$plt_ih1</a>";
			if ($plt_ih2_disp != "&nbsp;") $plt_ih2_disp = "<a href='{$base_url}a2 target='_blank'>$plt_ih2_disp</a>";

			// with the equations done, now we just print it to screen
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($items, "BOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($items, "WM_COMMODITY_NAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $plt_ih1_disp; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_ih1; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $plt_ih2_disp; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_ih2_disp; ?></font></td>
		<td><font size="2" face="Verdana"><a href="<?php echo $base_url . 'b'; ?>" target="_blank"><? echo $plt_ih_unscanned; ?></a>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_ih_unscanned; ?>&nbsp;</font></td>
		<td><font size="2" face="Verdana"><a href="<?php echo $base_url . 'c'; ?>" target="_blank"><? echo $plt_ih_total; ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_ih_total; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $plt_needed; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $ctn_needed; ?></font></td>
		<td><font size="2" face="Verdana"><b><? echo ($plt_ih_total - $plt_needed); ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo ($ctn_ih_total - $ctn_needed); ?></b></font></td>
		<td><? echo $plt_hold_disp; ?></td>
		<td><? echo $ctn_hold_disp; ?></td>
		<td><? echo $plt_not_released_disp; ?></td>
		<td><? echo $ctn_not_released_disp; ?></td>
	</tr>
<?
		} while(ocifetch($items));
	}
?>
</table>
<p>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2" align="left"><font size="3" face="Verdana">Legend</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Column</b></font></td>
		<td><font size="2" face="Verdana"><b>Criteria</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">A1</font></td>
		<td><font size="2" face="Verdana">Pallets received, not On Hold, Have a QTY In House > 10, Vessel has been Released</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">A2</font></td>
		<td><font size="2" face="Verdana">Pallets received, not On Hold, Have a QTY In House > 0 and ≤ 10, Vessel has been Released</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">B</font></td>
		<td><font size="2" face="Verdana">Pallets NOT received, not On Hold, Have a QTY In House > 0, Vessel has been Released</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">T</font></td>
		<td><font size="2" face="Verdana">Column A1 + Column A2 + Column B</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">P</font></td>
		<td><font size="2" face="Verdana">All Loads with Load Date >= Today, minus any pallets already scanned on said orders</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">N</font></td>
		<td><font size="2" face="Verdana">Column T - Column P</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">H</font></td>
		<td><font size="2" face="Verdana">Pallets from Released Vessels marked as ON HOLD</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">V</font></td>
		<td><font size="2" face="Verdana">Pallets from Unreleased Vessels</font></td>
	</tr>
</table>
<?
	include("pow_footer.php");