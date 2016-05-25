<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Ship Barcode Duplication Check";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barcode Duplication Check</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" action="result.php" method="post" name="result">
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2">Check .csv file:&nbsp;&nbsp;&nbsp;<input type="file" name="check_file">&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" width="3%">
		<td><input type="submit" name="submit" value="submit"></td>
	</tr>
</form>
</table>

<?
	include("pow_footer.php");
?>