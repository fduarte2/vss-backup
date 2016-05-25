<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Marketing Custoemr Profile Page";
  $area_type = "ROOT"; /// ??? no marketing area :(

  // Provides header / leftnav
  include("pow_header.php");

/*  As there is no marketing subdivision of the intranet, have to disable this... for now.
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
*/

  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

//  $customer_number = $HTTP_POST_VARS['customer_number'];

  $customer_name = $HTTP_POST_VARS['customer_name'];

  $misc_file_desc = $HTTP_POST_VARS['misc_file_desc'];

  $new_customer_name = $HTTP_POST_VARS['new_customer_name'];
  $new_corporate_name = $HTTP_POST_VARS['new_corporate_name'];
  $new_address_one = $HTTP_POST_VARS['new_address_one'];
  $new_address_two = $HTTP_POST_VARS['new_address_two'];
  $new_city = $HTTP_POST_VARS['new_city'];
  $new_state = $HTTP_POST_VARS['new_state'];
  $new_zip = $HTTP_POST_VARS['new_zip'];
  $new_contact_one = $HTTP_POST_VARS['new_contact_one'];
  $new_contact_two = $HTTP_POST_VARS['new_contact_two'];
  $new_contact_three = $HTTP_POST_VARS['new_contact_three'];
  $new_contact_four = $HTTP_POST_VARS['new_contact_four'];
  $new_contract_eff = $HTTP_POST_VARS['new_contract_eff'];
  $new_termination = $HTTP_POST_VARS['new_termination'];
  $new_notice_period = $HTTP_POST_VARS['new_notice_period'];
  $new_date_relation_started = $HTTP_POST_VARS['new_date_relation_started'];
//  $new_contractual_rates = $HTTP_POST_VARS['new_contractual_rates'];
//  $new_lease_extract = $HTTP_POST_VARS['new_lease_extract'];
  $new_commodity = $HTTP_POST_VARS['new_commodity'];
  $new_special_one = $HTTP_POST_VARS['new_special_one'];
  $new_special_two = $HTTP_POST_VARS['new_special_two'];
  $new_special_three = $HTTP_POST_VARS['new_special_three'];
  $new_comments = $HTTP_POST_VARS['new_comments'];

  $submit = $HTTP_POST_VARS['submit'];

  if($submit == "Save Changes"){
/*	  $testsql = "SELECT * FROM MARKETING_CUSTOMER_PROFILE WHERE CUSTOMER_NAME = '".$customer_name."'";
	  ora_parse($cursor, $testsql);
	  ora_exec($cursor);
	  // now we check if there is an entry for this customer int he table.  if yes, update.  if no, insert.
	  if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){ */
		  $sql = "UPDATE MARKETING_CUSTOMER_PROFILE SET
					CORPORATE_NAME = '".$new_corporate_name."',
					ADDRESS_ONE = '".$new_address_one."',
					ADDRESS_TWO = '".$new_address_two."',
					CITY = '".$new_city."',
					STATE = '".$new_state."',
					ZIP = '".$new_zip."',
					CONTACT_ONE = '".$new_contact_one."',
					CONTACT_TWO = '".$new_contact_two."',
					CONTACT_THREE = '".$new_contact_three."',
					CONTACT_FOUR = '".$new_contact_four."',
					CONTRACT_EFF = to_date('".$new_contract_eff."', 'MM/DD/YYYY'),
					TERMINATION = to_date('".$new_termination."', 'MM/DD/YYYY'),
					NOTICE_PERIOD = to_date('".$new_notice_period."', 'MM/DD/YYYY'),
					DATE_RELATION_STARTED = to_date('".$new_date_relation_started."', 'MM/DD/YYYY'),
					COMMODITY = '".$new_commodity."',
					SPECIAL_ONE = '".$new_special_one."',
					SPECIAL_TWO = '".$new_special_two."',
					SPECIAL_THREE = '".$new_special_three."',
					COMMENTS = '".$new_comments."'
					WHERE CUSTOMER_NAME = '".$customer_name."'";
/*	  } else { 
		  $sql = "INSERT INTO MARKETING_CUSTOMER_PROFILE 
					(\"CUSTOMER_NAME\",
					 \"CORPORATE_NAME\",
					 \"ADDRESS_ONE\",
					 \"ADDRESS_TWO\",
					 \"CITY\",
					 \"STATE\",
					 \"ZIP\",
					 \"CONTACT_ONE\",
					 \"CONTACT_TWO\",
					 \"CONTACT_THREE\",
					 \"CONTACT_FOUR\",
					 \"CONTRACT_EFF\",
					 \"TERMINATION\",
					 \"NOTICE_PERIOD\",
					 \"DATE_RELATION_STARTED\",
					 \"COMMODITY\",
					 \"SPECIAL_ONE\",
					 \"SPECIAL_TWO\",
					 \"SPECIAL_THREE\",
					 \"COMMENTS\")
		  VALUES (
					'".$new_customer_name."',
					'".$new_corporate_name."',
					'".$new_address_one."',
					'".$new_address_two."',
					'".$new_city."',
					'".$new_state."',
					'".$new_zip."',
					'".$new_contact_one."',
					'".$new_contact_two."',
					'".$new_contact_three."',
					'".$new_contact_four."',
					to_date('".$new_contract_eff."', 'MM/DD/YYYY'),
					to_date('".$new_termination."', 'MM/DD/YYYY'),
					to_date('".$new_notice_period."', 'MM/DD/YYYY'),
					to_date('".$new_date_relation_started."', 'MM/DD/YYYY'),
					'".$new_commodity."',
					'".$new_special_one."',
					'".$new_special_two."',
					'".$new_special_three."',
					'".$new_comments."')";
	  } */
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
  }

  if($submit == "Upload Terminal Agreement Extract" && $HTTP_POST_FILES['lease_extract']['name'] != "" && isset($customer_name)){
	  $target_path = "uploaded_stuff/";

	  $target_path = $target_path . basename($HTTP_POST_FILES['lease_extract']['name']);

	  if(move_uploaded_file($HTTP_POST_FILES['lease_extract']['tmp_name'], $target_path)){
		  $sql = "UPDATE MARKETING_CUSTOMER_PROFILE SET TERMINAL_AGREEMENT_EXTRACT = '".$target_path."' WHERE CUSTOMER_NAME = '".$customer_name."'";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
	  } else {
		  echo "Error on file upload.  Please contact TS";
		  exit;
	  }
  }

  if($submit == "Upload Contract" && $HTTP_POST_FILES['uploaded_rate']['name'] != "" && isset($customer_name)){
	  $target_path = "uploaded_stuff/";

	  $target_path = $target_path . basename($HTTP_POST_FILES['uploaded_rate']['name']);

	  if(move_uploaded_file($HTTP_POST_FILES['uploaded_rate']['tmp_name'], $target_path)){
		  $sql = "UPDATE MARKETING_CUSTOMER_PROFILE SET CONTRACTUAL_RATES = '".$target_path."' WHERE CUSTOMER_NAME = '".$customer_name."'";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
	  } else {
		  echo "Error on file upload.  Please contact TS";
		  exit;
	  }
  }

  if($submit == "Upload Terminal Agreement" && $HTTP_POST_FILES['terminal_agreement']['name'] != "" && isset($customer_name)){
	  $target_path = "uploaded_stuff/";

	  $target_path = $target_path . basename($HTTP_POST_FILES['terminal_agreement']['name']);

	  if(move_uploaded_file($HTTP_POST_FILES['terminal_agreement']['tmp_name'], $target_path)){
		  $sql = "UPDATE MARKETING_CUSTOMER_PROFILE SET TERMINAL_AGREEMENT = '".$target_path."' WHERE CUSTOMER_NAME = '".$customer_name."'";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
	  } else {
		  echo "Error on file upload.  Please contact TS";
		  exit;
	  }
  }

  if($submit == "Upload Additional Customer File" && $HTTP_POST_FILES['add_customer_file']['name'] != "" && isset($customer_name) && $misc_file_desc != ""){
	  $target_path = "uploaded_stuff/".$customer_name."/";

	  if(!is_dir($target_path)){
		  $cmd = "mkdir uploaded_stuff/".$customer_name." > /dev/null 2>&1";
		  system($cmd);
	  }

	  $target_path = $target_path . basename($HTTP_POST_FILES['add_customer_file']['name']);

	  if(move_uploaded_file($HTTP_POST_FILES['add_customer_file']['tmp_name'], $target_path)){
		  $sql = "INSERT INTO MARKETING_CONTACT_HISTORY (CUSTOMER_NAME, FILE_NAME, FILE_DESCRIPTION) VALUES ('".$customer_name."', 	'".$target_path."', '".$misc_file_desc."')";
		  ora_parse($cursor, $sql);
		  ora_exec($cursor);
	  } else {
		  echo "Error on file upload.  Please contact TS";
		  exit;
	  }
  }


  if(isset($customer_name)){
	  $sql = "SELECT * FROM MARKETING_CUSTOMER_PROFILE WHERE CUSTOMER_NAME = '".$customer_name."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//		  $customer_name = $row['CUSTOMER_NAME'];
		  $corporate_name = $row['CORPORATE_NAME'];
		  $address_one = $row['ADDRESS_ONE'];
		  $address_two = $row['ADDRESS_TWO'];
		  $city = $row['CITY'];
		  $state = $row['STATE'];
		  $zip = $row['ZIP'];
		  $contact_one = $row['CONTACT_ONE'];
		  $contact_two = $row['CONTACT_TWO'];
		  $contact_three = $row['CONTACT_THREE'];
		  $contact_four = $row['CONTACT_FOUR'];
		  $contract_eff = $row['CONTRACT_EFF'];
		  $termination = $row['TERMINATION'];
		  $notice_period = $row['NOTICE_PERIOD'];
		  $date_relation_started = $row['DATE_RELATION_STARTED'];
		  $contractual_rates = $row['CONTRACTUAL_RATES'];
		  $terminal_agreement_extract = $row['TERMINAL_AGREEMENT_EXTRACT'];
		  $terminal_agreement = $row['TERMINAL_AGREEMENT'];
		  $commodity = $row['COMMODITY'];
		  $special_one = $row['SPECIAL_ONE'];
		  $special_two = $row['SPECIAL_TWO'];
		  $special_three = $row['SPECIAL_THREE'];
		  $comments = $row['COMMENTS'];
	  }
	  $sql = "SELECT to_char(CONTRACT_EFF, 'MM/DD/YYYY') EFF, to_char(TERMINATION, 'MM/DD/YYYY') TERM, to_char(NOTICE_PERIOD, 'MM/DD/YYYY') NOTICE, to_char(DATE_RELATION_STARTED, 'MM/DD/YYYY') STARTED FROM MARKETING_CUSTOMER_PROFILE WHERE CUSTOMER_NAME = '".$customer_name."'";
	  ora_parse($cursor, $sql);
	  ora_exec($cursor);
	  while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		  $contract_eff = $row['EFF'];
		  $termination = $row['TERM'];
		  $notice_period = $row['NOTICE'];
		  $date_relation_started = $row['STARTED'];
	  }
  }

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Customer Profiles</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="customer_profile.php" method="post" name="customer_id">
   <tr>
      <td width="1%">&nbsp;</td>
	  <td width="25%"><font size="3" face="Verdana">Customer Group: </font></td>
	  <td><select name="customer_name" onchange="document.customer_id.submit(this.form)">
				<option value="" selected>Select Customer</option>
<?
	$sql = "SELECT * FROM MARKETING_CUSTOMER_GROUPS ORDER BY MARKETING_GROUP";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		if($row['MARKETING_GROUP'] == $customer_name) { $display_customer = $row['MARKETING_GROUP']; }
?>
		<option value="<? echo $row['MARKETING_GROUP']; ?>"><? echo $row['MARKETING_GROUP']; ?></option> 
<?
	}
?>
			</select></td>
	</tr>
	</form>
	<tr>
		<td colspan="3"><font size="2" face="Verdana">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer Group:  <? if($customer_name == ""){ echo "None Selected"; } else { ?><a href="marketing_printout.php?customer_name=<? echo $customer_name; ?>" target="marketing_printout.php?customer_name=<? echo $customer_name; ?>"><? echo $display_customer; ?></a> <? } ?></font></td>
	</tr>
	<form enctype="multipart/form-data" action="customer_profile.php" method="post" name="customer_info">
<!--	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Customer Name:  </font></td>
		<td><input type="text" name="new_customer_name" size="20" maxlength="50" value="<? echo $customer_name; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Corporate Name:  </font></td>
		<td><input type="text" name="new_corporate_name" size="20" maxlength="50" value="<? echo $corporate_name; ?>"></td>
	</tr> !-->
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Address:  </font></td>
		<td><input type="text" name="new_address_one" size="20" maxlength="50" value="<? echo $address_one; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Address (cont.):  </font></td>
		<td><input type="text" name="new_address_two" size="20" maxlength="50" value="<? echo $address_two; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">City:  </font></td>
		<td><input type="text" name="new_city" size="20" maxlength="20" value="<? echo $city; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">State:  </font></td>
		<td><input type="text" name="new_state" size="2" maxlength="2" value="<? echo $state; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Zip:  </font></td>
		<td><input type="text" name="new_zip" size="10" maxlength="10" value="<? echo $zip; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Contact 1:  </font></td>
		<td><input type="text" name="new_contact_one" size="20" maxlength="20" value="<? echo $contact_one; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Contact 2:  </font></td>
		<td><input type="text" name="new_contact_two" size="20" maxlength="20" value="<? echo $contact_two; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Contact 3:  </font></td>
		<td><input type="text" name="new_contact_three" size="20" maxlength="20" value="<? echo $contact_three; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Contact 4:  </font></td>
		<td><input type="text" name="new_contact_four" size="20" maxlength="20" value="<? echo $contact_four; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Contract Eff Date:  </font></td>
		<td><input type="text" name="new_contract_eff" size="10" maxlength="10" value="<? echo $contract_eff; ?>">  <font size="2" face="Verdana">(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Contract Termination:  </font></td>
		<td><input type="text" name="new_termination" size="10" maxlength="10" value="<? echo $termination; ?>">  <font size="2" face="Verdana">(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Notice Period:  </font></td>
		<td><input type="text" name="new_notice_period" size="10" maxlength="10" value="<? echo $notice_period; ?>">  <font size="2" face="Verdana">(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Date Relationship Started:  </font></td>
		<td><input type="text" name="new_date_relation_started" size="10" maxlength="10" value="<? echo $date_relation_started; ?>">  <font size="2" face="Verdana">(MM/DD/YYYY format)</font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
<? if ($contractual_rates != ""){ ?>
		<td width="25%"><font size="3" face="Verdana"><a href="<? echo $contractual_rates; ?>" target="<? echo $contractual_rates; ?>">Contractual Rates</a></font></td>
<? } else { ?>
		<td width="25%"><font size="3" face="Verdana">Contractual Rates  </font></td>
<? } ?>
<!--		<td><input type="text" name="new_contractual_rates" size="20" maxlength="50" value="<? echo $contractual_rates; ?>"></td> !-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
<? if ($terminal_agreement_extract != ""){ ?>
		<td width="25%"><font size="3" face="Verdana"><a href="<? echo $terminal_agreement_extract; ?>" target="<? echo $terminal_agreement_extract; ?>">Terminal Agreement Extract</a></font></td>
<? } else { ?>
		<td width="25%"><font size="3" face="Verdana">Terminal Agreement Extract  </font></td>
<? } ?>
<!--		<td><input type="text" name="new_lease_extract" size="20" maxlength="50" value="<? echo $lease_extract; ?>"></td> !-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
<? if ($terminal_agreement != ""){ ?>
		<td width="25%"><font size="3" face="Verdana"><a href="<? echo $terminal_agreement; ?>" target="<? echo $terminal_agreement; ?>">Terminal Agreement</a></font></td>
<? } else { ?>
		<td width="25%"><font size="3" face="Verdana">Terminal Agreement  </font></td>
<? } ?>
<!--		<td><input type="text" name="new_contractual_rates" size="20" maxlength="50" value="<? echo $contractual_rates; ?>"></td> !-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Commodity:  </font></td>
		<td><input type="text" name="new_commodity" size="20" maxlength="20" value="<? echo $commodity; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Special (1):  </font></td>
		<td><input type="text" name="new_special_one" size="20" maxlength="50" value="<? echo $special_one; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Special (2):  </font></td>
		<td><input type="text" name="new_special_two" size="20" maxlength="50" value="<? echo $special_two; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Special (3):  </font></td>
		<td><input type="text" name="new_special_three" size="20" maxlength="50" value="<? echo $special_three; ?>"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="25%"><font size="3" face="Verdana">Comments:  </font></td>
		<td><textarea name="new_comments" cols="50" rows="4"><? echo $comments; ?></textarea></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Changes"></td>
	</tr>
	<input type="hidden" name="customer_name" value="<? echo $customer_name; ?>">
	</form>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
<? if($customer_name != ""){ ?>
	<tr>
	<form enctype="multipart/form-data" action="customer_profile.php" method="post" name="contractual_rates">
	<input type="hidden" name="customer_name" value="<? echo $customer_name; ?>">
		<td width="1%">&nbsp;</td>
		<td colspan="2">Upload new Contractual Rate document for <? echo $customer_name; ?>:<br><input type="file" name="uploaded_rate">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Upload Contract"></td>
	</form>
	</tr>
	<tr>
	<form enctype="multipart/form-data" action="customer_profile.php" method="post" name="terminal_agreement">
	<input type="hidden" name="customer_name" value="<? echo $customer_name; ?>">
		<td width="1%">&nbsp;</td>
		<td colspan="2">Upload new Terminal Agreement for <? echo $customer_name; ?>:<br><input type="file" name="terminal_agreement">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Upload Terminal Agreement"></td>
	</form>
	</tr>
	<tr>
	<form enctype="multipart/form-data" action="customer_profile.php" method="post" name="terminal_agreement_extract">
	<input type="hidden" name="customer_name" value="<? echo $customer_name; ?>">
		<td width="1%">&nbsp;</td>
		<td colspan="2">Upload new Terminal Agreement Extract for <? echo $customer_name; ?>:<br><input type="file" name="lease_extract">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Upload Terminal Agreement Extract"></td>
	</form>
	</tr>
<? } ?>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3"><font size="4" face="Verdana">Associated Files</td>
	</tr>
<?
// this next part shows any uploaded files that are not part of the above set, I.E. correspondance, or
// absolutely anything else Marketing chooses to upload as such
	$check_dir = "uploaded_stuff/".$customer_name;
	if(!is_dir($check_dir) || $customer_name == ""){
?>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2"><font face="Verdana" size="3">No associated Files to display</td>
	</tr>
<?
	} else {
		$sql = "SELECT * FROM MARKETING_CONTACT_HISTORY WHERE CUSTOMER_NAME = '".$customer_name."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td width="1%">&nbsp;</td>
		<td><a href="<? echo $row['FILE_NAME']; ?>" target="<? echo $row['FILE_NAME']; ?>"><? echo $row['FILE_DESCRIPTION']; ?></a>
	</tr>
<?	
		}
	}
	if($customer_name != ""){
?>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
	<form enctype="multipart/form-data" action="customer_profile.php" method="post" name="add_customer_file">
	<input type="hidden" name="customer_name" value="<? echo $customer_name; ?>">
		<td width="1%">&nbsp;</td>
		<td colspan="2">&nbsp;&nbsp;&nbsp;Upload misc. file for <? echo $customer_name; ?>:<br>&nbsp;&nbsp;&nbsp;<input type="file" name="add_customer_file">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Upload Additional Customer File"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2">&nbsp;&nbsp;&nbsp;Description of File: <input type="text" name="misc_file_desc" size="20" maxlength="100"><td>
	</tr>
	</form>
	</tr>
<?
	}
?>
</table>
<?
	include("pow_footer.php");
?>