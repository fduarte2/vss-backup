<?
/*
*    This page generates a Fruit Tag Audit (tracker report) for a specified pallet ID.
*    Adapted from the Tag Audit pages in Finance and Inventory on the Intranet.
*    Charles Marttinen, March 2015
*
******************************************************************/
?>

<script type="text/javascript" src="/functions/calendar.js"></script>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fruit Tag Audit</font>
         <br />
		 <font size="2" face="Verdana">
			<hr>
         </font>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
        printf("Please enter a Pallet ID (or part of a Pallet ID) to view the Tag Audit report.");
      ?>
         </p></font>
	 <table align="left" width="80%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Pallet ID:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="get" action="tag_audit_print.php" onsubmit="return validate_form()">
                 <input type="textbox" name="pallet_id" size="22" value="<?= $pallet_id ?>">
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4" height="8"></td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="View the Report">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>
<?
  // Do we have some results from the previous page?
  if($pallet_id != ""){
   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);
   $stmt = "select pallet_id, cargo_description, to_char(date_received, 'MM/DD/YYYY HH24:MI:SS') date_received, receiver_id, arrival_num 
			from cargo_tracking where pallet_id like '%$pallet_id%'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
?>
   <br />
   <font size="2" face="Verdana"><p>
      <?
        printf("Please click one of the possible matches below:");
      ?>
    </p></font>
	<br />
   <table border="1" cellspacing="0">
     <th bgcolor="#FFA34F"><font size="2" face="Verdana"><b>Pallet ID</b></font></th>
	 <th bgcolor="#FFA34F"><font size="2" face="Verdana"><b>Description</b></font></th>
	 <th bgcolor="#FFA34F"><font size="2" face="Verdana"><b>Date Received</b></font></th>
	 <th bgcolor="#FFA34F"><font size="2" face="Verdana"><b>Customer#</b></font></th>
	 <th bgcolor="#FFA34F"><font size="2" face="Verdana"><b>Arrival#</b></font></th>
<?
   while(ora_fetch_into($cursor, $cargo_tracking, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td><a href="tag_audit_print.php?pallet_id=<? echo $cargo_tracking['PALLET_ID']; ?>&cust=<? echo $cargo_tracking['RECEIVER_ID']; ?>&ves=<? echo $cargo_tracking['ARRIVAL_NUM']; ?>">
			<font size='2' face='Verdana'><? echo $cargo_tracking['PALLET_ID']; ?></font></a></td>
		<td><font size="2" face="Verdana"><? echo $cargo_tracking['CARGO_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $cargo_tracking['DATE_RECEIVED']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $cargo_tracking['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $cargo_tracking['ARRIVAL_NUM']; ?></font></td>
	</tr>
<?
	}
?>
</table>
<?
  }
?>
