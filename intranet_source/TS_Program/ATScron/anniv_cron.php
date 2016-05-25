<?
/* Adam Walter, June 2007.
*  
*	This cron job is related to ATS in that it raeds from 2 tables
*	And determines who in AT_EMPLOYEE has passed a breakpoint of tenure
*	Based on VACATION_RATE.  Anyone who has, it generates an email to
*	Sylvia alerting to the need to change their vacation rates.
**************************************************************************************/


  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
//  $conn = ora_logon("SAG_OWNER@BNI.DEV", "SAG_DEV");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

	$sql = "SELECT LAST_NAME || ', ' || FIRST_NAME || ' (' || EMPLOYEE_ID || ') - ' || BREAK_POINT_YEAR || ' years of service, new vacation rate -- ' || HOURLY_VACATION_RATE || ' hours/week (' || round(HOURLY_VACATION_RATE * 52 / 8) || 'days/year)' THE_STRING, EMAIL_ADDRESS FROM AT_EMPLOYEE ATE, VACATION_RATE VR WHERE (ATE.ANNIVERSARY_DATE + numtoyminterval(VR.BREAK_POINT_YEAR, 'YEAR')) < SYSDATE AND (ATE.ANNIVERSARY_DATE + numtoyminterval(VR.BREAK_POINT_YEAR, 'YEAR')) > SYSDATE - 7 AND EMPLOYMENT_STATUS = 'ACTIVE' ORDER BY LAST_NAME";
	$statement = ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		exit;
	} else {
		do {
			$body = $row['THE_STRING']."\r\n";
			$mailSubject = "Employee Anniversary Date passed, ATS update needed";
			$mailheaders = "From: " . "No-Reply@port.state.de.us\r\n";
//			$mailheaders .= "Bcc: " . "ithomas@port.state.de.us,awalter@port.state.de.us\r\n"; -- 2/23/2012
			$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
			$mailTo = $row['EMAIL_ADDRESS'].",skennard@port.state.de.us";

//			echo $mailTo."    ".$mailSubject."   ".$body."   ".$mailheaders;
			mail($mailTo, $mailSubject, $body, $mailheaders);

		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	}
?>