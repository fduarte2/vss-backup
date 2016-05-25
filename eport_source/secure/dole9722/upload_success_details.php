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


	$act = $HTTP_GET_VARS['act'];
	$date = urldecode($HTTP_GET_VARS['date']);

	if($act == 2){
		$display = "2-Transfer";
	} elseif($act == 3) {
		$display = "3-Ship";
	} elseif($act == 4) {
		$display = "4-Return";
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Fresh Upload Success Details:  Date <? echo $date; ?>   Activity Type <? echo $display; ?>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	$sql = "SELECT *
			FROM DOLE_9722_HEADER DH, DOLE_9722_DETAIL DD
			WHERE TO_CHAR(REPORT_DATE, 'MM/DD/YYYY') = '".$date."'
				AND DH.FILE_ID = DD.FILE_ID
				AND ACTIVITY = '".$act."'
				AND ERROR_MOVE_TO_DB IS NULL
				AND ERROR_INPUT_LINE IS NULL
				AND IGNORE_LINE IS NULL
			ORDER BY POOL, PALLET_ID";
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
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "POOL"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "PALLET_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "QTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_TIME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_ORDER"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "COMM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "VARIETY"); ?></font></td>
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