<?
  // All POW files need this session file included
//  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Applications Access";
  $area_type = "ROOT";

  // Provides header / leftnav
  include("pow_header.php");
?>
<script language="JavaScript">
function PhoneBook()
{
  var now = new Date();
  document.location = "documents/EmployeeDir.xls?time="+now;
}
</script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Application Access Home</font>
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
	    <font size="2" face="Verdana"><b>The single point of access to:</font>
         </p>
	 <table border="0" width="100%" cellpadding="2" cellspacing="0">
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <a href="email_move_2012.php"><font face="Verdana" size="2" color="#000080">New Email Location</font></a>
               </td>
        </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <a href="ship_schedule/"><font face="Verdana" size="2" color="#000080">Ship Schedule</font></a>
               </td>
        </tr>
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                  <a href="javascript:PhoneBook()"><font face="Verdana" size="2" color="#000080">DSPC Phone Book</font></a>
               </td>
            </tr>
<!--	
            <tr>
               <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                  <a href="/help_desk/helpdesk.php"><font face="Verdana" size="2" color="#000080">DSPC Web Helpdesk</font></a>
               </td>
            </tr>
	    <tr>
	       <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		  <a href="/fourm/"><font face="Verdana" size="2" color="#000080">DSPC Forum</font></a>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		  <a href="/help_desk/ts_help.php"><font face="Verdana" size="2" color="#000080">DSPC Help Desk</font></a>
	       </td>
            </tr>
-->
	    <tr>
	       <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		  <font face="Verdana" size="2" color="#000080">Cold Chain Distribution Systems</font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">Finance Applications</font>
	       </td>
	    </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">Inventory Applications</font>
	       </td>
	    </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">Labor Control Systems</font>
	       </td>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">Supervisor Applications</font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080">Technology Solutions Applications</font>
               </td>
            </tr>
	 </table>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="images/FSI-comp.jpg" width="218" height="170"></p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
       <td>&nbsp;</td>     
       <td width="100%" valign="top" colspan="2">
         <font face="Verdana" size="2"><br />
	 <p align="left">
 	 The Port of Wilmington has been recognized by American Association of Port Authorities (AAPA) as 
	 a leading port in developing and perfecting technology solutions for scanning, billing and inventory
	 capabilities.</p>
	 <p align="left">
	 We are moving further by rewriting the systems and incorporating them into web applications to make 
	 them more reliable, web accessible and user friendly.</p>
	 <br />
      </td>
   </tr>
</table>
<?
  // Don't forget the footer
  include("pow_footer.php");
?>
