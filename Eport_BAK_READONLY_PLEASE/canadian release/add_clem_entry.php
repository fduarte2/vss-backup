<?
/*
*	Adam Walter, Oct 2013.
*
*	Adding an entry to a vessel on the Scoreboard Screen.
*************************************************************************/

	include("can_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
			WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vesname = ociresult($short_term_data, "THE_VESSEL");

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">New Release Line Entry
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($include_auth != "Y"){
		echo "<font color=\"#FF0000\">This user is not authorized for this page.  Cancelling script.</font>";
		exit;
	}
?>	

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="data_submit" action="canadian_scoreboard_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana">PoW Vessel#:</font></td>
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Container/Trailer#:</font></td>
		<td><input type="text" name="container" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Order #:</font></td>
		<td><input type="text" name="order" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Consignee:</font></td>
		<td><select name="consignee"><option value="">Please Select</option>
<?
	$sql = "SELECT CUSTOMER_NAME, CUSTOMER_ID FROM CUSTOMER_PROFILE
			WHERE CUSTOMER_ID IN
				(SELECT RECEIVER_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."')
			AND ('".$user."' = 'PORTINV'
				OR
				CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM EPORT_EXPED_AUTH WHERE CARGO_SYSTEM = 'CLEMENTINE' AND USERNAME = '".$user."')
				)
			ORDER BY CUSTOMER_ID";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
				<option value="<? echo ociresult($short_term_data, "CUSTOMER_ID"); ?>"><? echo ociresult($short_term_data, "CUSTOMER_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">T & E Number:</font></td>
		<td><input type="text" name="t_and_e" size="20" maxlength="70"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Seal:</font></td>
		<td><input type="text" name="seal" size="30" maxlength="30"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Insert Record"></td>
	</tr>
</form>
</table>