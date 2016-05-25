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
  include("utility.php");
  include("../claim_function.php");

?>

<!-- Prebill Update Report - Main Page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fruit Claim Report</font>
         <br />
         <hr>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<?
// make Oracle connection
$conn = ora_logon("SAG_OWNER@BNI", "SAG");
$cursor = ora_open($conn);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <table align="left" width="90%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="25%" align="right" valign="top">
	          <font size="2" face="Verdana">Customer:</font></td>
	       <td width="50%" align="left">
	       <form method="Post" name="report_form" action="report_print.php" method="Post"> 
	          <select name="customer">
	             <option value="">Please select the customer</option>
	       <?
//                  $customer = getCustomerList($cursor, "Y");
	          $customer = getClaimCustomerList($cursor, "RF");
         	  while (list($ora_customer_id, $customer_name) = each($customer)) {
			printf("<option value=\"%s,%s\">%s - %s</option>\n", $ora_customer_id, $customer_name, $customer_name,$ora_customer_id);

//                  while (list($customer_name, $customer_id) = each($customer)) {
//		    printf("<option value=\"%s,%s\">%s</option>", $customer_id, $customer_name, $customer_name);
		  }
	       ?>
	          </select>
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
<!--
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
-->
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Claim Type:</font></td>
               <td align="left">
                  <select name="claim_type">
                     <option value="">All Claim types</option>
                     <option value="Warehouse Damage">Warehouse Damage</option>
                     <option value="Damage">Damage</option>
                     <option value="Mis-Shipment">Mis-Shipment</option>
                     <option value="Missing">Missing</option>
                  </select>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Claim Handle Type:</font></td>
               <td align="left">
                  <select name="ispct">
<!--                     <option value="none">All types</option> !-->
                     <option value="Y">Chilean 68%</option>
                     <option value="N">Chilean 100%</option>
                     <option value="CHILEANTRUCK">Chilean Trucks (100%)</option>
                     <option value="ARG FRUIT">Argentine Fruit</option>
                     <option value="CLEMENTINES">Clementine</option>
                     <option value="BRAZILIAN">Brazilian</option>
                     <option value="PERUVIAN">Peruvian</option>
                  </select>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Status:</font></td>
               <td align="left">
                  <select name="status">
                     <option value="">All Claims</option>
                     <option value="O">Open Claims</option>
                     <option value="C">Closed Claims</option>
                  </select>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Order By:</font></td>
               <td align="left">
                  <select name="order_by">
					 <option value=" invoice_num asc ">Invoice Number</option>
                     <option value=" invoice_date desc ">Claim Date</option>
                     <option value=" customer_name ">Customer</option>
                     <option value=" prod ">Commodity</option>
					 <option value=" h.entry_date asc ">Entry Date</option>
                  </select>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Season:</font></td>
               <td align="left">
                  <select name="season">
                     <option value="">All</option>
<?
			$sql = "SELECT DISTINCT SEASON FROM CLAIM_HEADER_RF ORDER BY SEASON";
			$statement = ora_parse($cursor, $sql);
			ora_exec($cursor);
			while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
                     <option value="<? echo $row['SEASON']; ?>"><? echo $row['SEASON']; ?></option>
<?
			}
?>
                  </select>
               <td>&nbsp;</td>
            </tr>

	    <tr>
	       <td>&nbsp;</td>
	       <td align="right">
	          <font size="2" face="Verdana">Start Date:</font></td>
	       <td align="left">
	          <input type="text" name="start_date" size="20" maxlength="20" value="">
		  <a href="javascript:show_calendar('report_form.start_date');" 
		     onmouseover="window.status='Date Picker';return true;" 
	             onmouseout="window.status='';return true;"> 
		     <img src="../images/show-calendar.gif" width=25 height=22 border=0 />
		  </a>
               </td>
               <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right">
	          <font size="2" face="Verdana">End Date:</font></td>
	       <td align="left" valign="top">
	          <input type="text" name="end_date" size="20" maxlength="20" value="">
		  <a href="javascript:show_calendar('report_form.end_date');" 
		     onmouseover="window.status='Date Picker';return true;" 
        	     onmouseout="window.status='';return true;"> 
		     <img src="../images/show-calendar.gif" width=25 height=22 border=0 />
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

<?
include("pow_footer.php"); 
?>
