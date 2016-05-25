<?
/*
*
*	Adam Walter, Oct 2013.
*
*	Set the discharge date of a clementine ship (for scanner/picklist use).
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Clementine";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$vessel = $HTTP_POST_VARS['vessel'];
	$submit = $HTTP_POST_VARS['submit'];
	if($submit != "" && $vessel != ""){
		$discharge_date = $HTTP_POST_VARS['discharge_date'];
		if(!ereg("([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})", $discharge_date)){
			echo "<font color=\"#FF0000\">Date must be in MM/DD/YYYY format.</font>";
		} else {
			$sql = "UPDATE VESSEL_PROFILE
					SET DATE_DISCHARGED = TO_DATE('".$discharge_date."', 'MM/DD/YYYY')
					WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'CLEMDSCHGSAVE'";
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

			$mailSubject = ociresult($email, "SUBJECT");
			$body = ociresult($email, "NARRATIVE");

			$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VES
					FROM VESSEL_PROFILE
					WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);
			ocifetch($stid);

			$body = str_replace("_0_", ociresult($stid, "THE_VES"), $body);
			$body = str_replace("_1_", $discharge_date, $body);
			$body = str_replace("_2_", $user, $body);
			$body = str_replace("_br_", "\r\n", $body);

			$mailSubject = str_replace("_0_", ociresult($stid, "THE_VES"), $mailSubject);
			$mailSubject = str_replace("_1_", $discharge_date, $mailSubject);

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
							'CLEMDSCHGSAVE',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
				$email = ociparse($rfconn, $sql);
				ociexecute($email);
			}

			echo "<font color=\"#0000FF\">Date saved.</font>";
		}
	}
?>
<script language="JavaScript" src="../../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Clementine Vessel Discharge
            </font>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="get_ves" action="clem_ves_discharge.php" method="post">
	<tr>
		<td>LR: <select name="vessel" onchange="document.get_ves.submit(this.form)"><option value="">Select a Vessel</option>
<?
	$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE 
			WHERE SHIP_PREFIX = 'CLEMENTINES' 
			ORDER BY LR_NUM DESC"; 
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "LR_NUM"); ?>"<? if (ociresult($stid, "LR_NUM") == $vessel){?> selected <?}?>><? echo ociresult($stid, "LR_NUM")." - ".ociresult($stid, "VESSEL_NAME"); ?></option>
<?
	}
?>
		</select><hr></td>
	</tr>
</form>
</table>
<?
	if($vessel != ""){
		$sql = "SELECT TO_CHAR(DATE_DISCHARGED, 'MM/DD/YYYY') THE_DATE
				FROM VESSEL_PROFILE
				WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
?>
<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="save_ves" action="clem_ves_discharge.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
	<tr>
		<td>Completion of Discharge Date: <input type="text" name="discharge_date" size="10" maxlength="10" value="<? echo ociresult($stid, "THE_DATE"); ?>"><a href="javascript:show_calendar('save_ves.discharge_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Save Date"></td>
	</tr>
</form>
</table>
<?
	}
	include("pow_footer.php");