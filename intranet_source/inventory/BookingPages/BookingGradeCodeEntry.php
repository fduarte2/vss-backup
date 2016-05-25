<?
/*
*			Adam Walter, Jun 2010
*			This page allows OPS to add a 
*			New SSCC Grade Code for the booking EDI parser
******************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Booking Booking";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }


	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		echo "Error logging on to the RF Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$replace_array = array("'", ",", "\\");
	$product = str_replace($replace_array, "", $HTTP_POST_VARS['product']);
	$sscc = str_replace($replace_array, "", $HTTP_POST_VARS['sscc']);
	$desc = str_replace($replace_array, "", $HTTP_POST_VARS['desc']);

	if($submit == "Save Code"){
		if($product == "" || $sscc == "" || $desc == ""){
			echo "<font size=\"2\" face=\"Verdana\" color=\"#FF0000\"><b>Unable to save; all 3 fields must be entered</b></font><br>";
		} else {
			$sql = "INSERT INTO BOOKING_PAPER_GRADE_CODE (PRODUCT_CODE, SSCC_GRADE_CODE, GRADE_DESCRIPTION) VALUES
					('".$product."', '".$sscc."', '".$desc."')";
//					echo $sql;
			$ora_success = ora_parse($cursor, $sql);
			$ora_success = ora_exec($cursor, $sql);

			echo "<font size=\"2\" face=\"Verdana\" color=\"#0000FF\"><b>EDI code ".$product." saved.</b></font><br>";
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Booking Grade Code entry
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="BookingGradeCodeEntry.php" method="post">
	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Note:  Apostrophes and commas will be automatically removed from any entry.</b></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Product (EDI) Code:</font></td>
		<td><input type="text" name="product" size="15" maxlength="15">
	</tr>
	<tr>
		<td><font size="2" face="Verdana">SSCC (Printout) Code:</font></td>
		<td><input type="text" name="sscc" size="15" maxlength="15">
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Full Description:</font></td>
		<td><input type="text" name="desc" size="30" maxlength="60">
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Code"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>