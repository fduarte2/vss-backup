<?
/*
*		Adam Walter, July/Aug/Spe 2014.
*
*		Ignore Lloyd's # for CLR-EDI
*********************************************************************************/


  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "CLR ignore Lloyd";
  $area_type = "CLR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CLR system");
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
	$pagename = "ignore_lloyd";
	include("page_security.php");
//	$security_allowance = PageSecurityCheck($user, $pagename, "test");
	$security_allowance = PageSecurityCheck($user, $pagename, "");


	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Ignore"){
		$lloyd = $HTTP_POST_VARS['lloyd'];

		$sql = "SELECT COUNT(*) THE_COUNT
				FROM CLR_IGNORE_LLOYD
				WHERE LLOYD_NUM = '".$lloyd."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		if(ociresult($short_term_data, "THE_COUNT") < 1){
			$sql = "INSERT INTO CLR_IGNORE_LLOYD
						(LLOYD_NUM)
					VALUES
						('".$lloyd."')";
			$insert = ociparse($rfconn, $sql);
			ociexecute($insert);

			$sql = "UPDATE CLR_LLOYD_ARRIVAL_MAP
					SET CLR_IGNORE = 'Y'
					WHERE LLOYD_NUM = '".$lloyd."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
		}

		echo "<font color=\"#0000FF\">Lloyd# ".$lloyd." Ignored.</font><br>";
	}
	if($submit == "Remove Ignore"){
		$remove_lloyd = $HTTP_POST_VARS['remove_lloyd'];

		$sql = "DELETE FROM CLR_IGNORE_LLOYD
				WHERE LLOYD_NUM = '".$remove_lloyd."'";
		$delete = ociparse($rfconn, $sql);
		ociexecute($delete);

		echo "<font color=\"#0000FF\">Lloyd# ".$lloyd." Ignore Status Removed.</font><br>";
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Lloyd#'s to Ignore
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="0">
<form name="filter" action="ignore_lloyd.php" method="post">
	<tr>
		<td><font size="2" face="Verdana"><b>Lloyd's # to Ignore:</b></font></td>
		<td><input type="text" name="lloyd" size="20" maxlength="20"></td>
	</tr>
<?
	if(strpos($security_allowance, "M") !== false){
?>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Ignore"></td>
	</tr>
<?
	}
?>
</form>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><hr></td>
	</tr>
</table>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3" align="center"><font size="3" face="Verdana"><b>Ignored Lloyd's #s:</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Lloyd's #</b></font></td>
		<td><font size="2" face="Verdana"><b>Ship Name</b></font></td>
		<td>&nbsp;</td>
	</tr>
<?
	$form_counter = 0;

	$sql = "SELECT * FROM CLR_IGNORE_LLOYD
			ORDER BY LLOYD_NUM";
	$lloyd_data = ociparse($rfconn, $sql);
	ociexecute($lloyd_data);
	if(!ocifetch($lloyd_data)){
?>
	<tr>
		<td colspan="3" align="center"><font size="2" face="Verdana"><b>No Curent Ignored Lloyd's.</b></td>
	</tr>
<?
	} else {
		do {
			$form_counter++;

			$sql = "SELECT VESSEL_NAME
					FROM VESSEL_PROFILE
					WHERE LLOYD_NUM = '".ociresult($lloyd_data, "LLOYD_NUM")."'";
			$name_data = ociparse($rfconn, $sql);
			ociexecute($name_data);
			if(!ocifetch($name_data)){
				$vesname = "UNKNOWN";
			} else {
				$vesname = ociresult($name_data, "VESSEL_NAME");
			}
?>
	<form name="remove_lloyd<? echo $form_counter; ?>" action="ignore_lloyd.php" method="post">
	<input type="hidden" name="remove_lloyd" value="<? echo ociresult($lloyd_data, "LLOYD_NUM"); ?>">
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($lloyd_data, "LLOYD_NUM"); ?></td>
		<td><font size="2" face="Verdana"><? echo $vesname; ?></td>
<?
			if(strpos($security_allowance, "M") !== false){
?>
		<td><input name="submit" type="submit" value="Remove Ignore"></td>
<?
			} else {
?>
		<td>&nbsp;</td>
<?
			}
?>
	</tr>
	</form>
<?
		} while(ocifetch($lloyd_data));
	}
?>
</table>
<?
	include("pow_footer.php");