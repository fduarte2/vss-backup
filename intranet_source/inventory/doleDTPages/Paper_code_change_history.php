<?
/*
*	Screen that lets INV see adjusted paper codes (done by them).
*	Jul 2012.
********************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Dole EDI codes";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$DT = $HTTP_POST_VARS['DT'];
	$from_date = $HTTP_POST_VARS['from_date'];
	$to_date = $HTTP_POST_VARS['to_date'];

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Paper Code Change History</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="first" action="Paper_code_change_history.php" method="post">
	<tr>
		<td width="10%"><font size="3" face="Verdana">Dock Ticket:</font></td>
		<td><input type="text" size="10" maxlength="10" name="DT" value="<? echo $DT; ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="3" face="Verdana">From Date:</font></td>
		<td><input type="text" size="10" maxlength="10" name="from_date" value="<? echo $from_date; ?>"><a href="javascript:show_calendar('first.from_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="10%"><font size="3" face="Verdana">To Date:</font></td>
		<td><input type="text" size="10" maxlength="10" name="to_date" value="<? echo $to_date; ?>"><a href="javascript:show_calendar('first.to_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Check DT"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && ($DT != "" || $from_date != "" || $to_date != "")){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana">Dock Ticket</font></td>
		<td><font size="3" face="Verdana">Change Date</font></td>
		<td><font size="3" face="Verdana">Old Code</font></td>
		<td><font size="3" face="Verdana">New Code</font></td>
		<td><font size="3" face="Verdana">LR#</font></td>
		<td><font size="3" face="Verdana"># Rolls</font></td>
		<td><font size="3" face="Verdana">Basis Weight</font></td>
		<td><font size="3" face="Verdana">Width</font></td>
		<td><font size="3" face="Verdana">Customer</font></td>
	</tr>
<?
		$filter_clause = "";
		if($DT != ""){
			$filter_clause .= " AND CT.BOL = '".$DT."'";
		}
		if($from_date != ""){
			$filter_clause .= " AND DMCC.CHANGED_ON >= TO_DATE('".$from_date."', 'MM/DD/YYYY')";
		}
		if($to_date != ""){
			$filter_clause .= " AND DMCC.CHANGED_ON <= TO_DATE('".$to_date."', 'MM/DD/YYYY')";
		}

		$sql = "SELECT COUNT(*) THE_ROLLS, TO_CHAR(CHANGED_ON, 'MM/DD/YYYY HH24:MI') CHANGED_DATE,
					OLD_CODE, NEW_CODE, CT.ARRIVAL_NUM, CT.MARK, CARGO_SIZE, CT.RECEIVER_ID, BOL
				FROM CARGO_TRACKING CT, DOLEPAPER_MANUAL_CODE_CHANGE DMCC
				WHERE CT.PALLET_ID = DMCC.PALLET_ID
					AND CT.ARRIVAL_NUM = DMCC.ARRIVAL_NUM
					AND CT.RECEIVER_ID = DMCC.CUSTOMER_ID".$filter_clause."
				GROUP BY TO_CHAR(CHANGED_ON, 'MM/DD/YYYY HH24:MI'),
					OLD_CODE, NEW_CODE, CT.ARRIVAL_NUM, CT.MARK, CARGO_SIZE, CT.RECEIVER_ID, BOL
				ORDER BY TO_DATE(TO_CHAR(CHANGED_ON, 'MM/DD/YYYY HH24:MI'), 'MM/DD/YYYY HH24:MI')";
//		echo $sql;
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr>
		<td colspan="9" align="center"><font size="3" face="Verdana">No rolls altered within entered criteria.</font></td>
	</tr>
<?
		} else {
			do {
?>
		<tr>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "BOL"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "CHANGED_DATE"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "OLD_CODE"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "NEW_CODE"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "ARRIVAL_NUM"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_ROLLS"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "MARK"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "CARGO_SIZE"); ?></font></td>
			<td><font size="2" face="Verdana"><? echo ociresult($stid, "RECEIVER_ID"); ?></font></td>
		</tr>
<?
			} while(ocifetch($stid));
		}
?>
</table>
<? 
	}
	include("pow_footer.php");