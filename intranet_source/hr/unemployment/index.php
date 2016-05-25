<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";


  // Provides header / leftnav
  include("pow_header.php");
  
  // Include function library
  //include("unemployment_fnc.php");
  
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
	    <font size="5" face="Verdana" color="#0066CC">Unemployment Claims
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
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="hire_cutoff.php"><font face="Verdana" size="4" color="#000080">Daily Cutoff</font></a>
               </td>
            </tr>
            <tr>
                <td colspan=2>&nbsp;</td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="claim_request.php"><font face="Verdana" size="4" color="#000080">Request for Unemployment Claim Form</font></a>
               </td>
            </tr>

	    <tr>
		<td colspan=2>&nbsp;</td>
	    </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="claim.php"><font face="Verdana" size="4" color="#000080">Unemployment Claim Form</font></a>
               </td>
            </tr>
            <tr>
                <td colspan=2>&nbsp;</td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="update_emp_info.php"><font face="Verdana" size="4" color="#000080">Update Employee Information</font></a>
               </td>
            </tr>
          <tr>
                <td colspan=2>&nbsp;</td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="display_index.php"><font face="Verdana" size="4" color="#000080">View Employee Information</font></a>
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

<? printf($_GET['filename'])?>
<? include("pow_footer.php"); ?>
