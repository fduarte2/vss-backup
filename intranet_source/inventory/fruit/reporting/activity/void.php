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

<!-- Activity - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form

      date1 = x.start_date.value
      date2 = x.end_date.value

      if (date1 == "" || date2 == "") {
	 alert("You need to enter an Order Number or an Activity Date to view the Activity Report!");
         return false
      }
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Void Activity Report</font>
         <br />
	 <hr>
      </td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td>
	 <font size="2" face="Verdana">
	 | <a href="../">Home</a>
         </font>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
	   printf("Please enter Start Date and End Date to view the Activity Report.");
      ?>
         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Start Date:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="get" action="void_report.php" onsubmit="return validate_form()">
                 <input type="text" name="start_date" size="22" value="<? echo date('m/d/Y'); ?>">
                  <a href="javascript:show_calendar('report_form.start_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="images
/show-calendar.gif" width=24 height=22 border=0 /></a>

               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right"><font size="2" face="Verdana">Date:</font></td>
	       <td align="left">
	          <input type="text" name="end_date" size="22" value="<? echo date('m/d/Y'); ?>">
                  <a href="javascript:show_calendar('report_form.end_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>
	       </td>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="View the Report">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
