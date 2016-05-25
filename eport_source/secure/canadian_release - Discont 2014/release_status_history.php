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
	$type = $HTTP_GET_VARS['type'];

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$ves_name = ociresult($short_term_data, "VESSEL_NAME");

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">All Cargo <? echo $type; ?>-Release History
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="15%"><font size="3" face="Verdana">Vessel:</font></td>
		<td><font size="3" face="Verdana"><? echo $ves_name; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="3" face="Verdana">Container#:</font></td>
		<td><font size="3" face="Verdana"><? echo $cont; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="3" face="Verdana">BoL:</font></td>
		<td><font size="3" face="Verdana"><? echo $bol; ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</table>

<table  border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Username</b></font></td>
		<td><font size="2" face="Verdana"><b>Date/Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Typed Comments</b></font></td>
		<td><font size="2" face="Verdana"><b>Description of Action</b></font></td>
	</tr>
<?
	$sql = "SELECT USER_ID, ACTIVITY_DESC, COMMENTS, TO_CHAR(ACTIVITY_DATE, 'MM/DD/YYYY HH:MI:AM') THE_DATE
			FROM CANADIAN_RELEASE_HISTORY
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'
				AND RELEASE_SECTION = '".$type."'
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