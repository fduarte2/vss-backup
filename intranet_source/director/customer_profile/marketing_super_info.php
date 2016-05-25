<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Customer Profile Page (Marketing)";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

  $submit = $HTTP_POST_VARS['submit'];
  $new_super_info_id = $HTTP_POST_VARS['new_super_info_id'];
  $new_super_info_time = $HTTP_POST_VARS['new_super_info_time'];
  $new_super_info_budget = $HTTP_POST_VARS['new_super_info_budget'];
  $new_super_info_contrib = $HTTP_POST_VARS['new_super_info_contrib'];
  $modify_super_info_id = $HTTP_POST_VARS['modify_super_info_id'];
  $modify_super_info_date = $HTTP_POST_VARS['modify_super_info_date'];
  $modify_super_info_budget = $HTTP_POST_VARS['modify_super_info_budget'];
  $modify_super_info_contrib = $HTTP_POST_VARS['modify_super_info_contrib'];

  if($submit == "Add Information" && $new_super_info_id != "" && $new_super_info_time != ""){
	  $sql = "INSERT INTO MARKETING_SUPERCUSTOMER_INFO (SUPERCUSTOMER_ID, BUDGET_VALUE, CONTRIBUTION_VALUE, FOR_DATE) VALUES ('".$new_super_info_id."', '".$new_super_info_budget."', '".$new_super_info_contrib."', to_date('".$new_super_info_time."', 'MM/DD/YYYY'))";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($submit == "Modify Information" && $modify_super_info_id != "" && $modify_super_info_date != ""){
	  $sql = "UPDATE MARKETING_SUPERCUSTOMER_INFO SET BUDGET_VALUE = '".$modify_super_info_budget."', CONTRIBUTION_VALUE = '".$modify_super_info_contrib."' WHERE SUPERCUSTOMER_ID = '".$modify_super_info_id."' AND FOR_DATE = to_date('".$modify_super_info_date."', 'MM/DD/YYYY')";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Supercustomer Information
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="add_stuff" action="marketing_super_info.php" method="post">
		<td width="1%">&nbsp;</td>
		<td colspan="3"><font size="3" face="Verdana">Add new information:</font></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Supercustomer:  </font></td>
		<td><select name="new_super_info_id">
			<option value=""> </option>
<?
	$sql = "SELECT * FROM MARKETING_SUPERCUSTOMERS ORDER BY SUPERCUSTOMER";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
											<option value="<? echo $row['SUPERCUSTOMER_ID']; ?>"><? echo $row['SUPERCUSTOMER']; ?></option>
<?
	}
?>
							</select></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Date:  </font></td>
		<td><input type="text" name="new_super_info_time" size="20" maxlength="10">&nbsp;&nbsp;<font size="2" face="Verdana">(MM/DD/YYYY Please)</font></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Budgeted Revenue:  </font></td>
		<td><input type="text" name="new_super_info_budget" size="20" maxlength="13"></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Contribution:  </font></td>
		<td><input type="text" name="new_super_info_contrib" size="20" maxlength="13"></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td colspan="2"><input type="submit" name="submit" value="Add Information"></td>
	</form>
	</tr>
	<tr>
		<td colspan="4" align="center">&nbsp;<br><b>--OR--</b><br>&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="3"><font size="3" face="Verdana">Modify information:</font></td>
	</tr>
	<tr>
	<form name="modify_change_super" action="marketing_super_info.php" method="post">
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Supercustomer:  </font></td>
		<td><select name="modify_super_info_id" onchange="document.modify_change_super.submit(this.form)">
			<option value=""> </option>
<?
	$sql = "SELECT * FROM MARKETING_SUPERCUSTOMERS ORDER BY SUPERCUSTOMER";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
											<option value="<? echo $row['SUPERCUSTOMER_ID']; ?>" <? if($row['SUPERCUSTOMER_ID'] == $modify_super_info_id) { ?> selected <? } ?>><? echo $row['SUPERCUSTOMER']; ?></option>
<?
	}
?>
							</select></td>
	</form>
	</tr>
	<tr>
	<form name="modify_change_date" action="marketing_super_info.php" method="post">
	<input type="hidden" name="modify_super_info_id" value="<? echo $modify_super_info_id; ?>">
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Date:  </font></td>
		<td><select name="modify_super_info_date" onchange="document.modify_change_date.submit(this.form)">
					<option value=""> </option>
<?
	$sql = "SELECT to_char(FOR_DATE, 'MM/DD/YYYY') THE_DATE FROM MARKETING_SUPERCUSTOMER_INFO WHERE SUPERCUSTOMER_ID = '".$modify_super_info_id."' ORDER BY FOR_DATE DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
			<option value="<? echo $row['THE_DATE']; ?>" <? if($row['THE_DATE'] == $modify_super_info_date) { ?> selected <? } ?>><? echo $row['THE_DATE']; ?></option>
<?
	}
?>
				</select></td>
	</form>
	</tr>
	<tr>
	<form name="modify_super_stuff" action="marketing_super_info.php" method="post">
	<input type="hidden" name="modify_super_info_id" value="<? echo $modify_super_info_id; ?>">
	<input type="hidden" name="modify_super_info_date" value="<? echo $modify_super_info_date; ?>">
<?
	$sql = "SELECT * FROM MARKETING_SUPERCUSTOMER_INFO WHERE SUPERCUSTOMER_ID = '".$modify_super_info_id."' AND FOR_DATE = to_date('".$modify_super_info_date."', 'MM/DD/YYYY')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Budgeted Revenue:  </font></td>
		<td><input type="text" name="modify_super_info_budget" size="20" maxlength="13" value="<? echo $row['BUDGET_VALUE']; ?>"></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td width="30%"><font size="2" face="Verdana">Contribution:  </font></td>
		<td><input type="text" name="modify_super_info_contrib" size="20" maxlength="13" value="<? echo $row['CONTRIBUTION_VALUE']; ?>"></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td colspan="2"><input type="submit" name="submit" value="Modify Information"></td>
	</form>
	</tr>
</table>

<? include("pow_footer.php"); ?>
