<script type="text/javascript">

   function validate_bill()
   {
      x = document.bill_form

      bill_free = x.bill_free.value
      bill_select = x.bill_select.value

      // No billing # is entered or selected
      if(bill_free == "" && bill_select == ""){
	 alert("You need to either enter or select a billing number to update the prebill!")
         return false
      }

      if(bill_free != "" && bill_select != ""){
	 alert("You cannot enter and select the billing number at the same time! \nPlease reset the form and try it again.")
         return false
      }

      return true
   }

   function check(billing_info) {
      value_array = billing_info.split(",")

      document.select_bill.lr_num.value = value_array[0]
      document.select_bill.billing_type.value = value_array[1]
      document.select_bill.customer_id.value = value_array[2]
   }

   function validate_select_bill()
   {
      x = document.select_bill

      billing_num = x.billing_num.value
      page = x.page.value

      if (page == "edit") {
	 return true
      } else {
	 x.reason.value = prompt("Please input the reason for updating the prebill.", "")
	 reason = x.reason.value
	 while(reason == "") {
	    alert("You need to input the change reason!")
      	    x.reason.value = prompt("Please input the reason for updating the prebill.", "")
            reason = x.reason.value
         }

         if (reason.length > 50) {
	    alert("Please do not input more than 50 characters!")
            return false
         }

         // ask differently depending on the page
	 if (page == "delete") {
	    answer = confirm("Are you sure you want to delete the prebill with Billing # " + billing_num + ". \
			   \nClick OK to confirm or Cancel to cancel.")
         } else if (page == "un-delete") {
	    answer = confirm("Are you sure you want to un-delete the prebill with Billing # " + billing_num + ". \
			   \nClick OK to confirm or Cancel to cancel.")
	 }

	 if (answer == true) {
	    return true
	 } else {
	   return false
	 }
      }
   }

</script>

<!-- Bill Selection -->
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <font size="2" face="Verdana">Please enter or select a billing number to update the prebill.</font>
	 <br /><br />
    	 <table width="90%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td width="20%">&nbsp;</td>
	       <td width="25%">&nbsp;</td>
	       <td width="15%">&nbsp;</td>
	       <td width="40%">&nbsp;</td>
      	    </tr>
	       <form action="set_bill.php" method="Post" name="bill_form" onsubmit="return validate_bill()">
	          <input name="page_filename" type="hidden" value="<?= $_SERVER['PHP_SELF'] ?>">
	          <input name="page" type="hidden" value="<?= $page ?>">
	    <tr>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Free Form:</font></td>
	       <td align="left">
		  <INPUT TYPE="text" NAME="bill_free" SIZE="18" value=""><br /></td>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Or Select:</font></td>
	       <td align="left">
		  <select name="bill_select" onchange="document.bill_form.submit(this.form)">
		     <option value="" select="selected">Select the Prebill</option>
     		     <? 
			// Gets cookied billing #
			$selected_billing_num = $HTTP_COOKIE_VARS["billing_num"];

   			// generate and execute a query
                        if ($page == "un-delete") {
			   $stmt = "select distinct billing_num from billing where service_status = 'DELETED'  and billing_type != 'STORAGE'
                                    order by billing_num desc";
			} elseif ($page == "delete"){
			   $stmt = "select distinct billing_num from billing where service_status = 'PREINVOICE' and billing_type != 'STORAGE'
                                    order by billing_num desc";
			} else {
			   $stmt = "select distinct billing_num from billing where service_status = 'PREINVOICE' 
                                    order by billing_num desc";
			}

                        $ora_success = ora_parse($cursor, $stmt);
                        $ora_success = ora_exec($cursor);

                        ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                        $rows = ora_numrows($cursor);
 
   			if ($rows > 0) {
      	   		   // iterate through resultset
      	   		   do {
			     $billing_num = $row['BILLING_NUM']; // ora_getcolumn($cursor, 0);
             		
			      if($billing_num == "")
				 continue;

                              if($billing_num == $selected_billing_num){
	        	         printf("<option value=\"%s\" selected=\"selected\">%s</option>", 
					 $billing_num, $billing_num);
                              }else{
	        		 printf("<option value=\"%s\">%s</option>", $billing_num, $billing_num);
           		      }
        	           } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}

                        // to give feedback to user differently
                        if ($page == "edit") {
			  $feedback = "edited";
			} elseif ($page == "delete") {
			  $feedback = "deleted";
			} elseif ($page == "un-delete") {
			  $feedback = "un-deleted";
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
			    <input type="Submit" value="Select the Prebill">
			 </td>
			 <td width="5%">&nbsp;</td>
			 <td width="10%" align="left">
			    <input type="Reset" value=" Reset ">
			 </td>
         		 </form>
			 <td width="5%">&nbsp;</td>
			 <td width="30%" align="left">
			    <form action="reset_bill.php" method="Post">
		  	       <input name="page_filename" type="hidden" value="<?= $_SERVER['PHP_SELF'] ?>">
			       <input type="submit" value=" Cancel ">
			 </td>
			    </form>
			 <td width="10%">&nbsp;</td>
      		      </tr>
		   </table>		
	   	</td>
	     </tr>
	     <tr>
		<td colspan="4">&nbsp;</td>
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
      <td><font size="2" face="Verdana">You have successfully <?= $feedback ?> the prebill with billing #
         <?= $input ?>!
      </td>
   </tr>
<?
   }
?>

</table>
<br />

<!-- Prebill selection -->
<? 
   // Show prebills with the cookied billing #
   if ($selected_billing_num != "") {
      // having different processing page and button name depending on the page
      if ($page == "edit") {
 	 $process_page = "edit_bill.php";
	 $button_name = "Edit Prebill";
      } elseif ($page == "delete") {
	 $process_page = "delete_process.php";
	 $button_name = " Delete Prebill";
      } elseif ($page == "un-delete") {
	 $process_page = "undelete_process.php";
	 $button_name = "Un-Delete Prebill";
      }

      // a billing # is selected
      if ($page == "un-delete") {
	$stmt = "select * from billing where billing_num = $selected_billing_num and service_status = 'DELETED' and billing_type != 'STORAGE'
                 order by lr_num, billing_type, customer_id";
      } elseif ($page == "delete"){
	$stmt = "select * from billing where billing_num = $selected_billing_num and service_status = 'PREINVOICE' and billing_type != 'STORAGE'
                 order by lr_num, billing_type, customer_id";
      } else {
	$stmt = "select * from billing where billing_num = $selected_billing_num and service_status = 'PREINVOICE'
                 order by lr_num, billing_type, customer_id";
      }

      $ora_success = ora_parse($cursor, $stmt);
      $ora_success = ora_exec($cursor);
      
      ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
      $rows = ora_numrows($cursor);

      $billing_type = trim($row['BILLING_TYPE']);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
      <form name="select_bill" method="post" action="<?= $process_page ?>" 
	    onsubmit="return validate_select_bill()">
	 <input name="billing_num" type="hidden" value="<?= $selected_billing_num ?>">
	 <input name="lr_num" type="hidden" value="<?= trim($row['LR_NUM']) ?>">
	 <input name="billing_type" type="hidden" value="<?= $billing_type ?>">
	 <input name="customer_id" type="hidden" value="<?= trim($row['CUSTOMER_ID']) ?>">
	 <input name="page" type="hidden" value="<?= $page ?>">
	 <input name="reason" type="hidden" value="">
	 <p align="left">
	    <font size="2" face="Verdana">
	    <?= ($rows > 1) ? "Prebills" : "Prebill" ?> with Billing #: <?= $selected_billing_num ?></font>
	 </p>
	 <table bgcolor="#f0f0f0" width="90%" border="0" cellpadding="4" cellspacing="0">
	    <tr>
	       <td colspan="8" height="8"></td>
	    </tr>
	    <tr>
	       <th width="1%"  align="center">&nbsp;</th>	      
	       <th width="15%" align="left"><font size="2" face="Verdana"><u>Vessel</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Type</u></font></td>
	       <th width="10%" align="center"><font size="2" face="Verdana"><u>Customer</u></font></td>
	       <th width="10%" align="center"><font size="2" face="Verdana"><u>Date</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>QTY</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Rate</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Amount</u></font></td>
	    </tr>
<?
	 // define a counter so we know when is the 1st time
	 $i = 0;
         do {
 	    $billing_type = trim($row['BILLING_TYPE']);

	    // format service date
	    if ($row['SERVICE_START'] != "") {
	       $service_start = date('m/d/y', strtotime($row['SERVICE_START']));
	    } else {
	       $service_start = "";
	    }
?>
	    <tr>
	       <td align="right">
<?
	       // default to the 1st entry
	       if ($i == 0) {
?>
		  <input type="radio" name="billing_info" checked="checked"
			 value="<?= $row['LR_NUM'] . "," . $billing_type . "," . $row['CUSTOMER_ID'] ?>" 
	                 onclick="check(this.value)">

<?
	       } else {
?>
		  <input type="radio" name="billing_info" 
			 value="<?= $row['LR_NUM'] . "," . $billing_type . "," . $row['CUSTOMER_ID'] ?>" 
	                 onclick="check(this.value)">
<?
	       }
?>
	       </td>
	       <td align="left"><font size="2" face="Verdana"><?= $row['LR_NUM'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $billing_type ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['CUSTOMER_ID'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $service_start ?></font></td>
	       <td align="center">
                  <font size="2" face="Verdana"><?= $row['SERVICE_QTY'] . " " . $row['SERVICE_UNIT'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['SERVICE_RATE'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['SERVICE_AMOUNT'] ?></font></td>
	    </tr>
<?
            if ($row['SERVICE_DESCRIPTION'] != "") {
	      $desc = $row['SERVICE_DESCRIPTION'];

	      if ($billing_type == 'LABOR') {
		if ($row['LABOR_TICKET_NUM'] != "") {
		  $desc .= " (LABOR TICKET #: " . $row['LABOR_TICKET_NUM'] . ")";
		} else {
		  $desc .= " (LABOR TICKET #: NONE)";
		}
	      }
?>	      
            <tr>
               <td>&nbsp;</td>
               <td colspan="7"><font size="2" face="Verdana"><?= $desc ?></font></td>
            </tr>
<?
	    }

	    if ($billing_type == 'LABOR' && $row['LABOR_TICKET_NUM'] != "" && 
		($page == 'delete' || $page == 'un-delete')) {
	      // put the checkbox for deleting/un-delete all prebills of this labor ticket
?>

            <tr>
               <td>&nbsp;</td>
	       <td colspan="7">
		  <input type="checkbox" name="whole_labor_ticket" CHECKED> &nbsp;
	          <font size="2" face="Verdana"><?= strtoupper($page) ?> THE WHOLE LABOR TICKET</font></td>
            </tr>

<?
	    }

	    $i++;
         } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	    <tr>
	       <td>&nbsp;</td>
	       <td colspan="6" align="center">
		   <table>
		      <tr>
			 <td width="55%" align="right"> 
			    <input type="Submit" value="<?= $button_name ?>">
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="35%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
	   	</td>
		<td>&nbsp;</td>
	 </form>
	    </tr>
	    <tr>
	       <td colspan="8" height="8"></td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

<?
   }
?>
