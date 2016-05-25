<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF - Warehouse F Shipment";
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
   function validate_form()
   {
      x = document.report_form
      lr_num = x.lr_num.value

      if (lr_num == "") {
        alert("Please select vessel number!");
        return false
      }

      return true;
   }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Sail Vessel for RF Storage</font>
         <br />
	 <hr><? include("../rf_links.php"); ?>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
     <td width="1%">&nbsp;</td>
     <td valign="top" width="70%">
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="60%">
	 <font size="2" face="Verdana">
      <? if ($returning_num == "") {
           echo "Select a vessel of this fruit season to view the report.";
         } else { 
           echo "<b>No Information is found for Vessel # $returning_num!</b>";
         }
      ?>
           <br /><br />
         </font>
	 <table width="80%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Vessel:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="post" action="f_shipment_print.php" onsubmit="return validate_form()">
                 <select name="vessel" onchange="document.report_form.submit(this.form)">>
                   <option value="">Please Select Vessel</option>
	       <?
		  include("connect.php");
                  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
                  $cursor = ora_open($conn);

                  // get the minimum vessel # for this fruit season, which starts from November
                  if (date("m") >= 11) {
		    $min_lr_num = (date("Y") + 1) . "001";
		  } else {
		    $min_lr_num = date("Y") . "001";
		  }
                                                                                
                  $ora_sql = "select lr_num, vessel_name from vessel_profile 
                              where lr_num like '200%' and lr_num >= $min_lr_num 
                              order by lr_num desc";
                  ora_parse($cursor, $ora_sql);
                  ora_exec($cursor);

                  while (ora_fetch($cursor)){
                    $lr_num = ora_getcolumn($cursor, 0);
                    $ship_name = ora_getcolumn($cursor, 1);
                    printf("<option value=\"%s,%s\">%s - %s</option>", 
			   $lr_num, $ship_name, $lr_num, $ship_name);
                  }

                  // close Oracle connection
                  ora_close($cursor);
                  ora_logoff($conn);
              ?>
                 </select>
              </td>
            </tr>
	    <tr>
	       <td colspan="4" height="8"></td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="View Report">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
      <td width="40%" align="left" valign="top">
         <p><img border="0" src="images/gnome-word.png" width="100" height="100"></p>
      </td>
   </tr>
</table>
<? include("pow_footer.php"); ?>
