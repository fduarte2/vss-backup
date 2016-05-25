<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Update Invoices";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  // Make Oracle Database connection
  include("connect.php");

 $bni_conn = ora_logon("SAG_OWNER@$bni", "SAG");
 if (!$bni_conn) {
   printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($bni_conn));
   printf("Please report to TS!");
   exit;
 }

 // open cursors
 $bni_cursor = ora_open($bni_conn);
 $bni_cursor1 = ora_open($bni_conn);

 // connect to PROD
 $prod_conn = ora_logon("APPS@$prod", "APPS");
 $prod_cursor = ora_open($prod_conn);

?>

<!-- Edit Invoices - Main page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">

   function validate_invoice_num()
   {
      x = document.invoice_form

      invoice_free = x.invoice_free.value
      invoice_select = x.invoice_select.value

      // No invoice # is entered or selected
      if(invoice_free == "" && invoice_select == ""){
	 alert("Please either enter or select an invoice number to update!")
         return false
      }

      if(invoice_free != "" && invoice_select != ""){
	 alert("You cannot enter and select the invoice number at the same time! \nPlease reset the form and try it again.")
         return false
      }

      return true
   }


   function validate_update()
   {
      x = document.update_form
      invoice_num = x.invoice_num.value

      // check on the data inputs
      for (var i=0; i<x['billing_num[]'].length; ++i) {
	// check commodity code
	if (x['commodity_code[]'][i].value == "") {
	  alert("Please select the commodity code for billing # " + x['billing_num[]'][i].value + "!")
	  return false
	}
	
	// check service code
	if (x['service_code[]'][i].value == "") {
	  alert("Please select the service code for billing # " + x['billing_num[]'][i].value + "!")
	  return false
	}
	
	// check gl code
	if (x['gl_code[]'][i].value == "") {
	  alert("Please select the GL code for billing # " + x['billing_num[]'][i].value + "!")
	  return false
	}

	// check asset code
	if (x['asset_code[]'][i].value == "") {
	  alert("Please select the asset code for billing # " + x['billing_num[]'][i].value + "!")
	  return false
	}
      }
      
      // ask for reason of change
      x.reason.value = prompt("Please input the reason for updating the invoice.", "")
      reason = x.reason.value
      while (reason == "") {
	 alert("You need to input the change reason!")
	 x.reason.value = prompt("Please input the reason for updating the invoice.", "")
	 reason = x.reason.value
      }
      
      if (reason.length > 50) {
	 alert("Please do not input more than 50 characters for change reason!")
	 return false
      }

      // ask for user confirmation
      answer = confirm("Are you sure you want to save the changes you made for Invoice # " + invoice_num + ". \
			\nClick OK to confirm or Cancel to cancel.")
      return answer
   }

</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Update Invoices</font>
         <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<!-- Invoice Selection -->
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <font size="2" face="Verdana">From here you can update invoices that are not imported into Oracle Financial by either entering or selecting the invoice number.</font>
	 <br /><br />
    	 <table width="90%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td width="20%"></td>
	       <td width="25%"></td>
	       <td width="15%"></td>
	       <td width="40%"></td>
      	    </tr>
	       <form action="set_invoice.php" method="Post" name="invoice_form" onsubmit="return validate_invoice_num()">
	    <tr>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Free Form:</font></td>
	       <td align="left">
		  <INPUT TYPE="text" NAME="invoice_free" SIZE="18" value=""><br /></td>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Or Select:</font></td>
	       <td align="left">
		  <select name="invoice_select" onchange="document.invoice_form.submit(this.form)">
		     <option value="" select="selected">Select An Invoice</option>
     		     <? 
			// Gets cookied invoice #
			$invoice_num = $HTTP_COOKIE_VARS["invoice_num"];

   			// get available invoice #'s
                        $stmt = "select distinct invoice_num from billing 
                                 where service_status in ('INVOICED', 'CREDITMEMO', 'DEBITMEMO') and tosolomon is null 
                                 order by invoice_num";
                        $ora_success = ora_parse($bni_cursor, $stmt);
                        $ora_success = ora_exec($bni_cursor);

                        while(ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
			  if($row['INVOICE_NUM'] == "")
			    continue;
			  
			  if($row['INVOICE_NUM'] == $invoice_num){
			    printf("<option value=\"%s\" selected=\"selected\">%s</option>", 
				   $row['INVOICE_NUM'], $row['INVOICE_NUM']);
			  }else{
			    printf("<option value=\"%s\">%s</option>", $row['INVOICE_NUM'], 
				   $row['INVOICE_NUM']);
			  }
			}
    		     ?>
                  </select>
	       </td>
	    </tr>
	    <tr>
	       <td colspan="4" align="center">
		   <table border="0">
		      <tr>
			 <td width="10%">&nbsp;</td>
			 <td width="30%" align="right"> 
			    <input type="Submit" value="Select the Invoice">
			 </td>
			 <td width="5%">&nbsp;</td>
			 <td width="10%" align="left">
			    <input type="Reset" value=" Reset ">
			 </td>
         		 </form>
			 <td width="5%">&nbsp;</td>
			 <td width="30%" align="left">
			    <form action="reset_invoice.php" method="Post">
			       <input type="submit" value=" Cancel ">
			 </td>
			    </form>
			 <td width="10%">&nbsp;</td>
      		      </tr>
		   </table>		
	   	</td>
	     </tr>
	     <tr>
		<td colspan="4"></td>
	     </tr>
	 </table>
      </td>
   </tr>
<?
   if ($input != "") {
?>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><font size="2" face="Verdana"><b>You have successfully updated Invoice # <?= $input ?>!</b></font></td>
   </tr>
<?
   }
?>
</table>

<?
   if ($invoice_num != "") {
      // get valid commodity codes, service codes, GL codes and asset codes
      $valid_comm = array();
      $valid_serv = array();
      $valid_gl = array();
      $valid_asset = array();

      // commodity code
      $stmt = "select * from fnd_flex_values where flex_value_set_id = '1005837' order by flex_value";
      ora_parse($prod_cursor, $stmt);
      ora_exec($prod_cursor);

      while(ora_fetch_into($prod_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
	array_push($valid_comm, $row['FLEX_VALUE']);
      }

      // service code
      $stmt = "select * from fnd_flex_values where flex_value_set_id = '1005836' order by flex_value";
      ora_parse($prod_cursor, $stmt);
      ora_exec($prod_cursor);

      while(ora_fetch_into($prod_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
	array_push($valid_serv, $row['FLEX_VALUE']);
      }


      // GL code
      $stmt = "select * from fnd_flex_values where flex_value_set_id = '1005835' order by flex_value";
      ora_parse($prod_cursor, $stmt);
      ora_exec($prod_cursor);

      while(ora_fetch_into($prod_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
	array_push($valid_gl, $row['FLEX_VALUE']);
      }

      // asset code
      $stmt = "select * from fnd_flex_values where flex_value_set_id = '1005838' order by flex_value";
      ora_parse($prod_cursor, $stmt);
      ora_exec($prod_cursor);

      while(ora_fetch_into($prod_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
	array_push($valid_asset, $row['FLEX_VALUE']);
      }

      // get original billing information
      $stmt = "select billing_num, billing_type, commodity_code, service_code, gl_code, asset_code from billing where invoice_num = $invoice_num order by billing_num";
      $ora_success = ora_parse($bni_cursor, $stmt);
      $ora_success = ora_exec($bni_cursor);	

      ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

 ?>
         
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <br />
	 <p><font size="2" face="Verdana">Update Invoice (# <?= $invoice_num ?>)</font></p>
    	 <table width="90%" bgcolor="#f0f0f0" border="0" cellpadding="2" cellspacing="0">
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
	    <tr>
	       <td align="center" width="14%" nowrap><font size="2" face="Verdana"><u>Billing #</u></font></td>
	       <td align="center" width="16%" nowrap><font size="2" face="Verdana"><u>Billing Type</u></font></td>
 	       <td align="center" width="20%" nowrap><font size="2" face="Verdana"><u>Commodity</u></font></td>
	       <td align="center" width="20%" nowrap><font size="2" face="Verdana"><u>Service</u></font></td>
	       <td align="center" width="20%" nowrap><font size="2" face="Verdana"><u>GL Code</u></font></td>
	       <td align="center" width="20%" nowrap><font size="2" face="Verdana"><u>Asset Code</u></font></td>
	    </tr>
            <form name="update_form" action="update_invoice_process.php" method="Post" onsubmit="return validate_update()">
	       <input type="hidden" name="invoice_num" value="<?= $invoice_num ?>">
	       <input type="hidden" name="reason" value="">
<?
      do {
?>

 	    <tr>
	       <td align="center"><font size="2" face="Verdana"><?= $row['BILLING_NUM'] ?></font>
	          <input type="hidden" name="billing_num[]" size="10" maxlength="10" value="<?= $row['BILLING_NUM'] ?>"></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['BILLING_TYPE'] ?></font></td>
 	       <td align="center">
                  <select name="commodity_code[]">
                     <option value=""></option>
<?                
                     foreach ($valid_comm as $commodity_code) {
	                if ($row['COMMODITY_CODE'] == $commodity_code) {
			   printf("<option value=\"$commodity_code\" selected=\"selected\">$commodity_code</option>");
		        } else {
			   printf("<option value=\"$commodity_code\">$commodity_code</option>");
			}
	             }
?>
                  </select> 
               </td>
	       <td align="center">
                  <select name="service_code[]">
                     <option value=""></option>
<?                
                     foreach ($valid_serv as $service_code) {
	                if ($row['SERVICE_CODE'] == $service_code) {
			   printf("<option value=\"$service_code\" selected=\"selected\">$service_code</option>");
		        } else {
			   printf("<option value=\"$service_code\">$service_code</option>");
			}
	             }
?>
                  </select> 
               </td> 
	       <td align="center"> 
                  <select name="gl_code[]">
                     <option value=""></option>
<?                
                     // get GL Code based on service code if it is null
                     $my_gl_code = $row['GL_CODE'];

                     if($my_gl_code == ""){
		       $stmt = "select gl_code from service_category where service_code = " . $row['SERVICE_CODE'];
		       ora_parse($bni_cursor1, $stmt);
		       ora_exec($bni_cursor1);
		       ora_fetch_into($bni_cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		       $my_gl_code = $row1['GL_CODE'];
		     } 
 
                     foreach ($valid_gl as $gl_code) {
	                if ($my_gl_code == $gl_code) {
			   printf("<option value=\"$gl_code\" selected=\"selected\">$gl_code</option>");
		        } else {
			   printf("<option value=\"$gl_code\">$gl_code</option>");
			}
	             }
?>
                  </select> 
               </td> 
	       <td align="center">
                  <select name="asset_code[]">
                     <option value=""></option>
<?                
                     foreach ($valid_asset as $asset_code) {
	                if ($row['ASSET_CODE'] == $asset_code) {
			   printf("<option value=\"$asset_code\" selected=\"selected\">$asset_code</option>");
		        } else {
			   printf("<option value=\"$asset_code\">$asset_code</option>");
			}
	             }
?>
                  </select> 
               </td>
	    </tr>
<?
      } while (ora_fetch_into($bni_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td colspan="4" align="center">
		   <table>
		      <tr>
			 <td width="35%" align="right"> 
			    <input type="Submit" value="  Save  ">
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="25%" align="center">
			    <input type="Reset" value=" Reset ">
			 </td>
			 </form>
			 <td width="10%">&nbsp;</td>
			 <td width="20%" align="left">
			    <form name="cancel_form" action="reset_invoice.php" method="Post">
		  	       <input name="page_filename" type="hidden" value="<?= $_SERVER['PHP_SELF'] ?>">
			       <input type="submit" value=" Cancel ">
			 </td>
			    </form>
    		      </tr>
		   </table>		
	   	</td>
		<td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
         </table>
      </td>
   </tr>
</table>

<?
   }

   // close database connection
   ora_close($bni_cursor);
   ora_logoff($bni_conn);

   ora_close($prod_cursor);
   ora_logoff($prod_conn);
?>

<br />
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>&nbsp;</td>
      <td colspan="2" valign="middle" align="center">
	 <table border="0" width="95%" cellpadding="4" cellspacing="0">
	    <tr>
	       <td width="10%">&nbsp;</td>
	       <td width="65%" align="center">
	 	  <font face="Verdana" size="2" color="#000080">
	    	     <a href="update_invoice.doc" target="_new">Update Invoices - Help</a>
	 	  </font>
	       </td>
	       <td width="25%">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

<?
   include("pow_footer.php");
?>
