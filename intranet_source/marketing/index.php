<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Marketing System";
  $area_type = "MKTG";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from MKTG system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Marketing
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<!--   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="2" valign="top">
	    <font size="2" face="Verdana"><b>From here you can access the various functions needed for inventory management.</b></font>
	  </td>
   </tr> !-->
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="ppt_frontdoor_upload.php">Upload Powerpoint to Front Lobby</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="MRKT_contact_export.php">Export All Contacts To Excel</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
    	<td><font size="2" face="Verdana"><a href="How to distribute information using eblasts.pdf">How to distribute information using EBlasts</a></font></td>
   </tr>			  
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../images/yellowbulletsmall.gif"></td>
    	<td><font size="2" face="Verdana"><a href="How to update the website in crisis situation.pdf">How to update the website in a crisis situation</a></font></td>
   </tr>
	 
</table>

<? include("pow_footer.php"); ?>
