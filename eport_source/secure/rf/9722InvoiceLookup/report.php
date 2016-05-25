<?
/*
*	
*
*	
*************************************************************************/

	include("9722_db_def.php");
	$eport_customer_id = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];
	if (($user == "TS")||($user =="sshoemaker") || ($user == "lmizikar")) {
		$eport_customer_id = "453";
	}


?>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana"><b>INVOICE DETAILS FOR: <? echo $invoice_num; ?> (use ctrl-f to search for a pallet id)</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>RECEIVED</b></font></td>
		<td><font size="2" face="Verdana"><b>POOL NUMBER</b></font></td>
		<td><font size="2" face="Verdana"><b>PALLET ID</b></font></td>
		<td><font size="2" face="Verdana"><b>AMOUNT</b></font></td>
	</tr>
<?
	$sql = "SELECT TO_CHAR (ct.date_received, 'MM/DD/YYYY') THE_DATE, rbd.dole_pool THE_POOL, rbd.pallet_id THE_PALLET, rbd.service_amount THE_AMOUNT FROM rf_billing rb, rf_billing_detail rbd, cargo_tracking ct, cargo_tracking_additional_data ctad WHERE rb.customer_id = '".$eport_customer_id."' AND rb.invoice_num =".$invoice_num." AND rb.billing_num = rbd.sum_bill_num AND rbd.pallet_id = ct.pallet_id AND rbd.dole_pool = ctad.dole_pool AND ct.receiver_id = '9722' AND ctad.receiver_id = ct.receiver_id AND ctad.arrival_num = ct.arrival_num AND ctad.pallet_id = rbd.pallet_id ORDER BY THE_DATE, THE_POOL, THE_PALLET, THE_AMOUNT";

	//echo $sql;
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
		$pallet_id = ociresult($short_term_data, "THE_PALLET");
		$pool_num = ociresult($short_term_data, "THE_POOL");
		$the_amount = ociresult($short_term_data, "THE_AMOUNT");
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pool_num; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_id; ?></font></td>
		<td><font size="2" face="Verdana"><? echo "<a href=pallet_details.php?pallet_id=".$pallet_id."&pool_num=".$pool_num.">".$the_amount."</a"?></font></td>
	</tr>
<?
	}
?>
</table>

