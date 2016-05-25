<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Labor Ticket Review";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Labor Ticket Review
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td>&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b><a href="process.php" target="_new">Click here to generate a list of Un-Billed Labor Tickets</a></font>
         </p>
      </td>
   </tr>
   <tr>
       <td>&nbsp;</td>     
       <td width="100%" valign="top" colspan="2">
         <font face="Verdana" size="2"><br />
	 <p align="left">
         </p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
