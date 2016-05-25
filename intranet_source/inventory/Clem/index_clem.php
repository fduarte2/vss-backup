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
	    <font size="5" face="Verdana" color="#0066CC">Clementine Applications
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="clem_picklist_loc.php">Export Clementine Picklist Locations</a></font></td>
   </tr> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="domestic_picklist.php">Domestic Clementine Picklist Locations</a></font></td>
   </tr> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="clem_delete_pallet.php">Delete Clementine Pallet</a></font></td>
   </tr> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="clem_ves_discharge.php">Confirm Clementine Vessel Discharge</a></font></td>
   </tr> 
</table>

<? include("pow_footer.php"); ?>
