<?
/*
*	
*
*	
*************************************************************************/

	include("9722_db_def.php");
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];
//	if (($user == "TS")||($user =="sshoemaker") || ($user == "lmizikar")) {
//		$eport_customer_id = "453";
//		$pallet_id = '$_GET[pallet_id]';
//		$pool_num = '$_GET[pool_num]';
//	}
		
		//echo $pallet_id;
		//echo $pool_num;
?>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="9" align="center"><font size="3" face="Verdana"><b>DOLE FRESH STORAGE INVOICE - PALLET DETAILS</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>PALLET_ID</b></font></td>
		<td><font size="2" face="Verdana"><b>POOL NUMBER</b></font></td>
		<td><font size="2" face="Verdana"><b>RECEIVED DATE</b></font></td>
		<td><font size="2" face="Verdana"><b>RECEIVED QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>IN HOUSE QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>ACTIVITY TYPE</b></font></td>
		<td><font size="2" face="Verdana"><b>ACTIVITY DATE</b></font></td>
		<td><font size="2" face="Verdana"><b>ACTIVITY QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>ACTIVITY DESCRIPTION</b></font></td>
	</tr>
<?
	$sql = "select ct.pallet_id THE_PALLET, ctad.dole_pool THE_POOL, date_received THE_REC_DATE, qty_received THE_QTY_REC, qty_in_house THE_QTY_INHSE, 
			date_of_activity THE_ACT_DATE, service_name THE_SERVICE, qty_change THE_CHANGE, activity_description THE_DESC
			from cargo_tracking ct, cargo_tracking_additional_data ctad, cargo_activity ca, service_category sc
			WHERE ct.pallet_id = '".trim($pallet_id)."' AND ctad.dole_pool = '".$pool_num."' 
			AND ct.receiver_id = '9722' AND ctad.receiver_id = ct.receiver_id AND ca.customer_id = ct.receiver_id
			AND ctad.arrival_num = ct.arrival_num AND ca.arrival_num = ct.arrival_num
			AND ctad.pallet_id = ct.pallet_id AND ca.PALLET_ID = ct.PALLET_ID
			AND ca.SERVICE_CODE = sc.SERVICE_CODE";

	//echo $sql;
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_PALLET"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_POOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_REC_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_QTY_REC"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_QTY_INHSE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_SERVICE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_ACT_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_CHANGE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_DESC"); ?></font></td>
	</tr>
<?
	}
?>
</table>
