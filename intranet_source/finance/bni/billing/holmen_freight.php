<?
  // Interface to allow the user to record Holmen Paper freight charges

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Holmen Freight Charge";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript">

  function update_total(i) {
     // note that i is 1 greater than the array index of corresponding line
     x = document.mod_form
     
     if (x['fixed[]'][i-1].value != "" && x['fixed[]'][i-1].value >= 0) {
       curr_fixed = x['fixed[]'][i-1].value
     } else {
       curr_fixed = 0
     }

     if (x['surcharge[]'][i-1].value != "" && x['surcharge[]'][i-1].value >= 0) {
       curr_surcharge = x['surcharge[]'][i-1].value
     } else {
       curr_surcharge = 0
     }

     curr_total = parseFloat(curr_fixed) + parseFloat(curr_surcharge)

     if (curr_total > 0) {
       x['total[]'][i-1].value = curr_total
     } else {
       x['total[]'][i-1].value = ""
     }
  }


  function validate_mod(){
    x = document.mod_form
 
    for (var i=0; i<x['order_num[]'].length; ++i) {
      // validate numbers
      if (x['paid[]'][i].value != "" && !(x['paid[]'][i].value >= 0)) {
	alert("Please enter a number greater than 0 for amount paid for BOL # " + x['order_num[]'][i].value)
	return false
      }

      if (x['fixed[]'][i].value != "" && !(x['fixed[]'][i].value > 0)) {
	alert("Please enter a number greater than 0 for fixed amount of BOL # " + x['order_num[]'][i].value)
	return false
      }

      if (x['surcharge[]'][i].value != "" && !(x['surcharge[]'][i].value >= 0)) {
	alert("Please enter a number greater than or equal to 0 for surcharge of BOL # " + x['order_num[]'][i].value)
	return false
      }
    }

    answer = confirm("Are you sure you want to save the changes you made?");

    if (answer == true) {
       return true
    } else {
       return false
    }
  }
</script>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
   	    <tr>
      	       <td width="1%">&nbsp;</td>
      	       <td>
	    	  <font size="5" face="Verdana" color="#0066CC">Holmen - Record Freight Charge</font>
	    	  <hr><? include("../bni_links.php"); ?>
	          </font>
      	       </td>
   	    </tr>
	 </table>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td valign="top" width="90%">
	           <font size="2" face="Verdana">
	     <?
 		// check whether we came from the processing page
		if ($result == 1) {
		  printf("<b>You have successfully saved the changes you made!</b>");
		} else {
		  printf("From here you can record Holmen freight charges. The shipping orders are ordered 
                          by shipping date, trip ID, destination and then carrier.");
		}

   		// make database connection
                include("connect.php");
                $order_detail = "order_detail";

		$conn = ora_logon("PAPINET@$rf", "OWNER");
                $cursor = ora_open($conn);

                // get all shipping orders that are not freight charge reconciled
                $stmt = "select * from $order_detail 
                         where order_type = 'DELIVERY' and order_status = 'EXECUTED' and transport_mode = 'Road'
                            and (freight_reconciled is null or freight_reconciled <> 'Y')
                         order by ship_date, trailer_num, destination, carrier_name, calloff_num, order_num";

                $ora_success = ora_parse($cursor, $stmt);
                $ora_success = ora_exec($cursor);

                ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                $rows = ora_numrows($cursor);
             ?>

		   </font>
		   <br /><br />

		   <table width="99%" bgcolor="#f0f0f0" border="1" cellpadding="2" cellspacing="0">
		      <form name="mod_form" action="holmen_freight_process.php" method="Post" 
		            onsubmit="return validate_mod()">
		      <tr>
		         <td colspan="10" height="12"></td>
		      </tr>

	     <?
		if ($rows <= 0) {
		  // no shipping orders to process
	     ?>

		      <tr>
		         <td colspan="14">All shipping orders have freight charge reconciled. Please come back later!</td>
		      </tr>
		      <tr>
		         <td colspan="14" height="12"></td>
		      </tr>
		   </table>
		
	     <?
		} else {
		  // show charges one at a time
		  $i = 1;
	     ?>
		      <tr>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>Recon</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Transport/ Calloff</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Deliverynote/ BOL #</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>Date</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Trip Id/ Trailer Id</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>Destination</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>Carrier</u></font></td>
		         <td align="right" nowrap><font size="2" face="Verdana"><u>Tons</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>Pkgs</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Expected Value</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Holmen Received</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Trucking Fixed</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Trucking Surcharge</u></font></td>
		         <td align="center"><font size="2" face="Verdana"><u>Total Paid</u></font></td>
	              </tr>
	     <?
		   do {
		     $fixed_charge = $row['FIXED_CHARGE']; 
		     $surcharge = $row['SURCHARGE']; 

		     if ($fixed_charge != "" || $surcharge != "") {
		       $total = $fixed_charge + $surcharge;
		     } else {
		       $total = "";
		     }
             ?>
	              <tr>
		         <td align="center">
		            <input type="checkbox" name="reconciled[]" value="<?= $row['ORDER_NUM'] ?>"></td>
	                 <td align="center"><font size="2" face="Verdana"><?= $row['CALLOFF_NUM'] ?></font></td>
	                 <td align="left"><font size="2" face="Verdana"><?= $row['ORDER_NUM'] ?></font></td>
	                    <input type="hidden" name="order_num[]" value="<?= $row['ORDER_NUM'] ?>">
	                 <td align="center">
	                    <font size="2" face="Verdana"><?= date('m/d/y', strtotime($row['SHIP_DATE'])) ?></font></td> 
		         <td align="center"><font size="2" face="Verdana">
 		            <?= ($row['TRAILER_NUM'] == "" ? "&nbsp;" : $row['TRAILER_NUM']) ?></font></td>
	                 <td align="center"><font size="2" face="Verdana"><?= $row['DESTINATION'] ?></font></td>
	                 <td align="center"><font size="2" face="Verdana"><?= $row['CARRIER_NAME'] ?></font></td>
	                 <td align="right"><font size="2" face="Verdana">
		             <?= number_format($row['TONS'], 2, '.', ','); ?></font></td>
	                 <td align="center"><font size="2" face="Verdana"><?= $row['PACKAGES'] ?></font></td>
	                 <td align="center"><font size="2" face="Verdana"><?= $row['FREIGHT_EXPECTED'] ?></font></td>
	                 <td align="center"><font size="2" face="Verdana">
			    <input type="text" name="paid[]" size="8" maxlength="9"
		                   value="<?= $row['FREIGHT_PAID'] ?>"></font></td>
	                 <td align="center"><font size="2" face="Verdana">
			    <input type="text" name="fixed[]" size="8" maxlength="9" onchange="update_total(<?= $i ?>)" 
		                   value="<?= $fixed_charge ?>"></font>
                         </td>
	                 <td align="center"><font size="2" face="Verdana">
			    <input type="text" name="surcharge[]" size="8" maxlength="9" onchange="update_total(<?= $i ?>)"
		                   value="<?= $surcharge ?>">
		            </font>
                         </td>
	                 <td align="center"><font size="2" face="Verdana">
			    <input type="text" name="total[]" size="8" maxlength="9" value="<?= $total ?>"></font>
                         </td>
	              </tr>
	     <?
		     if ($row['FREIGHT_REMARK'] != "") {
	     ?>
	              <tr>
	                 <td>&nbsp;</td>
	                 <td align="left" colspan="9"><font size="2" face="Verdana"><?= $row['FREIGHT_REMARK'] ?></font>
	                 </td>
	              </tr>
	     <?
		     }
	              $i++;
		   } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
	     ?>

		      <tr>
		         <td colspan="14" height="12"></td>
		      </tr>
		      <tr>
		         <td colspan="14">
		            <table border="0" width="80%" align="center">
		               <tr>
		                  <td width="40%" align="right"><input type="Submit" value=" Save "></td>
  		                  <td width="20%">&nbsp;</td>
				  <td width="40%" align="left"><input type="Reset" value="Reset"></td>
			       </tr>
			    </table>		
			    </form>
			 </td>
		      </tr>
		   </table>		
		   </form>
		</td>
	     </tr>
	  </table>
	 <?
	    include("pow_footer.php");
	 ?>
