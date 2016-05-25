<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Edit Prebills";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<!-- Edit Prebills - Main page -->

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   // include the function isNumeric
   <? include("js_func.php") ?>

   function validate_edit()
   {
      x = document.edit_form

      billing_num = x.billing_num.value
      customer_id = x.customer_id.value
      lr_num = x.lr_num.value
      qty = x.qty.value
      rate = x.rate.value
      amount = x.amount.value

      // check on the data inputs
      if (customer_id == "") {
	 alert("Please enter the customer ID!")
         return false
      }

      if (lr_num == "") {
	 alert("Please enter vessel number!")
         return false
      }

      if (!(qty == "" )) {
	 test = IsNumeric(qty) 
	 if (test == false) {
            alert("Service QTY must be Numeric!")
	    return false
	 }
      }

      if (!(rate == "")) {
	test = IsNumeric(rate)
	if (test == false) {
	   alert("Service Rate must be Numeric!")
	   return false
	}
      }

      if (amount == "") {
	 alert("Please enter service amount!")
         return false
      } else {
	 test = IsNumeric(amount)
	 if (test == false) {
	    alert("Service Amount must be Numeric!")
	    return false
	 }
      }

      // ask for reason of change
      x.reason.value = prompt("Please input the reason for updating the prebill.", "")
      reason = x.reason.value
      while (reason == "") {
	 alert("You need to input the change reason!")
	 x.reason.value = prompt("Please input the reason for updating the prebill.", "")
	 reason = x.reason.value
      }
      
      if (reason.length > 50) {
	 alert("Please do not input more than 50 characters!")
	 return false
      }

      // ask for user confirmation
      answer = confirm("Are you sure you want to update the prebill with Billing # " + billing_num + ". \
			\nClick OK to confirm or Cancel to cancel.")
      return answer
   }

</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Edit Pre-Invoice</font>
         <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<?
   // Make Oracle Database connection
   include("connect.php");

   $conn = ora_logon("SAG_OWNER@$bni", "SAG");
   if (!$conn) {
      printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
      printf("Please report to TS!");
      exit;
   }

   // open cursors
   $cursor = ora_open($conn);         // general purpose
   $cursor1 = ora_open($conn);        // billing_detail
   $cursor2 = ora_open($conn);        // other use

   $page = "edit";
   include("select_bill.php");

   // get form value
   $billing_num = $HTTP_POST_VARS["billing_num"];
   $lr_num = $HTTP_POST_VARS["lr_num"];
   $billing_type = $HTTP_POST_VARS["billing_type"];
   $customer_id = $HTTP_POST_VARS["customer_id"];

   if ($billing_num != "") {
      // get the unchanged prebill information
      $stmt = "select b.*, to_char(service_start, 'HH:MI AM') start_time, to_char(service_stop, 'HH:MI AM') stop_time 
               from billing b where billing_num = $billing_num and billing_type = '$billing_type' and lr_num = $lr_num 
               and customer_id = $customer_id";
      $ora_success = ora_parse($cursor, $stmt);
      $ora_success = ora_exec($cursor);	

      ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

      if ($row['SERVICE_DATE'] != "") {
	 $service_date = date('m/d/Y', strtotime($row['SERVICE_DATE']));	 
      } else {
	 $service_date = "";
      }

      if ($row['SERVICE_START'] != "") {
	if ($row['START_TIME'] != "12:00 AM") {
	  $service_start = date('m/d/Y', strtotime($row['SERVICE_START'])) . " " . $row['START_TIME'];
	} else {
	  $service_start = date('m/d/Y', strtotime($row['SERVICE_START']));	 
	}
      } else {
	 $service_start = "";
      }

      if ($row['SERVICE_STOP'] != "") {
	if ($row['STOP_TIME'] != "12:00 AM") {
	  $service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP'])) . " " . $row['STOP_TIME'];
	} else {
	  $service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));	 
	}
      } else {
	 $service_stop = "";
      }

      // get billing details, if any
      $lis_types = array("CCDS/LOAD", "CCDS/ILOAD", "CCDS/PLOAD", "CCDS/INSP", "CCDS/PINSP", "CCDS/SW", "CCDS/PSW");
      $is_lis = in_array($billing_type, $lis_types);
      $detail_billing_num = "";

      $stmt = "select * from billing_detail where billing_num = $billing_num order by lot";
      $ora_success = ora_parse($cursor1, $stmt);
      $ora_success = ora_exec($cursor1);	
      
      ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($cursor1);

      if ($rows > 0) {
	$has_details = true;
	$detail_billing_num = $billing_num;
      } else { 
	$has_details = false;

	if ($is_lis) {
	  $stmt = "select ILOAD_BILLING_NUM from lis_group where ILOAD_BILLING_NUM = $billing_num 
                   or INSP_BILLING_NUM = $billing_num or SW_BILLING_NUM = $billing_num";
	  $ora_success = ora_parse($cursor1, $stmt);
	  $ora_success = ora_exec($cursor1);	
	  
	  ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	  $rows = ora_numrows($cursor1);
	  
	  if ($rows > 0) {
	    $stmt = "select * from billing_detail where billing_num = " . $row1['ILOAD_BILLING_NUM'] . 
	       " order by lot";
	    $ora_success = ora_parse($cursor1, $stmt);
	    $ora_success = ora_exec($cursor1);	
	    
	    ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	    $rows = ora_numrows($cursor1);
	    
	    if ($rows > 0) {
	      $has_details = true;
	      $detail_billing_num = $row1['ILOAD_BILLING_NUM'];
	    } else {
	      $has_details = false;
	    }
	  } else {
	    $has_details = false;
	  }
	}
      }
 ?>
         
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <br />
	 <p>
	    <font size="2" face="Verdana">Edit Prebill # <?= $billing_num ?></font>
         </p>
    	 <table width="100%" bgcolor="#f0f0f0" border="0" cellpadding="2" cellspacing="0">
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
	    <tr>
	       <td align="left" colspan="6">
	          <font size="2" face="Verdana">&nbsp;&nbsp;Billing Information:</font></td>
	    </tr>
	    <tr>
	       <td colspan="6" height="6"></td>
	    </tr>
	    <tr>
	       <td width="18%" align="right" nowrap><font size="2" face="Verdana">Bill Type:</font></td>
	       <td width="16%" align="left" nowrap>
	       <form name="edit_form" action="edit_process.php" method="Post" onsubmit="return validate_edit()">
		  <input name="reason" type="hidden" value="">
		  <input name="billing_num" type="hidden" value="<?= $billing_num ?>">
		  <input name="billing_type" type="hidden" value="<?= $billing_type ?>">
		  <input name="show_type" type="text" size="16" disabled="disabled" value="<?= $billing_type ?>">
		  <input name="has_details" type="hidden" value="<?= ($has_details ? "true" : "false") ?>">
                  <input name="detail_billing_num" type="hidden" value="<?= $detail_billing_num ?>">
	       </td>
	       <td width="12%" align="right" nowrap><font size="2" face="Verdana">Customer #:</font></td>
	       <td width="16%" align="left" nowrap>
		  <select name="customer_id">
		     <option value="">&nbsp;Customer ID&nbsp;</option>
   		  <? 
	             // let the user select customer_id to prevent invalid customer ID 
		     // we do not include 1, 2 becaues they are about POW
		     $stmt = "select customer_id from customer_profile where customer_id >= 3 
                              order by customer_id";
                     $ora_success = ora_parse($cursor2, $stmt);
                     $ora_success = ora_exec($cursor2);

                     ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

    		     // iterate through resultset
      	   	     do {
			$ora_cust_id = $row2['CUSTOMER_ID'];
             		
			if ($ora_cust_id == "") {
			   continue;
			}

                        if ($ora_cust_id == $customer_id) {
	        	   printf("<option value=\"%s\" selected=\"selected\">%s</option>", 
				  $ora_cust_id, $ora_cust_id);
			} else {
			   printf("<option value=\"%s\">%s</option>", $ora_cust_id, $ora_cust_id);
			}
		     } while (ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	          ?>
                  </select>
	       </td>
	       <td width="12%" align="right" nowrap><font size="2" face="Verdana">Vessel #:</font></td>
	       <td width="20%" align="left" nowrap>
		  <select name="lr_num">
		     <option value="">Select Vessel</option>
   		  <? 
	             // let the user select vessel # to prevent invalid vessel # 
		     // we do not include 5, which is Testing Vessel
		     $stmt = "select lr_num from vessel_profile where lr_num <> 5
                              order by lr_num";
                     $ora_success = ora_parse($cursor2, $stmt);
                     $ora_success = ora_exec($cursor2);

                     ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		     $lr_num = trim($lr_num);

    		     // iterate through resultset
      	   	     do {
		       
			$ora_lr_num = trim($row2['LR_NUM']);
             		
			if ($ora_lr_num == "") {
			   continue;
			}

                        if ($ora_lr_num == $lr_num) {
	        	   printf("<option value=\"%s\" selected=\"selected\">%s</option>", 
				  $ora_lr_num, $ora_lr_num);
			} else {
			   printf("<option value=\"%s\">%s</option>", $ora_lr_num, $ora_lr_num);
			}
		     } while (ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	          ?>
                  </select>
	       </td>
      	    </tr>
	    <tr>
 	       <td align="right" nowrap><font size="2" face="Verdana">Srv Date:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="service_date" size="10" value="<?= $service_date ?>">
		  <a href="javascript:show_calendar('edit_form.service_date');" 
		     onmouseover="window.status='Date Picker';return true;" 
		     onmouseout="window.status='';return true;">
		     <img src="/images/show-calendar.gif" width=24 height=22 border=0>
	          </a>
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">Start Date:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="service_start" size="19" value="<?= $service_start ?>">
		  <a href="javascript:show_calendar('edit_form.service_start');" 
		     onmouseover="window.status='Date Picker';return true;" 
		     onmouseout="window.status='';return true;">
		     <img src="/images/show-calendar.gif" width=24 height=22 border=0>
	          </a>
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">End Date:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="service_stop" size="19" value="<?= $service_stop ?>">
		  <a href="javascript:show_calendar('edit_form.service_stop');" 
		     onmouseover="window.status='Date Picker';return true;" 
		     onmouseout="window.status='';return true;">
		     <img src="/images/show-calendar.gif" width=24 height=22 border=0>
		  </a>
	       </td>
	    </tr>
	    <tr>
	       <td align="right" nowrap><font size="2" face="Verdana">QTY:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="qty" size="16" maxlength="12" value="<?= $row['SERVICE_QTY'] ?>">
	       </td>
 	       <td align="right" nowrap><font size="2" face="Verdana">Rate:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="rate" size="16" maxlength="14" value="<?= $row['SERVICE_RATE'] ?>">
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">Amount:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="amount" size="14" maxlength="10" value="<?= $row['SERVICE_AMOUNT'] ?>">
	       </td>
	    </tr>
	    <tr>
 	       <td align="right" nowrap><font size="2" face="Verdana">Unit:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="unit" size="16" maxlength="4" value="<?= $row['SERVICE_UNIT'] ?>">
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">Description:</font></td>
	       <td align="left" colspan="3">
		  <input type="text" name="desc" size="49" maxlength="2000" 
	                 value="<?= $row['SERVICE_DESCRIPTION'] ?>">
	       </td>
	    </tr>
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
	 <?
	    if ($has_details) {
	      // display billing details
         ?>
	    <tr>
	       <td align="left" colspan="6">
	          <font size="2" face="Verdana">&nbsp;&nbsp;Lot Information:</font></td>
	    </tr>
	    <tr>
	       <td colspan="6" height="6"></td>
	    </tr>
	    <tr>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Lot #</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Mark</u></font></td>
 	       <td align="center" nowrap><font size="2" face="Verdana"><u>PO #</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Pallets</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Cartons</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Non-Inspect QTY</u></font></td>
	    </tr>
	 <?
	      do {
         ?>
	    <tr>
	       <td align="right">&nbsp;
	          <input type="text" name="lot[]" size="10" maxlength="9" value="<?= $row1['LOT'] ?>"></td>
	       <td align="center">
                  <input type="text" name="mark[]" size="10" maxlength="20" value="<?= $row1['MARK'] ?>"></td>
 	       <td align="center">
	          <input type="text" name="po[]" size="10" maxlength="15" value="<?= $row1['PO'] ?>"></td>
	       <td align="center">
	          <input type="text" name="pallets[]" size="10" maxlength="10" value="<?= $row1['PALLET'] ?>"></td>
	       <td align="center">
	          <input type="text" name="cases[]" size="10" maxlength="10" value="<?= $row1['CTN'] ?>"></td>
	       <td align="center">
	          <input type="text" name="total[]" size="10" maxlength="10" 
	                 value="<?= $row1['NON_INSP_QTY'] ?>"></td>
	    </tr>
         <?
	      } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	 ?>
	    <!-- Add a blank line so user can enter another line item -->
	    <tr>
	       <td align="right">&nbsp;
	          <input type="text" name="lot[]" size="10" maxlength="9" value=""></td>
	       <td align="center"><input type="text" name="mark[]" size="10" maxlength="20" value=""></td>
 	       <td align="center"><input type="text" name="po[]" size="10" maxlength="15" value=""></td>
	       <td align="center"><input type="text" name="pallets[]" size="10" maxlength="10" value=""></td>
	       <td align="center"><input type="text" name="cases[]" size="10" maxlength="10" value=""></td>
	       <td align="center"><input type="text" name="total[]" size="10" maxlength="10" value=""></td>
	    </tr>
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
	 <?
	    }
         ?>
	    <tr>
	       <td>&nbsp;</td>
	       <td colspan="4" align="center">
		   <table>
		      <tr>
			 <td width="35%" align="right"> 
			    <input type="Submit" value="Save Prebill">
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="25%" align="center">
			    <input type="Reset" value=" Reset ">
			 </td>
			 </form>
			 <td width="10%">&nbsp;</td>
			 <td width="20%" align="left">
			    <form name="cancel_form" action="reset_bill.php" method="Post">
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
   ora_close($cursor);
   ora_logoff($conn);
   include("pow_footer.php");
?>
