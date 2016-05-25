<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Open Claim Report";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }

  include("billing_functions.php");
?>

<!-- Prebill Update Report - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Open Claim Report</font>
         <br />
         <hr>
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
	 Specify any of the following fields to narrow down your search for claim information.  Then click on View Report to see the report. Note that the more you specify, the faster the report will be brought up. 
         </p></font>
	 <table align="left" width="90%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="25%" align="right" valign="top">
	          <font size="2" face="Verdana">Customer:</font></td>
	       <td width="50%" align="left">
	       <form method="Post" name="report_form" action="reports/report_history_process.php" method="Post"> 
	          <select name="customer">
	             <option value="">Please select the customer</option>
	       <?
                  $customer = getCustomerList($cursor, "Y");
                  while (list($customer_name, $customer_id) = each($customer)) {
		    printf("<option value=\"%s,%s\">%s</option>", $customer_id, $customer_name, $customer_name);
		  }
	       ?>
	          </select>
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">Order By:</font></td>
	       <td align="left">
	          <select name="order_by">
	             <option value="claim_date desc">Claim Date</option>
	             <option value="claim_id, change_time desc">Claim ID, Change time</option>
	             <option value="system asc, claim_id">
	                System (Commodity), Claim ID</option>
	             <option value="customer_id asc, system, claim_date desc">
	                Customer ID, System (Commodity), Claim Date</option>
	             <option value="customer_id asc, amount, claim_date desc">
	                Customer ID, Claim Amount, Claim Date</option>
	          </select>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">System (Commodity):</font></td>
	       <td align="left">
	          <select name="system">
	             <option value="">Please select the Claim System</option>
		     <option value="BNI">BNI (Juice)</option>
		     <option value="RF">RF (Fruit)</option>
                     <option value="RF-OPPY">RF (Oppenhimer)</option>
		     <option value="CCDS">CCDS (Meat)</option>
	          </select>
	       <td>&nbsp;</td>
	    </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Claim Type:</font></td>
               <td align="left">
                  <select name="claim_type">
                     <option value="">All Claim types</option>
                     <option value="Damage">Damage</option>
                     <option value="USDA">USDA Rejection</option>
                     <option value="Mis-Shipment">Mis-Shipment</option>
                     <option value="Missing">Missing</option>
                  </select>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Claim Letter Type:</font></td>
               <td align="left">
                  <select name="letter_type">
                     <option value="">All Claim Letter types</option>
                     <option value="Customer">Customer</option>
                     <option value="Split">Split (Shipping Line / POW)</option>
                     <option value="Shipper">Shipper</option>
                     <option value="RShipper">Secondary Claim</option>
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
