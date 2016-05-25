<?

  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Add Customer Page (Marketing)";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }

  $new_customer = $HTTP_POST_VARS['new_customer'];
  $new_by_measurement = $HTTP_POST_VARS['new_by_measurement'];
  $new_supercustomer = $HTTP_POST_VARS['new_supercustomer'];
  $submit = $HTTP_POST_VARS['submit'];
  $modified_customer_name = $HTTP_POST_VARS['modified_customer_name'];
  $modified_by_measurement = $HTTP_POST_VARS['modified_by_measurement'];
  $modified_supercustomer = $HTTP_POST_VARS['modified_supercustomer'];
  $modified_customer_number = $HTTP_POST_VARS['modified_customer_number'];

  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);

  if($new_customer != "" && $new_by_measurement != "" && $submit == "Add Customer Group"){
	  $sql = "SELECT MAX(MARKETING_ID) MAX_NUM FROM MARKETING_CUSTOMER_GROUPS";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  $max = $row['MAX_NUM'];
	  if($max < 1 || $max == ""){
		  $max = 0;
	  }
	  $max++;

	  $sql = "INSERT INTO MARKETING_CUSTOMER_GROUPS (MARKETING_ID, BY_MEASUREMENT, MARKETING_GROUP, SUPERCUSTOMER_ID) VALUES ('".$max."', '".$new_by_measurement."', '".$new_customer."', '".$new_supercustomer."')";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);

	  $sql = "INSERT INTO MARKETING_CUSTOMER_PROFILE (MARKETING_ID, CUSTOMER_NAME) VALUES ('".$max."', '".$new_customer."')";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($modified_customer_number != "" && $modified_by_measurement != "" && $modified_customer_name != "" && $submit == "Modify Customer Group"){
	  $sql = "UPDATE MARKETING_CUSTOMER_GROUPS SET MARKETING_GROUP = '".$modified_customer_name."', BY_MEASUREMENT = '".$modified_by_measurement."', SUPERCUSTOMER_ID = '".$modified_supercustomer."' WHERE MARKETING_ID = '".$modified_customer_number."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);

	  $sql = "UPDATE MARKETING_CUSTOMER_PROFILE SET CUSTOMER_NAME = '".$modified_customer_name."' WHERE MARKETING_ID = '".$modified_customer_number."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }




?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add a new Customer
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
    <tr>
	<form name="pointless" action="customer_add.php" method="post">
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Customer to add:  <input name="new_customer" type="text" size="20" maxlength="50"></font></td>
	</tr>
    <tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td><font size="3" face="Verdana">Measurement Value:  </font><select name="new_by_measurement">
																	<option value="TONS">TONS</option>
																	<option value="OTHER">OTHER</option>
																	</select></td>
	</tr>
    <tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td><font size="3" face="Verdana">Supercustomer Group:  </font><select name="new_supercustomer">
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
		<td><input type="submit" name="submit" value="Add Customer Group"></td>
	</form>
	</tr>
	<tr>
		<td colspan="3" align="center">&nbsp;<br><b>--OR--</b><br>&nbsp;</td>
	</tr>
    <tr>
	<form name="not_pointless" action="customer_add.php" method="post">
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">Customer to Modify:  </font><select name="modified_customer_number" onchange="document.not_pointless.submit(this.form)"><option value=""> </option>
<?
	$sql = "SELECT * FROM MARKETING_CUSTOMER_GROUPS ORDER BY MARKETING_GROUP";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
													<option value="<? echo $row['MARKETING_ID']; ?>" <? if($row['MARKETING_ID'] == $modified_customer_number) { ?> selected <? } ?>><? echo $row['MARKETING_GROUP']; ?></option>
<?
	}
?>
										</select></td>
	</form>
	</tr>
    <tr>
	<form name="also_pointless" action="customer_add.php" method="post">
	<input type="hidden" name="modified_customer_number" value="<? echo $modified_customer_number; ?>">
<?
	$sql = "SELECT * FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$modified_customer_number."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>

		<td width="1%">&nbsp;</td>
		<td colspan="2"><font size="3" face="Verdana">New Name:  <input name="modified_customer_name" type="text" size="20" maxlength="50" value="<? echo $row['MARKETING_GROUP']; ?>"></font></td>
	</tr>
    <tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td><font size="3" face="Verdana">Measurement Value:  </font><select name="modified_by_measurement">
												<option value="TONS" <? if($row['BY_MEASUREMENT'] == "TONS"){ ?> selected <? } ?>>TONS</option>
												<option value="OTHER" <? if($row['BY_MEASUREMENT'] == "OTHER"){ ?> selected <? } ?>>OTHER</option>
												</select></td>
	</tr>
    <tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td><font size="3" face="Verdana">Supercustomer Group:  </font><select name="modified_supercustomer">
<?
	$sql = "SELECT * FROM MARKETING_SUPERCUSTOMERS ORDER BY SUPERCUSTOMER";
	ora_parse($cursor2, $sql);
	ora_exec($cursor2);
	while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
													<option value="<? echo $row2['SUPERCUSTOMER_ID']; ?>" <? if($row2['SUPERCUSTOMER_ID'] == $row['SUPERCUSTOMER_ID']){ ?> selected <? } ?>><? echo $row2['SUPERCUSTOMER']; ?></option>
<?
	}
?>
										</select></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td><input type="submit" name="submit" value="Modify Customer Group"></td>
	</form>
	</tr>
</table>

<? include("pow_footer.php"); ?>
