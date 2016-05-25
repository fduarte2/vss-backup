<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Update Pre-Invoices";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<!-- Prebill Update Report - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Pre-Invoice Update Report</font>
         <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<?
// database parameters
include ("connect.php");

// make Oracle connection
$conn = ora_logon("SAG_OWNER@$bni", "SAG");
$cursor = ora_open($conn);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
         <font size="2" face="Verdana"><p>
	 Specify any of the following fields to narrow down your search for prebill update information.  Then click on View Report to see the report. Note that the more you specify, the faster the report will be brought up. 
         </p></font>
	 <table align="left" width="90%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="30%" align="right" valign="top">
	          <font size="2" face="Verdana">Customer:</font></td>
	       <td width="50%" align="left">
	       <form method="Post" name="report_form" action="report.php" method="Post"> 
	          <select name="customer">
	             <option value="">Please select the customer</option>
	       <?
 	          $stmt = "select customer_id, customer_name from customer_profile 
                              where customer_id >= 3 and customer_status = 'ACTIVE' order by customer_name";
 	          ora_parse($cursor, $stmt);
 	          ora_exec($cursor);

                  // create an associative array using customer_id as key and customer_name as value
                  $customer = array();

                  while (ora_fetch($cursor)){
   		    $customer_id = trim(ora_getcolumn($cursor, 0));
   		    $customer_name = trim(ora_getcolumn($cursor, 1));

		    // if the name doesn't have the id #, add to it
		    if (strspn($customer_name, "1234567890") == 0) {
		      $customer_name = $customer_id . "-" . $customer_name;
		    }

		    $customer[$customer_id] = $customer_name;
 	          }

                  // sort the customer array by key (ID's)
                  ksort($customer, SORT_NUMERIC);
                  reset($customer);

                  while (list($customer_id, $customer_name) = each($customer)) {
		    printf("<option value=\"%s,%s\">%s</option>", $customer_id, $customer_name, $customer_name);
		  }
	       ?>
	          </select>
               </td>
               <td width="15%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">Order By:</font></td>
	       <td align="left">
	          <select name="order_by">
	             <option value="change_time desc">Change Time</option>
	             <option value="billing_num desc, change_time desc">Billing #, Change time</option>
	             <option value="action asc, billing_type asc, billing_num desc">
	                Action, Billing Type, Billing #</option>
	             <option value="customer_id asc, billing_type asc, change_time desc">
	                Customer ID, Billing Type, Change_Time</option>
	          </select>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">Billing Type:</font></td>
	       <td align="left">
	          <select name="billing_type">
	             <option value="">Please select the billing type</option>
	       <?
	          $stmt = "select distinct billing_type from billing";
 	          ora_parse($cursor, $stmt);
 	          ora_exec($cursor);

                  while (ora_fetch($cursor)){
		    $billing_type = trim(ora_getcolumn($cursor, 0));
		    printf("<option value=\"%s\">%s</option>", $billing_type, $billing_type);
		  }
	       ?>
	          </select>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">Start Date:</font></td>
	       <td align="left">
	          <input type="text" name="start_date" size="20" maxlength="20" value="">
		  <a href="javascript:show_calendar('report_form.start_date');" 
		     onmouseover="window.status='Date Picker';return true;" 
	             onmouseout="window.status='';return true;"> 
		     <img src="images/show-calendar.gif" width=25 height=22 border=0 />
		  </a>
               </td>
               <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">End Date:</font></td>
	       <td align="left">
	          <input type="text" name="end_date" size="20" maxlength="20" value="">
		  <a href="javascript:show_calendar('report_form.end_date');" 
		     onmouseover="window.status='Date Picker';return true;" 
        	     onmouseout="window.status='';return true;"> 
		     <img src="images/show-calendar.gif" width=25 height=22 border=0 />
		  </a>
               </td>
               <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4" height="6"></td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="center" colspan="2">
		   <table>
		      <tr>
			 <td width="45%" align="right"> 
			    <input type="Submit" value="View Report">
			 </td>
			 <td width="20%">&nbsp;</td>
			 <td width="35%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
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
<?

// close Oracle connection
ora_close($cursor);
ora_logoff($conn);

?>
</table>

<? include("pow_footer.php"); ?>
