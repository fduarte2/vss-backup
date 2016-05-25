<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";


  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Unemployment Claims - View/Add/Edit Employee SSN
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
         <table border="0" width="100%" cellpadding="2" cellspacing="0">

            <tr>
	         <td colspan=2>&nbsp;</td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="display_emp_ssn.php"><font face="Verdana" size="4" color="#000080">View Employee ID - SSN List</font></a>
               </td>
            </tr>
            <tr>
	         <td colspan=2>&nbsp;</td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="add_emp_ssn.php"><font face="Verdana" size="4" color="#000080">Add New Employee ID - SSN Record(s)</font></a>
               </td>
            </tr>

            <tr>
	         <td colspan=2>&nbsp;</td>
            </tr>
            <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="edit_emp_ssn.php"><font face="Verdana" size="4" color="#000080">Edit Employee SSN</font></a>
               </td>
            </tr>


	</table>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />
<? include("pow_footer.php"); ?>
