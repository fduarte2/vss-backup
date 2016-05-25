<?
   $employees = 0;

   // figure out some dates
   // This script should be run on a Thursday
   $today = getdate();
   $ora_monday = date('d-M-y', mktime (0,0,0, $today['mon'], ($today['mday'] - 10), $today['year']));
   $printable_monday = date('F j, Y', mktime (0,0,0, $today['mon'], ($today['mday'] - 10), $today['year']));
   $ora_sunday = date('d-M-y', mktime (0,0,0, $today['mon'], ($today['mday'] - 4), $today['year']));
   $printable_sunday = date('F j, Y', mktime (0,0,0, $today['mon'], ($today['mday'] - 4), $today['year']));

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
      printf("Error logging on to the LCS Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("Please try later!");
      exit;
   }
   ora_commitoff($conn);
   $cursor = ora_open($conn);
   $sql = "select sum(d.duration), e.employee_name, e.employee_id from hourly_detail d, employee e where e.employee_id = d.employee_id and d.hire_date >= '$ora_monday' and d.hire_date <= '$ora_sunday' group by e.employee_name, e.employee_id order by sum(d.duration) desc,e.employee_name";
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
 
   $body = "";
   // Build the message
   while (ora_fetch($cursor)){
     $hours = ora_getcolumn($cursor, 0);
     $employee_name = ora_getcolumn($cursor, 1);
     $employee_id = ora_getcolumn($cursor, 2);
     // Find out if we need to log this item
     if($hours >= 60){
       $employee_name_pad = $employee_name . "                          ";
       $temp = sprintf("%.7s - %.19s : %d", $employee_id, $employee_name_pad, $hours);
       $body .= "$temp\r\n";
       $employees++;
     }
   }
   // If we got nothing to report, let the user know
   if($body == ""){
     $body = "No employees with over 60 hours this week.";
   }

   // Build the headers

//   $mailTO = "dniessen@port.state.de.us,tscott@port.state.de.us,amarkow@port.state.de.us,wstans@port.state.de.us";
     $mailTO = "gbailey@port.state.de.us,rhorne@port.state.de.us,skennard@port.state.de.us,fvignuli@port.state.de.us,tscott@port.state.de.us,amarkow@port.state.de.us,wstans@port.state.de.us,ddonofrio@port.state.de.us,parul@port.state.de.us\r\n";
//	$mailTO = "awalter@port.state.de.us\r\n";

   $mailsubject = "Weekly Over 60 Hours Report for $printable_monday to $printable_sunday: $employees Employees";
   $mailheaders = "From: " . "pownoreply@port.state.de.us\r\n";
   $mailheaders .= "Bcc: " . "archive@port.state.de.us\r\n";
//   echo "$sql\n\n";
   //echo "$mailsubject\n\n";
   //echo "$body\n\n";
   //exit;
   // Send this message off
   mail($mailTO, $mailsubject, $body, $mailheaders);
?>
