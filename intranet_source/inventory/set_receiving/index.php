<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

   $user = $userdata['username'];
?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script type="text/javascript">
   function validate_sail_ship() {
       x = document.sail_ship
       lr_num = x.lr_num.value
       new_date = x.new_date.value

       if (lr_num == "") {
         alert("Please select a ship to update its sailing date!");
	 return false
       }

       if (new_date == "") {
         alert("Please select or enter the ship sailing date!");
	 return false
       }

       return true
   }
</script>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	    <tr>
               <td width="1%">&nbsp;</td>
	       <td>
	          <p align="left">
	          <font size="5" face="Verdana" color="#0066CC">Set Receiving Date</font>
	          <hr>
	          </p>
	       </td>
            </tr>
         </table>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
            <tr>
                <td width="1%">&nbsp;</td>
                <td valign="top">

		<? if ($input != "") {
		     echo "<b> Process complete! </b><br /><br />";
		   }
                ?>

		   <font size="2" face="Verdana">Please select a vessel and update the date on which vessel 
                   discharge FINISHED. This date is the receiving date of cargos coming with the vessel. </font>
		   <br /><br />

		   <table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
		      <tr>
		         <td colspan="4">&nbsp;</td>
		      </tr>
		      <tr>
			 <td width="5%">&nbsp;</td>
			 <td width="25%" align="right" valign="top">
			    <font size="2" face="Verdana">Ship Name:</font></td>
			 <form action="sail_ship_set.php" name="set_ship" method="Post">
			 <td width="75%" align="left" valign="middle">
			    <select name="lr_num" onchange="document.set_ship.submit(this.form)">
			    <option value="">Please Select a ship</option>
                   <?
                      $cook_lr_num = $HTTP_COOKIE_VARS['lr_num'];
                      $conn = ora_logon("SAG_OWNER@BNI", "SAG");
                      $cursor = ora_open($conn);
                      $sql = "select lr_num, vessel_name from vessel_profile where lr_num < '100000' and lr_num > '8000' order by lr_num desc";
                      $statement = ora_parse($cursor, $sql);
                      ora_exec($cursor);
                      while (ora_fetch($cursor)){
                         $lr_num = ora_getcolumn($cursor, 0);
                         $ship_name = ora_getcolumn($cursor, 1);
                         if($cook_lr_num == $lr_num){
                           printf("<option value=\"%s\" selected=\"true\">%s - %s</option>", $lr_num,  $ship_name, $lr_num);
                         }
                         else{
                           printf("<option value=\"%s\">%s - %s</option>", $lr_num,  $ship_name, $lr_num);
                         }
                      }
                      ora_close($cursor);
                      ora_logoff($conn);
                   ?>
		            </select><br /><br />
			    <input type="submit" value="Select Ship">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="reset" value="Reset">
			 </form>
		         </td>
		      </tr>
		      <tr>
			 <td>&nbsp;</td>
			 <td valign="top" align="right"><font size="2" face="Verdana"><nobr>Date Cargo Received:</nobr></font></td>
			 <td>
			    <p align="left">
			    <font size="2" face="Verdana">
	                 <form method="Post" name="sail_ship" action="sail_ship_process.php" 
	                       onsubmit="return validate_sail_ship()">
	                    <input type="hidden" name="lr_num" value="<? echo $cook_lr_num; ?>">
	                    <input type="text" size="20" name="new_date" value="<? echo $current_ship_date; ?>">
	                    <a href="javascript:show_calendar('sail_ship.new_date');" 
                               onmouseover="window.status='Date Picker';return true;" 
                               onmouseout="window.status='';return true;">
	                       <img src="images/show-calendar.gif" width=24 height=22 border=0>
	                    </a>
	                 <br /><br />
	                    <input type="submit" value="Set Date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                    <input type="reset" value="Reset">
	                 </td>
                      </tr>
                   </table>
		</td>
             </tr>
	  </table>
<? include("pow_footer.php"); ?>
