<?
/* April 2007.  Adam Walter
*  This page was desigend to give Inventory (specifically Marty)
*  The ability to "back out" an incorrectly imported
*  Manifest.  If there are entries present
*  In Cargo_Activity or Billing tables, however,
*  The page will not function.
*************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Pallet Arrival Correction";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

  // only marty gets to do this
  if($user != "martym"){
    printf("This page is only authorized to be used by Martin McLaughlin");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_Owner@BNI.DEV", "SAG_DEV");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $cursor = ora_open($conn);


	$vessel = $HTTP_POST_VARS['vessel'];
	$check = $HTTP_POST_VARS['check'];
	$submit = $HTTP_POST_VARS['submit'];

	if($check == "Retrieve Vessel"){
		if($vessel != ""){
			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM CARGO_MANIFEST WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$CM_count = $row['THE_COUNT'];

			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM CARGO_TRACKING WHERE LOT_NUM IN (SELECT CONTAINER_NUM FROM CARGO_MANIFEST WHERE LR_NUM = '".$vessel."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$CT_count = $row['THE_COUNT'];

			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM BNI_DUMMY_WITHDRAWAL WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$BDW_count = $row['THE_COUNT'];

			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM CARGO_ACTIVITY WHERE LOT_NUM IN (SELECT CONTAINER_NUM FROM CARGO_MANIFEST WHERE LR_NUM = '".$vessel."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$CA_count = $row['THE_COUNT'];

			$sql = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM BILLING WHERE LR_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$BIL_count = $row['THE_COUNT'];
		}
	}

	if($submit == "Remove Vessel" && $vessel != ""){
		$sql = "DELETE FROM CARGO_TRACKING WHERE LOT_NUM IN (SELECT CONTAINER_NUM FROM CARGO_MANIFEST WHERE LR_NUM = '".$vessel."')";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "DELETE FROM BNI_DUMMY_WITHDRAWAL WHERE LR_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);

		$sql = "DELETE FROM CARGO_MANIFEST WHERE LR_NUM = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <font size="5" face="Verdana" color="#0066CC">Imported Vessel Removal</font>
         <hr>
      </td>
   </tr>
	<tr>
      <td width="1%">&nbsp;</td>
		<td align="center"><font size="3" face="Verdana" color="ff0f00">Disclaimer:  This page is intended to allow for accidental / incorrect vessel imports to be backed out.<BR>To maintain data integrity, this page will NOT work if a vessel has either had activity run against its cargo,<BR> or has already been billed in some way.<br>In those cases, TS will need to be called to intervene.</font>
		<hr></td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="1" cellspacing="0">
<form name="whosawhatchit" action="import_vessel_removal.php" method="post">
	<tr>
		<td align="center"><font size="2" face="Verdana">Vessel #:  <input type="text" name="vessel" size="10" value="<? echo $vessel; ?>"></font></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="check" value="Retrieve Vessel"></td>
	</tr>
</form>
<?
	if($check == "Retrieve Vessel"){ // if first button is pressed
		if($vessel == ""){ // forgot to enter vessel
?>
	<tr>
		<td><hr><font size="2" face="Verdana">Please Enter a Vessel.</font></td>
	</tr>
<?
		} else { // vessel entered
			if($CA_count > 0 || $BIL_count > 0){ // vessel not able to be altered
?>
	<tr>
		<td align="center"><font size="2" face="Verdana">This vessel has already had activity and/or bills run against it.  Please contact TS if you still need it removed.</font></td>
	</tr>
<?
			} else { // give final confirmation
?>
	<tr>
		<td align="center">
			<table width="40%" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<td align="left">Cargo Manifest Entries:</td>
					<td align="right"><? echo $CM_count; ?></td>
				</tr>
				<tr>
					<td align="left">Cargo Tracking Entries:</td>
					<td align="right"><? echo $CT_count; ?></td>
				</tr>
				<tr>
					<td align="left">Pending Dummy Entries:</td>
					<td align="right"><? echo $BDW_count; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="Verdana">Are you sure you wish to Remove this vessel?</font></td>
	</tr>
<form name="anotherform" action="import_vessel_removal.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td align="center"><input type="submit" name="submit" value="Remove Vessel"></td>
	</tr>
</form>
<?
			}
		}
	} elseif($submit == "Remove Vessel"){ // confirmation given, repeating to screen
?>
	<tr>
		<td align="center"><font size="3" face="Verdana" color="ff0f00">Vessel <? echo $vessel; ?> Import Revoked.</font><br><font size="2" face="Verdana">You may now re-import the vessel.</font></td>
	</tr>
<?
	}
?>
</table>
<? include("pow_footer.php"); ?>