<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - BNI Maintain";
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
         <font size="5" face="Verdana" color="#0066CC">BNI - Maintaining</font>
         <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
         <font face="Verdana" size="2"><b>Here you can maintain the BNI System.</b></font>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td width="8%" valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td width="92%" valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="freight_rate.php">Holmen - Update Freight Rate</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="sail_ship.php">Holmen - Verify Ship Sailing Date</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="customer.php">BNI - Edit Customers</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
 <!--         <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="closing_fiscal_year.php">Closing Fiscal Year</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr> !-->
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="rfbnidef.php">RF/BNI Miscbill Rates</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
          <tr>
         </table>
      </td>
      <td valign="top" align="center" width="30%">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
