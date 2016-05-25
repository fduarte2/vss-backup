<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Customer Profile Page (Marketing)";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Customer Profile Main Page
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
    <tr>
		<td width="1%">&nbsp;</td>
		<td><font size="3" face="Verdana"><a href="customer_profile.php">Customer Profile View Page</a></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td><font size="3" face="Verdana"><a href="customer_add.php">Create / Rename Customer</a></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td><font size="3" face="Verdana"><a href="customer_modify.php">Manage Commodities / Customer Numbers</a></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td><font size="3" face="Verdana"><a href="marketing_supercustomer.php">Create / Rename Supercustomer</a></font></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td><font size="3" face="Verdana"><a href="marketing_super_info.php">Supercustomer Information</a></font></td>
	</tr>
</table>

<? include("pow_footer.php"); ?>
