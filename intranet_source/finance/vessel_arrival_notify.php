<?
/*
*		Adam Walter, Sept 2015.
*
*		Page to notify finance of an arriving barge
*
*		In case anyone is wondering why time and date are 2 different DB fields,
*		It's because they are 2 separate entry fields, each of which can
*		be null while the other is filled in.  It's easier this way
*		than to have a tiered criteria and conditional SQL clauses
*********************************************************************************/

	// All POW files need this session file included
	include("pow_session.php");

	// Define some vars for the skeleton page
	$title = "Vessel Entry";
	$area_type = "ROOT";

	// Provides header / leftnav
	include("pow_header.php");
/*	if($access_denied){
		printf("Access Denied from INVE system");
		include("pow_footer.php");
		exit;
	}
*/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI"); echo "<font color=\"#000000\" size=\"1\">BNI LIVE DB</font><br>";
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST"); echo "<font color=\"#FF0000\" size=\"5\">BNI TEST DB</font><br>";
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF"); echo "<font color=\"#000000\" size=\"1\">RF LIVE DB</font><br>";
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST"); echo "<font color=\"#FF0000\" size=\"5\">RF TEST DB</font><br>";
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($bniconn));
		exit;
	}


	$submit1 = str_replace("'", "`", $HTTP_POST_VARS['submit1']);
	$date_arrived = str_replace("'", "`", $HTTP_POST_VARS['date_arrived']);
	$time_arrived = str_replace("'", "`", $HTTP_POST_VARS['time_arrived']);
	$vesname = str_replace("'", "`", $HTTP_POST_VARS['vesname']);
	$vesname = str_replace("\\", "", $vesname);
	$date_departed = str_replace("'", "`", $HTTP_POST_VARS['date_departed']);
	$time_departed = str_replace("'", "`", $HTTP_POST_VARS['time_departed']);
	$company = str_replace("'", "`", $HTTP_POST_VARS['company']);
	$contact = str_replace("'", "`", $HTTP_POST_VARS['contact']);
	$telephone = str_replace("'", "`", $HTTP_POST_VARS['telephone']);
	$comments = str_replace("'", "`", $HTTP_POST_VARS['comments']);
	$length = str_replace("'", "`", $HTTP_POST_VARS['length']);
	$berth = str_replace("'", "`", $HTTP_POST_VARS['berth']);
	$lines_in = str_replace("'", "`", $HTTP_POST_VARS['lines_in']);
	$lines_out = str_replace("'", "`", $HTTP_POST_VARS['lines_out']);
	$company_contact = str_replace("'", "`", $HTTP_POST_VARS['company_contact']);
	$company_telephone = str_replace("'", "`", $HTTP_POST_VARS['company_telephone']);
	$ID = $HTTP_POST_VARS['ID'];
	if($ID == ""){
		$ID = $HTTP_GET_VARS['ID'];
	}

//	echo "tele:  ".$telephone."   contact:  ".$contact."     comp-tele:  ".$company_telephone."     comp-contact:  ".$company_contact."<br>";

	if($submit1 == "Reset"){
		$submit1 = "";
		$ID = "";
		$date_arrived = "";
		$vesname = "";
		$date_departed = "";
		$time_arrived = "";
		$time_departed = "";
		$company = "";
		$contact = "";
		$telephone = "";
		$comments = "";
		$length = "";
		$berth = "";
		$lines_in = "";
		$lines_out = "";
	}

	if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $date_arrived) && $date_arrived != ""){
		echo "<font color=\"#FF0000\">Date Arrived must be in MM/DD/YYYY format (was entered as ".$date_arrived.").</font><br>";
		$submit1 = "";
	}
	if(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $date_departed) && $date_departed != ""){
		echo "<font color=\"#FF0000\">Date Departed must be in MM/DD/YYYY format (was entered as ".$date_departed.").</font><br>";
		$submit1 = "";
	}
	if(!ereg("^^([0-2]{0,1}[0-9]{1}):([0-5][0-9])$", $time_arrived) && $time_arrived != ""){
		echo "<font color=\"#FF0000\">Time Arrived must be in HH24:MI format (was entered as ".$time_arrived.").</font><br>";
		$submit1 = "";
	}
	if(!ereg("^^([0-2]{0,1}[0-9]{1}):([0-5][0-9])$", $time_departed) && $time_departed != ""){
		echo "<font color=\"#FF0000\">Time Departed must be in HH24:MI format (was entered as ".$time_departed.").</font><br>";
		$submit1 = "";
	}
	if($submit != "" && $vesname == ""){
		echo "<font color=\"#FF0000\">Vessel Name is Required.</font><br>";
		$submit1 = "";
	}
	if($telephone != "" && $company_telephone != "" && $company_telephone != $telephone){
		echo "<font color=\"#FF0000\">The telephone entries did not match.</font><br>";
		$submit1 = "";
	}
	if($contact != "" && $company_contact != "" && $company_contact != $contact){
		echo "<font color=\"#FF0000\">The contact entries did not match.</font><br>";
		$submit1 = "";
	}
	if($telephone == "" && $company_telephone != ""){
		$telephone = $company_telephone;
	}
	if($contact == "" && $company_contact != ""){
		$contact = $company_contact;
	}


	if($submit1 == "Save"){
		if($ID == ""){
			// new Entry
			$sql = "SELECT MAX(ENTRY_ID) + 1 THE_MAX
					FROM VESSEL_NOTIFY_FINANCE";
			$maxnum = ociparse($bniconn, $sql);
			ociexecute($maxnum);
			ocifetch($maxnum);
			$ID = ociresult($maxnum, "THE_MAX");
			if($ID == ""){
				$ID = 1;
			}

			$sql = "INSERT INTO VESSEL_NOTIFY_FINANCE
						(ENTRY_ID,
						DATE_ARRIVE,
						TIME_ARRIVE,
						DATE_DEPART,
						TIME_DEPART,
						VESSEL_NAME,
						COMMENTS,
						CONTACT,
						COMPANY,
						TELEPHONE,
						BARGE_LENGTH,
						BERTH_NUM,
						DSPC_LINES_IN,
						DSPC_LINES_OUT,
						USERNAME,
						ENTRY_TIME)
					VALUES
						('".$ID."',
						TO_DATE('".$date_arrived."', 'MM/DD/YYYY'),
						TO_DATE('".$time_arrived."', 'HH24:MI'),
						TO_DATE('".$date_departed."', 'MM/DD/YYYY'),
						TO_DATE('".$time_departed."', 'HH24:MI'),
						'".$vesname."',
						'".$comments."',
						'".$contact."',
						'".$company."',
						'".$telephone."',
						'".$length."',
						'".$berth."',
						'".$lines_in."',
						'".$lines_out."',
						'".$user."',
						SYSDATE)";
			$update = ociparse($bniconn, $sql);
			ociexecute($update);
		} else {
			// updating entry
			$sql = "UPDATE VESSEL_NOTIFY_FINANCE
					SET
						DATE_ARRIVE = TO_DATE('".$date_arrived."', 'MM/DD/YYYY'),
						TIME_ARRIVE = TO_DATE('".$time_arrived."', 'HH24:MI'),
						DATE_DEPART = TO_DATE('".$date_departed."', 'MM/DD/YYYY'),
						TIME_DEPART = TO_DATE('".$time_departed."', 'HH24:MI'),
						VESSEL_NAME = '".$vesname."',
						COMMENTS = '".$comments."',
						CONTACT = '".$contact."',
						COMPANY = '".$company."',
						TELEPHONE = '".$telephone."',
						BARGE_LENGTH = '".$length."',
						BERTH_NUM = '".$berth."',
						DSPC_LINES_IN = '".$lines_in."',
						DSPC_LINES_OUT = '".$lines_out."',
						USERNAME = '".$user."',
						ENTRY_TIME = SYSDATE
					WHERE ENTRY_ID = '".$ID."'";
//			echo $sql."<br>";
			$update = ociparse($bniconn, $sql);
			ociexecute($update);
		}

		$sql = "INSERT INTO VESSEL_NOTIFY_FINANCE_HIST
					(ENTRY_ID,
					DATE_ARRIVE,
					TIME_ARRIVE,
					DATE_DEPART,
					TIME_DEPART,
					VESSEL_NAME,
					COMMENTS,
					CONTACT,
					COMPANY,
					TELEPHONE,
					BARGE_LENGTH,
					BERTH_NUM,
					DSPC_LINES_IN,
					DSPC_LINES_OUT,
					USERNAME,
					ENTRY_TIME)
				VALUES
					('".$ID."',
					TO_DATE('".$date_arrived."', 'MM/DD/YYYY'),
					TO_DATE('".$time_arrived."', 'HH24:MI'),
					TO_DATE('".$date_departed."', 'MM/DD/YYYY'),
					TO_DATE('".$time_departed."', 'HH24:MI'),
					'".$vesname."',
					'".$comments."',
					'".$contact."',
					'".$company."',
					'".$telephone."',
					'".$length."',
					'".$berth."',
					'".$lines_in."',
					'".$lines_out."',
					'".$user."',
					SYSDATE)";
		$update = ociparse($bniconn, $sql);
		ociexecute($update);

		
		
		
		// table record is in.  Send email.
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'DOCKEDSHIPENTRY'";
		$email = ociparse($rfconn, $sql);
		ociexecute($email);
		ocifetch($email);

		$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
		if(ociresult($email, "TEST") == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: ithomas@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us\r\n";
		} else {
			$mailTO = ociresult($email, "TO");
			if(ociresult($email, "CC") != ""){
				$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
			}
			if(ociresult($email, "BCC") != ""){
				$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
			}
		}

		$mailSubject = ociresult($email, "SUBJECT");
		$body = ociresult($email, "NARRATIVE");
		$body = str_replace("_0_", "Vessel Name:  ".$vesname."\r\nArrived:  ".$date_arrived." ".$time_arrived."\r\nDeparted:  ".$date_departed." ".$time_departed."\r\n", $body);
		$body = str_replace("_1_", "Company:  ".$company."\r\nContact:  ".$contact."\r\nTelephone:  ".$telephone."\r\nComments:  ".$comments, $body);
		$body = str_replace("_2_", "Barge Length:  ".$length."\r\nBerth:  ".$berth."\r\nLines In:  ".$lines_in."\r\nLines Out:  ".$lines_out, $body);
		$body = str_replace("_br_", "\r\n", $body);

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
						'DOCKEDSHIPENTRY',
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


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barge Arrival Notification&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</font><font size="3" face="Verdana" color="#0066CC"><a href="barge_contacts.php">Add or Edit Companies</a>
</font>	    
	 </p>
      </td>
	</tr>
</table>
<?
	if($HTTP_GET_VARS['ID'] != ""){
		$sql = "SELECT ENTRY_ID, VESSEL_NAME, TO_CHAR(DATE_ARRIVE, 'MM/DD/YYYY') THE_ARV, TO_CHAR(DATE_DEPART, 'MM/DD/YYYY') THE_DEP, COMMENTS, NVL(COMPANY, 'None') CUR_COMP,
					TO_CHAR(TIME_ARRIVE, 'HH24:MI') TIME_ARV, TO_CHAR(TIME_DEPART, 'HH24:MI') TIME_DEP, COMPANY, CONTACT, TELEPHONE, BARGE_LENGTH, BERTH_NUM, DSPC_LINES_IN, DSPC_LINES_OUT
				FROM VESSEL_NOTIFY_FINANCE
				WHERE ENTRY_ID = '".$ID."'";
		$select = ociparse($bniconn, $sql);
		ociexecute($select);
		if(ocifetch($select)){
			$vesname = ociresult($select, "VESSEL_NAME");
			$date_arrived = ociresult($select, "THE_ARV");
			$date_departed = ociresult($select, "THE_DEP");
			$time_arrived = ociresult($select, "TIME_ARV");
			$time_departed = ociresult($select, "TIME_DEP");
			$company = ociresult($select, "COMPANY");
			$cur_company = ociresult($select, "CUR_COMP");
			$contact = ociresult($select, "CONTACT");
			$telephone = ociresult($select, "TELEPHONE");
			$comments = ociresult($select, "COMMENTS");
			$length = ociresult($select, "BARGE_LENGTH");
			$berth = ociresult($select, "BERTH_NUM");
			$lines_in = ociresult($select, "DSPC_LINES_IN");
			$lines_out = ociresult($select, "DSPC_LINES_OUT");

		}
	}
	if($ID != ""){
		echo "<font size=\"3\" color=\"#0000FF\"><b>Currently Editing:  ".$vesname."</b></font><br>";
	}
?>


<table border="0" cellpadding="4" cellspacing="0">
<form name="main_form" action="vessel_arrival_notify.php" method="post">
<input type="hidden" name="ID" value="<? echo $ID; ?>">
	<tr>
		<td align="left"><font size="2" face="Verdana">Barge Name:  </font></td>
		<td><input type="text" name="vesname" size="50" maxlength="50" value="<? echo $vesname; ?>"></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Company:  </font></td>
		<td><select name="company" onchange="document.main_form.submit(this.form)"><option value="">---</option>
<?
	$sql = "SELECT COMPANY_NAME FROM VESSEL_NOTIFY_COMPANIES ORDER BY COMPANY_NAME";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "COMPANY_NAME"); ?>"<? if(ociresult($short_term_data, "COMPANY_NAME") == $company){?> selected <?}?>><? echo ociresult($short_term_data, "COMPANY_NAME"); ?></option>
<?
	}
?>
			</select></td>
		<td align="left"><font size="2" face="Verdana">(Current Company:  </font></td>
		<td><font size="2" face="Verdana"><? echo $cur_company; ?>)</font></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Company-Contact:  </font></td>
		<td><select name="company_contact"><option value="">---</option>
<?
	$sql = "SELECT CONTACT_NAME FROM VESSEL_NOTIFY_COMPANY_CONTACTS WHERE COMPANY_NAME = '".$company."' ORDER BY CONTACT_NAME";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "CONTACT_NAME"); ?>"<? if(ociresult($short_term_data, "CONTACT_NAME") == $contact){?> selected <?}?>><? echo ociresult($short_term_data, "CONTACT_NAME"); ?></option>
<?
	}
?>
			</select></td>
		<td align="left"><font size="2" face="Verdana">Contact:  </font></td>
		<td><input type="text" name="contact" size="30" maxlength="30" value="<? echo $contact; ?>"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Company-Telephone (+ Area Code):  </font></td>
		<td><select name="company_telephone"><option value="">---</option>
<?
	$sql = "SELECT TELEPHONE FROM VESSEL_NOTIFY_COMPANY_CONTACTS WHERE COMPANY_NAME = '".$company."' ORDER BY TELEPHONE";
	$short_term_data = ociparse($bniconn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
					<option value="<? echo ociresult($short_term_data, "TELEPHONE"); ?>"<? if(ociresult($short_term_data, "TELEPHONE") == $telephone){?> selected <?}?>><? echo ociresult($short_term_data, "TELEPHONE"); ?></option>
<?
	}
?>
			</select></td>
		<td align="left"><font size="2" face="Verdana">Telephone (+ Area Code):  </font></td>
		<td><input type="text" name="telephone" size="20" maxlength="20" value="<? echo $telephone; ?>"></td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Barge Length:  </font></td>
		<td><input type="text" name="length" size="20" maxlength="20" value="<? echo $length; ?>"></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Berth:  </font></td>
		<td><input type="text" name="berth" size="5" maxlength="5" value="<? echo $berth; ?>"></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">DSPC Lines  </font></td>
		<td><font size="2" face="Verdana">In:  </font><select name="lines_in">
				<option value="Y">Y</option>
				<option value="N"<? if($lines_in == "N"){?> selected <?}?>>N</option>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<font size="2" face="Verdana">Out:  </font><select name="lines_out">
				<option value="Y">Y</option>
				<option value="N"<? if($lines_out == "N"){?> selected <?}?>>N</option>
			</select></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Date Arrived:  </font></td>
		<td><input type="text" name="date_arrived" size="10" maxlength="10" value="<? echo $date_arrived; ?>">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">Time:  </font>
			<input type="text" name="time_arrived" size="5" maxlength="5" value="<? echo $time_arrived; ?>"></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Date Departed:  </font></td>
		<td><input type="text" name="date_departed" size="10" maxlength="10" value="<? echo $date_departed; ?>">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana">Time:  </font>
			<input type="text" name="time_departed" size="5" maxlength="5" value="<? echo $time_departed; ?>"></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><font size="2" face="Verdana">Comments:  </font></td>
		<td colspan="3"><input type="text" name="comments" size="150" maxlength="1000" value="<? echo $comments; ?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit1" value="Save"></td>
		<td colspan="3"><input type="submit" name="submit1" value="Reset"></td>
	</tr>
</form>
</table>
<br><br>
<?
	if($ID != ""){
?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="13" align="center"><font size="2" face="Verdana"><b>History of entries for current Barge</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barge</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrived</b></font></td>
		<td><font size="2" face="Verdana"><b>Departed</b></font></td>
		<td><font size="2" face="Verdana"><b>Contact</b></font></td>
		<td><font size="2" face="Verdana"><b>Company</b></font></td>
		<td><font size="2" face="Verdana"><b>Telephone</b></font></td>
		<td><font size="2" face="Verdana"><b>Barge Length</b></font></td>
		<td><font size="2" face="Verdana"><b>Berth</b></font></td>
		<td><font size="2" face="Verdana"><b>Lines In (Y/N)</b></font></td>
		<td><font size="2" face="Verdana"><b>Lines Out (Y/N)</b></font></td>
		<td><font size="2" face="Verdana"><b>Comments</b></font></td>
		<td><font size="2" face="Verdana"><b>User</b></font></td>
		<td><font size="2" face="Verdana"><b>Entry Time</b></font></td>
	</tr>
<?
		$sql = "SELECT ENTRY_ID, VESSEL_NAME, TO_CHAR(DATE_ARRIVE, 'MM/DD/YYYY') THE_ARV, TO_CHAR(DATE_DEPART, 'MM/DD/YYYY') THE_DEP, COMMENTS, USERNAME, BARGE_LENGTH, BERTH_NUM, DSPC_LINES_IN, DSPC_LINES_OUT,
					TO_CHAR(TIME_ARRIVE, 'HH24:MI') TIME_ARV, TO_CHAR(TIME_DEPART, 'HH24:MI') TIME_DEP, COMPANY, CONTACT, TELEPHONE, TO_CHAR(ENTRY_TIME, 'MM/DD/YYYY HH24:MI') THE_ENT
				FROM VESSEL_NOTIFY_FINANCE_HIST
				WHERE ENTRY_ID = '".$ID."'
				ORDER BY ENTRY_TIME";
		$entries = ociparse($bniconn, $sql);
		ociexecute($entries);
		while(ocifetch($entries)){
?>
	<tr>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "VESSEL_NAME"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "THE_ARV")." ".ociresult($entries, "TIME_ARV"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "THE_DEP")." ".ociresult($entries, "TIME_DEP"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "CONTACT"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "COMPANY"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "TELEPHONE"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "BARGE_LENGTH"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "BERTH_NUM"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "DSPC_LINES_IN"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "DSPC_LINES_OUT"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "COMMENTS"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "USERNAME"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "THE_ENT"); ?></font></td>
	</tr>
<?
		}
?>
</table>
<br><br>
<?
	}
	if($ID == ""){
?>
<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="12" align="center"><font size="2" face="Verdana"><b>All Entered Barges' Current Values</b></font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barge</b></font></td>
		<td><font size="2" face="Verdana"><b>Arrived</b></font></td>
		<td><font size="2" face="Verdana"><b>Departed</b></font></td>
		<td><font size="2" face="Verdana"><b>Contact</b></font></td>
		<td><font size="2" face="Verdana"><b>Company</b></font></td>
		<td><font size="2" face="Verdana"><b>Telephone</b></font></td>
		<td><font size="2" face="Verdana"><b>Barge Length</b></font></td>
		<td><font size="2" face="Verdana"><b>Berth</b></font></td>
		<td><font size="2" face="Verdana"><b>Lines In (Y/N)</b></font></td>
		<td><font size="2" face="Verdana"><b>Lines Out (Y/N)</b></font></td>
		<td><font size="2" face="Verdana"><b>Comments</b></font></td>
		<td><font size="2" face="Verdana"><b>User</b></font></td>
	</tr>
<?
		$sql = "SELECT ENTRY_ID, VESSEL_NAME, TO_CHAR(DATE_ARRIVE, 'MM/DD/YYYY') THE_ARV, TO_CHAR(DATE_DEPART, 'MM/DD/YYYY') THE_DEP, COMMENTS, BARGE_LENGTH, BERTH_NUM, DSPC_LINES_IN, DSPC_LINES_OUT,
					TO_CHAR(TIME_ARRIVE, 'HH24:MI') TIME_ARV, TO_CHAR(TIME_DEPART, 'HH24:MI') TIME_DEP, COMPANY, CONTACT, TELEPHONE, USERNAME
				FROM VESSEL_NOTIFY_FINANCE
				ORDER BY ENTRY_ID";
		$entries = ociparse($bniconn, $sql);
		ociexecute($entries);
		while(ocifetch($entries)){
?>
	<tr>
		<td><font size="2" face="Verdana"><a href="vessel_arrival_notify.php?ID=<? echo ociresult($entries, "ENTRY_ID"); ?>"><? echo ociresult($entries, "VESSEL_NAME"); ?></a></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "THE_ARV")." ".ociresult($entries, "TIME_ARV"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "THE_DEP")." ".ociresult($entries, "TIME_DEP"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "CONTACT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "COMPANY"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "TELEPHONE"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "BARGE_LENGTH"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "BERTH_NUM"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "DSPC_LINES_IN"); ?></font></td>
		<td><font size="2" face="Verdana">&nbsp;<? echo ociresult($entries, "DSPC_LINES_OUT"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo str_replace("_br_", "<br>", ociresult($entries, "COMMENTS")); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($entries, "USERNAME"); ?></font></td>
	</tr>
<?
		}
?>
</table>
<?
	}
	include("pow_footer.php");