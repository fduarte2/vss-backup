<?
   $user = $HTTP_COOKIE_VARS[directoruser];
   if($user == ""){
      header("Location: ../../director_login.php");
      exit;
   }
?>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p>
	 <font size="5" face="Verdana" color="#0066CC">Reserve Company Cars</font>
	 <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <font size="2" face="Verdana" color="#000080"><b>Car schedule for today:</b></font>
	 <br />
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <table align="left" width="80%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
<?
  include("defines.php");
  $host = "localhost";
  $user = "admin";
  $db = "ccds";
  $data = "company_car";
  
  $connection = pg_connect ("host=$host dbname=$db user=$user");
  if (!$connection){
       die("Could not open connection to database server");
  }
  $today = date('m-j-y');
  $sql = "select * from $data where reserve_date = '" . $today . "'";
  $result = pg_query($connection, $sql) or die("Error in query: $sql. " .  pg_last_error($connection));
  $rows = pg_num_rows($result);
  if ($rows > 0) {
?>
	    <tr>
	       <th>Car Name</th>
	       <th>Reserve Date</th>
	       <th>Start Time</th>
	       <th>End Time</th>
	    </tr>
<?
     for ($i=0; $i<$rows; $i++){
        $row = pg_fetch_row($result, $i);
	// show current reservations
?>
	    <tr>
	       <td><?= $row[0] ?></td>
	       <td><?= $row[2] ?></td>
	       <td><?= $row[3] ?></td>
	       <td><?= $row[4] ?></td>
	    </tr>
<?
     }
?>
	 </table>
      </td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td>To resolve conflicts, please contact <a href="mailto:aoutlaw@port.state.de.us">Astrid Outlaw</a>
      </td>
   </tr>

<?
  } else {
?>
	    <tr>
	       <td colspan="4"><font size="2" face="Verdana">No reservations for today.</font></td>
	    </tr>
	 </table>
<?
  }

  pg_close($connection);
?>
      </td>
   </tr>
   <tr>
      <td colspan="2">&nbsp;</td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td>
	 <font size="2" face="Verdana" color="#000080"><b>Make a reservation?</b></font>
	 <br />
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <form action="process.php" method="Post">
	 <table align="left" width="80%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
   	    <tr>
      	       <td colspan="4">&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td width="5%">&nbsp;</td>
               <td width="35%" align="right" valign="top">
	          <font size="2" face="Verdana">Reservation Date:</font></td>
               <td width="55%" align="left">
            	  <select name="day">
	    	  <?
	       	     $today = date("m/d/y");
  	             for($i = 1; $i <= 24; $i++){
    		  	printf("<option value=\"%s\">%s</option>\n", $today, $today);
    		  	$today = date("m/d/y", mktime (0,0,0,date("m")  ,date("d") + $i , date("y")));
  	       	     }	
	    	  ?>
	    	  </select>
      	       </td>
      	       <td width="5%">&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td>&nbsp;</td>
               <td align="right" valign="top">
	          <font size="2" face="Verdana">Start Time:</font></td>
               <td align="left">
		  <select name="start_time">
		     <option value="all">All Day</option>
			<?
  			   for($i = 1; $i < 12; $i++){
   			      printf("<option value=\"%s\">%s</option>\n", $i, "$i AM");
  			   }

			   for($i = 12; $i < 24; $i++){
			      printf("<option value=\"%s\">%s</option>\n", $i - 12, "$i PM");
  			   }
			?>
		  </select>
      	       </td>
      	       <td>&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td>&nbsp;</td>
      	       <td colspan="2" align="center">
	 	  <table border="0">
	    	     <tr>
	       		<td width="55%" align="right" valign="middle"> 
		  	   <input type="Submit" value=" Reserve a Car ">
	       		</td>
 	       		<td width="10%">&nbsp;</td>
	       		<td width="35%" align="left" valign="middle">
		  	   <input type="Reset" value=" Reset  ">
	       		</td>
    	    	     </tr>
         	  </table>		
      	       </td>
               <td>&nbsp;</td>
   	    </tr>
   	    <tr>
      	       <td colspan="4">&nbsp;</td>
   	    </tr>
	 </table>
         </form>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
   </tr>
</table>
