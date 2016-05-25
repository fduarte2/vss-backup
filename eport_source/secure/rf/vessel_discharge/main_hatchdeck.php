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

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Vessel Discharge by Hatchdeck</font>
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
	&nbsp;      
	</td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
<?
   }
?>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
         if ($eport_customer_id != 0) {
	   printf("Please select a vessel to view Vessel Discharge Report. Note that the report refreshes itself every 5 minutes to give you most updated information.");
	 } else {
	   printf("Please select a vessel to view the report. You may input a Pallet ID or part of a Pallet ID to view pallets with that ID or similar ID's only. You may also view pallets with overage/shortage only.");
	 }
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
	       <form name="report_form" method="post" action="report_hatchdeck.php" onsubmit="return validate_form()">
	          <select name="ship">
	       <?
                  $year = date('Y');
                  $year_1 = $year + 1;
                  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
                  $cursor = ora_open($conn);
 	          $ora_sql = "select vp.lr_num, vessel_name from vessel_profile vp, voyage voy where vp.lr_num = voy.lr_num and ship_prefix in ('CHILEAN', 'ARG FRUIT') and to_char(vp.lr_num) in (select distinct arrival_num from cargo_tracking where qty_in_house != 0) and voy.date_expected >= sysdate - 180 order by vp.lr_num desc";
					//  and date_received is not null
                  //            where (lr_num like '$year%' and lr_num <> '$year"
                  //            . "000') or (lr_num like '$year_1%' and lr_num <> '$year_1" . "000') order by lr_num desc";
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
<!--	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="checkbox" name="condense" value="On">
	          <font size="2" face="Verdana">Show Pallets with Over/Short Only</font>
	       </td>
	       <td>&nbsp;</td>
	    </tr> !-->
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="checkbox" name="hatch" value="OneHatch">
	          <font size="2" face="Verdana">Show Only Hatch:  <input type="text" name="hatch_spec" size="2" maxlength="2"></font>
	       </td>
	       <td>&nbsp;</td>
	    </tr>
		<tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="radio" name="discharge" value="all" checked>All&nbsp;&nbsp;&nbsp;&nbsp;
	          <input type="radio" name="discharge" value="tbd">To Be Discharged&nbsp;&nbsp;&nbsp;&nbsp;
	          <input type="radio" name="discharge" value="only">Discharged Only
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
