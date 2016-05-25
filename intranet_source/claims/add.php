<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Claim";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Add Claim
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
 <?
   if($claim_id != ""){
     printf("<b>Claim $claim_id has been saved!</b><br /><br />");
   }
?>
	    <font size="2" face="Verdana"><b>Choose System<br />
		<!--<br /><a href="add_ccds.php">Meat System (CCDS)</a><br />
        <br /><a href="add_rf.php?system=RF">Fruit System (RF)</a><br /> !-->
        <br /><a href="add_main.php?system=BNI">Juice System (BNI)</a>
         </p>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
