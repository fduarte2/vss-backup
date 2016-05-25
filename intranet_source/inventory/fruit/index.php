<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Fruit";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Fruit Systems</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>From here you can access Fruit System applications.</b></font>
         </p>
	 <table border="0" width="100%" cellpadding="0" cellspacing="2">
	    <tr>
	       <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		   <font face="Verdana" size="2" color="#000080">
		   <a href="reporting/index.php">Reporting</a></font>
	       </td>
            </tr>
            <tr>
	       <td colspan="2" height="10"></td>
	    </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">
		  <a href="/inventory/eloads/">Eloads</a></font>
	       </td>
	    </tr>
            <tr>
	       <td colspan="2" height="10"></td>
	    </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                   <font face="Verdana" size="2" color="#000080">
                   <a href="/inventory/fruit/import/">Import Manifest</a></font>
               </td>
             </tr>
             <tr>
                <td colspan="2" height="10"></td>
             </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                   <font face="Verdana" size="2" color="#000080">
                   <a href="/inventory/fruit/pallet_arrival_correction.php">Edit Pallet Arrival #</a></font>
               </td>
             </tr>
	 </table>
      </td>
      <td valign="middle" width="30%">
        <img border="0" src="images/warehouse_e.jpg" width="215" height="170">
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
