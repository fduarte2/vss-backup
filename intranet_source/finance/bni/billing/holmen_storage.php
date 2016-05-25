<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance - BNI - Holmen Storage";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
   	    <tr>
      	       <td width="1%">&nbsp;</td>
      	       <td>
	    	  <font size="5" face="Verdana" color="#0066CC">Holmen - Storage Charge</font>
	    	  <hr><? include("../bni_links.php"); ?>
	          </font>
      	       </td>
   	    </tr>
	 </table>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td valign="top">
	           <font size="2" face="Verdana"><p>
		   The BNI system generates Holmen Storage prebills automatically at the beginning of each month 
		   for Holmen paper rolls that are due for storage.</p>
		   <br />
		<?
                  // A lot of date manipulations to do...
                  include("connect.php");
                  $today = date('m/d/Y');
                  $current_month = date('F');
                  $last_month = date("F", mktime(0, 0, 0, date("m"), 0, date("Y")));
                  $last_month_num = date("m", mktime(0, 0, 0, date("m"), 0, date("Y")));


                  // Make DB connection
                  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
                  if (!$conn) {
		     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($conn));
		     printf("Please report to TS!");
		     exit;
		  }
                  $cursor = ora_open($conn);

                  $stmt = "select to_char(max(service_start), 'MM') last_billed_month from billing
                           where billing_type = 'H_STORAGE' and service_status <> 'DELETED'";
                  ora_parse($cursor, $stmt);
                  ora_exec($cursor);
                  ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

                  $last_billed_month = $row['LAST_BILLED_MONTH'];

                  if ($last_billed_month == "") {
		    $info = "We do not have any Holmen Storage bills in the system.  
                             Please come back later to check the status or contact TS!";
		  } else {
		    if ($last_billed_month != $last_month_num) {
		      $info = "Holmen Storage prebills have not been generated for $last_month.  
                               Please report to TS! ";
		    } else {
		      $stmt = "select min(billing_num) start_billing_num, max(billing_num) end_billing_num 
                               from billing where service_status = 'PREINVOICE' and billing_type = 'H_STORAGE'";
		      ora_parse($cursor, $stmt);
		      ora_exec($cursor);
		      ora_fetch($cursor);
		      $rows = ora_numrows($cursor);

		      $start_billing_num = ora_getcolumn($cursor, 0);
		      $end_billing_num = ora_getcolumn($cursor, 1);
		      
		      if ($start_billing_num != "" && $end_billing_num != "") {    // there are prebills
			$info = "Holmen Storage prebills for $last_month are ready for you to review. 
                                 Please go to <a href=\"print_prebill.php\">Print Prebills</a> page to print the 
                                 prebills with bill numbers starting at $start_billing_num and ending on 
                                 $end_billing_num.</b>";
		      } else {
			$info = "Holmen Storage prebills for $last_month have been invoiced.  Please come back
                                 to check the status when $current_month is over!";
		      }
		    }
		  }

                  ora_close($cursor);
                  ora_logoff($conn);

		?>

   <table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
      <tr>
	 <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="center" valign="top">
            <font size="2" face="Verdana"><?= $info ?></font>
	 </td>
	 <td>&nbsp;</td>
      </tr>
      <tr>
  	 <td colspan="4">&nbsp;</td>
      </tr>
   </table>
   <br />

<?
   include("pow_footer.php");
?>
