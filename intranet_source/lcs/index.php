<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "LCS System";
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
	    <font size="5" face="Verdana" color="#0066CC">LCS Applications
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<!-- top table and side image !-->
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>From here you can access LCS applications.</b></font>
         </p>
         <table border="0" width="95%" cellpadding="0" cellspacing="2">
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="http://dspc-260/qqest/Login/login.asp">LCS Online</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="laborTicket.php">Labor Ticket</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="../hr/hires/weekly_hires.php">Weekly Hires</a></font>
               </td>
            </tr>
            <tr>
               <td colspan="2" height="10"></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%">
                 <font face="Verdana" size="2">
                  <a href="SUPV_timeoff_summ.php">Supervisor Paid Time Off</a></font>
               </td>
            </tr>

	 </table>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>

<!-- bottom table !-->
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="99%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>Upload Information:<br>All three cubes are uploaded ONLY once a day and ready for use at 4:00 AM.<br>However, to make it into the cube, you need to enter data by the following cutoff times:</b></font>
         </p>
         <table border="0" width="95%" cellpadding="0" cellspacing="2">
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%" colspan="3">
                 <font face="Verdana" size="2"><a href="http://dspc-s16/director/olap/lcs.php">LCS Hourly Detail</a></font>
               </td>
            </tr>
            <tr>
               <td width="10%" height="10">&nbsp;</td>
			   <td width="15%">&nbsp;</td>
			   <td width="25%"><font face="Verdana" size="2">Data Entry Deadline:</font></td>
			   <td><font face="Verdana" size="2">12:30 AM</font></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%" colspan="3">
                 <font face="Verdana" size="2"><a href="http://dspc-s16/director/olap/productivity.php">Productivity Detail</a></font>
               </td>
            </tr>
            <tr>
               <td width="10%" height="10">&nbsp;</td>
			   <td width="15%">&nbsp;</td>
			   <td width="25%"><font face="Verdana" size="2">Data Entry Deadline:</font></td>
			   <td><font face="Verdana" size="2">2:00 AM</font></td>
            </tr>
            <tr>
               <td valign="middle" width="10%"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left" width="90%" colspan="3">
                 <font face="Verdana" size="2"><a href="http://dspc-s16/director/olap/prod_sum.php">Productivity Summary</a></font>
               </td>
            </tr>
            <tr>
               <td width="10%" height="10">&nbsp;</td>
			   <td width="15%">&nbsp;</td>
			   <td width="25%"><font face="Verdana" size="2">Data Entry Deadline:</font></td>
			   <td><font face="Verdana" size="2">2:00 AM</font></td>
            </tr>
            <tr>
               <td colspan="4" height="10"></td>
            </tr>

	 </table></td>
   </tr>

   
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
