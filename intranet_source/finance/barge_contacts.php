<?
/*
*		Adam Walter, Oct 2015.
*
*		Page toadd contacts to the barge notification screen
*
*********************************************************************************/

	// All POW files need this session file included
	include("pow_session.php");

	// Define some vars for the skeleton page
	$title = "Barge Entry";
	$area_type = "ROOT";

	// Provides header / leftnav
	include("pow_header.php");
/*	if($access_denied){
		printf("Access Denied from INVE system");
		include("pow_footer.php");
		exit;
	}
*/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI"); echo "<font color=\"#000000\" size=\"1\">BNI LIVE DB</font><br>";
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST"); echo "<font color=\"#FF0000\" size=\"5\">BNI TEST DB</font><br>";
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$company = str_replace("'", "`", $HTTP_POST_VARS['company']);

	if($submit == "Create New Company"){
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM VESSEL_NOTIFY_COMPANIES
				WHERE COMPANY_NAME = '".$company."'";
		$count_company = ociparse($bniconn, $sql);
		ociexecute($count_company);
		ocifetch($count_company);
		if(ociresult($count_company, "THE_COUNT") <= 0){
			$sql = "INSERT INTO VESSEL_NOTIFY_COMPANIES
						(COMPANY_NAME)
					VALUES
						('".$company."')";
			$insert = ociparse($bniconn, $sql);
			ociexecute($insert);
		}
	}
	if($submit == "Add Contact"){
		$new_contact = str_replace("'", "`", $HTTP_POST_VARS['new_contact']);
		$new_phone = str_replace("'", "`", $HTTP_POST_VARS['new_phone']);

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM VESSEL_NOTIFY_COMPANY_CONTACTS
				WHERE COMPANY_NAME = '".$company."'
					AND CONTACT_NAME = '".$new_contact."'
					AND TELEPHONE = '".$new_phone."'";
		$count_contact = ociparse($bniconn, $sql);
		ociexecute($count_contact);
		ocifetch($count_contact);
		if(ociresult($count_contact, "THE_COUNT") <= 0){
			$sql = "INSERT INTO VESSEL_NOTIFY_COMPANY_CONTACTS
						(COMPANY_NAME,
						CONTACT_NAME,
						TELEPHONE)
					VALUES
						('".$company."',
						'".$new_contact."',
						'".$new_phone."')";
			$insert = ociparse($bniconn, $sql);
			ociexecute($insert);
		}
	}
	if($submit == "Save Edits"){
		$new_contact = str_replace("'", "`", $HTTP_POST_VARS['new_contact']);
		$new_phone = str_replace("'", "`", $HTTP_POST_VARS['new_phone']);
		$old_name = str_replace("'", "`", $HTTP_POST_VARS['old_name']);
		$old_tele = str_replace("'", "`", $HTTP_POST_VARS['old_tele']);
		$max_rows = $HTTP_POST_VARS['rownum'];

		for($i = 0; $i <= $max_rows; $i++){
			if($new_contact[$i] != $old_name[$i] || $new_phone[$i] != $old_tele[$i]){
				$sql = "UPDATE VESSEL_NOTIFY_COMPANY_CONTACTS
						SET CONTACT_NAME = '".$new_contact[$i]."',
							TELEPHONE = '".$new_phone[$i]."'
						WHERE COMPANY_NAME = '".$company."'
							AND CONTACT_NAME = '".$old_name[$i]."'
							AND TELEPHONE = '".$old_tele[$i]."'";
				$update = ociparse($bniconn, $sql);
				ociexecute($update); 
			}
		}
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barge Contacts<br></font><font size="3" face="Verdana" color="#0066CC"><a href="vessel_arrival_notify.php">Return to Notify Screen</a>
</font>	    
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="contact_new" action="barge_contacts.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>New Company:</b></font></td>
		<td><input type="text" name="company" size="50" maxlength="50"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create New Company"></td>
	</tr>
</form>
	<tr>
		<td colspan="2"><b>---OR---</b></td>
	</tr>
<form name="contact_select" action="barge_contacts.php" method="post">
	<tr>
		<td><font size="2" face="Verdana">Select Company:  </font></td>
		<td><select name="company" onchange="document.contact_select.submit(this.form)"><option value="">---</option>
<?
	$sql = "SELECT COMPANY_NAME FROM VESSEL_NOTIFY_COMPANIES ORDER BY COMPANY_NAME";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "COMPANY_NAME"); ?>"<? if(ociresult($short_term_data, "COMPANY_NAME") == $company){?> selected <?}?>><? echo ociresult($short_term_data, "COMPANY_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
</form>
</table>
<?
	if($company != ""){
?>
<table border="0" cellpadding="4" cellspacing="0">
<form name="new_contact" action="barge_contacts.php" method="post">
<input type="hidden" name="company" value="<? echo $company; ?>">
	<tr>
		<td><font size="2" face="Verdana"><b>New Contact</b></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Contact Name</font></td>
		<td><input type="text" name="new_contact" size="50" maxlength="50"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Contact Phone</font></td>
		<td><input type="text" name="new_phone" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Add Contact"></td>
	</tr>
</form>
<br><br>
<table border="1" cellpadding="4" cellspacing="0">
<form name="edit_grid" action="barge_contacts.php" method="post">
<input type="hidden" name="company" value="<? echo $company; ?>">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Contact List for <? echo $company; ?></b></font></td>
	</tr>
<?
		$rownum = -1;
		$sql = "SELECT *
				FROM VESSEL_NOTIFY_COMPANY_CONTACTS
				WHERE COMPANY_NAME = '".$company."'";
		$line_data = ociparse($bniconn, $sql);
		ociexecute($line_data);
		if(!ocifetch($line_data)){
?>
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>None</b></font></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td><font size="2" face="Verdana">Contact Name</font></td>
		<td><font size="2" face="Verdana">Contact Phone</font></td>
	</tr>
<?
			do {
				$rownum++;
?>
<input type="hidden" name="old_name[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "CONTACT_NAME"); ?>">
<input type="hidden" name="old_tele[<? echo $rownum; ?>]" value="<? echo ociresult($line_data, "TELEPHONE"); ?>">
	<tr>
		<td><input type="text" name="new_contact[<? echo $rownum; ?>]" size="50" maxlength="50" value="<? echo ociresult($line_data, "CONTACT_NAME"); ?>"></td>
		<td><input type="text" name="new_phone[<? echo $rownum; ?>]" size="20" maxlength="20" value="<? echo ociresult($line_data, "TELEPHONE"); ?>"></td>
	</tr>
<?
			} while(ocifetch($line_data));
?>
<input type="hidden" name="rownum" value="<? echo $rownum; ?>">
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Save Edits">
	</tr>
<?
		}
?>
</form>
</table>
<?
	}
	include("pow_footer.php");
