<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - RF";
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
         <font size="5" face="Verdana" color="#0066CC">RF - Billing</font>
	 <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="5"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
         <font face="Verdana" size="2">
         <b>Here you can generate and update RF Storage prebills:</b></font>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
<!--            <tr>
               <td valign="middle" width="8%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="92%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="rf_storage.php">Run RF Storage</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="update_prebill.php">Update RF Storage Prebills</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr> !-->
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="Billed_Pallets.php">Total Tally of Billable Chilean Fruit Pallets in Storage</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="Finance_Counters.php">Running Totals for Chilean Fruit Ships</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="Abitibi_condensed_bill.php">Abitibi Invoice Summary</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
         </table>
      </td>
      <td width="40%" align="center" valign="top">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
