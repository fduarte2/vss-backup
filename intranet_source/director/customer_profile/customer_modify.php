<?
/* Adam Walter, October 2006.
*  It should be noted that the logic on this page 
*  is a bit wierd, but it works, since customer
*  numbers are unique in the table, so I do NOT
*  need to pass customer name with it.
****************************************************/
  
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

  $customer_id = $HTTP_POST_VARS['customer_id'];
  if($customer_id == ""){
	  $customer_id = $HTTP_GET_VARS['customer_id'];
  }

  $customer_measurement = $HTTP_POST_VARS['customer_measurement'];

  $remove_customer_number = $HTTP_GET_VARS['remove_customer_number'];
  $new_customer_number = $HTTP_POST_VARS['new_customer_number'];
  $new_commodity_number = $HTTP_POST_VARS['new_commodity_number'];
  $remove_commodity_number = $HTTP_GET_VARS['remove_commodity_number'];
  $remove_commodity_group = $HTTP_GET_VARS['remove_commodity_group'];
  $submit = $HTTP_POST_VARS['submit'];

  if($remove_customer_number != ""){
	  $sql = "DELETE FROM MARKETING_GROUP_CUSTOMERS WHERE CUSTOMER_NUMBER = '".$remove_customer_number."' AND MARKETING_ID = '".$customer_id."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($remove_commodity_number != "" && $remove_commodity_group != ""){
	  $sql = "DELETE FROM MARKETING_GROUP_COMMODITIES WHERE MARKETING_ID = '".$remove_commodity_group."' AND COMMODITY = '".$remove_commodity_number."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($submit == "Add Customer") {
	  $sql = "INSERT INTO MARKETING_GROUP_CUSTOMERS (MARKETING_ID, CUSTOMER_NUMBER) VALUES ('".$customer_id."', '".$new_customer_number."')";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($submit == "Add Commodity") {
	  $sql = "INSERT INTO MARKETING_GROUP_COMMODITIES (MARKETING_ID, COMMODITY) VALUES ('".$customer_id."', '".$new_commodity_number."')";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($customer_measurement != "" && $customer_id != ""){
	  $sql = "UPDATE MARKETING_CUSTOMER_GROUPS SET BY_MEASUREMENT = '".$customer_measurement."' WHERE MARKETING_ID = '".$customer_id."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Customer Profile Main Page
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
    <tr>
	<form name="customer_name" action="customer_modify.php" method="post">
		<td width="100%" colspan="2" align="center"><select name="customer_id" onchange="document.customer_name.submit(this.form)">
										<option value="" selected>Select a Customer</option>
<?
	$sql = "SELECT * FROM MARKETING_CUSTOMER_GROUPS ORDER BY MARKETING_GROUP";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if($row['MARKETING_ID'] == $customer_id) { $display_customer = $row['MARKETING_ID']; }
?>
								<option value="<? echo $row['MARKETING_ID']; ?>"><? echo $row['MARKETING_GROUP']; ?></option> 
<?
	}
?>
						</select></td>
	</form>
	</tr>
	<tr>
<? 
	$sql = "SELECT MARKETING_GROUP FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$customer_name = $row['MARKETING_GROUP'];
?>
		<td width="100%" colspan="2" align="center"><font size="2" face="Verdana">&nbsp;&nbsp;&nbsp;&nbsp;Customer Selected:  <? if($customer_id != "") { echo $customer_name; } else { echo "None"; } ?></font></td>
	</tr>

<?
	if($customer_id != ""){
?>
	<tr valign="top">
	<form name="customer_measurement" action="customer_modify.php" method="post">
	<input type="hidden" name="customer_id" value="<? echo $customer_id; ?>">
		<td colspan="2" width="100%" valign="top" align="center">Budget Measurement:  <select name="customer_measurement" onchange="document.customer_measurement.submit(this.form)">
												<option value="">Select Budget Measurement</option>
<?
	$sql = "SELECT BY_TONS FROM MARKETING_CUSTOMER_GROUPS WHERE MARKETING_ID = '".$customer_id."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
												<option value="TONS" <? if($row['BY_MEASUREMENT'] == "TONS") { ?> selected <? } ?>>Tons</option>
												<option value="OTHER" <? if($row['BY_MEASUREMENT'] == "OTHER") { ?> selected <? } ?>>Other</option>
												</select>
		</td>
	</form>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<br>&nbsp;</td>
	</tr>
<?
// and now we split the page in half, left side is customer numbers, right is commodities.  Customer first....
?>
	<tr valign="top">
	<td width="50%" valign="top">
	<table border="0" valign="top" width="100%" cellpadding="4" cellspacing="0">
		<tr>
			<td colspan="2"><font size="3" face="Verdana">Customer Number(s) for <? echo $customer_name; ?>:</font></td>
		</tr>
<?
		$sql = "SELECT * FROM MARKETING_GROUP_CUSTOMERS MGC, CUSTOMER_PROFILE CP WHERE MGC.CUSTOMER_NUMBER = CP.CUSTOMER_ID AND MARKETING_ID = '".$customer_id."' ORDER BY CUSTOMER_NUMBER";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
		<tr>
			<td width="75%"><font size="2" face="Verdana"><? echo $row['CUSTOMER_NAME']; ?></font></td>
			<td width="25%"><font size="2" face="Verdana"><a href="customer_modify.php?remove_customer_number=<? echo $row['CUSTOMER_NUMBER']; ?>&customer_id=<? echo $customer_id; ?>">Remove</a></font></td>
		</tr>
<?
		}
?>
		<tr>
			<td colspan="2">&nbsp;<br>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><font size="3" face="Verdana">Add new Customer:</font></td>
		</tr>
		<tr>
		<form name="new_customer" action="customer_modify.php" method="post">
		<input type="hidden" name="customer_id" value="<? echo $customer_id; ?>">
			<td colspan="2">&nbsp;&nbsp;&nbsp;<select name="new_customer_number">
<?
		$sql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID NOT IN (SELECT CUSTOMER_NUMBER FROM MARKETING_GROUP_CUSTOMERS WHERE MARKETING_ID = '".$customer_id."') ORDER BY CUSTOMER_ID";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
						<option value="<? echo $row['CUSTOMER_ID']; ?>"><? echo $row['CUSTOMER_NAME']; ?></option>
<?
		}
?>
					</select></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Add Customer"></td>
		</form>
		</tr>
	</table></td>
<?
	// now the commodities...
?>
	<td width="50%" valign="top">
	<table border="0" valign="top" width="100%" cellpadding="4" cellspacing="0">
		<tr>
			<td colspan="2"><font size="3" face="Verdana">Commodity Number(s) for <? echo $customer_name; ?>:</font></td>
		</tr>
<?
		$sql = "SELECT * FROM MARKETING_GROUP_COMMODITIES MGC, COMMODITY_PROFILE CP WHERE MGC.COMMODITY = CP.COMMODITY_CODE AND MARKETING_ID = '".$customer_id."' ORDER BY COMMODITY_CODE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
		<tr>
			<td width="75%"><font size="2" face="Verdana"><? echo $row['COMMODITY_NAME']; ?></font></td>
			<td width="25%"><font size="2" face="Verdana"><a href="customer_modify.php?remove_commodity_number=<? echo $row['COMMODITY_CODE']; ?>&remove_commodity_group=<? echo $customer_id; ?>&customer_id=<? echo $customer_id; ?>">Remove</a></font></td>
		</tr>
<?
		}
?>
		<tr>
			<td colspan="2">&nbsp;<br>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><font size="3" face="Verdana">Add new Commodity:</font></td>
		</tr>
		<tr>
		<form name="new_commodity" action="customer_modify.php" method="post">
		<input type="hidden" name="customer_id" value="<? echo $customer_id; ?>">
			<td colspan="2">&nbsp;&nbsp;&nbsp;<select name="new_commodity_number">
<?
		$sql = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
?>
						<option value="<? echo $row['COMMODITY_CODE']; ?>"><? echo $row['COMMODITY_NAME']; ?></option>
<?
		}
?>
					</select></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Add Commodity"></td>
		</form>
		</tr>
	</table></td>
	</tr>
<?
	}
?>
</table>

<? include("pow_footer.php"); ?>