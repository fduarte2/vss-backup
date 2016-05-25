<?
/*
*		Adam Walter, July 2014.
*
*		Trucker "main segment" of Cargo Load Release
*********************************************************************************/


  
	include("cargo_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$vessel = $HTTP_GET_VARS['vessel'];
	$bol = $HTTP_GET_VARS['bol'];
	$consign = $HTTP_GET_VARS['consign'];
	$cont = $HTTP_GET_VARS['cont'];
	$order = $HTTP_GET_VARS['order'];

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE
			WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$ves_data = ociparse($rfconn, $sql);
	ociexecute($ves_data);
	if(!ocifetch($ves_data)){
		$vesname = $vessel." - Unknown";
	} else {
		$vesname = ociresult($ves_data, "VESSEL_NAME");
	}

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">AMS-Release History
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="ID_select" action="AMS_EDI_hist_index.php" method="post">
	<tr>
		<td width="15%"><font size="2" face="Verdana"><b>Current Release Time:</b></font></td>
		<td><font size="2" face="Verdana"><? echo "put time here"; ?></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana"><b>Comments:</b></font></td>
		<td align="left"><input type="text" name="comments" size="50" maxlength="50"></td>
	</tr>
		
	<tr>
		<td><input type="submit" name="submit" value="Set AMS Release Time and Save Comments"></td>
		<td><input type="submit" name="submit" value="Remove AMS Release"><BR><HR></td>
	</tr>
</form>
</table>

<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>UserName</b></font>
		<td><font size="2" face="Verdana"><b>AMS EDI Date/Time</b></font>
		<td><font size="2" face="Verdana"><b>Date/Time Applied</b></font>
		<td><font size="2" face="Verdana"><b>Comments</b></font>
		<td><font size="2" face="Verdana"><b>Type</b></font>
		<td><font size="2" face="Verdana"><b>Description of Action</b></font>
	</tr>
<?
	$sql = "SELECT * FROM CARGO_AMS_RELEASE
			ORDER BY PROCESSED_TIME";
	$hist_data = ociparse($rfconn, $sql);
	ociexecute($hist_data);
	if(!ocifetch($hist_data)){
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana"><b>No Release Activity Found.</b></font></td>
	</tr>
<?
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "VESSEL_NAME"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "VESSEL_NAME"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "VESSEL_NAME"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "VESSEL_NAME"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "VESSEL_NAME"); ?></font>
		<td><font size="2" face="Verdana"><? echo ociresult($hist_data, "VESSEL_NAME"); ?></font>
	</tr>
<?
		} while(ocifetch($hist_data));
	}
?>
</table>
