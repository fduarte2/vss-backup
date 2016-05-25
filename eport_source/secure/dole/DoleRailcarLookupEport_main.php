<?
	$user_cust_num = $HTTP_COOKIE_VARS["eport_customer_id"];
	$user = $HTTP_COOKIE_VARS["eport_user"];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
// $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
//    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);         // general purpose
  $short_term_cursor = ora_open($conn);

	$LR = $HTTP_POST_VARS['LR'];
	$dockticket_from = $HTTP_POST_VARS['dockticket_from'];
	$dockticket_to = $HTTP_POST_VARS['dockticket_to'];
	$sort = $HTTP_POST_VARS['sort'];
	$rec = $HTTP_POST_VARS['rec'];



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole Dockticket Railcar Lookup
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0"> <!-- width="100%"  !-->
<form action="DoleRailcarLookupEport.php" method="post" name="the_upload">
	<tr>
		<td colspan="3"><font size="3" face="Verdana"><b><br>(Optional)&nbsp;Narrow search down by:</b></font><br>&nbsp;<br></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Arrival#:&nbsp;&nbsp;</b></font></td>
		<td colspan="2"><font size="2" face="Verdana"><input name="LR" type="text" size="15" maxlength="15" value="<? echo $LR; ?>"></font></td>
	</tr>
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>DockTicket Range:</b></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td width="20%"><font size="2" face="Verdana"><b>From #:&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="dockticket_from" type="text" size="10" maxlength="12" value="<? echo $dockticket_from; ?>"></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b>To #:&nbsp;&nbsp;</b></font></td>
		<td><font size="2" face="Verdana"><input name="dockticket_to" type="text" size="10" maxlength="12" value="<? echo $dockticket_to; ?>"></font></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b>Received Status&nbsp;&nbsp;</b></font></td>
		<td><select name="rec"><option value="both">All</option>
								<option value="yes" <? if($rec == "yes"){?>selected<?}?>>Received</option>
								<option value="no" <? if($rec == "no"){?>selected<?}?>>Un-Received</option>
						</select></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><b>Sort By&nbsp;&nbsp;</b></font></td>
		<td><select name="sort"><option value="LR">Arrival</option>
								<option value="dockticket" <? if($sort == "dockticket"){?>selected<?}?>>Dockticket</option>
						</select></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Retrieve List"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</form>
</table>

<?
	if($submit != ""){
		$sql = "SELECT DISTINCT ARRIVAL_NUM, BOL FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM'";
		if($LR != ""){
			$sql .= " AND ARRIVAL_NUM = '".$LR."'";
		}
		if($dockticket_from != ""){
			$sql .= " AND TO_NUMBER(BOL) >= '".$dockticket_from."'";
		}
		if($dockticket_to != ""){
			$sql .= " AND TO_NUMBER(BOL) <= '".$dockticket_to."'";
		}
		if($rec == "yes"){
			$sql .= " AND DATE_RECEIVED IS NOT NULL";
		} elseif($rec == "no"){
			$sql .= " AND DATE_RECEIVED IS NULL";
		}
		if($sort == "LR"){
			$sql .= " ORDER BY ARRIVAL_NUM";
		} else {
			$sql .= " ORDER BY BOL";
		}

?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Arrival#</b></font></td>
		<td><font size="2" face="Verdana"><b>DockTicket</b></font></td>
	</tr>
<?
		$ora_success = ora_parse($short_term_cursor, $sql);
		$ora_success = ora_exec($short_term_cursor, $sql);
		if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">No Entries match Search Criteria</font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><a href="DoleRailcarLookupEportDetails.php?LR=<? echo $short_term_row['ARRIVAL_NUM']; ?>&DT=<? echo $short_term_row['BOL']; ?>" target="DoleRailcarLookupEportDetails.php?LR=<? echo $short_term_row['ARRIVAL_NUM']; ?>&DT=<? echo $short_term_row['BOL']; ?>"><? echo $short_term_row['ARRIVAL_NUM']; ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo $short_term_row['BOL']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>