<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CCDS system");
    include("pow_footer.php");
    exit;
  }
   $user = $userdata['username'];
   $sDate = $HTTP_COOKIE_VARS[sDate];
   $eDate = $HTTP_COOKIE_VARS[eDate];
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Warehouse Report
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
	 <p>
	    <font size="2" face="Verdana">Select start and end date.<br />
	 </p>
	 <p>
            <form action="warehouse_export.php" method="post" name="warehouse">
              Start Date: <input type="textbox" name="sDate" size=15 value="<? echo $sDate; ?>"><a href="javascript:show_calendar('warehouse.sDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a><br />
	      <br /> End Date:&nbsp;&nbsp;&nbsp;<input type="textbox" name="eDate" size=15 value="<? echo $eDate; ?>"><a href="javascript:show_calendar('warehouse.eDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a><br />

	      <br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">&nbsp;&nbsp;&nbsp;<input type="submit" name = "reset" value="Reset">
<!--	      <br /><br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name ="export" value="Export to Excel">	
-->            </form>
         </p>
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
