<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance - RF Storage";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  // Connect to RF
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$rf", "OWNER");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  // open cursors
  $cursor = ora_open($conn);         // general purpose

  // Find out which dates we need to run
  $stmt = "select to_char(cut_off_date, 'MM/DD/YYYY') as cut_off_date, to_char(cut_off_date, 'YYYYMMDD') formatted from storage_bill_log order by formatted desc";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor); 
  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

  // change the start date to be one day later
  $start_date = date('m/d/Y', mktime(0,0,0, date("m", strtotime($row['CUT_OFF_DATE'])), 
				     date("d", strtotime($row['CUT_OFF_DATE'])) + 1, 
				     date("Y", strtotime($row['CUT_OFF_DATE']))));

  $end_date = date('m/d/Y', mktime(0,0,0, date("m", strtotime($start_date)), 
				   date("d", strtotime($start_date)) + 6, 
				   date("Y", strtotime($start_date))));
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form
      start_date = x.start_date.value
      end_date = x.end_date.value

      if (start_date == "") {
        alert("Please set the Start Date!")
        return false
      }

      if (end_date == "") {
        alert("Please set the End Date!")
        return false
      }

      answer = confirm("Are you sure you want to run RF Storage?")

      if (answer == true) {
	return true
      } else {
	return false
      }
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Run RF Storage</font>
         <br />
	 <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana">
<?
   if($start > 0 && $end > 0){
     printf("<b>You have successfully run RF Storage prebill generator.  Please find prebills 
             with bill numbers starting at %s and ending on %s.</b>", $start, $end);
   } elseif ($start == "0" && $end == "0") {
     printf("<b>There is no storage bill to generate for this date range!</b>");
   } else {
     printf("Please select a date range to run RF Storage.  Note that all cargos that are due 
             to get charged in this date range will be processed.");
   }
?>
           <br /><br />
         </font>
	 <table width="90%" align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
            <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right">
	          <font size="2" face="Verdana">Start Date:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="post" action="rf_storage_process.php" onsubmit="return validate_form()">
                 <input type="text" name="start_date" size="15" value="<?= $start_date ?>">
                  <a href="javascript:show_calendar('report_form.start_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;">
		  <img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
            <tr>
	       <td>&nbsp;</td>
	       <td align="right">
	          <font size="2" face="Verdana">End Date:</font></td>
	       <td align="left">
                 <input type="text" name="end_date" size="15" value="<?= $end_date ?>">
                  <a href="javascript:show_calendar('report_form.end_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;">
		  <img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>
               </td>
               <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4" height="8"></td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="Begin Processing">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
      <td width="30%" align="left" valign="top">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
