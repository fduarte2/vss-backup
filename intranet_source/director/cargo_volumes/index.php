<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Finalized Cargo Volumes 
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
	    <font size="2" face="Verdana"><b> </b></font>
         </p>
      </td>
   </tr>
   <tr>
      <td colspan="4">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>&nbsp;</td>
      <td valign="top" width="88%">
         <p align="left">
         <font size="2" face="Verdana"><a href="./FinalizedNonDole2006.xls">Pacific Seaways 2006 (Non-Dole)</a><br /><br />
         <font size="2" face="Verdana"><a href="./FinalizedDole2006.xls">Pacific Seaways 2006 (Dole)</a><br /><br />
      </td>
   </tr>
</table>
	
<? include("pow_footer.php"); ?>
