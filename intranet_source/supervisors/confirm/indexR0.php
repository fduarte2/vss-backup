
<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Vessel Discharge Confirm";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $cursor = ora_open($conn);

  $ship = $HTTP_POST_VARS['ship'];
  list($lr_num, $vessel_name) = split("-", $ship, 2);


?>

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

   function select_vessel()
   {
	document.report_form.submit();
   }

   function confirm_report()
   {
	var answer = confirm ("Are you sure you want to confirm this vessel discharge report?")
	if (answer)
	{
		document.report_form.action = "process.php";
		document.report_form.submit();
	}
   }

   function cancel_confirm()
   {
	document.location = "index.php";
   }
</script>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Vessel Discharge Confirm</font>
         <br />
	 <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
	printf("Please select a vessel to review and confirm Vessel Discharge Report. ");
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
	       <form name="report_form" method="post" action="" onsubmit="return validate_form()">
	          <select name="ship" onchange="javascript:select_vessel()">
		     <option value = "">----Select a vessel----</option>
	       <?
 	          $ora_sql = "select distinct d.arrival_num, v.vessel_name, v.lr_num 
			      from vessel_profile v, css_discharge d
			      where d.arrival_num = v.arrival_num and d.arrival_num not in
			      (select distinct arrival_num from ccd_cargo_verified_damage)
			      order by v.lr_num desc";  
 	          $statement = ora_parse($cursor, $ora_sql);
 	          ora_exec($cursor);

                  while (ora_fetch($cursor)){
   		    $lr_num_ora = ora_getcolumn($cursor, 0);
   		    $ship_name = ora_getcolumn($cursor, 1);
		    if ($lr_num_ora == $lr_num){
		    	$strSelect = "selected";
		    }else{
			$strSelect = "";
		    }
		    printf("<option value=\"%s-%s\" $strSelect>%s - %s</option>", $lr_num_ora, $ship_name, 
			   $lr_num_ora, $ship_name);
 	          }

	       ?>
	          </select>
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
                <?

        	if ($lr_num <>"" ){
               		$stmt = "select sum(d.qty_damaged) qty_damaged, d.description
                                 from   CCD_CARGO_TRACKING T, CCD_CUSTOMER_PROFILE C,
                                        (select lot_id, arrival_num, mark, description, sum(qty_damaged) qty_damaged
                                        from CCD_CARGO_DAMAGED D, CCD_DAMAGE_PROFILE P
                                        where d.damage_type_id = p.damage_type_id and d.damage_type_id <>'DP'
                                        group by lot_id, arrival_num, mark, description) D
                                 where  t.arrival_num = d.arrival_num and t.lot_id = d.lot_id and t.mark = d.mark and
                                        t.receiver_id = c.customer_id(+) and t.arrival_num = '$lr_num' 
                        	 group by d.description order by sum(d.qty_damaged) desc";

                        ora_parse($cursor, $stmt);
                        ora_exec($cursor);

                ?>
                    <tr>
		       <td width="5%">&nbsp;</td>
                       <td colspan="3"><font size = "2" face="Verdana" color="red"><b>Damage Summay:</b></font>
			<a  href="damage_report.php?ship=<?=$ship?>" target="_blank">
				<font size = "2" face="Verdana"> View Detail</font></a>	
		       </td>
                    </tr>
                <?
                        while(ora_fetch($cursor)){
                                $tot_damage += ora_getcolumn($cursor, 0);
                ?>

                    <tr>
                       <td width="5%">&nbsp;</td>
                       <td colspan="3"><font size = "2" face="Verdana" color="red">
                                <?= ora_getcolumn($cursor, 1) ?>: <?= ora_getcolumn($cursor, 0) ?> Case(s)</font></td>
                    </tr>
                <?
                        }
                ?>
                    <tr>
                       <td width="5%">&nbsp;</td>
                       <td colspan="3"><font size = "2" face="Verdana" color="red">
                       <b>TOTAL DAMAGES: <?= $tot_damage?> Case(s)</b></font></td>
                    </tr>
            <tr>
               <td colspan="2">&nbsp;</td>
               <td align="left">
                  <input type="button" name="confirm" value=" Confirm " onclick ="javascript:confirm_report()">&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" name="cancel" value="   Cancel  " onclick = "javascript:cancel_confirm()">
               </td>
               </form>
               <td>&nbsp;</td>
            </tr>



                <?
                    }
             	?>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>
<?
  // close Oracle connection
  ora_close($cursor);
  ora_logoff($conn);
?>


