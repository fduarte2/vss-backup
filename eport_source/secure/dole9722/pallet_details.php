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


	if($HTTP_POST_VARS['pallet'] != ""){
		$pallet_id = $HTTP_POST_VARS['pallet'];
	} else {
		$pallet_id = $HTTP_GET_VARS['pallet'];
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Pallets History  <!--Date <? echo $date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Activity Type: <? echo $display; ?> !-->
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select" action="pallet_details_index.php" method="post">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Pallet ID:</font></td>
		<td align="left"><input type="text" name="pallet" value="<? echo $pallet_id; ?>" size="32" maxlength="32"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Retrieve Results"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($pallet_id != ""){
		$sql = "SELECT TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI') THE_REC, DOLE_POOL, CT.VARIETY, CT.ARRIVAL_NUM || '-' || NVL(VESSEL_NAME, 'TRUCKED-IN') THE_VESNAME
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD, VESSEL_PROFILE VP
				WHERE CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
					AND CT.PALLET_ID = '".$pallet_id."'
					AND CT.RECEIVER_ID = '9722'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			echo "<font color=\"#FF0000\">No Entries match searched criteria</font><br>";
		} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>Pallet Information</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Pool#</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "DOLE_POOL"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Received Date</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_REC"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Variety</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "VARIETY"); ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Inbound Vessel</b></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_VESNAME"); ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</table>
<table border="1" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EECCDD">
		<td colspan="4" align="center"><font size="3" face="Verdana"><b>Recorded Pallet Activity</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Activity Type</b></font></td>
		<td><font size="2" face="Verdana"><b>Date/Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Order #</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
	</tr>
<?
			$sql = "SELECT ORDER_NUM, SERVICE_CODE, QTY_CHANGE, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI') THE_ACT_TIME
					FROM CARGO_ACTIVITY
					WHERE CUSTOMER_ID = '9722'
						AND PALLET_ID = '".$pallet_id."'
					ORDER BY DATE_OF_ACTIVITY";
			$activity = ociparse($rfconn, $sql);
			ociexecute($activity);
			while(ocifetch($activity)){

				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#EEEEEE";
				} else {
					$bgcolor = "#FFFFFF";
				}

				switch(ociresult($activity, "SERVICE_CODE")){
					case "1":
						$display_activity = "Ship-In";
					break;
					case "6":
						$display_activity = "Truck-Out";
					break;
					case "7":
						$display_activity = "Returned";
					break;
					case "8":
						$display_activity = "Trucked-In";
					break;
					case "13":
						$display_activity = "Unshipped";
					break;
				}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $display_activity; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($activity, "THE_ACT_TIME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($activity, "ORDER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($activity, "QTY_CHANGE"); ?></font></td>
	</tr>
<?
			}
?>
</table>
<?
		}
	}
//	if($detail_pool != ""){