<script type="text/javascript">
   function validate_lot()
   {
      x = document.lot_form
      y = document.ship_form

      lot_id = x.lot_id.value
      lot_free = x.lot_free.value
      mark_free = x.mark_free.value
      ccd_lot_id = x.ccd_lot_id.value
      ccd_lot_free = x.ccd_lot_free.value

      x.lr_num.value = y.lr_num.value
      lr_num = x.lr_num.value

      // No lot ID is selected
      if(ccd_lot_id == "" && ccd_lot_free == "Tracking Number" && lot_id == "" && lot_free == "Lot" && mark_free == "Mark"){
	 alert("You need to either select or enter a Lot, Mark pair, or select \
		\nor enter a Tracking Number to retrieve lot information!")
         return false
      }

      if(ccd_lot_id == "" && ccd_lot_free == "Tracking Number"){	// user selects from manifest
	 if(lr_num == ""){
	   alert("To select a lot from manifest, you have to choose a ship first!")
	   return false
	 }           

	 if(lot_free == "Lot" && mark_free == "Mark")
      	 {
            if(lot_id == "")
            { 
               alert("You need to either select or enter a Lot, Mark pair, or \
		      \nselect or enter a Tracking Number!")
               return false
            } 
         }else{
           if(lot_id != "")
           {
             alert("You cannot select a lot, mark pair and enter free-form at the same time! \
		    \nPlease remove the free-form text or un-select the Lot.")
             return false
           }
	  
	   if(mark_free == "Mark")
           {
             alert("If you choose to free form lot & mark, Mark is required. \
		    \nLot Id is required for C&S vessels but not KY vessels!")
             return false
           }
         }
      }else{							// user selects by ccd lot id
         if(ccd_lot_id == "")
         {
           if(ccd_lot_free == "Tracking Number")
           { 
               alert("You need to either select or enter a Lot, Mark pair, or \
		      \nselect or enter a Tracking Number!")
               return false
           } 
         }else{
           if(ccd_lot_free != "Tracking Number")
           {
             alert("You cannot select a Tracking Number and enter free form at the same time! \
		   \nPlease remove the free-form text or un-select the Tracking Number.")
             return false
           }
         }
      }

      return true
   }

</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
    	 <table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="0">
	    <tr>
	       <td width="20%" height="8"></td>
	       <td width="30%" height="8"></td>
	       <td width="15%" height="8"></td>
	       <td width="35%" height="8"></td>
      	    </tr>
	    <tr>
	       <td align="right"><font size="2" face="Verdana"><b>Lot & Mark:</b></font></td>
	       <td colspan="3">&nbsp;</td>
	    </tr>
     	    <tr>
	       <td align="right">
	    	  <font size="2" face="Verdana">Ship Name:</font>
	       </td>
	       <td align="left" valign="middle" colspan="2">
		  <form name="ship_form" action="set_ship.php" method="Post">
		     <input name="page_filename" type="hidden" value="<?= $_SERVER['PHP_SELF'] ?>">
	             <select name="lr_num" onchange="document.ship_form.submit(this.form)">
			<option value="">Ship Name - LR Number</option>
	       <?
		  $lr_num = $HTTP_COOKIE_VARS["lrnum"];
		  $ccd_lot_id = $HTTP_COOKIE_VARS["ccd_lot_id"];

		  //retrieve lr_num from ccd_received if ccd_lot_id is set
		  if ($ccd_lot_id != "") {
                     include("connect.php");

		     $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
		     if (!$connection){
		        die("Could not open connection to database server");
  		     }
  
 		     $sql = "select lr_num from ccd_received where ccd_lot_id = '$ccd_lot_id'";
  		     $result = pg_query($connection, $sql) 
			       or die("Error in query: $sql. " .  pg_last_error($connection));
		     $rows = pg_num_rows($result);

		     if($rows > 0){
		        $row = pg_fetch_row($result, $i);
		        $lr_num = $row[0];
  		     }

	             pg_free_result($result);
		     pg_close($connection);
		  }

 	          $conn = ora_logon("SAG_OWNER@RF", "OWNER");
 	          $cursor = ora_open($conn);
 	          $ora_sql = "select lr_num, vessel_name from vessel_profile order by lr_num desc";
 	          $statement = ora_parse($cursor, $ora_sql);
 	          ora_exec($cursor);
                  $i = 0;
 	          while (ora_fetch($cursor)){
   		     $lr_num_ora = ora_getcolumn($cursor, 0);

                     if($i == 0){
                       $temp_lr = $lr_num_ora;
                       $i = 1;
                     }

   		     $ship_name = ora_getcolumn($cursor, 1);

                     if($lr_num == $lr_num_ora){
        		printf("<option value=\"%s\" selected=\"true\">%s - %s</option>", $lr_num_ora, 
			$ship_name, $lr_num_ora);
                     }else{
   		        printf("<option value=\"%s\">%s - %s</option>", $lr_num_ora, $ship_name, $lr_num_ora);
                     }
	          }

		  ora_close($cursor);
	 	  ora_logoff($conn);
	       ?>
	             </select>
	       </td>
	       <td>&nbsp;</td>
                  </form>
            </tr>
            <tr>
	       <td align="right" valign="top">
	    	  <font size="2" face="Verdana">Free Form:</font></td>
	       <td align="left" valign="middle">
	 	  <form action="track_report_print.php" method="Post" name="lot_form" 
			onsubmit="return validate_lot()">
	  	     <input name="lr_num" type="hidden" value="">
	    	     <input type="textbox" name="lot_free" size="8" maxlength="20" value="Lot">
	    	     <input type="textbox" name="mark_free" size="12" maxlength="20" value="Mark">
	       </td>
	       <td align="right" valign="top">
	          <font size="2" face="Verdana">Or Select:</font></td>
	       <td align="left" valign="middle">
	    	     <select name="lot_id" onchange="document.lot_form.submit(this.form)">
	       	        <option value=""> Select a Lot ID </option>
	       <?
		  $lr_num = $HTTP_COOKIE_VARS["lrnum"];
                  include("connect.php");

		  $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
		  if (!$connection){
		     die("Could not open connection to database server");
  		  }
  
 		  $sql = "select lot_id, mark from ccd_received where lr_num = '$lr_num' and verified = true 
			  order by lot_id";
  		  $result = pg_query($connection, $sql) 
			    or die("Error in query: $sql. " .  pg_last_error($connection));
		  $rows = pg_num_rows($result);
		  for ($i=0; $i<$rows; $i++){
		     $row = pg_fetch_row($result, $i);
		     $lot_id = $row[0];
                     $mark = $row[1];
		     printf("<option value=\"%s, %s\">%s - %s</option>", $lot_id, $mark, $lot_id, $mark);
  		  }

	          pg_free_result($result);
		  pg_close($connection);
	       ?>
		  </select>
	       </td>
	    </tr>
	    <tr>
	       <td align="right"><font size="2" face="Verdana"><b>Tracking #:</b></font></td>
	       <td colspan="3">&nbsp;</td>
	    </tr>
	    <tr>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Free Form:</font></td>
	       <td align="left">
		  <INPUT TYPE="text" NAME="ccd_lot_free" SIZE="22" value="Tracking Number"><br /></td>
	       <td align="right" valign="top">
		  <font size="2" face="Verdana">Or Select:</font></td>
	       <td align="left">
		  <select name="ccd_lot_id" onchange="document.lot_form.submit(this.form)">
		     <option value="" select="selected">Tracking Number</option>
     		     <? 
			// Gets CCD lot ID's from ccds.ccd_received
			$ccd_lot_id = $HTTP_COOKIE_VARS["ccd_lot_id"];

                        include("connect.php");
	 	
   			// open a connection to the database server
   			$connection = pg_connect ("host=$host dbname=$db user=$dbuser");

		        if (!$connection) {
      	   		   die("Could not open connection to database server");
		        }

   			// generate and execute a query
			if ($lr_num == "") {
   			   $query = "select ccd_lot_id from ccd_received where verified = true 
				     order by ccd_lot_id";
			} else {
   			   $query = "select ccd_lot_id from ccd_received where verified = true 
				     and lr_num = '$lr_num' order by ccd_lot_id";
			}

   			$result = pg_query($connection, $query) or 
		  	          die("Error in query: $query. " .  pg_last_error($connection));

   			// get the number of rows in the resultset
   			$rows = pg_num_rows($result);

   			if ($rows > 0) {
      	   		   // iterate through resultset
      	   		   for ($i=0; $i<$rows; $i++) {
              		      $row = pg_fetch_row($result, $i);
	      		      $ccd_lot_pg = trim($row[0]);

			      // ignore null ccd_lot_id's
			      if($ccd_lot_pg == "")
				 continue;

                              if($ccd_lot_id == $ccd_lot_pg){
	        	         printf("<option value=\"%s\" selected=\"selected\">%s</option>", 
				        $ccd_lot_pg, $ccd_lot_pg);
                              }else{
	        		 printf("<option value=\"%s\">%s</option>", $ccd_lot_pg, $ccd_lot_pg);
           		      }
        	           }
			}

		        // close database connection
	 	        pg_free_result($result);
 			pg_close($connection);
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
			    <input type="Submit" value="Create Tracker Report">
			 </td>
			 <td width="5%">&nbsp;</td>
			 <td width="10%" align="left">
			    <input type="Reset" value=" Reset ">
			 </td>
         		 </form>
			 <td width="5%">&nbsp;</td>
			 <td width="30%" align="left">
			    <form action="reset_lot.php" method="Post">
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
		<td colspan="4" height="8"></td>
	     </tr>
	 </table>
      </td>
   </tr>
</table>
