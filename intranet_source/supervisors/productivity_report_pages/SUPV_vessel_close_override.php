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

  $conn = ora_logon("SAG_Owner@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
  }
  $cursor = ora_open($conn);

  $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $rf_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($rf_conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($rf_conn));
    printf("Please try later!");
    exit;
  }
  $ED_cursor = ora_open($rf_conn);

  
  $user_type = $userdata['user_type'];
  $user_types = split("-", $user_type);

	if((array_search('DIRE', $user_types) === FALSE || array_search('SUPV', $user_types) === FALSE) && array_search('ROOT', $user_types) === FALSE && $user != "ddonofrio") {
		printf("Access Denied");
	    include("pow_footer.php");
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	$opensuper1 = $HTTP_POST_VARS['opensuper1'];
	$opensuper2 = $HTTP_POST_VARS['opensuper2'];
	$opensuper3 = $HTTP_POST_VARS['opensuper3'];
	$opensuper4 = $HTTP_POST_VARS['opensuper4'];
	$opensuper5 = $HTTP_POST_VARS['opensuper5'];

	$reopen_list = "";

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Vessel Re-Opening
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="vessel_change" action="SUPV_vessel_close_override.php" method="post">
		<td colspan="6" align="center">Vessel:  <select name="vessel" onchange="document.vessel_change.submit(this.form)"><option value="">Please Select a Vessel</option>
<?
	$sql = "SELECT * FROM SUPER_VESSEL_CLOSE SVC, VESSEL_PROFILE VP WHERE SVC.VESSEL = VP.LR_NUM ORDER BY VESSEL DESC";
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

// Now that we do all this info, we'll do the "if button is pressed" portion
		if($submit == "Re-Open Selected Commodities"){
			if($opensuper1 == "reopen"){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER1 = '', TIME_ENTERED1 = '', TIME_COMPLETE1 = '', PROD_REPORT_RUN = 'N' WHERE VESSEL = '".$vessel."'"; 
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$reopen_list .= "\r\n"."Commodity #".$row['COMMODITY1']." (originally closed by ".$row['SUPER1'].")";
				$row['SUPER1'] = "";
			}
			if($opensuper2 == "reopen"){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER2 = '', TIME_ENTERED2 = '', TIME_COMPLETE2 = '', PROD_REPORT_RUN = 'N' WHERE VESSEL = '".$vessel."'"; 
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$reopen_list .= "\r\n"."Commodity #".$row['COMMODITY2']." (originally closed by ".$row['SUPER2'].")";
				$row['SUPER2'] = "";
			}
			if($opensuper3 == "reopen"){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER3 = '', TIME_ENTERED3 = '', TIME_COMPLETE3 = '', PROD_REPORT_RUN = 'N' WHERE VESSEL = '".$vessel."'"; 
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$reopen_list .= "\r\n"."Commodity #".$row['COMMODITY3']." (originally closed by ".$row['SUPER3'].")";
				$row['SUPER3'] = "";
			}
			if($opensuper4 == "reopen"){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER4 = '', TIME_ENTERED4 = '', TIME_COMPLETE4 = '', PROD_REPORT_RUN = 'N' WHERE VESSEL = '".$vessel."'"; 
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$reopen_list .= "\r\n"."Commodity #".$row['COMMODITY4']." (originally closed by ".$row['SUPER4'].")";
				$row['SUPER4'] = "";
			}
			if($opensuper5 == "reopen"){
				$sql = "UPDATE SUPER_VESSEL_CLOSE SET SUPER5 = '', TIME_ENTERED5 = '', TIME_COMPLETE5 = '', PROD_REPORT_RUN = 'N' WHERE VESSEL = '".$vessel."'"; 
				ora_parse($cursor, $sql);
				ora_exec($cursor);

				$reopen_list .= "\r\n"."Commodity #".$row['COMMODITY5']." (originally closed by ".$row['SUPER5'].")";
				$row['SUPER5'] = "";
			}

			if($reopen_list != ""){
//				$mailto .= "ddonofrio@port.state.de.us,wstans@port.state.de.us,fvignuli@port.state.de.us,OPSSupervisors@port.state.de.us,jjaffe@port.state.de.us";
//				$mailsubject = "Vessel #".$vessel." Edit";
//				$body = $user." has reopened Vessel #".$vessel." for corrections.";
//				$body = $body."\r\n".$reopen_list;
//				$body = $body."\r\n\r\nThe updated productivity report will be emailed.";
//				$body .= "\r\n";
/*				if($row['PROD_REPORT_RUN'] == 'Y'){
					$body .= "\r\nThe productivity report for this vessel will be re-run; please disregard the previously sent report.\r\n";
				} */
//				$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
//				$mailheaders .= "CC: " . "Martym@port.state.de.us,ltreut@port.state.de.us,cfoster@port.state.de.us,gbailey@port.state.de.us,ithomas@port.state.de.us";
/*				if($row['PROD_REPORT_RUN'] == 'Y'){
					$mailheaders .= ",SeniorManagers@port.state.de.us\r\n";
				} else {
					$mailheaders .= "\r\n";
				} */
//				$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us\r\n";

				$sql = "SELECT * FROM EMAIL_DISTRIBUTION
						WHERE EMAILID = 'SUPVVESCLSOVRRIDE'";
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
				$mailSubject = str_replace("_0_", $vessel, $mailSubject);

				$body = $email_row['NARRATIVE'];
				$body = str_replace("_1_", $user, $body);
				$body = str_replace("_2_", $vessel, $body);


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
								'SUPVVESCLSOVRRIDE',
								SYSDATE,
								'COMPLETED',
								'".$mailTO."',
								'".$email_row['CC']."',
								'".$email_row['BCC']."',
								'".substr($body, 0, 2000)."')";
					ora_parse($ED_cursor, $sql);
					ora_exec($ED_cursor);
				}

				//mail($mailto, $mailsubject, $body, $mailheaders);
			}
		}

?>
	<tr>
	<form name="update_closing" action="SUPV_vessel_close_override.php" method="post">
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
		<td width="16%"><font size="2" face="Verdana">Commodity #</font></td>
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
	<tr>
		<td width="16%"><b>Closed?</b></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER1'] != ""){ echo $row['SUPER1']; } else { if($row['COMMODITY1'] != ""){ ?> No <? } else { ?>N/A<? } } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER2'] != ""){ echo $row['SUPER2']; } else { if($row['COMMODITY2'] != ""){ ?> No <? } else { ?>N/A<? } } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER3'] != ""){ echo $row['SUPER3']; } else { if($row['COMMODITY3'] != ""){ ?> No <? } else { ?>N/A<? } } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER4'] != ""){ echo $row['SUPER4']; } else { if($row['COMMODITY4'] != ""){ ?> No <? } else { ?>N/A<? } } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER5'] != ""){ echo $row['SUPER5']; } else { if($row['COMMODITY5'] != ""){ ?> No <? } else { ?>N/A<? } } ?></font></td>
	</tr>
	<tr>
		<td width="16%"><font size="2" face="Verdana">Close Date:</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER1'] != ""){ echo $prep_row['DATE1']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER2'] != ""){ echo $prep_row['DATE2']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER3'] != ""){ echo $prep_row['DATE3']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER4'] != ""){ echo $prep_row['DATE4']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER5'] != ""){ echo $prep_row['DATE5']; } else { echo "---"; } ?></font></td>
	</tr>
	<tr>
		<td width="16%"><font size="2" face="Verdana">Close Time:</font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER1'] != ""){ echo $prep_row['TIME1']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER2'] != ""){ echo $prep_row['TIME2']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER3'] != ""){ echo $prep_row['TIME3']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER4'] != ""){ echo $prep_row['TIME4']; } else { echo "---"; } ?></font></td>
		<td width="16%"><font size="2" face="Verdana"><? if($row['SUPER5'] != ""){ echo $prep_row['TIME5']; } else { echo "---"; } ?></font></td>
	</tr>
	<tr>
		<td colspan="6"><hr>&nbsp;</td>
	</tr>
	<tr>
		<td width="16%"><b>Re-Open?</b></td>
		<td width="16%"><font size="2" face="Verdana"><input type="checkbox" name="opensuper1" value="reopen"<? if($row['SUPER1'] == ""){ ?> disabled <? } ?>></font></td>
		<td width="16%"><font size="2" face="Verdana"><input type="checkbox" name="opensuper2" value="reopen"<? if($row['SUPER2'] == ""){ ?> disabled <? } ?>></font></td>
		<td width="16%"><font size="2" face="Verdana"><input type="checkbox" name="opensuper3" value="reopen"<? if($row['SUPER3'] == ""){ ?> disabled <? } ?>></font></td>
		<td width="16%"><font size="2" face="Verdana"><input type="checkbox" name="opensuper4" value="reopen"<? if($row['SUPER4'] == ""){ ?> disabled <? } ?>></font></td>
		<td width="16%"><font size="2" face="Verdana"><input type="checkbox" name="opensuper5" value="reopen"<? if($row['SUPER5'] == ""){ ?> disabled <? } ?>></font></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input type="submit" name="submit" value="Re-Open Selected Commodities"></td>
	</form>
	</tr>
<?
	}
?>
</table>

<? include("pow_footer.php"); ?>
