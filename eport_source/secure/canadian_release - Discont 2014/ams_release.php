<?
/*
*	Adam Walter, Sep 2013.
*
*	AMS-release page for Canadian Program.
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

	$sql = "SELECT NVL(TO_CHAR(AMS_STATUS, 'MM/DD/YYYY HH:MI:SS AM'), 'NONE') THE_RELEASE, AMS_COMMENTS
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$current_date = ociresult($short_term_data, "THE_RELEASE");
	$current_comments = ociresult($short_term_data, "AMS_COMMENTS");

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">All Cargo AMS-Release</font><font size="3" face="Verdana" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?><br>&nbsp;&nbsp;&nbsp;PoW Vessel#:&nbsp;&nbsp;&nbsp;<? echo $vessel; ?><br>&nbsp;&nbsp;&nbsp;Container:&nbsp;&nbsp;&nbsp;<? echo $cont; ?><br>&nbsp;&nbsp;&nbsp;BoL:&nbsp;&nbsp;&nbsp;<? echo $bol; ?>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($ams_auth == "Y"){
//		echo "<font color=\"#FF0000\">This user is not authorized for this page.  Cancelling script.</font>";
//		exit;
//	}
?>	
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="canadian_scoreboard_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Current Release Time:</font></td>
		<td><font size="2" face="Verdana"><? echo $current_date; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Comments<br>(Required):</font></td>
		<td><input type="text" name="comments" size="100" maxlength="200" value="<? echo $current_comments; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Set AMS Release Time and Comments">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Remove AMS Release"><hr></td>
	</tr>
</form>
</table>
<?
	}
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana"><b>Release History</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Username</b></font></td>
		<td><font size="2" face="Verdana"><b>Date/Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Typed Comments</b></font></td>
		<td><font size="2" face="Verdana"><b>Description of Action</b></font></td>
	</tr>
<?
	$sql = "SELECT USER_ID, ACTIVITY_DESC, COMMENTS, TO_CHAR(ACTIVITY_DATE, 'MM/DD/YYYY HH:MI:SS AM') THE_DATE
			FROM CANADIAN_RELEASE_HISTORY
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'
				AND RELEASE_SECTION = 'AMS'
			ORDER BY ACTIVITY_DATE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "USER_ID"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "THE_DATE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "COMMENTS"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "ACTIVITY_DESC"); ?></font></td>
	</tr>
<?
	}
?>
</table>