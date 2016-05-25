<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
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
         <font size="5" face="Verdana" color="#0066CC">Finance Applications
          </font>
	  <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>From here you can access Finance applications.</b></font>
         </p>
         <table border="0" width="95%" cellpadding="0" cellspacing="2">
 <!--           <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="storagerate/">Storage Rate Table</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="../images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="contract_headers/">Contract Info Table</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr> !-->
          </table>
      </td>
      <td valign="middle" width="30%">
         <img border="0" src="../images/warehouse_e.jpg" width="218" height="170">
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
