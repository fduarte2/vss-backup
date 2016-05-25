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
         <font size="5" face="Verdana" color="#0066CC">RF - Reporting</font>
	 <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
          <font face="Verdana" size="2"<b>From here you can create reports for RF System:</b></font>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td width="8%" valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td width="92%" valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="tag_audit.php">Tag Audit</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="f_shipment.php">Warehouse F - Shipment</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="A2.php">Billed A2 Warehouse Report</a></font>
               </td>
            </tr> 
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="C3.php">Billed C3/A1 Warehouse Report</a></font>
               </td>
            </tr> 
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="swing_load.php">Sbrocco Swing Loads</a></font>
               </td>
            </tr> 
         </table>
      </td>
      <td valign="top" align="center" width="40%">
        <p><img border="0" src="images/gnome-gemvt.png" width="100" height="100"></p>
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
