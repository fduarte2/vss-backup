<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Holmen Freight Charge Report";
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
      x = document.mod_form
                                                                                                 
      start_date = x.start_date.value
      end_date = x.end_date.value
                                                                                                 
      if (start_date == "") {
	alert("Please enter the Start Date!")
	return false
      }

      if (end_date == "") {
	alert("Please enter the End Date!")
	return false
      }

      return true
   }
</script>
                                                                                               
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
   	    <tr>
      	       <td width="1%">&nbsp;</td>
      	       <td>
	    	  <font size="5" face="Verdana" color="#0066CC">Holmen Freight Charge Report</font>
	    	  <hr>
      	       </td>
   	    </tr>
	 </table>


	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td valign="top" width="70%">
	           <font size="2" face="Verdana">
		   Please enter the shipping date range to view the report.<br /><br />
   <form name="mod_form" action="freight_charge_print.php" method="Post" onsubmit="return validate_form()">
   <table width="65%" bgcolor="#f0f0f0" border="0" cellpadding="2" cellspacing="4">
      <tr>
  	 <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
         <td width="5%">&nbsp;</td>
         <td width="25%" align="right" valign="top">
            <font size="2" face="Verdana">Billing Status:</b></font></td>
         <td width="65%" align="left">
            <select name="status">
               <option value="all">All</option>
               <option value="pending">Pending Shipments</option>
               <option value="reconciled">Reconciled Shipments</option>
            </select>
         <td width="5%">&nbsp;</td>
      </tr>
       <tr>
	 <td width="5%">&nbsp;</td>
         <td width="25%" align="right" nowrap><font size="2" face="Verdana">Start Date:</font></td>
	 <td width="65%" align="left" nowrap>
	    <input type="text" name="start_date" size="15" maxlength="15" 
                   value="<?= date('m/d/Y', mktime(0, 0, 0, date("m") - 1, 1, date("Y"))); ?>">
	    <a href="javascript:show_calendar('mod_form.start_date');" 
	       onmouseover="window.status='Date Picker';return true;" 
               onmouseout="window.status='';return true;">
	       <img src="/images/show-calendar.gif" width=24 height=22 border=0>
	    </a>
	 </td>
	 <td width="5%">&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" nowrap><font size="2" face="Verdana">End Date:</font></td>
	 <td align="left" nowrap>
	    <input type="text" name="end_date" size="15" maxlength="15" 
                   value="<?= date('m/d/Y', mktime(0, 0, 0, date("m"), 0, date("Y"))); ?>">
	    <a href="javascript:show_calendar('mod_form.end_date');" 
	       onmouseover="window.status='Date Picker';return true;" 
	       onmouseout="window.status='';return true;">
	       <img src="/images/show-calendar.gif" width=24 height=22 border=0>
	    </a>
         </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
  	 <td colspan="4" height="8"></td>
      </tr>
      <tr>
	 <td colspan="4" align="center">
	    <table>
	       <tr>
		  <td width="35%" align="right"> 
		     <input type="Submit" value="View Report">
		  </td>
 		  <td width="15%">&nbsp;</td>
		  <td width="50%" align="left">
		      <input type="Reset" value="Reset">
		  </td>
    	       </tr>
	    </table>		
   	 </td>
      </tr>
      <tr>
  	 <td colspan="4">&nbsp;</td>
      </tr>
   </table>
</form>
		  </font></p>
	       </td>
	    </tr>
         </table>
	 <?
	    include("pow_footer.php");
	 ?>
