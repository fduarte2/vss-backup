<?
  // All POW files need this session file included
  include("pow_session.php");

   // Define some vars for the skeleton page
  $title = "Add/Edit Lot Id";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">Add/Edit a Lot - RF</font>
                  <hr> 
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
              if ($input != "") {
               printf("$input<br/><br/>");
              }
?>
         </font>
         
<table border="0" width="100%" cellpadding="0" cellspacing="2">

          <tr>
            <td valign="middle" width="5%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="40%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="add_lot.php">Add a Lot</a></font>
               </td>
          </tr>
          <tr>
             <td colspan="2" height="10"></td>
          </tr>
          <tr>
            <td valign="middle" width="5%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2" color="#000080">
                  <a href="edit_lot.php">Edit a Lot</a></font>
               </td>
          </tr>
          <tr>
             <td colspan="2" height="10"></td>
          </tr>

</table>
      </td>
   </tr>
</table>


<?
include("pow_footer.php");
?>

