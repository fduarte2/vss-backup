<?
  /* File: sail_ship.php
   * 
   * Lynn F. Wang, 09/13/2004
   * This is the interface for user to sail a Holmen vessel, i.e., set the vessel arrival and departure dates.
   */

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finace System - Holmen - Sail Ship";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
  function validate_ship() {
    x = document.set_ship
    
    lr_num = x.lr_num.value
    if (lr_num == "") {
      alert("Please select a vessel!")
      return false
    }
    
    return true
  }

  function validate_sail_ship() {
    x = document.sail_ship
    
    arrival_date = x.arrival_date.value
    if (arrival_date == "") {
      alert("Please enter the ship arrival date!")
      return false
    }
    
    sailing_date = x.sailing_date.value
    if (sailing_date == "") {
      alert("Please enter the ship sailing date!")
      return false
    }
    
    answer = confirm("Are you sure you have entered the right dates?");

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
	    	  <font size="5" face="Verdana" color="#0066CC">Holmen - Verify Ship Sailing Date
	    	  </font>
	    	  <hr>
      	       </td>
   	    </tr>
	 </table>


	 <table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   	    <tr>
            	<td width="1%">&nbsp;</td>
            	<td valign="top" width="70%">
<?
  if($in_lr_num != ""){
    printf("<b><br />You have successfully updated the arrival and departure date, as well as storage starting date, 
            for vessel # $in_lr_num!<br /></b>");
  }

  // get cookied vessel #
  $cookied_lr_num = $HTTP_COOKIE_VARS['lr_num'];

  $conn = ora_logon("PAPINET@RF", "OWNER");
  $cursor = ora_open($conn);

  $stmt = "select distinct pow_arrival_num, vessel_name, arrival_date, ship_sail_date, free_time_end from vessel_profile 
           where pow_arrival_num is not null and pow_arrival_num not in ('0', '9830') order by pow_arrival_num";
  $ora_success = ora_parse($cursor, $stmt);
  $ora_success = ora_exec($cursor);

?>

  <br />
  <font size="2" face="Verdana">From here you can update the arrival date and departure date of a Holmen vessel.  
  Storage starting day is 30 days after the departure day.</font>
  <br /><br /> 
    <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
      <tr>
	 <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
	 <td width="5%">&nbsp;</td>
	 <td width="25%" align="right" valign="top">
	    <font size="2" face="Verdana">Holmen Vessel:</font></td>
	 <td width="65%" align="left">
            <form name="set_ship" action="set_ship.php" method="Post" onsubmit="return validate_ship()">
               <input type="hidden" name="page" value="<?= $_SERVER['PHP_SELF'] ?>">
               <select name="lr_num" onchange="document.set_ship.submit(this.form)">
                  <option value="">Please Select a Ship</option>
<?
         while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
           $lr_num = $row['POW_ARRIVAL_NUM'];
           $vessel_name = $row['VESSEL_NAME'];

	   if ($cookied_lr_num != "" && $cookied_lr_num == $lr_num) {
	     printf("<option value=\"$lr_num\" selected=\"selected\">$lr_num - $vessel_name</option>");

	     // get ship arrival date and departure date
	     if ($row['ARRIVAL_DATE'] != "") {
	       $arrival_date = date("m/d/Y", strtotime($row['ARRIVAL_DATE']));
	     } else {
	       $arrival_date = "";
	     }
	     
	     if ($row['SHIP_SAIL_DATE'] != "") {
	       $sailing_date = date("m/d/Y", strtotime($row['SHIP_SAIL_DATE']));
	     } else {
	       $sailing_date = "";
	     }
	     
	     if ($row['FREE_TIME_END'] != "") {
	       $free_time_end = date("m/d/Y", strtotime($row['FREE_TIME_END']));
	     } else {
	       $free_time_end = "";
	     }
	   } else {
	     printf("<option value=\"$lr_num\">$lr_num - $vessel_name</option>");
	   }
         }
?>
              </select>
	 </td>
	 <td width="5%">&nbsp;</td>
      </tr>
      <tr>
	  <td colspan="2">&nbsp;</td>
 	  <td>
	     <input type="submit" value="Select Ship">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <input type="reset" value="Reset">
	  </form>
	  </td>
         <td>&nbsp;</td>
      </tr>

<?
  // display current arrival & departure date
  if ($cookied_lr_num != "") {
?>

      <tr>
  	 <td colspan="4" height="6"></td>
      </tr>
      <tr>
         <td>&nbsp;</td>
	 <td align="right"><font size="2" face="Verdana">Arrival Date:</font></td>
	 <td>
         <form name="sail_ship" method="Post" action="sail_ship_process.php" onsubmit="return validate_sail_ship()">
            <input type="hidden" name="lr_num" value="<?= $cookied_lr_num ?>">
            <input type="text" name="arrival_date" size="20" value="<?= $arrival_date ?>">
            <a href="javascript:show_calendar('sail_ship.arrival_date');" 
               onmouseover="window.status='Date Picker';return true;" 
               onmouseout="window.status='';return true;">
               <img src="images/show-calendar.gif" width=20 height=20 border=0>
            </a>
         </td>
         <td>&nbsp;</td>
       </tr>
      <tr>
         <td>&nbsp;</td>
	 <td align="right"><font size="2" face="Verdana">Sailing Date:</font></td>
	 <td>
            <input type="text" name="sailing_date" size="20" value="<?= $sailing_date ?>">
            <a href="javascript:show_calendar('sail_ship.sailing_date');" 
               onmouseover="window.status='Date Picker';return true;" 
               onmouseout="window.status='';return true;">
               <img src="images/show-calendar.gif" width=20 height=20 border=0>
            </a>
         </td>
         <td>&nbsp;</td>
       </tr>
      <tr>
         <td>&nbsp;</td>
	 <td align="right"><font size="2" face="Verdana">Storage Starts:</font></td>
	 <td>
            <input type="text" name="free_time_end" disabled="disabled" size="20" value="<?= $free_time_end; ?>">
         </td>
         <td>&nbsp;</td>
       </tr>
      <tr>
  	 <td colspan="4" height="10"></td>
      </tr>
       <tr>
	  <td colspan="2">&nbsp;</td>
 	  <td>
	     <input type="submit" value="Set Date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	     <input type="reset" value="Reset">
	  </td>
         <td>&nbsp;</td>
      </tr>
<?
  }
?>
      <tr>
  	 <td colspan="4">&nbsp;</td>
      </tr>
   </table>
</form>
		  </font></p>
	       </td>
	    </tr>
         </table>
	 <?
	    include("pow_footer.php");
	 ?>
