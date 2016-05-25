<?
/*
*	Adam Walter, Oct 2012
*
*	Inventory's default method for "stopping billing" is to just set
*	a "next billing date" sufficiently far int he future.
*
*	This email will notify INVE of any cargo that has a next bill
*	date 5 or mroe years in the future so they don't forget about it
************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$vessel = $HTTP_GET_VARS['vessel'];
	$comm = $HTTP_GET_VARS['comm'];
//	$commname = $HTTP_GET_VARS['commname'];

	$sql = "SELECT COUNT(*) THE_COUNT FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".$vessel."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '".$vessel."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$display = "Vessel: ".ociresult($stid, "VESSEL_NAME");
	} else {
		$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$vessel."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$display = "Customer: ".ociresult($stid, "CUSTOMER_NAME");
	}

	$submit = $HTTP_POST_VARS['submit'];
	if($submit != ""){
		$vessel = $HTTP_POST_VARS['vessel'];
		$comm = $HTTP_POST_VARS['comm'];
		$commname = $HTTP_POST_VARS['commname'];
		$notes = $HTTP_POST_VARS['notes'];

		$sql = "SELECT COUNT(*) THE_COUNT FROM RF_STORAGE_RECON_REPORT_NOTES
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND COMMODITY_CODE = '".$comm."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") >= 1){
			$sql = "UPDATE RF_STORAGE_RECON_REPORT_NOTES
					SET NOTE_DESCRIPTION = '".$notes."',
						ENTERED_BY = '".$user."',
						NOTE_DATE = SYSDATE
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND COMMODITY_CODE = '".$comm."'";
		} else {
			$sql = "INSERT INTO RF_STORAGE_RECON_REPORT_NOTES
						(ARRIVAL_NUM,
						COMMODITY_CODE,
						NOTE_DATE,
						NOTE_DESCRIPTION,
						ENTERED_BY)
					VALUES
						('".$vessel."',
						'".$comm."',
						SYSDATE,
						'".$notes."',
						'".$user."')";
		}
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
	}

	$sql = "SELECT NOTE_DESCRIPTION FROM RF_STORAGE_RECON_REPORT_NOTES
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND COMMODITY_CODE = '".$comm."'";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	if(ocifetch($stid)){
		$notes = ociresult($stid, "NOTE_DESCRIPTION");
	} else {
		$notes = "";
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Storage Reconciliation Notes
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($submit == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="storage_recon_notes.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel;?>">
<!--<input type="hidden" name="commname" value="<? echo $commname;?>"> !-->
<input type="hidden" name="comm" value="<? echo $comm;?>">
	<tr>
		<td align="left"><font size="2" face="Verdana"><? echo $display; ?> <? echo $vessel; ?></font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Commodity:  <? echo $comm." - ".$commname; ?></font></td>
	</tr>
	<tr>
		<td align="left"><input type="text" name="notes" size="50" maxlength="200" value="<? echo $notes; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Save Note"><hr></td>
	</tr>
</form>
</table>
<?
	} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="left"><font size="3" face="Verdana">Note Saved.</font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">You can safely close this window.</font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">You can view the new note on the Reconciliation Report by refreshing the other page.</font></td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>