<?

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Library";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Library system");
    include("pow_footer.php");
    exit;
  }

  $user = $userdata['username'];


	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}


	$submit = $HTTP_POST_VARS['submit'];
	$keywords = SafeInput($HTTP_POST_VARS['keywords']);
	$desc = SafeInput($HTTP_POST_VARS['desc']);
	$efective = SafeInput($HTTP_POST_VARS['efective']);
	$expire = SafeInput($HTTP_POST_VARS['expire']);
	$active = SafeInput($HTTP_POST_VARS['active']);
	$key_id = $HTTP_POST_VARS['key_id'];
	if($key_id == ""){
		$key_id = $HTTP_GET_VARS['key_id'];
	}

	if($submit == "Save" && $key_id != ""){
		$sql = "UPDATE DOCUMENT_STORE
					SET KEYWORDS = '".$keywords."',
					DESCRIPTION = '".$desc."',
					EFFECTIVE_DATE = TO_DATE('".$efective."', 'MM/DD/YYYY'),
					EXPIRATION_DATE = TO_DATE('".$expire."', 'MM/DD/YYYY'),
					ACTIVE = '".$active."'
				WHERE DOCUMENT_ID = '".$key_id."'";
		$files = ociparse($bniconn, $sql);
		ociexecute($files);

		echo "<font color=\"#0000FF\">File details changed.</font><br>";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Library File Description(s) Edit
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="retrieve" action="library_edit_file.php" method="post">
	<tr>
		<td width="10%"><font size="2" face="Verdana">File:</font></td>
		<td><select name="key_id"><option value="">Select a File</option>
<?
	$sql = "SELECT DOCUMENT_ID, DOCUMENT_NAME
			FROM DOCUMENT_STORE
			ORDER BY DOCUMENT_ID DESC";
	$files = ociparse($bniconn, $sql);
	ociexecute($files);
	while(ocifetch($files)){
?>
					<option value="<? echo ociresult($files, "DOCUMENT_ID"); ?>"<? if(ociresult($files, "DOCUMENT_ID") == $key_id){?> selected <?}?>>
							<? echo ociresult($files, "DOCUMENT_ID")." - ".ociresult($files, "DOCUMENT_NAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve File"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
<?
	if($key_id != ""){
		$sql = "SELECT DS.*, TO_CHAR(EFFECTIVE_DATE, 'MM/DD/YYYY') THE_EFFECTIVE, TO_CHAR(EXPIRATION_DATE, 'MM/DD/YYYY') THE_EXPIRE
				FROM DOCUMENT_STORE DS
				WHERE DOCUMENT_ID = '".$key_id."'";
		$short_term_data = ociparse($bniconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
?>
<form name="file_submit" action="library_edit_file.php" method="post">
<input name="key_id" type="hidden" value="<? echo $key_id; ?>">
	<tr>
		<td width="10%"><font size="2" face="Verdana">Keyword(s):</font></td>
		<td><input type="text" name="keywords" size="60" maxlength="60" value="<? echo ociresult($short_term_data, "KEYWORDS"); ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Description:</font></td>
		<td><input type="text" name="desc" size="100" maxlength="200" value="<? echo ociresult($short_term_data, "DESCRIPTION"); ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Effective Date:</font></td>
		<td><input type="text" name="efective" size="10" maxlength="10" value="<? echo ociresult($short_term_data, "THE_EFFECTIVE"); ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Expiry Date:</font></td>
		<td><input type="text" name="expire" size="10" maxlength="10" value="<? echo ociresult($short_term_data, "THE_EXPIRE"); ?>"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Active:</font></td>
		<td><input type="text" name="active" size="1" maxlength="1" value="<? echo ociresult($short_term_data, "ACTIVE"); ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Save"></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");












function SafeInput($string){
	$return = strtoupper($string);
	$return = str_replace("'", "`", $return);
	$return = str_replace("\\", "", $return);

	return $return;
}

