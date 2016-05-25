<?
/*
*	Adam Walter, Feb 2009
*
*	This page is a report for in-house rolls of Dole
*	Paper, and current orders against them.
*
*	Displays As-of the time report is generated.
********************************************************/

//	include 'class.ezpdf.php';
//	include("useful_info.php");
//	$short_term_data_cursor = ora_open($conn);
//	$dockticket_cursor = ora_open($conn);

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dockticket Paper REAL TIME Inventory
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="11" align="center"><font size="3" face="Verdana">Inventory For Dockticket Paper As Of:  <? echo date('m/d/Y h:i:s'); ?></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>Dock Ticket</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Code</b></font></td>
		<td><font size="2" face="Verdana"><b>Rolls Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Rolls Shipped</b></font></td>
		<td><font size="2" face="Verdana"><b>Rolls In House</b></font></td>
		<td><font size="2" face="Verdana"><b>Rolls In House (Reject)</b></font></td>
		<td><font size="2" face="Verdana"><b>Pending Orders</b></font></td>
		<td><font size="2" face="Verdana"><b>Weight In House</b></font></td>
	</tr>

<?
	$sql = "SELECT RECEIVER_ID, BOL, CARGO_DESCRIPTION, BATCH_ID, SUM(QTY_RECEIVED) THE_ORIG, TO_CHAR(MIN(DATE_RECEIVED), 'MM/DD/YYYY') THE_REC, SUM(QTY_IN_HOUSE) IN_HOUSE
			FROM CARGO_TRACKING
			WHERE DATE_RECEIVED IS NOT NULL
			AND REMARK = 'DOLEPAPERSYSTEM'
			GROUP BY RECEIVER_ID, BOL, CARGO_DESCRIPTION, BATCH_ID
			HAVING SUM(QTY_IN_HOUSE) > 0
			ORDER BY RECEIVER_ID, BOL";
	$dt_data = ociparse($rfconn, $sql);
	ociexecute($dt_data);
	if(!ocifetch($dt_data)){
?>
		<tr><td colspan="11" align="center"><b>No Shippable In-House Rolls to display</b></td></tr>
<?
	} else {
		$total_rec = 0;
		$total_ship = 0;
		$total_IH = 0;
		$total_IH_rej = 0;
		$total_pending = 0;

		$cur_cust = ociresult($dt_data, "RECEIVER_ID");

		do { // do this for each dock ticket
			if($cur_cust != ociresult($dt_data, "RECEIVER_ID")){
?>
	<tr>
		<td colspan="4"><font size="2" face="Verdana"><b>Total for Customer <? echo $cur_cust; ?>:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_rec; ?></b></font></td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b><? echo $total_ship; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_IH; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_IH_rej; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_pending; ?></b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
				$total_rec = 0;
				$total_ship = 0;
				$total_IH = 0;
				$total_IH_rej = 0;
				$total_pending = 0;
				$cur_cust = ociresult($dt_data, "RECEIVER_ID");
			}

			$sql = "SELECT SUM(QTY_SHIP) TO_SHIP FROM DOLEPAPER_DOCKTICKET DD, DOLEPAPER_ORDER DPO WHERE DD.ORDER_NUM = DPO.ORDER_NUM AND DD.DOCK_TICKET = '".ociresult($dt_data, "BOL")."' AND DPO.STATUS NOT IN ('6', '7', '8')";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$pending = ociresult($short_term_data, "TO_SHIP");

			$sql = "SELECT SUM(QTY_DAMAGED) THE_DMG, SUM(WEIGHT) THE_WEIGHT FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND REMARK = 'DOLEPAPERSYSTEM' AND QTY_IN_HOUSE > 0 AND BOL = '".ociresult($dt_data, "BOL")."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			$DMG_Inhouse = ociresult($short_term_data, "THE_DMG");
			$WT_Inhouse = ociresult($short_term_data, "THE_WEIGHT");

			$total_rec += ociresult($dt_data, "THE_ORIG");
			$total_ship += (ociresult($dt_data, "THE_ORIG") - ociresult($dt_data, "IN_HOUSE"));
			$total_IH += ociresult($dt_data, "IN_HOUSE");
			$total_IH_rej += $DMG_Inhouse;
			$total_pending += $pending;

?>
	<tr>
		<td><? echo ociresult($dt_data, "RECEIVER_ID"); ?></td>
		<td><? echo ociresult($dt_data, "BOL"); ?></td>
		<td><? echo ociresult($dt_data, "CARGO_DESCRIPTION"); ?></td>
		<td><? echo ociresult($dt_data, "BATCH_ID"); ?></td>
		<td><? echo ociresult($dt_data, "THE_ORIG"); ?></td>
		<td><? echo ociresult($dt_data, "THE_REC"); ?></td>
		<td><? echo (ociresult($dt_data, "THE_ORIG") - ociresult($dt_data, "IN_HOUSE")); ?></td>
		<td><a href="DTInHousedetails_index.php?DT=<? echo ociresult($dt_data, "BOL"); ?>"><? echo ociresult($dt_data, "IN_HOUSE"); ?></a></td>
		<td><? echo (0 + $DMG_Inhouse); ?></td>
		<td><a href="DTInHousedetails_pending_index.php?DT=<? echo ociresult($dt_data, "BOL"); ?>"><? echo (0 + $pending); ?></a></td>
		<td><? echo (0 + $WT_Inhouse); ?></td>
	</tr>
<?
		} while(ocifetch($dt_data));
?>
	<tr>
		<td colspan="4"><font size="2" face="Verdana"><b>Total for Customer <? echo $cur_cust; ?>:</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_rec; ?></b></font></td>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b><? echo $total_ship; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_IH; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_IH_rej; ?></b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $total_pending; ?></b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
	}