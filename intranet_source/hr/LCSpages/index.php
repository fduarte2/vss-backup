<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Human Resources Applications Access";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">LCS-Rate Applications
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="LCSRateTable.php">Modify Special Employee Rates</a></font></td>
   </tr>
   <tr>
    <td width="1%">&nbsp;</td>
	<td valign="middle" width="5%"><img src="../../images/yellowbulletsmall.gif"></td>
	<td><font size="2" face="Verdana"><a href="LCSRateTableExcep.php">Modify Service Code Rate Exceptions</a></font></td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
