<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Abitibi Paper Inventory
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="2" valign="top">
	    <font size="2" face="Verdana"><b>From here you can access the various functions needed for Abitibi Paper inventory management.</b></font>
	  </td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="abitibi_in_out.php">Abitibi Inbound/Outbound Report</a></font></td>
   </tr> 
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="railcarlookup.php">Abitibi Rail Car Lookup</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana">Booking # Set:&nbsp;&nbsp;<font size="2" face="Verdana"><a href="AbiBooking.php">Abitibi</a></font>&nbsp;&nbsp;<font size="2" face="Verdana"></font></td>   
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a target="abi_pallet_popup.php" href="abi_pallet_popup.php">Abitibi Pallet Popup</a></font></td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
