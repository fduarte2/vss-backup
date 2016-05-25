<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Front Window";
  $area_type = "TELR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TELR system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Front Window/Teller Links
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
	<td><font size="2" face="Verdana"><a href="../inventory/steel/steel_PO_create.php">Truck CheckIn - Create PO#</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="../inventory/steel/steel_bol.php">Truck CheckOut - Create BOL</a></font></td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
