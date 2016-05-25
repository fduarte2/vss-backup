<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Employee Absentee Entry";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }


	$bniconn = ocilogon("LABOR", "LABOR", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($bniconn < 1){
		printf("Error logging on to the LCS Oracle Server: ");
		printf(ora_errorcode($bniconn));
		exit;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rfconn));
		exit;
	}

	$employee = $HTTP_POST_VARS['employee'];
	$hire = $HTTP_POST_VARS['hire'];
	$submit = $HTTP_POST_VARS['submit'];

	if($hire != "" && !ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $hire)){
		echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format.</font>";
		$hire = "";
	}
	if($hire != ""){
		$temp = explode("/", $hire);
		if(mktime(0,0,0,$temp[0],$temp[1],$temp[2]) > time()){
			echo "<font color=\"#FF0000\">Date cannot be in the future.</font>";
			$hire = "";
		}
	}

	if($employee != ""){
		$sql = "SELECT EMPLOYEE_NAME, EMPLOYEE_TYPE_ID
				FROM EMPLOYEE
				WHERE EMPLOYEE_ID = '".$employee."'";
		$emp_data = ociparse($bniconn, $sql);
		ociexecute($emp_data);
		ocifetch($emp_data);
		$emp_type = ociresult($emp_data, "EMPLOYEE_TYPE_ID");
		if($emp_type == "REGR"){
			$sql_dateclause_current = " AND EXCUSE_DATE >= ADD_MONTHS(TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY'), -6) ";
			$sql_dateclause_past = " AND EXCUSE_DATE < ADD_MONTHS(TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY'), -6) ";
			 
		} else {
			$sql_dateclause_current = " AND EXCUSE_DATE >= TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY') - 14 ";
			$sql_dateclause_past = " AND EXCUSE_DATE < TO_DATE(TO_CHAR(SYSDATE, 'MM/DD/YYYY'), 'MM/DD/YYYY') - 14 ";
		}
	}

	if($submit == "Add Absentee Notice" && $employee != "" && $hire != ""){
		$reason = $HTTP_POST_VARS['reason'];
		if($reason == ""){
			echo "<font color=\"#FF0000\">No reason was selected.  Entry Aborted.</font>";
		} else {
			$sql = "INSERT INTO EMPLOYEE_EXCUSE_HISTORY
						(EMPLOYEE_ID,
						LETTERS_GENERATED,
						EXCUSE_DATE,
						REASON_ID)
					VALUES
						('".$employee."',
						'0',
						TO_DATE('".$hire."', 'MM/DD/YYYY'),
						'".$reason."')";
			$update = ociparse($bniconn, $sql);
			ociexecute($update);

			echo "<font color=\"#0000FF\">Entry Added.</font>";

			$sql = "SELECT COUNT(*) THE_COUNT
					FROM EMPLOYEE_EXCUSE_HISTORY EEH, EMPLOYEE_EXCUSE EE
					WHERE EMPLOYEE_ID = '".$employee."'
						AND EE.ROW_ID = EEH.REASON_ID
						AND COUNT_TOWARDS_NOTIFY = 'Y' ".$sql_dateclause_current;
			$emp_data = ociparse($bniconn, $sql);
			ociexecute($emp_data);
			ocifetch($emp_data);
			if($emp_type == "REGR" && ociresult($emp_data, "THE_COUNT") == 6){
				SendMailAndUpdate("UNEX_ABSENT_WARN", $employee, $rfconn, $bniconn);
				$sql = "UPDATE EMPLOYEE_EXCUSE_HISTORY
						SET LATEST_LETTER = SYSDATE,
							LETTERS_GENERATED = LETTERS_GENERATED + 1
						WHERE EMPLOYEE_ID = '".$employee."' ".$sql_dateclause_current;
				$update = ociparse($bniconn, $sql);
				ociexecute($update);
			} elseif($emp_type == "REGR" && ociresult($emp_data, "THE_COUNT") > 6){
				SendMailAndUpdate("UNEX_ABSENT_EXCEED", $employee, $rfconn, $bniconn);
			} elseif($emp_type == "CASB" && ociresult($emp_data, "THE_COUNT") >= 2){
				SendMailAndUpdate("UNEX_ABSENT_CASB", $employee, $rfconn, $bniconn);
				$sql = "UPDATE EMPLOYEE_EXCUSE_HISTORY
						SET LATEST_LETTER = SYSDATE,
							LETTERS_GENERATED = LETTERS_GENERATED + 1
						WHERE EMPLOYEE_ID = '".$employee."' ".$sql_dateclause_current;
				$update = ociparse($bniconn, $sql);
				ociexecute($update);
			}

		}
	}


?>

<script language="JavaScript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">LCS-Employee Unexcused Absentee Entry
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_emp" action="unexcused_absent.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Employee:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="employee"><option value="">Select An Employee</option>
<?
	$sql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME
			FROM EMPLOYEE
			WHERE EMPLOYEE_TYPE_ID IN ('CASB', 'REGR')
			ORDER BY EMPLOYEE_ID";
	$distinct_list = ociparse($bniconn, $sql);
	ociexecute($distinct_list);
	while(ocifetch($distinct_list)){
?>
							<option value="<? echo ociresult($distinct_list, "EMPLOYEE_ID"); ?>"<? if(ociresult($distinct_list, "EMPLOYEE_ID") == $employee){?> selected <?}?>>
								<? echo ociresult($distinct_list, "EMPLOYEE_ID")." - ".ociresult($distinct_list, "EMPLOYEE_NAME"); ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Hire Date:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
		<td><input type="text" name="hire" size="10" maxlength="10" value="<? echo $hire; ?>"><a href="javascript:show_calendar('get_emp.hire');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve Employee"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit != "" && $employee != ""){

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Retreived Employee Name:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
		<td width="20%"><font size="2" face="Verdana"><? echo ociresult($emp_data, "EMPLOYEE_NAME"); ?></font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3"><hr><font size="2" color="#FF0000" face="Verdana"><b>Recent Unexcused Absences:&nbsp;&nbsp;&nbsp;&nbsp;<b></font></td>
	</tr>
<?
		$sql = "SELECT REASON, TO_CHAR(EXCUSE_DATE, 'MM/DD/YYYY') THE_DATE, EXCUSE_DATE
				FROM EMPLOYEE_EXCUSE EE, EMPLOYEE_EXCUSE_HISTORY EEH
				WHERE EE.ROW_ID = EEH.REASON_ID ".$sql_dateclause_current."
					AND EEH.EMPLOYEE_ID = '".$employee."'
				ORDER BY EXCUSE_DATE";
		$excuse_data = ociparse($bniconn, $sql);
		ociexecute($excuse_data);
		if(!ocifetch($excuse_data)){
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">None</font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td>&nbsp;</td>
		<td width="20%" align="left"><font size="2" face="Verdana"><? echo ociresult($excuse_data, "THE_DATE"); ?></font></td>
		<td align="left"><font size="2" face="Verdana"><? echo ociresult($excuse_data, "REASON"); ?></font></td>
	</tr>
<?
			} while(ocifetch($excuse_data));
		}
?>
	<tr>
		<td colspan="3">&nbsp;<hr><font size="2" color="#0000FF" face="Verdana">Past Unexcused Absences:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
	</tr>
<?
		$sql = "SELECT REASON, TO_CHAR(EXCUSE_DATE, 'MM/DD/YYYY') THE_DATE, EXCUSE_DATE
				FROM EMPLOYEE_EXCUSE EE, EMPLOYEE_EXCUSE_HISTORY EEH
				WHERE EE.ROW_ID = EEH.REASON_ID ".$sql_dateclause_past."
					AND EEH.EMPLOYEE_ID = '".$employee."'
				ORDER BY EXCUSE_DATE";
		$excuse_data = ociparse($bniconn, $sql);
		ociexecute($excuse_data);
		if(!ocifetch($excuse_data)){
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2"><font size="2" face="Verdana">None</font></td>
	</tr>
<?
		} else {
			do {
?>
	<tr>
		<td>&nbsp;</td>
		<td width="20%" align="left"><font size="2" face="Verdana"><? echo ociresult($excuse_data, "THE_DATE"); ?></font></td>
		<td align="left"><font size="2" face="Verdana"><? echo ociresult($excuse_data, "REASON"); ?></font></td>
	</tr>
<?
			} while(ocifetch($excuse_data));
		}
?>
	<tr>
		<td colspan="3">&nbsp;<hr>&nbsp;</font></td>
	</tr>
</table>
<?
	}

	if($submit != "" && $employee != ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2"><font size="3" face="Verdana">Add New Entry</font></td>
	</tr>
<?
		if($hire == ""){
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">To Add a new entry, a hire date must be chosen.  Please enter a Hire Date in the top box, and Retrieve the Employee Data again.</font></td>
	</tr>
<?
		} else {
			$sql = "SELECT COUNT(*) THE_COUNT
					FROM EMPLOYEE_EXCUSE_HISTORY
					WHERE EMPLOYEE_ID = '".$employee."'
						AND EXCUSE_DATE = TO_DATE('".$hire."', 'MM/DD/YYYY')";
			$excuse_check = ociparse($bniconn, $sql);
			ociexecute($excuse_check);
			ocifetch($excuse_check);
			$excuse_check_count = ociresult($excuse_check, "THE_COUNT");

			$sql = "SELECT COUNT(*) THE_COUNT
					FROM DAILY_HIRE_LIST
					WHERE EMPLOYEE_ID = '".$employee."'
						AND HIRE_DATE = TO_DATE('".$hire."', 'MM/DD/YYYY')";
			$hire_check = ociparse($bniconn, $sql);
			ociexecute($hire_check);
			ocifetch($hire_check);
			$hire_check_count = ociresult($hire_check, "THE_COUNT");

			if($excuse_check_count >= 1){
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Employee already has an entry for this Hire Date.  Cannot add another.</font></td>
	</tr>
<?
			} elseif($hire_check_count >= 1){
?>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Employee has an active Hire Date entry for this date.  Cannot add an unexcused absence.</font></td>
	</tr>
<?
			} else {
				// if we are at this point, the employee qualifies for an unexcused absence entry.
?>
<form name="add_entry" action="unexcused_absent.php" method="post">
<input type="hidden" name="employee" value="<? echo $employee; ?>">
<input type="hidden" name="hire" value="<? echo $hire; ?>">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Reason:&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
		<td><select name="reason"><option value="">Select A Reason</option>
<?
	$sql = "SELECT ROW_ID, REASON
			FROM EMPLOYEE_EXCUSE
			ORDER BY ROW_ID";
	$distinct_list = ociparse($bniconn, $sql);
	ociexecute($distinct_list);
	while(ocifetch($distinct_list)){
?>
							<option value="<? echo ociresult($distinct_list, "ROW_ID"); ?>"><? echo ociresult($distinct_list, "ROW_ID")." - ".ociresult($distinct_list, "REASON"); ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Add Absentee Notice"><hr></td>
	</tr>
</form>
<?
			}
		}
?>
</table>
<?
	}
	include("pow_footer.php");





function SendMailAndUpdate($mailID, $employee, $rfconn, $bniconn){
	include 'class.ezpdf.php';
	$pdf = new Cezpdf('letter');
	$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
	$pdf->ezSetMargins(10,10,10,10);
	$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
	$pdf->setFontFamily('Helvetica.afm', $tmp);
    $pdf->ezStartPageNumbers(310, 60, 10);


	
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = '".$mailID."'";
	$email = ociparse($rfconn, $sql);
	ociexecute($email);
	ocifetch($email);

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
	$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
	$mailheaders .= "X-Mailer: PHP4\r\n";
	$mailheaders .= "X-Priority: 3\r\n";
	$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

	$mailSubject = ociresult($email, "SUBJECT");

	$sql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME
			FROM EMPLOYEE
			WHERE EMPLOYEE_ID = '".$employee."'";
	$emp = ociparse($bniconn, $sql);
	ociexecute($emp);
	ocifetch($emp);
	$empname = ociresult($emp, "EMPLOYEE_NAME");

	$mailSubject = str_replace("_0_", ociresult($emp, "EMPLOYEE_ID")." - ".ociresult($emp, "EMPLOYEE_NAME"), $mailSubject);

	$body = ociresult($email, "NARRATIVE");
	$Content="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
	$Content.="\r\n";
	$Content.= $body;
	$Content.="\r\n";

	if($mailID == "UNEX_ABSENT_WARN"){
		$pdf->addJpegFromFile('ChapterAEmployeeLetter.jpg', 0, 0, 620, 800);
		$pdf->ezSetY(568);
		$pdf->ezText($employee." - ".$empname, 10, array('aleft'=>100));
		$pdf->ezSetY(682);
		$pdf->ezText(date('m/d/Y'), 10, array('aleft'=>100));
		$pdfcode = $pdf->ezOutput();
		$attach=chunk_split(base64_encode($pdfcode));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"AbsentLetter.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}
	if($mailID == "UNEX_ABSENT_CASB"){
		$pdf->addJpegFromFile('ChapterBEmployeeLetter.jpg', 0, 0, 620, 800);
		$pdf->ezSetY(567);
		$pdf->ezText($employee." - ".$empname, 10, array('aleft'=>100));
		$pdf->ezSetY(681);
		$pdf->ezText(date('m/d/Y'), 10, array('aleft'=>100));
		$pdfcode = $pdf->ezOutput();
		$attach=chunk_split(base64_encode($pdfcode));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"AbsentLetter.pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";
	}

	if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
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
					'INSTANT',
					SYSDATE,
					'EMAIL',
					'".$mailID."',
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
