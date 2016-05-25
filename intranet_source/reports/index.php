<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Reports System";
  $area_type = "RPTS";

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
	    <font size="5" face="Verdana" color="#0066CC">Reports
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
	    <font size="2" face="Verdana"><b>A listing of On-Demand Reports.</b></font>
	  </td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="daily_transfer_detail.php">Daily Transfer Report</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="daily_dockret_detail.php">Daily Dock Return Summary Report</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="daily_outbound_summary.php">Daily Outbound Activity Summary</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="order_details.php">Order Details</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font face="Verdana" size="2" color="#000080">
		  Print Customer List:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="print_customer.php?show=all&user=<? echo $user; ?>">All</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="print_customer.php?show=active&user=<? echo $user; ?>">Active</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="Qty_InHouse.php">Quantity In-House Report by Customer</a></font></td>
   </tr>

</table>

<? include("pow_footer.php"); ?>
