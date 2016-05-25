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
	    <font size="5" face="Verdana" color="#0066CC">WALMART Pages
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
	<td><font size="2" face="Verdana"><a href="https://www.eportwilmington.com/GeneralLinks/WM_itemrecap_index.php" 
			target="https://www.eportwilmington.com/GeneralLinks/WM_itemrecap_index.php">Walmart Inventory by Product# (Opens New Tab)</font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_PO_InOut.php">BPO In/Out Report</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_po_change.php">Walmart Attributes change</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="walmart_InHouse_RightNow.php">Walmart Quantity Available by Item Number</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_split_alter_or_recoup.php">Walmart Split Pallets - Adjust QTY-Received / Recoup</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_order_auto.php">Walmart Projected Shipments File Upload</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="walmart_proj.php">Walmart Projected Shipments Manual Entry/Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="walmart_proj_item_lookup.php">(Lookup By Item)</a></font></td>
   </tr> 
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="walmart_lucca_order_entry.php">Add Lucca/Holt Order#</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_lucca_tally.php">Wal-Mart Lucca/Holt Tally Printout</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_repack_expected.php">Walmart Repack Order Screen</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_FIFO_override.php">WALMART FIFO-Override Entry</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="walmart_trans_report.php">Out-of-Walmart Transfer Report</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_by_date_activity.php">By Date Activity</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_weekly_audits_check.php">Weekly Spot Check</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="WM_expected_QC.php">Set Expected QC Date/Time</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="walmart_check_log.php">Walmart Truck Check</a></font></td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
