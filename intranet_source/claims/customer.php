<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Customer List";
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

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Edit Customer List
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
	 <p align="left">
	    <font size="2" face="Verdana">Select Customers in one list and hit
the arrow to push them into the other list.<br />
         <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
            <tr>
               <td width="20%">&nbsp;</td>
               <td width="30%">&nbsp;</td>
               <td width="15%">&nbsp;</td>
               <td width="35%">&nbsp;</td>
            </tr>
               <form action="customer_add.php" method="Post" name="cust_form">
               <input type="hidden" name="action" value="add">
            <tr>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Available:</font></td>
               <td align="left">
                  <select name="customer_id">
                     <option value="" select="selected">Select Customer</option>
                     <? 
                          $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
                          if (!$bni_conn) {
                             printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
                             printf("Please report to TS!");
                             exit;
                          }
                          $cursor = ora_open($bni_conn);
                          $customer = getCustomerList($cursor, "N");
                          while (list($customer_name, $ora_customer_id) = each($customer)) {
                            printf("<option value=\"%s\">%s - %s</option>", $ora_customer_id, $customer_name, $ora_customer_id);
                           }
                     ?>
                  </select>
               </td>
               <td><input type="Submit" value="->>">
            </tr>
            </form>
              <form action="customer_add.php" method="Post" name="cust_form2">
              <input type="hidden" name="action" value="del">
            <tr>
               <td align="right" valign="top">
                  <font size="2" face="Verdana">Your List:</font></td>
               <td align="left">
                  <select name="customer_id">
                     <option value="" select="selected">Select Customer</option>
                     <? 
                          $customer = getCustomerList($cursor, "Y");
                          while (list($customer_name, $ora_customer_id) = each($customer)) {
                            printf("<option value=\"%s\">%s - %s</option>", $ora_customer_id, $customer_name, $ora_customer_id);
                           }
                     ?>
                  </select>
               </td>
               <td><input type="Submit" value="<<-">
            </tr>
                 </form>
         </table>
         </p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
