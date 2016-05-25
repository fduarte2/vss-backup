<?
/*
*  Adam Walter, Dec 2014.
*
*	uploads xls File for a type of V2 bill
*	(termserv and transfer combined)
*********************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

 
  // Define some vars for the skeleton page
  $title = "Combo Billing";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}


	$lcsconn = ocilogon("LABOR", "LABOR", "LCS");
	if($lcsconn < 1){
		printf("Error logging on to the LCS Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$date = $HTTP_POST_VARS['date'];
	$supv_id = $HTTP_POST_VARS['supv_id'];

	if($submit == "Rejected"){
		$reason = $HTTP_POST_VARS['reason'];
		$reason = str_replace("'", "`", $reason);
		if($reason == ""){
			echo "<font color=\"#FF0000\">If you are Rejecting, a Reason must be given.</font>";
			$submit = "Retrieve Records";
		}
	}

	if($submit == "Rejected" || $submit == "Approved"){
		$sql = "INSERT INTO HR_SUPV_APPROVES
					(DATE_OF_ACTION,
					ACTION_BY,
					ACTION_STATUS,
					REJECT_REASON,
					SUPV_ID,
					WEEK_END_OF_HIRE)
				VALUES
					(SYSDATE,
					'".$user."',
					'".$submit."',
					'".$reason."',
					'".$supv_id."',
					TO_DATE('".$date."', 'MM/DD/YYYY'))";
		$insert_data = ociparse($lcsconn, $sql);
		ociexecute($insert_data);

		if($submit == "Rejected"){
			SendEmail($date, $supv_id, $reason, $user, $rfconn, $bniconn);
		}
	}




?>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Supervisor Time Review and Approval
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<form action="SUPVRevApp.php" method="post" name="get_data">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Week Ending:</font></td>
		<td align="left"><input name="date" type="text" size="15" maxlength="15" value="<? echo $date; ?>">&nbsp;&nbsp;<a href="javascript:show_calendar('get_data.date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="10%" align="left"><font size="2" face="Verdana">Supervisor:</font></td>
		<td><select name="supv_id">
<?
	$sql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME 
			FROM EMPLOYEE
			WHERE EMPLOYEE_TYPE_ID='SUPV'
			ORDER BY EMPLOYEE_ID";
	$short_term_data = ociparse($lcsconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "EMPLOYEE_ID"); ?>"<? if(ociresult($short_term_data, "EMPLOYEE_ID") == $supv_id){?> selected <?}?>>
											<? echo ociresult($short_term_data, "EMPLOYEE_ID")."-".ociresult($short_term_data, "EMPLOYEE_NAME"); ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Records"></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
		$total = 0;
		$sql = "SELECT EARNING_TYPE_ID, SUM(DURATION) THE_TOTAL
				FROM HOURLY_DETAIL
                WHERE EMPLOYEE_ID='".$supv_id."'
					AND HIRE_DATE <= TO_DATE('".$date."', 'MM/DD/YYYY')
					AND HIRE_DATE >= TO_DATE('".$date."', 'MM/DD/YYYY') - 6
                GROUP BY EARNING_TYPE_ID";
		$short_term_data = ociparse($lcsconn, $sql);
		ociexecute($short_term_data);
		while(ocifetch($short_term_data)){
			switch(ociresult($short_term_data, "EARNING_TYPE_ID")){
				case "REG":
					$reg_hrs = ociresult($short_term_data, "THE_TOTAL");
				break;
				case "OT":
					$ot_hrs = ociresult($short_term_data, "THE_TOTAL");
				break;
				case "SICK":
					$sick_hrs = ociresult($short_term_data, "THE_TOTAL");
				break;
				case "PERS":
					$pers_hrs = ociresult($short_term_data, "THE_TOTAL");
				break;
				case "VAC":
					$vac_hrs = ociresult($short_term_data, "THE_TOTAL");
				break;
				case "HOL":
					$hol_hrs = ociresult($short_term_data, "THE_TOTAL");
				break;
			}

			$total += ociresult($short_term_data, "THE_TOTAL");
		}

		$sql = "SELECT FIRST_NAME, LAST_NAME, EMPLOYEE_ID
				FROM AT_EMPLOYEE
				WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '".$supv_id."')";
		$short_term_data = ociparse($lcsconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$disp_manager = "UNKNOWN";
		} else {
			$disp_manager = ociresult($short_term_data, "FIRST_NAME")." ".ociresult($short_term_data, "LAST_NAME")." (".ociresult($short_term_data, "EMPLOYEE_ID").")";
		}

		$sql = "SELECT ACTION_STATUS, DATE_OF_ACTION, ACTION_BY, TO_CHAR(DATE_OF_ACTION, 'MM/DD/YYYY HH24:MI:SS') THE_ACT, REJECT_REASON
				FROM HR_SUPV_APPROVES
				WHERE SUPV_ID = '".$supv_id."'
					AND TO_CHAR(WEEK_END_OF_HIRE, 'MM/DD/YYYY') = '".$date."'
				ORDER BY DATE_OF_ACTION DESC";
		$short_term_data = ociparse($lcsconn, $sql);
		ociexecute($short_term_data);
		if(!ocifetch($short_term_data)){
			$disp_status = "PENDING";
		} elseif(ociresult($short_term_data, "ACTION_STATUS") == "Approved") {
			$disp_status = ociresult($short_term_data, "ACTION_STATUS")." (By ".ociresult($short_term_data, "ACTION_BY")." On ".ociresult($short_term_data, "THE_ACT").")";
		} else {
			$disp_status = ociresult($short_term_data, "ACTION_STATUS")." (By ".ociresult($short_term_data, "ACTION_BY")." On ".ociresult($short_term_data, "THE_ACT").
						" Reason: ".ociresult($short_term_data, "REJECT_REASON").")";
		}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="dostuff" action="SUPVRevApp.php" method="post">
<input type="hidden" name="date" value="<? echo $date; ?>">
<input type="hidden" name="supv_id" value="<? echo $supv_id; ?>">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Current Status:</font></td>
		<td><font size="2" face="Verdana"><? echo $disp_status; ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Manager:</font></td>
		<td><font size="2" face="Verdana"><? echo $disp_manager; ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">REG Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $reg_hrs); ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">OT Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $ot_hrs); ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">VAC Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $vac_hrs); ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">PERS Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $pers_hrs); ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">SICK Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $sick_hrs); ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">HOL Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $hol_hrs); ?></font></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Total Hours:</font></td>
		<td><font size="2" face="Verdana"><? echo (0 + $total); ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Approved">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Rejected">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">Reason For Rejection:&nbsp;</font><input type="text" name="reason" size="100" maxlength="100"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");






function SendEmail($date, $supv_id, $reason, $user, $rfconn, $bniconn){
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'REJECTSUPVTIMESHEET'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$sql = "SELECT EMAIL_ADDRESS
			FROM AT_EMPLOYEE
			WHERE EMPLOYEE_ID = '".$supv_id."'";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$supv_email = ociresult($short_term_data, "EMAIL_ADDRESS");
	$sql = "SELECT EMAIL_ADDRESS
			FROM AT_EMPLOYEE
			WHERE EMPLOYEE_ID = (SELECT SUPERVISOR_ID
								FROM AT_EMPLOYEE
								WHERE EMPLOYEE_ID = '".$supv_id."')";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$manager_email = ociresult($short_term_data, "EMAIL_ADDRESS");


	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "TEST") == "Y"){
		$mailTO = "awalter@port.state.de.us";
		$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,awalter@port.state.de.us\r\n";
	} else {
		$mailTO = ociresult($email, "TO");
		$mailTO = str_replace("_0_", $supv_email, $mailTO);
		if(ociresult($email, "CC") != ""){
			$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			$mailheaders = str_replace("_1_", $manager_email, $mailheaders);
		}
		if(ociresult($email, "BCC") != ""){
			$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
		}
	}

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_2_", $date, $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_3_", $reason, $body);

	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
		$sql = "INSERT INTO JOB_QUEUE
					(JOB_ID,
					SUBMITTER_ID,
					SUBMISSION_DATETIME,
					JOB_TYPE,
					JOB_DESCRIPTION,
					DATE_JOB_COMPLETED,
					COMPLETION_STATUS,
					JOB_EMAIL_TO,
					JOB_EMAIL_CC,
					JOB_EMAIL_BCC,
					JOB_BODY)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'".$user."',
					SYSDATE,
					'EMAIL',
					'REJECTSUPVTIMESHEET',
					SYSDATE,
					'COMPLETED',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."')";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
	}
}