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
	$date = urldecode($HTTP_GET_VARS['date']);

	if($type == "in"){
		$display = "Came In and Stayed";
	} elseif($type == "out") {
		$display = "Shipped Out - Here Last Night";
	} elseif($type == "even") {
		$display = "Came In but Shipped Out";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Daily Activity (By Pool):  <!--Date <? echo $date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Activity Type: <? echo $display; ?> !-->
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="1" align="center" cellpadding="4" cellspacing="0">
	<tr bgcolor="#EECCDD">
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Pallet Counts (By Pool), End-Of-Day</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Pool#</b></font></td>
		<td><font size="2" face="Verdana"><b><? echo $display; ?></b></font></td>
	</tr>
<?
	$sql = "SELECT DISTINCT DOLE_POOL
			FROM CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
			WHERE CA.PALLET_ID = CTAD.PALLET_ID
				AND CA.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
				AND CA.CUSTOMER_ID = CTAD.RECEIVER_ID
				AND CA.CUSTOMER_ID = '9722'
				AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
			ORDER BY DOLE_POOL";
	$pools = ociparse($rfconn, $sql);
	ociexecute($pools);
	while(ocifetch($pools)){
		$ins = 0;
		$outs = 0;
		$both = 0;


		$sql = "SELECT CA.PALLET_ID, SUM(DECODE(SERVICE_CODE, 6, 1, 0)) THE_OUTS, SUM(DECODE(SERVICE_CODE, 6, 0, 1)) THE_NOT_OUTS
				FROM CARGO_ACTIVITY CA, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CA.PALLET_ID = CTAD.PALLET_ID
					AND CA.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CA.CUSTOMER_ID = CTAD.RECEIVER_ID
					AND CA.CUSTOMER_ID = '9722'
					AND CTAD.DOLE_POOL = '".ociresult($pools, "DOLE_POOL")."'
					AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$date."'
				GROUP BY CA.PALLET_ID";
		$plts = ociparse($rfconn, $sql);
		ociexecute($plts);
		while(ocifetch($plts)){
			if(ociresult($plts, "THE_OUTS") < ociresult($plts, "THE_NOT_OUTS")){
				$ins++;
			} elseif(ociresult($plts, "THE_OUTS") > ociresult($plts, "THE_NOT_OUTS")){
				$outs++;
			} else {
				$both++;
			}
		}

		if($type == "in"){
			$show_value = $ins;
		} elseif($type == "out") {
			$show_value = $outs;
		} elseif($type == "even") {
			$show_value = $both;
		}

		if($show_value > 0){
			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			} else {
				$bgcolor = "#FFFFFF";
			}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $date; ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($pools, "DOLE_POOL"); ?></font></td>
<!--		<td><font size="2" face="Verdana"><? echo $show_value; ?></font></td> !-->
		<td><font size="2" face="Verdana">
					<a href="upload_result_list_by_datepooltype_index.php?date=<? echo urlencode($date); ?>&type=<? echo $type; ?>&pool=<? echo ociresult($pools, "DOLE_POOL"); ?>"><? echo $show_value; ?>
			</a></font></td>
	</tr>
<?
		}
	}
?>
</table>
<?
//	if($detail_pool != ""){