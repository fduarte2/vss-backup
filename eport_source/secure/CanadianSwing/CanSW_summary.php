<?
/*
*	Adam Walter, Feb 2010.
*
*	This page is a one-stop shop for Chilean Data.
*	Displays pallets and commoditites (all),
*	Date and qty received (only for those received),
*	And date and quantity shipped (only for those with activity records)
*
*	This used to be on S-16 (and a backup copy can still be located there,
*	Probably), but as it was a nice report for all to use,
*	It migrated to Eport.
*************************************************************************/

/*
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
 */
//	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$cont = $HTTP_POST_VARS['cont'];
	$submit = $HTTP_POST_VARS['submit'];


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Canadian Swingload Summary Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="CanSW_summary_index.php" method="post">
	<tr>
		<td width="10%"><font size="3" face="Verdana">Vessel:</font></td>
		<td><select name="vessel">
				<option value="">All</option>
<?
		$sql = "SELECT LR_NUM, LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE SHIP_PREFIX = 'CHILEAN' ORDER BY LR_NUM DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td width="10%"><font size="3" face="Verdana">Container:</font></td>
		<td><input type="text" name="cont" size="20" maxlength="20" value="<? echo $cont; ?>"><font size="2" face="Verdana">(optional)</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Report"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Container</b></font></td>
		<td><font size="2" face="Verdana"><b>CBP</b></font></td>
		<td><font size="2" face="Verdana"><b>USDA</b></font></td>
		<td><font size="2" face="Verdana"><b>FDA</b></font></td>
		<td><font size="2" face="Verdana"><b>Line Holds</b></font></td>
		<td><font size="2" face="Verdana"><b>OHL</b></font></td>
		<td><font size="2" face="Verdana"><b>Final Status</b></font></td>
	</tr>
<?
		$sql = "SELECT * FROM CARGO_TRACKING WHERE ARRIVAL_NUM = 'WHATEVER'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="8" align="center"><font size="2" face="Verdana">No Cargo to Display.</font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
		<td><font size="2" face="Verdana">---</font></td>
	</tr>
<?
			} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
</table>
<?
	}
//	include("pow_footer.php");
?>