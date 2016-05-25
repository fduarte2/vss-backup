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
	    <font size="5" face="Verdana" color="#0066CC">SSAB STEEL Applications
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
	<td><font size="2" face="Verdana"><a href="split_steel_pallet.php">Steel Split Pallet</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_DO_dest.php">PreLoad DO Information</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_DO_change.php">Change DO Assignments</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_PO_create.php">Truck CheckIn - Create PO#</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_picklist.php">Create Picklist</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_bol.php">Truck CheckOut - Create BOL</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_ship_view.php">Ship To Addresses</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_carrier_view.php">Carriers</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_DOPO_lookup.php">DO/PO Information</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_DO_outbound.php">DO-based Outbound report (by PO)</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_PO_reopen.php">Re-Open Port Order</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_whse_change.php">Change Steel Warehouse Location</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="steel_pallet_recon.php">Steel Pallet Reconcilliation</a></font></td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
