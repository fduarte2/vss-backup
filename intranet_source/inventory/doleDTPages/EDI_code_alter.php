<?
/*
*	Screen that lets INV add/adjust EDI paper codes.
*	Jul 2012.
********************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Dole EDI codes";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$code = $HTTP_POST_VARS['code'];
	$weight = $HTTP_POST_VARS['weight'];
	$width = $HTTP_POST_VARS['width'];
	$method = $HTTP_POST_VARS['method'];

	if($submit == "Save"){
		if($method == "insert"){
			$sql = "INSERT INTO DOLEPAPER_EDI_IMPORT_CODES
						(BASIS_WEIGHT,
						PAPER_WIDTH,
						PAPER_CODE)
					VALUES
						('".$weight."',
						'".$width."',
						'".$code."')";
		} else {
			$sql = "UPDATE DOLEPAPER_EDI_IMPORT_CODES
					SET BASIS_WEIGHT = '".$weight."',
					PAPER_WIDTH = '".$width."'
					WHERE PAPER_CODE = '".$code."'";
		}

		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		echo "<font color=\"#0000FF\">".$code." has been ".$method."ed.</font>";
	}

																	
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">EDI Grade Code Add/Update</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="first" action="EDI_code_alter.php" method="post">
	<tr>
		<td width="10%"><font size="3" face="Verdana">Grade Code:</font></td>
		<td><input type="text" size="10" maxlength="10" name="code" value="<? echo $code; ?>">
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Check code"><hr></td>
	</tr>
</form>
<?
	if($submit != "" && $code != ""){
		$sql = "SELECT BASIS_WEIGHT, PAPER_WIDTH
				FROM DOLEPAPER_EDI_IMPORT_CODES
				WHERE PAPER_CODE = '".$code."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			$weight = "";
			$width = "";
			$method = "insert";
		} else {
			$weight = ociresult($stid, "BASIS_WEIGHT");
			$width = ociresult($stid, "PAPER_WIDTH");
			$method = "update";
		}

?>
<form name="second" action="EDI_code_alter.php" method="post">
<input type="hidden" name="code" value="<? echo $code; ?>">
<input type="hidden" name="method" value="<? echo $method; ?>">
	<tr>
		<td><font size="3" face="Verdana">Code:</font></td>
		<td><font size="3" face="Verdana"><? echo $code; ?></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Basis Weight:</font></td>
		<td><input type="text" size="10" maxlength="10" name="weight" value="<? echo $weight; ?>">
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Width:</font></td>
		<td><input type="text" size="10" maxlength="10" name="width" value="<? echo $width; ?>">
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save"><hr></td>
	</tr>
</form>
<?
	}
?>
</table>
<?
	include("pow_footer.php");