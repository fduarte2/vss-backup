<?php
/*
*
*	Adam Walter, August 2008
*
*	Auto-notification for employees to turn submit timesheets.
*	Expected runtime:  Friday 8AM.
*
**************************************************************************/

   $conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
       	exit;
   }
   $cursor = ora_open($conn);

	$sql = "SELECT EMAIL_ADDRESS FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE'";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		echo "no one works here anymore!";
		exit;
	}else{
		$mailTO = $row['EMAIL_ADDRESS'];
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$mailTO .= ",".$row['EMAIL_ADDRESS'];
		}
	}

	$mailsubject = "Friendly Timesheet Submission Reminder";
	$mailheaders = "From: NoReplies\r\n";
	$body = "Good morning,\r\n\r\nKindly remember to submit your timesheet before you leave the office today, especially if you will not be in on Monday or if Monday is a holiday.  You can submit your timesheet at any time today.  Supervisors will be able to approve any correctly submitted timesheets today as well.  This will help to ensure that HR will have the necessary information on Tuesday to process your paycheck.\r\n\r\nThank you.";

//	echo $mailTO;
//	mail($mailTO, $mailsubject, $body, $mailheaders);
	mail("dspc@port.state.de.us", $mailsubject, $body, $mailheaders);
//	mail("awalter@port.state.de.us,ithomas@port.state.de.us,lstewart@port.state.de.us", $mailsubject, $body, $mailheaders);