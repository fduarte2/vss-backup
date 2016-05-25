<?
/*
*	Adam Walter, Sep 2013.
*
*	Page to swap HTH and Swing load status.
*************************************************************************/

	include("can_db_def.php");
//	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
//	if($rfconn < 1){
//		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
//		exit;
//	}

	$vessel = $HTTP_GET_VARS['vessel'];
	$cont = $HTTP_GET_VARS['cont'];
	$bol = $HTTP_GET_VARS['bol'];
	$sql2 = "";
	$sql3 = "";
	$sql4 = "";

	$submit = $HTTP_POST_VARS['submit'];
	if($submit != ""){
		$vessel = $HTTP_POST_VARS['vessel'];
		$cont = $HTTP_POST_VARS['cont'];
		$bol = $HTTP_POST_VARS['bol'];
		$comments = $HTTP_POST_VARS['comments'];
		$sql = "SELECT CARGO_MODE, NVL(TO_CHAR(GATEPASS_PDF_DATE), 'NONE') GATEPASS_CHECK,
				DECODE(AMS_STATUS, NULL, 'ON HOLD', TO_CHAR(AMS_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_AMS,
				DECODE(LINE_STATUS, NULL, 'ON HOLD', TO_CHAR(LINE_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_LINE,
				DECODE(BROKER_STATUS, NULL, 'ON HOLD', TO_CHAR(BROKER_STATUS, 'MM/DD/YYYY HH:MI AM')) THE_BROKER
				FROM CANADIAN_LOAD_RELEASE
				WHERE ARRIVAL_NUM = '".$vessel."'
					AND CONTAINER_NUM = '".$cont."'
					AND BOL = '".$bol."'";
		$short_term_data = ociparse($rfconn, $sql);
		ociexecute($short_term_data);
		ocifetch($short_term_data);
		$gatepass_check = ociresult($short_term_data, "GATEPASS_CHECK");
		$prev_mode = ociresult($short_term_data, "CARGO_MODE");
		$prev_ams = ociresult($short_term_data, "THE_AMS");
		$prev_line = ociresult($short_term_data, "THE_LINE");
		$prev_ohl = ociresult($short_term_data, "THE_BROKER");

		if($gatepass_check != "NONE"){
			echo "<font color=\"#FF0000\">GatePass has been issued while this screen was open.  Cannot alter mode.</font>";
		} elseif($comments == ""){
			echo "<font color=\"#FF0000\">A reason must be entered.</font>";
		} else {
			$sql = "UPDATE CANADIAN_LOAD_RELEASE SET ";
			if($prev_mode == "HTH"){
				$sql .= "CARGO_MODE = 'SWING', ";
				$desc_append = "From HTH to SWING";
			} elseif($prev_mode == "SWING"){
				$sql .= "CARGO_MODE = 'HTH', ";
				$desc_append = "From SWING to HTH";
			} else {
				echo "<font color=\"#FF0000\">Error changing Cargo Mode.  Please contact TS.</font>";
				exit;
			}
			if($prev_ams != "ON HOLD"){
				$sql .= "AMS_STATUS = NULL, AMS_COMMENTS = 'Cleared by ".$user." during Cargo Mode change', ";
				$sql2 = "INSERT INTO CANADIAN_RELEASE_HISTORY
							(ARRIVAL_NUM,
							ACTIVITY_DATE,
							CONTAINER_NUM,
							BOL,
							USER_ID,
							COMMENTS,
							RELEASE_SECTION,
							ACTIVITY_DESC)
						VALUES
							('".$vessel."',
							SYSDATE,
							'".$cont."',
							'".$bol."',
							'".$user."',
							'".$comments."',
							'AMS',
							'Cleared by ".$user." during Cargo Mode change ".$desc_append."')";
			}
			if($prev_line != "ON HOLD"){
				$sql .= "LINE_STATUS = NULL, LINE_COMMENTS = 'Cleared by ".$user." during Cargo Mode change', ";
				$sql3 = "INSERT INTO CANADIAN_RELEASE_HISTORY
							(ARRIVAL_NUM,
							ACTIVITY_DATE,
							CONTAINER_NUM,
							BOL,
							USER_ID,
							COMMENTS,
							RELEASE_SECTION,
							ACTIVITY_DESC)
						VALUES
							('".$vessel."',
							SYSDATE,
							'".$cont."',
							'".$bol."',
							'".$user."',
							'".$comments."',
							'LINE',
							'Cleared by ".$user." during Cargo Mode change ".$desc_append."')";
			}
			if($prev_ohl != "ON HOLD"){
				$sql .= "BROKER_STATUS = NULL, BROKER_COMMENTS = 'Cleared by ".$user." during Cargo Mode change', T_AND_E = NULL, T_AND_E_DISPLAY = NULL, T_E_SIGNAUTH_BY = NULL, T_E_YES_NO = NULL, ";
				$sql4 = "INSERT INTO CANADIAN_RELEASE_HISTORY
							(ARRIVAL_NUM,
							ACTIVITY_DATE,
							CONTAINER_NUM,
							BOL,
							USER_ID,
							COMMENTS,
							RELEASE_SECTION,
							ACTIVITY_DESC)
						VALUES
							('".$vessel."',
							SYSDATE,
							'".$cont."',
							'".$bol."',
							'".$user."',
							'".$comments."',
							'OHL',
							'Cleared by ".$user." during Cargo Mode change ".$desc_append."')";
			}
			$sql .= "MOST_REC_CHANGER_ID = '".$user."'
					WHERE ARRIVAL_NUM = '".$vessel."'
						AND CONTAINER_NUM = '".$cont."'
						AND BOL = '".$bol."'";
//			echo $sql."<br>";
			$short_term_data = ociparse($rfconn, $sql);
			ociexecute($short_term_data);
			if($sql2 != ""){
				$short_term_data = ociparse($rfconn, $sql2);
				ociexecute($short_term_data);
			}
			if($sql3 != ""){
				$short_term_data = ociparse($rfconn, $sql3);
				ociexecute($short_term_data);
			}
			if($sql4 != ""){
				$short_term_data = ociparse($rfconn, $sql4);
				ociexecute($short_term_data);
			}
			echo "<font color=\"#0000FF\"><b>Cargo Mode Changed.</b></font>";
			
			SendModeMail($cont, $vessel, $bol, $comments, $user, $prev_mode, $rfconn);
		}
	}
				
	
	
	
	$sql = "SELECT CARGO_MODE
			FROM CANADIAN_LOAD_RELEASE
			WHERE ARRIVAL_NUM = '".$vessel."'
				AND CONTAINER_NUM = '".$cont."'
				AND BOL = '".$bol."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$current_mode = ociresult($short_term_data, "CARGO_MODE");

	$sql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE 
			WHERE LR_NUM = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vesname = ociresult($short_term_data, "THE_VESSEL");

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Cargo Mode Change</font><font size="3" face="Verdana" color="#0066CC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: <? echo strtoupper($user);?>.&nbsp;&nbsp;&nbsp;&nbsp;Screen Refresh Date and Time: <? echo date('m/d/Y h:i:s a'); ?><br>&nbsp;&nbsp;&nbsp;PoW Vessel#:&nbsp;&nbsp;&nbsp;<? echo $vesname; ?><br>&nbsp;&nbsp;&nbsp;Container:&nbsp;&nbsp;&nbsp;<? echo $cont; ?><br>&nbsp;&nbsp;&nbsp;BoL:&nbsp;&nbsp;&nbsp;<? echo $bol; ?>
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($mode_auth != "Y"){
		echo "<font color=\"#FF0000\">This user is not authorized for this page.  Cancelling script.</font>";
		exit;
	}
?>	
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="2"><font size="4" face="Verdana"><a href="canadian_scoreboard_index.php?vessel=<? echo $vessel;?>">Click Here</a> to return to the Main Board.</font><hr></td>
	</tr>
<form name="get_data" action="mode_change_index.php" method="post">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="cont" value="<? echo $cont; ?>">
<input type="hidden" name="bol" value="<? echo $bol; ?>">
	<tr>
		<td width="15%"><font size="2" face="Verdana">Current Mode:</font></td>
		<td><font size="2" face="Verdana"><? echo $current_mode; ?></font></td>
	</tr>
	<tr>
		<td width="15%"><font size="2" face="Verdana">Notes (required):</font></td>
		<td><input type="text" name="comments" size="100" maxlength="200"></td>
	</tr>
<?
	if($current_mode == "SWING"){
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Change to HTH"></td>
	</tr>
<?
	}
	if($current_mode == "HTH"){
?>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Change to SWING"></td>
	</tr>
<?
	}
?>
</form>
</table>



<?
function SendModeMail($cont, $vessel, $bol, $comments, $user, $prev_mode, $rf_conn){
	$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'CANMODECHANGE'";
	$email = ociparse($rf_conn, $sql);
	ociexecute($email);
	ocifetch($email);

	if($prev_mode == "HTH"){
		$new_mode = "SWING";
	}else{
		$new_mode = "HTH";
	}

	$mailTO = ociresult($email, "TO");
	$mailheaders = "From: ".ociresult($email, "FROM")."\r\n";
	if(ociresult($email, "CC") != ""){
		$mailheaders .= "Cc: ".ociresult($email, "CC")."\r\n";
	}
	if(ociresult($email, "BCC") != ""){
		$mailheaders .= "Bcc: ".ociresult($email, "BCC")."\r\n";
	}

	$mailSubject = ociresult($email, "SUBJECT");
	$mailSubject = str_replace("_0_", $cont, $mailSubject);

	$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) = '".$vessel."'";
	$short_term_data = ociparse($rfconn, $sql);
	ociexecute($short_term_data);
	ocifetch($short_term_data);
	$vesname = ociresult($short_term_data, "VESSEL_NAME");

	$body = ociresult($email, "NARRATIVE");
	$body = str_replace("_0_", $cont."\r\n", $body);
	$body = str_replace("_1_", $vessel." - ".$vesname."\r\n", $body);
	$body = str_replace("_2_", $bol."\r\n", $body);
	$body = str_replace("_3_", $user."\r\n", $body);
	$body = str_replace("_4_", $comments."\r\n", $body);
	$body = str_replace("_5_", $prev_mode."\r\n", $body);
	$body = str_replace("_6_", $new_mode."\r\n\r\n\r\n", $body);

//	echo "premail<br>mailto:".$mailTO."<br>";
//	if(mail($mailTO, $mailSubject, $body, $mailheaders)){
//	echo "postmail<br>mailto:".$mailTO."<br>";
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
					JOB_BODY,
					VARIABLE_LIST)
				VALUES
					(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
					'INSTANTCRON',
					SYSDATE,
					'EMAIL',
					'CANMODECHANGE',
					SYSDATE,
					'PENDING',
					'".$mailTO."',
					'".ociresult($email, "CC")."',
					'".ociresult($email, "BCC")."',
					'".substr($body, 0, 2000)."',
					'".$cont."')";
		$email = ociparse($rf_conn, $sql);
		ociexecute($email);
//	}
}
