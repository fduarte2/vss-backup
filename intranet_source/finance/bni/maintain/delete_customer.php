<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - BNI CUSTOMER SYSTEM";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

   include("defines.php");
   include("connect.php");
   $conn = ora_logon("SAG_OWNER@$bni", "SAG");
   $cursor = ora_open($conn);
   $cursor1 = ora_open($conn);

?>

<script type="text/javascript">

   function validate_mod(){
    var ans = confirm("Are you sure to delete this customer information");

    if (ans == true)
       return true;
    else
       return false;

   }
</script>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">Delete Customers
                  </font>
                  <hr>
               </td>
            </tr>
         </table>

         <table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
                <td width="1%">&nbsp;</td>
                <td valign="top">
                   <font size="2" face="Verdana">
               <?
                  include("../bni_links.php"); 
                  $cust = $HTTP_COOKIE_VARS["cust"];
               ?>

Please select a Customer from the list.<br /><br />
<table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
      <tr>
         <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
         <td width="5%">&nbsp;</td>
         <td width="25%" align="right" valign="top">
            <font size="2" face="Verdana">Customer Name:</font></td>
         <td width="75%" align="left" valign="middle">
            <form action="set_delete_customer.php" name="cust_form" method="Post">
               <select name="cust" onchange="document.cust_form.submit(this.form)" >
               <option value="">Select Customer</option>
        <?
          $sql = "select customer_id,customer_name from customer_profile order by customer_id";
          $statement = ora_parse($cursor, $sql);
          ora_exec($cursor);

         while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC))
         {
            $cust_id = $row['CUSTOMER_ID'];
            $cust_name = $row['CUSTOMER_NAME'];
            if($cust_id == $cust){
              printf("<option value=\"%s\" selected=\"true\">%s - %s</option>", $cust_id, $cust_name, $cust_id);
            }
            else{
              printf("<option value=\"%s\">%s - %s</option>", $cust_id, $cust_name, $cust_id);
            }
         }

        ?>

         </tr>
      <tr>
         <td colspan="4">&nbsp;</td>
      </tr>
   </table>
</form>

<?
          if($cust != "") {
            $sql = "select * from customer_profile where customer_id = '" . $cust . "'";
            $statement = ora_parse($cursor, $sql);
            ora_exec($cursor);

            ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

            $comm_code = $row['COUNTRY_CODE'];
           
?>

   <a name="enter" />
   Customer Detail:<br />

   <form name="mod_form" action="customer_process.php" method="Post" onsubmit="return validate_mod()">
   <input type="hidden" name="input" value="delete">
   <input type="hidden" name="cust" value="<? echo $cust ?>">
<table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
      <tr>
         <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
         <td width="5%">&nbsp;</td>
         <td width="25%" align="right" valign="top">
            <font size="2" face="Verdana">Customer Id:</font></td>
         <td width="65%" align="left">
         <input type="textbox" name="customer_id" value="<? echo $row['CUSTOMER_ID'] ?>" size="10" maxlength="20">
         </td>
         <td width="5%">&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Customer Name:</font></td>
         <td align="left">
            <input type="textbox" name="customer_name" value="<? echo $row['CUSTOMER_NAME'] ?>" size="20" maxlength="20">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Contact</font></td>
         <td align="left">
            <input type="textbox" name="contact" value="<? echo $row['CUSTOMER_CONTACT'] ?>" size="20" maxlength="20">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Address:</font></td>
         <td align="left">
            <input type="textbox" name="address" value="<? echo $row['CUSTOMER_ADDRESS1'] ?>" size="50" maxlength="50">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Address Line 2:</font></td>
         <td align="left">
            <input type="textbox" name="address2" value="<? echo $row['CUSTOMER_ADDRESS2'] ?>" size="50" maxlength="50">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">City:</font></td>
         <td align="left">
            <input type="textbox" name="city" value="<? echo $row['CUSTOMER_CITY'] ?>" size="20" maxlength="20">
         </td>
      <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">State:</font></td>
         <td align="left">
            <input type="textbox" name="state" value="<? echo $row['CUSTOMER_STATE'] ?>" size="20" maxlength="20">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Zip Code:</font></td>
          <td align="left">
            <input type="textbox" name="zip" value="<? echo $row['CUSTOMER_ZIP'] ?>" size="20" maxlength="20">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Country:</font></td>
         <td align="left">
            <select name="country_code">
            <option value="">Select Country</option>
        
        <?

         $sql = "select * from country order by country_code";
         $statement = ora_parse($cursor, $sql);
         ora_exec($cursor);

         while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC))
         {
            $c_code = $row['COUNTRY_CODE'];
            $c_name = $row['COUNTRY_NAME'];
            if($c_code == $comm_code){
              printf("<option value=\"%s\" selected=\"true\">%s - %s</option>", $c_code, $c_name, $c_code);
            }
            else{
              printf("<option value=\"%s\">%s - %s</option>", $c_code, $c_name, $c_code);
            }
         }
       ?>
         </select>
         </td>
       </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Fax Number:</font></td>
         <td align="left">
            <input type="textbox" name="fax" value="<? echo $row['CUSTOMER_FAX'] ?>" size="20" maxlength="20">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Phone Number:</font></td>
<td align="left">
            <input type="textbox" name="phone" value="<? echo $row['CUSTOMER_PHONE'] ?>" size="20" maxlength="20">
          &nbsp;&nbsp;
            <font size="2" face="Verdana">Ext:</font>
            <input type="textbox" name="ext" value="<? echo $row['CUSTOMER_CONTACT_EXT'] ?>" size="15" maxlength="20">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">E-mail Address:</font></td>
         <td align="left">
            <input type="textbox" name="email" value="<? echo $row['CUSTOMER_EMAIL'] ?>" size="20" maxlength="50">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Status:</font></td>
         <td align="left">
            <select name="status">
           <?
              if ($row['CUSTOMER_STATUS'] == 'ACTIVE') {
           ?>
             <option value="ACTIVE" selected = "true">ACTIVE</option>
             <option value="HOLD">HOLD</option>
           <?
             } else {
           ?>   
             <option value="ACTIVE">ACTIVE</option>
             <option value="HOLD" selected = "true">HOLD</option>
           <? } ?>
           </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td colspan="2" align="center">
            <table>
               <tr>
                  <td width="40%" align="right">
                     <input type="Submit" value="Delete">&nbsp;&nbsp;
                  </td>
                  <td width="5%">&nbsp;</td>
                  <td width="55%" align="left">
                      <input type="Reset" value="Reset Form ">
                  </td>
               </tr>
            </table>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td colspan="4">&nbsp;</td>
      </tr>
   </table>
</form>


<?
   }
?>
    </font></p>
     </td>
     </tr>
   </table>

<?
  ora_close($cursor);
  ora_close($cursor1);
  ora_logoff($conn);

  include("pow_footer.php");
?>

