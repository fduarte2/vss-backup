<?
/*
*
*	Adam Walter, May 2014.
*
*	A screen for MArketing to "upload" a PPT file for showing in the lobby.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "PPT Upload";
  $area_type = "MKTG";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from MRKT system");
    include("pow_footer.php");
    exit;
  }

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

	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Upload"){
		$start_date = $HTTP_POST_VARS['start_date'];
		$start_time = $HTTP_POST_VARS['start_time'];
		$end_date = $HTTP_POST_VARS['end_date'];
		$end_time = $HTTP_POST_VARS['end_time'];
		$impfilename = date(mdYhis).".".basename($HTTP_POST_FILES['import_file']['name']);
		$impfilename = str_replace(" ", "", $impfilename);
		$target_path_import = "./powerpoints/".$impfilename;

		$valid = Validate($start_date, $start_time, $end_date, $end_time, $impfilename, $bniconn);
		if($valid != ""){
			echo "<font color=\"#FF0000\">".$valid."</font><br>";
		} else {
			if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
				$command = "/bin/chmod 775 $target_path_import";
				echo $command."<br>";
				system($command);
			} else {
				echo "Error on file upload.  Please contact TS";
				exit;
			}

			$sql = "INSERT INTO FRONTDOOR_POWERPOINT
						(FILENAME,
						START_DATE,
						END_DATE)
					VALUES
						('".$impfilename."',
						TO_DATE('".$start_date." ".$start_time."', 'MM/DD/YYYY HH24:MI'),
						TO_DATE('".$end_date." ".$end_time."', 'MM/DD/YYYY HH24:MI'))";
			$insert = ociparse($bniconn, $sql);
			ociexecute($insert);

			echo "<font color=\"#0000FF\">File Uploaded.".$valid."</font><br>";
/*
			$sql = "SELECT * FROM EMAIL_DISTRIBUTION WHERE EMAILID = 'PPUPLOAD'";
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
//			$mailheaders .= "Content-Type: text/html\r\n";

			$mailSubject = ociresult($email, "SUBJECT");
			$mailSubject = str_replace("_0_", $start_date." ".$start_time, $mailSubject);

			$body = ociresult($email, "NARRATIVE");

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
							'PPUPLOAD',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".ociresult($email, "CC")."',
							'".ociresult($email, "BCC")."',
							'".substr($body, 0, 2000)."')";
	//			echo $sql."\n";
				$update = ociparse($rfconn, $sql);
				ociexecute($update);
			} */
		}
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Powerpoint Upload Page.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="get_data" action="ppt_frontdoor_upload.php" method="post">
	<tr>
		<td align="left" colspan="3"><font size="3" face="Verdana">Select File:</font></td>
	</tr>
	<tr>
		<td align="left" colspan="3"><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana">Start On:</font></td>
		<td width="10%"><input type="text" name="start_date" size="10" maxlength="10">&nbsp;<a href="javascript:show_calendar('get_data.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a><font size="1">(MM/DD/YYYY)</font></td>
		<td><input type="text" name="start_time" size="5" maxlength="5">&nbsp;<font size="1">(24H:MI)</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">End On:</font></td>
		<td><input type="text" name="end_date" size="10" maxlength="10">&nbsp;<a href="javascript:show_calendar('get_data.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a><font size="1">(MM/DD/YYYY)</font></td>
		<td><input type="text" name="end_time" size="5" maxlength="5">&nbsp;<font size="1">(24H:MI)</font></td>
	</tr>
	<tr>
		<td align="left" colspan="3"><input type="submit" name="submit" value="Upload"><hr></td>
	</tr>
</form>
</table>

<table border="1">
	<tr>
		<td align="center" colspan="3"><font size="3" face="Verdana">Current Powerpoint Files</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">File Name</font></td>
		<td><font size="2" face="Verdana">Start On</font></td>
		<td><font size="2" face="Verdana">End On</font></td>
	</tr>
<?
	$sql = "SELECT FILENAME, TO_CHAR(START_DATE, 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(END_DATE, 'MM/DD/YYYY HH24:MI') THE_END
			FROM FRONTDOOR_POWERPOINT
			WHERE START_DATE >= SYSDATE
				OR (START_DATE <= SYSDATE
					AND END_DATE >= SYSDATE)
			ORDER BY START_DATE DESC";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		echo "<tr><td colspan=\"3\"><font size=\"2\" face=\"Verdana\"><b>No Pending Powerpoint Files</b></font></td></tr>";
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "FILENAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_START"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_END"); ?></font></td>
	</tr>
<?
		} while(ocifetch($stid));
	}
?>
</table>
<hr>
<table border="1">
	<tr>
		<td align="center" colspan="3"><font size="3" face="Verdana">Finished Powerpoint Files</font></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana">File Name</font></td>
		<td><font size="2" face="Verdana">Started On</font></td>
		<td><font size="2" face="Verdana">Ended On</font></td>
	</tr>
<?
	$sql = "SELECT FILENAME, TO_CHAR(START_DATE, 'MM/DD/YYYY HH24:MI') THE_START, TO_CHAR(END_DATE, 'MM/DD/YYYY HH24:MI') THE_END
			FROM FRONTDOOR_POWERPOINT
			WHERE END_DATE <= SYSDATE
			ORDER BY START_DATE DESC";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	if(!ocifetch($stid)){
		echo "<tr><td colspan=\"3\"><font size=\"2\" face=\"Verdana\"><b>No Finished Powerpoint Files</b></font></td></tr>";
	} else {
		do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "FILENAME"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_START"); ?></font></td>
		<td><font size="2" face="Verdana"><? echo ociresult($stid, "THE_END"); ?></font></td>
	</tr>
<?
		} while(ocifetch($stid));
	}
?>
</table>

<?
	include("pow_footer.php");






















function Validate($start_date, $start_time, $end_date, $end_time, $impfilename, $bniconn){
	$return = "";

	if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $start_date)){
		$return .= "Start Date must be in MM/DD/YYYY format.<br>";
	}

	if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $end_date)){
		$return .= "End Date must be in MM/DD/YYYY format.<br>";
	}

	if(!ereg("^[0-2]{0,1}[0-9]{1}:[0-9]{1,2}$", $start_time)){
		$return .= "Start Time must be in hh:mi format (24-hour clock).<br>";
	}

	if(!ereg("^[0-2]{0,1}[0-9]{1}:[0-9]{1,2}$", $end_time)){
		$return .= "End Time must be in hh:mi format (24-hour clock).<br>";
	}

	if(substr($impfilename, -4) != "pptx"){
		$return .= "Imported File must be a 2007-or-later Powerpoint file (ending in .pptx).<br>";
	}

	if($return != ""){
		// kick it back, because the next checks require the date and time to at least be correctly formatted
		return $return;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM DUAL WHERE TO_DATE('".$end_date." ".$end_time."', 'MM/DD/YYYY HH24:MI') < SYSDATE";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		$return .= "The Ending Date/Time cannot be earlier than right now.<br>";
	}
	$sql = "SELECT COUNT(*) THE_COUNT FROM DUAL WHERE TO_DATE('".$end_date." ".$end_time."', 'MM/DD/YYYY HH24:MI') < TO_DATE('".$start_date." ".$start_time."', 'MM/DD/YYYY HH24:MI')";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		$return .= "The Ending Date/Time cannot be earlier than the starting time.<br>";
	}

	$sql = "SELECT COUNT(*) THE_COUNT 
			FROM FRONTDOOR_POWERPOINT
			WHERE TO_DATE('".$start_date." ".$start_time."', 'MM/DD/YYYY HH24:MI') <= END_DATE
				AND TO_DATE('".$end_date." ".$end_time."', 'MM/DD/YYYY HH24:MI') >= START_DATE";
	$stid = ociparse($bniconn, $sql);
	ociexecute($stid);
	ocifetch($stid);
	if(ociresult($stid, "THE_COUNT") >= 1){
		$return .= "The Time frame entered conflicts with another file's timing.<br>";
	}

	return $return;
}

?>