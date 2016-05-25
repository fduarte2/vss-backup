<?

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director - HIRE PLAN";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Daily Hire Plan</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td>&nbsp;</td>
      <td valign="top" width="70%">
         <table border="0" width="100%" cellpadding="2" cellspacing="0">
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="truckloading.php"><font face="Verdana" size="2" color="#000080">Truckloading</a></font>
               </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 6220 to 6229, 6720 to 6729)</font>
               </td>
            </tr>      
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="backhaul.php"><font face="Verdana" size="2" color="#000080">Backhaul</font></a>
               </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 6110 to 6119, 6130 to 6149)</font>
               </td>
            </tr>      
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="terminal_service.php"><font face="Verdana" size="2" color="#000080">Terminal Service </font></a>
               </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 6520 to 6619)</font>
               </td>
            </tr>
     
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="rail_car.php"><font face="Verdana" size="2" color="#000080">Rail Car Handling</font></a>
               </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 6310 to 6319)</font>
               </td>
            </tr>
    
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="container.php"><font face="Verdana" size="2" color="#000080">Container Handling</font></a>
               </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 6410 to 6419)</font>
               </td>
            </tr>
   
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="maintenance.php"><font face="Verdana" size="2" color="#000080">Maintenance Service</font></a>          </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 7200 to 7277)</font>
               </td>
            </tr>

            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="non_revenue.php"><font face="Verdana" size="2" color="#000080">Non Revenue Service</font></a>
               </td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2">(Service code 7300 to 7399)</font>
               </td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="review.php"><font face="Verdana" size="2" color="#000080">Review All</font></a>
               </td>
            </tr>

	 </table>
      </td>
      <td valign="top" width="30%">
        <p><img border="0" src="images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
