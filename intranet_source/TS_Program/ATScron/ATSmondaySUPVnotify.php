<?php
/*
*
*	Adam Walter, August 2008
*
*	Auto-notification SUPVs for timesheet approvals.
*	Expected runtime:  Monday  10:15AM.
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

	$mailsubject = "Timesheet Approval Reminder";
	$mailheaders = "From: NoReplies\r\n";
//	$mailheaders .= "CC: awalter@port.state.de.us,ithomas@port.state.de.us\r\n";

	$sql = "SELECT EMPLOYEE_ID, EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE' AND SUPERVISORY_STATUS = 'SUPERVISOR'";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$mailTO = $row['EMAIL_ADDRESS'];

		$submitted = "";
		$not_submitted = "";

		// submitted, awaiting approval
		$sql = "SELECT FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE ATE, TIME_SUBMISSION TS WHERE ATE.EMPLOYEE_ID = TS.EMPLOYEE_ID AND TS.WEEK_START_MONDAY = '".$last_week."' AND SUPERVISOR_ID = '".$row['EMPLOYEE_ID']."' AND STATUS = 'SUBMITTED'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2, $sql);
		if(!ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// do nothing
		} else {
			$submitted = $row2['FIRST_NAME']." ".$row2['LAST_NAME']."\r\n";
			while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$submitted .= $row2['FIRST_NAME']." ".$row2['LAST_NAME']."\r\n";
			}
		}

		// non-submitted timesheets
		$sql = "SELECT FIRST_NAME, LAST_NAME, STATUS FROM AT_EMPLOYEE ATE, TIME_SUBMISSION TS
				WHERE TS.EMPLOYEE_ID(+) = ATE.EMPLOYEE_ID
				AND WEEK_START_MONDAY(+) = '".$last_week."'
				AND SUPERVISOR_ID = '".$row['EMPLOYEE_ID']."'
				AND ATE.EMPLOYMENT_STATUS = 'ACTIVE'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2, $sql);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			if($row2['STATUS'] != 'APPROVED' && $row2['STATUS'] != 'SUBMITTED'){
				$not_submitted .= $row2['FIRST_NAME']." ".$row2['LAST_NAME']."\r\n";
			}
		}

		if($submitted == ""){
			$submitted = "None";
		}
		if($not_submitted == ""){
			$not_submitted = "None";
		}

		if($submitted != "None" || $not_submitted != "None"){
			$body = "";
//			$body = "The following employees have submitted their timesheets, and are awaiting your approval:\r\n\r\n".$submitted."The following employees have NOT yet submitted a timesheet:\r\n\r\n".$not_submitted."\r\n\r\nPlease be aware that Gene will receive notification of outstanding timesheet approvals at 11AM today, so please be prompt in your reviews.";

			if($submitted != "None"){
				$body .= "The following employees have submitted their timesheets, and are awaiting your approval:\r\n\r\n".$submitted."\r\n\r\n";
			}

			if($not_submitted != "None"){
				$body .= "The following employees have NOT yet submitted a timesheet:\r\n\r\n".$not_submitted."\r\n\r\n";
			}

			mail($mailTO, $mailsubject, $body, $mailheaders);
		}
	}