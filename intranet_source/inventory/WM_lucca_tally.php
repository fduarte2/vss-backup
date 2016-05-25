<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Printification of Lucca Tallys";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Create Lucca/Crossdock Tally</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="vessel_check" action="WM_lucca_tally_pdf.php" method="post">
	<tr>
		<td align="left" width="20%"><font size="3" face="Verdana">Order #:</font></td>
		<td><input type="text" name="order" size="20" maxlength="20" value=""></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Truck #:</font></td>
		<td><input type="text" name="truck" size="20" maxlength="20" value=""></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Trucking Company:</font></td>
		<td><input type="text" name="trkcompany" size="20" maxlength="20" value=""></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Driver Last Name:</font></td>
		<td><input type="text" name="lastname" size="20" maxlength="20" value=""></td>
	</tr>
	<tr>
		<td align="left"><font size="3" face="Verdana">Seal #:</font></td>
		<td><input type="text" name="seal" size="20" maxlength="20" value=""></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Tally">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Generate Tally And Send Email"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
?>