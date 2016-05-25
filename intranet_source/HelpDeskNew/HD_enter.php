<?
/*
*	Adam Walter, May 2013
*
*	A page to allow entry HD requests
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HD System";
  $area_type = "ROOT";

  // Provides header / leftnav
  include("pow_header.php");
/*  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }*/

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

//	$user = "ithomas"; // REMOVE ASAP

	if (trim($user) == "") {
		echo "Please login the Intranet!";
		exit;
	}

	$sql = "SELECT HELPDESK_ACCESS FROM INTRANET_USERS
			WHERE USERNAME = '".$user."'";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	$permission = ociresult($stid, "HELPDESK_ACCESS");

	$job_id = $HTTP_GET_VARS['ID'];
	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Save Entry"){
		$job_id = $HTTP_POST_VARS['job_id'];

		$userid = $HTTP_POST_VARS['userid'];
		$description = trim(str_replace("'", "`", $HTTP_POST_VARS['description']));
		$description = str_replace("\\", "", $description);
		$category = $HTTP_POST_VARS['category'];
		$priority = $HTTP_POST_VARS['priority'];
		$status = $HTTP_POST_VARS['status'];
		$ts_contact = $HTTP_POST_VARS['ts_contact'];

		$check = validate_save($description, $category, $priority, $bniconn);
		if($check == ""){
			// time to save.

			if($job_id == "New"){
				if($category != 21){
					$field = "JOBID";
					$value = "HELPDESK_SEQ.NEXTVAL";
				} else {
					$field = "TS_ID";
					$value = "HD_TS_ID_SEQ.NEXTVAL";
				}
				$sql = "SELECT HD_MASTERLIST_SEQ.NEXTVAL THE_NEXT FROM DUAL";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid);
				ocifetch($stid);
				$job_id = ociresult($stid, "THE_NEXT");

				// new job, prepare to insert
				$sql = "INSERT INTO HELPDESK
							(MASTERID,
							".$field.",
							USERID,
							DESCRIPTION,
							PRIORITY,
							JOB_CATEGORY,
							JOB_STATUS,
							DATE_CREATED,
							LAST_CHANGED,
							TS_CONTACT)
						(SELECT
								'".$job_id."',
								".$value.",
								'".$userid."',
								'".$description."',
								'".$priority."',
								'".$category."',
								'".$status."',
								SYSDATE,
								SYSDATE,
								NVL('".$ts_contact."', PRIMARY_CONTACT)
							FROM HELPDESK_CATEGORY
							WHERE CAT_ID = '".$category."')"; 
//				echo $sql."<br>";
				$stid = ociparse($bniconn, $sql);
//				ociexecute($stid);
				ociexecute($stid, OCI_NO_AUTO_COMMIT); 

				$sql = "SELECT IU1.EMAIL_ADDRESS EM_U, IU2.EMAIL_ADDRESS EM1, DECODE(TS_ID, NULL, 'HD' || JOBID, 'TS' || TS_ID) THE_DESC 
							FROM HELPDESK HD, INTRANET_USERS IU1, INTRANET_USERS IU2
							WHERE HD.USERID = IU1.USERNAME
								AND HD.TS_CONTACT = IU2.USERNAME
								AND MASTERID = '".$job_id."'
								AND HD.USERID = '".$userid."'
						ORDER BY MASTERID DESC";
				$maxid = ociparse($bniconn, $sql);
//				ociexecute($stid);
				ociexecute($maxid, OCI_NO_AUTO_COMMIT);
				if(!ocifetch($maxid)){
					// if the sequence broke somehow, we don't want to proceed.
					echo "<font color=\"#FF0000\">There was an error saving your Helpdesk.<br>Please use your browser's Back button, and attempt to resubmit.<br>If you get this message more than 2 times, contact TS.</font>";
					exit;
				}

				$primary_email = ociresult($maxid, "EM1");
//				$job_id = ociresult($maxid, "THE_MAX");
				$desc_id = ociresult($maxid, "THE_DESC");
				$user_email = ociresult($maxid, "EM_U");

				$sql = "SELECT IU2.EMAIL_ADDRESS EM2, HC_DESCRIPTION
						FROM INTRANET_USERS IU2, HELPDESK_CATEGORY HC
						WHERE HC.SECOND_CONTACT = IU2.USERNAME
							AND HC.CAT_ID = '".$category."'";
				$more_emails = ociparse($bniconn, $sql);
//				ociexecute($stid);
				ociexecute($more_emails, OCI_NO_AUTO_COMMIT);
				ocifetch($more_emails);
//				$primary_email = ociresult($more_emails, "EM1");
				$second_email = ociresult($more_emails, "EM2");
				$cat_text = ociresult($more_emails, "HC_DESCRIPTION");

				$sql = "SELECT HP_DESCRIPTION
						FROM HELPDESK_PRIORITY
						WHERE PRIORITY_ID = '".$priority."'";
				$pri_emails = ociparse($bniconn, $sql);
				ociexecute($pri_emails, OCI_NO_AUTO_COMMIT);
				ocifetch($pri_emails);
				$pri_text = ociresult($pri_emails, "HP_DESCRIPTION");

				$sql = "SELECT * FROM EMAIL_DISTRIBUTION
						WHERE EMAILID = 'HELPDESKADD'";
				$stid = ociparse($rfconn, $sql);
				ociexecute($stid, OCI_NO_AUTO_COMMIT);
				ocifetch($stid);

				$mailTO = ociresult($stid, "TO");
				$mailTO = str_replace("_0_", $user_email, $mailTO);
				$mailTO = str_replace("_1_", $primary_email, $mailTO);

				$mailheaders = "From: ".ociresult($stid, "FROM")."\r\n";

				if(ociresult($stid, "CC") != ""){
					$mailheaders .= "Cc: ".ociresult($stid, "CC")."\r\n";
				}
				if(ociresult($stid, "BCC") != ""){
					$mailheaders .= "Bcc: ".ociresult($stid, "BCC")."\r\n";
				}
				$mailheaders = str_replace("_2_", $second_email, $mailheaders);

				$mailSubject = ociresult($stid, "SUBJECT");
				$mailSubject = str_replace("_3_", $desc_id, $mailSubject);

				$body = ociresult($stid, "NARRATIVE");
				$body = str_replace("_br_", "\r\n", $body);
				$body = str_replace("_4_", date('m/d/Y h:i:s a'), $body);
				$body = str_replace("_5_", $description, $body);
				$body = str_replace("_6_", $category."-".$cat_text, $body);
				$body = str_replace("_7_", $priority."-".$pri_text, $body);
				ocicommit($bniconn);

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
								'INSTANTCRON',
								SYSDATE,
								'EMAIL',
								'HELPDESKADD',
								SYSDATE,
								'COMPLETED',
								'".$mailTO."',
								'".ociresult($stid, "CC")."',
								'".ociresult($stid, "BCC")."',
								'".substr($body, 0, 2000)."')";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid, OCI_NO_AUTO_COMMIT);
				}

				echo "Your new Helpdesk has been entered.  Please <a href=\"HD_list.php\">Click Here</a> To return to the HD listing page.<br>An Email has been sent to your inbox confirming the Entry.";
				ocicommit($rfconn);
				include("pow_footer.php");
				exit;
			} else {
				// updating existing job.
				$sql = "UPDATE HELPDESK
						SET USERID = '".$userid."',
							DESCRIPTION = '".$description."',
							PRIORITY = '".$priority."',
							JOB_CATEGORY = '".$category."',
							JOB_STATUS = '".$status."',
							LAST_CHANGED = SYSDATE,
							TS_CONTACT = '".$ts_contact."'
						WHERE MASTERID = '".$job_id."'";
				$stid = ociparse($bniconn, $sql);
				ociexecute($stid, OCI_NO_AUTO_COMMIT);

				if($status == "7" || $status == "8"){
					$sql = "SELECT IU1.EMAIL_ADDRESS EM1, IU2.EMAIL_ADDRESS EM2, IU3.EMAIL_ADDRESS EM3, DECODE(TS_ID, NULL, 'HD' || JOBID, 'TS' || TS_ID) THE_DESC
							FROM HELPDESK HD, INTRANET_USERS IU1, INTRANET_USERS IU2, INTRANET_USERS IU3, HELPDESK_CATEGORY HC
							WHERE HD.MASTERID = '".$job_id."'
								AND HD.USERID = IU1.USERNAME
								AND HD.TS_CONTACT = IU2.USERNAME
								AND HD.JOB_CATEGORY = HC.CAT_ID
								AND HC.SECOND_CONTACT = IU3.USERNAME";
					$more_emails = ociparse($bniconn, $sql);
	//				ociexecute($stid);
					ociexecute($more_emails, OCI_NO_AUTO_COMMIT);
					ocifetch($more_emails);
					$user_email = ociresult($more_emails, "EM1");
					$desc_id = ociresult($more_emails, "THE_DESC");
					$ts_email = ociresult($more_emails, "EM2");
					$second_email = ociresult($more_emails, "EM3");

					$sql = "SELECT * FROM EMAIL_DISTRIBUTION
							WHERE EMAILID = 'HELPDESKCLOSE'";
					$stid = ociparse($rfconn, $sql);
					ociexecute($stid, OCI_NO_AUTO_COMMIT);
					ocifetch($stid);

					$mailTO = ociresult($stid, "TO");
					$mailTO = str_replace("_0_", $user_email, $mailTO);
					$mailTO = str_replace("_1_", $ts_email, $mailTO);

					$mailheaders = "From: ".ociresult($stid, "FROM")."\r\n";

					if(ociresult($stid, "CC") != ""){
						$mailheaders .= "Cc: ".ociresult($stid, "CC")."\r\n";
					}
					if(ociresult($stid, "BCC") != ""){
						$mailheaders .= "Bcc: ".ociresult($stid, "BCC")."\r\n";
					}
					$mailheaders = str_replace("_2_", $second_email, $mailheaders);

					$mailSubject = ociresult($stid, "SUBJECT");
					$mailSubject = str_replace("_3_", $desc_id, $mailSubject);

					$body = ociresult($stid, "NARRATIVE");
					$body = str_replace("_br_", "\r\n", $body);
					$body = str_replace("_3_", $desc_id, $body);
					$body = str_replace("_4_", $user, $body);
					$body = str_replace("_5_", $description, $body);

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
									'INSTANTCRON',
									SYSDATE,
									'EMAIL',
									'HELPDESKADD',
									SYSDATE,
									'COMPLETED',
									'".$mailTO."',
									'".ociresult($stid, "CC")."',
									'".ociresult($stid, "BCC")."',
									'".substr($body, 0, 2000)."')";
						$stid = ociparse($rfconn, $sql);
						ociexecute($stid, OCI_NO_AUTO_COMMIT);
					}
				}

				echo "Helpdesk Saved.  Please <a href=\"HD_list.php\">Click Here</a> To return to the HD listing page.<br>Please check your Inbox for the confirmation Email.";
				ocicommit($bniconn);
				ocicommit($rfconn);
				include("pow_footer.php");
				exit;
			}
		} else {
			echo "<font color=\"#FF0000\">Could not save Helpdesk entry:<br>".$check."</font>";
		}
	}










	if($job_id != "" && $job_id != "New"){
		$sql = "SELECT TO_CHAR(DATE_CREATED, 'MM/DD/YYYY HH24:MI:SS') DATE_CREATE, TO_CHAR(LAST_CHANGED, 'MM/DD/YYYY HH24:MI:SS') LAST_CHANGE, 
					USERID, DESCRIPTION, TS_CONTACT, PRIORITY, JOB_CATEGORY, JOB_STATUS, DECODE(TS_ID, NULL, 'HD' || JOBID, 'TS' || TS_ID) THE_DESC
				FROM HELPDESK
				WHERE MASTERID = '".$job_id."'";
//		echo $sql."<br>";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$userid = ociresult($stid, "USERID");
		$disp_desc = ociresult($stid, "THE_DESC");
		$description = ociresult($stid, "DESCRIPTION");
		$category = ociresult($stid, "JOB_CATEGORY");
		$priority = ociresult($stid, "PRIORITY");
		$status = ociresult($stid, "JOB_STATUS");
		$created = ociresult($stid, "DATE_CREATE");
		$changed = ociresult($stid, "LAST_CHANGE");
		$ts_contact = ociresult($stid, "TS_CONTACT");
	} else {
		$job_id = "New";
		$disp_desc = "New";
		if($check == ""){
			$userid = "";
			$description = "";
			$category = "";
			$priority = "";
			$status = "";
			$created = "";
			$changed = "";
			$ts_contact = "";
		}
	}

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Helpdesk Entry Page
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="HD_enter.php" method="post">
<input type="hidden" name="job_id" value="<? echo $job_id; ?>">
	<tr>
		<td><font size="2" face="Verdana">ID#:  </td>
		<td><font size="2" face="Verdana"><? echo $disp_desc; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">User:  </td>
		<td><select name="userid">
<?
	if($permission == "admin" || $permission == "operator"){
		$sql = "SELECT USERNAME FROM INTRANET_USERS WHERE HELPDESK_ACCESS IN ('admin', 'user', 'operator') ORDER BY USERNAME";
	} else {
		$sql = "SELECT USERNAME FROM INTRANET_USERS WHERE USERNAME = '".$user."' ORDER BY USERNAME";
	}
//	echo $sql."<br>";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "USERNAME"); ?>"<? if(ociresult($stid, "USERNAME") == $userid){?> selected <?}?>><? echo ociresult($stid, "USERNAME"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Description:  </td>
		<td><textarea name="description" cols="100" rows="20"><? echo $description; ?></textarea></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Category:  </td>
		<td><select name="category">
				<option value="">Choose a Category</option>
<?
	$sql = "SELECT * FROM HELPDESK_CATEGORY";
	if($permission != "admin"){
		$sql .= " WHERE CAT_ID != '21'";
	}
	$sql .= " ORDER BY CAT_ID";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "CAT_ID"); ?>"<? if(ociresult($stid, "CAT_ID") == $category){?> selected <?}?>>
									<? echo ociresult($stid, "CAT_ID")."-".ociresult($stid, "HC_DESCRIPTION"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Priority:  </td>
		<td><select name="priority">
				<option value="">Choose a Priority</option>
<?
	$sql = "SELECT * FROM HELPDESK_PRIORITY ORDER BY PRIORITY_ID";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "PRIORITY_ID"); ?>"<? if(ociresult($stid, "PRIORITY_ID") == $priority){?> selected <?}?>>
									<? echo ociresult($stid, "PRIORITY_ID")."-".ociresult($stid, "HP_DESCRIPTION"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Status:  </td>
		<td><select name="status">
<?
	if($permission == "admin" || $permission == "operator"){
		$sql = "SELECT * FROM HELPDESK_STATUS ORDER BY STATUS_ID";
	} else {
		$sql = "SELECT * FROM HELPDESK_STATUS WHERE STATUS_ID = '1' ORDER BY STATUS_ID";
	}
	echo $sql."<br>";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "STATUS_ID"); ?>"<? if(ociresult($stid, "STATUS_ID") == $status){?> selected <?}?>>
									<? echo ociresult($stid, "STATUS_ID")."-".ociresult($stid, "HS_DESCRIPTION"); ?></option>
<?
	}
?>
			</select></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Date created:  </td>
		<td><font size="2" face="Verdana"><? echo $created; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">Last Changed:  </td>
		<td><font size="2" face="Verdana"><? echo $changed; ?></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">TS Contact:  </td>
<? 
	if($permission == "admin" || $permission == "operator"){
?>
		<td><select name="ts_contact">
				<option value="">To Be Assigned By Category</option>
<?
		$sql = "SELECT USERNAME FROM INTRANET_USERS WHERE HELPDESK_ACCESS in ('admin', 'operator') ORDER BY USERNAME";
		$stid = ociparse($bniconn, $sql);
		ociexecute($stid);
		while(ocifetch($stid)){
?>
						<option value="<? echo ociresult($stid, "USERNAME"); ?>"<? if(ociresult($stid, "USERNAME") == $ts_contact){?> selected <?}?>>
									<? echo ociresult($stid, "USERNAME"); ?></option>
<?
		}
?>
			</select></td>
<?
	} else {
?>
		<td><font size="2" face="Verdana">To Be Assigned  </td>
<?
	}
?>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Save Entry"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");





function validate_save($description, $category, $priority, $bniconn){
	$return = "";

	if($description == ""){
		$return .= "You cannot leave the Description Field Blank.<br>";
	}
	if($category == ""){
		$return .= "You did not choose a category.<br>";
	}
	if($priority == ""){
		$return .= "You did not choose a priority.<br>";
	}

	return $return;
}