<?
  // All POW files need this session file included
  include("pow_session.php");
  $user =  $userdata['username'];

  // Define some vars for the skeleton page
  $title = "Temperature Change Request History";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("LABOR@LCS", "LABOR");
  if($conn < 1){
	printf("Error logging on to the Oracle Server: ");
       	printf(ora_errorcode($conn));
       	printf("</body></html>");
       	exit;
  }
  $cursor = ora_open($conn);

  $sql = "select * from set_temp_user where user_name = '$user' and status = 'A'";
  ora_parse($cursor, $sql);
  ora_exec($cursor);
  
  if (ora_fetch($cursor)){
	$isSetTemp = true;
  }else{
	$isSetTemp = false;
  }
?>
<style>td{font:12px}</style>
	 <table border="0" width="100%" cellpadding="4" cellspacing="0">
   	    <tr>
      	       <td width="1%">&nbsp;</td>
      	       <td>
    	 	  <font size="5" face="Verdana" color="#0066CC">Temperature Change Requests</font>
	          <hr>
              </td>
            </tr>
            <tr>
 	       <td>&nbsp;</td>
	       <td>
		  <p align="center">
<!--
		     <font size="2" face="Verdana">List of requests made by the use of the Web Access.<br />
		     Invalid requests have 'Mail Sent' marked with a N to show that the request was not 
		     processed due to a user error.<br />

		     Please send any concerns or questions to <a href="mailto:hdadmin@port.state.de.us">
		     hdadmin@port.state.de.us</a><br />

		     Please note that the most recently submitted entry is at the top!<br /></font>
-->
		     <a href="index.php">Click here to go back</a>
		  </p>

		  <p align="left">
		  <?
  		     // 8/12/02 - Created by Seth Morecraft 
  		     // Description: Grabs all entries in LCS.TEMP_REQ and displays them in order
  		     // of the most recent entry first.
  		     // Only allows users John Reece and Bob Barker to log in.

  		     $group_id = 1;
  		     if($group_id ==1)
		     {
                     	// Gotta have a correct order.
    		     	$sql = "select req_id, mail_sent, to_char(req_date, 'mm/dd/yy HH:MI AM'), user_name,new_set_point, 
			        to_char(effective, 'mm/dd/yy HH:MI AM'), whse, box, product, 
				to_char(expired, 'mm/dd/yy HH:MI AM'), comments, actual_set_point from 
			        TEMPERATURE_REQ order by req_id desc";
    		     	$statement = ora_parse($cursor, $sql);
    		     	ora_exec($cursor);
    		     	$num_columns = ora_NumCols($cursor);
  
    		     	// Build a table.
    		    	echo "<table border=1 cellpadding=0 cellspacing=0>";
    		    	echo "<TR  bgcolor=#4D76A5>
                              <td><b>Req#</b></td> 
        	     	      <td><b>Mail Sent</b></td>
        		      <td><b>Date</b></td>
        		      <td><b>User</b></td>
        		      <td><b>Set Point</b></td>
        		      <td><b>Effective Date</b></td>
        		      <td><b>Wing</b></td>
        		      <td><b>Box</b></td>
        		      <td><b>Product</b></td>
        		      <td><b>Expiration Date</b></td>
        		      <td><b>Comments</b></td>
                              <td><b>Actual Set Temperature</b></td>
        		      </TR>";
  
      		     	while (ora_fetch($cursor)){
          		   echo "<TR>";
          		   for ($i = 0; $i < $num_columns; $i++) {
                  	      $column_value = ora_getcolumn($cursor,$i);
                  	      if($column_value == "" && $i < $num_columns - 1){
                              	$column_value = "&nbsp;";
                  	      }
			      if ($isSetTemp == true  && $i == $num_columns - 1){
				if ($column_value == "")
					$column_value = "<font color='red'>Set Temperature</font>";
				if($column_value != "<font color='red'>Set Temperature</font>")
				$column_value = "<a href='set_temp.php?rId=".ora_getcolumn($cursor,0)."&ast=$column_value'>$column_value</a>";
				else
				$column_value = "<a href='set_temp.php?rId=".ora_getcolumn($cursor,0)."&ast='>$column_value</a>"; 	 	
			      }else if ($i == $num_columns - 1 && $column_value == ""){
				$column_value = "&nbsp;";
			      }			
                  	      echo "<TD><font size=\"2\" face=\"Verdana\"><center>$column_value</center></font></TD>";
          	           }
      		           echo "</TR>";
    		        }
    		        echo "</TABLE>";
    		        echo "<br /><font size=\"2\" face=\"Verdana\"><center><a href=\"index.php\">
			      Please click here to go back</a></center></font>";
  
		        ora_close($cursor);
  		     }else{
    		        printf("Invalid user! Please contact <a href=\"hdadmin@port.state.de.us\">
		 	       hdadmin@port.state.de.us</a> if you feel that you are getting this 
			       message in error.");
   		        exit;
               	     }
            ?>
  	 </p>
         <? include("pow_footer.php"); ?>
