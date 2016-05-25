<?
// ADAM WALTER, July 2006.  Creates a tab-delimited file with a comparison of the hours worked by employees
// for a given date VS. the hours ticketed to external customers for said date.

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
/*
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }
*/

  include("connect.php");
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
   if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
   }


   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	include("pow_footer.php");
       	exit;
   }

   $ticket_header_cursor = ora_open($conn);
   $ticket_detail_cursor = ora_open($conn);
   $ticket_for_ticket_file_cursor = ora_open($conn);
   $worked_cursor = ora_open($conn);
   $worked_supervisor_cursor = ora_open($conn);

   $report_date = $HTTP_POST_VARS['report_date'];
   $time_array = split("/", $report_date);
   $concat_report_date = date('mdY', mktime(0,0,0,$time_array[0], $time_array[1], $time_array[2]));

//   echo $report_date."\n";
//   echo $concat_report_date."\n";

   if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $report_date)){
	   printf("Date was not entered in MM/DD/YYYY format.  Please use the back button to return to the previous page.");
       include("pow_footer.php");
	   exit;
   }

// $hour_totals is the biggie in terms of this code.  It holds all running totals of hours in a FOUR (yes, 4)
// dimentional associative array.  Format is $hour_totals['Supervisornumber']['ServiceGroup']['EarningType']['HourType'] = X, where
// Supervisornumber = the letter E followed by a 6-digit number (the employee id of the supers in the Labor_Ticket_header table)
// ServiceGroup = a 3 digit number, indicating either the 3-digit number in Labor_ticket_header, or the first 3 digits of the 4 digit service codes
// EarningType = one of the following:  standard, meal, or overtime
// HourType = one of the following:  LT (referencing Labor_Ticket table) or SHD (referencing Simplified_hourly_detail table)
   $hour_totals = array();


// while not the most efficient way of handling this I'm sure, since I have to step through the entire hour_totals array later for printout,
// I needed to figure some way to determine every associate array combination possible.  As such, we have these 3 arrays, which will get used
// in nested while loops later.
   $supervisors_involved = array();
   $service_codes_involved = array();
   $earning_types_involved = array('meal', 'overtime', 'standard');

   $filename1 = "LaborTicket".$concat_report_date.".xls";
   $handle = fopen($filename1, "w");

   if(!$handle){
	   echo "File ".$filename1." could not be opened, please contact TS.\n";
	   include("pow_footer.php");
	   exit;
   }
   fwrite($handle, $report_date."\n\n");
   fwrite($handle, "Labor Ticket Detail\n\n");
   fwrite($handle, "\t\t\tTicket Number\t\tVessel\t\tCommodity\t\tService Group\t\tBreakdown\n");
   fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\tHours\tType\n");


   $sql = "SELECT LTH.TICKET_NUM TICKET_NUM, LTH.COMMODITY_CODE COMMODITY_CODE, LTH.VESSEL_ID VESSEL_ID, LTH.SERVICE_GROUP SERVICE_GROUP, LCS.USER_NAME USER_NAME FROM LABOR_TICKET_HEADER LTH, LCS_USER LCS WHERE LTH.USER_ID = LCS.USER_ID AND SERVICE_DATE = to_date('".$report_date."', 'MM/DD/YYYY') AND (SERVICE_GROUP LIKE '6%' OR SERVICE_GROUP LIKE '71%')";
   $statement = ora_parse($ticket_header_cursor, $sql);
   ora_exec($ticket_header_cursor);
// begin a while loop, as we take each ticket found for this date and itemize it based on earning type
   while(ora_fetch_into($ticket_header_cursor, $ticket_header_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	   $user_name = $ticket_header_row['USER_NAME'];
	   $service_group = $ticket_header_row['SERVICE_GROUP'];

	   if(array_search($ticket_header_row['USER_NAME'], $supervisors_involved) === FALSE){
//		   echo $ticket_header_row['USER_NAME']." user added<br>";
		   array_push($supervisors_involved, $ticket_header_row['USER_NAME']);
	   }

	   if(array_search($ticket_header_row['SERVICE_GROUP'], $service_codes_involved) === FALSE){
//		   echo $ticket_header_row['SERVICE_GROUP']." service added<br>";
		   array_push($service_codes_involved, $ticket_header_row['SERVICE_GROUP']);
	   }

	   $sql = "SELECT * FROM LABOR_TICKET WHERE TICKET_NUM = '".$ticket_header_row['TICKET_NUM']."'";
	   $statement = ora_parse($ticket_detail_cursor, $sql);
	   ora_exec($ticket_detail_cursor);
// this while loop increments the counter used for the summary later
	   while(ora_fetch_into($ticket_detail_cursor, $ticket_detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		   $hours_sum = $ticket_detail_row['QTY'] * $ticket_detail_row['HOURS'];
		   switch($ticket_detail_row['RATE_TYPE']){
			   case 'DT':
			   case 'MH':
				   $hour_type = 'meal';
			   break;

			   case 'OT':
			   case 'DF':
				   $hour_type = 'overtime';
			   break;

			   default:
				   $hour_type = 'standard';
			   break;
		   }

//		   echo $user_name." inloop ".$service_group." ".$hour_type." ";
		   $hour_totals[$user_name][$service_group][$hour_type]['LT'] += $hours_sum;
//		   echo $hour_totals[$user_name][$service_group][$hour_type]['LT']."<br>";
	   }
//	   echo $user_name." outloop ".$service_group."<br>";
//	   echo $hour_totals[$user_name][$service_group]['meal']['LT']." in meal<br>";
//	   echo $hour_totals[$user_name][$service_group]['overtime']['LT']." in overtime<br>";
//	   echo $hour_totals[$user_name][$service_group]['standard']['LT']." in standard<br>";


	   fwrite($handle, "\t".$ticket_header_row['USER_NAME']."\t\t".$ticket_header_row['TICKET_NUM']."\t\t".$ticket_header_row['VESSEL_ID']."\t\t".$ticket_header_row['COMMODITY_CODE']."\t\t".$ticket_header_row['SERVICE_GROUP']."\n");
	   $sql = "SELECT SUM(QTY * HOURS) TOTAL, RATE_TYPE FROM LABOR_TICKET WHERE TICKET_NUM = '".$ticket_header_row['TICKET_NUM']."' GROUP BY RATE_TYPE";
	   $statement = ora_parse($ticket_for_ticket_file_cursor, $sql);
	   ora_exec($ticket_for_ticket_file_cursor);
// this while loop generates the output for the labor ticket detail file, per super
	   while(ora_fetch_into($ticket_for_ticket_file_cursor, $TFTF_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		   fwrite($handle, "\t\t\t\t\t\t\t\t\t\t\t".$TFTF_row['TOTAL']."\t".$TFTF_row['RATE_TYPE']."\n");
	   }
   }
   fclose($handle);

   $filename2 = "LCSHours".$concat_report_date.".xls";
   $handle = fopen($filename2, "w");
   if(!$handle){
	   echo "File ".$filename2." could not be opened, please contact TS.\n";
	   include("pow_footer.php");
	   exit;
   }

   $sql = "SELECT LCS.USER_NAME SUPERV, to_char(HIRE_DATE, 'MM/DD/YYYY') HIRE_DATE, EMPLOYEE_NAME, SF.EMPLOYEE_ID, EMPLOYEE_TYPE_ID, VESSEL_ID, SERVICE_CODE, to_char(START_TIME, 'MM/DD/YYYY hh24:mi') STARTING, TO_CHAR(END_TIME, 'MM/DD/YYYY hh24:mi') ENDING, COMMODITY_CODE, PAY_LUNCH, PAY_DINNER FROM LCS_USER LCS, SF_HOURLY_DETAIL SF, EMPLOYEE EM WHERE SF.EMPLOYEE_ID = EM.EMPLOYEE_ID AND HIRE_DATE = to_date('".$report_date."', 'MM/DD/YYYY') AND (SERVICE_CODE LIKE '6%' OR SERVICE_CODE LIKE '71%') AND SF.USER_ID = LCS.USER_ID ORDER BY SUPERV";
   $statement = ora_parse($worked_cursor, $sql);
   ora_exec($worked_cursor);
// while there still exist employees who were hired on the report date, keep summing totals and writing rows
   while(ora_fetch_into($worked_cursor, $worked_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	   $service_group = substr($worked_row['SERVICE_CODE'], 0, 3);
	   $supervisor = $worked_row['SUPERV'];
	   $start_time = $worked_row['STARTING'];
	   $end_time = $worked_row['ENDING'];
	   $hire_date = $worked_row['HIRE_DATE'];
	   $pay_lunch = $worked_row['PAY_LUNCH'];
	   $pay_dinner = $worked_row['PAY_DINNER'];
	   $employee_id = $worked_row['EMPLOYEE_ID'];
	   $employee_name = $worked_row['EMPLOYEE_NAME'];
	   $employee_type_id = $worked_row['EMPLOYEE_TYPE_ID'];
	   $vessel_id = $worked_row['VESSEL_ID'];
	   $service_code = $worked_row['SERVICE_CODE'];
	   $commodity = $worked_row['COMMODITY_CODE'];

	   if(array_search($supervisor, $supervisors_involved) === FALSE){
//		   echo $ticket_header_row['USER_NAME']." user added<br>";
		   array_push($supervisors_involved, $supervisor);
	   }
	   if(array_search($service_group, $service_codes_involved) === FALSE){
//		   echo $ticket_header_row['SERVICE_GROUP']." service added<br>";
		   array_push($service_codes_involved, $service_group);
	   }


// if a new supervisor, make new row headings
	   if($current_supervisor != $supervisor){
		   fwrite($handle, "\n\n\t".$supervisor."\t\t\t\t\t\t\t\t\tMeal Includes\t\tHours\tTotal Ticketable\n");
		   fwrite($handle, "\t\tEmployee\tName\tType\tVessel\tService\tStart Time\tEnd Time\tComm\tLunch\tDinner\tWorked\tStandard\tMeal\tOvertime\n");
		   $current_supervisor = $supervisor;
	   }
// compile ticketable hours.  If service is backhaul (611X), apply different rules
       if($service_group == '611'){
		   $temp_for_ST = compile_backhaul_hours($start_time, $end_time, $hire_date, 'ST', $pay_lunch, $pay_dinner);
		   $temp_for_OT = compile_backhaul_hours($start_time, $end_time, $hire_date, 'OT', $pay_lunch, $pay_dinner);
		   $temp_for_DT = compile_backhaul_hours($start_time, $end_time, $hire_date, 'DT', $pay_lunch, $pay_dinner);
	   } else {
		   $temp_for_ST = compile_nonBackhaul_hours($start_time, $end_time, $hire_date, 'ST', $pay_lunch, $pay_dinner);
		   $temp_for_OT = compile_nonBackhaul_hours($start_time, $end_time, $hire_date, 'OT', $pay_lunch, $pay_dinner);
		   $temp_for_DT = compile_nonBackhaul_hours($start_time, $end_time, $hire_date, 'DT', $pay_lunch, $pay_dinner);
	   }
	   $hour_totals[$supervisor][$service_group]['standard']['SHD'] += $temp_for_ST;
//	   echo $supervisor." ".$service_group." ST ".$hour_totals[$supervisor][$service_group]['standard']['SHD']."<br>";
	   $hour_totals[$supervisor][$service_group]['overtime']['SHD'] += $temp_for_OT;
//	   echo $supervisor." ".$service_group." OT ".$hour_totals[$supervisor][$service_group]['overtime']['SHD']."<br>";
	   $hour_totals[$supervisor][$service_group]['meal']['SHD'] += $temp_for_DT;
//	   echo $supervisor." ".$service_group." DT ".$hour_totals[$supervisor][$service_group]['meal']['SHD']."<br>";
	   $employee_total = $temp_for_ST + $temp_for_DT + $temp_for_OT;
	   if(!ereg("^67", $service_code)){
		   $temp_for_ST = "N/A";
	   }
	   fwrite($handle, "\t\t".$employee_id."\t".$employee_name."\t".$employee_type_id."\t".$vessel_id."\t".$service_code."\t".$start_time."\t".$end_time."\t".$commodity);
	   fwrite($handle, "\t".$pay_lunch."\t".$pay_dinner."\t".$employee_total."\t".$temp_for_ST."\t".$temp_for_DT."\t".$temp_for_OT."\n");
   }

   fclose($handle);


   $filename3 = "PaidVSTickets".$concat_report_date.".xls";
   $handle = fopen($filename3, "w");

   if(!$handle){
	   echo "File ".$filename3." could not be opened, please contact TS.\n";
	   include("pow_footer.php");
	   exit;
   }


   fwrite($handle, $report_date."\n\n");
   fwrite($handle, "\t\tService Group\t\tLCS Hours Paid\t\t\tLabor Ticket Hours Submitted\t\t\tDifference (tickets minus paid)\n");
   fwrite($handle, "\t\t\t\tStandard\tOvertime\tMeal\tStandard\tOvertime\tMeal\tStandard\tOvertime\tMeal\n");
// this section writes the summary ending file.  I had to make use of "temp" varialbes to hold difference equations ebcause as it turns out,
// PHP cannot handle a line longer than 255 characters, which if I tried to in-line the equation in with the fwrite command, it would have surpassed.
   for($temp_user_counter = 0; $temp_user_counter < count($supervisors_involved); $temp_user_counter++){
       fwrite($handle, $supervisors_involved[$temp_user_counter]);
	   for($temp_services_counter = 0; $temp_services_counter < count($service_codes_involved); $temp_services_counter++){
			   if(isset($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['LT']) ||
				isset($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['LT']) ||
                isset($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['LT']) ||
                isset($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['SHD']) ||
				isset($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['SHD']) ||
				isset($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['SHD'])){
				   $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['LT'] +=0;
				   $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['LT'] +=0;
				   $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['LT'] +=0;
				   if(ereg("^67", $service_codes_involved[$temp_services_counter]) || 1){
					   fwrite($handle, "\t\t".$service_codes_involved[$temp_services_counter]);
					   fwrite($handle, "\t\t".($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['SHD'] - 0));
					   fwrite($handle, "\t".($hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['SHD'] - 0));
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['SHD']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['LT']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['LT']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['LT']);
					   $temp = $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['LT'] - $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['SHD'];
					   fwrite($handle, "\t".$temp);
					   $temp = $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['LT'] - $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['SHD'];
//					   echo $temp." OTDIFF<br>";
					   fwrite($handle, "\t".$temp);
					   $temp = $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['LT'] - $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['SHD'];
//					   echo $temp." MEALDIFF<BR>";
					   fwrite($handle, "\t".$temp."\n");
				   } else {
					   fwrite($handle, "\t\t".$service_codes_involved[$temp_services_counter]);
					   fwrite($handle, "\t\tN/A");
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['SHD']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['SHD']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['standard']['LT']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['LT']);
					   fwrite($handle, "\t".$hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['LT']);
					   fwrite($handle, "\tN/A");
					   $temp = $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['LT'] - $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['overtime']['SHD'];
//					   echo $temp." OTDIFF<br>";
					   fwrite($handle, "\t".$temp);
					   $temp = $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['LT'] - $hour_totals[$supervisors_involved[$temp_user_counter]][$service_codes_involved[$temp_services_counter]]['meal']['SHD'];
//					   echo $temp." MEALDIFF<BR>";
					   fwrite($handle, "\t".$temp."\n");
				   }
			   }
	   }
   fwrite($handle, "\n");
   }


   fclose($handle);
	
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hours worked vs. LCS Horus Paid report:
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
/*
// Email function not working ATM.

  $user = $userdata['username'];
  $query = "SELECT user_email FROM phpbb_users WHERE username='".$user."'" ;
  $result = pg_query($pg_conn, $query) or die("Error in query: $query. " .  pg_last_error($connection));
  $row_num = pg_num_rows($result);
  if($row_num == 0){
	  die("Unable to find this login name in email database.  Please contact TS.");
  } else {
     $row = pg_fetch_row($result, 0);
     $mailTO = $row[0];

	 $mailSubject = "LCS Worked vs Labor Tickets created reports";

	 $mailHeaders = "From:  MailServer@port.state.de.us\r\n";
     $mailheaders .= "MIME-Version: 1.0\r\n";
     $mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	 $mailheaders  .= "This is a multi-part Content in MIME format.\r\n";

     $body.="--MIME_BOUNDRY\r\n";
     $body.="Content-Type: application/Excel; name=\"".$filename1."\"\r\n";
     $body.="Content-disposition: attachment\r\n";
     $body.=$filename1."\r\n";
	 $body.="--MIME_BOUNDRY\r\n";
     $body.="Content-Type: application/Excel; name=\"".$filename2."\"\r\n";
     $body.="Content-disposition: attachment\r\n";
     $body.=$filename2."\r\n";
     $body.="--MIME_BOUNDRY\r\n";
     $body.="Content-Type: application/Excel; name=\"".$filename3."\"\r\n";
     $body.="Content-disposition: attachment\r\n";
     $body.=$filename3."\r\n";

	 mail($mailTO, $mailSubject, $body, $mailHeaders);
  }
*/
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<td><font size="3" face = "Verdana" color="#0066CC">Reports Generated.</font></td>
	</tr>
	<tr>
	<td><font size="2" face="Verdana" color="#0066CC">Right-Click to save the desired files.</font></td>
	</tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filename1; ?>">Labor Tickets Submitted Details
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filename2; ?>">LCS Hours Worked Details
</font>
	 </p>
      </td>
   </tr>
   <tr>
      <td>
	 <p align="left">
	    <font size="2" face="Verdana"><a href="<? echo $filename3; ?>">Worked VS Ticketed Summary
</font>
	 </p>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>

<?
// this function takes 6 inputs:  starttime $S, endtime $E, Hiredate $C, earning type $type,
// if lunch is paid $pay_lunch, and if dinner is paid $pay_dinner.  Return value is number
// of hours worked between $S and $E of type $type
function compile_backhaul_hours($S, $E, $C, $type, $pay_lunch, $pay_dinner) {
// note:  for reasons I am totally unaware of, I was unable to pass $C straight into strtotime().  My go-around
// was to get the current date, split it, and re mktime() it.
	$running_REG_total = 0;
	$running_OT_total = 0;
	$running_DT_total = 0;
	$time_loop = 0;

//	echo $S."Starting \n";
//  echo $E."Ending \n";
//	echo $C."HIRE \n";
	$temp = split("/", $C);
    $Cdate = date('m/d/Y', mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
	$is_weekend = date("l", mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
//	echo $is_weekend." BH \n";



	$start = strtotime($S);
	$end = strtotime($E);
//	echo $start."ats \n";
//	echo $end."ate \n";
	$OneAM = strtotime($Cdate) + (3600 * 1);
	$SixAM = strtotime($Cdate) + (3600 * 6);
	$SevenAM = strtotime($Cdate) + (3600 * 7);
	$noon = strtotime($Cdate) + (3600 * 12);
	$OnePM = strtotime($Cdate) + (3600 * 13);
	$SixPM = strtotime($Cdate) + (3600 * 18);
	$SevenPM = strtotime($Cdate) + (3600 * 19);
	$midnight = strtotime($Cdate); 
//	$next_midnight = strtotime($Cdate." 11:59 PM") + 60;

	for($time_loop = $start; $time_loop < $end; $time_loop += 900){
		if($time_loop >= $midnight && $time_loop < $OneAM){
			$running_DT_total++;
		}
		if($time_loop >= $SixAM && $time_loop < $SevenAM){
			$running_DT_total++;
		}
		if($time_loop >= $noon && $time_loop < $OnePM && $pay_lunch != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $SixPM && $time_loop < $SevenPM && $pay_dinner != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $OneAM && $time_loop < $SixAM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenPM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenAM && $time_loop < $noon){
			$running_REG_total++;
		}
		if($time_loop >= $OnePM && $time_loop < $SixPM){
			$running_REG_total++;
		}
	}

	if($is_weekend == "Saturday" || $is_weekend == "Sunday"){
		$running_OT_total += $running_REG_total;
		$running_REG_total = 0;
	}



	if($type == 'ST'){
		return ($running_REG_total / 4);
	}
	if($type == 'OT'){
		return ($running_OT_total / 4);
	}
	if($type == 'DT'){
		return ($running_DT_total / 4);
	}
}

// this function takes 6 inputs:  starttime $S, endtime $E, Hiredate $C, earning type $type,
// if lunch is paid $pay_lunch, and if dinner is paid $pay_dinner.  Return value is number
// of hours worked between $S and $E of type $type
function compile_nonBackhaul_hours($S, $E, $C, $type, $pay_lunch, $pay_dinner) {
// note:  for reasons I am totally unaware of, I was unable to pass $C straight into strtotime().  My go-around
// was to get the current date, split it, and re mktime() it.
	$running_REG_total = 0;
	$running_OT_total = 0;
	$running_DT_total = 0;
	$time_loop = 0;


	$start = strtotime($S);
	$end = strtotime($E);
	$temp = split("/", $C);
    $Cdate = date('m/d/Y', mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
	$is_weekend = date("l", mktime(0, 0, 0, $temp[0], $temp[1], $temp[2]));
//	echo $S." ".$start." starttime<br>";
//	echo $E." ".$end." endtime<br>";
//	echo $C."<br>".$temp[0]."<br>".$temp[1]."<br>".$temp[2]."<br>".$Cdate." hireDate<br>";
//	echo $is_weekend." dayOfWeek<br>";


//	echo $start."\n";
//	echo $end."\n";
//	echo $C."\n";
	$OneAM = strtotime($Cdate) + (3600 * 1);
	$SixAM = strtotime($Cdate) + (3600 * 6);
	$SevenAM = strtotime($Cdate) + (3600 * 7);
	$EightAM = strtotime($Cdate) + (3600 * 8);
	$noon = strtotime($Cdate) + (3600 * 12);
	$OnePM = strtotime($Cdate) + (3600 * 13);
	$FivePM = strtotime($Cdate) + (3600 * 17);
	$SixPM = strtotime($Cdate) + (3600 * 18);
	$SevenPM = strtotime($Cdate) + (3600 * 19);
	$midnight = strtotime($Cdate); 
//	$next_midnight = strtotime($C." 11:59 PM") + 60;

	for($time_loop = $start; $time_loop < $end; $time_loop += 900){
		if($time_loop >= $midnight && $time_loop < $OneAM){
			$running_DT_total++;
		}
		if($time_loop >= $SixAM && $time_loop < $SevenAM){
			$running_DT_total++;
		}
		if($time_loop >= $noon && $time_loop < $OnePM && $pay_lunch != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $SixPM && $time_loop < $SevenPM && $pay_dinner != 'N'){
			$running_DT_total++;
		}
		if($time_loop >= $OneAM && $time_loop < $SixAM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenAM && $time_loop < $EightAM){
			$running_OT_total++;
		}
		if($time_loop >= $FivePM && $time_loop < $SixPM){
			$running_OT_total++;
		}
		if($time_loop >= $SevenPM){
			$running_OT_total++;
		}
		if($time_loop >= $EightAM && $time_loop < $noon){
			$running_REG_total++;
		}
		if($time_loop >= $OnePM && $time_loop < $FivePM){
			$running_REG_total++;
		}
	}

	if($is_weekend == "Saturday" || $is_weekend == "Sunday"){
		$running_OT_total += $running_REG_total;
		$running_REG_total = 0;
	}

	if($type == 'ST'){
		return ($running_REG_total / 4);
	}
	if($type == 'OT'){
		return ($running_OT_total / 4);
	}
	if($type == 'DT'){
		return ($running_DT_total / 4);
	}
}

?>