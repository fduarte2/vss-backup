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


	$type = $HTTP_GET_VARS['type'];
	$pool = $HTTP_GET_VARS['pool'];
	$date = urldecode($HTTP_GET_VARS['date']);

	if($type == "in"){
		$display = "Came In and Stayed";
		$sql_having = "HAVING SUM(DECODE(SERVICE_CODE, 6, 1, 0)) < SUM(DECODE(SERVICE_CODE, 6, 0, 1))";
	} elseif($type == "out") {
		$display = "Shipped Out - Here Last Night";
		$sql_having = "HAVING SUM(DECODE(SERVICE_CODE, 6, 1, 0)) > SUM(DECODE(SERVICE_CODE, 6, 0, 1))";
	} elseif($type == "even") {
		$display = "Came In but Shipped Out";
		$sql_having = "HAVING SUM(DECODE(SERVICE_CODE, 6, 1, 0)) = SUM(DECODE(SERVICE_CODE, 6, 0, 1))";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Daily Activity Pallet List  <!--Date <? echo $date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Activity Type: <? echo $display; ?> !-->
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="1" align="center" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EECCDD">
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Pallet Counts, End-Of-Day.   Date:  <? echo $date; ?>    Pool:  <? echo $pool; ?>   Activity:  <? echo $display; ?></b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Pool#</b></font></td>
		<td><font size="2" face="Verdana"><b>Pallet ID</b></font></td>
	</tr>
<?


	$sql = "SELECT CA.PALLET_ID, SUM(DECODE(SERVICE_CODE, 6, 1, 0)) THE_OUTS, SUM(DECODE(SERVICE_CODE, 6, 0, 1)) THE_NOT_OUTS
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CA.PALLET_ID = CTAD.PALLET_ID
				AND CA.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CTAD.RECEIVER_ID
				AND CA.CUSTOMER_ID = '9722'
				AND CTAD.DOLE_POOL = '".$pool."'
				AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
			GROUP BY CA.PALLET_ID
			".$sql_having."
			ORDER BY CA.PALLET_ID";
	$plts = ociparse($rfconn, $sql);
	ociexecute($plts);
	while(ocifetch($plts)){

		if($bgcolor == "#FFFFFF"){
			$bgcolor = "#EEEEEE";
		} else {
			$bgcolor = "#FFFFFF";
		}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $date; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pool; ?></font></td>
		<td><font size="2" face="Verdana"><a href="pallet_details_index.php?pallet=<? echo ociresult($plts, "PALLET_ID"); ?>"><? echo ociresult($plts, "PALLET_ID"); ?></a></font></td>
<!--		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode($date); ?>&type=in&detail_pool=<? echo ociresult($pools, "DOLE_POOL"); ?>">
				<? echo $ins; ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode($date); ?>&type=out"><? echo $outs; ?></a></font></td>
		<td><font size="2" face="Verdana"><a href="upload_summary_totals_drilldown_index.php?date=<? echo urlencode($date); ?>&type=even"><? echo $both; ?></a></font></td> !-->
	</tr>
<?
	}
?>
</table>
<?
//	if($detail_pool != ""){