<?
/*
*	Adam Walter, Sep 2013.
*
*	page for overriding any and all rows.
*	HIGHLY FLAMMABLE
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

	if($submit == "Delete Line"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$sql = "DELETE FROM CANADIAN_LOAD_RELEASE
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);

		echo "<font color=\"0000FF\">Entry Deleted.  Click the link on the left to return to the main board.</font>";
		$just_deleted = true;
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC"><b>Main Board DELETE Entry</b>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($delete_auth != "Y"){
		echo "<font color=\"#FF0000\">This user is not authorized for this page.  Cancelling script.</font>";
		exit;
	}
?>
<? 
	if(!$just_deleted){ 
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="delete" action="general_override_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr><td width="15%"><font size="2" face="Verdana">LR#:</td><td><? echo $vessel; ?></font></td></tr>
	<tr><td width="15%"><font size="2" face="Verdana">Container#:</td><td><? echo $cont; ?></font></td></tr>
	<tr><td width="15%"><font size="2" face="Verdana">BoL/Order:</td><td><? echo $bol; ?></font></td></tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><font size="3" face="Verdana"><b>ARE YOU SURE YOU WANT TO DELETE THIS LINE?</b></font></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Delete Line"></td>
	</tr>
</form>
</table>
<? 
	} 
?>