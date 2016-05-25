<?
  // File: holmen_vessel.php
  //
  // Lynn F. Wang, 8/16/04
  // Interface to let user process Holmen Vessel billing

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance - BNI - Holmen Vessel Billing";
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
  function validate_mod(){
    answer = confirm("Are you sure you want to save the freight charges you made?");

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
	    	  <font size="5" face="Verdana" color="#0066CC">Holmen - Vessel Billing</font>
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
		if ($status == "done") {
		  printf("<b>You have successfully processed Holmen Vessel Billing!</b>");
		} else {
		  printf("From here you can process Holmen vessl billing.");
		}

   		// make database connection
                include("connect.php");
		$rf_conn = ora_logon("PAPINET@$rf", "OWNER");
                $rf_cursor1 = ora_open($rf_conn);
                $rf_cursor2 = ora_open($rf_conn);

                // get all vessels that are ready for vessel billing
                $stmt = "select distinct pow_arrival_num, arrival_num, sum(orig_gross_weight) weight 
                         from cargo_tracking 
                         where date_received is not null and edi_goodsreceipt = 'Y' and 
                           (vessel_bill is null or vessel_bill <> 'Y')
                         group by pow_arrival_num, arrival_num";
                $ora_success = ora_parse($rf_cursor1, $stmt); 
                $ora_success = ora_exec($rf_cursor1);
                ora_fetch_into($rf_cursor1, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

                $rows = ora_numrows($rf_cursor1);
             ?>

		   </font>
		   <br /><br />
		   <table width="80%" bgcolor="#f0f0f0" border="0" cellpadding="2" cellspacing="0">
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>

	     <?
		if ($rows <= 0) {
		  // no vessels to bill
	     ?>

		      <tr>
		         <td colspan="4" align="center">
	                    <font size="2" face="Verdana">
                            All vessels have been billed up to date. &nbsp; Please come back later!</font>
	                 </td>
		      </tr>
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
		   </table>
		
	     <?
		} else {
		  // have vessel to bill
		  $i = 1;
		  $curr_lr_num = "";
	     ?>
		      <tr>
		         <td colspan="4">
			    <font size="2" face="Verdana">The following vessels are ready to bill:</font>
			 </td>
		      </tr>
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
		      <tr>
		         <td width="15%" align="center" nowrap><font size="2" face="Verdana"><u>#</u></font></td>
		         <td width="35%" align="left" nowrap><font size="2" face="Verdana"><u>Vessel</td>
		         <td width="25%" align="left" nowrap>
                            <font size="2" face="Verdana"><u>Holmen Voyage #</td>
		         <td width="25%" align="left" nowrap>
                            <font size="2" face="Verdana"><u>Weight (in MT)</u></font>
                         </td>
	              </tr>
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
	     <?
		   do {
		     $lr_num = $row['POW_ARRIVAL_NUM'];
		     if ($lr_num != $curr_lr_num) {
		       $new_vessel = true;
		     } else {
		       $new_vessel = false;
		     }

		     $mt = round($row['WEIGHT'] / 1000, 2);

		     $stmt = "select distinct vessel_name from vessel_profile where pow_arrival_num = '$lr_num'";
		     $ora_success = ora_parse($rf_cursor2, $stmt);
		     $ora_success = ora_exec($rf_cursor2);
		     ora_fetch_into($rf_cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		     
		     $vessel_name = trim($row2['VESSEL_NAME']);
 
		     if ($new_vessel) {
		       // add a blank line
	     ?>
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
	     <?
		     }
	     ?>
	              <tr>
		         <td align="center">
		            <font size="2" face="Verdana"><?= ($new_vessel ? $i : "") ?></font></td>
	                 <td align="left"><font size="2" face="Verdana">
		            <?= ($new_vessel ? $lr_num . " - " . $vessel_name : "") ?></font></td>
		         <td align="left"><font size="2" face="Verdana"><?= $row['ARRIVAL_NUM'] ?></font></td>
	                 <td align="left"><font size="2" face="Verdana"><?= $mt ?></font></td>
	              </tr>
	     <?
		     if ($lr_num != $curr_lr_num) {
		       $i++;
		       $curr_lr_num = $lr_num;
		     }
		   } while (ora_fetch_into($rf_cursor1, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	     ?>
		      <tr>
		         <td colspan="4">&nbsp;</td>
		      </tr>
		      <tr>
		         <td>&nbsp;</td>
		         <td colspan="3" align="left">
		            <font size="2" face="Verdana">
		            <a href="../reporting/vessel_billing.php">Step 1: &nbsp; Run Vessel Billing Report 
		             for Exceptions</a></font>
                         </td>
		         <td>&nbsp;</td>
		      </tr>
		      <tr>
		         <td colspan="4" height="10"></td>
		      </tr>
		      <tr>
		         <td>&nbsp;</td>
		         <td colspan="3" align="left">
		            <font size="2" face="Verdana">
			    <a href="holmen_vessel_process.php">Step 2: &nbsp; Generate Vessel Billing Charges for Above 
		             Vessel<?= ($i > 1 ? "s" : "") ?></a></font>
                         </td>
		         <td>&nbsp;</td>
		      </tr>
             <?
		}
	     ?>

		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
		   </table>		
		</td>
	     </tr>
	  </table>

 	  <?
	     include("pow_footer.php");
	  ?>
