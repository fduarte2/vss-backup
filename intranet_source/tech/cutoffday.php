<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "TS Applications Access";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }
  
  if ($submit == "Update"){ 
  
	  if ($optTDay == "Monday") $Day = 2 ;
      if ($optTDay == "Tuesday") $Day = 3 ;
      if ($optTDay == "Wednesday") $Day = 4 ;
      if ($optTDay == "Thursday") $Day = 5 ;
      if ($optTDay == "Friday") $Day = 6 ;
      if ($optTDay == "Saturday") $Day = 7 ;
      if ($optTDay == "Sunday") $Day = 1 ;
	$msg = $optTDay . ' at ' . $THOUR . ':' . $TMINUTE . '<br />';
	
	if ($Day<1 or $Day>7) print 'Invalid day! (1-7)'; else
		 if ($THOUR<0 or $THOUR>23) print 'Invalid hour! (0-23)'; else
			 if ($TMINUTE<0 or $TMINUTE>59) print 'Invalid hour! (0-59)'; else
				{

				// Update Cutoff day Table
					$ora_conn = ora_logon("LABOR@LCS", "LABOR");
						if (!$ora_conn) {
						  printf("Error logging on to Oracle Server: ");
						  printf(ora_errorcode($ora_conn));
						  exit;
						}
						// create cursor
					$cursor = ora_open($ora_conn);
					if (!$cursor) {
					  printf("Error opening a cursor on Oracle Server: ");
					  printf(ora_errorcode($cursor));
					  exit;
					}	
					$stmt = "delete from CUTOFF_DAY";

					$ora_success = ora_parse($cursor, $stmt);
					$ora_success = ora_exec($cursor);

					$stmt = "INSERT INTO CUTOFF_DAY VALUES('". $Day . "','" . $THOUR . "','" . $TMINUTE . "')";

					$ora_success = ora_parse($cursor, $stmt);
					$ora_success = ora_exec($cursor);
					
					
					ora_close($cursor);		
					ora_logoff($ora_conn);

						
				//
				}
			
  } 
  
  //
  // connect to RF database
	$ora_conn = ora_logon("LABOR@LCS", "LABOR");
	if (!$ora_conn) {
	  printf("Error logging on to Oracle Server: ");
	  printf(ora_errorcode($ora_conn));
	  exit;
	}

	// create cursor
	$cursor1 = ora_open($ora_conn);
	if (!$cursor1) {
	  printf("Error opening a cursor on Oracle Server: ");
	  printf(ora_errorcode($cursor1));
	  exit;
	}		

  // get infomation from LCS.CUTOFF_DAY
  $stmt = "select DAY,HOUR,MINUTE FROM CUTOFF_DAY";

	$ora_success = ora_parse($cursor1, $stmt);
	$ora_success = ora_exec($cursor1);

	ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$rows1 = ora_numrows($cursor1);

	if (!$ora_success) {
	// close Oracle connection
		printf("Oracle Error Occurred While Retrieving Data from LCS.CUTOFF_DAY. Please Try Again Later.");
		printf(ora_errorcode($ora_conn));	
		ora_close($cursor1);
		ora_logoff($ora_conn);
		printf("");
		exit;
	}
	$DAY = $row1['DAY'];
	$HOUR = $row1['HOUR'];
	$MINUTE = $row1['MINUTE'];

	if ($DAY == 1) $optDay = "Sunday" ;
	if ($DAY == 2) $optDay = "Monday" ;
	if ($DAY == 3) $optDay = "Tuesday" ;
	if ($DAY == 4) $optDay = "Wednesday" ;
	if ($DAY == 5) $optDay = "Thursday" ;
	if ($DAY == 6) $optDay = "Friday" ;
	if ($DAY == 7) $optDay = "Saturday" ;
	

  //
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">TS Applications
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
In order for supervisors to enter time for hires for the previous week, all hours must be entered before the cutoff date and time. Use this form to make changes to the cutoff date and time. </p> <p> </p>
	    <font size="2" face="Verdana"><b>Current Cuttoff day is:</b></font>
         </p>

	<?	print $optDay;  print ' at '; print $HOUR; 	print ':'; 	print $MINUTE;?><br />

	 
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="images/FSI-comp.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<form method="post" action="cutoffday.php"> 

Change to:  Day:<select name="optTDay">
    <option<? if ($DAY ==2) print ' selected'?>>Monday</option>
    <option<? if ($DAY ==3) print ' selected'?>>Tuesday</option>
    <option<? if ($DAY ==4) print ' selected'?>>Wednesday</option>
    <option<? if ($DAY ==5) print ' selected'?>>Thursday</option>
    <option<? if ($DAY ==6) print ' selected'?>>Friday</option>
    <option<? if ($DAY ==7) print ' selected'?>>Saturday</option>
<!--    <option<? if ($DAY ==1) print ' selected'?>>Sunday</option> !-->
    </select><br> 
Hour:<input type="text" name="THOUR" value = <? print $HOUR?>></input> 
Minute:<input type="text" name="TMINUTE" value = <? print $MINUTE?>></input><br> 
<br> 
<input type="submit" name="submit" value="Update"></input> 

</form> 


<?
ora_close($cursor1);
ora_logoff($ora_conn);
?>

<? include("pow_footer.php"); ?>
