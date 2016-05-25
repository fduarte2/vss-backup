<?
  // All POW files need this session file included
  include("pow_session.php");
  include("connect.php");
/*
  $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if (!$conn){
   die("Could not open connection to database server");
  }
*/
	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		echo "Error logging on to the BNI Oracle Server: ";
		echo ora_errorcode($conn);
		exit;
	}
	$select_cursor = ora_open($conn);
	$short_term_cursor = ora_open($conn);

  $title = "Guard Gate Visitors";
    $area_type = "GATE";
    include("pow_header.php");
  $user = $userdata['username'];
/*
  if($user == "Anonymous"){
    include("pow_header.php");
    printf("You need to log in to request a visitor!");
    include("pow_footer.php");
    exit;
  }
*/
  // Set up the number of visitors
  $num_visitors = $HTTP_POST_VARS["num_visitors"];
  if($num_visitors == ""){
    $num_visitors = 1;
  }

//	echo $user."<br>";
  if($user == "gate"){
    // Display all users if the guards are logged in
//    $area_type = "GATE";

    $today = date('Y-m-d');
    $start_today = date('Y-m-d');
    $start_today .= " 01:00:00";
    $end_today = date('Y-m-d');
    $end_today .= " 23:59:59";

//    $stmt = "select * from security_gate where (reservation_date between '$start_today' and '$end_today') or (reservation_date <= '$start_today' and end_date >= '$today') order by reservation_date desc";]
//    $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
//    $rows = pg_num_rows($result);
//    if($rows == 0){
	$sql = "SELECT REQUEST_NUMBER, VISITOR_NAME, COMMENTS,
				TO_CHAR(REQUEST_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_REQ,
				TO_CHAR(RESERVATION_DATE, 'MM/DD/YYYY HH24:MI:SS') THE_RESERVE,
				TO_CHAR(END_DATE, 'MM/DD/YYYY') THE_END
			FROM SECURITY_GATE
			WHERE
				(RESERVATION_DATE >= TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY') AND RESERVATION_DATE <= TO_DATE('".date('m/d/Y')." 23:59:59', 'MM/DD/YYYY HH24:MI:SS') )
				OR
				(RESERVATION_DATE <= TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY') AND END_DATE >= TO_DATE('".date('m/d/Y')."', 'MM/DD/YYYY'))
			ORDER BY RESERVATION_DATE DESC";
	ora_parse($select_cursor, $sql);
	ora_exec($select_cursor);
	if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      printf("<b>There are no pending requests</b>");
    } else{
?>
<script language="Javascript1.2">
function printpage() {
  window.print();  
}
</script>

<form action="index.php" method="Post"><input type=button value="Print" onClick="printpage()">&nbsp;&nbsp;<input type="submit" value="Update"></form><br />

<b>Pending Requests for <?= date('D, F jS') ?></b>
<table width=\"100%\" border="1" cellpadding="4" cellspacing="0">
  <th>Request Num</th><th>Requestor</th><th>Visitor</th><th>ETA<th>Through</th><th>Comments</th>
<?
//    for($i = 0; $i < $rows; $i++){
		do{
//      $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
//      printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['request_number'], $row['requestor'], $row['visitor_name'], date('H:i:s',strtotime($row['reservation_date'])), date('m/d/y', strtotime($row['end_date'])), $row['comments']);
      printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $select_row['REQUEST_NUMBER'], $select_row['VISITOR_NAME'], $select_row['THE_REQ'], $select_row['THE_RESERVE'], $select_row['THE_END'], $select_row['COMMENTS']);
		} while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
     printf("</table>");
	}
  }
  else{
  // Display the normal page

    // Define some vars for the skeleton page
 //   $area_type = "ROOT";

    // Provides header / leftnav
 //   include("pow_header.php");
?>

<script type="text/javascript">
  function validate_info(){
    x = document.gate
    visitor = x.visitor.value
    if(visitor == ""){
      alert("You must enter a Visitor Name!");
      return false;
    }
    return true;
  }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p>
	 <font size="5" face="Verdana" color="#0066CC">Port Visitor Request</font>
	 <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <font size="2" face="Verdana" color="#000080"><b>Request a Port visitor:</b></font>
	 <br />
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <table align="left" width="80%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
   	    <tr>
      	       <td colspan="4">&nbsp;</td>
   	    </tr>
	 <form action="index.php" method="Post" name="visitors">
            <tr>
      	       <td width="5%">&nbsp;</td>
               <td width="35%" align="right">
	          <font size="2" face="Verdana">Number of Visitors:</font></td>
               <td width="55%" align="left">
                  <select name="num_visitors" onchange="document.visitors.submit(this.form)">
                  <?
                    for($x = 1; $x <= 15; $x++){
                      if($num_visitors == $x){
                        printf("<option value=\"$x\" selected>$x</option>\n");
                      }
                      else{
                        printf("<option value=\"$x\">$x</option>\n");
                      } 
                    }
                  ?>
      	       </td>
   	    </tr>
         </form>
	 <form action="process.php" method="Post" name="gate" onsubmit="return validate_info()">
	 <input type="hidden" name="submitter" value="<? echo $user; ?>">
         <input type="hidden" value="<?= $num_visitors ?>" name="num_visitors">
   	    <tr>
      	       <td width="5%">&nbsp;</td>
               <td width="35%" align="right">
	          <font size="2" face="Verdana">Reservation Date:</font></td>
               <td width="55%" align="left">
            	  <select name="day">
	    	  <?
	       	     $today = date("m/d/Y");
  	             for($i = 1; $i <= 24; $i++){
    		  	printf("<option value=\"%s\">%s</option>\n", $today, $today);
    		  	$today = date("m/d/Y", mktime (0,0,0,date("m")  ,date("d") + $i , date("Y")));
  	       	     }	
	    	  ?>
	    	  </select>
	          &nbsp;&nbsp;<font size="2" face="Verdana">Start Time:</font>
		  <select name="start_time">
			<?
                        printf("<option value=\"%s\">%s</option>\n", "8", " 8 AM");
  			   for($i = 1; $i < 12; $i++){
   			      printf("<option value=\"%s\">%s</option>\n", $i, "$i AM");
  			   }

			   for($i = 12; $i < 24; $i++){
                              if($i != 12){
                                $x = $i - 12;
			        printf("<option value=\"%s\">%s</option>\n", $i, "$x PM");
                              }
                              else{
                                $x = 12;
			        printf("<option value=\"%s\" selected=\"true\">%s</option>\n", $i, "$x PM");
                              }
  			   }
			   printf("<option value=\"24\">12 AM</option>\n");
			?>
		  </select>
      	       </td>
   	    </tr>
   	    <tr>
      	       <td width="5%">&nbsp;</td>
               <td width="35%" align="right">
	          <font size="2" face="Verdana">End Date:</font></td>
               <td width="55%" align="left">
            	  <select name="end_day">
                    <option value="">One Day</option>
	    	  <?
	       	     $today = date("m/d/Y");
  	             for($i = 1; $i <= 24; $i++){
    		  	printf("<option value=\"%s\">%s</option>\n", $today, $today);
    		  	$today = date("m/d/Y", mktime (0,0,0,date("m")  ,date("d") + $i , date("Y")));
  	       	     }	
	    	  ?>
	    	  </select>
      	       </td>
               <td>&nbsp;</td>
   	    </tr>
<?
   for($x = 1; $x <= $num_visitors; $x++){
?>
            <tr>
               <td>&nbsp;</td>
               <td width="35%" align="right">
	          <font size="2" face="Verdana">Visitor <?= $x ?> Name:</font></td>
               <td width="55%" align="left">
               <input type="textbox" name="visitor<?= $x ?>" size="20" maxlength="30" value="">
               </td>
               <td>&nbsp;</td>
            </tr>
<?
  }
?>
            <tr>
               <td>&nbsp;</td>
               <td width="35%" align="right" valign="top">
	          <font size="2" face="Verdana">Comments:</font></td>
               <td width="55%" align="left">
               <!--<input type="textbox" name="notes" size="40" maxlength="100" value="">-->
               <textarea name="notes" rows="5" cols="40" value = "" ></textarea>       
	       </td>
               <td>&nbsp;</td>
            </tr>
   	    <tr>
      	       <td>&nbsp;</td>
      	       <td colspan="2" align="center">
	 	  <table border="0">
	    	     <tr>
	       		<td width="55%" align="right" valign="middle"> 
		  	   <input type="Submit" value=" Make Request ">
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

<?
//  $stmt = "select * from security_gate where requestor = '$user' order by reservation_date desc";
//  $result = pg_query($conn, $stmt) or die("Error in query: $stmt. " . pg_last_error($conn));
//  $rows = pg_num_rows($result);
//  if($rows == 0){
	$sql = "SELECT REQUEST_NUMBER, VISITOR_NAME, COMMENTS,
				TO_CHAR(REQUEST_TIME, 'MM/DD/YYYY HH24:MI:SS') THE_REQ,
				TO_CHAR(RESERVATION_DATE, 'MM/DD/YYYY HH24:MI:SS') THE_RESERVE,
				TO_CHAR(END_DATE, 'MM/DD/YYYY') THE_END
			FROM SECURITY_GATE WHERE REQUESTOR = '".$user."' ORDER BY RESERVATION_DATE DESC";
	ora_parse($select_cursor, $sql);
	ora_exec($select_cursor);
	if(!ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	    printf("<b>You have no pending requests</b>");
	} else {
?>
<b>Your Requests</b>
<table width=\"100%\" border="1" cellpadding="4" cellspacing="0">
  <th>Request ID<th>Visitor Name</th><th>Request Time</th><th>Arrival Time<th>End Date</th><th>Comments</th>
<?
//    for($i = 0; $i < $rows; $i++){
//      $row = pg_fetch_array($result, $i, PGSQL_ASSOC);
		do {
//      printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['request_number'], $row['visitor_name'], date('m/d/y H:i:s', strtotime($row['request_time'])), date('m/d/y H:i:s',strtotime($row['reservation_date'])), date('m/d/y', strtotime($row['end_date'])), $row['comments']);
      printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $select_row['REQUEST_NUMBER'], $select_row['VISITOR_NAME'], $select_row['THE_REQ'], $select_row['THE_RESERVE'], $select_row['THE_END'], $select_row['COMMENTS']);
		} while(ora_fetch_into($select_cursor, $select_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
     printf("</table>");
	}
}
?>

<? include("pow_footer.php"); ?>
