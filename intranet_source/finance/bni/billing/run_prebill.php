<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - BNI Generate Prebills";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<!-- Generate Prebill - Main page -->

<script type="text/javascript">

   function check(billing_type) {
      x = document.select_type
      x.selected_type.value = billing_type
   }

   function validate_form()
   {
      x = document.select_type
      selected_type = x.selected_type.value

      // No order is selected
      if (selected_type == "") {
	 alert("You need to select a billing type!")
         return false
      }

      return true
   }

</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">BNI - Generate Prebills</font>
         <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
      <p align="left">From here you can generate prebills:</p></font>
         <table border="0" width="100%" cellpadding="0" cellspacing="2">
            <tr>
               <td width="8%" valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td width="92%" valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="holmen_freight.php">Holmen - Record Freight Charge</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="holmen_storage.php">Holmen - Storage Charge</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="holmen_vessel.php">Holmen - Vessel Billing</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="rate.php">Star Vessel - Rates</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080">
                  <a href="star_advance_billing.php">Star Vessel - Vessel/Wharfage Billing</a></font>
               </td>
            </tr>

         </table>
      </td>
      <td width="30%" align="center" valign="top">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
   </tr>
</table>
<br />
<? include("pow_footer.php"); ?>
