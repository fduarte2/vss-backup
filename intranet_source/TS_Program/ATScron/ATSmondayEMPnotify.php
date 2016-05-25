<?php
/*
*
*	Adam Walter, August 2008
*
*	Auto-notification "last chance" for employees to turn submit timesheets.
*	Expected runtime:  Monday  9AM.
*
**************************************************************************/


   $last_week = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 7, date('Y')));

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
       	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

	$mailsubject = "Last Timesheet Submission Reminder";
	$mailheaders = "From: NoReplies\r\n";
//	$mailheaders .= "CC: awalter@port.state.de.us,ithomas@port.state.de.us\r\n";


	$sql = "SELECT EMPLOYEE_ID, EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE'";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailTO = $row['EMAIL_ADDRESS'];

		$sql = "SELECT STATUS FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '".$row['EMPLOYEE_ID']."' AND WEEK_START_MONDAY = '".$last_week."'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2, $sql);
		if(!ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$body = "Your timesheet has not yet been created.  You have until 10:00 am today to create it for your supervisor’s approval.  After 10:00, a conditional timesheet for 40 hours will be created and submitted for you.  You will still be responsible for submitting a timesheet for this period in order for your records to be accurately maintained.";
			mail($mailTO, $mailsubject, $body, $mailheaders);
		}elseif($row2['STATUS'] == 'ON HOLD'){
			$body = "Your timesheet has been saved, but not yet submitted.  You have until 10:00 am today to submit it for your supervisor’s approval.  After 10:00, your currently saved timesheet will be given to your supervisor for you.  You will still be responsible for submitting a timesheet for this period in order for your records to be accurately maintained.";
			mail($mailTO, $mailsubject, $body, $mailheaders);
		}
	}


//	echo $mailTO;
//	mail($mailTO, $mailsubject, $body, $mailheaders);
//	mail("awalter@port.state.de.us,ithomas@port.state.de.us,lstewart@port.state.de.us", $mailsubject, $body, $mailheaders);