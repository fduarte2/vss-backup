<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Fruit Reports";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

<!-- Vessel Discharge Report - Main Page -->

<script type="text/javascript">
   function validate_form()
   {
      x = document.report_form

      ship = x.ship.value

      if (ship == "") {
	 alert("You need to select a vessel to view its discharge report!")
         return false
      }
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Vessel Discharge Report</font>
         <br />
	 <hr>
      </td>
   </tr>
<?
   if ($eport_customer_id != 0) {
?>
   <tr>
      <td>&nbsp;</td>
      <td>
	 <font size="2" face="Verdana">
	 | <a href="../">Home</a>
         </font>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
<?
   }
?>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
	   printf("Please select a vessel to view Vessel Discharge Report. You
may also view pallets with overage/shortage only. Note that the report refreshes itself every 5 minutes to give you most updated information.");
      ?>
         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Ship name:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="get" action="report.php" onsubmit="return validate_form()">
	          <select name="ship">
	       <?
                  $year = date('Y');
                  $year_1 = $year + 1;
                  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
                  $cursor = ora_open($conn);
 	          $ora_sql = "select lr_num, vessel_name from vessel_profile 
                              where (lr_num like '$year%' and lr_num <> '$year"
                              . "000') or (lr_num like '$year_1%' and lr_num <> '$year_1" . "000') order by lr_num desc";
 	          $statement = ora_parse($cursor, $ora_sql);
 	          ora_exec($cursor);

                  while (ora_fetch($cursor)){
   		    $lr_num_ora = ora_getcolumn($cursor, 0);
   		    $ship_name = ora_getcolumn($cursor, 1);
		    printf("<option value=\"%s-%s\">%s - %s</option>", $lr_num_ora, $ship_name, 
			   $lr_num_ora, $ship_name);
 	          }

	          // close Oracle connection
	          ora_close($cursor);
	          ora_logoff($conn);
	       ?>
	          </select>
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <?
	       if ($eport_customer_id == 0) {
	    ?>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="right"><font size="2" face="Verdana">Pallet ID:</font></td>
	       <td align="left">
	          <input type="text" name="pallet_id" size="22" value="">
	       </td>
	       <td>&nbsp;</td>
	    </tr>
	    <?
	       }
	    ?>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="checkbox" name="condense" value="On">
	          <font size="2" face="Verdana">Show Pallets with Over/Short Only</font>
	       </td>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="View the Report">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
