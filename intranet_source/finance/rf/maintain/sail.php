<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF Sail Ship";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form
      date1 = x.free_date.value

      if (date1 == "") {
        alert("You need to enter the Ship Sailing Date!");
        return false
      }
      else{
        answer = confirm("Are you sure you want to set the Ship Sailing Date?")
        return answer
      }
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Sail Vessel for RF Storage</font>
         <br />
	 <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
     <td width="1%">&nbsp;</td>
     <td valign="top" width="70%">
<?
  if($success == 1){
    printf("You have set free time for $vessel Successfully!<br />");
  }
  if($success == 2){
    printf("Error setting free time for $vessel<br />");
    if($reason == "FreeTime" || $reason == ""){
     printf("<b>Free Time not Found for Shipping Line!</b><br />");
    }
    if($reason == "ShippingLine"){
     printf("<b>Shipping Line not defined for Vessel!</b><br />");
    }
  }
?>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
	 <font size="2" face="Verdana">
	   Select a Vessel to Sail (Note that vessels listed here have <b>not</b> been sailed yet).
           Also enter the <b>date the vessel sailed</b> (Note that the Free Days will be <b>added</b> to this date).
           <br /><br />
         </font>
	 <table width="90%" align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Vessel:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="post" action="sail_process.php" onsubmit="return validate_form()">
                 <select name="vessel">
                   <option value="">Please Select Vessel</option>
<?
                  $year = date('Y');
                  $year_1 = $year + 1;
                  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
                  $cursor = ora_open($conn);
                  $ora_sql = "select lr_num, vessel_name from vessel_profile where free_time_end is null and ((ship_prefix = 'CHILEAN' or ship_prefix = 'CLEMENTINES') or (lr_num like '$year%' and lr_num <> '$year" . "000') or (lr_num like '$year_1%' and lr_num <> '$year_1" . "000') or (lr_num like '1004%' and lr_num != '1004000')) order by lr_num desc";
                  $statement = ora_parse($cursor, $ora_sql);
                  ora_exec($cursor);

                  while (ora_fetch($cursor)){
                    $lr_num_ora = ora_getcolumn($cursor, 0);
                    $ship_name = ora_getcolumn($cursor, 1);
                    printf("<option value=\"%s\">%s - %s</option>", $lr_num_ora, $lr_num_ora, $ship_name);
                  }

                  // close Oracle connection
                  ora_close($cursor);
?>
                 </select>
              </td>
            </tr>
            <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right">
	          <font size="2" face="Verdana">Date:</font></td>
	       <td width="55%" align="left">
                 <input type="text" name="free_date" size="15" value="<? echo date('m/d/Y'); ?>">
                  <a href="javascript:show_calendar('report_form.free_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;">
		  <img src="images/show-calendar.gif" width=24 height=22 border=0 /></a>

               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4" height="8"></td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value=" Set Free Time ">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
      <td width="40%" align="left" valign="top">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
   </tr>
</table>
<? include("pow_footer.php"); ?>
