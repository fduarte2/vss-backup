<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Reports";
  $area_type = "CLAI";

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
	    <font size="5" face="Verdana" color="#0066CC">Fruit Claim
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
                  <a href="rf_claim.php"><font face="Verdana" size="2" color="#000080">Claim Entry</font></a>
               </td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="report.php"><font face="Verdana" size="2" color="#000080">Report</font></a>
               </td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="chilean.php"><font face="Verdana" size="2" color="#000080">Fruit Claim Summary</font></a>
               </td>
            </tr>
<!--
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="report2.php"><font face="Verdana" size="2" color="#000080">Report2</font></a>
               </td>
            </tr>
-->
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="../customer_list.php"><font face="Verdana" size="2" color="#000080">Customer Short List</font></a>
               </td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="pallet_info.php"><font face="Verdana" size="2" color="#000080">Tag Audit</font></a>
               </td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="OSD.php"><font face="Verdana" size="2" color="#000080">OS & D Report</font></a>
               </td>
            </tr>
            <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="missing_pallet.php"><font face="Verdana" size="2" color="#000080">RF system Missing Pallet Report (All Season)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="missing_pallet_date_select.php">(Date Specific)</a></font>
               </td>
            </tr>

	<? if ($user == "ffitzgerald" || $user =="rwang"){ ?>
 <!--           <tr>
               <td valign="middle" align ="center"><img src="/images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <a href="reopen_claim.php"><font face="Verdana" size="2" color="#000080">ReOpen Claim</font></a>
               </td>
            </tr>
-->
	<? }  ?>
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
