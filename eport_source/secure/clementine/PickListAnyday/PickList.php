<?
/* Adam Walter, 8/25/07
*	This file simply displays on Eport a grid of inventory,
*	Packing houses across the top of the grid,
*	Sizes down the left.
*********************************************************************************/

	$conn2 = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn2 = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn2 < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn2));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn2);
	$Short_Term_Data = ora_open($conn2);

   $submit = $HTTP_POST_VARS['submit'];
   $date = $HTTP_POST_VARS['date'];
	
	if($submit != ""){
		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $date)){
			echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format.</font>";
			$submit = "";
		}
	}

?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">DC Orders, <? echo $date; ?></font>
         <hr>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="data_submit" action="index_picklist.php" method="post">
	<tr>
		<td><font size="3" face="Verdana">Enter Date:</font><input type="text" name="date" size="10" maxlength="10" value="<? echo $date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('data_submit.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Get Picklists"><hr></td>
	</tr>
</form>
<table>

<? 
	if($submit != ""){
?>
<table border="1" width="65%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="7" align="center"><font size="4" face="Verdana"><b>DC ORDERS + PACKING STATIONS</b></font></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana"><b><? echo $date; ?></b></font></td>
		<td colspan="5" align="left">&nbsp;</td>
		<td align="left"><font size="3" face="Verdana"><b>Comments</b></font></td>
	</tr>
<?
		$cur_order = "beans";
		$sql = "SELECT DCO.ORDERNUM THE_ORDER, DCO.LOADTYPE, DCO.ORDERSTATUSID, TO_CHAR(DCO.PICKUPDATE, 'MM/DD/YYYY') THE_PICKUP,
					DCOD.ORDERQTY, DCOD.SIZEHIGH, DCOD.SIZELOW, DCOD.WEIGHTKG, DCC.CONSIGNEENAME, DCP.COMMENTS, DCP.PACKINGHOUSE, DCP.PALLETQTY
				FROM DC_ORDER DCO, DC_ORDERDETAIL DCOD, DC_PICKLIST DCP, DC_CONSIGNEE DCC 
				WHERE DCOD.ORDERNUM = DCP.ORDERNUM(+)
					AND DCOD.ORDERDETAILID = DCP.ORDERDETAILID(+)
					AND DCOD.ORDERNUM = DCO.ORDERNUM
					AND DCO.CONSIGNEEID = DCC.CONSIGNEEID
					AND DCO.CUSTOMERID = DCC.CUSTOMERID
					AND DCO.PICKUPDATE >= TO_DATE('".$date."', 'MM/DD/YYYY') 
					AND DCO.PICKUPDATE < TO_DATE('".$date."', 'MM/DD/YYYY') + 1
				ORDER BY DCO.ORDERNUM";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="7" align="center"><font size="3" face="Verdana"><b>No Picklists for Entered Date.</b></font></td>
	</tr>
<?
		} else {
			do {
				if($cur_order != $row['THE_ORDER']){
?>
	<tr>
		<td colspan="3" align="left"><font size="2" face="Verdana"><b><? echo $row['ORDERSTATUSID']."&nbsp;&nbsp;&nbsp;".$row['THE_ORDER']."&nbsp;".$row['CONSIGNEENAME']; ?></b></font></td>
		<td colspan="4" align="left"><font size="2" face="Verdana"><b><? echo $row['LOADTYPE']; ?></b></font></td>
	</tr>
<?
					$cur_order = $row['THE_ORDER'];
				}
?>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana"><? echo $row['SIZELOW']." -- ".$row['SIZEHIGH']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo number_format($row['ORDERQTY'], 0)." cases"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['WEIGHTKG']." kg"; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PACKINGHOUSE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['PALLETQTY']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['COMMENTS']; ?></font></td>
	</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	}
?>
</table>