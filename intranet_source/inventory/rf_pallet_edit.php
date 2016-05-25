<?
/* Adam Walter, April 2007
*  This program allows for the editing of an entry in Cargo Tracking of RF;
*  Specifically, the Commodity, Vessel, BoL, and
*  CONTAINER_ID + CARGO_DESCRIPTION (which make up the BNI mark) fields
**********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory - RF Pallet adjustment";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }



//  $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if(!$conn){
    echo "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    exit;
  }
  $cursor = ora_open($conn);


	$pallet_id = $HTTP_POST_VARS['pallet_id'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$new_comm = $HTTP_POST_VARS['new_comm'];
	$new_bol = $HTTP_POST_VARS['new_bol'];
	$new_mark = $HTTP_POST_VARS['new_mark'];
	$new_pallet_id = $HTTP_POST_VARS['new_pallet_id'];
	$new_code = $HTTP_POST_VARS['new_code'];
	$numrows = 0;
	$qty_in_house = 0;
	$bad_comm = 0;

	if($submit == "Edit Pallet"){
		$sql = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$new_comm."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "UPDATE CARGO_TRACKING 
					SET COMMODITY_CODE = '".$new_comm."', 
						BOL = '".$new_bol."', 
						PALLET_ID = '".$new_pallet_id."', 
						CARGO_DESCRIPTION = '".$new_mark."',
						BATCH_ID = '".$new_code."'
					WHERE PALLET_ID = '".$pallet_id."' 
					AND ARRIVAL_NUM = '".$vessel."'";
			ora_parse($cursor, $sql);
			$success = ora_exec($cursor);
		} else {
			$bad_comm = 1;
		}
	}


	// this is done in case PALLET_ID was just modified
	if($new_pallet_id != ""){
		$pallet_id = $new_pallet_id;
	}


	if($submit == "Retrieve Pallet" || $submit == "Edit Pallet"){
		$sql = "SELECT * FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel."' AND PALLET_ID = '".$pallet_id."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$commodity = $row['COMMODITY_CODE'];
			$bol = $row['BOL'];
			$mark = $row['CARGO_DESCRIPTION'];
			$numrows = 1;
			$qty_in_house = $row['QTY_IN_HOUSE'];
			$code = $row['BATCH_ID'];
		} else {
			$commodity = "";
			$bol = "";
			$mark = "";
			$code = "";
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <font size="5" face="Verdana" color="#0066CC">RF Argentine Juice Pallet Modification</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
	if($success == TRUE){
?>
	<tr>
		<td colspan="5" align="center"><font size="3" face="Verdana" color="#ff0f00">Pallet Updated</font></td>
	</tr>
<?
	} elseif ($bad_comm == 1){
?>
	<tr>
		<td colspan="5" align="center"><font size="3" face="Verdana" color="#ff0f00">No change made; Commodity not in RF system.</font></td>
	</tr>
<?
	}
?>
<form name="choose_pallet" action="rf_pallet_edit.php" method="post">
	<tr>
		<td colspan="6" align="center"><font size="3" face="Verdana">Pallet#:&nbsp;&nbsp;<input name="pallet_id" value="<? echo $pallet_id; ?>" type="text" size="30" maxlength="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vessel#&nbsp;&nbsp;<input name="vessel" value="<? echo $vessel; ?>" type="text" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Retrieve Pallet"></td>
	</tr>
</form>
<?
	if($submit == "Retrieve Pallet" || $submit == "Edit Pallet"){
		if($numrows == 0){
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana">This Pallet ID does not exist for this Vessel.</font></td>
	</tr>
<?
		} else {
?>
	<form name="edit_pallet" action="rf_pallet_edit.php" method="post">
	<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<input type="hidden" name="pallet_id" value="<? echo $pallet_id; ?>">
	<tr>
		<td colspan="6"><hr>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><font size="2" face="Verdana">Commodity:</font></td>
		<td><font size="2" face="Verdana">BoL:</font></td>
		<td><font size="2" face="Verdana">Pallet ID:</font></td>
		<td><font size="2" face="Verdana">Mark / Lot:</font></td>
		<td><font size="2" face="Verdana">Code:</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Current:</font></td>
		<td><font size="2" face="Verdana"><? echo $commodity; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $bol; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $pallet_id; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $mark; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $code; ?></font></td>
	</tr>
<?
			if($qty_in_house > 0){
?>
	<tr>
		<td><font size="2" face="Verdana">New:</font></td>
		<td><input name="new_comm" type="text" value="<? echo $commodity; ?>" size="5" maxlength="4"></td>
		<td><input name="new_bol" type="text" value="<? echo $bol; ?>" size="10" maxlength="15"></td>
		<td><input name="new_pallet_id" type="text" value="<? echo $pallet_id; ?>" size="30" maxlength="30"></td>
		<td><input name="new_mark" type="text" value="<? echo $mark; ?>" size="50" maxlength="50"></td>
		<td><input name="new_code" type="text" value="<? echo $code; ?>" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Edit Pallet"></td>
	</tr>
<?
			} else {
?>
	<tr>
		<td colspan="6" align="center"><font size="2" face="Verdana">This pallet has already been shipped out.  No changes can be made.</font></td>
	</tr>
<?
			}
?>
	</form>
<?
		}
	}
?>
</table>
<?
include("pow_footer.php");
?>
