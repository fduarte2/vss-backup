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
       x = document.mod_form
       name = x.customer_name.value
       var asless = false;
       if(name == ""){
        alert("You MUST have a Customer Name!");
       }
       else
         asless = true; 
       return asless
   }
</script>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">Modify Customers
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
            <form action="set_modify_customer.php" name="cust_form" method="Post">
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
   Replace values shown here with the correct values:<br />

   <form name="mod_form" action="customer_process.php" method="Post" onsubmit="return validate_mod()">
   <input type="hidden" name="user" value="<? echo $userdata['username']; ?>">
   <input type="hidden" name="input" value="modify">
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
         <input type="textbox" name="customer_id" readonly = "True" value="<? echo $row['CUSTOMER_ID'] ?>" size="5" maxlength="6">
         </td>
         <td width="5%">&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Customer Name:</font></td>
         <td align="left">
            <input type="textbox" name="customer_name" value="<? echo $row['CUSTOMER_NAME'] ?>" size="60" maxlength="60">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Contact</font></td>
         <td align="left">
            <input type="textbox" name="contact" value="<? echo $row['CUSTOMER_CONTACT'] ?>" size="30" maxlength="40">
         </td>
         <td>&nbsp;</td>
      </tr>
	  <tr>
		<td colspan="4">
			<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td colspan="2" align="center"><font size="2" face="Verdana"><b>Billing:</b></font></td>
					<td colspan="2" align="center"><font size="2" face="Verdana"><b>Claims (optional):</b></font></td>
				</tr>
				<tr>
					<td width="10%"><font size="2" face="Verdana">Address:</font></td>
					<td width="40%"><input type="textbox" name="address" size="40" maxlength="40" value="<? echo $row['CUSTOMER_ADDRESS1'] ?>" >
					<td width="10%"><font size="2" face="Verdana">Address:</font></td>
					<td width="40%"><input type="textbox" name="claim_address" size="40" maxlength="40" value="<? echo $row['CUSTOMER_CLAIM_ADDRESS1'] ?>">
				</tr>
				<tr>
					<td width="10%"><font size="2" face="Verdana">Address (2):</font></td>
					<td width="40%"><input type="textbox" name="address2" size="30" maxlength="40" value="<? echo $row['CUSTOMER_ADDRESS2'] ?>">
					<td width="10%"><font size="2" face="Verdana">Address (2):</font></td>
					<td width="40%"><input type="textbox" name="claim_address2" size="30" maxlength="40" value="<? echo $row['CUSTOMER_CLAIM_ADDRESS2'] ?>">
				</tr>
				<tr>
					<td width="10%"><font size="2" face="Verdana">City:</font></td>
					<td width="40%"><input type="textbox" name="city" size="20" maxlength="20" value="<? echo $row['CUSTOMER_CITY'] ?>">
					<td width="10%"><font size="2" face="Verdana">City:</font></td>
					<td width="40%"><input type="textbox" name="claim_city" size="20" maxlength="20" value="<? echo $row['CUSTOMER_CLAIM_CITY'] ?>">
				</tr>
				<tr>
					<td width="10%"><font size="2" face="Verdana">State:</font></td>
					<td width="40%"><input type="textbox" name="state" size="10" maxlength="10" value="<? echo $row['CUSTOMER_STATE'] ?>">
					<td width="10%"><font size="2" face="Verdana">State:</font></td>
					<td width="40%"><input type="textbox" name="claim_state" size="10" maxlength="10" value="<? echo $row['CUSTOMER_CLAIM_STATE'] ?>">
				</tr>
				<tr>
					<td width="10%"><font size="2" face="Verdana">Zip:</font></td>
					<td width="40%"><input type="textbox" name="zip" size="10" maxlength="13" value="<? echo $row['CUSTOMER_ZIP'] ?>">
					<td width="10%"><font size="2" face="Verdana">Zip:</font></td>
					<td width="40%"><input type="textbox" name="claim_zip" size="10" maxlength="13" value="<? echo $row['CUSTOMER_CLAIM_ZIP'] ?>">
				</tr>
			</table>
		</td>
	</tr>

	  
	  
	  
<!--	  
	  <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Address:</font></td>
         <td align="left">
            <input type="textbox" name="address" value="<? echo $row['CUSTOMER_ADDRESS1'] ?>" size="40" maxlength="40">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Address Line 2:</font></td>
         <td align="left">
            <input type="textbox" name="address2" value="<? echo $row['CUSTOMER_ADDRESS2'] ?>" size="40" maxlength="40">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">CLAIMS Address:</font></td>
         <td align="left">
            <input type="textbox" name="claim_address" value="<? echo $row['CUSTOMER_CLAIM_ADDRESS1'] ?>" size="30" maxlength="40">&nbsp;&nbsp;<font size="2" face="Verdana">(Optional)</font>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">CLAIMS Address Line 2:</font></td>
         <td align="left">
            <input type="textbox" name="claim_address2" value="<? echo $row['CUSTOMER_CLAIM_ADDRESS2'] ?>" size="30" maxlength="40">&nbsp;&nbsp;<font size="2" face="Verdana">(Optional)</font>
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
            <input type="textbox" name="state" value="<? echo $row['CUSTOMER_STATE'] ?>" size="10" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Zip Code:</font></td>
          <td align="left">
            <input type="textbox" name="zip" value="<? echo $row['CUSTOMER_ZIP'] ?>" size="10" maxlength="13">
         </td>
         <td>&nbsp;</td>
      </tr>
!-->
	  
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
            <input type="textbox" name="ext" value="<? echo $row['CUSTOMER_CONTACT_EXT'] ?>" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">E-mail Address:</font></td>
         <td align="left">
            <input type="textbox" name="email" value="<? echo $row['CUSTOMER_EMAIL'] ?>" size="50" maxlength="80">
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
             <option value="HOLD">INACTIVE</option>
           <?
             } else {
           ?>   
             <option value="ACTIVE">ACTIVE</option>
             <option value="HOLD" selected = "true">INACTIVE</option>
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
                     <input type="Submit" value="Save Info">&nbsp;&nbsp;
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

