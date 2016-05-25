<?
  // All POW files need this session file included
//  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Applications Access";
  $area_type = "ROOT";

  // Provides header / leftnav
  include("pow_header.php");
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">New Email Location</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
	<tr>
		<td><font size="3" face="Verdana">Once TS has helped you sign in for the first time, you can use the link below to get to your email on the new system.<br><br><a href="https://login.microsoftonline.com" target="https://login.microsoftonline.com">https://login.microsoftonline.com</a><br><br>Once you enter your password, you will get to the main screen on Outlook 365 where you can then select Inbox to get to your emails.<br><br>Please use the new email system, and let TS know of any problems.  If any of your old emails are not yet in the new system, use the icon to the old system that was placed on your desktop during the initial setup of the new system.</font></td>
	</tr>
</table>
<?
  // Don't forget the footer
  include("pow_footer.php");
?>
