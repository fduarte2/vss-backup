<?
/*
*			Charles Marttinen, March 2015
*			This page sends a HelpDesk summary email to person assigned
*
******************************************************************/

//Connect to SQL
$conn = ocilogon("SAG_OWNER", "SAG", "BNI");
if($conn < 1){
	printf("Error logging on to the RF Oracle Server: ");
	exit;
}

//Get all TS_Contacts
$sql = "SELECT DISTINCT TS_CONTACT
		FROM HELPDESK";
$all_ts_contacts = ociparse($conn, $sql);
ociexecute($all_ts_contacts);

//For each TS_Contact...
while(ocifetch($all_ts_contacts)) {
	//Get open HelpDesk jobs
	$ts_contact = ociresult($all_ts_contacts, "TS_CONTACT");
	$sql = "select nvl(to_char(H.JOBID), 'None') as JOBID,
				H.MASTERID,
				H.DESCRIPTION,
				P.HP_DESCRIPTION,
				to_char(H.DATE_CREATED, 'MM/DD/YY HH:MI:SS AM') as DATE_CREATED,
				H.USERID,
				S.HS_DESCRIPTION
			from HELPDESK H
			left join HELPDESK_PRIORITY P
				on H.PRIORITY=P.PRIORITY_ID
			left join HELPDESK_STATUS S
				on S.STATUS_ID=H.JOB_STATUS
			where H.JOB_STATUS<7
				and H.TS_CONTACT='".$ts_contact."'
			order by H.PRIORITY, H.DATE_CREATED desc";
	$helpdesk = ociparse($conn, $sql);
	ociexecute($helpdesk);
	
	//HTML table of open jobs
	$table = "
<table border='1' width='100%' cellpadding='4' cellspacing='0'>
	<tr>
		<td><font size='2' face='Verdana'><b>Job ID</b></font>
		<td><font size='2' face='Verdana'><b>Description</b></font>
		<td><font size='2' face='Verdana'><b>Priority</b></font>
		<td><font size='2' face='Verdana'><b>Date Created</b></font>
		<td><font size='2' face='Verdana'><b>Status</b></font>
		<td><font size='2' face='Verdana'><b>User</b></font>
	</tr>";

	while(ocifetch($helpdesk)) {
		$i++;
		$colour = (($i & 1)? "FBFBFB": "white"); //uses bitchecking and the ternary operator; if odd, light grey, else white
		$jobid = ociresult($helpdesk, "JOBID");
		$masterid = ociresult($helpdesk, "MASTERID");
		$desc = ociresult($helpdesk, "DESCRIPTION");
		
		//Truncates a string to <300 chars to the nearest word and adds "..." when necessary
		$desc = str_replace(array("\r", "\r\n", "\n"), '   ', $desc);
		$desc = strtok(wordwrap($desc, 300, "...\n"), "\n");
		
		$priority = ociresult($helpdesk, "HP_DESCRIPTION");
		$date = ociresult($helpdesk, "DATE_CREATED");
		$status = ociresult($helpdesk, "HS_DESCRIPTION");
		$user = ociresult($helpdesk, "USERID");
		
		$table .= "<tr bgcolor='$colour'>
				<td><font size='2' face='Verdana'><a href='http://intranet/HelpDeskNew/HD_enter.php?ID=$masterid'>$jobid</a></font>
				<td><font size='2' face='Verdana'>$desc</font>
				<td><font size='2' face='Verdana'>$priority</font>
				<td><font size='2' face='Verdana'>$date</font>
				<td><font size='2' face='Verdana'>$status</font>
				<td><font size='2' face='Verdana'>$user</font></tr>";
	}
	$table .= "</table>";
	
	//Get the number of jobs for each priority level, and the overall total
	$sql = "select PRIORITY, HP_DESCRIPTION, COUNT(PRIORITY) TOTAL
			from HELPDESK
			left join HELPDESK_PRIORITY
				on PRIORITY=PRIORITY_ID
			where JOB_STATUS < 7
				and TS_CONTACT='".$ts_contact."'
			group by HP_DESCRIPTION, PRIORITY
			order by PRIORITY";
	$priority_nums = ociparse($conn, $sql);
	ociexecute($priority_nums);
	$total_jobs = ""; //initialise vars
	$jobs_per = "";
	while(ocifetch($priority_nums)) {
		$total_jobs += ociresult($priority_nums, "TOTAL");
		$jobs_per .= ociresult($priority_nums, "TOTAL") . " " . ociresult($priority_nums, "HP_DESCRIPTION") . "</br>";
	}
	$jobs_per = "<b>$total_jobs Help Desk job(s):</b></br>$jobs_per<br/>";
	
	//Count jobs closed yesterday
	$sql = "select count(LAST_CHANGED) as YESTERDAY
			from HELPDESK
			where TS_CONTACT = '".$ts_contact."'
				and JOB_STATUS > 6
				and trunc(LAST_CHANGED) = trunc(sysdate)-1";
	$jobs_yesterday = ociparse($conn, $sql);
	ociexecute($jobs_yesterday);
	ocifetch($jobs_yesterday);
	$total_yesterday = ociresult($jobs_yesterday, "YESTERDAY") . " jobs closed yesterday";
	
	//Count jobs closed last week (last 7 days)
	$sql = "select COUNT(LAST_CHANGED) as LAST_WEEK
			from HELPDESK
			where TS_CONTACT='".$ts_contact."'
				and JOB_STATUS between 7 and 9
				and trunc(LAST_CHANGED) between (trunc(sysdate)-8) and (trunc(sysdate)-1)";
	$jobs_week = ociparse($conn, $sql);
	ociexecute($jobs_week);
	ocifetch($jobs_week);
	$total_lastweek = ociresult($jobs_week, "LAST_WEEK") . " jobs closed in the last 7 days" . "<br/>";
	
	//Assemble subject and body
	$subject = "Help Desk Summary: $total_jobs job(s) for $ts_contact; $total_yesterday";
	$body = $jobs_per . $total_yesterday  . "<br/>" . $total_lastweek . $table;
	
	//To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	//Additional headers
	$headers .= 'From: PoWnoreply@port.state.de.us' . "\r\n";
	$headers .= 'Bcc: archive@port.state.de.us, cmarttinen@port.state.de.us' . "\r\n";
	
	//Send email to recipient
	mail("$ts_contact@port.state.de.us", $subject, $body, $headers);
	
	// mail("cmarttinen@port.state.de.us", $subject, $body, $headers);
	// echo "$subject<br/>$body<br/><hr><br/>";
}