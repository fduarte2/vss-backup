<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Generate Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<!-- Run BNI Invoices - Main page -->

<script type="text/javascript">

   function check(billing_type) {
      x = document.select_type
      x.selected_type.value = billing_type
   }

   function validate_form()
   {
      x = document.select_type
      selected_type = x.selected_type.value
      selection_type = x.selection_type.value

      if (selection_type == "" && selected_type == "") {
		// No order is selected
		alert("You need to select a billing type!")
		return false
	      } else {
		if (selection_type != "") {
		  selected_type = selection_type
		  x.selected_type.value = selected_type
		}
		if (x.selected_type.value == "TRANSFER" || x.selected_type.value == "LEASE" || x.selected_type.value == "4PDI") { //|| x.selected_type.value == "LABOR" 
			x.action = "print_bills.php"
		}
      }

      answer = confirm("Are you sure you are ready to generate " + selected_type + " invoices?")

      if (answer == true) {
	 return true
      } else {
         return false
      }
   }

</script>

<?
// Variables for switching from testing database tables and those of production
include("connect.php");
include("billing_functions.php");

// Make Oracle Database connection
$ora_conn = ora_logon("SAG_OWNER@$bni", "SAG");
//$ora_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
if (!$ora_conn) {
  printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($ora_conn));
  printf("Please report to TS!\n");
  exit;
}

// open up a cursor over this connection
$cursor = ora_open($ora_conn);

// retrieve all invoice records for the given billing type
$stmt = "select type, to_char(run_date, 'MM/DD/YY') run_date, start_inv_no, end_inv_no from invoicedate 
         where bill_type = 'B' and type not like '%CCDS%' order by type";
$ora_success = ora_parse($cursor, $stmt);
$ora_success = ora_exec($cursor);
ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

$rows = ora_numrows($cursor);
if ($rows <= 0) {
  $has_prebill = false;
} else {
  $has_prebill = true;
}

?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">BNI - Generate Invoices</font>
         <hr><? include("../bni_links.php"); ?> 
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="80%">
<?
      if ($type != "") {
	// There is no prebills of the selected type; returned from the processing page.
?>
        <p align="left">There is no <?= $type ?> prebills available. Please 
        <a href="run_prebill.php">Generate Prebills</a> first and then come back to try it again.</p>
        </font>
<?
      } else {
	// a new page
?>
      <form name="select_type" method="post" action="run_invoice_process.php" onsubmit="return validate_form()">
         <input name="generate_type" type="hidden" value="INVOICE">
         <input name="selected_type" type="hidden" value="">
	 <p align="left">Please select a billing type to generate and print invoices of that type.</p></font>
	 <table bgcolor="#f0f0f0" width="95%" border="0" cellpadding="4" cellspacing="0">
	    <tr>
	       <td colspan="5" height="10"></td>
	    </tr>
<?
	 if (!$has_prebill) {
?>	 
            <input name="selection_type" type="hidden" value="">
	    <tr>
               <td>&nbsp;</td>
	       <td colspan="4">
                  <font size="2" face="Verdana">Currently there is no prebills to generate invoices!</font>
               </td>
            </tr>
	    <tr>
	       <td colspan="5" height="10"></td>
	    </tr>
<?
	 } else {
?>
	    <tr>
               <td>&nbsp;</td>
	       <td colspan="4">
                  <font size="2" face="Verdana">You may either select from the selection box below:</font>
               </td>
            </tr>
	    <tr>
	       <td colspan="5" height="4"></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td colspan="4">
	       <select name="selection_type" size="6">
<?
	   do {
	     $run_date = date("m/d/y", strtotime($row['RUN_DATE']));
	     $billing_type = trim($row['TYPE']);
?>	 
		  <option value="<?= $billing_type ?>">
		     <?= $billing_type . " : " . $run_date . " : " .  
		         $row['START_INV_NO'] . " - " . $row['END_INV_NO'] ?>
		  </option>
<?
	   } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	       </select>
	       </td>
	    </tr>
	    <tr>
	       <td colspan="5" height="6"></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td colspan="4">
                  <font size="2" face="Verdana">Or click on the radio buttons to select:</font>
               </td>
            </tr>
<?
	 }

      // close the connection and release resources
      ora_close($cursor);
      ora_logoff($ora_conn);
?>	 
	    <tr>
               <td width="2%">&nbsp;</td>
	       <td width="6%">
	          <input type="radio" name="billing_type" value="Advance Billing" onclick="check(this.value)"></td>
	       <td width="50%"><font size="2" face="Verdana">Advance Billing for Vessel #: <input type="text" name="AB_LR_NUM" size="10"></input>
 			</font><font size="1" face="Verdana">(optional:  leave blank for ALL)</font></td>
	       <td width="6%"><input type="radio" name="billing_type" value="BNI Storage" onclick="check(this.value)"></td>
	       <td width="38%"><font size="2" face="Verdana">BNI Storage</font></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td>
	          <input type="radio" name="billing_type" value="Dockage/Lines" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Dockage/Lines for Vessel #: <input type="text" name="DL_LR_NUM" size="10"></input>
	       </font><font size="1" face="Verdana">(optional:  leave blank for ALL)</font></td>
	       <td><input type="radio" name="billing_type" value="Equipment" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Equipment</font></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td><input type="radio" name="billing_type" value="FumInsp" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Fumigation / Inspection</font></td>
	       <td><input type="radio" name="billing_type" value="Holmen Storage" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Holmen Storage</font></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td><input type="radio" name="billing_type" value="Holmen Vessel" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Holmen Vessel</font></td>
	       <td><input type="radio" name="billing_type" value="Labor" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Labor (Security)</font></td>
	    </tr>
	    <tr>
               <td>&nbsp;</td>
	       <td><input type="radio" name="billing_type" value="Miscellaneous" 
			  onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Miscellaneous for Vessel #: <input type="text" name="MISC_LR_NUM" size="10"></input>
	       </font><font size="1" face="Verdana">(optional:  leave blank for ALL)</font></td>
	       <td><input type="radio" name="billing_type" value="Truck Loading" 
			  onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Truck Loading</font></td>
	    <tr>
               <td>&nbsp;</td>
	       <td><input type="radio" name="billing_type" value="Truck Unloading" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Truck Unloading</font></td>
	       <td><input type="radio" name="billing_type" value="Wharfage" 
			  onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Wharfage for Vessel #: <input type="text" name="WF_LR_NUM" size="10"></input>
	       </font><font size="1" face="Verdana">(optional:  leave blank for ALL)</font></td>
	    </tr>
            <tr>
               <td>&nbsp;</td>
               <td><input type="radio" name="billing_type" value="Star Vessel" onclick="check(this.value)"></td>
               <td><font size="2" face="Verdana">Star Vessel</font></td>
               <td><input type="radio" name="billing_type" value="S-Wharfage"
                          onclick="check(this.value)"></td>
               <td><font size="2" face="Verdana">Star Wharfage</font></td>
            </tr>

	    <tr>
	       <td colspan="5" height="10"></td>
	    </tr>
	    <tr>
	       <td colspan="5" align="center">
		   <table border="0">
		      <tr>
			 <td width="40%" align="right"> 
			    <input type="Submit" value="Generate Invoices">
			 </td>
			 <td width="20%">&nbsp;</td>
			 <td width="40%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
	   	</td>
	    </tr>
	    <tr>
	       <td colspan="4" height="10"></td>
	    </tr>
	 </table>

	 <p align="left">Billing V2 Modules:</p></font>
	 <table bgcolor="#f0f0f0" width="95%" border="0" cellpadding="4" cellspacing="0">
	    <tr>
	       <td colspan="5" height="10"></td>
	    </tr>
		<tr>
			   <td width="2%">&nbsp;</td>
		   <td width="6%"><input type="radio" name="billing_type" value="TRANSFER" onclick="check(this.value)"></td>
		   <td width="50%"><font size="2" face="Verdana">Transfer (Scanned)</font></td>
	       <td width="6%"><input type="radio" name="billing_type" value="LEASE" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Leases</font></td>
		</tr>
		<tr>
			   <td width="2%">&nbsp;</td>
<!--		   <td width="6%"><input type="radio" name="billing_type" value="LABOR" onclick="check(this.value)"></td>
		   <td width="50%"><font size="2" face="Verdana">Labor (Operations)</font></td> !-->
			<td width="6%">&nbsp;</td>
			<td width="50%">&nbsp;</td>
	       <td width="6%"><input type="radio" name="billing_type" value="4PDI" onclick="check(this.value)"></td>
	       <td><font size="2" face="Verdana">Terminal Services (Chilean Invoices)</font></td>
		</tr>
	    <tr>
	       <td colspan="5" align="center">
		   <table border="0">
		      <tr>
			 <td width="35%" align="right"> 
			    <input type="Submit" value="Generate Invoices">
			 </td>
			 <td width="20%">&nbsp;</td>
			 <td width="45%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
			</td>
	    </tr>
	</table>

	</form>

<?
	}
?>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
