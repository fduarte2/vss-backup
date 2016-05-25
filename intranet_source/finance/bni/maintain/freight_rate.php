<?
  // Interface to allow the user to update Holmen freight rate matrix

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

  function validate_mod(){
    x = document.mod_form

    surcharge = x.surcharge.value

    if (surcharge == "") {
      alert("Please enter the surcharge percentage rate!")
      return false
    }
 
    for (var i=0; i<x['destination[]'].length; ++i) {
      // we expect all data for each destination and carrier pair
      if (x['destination[]'][i].value != "" && x['carrier[]'][i].value != "") {

	// check carrier rating
	if (x['rating[]'][i].value == "" || !(x['rating[]'][i].value >= 0)) {
	  alert("Please enter a number greater than or equal to 1 for rating of carrier, " + x['carrier[]'][i].value + "!")
	  return false
	}

	// check fixed rate
	if (x['fixed[]'][i].value == "" || !(x['fixed[]'][i].value > 0)) {
	  alert("Please enter a number greater than 0 for fixed rate for destination, " + x['destination[]'][i].value + " and carrier, " + x['carrier[]'][i].value + "!")
	  return false
	}

	// check traveling days
	if (x['days[]'][i].value == "" || !(x['days[]'][i].value > 0)) {
	  alert("Please enter number of traveling days from Port of Wilming to " + x['destination[]'][i].value + " for carrier, " + x['carrier[]'][i].value + "!")
	  return false
	}
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
	    	  <font size="5" face="Verdana" color="#0066CC">Holmen - Update Freight Rate</font>
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
		if ($feedback == 1) {
		  printf("<b>You have successfully saved the changes you made!</b>");
		} else {
		  printf("Here you can add, delete or edit Holmen freight rates for each destination and carrier.");
		}

   		// make database connection
                include("connect.php");
		$conn = ora_logon("PAPINET@$rf", "OWNER");
                $cursor = ora_open($conn);

                // get rate information
                $stmt = "select * from freight_rate_matrix order by destination, carrier_rating";
                $ora_success = ora_parse($cursor, $stmt);
                $ora_success = ora_exec($cursor);

                ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
             ?>

		   </font>
		   <br /><br />

		   <table width="90%" bgcolor="#f0f0f0" border="0" cellpadding="2" cellspacing="0">
		      <form name="mod_form" action="freight_rate_process.php" method="Post" 
		            onsubmit="return validate_mod()">
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
		      <tr>
		         <td colspan="4" align="center">
 		         <font size="2" face="Verdana">SURCHARGE PERCENTAGE RATE: 
	                    <input type="text" name="surcharge" size="20" maxlength="50"
	                           value="<?= $row['SURCHARGE_PERCENT'] ?>"></font>
		         </td>
		      </tr>
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
		      <tr>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>DESTINATION</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>CARRIER</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>RATING</u></font></td>
		         <td align="center" nowrap><font size="2" face="Verdana"><u>FIXED RATE</u></font></td>
	              </tr>
		      <tr>
		         <td colspan="4" height="8"></td>
		      </tr>
	     <?
		   do {
             ?>
	              <tr>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="destination[]" size="20" maxlength="50"
	                           value="<?= $row['DESTINATION'] ?>"></font></td>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="carrier[]" size="20" maxlength="50" 
	                           value="<?= $row['CARRIER'] ?>"></font></td>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="rating[]" size="10" maxlength="9" 
	                           value="<?= $row['CARRIER_RATING'] ?>"></font></td>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="fixed[]" size="10" maxlength="9" 
	                           value="<?= $row['FIXED_RATE'] ?>"></font></td>
	              </tr>
	     <?
		   } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	     ?>
	              <tr>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="destination[]" size="20" maxlength="9" value=""></font></td>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="carrier[]" size="20" maxlength="9" value=""></font></td>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="rating[]" size="10" maxlength="9" value=""></font></td>
	                 <td align="center"><font size="2" face="Verdana">
	                    <input type="text" name="fixed[]" size="10" maxlength="9" value=""></font></td>
	              </tr>
		      <tr>
		         <td colspan="4" height="12"></td>
		      </tr>
		      <tr>
		         <td colspan="4">
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
