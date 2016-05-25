<?
/*
*		Adam Walter, Jsn 2015.
*
*		Dole9722 review details
*********************************************************************************/


	$pagename = "dole9722_upload_report";
	include("dole9722_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}


	$serv = $HTTP_GET_VARS['serv'];
	$date = urldecode($HTTP_GET_VARS['date']);

	if($serv == 1){
		$display = "Ship-Ins";
	} elseif($serv == 6) {
		$display = "Ship-Outs";
	} elseif($serv == 7) {
		$display = "Returns";
	} elseif($serv == 8) {
		$display = "Truck Ins";
	} elseif($serv == 13) {
		$display = "Unships";
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Upload Success Details:  Date <? echo $date; ?>   Activity Type:  <? echo $display; ?>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
/*	$sql = "SELECT *
			FROM DOLE_9722_HEADER DH, DOLE_9722_DETAIL DD
			WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$date."'
				AND DH.FILE_ID = DD.FILE_ID
				AND ACTIVITY = '".$act."'
				AND ERROR_MOVE_TO_DB IS NULL
				AND ERROR_INPUT_LINE IS NULL
				AND IGNORE_LINE IS NULL
			ORDER BY PALLET_ID";
*/
	if($serv != "6"){
		$sql = "SELECT CT.PALLET_ID THE_PLT, CTAD.DOLE_POOL THE_POOL, CA.QTY_CHANGE THE_QTY, TO_CHAR(DATE_OF_ACTIVITY, 'HH24:MI') THE_TIME, 
					CA.ORDER_NUM THE_ORDER, COMP.COMMODITY_NAME THE_COMM, CT.VARIETY THE_VAR, '' MULTIPLE_MENTION
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD, COMMODITY_PROFILE COMP
				WHERE CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.RECEIVER_ID = '9722'
					AND CA.SERVICE_CODE = '".$serv."'
					AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				ORDER BY DOLE_POOL, CT.PALLET_ID";
	} else {
		$sql = "SELECT CT.PALLET_ID THE_PLT, CTAD.DOLE_POOL THE_POOL, MAX(CA.QTY_CHANGE) THE_QTY, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'HH24:MI') THE_TIME, 
					CA.ORDER_NUM THE_ORDER, COMP.COMMODITY_NAME THE_COMM, CT.VARIETY THE_VAR, DECODE(COUNT(*), 1, '', '*MULTIPLE ENTRIES*') MULTIPLE_MENTION
				FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD, COMMODITY_PROFILE COMP
				WHERE CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.PALLET_ID = CA.PALLET_ID
					AND CT.RECEIVER_ID = CA.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM
					AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE
					AND CT.RECEIVER_ID = '9722'
					AND CA.SERVICE_CODE = '".$serv."'
					AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				GROUP BY CT.PALLET_ID, CTAD.DOLE_POOL, CA.ORDER_NUM, COMP.COMMODITY_NAME, CT.VARIETY
				ORDER BY DOLE_POOL, CT.PALLET_ID";
	}

	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		echo "<font color=\"#FF0000\">No Entries match selected criteria</font><br>";
	} else {
		$total = 0;
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Pool</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet Number</b></font></td>
		<td><font size="2" face="Verdana"><b>Boxes</b></font></td>
		<td><font size="2" face="Verdana"><b>Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Order</b></font></td>
		<td><font size="2" face="Verdana"><b>Com</b></font></td>
		<td><font size="2" face="Verdana"><b>Variety</b></font></td>
	</tr>
<?
		do {
			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			} else {
				$bgcolor = "#FFFFFF";
			}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_POOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_PLT")." ".ociresult($stid, "MULTIPLE_MENTION"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_QTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_TIME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_ORDER"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_COMM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_VAR"); ?></font></td>
	</tr>
<?
			$total++;
		} while(ocifetch($stid));
?>
	<tr bgcolor="#DDEEDD">
		<td><font size="2" face="Verdana"><b>Total</b></font></td>
		<td colspan="6"><font size="2" face="Verdana"><? echo $total; ?> Pallets</font></td>
	</tr>
<?
	}
?>
</table>