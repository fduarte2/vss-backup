<?

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - BNI CUSTOMER SYSTEM";
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
                  <font size="5" face="Verdana" color="#0066CC">Add/Modify/Delete Customers</font>
                  <hr><? include("../bni_links.php"); ?>
                  </font>
               </td>
            </tr>
         </table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
       <font size="3" face="Verdana" color="#FF0000">
          <?
              if ($add != "") {
               printf("You are trying to add again the existing customer detail<br/>");  
              }
              if ($added == "true") {
               printf("Succesfully added<br/>");
              }
              if ($modify == "true") {
               printf("Changes Saved<br/>");
              }
              if ($delete == "true") {
               printf("Succesfully deleted<br/>");
              }
          ?>
         </font>
         <p align="left">
            <font size="2" face="Verdana"><b>Maintain Customer Details</b></font>
         </p>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">

          <tr>
            <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="add_customer.php">Add New Customers</a></font>
               </td>
          </tr>
          <tr>
             <td colspan="2" height="10"></td>
          </tr>
          <tr>
            <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="modify_customer.php">Modify Customers</a></font>
               </td>
          </tr>

          <tr>
             <td colspan="2" height="10"></td>
          </tr>

          <tr>
            <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  Print Customer List:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../../reports/print_customer.php?show=all&user=<? echo $user; ?>">All</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../../reports/print_customer.php?show=active&user=<? echo $user; ?>">Active</a></font>
               </td>
          </tr>
         </table>
      <td valign="top" width="30%">
        <p><img border="0" src="images/gnome-word.png" width="64" height="64"></p>
      </td>
     </td>
   </tr>
</table>


<?
include("pow_footer.php");
?>
