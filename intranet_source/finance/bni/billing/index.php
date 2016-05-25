<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - BNI";
  $area_type = "FINA";

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
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">BNI - Billing</font>
	 <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="5"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
         <font face="Verdana" size="2" color="#000080">
         <b>Here you can generate, update and print prebills and invoices:</b></font>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="8%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="92%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="run_prebill.php">Generate Pre-Invoice</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="update_prebill.php">Update Pre-Invoice</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="print_prebill.php">Print Pre-Invoice</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="run_invoice.php">Generate Invoices</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  Update Invoices&nbsp;&nbsp;&nbsp;<a href="update_invoice.php">V1</a>&nbsp;&nbsp;&nbsp;<a href="update_invoice_v2.php">V2</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="forcerun_preinvoices.php">Force-run V2 PreBills</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="v2_edit.php">V2 bill MANUAL Edit</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  Delete V2 PreBills
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="delete_prebills.php">Individual</a>
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="delete_all_v2.php">All (By Type)</a></font>
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="delete_lease_range_v2.php">Leases (By Range)</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="lease_maintain_v2.php">Maintain Lease Bills for Billing-V2</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="ForPDI.php">Terminal Services / Transfers (Chilean) v2</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
<!--            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="reprint_invoice.php">Reprint Invoices</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2">&nbsp;</td>
            </tr> !-->
         </table>
      </td>
      <td width="30%" align="center" valign="top">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
