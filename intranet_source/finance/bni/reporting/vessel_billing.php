<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Holmen Vessel Billing Report";
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
	 <font size="5" face="Verdana" color="#0066CC">Holmen - Vessel Billing Report
	 <hr><? include("../bni_links.php"); ?>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
      <font size="2" face="Verdana">Select a vessel to view the Vessel Billing Report for this vessel. </font><br /><br />
	 <table border="0" bgcolor="#f0f0f0" width="85%" cellpadding="2" cellspacing="2">
	    <tr>
	       <td colspan="4" height="12"></td>
	    </tr>
	    <tr>
	       <td align="center">
	       <form action="vessel_billing_print.php" method="Post" name="print_form">
               <select name="vessel" onchange="document.print_form.submit(this.form)">

            <?
               $conn = ora_logon("PAPINET@RF", "OWNER");
               $cursor = ora_open($conn);

               $stmt = "select distinct v.pow_arrival_num, v.vessel_name, v.ship_sail_date from cargo_tracking c, vessel_profile v where c.pow_arrival_num = v.pow_arrival_num and c.arrival_num = v.arrival_num and c.date_received is not null and c.edi_goodsreceipt = 'Y' and (c.vessel_bill is null or c.vessel_bill <> 'Y')";
               ora_parse($cursor, $stmt);
               ora_exec($cursor);

               ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
               $rows = ora_numrows($cursor);

               if ($rows <= 0 || $row['POW_ARRIVAL_NUM'] == "") {
		 printf("<option value=\"\">All Vessel Has Been Billed Up To Date</option>");
	       } else {
		 printf("<option value=\"\">Please Select A Vessel</option>");
		 
		 do {
		   printf("<option value=\"%s,%s,%s\">%s - %s</option>", $row['POW_ARRIVAL_NUM'], $row['VESSEL_NAME'], $row['SHIP_SAIL_DATE'], $row['POW_ARRIVAL_NUM'], $row['VESSEL_NAME']);
		 } while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	       }
            ?>
	       </select>
	       </td>
	    </tr>
	    <tr>
	       <td height="8"></td>
	    </tr>
	    <tr>
	       <td align="center">
		    <table>
		       <tr>
			  <td width="35%" align="right"> 
			     <input type="Submit" value="  Print  ">
			  </td>
 			  <td width="25%">&nbsp;</td>
			  <td width="40%" align="left">
			      <input type="Reset" value="Reset">
			  </td>
	    	       </tr>
	    	   </table>		
                  </form>
	        </td>
	    </tr>
	    <tr>
	       <td colspan="4" height="12"></td>
	    </tr>
	 </table>
      </td>
      <td valign="top" width="30%">
        <p><img border="0" src="images/printer.jpg" width="180" height="200"></p>
      </td>
   </tr>
</table>
      <?
	 include("pow_footer.php");
      ?>
