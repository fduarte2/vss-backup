<?
/*
*  Adam Walter, Nov 2014.
*
*	INV will be able to alter EDI codes for CLR
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Argen Juice Barcode Correction";
  $area_type = "CLR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($rfconn));
		exit;
	}
	$pagename = "CLR_EDI_codes";
	include("page_security.php");
//	$security_allowance = PageSecurityCheck($user, $pagename, "test");
	$security_allowance = PageSecurityCheck($user, $pagename, "");


	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Add/Change Code"){
		$edi_code =  str_replace("'", "`", $HTTP_POST_VARS['edi_code']);
		$action = $HTTP_POST_VARS['action'];
		$remove_code =  str_replace("'", "`", $HTTP_POST_VARS['remove_code']);

		$good_to_go = ValidateEntry($edi_code, $action, $remove_code, $rfconn);

		if($good_to_go != ""){
			echo "<font color=\"#FF0000\">Could not save for the following reason(s):<br>".$good_to_go."</font>";
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM CANADIAN_AMSEDI_CODES
					WHERE EDI_CODE = '".$edi_code."'";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			ocifetch($short_term_data);
			if(ociresult($short_term_data, "THE_COUNT") >= 1){
				$sql = "UPDATE CANADIAN_AMSEDI_CODES
						SET ACTION = '".$action."',
							CODE_REMOVED = '".$remove_code."',
							USERNAME = '".$user."'
						WHERE EDI_CODE = '".$edi_code."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);

				$sql = "UPDATE CLR_AMS_RELEASE
						SET EDI_CODE_TYPE = '".$action."',
							ACTION_TYPE = '".$action."'
						WHERE APPLIED_TO_CLR IS NULL
							AND EDI_CODE = '".$edi_code."'";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
			} else {
				$sql = "INSERT INTO CANADIAN_AMSEDI_CODES
							(EDI_CODE,
							ACTION,
							CODE_REMOVED,
							USERNAME)
						VALUES
							('".$edi_code."',
							'".$action."',
							'".$remove_code."',
							'".$user."')";
				$short_term_data = ociparse($rfconn, $sql);
				ociexecute($short_term_data);
			}

			echo "<font color=\"#0000FF\">Code ".$edi_code." Accepted.<br></font>";
		}
	}

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">CLR-EDI 350 Codes 
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>
<?
	if(strpos($security_allowance, "M") !== false){
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="EDI_codes.php" method="post" name="the_upload">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Enter New or Existing Code:</b>&nbsp;&nbsp;</td>
		<td><input type="text" name="edi_code" size="3" maxlength="2"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Choose New Action Type:</b>&nbsp;&nbsp;</td>
		<td><select name="action"><option value="">Please Select</option>
											<option value="IGNORE">IGNORE</option>
											<option value="HOLD">HOLD</option>
											<option value="REMOVE HOLD">REMOVE HOLD</option>
											<option value="RELEASE">RELEASE</option>
											<option value="REMOVE RELEASE">REMOVE RELEASE</option>
						</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Hold Code to Cancel:</b>&nbsp;&nbsp;</td>
		<td><input type="text" name="remove_code" size="3" maxlength="2">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">(Needed only if "Remove Hold" is selected above)</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Add/Change Code"><hr></td>
	</tr>
</form>
</table>
<?
	}
?>
<table border="0" cellpadding="4" cellspacing="0">
<!--	<tr>
		<td colspan="4" align="center"><font size="3" face="Verdana">Current EDI code list</font></td>
	</tr> !-->
	<tr>
		<td valign="top">
			<table border="1" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><font size="3" face="Verdana">To Release (Note:  Releases do NOT remove HOLDS)</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Code</b></font></td>
					<!--<td><font size="2" face="Verdana"><b>Remove HOLD Code</b></font></td> !-->
					<td><font size="2" face="Verdana"><b>Last Edited By</b></font></td>
				</tr>
<?
	$sql = "SELECT * 
			FROM CANADIAN_AMSEDI_CODES CAD
			WHERE ACTION = 'RELEASE'
			ORDER BY EDI_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "EDI_CODE"); ?></font></td>
					<!--<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CODE_REMOVED"); ?>&nbsp;</font></td> !-->
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "USERNAME"); ?>&nbsp;</font></td>
				</tr>
<?
	}
?>
			</table>
		</td>
		<td valign="top">
			<table border="1" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><font size="3" face="Verdana">To Remove-Release</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Code</b></font></td>
					<!--<td><font size="2" face="Verdana"><b>Remove HOLD Code</b></font></td> !-->
					<td><font size="2" face="Verdana"><b>Last Edited By</b></font></td>
				</tr>
<?
	$sql = "SELECT * 
			FROM CANADIAN_AMSEDI_CODES
			WHERE ACTION = 'REMOVE RELEASE'
			ORDER BY EDI_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "EDI_CODE"); ?></font></td>
					<!--<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CODE_REMOVED"); ?>&nbsp;</font></td> !-->
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "USERNAME"); ?>&nbsp;</font></td>
				</tr>
<?
	}
?>
			</table>
			<br><br>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br><br></td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td><hr></td>
	</tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="1" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><font size="3" face="Verdana">To Hold</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Code</b></font></td>
					<!--<td><font size="2" face="Verdana"><b>Remove HOLD Code</b></font></td> !-->
					<td><font size="2" face="Verdana"><b>Last Edited By</b></font></td>
				</tr>
<?
	$sql = "SELECT * 
			FROM CANADIAN_AMSEDI_CODES
			WHERE ACTION = 'HOLD'
			ORDER BY EDI_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "EDI_CODE"); ?></font></td>
					<!--<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CODE_REMOVED"); ?>&nbsp;</font></td> !-->
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "USERNAME"); ?>&nbsp;</font></td>
				</tr>
<?
	}
?>
			</table>
		</td>
		<td valign="top">
			<table border="1" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><font size="3" face="Verdana">To Remove-Hold</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Code</b></font></td>
					<td><font size="2" face="Verdana"><b>Removes HOLD Code</b></font></td>
					<td><font size="2" face="Verdana"><b>Last Edited By</b></font></td>
				</tr>
<?
	$sql = "SELECT * 
			FROM CANADIAN_AMSEDI_CODES
			WHERE ACTION = 'REMOVE HOLD'
			ORDER BY EDI_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "EDI_CODE"); ?></font></td>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CODE_REMOVED"); ?>&nbsp;</font></td>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "USERNAME"); ?>&nbsp;</font></td>
				</tr>
<?
	}
?>
			</table>

		</td>
	</tr>
	<tr>
		<td colspan="2"><br><br></td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td><hr></td>
	</tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="1" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3" align="center"><font size="3" face="Verdana">To Ignore</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana"><b>Code</b></font></td>
					<!--<td><font size="2" face="Verdana"><b>Remove HOLD Code</b></font></td> !-->
					<td><font size="2" face="Verdana"><b>Last Edited By</b></font></td>
				</tr>
<?
	$sql = "SELECT * 
			FROM CANADIAN_AMSEDI_CODES
			WHERE ACTION = 'IGNORE'
			ORDER BY EDI_CODE";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
				<tr>
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "EDI_CODE"); ?></font></td>
					<!--<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "CODE_REMOVED"); ?>&nbsp;</font></td> !-->
					<td><font size="2" face="Verdana"><? echo ociresult($short_term_data, "USERNAME"); ?>&nbsp;</font></td>
				</tr>
<?
	}
?>
			</table>
		</td>
	</tr>
</table>

<?
	include("pow_footer.php");







function ValidateEntry($edi_code, $action, $remove_code, $rfconn){

	if($edi_code == ""){
		return "EDI Code field cannot be blank.";
	} elseif($action == ""){
		return "Action must be selected.";
	} elseif($action == "REMOVE HOLD" && $remove_code == ""){
		return "If the Action Type is \"Remove Hold\", the Hold Code it removes must be specified.";
	} elseif($action == "REMOVE HOLD") {
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CANADIAN_AMSEDI_CODES
				WHERE EDI_CODE = '".$remove_code."'
					AND ACTION = 'HOLD'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") <= 0){
			return "The \"Remove Code\" entered was not a HOLD.  The Remove Hold option can only be sued to remove HOLD-type codes.";
		}
	}

	return "";
}