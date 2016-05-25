<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Add Claim";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }

  // Begin Claims Code
   include("connect.php");
   include("billing_functions.php");
   /*
   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
   if (!$connection){
     die("Could not open connection to database server");
   }
*/

	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if (!$conn) {
	 printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
	 printf("Please report to TS!");
	 exit;
	}
	$cursor = ora_open($conn);
	$ex_postgres_cursor = ora_open($conn);   
   
   // Make it easier to append
   $my_claim_id = $HTTP_POST_VARS['claim_id'];
   if($my_claim_id != ""){
     $sql = "select * from claim_log_oralce where claim_id = '$my_claim_id'";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	ora_fetch_into($ex_postgres_cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
//     $result = pg_query($connection, $stmt) or die("Error in query: $stmt. " .  pg_last_error($connection));
//     $row = pg_fetch_row($result, 0, PGSQL_ASSOC);
     $invoice_num = $row['CUSTOMER_INVOICE_NUM'];
     $claim_date = $row['CLAIM_DATE'];
     $letter_type = $row['LETTER_TYPE'];
     $vessel_bl = $row['BNI_BL'];
     $customer_id = $row['CUSTOMER_ID'];
     $received_date = $row['RECEIVED_DATE'];
     $voyage = $row['VOYAGE'];
     $vessel_name = $row['VESSEL_NAME'];
   }
?>

<script type="text/javascript">
<? include("js_func.php") ?>

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
     quantity = x.quantity.value
     saved_quantity = x.quantity.value
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
//         amount = cost * port
         shipper_amount = cost * shipper
       }
       else if(letter_type == "Customer"){
//         amount = cost * gallons
         shipper_amount = 0
       }
       else if(letter_type == "Shipper" || letter_type == "RShipper"){
//         amount = 0
         shipper_amount = cost * total_quantity
       }
       else{
//         amount = 0
         shipper_amount = 0
       }
     }

     // Round some stuff off
     dummy_amount_total = Math.round(dummy_amount_total * 100) / 100;
//     shipper_amount = Math.round(shipper_amount * 100) / 100;
//     amount = Math.round(amount * 100) / 100;
//     denied_amount = Math.round(denied_amount * 100) / 100;

     x.dummy_amount.value = dummy_amount_total
//     x.shipper_amount.value = shipper_amount
//     x.amount.value = amount
 //    x.denied.value = denied_amount

   }

</script>

<script language="JavaScript" src="/functions/calendar.js"></script>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
   	    <tr>
      	       <td width="1%">&nbsp;</td>
      	       <td>
	    	  <font size="5" face="Verdana" color="#0066CC">Create a Claim
	    	  </font>
	    	  <hr>
      	       </td>
   	    </tr>
	 </table>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td valign="top">
   Please Validate and input information here for your new claim.<br />
   <form name="mod_form2" action="add_main.php?system=<?= $system ?>" method="Post">
   <table width="99%" bgcolor="#C5C5C0" border="0" cellpadding="4" cellspacing="4">
   <input type="hidden" name="system" value="<? echo $system; ?>">
      <!-- Each system will have to have a different ClaimID Select Box -->
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Claim ID:</font></td>
         <td align="left">
         <select name="claim_id" onchange="document.mod_form2.submit(this.form)">
          <option value="">New Claim</option>
<?

  // Specific data for each type of item
  if($system == "CCDS"){
    $ccd_lot_id = $HTTP_COOKIE_VARS["ccd_lot_id"];
    if($ccd_lot_id == ""){
      header("Location: add_ccds.php");
      exit;
    }

    // Fill up the claim selection box
    $sql = "select distinct claim_id, customer_invoice_num from claim_log_oracle where completed = 'f' and system = '$system'";
    $claim_result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    $rows = pg_num_rows($claim_result);
    for($i = 0; $i < $rows; $i++){
      $row = pg_fetch_row($claim_result, $i);
      $claim = $row[0];
      $cust_invoice = $row[1];
      if($my_claim_id == $claim){
        echo "<option value=\"$claim\" SELECTED>$cust_invoice - $claim</option>";
      }
      else{
        echo "<option value=\"$claim\">$cust_invoice - $claim</option>";
      }
    }
        printf(" </select>");

    $sql = "select d.*, c.customer_name from ccd_received d, ccd_customer c where d.customer_id = c.customer_id and ccd_lot_id = '$ccd_lot_id'";
    $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    // We are going to find this since we found it in selection screen
    $ccds_row = pg_fetch_row($result, 0, PGSQL_ASSOC);
    $customer_id = $ccds_row['customer_id'];
    $customer_id = ClaimsconvertCustomer($customer_id);
?>
	 </td>
	 <td>&nbsp;</td>
      </tr>
   </form>
   <form name="mod_form" action="process_add.php" method="Post" onsubmit="return validate_mod()">
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Tracking Number: </font></td>
	 <td align="left">
	    <input type="hidden" name="ccd_lot_id" size="20" value="<?echo "$ccd_lot_id";?>">
            <input type="hidden" name="claim_id" value="<? echo $my_claim_id; ?>">
	    <font size="2" face="Verdana">
	    <?
	       printf("$ccd_lot_id<br />"); 
	    ?>
	    </font>
	 </td>
	 <td width = "25%" align="right" valign="top">
	    <font size="2" face="Verdana">Mark:</font> 
	    <input type="hidden" name="mark" value="<? echo $ccds_row['mark'];?>">
	    <input type="hidden" name="ccd_cut" value="<? echo $ccds_row['cut'];?>">
	    <input type="hidden" name="ship_type" value="<? echo $ccds_row['ship_type'];?>">
	    <input type="hidden" name="product_name" value="<? echo $ccds_row['product'];?>">
	    <input type="hidden" name="system" value="CCDS">
	 </td>
         <td align="left"><font size="2" face="Verdana"><?echo $ccds_row['mark'];?></font></td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Vessel:</font></td>
         <td align="left"><font size="2" face="Verdana">
	    <input type="hidden" name="vessel_name" value="<? echo $ccds_row['vessel'];?>">
	    <input type="hidden" name="voyage" value="<? echo $ccds_row['voyage'];?>">
         <?
	       echo $ccds_row['vessel'] . " " . $ccds_row['voyage'];
               if($ccds_row['ship_type'] == "K"){
                 echo "  KY";
               }
               if($ccds_row['ship_type'] == "C"){
                 echo "  C&S";
               }
 
	 ?>
	    </font>
	 </td>
	 <td align="right">
	    <font size="2" face="Verdana">PO #:</font></td>
         <td align="left"><font size="2" face="Verdana">
	 <? echo $ccds_row['po_num']; ?>
	    </font>
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Vessel BL:</font></td>
         <td align="left">
            <input type="textbox" name="vessel_bl" size="20" maxlength="20" value="<?= $vessel_bl ?>">
	 </td>
	 <td>&nbsp;</td>
      </tr>
<?
  }elseif($system == "BNI"){
    // Fill up the claim selection box
    $sql = "select distinct CLAIM_ID, CUSTOMER_INVOICE_NUM from claim_log where completed = 'f' and system = '$system'";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	while(ora_fetch_into($ex_postgres_cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      $claim = $Short_Term_Row["CLAIM_ID"];
      $cust_invoice = $row["CUSTOMER_INVOICE_NUM"];
      if($my_claim_id == $claim){
        echo "<option value=\"$claim\" SELECTED>$cust_invoice - $claim</option>";
      }
      else{
        echo "<option value=\"$claim\">$cust_invoice - $claim</option>";
      }
	/*
    $claim_result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    $rows = pg_num_rows($claim_result);
    for($i = 0; $i < $rows; $i++){
      $row = pg_fetch_row($claim_result, $i);
      $claim = $row[0];
      $cust_invoice = $row[1];
      if($my_claim_id == $claim){
        echo "<option value=\"$claim\" SELECTED>$cust_invoice - $claim</option>";
      }
      else{
        echo "<option value=\"$claim\">$cust_invoice - $claim</option>";
      }
*/    }
?>
         </select>
	 </td>
	 <td>&nbsp;</td>
      </tr>
   </form>
   <form name="mod_form" action="process_add.php" method="Post" onsubmit="return validate_mod()">
      <input type="hidden" name="system" value="<?= $system ?>">
      <input type="hidden" name="claim_id" value="<? echo $my_claim_id; ?>">
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Vessel Name:</font></td>
         <td align="left">
            <input type="textbox" name="vessel_name" size="20" maxlength="30" value="">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Voyage Name:</font></td>
         <td align="left">
            <input type="textbox" name="voyage" size="20" maxlength="10" value="">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">WO #:</font></td>
         <td align="left">
            <input type="textbox" name="bl" size="20" maxlength="20" value="">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Mark:</font></td>
         <td align="left">
            <input type="textbox" name="bni_mark" size="20" maxlength="20" value="">
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Product:</font></td>
         <td align="left">
            <input type="textbox" name="product_name" size="20" maxlength="30" value="">
	 </td>
	 <td>&nbsp;</td>
      </tr>
<?
  }else if($system == "RF"){
    $pallet_id = $HTTP_COOKIE_VARS["pallet_id"];
    $schema = $HTTP_COOKIE_VARS["schema"];
    if($pallet_id == ""){
      header("Location: add_rf.php");
      exit;
    }

    // Log into RF and get some information
    $sql = "select ct.*, vp.vessel_name, cp.commodity_name from $schema.cargo_tracking ct, SAG_OWNER.vessel_profile vp, SAG_OWNER.commodity_profile cp where ct.pallet_id = '$pallet_id' and ct.arrival_num = vp.lr_num and ct.commodity_code = cp.commodity_code";
    $RF_conn = ora_logon("SAG_OWNER@RF", "OWNER");
    $RF_cursor = ora_open($RF_conn);
    $ora_success = ora_parse($RF_cursor, $sql);
    $ora_success = ora_exec($RF_cursor);
    ora_fetch_into($RF_cursor, $RF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $test_pallet = $RF_row['PALLET_ID'];
    if($test_pallet == ""){
      header("Location: add_rf.php?input=1");
      exit;
    }
    $customer_id = $RF_row['RECEIVER_ID'];

    $sql = "select distinct CLAIM_ID, CUSTOMER_INVOICE_NUM from claim_log where completed = 'f' and system = '$system'";
	ora_parse($ex_postgres_cursor, $sql);
	ora_exec($ex_postgres_cursor);
	while(ora_fetch_into($ex_postgres_cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      $claim = $Short_Term_Row["CLAIM_ID"];
      $cust_invoice = $row["CUSTOMER_INVOICE_NUM"];
      if($my_claim_id == $claim){
        echo "<option value=\"$claim\" SELECTED>$cust_invoice - $claim</option>";
      }
      else{
        echo "<option value=\"$claim\">$cust_invoice - $claim</option>";
      }
	}

/*
    // Fill up the claim selection box
    $sql = "select distinct claim_id, customer_invoice_num from claim_log where completed = 'f' and system = '$system'";
    $claim_result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
    $rows = pg_num_rows($claim_result);
    $tst = 0;
    for($i = 0; $i < $rows; $i++){
      $row = pg_fetch_row($claim_result, $i);
      $claim = $row[0];
      $cust_invoice = $row[1];
      if($my_claim_id == $claim){
        echo "<option value=\"$claim\" SELECTED>$cust_invoice - $claim</option>";
        $tst = 1;
      }
      else{
        echo "<option value=\"$claim\">$cust_invoice - $claim</option>";
      }
    }
    if($tst != 1){
      $vessel_name = $RF_row['VESSEL_NAME'];
    }
*/
?>
         </select>
	 </td>
	 <td>&nbsp;</td>
      </tr>
   </form>
   <form name="mod_form" action="process_add.php" method="Post" onsubmit="return validate_mod()">
    <input type="hidden" name="system" value="<?= $system ?>">
    <input type="hidden" name="product_name" value="<? echo $RF_row['COMMODITY_NAME'];?>">
    <input type="hidden" name="mark" value="FRUIT">
    <input type="hidden" name="claim_id" value="<? echo $my_claim_id; ?>">
      <tr>
	 <td>&nbsp;</td>
	 <td align="right">
	    <font size="2" face="Verdana">Vessel: </font></td>
         <td align="left">
            <input type="textbox" name="vessel_name" value="<? echo $vessel_name; ?>" size="20">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <font size="2" face="Verdana">Voyage: <input type="textbox" name="voyage" value="<? echo $voyage; ?>"></font>
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td align="right" valign="top">
	    <font size="2" face="Verdana">Pallet ID:</font></td>
         <td align="left">
            <font size="2" face="Verdana"><?= $pallet_id ?></font>
            <input type="hidden" name="pallet_id" value="<?= $pallet_id ?>">
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
            <input type="textbox" name="exporter" value="<?= $row['exporter'] ?>">
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
            <input type="textbox" name="bl" size="20" maxlength="20" value="<?= $vessel_bl ?>">
	 </td>
	 <td>&nbsp;</td>
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
            <input type="textbox" name="claim_date" size="10" maxlength="20" value="<?= date('m/d/Y', strtotime($claim_date)) ?>"><a href="javascript:show_calendar('mod_form.claim_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a>
         </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Date Received:</font></td>
         <td align="left">
            <input type="textbox" name="received_date" size="10" maxlength="20" value="<?= date('m/d/Y', strtotime($received_date)) ?>"><a href="javascript:show_calendar('mod_form.received_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width=24 height=22 border=0></a>
         </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Customer Invoice Number:</font></td>
         <td align="left">
            <input type="textbox" name="invoice_num" size="20" maxlength="20" value="<?= $invoice_num ?>">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Quantity:</font></td>
         <td align="left">
            <input type="textbox" name="quantity" onfocus="update_amount()" size="10" maxlength="20" value=""> &nbsp;&nbsp; 
<select name="unit">
<option value="">Select Unit</option>
<option value="Case">Case(s)</option>
<option value="Pallet">Pallet(s)</option>
<option value="Drum">Drum(s)</option>
<option value="Bin">Bin(s)</option>
<option value="Bundle">Bundle(s)</option>
<option value="Coil">Coil(s)</option>
<option value="MBF">MBF(s)</option>
<option value="Roll">Roll(s)</option>
<option value="Ton">Ton(s)</option>
<option value="Tray">Tray(s)</option>
<option value="Each">Each (Generic)</option>
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
            <input type="textbox" name="weight" onfocus="update_amount()" size="10" maxlength="20" value="">
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
            <input type="textbox" name="cost" onfocus="update_amount()" size="10" maxlength="20" value="">
<?
  if($system != "BNI"){
?>
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="dummy_amount" onfocus="update_amount()" size="10" maxlength="20" value="" disabled>
         </td>
         <td>&nbsp;</td>
      </tr>
<?
  }
  if($system == "BNI"){
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
            <input type="textbox" name="gallons" onfocus="update_amount()" size="10" maxlength="20" value="">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="dummy_amount" onfocus="update_amount()" size="10" maxlength="20" value="" disabled>
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
            <input type="textbox" name="claimed" onfocus="update_amount()" size="10" maxlength="20" value="">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="amount" onfocus="update_amount()" size="10" maxlength="20" value="">
         </td>
         <td width="25%">&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Denied:</font></td>
         <td align="left">
            <input type="textbox" name="denied_qty" onfocus="update_amount()" size="10" maxlength="20" value="">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="denied" onfocus="update_amount()" size="10" maxlength="20" value="">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Quantity to Ship Line:</font></td>
         <td align="left">
            <input type="textbox" name="shipper" onfocus="update_amount()" size="10" maxlength="20" value="">
            <font size="2" face="Verdana">Amount:</font>
            <input type="textbox" name="shipper_amount" onfocus="update_amount()" size="10" maxlength="20" value="">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Letter Type:</font></td>
         <td align="left">
           <select name="letter_type" onchange="update_amount()">
 <?
   printf("<option value=\"Customer\"");
   if($letter_type == "Customer"){
     printf("SELECTED");
   }
   printf(">To Customer</option>");

   printf("<option value=\"Split\"");
   if($letter_type == "Split"){
     printf("SELECTED");
   }
   printf(">Split Between Ship Line and POW</option>");

   printf("<option value=\"Shipper\"");
   if($letter_type == "Shipper"){
     printf("SELECTED");
   }
   printf(">To Ship Line</option>");
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
              <option value="Damage" SELECTED>Damage</option>
              <option value="USDA">USDA Rejection</option>
              <option value="Mis-Shipment">Mis-Shipment</option>
              <option value="Missing">Missing</option>
            </select>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Letter Notes:</font></td>
         <td align="left">
            <textarea name="notes" cols="40" rows="6" maxlength="300"></textarea>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Internal Notes:</font></td>
         <td align="left">
            <textarea name="internal_notes" cols="40" rows="6" maxlength="300"></textarea>

         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right">
            <font size="2" face="Verdana">Exclude from Final Total:</font></td>
         <td align="left">
            <input type="checkbox" name="include_denied">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
	 <td>&nbsp;</td>
	 <td colspan="2" align="center">
	    <table>
	       <tr>
		  <td width="40%" align="right"> 
		     <input type="Submit" value="Create Claim">&nbsp;&nbsp;
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

function ClaimsconvertCustomer($cust_id){
  // Get Ora_Number
  if(strlen($cust_id) == 1){
    $ora_cust = "9000" . $cust_id;
  }
  elseif(strlen($cust_id) == 2){
    $ora_cust = "900" . $cust_id;
  }
  elseif(strlen($cust_id) == 3){
    $ora_cust = "90" . $cust_id;
  }
  elseif(strlen($cust_id) == 4){
   $ora_cust = "9" . $cust_id;
  }
  else{
   $ora_cust = $cust_id;
  }
  return $ora_cust;
}

	 ?>
