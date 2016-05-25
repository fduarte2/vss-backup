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

	$vessel = $HTTP_GET_VARS['vessel'];
	$cont = $HTTP_GET_VARS['cont'];
	$bol = $HTTP_GET_VARS['bol'];

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

	$sql = "SELECT CONSIGNEE, T_AND_E_DISPLAY, SEAL_NUM
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND BOL = '".$bol."'
				AND CONTAINER_NUM = '".$cont."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$consignee = ociresult($short_term_data, "CONSIGNEE");
	$t_and_e_disp = ociresult($short_term_data, "T_AND_E_DISPLAY");
	$seal = ociresult($short_term_data, "SEAL_NUM");

	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
			WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vesname = ociresult($short_term_data, "THE_VESSEL");
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="data_submit" action="canadian_scoreboard_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana">PoW Vessel#:</font></td>
		<td><font size="2" face="Verdana"><? echo $vesname; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Container/Trailer#:</font></td>
		<td><font size="2" face="Verdana"><? echo $cont; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Order #:</font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Consignee:</font></td>
		<td><font size="2" face="Verdana"><? echo $consignee; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">T & E Number:</font></td>
		<td><input type="text" name="t_and_e_disp" size="20" maxlength="70" value="<? echo $t_and_e_disp; ?>"></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Seal:</font></td>
		<td><font size="2" face="Verdana"><? echo $seal; ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Edit Record"></td>
	</tr>
</form>
</table>