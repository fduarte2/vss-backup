<?
/*
*	Adam Walter, Nov 26, 2008
*	Page "un-imports" an RF-imported vessel,
*	Provided nothing on it has been received yet.
*****************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF-Import";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }

 
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if (!$conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Remove Vessel"){
		$sql = "DELETE FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND DATE_RECEIVED IS NULL";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);

		echo "<font color=\"#FF0000\">Vessel $vessel Import Removed</font>";
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">RF Import-Removal Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<form name="sel_vessel" action="rf_import_remove.php" method="post">
	<tr>
		<td align="center"><select name="vessel" onchange="document.sel_vessel.submit(this.form)"><option value="">Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE 
			WHERE SHIP_PREFIX = 'CHILEAN'
				AND TO_CHAR(LR_NUM) IN 
					(SELECT ARRIVAL_NUM FROM CARGO_TRACKING)
				AND TO_CHAR(LR_NUM) NOT IN 
					(SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING WHERE DATE_RECEIVED IS NOT NULL AND RECEIVING_TYPE = 'S') 
			ORDER BY LR_NUM DESC";
	$ora_success = ora_parse($cursor, $sql);
	$ora_success = ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
							</select></td>
	</tr>
	</form>
<?
	if($vessel != "" && $submit == ""){
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."'";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$row_count = $row['THE_COUNT'];
?>
	<form name="del_vessel" action="rf_import_remove.php" method="post">
	<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td align="center"><font size="2" face="Verdana">Do you wish to remove the <? echo $row_count; ?> records for vessel number <? echo $vessel; ?>?</font></td>
	</tr>
	<tr>
		<td align="center"><input name="submit" type="submit" value="Remove Vessel"></td>
	</tr>
	</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>