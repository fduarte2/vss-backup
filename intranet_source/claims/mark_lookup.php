<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Mark Lookup";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript">

   function validate_mark()
   {
      x = document.mark_form

      mark_free = x.mark_free.value
      mark_select = x.mark_select.value

      // No lot ID is selected
      if(mark_free == "" && mark_select == ""){
	 alert("You need to either select or enter the Mark to retrieve lot information!")
         return false
      }

      if(mark_free != "" && mark_select != ""){
	 alert("You cannot select and enter the Mark at the same time!  Please reset the form and try it again.")
         return false
      }

      return true
   }

   function check(lot_ship) {
      value_array = lot_ship.split(" ")
      document.select_lot.selected_lot.value = value_array[0]
      document.select_lot.selected_ship.value = value_array[1]
   }

   function validate_select_lot()
   {
      x = document.select_lot
      selected_lot = x.selected_lot.value
      lot_ship = x.lot_ship.value

      // No lot is selected
      if (selected_lot == "") {
	 alert("Please click on a radio button to select a lot before you click on Select the Lot!")
         return false
      }

      answer = confirm("You selected the lot with tracking # " + selected_lot + ". \
			\nClick OK to confirm or Cancel to cancel.")
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
         <font size="5" face="Verdana" color="#0066CC">Mark Lookup
         <hr><p>
	 <font size="2" face="Verdana" color="#000080">
	 |<a href="add_ccds.php">Back to Add</a>|
   	 |<a href="track_report.php">Tracker Report</a>|
	</p>
	 </font>
      </td>
   </tr>
</table>

<!-- Mark Selection -->
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <font size="2" face="Verdana">Please enter or select a mark to look up for the intended lot.</font>
	 <br /><br />
    	 <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td width="20%">&nbsp;</td>
	       <td width="30%">&nbsp;</td>
	       <td width="15%">&nbsp;</td>
	       <td width="35%">&nbsp;</td>
      	    </tr>
	       <form action="set_mark.php" method="Post" name="mark_form" onsubmit="return validate_mark()">
	    <tr>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Free Form:</font></td>
	       <td align="left">
		  <INPUT TYPE="text" NAME="mark_free" SIZE="22" value=""><br /></td>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Or Select:</font></td>
	       <td align="left">
		  <select name="mark_select" onchange="document.mark_form.submit(this.form)">
		     <option value="" select="selected">Select the Mark</option>
     		     <? 
			// Gets unique marks from ccds.ccd_received
			$mark = $HTTP_COOKIE_VARS["mark"];

                        include("connect.php");
	 	
   			// open a connection to the database server
   			$conn = pg_connect ("host=$host dbname=$db user=$dbuser");

		        if (!$conn) {
      	   		   die("Could not open connection to database server");
		        }

   			// generate and execute a query
   			$stmt = "select distinct mark from ccd_received where verified = true order by mark";
   			$result = pg_query($conn, $stmt) or 
		  	          die("Error in query: $stmt. " .  pg_last_error($conn));

   			// get the number of rows in the resultset
   			$rows = pg_num_rows($result);

   			if ($rows > 0) {
      	   		   // iterate through resultset
      	   		   for ($i=0; $i<$rows; $i++) {
              		      $row = pg_fetch_row($result, $i);
	      		      $pg_mark = trim($row[0]);

			      // ignore null mark's
			      if($pg_mark == "")
				 continue;

                              if($pg_mark == $mark){
	        	         printf("<option value=\"%s\" selected=\"selected\">%s</option>", 
					 $pg_mark, $pg_mark);
                              }else{
	        		 printf("<option value=\"%s\">%s</option>", $pg_mark, $pg_mark);
           		      }
        	           }
			}
    		     ?>
                  </select>
	       </td>
	    </tr>
	    <tr>
	       <td colspan="4" align="center">
		   <table border="0">
		      <tr>
			 <td width="10%">&nbsp;</td>
			 <td width="30%" align="right"> 
			    <input type="Submit" value="Set the Mark">
			 </td>
			 <td width="5%">&nbsp;</td>
			 <td width="10%" align="left">
			    <input type="Reset" value=" Reset ">
			 </td>
         		 </form>
			 <td width="5%">&nbsp;</td>
			 <td width="30%" align="left">
			    <form action="reset_mark.php" method="Post">
		  	       <input name="page_filename" type="hidden" value="<?= $_SERVER['PHP_SELF'] ?>">
			       <input type="submit" value=" Cancel ">
			 </td>
			    </form>
			 <td width="10%">&nbsp;</td>
      		      </tr>
		   </table>		
	   	</td>
	     </tr>
	     <tr>
		<td colspan="4">&nbsp;</td>
	     </tr>
	 </table>
      </td>
   </tr>
</table>
<br />

<!-- Lot selection -->
<? 
   // Show lot information with mark as cookied
   $ccd_lot_id = $HTTP_COOKIE_VARS["ccd_lot_id"];

   if ($mark != "") {
      $stmt = "select * from ccd_received where verified = true and mark = '$mark' order by ccd_lot_id";
      $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " .  pg_last_error($conn));

      // get the number of rows in the resultset
      $rows = pg_num_rows($result);

      if ($rows <= 0) {
?>

<table border="0" width="80%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <font size="2" face="Verdana">
	 No lot is found with Mark: <?= $mark ?>.
	 </font>
      </td>
   </tr>
</table>

<?
      } else {
?>

<table border="0" width="80%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
      <form name="select_lot" method="post" action="set_mark_lot.php" onsubmit="return validate_select_lot()">
	 <input name="selected_lot" type="hidden" value="">
	 <input name="selected_ship" type="hidden" value="">
	 <input name="current_ccd_lot" type="hidden" value="<?= $ccd_lot_id ?>">

	 <p align="left">
	    <font size="2" face="Verdana">Lots with Mark: <?= $mark ?></font>
	 </p>
	 <table bgcolor="#f0f0f0" width="95%" border="0" cellpadding="4" cellspacing="0">
	    <tr>
	       <td colspan="8" height="8"></td>
	    </tr>
	    <tr>
	       <th width="1%"  align="center">&nbsp;</th>	      
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Tracking #</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Vessel #</u></font></td>
	       <th width="10%" align="center"><font size="2" face="Verdana"><u>Lot #</u></font></td>
	       <th width="10%" align="center"><font size="2" face="Verdana"><u>PO #</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Receiver</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Pallets Rcd</u></font></td>
	       <th width="15%" align="center"><font size="2" face="Verdana"><u>Cases Rcd</u></font></td>
	    </tr>
<?
         for ($i=0; $i<$rows; $i++) {
            $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
?>
	    <tr>
	       <td align="right">
		  <input type="radio" name="lot_ship" value="<?= $row['ccd_lot_id'] . " " . $row['lr_num'] ?>" 
		         onclick="check(this.value)">
	       </td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['ccd_lot_id'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['lr_num'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['lot_id'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['po_num'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['customer_id'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['pallets_received'] ?></font></td>
	       <td align="center"><font size="2" face="Verdana"><?= $row['cases_received'] ?></font></td>
	    </tr>
<?
         }
?>
	    <tr>
	       <td>&nbsp;</td>
	       <td colspan="6" align="center">
		   <table>
		      <tr>
			 <td width="55%" align="right"> 
			    <input type="Submit" value="Select the Lot">
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="35%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
	   	</td>
		<td>&nbsp;</td>
	 </form>
	    </tr>

	    <tr>
	       <td colspan="7" height="8"></td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>

<?
      }
   }
   
   // close database connection
   pg_free_result($result);
   pg_close($conn);
   include("pow_footer.php");
?>
