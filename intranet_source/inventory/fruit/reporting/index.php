<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Fruit Reports";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">RF Reports
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
	 <font size="2" face="Verdana"><b>Information Access:</font>
         <br /><br />
	 <table border="0" width="95%" cellpadding="2" cellspacing="0">
            <tr>
	       <td valign="middle" width="8%"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		  <a href="activity/index.php">
                     <font face="Verdana" size="2" color="#000080">Pallet Activity</font>
                  </a>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <a href="tag_audit/index.php">
                     <font face="Verdana" size="2" color="#000080">Tag Audit</font>
                  </a>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <a href="vessel_discharge/index.php">
                     <font face="Verdana" size="2" color="#000080">Vessel Discharge Report</font>
                  </a>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <a href="activity/void.php">
                     <font face="Verdana" size="2" color="#000080">Void Activity</font>
                  </a>
	       </td>
            </tr>
            <tr>
               <td colspan="2" height="6"></td>
            </tr>
	 </table>
      </td>
      <td width="1%">&nbsp;</td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
