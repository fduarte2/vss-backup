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
         <font size="5" face="Verdana" color="#0066CC">BNI System</font>
         <hr>
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
         <font size="2" face="Verdana"><b>From here you can access BNI System.</b></font>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="8%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="92%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="billing/">Billing</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="maintain/">Maintaining</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="reporting/">Reporting</a></font>
               </td>
            </tr>
         </table>
      </td>
      <td valign="top" align="center" width="30%">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td valign="middle" align="center">
	 <font face="Verdana" size="2" color="#000080">
	    <a href="bni_system_help.doc" target="_new">BNI System Help</a>
	 </font>
      </td>
      <td>&nbsp;</td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
