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
  include("claim_function.php");
  include("connect.php");
  $bni_conn = ora_logon("SAG_OWNER@$bni", "SAG");
  if (!$bni_conn) {
     	printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     	printf("Please report to TS!");
        exit;
  }
  $cursor = ora_open($bni_conn);

  $system = $HTTP_POST_VARS[system];
  if ($system =="")
	$system = "CCDS";



  if ($HTTP_POST_VARS[type] =="add"){
        $cust = $HTTP_POST_VARS[customer];
        addClaimCustomer($cursor, $cust, $system);
  }else if ($HTTP_POST_VARS[type] =="remove" ){
        $sCust = $HTTP_POST_VARS[sCustomer];
        removeClaimCustomer($cursor, $sCust, $system);
  }
  $customer = getAllCustomerList($cursor);
  $sCustomer = getClaimCustomerList($cursor, $system);


?>
<script language="JavaScript">
function changeCustomer(type){
  document.customer.action = "customer_list.php";
  document.customer.type.value=type;
  document.customer.submit();
}
function changeSystem(){
  document.customer.submit();
}
</script>
<form action=""  method="Post" name="customer">
<input type="hidden" name="type" value="">
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
         <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
<!--
            <tr>
               <td width="20%">&nbsp;</td>
               <td width="30%">&nbsp;</td>
               <td width="15%">&nbsp;</td>
               <td width="35%">&nbsp;</td>
            </tr>
-->
	    <tr>	
	       <td colspan=4>
		   <font size="2" face="Verdana"><b>System:</b>
<!--		   	<input type=radio name=system value="CCDS" <?if ($system=="CCDS") echo "checked" ?> onClick="javascript:changeSystem()">CCDS&nbsp;&nbsp;&nbsp;&nbsp; !-->
		   	<input type=radio name=system value="RF" <?if ($system=="RF") echo "checked" ?> onClick="javascript:changeSystem()">FRUIT&nbsp;&nbsp;&nbsp;&nbsp;
<!--                   	<input type=radio name=system value="BNI" <?if ($system=="BNI") echo "checked" ?>onClick="javascript:changeSystem()">BNI !-->
		   </font>
	       </td>
	    </tr>
 
            <tr>
               <td align="left" valign="top">
                  <font size="2" face="Verdana">Available Customer List:</font></td>
	       </td>
	       <td>&nbsp;</td>
               <td align="left" valign="top">
                  <font size="2" face="Verdana">Your Customer List:</font></td>
	       </td>	
	    </tr>
	    <tr>
               <td align="left">
                  <select name="customer[]" multiple=true size=15 style="width:330px">
                     <? 
                          while (list($customer_id, $customer_name) = each($customer)) {
				if(array_key_exists($customer_id, $sCustomer) == false){

                                printf("<option value=\"%s,%s\">%s - %s</option>", $customer_id, $customer_name, $customer_name, $customer_id);
				}
                        //    printf("<option value=\"%s\">%s - %s</option>", $ora_customer_id, $customer_name, $ora_customer_id);
                           }
                     ?>
                  </select>
               </td>
               <td>
                	<input type=button name="add" value="  ->>  "  onclick="changeCustomer('add')">
                	<br />
                	<input type=button name="remove" value="  <<-  " onclick="changeCustomer('remove')">
	       </td> 	
               <td align="left">
                  <select name="sCustomer[]" multiple=true size=15 style="width:330px">
                     <? 
                          while (list($customer_id, $customer_name) = each($sCustomer)) {
                            printf("<option value=\"%s,%s\">%s - %s</option>", $customer_id,$customer_name, $customer_name, $customer_id);
                           }
                     ?>
                  </select>
               </td>
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

<?
ora_close($cursor);
ora_logoff($bni_conn); 
include("pow_footer.php"); 
?>
