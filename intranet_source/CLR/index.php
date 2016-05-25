<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "CLR System";
  $area_type = "CLR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CLR system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Ocean Manifest EDI Information
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan="2" valign="top">
	    <font size="2" face="Verdana"><b>From here you can access the various functions needed for CLR management.</b></font>
	  </td>
   </tr>
<!--   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="seal_change.php">Seal Change</a></font></td>
   </tr> !-->
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="forcerun_CBP.php">Forcerun CBP Emails</a></font></td>
   </tr> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="push_to_CLR.php">Ocean Manifest-EDI Review/Edit</a></font></td>
   </tr> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="push_to_CLR_confirm.php">Ocean Manifest-EDI Push</a></font></td>
   </tr> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="lloyd_to_LR.php">Lloyd# Match to PoW Arrival#</a></font></td>
   </tr> 
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="ignore_lloyd.php">Lloyd#s to Ignore</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="CLR_main_upload.php">Manual Entry</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="EDI_codes.php">CLR-EDI 350 Codes</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="review_309.php">EDI 309 Lookup/Summary</a></font></td>
   </tr>
   <tr>
	<td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="/images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="350_pending.php">EDI 350 UNPROCESSED Lookup</a></font></td>
   </tr>
			  
	 
</table>

<? include("pow_footer.php"); ?>
