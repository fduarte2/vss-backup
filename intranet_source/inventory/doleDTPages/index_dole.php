<?
// All POW files need this session file included
include "pow_session.php";

// Define some vars for the skeleton page
$title = "Inventory System";
$area_type = "INVE";

// Provides header / leftnav
include "pow_header.php";
if ($access_denied) {
	printf("Access Denied from INVE system");
	include "pow_footer.php";
	exit;
}
?>

<h1>Dole DT Pages</h1>

<ul>
	<li><a href="Paper_to_Port.php">Transfer to Port Account</a>
	<li><a href="sell_paper_to_newcust.php">DT Customer-to-Customer Transfer</a>
	<li><a href="delete_DT.php">Dole DT Unreceived Roll (EDI) Deletion</a>
	<li><a href="dole_DT_in_out.php">Dole DT Inbound/Outbound Report</a>
	<li><a target="DoleDTNonReceive.php" href="DoleDTNonReceive.php">Dole DT non-received Dock Tickets</a>
	<li><a target="Dolerailcarlookup.php" href="Dolerailcarlookup.php">Dole DT Rail Car Lookup</a>
		By Railcar: <input type="text" name="railcar" size="20" maxlength="20"> <input type="submit" name="submit" value="Railcar Search">
	</li>
	<li><a href="DoleEDImanualupload/DoleDTupload.php">Dole DT Barcode Manual-Upload</a>
	<li><a href="../paper_orders_active.php">Paper Orders Open / Completed Today</a>
	<li><a href="DoleEDILookup.php">Dole EDI Lookup</a>
	<li><a href="Paper_to_Port.php">Transfer to Port Account</a>
	<li><a href="EDI_code_alter.php">Add/Change EDI Grade Code</a>
	<li><a href="Paper_code_change.php">Change Already Imported Paper Codes</a>
	<li><a href="DT_list_edit.php">DT Pre-Received Paper Alterations</a>
	<li><a href="Paper_code_change_history.php">History of Manually Changed Codes</a>
	<li><a href="adjust.php">Roll Correction</a>
</ul>







<?php
/*
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Dole DT Pages
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
	<td><font size="2" face="Verdana"><a href="transfer_to_port_DT.php">Transfer to Port Account</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="delete_DT.php">Dole DT Unreceived Roll (EDI) Deletion</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="dole_DT_in_out.php">Dole DT Inbound/Outbound Report</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a target="DoleDTNonReceive.php" href="DoleDTNonReceive.php">Dole DT non-received Dock Tickets</a></font></td>
   </tr>
   <tr>
   <form name="form1" action="Dolerailcarlookup.php" method="post">
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a target="Dolerailcarlookup.php" href="Dolerailcarlookup.php">Dole DT Rail Car Lookup</a></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<font size="2" face="Verdana">By Railcar:</font>&nbsp;&nbsp;&nbsp;
										<input type="text" name="railcar" size="20" maxlength="20">&nbsp;
										<input type="submit" name="submit" value="Railcar Search"></td>
   </form></tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="DoleEDImanualupload/DoleDTupload.php">Dole DT Barcode Manual-Upload</a></font></td>
   </tr>
<!--   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="dole_book_in.php">Dole Booking Inbound Report</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="dole_book_out.php">Dole Booking Outbound Report</a></font></td>
   </tr> !-->
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="../paper_orders_active.php">Paper Orders Open / Completed Today</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="DoleEDILookup.php">Dole EDI Lookup</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="Paper_to_Port.php">Transfer to Port Account</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="EDI_code_alter.php">Add/Change EDI Grade Code</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="Paper_code_change.php">Change Already Imported Paper Codes</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="DT_list_edit.php">DT Pre-Received Paper Alterations</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="Paper_code_change_history.php">History of Manually Changed Codes</a></font></td>
   </tr>
</table>
*/
?>

<? include "pow_footer.php"; ?>
