<?
/*
*  Adam Walter, Mar 2015.
*
*	Review screen for 350 uploads pending.
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "350 Review";
  $area_type = "CLR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$ves = $HTTP_POST_VARS['ves'];

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">CLR-EDI 350's Pending 
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="350_pending.php" method="post" name="the_upload">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Select a Vessel:</b>&nbsp;&nbsp;</td>
		<td><select name="ves"><option value="">Please Select</option>
								<option value="nomatch" <? if($ves == "nomatch"){?> selected <? } ?>>Unmatched EDIs</option>
<?
	$sql = "SELECT VP.LR_NUM, VESSEL_NAME, CLAM.LLOYD_NUM, CLAM.VOYAGE_NUM
			FROM CLR_LLOYD_ARRIVAL_MAP CLAM, VESSEL_PROFILE VP
			WHERE CLAM.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM)
			ORDER BY LR_NUM DESC";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
								<option value="<? echo ociresult($short_term_data, "LR_NUM"); ?>"<? if($ves == ociresult($short_term_data, "LR_NUM")){?> selected <?}?>>
									<? echo ociresult($short_term_data, "LR_NUM")."-".ociresult($short_term_data, "VESSEL_NAME")." (LLOYD#".ociresult($short_term_data, "LLOYD_NUM")." / VOYAGE#".ociresult($short_term_data, "VOYAGE_NUM").")"; ?>
								</option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $ves != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Container</b></font></td>
		<td><font size="2" face="Verdana"><b>BoL</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI-Vessel Name</b></font></td>
		<td><font size="2" face="Verdana"><b>Lloyd#</b></font></td>
		<td><font size="2" face="Verdana"><b>Voyage#</b></font></td>
		<td><font size="2" face="Verdana"><b>QTY</b></font></td>
		<td><font size="2" face="Verdana"><b>EDI-Code</b></font></td>
	</tr>
<?
		if($ves == "nomatch"){
			$extra_sql = " AND (LLOYD_NUM, VOYAGE_NUM) NOT IN (SELECT LLOYD_NUM, VOYAGE_NUM FROM CLR_LLOYD_ARRIVAL_MAP) ";
		} else {
			$extra_sql = " AND (LLOYD_NUM, VOYAGE_NUM) IN (SELECT LLOYD_NUM, VOYAGE_NUM FROM CLR_LLOYD_ARRIVAL_MAP WHERE ARRIVAL_NUM = '".$ves."') ";
		}

		$sql = "SELECT CONTAINER_NUM, BOL_EQUIV, LLOYD_NUM, VOYAGE_NUM, VESNAME, QTY, EDI_DATE_TIME, EDI_CODE
				FROM CLR_AMS_350_EDI
				WHERE MOVE_TO_CLR_HIST IS NULL
					".$extra_sql."
					AND LLOYD_NUM NOT IN (SELECT LLOYD_NUM FROM CLR_IGNORE_LLOYD)
				ORDER BY CONTAINER_NUM, BOL_EQUIV, EDI_DATE_TIME";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana"><b>No Unresolved EDI's found for selection.</b></font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CONTAINER_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "BOL_EQUIV"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "VESNAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "LLOYD_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "VOYAGE_NUM"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "QTY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "EDI_CODE"); ?></font></td>
	</tr>
<?
			} while(ocifetch($short_term_data));
		}
?>
</table>
<?
	}
	include("pow_footer.php");
