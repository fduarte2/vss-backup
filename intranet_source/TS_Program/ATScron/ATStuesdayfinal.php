<?php
/*
*
*	Adam Walter, August 2008
*
*	Auto-notification of "finalized" timesheet.
*	Expected runtime:  Tuesday 5:00PM.
*
**************************************************************************/


   $last_week = date('d-M-Y', mktime(0,0,0,date('m'), date('d') - 8, date('Y')));
   $nice_last_week = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - 8, date('Y')));

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
       	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

	$mailsubject = "Timesheet Information, ".$nice_last_week;
	$mailheaders = "From: NoReplies\r\n";
//	$mailheaders .= "CC: awalter@port.state.de.us,ithomas@port.state.de.us\r\n";

	$sql = "SELECT NVL(TO_CHAR(SUBMISSION_DATETIME, 'MM/DD/YYYY HH24:MI'), 'NONE') SUB_TIME,
			   NVL(TO_CHAR(SIGN_OFF_DATETIME, 'MM/DD/YYYY HH24:MI'), 'NONE') SIGN_TIME,
			   ATE.EMPLOYEE_ID, FIRST_NAME, LAST_NAME, DEPARTMENT_ID, STATUS,
			   DECODE(SUBMISSION_PC_USERID, 'cron', 'Auto-Submitted', SUBMISSION_PC_USERID) THE_SUBBER,
			   DECODE(SIGN_OFF_PC_USERID, 'cron', 'Auto-Approved', SIGN_OFF_PC_USERID) THE_SIGNER,
			   WEEK_TOTAL_REG, WEEK_TOTAL_HOLIDAY, EMAIL_ADDRESS, CONDITIONAL_SUBMISSION, SUBMISSION_PC, SIGN_OFF_PC,
			   WEEK_TOTAL_VACATION, WEEK_TOTAL_PERSONAL, WEEK_TOTAL_SICK, WEEK_TOTAL_OVERTIME, WEEK_TOTAL_TOTAL
			FROM AT_EMPLOYEE ATE, TIME_SUBMISSION TS
			WHERE ATE.EMPLOYEE_ID = TS.EMPLOYEE_ID
			AND TS.WEEK_START_MONDAY = '".$last_week."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailTO = $row['EMAIL_ADDRESS'];

		$body = "Here is the most recent timesheet information, for your records.\r\n\r\n";
		$body .= "Week of: ".$nice_last_week."\r\n";
		if($row['CONDITIONAL_SUBMISSION'] == 'Y'){
			$body .= "Status: ".$row['STATUS']." ---CONDITIONAL---\r\n\r\n";
		}else{
			$body .= "Status: ".$row['STATUS']."\r\n\r\n";
		}

		$body .= "Employee: ".$row['FIRST_NAME']." ".$row['LAST_NAME']."\r\n";
		$body .= "ID : ".$row['EMPLOYEE_ID']."\r\n";
		$body .= "Department: ".$row['DEPARTMENT_ID']."\r\n\r\n";

		$body .= "Submitted by: ".$row['THE_SUBBER']."\r\n";
		$body .= "Submitted from: ".$row['SUBMISSION_PC']."\r\n";
		$body .= "Submission time: ".$row['SUB_TIME']."\r\n\r\n";

		$body .= "Approved by: ".$row['THE_SIGNER']."\r\n";
		$body .= "Approved from: ".$row['SIGN_OFF_PC']."\r\n";
		$body .= "Approved time: ".$row['SIGN_TIME']."\r\n\r\n";

		$body .= "Hours:\r\n\r\n";
		$body .= "Regular: ".$row['WEEK_TOTAL_REG']."\r\n";
		$body .= "Holiday: ".$row['WEEK_TOTAL_HOLIDAY']."\r\n";
		$body .= "Vacation: ".$row['WEEK_TOTAL_VACATION']."\r\n";
		$body .= "Personal: ".$row['WEEK_TOTAL_PERSONAL']."\r\n";
		$body .= "Sick: ".$row['WEEK_TOTAL_SICK']."\r\n";
		$body .= "Overtime: ".$row['WEEK_TOTAL_OVERTIME']."\r\n";
		$body .= "Total: ".$row['WEEK_TOTAL_TOTAL']."\r\n\r\n";

		if($row['CONDITIONAL_SUBMISSION'] == 'Y'){ // extra line in email for conditionals
			if($row['STATUS'] != 'SUBMITTED'){
				$body .= "Please note:  The timesheet for the week of ".$nice_last_week." is currently in a conditional status, and is waiting for re-submission from you.  You will not be able to enter the time for future weeks until you have re-submitted this one.\r\n\r\nThank you.";
			}
		}

//		echo $mailTO."  ";
		mail($mailTO, $mailsubject, $body, $mailheaders);
	}