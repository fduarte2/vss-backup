<?

/*
*  Adam Walter, July 2007
*  This is a cron job designed to notify the supervisors when
*  The temperature settings are about to / have expired.
*
*  It is expected to be run once daily, at an off-hours time,
*  So that it can capture the day's worth of info without
*  Being unnecessarily spammy.
*
*  Why this sin't built into the daily-email routine that sends
*  A report to the port's directors is I don't want to screw
*  Up that currently-working script, as I have to make this one
*  In a day maximum :(
****************************************************************/

	$conn = ora_logon("LABOR@LCS", "LABOR");
	if($conn < 1){
		printf("Error logging on to the Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("</body></html>");
		exit;
	}
	$cursor = ora_open($conn);
	$warehouse_cursor = ora_open($conn);

	$emailbody = "";
	$now = mktime();

	$sql = "SELECT DISTINCT WHSE, BOX FROM WAREHOUSE_LOCATION ORDER BY WHSE, BOX";
	$statement = ora_parse($warehouse_cursor, $sql);
	ora_exec($warehouse_cursor);

	
	while(ora_fetch_into($warehouse_cursor, $warehouse_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
        $whse = $warehouse_row['WHSE'];
        $box = $warehouse_row['BOX'];
		$both = $whse.$box;

		$sql =  "select whse, box, to_char(expired, 'MM/DD/YYYY HH:MI:SS AM') the_exp, user_name
				from temperature_req
				where (whse, box, req_id) in
				(select whse, box, max(req_id) from temperature_req
				where effective <= sysdate and whse='$whse' and box=$box
				group by whse, box)";

		$statement = ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC) && $both != "A4" && $both != "B1" && $both != "B2"){
			$exp_time = strtotime($row['THE_EXP']);
//			echo $exp_time." ".date('m/d/Y h:i:s a', $exp_time)."\n";

			if($now > $exp_time){
				$emailbody .= $row['WHSE']."-".$row['BOX']."  ---EXPIRED---  Last Entered By: ".$row['USER_NAME']."\r\n\r\n";
			} elseif($now + 604800 > $exp_time){
//			} elseif($now + 1815400 > $exp_time){ // 3 weeks
				$emailbody .= $row['WHSE']."-".$row['BOX']."  Expires on: ".$row['THE_EXP']."  Last Entered By: ".$row['USER_NAME']."\r\n\r\n";
			}
		}
	}

	if($emailbody != ""){
//		$body = "The following warehouse boxes are at or near the expiration date of their requested temperatures:\r\n\r\n";
		$body = $emailbody;
		$body .= "\r\nPlease renew temperature requests for the above ASAP.";

//		$mailto = "awalter@port.state.de.us,ithomas@port.state.de.us";
		$mailto = "OPSSupervisors@port.state.de.us";
		$mailsubject = "ALERT: Temperature Expiration - Action Required";
		$mailheaders = "From:  Mailserver@port.state.de.us\r\n";
		$mailheaders .= "CC:  fvignuli@port.state.de.us,gbailey@port.state.de.us,rhorne@port.state.de.us,bbarker@port.state.de.us\r\n";
		$mailheaders .= "Bcc:  awalter@port.state.de.us,ithomas@port.state.de.us";

		mail($mailto, $mailsubject, $body, $mailheaders);
//		echo $emailbody;
	}
