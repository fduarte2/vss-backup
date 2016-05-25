<?
/* Adam Walter, June 2006.  This page allows Supervisors to enter a vessel,
*  Completion time, date; vessel will be marked as "closed", and
*  emails sent out accordingly.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Vessel Information Entry";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Supervisors system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];

  $today = date('m/d/Y');
  $today_rawtime = time();
  $ora_now = date('m/d/Y H:m');
  $now_for_overdue = date('m/d/Y h:m a');

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($rf_conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);

  $conn = ora_logon("SAG_Owner@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $cursor = ora_open($conn);
  $vessel_cursor = ora_open($conn);
  $commodity_name_cursor = ora_open($conn);
/*
  $LCSconn = ora_logon("SAG_Owner@BNI", "SAG");
  if($LCSconn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($LCSconn));
			printf("</body></html>");
			exit;
  }
  $LCS_cursor = ora_open($LCSconn);
*/

// this ranks among the most ugly methods I've ever used (well, this and OPS_..._.php in the same directory), but it works.
  $vessel = $HTTP_POST_VARS['vessel'];

// most of the variables
  $date1 = $HTTP_POST_VARS['date1'];
  $date_array1 = split("/", $date1);
  $date2 = $HTTP_POST_VARS['date2'];
  $date_array2 = split("/", $date2);
  $date3 = $HTTP_POST_VARS['date3'];
  $date_array3 = split("/", $date3);
  $date4 = $HTTP_POST_VARS['date4'];
  $date_array4 = split("/", $date4);
  $date5 = $HTTP_POST_VARS['date5'];
  $date_array5 = split("/", $date5);
  $time1 = $HTTP_POST_VARS['time1'];
  $time_array1 = split(":", $time1);
  $time2 = $HTTP_POST_VARS['time2'];
  $time_array2 = split(":", $time2);
  $time3 = $HTTP_POST_VARS['time3'];
  $time_array3 = split(":", $time3);
  $time4 = $HTTP_POST_VARS['time4'];
  $time_array4 = split(":", $time4);
  $time5 = $HTTP_POST_VARS['time5'];
  $time_array5 = split(":", $time5);
  $ampm1 = $HTTP_POST_VARS['ampm1'];
  $ampm2 = $HTTP_POST_VARS['ampm2'];
  $ampm3 = $HTTP_POST_VARS['ampm3'];
  $ampm4 = $HTTP_POST_VARS['ampm4'];
  $ampm5 = $HTTP_POST_VARS['ampm5'];

// check if they entered anything at all  
  if(trim($date1) != "" && $date1 != "MM/DD/YYYY"){
	  $super1 = $user;
  }
  if(trim($date2) != "" && $date2 != "MM/DD/YYYY"){
	  $super2 = $user;
  }
  if(trim($date3) != "" && $date3 != "MM/DD/YYYY"){
	  $super3 = $user;
  }
  if(trim($date4) != "" && $date4 != "MM/DD/YYYY"){
	  $super4 = $user;
  }
  if(trim($date5) != "" && $date5 != "MM/DD/YYYY"){
	  $super5 = $user;
  }

// if vessel exists, get the time of OPS's vessel entry

  $submit = $HTTP_POST_VARS['submit'];

  $error = "";

// for each of the 5 possible entries, check if data is valid.  first that raw input is not of bad form (like, letters where numbers should be), then arithmetic checks.
// Only done if data is attmpeted to be entered
	if($submit == "Close Selected Commodities"){
		$validtime1 = is_numeric($time_array1[0]) && is_numeric($time_array1[1]) && $time_array1[0] > 0 && $time_array1[0] < 13 && $time_array1[1] >= 0 && $time_array1[1] < 60;
		$validdate1 = is_numeric($date_array1[0]) && is_numeric($date_array1[1]) && is_numeric($date_array1[2]) && $date_array1[0] < 13 && $date_array1[1] < 32;
		$valid1 = ($super1 == "") || ($validtime1 && $validdate1);
		if(!$valid1){
		  $error .= "<font color=#ff0000>Date / Time format invalid for first commodity.  Please re-enter.<BR></font>";
		}
		if($time_array1[0] == 12 && $ampm1 == "AM"){
		  $time_array1[0] = 0;
		}
		if($time_array1[0] != 12 && $ampm1 == "PM"){
		  $time_array1[0] += 12;
		}
		$completion1 = mktime($time_array1[0], $time_array1[1], 0, $date_array1[0], $date_array1[1], $date_array1[2]);
		if($completion1 > $today_rawtime){
			$error .= "<font color=#ff0000>Cannot enter a completion date in the future for first commodity.  Please re-enter.<BR></font>";
			$valid1 = FALSE;
		}

		$validtime2 = is_numeric($time_array2[0]) && is_numeric($time_array2[1]) && $time_array2[0] > 0 && $time_array2[0] < 13 && $time_array2[1] >= 0 && $time_array2[1] < 60;
		$validdate2 = is_numeric($date_array2[0]) && is_numeric($date_array2[1]) && is_numeric($date_array2[2]) && $date_array2[0] < 13 && $date_array2[1] < 32;
		$valid2 = ($super2 == "") || ($validtime2 && $validdate2);
		if(!$valid2){
		  $error .= "<font color=#ff0000>Date / Time format invalid for second commodity.  Please re-enter.<BR></font>";
		}
		if($time_array2[0] == 12 && $ampm2 == "AM"){
		  $time_array2[0] = 0;
		}
		if($time_array2[0] != 12 && $ampm2 == "PM"){
		  $time_array2[0] += 12;
		}
		$completion2 = mktime($time_array2[0], $time_array2[1], 0, $date_array2[0], $date_array2[1], $date_array2[2]);
		if($completion2 > $today_rawtime){
			$error .= "<font color=#ff0000>Cannot enter a completion date in the future for second commodity.  Please re-enter.<BR></font>";
			$valid2 = FALSE;
		}

		$validtime3 = is_numeric($time_array3[0]) && is_numeric($time_array3[1]) && $time_array3[0] > 0 && $time_array3[0] < 13 && $time_array3[1] >= 0 && $time_array3[1] < 60;
		$validdate3 = is_numeric($date_array3[0]) && is_numeric($date_array3[1]) && is_numeric($date_array3[2]) && $date_array3[0] < 13 && $date_array3[1] < 32;
		$valid3 = ($super3 == "") || ($validtime3 && $validdate3);
		if(!$valid3){
		  $error .= "<font color=#ff0000>Date / Time format invalid for third commodity.  Please re-enter.<BR></font>";
		}
		if($time_array3[0] == 12 && $ampm3 == "AM"){
		  $time_array3[0] = 0;
		}
		if($time_array3[0] != 12 && $ampm3 == "PM"){
		  $time_array3[0] += 12;
		}
		$completion3 = mktime($time_array3[0], $time_array3[1], 0, $date_array3[0], $date_array3[1], $date_array3[2]);
		if($completion3 > $today_rawtime){
			$error .= "<font color=#ff0000>Cannot enter a completion date in the future for third commodity.  Please re-enter.<BR></font>";
			$valid3 = FALSE;
		}

		$validtime4 = is_numeric($time_array4[0]) && is_numeric($time_array4[1]) && $time_array4[0] > 0 && $time_array4[0] < 13 && $time_array4[1] >= 0 && $time_array4[1] < 60;
		$validdate4 = is_numeric($date_array4[0]) && is_numeric($date_array4[1]) && is_numeric($date_array4[2]) && $date_array4[0] < 13 && $date_array4[1] < 32;
		$valid4 = ($super4 == "") || ($validtime4 && $validdate4);
		if(!$valid4){
		  $error .= "<font color=#ff0000>Date / Time format invalid for fourth commodity.  Please re-enter.<BR></font>";
		}
		if($time_array4[0] == 12 && $ampm4 == "AM"){
		  $time_array4[0] = 0;
		}
		if($time_array4[0] != 12 && $ampm4 == "PM"){
		  $time_array4[0] += 12;
		}
		$completion4 = mktime($time_array4[0], $time_array4[1], 0, $date_array4[0], $date_array4[1], $date_array4[2]);
		if($completion4 > $today_rawtime){
			$error .= "<font color=#ff0000>Cannot enter a completion date in the future for fourth commodity.  Please re-enter.<BR></font>";
			$valid4 = FALSE;
		}

		$validtime5 = is_numeric($time_array5[0]) && is_numeric($time_array5[1]) && $time_array5[0] > 0 && $time_array5[0] < 13 && $time_array5[1] >= 0 && $time_array5[1] < 60;
		$validdate5 = is_numeric($date_array5[0]) && is_numeric($date_array5[1]) && is_numeric($date_array5[2]) && $date_array5[0] < 13 && $date_array5[1] < 32;
		$valid5 = ($super5 == "") || ($validtime5 && $validdate5);
		if(!$valid5){
		  $error .= "<font color=#ff0000>Date / Time format invalid for fifth commodity.  Please re-enter.<BR></font>";
		}
		if($time_array5[0] == 12 && $ampm5 == "AM"){
		  $time_array5[0] = 0;
		}
		if($time_array5[0] != 12 && $ampm5 == "PM"){
		  $time_array5[0] += 12;
		}
		$completion5 = mktime($time_array5[0], $time_array5[1], 0, $date_array5[0], $date_array5[1], $date_array5[2]);
		if($completion5 > $today_rawtime){
			$error .= "<font color=#ff0000>Cannot enter a completion date in the future for fifth commodity.  Please re-enter.<BR></font>";
			$valid5 = FALSE;
		}

		$allvalid = ($valid1 && $valid2 && $valid3 && $valid4 && $valid5);
		$any_entries = (($super1 != "") || ($super2 != "") || ($super3 != "") || ($super4 != "") || ($super5 != ""));

// note that we are still in the clause of "only doing this if submit is pressed"
		if($allvalid && $any_entries){

// set this variable such that SUPV's who enter time way later than they should will be ferreted out...
			$overdue_entry_body = "";
			$X_days_ago = time() - (60 * 60 * 24 * 1);  // 1 day, as indicated by the final multiplier

			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
			ora_parse($vessel_cursor, $sql);
			ora_exec($vessel_cursor);
			ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$vessel_name = $vessel_row['VESSEL_NAME'];

			$sql = "SELECT * FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
			ora_parse($vessel_cursor, $sql);
			ora_exec($vessel_cursor);
			ora_fetch_into($vessel_cursor, $vessel_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$commodity1 = $vessel_row['COMMODITY1'];
			$commodity2 = $vessel_row['COMMODITY2'];
			$commodity3 = $vessel_row['COMMODITY3'];
			$commodity4 = $vessel_row['COMMODITY4'];
			$commodity5 = $vessel_row['COMMODITY5'];

//			$mailto = "ddonofrio@port.state.de.us,OPSSupervisors@port.state.de.us,jjaffe@port.state.de.us";
//			$mailsubject = $vessel_name." Closing";
			$body = "";
//			$body = $user." has closed the following from the vessel ".$vessel_name.":\r\n";
//			$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
//			$mailheaders .= "CC: " . "Martym@port.state.de.us,ltreut@port.state.de.us,wstans@port.state.de.us,cfoster@port.state.de.us,fvignuli@port.state.de.us,gbailey@port.state.de.us\r\n";
//		    $mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,ithomas@port.state.de.us\r\n";

			if($super1 != ""){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER1 = '".$super1."', TIME_COMPLETE1 = to_date('".date('m/d/Y H:i', $completion1)."', 'MM/DD/YYYY HH24:MI'), TIME_ENTERED1 = to_date('".date('m/d/Y H:i')."', 'MM/DD/YYYY HH24:MI') WHERE VESSEL = '".$vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity1."'";
				ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name1 = $commodity_name_row['COMMODITY_NAME'];

				$body .= "Commodity ".$commodity_name1." completed discharge at ".date('m/d/y H:i', $completion1)."\r\nData entered by ".$user." at ".date('m/d/y h:i a').".";

				$body .= "  Data entry was done ".secondsToWords(time() - $completion1)." from discharge completion.\r\n\r\n";
				/*
				if($completion1 < $X_days_ago){
					$body .= "  The data entry is over 24 hours from discharge completion.   Please enter data within 24 hours for reports.\r\n\r\n";
				} else {
					$body .= "\r\n\r\n";
				}
				*/
			}
			if($super2 != ""){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER2 = '".$super2."', TIME_COMPLETE2 = to_date('".date('m/d/Y H:i', $completion2)."', 'MM/DD/YYYY HH24:MI'), TIME_ENTERED2 = to_date('".date('m/d/Y H:i')."', 'MM/DD/YYYY HH24:MI') WHERE VESSEL = '".$vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity2."'";
				ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name2 = $commodity_name_row['COMMODITY_NAME'];

				$body .= "Commodity ".$commodity_name2." completed discharge at ".date('m/d/y H:i', $completion2)."\r\nData entered by ".$user." at ".date('m/d/y h:i a').".";

				$body .= "  Data entry was done ".secondsToWords(time() - $completion2)." from discharge completion.\r\n\r\n";
				/*
				if($completion2 < $X_days_ago){
					$body .= "  The data entry is over 24 hours from discharge completion.   Please enter data within 24 hours for reports.\r\n\r\n";
				} else {
					$body .= "\r\n\r\n";
				}
				*/
			}
			if($super3 != ""){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER3 = '".$super3."', TIME_COMPLETE3 = to_date('".date('m/d/Y H:i', $completion3)."', 'MM/DD/YYYY HH24:MI'), TIME_ENTERED3 = to_date('".date('m/d/Y H:i')."', 'MM/DD/YYYY HH24:MI') WHERE VESSEL = '".$vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity3."'";
				ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name3 = $commodity_name_row['COMMODITY_NAME'];

				$body .= "Commodity ".$commodity_name3." completed discharge at ".date('m/d/y H:i', $completion3)."\r\nData entered by ".$user." at ".date('m/d/y h:i a').".";

				$body .= "  Data entry was done ".secondsToWords(time() - $completion3)." from discharge completion.\r\n\r\n";
				/*
				if($completion3 < $X_days_ago){
					$body .= "  The data entry is over 24 hours from discharge completion.   Please enter data within 24 hours for reports.\r\n\r\n";
				} else {
					$body .= "\r\n\r\n";
				}
				*/
			}
			if($super4 != ""){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER4 = '".$super4."', TIME_COMPLETE4 = to_date('".date('m/d/Y H:i', $completion4)."', 'MM/DD/YYYY HH24:MI'), TIME_ENTERED4 = to_date('".date('m/d/Y H:i')."', 'MM/DD/YYYY HH24:MI') WHERE VESSEL = '".$vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity4."'";
				ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name4 = $commodity_name_row['COMMODITY_NAME'];

				$body .= "Commodity ".$commodity_name4." completed discharge at ".date('m/d/y H:i', $completion4)."\r\nData entered by ".$user." at ".date('m/d/y h:i a').".";

				$body .= "  Data entry was done ".secondsToWords(time() - $completion4)." from discharge completion.\r\n\r\n";
				/*
				if($completion4 < $X_days_ago){
					$body .= "  The data entry is over 24 hours from discharge completion.   Please enter data within 24 hours for reports.\r\n\r\n";
				} else {
					$body .= "\r\n\r\n";
				}
				*/
			}
			if($super5 != ""){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER5 = '".$super5."', TIME_COMPLETE5 = to_date('".date('m/d/Y H:i', $completion5)."', 'MM/DD/YYYY HH24:MI'), TIME_ENTERED5 = to_date('".date('m/d/Y H:i')."', 'MM/DD/YYYY HH24:MI') WHERE VESSEL = '".$vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$commodity5."'";
				ora_parse($commodity_name_cursor, $sql);
				ora_exec($commodity_name_cursor);
				ora_fetch_into($commodity_name_cursor, $commodity_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$commodity_name5 = $commodity_name_row['COMMODITY_NAME'];

				$body .= "Commodity ".$commodity_name5." completed discharge at ".date('m/d/y H:i', $completion5)."\r\nData entered by ".$user." at ".date('m/d/y h:i a').".";

				$body .= "  Data entry was done ".secondsToWords(time() - $completion5)." from discharge completion.\r\n\r\n";
				/*
				if($completion5 < $X_days_ago){
					$body .= "  The data entry is over 24 hours from discharge completion.   Please enter data within 24 hours for reports.\r\n\r\n";
				} else {
					$body .= "\r\n\r\n";
				}
				*/
			}

			$sql = "SELECT * FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
			ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			if(($row['COMMODITY1'] == "" || ($row['SUPER1'] != "")) && ($row['COMMODITY2'] == "" || ($row['SUPER2'] != "")) && ($row['COMMODITY3'] == "" || ($row['SUPER3'] != "")) && ($row['COMMODITY4'] == "" || ($row['SUPER4'] != "")) && ($row['COMMODITY5'] == "" || ($row['SUPER5'] != ""))){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET PROD_REPORT_RUN = 'R' WHERE VESSEL = '".$vessel."'";
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				if(date('H') < 11){
					$body .= "Productivity Report will be emailed at 11AM today.\r\n";
				} else {
					$body .= "Productivity Report will be emailed at 11AM tomorrow.\r\n";
				}
			} else {
				$body .= "Supervisors note that the vessel has more than one commodity.   You must close all commodities before productivity will be computed.";
			}
			$body_replace = $body;

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'SUPVVSLCLOSE'";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
			ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

			$mailTO = $email_row['TO'];
			$mailheaders = "From: ".$email_row['FROM']."\r\n";

			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
			$mailSubject = $email_row['SUBJECT'];
			$mailSubject = str_replace("_0_", $vessel_name, $mailSubject);

			$body = $email_row['NARRATIVE'];
			$body = str_replace("_1_", $body_replace, $body);


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
							'SUPVVSLCLOSE',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($ED_cursor, $sql);
				ora_exec($ED_cursor);
			}

//			mail($mailto, $mailsubject, $body, $mailheaders);

/*			if($overdue_entry_body != ""){
				$overdue_mailto = "wstans@port.state.de.us";
				$overdue_mailsubject = $vessel_name."  overdue entry";
				$overdue_body = $user." closed ".$vessel_name." at ".$now_for_overdue;
				$overdue_body .= "\r\n".$overdue_entry_body;
				$overdue_body .= "\r\n\r\nNote that it is over 24 hours since the vessel actually completed:";
				$overdue_mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
				$overdue_mailheaders .= "CC: " . "fvignuli@port.state.de.us\r\n";
				$overdue_mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,ithomas@port.state.de.us\r\n";

				mail($overdue_mailto, $overdue_mailsubject, $overdue_body, $overdue_mailheaders);
			} */
		} else {
			echo $error;
		}
	}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Supervisor Vessel Closing Page
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="vessel_change" action="SUPV_vessel_closing.php" method="post">
		<td colspan="6" align="center">Vessel:  <select name="vessel" onchange="document.vessel_change.submit(this.form)"><option value="">Please Select a Vessel</option>
<?
	$sql = "SELECT * FROM SUPER_VESSEL_CLOSE SVC, VESSEL_PROFILE VP WHERE SVC.VESSEL = VP.LR_NUM AND SVC.PROD_REPORT_RUN = 'N' ORDER BY VESSEL DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['VESSEL']; ?>" <? if($row['VESSEL'] == $vessel){ ?> selected <? } ?>><? echo $row['VESSEL_NAME']; ?></option>
<?
	}
?>
							</select></td>
	</form>
	</tr>
	<tr>
		<td align="center" colspan="6"><font size="2" face="Verdana" color="#ff0000">Please note:  If the vessel you are attempting to close<BR>is not in the list, then Inventory<BR>has not yet entered the tonnage data for it.</td>
	</tr>
	<tr>
		<td>&nbsp;<br>&nbsp;</td>
	</tr>
<?
// this is some crazy logic here; grab all data for given vessel to display it...
	if($vessel != ""){
		$sql = "SELECT SVC.*, to_char(SVC.OPS_ENTRY_TIME, 'MM/DD/YYYY HH12:MI AM') THE_TIME FROM SUPER_VESSEL_CLOSE SVC WHERE VESSEL = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$sql = "SELECT to_char(TIME_COMPLETE1, 'MM/DD/YYYY') DATE1, to_char(TIME_COMPLETE2, 'MM/DD/YYYY') DATE2, to_char(TIME_COMPLETE3, 'MM/DD/YYYY') DATE3, to_char(TIME_COMPLETE4, 'MM/DD/YYYY') DATE4, to_char(TIME_COMPLETE5, 'MM/DD/YYYY') DATE5, to_char(TIME_COMPLETE1, 'HH12:MI AM') TIME1, to_char(TIME_COMPLETE2, 'HH12:MI AM') TIME2, to_char(TIME_COMPLETE3, 'HH12:MI AM') TIME3, to_char(TIME_COMPLETE4, 'HH12:MI AM') TIME4, to_char(TIME_COMPLETE5, 'HH12:MI AM') TIME5 FROM SUPER_VESSEL_CLOSE WHERE VESSEL = '".$vessel."'";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		ora_fetch_into($cursor, $prep_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
	<tr>
	<form name="update_closing" action="SUPV_vessel_closing.php" method="post">
	<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
		<td align="center" colspan="6">Values Entered by Inventory at <? echo $row['THE_TIME']; ?></td>
	</tr>
	<tr>
		<td width="16%">&nbsp;</td>
		<td width="16%"><font size="2" face="Verdana">Commodity 1</font></td>
		<td width="16%"><font size="2" face="Verdana">Commodity 2</font></td>
		<td width="16%"><font size="2" face="Verdana">Commodity 3</font></td>
		<td width="16%"><font size="2" face="Verdana">Commodity 4</font></td>
		<td width="16%"><font size="2" face="Verdana">Commodity 5</font></td>
	</tr>
	<tr>
		<td width="16%"><font size="2" face="Verdana"><a href="budget_group_codes.php" target="budget_group_codes.php">Commodity #</a></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['COMMODITY1'] == ""){ echo "N/A"; } else { echo $row['COMMODITY1']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['COMMODITY2'] == ""){ echo "N/A"; } else { echo $row['COMMODITY2']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['COMMODITY3'] == ""){ echo "N/A"; } else { echo $row['COMMODITY3']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['COMMODITY4'] == ""){ echo "N/A"; } else { echo $row['COMMODITY4']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['COMMODITY5'] == ""){ echo "N/A"; } else { echo $row['COMMODITY5']; } ?></font></td>
	</tr>
	<tr>
		<td width="16%"><font size="2" face="Verdana">Tonnage</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['TONNAGE1'] == ""){ echo "N/A"; } else { echo $row['TONNAGE1']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['TONNAGE2'] == ""){ echo "N/A"; } else { echo $row['TONNAGE2']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['TONNAGE3'] == ""){ echo "N/A"; } else { echo $row['TONNAGE3']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['TONNAGE4'] == ""){ echo "N/A"; } else { echo $row['TONNAGE4']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['TONNAGE5'] == ""){ echo "N/A"; } else { echo $row['TONNAGE5']; } ?></font></td>
	</tr>
	<tr>
		<td width="16%"><font size="2" face="Verdana">Units (Measurement)</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['QTY1'] == ""){ echo "N/A"; } else { echo $row['QTY1']; } ?>&nbsp;&nbsp;<? if($row['QTY1'] == ""){ echo "N/A"; } else { echo $row['MEASUREMENT1']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['QTY2'] == ""){ echo "N/A"; } else { echo $row['QTY2']; } ?>&nbsp;&nbsp;<? if($row['QTY2'] == ""){ echo "N/A"; } else { echo $row['MEASUREMENT2']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['QTY3'] == ""){ echo "N/A"; } else { echo $row['QTY3']; } ?>&nbsp;&nbsp;<? if($row['QTY3'] == ""){ echo "N/A"; } else { echo $row['MEASUREMENT3']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['QTY4'] == ""){ echo "N/A"; } else { echo $row['QTY4']; } ?>&nbsp;&nbsp;<? if($row['QTY4'] == ""){ echo "N/A"; } else { echo $row['MEASUREMENT4']; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['QTY5'] == ""){ echo "N/A"; } else { echo $row['QTY5']; } ?>&nbsp;&nbsp;<? if($row['QTY5'] == ""){ echo "N/A"; } else { echo $row['MEASUREMENT5']; } ?></font></td>
	</tr>
	<tr>
		<td colspan="6"><hr>&nbsp;</td>
	</tr>
<!-- and now comes the good part, if/thens in PHP to determie which columns are super-closable.
Defined as:  
If either a column has N/A data, show all greyed out
If a column is closed by another supervisor, show grey-ed out data that other Super entered
If a column has data and no closing but does have data, allow to be closed.
I feel this comment is necessary due to the ugliness of the following lines
!-->
	<tr>
		<td width="16%"><font size="2" face="Verdana">Closed by:</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER1'] != ""){ echo $row['SUPER1']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER2'] != ""){ echo $row['SUPER2']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER3'] != ""){ echo $row['SUPER3']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER4'] != ""){ echo $row['SUPER4']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER5'] != ""){ echo $row['SUPER5']; } else { echo "---"; } ?></font></td>
	</tr> 
	<tr>
		<td width="16%"><font size="2" face="Verdana">Close Date:</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER1'] != ""){ echo $prep_row['DATE1']; } else { ?><input type="text" name="date1" size="14" maxlength="10" value="MM/DD/YYYY" <? if($row['COMMODITY1'] == ""){ ?> disabled <? } echo ">"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER2'] != ""){ echo $prep_row['DATE2']; } else { ?><input type="text" name="date2" size="14" maxlength="10" value="MM/DD/YYYY" <? if($row['COMMODITY2'] == ""){ ?> disabled <? } echo ">"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER3'] != ""){ echo $prep_row['DATE3']; } else { ?><input type="text" name="date3" size="14" maxlength="10" value="MM/DD/YYYY" <? if($row['COMMODITY3'] == ""){ ?> disabled <? } echo ">"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER4'] != ""){ echo $prep_row['DATE4']; } else { ?><input type="text" name="date4" size="14" maxlength="10" value="MM/DD/YYYY" <? if($row['COMMODITY4'] == ""){ ?> disabled <? } echo ">"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER5'] != ""){ echo $prep_row['DATE5']; } else { ?><input type="text" name="date5" size="14" maxlength="10" value="MM/DD/YYYY" <? if($row['COMMODITY5'] == ""){ ?> disabled <? } echo ">"; } ?></font></td>
	</tr>
	<tr>
		<td width="16%"><font size="2" face="Verdana">Close Time:</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER1'] != ""){ echo $prep_row['TIME1']; } else { ?><input type="text" name="time1" size="14" maxlength="10" value="HH:MM" <? if($row['COMMODITY1'] == ""){ ?> disabled <? } echo ">"; ?>&nbsp;&nbsp;<select name="ampm1"><option value="AM">AM</option><option value="PM">PM</option></select><? } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER2'] != ""){ echo $prep_row['TIME2']; } else { ?><input type="text" name="time2" size="14" maxlength="10" value="HH:MM" <? if($row['COMMODITY2'] == ""){ ?> disabled <? } echo ">"; ?>&nbsp;&nbsp;<select name="ampm2"><option value="AM">AM</option><option value="PM">PM</option></select><? } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER3'] != ""){ echo $prep_row['TIME3']; } else { ?><input type="text" name="time3" size="14" maxlength="10" value="HH:MM" <? if($row['COMMODITY3'] == ""){ ?> disabled <? } echo ">"; ?>&nbsp;&nbsp;<select name="ampm3"><option value="AM">AM</option><option value="PM">PM</option></select><? } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER4'] != ""){ echo $prep_row['TIME4']; } else { ?><input type="text" name="time4" size="14" maxlength="10" value="HH:MM" <? if($row['COMMODITY4'] == ""){ ?> disabled <? } echo ">"; ?>&nbsp;&nbsp;<select name="ampm4"><option value="AM">AM</option><option value="PM">PM</option></select><? } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER5'] != ""){ echo $prep_row['TIME5']; } else { ?><input type="text" name="time5" size="14" maxlength="10" value="HH:MM" <? if($row['COMMODITY5'] == ""){ ?> disabled <? } echo ">"; ?>&nbsp;&nbsp;<select name="ampm5"><option value="AM">AM</option><option value="PM">PM</option></select><? } ?></font></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Close Selected Commodities"></td>
	</form>
	</tr>
<?
	}
?>
</table>



<? include("pow_footer.php");?>


<?

function secondsToWords($seconds)
{
    $ret = "";

	$days = intval(intval($seconds) / 86400);
    if($days > 0)
    {
        $ret .= "$days days, ";
    }
    $hours = bcmod((intval($seconds) / 3600), 24);
    if($hours > 0)
    {
        $ret .= "$hours hours ";
    }
	/*
    $minutes = bcmod((intval($seconds) / 60),60);
    if($hours > 0 || $minutes > 0)
    {
        $ret .= "$minutes minutes ";
    }
  
    $seconds = bcmod(intval($seconds),60);
    $ret .= "$seconds seconds";
	*/
	if($ret == ""){
		$ret = "Within 1 hour";
	}
    return $ret;
}
?>
