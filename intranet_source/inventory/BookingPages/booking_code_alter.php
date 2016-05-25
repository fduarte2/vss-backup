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

	$conn = ocilogon("SAG_OWNER", "OWNER", "RF"); echo "<font size=\"1\">LIVE DB</font><br>";
//	$conn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");  echo "<font size=\"5\" color=\"#FF0000\">TEST DB</font><br>";
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$code = strtoupper($HTTP_POST_VARS['code']);
	$sscc = strtoupper($HTTP_POST_VARS['sscc']);
	$desc = $HTTP_POST_VARS['desc'];
	$grade_type = $HTTP_POST_VARS['grade_type'];
	$method = $HTTP_POST_VARS['method'];

	if($submit == "Save"){
		$sql = "SELECT SSCC_GRADE_CODE
				FROM BOOKING_PAPER_GRADE_CODE
				WHERE PRODUCT_CODE = '".$code."'
					AND SSCC_GRADE_CODE != '".$sscc."'";
		$valid_check = ociparse($conn, $sql);
		ociexecute($valid_check);
		if(!ocifetch($valid_check)){
			// no duplicate, proceed
			if($method == "insert"){
				$sql = "INSERT INTO BOOKING_PAPER_GRADE_CODE
							(PRODUCT_CODE,
							SSCC_GRADE_CODE,
							GRADE_DESCRIPTION,
							GRADE_TYPE_ID)
						VALUES
							('".$code."',
							'".$sscc."',
							'".$desc."',
							'".$grade_type."')";
			} else {
				$sql = "UPDATE BOOKING_PAPER_GRADE_CODE
						SET PRODUCT_CODE = '".$code."',
							GRADE_DESCRIPTION = '".$desc."',
							GRADE_TYPE_ID = '".$grade_type."'
						WHERE SSCC_GRADE_CODE = '".$sscc."'";
			}

			$stid = ociparse($conn, $sql);
			ociexecute($stid);
			echo "<font color=\"#0000FF\">".$code." has been ".$method."ed.</font>";
		} else {
			// product code is a key.  alert user.
			echo "<font color=\"#FF0000\">Product Code ".$code." has already been tied to SSCC code ".ociresult($valid_check, "SSCC_GRADE_CODE").".  Cannot Save.</font>";
		}
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
<form name="first" action="booking_code_alter.php" method="post">
	<tr>
		<td width="10%"><font size="3" face="Verdana">SSCC Code:</font></td>
		<td><input type="text" size="15" maxlength="15" name="sscc" value="<? echo $sscc; ?>">
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Check code"><hr></td>
	</tr>
</form>
<?
	if($submit != "" && $sscc != ""){
		$sql = "SELECT PRODUCT_CODE, GRADE_DESCRIPTION, GRADE_TYPE_ID
				FROM BOOKING_PAPER_GRADE_CODE
				WHERE SSCC_GRADE_CODE = '".$sscc."'";
		$stid = ociparse($conn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
			$code = "";
			$desc = "";
			$grade_type = "";
			$method = "insert";
		} else {
			$code = ociresult($stid, "PRODUCT_CODE");
			$desc = ociresult($stid, "GRADE_DESCRIPTION");
			$grade_type = ociresult($stid, "GRADE_TYPE_ID");
			$method = "update";
		}

?>
<form name="second" action="booking_code_alter.php" method="post">
<input type="hidden" name="sscc" value="<? echo $sscc; ?>">
<input type="hidden" name="method" value="<? echo $method; ?>">
	<tr>
		<td><font size="3" face="Verdana">SSCC Code:</font></td>
		<td><font size="3" face="Verdana"><? echo $sscc; ?></font></td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Product Code:</font></td>
		<td><input type="text" size="15" maxlength="15" name="code" value="<? echo $code; ?>">
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Grade Description:</font></td>
		<td><input type="text" size="60" maxlength="60" name="desc" value="<? echo $desc; ?>">
	</tr>
	<tr>
		<td><font size="3" face="Verdana">Grade Type:</font></td>
		<td><input type="text" size="4" maxlength="4" name="grade_type" value="<? echo $grade_type; ?>">
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