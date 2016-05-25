<?
  // Seth Morecraft 19-OCT-03
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Edit Claim";
  $area_type = "CLAI";


   $claim_id = $HTTP_POST_VARS['claim_select'];
   $line_id = $HTTP_POST_VARS['line_id'];

   // free-form takes the lead
   $claim_id_free = $HTTP_POST_VARS['claim_free'];
   if($claim_id_free != "")
     $claim_id = $claim_id_free;

   if($claim_id == ""){
     header("Location: /claims/edit.php?input=1");
     exit;
   }
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);
  $ex_postgres_cursor = ora_open($conn);


   include("connect.php");
   include("billing_functions.php");
/*   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
   if (!$connection){
     die("Could not open connection to database server");
   }
   $stmt = "select * from claim_log_oracle where claim_id = '$claim_id'";
   $result = pg_query($connection, $stmt) or die("Error in query: $stmt. " . pg_last_error($connection));
   // Find out if we need a unique line ID
   $rows = pg_num_rows($result);

   // If the wrong claim ID was entered, go back and try again
   if($rows <= 0){
     header("Location: /claims/edit.php?input=2");
     exit;
   }*/
   $sql = "select * from claim_log_oracle where claim_id = '$claim_id'";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	if(!ora_fetch_into($ex_postgres_cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     header("Location: /claims/edit.php?input=2");
     exit;
   } else
   // Only check for line items if there is more than one
//   if($rows > 1){
     if($line_id == ""){
       // We need to go back to the previous page and select a line id
       header("Location: /claims/edit.php?select_line=$claim_id");
       exit;
     }
     else{
       $sql = "select * from claim_log_oracle where claim_id = '$claim_id' and line_id = '$line_id'";
//       $result = pg_query($connection, $stmt) or die("Error in query: $stmt. " . pg_last_error($connection));
     }
   

   // Get on with the edit of this item
//   $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	ora_fetch_into($ex_postgres_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $system = $row['SYSTEM'];

  // After Location headers- we do this:
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }

?>

<script type="text/javascript">
<? include("js_func.php"); ?>

   function validate_mod(){
		x = document.mod_form

		claimed = x.claimed.value
		shipper = x.shipper.value
		denied_qty = x.denied_qty.value
		amount = x.amount.value
		denied = x.denied.value
		shipper_amount = x.shipper_amount.value
		
		quantity = x.quantity.value
		dummy_amount = x.dummy_amount.value

		if(quantity !=  Number(claimed) + Number(shipper) + Number(denied_qty)){
			alert("Total QTY did not match the sum of the 3 lower QTY boxes\nTotal QTY: " + quantity + "\nPort QTY: " + claimed + "\nDenied QTY: " + denied_qty + "\nShipper QTY: " + shipper)
			return false
		}
		if(dummy_amount != Number(amount) + Number(denied) + Number(shipper_amount)){
			alert("Total Amount did not match the sum of the 3 lower Amount boxes\nTotal Amt: " + dummy_amount + "\nPort Amt: " + amount + "\nDenied Amt: " + denied + "\nShipper Amt: " + shipper_amount)
			return false
		}
		if(Number(claimed) < 0 || Number(shipper) < 0 || Number(denied_qty) < 0 || Number(amount) < 0 || Number(denied) < 0 || Number(shipper_amount) < 0){
			alert("Can not have negative values")
			return false
		}

		var asless = true;
		return asless;
    }
   function update_amount() {
     x = document.mod_form

     letter_type = x.letter_type.value
     system = x.system.value
     saved_quantity = x.quantity.value
     quantity = x.quantity.value
     claimed = x.claimed.value
     shipper = x.shipper.value
     weight = x.weight.value
     denied_qty = x.denied_qty.value
     cost = x.cost.value
     total_quantity = quantity - denied_qty;
     
     if(system == "CCDS"){
       weight_per = weight / quantity
       port_weight = weight_per * claimed
       denied_weight = weight_per * denied_qty
       denied_amount = cost * denied_weight
       shipper_weight = weight_per * shipper
       dummy_amount_total = cost * weight
       if(letter_type == "Split"){
         amount = cost * port_weight
         shipper_amount = cost * shipper_weight
       }
       else if(letter_type == "Customer"){
         amount = cost * port_weight
         shipper_amount = 0
       }
       else if(letter_type == "Shipper"){
         amount = 0
         shipper_amount = cost * shipper_weight
       }
       else if(letter_type == "RShipper"){
         amount = cost * port_weight
         shipper_amount = cost * shipper_weight
       }
       else{
         amount = 0
         shipper_amount = 0
       }
     }
     if(system == "RF"){
       dummy_amount_total = cost * saved_quantity
       denied_amount = cost * denied_qty
       if(letter_type == "Split"){
         port = total_quantity - shipper
         amount = cost * port
         shipper_amount = cost * shipper
       }
       else if(letter_type == "Customer"){
         amount = cost * claimed
         shipper_amount = 0
       }
       else if(letter_type == "Shipper" || letter_type == "RShipper"){
         amount = 0
         shipper_amount = cost * total_quantity
       }
       else{
         amount = 0
         shipper_amount = 0
       }
     }
     if(system == "BNI"){
       gallons = x.gallons.value
       dummy_amount_total = cost * gallons
       denied_amount = denied_qty
       if(letter_type == "Split"){
         port = total_quantity - shipper
 //        amount = cost * port
         shipper_amount = cost * shipper
       }
       else if(letter_type == "Customer"){
 //        amount = cost * gallons
         shipper_amount = 0
       }
       else if(letter_type == "Shipper" || letter_type == "RShipper"){
//         amount = 0
         shipper_amount = cost * total_quantity
       }
       else{
 //        amount = 0
         shipper_amount = 0
       }
     }

     // Round some stuff off
     dummy_amount_total = Math.round(dummy_amount_total * 100) / 100;
//     shipper_amount = Math.round(shipper_amount * 100) / 100;
//     amount = Math.round(amount * 100) / 100;
//     denied_amount = Math.round(denied_amount * 100) / 100;

//     x.shipper_amount.value = shipper_amount
//     x.amount.value = amount
     x.dummy_amount.value = dummy_amount_total
//     x.denied.value = denied_amount

   }

</script>

<script language="JavaScript" src="/functions/calendar.js"></script>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
   	    <tr>
      	       <td width="1%">&nbsp;</td>
      	       <td>
	    	  <font size="5" face="Verdana" color="#0066CC">Edit a Claim
	    	  </font>
	    	  <hr>
      	       </td>
   	    </tr>
	 </table>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td valign="top">
   Please Validate and input information here for editing this claim.<br />
   <form name="mod_form" action="process_edit.php" method="Post" onsubmit="return validate_mod()">
   <table width="99%" bgcolor="#C5C5C0" border="0" cellpadding="4" cellspacing="4">
   <input type="hidden" name="system" value="<? echo $system; ?>">
   <input type="hidden" name="claim_id" value="<? echo $claim_id; ?>">
   <input type="hidden" name="line_id" value="<? echo $row['LINE_ID']; ?>">
      <!-- Each system will have to have a different ClaimID Select Box -->
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Claim ID:</font></td>
         <td align="left">
         <?= $claim_id ?>
	 </td>
	 <td>&nbsp;</td>
      </tr>
<?
/*  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if (!$conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
     printf("Please report to TS!");
     exit;
  }
  $cursor = ora_open($conn);
*/
  // Specific data for each type of item
  if($system == "CCDS"){
    $ccd_lot_id = $row['CCD_LOT_ID'];
    $customer_id = $row['CUSTOMER_ID'];
?>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Tracking Number: </font></td>
	 <td align="left">
	    <input type="hidden" name="ccd_lot_id" size="20" value="<?echo "$ccd_lot_id";?>">
	    <font size="2" face="Verdana">
	    <?
	       printf("$ccd_lot_id<br />"); 
	    ?>
	    </font>
	 </td>
	 <td width = "25%" align="right" valign="top">
	    <font size="2" face="Verdana">Mark:</font> 
	    <input type="hidden" name="mark" value="<? echo $row['CCD_MARK'];?>">
	 </td>
         <td align="left"><font size="2" face="Verdana"><?echo $row['CCD_MARK'];?></font></td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Vessel:</font></td>
         <td align="left"><font size="2" face="Verdana">
	    <input type="hidden" name="vessel_name" value="<? echo $row['VESSEL_NAME'];?>">
	    <input type="hidden" name="voyage" value="<? echo $row['VOYAGE'];?>">
         <?
	       echo $row['VESSEL_NAME'] . " " . $row['VOYAGE'];
               if($row['VESSEL_TYPE'] == "K"){
                 echo "  KY";
               }
               if($row['VESSEL_TYPE'] == "C"){
                 echo "  C&S";
               }

	 ?>
	    </font>
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Vessel BL:</font></td>
         <td align="left">
            <input type="textbox" name="vessel_bl" size="20" maxlength="20" value="<?= $row['VESSEL_BL'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
<?
  }else if($system == "BNI"){
    $customer_id = $row['CUSTOMER_ID'];
    // Fill up the claim selection box
?>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Vessel Name:</font></td>
         <td align="left">
            <input type="textbox" name="vessel_name" size="20" maxlength="20" value="<?= $row['VESSEL_NAME'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Voyage:</font></td>
         <td align="left">
            <input type="textbox" name="voyage" size="20" maxlength="20" value="<?= $row['VOYAGE'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">WO #:</font></td>
         <td align="left">
            <input type="textbox" name="bl" size="20" maxlength="20" value="<?= $row['BNI_BL'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Mark:</font></td>
         <td align="left">
            <input type="textbox" name="bni_mark" size="20" maxlength="20" value="<?= $row['BNI_MARK'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
<?
  }else if($system == "RF"){
    $customer_id = $row['CUSTOMER_ID'];
    // Fill up the claim selection box
?>
      <input type="hidden" name="vessel_name" value="<?= $row['VESSEL_NAME'];?>">
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Pallet ID:</font></td>
         <td align="left">
            <input type="textbox" name="pallet_id" size="20" maxlength="20" value="<?= $row['RF_PALLET_ID'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
<?
  if($customer_id != "1512"){
?>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Exporter:</font></td>
         <td align="left">
            <font size="2" face="Verdana">
            <input type="textbox" name="exporter" value="<?= $row['EXPORTER']
?>
">
         </td>
         <td>&nbsp;</td>
      </tr>
<?
  }
?>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Bill of Lading:</font></td>
         <td align="left">
            <input type="textbox" name="bl" size="20" maxlength="20" value="<?= $row['BNI_BL'] ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right"><font size="2" face="Verdana">Vessel:</font></td>
         <td><font size="2" face="Verdana"><? echo $row['VESSEL_NAME']; ?></font>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <font size="2" face="Verdana">Voyage:</font>
         <input type="textbox" name="voyage" size="20" maxlength="20" value="<? echo $row['VOYAGE']; ?>"></td>
      </tr>
<?
  }
  // Now items for each claim
?>
     <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Customer:</font></td>
          <td>
            <select name="customer_id">
<?
         $customer = getCustomerList($cursor, "Y");
         while (list($customer_name, $ora_customer_id) = each($customer)) {
           if($ora_customer_id == $customer_id){
             printf("<option value=\"%s\" SELECTED>%s</option>\n", $ora_customer_id, $customer_name);
             $cust_in = true;
           } else {
             printf("<option value=\"%s\">%s</option>\n", $ora_customer_id, $customer_name);
           }
         }
         if(!$cust_in){
           printf("<option value=\"\" SELECTED>Customer $customer_id Needs to be Added!</option>");
         }
?>
                  </select>
         </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Invoice Date:</font></td>
         <td align="left">
            <input type="textbox" name="claim_date" size="10" maxlength="20" value="<?= date('m/d/Y', strtotime($row['CLAIM_DATE'])) ?>"><a href="javascript:show_calendar('mod_form.claim_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a>
         </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Date Received:</font></td>
         <td align="left">
            <input type="textbox" name="received_date" size="10" maxlength="20" value="<?= date('m/d/Y', strtotime($row['RECEIVED_DATE'])) ?>"><a href="javascript:show_calendar('mod_form.received_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a>
         </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Customer Invoice Number:</font></td>
         <td align="left">
            <input type="textbox" name="invoice_num" size="20" maxlength="20" value="<?= $row['CUSTOMER_INVOICE_NUM'] ?>">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Quantity:</font></td>
         <td align="left">
            <input type="textbox" name="quantity" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['QUANTITY'] ?>"> &nbsp;&nbsp; 
<select name="unit">
<?
  $unit = $row['UNIT'];
  printf("<option value=\"\"");
  if($unit == ""){
    printf("SELECTED");
  }
  printf(">Select Unit</option>");

  printf("<option value=\"Case\"");
  if($unit == "Case"){
    printf("SELECTED");
  }
  printf(">Case(s)</option>");

  printf("<option value=\"Pallet\"");
  if($unit == "Pallet"){
    printf("SELECTED");
  }
  printf(">Pallet(s)</option>");

  printf("<option value=\"Drum\"");
  if($unit == "Drum"){
    printf("SELECTED");
  }
  printf(">Drum(s)</option>");

  printf("<option value=\"Bin\"");
  if($unit == "Bin"){
    printf("SELECTED");
  }
  printf(">Bin(s)</option>");

  printf("<option value=\"Bundle\"");
  if($unit == "Bundle"){
    printf("SELECTED");
  }
  printf(">Bundle(s)</option>");

  printf("<option value=\"Coil\"");
  if($unit == "Coil"){
    printf("SELECTED");
  }
  printf(">Coil(s)</option>");

  printf("<option value=\"MBF\"");
  if($unit == "MBF"){
    printf("SELECTED");
  }
  printf(">MBF(s)</option>");

  printf("<option value=\"Roll\"");
  if($unit == "Roll"){
    printf("SELECTED");
  }
  printf(">Roll(s)</option>");

  printf("<option value=\"Ton\"");
  if($unit == "Ton"){
    printf("SELECTED");
  }
  printf(">Ton(s)</option>");

  printf("<option value=\"Tray\"");
  if($unit == "Tray"){
    printf("SELECTED");
  }
  printf(">Tray(s)</option>");

  printf("<option value=\"Each\"");
  if($unit == "Each"){
    printf("SELECTED");
  }
  printf(">Each (Generic)</option>");
?>
</select>
         </td>
         <td>&nbsp;</td>
      </tr>
<?
  // Only show weight for CCDS
  if($system == "CCDS"){
?>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Weight:</font></td>
         <td align="left">
            <input type="textbox" name="weight" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['WEIGHT'] ?>">
         </td>
         <td>&nbsp;</td>
      </tr>
<?
  }
  else{
    // Variable needs to be held for javascript to work
    printf("<input type=\"hidden\" name=\"weight\" value=\"0\">");
  }
?>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Market Price <? if($system == "CCDS"){
                                                    printf("Per Weight:");
                                                   }
                                                  else{
                                                    printf("Per Qty:");
                                                  }
                                                ?>
            </font></td>
         <td align="left">
            <input type="textbox" name="cost" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['COST'] ?>">
<?
  if($system != "BNI"){
?>
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="dummy_amount" onfocus="update_amount()" size="10" maxlength="20" value="<?  if($system == "CCDS"){
                                      echo $row['COST'] * $row['WEIGHT'];
                                    } else {
                                      echo $row['COST'] * $row['QUANTITY'];
                                    }
                                ?>" disabled>
         </td>
         <td>&nbsp;</td>
      </tr>
<?
  }else{
?>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Gallons:
             </font></td>
         <td align="left">
            <input type="textbox" name="gallons" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['GALLONS'] ?>">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="dummy_amount" onfocus="update_amount()" size="10" maxlength="20" value="<? 
                                if($system == "BNI"){
                                  echo $row['COST'] * $row['GALLONS'];
                                }
                                else{
                                  echo $row['COST'] * $row['QUANTITY'];
                                } ?>" disabled>
         </td>
         <td>&nbsp;</td>
      </tr>
<?
  }
?>
    </table>

    <br /><br />

    <table width="99%" bgcolor="#C5C5C0" border="0" cellpadding="4" cellspacing="4">
      <tr>
         <td width="10%">&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Quantity to Port:</font></td>
         <td width="25%" align="left">
            <input type="textbox" name="claimed" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['QUANTITY_CLAIMED'] ?>">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="amount" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['AMOUNT'] ?>">
         </td>
         <td width="25%">&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Quantity Denied:</font></td>
         <td align="left">
            <input type="textbox" name="denied_qty" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['DENIED_QTY'] ?>">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="denied" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['DENIED_QUANTITY'] ?>">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Quantity to Shipper:</font></td>
         <td align="left">
            <input type="textbox" name="shipper" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['SHIPPER_QTY'] ?>">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="shipper_amount" onfocus="update_amount()" size="10" maxlength="20" value="<?= $row['SHIPPER_AMOUNT'] ?>">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Letter Type:</font></td>
         <td align="left">
           <select name="letter_type">
 <?
   $letter_type = $row['LETTER_TYPE'];
   printf("<option value=\"Customer\"");
   if($letter_type == "Customer"){
     printf("SELECTED");
   }
   printf(">To Customer</option>");

   printf("<option value=\"Split\"");
   if($letter_type == "Split"){
     printf("SELECTED");
   }
   printf(">Split Between Shipper and POW</option>");

   printf("<option value=\"Shipper\"");
   if($letter_type == "Shipper"){
     printf("SELECTED");
   }
   printf(">To Shipper</option>");
   printf("<option value=\"RShipper\"");
   if($letter_type == "RShipper"){
     printf("SELECTED");
   }
   printf(">Secondary Claim</option>");
?>
           </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Claim Type:</font></td>
         <td align="left">
            <select name="claim_type">
<?
  $claim_type = $row['CLAIM_TYPE'];
  printf("<option value=\"Damage\"");
  if($claim_type == "Damage"){
    printf(" SELECTED");
  }
  printf(">Damage</option>");

  printf("<option value=\"USDA\"");
  if($claim_type == "USDA"){
    printf(" SELECTED");
  }
  printf(">USDA Rejection</option>");

  printf("<option value=\"Mis-Shipment\"");
  if($claim_type == "Mis-Shipment"){
    printf(" SELECTED");
  }
  printf(">Mis-Shipment</option>");

  printf("<option value=\"Missing\"");
  if($claim_type == "Missing"){
    printf(" SELECTED");
  }
  printf(">Missing</option>");
?>
            </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Letter Notes:</font></td>
         <td align="left">
            <textarea name="notes" cols="40" rows="6" maxlength="300"><?= $row['NOTES'] ?></textarea>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Internal Notes:</font></td>
         <td align="left">
            <textarea name="internal_notes" cols="40" rows="6" maxlength="300"><?= $row['INTERNAL_NOTES'] ?></textarea>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Exclude from Final Total:</font></td>
         <td align="left">
            <input type="checkbox" name="include_denied" <? if($row['INCLUDE_DENIED'] == 't'){
  echo "CHECKED";
}
 ?>>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td colspan="2" align="center">
	    <table>
	       <tr>
		  <td width="40%" align="right"> 
		     <input type="Submit" value="Edit Claim">&nbsp;&nbsp;
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
//   pg_close($connection);
?>

		  </font></p>
	       </td>
	    </tr>
         </table>
	 <?
	    include("pow_footer.php");

function convertCustomer($cust_id){
  // Get Ora_Number
  if(strlen($cust_id) == 1){
    $ora_cust = "9000" . $cust_id;
  }
  else if(strlen($cust_id) == 2){
    $ora_cust = "900" . $cust_id;
  }
  else if(strlen($cust_id) == 3){
    $ora_cust = "90" . $cust_id;
  }
  else if(strlen($cust_id) == 4){
   $ora_cust = "9" . $cust_id;
  }
  else{
   $ora_cust = $cust_id;
  }
  return $ora_cust;
}

	 ?>
