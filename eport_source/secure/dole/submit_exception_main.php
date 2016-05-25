<?
/*
*		Adam Walter, Sep/Oct 2008
*
*		SUBMIT EXCEPTION (aka Port-submissions) of the Dole paper system.
*
************************************************************************/

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$order_num = $HTTP_POST_VARS['order_num'];
	if($order_num == ""){
		$order_num = $HTTP_GET_VARS['order_num'];
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Dole Submit One (Port) Page
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="2" cellspacing="0">
<form name="sub_order" action="index.php" method="post">
<input type="hidden" name="order_num" value="<? echo $order_num; ?>">
<input type="hidden" name="action" value="submit_order">
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana">Reason for Port Submission Override:</font></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><textarea name="special_note" cols="40" rows="5"></textarea></td>
	</tr>
	<tr>
		<td colspan="3" align="center">&nbsp;</textarea></td>
	</tr>
	<tr>
		<td width="46%" align="right"><input type="submit" name="yes_or_no" value="Submit Order"></td>
		<td width="8%">&nbsp;</td>
		<td width="46%" align="left"><input type="submit" name="yes_or_no" value="Cancel Change"></td>
	</tr>
</form>
</table>