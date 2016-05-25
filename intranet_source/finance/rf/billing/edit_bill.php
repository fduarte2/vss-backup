<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Edit Prebill";
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
      service_date = x.service_date.value
      service_start = x.service_start.value
      service_stop = x.service_stop.value
      rate = x.rate.value

      // check on the data inputs
      if (customer_id == "") {
	 alert("Please enter the customer ID!")
         return false
      }

      if (lr_num == "") {
	 alert("Please enter vessel number!")
         return false
      }

      if (service_date == "") {
	alert("Please enter service date!")
	return false
      }

      if (service_start == "") {
	alert("Please enter service start date!")
	return false
      }

      if (service_stop == "") {
	alert("Please enter service stop date!")
	return false
      }

      if (rate == "") {
	alert("Please enter service rate!");
	return false
      } else {
	test = IsNumeric(rate)
	if (test == false) {
	   alert("Service Rate must be Numeric!")
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
         <font size="5" face="Verdana" color="#0066CC">Edit Prebill</font>
         <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<?
   // Make Oracle Database connection
   include("connect.php");

   $conn = ora_logon("SAG_OWNER@$rf", "OWNER");
//   $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
   if (!$conn) {
      printf("Error logging on to the RF Oracle Server: " . ora_errorcode($conn));
      printf("Please report to TS!");
      exit;
   }

   // open cursors
   $cursor = ora_open($conn);         // general purpose
   $cursor1 = ora_open($conn);        // billing_detail
   $cursor2 = ora_open($conn);        // other use

   $page = "edit";
   $rf_billing = "rf_billing";
   $rf_billing_detail = "rf_billing_detail";

   include("select_bill.php");

   // get form value
   $billing_num = $HTTP_COOKIE_VARS["billing_num"];

   if ($billing_num != "") {
      // get the unchanged prebill information
      $stmt = "select * from $rf_billing where billing_num = $billing_num";
      $ora_success = ora_parse($cursor, $stmt);
      $ora_success = ora_exec($cursor);	

      ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

      $customer_id = $row['CUSTOMER_ID'];
      $billing_type = trim($row['BILLING_TYPE']);
      $lr_num = $row['ARRIVAL_NUM'];

      if ($row['SERVICE_DATE'] != "") {
	 $service_date = date('m/d/Y', strtotime($row['SERVICE_DATE']));	 
      } else {
	 $service_date = "";
      }

      if ($row['SERVICE_START'] != "") {
	 $service_start = date('m/d/Y', strtotime($row['SERVICE_START']));	 
      } else {
	 $service_start = "";
      }

      if ($row['SERVICE_STOP'] != "") {
	 $service_stop = date('m/d/Y', strtotime($row['SERVICE_STOP']));	 
      } else {
	 $service_stop = "";
      }

      // get billing details, if any
      $stmt = "select * from $rf_billing_detail 
               where sum_bill_num = $billing_num and service_status = 'PREINVOICE' 
               order by pallet_id";
      $ora_success = ora_parse($cursor1, $stmt);
      $ora_success = ora_exec($cursor1);	
      
      ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($cursor1);
 ?>
         
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <br /><font size="2" face="Verdana">Edit Prebill # <?= $billing_num ?></font><br /><br />
    	 <table width="90%" bgcolor="#f0f0f0" border="0" cellpadding="2" cellspacing="0">
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
	       <form name="edit_form" action="edit_process.php" method="Post" onsubmit="return validate_edit()">
		  <input name="reason" type="hidden" value="">
		  <input name="billing_num" type="hidden" value="<?= $billing_num ?>">
	    <tr>
	       <td width="15%" align="right" nowrap><font size="2" face="Verdana">Bill Type:</font></td>
	       <td width="16%" align="left" nowrap>
		  <input name="billing_type" type="text" size="16" disabled="disabled" 
			       value="<?= ($billing_type == "PLT-STRG" ? "RF STORAGE" : "UNKNOWN") ?>">
	       </td>
	       <td width="12%" align="right" nowrap><font size="2" face="Verdana">Customer #:</font></td>
	       <td width="16%" align="left" nowrap>
		  <select name="customer_id">
		     <option value="">Customer ID</option>
   		  <? 
	             // let the user select customer_id to prevent invalid customer ID 
		     // we do not include 1, 2 becaues they are about POW
		     $stmt = "select customer_id from customer_profile 
                              where customer_id > 1 and customer_name is not null
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
		  <input name="lr_num" type="text" size="16" value="<?= $lr_num ?>">
	       </td>
      	    </tr>
	    <tr>
 	       <td align="right" nowrap><font size="2" face="Verdana">Srv Date:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="service_date" size="11" value="<?= $service_date ?>">
		  <a href="javascript:show_calendar('edit_form.service_date');" 
		     onmouseover="window.status='Date Picker';return true;" 
		     onmouseout="window.status='';return true;">
		     <img src="/images/show-calendar.gif" width=24 height=22 border=0>
	          </a>
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">Start Date:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="service_start" size="11" value="<?= $service_start ?>">
		  <a href="javascript:show_calendar('edit_form.service_start');" 
		     onmouseover="window.status='Date Picker';return true;" 
		     onmouseout="window.status='';return true;">
		     <img src="/images/show-calendar.gif" width=24 height=22 border=0>
	          </a>
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">End Date:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="service_stop" size="11" value="<?= $service_stop ?>">
		  <a href="javascript:show_calendar('edit_form.service_stop');" 
		     onmouseover="window.status='Date Picker';return true;" 
		     onmouseout="window.status='';return true;">
		     <img src="/images/show-calendar.gif" width=24 height=22 border=0>
		  </a>
	       </td>
	    </tr>
	    <tr>
 	       <td align="right" nowrap><font size="2" face="Verdana">Rate:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="rate" size="16" maxlength="14" value="<?= $row['SERVICE_RATE'] ?>">
	       </td>
	       <td align="right" nowrap><font size="2" face="Verdana">Unit:</font></td>
	       <td align="left" nowrap>
		  <input type="text" name="unit" size="16" maxlength="4" value="<?= $row['SERVICE_UNIT'] ?>">
	       </td>
	       <td colspan="2"></td>
	    </tr>
	    <tr>
	       <td colspan="6" height="12"></td>
	    </tr>
	    <tr>
	       <td align="left" colspan="6">
	          <font size="2" face="Verdana">&nbsp;&nbsp;Pallet Details:</font></td>
	    </tr>
	    <tr>
	       <td colspan="6" height="6"></td>
	    </tr>
	    <tr>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Barcode</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Start</u></font></td>
 	       <td align="center" nowrap><font size="2" face="Verdana"><u>End</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Cases</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Rate</u></font></td>
	       <td align="center" nowrap><font size="2" face="Verdana"><u>Amount</u></font></td>
	    </tr>
	 <?
	    if ($rows > 0) {
	      do {
		// reformat dates
		if ($row1['SERVICE_START'] != "") {
		  $start_date = date('m/d/Y', strtotime($row1['SERVICE_START']));	 
		} else {
		  $start_date = "";
		}
		
		if ($row1['SERVICE_STOP'] != "") {
		  $stop_date = date('m/d/Y', strtotime($row1['SERVICE_STOP']));	 
		} else {
		  $stop_date = "";
		}
         ?>
	    <tr>
	       <td align="center">&nbsp;
	          <input type="text" name="barcode[]" size="12" maxlength="25" value="<?= $row1['PALLET_ID'] ?>"></td>
	       <td align="center">
                  <input type="text" name="start[]" size="12" maxlength="20" value="<?= $start_date ?>"></td>
 	       <td align="center">
	          <input type="text" name="end[]" size="12" maxlength="20" value="<?= $stop_date ?>"></td>
	       <td align="center">
	          <input type="text" name="detail_cases[]" size="10" maxlength="10" 
	                 value="<?= $row1['SERVICE_QTY'] ?>"></td>
	       <td align="center">
	          <input type="text" name="detail_rate[]" size="10" maxlength="10" 
                         value="<?= $row1['SERVICE_RATE'] ?>"></td>
	       <td align="center">
	          <input type="text" name="detail_amount[]" size="10" maxlength="10" 
	                 value="<?= $row1['SERVICE_AMOUNT'] ?>"></td>
	    </tr>
         <?
	      } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	    }
	 ?>

	    <!-- Add blank lines so user can enter more if needed -->
<?
		if($HTTP_POST_VARS['submit'] == "Add Rows" && is_numeric($HTTP_POST_VARS['more_rows'])){
			for($i = 1; $i <= $HTTP_POST_VARS['more_rows']; $i++){
?>
	    <tr>
	       <td align="center">&nbsp;
	          <input type="text" name="barcode[]" size="12" maxlength="25" value=""></td>
	       <td align="center"><input type="text" name="start[]" size="12" maxlength="20" value=""></td>
 	       <td align="center"><input type="text" name="end[]" size="12" maxlength="20" value=""></td>
	       <td align="center"><input type="text" name="detail_cases[]" size="10" maxlength="10" value=""></td>
	       <td align="center"><input type="text" name="detail_rate[]" size="10" maxlength="10" value=""></td>
	       <td align="center"><input type="text" name="detail_amount[]" size="10" maxlength="10" value=""></td>
	    </tr>
	    <tr>
	       <td colspan="6" height="3"></td>
	    </tr>
<?
			}
		}
?>
		<tr>
	       <td>&nbsp;</td>
	       <td colspan="4" align="center">
		   <table>
		      <tr>
			 <td width="15%" align="right"> 
			    <input type="Submit" value="Save Prebill">
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="15%" align="center">
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
			 <td width="15%">&nbsp;</td>
			 <td width="15%" align="left">
				<form name="enlarge_form" action="edit_bill.php" method="post">
					<nobr><input type="text" name="more_rows" size="2" maxlength="2" value="<? echo (0 + $HTTP_POST_VARS['more_rows']); ?>">
					<input type="submit" name="submit" value="Add Rows"><font size="2" face="Verdana"><b>&nbsp;(save changes before using)</b></nobr>
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
