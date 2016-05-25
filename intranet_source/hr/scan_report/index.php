<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Security Scan Report";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  $today = date('m/d/Y');
  $yesterday = date('m/d/Y',mktime(0,0,0,date("m"),date("d")-1,date("Y")));
?>

<script language="JaveScript">
function exportToExcel()
{
        //alert('export to excel');
}
</script>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Security Log Report
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
	    <font size="2" face="Verdana">Please select start date and end date.<br /><br /><br />

	 </p>
	 <p>
            <form action="scan_daily_report.php" method="post" name="scan">
	       Start Date: <input type="textbox" name="start_date" size=10 value="<? echo $yesterday; ?>"><a href="javascript:show_calendar('scan.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>&nbsp;&nbsp;&nbsp;&nbsp;6:00 PM<br />

              <br /> End Date:&nbsp;&nbsp;&nbsp;<input type="textbox" name="end_date" size=10 value="<? echo $today; ?>"><a href="javascript:show_calendar('scan.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>&nbsp;&nbsp;&nbsp;&nbsp;6:00 AM<br />
              
	      <br \><br \><br \>
	      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">&nbsp;&nbsp;&nbsp;<input type="reset" value=" Reset ">
              <br /><br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value="Export to Excel" name="export">

            </form>
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
