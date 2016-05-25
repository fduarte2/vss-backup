<?
/*
*		Adam Walter, Sep/Oct 2008
*
*		SUBMIT ALL SCREEN of the Dole paper system.
*
*****************************************************************/

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);


	// initializations
	$vessel = $HTTP_POST_VARS['vessel'];
	$destination = $HTTP_POST_VARS['destination'];
	$load_date = $HTTP_POST_VARS['load_date'];

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$vessel_name = $row['VESSEL_NAME'];
	$sql = "SELECT DESTINATION FROM DOLEPAPER_DESTINATIONS WHERE DESTINATION_NB = '".$destination."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$destination_name = $row['DESTINATION'];

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Dole Submit All Page
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="2" cellspacing="0">
<form name="sel_vessel" action="index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="destination" value="<? echo $destination; ?>">
<input type="hidden" name="load_date" value="<? echo $load_date; ?>">
<input type="hidden" name="action" value="submit_all">
	<tr>
		<td colspan="3"align="center"><font size="3" face="Verdana">Are you sure you wish to submit ALL orders for the following?</font></td>
	</tr>
	<tr>
		<td width="46%" align="right"><font size="2" face="Verdana"><b>Vessel:</b></font></td>
		<td width="8%">&nbsp;</td>
		<td width="46%" align="left"><font size="2" face="Verdana"><b><? echo $vessel_name; ?></b></font></td>
	</tr>
	<tr>
		<td width="46%" align="right"><font size="2" face="Verdana"><b>Loading Date:</b></font></td>
		<td width="8%">&nbsp;</td>
		<td width="46%" align="left"><font size="2" face="Verdana"><b><? echo $load_date; ?></b></font></td>
	</tr>
	<tr>
		<td width="46%" align="right"><font size="2" face="Verdana"><b>Destination:</b></font></td>
		<td width="8%">&nbsp;</td>
		<td width="46%" align="left"><font size="2" face="Verdana"><b><? echo $destination_name; ?></b></font></td>
	</tr>
<?
	if($sub_type == "PORT"){
?>
	<tr>
		<td colspan="3" align="center">&nbsp;</textarea></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana">Note:  Port employees submitting orders need to supply a reason for override:</font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><textarea name="special_note" cols="40" rows="5"></textarea></td>
	</tr>
<?
	}
?>
	<tr>
		<td colspan="3" align="center">&nbsp;</textarea></td>
	</tr>
	<tr>
		<td width="46%" align="right"><input type="submit" name="yes_or_no" value="Yes, Submit All Orders"></td>
		<td width="8%">&nbsp;</td>
		<td width="46%" align="left"><input type="submit" name="yes_or_no" value="No (Return to Main Screen)"></td>
	</tr>
</form>
</table>