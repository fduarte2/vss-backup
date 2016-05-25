<?
//	Edit, Adam Walter, Dec 2007.
//	Per request of Gene, I have removed the top area of the meeting agenda.
//	As it was a mix of HMTL and PHP, It was much easier for me to cut it out then
//	try and comment each block.
//	I have pasted the cut code in a file called "removed_from_meeting.php",
//	and saved that file int he same folder as this one, if anyone wants it back.
//	also note that these changes, and ones to meeting_save.php, apparently cause
//	Postgres tables meeting_detail, mk_visit, and mk_quotes to become obsoleted,
//	But I won't remove them now.

/*
   if (isset($HTTP_POST_VARS[save]) || isset($HTTP_POST_VARS[add]) ){
        include("meeting_save.php");
   }

   if (isset($HTTP_POST_VARS[to_print])){
       header("Location: meeting_print.php?mId=$HTTP_POST_VARS[mId]");
   }
*/
  $exec_agenda_row = $HTTP_POST_VARS[exec_agenda_row];
  $admin_agenda_row = $HTTP_POST_VARS[admin_agenda_row];
  $op_agenda_row = $HTTP_POST_VARS[op_agenda_row];
  $mk_agenda_row = $HTTP_POST_VARS[mk_agenda_row];
  $ts_agenda_row = $HTTP_POST_VARS[ts_agenda_row];
  $hr_agenda_row = $HTTP_POST_VARS[hr_agenda_row];
  $fina_agenda_row = $HTTP_POST_VARS[fina_agenda_row];
  $eng_agenda_row = $HTTP_POST_VARS[eng_agenda_row];

  if ($exec_agenda_row < 5)	$exec_agenda_row = 5;
  if ($admin_agenda_row < 5)    $admin_agenda_row = 5;
  if ($op_agenda_row < 5)     	$op_agenda_row = 5;
  if ($mk_agenda_row < 5)     	$mk_agenda_row = 5;
  if ($ts_agenda_row < 5)     	$ts_agenda_row = 5;
  if ($hr_agenda_row < 5)     	$hr_agenda_row = 5;
  if ($fina_agenda_row < 5)     $fina_agenda_row = 5;
  if ($eng_agenda_row < 5)     	$eng_agenda_row = 5;



  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Meeting";
  $area_type = "DIRE";
/*
  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Director system");
    include("pow_footer.php");
    exit;
  }
*/
  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);

  $user_occ = $userdata['user_occ'];
  if (stristr($user_occ,"Director") == false && array_search('ROOT', $user_types) === false ){
    printf("Access Denied");
    include("pow_footer.php");
    exit;
  }

  include("connect.php");
  $data = "meeting_date";

   // open a connection to the database server
   $connection = pg_connect ("host=$host dbname=$db user=$dbuser");
   $temp = 0;

   if (!$connection){
      printf("Could not open connection to database server.   Please go back to <a href=\"director_login.php\"> Director Login Page</a> and try again later!<br />");
      exit;
   }

   $username = $userdata['username'];
   $user_email = $userdata['user_email'];

   // This is not needed- $mId will already be set
   //$mId = $HTTP_GET_VARS[mId];

   $query ="select sub_type from ccd_user where email = '".$user_email."'";
   $result = pg_query($connection, $query) or
             die("Error in query: $query. " .  pg_last_error($connection));
   $rows = pg_num_rows($result);
   if ($rows >0){
	$row = pg_fetch_row($result, 0);
       	$user_type = $row[0];
   }

//   echo "User type: $user_type";
   $exec_disabled="";
   $admin_disabled="";
   $op_disabled="";
   $mk_disabled="";
   $ts_disabled="";
   $hr_disabled="";
   $fina_disabled="";
   $eng_disabled="";

   if ($user_type != "EXEC" && $user_type !="ADMIN")
	$exec_disabled = "disabled";

  
   if ($user_type !="EXEC" && $user_type !="ADMIN")
	$admin_disabled = "disabled";

   if ($user_type !="EXEC" && $user_type !="OPS")
	$op_disabled = "disabled";

   if ($user_type !="EXEC" && $user_type !="MRKT")
        $mk_disabled = "disabled";

   if ($user_type !="EXEC" && $user_type !="TECH")
	$ts_disabled = "disabled";

   if ($user_type !="EXEC" && $user_type !="HR")
	$hr_disabled = "disabled";

   if ($user_type !="EXEC" && $user_type !="FINA")
	$fina_disabled = "disabled";

   if ($user_type !="EXEC" && $user_type !="ENG")
	$eng_disabled = "disabled";


   if (isset($HTTP_POST_VARS[to_print]) || isset($HTTP_POST_VARS[save]) || isset($HTTP_POST_VARS[add]) ){
	include("meeting_save.php");
   }

   if (isset($HTTP_POST_VARS[to_print])){
       header("Location: meeting_print.php?mId=$HTTP_POST_VARS[mId]");
   }

   $today = getdate();
   $wday = $today['wday'];

   $week_of_Tue = mktime(0,0,0,date("m"),date("d")  - ($wday-2), date("Y"));

   $meeting_date = date("M j, Y", $week_of_Tue);
   
   $dMeeting_date = date("m/d/Y", $week_of_Tue);
   
   $data = "meeting_date";
   // generate and execute a query
   $query = "select id from $data where m_date='".$meeting_date."'";

   $result = pg_query($connection, $query) or
             die("Error in query: $query. " .  pg_last_error($connection));

   // get the number of rows in the resultset
   $row_num = pg_num_rows($result);

   // if records present
   if ($row_num <= 0){
      $query = "insert into meeting_date (m_date) values('".$meeting_date."')";
      $result = pg_query($connection, $query) or
             die("Error in query: $query. " .  pg_last_error($connection));
   }
   //add next Tuesday to list
   if ($wday >=3){
        $next_Tue = mktime(0,0,0,date("m"),date("d")+ 7  - ($wday-2), date("Y"));
        $next_Tue_date = date("M j, Y", $next_Tue);

	$query = "select id from $data where m_date='".$next_Tue_date."'";
	$result = pg_query($connection, $query) or
             die("Error in query: $query. " .  pg_last_error($connection));

   	// get the number of rows in the resultset
   	$row_num = pg_num_rows($result);
   	// if records present
   	if ($row_num <= 0){
      		$query = "insert into meeting_date (m_date) values('".$next_Tue_date."')";
      		$result = pg_query($connection, $query) or
             		die("Error in query: $query. " .  pg_last_error($connection));
	}
   }

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Director system");
    include("pow_footer.php");
    exit;
  }

   
?>

<script language="javascript">
function mId_change()
{
	mId = document.meeting.mId.options[document.meeting.mId.selectedIndex].value;
	document.location = 'meeting.php?mId=' + mId;
}
function addMore(type)
{	
	document.meeting.elements[type].value = parseInt(document.meeting.elements[type].value)+1;
	document.meeting.action="meeting.php#"+type;
}
</script>

	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
	   <form name = "meeting"  method="Post" action="">
	    <tr>
      	       <td width="1%">&nbsp;</td>
	       <td> 
         	  <p align="left">
	             <font size="5" face="Verdana" color="#0066CC">Agenda for Meeting Date:
              		<select name="mId" onchange="javascript:mId_change()">
                           <?
                              // Gets a list of meeting date

                              // Solid defines for the DB
                              include("defines.php");

                              // generate and execute a query
                              $query = "SELECT m_date, id  FROM $data order by m_date";
                              $result = pg_query($connection, $query) or
                              die("Error in query: $query. " .  pg_last_error($connection));

                              // get the number of rows in the resultset
                              $rows = pg_num_rows($result);

                              // if records present
                              if ($rows > 0){
                                 // iterate through resultset
                                 for ($i=0; $i<$rows; $i++){
                                    $row = pg_fetch_row($result, $i);
                                    $m_date = $row[0];
                                    $id = $row[1];
				    

                                    $arrDate = explode('-', $m_date);

                                    $mDate = mktime(0,0,0,$arrDate[1], $arrDate[2], $arrDate[0]);
                                    $display_date = date("m/d/Y", $mDate);

                                    if ($mId > 0){
                                        if ($id == $mId)
                                                $selected = "selected";
                                        else
                                                $selected = "";
                                    } else if ($display_date == $dMeeting_date){
                                        $selected = "selected";
                                        $mId = $id;
                                    }else{
                                        $selected = "";
                                    }
                                    printf("<option value=\"%s\" %s>%s</option>", $id, $selected, $display_date);
                                 }
                              }
                              else{
                                 printf("<font size=\"4\" face=\"Verdana\">No data is found in %s.</font>\n",
                                        $data);
                              }
                           ?>
                           </select> 

                     </font>
         	   <hr> 
		 </p>
	       </td>
	    </tr>

	</table>

	<table border="0" width="65%" cellpadding="0" cellspacing="1">

	    <tr>
	       <td width="1%">&nbsp;</td>
               <td align="center"><b><font size="3" face="Verdana"><U>AGENDA ITEMS</u></font></b></td>
               <td width="1%">&nbsp;</td>
            </tr>
 
           <tr>
               <td>&nbsp;</td>
               <td align="center">
	        <table>
                <tr>
		 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="exec_agenda_row">EXEC Director:</a></font></b></td>
		<td></td>
		 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="admin_agenda_row">ADMINISTRATION:</a></font></b></td>

		</tr>
		<tr>
		<td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='EXEC'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $exec_agenda[$i] = $row[0];
                                }

				if ($rows < $exec_agenda_row)	$rows = $exec_agenda_row;
                                for($i= 0; $i <$rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("exec_agenda".$i) ?>" size="46" value="<?php echo $exec_agenda[$i]?>" <?php echo $exec_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
				<tr>
				    <input type="hidden" name="exec_agenda_row" value="<?echo $rows?>">
				    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                        	    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp; 
				    <input type="submit" name="add" value="Add Line" onClick="javascript:addMore('exec_agenda_row')">
				</tr>
                           </table>

		</td>
                <td>
                </td>
                <td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='ADMIN'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $admin_agenda[$i] = $row[0];
                                }

				if ($rows < $admin_agenda_row)   $rows = $admin_agenda_row;

                                for($i= 0; $i <$rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("admin_agenda".$i) ?>" size="46" value="<?php echo $admin_agenda[$i]?>" <?php echo $admin_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="admin_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('admin_agenda_row')">
                                </tr>

                           </table>

                </td>

		</tr>
		   
                <tr>
                 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="op_agenda_row">OPERATIONS:</a></font></b></td>
                <td></td>
                 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="mk_agend_row">MARKETING:</a></font></b></td>

                </tr>
                <tr valign="top">
		           <td>
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='OPS'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $op_agenda[$i] = $row[0];
                                }

                                if ($rows < $op_agenda_row)   $rows = $op_agenda_row;

                                for($i= 0; $i <$rows; $i++){
                                ?>
                                <tr>
                                   <input type="text" name="<?php echo ("op_agenda".$i) ?>" size="46" value="<?php echo $op_agenda[$i]?>" <?php echo $op_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="op_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('op_agenda_row')">
                                </tr>

                           </table>
                        </td>
                        <td></td>
                           <td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='MRKT'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $mk_agenda[$i] = $row[0];
                                }

                                if ($rows < $mk_agenda_row)   $rows = $mk_agenda_row;

                                for($i= 0; $i <$rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("mk_agenda".$i) ?>" size="46" value="<?php echo $mk_agenda[$i]?>" <?php echo $mk_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="mk_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('mk_agenda_row')">
                                </tr>

                          </table>
                        </td>
  		    </tr>

                <tr>
                 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="ts_agenda_row">TECH SOLUTIONS:</a></font></b></td>
                <td></td>
                 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="hr_agenda_row">HR:</a></font></b></td>

                </tr>
                <tr>
                           <td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='TECH'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $ts_agenda[$i] = $row[0];
                                }

                                if ($rows < $ts_agenda_row)   $rows = $ts_agenda_row;

                                for($i= 0; $i <$rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("ts_agenda".$i) ?>" size="46" value="<?php echo $ts_agenda[$i]?>" <?php echo $ts_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="ts_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('ts_agenda_row')">
                                </tr>

                          </table>
                        </td>
			<td></td>
                          <td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='HR'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $hr_agenda[$i] = $row[0];
                                }

                                if ($rows < $hr_agenda_row)   $rows = $hr_agenda_row;

                                for($i= 0; $i < $rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("hr_agenda".$i) ?>" size="46" value="<?php echo $hr_agenda[$i]?>" <?php echo $hr_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="hr_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('hr_agenda_row')">
                                </tr>

                          </table>
                        </td>
                 </tr>

                <tr>
                 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="fina_agenda_row">FINANCE:</a></font></b></td>
                <td></td>
                 <td width="100%" align="left" valign="top"><b><font size="2" face="Verdana"><a name="eng+agenda_row">ENGINEERING:</a></font></b></td>

                </tr>
                <tr>
                          <td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='FINA'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $fina_agenda[$i] = $row[0];
                                }

                                if ($rows < $fina_agenda_row)   $rows = $fina_agenda_row;

                                for($i= 0; $i <$rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("fina_agenda".$i) ?>" size="46" value="<?php echo $fina_agenda[$i]?>" <?php echo $fina_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="fina_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('fina_agenda_row')">
                                </tr>

                           </table>
                        </td>
			<td></tr>
                          <td valign="top">
                           <table>
                                <?php
                                $data = "meeting_agenda";
                                $query = "SELECT items  FROM $data where meeting_id=$mId and dept='ENG'";
                                $result = pg_query($connection, $query) or
                                  die("Error in query: $query. " .  pg_last_error($connection));

                                // get the number of rows in the resultset
                                $rows = pg_num_rows($result);

                                // if records present
                                for($i=0; $i<$rows; $i++){
                                        $row = pg_fetch_row($result, $i);
                                        $eng_agenda[$i] = $row[0];
                                }

                                if ($rows < $eng_agenda_row)   $rows = $eng_agenda_row;

                                for($i= 0; $i < $rows; $i++){
                                ?>

                                <tr>
                                   <input type="text" name="<?php echo ("eng_agenda".$i) ?>" size="46" value="<?php echo $eng_agenda[$i]?>" <?php echo $eng_disabled ?>>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <input type="hidden" name="eng_agenda_row" value="<?echo $rows?>">
                                    <input type="submit" name="save" value="   Save  ">&nbsp;&nbsp;
                                    <input type="submit" name="to_print" value = "   Print   ">&nbsp;&nbsp;
                                    <input type="submit" name="add" value="Add More" onClick="javascript:addMore('eng_agenda_row')">
                                </tr>

                           </table>
                        </td>


		</tr>
                </table>
               </td>
               <td> </td>
           </tr>
<!--
	     <tr> 
	    	<td></td>
		<td align = "center"><input type="submit" name="save" value="  Save  ">&nbsp;&nbsp; &nbsp; &nbsp; <input type="submit" name="to_print" value = "   Print   "> </td>
		<td></td>
		
	     </tr>
-->
         </table>

         </p>
<? include("pow_footer.php");
   pg_close($connection); ?>
